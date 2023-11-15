<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function getBrands(Request $request)
    {
        $data = Brand::join("brand_categories AS bc", "bc.brand_id", "brands.id")
            ->where([
                ["brands.name", "LIKE", "%" . $request->get("q") . "%"],
                ["bc.category_id", $request->get("id")]
            ])->get(["brands.name", "brands.id"]);
        return response()->json($data);
    }
}
