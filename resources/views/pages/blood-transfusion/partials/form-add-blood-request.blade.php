{{-- Form Add New Blood Request :begin --}}
<form id="add_new_blood_request" autocomplete="off" data-wizard-validation="" novalidate>
    <div class="ins-wizard" data-wizard="">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs wizard-tabs mb-3 justify-content-center gap-4" data-wizard-nav="" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#stepPatient">
                    <span class="d-flex align-items-center">
                        <i class="ti ti-user-circle fs-32"></i>
                        <span class="flex-grow-1 ms-2 text-truncate">
                            <span class="mb-0 lh-base d-block fw-semibold text-body fs-base">
                                Pasien
                            </span>
                            <span class="mb-0 fw-normal">Detail Pasien & Transaksi</span>
                        </span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#stepBloodRequest">
                    <span class="d-flex align-items-center">
                        <i data-lucide="flask-conical" class="align-middle fs-32"></i>
                        <span class="flex-grow-1 ms-2 text-truncate">
                            <span class="mb-0 lh-base d-block fw-semibold text-body fs-base">
                                Permintaan Darah
                            </span>
                            <span class="mb-0 fw-normal">Detail Permintaan Darah</span>
                        </span>
                    </span>
                </a>
            </li>
        </ul>

        <!-- Patient & Transaction -->
        <div class="tab-content pt-3" data-wizard-content="">
            <!-- Step 1 -->
            <div class="tab-pane fade show active" id="stepPatient">
                {{-- Choose Patient --}}
                <div class="col-lg-12 mb-3">
                    <label class="form-label" for="select-patient">{{ __('Pilih pasien atau tambahkan baru') }}</label>
                    <div class="input-group">
                        <select class="form-control form-control-sm" id="select-patient" name="patient_id"
                            placeholder="{{ __('Pilih pasien') }}"></select>
                        <button class="btn btn-sm btn-soft-primary" id="add-new-patient-data" type="button">
                            {{ __('Tambah Pasien Baru') }}
                        </button>
                    </div>
                    <small class="form-text text-muted fs-6">
                        {{ __('Pilih pasien jika sudah pernah ada permintaan atau tambahkan pasien baru jika belum
                        pernah ada permintaan.') }}
                    </small>
                </div>

                <hr />

                <div class="row g-2">
                    {{-- Patient Data :begin --}}
                    <div class="col-lg-7 col-12 patient-data-border pe-lg-4">
                        {{-- Title --}}
                        <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
                            <h4>{{ __('Data Detail Pasien') }}</h4>
                        </div>

                        <div class="row mb-2">
                            {{-- Medrec --}}
                            <div class="col-xxl-6 col-md-6 col-12 mb-0">
                                <label class="form-label" for="medrec">{{ __('Medrec') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input autocomplete="off" class="form-control" id="medrec" name="medrec" type="text"
                                    placeholder="{{ __('No. Rekam Medis') }}" readonly />
                                <small class="form-text text-muted">
                                    {{ __('Klik inputan untuk menghasilkan nomor rekam medis.') }}
                                </small>
                            </div>

                            {{-- Name --}}
                            <div class="col-xxl-6 col-md-6 col-12 mb-0">
                                <label class="form-label" for="name">{{ __('Nama') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input autocomplete="off" class="form-control" id="name" name="name" type="text"
                                    placeholder="{{ __('Nama lengkap pasien') }}" required />
                            </div>

                            {{-- Email --}}
                            <div class="col-xxl-6 col-md-6 col-12 mb-0">
                                <label class="col-form-label" for="email">{{ __('Email') }}</label>
                                <input autocomplete="off" class="form-control" id="email" name="email" type="email"
                                    placeholder="{{ __('Alamat email pasien') }}" />
                            </div>

                            {{-- Phone Number --}}
                            <div class="col-xxl-6 col-md-6 col-12 mb-0">
                                <label class="col-form-label" for="phone_number">{{ __('Nomor HP') }}</label>
                                <input autocomplete="off" class="form-control" id="phone_number" name="phone_number"
                                    type="text" placeholder="08**********" />
                            </div>

                            {{-- Blood Group --}}
                            <div class="col-xxl-6 col-md-6 col-12 mt-2">
                                <label class="form-label" for="select-blood-group">{{ __('Golongan Darah') }}</label>
                                <select class="form-control" id="select-blood-group" name="blood_group"
                                    placeholder="{{ __('Golongan darah pasien') }}"></select>
                            </div>

                            {{-- Blood Rhesus --}}
                            <div class="col-xxl-6 col-md-6 col-12 mt-2">
                                <label class="form-label" for="select-blood-rhesus">{{ __('Rhesus Darah') }}</label>
                                <select class="form-control" id="select-blood-rhesus" name="blood_rhesus"
                                    placeholder="{{ __('Rhesus darah pasien') }}"></select>
                            </div>

                            {{-- Birth Date --}}
                            <div class="col-xxl-6 col-md-6 col-12 mb-2">
                                <label class="col-form-label m-0" for="birthdate">
                                    {{ __('Tanggal Lahir') }} <span class="text-danger">*</span>
                                </label>
                                <input class="form-control patient-birth-date" aria-describedby="patient-birth-date"
                                    data-date-format="Y-m-d" data-provider="flatpickr" type="text" id="birthdate"
                                    name="birthdate" placeholder="{{ __('Pilih tanggal lahir pasien') }}" />
                            </div>

                            {{-- Gender --}}
                            <div class="col-xxl-6 col-md-12 col-12 mt-2">
                                <label class="form-label" for="gender">{{ __('Jenis Kelamin') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="mt-1">
                                    {{-- Male --}}
                                    <div class="form-check form-check-inline">
                                        <input checked="" class="form-check-input" id="gender" name="gender"
                                            type="radio" value="M" />
                                        <label class="form-check-label" for="gender">{{ __('Laki-laki') }}</label>
                                    </div>
                                    {{-- Female --}}
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" id="gender" name="gender" type="radio"
                                            value="F" />
                                        <label class="form-check-label" for="gender">{{ __('Perempuan') }}</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Relation Name --}}
                            <div class="col-xxl-6 col-md-12 col-12 mt-2">
                                <label class="form-label" for="relation_name">
                                    {{ __('Nama Relasi') }}
                                </label>
                                <input autocomplete="off" class="form-control" id="relation_name" name="relation_name"
                                    type="text" placeholder="{{ __('Nama relasi pasien') }}" />
                            </div>

                            {{-- Relation Type --}}
                            <div class="col-xxl-6 col-md-12 col-12 mt-2">
                                <label class="form-label" for="select-relation-type">
                                    {{ __('Jenis Relasi') }}
                                </label>
                                <select class="form-control" id="select-relation-type" name="relation_type"
                                    placeholder="{{ __('Pilih jenis relasi pasien') }}"></select>
                            </div>

                            {{-- Address --}}
                            <div class="col-xxl-12 col-md-12 col-12 mt-2">
                                <label class="form-label" for="address">
                                    {{ __('Alamat') }}</label>
                                <textarea class="form-control" id="address" name="address"
                                    placeholder="{{ __('Alamat lengkap pasien') }}" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    {{-- Patient Data :end --}}

                    {{-- Transaction : begin --}}
                    <div class="col-lg-5 col-12 ps-lg-4">
                        {{-- Title --}}
                        <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
                            <h4>{{ __('Data Detail Transaksi') }}</h4>
                        </div>

                        <div class="row">
                            {{-- Insurance --}}
                            <div class="col-12 mt-2">
                                <label class="form-label" for="select-insurance">{{ __('Penjamin/Asuransi') }}</label>
                                <span class="text-danger">*</span>
                                <select class="form-control" id="select-insurance" name="insurance_id"
                                    placeholder="{{ __('Pilih penjamin/asuransi') }}"></select>
                            </div>

                            {{-- Room --}}
                            <div class="col-12 mt-2">
                                <label class="form-label" for="select-room">{{ __('Ruangan') }}</label>
                                <span class="text-danger">*</span>
                                <select class="form-control" id="select-room" name="room_id"
                                    placeholder="{{ __('Pilih ruangan') }}"></select>
                            </div>

                            {{-- Doctor --}}
                            <div class="col-xxl-12 col-md-12 col-12 mt-2">
                                <label class="form-label" for="select-doctor">{{ __('Dokter') }}</label>
                                <span class="text-danger">*</span>
                                <select class="form-control" id="select-doctor" name="doctor_id"
                                    placeholder="{{ __('Pilih dokter') }}"></select>
                            </div>

                            {{-- Diagnosis --}}
                            <div class="col-xxl-12 col-md-12 col-12 mt-2">
                                <label class="form-label" for="diagnosis">{{ __('Diagnosis') }}</label>
                                <textarea class="form-control" id="diagnosis" name="diagnosis"
                                    placeholder="{{ __('Diagnosis pasien') }}" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    {{-- Transaction : end --}}
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary" data-wizard-next="#stepBloodRequest" type="button">
                        Selanjutnya <i class="ti ti-arrow-right-dashed ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Blood Request -->
            <div class="tab-pane fade" id="stepBloodRequest">
                {{-- Title --}}
                <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
                    <h4>{{ __('Data Detail Permintaan Darah') }}</h4>
                </div>

                {{-- Blood Required Date --}}
                <div class="col-xxl-12 col-md-12 col-12 mb-4">
                    <label class="form-label" for="blood_required_at">
                        {{ __('Tanggal Darah Dibutuhkan') }}
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" id="patient-blood-required-date">
                            <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
                        </span>
                        <input class="form-control form-control-sm patient-blood-required-date"
                            aria-describedby="patient-blood-required-date" data-date-format="d-m-Y"
                            data-provider="flatpickr" type="text" id="blood_required_at" name="blood_required_at"
                            placeholder="{{ __('Pilih tanggal') }}" />
                    </div>
                </div>

                {{-- Blood Pack Selection --}}
                <div class="row g-3">
                    {{-- Available Blood Components (Left) --}}
                    <div class="col-lg-6 col-12">
                        <h5 class="mb-2">{{ __('Komponen Darah Yang Tersedia') }}</h5>
                        <div>
                            <table class="table table-sm table-striped dt-responsive align-middle mb-0"
                                id="available-blood-components-table">
                                <thead class="thead-sm text-uppercase fs-xxs">
                                    <tr>
                                        <th>{{ __('Komponen') }}</th>
                                        <th>{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Selected Blood Packs (Right) --}}
                    <div class="col-lg-6 col-12">
                        <h5 class="mb-3">{{ __('Komponen Darah Yang Dipilih') }}</h5>
                        <div>
                            <table class="table table-sm table-hover table-striped dt-responsive align-middle mb-0"
                                id="selected-blood-pack-table">
                                <thead class="thead-sm text-uppercase fs-xxs">
                                    <tr>
                                        <th>{{ __('Komponen') }}</th>
                                        <th>{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Hidden Input for Selected Blood Components --}}
                <input type="hidden" id="selected-blood-components" name="selected_blood_components" value="[]" />
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-dark" data-wizard-prev="#stepPatient" type="button"><i
                            class="ti ti-arrow-left-dashed me-1"></i>Kembali</button>
                    <button class="btn btn-success" type="submit">{{ __('Buat Permintaan') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
{{-- Form Add New Blood Request :end --}}