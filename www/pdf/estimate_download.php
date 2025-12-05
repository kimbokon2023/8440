<?php
// 로컬/서버 환경 설정
$is_local = (
    isset($_SERVER['HTTP_HOST']) &&
    (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)
);
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

// 메모리 & 타임아웃 (최상단)
@ini_set('memory_limit', '512M');
@set_time_limit(120);

// /pdf/estimate_download.php
require_once dirname(__DIR__) . '/session.php';
require_once dirname(__DIR__) . '/lib/mydb.php';

// Dompdf 오토로드 (_dompdf 격리 설치)
$dompdfAutoload = dirname(__DIR__) . '/_dompdf/vendor/autoload.php';
if (!is_file($dompdfAutoload)) {
    http_response_code(500);
    exit('Dompdf autoload를 찾을 수 없습니다. _dompdf/vendor/autoload.php 경로를 확인하세요.');
}
require_once $dompdfAutoload;

use Dompdf\Dompdf;
use Dompdf\Options;

// ------------------------- 입력값 -------------------------
$id = $_GET['id'] ?? 0;
$id = (int)$id;
$download = $_GET['download'] ?? 1; // 1=다운, 0=미리보기
$download = (int)$download;
$debug = $_GET['debug'] ?? 0;
$debug = (int)$debug;

if ($id <= 0) {
    http_response_code(400);
    exit('잘못된 id 파라미터입니다.');
}

// ------------------------- 데이터 조회 -------------------------
$pdo = db_connect();
// estimates 테이블 조회
$sql = "SELECT * FROM `estimates` WHERE id = :id AND is_deleted = 0";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$estimate = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$estimate) {
    http_response_code(404);
    exit('견적서를 찾을 수 없습니다.');
}

// JSON 파싱
$items = !empty($estimate['estimate_items']) ? json_decode($estimate['estimate_items'], true) : [];

// 기본 정보 매핑
$recipient = $estimate['contact_name'] ?? $estimate['supplier_name'] ?? '';
$site_name = $estimate['project_site'] ?? '';
$reference = $estimate['reference'] ?? '';
$quote_date = $estimate['issue_date'] ?? date('Y-m-d');
$signed_by = '소현철'; // 고정값 또는 설정에서 가져오기
$payment_account = '중소기업은행 339-084210-01-012 ㈜ 미래기업'; // 고정값
$estimate_no = $estimate['estimate_no'] ?? '';
$note = $estimate['note'] ?? '';

// 문서번호 자동 생성 (형식: YYYYMM-NN)
if (empty($estimate_no)) {
    // 견적서 생성일을 기준으로 연도와 월 추출
    $issue_date = $estimate['issue_date'] ?? date('Y-m-d');
    $date_parts = explode('-', $issue_date);
    $year = isset($date_parts[0]) ? $date_parts[0] : date('Y');
    $month = isset($date_parts[1]) ? $date_parts[1] : date('m');
    
    // 해당 연도/월에 생성된 견적서 중에서 현재 견적서의 순서 찾기
    // issue_date와 id를 기준으로 정렬하여 순서 결정
    $year_month = $year . '-' . $month;
    $sql_seq = "SELECT COUNT(*) + 1 as seq 
                FROM `estimates` 
                WHERE DATE_FORMAT(issue_date, '%Y-%m') = :year_month 
                AND is_deleted = 0
                AND (
                    issue_date < :issue_date 
                    OR (issue_date = :issue_date2 AND id <= :current_id)
                )";
    $stmt_seq = $pdo->prepare($sql_seq);
    $stmt_seq->bindValue(':year_month', $year_month, PDO::PARAM_STR);
    $stmt_seq->bindValue(':issue_date', $issue_date, PDO::PARAM_STR);
    $stmt_seq->bindValue(':issue_date2', $issue_date, PDO::PARAM_STR);
    $stmt_seq->bindValue(':current_id', $id, PDO::PARAM_INT);
    $stmt_seq->execute();
    $seq_result = $stmt_seq->fetch(PDO::FETCH_ASSOC);
    $sequence = $seq_result['seq'] ?? 1;
    
    // 문서번호 생성 (YYYYMM-NN 형식)
    $estimate_no = $year . $month . '-' . str_pad($sequence, 2, '0', STR_PAD_LEFT);
}

