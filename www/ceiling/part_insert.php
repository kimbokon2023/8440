<?php   
session_start();    
  
$level= $_SESSION["level"];
$user_name= $_SESSION["name"];

isset($_REQUEST["mode"])  ? $mode = $_REQUEST["mode"] : $mode=""; 
isset($_REQUEST["num"])  ? $num = $_REQUEST["num"] : $num=""; 
	  
// 테이블명 명시	  
$tablename = 'part';
$itemCount = 20 ;
		  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
     

if($mode=="modify" || $mode=="insert"){   

$fieldarr = array();
$strarr = array();

array_push($fieldarr, 'inputdate') ; 
array_push($strarr, $_REQUEST['inputdate'])     ; 

for ($i=0;$i<$itemCount;$i++)
	{
		$tmp = "part" . (int)($i+1) ; 
		array_push($fieldarr, $tmp)   ; 
		array_push($strarr, $_REQUEST[$tmp])     ; 
	}

if($mode=="modify")
    array_push($strarr, $num);
}

 if ($mode=="modify"){      
     try{
        $sql = "select * from mirae8440." . $tablename . " where num=?";  // get target record
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
		$sql = "update mirae8440." . $tablename . " set " ;     	
		for($i=0;$i<count($fieldarr);$i++)  //  필드 배열수 만큼 반복
		 {
			 if($i!=0)
				  $sql .= ' , ';
		   $sql .= $fieldarr[$i] . '=? ' ;
		 }
		    $sql .= " where num=?  LIMIT 1";	

        print $sql;			
    
		$stmh = $pdo->prepare($sql); 
		for($i=0;$i<count($strarr);$i++)  //  필드 배열수 만큼 반복	
			$stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_STR);  
		 
		 $stmh->execute();
		 $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } 
 
 // insert인 경우 
 if ($mode=="insert")	 	  
{	
     $sql = "insert into mirae8440." . $tablename . "  ( " ;     	

	for($j=0;$j<count($fieldarr);$j++)  //  필드 배열수 만큼 반복
	 {
		 if($j!=0)
			  $sql .= ' , ';
	   $sql .= $fieldarr[$j] ;
	 }
	  $sql .= ' )  values( ';

	for($j=0;$j<count($fieldarr);$j++)  //  필드 배열수 만큼 반복
	 {
		 if($j!=0 ) 
			  $sql .= ' , ';
	   $sql .= '?';
	 }	      
	$sql .= ' ) ';
	
   try{
     $pdo->beginTransaction();
     $stmh = $pdo->prepare($sql);
	 for($i=0;$i<count($strarr);$i++)
       $stmh->bindValue($i+1,$strarr[$i],PDO::PARAM_STR);      	 
	 
     $stmh->execute();   
     $pdo->commit(); 
		} catch (PDOException $Exception) {
		   $pdo->rollBack();
		   print "오류: ".$Exception->getMessage();
	   }    
	   
// insert 후 레코드 번호를 추출한다.	   
	try{
        $sql = "select * from mirae8440." . $tablename . " order by num desc "; 
        $stmh = $pdo->prepare($sql);
        $stmh->execute();  		
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
		$num=$row["num"];
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     }    
	   
}
   
 if ($mode=="delete"){	 	 
	   
	try{	 
     $pdo->beginTransaction();
  	 
     $sql = "delete from mirae8440." . $tablename . " where num = ? ";
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();	 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
}

echo $num;

// echo "<script> document.getElementById('num').value='" . $num . "'; </script>";   // 부모창에 num 기록

// var_dump($_FILES['upfile']['name']);
//var_dump($fieldarr]);
//var_dump($strarr);

 ?>
 

 

