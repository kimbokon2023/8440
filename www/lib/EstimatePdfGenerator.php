<?php
/**
 * 견적서 PDF 생성 클래스
 */

require_once __DIR__ . '/../bootstrap.php';

// Dompdf 오토로드
$dompdfAutoload = getDocumentRoot() . '/_dompdf/vendor/autoload.php';
if (file_exists($dompdfAutoload)) {
    require_once $dompdfAutoload;
}

use Dompdf\Dompdf;
use Dompdf\Options;

class EstimatePdfGenerator {
    private $pdo;
    private $dompdf;
    private $baseDir;

    public function __construct() {
        $this->pdo = db_connect();
        $this->baseDir = getDocumentRoot() . '/pdf'; // PDF 관련 리소스 기준 경로
        $this->initDompdf();
    }

    private function initDompdf() {
        $fontDir = $this->baseDir . '/asset/fonts';
        $tmpDir = $this->baseDir . '/tmp';
        $fontCache = $tmpDir . '/font_cache';

        // 디렉토리 생성
        if (!is_dir($tmpDir)) @mkdir($tmpDir, 0777, true);
        if (!is_dir($fontCache)) @mkdir($fontCache, 0777, true);

        $options = new Options();
        $options->set('defaultFont', 'NotoSansKR');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', $this->baseDir);
        $options->set('tempDir', $tmpDir);
        $options->set('fontCache', $fontCache);
        $options->set('isFontSubsettingEnabled', true);
        
        $this->dompdf = new Dompdf($options);
    }

    public function generate($estimateId) {
        // 1. 데이터 조회
        $estimate = $this->getEstimateData($estimateId);
        if (!$estimate) {
            throw new Exception("견적서를 찾을 수 없습니다.");
        }

        // 2. HTML 생성
        $html = $this->renderHtml($estimate);

        // 3. PDF 렌더링
        $this->dompdf->loadHtml($html, 'UTF-8');
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        return $this->dompdf->output();
    }

    public function stream($estimateId, $download = true) {
        $pdfContent = $this->generate($estimateId);
        $estimate = $this->getEstimateData($estimateId);
        
        $contactName = $estimate['contact_name'] ?? '거래처';
        $filename = '견적서_' . $this->sanitizeFilename($contactName) . '_' . date('y.m.d') . '.pdf';

        $this->dompdf->stream($filename, ['Attachment' => $download]);
    }

