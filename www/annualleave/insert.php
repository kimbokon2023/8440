<?php   
session_start();   
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  

isset($_REQUEST["mode"])  ? $mode = $_REQUEST["mode"] : $mode=""; 
isset($_REQUEST["num"])  ? $num = $_REQUEST["num"] : $num=""; 

$name=$_REQUEST["name"];			  
$part=$_REQUEST["part"];			  

$dateofentry=$_REQUEST["dateofentry"];
$referencedate=$_REQUEST["referencedate"];
$availableday=$_REQUEST["availableday"];			  
$comment=$_REQUEST["comment"];			  
			  
require_once("../lib/mydb.php");
$pdo = db_connect();
     
 if ($mode=="modify"){      
     try{
        $sql = "select * from mirae8440.almember where num=?";  // get target record
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
		$sql = "update mirae8440.almember set name=?, part=?, dateofentry=?, referencedate=?, availableday=? , comment=? ";
		$sql .= " where num=?  LIMIT 1";		

		$stmh = $pdo->prepare($sql); 
		$stmh->bindValue(1, $name, PDO::PARAM_STR);  
		$stmh->bindValue(2, $part, PDO::PARAM_STR);  
		$stmh->bindValue(3, $dateofentry, PDO::PARAM_STR);  
		$stmh->bindValue(4, $referencedate, PDO::PARAM_STR);  
		$stmh->bindValue(5, $availableday, PDO::PARAM_STR);  
		$stmh->bindValue(6, $comment, PDO::PARAM_STR);  
		$stmh->bindValue(7, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
		$stmh->execute();
		$pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }      
 } 
 
 if ($mode=="insert"){	 	 
   try{
		$pdo->beginTransaction();
		 
		$sql = "insert into mirae8440.almember(name, part, dateofentry, referencedate, availableday, comment) ";     
		$sql .= " values(?, ?, ?, ?, ?, ?) ";
		   
		$stmh = $pdo->prepare($sql); 
		$stmh->bindValue(1, $name, PDO::PARAM_STR);  
		$stmh->bindValue(2, $part, PDO::PARAM_STR);  
		$stmh->bindValue(3, $dateofentry, PDO::PARAM_STR);  
		$stmh->bindValue(4, $referencedate, PDO::PARAM_STR);  
		$stmh->bindValue(5, $availableday, PDO::PARAM_STR); 
		$stmh->bindValue(6, $comment, PDO::PARAM_STR); 
	 
		$stmh->execute();
		$pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
}

 if ($mode=="delete"){	 	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "delete from  mirae8440.almember where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();	 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
}

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"dateofentry" =>  $dateofentry,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));   
   
 ?>