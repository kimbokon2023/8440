<?php

$filename=$_FILES['myfiles']['name'];

$xml = file_get_contents($filename);
$result_xml = simplexml_load_string($xml);
// print_r($result_xml);

echo $result_xml-> Parts->Part->Dimensions->Length . "  ";
echo $result_xml-> Parts->Part->Dimensions->Width . "  "; 
echo $result_xml-> Parts->Part->Dimensions->Thickness . "  "; 
echo $result_xml-> Parts->Part->Weight . "  "; 
echo $result_xml-> Parts->Part->TotalQuantityInJob . "  "; 
echo $result_xml-> Parts->Part->TargetProcessingTimePerPiece . "  "; 

?>