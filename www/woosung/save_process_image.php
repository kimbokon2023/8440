<?php

$imagedata = base64_decode($_REQUEST['imgSrc']);
$filename = "capture_".date("YmdHis").".jpg";

$file = getDocumentRoot() . "/woosung/uploads/" . $filename;
file_put_contents($file, $imagedata);

echo $filename;

?>