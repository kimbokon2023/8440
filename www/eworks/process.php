<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// Return JSON for AJAX requests
header('Content-Type: application/json; charset=utf-8');

// 변수 초기화
$e_num = $_REQUEST["e_num"] ?? $_REQUEST["num"] ?? null;
$ripple_num = $_REQUEST["ripple_num"] ?? '';
$SelectWork = $_REQUEST["SelectWork"] ?? 'insert';
$e_line = $_REQUEST["e_line"] ?? '';
$e_line_id = $_REQUEST["e_line_id"] ?? '';
$e_confirm = $_REQUEST["e_confirm"] ?? '';
$eworks_item = $_REQUEST["eworks_item"] ?? '';
$author = $_REQUEST["author"] ?? '';
$author_id = $_REQUEST["author_id"] ?? '';

// 기타 변수들 초기화
$recent_num = $e_num;
$status = '';
$e_title = '';
$contents = '';
$registdate = '';
$e_confirm_id = '';
$r_line = '';
$r_line_id = '';
$recordtime = '';
$e_viewexcept_id = '';
$done = '';
$date = date('Y-m-d H:i:s');

/**
 * 사용자의 직책을 가져오는 함수
 * @param string $userId 사용자 ID
 * @param PDO $pdo 데이터베이스 연결 객체
 * @return string 직책명
 */
function getPosition($userId, $pdo, $DB = 'mirae8440') {
    $query = "SELECT position FROM {$DB}.member WHERE id = ?";
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $userId, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['position'] : '';
    } catch (PDOException $ex) {
        error_log("getPosition Error: " . $ex->getMessage());
        return '';
    }
}

/**
 * 댓글 데이터를 가져오는 함수
 * @param int $rippleId 댓글 ID
 * @param PDO $pdo 데이터베이스 연결 객체
 * @return array 댓글 데이터
 */
