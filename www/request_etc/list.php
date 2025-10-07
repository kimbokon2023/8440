<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
require_once getDocumentRoot() . '/vendor/autoload.php';
require_once(includePath('lib/mydb.php'));

// 서비스 계정 JSON 파일 경로
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';	

// Google Drive 클라이언트 설정
$client = new Google_Client();
$client->setAuthConfig($serviceAccountKeyFile);
$client->addScope(Google_Service_Drive::DRIVE);

// Google Drive 서비스 초기화
$service = new Google_Service_Drive($client);

// 특정 폴더 확인 함수
function getFolderId($service, $folderName, $parentFolderId = null) {
    $query = "name='$folderName' and mimeType='application/vnd.google-apps.folder' and trashed=false";
    if ($parentFolderId) {
        $query .= " and '$parentFolderId' in parents";
    }

    $response = $service->files->listFiles([
        'q' => $query,
        'spaces' => 'drive',
        'fields' => 'files(id, name)'
    ]);

    return count($response->files) > 0 ? $response->files[0]->id : null;
}

// Google Drive에서 파일 썸네일 검사 및 반환
function getThumbnail($fileId, $service) {
    try {
        $file = $service->files->get($fileId, ['fields' => 'thumbnailLink']);
        return $file->thumbnailLink ?? null; // 썸네일 URL이 있으면 반환, 없으면 null
    } catch (Exception $e) {
        error_log("썸네일 가져오기 실패: " . $e->getMessage());
        return null; // 실패 시 null 반환
    }
}

 if(!isset($_SESSION["level"]) || $level>8) {
	     $_SESSION["url"]='https://8440.co.kr/request_etc/list.php' ; 		   
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");         
         exit;
   }       
$title_message = '부자재 구매';     
?> 
<?php include getDocumentRoot() . '/load_header.php'; ?> 
<title> <?=$title_message?>  </title>  
<style>
#showextract {
	display: inline-block;
	position: relative;
}
		
#showextractframe {
    display: none;
    position: absolute;
    width: 800px;
    z-index: 1000;
    left: 50%; /* 화면 가로축의 중앙에 위치 */
    top: 110px; /* Y축은 절대 좌표에 따라 설정 */
    transform: translateX(-50%); /* 자신의 너비의 반만큼 왼쪽으로 이동 */
}
#autocomplete-list {
	border: 1px solid #d4d4d4;
	border-bottom: none;
	border-top: none;
	position: absolute;
	top: 87%;
	left: 65%;
	right: 30%;
	width : 10%;
	z-index: 99;
}
.autocomplete-item {
	padding: 10px;
	cursor: pointer;
	background-color: #fff;
	border-bottom: 1px solid #d4d4d4;
}
.autocomplete-item:hover {
	background-color: #e9e9e9;
}
</style>   
</head>		 
<body>
<?php
$tablename = 'request_etc';
 if(!$chkMobile) 
{ 	
	require_once(includePath('myheader.php')); 
}

 // 모바일이면 특정 CSS 적용
if ($chkMobile) {
    echo '<style>
        table th, table td, h4, .form-control, span {
            font-size: 22px;
        }
         h4 {
            font-size: 40px; 
        }
		.btn-sm {
        font-size: 30px;
		}
    </style>';
}
 
include "_request.php"; 
	  
$pdo = db_connect();


// 현재 날짜
$currentDate = date("Y-m-d");

// fromdate 또는 todate가 빈 문자열이거나 null인 경우
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = date("Y-m-d", strtotime("-3 months", strtotime($currentDate))); // 6개월 이전 날짜
    $todate = $currentDate; // 현재 날짜
	$Transtodate = $todate;
} else {
    // fromdate와 todate가 모두 설정된 경우 (기존 로직 유지)
    $Transtodate = $todate;
}
			  
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 $find=$_REQUEST["find"];
 

$SettingDate="registdate ";

$Andis_deleted =  " AND (is_deleted IS NULL or is_deleted='0')  AND eworks_item='부자재구매' ";
$Whereis_deleted =  " Where  (is_deleted IS NULL or is_deleted='0')  AND eworks_item='부자재구매' ";	

