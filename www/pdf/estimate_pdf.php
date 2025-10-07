<?php
// 메모리 & 타임아웃 (최상단)
@ini_set('memory_limit', '512M');
@set_time_limit(120);

// /pdf/estimate_pdf.php (PHP 7.3)
require_once getDocumentRoot() . '/session.php';
require_once getDocumentRoot() . '/lib/mydb.php';

// Dompdf 오토로드 (_dompdf 격리 설치)
$dompdfAutoload = getDocumentRoot() . '/_dompdf/vendor/autoload.php';
if (!is_file($dompdfAutoload)) {
    http_response_code(500);
    exit('Dompdf autoload를 찾을 수 없습니다. _dompdf/vendor/autoload.php 경로를 확인하세요.');
}
require_once $dompdfAutoload;

use Dompdf\Dompdf;
use Dompdf\Options;

// ------------------------- 입력값 -------------------------
$num      = isset($_GET['num']) ? (int)$_GET['num'] : 0;
$download = isset($_GET['download']) ? (int)$_GET['download'] : 1; // 1=다운, 0=미리보기
$debug    = isset($_GET['debug']) ? (int)$_GET['debug'] : 0;

if ($num <= 0) {
    http_response_code(400);
    exit('잘못된 num 파라미터입니다.');
}

// ------------------------- 데이터 조회 -------------------------
$pdo = db_connect();
$table = isset($DB) && $DB ? "{$DB}.phomi_estimate" : "phomi_estimate";

$sql = "SELECT * FROM {$table} WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':num', $num, PDO::PARAM_INT);
$stmt->execute();
$estimate = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$estimate) {
    http_response_code(404);
    exit('견적서를 찾을 수 없습니다.');
}

// JSON 파싱
$items                = !empty($estimate['items']) ? json_decode($estimate['items'], true) : [];
$other_costs          = !empty($estimate['other_costs']) ? json_decode($estimate['other_costs'], true) : [];

// 기본 정보
$recipient       = isset($estimate['recipient']) ? $estimate['recipient'] : '';
$division        = isset($estimate['division']) ? $estimate['division'] : '';
$site_name       = isset($estimate['site_name']) ? $estimate['site_name'] : '';
$quote_date   = isset($estimate['quote_date']) ? $estimate['quote_date'] : '';
$signed_by       = isset($estimate['signed_by']) ? $estimate['signed_by'] : '소현철';
$payment_account = isset($estimate['payment_account']) ? $estimate['payment_account'] : '중소기업은행 339-084210-01-012 ㈜ 미래기업';
$estimate_num    = isset($estimate['estimate_num']) ? $estimate['estimate_num'] : '';
$note            = isset($estimate['note']) ? $estimate['note'] : '';
$valid_until     = isset($estimate['valid_until']) ? $estimate['valid_until'] : '';

// 헬퍼 (PHP 7.3)
$esc = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
$nf  = function ($v, $dec = 0) {
    $n = (float)str_replace(',', '', (string)$v);
    return number_format($n, $dec);
};
$safeDate = function ($ymd) {
    if (!$ymd || $ymd === '0000-00-00') return '-';
    $ts = strtotime($ymd);
    return $ts ? date('Y.m.d', $ts) : '-';
};

// ------------------------- 합계 계산 -------------------------
$total_supply = 0; $total_tax = 0;
$other_supply = 0; $other_tax = 0;
$discount_supply = 0; $discount_tax = 0;
$discount_other_supply = 0; $discount_other_tax = 0;

// 상품 합계
foreach ($items as $it) {
    $area = isset($it['area']) ? (float)str_replace(',', '', $it['area']) : 0;
    $unit_price = isset($it['unit_price']) ? (float)str_replace(',', '', $it['unit_price']) : 0;
    $supply = $area * $unit_price;
    $tax = $supply * 0.1;
    $total_supply += $supply;
    $total_tax += $tax;
}

// 기타비용 합계
foreach ($other_costs as $c) {
    $qty   = isset($c['quantity']) ? (float)str_replace(',', '', $c['quantity']) : 0;
    $price = isset($c['unit_price']) ? (float)str_replace(',', '', $c['unit_price']) : 0;
    $unit  = isset($c['unit']) ? $c['unit'] : '';
    if (($unit === '㎡' || $unit === 'm²') && $qty > 0) {
        $supply = ($qty > 28) ? ($qty * $price) : $price;
    } else {
        $supply = $qty * $price;
    }
    $tax = $supply * 0.1;
    $other_supply += $supply;
    $other_tax += $tax;
}

