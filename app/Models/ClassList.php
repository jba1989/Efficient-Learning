<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassList extends Model
{
    protected $table = 'class_list';

    public $timestamps = true;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'classId',
        'className',
        'teacher',
        'classId',
        'className',
        'teacher',
        'classType',
        'school',
        'countTitle',
        'description',
    ];

    protected $casts = [
        'description' => 'array',
    ];

    public function titles()
    {
        return $this->hasMany('App\Models\TitleList', 'classId', 'classId');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message', 'classId', 'classId');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\ClassListLike', 'classId', 'classId');
    }
}
