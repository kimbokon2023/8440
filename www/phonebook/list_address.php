<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  
?>
<?php include getDocumentRoot() . '/load_header.php' ?>
<title> 주소 검색 </title>
<style>
	@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");

	fieldset.groupbox-border {
	border: 1px groove #ddd !important; 
	padding: 3 3 3 3 !important; 
	margin: 3 3 3 3 !important; 
	box-shadow: 0px 0px 0px 0px #000; 
	} 

	legend.groupbox-border { 
		background-color: #F0F0F0;
		color: #000;
		padding: 3px 6px;
	font-size: 1.0em !important; 
	font-weight: bold !important; 
	text-align: left !important; 
	border-bottom:none; 
	}  

	fieldset.groupbox1-border {
	border: 1px groove #ddd !important; 
	padding: 3 3 3 3 !important; 
	margin: 3 3 3 3 !important; 
	} 

	legend.groupbox1-border { 
		background-color: #F0F0F0;
		color: #000;
		padding: 9px 9px;
		font-size: 1.0em !important; 
		font-weight: bold !important; 
		text-align: left !important; 
		border-bottom:none; 
	}   

	.input-group-text {
		display: flex;
		align-items: center;
		padding: 0.375rem 0.75rem;
		font-size: 1rem;
		font-weight: 400;
		line-height: 1;
		color: #212529;
		text-align: center;
		white-space: nowrap;
		background-color: #e9ecef;
		border: 1px solid #ced4da;
		border-radius: 0.25rem;
	}
	  
		footer {    
			position: absolute;
			bottom: 0;
			width: 100%;
			background-color: #dddddd;
		}
		footer.btnBox_todayClose {
			padding: 0.5rem 0 0.7rem;
			display: flex;
		}
		form {padding-right: 2rem;}
		input#chkday {
			vertical-align: middle;
		}
		label {vertical-align: middle;}  
	  
</style>

<?php

function checkNull($strtmp) {
    if ($strtmp === null || trim($strtmp) === '') {
        return false;
    } else {
        return true;
    }
}

if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	$search=$_REQUEST["search"];	  
	
ini_set('display_errors','1');  // 화면에 warning 없애기	

$tablename = 'ceiling';
 	  
require_once(includePath('lib/mydb.php'));
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
 
  $scale = 1000;        // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 	
	 if (checkNull($search)) {
		// 검색어가 있을 때: WHERE절에 공백제외 조건 추가
		$sqlcon = "
			SELECT DISTINCT address
			FROM {$DB}.{$tablename}
			WHERE address LIKE '%{$search}%'
			  AND TRIM(address) <> ''
			ORDER BY address ASC
		";
		$sql = "
			SELECT DISTINCT address
			FROM {$DB}.{$tablename}
			WHERE address LIKE '%{$search}%'
			  AND TRIM(address) <> ''
			ORDER BY address ASC
			LIMIT {$first_num}, {$scale}
		";
	} else {
		// 검색어가 없을 때: 전체 주소 중 공백제외
		$sqlcon = "
			SELECT DISTINCT address
			FROM {$DB}.{$tablename}
			WHERE TRIM(address) <> ''
			ORDER BY address ASC
		";
		$sql = "
			SELECT DISTINCT address
			FROM {$DB}.{$tablename}
			WHERE TRIM(address) <> ''
			ORDER BY address ASC
			LIMIT {$first_num}, {$scale}
		";
	}
	
   
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
      $total_row = $temp2;     // 전체 글수	 
	  
			  
		if ($total_row === 1 && $enterpress==='true') {
			$row = $stmh->fetch(PDO::FETCH_ASSOC);
			echo "<script>
			window.onload = function() {
				maketext('" . addslashes($row['address']) . "');
			};
			</script>";
		}	    
        					 
  	  $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	  $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 

   
?>	


