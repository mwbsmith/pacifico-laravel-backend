<?php
// app/Http/Controllers/Api/EventController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Event::query();
        
        if ($request->boolean('featured')) {
            $query->featured();
        }
        
        if ($request->boolean('upcoming')) {
            $query->upcoming();
        }
        
        $events = $query
            ->orderBy('event_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    public function featured(): JsonResponse
    {
        $events = Event::featured()
            ->upcoming()
            ->orderBy('event_date', 'asc')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
}