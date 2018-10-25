<?php

namespace App\Repositories;

use App\Models\ClassList;
use App\Models\TotalClass;

class ClassRepository
{   
    public function classList()
    {
        return ClassList::paginate(2);        
    }
}
