<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php"); 	
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num='';
isset($_REQUEST["update_log"])  ? $update_log=$_REQUEST["update_log"] : $update_log='';
 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 // 당일 입고완료처리
 
$which='3';
$inventory='이관';

$indate= date("Y-m-d");   // 현재일자 변수지정   
$today=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
$update_log = $today . $update_log . "&#10";  // 개행문자 Textarea     		
       
	try{
        $pdo->beginTransaction();   
        $sql = "update " . $DB . "." . "eworks set which=? , indate=?, inventory=?, update_log=? where num=?  LIMIT 1";            
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $which, PDO::PARAM_STR);         
     $stmh->bindValue(2, $indate, PDO::PARAM_STR);         
     $stmh->bindValue(3, $inventory, PDO::PARAM_STR);         
     $stmh->bindValue(4, $update_log, PDO::PARAM_STR);         
     $stmh->bindValue(5, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }      

//  요청자료를 읽어서 원자재에 이관함
 
   try{
      $sql = "select * from " . $DB . "." . "eworks where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
      $nowday=date("Y-m-d");   // 현재일자 변수지정   
              $num=$row["num"];
 			  $outdate=$nowday;			  
			  
			  $indate=$nowday;
			  $outworkplace=$row["outworkplace"];
			  
			  $steel_item=$row["steel_item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $request_comment=$row["request_comment"];			  
			  $which='1';
			  $model=$row["model"];		
			  $supplier=$row["supplier"];	
					

     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
    	 
	 // 원재재 입고처리 데이터 신규 등록하는 구간
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into " . $DB . "." . "steel(which, outdate, indate, outworkplace, item, spec, steelnum, company, comment, model,first_writer, supplier ) ";
     
     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	 
    //first writer 
	$first_writer=$_SESSION["name"] . " _" . date("Y-m-d H:i:s");  // 최초등록자 기록     
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $which, PDO::PARAM_STR);  
     $stmh->bindValue(2, $outdate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $indate, PDO::PARAM_STR);  
     $stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);  
     $stmh->bindValue(5, $steel_item, PDO::PARAM_STR);  
     $stmh->bindValue(6, $spec, PDO::PARAM_STR);  
     $stmh->bindValue(7, $steelnum, PDO::PARAM_STR);  
     $stmh->bindValue(8, $company, PDO::PARAM_STR);  
     $stmh->bindValue(9, $request_comment, PDO::PARAM_STR);  
     $stmh->bindValue(10, $model, PDO::PARAM_STR);  
     $stmh->bindValue(11, $first_writer, PDO::PARAM_STR);  
     $stmh->bindValue(12, $supplier, PDO::PARAM_STR);  
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }    

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>  $num ,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));	 
   
    ?>
  