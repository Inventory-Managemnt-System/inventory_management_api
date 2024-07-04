<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        "unique_id",
        "barcode_id",
        "name",
        "description",
        "brand",
        "category",
        "value",
        "image",
        "unit_cost",
        "quantity",
        "reorder_point",
        "supplier",
    ];

    public static function filterItems($params): array|\Illuminate\Database\Eloquent\Collection
    {
        $query = self::query();

        if(isset($params['date_range'])) {
            $query->whereBetween('created_at', $params['date_range']);
        }
        if(isset($params['category'])) {
            $query->where('category', $params['category']);
        }
        if(isset($params['stock_level'])) {
            $query->where('quantity', $params['stock_level']);
        }

        return $query->get();
    }
}
