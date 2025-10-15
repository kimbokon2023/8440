# 환경별 설정 시스템 사용 가이드

## 개요

이 폴더에는 로컬 개발 환경과 서버 운영 환경을 자동으로 구분하여 각각의 환경에 맞는 설정을 제공하는 시스템이 구축되어 있습니다.

## 파일 구조

```
www/
├── config/
│   └── environment.php          # 환경 설정 핵심 파일
├── common/
│   ├── functions.php            # 공통 함수 모음
│   └── modal.php               # 공통 모달 컴포넌트
├── lib/
│   └── mydb.php                # 데이터베이스 연결 함수
└── session.php                 # 세션 관리 및 환경별 설정
```

## 사용 방법

### 1. 기본 사용

새로운 PHP 파일을 만들 때 상단에 다음 코드를 추가:

```php
<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$level = $_SESSION["level"] ?? 10;
```

### 2. URL 생성 및 리다이렉션

하드코딩된 URL 대신 환경별 동적 URL 생성:

```php
// ❌ 기존 방식 (하드코딩)
header("Location: http://8440.co.kr/login/logout.php");

// ✅ 새로운 방식 (환경별 자동 적용)
$host = $_SERVER['HTTP_HOST'] ?? '';
if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    header("Location: http://" . $host . "/login/logout.php");
} else {
    header("Location: http://8440.co.kr/login/logout.php");
}
```

### 3. 환경 확인

```php
if (isLocal()) {
    // 로컬 환경에서만 실행할 코드
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // 서버 환경에서만 실행할 코드
    error_reporting(0);
    ini_set('display_errors', 0);
}
```

### 4. 데이터베이스 연결

```php
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();  // 환경에 맞는 설정으로 자동 연결
```

## 주요 함수

### URL 관련 함수

- `url($path)` - 환경별 URL 생성
- `asset($path)` - 자산(CSS, JS, 이미지 등) URL 생성
- `getBaseUrl()` - 기본 URL 반환
- `redirect($path)` - 환경별 리다이렉트

### 환경 확인 함수

- `isLocal()` - 로컬 환경 여부 확인
- `isServer()` - 서버 환경 여부 확인
- `isDebugMode()` - 디버그 모드 여부 확인

### 자산 로드 함수

- `js($path)` - JavaScript 파일 로드 태그 생성
- `css($path)` - CSS 파일 로드 태그 생성

### 디버그 함수

- `debug($data, $label)` - 로컬 환경에서만 데이터 출력
- `setupErrorReporting()` - 환경별 에러 설정

## 환경 감지 조건

### 로컬 환경으로 감지되는 경우:
- `localhost`
- `127.0.0.1`
- `192.168.x.x` (사설 IP)
- `10.0.x.x` (사설 IP)
- `172.16.x.x ~ 172.31.x.x` (사설 IP)

### 서버 환경으로 감지되는 경우:
- 위 조건에 해당하지 않는 모든 도메인 (예: 8440.co.kr)

## 환경별 설정

### 로컬 환경
- DB 사용자: `root`
- DB 비밀번호: (빈 문자열)
- DB 이름: `mirae8440`
- 기본 URL: 현재 도메인 자동 감지 (예: http://localhost:8000)

### 서버 환경
- DB 사용자: `mirae8440`
- DB 비밀번호: `dnjstksfl1!!`
- DB 이름: `mirae8440`
- 기본 URL: `https://8440.co.kr`

## 예제

### 예제 1: 데이터베이스 조회 및 에러 처리

```php
<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $sql = "select * from {$DB}.tablename where num = ? ";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // 데이터 처리
    }
} catch (PDOException $ex) {
    error_log("데이터 조회 오류: " . $ex->getMessage());
}
?>
```

### 예제 2: 데이터 삽입 및 트랜잭션

```php
<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

$DB = $_SESSION["DB"] ?? '';
$mode = $_REQUEST["mode"] ?? 'insert';
$name = $_REQUEST["name"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO {$DB}.tablename (name) VALUES (?)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $name, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
    echo json_encode(array("status" => "success"), JSON_UNESCAPED_UNICODE);
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("데이터 삽입 오류: " . $ex->getMessage());
    echo json_encode(array("error" => "삽입 실패"), JSON_UNESCAPED_UNICODE);
}
?>
```

### 예제 3: 환경별 리다이렉션

```php
<?php
require_once __DIR__ . '/../common/functions.php';

// 로컬/서버 환경에 따른 동적 리다이렉션
$host = $_SERVER['HTTP_HOST'] ?? '';
$protocol = 'http://';

if (strpos($host, 'localhost') === false && strpos($host, '127.0.0.1') === false) {
    $host = '8440.co.kr';
    $protocol = 'https://';
}

header("Location: " . $protocol . $host . "/login/logout.php");
exit;
?>
```

### 예제 4: URL 파라미터 처리

```php
<?php
require_once __DIR__ . '/../common/functions.php';

$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$search = $_REQUEST["search"] ?? '';

$params = http_build_query(array(
    'num' => $num,
    'page' => $page,
    'search' => $search
));

header("Location: view.php?" . $params);
exit;
?>
```

### 예제 5: JavaScript ES5 호환 코드

```javascript
$(document).ready(function() {
    $("#saveBtn").click(function() {
        var formData = new FormData($('#myForm')[0]);
        
        $.ajax({
            url: 'insert.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
                console.log('성공:', data);
            },
            error: function(jqxhr, status, error) {
                console.error('오류:', error);
            }
        });
    });
});
```

## 주요 개선 패턴

### 1. 변수 초기화 패턴
```php
// NULL 병합 연산자 사용
$variable = $_REQUEST["name"] ?? '';
$session_var = $_SESSION["key"] ?? '';
$server_var = $_SERVER['HTTP_HOST'] ?? '';
```

### 2. 에러 처리 패턴
```php
try {
    $pdo->beginTransaction();
    // 데이터베이스 작업
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("작업명 오류: " . $ex->getMessage());
}
```

### 3. JavaScript ES5 패턴
```javascript
// var 사용 (let/const 대신)
var myVar = 'value';

// 전통적 function 사용 (화살표 함수 대신)
function myFunction() {
    // 코드
}

// 문자열 연결 (템플릿 리터럴 대신)
var message = "안녕하세요 " + name + "님";

// for 루프 (forEach 대신)
for (var i = 0; i < array.length; i++) {
    var item = array[i];
}
```

## 주의사항

1. **보안**: 민감한 정보는 절대 Git에 커밋하지 마세요
2. **세션**: `session.php`를 include하면 자동으로 환경별 설정이 적용됩니다
3. **에러 로깅**: `print` 대신 `error_log()` 사용으로 보안 강화
4. **PHP 7.3 호환**: NULL 병합 연산자(`??`) 사용
5. **ES5 호환**: IE11+ 브라우저 지원을 위해 ES5 문법 사용

## 문제 해결

### 변수 미선언 경고
모든 변수를 NULL 병합 연산자로 초기화:
```php
$var = $_REQUEST["key"] ?? '';
```

### 로컬/서버 환경 구분 오류
`$_SERVER['HTTP_HOST']`를 확인하여 localhost 여부 체크:
```php
$host = $_SERVER['HTTP_HOST'] ?? '';
if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    // 로컬 환경
} else {
    // 서버 환경
}
```

### 데이터베이스 연결 오류
1. `includePath('lib/mydb.php')` 사용 확인
2. 로컬 MySQL 서버 실행 확인
3. DB 사용자 권한 확인

