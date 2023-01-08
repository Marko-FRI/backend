<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class FooterController extends Controller
{
    public function index() {
        $categories = Category::select('id_category', 'name', 'image_path')->get();
        
        foreach ($categories as $category)
            $category->image_path = $this->CATEGORY_IMAGES_URL . $category->image_path;

        $response = [
            'categories' => $categories
        ];

        return $response;
    }
}
