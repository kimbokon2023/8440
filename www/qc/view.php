<?php
require_once __DIR__ . '/../bootstrap.php';

$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];
	
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
      	
// 첫 화면 표시 문구
$title_message = '장비 점검';     
   
?>
   
<?php include getDocumentRoot() . '/load_header.php' ?>
 
<title> <?=$title_message?> </title>

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
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .card-body {
            padding: 0.75rem 0.5rem !important;
            overflow-x: hidden !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            max-width: 100% !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .card-header {
            padding: 0.75rem 0.5rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .card-footer {
            padding: 0.75rem 0.5rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 제목 최적화 */
        h2, h3 {
            font-size: 1.125rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        /* d-flex 요소 최적화 */
        .d-flex {
            flex-wrap: wrap !important;
        }
        
        .d-flex.justify-content-between,
        .d-flex.justify-content-center,
        .d-flex.justify-content-start,
        .d-flex.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        
        /* 버튼 최적화 */
        .btn {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        .btn-sm {
            font-size: 0.875rem !important;
            padding: 0.5rem !important;
        }
        
        /* 텍스트 요소 최적화 */
        .lead,
        .fw-normal,
        p {
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 텍스트 영역 최적화 */
        textarea.form-control {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            font-size: 0.875rem !important;
            box-sizing: border-box !important;
        }
        
        /* 라벨 최적화 */
        label {
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 이미지 최적화 */
        img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
            margin: 0.25rem 0 !important;
            box-sizing: border-box !important;
        }
        
        /* jQuery DataTables 컨트롤 숨기기 */
        .dataTables_length,
        .dataTables_filter {
            display: none !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important; /* block에서 inline-block으로 변경하여 흐름 유지하되 줄바꿈 가능하게 */
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
        
        /* row와 col 최적화 */
        .row {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        .col,
        [class*="col-"] {
            padding: 0.5rem !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
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
        
        .modal-body {
            padding: 0.75rem 0.5rem !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            flex: 1 1 auto !important;
            -webkit-overflow-scrolling: touch !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
        }
        
        .modal-footer .btn {
            width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        /* SweetAlert2 모달 최적화 */
        .swal2-popup {
            width: 90% !important;
            max-width: 90% !important;
            padding: 1rem !important;
            font-size: 0.875rem !important;
        }
        
        .swal2-title {
            font-size: 1.125rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .swal2-content {
            font-size: 0.875rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .swal2-actions {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .swal2-confirm,
        .swal2-cancel {
            width: 100% !important;
            margin: 0 !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 체크리스트 항목 최적화 */
        .card-body.p-4 {
            padding: 0.75rem 0.5rem !important;
        }
        
        /* 섹션 최적화 */
        section {
            padding: 0.5rem 0 !important;
        }
        
        /* 컨테이너 플루이드 최적화 */
        .container-fluid.py-5 {
            padding: 0.5rem 0 !important;
        }

        /* 테이블 카드화 (jQuery DataTables, Tabulator, Tui Grid 등) */
        table, 
        .tabulator, 
        .tui-grid-container {
            width: 100% !important;
        }

        /* jQuery DataTables 모바일 카드 뷰 변환 */
        table.dataTable,
        table.dataTable tbody,
        table.dataTable tr,
        table.dataTable td {
            display: block !important;
            width: 100% !important;
        }

        table.dataTable thead {
            display: none !important; /* 헤더 숨김 */
        }

        table.dataTable tr {
            margin-bottom: 1rem !important;
            border: 1px solid #ddd !important;
            border-radius: 5px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            background: #fff !important;
            padding: 0.5rem !important;
        }

        table.dataTable td {
            text-align: right !important;
            padding-left: 50% !important;
            position: relative !important;
            border-bottom: 1px solid #eee !important;
        }

        table.dataTable td:last-child {
            border-bottom: none !important;
        }

        table.dataTable td::before {
            content: attr(data-label); /* data-label 속성이 있다면 사용 */
            position: absolute !important;
            left: 10px !important;
            width: 45% !important;
            padding-right: 10px !important;
            white-space: nowrap !important;
            text-align: left !important;
            font-weight: bold !important;
        }
    }
    
    /* PC 환경 최적화 */
    @media (min-width: 769px) {
        .d-flex.justify-content-start .btn,
        .d-flex.justify-content-end .btn,
        .d-flex.align-items-center .btn {
            margin-left: 0.25rem !important;
            margin-right: 0.25rem !important;
        }
    }
</style>

<?php



require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열로 장비점검리스트 불러옴
include "load_DB.php";

// echo $user_name;

$nowday=date("Y-m-d");   // 현재일자 변수지정   
try{  
	 $sql="select * from mirae8440.mymclist  where num=? ";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 
			 $checkdate = $row["checkdate"];		
			 $item = $row["item"];		
			 $term = $row["term"];		
			 $check1 = $row["check1"];		
			 $check2 = $row["check2"];		
			 $check3 = $row["check3"];		
			 $check4 = $row["check4"];		
			 $check5 = $row["check5"];		
			 $check6 = $row["check6"];		
			 $check7 = $row["check7"];		
			 $check8 = $row["check8"];		
			 $check9 = $row["check9"];		
			 $check10 = $row["check10"];		
			 $trouble = $row["trouble"];		
			 $fixdata = $row["fixdata"];	
			 $writer = $row["writer"];	
			 $writer2 = $row["writer2"];	
			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
// 작성자가 없을때는 작성자 생성  
if($writer==null)       
    $writer=$user_name;	
// print $writer;
// print $writer2;
// print $user_name;
  
$question = array();  
  
if($item=='laser01' && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "(칠러) ☞ 물통내부 냉각수 양은 적정선까지 채워져 있는가? ");
array_push($question, "(칠러) ☞ 냉각수는 오염되어 있는지 확인했는가?");
array_push($question, "(칠러) ☞ 에어필터를 분리해 먼지를 청소했는가? ");
array_push($question, "(XY축 자바라) ☞ XY축 자바라부를 청소했는가? ");
array_push($question, "(재받이) ☞ 재받이부는 청소되어 있는가? ");
array_push($question, "(헤드부) ☞ 헤드부와 노출부 분진은 닦았고, 노즐팁은 깨끗한가? ");
array_push($question, "(집진기) ☞ 집진부 재받이는 청소되어 있는가? ");
}  

if($item=='laser01' && $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "(집진기) ☞ 집진부 필터의 오염상태는 확인했는가? ");
array_push($question, "(칠러) ☞ 내부 외부 누수는 없는지 확인 했는가?");
array_push($question, "(칠러) ☞ 배출 라디에이터는 먼지청소를 했는가?");
array_push($question, "(테이블체인저) ☞ 체인부에 구리스를 발랐는가? ");
}
      	
if($item=='laser01' && $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "(XYZ) ☞ (8시간/일 가동시 2개월에 1회) XYZ축에 구리스 주입했는가? ");
array_push($question, "(XYZ) ☞ XY축 자바라내 랙기어에 구리스를 발랐는가? ");
array_push($question, "(칠러) ☞ 물필터 오염상태를 확인하고 청소했는가? ");
array_push($question, "(컴퓨터) ☞ 가공조건, 중요자료를 안전한 컴퓨터에 복사했는가? ");
}
      	
if($item=='laser01' && $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "(작업테이블) ☞ 그리드(살대) 청소를 했는가? " );
array_push($question, "(칠러) ☞ 증류수 교체는 했는가? ");
}
      	
// v-cut기계		
if($item=='vcut01' && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "(바이트) ☞ 바이트날 마모상태는 양호한가? ");
array_push($question, "(테이블) ☞ 작업테이블의 오염 및 파손은 없는가? ");
array_push($question, "(청결) ☞ 장비주변의 청결상태는 양호한가? ");
array_push($question, "(XY이동장치) ☞ 움직이는 부위의 구리스상태는 양호한가? ");
array_push($question, "(조작판) ☞ 조작등은 정상작동하는가? ") ;
array_push($question, "(에어공급) ☞ 콤푸레샤로부터 에어공급은 잘 되는가? ");
}  

if($item=='vcut01' && $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "(바이트) ☞ 바이트날 재고는 적정량을 보유하고 있는가? ");
array_push($question, "(에어공급) ☞ 콤푸레샤로부터 에어공급장치의 결함은 없는가? ");
array_push($question, "(작업페달) ☞ 장비 하단의 작업 패달작동은 양호한가?");
}
      	
if($item=='vcut01' && $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "(에어공급) ☞ 에어압력장치는 양호한가? ");
array_push($question, "(조작램프) ☞ 조작램프는 정상작동하는가? ");
array_push($question, "(프로그램) ☞ 자료 저장 읽기 등 프로그램의 작동은 양호한가? ");
}
      	
if($item=='vcut01' && $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "(구리스주입구) ☞ 장치의 마찰부위의 구리스 주입구 상태는 양호한가? " );
array_push($question, "(전기장치) ☞ 전원공급장치의 전선상태는 양호한가? ");
}
   
// bending01		
if($item=='bending01' && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "(절곡날) ☞ 절곡날 마모상태는 양호한가? ");
array_push($question, "(청결) ☞ 장비주변의 청결상태는 양호한가? ");
array_push($question, "(XY이동장치) ☞ 움직이는 부위의 구리스상태는 양호한가? ");
array_push($question, "(조작판) ☞ 조작등은 정상작동하는가? ") ;
array_push($question, "(부속자재 - 절곡펀치) ☞ 마모상태 확인, 연마 상태는 양호한가? ") ;
array_push($question, "(부속자재 - 절곡다이-v블럭) ☞ 마모상태 확인, 연마 상태는 양호한가? ") ;
}  

if($item=='bending01' && $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "(프로그램) ☞ 데이터 저장/읽기 등 프로그램은 정상작동하는가? ");
array_push($question, "(아답터) ☞ 아답터 조립상태는 양호한가? ");
array_push($question, "(작업페달) ☞ 장비 하단의 작업 패달작동은 양호한가?");
array_push($question, "(부속자재 - 절곡펀치) ☞ 마모상태 확인, 연마 상태는 양호한가? ") ;
array_push($question, "(부속자재 - 절곡다이-v블럭) ☞ 마모상태 확인, 연마 상태는 양호한가? ") ;
}
      	
if($item=='bending01' && $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "(에어공급) ☞ 에어압력장치는 양호한가? ");
array_push($question, "(조작램프) ☞ 조작램프는 정상작동하는가? ");
array_push($question, "(프로그램) ☞ 자료 저장 읽기 등 프로그램의 작동은 양호한가? ");
array_push($question, "(부속자재 - 절곡펀치) ☞ 마모상태 확인, 연마 상태는 양호한가? ") ;
array_push($question, "(부속자재 - 절곡다이-v블럭) ☞ 마모상태 확인, 연마 상태는 양호한가? ") ;
}
      	
if($item=='bending01' && $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "(절곡날밸런스) ☞  절곡날의 좌우밸런스는 잘 나오는가? " );
array_push($question, "(부속자재 - 절곡펀치) ☞ 마모상태 확인, 연마 상태는 양호한가? ") ;
array_push($question, "(부속자재 - 절곡다이-v블럭) ☞ 마모상태 확인, 연마 상태는 양호한가? ") ;
}

// shearing01		
if($item=='shearing01' && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "(절단날) ☞ 절단날 마모상태는 양호한가? ");
array_push($question, "(청결) ☞ 장비주변의 청결상태는 양호한가? ");
array_push($question, "(XY이동장치) ☞ 움직이는 부위의 구리스상태는 양호한가? ");
array_push($question, "(조작판) ☞ 조작등은 정상작동하는가? ") ;
}  

if($item=='shearing01' && $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "(수동프로그램) ☞ 위치조절 프로그램은 정상 작동하는가? ");
array_push($question, "(작업페달) ☞ 장비 하단의 작업 패달작동은 양호한가?");
}
      	
