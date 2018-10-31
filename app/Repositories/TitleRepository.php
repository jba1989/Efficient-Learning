<?php

namespace App\Repositories;

use App\Models\TotalClass;

class TitleRepository
{   
    /**
    * 插入一筆資料
    * 
    * @param array $conditions
    * @param array $contents 
    */
   public function firstOrCreate($conditions, $contents)
   {
        TotalClass::firstOrCreate($conditions, $contents);
   }

   /**
    * 更新或新增一筆資料
    * 
    * @param array $conditions
    * @param array $contents 
    */
   public function updateOrCreate($conditions, $contents)
   {
        TotalClass::updateOrCreate($conditions, $contents);
   }

   /**
    * 更新一筆資料
    * 
    * @param array $conditions
    * @param array $contents
    */
   public function update($conditions, $contents)
   {
        TotalClass::where($conditions)->update($contents);
   }
}
