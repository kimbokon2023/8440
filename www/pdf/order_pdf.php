<?php
// =========================================
// /pdf/order_pdf.php   (PHP 7.3 compatible)
// 룩앤필: 천안캠퍼스쇼룸.pdf 스타일
// - asset/fonts/NotoSansKR-Regular.ttf 사용(필수)
// - _dompdf 격리 설치 (/_dompdf/vendor/autoload.php)
// - GET: num, download(1|0), debug(1|0)
// =========================================

// 메모리/타임아웃
@ini_set('memory_limit', '512M');
@set_time_limit(120);

// 공통 include
require_once getDocumentRoot() . '/session.php';
require_once getDocumentRoot() . '/lib/mydb.php';

// Dompdf 오토로드
$dompdfAutoload = getDocumentRoot() . '/_dompdf/vendor/autoload.php';
if (!is_file($dompdfAutoload)) {
    http_response_code(500);
    exit('Dompdf autoload를 찾을 수 없습니다. _dompdf/vendor/autoload.php 경로를 확인하세요.');
}
require_once $dompdfAutoload;

use Dompdf\Dompdf;
use Dompdf\Options;

// -------------------------
// 입력값
// -------------------------
$num      = isset($_GET['num']) ? (int)$_GET['num'] : 0;
$download = isset($_GET['download']) ? (int)$_GET['download'] : 1; // 1=다운로드, 0=브라우저
$debug    = isset($_GET['debug']) ? (int)$_GET['debug'] : 0;

if ($num <= 0) {
    http_response_code(400);
    exit('잘못된 num 파라미터입니다.');
}

// -------------------------
// 데이터 조회
// -------------------------
$pdo   = db_connect();
$table = isset($DB) && $DB ? "{$DB}.phomi_order" : "phomi_order";

$sql = "SELECT * FROM {$table} WHERE num = :num AND (is_deleted IS NULL OR is_deleted = 'N')";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':num', $num, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    http_response_code(404);
    exit('수주서를 찾을 수 없습니다.');
}

// JSON 필드 파싱
$items                = !empty($order['items']) ? json_decode($order['items'], true) : [];
$other_costs          = !empty($order['other_costs']) ? json_decode($order['other_costs'], true) : [];
$discount_items       = !empty($order['discount_items']) ? json_decode($order['discount_items'], true) : [];
$discount_other_costs = !empty($order['discount_other_costs']) ? json_decode($order['discount_other_costs'], true) : [];

// 기본 정보
$recipient       = isset($order['recipient']) ? $order['recipient'] : '';
$division        = isset($order['division']) ? $order['division'] : '';
$site_name       = isset($order['site_name']) ? $order['site_name'] : '';
$order_date      = isset($order['order_date']) ? $order['order_date'] : '';
$signed_by       = isset($order['signed_by']) ? $order['signed_by'] : '소현철';
$payment_account = isset($order['payment_account']) ? $order['payment_account'] : '중소기업은행 339-084210-01-012 ㈜ 미래기업';
$estimate_num    = isset($order['estimate_num']) ? $order['estimate_num'] : '';
$note            = isset($order['note']) ? $order['note'] : '';

// helpers (PHP 7.3 OK)
$esc = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
$nf  = function ($v, $dec = 0) {
    $n = (float)str_replace(',', '', (string)$v);
    return number_format($n, $dec);
};
$kdate = function ($ymd) {
    if (!$ymd || $ymd === '0000-00-00') return '-';
    $ts = strtotime($ymd);
    return $ts ? (date('Y', $ts).'년 '.date('n', $ts).'월 '.date('j', $ts).'일') : '-';
};
$ymdDot = function ($ymd) {
    if (!$ymd || $ymd === '0000-00-00') return '-';
    $ts = strtotime($ymd);
    return $ts ? date('Y.m.d', $ts) : '-';
};

// -------------------------
// 합계 계산(시스템 로직 준수)
// -------------------------
$total_supply = 0; $total_tax = 0;
$other_supply = 0; $other_tax = 0;
$discount_supply = 0; $discount_tax = 0;
$discount_other_supply = 0; $discount_other_tax = 0;

