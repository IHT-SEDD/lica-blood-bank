// Ganti ukuran font aktif (f = jenis font, size = ukuran dot)
const font = (size, f = 0) => `^CF${f},${size}`;
const field = (x, y, text) => `^FO${x},${y}^FD${text}^FS`;
const fieldFH = (x, y, text) => `^FO${x},${y}^FH^FD${text}^FS`;
const zplEncode = (char) =>
    [...new TextEncoder().encode(char)]
        .map((b) => `_${b.toString(16).toUpperCase().padStart(2, "0")}`)
        .join("");
const DEGREE = zplEncode("°");

// Gambar garis horizontal; ubah w untuk lebar, t untuk ketebalan
const hline = (x, y, w = 700, h = 3, t = 3) =>
    `^FO${x},${y}^GB${w},${h},${t}^FS`;

// Cetak satu baris: [label]  [:]  [nilai], labelSize & valueSize bisa diubah jika butuh ukuran font berbeda
const row = (
    labelX,
    colonX,
    valueX,
    y,
    label,
    value,
    labelSize = 25,
    valueSize = 26,
) =>
    font(labelSize) +
    field(labelX, y, label) +
    field(colonX, y, ":") +
    font(valueSize) +
    field(valueX, y, value);
const bloodInfoRow = (y, label, value) => row(50, 230, 250, y, label, value);
const patientRow = (y, label, value, labelSize = 25, valueSize = 26) =>
    row(50, 260, 280, y, label, value, labelSize, valueSize);
const bloodHeader = () =>
    font(35) +
    field(65, 40, "INSTALASI PELAYANAN DARAH RUMAH SAKIT") +
    field(200, 80, "UMUM DAERAH INDRAMAYU") +
    font(20) +
    field(180, 120, "Jl. Murah Nara No. 7 Telp. (0234) 272655 - Indramayu") +
    hline(50, 150);
const cliaRows = (clia) => {
    const { reactive, non_reactive } = clia;
    if (reactive !== null && non_reactive === null) {
        return bloodInfoRow(380, "Reaktif", reactive);
    }
    if (non_reactive !== null && reactive === null) {
        return bloodInfoRow(380, "Non Reaktif", non_reactive);
    }
    return (
        bloodInfoRow(380, "Non Reaktif", non_reactive ?? "-") +
        bloodInfoRow(420, "Reaktif", reactive ?? "-")
    );
};

