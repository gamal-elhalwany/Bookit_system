<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::all();
        return response()->json(["data" => $packages], 200);
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
            'name.ar' => 'required|string|max:255|unique:packages,name->ar',
            'name.en' => 'required|string|max:255|unique:packages,name->en',
            'description' => 'nullable|array',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_restaurants' => 'required|integer|min:1',
            'features' => 'required|array',
        ]);

        $package = Package::create($request->all());
        return response()->json($package, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        if (!$package) {
            return response()->json(["message" => "Package not found"], 404);
        }
        return response()->json(["Data" => $package], 200);
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
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|array',
            'name.ar' => 'required|string|max:255|unique:packages,name->ar',
            'name.en' => 'required|string|max:255|unique:packages,name->en',
            'description' => 'nullable|array',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_restaurants' => 'required|integer|min:1',
            'features' => 'required|array',
        ]);

        $package->update($request->all());
        return response()->json(["Data" => $package], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();
        return response()->json(
            [
                "message" => "Package deleted successfully",
                "Data" => $package
            ], 200);
    }
}
