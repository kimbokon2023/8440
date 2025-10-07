<?php
// 가비아 서버 환경 정보 확인
// 보안상 실제 운영환경에서는 이 파일을 삭제하거나 접근을 제한해야 합니다.

// 기본 PHP 정보 출력
echo "<h1>가비아 서버 PHP 환경 정보</h1>";
echo "<hr>";

// 1. PHP 버전 정보
echo "<h2>1. PHP 버전 정보</h2>";
echo "<p><strong>PHP 버전:</strong> " . phpversion() . "</p>";
echo "<p><strong>Zend 버전:</strong> " . zend_version() . "</p>";
echo "<hr>";

// 2. 업로드 관련 설정
echo "<h2>2. 파일 업로드 설정</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr><th>설정명</th><th>값</th><th>설명</th></tr>";

$upload_settings = array(
    'file_uploads' => '파일 업로드 허용 여부',
    'upload_max_filesize' => '최대 업로드 파일 크기',
    'post_max_size' => 'POST 데이터 최대 크기',
    'max_file_uploads' => '한 번에 업로드 가능한 최대 파일 수',
    'memory_limit' => '메모리 제한',
    'max_execution_time' => '최대 실행 시간',
    'max_input_time' => '최대 입력 처리 시간',
    'max_input_vars' => '최대 입력 변수 수'
);

foreach ($upload_settings as $setting => $description) {
    $value = ini_get($setting);
    $status = $value ? $value : '설정되지 않음';
    echo "<tr>";
    echo "<td><strong>$setting</strong></td>";
    echo "<td>$status</td>";
    echo "<td>$description</td>";
    echo "</tr>";
}
echo "</table>";
echo "<hr>";

// 3. 서버 정보
echo "<h2>3. 서버 정보</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr><th>항목</th><th>값</th></tr>";

$server_info = array(
    'SERVER_SOFTWARE' => '웹서버 소프트웨어',
    'SERVER_NAME' => '서버 이름',
    'SERVER_ADDR' => '서버 IP 주소',
    'SERVER_PORT' => '서버 포트',
    'DOCUMENT_ROOT' => '문서 루트 디렉토리',
    'SCRIPT_FILENAME' => '현재 스크립트 경로',
    'HTTP_HOST' => 'HTTP 호스트',
    'HTTP_USER_AGENT' => '사용자 에이전트'
);

foreach ($server_info as $key => $description) {
    $value = isset($_SERVER[$key]) ? $_SERVER[$key] : '정보 없음';
    echo "<tr>";
    echo "<td><strong>$description</strong></td>";
    echo "<td>$value</td>";
    echo "</tr>";
}
echo "</table>";
echo "<hr>";

// 4. PHP 확장 모듈 정보
echo "<h2>4. 주요 PHP 확장 모듈</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr><th>모듈명</th><th>상태</th><th>버전</th></tr>";

$important_extensions = array(
    'pdo', 'pdo_mysql', 'mysqli', 'json', 'mbstring', 
    'curl', 'gd', 'zip', 'openssl', 'session', 'fileinfo'
);

foreach ($important_extensions as $ext) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? '로드됨' : '로드되지 않음';
    $version = $loaded ? phpversion($ext) : 'N/A';
    
    echo "<tr>";
    echo "<td><strong>$ext</strong></td>";
    echo "<td style='color: " . ($loaded ? 'green' : 'red') . ";'>$status</td>";
    echo "<td>$version</td>";
    echo "</tr>";
}
echo "</table>";
echo "<hr>";

// 5. 디렉토리 권한 및 쓰기 가능 여부
echo "<h2>5. 디렉토리 권한 확인</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr><th>디렉토리</th><th>존재 여부</th><th>쓰기 권한</th><th>권한</th></tr>";

$directories = array(
    './' => '현재 디렉토리',
    '../' => '상위 디렉토리',
    './uploads/' => 'uploads 디렉토리',
    './temp/' => 'temp 디렉토리'
);

foreach ($directories as $dir => $description) {
    $exists = is_dir($dir);
    $writable = $exists ? is_writable($dir) : false;
    $perms = $exists ? substr(sprintf('%o', fileperms($dir)), -4) : 'N/A';
    
    echo "<tr>";
    echo "<td><strong>$description</strong></td>";
    echo "<td style='color: " . ($exists ? 'green' : 'red') . ";'>" . ($exists ? '존재' : '존재하지 않음') . "</td>";
    echo "<td style='color: " . ($writable ? 'green' : 'red') . ";'>" . ($writable ? '쓰기 가능' : '쓰기 불가') . "</td>";
    echo "<td>$perms</td>";
    echo "</tr>";
}
echo "</table>";
echo "<hr>";

