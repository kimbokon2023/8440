<?php
/**
 * RnDfund 목록 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
require_once includePath('load_GoogleDrive.php'); 

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}   

include includePath('load_header.php');
  
// 첫 화면 표시 문구
$title_message = '연구전담부서 운영비';  
$tablename ='RnDfund';
 ?>

 <title> <?=$title_message ?> </title> 

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body,
        .card-header {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 영역 최적화 */
        .d-flex.mb-4.mt-4.fs-6.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-4.mt-4.fs-6.justify-content-center {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
            margin: 0.5rem 0 !important;
        }
        
        /* 검색/버튼 영역 최적화 */
        .d-flex.mb-1.mt-1.justify-content-center.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mb-1.mt-1.justify-content-center.align-items-center button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* DataTables 컨트롤 숨기기 */
        #myTable_wrapper .dataTables_length {
            display: none !important;
        }
        
        #myTable_wrapper .dataTables_filter {
            display: none !important;
        }
        
        /* 테이블 숨기기 (데이터는 읽을 수 있도록) */
        #myTable {
            visibility: hidden !important;
            position: absolute !important;
            left: -9999px !important;
        }
        
        /* 모바일 카드 컨테이너 */
        #mobile-card-container {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
        }
        
        .mobile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            cursor: pointer;
            transition: box-shadow 0.15s ease-in-out;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .mobile-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .mobile-card-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 0.75rem;
            color: #212529;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-item:last-child {
            border-bottom: none;
        }
        
        .mobile-card-label {
            font-weight: 600;
            color: #6c757d;
            display: inline-block;
            min-width: 80px;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .mobile-card-value {
            color: #212529;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
    
    /* PC 환경에서 모바일 카드 컨테이너 숨기기 */
    @media (min-width: 769px) {
        #mobile-card-container {
            display: none !important;
        }
    }
</style>

 </head>
 <body>

<?php require_once(includePath('myheader.php')); ?>   

 <?php
$search = $_REQUEST["search"] ?? '';   //목록표에 제목,이름 등 나오는 부분
$separate_date = $_REQUEST["separate_date"] ?? '';   //출고일 접수일	 
 
if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
 $list=$_REQUEST["list"];
else
	  $list=0;
  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
  
 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
 $cursort = $_REQUEST["cursort"] ?? '';    // 현재 정렬모드 지정

  if($separate_date=="") $separate_date="1";

 // 기간을 정하는 구간
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';	 

if($fromdate=="")
{
	// $fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "2021-01-01";
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
		  
$process = "전체";  // 기본 전체로 정한다.   
	
$SettingDate = "proDate";

$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc ";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
  
$sum_title=array(); 
$input_sum = 0;
$output_sum = 0;
 
 // 수입, 지출 처리하는 부분  
$sql="select * from ".$DB."." . $tablename . " where proDate between date('2010-01-01') and date('$todate') order by  proDate " ; 
try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			  $num=$row["num"];
 			  $proDate=$row["proDate"];			  			  
 			  $writer=$row["writer"];			  			  
 			  $amount=$row["amount"];			  			  
 			  $memo=$row["memo"];
			  $which=$row["which"];
			  
		if($which=='1') 	  
	          $input_sum += (int)conv_num($amount);
		     else
      		    $output_sum += (int)conv_num($amount);

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
 
$remain_sum = $input_sum - $output_sum ;

$resultText = "총지출(" . number_format($output_sum) . "원) ";
 
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from ".$DB."." . $tablename . " " . $a; 						                         
			       }
	             else { // 각 필드별로 검색어가 있는지 쿼리주는 부분	                                                      							   
							  $sql ="select * from ".$DB.".fund where (" . $SettingDate . " between date('$fromdate') and date('$Transtodate')) and ( (memo like '%$search%') or (writer like '%$search%') ) ";
							  $sql .=" order by " . $SettingDate . " desc, num desc limit $first_num, $scale ";
							  
						      }
               }
