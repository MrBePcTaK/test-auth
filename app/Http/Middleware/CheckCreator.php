<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCreator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $model
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $model)
    {
        $user = Auth::user();
        $objectId = $request->route('id'); // Assuming the ID is passed as a route parameter

        // Dynamically resolve the model class
        $modelClass = "App\\Models\\" . ucfirst($model);
        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Invalid model type'], 400);
        }

        $object = $modelClass::find($objectId);

        if (!$object) {
            return response()->json(['error' => 'Object not found'], 404);
        }

        if ($object->creator !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}