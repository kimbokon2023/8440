  <?php   
   session_start();
   
     session_start(); 
  
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=8) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://5130.co.kr/login/logout.php");
         exit;
   }
   
   $num=$_REQUEST["num"];
         
    require_once("../lib/mydb.php");
   $pdo = db_connect();
 
   $upload_dir = '../uplaods/output/';   //물리적 저장위치   
 
   try{
     $sql = "select * from chandj.output where num = ? ";
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1,$num,PDO::PARAM_STR); 
     $stmh->execute();
     $count = $stmh->rowCount();              
 
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
     $copied_name[0] = $row[file_copied_0];
     $copied_name[1] = $row[file_copied_1];
     $copied_name[2] = $row[file_copied_2];
     $copied_name[3] = $row[file_copied_3];
     $copied_name[4] = $row[file_copied_4];
      
     for ($i=0; $i<5; $i++)
     { 
         if ($copied_name[$i])
         {
	     $image_name = $upload_dir.$copied_name[$i];
	     unlink($image_name);
	  }
     }
   }catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
   }
 
   try{
     $pdo->beginTransaction();
     $sql = "delete from chandj.output where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();
 
     header("Location:http://5130.co.kr/output/list.php");
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
?>