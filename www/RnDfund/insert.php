<?php
/**
 * RnDfund 게시글 삽입/수정 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 변수 초기화
$which = $_REQUEST["which"] ?? '';
$search_opt = $_REQUEST["search_opt"] ?? '';
$displaySelect = $_REQUEST["displaySelect"] ?? '';
$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$mode = $_REQUEST["mode"] ?? '';

include '_request.php';

     
 if ($mode=="modify"){
      
    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        error_log("게시글 조회 오류: " . $Exception->getMessage());

        echo json_encode(array(
            "success" => false,
            "message" => "게시글 조회 중 오류가 발생했습니다."
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }        

    try {
        $pdo->beginTransaction();
        $sql = "update " . $DB . "." . $tablename . " set proDate = ?, writer = ?, amount = ?, memo = ?, which = ?, item = ?, comment = ?";
        $sql .= " where num = ? LIMIT 1";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $proDate, PDO::PARAM_STR);
        $stmh->bindValue(2, $writer, PDO::PARAM_STR);
        $stmh->bindValue(3, $amount, PDO::PARAM_STR);
        $stmh->bindValue(4, $memo, PDO::PARAM_STR);
        $stmh->bindValue(5, $which, PDO::PARAM_STR);
        $stmh->bindValue(6, $item, PDO::PARAM_STR);
        $stmh->bindValue(7, $comment, PDO::PARAM_STR);
        $stmh->bindValue(8, $num, PDO::PARAM_INT);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("게시글 수정 오류: " . $Exception->getMessage());

        echo json_encode(array(
            "success" => false,
            "message" => "게시글 수정 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }                         
       
 } else	{
	 		 
    try {
        $pdo->beginTransaction();

        $sql = "insert into " . $DB . "." . $tablename . " (proDate, writer, amount, memo, which, item, comment)";
        $sql .= " values(?, ?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $proDate, PDO::PARAM_STR);
        $stmh->bindValue(2, $writer, PDO::PARAM_STR);
        $stmh->bindValue(3, $amount, PDO::PARAM_STR);
        $stmh->bindValue(4, $memo, PDO::PARAM_STR);
        $stmh->bindValue(5, $which, PDO::PARAM_STR);
        $stmh->bindValue(6, $item, PDO::PARAM_STR);
        $stmh->bindValue(7, $comment, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        error_log("게시글 등록 오류: " . $Exception->getMessage());

        echo json_encode(array(
            "success" => false,
            "message" => "게시글 등록 중 오류가 발생했습니다.",
            "error" => $Exception->getMessage()
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }   
	 
    // 신규 등록인 경우 마지막 번호 추출
    $sql = "select * from " . $DB . "." . $tablename . " order by num desc limit 1";

    try {
        $stmh = $pdo->query($sql);
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"] ?? '';
    } catch (PDOException $Exception) {
        error_log("마지막 번호 조회 오류: " . $Exception->getMessage());
    }    
                     

   }
  

// 성공 응답
$data = array(
    'success' => true,
    'num' => $num,
    'message' => ($mode == "modify") ? "게시글이 수정되었습니다." : "게시글이 등록되었습니다."
);

echo json_encode($data, JSON_UNESCAPED_UNICODE);

 ?>