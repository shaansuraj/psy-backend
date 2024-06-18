<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AppUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
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
        'password' => 'hashed',
        'created_at' => 'datetime:F Y',
    ];

    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    public function followings()
    {
        return $this->belongsToMany(AppUser::class, 'follower_user', 'follower_id', 'user_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(AppUser::class, 'follower_user', 'user_id', 'follower_id')->withTimestamps();
    }

    public function follows($id)
    {
        return $this->followings()->where('user_id', $id)->exists();
    }

    public function likes()
    {
        return $this->belongsToMany(\App\Models\Post::class, 'post_like')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'user_id', 'id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function scopeSelectbasic($query)
    {
        $query->select('name', 'profile', 'id', 'about', 'user_name', 'created_at');
    }
}
