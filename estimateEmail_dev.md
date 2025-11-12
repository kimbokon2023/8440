# 견적서 온라인 접수 및 이메일 발송 시스템 개발 문서

## 1. 개요

본 문서는 웹사이트에서 견적 문의를 온라인으로 접수받아 지정된 이메일 주소로 전송하는 시스템의 구현 가이드입니다.

### 1.1 주요 기능
- 웹 폼을 통한 견적 문의 접수
- 파일 첨부 지원 (PDF, 이미지, 최대 10MB)
- 개인정보 수집 동의 체크
- PHPMailer를 통한 이메일 발송
- AJAX 기반 비동기 전송
- SweetAlert2를 이용한 사용자 피드백

### 1.2 기술 스택
- Frontend: HTML5, CSS3, Bootstrap, jQuery
- Backend: PHP 7.4+
- Email: PHPMailer 6.x
- Alert: SweetAlert2

---

## 2. 디렉토리 구조

⚠️ **주의**: 현재 미래8440 프로젝트에는 `PHPMailer/` 폴더와 `sendmail.php` 파일이 존재하지 않습니다.
아래 구조는 **새로 만들어야 하는** 디렉토리 구조입니다.

```
your-site/
├── index.php                    # 메인 페이지 (견적 폼 포함)
├── PHPMailer/                  
│   └── sendmail.php            
├── vendor/                     # Composer 의존성 (별도 복사 필요)
│   └── phpmailer/
│       └── phpmailer/
│           ├── src/
│           └── ...
└── assets/
    └── js/
        └── custom.js           # 커스텀 JavaScript (옵션)
```

### 2.1 현재 상태
- ✅ `index.php` - 견적 폼 UI 및 JavaScript 코드 존재
- ✅ `vendor/` - 이미 존재 (다른 사이트로 복사 예정)
- ❌ `PHPMailer/` - **생성 필요**
- ❌ `sendmail.php` - **작성 필요**

---

## 3. HTML 폼 구조

### 3.1 견적 문의 폼 (index.php에 포함)

```html
<!-- 견적 섹션 -->
<div id="contact" class="contact-us section">
  <div class="container">
    <div class="row">
      <!-- 왼쪽: 안내 정보 -->
      <div class="col-lg-6 align-self-center">
        <div class="section-heading">
          <h6>견적</h6>
          <h2>견적 문의</h2>
          <p>
            안녕하세요! 엘리베이터 의장재 전문 제조업체입니다.
            제품 견적이나 맞춤 제작 상담이 필요하시다면 언제든지 편하게 문의해 주세요.
            고객님의 요청에 신속하고 정확하게 답변드리며, 최적의 솔루션을 제공해 드리겠습니다.
          </p>
          <div class="special-offer">
            <span class="offer">편리한<br><em>견적</em></span>
            <h6>Email: <em>your-email@company.com</em></h6>
            <h4>연락처 <em>031</em> XXX.XXXX</h4>
          </div>
        </div>
      </div>

      <!-- 오른쪽: 견적 폼 -->
      <div class="col-lg-6">
        <div class="contact-us-content">
          <form id="contact-form" name="contact-form" method="post" enctype="multipart/form-data">
            <div class="row">
              <!-- 성함 -->
              <div class="col-lg-12">
                <fieldset>
                  <input type="name" name="name" id="name"
                         placeholder="성함" autocomplete="off" required>
                </fieldset>
              </div>

              <!-- 이메일 -->
              <div class="col-lg-12">
                <fieldset>
                  <input type="text" name="email" id="email"
                         pattern="[^ @]*@[^ @]*"
                         placeholder="받으실 Email 주소, ex) yes@gmail.com"
                         autocomplete="off" required>
                </fieldset>
              </div>

              <!-- 연락처 -->
              <div class="col-lg-12">
                <fieldset>
                  <input type="text" name="phone" id="phone"
                         pattern="010-[0-9]{4}-[0-9]{4}"
                         placeholder="연락처 HP, ex) 010-0000-0000"
                         autocomplete="off" required>
                </fieldset>
              </div>

              <!-- 개인정보 동의 -->
              <div class="col-lg-12">
                <div class="text-start d-flex align-items-center">
                  <input type="checkbox" id="privacyCheck" name="privacyCheck"
                         style="transform: scale(0.5); margin: 0 8px 0 0; position: relative;" required>
                  <label for="privacyCheck" class="mb-0">
                    <a href="javascript:void(0);" id="privacyPolicyLink"
                       class="badge bg-primary text-decoration-underline fs-6"
                       style="margin: 0 8px 0 0; position: relative;">
                      개인정보 수집 및 이용에 동의합니다
                    </a>
                  </label>
                </div>
              </div>

              <!-- 메시지 -->
              <div class="col-lg-12">
                <fieldset>
                  <textarea name="message" id="message"
                            placeholder="남기고 싶은 말씀"></textarea>
                </fieldset>
              </div>

              <!-- 파일 첨부 -->
              <div class="col-lg-12">
                <fieldset>
                  <label class="text-white" for="file">
                    파일첨부 (10M 이하, PDF, 이미지):
                  </label>
                  <input type="file" id="file" name="file"
                         class="form-control" accept=".pdf,image/*">
                </fieldset>
              </div>

              <!-- 제출 버튼 -->
              <div class="col-lg-12">
                <fieldset>
                  <button type="button" id="form-submit" class="orange-button">
                    견적 의뢰 하기
                  </button>
                </fieldset>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
```

