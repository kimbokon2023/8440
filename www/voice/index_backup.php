<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/load_header.php");

// 권한 체크 (레벨 5 이하만 접근)
if ($level > 5) {
    echo "<script>alert('접근 권한이 없습니다.'); history.back();</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사업자등록증 OCR</title>
    <style>
        .ocr-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
        }
        .ocr-row {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }
        .ocr-col {
            flex: 1;
        }
        .preview-section {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            background: #f8f9fa;
        }
        #preview-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 10px 0;
        }
        #pdf-canvas {
            display: none;
        }
        .raw-text {
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            background: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 300px;
            overflow-y: auto;
            font-size: 12px;
        }
        .form-section {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            background: #fff;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .auto-filled {
            background-color: #fff7cc !important;
            border-color: #ffc107 !important;
            transition: background-color 0.5s ease, border-color 0.5s ease;
        }
        .form-control:focus.auto-filled {
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
        }
        .status-indicator {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-left: 15px;
        }
        .status-waiting {
            background: #e9ecef;
            color: #495057;
        }
        .status-processing {
            background: #cfe2ff;
            color: #084298;
        }
        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }
        .status-error {
            background: #f8d7da;
            color: #842029;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-primary {
            background: #0d6efd;
            color: white;
        }
        .btn-primary:hover {
            background: #0b5ed7;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5c636a;
        }
        .btn-success {
            background: #198754;
            color: white;
        }
        .btn-success:hover {
            background: #157347;
        }
        .save-status {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            display: none;
        }
        .save-status.success {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            display: block;
        }
        .save-status.error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            display: block;
        }
        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .file-input-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .toggle-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }
        .toggle-label {
            font-weight: bold;
            color: #495057;
            font-size: 14px;
        }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 120px;
            height: 34px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        input:checked + .slider:before {
            transform: translateX(86px);
        }
        .slider-text {
            position: absolute;
            color: white;
            font-size: 11px;
            font-weight: bold;
            top: 50%;
            transform: translateY(-50%);
            transition: opacity 0.4s;
        }
        .slider-text-left {
            left: 10px;
        }
        .slider-text-right {
            right: 10px;
        }
        input:not(:checked) + .slider .slider-text-right {
            opacity: 0.5;
        }
        input:checked + .slider .slider-text-left {
            opacity: 0.5;
        }
        .mode-description {
            font-size: 12px;
            color: #6c757d;
            margin-left: 10px;
        }
        .ai-mode-active {
            color: #28a745;
            font-weight: bold;
        }
        .js-mode-active {
            color: #667eea;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/myheader.php"; ?>

<div class="ocr-container">
    <div class="header-section">
        <div>
            <h3><i class="bi bi-file-earmark-text"></i> 사업자등록증 OCR</h3>
            <p style="color: #6c757d; margin: 5px 0;">사업자등록증 이미지 또는 PDF를 업로드하면 자동으로 정보를 추출합니다.</p>
            <p style="color: #999; font-size: 12px; margin: 5px 0;">
                <i class="bi bi-info-circle"></i> 
                처리 시간: 약 10-30초 소요 | 원본 이미지로 OCR 처리 + 오인식 보정 적용
            </p>
            <p style="color: #28a745; font-size: 11px; margin: 5px 0;">
                <i class="bi bi-check-circle"></i> 
                인식률 향상: 원본 이미지 → OCR → 특수문자 제거 → 오타 보정 → 유연한 키워드 매칭 → 자동 입력
            </p>
            <p style="color: #6610f2; font-size: 11px; margin: 5px 0;">
                <i class="bi bi-stars"></i> 
                특수문자 제거 + 오타 허용: "수 식 회 사" → 주식회사 | "대 _ 표 . 자" → 대표자 ✨
            </p>
        </div>
        <div>
            <a href="list.php" class="btn btn-secondary">
                <i class="bi bi-list-ul"></i> 목록
            </a>
        </div>
    </div>

    <div class="toggle-wrapper">
        <span class="toggle-label"><i class="bi bi-gear"></i> OCR 모드:</span>
        <label class="toggle-switch">
            <input type="checkbox" id="mode-toggle">
            <span class="slider">
                <span class="slider-text slider-text-left">JS 사용</span>
                <span class="slider-text slider-text-right">AI API</span>
            </span>
        </label>
        <span id="mode-description" class="mode-description js-mode-active">
            <i class="bi bi-code-square"></i> JavaScript OCR (Tesseract.js) - 브라우저에서 직접 처리
        </span>
    </div>

    <div class="file-input-wrapper">
        <input type="file" id="file-input" class="form-control" accept="application/pdf,image/*" style="max-width: 400px;">
        <span id="status" class="status-indicator status-waiting">대기중</span>
    </div>

    <div class="ocr-row">
        <div class="ocr-col">
            <div class="preview-section">
                <h5><i class="bi bi-image"></i> 미리보기</h5>
                <canvas id="pdf-canvas"></canvas>
                <img id="preview-image" alt="파일을 업로드하면 미리보기가 표시됩니다" style="display: none;">
                <div style="margin-top: 15px;">
                    <h6><i class="bi bi-file-text"></i> OCR 원문</h6>
                    <div id="raw-text" class="raw-text">OCR 결과가 여기에 표시됩니다...</div>
                    <p style="color: #999; font-size: 11px; margin-top: 5px;">
                        <i class="bi bi-lightbulb"></i> 
                        OCR 완료 후 자동으로 우측 폼에 정보가 입력됩니다 (노란색 배경)
                    </p>
                </div>
            </div>
        </div>

        <div class="ocr-col">
            <div class="form-section">
                <h5><i class="bi bi-pencil-square"></i> 사업자 정보 입력</h5>
                <form id="biz-form">
                    <div class="form-group">
                        <label for="biz_no">사업자등록번호 *</label>
                        <input type="text" class="form-control" id="biz_no" name="biz_no" placeholder="000-00-00000" required>
                    </div>
                    <div class="form-group">
                        <label for="company_name">상호명 *</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="상호명" required>
                    </div>
                    <div class="form-group">
                        <label for="representative">대표자명 *</label>
                        <input type="text" class="form-control" id="representative" name="representative" placeholder="대표자명" required>
                    </div>
                    <div class="form-group">
                        <label for="open_date">개업일자</label>
                        <input type="date" class="form-control" id="open_date" name="open_date">
                    </div>
                    <div class="form-group">
                        <label for="address">본점 소재지</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="본점 소재지">
                    </div>
                    <div class="form-group">
                        <label for="type">업태</label>
                        <input type="text" class="form-control" id="type" name="type" placeholder="업태">
                    </div>
                    <div class="form-group">
                        <label for="item">종목</label>
                        <input type="text" class="form-control" id="item" name="item" placeholder="종목">
                    </div>
                    <div class="form-group">
                        <label for="issue_date">발급일자</label>
                        <input type="date" class="form-control" id="issue_date" name="issue_date">
                    </div>

                    <div class="btn-group">
                        <button type="button" id="save-btn" class="btn btn-success">
                            <i class="bi bi-save"></i> 저장
                        </button>
                        <button type="button" id="reset-btn" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> 초기화
                        </button>
                    </div>

                    <div id="save-status" class="save-status"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- PDF.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@4.6.82/build/pdf.min.js"></script>

<!-- Tesseract.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

<script>
// 모든 스크립트가 로드될 때까지 대기
document.addEventListener('DOMContentLoaded', function() {
    // PDF.js Worker 설정
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdn.jsdelivr.net/npm/pdfjs-dist@4.6.82/build/pdf.worker.min.js";
    }
    
    initializeOCR();
});

