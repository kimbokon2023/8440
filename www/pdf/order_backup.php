<?php
// 메모리 & 타임아웃 (최상단)
@ini_set('memory_limit', '512M');   // 256M~512M 권장
@set_time_limit(120);
// ---------------------------------------------------------
// /pdf/order_pdf.php  (PHP 7.3 호환)
require_once $_SERVER['DOCUMENT_ROOT'] . '/session.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/mydb.php';

// Dompdf 오토로드 (_dompdf 격리 설치)
$dompdfAutoload = $_SERVER['DOCUMENT_ROOT'] . '/_dompdf/vendor/autoload.php';
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
$download = isset($_GET['download']) ? (int)$_GET['download'] : 1; // 1=다운, 0=브라우저
$debug    = isset($_GET['debug']) ? (int)$_GET['debug'] : 0;

if ($num <= 0) {
    http_response_code(400);
    exit('잘못된 num 파라미터입니다.');
}

// -------------------------
// 데이터 조회
// -------------------------
$pdo = db_connect();
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

// PHP 7.3 호환: 익명 함수 사용
$esc = function ($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
};
$nf  = function ($v, $dec = 0) {
    $n = (float)str_replace(',', '', (string)$v);
    return number_format($n, $dec);
};
$safeDate = function ($ymd) {
    if (!$ymd || $ymd === '0000-00-00') return '-';
    $ts = strtotime($ymd);
    return $ts ? date('Y.m.d', $ts) : '-';
};

// -------------------------
// 합계 계산
// -------------------------
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

// -------------------------
// Dompdf 옵션 + 폰트 경로(절대경로)
// -------------------------
$BASE_DIR   = __DIR__;                 // /pdf
$FONT_DIR   = $BASE_DIR . '/asset/fonts';
$TMP_DIR    = $BASE_DIR . '/tmp';
$FONT_CACHE = $TMP_DIR . '/font_cache';

@is_dir($TMP_DIR)    || @mkdir($TMP_DIR,    0777, true);
@is_dir($FONT_CACHE) || @mkdir($FONT_CACHE, 0777, true);

$FONT_REG_PATH  = realpath($FONT_DIR . '/NotoSansKR-Regular.ttf');
// $FONT_MED_PATH  = realpath($FONT_DIR . '/NotoSansKR-Medium.ttf');
// $FONT_BOLD_PATH = realpath($FONT_DIR . '/NotoSansKR-Bold.ttf');

$FONT_REG_URL  = $FONT_REG_PATH  ? 'file://' . $FONT_REG_PATH  : '';
// $FONT_MED_URL  = $FONT_MED_PATH  ? 'file://' . $FONT_MED_PATH  : '';
// $FONT_BOLD_URL = $FONT_BOLD_PATH ? 'file://' . $FONT_BOLD_PATH : '';

