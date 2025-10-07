<?php

// in save_process.php

$imagedata = base64_decode($_REQUEST['imgSrc']);
$file_name = "capture_".date("YmdHis").".jpg";

$file = getDocumentRoot() . "/ajax/" . $file_name;

// echo $file;

file_put_contents($file, $imagedata);

echo "SUCCESS";

?>