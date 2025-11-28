<?php
/**
 * 발주서 PDF 생성 클래스
 */

require_once __DIR__ . '/../bootstrap.php';

// Dompdf 오토로드
$dompdfAutoload = getDocumentRoot() . '/_dompdf/vendor/autoload.php';
if (file_exists($dompdfAutoload)) {
    require_once $dompdfAutoload;
}

use Dompdf\Dompdf;
use Dompdf\Options;

class OrderPdfGenerator {
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

    public function generate($orderId) {
        // 1. 데이터 조회
        $order = $this->getOrderData($orderId);
        if (!$order) {
            throw new Exception("발주서를 찾을 수 없습니다.");
        }

        // 2. HTML 생성
        $html = $this->renderHtml($order);

        // 3. PDF 렌더링
        $this->dompdf->loadHtml($html, 'UTF-8');
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        return $this->dompdf->output();
    }

    public function stream($orderId, $download = true) {
        $pdfContent = $this->generate($orderId);
        $order = $this->getOrderData($orderId);
        
        $contactName = $order['contact_name'] ?? '거래처';
        $filename = '발주서_' . $this->sanitizeFilename($contactName) . '_' . date('y.m.d') . '.pdf';

        $this->dompdf->stream($filename, ['Attachment' => $download]);
    }

