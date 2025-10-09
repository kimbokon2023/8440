<?php

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 데이터 배열 초기화
$data_ceiling = array();

try {
    // ceiling 테이블에서 필요한 컬럼 조회
    $sql = "SELECT workplacename, deadline, secondord, num, bon_su, lc_su, etc_su, 
                   lcassembly_date, mainassembly_date, etcassembly_date, type, workday, 
                   main_draw, lc_draw, etc_draw, cabledone 
            FROM mirae8440.ceiling";
    
    $stmh = $pdo->query($sql);
    
    // 조회 결과를 배열에 추가
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        array_push($data_ceiling, $row);
    }
    
    // JSON 인코딩을 위한 배열 구성
    $data_ceiling = array(
        "data_ceiling" => $data_ceiling,
    );
    
    // JSON 출력 (한글 유지)
    echo json_encode($data_ceiling, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

?>
