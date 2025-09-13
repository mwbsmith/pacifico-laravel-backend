<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Http\Requests\StoreNewsletterSubscriptionRequest;
use App\Models\NewsletterSubscription;

class NewsletterSubscriptionController extends Controller
{
    /**
     * Store a new newsletter subscription.
     */
    public function store(StoreNewsletterSubscriptionRequest $request)
    {
        $validated = NewsletterSubscription::create($request->validated());

        // validate incoming data
        /*
        $validated = $request->validate([
            'fullName'   => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'whatsapp'   => ['nullable', 'string', 'max:50'],
            'mailingList'=> ['boolean'],
        ]);
        */

        // map camelCase input to snake_case DB fields
        $subscription = NewsletterSubscription::create([
            'full_name'        => $validated['full_name'],
            'email'            => $validated['email'],
            'whatsapp_number'  => $validated['whatsapp'] ?? null,
            'also_mailing_list'=> $validated['mailing_list'] ?? false,
        ]);

        return response()->json([
            'success' => true,
            'data'    => $subscription,
        ], 201);
    }
}