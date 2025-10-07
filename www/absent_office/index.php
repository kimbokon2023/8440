<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함

?>
  
<?php include getDocumentRoot() . '/load_header.php';
if(!isset($_SESSION["level"]) ) {	          
		 $_SESSION["url"]='https://8440.co.kr/absent_office/index.php?user_name=' . $user_name; 	
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
}   
 ?>

<title> 사무실 근태 </title>	
<body>                
<?php if( $menu!=='no')  require_once(includePath('myheader.php')); ?>    

<?php
if($user_name=='소현철' || $user_name=='김보곤' || $user_name=='최장중' || $user_name=='이경묵' || $user_name=='소민지' )
	  $admin = 1;

isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
isset($_REQUEST["year"])  ? $year=$_REQUEST["year"] :   $year = date("Y");
isset($_REQUEST["month"])  ? $month=$_REQUEST["month"] :  $month =date("m");
	  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

// 배열로 기본정보 불러옴
include "load_DB.php";

$name_arr = array_unique($basic_name_arr);

// var_dump($name_arr);
// 현재 년도와 월을 설정함
// 현재 날짜를 가져옵니다.
$today = date("Y-m-d");

 // 제조파트에 해당되는 직원들의 근무를 파악하는 루틴
// 배열에 이름, 일자, 내용을 기록한다.
// 해당요일과 맞으면 출력해 준다.

  $view_name = array();
  $view_date = array();
  $view_item = array();  
  $view_contents = array();    
  $view_sum = array(); // 연장근로
  $sum_holidaywork = array(); // 특근 합계
	try{
	  $sql = "select * from mirae8440.absent_office ";
	  $stmh = $pdo->prepare($sql); 
	  $stmh->bindValue(1,$num,PDO::PARAM_STR); 
	  $stmh->execute();
	  $count = $stmh->rowCount();            
	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		 
	     include 'rowDBask.php';	  				 
		 
	  array_push($view_name, $name);
	  array_push($view_date, $askdatefrom); // 작업일 기준
	  array_push($view_contents, $content);  // 형태 (연장근로, 특근)	  
	  array_push($view_item, $item);  // 작업시간
	    }
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
	 }
?>



<form name="board_form" id="board_form"  method="post" action="index.php">  

 	<div class="container-fluid">     

	<div class="d-flex mt-3 mb-3 justify-content-center align-items-center " >
		<h5 > 사무실 근태관리 (개별입력) </h5>
		<button type="button" class="btn btn-dark btn-sm mx-2" onclick='location.reload()'>  <i class="bi bi-arrow-clockwise"></i> </button>
	</div>	    	  
  
<div class="row">  
<?php
if($chkMobile=== true )
echo '<div class="col-sm-12"> '	;
 else
  echo '<div class="col-sm-6"> 	 ';
 
?> 
  <div class="card card-body"> 
	<div class="d-flex mt-3 mb-3 justify-content-center align-items-center" >
		<h5> 년월 설정 </h5>
		<select name="year" id="year"   class="form-select form-select-sm w-auto text-center mx-2">
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
	<div class="table-responsive mt-3 mb-3 justify-content-center " >
	
	<table class="table table-striped  table-bordered">
  <thead class="table-primary" >
    <tr class="text-center" >
	 <?php 
	 