// 상품 합계: 금액 = (area * unit_price), 세액 10%
foreach ($items as $it) {
    $area = isset($it['area']) ? (float)str_replace(',', '', $it['area']) : 0;
    $unit_price = isset($it['unit_price']) ? (float)str_replace(',', '', $it['unit_price']) : 0;
    $s = $area * $unit_price;
    $t = $s * 0.1;
    $total_supply += $s; $total_tax += $t;
}

// 기타비용 합계: ㎡ 단위 특례 반영
foreach ($other_costs as $c) {
    $qty   = isset($c['quantity']) ? (float)str_replace(',', '', $c['quantity']) : 0;
    $price = isset($c['unit_price']) ? (float)str_replace(',', '', $c['unit_price']) : 0;
    $unit  = isset($c['unit']) ? $c['unit'] : '';
    if (($unit === '㎡' || $unit === 'm²') && $qty > 0) {
        $s = ($qty > 28) ? ($qty * $price) : $price;
    } else {
        $s = $qty * $price;
    }
    $t = $s * 0.1;
    $other_supply += $s; $other_tax += $t;
}

// 할인 차감(상품)
foreach ($discount_items as $it) {
    $s = isset($it['supply_amount']) ? (float)str_replace(',', '', $it['supply_amount']) : 0;
    $t = isset($it['tax_amount'])    ? (float)str_replace(',', '', $it['tax_amount'])    : 0;
    $discount_supply -= $s; $discount_tax -= $t;
}
// 할인 차감(기타)
foreach ($discount_other_costs as $c) {
    $s = isset($c['supply_amount']) ? (float)str_replace(',', '', $c['supply_amount']) : 0;
    $t = isset($c['tax_amount'])    ? (float)str_replace(',', '', $c['tax_amount'])    : 0;
    $discount_other_supply -= $s; $discount_other_tax -= $t;
}

$grand_supply = $total_supply + $other_supply + $discount_supply + $discount_other_supply;
$grand_tax    = $total_tax + $other_tax + $discount_tax + $discount_other_tax;
$grand_total  = $grand_supply + $grand_tax;

// -------------------------
// Dompdf 옵션 + 폰트 경로(절대경로: asset/fonts)
// -------------------------
$BASE_DIR   = __DIR__;                 // /pdf
$FONT_DIR   = $BASE_DIR . '/asset/fonts';
$TMP_DIR    = $BASE_DIR . '/tmp';
$FONT_CACHE = $TMP_DIR . '/font_cache';

@is_dir($TMP_DIR)    || @mkdir($TMP_DIR, 0777, true);
@is_dir($FONT_CACHE) || @mkdir($FONT_CACHE, 0777, true);

$FONT_REG_PATH = realpath($FONT_DIR . '/NotoSansKR-Regular.ttf');
$FONT_REG_URL  = $FONT_REG_PATH ? 'file://' . $FONT_REG_PATH : '';

