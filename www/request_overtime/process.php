<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';
require_once(includePath('lib/mydb.php'));

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// PDO 연결
$pdo = db_connect();

// 요청 파라미터
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';

$tablename = "eworks";
$response = array('success' => false, 'message' => '', 'data' => null);

try {
    switch ($mode) {
        case 'insert':
            // ===== DEBUG: 전달받은 REQUEST 데이터 확인 =====
            error_log("");
            error_log("========================================");
            error_log("===== 연장근무 INSERT - REQUEST 데이터 =====");
            error_log("========================================");
            error_log("현재 세션 사용자: user_name='{$user_name}', user_id='{$user_id}'");
            error_log("");
            error_log("📥 REQUEST에서 받은 RAW 데이터:");
            error_log("  - author: " . (isset($_REQUEST["author"]) ? "'{$_REQUEST["author"]}'" : 'NOT SET'));
            error_log("  - author_id: " . (isset($_REQUEST["author_id"]) ? "'{$_REQUEST["author_id"]}'" : 'NOT SET'));
            error_log("  - al_part: " . (isset($_REQUEST["al_part"]) ? "'{$_REQUEST["al_part"]}'" : 'NOT SET'));
            error_log("  - mode: " . (isset($_REQUEST["mode"]) ? "'{$_REQUEST["mode"]}'" : 'NOT SET'));
            error_log("");

            // 데이터 입력 (연차 방식과 동일하게 REQUEST에서 직접 받기)
            $author = $_REQUEST["author"] ?? '';
            $al_part = $_REQUEST["al_part"] ?? '';
            $author_id = $_REQUEST["author_id"] ?? '';
            $ot_type = $_REQUEST["ot_type"] ?? '';
            $al_askdatefrom = $_REQUEST["al_askdatefrom"] ?? '';
            $ot_start_time = $_REQUEST["ot_start_time"] ?? '';
            $ot_end_time = $_REQUEST["ot_end_time"] ?? '';
            $al_usedday = $_REQUEST["al_usedday"] ?? '';
            $al_content = $_REQUEST["al_content"] ?? '';
            $meal_deduction = isset($_REQUEST["meal_deduction"]) && $_REQUEST["meal_deduction"] == '1' ? '1' : '0';
            $registdate = date('Y-m-d H:i:s');

            // DEBUG: 변수에 할당된 값 확인
            error_log("📝 변수에 할당된 최종 값:");
            error_log("  - author (신청자 이름): '{$author}'");
            error_log("  - author_id (신청자 ID): '{$author_id}' (비어있음: " . (empty($author_id) ? 'YES' : 'NO') . ")");
            error_log("  - al_part (신청자 부서): '{$al_part}' (비어있음: " . (empty($al_part) ? 'YES' : 'NO') . ")");
            error_log("  - ot_type: '{$ot_type}'");
            error_log("");
            
            // 중요: author와 author_id가 세션 사용자와 다른지 확인 (관리자가 대리 신청하는 경우)
            if ($author !== $user_name) {
                error_log("⚠️ 대리 신청 감지:");
                error_log("  - 세션 사용자: '{$user_name}' (ID: '{$user_id}')");
                error_log("  - 신청 대상자: '{$author}' (ID: '{$author_id}')");
                error_log("  - 대상자 부서: '{$al_part}'");
            } else {
                error_log("✅ 본인 신청:");
                error_log("  - 신청자: '{$user_name}' (ID: '{$user_id}')");
            }
            error_log("");

            // 트랜잭션 시작 (연차와 동일)
            $pdo->beginTransaction();

            // 결재라인 정보 가져오기 (연차신청과 동일)
            $jsonString = file_get_contents(getDocumentRoot() . '/member/Company_approvalLine_.json');
            $approvalLines = json_decode($jsonString, true);

            // Default values for e_line_id and e_line
            $e_line_id = '';
            $e_line = '';

            // Check if decoded JSON is an array and process it
            if (is_array($approvalLines)) {
                foreach ($approvalLines as $line) {
                    if ($al_part == $line['savedName']) {
                        foreach ($line['approvalOrder'] as $order) {
                            $e_line_id .= $order['user-id'] . '!';
                            $e_line .= $order['name'] . '!';
                        }
                        break;
                    }
                }
            }

            // Set status
            $status = 'send';
            $e_title = '연장근무';

            // contents JSON 생성 (전자결재 화면에 표시될 데이터)
            $contents_data = array(
                "author" => $author,
                "ot_type" => $ot_type,
                "al_askdatefrom" => $al_askdatefrom,
                "ot_start_time" => $ot_start_time,
                "ot_end_time" => $ot_end_time,
                "al_usedday" => $al_usedday,
                "al_content" => $al_content
            );
            $contents = json_encode($contents_data, JSON_UNESCAPED_UNICODE);
            $eworks_item = '연장근무';

            // SQL statement (연차와 동일한 방식으로 bindValue 사용)
            $sql = "INSERT INTO " . $DB . "." . $tablename . " (author_id, author, registdate, al_part, ot_type, al_askdatefrom, ot_start_time, ot_end_time, al_usedday, al_content, `which`, status, e_line_id, e_line, e_title, contents, eworks_item) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            error_log("💾 SQL 실행 직전 바인딩 값:");
            error_log("  [1] author_id: '{$author_id}'");
            error_log("  [2] author: '{$author}'");
            error_log("  [4] al_part: '{$al_part}'");
            error_log("");

            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $author_id, PDO::PARAM_STR);
            $stmh->bindValue(2, $author, PDO::PARAM_STR);
            $stmh->bindValue(3, $registdate, PDO::PARAM_STR);
            $stmh->bindValue(4, $al_part, PDO::PARAM_STR);
            $stmh->bindValue(5, $ot_type, PDO::PARAM_STR);
            $stmh->bindValue(6, $al_askdatefrom, PDO::PARAM_STR);
            $stmh->bindValue(7, $ot_start_time, PDO::PARAM_STR);
            $stmh->bindValue(8, $ot_end_time, PDO::PARAM_STR);
            $stmh->bindValue(9, $al_usedday, PDO::PARAM_STR);
            $stmh->bindValue(10, $al_content, PDO::PARAM_STR);
            $stmh->bindValue(11, $meal_deduction, PDO::PARAM_STR);
            $stmh->bindValue(12, $status, PDO::PARAM_STR);
            $stmh->bindValue(13, rtrim($e_line_id, '!'), PDO::PARAM_STR);
            $stmh->bindValue(14, rtrim($e_line, '!'), PDO::PARAM_STR);
            $stmh->bindValue(15, $e_title, PDO::PARAM_STR);
            $stmh->bindValue(16, $contents, PDO::PARAM_STR);
            $stmh->bindValue(17, $eworks_item, PDO::PARAM_STR);

            $stmh->execute();
            $inserted_num = $pdo->lastInsertId();
            $pdo->commit();

            error_log("✅ INSERT 성공!");
            error_log("  - 생성된 num: {$inserted_num}");
            error_log("  - 저장된 author: '{$author}'");
            error_log("  - 저장된 author_id: '{$author_id}'");
            error_log("  - 저장된 al_part: '{$al_part}'");
            error_log("========================================");
            error_log("");

            $response['success'] = true;
            $response['message'] = '연장근무 신청이 등록되었습니다.';
            $response['data'] = array('num' => $inserted_num);
            break;

        case 'update':
            // 관리자 권한 확인
            $isAdmin = ($user_name == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵') ? 1 : 0;
            
            // 데이터 수정
            $author = $_REQUEST["author"] ?? '';
            $author_id = $_REQUEST["author_id"] ?? '';
            $al_part = $_REQUEST["al_part"] ?? '';
            $ot_type = $_REQUEST["ot_type"] ?? '';
            $al_askdatefrom = $_REQUEST["al_askdatefrom"] ?? '';
            $ot_start_time = $_REQUEST["ot_start_time"] ?? '';
            $ot_end_time = $_REQUEST["ot_end_time"] ?? '';
            $al_usedday = $_REQUEST["al_usedday"] ?? 0;
            $al_content = $_REQUEST["al_content"] ?? '';
            $meal_deduction = isset($_REQUEST["meal_deduction"]) && $_REQUEST["meal_deduction"] == '1' ? '1' : '0';

            error_log("===== 연장근무 UPDATE - REQUEST 데이터 =====");
            error_log("- author: '{$author}'");
            error_log("- author_id: '{$author_id}'");
            error_log("- al_part: '{$al_part}'");
            error_log("- 관리자 여부: " . ($isAdmin ? 'YES' : 'NO'));

            // 현재 상태 확인 (결재 진행중이면 수정 불가)
            $sql_check = "SELECT status, author_id, author, al_part FROM {$DB}.{$tablename} WHERE num = ?";
            $stmh_check = $pdo->prepare($sql_check);
            $stmh_check->execute([$num]);
            $row_check = $stmh_check->fetch(PDO::FETCH_ASSOC);

            if (!$row_check) {
                $response['message'] = '해당 데이터를 찾을 수 없습니다.';
                break;
            }

            // 권한 확인: 관리자가 아닌 경우 본인만 수정 가능
            if ($isAdmin != 1 && $row_check['author_id'] !== $user_id) {
                $response['message'] = '본인이 작성한 데이터만 수정할 수 있습니다.';
                break;
            }

            // 본인의 경우: 결재가 진행되면 수정 불가 (status로 확인)
            if ($isAdmin != 1 && $row_check['author_id'] === $user_id) {
                $current_status = $row_check['status'] ?? '';
                // status가 'send'가 아니면 (즉, 'ing'이나 'end'이면) 결재가 진행된 것
                if ($current_status && $current_status !== 'send' && $current_status !== '') {
                    $response['message'] = '결재가 진행된 데이터는 수정할 수 없습니다.';
                    error_log("수정 불가: status='{$current_status}' (결재 진행됨)");
                    break;
                }
            }

            // 관리자인 경우: 작성자 정보 변경 가능 (결재라인 재설정)
            $updateAuthor = false;
            if ($isAdmin == 1 && ($author !== $row_check['author'] || $author_id !== $row_check['author_id'])) {
                $updateAuthor = true;
                error_log("관리자가 작성자를 변경함: {$row_check['author']} -> {$author}");
                
                // 결재라인 정보 다시 가져오기
                $jsonString = file_get_contents(getDocumentRoot() . '/member/Company_approvalLine_.json');
                $approvalLines = json_decode($jsonString, true);
                
                $e_line_id = '';
                $e_line = '';
                
                if (is_array($approvalLines)) {
                    foreach ($approvalLines as $line) {
                        if ($al_part == $line['savedName']) {
                            foreach ($line['approvalOrder'] as $order) {
                                $e_line_id .= $order['user-id'] . '!';
                                $e_line .= $order['name'] . '!';
                            }
                            break;
                        }
                    }
                }
            }

            // contents JSON 업데이트 (작성자 정보도 포함)
            $contents_data = array(
                "author" => $author,
                "ot_type" => $ot_type,
                "al_askdatefrom" => $al_askdatefrom,
                "ot_start_time" => $ot_start_time,
                "ot_end_time" => $ot_end_time,
                "al_usedday" => $al_usedday,
                "al_content" => $al_content
            );
            $contents = json_encode($contents_data, JSON_UNESCAPED_UNICODE);

            // 데이터 수정 SQL (관리자가 작성자를 변경한 경우 author, author_id, al_part, e_line도 업데이트)
            if ($updateAuthor) {
                $sql = "UPDATE {$DB}.{$tablename}
                        SET author_id = ?, author = ?, al_part = ?, 
                            ot_type = ?, al_askdatefrom = ?, ot_start_time = ?, ot_end_time = ?,
                            al_usedday = ?, al_content = ?, `which` = ?, contents = ?,
                            e_line_id = ?, e_line = ?
                        WHERE num = ?";
                
                $stmh = $pdo->prepare($sql);
                $result = $stmh->execute([
                    $author_id,
                    $author,
                    $al_part,
                    $ot_type,
                    $al_askdatefrom,
                    $ot_start_time,
                    $ot_end_time,
                    $al_usedday,
                    $al_content,
                    $meal_deduction,
                    $contents,
                    rtrim($e_line_id, '!'),
                    rtrim($e_line, '!'),
                    $num
                ]);
            } else {
                $sql = "UPDATE {$DB}.{$tablename}
                        SET ot_type = ?, al_askdatefrom = ?, ot_start_time = ?, ot_end_time = ?,
                            al_usedday = ?, al_content = ?, `which` = ?, contents = ?
                        WHERE num = ?";
                
                $stmh = $pdo->prepare($sql);
                $result = $stmh->execute([
                    $ot_type,
                    $al_askdatefrom,
                    $ot_start_time,
                    $ot_end_time,
                    $al_usedday,
                    $al_content,
                    $meal_deduction,
                    $contents,
                    $num
                ]);
            }

            if ($result) {
                $response['success'] = true;
                $response['message'] = '수정되었습니다.';
            } else {
                $response['message'] = '수정에 실패했습니다.';
            }
            break;

        case 'delete':
            // 관리자 권한 확인
            $isAdmin = ($user_name == '소현철' || $user_name == '김보곤' || $user_name == '최장중' || $user_name == '이경묵') ? 1 : 0;
            
            // 데이터 삭제 (소프트 삭제)
            // 현재 상태 확인
            $sql_check = "SELECT status, author_id FROM {$DB}.{$tablename} WHERE num = ?";
            $stmh_check = $pdo->prepare($sql_check);
            $stmh_check->execute([$num]);
            $row_check = $stmh_check->fetch(PDO::FETCH_ASSOC);

            if (!$row_check) {
                $response['message'] = '해당 데이터를 찾을 수 없습니다.';
                break;
            }

            error_log("===== 연장근무 DELETE - 권한 확인 =====");
            error_log("- num: {$num}");
            error_log("- 작성자 ID: " . ($row_check['author_id'] ?? 'NULL'));
            error_log("- 현재 사용자 ID: {$user_id}");
            error_log("- 관리자 여부: " . ($isAdmin ? 'YES' : 'NO'));
            error_log("- status: " . ($row_check['status'] ?? 'NULL'));

            // 권한 확인: 관리자가 아닌 경우 본인만 삭제 가능
            if ($isAdmin != 1 && $row_check['author_id'] !== $user_id) {
                $response['message'] = '본인이 작성한 데이터만 삭제할 수 있습니다.';
                error_log("삭제 거부: 본인이 아님");
                break;
            }

            // 본인의 경우: 결재가 진행되면 삭제 불가 (status로 확인)
            if ($isAdmin != 1 && $row_check['author_id'] === $user_id) {
                $current_status = $row_check['status'] ?? '';
                // status가 'send'가 아니면 (즉, 'ing'이나 'end'이면) 결재가 진행된 것
                if ($current_status && $current_status !== 'send' && $current_status !== '') {
                    $response['message'] = '결재가 진행된 데이터는 삭제할 수 없습니다.';
                    error_log("삭제 거부: status='{$current_status}' (결재 진행됨)");
                    break;
                }
            }

            // 소프트 삭제 (is_deleted 플래그 설정)
            $sql = "UPDATE {$DB}.{$tablename} SET is_deleted = 1 WHERE num = ?";
            $stmh = $pdo->prepare($sql);
            $result = $stmh->execute([$num]);

            if ($result) {
                $response['success'] = true;
                $response['message'] = '삭제되었습니다.';
            } else {
                $response['message'] = '삭제에 실패했습니다.';
            }
            break;

        case 'load':
            // 데이터 조회
            $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ? AND is_deleted IS NULL";
            $stmh = $pdo->prepare($sql);
            $stmh->execute([$num]);
            $row = $stmh->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $response['success'] = true;
                $response['data'] = array(
                    'num' => $row['num'],
                    'author_id' => $row['author_id'],
                    'author' => $row['author'],
                    'al_part' => $row['al_part'] ?? '',
                    'ot_type' => $row['ot_type'] ?? '',
                    'al_askdatefrom' => $row['al_askdatefrom'] ?? '',
                    'ot_start_time' => $row['ot_start_time'] ?? '',
                    'ot_end_time' => $row['ot_end_time'] ?? '',
                    'al_usedday' => $row['al_usedday'] ?? 0,
                    'al_content' => $row['al_content'] ?? '',
                    'meal_deduction' => $row['which'] ?? '1',  // which 컬럼에서 식사시간 공제 체크 여부 로드
                    'status' => $row['status'] ?? '',
                    'registdate' => $row['registdate'] ?? ''
                );
            } else {
                $response['message'] = '데이터를 찾을 수 없습니다.';
            }
            break;

        default:
            $response['message'] = '올바르지 않은 요청입니다.';
            break;
    }
} catch (PDOException $ex) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response['message'] = '데이터베이스 오류: ' . $ex->getMessage();
    error_log("연장근무 처리 오류: " . $ex->getMessage());
}

// JSON 응답
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
