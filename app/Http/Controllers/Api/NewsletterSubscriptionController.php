<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Http\Requests\StoreNewsletterSubscriptionRequest;
use App\Models\NewsletterSubscription;

class NewsletterSubscriptionController extends Controller
{
    /**
     * Store a new newsletter subscription.
     */
    public function store(request $request)
    {
        // $validated = NewsletterSubscription::create($request->validated());

        // validate incoming data

        /*
        body: JSON.stringify({
          full_name: newsletterForm.fullName,
          email: newsletterForm.email,
          whatsapp: newsletterForm.whatsapp,
          mailing_list: newsletterForm.mailingList,
          */
        
        $validated = $request->validate([
            'full_name'   => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'whatsapp'   => ['nullable', 'string', 'max:50'],
            'mailing_list'=> ['boolean'],
        ]);

        // Strip out the non-digits from the whatsapp number
        $whatsapp = $validated['whatsapp'] ?? null;

        if ($whatsapp) {
            // keep only digits
            $whatsapp = preg_replace('/\D/', '', $whatsapp);
        }

        // map camelCase input to snake_case DB fields
        $subscription = NewsletterSubscription::create([
            'full_name'        => $validated['full_name'],
            'email'            => $validated['email'],
            'whatsapp_number'  => $whatsapp,
            'also_mailing_list'=> $validated['mailing_list'] ?? false,
        ]);

        return response()->json([
            'success' => true,
            'data'    => $subscription,
        ], 201);
    }
}