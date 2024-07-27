<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories($perPage = 10)
    {
        return Category::paginate($perPage);
    }

    public function createCategory($data)
    {
        return Category::create($data);
    }

    public function getCategoryById($id)
    {
        return Category::find($id);
    }

    public function updateCategory($id, $data)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return null;
        }

        $category->update($data);
        return $category;
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return false;
        }

        $category->delete();
        return true;
    }
}