if($mode=="") {
							 $sql="select * from ".$DB."." . $tablename . " " . $a; 						                         
                }		
				
$nowday=date("Y-m-d");   // 현재일자 변수지정    		   

	 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
               include '_row.php';
			   
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}     
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();


		$regist_state = $regist_state ?? "1";
		 
			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$proDate) {
                            $date_font="red";
						}
												
								$font="black";							
												
							  
 if($proDate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $proDate = $proDate . $week[ date('w',  strtotime($proDate)  ) ] ;
}	
			 
			?>
		 
	 
<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>">  
		<input type="hidden" id="page" name="page" value="<?=$page?>" >
		<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >

<div class="container">  			

<div class="card mt-2 mb-1">  			
<div class="card-header">  			
	<div class="d-flex mb-4 mt-4 fs-6 justify-content-center">  
	   <?=$title_message?> 
	</div>    			
	<div class="d-flex mb-4 mt-4 fs-6 justify-content-center">  
	   <?=$resultText?> 
	</div>   

    <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  			
		▷ <?= $total_row ?> &nbsp;&nbsp;
		<!-- 기간설정 칸 -->
		 <?php include includePath('setdate.php') ?>		
		<?php
		   if(isset($_SESSION["userid"]) &&  ( $user_name==='소현철' ||  $user_name==='소민지' ||  $user_name==='김보곤')   )
		   {
		  ?>
            &nbsp;&nbsp;
			<button type="button" id="writeBtn" class="btn btn-dark btn-sm" > <i class="bi bi-pencil"></i>  신규 </button>
		  <?php
			}
		  ?> 
		  
		</div>
	</div>   
</div>
	
<div class="card">  			
<div class="card-body justify-content-center align-items-center">  		
  <div class="row"> 
	<div class="col-sm-1 mb-1 mt-1 justify-content-center align-items-center">  </div>   	   
	<div class="col-sm-10 mb-1 mt-1 justify-content-center align-items-center">
      <!-- 모바일 카드 컨테이너 -->
      <div id="mobile-card-container" style="display: none;"></div>
      
      <table class="table table-hover " id="myTable">
	     <thead class="table-primary">
		   <tr>
				<th class=" text-center">번호</th>
				<th class=" text-center">일자</th>            				
				<th class=" text-center">작성자</th>								
				<th class=" text-center">품목</th>
				<th class=" text-center">내역</th>
				<th class=" text-center">금액</th>
				<th class=" text-center">비고</th>
			</tr>
          </thead>
		  <tbody>
	 <?php
		  
		$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번		  
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

                  include '_row.php';		  	

				 if($proDate!="") {
				$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
				$proDate = $proDate . $week[ date('w',  strtotime($proDate)  ) ] ;
			          }
											
					 if($which=='1')
						   {
						   $tmp_word="수입";
						   $font_state="black";
						   }
						   else
						   {
							   $tmp_word="지출";
							   $font_state="red";				   
						   }			  					   					
		?>		   			
			<tr onclick="redirectToView('<?=$num?>')">
				<td class=" text-center"><?=$start_num?></td>
				<td class=" text-center"><?=$proDate?></td>
				<td class="text-center"><?=$writer?></td>
				<td class="text-start"><?=$item?></td>
				<td class="text-start"><?=$memo?></td>
				<td class="text-end"><?=$amount?></td>				
				<td class="text-end"><?=$comment?></td>						
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
 <div class="col-sm-1 mb-1 mt-1 justify-content-center align-items-center">  </div>   	   
  </div>   	   
 

     </div>   
     </div>   
     </div>  
 
	</form>	 
	
<div class="container-fluid">
	<?php require_once(includePath('footer_sub.php')); ?>
</div>
	 
<script> 

