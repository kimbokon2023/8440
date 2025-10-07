<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>웹코딩 퀴즈</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #000;
      color: #fff;
      font-family: 'Arial', sans-serif;
      height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    .mode-selector {
      position: absolute;
      top: 5px;
      left: 5px;
      font-size: 1rem;
      display: flex;
      gap: 10px;
      z-index: 1000;
      align-items: center;
    }
    .mode-selector .form-check {
      margin: 0;
    }
    .mode-selector .form-check-input {
      width: 0.8em;
      height: 0.8em;
    }
    .mode-selector .form-check-input:checked {
      background-color: #fff;
      border-color: #fff;
    }
    .mode-selector .form-check-label {
      color: #fff;
    }
    .mode-selector .runtime {
      color: #fff;
    }
    .mode-selector .quiz-type {
      color: #fff;
      margin-right: 5px;
      font-size: 1rem;
    }
    .mode-selector select {
      background: #333;
      color: #fff;
      border: 1px solid #fff;
      border-radius: 5px;
      padding: 2px;
      font-size: 1rem;
    }
    .mode-selector.video-mode {
      opacity: 0.7;
    }
    .shorts-header {
      height: 20%;
      background: #000;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }
    .channel-name {
      font-size: 2.2rem;
      color: #ccc;
      text-transform: uppercase;
      margin-bottom: 5px;
    }
    .shorts-title {
      font-size: 3.5rem;
      font-weight: bold;
      text-transform: uppercase;
      text-shadow: 0 0 5px #fff;
    }
    .shorts-footer {
      height: 6%;
      background: #000;
    }
    .quiz-container {
      width: 100%;
      max-width: 460px;
      aspect-ratio: 9/16;
      height: 70%;
      padding: 20px;
      background: rgba(0, 0, 0, 0.7);
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
      text-align: center;
      position: relative;
      margin: auto;
    }
    .quiz-question {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 10px;
      animation: pulseText 1s infinite;
    }
    .timer {
      font-size: 2.5rem;
      font-weight: bold;
      color: #ff0;
      text-shadow: 0 0 10px #ff0, 0 0 20px #ff0;
      margin-bottom: 20px;
      animation: glow 1s infinite;
      text-align: center;
      height: 2.5rem;
      visibility: hidden;
    }
    .timer i {
      margin-right: 5px;
    }
    .quiz-options .btn {
      display: block;
      width: 100%;
      margin: 10px 0;
      font-size: 1rem;
      padding: 12px;
      border-radius: 10px;
      transform: scale(0.9);
      opacity: 0;
      animation: popIn 0.5s ease forwards;
      animation-delay: calc(0.1s * var(--order));
      transition: transform 0.2s;
    }
    .quiz-options .btn:hover {
      transform: scale(1.05);
    }
    .quiz-options .correct {
      border: 5px solid #28a745 !important;
      box-shadow: 0 0 10px #28a745;
    }
    .quiz-feedback {
      font-size: 1.2rem;
      margin-top: 20px;
      animation: fadeIn 0.5s;
    }
    .time-up {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 3.5rem;
      color: #ff0000;
      text-shadow: 0 0 10px #ff0000, 0 0 20px #ff0000;
      animation: spread 1s ease forwards;
      display: none;
    }
    .score-card {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(255, 255, 255, 0.9);
      color: #000;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
      text-align: center;
      width: 80%;
      max-width: 400px;
      animation: popIn 0.5s;
      display: none;
    }
    .score-card h3 {
      font-size: 1.5rem;
      margin-bottom: 10px;
    }
    .score-card p {
      font-size: 1.2rem;
      margin: 0;
    }
    .retry-btn {
      display: none;
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 1rem;
      border-radius: 10px;
      background: #28a745;
      color: #fff;
      border: none;
      cursor: pointer;
      animation: fadeIn 0.5s;
    }
    .retry-btn:hover {
      background: #218838;
    }
    .start-btn {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 15px 30px;
      font-size: 1.2rem;
      border-radius: 10px;
      background: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
      animation: fadeIn 0.5s;
      z-index: 1000;
    }
    .start-btn:hover {
      background: #0056b3;
    }
    @keyframes pulseText {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
    @keyframes glow {
      0% { text-shadow: 0 0 10px #ff0, 0 0 20px #ff0; }
      50% { text-shadow: 0 0 20px #ff0, 0 0 30px #ff0; }
      100% { text-shadow: 0 0 10px #ff0, 0 0 20px #ff0; }
    }
    @keyframes popIn {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes spread {
      0% { transform: translate(-50%, -50%) scale(0); opacity: 1; }
      100% { transform: translate(-50%, -50%) scale(2); opacity: 0; }
    }
    @media (max-width: 576px) {
      .channel-name { font-size: 1rem; }
      .shorts-title { font-size: 1.5rem; }
      .mode-selector { font-size: 0.8rem; gap: 8px; }
      .mode-selector .quiz-type { font-size: 0.8rem; }
      .mode-selector select { font-size: 0.8rem; }
      .quiz-question { font-size: 1.2rem; }
      .timer { font-size: 2rem; height: 2rem; }
      .quiz-options .btn { font-size: 0.9rem; }
      .time-up { font-size: 2.5rem; }
      .score-card h3 { font-size: 1.2rem; }
      .score-card p { font-size: 1rem; }
      .start-btn { font-size: 1rem; padding: 10px 20px; }
    }
  </style>
</head>
<body>
  <div class="container-fluid" >
	  <div class="row" >
	  <div class="col-sm-9" >
		  <div class="mode-selector" id="mode-selector">
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="radio" name="mode" id="web-mode" value="web" checked>
			  <label class="form-check-label" for="web-mode">웹용</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="radio" name="mode" id="video-mode" value="video">
			  <label class="form-check-label" for="video-mode">동영상 촬영용</label>
			</div>
			<span class="quiz-type ms-4 badge bg-secondary">HTML 퀴즈</span>
			<select id="quiz-select">
			  <option value="random">랜덤발생</option>
			  <!-- 동적으로 1번~20번 문제그룹 추가 -->
			</select>			
		  </div>
     </div>
	  <div class="col-sm-3" >
		<div class="d-flex justify-content-end" >
			<span class="runtime" id="runtime">실행시간: 0분0초</span>
		</div>
	  </div>
	</div>
  </div>
  <div class="shorts-header">
    <div class="channel-name">우린 퀴즈왕</div>
    <div class="shorts-title" id="shorts-title"></div>
  </div>
  <div class="quiz-container" id="quiz-container">
    <button class="start-btn" id="start-btn">퀴즈 시작</button>
    <div class="quiz-question" id="quiz-question" style="display: none;"></div>
    <div class="timer" id="timer"></div>
    <div class="time-up" id="time-up">Time's Up!</div>
    <div class="quiz-options" id="quiz-options"></div>
    <div class="quiz-feedback" id="quiz-feedback"></div>
    <div class="score-card" id="score-card">
      <h3>퀴즈 결과</h3>
      <p id="score-text"></p>
    </div>
    <button class="retry-btn" id="retry-btn">다시 도전</button>
  </div>
  <div class="shorts-footer"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const backgroundGradients = [
      'linear-gradient(180deg, #007bff, #2c0b5e)',
      'linear-gradient(180deg, #d32f2f, #7f0000)',
      'linear-gradient(180deg, #2e7d32, #1b3c1e)',
      'linear-gradient(180deg, #6a1b9a, #3e0f5e)',
      'linear-gradient(180deg, #00695c, #002f36)'
    ];

    const SOUND_VOLUMES = {
      'dudung.mp3': 0.4,
      'tick.mp3': 0.3,
      'correct.mp3': 0.7,
      'wrong.mp3': 0.3,
      'timeout.mp3': 0.4,
      'bgm.mp3': 0.1
    };

    const FALLBACK_QUIZ = {
      title: "기본 퀴즈",
      questions: [
        {
          question: "HTML은 무엇의 약자인가요?",
          choices: ["Hyper Text Markup Language", "High Text Machine Language", "Hyperlink and Text Markup Language", "Home Tool Markup Language"],
          correctAnswer: "Hyper Text Markup Language"
        }
      ]
    };

    let quizData = [];
    let allQuizSets = [];
    let currentQuestion = 0;
    let correctAnswers = 0;
    let timeLeft = 7;
    let timerInterval = null;
    let mode = 'web';
    let startTime;
    let runtimeInterval = null;
    let timeUpEl = document.getElementById('time-up');
    let tickSound = new Audio('tick.mp3');
    tickSound.loop = true;
    tickSound.volume = SOUND_VOLUMES['tick.mp3'];
    let bgmSound = new Audio('bgm.mp3');
    bgmSound.loop = true;
    bgmSound.volume = SOUND_VOLUMES['bgm.mp3'];

    // Fisher-Yates 셔플 알고리즘
    function shuffleArray(array) {
      for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
      }
      return array;
    }

    // 랜덤 퀴즈 5문제 선택
    function selectRandomQuestions() {
      if (!allQuizSets.length) return FALLBACK_QUIZ.questions;
      const randomGroup = allQuizSets[Math.floor(Math.random() * allQuizSets.length)];
      const questions = randomGroup.questions.slice();
      return shuffleArray(questions).slice(0, 5);
    }

    function populateQuizSelect() {
      const select = document.getElementById('quiz-select');
      select.innerHTML = '<option value="random">랜덤발생</option>';
      for (let i = 0; i < 20; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `${i + 1}번 문제그룹`;
        select.appendChild(option);
      }
    }

    async function loadQuizData(quizIndex = 0) {
      try {
        console.log(`Loading quiz data for index ${quizIndex}`);
        const response = await fetch('./html/quizData.json');
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        if (!Array.isArray(data) || data.length < 20) {
          throw new Error('Invalid quiz data: Expected at least 20 groups');
        }
        allQuizSets = data;
        if (quizIndex === 'random') {
          quizData = selectRandomQuestions();
          document.getElementById('shorts-title').textContent = '랜덤 퀴즈';
        } else {
          quizIndex = parseInt(quizIndex);
          if (quizIndex < 0 || quizIndex >= data.length) {
            console.warn(`Invalid quizIndex ${quizIndex}, defaulting to 0`);
            quizIndex = 0;
          }
          quizData = data[quizIndex].questions.slice(0, 5); // 최대 5문제
          document.getElementById('shorts-title').textContent = data[quizIndex].title || `${quizIndex + 1}번 문제그룹`;
        }
        console.log(`Loaded quiz: ${document.getElementById('shorts-title').textContent}, ${quizData.length} questions`);
        if (!quizData.length) {
          console.warn('quizData is empty, using fallback');
          quizData = FALLBACK_QUIZ.questions;
          document.getElementById('shorts-title').textContent = FALLBACK_QUIZ.title;
        }
      } catch (error) {
        console.error('Error loading quiz data:', error);
        quizData = FALLBACK_QUIZ.questions;
        document.getElementById('shorts-title').textContent = FALLBACK_QUIZ.title;
        document.getElementById('quiz-question').textContent = '퀴즈 데이터를 불러오지 못했습니다. 기본 퀴즈를 사용합니다.';
      }
    }

    function startRuntime() {
      if (runtimeInterval) clearInterval(runtimeInterval);
      startTime = Date.now();
      runtimeInterval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        document.getElementById('runtime').textContent = `실행시간: ${minutes}분${seconds}초`;
      }, 1000);
    }

    function stopRuntime() {
      if (runtimeInterval) {
        clearInterval(runtimeInterval);
        runtimeInterval = null;
      }
      document.getElementById('runtime').textContent = '실행시간: 0분0초';
      try {
        bgmSound.pause();
      } catch (err) {
        console.warn('BGM pause failed:', err);
      }
    }

    function showStartButton() {
      console.log('Showing start button');
      const quizContainer = document.getElementById('quiz-container');
      quizContainer.style.background = 'rgba(0, 0, 0, 0.7)'; // 초기 배경 복원
      document.getElementById('start-btn').style.display = 'block';
      document.getElementById('quiz-question').style.display = 'none';
      document.getElementById('quiz-question').textContent = '';
      document.getElementById('quiz-options').innerHTML = '';
      document.getElementById('quiz-feedback').textContent = '';
      document.getElementById('score-card').style.display = 'none';
      document.getElementById('retry-btn').style.display = 'none';
      document.getElementById('timer').style.visibility = 'hidden';
      document.getElementById('timer').innerHTML = '';
      timeUpEl.style.display = 'none';
    }

    function resetQuiz() {
      console.log('Resetting quiz');
      currentQuestion = 0;
      correctAnswers = 0;
      if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
      }
      try {
        tickSound.pause();
      } catch (err) {
        console.warn('Tick pause failed:', err);
      }
      stopRuntime();
      if (!quizData.length) {
        console.warn('quizData is empty during reset, reloading');
        const selectedIndex = document.getElementById('quiz-select').value;
        loadQuizData(selectedIndex).then(() => {
          showStartButton();
        });
      } else {
        showStartButton();
      }
    }

    function startQuiz() {
      console.log('Starting quiz');
      document.getElementById('start-btn').style.display = 'none';
      document.getElementById('quiz-question').style.display = 'block';
      startRuntime();
      try {
        bgmSound.play().catch(err => console.warn('BGM play failed:', err));
      } catch (err) {
        console.warn('BGM play error:', err);
      }
      showQuestion();
    }

    function showQuestion() {
      if (!quizData.length || currentQuestion >= quizData.length) {
        console.log('No more questions or invalid quiz data');
        showScoreCard();
        return;
      }
      console.log(`Showing question ${currentQuestion + 1}/${quizData.length}`);
      const q = quizData[currentQuestion];
      document.getElementById('quiz-question').textContent = q.question;
      const optionsEl = document.getElementById('quiz-options');
      optionsEl.innerHTML = '';
      q.choices.forEach((choice, index) => {
        const btn = document.createElement('button');
        btn.className = 'btn btn-outline-light';
        btn.style.setProperty('--order', index);
        btn.textContent = choice;
        if (mode === 'web') {
          btn.onclick = () => {
            if (timerInterval) clearInterval(timerInterval);
            try {
              tickSound.pause();
            } catch (err) {
              console.warn('Tick pause failed:', err);
            }
            showFeedback(choice, q.correctAnswer);
          };
        } else {
          btn.disabled = true;
        }
        optionsEl.appendChild(btn);
      });
      const randomBg = backgroundGradients[Math.floor(Math.random() * backgroundGradients.length)];
      document.getElementById('quiz-container').style.background = randomBg;
      document.getElementById('score-card').style.display = 'none';
      document.getElementById('retry-btn').style.display = 'none';
      try {
        const dudungSound = new Audio('dudung.mp3');
        dudungSound.volume = SOUND_VOLUMES['dudung.mp3'];
        dudungSound.play().catch(err => console.warn('Dudung play failed:', err));
      } catch (err) {
        console.warn('Dudung play error:', err);
      }
      startTimer();
    }

    function startTimer() {
      if (timerInterval) clearInterval(timerInterval);
      timeLeft = 7;
      const timerEl = document.getElementById('timer');
      timeUpEl.style.display = 'none';
      timerEl.style.visibility = 'hidden';
      timerEl.innerHTML = '';
      try {
        tickSound.play().catch(err => console.warn('Tick play failed:', err));
      } catch (err) {
        console.warn('Tick play error:', err);
      }
      timerInterval = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 5) {
          timerEl.style.visibility = 'visible';
          timerEl.innerHTML = `<i class="bi bi-clock"></i> ${timeLeft}`;
        }
        if (timeLeft <= 0) {
          clearInterval(timerInterval);
          timerInterval = null;
          try {
            tickSound.pause();
          } catch (err) {
            console.warn('Tick pause failed:', err);
          }
          timeUpEl.style.display = 'block';
          try {
            const timeoutSound = new Audio('timeout.mp3');
            timeoutSound.volume = SOUND_VOLUMES['timeout.mp3'];
            timeoutSound.play().catch(err => console.warn('Timeout play failed:', err));
          } catch (err) {
            console.warn('Timeout play error:', err);
          }
          showFeedback(null, quizData[currentQuestion].correctAnswer);
        }
      }, 1000);
    }

    function showFeedback(selected, correct) {
      console.log(`Feedback: selected=${selected}, correct=${correct}`);
      const feedbackEl = document.getElementById('quiz-feedback');
      const optionsEl = document.getElementById('quiz-options');
      optionsEl.querySelectorAll('button').forEach(btn => {
        btn.disabled = true;
        if (btn.textContent === correct && mode === 'video') {
          btn.classList.add('correct');
        }
      });
      if (mode === 'web') {
        if (selected === correct) {
          feedbackEl.textContent = '✅ 정답!';
          try {
            const correctSound = new Audio('correct.mp3');
            correctSound.volume = SOUND_VOLUMES['correct.mp3'];
            correctSound.play().catch(err => console.warn('Correct play failed:', err));
          } catch (err) {
            console.warn('Correct play error:', err);
          }
          correctAnswers++;
        } else if (selected === null) {
          feedbackEl.textContent = `⏰ 시간 초과! 정답: ${correct}`;
        } else {
          feedbackEl.textContent = `❌ 오답! 정답: ${correct}`;
          try {
            const wrongSound = new Audio('wrong.mp3');
            wrongSound.volume = SOUND_VOLUMES['wrong.mp3'];
            wrongSound.play().catch(err => console.warn('Wrong play failed:', err));
          } catch (err) {
            console.warn('Wrong play error:', err);
          }
        }
      } else {
        feedbackEl.textContent = '';
      }
      setTimeout(() => {
        currentQuestion++;
        if (currentQuestion < quizData.length) {
          showQuestion();
          feedbackEl.textContent = '';
          timeUpEl.style.display = 'none';
        } else if (mode === 'web') {
          feedbackEl.textContent = '';
          stopRuntime();
          showScoreCard();
        } else {
          feedbackEl.textContent = '';
          timeUpEl.style.display = 'none';
          stopRuntime();
        }
      }, 1500);
    }

    function showScoreCard() {
      console.log(`Showing score card: ${correctAnswers}/${quizData.length}`);
      const scoreCard = document.getElementById('score-card');
      const scoreText = document.getElementById('score-text');
      const totalQuestions = quizData.length;
      const message = correctAnswers === totalQuestions ? '참 잘했어요! 🎉' : '다시 도전해 보세요! 💪';
      scoreText.textContent = `정답: ${correctAnswers}/${totalQuestions}\n${message}`;
      setTimeout(() => {
        scoreCard.style.display = 'block';
        setTimeout(() => {
          const retryBtn = document.getElementById('retry-btn');
          retryBtn.style.display = 'block';
          console.log('Retry button displayed');
        }, 1000);
      }, 2000);
    }

    // 이벤트 리스너 설정
    function setupEventListeners() {
      const startBtn = document.getElementById('start-btn');
      const retryBtn = document.getElementById('retry-btn');
      const quizSelect = document.getElementById('quiz-select');
      const modeRadios = document.querySelectorAll('input[name="mode"]');

      // 시작 버튼
      startBtn.onclick = () => {
        console.log('Start button clicked');
        startQuiz();
      };

      // 다시 도전 버튼
      retryBtn.onclick = () => {
        console.log('Retry button clicked');
        const selectedIndex = quizSelect.value;
        loadQuizData(selectedIndex).then(() => {
          console.log('Quiz data loaded for retry, resetting quiz');
          resetQuiz();
        }).catch(err => {
          console.error('Failed to load quiz data on retry:', err);
          quizData = FALLBACK_QUIZ.questions;
          document.getElementById('shorts-title').textContent = FALLBACK_QUIZ.title;
          resetQuiz();
        });
      };

      // 퀴즈 선택
      quizSelect.addEventListener('change', (e) => {
        console.log(`Quiz selected: ${e.target.value}`);
        loadQuizData(e.target.value).then(() => {
          console.log('Quiz data loaded for select change, resetting quiz');
          resetQuiz();
        }).catch(err => {
          console.error('Failed to load quiz data on select change:', err);
          quizData = FALLBACK_QUIZ.questions;
          document.getElementById('shorts-title').textContent = FALLBACK_QUIZ.title;
          resetQuiz();
        });
      });

      // 모드 변경
      modeRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
          console.log(`Mode changed to: ${e.target.value}`);
          mode = e.target.value;
          const modeSelector = document.getElementById('mode-selector');
          modeSelector.classList.toggle('video-mode', mode === 'video');
          loadQuizData(quizSelect.value).then(() => {
            console.log('Quiz data loaded for mode change, resetting quiz');
            resetQuiz();
          }).catch(err => {
            console.error('Failed to load quiz data on mode change:', err);
            quizData = FALLBACK_QUIZ.questions;
            document.getElementById('shorts-title').textContent = FALLBACK_QUIZ.title;
            resetQuiz();
          });
        });
      });
    }

    // 초기화
    populateQuizSelect();
    setupEventListeners();
    loadQuizData(0).then(() => {
      console.log('Initial quiz data loaded, showing start button');
      showStartButton();
    }).catch(err => {
      console.error('Failed to load initial quiz data:', err);
      quizData = FALLBACK_QUIZ.questions;
      document.getElementById('shorts-title').textContent = FALLBACK_QUIZ.title;
      showStartButton();
    });
  </script>
</body>
</html>