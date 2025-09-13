<?php

// app/Models/NewsletterSubscription.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    protected $fillable = [
        'full_name', 'email', 'whatsapp_number', 'also_mailing_list',
    ];
}