<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllSchools extends Model
{
    use HasFactory;
    protected $primaryKey = 'LGA';

    public function items(){
        return $this->hasMany(Item::class, 'LGA', 'LGA');
    }
}
