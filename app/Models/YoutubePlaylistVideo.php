<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id', 'playlistId', 'videoId'])]
class YoutubePlaylistVideo extends Model
{
    public $timestamps = false;

    protected $keyType = 'string';

    public $incrementing = false;

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(YoutubePlaylist::class, 'playlistId', 'id');
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(YoutubeVideo::class, 'videoId', 'id');
    }
}
