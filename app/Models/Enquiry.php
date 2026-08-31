<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $table = 'enquiries';

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'service', 'budget', 'timeline',
        'source', 'nda', 'message', 'ip', 'page', 'is_read',
    ];

    protected $casts = ['nda' => 'boolean', 'is_read' => 'boolean'];
}
