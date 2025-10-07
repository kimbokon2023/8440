<?php\nrequire_once __DIR__ . '/../common/functions.php';
include getDocumentRoot() . "/session.php";  

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }    
 ?>

<?php
include getDocumentRoot() . '/load_header.php';
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
isset($_REQUEST["year"])  ? $year=$_REQUEST["year"] :   $year = date("Y");
isset($_REQUEST["month"])  ? $month=$_REQUEST["month"] :  $month =date("m");
 
 if($user_name=='소현철' || $user_name=='김보곤' || $user_name=='최장중' || $user_name=='이경묵'   || $user_name=='소민지'  )
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
  $view_memo = array();  
  $view_contents = array();    
  $view_sum_after =0 ; // 점심식사
  $view_sum_evening =0 ; // 석식 합계
  
	try{
	  $sql = "select * from mirae8440.afterorder ";
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
	  array_push($view_memo, $memo);  // 비고기록	  
	    }
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
	 }

// var_dump($view_memo);

 ?>
 
<title> 식사 주문 </title>
	
<body>                

<? include '../myheader.php'; ?>   
 
 <?php if($chkMobile==false) { ?>
	<div class="container">     
 <?php } else { ?>
 	<div class="container-fluid">     
	<?php } ?>	 
	
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title"> 알림제목 넣는 칸 </h4>
        </div>
        <div class="modal-body">
		
		<input type="hidden" name="saleprice_val" id="saleprice_val" > 
		<input type="hidden" name="mcmain" id="mcmain" > 
		<input type="hidden" name="mcsub" id="mcsub" > 
		<input type="hidden" name="cartsave" id="cartsave" > 
		  
		  (부제목) : <input type="text" name="test" id="test" size="2" value=""/> <br><br>
		  
		  				
           <div class="row gx-4 gx-lg-4 align-items-center">
           <div class="col-md-5"><img id="imgID" class="card-img-top mb-5 mb-md-0" src="" alt="..." >
		   <br>
		   <br>
		   <br> <p class=lasercut >  </p>
			<div class="embed-responsive embed-responsive-16by9">
				<iframe id=youtubeID class="embed-responsive-item" src="<?=$youtube1?>"  frameborder="0" allowfullscreen></iframe>
			</div> 		   

		   <br> <p class=workdone >  </p>
			<div class="embed-responsive embed-responsive-16by9">
				<iframe id=youtubeIDsecond class="embed-responsive-item" src="<?=$youtube1?>"  frameborder="0" allowfullscreen></iframe>
			</div> 
		   
		   </div>
           <div class="col-md-7">
				<div class="small mb-5">
				<h1 id="catagory_sub" class="display-5 fw-bolder"> </h1>
				<div id="item_sub" class="fs-1 mb-5" > </div>
				
				<div id="itemdes_sub" class="fs-3 mb-5" > </div>	   
					  
				  <div class="d-flex justify-content-center large text-warning mb-2"> 
									<div class="bi-star-fill"></div>
									<div class="bi-star-fill"></div>
									<div class="bi-star-fill"></div>
									<div class="bi-star-fill"></div>
									<div class="bi-star-fill"></div>
								</div>		
					  
					   <div class="d-flex fs-2 mb-2">
                       <span id="price_sub" class="text-decoration-line-through"> 11</span> &nbsp;								
					   <span id="salepricerate" style="color:red;font-weight:bold;"> </span>  &nbsp;	
					     </div> 
						 <div class="d-flex fs-2 mb-5">
					   <span  style="color:blue;"> 판매가 </span>  &nbsp;
					   <span id="saleprice_sub" > </span> 						                           
                       </div> 			
                        
							   <br>
							<div class="d-flex">
                            <button type="button" id="addcart"   class="btn btn-outline-dark mt-auto fs-1" > 
                                <i class="bi-cart-fill me-1"></i>
                                장바구니 넣기                           </button>
							</div>
													
							</div>
                        </div>
                    </div>			  

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>  
</div>

