<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
$level= $_SESSION["level"];
$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];	
$WebSite = "http://8440.co.kr/";	
 
$menu=$_REQUEST["menu"]; 
   
$title_message = '원자재 종류';   
   
?>

<?php 

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }    
   
	include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
   
 ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/modal.php'; ?>

<title> <?=$title_message?>  </title>

</head>

<body>

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
	
if(isset($_REQUEST["item"]))   //목록표에 제목,이름 등 나오는 부분
	$item=$_REQUEST["item"];	  

	
ini_set('display_errors','0');  // 화면에 warning 없애기	
 
	  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
	$a="  order by item asc, num desc ";  
	$b="  order by item asc, num desc ";
if(checkNull($search))
{
	$sql ="select * from mirae8440.steelitem where item like '%$search%'  "  . $a;		
}
	else
		{
			$sql ="select * from mirae8440.steelitem  "  . $a;				
		}

// print 'mode : ' . $mode;   
// print 'search : ' . $search;   
// print $sql;   
   
	 try{  	   
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();
	         
?>	


<body>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data"   >			 

	<input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>">             
	<input type="hidden" id="num" name="num" value=<?=$num?> > 
	<input type="hidden" id="page" name="page" value=<?=$page?> > 
	<input type="hidden" id="mode" name="mode" value=<?=$mode?> > 				
	<input type="hidden" id="item" name="item" value=<?=$item?> > 				

<title> <?=$title_message?> </title>

<div class="container">	
				
<div class="card" >
<div class="card-body" >	
		<h4 class="text-center" >  <?=$title_message?>   </h4>								
	 				
		<div class="d-flex p-2 mb-2 mt-4 justify-content-center align-items-center" >	
			▷ <?= $total_row ?> &nbsp; 
				<div class="inputWrap30">			
					<input type="text" id="search" name="search" class="form-control me-1" style="width:150px;" value="<?=$search?>" onKeyPress="if (event.keyCode==13){ enter(); }" >
					<button class="btnClear">  </button>
				</div>												
			 <button class="btn btn-dark btn-sm me-1" type="button" id="searchBtn" > <i class="bi bi-search"></i>  </button>
		 &nbsp;
			   <button id="newBtn" type="button" class="btn btn-dark btn-sm me-4"> <i class="bi bi-pencil-fill"></i> 신규  </button>			 
			   <button class="btn btn-secondary btn-sm me-1" onclick="self.close();" >  <i class="bi bi-x-lg"></i> 창닫기 </button>	
		</div>
		
<div class="card mb-2">
<div class="card-body">	  	  
   <div class="table-responsive"> 	
   <table class="table table-hover" id="myTable">
    <thead class="table-primary">
      <tr>
        <th class="text-center" scope="col" style="width:15%;">번호</th>
        <th class="text-center" scope="col"> 원자재명 </th>
        <th class="text-center" scope="col"> 삭제 </th>
      </tr>
    </thead>
	
    <tbody>					      	 
		<?php  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				include '_row.php';
			 ?>		
			<tr onclick="maketext('<?=$item?>');return false;">
				<td class="text-center"><?= $start_num ?></td>
				<td class="text-start"><?=$item?></td>
				<td class="text-center">
					<button type="button" class="btn btn-danger btn-sm" onclick="event.stopPropagation(); delFn('<?=$num?>')"><i class="bi bi-trash"></i></button>
				</td>
			</tr>
			<?php
			$start_num--;
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
     </div>	  
     </div>	  
</form>
</body>
</html>

<script>

var dataTable; // DataTables 인스턴스 전역 변수
var standard_materialpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": false,
        "ordering": true,
        "searching": false,
        "pageLength": 50,
        "lengthMenu": [50, 100, 200, 500, 1000],
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('standard_materialpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var standard_materialpageNumber = dataTable.page.info().page + 1;
        setCookie('standard_materialpageNumber', standard_materialpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('standard_materialpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('standard_materialpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}



$(document).ready(function(){	

 const startstr = '<?php echo $param; ?>';
 
 if(startstr == '')
	  // 전체 화면에 출력
      {
		  $("#param").val(''); 
	      displaytext();	  
	  }
		 
	
});

// Enterkey 동작
function enter()
{
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
function maketext(str)   // 클릭시 화면에 정보 보여줌, 코드명, 거래처
{					                                           
     
	var item = $("#item").val();
	
	$("#" + item, opener.document).val(str); 	
	  
	// 자식 창에서 새로운 spec_arr 값 추가 예시
	var newValue = str ; // 실제로 추가하려는 값을 사용하세요

	if (window.opener && typeof window.opener.updateOptions === "function") {
		window.opener.updateOptions(item, newValue);
	}
	  self.close();		  
}	
	
// 규격전체 화면에 찍어주기
	function displaytext()
	{					                                           		 
		  $("#result").val($("#text1").val() + $("#text2").val() + $("#text3").val());
		  
	}	

	$("#searchBtn").on("click", function() {
		$("#board_form").submit();
	});	

	// 신규 버튼
	$("#newBtn").on("click", function() {
		  popupCenter('./write.php', '등록', 580, 450);	
	});	
	// 창닫기 버튼
	$("#closeBtn").on("click", function() {
		self.close();
	});	
	
	
function  delFn(delChoice) {
	console.log(delChoice);
	$("#SelectWork").val("delete");
	$("#num").val(delChoice);

		// DATA 삭제버튼 클릭시
			Swal.fire({ 
				   title: '해당 DATA 삭제', 
				   text: " DATA 삭제는 신중하셔야 합니다. '\n 정말 삭제 하시겠습니까?", 
				   icon: 'warning', 
				   showCancelButton: true, 
				   confirmButtonColor: '#3085d6', 
				   cancelButtonColor: '#d33', 
				   confirmButtonText: '삭제', 
				   cancelButtonText: '취소' })
				   .then((result) => { if (result.isConfirmed) { 
													
							$.ajax({
								url: "process.php",
								type: "post",		
								data: $("#board_form").serialize(),								
								success : function( data ){			
																			
										 Toastify({
												text: "파일 삭제 완료!",
												duration: 3000,
												close:true,
												gravity:"top",
												position: "center",
												backgroundColor: "#4fbe87",
											}).showToast();									
									  setTimeout(function() {
												location.reload();	
										   }, 1500);															
															
																							 
													
									},
									error : function( jqxhr , status , error ){
										console.log( jqxhr , status , error );
								} 			      		
							   });												
				   } });	
}

	
// 자식창에서 돌아와서 이걸 실행한다
function reloadlist() {

		const search = $("#search").val();
		$("#board_form").submit();				

}
	
</script>
