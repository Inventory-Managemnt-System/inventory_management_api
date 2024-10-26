<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "qa_id",
        "title",
        "description",
        "slug"
    ];

    /**
     * Get all of the newitems for the Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newitems(): HasMany
    {
        return $this->hasMany(NewItem::class, 'location_id');
    }
    
}
