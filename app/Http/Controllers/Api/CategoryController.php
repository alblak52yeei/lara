<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Список категорий
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Category::query();

        // Поиск через Query Scope
        if ($request->has('search')) {
            $query->search($request->get('search'));
        }

        // Загрузка связей
        if ($request->has('with_contacts')) {
            $query->withCount('contacts');
        }

        $categories = $query->get();

        return CategoryResource::collection($categories);
    }

    /**
     * Создание категории
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = Category::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Просмотр категории
     */
    public function show(string $id): JsonResponse
    {
        $category = Category::with('contacts')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Обновление категории
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $category->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category->fresh()),
        ]);
    }

    /**
     * Мягкое удаление категории
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Категория успешно удалена',
        ]);
    }
}
