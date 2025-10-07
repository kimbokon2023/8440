<?php\nrequire_once __DIR__ . '/../common/functions.php';
if(!isset($_SESSION)) {
    session_start();
}
if(isset($_SESSION["DB"])) {
    $DB = $_SESSION["DB"];
}
$level = $_SESSION["level"] ?? null;
$user_name = $_SESSION["name"] ?? null;
$user_id = $_SESSION["userid"] ?? null;

$mcno = $_REQUEST["mcno"] ?? $_REQUEST["mcname"] ?? null;
$selnum = $_REQUEST["selnum"] ?? 1;

$WebSite = "http://8440.co.kr/";

if(!isset($level) || $level > 8) {
    // 세션에 이동할 URL 저장
    $_SESSION["url"] = $WebSite . "qc/laser.php?mcno=" . urlencode($mcno);
    header("Location: " . $WebSite . "login/login_form.php");
    exit;
} 
  
  // 첫 화면 표시 문구
 $title_message = '장비 (주간,정기) 점검표';     
   
   ?>
   
<?php include getDocumentRoot() . '/load_header.php' ?>
 
<title> <?=$title_message?> </title>   

 </head> 
 
<body>

<?php include getDocumentRoot() . "/common/modal.php"; ?>

<?php
 // 모바일이면 특정 CSS 적용
if ($chkMobile) {
    echo '<style>
        body, table th, table td, .form-control, span {
            font-size: 32px;
        }
         h4 {
            font-size: 40px; 
        }
		
		.btn-sm {
        font-size: 26px;
		}
		
		.spantitle {
			font-size: 40px;
		}
		
    </style>';
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect(); 

// 배열로 장비점검리스트 불러옴
include "load_DB.php";   

 // $find="firstord";	    //검색할때 고정시킬 부분 저장 ex) 전체/공사담당/건설사 등
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
 
if(isset($_REQUEST["scale"])) // $_REQUEST["scale"]값이 없을 때에는 20로 지정 
 {
    $scale=$_REQUEST["scale"];  // 페이지 번호
 }
  else
  {
    $scale=10;	   // 한 페이지에 보여질 게시글 수
  }   

  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // List에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")	$fromdate="2010-01-01";

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

$a= " order by checkdate desc ";    //내림차순
$b= " order by checkdate desc ";    //내림차순 전체

 isset($_REQUEST["mcno"])  ? $mcno=$_REQUEST["mcno"] :   $mcno=$_REQUEST["mcname"]; 
 isset($_REQUEST["selnum"])  ? $selnum=$_REQUEST["selnum"] :   $selnum=1; 

// var_dump($mcno);
// var_dump($selnum);
 
if(intval($selnum)==1)
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' " . $a; 						
}  
 
if(intval($selnum)==2)
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' and term='주간' " . $a; 						
}  
if(intval($selnum)==3) 
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' and term='1개월' " . $a; 						
}  
if(intval($selnum)==4) 
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' and term='2개월' " . $a; 						
}  
if(intval($selnum)==5) 
{	
	$sql="select * from mirae8440.mymclist where item='" . $mcno . "' and term='6개월' " . $a; 						
}  
// 미점검 리스트
if(intval($selnum)==6) 
{	     
	 // var_dump($mcno_arr[1]);
	 // var_dump($questionstep_arr);
	// check1~check10까지 해당 질의 수를 추출해서 sql문장을 만들어 보자
	for($j=0;$j<count($mcno_arr);$j++)
	 {
	  if($mcno==$mcno_arr[$j])
	  {  
		  $arrtmp = explode(",", $questionstep_arr[$j]);  // 이 함수 조심해야 함. 배열은 인식못함		  
	  }
	 }
		  
  $period = array();
  array_push($period,"주간","1개월","2개월","6개월");
  
  // var_dump($period);
  
   $sqladd = "";
  
  for($k=0; $k<count($period); $k++)   // 주간/1개월/2개월/6개월  4개항목 반복문
	{
	  $sqladd .=" (item='" . $mcno . "' and term = '" . $period[$k] . "' ) and ( ";  // 공통적으로 주간/1개월/2개월/6개월 주기
	  for($j=1; $j<=(int)$arrtmp[$k]; $j++)
	  {
		  if($j!=1)
			  $sqladd .= " or " ;    
		  $sqladd .= "   check" . $j . " is null  ";  // 공통적으로 주간/1개월/2개월/6개월 주기 
	  }
	   if($k==count($period)-1)
	        $sqladd .= "  ) " ; 
		else
			$sqladd .= "  ) or " ; 
	}	  
		$sql="select * from mirae8440.mymclist where " . $sqladd . $a; 							
		
}  
		 
