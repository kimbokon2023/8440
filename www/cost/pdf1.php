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

try {
    // 이미지 파일 존재 확인
    $imageHeaders = @get_headers($fullImageURL);
    if ($imageHeaders === false || strpos($imageHeaders[0], '200') === false) {
        throw new Exception("이미지 파일을 찾을 수 없습니다: " . $fullImageURL);
    }
    
    // TCPDF 라이브러리 로드
    $tcpdf_path = getDocumentRoot() . '/tcpdf/tcpdf_import.php';
    if (!file_exists($tcpdf_path)) {
        throw new Exception("TCPDF 라이브러리를 찾을 수 없습니다.");
    }
    require_once $tcpdf_path;
    
    // PDF 문서 생성
    /** @var TCPDF $pdf */
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // 헤더 및 푸터 폰트 설정
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    // 기본 고정폭 폰트 설정
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    // 여백 설정
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    // 자동 페이지 나누기 설정
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    
    // 이미지 크기 비율 설정
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // 페이지 추가
    $pdf->AddPage();
    
    // JPEG 품질 설정
    $pdf->setJPEGQuality(75);
    
    // 이미지 삽입
    // Image($file, $x, $y, $w, $h, $type, $link, $align, $resize, $dpi, ...)
    $pdf->Image($fullImageURL, 15, 15, 180, 250, 'JPG', '', '', true, 200, '', false, false, 1, false, false, false);
    
    // 출력 버퍼 정리
    ob_end_clean();
    
    // PDF 문서 출력 (브라우저에서 바로 보기)
    $pdf->Output('Steel_inspection.pdf', 'I');
    
    // 성공 로그
    error_log("PDF 생성 완료: Steel_inspection.pdf (이미지: " . $fullImageURL . ")");
    
    // 이미지 파일 삭제 (선택사항)
    // 주의: URL이므로 직접 삭제 불가, 로컬 경로로 변환 필요
    // $local_image_path = getDocumentRoot() . '/request/' . basename($imageURL);
    // if (file_exists($local_image_path)) {
    //     unlink($local_image_path);
    // }
} catch (Exception $ex) {
    error_log("PDF 생성 오류: " . $ex->getMessage());
    
    // 에러 페이지 표시
    echo "<!DOCTYPE html>";
    echo "<html lang='ko'>";
    echo "<head><meta charset='utf-8'><title>PDF 생성 오류</title></head>";
    echo "<body>";
    echo "<h1>PDF 생성 오류</h1>";
    echo "<p>" . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p><a href='javascript:history.back()'>뒤로 가기</a></p>";
    echo "</body>";
    echo "</html>";
    exit;
}

?>