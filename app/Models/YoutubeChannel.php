<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'branding', 'cId', 'title', 'description', 'image', 'publishedAt', 'keywords', 'customUrl', 'state', 'etag'])]
class YoutubeChannel extends Model
{
    public $timestamps = false;

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'branding' => 'array',
            'image' => 'array',
            'publishedAt' => 'datetime',
        ];
    }
}
