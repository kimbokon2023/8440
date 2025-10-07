<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/session.php');	

$which = $_REQUEST["which"] ?? '';
$search_opt = $_REQUEST["search_opt"] ?? '';
$displaySelect = $_REQUEST["displaySelect"] ?? '';
$page = $_REQUEST["page"] ?? 1;  // 1로 설정해야 함
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';

$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';
$proDate = $_REQUEST["proDate"] ?? '';
$writer = $_REQUEST["writer"] ?? '';
$amount = $_REQUEST["amount"] ?? '';
$memo = $_REQUEST["memo"] ?? '';
			  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();
     
 if ($mode=="modify"){

     try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.automan set proDate=?, writer=?, amount=?, memo=?, which=? ";        
        $sql .= " where num=?  LIMIT 1";		

	$stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $proDate, PDO::PARAM_STR);  
	$stmh->bindValue(2, $writer, PDO::PARAM_STR);  
	$stmh->bindValue(3, $amount, PDO::PARAM_STR);  
	$stmh->bindValue(4, $memo, PDO::PARAM_STR);  
	$stmh->bindValue(5, $which, PDO::PARAM_STR);  
    $stmh->bindValue(6, $num, PDO::PARAM_STR);   
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } else	{
	 	 
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.automan(proDate, writer, amount, memo, which) ";          
     $sql .= " values(?, ?, ?, ?, ?)";
	   
     $stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $proDate, PDO::PARAM_STR);  
	$stmh->bindValue(2, $writer, PDO::PARAM_STR);  
	$stmh->bindValue(3, $amount, PDO::PARAM_STR);  
	$stmh->bindValue(4, $memo, PDO::PARAM_STR);  
	$stmh->bindValue(5, $which, PDO::PARAM_STR);      	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
   }
  
  if($mode=="not")
   header("Location:http://8440.co.kr/automan/read_DB.php?num=$num&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");    // 신규가입일때는 리스트로 이동
	 else		 
     header("Location:http://8440.co.kr/automan/view.php?num=$num&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");  
 ?>