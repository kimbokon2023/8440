<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함 
?>
  
<?php 
include getDocumentRoot() . '/load_header.php';
 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }    
?>  

<?php
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
isset($_REQUEST["year"])  ? $year=$_REQUEST["year"] :   $year = date("Y");
isset($_REQUEST["month"])  ? $month=$_REQUEST["month"] :  $month =date("m");
 
 if($user_name=='소현철' || $user_name=='김보곤' || $user_name=='최장중' || $user_name=='이경묵'  || $user_name=='소민지' )
	  $admin = 1;
 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
// 배열로 기본정보 불러옴
include "load_DB.php";

$name_arr = array_unique($basic_name_arr);

// 현재 년도와 월을 설정함
// 현재 날짜를 가져옵니다.
$today = date("Y-m-d");

 // 제조파트에 해당되는 직원들의 근무를 파악하는 루틴
// 배열에 이름, 일자, 내용을 기록한다.
// 해당요일과 맞으면 출력해 준다.

  $view_name = array();
  $view_date = array();
  $view_item = array();  
  $view_labor_name = array();  
  $view_contents = array();    
  $view_sum_after =0 ; // 점심식사
  $view_sum_evening =0 ; // 석식 합계
  
	try{
	  $sql = "select * from mirae8440.daylaborer ";
	  $stmh = $pdo->prepare($sql); 
	  $stmh->bindValue(1,$num,PDO::PARAM_STR); 
	  $stmh->execute();
	  $count = $stmh->rowCount();            
	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		 
	     include 'rowDBask.php';	 
		 
	  array_push($view_name, $name);
	  array_push($view_date, $askdatefrom); // 작업일 기준
	  array_push($view_contents, $content);  // 형태 (중식/석식 구분)
	  array_push($view_item, $item);  // 인원수 둔갑
	  array_push($view_labor_name, $labor_name);  // 비고기록	  
	    }
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
	 }

// var_dump($view_labor_name);

 ?>

<title> 일용직 근태관리 </title>
	
<body>                
 <?php if( $menu!=='no')  require_once(includePath('myheader.php')); ?>    
 
	<form name="board_form" id="board_form"  method="post" action="index.php">  

	<div class="container">

	<div class="d-flex mt-3 mb-3 justify-content-center align-items-center" >
		<h5 class="text-dark" > (신청) -> 공장장  </h5>    &nbsp;&nbsp;&nbsp; 		
		<h5 class="text-primary" > (결재관련) -> 경리부 </h5>    
		<button type="button" class="btn btn-dark btn-sm mx-2" onclick='location.reload()'>  <i class="bi bi-arrow-clockwise"></i> </button>		
	</div>	    
	
    <div class="row">  
	<?php
	 if($chkMobile==true)
		echo '<div class="col-sm-12"> '	;
		 else
		  echo '<div class="col-sm-5"> 	 ';		 
	?>
  
  <div class="card card-body">
  
	<div class="d-flex mt-3 mb-3 justify-content-center align-items-center" >
		<h5> (일용직 근태) 년월 설정 </h5>
		<select name="year" id="year" class="form-select form-select-sm w-auto text-center mx-2">
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
		<select name="month" id="month"  class="form-select form-select-sm w-auto text-center">
		   <?php		    
			$month_arr = array("1","2","3","4","5","6","7","8","9","10","11","12"); 					
		   for($i=0;$i<count($month_arr);$i++) {
				 if($month==$month_arr[$i])
							print "<option selected value='" . $month_arr[$i] . "'> " . $month_arr[$i] .   "</option>";
					 else   
			   print "<option value='" . $month_arr[$i] . "'> " . $month_arr[$i] .   "</option>";
		   } 		   
				?>	  
		</select> 	
	</div> 
	<div class="d-flex mt-3 mb-3 justify-content-center " >
		
	</div>
	<div class="d-flex mt-3 mb-3 justify-content-center " >
		
	<table class="table table-striped table-bordered">
	  <thead class="table-primary">
		<tr class="text-center">
		  <th scope="col">구분</th>
		  <th>근무인원</th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		// 현재 날짜를 가져옵니다.
		$today = date("Y-m-d");
		// 요일을 출력합니다.
		$days = array("일", "월", "화", "수", "목", "금", "토");

		// 해당 월의 마지막 날짜를 구합니다.
		$num_days = date("t", mktime(0, 0, 0, $month, 1, $year));

		$exceptNum = 0;

		for ($i = 0; $i < $num_days + 2; $i++) {
		  print '<tr class="text-center">';
		  if ($i === 0) {
			print '<td class="text-primary">인원 합계</td>';
			print '<td></td>';
		  } else if ($i === 1) {
			print '<td></td>';
		  } else {
			$thisday = $days[date('w', strtotime($year . "-" . $month . "-" . ($i - 1)))]; // 화면에 보여주기 위한 날짜
			$pointday = date("Y-m-d", strtotime($year . "-" . $month . "-" . ($i - 1))); // 해당일을 뽑아내서 비교하기 위한 변수

			if ($thisday === '토' || $thisday === '일') {
			  print '<td class="text-danger">' . ($i - 1) . '(' . $thisday . ')' . '</td>';
			} else {
			  print '<td>' . ($i - 1) . '(' . $thisday . ')' . '</td>';
			}

			$printstr = '<td> </td>';
			$inner = 0;
			for ($kk = 0; $kk < count($view_date); $kk++) {
			  if ($view_date[$kk] === $pointday && $view_contents[$kk] == '일당') {
				$inner += (float)$view_item[$kk];
				$view_sum_after += (float)$view_item[$kk];
				$printstr = '<td>' . $inner . '</td>';
			  }
			}
			print $printstr;
		  }
		  print '</tr>';
		}
		?>
	  </tbody>
	</table>


		</div>
	</div>
