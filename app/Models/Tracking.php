<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = [
        "item_id",
        "school_id",
        "priority",
        "date_moved",
        "action",
        "reference_number",
        "additional_info",
        "status"
    ];
}
