<?php

namespace App\Http\Controllers;

use App\Services\UtilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use PragmaRX\Google2FAQRCode\Google2FA;

class UtilityController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected UtilityService $service,
        private Google2FA $google2fa
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Fungsi untuk mengambil data agar bisa ditampilkan di select ----------
    public function selectData(Request $request, string $select)
    {
        return response()->json(
            $this->service->getSelectData($request, $select)
        );
    }

    // ---------- Fungsi untuk mengambil data agar bisa ditampilkan di select ----------
    public function selectDataSpecial(Request $request, string $select, string $id)
    {
        return response()->json(
            $this->service->getSelectDataSpecial($request, $select, $id)
        );
    }

    // ---------- Fungsi untuk mengambil data secara batch agar bisa ditampilkan di select ----------
    public function selectBatchData(Request $request): JsonResponse
    {
        $keys = $request->input('keys', []);

        if (empty($keys) || !is_array($keys)) {
            return response()->json(['results' => []]);
        }

        $results = [];
        foreach ($keys as $key) {
            $fakeRequest = Request::create(
                "/utility/select/{$key}",
                'GET',
                ['q' => '']
            );

            try {
                $results[$key] = $this->service->getSelectData($fakeRequest, $key);
            } catch (\Throwable $e) {
                $results[$key] = ['results' => []];
            }
        }

        return response()->json($results);
    }

    // ---------- Fungsi untuk mengambil data no bdrs ----------
    public function selectDataBDRSNumber(Request $request)
    {
        return response()->json(
            $this->service->getSelectBdrsNumber($request)
        );
    }

    // ---------- Fungsi untuk mengambil data no order ----------
    public function selectDataOrderNumber(Request $request)
    {
        return response()->json(
            $this->service->getSelectOrderNumber($request)
        );
    }

    // ---------- Fungsi untuk mengambil data per id ----------
    public function getDataById(Request $request, string $data, string $id)
    {
        return response()->json(
            $this->service->getDataById($request, $data, $id)
        );
    }

    // ---------- Fungsi untuk menampilkan halaman OTP ----------
    public function displayOtpenrollment(Request $request)
    {
        $user = $this->authorizeSuperadmin($request);

        if ($user->mfa_confirmed_at) {
            return redirect()->route('mfa.challenge');
        }

        if (! $user->mfa_secret) {
            $user->mfa_secret = $this->google2fa->generateSecretKey();
            $user->save();
        }

        $qrCode = $this->google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->mfa_secret
        );

        return view('auth.mfa-enrollment', [
            'qrCode' => $qrCode,
            'secret' => $user->mfa_secret,
        ]);
    }

    // ---------- Fungsi untuk mengkonfirmasi enrollment OTP ----------
    public function confirmOtpEnrollment(Request $request)
    {
        $user = $this->authorizeSuperadmin($request);
        $request->validate([
            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        // Belum ada secret -> kembali ke halaman enrollment
        if (! $user->mfa_secret) {
            return redirect()->route('mfa.enrollment');
        }

        $key = 'mfa-enrollment:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'code' => 'Terlalu banyak percobaan. Silakan coba lagi nanti.',
            ]);
        }

        // Window 1 = toleransi selisih waktu +/- 30 detik
        $valid = $this->google2fa->verifyKey(
            $user->mfa_secret,
            $request->code,
            1
        );
        if (! $valid) {
            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'code' => 'Kode MFA tidak valid.',
            ]);
        }

        RateLimiter::clear($key);

        $user->mfa_confirmed_at = now();
        $user->save();
        $this->markMfaVerified($request, $user);
        return $this->mfaSuccessRedirect();
    }

    // ---------- Fungsi untuk menampilkan MFA challenge ----------
    public function displayMfaChallenge(Request $request)
    {
        $user = $this->authorizeSuperadmin($request);

        if (! $user->mfa_confirmed_at) {
            return redirect()->route('mfa.enrollment');
        }

        if ($request->session()->get('mfa_verified_user_id') === $user->id) {
            return $this->mfaSuccessRedirect();
        }

        return view('auth.mfa-challenge');
    }

    // ---------- Fungsi untuk verifikasi OTP code ----------
    public function verifyOtp(Request $request)
    {
        $user = $this->authorizeSuperadmin($request);
        $request->validate([
            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        // Belum enroll -> arahkan ke enrollment
        if (! $user->mfa_confirmed_at || ! $user->mfa_secret) {
            return redirect()->route('mfa.enrollment');
        }

        $key = 'mfa-login:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'code' => 'Terlalu banyak percobaan MFA. Silakan tunggu sebelum mencoba lagi.',
            ]);
        }

        $valid = $this->google2fa->verifyKey(
            $user->mfa_secret,
            $request->code,
            1
        );
        if (! $valid) {
            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'code' => 'Kode MFA tidak valid.',
            ]);
        }

        RateLimiter::clear($key);
        $this->markMfaVerified($request, $user);
        return $this->mfaSuccessRedirect();
    }

    // ---------- HELPER: untuk memastikan hanya superadmin yang boleh mengakses route MFA ----------
    private function authorizeSuperadmin(Request $request)
    {
        $user = $request->user();
        abort_unless(
            $user && $user->hasRole('superadmin'),
            403
        );
        return $user;
    }
    // ---------- HELPER: untuk menentukan tujuan redirect setelah MFA berhasil ----------
    private function mfaSuccessRedirect()
    {
        $default = Route::has('dashboard')
            ? route('dashboard')
            : route('welcome');

        return redirect()->intended($default);
    }
    // ---------- HELPER: untuk menandai session sudah lolos MFA (terikat ke user id) ----------
    private function markMfaVerified(Request $request, $user): void
    {
        // regenerate dulu, baru simpan flag, supaya flag ada di session id yang baru
        $request->session()->regenerate();
        $request->session()->put('mfa_verified_user_id', $user->id);
    }
}
