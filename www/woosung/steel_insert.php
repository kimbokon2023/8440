<?php   

// 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'steelini.php';   
	  
$fieldarr = array();
$strarr = array();


if($mode=="modify" || $mode=="insert" || $mode=="copy"){   

$fieldarr = array();
$strarr = array();

array_push($fieldarr, 'workplacename');
array_push($fieldarr, 'address');
array_push($fieldarr, 'regist_day');
array_push($fieldarr, 'doneday');
array_push($fieldarr, 'demand');
array_push($fieldarr, 'secondord');
array_push($fieldarr, 'secondordman');
array_push($fieldarr, 'secondordmantel');
array_push($fieldarr, 'memo');
array_push($fieldarr, 'st_content');
array_push($fieldarr, 'update_log');
array_push($fieldarr, 'supplier');


array_push($strarr, $_REQUEST["workplacename"]);
array_push($strarr, $_REQUEST["address"]);
array_push($strarr, $_REQUEST["regist_day"]);
array_push($strarr, $_REQUEST["doneday"]);
array_push($strarr, $_REQUEST["demand"]);
array_push($strarr, $_REQUEST["secondord"]);
array_push($strarr, $_REQUEST["secondordman"]);
array_push($strarr, $_REQUEST["secondordmantel"]);
array_push($strarr, $_REQUEST["memo"]);
array_push($strarr, $_REQUEST["st_content"]);
array_push($strarr, $_REQUEST["update_log"]);
array_push($strarr, $_REQUEST["supplier"]);

if($mode=="modify")
    array_push($strarr, $id);  // 수정인 경우는 $id 넣어야 함
}
			  
 require_once("../lib/mydb.php");
 $pdo = db_connect();
     
 if ($mode=="modify"){      
     try{
        $sql = "select * from mirae8440." . $tablename . " where id = ?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$id,PDO::PARAM_STR); 
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
		    $sql .= " where id = ?  LIMIT 1";	

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
 
 // insert, copy인 경우 
 if ($mode=="insert" or $mode=="copy")	 	  
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
        $sql = "select * from mirae8440." . $tablename . " order by id desc "; 
        $stmh = $pdo->prepare($sql);
        $stmh->execute();  		
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
		$id=$row["id"];
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     }    
	   
}
   
 if ($mode=="delete"){	 	     
	   
	try{	 
     $pdo->beginTransaction();
  	 
     $sql = "delete from mirae8440." . $tablename . " where id = ? ";
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$id,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();	 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
}

echo $id;


 ?>

 