if (!$FONT_REG_PATH || !is_readable($FONT_REG_PATH)) {
    error_log('[Dompdf] Missing font: '.$FONT_DIR.'/NotoSansKR-Regular.ttf');
    http_response_code(500);
    exit('한글 폰트를 읽을 수 없습니다. /pdf/asset/fonts/NotoSansKR-Regular.ttf');
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

// 파일명
$filename = '포미스톤수주서_'.
            preg_replace('/[\\\/:*?"<>|]/', '', $recipient ? $recipient : '수신처').'_'.
            preg_replace('/[\\\/:*?"<>|]/', '', $site_name ? $site_name : '현장').
            '('.date('y.m.d').').pdf';

// -------------------------
// HTML (천안캠퍼스쇼룸.pdf 스타일)
// -------------------------
ob_start();
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<style>
  @page        { margin: 20mm 14mm 18mm; }
  html, body   { font-family:'NotoSansKR', DejaVu Sans, sans-serif; color:#111; font-size:11px; }
  table        { border-collapse:collapse; width:100%; }
  .text-left   { text-align:left; }
  .text-right  { text-align:right; }
  .text-center { text-align:center; }
  .muted       { color:#6b7280; }
  .small       { font-size:10px; }
  .lh-tight    { line-height:1.3; }
  .mb6{margin-bottom:6px;} .mb8{margin-bottom:8px;} .mb12{margin-bottom:12px;} .mb16{margin-bottom:16px;}
  .fw700{font-weight:700;} .fw600{font-weight:600;}
  .rule { height:2px; background:#111; margin:6px 0 10px; }

  /* 헤더/푸터 */
  #header { position: fixed; top: -14mm; left:0; right:0; height: 10mm; }
  #footer { position: fixed; bottom: -14mm; left:0; right:0; height: 10mm; }
  .page:after { content: counter(page) " / " counter(pages); }

  /* 좌측 회사정보 박스 + 우측 타이틀 */
  .topgrid { width:100%; }
  .leftbox {
      border:1px solid #000; padding:8px 10px; vertical-align:top; width:58%;
      letter-spacing:0.5px;
  }
  .rightbox { vertical-align:top; width:42%; padding-left:10px; }
  .title-ko { font-size:20px; letter-spacing:6px; text-align:center; }
  .title-en { text-align:center; letter-spacing:2px; font-size:10px; margin-top:2px; }

  /* 큰 합계금액 */
  .grand {
      border:1px solid #000; padding:8px 10px; font-size:14px; font-weight:700;
  }

  /* 테이블 공통 */
  .tbl th, .tbl td { border:1px solid #000; padding:6px 6px; }
  .tbl thead th    { background:#f3f4f6; }

  /* (인) 박스는 간단한 가짜도장 칸 */
  .signbox {
      border:1px solid #000; width:28mm; height:18mm; display:inline-block; vertical-align:middle;
      margin-left:6px;
  }
</style>
</head>
<body>

<!-- 헤더 -->
<div id="header">
  <div class="rule"></div>
</div>

<!-- 푸터 -->
<div id="footer">
  <div class="rule"></div>
  <table>
    <tr>
      <td class="small muted">
        경기도 김포시 양촌읍 흥신로 220-27  ·  TEL 031-983-8440  ·  FAX 031-982-8449
      </td>
      <td class="text-right small"><span class="muted">페이지</span> <span class="page"></span></td>
    </tr>
  </table>
</div>

<!-- 상단 레터헤드 -->
<table class="topgrid mb12">
  <tr>
    <!-- 좌측 회사정보 박스 -->
    <td class="leftbox lh-tight">
      <div class="fw700" style="font-size:16px;">미  래  기  업</div>
      <div class="small">경기도 김포시 양촌읍 흥신로220-27</div>
      <table style="margin-top:6px;">
        <tr><td style="width:38mm;">T&nbsp;E&nbsp;L :</td><td>031) 983-8440</td></tr>
        <tr><td>F&nbsp;A&nbsp;X :</td><td>031) 982-8449</td></tr>
        <tr><td>참&nbsp;&nbsp;&nbsp;&nbsp;조 :</td><td>&nbsp;</td></tr>
        <tr><td>현 장 명 :</td><td><?= $esc($site_name) ?: '&nbsp;' ?></td></tr>
      </table>
    </td>

    <!-- 우측 타이틀 -->
    <td class="rightbox">
      <div class="title-ko fw700">견    적    서</div>
      <div class="title-en">ESTIMATE</div>

      <div style="margin-top:14px;">
        <div class="text-right"><?= $esc($recipient) ?: '' ?> <?= $recipient ? '貴下' : '' ?> <?= $esc($kdate($order_date)) ?></div>
      </div>

      <div style="margin-top:10px;">
        <table>
          <tr>
            <td style="width:22mm;">대&nbsp;&nbsp;&nbsp;&nbsp;표</td>
            <td class="fw700"><?= $esc($signed_by) ?></td>
            <td class="signbox text-center small">( 인 )</td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>

<!-- 합계금액 박스 -->
<table class="mb12">
  <tr>
    <td class="grand">합계금액 ₩<?= $nf($grand_total) ?></td>
    <td class="text-right small">견적번호 : <?= $esc($estimate_num) ?: '-' ?> &nbsp;&nbsp;|&nbsp;&nbsp; 계정 : <?= $esc($payment_account) ?></td>
  </tr>
</table>

<!-- 상세 표 (품명/규격/수량/단위/단가/금액/비고) -->
<table class="tbl mb16">
  <thead>
    <tr>
      <th style="width:5%;">No.</th>
      <th style="width:28%;">품명</th>
      <th style="width:18%;">규격</th>
      <th style="width:8%;">수량</th>
      <th style="width:7%;">단위</th>
      <th style="width:12%;">단가</th>
      <th style="width:14%;">금액</th>
      <th style="width:8%;">비고</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $rowno = 1;

      // 상품 rows
      foreach ($items as $it) {
        $prodcode = isset($it['product_code']) ? $it['product_code'] : '';
        $spec      = isset($it['specification']) ? $it['specification'] : '';
        $size      = isset($it['size']) ? $it['size'] : '';
        $qty_raw   = isset($it['area']) ? $it['area'] : (isset($it['quantity']) ? $it['quantity'] : 0); // 표시용 수량 = area 우선
        $qty       = (float)str_replace(',', '', $qty_raw);
        $unit      = 'm²';
        $unit_price= (float)str_replace(',', '', isset($it['unit_price']) ? $it['unit_price'] : 0);
        $amount    = $qty * $unit_price;
        $remarks   = isset($it['remarks']) ? $it['remarks'] : '';

        // 보기용 품명 (DB 조회 보조)
        $display = isset($it['product_name']) ? $it['product_name'] : '';
        if (!$display && $prodcode) {
            try {
                $upTable = (isset($DB) && $DB) ? "{$DB}.phomi_unitprice" : "phomi_unitprice";
                $ps = $pdo->prepare("SELECT texture_kor, design_kor FROM {$upTable} WHERE prodcode = :p");
                $ps->execute(array(':p'=>$prodcode));
                $pinfo = $ps->fetch(PDO::FETCH_ASSOC);
                $display = $pinfo ? ($pinfo['texture_kor'].' '.$pinfo['design_kor']) : $prodcode;
            } catch (Exception $e) {
                $display = $prodcode;
            }
        }
    ?>
    <tr>
      <td class="text-center"><?= $rowno++ ?></td>
      <td class="text-left"><?= $esc($display) ?></td>
      <td class="text-left"><?= $esc($spec) ?></td>
      <td class="text-right"><?= $qty ? $nf($qty, 2) : '' ?></td>
      <td class="text-center"><?= $unit ?></td>
      <td class="text-right"><?= $unit_price ? $nf($unit_price) : '' ?></td>
      <td class="text-right"><?= $amount ? $nf($amount) : '' ?></td>
      <td class="text-center"><?= $esc($remarks) ?></td>
    </tr>
    <?php } ?>

    <!-- 기타비용 구분 라벨 -->
    <?php if (!empty($other_costs)) { ?>
    <tr>
      <td class="text-center fw700" colspan="8" style="background:#fafafa;">기타비용</td>
    </tr>
    <?php } ?>

    <!-- 기타비용 rows -->
    <?php
      foreach ($other_costs as $c) {
        $cat   = isset($c['category']) ? $c['category'] : '';
        $item  = isset($c['item']) ? $c['item'] : '';
        $unit  = isset($c['unit']) ? $c['unit'] : '';
        $qty   = (float)str_replace(',', '', isset($c['quantity']) ? $c['quantity'] : 0);
        $up    = (float)str_replace(',', '', isset($c['unit_price']) ? $c['unit_price'] : 0);
        if (($unit === '㎡' || $unit === 'm²') && $qty > 0) {
            $amount = ($qty > 28) ? ($qty * $up) : $up;
        } else {
            $amount = $qty * $up;
        }
        $remarks = isset($c['remarks']) ? $c['remarks'] : '';
    ?>
    <tr>
      <td class="text-center"><?= $rowno++ ?></td>
      <td class="text-left"><?= $esc($cat.' '.$item) ?></td>
      <td class="text-left"></td>
      <td class="text-right"><?= $qty ? $nf($qty) : '' ?></td>
      <td class="text-center"><?= $esc($unit) ?></td>
      <td class="text-right"><?= $up ? $nf($up) : '' ?></td>
      <td class="text-right"><?= $amount ? $nf($amount) : '' ?></td>
      <td class="text-center"><?= $esc($remarks) ?></td>
    </tr>
    <?php } ?>

    <!-- 할인 내역 -->
    <?php if (!empty($discount_items) || !empty($discount_other_costs)) { ?>
    <tr>
      <td class="text-center fw700" colspan="8" style="background:#fff0f0; color:#b91c1c;">할인 내역</td>
    </tr>
    <?php } ?>

    <?php foreach ($discount_items as $it) {
        $name   = isset($it['code_string']) ? $it['code_string'] : (isset($it['product_name']) ? $it['product_name'] : '');
        $spec   = isset($it['specification']) ? $it['specification'] : '';
        $size   = isset($it['size']) ? $it['size'] : '';
        $qty    = (float)(isset($it['quantity']) ? $it['quantity'] : 0);
        $unit   = $size ? $size : '';
        $supply = (float)str_replace(',', '', isset($it['supply_amount']) ? $it['supply_amount'] : 0);
        $remarks= isset($it['remarks']) ? $it['remarks'] : '';
    ?>
    <tr>
      <td class="text-center"><?= $rowno++ ?></td>
      <td class="text-left"><?= $esc($name) ?></td>
      <td class="text-left"><?= $esc($spec) ?></td>
      <td class="text-right"><?= $qty ? $nf($qty) : '' ?></td>
      <td class="text-center"><?= $esc($unit) ?></td>
      <td class="text-right"></td>
      <td class="text-right">-<?= $nf($supply) ?></td>
      <td class="text-center"><?= $esc($remarks) ?></td>
    </tr>
    <?php } ?>

    <?php foreach ($discount_other_costs as $c) {
        $cat    = isset($c['category']) ? $c['category'] : '';
        $item   = isset($c['item']) ? $c['item'] : '';
        $unit   = isset($c['unit']) ? $c['unit'] : '';
        $qty    = (float)str_replace(',', '', isset($c['quantity']) ? $c['quantity'] : 0);
        $supply = (float)str_replace(',', '', isset($c['supply_amount']) ? $c['supply_amount'] : 0);
        $remarks= isset($c['remarks']) ? $c['remarks'] : '';
    ?>
    <tr>
      <td class="text-center"><?= $rowno++ ?></td>
      <td class="text-left"><?= $esc($cat.' '.$item) ?></td>
      <td class="text-left"></td>
      <td class="text-right"><?= $qty ? $nf($qty) : '' ?></td>
      <td class="text-center"><?= $esc($unit) ?></td>
      <td class="text-right"></td>
      <td class="text-right">-<?= $nf($supply) ?></td>
      <td class="text-center"><?= $esc($remarks) ?></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="6" class="text-right">합   계</th>
      <th class="text-right">₩<?= $nf($grand_total) ?></th>
      <th></th>
    </tr>
  </tfoot>
</table>

<!-- 비고 -->
<?php if (!empty($note)) { ?>
  <div class="mb6 fw700">비   고</div>
  <div class="small" style="border:1px solid #000; padding:8px 10px; white-space:pre-line;"><?= nl2br($esc($note)) ?></div>
<?php } ?>

</body>
</html>
<?php
$html = ob_get_clean();

// 디버그 미리보기
if ($debug) {
    header('Content-Type: text/html; charset=UTF-8');
    echo $html;
    exit;
}

// PDF 렌더/출력
try {
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    if (ob_get_length()) { ob_end_clean(); }
    $dompdf->stream($filename, array('Attachment' => $download ? true : false));
} catch (Throwable $e) {
    error_log('[Dompdf] '.$e->getMessage());
    http_response_code(500);
    echo 'PDF 생성 중 오류가 발생했습니다.';
}
