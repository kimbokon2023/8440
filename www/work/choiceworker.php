<!-- Work List -->

<?php
require_once __DIR__ . '/../bootstrap.php';

// 첫 화면 표시 문구
$title_message = 'jamb 시공소장'; 

$user_name = $_SESSION["name"] ?? null;
$level = $_SESSION["level"] ?? null;

if(!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php"); 
    exit;
}

ini_set('display_errors','0');  // 화면에 warning 없애기
ini_set('log_errors','1');

?>
 
<?php include includePath('load_header.php'); ?>
 
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
    background-color: #ffffff; /* 짝수 줄 배경색 변경 */
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
		
		// Initialize variables to prevent undefined variable warnings
		$done_check_val = $_REQUEST['done_check_val'] ?? '';
		$voc_alert = $_REQUEST['voc_alert'] ?? '';
		$ma_alert = $_REQUEST['ma_alert'] ?? '';
		$order_alert = $_REQUEST['order_alert'] ?? '';
		$page = $_REQUEST['page'] ?? '';
		$scale = $_REQUEST['scale'] ?? '';
		$yearcheckbox = $_REQUEST['yearcheckbox'] ?? '';
		$year = $_REQUEST['year'] ?? '';
		$check = $_REQUEST['check'] ?? '';
		$output_check = $_REQUEST['output_check'] ?? '';
		$plan_output_check = $_REQUEST['plan_output_check'] ?? '';
		$team_check = $_REQUEST['team_check'] ?? '';
		$measure_check = $_REQUEST['measure_check'] ?? '';
		$cursort = $_REQUEST['cursort'] ?? '';
		$sortof = $_REQUEST['sortof'] ?? '';
		$stable = $_REQUEST['stable'] ?? '';
		$sqltext = $_REQUEST['sqltext'] ?? '';
		?>
   			
	</div>	
</div>	 
  
  
			<input type="hidden" id="done_check_val" name="done_check_val" value="<?= htmlspecialchars($done_check_val, ENT_QUOTES, 'UTF-8') ?>" >
			<input type="hidden" id="voc_alert" name="voc_alert" value="<?= htmlspecialchars($voc_alert, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="ma_alert" name="ma_alert" value="<?= htmlspecialchars($ma_alert, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="order_alert" name="order_alert" value="<?= htmlspecialchars($order_alert, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="page" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="scale" name="scale" value="<?= htmlspecialchars($scale, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?= htmlspecialchars($yearcheckbox, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="year" name="year" value="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="check" name="check" value="<?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="output_check" name="output_check" value="<?= htmlspecialchars($output_check, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?= htmlspecialchars($plan_output_check, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="team_check" name="team_check" value="<?= htmlspecialchars($team_check, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="measure_check" name="measure_check" value="<?= htmlspecialchars($measure_check, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="cursort" name="cursort" value="<?= htmlspecialchars($cursort, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="sortof" name="sortof" value="<?= htmlspecialchars($sortof, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="stable" name="stable" value="<?= htmlspecialchars($stable, ENT_QUOTES, 'UTF-8') ?>" size="5" > 	
			<input type="hidden" id="sqltext" name="sqltext" value="<?= htmlspecialchars($sqltext, ENT_QUOTES, 'UTF-8') ?>" > 

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
	// self.close();	
	popupCenter(window.baseUrl + "/p/index.php?workername=" + name , "소장 모바일 쟘공사 화면",1100,950);
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
