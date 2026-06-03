export function buildZplBarcodeBlood(item) {
    let zpl = "";
    // zpl += "^XA";
    // zpl += "^PW799";
    // zpl += "^LH0,0";

    // // ----- Header -----
    // zpl += "^CF0,50";
    // zpl += "^FO80,40^FDINSTALASI PELAYANAN DARAH^FS";
    // zpl += "^FO85,90^FDRUMAH SAKIT UMUM DAERAH^FS";
    // zpl += "^FO260,140^FDINDRAMAYU^FS";
    // zpl += "^CF0,25";
    // zpl +=
    //     "^FO100,200^FDJl. Murah Nara No. 7 Telp. (0234) 272655 - Indramayu^FS";
    // zpl += "^FO50,240^GB700,3,3^FS";

    // // ----- Kantong Darah -----
    // zpl += "^CF0,25";
    // zpl += "^FO50,265^FDKantong No^FS";
    // zpl += "^FO230,265^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += `^FO250,265^FD${item.bag_number}^FS`;

    // // ----- Golongan Darah -----
    // zpl += "^CF0,60";
    // zpl += `^FO610,270^FD${item.component}^FS`;
    // zpl += "^CF0,80";
    // zpl += `^FO610,325^FD${item.blood_group}${item.blood_rhesus}^FS`;

    // // ----- Tanggal Darah -----
    // zpl += "^CF0,25";
    // zpl += "^FO50,302^FDTanggal Aftap^FS";
    // zpl += "^FO230,302^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += `^FO250,302^FD${item.aftap_date}^FS`;
    // zpl += "^CF0,25";
    // zpl += "^FO50,339^FDTanggal Proses^FS";
    // zpl += "^FO230,339^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += `^FO250,339^FD${item.process_date}^FS`;
    // zpl += "^CF0,25";
    // zpl += "^FO50,378^FDTanggal Expire^FS";
    // zpl += "^FO230,378^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += `^FO250,378^FD${item.expiry_date}^FS`;

    // // ----- Divider -----
    // zpl += "^FO50,415^GB700,3,3^FS";

    // // ----- Pasien -----
    // zpl += "^CF0,25";
    // zpl += "^FO50,440^FDTanggal diberikan^FS";
    // zpl += "^FO260,440^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += "^FO280,440^FD2026-04-01^FS";
    // zpl += "^CF0,25";
    // zpl += "^FO50,477^FDNama O.S^FS";
    // zpl += "^FO260,477^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += `^FO280,477^FD${item.patient_name}^FS`;
    // zpl += "^CF0,25";
    // zpl += "^FO50,514^FDNo. Register^FS";
    // zpl += "^FO260,514^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += `^FO280,514^FD${item.patient_medrec}^FS`;
    // zpl += "^CF0,25";
    // zpl += "^FO50,551^FDRuangan/Kelas^FS";
    // zpl += "^FO260,551^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += `^FO280,551^FD${item.room_name}/${item.room_class}^FS`;

    // // ----- Divider -----
    // zpl += "^FO50,625^GB700,3,3^FS";

    // // ----- Crossmatch -----
    // zpl += "^CF0,27";
    // zpl += "^FO50,650^FDCrossmatching dijalankan oleh^FS";
    // zpl += "^FO410,650^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += `^FO435,650^FD${item.crossmatch_by}^FS`;
    // zpl += "^CF0,27";
    // zpl += "^FO340,687^FDHasil^FS";
    // zpl += "^FO410,687^FD:^FS";
    // zpl += "^CF0,26";
    // zpl += `^FO435,687^FD${item.crossmatch_result}^FS`;

    // // ----- Header Perhatian -----
    // zpl += "^CF0,25";
    // zpl += "^FO50,815^FDPERHATIAN^FS";

    // // ----- Body Perhatian -----
    // zpl += "^CF0,20";
    // zpl +=
    //     "^FO50,850^FDSetiap darah yang akan ditransfusikan pada labelnya harus ditandatangani^FS";
    // zpl +=
    //     "^FO50,875^FDoleh petugas yang mentransfusikan, dengan sebelumnya mencocokkan^FS";
    // zpl +=
    //     "^FO50,900^FDFormulir Permintaan Darah dengan kantong darah, label & identitas OS.^FS";
    // zpl +=
    //     "^FO50,925^FDBila ada ketidakcocokan, segera kembalikan ke Bank Darah RS Setempat.^FS";
    // zpl +=
    //     "^FO50,950^FDLabel ini jangan dilepas dari kantong darah yang sedang ditransfusikan.^FS";

    // // ----- Header Catatan -----
    // zpl += "^CF0,28";
    // zpl += "^FO50,1000^GB700,50,50^FS";
    // zpl += "^CF0,26";
    // zpl += "^FO70,1015^FR";
    // zpl += "^FDCATATAN UNTUK RUMAH SAKIT BILA ADA REAKSI TRANSFUSI^FS";

    // // ----- Body Catatan -----
    // zpl += "^CF0,20";
    // zpl +=
    //     "^FO50,1060^FD1. Gejala-gejala reaksi transfusi : _________________________________^FS";
    // zpl +=
    //     "^FO50,1090^FD2. Label ini, kantong darah dan contoh darah post transfusi harap dikirim ke^FS";
    // zpl +=
    //     "^FO50,1110^FDInstalasi Pelayanan Darah RSUD Indramayu, Jl. Murah Nara No.7 Indramayu^FS";

    // zpl += "^XZ";

    zpl += "^XA";
    zpl += "^PW799";
    zpl += "^LH0,0";

    zpl += "^CF0,35";
    zpl += "^FO65,40^FDINSTALASI PELAYANAN DARAH RUMAH SAKIT^FS";
    zpl += "^FO200,80^FDUMUM DAERAH INDRAMAYU^FS";
    zpl += "^CF0,20";
    zpl +=
        "^FO180,120^FDJl. Murah Nara No. 7 Telp. (0234) 272655 - Indramayu^FS";
    zpl += "^FO50,170^GB700,3,3^FS";

    zpl += "^CF0,25";
    zpl += "^FO50,200^FDKantong No^FS";
    zpl += "^FO230,200^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,200^FD${item.bag_number}^FS`;

    zpl += "^CF0,60";
    zpl += `^FO610,200^FD${item.component}^FS`;
    zpl += "^CF0,80";
    zpl += `^FO610,258^FD${item.blood_group}${item.blood_rhesus}^FS`;
    zpl += "^CF0,50";
    zpl += `^FO590,338^FD${item.blood_volume} CC^FS`;

    zpl += "^CF0,25";
    zpl += "^FO50,237^FDTanggal Aftap^FS";
    zpl += "^FO230,237^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,237^FD${item.aftap_date}^FS`;

    zpl += "^CF0,25";
    zpl += "^FO50,274^FDTanggal Proses^FS";
    zpl += "^FO230,274^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,274^FD${item.process_date}^FS`;

    zpl += "^CF0,25";
    zpl += "^FO50,311^FDTanggal Expire^FS";
    zpl += "^FO230,311^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,311^FD${item.expiry_date}^FS`;

    zpl += "^CF0,25";
    zpl += "^FO50,348^FDSuhu Simpan^FS";
    zpl += "^FO230,348^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,348^FD${item.storage_temp_from}-${item.storage_temp_to}^FS`;

    zpl += "^CF0,25";
    zpl += "^FO50,385^FDNon Reaktif^FS";
    zpl += "^FO230,385^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,385^FD${item.reactive}^FS`;

    zpl += "^BY3,3,70";
    zpl += `^FO50,425^BCN,70,N,N,N^FD${item.bag_number}^FS`;

    zpl += "^FO50,520^GB700,3,3^FS";

    zpl += "^CF0,25";
    zpl += "^FO50,540^FDTanggal diberikan^FS";
    zpl += "^FO260,540^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO280,540^FD${item.released_at}^FS`;

    zpl += "^CF0,25";
    zpl += "^FO50,577^FDNama O.S^FS";
    zpl += "^FO260,577^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO280,577^FD${item.patient_name}^FS`;

    zpl += "^CF0,25";
    zpl += "^FO50,614^FDNo. Register^FS";
    zpl += "^FO260,614^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO280,614^FD${item.patient_medrec}^FS`;

    zpl += "^CF0,25";
    zpl += "^FO50,651^FDRuangan/Kelas^FS";
    zpl += "^FO260,651^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO280,651^FD${item.room_name}/${item.room_class}^FS`;

    zpl += "^FO50,691^GB700,3,3^FS";

    zpl += "^CF0,27";
    zpl += "^FO50,711^FDCrossmatching dijalankan oleh^FS";
    zpl += "^FO400,711^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO425,711^FD${item.crossmatch_by}^FS`;

    zpl += "^CF0,27";
    zpl += "^FO50,740^FDHasil^FS";
    zpl += "^FO115,740^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO135,740^FD${item.crossmatch_result}^FS`;
    zpl += "^FO270,740^FD/^FS";
    zpl += `^FO300,740^FD${item.crossmatch_finish_at}^FS`;

    zpl += "^CF0,25";
    zpl += "^FO50,1030^GB700,3,3^FS";

    zpl += "^CF0,23";
    zpl += "^FO50,1070^FDNama O.S^FS";
    zpl += "^FO260,1070^FD:^FS";
    zpl += "^CF0,24";
    zpl += `^FO280,1070^FD${item.patient_name}^FS`;

    zpl += "^CF0,23";
    zpl += "^FO50,1100^FDNo. Register^FS";
    zpl += "^FO260,1100^FD:^FS";
    zpl += "^CF0,24";
    zpl += `^FO280,1100^FD${item.patient_medrec}^FS`;

    zpl += "^CF0,23";
    zpl += "^FO50,1130^FDRuangan/Kelas^FS";
    zpl += "^FO260,1130^FD:^FS";
    zpl += "^CF0,24";
    zpl += `^FO280,1130^FD${item.room_name}/${item.room_class}^FS`;

    zpl += "^XZ";

    return zpl;
}

