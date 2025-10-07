<?php  
session_start(); 
header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문 받는측에서 필요한 정보임 ajax로 보내는 쪽에서 type : json
ini_set('display_errors','1');  // 화면에 warning 없애기	  
  
include 'request.php';
include '_request.php';
   
// 날짜 포맷을 정리하는 함수
function format_date($date) {
    return ($date != "0000-00-00" && $date != "1970-01-01" && $date != "") ? date("Y-m-d", strtotime($date)) : "";
}

// 공통된 날짜 데이터 처리
$orderday = format_date($orderday);
$measureday = format_date($measureday);
$drawday = format_date($drawday);
$deadline = format_date($deadline);
$workday = format_date($workday);
$endworkday = format_date($endworkday);
$demand = format_date($demand);
$startday = format_date($startday);
$testday = format_date($testday);
$doneday = format_date($doneday);
$workfeedate = format_date($workfeedate);
$assigndate = format_date($assigndate);

// 실측일은 도면완료일이 입력되면 자동으로 기록한다.
if ($measureday == "" && $drawday != "") {
    $measureday = $drawday;
}
  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();
    

if ($mode == "modify") {
		
	$update_dir = './update/'; // 업데이트 폴더 위치

	if (!file_exists($update_dir)) {
		mkdir($update_dir, 0777, true); // 업데이트 폴더가 없다면 생성
	}	
	

    try {
        // 레코드 검색 및 추출
        $stmh = $pdo->prepare("SELECT * FROM mirae8440.work WHERE num = ?");
        $stmh->execute([$num]);
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // JSON 형식으로 변환
            $jsonData = json_encode($row);

            // 파일로 저장 (파일 이름은 레코드 ID와 현재 날짜/시간을 사용)
            $backupFileName = $update_dir . "update_" . $num . "_" . date("Y-m-d_H-i-s") . ".json";
            file_put_contents($backupFileName, $jsonData);
        }	
		
		} catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
	
    $data = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . " ";
    $update_log = $data . $update_log . "&#10";

    try {
        $pdo->beginTransaction();

	$columns = [
		'checkstep', 'workplacename', 'address',
		'firstord', 'firstordman', 'firstordmantel', 'secondord', 'secondordman', 'secondordmantel',
		'chargedman', 'chargedmantel',
		'orderday', 'measureday', 'drawday', 'deadline', 'workday', 'worker', 'endworkday',
		'material1', 'material2', 'material3', 'material4', 'material5', 'material6',
		'widejamb', 'normaljamb', 'smalljamb', 'memo',
		'regist_day', 'update_day', 'delivery', 'delicar', 'delicompany', 'delipay', 'delimethod',
		'demand', 'startday', 'testday', 'hpi', 'first_writer', 'update_log', 'memo2', 'doneday',
		'checkhold', 'designer', 'attachment',
		'widejambworkfee', 'normaljambworkfee', 'smalljambworkfee', 'workfeedate',
		'dwglocation', 'work_order', 'checkmat1', 'checkmat2', 'checkmat3', 
		'outsourcing', 'madeconfirm', 'mymemo', 'assigndate', 'gapcover'
	];


        $sql = "UPDATE mirae8440.work SET ";
        $values = [];

        foreach ($columns as $index => $column) {
            $sql .= "$column=?, ";
            $values[] = $$column; // 변수 이름으로 변수 값에 접근
        }

        $sql = rtrim($sql, ", ") . " WHERE num=? LIMIT 1";
        $values[] = $num;

        $stmh = $pdo->prepare($sql);

        foreach ($values as $index => $value) {
            $stmh->bindValue($index + 1, $value);
        }

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

 
else {
    // 데이터 신규 등록하는 구간
    $first_writer = date("Y-m-d H:i:s") . "_" . $_SESSION["name"] . " "; // 최초등록자 기록

    try {
        $pdo->beginTransaction();

        $columns = [
            'checkstep', 'workplacename', 'address',
            'firstord', 'firstordman', 'firstordmantel', 'secondord', 'secondordman', 'secondordmantel',
            'chargedman', 'chargedmantel',
            'orderday', 'measureday', 'drawday', 'deadline', 'workday', 'worker', 'endworkday',
            'material1', 'material2', 'material3', 'material4', 'material5', 'material6',
            'widejamb', 'normaljamb', 'smalljamb', 'memo',
            'regist_day', 'update_day', 'delivery', 'delicar', 'delicompany', 'delipay', 'delimethod',
            'demand', 'startday', 'testday', 'hpi', 'first_writer', 'memo2', 'doneday',
            'checkhold', 'designer', 'attachment',
            'widejambworkfee', 'normaljambworkfee', 'smalljambworkfee', 'workfeedate',
            'dwglocation', 'work_order', 'checkmat1', 'checkmat2', 'checkmat3',
            'outsourcing', 'madeconfirm', 'mymemo', 'assigndate', 'gapcover'
        ];


        // 변수 값 저장
        $values = [];
        foreach ($columns as $column) {
            $values[] = $$column;
        }

        $sql = "INSERT INTO mirae8440.work (" . implode(", ", $columns) . ") VALUES (";
        $sql .= implode(", ", array_fill(0, count($columns), "?")) . ")";

        $stmh = $pdo->prepare($sql);

        foreach ($values as $index => $value) {
            $stmh->bindValue($index + 1, $value);
        }

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}
  
  
  if ($mode!="modify"){ 
 
	 try{
		 $sql = "select * from mirae8440.work order by num desc limit 1";
		 $stmh = $pdo->prepare($sql);  
		 $stmh->execute();                  
		 $row = $stmh->fetch(PDO::FETCH_ASSOC);	 
		 $num=$row["num"];	 
		
		}
	   catch (PDOException $Exception) {
		   print "오류: ".$Exception->getMessage();
	  } 
	  
  }
 
  $data = [   
 'num' => $num
 ]; 
 
 echo json_encode($data, JSON_UNESCAPED_UNICODE);

 ?>
