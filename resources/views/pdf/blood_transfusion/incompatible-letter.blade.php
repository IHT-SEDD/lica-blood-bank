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
          @if (!empty($data->doctor?->name))
          <div class="paragraph">{{ $data->doctor?->name ?? '-' }}</div>
          @else
          <div class="paragraph">dr. Sofyan, Sp.PD</div>
          @endif
        </td>
      </tr>

      {{-- Pengantar detail pasien --}}
      <tr>
        <td width="17%">
          <div class="paragraph">{{ __('Di') }}</div>
        </td>
        <td width="3%">
          <div class="paragraph">:</div>
        </td>
        <td width="80%">
          <div class="paragraph">RSUD Indramayu</div>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <div class="paragraph" style="margin-top: 14px;">{{ __('Dengan Hormat,') }}</div>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <div class="paragraph">
            {{ __('Berdasarkan surat permintaan darah untuk pasien :') }}
          </div>
        </td>
      </tr>

      <!--- Nama --->
      <tr>
        <td width="30%">
          <div class="paragraph" style="margin-top: 12px;">{{ __('Nama') }}</div>
        </td>
        <td width="3%">
          <div class="paragraph">:</div>
        </td>
        <td width="67%">
          @if (!empty($data->patient?->name))
          <div class="paragraph">{{ $data->patient?->name ?? '-' }}</div>
          @else
          <div class="paragraph">Unknown Patient Name</div>
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
          @if (!empty($data->patient?->medrec))
          <div class="paragraph">{{ $data->patient?->medrec ?? '-' }}</div>
          @else
          <div class="paragraph">Unknown Patient Medrec</div>
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
          @if (!empty($data->patient?->address))
          <div class="paragraph">{{ $data->patient?->address ?? '-' }}</div>
          @else
          <div class="paragraph">Jawa Barat, Indonesia</div>
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
          @if (!empty($data->patient?->blood_group))
          <div class="paragraph">
            {{ $data->patient?->blood_group . $data->patient?->blood_rhesus ?? '-'}}
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
            {{ $data->created_at ? \Carbon\Carbon::parse($data->created_at)->format('d F Y') : '-' }}
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

      @php
      function formatResult(?string $result): string {
      if (!$result) return '-';
      return strtoupper(str_replace('_', ' ', $result));
      }
      @endphp

      <tbody>
        @forelse ($data->details as $detail)
        @php
        $tests = $detail->bloodTransfusionDetailTests->keyBy(fn($t) => $t->test?->name);
        $mayor = $tests->get('Mayor');
        $minor = $tests->get('Minor');
        $autoControl = $tests->get('Auto Control');
        @endphp
        <tr>
          <td class="text-center">
            {{ $detail->bloodStock?->bag_number ?? '-' }}
          </td>
          <td class="text-center">
            <strong>{{ $detail->component ?? '-' }}</strong>
          </td>
          <td class="text-center">
            {{ formatResult($mayor?->result) }}
          </td>
          <td class="text-center">
            {{ formatResult($minor?->result) }}
          </td>
          <td class="text-center">
            {{ formatResult($autoControl?->result) }}
          </td>
        </tr>
        @empty
        <tr>
          <td class="text-center" colspan="5">No Data Available</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Penjelasan DCT --}}
    <table>
      <tr>
        <td width="100%" align="left">
          <div class="paragraph">
            {{ __('Hasil pemeriksaan Direct Coomb Test (DCT) :') }}
            <strong>{{ $data->is_dct ? 'DILAKUKAN' : 'TIDAK DILAKUKAN' }}</strong>
          </div>
        </td>
      </tr>
    </table>

    {{-- Pernyataan dan Persetujuan --}}
    <table>
      <tr>
        <td width="100%" align="left">
          <div class="paragraph">{{ __('Sehubungan dengan hasil pemeriksaan tersebut :') }}</div>
        </td>
      </tr>
      <tr>
        <td width="100%" align="left">
          <div class="paragraph" style="margin-top: 10px;">
            {{ __('Permintaan darah atas nama') }}
            <strong>{{ $data->patient?->name ?? '-' }}</strong>
            {{ __('tidak dapat kami berikan darahnya, kecuali dengan persetujuan Dokter Penanggung Jawab Pasien (DPJP)
            bertanggung jawab bilamana ada reaksi transfusi darah.') }}
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
            <strong>{{ $printBy ?? '_______________' }}</strong>
          </div>
        </td>
      </tr>
    </table>

    {{-- Footer --}}
    <div class="footer">
      {{ __('Generated On') }} {{ now()->format('d F Y, H:i') }} &mdash; Incompatible Letter &mdash;
      {{ __('LICA Blood Bank Information System') }}
    </div>
  </div>
</body>

</html>