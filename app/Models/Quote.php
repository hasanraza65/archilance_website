<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'company', 'services', 'project_type', 'size',
        'scope', 'timeline', 'engagement', 'notes', 'hours_low', 'hours_high',
        'price_low', 'price_high', 'recommended_plan', 'breakdown', 'ip', 'is_read',
    ];

    protected $casts = [
        'services' => 'array',
        'breakdown' => 'array',
        'is_read' => 'boolean',
    ];
}
