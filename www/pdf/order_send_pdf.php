<?php
/**
 * 발주서 업체 발송용 PDF 생성
 * 업체발송양식.pdf를 참조하여 작성
 */

// 로컬/서버 환경 설정
$is_local = (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false));
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

// 메모리 & 타임아웃
@ini_set('memory_limit', '512M');
@set_time_limit(120);

// 공통 함수 로드 (getDocumentRoot 함수 포함)
require_once __DIR__ . '/../common/functions.php';

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
$id = $_GET['id'] ?? 0;
$id = (int)$id;
$download = $_GET['download'] ?? 1;
$download = (int)$download;
$debug = $_GET['debug'] ?? 0;
$debug = (int)$debug;

if ($id <= 0) {
    http_response_code(400);
    exit('잘못된 id 파라미터입니다.');
}

// 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 5) {
    http_response_code(403);
    exit('권한이 없습니다.');
}

// ------------------------- 데이터 조회 -------------------------
try {
    $pdo = db_connect();
    $DB = $_SESSION['DB'] ?? 'mirae8440';
    
    $sql = "SELECT * FROM `order` WHERE id = :id AND is_deleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        http_response_code(404);
        exit('발주서를 찾을 수 없습니다.');
    }
} catch (PDOException $e) {
    error_log("발주서 조회 오류: " . $e->getMessage());
    http_response_code(500);
    exit('데이터 조회 중 오류가 발생했습니다.');
}

// JSON 필드 파싱
$items = !empty($order['order_items']) ? json_decode($order['order_items'], true) : [];
if (!is_array($items)) {
    $items = [];
}

// 기본 정보
$order_no = $order['order_no'] ?? '';
$issue_date = $order['issue_date'] ?? '';
$supplier_name = $order['supplier_name'] ?? '주식회사미래기업';
$supplier_address = $order['supplier_address'] ?? '경기도 김포시 양촌읍 흥신로 220-27 (흥신리)';
$business_type = $order['business_type'] ?? '제조업';
$business_item = $order['business_item'] ?? '엘리베이터의장품';
$supplier_phone = $order['supplier_phone'] ?? '031-983-8440';
$supplier_fax = $order['supplier_fax'] ?? '031-982-8449';
$contact_name = $order['contact_name'] ?? '';
$phone = $order['phone'] ?? '';
$fax = $order['fax'] ?? '';
$business_registration_number = $order['business_registration_number'] ?? '';
$project_site = $order['project_site'] ?? '';
$delivery_date = $order['delivery_date'] ?? '';
$delivery_location = $order['delivery_location'] ?? '';
$payment_terms = $order['payment_terms'] ?? '';
$valid_date = $order['valid_date'] ?? '';
$note = $order['note'] ?? '';

// 합계 계산
$total_supply = 0;
$total_tax = 0;
$grand_total = 0;

foreach ($items as $item) {
    $quantity = (float)str_replace(',', '', $item['수량'] ?? $item['quantity'] ?? 0);
    $unit_price = (float)str_replace(',', '', $item['단가'] ?? $item['unit_price'] ?? 0);
    $supply = $quantity * $unit_price;
    $tax = round($supply * 0.1);
    
    $total_supply += $supply;
    $total_tax += $tax;
}

$grand_total = $total_supply + $total_tax;

