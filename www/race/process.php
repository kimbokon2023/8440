 <?php session_start(); 
  
  if(isset($_REQUEST["username"]))
       $username=$_REQUEST["username"];
 if(isset($_REQUEST["score"]))  //modify_form에서 호출할 경우
    $score=$_REQUEST["score"];
   
 require_once("../lib/mydb.php");
 $pdo = db_connect();
  	 
	 $rec_time= date("Y-m-d H:i:s");  // 최초등록자 기록 
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.race(" ;
     $sql .="username, rec_time, score";     // 11
     $sql .= ") ";	 

     $sql .= " values(?, ?, ?)";   // 
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $username, PDO::PARAM_STR);             
     $stmh->bindValue(2, $rec_time, PDO::PARAM_STR);             
     $stmh->bindValue(3, $score, PDO::PARAM_STR);             	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }     
 
 ?>