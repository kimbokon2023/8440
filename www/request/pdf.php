<?php

// 파일을 읽어와서 서버에 저장함.
isset($_REQUEST["imageURL"])  ? $imageURL=$_REQUEST["imageURL"] :   $imageURL=''; 

$imageURL = 'http://8440.co.kr/request/' . $imageURL ;

$mpdf = new mPDF();
$file = $imageURL;
$size =  getimagesize( $file );
$width = $size[0];
$height = $size[1];
$mpdf->WriteHTML('');
$mpdf->Image($file,60,50,$width,$height,'jpg','',true, true);
$mpdf->Output($filename);




print $imageURL;

// 이미지 화일 jpg 삭제
// unlink($imageURL);


?>