function getRippleData($rippleId, $pdo, $DB = 'mirae8440') {
    $query = "SELECT * FROM {$DB}.eworks_ripple WHERE num = ?";
    try {
        $stmh = $pdo->prepare($query);
        $stmh->bindValue(1, $rippleId, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        return $row ?: array();
    } catch (PDOException $ex) {
        error_log("getRippleData Error: " . $ex->getMessage());
        return array();
    }
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 배열 분리 및 카운트
$arr = explode("!", $e_line_id);		
$e_line_count = count($arr);
// 결재시간 추출해서 조합하기
$approval_time = explode("!", $e_confirm);	
$e_confirm_count = count($approval_time);

// _request.php 포함
include "_request.php";

// 상태 초기화
if ($status == null) {
    $status = 'draft';   // 최초 작성으로 설정함
}

try {
    if ($SelectWork == "update") {	
        $sql = "UPDATE {$DB}.eworks SET eworks_item=?, e_title=?, contents=?, registdate=?, status=?, e_line=?, e_line_id=?, e_confirm=?, e_confirm_id=?, r_line=?, r_line_id=?, recordtime=?, author=?, author_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($eworks_item, $e_title, $contents, $date, $status, $e_line, $e_line_id, $e_confirm, $e_confirm_id, $r_line, $r_line_id, $recordtime, $author, $author_id, $e_num));
    }
                
    if ($SelectWork == "insert") {	 
        // 데이터베이스에 새로운 문서 추가
        $sql = "INSERT INTO {$DB}.eworks (eworks_item, e_title, contents, registdate, status, e_line, e_line_id, e_confirm, e_confirm_id, r_line, r_line_id, recordtime, author, author_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($eworks_item, $e_title, $contents, $registdate, $status, $e_line, $e_line_id, $e_confirm, $e_confirm_id, $r_line, $r_line_id, $recordtime, $author, $author_id));
        $recent_num = $pdo->lastInsertId();
    } 

    // 결재상신 (작성에서 상신으로 수정)
    if ($SelectWork == "send") {     
        $status = 'send';
        $sql = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($status, $e_num));
    }

    // 결재 승인
    if ($SelectWork == "approval") {
        // 디버깅: 승인 처리 시작
        error_log("=== APPROVAL 처리 시작 ===");
        error_log("e_num: " . $e_num);
        error_log("user_id: " . $user_id);
        error_log("user_name: " . $user_name);
        error_log("받은 e_confirm: " . ($e_confirm ?? 'NULL'));
        error_log("받은 e_confirm_id: " . ($e_confirm_id ?? 'NULL'));
        
        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date;
        // update approval history: append current approver id separated by '!'
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;

        error_log("생성된 e_confirm_value: " . $e_confirm_value);
        error_log("생성된 e_confirm_id_value: " . $e_confirm_id_value);

        $sql = "UPDATE {$DB}.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_confirm_value, $e_confirm_id_value, $e_num));
        
        error_log("UPDATE 완료 - affected rows: " . $stmh->rowCount());
        
        // 결재상태 확인 및 업데이트
        $arr = explode("!", $e_line_id);
        $approval_time = explode("!", $e_confirm_id_value); // 최신 e_confirm_id 사용
        $e_line_count = count($arr);
        $e_confirm_count = count($approval_time);

        if ($e_line_count > $e_confirm_count) {
            $status = 'ing';
        } else if ($e_line_count <= $e_confirm_count) {
            $status = 'end';
            $done = 'done';
            $sql_done = "UPDATE {$DB}.eworks SET done=? WHERE num=?";
            $stmh_done = $pdo->prepare($sql_done);
            $stmh_done->execute(array($done, $e_num));

            // 최종 결재 완료 시 연장근무인 경우 absent/absent_office 테이블에 자동 입력
            if ($eworks_item === '연장근무') {
                try {
                    // 전자결재 문서 정보 조회
                    $sql_eworks = "SELECT * FROM {$DB}.eworks WHERE num=?";
                    $stmh_eworks = $pdo->prepare($sql_eworks);
                    $stmh_eworks->execute(array($e_num));
                    $eworks_row = $stmh_eworks->fetch(PDO::FETCH_ASSOC);

                    if ($eworks_row) {
                        $eworks_author = $eworks_row['author'] ?? '';
                        $eworks_author_id = $eworks_row['author_id'] ?? '';
                        $eworks_part = $eworks_row['al_part'] ?? '';
                        $eworks_askdatefrom = $eworks_row['al_askdatefrom'] ?? '';
                        $eworks_usedday = $eworks_row['al_usedday'] ?? '';
                        $eworks_content = $eworks_row['al_content'] ?? '';
                        $eworks_contents = $eworks_row['contents'] ?? '';

                        // contents JSON에서 상세 정보 추출
                        $contents_arr = json_decode($eworks_contents, true);
                        $ot_type = $contents_arr['ot_type'] ?? '';

                        // 연장근무 유형에 따른 content 매핑
                        // ot_type이 '잔업'이면 '연장근로', '특근'이면 '특근'
                        $absent_content = '';
                        if ($ot_type === '잔업') {
                            $absent_content = '연장근로';
                        } else if ($ot_type === '특근') {
                            $absent_content = '특근';
                        } else {
                            // 기본값: al_content 활용 또는 ot_type 그대로 사용
                            $absent_content = $ot_type ?: '연장근로';
                        }

                        // 등록일 설정
                        $registdate = date('Y-m-d H:i:s');
                        $state = '결재완료';

                        // 부서별 테이블 선택 및 INSERT
                        if ($eworks_part === '제조파트') {
                            // absent 테이블에 INSERT
                            $sql_insert = "INSERT INTO {$DB}.absent (id, name, registdate, item, askdatefrom, askdateto, usedday, content, state, part) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmh_insert = $pdo->prepare($sql_insert);
                            $stmh_insert->execute(array(
                                $eworks_author_id,
                                $eworks_author,
                                $registdate,
                                $eworks_usedday,      // item: 시간
                                $eworks_askdatefrom,   // askdatefrom: 작업일
                                $eworks_askdatefrom,   // askdateto: 작업일 (동일)
                                '0',                   // usedday: 연장근무는 0
                                $absent_content,       // content: 연장근로/특근
                                $state,
                                $eworks_part
                            ));
                            error_log("연장근무 자동입력 완료: absent 테이블 - {$eworks_author} ({$eworks_askdatefrom})");
                        } else if ($eworks_part === '지원파트') {
                            // absent_office 테이블에 INSERT
                            $sql_insert = "INSERT INTO {$DB}.absent_office (id, name, registdate, item, askdatefrom, askdateto, usedday, content, state, part) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmh_insert = $pdo->prepare($sql_insert);
                            $stmh_insert->execute(array(
                                $eworks_author_id,
                                $eworks_author,
                                $registdate,
                                $eworks_usedday,      // item: 시간
                                $eworks_askdatefrom,   // askdatefrom: 작업일
                                $eworks_askdatefrom,   // askdateto: 작업일 (동일)
                                '0',                   // usedday: 연장근무는 0
                                $absent_content,       // content: 특근
                                $state,
                                $eworks_part
                            ));
                            error_log("연장근무 자동입력 완료: absent_office 테이블 - {$eworks_author} ({$eworks_askdatefrom})");
                        }
                    }
                } catch (PDOException $ex) {
                    error_log("연장근무 자동입력 오류: " . $ex->getMessage());
                }
            }
        }

        // status 업데이트
        $sql_status = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh_status = $pdo->prepare($sql_status);
        $stmh_status->execute(array($status, $e_num));
    }

    // 복구
    if ($SelectWork == "restore") {
        $idArray = explode('!', $e_viewexcept_id);
        if (($key = array_search($user_id, $idArray)) !== false) {
            unset($idArray[$key]);
        }
        $e_viewexcept_id_new = implode('!', $idArray);

        $sql = "UPDATE {$DB}.eworks SET e_viewexcept_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_viewexcept_id_new, $e_num));
    }

    // viewexcept 처리 본인에게 보이지 않게 하는 메뉴
    if ($SelectWork == "except") {    
        $e_viewexcept_id_new = ($e_viewexcept_id === '' || $e_viewexcept_id === null) ? $user_id : $e_viewexcept_id . '!' . $user_id;
        
        $sql = "UPDATE {$DB}.eworks SET e_viewexcept_id=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_viewexcept_id_new, $e_num));
    }

    // 결재 회수
    if ($SelectWork == "recall") {
        $status = 'draft';
        $sql = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($status, $e_num));
    }

    // 결재 거절
    if ($SelectWork == "reject") {
        $status = 'reject';
        $sql = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($status, $e_num));

        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date;
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;

        $sql_confirm = "UPDATE {$DB}.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?";
        $stmh_confirm = $pdo->prepare($sql_confirm);
        $stmh_confirm->execute(array($e_confirm_value, $e_confirm_id_value, $e_num));
    }

    // 결재 보류
    if ($SelectWork == "wait") {
        $status = 'wait';
        $sql = "UPDATE {$DB}.eworks SET status=? WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($status, $e_num));

        $e_confirm_value = ($e_confirm === '' || $e_confirm === null) ? $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date : $e_confirm . '!' . $user_name . " " . getPosition($user_id, $pdo, $DB) . " " . $date;
        $e_confirm_id_value = ($e_confirm_id === '' || $e_confirm_id === null) ? $user_id : $e_confirm_id . '!' . $user_id;
        
        $sql_confirm = "UPDATE {$DB}.eworks SET e_confirm=?, e_confirm_id=? WHERE num=?";
        $stmh_confirm = $pdo->prepare($sql_confirm);
        $stmh_confirm->execute(array($e_confirm_value, $e_confirm_id_value, $e_num));
    }

    if ($SelectWork == "delete_ripple") {
        $sql = "UPDATE {$DB}.eworks_ripple SET is_deleted=1 WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($ripple_num));
    }

    if ($SelectWork == "insert_ripple") {
        $ripple_content = $_REQUEST['ripple_content'] ?? '';
        $regist_day = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO {$DB}.eworks_ripple (content, author, author_id, parent, regist_day) VALUES (?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($ripple_content, $user_name, $user_id, $e_num, $regist_day));
        
        $last_id = $pdo->lastInsertId();
        $ripple_data = getRippleData($last_id, $pdo, $DB);

        echo json_encode($ripple_data, JSON_UNESCAPED_UNICODE);
    }

    if ($SelectWork == "deldata") {   
        // data 삭제시 변경 영구삭제가 아닌 소프트삭제 DB남기고 check로 구분    
        $sql = "UPDATE {$DB}.eworks SET is_deleted=1 WHERE num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->execute(array($e_num));
    }

    // 전자결재 댓글저장이 아니면 실행
    if ($SelectWork !== "insert_ripple") {
        $data = array('e_num' => $recent_num); 	
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $ex) {
    http_response_code(500);
    error_log("Database error in process.php: " . $ex->getMessage());
    echo json_encode(array('error' => 'Database processing error', 'message' => $ex->getMessage()), JSON_UNESCAPED_UNICODE);
}

?>