export function buildZplBarcodeTransaction(item) {
    let zpl = "";

    zpl += "^XA";
    zpl += "^PW799";
    zpl += "^LH0,0";

    // ----- Header -----
    zpl += "^CF0,50";
    zpl += "^FO80,40^FDINSTALASI PELAYANAN DARAH^FS";
    zpl += "^FO85,90^FDRUMAH SAKIT UMUM DAERAH^FS";
    zpl += "^FO260,140^FDINDRAMAYU^FS";
    zpl += "^CF0,25";
    zpl +=
        "^FO100,200^FDJl. Murah Nara No. 7 Telp. (0234) 272655 - Indramayu^FS";
    zpl += "^FO50,240^GB700,3,3^FS";

    // ----- Info Lab -----
    zpl += "^CF0,25";
    zpl += "^FO50,265^FDBNo. Lab^FS";
    zpl += "^FO230,265^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,265^FD${item.lab_number}^FS`;

    // ----- Info Order & Pasien -----
    zpl += "^CF0,25";
    zpl += "^FO50,302^FDNo. Order^FS";
    zpl += "^FO230,302^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,302^FD${item.order_number}^FS`;
    zpl += "^CF0,25";
    zpl += "^FO50,339^FDNama^FS";
    zpl += "^FO230,339^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,339^FD${item.patient_name}^FS`;
    zpl += "^CF0,25";
    zpl += "^FO50,378^FDRuangan/Kelas^FS";
    zpl += "^FO230,378^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO250,378^FD${item.room_name}/${item.room_class}^FS`;

    // ----- Divider -----
    zpl += "^FO50,415^GB700,3,3^FS";

    // ----- Barcode -----
    zpl += "^CF0,30";
    zpl += "^BY2,3,70";
    zpl += `^FO480,445^BC^FD${item.lab_number}^FS`;

    // ----- Komponen & Darah -----
    zpl += "^CF0,25";
    zpl += "^FO50,440^FDKomponen^FS";
    zpl += "^FO260,440^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO280,440^FD${item.component}^FS`;
    zpl += "^CF0,25";
    zpl += "^FO50,477^FDJumlah^FS";
    zpl += "^FO260,477^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO280,477^FD${item.blood_quantity}^FS`;
    zpl += "^CF0,25";
    zpl += "^FO50,514^FDGolongan Darah^FS";
    zpl += "^FO260,514^FD:^FS";
    zpl += "^CF0,26";
    zpl += `^FO280,514^FD${item.blood_group}, Rh(${item.blood_rhesus})^FS`;

    // ----- Divider -----
    zpl += "^FO50,551^GB700,3,3^FS";

    // ----- Header Perhatian -----
    zpl += "^CF0,25";
    zpl += "^FO50,588^FDPERHATIAN^FS";

    // ----- Body Perhatian -----
    zpl += "^CFN,20";
    zpl +=
        "^FO50,623^FDBerikan label ini ke petugas BDRS untuk pengambilan darah^FS";
    zpl += "^FO50,643^FDHarap simpan label dan jangan sampai hilang^FS";

    zpl += "^XZ";

    return zpl;
}

export function buildZplDefault(item, padleft) {
    const { patient_name, no_lab, medrec, birth_date, gender } = item;
    const barcodeWidth = 200;
    const barcodeX = Math.floor((padleft - barcodeWidth) / 2);

    let zpl = "";

    zpl += "^XA";
    zpl += "^CFB,20";
    zpl += `^FB${padleft},1,0,C`;
    zpl += `^FO0,15^FD${patient_name.substring(0, 20)}^FS`;
    zpl += "^BY2,3,70";
    zpl += `^FO${barcodeX},40^BC^FD${no_lab.substring(4)}^FS`;
    zpl += `^FB${padleft},1,0,C`;
    zpl += "^CFA,20";
    zpl += `^FO0,135^FD${medrec.substring(0, 20)} / ${birth_date} / ${gender}^FS`;
    zpl += "^XZ";

    return zpl;
}
