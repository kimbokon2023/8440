<?php
// ───────────────────────────────────────────────────────────
// monthly_ceiling.php: 조명천장/본천장 수주통계 (원 단위 표시)
// ───────────────────────────────────────────────────────────
include $_SERVER['DOCUMENT_ROOT'] . '/session.php';
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}
$title_message = "조명천장/본천장 수주현황 분석";

// 단가 파일 읽기
$readIni = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/ceiling/estimate.ini", false);

// ── 1) 기간 계산 (start, end 둘 다 있으면 그대로, 하나만 있으면 12개월 보정) ──
if (!empty($_GET['start']) && !empty($_GET['end'])) {
    $startDate = DateTime::createFromFormat('Y-m-01', $_GET['start'] . '-01');
    $endDate   = DateTime::createFromFormat('Y-m-01', $_GET['end']   . '-01');
} elseif (!empty($_GET['start'])) {
    $startDate = DateTime::createFromFormat('Y-m-01', $_GET['start'] . '-01');
    $endDate   = (clone $startDate)->modify('+11 months');
} elseif (!empty($_GET['end'])) {
    $endDate   = DateTime::createFromFormat('Y-m-01', $_GET['end']   . '-01');
    $startDate = (clone $endDate)->modify('-11 months');
} else {
    $endDate   = new DateTime('first day of this month');
    $startDate = (clone $endDate)->modify('-11 months');
}
$startYM = $startDate->format('Y-m');
$endYM   = $endDate  ->format('Y-m');

// ── 2) 보기 모드 정의 ──
$viewMode = $_GET['view'] ?? 'revenue';

// ── 3) 월 리스트 & 레이블 생성 ──
$months = $labels = [];
for ($dt = clone $startDate; $dt <= $endDate; $dt->modify('+1 month')) {
    $ym = $dt->format('Y-m');
    $months[] = $ym;
    $labels[] = $dt->format('Y') . '년 ' . $dt->format('n') . '월';
}

// ── 4) 매출 계산 함수: {bon_su, lc_su, inseung} → 원 단위 수주금액 ──
function calc_rev(array $row, array $units): int {
    $i = intval($row['inseung']);
    if      ($i <= 12) { $bonU = $units['bon_unit_12'];    $lcU = $units['lc_unit_12']; }
    elseif  ($i <= 17) { $bonU = $units['bon_unit_13to17']; $lcU = $units['lc_unit_13to17']; }
    else               { $bonU = $units['bon_unit_18'];    $lcU = $units['lc_unit_18']; }
    // ★ 여기에 ×1000 추가: ini 파일에 기록된 단가는 '천원' 단위이므로 원 단위로 환산합니다.
    return (
         intval($row['bon_su']) * $bonU
       + intval($row['lc_su'])  * $lcU
    ) * 1000;
}
 
