<?php
require_once __DIR__ . '/../bootstrap.php';

header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors','0');
ini_set('log_errors','1');
  
include 'request.php';
include '_request.php';
   
// 날짜 포맷을 정리하는 함수 (null 안전성 추가)
function format_date($date) {
    if (!$date || $date == "0000-00-00" || $date == "1970-01-01" || $date == "") {
        return "";
    }
    $timestamp = strtotime($date);
    return $timestamp ? date("Y-m-d", $timestamp) : "";
}

// 공통된 날짜 데이터 처리
$orderday = format_date($orderday ?? '');
$measureday = format_date($measureday ?? '');
$drawday = format_date($drawday ?? '');
$deadline = format_date($deadline ?? '');
$workday = format_date($workday ?? '');
$endworkday = format_date($endworkday ?? '');
$demand = format_date($demand ?? '');
$startday = format_date($startday ?? '');
$testday = format_date($testday ?? '');
$doneday = format_date($doneday ?? '');
$workfeedate = format_date($workfeedate ?? '');
$assigndate = format_date($assigndate ?? '');

// 실측일은 도면완료일이 입력되면 자동으로 기록한다.
if ($measureday == "" && $drawday != "") {
    $measureday = $drawday;
}
  
// bootstrap.php에서 이미 DB 연결됨
    

if ($mode == "modify") {
		
	$update_dir = './update/'; // 업데이트 폴더 위치

	if (!file_exists($update_dir)) {
		mkdir($update_dir, 0755, true); // 권한을 0755로 변경 (보안 강화)
	}	
	

    try {
        // 레코드 검색 및 추출
        $stmh = $pdo->prepare("SELECT * FROM mirae8440.work WHERE num = ?");
        $stmh->execute([$num]);
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // JSON 형식으로 변환
            $jsonData = json_encode($row, JSON_UNESCAPED_UNICODE);

            // 파일로 저장 (파일 이름은 레코드 ID와 현재 날짜/시간을 사용)
            $backupFileName = $update_dir . "update_" . $num . "_" . date("Y-m-d_H-i-s") . ".json";
            file_put_contents($backupFileName, $jsonData);
        }	
		
		} catch (PDOException $Exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if (isLocal()) {
                error_log("Backup error in insert.php: " . $Exception->getMessage());
                print json_encode(['success' => false, 'message' => '백업 중 오류가 발생했습니다: ' . $Exception->getMessage()]);
            } else {
                error_log("Backup error in insert.php: " . $Exception->getMessage());
                print json_encode(['success' => false, 'message' => '시스템 오류가 발생했습니다. 관리자에게 문의하세요.']);
            }
            exit;
    }
	
    $data = date("Y-m-d H:i:s") . " - " . ($_SESSION["name"] ?? 'Unknown') . " ";
    $update_log = $data . ($update_log ?? '') . "&#10";

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
        
        // 성공 응답
        print json_encode(['success' => true, 'message' => '데이터가 성공적으로 수정되었습니다.', 'num' => $num], JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (PDOException $Exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        if (isLocal()) {
            error_log("Database error in insert.php (modify): " . $Exception->getMessage());
            print json_encode(['success' => false, 'message' => '데이터베이스 오류: ' . $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
        } else {
            error_log("Database error in insert.php (modify): " . $Exception->getMessage());
            print json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.'], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
}

 
else {
    // 데이터 신규 등록하는 구간
    $first_writer = date("Y-m-d H:i:s") . "_" . ($_SESSION["name"] ?? 'Unknown') . " "; // 최초등록자 기록

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
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        if (isLocal()) {
            error_log("Database error in insert.php (insert): " . $Exception->getMessage());
            print json_encode(['success' => false, 'message' => '데이터베이스 오류: ' . $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
        } else {
            error_log("Database error in insert.php (insert): " . $Exception->getMessage());
            print json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.'], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
}
  
  
  if ($mode!="modify"){ 
 
	 try{
		 $sql = "select * from mirae8440.work order by num desc limit 1";
		 $stmh = $pdo->prepare($sql);  
		 $stmh->execute();                  
		 $row = $stmh->fetch(PDO::FETCH_ASSOC);	 
		 $num = $row["num"] ?? 0;	 
		
		}
	   catch (PDOException $Exception) {
           if (isLocal()) {
               error_log("Database error in insert.php (get num): " . $Exception->getMessage());
               print json_encode(['success' => false, 'message' => '데이터베이스 오류: ' . $Exception->getMessage()], JSON_UNESCAPED_UNICODE);
           } else {
               error_log("Database error in insert.php (get num): " . $Exception->getMessage());
               print json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.'], JSON_UNESCAPED_UNICODE);
           }
           exit;
	  } 
	  
  }
 
  // 성공 응답 (신규 등록의 경우)
  $data = [
      'success' => true,
      'message' => '데이터가 성공적으로 등록되었습니다.',
      'num' => $num
  ]; 

  echo json_encode($data, JSON_UNESCAPED_UNICODE);

 ?>
