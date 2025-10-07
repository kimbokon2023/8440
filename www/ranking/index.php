<?php
require_once __DIR__ . '/../common/functions.php';
?>
 <?php
 session_start(); 
 
  if(!isset($_SESSION["level"]) || $_SESSION["level"]>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:" . $_SERVER["DOCUMENT_ROOT"] . "/login/login_form.php"); 
         exit;
   } 
 
include getDocumentRoot() . '/load_header.php' ;
 
$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];

// 직원 명단 (이름만)
$employees = [
    '이경묵', '최장중', '김보곤', '조성원', '조경임', '권영철', 
    '안현섭', '김영무', '이미래', '라나', '김수로', '샤집', 
    '까심', '소민지', '이소정', '볼한', '딥', '안병길', '이도훈'
];

?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>선물 선택 순위</title>

<style>
	/* 선물추첨 전용 스타일 */
	.gift-draw-container {
		background: linear-gradient(135deg,rgb(255, 255, 255) 0%,rgb(255, 255, 255) 100%);
		min-height: 100vh;
		padding: 20px;
	}

	.main-title {
		text-align: center;		
		font-size: 3rem;
		font-weight: bold;
		margin-bottom: 30px;
		text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
	}

	.draw-button {
		background: linear-gradient(135deg, #6ec6e9 0%, #269dcf 100%);
		border: none;
		color: black;
		font-size: 2rem;
		font-weight: bold;
		padding: 20px 40px;
		border-radius: 50px;
	  cursor: pointer;
		box-shadow: 0 8px 25px rgba(0,0,0,0.3);
		transition: all 0.3s ease;
		display: block;
		margin: 0 auto 40px;
	}

	.draw-button:hover {
		transform: translateY(-3px);
		box-shadow: 0 12px 35px rgba(0,0,0,0.4);
	}

	.draw-button:active {
		transform: translateY(0);
	}

	/* 카운트다운 모달 */
	.countdown-modal {
	  display: none;
	  position: fixed;
		z-index: 1000;
	  left: 0;
	  top: 0;
	  width: 100%;
	  height: 100%;
		background-color: rgba(0, 0, 0, 0.8);
		backdrop-filter: blur(5px);
	}

	.countdown-content {
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  height: 100%;
	}

	#countdown {
		font-size: 15rem;
		color: #ff6b6b;
		font-weight: bold;
		text-shadow: 0 0 30px rgba(255, 107, 107, 0.8);
		animation: pulse 1s ease-in-out;
	}

	@keyframes pulse {
		0% { transform: scale(1); }
		50% { transform: scale(1.2); }
		100% { transform: scale(1); }
	}

	/* 결과 테이블 */
	.results-container {
		background: white;
		border-radius: 20px;
		padding: 30px;
		box-shadow: 0 10px 30px rgba(0,0,0,0.2);
		margin-top: 20px;
		display: none;
	}

	.results-title {
		text-align: center;
		color: #333;
		font-size: 2rem;
		font-weight: bold;
		margin-bottom: 30px;
	}

	.ranking-table {
		width: 100%;
		border-collapse: collapse;
		margin-top: 20px;
	}

	.ranking-table th {
		background: linear-gradient(45deg, #0077AA, #00AACC);
	  color: white;
		padding: 15px;
		text-align: center;
		font-size: 1.2rem;
		font-weight: bold;
	}

	.ranking-table td {
		padding: 15px;
		text-align: center;
		border-bottom: 1px solid #eee;
		font-size: 1.1rem;
	}

	.ranking-table tr:nth-child(even) {
		background-color: #f8f9fa;
	}

	.ranking-table tr:hover {
		background-color: #e3f2fd;
		transform: scale(1.02);
		transition: all 0.3s ease;
	}

	.rank-number {
		font-weight: bold;
		font-size: 1.3rem;
		color: #0077AA;
	}

	.employee-name {
		font-weight: 500;
		color: #333;
	}

	/* 체크박스 스타일 */
	.gift-checkbox {
		width: 2em;
		height: 2em;
		cursor: pointer;
		accent-color: #0077AA;
	}

	.gift-checkbox:checked {
		background-color: #0077AA;
	}

	/* 애니메이션 효과 */
	.fade-in {
		animation: fadeIn 0.5s ease-in;
	}

	@keyframes fadeIn {
		from { opacity: 0; transform: translateY(20px); }
		to { opacity: 1; transform: translateY(0); }
	}

	.slide-in {
		animation: slideIn 0.6s ease-out;
	}

	@keyframes slideIn {
		from { transform: translateX(-100%); opacity: 0; }
		to { transform: translateX(0); opacity: 1; }
	}

	/* 반응형 디자인 */
	@media (max-width: 768px) {
		.main-title {
			font-size: 2rem;
		}
		
		.draw-button {
			font-size: 1.5rem;
			padding: 15px 30px;
		}
		
		#countdown {
			font-size: 8rem;
		}
		
		.ranking-table th,
		.ranking-table td {
			padding: 10px 5px;
			font-size: 0.9rem;
		}
	}		
  </style>

