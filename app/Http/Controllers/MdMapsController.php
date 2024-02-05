<?php

namespace App\Http\Controllers;

use App\Models\md_maps;
use App\Http\Requests\Storemd_mapsRequest;
use App\Http\Requests\Updatemd_mapsRequest;
use App\Models\User;
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
        // $maps = md_maps::join('model_has_roles', 'md_maps.id', '=', 'model_has_roles.model_id')
        //     ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        //     ->select('md_maps.*', 'roles.name as role_name')
        //     ->get();

        // // Mengembalikan data dalam format JSON
        // return response()->json($maps);

        $maps = md_maps::all();
        return response()->json($maps);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function has_role()
    {
        $users = User::all();

        // Mengubah setiap user menjadi array dan menambahkan properti role_names
        $users = $users->map(function ($user) {
            return array_merge($user->toArray(), ['role_names' => $user->role_names]);
        });

        // Mengembalikan data dalam format JSON
        return response()->json($users);
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
        $form->lokasi = $request->lokasi;
        $form->name = $request->name;
        $form->date = Carbon::now()->toDateString();
        // dd($form);
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

    public function delete_user($id)
    {
        try {
            $maps = User::find($id);

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
