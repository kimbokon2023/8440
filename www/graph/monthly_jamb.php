<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 베이스 URL 설정 (로컬/서버 환경 자동 감지)
$base_url = getBaseUrl();

// 세션 변수 안전하게 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_name = $_SESSION["user_name"] ?? '';

$title_message = "JAMB 수주현황 분석";
include includePath('load_header.php');

// 기간 계산: start, end 둘 다 있으면 그대로, 하나만 있으면 12개월 보정
if (!empty($_GET['start']) && !empty($_GET['end'])) {
    $startDate = DateTime::createFromFormat('Y-m-01', $_GET['start'] . '-01');
    $endDate = DateTime::createFromFormat('Y-m-01', $_GET['end'] . '-01');
} elseif (!empty($_GET['start'])) {
    $startDate = DateTime::createFromFormat('Y-m-01', $_GET['start'] . '-01');
    $endDate = (clone $startDate)->modify('+11 months');
} elseif (!empty($_GET['end'])) {
    $endDate = DateTime::createFromFormat('Y-m-01', $_GET['end'] . '-01');
    $startDate = (clone $endDate)->modify('-11 months');
} else {
    $endDate = new DateTime('first day of this month');
    $startDate = (clone $endDate)->modify('-11 months');
}
$startYM = $startDate->format('Y-m');
$endYM = $endDate->format('Y-m');

// 보기 모드: revenue|vendor|topVendorMonthly
$viewMode = $_GET['view'] ?? 'revenue';

// 단가 설정
$price_wide = 340000;
$price_normal = 300000;
$price_small = 70000;

// 월 리스트 & 레이블 생성
$months = $labels = [];
for ($dt = clone $startDate; $dt <= $endDate; $dt->modify('+1 month')) {
    $ym = $dt->format('Y-m');
    $months[] = $ym;
    $labels[] = $dt->format('Y') . '년 ' . $dt->format('n') . '월';
}

