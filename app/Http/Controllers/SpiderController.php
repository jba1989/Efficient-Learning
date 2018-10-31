<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NTUClassService;
use App\Services\NCTUClassService;

class SpiderController extends Controller
{
    protected $ntu;
    protected $nctu;

    /**
     * 注入service
     */    
    public function __construct(NCTUClassService $nctuClassService, NTUClassService $ntuClassService)
    {
        $this->ntu = $ntuClassService;
        $this->nctu = $nctuClassService;
    }

    /**
     * 自動更新課程
     */
    public function update($school)
    {        
        switch ($school) {
            case 'NTU':
            case 'ntu':
                $this->ntu->update();
                break;
            case 'NCTU':
            case 'nctu':
                $this->nctu->update();
                break;            
        }
        echo 'finish';
    }

    /**
     * 自動更新課程
     */
    public function title($classId)
    {        
        echo $this->nctu->parseClassDescription($classId);
    }
}
