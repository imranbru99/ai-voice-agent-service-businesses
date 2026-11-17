<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'voice_call_id', 'sequence', 'speaker', 'start_ms', 'end_ms', 'content',
    'intent', 'sentiment', 'ai_highlighted', 'entities',
])]
class CallTranscript extends Model
{
    protected function casts(): array
    {
        return [
            'ai_highlighted' => 'boolean',
            'entities' => 'array',
        ];
    }

    public function voiceCall(): BelongsTo
    {
        return $this->belongsTo(VoiceCall::class);
    }
}
