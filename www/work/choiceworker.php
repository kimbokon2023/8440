<!-- Work List -->

<?php
 session_start();
 
 // 첫 화면 표시 문구
 $title_message = 'jamb 시공소장'; 
 
 $user_name= $_SESSION["name"];
 $level= $_SESSION["level"];
 
  if(!isset($_SESSION["level"]) || $level>5) {
		 sleep(1);
		  header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }

ini_set('display_errors','1');  // 화면에 warning 없애기	

 ?>
 
 <?php include getDocumentRoot() . '/load_header.php' ?>
 
<title>  <?=$title_message?>  </title>

</head>
 
<style>
 @charset "utf-8";
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

  .table-hover tbody tr:hover {
    background-color: #000000; /* 원하는 배경색으로 변경하세요 */
	 cursor: pointer; /* 원하는 커서 스타일로 변경하세요 */
  }


  .custom-table-striped tbody tr:nth-of-type(odd) {
    background-color: #f5f5f5; /* 홀수 줄 배경색 변경 */
  }
  .custom-table-striped tbody tr:nth-of-type(even) {
    background-color: #fsfsfs; /* 짝수 줄 배경색 변경 */
  }
  
</style>

<title> 소장화면 </title>

</head>

<body>

<div class="container-fluid justify-content-center align-items-center mt-1 mb-1">  	
<div class="card">  	
<div class="card-body">  	
	<div class="d-flex mb-1 mt-1 justify-content-center align-items-center">
	        <span class="badge bg-primary fs-2 " > 소장 전용 모바일 화면(쟘) </span>
	</div>	
	<div class="d-flex mb-3 mt-5 justify-content-center align-items-center">
	        <span class="text-secondary " > 소장을 선택하면 해당소장 이름으로 화면을 볼 수 있습니다. </span> &nbsp;&nbsp;&nbsp;&nbsp;			
	</div>	
	<div class="d-flex mb-1 mt-1 justify-content-center align-items-center">	        
	<?php
		$com1_arr = array( '추영덕', '이만희', '김운호', '김상훈', '유영', '손상민', '조장우', '박철우', '이인종','이춘일','서영선');

		foreach ($com1_arr as $worker) {
			echo "<button type='button' onclick=\"movetoPersonPage('$worker')\" class='btn btn-secondary m-2'>$worker</button>";
		}
		?>
   			
	</div>	
</div>	 
  
  
			<input type="hidden" id="done_check_val" name="done_check_val" value="<?=$done_check_val?>" >
			<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
			<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
			<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
			<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
			<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
			<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" > 	
			<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" > 	
			<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
			<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
			<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
			<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
			<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
			<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
			<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
			<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
			<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 

     </div>   
     </div>   
</body>

</html>

   
  
<script>

function blinker() {
	$('.blinking').fadeOut(500);
	$('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);

function SearchEnter(){

    if(event.keyCode == 13){
	
    $("#page").val('1');		
	document.getElementById('board_form').submit(); 
    }
}

function movetoPersonPage(name) {
    // Do something with the name
    console.log(name);
    // alert(name);
	self.close();	
	popupCenter("../p/index.php?workername=" + name , "소장 모바일 쟘공사 화면",1100,950);
}


function exe_view_table(){   // 출고현황 검색을 클릭시 실행

document.getElementById('view_table').value="search"; 

document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 

function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}


</script>