    private function getOrderData($id) {
        $sql = "SELECT * FROM `orders` WHERE id = :id AND is_deleted = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function renderHtml($order) {
        // 데이터 전처리
        $items = !empty($order['order_items']) ? json_decode($order['order_items'], true) : [];
        if (!is_array($items)) $items = [];

        // 변수 준비
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

        // 합계 계산
        $total_supply = 0;
        $total_tax = 0;
        foreach ($items as $item) {
            $quantity = (float)str_replace(',', '', $item['수량'] ?? $item['quantity'] ?? 0);
            $unit_price = (float)str_replace(',', '', $item['단가'] ?? $item['unit_price'] ?? 0);
            $supply = $quantity * $unit_price;
            $tax = round($supply * 0.1);
            $total_supply += $supply;
            $total_tax += $tax;
        }
        $grand_total = $total_supply + $total_tax;

        // 폰트 경로
        $fontPath = realpath($this->baseDir . '/asset/fonts/NotoSansKR-Regular.ttf');
        $fontUrl = $fontPath ? 'file://' . $fontPath : '';

        // 헬퍼 함수 (Closure로 정의하거나 private 메서드로 분리 가능, 여기선 간단히 내부 변수로)
        $esc = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
        $nf = function ($v) { return number_format((float)str_replace(',', '', (string)$v)); };
        $ymdKorean = function ($ymd) {
            if (!$ymd || $ymd === '0000-00-00') return '';
            $ts = strtotime($ymd);
            return $ts ? date('Y년 n월 j일', $ts) : '';
        };
        $formatPhone = function ($phone) {
            // 간단한 포맷팅 로직 (기존 코드 참조)
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($phone) === 11) return substr($phone, 0, 3) . '-' . substr($phone, 3, 4) . '-' . substr($phone, 7);
            if (strlen($phone) === 10) return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6); // 02 제외 일반화
            return $phone; 
        };

        // HTML 템플릿 시작
        ob_start();
        ?>
        <!doctype html>
        <html lang="ko">
        <head>
        <meta charset="utf-8">
        <style>
            @page { margin: 10mm 8mm 10mm 8mm; }
            @font-face {
                font-family: 'NotoSansKR';
                src: url('<?= $fontUrl ?>') format('truetype');
                font-weight: 400;
                font-style: normal;
            }
            html, body { font-family: 'NotoSansKR', sans-serif; font-size: 10pt; color: #111; line-height: 1.5; }
            table { width: 100%; border-collapse: collapse; font-size: 9pt; }
            th, td { border: 1px solid #000; padding: 3pt 4pt; vertical-align: middle; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .fw700 { font-weight: 700; }
            /* ... 기존 스타일 ... */
            .title-section { text-align: center; margin-bottom: 8pt; position: relative; }
            .title-section h1 { font-size: 18pt; font-weight: 700; margin: 0; padding-bottom: 4pt; letter-spacing: 0.8em; }
            .title-section::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px; background: #000; }
            .top-layout { display: table; width: 100%; margin-bottom: 8pt; margin-top: 4pt; }
            .top-left-cell { display: table-cell; width: 45%; vertical-align: top; padding-right: 10pt; }
            .top-right-cell { display: table-cell; width: 55%; vertical-align: top; padding-left: 10pt; }
            .supplier-table th { background: #f2f2f2; text-align: center; }
            #footer { position: fixed; bottom: -18mm; left: 0; right: 0; height: 8mm; font-size: 8pt; color: #666; text-align: center; border-top: 1px solid #000; padding-top: 4pt; }
        </style>
        </head>
        <body>
        <div id="footer">경기도 김포시 양촌읍 흥신로 220-27 | TEL: 031-983-8440 | FAX: 031-982-8449</div>
        
        <div class="title-section"><h1>발 주 서</h1></div>
        
        <?php if ($order_no): ?>
        <div style="text-align: left; margin-bottom: 6pt; font-size: 9pt;">
            <strong>NO :</strong> <?= $esc($order_no) ?>
            <div style="border-bottom: 1px solid #000; margin-top: 4pt; width: 100%;"></div>
        </div>
        <?php endif; ?>

        <div class="top-layout">
            <div class="top-left-cell">
                <div style="font-size: 12pt; font-weight: 700; margin-bottom: 8pt;"><?= $esc($contact_name ?: '거래처') ?> 貴下</div>
                <div style="font-size: 9pt; line-height: 1.6;">
                    <div><strong>발주일자:</strong> <?= $ymdKorean($issue_date) ?></div>
                    <div><strong>전화번호:</strong> <?= $esc($formatPhone($phone)) ?> 
                    <?php if ($fax): ?> <strong>팩스번호:</strong> <?= $esc($formatPhone($fax)) ?><?php endif; ?>
                    </div>
                    <div style="font-size: 12pt; margin-top: 6pt; font-weight: 600;"><strong>프로젝트/현장명: <?= $esc($project_site) ?></strong></div>
                </div>
            </div>
            <div class="top-right-cell">
                <table class="supplier-table">
                    <tr>
                        <th rowspan="5" style="width: 20pt; padding: 4pt 2pt;">발<br>주<br>자</th>
                        <th>등록번호</th>
                        <td colspan="3"><?= $esc($business_registration_number ?: '722-88-00035') ?></td>
                    </tr>
                    <tr>
                        <th>상호</th>
                        <td class="fw700"><?= $esc($supplier_name) ?></td>
                        <th>성명</th>
                        <td>소현철 
                            <?php 
                            $stampPath = getDocumentRoot() . '/img/miraestamp.png';
                            if (file_exists($stampPath)) {
                                $base64 = base64_encode(file_get_contents($stampPath));
                                echo '<img src="data:image/png;base64,' . $base64 . '" style="width: 20px; height: 20px; vertical-align: middle; margin-left: 4pt;">';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>전화번호</th>
                        <td><?= $esc($formatPhone($supplier_phone)) ?></td>
                        <th>팩스번호</th>
                        <td><?= $esc($formatPhone($supplier_fax)) ?></td>
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
                </table>
            </div>
        </div>

        <div style="margin: 12pt 0; font-size: 9pt;">
            <div style="margin-bottom: 6pt;">납기일 내에 인도해 주시기 바랍니다.</div>
            <div>
                <span class="fw700" style="margin-right: 8pt;">합계금액:</span>
                <span class="fw700" style="font-size: 11pt;">일금 <?= $nf($grand_total) ?>원정</span>
                <span style="font-size: 9pt; margin-left: 4pt;">(부가세포함)</span>
            </div>
        </div>

        <?php if (!empty($items)): ?>
        <table>
            <thead>
                <tr style="background: #f5f5f5;">
                    <th style="width: 5%;">순번</th>
                    <th style="width: 28%;">품목</th>
                    <th style="width: 18%;">규격</th>
                    <th style="width: 7%;">수량</th>
                    <th style="width: 10%;">단가</th>
                    <th style="width: 11%;">공급가액</th>
                    <th style="width: 11%;">세액</th>
                    <th style="width: 10%;">비고</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($items as $item): 
                    $q = (float)str_replace(',', '', $item['수량'] ?? $item['quantity'] ?? 0);
                    $p = (float)str_replace(',', '', $item['단가'] ?? $item['unit_price'] ?? 0);
                    $s = $q * $p;
                    $t = round($s * 0.1);
                ?>
                <tr>
                    <td class="text-center"><?= $i++ ?></td>
                    <td><?= $esc($item['품목'] ?? $item['item_name'] ?? '') ?></td>
                    <td><?= $esc($item['규격'] ?? $item['spec'] ?? '') ?></td>
                    <td class="text-right"><?= $q ? $nf($q) : '' ?></td>
                    <td class="text-right"><?= $p ? $nf($p) : '' ?></td>
                    <td class="text-right"><?= $s ? $nf($s) : '' ?></td>
                    <td class="text-right"><?= $t ? $nf($t) : '' ?></td>
                    <td><?= $esc($item['비고'] ?? $item['remarks'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    private function sanitizeFilename($str) {
        return preg_replace('/[\\\\\/:\*\?"<>\|]/u', '', $str);
    }
}