// 할인(상품)
foreach ($discount_items as $it) {
    $supply = isset($it['supply_amount']) ? (float)str_replace(',', '', $it['supply_amount']) : 0;
    $tax    = isset($it['tax_amount'])    ? (float)str_replace(',', '', $it['tax_amount'])    : 0;
    $discount_supply -= $supply;
    $discount_tax    -= $tax;
}
// 할인(기타비용)
foreach ($discount_other_costs as $c) {
    $supply = isset($c['supply_amount']) ? (float)str_replace(',', '', $c['supply_amount']) : 0;
    $tax    = isset($c['tax_amount'])    ? (float)str_replace(',', '', $c['tax_amount'])    : 0;
    $discount_other_supply -= $supply;
    $discount_other_tax    -= $tax;
}

$grand_supply = $total_supply + $other_supply + $discount_supply + $discount_other_supply;
$grand_tax    = $total_tax + $other_tax + $discount_tax + $discount_other_tax;
$grand_total  = $grand_supply + $grand_tax;

// ------------------------- Dompdf 옵션 & 폰트 -------------------------
$BASE_DIR   = __DIR__;                 // /pdf
$FONT_DIR   = $BASE_DIR . '/asset/fonts';
$TMP_DIR    = $BASE_DIR . '/tmp';
$FONT_CACHE = $TMP_DIR . '/font_cache';

@is_dir($TMP_DIR)    || @mkdir($TMP_DIR,    0777, true);
@is_dir($FONT_CACHE) || @mkdir($FONT_CACHE, 0777, true);

$FONT_REG_PATH = realpath($FONT_DIR . '/NotoSansKR-Regular.ttf');
$FONT_REG_URL  = $FONT_REG_PATH ? 'file://' . $FONT_REG_PATH : '';

if (!$FONT_REG_PATH || !is_readable($FONT_REG_PATH)) {
    error_log("[Dompdf] Font file missing/unreadable: " . ($FONT_REG_PATH ?: 'not found'));
    http_response_code(500);
    exit("한글 폰트를 읽을 수 없습니다. /pdf/asset/fonts/NotoSansKR-Regular.ttf");
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
$today    = date('y.m.d');
// 파일명에 사용할 수 없는 특수문자만 제거 (윈도우 기준: \ / : * ? " < > |)
function sanitize_filename($str) {
    return preg_replace('/[\\\\\/:\*\?"<>\|]/u', '', $str);
}
$filename = '포미스톤견적서_' .
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
    line-height: 1.25;           /* 더 촘촘한 행간 */
  }

  .small { font-size: 9pt; }
  .xs    { font-size: 8.6pt; }
  .muted { color:#555; }

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
    padding: 5pt 5pt;
    vertical-align: top;
  }

  /* 테이블: 얇은 검은 테두리, 아주 은은한 헤더 음영 */
  table.tbl { width:100%; border-collapse: collapse; font-size: 8.5pt; }
  .tbl th, .tbl td { border: 1px solid #000; padding: 2pt 2pt; font-size: 8.5pt; }
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
      <td class="small muted">문서번호: <?= $esc($num) ?></td>
      <td class="text-right small muted">생성일: <?= date('Y-m-d') ?></td>
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
        본사: 경기도 김포시 양촌읍 흥신로 220-27 &nbsp;&nbsp;|&nbsp;&nbsp; 전시장: 인천 서구 중봉대로 393번길 16 홈씨씨 2층 포미스톤
        <br> T. 031-983-8440 &nbsp;&nbsp;F. 031-982-8449
      </td>
      <td class="text-right xs"><span class="muted">Page</span> <span class="page-number"></span></td>
    </tr>
  </table>
</div>

<!-- 제목 -->
<div class="doc-title mb2">견      적      서</div>
<div class="doc-title2 "> &nbsp; &nbsp; ESTIMATE</div>
<hr>
<div class="mb2" style="display:flex; justify-content:space-between; align-items:center; width:100%;">
  <span class="doc-title3 mb6" style="flex:0 1 auto;">
    <?= $esc($recipient) ?> 貴下
  </span>
  <span class="mb2" style="white-space:nowrap; font-size:12pt;">
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
      <span class="fw-700"> 현장명 :  <?= $esc($site_name) ?: '-' ?> </span>
      <br>
      <br>
      <br>
      <span> 별첨과 같이 견적합니다. </span>
    </td>
    <td class="grid-cell-half" style="border:none; ">
      <table style="border:none; font-size:9pt; ">
        <tbody>
        <tr>       
        <td>
          <div style="position: relative; display: inline-block;">
              <?php 
                // 포미스톤 미래기업 이미지 200x55 크기
                $stamp_paths = [
                    dirname(__FILE__) . '/../img/phomimirae1.jpg'
                ];
                
                $stamp_found = false;
                $stamp_html = '';
                
                foreach ($stamp_paths as $path) {
                    if (file_exists($path)) {
                        $stamp_found = true;
                        $file_ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        if ($file_ext === 'jpg') {
                            // PNG 파일을 Base64로 인코딩하여 임베딩
                            $jpg_content = file_get_contents($path);
                            $base64_jpg = base64_encode($jpg_content);
                            $stamp_html = '<img src="data:image/jpg;base64,' . $base64_jpg . '" style="width: 283px; height: 55px; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); z-index: 10;" alt="포미스톤미래기업">';
                        }
                        break;
                    }
                }
                
                if ($stamp_found && $stamp_html) {
                    echo '<!-- 이미지 파일을 찾았습니다: ' . basename($path) . ' -->';
                    echo $stamp_html;
                } 
              ?>              
          </div> 
        </td>                    
        </tr>
        <tr>                  
          <td colspan="4" style="font-size:8pt;">
           <br>  
             본사: 경기도 김포시 양촌읍 흥신로 220-27 
            <br>
             전시장: 인천 서구 중봉대로 393번길 16 홈씨씨 2층 포미스톤
            <br>
                T E L : 031 ) 983 - 8440 &nbsp;&nbsp;|&nbsp;&nbsp;    
                F A X : 031 ) 982 - 8449
            <br>
            <?php
                // 이미지 파일 경로 확인 및 처리
                $stamp_paths = [
                    dirname(__FILE__) . '/../img/miraestamp.png'
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
                            $stamp_html = '<img src="data:image/png;base64,' . $base64_png . '" style="width: 45px; height: 45px; position: absolute; top: 275px; left: 89%; transform: translateX(-50%); z-index: 10;" alt="미래기업도장">';
                        }
                        break;
                    }
                }
                
                if ($stamp_found && $stamp_html) {
                    echo '<!-- 이미지 파일을 찾았습니다: ' . basename($path) . ' -->';
                    echo $stamp_html;
                } else {
                    echo '<!-- 이미지 파일을 찾을 수 없습니다. SVG 파일을 기본으로 사용합니다. -->';
                    // 기본 SVG 도장 생성
                    echo '<svg width="50" height="50" viewBox="0 0 50 50" style="position: absolute; top: -5px; left: 50%; transform: translateX(-50%); z-index: 10;"><circle cx="25" cy="25" r="23" fill="#D32F2F" stroke="#8B0000" stroke-width="2"/><text x="25" y="30" font-family="serif" font-size="10" fill="#8B0000" text-anchor="middle" font-weight="bold">도장</text></svg>';
                }
              ?>            
            <div style="width:95%; margin-left:5px; letter-spacing:0.6em; text-align:left; display:block;">
                <b>(주) 미래기업</b> 대표 <?= $esc($signed_by) ?> (인)
            </div>
        </td>
        </tr>
        </tbody>
      </table>
    </td>
  </tr>