// PDO 연결
require_once __DIR__ . "/../lib/mydb.php";
$pdo = db_connect();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title_message; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- 공통 함수 -->
    <script src="<?php echo $base_url; ?>/js/common.js"></script>

    <!-- Light & Subtle Theme CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/dashboard-style.css" type="text/css" />

    <style>
        /* Monthly Jamb Specific Styles - Light & Subtle Theme */
        body {
            background: white;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            overflow-x: hidden;
        } 

    .container-fluid {
      background: var(--gradient-primary);
      border-radius: 12px;
      border: 1px solid var(--dashboard-border);
      box-shadow: var(--dashboard-shadow);
      margin: 1rem;
      padding: 1.5rem;
      overflow-x: hidden;
    }

    .jamb-chart-container {
      background: var(--gradient-primary);
      border: 1px solid var(--dashboard-border);
      border-radius: 8px;
      padding: 1rem;
      margin: 0.5rem 0;
      box-shadow: var(--dashboard-shadow);
    }

    .jamb-chart-title {
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--dashboard-text);
      margin-bottom: 0.5rem;
    }

    .jamb-form-check-label {
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--dashboard-text);
      margin-bottom: 0;
      line-height: 1.1rem;
      margin-left: 0.5rem;
      display: inline-flex;
      align-items: center;
    }

    .jamb-form-check-input {
      width: 1.1rem;
      height: 1.1rem;
      margin-top: 0;
      vertical-align: middle;
      flex-shrink: 0;
    }

    .jamb-form-control {
      border: 1px solid var(--dashboard-border);
      border-radius: 4px;
      font-size: 1.17rem;  /* 1.3배 크기 */
      padding: 0.65rem;    /* 패딩도 1.3배 */
    }

    .jamb-form-control:focus {
      border-color: var(--dashboard-accent);
      box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
    }

    .jamb-form-check-input:checked {
      background-color: var(--dashboard-accent);
      border-color: var(--dashboard-accent);
    }

    .form-check {
      display: inline-flex;
      align-items: center;
      margin-right: 1.5rem;
      margin-bottom: 0.5rem;
      padding-left: 0;
    }

    .form-check-inline {
      margin-right: 1.5rem;
    }

    .jamb-table th {
      background: var(--dashboard-secondary);
      color: var(--dashboard-text);
      font-size: 0.8rem;
      font-weight: 600;
      padding: 0.5rem;
    }

    .jamb-table td {
      font-size: 0.9rem;
      padding: 0.5rem;
      background: white;
      border-color: var(--dashboard-border);
    }

    .jamb-table-secondary {
      background: var(--gradient-accent) !important;
      color: white;
    }

    .jamb-card {
      border: 1px solid var(--dashboard-border);
      border-radius: 8px;
      margin-bottom: 1rem;
      background: var(--gradient-primary);
      box-shadow: var(--dashboard-shadow);
    }

    .jamb-card-header {
      background: var(--dashboard-secondary);
      color: var(--dashboard-text);
      font-weight: 600;
      font-size: 0.9rem;
      padding: 0.75rem;
    }

    .highcharts-container {
      height: 300px !important;
    }
    #loadingOverlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      z-index: 9999;
    }

    #loadingOverlay .spinner {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      background: var(--gradient-primary);
      border: 1px solid var(--dashboard-border);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: var(--dashboard-shadow);
    }

    .spinner-border.text-primary {
      color: var(--dashboard-accent) !important;
    }

    .compact-title {
      color: var(--dashboard-text);
      font-weight: 500;
      font-size: 1.17rem;  /* 0.9rem * 1.3 */
    }

    .compact-badge {
      background: var(--gradient-accent);
      color: white;
      padding: 0.3rem 0.8rem;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    /* ========================================= */
    /* Animations */
    /* ========================================= */
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-10px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .compact-stats-card,
    .compact-chart-container,
    .card {
      animation: slideIn 0.3s ease-out;
    }

    /* ========================================= */
    /* Responsive Optimizations */
    /* ========================================= */
    @media (max-width: 768px) {
	/* body와 html의 width 제한 */
	html, body {
		max-width: 100vw !important;
		overflow-x: hidden !important;
		font-size: 16px !important;
	}

	/* 컨테이너 패딩 조정 */
	.container,
	.container-fluid {
		max-width: 100vw !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
		overflow-x: hidden !important;
		margin: 0.5rem 0 !important;
	}

	/* 제목 영역 모바일 최적화 */
	h4.text-center {
		font-size: 1rem !important;
		margin-bottom: 1rem !important;
		padding: 0.5rem !important;
	}

	/* 필터 컨테이너 모바일 최적화 */
	.filter-container {
		margin-bottom: 1rem !important;
	}

	.filter-container .row {
		margin-left: -5px !important;
		margin-right: -5px !important;
	}

	.filter-container .col-md-6 {
		flex: 0 0 100% !important;
		max-width: 100% !important;
		margin-bottom: 1rem !important;
		padding-left: 5px !important;
		padding-right: 5px !important;
	}

	/* 기간 설정 영역 모바일 최적화 - 한 줄로 표시 */
	.col-md-6.d-flex.align-items-center {
		flex-wrap: nowrap !important;
		gap: 3px !important;
		justify-content: center !important;
		align-items: center !important;
		padding: 0.5rem 0.25rem !important;
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch !important;
	}

	.col-md-6.d-flex.align-items-center > label {
		font-size: 0.75rem !important;
		margin-right: 0.3rem !important;
		margin-left: 0.3rem !important;
		white-space: nowrap !important;
		flex-shrink: 0 !important;
	}

	.col-md-6.d-flex.align-items-center .jamb-form-control {
		width: auto !important;
		min-width: 130px !important;
		max-width: 150px !important;
		font-size: 0.8rem !important;
		padding: 0.4rem 0.5rem !important;
		flex: 0 0 auto !important;
		margin-right: 0.3rem !important;
	}

	.col-md-6.d-flex.align-items-center .jamb-form-control::-webkit-datetime-edit {
		font-size: 0.8rem !important;
		padding: 0 !important;
	}

	.col-md-6.d-flex.align-items-center .jamb-form-control::-webkit-calendar-picker-indicator {
		width: 16px !important;
		height: 16px !important;
		padding: 0 !important;
	}

	/* 보기 모드 라디오 버튼 영역 모바일 최적화 */
	.col-md-6.d-flex.align-items-center:last-child {
		flex-wrap: wrap !important;
		justify-content: center !important;
		gap: 0.5rem !important;
	}

	.form-check,
	.form-check-inline {
		margin-right: 0.5rem !important;
		margin-bottom: 0.5rem !important;
		flex-shrink: 0 !important;
	}

	.jamb-form-check-label {
		font-size: 0.75rem !important;
		margin-left: 0.3rem !important;
		white-space: nowrap !important;
	}

	.jamb-form-check-input {
		width: 1rem !important;
		height: 1rem !important;
		margin-right: 0.2rem !important;
	}

	/* 차트 컨테이너 모바일 최적화 */
	.compact-chart-container {
		margin-bottom: 1rem !important;
		padding: 0.75rem !important;
	}

	.jamb-chart-container {
		padding: 0.75rem !important;
		height: 250px !important;
	}

	#chartMain,
	[id^="chartV"] {
		width: 100% !important;
		height: 250px !important;
	}

	.highcharts-container {
		height: 250px !important;
	}

	.compact-chart-title {
		font-size: 0.85rem !important;
		margin-bottom: 0.5rem !important;
	}

	/* 테이블 컨테이너 모바일 최적화 */
	.table-container {
		margin-left: 0 !important;
		margin-top: 1rem !important;
		padding: 0.5rem !important;
	}

	.jamb-table {
		font-size: 0.8rem !important;
		width: 100% !important;
	}

	.jamb-table th {
		font-size: 0.75rem !important;
		padding: 0.4rem 0.3rem !important;
		white-space: nowrap !important;
	}

	.jamb-table td {
		font-size: 0.75rem !important;
		padding: 0.4rem 0.3rem !important;
		word-wrap: break-word !important;
	}

	/* 카드 모바일 최적화 */
	.jamb-card {
		margin-bottom: 1rem !important;
	}

	.jamb-card-header {
		padding: 0.75rem !important;
		font-size: 0.85rem !important;
		flex-wrap: wrap !important;
	}

	.jamb-card-header .d-flex {
		flex-wrap: wrap !important;
		gap: 0.5rem !important;
	}

	.compact-badge {
		font-size: 0.7rem !important;
		padding: 0.25rem 0.6rem !important;
	}

	.card-body {
		padding: 1rem !important;
	}

	.card-body .row {
		margin-left: -5px !important;
		margin-right: -5px !important;
	}

	.card-body .col-md-8,
	.card-body .col-md-4 {
		flex: 0 0 100% !important;
		max-width: 100% !important;
		margin-bottom: 1rem !important;
		padding-left: 5px !important;
		padding-right: 5px !important;
	}

	/* 차트 영역 모바일 최적화 */
	.col-md-8,
	.col-md-4 {
		flex: 0 0 100% !important;
		max-width: 100% !important;
		margin-bottom: 1rem !important;
		padding-left: 5px !important;
		padding-right: 5px !important;
	}

	/* 행 레이아웃 모바일 최적화 */
	.row {
		margin-left: -5px !important;
		margin-right: -5px !important;
	}

	.row > [class*="col-"] {
		padding-left: 5px !important;
		padding-right: 5px !important;
	}

	/* 로딩 오버레이 모바일 최적화 */
	#loadingOverlay .spinner {
		padding: 1.5rem !important;
		font-size: 0.9rem !important;
	}
    } 
  </style>
