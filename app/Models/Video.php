<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use Sluggable;

    protected $slugColumnFrom = 'name';

    protected $fillable = [
        'name','code', 'description', 'thumb',
        'video', 'is_processed', 'slug'
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