foreach (array(
    'Regular' => $FONT_REG_PATH
    // 'Medium'  => $FONT_MED_PATH,
    // 'Bold'    => $FONT_BOLD_PATH
) as $label => $fpath) {
    if (!$fpath || !is_readable($fpath)) {
        error_log("[Dompdf] Font file missing/unreadable ($label): " . ($fpath ? $fpath : 'not found'));
        http_response_code(500);
        exit("한글 폰트를 읽을 수 없습니다. /pdf/asset/fonts/NotoSansKR-{$label}.ttf");
    }
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

// -------------------------
// HTML (룩앤필)
// -------------------------
$today    = date('y.m.d');
$filename = '포미스톤수주서_' .
            preg_replace('/[\\\/:*?"<>|]/', '', $recipient ? $recipient : '수신처') . '_' .
            preg_replace('/[\\\/:*?"<>|]/', '', $site_name ? $site_name : '현장') .
            '(' . $today . ').pdf';

ob_start();
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<style>
  @page { margin: 24mm 14mm 22mm; }

  /* 절대경로 @font-face */
  @font-face {
    font-family: 'NotoSansKR';
    src: url('<?= $FONT_REG_URL ?>') format('truetype');
    font-weight: 400; font-style: normal;
  }
  /* // @font-face {
  //   font-family: 'NotoSansKR';
  //   src: url('<?= $FONT_MED_URL ?>') format('truetype');
  //   font-weight: 500; font-style: normal;
  // }
  @font-face {
    font-family: 'NotoSansKR';
  //   src: url('<?= $FONT_BOLD_URL ?>') format('truetype');
  //   font-weight: 700; font-style: normal;
  // } */

  html, body { font-family: 'NotoSansKR', DejaVu Sans, sans-serif; color:#1E2A3A; font-size:11px; }
  .muted { color:#6A778B; }
  .accent { color:#00A3FF; }
  .small { font-size:10px; }
  .mb8 { margin-bottom:8px; }
  .mb12 { margin-bottom:12px; }
  .mb16 { margin-bottom:16px; }
  .mb20 { margin-bottom:20px; }
  .w-100 { width:100%; }
  .text-right { text-align:right; }
  .text-left { text-align:left; }
  .text-center { text-align:center; }
  .fw-500 { font-weight:500; }
  .fw-700 { font-weight:700; }
  .pill { display:inline-block; padding:3px 8px; border-radius:999px; background:#F1F5F9; }

  #header { position: fixed; top: -16mm; left: 0; right: 0; height: 12mm; }
  #footer { position: fixed; bottom: -16mm; left: 0; right: 0; height: 12mm; }
  .rule { height:2px; background:linear-gradient(90deg,#00A3FF,#6CC8FF); border:0; }
  .page-number:after { content: counter(page) " / " counter(pages); }

  .grid { display: table; width:100%; border-spacing:0 6px; }
  .grid-row { display: table-row; }
  .grid-cell { display: table-cell; vertical-align: top; padding:8px 10px; background:#fff; border:1px solid #E2E8F0; }

  table.tbl { width:100%; border-collapse: collapse; font-family:'NotoSansKR', sans-serif; }
  .tbl th, .tbl td { border:1px solid #E2E8F0; padding:6px 8px; }
  .tbl thead th { background:#F8FAFC; font-weight:700; }
  .tbl tfoot td { background:#F1F5F9; font-weight:700; }
  .num { text-align:right; }

  .totals { width:100%; border:1px solid #CBD5E1; font-family:'NotoSansKR', sans-serif; }
  .totals td { padding:8px 10px; }
  .totals .label { background:#F8FAFC; width:28%; }
  .totals .value { font-weight:700; }

  .brand { font-size:14px; letter-spacing:1px; font-weight:700; }
</style>
</head>
<body>

<div id="header">
  <table class="w-100" style="border-collapse:collapse;">
    <tr>
      <td class="brand">PHOMI STONE · 주식회사 미래기업</td>
      <td class="text-right small muted">
        문서: 수주서 &nbsp;|&nbsp; 번호: <?= $esc($num) ?> &nbsp;|&nbsp; 생성일: <?= date('Y-m-d') ?>
      </td>
    </tr>
  </table>
  <div class="rule"></div>
</div>

<div id="footer">
  <div class="rule"></div>
  <table class="w-100" style="border-collapse:collapse;">
    <tr>
      <td class="small muted">
        본사: 경기도 김포시 양촌읍 흥신로 220-27 &nbsp;|&nbsp; 전시장: 인천 서구 중봉대로 393번길 16 홈씨씨 2층 포미스톤
      </td>
      <td class="text-right small">
        <span class="muted">페이지</span> <span class="page-number"></span>
      </td>
    </tr>
  </table>
</div>

<div class="mb12" style="margin-top:2mm;">
  <table class="w-100" style="border-collapse:collapse;">
    <tr>
      <td class="fw-700" style="font-size:18px;">수주서</td>
      <td class="text-right small">
        <span class="pill">계정: <?= $esc($payment_account) ?></span>
      </td>
    </tr>
  </table>
</div>

<div class="grid mb16">
  <div class="grid-row">
    <div class="grid-cell" style="width:36%">
      <div class="mb8"><span class="muted">수신</span><br><span class="fw-700" style="font-size:13px;"><?= $esc($recipient) ?> 귀하</span></div>
      <div class="mb8"><span class="muted">구분</span><br><span class="fw-500"><?= $esc($division) ?: '-' ?></span></div>
      <div class="mb8"><span class="muted">현장명</span><br><span class="fw-500"><?= $esc($site_name) ?: '-' ?></span></div>
      <div class="mb8"><span class="muted">수주일자</span><br><span class="fw-500"><?= $esc($safeDate($order_date)) ?></span></div>
      <div class=""><span class="muted">견적번호</span><br><span class="fw-500"><?= $esc($estimate_num) ?: '-' ?></span></div>
    </div>
    <div class="grid-cell">
      <table class="tbl">
        <tbody>
          <tr>
            <th style="width:20%">상호</th><td><b>주식회사 미래기업</b></td>
            <th style="width:20%">대표</th><td><?= $esc($signed_by) ?></td>
          </tr>
          <tr>
            <th>사업자번호</th><td>722-88-00035</td>
            <th>연락처</th><td>010-3784-5438</td>
          </tr>
          <tr>
            <th>주소</th>
            <td colspan="3">본사: 경기도 김포시 양촌읍 흥신로 220-27 / 전시장: 인천 서구 중봉대로 393번길 16 홈씨씨 2층 포미스톤</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<table class="totals mb16">
  <tr>
    <td class="label">합계금액 (부가세별도)</td>
    <td class="value accent num">(<?= $nf($grand_supply) ?>)</td>
    <td class="label">합계금액 (부가세포함)</td>
    <td class="value accent num">(<?= $nf($grand_total) ?>)</td>
  </tr>
</table>

<div class="mb12 fw-700">상품 내역</div>
<table class="tbl mb16">
  <thead>
    <tr>
      <th style="width:5%">No.</th>
      <th style="width:28%">상품명</th>
      <th style="width:12%">규격</th>
      <th style="width:10%">분류</th>
      <th style="width:7%" class="num">수량</th>
      <th style="width:8%" class="num">m²</th>
      <th style="width:10%" class="num">단가</th>
      <th style="width:10%" class="num">공급가액</th>
      <th style="width:10%" class="num">세액</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach ($items as $it):
        $prodcode = isset($it['product_code']) ? $it['product_code'] : '';
        $spec      = isset($it['specification']) ? $it['specification'] : '';
        $size      = isset($it['size']) ? $it['size'] : '';
        $qty       = (float)(isset($it['quantity']) ? $it['quantity'] : 0);
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
      <td class="text-left"><?= $esc($display) ?></td>
      <td class="text-left"><?= $esc($spec) ?></td>
      <td class="text-center"><?= $esc($size) ?></td>
      <td class="num"><?= $nf($qty) ?></td>
      <td class="num"><?= $nf($area, 2) ?></td>
      <td class="num"><?= $nf($up) ?></td>
      <td class="num"><?= $nf($supply) ?></td>
      <td class="num"><?= $nf($tax) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="7" class="text-right">소계</td>
      <td class="num"><?= $nf($total_supply) ?></td>
      <td class="num"><?= $nf($total_tax) ?></td>
    </tr>
  </tfoot>
</table>

<div class="mb12 fw-700">기타 비용 (부자재 및 인건비 등)</div>
<table class="tbl mb16">
  <thead>
    <tr>
      <th style="width:12%">구분</th>
      <th style="width:26%">항목</th>
      <th style="width:10%">단위</th>
      <th style="width:10%" class="num">수량</th>
      <th style="width:12%" class="num">단가</th>
      <th style="width:15%" class="num">공급가액</th>
      <th style="width:15%" class="num">세액</th>
    </tr>
  </thead>
  <tbody>
    <?php $os=0;$ot=0; foreach ($other_costs as $c):
        $cat   = isset($c['category']) ? $c['category'] : '';
        $item  = isset($c['item']) ? $c['item'] : '';
        $unit  = isset($c['unit']) ? $c['unit'] : '';
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
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" class="text-right">소계</td>
      <td class="num"><?= $nf($os) ?></td>
      <td class="num"><?= $nf($ot) ?></td>
    </tr>
  </tfoot>
</table>

<?php if (!empty($discount_items) || !empty($discount_other_costs)): ?>
  <div class="mb12 fw-700" style="color:#c0392b;">할인 내역</div>
  <?php if (!empty($discount_items)): ?>
    <table class="tbl mb12">
      <thead>
        <tr>
          <th style="width:5%">No.</th>
          <th style="width:33%">상품명</th>
          <th style="width:12%">규격</th>
          <th style="width:10%">분류</th>
          <th style="width:10%" class="num">수량</th>
          <th style="width:10%" class="num">m²</th>
          <th style="width:10%" class="num">공급가액</th>
          <th style="width:10%" class="num">세액</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach ($discount_items as $it):
            $name   = isset($it['code_string']) ? $it['code_string'] : (isset($it['product_name']) ? $it['product_name'] : '');
            $spec   = isset($it['specification']) ? $it['specification'] : '';
            $size   = isset($it['size']) ? $it['size'] : '';
            $qty    = (float)(isset($it['quantity']) ? $it['quantity'] : 0);
            $area   = (float)str_replace(',', '', isset($it['area']) ? $it['area'] : 0);
            $supply = (float)str_replace(',', '', isset($it['supply_amount']) ? $it['supply_amount'] : 0);
            $tax    = (float)str_replace(',', '', isset($it['tax_amount']) ? $it['tax_amount'] : 0);
            $discount_supply += -$supply; $discount_tax += -$tax;
        ?>
        <tr>
          <td class="text-center"><?= $i++ ?></td>
          <td class="text-left"><?= $esc($name) ?></td>
          <td class="text-left"><?= $esc($spec) ?></td>
          <td class="text-center"><?= $esc($size) ?></td>
          <td class="num"><?= $nf($qty) ?></td>
          <td class="num"><?= $nf($area, 2) ?></td>
          <td class="num">-<?= $nf($supply) ?></td>
          <td class="num">-<?= $nf($tax) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="6" class="text-right">할인 소계</td>
          <td class="num" style="color:#c0392b;"><?= $nf($discount_supply) ?></td>
          <td class="num" style="color:#c0392b;"><?= $nf($discount_tax) ?></td>
        </tr>
      </tfoot>
    </table>
  <?php endif; ?>

  <?php if (!empty($discount_other_costs)): ?>
    <table class="tbl mb16">
      <thead>
        <tr>
          <th style="width:15%">구분</th>
          <th style="width:35%">항목</th>
          <th style="width:10%">단위</th>
          <th style="width:10%" class="num">수량</th>
          <th style="width:15%" class="num">공급가액</th>
          <th style="width:15%" class="num">세액</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($discount_other_costs as $c):
            $cat    = isset($c['category']) ? $c['category'] : '';
            $item   = isset($c['item']) ? $c['item'] : '';
            $unit   = isset($c['unit']) ? $c['unit'] : '';
            $qty    = (float)str_replace(',', '', isset($c['quantity']) ? $c['quantity'] : 0);
            $supply = (float)str_replace(',', '', isset($c['supply_amount']) ? $c['supply_amount'] : 0);
            $tax    = (float)str_replace(',', '', isset($c['tax_amount']) ? $c['tax_amount'] : 0);
            $discount_other_supply += -$supply; $discount_other_tax += -$tax;
        ?>
        <tr>
          <td class="text-center"><?= $esc($cat) ?></td>
          <td class="text-left"><?= $esc($item) ?></td>
          <td class="text-center"><?= $esc($unit) ?></td>
          <td class="num"><?= $qty ? $nf($qty) : '' ?></td>
          <td class="num">-<?= $nf($supply) ?></td>
          <td class="num">-<?= $nf($tax) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" class="text-right">할인 소계</td>
          <td class="num" style="color:#c0392b;"><?= $nf($discount_other_supply) ?></td>
          <td class="num" style="color:#c0392b;"><?= $nf($discount_other_tax) ?></td>
        </tr>
      </tfoot>
    </table>
  <?php endif; ?>
<?php endif; ?>

<?php if (!empty($note)): ?>
  <div class="mb8 fw-700">비고</div>
  <div class="small" style="white-space:pre-line; border:1px solid #E2E8F0; padding:8px 10px;"><?= nl2br($esc($note)) ?></div>
<?php endif; ?>

</body>
</html>
<?php
$html = ob_get_clean();

// HTML 미리보기 (디버그)
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
