<?php

function sstripslashes($string)
{
    if(is_array($string))
    {
        foreach($string as $key=>$val)
        {
            $string[$key]=sstripslashes($val);
        }
    }else
    {
        $string=stripslashes($string);
    }

    return $string;
}

function make_path($path)
{
    if(!is_dir($path))
    {
        mkdir($path,0777,1);
    }

    return true;
}

function dgmdate($timestamp)
{
    $todaytimestamp = time();

    if(intval($todaytimestamp-$timestamp) < 3600)
    {
        return intval(($todaytimestamp-$timestamp)/60) .'分钟前';

    }elseif(intval($todaytimestamp-$timestamp) < 86400)
    {
        return intval(($todaytimestamp-$timestamp)/3600) .'小时前';

    }else
    {
        return date('n月j日',$timestamp);
    }
}




