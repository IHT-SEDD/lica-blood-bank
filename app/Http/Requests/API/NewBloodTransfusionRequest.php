<?php

namespace App\Http\Requests\API;

use App\Services\API\ApiUtilityService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewBloodTransfusionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $apiUtilityService = app(ApiUtilityService::class);
        $apiConfig = $apiUtilityService->validateToken($this);

        return $apiConfig !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'demografi' => 'required|array',
            'demografi.no_rkm_medis' => 'required|string',
            'demografi.nama_pasien' => 'required|string',
            'demografi.tgl_lahir' => 'required|date',
            'demografi.jk' => 'required',
            'demografi.alamat' => 'nullable|string',
            'demografi.no_telp' => 'nullable|string',

            'transaksi' => 'required|array',
            'transaksi.no_order' => 'required|string',
            'transaksi.tgl_permintaan' => 'required|date',
            'transaksi.jam_permintaan' => 'required|string',
            'transaksi.kode_pembayaran' => 'required|string',
            'transaksi.pembayaran' => 'nullable|string',
            'transaksi.kode_ruangan' => 'required|string',
            'transaksi.kelas' => 'required|string',
            'transaksi.ruangan' => 'nullable|string',
            'transaksi.kode_jenis' => 'required|string',
            'transaksi.jenis' => 'required|string',
            'transaksi.kode_dokter' => 'required|string',
            'transaksi.dokter' => 'nullable|string',
            'transaksi.diagnosis' => 'nullable|string',

            'tes' => 'required|array|min:1',
            'tes.*.kode_jenis_tes' => 'required|string',
            'tes.*.nama_tes' => 'required|string',
            'tes.*.cito' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'demografi.required' => 'Data demografi tidak boleh kosong!.',
            'demografi.no_rkm_medis.required' => 'No. rekam medis tidak boleh kosong!.',
            'demografi.nama_pasien.required' => 'Nama pasien tidak boleh kosong!.',
            'demografi.tgl_lahir.required' => 'Tanggal lahir tidak boleh kosong!.',
            'demografi.tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
            'demografi.jk.required' => 'Jenis kelamin tidak boleh kosong!.',

            'transaksi.required' => 'Data transaksi tidak boleh kosong!.',
            'transaksi.no_order.required' => 'No. order tidak boleh kosong!.',
            'transaksi.tgl_permintaan.required' => 'Tanggal permintaan tidak boleh kosong!.',
            'transaksi.tgl_permintaan.date' => 'Format tanggal permintaan tidak valid.',
            'transaksi.jam_permintaan.required' => 'Jam permintaan tidak boleh kosong!.',
            'transaksi.kode_pembayaran.required' => 'Kode pembayaran tidak boleh kosong!.',
            'transaksi.kode_ruangan.required' => 'Kode ruangan tidak boleh kosong!.',
            'transaksi.kelas.required' => 'Kelas ruangan tidak boleh kosong!.',
            'transaksi.kode_jenis.required' => 'Kode jenis tidak boleh kosong!.',
            'transaksi.jenis.required' => 'Jenis tidak boleh kosong!.',
            'transaksi.kode_dokter.required' => 'Kode dokter tidak boleh kosong!.',

            'tes.required' => 'Data tes tidak boleh kosong!.',
            'tes.min' => 'Minimal harus ada 1 data tes.',
            'tes.*.kode_jenis_tes.required' => 'Kode jenis tes tidak boleh kosong!.',
            'tes.*.nama_tes.required' => 'Nama tes tidak boleh kosong!.',
        ];
    }

    protected function failedAuthorization(): never
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Invalid or missing token.',
            ], 401)
        );
    }

    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
