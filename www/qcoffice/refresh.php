 <?php session_start(); 
   
isset($_REQUEST["mcname"])  ? $mcname=$_REQUEST["mcname"] :   $mcname=''; 
isset($_REQUEST["selnum"])  ? $selnum=$_REQUEST["selnum"] :   $selnum=1;  

header("Location:http://8440.co.kr/qcoffce/laser.php?mcname=$mcname&selnum=$selnum");  
	
 ?>