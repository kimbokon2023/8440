<?php
/**
 * 회원 정보 등록/수정/삭제 처리 (AJAX)
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
session_start();

header("Content-Type: application/json; charset=utf-8");

// 모드 변수 초기화
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';

// _request.php에서 변수 가져오기
include '_request.php';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 응답 데이터 초기화
$response = [
    'status' => 'error',
    'message' => '',
    'id' => $id ?? ''
];

// 변수 존재 여부 확인 (보안)
$requiredVars = ['id', 'name', 'pass', 'level', 'part', 'hp', 'numorder', 'eworks_level', 'position', 'division'];
foreach ($requiredVars as $var) {
    if (!isset($$var)) {
        $$var = '';
    }
}

// 수정 모드
if ($mode == "modify") {
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE mirae8440.member 
                SET name = ?, pass = ?, level = ?, part = ?, hp = ?, 
                    numorder = ?, eworks_level = ?, position = ?, division = ? 
                WHERE id = ? LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $name, PDO::PARAM_STR);
        $stmh->bindValue(2, $pass, PDO::PARAM_STR);
        $stmh->bindValue(3, $level, PDO::PARAM_STR);
        $stmh->bindValue(4, $part, PDO::PARAM_STR);
        $stmh->bindValue(5, $hp, PDO::PARAM_STR);
        $stmh->bindValue(6, $numorder, PDO::PARAM_STR);
        $stmh->bindValue(7, $eworks_level, PDO::PARAM_STR);
        $stmh->bindValue(8, $position, PDO::PARAM_STR);
        $stmh->bindValue(9, $division, PDO::PARAM_STR);
        $stmh->bindValue(10, $id, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
        
        $response['status'] = 'success';
        $response['message'] = '회원 정보가 수정되었습니다.';
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("회원 정보 수정 오류 (id: {$id}): " . $ex->getMessage());
        $response['message'] = '회원 정보 수정 중 오류가 발생했습니다.';
        $response['error'] = $ex->getMessage();
    }
}

// 등록 모드
if ($mode == "insert") {
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO mirae8440.member(name, pass, level, part, hp, numorder, eworks_level, position, division, id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $name, PDO::PARAM_STR);
        $stmh->bindValue(2, $pass, PDO::PARAM_STR);
        $stmh->bindValue(3, $level, PDO::PARAM_STR);
        $stmh->bindValue(4, $part, PDO::PARAM_STR);
        $stmh->bindValue(5, $hp, PDO::PARAM_STR);
        $stmh->bindValue(6, $numorder, PDO::PARAM_STR);
        $stmh->bindValue(7, $eworks_level, PDO::PARAM_STR);
        $stmh->bindValue(8, $position, PDO::PARAM_STR);
        $stmh->bindValue(9, $division, PDO::PARAM_STR);
        $stmh->bindValue(10, $id, PDO::PARAM_STR);
        
        $stmh->execute();
        $pdo->commit();
        
        $response['status'] = 'success';
        $response['message'] = '회원이 등록되었습니다.';
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("회원 등록 오류 (id: {$id}): " . $ex->getMessage());
        $response['message'] = '회원 등록 중 오류가 발생했습니다.';
        $response['error'] = $ex->getMessage();
    }
}

// 삭제 모드
if ($mode == "delete") {
    try {
        $pdo->beginTransaction();
        
        $sql = "DELETE FROM mirae8440.member WHERE id = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
        
        $response['status'] = 'success';
        $response['message'] = '회원이 삭제되었습니다.';
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("회원 삭제 오류 (id: {$id}): " . $ex->getMessage());
        $response['message'] = '회원 삭제 중 오류가 발생했습니다.';
        $response['error'] = $ex->getMessage();
    }
}

// JSON 응답 출력
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
