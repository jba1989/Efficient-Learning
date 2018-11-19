<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository
{
    /**
     * 插入一筆資料
     * 
     * @param array $contents
     */
    public function create($contents)
    {
        Message::insert($contents);
    }

    /**
     * 更新一筆資料
     * 
     * @param array $conditions
     * @param array $contents
     */
    public function update($conditions, $contents)
    {
        return Message::where($conditions)->update($contents);
    }

    /**
     * 依條件查詢留言板資料
     * 
     * @return array $conditions
     */
    public function show($conditions)
    {        
        return Message::where($conditions)->orderBy('created_at', 'desc')->paginate(30, ['*'], 'msg_page');
    }

    /**
     * 依條件刪除留言
     * 
     * @return array $conditions
     */
    public function delete($conditions)
    {        
        return Message::where($conditions)->delete();
    }
}