// 헬퍼 (PHP 7.3)
$esc = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
$nf = function ($v, $dec = 0) {
    $n = (float)str_replace(',', '', (string)$v);
    return number_format($n, $dec);
};

// 숫자를 한글로 변환하는 함수 (일금 형식)
$numToKorean = function($number) {
    $num = (int)round((float)str_replace(',', '', (string)$number));
    if ($num == 0) return '영';
    
    $han = ['', '일', '이', '삼', '사', '오', '육', '칠', '팔', '구'];
    $unit = ['', '십', '백', '천'];
    $unit2 = ['', '만', '억', '조'];
    
    $numStr = (string)$num;
    $len = strlen($numStr);
    $result = '';
    
    // 4자리씩 그룹으로 나누기 (뒤에서부터)
    $groupCount = (int)ceil($len / 4);
    for ($g = 0; $g < $groupCount; $g++) {
        $start = max(0, $len - ($g + 1) * 4);
        $end = $len - $g * 4;
        $groupStr = substr($numStr, $start, $end - $start);
        $groupNum = (int)$groupStr;
        
        if ($groupNum == 0) continue;
        
        $groupStr = str_pad($groupStr, 4, '0', STR_PAD_LEFT);
        $partResult = '';
        
        // 천, 백, 십, 일 자리 처리
        for ($i = 0; $i < 4; $i++) {
            $digit = (int)$groupStr[$i];
            if ($digit == 0) continue;
            
            if ($i == 0) {
                // 천의 자리
                if ($digit == 1) {
                    $partResult .= '천';
                } else {
                    $partResult .= $han[$digit] . '천';
                }
            } else if ($i == 1) {
                // 백의 자리
                if ($digit == 1) {
                    $partResult .= '백';
                } else {
                    $partResult .= $han[$digit] . '백';
                }
            } else if ($i == 2) {
                // 십의 자리
                if ($digit == 1) {
                    $partResult .= '십';
                } else {
                    $partResult .= $han[$digit] . '십';
                }
            } else {
                // 일의 자리
                $partResult .= $han[$digit];
            }
        }
        
        // 만, 억, 조 단위 추가
        if ($g > 0 && $partResult) {
            $result = $partResult . $unit2[$g] . $result;
        } else {
            $result = $partResult . $result;
        }
    }
    
    return $result ?: '영';
};

// ------------------------- 합계 계산 -------------------------
$total_supply = 0;
$total_tax = 0;

// 상품 합계
foreach ($items as $it) {
    $supply = (float)str_replace(',', '', $it['공급가액'] ?? $it['견적가액'] ?? $it['amount'] ?? 0);
    $tax = (float)str_replace(',', '', $it['세액'] ?? $it['tax_amount'] ?? 0);
    
    // 값이 없으면 계산
    if ($supply == 0) {
        $qty = (float)str_replace(',', '', $it['수량'] ?? $it['quantity'] ?? 0);
        $price = (float)str_replace(',', '', $it['단가'] ?? $it['unit_price'] ?? 0);
        $supply = $qty * $price;
    }
    if ($tax == 0) {
        $tax = $supply * 0.1;
    }
    
    $total_supply += $supply;
    $total_tax += $tax;
}

$grand_supply = $total_supply;
$grand_tax = $total_tax;
$grand_total = $grand_supply + $grand_tax;

// ------------------------- Dompdf 옵션 & 폰트 -------------------------
$BASE_DIR = __DIR__;                 // /pdf
$FONT_DIR = $BASE_DIR . '/asset/fonts';
$TMP_DIR = $BASE_DIR . '/tmp';
$FONT_CACHE = $TMP_DIR . '/font_cache';

