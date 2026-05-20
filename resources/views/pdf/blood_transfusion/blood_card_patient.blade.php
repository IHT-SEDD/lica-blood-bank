<!DOCTYPE html>
<html lang="id">

<head>
 <meta charset="UTF-8">
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
   height: 9%;
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

  .table-incompatible {
   margin-top: 15px;
   margin-bottom: 15px;
   border: 1px solid #ccc;
  }

  .table-incompatible thead tr {
   text-transform: uppercase;
  }

  .table-incompatible th {
   padding: 9px 12px;
   font-size: 10px;
   border: 1px solid #e0e0e0;
   font-weight: 700;
   text-align: center;
  }

  .table-incompatible td {
   padding: 8px 12px;
   border: 1px solid #e0e0e0;
   font-size: 10.5px;
   vertical-align: top;
  }

  .table-incompatible tbody tr:nth-child(even) {
   background: #fafafa;
  }

  .table-incompatible tfoot tr {
   background: #f5f5f5;
  }

  .table-incompatible tfoot td {
   padding: 9px 12px;
   font-size: 11px;
   font-weight: 700;
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
      <img class="logo" src="{{ public_path('assets/images/logos/logo_rsud_indramayu.png') }}"
       alt="Logo RSUD Indramayu - Full Color">
     </td>
     <td width="90%" align="center">
      <div class="heading">{{ __('LABORATORIUM PATOLOGI KLINIK') }}</div>
      <div class="heading-2">{{ __('BANK DARAH RUMAH SAKIT (LAYANAN DARAH TRANSFUSI)') }}</div>
      <div class="heading-3">
       {{ __('Jl. Murah Nara No. 7 Indramayu Kode Pos 45222 Jawa Barat') }}
      </div>
      <div class="subheading">
       {{ __('Telp: (0234) 272655, E-mail:rsudkabindramayu@yahoo.co.id, Faks:(0234) 275330') }}
      </div>
     </td>
    </tr>
    <tr>
     <td width="10%"></td>
     <td width="90%" align="right">
      <div class="subheading" style="margin-top: 20px;">
       {{ __('Penanggung Jawab: Indriani Silvia, dr,Sp.PK(K)., MKes') }}
      </div>
     </td>
    </tr>
   </table>
  </div>

  {{-- Title --}}
  <table>
   <tr>
    <td width="100%" align="center">
     <div class="heading-2">{{ __('SURAT HASIL UJI SILANG SERASI') }}</div>
     <div class="heading-2" style="margin-bottom: 15px;">{{ __('BANK DARAH RUMAH SAKIT INDRAMAYU') }}</div>
    </td>
   </tr>
  </table>

  {{-- Detail Transaksi --}}
  <table width="100%">
   <!--- Dokter --->
   <tr>
    <td width="17%">
     <div class="paragraph">{{ __('Kepada Yth') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="80%">
     @if (!empty($data->doctors->name))
     <div class="paragraph">{{ $data->doctors->name ?? '-' }}</div>
     @else
     <div class="paragraph">dr. Sofyan, Sp.PD</div>
     @endif
    </td>
   </tr>

   <!--- Nama --->
   <tr>
    <td width="30%">
     <div class="paragraph">{{ __('Nama') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="67%">
     @if (!empty($data->patients->name))
     <div class="paragraph">{{ $data->patients->name ?? '-' }}</div>
     @else
     <div class="paragraph">RHINO SUWARNO</div>
     @endif
    </td>
   </tr>
   <!--- Rekam Medis --->
   <tr>
    <td width="30%">
     <div class="paragraph">{{ __('Rekam Medis') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="67%">
     @if (!empty($data->patients->medrec))
     <div class="paragraph">{{ $data->patients->medrec ?? '-' }}</div>
     @else
     <div class="paragraph">2308010</div>
     @endif
    </td>
   </tr>
   <!--- No Lab --->
   <tr>
    <td width="30%">
     <div class="paragraph">{{ __('No. Lab') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="67%">
     @if (!empty($data->lab_number))
     <div class="paragraph">{{ $data->lab_number ?? '-' }}</div>
     @else
     <div class="paragraph">260519001</div>
     @endif
    </td>
   </tr>
   <!--- Alamat --->
   <tr>
    <td width="30%">
     <div class="paragraph">{{ __('Alamat') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="67%">
     @if (!empty($data->patients->address))
     <div class="paragraph">{{ $data->patients->address ?? '-' }}</div>
     @else
     <div class="paragraph">LEGOK JAWA BARAT</div>
     @endif
    </td>
   </tr>
   <!--- Jenis Kelamin --->
   <tr>
    <td width="30%">
     <div class="paragraph">{{ __('Jenis Kelamin') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="67%">
     @if (!empty($data->patients->gender))
     <div class="paragraph">
      @if ($data->patients->gender == 'M')
      Laki-laki
      @else
      Perempuan
      @endif
     </div>
     @else
     <div class="paragraph">Laki-laki</div>
     @endif
    </td>
   </tr>
   <!--- Goldar & Rhesus --->
   <tr>
    <td width="30%">
     <div class="paragraph">{{ __('Golongan Darah dan Rhesus') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="67%">
     @if (!empty($dataDetail->blood_pack_id))
     <div class="paragraph">
      {{ $dataDetail->blood_packs->blood_group . $dataDetail->blood_packs->blood_rhesus ?? '-'}}
     </div>
     @else
     <div class="paragraph">B+</div>
     @endif
    </td>
   </tr>
   <!--- Tanggal Pemeriksaan --->
   <tr>
    <td width="30%">
     <div class="paragraph">{{ __('Tanggal Pemeriksaan') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="67%">
     @if (!empty($data->created_at))
     <div class="paragraph">
      {{ $data->created_at ?? '-'}}
     </div>
     @else
     <div class="paragraph">19 Mei 2026</div>
     @endif
    </td>
   </tr>
  </table>

  {{-- Pengantar detail darah --}}
  <table>
   <tr>
    <td width="100%" align="left">
     <div class="paragraph">
      {{ __('Dengan ini kami beritahukan hasil pemeriksaan uji silang (crossmatch) antara darah pasien dan darah
      donor adalah sebagai berikut :') }}
     </div>
    </td>
   </tr>
  </table>

  {{-- Detail darah --}}
  <table class="table-incompatible">
   <thead>
    <tr>
     <th class="text-center" style="width: 12%;">{{ __('No. Kantong') }}</th>
     <th class="text-center" style="width: 3%;">{{ __('Komponen') }}</th>
     <th class="text-center" style="width: 19%;">{{ __('MAYOR') }}</th>
     <th class="text-center" style="width: 19%;">{{ __('MINOR') }}</th>
     <th class="text-center" style="width: 28%;">{{ __('AUTOCONTROL') }}</th>
    </tr>
   </thead>

   <tbody>
    <tr>
     <td class="text-center" align="center">H6982136A</td>
     <td class="text-center" align="center">
      <strong>PRC</strong>
     </td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
    </tr>
    <tr>
     <td class="text-center" align="center">H6982136A</td>
     <td class="text-center" align="center">
      <strong>PRC</strong>
     </td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
    </tr>
    <tr>
     <td class="text-center" align="center">H6982136A</td>
     <td class="text-center" align="center">
      <strong>PRC</strong>
     </td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
    </tr>
    <tr>
     <td class="text-center" align="center">H6982136A</td>
     <td class="text-center" align="center">
      <strong>PRC</strong>
     </td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
     <td class="text-center" align="center">COMPATIBLE (1+)</td>
    </tr>
   </tbody>
  </table>

  {{-- Penjelasan DCT --}}
  <table>
   <tr>
    <td width="100%" align="left">
     <div class="paragraph">
      {{ __('Hasil pemeriksaan Direct Coomb Test (DCT) :') }}
      @if (!empty($data->is_dct) && $data->is_dct == 'true')
      <strong>DILAKUKAN</strong>
      @else
      <strong>TIDAK DILAKUKAN</strong>
      @endif
     </div>
    </td>
   </tr>
  </table>

  {{-- Signature --}}
  <table style="margin-top: 18px;">
   <tr>
    <td width="65%" align="center">
    </td>
    <td width="35%" align="center">
     <div class="paragraph"><strong>{{ __('Indramayu') }}, {{ now()->format('d F Y') }}</strong></div>
     <div class="paragraph"><strong>{{ __('Pemeriksa') }}</strong></div>
     <br>
     <br>
     <br>
     <br>
     <br>
     <br>
     <br>
     <div class="heading-3">
      @if (!empty($data->users->name))
      <strong>{{ $data->users->name ?? '' }}</strong>
      @else
      <strong>_______________</strong>
      @endif
     </div>
    </td>
   </tr>
  </table>

  {{-- Footer --}}
  <div class="footer">
   {{ __('Generated On') }} {{ now()->format('d F Y, H:i') }} &mdash; Crossmatch Result &mdash;
   {{ __('LICA Blood Bank Information System') }}
  </div>
 </div>
</body>

</html>