<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Auth::user()->projects()->with(['rooms'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = new Project;

        $project->name = $request->name;
        $project->address = $request->address;
        $project->creator = $request->creator;
        $project->state = 1;

        $project->save();
        return $project;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $test = Project::where('id', $id)->with(['rooms'])->get();
        return $test;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::find($id);
        
        $project->name = $request->name ?? $project->name; 
        $project->address = $request->address ?? $project->address;
        $project->state = $request->state ?? $project->state;
        $project->save();
        return $project;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::find($id);
        $project->deleted_at = now();
        $project->save();
        return $project;
    }
}