if($item=='shearing01' && $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "(조작램프) ☞ 조작램프는 정상 작동하는가? ");
array_push($question, "(백게이지) ☞ 백게이지 이동은 정상 작동하는가? ");
}
      	
if($item=='shearing01' && $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "(절단밸런스) ☞  절단날의 좌우밸런스는 잘 나오는가? " );
}

// welder01~04		
if(($item=='welder01' || $item=='welder02' || $item=='welder03' || $item=='welder04') && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "(전원) ☞ 전원은 정격전압에 연결되어 있는가? ");
array_push($question, "(전선) ☞ 케이블(전선)의 피복의 벗겨진 부분은 없는가? ");
array_push($question, "(전선) ☞ 케이블(전선)의 용접기와 접속부의 부착, 절연상태는 양호한가?");
array_push($question, "(청결) ☞ 작업장 부근에 기름, 도료, 헝겊 등의 타기 쉬운 물건을 두지 않았는가? ");
array_push($question, "(청결) ☞ 통풍이나 환기는 충분히 이뤄지고 있는가? ");
}  

if(($item=='welder01' || $item=='welder02' || $item=='welder03' || $item=='welder04') &&  $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "(조작판) ☞ 조작등(램프류)은 정상 작동하는가? ") ;
array_push($question, "(조작스위치) ☞ 조작 스위치(버튼)류는 정상 작동하는가? ") ;
}
      	