---

## 4. JavaScript 코드 (AJAX 전송)

### 4.1 필수 라이브러리 로드

```html
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### 4.2 폼 전송 JavaScript

```javascript
<script>
$(document).ready(function(){

  $("#form-submit").click(function () {
    // 1. 필수 필드 확인
    if (
        $('#name').val() === '' ||
        $('#phone').val() === '' ||
        $('#email').val() === '' ||
        $('#message').val() === ''
    ) {
        alert("모든 필드를 입력해주세요.");
        return;
    }

    // 2. 체크박스 확인
    if (!$('#privacyCheck').is(':checked')) {
        alert("개인정보 수집 및 이용에 동의해야 합니다.");
        return;
    }

    // 3. FormData 생성 (파일 첨부 지원)
    var form = $('#contact-form')[0];
    var data = new FormData(form);

    // 4. AJAX를 사용해 데이터 전송
    $.ajax({
        enctype: 'multipart/form-data',
        url: "./PHPMailer/sendmail.php",
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000, // 10분
        data: data,
        success: function (response) {
            console.log(response);

            if (response === "1") {
                Swal.fire({
                    title: "성공",
                    text: "견적요청 메일이 성공적으로 전송되었습니다.",
                    icon: "success",
                    confirmButtonText: "확인"
                });
                $('#contact-form')[0].reset(); // 폼 초기화
            } else {
                Swal.fire({
                    title: "오류",
                    text: "메일 전송 중 오류가 발생했습니다.",
                    icon: "error",
                    confirmButtonText: "확인"
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire({
                title: "오류",
                text: "서버와의 통신 중 문제가 발생했습니다. 다시 시도해주세요.",
                icon: "error",
                confirmButtonText: "확인"
            });
            console.error("에러 발생:", textStatus, errorThrown);
        }
    });
  });

});
</script>
```

---

## 5. PHP 메일 발송 코드

### 5.1 PHPMailer 설치

#### Composer를 통한 설치 (권장)
```bash
composer require phpmailer/phpmailer
```

#### 수동 설치
1. [PHPMailer GitHub](https://github.com/PHPMailer/PHPMailer) 에서 다운로드
2. `vendor/phpmailer/phpmailer` 폴더에 압축 해제

### 5.2 sendmail.php 작성

**파일 경로**: `PHPMailer/sendmail.php`

```php
<?php
/**
 * 견적서 이메일 발송 처리 스크립트
 *
 * @author Your Company
 * @version 1.0
 */

// PHPMailer 라이브러리 로드
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Composer autoload

// ============================================
// 환경 설정
// ============================================
define('SMTP_HOST', 'smtp.gmail.com');           // SMTP 서버 주소
define('SMTP_PORT', 587);                        // SMTP 포트 (TLS: 587, SSL: 465)
define('SMTP_USERNAME', 'your-email@gmail.com'); // 발신 이메일
define('SMTP_PASSWORD', 'your-app-password');    // 앱 비밀번호
define('SMTP_SECURE', 'tls');                    // 암호화 방식 (tls 또는 ssl)

define('FROM_EMAIL', 'your-email@gmail.com');    // 발신자 이메일
define('FROM_NAME', '견적 시스템');               // 발신자 이름

define('TO_EMAIL', 'mirae@8440.co.kr');          // 수신자 이메일 (견적 접수 담당자)
define('TO_NAME', '미래기업 견적담당자');        // 수신자 이름

define('MAX_FILE_SIZE', 10 * 1024 * 1024);       // 최대 파일 크기 (10MB)

// ============================================
// POST 데이터 받기
// ============================================
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$privacy = isset($_POST['privacyCheck']) ? $_POST['privacyCheck'] : '';

// ============================================
// 입력 검증
// ============================================
if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    echo "0"; // 필수 입력값 누락
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "0"; // 잘못된 이메일 형식
    exit;
}

// ============================================
// PHPMailer 인스턴스 생성
// ============================================
$mail = new PHPMailer(true);

try {
    // ============================================
    // SMTP 서버 설정
    // ============================================
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port       = SMTP_PORT;

    // 인코딩 설정
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // ============================================
    // 발신자 및 수신자 설정
    // ============================================
    $mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->addAddress(TO_EMAIL, TO_NAME);
    $mail->addReplyTo($email, $name); // 답장 주소를 문의자 이메일로 설정

    // ============================================
    // 파일 첨부 처리
    // ============================================
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // 파일 크기 확인
        if ($fileSize > MAX_FILE_SIZE) {
            echo "0"; // 파일 크기 초과
            exit;
        }

        // 허용된 파일 형식 확인
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "0"; // 허용되지 않은 파일 형식
            exit;
        }

        // 파일 첨부
        $mail->addAttachment($fileTmpPath, $fileName);
    }

    // ============================================
    // 이메일 내용 설정
    // ============================================
    $mail->isHTML(true);
    $mail->Subject = '[견적문의] ' . $name . '님의 견적 요청';

    // 이메일 본문 (HTML)
    $emailBody = "
    <html>
    <head>
        <style>
            body { font-family: 'Malgun Gothic', sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #4CAF50; color: white; padding: 15px; text-align: center; }
            .content { background-color: #f9f9f9; padding: 20px; margin-top: 10px; }
            .field { margin-bottom: 15px; }
            .field-label { font-weight: bold; color: #333; }
            .field-value { margin-top: 5px; color: #555; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #999; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>새로운 견적 문의가 접수되었습니다</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='field-label'>📌 문의자 정보</div>
                </div>

                <div class='field'>
                    <div class='field-label'>성함:</div>
                    <div class='field-value'>" . htmlspecialchars($name) . "</div>
                </div>

                <div class='field'>
                    <div class='field-label'>이메일:</div>
                    <div class='field-value'>" . htmlspecialchars($email) . "</div>
                </div>

                <div class='field'>
                    <div class='field-label'>연락처:</div>
                    <div class='field-value'>" . htmlspecialchars($phone) . "</div>
                </div>

                <div class='field'>
                    <div class='field-label'>문의 내용:</div>
                    <div class='field-value'>" . nl2br(htmlspecialchars($message)) . "</div>
                </div>

                <div class='field'>
                    <div class='field-label'>접수 시간:</div>
                    <div class='field-value'>" . date('Y-m-d H:i:s') . "</div>
                </div>
            </div>
            <div class='footer'>
                <p>본 메일은 웹사이트 견적 문의 시스템에서 자동으로 발송되었습니다.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $mail->Body = $emailBody;

    // 텍스트 버전 (HTML을 지원하지 않는 메일 클라이언트용)
    $mail->AltBody = "
    [새로운 견적 문의]

    성함: $name
    이메일: $email
    연락처: $phone
    문의 내용:
    $message

    접수 시간: " . date('Y-m-d H:i:s') . "
    ";

    // ============================================
    // 메일 전송
    // ============================================
    $mail->send();
    echo "1"; // 성공

} catch (Exception $e) {
    // 오류 로그 기록 (선택사항)
    error_log("메일 전송 실패: {$mail->ErrorInfo}");
    echo "0"; // 실패
}
?>
```

---

## 6. 이메일 계정 설정 (Gmail 기준)

### 6.1 Gmail 앱 비밀번호 생성

1. Google 계정 관리 → 보안
2. "2단계 인증" 활성화
3. "앱 비밀번호" 생성
4. 생성된 16자리 비밀번호를 `SMTP_PASSWORD`에 입력

### 6.2 다른 SMTP 서버 사용 예시

#### Naver 메일
```php
define('SMTP_HOST', 'smtp.naver.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
```

#### Daum 메일
```php
define('SMTP_HOST', 'smtp.daum.net');
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl');
```

#### 자체 메일 서버
```php
define('SMTP_HOST', 'mail.yourdomain.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
```

---

## 7. 설치 및 배포 가이드

### 7.1 신규 사이트 설치 순서

```bash
# 1. 프로젝트 폴더 생성
mkdir your-new-site
cd your-new-site

# 2. composer.json 생성
composer init

# 3. PHPMailer 설치
composer require phpmailer/phpmailer

# 4. 파일 복사
# - index.php (견적 폼 부분 추출)
# - PHPMailer/sendmail.php
# - 필요한 CSS/JS 파일들

# 5. sendmail.php 설정 수정
# - SMTP 서버 정보
# - 발신/수신 이메일 주소
# - 파일 크기 제한 등

# 6. 테스트
# - 로컬 환경에서 폼 제출 테스트
# - 이메일 수신 확인
# - 파일 첨부 기능 테스트
```

### 7.2 체크리스트

- [ ] Composer를 통해 PHPMailer 설치됨
- [ ] `vendor/` 폴더가 웹 루트에 존재
- [ ] `PHPMailer/sendmail.php` 파일 생성 및 설정 완료
- [ ] SMTP 계정 정보가 정확히 입력됨
- [ ] 수신자 이메일 주소 확인
- [ ] 파일 업로드 디렉토리 권한 설정 (필요시)
- [ ] PHP `upload_max_filesize` 및 `post_max_size` 확인
- [ ] SweetAlert2 및 jQuery 라이브러리 로드 확인
- [ ] 테스트 메일 발송 성공 확인

---

## 8. 보안 고려사항

### 8.1 필수 보안 조치

```php
// 1. XSS 방지: 사용자 입력 이스케이핑
$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');

// 2. SQL 인젝션 방지 (DB 사용 시)
$stmt = $pdo->prepare("INSERT INTO inquiries VALUES (?, ?, ?)");
$stmt->execute([$name, $email, $message]);

// 3. CSRF 토큰 사용 (권장)
// 폼에 토큰 추가 후 서버에서 검증

// 4. Rate Limiting (스팸 방지)
// IP별 요청 횟수 제한 구현

// 5. 파일 업로드 보안
// - 확장자 화이트리스트 방식
// - MIME 타입 검증
// - 업로드 경로 외부 접근 차단
```

### 8.2 .htaccess 설정 (Apache)

```apache
# vendor 폴더 접근 차단
<Directory "/path/to/vendor">
    Require all denied
</Directory>

# PHP 업로드 크기 제한
php_value upload_max_filesize 10M
php_value post_max_size 12M
```

---

## 9. 트러블슈팅

### 9.1 메일이 발송되지 않을 때

```php
// 디버그 모드 활성화
$mail->SMTPDebug = 2; // 상세 로그 출력
$mail->Debugoutput = 'html';
```

**확인 사항:**
- SMTP 계정 정보 정확성
- 방화벽에서 SMTP 포트(587, 465) 허용 여부
- Gmail의 경우 "보안 수준이 낮은 앱 허용" 또는 앱 비밀번호 사용
- PHP `openssl` 확장 활성화 여부

### 9.2 파일 첨부 실패

```php
// PHP 설정 확인
echo "upload_max_filesize: " . ini_get('upload_max_filesize');
echo "post_max_size: " . ini_get('post_max_size');
```

### 9.3 한글 깨짐

```php
// UTF-8 인코딩 확인
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';

// HTML 헤더에도 명시
<meta charset="UTF-8">
```

---

## 10. 추가 기능 구현 (선택사항)

### 10.1 DB 저장 기능

```php
// 견적 문의 내역을 DB에 저장
$pdo = new PDO("mysql:host=localhost;dbname=your_db", "user", "pass");
$stmt = $pdo->prepare("
    INSERT INTO inquiries (name, email, phone, message, created_at)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->execute([$name, $email, $phone, $message]);
```

### 10.2 자동 응답 메일

```php
// 문의자에게 자동 응답 메일 발송
$mail->addAddress($email, $name); // 문의자에게도 발송

$mail->Subject = '[자동응답] 견적 문의가 접수되었습니다';
$mail->Body = "
    <p>$name 님, 안녕하세요.</p>
    <p>견적 문의가 정상적으로 접수되었습니다.</p>
    <p>빠른 시일 내에 답변드리겠습니다.</p>
";
```

### 10.3 관리자 알림 (Slack, SMS 등)

```php
// Slack Webhook 예시
$webhookUrl = 'https://hooks.slack.com/services/YOUR/WEBHOOK/URL';
$data = json_encode([
    'text' => "새 견적 문의: $name ($email)"
]);

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);
```

---

## 11. 라이선스 및 참고자료

### 11.1 사용 라이브러리
- **PHPMailer**: LGPL 2.1 라이선스
- **jQuery**: MIT 라이선스
- **SweetAlert2**: MIT 라이선스

### 11.2 참고 링크
- PHPMailer 공식 문서: https://github.com/PHPMailer/PHPMailer
- Gmail SMTP 설정: https://support.google.com/mail/answer/7126229
- SweetAlert2 문서: https://sweetalert2.github.io/

---

## 12. 버전 히스토리

| 버전 | 날짜 | 변경사항 |
|------|------|----------|
| 1.0 | 2025-11-11 | 초기 문서 작성 |

---

## 13. 문의

기술 지원이 필요하신 경우:
- Email: your-email@company.com
- 전화: 031-XXX-XXXX

---

**문서 작성일**: 2025년 11월 11일
**작성자**: Development Team