</div>
 <?php
 if($chkMobile==true)
	echo '<div class="col-sm-12"> '	;
 else
  echo '<div class="col-sm-7"> 	 ';
 
?>

  <div class="card">
	<div class="d-flex mt-5 mb-3 justify-content-center align-items-center" >
		
 <?php
 

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
	 
  if(isset($_REQUEST["mode"]))   //목록표에 제목,이름 등 나오는 부분
	 $mode=$_REQUEST["mode"];
	 
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;	  
	  
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
 
  $scale = 35;       // 한 페이지에 보여질 게시글 수
  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.	  
	  
if($mode=="search" || $mode==""){
	  if($search==""){		  
				$sql="select * from mirae8440.daylaborer order by   askdatefrom desc, name asc  limit $first_num, $scale " ;
				$sqlcon="select * from mirae8440.daylaborer order by   askdatefrom desc  " ;
		              
			       }
             elseif($search!="") {				  
										  $sql ="select * from mirae8440.daylaborer where (name like '%$search%')  or (content like '%$search%') or (labor_name like '%$search%')  ";
										  $sql .=" order by   askdatefrom desc  limit $first_num, $scale ";
										  $sqlcon ="select * from mirae8440.daylaborer where (name like '%$search%')   or (content like '%$search%') or (labor_name like '%$search%') ";
										  $sqlcon .=" order by   askdatefrom desc ";
								
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
	
	▷ <?= $total_row ?>  &nbsp;&nbsp;
	 <button type="button" class="btn btn-dark btn-sm me-1" onclick="popupCenter('write_form_ask.php', '등록/수정/삭제', 450, 550);return false;" > <i class="bi bi-pencil"></i>  신규 </button> 		   
	<input type="text" name="search" id="search" class="form-control me-1" style="width:150px;" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어"> 	   
	<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-1"  >  <i class="bi bi-search"></i> 검색  </button>	

     </div>
	<div class="table table-responsive" > 
	<table class="table table-striped  table-bordered table-hover">
     <thead class="table-primary">
        <tr class="text-center">
            <th class=" text-center">번호</th>
            <th class=" text-center">근무일</th>
            <th class=" text-center">종류</th>            
            <th class=" text-center"> 성함 </th>
            <th class=" text-center"> 요청/요청확인/지급완료 </th>
            <th class=" text-center"> 비고(추가근무 등) </th>
            
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
			   
				?>
		<tr onclick="popupCenter('write_form_ask.php?num=<?=$num?>', '일용직 신청', 450, 550);return false;">				   

			<td class="text-center"><?=$start_num?>				</td>			 
            <td class="text-center"><?=iconv_substr($askdatefrom,5,5,"utf-8")?>	</td>
            <td class="text-center"><?=$content?> </td>            			            
            <td class="text-center"><?=$labor_name?>	</td>            			
            <td class="text-center <?= $state == '요청' ? 'text-primary' : ($state == '지급완료' ? 'text-danger' : '') ?>"><?= $state ?></td>          			
            <td class="text-center "><?= $part ?></td>          			
            
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
	<div class="d-flex mt-2 mb-2 justify-content-center " >	         
	
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
			

</form>

 </div>   
    

   
  
<script>


$(document).ready(function(){	


// 합계치가 나오면 첫번째줄의 요소를 바꿔준다.
view_sum_after = '<?php echo $view_sum_after; ?>' ;	 
view_sum_evening = '<?php echo $view_sum_evening; ?>' ;	 

 console.log(view_sum_after);
 console.log(view_sum_evening);

// 연장근로 상단에 표시해주기
$("td:eq(" + (1) + ")").text(view_sum_after);
$("td:eq(" + (2) + ")").text(view_sum_evening);


$('select[name="year"]').change(function(){
   var val = $('input[name="year"]:checked').val();	   
   document.getElementById('board_form').submit(); 
});	

$('select[name="month"]').change(function(){
   var val = $('input[name="month"]:checked').val();	   
   document.getElementById('board_form').submit(); 
});	


$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});


$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });		
	
$('a').children().css('textDecoration','none');  // a tag 전체 밑줄없앰.	
$('a').parent().css('textDecoration','none');

});

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}


setTimeout(function() {  

 $("#exceptNum").text('<?php echo $exceptNum; ?>');
 
}, 1500);


</script>
  </body>
  </html>