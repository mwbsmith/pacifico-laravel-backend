<?php
// app/Services/GoogleCalendarService.php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarService
{
    protected $calendarId;
    protected $service;

    public function __construct()
    {
        $this->calendarId = config('services.google.calendar_id'); // set this in config/services.php

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
            return [
                'id' => $event->getId(),
                'summary' => $event->getSummary(),
                'description' => $event->getDescription(),
                'location' => $event->getLocation(),
                'start' => $event->getStart()->getDateTime() ?: $event->getStart()->getDate(),
                'end' => $event->getEnd()->getDateTime() ?: $event->getEnd()->getDate(),
            ];
        });
    }
}