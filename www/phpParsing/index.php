<?php
$myfiles=$_REQUEST["myfiles"];
?>
<!DOCTYPE html>
<html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<body>

<?php

// 파일 열기

$filename = "sample.xml";
$handle = fopen($filename, "rb");
if (FALSE === $handle) {
    exit("Failed to open stream to URL");
}

$contents = '';

while (!feof($handle)) {
    $contents .= fread($handle, 8192);
}


// $tmp = (string) $contents

 // echo  strrpos($ tmp, 'JobName') . "<br>";​
 
 $tmp = str_replace("<","",serialize($contents));
 $tmp = str_replace(">","",serialize($tmp));
 
 // Jobname 문자열 찾아서 번호 저장
 $Jobname = substr($tmp, strpos($tmp,"JobName=") + 9 ,9);  
 
 print $Jobname;
 
  print "<br>";
 print "<br>";
 print "<br>";
 print "<br>";
 print "<br>";
 
 // print_r($tmp);
 
 print "<br>";
 print "<br>";
 print "<br>";
 print "<br>";
 print "<br>";

// print_r($contents);

fclose($handle);

/* $xml=simplexml_load_file($filename);
echo $xml->getName() . "<br>";

		foreach($xml->children() as $child)
		{
			echo $child->getName() . ": " . $child . "<br>";
		} */

 print "<br>";
 print "<br>";
 print "<br>";
 print "<br>";
 print "<br>";  	
	
$xml = file_get_contents($myfiles);
$result_xml = simplexml_load_string($xml);
// print_r($result_xml);

echo $result_xml-> Parts->Part->Dimensions->Length . "  ";
echo $result_xml-> Parts->Part->Dimensions->Width . "  "; 
echo $result_xml-> Parts->Part->Dimensions->Thickness . "  "; 
echo $result_xml-> Parts->Part->Weight . "  "; 
echo $result_xml-> Parts->Part->TotalQuantityInJob . "  "; 
echo $result_xml-> Parts->Part->TargetProcessingTimePerPiece . "  "; 

	
?>

<!DOCTYPE HTML>
<html>
<head>
</head>
<body>
<!--multiple is set to allow multiple files to be selected-->
<form> 
<br>
 <label for="avatar">xml파일 선택 :</label>
<br>
<input id="myfiles" name="myfiles" type="file" accept=".xml" required>
<input type="text" id="inputValue" name="inputValue" >
<div id="php_code" name="php_code">  </div>

<!-- <input type="file" name="filename" onchange="fileTypeCheck(this)" accept=".xml"/> -->

<input type="file" id="uploadInput" name="uploadInput" onchange="javascript:getRealPath(this);" />

<input type="hidden" id="real_path" name="real_path" value="" />

    </form>
    <script src="main.js"> </script>
</html>

</body>

</html>