</table>

<!-- 합계 -->
<table class="totals mb12">
  <tr>
    <td class="label">합계금액 (공급가액)</td>
    <td class="value num">₩ <?= $nf($grand_supply) ?></td>
    <td class="label">합계금액 (부가세포함)</td>
    <td class="value num">₩ <?= $nf($grand_total) ?></td>
  </tr>
</table>

<!-- 상품 내역 -->
<div class="fw-700 mb6">상품 내역</div>
<table class="tbl mb12">
  <thead>
    <tr>
      <th style="width:5%;">No.</th>
      <th style="width:25%;">상품명</th>
      <th style="width:12%;">규격</th>
      <th style="width:10%;">분류</th>
      <th style="width:7%;"  class="num">수량</th>
      <th style="width:8%;"  class="num">m²</th>
      <th style="width:10%;" class="num">단가</th>
      <th style="width:10%;" class="num">공급가액</th>
      <th style="width:10%;" class="num">세액</th>
      <th style="width:15%;" >비고</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach ($items as $it):
        $prodcode = isset($it['product_code']) ? $it['product_code'] : '';
        $spec      = isset($it['specification']) ? $it['specification'] : '';
        $size      = isset($it['size']) ? $it['size'] : '';
        $qty       = (float)(isset($it['quantity']) ? $it['quantity'] : 0);
        $remarks      = isset($it['remarks']) ? $it['remarks'] : '';
        $area      = (float)str_replace(',', '', isset($it['area']) ? $it['area'] : 0);
        $up        = (float)str_replace(',', '', isset($it['unit_price']) ? $it['unit_price'] : 0);
        $supply    = $area * $up; $tax = $supply * 0.1;

        $display = isset($it['product_name']) ? $it['product_name'] : '';
        if (!$display && $prodcode) {
            try {
                $upTable = (isset($DB) && $DB) ? "{$DB}.phomi_unitprice" : "phomi_unitprice";
                $ps = $pdo->prepare("SELECT texture_kor, design_kor FROM {$upTable} WHERE prodcode = :p");
                $ps->execute(array(':p' => $prodcode));
                $pinfo = $ps->fetch(PDO::FETCH_ASSOC);
                $display = $pinfo ? ($pinfo['texture_kor'].' '.$pinfo['design_kor']) : $prodcode;
            } catch (Exception $e) {
                $display = $prodcode;
            }
        }
    ?>
    <tr>
      <td class="text-center"><?= $i++ ?></td>
      <td class="text-left"><?php
        // $display에서 한글만 추출 (공백은 유지)
        $kor_only = preg_replace('/[^가-힣\s]/u', '', $display);
        echo $esc(trim($kor_only));
      ?></td>
      <td class="text-left"><?= $esc($spec) ?></td>
      <td class="text-center"><?= $esc($size) ?></td>
      <td class="num"><?= $nf($qty) ?></td>
      <td class="num"><?= $nf($area, 2) ?></td>
      <td class="num"><?= $nf($up) ?></td>
      <td class="num"><?= $nf($supply) ?></td>
      <td class="num"><?= $nf($tax) ?></td>
      <td class="text-left"><?= $esc($remarks) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="7" class="text-right">소계</td>
      <td class="num"><?= $nf($total_supply) ?></td>
      <td class="num"><?= $nf($total_tax) ?></td>
      <td ></td>
    </tr>
  </tfoot>
