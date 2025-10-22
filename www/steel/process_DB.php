<?php
session_start();

// Environment detection for URL
$is_local = (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false));
$base_url = $is_local ? 'http://localhost' : 'http://5130.co.kr';

// Initialize session variables with null safety
$level = $_SESSION["level"] ?? null;

if (!isset($_SESSION["level"]) || $level >= 8) {
    echo "<script> alert('관리자 승인이 필요합니다.') </script>";
    sleep(2);
    header("Location:" . $base_url . "/login/logout.php");
    exit;
}

// Initialize request variables with null safety
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

// code는 접수완료, 처리완료를 표시해 준다.
$code = $_REQUEST["code"] ?? '';

if ($code == "1") {
    $regist_state = "2";
} else {
    $regist_state = "3";
}  
 		
 require_once("../lib/mydb.php");
 $pdo = db_connect();
     
     try{
        $sql = "select regist_state from chandj.output where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
       
	try{
        $pdo->beginTransaction();   
        $sql = "update chandj.output set regist_state=? where num=?  LIMIT 1";            
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $regist_state, PDO::PARAM_STR);         
     $stmh->bindValue(2, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
    header("Location:" . $base_url . "/output/view.php?num=$num&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");  
 ?>