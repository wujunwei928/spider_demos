<?php
//php爬取花瓣网美女图片

function curl_request($fetch_url)
{
     //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $fetch_url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);

    //
    preg_match("/pins(.*);/i", $data, $match);

    $json = str_replace('"] = ', '', $match[1]);
    $json_arr = json_decode($json, true);

    return $json_arr;
}

//文件保存路径, 如果不存在, 创建
$save_dir = 'huaban_images/';
if(!file_exists($save_dir)){
    mkdir($save_dir);
}

for ($i=648889720; $i < 998889720; $i+=1000000) { 
    $fetch_url = 'http://huaban.com/favorite/beauty/?ip7yqiov&max='.$i.'&limit=20&wfl=1';
    $json_arr = curl_request($fetch_url);
    foreach ($json_arr as $value) {
        $img_url = 'http://hbimg.b0.upaiyun.com/' . $value['file']['key'];
        echo $img_url."\n";
        $img_file = $save_dir.'/'.$value['file']['key'].'.jpg';
        if (!file_exists($img_file)) {
            $img_data = file_get_contents($img_url);
            file_put_contents($img_file, $img_data);
        }
    }
}


