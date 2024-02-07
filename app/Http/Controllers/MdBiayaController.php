<?php

namespace App\Http\Controllers;

use App\Models\md_biaya;
use App\Http\Requests\Storemd_biayaRequest;
use App\Http\Requests\Updatemd_biayaRequest;
use Inertia\Inertia;

class MdBiayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maps = md_biaya::all();
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
    public function store(Storemd_biayaRequest $request)
    {
        $company = new md_biaya();
        $company->name_biaya = $request->name_biaya;
        $company->save();

        return Inertia::render('Components/Company');
    }

    /**
     * Display the specified resource.
     */
    public function show(md_biaya $md_biaya)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(md_biaya $md_biaya)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatemd_biayaRequest $request, md_biaya $md_biaya)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(md_biaya $md_biaya)
    {
        //
    }
}
