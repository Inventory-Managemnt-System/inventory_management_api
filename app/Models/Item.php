<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "barcode_id",
        "item_code",
        "item_name",
        "class",
        "subject_category",
        "distribution",
        "quantity",
        "category",
        "category_id",
        "image",
        "start_location",
        "current_location"
    ];

    /**
     * Get the location associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
