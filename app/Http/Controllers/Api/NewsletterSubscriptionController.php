<?php
// app/Http/Controllers/Api/NewsletterSubscriptionController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsletterSubscriptionRequest;
use App\Models\NewsletterSubscription;

class NewsletterSubscriptionController extends Controller
{
    public function store(StoreNewsletterSubscriptionRequest $request)
    {
        $subscription = NewsletterSubscription::create($request->validated());

        return response()->json([
            'ok' => true,
            'data' => $subscription,
            'message' => 'Subscription saved.',
        ], 201);
    }
}