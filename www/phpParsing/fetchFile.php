<?php
function get_saveFile($url)
{
    $imgName_arry = explode("/", $url);
    $imgName = $imgName_arry[sizeof($imgName_arry) - 1]; 
    
    //파일명 가져옴 
    
    $cachePath = "../cache/upload/"; 
    //업로드 위치 
    
    $Path = $cachePath.$imgName; 
    //파일이 있는지 확인 
    if(file_exists($Path)) {         //cache에 파일이 있음 
        $imgSaveTime = filemtime($Path);         //파일 저장된 날짜 
        $imgCheckTime = $imgSaveTime + ( 60 * 60 * 24 * 7 );         //파일이 저장된 날짜의 일주일 날짜 계산 
        $now = time(); 
        //현재 시간 
        if($imgCheckTime <= $now) { 
        //파일 저장된 날짜보다 현재날짜가 더 최근일 경우 
        $img=file_get_contents($url);         //파일을 가져옴 
        file_put_contents($Path, $img);       //덮어쓰기(저장) 
                } } else {        //cache에 파일이 없음 
            $img=file_get_contents($url); //파일을 가져옴 
            file_put_contents($Path, $img); //저장 
        } return $Path; //파일이 cache에 있으면 불러옴 
} 
    
$imgUrl=get_saveFile('http://testpage.com/img/imgfile.png');

?>