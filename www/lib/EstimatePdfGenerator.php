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
        $estimate_no = $estimate['estimate_no'] ?? '';
        $issue_date = $estimate['issue_date'] ?? '';
        $supplier_name = $estimate['supplier_name'] ?? '주식회사 미래기업';
        if ($supplier_name === '주식회사미래기업') $supplier_name = '주식회사 미래기업';

        $supplier_address = $estimate['supplier_address'] ?? '경기도 김포시 양촌읍 흥신로 220-27';
        $supplier_address = str_replace('(흥신리)', '', $supplier_address);
        $supplier_address = trim($supplier_address);
        
        $business_type = $estimate['business_type'] ?? '제조업';
        $business_item = $estimate['business_item'] ?? '엘리베이터의장품';
        $supplier_phone = $estimate['supplier_phone'] ?? '031-983-8440';
        $supplier_fax = $estimate['supplier_fax'] ?? '031-982-8449';
        $contact_name = $estimate['contact_name'] ?? '';
        $phone = $estimate['phone'] ?? '';
        $fax = $estimate['fax'] ?? '';
        $business_registration_number = $estimate['business_registration_number'] ?? '';
        $project_site = $estimate['project_site'] ?? '';

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

        // 헬퍼 함수
        $esc = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
        $nf = function ($v) { return number_format((float)str_replace(',', '', (string)$v)); };
        $ymdKorean = function ($ymd) {
            if (!$ymd || $ymd === '0000-00-00') return '';
            $ts = strtotime($ymd);
            return $ts ? date('Y년 m월 d일', $ts) : '';
        };
        $formatPhone = function ($phone) {
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($phone) === 11) return substr($phone, 0, 3) . '-' . substr($phone, 3, 4) . '-' . substr($phone, 7);
            if (strlen($phone) === 10) return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
            return $phone; 
        };
        
        // 숫자 한글 변환 (간이 구현)
        $numToKorean = function($number) {
            $num = (string)$number;
            $han = ['','일','이','삼','사','오','육','칠','팔','구'];
            $unit = ['','십','백','천'];
            $unit2 = ['','만','억','조','경'];
            
            $result = '';
            $len = strlen($num);
            $j = 0;
            
            for ($i = $len - 1; $i >= 0; $i--) {
                $n = $num[$len - 1 - $i];
                if ($n > 0) {
                    $result .= $han[$n];
                    $result .= $unit[$i % 4];
                }
                if ($i % 4 == 0) {
                    $result .= $unit2[$i / 4];
                }
            }
            return $result ? $result : '영';
        };

        // HTML 템플릿 시작
        ob_start();
        ?>
        <!doctype html>
        <html lang="ko">
        <head>
        <meta charset="utf-8">
        <style>
            @page { margin: 7mm; }
            @font-face {
                font-family: 'NotoSansKR';
                src: url('<?= $fontUrl ?>') format('truetype');
                font-weight: 400;
                font-style: normal;
            }
            body { font-family: 'NotoSansKR', sans-serif; font-size: 9pt; color: #000; line-height: 1.3; }
            
            /* Layout Table */
            .layout-table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 10pt; }
            .layout-table td { border: 1px solid #000; padding: 3pt; vertical-align: middle; }
            
            /* Left Side (Recipient) */
            .recipient-cell { width: 40%; vertical-align: top; padding: 10pt; position: relative; }
            .recipient-name { font-size: 16pt; font-weight: bold; text-align: center; text-decoration: underline; margin-bottom: 15pt; }
            .order-info { margin-bottom: 3pt; font-size: 10pt; }
            .delivery-note { margin-top: 20pt; font-weight: bold; text-align: center; }
            
            /* Right Side (Supplier) */
            .supplier-label { background-color: #e0e0e0; text-align: center; font-weight: bold; width: 50pt; white-space: nowrap; font-size: 8pt; }
            .vertical-header { background-color: #e0e0e0; width: 25pt; text-align: center; font-weight: bold; vertical-align: middle; font-size: 9pt; }
            .supplier-content { font-size: 8pt; }
            
            /* Items Table */
            .items-table { width: 100%; border-collapse: collapse; margin-top: 5pt; }
            .items-table th { 
                background-color: #e0e0e0; 
                color: #000; 
                border: 1px solid #ccc; 
                padding: 3pt;
                font-weight: bold;
                text-align: center;
            }
            .items-table td { 
                padding: 3pt;
                border: 1px solid #eee;
            }
            .items-table tr:nth-child(even) { background-color: #f9f9f9; }
            .items-table tr:last-child td { border-bottom: 1px solid #000; }
            
            /* Utils */
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .text-bold { font-weight: bold; }
        </style>
        </head>
        <body>

            <!-- Header Title -->
            <div style="text-align: center; margin-bottom: 20pt;">
                <h1 style="font-size: 24pt; font-weight: bold; margin: 0;">견 적 서</h1>
            </div>

            <!-- Main Layout Table -->
            <table class="layout-table">
                <tr>
                    <!-- Left: Recipient Info -->
                    <td class="recipient-cell">
                        <div class="recipient-name"><?= $esc($contact_name ?: '거래처') ?> 귀하</div>
                        <div class="order-info">견적일자 : <?= $ymdKorean($issue_date) ?></div>
                        <div class="order-info">
                            전화번호 : <?= $esc($formatPhone($phone)) ?> &nbsp;&nbsp; 
                            <?php if ($fax): ?>팩스번호 : <?= $esc($formatPhone($fax)) ?><?php endif; ?>
                        </div>
                        <div class="delivery-note">아래와 같이 견적합니다.</div>
                    </td>

                    <!-- Right: Supplier Info -->
                    <td style="padding: 0; border: none; width: 60%; vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td rowspan="5" class="vertical-header">공<br>급<br>자</td>
                                <td class="supplier-label">등록번호</td>
                                <td colspan="3" class="text-center"><?= $esc($business_registration_number ?: '722-88-00035') ?></td>
                            </tr>
                            <tr>
                                <td class="supplier-label">상호</td>
                                <td class="text-center"><?= $esc($supplier_name) ?></td>
                                <td class="supplier-label" style="width: 40pt;">성명</td>
                                <td class="text-center" style="position: relative;">
                                    소현철
                                    <?php 
                                    $stampPath = getDocumentRoot() . '/img/miraestamp.png';
                                    if (file_exists($stampPath)) {
                                        $base64 = base64_encode(file_get_contents($stampPath));
                                        echo '<img src="data:image/png;base64,' . $base64 . '" style="width: 7mm; vertical-align: middle; margin-left: 5pt;">';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="supplier-label">주소</td>
                                <td colspan="3" class="text-center" style="font-size: 8pt;"><?= $esc($supplier_address) ?></td>
                            </tr>
                            <tr>
                                <td class="supplier-label">업태</td>
                                <td class="text-center"><?= $esc($business_type) ?></td>
                                <td class="supplier-label">종목</td>
                                <td class="text-center"><?= $esc($business_item) ?></td>
                            </tr>
                            <tr>
                                <td class="supplier-label">전화번호</td>
                                <td class="text-center"><?= $esc($supplier_phone) ?></td>
                                <td class="supplier-label">팩스번호</td>
                                <td class="text-center"><?= $esc($supplier_fax) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Bottom Row: Total & Project -->
                <tr>
                    <td colspan="2" style="padding: 0; border-top: 2px solid #000;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 60%; padding: 8pt; border-right: 1px solid #000; font-weight: bold;">
                                    합계금액 : 일금 <?= $numToKorean($grand_total) ?> 원정 (₩ <?= $nf($grand_total) ?>) (부가세포함)
                                </td>
                                <td style="padding: 8pt; font-weight: bold;">
                                    프로젝트/현장 : <?= $esc($project_site) ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div style="margin-bottom: 5pt; font-size: 9pt; color: #666;">
                * 견적 유효기간 내에 발주해 주시기 바랍니다.
            </div>

            <?php if (!empty($items)): ?>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 35%;">품목명</th>
                        <th style="width: 15%;">규격</th>
                        <th style="width: 8%;">수량</th>
                        <th style="width: 12%;">단가</th>
                        <th style="width: 12%;">공급가액</th>
                        <th style="width: 13%;">세액</th>
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
                        <td style="padding-left: 5pt;"><?= $esc($item['품목'] ?? $item['item_name'] ?? '') ?></td>
                        <td class="text-center"><?= $esc($item['규격'] ?? $item['spec'] ?? '') ?></td>
                        <td class="text-right" style="padding-right: 5pt;"><?= $q ? $nf($q) : '' ?></td>
                        <td class="text-right" style="padding-right: 5pt;"><?= $p ? $nf($p) : '' ?></td>
                        <td class="text-right" style="padding-right: 5pt;"><?= $s ? $nf($s) : '' ?></td>
                        <td class="text-right" style="padding-right: 5pt;"><?= $t ? $nf($t) : '' ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <!-- 빈 줄 채우기 (옵션) -->
                    <?php for($k=0; $k<max(0, 10 - count($items)); $k++): ?>
                    <tr>
                        <td style="height: 18pt;"></td> <!-- 번호 없음 -->
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <!-- Bottom Info Table -->
            <table class="items-table" style="margin-top: 20pt;">
                <tr>
                    <th style="width: 15%;">납기일자</th>
                    <td style="width: 35%;"><?= $ymdKorean($estimate['delivery_date'] ?? '') ?></td>
                    <th style="width: 15%;">납품장소</th>
                    <td style="width: 35%;"><?= $esc($estimate['delivery_location'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>유효일자</th>
                    <td><?= $ymdKorean($estimate['valid_date'] ?? '') ?></td>
                    <th>결제조건</th>
                    <td><?= $esc($estimate['payment_terms'] ?? '') ?></td>
                </tr>
                <tr>
                    <th style="height: 60pt;">비 고</th>
                    <td colspan="3" style="vertical-align: top; text-align: left; padding: 5pt;"><?= nl2br($esc($estimate['note'] ?? '')) ?></td>
                </tr>
            </table>

            <div style="text-align: right; margin-top: 5pt; font-size: 9pt;">
                Print Date : <?= date('Y-m-d H:i') ?>
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
