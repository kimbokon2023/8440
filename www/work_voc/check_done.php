 <?php session_start(); ?>
 <meta charset="utf-8">
 <?php
  if(!isset($_SESSION["userid"])) {
 ?>
  <script>
        alert('로그인 후 이용해 주세요.');
        history.back();
  </script>
 <?php
  }
  if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
  if(isset($_REQUEST["page"]))
    $page=$_REQUEST["page"]; 

if(isset($_REQUEST["option"]))
    $option=$_REQUEST["option"];
     
require_once("../lib/mydb.php");
$pdo = db_connect();
   
 	$is_html = "2";          
       
     try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.voc set is_html=? where num=?";
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $is_html, PDO::PARAM_STR);     
        $stmh->bindValue(2, $num, PDO::PARAM_STR);   
        $stmh->execute();
        $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
 if($option==1)
     header("Location:http://8440.co.kr/work_voc/view_temp.php?num=$num&page=$page");
    else
		header("Location:http://8440.co.kr/work_voc/view.php?num=$num&page=$page");		
 ?>

