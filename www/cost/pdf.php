<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$imageURL = $_REQUEST["imageURL"] ?? '';

// 로컬/서버 환경 감지
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isLocal = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);
$baseUrl = $isLocal ? 'http://' . $host : 'http://8440.co.kr';

// 전체 이미지 URL 생성
$fullImageURL = $baseUrl . '/request/' . $imageURL;

// 변수 초기화
$filename = 'inspection_' . date('YmdHis') . '.pdf';
$width = 0;
$height = 0;
$size = array();

try {
    // 이미지 파일 존재 및 크기 확인
    $file = $fullImageURL;
    $size = @getimagesize($file);
    
    if ($size === false) {
        throw new Exception("이미지 파일을 찾을 수 없거나 유효하지 않습니다: " . $file);
    }
    
    $width = $size[0];
    $height = $size[1];
    
    // mPDF 라이브러리가 로드되었는지 확인
    if (!class_exists('mPDF')) {
        // Composer autoload 시도
        $vendor_autoload = getDocumentRoot() . '/vendor/autoload.php';
        if (file_exists($vendor_autoload)) {
            require_once $vendor_autoload;
        } else {
            throw new Exception("mPDF 라이브러리를 찾을 수 없습니다.");
        }
    }
    
    // PDF 생성
    // Note: mPDF 클래스는 Composer를 통해 런타임에 로드됨 (vendor/autoload.php)
    /** @var \Mpdf\Mpdf $mpdf */
    $mpdf = new mPDF();
    $mpdf->WriteHTML('');
    $mpdf->Image($file, 60, 50, $width, $height, 'jpg', '', true, true);
    $mpdf->Output($filename, 'D'); // 'D' = Download
    
    // 성공 로그
    error_log("PDF 생성 완료: " . $filename);
} catch (Exception $ex) {
    error_log("PDF 생성 오류: " . $ex->getMessage());
    
    header("Content-Type: application/json");
    echo json_encode(
        array(
            'success' => false,
            'message' => 'PDF 생성 중 오류가 발생했습니다.',
            'error' => $ex->getMessage()
        ),
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

// 이미지 파일 삭제 (선택사항)
// 로컬 파일 시스템에 있는 경우에만 삭제 가능
// unlink($imageURL);

?>