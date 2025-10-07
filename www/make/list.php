<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/session.php');	
  
 if(!isset($_SESSION["level"]) || $level>=5) {
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");
         exit;
   }   
   
 // 첫 화면 표시 문구
 $title_message = '도장 발주';
   
 ?>
 
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

<title> <?=$title_message?> </title>  
 
 </head> 

<body>
   
<? require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>   

<?php
  
include "_request.php";

if($fromdate=="")
{
	// $fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "2020-01-01";
}
if($todate=="")
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
		  
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 $find=$_REQUEST["find"];
 
$process="전체";  // 기본 전체로 정한다.
/*  
$a=" order by orderdate desc limit $first_num, $scale";  
$b=" order by orderdate desc"; */

if($separate_date=="1") $SettingDate="orderdate ";
    else
		 $SettingDate="indate ";

$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by ";
$a= $common . " num desc ";    //내림차순
$b= $common . " num desc ";    //내림차순 전체
$c= $common . " num desc ";    //오름차순
$d= $common . " num desc ";    //오름차순 전체

$where=" where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') ";
$all=" ";
  
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from mirae8440.make " . $a; 						                         
			       }
             elseif($search!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from mirae8440.make where (orderdate like '%$search%')  or (text like '%$search%') ";
							  $sql .="or (indate like '%$search%') or (company like '%$search%') ";
							  $sql .=" order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";							  
						}

               }
if($mode=="" || $mode=="not") {
							 $sql="select * from mirae8440.make " . $a; 						                    
                }		
	
		      
   
$nowday=date("Y-m-d");   // 현재일자 변수지정   
   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();
	      			 
?>		 


<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&scale=10000">  

		<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
		<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
		<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
		<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
		<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
		<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" > 	
		<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" > 	
		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
		<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
		<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
		<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
		<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
		<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
		<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 

<div class="container">  
	<div class="card mt-2 mb-4">    
 <div class="card-header mb-1 justify-content-center  align-items-center">  
	<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 	 
		<h4 class="me-4"> <?=$title_message?>  </h4> <button type="button" class="btn btn-outline-success btn-sm"  onclick="location.href='../paint/index.php';">   도장발주 모바일버전으로 화면보기 </button> 		
		</div>
  </div>	 	
	<div class="card-body justify-content-center">  		 			
<div class="row"> 		  
	<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 		
	<!-- 기간설정 칸 -->
	 <?php include $_SERVER['DOCUMENT_ROOT'] . '/setdate.php' ?>
	&nbsp;
	<button type="button" class="btn btn-dark  btn-sm me-2" id="writeBtn"> <i class="bi bi-pencil"></i>  신규 </button> 
	</div>
</div>
 	<div class="d-flex justify-content-center align-items-center"> 			  
	    <table class="table table-hover" id="myTable">
			<thead class="table-primary">
			  <tr>  
				<th class="text-center" style="width:50px;">  번호  </th>            
				<th class="text-center" style="width:120px;"> 접수  </th> 
				<th class="text-center" style="width:100px;"> 발주  </th> 
				<th class="text-center" style="width:120px;"> 발주처 </th>  
				<th class="text-center">(현장명 등) 발주내용 </th> 
             </tr>
		  <tbody>
<?php  			
	$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번		  

	while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		$num=$row["num"];
		$orderdate=$row["orderdate"];
		$indate=$row["indate"];
		$company=$row["company"];
		$text=$row["text"];

		//	$text =explode('|' , $text);
		$text = str_replace (",", " ", $text);
		$text = str_replace ("|", " ", $text);
		$sumStr=$text;

	$date_font="color-black";  // 현재일자 Red 색상으로 표기
	if($nowday==$orderdate) {
				$date_font="color-red";
	}				
							  
 if($orderdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $orderdate = $orderdate . $week[ date('w',  strtotime($orderdate)  ) ] ;
}  
	echo '<tr style="cursor:pointer;" onclick="redirectToView(' . $num . ', \'' . $page . '\', \'' . $find . '\', \'' . $search . '\', \'' . $process . '\', \'' . $asprocess . '\', \'' . $yearcheckbox . '\', \'' . $year . '\', \'' . $fromdate . '\', \'' . $todate . '\', \'' . $separate_date . '\', \'' . $scale . '\')">';	  
?>			 
		<td class="text-center "><?=$start_num?> </td>
		<td class="<?=$date_font?>  text-center ">	<?=$orderdate?>		</td>
		<td class="<?=$date_font?> text-center ">	 <?=$indate ?> </td>
		<td class="text-center ">	 <?=$company?>		</td>
		<td class="color-gray">	 <?=$sumStr?>		</td>            			
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
</div> <!--card-body-->
</div> <!--card -->
</div> <!--container-->

</form>

<div class="container-fluid">
	<? include '../footer_sub.php'; ?>
</div>
  
<script>    
var dataTable; // DataTables 인스턴스 전역 변수
var paintpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('paintpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var paintpageNumber = dataTable.page.info().page + 1;
        setCookie('paintpageNumber', paintpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('paintpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
	
	$("#writeBtn").click(function(){ 
		var page = paintpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)			
		var url = "write_form.php"; 
		customPopup(url, '신규 등록', 1400, 800); 	
	 });			
});

function restorePageNumber() {
    var savedPageNumber = getCookie('paintpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function redirectToView(num, find, search, Bigsearch, yearcheckbox, year, fromdate, todate, separate_date, scale, mywrite) {
    var page = paintpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
    	
    var url = "view.php?menu=no&num=" + num         
        + "&find=" + find 
        + "&search=" + search 
        + "&Bigsearch=" + Bigsearch 
        + "&yearcheckbox=" + yearcheckbox 
        + "&year=" + year 
        + "&fromdate=" + fromdate 
        + "&todate=" + todate 
        + "&separate_date=" + separate_date 
        + "&scale=" + scale
        + "&mywrite=" + mywrite;       

	customPopup(url, '도장 발주', 1400, 800); 		    	
}
</script>


<script>
	$(document).ready(function(){
		saveLogData('도장 발주'); // 다른 페이지에 맞는 menuName을 전달
	});
</script> 
	
	</body>  
  </html>
  