{{-- Form :begin --}}
<form class="row gy-2" id="add_new_donor" autocomplete="off" novalidate>
 {{-- Pendonor --}}
 <div class="col-lg-12 mb-3">
  <label class="form-label" for="select-patient">{{ __('Pilih pendonor atau tambahkan baru') }}</label>
  <div class="input-group">
   <select class="form-control form-control-sm" id="select-patient" name="patient_id"
    placeholder="{{ __('Pilih pendonor') }}"></select>
   <button class="btn btn-sm btn-soft-primary" id="add-new-patient-data" type="button">
    {{ __('Tambah Pendonor Baru') }}
   </button>
  </div>
  <small class="form-text text-muted fs-6">
   {{ __('Pilih pendonor jika sudah pernah ada permintaan atau tambahkan pendonor baru jika belum
   pernah ada permintaan.') }}
  </small>
 </div>

 <hr />

 {{-- No kartu donor --}}
 <div class="col-lg-12">
  <label class="form-label" for="donor_card_number">{{ __('No. Kartu Donor') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input autocomplete="off" class="form-control" id="donor_card_number" name="donor_card_number"
    placeholder="{{ __('Masukkan nomor kartu donor') }}" type="numeric" inputmode="numeric" />
  </div>
 </div>

 {{-- Nama lengkap --}}
 <div class="col-lg-6">
  <label class="form-label" for="name">{{ __('Nama Lengkap') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input autocomplete="off" class="form-control" id="name" name="name" placeholder="{{ __('Nama lengkap pendonor') }}"
    type="text" />
  </div>
  <small class="form-text text-muted fs-6">
   {{ __('Nama lengkap sesuai KTP.') }}
  </small>
 </div>

 {{-- Jenis Kelamin --}}
 <div class="col-lg-6">
  <label class="form-label" for="gender">{{ __('Jenis Kelamin') }}
   <span class="text-danger">*</span>
  </label>
  <div class="mt-1 mb-2">
   {{-- Male --}}
   <div class="form-check form-check-inline">
    <input checked="" class="form-check-input" id="gender" name="gender" type="radio" value="M" />
    <label class="form-check-label" for="gender">{{ __('Laki-laki') }}</label>
   </div>
   {{-- Female --}}
   <div class="form-check form-check-inline">
    <input class="form-check-input" id="gender" name="gender" type="radio" value="F" />
    <label class="form-check-label" for="gender">{{ __('Perempuan') }}</label>
   </div>
  </div>
  <small class="form-text text-muted fs-6">
   {{ __('Jenis kelamin sesuai KTP.') }}
  </small>
 </div>

 {{-- Tempat lahir --}}
 <div class="col-lg-6">
  <label class="form-label" for="birthplace">{{ __('Tempat Lahir') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input autocomplete="off" class="form-control" id="birthplace" name="birthplace"
    placeholder="{{ __('Tempat lahir pendonor') }}" type="text" />
  </div>
  <small class="form-text text-muted fs-6">
   {{ __('Tempat lahir sesuai KTP.') }}
  </small>
 </div>

 {{-- Tanggal lahir --}}
 <div class="col-lg-6">
  <label class="form-label" for="birthdate">{{ __('Tanggal Lahir') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input class="form-control patient-birth-date" aria-describedby="patient-birth-date" data-date-format="Y-m-d"
    data-provider="flatpickr" type="text" id="birthdate" name="birthdate"
    placeholder="{{ __('Pilih tanggal lahir pendonor') }}" />
  </div>
  <small class="form-text text-muted fs-6">
   {{ __('Tanggal lahir sesuai KTP.') }}
  </small>
 </div>

 {{-- Alamat tempat tinggal --}}
 <div class="col-lg-8">
  <label class="form-label" for="address">{{ __('Alamat Tempat Tinggal') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <textarea class="form-control" id="address" name="address" placeholder="{{ __('Alamat tempat tinggal pendonor') }}"
    rows="5"></textarea>
  </div>
 </div>

 {{-- RT --}}
 <div class="col-lg-2">
  <label class="form-label" for="rt">{{ __('RT') }}</label>
  <div class="input-group">
   <input class="form-control" id="rt" inputmode="numeric" maxlength="3" name="rt" pattern="[0-9]*"
    placeholder="{{ __('Nomor RT') }}" type="text" />
  </div>
 </div>
 {{-- RW --}}
 <div class="col-lg-2">
  <label class="form-label" for="rw">{{ __('RW') }}</label>
  <div class="input-group">
   <input class="form-control" id="rw" inputmode="numeric" maxlength="3" name="rw" pattern="[0-9]*"
    placeholder="{{ __('Nomor RW') }}" type="text" />
  </div>
 </div>

 {{-- Kota --}}
 <div class="col-lg-3">
  <label class="form-label" for="city">{{ __('Kota') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input class="form-control" id="city" name="city" placeholder="{{ __('Kota tempat tinggal') }}" type="text" />
  </div>
 </div>

 {{-- Kabupaten --}}
 <div class="col-lg-3">
  <label class="form-label" for="district">{{ __('Kabupaten') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input class="form-control" id="district" name="district" placeholder="{{ __('Kabupaten tempat tinggal') }}"
    type="text" />
  </div>
 </div>

 {{-- Kecamatan --}}
 <div class="col-lg-3">
  <label class="form-label" for="sub_district">{{ __('Kecamatan') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input class="form-control" id="sub_district" name="sub_district" placeholder="{{ __('Kecamatan tempat tinggal') }}"
    type="text" />
  </div>
 </div>

 {{-- Kelurahan/desa --}}
 <div class="col-lg-3">
  <label class="form-label" for="village">{{ __('Kelurahan/Desa') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input class="form-control" id="village" name="village" placeholder="{{ __('Kelurahan/desa tempat tinggal') }}"
    type="text" />
  </div>
 </div>

 {{-- No. HP --}}
 <div class="col-lg-4">
  <label class="form-label" for="phone">{{ __('No. HP') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input class="form-control" id="phone" inputmode="numeric" name="phone" pattern="[0-9]*"
    placeholder="{{ __('No. telepon pribadi') }}" type="text" />
  </div>
  <small class="form-text text-muted fs-6">
   {{ __('Pastikan nomor aktif dan menggunakan WhatsApp.') }}
  </small>
 </div>
 {{-- No. Telepon Rumah --}}
 <div class="col-lg-4">
  <label class="form-label" for="home_phone">{{ __('No. Telepon Rumah') }}</label>
  <div class="input-group">
   <input class="form-control" id="home_phone" inputmode="numeric" name="home_phone" pattern="[0-9]*"
    placeholder="{{ __('No. telepon rumah') }}" type="text" />
  </div>
 </div>
 {{-- Pekerjaan --}}
 <div class="col-lg-4">
  <label class="form-label" for="job">{{ __('Pekerjaan') }}
   <span class="text-danger">*</span>
  </label>
  <div class="input-group">
   <input class="form-control" id="job" name="job" placeholder="{{ __('Pekerjaan saat ini') }}" type="text" />
  </div>
 </div>

 {{-- Bersedia donor --}}
 <div class="col-lg-12">
  <label class="form-label" for="is_ready_to_donor">{{ __('Apakah bersedia mendonor saat dibutuhkan?') }}
   <span class="text-danger">*</span>
  </label>
  <div class="mt-1 mb-2">
   {{-- Bersedia --}}
   <div class="form-check form-check-inline">
    <input checked="" class="form-check-input" id="is_ready_to_donor" name="is_ready_to_donor" type="checkbox" />
    <label class="form-check-label" for="is_ready_to_donor">{{ __('Bersedia') }}</label>
   </div>
   {{-- Tidak Bersedia --}}
   <div class="form-check form-check-danger form-check-inline">
    <input class="form-check-input" id="is_ready_to_donor" name="is_ready_to_donor" type="checkbox" />
    <label class="form-check-label" for="is_ready_to_donor">{{ __('Tidak Bersedia') }}</label>
   </div>
  </div>
 </div>

 <hr />

 <div class="col-lg-12 d-flex justify-content-end">
  <button class="btn btn-success" type="submit">{{ __('Tambahkan') }}</button>
 </div>
</form>
{{-- Form :end --}}