// 헬퍼 함수
$esc = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
$nf = function ($v, $dec = 0) {
    $n = (float)str_replace(',', '', (string)$v);
    return number_format($n, $dec);
};
$ymdDot = function ($ymd) {
    if (!$ymd || $ymd === '0000-00-00' || $ymd === '') return '';
    $ts = strtotime($ymd);
    if (!$ts || $ts === false) return '';
    return date('Y.m.d', $ts);
};
$ymdKorean = function ($ymd) {
    if (!$ymd || $ymd === '0000-00-00' || $ymd === '') return '';
    $ts = strtotime($ymd);
    if (!$ts || $ts === false) return '';
    return date('Y년 n월 j일', $ts);
};
$formatPhone = function ($phone) {
    if (empty($phone)) return '';
    // 숫자만 추출
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (empty($phone)) return '';
    
    // 02로 시작하는 경우
    if (substr($phone, 0, 2) === '02') {
        if (strlen($phone) === 9) {
            // 02-123-4567
            return '02-' . substr($phone, 2, 3) . '-' . substr($phone, 5);
        } elseif (strlen($phone) === 10) {
            // 02-1234-5678
            return '02-' . substr($phone, 2, 4) . '-' . substr($phone, 6);
        }
    }
    
    // 지역번호 3자리 (031, 032, 041, 042, 043, 044, 051, 052, 053, 054, 055, 061, 062, 063, 064)
    if (strlen($phone) === 10 && in_array(substr($phone, 0, 3), ['031', '032', '041', '042', '043', '044', '051', '052', '053', '054', '055', '061', '062', '063', '064'])) {
        // 031-123-4567
        return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
    }
    if (strlen($phone) === 11 && in_array(substr($phone, 0, 3), ['031', '032', '041', '042', '043', '044', '051', '052', '053', '054', '055', '061', '062', '063', '064'])) {
        // 031-1234-5678
        return substr($phone, 0, 3) . '-' . substr($phone, 3, 4) . '-' . substr($phone, 7);
    }
    
    // 기본 형식 (010 등)
    if (strlen($phone) === 11) {
        return substr($phone, 0, 3) . '-' . substr($phone, 3, 4) . '-' . substr($phone, 7);
    }
    if (strlen($phone) === 10) {
        return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
    }
    
    return $phone;
};

// ------------------------- Dompdf 옵션 & 폰트 -------------------------
$BASE_DIR = __DIR__;
$FONT_DIR = $BASE_DIR . '/asset/fonts';
$TMP_DIR = $BASE_DIR . '/tmp';
$FONT_CACHE = $TMP_DIR . '/font_cache';

@is_dir($TMP_DIR) || @mkdir($TMP_DIR, 0777, true);
@is_dir($FONT_CACHE) || @mkdir($FONT_CACHE, 0777, true);

$FONT_REG_PATH = realpath($FONT_DIR . '/NotoSansKR-Regular.ttf');
$FONT_REG_URL = $FONT_REG_PATH ? 'file://' . $FONT_REG_PATH : '';

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

// 파일명 생성
$today = date('y.m.d');
function sanitize_filename($str) {
    return preg_replace('/[\\\\\/:\*\?"<>\|]/u', '', $str);
}
$contact_sanitized = sanitize_filename($contact_name ?: '거래처');
$filename = '발주서_' . $contact_sanitized . '_' . $today . '.pdf';

