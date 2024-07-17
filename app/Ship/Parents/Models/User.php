<?php

declare(strict_types=1);

namespace App\Ship\Parents\Models;

use App\Ship\Core\Abstracts\Models\UserModel;
use App\Ship\Parents\Contracts\UserModelContract;
use App\Ship\Parents\Factories\UserFactory;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User.
 *
 * @property int $id
 */
class User extends UserModel implements CanResetPassword, UserModelContract
{
    use HasApiTokens;
    use Notifiable;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string
     */
    protected string $guard_name = 'sanctum';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'biography',
        'picture',
        'email',
        'phone',
        'password',
        'ip_register',
        'phone_verified',
        'email_verified',
        'email_verified_at',
    ];

    protected $guarded = [
        'email',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified' => 'bool',
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return string
     */
    public function getPictureAttribute(): ?string
    {
        if ($this->attributes['picture']) {
            return config('app.url') . '/storage/app/profiles/' . $this->attributes['picture'];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function getIsStaffAttribute(): bool
    {
        return false;
    }

    /**
     * @return UserFactory|null
     */
    protected static function newFactory(): ?UserFactory
    {
        return UserFactory::new();
    }
}
