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

$fromdatetmp = date('Y-m-d',strtotime($fromdate."-1 day"));

$common=" where endworkday>=date('$fromdatetmp') order by endworkday ";  // 출고예정일이 현재일보다 클때 조건

$sql = "select * from mirae8440.work " . $common; 				

$date_arr= array();
$tmp = array();
array_push($tmp, $sql);  

try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

            $num=$row["num"];
			  
			$worker = $row["worker"];
		    $endworkday = $row["endworkday"];	
		    $workplacename = $row["workplacename"];	
  		    $filename1=$row["filename1"];
			$filename2=$row["filename2"];	
		    $widejamb=$row["widejamb"];
		    $normaljamb=$row["normaljamb"];
		    $smalljamb=$row["smalljamb"];			  
						  
			 $workitem="☞ ";
			 if($widejamb!="")   {
					$workitem .="막판" . $widejamb . " "; 
								}
			 if($normaljamb!="")   {
					$workitem .="막(無)" . $normaljamb . " "; 					
					}
			 if($smalljamb!="") {
					$workitem .="쪽쟘" . $smalljamb . " "; 												   
					}	

            if($filename1!=null)
				$filename1 = ' (전사진';
            if($filename2!=null)
                if($filename1!=null)				
				    $filename2 = ', 후사진';			
			       else
				      $filename2 = ' 후사진';			
           if($filename1==null && $filename2==null )
				$worker = $worker . $filename1 . $filename2  ;
			  else
			    $worker = $worker . $filename1 . $filename2 . ')' ;
			
           // echo $worker . $endworkday ;			

            if($fromdatetmp==$endworkday) 
			    {
					
				  array_push($date_arr, $worker, $workplacename, $workitem);     
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