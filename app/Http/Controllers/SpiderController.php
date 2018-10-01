<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassList as ClassList;

class SpiderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
     */
    public function parseClassList()
    {
        $url = 'http://ocw.aca.ntu.edu.tw/ntu-ocw/'; // 台大開放式課程網址
        $responce = $this->myCurl($url);
        $pattern = '/<option value="\/ntu-ocw\/ocw\/cou\/([^"]+?)">(.*?)\((.*?)\)<\/option>/';
        preg_match_all($pattern, $responce, $matches);
        $data = array_combine($matches[1], $matches[2]);
        for ($i = 0; $i < count($matches[1]); $i++) {
            $result = ClassList::firstOrCreate(['classId' => $matches[1][$i]], [
                'classId' => $matches[1][$i], 
                'className' => $matches[2][$i], 
                'teacher' => $matches[3][$i],
                'school' => '台大',
                ]
            );            
        }
    }

    public function videoSpider()
    {
        $classIdArr = ClassList::all('classId');
        foreach ($classIdArr as $classId) {            
            $url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/ocw/cou/$classId/1";
            $response = $this->myCurl($url);            
            $pattern = '/<div class="video">(.*)<\/iframe>/';
            preg_match_all($pattern, $response, $matches);            
        }
    }

    public function classDiscriptionSpider()
    {
        $classIdArr = ClassList::all('classId');
        
    }

    // ---- 抓取單一課程上課次數 ----
    public function countClass ($classId)
    {
        $classIdArr = ClassList::all('classId');
        $saveToDB = array();
        foreach ($classIdArr as $classId) {
            $url = "http://ocw.aca.ntu.edu.tw/ntu-ocw/index.php/ocw/cou/$classId";
            $response = $this->myCurl($url); 
            $sBeginWith = 'align="texttop" />';
            $count = substr_count($response, $sBeginWith);
            ClassList::where('classId', $classId)->where('countTitle', '><', $count)->update('countTitle', $count);
        }   
    }

    // ---- 頗析字串 ----
    public function StrFind($sSource, $sBeginWith, $sEndWith, $iTh = 1 , $bIncludeBeginEnd = TRUE) 
    { 
        //(資料來源,開始字串,結束字串,第X次出現的字串,是否包含開始結數字元)
        $result = "";   //先將結果設為空字串
        $iStartPosition = - 1;   //配合後續$iStartPosition+1可往下查下一次出現的位置
        
        // <---搜尋指定之$sBeginWith位置--->
        for($i = 1; $i <= $iTh; $i ++) {
            $iStartPosition = strpos ( $sSource, $sBeginWith, $iStartPosition + 1 );
        }
        $istartPoint = $iStartPosition;  //做開始位置的備存
        
        
        // <---搜尋指定之$sBeginWith位置--->
        if ($iStartPosition < 0)
            return $result;    //若無找到$sSource中有$sBeginWith回傳空字串
        
        // <---搜尋指定之$iEndPosition位置--->
        $iEndPosition = strpos ( $sSource, $sEndWith, $iStartPosition + strlen($sBeginWith) );
        if ($iEndPosition < 0)
            return $result;
            
        // <---判斷是否有多重table--->
            $icount = -1;    //計算幾次的計數器,因會先計算一次故從-1開始
            do	
            {
                $istartPoint = strpos ( $sSource, $sBeginWith, $istartPoint + 1 );
                $icount ++;
            } while ( ($istartPoint < $iEndPosition) && ($istartPoint > 0) );
            //echo "icount=".$icount."<br />" ;    除錯用
            
        // <---重新尋找正確的$iEndPosition--->
            for ($j = 0 ; $j < $icount ; $j++)
            {
                $iEndPosition =  strpos ( $sSource, $sEndWith, $iEndPosition + 1 );
            }
        
        // <---重組字串並判斷是否連接"$sBeginWith"/"$sEndWith"字串--->
        if ($bIncludeBeginEnd) {
            $result = $sBeginWith . substr ($sSource, $iStartPosition + strlen ( $sBeginWith ), $iEndPosition - $iStartPosition - strlen ( $sBeginWith ) ) . $sEndWith;
        } 
        else
            $result = substr ( $sSource, $iStartPosition + strlen ( $sBeginWith ), $iEndPosition - $iStartPosition - strlen ( $sBeginWith ) );
        return $result;
    }
}
