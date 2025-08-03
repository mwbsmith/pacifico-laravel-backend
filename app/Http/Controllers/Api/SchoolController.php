<?php
// app/Http/Controllers/Api/SchoolController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolInfo;
use Illuminate\Http\JsonResponse;

class SchoolController extends Controller
{
    public function info(): JsonResponse
    {
        $schoolInfo = SchoolInfo::first();
        
        if (!$schoolInfo) {
            $schoolInfo = [
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
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $schoolInfo
        ]);
    }
}