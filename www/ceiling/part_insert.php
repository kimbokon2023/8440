<?php
require_once __DIR__ . '/../common/functions.php';
session_start();

// 세션 변수 초기화
$level = isset($_SESSION["level"]) ? $_SESSION["level"] : 10;
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : "";

// REQUEST 변수 초기화
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "";
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";

// 테이블 설정
$tablename = 'part';
$itemCount = 20;

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();


// 데이터 수집 (modify 또는 insert 모드)
if ($mode == "modify" || $mode == "insert") {
    $fieldarr = array();
    $strarr = array();
    
    // 입력일자 추가
    array_push($fieldarr, 'inputdate');
    array_push($strarr, isset($_REQUEST['inputdate']) ? $_REQUEST['inputdate'] : '');
    
    // 부품 항목 추가 (part1 ~ part20)
    for ($i = 0; $i < $itemCount; $i++) {
        $tmp = "part" . (int)($i + 1);
        array_push($fieldarr, $tmp);
        array_push($strarr, isset($_REQUEST[$tmp]) ? $_REQUEST[$tmp] : '');
    }
    
    // modify 모드인 경우 num 추가
    if ($mode == "modify") {
        array_push($strarr, $num);
    }
}

// 수정 모드 처리
if ($mode == "modify") {
    // 기존 데이터 조회
    try {
        $sql = "select * from mirae8440." . $tablename . " where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
    
    // 데이터 업데이트
    try {
        $pdo->beginTransaction();
        
        // UPDATE SQL 생성
        $sql = "update mirae8440." . $tablename . " set ";
        for ($i = 0; $i < count($fieldarr); $i++) {
            if ($i != 0) {
                $sql .= ' , ';
            }
            $sql .= $fieldarr[$i] . '=? ';
        }
        $sql .= " where num=? LIMIT 1";
        
        // 디버그 출력 (필요시 주석 해제)
        // print $sql;
        
        // SQL 실행
        $stmh = $pdo->prepare($sql);
        for ($i = 0; $i < count($strarr); $i++) {
            $stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_STR);
        }
        
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

// 삽입 모드 처리
if ($mode == "insert") {
    // INSERT SQL 생성 - 필드명 부분
    $sql = "insert into mirae8440." . $tablename . " ( ";
    
    for ($j = 0; $j < count($fieldarr); $j++) {
        if ($j != 0) {
            $sql .= ' , ';
        }
        $sql .= $fieldarr[$j];
    }
    $sql .= ' ) values( ';
    
    // INSERT SQL 생성 - 값 부분
    for ($j = 0; $j < count($fieldarr); $j++) {
        if ($j != 0) {
            $sql .= ' , ';
        }
        $sql .= '?';
    }
    $sql .= ' ) ';
    
    // 데이터 삽입
    try {
        $pdo->beginTransaction();
        $stmh = $pdo->prepare($sql);
        
        for ($i = 0; $i < count($strarr); $i++) {
            $stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_STR);
        }
        
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
	   
    // 삽입 후 레코드 번호 추출
    try {
        $sql = "select * from mirae8440." . $tablename . " order by num desc ";
        $stmh = $pdo->prepare($sql);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"];
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

// 삭제 모드 처리
if ($mode == "delete") {
    try {
        $pdo->beginTransaction();
        
        $sql = "delete from mirae8440." . $tablename . " where num = ? ";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
}

// 레코드 번호 출력
echo $num;

?>

