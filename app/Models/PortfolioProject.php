<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioProject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'badges',
        'image',
        'secondary_image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'badges' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Shape used by the public marketing React carousel.
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'badges' => $this->badges ?? [],
            'image' => $this->image,
            'secondaryImage' => $this->secondary_image,
        ];
    }
}
