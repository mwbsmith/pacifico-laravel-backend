<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInfo extends Model
{
    use HasFactory;

    protected $table = 'school_info';
    
    protected $fillable = [
        'name',
        'description', 
        'address',
        'phone',
        'email',
        'hours'
    ];

    protected function casts(): array
    {
        return [
            'hours' => 'array',
        ];
    }

    // Laravel 12 query scopes
    public function scopeActive($query)
    {
        return $query->whereNotNull('name');
    }
}