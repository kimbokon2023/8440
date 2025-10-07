<?php
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["fromdate"])  ? $fromdate=$_REQUEST["fromdate"] :   $fromdate=''; 
isset($_REQUEST["todate"])  ? $todate=$_REQUEST["todate"] :   $todate=''; 
isset($_REQUEST["weekend"])  ? $weekend=$_REQUEST["weekend"] :   $weekend=''; 

function conv_num($num) {                        // ,콤마를 없애주는 함수
$number = (int)str_replace(',', '', $num);
return $number;
}

require_once("../lib/mydb.php");
$pdo = db_connect();	

$now = date("Y-m-d");	     // 현재 날짜와 크거나 같으면 출고예정으로 구분
$nowtime = date("H:i:s");	 // 현재시간	

$where = " ";    // 조건절 조합

 // 기간을 정하는 구간

$fromdatetmp = date('Y-m-d',strtotime($fromdate."0 day"));  // 납기일 기준

$common=" where deadline>=date('$fromdatetmp') order by deadline ";  // 출고예정일이 현재일보다 클때 조건

$sql = "select * from mirae8440.ceiling " . $common; 				

$date_arr= array();
$tmp = array();
array_push($tmp, $sql);  

try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

            $num=$row["num"];
			  
			$secondord=$row["secondord"];
		    $deadline = $row["deadline"];	
		    $workplacename = $row["workplacename"];	  
			  $bon_su=$row["bon_su"];			  
			  $lc_su=$row["lc_su"];			  
			  $etc_su=$row["etc_su"];			  
			
			if($bon_su!="") 
			    $secondord .= ", 본 " . $bon_su ;			
			if($lc_su!="") 
			    $secondord .= ", LC " . $lc_su ;			
			if($etc_su!="") 
			    $secondord .= ", 기타 " . $etc_su ;

            if($fromdatetmp==$deadline) 
			    {
					
				  array_push($date_arr, $secondord, $workplacename);     
				}
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

//각각의 정보를 하나의 배열 변수에 넣어준다.

$data = array(
	"weekend"=> $weekend,
	"date_arr" =>      $date_arr,
);
   
//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>