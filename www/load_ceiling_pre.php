<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

$sum_ceiling = array();
 
$yesterday = date("Y-m-d", strtotime("-1 day"));

// 접수일자로 접수수량 체크  
$a = "  where orderday='$yesterday' order by num desc ";    
$sql = "select * from mirae8440.ceiling " . $a; 					
	   
try {  
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
    $temp1 = $stmh->rowCount();    
    $total_row = $temp1;	  
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}  	  
	  
$ceiling_registedate = $total_row;	  

// 출고일자로 접수수량 체크  
$a = "  where deadline='$yesterday' order by num desc ";    
$sql = "select * from mirae8440.ceiling " . $a; 					
	   
try {  
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
    $temp1 = $stmh->rowCount();    
    $total_row = $temp1;	  
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}  	  
	  
$ceiling_duedate = $total_row;	  
  
// 출고완료 수량 체크  
$a = "  where workday='$yesterday' order by num desc ";    
$sql = "select * from mirae8440.ceiling " . $a; 					
	   
try {  
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
    $temp1 = $stmh->rowCount();    
    $total_row = $temp1;	  
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}  	  
	  
$ceiling_outputdonedate = $total_row;	  

// 최종 수량 체크  
$a = "  where deadline='$yesterday' order by num desc ";  
$sql = "select * from mirae8440.ceiling " . $a; 					
	
try {  
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
    $temp1 = $stmh->rowCount();
    $total_row = $temp1;
    $sum = array(0, 0, 0, 0);  // Initialize sum array with default values
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include './ceiling/_rowDB.php';			 
		  
        $sum1[$counter] += (int)$su;
        $sum2[$counter] += (int)$bon_su;
        $sum3[$counter] += (int)$lc_su;
        $sum4[$counter] += (int)$etc_su;

        $sum[0] += (int)$su;
        $sum[1] += (int)$bon_su;
        $sum[2] += (int)$lc_su;
        $sum[3] += (int)$etc_su;			  			  
		  
        $preceilinglist = " 천장 : " . $sum[0] . ",  본천장 : " . $sum[1] . " ,  L/C : "  . $sum[2] . "  , 기타 : "  . $sum[3]; 			   			  			  
    } 
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}  

// 천장 전일 내역

$a = "  where orderday='$yesterday' order by num desc ";    
$sql = "select * from mirae8440.ceiling " . $a; 					
	 
try {  
    $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
    $temp1 = $stmh->rowCount();
    $total_row = $temp1;
    $sum = array(0, 0, 0, 0);  // Initialize sum array with default values
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include './ceiling/_rowDB.php';			 
		  
        $sum1[$counter] += (int)$su;
        $sum2[$counter] += (int)$bon_su;
        $sum3[$counter] += (int)$lc_su;
        $sum4[$counter] += (int)$etc_su;

        $sum[0] += (int)$su;
        $sum[1] += (int)$bon_su;
        $sum[2] += (int)$lc_su;
        $sum[3] += (int)$etc_su;			  			  
		  
        $beforedayceilinglist = " 천장 : " . $sum[0] . ",  본천장 : " . $sum[1] . " ,  L/C : "  . $sum[2] . "  , 기타 : "  . $sum[3]; 			   			  			  
    } 
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}  
  
?>