    private function getEstimateData($id) {
        $sql = "SELECT * FROM `estimates` WHERE id = :id AND is_deleted = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function renderHtml($estimate) {
        // 데이터 전처리
        $items = !empty($estimate['estimate_items']) ? json_decode($estimate['estimate_items'], true) : [];
        if (!is_array($items)) $items = [];

        // 변수 준비
        $recipient = $estimate['contact_name'] ?? $estimate['supplier_name'] ?? '';
        $site_name = $estimate['project_site'] ?? '';
        $quote_date = $estimate['issue_date'] ?? date('Y-m-d');
        $signed_by = '소현철';
        $payment_account = '중소기업은행 339-084210-01-012 ㈜ 미래기업';
        $estimate_no = $estimate['estimate_no'] ?? '';
        $note = $estimate['note'] ?? '';

        // 합계 계산
        $total_supply = 0;
        $total_tax = 0;

        foreach ($items as $it) {
            $supply = (float)str_replace(',', '', $it['견적가액'] ?? $it['amount'] ?? 0);
            $tax = (float)str_replace(',', '', $it['세액'] ?? $it['tax_amount'] ?? 0);
            
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

        // 폰트 경로
        $fontPath = realpath($this->baseDir . '/asset/fonts/NotoSansKR-Regular.ttf');
        $fontUrl = $fontPath ? 'file://' . $fontPath : '';

        // 헬퍼 함수
        $esc = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
        $nf = function ($v, $dec = 0) {
            $n = (float)str_replace(',', '', (string)$v);
            return number_format($n, $dec);
        };

        // HTML 템플릿 시작
        ob_start();
        ?>
        <!doctype html>
        <html lang="ko">
        <head>
        <meta charset="utf-8">
        <style>
          @page { margin: 18mm 15mm 18mm 15mm; }
          @font-face {
            font-family: 'NotoSansKR';
            src: url('<?= $fontUrl ?>') format('truetype');
            font-weight: 400;
            font-style: normal;
          }
          html, body {
            font-family: 'NotoSansKR', DejaVu Sans, sans-serif;
            font-size: 10.2pt;
            color: #111;
            line-height: 1.15;
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
          .text-right { text-align:right; }
          .text-left  { text-align:left; }
          .text-center{ text-align:center; }
          .fw-600 { font-weight: 600; }
          .fw-700 { font-weight: 700; }
          #header { position: fixed; top: -14mm; left: 0; right: 0; height: 12mm; }
          #footer { position: fixed; bottom: -14mm; left: 0; right: 0; height: 12mm; }
          .hr    { height:1px; background:#000; border:0; }
          body { counter-reset: page 0; counter-reset: pages 1; }
          @page { counter-increment: page; counter-increment: pages; }
          .page-number:after { content: counter(page) " / " counter(pages); }
          .doc-title { font-size: 23pt; letter-spacing: 5px; word-spacing: 10px; text-align: center; font-weight: 700; }
          .doc-title2 { font-size: 14pt; letter-spacing: 2px; word-spacing: 2px; text-align: center; font-weight: 700; }
          .doc-title3 { font-size: 14pt; letter-spacing: 2px; word-spacing: 4px; text-align: center; font-weight: 600; position: relative; display: inline-block; vertical-align: middle; margin-left: 10px; margin-right: 350px; border-bottom: 1px solid #222; padding-bottom: 2px; }
          .grid-cell-half { width: 50% !important; max-width: 50% !important; min-width: 50% !important; vertical-align: top; box-sizing: border-box; }
          table.tbl { width:100%; border-collapse: collapse; font-size: 8.5pt; }
          .tbl th, .tbl td { border: 1px solid #000; padding: 0.5pt 2pt; font-size: 8.5pt; }
          .tbl thead th { background: #f2f2f2; font-weight:700; font-size: 8.5pt; }
          .tbl tfoot td { background: #f7f7f7; font-weight:700; font-size: 8.5pt; }
          .num { text-align:right; }
          .totals { width:100%; border:1px solid #000; border-collapse: collapse; font-size: 9pt; }
          .totals td { border:1px solid #000; padding:2pt 2pt; font-size: 9pt;  }
          .totals .label { width:28%; background:#f7f7f7; font-size: 9pt; }
          .totals .value { font-weight:700; font-size: 9pt; }
        </style>
        </head>
        <body>

        <div id="header">
          <table style="width:100%; border-collapse:collapse;">
            <tr>
              <td class="small muted">문서번호: <?= $esc($estimate_no) ?></td>
              <td class="text-right small muted">생성일: <?= date('Y-m-d') ?></td>
            </tr>
          </table>
          <div class="hr"></div>
        </div>

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

        <div class="doc-title mb2">견      적      서</div>
        <div class="doc-title2 ">ESTIMATE</div>
        <hr>
        <div class="mb2" style="display:flex; justify-content:space-between; align-items:center; width:100%;">
          <span class="doc-title3 mb6" style="flex:0 1 auto;">
            <?= $esc($recipient) ?> 貴下
          </span>
          <span class="mb2" style="white-space:nowrap; font-size:12pt;">
            <?= date('Y년 n월 j일', strtotime($quote_date)) ?>
          </span>
        </div>

        <table class="mb8" style="border:none; table-layout:fixed; width:100%;">
          <tr>
            <td class="grid-cell-half" style="border:none; padding-right:10px;">
              <span class="fw-700"> 현장명 :  <?= $esc($site_name) ?: '-' ?> </span>
              <br><br><br>
              <span> 별첨과 같이 견적합니다. </span>
            </td>
            <td class="grid-cell-half" style="border:none; ">
              <table style="border:none; font-size:9pt; ">
                <tbody>
                <tr>
                <td>
                  <div style="position: relative; display: inline-block;"></div>
                </td>
                </tr>
                <tr>
                  <td colspan="4" style="font-size:8pt;">
                   <br>
                     본사: 경기도 김포시 양촌읍 흥신로 220-27
                    <br>
                        T E L : 031 ) 983 - 8440 &nbsp;&nbsp;|&nbsp;&nbsp;
                        F A X : 031 ) 982 - 8449
                    <br>
                    <?php
                        $stampPath = getDocumentRoot() . '/img/miraestamp.png';
                        if (file_exists($stampPath)) {
                            $base64 = base64_encode(file_get_contents($stampPath));
                            echo '<img src="data:image/png;base64,' . $base64 . '" style="width: 45px; height: 45px; position: absolute; top: 225px; left: 82%; transform: translateX(-50%); z-index: 10;" alt="미래기업도장">';
                        } else {
                            echo '<svg width="50" height="50" viewBox="0 0 50 50" style="position: absolute; top: -5px; left: 50%; transform: translateX(-50%); z-index: 10;"><circle cx="25" cy="25" r="23" fill="#D32F2F" stroke="#8B0000" stroke-width="2"/><text x="25" y="30" font-family="serif" font-size="10" fill="#8B0000" text-anchor="middle" font-weight="bold">도장</text></svg>';
                        }
                      ?>
                    <div style="width:100%; margin-top: 5px; letter-spacing:0.1em; text-align:left; display:block; white-space: nowrap; font-size: 10pt;">
                        <b>(주)미래기업</b> &nbsp;대표&nbsp; <?= $esc($signed_by) ?> &nbsp;(인)
                    </div>
                </td>
                </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </table>

        <table class="totals mb12">
          <tr>
            <td class="label">합계금액 (공급가액)</td>
            <td class="value num">₩ <?= $nf($grand_supply) ?></td>
            <td class="label">합계금액 (부가세포함)</td>
            <td class="value num">₩ <?= $nf($grand_total) ?></td>
          </tr>
        </table>

        <div class="fw-700 mb6">세부 내역</div>
        <table class="tbl mb12">
          <thead>
            <tr>
              <th style="width:5%;">No.</th>
              <th style="width:30%;">품목</th>
              <th style="width:15%;">규격</th>
              <th style="width:10%;" class="num">수량</th>
              <th style="width:10%;" class="num">단위</th>
              <th style="width:10%;" class="num">단가</th>
              <th style="width:10%;" class="num">공급가액</th>
              <th style="width:10%;" class="num">세액</th>
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
              <td colspan="6" class="text-right">소계</td>
              <td class="num"><?= $nf($total_supply) ?></td>
              <td class="num"><?= $nf($total_tax) ?></td>
            </tr>
          </tfoot>
        </table>

        <?php if (!empty($note)): ?>
          <div class="fw-700 mb6">비고</div>
          <div class="small" style="white-space:pre-line; border:1px solid #000; padding:7pt 8pt;"><?= nl2br($esc($note)) ?></div>
        <?php endif; ?>

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
        return ob_get_clean();
    }

    private function sanitizeFilename($str) {
        return preg_replace('/[\\\\\/:\*\?"<>\|]/u', '', $str);
    }
}