</head>
<body>

<?php require_once(includePath('myheader.php')); ?> 

<div class="gift-draw-container">
	<div class="container">
		<h1 class="main-title">🎁 선물 선택 순위 🎁</h1>
		
		<button id="drawButton" class="draw-button">
			🎲 순위 추첨하기 🎲
		</button>
		
		<div id="resultsContainer" class="results-container">
			<h2 class="results-title">🏆 선택 순위 결과 🏆</h2>
			<table class="ranking-table">
				<thead>
					<tr>
						<th>순위</th>
						<th>이름</th>
						<th>선물</th>
						<th>지급완료</th>
					</tr>
				</thead>
				<tbody id="resultsTableBody">
					<!-- 결과가 여기에 동적으로 추가됩니다 -->
				</tbody>
			</table>
			</div>
		</div>
</div>

<!-- 카운트다운 모달 -->
<div id="countdownModal" class="countdown-modal">
	<div class="countdown-content">
		<div id="countdown"></div>
	</div>
</div>

<script>
// 직원 명단 (PHP에서 전달받은 데이터)
const employees = <?php echo json_encode($employees); ?>;

// 선물 목록 (모두 대기중인 선물로 통일)
const gifts = [
    '🎁 대기중인 선물', '🎁 대기중인 선물', '🎁 대기중인 선물', '🎁 대기중인 선물',
    '🎁 대기중인 선물', '🎁 대기중인 선물', '🎁 대기중인 선물', '🎁 대기중인 선물',
    '🎁 대기중인 선물', '🎁 대기중인 선물', '🎁 대기중인 선물', '🎁 대기중인 선물',
    '🎁 대기중인 선물', '🎁 대기중인 선물', '🎁 대기중인 선물', '🎁 대기중인 선물',
    '🎁 대기중인 선물', '🎁 대기중인 선물', '🎁 대기중인 선물'
];

// DOM 요소들
const drawButton = document.getElementById('drawButton');
const countdownModal = document.getElementById('countdownModal');
const countdown = document.getElementById('countdown');
const resultsContainer = document.getElementById('resultsContainer');
const resultsTableBody = document.getElementById('resultsTableBody');

// 추첨 시작 함수
function startDraw() {
    // 버튼 비활성화
    drawButton.disabled = true;
    drawButton.textContent = '추첨 중...';
    
    // 카운트다운 시작
    showCountdown();
}

// 카운트다운 표시
function showCountdown() {
    countdownModal.style.display = 'block';
    let counter = 3;
    countdown.textContent = counter;
    
    const countdownInterval = setInterval(() => {
        counter--;
        if (counter > 0) {
            countdown.textContent = counter;
            } else {
            clearInterval(countdownInterval);
            countdownModal.style.display = 'none';
            performDraw();
        }
			}, 1000);
}

// 실제 추첨 수행
function performDraw() {
    // 직원 배열을 복사하고 섞기
    const shuffledEmployees = [...employees];
    shuffleArray(shuffledEmployees);
    
    // 결과 테이블 생성
    generateResultsTable(shuffledEmployees);
    
    // 결과 표시
    resultsContainer.style.display = 'block';
    resultsContainer.classList.add('fade-in');
    
    // 버튼 복원
    drawButton.disabled = false;
    drawButton.textContent = '🎲 다시 순위 추첨하기 🎲';
}

// 배열 섞기 함수 (Fisher-Yates 알고리즘)
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

// 결과 테이블 생성
function generateResultsTable(rankedEmployees) {
    resultsTableBody.innerHTML = '';
    
    rankedEmployees.forEach((employee, index) => {
        const row = document.createElement('tr');
        row.classList.add('slide-in');
        row.style.animationDelay = `${index * 0.1}s`;
        
        const rank = index + 1;
        const gift = gifts[index] || '🎁 참가상';
        
        row.innerHTML = `
            <td><span class="rank-number">${rank}위</span></td>
            <td><span class="employee-name">${employee}</span></td>
            <td>${gift}</td>
            <td><input type="checkbox" class="gift-checkbox" id="gift-${index}"></td>
        `;
        
        resultsTableBody.appendChild(row);
    });
}

// 이벤트 리스너 등록
drawButton.addEventListener('click', startDraw);

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', function() {
    // 결과 컨테이너는 처음에 숨김
    resultsContainer.style.display = 'none';
});
    </script>
</body>
</html>