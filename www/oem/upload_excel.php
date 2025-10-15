<!DOCTYPE html>
<?php
/**
 * 엑셀 파일 업로드 및 OEM 데이터 일괄 등록
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["name"] ?? '';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

// 권한 체크 (관리자만 접근 가능)
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location: {$base_url}/login/logout.php");
    exit;
}

// DB 연결 (기존 mydb.php 사용)
require_once("../lib/mydb.php");
$pdo = db_connect();

// PHPExcel 라이브러리 로드
require_once("../PHPExcel_1.8.0/Classes/PHPExcel.php");
require_once("../PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php");

$objPHPExcel = new PHPExcel();
$filename = './uploadexcel.xls';

// 엑셀 파일 존재 확인
if (!file_exists($filename)) {
    error_log("엑셀 파일을 찾을 수 없음: {$filename}");
    die("<div class='alert alert-danger'>오류: 엑셀 파일을 찾을 수 없습니다. ({$filename})</div>");
}

$success_count = 0;
$error_count = 0;
$maxRow = 0;

try {
    // 엑셀 파일 읽기
    $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
    
    // 읽기 전용 설정 (PHPExcel 라이브러리 메서드)
    if (method_exists($objReader, 'setReadDataOnly')) {
        $objReader->setReadDataOnly(true);
    }
    
    $objExcel = $objReader->load($filename);
    
    // 첫번째 시트 선택
    $objExcel->setActiveSheetIndex(0);
    $objWorksheet = $objExcel->getActiveSheet();
    $rowIterator = $objWorksheet->getRowIterator();
    
    foreach ($rowIterator as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
    }
    
    $maxRow = $objWorksheet->getHighestRow();
    echo "<div class='alert alert-info'>최대행: {$maxRow}</div>";
    
    // 모든 행 처리 (1번째 행부터)
    for ($i = 1; $i <= $maxRow; $i++) {
        // 엑셀 셀 데이터 읽기
        $add1 = $objWorksheet->getCell('A' . $i)->getValue(); // 접수일
        $add1 = PHPExcel_Style_NumberFormat::toFormattedString($add1, 'YYYY-MM-DD');
        
        $add2 = $objWorksheet->getCell('B' . $i)->getValue(); // 원청
        $add3 = $objWorksheet->getCell('C' . $i)->getValue(); // 발주처
        $add4 = $objWorksheet->getCell('D' . $i)->getValue(); // 현장명
        
        $add5 = $objWorksheet->getCell('E' . $i)->getValue(); // 납기일
        $add5 = PHPExcel_Style_NumberFormat::toFormattedString($add5, 'YYYY-MM-DD');
        
        $add6 = $objWorksheet->getCell('F' . $i)->getValue(); // 타입
        $add7 = $objWorksheet->getCell('G' . $i)->getValue(); // 인승
        $add8 = $objWorksheet->getCell('H' . $i)->getValue(); // 수량(SET)
        $add9 = $objWorksheet->getCell('I' . $i)->getValue(); // L/C 수량
        $add10 = $objWorksheet->getCell('J' . $i)->getValue(); // 기타 수량
        $add11 = $objWorksheet->getCell('K' . $i)->getValue(); // Car insize
        $add12 = $objWorksheet->getCell('L' . $i)->getValue(); // 비고
        $add13 = $objWorksheet->getCell('M' . $i)->getValue(); // 운반비
        
        $add14 = $objWorksheet->getCell('N' . $i)->getValue(); // 발주일
        $add14 = PHPExcel_Style_NumberFormat::toFormattedString($add14, 'YYYY-MM-DD');
        
        $add15 = $objWorksheet->getCell('O' . $i)->getValue(); // 출고일
        $add15 = PHPExcel_Style_NumberFormat::toFormattedString($add15, 'YYYY-MM-DD');
        
        $add16 = $objWorksheet->getCell('P' . $i)->getValue(); // 청구일
        $add16 = PHPExcel_Style_NumberFormat::toFormattedString($add16, 'YYYY-MM-DD');
        
        try {
            $pdo->beginTransaction();
            
            $sql = "INSERT INTO mirae8440.oem(";
            $sql .= "orderday, firstord, secondord, workplacename, deadline, type1, inseung1, su, lc_su, etc_su, ";
            $sql .= "car_insize1, memo, delivery, startday, workday, demand";
            $sql .= ") VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $add1, PDO::PARAM_STR);
            $stmh->bindValue(2, $add2, PDO::PARAM_STR);
            $stmh->bindValue(3, $add3, PDO::PARAM_STR);
            $stmh->bindValue(4, $add4, PDO::PARAM_STR);
            $stmh->bindValue(5, $add5, PDO::PARAM_STR);
            $stmh->bindValue(6, $add6, PDO::PARAM_STR);
            $stmh->bindValue(7, $add7, PDO::PARAM_STR);
            $stmh->bindValue(8, $add8, PDO::PARAM_STR);
            $stmh->bindValue(9, $add9, PDO::PARAM_STR);
            $stmh->bindValue(10, $add10, PDO::PARAM_STR);
            $stmh->bindValue(11, $add11, PDO::PARAM_STR);
            $stmh->bindValue(12, $add12, PDO::PARAM_STR);
            $stmh->bindValue(13, $add13, PDO::PARAM_STR);
            $stmh->bindValue(14, $add14, PDO::PARAM_STR);
            $stmh->bindValue(15, $add15, PDO::PARAM_STR);
            $stmh->bindValue(16, $add16, PDO::PARAM_STR);
            
            $stmh->execute();
            $pdo->commit();
            
            $success_count++;
            echo "<div class='alert alert-success'>기록번호 {$i} - 성공</div>";
        } catch (PDOException $ex) {
            $pdo->rollBack();
            error_log("엑셀 데이터 삽입 오류 (행: {$i}): " . $ex->getMessage());
            echo "<div class='alert alert-danger'>기록번호 {$i} - 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
            $error_count++;
        }
    }
    
    echo "<div class='alert alert-info'><strong>처리 완료:</strong> 성공 {$success_count}건, 실패 {$error_count}건</div>";
    
} catch (Exception $ex) {
    error_log("엑셀 파일 읽기 오류: " . $ex->getMessage());
    echo "<div class='alert alert-danger'>엑셀파일을 읽는 도중 오류가 발생하였습니다.<br>" . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
}
?>

<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>엑셀 파일 업로드</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    
    <style>
        body {
            padding: 20px;
        }
        .alert {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>엑셀 데이터 업로드 결과</h1>
    <hr>
    
    <div class="mt-3">
        <a href="list.php" class="btn btn-primary">목록으로 돌아가기</a>
    </div>
</div>

</body>
</html>
