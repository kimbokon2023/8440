<?php
 session_start();
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }
   
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21   
//header("Refresh:0");  // reload refresh   

include $_SERVER['DOCUMENT_ROOT'] . "/common.php";

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>조직도</title>
    <link rel="stylesheet" href="styles.css">
	
    <script>
        var userLevel = <?php echo isset($_SESSION["level"]) ? $_SESSION["level"] : 0; ?>;
    </script>	
	
</head>
<body>
    <div id="organizationChart"></div>
    <script src="scripts.js"></script>
</body>
</html>
