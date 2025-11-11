<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    /** @use HasFactory<\Database\Factories\ContentFactory> */
    use HasFactory, Sluggable;

    protected $slugColumnFrom = 'title';

    protected $fillable = ['title', 'description', 'code', 'body', 'type', 'slug', 'cover'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:d/m/Y H:i'
        ];
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    #[Scope]
    public function getWithActiveVideos(Builder $query): Builder
    {
        return $query->whereHas(
            'videos',
            fn($query) =>$query->whereNotNull('code')
                              ->whereIsProcessed(true)
        );
    }
}