@is_dir($TMP_DIR) || @mkdir($TMP_DIR, 0777, true);
@is_dir($FONT_CACHE) || @mkdir($FONT_CACHE, 0777, true);

$FONT_REG_PATH = realpath($FONT_DIR . '/NotoSansKR-Regular.ttf');
$FONT_REG_URL = $FONT_REG_PATH ? 'file://' . $FONT_REG_PATH : '';

if (!$FONT_REG_PATH || !is_readable($FONT_REG_PATH)) {
    // 폰트 파일이 없으면 기본 폰트 사용 시도 또는 에러 처리
    // 여기서는 에러 로그만 남기고 진행 (실제 환경에 따라 조정 필요)
    error_log("[Dompdf] Font file missing/unreadable: " . ($FONT_REG_PATH ?: 'not found'));
}

$options = new Options();
$options->set('defaultFont', 'NotoSansKR');
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('chroot', $BASE_DIR);
$options->set('tempDir', $TMP_DIR);
$options->set('fontCache', $FONT_CACHE);
$options->set('isFontSubsettingEnabled', true);
$options->set('logOutputFile', $BASE_DIR . '/dompdf.log');

$dompdf = new Dompdf($options);

// ------------------------- HTML (룩앤필: 천안캠퍼스쇼룸 스타일) -------------------------
$today = date('y.m.d');
// 파일명에 사용할 수 없는 특수문자만 제거
function sanitize_filename($str) {
    return preg_replace('/[\\\\\/:\*\?"<>\|]/u', '', $str);
}
$filename = '견적서_' .
            sanitize_filename($recipient ? $recipient : '수신처') . '_' .
            sanitize_filename($site_name ? $site_name : '현장') .
            '(' . $today . ').pdf';

