<meta charset="utf-8">

<?php
session_start();

// Environment detection for URL
$is_local = (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false));
$base_url = $is_local ? 'http://localhost' : 'http://8440.co.kr';

// Initialize request variables with null safety
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$page = $_REQUEST["page"] ?? '';
$process = $_REQUEST["process"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$year = $_REQUEST["year"] ?? '';

 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.steel order by num desc limit 1";
     $stmh = $pdo->prepare($sql);  
     $stmh->execute();                  
     $row = $stmh->fetch(PDO::FETCH_ASSOC);	 
     $num=$row["num"];
	 
	print "마지막 레코드 번호 : " . $num;		 

	}
   catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
  header("Location:" . $base_url . "/steel/view.php?num=$num&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");  
 ?>  
	
