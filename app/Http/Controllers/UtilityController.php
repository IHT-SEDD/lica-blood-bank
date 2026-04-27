<?php

namespace App\Http\Controllers;

use App\Services\UtilityService;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    // Init Services
    public function __construct(
        protected UtilityService $service
    ) {}

    // Dynamic select data
    public function selectData(Request $request, $select)
    {
        return response()->json(
            $this->service->getSelectData($request, $select)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
