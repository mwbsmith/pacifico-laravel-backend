<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Carbon\Carbon;

class GoogleCalendarService
{
    /*
    protected $calendarId;
    protected $service;
    */

    protected string $calendarId;
    protected Google_Service_Calendar $service;

    public function __construct(Google_Service_Calendar $service)
    {
        $this->calendarId = config('services.google.calendar_id');
        $this->service = $service;
    }
/*
    public function __construct()
    {
        $this->calendarId = config('services.google.calendar_id'); // Define in config/services.php

        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google-calendar.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

        $this->service = new Google_Service_Calendar($client);
    }
*/
    public function getUpcomingEvents($maxResults = 20)
    {
    $params = [
        'maxResults' => $maxResults,
        'orderBy' => 'startTime',
        'singleEvents' => true,
        'timeMin' => now()->toRfc3339String(),
    ];

    $events = $this->service->events->listEvents($this->calendarId, $params);

    return collect($events->getItems())->map(function ($event) {
        $startObj = $event->getStart();
        $endObj = $event->getEnd();

        $startDateTime = $startObj->getDateTime();
        $startDate = $startObj->getDate();

        $endDateTime = $endObj->getDateTime();
        $endDate = $endObj->getDate();

        $isAllDay = is_null($startDateTime);

        if ($isAllDay) {
            $start = Carbon::parse($startDate)->setTimezone(config('app.timezone'));
            $end = Carbon::parse($endDate)->setTimezone(config('app.timezone'));
        } else {
            $start = Carbon::parse($startDateTime)->setTimezone(config('app.timezone'));
            $end = Carbon::parse($endDateTime)->setTimezone(config('app.timezone'));
        }

        return [
            'id' => $event->getId(),
            'status' => $event->getStatus(),
            'summary' => $event->getSummary(),
            'description' => $event->getDescription(),
            'location' => $event->getLocation(),
            'htmlLink' => $event->getHtmlLink(),
            'created' => $event->getCreated(),
            'updated' => $event->getUpdated(),
            'organizer' => $event->getOrganizer(),
            'creator' => $event->getCreator(),
            'attendees' => $event->getAttendees(),
            'hangoutLink' => $event->getHangoutLink(),
            'recurrence' => $event->getRecurrence(),
            'recurringEventId' => $event->getRecurringEventId(),
            'originalStartTime' => $event->getOriginalStartTime(),
            'visibility' => $event->getVisibility(),
            'sequence' => $event->getSequence(),
            'iCalUID' => $event->getICalUID(),
            'reminders' => $event->getReminders(),
            'colorId' => $event->getColorId(),
            'kind' => $event->getKind(),
            'etag' => $event->getEtag(),

            // Date/time breakdown
            'isAllDay' => $isAllDay,
            'startDate' => $start->toDateString(),
            'startTime' => $isAllDay ? null : $start->format('H:i:s'),
            'endDate' => $end->toDateString(),
            'endTime' => $isAllDay ? null : $end->format('H:i:s'),
            'timeZone'   => $start->getTimezone()->getName(),

            // Full datetime strings for reference
            'startDateTime' => $isAllDay ? null : $start->toDateTimeString(),
            'endDateTime' => $isAllDay ? null : $end->toDateTimeString(),
        ];
    });
}
}