if(($item=='welder01' || $item=='welder02' || $item=='welder03' || $item=='welder04') &&  $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "(장비안전) ☞ 용접기 본체는 접치가 되어있는가? ");
array_push($question, "(용품비치) ☞ 용접장소에 소화 준비물(소화기,물통 등) 비치되어 있는가? ");
}
      	
if(($item=='welder01' || $item=='welder02' || $item=='welder03' || $item=='welder04') &&  $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "(성능) ☞  용접기 성능(용접상태, 소음 등)은 이상이 없는가? " );
array_push($question, "(관련부품훼손) ☞  용접기 주요부품 및 부속품에 이상은 없는가? " );
}
   
// motor01~04		
if(($item=='motor01' || $item=='motor02' ) && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "(오일수준) ☞ 유압오일 레벨은 양호한가?");
array_push($question, "(오일수준) ☞ 브레이크오일 레벨은 양호한가?");
array_push($question, "(전기전장) ☞ 각종 경고장치는 작동은 양호한가?");
array_push($question, "(전기전장) ☞ 배선 및 휴즈상태는 양호한가?");
array_push($question, "(동작) ☞ 리프트 작동상태는 양호한가?");
array_push($question, "(동작) ☞ 틸트 작동상태는 양호한가?");
array_push($question, "(제어) ☞ 핸들 작동상태는 양호한가?");
array_push($question, "(제동) ☞ 주차브레이크 작동상태는 양호한가?");
}  