// 6. 업로드 테스트
echo "<h2>6. 파일 업로드 테스트</h2>";
echo "<form method='post' enctype='multipart/form-data'>";
echo "<input type='file' name='test_file' accept='.txt,.pdf,.jpg,.png'>";
echo "<input type='submit' value='업로드 테스트' style='margin-left: 10px;'>";
echo "</form>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    $file = $_FILES['test_file'];
    
    echo "<h3>업로드 결과:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr><th>항목</th><th>값</th></tr>";
    
    echo "<tr><td>파일명</td><td>" . $file['name'] . "</td></tr>";
    echo "<tr><td>파일 타입</td><td>" . $file['type'] . "</td></tr>";
    echo "<tr><td>파일 크기</td><td>" . number_format($file['size']) . " bytes</td></tr>";
    echo "<tr><td>임시 파일</td><td>" . $file['tmp_name'] . "</td></tr>";
    echo "<tr><td>업로드 에러</td><td>" . $file['error'] . " (" . getUploadErrorMessage($file['error']) . ")</td></tr>";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $upload_dir = './uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $target_file = $upload_dir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            echo "<tr><td>저장 결과</td><td style='color: green;'>성공 - $target_file</td></tr>";
        } else {
            echo "<tr><td>저장 결과</td><td style='color: red;'>실패</td></tr>";
        }
    }
    
    echo "</table>";
}
echo "<hr>";

// 7. 메모리 사용량
echo "<h2>7. 메모리 사용량</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr><th>항목</th><th>값</th></tr>";

$memory_usage = memory_get_usage(true);
$memory_peak = memory_get_peak_usage(true);
$memory_limit = ini_get('memory_limit');

echo "<tr><td>현재 메모리 사용량</td><td>" . formatBytes($memory_usage) . "</td></tr>";
echo "<tr><td>최대 메모리 사용량</td><td>" . formatBytes($memory_peak) . "</td></tr>";
echo "<tr><td>메모리 제한</td><td>$memory_limit</td></tr>";
echo "</table>";
echo "<hr>";

// 8. 전체 PHP 정보 링크 (보안상 주의)
echo "<h2>8. 전체 PHP 정보</h2>";
echo "<p><a href='?full_info=1' style='color: blue; text-decoration: underline;'>전체 PHP 정보 보기 (phpinfo())</a></p>";
echo "<p style='color: red; font-size: 12px;'>※ 보안상 실제 운영환경에서는 이 기능을 비활성화해야 합니다.</p>";

// 전체 PHP 정보 출력 (보안상 기본적으로 비활성화)
if (isset($_GET['full_info']) && $_GET['full_info'] == '1') {
    echo "<hr>";
    echo "<h2>전체 PHP 정보 (phpinfo)</h2>";
    echo "<div style='background: #f5f5f5; padding: 10px; border: 1px solid #ccc;'>";
    phpinfo();
    echo "</div>";
}

// 유틸리티 함수들
function getUploadErrorMessage($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_OK:
            return '업로드 성공';
        case UPLOAD_ERR_INI_SIZE:
            return '파일이 upload_max_filesize를 초과';
        case UPLOAD_ERR_FORM_SIZE:
            return '파일이 MAX_FILE_SIZE를 초과';
        case UPLOAD_ERR_PARTIAL:
            return '파일이 부분적으로만 업로드됨';
        case UPLOAD_ERR_NO_FILE:
            return '파일이 업로드되지 않음';
        case UPLOAD_ERR_NO_TMP_DIR:
            return '임시 폴더가 없음';
        case UPLOAD_ERR_CANT_WRITE:
            return '디스크에 쓰기 실패';
        case UPLOAD_ERR_EXTENSION:
            return 'PHP 확장에 의해 업로드 중단';
        default:
            return '알 수 없는 오류';
    }
}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f9f9f9;
}
h1 {
    color: #333;
    border-bottom: 2px solid #007cba;
    padding-bottom: 10px;
}
h2 {
    color: #007cba;
    margin-top: 30px;
}
table {
    background-color: white;
    margin: 10px 0;
}
th {
    background-color: #007cba;
    color: white;
    font-weight: bold;
}
td, th {
    padding: 8px;
    text-align: left;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
input[type="file"], input[type="submit"] {
    padding: 5px;
    margin: 5px 0;
}
input[type="submit"] {
    background-color: #007cba;
    color: white;
    border: none;
    padding: 8px 15px;
    cursor: pointer;
}
input[type="submit"]:hover {
    background-color: #005a8b;
}
</style> 