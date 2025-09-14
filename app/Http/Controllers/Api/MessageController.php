<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Mail\ContactMessage;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Exception;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request): JsonResponse
    {
        try {
            // Store the message in the database
            $message = Message::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'message' => $request->message,
                'sent_at' => now(),
            ]);

            // Get recipients from config and normalize
            $rawRecipients = (string) config('mail.contact_recipient', 'mwbsmith@gmail.com');

            $recipients = collect(explode(',', $rawRecipients))
                ->map(fn ($e) => trim($e))
                ->filter(fn ($e) => $e !== '' && filter_var($e, FILTER_VALIDATE_EMAIL))
                ->values()
                ->all();

            // Send one email per recipient
            foreach ($recipients as $email) {
                Mail::to($email)->send(new ContactMessage($message->fresh()));
            }

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully. We will get back to you soon!',
                'data'    => [
                    'id'      => $message->id,
                    'sent_at' => $message->sent_at->toISOString(),
                ],
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error sending your message. Please try again later.',
                'error'   => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $messages = Message::orderBy('sent_at', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data'    => $messages,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving messages',
                'error'   => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}