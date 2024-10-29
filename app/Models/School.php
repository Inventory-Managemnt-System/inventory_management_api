<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "school_id",
        "qa_id",
        "website",
        "email",
        "phone_number",
        "level",
        "logo",
        "address",
        "city",
        "lga",
        "postal_code"
    ];

    public function items()
    {
        // return NewItem::where(['school_id' => $school_id])->get();
        return $this->hasMany(Item::class, 'school_id', 'school_id');
    }


    /**
     * Get all of the newitems for the Location
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newitems(): HasMany
    {
        return $this->hasMany(NewItem::class, 'school_id', 'school_id');
    }
}