// 점검 리스트
if(intval($selnum)==7) 
{	     
	 // var_dump($mcno_arr[1]);
	 // var_dump($questionstep_arr);
	// check1~check10까지 해당 질의 수를 추출해서 sql문장을 만들어 보자
	for($j=0;$j<count($mcno_arr);$j++)
	 {
	  if($mcno==$mcno_arr[$j])
	  {  
		  $arrtmp = explode(",", $questionstep_arr[$j]);  // 이 함수 조심해야 함. 배열은 인식못함		  
	  }
	 }
	 
	 // var_dump($arrtmp);	  
	 // var_dump('배열점검');
	 
	  $sqladd=" and term = '주간' ) and ( ";  // 공통적으로 주간/1개월/2개월/6개월 주기
	  
	  for($j=1; $j<=(int)$arrtmp[0]; $j++)
	  {
		  if($j!=1)
			  $sqladd .= " and " ;    // 점검완료는 and 조건
		  $sqladd .= "   check" . $j . " is not null  ";  // 공통적으로 주간/1개월/2개월/6개월 주기  not null로 변경
	  }
	    $sqladd .= "  ) " ; 
	  
		$sql="select * from mirae8440.mymclist where (item='" . $mcno . "'" . $sqladd . $a; 					
		
  $period = array();
  array_push($period,"주간","1개월","2개월","6개월");
  
  // var_dump($period);
  
   $sqladd = "";
  
  for($k=0; $k<count($period); $k++)   // 주간/1개월/2개월/6개월  4개항목 반복문
	{
	  $sqladd .=" (item='" . $mcno . "' and term = '" . $period[$k] . "' ) and ( ";  // 공통적으로 주간/1개월/2개월/6개월 주기
	  for($j=1; $j<=(int)$arrtmp[$k]; $j++)
	  {
		  if($j!=1)
			   $sqladd .= " and " ;    // 점검완료는 and 조건
		  $sqladd .= "   check" . $j . " is not null  ";  // 공통적으로 주간/1개월/2개월/6개월 주기  not null로 변경 두가지 검색조건 6,7번 다른점
	  }
	   if($k==count($period)-1)
	        $sqladd .= "  ) " ; 
		else
			$sqladd .= "  ) or " ; 
	}
	  
		$sql="select * from mirae8440.mymclist where " . $sqladd . $a; 							
		
	// print $sql;  

}  
			
 // print $sql;  			
 
$nowday=date("Y-m-d");   // 현재일자 변수지정   
   
// 전체 레코드수를 파악한다.
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();    		
			 
 ?>
		 
<form name="board_form" id="board_form"  method="post" action="laser.php" >    
		
	<input type="hidden" id="alerts" name="alerts" value="<?=$alerts?>" size="3" > 	
	<input type="hidden" id="selnum" name="selnum" value="<?=$selnum?>"  > 	
	<input type="hidden" id="mcmain" name="mcmain" value="<?=$mcmain?>"  > 	
	<input type="hidden" id="mcsub" name="mcsub" value="<?=$mcsub?>"  > 					
  
<?php if($chkMobile) { ?>	
<div class="container-fluid mt-2 mb-2"  >
<?php } if(!$chkMobile) { ?>	
<div class="container mt-2 mb-2"  >   
<?php  } ?>		

<div class="card mt-2 mb-4">  
<div class="card-body">   
 <div class="d-flex mt-3 mb-1 justify-content-start">  
   <h3 class="spantitle ">
		장비 점검
	</h3>
 </div>
 <div class="d-flex mt-3 mb-1 justify-content-end">  
<?php
    if(!isset($_SESSION["userid"]))
	{
?>
          <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
<?php
	}
	else
	 {
?>

	<?=$_SESSION["name"]?> | 
		<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
		
<?php
	 }
?>

</div>   
	  
	<div class="d-flex align-items-center mt-4 mb-3 justify-content-start">  
   				<?php if($chkMobile) { ?>
						<select class="form-control me-2" name="mcno" id="mcno" style="width:25%;" >		
				<?php }  else { ?>		
						<select class="form-control me-2" name="mcno" id="mcno" style="width:12%;" >		  
				<?php } ?>		
		   <?php
		   for($i = 0; $i < count($mcno_arr); $i++) {
			   if($mcno == $mcno_arr[$i])
				   print "<option selected value='" . $mcno_arr[$i] . "'> " . $mcno_arr[$i] . "</option>";
			   else   
				   print "<option value='" . $mcno_arr[$i] . "'> " . $mcno_arr[$i] . "</option>";
		   }        
		   ?>      
		</select>						 
   				<?php if($chkMobile) { ?>
						<span class="badge bg-secondary form-control me-2 fs-5" style="width:35%;border:0px;"  readonly>		
				<?php }  else { ?>		
						<span class="badge bg-secondary form-control me-2 fs-5" style="width:20%;border:0px;" readonly>		  
				<?php } ?>						
				 (정) <?=$mcmain?>, (부) <?=$mcsub?> 
			</span>			
			<button type="button" class="btn btn-danger btn-sm me-2" onclick="show_list(6);"> 미점검 </button>
			<button type="button" class="btn btn-success btn-sm me-2" onclick="show_list(7);"> 점검완료 </button>
	</div> 

	<div class="d-flex align-items-center mt-3 mb-1 justify-content-start">    
				<button type="button" id="closeBtn" class="btn btn-outline-dark  btn-sm "  >    창닫기 </button>	
				 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-outline-dark btn-sm " onclick="show_list(1);"> 전체  </button>&nbsp;
				<button type="button" class="btn btn-outline-success  btn-sm  "  onclick="show_list(2);"> 주간 </button>&nbsp;
				<button type="button" class="btn btn-outline-danger  btn-sm  "  onclick="show_list(3);"> 1개월 </button> &nbsp;				  
				<button type="button" class="btn btn-outline-secondary  btn-sm  "  onclick="show_list(4);"> 2개월  </button>&nbsp;
				<button type="button" class="btn btn-outline-dark  btn-sm  "       onclick="show_list(5);"> 6개월 </button>
	</div>	 
	<div class="d-flex align-items-center mt-3 mb-1 justify-content-start">  	   
	     (주간 점검) 매주 금요일 작업종료 30분전, <br> (월간 점검) 매월 넷째주 금요일 작업종료 30분전,  <br>
		 (2개월 점검) 짝수달 넷째주 금요일 작업종료 30분전 , <br> (6개월 점검) 6월,12월 넷째주 금요일 작업종료 30분전  <br>       
      </div> 
	<div class="d-flex align-items-center mt-3 mb-1 justify-content-start"> 
             ▷  <?= $total_row ?> 개 &nbsp;&nbsp;	&nbsp;&nbsp;    	
    </div> 

	<div class="row d-flex"  >
		<table class="table table-hover" id="myTable">
			<thead class="table-primary" >
				<tr>
					 <th class="text-center" > 점검일   </td>
					 <th class="text-center" > 주간점검  </td>
					 <th class="text-center" > 1개월점검 </td>
					 <th class="text-center" > 2개월점검 </td>   
					 <th class="text-center" > 6개월점검 </td>   
				</tr>
			</thead>
			<tbody> 
	<?php  
	  $start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
         include "rowDB.php";
			 