if(($item=='motor01' || $item=='motor02' )&&  $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "(구리스주입) ☞ 마스트 및 베어링 구리스 주입은 양호한가? ") ;
array_push($question, "(구리스주입) ☞ 틸트핀 작동부 구리스 주입은 양호한가? ") ;
array_push($question, "(구리스주입) ☞ 각종 조인트 구리스 주입은 양호한가? ") ;
array_push($question, "(유압계통) ☞ 각종 실린더 누유는 없는가? ") ;
array_push($question, "(유압계통) ☞ 각종 펌프 누유는 없는가? ") ;
array_push($question, "(유압계통) ☞ 각종 파이프 및 호스 누유는 없는가? ") ;
}
      	
if(($item=='motor01' || $item=='motor02' ) &&  $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "(타이어) ☞ 타이어 마모량 상태는 양호한가? ");
array_push($question, "(타이어) ☞ 타이어 휠볼트 체결 상태는 양호한가? ");
array_push($question, "(타이어) ☞ 타이어 외관 상태는 양호한가? ");
}
      	
if(($item=='motor01' || $item=='motor02' ) &&  $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "(베터리) ☞  증류수량은 적당한가? " );
array_push($question, "(베터리) ☞  베터리 접지에는 이상 없는가? " );
}

// tapdrill01		
if($item=='tapdrill01' && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "(드릴날) ☞ 드릴날 마모상태는 양호한가? ");
array_push($question, "(청결) ☞ 장비주변의 청결상태는 양호한가? ");
array_push($question, "(XY이동장치) ☞ 움직이는 부위의 구리스상태는 양호한가? ");
array_push($question, "(조작판) ☞ 조작등은 정상작동하는가? ") ;
}  

