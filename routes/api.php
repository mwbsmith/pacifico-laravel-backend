<?php
// routes/api.php
use App\Http\Controllers\Api\GoogleCalendarController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Support\Facades\Route;

/* For email test with MAILGUN */
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('/school-info', [SchoolController::class, 'info']);
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/featured', [EventController::class, 'featured']);
    Route::post('/contact', [ContactController::class, 'store']);
    
    // Calendar integration
    Route::get('/calendar', function () {
        return response()->json([
            'success' => true,
            'calendar_url' => 'https://calendar.google.com/calendar/embed?src=93e6bc2fe2660ddcc925e876ff13dd04394372fc3d48130f6617c431e92dbbd6%40group.calendar.google.com&ctz=America%2FCosta_Rica'
        ]);
    });

    Route::get('/calendar/events', [GoogleCalendarController::class, 'index']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
    
    // Contact form routes
    Route::prefix('contact')->group(function () {
        Route::post('/message', [MessageController::class, 'store'])
             ->middleware(['throttle:5,1']); // Rate limit: 5 requests per minute
        
        Route::get('/messages', [MessageController::class, 'index'])
             ->middleware(['auth:sanctum']); // Protect admin route
    });

    // TEST EMAIL ROUTE WITH MAILGUN
    Route::get('/send-test', function () {
        $recipients = [
            'mwbsmith@gmail.com',
            'helene@waldorf.cr',
        ];
        Mail::to($recipients)->send(new TestEmail());
        return 'Email sent';
    });

});