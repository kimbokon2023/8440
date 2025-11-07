# 음성 녹음 및 AI 요약 시스템 개발 문서

## 프로젝트 개요

Chrome 브라우저의 Web Speech API를 활용한 실시간 음성 인식 및 Claude AI를 이용한 회사 기록용 요약 생성 시스템

**주요 기능:**
- 실시간 음성 → 텍스트 변환 (무료, API 키 불필요)
- 오디오 파형 실시간 시각화
- AI 기반 회사 기록용 요약 생성
- 텍스트 복사/다운로드 기능

---

## 시스템 아키텍처

### 디렉토리 구조
```
voice/
├── index.php           # 메인 UI (음성 녹음 인터페이스)
├── summary_api.php     # Claude API 요약 백엔드
└── claude_api.php      # (사용안함 - OpenAI Whisper용)
```

### 기술 스택
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: PHP 7+
- **APIs**:
  - Web Speech API (Google) - 음성 인식
  - Claude 3.5 Haiku API - 텍스트 요약
- **Browser APIs**:
  - MediaRecorder API - 오디오 스트림
  - Web Audio API - 파형 시각화
  - Clipboard API - 복사 기능

---

## 주요 기능 상세

### 1. 실시간 음성 인식 (Web Speech API)

**특징:**
- ✅ 완전 무료 (API 키 불필요)
- ✅ 실시간 변환 (말하는 즉시 텍스트 표시)
- ✅ 한국어 지원 (`ko-KR`)
- ✅ 임시 결과(회색) + 최종 결과(검정색) 동시 표시

**주요 코드:**
```javascript
// Web Speech API 초기화
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
recognition = new SpeechRecognition();
recognition.lang = 'ko-KR';
recognition.continuous = true;      // 연속 인식
recognition.interimResults = true;  // 중간 결과 표시
recognition.maxAlternatives = 1;

// 음성 인식 결과 처리
recognition.onresult = (event) => {
    interimTranscript = '';

    for (let i = event.resultIndex; i < event.results.length; i++) {
        const transcript = event.results[i][0].transcript;

        if (event.results[i].isFinal) {
            finalTranscript += transcript + ' ';
        } else {
            interimTranscript += transcript;
        }
    }

    // 화면 업데이트
    const displayText = finalTranscript +
        (interimTranscript ? '<span style="color: #999;">' + interimTranscript + '</span>' : '');
    transcriptEl.innerHTML = displayText;
};
```

**에러 처리:**
- `no-speech`: 음성 미감지 → 자동 재시작
- `aborted`: 사용자 중단 → 무시
- `not-allowed`: 마이크 권한 거부 → 알림 표시

---

### 2. 오디오 파형 시각화 (Web Audio API)

**구현 방식:**
- Canvas 2D API를 사용한 실시간 파형 그리기
- AudioContext + AnalyserNode를 통한 주파수 분석

**주요 코드:**
```javascript
// 오디오 스트림 시작
mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });

audioContext = new (window.AudioContext || window.webkitAudioContext)();
analyser = audioContext.createAnalyser();
const source = audioContext.createMediaStreamSource(mediaStream);
source.connect(analyser);

analyser.fftSize = 2048;
const bufferLength = analyser.frequencyBinCount;
dataArray = new Uint8Array(bufferLength);

// 파형 그리기 (애니메이션 프레임)
function drawWaveform() {
    animationId = requestAnimationFrame(drawWaveform);
    analyser.getByteTimeDomainData(dataArray);

    canvasCtx.fillStyle = '#f8f9fa';
    canvasCtx.fillRect(0, 0, waveformCanvas.width, waveformCanvas.height);

    canvasCtx.lineWidth = 2;
    canvasCtx.strokeStyle = '#667eea';
    canvasCtx.beginPath();

    const sliceWidth = waveformCanvas.width / dataArray.length;
    let x = 0;

    for (let i = 0; i < dataArray.length; i++) {
        const v = dataArray[i] / 128.0;
        const y = v * waveformCanvas.height / 2;

        if (i === 0) {
            canvasCtx.moveTo(x, y);
        } else {
            canvasCtx.lineTo(x, y);
        }
        x += sliceWidth;
    }

    canvasCtx.lineTo(waveformCanvas.width, waveformCanvas.height / 2);
    canvasCtx.stroke();
}
```

---

### 3. AI 요약 기능 (Claude 3.5 Haiku)

**요약 지침:**
1. 회사 업무 기록 형식
2. 중요 내용, 결정사항, 액션 아이템 명확 정리
3. 간결하고 핵심적인 요약
4. 날짜, 시간, 담당자, 금액 등 중요 정보 보존
5. 불필요한 대화 및 중복 제거

