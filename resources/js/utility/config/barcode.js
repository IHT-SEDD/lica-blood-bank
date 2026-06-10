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
    hline(50, 170);

// Jika hanya reaktif → tampilkan 1 baris "Reaktif"
// Jika hanya non-reaktif → tampilkan 1 baris "Non Reaktif"
// Jika keduanya ada (atau keduanya null) → tampilkan 2 baris
const cliaRows = (clia) => {
    const { reactive, non_reactive } = clia;
    if (reactive !== null && non_reactive === null) {
        return bloodInfoRow(385, "Reaktif", reactive);
    }
    if (non_reactive !== null && reactive === null) {
        return bloodInfoRow(385, "Non Reaktif", non_reactive);
    }
    return (
        bloodInfoRow(385, "Non Reaktif", non_reactive ?? "-") +
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
        bloodInfoRow(200, "Kantong No", item.bag_number),
        font(60) + field(610, 200, item.component),
        font(80) + field(610, 258, `${item.blood_group}${item.blood_rhesus}`),
        font(50) + field(590, 338, `${item.blood_volume} CC`),
        bloodInfoRow(237, "Tanggal Aftap", item.aftap_date),
        bloodInfoRow(274, "Tanggal Proses", item.process_date),
        bloodInfoRow(311, "Tanggal Expire", item.expiry_date),
        // bloodInfoRow(
        //     348,
        //     "Suhu Simpan",
        //     `${item.storage_temp_from}-${item.storage_temp_to}°C`,
        // ),
        font(25) +
            field(50, 348, "Suhu Simpan") +
            field(230, 348, ":") +
            font(26) +
            fieldFH(
                250,
                348,
                `${item.storage_temp_from}-${item.storage_temp_to}${DEGREE}C`,
            ),
        cliaRows(item.clia),

        // Barcode nomor kantong
        // BY: lebar modul, rasio, tinggi — BCN: Code-128, tanpa teks di bawah
        "^BY3,3,70",
        font(0) + `^FO50,455^BCN,70,N,N,N^FD${item.bag_number}^FS`,

        hline(50, 540),
        patientRow(560, "Tanggal diberikan", item.released_at),
        patientRow(597, "Nama O.S", item.patient_name),
        patientRow(634, "No. Register", item.patient_medrec),
        patientRow(671, "No. BDRS", item.patient_lab_number),
        patientRow(
            708,
            "Ruangan/Kelas",
            `${item.room_name}/${item.room_class}`,
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
            field(50, 799, "Hasil") +
            field(115, 799, ":") +
            font(26) +
            field(135, 799, item.crossmatch_result) +
            field(270, 799, "/") +
            field(300, 799, item.crossmatch_finish_at),

        // Salinan info pasien di bagian bawah (font lebih kecil: 23/24)
        hline(50, 1030),
        row(50, 260, 280, 1060, "Nama O.S", item.patient_name, 23, 24),
        row(50, 260, 280, 1090, "No. Register", item.patient_medrec, 23, 24),
        row(50, 260, 280, 1120, "No. BDRS", item.patient_lab_number, 23, 24),
        row(
            50,
            260,
            280,
            1150,
            "Ruangan/Kelas",
            `${item.room_name}/${item.room_class}`,
            23,
            24,
        ),

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
    const label = (lines, fontSize = 50) =>
        [
            "^XA",
            "^CI28",
            "^LH0,0",
            `^CF0,${fontSize}^FB350,1,0,C`,
            ...lines,
            "^XZ",
        ].join("\n");
    const valueX = 40;
    const valueXReleasedAt = 25;
    const centerY = 60;
    const labelBagNumber = label([
        `^FO${valueX},${centerY}^FD${bag_number}^FS`,
    ]);
    const labelReceivedBy = label(
        [`^FO${valueX},${centerY}^FD${received_by.substring(0, 10)}^FS`],
        45,
    );
    const labelReleasedBy = label(
        [`^FO${valueX},${centerY}^FD${released_by.substring(0, 10)}^FS`],
        45,
    );
    const labelReleasedAt = label(
        [`^FO${valueXReleasedAt},${centerY}^FD${released_at}^FS`],
        40,
    );
    return [
        labelBagNumber,
        labelReceivedBy,
        labelReleasedBy,
        labelReleasedAt,
    ].join("\n");
}
