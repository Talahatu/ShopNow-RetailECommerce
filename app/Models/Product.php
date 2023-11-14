<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    public static function getProducts($userID, $type)
    {
        return Product::join("shops", "shops.id", "products.shop_id")
            ->where([
                ["shops.user_id", $userID],
                ["products.status", $type]
            ])->get(["products.id", "products.name", "products.SKU", 'products.price', 'products.stock']);
    }
}
