 <?php session_start(); ?>
  
 <meta charset="utf-8">
 <script src="http://5130.co.kr/analysis/make.js"></script>
 <?php
 
 $num=$_REQUEST["num"];  // 번호만 불러오자
 $sort=$_REQUEST["sort"];  //
 $recallcal=$_REQUEST["recallcal"];  //
 $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호
 $ordercompany=$_REQUEST["ordercompany"]; 	     // 발주처   
 $datanum=$_REQUEST["datanum"];  //절곡데이터 번호 기억

 require_once("../lib/mydb.php"); 
  $pdo = db_connect();
  $sql="select * from chandj.bending_write  where num='$num'";	 // 처음 오름차순

	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
              $num=$row["num"];

			 $parentnum=$row["parentnum"]; 	     // 리스트번호  
			 $upnum=$row["upnum"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
			 
			 $steeltype=$row["steeltype"]; 
			 $steel_alias=$row["steel_alias"]; 
			 $copied_file_name=$row["copied_file_name"]; 
			 $uploaded_file=$row["uploaded_file"]; 			 
			 
			 $outputnum=$row["outputnum"]; 
			 $length1=$row["length1"]; 
			 $length2=$row["length2"]; 
			 $length3=$row["length3"]; 
			 $length4=$row["length4"]; 
			 $length5=$row["length5"]; 
			 $amount1=$row["amount1"]; 
			 $amount2=$row["amount2"]; 
			 $amount3=$row["amount3"]; 
			 $amount4=$row["amount4"]; 
			 $amount5=$row["amount5"]; 
			 $material1=$row["material1"]; 
			 $material2=$row["material2"]; 
			 $material3=$row["material3"]; 
			 $material4=$row["material4"]; 
			 $material5=$row["material5"]; 
			 $material6=$row["material6"]; 
			 $material7=$row["material7"]; 
			 $material8=$row["material8"]; 
			 $sum1=$row["sum1"]; 
			 $sum2=$row["sum2"]; 
			 $sum3=$row["sum3"]; 
			 $sum4=$row["sum4"]; 
			 $sum5=$row["sum5"]; 
			 $sum6=$row["sum6"]; 
			 $sum7=$row["sum7"]; 
			 $sum8=$row["sum8"]; 
			 $total_text=$row["total_text"];  			 
			 $datanum=$row["datanum"];  			 
			 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   	 
	 $tmp = "Location:http://5130.co.kr/analysis/write.php?upnum=$upnum&num=$num&outputnum=$outputnum&ordercompany=$ordercompany&parentnum=$parentnum&modify=update";
	 $tmp .="&steeltype=$steeltype&steel_alias=$steel_alias&copied_file_name=$copied_file_name&uploaded_file=$uploaded_file&length1=$length1&length2=$length2&length3=$length3&length4=$length4&length5=$length5";
	 $tmp .="&amount1=$amount1&amount2=$amount2&amount3=$amount3&amount4=$amount4&amount5=$amount5";
	 $tmp .="&material1=$material1&material2=$material2&material3=$material3&material4=$material4&material5=$material5&material6=$material6&material7=$material7&material8=$material8";
	 $tmp .="&sum1=$sum1&sum2=$sum2&sum3=$sum3&sum4=$sum4&sum5=$sum5&sum6=$sum6&sum7=$sum7&sum8=$sum8&total_text=$total_text&datanum=$datanum";
	 
	 header($tmp);   //
 ?>
