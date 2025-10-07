<?php include $_SERVER['DOCUMENT_ROOT'] . '/session.php';   

$title_message = '미래기업 추억 사진 영상'; 
 
include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?> 
 
<meta property="og:type" content="미래기업 유튜브">
<meta property="og:title" content="미래기업 추억">
<meta property="og:url" content="8440.co.kr">
<meta property="og:description" content="미래기업 영상 모음!">
<meta property="og:image" content="http://8440.co.kr/img/miraethumbnail.jpg"> 
<!-- viewport
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />	
 --> 
 
<title> <?=$title_message?> </title> 
</head>

<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/myheader.php'; ?>   

<!-- background: -webkit-linear-gradient(left, #33156d 0%,#f282bc 100%); /* Chrome10-25,Safari5.1-6 */  -->
<style>
	.progress-bar {
	background: -webkit-linear-gradient(left, #dcdcdc 0%,#3c3c3c 100%); /* Chrome10-25,Safari5.1-6 */
	}
	.progress-bar2 {
	background: -webkit-linear-gradient(left, #CCCCFF 0%,#aaaaaa 100%); /* Chrome10-25,Safari5.1-6 */
	}
	.typing-txt{display: none;}
	.typeing-txt ul{list-style:none;}
	.typing {  
	  display: inline-block; 
	  animation-name: cursor; 
	  animation-duration: 0.3s; 
	  animation-iteration-count: infinite; 
	} 
	@keyframes cursor{ 
	  0%{border-right: 1px solid #fff} 
	  50%{border-right: 1px solid #000} 
	  100%{border-right: 1px solid #fff}   
	  }    
</style>

<div class="container" >  
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 					

<style>
.photo-frame {
    margin: 15px;
    padding: 10px;
    background-color: white;
    border: 5px solid #ccc;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: inline-block;
}

.framed-photo {
    width: 45%;
    height: auto;    
    border: 1px solid #ddd;
}
.framed-photofull {
    width: 90%;
    height: auto;    
    border: 1px solid #ddd;
}
</style>

<!-- 홍천스키 -->
<div class="row d-flex board_list" style="padding:0;">		
<div class="col-sm-12 board_list" style="padding:7;">
    <div class="card justify-content-center my-card-padding">
        <div class="card-header text-center my-card-padding mt-5 mb-2">
		<button  type="button" id="albumBtn4" class="btn btn-dark btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>  
           <span class="fw-bold shop-header fs-5" >  여직원 단합식사 </span> 	
        </div>
	  <div id="album4"> 		 		    		
		<div class="d-flex justify-content-center align-items-center">
		<div class="photo-frame justify-content-center text-center">
			<?php
				$photoPath = "img/trip/2024trip001.jpg";				
				echo '<img src="' . $photoPath . '" class="framed-photofull">';			
			?>
		</div>
		</div>
    </div>
    </div>
</div> 
</div> 
<!-- 홍천스키 -->
<div class="row d-flex board_list" style="padding:0;">		
<div class="col-sm-12 board_list" style="padding:7;">
    <div class="card justify-content-center my-card-padding">
        <div class="card-header text-center my-card-padding mt-2 mb-2">
		<button  type="button" id="albumBtn1" class="btn btn-dark btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>  
           <span class="fw-bold shop-header fs-5" >  2025 홍천 스키 </span> 	
        </div>
	  <div id="album1"> 		 		    
		<div class="d-flex p-2 mb-2 justify-content-center"> 		 		    
			<iframe width="315" height="560" src="https://www.youtube.com/embed/CpgEZMwbamU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> &nbsp;&nbsp;&nbsp;
			<iframe width="315" height="560" src="https://www.youtube.com/embed/GWBmJ-EQz8c" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>		    			
		</div>						
		<div class="d-flex justify-content-center align-items-center">
		<div class="photo-frame justify-content-center text-center">
			<?php
			for ($i = 1; $i <= 36; $i++) {
				// 사진 파일 경로 생성
				$photoPath = "img/trip/202501trip" . $i . ".jpg";
				// 사진 태그 출력
				echo '<img src="' . $photoPath . '" class="framed-photo">';
			}
			?>
		</div>
		</div>
    </div>
    </div>
</div> 
</div> 

<!-- 2024 한탄강 -->
<div class="row d-flex board_list" style="padding:0;">		
<div class="col-sm-12" style="padding:7;">
    <div class="card justify-content-center my-card-padding">
        <div class="card-header text-center my-card-padding">        
		<button  type="button" id="albumBtn2" class="btn btn-dark btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>  
           <span class="fw-bold shop-header fs-5" >  2024 한탄강 </span> 	           
        </div>
	  <div id="album2"> 			
        <div class="d-flex justify-content-center align-items-center ">
            <div class="photo-frame justify-content-center  text-center">
                <img src="img/trip/20241213_trip1.jpg" class="framed-photo">
				<img src="img/trip/20241213_trip2.jpg" class="framed-photo">
            </div>            
        </div>
    </div>
</div> 
</div> 
</div> 

<!-- 2023 군산 선유도 -->
<div class="row d-flex" style="padding:0;">		
<div class="col-sm-12" style="padding:7;">
    <div class="card justify-content-center my-card-padding">
        <div class="card-header text-center my-card-padding mt-2 mb-2">
		<button  type="button" id="albumBtn3" class="btn btn-dark btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>  
           <span class="fw-bold shop-header fs-5" >  2023 군산 선유도 </span> 	
        </div>
	  <div id="album3"> 		 		    		
		<div class="d-flex justify-content-center align-items-center">
		<div class="photo-frame justify-content-center text-center">
			<?php
			for ($i = 1; $i <= 56; $i++) {
				// 숫자를 두 자리로 포맷
				$formattedNumber = sprintf("%02d", $i);
				// 사진 파일 경로 생성
				$photoPath = "img/trip/2023trip" . $formattedNumber . ".jpg";
				// 사진 태그 출력
				echo '<img src="' . $photoPath . '" class="framed-photo">';
			}
			?>
		</div>
		</div>
    </div>
    </div>
</div> 
</div> 

<!-- 2022 동해 속초-->
<div class="row d-flex" style="padding:0;">		
<div class="col-sm-12" style="padding:7;">
    <div class="card justify-content-center my-card-padding">
        <div class="card-header text-center my-card-padding mt-2 mb-2">
		<button  type="button" id="albumBtn5" class="btn btn-dark btn-sm me-2 fw-bold"> <i class="bi bi-chevron-down"></i> </button>  
           <span class="fw-bold shop-header fs-5" >  2022 동해 속초 </span> 	
        </div>
	  <div id="album5"> 		 		    		
		<div class="d-flex justify-content-center align-items-center">
		<div class="photo-frame justify-content-center text-center">
			<?php
			for ($i = 1; $i <= 28; $i++) {
				// 숫자를 두 자리로 포맷
				$formattedNumber = sprintf("%02d", $i);
				// 사진 파일 경로 생성
				$photoPath = "img/trip/2022trip" . $formattedNumber . ".jpg";
				// 사진 태그 출력
				echo '<img src="' . $photoPath . '" class="framed-photo">';
			}
			?>
		</div>
		</div>
    </div>
    </div>
</div> 
</div> 


  	  <div class="d-flex p-2 mb-2 mt-5 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 공장문턱 단차 제거작업
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/lthPaJyxLUo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>

  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 공장바닥 라인 도색
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/e34jGHQXEy0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>

  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 창립10주년 행사 new
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/YKcek2of6S8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				new 미래기업 드론촬영분
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/PzX3742gYjM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				2022 미래기업 강원도 여행
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/cRBlI-x3GSc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>

  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				김이사님 5만원 아저씨 빙의
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/QZpHQVvLOxA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 무사고 안전기원
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/VC454gmrU6E" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 전직원 드론촬영
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/wssvBQ5vS1Y" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>

  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				제1회 미래기업 골프 미니퍼팅대회결과
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/laoox8c0bA8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 사내 미니퍼팅대회
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/lW5hrw_vWsU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 직원콜라보 Lee Kyoungmook Enamul Rana
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/eV2oLtAzzoQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 사무실 어느 퍼팅매트 입고날 feat 제공 안차장님
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/ZJP5uRV7JU0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 직원소개 Rana 용접마스터
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/aU1spL0v2gI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 이경묵 공장장 영상
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/B3OPdFKm7JY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 직원 성실한 Enamul 영상
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/wNRrbx4WXW8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>

  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 직원 패러디 넌 나에게 모욕감을 줬어
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/PnrQpLDXkfI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				[드론촬영] 미래기업 공장소개
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/XFFPwYE9nKg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 찾아오는 방법 (김포시 양촌읍 흥신로)
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/zPeiLZm8peQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		    </h4>	
	  </div>	
	  
  	  <div class="d-flex p-2 mb-2 mt-3 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 
				미래기업 공장이전 프롤로그
		    </h4>	
	  </div>
	  <div class="d-flex p-2 mb-2 justify-content-center"> 		 
		    <h4 class="text-secondary text-center"> 		
				<iframe width="560" height="315" src="https://www.youtube.com/embed/QzHtt-meogo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>		
		    </h4>	
	  </div>					
		 
<br/><br/>

<? include 'footer.php'; ?>   

 </div> <!-- container-fulid end -->

  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/counter.js"></script>
  <script src="assets/js/custom.js"></script>
  
<script>
$(document).ready(function() {
    // 쿠키 저장 함수
    function setCookie(name, value, days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    // 쿠키 읽기 함수
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }
        return null;
    }

    // 공통 클릭 이벤트 처리 함수
    function toggleAlbum(buttonId, albumId, cookieName) {
        var albumContainer = $(albumId);

        // 페이지 로딩 시 쿠키 값에 따라 초기 상태 설정
        var showAlbum = getCookie(cookieName);
        if (showAlbum === "hide") {
            albumContainer.css("display", "none");
        } else {
            albumContainer.css("display", "inline-block");
        }

        // 버튼 클릭 이벤트
        $(buttonId).on("click", function () {
            if (albumContainer.css("display") === "none") {
                albumContainer.css("display", "inline-block");
                setCookie(cookieName, "show", 10);
            } else {
                albumContainer.css("display", "none");
                setCookie(cookieName, "hide", 10);
            }
        });
    }

    // 앨범 버튼과 컨테이너 매핑
    toggleAlbum("#albumBtn1", "#album1", "showAlbum1");
    toggleAlbum("#albumBtn2", "#album2", "showAlbum2");
    toggleAlbum("#albumBtn3", "#album3", "showAlbum3");
    toggleAlbum("#albumBtn4", "#album4", "showAlbum4");
    toggleAlbum("#albumBtn5", "#album5", "showAlbum5");
});


$(document).ready(function(){    
   // 방문기록 남김
   var title = '<?php echo $title_message; ?>';   
   // title = '절곡 ' + title ;
   saveMenuLog(title);
});	

</script>
</body>
</html>

