<?php
require_once __DIR__ . '/../bootstrap.php';

header("Content-Type: application/json");

function pipetocomma($str) {
    return str_replace('|', ',', $str);
}

// $col1, $col2, ..., $col47 초기화
for($i = 1; $i <= 47; $i++) {
    ${"col$i"} = $_REQUEST["col$i"] ?? '';
    // 빈 배열 체크 추가
    if(!empty(${"col$i"}[0])) {
        ${"colarr$i"} = explode(",", ${"col$i"}[0]);
    } else {
        ${"colarr$i"} = [];
    }
}

// 파이프를 콤마로 변환
if(!empty($colarr7)) {
    for($i=0; $i < count($colarr7); $i++) {
        for($j = 1; $j <= 47; $j++) {
            if(isset(${"colarr$j"}[$i])) {
                ${"colarr$j"}[$i] = pipetocomma(${"colarr$j"}[$i]);
            }
        }
    }
}

$orderday = date("Y-m-d"); 
// bootstrap.php에서 이미 DB 연결됨

$innercount = 0;

for($i=0; $i < count($colarr7); $i++) {
    if($colarr1[$i]!='' and $colarr1[$i]!=null) {
        try {
            $secondord = 'TKEK';
            $secondordman = '박일우부장';
            $secondordmantel = '010-4677-8493';
            $workplacename =  $colarr17[$i] . "(" . $colarr14[$i] . ")";
            // $memo = $colarr17[$i] . ', ' . $colarr18[$i] . ', ' . $colarr44[$i]. ', ' . $colarr47[$i];
            $memo = '';
            $first_writer = $_SESSION["name"] . " _" . date("Y-m-d H:i:s");
			$checkstep = '';  // 덧방은 ''
			
            $pdo->beginTransaction();

            $sql = "INSERT INTO mirae8440.work (orderday, secondord, secondordman, secondordmantel, workplacename,  memo, first_writer, checkstep )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $orderday, PDO::PARAM_STR);
            $stmh->bindValue(2, $secondord, PDO::PARAM_STR);
            $stmh->bindValue(3, $secondordman, PDO::PARAM_STR);
            $stmh->bindValue(4, $secondordmantel, PDO::PARAM_STR);            
            $stmh->bindValue(5, $workplacename, PDO::PARAM_STR);            
            $stmh->bindValue(6, $memo, PDO::PARAM_STR);            
            $stmh->bindValue(7, $first_writer, PDO::PARAM_STR);
            $stmh->bindValue(8, $checkstep, PDO::PARAM_STR);
			
            $stmh->execute() or error_log(print_r($stmh->errorInfo(), true));

            $pdo->commit();
			$innercount++;
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            print "오류: " . $Exception->getMessage();
        }
		
	
    }
}

$data = array("colarr7" => $colarr7, "innercount" => $innercount);
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>

