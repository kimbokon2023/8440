<?php include $_SERVER['DOCUMENT_ROOT'] . '/session.php';   

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {          
		 sleep(1);
         header ("Location:https://8440.co.kr/login/logout.php");
         exit;
 }  
 ?>
 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

<?php  
// // ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
// header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
// header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Cache-Control: post-check=0, pre-check=0", false);
// header("Pragma: no-cache");

//header("Refresh:0");  // reload refresh   

require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();
	
// 배열로 기본정보 불러옴
 include "load_DB.php";
 
 // var_dump($basic_name_arr);
 // var_dump('<br>');
 // var_dump('<br>');
 // var_dump('<br>');
 // var_dump($totalused_arr);

 ?>

 <title> 직원 연차 관리 </title> 

<body>

<?php if( $menu!=='no')  require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>    

 <?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();

if (isset($_REQUEST["search"])) {
    $search = $_REQUEST["search"];
}

if (isset($_REQUEST["mode"])) {
    $mode = $_REQUEST["mode"];
}

if (isset($_REQUEST["list"])) {
    $list = $_REQUEST["list"];
} else {
    $list = 0;
}

if (isset($_REQUEST["page"])) {
    $page = $_REQUEST["page"];
} else {
    $page = 1;
}

isset($_REQUEST["year"]) ? $year = $_REQUEST["year"] : $year = date("Y");
$showRetired = isset($_POST['showRetired']) ? $_POST['showRetired'] : 0;

$whereCondition = " WHERE referencedate = '$year' ";
$andCondition = " AND referencedate = '$year' ";
if (!$showRetired) {
    $whereCondition .= " AND (comment IS NULL or comment ='') ";
}
else
	$whereCondition .= " AND comment = '퇴사' ";

