<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\SchoolInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Use PostgreSQL transaction for better performance
        DB::transaction(function () {
            // Create school info
            SchoolInfo::create([
                'name' => 'Pacifico Internacional',
                'description' => 'Waldorf-Inspired Learning',
                'address' => '123 Ocean Breeze Avenue, Costa Rica, Guanacaste 50101',
                'phone' => '+506 2345-6789',
                'email' => 'info@pacificointernacional.edu',
                'hours' => [
                    'grades' => 'Monday - Friday: 8:00 AM - 2:15 PM',
                    'kindergarten' => 'Monday - Friday: 8:00 AM - 1:00 PM',
                    'extended_care' => 'Until 2:15 PM'
                ]
            ]);

            // Bulk insert events for better PostgreSQL performance
            $events = [
                [
                    'title' => 'Spring Festival',
                    'description' => 'Celebrate the renewal of spring with traditional dances, flower crowns, and nature crafts.',
                    'event_date' => '2024-03-15',
                    'color' => 'pink',
                    'icon' => 'sun',
                    'is_featured' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Ocean Day',
                    'description' => 'Dive into marine conservation with beach cleanup, aquarium visits, and ocean-themed projects.',
                    'event_date' => '2024-04-22',
                    'color' => 'blue',
                    'icon' => 'waves',
                    'is_featured' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Jungle Expedition',
                    'description' => 'Explore rainforest ecosystems through interactive exhibits and indigenous culture presentations.',
                    'event_date' => '2024-05-10',
                    'color' => 'green',
                    'icon' => 'tree-pine',
                    'is_featured' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Parent Workshop',
                    'description' => 'Join our community for Waldorf parenting insights and family activity sessions.',
                    'event_date' => '2024-06-01',
                    'color' => 'yellow',
                    'icon' => 'heart',
                    'is_featured' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Summer Solstice',
                    'description' => 'Welcome summer with outdoor games, storytelling, and a community feast.',
                    'event_date' => '2024-06-21',
                    'color' => 'purple',
                    'icon' => 'sun',
                    'is_featured' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Harvest Festival',
                    'description' => 'Celebrate autumn\'s bounty with our school garden harvest and thanksgiving activities.',
                    'event_date' => '2024-09-22',
                    'color' => 'orange',
                    'icon' => 'leaf',
                    'is_featured' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            Event::insert($events);
        });
    }
}