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

    public function jingcai(Request $request)
    {
        $typeid=627;

        $sql="SELECT id,adname,filetype,htmlbody,parambody,attribute,cityid FROM iiss_ad WHERE flag>1 AND isfinish=0 AND typeid={$typeid} AND (timeset=0 or (starttime<".time()." and endtime>".time().")) ORDER BY level,orderid DESC";

        $res=DB::connection('cis_ad')->select($sql);

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

            $adList[] = [
                'id'=>$item['id'],
                'subject'=>$item['adname'],
                'url'=>$url,
                'pic'=>'http://image1.iissbbs.com'.'/'.$item['parambody']['pic'],
                'gmdate'=>date('Y-n-j')
            ];
        }

        return response()->json(['data'=>$adList]);
    }

    public function n5more(Request $request)
    {
        global $db,$param;
        $callback  = isset($_GET['callback']) ? $_GET['callback'] : '';
        $lasttime  = isset($_GET['lasttime']) ? intval($_GET['lasttime']) : time();
        $lasttime  = !empty($lasttime) ? $lasttime : time();
        $categoryid= isset($_GET['categoryid']) ? intval($_GET['categoryid']) : 0;

        $fields = 'id,artid,subject,pic,pubdate,flag,url';
        $jump_table = 'iiss_mobile_jump';
        $table = 'iiss_mobile_article';

        switch($categoryid)
        {
            case 763:
                $table = 'iiss_wap_article';
                $where = "topid=19";
                break;
            case 33:
                $where = "categoryid=764";
                break;
            case 4:
                $table = 'iiss_wap_article';
                $where = "topid=4";
                break;
            case 52:
                $table = 'iiss_wap_article';
                $where = "topid=52";
                break;
            default:
                $table = 'iiss_wap_article';
                $where = "categoryid={$categoryid}";
                break;
        }

        if($table == 'iiss_mobile_article')
        {
//            $sql = "select jumpid,oldinfoid from $jump_table where ischeck=1 and $where and pubdate<{$lasttime} order by pubdate desc limit 0,10";
//
//            $query = $db->query($sql);
//            $artids = [];
//            while($value = $db->fetch_array($query))
//            {
//                $artids[] = $value['oldinfoid'];
//            }
//            $artlist = array();
//            $todaytimestamp = time();
//            if($artids){
//                $sid = implode(",", $artids);
//                $where = "id in ($sid)";
//                $sql = "select $fields from $table where $where and pic != '' order by pubdate desc";
//
//                $query = $db->query($sql);
//                while($value = $db->fetch_array($query))
//                {
//                    if($_GET['test']){
//                        var_dump($value);die;
//                    }
//                    $value['artid'] = $value['id'].'1';
//                    $value['gmdate'] = self::dgmdate($value['pubdate']);
//                    $value['flag'] = '';
//
//                    if($value['url']){
//                        $value['url'] = $value['url'];
//                    }else{
//                        $value['url'] = 'http://n1.iissbbs.com/touch/uc/'.$value['artid'];
//                    }
//                    $value['pic'] = IConfig::IMAGE1URL.'/'.$value['pic'];
//                    $artlist[]  = $value;
//                }
//            }
        }

        if($table == 'iiss_wap_article')
        {
            $artlist = [];

            $fields = 'artid,subject,pic,pubdate,flag,url';

            $sql="select $fields from $table where $where and pubdate<{$lasttime} and pic != '' order by sortnum desc,pubdate desc limit 10";

            $query=DB::connection('mobile')->select($sql);

            foreach ($query as $value)
            {
                $value=(array)$value;

                $value['artid'] = $value['artid'].'1';
                $value['gmdate'] = dgmdate($value['pubdate']);
                $value['flag'] = '';

                if($value['url'])
                {
                    $value['url']=str_replace('http://n1','http://n2',$value['url']);

                }else
                {
                    $value['url']='http://n2.iissbbs.com/touch/uc/'.$value['artid'];
                }

                $value['pic'] = 'http://image1.iissbbs.com'.'/'.$value['pic'];

                $artlist[] = $value;
            }
        }

        return response()->json(['artlist'=>$artlist]);
    }

    //创建广告页面
    public function cachePage(Request $request,$root,$day,$page)
    {
        //======================================
        //$page组成是artcle.modelid_分页.html
        //所以artcleid需要去掉最后一位数字
        //======================================

        preg_match_all('/\d+/',$page,$artcleid);

        $artcleid=current($artcleid);

        if (count($artcleid)===2)
        {
            self::$is_main_page=$artcleid[1];

        }elseif (count($artcleid)>2)
        {
            //瞎机霸输入就返回404
            return abort(404);
        }

        //看看在哪里
        $res=(new ADRead())->pattern_one($artcleid[0]);

        if (!empty($res))
        {
            return (new ADSave())->save_html($res);
        }

        //======================================
        $res=(new ADRead())->pattern_two($artcleid[0]);

        if (!empty($res))
        {
            return (new ADSave())->save_html($res);
        }

        //======================================
        $res=(new ADRead())->pattern_three($artcleid[0]);

        if (!empty($res))
        {
            return (new ADSave())->save_html($res);
        }

        return abort(404);
    }










}