</table>

<!-- 기타비용 -->
<div class="fw-700 mb6">기타 비용 (부자재 및 인건비 등)</div>
<table class="tbl mb12">
  <thead>
    <tr>
      <th style="width:12%;">구분</th>
      <th style="width:26%;">항목</th>
      <th style="width:10%;">단위</th>
      <th style="width:10%;" class="num">수량</th>
      <th style="width:12%;" class="num">단가</th>
      <th style="width:15%;" class="num">공급가액</th>
      <th style="width:15%;" class="num">세액</th>
      <th style="width:15%;" >비고</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $os=0;$ot=0; 
    foreach ($other_costs as $c):
        $cat   = isset($c['category']) ? $c['category'] : '';
        // $cat이 공백이면 행을 출력하지 않음
        if (trim($cat) === '') continue;
        $item  = isset($c['item']) ? $c['item'] : '';
        $unit  = isset($c['unit']) ? $c['unit'] : '';
        $remarks    = isset($c['remarks']) ? $c['remarks'] : '';
        $qty   = (float)str_replace(',', '', isset($c['quantity']) ? $c['quantity'] : 0);
        $up    = (float)str_replace(',', '', isset($c['unit_price']) ? $c['unit_price'] : 0);
        if (($unit === '㎡' || $unit === 'm²') && $qty > 0) {
            $supply = ($qty > 28) ? ($qty * $up) : $up;
        } else {
            $supply = $qty * $up;
        }
        $tax = $supply * 0.1; $os += $supply; $ot += $tax;
    ?>
    <tr>
      <td class="text-center"><?= $esc($cat) ?></td>
      <td class="text-left"><?= $esc($item) ?></td>
      <td class="text-center"><?= $esc($unit) ?></td>
      <td class="num"><?= $qty ? $nf($qty) : '' ?></td>
      <td class="num"><?= $qty ? $nf($up) : '' ?></td>
      <td class="num"><?= $qty ? $nf($supply) : '' ?></td>
      <td class="num"><?= $qty ? $nf($tax) : '' ?></td>
      <td class="text-left"><?= $esc($remarks) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" class="text-right">소계</td>
      <td class="num"><?= $nf($os) ?></td>
      <td class="num"><?= $nf($ot) ?></td>
      <td ></td>
    </tr>
  </tfoot>
</table>

<!-- 비고 -->
<?php if (!empty($note)): ?>
  <div class="fw-700 mb6">비고</div>
  <div class="small" style="white-space:pre-line; border:1px solid #000; padding:7pt 8pt;"><?= nl2br($esc($note)) ?></div>
<?php endif; ?>


<!-- 면책 조항 -->
<div class="small text-muted" style="line-height:0.9;">
    <p style="font-weight: bold; font-size: 1.15em;">계좌 : <?= $esc($payment_account) ?></p>
    <p>1. 상기 견적의 금액은 이후 확정 시 금액이 변동될 수 있습니다.</p>
    <p>2. 제품 현장 도착 후 즉시 현장 검수를 원칙으로 하며, 반품·교환 시 추가 운송비가 발생할 수 있습니다.</p>
    <p>3. 견적서 내역 검토는 구매자의 의무이며, 미검토로 인한 배송 오류에 대한 책임은 구매자에게 있습니다.</p>
    <p>4. 양중 시 찍힘이 발생할 수 있으니 취급에 주의하시기 바랍니다.</p>
    <p>5. 본 견적서로 계약서를 갈음하며, 납기 확정 시 견적 내용에 동의하는 것으로 간주합니다.</p>
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
