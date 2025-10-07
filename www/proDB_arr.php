<?php
// 오류 출력 설정
error_reporting(E_ALL);
ini_set('display_errors', 0); // JSON 응답에서는 오류를 화면에 출력하지 않음

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/mydb.php';

$response = [
    'success' => false,
    'command' => null,
    'message' => '',
];

try {
    // 1) 요청값 취득 및 간단 정리
    $table    = isset($_REQUEST['table'])    ? trim($_REQUEST['table'])    : '';
    $command  = isset($_REQUEST['command'])  ? trim($_REQUEST['command'])  : '';
    $field    = isset($_REQUEST['field'])    ? trim($_REQUEST['field'])    : '';
    $strtmp   = isset($_REQUEST['strtmp'])   ? trim($_REQUEST['strtmp'])   : '';
    $recnum   = isset($_REQUEST['recnum'])   ? trim($_REQUEST['recnum'])   : '';
    $arr      = isset($_REQUEST['arr'])      ? $_REQUEST['arr']           : [];
    $fieldarr = isset($_REQUEST['fieldarr']) ? $_REQUEST['fieldarr']      : [];

    $response['command'] = $command;

    // 2) 기본 검증
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        throw new Exception('유효하지 않은 테이블명입니다.');
    }
    if (!in_array($command, ['insert','update','delete'], true)) {
        throw new Exception('지원하지 않는 명령입니다.');
    }

    // 3) DB 연결
    $pdo = db_connect();

    // 4) 명령별 추가 검증
    $fieldstrarr = [];
    $strarr      = [];

    if ($command === 'insert') {
        if (empty($fieldarr)) {
            throw new Exception('필드 배열이 누락되었습니다.');
        }
        
        // 배열이 문자열로 전송된 경우 처리
        if (is_string($fieldarr)) {
            $fieldstrarr = explode(',', $fieldarr);
        } elseif (is_array($fieldarr)) {
            $fieldstrarr = is_string($fieldarr[0]) ? explode(',', $fieldarr[0]) : $fieldarr;
        } else {
            throw new Exception('필드 배열 형식이 올바르지 않습니다.');
        }
        
        // 값 배열 처리
        if (is_string($arr)) {
            $strarr = explode(',', $arr);
        } elseif (is_array($arr)) {
            $strarr = is_string($arr[0]) ? explode(',', $arr[0]) : $arr;
        } else {
            $strarr = [];
        }

        if (count($fieldstrarr) !== count($strarr)) {
            throw new Exception('필드 수와 값 수가 일치하지 않습니다.');
        }
        foreach ($fieldstrarr as $f) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $f)) {
                throw new Exception("유효하지 않은 필드명: {$f}");
            }
        }
    } elseif ($command === 'update') {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $field)) {
            throw new Exception('유효하지 않은 업데이트 필드명입니다.');
        }
        if ($recnum === '' || !ctype_digit($recnum)) {
            throw new Exception('유효하지 않은 레코드 번호입니다.');
        }
    } elseif ($command === 'delete') {
        if ($recnum === '' || !ctype_digit($recnum)) {
            throw new Exception('유효하지 않은 레코드 번호입니다.');
        }
    }

    // 5) 실행
    if ($command === 'update') {
        $sql = "UPDATE `mirae8440`.`$table` SET `$field` = ? WHERE `num` = ? LIMIT 1";
        $pdo->beginTransaction();
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$strtmp, $recnum]);
        $pdo->commit();

        $response['success'] = true;
        $response['message'] = '업데이트 완료';

    } elseif ($command === 'delete') {
        $sql = "DELETE FROM `mirae8440`.`$table` WHERE `num` = ? LIMIT 1";
        $pdo->beginTransaction();
        $stmh = $pdo->prepare($sql);
        $stmh->execute([$recnum]);
        $pdo->commit();

        $response['success'] = true;
        $response['message'] = '삭제 완료';

    } else { // insert
        $cols         = array_map(function($f) { return "`{$f}`"; }, $fieldstrarr);
        $placeholders = rtrim(str_repeat('?,', count($fieldstrarr)), ',');
        $sql = "INSERT INTO `mirae8440`.`$table` (" . implode(',', $cols) . ") VALUES ({$placeholders})";

        $pdo->beginTransaction();
        $stmh = $pdo->prepare($sql);
        $stmh->execute($strarr);
        $lastId = $pdo->lastInsertId();
        $pdo->commit();

        $response['success']     = true;
        $response['message']     = '삽입 완료';
        $response['lastInsertId']= $lastId;
    }

} catch (Exception $e) {
    // 트랜잭션 중 오류 시 롤백
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    $response['error_details'] = [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ];
}

// 6) JSON 응답
echo json_encode($response, JSON_UNESCAPED_UNICODE);
