<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class classList extends Model
{
    protected $table = 'class_list';

    public $timestamps = false;

    protected $fillable = [
        'classId',
        'className',
        'teacher',
        'classId',
        'className',
        'teacher',
        'likeCount',
        'dislikeCount',
        'classType',
        'school',
    ];
}
