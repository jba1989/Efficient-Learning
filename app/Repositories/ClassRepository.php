<?php

namespace App\Repositories;

use App\Models\ClassList;

class ClassRepository
{    
    /**
     * 插入一筆資料
     * 
     * @param array $conditions
     * @param array $contents
     */
    public function firstOrCreate($conditions, $contents)
    {
        ClassList::firstOrCreate($conditions, $contents);
    }

    /**
     * 更新或新增一筆資料
     * 
     * @param array $conditions
     * @param array $contents
     */
    public function updateOrCreate($conditions, $contents)
    {
        ClassList::updateOrCreate($conditions, $contents);
    }

    /**
     * 更新一筆資料
     * 
     * @param array $conditions
     * @param array $contents
     */
    public function update($conditions, $contents)
    {
        ClassList::where($conditions)->update($contents);
    }
}
