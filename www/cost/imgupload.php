<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// JSON 헤더 설정
header('Content-Type: application/json');

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 업로드 디렉토리 설정
$upload_dir = '';   // 물리적 저장위치

// 응답 데이터 초기화
$response = array(
    'success' => false,
    'message' => '',
    'filename' => ''
);

// 파일 업로드 체크
if (!isset($_FILES["file"]) || !is_array($_FILES["file"])) {
    $response['message'] = '업로드할 파일이 없습니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$files = $_FILES["file"];

// 파일 정보 초기화
$upfile_name = isset($files["name"]) ? $files["name"] : '';
$upfile_tmp_name = isset($files["tmp_name"]) ? $files["tmp_name"] : '';
$upfile_type = isset($files["type"]) ? $files["type"] : '';
$upfile_size = isset($files["size"]) ? $files["size"] : 0;
$upfile_error = isset($files["error"]) ? $files["error"] : UPLOAD_ERR_NO_FILE;

// 파일명 및 확장자 추출
$file = explode(".", $upfile_name);
$file_name = isset($file[0]) ? $file[0] : '';
$file_ext = isset($file[1]) ? $file[1] : '';

// 파일 업로드 오류 체크
if ($upfile_error !== UPLOAD_ERR_OK) {
    $response['message'] = '파일 업로드 중 오류가 발생했습니다. (Error code: ' . $upfile_error . ')';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 파일 확장자 검증 (보안)
$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx');
if (!in_array(strtolower($file_ext), $allowed_extensions)) {
    $response['message'] = '허용되지 않는 파일 형식입니다.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 파일 크기 검증 (10MB 제한)
$max_file_size = 10 * 1024 * 1024; // 10MB
if ($upfile_size > $max_file_size) {
    $response['message'] = '파일 크기가 너무 큽니다. (최대 10MB)';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // 새 파일명 생성 (타임스탬프 기반)
    $new_file_name = date("YmdHis");
    $copied_file_name = $new_file_name . "." . $file_ext;
    $uploaded_file = $upload_dir . $copied_file_name;
    
    // 파일 이동
    if (!move_uploaded_file($upfile_tmp_name, $uploaded_file)) {
        $response['message'] = '파일을 지정한 디렉토리에 복사하는데 실패했습니다.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // 성공 응답
    $response['success'] = true;
    $response['message'] = '파일이 성공적으로 업로드되었습니다.';
    $response['filename'] = $uploaded_file;
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Exception $ex) {
    error_log("파일 업로드 오류: " . $ex->getMessage());
    
    $response['success'] = false;
    $response['message'] = '파일 업로드 중 오류가 발생했습니다.';
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

?>

