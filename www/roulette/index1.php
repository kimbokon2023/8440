<?php session_start(); 

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21   
//header("Refresh:0");  // reload refresh  

?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="http://8440.co.kr/common.js"></script>
<script src="http://8440.co.kr/js/typingscript.js"></script>  <!-- typingscript.js 포함  글자 움직이면서 써지는 루틴 -->
<!-- 최초화면에서 보여주는 상단메뉴 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

<link rel="stylesheet" href="./css/style1.css">

<body>
<title> 룰렛(loulette) 이벤트 추첨 </title>

<div class="container-fluid">  

<?php
		if(isset($_SESSION["name"])) 
		{
			 ?>
			 <div class="d-flex mb-1 justify-content-center">    
		   <a href="../index.php"><img src="../img/companylogo.png" style="width:100%;" ></a>	
		</div>

		<? include '../myheader.php'; ?>
		

<? } ?>
	 
</div>

<div class="container-fluid">  

    <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">알림</h4>
        </div>
        <div class="modal-body">		
		   <div id=alertmsg class="fs-1 mb-5 justify-content-center" >
		     결재가 진행중입니다. <br> 
		   <br> 
		  수정사항이 있으면 결재권자에게 말씀해 주세요.
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>





    <div id="app" class="d-flex  mt-5 mb-5 justify-content-center "></div>
	
	</div>
</div>

<div class="container-fluid">  

<?php
		if(isset($_SESSION["name"])) 
		{
			 ?>
		<? include '../footer.php'; ?>		

<? } ?>



</div>
</body>
</html>


<script>
 
$(document).ready(function(){	

	$("#closemodalBtn").click(function(){   	
		$('#myModal').modal('hide');						 
	});  
	
	

});


function alertmodal(tmp, second)
{	
	$('#alertmsg').html(tmp); 			  
	$('#myModal').modal('show'); 	
	
	setTimeout(function() {
	$('#myModal').modal('hide');  
	}, second);		
	
}



var rolLength = 6; // 해당 룰렛 콘텐츠 갯수
var setNum; // 랜덤숫자 담을 변수
var hiddenInput = document.createElement("input");
hiddenInput.className = "hidden-input";

//랜덤숫자부여
const rRandom = () => {
  var min = Math.ceil(0);
  var max = Math.floor(rolLength - 1);
  return Math.floor(Math.random() * (max - min)) + min;
};

const rRotate = () => {
  var panel = document.querySelector(".roulette-wacu");
  var btn = document.querySelector(".roulette-btn");
  var deg = [];
  // 룰렛 각도 설정(rolLength = 6)
  for (var i = 1, len = rolLength; i <= len; i++) {
    deg.push((360 / len) * i);
  }
  
  // 랜덤 생성된 숫자를 히든 인풋에 넣기
  var num = 0;
  document.body.append(hiddenInput);
  setNum = hiddenInput.value = rRandom();
	
  // 애니설정
  var ani = setInterval(() => {
    num++;
    panel.style.transform = "rotate(" + 360 * num + "deg)";
    btn.disabled = true; //button,input
    btn.style.pointerEvents = "none"; //a 태그
    
    // 총 50에 다달했을때, 즉 마지막 바퀴를 돌고나서
    if (num === 50) {
      clearInterval(ani);
      panel.style.transform = `rotate(${deg[setNum]}deg)`;
    }
  }, 50);
};

// 정해진 alert띄우기, custom modal등
const rLayerPopup = (num) => {
  switch (num) {
    case 1:
	alertmodal("당첨!! 스타벅스 아메리카노", 2000);    
      break;
    case 3:
	alertmodal("당첨!! 햄버거 세트 교환권", 2000);
      break;
    case 5:
	alertmodal("당첨!! CU 3,000원 상품권", 2000);
      break;
    default:
	alertmodal("꽝! 다음기회에", 2000);      
  }
};

// reset
const rReset = (ele) => {
  setTimeout(() => {
    ele.disabled = false;
    ele.style.pointerEvents = "auto";
    rLayerPopup(setNum);
    hiddenInput.remove();
  }, 5500);
};

// 룰렛 이벤트 클릭 버튼
document.addEventListener("click", function (e) {
  var target = e.target;
  if (target.tagName === "BUTTON") {
    rRotate();
    rReset(target);
  }
});

// roulette default
document.getElementById("app").innerHTML = `
<div class="roulette">
    <div class="roulette-bg">
        <div class="roulette-wacu"></div>
    </div>
    <div class="roulette-arrow"></div>
    <button class="roulette-btn">start</button>
</div>
`;

</script>