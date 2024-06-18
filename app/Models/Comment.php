<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['human_readable_created_at'];

    public function post() {
        return $this->hasOne(\App\Models\Post::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\AppUser::class, 'user_id', 'id');
    }

    public function nestedReplies()
    {
        return $this->hasMany(\App\Models\NestedReply::class);
    }

    public function getHumanReadableCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
    }
}