// ------------------------- HTML 생성 -------------------------
ob_start();
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<style>
  @page { margin: 20mm 15mm 20mm 15mm; }

  @font-face {
    font-family: 'NotoSansKR';
    src: url('<?= $FONT_REG_URL ?>') format('truetype');
    font-weight: 400;
    font-style: normal;
  }

  html, body {
    font-family: 'NotoSansKR', DejaVu Sans, sans-serif;
    font-size: 10pt;
    color: #111;
    line-height: 1.5;
  }

  .small { font-size: 9pt; }
  .xs { font-size: 8pt; }

  .mb2 { margin-bottom: 2pt; }
  .mb4 { margin-bottom: 4pt; }
  .mb6 { margin-bottom: 6pt; }
  .mb8 { margin-bottom: 8pt; }
  .mb10 { margin-bottom: 10pt; }

  .text-right { text-align: right; }
  .text-left { text-align: left; }
  .text-center { text-align: center; }

  .fw600 { font-weight: 600; }
  .fw700 { font-weight: 700; }

  /* 제목 */
  .title-section {
    text-align: center;
    margin-bottom: 8pt;
    position: relative;
  }

  .title-section h1 {
    font-size: 18pt;
    font-weight: 700;
    margin: 0;
    padding-bottom: 4pt;
    letter-spacing: 0.8em; /* 글자 간격 2배 증가 */
  }

  .title-section::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: #000;
  }

  /* 상단 레이아웃 */
  .top-layout {
    display: table;
    width: 100%;
    margin-bottom: 8pt;
    margin-top: 4pt;
  }

  .top-left-cell {
    display: table-cell;
    width: 45%;
    vertical-align: top;
    padding-right: 10pt;
  }

  .top-right-cell {
    display: table-cell;
    width: 55%;
    vertical-align: top;
    padding-left: 10pt;
  }

  /* 수신처 정보 */
  .recipient-section {
    margin-bottom: 10pt;
  }

  .recipient-name {
    font-size: 12pt;
    font-weight: 700;
    margin-bottom: 8pt;
  }

  .recipient-detail {
    font-size: 9pt;
    line-height: 1.6;
  }

  /* 발주자 정보 테이블 (estimate_pdf.php 스타일 참고) */
  .supplier-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9pt;
    margin-bottom: 10pt;
  }

  .supplier-table th,
  .supplier-table td {
    border: 1px solid #000;
    padding: 3pt 4pt;
    vertical-align: middle;
    font-size: 7.2pt; /* 9pt의 80% */
  }

  .supplier-table th {
    background: #f2f2f2;
    font-weight: 700;
    text-align: center;
  }

  .supplier-table td {
    text-align: left;
  }

  .supplier-table .rowspan-label {
    width: 20pt;
    font-weight: 700;
    background: #f2f2f2;
    padding: 3pt 2pt;
    text-align: center;
    vertical-align: middle;
  }

  /* 중간 섹션 */
  .middle-section {
    margin: 12pt 0;
    font-size: 9pt;
  }

  .delivery-note {
    margin-bottom: 6pt;
  }

  .total-amount-section {
    margin: 8pt 0;
  }

  .total-amount-label {
    font-weight: 700;
    margin-right: 8pt;
  }

  .total-amount-value {
    font-weight: 700;
    font-size: 11pt;
  }

  /* 테이블 */
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9pt;
    margin-top: 10pt;
  }

  table th, table td {
    border: 1px solid #000;
    padding: 5pt 6pt;
    vertical-align: middle;
  }

  table thead th {
    background: #f5f5f5;
    font-weight: 700;
    text-align: center;
    font-size: 9pt;
  }

  table tbody td {
    font-size: 9pt;
  }

  .num {
    text-align: right;
  }

  /* 푸터 */
  #footer {
    position: fixed;
    bottom: -18mm;
    left: 0;
    right: 0;
    height: 8mm;
    font-size: 8pt;
    color: #666;
    text-align: center;
    border-top: 1px solid #000;
    padding-top: 4pt;
  }
</style>
</head>
<body>

<!-- 푸터 -->
<div id="footer">
  경기도 김포시 양촌읍 흥신로 220-27 | TEL: 031-983-8440 | FAX: 031-982-8449
</div>

<!-- 제목 -->
<div class="title-section">
  <h1>발 주 서</h1>
</div>

<!-- 발주번호 -->
<?php if (!empty($order_no)): ?>
<div style="text-align: left; margin-bottom: 6pt; font-size: 9pt;">
  <strong>NO :</strong> <?= $esc($order_no) ?>
  <div style="border-bottom: 1px solid #000; margin-top: 4pt; width: 100%;"></div>
</div>
<?php endif; ?>

