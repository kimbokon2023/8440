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
		 $str ='';
		 $str .= addspace($itemlist, 600) ;
		 $str .= addspace($row["send_company"], 250) ;
		 $str .= addspace($row["send_date"], 30) ;
		 $str .= addspace($row["send_deadline"], 30) ;	 		 
		 $sub_data["text"] = $str;			 
		 
	 }
		else
		{
			// 부모노트 0인 경우 실제 화면에 보이는 내용 text
		 $str ='';
		  //  $result = addspace($row["workplacename"], 630) ;
		 $str .= addspace($row["workplacename"], 450- (int)strlen($row["workplacename"]) * 4 )   ;
		 $str .= addspace($row["secondord"], 290) ;
		 $str .= addspace($row["regist_day"], 30) ;
		 $str .= addspace($row["doneday"], 30) ;	 		 
		 $sub_data["text"] = $str;			
		}
	$sub_data["parent_id"] = $row["parent_id"];
	$data[] = $sub_data;
}

foreach($data as $key => &$value)
{
	$output[$value["id"]] = &$value;
}

foreach($data as $key => &$value)
{
	 if($value["parent_id"] && isset($output[$value["parent_id"]]))
	 {
		$output[$value["parent_id"]]["nodes"][] = &$value;
		unset($data[$key]);		 // 정령한 데이터 제거함 unset 배열제거 역할
	 }
}

// 키 배열을 정렬하고 반드시 소트를 해야 에러가 나지 않음 주의요함 . nodeID 에러 발생하는거 sort로 하니까 사라짐 2시간동안 찾음
sort($data);
    
  
// echo '<pre>';

// // 상위 부모아이디는 0번으로 등록시 나타나도록 수정함.
// foreach($output as $key => &$value)
// {
 // // if($value["parent_id"] && isset($output[$value["parent_id"]]) && $output[$value["parent_id"]] !== null )
 // // if($value["parent_id"] !== 0 )
 // // {
    // // unset($data[$key]);
 // // }
 
 // print_r($value["parent_id"]);
 
// }

// echo '<br>';

// print_r($data);
// echo '</pre>'; 

echo json_encode($data);

?>