**Backend API (`summary_api.php`):**

```php
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");

// 권한 체크
if ($level > 5) {
    echo json_encode(['ok' => false, 'error' => '접근 권한이 없습니다.']);
    exit;
}

// POST 데이터 받기
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['text']) || empty(trim($data['text']))) {
    echo json_encode(['ok' => false, 'error' => '요약할 텍스트가 없습니다.']);
    exit;
}

$text = $data['text'];

// Claude API 키 읽기
$apiKeyFile = $_SERVER['DOCUMENT_ROOT'] . '/apikey/claude_api.txt';
if (!file_exists($apiKeyFile)) {
    echo json_encode(['ok' => false, 'error' => 'Claude API 키 파일이 존재하지 않습니다.']);
    exit;
}

$apiKey = trim(file_get_contents($apiKeyFile));

// Claude API 요청 프롬프트
$promptText = <<<EOT
다음 음성 녹음을 텍스트로 변환한 내용을 회사 업무 기록용으로 요약해주세요.

**원본 텍스트:**
{$text}

**요약 지침:**
1. 회사 업무 기록에 적합한 형식으로 작성하세요
2. 중요한 내용, 결정사항, 액션 아이템을 명확히 정리하세요
3. 간결하고 명확하게 핵심만 요약하세요
4. 날짜, 시간, 담당자, 금액 등 중요한 정보는 빠뜨리지 마세요
5. 불필요한 대화나 중복 내용은 제거하세요

**응답 형식:**
제목과 본문을 포함한 정리된 요약문만 반환하세요. 추가 설명은 하지 마세요.
EOT;

// Claude API 호출
$apiUrl = 'https://api.anthropic.com/v1/messages';

$requestBody = [
    'model' => 'claude-3-5-haiku-20241022',  // Haiku 모델 사용
    'max_tokens' => 2048,
    'messages' => [
        [
            'role' => 'user',
            'content' => $promptText
        ]
    ]
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-api-key: ' . $apiKey,
    'anthropic-version: 2023-06-01'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    $errorJson = json_decode($response, true);
    $errorDetails = $errorJson['error']['message'] ?? 'API 호출 실패';

    echo json_encode([
        'ok' => false,
        'error' => "Claude API 호출 실패 (HTTP {$httpCode})",
        'details' => $errorDetails
    ]);
    exit;
}

$apiResponse = json_decode($response, true);
$summary = trim($apiResponse['content'][0]['text']);

echo json_encode([
    'ok' => true,
    'summary' => $summary
]);
```

**Frontend 호출:**
```javascript
aiSummaryBtn.addEventListener('click', async () => {
    const text = transcriptEl.textContent;

    aiSummaryBtn.disabled = true;
    aiSummaryBtn.innerHTML = '<span class="loading-spinner"></span> 요약 중...';

    try {
        const response = await fetch('summary_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ text: text })
        });

        const result = await response.json();

        if (result.ok) {
            summaryText.value = result.summary;
            summarySection.style.display = 'block';
            summarySection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            throw new Error(result.error);
        }
    } catch (error) {
        alert('AI 요약에 실패했습니다: ' + error.message);
    } finally {
        aiSummaryBtn.disabled = false;
        aiSummaryBtn.innerHTML = originalBtnText;
    }
});
```

---

### 4. UI/UX 기능

#### 타이머
- MM:SS 형식
- 녹음 중 빨간색 표시
- 1초 간격 업데이트

```javascript
function startTimer() {
    startTime = Date.now();
    timerEl.classList.add('active');

    timerInterval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        timerEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }, 1000);
}
```

#### 상태 표시
- 대기중 (회색)
- 음성 인식 중 (빨강)
- 처리중 (파랑)
- 변환 완료 (녹색)
- 오류 (빨강)

```javascript
function updateStatus(message, statusClass) {
    statusEl.textContent = message;
    statusEl.className = 'status-indicator status-' + statusClass;
}
```

#### 녹음 버튼 애니메이션
```css
.record-button.recording {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4); }
    50% { box-shadow: 0 4px 30px rgba(245, 87, 108, 0.8); }
}
```

---

## 설치 및 설정

### 1. 필수 요구사항
- PHP 7.0 이상
- Chrome 브라우저 (Web Speech API 지원)
- HTTPS 환경 (마이크 접근 권한)
- Claude API 키

### 2. 파일 배치
```bash
# voice 디렉토리 생성 및 파일 복사
mkdir -p /your-project/voice
cp index.php /your-project/voice/
cp summary_api.php /your-project/voice/
```