if($item=='tapdrill01' && $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "(수동/자동레버) ☞ 레바 작동시 드릴회전은 정상 작동하는가? ");
array_push($question, "(높이조절작업대) ☞ 높이 조절작업대는 작동은 양호한가?");
}
      	
if($item=='tapdrill01' && $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "(조작램프) ☞ 조작램프는 정상 작동하는가? ");
array_push($question, "(진동/소음) ☞ 작동시 모터의 이상소음 및 진동은 정상인가? ");
}
      	
if($item=='tapdrill01' && $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "(전선) ☞ 케이블(전선)의 피복의 벗겨진 부분은 없는가? ");
array_push($question, "(모터) ☞  회전모터의 회전량 및 출력은 정상인가? " );
}

// comp01,02 
if(($item=='comp01' || $item=='comp02' ) && $term=='주간')
{	
// 체크리스트 배열저장
array_push($question, "(오일수준) ☞ 피스톤 유압오일 양호한가(폭발위험)?");
array_push($question, "(수분) ☞ 탱크 하부 수분은 양호한가?");
array_push($question, "(밸트장력) ☞ 느슨함이 없이 작동은 양호한가?");
}  

if(($item=='comp01' || $item=='comp02' )&&  $term=='1개월')
{	
// 체크리스트 배열저장
array_push($question, "(위험요소) ☞ 폭발이 가능한 물질이나 환경으로 안전한가??");
array_push($question, "(정리정돈) ☞ 장비주변에 정리정돈은 양호한가?");
}
      	
if(($item=='comp01' || $item=='comp02' ) &&  $term=='2개월')
{	
// 체크리스트 배열저장
array_push($question, "(위험요소) ☞ 폭발이 가능한 물질이나 환경으로 안전한가??");
array_push($question, "(정리정돈) ☞ 장비주변에 정리정돈은 양호한가?");
}
      	
if(($item=='comp01' || $item=='comp02' ) &&  $term=='6개월')
{	
// 체크리스트 배열저장
array_push($question, "(위험요소) ☞ 폭발이 가능한 물질이나 환경으로 안전한가??");
array_push($question, "(정리정돈) ☞ 장비주변에 정리정돈은 양호한가?");
}


$questionNum = count($question);

// Search $mcno_arr for a match with $item
$index = array_search($item, $mcno_arr);

// If a match is found, set $itemstr to the corresponding value in $mcname_arr
if ($index !== false) {
    $itemstr = $mcname_arr[$index];
    $mcmain = $mcmain_arr[$index];
    $mcsub = $mcsub_arr[$index];
}	
      	
?>


<form  id="board_form" name="board_form" method="post"  >