<!-- 상단 레이아웃 -->
<div class="top-layout">
  <!-- 좌측: 수신처 정보 -->
  <div class="top-left-cell">
    <div class="recipient-section">
      <div class="recipient-name">
        <?= $esc($contact_name ?: '거래처') ?> 貴下
      </div>
      <div class="recipient-detail">
        <div><strong>발주일자:</strong> <?= $ymdKorean($issue_date) ?></div>
        <?php if (!empty($phone) || !empty($fax)): ?>
        <div>
          <?php if (!empty($phone)): ?>
          <strong>전화번호:</strong> <?= $esc($formatPhone($phone)) ?>
          <?php endif; ?>
          <?php if (!empty($phone) && !empty($fax)): ?> | <?php endif; ?>
          <?php if (!empty($fax)): ?>
          <strong>팩스번호:</strong> <?= $esc($formatPhone($fax)) ?>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- 우측: 발주자 정보 테이블 -->
  <div class="top-right-cell">
    <table class="supplier-table">
      <tr>
        <th rowspan="5" class="rowspan-label" style="width: 20pt; padding: 4pt 2pt;">
          <div style="line-height: 1.2;">발</div>
          <div style="line-height: 1.2;">주</div>
          <div style="line-height: 1.2;">자</div>
        </th>
        <th style="width: 20%;">등록번호</th>
        <td style="width: 30%; white-space: nowrap; min-width: 80pt;"><?= $esc($business_registration_number ?: '722-88-00035') ?></td>
        <th style="width: 10%;">상호</th>
        <td class="fw700" style="width: 40%;"><?= $esc($supplier_name) ?></td>
      </tr>
      <tr>
        <th>성명</th>
        <td style="position: relative;">
          소현철
          <?php
          // 도장 이미지 경로 확인 및 처리
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
                      $stamp_html = '<img src="data:image/png;base64,' . $base64_png . '" style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; margin-left: 4pt;" alt="미래기업도장">';
                  }
                  break;
              }
          }

          if ($stamp_found && $stamp_html) {
              echo $stamp_html;
          }
          ?>
        </td>
        <th>전화번호</th>
        <td><?= $esc($formatPhone($supplier_phone)) ?></td>
      </tr>
      <tr>
        <th>주소</th>
        <td colspan="3"><?= $esc($supplier_address) ?></td>
      </tr>
      <tr>
        <th>업태</th>
        <td><?= $esc($business_type) ?></td>
        <th>종목</th>
        <td><?= $esc($business_item) ?></td>
      </tr>
      <tr>
        <th>팩스번호</th>
        <td colspan="3"><?= $esc($formatPhone($supplier_fax)) ?></td>
      </tr>
    </table>
  </div>
</div>

<!-- 중간 섹션 -->
<div class="middle-section">
  <div class="delivery-note">
    납기일 내에 인도해 주시기 바랍니다.
  </div>
  <div class="total-amount-section">
    <span class="total-amount-label">합계금액:</span>
    <span class="total-amount-value">일금 <?= $nf($grand_total) ?>원정</span>
    <span style="font-size: 9pt; margin-left: 4pt;">(부가세포함)</span>
  </div>
  <?php if (!empty($project_site)): ?>
  <div style="margin-top: 6pt;">
    <strong>프로젝트/현장:</strong> <?= $esc($project_site) ?>
  </div>
  <?php endif; ?>
</div>

<!-- 품목 내역 테이블 -->
<?php if (!empty($items)): ?>
<table>
  <thead>
    <tr>
      <th style="width: 6%;">순번</th>
      <th style="width: 30%;">품목</th>
      <th style="width: 12%;">규격</th>
      <th style="width: 8%;">수량</th>
      <th style="width: 10%;">단가</th>
      <th style="width: 12%;">공급가액</th>
      <th style="width: 12%;">세액</th>
      <th style="width: 10%;">비고</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $rowno = 1;
    foreach ($items as $item):
      $item_name = $item['품목'] ?? $item['item_name'] ?? '';
      $spec = $item['규격'] ?? $item['spec'] ?? '';
      $quantity = (float)str_replace(',', '', $item['수량'] ?? $item['quantity'] ?? 0);
      $unit_price = (float)str_replace(',', '', $item['단가'] ?? $item['unit_price'] ?? 0);
      $supply = $quantity * $unit_price;
      $tax = round($supply * 0.1);
      $remarks = $item['비고'] ?? $item['remarks'] ?? '';
    ?>
    <tr>
      <td class="text-center"><?= $rowno++ ?></td>
      <td class="text-left"><?= $esc($item_name) ?></td>
      <td class="text-left"><?= $esc($spec) ?></td>
      <td class="num"><?= $quantity ? $nf($quantity) : '' ?></td>
      <td class="num"><?= $unit_price ? $nf($unit_price) : '' ?></td>
      <td class="num"><?= $supply ? $nf($supply) : '' ?></td>
      <td class="num"><?= $tax ? $nf($tax) : '' ?></td>
      <td class="text-left"><?= $esc($remarks) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

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
    error_log('[Dompdf] ' . $e->getMessage());
    http_response_code(500);
    echo 'PDF 생성 중 오류가 발생했습니다.';
}
