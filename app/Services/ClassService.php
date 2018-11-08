<?php 

namespace App\Services;

use App\Repositories\ClassRepository;
use App\Repositories\TitleRepository;

class ClassService
{
    protected $class;
    protected $title;

    /**
     * 注入repository
     */    
    public function __construct(ClassRepository $classRepository, TitleRepository $titleRepository)
    {
        $this->class = $classRepository;
        $this->title = $titleRepository;
    }

    /**
     * 查詢所有課程
     * 
     * @return array
     */
    public function showClass()
    {        
        return $this->class->show();
    }

    /**
     * 依條件查詢課程資料
     * 
     * @param array $conditions
     * @return array
     */
    public function showClassBy($conditions)
    {        
        return $this->class->showBy($conditions);
    }

    /**
     * 依條件查詢章節資料
     * 
     * @param array $conditions
     * @return array
     */
    public function showTitleBy($conditions)
    {        
        return $this->title->showBy($conditions);
    }

    /**
     * 依條件查詢留言板資料
     * 
     * @param array $conditions
     * @return array
     */
    public function showMessageBy($conditions)
    {        
        return $this->class->showMessageBy($conditions);
    }
}