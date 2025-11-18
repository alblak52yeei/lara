<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Список комментариев
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Comment::query();

        // Query Scopes для фильтрации
        if ($request->has('user_id')) {
            $query->byUser($request->get('user_id'));
        }

        if ($request->has('commentable_type') && $request->has('commentable_id')) {
            $query->forModel($request->get('commentable_type'), $request->get('commentable_id'));
        }

        // Поиск через Query Scope
        if ($request->has('search')) {
            $query->search($request->get('search'));
        }

        // Загрузка связей
        if ($request->has('with_user')) {
            $query->with('user');
        }

        if ($request->has('with_commentable')) {
            $query->with('commentable');
        }

        $comments = $query->get();

        return CommentResource::collection($comments);
    }

    /**
     * Создание комментария (полиморфная связь)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'commentable_type' => 'required|string|in:App\Models\Contact,App\Models\Category',
            'commentable_id' => 'required|integer',
            'user_id' => 'nullable|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Проверка существования модели
        $modelClass = $request->commentable_type;
        $model = $modelClass::find($request->commentable_id);

        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'Модель не найдена',
            ], 404);
        }

        $comment = Comment::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => new CommentResource($comment->load(['user', 'commentable'])),
        ], 201);
    }

    /**
     * Просмотр комментария
     */
    public function show(string $id): JsonResponse
    {
        $comment = Comment::with(['user', 'commentable'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new CommentResource($comment),
        ]);
    }

    /**
     * Обновление комментария
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'content' => 'sometimes|required|string|max:1000',
            'user_id' => 'sometimes|nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $comment->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => new CommentResource($comment->fresh()->load(['user', 'commentable'])),
        ]);
    }

    /**
     * Мягкое удаление комментария
     */
    public function destroy(string $id): JsonResponse
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Комментарий успешно удален',
        ]);
    }
}
