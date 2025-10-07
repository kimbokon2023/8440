<?php
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

isset($_REQUEST["id"])  ? $id=$_REQUEST["id"] :   $id=''; 
isset($_REQUEST["tablename"])  ? $tablename=$_REQUEST["tablename"] :  $tablename=''; 
isset($_REQUEST["imagecolumn"])  ? $imagecolumn=$_REQUEST["imagecolumn"] :   $imagecolumn=''; 

require_once("../lib/mydb.php");
$pdo = db_connect();	

$now = date("Y-m-d");	     // 현재 날짜와 크거나 같으면 출고예정으로 구분
$nowtime = date("H:i:s");	 // 현재시간	

$sql=" select * from mirae8440.woosungpic where tablename ='$tablename' and item ='$imagecolumn' and parentid ='$id' ";	

$recid=0; 
$id_arr=array(); 
$parentid_arr=array(); 
$img_arr=array(); 

 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   
   $i= 0;    
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

            $id_arr[$i]        =$row["id"];			
			$parentid_arr[$i] = $row["parentid"];
			$img_arr[$i]       = $row["picname"];
			
			$i++;
        }		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

$recid = $i;

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
	"recid"=>           $recid,
	"id_arr" =>         $id_arr,
	"parentid_arr" =>   $parentid_arr,
	"img_arr" =>         $img_arr,

);   

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>