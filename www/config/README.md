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
// 환경별 설정 로드
require_once __DIR__ . '/config/environment.php';
// 또는 공통 함수를 사용하려면
require_once __DIR__ . '/common/functions.php';
```

### 2. URL 생성

하드코딩된 URL 대신 환경별 함수 사용:

```php
// ❌ 기존 방식 (하드코딩)
echo "https://8440.co.kr/css/style.css";

// ✅ 새로운 방식 (환경별 자동 적용)
echo asset('css/style.css');
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
require_once __DIR__ . '/lib/mydb.php';
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

### 예제 1: CSS 파일 로드

```php
<!DOCTYPE html>
<html>
<head>
    <?php
    require_once __DIR__ . '/common/functions.php';
    echo css('css/style.css');
    echo css('css/dashboard.css');
    ?>
</head>
<body>
    <!-- 내용 -->
</body>
</html>
```

### 예제 2: JavaScript 파일 로드

```php
<?php
require_once __DIR__ . '/common/functions.php';
echo js('js/jquery.min.js');
echo js('js/common.js');
?>
```

### 예제 3: 이미지 URL

```php
<?php require_once __DIR__ . '/common/functions.php'; ?>
<img src="<?= asset('img/logo.png') ?>" alt="로고">
```

### 예제 4: 폼 액션 URL

```php
<?php require_once __DIR__ . '/common/functions.php'; ?>
<form action="<?= url('api/login.php') ?>" method="POST">
    <!-- 폼 필드 -->
</form>
```

### 예제 5: 디버그 정보 출력

```php
<?php
require_once __DIR__ . '/common/functions.php';

$data = ['name' => '홍길동', 'age' => 30];
debug($data, '사용자 정보');  // 로컬 환경에서만 출력됨
?>
```

## 주의사항

1. **보안**: `environment.php`에 DB 비밀번호가 포함되어 있으므로 Git에 커밋하지 마세요 (이미 .gitignore에 포함됨)
2. **세션**: `session.php`를 include하면 자동으로 환경별 설정이 적용됩니다
3. **캐싱**: 환경 감지 결과는 요청당 한 번만 수행되므로 성능 걱정 없습니다

## 문제 해결

### 환경이 잘못 감지되는 경우
`config/environment.php`의 `getCurrent()` 메서드에서 환경 감지 조건을 수정하세요.

### 데이터베이스 연결 오류
1. 환경별 DB 설정 확인: `config/environment.php`의 `getDatabaseConfig()` 함수
2. 로컬 MySQL 서버 실행 확인
3. DB 사용자 권한 확인

### URL이 잘못 생성되는 경우
`config/environment.php`의 `getBaseUrl()` 함수 확인

