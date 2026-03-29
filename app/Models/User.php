<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'avatar',
    ];

    protected $appends = ['avatar_url', 'initials', 'role', 'active_tasks'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Tasks assigned to this user. */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /** Team member record for this user. */
    public function teamMember()
    {
        return $this->hasOne(TeamMember::class);
    }

    /** Full public URL for the stored avatar. */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar ? Storage::url($this->avatar) : '';
    }

    /** Two-letter initials fallback. */
    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getRoleAttribute(): string
    {
        return $this->getRoleNames()->first() ?? '';
    }

    public function getActiveTasksAttribute(): int
    {
        return $this->tasks->count();
    }
}
