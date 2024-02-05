<?php

namespace App\Http\Controllers;

use App\Models\md_satuan;
use App\Http\Requests\Storemd_satuanRequest;
use App\Http\Requests\Updatemd_satuanRequest;

class MdSatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maps = md_satuan::all();
        return response()->json($maps);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storemd_satuanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(md_satuan $md_satuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(md_satuan $md_satuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatemd_satuanRequest $request, md_satuan $md_satuan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(md_satuan $md_satuan)
    {
        //
    }
}
