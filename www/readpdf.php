<?php
$Readpdf='sample.pdf';
header('Content-type:application/pdf');
header('Content-Disposition:inline; filename="' . $Readpdf . '"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
@readfile($Readpdf);
?>
