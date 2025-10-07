<?php
 session_start();

 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 
   if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {          		 
		 sleep(1);
		  header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }  

// ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//header("Refresh:0");  // reload refresh   

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?> 
 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 
<title> JAMB 외주단가 </title> 

<style>
tr, th {
	font-size : 13px;
}

#showdate {
	display: inline-block;
	position: relative;
}
		
#showframe {
	display: none;
	position: absolute;
	width: 500px;
	z-index: 1000;
}

</style> 
 
 </head>
 
 <body >

 <?php
 
 include "_request.php";
 
 
  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
 
 
if($fromdate==="")
{	
	$fromdate="2010-01-01";
}
if($todate==="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}

// 입출고일 기준
$SettingDate="registerdate ";    

$common = "   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
   
// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search); 
 
if($search==""){		               				
		  $sql ="select * from mirae8440.work_outcost where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate'))   ";
		  $sql .=" order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
		  $sqlcon ="select * from mirae8440.work_outcost where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) ";
		  $sqlcon .="  order by " . $SettingDate . " desc, num desc "; 				
	}
	else {	 							
		  $sql ="select * from mirae8440.work_outcost where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate'))   ";
		  $sql .=" order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
		  $sqlcon ="select * from mirae8440.work_outcost where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) ";
		  $sqlcon .="  order by " . $SettingDate . " desc, num desc "; 	
	}
										
								            
$nowday=date("Y-m-d");   // 현재일자 변수지정   


 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
         include '_row.php'; 
				   
		try {
			$current_month = date('Y-m');
			$start_month = date('Y-m', strtotime('-23 months'));

			$data = []; // 연관 배열을 저장할 변수

			for ($i = 23; $i >= 0; $i--) {
				$target_month = $fromdate;
				$next_month = $Transtodate;

				$sqlsearch = "SELECT widejamb_unitprice, normaljamb_unitprice, narrowjamb_unitprice,registerdate  FROM mirae8440.work_outcost WHERE registerdate >= :target_month AND registerdate < :next_month";
				$stmt = $pdo->prepare($sqlsearch);
				$stmt->bindParam(':target_month', $target_month);
				$stmt->bindParam(':next_month', $next_month);
				$stmt->execute();

				while ($row_choice = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$registerdate=$row_choice["registerdate"];	
						$widejamb_unitprice=$row_choice["widejamb_unitprice"];	
						$normaljamb_unitprice=$row_choice["normaljamb_unitprice"];	
						$narrowjamb_unitprice=$row_choice["narrowjamb_unitprice"];	

					$widejamb_unitprice_cleaned = intval(str_replace(",", "", $widejamb_unitprice));
					$normaljamb_unitprice_cleaned = intval(str_replace(",", "", $normaljamb_unitprice));
					$narrowjamb_unitprice_cleaned = intval(str_replace(",", "", $narrowjamb_unitprice));

					$sum_unitprice = intval(($widejamb_unitprice_cleaned + $normaljamb_unitprice_cleaned + $narrowjamb_unitprice_cleaned) / 3);

				
					if (!isset($data)) {
						$data = []; // 아이템에 대한 배열을 초기화합니다.
					}

					$data[$registerdate] = $sum_unitprice; // 가공비 해당 단가를 저장합니다.
				}
			}
		} catch (PDOException $Exception) {
			error_log("오류: " . $Exception->getMessage());
		}			   
				   
			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

// var_dump($data);
   
 try{
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp2;     // 전체 글수	  		
         					 
     $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 
   	 				


?>   
   
<form name="board_form" id="board_form"  method="post" action="list.php?search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&scale=<?=$scale?>">  
  
 <div class="container" >
  
   		<input type="hidden" id="username" name="username" value="<?=$user_name?>" size="5" > 							
		
		<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
		<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
		<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
		<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
		<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
		<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
		<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
		<input type="hidden" id="list" name="list" value="<?=$list?>" > 				
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>" > 	
		
													
	<div class="d-flex mb-3 mt-2 justify-content-center align-items-center"> 
		<div id="display_board" class="text-primary fs-3 text-center" style="display:none"> 
		</div>     
	</div>	
	
	
	<div class="card" > 
		<div class="card-body" > 
	
	<div class="d-flex mb-1 mt-4 justify-content-center align-items-center"> 		 
		<span class="text-secondary fs-4" >  쟘(jamb) 외주 가공단가 (DS레이져) - 자재:사급제공  </span>		 
	</div>	
	

	<div class="d-flex justify-content-center align-items-center"> 		
	<div class="input-group p-1 mb-1 justify-content-center align-items-center">
	
		▷ <?= $total_row ?>건 
	
		&nbsp; 	&nbsp; 	


<div class="d-flex mb-2 justify-content-center align-items-center "> 		 
    <div class="input-group p-2 justify-content-center align-items-center text-center">			
       <span class='input-group-text align-items-center' style='width:400 px;'>  
		   <span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 
			<div id="showframe" class="card">
				<div class="card-header " style="padding:2px;">
					기간 검색
				</div>
				<div class="card-body">
					<button type="button" id="preyear" class="btn btn-primary btn-sm "   onclick='pre_year()' > 전년도 </button>  
					<button type="button" id="three_month" class="btn btn-secondary btn-sm "  onclick='three_month_ago()' > M-3월 </button>
					<button type="button" id="prepremonth" class="btn btn-secondary btn-sm "  onclick='prepre_month()' > 전전월 </button>	
					<button type="button" id="premonth" class="btn btn-secondary btn-sm "  onclick='pre_month()' > 전월 </button> 						
					<button type="button" class="btn btn-danger btn-sm "  onclick='this_today()' > 오늘 </button>
					<button type="button" id="thismonth" class="btn btn-dark btn-sm "  onclick='this_month()' > 당월 </button>
					<button type="button" id="thisyear" class="btn btn-dark btn-sm "  onclick='this_year()' > 당해년도 </button> 
				</div>
				<div class="card-footer">					
				</div>
			</div>	   
       <input type="date" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp; 부터 &nbsp;  
       <input type="date" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">  &nbsp;  까지    </span>  &nbsp;	   			
		
       </div>		         
   </div>	
		
		&nbsp; 	&nbsp; 	
			
			
			</div>
		</div>
	
		<div class="d-flex justify-content-center align-items-center"> 	 	 
				
					<button type="button" class="btn btn-dark  btn-sm " id="write_from" > <ion-icon name="pencil-outline"></ion-icon>  </button> &nbsp;&nbsp;	     
					 
			 
				   
			  목록수 &nbsp;
			   
		    <select name="scaleval" id="scaleval" class="form-control" style="width: 5%;" >
					   <?php		 
									
						   $scalearr = array();
						   array_push($scalearr,'50','100','200','300','500');
						   
						   for($i=0; $i<count($scalearr); $i++) {
									 if($scale==$scalearr[$i])
												print "<option selected value='" . $$scalearr[$i] . "'> " . $scalearr[$i] .   "</option>";
										 else   
								   print "<option value='" . $scalearr[$i] . "'> " . $scalearr[$i] .   "</option>";
							   } 		   
						   

						 ?>	  
					</select> 
			 &nbsp;&nbsp;   
	
			<input type="text" name="search" class="form-control" id="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어"  style="width: 20%;">
			  &nbsp;
					<button type="button" id="searchBtn" class="btn btn-dark btn-sm"  > <ion-icon name="search"></ion-icon> </button>	
					
       </div>
       </div>
       </div>		       	


<!-- HTML Body의 적절한 위치에 차트를 그릴 canvas 요소를 추가합니다. -->
	<div class="card" > 
	<div class="card-body" > 
		<canvas id="myChart" width="300" height="60"></canvas>
	</div>
	</div>	
	<div class="card" > 
	<div class="card-body" > 	
<script>
// PHP로부터 데이터를 가져와서 JavaScript 변수에 할당합니다.
var data = <?php 
    echo json_encode(array_reverse($data, true)); // JSON 형식으로 변환
?>;

// labels와 datasets 변수를 초기화합니다.
var labels = [];
var datasets = [];

// data 객체를 순회하며 labels와 datasets을 채웁니다.
for (var month in data) {
    if (data.hasOwnProperty(month)) {
        labels.push(month); // 월을 labels 배열에 추가
        datasets.push(data[month]); // 단가를 datasets 배열에 추가
    }
}

// Chart.js를 사용하여 차트를 생성합니다.
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line', // 라인 차트를 생성
    data: {
        labels: labels, // x축 레이블로 월을 설정
        datasets: [{
            label: ' 세트당 평균 단가 (원)',
            data: datasets, // y축 데이터로 단가를 설정
            borderColor: 'rgba(75, 192, 192, 1)', // 선 색상
            borderWidth: 2 // 선 두께
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>


</div>
</div>
	<div class="card mb-5" > 
	<div class="card-body" > 
   
  <div class="row mt-3 mb-1 p-1 m-2" >
     <table class="table table-hover table-border">
	   <thead class=" table-secondary">
	   <tr>
            <th class="text-center" >번호    </th>
            <th class="text-center" >등록일  </th>            
            <th class="text-center" >와이드쟘(막판)    </th>
            <th class="text-center" >멍텅구리(막판무)    </th>
			<th class="text-center" >쪽쟘  </th>                        
			<th class="text-center" >SET당 평균 가공단가  </th>                        
		  </tr>
		</thead>
	  <tbody>
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

             include '_row.php';
			 
			$widejamb_unitprice_cleaned = intval(str_replace(",", "", $widejamb_unitprice));
			$normaljamb_unitprice_cleaned = intval(str_replace(",", "", $normaljamb_unitprice));
			$narrowjamb_unitprice_cleaned = intval(str_replace(",", "", $narrowjamb_unitprice));

			$average = intval(($widejamb_unitprice_cleaned + $normaljamb_unitprice_cleaned + $narrowjamb_unitprice_cleaned) / 3);			 
			 					   		
				?>
		    <tr onclick="redirectToView('<?=$num?>');">

            <td class="text-center" > <?=$start_num?>		</td>
            <td class="text-center" > <?=$registerdate?>		</td>
            <td class="text-center" >	 <?=$widejamb_unitprice?>		</td>						   
            <td class="text-center" >	 <?=$normaljamb_unitprice?>		</td>						   
            <td class="text-center" >	 <?=$narrowjamb_unitprice?>		</td>			
            <td class="text-center" >	 <?=number_format($average)?>		</td>			
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
  
 
<div class="row row-cols-auto mt-3 mb-5 justify-content-center align-items-center"> 
 <?php
 
 
   $BigsearchTag = str_replace(' ','|',$Bigsearch);
 
	if($page!=1 && $page>$page_scale){
              $prev_page = $page - $page_scale;    
              // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
              if($prev_page <= 0) 
              $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
		      print '<button class="btn btn-outline-secondary  btn-sm" type="button" id=previousListBtn  onclick="javascript:movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;' ;              
            }
            for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
              if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                print '<span class="text-secondary" >  ' . $i . '  </span>'; 
              else 
                   print '<button class="btn btn-outline-secondary btn-sm" type="button" id=moveListBtn onclick="javascript:movetoPage(' . $i . ')"> ' . $i . '</button> &nbsp;' ;     			
            }

            if($page<$total_page){
              $next_page = $page + $page_scale;
              if($next_page > $total_page) 
                     $next_page = $total_page;
                // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
				  print '<button class="btn btn-outline-secondary  btn-sm" type="button" id=nextListBtn onclick="javascript:movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;' ; 
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


function redirectToView(num) {
	 customPopup("write_form.php?num=" + num + "&mode=modify", '외주단가 자료등록', 600, 500);     
}

$(document).ready(function(){	


$("#write_from").click(function(){ 	
	    customPopup('write_form.php', '외주단가 자료등록', 600, 500);     
 
 });


$("#scaleval").on("change", function(){
    //selected value
    $("#scale").val($(this).val());
	// 화면고정
	$('#stable').val('1');      
	$('#sortof').val('0');	
	$('#page').val('1');	
	
	$('#board_form').submit();			
    
});	
	


$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});


$("#searchBtn").click(function(){ 
	
	  // $BigsearchTag  설정
	  var str = '<?php echo $BigsearchTag; ?>' ;
	  
     $("#BigsearchTag").val(str.replace(' ','|'));
     $("#stable").val('1');
     $("#page").val('1');
     $("#list").val('1');
	 $("#board_form").submit();  
	 
	 console.log('bigsearch : ' + $("#Bigsearch").val());
	 console.log('bigsearchTag : ' + $("#BigsearchTag").val());	

	document.getElementById('board_form').submit();   
 
 
 });		
	
  $('a').children().css('textDecoration','none');  // a tag 전체 밑줄없앰.	
  $('a').parent().css('textDecoration','none');


$("input:radio[name=separate_date]").click(function() { 
		process_list(); 
});
    

	
	

});

    $('#Bigsearch').change(function() {
        // $BigsearchTag 설정
        var str = $("#Bigsearch").val();
        
        $("#page").val('1');
        
        $("#BigsearchTag").val(str.replace(' ','|'));
        $("#list").val('1');
        $("#board_form").submit();
        
        console.log('bigsearch : ' + $("#Bigsearch").val());
        console.log('bigsearchTag : ' + $("#BigsearchTag").val());  
        
        document.getElementById('board_form').submit();
    });

function blinker() {
	$('.blinking').fadeOut(700);
	$('.blinking').fadeIn(700);
}
setInterval(blinker, 1500);


function changeType(obj){   // 유형 선택하면 처리하는 부분
	var tmpType = $(obj).val();
	// console.log(tmpType);	
	 switch(tmpType) {
			case   "해당없음" :    
			  document.getElementById('search').value=''; 
			  break;
			case   "전체" :
			case   "설계"  :
			  document.getElementById('search').value=tmpType; 
			  break;
			case   "레이져" :
			  document.getElementById('search').value=tmpType; 
			  break;			
			case   "V컷" :
			  document.getElementById('search').value=tmpType; 
			  break;	
			case   "절곡" :
			  document.getElementById('search').value=tmpType; 
			  break;			
			case   "운반중" :
			  document.getElementById('search').value=tmpType; 
			  break;			
			case   "소장"   :
			  document.getElementById('search').value=tmpType; 
			  break;			
			case   "기타"   :
			  document.getElementById('search').value=tmpType; 
			  break;	
            default:
              document.getElementById('search').value=''; 
			 break;			
	 	}
	document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  	
}


function saveAsFile(str, filename) {  // text파일로 저장하기
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:attachment/text,' + encodeURI(str);
    hiddenElement.target = '_blank';
    hiddenElement.download = filename;
    hiddenElement.click();
}

function SearchEnter(){
		
    if(event.keyCode == 13){
		
	  // $BigsearchTag  설정
	  var str = $("#Bigsearch").val();
	  
	 $("#page").val('1');
	  
     $("#BigsearchTag").val(str.replace(' ','|'));
     $("#list").val('1');
	 $("#board_form").submit();  
	 
	 console.log('bigsearch : ' + $("#Bigsearch").val());
	 console.log('bigsearchTag : ' + $("#BigsearchTag").val());	
		
		
		document.getElementById('board_form').submit(); 
    }
}

function movetoPage(page){ 	  
	  $("#page").val(page); 
	  
	  // $BigsearchTag  설정
	  var str = '<?php echo $BigsearchTag; ?>' ;
	  
     $("#BigsearchTag").val(str.replace(' ','|'));
     $("#list").val('1');
	 $("#board_form").submit();  
	 
	 console.log('bigsearch : ' + $("#Bigsearch").val());
	 console.log('bigsearchTag : ' + $("#BigsearchTag").val());
}	


</script>
  