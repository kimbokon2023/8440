<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문 받는측에서 필요한 정보임 ajax로 보내는 쪽에서 type : json
   
// 임시저장된 첨부파일을 확정하기 위해 검사하기  
isset($_REQUEST["timekey"])  ? $timekey=$_REQUEST["timekey"] :  $timekey='';   // 신규데이터에 생성할때 임시저장키  

 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

 if(isset($_REQUEST["tablename"]))
    $tablename=$_REQUEST["tablename"];
 else 
    $tablename="";
       
 if(isset($_REQUEST["is_html"]))  //checkbox는 체크해야 변수명 전달됨.
    $is_html=$_REQUEST["is_html"];
  else
    $is_html="";  

 if(isset($_REQUEST["noticecheck"])) 
    $noticecheck=$_REQUEST["noticecheck"];
  else
    $noticecheck="";
   
  $subject=$_REQUEST["subject"];
  $content=$_REQUEST["content"];
  $division=$_REQUEST["division"];
  $searchtext=$_REQUEST["searchtext"];
   
        
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
    
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
        $sql = "update mirae8440." . $tablename . " set subject=?, content=?, is_html=?, division=?, searchtext=? where num=?  ";
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);  
        $stmh->bindValue(2, $content, PDO::PARAM_STR);  
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);             
        $stmh->bindValue(4, $division, PDO::PARAM_STR);             
        $stmh->bindValue(5, $searchtext, PDO::PARAM_STR);             
        $stmh->bindValue(6, $num, PDO::PARAM_STR);   
        $stmh->execute();
        $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } else	{
    if ($is_html =="y"){
	         $content = htmlspecialchars($content);
       }
   try{
     $pdo->beginTransaction();
     $sql = "insert into mirae8440." . $tablename . " (id, name, nick, subject, content, regist_day, hit, is_html, division, searchtext) ";     
     $sql .= "values(?, ?, ?, ?, ?, now(), 0, ?, ?, ?)";
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $_SESSION["userid"], PDO::PARAM_STR);  
     $stmh->bindValue(2, $_SESSION["name"], PDO::PARAM_STR);  
     $stmh->bindValue(3, $_SESSION["nick"], PDO::PARAM_STR);   
     $stmh->bindValue(4, $subject, PDO::PARAM_STR);  
     $stmh->bindValue(5, $content, PDO::PARAM_STR);  
     $stmh->bindValue(6, $is_html, PDO::PARAM_STR);   
     $stmh->bindValue(7, $division, PDO::PARAM_STR);   
     $stmh->bindValue(8, $searchtext, PDO::PARAM_STR);   
     
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
}   
 
  
if ($mode!=="modify"){
	
// 신규데이터인경우 num을 추출한 후 view로 보여주기
 $sql="select * from mirae8440." . $tablename . " order by num asc"; 					

  try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	   
 			  $num=$row["num"];			   			 
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    

// 신규데이터인경우 num을 추출한 후 view로 보여주기  
  try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.picuploads set parentnum=? where parentnum=? ";
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $num, PDO::PARAM_STR);  
        $stmh->bindValue(2, $timekey, PDO::PARAM_STR);   
        $stmh->execute();
        $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
      		
}


 $data = [   
 'num' => $num, 
 'tablename' => $tablename
 ]; 
 
 echo json_encode($data, JSON_UNESCAPED_UNICODE);


 ?>

