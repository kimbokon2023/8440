<?php
require_once __DIR__ . '/../common/functions.php';
?>
<?

session_start();
// 접속자가 php 모바일 PC 구분하는 법
$mobile_agent = "/(iPod|iPad|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";

if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])){
	$deive= "Mobile";
}else{
	$deive= "PC";
}

isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
isset($_REQUEST["year"])  ? $year=$_REQUEST["year"] :   $year = date("Y");
isset($_REQUEST["month"])  ? $month=$_REQUEST["month"] :  $month =date("m");

 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"]; 
 
 if($user_name=='소현철' ||$user_name=='김보곤' ||$user_name=='최장중' ||$user_name=='이경묵')
	  $admin = 1;
 
if(!isset($_SESSION["level"]) ) {	          
		 $_SESSION["url"]='http://8440.co.kr/absent/index.php?user_name=' . $user_name; 	
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
} 
  
// ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
 
							                	
function conv_num($num) {
$number = (float)str_replace(',', '', $num);
return $number;
}
	  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
// 배열로 기본정보 불러옴
include "../absent/load_DB.php";

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
  $view_contents = array();    
  $view_sum = array(); // 연장근로
  $view_sum_holiday = array(); // 특근 합계
	try{
	  $sql = "select * from mirae8440.absent ";
	  $stmh = $pdo->prepare($sql); 
	  $stmh->bindValue(1,$num,PDO::PARAM_STR); 
	  $stmh->execute();
	  $count = $stmh->rowCount();            
	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		 
	     include '../absent/rowDBask.php';	  				 

	  
	  array_push($view_name, $name);
	  array_push($view_date, $askdatefrom); // 작업일 기준
	  array_push($view_contents, $content);  // 형태 (연장근로, 특근)	  
	  array_push($view_item, $item);  // 작업시간
	    }
	 }catch (PDOException $Exception) {
	   print "오류: ".$Exception->getMessage();
	 }


 ?>
 
 <!DOCTYPE HTML>
 <html>
 
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- CSS only -->
<script src="../common.js"></script>  

<link rel="stylesheet" type="text/css" href="../css/common.css?v=2">
<link rel="stylesheet" type="text/css" href="../css/work.css?v=2">

<script src="http://8440.co.kr/js/typingscript.js"></script>  <!-- typingscript.js 포함  글자 움직이면서 써지는 루틴 -->

<!-- 최초화면에서 보여주는 상단메뉴 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
	
<title> 미래기업 근태관리 </title>
<style>

#qc-control {
  border: 1px solid #ccc;
  padding: 10px;
  width: 300px;
}

#qc-control label {
  display: block;
  margin-bottom: 5px;
}

#qc-control input[type="text"] {
  width: 100%;
  padding: 5px;
  margin-bottom: 10px;
}

#qc-control button {
  display: block;
  margin: 0 auto;
  padding: 5px 10px;
}

#qc-control #result {
  margin-top: 10px;
  padding: 5px;
  border: 1px solid #ccc;
}


</style>
	
<body>                

 
<div class="container">  
	
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

<div class="container-fluid">  
<div class="d-flex mb-1 justify-content-center">    
  <a href="../index.php"><img src="../img/toplogo.jpg" style="width:100%;" ></a>	
</div>

<? include '../myheader.php'; ?>   

