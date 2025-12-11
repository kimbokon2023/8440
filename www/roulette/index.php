<?php
session_start();

// Load common functions
require_once __DIR__ . '/../bootstrap.php';

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21
//header("Refresh:0");  // reload refresh

$Employee = 17; // 직원수

?>

 <?php include getDocumentRoot() . '/load_header.php' ?>
 
<link rel="stylesheet" href="./css/style.css">

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 영역 최적화 */
        .d-flex.mt-5.mb-5.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-5.mb-5.justify-content-center .badge {
            width: 100% !important;
            max-width: 100% !important;
            text-align: center !important;
            font-size: 1.25rem !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-5.mb-5.justify-content-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 카드 영역 최적화 */
        .d-flex.justify-content-center.align-self-center.mt-2 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 최적화 */
        .card-body h3 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
        }
        
        .card-body h4 {
            font-size: 1.1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
        }
        
        /* Canvas 최적화 */
        #canvas_person,
        #canvas {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            aspect-ratio: 1 / 1 !important;
        }
        
        /* 버튼 그룹 최적화 */
        .d-flex.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.justify-content-center.align-items-center input[type="checkbox"],
        .d-flex.justify-content-center.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .d-flex.justify-content-center.align-items-center span {
            width: 100% !important;
            max-width: 100% !important;
            text-align: center !important;
            margin: 0.25rem 0 !important;
        }
        
        /* 리스트 영역 최적화 */
        #personlist,
        #giftlist {
            width: 100% !important;
            max-width: 100% !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
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
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
</style>

<body>
<?php include getDocumentRoot() . '/myheader.php' ?>
<title> 룰렛(loulette) 선물 추첨 </title>	


<div class="container-fluid">  
  
   <div class="d-flex mt-5 mb-5 justify-content-center">  
   
  <a class="nav-link" href="<?$root_dir?>/index2.php" title="mirae Homepage"  ><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-house-check-fill" viewBox="0 0 16 16">
  <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
  <path d="m8 3.293 4.712 4.712A4.5 4.5 0 0 0 8.758 15H3.5A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.707l.547.547 1.17-1.951a.5.5 0 1 1 .858.514Z"/>
</svg> </a>

 &nbsp;&nbsp;
    <span class="badge bg-primary text-white fs-4 " > &nbsp; 선물추첨 프로그램 &nbsp;</span>
	<button type="button" class="btn btn-dark btn-sm ms-5" onclick='location.href="index_dart.php"' > 	<i class="bi bi-radar"></i> dart </button>&nbsp; &nbsp; 
	

  </div>	

  <div class="d-flex justify-content-center align-self-center mt-2">
    <div class="card col-sm-5" >
      <div class="card-body">
			<div class="d-flex justify-content-center align-items-center">
				<h3 class="text-center"> 랜덤 직원 선택 </h3> &nbsp;&nbsp; <span id="person_su" class="text-primary fs-4"> </span> 남은분 <span id="person_su_remain" class="text-dark fs-4"> </span>
			</div>
		<div class="d-flex justify-content-center align-items-center">
				<canvas id="canvas_person" width="500" height="500"></canvas>
		 </div>
        <br>
		  <div class="d-flex justify-content-center align-items-center">
			 <input type="checkbox" id="both"  name="both" checked value="1"> 상품추첨 동시실행  &nbsp; &nbsp; &nbsp; &nbsp; 			 
			 <button type="button" class="btn btn-outline-dark btn-sm " id='spin_all'> 일괄실행(전체) </button>&nbsp; &nbsp; 
			 <button type="button" class="btn btn-dark btn-sm " id='spin_person'> 개별 직원 선택 </button> 
		  </div>	
      </div>
    </div>
    <div class="card col-sm-1" >
      <div class="card-body">
        <div id="person_script" > <h4 class="text-center"> 선택 </h4> </div>
		 <div class="d-flex justify-content-center align-items-center mt-2">
				 <div id="personlist" class="row d-flex  justify-content-center fs-4"  > </div> 
		 </div>
		  
      </div>
    </div>
     <div class="card col-sm-4" >
      <div class="card-body">
        <h3 class="text-center"> 행운의 선물은? </h3>
		
		<div class="d-flex mt-3 justify-content-start align-items-center mt-1">
				 <div id="display"  > </div> 
		 </div>
		  <div class="d-flex justify-content-center align-items-center">
			<canvas id="canvas" width="500" height="500" class="d-flex justify-content-center" ></canvas>
		  </div>        
		  <div class="d-flex justify-content-center align-items-center">
			<button type="button" class="btn btn-success btn-sm " id='spin'> 강제 선택 </button>
		  </div>	
      </div>
    </div>
    <div class="card col-2" >
      <div class="card-body">
         <div id="gift_script" > <h3 class="text-center"> 선물추첨 결과 </h3> </div>
		 <div class="d-flex justify-content-center align-items-center mt-2">
				 <div id="giftlist" class="d-flex justify-content-center fs-5"  > </div> 
		 </div>
		  
      </div>
    </div>	
  </div>
  
