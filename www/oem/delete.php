  <?php   
   session_start();
   $num=$_REQUEST["num"];

if(isset($_REQUEST["cursort"])) 
	 $cursort=$_REQUEST["cursort"]; // 미실측리스트
   else
	if(isset($_POST["cursort"]))   
         $cursort=$_POST["cursort"]; // 미실측리스트
	 else
		 $cursort='0';		  


if(isset($_REQUEST["sortof"])) 
	 $sortof=$_REQUEST["sortof"]; // 미실측리스트
   else
	if(isset($_POST["sortof"]))   
         $sortof=$_POST["sortof"]; // 미실측리스트
	 else
		 $sortof='0';		 
	 
 if(isset($_REQUEST["stable"])) 
	 $stable=$_REQUEST["stable"]; // 미실측리스트
   else
	if(isset($_POST["stable"]))   
         $stable=$_POST["stable"]; // 미실측리스트
	 else
		 $stable='0';	
	 
    require_once("../lib/mydb.php");
   $pdo = db_connect();
 
   $upload_dir = '../uploads/'; ;   //물리적 저장위치   
 
   try{
     $sql = "select * from mirae8440.oem where num = ? ";
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1,$num,PDO::PARAM_STR); 
     $stmh->execute();
     $count = $stmh->rowCount();              
 
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
     $copied_name[0] = $row[file_copied_0];
     $copied_name[1] = $row[file_copied_1];
     $copied_name[2] = $row[file_copied_2];
      
     for ($i=0; $i<3; $i++)
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
     $sql = "delete from mirae8440.oem where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();
 
     header("Location:http://8440.co.kr/oem/list.php");
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }
?>