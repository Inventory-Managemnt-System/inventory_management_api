<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = [
        "item_name",
        "item_description",
        "brand",
        "category",
        "priority",
        "address",
        "picking_area",
        "building_number",
        "time_moved",
        "date_moved",
        "action",
        "reference_number",
        "additional_info",
        "status"
    ];
}
