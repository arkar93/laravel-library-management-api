<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\DeleteCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Category\ViewCategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(ViewCategoryRequest $request)
    {
        $perPage = $request->input('per_page', 10);
        $categories = $this->categoryService->getAllCategories($perPage);

        $response = [
            'data' => $categories->items(),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'limit' => $categories->perPage(),
                'total_pages' => $categories->lastPage(),
                'total' => $categories->total(),
            ],
        ];

        return response()->json($response);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->createCategory($request->validated());
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    public function show(ViewCategoryRequest $request, $id)
    {
        $category = $this->categoryService->getCategoryById($id);

        if (is_null($category)) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->categoryService->updateCategory($id, $request->validated());

        if (is_null($category)) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ], 200);
    }

    public function destroy(DeleteCategoryRequest $request, $id)
    {
        $deleted = $this->categoryService->deleteCategory($id);

        if (!$deleted) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}

