<?php
// app/Http/Controllers/Api/GoogleCalendarController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;

class GoogleCalendarController extends Controller
{
    public function index(GoogleCalendarService $calendar)
    {
        return response()->json([
            'events' => $calendar->getUpcomingEvents(),
        ]);
    }
}