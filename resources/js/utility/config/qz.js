import { buildZplBarcodeBlood, buildZplDefault } from "./barcode";

export const QzManager = (() => {
    const PRINTER_PRIORITIES = ["BarcodeBDRS"];
    const PRINTER_SIZES = {
        BarcodeBDRS: 800,
    };

    let _connecting = false;
    function setupSecurity() {
        qz.security.setCertificatePromise((resolve, reject) => {
            fetch("/vendor/qz/override.crt", {
                cache: "no-store",
                headers: { "Content-Type": "text/plain" },
            })
                .then((res) =>
                    res.ok ? res.text() : Promise.reject("404 - Not Found"),
                )
                .then(resolve)
                .catch(reject);
        });

        qz.security.setSignatureAlgorithm("SHA512");
        qz.security.setSignaturePromise((toSign) => (resolve, reject) => {
            $.post("/vendor/qz/sign-message.php", {
                request: toSign,
            }).then(resolve, reject);
        });
    }

    let _securityReady = false;
    async function connect() {
        if (qz.websocket.isActive()) return;
        if (_connecting) {
            console.warn("Sedang mencoba konek ke QZ Tray, harap tunggu...");
            return;
        }

        if (!_securityReady) {
            setupSecurity();
            _securityReady = true;
        }
        _connecting = true;

        try {
            await qz.websocket.connect();
            const version = await qz.api.getVersion();
            console.log("QZ Tray versi:", version);

            const [major, minor] = version.split(".").map(Number);
            if (major < 2 || (major === 2 && minor < 2)) {
                console.error(
                    "QZ Tray versi terlalu lama, gunakan versi 2.2.5 atau lebih baru.",
                );
            }
        } catch (err) {
            console.error("Gagal konek QZ Tray:", err);
            throw err;
        } finally {
            _connecting = false;
        }
    }

    async function findFirstAvailablePrinter(priorities = PRINTER_PRIORITIES) {
        for (const name of priorities) {
            try {
                const printer = await qz.printers.find(name);
                if (printer) {
                    console.log("Printer ditemukan:", printer);
                    return printer;
                }
            } catch {
                console.warn("Printer tidak ditemukan:", name);
            }
        }
        return null;
    }

    function getPaperWidth(printerName) {
        return PRINTER_SIZES[printerName] ?? null;
    }

    async function sendZpl(data = [], print = null) {
        const items = resolvePrintData(data, print);

        if (!Array.isArray(items) || items.length === 0) return;

        try {
            await connect();
        } catch (err) {
            console.error("QZ Connect Error:", err);
            notyf.error({
                message:
                    "QZ Tray belum dijalankan. Harap jalankan terlebih dahulu",
            });
            return;
        }

        const printerName = await findFirstAvailablePrinter();
        if (!printerName) {
            notyf.error({
                message: "Tidak ada printer yang tersedia.",
            });
            return;
        }

        const padleft = getPaperWidth(printerName);
        if (!padleft) {
            console.error(
                "Ukuran kertas tidak dikenali untuk printer:",
                printerName,
            );
            return;
        }

        const config = qz.configs.create(printerName);

        for (const item of items) {
            const zpl = buildZPL(item, padleft, print);
            console.log(item, zpl);
            const result = [{ type: "raw", format: "plain", data: zpl }];

            try {
                await qz.print(config, result);
                console.log(`Cetak berhasil di ${printerName}`);
                notyf.success({
                    message: `Cetak berhasil di ${printerName}`,
                });
            } catch (err) {
                console.error(`Gagal cetak di ${printerName}:`, err);
                notyf.error({
                    message: `Gagal cetak di ${printerName}:`,
                });
            }
        }
    }

    return { connect, sendZpl };
})();

function buildZPL(item, padleft, printType) {
    switch (printType) {
        case "barcode-blood":
            return buildZplBarcodeBlood(item);
        default:
            return buildZplDefault(item, padleft);
    }
}

function resolvePrintData(data, printType) {
    switch (printType) {
        case "barcode-blood":
            return (data.blood_details || []).map((detail) => ({
                patient_name: data.patient_name,
                patient_gender: data.patient_gender,
                patient_medrec: data.patient_medrec,
                patient_lab_number: data.patient_lab_number,
                patient_birthdate: data.patient_birthdate,
                room_name: data.room_name,
                room_class: data.room_class,

                bag_number: detail.bag_number,
                storage_temp_from: detail.storage_temp_from,
                storage_temp_to: detail.storage_temp_to,
                blood_volume: detail.blood_volume,
                blood_group: detail.blood_group,
                blood_rhesus: detail.blood_rhesus,
                component: detail.component,
                aftap_date: detail.aftap_date,
                process_date: detail.process_date,
                expiry_date: detail.expiry_date,
                crossmatch_result: detail.crossmatch_result,
                crossmatch_finish_at: detail.crossmatch_finish_at,
                crossmatch_by: detail.crossmatch_by,
                released_at: detail.released_at,
                clia: detail.clia,
            }));

        default:
            return Array.isArray(data) ? data : [data];
    }
}
