<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