</head>
<body>
<?php 
if (isset($_GET['header']) && $_GET['header'] === 'header') {
    include includePath('myheader.php');
}
?>
<div id="loadingOverlay">
  <div class="spinner">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <div class="mt-2">페이지를 생성중입니다...</div>
  </div>
</div>

<div class="container py-2">
    <h4 class="text-center mb-4"><?php echo $title_message; ?></h4>
    
    <!-- 필터: 기간 & 보기 모드 -->
    <div class="filter-container">
        <div class="row">
            <div class="col-md-6 d-flex align-items-center mb-2">
                <label class="me-2 compact-title">시작:</label>
                <input type="month" id="startYM" class="jamb-form-control me-4" style="width:210px;" value="<?php echo $startYM; ?>">
                <label class="me-2 compact-title">종료:</label>
                <input type="month" id="endYM" class="jamb-form-control" style="width:210px;" value="<?php echo $endYM; ?>">
            </div>
            <div class="col-md-6 d-flex align-items-center mb-2">
                <?php foreach (['revenue'=>'수주금액 기준','vendor'=>'발주처 수주순위','topVendorMonthly'=>'업체별 월별수주금액'] as $val=>$text): ?>
                <div class="form-check form-check-inline">
                    <input class="jamb-form-check-input" type="radio" name="view" id="view<?php echo $val; ?>" value="<?php echo $val; ?>"
                        <?php echo $viewMode === $val ? 'checked' : ''; ?>>
                    <label class="jamb-form-check-label" for="view<?php echo $val; ?>"><?php echo $text; ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php
    switch ($viewMode) {
        // 1) 월별 총수주금액
        case 'revenue':
            $data = [];
            
            // _row.php에서 사용할 변수 초기화
            $widejamb = 0;
            $normaljamb = 0;
            $smalljamb = 0;
            
            foreach ($months as $ym) {
                $from = "$ym-01";
                $to = date("Y-m-t", strtotime($from));
                $stmt = $pdo->prepare("SELECT * FROM {$DB}.work WHERE workday BETWEEN :from AND :to");
                $stmt->execute(['from'=>$from, 'to'=>$to]);
                $rev = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    include includePath('work/_row.php');
                    $rev += intval($widejamb) * $price_wide
                          + intval($normaljamb) * $price_normal
                          + intval($smalljamb) * $price_small;
                }
                $data[] = $rev;
            }
            ?>
      <div class="row">
        <div class="col-md-8">
          <div class="compact-chart-container">
            <div class="compact-chart-header">
              <div class="compact-chart-icon">📊</div>
              <h6 class="compact-chart-title">월별 수주금액 추이 (천원)</h6>
            </div>
            <div id="chartMain" class="jamb-chart-container"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="table-container">
            <table class="jamb-table table-sm table-bordered">
              <thead>
                <tr><th>월</th><th class="text-end">수주금액(천원)</th></tr>
              </thead>
              <tbody>
              <?php foreach($labels as $i=>$lbl): ?>
                <tr>
                  <td><?=$lbl?></td>
                  <td class="text-end"><?=number_format($data[$i]/1000)?></td>
                </tr>
              <?php endforeach;?>
              <tr class="jamb-table-secondary fw-bold">
                <td>합계</td>
                <td class="text-end"><?=number_format(array_sum($data)/1000)?></td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php
      break;

        // 2) 발주처별 총합 순위 (Top20)
        case 'vendor':
            $vendorRev = [];
            
            // _row.php에서 사용할 변수 초기화
            $widejamb = 0;
            $normaljamb = 0;
            $smalljamb = 0;
            
            $stmt = $pdo->prepare("SELECT * FROM {$DB}.work WHERE workday BETWEEN :from AND :to");
            $stmt->execute([
                'from' => $startDate->format('Y-m-d'),
                'to' => $endDate->format('Y-m-t'),
            ]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                include includePath('work/_row.php');
                $rev = intval($widejamb) * $price_wide
                     + intval($normaljamb) * $price_normal
                     + intval($smalljamb) * $price_small;
                $vendor = $row['secondord'] ?: '기타';
                $vendorRev[$vendor] = ($vendorRev[$vendor] ?? 0) + $rev;
            }
            arsort($vendorRev);
            $top20 = array_slice($vendorRev, 0, 20, true);
            $labels2 = array_keys($top20);
            $data2 = array_values($top20);
            ?>
      <div class="row">
        <div class="col-md-8">
          <div class="compact-chart-container">
            <div class="compact-chart-header">
              <div class="compact-chart-icon">🏆</div>
              <h6 class="compact-chart-title">발주처별 수주금액 순위 (Top 20)</h6>
            </div>
            <div id="chartMain" class="jamb-chart-container"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="table-container">
            <table class="jamb-table table-sm table-bordered">
              <thead>
                <tr><th>순위</th><th>발주처</th><th class="text-end">수주금액(천원)</th></tr>
              </thead>
              <tbody>
              <?php $rank=1; foreach($top20 as $vendor=>$rev): ?>
                <tr>
                  <td><?=$rank++?></td>
                  <td><?=$vendor?></td>
                  <td class="text-end"><?=number_format($rev/1000)?></td>
                </tr>
              <?php endforeach;?>
              <tr class="jamb-table-secondary fw-bold">
                <td colspan="2">합계</td>
                <td class="text-end"><?=number_format(array_sum($data2)/1000)?></td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php
      break;

        // 3) 상위20개 업체 월별 그래프·테이블 반복
        case 'topVendorMonthly':
            // 1) 총합으로 Top20 선정
            $vendorRev = [];
            
            // _row.php에서 사용할 변수 초기화
            $widejamb = 0;
            $normaljamb = 0;
            $smalljamb = 0;
            
            $stmt = $pdo->prepare("SELECT * FROM {$DB}.work WHERE workday BETWEEN :from AND :to");
            $stmt->execute([
                'from' => $startDate->format('Y-m-d'),
                'to' => $endDate->format('Y-m-t'),
            ]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                include includePath('work/_row.php');
                $rev = intval($widejamb) * $price_wide
                     + intval($normaljamb) * $price_normal
                     + intval($smalljamb) * $price_small;
                $vendor = trim($row['secondord']) ?: '기타';
                $vendorRev[$vendor] = ($vendorRev[$vendor] ?? 0) + $rev;
            }
            arsort($vendorRev);
            $top20keys = array_slice(array_keys($vendorRev), 0, 20);

            // 2) 각 업체 월별 집계 + 디버그용 카운트/합계 수집
            $vendorMonthly = [];
            $rowCounts = [];
            $sumValues = [];

            foreach ($top20keys as $vendor) {
                $sums = [];
                $cnts = [];
                foreach ($months as $ym) {
                    $from = "$ym-01";
                    $to = date("Y-m-t", strtotime($from));

                    $st2 = $pdo->prepare("
                        SELECT * FROM {$DB}.work
                        WHERE workday BETWEEN :from AND :to
                            AND TRIM(secondord) = :vendor
                    ");
                    $st2->execute(['from'=>$from, 'to'=>$to, 'vendor'=>$vendor]);

                    // fetchAll 로 한꺼번에 가져온 뒤
                    $rows = $st2->fetchAll(PDO::FETCH_ASSOC);
                    $rowCount = count($rows);
                    $cnts[] = $rowCount;

                    // 합계 계산
                    $sum = 0;
                    foreach ($rows as $r) {
                        // 여기가 핵심! include 전에 $row 에 덮어써 줍니다.
                        $row = $r;
                        include includePath('work/_row.php');
                        $sum += intval($widejamb) * $price_wide
                              + intval($normaljamb) * $price_normal
                              + intval($smalljamb) * $price_small;
                    }
                    $sums[] = $sum;
                }
                $rowCounts[$vendor] = $cnts;
                $vendorMonthly[$vendor] = $sums;
            }

            // 디버그 출력 (콘솔 + 화면)
            echo "<script>
                console.group('🛠 Debug topVendorMonthly');
                console.log('Top20 업체:', ". json_encode($top20keys, JSON_UNESCAPED_UNICODE) .");
                console.log('월별 합계 sums:', ". json_encode($vendorMonthly, JSON_UNESCAPED_UNICODE) .");
                console.log('월별 조회행수 counts:', ". json_encode($rowCounts, JSON_UNESCAPED_UNICODE) .");
                console.groupEnd();
            </script>";

            // echo "<pre style='background:#f8f9fa; padding:1rem; border:1px solid #ccc;'>
            // === Debug: 각 업체별 월별 합계 sums ===\n"
            //   . htmlspecialchars(print_r($vendorMonthly, true))
            //   . "\n\n=== Debug: 각 업체별 월별 조회행수 counts ===\n"
            //   . htmlspecialchars(print_r($rowCounts, true))
            //   . "</pre>";

            // 3) 실제 화면 출력 (기존 코드)
            foreach ($top20keys as $i => $vendor):
                $canvasId = "chartV{$i}";
            ?>
            <div class="jamb-card mb-4">
                <div class="jamb-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <span><?php echo ($i+1); ?>위: <?php echo htmlspecialchars($vendor); ?></span>
                        <div class="compact-badge" style="margin: 0;">
                            합계: <?php echo number_format(array_sum($vendorMonthly[$vendor])/1000); ?>천원
                        </div>
                    </div>
                </div>
                <div class="card-body row" style="padding: 1.5rem;">
                    <div class="col-md-8">
                        <div class="compact-chart-container">
                            <div class="compact-chart-header">
                                <div class="compact-chart-icon">📈</div>
                                <h6 class="compact-chart-title"><?php echo htmlspecialchars($vendor); ?> 월별 추이 (천원)</h6>
                            </div>
                            <div id="<?php echo $canvasId; ?>" class="jamb-chart-container"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="table-container">
                            <table class="jamb-table table-sm table-bordered">
                                <thead>
                                    <tr><th>월</th><th class="text-end">수주금액(천원)</th></tr>
                                </thead>
                                <tbody>
                                <?php foreach ($labels as $j => $lbl): ?>
                                    <tr>
                                        <td><?php echo $lbl; ?></td>
                                        <td class="text-end">
                                            <?php echo number_format(($vendorMonthly[$vendor][$j] ?? 0)/1000); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="jamb-table-secondary fw-bold">
                                    <td>합계</td>
                                    <td class="text-end">
                                        <?php echo number_format(array_sum($vendorMonthly[$vendor])/1000); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            endforeach;
            break;
    }
    ?>

