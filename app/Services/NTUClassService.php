<?php    
    
namespace App\Services;

use App\Repositories\ClassRepository;
use App\Repositories\TitleRepository;
    
class NTUClassService
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
     * 自動更新課程
     */
    public function update()
    {
        $classIdArr = $this->parseClassList();

        foreach ($classIdArr as $classId) {
            $this->parseClassDescription($classId);
            $this->parseClassTitle($classId);
            //$this->videoSpider($classId, $count);
        }
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
            $this->class->firstOrCreate($conditions, $contents);
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
            $description = $matches[1][0];
        } else {
            $description = null;
        }        

        $conditions = array('classId' => $classId);
        $contents = array('description' => $description);
        $this->class->update($conditions, $contents);
    }

    /**
     * 抓取上課次數,課程章節
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

        $conditions = array('classId' => $classId);
        $contents = array('countTitle' => $count);
        $this->class->update($conditions, $contents);

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
            );
            $this->title->updateOrCreate($conditions, $contents);
        };
    }

    /**
     * 抓取課程章節影片
     *
     * @param string
     * @param integer
     * @return array
     */
    public function videoSpider($classId, $count)
    {
//        for ($i = 0; $i < $count; $i++) {
//            $url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/$classId/$i";
//            $response = $this->myCurl($url);
//            $parseData = $this->strFind($response, '<div class="video">', '</iframe>', 1, FALSE);
//            $result = $this->strFind($parseData, "src='", "'>", 1, FALSE);
//            TotalClass::where('classId', $classId)->where('titleId', $i + 1)->update(['videoLink' => $result]);
//        }
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