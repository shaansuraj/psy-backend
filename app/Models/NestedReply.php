<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NestedReply extends Model
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

    public function getUser($id)
    {
        return \App\Models\AppUser::where('id', $id)->select('id', 'user_name', 'profile')->get();
    }

    public function comment()
    {
        return $this->belongsTo(\App\Models\Comment::class);
    }

    public function parent()
    {
        return $this->belongsTo(\App\Models\NestedReply::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(\App\Models\NestedReply::class, 'parent_id');
    }
}
