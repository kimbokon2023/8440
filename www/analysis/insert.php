 <?php session_start(); ?>
  
 <meta charset="utf-8">
 <script src="http://5130.co.kr/analysis/make.js"></script>
 <?php
 
 $modify=$_REQUEST["modify"];  

 $ordercompany=$_REQUEST["ordercompany"]; 	     // 발주처  
 $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호  
 $upnum=$_REQUEST["upnum"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $num=$_REQUEST["num"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $outputnum=$_REQUEST["outputnum"]; 
 
 $steeltype=$_REQUEST["steeltype"]; 
 $tmp_steeltype=$steeltype; 
 
 $steel_alias=$_REQUEST["steel_alias"]; 
 $tmp_steel_alias=$steel_alias;  
 
 $copied_file_name=$_REQUEST["copied_file_name"]; 
 $uploaded_file=$_REQUEST["uploaded_file"]; 
 
 $length1=$_REQUEST["length1"]; 
 $length2=$_REQUEST["length2"]; 
 $length3=$_REQUEST["length3"]; 
 $length4=$_REQUEST["length4"]; 
 $length5=$_REQUEST["length5"]; 
 $amount1=$_REQUEST["amount1"]; 
 $amount2=$_REQUEST["amount2"]; 
 $amount3=$_REQUEST["amount3"]; 
 $amount4=$_REQUEST["amount4"]; 
 $amount5=$_REQUEST["amount5"]; 
 $material1=$_REQUEST["material1"]; 
 $material2=$_REQUEST["material2"]; 
 $material3=$_REQUEST["material3"]; 
 $material4=$_REQUEST["material4"]; 
 $material5=$_REQUEST["material5"]; 
 $material6=$_REQUEST["material6"]; 
 $material7=$_REQUEST["material7"]; 
 $material8=$_REQUEST["material8"]; 
 $sum1=$_REQUEST["sum1"]; 
 $sum2=$_REQUEST["sum2"]; 
 $sum3=$_REQUEST["sum3"]; 
 $sum4=$_REQUEST["sum4"]; 
 $sum5=$_REQUEST["sum5"]; 
 $sum6=$_REQUEST["sum6"]; 
 $sum7=$_REQUEST["sum7"]; 
 $sum8=$_REQUEST["sum8"]; 
 $total_text=$_REQUEST["total_text"]; 
 
 $datanum=$_REQUEST["datanum"];  //절곡데이터 번호 기억


// print "<script> alert($modify); </script>";

 require_once("../lib/mydb.php");
  $pdo = db_connect();
  if($modify=='1')
  {
     try{
        $sql = "select * from chandj.bending_write where num=?";  // get target record
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
        $sql = "update chandj.bending_write set upnum=?, outputnum=? ,length1=?, length2=? , length3=?, length4=? , length5=? , amount1=? , amount2=? , amount3=? , amount4=? , amount5=? ,";
        $sql .= " material1=?, material2=?, material3=?, material4=?, material5=?, material6=?, material7=?, material8=?, sum1=?,  sum2=?,  sum3=?,  sum4=?, sum5=?, sum6=?,  sum7=?, sum8=?, total_text=?,";
        $sql .= " steeltype=?, steel_alias=?, copied_file_name=?, uploaded_file=?, datanum=?  where num=?  LIMIT 1"; 
 
	 $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $upnum, PDO::PARAM_STR);  
     $stmh->bindValue(2, $outputnum, PDO::PARAM_STR);  
     $stmh->bindValue(3, $length1, PDO::PARAM_STR);  
     $stmh->bindValue(4, $length2, PDO::PARAM_STR);  
     $stmh->bindValue(5, $length3, PDO::PARAM_STR);  
     $stmh->bindValue(6, $length4, PDO::PARAM_STR);  
     $stmh->bindValue(7, $length5, PDO::PARAM_STR);  
     $stmh->bindValue(8, $amount1, PDO::PARAM_STR);  
     $stmh->bindValue(9, $amount2, PDO::PARAM_STR);  
     $stmh->bindValue(10, $amount3, PDO::PARAM_STR);  
     $stmh->bindValue(11, $amount4, PDO::PARAM_STR);  
     $stmh->bindValue(12, $amount5, PDO::PARAM_STR);  
     $stmh->bindValue(13, $material1, PDO::PARAM_STR);  
     $stmh->bindValue(14, $material2, PDO::PARAM_STR);  
     $stmh->bindValue(15, $material3, PDO::PARAM_STR);  
     $stmh->bindValue(16, $material4, PDO::PARAM_STR);  
     $stmh->bindValue(17, $material5, PDO::PARAM_STR);  
     $stmh->bindValue(18, $material6, PDO::PARAM_STR);  
     $stmh->bindValue(19, $material7, PDO::PARAM_STR);  
     $stmh->bindValue(20, $material8, PDO::PARAM_STR);  
     $stmh->bindValue(21, $sum1, PDO::PARAM_STR);  
     $stmh->bindValue(22, $sum2, PDO::PARAM_STR);  
     $stmh->bindValue(23, $sum3, PDO::PARAM_STR);  
     $stmh->bindValue(24, $sum4, PDO::PARAM_STR);  
     $stmh->bindValue(25, $sum5, PDO::PARAM_STR);  
     $stmh->bindValue(26, $sum6, PDO::PARAM_STR);  
     $stmh->bindValue(27, $sum7, PDO::PARAM_STR);  
     $stmh->bindValue(28, $sum8, PDO::PARAM_STR);  
     $stmh->bindValue(29, $total_text, PDO::PARAM_STR);         	 
     $stmh->bindValue(30, $steeltype, PDO::PARAM_STR);         	 
     $stmh->bindValue(31, $steel_alias, PDO::PARAM_STR);         	 
     $stmh->bindValue(32, $copied_file_name, PDO::PARAM_STR);         	 
     $stmh->bindValue(33, $uploaded_file, PDO::PARAM_STR);         	 
     $stmh->bindValue(34, $datanum, PDO::PARAM_STR);           	 
     $stmh->bindValue(35, $num, PDO::PARAM_STR);           	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   

  }
elseif($modify=='2') // sorting 요청
{	
}
else
{	

	
	 // 데이터 신규 등록하는 구간
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into chandj.bending_write(upnum, outputnum,length1, length2, length3, length4, length5, amount1, amount2 , amount3 , amount4 , amount5,";
     $sql .= " material1, material2, material3, material4, material5, material6, material7, material8, sum1,  sum2, sum3,  sum4, sum5, sum6, sum7, sum8, total_text, steeltype, steel_alias, copied_file_name, uploaded_file, datanum) ";	 
     
     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ";
     $sql .=        " ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "; 
     $sql .=        " ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";   // 33개 + num 1개
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $upnum, PDO::PARAM_STR);  
     $stmh->bindValue(2, $outputnum, PDO::PARAM_STR);  
     $stmh->bindValue(3, $length1, PDO::PARAM_STR);  
     $stmh->bindValue(4, $length2, PDO::PARAM_STR);  
     $stmh->bindValue(5, $length3, PDO::PARAM_STR);  
     $stmh->bindValue(6, $length4, PDO::PARAM_STR);  
     $stmh->bindValue(7, $length5, PDO::PARAM_STR);  
     $stmh->bindValue(8, $amount1, PDO::PARAM_STR);  
     $stmh->bindValue(9, $amount2, PDO::PARAM_STR);  
     $stmh->bindValue(10, $amount3, PDO::PARAM_STR);  
     $stmh->bindValue(11, $amount4, PDO::PARAM_STR);  
     $stmh->bindValue(12, $amount5, PDO::PARAM_STR);  
     $stmh->bindValue(13, $material1, PDO::PARAM_STR);  
     $stmh->bindValue(14, $material2, PDO::PARAM_STR);  
     $stmh->bindValue(15, $material3, PDO::PARAM_STR);  
     $stmh->bindValue(16, $material4, PDO::PARAM_STR);  
     $stmh->bindValue(17, $material5, PDO::PARAM_STR);  
     $stmh->bindValue(18, $material6, PDO::PARAM_STR);  
     $stmh->bindValue(19, $material7, PDO::PARAM_STR);  
     $stmh->bindValue(20, $material8, PDO::PARAM_STR);  
     $stmh->bindValue(21, $sum1, PDO::PARAM_STR);  
     $stmh->bindValue(22, $sum2, PDO::PARAM_STR);  
     $stmh->bindValue(23, $sum3, PDO::PARAM_STR);  
     $stmh->bindValue(24, $sum4, PDO::PARAM_STR);  
     $stmh->bindValue(25, $sum5, PDO::PARAM_STR);  
     $stmh->bindValue(26, $sum6, PDO::PARAM_STR);  
     $stmh->bindValue(27, $sum7, PDO::PARAM_STR);  
     $stmh->bindValue(28, $sum8, PDO::PARAM_STR);  
     $stmh->bindValue(29, $total_text, PDO::PARAM_STR);        
     $stmh->bindValue(30, $steeltype, PDO::PARAM_STR);         	 
     $stmh->bindValue(31, $steel_alias, PDO::PARAM_STR);         	 
     $stmh->bindValue(32, $copied_file_name, PDO::PARAM_STR);         	 
     $stmh->bindValue(33, $uploaded_file, PDO::PARAM_STR);  	 
     $stmh->bindValue(34, $datanum, PDO::PARAM_STR);  	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
	 
}

  if($modify=='update')   $modify="1";
	   header("Location:http://5130.co.kr/analysis/write.php?num=$num&sort=$sort&upnum=$upnum&datanum=$datanum&outputnum=$outputnum&parentnum=$parentnum&ordercompany=$ordercompany&modify=$modify");    // 신규가입일때는 리스트로 이동 
	   
	   
	   ?>