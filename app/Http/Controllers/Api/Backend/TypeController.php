<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Type;

class TypeController extends Controller
{

    public function index()
    {
        $types = Type::where('status', 'active')->get();
        $data = [
            'types' => $types
        ];
        return Helper::jsonResponse(true, 'Category', 200, $data);

    }
}
