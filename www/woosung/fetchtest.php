<?php

//fetch.php
// 환경파일 읽어오기 (테이블명 작업 폴더 등)

include 'ini.php';  

function addspace($strtmp, $length){
	// 공백을 삽입하는 함수 제작
	for($k=0;$k<(int)$length;$k++) 
		if(strlen($strtmp) < $k)
			$strtmp .= ' &nbsp; ';	
	return $strtmp;	
	
}

$connect = mysqli_connect("localhost", "mirae8440", "dnjstksfl1!!", "mirae8440");

if($search=='') 
{
		$query = "
		 SELECT * FROM woosung
		";
}
 else
	{
		$query = "
		 SELECT * FROM woosung where workplacename like '%$search%' or  secondord like '%$search%' 
									or send_company like '%$search%' or  firstord like '%$search%' 
									or item_name like '%$search%' or  item_spec like '%$search%' 
									or item_memo like '%$search%' or  item_note like '%$search%' 
		";				
		
	}
	 
$result = mysqli_query($connect, $query);

while($row = mysqli_fetch_array($result))
{
	$sub_data["id"] = $row["id"];
	$sub_data["workplacename"] = $row["workplacename"];
	
	$itemlist= str_replace('|', ',', $row["item_name"]);
	
	if($row["parent_id"] != 0 )   // 자식노드이면 주소를 넣지 않는다.	   
	 {		 
		 $sub_data["name"] =$itemlist ;
		 $sub_data["company"] = $row["send_company"];
		 $sub_data["date1"] = $row["send_date"];
		 $sub_data["date2"] = $row["send_deadline"];		 
		 $sub_data["pid"] = $row["parent_id"];
		 if($search!='')  // 검색에 안걸리는 걸 피하는 방법
		      $sub_data["parent_id"] = 0 ;
		    else
				$sub_data["parent_id"] = $row["parent_id"];			
	 }
		else
		{
			// 부모노트 0인 경우 실제 화면에 보이는 내용 text
		 $sub_data["name"] =$row["workplacename"] ;
		 $sub_data["company"] = $row["secondord"];
		 $sub_data["date1"] = $row["regist_day"];
		 $sub_data["date2"] = $row["doneday"];			
          $sub_data["pid"] = $row["parent_id"];		 
		 $sub_data["parent_id"] = $row["parent_id"];	
		 
		}
	
	$data[] = $sub_data;
}
// 아래 문장은 treeview 할때 필요한 구문
// foreach($data as $key => &$value)
// {
	// $output[$value["id"]] = &$value;
// }

// foreach($data as $key => &$value)
// {
	 // if($value["pid"] && isset($output[$value["pid"]]))
	 // {
		// $output[$value["pid"]]["nodes"][] = &$value;
		// unset($data[$key]);		 // 정령한 데이터 제거함 unset 배열제거 역할
	 // }
// }

// // 키 배열을 정렬하고 반드시 소트를 해야 에러가 나지 않음 주의요함 . nodeID 에러 발생하는거 sort로 하니까 사라짐 2시간동안 찾음
// sort($data);
    
  
// echo '<pre>';

// // 상위 부모아이디는 0번으로 등록시 나타나도록 수정함.
// foreach($output as $key => &$value)
// {
 // // if($value["pid"] && isset($output[$value["pid"]]) && $output[$value["pid"]] !== null )
 // // if($value["pid"] !== 0 )
 // // {
    // // unset($data[$key]);
 // // }
 
 // print_r($value["pid"]);
 
// }

// echo '<br>';

// print_r($data);
// echo '</pre>'; 

// echo '<pre>';
// echo print_r($data);
// echo '</pre>';

echo json_encode($data);

?>