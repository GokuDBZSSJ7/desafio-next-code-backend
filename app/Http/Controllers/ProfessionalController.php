<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfessionalResource;
use App\Models\Professional;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProfessionalRequest;
use App\Http\Requests\UpdateProfessionalRequest;

class ProfessionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProfessionalResource::collection(Professional::query()->latest()->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfessionalRequest $request)
    {
        $data = $request->validated();
        $pro = Professional::create($data);
        return new ProfessionalResource($pro);
    }

    /**
     * Display the specified resource.
     */
    public function show(Professional $professional)
    {
        return new ProfessionalResource($professional);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfessionalRequest $request, Professional $professional)
    {
        $professional->update($request->validated());
        return new ProfessionalResource($professional);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professional $professional)
    {
        $professional->delete();
        return response()->noContent();
    }
}
