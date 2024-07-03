<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
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
}
