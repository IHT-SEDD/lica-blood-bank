<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperadminMfa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Tamu -> biarkan middleware 'auth' yang menangani
        // Non-superadmin -> MFA tidak wajib
        if (! $user || ! $user->hasRole('superadmin')) {
            return $next($request);
        }

        // Route MFA dan logout harus bisa diakses sebelum verifikasi (hindari redirect loop)
        if ($request->routeIs('mfa.*', 'logout')) {
            return $next($request);
        }

        // Sudah verifikasi pada session ini (terikat ke user id)
        if ($request->session()->get('mfa_verified_user_id') === $user->id) {
            return $next($request);
        }

        // Request AJAX / datatable / select2
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Verifikasi MFA diperlukan.',
            ], 403);
        }

        // Simpan URL tujuan agar setelah MFA bisa kembali ke halaman semula
        if ($request->isMethod('GET')) {
            $request->session()->put('url.intended', $request->fullUrl());
        }

        // Belum enroll -> enrollment, sudah enroll -> challenge
        return redirect()->route(
            $user->mfa_confirmed_at ? 'mfa.challenge' : 'mfa.enrollment'
        );
    }
}
