<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassListLike extends Model
{
    protected $table = 'class_list_like';

    public $timestamps = false;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'classId',
        'likeCount',
        'dislikeCount',
    ];

    protected $casts = [
        'likeCount' => 'array',
        'dislikeCount' => 'array',
    ];
}
