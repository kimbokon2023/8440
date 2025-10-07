<?php include $_SERVER['DOCUMENT_ROOT'] . '/session.php';    
if(!isset($_SESSION["name"]) ) {	          
		 $_SESSION["url"]='https://8440.co.kr/annualleave/index.php?user_name=' . $user_name; 	
		 sleep(1);
         header ("Location:https://8440.co.kr/login/logout.php");
         exit;
} 
$title_message = '직원 연차'; 

?> 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
<title> <?=$title_message?> </title>
</head>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>   

<?php  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();		
// 배열로 기본정보 불러옴
include $_SERVER['DOCUMENT_ROOT'] . '/annualleave/load_DB.php';

  if($user_name=='소현철' || $user_name=='김보곤' || $user_name=='최장중' || $user_name=='이경묵' || $user_name=='소민지')
	  $admin = 1;
  
// var_dump($level);
?>

<form name="board_form" id="board_form"  method="post" action="index.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>">  
	
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>"  > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>"  > 
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>"  > 					
	<input type="hidden" id="username" name="username" value="<?=$user_name?>"  > 		

 <?php if($chkMobile==false) { ?>
	<div class="container">     
 <?php } else { ?>
 	<div class="container-fluid">     
	<?php } ?>	 
	
	<div class="card"> 						
	<div class="card-body"> 							
		<div class="d-flex justify-content-center align-items-center mt-3 mb-2">
			<span class=" fs-5"> <?=$title_message?> </span>
			<button type="button" class="btn btn-dark btn-sm mx-2" onclick='location.reload()'>  <i class="bi bi-arrow-clockwise"></i> </button>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<? if ($admin == 1) { ?>
				  <!-- <button type="button" id="openAlmemberBtn" class="btn btn-success btn-sm me-2">
					<i class="bi bi-pencil-square"></i> 직원 정보 -->
					<button type="button" class="btn btn-primary btn-sm" onclick="location.href='./admin.php'">
						<i class="bi bi-pencil-square"></i>
						관리자모드
					</button>
				<? } ?>
		</div>				
		<div class="d-flex justify-content-center align-items-center mt-3 mb-1">
			<h6 class="text-center mb-1"> 
				<div class="d-flex justify-content-center align-items-center mb-1">
					<select id="yearSelect" class="form-select form-select-sm d-inline w-auto">
						<?php
						$currentYear = date("Y");
						for ($year = $currentYear; $year >= $currentYear - 3; $year--) {
							echo "<option value='{$year}'" . ($year == $currentYear ? " selected" : "") . ">{$year}</option>";
						}
						?>
					</select>							
						년도 &nbsp; <?=$user_name?> 님							
				</div>
				<div class="d-flex justify-content-center align-items-center mb-1">				
					<table class="table table-bordered text-center">
						<tbody>
							<tr>
								<td class="table-success fs-6"><span class="badge bg-success">발생일수</span></td>
								<td class="table-primary fs-6"><span class="badge bg-primary">사용일수</span></td>
								<td class="table-secondary fs-6"><span class="badge bg-secondary">잔여일수</span></td>
							</tr>
							<tr>
								<td class="text-success fs-6" id="totalDays"><?=$total?></td>
								<td class="text-primary fs-6" id="usedDays"><?=$thisyeartotalusedday?></td>
								<td class="text-dark fs-6" id="remainingDays"><?=$thisyeartotalremainday?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</h6>
		</div>
	</div>
	<div class="d-flex justify-content-center mt-1 mb-1 " >
		<span class="badge bg-secondary fs-6 me-4"> 결재 진행중에는 수정, 삭제가 불가합니다. </span> <span class="text-secondary fs-6"> 진행순서 : 결재상신 -> 1차결재 -> 처리완료  </span>  	
	</div>    
<?php
 
$tablename = "eworks";

$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : null;
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : null;
$list = isset($_REQUEST["list"]) ? $_REQUEST["list"] : 0;
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

  $scale = 50;       // 한 페이지에 보여질 게시글 수
  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.	  

