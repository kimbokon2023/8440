<?php
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

//isset($_REQUEST["num_arr"])  ? $num_arr=$_REQUEST["num_arr"] :   $num_arr=''; 
isset($_REQUEST["searchtel"])  ? $searchtel=$_REQUEST["searchtel"] :  $searchtel=''; 
// $data = file_get_contents($url); // 파일의 내용을 변수에 넣는다

//print_r($num_arr[0]);
// $num_tmp = explode(",",$num_arr[0]); // 배열인 경우 이렇게 받는다
//print_r(count($tmp));
//print_r($num_tmp);

$num_arr = array();
$orderdate_arr = array();
$orderlist_arr = array();
$state_arr = array();
$deliveryfee_arr = array();
$payment_arr = array();

require_once("../lib/mydb.php");	
$pdo = db_connect();

$sql="select * from mirae8440.shop  where delvalue!=1 order by num desc" ; 						

 try{		 
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh          					 
	  
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			$num=$row["num"];
			$password = $row["password"];			
			$state =$row["state"];
			$orderdate =$row["orderdate"];
			$orderlist = $row["orderlist"];   // orderlist 복호화 중요함 핵심기술
			$name= $row["name"]; 
			$tel = $row["tel"]; 
			$receivename= $row["receivename"]; 
			$receivetel= $row["receivetel"]; 
			$email= $row["email"]; 
			$address= $row["address"]; 
			$address2= $row["address2"]; 
			$request= $row["request"]; 
			$code= $row["code"];
			$deliveryfee= $row["deliveryfee"];  // 3000원으로 배송비 일단 세팅함	 
			$payment= $row["payment"]; 
			
			$tel = preg_replace("/\s+/","",$tel);  // 모든 공백제거			  
			if(strpos($tel,'-') || strpos($tel,'.')) { 
				$str = array('-','.');					
				$tmp = str_replace($str, "", $tel);				}			
			   else
			   {
				   $tmp = $tel;  
			   }
			   
			// 오른쪽 8문자만 추출 전화번호 뒷자리8자리
			if(strlen($tmp)>8)
			   $tmp = substr($tmp, -8);   // 뒷에서 8자리
			
			if($searchtel==$tmp)  // 검색하는 전번과 같으면
			 {
			   array_push($num_arr, $num );
			   array_push($orderdate_arr, $orderdate );
			   array_push($orderlist_arr, $orderlist );
			   array_push($state_arr, $state );
			   array_push($deliveryfee_arr, $deliveryfee );
			   array_push($payment_arr, $payment );
			}
		   }
	   

        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       } 

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num_arr" =>         $num_arr,
		"orderdate_arr" =>   $orderdate_arr,
		"orderlist_arr" =>   $orderlist_arr,
		"state_arr" =>       $state_arr,
		"deliveryfee_arr" => $deliveryfee_arr,
		"payment_arr" => $payment_arr,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>