$common="   where " . $SettingDate . " between date_format('$fromdate', '%Y-%m-%d 00:00:00') and date_format('$Transtodate', '%Y-%m-%d 23:59:59') " . $Andis_deleted . " order by " ;
$a= $common . " num desc ";    //내림차순 전체
  
 // 전체합계(입고부분)를 산출하는 부분 
$sum_title=array(); 
$sum=array();
$num_arr = array();
$item_arr = array();
$supplier_arr = array();
$company_arr = array();

$sql="select * from ".$DB.".eworks " . $a; 	
 
 $recount = 0 ;
 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

		$num=$row["num"];			  
		include '_row.php';		
		 
		$tmp=$steel_item . $spec;			  
		$num_arr[$recount] = $num;
		$item_arr[$recount] = $steel_item;
		$supplier_arr[$recount] = $supplier;
		$company_arr[$recount] = $company;
		$recount++;	
		
		for($i=1;$i<=$rowNum;$i++) {			
			  $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='1' and $tmp==$sum_title[$i])
					$sum[$i]=$sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
				// $sum[$i]=(float)-1;				
				   }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


 // 전체합계(출고부분)를 처리하는 부분 

$sql="select * from mirae8440.eworks " . $a; 	 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
			  
			   include '_row.php';
			  
			  $tmp=$steel_item . $spec;
	
        for($i=1;$i<=$rowNum;$i++) {
			 			  
 			  
	          $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='2' and $tmp==$sum_title[$i])
				    $sum[$i]=$sum[$i] - (int)$steelnum;			
		           }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  



// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search);    
  
  if($mode=="search"){
		  if($search==""){			  
			    if($done_check_val=='1')  // 입고완료제외인 경우
				     {
								$common="   where ( " . $SettingDate . " between date('$fromdate') and date('$Transtodate') ) and which<>'3' " . $Andis_deleted . "  order by " ;
								$a = $common . " num desc ";    //내림차순 전체							 
							 $sql="select * from mirae8440.eworks " . $a; 	
					 }
					else
							{
									 $sql="select * from mirae8440.eworks " . $a; 					
							}						
					
			       }
             elseif($search!="") { 
			    if($done_check_val=='1')  // 입고완료제외인 경우			 
			          	{			 
							  $sql ="select * from mirae8440.eworks where ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
							  $sql .="or (steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (first_writer like '%$search%') or (supplier like '%$search%')  or (payment like '%$search%')  or (request_comment like '%$search%')) and (which<>'3')  " . $Andis_deleted . "  order by num desc ";
						}
								else
								{
										  $sql ="select * from mirae8440.eworks where ((outdate like '%$search%')  or (replace(outworkplace,' ','') like '%$search%' ) ";
										  $sql .="or (steel_item like '%$search%') or (spec like '%$search%') or (company like '%$search%')  or (first_writer like '%$search%') or (payment like '%$search%')   or (supplier like '%$search%') or (request_comment like '%$search%') )  " . $Andis_deleted . " order by num desc  ";										 
								}				
						}

               }
  if($mode=="") {
							 $sql="select * from mirae8440.eworks " . $a; 						                         
                }		
				         
   
$nowday=date("Y-m-d");   // 현재일자 변수지정   

$dateCon =" AND between date('$fromdate') and date('$Transtodate') " ;


 // print $sql;	
 
 // // search 모드가 아닐때는 100개로 데이터 제한
// if($mode!=='search')  
	// $sql .= ' limit 0, 50';

 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
 			  $outdate=$row["outdate"];			  
			  
			  include '_row.php';

			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();
		
		if($regist_state==null)
			 $regist_state="1";
		 
			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$outdate) {
                            $date_font="color-green";
						}
						
								$font="color-black";
							
							switch ($regist_state) {
								case   "1"     :  $font_state="black"; $regist_word="등록"; break;
								case   "2"     :  $font_state="red"  ; $regist_word="접수"; break;	
								case   "3"     :  $font_state="blue"  ; $regist_word="완료"; break;	
								default:  $regist_word="등록"; break;
							}								
							
	 if($outdate!="") {
		$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
		$outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
	}	

?>

<form name="board_form" id="board_form"  method="post" action="list.php?mode=search">    
			<input type="hidden" id="done_check_val" name="done_check_val" value="<?=$done_check_val?>" >
			<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
			<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
			<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 				
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
			<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >	
						
