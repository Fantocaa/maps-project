<?php

namespace App\Http\Controllers;

use App\Models\md_maps;
use App\Http\Requests\Storemd_mapsRequest;
use App\Http\Requests\Updatemd_mapsRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MdMapsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maps = md_maps::join('model_has_roles', 'md_maps.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('md_maps.*', 'roles.name as role_name')
            ->get();

        // Mengembalikan data dalam format JSON
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
    public function store(Storemd_mapsRequest $request)
    {
        $form = new md_maps();
        $form->notes = $request->notes;
        $form->lat = $request->lat;
        $form->lng = $request->lng;
        $form->name = Auth::user()->name;
        $form->date = Carbon::now();
        $form->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(md_maps $md_maps)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(md_maps $md_maps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatemd_mapsRequest $request, md_maps $md_maps)
    {
        //
    }

    public function update_maps(Updatemd_mapsRequest $request, md_maps $md_maps)
    {
        // dd(request($request));
        $form = md_maps::find($request->id);

        // Tambahkan pengecekan untuk memastikan objek ditemukan sebelum memanipulasinya
        if ($form) {
            $form->notes = $request->notes;
            // $form->lat = $request->lat;
            // $form->lng = $request->lng;
            $form->save();
        } else {
            // Handle ketika objek tidak ditemukan
            // Misalnya, Anda bisa melempar exception atau memberikan respon yang sesuai
            return response()->json(['error' => 'Data not found'], 404);
        }
    }

    public function delete_maps($id)
    {
        try {
            $maps = md_maps::find($id);

            if ($maps) {
                $maps->delete();
                return response()->json(['message' => 'Data deleted successfully'], 200);
            } else {
                return response()->json(['error' => 'Data not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(md_maps $md_maps)
    {
        //
    }
}
