<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['human_readable_created_at'];

    public function getHumanReadableCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\AppUser::class, 'user_id', 'id');
    }

    public function images()
    {
        return $this->hasMany('App\Models\Image', 'post_id', 'id');
    }

    public function likes()
    {
        return $this->belongsToMany(\App\Models\AppUser::class, 'post_like')->withTimestamps();
    }

    public function shares()
    {
        return $this->hasMany(\App\Models\PostShare::class, 'post_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'post_id', 'id');
    }

    public function scopeFetch($query)
    {
        $query->where('verified', 1)
            ->with([
                'user:id,name,profile,user_name,about',
            ])
            ->with(['images' => function ($query) {
                $query->select('name', 'post_id');
            }])
            ->withCount('likes')
            ->withCount('comments')
            ->withCount('shares');
    }
}
