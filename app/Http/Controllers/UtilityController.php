<?php

namespace App\Http\Controllers;

use App\Services\UtilityService;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected UtilityService $service
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Fungsi untuk mengambil data agar bisa ditampilkan di select ----------
    public function selectData(Request $request, $select)
    {
        return response()->json(
            $this->service->getSelectData($request, $select)
        );
    }

    // ---------- Fungsi untuk mengambil data per id ----------
    public function getDataById(Request $request, $data, $id)
    {
        return response()->json(
            $this->service->getDataById($request, $data, $id)
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
