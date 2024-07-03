<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        "school_name",
        "head_teacher_name",
        "item_name",
        "quantity",
        "comment"
    ];
}
