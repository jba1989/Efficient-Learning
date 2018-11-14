<?php 

namespace App\Services;

use App\Repositories\MessageRepository;
use Auth;

class MessageService
{
    protected $messageRepository;

    /**
     * 注入repository
     */    
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * 插入一筆資料
     * 
     * @param array $contents
     */
    public function create($contents)
    {
        $this->messageRepository->create($contents);
    }

    /**
     * 更新一筆資料
     * 
     * @param array $conditions
     * @param array $contents
     */
    public function update($user, $input)
    {
        $conditions = array(
            'id' => $input['id'],
            'userName' => $user->name,
        );
        $contents = array(
            'message' => htmlspecialchars($input['message'])
        );

        return $this->messageRepository->update($conditions, $contents);
    }

    /**
     * 依條件查詢留言板資料
     * 
     * @return array $conditions
     */
    public function show($conditions)
    {        
        return $this->messageRepository->show($conditions);
    }

    /**
     * 依條件刪除留言
     * 
     * @return array $conditions
     */
    public function delete($user, $input)
    {        
        $conditions = array(
            'id'=> $input['id'],
            'userName'=> $user->name,
        );

        return $this->messageRepository->delete($conditions);
    }
}