// 점검을 했는지 여부는 check1의 체크여부로 판단함
// check1~check10까지 해당 질의 수를 추출
for($j=0;$j<count($mcno_arr);$j++)
	 {
	  if($mcno==$mcno_arr[$j])
	  {  
		  $arrtmp = explode(",", $questionstep_arr[$j]);  
	  }
	 }	
// 주간점검 / 미점검 	 
	  for($j=1; $j<=(int)$arrtmp[0]; $j++)
	  {
		  $checkstr = 'check' . $j;
		  if($$checkstr==null)
		    {
				$weektermstr = '미점검';
				break;
			}
		    else
				$weektermstr = '완료';		
	  }
// 1개월 점검 / 미점검 	 
	  for($j=1; $j<=(int)$arrtmp[1]; $j++)
	  {
		  $checkstr = 'check' . $j;
		  if($$checkstr==null)
		    {
				$monthtermstr = '미점검';
				break;
			}
		    else
				$monthtermstr = '완료';		
	  }
// 2개월 점검 / 미점검 	 
	  for($j=1; $j<=(int)$arrtmp[2]; $j++)
	  {
		  $checkstr = 'check' . $j;
		  if($$checkstr==null)
		    {
				$twomonthtermstr = '미점검';
				break;
			}
		    else
				$twomonthtermstr = '완료';		
	  }
// 6개월 점검 / 미점검 	 
	  for($j=1; $j<=(int)$arrtmp[3]; $j++)
	  {
		  $checkstr = 'check' . $j;
		  if($$checkstr==null)
		    {
				$sixmonthtermstr = '미점검';
				break;
			}
		    else
				$sixmonthtermstr = '완료';		
	  }
			 
			 ?>
			 
	<tr onclick="redirectToView('<?=$num?>')">   			 	
    <td class="text-center"> 
        <span class="  text-center" >            <?=$checkdate?>  </span> 
    </td>

    <td class="text-center"> 
        <span class="  text-center" >            
                <?php if($term=='주간') echo $weektermstr; ?>            
        </span>
    </td>
    
    <td class="text-center"> 
        <span class="  text-center" >            
                <?php if($term=='1개월') echo $monthtermstr; ?>              
        </span>
    </td>

    <td class="text-center"> 
        <span class="  text-center" >            
                <?php if($term=='2개월') echo $twomonthtermstr; ?>              
        </span>
    </td>

    <td class="text-center"> 
        <span class="  text-center" >             
                <?php if($term=='6개월') echo $sixmonthtermstr; ?>              
        </span>
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
     
</form>
	
	

<script>

var dataTable; // DataTables 인스턴스 전역 변수
var mcpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('mcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var mcpageNumber = dataTable.page.info().page + 1;
        setCookie('mcpageNumber', mcpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('mcpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('mcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function redirectToView(num) {       	
    var url = "view.php?num=" + num ;
	customPopup(url, '장비 점검', 1300, 800); 		    
}

function show_list(insu){      
	$("#selnum").val(insu); 
	$("#page").val('1'); 
	// alert($("#selnum").val());
	$("#board_form").submit(); 
}  

$(document).ready(function(){	

 $("#mcno").bind( "change", function() {		
	  $("#board_form").submit(); 
	 });	

	$("#closeBtn").click(function(){ 	   
	   window.close();		
	});	
	 
		
});	
</script>		

</body>
</html>