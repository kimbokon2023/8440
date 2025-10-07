<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>카카오톡 스타일 대화 + 유튜브 쇼츠</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    body {
      font-family: 'Apple SD Gothic Neo', 'Noto Sans KR', sans-serif;
      background-color: #ccd6dd;
    }
    .mention {
      color: #007aff;
      font-weight: bold;
    }
  </style>
</head>
<body class="h-screen flex flex-col items-center justify-center">

  <!-- 메인 컨테이너 -->
  <div class="flex w-full max-w-[900px] h-[800px] bg-white shadow-xl rounded-xl overflow-hidden border">

    <!-- 왼쪽: 유튜브 쇼츠 + 요약 -->
    <div class="w-1/2 bg-gray-100 p-4 space-y-4 flex flex-col justify-between">
      <div class="flex justify-center items-center p-1 bg-white shadow">
          <h2 class="text-3xl font-bold text-center text-gray-900 mb-2">조회수 770만 쇼츠</h2>
      </div>	
      <!-- 유튜브 쇼츠 영상 (9:16 비율) -->
      <div class="aspect-[9/16] w-full overflow-hidden rounded-md shadow-md">
	<script>
	  // ✅ 입력할 쇼츠 주소
	  const shortsUrl = "https://www.youtube.com/shorts/jKTkBNoV1OQ";

	  // ✅ Shorts 주소에서 영상 ID 추출
	  const videoId = shortsUrl.split("/shorts/")[1]?.split("?")[0];

	  // ✅ embed URL 생성
	  const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=0&modestbranding=1&rel=0&controls=1`;

	  // ✅ iframe에 적용
	  $(document).ready(function () {
		$("iframe#youtubeShorts").attr("src", embedUrl);
	  });
	</script>

	<!-- iframe 요소 (id는 반드시 youtubeShorts로 설정해야 함) -->
	<iframe 
	  id="youtubeShorts"
	  class="w-full h-full" 
	  src="" 
	  frameborder="0" 
	  allowfullscreen>
	</iframe>
      </div>
      <!-- 요약 텍스트 -->
      <div class="bg-white p-3 text-sm rounded shadow">
        <p class="text-gray-800 font-semibold mb-1">📌 쇼츠 요약</p>
        <p class="text-gray-600 leading-relaxed">
			초등학생이 만든 컵라면 발명품에 댓글 반응이 뜨겁습니다! 컵라면 뚜껑이 자꾸 열리는 불편함과 뜨거운 뚜껑에 덜어 먹을 때 손이 데는 문제를 한 번에 해결한 이 아이디어는, 2013년 청소년 발명 페스티벌에서 대통령상을 받은 작품입니다. “진작 나왔어야 할 발명”, “지금이라도 상용화되면 무조건 산다” 등 실사용자들의 폭발적인 공감과 호응이 이어지고 있어요. 작지만 실용적인 아이디어에 많은 사람들이 감탄하고 있으며, 생활 속 불편을 해결한 진짜 ‘생활 밀착형 발명품’이라는 평가를 받고 있습니다.
        </p>
      </div>
    </div>
    <!-- 오른쪽: 카톡 스타일 대화창 -->
    <div class="w-1/2 bg-[#ccd6dd] flex flex-col relative">
      <!-- 상단 버튼 -->
      <div class="flex justify-between items-center p-2 bg-white shadow">
        <button id="startBtn" class="text-white bg-green-600 px-4 py-1 rounded">인기댓글 보기 Start</button>
        <div id="timeDisplay" class="text-gray-700 font-semibold">00:00</div>
        <button id="stopBtn" class="text-white bg-red-500 px-4 py-1 rounded">종료</button>
      </div>
      <!-- 대화창 -->
      <div id="chatWindow" class="p-4 space-y-3 overflow-y-auto flex-1 pb-10"></div>
     </div>
    </div>

<script>
$(document).ready(function () {
  console.log("✅ 페이지 로드 완료");
  let chatData = [];
  let index = 0;
  let isStopped = false;
  let startTime = null;
  let timerInterval = null;

  // 🎵 효과음 파일 목록
  const soundEffects = [
    "1.띠링.mp3",
    "2.띵.mp3",
    "4.뜨릉.mp3",
    "8bbong.mp3",
    "071_쉭.mp3",
    "082_띵.mp3"
  ];

  function playRandomSound() {
    const randomFile = soundEffects[Math.floor(Math.random() * soundEffects.length)];
    const audio = new Audio(randomFile);
    audio.play().catch(err => console.warn("🔇 효과음 재생 실패:", err));
  }

  function speakText(text, callback) {
    window.speechSynthesis.cancel();

    const cleanText = text
      .replace(/@@\w+/g, '')    // '@@아이디' 제거
      .replace(/@\w+/g, '')     // '@아이디' 제거
      .replace(/@/g, '')        // 남은 @ 제거
      .replace(/\s{2,}/g, ' ')  // 이중 공백 제거
      .trim();

    if (!cleanText) {
      callback();
      return;
    }

    const utterance = new SpeechSynthesisUtterance(cleanText);
    utterance.lang = "ko-KR";

    utterance.onend = () => {
      console.log("✅ 발화 완료");
      playRandomSound();
      callback();
    };

    utterance.onerror = (e) => {
      console.error("❌ TTS 오류:", e.error);
      callback();
    };

    console.log("🗣️ TTS 읽기:", cleanText);
    window.speechSynthesis.speak(utterance);
  }

  function showNextMessage() {
    if (isStopped || index >= chatData.length) return;

    const msg = chatData[index];
    const isMine = !msg.sender;

    const wrapper = $("<div>").addClass(`flex ${isMine ? 'justify-end' : 'justify-start'}`);
    const bubble = $("<div>")
      .addClass(`${isMine ? 'bg-yellow-300' : 'bg-white'} text-sm text-black rounded-xl p-3 max-w-[80%] shadow-md whitespace-pre-line`)
      .html(msg.text.replace(/(@\w+)/g, '<span class="mention">$1</span>'));

    wrapper.append(bubble);
    $("#chatWindow").append(wrapper);
    $("#chatWindow")[0].scrollTop = $("#chatWindow")[0].scrollHeight;

    console.log(`💬 메시지 [${index + 1}/${chatData.length}]: ${msg.text}`);
    speakText(msg.text, () => {
      index++;
      setTimeout(showNextMessage, 400);
    });
  }

  function startTimer() {
    startTime = Date.now();
    timerInterval = setInterval(() => {
      const elapsed = Math.floor((Date.now() - startTime) / 1000);
      const minutes = String(Math.floor(elapsed / 60)).padStart(2, '0');
      const seconds = String(elapsed % 60).padStart(2, '0');
      $("#timeDisplay").text(`${minutes}:${seconds}`);
    }, 1000);
  }

  function stopTimer() {
    clearInterval(timerInterval);
  }

  $("#startBtn").on("click", function () {
    if (chatData.length === 0) {
      fetch("chat_data.json")
        .then(res => res.json())
        .then(data => {
          console.log("✅ chat_data.json 로드 성공");
          chatData = data;
          index = 0;
          isStopped = false;
          $("#chatWindow").empty();
          $("#timeDisplay").text("00:00");
          startTimer();
          showNextMessage();
        });
    } else {
      isStopped = false;
      startTimer();
      showNextMessage();
    }
  });

  $("#stopBtn").on("click", function () {
    isStopped = true;
    stopTimer();
    window.speechSynthesis.cancel();
    console.log("⛔ 대화 중단됨");
  });
});
</script>
</body>
</html>