<input type="hidden" name="check1" id="check1" value="<?=$check1?>" >
<input type="hidden" name="check2" id="check2" value="<?=$check2?>" >
<input type="hidden" name="check3" id="check3" value="<?=$check3?>" >
<input type="hidden" name="check4" id="check4" value="<?=$check4?>" >
<input type="hidden" name="check5" id="check5" value="<?=$check5?>" >
<input type="hidden" name="check6" id="check6" value="<?=$check6?>" >
<input type="hidden" name="check7" id="check7" value="<?=$check7?>" >
<input type="hidden" name="check8" id="check8" value="<?=$check8?>" >
<input type="hidden" name="check9" id="check9" value="<?=$check9?>" >
<input type="hidden" name="check10" id="check10" value="<?=$check10?>" >

<!-- proDB.php 전송용 hidden fields -->
<input type="hidden" id="table" name="table" >
<input type="hidden" id="command" name="command" >
<input type="hidden" id="field" name="field" >
<input type="hidden" id="strtmp" name="strtmp" >
<input type="hidden" id="recnum" name="recnum" >
<input type="hidden" id="datanum" name="datanum" >
<input type="hidden" id="fieldarr" name="fieldarr[]" >
<input type="hidden" id="arr" name="arr[]" > 
	
<div class="container-fluid mt-2 mb-2"  >
	
<div class="card mt-3">    		
	<div class="card-body">    		
		<div class="row gx-1 gx-lg-1 align-items-center">                      
				<div class="fs-4 mb-1" id="leftchar">
					  <label class="form-check-label text-primary" for="leftchar">
							 &nbsp;&nbsp; ' <?= htmlspecialchars($itemstr) ?> ' &nbsp;
					  </label>		
							 담당 (정) <?= htmlspecialchars($mcmain) ?> , (부) <?= htmlspecialchars($mcsub) ?> &nbsp;&nbsp; 	&nbsp;&nbsp; 	
					  <button type="button" id="closeBtn" class="btn btn-dark btn-sm"> <ion-icon name="close-outline"> </ion-icon> 창닫기 </button>
						<?php 
							if($user_name=='김보곤' || $user_name=='이경묵')
								print '<button type="button" id="passBtn" class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i> pass </button>';
						?>						 								
				</div>			
				</div>    
		</div>   
</div>				
	
