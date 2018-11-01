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

    /**
     * 查詢所有課程
     * 
     * @return array
     */
    public function show()
    {        
        return ClassList::paginate(30);
    }

    /**
     * 依條件查詢資料
     * 
     * @param array $conditions
     * @return array
     */
    public function showBy($conditions)
    {        
        return ClassList::where($conditions)->orderBy('id', 'asc')->paginate(30);
    }

    /**
     * 依條件查詢留言板資料
     * 
     * @param array $conditions
     * @return array
     */
    public function showMessageBy($conditions)
    {        
        return ClassList::find(1)->messages()->where($conditions)->orderBy('id', 'asc')->paginate(30);
    }
}