</div>   

	<form name="board_form" id="board_form"  method="post" action="index.php">  

	<div class="container">

	<div class="d-flex mt-3 mb-3 justify-content-center " >
		<div id="qc-control">
		  <label for="measurement">Measurement:</label>
		  <input type="text" id="measurement">
		  <button id="submit">Submit</button>
		  <div id="result"></div>
		</div>		
	</div>	    

  <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    접기
  </a>

	
  <div class="collapse" id="collapseExample">
  <div class="card card-body">

 
	<div class="d-flex mt-3 mb-3 justify-content-center " >
	
		<h2>		년월 설정
		<select name="year" id="year"  >
		   <?php		    
			$year_arr = array("2023","2022"); 
		   for($i=0;$i<count($year_arr);$i++) {
				 if($year==$year_arr[$i])
							print "<option selected value='" . $year_arr[$i] . "'> " . $year_arr[$i] .   "</option>";
					 else   
			   print "<option value='" . $year_arr[$i] . "'> " . $year_arr[$i] .   "</option>";
		   } 		   
				?>	  
		</select> 					
		<select name="month" id="month"  >
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
		</h2>
	</div>
	<div class="d-flex mt-3 mb-3 justify-content-center " >
	
	<table class="table table-striped  table-bordered">
  <thead>
    <tr class="text-center" >
	 <?php 
        // 직원 이름을 출력하는 열 출력
		for($i=0;$i<count($name_arr) + 1;$i++)
	     {
               if($i===0)
				   print '<th scope="col"> 구분  </th>';
			      else
				   print '<th scope="col">' . $name_arr[$i-1]  .'</th>';
			   
			   
			   $view_sum[$i] = 0;
			   $view_sum_holiday[$i] = 0;
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
				print '<td class="text-primary" > 연장근로(시간) 합계 </td>'; 
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
						//print count($view_date);
						
						for($j=0;$j<count($name_arr) ;$j++)						
						{
							// 연차 사용이력 추적
							$annualleave = '';	
									 

							// 연차등 사용한 것 있으면 화면에 보여준다.										
									try{
									  $sql1 = "select * from mirae8440.al ";
									  $stmh1 = $pdo->prepare($sql1); 			  
									  $stmh1->execute();			  
									 while($row = $stmh1->fetch(PDO::FETCH_ASSOC)) {			    					      
												   $al_name = $row["name"];     
												   $al_item = $row["item"];     
												   $al_askdatefrom = $row["askdatefrom"]; 
											  if($pointday === $al_askdatefrom &&  $al_name===$name_arr[$j])  // 이름과 신청일이 같을때는 자료 넣어준다.
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
									  if($view_name[$kk]===$name_arr[$j] && $view_date[$kk]=== $pointday )  //  이름과 데이터의 이름과 같은때 찍어준다.
									  {
											
     									 if($view_contents[$kk] == '연장근로')		
										       {
											        $view_sum[$j] += (float)$view_item[$kk] ;
													$addstr = '(야)';
											   }
										 if($view_contents[$kk] == '특근')														
												{													
													$view_sum_holiday[$j] += (float)$view_item[$kk] ;
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

		 
		 
		// var_dump($view_sum);
		?>
  
  </tbody>
</table>
		
		</div>
	</div>
</div>
	
	
	
	
	
	
	<div class="d-flex mt-5 mb-3 justify-content-center " >
		
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
 
  $scale = 100;       // 한 페이지에 보여질 게시글 수
  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.	  
	  
if($mode=="search" || $mode==""){
	  if($search==""){
		  
				$sql="select * from mirae8440.absent order by   askdatefrom desc, name asc  limit $first_num, $scale " ;
				$sqlcon="select * from mirae8440.absent order by   askdatefrom desc  " ;
		                
			       }
             elseif($search!="") {
				  
										  $sql ="select * from mirae8440.absent where (name like '%$search%')  ";
										  $sql .=" order by   askdatefrom desc  limit $first_num, $scale ";
										  $sqlcon ="select * from mirae8440.absent where (name like '%$search%') ";
										  $sqlcon .=" order by   askdatefrom desc ";
					
										 
								}				
	      	 }      

 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   
	   include "../absent/rowDBask.php";
			  
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
  
 
	 <button type="button" class="btn btn-dark" onclick="popupCenter('write_form_ask.php', '등록/수정/삭제', 415, 520);return false;" > 자료입력 </button> &nbsp;&nbsp;&nbsp;	
	
     &nbsp;&nbsp;&nbsp; ▷ 총 <?= $total_row ?>  &nbsp;&nbsp;&nbsp; 		    
	   
	<input type="text" name="search" id="search" size="20" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어"> 
	   &nbsp;&nbsp;&nbsp; 
	<button type="button" id="searchBtn" class="btn btn-dark "  > 검색 </button>	&nbsp;&nbsp;
	<button type="button" class="btn btn-secondary  " onclick="popupCenter('batchDB.php','연차 Grid 보기',1400,950);"> 연차 Grid 보기 </button>    &nbsp;

     </div>
	<div class="d-flex mt-3 mb-3 justify-content-center " > 
      <div class="limit justify-content-center ">
        <ul class="list-group justify-content-center ">				
          <li class="list-row list-row--header justify-content-center ">
		  <h4>
            <div class="list-cell list-cell--200  text-center">번호</div>
			<div class="list-cell list-cell--200  text-center">성명</div>            
            <div class="list-cell list-cell--200  text-center">작업일</div>
            <div class="list-cell list-cell--300  text-center">근로형태</div>
            <div class="list-cell list-cell--200  text-center">작업시간</div>
            
            
			</h4>
          </li>	       
		  
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	           include "../absent/rowDBask.php";    
			   
				?>
		<li class="list-row">
		
		   <a href='write_form_ask.php?num=<?=$num?>' class="list-link" style="text-decoration:none;" onclick="popupCenter(this.href, '연장근로 등록/수정/삭제', 420, 550);return false;">
		     <h4>
		     <div class="list-cell list-cell--200  text-center"><?=$start_num?>				</div>
			 <div class="list-cell list-cell--200  text-center"><?=$name?>	</div>            			            
            <div class="list-cell list-cell--200  text-center"><?=iconv_substr($askdatefrom,5,5,"utf-8")?>	</div>
            <div class="list-cell list-cell--300  text-center"><?=$content?> </div>            			
            <div class="list-cell list-cell--200  text-center"><?=$item?>	</div>            			
            
            
			</h4>
			</a>
			
          </li>			
			    
				
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
       </ul>
	   </div>
	   </div>
	<div class="d-flex mt-3 mb-3 justify-content-center " > 	         
	
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

</form>

 </div>   
    
 


   
  
<script>


$(document).ready(function(){	

// UI 요소 가져오기
const measurementInput = document.getElementById('measurement');
const submitButton = document.getElementById('submit');
const resultDiv = document.getElementById('result');

// Submit 버튼에 이벤트 핸들러 추가하기
submitButton.addEventListener('click', () => {
  const measurement = parseFloat(measurementInput.value);

  if (isNaN(measurement)) {
    resultDiv.textContent = 'Invalid measurement';
  } else if (measurement < 10) {
    resultDiv.textContent = 'Measurement too low';
  } else if (measurement > 20) {
    resultDiv.textContent = 'Measurement too high';
  } else {
    resultDiv.textContent = 'Measurement within acceptable range';
  }
});


  console.log(localStorage.getItem('isCollapsed'));

// 토글버튼을 웹브라우저에 저장하고 불러오는 방법
  if (localStorage.getItem('isCollapsed') === 'true') {
    $('.collapse').collapse('show');
  } else {
    $('.collapse').collapse('hide');
  }

  // 토글 버튼이 클릭될 때마다 상태 값을 저장합니다.
  $('.collapse').on('hidden.bs.collapse', function () {
		localStorage.setItem('isCollapsed', 'false');
		console.log('ㅇㅇㅇㅇ');
		console.log(localStorage.getItem('isCollapsed'));
  });

  $('.collapse').on('shown.bs.collapse', function () {
		localStorage.setItem('isCollapsed', 'true');
		console.log(localStorage.getItem('isCollapsed'));
		console.log('ㅇㅇㅇㅇ');
  });
  



// 합계치가 나오면 첫번째줄의 요소를 바꿔준다.
view_sum = <?php echo json_encode($view_sum); ?> ;	 
view_sum_holiday = <?php echo json_encode($view_sum_holiday); ?> ;	 

// console.log(view_sum);
// console.log(view_sum.length);

// 연장근로 상단에 표시해주기
for(i=0;i<view_sum.length-1;i++)  // 12명까지 계산해야 함 13명이면 1일자가 0으로 나옴
	if(Number(view_sum[i]) !== 0)
		$("td:eq(" + (i+1) + ")").text(view_sum[i]);

// 특근 상단에 표시해주기
for(i=view_sum.length+1;i< (view_sum.length+view_sum_holiday.length+1)-1;i++)  // 12명까지 계산해야 함 13명이면 1일자가 0으로 나옴	
  if(Number(view_sum_holiday[i-(view_sum.length+1)]) !== 0)
     $("td:eq(" + (i) + ")").text(view_sum_holiday[i-(view_sum.length+1)]); 
	 
  // $("td:eq(15)").text('10');	 

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

</script>
  </body>
  </html>