$AndisDeleted = " AND is_deleted IS NULL "  ;
$WhereisDeleted = " where is_deleted IS NULL "  ;

if($mode=="search" || $mode==""){
	  if($search==""){
		  if($admin==1) {
				$sql="select * from " . $DB . "." . $tablename . $WhereisDeleted . " order by   al_askdatefrom desc, registdate desc  limit $first_num, $scale " ;
				$sqlcon="select * from " . $DB . "." . $tablename . $WhereisDeleted . "  order by   al_askdatefrom desc, registdate desc  " ;
		                }
						else {
						$sql="select * from " . $DB . "." . $tablename . "  where author like '%$user_name%' " . $AndisDeleted  . " order by   al_askdatefrom desc, registdate desc  limit $first_num, $scale " ;
						$sqlcon="select * from " . $DB . "." . $tablename . "   where author like '%$user_name%'  " . $AndisDeleted  . "order by   al_askdatefrom desc, registdate desc  " ;
		                }
			       }
             elseif($search!="") {
				  if($admin==1) {
										  $sql ="select * from " . $DB . "." . $tablename . "  where (author like '%$search%')  " . $AndisDeleted ;
										  $sql .=" order by   al_askdatefrom desc, registdate desc  limit $first_num, $scale ";
										  $sqlcon ="select * from " . $DB . "." . $tablename . "  where (author like '%$search%') " . $AndisDeleted ;
										  $sqlcon .=" order by   al_askdatefrom desc, registdate desc ";
								}
								else {
										  $sql ="select * from " . $DB . "." . $tablename . "  where (author = '$user_name' ) and (author like '%$search%')   " . $AndisDeleted ;
										  $sql .=" order by   al_askdatefrom desc, registdate desc  limit $first_num, $scale ";
										  $sqlcon ="select * from " . $DB . "." . $tablename . "  where (author = '$user_name' ) and (author like '%$search%')  " . $AndisDeleted ;
										  $sqlcon .=" order by   al_askdatefrom desc, registdate desc ";
								}
										 
								}				
	      	 }     
			 

 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
	   include "rowDBask.php";
			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