</div>

<script>
    // 로딩 오버레이 보이기
    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'block';
    }

    // 파라미터 셋업 및 페이지 리로드
    function reloadPage(params) {
        showLoading();
        const url = new URL(window.location);
        url.searchParams.delete('start');
        url.searchParams.delete('end');
        url.searchParams.delete('view');
        Object.entries(params).forEach(([k, v]) => {
            url.searchParams.set(k, v);
        });
        window.location = url;
    }

    function getParams() {
        return {
            start: document.getElementById('startYM').value,
            end: document.getElementById('endYM').value,
            view: document.querySelector('input[name=view]:checked').value
        };
    }

    // 이벤트 바인딩
    document.getElementById('startYM').addEventListener('change', function() {
        const dt = new Date(this.value + '-01');
        dt.setMonth(dt.getMonth() + 11);
        document.getElementById('endYM').value = dt.toISOString().slice(0, 7);
        reloadPage(getParams());
    });
    document.getElementById('endYM').addEventListener('change', function() {
        const dt = new Date(this.value + '-01');
        dt.setMonth(dt.getMonth() - 11);
        document.getElementById('startYM').value = dt.toISOString().slice(0, 7);
        reloadPage(getParams());
    });
    document.querySelectorAll('input[name=view]').forEach(rb => {
        rb.addEventListener('change', () => {
            reloadPage(getParams());
        });
    });

    // 차트 그리기 - 컴팩트 블루 테마 적용
    <?php if (in_array($viewMode, ['revenue', 'vendor'])): ?>
    Highcharts.chart('chartMain', {
        chart: {
            type: 'column',
            backgroundColor: 'rgba(255, 255, 255, 0.9)'
        },
        title: {
            text: '<?php echo $viewMode === 'revenue' ? '월별 수주금액 추이 (천원)' : '발주처 수주금액(천원)'; ?>',
            style: { fontSize: '14px', fontWeight: '600', color: '#334155' }
        },
        xAxis: {
            categories: <?php echo json_encode($viewMode === 'revenue' ? $labels : $labels2); ?>,
            labels: { style: { fontSize: '10px', color: '#64748b' } }
        },
        yAxis: {
            title: { text: '천원', style: { fontSize: '10px', color: '#64748b' } },
            labels: { style: { fontSize: '10px', color: '#64748b' } }
        },
        series: [{
            name: '수주금액',
            data: <?php echo json_encode($viewMode === 'revenue' ? array_map(function($x){return $x/1000;}, $data) : array_map(function($x){return $x/1000;}, $data2)); ?>,
            color: '#64748b'
        }],
        tooltip: {
            formatter: function() {
                return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' 천원</b>';
            }
        },
        legend: { enabled: false },
        credits: { enabled: false },
        responsive: {
            rules: [{
                condition: { maxWidth: 500 },
                chartOptions: {
                    legend: { enabled: false }
                }
            }]
        }
    });
    <?php elseif ($viewMode === 'topVendorMonthly'): ?>
    <?php foreach ($top20keys as $i => $vendor): ?>
    Highcharts.chart('chartV<?php echo $i; ?>', {
        chart: {
            type: 'line',
            backgroundColor: 'rgba(255, 255, 255, 0.9)'
        },
        title: {
            text: '<?php echo $vendor; ?> 월별 추이 (천원)',
            style: { fontSize: '12px', fontWeight: '600', color: '#334155' }
        },
        xAxis: {
            categories: <?php echo json_encode($labels); ?>,
            labels: { style: { fontSize: '9px', color: '#64748b' } }
        },
        yAxis: {
            title: { text: '천원', style: { fontSize: '9px', color: '#64748b' } },
            labels: { style: { fontSize: '9px', color: '#64748b' } }
        },
        series: [{
            name: '수주금액',
            data: <?php echo json_encode(array_map(function($x){return $x/1000;}, $vendorMonthly[$vendor])); ?>,
            color: '#64748b',
            lineWidth: 2
        }],
        tooltip: {
            formatter: function() {
                return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' 천원</b>';
            }
        },
        legend: { enabled: false },
        credits: { enabled: false }
    });
    <?php endforeach; ?>
    <?php endif; ?>

    $(document).ready(function() {
        // 방문기록 남김
        var title = '<?php echo $title_message; ?>';        
        saveMenuLog(title);
    });

</script>
</body>
</html>
