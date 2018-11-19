<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    protected $table = 'message';

    public $timestamps = true;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'classId',
        'message',
        'userName',
        'fatherId',
        'like',
        'dislike',
    ];
}