### 3. API 키 설정
```bash
# Claude API 키 파일 생성
mkdir -p /your-project/apikey
echo "your-claude-api-key" > /your-project/apikey/claude_api.txt
chmod 600 /your-project/apikey/claude_api.txt
```

### 4. 세션 설정 (`session.php`)
```php
<?php
session_start();

// 사용자 레벨 설정 (1-7, 낮을수록 높은 권한)
$level = $_SESSION['level'] ?? 99;

// voice 시스템은 level 5 이하만 접근 가능
```

### 5. 헤더 파일 (`load_header.php`, `myheader.php`)
프로젝트의 공통 헤더 파일을 사용하여 Bootstrap, Bootstrap Icons 등 CDN 로드

---

## 사용 흐름

### 1. 음성 녹음 및 변환
```
1. 마이크 버튼 클릭
   ↓
2. 브라우저 마이크 권한 허용
   ↓
3. Web Speech API 시작 + 오디오 시각화 시작
   ↓
4. 음성 입력 → 실시간 텍스트 변환 (임시 + 최종)
   ↓
5. 정지 버튼 클릭
   ↓
6. 최종 텍스트 확정
```

### 2. AI 요약 생성
```
1. 변환된 텍스트 확인
   ↓
2. "AI 요약" 버튼 클릭
   ↓
3. summary_api.php 호출 (Claude API)
   ↓
4. 회사 기록용 요약문 생성
   ↓
5. textarea에 표시 (수정 가능)
```

### 3. 결과 활용
- **복사**: 클립보드에 복사
- **다운로드**: .txt 파일로 저장 (`transcript_timestamp.txt`, `summary_timestamp.txt`)
- **초기화**: 모든 내용 리셋

---

## API 사용량 및 비용

### Web Speech API (Google)
- **비용**: 무료
- **제한**: 없음 (브라우저 기반)
- **지원 언어**: 99개 언어

### Claude 3.5 Haiku API
- **비용**:
  - Input: $0.25 / 1M tokens
  - Output: $1.25 / 1M tokens
- **예상 비용**: 약 $0.001 ~ $0.005 per 요약 (1~5원)
- **모델**: `claude-3-5-haiku-20241022`
- **max_tokens**: 2048

---

## 주요 CSS 클래스

### 컨테이너
- `.voice-container`: 메인 컨테이너 (max-width: 900px)
- `.recording-section`: 녹음 컨트롤 섹션
- `.transcript-section`: 텍스트 표시 섹션

### 버튼
- `.record-button`: 원형 녹음 버튼 (120px x 120px)
- `.record-button.recording`: 녹음 중 상태 (pulse 애니메이션)
- `.btn-primary`: 파란색 버튼
- `.btn-secondary`: 회색 버튼
- `.btn-success`: 녹색 버튼 (AI 요약)

### 상태 표시
- `.status-waiting`: 대기중 (회색)
- `.status-recording`: 녹음중 (빨강)
- `.status-processing`: 처리중 (파랑)
- `.status-completed`: 완료 (녹색)
- `.status-error`: 오류 (빨강)

### 텍스트 영역
- `.transcript-text`: 텍스트 표시 영역
- `.transcript-text.empty`: 빈 상태 (회색, 이탤릭)

---

## 브라우저 호환성

### 완전 지원
- ✅ Chrome 33+
- ✅ Edge 79+

### 부분 지원
- ⚠️ Safari 14.1+ (Web Speech API 제한적)
- ⚠️ Firefox (Web Speech API 미지원)

### 미지원
- ❌ Internet Explorer (모든 버전)

**권장**: Chrome 또는 Edge 최신 버전 사용

---

## 보안 고려사항

### 1. HTTPS 필수
- 마이크 접근 권한은 HTTPS 환경에서만 허용
- 로컬 개발: `localhost`는 예외

### 2. 세션 기반 인증
```php
// 권한 체크
if ($level > 5) {
    echo json_encode(['ok' => false, 'error' => '접근 권한이 없습니다.']);
    exit;
}
```

### 3. API 키 보호
- 파일 권한: `chmod 600 apikey/claude_api.txt`
- .gitignore에 추가: `apikey/`
- 환경 변수 사용 권장

### 4. CORS 설정
```php
header('Access-Control-Allow-Origin: https://your-domain.com');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
```

---

## 트러블슈팅

### 1. 마이크 권한 거부
**증상**: "마이크 권한을 허용해주세요" 알림
**해결**:
- Chrome 설정 → 개인정보 및 보안 → 사이트 설정 → 마이크
- 해당 사이트 권한 허용

