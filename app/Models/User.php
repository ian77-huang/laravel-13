<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FriendshipStatus;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'is_active', 'is_admin'])]
#[Appends(['friend_status'])]
#[Hidden(['password', 'remember_token', 'sent_status', 'received_status'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    use HasRoles;

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the profile associated with this user.
     *
     * @return HasOne<UserProfile, $this>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    /**
     * Get the notifications received by this user.
     *
     * @return HasMany<Notifications, $this>
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notifications::class, 'user_id');
    }

    /**
     * Get the friend requests this user has sent.
     *
     * @return HasMany<Friendships, $this>
     */
    public function sentFriendships(): HasMany
    {
        return $this->hasMany(Friendships::class, 'user_id');
    }

    /**
     * Get the friend requests this user has received.
     *
     * @return HasMany<Friendships, $this>
     */
    public function receivedFriendships(): HasMany
    {
        return $this->hasMany(Friendships::class, 'friend_id');
    }

    /**
     * The friendship status relative to the viewer, computed from the joined
     * friendship rows loaded by the WithFriendStatus scope. Null when the
     * user was not queried through that scope.
     */
    protected function friendStatus(): Attribute
    {
        return Attribute::get(function () {
            if (! array_key_exists('sent_status', $this->attributes)) {
                return null;
            }

            $sent = isset($this->attributes['sent_status'])
                ? FriendshipStatus::from($this->attributes['sent_status'])
                : null;
            $received = isset($this->attributes['received_status'])
                ? FriendshipStatus::from($this->attributes['received_status'])
                : null;

            return match (true) {
                $sent === FriendshipStatus::Accepted || $received === FriendshipStatus::Accepted => 'friend',
                $sent === FriendshipStatus::PendingSent => 'pending_sent',
                $received === FriendshipStatus::PendingSent => 'pending_received',
                default => 'none',
            };
        });
    }

    /**
     * Scope to list active, non-admin users with their friendship status relative to the viewer.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeWithFriendStatus(Builder $query, int $viewerId): Builder
    {
        return $query
            ->leftJoin('users_profile as usersProfile', 'usersProfile.user_id', '=', 'users.id')
            ->leftJoin('friendships as sent', function ($join) use ($viewerId) {
                $join->on('sent.friend_id', '=', 'users.id')
                    ->where('sent.user_id', '=', $viewerId);
            })
            ->leftJoin('friendships as received', function ($join) use ($viewerId) {
                $join->on('received.user_id', '=', 'users.id')
                    ->where('received.friend_id', '=', $viewerId);
            })
            ->whereNot('users.id', $viewerId)
            ->where('users.is_admin', false)
            ->where('users.is_active', true)
            ->select(
                'users.id',
                'users.name',
                'users.email as account',
                'usersProfile.avatar_url',
                'sent.status as sent_status',
                'received.status as received_status',
            )
            ->orderBy('users.name');
    }
}