export function buildZplBarcodeBlood(item) {
    return [
        "^XA",
        "^PW799", // lebar cetak (dot); sesuaikan dengan lebar kertas printer
        "^CI28", // Unicode UTF-8
        "^LH0,0", // titik awal cetak (kiri atas)
        bloodHeader(),
        bloodInfoRow(180, "No. Kantong", item.bag_number),
        font(60) + field(610, 180, item.component),
        font(80) + field(610, 250, `${item.blood_group}${item.blood_rhesus}`),
        font(50) + field(590, 330, `${item.blood_volume} CC`),
        bloodInfoRow(220, "Tanggal Aftap", item.aftap_date),
        bloodInfoRow(260, "Tanggal Proses", item.process_date),
        bloodInfoRow(300, "Tanggal Expire", item.expiry_date),
        font(25) +
            field(50, 340, "Suhu Simpan") +
            field(230, 340, ":") +
            font(26) +
            fieldFH(
                250,
                340,
                `${item.storage_temp_from ?? "-"}-${item.storage_temp_to ?? "-"}${DEGREE}C`,
            ),
        cliaRows(item.clia),

        // Barcode nomor kantong
        // BY: lebar modul, rasio, tinggi — BCN: Code-128, tanpa teks di bawah
        "^BY3,3,70",
        font(0) + `^FO50,420^BCN,70,N,N,N^FD${item.bag_number}^FS`,

        hline(50, 510),
        patientRow(530, "Tanggal diberikan", item.released_at),
        patientRow(570, "Nama O.S", item.patient_name),
        patientRow(605, "No. Register", item.patient_medrec),
        patientRow(640, "No. BDRS", item.patient_lab_number),
        patientRow(
            675,
            "Ruangan/Kelas",
            `${item.room_name} / ${item.room_class}`,
        ),
        patientRow(
            710,
            "Gol. Rh",
            `${item.patient_blood_group} ${item.patient_blood_rhesus}`,
        ),

        hline(50, 745),
        row(
            50,
            400,
            425,
            770,
            "Crossmatching dijalankan oleh",
            item.crossmatch_by,
            27,
            26,
        ),
        font(27) +
            field(50, 800, "Hasil") +
            field(115, 800, ":") +
            font(26) +
            field(135, 800, item.crossmatch_result) +
            field(270, 800, "/") +
            field(300, 800, item.crossmatch_finish_at),

        hline(50, 850),
        row(50, 220, 240, 870, "No. Kantong", item.bag_number, 23, 24),
        font(40) +
            field(
                620,
                940,
                `${item.component} ${item.blood_group}${item.blood_rhesus}`,
            ),
        font(40) + field(620, 980, `${item.blood_volume} CC`),
        row(
            50,
            220,
            240,
            900,
            "Tgl. Aftap/Proses",
            `${item.aftap_date} / ${item.process_date}`,
            23,
            24,
        ),
        row(50, 220, 240, 930, "Tgl. Expire", item.expiry_date, 23, 24),
        row(50, 220, 240, 960, "Tgl. Diberikan", item.released_at, 23, 24),
        row(
            50,
            220,
            240,
            990,
            "Nama O.S",
            `${item.patient_name} / ${item.patient_birthdate}`,
            23,
            24,
        ),
        row(50, 220, 240, 1020, "No. BDRS", item.patient_lab_number, 23, 24),
        row(400, 540, 570, 1020, "No. Register", item.patient_medrec, 23, 24),
        row(
            50,
            220,
            240,
            1050,
            "Ruangan/Kelas",
            `${item.room_name} / ${item.room_class}`,
            23,
            24,
        ),
        row(
            50,
            220,
            240,
            1080,
            "Gol. Rh/Tes Oleh",
            `${item.patient_blood_group}${item.patient_blood_rhesus} / `,
            23,
            24,
        ),
        row(
            50,
            220,
            240,
            1110,
            "Hasil Crossmatch",
            `${item.crossmatch_result} / ${item.crossmatch_finish_at}`,
            23,
            24,
        ),
        row(50, 220, 240, 1140, "Oleh", item.crossmatch_by, 23, 24),

        "^XZ",
    ].join("");
}
export function buildZplDefault(item, padleft) {
    const { patient_name, no_lab, medrec, birth_date, gender } = item;
    const barcodeX = Math.floor((padleft - 200) / 2);

    return [
        "^XA",
        `^CFB,20^FB${padleft},1,0,C`,
        field(0, 15, patient_name.substring(0, 20)),
        "^BY2,3,70",
        `^FO${barcodeX},40^BC^FD${no_lab.substring(4)}^FS`,
        `^FB${padleft},1,0,C`,
        "^CFA,20",
        field(0, 135, `${medrec.substring(0, 20)} / ${birth_date} / ${gender}`),
        "^XZ",
    ].join("");
}
export function buildZplBarcodeRelease(item) {
    const { bag_number, received_by, released_by, released_at } = item;
    const truncate = (text, length = 13) => text?.substring(0, length) ?? "-";
    return [
        "^XA",
        "^CFB,20",
        `^FB420,1,0,C`,
        `^FO0,15^FD${bag_number}^FS`,
        `^FB420,1,0,C`,
        `^FO0,60^FD${truncate(received_by)}^FS`,
        `^FB420,1,0,C`,
        `^FO0,93^FD${truncate(released_by)}^FS`,
        "^CFB,16",
        `^FB420,1,0,C`,
        `^FO0,135^FD${released_at}^FS`,
        "^XZ",
    ].join("\n");
}
