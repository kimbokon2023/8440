<?php
/**
 * 견적서 PDF 다운로드/미리보기 스크립트
 * EstimatePdfGenerator 클래스를 사용하여 PDF 생성
 */

// 메모리 & 타임아웃 (최상단)
@ini_set('memory_limit', '512M');
@set_time_limit(120);

// 세션 및 DB 연결
require_once dirname(__DIR__) . '/session.php';
require_once dirname(__DIR__) . '/lib/mydb.php';
require_once dirname(__DIR__) . '/lib/EstimatePdfGenerator.php';

// 입력값
$id = $_GET['id'] ?? 0;
$id = (int)$id;
$download = $_GET['download'] ?? 1; // 1=다운, 0=미리보기
$download = (int)$download;
$debug = $_GET['debug'] ?? 0;
$debug = (int)$debug;

if ($id <= 0) {
    http_response_code(400);
    exit('잘못된 id 파라미터입니다.');
}

try {
    // EstimatePdfGenerator 클래스 사용
    $generator = new EstimatePdfGenerator();
    
    // 디버그 모드: HTML 미리보기
    if ($debug) {
        $html = $generator->renderHtmlForDebug($id);
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        exit;
    }
    
    // PDF 스트리밍 (다운로드 또는 미리보기)
    $generator->stream($id, $download == 1);
    
} catch (Exception $e) {
    error_log('[Estimate PDF] Exception: ' . $e->getMessage());
    http_response_code(500);
    echo 'PDF 생성 중 오류가 발생했습니다: ' . htmlspecialchars($e->getMessage());
} catch (Throwable $e) {
    error_log('[Estimate PDF] Throwable: ' . $e->getMessage());
    http_response_code(500);
    echo 'PDF 생성 중 오류가 발생했습니다.';
}
