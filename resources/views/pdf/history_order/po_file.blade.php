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
   margin-bottom: 16px;
   border-bottom: 2px solid #c0392b;
   padding-bottom: 16px;
  }

  .logo {
   height: 52px;
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
   font-size: 15px;
   font-weight: bold;
   color: #3d3d3d;
  }

  .heading-3 {
   font-size: 13px;
   font-weight: 700;
   color: #3d3d3d;
  }

  .subheading {
   font-size: 10.5px;
   color: #494949;
  }

  .text-center {
   text-align: center;
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
   margin-top: 22px;
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
   text-align: left;
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
     <td width="70%" align="left">
      <div class="title">{{ __('PURCHASE ORDER') }}</div>
      <div class="subtitle">{{ __('Blood Bank Information System') }}</div>
     </td>
     <td width="30%" align="right">
      <img class="logo" src="{{ public_path('assets/images/logos/logo-lica-bb.png') }}" alt="Logo">
     </td>
    </tr>
   </table>

   <table style="margin-top: 22px;">
    <tr>
     <td width="60%" align="left">
      <div class="heading-2">{{ $order->po_number }}</div>
     </td>
     <td width="40%" align="right">
      <div class="heading-2">
       {{ \Carbon\Carbon::parse($order->created_at)->format('d F Y') }}
      </div>
     </td>
    </tr>
   </table>
  </div>

  {{-- Vendor & Ordered By --}}
  <table>
   <tr>
    <td width="60%" align="left">
     <div class="heading">{{ __('Vendor') }}</div>
     <div class="subheading">{{ $vendor?->name ?? '-' }}</div>
     <div class="subheading">{{ $vendor?->address ?? '-' }}</div>
    </td>

    <td width="40%" align="right">
     <div class="heading">{{ __('Ordered By') }}</div>
     <div class="subheading">{{ $order->users?->name ?? '-' }}</div>
    </td>
   </tr>
  </table>

  {{-- Order Detail --}}
  <table class="table-order">
   <thead>
    <tr>
     <th class="text-center" style="width: 5%;">{{ __('No.') }}</th>
     <th style="width: 35%;">{{ __('Blood Pack') }}</th>
     <th class="text-right" style="width: 12%;">{{ __('Quantity') }}</th>
     <th>{{ __('Note') }}</th>
    </tr>
   </thead>

   <tbody>
    @forelse($details as $i => $detail)
    <tr>
     <td class="text-center">{{ $i + 1 }}</td>

     <td>
      @if($detail->bloodPacks)
      <strong>
       {{ $detail->bloodPacks->blood_group }}{{ $detail->bloodPacks->blood_rhesus }}
      </strong>
      {{ $detail->bloodPacks->blood_component }}
      @else
      -
      @endif
     </td>

     <td class="text-right">
      {{ number_format($detail->quantity) }}
     </td>

     <td>
      {{ $detail->note ?? '-' }}
     </td>
    </tr>
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
     <td colspan="2">{{ __('Total Quantity') }}</td>
     <td class="text-right">
      {{ number_format($order->total_quantity) }}
     </td>
     <td></td>
    </tr>
   </tfoot>
  </table>

  @if($order->description)
  <table>
   <tr>
    <td width="50%" align="left">
     <div class="heading-3">{{ __('Description / Notes') }}</div>
     <div class="subheading">{{ $order->description }}</div>
    </td>
   </tr>
  </table>
  @endif

  {{-- Signature --}}
  <table>
   <tr>
    <td width="50%" align="right">
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