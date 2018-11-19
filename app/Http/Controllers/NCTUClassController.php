<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList;
use App\Models\TotalClass;

class NCTUClassController extends Controller
{
    /**
     * 更新交大課程清單
     */
    public function update()
    {
        $url = 'http://ocw.nctu.edu.tw/course.php'; // 交大開放式課程網址
        $response = $this->myCurl($url);

        // 依課程分類抓取所有課程清單
        $pattern = '/<option value="([0-9]+)">\s+(.*)\s+<\/option>/';
        preg_match_all($pattern, $response, $matches);
        $classTypeIdArr = $matches[1];
        $classTypeNameArr = $matches[2];

        // 依課程分類讀取頁數
        for ($i = 0; $i < count($classTypeIdArr); $i++) {

            $url = 'http://ocw.nctu.edu.tw/course_list_search.php?&s1=' . $classTypeIdArr[$i];
            $response = $this->myCurl($url);

            $pattern = '/<li><a href="#">([0-9]?)<\/a><\/li>/';
            preg_match_all($pattern, $response, $pages);

            // 從每一頁爬取開課清單
            foreach ($pages[1] as $page) {
                $url = 'http://ocw.nctu.edu.tw/course_list_search.php?page=' . $page . '&s1=' . $classTypeIdArr[$i];
                $response = $this->myCurl($url);

                $pattern = '/<h3><a href=.*bgid.*nid=([0-9]+)">\s+(.*)?<\/h3>\s+<\/div>\s+<.*>\s+<span class="pull-right">(.*)?<\/span>/';
                preg_match_all($pattern, $response, $classContents);

                for ($j = 0; $j < count($classContents[1]); $j++) {
                    $conditions = array(
                        'classId' => $classContents[1][$j],
                    );

                    $contents = array(
                        'classId' => $classContents[1][$j],
                        'className' => $classContents[2][$j],
                        'teacher' => $classContents[3][$j],
                        'classType' => $classTypeNameArr[$i],
                        'school' => 'NCTU',
                        'description' => $this->parseClassDescription($page, $classTypeIdArr[$i]),
                    );

                    // 寫入資料庫
                    ClassList::firstOrCreate($conditions, $contents);

                    // 依照每個課程id抓取單一課程上課次數,課程章節,寫入資料庫
                    $this->parseClassTitle($classContents[1][$j]);
                }
            }
        }

        echo 'finished';
    }

    /**
     * 抓取單一課程描述
     *
     * @param string $classId
     * @return string
     */
    protected function parseClassDescription($classId)
    {
        $url = 'http://ocw.nctu.edu.tw/course_detail.php?nid=' . $classId;        
        $response = $this->myCurl($url);

        $pos = strpos($response, '&nbsp;</p>');
        if ($pos != FALSE) {
            $begin = strpos($response, '&nbsp;</p>', $pos + strlen('&nbsp;</p>'));
            $end = strpos($response, '</p>', $begin + strlen('&nbsp;</p>'));
            $description = trim(strip_tags(substr($response, $begin + strlen('&nbsp;</p>'), $end - $begin)));
            return $description;
        } else {
            return null;
        }
    }

    /**
     * 依課程id抓取上課次數,課程章節,寫入資料庫
     *
     * @param string $classId
     */
    protected function parseClassTitle($classId)
    {
        $url = 'http://ocw.nctu.edu.tw/course_detail-v.php?nid=' . $classId;        
        $response = $this->myCurl($url);

        $titles = array();
        for ($i = 1; $i < substr_count($response, '<tr>'); $i++) {
            $rawTitle = trim(strip_tags($this->strFind($response, '<tr>', '</tr>', $i + 1, FALSE)));
            $filterArr = ['WMV', 'MP4', '下載', '線上觀看'];
            if ($rawTitle != '') {
                $pattern = '/([\S]+)/';
                $title = preg_match_all($pattern, $rawTitle, $matches);
                $titles[] = implode(' ', array_diff($matches[1], $filterArr));
            }
        }

        $count = count($titles);

        ClassList::where('classId', $classId)->update(['countTitle' => $count]);

        for ($i = 0; $i < $count; $i++) {
            $conditions = array(
                'classId' => $classId,
                'titleId' => $i + 1,
            );

            $contents = array(
                'classId' => $classId,
                'titleId' => $i + 1,
                'title' => $titles[$i],
                'videoLink' => $url,
            );

            // 寫入資料庫
            TotalClass::updateOrCreate($conditions, $contents);
        }        
    }
    
    /**
     * 解析網頁內容
     *
     * @param string $response
     * @param string $sBeginWith
     * @param string $sEndWith
     * @param integer $iTh
     * @param boolean $bIncludeBeginEnd
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

    /**
     * curl請求, 並返回網站內容
     *
     * @param $url
     * @return string
     */
    public function myCurl($url)
    {        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
       
        $response = curl_exec($ch);
        
        curl_close($ch);

        return $response;
    }    
}