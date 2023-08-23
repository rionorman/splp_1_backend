<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CategoryAPIController extends Controller
{
    public function getCategories()
    {
        $rows = Category::all();
        return response()->json([
            'success' => true,
            'message' => "Data Kategori",
            'data' => $rows
        ]);
    }
}