<div class="card mt-3">    		
	<div class="card-body">   	
		<!-- 체크리스트 구현 section-->
		<section class="h-100 gradient-custom">
		  <div class="container-fluid py-5 h-100">
			<div class="row d-flex justify-content-center align-items-center h-100">
			  <div class="col-xl-12">
				<div class="card" style="border-radius: 10px;">		  
				  <div class="card-header px-0 py-0 text-center">
					<h3 class="text-muted mb-3"> <?=$term?> 
					<span style="color: #a8729a;">점검</span>!</h3>
				  </div>
				  <!-- 대분류 시작 -->	
				 <?php
					// 질문 배열이 비어있거나 없는 경우 경고 표시
					if (empty($question)) {
						echo '<div class="alert alert-warning m-3">이 장비/점검주기 조합에 대한 점검 항목이 정의되지 않았습니다.</div>';
					} else {
						// 질문 배열을 순회 (배열에 실제로 있는 항목만)
						for ($i = 0; $i < count($question); $i++) {
							if (!isset($question[$i])) continue;
							
							$questionText = $question[$i];
							$checktmp = 'check' . ($i + 1);
							$checkValue = isset($$checktmp) ? $$checktmp : null;
				   ?>				
				  <div class="card-body p-4">
					<div class="d-flex justify-content-between align-items-center mb-4">
					  <p class="lead fw-normal mb-0" style="color: #a8729a;"> 
					 <?php echo htmlspecialchars($questionText); ?>
					&nbsp;&nbsp;&nbsp;&nbsp; 	<span id="ckname<?php echo $i+1; ?>" style="color:gray;">
					<?php if($checkValue != null) {
						echo htmlspecialchars($checkValue) . ", (점검자) " . htmlspecialchars($writer);
					} else { ?>
					  </span>
							<button type="button" id="ckbtn<?php echo $i+1; ?>" class="btn btn-secondary btn-sm check-btn"  onclick="checklist('<?php echo htmlspecialchars($num); ?>','<?php echo $i+1; ?>');"> 점검완료 </button>
					  <?php } ?>
					  </p>              
					</div>
					</div>
					<?php 
						}
					} ?>
					
				  <!-- 대분류 끝 -->	
					
				 <?php
					 // 절곡기인 경우는 이미지를 출력해주는 부분을 추가한다.
					 if($item=='bending01' )
					 {
						 // 서버/로컬 모두에서 이미지 경로 자동 분기						 
							// 로컬 개발/운영 서버 구분용
							if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1')) {
								$img_base = '/img/bending/';
							} else {
								$img_base = 'http://8440.co.kr/img/bending/';
							}

							$img_rows = [
								['a105.jpg', 'a101_84.jpg', 'a101_78.jpg'],
								['a103.jpg', 'a115.jpg', 'd605_80.jpg'],
								['d605_86.jpg', 'd612.jpg', 'd602.jpg'],
								['d603.jpg']
							];

					foreach ($img_rows as $row) {
						print '<div class="d-flex justify-content-between align-items-center mb-4" style="flex-wrap: wrap;">';
						foreach ($row as $img) {
							// HTTPS 페이지에서는 HTTP를 HTTPS로 변환
							$img_url = $img_base . $img;
							if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
								$img_url = str_replace('http://', 'https://', $img_url);
							}
							printf("<img style='width:100%%;max-width:100%%;height:auto;margin:0.25rem 0;' src='%s' alt='%s'>", htmlspecialchars($img_url), htmlspecialchars($img));
						}
						print '</div>';
					}
				 }

				   ?>				
						  
				  
				  <!-- 대분류 시작 -->
				  <div class="card-header px-0 py-0 text-center">
					<h3 class="text-muted mb-3"> '점검 후 특이사항' 기록</h3>
				  </div>
				  <div class="card-body p-4">		   
					<div class="d-flex justify-content-center align-items-center mb-4">      
					   <textarea class="form-control" style="width:500px;" rows="3" id="trouble" name="trouble" placeholder="특이사항 있을시 기록" ><?=$trouble?></textarea>			   
						&nbsp; 
					   <p class="fw-normal mb-0" style="color: #a8729a;"> 
							<button type="button" class="btn btn-dark btn-sm"  onclick="write_memo('<?=$num?>');">  기록 저장 </button>
					  </p> 
				   </div>			  
				  <!-- 대분류 끝 -->
				  
				  <!-- footer -->
				  <div class="card-footer border-0 px-5 py-4"
					style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
					<h2 class="d-flex align-items-center justify-content-center text-white mb-0"> 
					안전을 최우선으로 생각하는 미래기업
					</h2>
					  <h3 class="d-flex align-items-center justify-content-center text-center text-white mb-0"> 			
					  고객만족 품질경영</h3>			  
						</div>
				</div>
			  </div>
			</div>
		  </div>
		</section>
      </div>
    </div>
  </div>		 
  </div>
  </div>
</form>

	<div id=dummy > </div>
		
		
</body>

</html>

<script>

$(document).ready(function(){
	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});		
	
	$("#closeBtn").click(function(){ 
	   opener.location.reload();
	   window.close();		
	});		
	
	$("#passBtn").click(function(){ 
		document.querySelectorAll('.check-btn').forEach(function(button) {
			button.click();
		});	
	});			
		
// 일시로 만든 함수 각 장비의 체크리스트 자료 db 생성을 위해서		
	$("#doBtn").click(function(){ 
	//  $("#dummy").load("../dumproDB.php");	
			
			
		});		

	