try{   
	$allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
	$temp2=$allstmh->rowCount();  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$temp1=$stmh->rowCount();
	  
	$total_row = $temp2;     // 전체 글수	
	
	$total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	$current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			   										 
	
?>    		
<div class="row">   
	<div class="col-sm-9">   
	<div class="d-flex justify-content-end align-items-center mt-2 mb-2">   	 	
     &nbsp;&nbsp;&nbsp; ▷ <?= $total_row ?>  &nbsp;&nbsp;&nbsp;	 
	<input type="text" name="search" id="search"  class="form-control me-1" style="width:180px;"  value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off" placeholder="검색어"> 	   
	<button type="button" id="searchBtn" class="btn btn-dark btn-sm ms-1 me-1">  <i class="bi bi-search"></i> 검색  </button>	
	<button type="button" id="writeBtn" class="btn btn-dark btn-sm  ms-1 me-1"> <i class="bi bi-pencil-square"></i> 신청 </button> 
	<button  type="button" id="massBtn" class="btn btn-sm btn-primary"> <i class="bi bi-cloud-arrow-up"></i> 대량등록</button>	
	</div>
	</div>
	<div class="col-sm-3">   
	<div class="d-flex justify-content-end align-items-center mt-2 mb-2">   	
		<?php if($level < 4) { ?>
			<button type="button" class="btn btn-dark  btn-sm ms-3 me-1" onclick="popupCenter('batchDB.php','연차현황',1400,950);"> <i class="bi bi-grid-3x3"></i> 연차 현황 </button>    &nbsp;
		<?php } ?>	
	</div>
	</div>
</div>
   	
	<div class="d-flex justify-content-center" >  
      <table class="table table-bordered table-hover">        
	      <thead class="table-primary" >
		     <tr>
				<th class="text-center">번호</th>
				<th class="text-center">접수일</th>
				<th class="text-center">시작일</th>
				<th class="text-center">종료일</th>
				<th class="text-center">구분</th>
				<th class="text-center">사용일수</th>
				<th class="text-center">성명</th>
				<th class="text-center">사유</th>
				<th class="text-center">결재상태</th>
			</tr>
		 </thead>
	 <tbody>
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	           include "rowDBask.php";    
			   
			   switch($status) {
				   
				   case 'send':
				      $statusstr = '결재상신';
					  break;
				   case 'ing':
				      $statusstr = '결재중';
					  break;
				   case 'end':
				      $statusstr = '결재완료';
					  break;
				   default:
					  $statusstr = '';
					  break;
			   }
			   
				?>
			<tr onclick="view('<?=$num?>');">        
				<td class="text-center"><?=$start_num?></td>
				<td class="text-center"><?= substr($registdate, 0, 10) ?></td>
				<td class="text-center"><?=$al_askdatefrom?></td>
				<td class="text-center"><?=$al_askdateto?></td>                        
				<td class="text-center"><?=$al_item?></td>                        
				<td class="text-center"><?=$al_usedday?></td>                        
				<td class="text-center"><?=$author?></td>                        
				<td class="text-center"><?=$al_content?></td>                        
				<td class="text-center"><?=$statusstr?></td>                        
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
	  </div>
	<div class="d-flex justify-content-center mt-5 mb-5" >  	   
   <div class="input-group p-1 mb-2 mt-2 justify-content-center">	  
	
 <?php
    	
      if($page!=1 && $page>$page_scale)
      {
        $prev_page = $page - $page_scale;    
        // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
        if($prev_page <= 0) 
            $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
        print "<a href=index.php?page=$prev_page&mode=search&search=$search>◀ </a>";
      }
    for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) 
      {        // [1][2][3] 페이지 번호 목록 출력
        if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
           print "<font color=red><b>[$i]</b></font>"; 
        else 
           print "<a href=index.php?page=$i&mode=search&search=$search>[$i]</a>";
  }
      if($page<$total_page)
      {
        $next_page = $page + $page_scale;
        if($next_page > $total_page) 
            $next_page = $total_page;
        // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
        print "<a href=index.php?page=$next_page&mode=search&search=$search> ▶</a><p>";
      }
 ?>			
        </div>		     

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

function view(num){
	// 결재상황을 표로 정리해서 보여준다.
	// 개선된 화면 구성 작성
	popupCenter('write_form_ask.php?num=' + num + '&viewoption=1', '데이터등록', 500, 800);
}

$(document).ready(function(){	

	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});

	$("#writeBtn").click(function(){ 
		popupCenter('write_form_ask.php', '등록/수정/삭제', 420, 720);	  
	});
	$("#massBtn").click(function(){ 
		popupCenter('write_form_mass.php', '대량등록', 420, 820);	  
	});

	$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		

});

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}

$(document).ready(function () {
    $('#yearSelect').change(function () {
        const selectedYear = $(this).val();
        $.ajax({
            url: 'update_annual_leave.php', // 데이터 업데이트를 처리할 PHP 파일
            method: 'POST',
            data: { year: selectedYear, user_name: "<?=$user_name?>" },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#totalDays').text(response.total);
                    $('#usedDays').text(response.usedDays);
                    $('#remainingDays').text(response.remainingDays);
                } else {
                    alert('데이터를 업데이트하는 동안 오류가 발생했습니다.');
                }
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);				
                alert('서버 요청 중 오류가 발생했습니다.');
            }
        });
    });
	
    // 페이지 로드 시 자동 트리거
    $('#yearSelect').trigger('change');	
});




$(document).ready(function(){    
   // 방문기록 남김
   var title = '<?php echo $title_message; ?>';
   // title = '품질방침/품질목표';
   // title = '절곡 ' + title ;
   saveMenuLog(title);
});	


</script>
