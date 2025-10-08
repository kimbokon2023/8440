<?php
require_once __DIR__ . '/../bootstrap.php';
if (!isset($pdo) || !$pdo) {
    require_once includePath('lib/mydb.php');
    $pdo = db_connect();
}

// 불량에 대한 정보를

$num_arr = array();
$outdate_arr = array();
$workplace_arr = array();
$bad_choice_arr = array();

// date("Y-m-d H:i:s", strtotime("-1 week")); // 일주일 전
// $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME']-86400);

$yesterday = date("Y-m-d H:i:s", strtotime("-1 week"));
$now = date("Y-m-d");

$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
// if($week[ date('w', strtotime($yesterday)) ] == '(토)')
// $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME']-172800);
// if($week[ date('w', strtotime($yesterday)) ] == '(일)')
// $yesterday = date('Y-m-d', $_SERVER['REQUEST_TIME']-172800);		

// 자료읽기
$sql="select * from mirae8440.steel where outdate between date('$yesterday')  and  date('$now') order by outdate desc";
 
 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
          $bad_choice=$row["bad_choice"];
		  if(strpos($bad_choice, '불량') !== false) {  		  
	          array_push($num_arr,$row["num"]);	
	          array_push($outdate_arr, $row["outdate"]);				  
	          array_push($workplace_arr, $row["outworkplace"] . ' --> (소요자재) ' . $row["item"]. ' (규격) ' .  $row["spec"] . ' / ' . $row["steelnum"] .  "매");		
	          array_push($bad_choice_arr, $row["bad_choice"]);			          
		        }
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

$bad_number = count($bad_choice_arr);

?>