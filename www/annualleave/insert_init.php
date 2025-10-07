<?php
session_start();
header("Content-Type: application/json"); // JSON 응답을 위해 설정

require_once("../lib/mydb.php");
$pdo = db_connect();

try {
    // POST 데이터에서 author_list와 기타 정보를 가져옴
    $authorList = isset($_POST['author_list']) ? json_decode($_POST['author_list'], true) : [];    
    $currentYear = $_POST['current_year'] ?? "";
    $availableday = $_POST['availableday'] ?? 0;

    if (empty($authorList)) {
        echo json_encode(["status" => "error", "message" => "등록할 직원 정보가 없습니다."]);
        exit;
    }

    $pdo->beginTransaction();

    $sql = "INSERT INTO mirae8440.almember (name, part, dateofentry, referencedate, availableday) 
            VALUES (?, ?, ?, ?, ?)";
    $stmh = $pdo->prepare($sql);

    foreach ($authorList as $author) {
        $name = $author['name'] ?? "";
        $part = $author['part'] ?? "";      
		$dateofentry = $author['dateofentry'] ?? null;   

        // 각 직원의 데이터를 삽입
        $stmh->bindValue(1, $name, PDO::PARAM_STR);
        $stmh->bindValue(2, $part, PDO::PARAM_STR);
        $stmh->bindValue(3, $dateofentry, PDO::PARAM_STR); // 등록 날짜
        $stmh->bindValue(4, $currentYear, PDO::PARAM_STR); // 해당 연도
        $stmh->bindValue(5, $availableday, PDO::PARAM_INT); // 연차 발생일수        
        $stmh->execute();
    }

    $pdo->commit();

    echo json_encode(["status" => "success", "message" => "대량 등록이 성공적으로 완료되었습니다."]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(["status" => "error", "message" => "DB 오류: " . $e->getMessage()]);
    exit;
}
?>
