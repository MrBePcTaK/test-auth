<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'required|numeric',
            'height' => 'required|numeric',
            'project_id' => 'required|integer|exists:projects,id'
        ]);

        $room = new Room;

        $room->name = $data['name'];
        $room->area = $data['area'];
        $room->height = $data['height'];
        $room->project_id = $data['project_id'];

        $room->save();
        return $room;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Room::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'string|max:255',
            'area' => 'numeric',
            'height' => 'numeric',
            'project_id' => 'integer|exists:projects,id'
        ]);

        if ($data) {
            $room = Room::find($id);

            $room->name = $data['name'] ?? $room->name;
            $room->area = $data['area'] ?? $room->area;
            $room->height = $data['height'] ?? $room->height;
            $room->project_id = $data['project_id'] ?? $room->project_id;
            $room->save();
            return $room;
        }
        
        return response(['error_message' => 'Invalid data']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Room::find($id)->delete();
    }
}
