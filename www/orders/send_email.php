<?php
/**
 * 발주서 이메일 발송 처리 스크립트
 */

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../lib/OrderPdfGenerator.php';

// PHPMailer 라이브러리 로드
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// JSON 응답 헤더
header('Content-Type: application/json; charset=utf-8');

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 5) {
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.']);
    exit;
}

// POST 데이터 받기
$orderId = $_POST['order_id'] ?? 0;
$targetEmail = $_POST['email'] ?? '';

if (empty($orderId) || empty($targetEmail)) {
    echo json_encode(['success' => false, 'message' => '필수 정보가 누락되었습니다.']);
    exit;
}

if (!filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => '유효하지 않은 이메일 주소입니다.']);
    exit;
}

try {
    // 1. PDF 생성
    $generator = new OrderPdfGenerator();
    $pdfContent = $generator->generate($orderId);
    
    // 파일명 생성 (발주서 정보 조회 필요)
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT contact_name, order_no, project_site FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $contactName = $order['contact_name'] ?? '거래처';
    $projectSite = $order['project_site'] ?? '';
    $filename = '발주서_' . preg_replace('/[\\\\\/:\*\?"<>\|]/u', '', $contactName) . '_' . date('y.m.d') . '.pdf';

    // 2. 이메일 발송 설정
    $mail = new PHPMailer(true);
    
    // SMTP 설정
    $mail->isSMTP();
    $mail->Host       = 'smtp.cafe24.com';
    $mail->SMTPAuth   = true;
    $mail->Port       = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->CharSet    = 'UTF-8';

    // 계정 정보 로드
    $secretPath = __DIR__ . '/../secret/webmail.txt';
    if (!file_exists($secretPath)) {
        throw new Exception('메일 설정 파일을 찾을 수 없습니다.');
    }
    
    $lines = file($secretPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $username = '';
    $password = '';
    
    foreach ($lines as $line) {
        $parts = explode(':', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            
            if ($key === 'id') {
                $username = $value;
            } elseif ($key === 'password') {
                $password = $value;
            }
        }
    }
    
    if (empty($username) || empty($password)) {
        throw new Exception('메일 계정 정보를 읽을 수 없습니다.');
    }

    // 아이디가 이메일 형식이 아니면 도메인 추가 (발신자 주소용)
    $fullEmail = $username;
    if (!filter_var($fullEmail, FILTER_VALIDATE_EMAIL)) {
        $fullEmail .= '@8440.co.kr';
    }

    $mail->Username   = $fullEmail; // 전체 이메일 주소로 인증 시도 (mirae@8440.co.kr)
    $mail->Password   = $password;

    // 발신자/수신자 설정
    $mail->setFrom($fullEmail, '미래기업');
    $mail->addAddress($targetEmail);
    
    // 첨부파일 추가
    $mail->addStringAttachment($pdfContent, $filename);

    // 내용
    $mail->isHTML(true);
    
    // 제목 수정: [미래기업] (현장명) 발주 드립니다.
    $subject = '[미래기업] ';
    if (!empty($projectSite)) {
        $subject .= $projectSite . ' ';
    }
    $subject .= '발주 드립니다.';
    
    $mail->Subject = $subject;
    $mail->Body    = "
        <html>
        <body>
            <h3>발주서 송부드립니다.</h3>
            <p>안녕하세요, 미래기업입니다.</p>
            <p>첨부된 발주서를 확인 부탁드립니다.</p>
            <p>감사합니다.</p>
        </body>
        </html>
    ";
    $mail->AltBody = "발주서 송부드립니다.\n첨부된 발주서를 확인 부탁드립니다.\n감사합니다.";

    $mail->send();

    // 3. 보낸편지함에 저장 (IMAP)
    // 주의: IMAP 확장이 설치되어 있어야 하며, 메일 서버가 IMAP을 지원해야 합니다.
    try {
        if (function_exists('imap_open')) {
            // Cafe24 공용 보안 서버 사용
            $imapHost = '{ssl.cafe24.com:993/imap/ssl}';
            
            // 시도할 폴더 목록 (우선순위 순)
            $folders = [
                "Sent",
                "INBOX.Sent",
                "Sent Messages",
                "INBOX.Sent Messages",
                "nSent",           // 사용자 URL 기반 추정
                "INBOX.nSent",
                "보낸편지함",
                "INBOX.보낸편지함"
            ];
            
            $saved = false;
            foreach ($folders as $folder) {
                $imapStream = @imap_open($imapHost . $folder, $fullEmail, $password);
                if ($imapStream) {
                    imap_append($imapStream, $imapHost . $folder, $mail->getSentMIMEMessage());
                    imap_close($imapStream);
                    $saved = true;
                    break; // 저장 성공 시 중단
                }
            }
            
            if (!$saved) {
                error_log("IMAP 보낸편지함 저장 실패: 유효한 폴더를 찾을 수 없습니다.");
            }
        }
    } catch (Throwable $e) {
        // IMAP 저장은 부가 기능이므로 실패해도 전체 프로세스는 성공으로 처리하고 로그만 남김
        error_log("IMAP 보낸편지함 저장 실패: " . $e->getMessage());
    }

    // 4. 발송 상태 업데이트 (선택 사항)
    $updateStmt = $pdo->prepare("UPDATE orders SET status = 'sent' WHERE id = ? AND status = 'draft'");
    $updateStmt->execute([$orderId]);

    echo json_encode(['success' => true, 'message' => '이메일이 성공적으로 발송되었습니다.']);

} catch (Exception $e) {
    error_log("이메일 발송 실패: " . $mail->ErrorInfo);
    echo json_encode(['success' => false, 'message' => '이메일 발송 중 오류가 발생했습니다: ' . $mail->ErrorInfo]);
} catch (Throwable $e) {
    error_log("시스템 오류: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '시스템 오류가 발생했습니다: ' . $e->getMessage()]);
}
