<?php
/**
 * mceiling 모듈 - 데이터 저장/삭제 처리
 * 완료/삭제 버튼 처리 및 부모창 새로고침 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// bootstrap.php에서 이미 common/functions.php가 로드되므로 trans_date() 함수는 사용 가능

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }
    return $default;
}

// 요청 변수 초기화
$page = getRequestValue("page", 1);
$mode = getRequestValue("mode", '');
$num = getRequestValue("num", '');
$data = getRequestValue("data", '');
$deldata = getRequestValue("deldata", '');
$from_view = getRequestValue("from_view", '');

// deldata가 있으면 삭제 처리, 아니면 완료 처리
$is_delete_mode = !empty($deldata);
if ($is_delete_mode) {
    $data = $deldata;
}

// 허용된 필드 목록 (SQL Injection 방지)
$allowed_fields = [
    'etclaser_date',
    'etcbending_date',
    'etcwelding_date',
    'etcpainting_date',
    'etcassembly_date',
    'lclaser_date',
    'lcbending_date',
    'lcwelding_date',
    'lcpainting_date',
    'lcassembly_date',
    'mainbending_date',
    'mainwelding_date',
    'mainpainting_date',
    'mainassembly_date',
    'main_draw',
    'lc_draw',
    'eunsung_make_date',
    'eunsung_laser_date'
];

// 필드 검증
if (empty($data) || !in_array($data, $allowed_fields)) {
    error_log("잘못된 필드명: {$data}");
    echo "오류: 잘못된 요청입니다.";
    exit;
}

// 필드명 검증
if (empty($num)) {
    error_log("num 값이 없습니다.");
    echo "오류: 필수 값이 누락되었습니다.";
    exit;
}

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 기존 update_log 조회
$update_log = '';

try {
    $sql = "SELECT update_log FROM mirae8440.ceiling WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $update_log = $row["update_log"] ?? '';
    }
    
} catch (PDOException $ex) {
    error_log("update_log 조회 오류 (num: {$num}): " . $ex->getMessage());
    echo "오류: 데이터를 불러오는 중 문제가 발생했습니다.";
    exit;
}

// 업데이트 로그 생성
$date = date("Y-m-d H:i:s") . " - " . ($_SESSION["name"] ?? 'unknown') . "  ";
$update_log = $date . $update_log . "&#10";  // 개행문자 Textarea

// 업데이트 날짜 결정
if ($is_delete_mode) {
    $update_day = 'X';  // 삭제 모드
} else {
    $update_day = date("Y-m-d");  // 현재날짜 - 완료 모드
}

// 데이터 업데이트
try {
    $pdo->beginTransaction();
    
    // 동적 SQL 구성 (화이트리스트로 검증된 필드만 사용)
    $sql = "UPDATE mirae8440.ceiling SET update_log = ?, {$data} = ? WHERE num = ? LIMIT 1";
    
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $update_log, PDO::PARAM_STR);
    $stmh->bindValue(2, $update_day, PDO::PARAM_STR);
    $stmh->bindValue(3, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();

    // 성공 처리
    $success_message = $is_delete_mode ? '삭제 처리되었습니다.' : '완료 처리되었습니다.';

    // view.php에서 온 경우 부모창 새로고침 처리
    if ($from_view == '1') {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
        </head>
        <body>
        <script>
            // 부모 창이 있으면 갱신 (팝업인 경우)
            if (window.opener && !window.opener.closed) {
                window.opener.location.reload();
                window.location.href = 'view.php?num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>';
            } else {
                // 일반 페이지에서 온 경우: 메시지 후 index.php로 이동
                alert('<?= $success_message ?>');
                window.location.href = 'index.php';
            }
        </script>
        </body>
        </html>
        <?php
        exit;
    }

    // AJAX 응답인 경우 JSON 반환
    echo json_encode([
        'status' => 'success',
        'message' => $success_message,
        'field' => $data,
        'value' => $update_day,
        'num' => $num
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("데이터 업데이트 오류 (num: {$num}, field: {$data}): " . $ex->getMessage());
    echo "오류: 데이터를 업데이트하는 중 문제가 발생했습니다.";
    exit;
}
?>
