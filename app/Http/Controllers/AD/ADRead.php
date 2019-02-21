<?php

namespace App\Http\Controllers\AD;

use Illuminate\Support\Facades\DB;

class ADRead extends ADBase
{
    public function pattern_one($artcleid)
    {
        $artcleid=substr($artcleid,0,-1);

        $starttime=strtotime('2017-11-22');

        $endtime=time();

        $sql="select artid,modelid from iiss_wap_article where artid={$artcleid} and pubdate>{$starttime} and pubdate<{$endtime}";

        //所有文章1
        $tmp=DB::connection('mobile')->select($sql);

        return $tmp;
    }

    public function pattern_two($artcleid)
    {
        $artcleid=substr($artcleid,0,-1);

        $now=time();

        //所有文章2
        $sql="select * from ( 
select artid,sortnum from iiss_wap_article where pubdate BETWEEN '1525190400' AND '{$now}' and artid={$artcleid} and find_in_set('c',flag)>0 
) as tb order by sortnum desc limit 14";

        $tmp=DB::connection('mobile')->select($sql);

        return $tmp;
    }

    public function pattern_three($artcleid)
    {
        $artcleid=substr($artcleid,0,-1);

        //所有文章3
        $sql="select artid,modelid from iiss_wap_article where artid in(
587317,587313,587312,587319,587493,2000004619,587316,587314,2000004653,2000004654,2000004655,2000004651,2000004730,2000004726,2000004728,2000004730,2000004729,2000004731,596780,597051,596666, 597493, 595226, 595226, 596615, 597493, 596993, 597494)";

        $tmp=DB::connection('mobile')->select($sql);

        return $tmp;
    }

    public function pattern_four()
    {
        //取所有文章4
        $sql="select artid,modelid,subject,pic,url,pubdate,flag from iiss_wap_article 
where artid in(5966641,587317,587313,587312,587319,587493,2000004619,587316,587314,2000004653,2000004654,2000004655,2000004651,597493,595226,595226,596615,597493,596993,597494,5966151) 
and pic != '' order by sortnum desc limit 12";

        $query=DB::connection('mobile')->select($sql);

        $h = 1;

        foreach ($query as $value)
        {
            $value->pubdate=time()-$h*150;
            $value->artid  =$value->artid.$value->modelid;
            $value->gmdate =date('Y-m-d',$value->pubdate);
            $value->url    ='http://n2.iissbbs.com'.'/'.$value->url;
            $artlist[]     =$value;

            $h++;
        }

        return $artlist;
    }


}
