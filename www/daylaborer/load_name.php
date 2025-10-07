<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }  
?>


 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
 <?php

if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	$search=$_REQUEST["search"];	  

	
ini_set('display_errors','1');  // 화면에 warning 없애기	

$tablename = $_REQUEST["tablename"];   
	  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

  // $find="unitper";	    //검색할때 고정시킬 부분 저장 ex) 전체/공사담당/건설사 등
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
 
  $scale = 10;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";      
	
	$b="  order by num desc";	

	$sql ="select * from ".$DB.".".$tablename . "  "  . $b;
   
?>	

<title> 일용직 이름검색 </title>


<form id="board_form" name="board_form" method="post" enctype="multipart/form-data"   >			 

	<input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>">             
	<input type="hidden" id="num" name="num" value=<?=$num?> > 
	<input type="hidden" id="page" name="page" value=<?=$page?> > 
	<input type="hidden" id="mode" name="mode" value=<?=$mode?> > 				
	<input type="hidden" id="tablename" name="tablename" value=<?=$tablename?> > 				
	 
<div class="container"  style="width:500px;">	
				
<div class="card justify-content-center text-center mt-1" >
	<div class="card-header align-items-center ">
		<span class="text-center fs-5 me" > 이름 찾기  </span>
	</div>
	<div class="card-body" >											
	<div class="d-flex align-items-center justify-content-start mb-2">
		<button id="closeBtn" type="button" onclick="self.close();" class="btn btn-dark  btn-sm me-2"  > <ion-icon name="close-outline"></ion-icon> 창닫기  </button> 	   
	</div>								
	
	<div class="table-reponsive" >	
	  <table class="table table-bordered table-hover">				 
	       <thead class="table-primary">
				 <th>번호</th>				 
				 <th>성명</th>		
				 <th>비고</th>		
			</thead>
			<tbody>		      	 
<?php  

	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
      $total_row = $temp2;     // 전체 글수	 
  
        					 
  	  $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	  $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산		

		  $unique_combinations = []; // 중복 확인을 위한 배열

		  if ($page <= 1)  
			$start_num = $total_row;
		  else 
			$start_num = $total_row - ($page - 1) * $scale;
		  
		  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			$labor_name = $row["labor_name"];
			$part = $row["part"];
			
			$combination = $labor_name . '_' . $part; // 조합 생성

			// 중복 검사
			if (!in_array($combination, $unique_combinations)) {
			  $unique_combinations[] = $combination; // 배열에 추가

			  // 테이블 행 표시
			  ?>
			  <tr onclick="maketext('<?=$labor_name?>');return false;" > 
				<td class="text-center" >  <?= $start_num ?> </td>
				<td class="text-center" >  <?=$labor_name ?>  </td>      
				<td class="text-center" >  <?=$part ?>  </td>      
			  </tr>
			  <?php
			  $start_num--;
			}
		  } 
		} catch (PDOException $Exception) {
		  print "오류: ".$Exception->getMessage();
		}
?>
 
  </tbody>
 </table>
 
		 </div>
		 
		</div>
	</div>    
</div>

</form>
</body>
</html>

<script>

function movetoPage(page){ 	  
	  $("#page").val(page);   
     $("#board_form").submit();
}	


///////////////////// input 필드 값 옆에 X 마크 띄우기 
///////////////////// input 필드 값 옆에 X 마크 띄우기 

	var btnClear = document.querySelectorAll('.btnClear');
	btnClear.forEach(function(btn){
		btn.addEventListener('click', function(e){
			btn.parentNode.querySelector('input').value = "";
			e.preventDefault(); // 기본 이벤트 동작 막기
		  // 포커스 얻기
		  btn.parentNode.querySelector('input').focus();				
		})
	})	


/* ESC 키 누를시 팝업 닫기 */
$(document).keydown(function(e){
	//keyCode 구 브라우저, which 현재 브라우저
	var code = e.keyCode || e.which;

	if (code == 27) { // 27은 ESC 키번호
		self.close();
	}
});
	
// 실행
function maketext(firstitem)   // 클릭시 화면에 정보 보여줌, 코드명, 거래처
{	    
    
	$("#labor_name", opener.document).val(firstitem); 	

	if (window.opener && typeof window.opener.updateOptions === "function") {
		window.opener.updateOptions("#labor_name" , firstitem);
	}
	  self.close();		  
}	
	
	// 창닫기 버튼
	$("#closeBtn").on("click", function() {
		self.close();
	});	
	
	

	
</script>
