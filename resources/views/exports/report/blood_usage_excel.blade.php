@php
$compKeys = array_keys($components);
$totalCols = 2 + count($components) * count($bloodGroups) + 1;
$headerStyle = 'font-weight:bold; text-align:center; vertical-align:middle;
background-color:#D9E1F2; border:1px solid #000; white-space:nowrap;';
$cellStyle = 'text-align:center; border:1px solid #000;';
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

  {{-- Baris komponen (span 4 tiap komponen) --}}
  <tr>
   <th rowspan="3" style="{{ $headerStyle }}">NO</th>
   <th rowspan="3" style="{{ $headerStyle }}">Ruangan</th>
   @foreach ($components as $label)
   <th colspan="{{ count($bloodGroups) }}" style="{{ $headerStyle }}">
    {{ $label }}
   </th>
   @endforeach
   <th rowspan="3" style="{{ $headerStyle }}">JUMLAH</th>
  </tr>

  {{-- Baris "Golongan Darah" --}}
  <tr>
   @foreach ($components as $label)
   <th colspan="{{ count($bloodGroups) }}" style="{{ $headerStyle }}">
    Golongan Darah
   </th>
   @endforeach
  </tr>

  {{-- Baris A | B | O | AB --}}
  <tr>
   @foreach ($compKeys as $comp)
   @foreach ($bloodGroups as $group)
   <th style="{{ $headerStyle }}">{{ $group }}</th>
   @endforeach
   @endforeach
  </tr>
 </thead>

 <tbody>
  {{-- Baris per ruangan --}}
  @foreach ($rooms as $index => $room)
  <tr>
   <td style="{{ $cellStyle }}">{{ $index + 1 }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">{{ $room }}</td>
   @foreach ($compKeys as $comp)
   @foreach ($bloodGroups as $group)
   <td style="{{ $cellStyle }}">
    {{ $rows[$room][$comp][$group] ?: '' }}
   </td>
   @endforeach
   @endforeach
   <td style="font-weight:bold; {{ $cellStyle }}">
    {{ $rows[$room]['_total'] ?: '' }}
   </td>
  </tr>
  @endforeach

  {{-- Baris JUMLAH --}}
  <tr>
   <td colspan="2" style="font-weight:bold; text-align:center; background-color:#D9E1F2; border:1px solid #000;">
    JUMLAH
   </td>
   @foreach ($compKeys as $comp)
   @foreach ($bloodGroups as $group)
   <td style="font-weight:bold; background-color:#D9E1F2; {{ $cellStyle }}">
    {{ $totals[$comp][$group] ?: '' }}
   </td>
   @endforeach
   @endforeach
   <td style="font-weight:bold; background-color:#D9E1F2; {{ $cellStyle }}">
    {{ $totals['_grand'] ?: '' }}
   </td>
  </tr>
 </tbody>
</table>