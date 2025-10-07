<?php   
/// 개별로 신청하는 것에 대한 DB처리 구간 ///
/// 개별로 신청하는 것에 대한 DB처리 구간 ///

session_start();   

$level= $_SESSION["level"];
$user_name= $_SESSION["name"];
$id= $_SESSION["userid"];

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  

isset($_REQUEST["mode"])  ? $mode = $_REQUEST["mode"] : $mode=""; 
isset($_REQUEST["num"])  ? $num = $_REQUEST["num"] : $num=""; 
isset($_REQUEST["name"])  ? $name = $_REQUEST["name"] : $name=""; 
isset($_REQUEST["part"])  ? $part = $_REQUEST["part"] : $part=""; 
isset($_REQUEST["state"])  ? $state = $_REQUEST["state"] : $state=""; 

$id= $_SESSION["userid"];		
$registdate=$_REQUEST["registdate"];			  
$item=$_REQUEST["item"];			  
$askdatefrom=$_REQUEST["askdatefrom"];			  
$askdateto=$_REQUEST["askdateto"];			  
$usedday=$_REQUEST["usedday"];			  
$content=$_REQUEST["content"];			  
$state=$_REQUEST["state"];			  
$memo=$_REQUEST["memo"];			  
			  
require_once(includePath('lib/mydb.php'));
 $pdo = db_connect();
     
 if ($mode=="modify"){      
     try{
        $sql = "select * from mirae8440.afterorder where num=?";  // get target record
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
        $sql = "update mirae8440.afterorder set id=?, name=?, registdate=?, item=?, askdatefrom=?,  askdateto=?,  usedday=?,  content=?,  state=?, part=?, memo=? ";
        $sql .= " where num=?  LIMIT 1";		
		
    
	$stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $id, PDO::PARAM_STR);  
	$stmh->bindValue(2, $name, PDO::PARAM_STR);  
	$stmh->bindValue(3, $registdate, PDO::PARAM_STR);  
	$stmh->bindValue(4, $item, PDO::PARAM_STR);  
	$stmh->bindValue(5, $askdatefrom, PDO::PARAM_STR);  
    $stmh->bindValue(6, $askdateto, PDO::PARAM_STR);      
    $stmh->bindValue(7, $usedday, PDO::PARAM_STR);        
    $stmh->bindValue(8, $content, PDO::PARAM_STR);        
    $stmh->bindValue(9, $state, PDO::PARAM_STR);          
    $stmh->bindValue(10, $part, PDO::PARAM_STR);           
    $stmh->bindValue(11, $memo, PDO::PARAM_STR);           
    $stmh->bindValue(12, $num, PDO::PARAM_STR);           
	 
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
  	 
     $sql = "insert into mirae8440.afterorder(id , name , registdate , item , askdatefrom ,  askdateto ,  usedday ,  content ,  state, part , memo) ";     
     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
	   
	  
     $stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $id, PDO::PARAM_STR);  
	$stmh->bindValue(2, $name, PDO::PARAM_STR);  
	$stmh->bindValue(3, $registdate, PDO::PARAM_STR);  
	$stmh->bindValue(4, $item, PDO::PARAM_STR);  
	$stmh->bindValue(5, $askdatefrom, PDO::PARAM_STR);  
    $stmh->bindValue(6, $askdateto, PDO::PARAM_STR);      
    $stmh->bindValue(7, $usedday, PDO::PARAM_STR);        
    $stmh->bindValue(8, $content, PDO::PARAM_STR);        
    $stmh->bindValue(9, $state, PDO::PARAM_STR);     
    $stmh->bindValue(10, $part, PDO::PARAM_STR);     
    $stmh->bindValue(11, $memo, PDO::PARAM_STR);     
	 
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
  	 
     $sql = "delete from  mirae8440.afterorder where num = ?";  
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
		"registdate" =>  $registdate,
		"state" =>  $state,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));   
   
 ?>