// 브라우저 강제로 닫을때 이벤트
$(window).bind("beforeunload", function (e){	opener.location.reload();  });
	
	// // order 버튼 클릭시
// $("#orderBtn").click(function(){  

});

// 점검후 특이사항 기록하기
function write_memo(num)
{

        // DB 수정
		       $("#table").val('mymclist');
		       $("#command").val('update');
		       // $("#command").val('insert');
		       // $("#command").val('delete');  // insert, delete, update
		       $("#field").val('trouble');
		       $("#strtmp").val($("#trouble").val());
		       $("#recnum").val(num);
		       // $("#arr").val('free');

		   // data저장을 위한 ajax처리구문
			$.ajax({
				url: "../proDB.php",
				type: "post",
				data: $("#board_form").serialize(),
				dataType:"json",
				success : function( data ){
					console.log( data);
				},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
				}
			   });

		  $('#myModal').modal('show');
}


function checklist(num, whichone)
 {
         var writer = '<?php echo $writer; ?>' ;
         var writer2 = '<?php echo $writer2;?>';
         var user_name = '<?php echo $user_name; ?>';
         var question = '<?php echo $questionNum; ?>';

		console.log(writer);
		console.log(user_name);
		console.log(question);

  if(writer == user_name ||  writer2 == user_name || user_name==='김보곤' || user_name==='이경묵' ) // 로그인 이름과 같을때는 기록한다.
	{
			// DB 수정
			   $("#table").val('mymclist');
			   $("#command").val('update');
			   // $("#command").val('insert');
			   // $("#command").val('delete');  // insert, delete, update
			   $("#field").val('check'+ whichone);
			   $("#strtmp").val(getToday());
			   $("#recnum").val(num);
			   $("#arr").val('free');

			   // check값 form의 변수에 넣어주기
			   $('#check'+ whichone).val(getToday());

			   // data저장을 위한 ajax처리구문
				$.ajax({
					url: "../proDB.php",
					type: "post",
					data: $("#board_form").serialize(),
					dataType:"json",
					success : function( data ){
						console.log( data);
					},
					error : function( jqxhr , status , error ){
						console.log( jqxhr , status , error );
					}
				   });		
				   
	// 각 주간점검/1개월 점검등 문항을 전부 check했을 경우 완료 done 처리하기
	// 조건 문항수에 맞는 check가 되었는지 확인한다
	// 10개 문항을 기준으로 검색해서 처리한다.
	   var sum = 0;
	   for (i=1; i<=10 ; i++ )
	   {
		  if($('#check'+ i).val() != '' )
				sum += 1;
	   }
	   console.log('질문수 '  + question);
	   console.log('답변수 '  + sum);
	   if(question == sum)
	   {
			// 체크문항과 같으면 DB 완료로 수정하기
			   $("#table").val('mymclist');
			   $("#command").val('update');
			   // $("#command").val('insert');
			   // $("#command").val('delete');  // insert, delete, update
			   $("#field").val('done');
			   $("#strtmp").val('1');
			   $("#recnum").val(num);
			   $("#arr").val('free');

			   // data저장을 위한 ajax처리구문
				$.ajax({
					url: "../proDB.php",
					type: "post",
					data: $("#board_form").serialize(),
					dataType:"json",
					success : function( data ){
						console.log( data);
					},
					error : function( jqxhr , status , error ){
						console.log( jqxhr , status , error );
					}
				   });
		  }
			   
				   // 화면 변경하기 
				  $("#ckname" + whichone).html(getToday() + ' ' + '(작성자) '+ user_name); 
				  // 버튼삭제
				  $("#ckbtn" + whichone).remove();			  
	}
	
  else
  {
	      tmp='점검자와 이름이 다릅니다. 확인바랍니다.';
		
		  $('#alertmsg').html(tmp); 
		  
		  $('#myModal').modal('show');  
  }

				
}

</script>
