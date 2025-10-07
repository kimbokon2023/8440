<?php
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["steelitem"])  ? $steelitem=$_REQUEST["steelitem"] :   $steelitem=''; 
isset($_REQUEST["steelspec"])  ? $steelspec=$_REQUEST["steelspec"] :   $steelspec=''; 
// take는 사급자재업체명 기록필드
isset($_REQUEST["steeltake"])  ? $steeltake=$_REQUEST["steeltake"] :   $steeltake=''; 

require_once("../lib/mydb.php");
$pdo = db_connect();

// 기존의 자료에 없으면 추가해주기
// 기존자료 배열저장
   
// 원자재 현재 상태 읽기   
$sql="select * from mirae8440.steelsource order by sortorder asc, item desc "; 					

try{  
   $stmh = $pdo->query($sql);     
   $j=0;
   $item_counter=1;
   $title=array();
   $saved_title=array();  

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	    			  			  
			     array_push($saved_title, trim($row["item"]) . trim($row["spec"]) . $row["take"] );   				 
			  			 			  
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    

$saved_title = array_unique($saved_title);  // 고유한 배열만 정리하는 함수
sort($saved_title);

// 전달된 DATA가 기존의 배열에 없으면 추가하기
// 있다면 추가하기

$checkinsert = true;
for($i=0;$i<count($saved_title);$i++) 
{
// DB에 없으면 맞으면 데이터 추가  
if($saved_title[$i] == trim($steelitem) . trim($steelspec) . trim($steeltake) )
  {
	 $checkinsert = false;
     break; 
   }
}

$sortorder = 9 ;  

// $pos = strpos($steelitem,'304 MR');

if(strpos($steelitem,'304 BA') > 1)
	  $sortorder = 4 ;
if(strpos($steelitem,'304 MR') > 1)
	$sortorder = 4;
if(strpos($steelitem,'304 HL NSP') > 1)
	$sortorder = 4;
if(strpos($steelitem,'304 HL') > 1)
	$sortorder = 4;
if(strpos($steelitem,'EGI') > 1)
	$sortorder = 3;
if(strpos($steelitem,'PO') > 1)
	$sortorder = 2;
if(strpos($steelitem,'CR') > 1)
	$sortorder = 1;


if($checkinsert == true)
// 데이터 추가하는 구간			
  try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.steelsource(sortorder, item, spec, take) ";     
     $sql .= " values(?, ?, ?, ?)";
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $sortorder, PDO::PARAM_STR);  
     $stmh->bindValue(2, $steelitem, PDO::PARAM_STR);  
     $stmh->bindValue(3, $steelspec, PDO::PARAM_STR);  
     $stmh->bindValue(4, $steeltake, PDO::PARAM_STR);  
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }          
 
//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
	"steelitem " =>  $steelitem ,
	"steelspec " =>  $steelspec ,
	"steeltake " =>  $steeltake ,
	"sortorder " =>  $sortorder ,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>

