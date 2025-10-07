 <?php session_start(); ?>
  
 <meta charset="utf-8">
 <?php

  if(isset($_REQUEST["name"]))
       $name=$_REQUEST["name"];
 if(isset($_REQUEST["score"]))  //modify_form에서 호출할 경우
    $score=$_REQUEST["score"];
   
 require_once("../lib/mydb.php");
 $pdo = db_connect();
  	 
  // 데이터 신규 등록하는 구간
	 $rec_date= date("Y-m-d H:i:s");  // 최초등록자 기록 
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.tetris(" ;
     $sql .="name, rec_date, score";     // 11
     $sql .= ") ";	 

     $sql .= " values(?, ?, ?)";   // 
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $name, PDO::PARAM_STR);             
     $stmh->bindValue(2, $rec_date, PDO::PARAM_STR);             
     $stmh->bindValue(3, $score, PDO::PARAM_STR);             	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }     

      // sleep(5); 
      header("Location:http://8440.co.kr/tetris/index.php?new=yes");   
 
 ?>