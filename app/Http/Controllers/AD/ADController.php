<?php

namespace App\Http\Controllers\AD;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ADController extends ADBase
{
    public function zhongzhuan(Request $request)
    {
        $typeid=628;
        $sql="SELECT id,adname,filetype,htmlbody,parambody,attribute,cityid FROM iiss_ad WHERE flag>1 AND isfinish=0 AND typeid={$typeid} AND (timeset=0 or (starttime<".time()." and endtime>".time().")) ORDER BY level,orderid DESC";
        $res=DB::connection('cis_ad')->select($sql);

        $adarr = [];

        foreach ($res as $value)
        {
            $value=(array)$value;

            $adarr[$value['id']] = $value;
            $parambody = unserialize($value['parambody']);
            $adarr[$value['id']]['parambody'] = $parambody;
        }

        foreach($adarr as $item)
        {
            //chinaiiss.com  替换为 iissbbs.com
            $url = str_replace('n9.chinaiiss.com', 'n2.iissbbs.com', $item['parambody']['url']);

            $adList[] = array(
                'id'=>$item['id'],
                'subject'=>$item['adname'],
//				'url'=>$item['parambody']['url'],
                'url'=>$url,
                'pic'=>!empty($item['parambody']['pic']) ? 'http://image1.iissbbs.com'.'/'.$item['parambody']['pic'] : '',
                'flag'=>$item['filetype'],
                'pics'=>isset($item['parambody']['pics']) ? $item['parambody']['pics'] : '',
                'gmdate'=>date('Y-n-j')
            );
        }

        return response()->json(['data'=>$adList]);
    }

    public function cachePage(Request $request,$root,$day,$page)
    {
        //从n1拿静态页
        $cache=file_get_contents("http://n1.iissbbs.com/{$root}/{$day}/{$page}");

        $cache=str_replace('n1.iissbbs.com','n2.iissbbs.com',$cache);

        return $cache;
    }













}
