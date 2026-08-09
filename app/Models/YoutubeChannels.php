<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'branding', 'cId', 'title', 'description', 'image', 'publishedAt', 'keywords', 'customUrl', 'state', 'etag'])]
class YoutubeChannels extends Model
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
            'id' => 'string',
            'cId' => 'integer',
            'title' => 'string',
            'branding' => 'array',
            'image' => 'array',
            'state' => 'boolean',
            'publishedAt' => 'datetime',
        ];
    }
}
