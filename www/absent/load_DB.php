<?php

if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
 $tablename = "eworks";

$basic_num_arr = array();
$basic_name_arr = array();
$basic_part_arr = array();
$referencedate_arr = array();
$availableday_arr = array();

// 자료읽기 eworks_level로 퇴직자 구분
$sql="select * from mirae8440.member where part='제조파트' and eworks_level > 0 AND position !='퇴사' order by numorder asc " ;
 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	          array_push($basic_num_arr,$row["num"]);	
	          array_push($basic_name_arr, $row["name"]);	
	          array_push($basic_part_arr, $row["part"]);			   
	          array_push($availableday_arr, "");			   
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
    
// 자료읽기
$sql="select * from mirae8440.almember " ;
 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	     for($i=0; $i < count($basic_name_arr); $i++ )
		    { 
		    // print_r($basic_name_arr[$i]) ;
		    //  print_r( $row["availableday"]) ;
	          if(trim($row["name"]) == trim($basic_name_arr[$i]) &&  trim($row["referencedate"]) == date("Y",time()))
			  {
			     $availableday_arr[$i] = $row["availableday"];			   
			    }
			  }
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
    
	
		
$today=date("Y-m-d");   // 현재일자 변수지정   
$sql = "select * from mirae8440.absent ";

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
	       array_push($content_arr,$row["content"]);	    
	       array_push($state_arr,$row["state"]);	    		 		
  		 		
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
}	

$totalname_arr = array();
$totalused_arr = array();
$totalusedYear_arr = array();
$remainedAL = array();

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
	 if(trim($user_name)== trim($name_arr[$i]) && substr(trim($askdatefrom_arr[$i]),0,4) == trim(date("Y"))&& trim($state_arr[$i]) == '결재완료')
		$thisyeartotalusedday += $usedday_arr[$i];		
	
// 잔여일 산출
$totalremainday = $total - $totalusedday;
$thisyeartotalremainday = $total - $thisyeartotalusedday;

// 연차 사용일수 산출
	
$today=date("Y-m-d");   // 현재일자 변수지정   

$sql = "select * from mirae8440.eworks where is_deleted IS NULL and eworks_item='연차' ";

// $sql = "select * from mirae8440.al ";// 

$al_name_arr = array();
$al_askdatefrom_arr = array();
$al_askdateto_arr = array();
$al_usedday_arr = array();

$usedAL = array();
$remainedAL = array();

 try{  
 
   $stmh = $pdo->query($sql);      // 검색조건에 맞는글 stmh   

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	       
	       array_push($al_name_arr,$row["author"]);	    
	       array_push($al_askdatefrom_arr,$row["al_askdatefrom"]);	    
	       array_push($al_askdateto_arr,$row["al_askdateto"]);	    
	       array_push($al_usedday_arr,$row["al_usedday"]);	    
	       	$usedAL[] = 0.0;
			$remainedAL[] = 0.0;	
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
	}	

// 전 직원 배열로 계산 후 사용일수 남은일수 값 넣기 
for ($j = 0; $j < count($basic_name_arr); $j++) {
    // 초기화
    $usedAL[$j] = 0;
    
    // 사용일 계산 처리완료일때 가산됨
    for ($i = 0; $i < count($al_name_arr); $i++) {
        $askDateYear = substr($al_askdateto_arr[$i], 0, 4);
        $currentYear = date("Y", time());

        if (trim($basic_name_arr[$j]) === trim($al_name_arr[$i]) && $askDateYear == $currentYear) {
            // 사용일 계산 처리
            $usedAL[$j] += (float)$al_usedday_arr[$i];
        }
    }

    // 남은 일수 계산
    if (is_numeric($availableday_arr[$j]) && is_numeric($usedAL[$j])) {
        $remainedAL[$j] = $availableday_arr[$j] - $usedAL[$j];
    } else {
        // 값이 숫자가 아닐 경우 오류 처리
        $remainedAL[$j] = "자료무";
    }
}


// var_dump($basic_name_arr);
// var_dump($availableday_arr);
// var_dump($usedAL);
// var_dump($remainedAL);

 ?>