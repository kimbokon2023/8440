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
		/* body와 html 오버플로우 방지 */
		html, body {
			overflow-x: hidden !important;
			max-width: 100% !important;
			width: 100% !important;
			box-sizing: border-box !important;
			margin: 0 !important;
			padding: 0 !important;
		}
		
		* {
			max-width: 100% !important;
			box-sizing: border-box !important;
		}
		
		/* 컨테이너 최적화 */
		.container,
		.gift-draw-container {
			padding: 0.5rem !important;
			max-width: 100% !important;
			width: 100% !important;
			box-sizing: border-box !important;
			margin: 0 !important;
			overflow-x: hidden !important;
		}
		
		.main-title {
			font-size: 1.5rem !important;
			margin-bottom: 1rem !important;
			padding: 0.5rem !important;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		.draw-button {
			font-size: 1.25rem !important;
			padding: 1rem !important;
			width: 100% !important;
			max-width: 100% !important;
			margin: 0.5rem 0 !important;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		#countdown {
			font-size: 8rem !important;
		}
		
		/* 결과 컨테이너 최적화 */
		.results-container {
			width: 100% !important;
			max-width: 100% !important;
			padding: 0.75rem !important;
			margin: 0.5rem 0 !important;
			box-sizing: border-box !important;
			display: block !important;
		}
		
		.results-title {
			font-size: 1.25rem !important;
			margin-bottom: 1rem !important;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		/* 테이블을 카드 형식으로 변환 */
		.ranking-table {
			display: block !important;
			width: 100% !important;
			max-width: 100% !important;
		}
		
		.ranking-table thead {
			display: none !important;
		}
		
		.ranking-table tbody {
			display: block !important;
			width: 100% !important;
			max-width: 100% !important;
		}
		
		.ranking-table tr {
			display: block !important;
			width: 100% !important;
			max-width: 100% !important;
			margin-bottom: 0.75rem !important;
			border: 1px solid #ddd !important;
			border-radius: 0.5rem !important;
			padding: 0.75rem !important;
			background: #f8f9fa !important;
			box-sizing: border-box !important;
		}
		
		.ranking-table td {
			display: block !important;
			width: 100% !important;
			max-width: 100% !important;
			padding: 0.5rem 0 !important;
			text-align: left !important;
			border: none !important;
			font-size: 0.9rem !important;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
			box-sizing: border-box !important;
		}
		
		.ranking-table td:before {
			content: attr(data-label) ": ";
			font-weight: bold !important;
			color: #0077AA !important;
			margin-right: 0.5rem !important;
			display: inline-block !important;
		}
		
		.ranking-table td:first-child:before {
			content: "순위: " !important;
		}
		
		.ranking-table td:nth-child(2):before {
			content: "이름: " !important;
		}
		
		.ranking-table td:nth-child(3):before {
			content: "선물: " !important;
		}
		
		.ranking-table td:nth-child(4):before {
			content: "지급완료: " !important;
		}
		
		.ranking-table td .rank-number,
		.ranking-table td .employee-name {
			display: inline !important;
		}
		
		/* 텍스트 오버플로우 방지 */
		* {
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
			box-sizing: border-box !important;
		}
		
		/* 모든 텍스트 요소 강제 줄바꿈 */
		p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
			word-break: break-word !important;
			white-space: normal !important;
			max-width: 100% !important;
			box-sizing: border-box !important;
		}
		
		/* span 요소 줄바꿈 처리 */
		span {
			display: inline-block !important;
			overflow: visible !important;
			max-width: 100% !important;
			box-sizing: border-box !important;
		}
		
		/* 모든 div 요소 오버플로우 방지 */
		div {
			max-width: 100% !important;
			overflow-x: hidden !important;
			box-sizing: border-box !important;
		}
		
		/* 결과 테이블 컨테이너 추가 최적화 */
		.results-container table {
			width: 100% !important;
			max-width: 100% !important;
			table-layout: fixed !important;
		}
		
		/* '기간' 버튼 숨기기 */
		#showdate {
			display: none !important;
		}
		
		/* 모달 최적화 */
		.countdown-modal {
			padding: 0 !important;
			overflow: hidden !important;
			z-index: 9999 !important;
		}
		
		.countdown-content {
			margin: 0 !important;
			width: 100% !important;
			max-width: 100% !important;
			height: 100vh !important;
			max-height: 100vh !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			box-sizing: border-box !important;
		}
		
		#countdown {
			font-size: 8rem !important;
			word-wrap: break-word !important;
			overflow-wrap: break-word !important;
		}
		
		/* 체크박스 최적화 */
		.gift-checkbox {
			width: 1.5em !important;
			height: 1.5em !important;
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
    
    const isMobile = window.innerWidth <= 768;
    
    rankedEmployees.forEach((employee, index) => {
        const row = document.createElement('tr');
        row.classList.add('slide-in');
        row.style.animationDelay = `${index * 0.1}s`;
        
        const rank = index + 1;
        const gift = gifts[index] || '🎁 참가상';
        
        if (isMobile) {
            // 모바일에서는 카드 형식으로 표시
            row.innerHTML = `
                <td data-label="순위"><span class="rank-number">${rank}위</span></td>
                <td data-label="이름"><span class="employee-name">${employee}</span></td>
                <td data-label="선물">${gift}</td>
                <td data-label="지급완료"><input type="checkbox" class="gift-checkbox" id="gift-${index}"></td>
            `;
        } else {
            // 데스크톱에서는 일반 테이블 형식
            row.innerHTML = `
                <td><span class="rank-number">${rank}위</span></td>
                <td><span class="employee-name">${employee}</span></td>
                <td>${gift}</td>
                <td><input type="checkbox" class="gift-checkbox" id="gift-${index}"></td>
            `;
        }
        
        resultsTableBody.appendChild(row);
    });
}

// 이벤트 리스너 등록
drawButton.addEventListener('click', startDraw);

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', function() {
    // 결과 컨테이너는 처음에 숨김
    resultsContainer.style.display = 'none';
    
    // 창 크기 변경 시 테이블 다시 렌더링
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // 결과가 표시되어 있으면 다시 렌더링
            if (resultsContainer.style.display === 'block' && resultsTableBody.children.length > 0) {
                const currentEmployees = Array.from(resultsTableBody.children).map(row => {
                    return row.querySelector('.employee-name').textContent;
                });
                generateResultsTable(currentEmployees);
            }
        }, 250);
    });
});
    </script>
</body>
</html>