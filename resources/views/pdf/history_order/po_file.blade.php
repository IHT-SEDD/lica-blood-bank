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
      <img class="logo" src="{{ public_path('assets/images/logos/logo_rsud_indramayu.png') }}"
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

  {{-- Title --}}
  <table>
   <tr>
    <td width="100%" align="center">
     <div class="heading-2">{{ __('SURAT PERMINTAAN DARAH') }}</div>
    </td>
   </tr>
  </table>

  {{-- Detail User --}}
  <table width="100%">
   <tr>
    <td colspan="3">
     <div class="paragraph">
      {{ __('Yang bertanda tangan dibawah ini,') }}
     </div>
    </td>
   </tr>
   <tr>
    <td width="17%">
     <div class="paragraph">{{ __('Nama') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="80%">
     <div class="paragraph">{{ $order->users?->name ?? '-' }}</div>
    </td>
   </tr>
   <tr>
    <td width="17%">
     <div class="paragraph">{{ __('Jabatan') }}</div>
    </td>
    <td width="3%">
     <div class="paragraph">:</div>
    </td>
    <td width="80%">
     <div class="paragraph">{{ $order->users?->roles->first()?->name ?? '-' }}</div>
    </td>
   </tr>
  </table>

  {{-- Detail Vendor --}}
  <table>
   <tr>
    <td width="100%" align="left">
     <div class="paragraph">{{ __('Dengan ini kami mengajukan permintaan darah kepada ') }}
      <span class="paragraph" style="font-weight: bold !important;">{{ $vendor?->name ?? '-' }},</span>
      {{ __('dengan detail sebagai berikut :') }}
     </div>
    </td>
   </tr>
  </table>

  {{-- Order Detail --}}
  <table class="table-order">
   <thead>
    <tr>
     <th class="text-center" style="width: 5%;">{{ __('No.') }}</th>
     <th style="width: 35%;">{{ __('Jenis Komponen') }}</th>
     <th class="text-right" style="width: 12%;">{{ __('Jumlah') }}</th>
     <th>{{ __('Catatan') }}</th>
    </tr>
   </thead>

   @php
   $componentLabels = [
   'WB' => 'Whole Blood (WB)',
   'PRC' => 'Packed Red Cell (PRC)',
   'FFP' => 'Fresh Frozen Plasma (FFP)',
   'TC' => 'Thrombocyte Concentrate (TC)',
   'CRYO' => 'Cryoprecipitate (CRYO)',
   'WRC' => 'Washed Red Cell (WRC)',
   ];
   $groupedDetails = collect($details)
   ->groupBy(fn($detail) =>
   $detail->bloodPacks?->blood_component?->value
   );
   @endphp

   <tbody>
    @forelse($groupedDetails as $component => $items)

    {{-- Header Component --}}
    <tr>
     <td class="text-center align-top">
      {{ $loop->iteration }}
     </td>
     <td colspan="3">
      <strong>
       {{ $componentLabels[$component] ?? $component }}
      </strong>
     </td>
    </tr>

    {{-- Detail Blood --}}
    @foreach($items as $detail)
    <tr>
     {{-- Empty column nomor --}}
     <td></td>
     {{-- Golongan darah --}}
     <td>
      @if($detail->bloodPacks)
      <div>
       {{ $detail->bloodPacks->blood_group->value }}
       {{ $detail->bloodPacks->blood_rhesus }}
      </div>
      @else
      -
      @endif
     </td>

     {{-- Quantity --}}
     <td class="text-center">
      {{ number_format($detail->quantity) }}
     </td>

     {{-- Note --}}
     <td>
      {{ $detail->note ?? '-' }}
     </td>
    </tr>
    @endforeach

    @empty
    <tr>
     <td colspan="4" class="text-center" style="color:#aaa;">
      {{ __('No Data Available') }}
     </td>
    </tr>
    @endforelse
   </tbody>

   <tfoot>
    <tr>
     <td colspan="2">{{ __('Jumlah Total') }}</td>
     <td class="text-center">
      {{ number_format($order->total_quantity) }}
     </td>
     <td></td>
    </tr>
   </tfoot>
  </table>

  {{-- Order Text --}}
  <table>
   <tr>
    <td width="100%" align="left">
     <div class="paragraph">{{ __('Untuk keperluan bulan ') }}<strong>{{ now()->format('F Y') }}</strong>
      {{ __(' pada sub bagian') }}<strong>{{ __('Bank Darah Rumah Sakit (BDRS)') }}</strong>
     </div>
     <div class="paragraph">
      {{ __('Demikian surat permohonan ini kami ajukan untuk dapat direalisasikan ') }}
     </div>
    </td>
   </tr>
  </table>

  {{-- Order Description --}}
  @if($order->description)
  <table>
   <tr>
    <td width="100%" align="left">
     <div class="paragraph">{{ __('Description / Notes') }} :</div>
     <div class="paragraph">{{ $order->description }}</div>
    </td>
   </tr>
  </table>
  @endif

  {{-- Signature --}}
  <table style="margin-top: 18px;">
   <tr>
    <td width="65%" align="center">
    </td>
    <td width="35%" align="center">
     <div class="paragraph">{{ __('Indramayu') }}, {{ now()->format('d F Y') }}</div>
     <div class="paragraph">{{ __('Pemohon') }}</div>
     <div class="paragraph">{{ $order->users?->roles->first()?->name ?? '-' }}</div>
     <br>
     <br>
     <br>
     <br>
     <br>
     <br>
     <div class="heading-3">{{ $order->users?->name ?? '_______________' }}</div>
    </td>
   </tr>
  </table>

  {{-- Footer --}}
  <div class="footer">
   {{ __('Generated On') }} {{ now()->format('d F Y, H:i') }} &mdash; {{ $order->po_number }} &mdash; {{ __('LICA Blood
   Bank Information System') }}
  </div>
 </div>
</body>

</html>