<div class="container-fluid">  	
		<div class="card mt-2">
			<div class="card-body">
				<div class="d-flex mb-3 mt-2 justify-content-center align-items-center">  
					<h4> <?=$title_message?> </h4>  
				</div>	
			<div class="d-flex mb-1 mt-1 justify-content-center align-items-center">  		
				<!-- Replace the checkbox code with the Bootstrap-styled button -->					
					<?php
							
						if($done_check_val==='0')   
							   print '<button class="btn btn-dark  btn-sm  checktask " type="button"   > 입고완료 제외 </button>  &nbsp;&nbsp;';
							else
								print '<button class="btn btn-outline-dark  btn-sm  checktask " type="button"   > 입고완료 포함 </button>  &nbsp;&nbsp;';	
						print '<span > ▷ ' .  $total_row . '&nbsp;&nbsp; </span> ' ;
							
					?>
											   
			<!-- 기간부터 검색까지 연결 묶음 start -->
			<span id="showdate" class="btn btn-dark btn-sm " > 기간 </span>	&nbsp; 
				<select name="dateRange" id="dateRange" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
					<?php
					$dateRangeArray = array('최근3개월','최근6개월', '최근1년', '최근2년','직접설정','전체');
					$savedDateRange = $_COOKIE['dateRange'] ?? ''; // 쿠키에서 dateRange 값 읽기

					foreach ($dateRangeArray as $range) {
						$selected = ($savedDateRange == $range) ? 'selected' : '';
						echo "<option $selected value='$range'>$range</option>";
					}
					?>
				</select>			
			<div id="showframe" class="card">
				<div class="card-header " style="padding:2px;">
					<div class="d-flex justify-content-center align-items-center">  
						기간 설정
					</div>
				</div>
				<div class="card-body">
					<div class="d-flex justify-content-center align-items-center">  	
						<button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange"   onclick='alldatesearch()' > 전체 </button>  
						<button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange"   onclick='pre_year()' > 전년도 </button>  
						<button type="button" id="three_month" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='three_month_ago()' > M-3월 </button>
						<button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='prepre_month()' > 전전월 </button>	
						<button type="button" id="premonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='pre_month()' > 전월 </button> 						
						<button type="button" class="btn btn-outline-danger btn-sm me-1 change_dateRange "  onclick='this_today()' > 오늘 </button>
						<button type="button" id="thismonth" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_month()' > 당월 </button>
						<button type="button" id="thisyear" class="btn btn-dark btn-sm me-1 change_dateRange "  onclick='this_year()' > 당해년도 </button> 
					</div>
				</div>
			</div>		
			   <input type="date" id="fromdate" name="fromdate" size="12"  class="form-control"   style="width:100px;" value="<?=$fromdate?>" placeholder="기간 시작일">  &nbsp;   ~ &nbsp;  
			   <input type="date" id="todate" name="todate" size="12"   class="form-control"   style="width:100px;" value="<?=$todate?>" placeholder="기간 끝">  &nbsp;     </span> 
			   &nbsp;				   
				<?php if($chkMobile) { ?>
						</div>
				<div class="d-flex justify-content-center align-items-center">  	
			<?php } ?>
			<select id="find" name="find" class="form-select w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
				<?php	
				$findarr=array('전체','입고','출고','공급처');

				   for($i=0;$i<count($findarr);$i++) {
						 if($find==$findarr[$i]) 
									print "<option selected value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
							 else   
					   print "<option value='" . $findarr[$i] . "'> " . $findarr[$i] .   "</option>";
				   } 		   
		      	?>				   
				</select>				
			<div class="inputWrap">
				<input type="text" id="search" name="search" value="<?=$search?>" autocomplete="off"  class="form-control" style="width:150px;" > &nbsp;			
				<button class="btnClear"></button>
			</div>				
			<div id="autocomplete-list">
			</div>
			 &nbsp;			 
				<button type="button" id="searchBtn" class="btn btn-dark  btn-sm "  > <i class="bi bi-search"></i>  </button>	&nbsp;&nbsp;
			    <button type="button" class="btn btn-dark  btn-sm me-1" id="writeBtn"> <i class="bi bi-pencil-fill"></i> 신규  </button> 	     
				<button  type="button" id="rawmaterialBtn"  class="btn btn-dark btn-sm" > <i class="bi bi-list"></i> 재고 </button> &nbsp;				 
		</div>
		</div>
      </div>	
	<style>
	th {
		white-space: nowrap;
	}
	</style>		  