ob_start();
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<style>
  /* 여백: 상/하 18mm, 좌/우 15mm (원본 문서와 유사) */
  @page { margin: 18mm 15mm 18mm 15mm; }

  /* 폰트 등록 (Regular만 사용) */
  @font-face {
    font-family: 'NotoSansKR';
    src: url('<?= $FONT_REG_URL ?>') format('truetype');
    font-weight: 400;
    font-style: normal;
  }

  /* 기본 타이포(원본계열: 작게, 촘촘하게) */
  html, body {
    font-family: 'NotoSansKR', DejaVu Sans, sans-serif;
    font-size: 10.2pt;           /* 본문 크기 */
    color: #111;
    line-height: 1.15;           /* 더 촘촘한 행간 (1.25 -> 1.15) */
  }

  .small { font-size: 9pt; }
  .xs    { font-size: 8.6pt; }
  .muted { color:#555; }

  .mb1 { margin-bottom: 1pt; }
  .mb2 { margin-bottom: 2pt; }
  .mb4 { margin-bottom: 4pt; }
  .mb6 { margin-bottom: 6pt; }
  .mb8 { margin-bottom: 8pt; }
  .mb10{ margin-bottom:10pt; }
  .mb12{ margin-bottom:12pt; }
  .mb14{ margin-bottom:14pt; }

  .text-right { text-align:right; }
  .text-left  { text-align:left; }
  .text-center{ text-align:center; }

  .fw-600 { font-weight: 600; }
  .fw-700 { font-weight: 700; }

  /* 헤더/푸터: 심플한 실선 */
  #header { position: fixed; top: -14mm; left: 0; right: 0; height: 12mm; }
  #footer { position: fixed; bottom: -14mm; left: 0; right: 0; height: 12mm; }
  .hr    { height:1px; background:#000; border:0; }

  /* 페이지 카운터 초기화 및 설정 */
  body {
    counter-reset: page 0;
    counter-reset: pages 1;
  }

  @page {
    counter-increment: page;
    counter-increment: pages;
  }

  .page-number:after { content: counter(page) " / " counter(pages); }

  /* 제목: 큰 고정폭 느낌(글자간 살짝 띄움) */
.doc-title {
    font-size: 23pt;             /* 원본 타이틀과 유사한 볼륨 */
    letter-spacing: 5px;
    word-spacing: 10px;
    text-align: center;
    font-weight: 700;
}
.doc-title2 {
    font-size: 14pt;             /* 원본 타이틀과 유사한 볼륨 */
    letter-spacing: 2px;
    word-spacing: 2px;
    text-align: center;
    font-weight: 700;
}
.doc-title3 {
    font-size: 14pt;             /* 원본 타이틀과 유사한 볼륨 */
    letter-spacing: 2px;
    word-spacing: 4px;
    text-align: center;
    font-weight: 600;
    position: relative;
    display: inline-block;
    vertical-align: middle;
    margin-left: 10px;
    margin-right: 350px;
    border-bottom: 1px solid #222;
    padding-bottom: 2px; /* 밑줄과 글자 사이 간격 */
}

  /* 회사 블록 (우측 정보) */
  .company-block { font-size: 10pt; line-height: 1.45; }

  /* 상단 그리드형 정보 박스 (테두리 얇고 검은색) */
  .grid {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 4pt;       /* 블록 사이 공간 */
  }
  .grid-cell {
    border: 1px solid #000;
    padding: 2pt 2pt;
    vertical-align: top;
  }

  /* 테이블: 얇은 검은 테두리, 아주 은은한 헤더 음영 */
  table.tbl { width:100%; border-collapse: collapse; font-size: 8.5pt; }
  .tbl th, .tbl td { border: 1px solid #000; padding: 0.5pt 2pt; font-size: 8.5pt; }
  .tbl thead th { background: #f2f2f2; font-weight:700; font-size: 8.5pt; }
  .tbl tfoot td { background: #f7f7f7; font-weight:700; font-size: 8.5pt; }

  .num { text-align:right; }

  /* 합계 박스: 테이블과 통일된 느낌 */
  .totals { width:100%; border:1px solid #000; border-collapse: collapse; font-size: 9pt; }
  .totals td { border:1px solid #000; padding:2pt 2pt; font-size: 9pt;  }
  .totals .label { width:28%; background:#f7f7f7; font-size: 9pt; }
  .totals .value { font-weight:700; font-size: 9pt; }

  /* 라벨 텍스트(좌측) */
  .label-k { display:inline-block; min-width:60pt; color:#333; vertical-align: baseline; }

</style>
</head>
<body>

<!-- 헤더 -->
<div id="header">
  <table style="width:100%; border-collapse:collapse;">
    <tr>
      <td class="small muted">문서번호: <?= $esc($estimate_no) ?></td>
      <td class="text-right small muted">생성일: <?= $esc($quote_date) ?></td>
    </tr>
  </table>
  <div class="hr"></div>
</div>

<!-- 푸터 -->
<div id="footer">
  <div class="hr"></div>
  <table style="width:100%; border-collapse:collapse;">
    <tr>
      <td class="xs muted">
        본사: 경기도 김포시 양촌읍 흥신로 220-27 &nbsp;&nbsp;
        <br> T. 031-983-8440 &nbsp;&nbsp;F. 031-982-8449
      </td>
      <td class="text-right xs"><span class="muted">Page</span> <span class="page-number"></span></td>
    </tr>
  </table>
</div>

<!-- 제목 -->
<div class="doc-title mb2">견    적    서</div>
<div class="doc-title2 ">E S T I M A T E</div>
<hr>
<div class="mb2" style="display: flex; align-items: center; width: 100%;">
  <span class="doc-title3 mb6" style="flex: 1 1 auto;">
    <?= $esc($recipient) ?> 貴下
  </span>
  <span style="white-space: nowrap; font-size: 11pt; flex: 0 0 auto; margin-left: auto; text-align: right;">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?= date('Y년 n월 j일', strtotime($quote_date)) ?>
  </span>
</div>

<!-- 상단: 좌측(수신/현장) + 우측(회사 정보) -->
<style>
  /* grid-cell이 정확히 50%씩 차지하도록 강제 */
  .grid-cell-half {
    width: 50% !important;
    max-width: 50% !important;
    min-width: 50% !important;
    vertical-align: top;
    box-sizing: border-box;
  }
  @media print {
    .grid-cell-half { width: 50% !important; max-width: 50% !important; min-width: 50% !important; }
  }
</style>
<table class="mb8" style="border:none; table-layout:fixed; width:100%;">
  <tr>
    <td class="grid-cell-half" style="border:none; padding-right:10px;">
      <span class="fw-700"> 참조 :  <?= $esc($reference) ?: '-' ?> </span>
      <br>
      <span class="fw-700"> 현장명 :  <?= $esc($site_name) ?: '-' ?> </span>
      <br>
      <br>
      <span> 별첨과 같이 견적합니다. </span>
    </td>
    <td class="grid-cell-half" style="border:none; ">
      <table style="border:none;">
        <tbody>
        <tr >
        <td>
          <div style="position: absolute; display: inline-block; top: 130px; right: 170px;">
              <!-- 로고 영역 공란 -->
              <img src="https://8440.co.kr/img/mirae_logo.png" style="width: 100%; max-width: 120px; height: auto; object-fit: contain;">
          </div>
        </td>
        </tr>
        <tr>
          <td  style="font-size:9pt; text-align:right; padding-right: 0;">
           <br>
            <div style="text-align:right; width:100%;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             주소 : 경기도 김포시 양촌읍 흥신로 220-27
            </div>
            <div style="text-align:right; width:100%;">
                T E L : 031 ) 983 - 8440 &nbsp;&nbsp;|&nbsp;&nbsp;
                F A X : 031 ) 982 - 8449
            </div>
            <?php
                // 이미지 파일 경로 확인 및 처리
                $stamp_paths = [
                    dirname(__DIR__) . '/img/miraestamp.png'
                ];

                $stamp_found = false;
                $stamp_html = '';

                foreach ($stamp_paths as $path) {
                    if (file_exists($path)) {
                        $stamp_found = true;
                        $file_ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        if ($file_ext === 'png') {
                            // PNG 파일을 Base64로 인코딩하여 임베딩
                            $png_content = file_get_contents($path);
                            $base64_png = base64_encode($png_content);
                            // 도장 위치 조정 (우측 끝에 붙도록)
                            $stamp_html = '<img src="data:image/png;base64,' . $base64_png . '" style="width: 40px; height: 40px; position: absolute; top: 230px; right: 0; z-index: 10;" alt="미래기업도장">';
                        }
                        break;
                    }
                }

                if ($stamp_found && $stamp_html) {
                    echo $stamp_html;
                } else {
                    // 기본 SVG 도장 생성 (우측 끝에 붙도록)
                    echo '<svg width="50" height="50" viewBox="0 0 50 50" style="position: absolute; top: -5px; right: 0; z-index: 10;"><circle cx="25" cy="25" r="23" fill="#D32F2F" stroke="#8B0000" stroke-width="2"/><text x="25" y="30" font-family="serif" font-size="10" fill="#8B0000" text-anchor="middle" font-weight="bold">도장</text></svg>';
                }
              ?>
            <div style="width:100%; margin-top: 5px; letter-spacing:0.1em; text-align:right; display:block; white-space: nowrap; font-size: 10pt;">
                <b>(주)미래기업</b> &nbsp;대표&nbsp; <?= $esc($signed_by) ?> &nbsp;(인)
            </div>
        </td>
        </tr>
        </tbody>
      </table>
    </td>
  </tr>
</table>

<!-- 합계 -->
<table class="totals mb12" style="width: 100%; border: 1px solid #000; border-collapse: collapse;">
  <tr>
    <td style="padding: 8px 12px; text-align: left; font-size: 11pt; font-weight: bold; border: 1px solid #000; background: #f7f7f7;">
      합계금액 : 일금 <?= $esc($numToKorean($grand_total)) ?> 원정 (₩<?= $nf($grand_total) ?>) (부가세포함)
    </td>
  </tr>
</table>

<!-- 상품 내역 -->
<div class="fw-700 mb6">세부 내역</div>
<table class="tbl mb12">
  <thead>
    <tr>
      <th style="width:5%;" >No.</th>
      <th style="width:30%;" >품목</th>
      <th style="width:15%;" >규격</th>
      <th style="width:10%;" >수량</th>
      <th style="width:10%;" >단위</th>
      <th style="width:10%;" >단가</th>
      <th style="width:10%;" >공급가액</th>
      <th style="width:10%;" >세액</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach ($items as $it):
        $name = $it['품목'] ?? $it['item_name'] ?? '';
        $spec = $it['규격'] ?? $it['spec'] ?? '';
        $qty = (float)str_replace(',', '', $it['수량'] ?? $it['quantity'] ?? 0);
        $unit = $it['단위'] ?? $it['unit'] ?? 'EA';
        $price = (float)str_replace(',', '', $it['단가'] ?? $it['unit_price'] ?? 0);
        $supply = (float)str_replace(',', '', $it['견적가액'] ?? $it['amount'] ?? 0);
        $tax = (float)str_replace(',', '', $it['세액'] ?? $it['tax_amount'] ?? 0);
        
        if ($supply == 0 && $qty > 0 && $price > 0) $supply = $qty * $price;
        if ($tax == 0 && $supply > 0) $tax = $supply * 0.1;
    ?>
    <tr>
      <td class="text-center"><?= $i++ ?></td>
      <td class="text-left"><?= $esc($name) ?></td>
      <td class="text-left"><?= $esc($spec) ?></td>
      <td class="num"><?= $nf($qty) ?></td>
      <td class="text-center"><?= $esc($unit) ?></td>
      <td class="num"><?= $nf($price) ?></td>
      <td class="num"><?= $nf($supply) ?></td>
      <td class="num"><?= $nf($tax) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php
    // 최소 5줄 채우기
    while ($i <= 5) {
        echo '<tr>';
        echo '<td class="text-center">' . $i++ . '</td>';
        echo '<td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
        echo '</tr>';
    }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="6" class="text-center">소계</td>
      <td class="num"><?= $nf($total_supply) ?></td>
      <td class="num"><?= $nf($total_tax) ?></td>
    </tr>
  </tfoot>
</table>

<!-- 비고 -->
<?php if (!empty($note)): ?>
  <div class="fw-700 mb6">비고</div>
  <div class="small" style="white-space:pre-line; border:1px solid #000; padding:7pt 8pt;"><?= nl2br($esc($note)) ?></div>
<?php endif; ?>

<!-- 면책 조항 -->
<div class="small text-muted" style="line-height:1.0; font-size: 8pt; margin-top: 20px;">
    <p style="font-weight: bold; font-size: 1.1em; margin: 0 0 2pt 0; color: #000;">계좌 : <?= $esc($payment_account) ?></p>
    <p style="margin:0;">1. 상기 견적의 금액은 이후 확정 시 금액이 변동될 수 있습니다.</p>
    <p style="margin:0;">2. 제품 현장 도착 후 즉시 현장 검수를 원칙으로 하며, 반품·교환 시 추가 운송비가 발생할 수 있습니다.</p>
    <p style="margin:0;">3. 견적서 내역 검토는 구매자의 의무이며, 미검토로 인한 배송 오류에 대한 책임은 구매자에게 있습니다.</p>
    <p style="margin:0;">4. 본 견적서로 계약서를 갈음하며, 납기 확정 시 견적 내용에 동의하는 것으로 간주합니다.</p>
</div>

</body>
</html>
<?php
$html = ob_get_clean();

// 디버그: HTML 미리보기
if ($debug) {
    header('Content-Type: text/html; charset=UTF-8');
    echo $html;
    exit;
}

try {
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    if (ob_get_length()) { ob_end_clean(); }

    $dompdf->stream($filename, array('Attachment' => $download ? true : false));
} catch (Throwable $e) {
    error_log('[Dompdf] Exception: ' . $e->getMessage());
    http_response_code(500);
    echo 'PDF 생성 중 오류가 발생했습니다.';
}
