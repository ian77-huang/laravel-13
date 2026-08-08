<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['id', 'channelId', 'title', 'cId', 'image', 'player', 'publishedAt', 'description', 'channelTitle', 'etag'])]
class YoutubePlaylist extends Model
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
            'image' => 'array',
            'player' => 'array',
            'publishedAt' => 'datetime',
        ];
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(YoutubeChannel::class, 'channelId', 'id');
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(
            YoutubeVideo::class,
            'youtube_playlist_videos',
            'playlistId',
            'videoId'
        );
    }
}
