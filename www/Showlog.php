<?php
require_once __DIR__ . '/bootstrap.php';

// Environment detection for URL
$is_local = (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false));
$WebSite = $is_local ? 'http://localhost/' : 'http://8440.co.kr/';

// 세션 변수 안전하게 초기화
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? 'Unknown';
$DB = $_SESSION["DB"] ?? 'mirae8440';

ini_set('display_errors', isLocal() ? '1' : '0');

// 요청 변수 안전하게 초기화
$num = $_REQUEST["num"] ?? '';
$workitem = $_REQUEST["workitem"] ?? '';

// 입력 검증
if (empty($num) || empty($workitem)) {
    die('필수 매개변수가 누락되었습니다.');
}

// 로그 데이터 조회
$update_log = "";
$update_log_arr = "";

try {
    $sql = "SELECT * FROM {$DB}.{$workitem} WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();

    if ($count < 1) {
        // 데이터가 없을 경우 빈 문자열로 초기화
        $update_log = "";
        $update_log_arr = "";
    } else {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $update_log = $row["update_log"] ?? "";

        // 로그 포맷팅: 연도 앞에 줄바꿈 추가
        $update_log_arr = preg_replace('/(\d{4})/', "<br>$1", $update_log);
    }
} catch (PDOException $Exception) {
    if (isLocal()) {
        error_log("Database error in Showlog.php: " . $Exception->getMessage());
        die("데이터베이스 오류: " . $Exception->getMessage());
    } else {
        error_log("Database error in Showlog.php: " . $Exception->getMessage());
        die("데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.");
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그 기록</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .main-container {
            max-width: 700px;
            width: 100%;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideInUp 0.5s ease;
            overflow: hidden;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border: none;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.75rem;
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
        }

        .card-header h5 i {
            font-size: 2rem;
        }

        .card-body {
            padding: 0;
            background: white;
        }

        .log-container {
            background: #f8f9fa;
            border-radius: 15px;
            margin: 25px;
            overflow: hidden;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .log-header {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: 15px 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .log-header h6 {
            margin: 0;
            font-weight: 600;
            color: #495057;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .log-content {
            padding: 0;
            max-height: 500px;
            overflow-y: auto;
        }

        .log-content::-webkit-scrollbar {
            width: 8px;
        }

        .log-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .log-content::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 4px;
        }

        .log-content::-webkit-scrollbar-thumb:hover {
            background: #764ba2;
        }

        .log-entry {
            padding: 16px 20px;
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s ease;
            font-size: 0.95rem;
            line-height: 1.8;
            color: #495057;
        }

        .log-entry:last-child {
            border-bottom: none;
        }

        .log-entry:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        .log-timestamp {
            color: #667eea;
            font-weight: 600;
            margin-right: 8px;
        }

        .log-user {
            color: #764ba2;
            font-weight: 500;
        }

        .log-empty {
            padding: 60px 20px;
            text-align: center;
            color: #6c757d;
        }

        .log-empty i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
            display: block;
        }

        .log-empty p {
            margin: 0;
            font-size: 1.1rem;
        }

        .card-footer {
            background: white;
            padding: 25px;
            border-top: 2px solid #f1f3f5;
        }

        .btn {
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #654a8e 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn i {
            font-size: 1.1rem;
        }

        .info-badge {
            display: inline-block;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 10px;
        }

        /* 반응형 디자인 */
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .card-header {
                padding: 20px;
            }

            .card-header h5 {
                font-size: 1.4rem;
            }

            .log-container {
                margin: 15px;
            }

            .log-content {
                max-height: 400px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* 로딩 애니메이션 */
        .loading-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="card">
            <div class="card-header">
                <h5>
                    <i class="bi bi-clock-history"></i>
                    로그 기록
                </h5>
            </div>

            <div class="card-body">
                <div class="log-container">
                    <div class="log-header">
                        <h6>
                            <i class="bi bi-calendar-event"></i>
                            기록 시간 / 사용자명
                        </h6>
                    </div>

                    <div class="log-content">
                        <?php
                        // 로그 데이터 포맷팅 및 출력
                        $update_log_arr = str_replace("&#10;", "<br>", $update_log_arr);

                        if (!empty($update_log_arr)) {
                            // 로그 항목을 파싱하여 개별 엔트리로 분리
                            $log_entries = explode("<br>", $update_log_arr);
                            $log_entries = array_filter($log_entries, function($entry) {
                                return !empty(trim($entry));
                            });

                            if (count($log_entries) > 0) {
                                foreach ($log_entries as $entry) {
                                    $entry = trim($entry);
                                    if (!empty($entry)) {
                                        // 날짜/시간과 사용자명 분리 시도 (예: 2024-01-01 10:30:45 / 홍길동)
                                        if (preg_match('/^(\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2})\s*\/\s*(.+)$/', $entry, $matches)) {
                                            echo '<div class="log-entry">';
                                            echo '<span class="log-timestamp"><i class="bi bi-clock"></i> ' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8') . '</span>';
                                            echo '<span class="log-user"><i class="bi bi-person-circle"></i> ' . htmlspecialchars($matches[2], ENT_QUOTES, 'UTF-8') . '</span>';
                                            echo '</div>';
                                        } else {
                                            // 일반 형식
                                            echo '<div class="log-entry">';
                                            echo htmlspecialchars($entry, ENT_QUOTES, 'UTF-8');
                                            echo '</div>';
                                        }
                                    }
                                }

                                // 로그 개수 표시
                                echo '<div class="text-center p-3">';
                                echo '<span class="info-badge"><i class="bi bi-list-ul"></i> 총 ' . count($log_entries) . '개의 로그</span>';
                                echo '</div>';
                            } else {
                                echo '<div class="log-empty">';
                                echo '<i class="bi bi-inbox"></i>';
                                echo '<p>로그 기록이 없습니다.</p>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="log-empty">';
                            echo '<i class="bi bi-inbox"></i>';
                            echo '<p>로그 기록이 없습니다.</p>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="button" id="closeBtn" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    창닫기
                </button>
            </div>
        </div>
    </div>

<script>
// 전역 변수 초기화
var ajRequest = null;

$(document).ready(function() {
    // 창닫기 버튼 이벤트
    $("#closeBtn").on("click", function() {
        // 버튼 애니메이션
        $(this).addClass('loading-pulse');

        setTimeout(function() {
            self.close();
        }, 200);
    });

    // 로그 엔트리 애니메이션
    $('.log-entry').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateX(-20px)'
        });

        $(this).delay(index * 50).animate({
            'opacity': '1'
        }, 300, function() {
            $(this).css('transform', 'translateX(0)');
        });
    });
});

// ESC 키 누를시 팝업 닫기
$(document).keydown(function(e) {
    var code = e.keyCode || e.which;

    if (code == 27) { // 27은 ESC 키번호
        self.close();
    }
});
</script>
</body>
</html>
