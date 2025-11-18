<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
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
     * Связь: контакт принадлежит категории
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Полиморфная связь: контакт имеет много комментариев
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Query Scope: фильтр по статусу
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Query Scope: новые контакты
     */
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    /**
     * Query Scope: поиск по имени, email или телефону
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%");
    }

    /**
     * Query Scope: контакты с категорией
     */
    public function scopeWithCategory(Builder $query): Builder
    {
        return $query->whereNotNull('category_id');
    }

    /**
     * Query Scope: контакты без категории
     */
    public function scopeWithoutCategory(Builder $query): Builder
    {
        return $query->whereNull('category_id');
    }
}