<form id="board_form" name="board_form" method="post" enctype="multipart/form-data"   >			 

	<input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>">             
	<input type="hidden" id="num" name="num" value=<?=$num?> > 
	<input type="hidden" id="page" name="page" value=<?=$page?> > 
	<input type="hidden" id="mode" name="mode" value=<?=$mode?> > 				
	<input type="hidden" id="tablename" name="tablename" value=<?=$tablename?> > 				
	 
<div class="container-fluid" style="width:580px;">	
				
<div class="card justify-content-center text-center mt-1" >
	<div class="card-header">
		<span class="text-center fs-5" > 주소  </span>								
	</div>
	<div class="card-body" >								
	<div class="d-flex  justify-content-center text-center align-items-center mb-2" >										
			▷ <?= $total_row ?> &nbsp; 
				<div class="inputWrap30">			
					<input type="text" id="search" name="search" value="<?=$search?>" onKeyPress="if (event.keyCode==13){ enter(); }" >
					<button class="btnClear">  </button>
				</div>							
					&nbsp;&nbsp;
			 <button class="btn btn-outline-dark btn-sm " type="button" id="searchBtn" > 검색 </button> </span> &nbsp;&nbsp;&nbsp;&nbsp;		
			<button id="closeBtn" type="button" class="btn btn-outline-dark btn-sm"> 창닫기 </button>
		</div>		
		
	<div class="table-reponsive" >	
	  <table class="table table-bordered table-hover">				 
	       <thead class="table-primary">
				 <th>번호</th>
				 <th>주소</th>
			</thead>
			<tbody>		      	 
		<?php  
		
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				$address = $row['address'];
			 ?>		
			 
             <tr> 			 
				 <td ><?= $start_num ?></td>
				 <td><a href="#"  onclick="maketext('<?=$address?>');return false;"  title="<?=$address?>" > <?=$address ?> </a></td>     
             </tr>		 
			<?php
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;  
 ?>
 
  </tbody>
 </table>
       
  <div class="row row-cols-auto mt-1 justify-content-center align-items-center">  
         <?php
            if($page!=1 && $page>$page_scale){
              $prev_page = $page - $page_scale;    
              // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
              if($prev_page <= 0) 
                        $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
					print '<button class="btn btn-outline-secondary btn-sm" type="button"  onclick="javascript:movetoPage(' . $prev_page . ');"> ◀ </button> &nbsp;' ;              
            }
            for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
              if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                print '<span class="text-secondary" >  ' . $i . '  </span>'; 
              else 
                   print '<button class="btn btn-outline-secondary btn-sm" type="button"  onclick="javascript:movetoPage(' . $i . ');"> ' . $i . '</button> &nbsp;' ;     			
            }

            if($page<$total_page){
              $next_page = $page + $page_scale;
              if($next_page > $total_page) 
                     $next_page = $total_page;
                // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
					print '<button class="btn btn-outline-secondary btn-sm" type="button"  onclick="javascript:movetoPage(' . $next_page . ');"> ▶ </button> &nbsp;' ; 
            }
            ?>              
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

// Enterkey 동작
function enter()
{
	
	$("#page").val('1');
	$("#board_form").submit();	       		
}

/* ESC 키 누를시 팝업 닫기 */
$(document).keydown(function(e){
	//keyCode 구 브라우저, which 현재 브라우저
	var code = e.keyCode || e.which;

	if (code == 27) { // 27은 ESC 키번호
		self.close();
	}
});
	
// 실행
function maketext(address) {
    // 부모 창의 updateOptions 함수를 호출하여 #address 요소의 값을 업데이트
    if (window.opener && !window.opener.closed) {
        window.opener.updateOptions("#address", address);
    }
    self.close(); // 팝업 창 닫기
}


	$("#searchBtn").on("click", function() {
		$("#board_form").submit();
	});	
	
	$("#search_directinput").on("click", function() {
		$("#custreg_search").hide();
	});	
	// 창닫기 버튼
	$("#closeBtn").on("click", function() {
		self.close();
	});	
	
// 자식창에서 돌아와서 이걸 실행한다
function reloadlist() {

		const search = $("#search").val();
		$("#board_form").submit();				

}
	
</script>
