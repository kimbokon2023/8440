<?php
/**
 * 견적서 이메일 발송 처리 스크립트
 */

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../lib/EstimatePdfGenerator.php';

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
$estimateId = $_POST['estimate_id'] ?? 0;
$targetEmail = $_POST['email'] ?? '';

if (empty($estimateId) || empty($targetEmail)) {
    echo json_encode(['success' => false, 'message' => '필수 정보가 누락되었습니다.']);
    exit;
}

if (!filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => '유효하지 않은 이메일 주소입니다.']);
    exit;
}

try {
    // 1. PDF 생성
    $generator = new EstimatePdfGenerator();
    $pdfContent = $generator->generate($estimateId);
    
    // 파일명 생성 (견적서 정보 조회 필요)
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT contact_name, estimate_no, project_site FROM estimates WHERE id = ?");
    $stmt->execute([$estimateId]);
    $estimate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $contactName = $estimate['contact_name'] ?? '거래처';
    $projectSite = $estimate['project_site'] ?? '';
    $filename = '견적서_' . preg_replace('/[\\\\\/:\*\?"<>\|]/u', '', $contactName) . '_' . date('y.m.d') . '.pdf';

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
    $mail->addBCC($fullEmail); // 보낸 사람에게 숨은 참조(BCC) 발송
    
    // 첨부파일 추가
    $mail->addStringAttachment($pdfContent, $filename);

    // 내용
    $mail->isHTML(true);
    
    // 제목 및 본문 설정 (클라이언트에서 전달받은 값 사용)
    $subject = $_POST['subject'] ?? '';
    $content = $_POST['content'] ?? '';
    
    // 제목이 없으면 기본값 생성 (하위 호환성)
    if (empty($subject)) {
        $subject = '[미래기업] ';
        if (!empty($projectSite)) {
            $subject .= $projectSite . ' ';
        }
        $subject .= '견적 드립니다.';
    }
    
    // 본문이 없으면 기본값 생성
    if (empty($content)) {
        $content = "견적서 송부드립니다.\n\n안녕하세요, 미래기업입니다.\n첨부된 견적서를 확인 부탁드립니다.\n\n감사합니다.";
    }
    
    $mail->Subject = $subject;
    
    // 줄바꿈을 <br>로 변환하여 HTML 본문 생성
    $htmlContent = nl2br(htmlspecialchars($content));
    
    $mail->Body    = "
        <html>
        <body>
            <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                $htmlContent
            </div>
        </body>
        </html>
    ";
    $mail->AltBody = $content;

    $mail->send();

    // 3. 발송 로그 저장 (DB) 및 테이블 자동 생성
    try {
        // 테이블이 없으면 생성 (최초 1회 실행됨)
        $createTableSql = "CREATE TABLE IF NOT EXISTS `estimate_email_logs` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `estimate_id` INT(11) NOT NULL COMMENT '견적서 ID',
            `contact_name` VARCHAR(255) DEFAULT NULL COMMENT '거래처명',
            `sender_email` VARCHAR(255) NOT NULL COMMENT '발신자 이메일',
            `recipient_email` VARCHAR(255) NOT NULL COMMENT '수신자 이메일',
            `subject` VARCHAR(255) NOT NULL COMMENT '메일 제목',
            `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '발송 시간',
            `status` VARCHAR(50) DEFAULT 'success' COMMENT '발송 상태 (success, fail)',
            `error_message` TEXT DEFAULT NULL COMMENT '에러 메시지 (실패 시)',
            PRIMARY KEY (`id`),
            KEY `idx_estimate_id` (`estimate_id`),
            KEY `idx_sent_at` (`sent_at`),
            KEY `idx_contact_name` (`contact_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='견적서 이메일 발송 로그'";
        
        $pdo->exec($createTableSql);

        // contact_name 컬럼이 없으면 추가
        try {
            $checkColumn = $pdo->query("SHOW COLUMNS FROM `estimate_email_logs` LIKE 'contact_name'");
            if ($checkColumn->rowCount() == 0) {
                $pdo->exec("ALTER TABLE `estimate_email_logs` ADD COLUMN `contact_name` VARCHAR(255) DEFAULT NULL COMMENT '거래처명' AFTER `estimate_id`");
                // 인덱스 추가
                try {
                    $pdo->exec("ALTER TABLE `estimate_email_logs` ADD INDEX `idx_contact_name` (`contact_name`)");
                } catch (Exception $e) {
                    // 인덱스가 이미 존재할 수 있음
                }
            }
        } catch (Exception $e) {
            error_log("contact_name 컬럼 확인/추가 오류: " . $e->getMessage());
        }

        // 거래처명 가져오기 (이미 조회한 $estimate 사용)
        $contactName = $estimate['contact_name'] ?? '';

        // 로그 저장 (거래처명 포함)
        $logStmt = $pdo->prepare("INSERT INTO estimate_email_logs (estimate_id, contact_name, sender_email, recipient_email, subject, status) VALUES (?, ?, ?, ?, ?, 'success')");
        $logStmt->execute([$estimateId, $contactName, $fullEmail, $targetEmail, $subject]);

    } catch (Throwable $e) {
        error_log("발송 로그 저장 실패: " . $e->getMessage());
        // 로그 저장이 실패해도 메일은 발송되었으므로 진행
    }

    // 4. 발송 상태 업데이트 (선택 사항)
    $updateStmt = $pdo->prepare("UPDATE estimates SET status = 'sent' WHERE id = ? AND status = 'draft'");
    $updateStmt->execute([$estimateId]);

    echo json_encode(['success' => true, 'message' => '이메일이 성공적으로 발송되었습니다.']);

} catch (Exception $e) {
    error_log("이메일 발송 실패: " . $mail->ErrorInfo);
    echo json_encode(['success' => false, 'message' => '이메일 발송 중 오류가 발생했습니다: ' . $mail->ErrorInfo]);
} catch (Throwable $e) {
    error_log("시스템 오류: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => '시스템 오류가 발생했습니다: ' . $e->getMessage()]);
}