function initializeOCR() {
// DOM Elements
const fileInput = document.getElementById('file-input');
const statusEl = document.getElementById('status');
const rawTextEl = document.getElementById('raw-text');
const previewEl = document.getElementById('preview-image');
const pdfCanvas = document.getElementById('pdf-canvas');
const saveBtn = document.getElementById('save-btn');
const resetBtn = document.getElementById('reset-btn');
const saveStatus = document.getElementById('save-status');
const modeToggle = document.getElementById('mode-toggle');
const modeDescription = document.getElementById('mode-description');

// 토글 버튼 이벤트
modeToggle.addEventListener('change', function() {
    if (this.checked) {
        // AI API 모드
        modeDescription.innerHTML = '<i class="bi bi-stars"></i> AI API (Claude) - 서버에서 AI로 처리';
        modeDescription.className = 'mode-description ai-mode-active';
    } else {
        // JS 모드
        modeDescription.innerHTML = '<i class="bi bi-code-square"></i> JavaScript OCR (Tesseract.js) - 브라우저에서 직접 처리';
        modeDescription.className = 'mode-description js-mode-active';
    }
});

// Utility Functions
function normalizeBizNo(value) {
    const digits = (value || '').replace(/\D/g, '');
    if (digits.length === 10) {
        return digits.slice(0, 3) + '-' + digits.slice(3, 5) + '-' + digits.slice(5);
    }
    return value || '';
}

function isValidBizNo(value) {
    const digits = (value || '').replace(/\D/g, '');
    if (digits.length !== 10) return false;

    const weights = [1, 3, 7, 1, 3, 7, 1, 3, 5];
    let sum = 0;
    for (let i = 0; i < 9; i++) {
        sum += Number(digits[i]) * weights[i];
    }
    sum += Math.floor((Number(digits[8]) * 5) / 10);
    const check = (10 - (sum % 10)) % 10;
    return check === Number(digits[9]);
}

function toDateISO(dateStr) {
    if (!dateStr) return '';

    // 공백이 많이 섞인 날짜 형식 처리
    // "2015 년 06 월 02 일" -> "2015-06-02"
    let cleaned = dateStr.replace(/\s+/g, ' ').trim();
    
    // 년월일 형식 추출
    const patterns = [
        /(\d{4})\s*년\s*(\d{1,2})\s*월\s*(\d{1,2})\s*일/,
        /(\d{4})\s*년\s*(\d{1,2})\s*월\s*(\d{1,2})/,
        /(\d{4})[\s.\-\/년]+(\d{1,2})[\s.\-\/월]+(\d{1,2})/
    ];
    
    for (let pattern of patterns) {
        const match = cleaned.match(pattern);
    if (match) {
        return `${match[1]}-${match[2].padStart(2, '0')}-${match[3].padStart(2, '0')}`;
    }
    }
    
    return '';
}

// OCR 텍스트 파싱 함수 (개선된 버전 - 공백 + 오타 처리)
function parseBizCert(text) {
    console.log('=== OCR 원문 ===');
    console.log(text);
    console.log('===============');
    
    const T = text || '';
    // 공백 제거 버전 (패턴 매칭용)
    const T_NO_SPACE = T.replace(/\s+/g, '');
    
    // OCR 오인식 보정 함수 (유사 문자 치환)
    const correctOCRErrors = (text) => {
        return text
            // 특수문자 제거 (한글/숫자 사이의 방해 요소) - 최우선 처리
            .replace(/([가-힣])\s*[_\.\-~`'"]\s*([가-힣])/g, '$1 $2')  // 한글 사이 특수문자 제거
            .replace(/([가-힣])\s*[_\.\-~`'"]+\s*/g, '$1 ')  // 한글 뒤 특수문자 제거
            .replace(/\s*[_\.\-~`'"]+\s*([가-힣])/g, ' $1')  // 한글 앞 특수문자 제거
            // 한글 오인식 보정
            .replace(/수\s*식\s*회\s*사/g, '주식회사')  // 수식회사, 수 식 회 사 → 주식회사 (공백 포함)
            .replace(/수식호사/g, '주식회사')  // 수식호사 → 주식회사
            .replace(/주\s*식\s*호\s*사/g, '주식회사')  // 주식호사 → 주식회사
            .replace(/면/g, '명')  // 면 → 명 (법인면 → 법인명, 개업면월일 → 개업명월일)
            .replace(/[=＝]/g, '표')  // = → 표 (대=자 → 대표자)
            .replace(/대\s*표\s*차/g, '대표자')  // 대표차 → 대표자
            .replace(/[丨\|]/g, '')  // 세로선 제거
            .replace(/［/g, '[')
            .replace(/］/g, ']')
            .replace(/얼\s*태/g, '업태')  // 얼태 → 업태
            .replace(/총\s*록/g, '종목')  // 총록 → 종목
            .replace(/총\s*목/g, '종목')  // 총목 → 종목
            .replace(/엘\s*리\s*베\s*이\s*터/g, '엘리베이터')
            .replace(/하\s*장\s*품/g, '부장품')  // 하장품 → 부장품
            .replace(/의\s*장\s*품/g, '의장품')  // 의장품 → 의장품 (보이는 물건)
            // 날짜 관련 오인식 보정
            .replace(/뭘/g, '월')  // 뭘 → 월 (06 뭘 → 06 월)
            .replace(/뭠/g, '월')  // 뭠 → 월
            .replace(/울/g, '월')  // 울 → 월
            .replace(/욀/g, '월')  // 욀 → 월
            .replace(/융/g, '일')  // 융 → 일
            .replace(/임/g, '일')  // 임 → 일
            .replace(/읷/g, '일')  // 읷 → 일
            .replace(/연/g, '년')  // 연 → 년
            .replace(/념/g, '년')  // 념 → 년
            // 숫자 오인식 보정
            .replace(/[oO]/g, '0')  // o, O → 0
            .replace(/[Il]/g, '1')  // I, l → 1
            .replace(/[Zz]/g, '2')  // Z, z → 2 (선택적)
            .replace(/S/g, '5')  // S → 5 (선택적)
            .replace(/b/g, '6');  // b → 6 (선택적)
    };
    
    // 오인식 보정된 버전
    const T_CORRECTED = correctOCRErrors(T);
    const T_CORRECTED_NO_SPACE = T_CORRECTED.replace(/\s+/g, '');
    
    console.log('=== 오인식 보정 후 ===');
    console.log(T_CORRECTED);
    console.log('====================');
    
    // 키워드 유연 검색 함수 (유사도 기반)
    const findKeyword = (text, keywords) => {
        // 공백 제거 버전에서 키워드 찾기
        const textNoSpace = text.replace(/\s+/g, '');
        
        for (let keyword of keywords) {
            const keywordNoSpace = keyword.replace(/\s+/g, '');
            
            // 정확한 매칭
            if (textNoSpace.includes(keywordNoSpace)) {
                return text.indexOf(keyword.split('')[0]); // 원본 텍스트에서 위치 반환
            }
            
            // 유사 매칭 (80% 이상 일치)
            const similarity = calculateSimilarity(textNoSpace, keywordNoSpace);
            if (similarity > 0.8) {
                return 0;
            }
        }
        return -1;
    };
    
    // 문자열 유사도 계산 (간단한 버전)
    const calculateSimilarity = (str1, str2) => {
        const longer = str1.length > str2.length ? str1 : str2;
        const shorter = str1.length > str2.length ? str2 : str1;
        
        if (longer.length === 0) return 1.0;
        
        let matches = 0;
        for (let i = 0; i < shorter.length; i++) {
            if (longer.includes(shorter[i])) matches++;
        }
        
        return matches / longer.length;
    };
    
    // 여러 패턴을 시도하는 함수 (보정된 버전 우선)
    const pickMultiple = (patterns, useNoSpace = false) => {
        // 1순위: 보정된 텍스트에서 시도
        const targetText1 = useNoSpace ? T_CORRECTED_NO_SPACE : T_CORRECTED;
        for (let pattern of patterns) {
            const match = targetText1.match(pattern);
            if (match && match[1]) {
                let result = match[1].trim();
                result = result.replace(/[:\s=]+$/, '').trim();
                result = result.replace(/\s+/g, ' ');
                console.log(`✓ 패턴 매칭 성공 (보정): ${pattern} → "${result}"`);
                return result;
            }
        }
        
        // 2순위: 원본 텍스트에서 시도
        const targetText2 = useNoSpace ? T_NO_SPACE : T;
        for (let pattern of patterns) {
            const match = targetText2.match(pattern);
            if (match && match[1]) {
                let result = match[1].trim();
                result = result.replace(/[:\s=]+$/, '').trim();
                result = result.replace(/\s+/g, ' ');
                console.log(`✓ 패턴 매칭 성공 (원본): ${pattern} → "${result}"`);
                return result;
            }
        }
        
        return '';
    };

    // 사업자등록번호 추출 (공백 제거 버전 사용)
    let bizNo = pickMultiple([
        /등록번호[:\s]*(\d{3}[\-]?\d{2}[\-]?\d{5})/ui,
        /번호[:\s]*(\d{3}[\-]?\d{2}[\-]?\d{5})/ui,
        /(\d{3}[\-]\d{2}[\-]\d{5})/u
    ], true);
    
    // 원본에서도 시도 (공백 포함)
    if (!bizNo) {
        bizNo = pickMultiple([
            /사업자\s*등록\s*번호[^\d\n]*(\d{3}[\s\-]?\d{2}[\s\-]?\d{5})/ui,
            /등록\s*번호[^\d\n]*(\d{3}[\s\-]?\d{2}[\s\-]?\d{5})/ui
        ]);
    }

    // 상호명 추출 (법인명, 단체명 포함)
    let companyName = pickMultiple([
        /법인명\s*\(\s*단체명\s*\)[:\s]*([^\n\r]+?)(?=\s*대)/ui,
        /법\s*인\s*명[:\s\(]*(?:단\s*체\s*명\s*\))?[:\s]*([가-힣\s]+)(?=\s*대)/ui,
        /상\s*호\s*명?[:\s]*([가-힣\s]+)(?=\s*대표자)/ui,
        /상\s*호[:\s]+([가-힣\s]+)/ui
    ]);
    // 공백 제거 버전에서도 시도
    if (!companyName) {
        companyName = pickMultiple([
            /법인명[:\(]?(?:단체명\))?[:\s]*([가-힣]+주식회사[가-힣]+|주식회사[가-힣]+|[가-힣]{2,})/ui,
            /상호명?[:\s]*([가-힣]+)/ui
        ], true);
    }

    // 대표자명 추출 (유연한 키워드 매칭 + 한국 이름 패턴)
    let representative = '';
    const repKeywords = ['대표자명', '대표자', '대표'];
    let foundRep = false;
    
    for (let keyword of repKeywords) {
        // 키워드가 포함된 라인 찾기 (공백 무시)
        const keywordPattern = keyword.split('').join('[\\s]*');
        const lineMatch = T_CORRECTED.match(new RegExp(`${keywordPattern}[^\\n]{0,50}`, 'ui'));
        
        if (lineMatch) {
            const line = lineMatch[0];
            console.log(`대표자 키워드 발견: "${keyword}" → 라인: "${line}"`);
            
            // 한국 이름 패턴 찾기 (2-4자 한글, 공백 포함 가능)
            const namePatterns = [
                // 콜론이나 공백 뒤 한글 이름 (공백 포함)
                /[:\s]+([가-힣]\s*[가-힣]\s*[가-힣]?\s*[가-힣]?)\s*(?=[개발사법세]|$)/u,
                // 한글 2-4자 (공백 제거 후)
                /[:\s]+([가-힣\s]{2,7})\s*(?=[개발사법세]|$)/u,
                // 단순 한글 패턴
                /[:\s]+([가-힣\s]+)/u
            ];
            
            for (let namePattern of namePatterns) {
                const nameMatch = line.match(namePattern);
                if (nameMatch) {
                    // 공백 제거하여 이름 추출
                    const name = nameMatch[1].replace(/\s+/g, '');
                    // 한국 이름 길이 검증 (2-4자)
                    if (name.length >= 2 && name.length <= 4) {
                        representative = name;
                        console.log(`✓ 대표자명 추출 성공: "${representative}" (원본: "${nameMatch[1]}")`);
                        foundRep = true;
                        break;
                    } else if (name.length > 4) {
                        // 4자 넘으면 앞 3자만 (일반적인 한국 이름)
                        representative = name.substring(0, 3);
                        console.log(`✓ 대표자명 추출 성공 (길이 조정): "${representative}" (원본: "${name}")`);
                        foundRep = true;
                        break;
                    }
                }
            }
            if (foundRep) break;
        }
    }
    
    // 추가 시도: 기존 패턴 사용
    if (!representative) {
        representative = pickMultiple([
            /대\s*[=표]\s*자[:\s]*([가-힣\s]{2,10})(?=\s*개업|\s*사업장)/ui,
            /대\s*표\s*자\s*명?[:\s]*([가-힣\s]{2,10})/ui,
            /대\s*표[:\s]+([가-힣\s]{2,10})/ui
        ]);
    }
    if (!representative) {
        representative = pickMultiple([
            /대[=표]자[:\s]*([가-힣]{2,10})/ui,
            /대표자[:\s]*([가-힣]{2,10})/ui
        ], true);
    }
    // 최종 공백 제거 및 길이 검증
    if (representative) {
        representative = representative.replace(/\s+/g, '');
        if (representative.length > 4) {
            representative = representative.substring(0, 3);
        }
    }

    // 개업일자 추출 (유연한 키워드 매칭)
    let openDate = '';
    
    // "개업" 키워드 근처에서 날짜 찾기
    const openKeywords = ['개업연월일', '개업일자', '개업일', '개업'];
    let foundOpen = false;
    
    for (let keyword of openKeywords) {
        // 키워드가 포함된 라인 찾기
        const keywordPattern = keyword.split('').join('[\\s]*');  // 공백 무시
        const lineMatch = T_CORRECTED.match(new RegExp(`${keywordPattern}[^\\n]{0,80}`, 'ui'));
        
        if (lineMatch) {
            const line = lineMatch[0];
            console.log(`개업 키워드 발견: "${keyword}" → 라인: "${line}"`);
            
            // 해당 라인 또는 근처에서 날짜 패턴 찾기 (유연한 패턴)
            const datePatterns = [
                // 년월일이 정확히 있는 경우
                /(\d{4})\s*[년]\s*(\d{1,2})\s*[월]\s*(\d{1,2})\s*[일]/,
                // 년월일이 보정되어 있는 경우
                /(\d{4})\s*년\s*(\d{1,2})\s*월\s*(\d{1,2})\s*일/,
                // . / - 구분자
                /(\d{4})\s*[\.\/\-]\s*(\d{1,2})\s*[\.\/\-]\s*(\d{1,2})/,
                // 공백 없이 붙어있는 경우
                /(\d{4})년(\d{1,2})월(\d{1,2})일/,
                /(\d{4})[\.\-\/](\d{1,2})[\.\-\/](\d{1,2})/,
                // 숫자만 연속 (매우 유연)
                /(\d{4})[^\d]{0,5}(\d{1,2})[^\d]{0,5}(\d{1,2})/
            ];
            
            for (let datePattern of datePatterns) {
                const dateMatch = line.match(datePattern);
                if (dateMatch) {
                    const year = parseInt(dateMatch[1]);
                    const month = parseInt(dateMatch[2]);
                    const day = parseInt(dateMatch[3]);
                    
                    // 유효성 검증 (합리적인 범위)
                    if (year >= 1900 && year <= 2100 && month >= 1 && month <= 12 && day >= 1 && day <= 31) {
                        openDate = `${dateMatch[1]}.${dateMatch[2]}.${dateMatch[3]}`;
                        console.log(`✓ 개업일자 추출 성공: "${openDate}" (패턴: ${datePattern})`);
                        foundOpen = true;
                        break;
                    }
                }
            }
            if (foundOpen) break;
        }
    }
    
    // 추가 시도: 전체 텍스트에서 "개업" 근처 날짜 찾기
    if (!openDate) {
        openDate = pickMultiple([
            /개\s*업[^\d\n]{0,20}(\d{4})\s*년\s*(\d{1,2})\s*월\s*(\d{1,2})/ui,
            /개\s*업[:\s]+(\d{4}[\s년.\-]+\d{1,2}[\s월.\-]+\d{1,2})/ui
        ]);
    }

    // 주소 추출 (본점소재지, 사업장소재지)
    let address = pickMultiple([
        /본\s*점\s*소\s*재\s*지[:\s]*([가-힣\s\d\-]+?)(?=\s*사업의|$)/ui,
        /사업장\s*소재지[:\s]*([가-힣\s\d\-]+?)(?=\s*본점|$)/ui,
        /소\s*재\s*지[:\s]*([가-힣\s\d\-]+)/ui
    ]);

    // 업태 추출 (유연한 키워드 매칭)
    let type = '';
    const typeKeywords = ['업태'];
    let foundType = false;
    
    for (let keyword of typeKeywords) {
        // 키워드가 포함된 라인 찾기 (공백 무시)
        const keywordPattern = keyword.split('').join('[\\s]*');
        // "업태" 키워드부터 "[" 또는 "종목"까지 또는 줄바꿈까지
        const lineMatch = T_CORRECTED.match(new RegExp(`${keywordPattern}[^\\n]{0,100}`, 'ui'));
        
        if (lineMatch) {
            const line = lineMatch[0];
            console.log(`업태 키워드 발견: "${keyword}" → 라인: "${line}"`);
            
            // 업태 값 추출 패턴
            const typePatterns = [
                // [ 업태 | 제조업 [ 종목 형태
                /업\s*태[:\s]*([^\[\]]+?)(?=\s*\[)/ui,
                // 업태 제조업 종목 형태
                /업\s*태[:\s\|]*([^\[\]\n]+?)(?=\s*종\s*목)/ui,
                // 업태 : 제조업 형태
                /업\s*태[:\s]+([가-힣\s]+?)(?=\s*[\[\n]|$)/ui,
                // 단순 패턴
                /업\s*태[:\s]+([^\n\[]+)/ui
            ];
            
            for (let typePattern of typePatterns) {
                const typeMatch = line.match(typePattern);
                if (typeMatch) {
                    type = typeMatch[1].trim();
                    // 불필요한 기호 제거
                    type = type.replace(/[\|\[\]]/g, '').trim();
                    if (type.length > 0) {
                        console.log(`✓ 업태 추출 성공: "${type}"`);
                        foundType = true;
                        break;
                    }
                }
            }
            if (foundType) break;
        }
    }
    
    // 추가 시도: 기존 패턴 사용
    if (!type) {
        type = pickMultiple([
            /사업의\s*종류[:\s]*\[\s*업\s*태\s*\|\s*([^\[\]]+?)\s*\[\s*종\s*목/ui,
            /업\s*태[:\s\|]*([^\[\]종목\n]+?)(?=\s*\[?\s*종\s*목|\||$)/ui,
            /업태[:\s]*([^\n]+?)(?=종목|$)/ui
        ]);
    }

    // 종목 추출 (유연한 키워드 매칭)
    let item = '';
    const itemKeywords = ['종목'];
    let foundItem = false;
    
    for (let keyword of itemKeywords) {
        // 키워드가 포함된 라인 찾기 (공백 무시)
        const keywordPattern = keyword.split('').join('[\\s]*');
        // "종목" 키워드부터 줄바꿈 또는 특정 키워드까지
        const lineMatch = T_CORRECTED.match(new RegExp(`${keywordPattern}[^\\n]{0,100}`, 'ui'));
        
        if (lineMatch) {
            const line = lineMatch[0];
            console.log(`종목 키워드 발견: "${keyword}" → 라인: "${line}"`);
            
            // 종목 값 추출 패턴
            const itemPatterns = [
                // [ 종목 | 엘리베이터부장품 형태
                /종\s*목[:\s\|]*([^\[\]\n]+?)(?=\s*발급|\s*세금|\s*\]|$)/ui,
                // 종목 : 엘리베이터부장품 형태
                /종\s*목[:\s]+([가-힣\s]+?)(?=\s*발급|\s*세금|$)/ui,
                // 단순 패턴
                /종\s*목[:\s]+([^\n\[]+)/ui
            ];
            
            for (let itemPattern of itemPatterns) {
                const itemMatch = line.match(itemPattern);
                if (itemMatch) {
                    item = itemMatch[1].trim();
                    // 불필요한 기호 제거
                    item = item.replace(/[\|\[\]]/g, '').trim();
                    if (item.length > 0) {
                        console.log(`✓ 종목 추출 성공: "${item}"`);
                        foundItem = true;
                        break;
                    }
                }
            }
            if (foundItem) break;
        }
    }
    
    // 추가 시도: 기존 패턴 사용
    if (!item) {
        item = pickMultiple([
            /\[\s*종\s*목\s*\|\s*([^\[\]]+?)(?=\s*발급|\s*세금|$)/ui,
            /종\s*목[:\s\|]*([^\[\]\n]+?)(?=\s*발급|\s*세금|$)/ui,
            /종목[:\s]*([^\n]+?)(?=발급|$)/ui
        ]);
    }

    // 발급일자 추출 (유연한 키워드 매칭)
    let issueDate = '';
    
    // "발급" 또는 "교부" 키워드 근처에서 날짜 찾기
    const issueKeywords = ['발급일자', '발급일', '교부일자', '교부일', '발급', '교부'];
    let foundIssue = false;
    
    for (let keyword of issueKeywords) {
        const keywordPattern = keyword.split('').join('[\\s]*');
        const lineMatch = T_CORRECTED.match(new RegExp(`${keywordPattern}[^\\n]{0,80}`, 'ui'));
        
        if (lineMatch) {
            const line = lineMatch[0];
            console.log(`발급 키워드 발견: "${keyword}" → 라인: "${line}"`);
            
            // 유연한 날짜 패턴
            const datePatterns = [
                /(\d{4})\s*[년]\s*(\d{1,2})\s*[월]\s*(\d{1,2})\s*[일]/,
                /(\d{4})\s*년\s*(\d{1,2})\s*월\s*(\d{1,2})\s*일/,
                /(\d{4})\s*[\.\/\-]\s*(\d{1,2})\s*[\.\/\-]\s*(\d{1,2})/,
                /(\d{4})년(\d{1,2})월(\d{1,2})일/,
                /(\d{4})[\.\-\/](\d{1,2})[\.\-\/](\d{1,2})/,
                // 숫자만 연속 (매우 유연)
                /(\d{4})[^\d]{0,5}(\d{1,2})[^\d]{0,5}(\d{1,2})/
            ];
            
            for (let datePattern of datePatterns) {
                const dateMatch = line.match(datePattern);
                if (dateMatch) {
                    const year = parseInt(dateMatch[1]);
                    const month = parseInt(dateMatch[2]);
                    const day = parseInt(dateMatch[3]);
                    
                    // 유효성 검증
                    if (year >= 1900 && year <= 2100 && month >= 1 && month <= 12 && day >= 1 && day <= 31) {
                        issueDate = `${dateMatch[1]}.${dateMatch[2]}.${dateMatch[3]}`;
                        console.log(`✓ 발급일자 추출 성공: "${issueDate}" (패턴: ${datePattern})`);
                        foundIssue = true;
                        break;
                    }
                }
            }
            if (foundIssue) break;
        }
    }
    
    // 추가 시도: 세무서 근처 날짜 또는 마지막 날짜
    if (!issueDate) {
        // 세무서 앞 날짜
        const taxMatch = T_CORRECTED.match(/(\d{4})\s*년\s*(\d{1,2})\s*월\s*(\d{1,2})\s*일[^\n]*세무서/ui);
        if (taxMatch) {
            issueDate = `${taxMatch[1]}.${taxMatch[2]}.${taxMatch[3]}`;
            console.log(`✓ 발급일자 추출 (세무서 근처): "${issueDate}"`);
        } else {
            // 텍스트의 마지막 날짜를 발급일로 추정
            const dates = T.match(/(\d{4})\s*년\s*(\d{1,2})\s*월\s*(\d{1,2})\s*일/g);
            if (dates && dates.length > 0) {
                issueDate = dates[dates.length - 1];
                console.log(`✓ 발급일자 추출 (마지막 날짜): "${issueDate}"`);
            }
        }
    }

    // 텍스트 정리 함수 (불필요한 공백 제거, 특수문자 정리)
    const cleanText = (text) => {
        if (!text) return '';
        return text
            .replace(/\s+/g, ' ')  // 연속 공백을 하나로
            .replace(/\s*([,.])\s*/g, '$1 ')  // 구두점 뒤 공백 정리
            .replace(/\s+$/, '')  // 끝 공백 제거
            .replace(/^\s+/, '')  // 시작 공백 제거
            .replace(/[\_\=\|]+/g, '')  // 불필요한 특수문자 제거
            .trim();
    };
    
    // 한글 단어 내 공백 제거 (예: "미 래 기 업" -> "미래기업")
    const removeKoreanSpaces = (text) => {
        if (!text) return '';
        let result = text;
        // 반복적으로 한글 사이의 단일 공백 제거
        let prevResult;
        do {
            prevResult = result;
            result = result.replace(/([가-힣])\s+([가-힣])/g, '$1$2');
        } while (result !== prevResult);
        return result;
    };

    // 정규화
    bizNo = normalizeBizNo(bizNo);
    openDate = toDateISO(openDate);
    issueDate = toDateISO(issueDate);

    // 텍스트 정리
    companyName = removeKoreanSpaces(cleanText(companyName));
    representative = removeKoreanSpaces(cleanText(representative));
    address = cleanText(address);
    type = cleanText(type);
    item = cleanText(item);

    // 추출 결과 로깅
    const result = {
        biz_no: bizNo,
        company_name: companyName,
        representative: representative,
        open_date: openDate,
        address: address,
        type: type,
        item: item,
        issue_date: issueDate
    };
    
    console.log('=== 추출 결과 (최종) ===');
    console.log('사업자번호:', bizNo || '❌ 추출 실패');
    console.log('상호명:', companyName || '❌ 추출 실패');
    console.log('대표자:', representative || '❌ 추출 실패');
    console.log('개업일자:', openDate || '❌ 추출 실패');
    console.log('주소:', address || '❌ 추출 실패');
    console.log('업태:', type || '❌ 추출 실패');
    console.log('종목:', item || '❌ 추출 실패');
    console.log('발급일자:', issueDate || '❌ 추출 실패');
    console.log('========================');
    
    // 성공률 계산
    const totalFields = 8;
    const successCount = [bizNo, companyName, representative, openDate, address, type, item, issueDate]
        .filter(v => v).length;
    const successRate = Math.round((successCount / totalFields) * 100);
    console.log(`✓ 추출 성공률: ${successCount}/${totalFields} (${successRate}%)`);
    console.log('========================');

    return result;
}

// 이미지 전처리 (OCR 인식률 향상)
function preprocessImage(canvas) {
    const ctx = canvas.getContext('2d');
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;

    // 1. 그레이스케일 변환
    for (let i = 0; i < data.length; i += 4) {
        const avg = (data[i] + data[i + 1] + data[i + 2]) / 3;
        data[i] = avg;     // R
        data[i + 1] = avg; // G
        data[i + 2] = avg; // B
    }

    // 2. 대비 증가 (Contrast Enhancement)
    const factor = 1.5; // 대비 계수 (1.0 = 원본, 값이 클수록 대비 증가)
    for (let i = 0; i < data.length; i += 4) {
        data[i] = Math.min(255, Math.max(0, factor * (data[i] - 128) + 128));
        data[i + 1] = Math.min(255, Math.max(0, factor * (data[i + 1] - 128) + 128));
        data[i + 2] = Math.min(255, Math.max(0, factor * (data[i + 2] - 128) + 128));
    }

    // 3. 이진화 (Binarization) - Otsu's method 간소화 버전
    // 밝은 배경의 문서에 적합
    const threshold = 128; // 임계값
    for (let i = 0; i < data.length; i += 4) {
        const brightness = data[i];
        const value = brightness > threshold ? 255 : 0;
        data[i] = value;
        data[i + 1] = value;
        data[i + 2] = value;
    }

    ctx.putImageData(imageData, 0, 0);
    return canvas;
}

// PDF를 이미지로 변환 (원본 해상도 유지)
async function pdfToImage(file) {
    const arrayBuffer = await file.arrayBuffer();
    const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
    const page = await pdf.getPage(1);
    
    // 원본 크기로 렌더링
    const viewport = page.getViewport({ scale: 2.0 });

    const canvas = pdfCanvas;
    const context = canvas.getContext('2d');
    canvas.width = viewport.width;
    canvas.height = viewport.height;

    await page.render({ canvasContext: context, viewport: viewport }).promise;
    
    // 원본 이미지 그대로 반환 (전처리 제거)
    return canvas.toDataURL('image/png');
}

// 일반 이미지 원본 유지 (전처리 제거)
async function preprocessImageFile(imageDataURL) {
    // 원본 이미지를 그대로 반환
    return Promise.resolve(imageDataURL);
}

// AI API를 사용한 OCR 처리
async function runAIOCR(imageDataURL, rawText) {
    updateStatus('AI API 호출중...', 'processing');

    try {
        // 현재 페이지와 같은 디렉토리의 claude_api.php
        const currentPath = window.location.pathname;
        const apiUrl = currentPath.substring(0, currentPath.lastIndexOf('/')) + '/claude_api.php';

        console.log('Current path:', currentPath);
        console.log('API URL:', apiUrl);

        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                image: imageDataURL,
                raw_text: rawText
            })
        });

        console.log('Response status:', response.status);
        console.log('Response URL:', response.url);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('HTTP Error:', response.status, errorText);
            throw new Error(`Claude API 호출 실패 (HTTP ${response.status})\nURL: ${response.url}`);
        }

        const result = await response.json();
        console.log('API Response:', result);

        if (!result.ok) {
            console.error('API Error:', result);
            throw new Error(result.error || 'AI API 호출 실패');
        }

        updateStatus('AI 분석 완료', 'completed');
        return result.data;
    } catch (error) {
        console.error('AI API Error:', error);
        updateStatus('AI API 실패: ' + error.message, 'error');
        throw error;
    }
}

// OCR 실행 (최적화된 설정)
async function runOCR(imageDataURL) {
    updateStatus('OCR 처리중...', 'processing');

    try {
        // Tesseract.js 실행 (한글 최적화 설정)
        const { data } = await Tesseract.recognize(
            imageDataURL,
            'kor+eng',  // 한글 + 영어 동시 인식
            {
                logger: (m) => {
                    if (m.status === 'recognizing text') {
                        updateStatus(`인식중... ${Math.round(m.progress * 100)}%`, 'processing');
                    }
                    if (m.status === 'loading lang') {
                        updateStatus('언어팩 다운로드중...', 'processing');
                    }
                    if (m.status === 'initializing tesseract') {
                        updateStatus('OCR 엔진 초기화중...', 'processing');
                    }
                },
                langPath: 'https://tessdata.projectnaptha.com/4.0.0',
                // Tesseract 파라미터 최적화
                tessedit_pageseg_mode: '1',  // Automatic page segmentation with OSD
                tessedit_char_whitelist: '',  // 모든 문자 허용
                preserve_interword_spaces: '1'  // 단어 간 공백 유지
            }
        );

        console.log('=== OCR 신뢰도 정보 ===');
        console.log('평균 신뢰도:', data.confidence + '%');
        console.log('===================');

        updateStatus('OCR 완료', 'completed');
        return data.text || '';
    } catch (error) {
        console.error('OCR Error:', error);
        updateStatus('OCR 실패', 'error');
        throw error;
    }
}

// 상태 업데이트
function updateStatus(message, status) {
    statusEl.textContent = message;
    statusEl.className = 'status-indicator status-' + status;
}

// 폼 필드 자동입력 (개선된 버전)
function fillFormFields(fields) {
    let filledCount = 0;
    let totalFields = Object.keys(fields).length;
    
    console.log('=== 필드 자동입력 시작 ===');
    
    Object.keys(fields).forEach(key => {
        const element = document.getElementById(key);
        const value = fields[key];
        
        console.log(`${key}: "${value}" → ${value ? '입력' : '빈값'}`);
        
        if (element) {
            if (value) {
                element.value = value;
            element.classList.add('auto-filled');
                filledCount++;
                
                // 애니메이션 효과
                element.style.transition = 'background-color 0.5s';
                setTimeout(() => {
                    element.style.backgroundColor = '#fff7cc';
                }, 50);
            } else {
                element.value = '';
                element.classList.remove('auto-filled');
                element.style.backgroundColor = '';
            }
        }
    });
    
    console.log(`총 ${filledCount}/${totalFields} 필드 입력 완료`);
    console.log('=========================');

    // 사업자번호 유효성 검증
    const bizNo = document.getElementById('biz_no').value;
    if (bizNo) {
        if (isValidBizNo(bizNo)) {
            updateStatus(`OCR 완료 (${filledCount}개 필드 자동입력) ✓`, 'completed');
        } else {
            updateStatus(`OCR 완료 (사업자번호 검증 실패 - 수동 확인 필요)`, 'error');
        }
    } else {
        if (filledCount > 0) {
            updateStatus(`OCR 완료 (${filledCount}개 필드 자동입력)`, 'completed');
        } else {
            updateStatus('OCR 완료 (자동 추출 실패 - 수동 입력 필요)', 'error');
        }
    }
}

// 파일 선택 이벤트
fileInput.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    rawTextEl.textContent = 'OCR 처리중 ...';
    previewEl.style.display = 'none';
    updateStatus('파일 처리중 ...', 'processing');

    // 이전 입력 필드 초기화
    document.querySelectorAll('.form-control').forEach(el => {
        el.value = '';
        el.classList.remove('auto-filled');
    });

    let imageDataURL = null;
    const useAI = modeToggle.checked;

    try {
        if (file.type === 'application/pdf') {
            updateStatus('PDF 변환중...', 'processing');
            imageDataURL = await pdfToImage(file);
            previewEl.src = imageDataURL;
            previewEl.style.display = 'block';
        } else if (file.type.startsWith('image/')) {
            updateStatus('원본 이미지 로딩중...', 'processing');
            const originalDataURL = await new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) => resolve(e.target.result);
                reader.readAsDataURL(file);
            });

            // 원본 이미지 그대로 사용
            imageDataURL = await preprocessImageFile(originalDataURL);
            previewEl.src = originalDataURL; // 미리보기도 원본으로
            previewEl.style.display = 'block';
        } else {
            updateStatus('지원하지 않는 파일 형식', 'error');
            return;
        }

        if (useAI) {
            // AI API 모드
            updateStatus('Tesseract.js로 텍스트 추출중...', 'processing');
            const text = await runOCR(imageDataURL);
            rawTextEl.textContent = text;

            // AI API로 데이터 추출
            updateStatus('AI API로 데이터 분석중...', 'processing');
            const fields = await runAIOCR(imageDataURL, text);

            console.log('=== AI API 추출 결과 ===');
            console.log(fields);
            console.log('======================');

            fillFormFields(fields);
        } else {
            // JavaScript OCR 모드
            const text = await runOCR(imageDataURL);
            rawTextEl.textContent = text;

            // 파싱 후 폼 자동입력
            const fields = parseBizCert(text);
            fillFormFields(fields);
        }

    } catch (error) {
        console.error('처리 오류:', error);
        rawTextEl.textContent = '오류가 발생했습니다: ' + error.message;
        updateStatus('파일 처리 실패', 'error');
    }
});

// 저장 버튼
saveBtn.addEventListener('click', async () => {
    const formData = {};
    const form = document.getElementById('biz-form');

    // 필수 필드 검증
    const requiredFields = ['biz_no', 'company_name', 'representative'];
    let isValid = true;

    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            element.style.borderColor = 'red';
            isValid = false;
        } else {
            element.style.borderColor = '#ddd';
        }
    });

    if (!isValid) {
        showSaveStatus('필수 항목을 입력해주세요.', 'error');
        return;
    }

    // 폼 데이터 수집
    new FormData(form).forEach((value, key) => {
        formData[key] = value;
    });
    formData['raw_text'] = rawTextEl.textContent;

    try {
        const response = await fetch('save_biz.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (result.ok) {
            showSaveStatus(`저장 성공 완료 (ID: ${result.id})`, 'success');
            setTimeout(() => {
                if (confirm('목록으로 이동하시겠습니까?')) {
                    location.href = 'list.php';
                }
            }, 1000);
        } else {
            showSaveStatus('저장 실패: ' + (result.error || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('저장 오류:', error);
        showSaveStatus('저장 중 오류가 발생했습니다.', 'error');
    }
});

// 초기화 버튼
resetBtn.addEventListener('click', () => {
    if (confirm('입력한 내용을 초기화하시겠습니까?')) {
        document.getElementById('biz-form').reset();
        document.querySelectorAll('.form-control').forEach(el => {
            el.classList.remove('auto-filled');
            el.style.borderColor = '#ddd';
        });
        rawTextEl.textContent = 'OCR 결과가 여기에 표시됩니다...';
        previewEl.style.display = 'none';
        updateStatus('대기중', 'waiting');
        saveStatus.style.display = 'none';
        fileInput.value = '';
    }
});

// 저장 상태 표시
function showSaveStatus(message, type) {
    saveStatus.textContent = message;
    saveStatus.className = 'save-status ' + type;
    saveStatus.style.display = 'block';

    if (type === 'success') {
        setTimeout(() => {
            saveStatus.style.display = 'none';
        }, 3000);
    }
}
} // initializeOCR 함수 종료

// 페이지 로드 완료 시 loader 숨기기
window.addEventListener('load', function() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'none';
    }
});
</script>

</body>
</html>
