<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'user_id',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Полиморфная связь: комментарий принадлежит модели (Contact, Category и т.д.)
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Связь: комментарий принадлежит пользователю
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Query Scope: комментарии пользователя
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Query Scope: поиск по содержимому
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('content', 'like', "%{$search}%");
    }

    /**
     * Query Scope: комментарии к определенной модели
     */
    public function scopeForModel(Builder $query, string $modelType, int $modelId): Builder
    {
        return $query->where('commentable_type', $modelType)
            ->where('commentable_id', $modelId);
    }
}