$scale = 100;       // 한 페이지에 보여질 게시글 수
$page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
$first_num = ($page - 1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.

if ($mode == "search") {
    if (empty($search)) {
        $sql = "SELECT * FROM ".$DB.".almember ".$whereCondition." ORDER BY referencedate DESC, dateofentry ASC, num DESC LIMIT $first_num, $scale";
        $sqlcon = "SELECT * FROM ".$DB.".almember ".$whereCondition." ORDER BY referencedate DESC, dateofentry ASC, num DESC";
    } elseif (!empty($search)) {
        $sql = "SELECT * FROM ".$DB.".almember WHERE (name LIKE '%$search%') OR (part LIKE '%$search%') OR (referencedate LIKE '%$search%')";
        $sql .= " ".$andCondition." ORDER BY referencedate DESC, dateofentry ASC, num DESC LIMIT $first_num, $scale";
        $sqlcon = "SELECT * FROM ".$DB.".almember WHERE (name LIKE '%$search%') OR (part LIKE '%$search%') OR (referencedate LIKE '%$search%') ";
        $sqlcon .= " ".$andCondition." ORDER BY referencedate DESC, dateofentry ASC, num DESC";
    }
} else {
    $sql = "SELECT * FROM ".$DB.".almember ".$whereCondition." ORDER BY referencedate DESC, dateofentry ASC, num DESC LIMIT $first_num, $scale";
    $sqlcon = "SELECT * FROM ".$DB.".almember ".$whereCondition." ORDER BY referencedate DESC, dateofentry ASC, num DESC";
}
   
 try{  
	  $allstmh = $pdo->query($sqlcon);       
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);           
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp2;     // 전체 글수	  	

    // echo $total_row . '  ';
	// echo $sql;	
         					 
     $total_page = ceil($total_row / $scale);
	 $current_page = ceil($page/$page_scale);
			 
?>   
<form name="board_form" id="board_form"  method="post" action="admin.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>">    
  
<div class="container">  
	<div class="card mt-2 mb-4">  
	<div class="card-body"> 		 
	  
	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog  modal-lg modal-center" >
		
		  <!-- Modal content-->
		  <div class="modal-content modal-lg">
			<div class="modal-header">          
			  <h4 class="modal-title">알림</h4>
			</div>
			<div class="modal-body">		
			   <div class="row gx-4 gx-lg-4 align-items-center">		  
					   <br>
					   <div id="alertmsg" class="fs-3" > </div> <br>
					  <br>		  									
					</div>
				</div>		  
			<div class="modal-footer">
			  <button id="closeModalBtn" type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
			</div>
			</div>
			</div>
	</div>         
   
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 					
	<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" size="5" > 		
    <input type="hidden" id="page" name="page" value="<?=$page?>"  > 
	<input type="hidden" id="scale" name="scale" value="<?=$scale?>"  > 	
	

 <div class="d-flex mt-3 mb-3 justify-content-center align-items-center"> 	
	<span class="text-dark fs-6 me-1" > 년도별 연차 일자 입력/조회 </span>
	<span class="badge bg-secondary fs-6 me-2">  <i class="bi bi-layout-text-window"></i> 부서구분 : 제조파트, 지원파트 2개로 구분해서 사용함&nbsp;&nbsp;  </span>
	
<button type="button" id="backBtn" class="btn btn-outline-primary btn-sm me-2"  > <i class="bi bi-arrow-left"></i>  이전화면  </button> &nbsp;&nbsp;&nbsp;		 
 	
</div>
<div class="d-flex mt-3 mb-1 justify-content-center  align-items-center"> 	
    <!-- 퇴사자 체크박스 -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="showRetired" name="showRetired" value=1 onchange="filterRetired()" <?php echo isset($_POST['showRetired']) && $_POST['showRetired'] == 1 ? 'checked' : ''; ?>>
        <label class="form-check-label fs-6 me-2" for="showRetired">퇴사자 보기</label>
    </div>
    &nbsp;&nbsp;&nbsp;
			<select name="year" id="year"  class="form-select d-block w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
			   <?php		    
				$current_year = date("Y"); // 현재 년도를 얻습니다.
				$year_arr = array(); // 빈 배열을 생성합니다.

				for ($i = 0; $i < 3; $i++) {
					$year_arr[] = $current_year - $i;
				}
			   for($i=0;$i<count($year_arr);$i++) {
					 if($year==$year_arr[$i])
								print "<option selected value='" . $year_arr[$i] . "'> " . $year_arr[$i] .   "</option>";
						 else   
				   print "<option value='" . $year_arr[$i] . "'> " . $year_arr[$i] .   "</option>";
			   } 		   
					?>	  
			</select> 					
			년도 &nbsp;

     &nbsp;&nbsp;&nbsp; <i class="bi bi-caret-right"></i> 총 <?= $total_row ?>개  &nbsp;&nbsp;&nbsp; 

	   <input type="text" name="search" id="search" class="form-control mx-1" style="font-size: 0.8rem; height: 32px; width:150px;" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" autocomplete="off" placeholder="검색어"> 	   
		<button type="button" id="searchBtn" class="btn btn-dark btn-sm ms-1 me-1"  >  <i class="bi bi-search"></i> 검색 </button>	
		 <button type="button" class="btn btn-dark btn-sm ms-1 me-1" onclick="popupCenter('write_form.php', '신규', 600, 700);return false;" >  <i class="bi bi-pencil"></i>  신규 </button>
		 <button type="button" class="btn btn-dark btn-sm mx-1" id="csvDownload" >  <i class="bi bi-floppy-fill"></i> CSV  </button> 
		 <button  type="button" id="massBtn" class="btn btn-sm btn-primary mx-1"> <i class="bi bi-cloud-arrow-up"></i> 대량등록</button>	
			
		
	
</div>  
<div class="d-flex mt-3 mb-1 justify-content-center  align-items-center"> 	
    <table class="table table-hover" id="csvTable">
      <thead class="table-primary">
        <tr>
          <th class="text-center">번호</th>
          <th class="text-center">구분</th>
          <th class="text-center">직원이름</th>
          <th class="text-center">부서</th>
          <th class="text-center">입사일</th>
          <th class="text-center">해당연도</th>
          <th class="text-center">근속년</th>
          <th class="text-center">근속월</th>
          <th class="text-center">연차 발생일수</th>
          <th class="text-center">연차 사용일수</th>
          <th class="text-center">연차 잔여일수</th>
        </tr>
      </thead>
      <tbody>        
       
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	           include "rowDB.php";  


        // var_dump($totalused_arr);
		$totalusedday = 0;
		$totalremainday = $availableday;		
		
		 for($i=0;$i<count($totalname_arr);$i++)
		 {			 

             // 해당년도가 같고 이름이 같으면 계산			 
			 if(trim($name) == trim($totalname_arr[$i]) && $referencedate == $totalusedYear_arr[$i] )
			 {				 
				$totalusedday = $totalused_arr[$i];
				$totalremainday = floatval($availableday) - floatval($totalusedday) ;	
				// print 	$basic_name_arr[$i];	
				// print 	$totalname_arr[$i];				
			 }
			 
			// print '<tr>';
			// print '<td> ' . $totalused_arr[$i] . '</td>';
			// print '</tr>';

		 }
		 			 
          
 
				?>		
				
		<tr onclick="popupCenter('write_form.php?num=<?=$num?>', '데이터등록', 600, 700)">  
		    <td class="text-center"><?=$start_num?>	</td>
            <td class="text-center"><?=$comment?>	</td>
            <td class="text-center"><?=$name?>	    </td>
            <td class="text-center"><?=$part?>   	</td>
            <td class="text-center"><?=$dateofentry?>	</td>
            <td class="text-center"><?=$referencedate?>	</td>
			
			<?php 
						
			// DateTime 객체로 변환
			$entryDate = new DateTime($dateofentry);
			$referenceDate = new DateTime($referencedate);

			// 두 날짜 간의 차이 계산
			$interval = $entryDate->diff($referenceDate);

			// 총 년수 계산
			$years = $interval->y;

			// 총 월수 계산
			$months = $interval->m;

			// 근속년수 계산 (년 + (월 / 12)), 소수점 첫째 자리까지 반올림
			$continueYear = round($years + ($months / 12), 1);

			// 단순 월 계산
			$continueMonth = intval($years)*12 + $interval->m;
			?>
			
            <td class="text-center"><?=$continueYear?>	</td>
            <td class="text-center"><?=$continueMonth?>	</td>			
            <td class="text-center text-primary"><b><?=$availableday?></b>	</td>            			
            <td class="text-center text-success"><b><?=$totalusedday?></b>	</td>            			
            <td class="text-center text-danger"><b> <?=$totalremainday?></b>	</td>            			         
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
  
 
  <div class="row row-cols-auto mt-4 justify-content-center align-items-center"> 
 <?php
	if($page!=1 && $page>$page_scale){
              $prev_page = $page - $page_scale;    
              // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
              if($prev_page <= 0) 
              $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
		      print '<button class="btn btn-outline-secondary btn-sm" type="button" id=previousListBtn  onclick="javascript:movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;' ;              
            }
            for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
              if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                print '<span class="text-secondary" >  ' . $i . '  </span>'; 
              else 
                   print '<button class="btn btn-outline-secondary btn-sm" type="button" id=moveListBtn onclick="javascript:movetoPage(' . $i . ')">' . $i . '</button> &nbsp;' ;     			
            }

            if($page<$total_page){
              $next_page = $page + $page_scale;
              if($next_page > $total_page) 
                     $next_page = $total_page;
                // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
				  print '<button class="btn btn-outline-secondary btn-sm" type="button" id=nextListBtn onclick="javascript:movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;' ; 
            }
            ?>         
   </div>


   </div>	 
   </div>	 
   </div>	 
  
  
  	</form>	   	
    
