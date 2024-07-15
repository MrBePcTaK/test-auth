<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'address' => 'required|max:255',
            'state' => 'integer|min:1|max:5',
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation Error',
            'error' => $validator->errors()]);
        } else {
            $user = Auth::user();
            $project = new Project;
    
            $project->name = $data['name'];
            $project->address = $data['address'];
            $project->creator = $user->id;
            $project->state = $data['state'] ?? 1;
    
            $project->save();
            return $project;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Project::where('id', $id)->with(['rooms'])->get();;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'max:255',
            'address' => 'max:255',
            'state' => 'integer|min:1|max:5',
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation Error',
            'error' => $validator->errors()]);
        } else {
            $project = Project::find($id);
            
            $project->name = $data['name'] ?? $project->name;
            $project->address = $data['address'] ?? $project->address;
            $project->state = $data['state'] ?? $project->state;
            $project->save();
            return $project;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Мягкое удаление проекта и связанных с ним комнат
        $project = Project::find($id);
        foreach ($project->rooms()->get() as $room) {
            $room->delete();
        }
        $project->delete();
        return $project;
    }
}
