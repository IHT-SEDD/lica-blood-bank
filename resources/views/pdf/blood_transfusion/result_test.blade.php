<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Result</title>
 <style>
  * {
   margin: 0;
   padding: 0;
   box-sizing: border-box;
  }

  body {
   font-family: DejaVu Sans, sans-serif;
   font-size: 11px;
   color: #1a1a1a;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6,
  p {
   margin: 0;
   padding: 0;
   font-weight: normal;
  }

  .page {
   padding: 32px 40px 80px 40px;
  }

  .header {
   margin-bottom: 10px;
   border-bottom: 4px solid #c0392b;
   padding-bottom: 2px;
  }

  .logo {
   height: 100px;
   width: auto;
   object-fit: contain;
  }

  .title {
   font-size: 22px;
   font-weight: bold;
  }

  .subtitle {
   font-size: 12px;
   color: #666;
  }

  .heading {
   font-size: 18px;
   font-weight: bold;
  }

  .heading-2 {
   font-size: 14px;
   font-weight: bold;
  }

  .heading-3 {
   font-size: 12px;
   font-weight: bold;
  }

  .paragraph {
   font-size: 12px;
  }

  .paragraph-2 {
   font-size: 11px;
  }

  .subheading {
   font-size: 10.5px;
   color: #666;
  }

  .text-center {
   text-align: center;
  }

  .text-left {
   text-align: left;
  }

  .text-right {
   text-align: right;
  }

  table {
   width: 100%;
   border-collapse: collapse;
   margin-bottom: 10px;
  }

  .table-order {
   margin-top: 15px;
   margin-bottom: 15px;
   border: 1px solid #ccc;
  }

  .table-order thead tr {
   background: #c0392b;
   color: #fff;
   text-transform: uppercase;
  }

  .table-order th {
   padding: 9px 12px;
   font-size: 10px;
   font-weight: 700;
   border: 1px solid #a93226;
   text-align: center;
  }

  .table-order td {
   padding: 8px 12px;
   border: 1px solid #e0e0e0;
   font-size: 10.5px;
   vertical-align: top;
  }

  .table-order tbody tr:nth-child(even) {
   background: #fafafa;
  }

  .table-order tfoot tr {
   background: #f5f5f5;
  }

  .table-order tfoot td {
   padding: 9px 12px;
   font-size: 11px;
   font-weight: 700;
   border-top: 2px solid #c0392b;
  }

  .footer {
   position: fixed;
   bottom: 20px;
   left: 40px;
   right: 40px;
   margin-top: 32px;
   border-top: 1px solid #e0e0e0;
   padding-top: 10px;
   text-align: center;
   font-size: 9px;
   color: #aaa;
  }
 </style>
</head>
<body>
 <div class="page">

  {{-- Header --}}
  <div class="header">
   <table>
    <tr>
     <td width="10%" align="left">
      <img class="logo" src="{{ asset('assets/images/logos/logo_rsud_indramayu.png') }}"
       alt="Logo RSUD Indramayu - Full Color">
     </td>
     <td width="90%" align="center">
      <div class="heading">{{ __('PEMERINTAH KABUPATEN INDRAMAYU') }}</div>
      <div class="heading">{{ __('DINAS KESEHATAN') }}</div>
      <div class="heading-2">{{ __('UPTD RUMAH SAKIT UMUM DAERAH KABUPATEN INDRAMAYU') }}
      </div>
      <div class="heading-3">{{ __('Jl. Murah Nara No. 7 Indramayu Kode Pos 45222 Jawa
       Barat') }}
      </div>
      <div class="subheading">{{ __('Telp: (0234) 272655, E-mail:
       rsudkabindramayu@yahoo.co.id, Faks:
       (0234) 275330') }}
      </div>
     </td>
    </tr>
   </table>
  </div>
  {{-- //Title --}}
      <table>
   <tr>
    <td width="100%" align="center">
     <div class="heading-2">{{ __('SURAT HASIL UJI SILANG SERASI BANK DARAH RUMAH SAKIT INDRAMAYU') }}</div>
    </td>
   </tr>
  </table>

  {{-- data pasien --}}
  <div>
  <table>
    <tr>
        <td>Nama Pasien </td>
        <td> : Testing</td>
        <td>Tanggal </td>
        <td> : 11/05/2026 </td>
    </tr>
    <tr>
        <td>Tanggal Lahir/Umur </td>
        <td> : 12/12/1986 / 39 Thn 4 Bln 29 Hr</td>
        <td>Diagnosis Kerja </td>
        <td> :  tumpar dd tumed</td>
    </tr>
    <tr>
        <td>No. Rm/No. Lab </td>
        <td> : <b>35768 / 260511083 </b></td>
        <td>Dokter Pengirim </td>
        <td> : dr. Dita Kurnia Sanie, Sp.P</td>
    </tr>
    <tr>
        <td>Alamat Pasien </td>
        <td> : RAMBATAN KULON 40/5 LOHBENER</td>
        <td>Poli / Kelas </td>
        <td> : Klinik DOTS</td>
    </tr>
    <tr>
        <td>Jenis Kelamin </td>
        <td> : Laki-laki</td>
        <td>Jenis Pasien  </td>
        <td> : BPJS / JKN </td>
    </tr>
    <tr>
        <td>Gol. Darah & Rhesus </td>
        <td>: A +</td>
    </tr>
  </table>
  </div>
  <hr>

  {{-- Test Result --}}
  <div>
    <table class="table-order">
        <tr>
            <th width="20%" rowspan="2">No. Kantong Darah</th>
            <th width="20%" rowspan="2">Jenis Kantong Darah</th>
            <th width="20% " colspan="3">Hasil Crossmatch</th>
            {{-- <th width="20%">Hasil</th> --}}
        </tr>
        <tr>
            <th>Mayor</th>
            <th>Minor</th>
            <th>Auto Control</th>
        </tr>
        <tr>
            <td align="center" class="heading-3">S7292010A</td>
            <td align="center" class="heading-3">PRC</td>
            <td class="heading-3">INCOMPATIBLE (1+)</td>
            <td class="heading-3">INCOMPATIBLE (1+)</td>
            <td class="heading-3">INCOMPATIBLE (1+)</td>
        
        </tr>
        <tr>
            <td align="center" class="heading-3">S7282155A</td>
            <td align="center" class="heading-3">PRC</td>
            <td class="heading-3">INCOMPATIBLE (1+)</td>
            <td class="heading-3">INCOMPATIBLE (1+)</td>
            <td class="heading-3">INCOMPATIBLE (1+)</td>
        
        </tr>
        <tr>
            <td align="center" class="heading-3">H7332520A</td>
            <td align="center" class="heading-3">PRC</td>
            <td class="heading-3">INCOMPATIBLE (1+)</td>
            <td class="heading-3">INCOMPATIBLE (1+)</td>
            <td class="heading-3">INCOMPATIBLE (1+)</td>
        
        </tr>
    </table>
    <P>
        Keterangan : <br>
        Telah dilakukan uji silang serasi atas permintaan transfusi darah 
    </P>
  </div>
  <div>
    <table>
        <tr>>Indramayu, 11 Mei 2026</tr>
        <tr align="right">
            <td
                <br><br><br>
                dr. Indriani Silvia, dr, Sp.PK(K)., MKes</td>
        </tr>
    </table>
  </div>
  
 </div>

</body>
</html>