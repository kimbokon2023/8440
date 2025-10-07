<?php   
// 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'ini.php';   

if($mode=="modify" || $mode=="insert"  || $mode=="copy" ){   

 require_once("../lib/mydb.php");
 $pdo = db_connect();
     
 if ($mode=="modify"){      
     try{
        $sql = "select * from mirae8440.wssteel where id=?";  // get target record
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
		$sql = "update mirae8440.wssteel contents set=? where id=?  LIMIT 1 " ;     			
		$stmh = $pdo->prepare($sql); 
		$stmh->bindValue(1, $contents, PDO::PARAM_STR);  		 
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
     $sql = "insert into mirae8440.wssteel (contents) values(?) ";
	
   try{
     $pdo->beginTransaction();
     $stmh = $pdo->prepare($sql);
       $stmh->bindValue(1,$contents ,PDO::PARAM_STR);      	 	 
     $stmh->execute();   
     $pdo->commit(); 
		} catch (PDOException $Exception) {
		   $pdo->rollBack();
		   print "오류: ".$Exception->getMessage();
	   }    
	   
// insert 후 레코드 번호를 추출한다.	   
	try{
        $sql = "select * from mirae8440.wssteel order by id desc "; 
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
  	 
     $sql = "delete from mirae8440.wssteel where id = ? ";
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$id,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();	 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
}

$data = $id ;

echo $data ;

 ?>
 

 

