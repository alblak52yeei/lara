<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Список контактов
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Contact::query();

        // Query Scopes для фильтрации
        if ($request->has('status')) {
            $query->byStatus($request->get('status'));
        }

        if ($request->has('new_only') && $request->get('new_only')) {
            $query->new();
        }

        if ($request->has('with_category') && $request->get('with_category')) {
            $query->withCategory();
        }

        if ($request->has('without_category') && $request->get('without_category')) {
            $query->withoutCategory();
        }

        // Поиск через Query Scope
        if ($request->has('search')) {
            $query->search($request->get('search'));
        }

        // Загрузка связей
        if ($request->has('with_category')) {
            $query->with('category');
        }

        if ($request->has('with_comments')) {
            $query->with('comments');
            $query->withCount('comments');
        }

        $contacts = $query->get();

        return ContactResource::collection($contacts);
    }

    /**
     * Создание контакта
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
            'status' => 'sometimes|in:new,in_progress,completed,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['status'] = $data['status'] ?? 'new';

        $contact = Contact::create($data);

        return response()->json([
            'success' => true,
            'data' => new ContactResource($contact->load('category')),
        ], 201);
    }

    /**
     * Просмотр контакта
     */
    public function show(string $id): JsonResponse
    {
        $contact = Contact::with(['category', 'comments'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new ContactResource($contact),
        ]);
    }

    /**
     * Обновление контакта
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'message' => 'sometimes|required|string|max:1000',
            'status' => 'sometimes|in:new,in_progress,completed,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $contact->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => new ContactResource($contact->fresh()->load('category')),
        ]);
    }

    /**
     * Мягкое удаление контакта
     */
    public function destroy(string $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Контакт успешно удален',
        ]);
    }
}
