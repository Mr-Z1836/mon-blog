<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'description'])]
class PostSeries extends Model
{
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->orderBy('series_part');
    }
}
