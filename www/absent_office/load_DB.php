<?php

$basic_num_arr = array();
$basic_name_arr = array();
$basic_part_arr = array();
$referencedate_arr = array();
$availableday_arr = array();

// 대상 직원 자료 추출
$sql="select * from mirae8440.member where part='지원파트' and position != '퇴사' order by numorder asc " ;

 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	      if($row["name"] !== '소현철')
			  {
				  array_push($basic_num_arr,$row["num"]);	
				  array_push($basic_name_arr, $row["name"]);	
				  array_push($basic_part_arr, $row["part"]);	
				  array_push($referencedate_arr, $row["referencedate"]);
				  array_push($availableday_arr, $row["availableday"]);   
			  }		   
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
    
// var_dump($year_arr);	
// var_dump($basic_name_arr);	
	
$today=date("Y-m-d");   // 현재일자 변수지정   

$sql = "select * from mirae8440.absent_office ";

$num_arr = array(); 
$id_arr = array();
$name_arr = array();
$part_arr = array();
$registdate_arr = array();
$item_arr = array();
$askdatefrom_arr = array();
$askdateto_arr = array();
$usedday_arr = array();
$content_arr = array();
$state_arr = array();

 try{   
 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	       array_push($num_arr,$row["num"]);	    
	       array_push($id_arr,$row["id"]);	    
	       array_push($name_arr,$row["name"]);	    
	       array_push($part_arr,$row["part"]);	    
	       array_push($registdate_arr,$row["registdate"]);	    
	       array_push($item_arr,$row["item"]);	    
	       array_push($askdatefrom_arr,$row["askdatefrom"]);	    
	       array_push($askdateto_arr,$row["askdateto"]);	    
	       array_push($usedday_arr,$row["usedday"]);	    
	       array_push($content_arr,$row["content"]);	    
	       array_push($state_arr,$row["state"]);	    		 		
  		 		
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
}	

$totalname_arr = array();
$totalused_arr = array();
$totalusedYear_arr = array();

// 전 직원 배열로 계산 후 사용일수 남은일수 값 넣기 
for($j=0;$j<count($basic_name_arr);$j++)  
{
	array_push($totalname_arr ,$basic_name_arr[$j]) ; 
		
	// 사용일 계산	 처리완료일때 가산됨
	$totalused_arr[$j] = 0;
	 for($i=0;$i<count($num_arr);$i++) {	 
			 if(trim($basic_name_arr[$j]) == trim($name_arr[$i]) && (substr(trim($askdatefrom_arr[$i]),0,4) == trim($referencedate_arr[$j])) && trim($state_arr[$i]) == '처리완료')
			 {				
				$totalused_arr[$j] += (float)$usedday_arr[$i];				
				$totalusedYear_arr[$j] = $referencedate_arr[$j];               
			 }
	   }  
} 
 
$total = 0; 
// 금년도 개별 일수 산출 
for($i=0;$i<count($availableday_arr);$i++) 
	 if(trim($user_name)== trim($basic_name_arr[$i]) && (trim($referencedate_arr[$i])==date("Y")))
		$total = $availableday_arr[$i];		
	
// 사용일 계산 처리완료일때 가산됨
// 금년도 년차수량계산함	
$thisyeartotalusedday = 0;	
 for($i=0;$i<count($usedday_arr);$i++) 
	 if(trim($user_name)== trim($name_arr[$i]) && substr(trim($askdatefrom_arr[$i]),0,4) == trim(date("Y"))&& trim($state_arr[$i]) == '처리완료')
		$thisyeartotalusedday += $usedday_arr[$i];		
	
// 잔여일 산출
$totalremainday = $total - $totalusedday;
$thisyeartotalremainday = $total - $thisyeartotalusedday;

// var_dump($totalremainday);
// var_dump($thisyeartotalremainday);

 ?>