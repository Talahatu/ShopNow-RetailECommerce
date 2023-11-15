<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        $data = Category::where([
            ["name", "LIKE", "%" . $request->get("q") . "%"]
        ])->get(["name", "id"]);
        return response()->json($data);
    }
}
