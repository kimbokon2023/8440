<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/load_header.php");

// 권한 체크
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
    <title>음성 녹음 및 텍스트 변환</title>
    <style>
        .voice-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
        }

        .header-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-section h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .header-section p {
            color: #6c757d;
            font-size: 14px;
        }

        .recording-section {
            background: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .record-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .record-button {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .record-button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .record-button.recording {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
            }
            50% {
                box-shadow: 0 4px 30px rgba(245, 87, 108, 0.8);
            }
        }

        .timer {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            font-family: 'Courier New', monospace;
            min-height: 40px;
        }

        .timer.active {
            color: #f5576c;
        }

        .status-indicator {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .status-waiting {
            background: #e9ecef;
            color: #495057;
        }

        .status-recording {
            background: #f8d7da;
            color: #842029;
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

        .waveform-container {
            width: 100%;
            height: 100px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
        }

        .waveform-canvas {
            width: 100%;
            height: 100%;
        }

        .audio-player {
            width: 100%;
            margin: 20px 0;
            display: none;
        }

        .transcript-section {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            min-height: 200px;
        }

        .transcript-section h5 {
            margin-bottom: 15px;
            color: #333;
        }

        .transcript-text {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            min-height: 150px;
            font-size: 16px;
            line-height: 1.8;
            color: #333;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .transcript-text.empty {
            color: #999;
            font-style: italic;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
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

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box p {
            margin: 5px 0;
            color: #084298;
            font-size: 13px;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/myheader.php"; ?>

<div class="voice-container">
    <div class="header-section">
        <h3><i class="bi bi-mic-fill"></i> 음성 녹음 및 텍스트 변환</h3>
        <p>음성을 녹음하고 AI를 통해 텍스트로 변환합니다</p>
    </div>

    <div class="info-box">
        <p><i class="bi bi-info-circle"></i> Chrome 브라우저에서 마이크 권한을 허용해주세요</p>
        <p><i class="bi bi-clock"></i> 실시간 음성 인식 - 말하는 즉시 텍스트로 변환됩니다</p>
        <p><i class="bi bi-stars"></i> Google Web Speech API를 사용한 무료 한국어 음성 인식</p>
    </div>

    <div class="recording-section">
        <div class="record-controls">
            <button id="record-button" class="record-button">
                <i class="bi bi-mic-fill"></i>
            </button>

            <div id="status" class="status-indicator status-waiting">대기중</div>

            <div id="timer" class="timer">00:00</div>

            <div class="waveform-container">
                <canvas id="waveform" class="waveform-canvas"></canvas>
            </div>

            <audio id="audio-player" class="audio-player" controls></audio>
        </div>
    </div>

    <div class="transcript-section">
        <h5><i class="bi bi-file-text"></i> 변환된 텍스트</h5>
        <div id="transcript" class="transcript-text empty">
            녹음 버튼을 클릭하여 음성을 녹음하세요
        </div>

        <div class="action-buttons">
            <button id="copy-btn" class="btn btn-primary" disabled>
                <i class="bi bi-clipboard"></i> 복사
            </button>
            <button id="download-btn" class="btn btn-secondary" disabled>
                <i class="bi bi-download"></i> 다운로드
            </button>
            <button id="ai-summary-btn" class="btn btn-success" disabled>
                <i class="bi bi-stars"></i> AI 요약
            </button>
            <button id="reset-btn" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise"></i> 초기화
            </button>
        </div>
    </div>

    <div class="transcript-section" id="summary-section" style="display: none;">
        <h5><i class="bi bi-stars"></i> AI 요약 (회사 기록용)</h5>
        <textarea id="summary-text" class="transcript-text" style="min-height: 150px; width: 100%; resize: vertical; box-sizing: border-box; padding: 20px; border: 1px solid #dee2e6; border-radius: 8px; font-size: 16px; line-height: 1.8;" placeholder="AI 요약 버튼을 클릭하면 여기에 요약이 표시됩니다"></textarea>

        <div class="action-buttons">
            <button id="copy-summary-btn" class="btn btn-primary">
                <i class="bi bi-clipboard"></i> 요약 복사
            </button>
            <button id="download-summary-btn" class="btn btn-secondary">
                <i class="bi bi-download"></i> 요약 다운로드
            </button>
        </div>
    </div>
</div>

<script>
// Web Speech API 변수
let recognition = null;
let isRecognizing = false;
let finalTranscript = '';
let interimTranscript = '';

// 타이머 변수
let startTime = null;
let timerInterval = null;

// 시각화 변수
let audioContext = null;
let analyser = null;
let dataArray = null;
let animationId = null;
let mediaStream = null;

// DOM Elements
const recordButton = document.getElementById('record-button');
const statusEl = document.getElementById('status');
const timerEl = document.getElementById('timer');
const waveformCanvas = document.getElementById('waveform');
const audioPlayer = document.getElementById('audio-player');
const transcriptEl = document.getElementById('transcript');
const copyBtn = document.getElementById('copy-btn');
const downloadBtn = document.getElementById('download-btn');
const aiSummaryBtn = document.getElementById('ai-summary-btn');
const resetBtn = document.getElementById('reset-btn');
const summarySection = document.getElementById('summary-section');
const summaryText = document.getElementById('summary-text');
const copySummaryBtn = document.getElementById('copy-summary-btn');
const downloadSummaryBtn = document.getElementById('download-summary-btn');

// Canvas Context
const canvasCtx = waveformCanvas.getContext('2d');

// Initialize Canvas Size
function resizeCanvas() {
    waveformCanvas.width = waveformCanvas.offsetWidth;
    waveformCanvas.height = waveformCanvas.offsetHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

// Web Speech API 초기화
function initSpeechRecognition() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        alert('이 브라우저는 음성 인식을 지원하지 않습니다. Chrome 브라우저를 사용해주세요.');
        return false;
    }

    recognition = new SpeechRecognition();
    recognition.lang = 'ko-KR';
    recognition.continuous = true;
    recognition.interimResults = true;
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

        // 텍스트 업데이트 (최종 + 임시)
        const displayText = finalTranscript + (interimTranscript ? '<span style="color: #999;">' + interimTranscript + '</span>' : '');
        transcriptEl.innerHTML = displayText || '음성을 인식하고 있습니다...';
        transcriptEl.classList.remove('empty');

        // 버튼 활성화
        if (finalTranscript.trim()) {
            copyBtn.disabled = false;
            downloadBtn.disabled = false;
            aiSummaryBtn.disabled = false;
        }
    };

    // 음성 인식 시작
    recognition.onstart = () => {
        isRecognizing = true;
        updateStatus('음성 인식 중', 'recording');
    };

    // 음성 인식 종료
    recognition.onend = () => {
        isRecognizing = false;

        if (recordButton.classList.contains('recording')) {
            // 자동 재시작 (연속 녹음 모드)
            try {
                recognition.start();
            } catch (e) {
                console.log('Recognition restart failed:', e);
            }
        } else {
            updateStatus('변환 완료', 'completed');
            stopTimer();
            stopWaveform();
            stopAudioStream();
        }
    };

    // 에러 처리
    recognition.onerror = (event) => {
        console.error('Speech recognition error:', event.error);

        if (event.error === 'no-speech') {
            // 음성이 감지되지 않음 - 자동 재시작
            return;
        }

        if (event.error === 'aborted') {
            // 사용자가 중단함
            return;
        }

        updateStatus('오류 발생: ' + event.error, 'error');

        if (event.error === 'not-allowed') {
            alert('마이크 권한이 거부되었습니다. 브라우저 설정에서 마이크 권한을 허용해주세요.');
        }
    };

    return true;
}

// Update Status
function updateStatus(message, statusClass) {
    statusEl.textContent = message;
    statusEl.className = 'status-indicator status-' + statusClass;
}

// Timer Functions
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

function stopTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
    timerEl.classList.remove('active');
}

function resetTimer() {
    stopTimer();
    timerEl.textContent = '00:00';
}

// Waveform Visualization
function drawWaveform() {
    if (!analyser || !dataArray) return;

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

function stopWaveform() {
    if (animationId) {
        cancelAnimationFrame(animationId);
        animationId = null;
    }

    // Clear canvas
    canvasCtx.fillStyle = '#f8f9fa';
    canvasCtx.fillRect(0, 0, waveformCanvas.width, waveformCanvas.height);
}

// 오디오 스트림 시작 (시각화용)
async function startAudioStream() {
    try {
        mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });

        // Setup Audio Context for Visualization
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
        analyser = audioContext.createAnalyser();
        const source = audioContext.createMediaStreamSource(mediaStream);
        source.connect(analyser);

        analyser.fftSize = 2048;
        const bufferLength = analyser.frequencyBinCount;
        dataArray = new Uint8Array(bufferLength);

        // Start Waveform Visualization
        drawWaveform();

        return true;
    } catch (error) {
        console.error('마이크 접근 오류:', error);
        alert('마이크 권한을 허용해주세요');
        return false;
    }
}

// 오디오 스트림 중지
function stopAudioStream() {
    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
    }

    if (audioContext) {
        audioContext.close();
        audioContext = null;
    }
}

