 <?php session_start(); 
 
 $mode=$_REQUEST["mode"];  

 $num=$_REQUEST["num"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $page=$_REQUEST["page"]; 
 $scale=$_REQUEST["scale"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $orderdate=$_REQUEST["orderdate"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $indate=$_REQUEST["indate"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $text=$_REQUEST["textsave"] ?? '';
 $company=$_REQUEST["company"];  
  
 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

  if($mode=='modify')
  {	  
try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.make set orderdate=?, indate=? , company=?, text=?   ";
        $sql .= " where num=?  LIMIT 1"; 
 
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $orderdate, PDO::PARAM_STR);  
     $stmh->bindValue(2, $indate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $company, PDO::PARAM_STR);       

     $stmh->bindValue(4, $text, PDO::PARAM_STR);        

     $stmh->bindValue(5, $num, PDO::PARAM_STR);           	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   

  }

else
{	

	
	 // 데이터 신규 등록하는 구간
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.make(orderdate, indate, company, text) ";	 

     $sql .= " values(?, ?, ?, ?)"; 
	   
     $stmh = $pdo->prepare($sql); 
	 
     $stmh->bindValue(1, $orderdate, PDO::PARAM_STR);  
     $stmh->bindValue(2, $indate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $company, PDO::PARAM_STR);       

     $stmh->bindValue(4, $text, PDO::PARAM_STR);        
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
	 
}

  if($mode!="modify")
   header("Location:http://8440.co.kr/make/read_DB.php?num=$num&scale=$scale&page=$page");    // 신규가입일때는 리스트로 이동
		else		 
	   header("Location:http://8440.co.kr/make/view.php?num=$num&scale=$scale&page=$page");  
 ?>
