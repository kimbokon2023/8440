<?php
// 선택해서 결재하는 기능에 대한 처리는 여기서 한다.
// 다중 선택결재 처리 기능
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
require_once(includePath('lib/mydb.php'));

// JSON 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$selectedIds = $_REQUEST["selectedIds"] ?? array();

// 데이터베이스 연결
$pdo = db_connect();

/**
 * 사용자 직책 조회
 * 
 * @param string $userId 사용자 ID
 * @param PDO $pdo 데이터베이스 연결
 * @return string 사용자 직책
 */
function getPosition($userId, $pdo) {
    global $DB;
    
    $query = "SELECT position FROM {$DB}.member WHERE id = ?";
    
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $userId, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        return $row ? $row['position'] : '';
    } catch (PDOException $ex) {
        error_log("직책 조회 오류: " . $ex->getMessage());
        return '';
    }
}

/**
 * 전자결재 정보 조회
 * 
 * @param int $e_num 전자결재 번호
 * @param PDO $pdo 데이터베이스 연결
 * @return array 전자결재 정보
 */
function getEConfirmValues($e_num, $pdo) {
    global $DB;
    
    $query = "SELECT e_confirm, e_confirm_id, e_line_id FROM {$DB}.eworks WHERE num = ?";
    
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $e_num, PDO::PARAM_INT);
        $stmh->execute();
        
        return $stmh->fetch(PDO::FETCH_ASSOC) ?: array();
    } catch (PDOException $ex) {
        error_log("전자결재 정보 조회 오류: " . $ex->getMessage());
        return array();
    }
}

/**
 * 전자결재 상세 정보 조회
 *
 * @param int $e_num 전자결재 번호
 * @param PDO $pdo 데이터베이스 연결
 * @return array 전자결재 상세 정보
 */
function getEworksDetails($e_num, $pdo) {
    global $DB;

    $query = "SELECT eworks_item, author, author_id, al_part, al_askdatefrom, al_usedday, al_content, contents FROM {$DB}.eworks WHERE num = ?";

    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $e_num, PDO::PARAM_INT);
        $stmh->execute();

        return $stmh->fetch(PDO::FETCH_ASSOC) ?: array();
    } catch (PDOException $ex) {
        error_log("전자결재 상세 정보 조회 오류: " . $ex->getMessage());
        return array();
    }
}

// 현재 시간
$date = date('Y-m-d H:i:s');
$last_e_num = null;

try {
    // 선택된 항목들에 대해 결재 처리
    foreach ($selectedIds as $e_num) {
        $last_e_num = $e_num;
        
        // 기존 결재 정보 조회
        $confirmValues = getEConfirmValues($e_num, $pdo);
        $e_confirm = $confirmValues['e_confirm'] ?? '';
        $e_confirm_id = $confirmValues['e_confirm_id'] ?? '';
        $e_line_id = $confirmValues['e_line_id'] ?? '';
        
        // 결재자 정보 생성
        $position = getPosition($user_id, $pdo);
        $approver_info = $user_name . " " . $position . " " . $date;
        
        // 결재자 정보 누적
        if ($e_confirm === '' || $e_confirm === null) {
            $e_confirm_value = $approver_info;
        } else {
            $e_confirm_value = $e_confirm . '!' . $approver_info;
        }
        
        // 결재자 ID 누적
        if ($e_confirm_id === '' || $e_confirm_id === null) {
            $e_confirm_id_value = $user_id;
        } else {
            $e_confirm_id_value = $e_confirm_id . '!' . $user_id;
        }
        
        // 결재 완료 여부 확인
        $e_line_id_count = count(explode("!", $e_line_id));
        $e_confirm_count = count(explode("!", $e_confirm_id_value));
        
        $status = 'ing';
        $done = null;
        
        if ($e_line_id_count == $e_confirm_count) {
            $status = 'end';
            $done = 'done';

            // 연장근무 자동 입력 처리
            $eworksDetails = getEworksDetails($e_num, $pdo);
            if (($eworksDetails['eworks_item'] ?? '') === '연장근무') {
                try {
                    $eworks_author = $eworksDetails['author'] ?? '';
                    $eworks_author_id = $eworksDetails['author_id'] ?? '';
                    $eworks_part = $eworksDetails['al_part'] ?? '';
                    $eworks_askdatefrom = $eworksDetails['al_askdatefrom'] ?? '';
                    $eworks_usedday = $eworksDetails['al_usedday'] ?? '';
                    $eworks_al_content = $eworksDetails['al_content'] ?? '';
                    $eworks_contents = $eworksDetails['contents'] ?? '';

                    $contents_arr = json_decode($eworks_contents, true);
                    $ot_type = is_array($contents_arr) ? ($contents_arr['ot_type'] ?? '') : '';

                    $absent_content = '';
                    if ($ot_type === '잔업') {
                        $absent_content = '연장근로';
                    } else if ($ot_type === '특근') {
                        $absent_content = '특근';
                    } else {
                        $absent_content = $ot_type ?: ($eworks_al_content ?: '연장근로');
                    }

                    $registdate = date('Y-m-d H:i:s');
                    $state = '결재완료';

                    if ($eworks_part === '제조파트') {
                        $sql_insert = "INSERT INTO {$DB}.absent (id, name, registdate, item, askdatefrom, askdateto, usedday, content, state, part) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmh_insert = $pdo->prepare($sql_insert);
                        $stmh_insert->execute(array(
                            $eworks_author_id,
                            $eworks_author,
                            $registdate,
                            $eworks_usedday,
                            $eworks_askdatefrom,
                            $eworks_askdatefrom,
                            '0',
                            $absent_content,
                            $state,
                            $eworks_part
                        ));
                        error_log("연장근무 자동입력 완료: absent 테이블 - {$eworks_author} ({$eworks_askdatefrom})");
                    } else if ($eworks_part === '지원파트') {
                        $sql_insert = "INSERT INTO {$DB}.absent_office (id, name, registdate, item, askdatefrom, askdateto, usedday, content, state, part) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmh_insert = $pdo->prepare($sql_insert);
                        $stmh_insert->execute(array(
                            $eworks_author_id,
                            $eworks_author,
                            $registdate,
                            $eworks_usedday,
                            $eworks_askdatefrom,
                            $eworks_askdatefrom,
                            '0',
                            $absent_content,
                            $state,
                            $eworks_part
                        ));
                        error_log("연장근무 자동입력 완료: absent_office 테이블 - {$eworks_author} ({$eworks_askdatefrom})");
                    }
                } catch (PDOException $ex) {
                    error_log("연장근무 자동입력 오류: " . $ex->getMessage());
                }
            }
        }
        
        // 데이터베이스 업데이트
        $sql = "UPDATE {$DB}.eworks SET e_confirm = ?, e_confirm_id = ?, done = ?, status = ? WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_confirm_value, $e_confirm_id_value, $done, $status, $e_num));
    }
    
    // 성공 응답
    $data = array(
        "success" => true,
        "num" => $last_e_num,
        "message" => "결재 처리 완료"
    );
    
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $ex) {
    http_response_code(500);
    
    error_log("결재 처리 오류: " . $ex->getMessage());
    
    echo json_encode(array(
        'success' => false,
        'error' => 'Database processing error',
        'message' => $ex->getMessage()
    ), JSON_UNESCAPED_UNICODE);
}

?>