<br>
<br>
<div class="container">
<? include '../footer_sub.php'; ?>
</div>
  
</body>
</html>

<script>
$(document).ready(function(){	

	$('select[name="year"]').change(function(){
	   var val = $('input[name="year"]:checked').val();	   
	   document.getElementById('board_form').submit(); 
	});	

	$("#massBtn").click(function(){ 
		popupCenter('write_form_init.php', '연초 대량등록', 420, 460);	  
	});


$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});


$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		
$("#backBtn").click(function(){  location.href='/annualleave/index.php';   });		
	
$('a').children().css('textDecoration','none');  // a tag 전체 밑줄없앰.	
$('a').parent().css('textDecoration','none');

});


function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}


function movetoPage(page){ 	  
	  $("#page").val(page); 
      // var echo="<?php echo $partOpt; ?>"; 
      // var searchOpt="<?php echo $searchOpt; ?>"; 
      // var search="<?php echo $search; ?>"; 

     // $("#partOpt").val(partOpt);
     // $("#searchOpt").val(searchOpt);
     // $("#search").val(search);
	 $("#board_form").submit();  
}	
	
document.getElementById("csvDownload").addEventListener("click", function() {
  const table = document.getElementById("csvTable");
  const theadRow = table.querySelector("thead tr");
  const rows = table.querySelectorAll("tbody tr");
  
  const csvRows = [];
  
  // Include the header row
  const headerData = [];
  theadRow.querySelectorAll("th").forEach(function(cell) {
    headerData.push(cell.textContent.trim());
  });
  csvRows.push(headerData.join(","));

  // Include the data rows
  rows.forEach(function(row) {
    const rowData = [];
    row.querySelectorAll("td").forEach(function(cell) {
      let cellValue = cell.textContent.trim();

      // 숫자인지 확인 (정수 또는 소수)
      if (/^\d+(\.\d+)?$/.test(cellValue)) {
        // 숫자인 경우 좌우 공백 제거 후 숫자로 변환하여 다시 문자열로
        cellValue = parseFloat(cellValue).toString();
      }

      rowData.push(cellValue);
    });
    csvRows.push(rowData.join(","));
  });

  const csvContent = csvRows.join("\n");
   // 한글깨짐문제 '\ufeff' + data 이것 참조
  const blob = new Blob(['\ufeff' + csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.setAttribute("download", "직원연차정보.csv");
  document.body.appendChild(link);
  link.click();
});

function filterRetired() {
    // 퇴사자 체크박스 상태 확인
    // const showRetired = document.getElementById('showRetired').checked;	
	document.getElementById('board_form').submit(); 

}
</script>