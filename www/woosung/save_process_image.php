<?php

$imagedata = base64_decode($_REQUEST['imgSrc']);
$filename = "capture_".date("YmdHis").".jpg";

$file = $_SERVER['DOCUMENT_ROOT'] . "/woosung/uploads/" . $filename;
file_put_contents($file, $imagedata);

echo $filename;

?>