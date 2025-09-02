<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JoelButcher\Socialstream\HasConnectedAccounts;
use JoelButcher\Socialstream\SetsProfilePhotoFromUrl;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasConnectedAccounts;
    use HasFactory;
    use HasProfilePhoto {
        HasProfilePhoto::profilePhotoUrl as getPhotoUrl;
    }
    use HasTeams;
    use Notifiable;
    use SetsProfilePhotoFromUrl;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_name',
    ];

    protected $with = ['role'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the URL to the user's profile photo.
     */
    protected function profilePhotoUrl(): Attribute
    {
        return filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)
            ? Attribute::get(fn () => $this->profile_photo_path)
            : $this->getPhotoUrl();
    }

    # belong to role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_name', 'name');
    }

    /**
     * Get notifications for this user
     */
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_users')
                    ->withPivot(['is_read', 'read_at', 'sent_at'])
                    ->withTimestamps();
    }

    /**
     * Get unread notifications for this user
     */
    public function unreadNotifications()
    {
        return $this->notifications()->wherePivot('is_read', false);
    }

    /**
     * Get read notifications for this user
     */
    public function readNotifications()
    {
        return $this->notifications()->wherePivot('is_read', true);
    }

    /**
     * Get notifications created by this user
     */
    public function createdNotifications()
    {
        return $this->hasMany(Notification::class, 'created_by');
    }
}
