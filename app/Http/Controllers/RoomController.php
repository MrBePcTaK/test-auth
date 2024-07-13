<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Room::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $room = new Room;

        $room->name = $request->name;
        $room->area = $request->area;
        $room->height = $request->height;
        $room->project_id = $request->project_id;

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
        $room = Room::find($id);
        if ($request->has('name')) {
            $room->name = $request->name;
        }
        if ($request->has('area')) {
            $room->area = $request->area;
        }
        if ($request->has('height')) {
            $room->height = $request->height;
        }
        $room->save();
        return $room;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::find($id);
        $room->deleted_at = now();
        $room->save();
        return $room;
    }
}
