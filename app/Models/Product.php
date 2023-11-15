<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = ["name", "description", 'weight', "stock", "price", "status", "rating", "shop_id", "category_id"];

    public static function getProducts($userID, $type)
    {
        return Product::join("shops", "shops.id", "products.shop_id")
            ->where([
                ["shops.user_id", $userID],
                ["products.status", $type]
            ])->get(["products.id", "products.name", "products.SKU", 'products.price', 'products.stock']);
    }
    public static function insertNewProduct($datas, $shopID)
    {
        DB::transaction(function () use ($datas, $shopID) {
            $category = Category::find($datas['category']);
            $brand = Brand::find($datas['brand']);
            $newProd = new Product();
            $newProd->shop_id = $shopID;
            $newProd->category_id = $datas['category'];
            $newProd->brand_id = $datas['brand'];
            $newProd->name = $datas["name"];
            $newProd->description = $datas["desc"];
            $newProd->weight = $datas["weight"];
            $newProd->stock = $datas["stock"];
            $newProd->price = $datas["price"];
            $newProd->status = "live";
            $newProd->SKU = Product::getSKU($category->name, $brand->name, $datas["name"]);
            $newProd->save();
        });
        return true;
    }

    private function getSKU($category, $brand, $name)
    {
        return substr($category, 0, 1) . substr($brand, 0, 1) . substr($name, 0, 1) . now()->format("Ymds");
    }
}
