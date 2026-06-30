@php
$headerStyle = 'font-weight:bold; text-align:center; vertical-align:middle; background-color:#D9E1F2; border:1px solid #000; white-space:nowrap;';
$cellStyle = 'text-align:center; border:1px solid #000; vertical-align:middle;';
@endphp

<table>
 <thead>
  {{-- Judul laporan --}}
  <tr>
   <th colspan="{{ $totalColumns }}" rowspan="2" style="font-weight:bold; text-align:center; font-size:14pt; vertical-align:middle">
    {{ $title }}
   </th>
  </tr>
  <tr></tr>

  <tr>
   <th rowspan="2" style="{{ $headerStyle }}">NO</th>
   <th rowspan="2" style="{{ $headerStyle }}">Tanggal</th>
   <th rowspan="2" style="{{ $headerStyle }}">Asal Labu</th>
   <th rowspan="2" style="{{ $headerStyle }}">Nama Pasien</th>
   <th rowspan="2" style="{{ $headerStyle }}">No. RM</th>
   <th rowspan="2" style="{{ $headerStyle }}">Goldar dan Rhesus</th>
   <th rowspan="2" style="{{ $headerStyle }}">Ruangan</th>
   <th rowspan="2" style="{{ $headerStyle }}">Diagnosa</th>
   <th rowspan="2" style="{{ $headerStyle }}">Jenis Pasien</th>
   <th rowspan="2" style="{{ $headerStyle }}">Jenis Darah</th>
   <th rowspan="2" style="{{ $headerStyle }}">Jumlah Permintaan</th>
   <th rowspan="2" style="{{ $headerStyle }}">Jam Penerimaan</th>
   <th colspan="2" style="{{ $headerStyle }}">Jam Periksa</th>
   <th rowspan="2" style="{{ $headerStyle }}">No. Kantong Darah</th>
    <th colspan="6" style="{{ $headerStyle }}">Crossmatch</th>
  </tr>
  <tr>
    <th style="{{ $headerStyle }}">Mulai</th>
    <th style="{{ $headerStyle }}">Selesai</th>
    <th style="{{ $headerStyle }}">Mayor</th>
    <th style="{{ $headerStyle }}">Minor</th>
    <th style="{{ $headerStyle }}">AK</th>
    <th style="{{ $headerStyle }}">Hasil</th>
    <th style="{{ $headerStyle }}">Teknisi</th>
    <th style="{{ $headerStyle }}">Admin</th>
  </tr>
 </thead>
 <tbody>

<tbody>
  @foreach ($data as $index => $row)
    @php 
        $rowspan = count($row['details']); 
        $firstDetail = $row['details'][0] ?? null;
    @endphp
    
    <tr>
       
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $loop->iteration }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['tanggal'] }}</td>
        <td style="{{ $cellStyle }}">{{ $firstDetail['asal_labu'] ?? '' }}</td>
        
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['nama_pasien'] }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['no_medrec'] }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['goldar_rhesus'] }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['ruangan'] }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['diagnosa'] }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['jenis_pasien'] }}</td>
        
        <td style="{{ $cellStyle }}">{{ $firstDetail['jenis_darah'] ?? '' }}</td>
        
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['countBag'] ?? $rowspan }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['jam_penerimaan'] }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['jam_mulai'] }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['jam_selesai'] }}</td>
        <td style="{{ $cellStyle }}">{{ $firstDetail['no_kantong_darah'] ?? '' }}</td>
        <td style="{{ $cellStyle }}">{{ $firstDetail['result_mayor'] ?? '' }}</td>
        <td style="{{ $cellStyle }}">{{ $firstDetail['result_minor'] ?? '' }}</td>
        <td style="{{ $cellStyle }}">{{ $firstDetail['result_auto_control'] ?? '' }}</td>
        <td style="{{ $cellStyle }}">{{ $firstDetail['result_crossmatch'] ?? '' }}</td>
        
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['teknisi_bank_darah'] }}</td>
        <td rowspan="{{ $rowspan }}" style="{{ $cellStyle }}">{{ $row['admin'] }}</td>
    </tr>

    @if($rowspan > 1)
        @foreach ($row['details'] as $subIndex => $detail)
            @continue($subIndex === 0) 
            <tr>
                <td style="{{ $cellStyle }}">{{ $detail['asal_labu'] }}</td>
                <td style="{{ $cellStyle }}">{{ $detail['jenis_darah'] }}</td>
                <td style="{{ $cellStyle }}">{{ $detail['no_kantong_darah'] }}</td>
                <td style="{{ $cellStyle }}">{{ $detail['result_mayor'] }}</td>
                <td style="{{ $cellStyle }}">{{ $detail['result_minor'] }}</td>
                <td style="{{ $cellStyle }}">{{ $detail['result_auto_control'] }}</td>
                <td style="{{ $cellStyle }}">{{ $detail['result_crossmatch'] }}</td>
            </tr>
        @endforeach
    @endif
  @endforeach
</tbody>
</table>