// 음성 인식 시작
async function startRecognition() {
    // Speech Recognition 초기화
    if (!recognition) {
        if (!initSpeechRecognition()) {
            return;
        }
    }

    // 오디오 스트림 시작 (시각화용)
    const streamStarted = await startAudioStream();
    if (!streamStarted) {
        return;
    }

    // 변수 초기화
    finalTranscript = '';
    interimTranscript = '';

    // UI 업데이트
    recordButton.classList.add('recording');
    recordButton.innerHTML = '<i class="bi bi-stop-fill"></i>';
    updateStatus('음성 인식 중', 'recording');
    startTimer();

    // 음성 인식 시작
    try {
        recognition.start();
    } catch (error) {
        console.error('음성 인식 시작 오류:', error);
        updateStatus('음성 인식 시작 실패', 'error');
    }
}

// 음성 인식 중지
function stopRecognition() {
    if (recognition && isRecognizing) {
        recognition.stop();
    }

    // UI 업데이트
    recordButton.classList.remove('recording');
    recordButton.innerHTML = '<i class="bi bi-mic-fill"></i>';

    // 최종 텍스트 정리
    if (finalTranscript.trim()) {
        transcriptEl.textContent = finalTranscript.trim();
        transcriptEl.classList.remove('empty');
        updateStatus('변환 완료', 'completed');
    } else {
        updateStatus('대기중', 'waiting');
    }

    stopTimer();
    stopWaveform();
    stopAudioStream();
}