var dataTable; // DataTables 인스턴스 전역 변수
var fundpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": false, // 모바일에서 수동 검색 사용
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']],
        "initComplete": function() {
            // 모바일에서 카드 렌더링
            if (window.innerWidth <= 768) {
                setTimeout(function() {
                    renderMobileCards();
                }, 500);
            }
        },
        "drawCallback": function() {
            // 모바일에서 카드 재렌더링
            if (window.innerWidth <= 768) {
                setTimeout(function() {
                    renderMobileCards();
                }, 300);
            }
        }
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('fundpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var fundpageNumber = dataTable.page.info().page + 1;
        setCookie('fundpageNumber', fundpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('fundpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
    
    // 모바일 카드 클릭 이벤트
    $(document).on('click', '.mobile-card', function() {
        var num = $(this).data('num');
        redirectToView(num);
    });
    
    // 창 크기 변경 시 카드/테이블 전환
    $(window).on('resize', function() {
        if (window.innerWidth <= 768) {
            renderMobileCards();
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('fundpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function redirectToView(num) {    	
    var url = "view.php?tablename=" + $("#tablename").val() + "&num=" + num ;          
	customPopup(url, '<?=$title_message ?>', 800, 500); 		    
}

$(document).ready(function(){		
	$("#writeBtn").click(function(){ 		
			var url = "write_form.php?tablename=" + $("#tablename").val();				        
			popupCenter(url, '<?=$title_message ?>', 800, 500); 	
	 });	
});

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('연구전담부서 운영비'); // 다른 페이지에 맞는 menuName을 전달
});

/**
 * 모바일에서 테이블을 카드 형식으로 렌더링
 */
function renderMobileCards() {
    if (window.innerWidth > 768) {
        $('#mobile-card-container').hide();
        return;
    }
    
    $('#mobile-card-container').show();
    $('#mobile-card-container').empty();
    
    // 원본 테이블에서 데이터 읽기
    var rows = $('#myTable tbody tr');
    
    if (rows.length === 0) {
        $('#mobile-card-container').html('<div class="text-center text-muted p-3">데이터가 없습니다.</div>');
        return;
    }
    
    rows.each(function() {
        var $row = $(this);
        var num = $row.attr('onclick');
        if (!num) return;
        
        // onclick에서 num 추출
        var numMatch = num.match(/redirectToView\('(\d+)'\)/);
        if (!numMatch) return;
        var numValue = numMatch[1];
        
        var tds = $row.find('td');
        if (tds.length < 7) return;
        
        var 번호 = escapeHtml($(tds[0]).text().trim());
        var 일자 = escapeHtml($(tds[1]).text().trim());
        var 작성자 = escapeHtml($(tds[2]).text().trim());
        var 품목 = escapeHtml($(tds[3]).text().trim());
        var 내역 = escapeHtml($(tds[4]).text().trim());
        var 금액 = escapeHtml($(tds[5]).text().trim());
        var 비고 = escapeHtml($(tds[6]).text().trim());
        
        // 유효성 검사
        if (!번호 || 번호 === '' || 번호 === 'No data available in table') {
            return;
        }
        
        var cardHtml = '<div class="mobile-card" data-num="' + escapeHtml(numValue) + '">' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">번호:</span>' +
                '<span class="mobile-card-value">' + 번호 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">일자:</span>' +
                '<span class="mobile-card-value">' + 일자 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">작성자:</span>' +
                '<span class="mobile-card-value">' + 작성자 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">품목:</span>' +
                '<span class="mobile-card-value">' + 품목 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">내역:</span>' +
                '<span class="mobile-card-value">' + 내역 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">금액:</span>' +
                '<span class="mobile-card-value">' + 금액 + '</span>' +
            '</div>' +
            '<div class="mobile-card-item">' +
                '<span class="mobile-card-label">비고:</span>' +
                '<span class="mobile-card-value">' + 비고 + '</span>' +
            '</div>' +
        '</div>';
        
        $('#mobile-card-container').append(cardHtml);
    });
}

/**
 * HTML 이스케이프 함수
 */
function escapeHtml(text) {
    if (!text) return '';
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script> 
</body>
</html>
