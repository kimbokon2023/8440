 <?php session_start(); ?>
 
    <?php
  if(!isset($_SESSION["userid"])) {
 ?>
  <script>
        alert('로그인 후 이용해 주세요.');
        history.back();
  </script>
  
 <?php
  }
  
  
$a=1;
require_once("../lib/mydb.php");
$pdo = db_connect();   
$alerts=1;  // 알람설정
	
try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.alert set alert=?  ";
        $sql .= " where num=?  LIMIT 1";  
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $alerts, PDO::PARAM_STR);    	 
     $stmh->bindValue(2, $a, PDO::PARAM_STR);      	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   	
 
  
  
  
  if(isset($_REQUEST["page"]))
    $page=$_REQUEST["page"];
  else 
    $page=1;   // 1로 설정해야 함
 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["childnum"]))
    $childnum=$_REQUEST["childnum"];
 else 
    $childnum="";
if(isset($_REQUEST["parent"]))
    $parent=$_REQUEST["parent"];
 else 
    $parent="";
       
 if(isset($_REQUEST["html_ok"]))  //checkbox는 체크해야 변수명 전달됨.
    $html_ok=$_REQUEST["html_ok"];
  else
    $html_ok="";
   
  $subject=$_REQUEST["workplacename"];
  $content=$_REQUEST["content"];
  
        
require_once("../lib/mydb.php");
 $pdo = db_connect();
    
 if ($mode=="modify"){
	$is_html = "1";          
     try{
        $sql = "select * from mirae8440.voc where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$childnum,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
       
     try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.voc set subject=?, content=?, is_html=? where num=?";
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);  
        $stmh->bindValue(2, $content, PDO::PARAM_STR);  
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);     
        $stmh->bindValue(4, $childnum, PDO::PARAM_STR);   
        $stmh->execute();
        $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } else	{
	$is_html = "1";    
   try{
     $pdo->beginTransaction();
     $sql = "insert into mirae8440.voc(id, name, nick, subject, content, regist_day, hit, is_html, ";
     $sql .= " file_name_0, file_name_1, file_name_2, file_copied_0,  file_copied_1, file_copied_2, parent) ";
     $sql .= "values(?, ?, ?, ?, ?, now(), 0, ?, ?, ?, ?, ?, ?, ?, ?)";
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $_SESSION["userid"], PDO::PARAM_STR);  
     $stmh->bindValue(2, $_SESSION["name"], PDO::PARAM_STR);  
     $stmh->bindValue(3, $_SESSION["nick"], PDO::PARAM_STR);   
     $stmh->bindValue(4, $subject, PDO::PARAM_STR);  
     $stmh->bindValue(5, $content, PDO::PARAM_STR);  
     $stmh->bindValue(6, $is_html, PDO::PARAM_STR);    
     $stmh->bindValue(7, $upfile_name[0], PDO::PARAM_STR); 
     $stmh->bindValue(8, $upfile_name[1], PDO::PARAM_STR);  
     $stmh->bindValue(9, $upfile_name[2], PDO::PARAM_STR);   
     $stmh->bindValue(10, $copied_file_name[0], PDO::PARAM_STR);  
     $stmh->bindValue(11, $copied_file_name[1], PDO::PARAM_STR);  
     $stmh->bindValue(12, $copied_file_name[2], PDO::PARAM_STR);        
     $stmh->bindValue(13, $parent, PDO::PARAM_STR);        
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
   }
header("Location:http://8440.co.kr/p/view.php?num=$parent");
 ?>

