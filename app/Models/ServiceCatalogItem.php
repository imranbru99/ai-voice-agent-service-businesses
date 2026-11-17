<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'sku', 'name', 'trade', 'slug', 'description', 'base_price',
    'typical_duration_minutes', 'bookable_after_hours', 'is_active', 'tags',
])]
class ServiceCatalogItem extends Model
{
    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'bookable_after_hours' => 'boolean',
            'is_active' => 'boolean',
            'tags' => 'array',
        ];
    }

    public function jobBookings(): HasMany
    {
        return $this->hasMany(JobBooking::class);
    }
}
