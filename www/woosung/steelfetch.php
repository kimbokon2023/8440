<?php

// 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'steelini.php';  

$connect = mysqli_connect("localhost", "mirae8440", "dnjstksfl1!!", "mirae8440");

if($search=='') 
{
		$query = "
		 SELECT * FROM " . $tablename ;		
}
 else
	{
		$query = " SELECT * FROM " . $tablename . " where workplacename like '%$search%' or  secondord like '%$search%' 
									or supplier like '%$search%' or  memo like '%$search%' 
									or st_content like '%$search%' or  regist_day like '%$search%' 
									or secondordman like '%$search%' or  address '%$search%' 
									or secondordmantel like '%$search%' 									
		";				
		
	}
	 
$result = mysqli_query($connect, $query);

while($row = mysqli_fetch_array($result))
{
	
$st_content = $row["st_content"];	
	
// grid 분할하는 로직 불러오기 공통사용
include 'load_steel.php';	
	
	$sub_data["id"] = $row["id"];	
	$sub_data["pid"] = 0;	// 부모노드로 정해줌
	
	$sub_data["regist_day"] = $row["regist_day"];
	$sub_data["doneday"] = $row["doneday"];
	$sub_data["workplacename"] = $row["workplacename"];
	$sub_data["demand"] = $row["demand"];
	$sub_data["memo"] = $row["memo"];
	$sub_data["supplier"] = $row["supplier"];
	$sub_data["secondord"] = $row["secondord"];	
	
	$sub_data["steelnum"] = number_format($steelnum_val) ;
	$sub_data["unitprice"] =  number_format($unitprice_val);
	$sub_data["tax"] =  number_format($tax_val);
	$sub_data["amount"] =  number_format($amount_val);
	$sub_data["totalamount"] =  number_format($totalamount_val);
	$sub_data["item"] =  $item_str;
	$sub_data["spec"] =  $spec_str;
	
	$itemlist= str_replace('|', ',', $row["item_name"]);	
	
	$data[] = $sub_data;
}

echo json_encode($data);

?>