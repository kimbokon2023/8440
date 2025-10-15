<?php
/**
 * 디버그 정보 페이지
 * 서버 환경 문제 진단용
 */

require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 999;

// 관리자만 접근 가능
if (!isset($_SESSION["level"]) || $level > 1) {
    die("접근 권한이 없습니다.");
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>서버 환경 디버그</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .section h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        .ok { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        pre {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 3px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>🔍 서버 환경 디버그 정보</h1>
    
    <div class="section">
        <h3>1. PHP 환경</h3>
        <p>PHP 버전: <strong><?= PHP_VERSION ?></strong></p>
        <p>Document Root: <strong><?= getDocumentRoot() ?></strong></p>
        <p>현재 파일: <strong><?= __FILE__ ?></strong></p>
    </div>
    
    <div class="section">
        <h3>2. 필수 파일 존재 여부</h3>
        <?php
        $files = [
            'bootstrap.php' => __DIR__ . '/../bootstrap.php',
            'session.php' => getDocumentRoot() . '/session.php',
            'vendor/autoload.php' => getDocumentRoot() . '/vendor/autoload.php',
            'tokens/mytoken.json' => getDocumentRoot() . '/tokens/mytoken.json',
            'lib/mydb.php' => getDocumentRoot() . '/lib/mydb.php',
            'php/common.php' => getDocumentRoot() . '/php/common.php',
            'ceiling/_row.php' => getDocumentRoot() . '/ceiling/_row.php'
        ];
        
        foreach ($files as $name => $path) {
            $exists = file_exists($path);
            $class = $exists ? 'ok' : 'error';
            $status = $exists ? '✅ 존재' : '❌ 없음';
            echo "<p class='{$class}'>{$name}: {$status} ({$path})</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h3>3. Google API 클라이언트</h3>
        <?php
        if (file_exists(getDocumentRoot() . '/vendor/autoload.php')) {
            require_once getDocumentRoot() . '/vendor/autoload.php';
            
            $googleClientExists = class_exists('Google_Client') || class_exists('\Google\Client');
            echo "<p class='" . ($googleClientExists ? 'ok' : 'error') . "'>";
            echo "Google_Client 클래스: " . ($googleClientExists ? '✅ 사용 가능' : '❌ 없음');
            echo "</p>";
            
            if ($googleClientExists) {
                try {
                    $client = new Google_Client();
                    echo "<p class='ok'>✅ Google_Client 인스턴스 생성 성공</p>";
                    
                    if (file_exists(getDocumentRoot() . '/tokens/mytoken.json')) {
                        $client->setAuthConfig(getDocumentRoot() . '/tokens/mytoken.json');
                        echo "<p class='ok'>✅ 인증 설정 성공</p>";
                    }
                } catch (Exception $e) {
                    echo "<p class='error'>❌ Google_Client 초기화 실패: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
        } else {
            echo "<p class='error'>❌ Composer autoload 없음</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h3>4. 데이터베이스 연결</h3>
        <?php
        try {
            require_once getDocumentRoot() . '/lib/mydb.php';
            $pdo = db_connect();
            echo "<p class='ok'>✅ 데이터베이스 연결 성공</p>";
            
            // 테이블 존재 확인
            $sql = "SHOW TABLES LIKE 'ceiling'";
            $stmt = $pdo->query($sql);
            if ($stmt->rowCount() > 0) {
                echo "<p class='ok'>✅ ceiling 테이블 존재</p>";
            } else {
                echo "<p class='error'>❌ ceiling 테이블 없음</p>";
            }
            
            $sql = "SHOW TABLES LIKE 'picuploads'";
            $stmt = $pdo->query($sql);
            if ($stmt->rowCount() > 0) {
                echo "<p class='ok'>✅ picuploads 테이블 존재</p>";
            } else {
                echo "<p class='error'>❌ picuploads 테이블 없음</p>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ 데이터베이스 연결 실패: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h3>5. PHP 확장 모듈</h3>
        <?php
        $extensions = ['pdo', 'pdo_mysql', 'gd', 'json', 'mbstring', 'curl'];
        foreach ($extensions as $ext) {
            $loaded = extension_loaded($ext);
            $class = $loaded ? 'ok' : 'warning';
            $status = $loaded ? '✅ 로드됨' : '⚠️ 없음';
            echo "<p class='{$class}'>{$ext}: {$status}</p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h3>6. PHP 에러 로그 (최근 100줄)</h3>
        <?php
        $errorLog = ini_get('error_log');
        if ($errorLog && file_exists($errorLog)) {
            $lines = file($errorLog);
            $recent = array_slice($lines, -100);
            echo "<pre>";
            echo htmlspecialchars(implode('', $recent));
            echo "</pre>";
        } else {
            echo "<p class='warning'>⚠️ 에러 로그 파일 위치를 찾을 수 없습니다.</p>";
            echo "<p>error_log 설정: <strong>" . ($errorLog ?: '설정 없음') . "</strong></p>";
        }
        ?>
    </div>
    
    <div class="section">
        <h3>7. 세션 정보</h3>
        <pre><?php print_r($_SESSION); ?></pre>
    </div>
    
    <div class="section">
        <h3>8. 서버 정보</h3>
        <p>서버 소프트웨어: <strong><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></strong></p>
        <p>서버 이름: <strong><?= $_SERVER['SERVER_NAME'] ?? 'Unknown' ?></strong></p>
        <p>Document Root: <strong><?= $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' ?></strong></p>
        <p>현재 시간: <strong><?= date('Y-m-d H:i:s') ?></strong></p>
    </div>
    
    <div class="section">
        <h3>9. view.php 테스트</h3>
        <p>
            <a href="view.php?num=8155" target="_blank" style="color: #007bff;">
                view.php?num=8155 열기 →
            </a>
        </p>
    </div>
    
</body>
</html>

