<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
$level= $_SESSION["level"];
$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];	
$WebSite = "https://8440.co.kr/";

?>