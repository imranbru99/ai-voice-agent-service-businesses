<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'name', 'description', 'enabled'])]
class ModuleSetting extends Model
{
    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }
}
