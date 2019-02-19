<?php
Route::fallback(function (){return 'no page';});

//Route::middleware('page-cache')->get('/', function () {return view('ad_index');});


Route::get('/', function () {return view('ad_index');});
















//广告路由
Route::group(['namespace'=>'AD'],function (){

    //zhongzhuan
    Route::get('/zhongzhuan','ADController@zhongzhuan');

    //取得广告页面，并且缓存
    Route::middleware('page-cache')->get('/{root}/{day}/{page}','ADController@cachePage');



});
























