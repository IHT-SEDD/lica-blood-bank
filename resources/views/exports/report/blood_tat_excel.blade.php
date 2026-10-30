@php
$headerStyle = 'font-weight:bold; text-align:center; vertical-align:middle;
background-color:#f58700; border:1px solid #000; white-space:nowrap;';
$cellStyle = 'text-align:center; border:1px solid #000;';
$totalCols = 9;
@endphp

<table>
 <thead>
  {{-- Judul laporan --}}
  <tr>
   <th colspan="{{ $totalCols }}" style="font-weight:bold; text-align:center; font-size:14pt;">
    {{ $title }}
   </th>
  </tr>
  <tr></tr>

  {{-- Header kolom --}}
  <tr>
   <th style="{{ $headerStyle }}">NO</th>
   <th style="{{ $headerStyle }}">Tanggal</th>
   <th style="{{ $headerStyle }}">No. Labu</th>
   <th style="{{ $headerStyle }}">Komponen</th>
   <th style="{{ $headerStyle }}">Golongan &amp; Rhesus</th>
   <th style="{{ $headerStyle }}">Tgl. Masuk</th>
   <th style="{{ $headerStyle }}">Tgl. Digunakan</th>
   <th style="{{ $headerStyle }}">Tgl. Dikeluarkan</th>
   <th style="{{ $headerStyle }}">TAT</th>
  </tr>
 </thead>

 <tbody>
  {{-- Baris per labu darah --}}
  @foreach ($rows as $index => $row)
  <tr>
   <td style="{{ $cellStyle }}">{{ $index + 1 }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $row['released_date'] }}</td>
   <td style="{{ $cellStyle }}">{{ $row['bag_number'] }}</td>
   <td style="{{ $cellStyle }}">{{ $row['component'] }}</td>
   <td style="{{ $cellStyle }}">{{ $row['blood_type'] }}</td>
   <td style="{{ $cellStyle }}">{{ $row['entered_at'] }}</td>
   <td style="{{ $cellStyle }}">{{ $row['used_at'] }}</td>
   <td style="{{ $cellStyle }}">{{ $row['released_at'] }}</td>
   <td style="{{ $cellStyle }}">{{ $row['tat'] }}</td>
  </tr>
  @endforeach

  {{-- Baris RATA-RATA TAT --}}
  <tr>
   <td colspan="2" style="font-weight:bold; text-align:center; background-color:#f58700; border:1px solid #000;">
    RATA-RATA TAT
   </td>
   <td colspan="7" style="font-weight:bold; background-color:#f58700; {{ $cellStyle }}">
    {{ $averageTat }}
   </td>
  </tr>
 </tbody>
</table>