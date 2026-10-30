@php
// Urutan kolom golongan darah sesuai file Excel: A+, A-, B+, B-, O+, O-, AB+, AB-
$bloodTypes = $bloodTypes ?? collect(['A', 'B', 'O', 'AB'])
->flatMap(fn($g) => [$g . '+', $g . '-'])
->all();

$compKeys = array_keys($components);
$typeCount = count($bloodTypes);
$totalCols = 2 + count($compKeys) * $typeCount + 1;

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

  {{-- Baris 1: NO, Tanggal, nama komponen, JUMLAH --}}
  <tr>
   <th rowspan="3" style="{{ $headerStyle }}">NO</th>
   <th rowspan="3" style="{{ $headerStyle }}">Tanggal</th>
   @foreach ($components as $label)
   <th colspan="{{ $typeCount }}" style="{{ $headerStyle }}">{{ $label }}</th>
   @endforeach
   <th rowspan="3" style="{{ $headerStyle }}">JUMLAH</th>
  </tr>

  {{-- Baris 2: Golongan Darah --}}
  <tr>
   @foreach ($components as $label)
   <th colspan="{{ $typeCount }}" style="{{ $headerStyle }}">Golongan Darah</th>
   @endforeach
  </tr>

  {{-- Baris 3: A+ | A- | B+ | B- | O+ | O- | AB+ | AB- --}}
  <tr>
   @foreach ($compKeys as $comp)
   @foreach ($bloodTypes as $type)
   <th style="{{ $headerStyle }}">{{ $type }}</th>
   @endforeach
   @endforeach
  </tr>
 </thead>

 <tbody>
  {{-- Baris per tanggal --}}
  @foreach ($releaseDates as $index => $date)
  <tr>
   <td style="{{ $cellStyle }}">{{ $index + 1 }}</td>
   <td style="border:1px solid #000; white-space:nowrap;">
    {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
   </td>
   @foreach ($compKeys as $comp)
   @foreach ($bloodTypes as $type)
   <td style="{{ $cellStyle }}">{{ ($rows[$date][$comp][$type] ?? 0) ?: '' }}</td>
   @endforeach
   @endforeach
   <td style="font-weight:bold; {{ $cellStyle }}">{{ ($rows[$date]['_total'] ?? 0) ?: '' }}</td>
  </tr>
  @endforeach

  {{-- Baris JUMLAH --}}
  <tr>
   <td colspan="2" style="font-weight:bold; text-align:center; background-color:#f58700; border:1px solid #000;">
    JUMLAH
   </td>
   @foreach ($compKeys as $comp)
   @foreach ($bloodTypes as $type)
   <td style="font-weight:bold; background-color:#f58700; {{ $cellStyle }}">
    {{ ($totals[$comp][$type] ?? 0) ?: '' }}
   </td>
   @endforeach
   @endforeach
   <td style="font-weight:bold; background-color:#f58700; {{ $cellStyle }}">
    {{ ($totals['_grand'] ?? 0) ?: '' }}
   </td>
  </tr>
 </tbody>
</table>