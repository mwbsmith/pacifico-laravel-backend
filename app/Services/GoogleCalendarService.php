<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Carbon\Carbon;

class GoogleCalendarService
{
    protected $calendarId;
    protected $service;

    public function __construct()
    {
        $this->calendarId = config('services.google.calendar_id'); // Define in config/services.php

        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google-calendar.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

        $this->service = new Google_Service_Calendar($client);
    }

    public function getUpcomingEvents($maxResults = 6)
    {
        $params = [
            'maxResults' => $maxResults,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => now()->toRfc3339String(),
        ];

        $events = $this->service->events->listEvents($this->calendarId, $params);

        return collect($events->getItems())->map(function ($event) {
            $startRaw = $event->getStart()->getDateTime() ?? $event->getStart()->getDate();
            $endRaw = $event->getEnd()->getDateTime() ?? $event->getEnd()->getDate();

            // Parse using Carbon and set timezone to app timezone
            $start = Carbon::parse($startRaw)->setTimezone(config('app.timezone'))->toDateTimeString();
            $end = Carbon::parse($endRaw)->setTimezone(config('app.timezone'))->toDateTimeString();

            return [
                'id' => $event->getId(),
                'summary' => $event->getSummary(),
                'description' => $event->getDescription(),
                'location' => $event->getLocation(),
                'start' => $start,
                'end' => $end,
            ];
        });
    }
}