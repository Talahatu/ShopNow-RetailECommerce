<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockHistory extends Model
{
    use HasFactory;

    protected $table = "product_stock_history";

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id", "id");
    }
}
