<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\AppUser::class, 'user_id', 'id');
    }

    protected $appends = ['human_readable_created_at'];

    public function getHumanReadableCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
    }
}