// Record Button Click
recordButton.addEventListener('click', () => {
    if (!isRecognizing) {
        startRecognition();
    } else {
        stopRecognition();
    }
});

// Copy Button
copyBtn.addEventListener('click', () => {
    const text = transcriptEl.textContent;
    navigator.clipboard.writeText(text).then(() => {
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="bi bi-check"></i> 복사됨';
        setTimeout(() => {
            copyBtn.innerHTML = originalText;
        }, 2000);
    }).catch(err => {
        console.error('복사 실패:', err);
        alert('복사에 실패했습니다');
    });
});

// Download Button
downloadBtn.addEventListener('click', () => {
    const text = transcriptEl.textContent;
    const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `transcript_${new Date().getTime()}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
});

// AI Summary Button
aiSummaryBtn.addEventListener('click', async () => {
    const text = transcriptEl.textContent;

    if (!text || text.trim() === '') {
        alert('요약할 텍스트가 없습니다.');
        return;
    }

    // 버튼 비활성화 및 로딩 표시
    aiSummaryBtn.disabled = true;
    const originalBtnText = aiSummaryBtn.innerHTML;
    aiSummaryBtn.innerHTML = '<span class="loading-spinner"></span> 요약 중...';

    try {
        // Claude API 호출
        const response = await fetch('summary_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                text: text
            })
        });

        const result = await response.json();

        if (result.ok) {
            summaryText.value = result.summary;
            summarySection.style.display = 'block';

            // 요약 섹션으로 스크롤
            summarySection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            // 에러 상세 정보 콘솔 출력
            console.error('API 에러 응답:', result);

            let errorMsg = result.error || 'API 호출 실패';
            if (result.details) {
                errorMsg += '\n상세: ' + result.details;
            }
            if (result.curl_error) {
                errorMsg += '\nCURL 오류: ' + result.curl_error;
            }

            throw new Error(errorMsg);
        }
    } catch (error) {
        console.error('요약 오류:', error);
        console.error('에러 상세:', error);

        // 더 자세한 에러 메시지 표시
        let errorMsg = 'AI 요약에 실패했습니다: ' + error.message;
        alert(errorMsg);
    } finally {
        // 버튼 복원
        aiSummaryBtn.disabled = false;
        aiSummaryBtn.innerHTML = originalBtnText;
    }
});

// Copy Summary Button
copySummaryBtn.addEventListener('click', () => {
    const text = summaryText.value;
    navigator.clipboard.writeText(text).then(() => {
        const originalText = copySummaryBtn.innerHTML;
        copySummaryBtn.innerHTML = '<i class="bi bi-check"></i> 복사됨';
        setTimeout(() => {
            copySummaryBtn.innerHTML = originalText;
        }, 2000);
    }).catch(err => {
        console.error('복사 실패:', err);
        alert('복사에 실패했습니다');
    });
});

// Download Summary Button
downloadSummaryBtn.addEventListener('click', () => {
    const text = summaryText.value;
    const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `summary_${new Date().getTime()}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
});

// Reset Button
resetBtn.addEventListener('click', () => {
    if (confirm('모든 내용을 초기화하시겠습니까?')) {
        // Stop recognition if active
        if (isRecognizing) {
            stopRecognition();
        }

        // Reset all states
        finalTranscript = '';
        interimTranscript = '';
        transcriptEl.textContent = '녹음 버튼을 클릭하여 음성을 녹음하세요';
        transcriptEl.classList.add('empty');

        copyBtn.disabled = true;
        downloadBtn.disabled = true;
        aiSummaryBtn.disabled = true;

        // 요약 섹션 숨기기
        summarySection.style.display = 'none';
        summaryText.value = '';

        resetTimer();
        stopWaveform();
        stopAudioStream();
        updateStatus('대기중', 'waiting');

        recordButton.classList.remove('recording');
        recordButton.innerHTML = '<i class="bi bi-mic-fill"></i>';
    }
});

// Page Load Complete
window.addEventListener('load', function() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'none';
    }
});
</script>

</body>
</html>
