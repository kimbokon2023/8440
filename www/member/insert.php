<?php\nrequire_once __DIR__ . '/../common/functions.php';
session_start();   

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  

isset($_REQUEST["mode"])  ? $mode = $_REQUEST["mode"] : $mode=""; 

include '_request.php';
			  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
     
 if ($mode=="modify"){      
		  
     try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.member set name=?, pass=?, level=?,  part=?,  hp=?,  numorder=?, eworks_level=? , position=?, division=? ";
        $sql .= " where id = ?  LIMIT 1";		
		   
	$stmh = $pdo->prepare($sql); 	
	$stmh->bindValue(1, $name, PDO::PARAM_STR);  
	$stmh->bindValue(2, $pass, PDO::PARAM_STR);  
	$stmh->bindValue(3, $level, PDO::PARAM_STR);  
	$stmh->bindValue(4, $part, PDO::PARAM_STR);  
    $stmh->bindValue(5, $hp, PDO::PARAM_STR);      
    $stmh->bindValue(6, $numorder, PDO::PARAM_STR);       
    $stmh->bindValue(7, $eworks_level, PDO::PARAM_STR);       
    $stmh->bindValue(8, $position, PDO::PARAM_STR);       
    $stmh->bindValue(9, $division, PDO::PARAM_STR);       
    $stmh->bindValue(10, $id, PDO::PARAM_STR);       
	 
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
  	 
	$sql = "insert into mirae8440.member( name , pass , level , part ,  hp , numorder, eworks_level, position, division,  id ) ";     
	$sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";  
	  
    $stmh = $pdo->prepare($sql); 
	   
	$stmh->bindValue(1, $name, PDO::PARAM_STR);  
	$stmh->bindValue(2, $pass, PDO::PARAM_STR);  
	$stmh->bindValue(3, $level, PDO::PARAM_STR);  
	$stmh->bindValue(4, $part, PDO::PARAM_STR);  
    $stmh->bindValue(5, $hp, PDO::PARAM_STR);      
    $stmh->bindValue(6, $numorder, PDO::PARAM_STR);       
    $stmh->bindValue(7, $eworks_level, PDO::PARAM_STR);       
    $stmh->bindValue(8, $position, PDO::PARAM_STR);       
    $stmh->bindValue(9, $division, PDO::PARAM_STR);       
    $stmh->bindValue(10, $id, PDO::PARAM_STR);    	   
	 
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
  	 
     $sql = "delete from  mirae8440.member where id = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$id,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();	 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
}

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"id" =>  $id,
		
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));   
   
 ?>