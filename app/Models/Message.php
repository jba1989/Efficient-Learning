<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    protected $table = 'message';

    public $timestamps = true;

    protected $fillable = [
        'classId',
        'titleId',
        'message',
        'userName',
        'father',
        'like',
        'dislike',
    ];
}
