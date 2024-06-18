<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:d M Y',
    ];

    public function byUser()
    {
        return $this->belongsTo(\App\Models\AppUser::class, 'by_user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\AppUser::class, 'user_id', 'id');
    }
}
