@php
$compKeys = array_keys($components);
$totalCols = 4 + count($components) * count($bloodGroups) + 1;
$headerStyle = 'font-weight:bold; text-align:center; vertical-align:middle;
background-color:#f58700; border:1px solid #000; white-space:nowrap;';
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
   <th rowspan="3" style="{{ $headerStyle }}">Tanggal</th>
   <th rowspan="3" style="{{ $headerStyle }}">PO</th>
   <th rowspan="3" style="{{ $headerStyle }}">PMI</th>
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
  {{-- Baris per PO --}}
  @foreach ($rows as $index => $row)
  <tr>
   <td style="{{ $cellStyle }}">{{ $index + 1 }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">
    {{ $row['created_at']->format('d-m-Y') }}
   </td>
   <td style="border:1px solid #000; white-space:nowrap; {{ $cellStyle }}">
    {{ $row['po_number'] }}
   </td>
   <td style="border:1px solid #000; white-space:nowrap;">
    {{ $row['vendor_name'] }}
   </td>
   @foreach ($compKeys as $comp)
   @foreach ($bloodGroups as $group)
   <td style="{{ $cellStyle }}">
    {{ $row[$comp][$group] ?: '' }}
   </td>
   @endforeach
   @endforeach
   <td style="font-weight:bold; {{ $cellStyle }}">
    {{ $row['_total'] ?: '' }}
   </td>
  </tr>
  @endforeach

  {{-- Baris JUMLAH --}}
  <tr>
   <td colspan="4" style="font-weight:bold; text-align:center; background-color:#f58700; border:1px solid #000;">
    JUMLAH
   </td>
   @foreach ($compKeys as $comp)
   @foreach ($bloodGroups as $group)
   <td style="font-weight:bold; background-color:#f58700; {{ $cellStyle }}">
    {{ $totals[$comp][$group] ?: '' }}
   </td>
   @endforeach
   @endforeach
   <td style="font-weight:bold; background-color:#f58700; {{ $cellStyle }}">
    {{ $totals['_grand'] ?: '' }}
   </td>
  </tr>
 </tbody>
</table>