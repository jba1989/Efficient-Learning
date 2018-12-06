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
        'likeUserList',
        'dislikeUserList',
        'likeCount',
        'dislikeCount',
    ];

    protected $casts = [
        'likeUserList' => 'array',
        'dislikeUserList' => 'array',
    ];
}
