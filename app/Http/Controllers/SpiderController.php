<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList as ClassList;
use App\Models\TotalClass as TotalClass;

class SpiderController extends Controller
{
    /**
     * 自動更新課程
     */
    public function update()
    {
        $classIdArr = $this->parseClassList();

        foreach ($classIdArr as $classId) {
            $this->parseClassDescription($classId);
            $count = $this->countClass($classId);
            $this->parseClassTitle($classId, $count);
            //$this->videoSpider($classId, $count);
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

    /**
     * 更新台大課程清單
     *
     * @return array
     */
    public function parseClassList()
    {
        $url = 'http://ocw.aca.ntu.edu.tw/ntu-ocw/'; // 台大開放式課程網址
        $responce = $this->myCurl($url);
        $pattern = '/<option value="\/ntu-ocw\/ocw\/cou\/([^"]+?)">(.*?)\((.*?)\)<\/option>/';
        preg_match_all($pattern, $responce, $matches);
        for ($i = 0; $i < count($matches[1]); $i++) {
            ClassList::firstOrCreate(['classId' => $matches[1][$i]], [
                'classId' => $matches[1][$i],
                'className' => $matches[2][$i],
                'teacher' => $matches[3][$i],
                'school' => '台大',
                ]
            );
        }

        return $matches[1];
    }

    /**
     * 抓取單一課程描述
     *
     * @param string
     * @param integer
     */
    public function parseClassDescription($classId)
    {
        $url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/ocw/cou_intro/$classId";
        $response = $this->myCurl($url);
        $sBeginWith = 'og:description" content="';
        $sEndWith = '" />';
        $result = trim($this->strFind($response, $sBeginWith, $sEndWith, 1, FALSE));
        ClassList::updateOrCreate(['classId' => $classId], ['description' => $result]);
    }

    /**
     * 抓取單一課程上課次數
     *
     * @param string
     * @return integer
     */
    public function countClass($classId)
    {
        $url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/$classId";
        $response = $this->myCurl($url);
        $sBeginWith = 'align="texttop" />';
        $count = substr_count($response, $sBeginWith);
        ClassList::where('classId', $classId)->update(['countTitle' => $count]);

        return $count;
    }

    /**
     * 抓取課程章節
     *
     * @param string
     * @param integer
     * @return array
     */
    public function parseClassTitle($classId, $count)
    {
        $url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/$classId/1";
        $response = $this->myCurl($url);
        $sBeginWith = 'align="texttop" />';
        $sEndWith = '</div>';
        $result = array();
        for ($i = 0; $i < $count; $i++)
        {
            $result = trim($this->strFind($response, $sBeginWith, $sEndWith, ($i+1) , FALSE));
//            if ($result == '') {
//                throw new \Exception('$classId' . "-titleId:$i-null");
//            }
            TotalClass::updateOrCreate(['classId' => $classId, 'titleId' => $i + 1], ['classId' => $classId, 'titleId' => $i + 1, 'title' => $result]);

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
     * 解析網頁內容
     *
     * @param string
     * @param string
     * @param string
     * @param integer
     * @param boolean
     * @return string
     */
    public function strFind($response, $sBeginWith, $sEndWith, $iTh = 1 , $bIncludeBeginEnd = TRUE) 
    { 
        //(資料來源,開始字串,結束字串,第X次出現的字串,是否包含開始結數字元)
        $result = "";   //先將結果設為空字串
        $iStartPosition = - 1;   //配合後續$iStartPosition+1可往下查下一次出現的位置
        
        // <---搜尋指定之$sBeginWith位置--->
        for($i = 1; $i <= $iTh; $i ++) {
            $iStartPosition = strpos ( $response, $sBeginWith, $iStartPosition + 1 );
        }
        $istartPoint = $iStartPosition;  //做開始位置的備存
        
        
        // <---搜尋指定之$sBeginWith位置--->
        if ($iStartPosition < 0) {
            return $result;    //若無找到$response中有$sBeginWith回傳空字串
        }

        
        // <---搜尋指定之$iEndPosition位置--->
        $iEndPosition = strpos ( $response, $sEndWith, $iStartPosition + strlen($sBeginWith) );
        if ($iEndPosition < 0){
            return $result;
        }

            
        // <---判斷是否有多重table--->
        $icount = -1;    //計算幾次的計數器,因會先計算一次故從-1開始
        do {
            $istartPoint = strpos ( $response, $sBeginWith, $istartPoint + 1 );
            $icount ++;
        } while ( ($istartPoint < $iEndPosition) && ($istartPoint > 0) );
            
        // <---重新尋找正確的$iEndPosition--->
        for ($j = 0 ; $j < $icount ; $j++) {
            $iEndPosition =  strpos ( $response, $sEndWith, $iEndPosition + 1 );
        }
        
        // <---重組字串並判斷是否連接"$sBeginWith"/"$sEndWith"字串--->
        if ($bIncludeBeginEnd) {
            $result = $sBeginWith . substr ($response, $iStartPosition + strlen ( $sBeginWith ), $iEndPosition - $iStartPosition - strlen ( $sBeginWith ) ) . $sEndWith;
        } else {
            $result = substr ( $response, $iStartPosition + strlen ( $sBeginWith ), $iEndPosition - $iStartPosition - strlen ( $sBeginWith ) );
        }

        return $result;
    }
}
