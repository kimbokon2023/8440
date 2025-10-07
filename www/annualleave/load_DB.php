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
$basic_id_arr = array();
$basic_part_arr = array();
$referencedate_arr = array();
$availableday_arr = array();
$comment_arr = array();
$dateofentry_arr = array();

// 자료읽기
$sql="select * from " . $DB . ".almember " ;

 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	          array_push($basic_num_arr,$row["num"]);	
	          array_push($basic_id_arr, $row["id"]);	
	          array_push($basic_name_arr, $row["name"]);	
	          array_push($basic_part_arr, $row["part"]);	
	          array_push($referencedate_arr, $row["referencedate"]);
	          array_push($availableday_arr, intval($row["availableday"]));   
	          array_push($comment_arr, $row["comment"]);   

			// $dateofentry_arr를 연관 배열로 설정
			$dateofentry_arr[$row["name"]] = $row["dateofentry"];			  
		   
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

// echo '<pre>';
// print_r($availableday_arr);	
// echo '</pre>';	
	
$today=date("Y-m-d");   // 현재일자 변수지정   
$sql = "select * from " . $DB . ".eworks  where (is_deleted IS NULL or is_deleted = '0')  AND eworks_item='연차' ";

$num_arr = array();
$e_viewexcept_id_arr = array();
$eworks_item_arr = array();
$e_title_arr = array();
$contents_arr = array();
$registdate_arr = array();
$status_arr = array();
$e_line_arr = array();
$e_line_id_arr = array();
$e_confirm_arr = array();
$e_confirm_id_arr = array();
$r_line_arr = array();
$r_line_id_arr = array();
$recordtime_arr = array();
$author_arr = array();
$author_id_arr = array();
$done_arr = array();
$al_askdatefrom_arr = array();
$al_askdateto_arr = array();
$al_item_arr = array();
$al_part_arr = array();
$al_usedday_arr = array();
$al_content_arr = array();

try {
    $stmh = $pdo->query($sql);
    while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($num_arr, $row["num"]);
        array_push($e_viewexcept_id_arr, $row["e_viewexcept_id"]);
        array_push($eworks_item_arr, $row["eworks_item"]);
        array_push($e_title_arr, $row["e_title"]);
        array_push($contents_arr, $row["contents"]);
        array_push($registdate_arr, $row["registdate"]);
        array_push($status_arr, $row["status"]);
        array_push($e_line_arr, $row["e_line"]);
        array_push($e_line_id_arr, $row["e_line_id"]);
        array_push($e_confirm_arr, $row["e_confirm"]);
        array_push($e_confirm_id_arr, $row["e_confirm_id"]);
        array_push($r_line_arr, $row["r_line"]);
        array_push($r_line_id_arr, $row["r_line_id"]);
        array_push($recordtime_arr, $row["recordtime"]);
        array_push($author_arr, $row["author"]);
        array_push($author_id_arr, $row["author_id"]);
        array_push($done_arr, $row["done"]);        
        array_push($al_askdatefrom_arr, $row["al_askdatefrom"]);
        array_push($al_askdateto_arr, $row["al_askdateto"]);
        array_push($al_item_arr, $row["al_item"]);
        array_push($al_part_arr, $row["al_part"]);
        array_push($al_usedday_arr, $row["al_usedday"]);
        array_push($al_content_arr, $row["al_content"]);        
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}


$totalname_arr = array();
$totalused_arr = array();
$totalusedYear_arr = array();

// 전 직원 배열로 계산 후 사용일수 남은일수 값 넣기 
for($j = 0; $j < count($basic_name_arr); $j++) {
    array_push($totalname_arr, $basic_name_arr[$j]); 
        
    // 사용일 계산 - 처리완료일때 가산됨
    $totalused_arr[$j] = 0;
    for($i = 0; $i < count($num_arr); $i++) {     
        if(trim($basic_name_arr[$j]) == trim($author_arr[$i]) && (substr(trim($al_askdatefrom_arr[$i]), 0, 4) == trim($referencedate_arr[$j])) && trim($status_arr[$i]) == 'end') {                
            $totalused_arr[$j] += (float)$al_usedday_arr[$i];                
            $totalusedYear_arr[$j] = $referencedate_arr[$j];               
        }
    }
}

$total_occur = 0; 
// 금년도 개별 일수 산출 
for($i = 0; $i < count($availableday_arr); $i++) {
    if(trim($user_name) == trim($basic_name_arr[$i]) && (trim($referencedate_arr[$i]) == date("Y"))) {
        $total_occur = $availableday_arr[$i];        
    }
}

// 금년도 사용일 계산 - 처리완료일때 가산됨
$thisyeartotalusedday = 0;    
for($i = 0; $i < count($al_usedday_arr); $i++) {
    if(trim($user_name) == trim($author_arr[$i]) && substr(trim($al_askdatefrom_arr[$i]), 0, 4) == date("Y") && trim($status_arr[$i]) == 'end') {
        $thisyeartotalusedday += $al_usedday_arr[$i];        
    }
}

// 금년도 잔여일 산출
$totalremainday = $total_occur - $totalusedday;
$thisyeartotalremainday = $total_occur - $thisyeartotalusedday;

// eworks에 해당되는 직원이름 선택기능
$employee_name_arr = array();
$employee_id_arr = array();
$employee_part_arr = array();

// 자료읽기
$sql = "SELECT * FROM mirae8440.member where position!='퇴사' ";
try {  
    $stmh = $pdo->query($sql);            
    while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {        
        if((int)$row["eworks_level"] > 0 && $row["name"] != '소현철') { // 사장님 제외
            array_push($employee_name_arr, $row["name"]);    
            array_push($employee_id_arr, $row["id"]);    
            array_push($employee_part_arr, $row["part"]);    
        }
    }         
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}


// echo '<pre>';
// print_r($thisyeartotalusedday);	
// echo '</pre>';	

?>
