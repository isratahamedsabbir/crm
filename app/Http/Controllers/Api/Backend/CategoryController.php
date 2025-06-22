<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index()
    {
        $category = Category::where('status', 'active')->get();
        $data = [
            'categories' => $category
        ];
        return Helper::jsonResponse(true, 'Category', 200, $data);

    }
}
