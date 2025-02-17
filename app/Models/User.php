<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\UserSaved;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that should be appended to the model's array and JSON form.
     *
     * @var list<string>
     */
    protected $appends = [
        'full_name',
        'middle_initial',
        'avatar'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'prefix_name',
        'first_name',
        'middle_name',
        'last_name',
        'suffix_name',
        'user_name',
        'email',
        'password',
        'photo',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }

    public function type(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
        );
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} " . ($this->middle_name ?? '') . " {$this->last_name}");
    }

    /**
     * Get the user's middle initial.
     *
     * @return string
     */
    public function getMiddleInitialAttribute(): string
    {
        return $this->middle_name ? strtoupper(substr($this->middle_name, 0, 1)) . '.' : '';
    }

    /**
     * Get the user's avatar URL.
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        return $this->photo ? asset('storage/' . $this->photo) : '';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->password)) {
                $user->password = bcrypt('defaultPassword123');
            }
        });

        static::created(function ($user) {
            event(new UserSaved($user));
        });

        static::updated(function ($user) {
            event(new UserSaved($user));
        });
    }


    /**
     * Get the user's details.
     *
     * @return HasOne|User
     */
    public function details(): HasOne|User
    {
        return $this->hasOne(Detail::class);
    }
}
