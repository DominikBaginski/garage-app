<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Garage;
use Illuminate\Http\Request;
use App\Http\Resources\GarageResource;
use App\Http\Requests\GarageRequest;

class GarageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GarageResource::collection(Garage::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GarageRequest $request)
    {
        $garage = Garage::create($request->validated());

        return new GarageResource($garage);
    }

    /**
     * Display the specified resource.
     */
    public function show(Garage $garage)
    {
        return new GarageResource($garage);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GarageRequest $request, Garage $garage)
    {
        $garage->update($request->validated());

        return new GarageResource($garage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Garage $garage)
    {
        $garage->delete();

        return response()->noContent();
    }
}
