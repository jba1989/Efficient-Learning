<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TitleList extends Model
{
    protected $table = 'title_list';

    public $timestamps = false;

    protected $guarded = [
        'id',
    ];
    
    protected $fillable = [
        'classId',
        'titleId',
        'title',
        'videoLink',
    ];

    public function classes()
    {
        return $this->belongsTo('App\Models\ClassList', 'classId', 'classId');
    }
}
