<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Project::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

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
        return Project::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::find($id);
        if ($request->has('name')) {
            $project->name = $request->name;
        }
        if ($request->has('address')) {
            $project->address = $request->address;
        }
        if ($request->has('state')) {
            $project->state = $request->state;
        }
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