<div class="card mb-2">
<div class="card-body">	  	  
   <div class="table-responsive"> 	
   <table class="table table-hover " id="myTable">
    <thead class="table-primary">
      <tr>
        <th class="text-center" scope="col" style="width:4%;">번호</th>
        <th class="text-center" scope="col" style="width:80px;">접수 </th>
		<th class=" text-center" style="width:4%;">  납기</th>   
		<th class=" text-center" style="width:4%;">  완료 </th>     <!-- 완료일 -->		
        <th class="text-center" scope="col"> 진행상태 </th>
        <th class="text-center" scope="col"> 요청인</th>
        <th class="text-center" scope="col"> 구매 물품명</th>
        <th class="text-center" scope="col"> 규격</th>        
        <th class="text-center" scope="col"> 수량</th>
        <th class="text-center" scope="col"> 공급처</th>
        <th class="text-center" scope="col"> 구매방법</th>
        <th class="text-center" scope="col"> 비고</th>
      </tr>
    </thead>
	
    <tbody>
      <?php
      if ($page <= 1)
        $start_num = $total_row; // 페이지당 표시되는 첫번째 글순번
      else
        $start_num = $total_row - ($page - 1) * $scale;	
	//  print $sql;
      while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        include '_row.php';
		
        // if ($registdate != "") {
          // $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
          // $registdate = $registdate . $week[date('w', strtotime($registdate))];
        // }
		
		echo '<tr style="cursor:pointer;" data-id="'.  $num . '" onclick="redirectToView(' . $num . ')">';
      ?>
		  <td class="text-center"><?= $start_num ?></td>
            <?php				
				// DateTime 객체 생성
				$dateTime1 = new DateTime($registdate);
				// 날짜 형식을 YYYY-MM-DD로 변환
				$formattedDate = $dateTime1->format('Y-m-d');
				
				$formattedDate_outdate ='';
				if(isNotNull($outdate))
				{
					$dateTime2 = new DateTime($outdate);
					// 날짜 형식을 YYYY-MM-DD로 변환
					$formattedDate_outdate = $dateTime2->format('m-d');
				}
				$formattedDate_indate ='';
				if(isNotNull($indate))
				{
					$dateTime3 = new DateTime($indate);
					// 날짜 형식을 YYYY-MM-DD로 변환
					$formattedDate_indate = $dateTime3->format('m-d');
				}
		  ?>

		<td class="text-center" data-order="<?= $registdate ?>">
			<?=$formattedDate?>
		</td>

		<td class="text-center" data-order="<?= $outdate ?>">
		<?= $formattedDate_outdate ?>
		</td>          

		<td class="text-center" data-order="<?= $indate ?>">
		<?= $formattedDate_indate ?>
		</td>
			<?php
			if ($which == '') $which = '1';
			switch ($which) {
			  case "1":
				$tmp_word = "요청";
				$font_state = "text-primary";
				break;
			  case "2":
				$tmp_word = "발주보냄";
				$font_state = "text-danger";
				break;
			  case "3":
				$tmp_word = "입고완료";
				$font_state = "text-secondary";
				break;
			  default:
				break;
			}			
			?>
			
		  <td class="text-center <?= $font_state ?>"><?= $tmp_word ?></td>
		  
          <td class="text-center">
		   <?php
				$pattern = "/^[가-힣]+/";
				
				preg_match($pattern, $first_writer, $matches);
				$tmpStr = $matches[0]; // '이미래' 출력
		    ?>			
          <?= $tmpStr ?> </td>
          <td class="text-center color-brown"><?= $outworkplace ?></td>
          <td class="text-center color-brown"><?= $spec ?></td>
		  <!-- <td class="text-center thumbnail-cell"></td> <!--  이미지 썸네일 -->
          <td class="text-center color-red"><?= $steelnum ?></td>
          <td class="text-center"><?= $supplier ?></td>
		<td class="text-center">
		<?php
			if ($payment == '법인카드') {
			echo '<span class="text-primary">법인카드</span>';
			} elseif ($payment == '세금계산서') {
			echo '<span class="text-danger">세금계산서</span>';
			}
			?>
		</td>
          <td><?= $request_comment ?></td>
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