// print '<br>';
// print '$name_arr) ';
// var_dump($name_arr);
// print '<br>';
// print '$view_name) ';
// var_dump($view_name);
// print '<br>';
// print '$view_date) ';
// var_dump($view_date);
// print '<br>';
// print '$view_item) ';
// var_dump($view_item);
// print '<br>';
// print '$view_contents) ';
// var_dump($view_contents);	 
	 
	// 직원 이름을 출력하는 열 출력
	for($i=0;$i<count($name_arr) + 1;$i++)
	 {
		   if($i===0)
			   print '<th scope="col"> 구분  </th>';
			  else
			   print '<th scope="col">' . $name_arr[$i-1]  .'</th>';
		   
		   
		   $view_sum[$i] = 0;
		   $sum_holidaywork[$i] = 0;
	 }
	?>
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

		// 결과를 출력합니다.
		// echo "{$year}년 {$month}월은 {$num_days}일까지 있습니다.";
	
		
		for($i=0;$i<$num_days+3; $i++)
	     {
			print '<tr class="text-center" >';
			 if($i===0)
			 {
				// print '<td class="text-primary" > 연장근로(시간) 합계 </td>'; 
				 print '<td class="text-primary" > </td>'; 
				for($j=0;$j<count($name_arr) ;$j++)						
							print '<td > </td>'; 
				
			 }
			 else if($i===1)
			 {
				print '<td class="text-danger "> 특근(시간) 합계 </td>'; 
				for($j=0;$j<count($name_arr) ;$j++)						
							print '<td > </td>'; 
				
			 }
			 else if($i===2)  // 공백한칸 띄움
			 {
				print '<td >  </td>'; 
				for($j=0;$j<count($name_arr) ;$j++)						
							print '<td > </td>'; 
				
			 }
			 else
			   {
			   $thisday =  $days[date('w', strtotime( $year . "-" . $month . "-" . ($i-2)))];
			   $pointday = date("Y-m-d",strtotime( $year . "-" . $month . "-" . ($i-2)));
			   // print $pointday;
			if( $thisday  ==='토' || $thisday  ==='일')
					print '<td class="text-danger" > ' . ($i-2) . '(' . $thisday . ')' . ' </td>';
				else
						print '<td > ' . ($i-2) . '(' . $thisday . ')' . ' </td>';
			  
						// 자료있는 숫자만큼 찾는다
						// print count($view_date) . ' ';
						// var_dump($view_date) . ' ';
						// var_dump($name_arr) . ' ';
						
						for($j=0;$j<count($name_arr);$j++)						
						{ 
							// 연차 사용이력 추적
							$annualleave = '';	
									 

							// 연차등 사용한 것 있으면 화면에 보여준다.										
									try{
									  $sql1 = "select * from mirae8440.eworks where is_deleted IS NULL and eworks_item='연차' ";
									  $stmh1 = $pdo->prepare($sql1); 			  
									  $stmh1->execute();			  
									 while($row = $stmh1->fetch(PDO::FETCH_ASSOC)) {			    					      
												   $al_name = $row["author"];     
												   $al_item = $row["al_item"];     
												   $al_askdatefrom = $row["al_askdatefrom"]; 
												   $al_askdateto = $row["al_askdateto"]; 
											 if ($pointday >= $al_askdatefrom && $pointday <= $al_askdateto && $al_name === $name_arr[$j])  // 이름과 신청일이 같을때는 자료 넣어준다.
													if( $thisday  !=='토' && $thisday  !=='일')
														  $annualleave = $al_item ;						   
											}
										 }catch (PDOException $Exception) {
										   print "오류: ".$Exception->getMessage();
										 }		 												
									
									// 기본적으로 연차표시
									 $printstr = '<td >' . $annualleave . '  </td>'; 	 																
							
							$addstr = '';							
							for($kk=0;$kk<count($view_date); $kk++)
								 { 
									  if(trim($view_name[$kk])== trim($name_arr[$j]) && trim($view_date[$kk])=== trim($pointday) )  //  이름과 데이터의 이름과 같은때 찍어준다.
									  {
										
										 if(trim($view_contents[$kk]) == '특근')														
												{													
											        // print $view_item[$kk];
													$sum_holidaywork[$j] += (float)$view_item[$kk] ;  // 누적시간 계산
													$addstr = '(특)';
												}
												
											$printstr = '<td > ' . $annualleave. $addstr . $view_item[$kk]  . '</td>' ;				
									  }
									  	
												
																						
								 }
								 
								 print $printstr;											
						 }
					  
			
		   }
		   
		   		 
		 print '</tr>';
		 }
		 
		//  var_dump($view_sum);
		?>
  
  </tbody>
</table>
		
		</div>
	</div>
</div>
 <?php
if($chkMobile=== true )
	echo '<div class="col-sm-12"> '	;
 else
  echo '<div class="col-sm-6"> 	 ';
 
?>

  <div class="card">
	<div class="d-flex mt-3 mb-3 justify-content-center align-items-center" >
		
		
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
		  
				$sql="select * from mirae8440.absent_office order by   askdatefrom desc, name asc  limit $first_num, $scale " ;
				$sqlcon="select * from mirae8440.absent_office order by   askdatefrom desc  " ;
		                
			       }
             elseif($search!="") {
				  
										  $sql ="select * from mirae8440.absent_office where (name like '%$search%')  ";
										  $sql .=" order by   askdatefrom desc  limit $first_num, $scale ";
										  $sqlcon ="select * from mirae8440.absent_office where (name like '%$search%') ";
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
		▷ <?= $total_row ?>  &nbsp;&nbsp;&nbsp; 		    
		<button type="button" class="btn btn-dark btn-sm me-1" onclick="popupCenter('write_form_ask.php', '사무실 등록', 415, 520);return false;" > <i class="bi bi-pencil"></i>  신규 </button> 	     	   
		<input type="text" name="search" id="search" class="form-control me-1" style="width:150px;" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어"> 	   
		<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-1"> <i class="bi bi-search"></i> 검색 </button>	
		<button type="button" class="btn btn-dark btn-sm" onclick="popupCenter('../annualleave/batchDB.php','연차 Grid',1400,950);" > 연차 Grid </button>
     </div>
	 
	<div class="d-flex mt-3 mb-3 justify-content-center " > 
      <table class="table table-hover">
	    <thead  class="table-primary" >
		<tr>
            <th class="text-center">번호</th>
			<th class="text-center">성명</th>            
            <th class="text-center">작업일</th>
            <th class="text-center">근로형태</th>
            <th class="text-center">작업시간</th>
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
		<tr onclick="popupCenter('write_form_ask.php?num=<?=$num?>', '사무실 특근', 420, 550);return false;">
		     
		    <td class="text-center" > <?=$start_num?>				</td>
			<td class="text-center" > <?=$name?>	</td>            			            
            <td class="text-center" > <?=iconv_substr($askdatefrom,5,5,"utf-8")?>	</td>
            <td class="text-center" > <?=$content?> </td>            			
            <td class="text-center" > <?=$item?>	</td>            			
            
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
sum_holidaywork = <?php echo json_encode($sum_holidaywork); ?> ;	 

console.log(sum_holidaywork);

let startNum = sum_holidaywork.length*3  ; 

       for(var i = startNum; i <= sum_holidaywork.length*4  ; i++) {
            // 테이블의 두 번째 행 (인덱스 1)에 특근 시간을 삽입합니다.
            // 이때, td의 인덱스는 1(빈 칸) + i로 계산합니다.
			console.log(i + ' ');
			console.log(sum_holidaywork[i - startNum ]);
            if(sum_holidaywork[i - startNum ] !== 0) {
                $("td:eq(" + i + ")").text(sum_holidaywork[i - startNum ]);
            }
        }
    
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

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('사무실 근태관리 (개별입력)'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>