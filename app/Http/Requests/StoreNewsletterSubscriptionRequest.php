<?php

// app/Http/Requests/StoreNewsletterSubscriptionRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterSubscriptionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'full_name'         => ['required','string','max:255'],
            // simpler email validation that works in Laravel 12
            'email'             => ['required','email','max:255'],
            'whatsapp_number'   => ['nullable','string','max:32','regex:/^\+?[0-9\s\-()]{6,}$/'],
            'also_mailing_list' => ['required','boolean'],
        ];
    }
}