<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $fillable = [
        'ip_hash',
        'page_url',
        'event_type',
        'user_agent'
    ];
}
