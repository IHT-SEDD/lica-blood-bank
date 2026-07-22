@php
$headerStyle = 'font-weight:bold; text-align:center; vertical-align:middle;
background-color:#D9E1F2; border:1px solid #000; white-space:nowrap;';
$cellStyle = 'text-align:center; border:1px solid #000;';
@endphp

<table>
 <thead>
  {{-- Judul laporan --}}
  <tr>
   <th colspan="13" style="font-weight:bold; text-align:center; font-size:14pt;">
    {{ $title }}
   </th>
  </tr>
  <tr></tr>

  {{-- Tanggal Mulai --}}
  <tr>
   <th colspan="2" style="font-weight:bold; text-align:center;">Tanggal Mulai</th>
   <th style="text-align:center;">:</th>
   <th colspan="2">{{ $startDate }}</th>
  </tr>
  {{-- Tanggal Akhir --}}
  <tr>
   <th colspan="2" style="font-weight:bold; text-align:center;">Tanggal Akhir</th>
   <th style="text-align:center;">:</th>
   <th colspan="2">{{ $endDate }}</th>
  </tr>
  <tr></tr>

  {{-- Baris komponen (span 4 tiap komponen) --}}
  <tr>
   <th rowspan="2" style="{{ $headerStyle }}">No.</th>
   <th rowspan="2" style="{{ $headerStyle }}">No. BDRS</th>
   <th rowspan="2" style="{{ $headerStyle }}">No. Order</th>
   <th rowspan="2" style="{{ $headerStyle }}">Ruangan</th>
   <th rowspan="2" style="{{ $headerStyle }}">Penjamin</th>
   <th rowspan="2" style="{{ $headerStyle }}">Tgl. Dibuat</th>
   <th rowspan="2" style="{{ $headerStyle }}">No. Labu</th>
   <th rowspan="2" style="{{ $headerStyle }}">Detail Labu</th>
   <th colspan="3" style="{{ $headerStyle }}">Hasil</th>
   <th rowspan="2" style="{{ $headerStyle }}">Hasil Crossmatch</th>
   <th rowspan="2" style="{{ $headerStyle }}">Tgl. Selesai</th>
  </tr>

  {{-- Baris Mayor | Minor | Auto Control --}}
  <tr>
   <th style="{{ $headerStyle }}">Mayor</th>
   <th style="{{ $headerStyle }}">Minor</th>
   <th style="{{ $headerStyle }}">Auto Control</th>
  </tr>
 </thead>

 <tbody>
  @foreach ($dataIncompatibles as $index => $data)
  <tr>
   <td style="{{ $cellStyle }}">{{ $index + 1 }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->lab_number }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->order_number }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->room_name }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->insurance_name }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->created_at }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->bag_number }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">
    {{ $data->blood_component->name . ' ' . $data->blood_group->name . $data->blood_rhesus }}
   </td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->mayor_result }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->minor_result }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->auto_control_result }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->crossmatch_result }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $data->finish_at }}</td>
  </tr>
  @endforeach
 </tbody>
</table>