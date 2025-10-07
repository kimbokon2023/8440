<?php

$fileName = "gridData.json";

// 파일의 내용을 불러오기
if (file_exists($fileName)) {
    $data = file_get_contents($fileName);
    echo $data; // JSON 데이터 반환
} else {
    echo json_encode(array("error" => "File not found")); 
}

?>
