<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'timezone', 'business_open', 'business_close', 'weekdays',
    'ai_answers', 'allow_emergency_booking', 'escalate_to_phone',
    'greeting_script', 'is_active',
])]
class AfterHoursRule extends Model
{
    protected function casts(): array
    {
        return [
            'weekdays' => 'array',
            'ai_answers' => 'boolean',
            'allow_emergency_booking' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