### 2. Web Speech API 미작동
**증상**: "이 브라우저는 음성 인식을 지원하지 않습니다"
**해결**:
- Chrome 브라우저 사용
- HTTPS 환경 확인
- 브라우저 업데이트

### 3. Claude API 404 에러
**증상**: "Claude API 호출 실패 (HTTP 404)"
**원인**: 모델명 불일치 (Sonnet vs Haiku)
**해결**:
```php
// summary_api.php에서 모델 확인
'model' => 'claude-3-5-haiku-20241022'  // Haiku 사용
```

### 4. 요약이 너무 길거나 짧음
**해결**: `max_tokens` 조정
```php
$requestBody = [
    'model' => 'claude-3-5-haiku-20241022',
    'max_tokens' => 2048,  // 조정 (1024 ~ 4096)
    // ...
];
```

---

## 확장 아이디어

### 1. 다국어 지원
```javascript
// 언어 선택 UI 추가
recognition.lang = selectedLanguage;  // 'en-US', 'ja-JP', 'zh-CN' 등
```

### 2. 요약 스타일 선택
```php
// 프롬프트에 스타일 옵션 추가
$styles = [
    'formal' => '공식적인 회사 문서 스타일',
    'casual' => '일반 업무 메모 스타일',
    'bullet' => '핵심만 요약한 bullet point 스타일'
];
```

### 3. 음성 파일 저장
```javascript
// MediaRecorder로 녹음 파일 저장
const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
// 서버로 업로드 또는 로컬 다운로드
```

### 4. 회의록 템플릿
```php
// 회의록 형식 프롬프트
**응답 형식:**
- 회의 일시:
- 참석자:
- 주요 안건:
- 결정 사항:
- 액션 아이템:
```

### 5. 실시간 자막 표시
```javascript
// 음성 인식 중 자막 오버레이
const subtitleOverlay = document.createElement('div');
subtitleOverlay.className = 'subtitle-overlay';
subtitleOverlay.textContent = interimTranscript;
```

---

## 성능 최적화

### 1. Canvas 렌더링 최적화
```javascript
// requestAnimationFrame 사용으로 부드러운 애니메이션
function drawWaveform() {
    animationId = requestAnimationFrame(drawWaveform);
    // ... 파형 그리기
}
```

### 2. API 요청 디바운싱
```javascript
// 연속 요청 방지
let summaryTimeout;
aiSummaryBtn.addEventListener('click', () => {
    clearTimeout(summaryTimeout);
    summaryTimeout = setTimeout(callSummaryAPI, 300);
});
```

### 3. 메모리 관리
```javascript
// 오디오 스트림 정리
function cleanup() {
    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }
    if (audioContext) {
        audioContext.close();
        audioContext = null;
    }
}
```

---

## 라이선스 및 의존성

### 사용된 라이브러리
- **Bootstrap 5**: MIT License
- **Bootstrap Icons**: MIT License

### API 서비스
- **Web Speech API**: Google (무료)
- **Claude API**: Anthropic (유료)

### 브라우저 API
- MediaRecorder API
- Web Audio API
- Clipboard API
- Fetch API

---

## 버전 히스토리

### v1.0.0 (2025-11-05)
- ✅ Web Speech API 기반 실시간 음성 인식
- ✅ 오디오 파형 시각화
- ✅ Claude 3.5 Haiku API 요약 기능
- ✅ 복사/다운로드 기능
- ✅ 반응형 UI 디자인

---

## 개발자 정보

**개발 환경:**
- PHP 7+
- Chrome 120+
- Bootstrap 5.x

**테스트 환경:**
- Windows 11 + Chrome
- macOS + Chrome/Safari
- Linux + Chrome

**문의:**
- 프로젝트: 5130 ERP System
- 모듈: Voice Recognition & AI Summary

---

## 참고 자료

### 공식 문서
- [Web Speech API - MDN](https://developer.mozilla.org/en-US/docs/Web/API/Web_Speech_API)
- [Claude API - Anthropic](https://docs.anthropic.com/claude/reference/messages)
- [Web Audio API - MDN](https://developer.mozilla.org/en-US/docs/Web/API/Web_Audio_API)

### 관련 기술
- [MediaRecorder API](https://developer.mozilla.org/en-US/docs/Web/API/MediaRecorder)
- [Canvas API](https://developer.mozilla.org/en-US/docs/Web/API/Canvas_API)
- [Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API)

---

**문서 버전**: 1.0
**최종 업데이트**: 2025-11-05
**작성자**: Claude Code Development Team