<form name="board_form" id="board_form"  method="post" action="index.php">  

	<div class="container">

	<div class="d-flex mt-3 mb-3 justify-content-center align-items-center" >
		<h5 class="text-dark" > (중식) 1차 주문 소민지, 부재시 조경임,  </h5>    &nbsp;&nbsp;&nbsp; 		
		<h5 class="text-primary" > (석식) 주문 이경묵 입력 </h5>    
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

 
	<div class="d-flex mt-3 mb-3 justify-content-center align-items-center " >
	
		<h6>	(식사 주문) 년월 설정  </h6>
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
	
		<h5>	식사 제외 인원(외식등) :  <span id="exceptNum" > <span>  	</h5> 
	</div>
	<div class="d-flex mt-3 mb-3 justify-content-center " >
	
		<h5>	지정식당 지급인원수 (식수 - 제외수) :  <span id="totalNum" > <span>  	</h5> 
	</div>
	<div class="d-flex mt-3 mb-3 justify-content-center " >
	
	<table id="table_sum" class="table table-striped  table-bordered">
  <thead class="table-primary">
    <tr class="text-center" >
	 <?php 
        // 직원 이름을 출력하는 열 출력

				   print '<th scope="col"> 구분  </th>';			      
				   print '<th >' .' (중식) 인원수 '  .'</th>';			   
				   print '<th >' .' (석식) 인원수 '  .'</th>';			   
			   
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
		
		$exceptNum = 0;
	
		
		for($i=0;$i<$num_days+2; $i++)
	     {
			print '<tr class="text-center" >';
			 if($i===0)
			 {
				print '<td class="text-primary" > 인원 합계 </td>'; 				
				print '<td >  </td>';  
				print '<td >  </td>';  
				
			 }
			 
			 else if($i===1)  // 공백한칸 띄움
			 {
				print '<td >  </td>';  
				print '<td >  </td>';  
				print '<td >  </td>';  
				
			 }
			 else
			   {
					   $thisday =  $days[date('w', strtotime( $year . "-" . $month . "-" . ($i-1)))]; // 화면에 보여주기 위한 날짜
					   $pointday = date("Y-m-d",strtotime( $year . "-" . $month . "-" . ($i-1)));  // 해당일을 뽑아내서 비교하기 위한 변수
					   // print $pointday;
					if( $thisday  ==='토' || $thisday  ==='일')
							print '<td class="text-danger" > ' . ($i-1) . '(' . $thisday . ')' . ' </td>';
						else
								print '<td > ' . ($i-1) . '(' . $thisday . ')' . ' </td>';
					  
									// 중식 있으면 찾는 구문
									// 기본적으로 표시 없음
									 $printstr = '<td >   </td>'; 	 														
									
									for($kk=0;$kk<count($view_date); $kk++)
										 { 

											if($view_date[$kk]=== $pointday  && $view_contents[$kk] == '중식' )  //  이름과 데이터의 이름과 같은때 찍어준다.
												  {
														$view_sum_after += (float)$view_item[$kk] ;
													 if( $view_memo[$kk] !== '지정식당' && $view_memo[$kk] !== null )
														    $exceptNum += (float)$view_item[$kk] ;
														
														$printstr = '<td > ' .  $view_item[$kk]  . '</td>' ;				
												  }
																								
										 }		
										print $printstr;
										
									// 석식 있으면 찾는 구문
									// 기본적으로 표시 없음
									 $printstr = '<td >   </td>'; 	 														
									
									for($kk=0;$kk<count($view_date); $kk++)
										 { 

											if($view_date[$kk]=== $pointday && $view_contents[$kk] == '석식' )  //  이름과 데이터의 이름과 같은때 찍어준다.
												  {
																												
														$view_sum_evening += (float)$view_item[$kk] ;
														
													 if( $view_memo[$kk] !== '지정식당' && $view_memo[$kk] !== null )
														    $exceptNum += (float)$view_item[$kk] ;															
															
														$printstr = '<td > ' .  $view_item[$kk]  . '</td>' ;				
												  }
																								
										 }		
										print $printstr;														
								 }
								 
																								 
					  
		
		   		 
		 print '</tr>';
		 }

		 
		 
		// var_dump($view_sum);
		// var_dump($exceptNum);
		
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
	<div class="d-flex mt-3 mb-3 justify-content-center align-items-center " >
		
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
				$sql="select * from mirae8440.afterorder order by   askdatefrom desc, name asc  limit $first_num, $scale " ;
				$sqlcon="select * from mirae8440.afterorder order by   askdatefrom desc  " ;
		              
			       }
             elseif($search!="") {				  
										  $sql ="select * from mirae8440.afterorder where (name like '%$search%')  or (content like '%$search%') or (memo like '%$search%')  ";
										  $sql .=" order by   askdatefrom desc  limit $first_num, $scale ";
										  $sqlcon ="select * from mirae8440.afterorder where (name like '%$search%')   or (content like '%$search%') or (memo like '%$search%') ";
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
	
		 <button type="button" class="btn btn-dark btn-sm me-1" onclick="popupCenter('write_form_ask.php', '식사 관리', 415, 520);return false;" >  <i class="bi bi-pencil"></i>  신규 </button> 		   
		 <input type="text" class="form-control mx-1" name="search" id="search" value="<?=$search?>" style="width:150px; height:32px;" onkeydown="JavaScript:SearchEnter();" placeholder="검색어 입력"> 
		 <button type="button" id="searchBtn" class="btn btn-dark btn-sm"  > <i class="bi bi-search"></i> 검색 </button>				
	 </div>
	<div class="table table-responsive" > 
	<table class="table table-striped  table-hover table-bordered">
     <thead class="table-primary">
        <tr class="text-center" >
            <th class=" text-center">번호</th>
            <th class=" text-center">주문일</th>
            <th class=" text-center">유형</th>
            <th class=" text-center">주문수량</th>
            <th class=" text-center"> 종류 </th>
            <th class=" text-center"> 확인 </th>
            
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
		<tr onclick="popupCenter('write_form_ask.php?num=<?=$num?>', '식사주문', 420, 550);return false;">				   

			<td class="text-center"><?=$start_num?>				</td>			 
            <td class="text-center"><?=iconv_substr($askdatefrom,5,5,"utf-8")?>	</td>
            <td class="text-center"><?=$content?> </td>            			
            <td class="text-center"><?=$item?>	</td>            			
            <td class="text-center"><?=$memo?>	</td>            			
            <td class="text-center <?= $state == '요청' ? 'text-primary' : '' ?>"><?= $state ?></td>
          			
            
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
exceptNum = '<?php echo $exceptNum; ?>' ;	 

var totalNum;

totalNum = Number(view_sum_after) + Number(view_sum_evening) - Number(exceptNum) ;

exceptNumStr = '(' + (Number(view_sum_after) + Number(view_sum_evening)) + ' - ' + exceptNum + ') = ' + totalNum ;
 console.log(view_sum_after);
 console.log(view_sum_evening);

// 연장근로 상단에 표시해주기
$("#table_sum tr:eq(1) td:eq(" + (1) + ")").text(view_sum_after);
$("#table_sum tr:eq(1) td:eq(" + (2) + ")").text(view_sum_evening);


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


 $("#exceptNum").text(exceptNum);
 $("#totalNum").text(exceptNumStr);

});

function SearchEnter(){
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}


// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('(중식) 1차 주문 소민지'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>