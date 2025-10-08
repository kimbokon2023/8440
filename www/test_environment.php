<?php require_once __DIR__ . '/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>환경 설정 테스트</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .section h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #333;
            font-size: 16px;
            word-break: break-all;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .badge-local {
            background: #4caf50;
            color: white;
        }
        
        .badge-server {
            background: #2196f3;
            color: white;
        }
        
        .status {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .status-success {
            background: #4caf50;
        }
        
        .status-error {
            background: #f44336;
        }
        
        .test-result {
            margin-top: 10px;
            padding: 10px;
            background: white;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
        }
        
        .url-examples {
            margin-top: 10px;
        }
        
        .url-example {
            background: white;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
            border-left: 3px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 환경 설정 테스트</h1>
            <p>로컬/서버 환경 자동 구분 시스템 확인</p>
        </div>
        
        <div class="content">
            <!-- 현재 환경 -->
            <div class="section">
                <h2>📍 현재 환경</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">환경 타입</div>
                        <div class="info-value">
                            <?php if (isLocal()): ?>
                                <span class="badge badge-local">🖥️ 로컬 개발 환경</span>
                            <?php else: ?>
                                <span class="badge badge-server">🌐 서버 운영 환경</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">HTTP_HOST</div>
                        <div class="info-value"><?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'N/A') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">디버그 모드</div>
                        <div class="info-value"><?= isDebugMode() ? '✅ 활성화' : '❌ 비활성화' ?></div>
                    </div>
                </div>
            </div>
            
            <!-- URL 설정 -->
            <div class="section">
                <h2>🌍 URL 설정</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">기본 URL</div>
                        <div class="info-value"><?= htmlspecialchars(getBaseUrl()) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">현재 페이지 URL</div>
                        <div class="info-value"><?= htmlspecialchars(currentUrl()) ?></div>
                    </div>
                </div>
                
                <div class="url-examples">
                    <div class="info-label">URL 생성 예제</div>
                    <div class="url-example">
                        <strong>asset('css/style.css')</strong><br>
                        → <?= htmlspecialchars(asset('css/style.css')) ?>
                    </div>
                    <div class="url-example">
                        <strong>url('login/login_form.php')</strong><br>
                        → <?= htmlspecialchars(url('login/login_form.php')) ?>
                    </div>
                    <div class="url-example">
                        <strong>asset('img/logo.png')</strong><br>
                        → <?= htmlspecialchars(asset('img/logo.png')) ?>
                    </div>
                </div>
            </div>
            
            <!-- 데이터베이스 설정 -->
            <div class="section">
                <h2>💾 데이터베이스 설정</h2>
                <?php
                $dbConfig = getDatabaseConfig();
                $dbConnected = false;
                $dbError = '';
                
                try {
                    require_once __DIR__ . '/lib/mydb.php';
                    $pdo = db_connect();
                    $dbConnected = true;
                } catch (Exception $e) {
                    $dbError = $e->getMessage();
                }
                ?>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">DB 호스트</div>
                        <div class="info-value"><?= htmlspecialchars($dbConfig['host']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">DB 사용자</div>
                        <div class="info-value"><?= htmlspecialchars($dbConfig['user']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">DB 이름</div>
                        <div class="info-value"><?= htmlspecialchars($dbConfig['name']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">연결 상태</div>
                        <div class="info-value">
                            <?php if ($dbConnected): ?>
                                <span class="status status-success"></span> 연결 성공
                            <?php else: ?>
                                <span class="status status-error"></span> 연결 실패
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php if (!$dbConnected): ?>
                    <div class="test-result">
                        <strong style="color: #f44336;">오류:</strong> <?= htmlspecialchars($dbError) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- 환경 정보 -->
            <div class="section">
                <h2>ℹ️ 전체 환경 정보</h2>
                <div class="test-result">
                    <?php
                    $envInfo = getEnvironmentInfo();
                    echo '<pre>' . print_r($envInfo, true) . '</pre>';
                    ?>
                </div>
            </div>
            
            <!-- 사용 가이드 -->
            <div class="section">
                <h2>📖 빠른 사용 가이드</h2>
                <div style="line-height: 1.8;">
                    <p><strong>1. URL 생성:</strong> <code>url('path/to/page.php')</code> 또는 <code>asset('css/style.css')</code></p>
                    <p><strong>2. 환경 확인:</strong> <code>if (isLocal()) { ... }</code></p>
                    <p><strong>3. DB 연결:</strong> <code>$pdo = db_connect();</code></p>
                    <p><strong>4. 디버그:</strong> <code>debug($data, 'Label');</code></p>
                    <p style="margin-top: 15px;">
                        <a href="<?= url('config/README.md') ?>" style="color: #667eea; text-decoration: none; font-weight: bold;">
                            📄 상세 문서 보기 →
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

