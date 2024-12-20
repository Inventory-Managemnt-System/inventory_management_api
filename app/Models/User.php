<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     * all hidden
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

     public function notifications(){
        return $this->hasMany(Notification::class, 'user_id');
     }
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class);
    }


    /**
     * Get the location associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }


    /**
     * Get all of the schoolqa for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schoolqa(): HasMany
    {
        return $this->hasMany(School::class, "qa_id");
    }
}