<script src="index.js">         </script>  
<script src="index_person.js">  </script>

  <!-- 자바스크립트 TTS 구현 -->
<div class="container-fluid" style="display:none;">  
    <select id="select-lang">
        <option value="ko-KR" selected>한국어</option>
        <option value="ja-JP">일본어</option>
        <option value="en-US">영어</option>
    </select>
    <input id="text"> 
    <button id="btn-read">읽기</button>
</div>

</div>

</body>
</html>

<script>

$(document).ready(function(){
	// 모바일에서 Canvas 크기 조정
	function adjustCanvasSize() {
		if (window.innerWidth <= 768) {
			var canvasPerson = document.getElementById('canvas_person');
			var canvas = document.getElementById('canvas');
			
			if (canvasPerson) {
				var containerWidth = canvasPerson.parentElement.clientWidth - 20;
				canvasPerson.style.width = containerWidth + 'px';
				canvasPerson.style.height = containerWidth + 'px';
			}
			
			if (canvas) {
				var containerWidth = canvas.parentElement.clientWidth - 20;
				canvas.style.width = containerWidth + 'px';
				canvas.style.height = containerWidth + 'px';
			}
		}
	}
	
	// 초기 조정
	adjustCanvasSize();
	
	// 창 크기 변경 시 조정
	$(window).on('resize', function() {
		adjustCanvasSize();
	});
	
	// 사람선택 누를시
	$("#spin_person").click(function(){ 
	   // audioRandom();
	   spin_person(); 
		$('input:checkbox[name=both]').each(function (index) {
			if($(this).is(":checked")==true){
				// audioRandom();
				setTimeout(() => spin(), 6000);  
			}
		});
		   
	});
	
	// 전체
	$("#spin_all").click(function(){ 		
		// 첫번째는 그냥 실행
		spin_person();
		setTimeout(() => spin(), 8000);  
		
		let count = 0;
		let id = setInterval(frame, 24000);
		let personNum = <?php echo $Employee; ?>;  // 직원수대로 하는 것이다.
		  function frame() {
			if (count == personNum - 1 ) {
			  clearInterval(id);
			} else {
			  count++;
				spin_person();
				setTimeout(() => spin(), 7500);  
			   // 직원수 표시
			  $("#person_su_remain").text(personNum - count );				
			}
		  }
		if(count === personNum)
		{
			Swal.fire({
				title: '선물 추첨 완료',
				text: "추첨을 완료했습니다. \n\r 즐거운 명절되세요!",
				icon: 'info',
				confirmButtonText: '확인'
			});  
		}

	  });

   // 직원수 표시
  $("#person_su").text(rolLength_p);
  $("#person_su_remain").text(rolLength_p);	  
});

function speak(text, opt_prop) {
	if (typeof SpeechSynthesisUtterance === "undefined" || typeof window.speechSynthesis === "undefined") {
		alert("이 브라우저는 음성 합성을 지원하지 않습니다.")
		return
	}
	
	window.speechSynthesis.cancel() // 현재 읽고있다면 초기화

	const prop = opt_prop || {}

	const speechMsg = new SpeechSynthesisUtterance()
	speechMsg.rate = prop.rate || 1 // 속도: 0.1 ~ 10      
	speechMsg.pitch = prop.pitch || 1 // 음높이: 0 ~ 2
	speechMsg.lang = prop.lang || "ko-KR"
	speechMsg.text = text
	
	// SpeechSynthesisUtterance에 저장된 내용을 바탕으로 음성합성 실행
	window.speechSynthesis.speak(speechMsg)
}


// 이벤트 영역
const selectLang = document.getElementById("select-lang")
const text = document.getElementById("text")
const btnRead = document.getElementById("btn-read")

btnRead.addEventListener("click", e => {
	speak(text.value, {
		rate: 1,
		pitch: 1.5,
		lang: selectLang.options[selectLang.selectedIndex].value
	})
})

</script>