include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title><?=$title_message?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <!-- Light & Subtle Theme CSS -->
  <link rel="stylesheet" href="../css/dashboard-style.css" type="text/css" />
  <style>
    /* ========================================= */
    /* Light & Subtle Theme - Monthly Ceiling Specific */
    /* ========================================= */

    body {
      background: var(--gradient-primary);
      font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      overflow-x: hidden;
    }

    .container-fluid {
      background: var(--gradient-primary);
      border: 1px solid var(--dashboard-border);
      border-radius: 12px;
      box-shadow: var(--dashboard-shadow);
      margin: 1rem;
      padding: 1.5rem;
      max-width: 100%;
      overflow-x: hidden;
    }

    /* ========================================= */
    /* Compact Stats Cards */
    /* ========================================= */
    .compact-stats-card {
      background: var(--gradient-primary);
      border: 1px solid var(--dashboard-border);
      border-radius: 12px;
      padding: 1rem;
      box-shadow: var(--dashboard-shadow);
      transition: all 0.2s ease;
      position: relative;
      overflow: hidden;
      margin-bottom: 0.75rem;
      border-top: 3px solid var(--dashboard-accent);
    }

    .compact-stats-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(100, 116, 139, 0.1);
    }

    /* ========================================= */
    /* Chart Containers */
    /* ========================================= */
    .compact-chart-container {
      background: var(--gradient-primary);
      border: 1px solid var(--dashboard-border);
      border-radius: 12px;
      padding: 0.9rem;
      box-shadow: var(--dashboard-shadow);
      margin-bottom: 1rem;
    }

    .chart-canvas {
      height: 280px !important;
      border-radius: 8px;
    }

    .chart-container {
      width: 100%;
      height: 280px;
      border-radius: 8px;
    }

    .compact-chart-title {
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--dashboard-text);
      margin-bottom: 0.5rem;
    }

    /* ========================================= */
    /* Typography */
    /* ========================================= */
    .compact-title {
      color: var(--dashboard-text);
      font-weight: 500;
      font-size: 1.17rem;  /* 0.9rem * 1.3 - 날짜 라벨 크기 조정 */
    }

    .compact-value {
      font-size: 1.25rem;
      font-weight: 700;
      background: var(--gradient-accent);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      line-height: 1;
      margin-bottom: 0.25rem;
    }

    .compact-subtitle {
      color: var(--dashboard-text-secondary);
      font-size: 0.8rem;
      font-weight: 500;
      line-height: 1.4;
    }

    /* ========================================= */
    /* Badge Components */
    /* ========================================= */
    .compact-badge {
      background: var(--gradient-accent);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 500;
      display: inline-block;
      margin: 0.25rem;
    }

    /* ========================================= */
    /* Filter Controls */
    /* ========================================= */
    .filter-container {
      background: var(--gradient-primary);
      border: 1px solid var(--dashboard-border);
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      box-shadow: var(--dashboard-shadow);
    }

    .form-control {
      border: 1px solid var(--dashboard-border);
      border-radius: 4px;
      font-size: 1.17rem;  /* 1.3배 크기 */
      padding: 0.65rem;    /* 패딩도 1.3배 */
    }

    .form-control:focus {
      border-color: var(--dashboard-accent);
      box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
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

    .form-check-input {
      width: 1.1rem;
      height: 1.1rem;
      margin-top: 0;
      vertical-align: middle;
      flex-shrink: 0;
    }

    .form-check-input:checked {
      background-color: var(--dashboard-accent);
      border-color: var(--dashboard-accent);
    }

    .form-check-label {
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--dashboard-text);
      margin-bottom: 0;
      line-height: 1.1rem;
      margin-left: 0.5rem;
      display: inline-flex;
      align-items: center;
    }

    /* ========================================= */
    /* Table Layout */
    /* ========================================= */
    .table-container {
      background: var(--gradient-primary);
      border: 1px solid var(--dashboard-border);
      border-radius: 12px;
      padding: 0.75rem;
      margin-left: 1rem;
      box-shadow: var(--dashboard-shadow);
    }

    .table {
      margin-bottom: 0;
      background: transparent;
    }

    .table th {
      background: var(--dashboard-secondary);
      color: var(--dashboard-text);
      font-size: 0.8rem;
      font-weight: 600;
      padding: 0.5rem;
      border: none;
      text-align: center;
    }

    .table td {
      padding: 0.5rem;
      font-size: 0.85rem;
      border-bottom: 1px solid var(--dashboard-border);
      transition: background-color 0.2s ease;
    }

    .table tr:hover td {
      background-color: var(--dashboard-hover);
    }

    .table-secondary {
      background: var(--gradient-accent) !important;
      color: white !important;
    }

    /* ========================================= */
    /* Card Components */
    /* ========================================= */
    .card {
      background: var(--gradient-primary);
      border: 1px solid var(--dashboard-border);
      border-radius: 12px;
      box-shadow: var(--dashboard-shadow);
      overflow: hidden;
      transition: all 0.2s ease;
      margin-bottom: 1rem;
    }

    .card:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
    }

    .card-header {
      background: var(--dashboard-secondary);
      color: var(--dashboard-text);
      padding: 0.75rem;
      text-align: center;
      font-size: 0.9rem;
      font-weight: 500;
      letter-spacing: 0.3px;
    }

    .card-body {
      padding: 1rem;
      background: transparent;
    }

    /* ========================================= */
    /* Page Title */
    /* ========================================= */
    h4.text-center {
      color: var(--dashboard-text);
      font-weight: 600;
      margin-bottom: 1.5rem;
      font-size: 1.3rem;
    }

    /* ========================================= */
    /* Loading Overlay */
    /* ========================================= */
    #loadingOverlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
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
      border-radius: 12px;
      padding: 2rem;
      box-shadow: var(--dashboard-shadow);
    }

    .spinner-border.text-primary {
      color: var(--dashboard-accent) !important;
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
    .row {
      margin: 0;
      padding: 0;
    }

    .col-md-6, .col-md-8, .col-md-4 {
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }

    @media (max-width: 768px) {
      .container-fluid {
        margin: 0.5rem;
        padding: 1rem;
      }

      .table-container {
        margin-left: 0;
        margin-top: 1rem;
      }

      .chart-canvas {
        height: 200px !important;
      }

      .col-md-6, .col-md-8, .col-md-4 {
        padding-left: 0.25rem;
        padding-right: 0.25rem;
      }

      .form-check-inline {
        margin-right: 0.5rem;
      }
    }
  </style>
</head>
<body>
<?php if($_GET['header']=='header'){
        include $_SERVER['DOCUMENT_ROOT'] . '/myheader.php';
} ?>
<div id="loadingOverlay">
  <div class="spinner">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <div class="mt-2">페이지를 생성중입니다...</div>
  </div>
</div>

<div class="container-fluid py-4">
  <h4 class="text-center mb-4"><?=$title_message?></h4>

  <!-- 필터: 기간 & 보기 모드 -->
  <div class="filter-container">
    <div class="row">
      <div class="col-md-6 d-flex align-items-center mb-2">
        <label class="me-2 compact-title">시작:</label>
        <input type="month" id="startYM" class="form-control me-4" style="width:210px;" value="<?=$startYM?>">
        <label class="me-2 compact-title">종료:</label>
        <input type="month" id="endYM" class="form-control" style="width:210px;" value="<?=$endYM?>">
      </div>
      <div class="col-md-6 d-flex align-items-center mb-2">
        <?php foreach ([
          'revenue'          => '수주금액 기준',
          'vendor'           => '발주처 수주순위',
          'topVendorMonthly' => '업체별 월별수주금액'
        ] as $val => $text): ?>
          <div class="form-check form-check-inline">
            <input class="form-check-input"
                   type="radio" name="view"
                   id="view<?=$val?>" value="<?=$val?>"
              <?= $viewMode === $val ? 'checked' : '' ?>>
            <label class="form-check-label" for="view<?=$val?>"><?=$text?></label>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <?php
  switch ($viewMode):

    // ── 1) 월별 총수주금액 ──
    case 'revenue':
      $data = [];
      foreach ($months as $ym) {
        $from = "$ym-01";
        $to   = date("Y-m-t", strtotime($from));
        $stmt = $pdo->prepare("
          SELECT inseung, bon_su, lc_su
          FROM {$DB}.ceiling
          WHERE workday BETWEEN :from AND :to
        ");
        $stmt->execute(['from'=>$from,'to'=>$to]);
        $rev = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $rev += calc_rev($row, $readIni);
        }
        $data[] = $rev;
      }
      ?>
      <div class="row">
        <div class="col-md-8">
          <div class="compact-chart-container">
            <div class="compact-chart-header">
              <div class="compact-chart-icon">🏠</div>
              <h6 class="compact-chart-title">조명천장/본천장 월별 수주금액 추이 (천원)</h6>
            </div>
            <div id="chartMain" class="chart-container"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="table-container">
            <table class="table table-sm table-bordered">
              <thead>
                <tr><th>월</th><th class="text-end">수주금액(천원)</th></tr>
              </thead>
              <tbody>
              <?php foreach($labels as $i=>$lbl): ?>
                <tr>
                  <td><?=$lbl?></td>
                  <td class="text-end"><?=number_format($data[$i]/1000)?></td>
                </tr>
              <?php endforeach; ?>
                <tr class="table-secondary fw-bold">
                  <td>합계</td>
                  <td class="text-end"><?=number_format(array_sum($data)/1000)?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php break;

    // ── 2) 발주처별 총합 순위 (Top20) ──
    case 'vendor':
      $vendorRev = [];
      $stmt = $pdo->prepare("
        SELECT inseung, bon_su, lc_su, secondord
        FROM {$DB}.ceiling
        WHERE workday BETWEEN :from AND :to
      ");
      $stmt->execute([
        'from'=>$startDate->format('Y-m-d'),
        'to'  =>$endDate  ->format('Y-m-t'),
      ]);
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rev    = calc_rev($row, $readIni);
        $vendor = trim($row['secondord']) ?: '기타';
        $vendorRev[$vendor] = ($vendorRev[$vendor] ?? 0) + $rev;
      }
      arsort($vendorRev);
      $top20  = array_slice($vendorRev, 0, 20, true);
      $labels2= array_keys($top20);
      $data2  = array_values($top20);
      ?>
      <div class="row">
        <div class="col-md-8">
          <div class="compact-chart-container">
            <div class="compact-chart-header">
              <div class="compact-chart-icon">🏆</div>
              <h6 class="compact-chart-title">조명천장/본천장 발주처별 수주금액 순위 (Top 20)</h6>
            </div>
            <div id="chartMain" class="chart-container"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="table-container">
            <table class="table table-sm table-bordered">
              <thead>
                <tr><th>순위</th><th>발주처</th><th class="text-end">수주금액(천원)</th></tr>
              </thead>
              <tbody>
              <?php $rank=1; foreach($top20 as $v=>$rev): ?>
                <tr>
                  <td><?=$rank++?></td>
                  <td><?=$v?></td>
                  <td class="text-end"><?=number_format($rev/1000)?></td>
                </tr>
              <?php endforeach; ?>
                <tr class="table-secondary fw-bold">
                  <td colspan="2">합계</td>
                  <td class="text-end"><?=number_format(array_sum($data2)/1000)?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php break;

    // ── 3) Top20 업체 월별 수주금액 ──
    case 'topVendorMonthly':
      // 3-1) Top20 선정
      $vendorRev = [];
      $stmt = $pdo->prepare("
        SELECT inseung, bon_su, lc_su, secondord
        FROM {$DB}.ceiling
        WHERE workday BETWEEN :from AND :to
      ");
      $stmt->execute([
        'from'=>$startDate->format('Y-m-d'),
        'to'  =>$endDate  ->format('Y-m-t'),
      ]);
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rev    = calc_rev($row, $readIni);
        $vendor = trim($row['secondord']) ?: '기타';
        $vendorRev[$vendor] = ($vendorRev[$vendor] ?? 0) + $rev;
      }
      arsort($vendorRev);
      $top20keys = array_slice(array_keys($vendorRev), 0, 20);

      // 3-2) 업체별 월별 집계
      $vendorMonthly = [];
      foreach ($top20keys as $vendor) {
        $arr = [];
        foreach ($months as $ym) {
          $from = "$ym-01";
          $to   = date("Y-m-t", strtotime($from));
          $st2 = $pdo->prepare("
            SELECT inseung, bon_su, lc_su
            FROM {$DB}.ceiling
            WHERE workday BETWEEN :from AND :to
              AND TRIM(secondord)=:vendor
          ");
          $st2->execute(['from'=>$from,'to'=>$to,'vendor'=>$vendor]);
          $sum = 0;
          while ($r = $st2->fetch(PDO::FETCH_ASSOC)) {
            $sum += calc_rev($r, $readIni);
          }
          $arr[] = $sum;
        }
        $vendorMonthly[$vendor] = $arr;
      }

      // 3-3) 출력
      foreach ($top20keys as $i => $vendor):
        $cid = "chartV{$i}";
      ?>
      <div class="card mb-4">
        <div class="card-header">
          <div class="d-flex align-items-center justify-content-between">
            <span><?=($i+1)?>위: <?=htmlspecialchars($vendor)?></span>
            <div class="compact-badge" style="margin: 0;">
              합계: <?=number_format(array_sum($vendorMonthly[$vendor])/1000)?>천원
            </div>
          </div>
        </div>
        <div class="card-body row">
          <div class="col-md-8">
            <div class="compact-chart-container">
              <div class="compact-chart-header">
                <div class="compact-chart-icon">🏠</div>
                <h6 class="compact-chart-title"><?=htmlspecialchars($vendor)?> 천장 월별 추이 (천원)</h6>
              </div>
              <div id="<?=$cid?>" class="chart-container"></div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="table-container">
              <table class="table table-sm table-bordered vendor-monthly-table">
                <thead>
                  <tr><th>월</th><th class="text-end">수주금액(천원)</th></tr>
                </thead>
                <tbody>
                <?php foreach($labels as $j=>$lbl): ?>
                  <tr>
                    <td><?=$lbl?></td>
                    <td class="text-end"><?=number_format(($vendorMonthly[$vendor][$j] ?? 0)/1000)?></td>
                  </tr>
                <?php endforeach; ?>
                  <tr class="table-secondary fw-bold">
                    <td>합계</td>
                    <td class="text-end"><?=number_format(array_sum($vendorMonthly[$vendor])/1000)?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach;
  endswitch;
  ?>

</div>

<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script>
  // 로딩 오버레이
  function showLoading(){ document.getElementById('loadingOverlay').style.display='block'; }
  function getParams(){
    return {
      start: document.getElementById('startYM').value,
      end:   document.getElementById('endYM').value,
      view:  document.querySelector('input[name=view]:checked').value
    };
  }
  function reloadPage(params){
    showLoading();
    const url = new URL(window.location);
    ['start','end','view'].forEach(p=>url.searchParams.delete(p));
    Object.entries(params).forEach(([k,v])=>url.searchParams.set(k,v));
    window.location = url;
  }
  document.getElementById('startYM').addEventListener('change',function(){
    const dt=new Date(this.value+'-01'); dt.setMonth(dt.getMonth()+11);
    document.getElementById('endYM').value = dt.toISOString().slice(0,7);
    reloadPage(getParams());
  });
  document.getElementById('endYM').addEventListener('change',function(){
    const dt=new Date(this.value+'-01'); dt.setMonth(dt.getMonth()-11);
    document.getElementById('startYM').value = dt.toISOString().slice(0,7);
    reloadPage(getParams());
  });
  document.querySelectorAll('input[name=view]').forEach(rb=>
    rb.addEventListener('change',()=>reloadPage(getParams()))
  );

  // 차트 그리기 - 컴팩트 블루 테마 적용
  <?php if (in_array($viewMode, ['revenue','vendor'])): ?>
  Highcharts.chart('chartMain', {
    chart: {
      type: 'column',
      backgroundColor: 'rgba(255, 255, 255, 0.9)'
    },
    title: {
      text: '<?= $viewMode==='revenue'? '조명천장/본천장 월별 수주금액 추이 (천원)' : '발주처별 수주금액(천원)' ?>',
      style: { fontSize: '14px', fontWeight: '600', color: '#01579b' }
    },
    xAxis: {
      categories: <?= json_encode($viewMode==='revenue'? $labels : $labels2, JSON_UNESCAPED_UNICODE) ?>,
      labels: { style: { fontSize: '10px', color: '#01579b' } }
    },
    yAxis: {
      title: { text: '천원', style: { fontSize: '10px', color: '#01579b' } },
      labels: { style: { fontSize: '10px', color: '#01579b' } }
    },
    series: [{
      name: '수주금액',
      data: <?= json_encode($viewMode==='revenue'? array_map(function($x){return $x/1000;}, $data) : array_map(function($x){return $x/1000;}, $data2)) ?>,
      color: '#0288d1'
    }],
    tooltip: {
      formatter: function() {
        return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' 천원</b>';
      }
    },
    legend: { enabled: false },
    credits: { enabled: false }
  });
  <?php elseif ($viewMode==='topVendorMonthly'): ?>
  <?php foreach($top20keys as $i=>$vendor): ?>
  Highcharts.chart('chartV<?=$i?>',
    {
    chart: {
      type: 'line',
      backgroundColor: 'rgba(255, 255, 255, 0.9)'
    },
    title: {
      text: '<?= addslashes($vendor) ?> 천장 월별 추이 (천원)',
      style: { fontSize: '12px', fontWeight: '600', color: '#01579b' }
    },
    xAxis: {
      categories: <?= json_encode($labels, JSON_UNESCAPED_UNICODE) ?>,
      labels: { style: { fontSize: '9px', color: '#01579b' } }
    },
    yAxis: {
      title: { text: '천원', style: { fontSize: '9px', color: '#01579b' } },
      labels: { style: { fontSize: '9px', color: '#01579b' } }
    },
    series: [{
      name: '수주금액',
      data: <?= json_encode(array_map(function($x){return $x/1000;}, $vendorMonthly[$vendor])) ?>,
      color: '#0288d1',
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


  $(document).ready(function(){    
   // 방문기록 남김
   var title = '<?php echo $title_message; ?>';
   // title = '품질방침/품질목표';
   // title = '절곡 ' + title ;
   saveMenuLog(title);
});	


</script>
</body>
</html>
