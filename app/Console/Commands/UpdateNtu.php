<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassList;
use App\Models\TitleList;
use Exception;
use DB;

class UpdateNtu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ntu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update NTU classes information';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->update();
        } catch (Exception $err) {
            DB::rollBack();
            Log::critical($err);
        }

        $this->info('Update NTU classes finished');
    }

    /**
     * 自動更新課程
     */
    public function update()
    {
        $classIdArr = $this->parseClassList();

        foreach ($classIdArr as $classId) {
            $this->parseClassDescription($classId);
            $this->parseClassTitle($classId);                    
        }

        $this->parseClassType();
    }
  
    /**
     * 更新台大課程清單
     *
     * @return array
     */
    public function parseClassList()
    {
        $url = 'http://ocw.aca.ntu.edu.tw/ntu-ocw/'; // 台大開放式課程網址
        $response = $this->myCurl($url);
        $pattern = '/<option value="\/ntu-ocw\/ocw\/cou\/([^"]+?)">(.*?)\((.*?)\)<\/option>/';
        preg_match_all($pattern, $response, $classContents);
        for ($i = 0; $i < count($classContents[1]); $i++) {            
            $conditions = array(
                'classId' => $classContents[1][$i],
            );

            $contents = array(
                'classId' => $classContents[1][$i],
                'className' => $classContents[2][$i],
                'teacher' => $classContents[3][$i],
                'school' => 'NTU',
            );

            DB::beginTransaction();
            ClassList::updateOrCreate($conditions, $contents);
            DB::commit();
        }
        return $classContents[1];
    }

    /**
     * 抓取單一課程描述
     *
     * @param string
     * @param integer
     */
    public function parseClassDescription($classId)
    {
        $url = 'http://ocw.aca.ntu.edu.tw/ntu-ocw/ocw/cou_intro/' . $classId;
        $response = $this->myCurl($url);

        $pattern = '/og:description" content="(.*)"\s?\/>/';
        preg_match_all($pattern, $response, $matches);
        if (count($matches[1]) > 0) {
            $description = $matches[1];
        } else {
            $description = array();
        }        

        DB::beginTransaction();
        ClassList::where('classId', $classId)->update(['description' => json_encode($description)]);
        DB::commit();
    }

    /**
     * 抓取上課次數,課程章節,影片連結
     *
     * @param string
     * @param integer
     */
    public function parseClassTitle($classId)
    {
        $url = 'http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/' . $classId . '/1';
        $response = $this->myCurl($url);
        
        $pattern = '/align="texttop" \/>\s+(.*)\s+<\/div>/';
        preg_match_all($pattern, $response, $matches);
        $titles = $matches[1];
        $count = count($titles);

        DB::beginTransaction();
        ClassList::where('classId', $classId)->update(['countTitle' => $count]);
        DB::commit();
 
        $result = array();
        for ($i = 0; $i < $count; $i++)
        {   
            $conditions = array(
                'classId' => $classId,
                'titleId' => $i + 1,
            );
            $contents = array(
                'classId' => $classId,
                'titleId' => $i + 1,
                'title' => $titles[$i],
                'videoLink' => "http://ocw.aca.ntu.edu.tw/ntu-ocw/ocw/cou/$classId/$i",
            );

            DB::beginTransaction();
            TitleList::updateOrCreate($conditions, $contents);
            DB::commit();
        };
    }

    /**
     * 抓取課程類型
     */
    public function parseClassType()
    {        
        $typeArr = array(
            '1' => '文史哲藝',
            '2' => '法社管理',
            '3' => '理工電資',
            '4' => '生農醫衛',
        );

        foreach ($typeArr as $key => $val) {       
            $url = 'http://ocw.aca.ntu.edu.tw/ntu-ocw/home/show-category/' . $key;
            $response = $this->myCurl($url);
            $pattern = "/<a href='\/ntu-ocw\/ocw\/cou\/(\S+)'>/";
            preg_match_all($pattern, $response, $matches);
            
            if (count($matches[1]) > 0) {
                foreach ($matches[1] as $classId) {
                    DB::beginTransaction();
                    ClassList::where('classId', $classId)->update(['classType' => $val]);
                    DB::commit();
                }
            }
        }
    }

    /**
     * curl請求, 並返回網站內容
     *
     * @param $url
     * @return string
     */
    public function myCurl($url)
    {
        // 1. 初始設定
        $ch = curl_init();

        // 2. 設定 / 調整參數
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // 3. 執行，取回 response 結果
        $response = curl_exec($ch);

        // 4. 關閉與釋放資源
        curl_close($ch);

        return $response;
    }
}