</form>	 
   

<!-- ajax 전송으로 DB 수정 -->
<? include "../formload.php"; ?>	
   
<div class="container-fluid">
<? include '../footer_sub.php'; ?>
</div>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var requestetcpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('requestetcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var requestetcpageNumber = dataTable.page.info().page + 1;
        setCookie('requestetcpageNumber', requestetcpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('requestetcpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('requestetcpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}

function blinker() {
	$('.blinking').fadeOut(500);
	$('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);

$(document).ready(function() {
    // Event listener for keydown on #search
    $("#search").keydown(function(event) {
        // Check if the pressed key is 'Enter'
        if (event.key === "Enter" || event.keyCode === 13) {
            // Prevent the default action to stop form submission
            event.preventDefault();
            // Trigger click event on #searchBtn
            $("#searchBtn").click();
        }
    });
	
	// 자재현황 클릭시
	$("#rawmaterialBtn").click(function(){ 			
		 popupCenter('/ceiling/list_part_table.php?menu=no'  , '부자재현황보기', 1050, 950);	
	});	

});


$(document).ready(function() { 

// 입고완료 처리부분 버튼설계
	$(".checktask").click(function() {	  
      // 체크박스가 선택되어 있으면 페이지 리로드	  
      $("#sortof").val('');
      $("#cursort").val('');
      
	  var check = $("#done_check_val").val();		 
	  
	  if(Number(check) === 1)
		$("#done_check_val").val('0');		 
	    else
			$("#done_check_val").val('1');		 
		
	  $("#board_form").submit();
  });

	$("#writeBtn").click(function(){ 		
		var tablename = $("#tablename").val();			
		var url = "write_form.php?tablename=" + tablename ; 

		customPopup(url, '부자재 구매 등록', 1400, 800); 		
	 });	 


$("#searchBtn").click(function() { 
    // 페이지 번호를 1로 설정
    currentpageNumber = 1;
    setCookie('currentpageNumber', currentpageNumber, 10); // 쿠키에 페이지 번호 저장

	// Set dateRange to '전체' and trigger the change event
	$('#dateRange').val('전체').change();
    document.getElementById('board_form').submit();
});

}); 


$(document).ready(function() {

    // 쿠키에서 dateRange 값을 읽어와 셀렉트 박스에 반영
    var savedDateRange = getCookie('dateRange');
    if (savedDateRange) {
        $('#dateRange').val(savedDateRange);
    }

    // dateRange 셀렉트 박스 변경 이벤트 처리
    $('#dateRange').on('change', function() {
        var selectedRange = $(this).val();
        var currentDate = new Date(); // 현재 날짜
        var fromDate, toDate;

        switch(selectedRange) {
            case '최근3개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 3));
                break;
            case '최근6개월':
                fromDate = new Date(currentDate.setMonth(currentDate.getMonth() - 6));
                break;
            case '최근1년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;
            case '최근2년':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 2));
                break;
            case '직접설정':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 1));
                break;   
            case '전체':
                fromDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 20));
                break;            
            default:
                // 기본 값 또는 예외 처리
                break;
        }

        // 날짜 형식을 YYYY-MM-DD로 변환
        toDate = formatDate(new Date()); // 오늘 날짜
        fromDate = formatDate(fromDate); // 계산된 시작 날짜

        // input 필드 값 설정
        $('#fromdate').val(fromDate);
        $('#todate').val(toDate);
		
		var selectedDateRange = $(this).val();
       // 쿠키에 저장된 값과 현재 선택된 값이 다른 경우에만 페이지 새로고침
        if (savedDateRange !== selectedDateRange) {
            setCookie('dateRange', selectedDateRange, 30); // 쿠키에 dateRange 저장
			document.getElementById('board_form').submit();      
        }		
		
		
    });
});

function redirectToView(num) {    
    var tablename = $("#tablename").val();
    	
    var url = "view.php?mode=view&num=" + num         
        + "&tablename=" + tablename;   
		
	customPopup(url, '원자재 구매', 1200, 800); 	    
}

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('부자재 구매'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>