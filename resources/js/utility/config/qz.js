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
    const { patient_name, no_lab, medrec, birth_date, gender, blood_group } =
        item;
    const barcodeWidth = 200;
    const barcodeX = Math.floor((padleft - barcodeWidth) / 2);

    let zpl = "";
    switch (printType) {
        case "barcode-blood":
            zpl += "^XA";
            zpl += "^PW799";
            zpl += "^LH0,0";
            zpl += "^FO50,50^A0R,30,30^FDKantong No: ^FS";
            zpl += "^FO50,220^A0R,28,28^FDAPRCP-00002^FS";
            zpl += "^FO50,430^A0R,30,30^FDVolume: ^FS";
            zpl += "^FO50,550^A0R,30,30^FD220 CC^FS";
            zpl += "^XZ";
            // zpl = "^XA";
            // zpl += "^PW799";
            // zpl += "^LH0,0";
            // // ----- Kantong Darah -----
            // zpl += "^CF0,30";
            // zpl += `^FO50,50^FDKantong No: ${item.bag_number} Volume: ${item.blood_volume}CC^FS`;
            // zpl += `^FO50,90^FDGolongan Darah: ${item.blood_group}, Rh(${item.blood_rhesus}) Ditetapkan Oleh: Super Admin^FS`;
            // zpl += `^FO50,120^FDKomponen Darah: ${item.component}^FS`;
            // zpl += `^FO50,160^FDTanggal Aftap / Proses: ${item.aftap_date} / ${item.process_date}^FS`;
            // zpl += "^FO50,200^FDOleh: Super Admin^FS";
            // zpl += `^FO50,240^FDTanggal Expire: ${item.expiry_date}^FS`;

            // // ----- Divider -----
            // zpl += "^FO50,290^GB700,3,3^FS";

            // // ----- Pasien -----
            // zpl += "^CF0,30";
            // zpl += "^FO50,320^FDDarah diberikan pada tanggal: 2026-04-01^FS";
            // zpl += `^FO50,360^FDNama O.S / Jenis Kelamin: ${item.patient_name} / ${item.patient_gender}^FS`;
            // zpl += `^FO50,400^FDNo Register: ${item.patient_medrec} Tgl. Lahir: ${item.patient_birthdate}^FS`;
            // zpl += "^FO50,440^FDNama Suami/Istri: Suami Pasien Uji Coba^FS";
            // zpl += `^FO50,480^FDRSUD Indramayu Ruang: ${item.room_name} Kelas: ${item.room_class}^FS`;

            // // ----- Divider -----
            // zpl += "^FO50,520^GB700,3,3^FS";

            // // ----- Crossmatch-----
            // zpl += "^CF0,30";
            // zpl += "^FO50,550^A0N,28,28^FDCrossmatching *)^FS";
            // zpl += "^FO250,550^A0N,28,28^FDDijalankan^FS";
            // zpl += `^FO380,550^A0N,28,28^FD${item.crossmatch_result}^FS`;
            // zpl += "^FO50,590^A0N,28,28^FDOleh : __________^FS";
            // zpl += "^FO50,640^A0N,28,28^FDCrossmatching *)^FS";
            // zpl += "^FO250,640^A0N,28,28^FDDijalankan^FS";
            // zpl += `^FO380,640^A0N,28,28^FD${item.crossmatch_result}^FS`;
            // zpl += "^FO50,690^A0N,28,28^FDOleh : __________^FS";

            // // ----- Header Perhatian -----
            // zpl += "^FO50,740^A0N,28,28^FR";
            // zpl += "^FDPERHATIAN^FS";
            // // ----- Body Perhatian -----
            // zpl += "^FO50,770^A0N,23,23";
            // zpl +=
            //     "^FDSetiap darah yang akan ditransfusikan pada labelnya harus ditandatangani^FS";
            // zpl += "^FO50,790^A0N,23,23";
            // zpl +=
            //     "^FDoleh petugas yang mentransfusikan, dengan sebelumnya mencocokkan^FS";
            // zpl += "^FO50,820^A0N,23,23";
            // zpl +=
            //     "^FDFormulir Permintaan Darah dengan kantong darah, label & identitas OS.^FS";
            // zpl += "^FO50,850^A0N,23,23";
            // zpl +=
            //     "^FDBila ada ketidakcocokan, segera kembalikan ke Bank Darah RS Setempat.^FS";
            // zpl += "^FO50,880^A0N,23,23";
            // zpl +=
            //     "^FDLabel ini jangan dilepas dari kantong darah yang sedang ditransfusikan.^FS";

            // // ----- Header Catatan -----
            // zpl += "^FO30,930^GB740,50,50^FS";
            // zpl += "^FO45,945^A0N,28,28^FR";
            // zpl += "^FDCATATAN UNTUK RUMAH SAKIT BILA ADA REAKSI TRANSFUSI^FS";
            // // ----- Body Catatan -----
            // zpl += "^FO30,990^A0N,23,23";
            // zpl +=
            //     "^FD1. Gejala-gejala reaksi transfusi : _________________________________^FS";
            // zpl += "^FO30,1020^A0N,23,23";
            // zpl +=
            //     "^FD2. Label ini, kantong darah dan contoh darah post transfusi harap dikirim ke^FS";
            // zpl += "^FO30,1050^A0N,23,23";
            // zpl +=
            //     "^FDInstalasi Pelayanan Darah RSUD Indramayu, Jl. Murah Nara No.7 Indramayu^FS";
            // zpl += "^XZ";
            break;

        default:
            zpl = "^XA";
            zpl += "^CFB,20";
            zpl += `^FB${padleft},1,0,C`;
            zpl += `^FO0,15^FD${patient_name.substring(0, 20)}^FS`;
            zpl += "^BY2,3,70";
            zpl += `^FO${barcodeX},40^BC^FD${no_lab.substring(4)}^FS`;
            zpl += `^FB${padleft},1,0,C`;
            zpl += "^CFA,20";
            zpl += `^FO0,135^FD${medrec.substring(0, 20)} / ${birth_date} / ${gender}^FS`;
            zpl += "^XZ";
            break;
    }

    return zpl;
}

function resolvePrintData(data, printType) {
    switch (printType) {
        case "barcode-blood":
            return (data.blood_details || []).map((detail) => ({
                patient_name: data.patient_name,
                patient_gender: data.patient_gender,
                patient_medrec: data.patient_medrec,
                patient_birthdate: data.patient_birthdate,
                room_name: data.room_name,
                room_class: data.room_class,

                bag_number: detail.bag_number,
                blood_volume: detail.blood_volume,
                blood_group: detail.blood_group,
                blood_rhesus: detail.blood_rhesus,
                component: detail.component,
                aftap_date: detail.aftap_date,
                process_date: detail.process_date,
                expiry_date: detail.expiry_date,
                crossmatch_result: detail.crossmatch_result,
            }));

        default:
            return Array.isArray(data) ? data : [data];
    }
}
