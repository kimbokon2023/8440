<?php
session_start(); 
$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];
	
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
      	
// 첫 화면 표시 문구
$title_message = '장비 점검';     
   
?>
   
<?php include getDocumentRoot() . '/load_header.php' ?>
 
<title> <?=$title_message?> </title>   

<?php

 // 모바일이면 특정 CSS 적용
if ($chkMobile) {
    echo '<style>
        body, table th, table td, .form-control, span {
            font-size: 25px;
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



require_once("../lib/mydb.php");
$pdo = db_connect();

// 배열로 장비점검리스트 불러옴
include "load_DB.php";


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
	
<?php if($chkMobile) { ?>	
<div class="container-fluid mt-2 mb-2"  >
<?php } if(!$chkMobile) { ?>	
<div class="container mt-2 mb-2"  >   
<?php  } ?>		
	
<div class="card mt-3">    		
	<div class="card-body">    		
		<div class="row gx-1 gx-lg-1 align-items-center">                      
				<div class="fs-4 mb-1" id="leftchar">
					  <label class="form-check-label text-primary" for="leftchar">
							 &nbsp;&nbsp; ' <?=$itemstr?> ' &nbsp;
					  </label>		
							 담당 (정) <?=$writer?> , (부) <?=$writer2?> &nbsp;&nbsp; 	&nbsp;&nbsp; 	
					  <button type="button" id="closeBtn" class="btn btn-dark btn-sm"> <ion-icon name="close-outline"> </ion-icon> 창닫기 </button>
						<?php 
							if($user_name=='김보곤' || $user_name=='이경묵')
								print '<button type="button" id="passBtn" class="btn btn-primary btn-sm"  >  <ion-icon name="color-wand-outline"></ion-icon> pass </button>';
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
				 <?
					for ($i=0;$i<count($question);$i++)
					{
						$checktmp = 'check' . (string)($i+1) ;
				   ?>				
				  <div class="card-body p-4">
					<div class="d-flex justify-content-between align-items-center mb-4">
					  <p class="lead fw-normal mb-0" style="color: #a8729a;"> 
					 <?=$question[$i]?>
					&nbsp;&nbsp;&nbsp;&nbsp; 	<span id="ckname<?=$i+1?>" style="color:gray;">
					<? if($$checktmp !=null)
						print $$checktmp . ", (점검자) " . $writer ;
					  else  { ?>
					  </span>
							<button type="button" id="ckbtn<?=$i+1?>" class="btn btn-secondary btn-sm check-btn"  onclick="checklist('<?=$num?>','<?=$i+1?>');"> 점검완료 </button>
					  <? } ?>
					  </p>              
					</div>
					</div>
					<? } ?>
					
				  <!-- 대분류 끝 -->	
					
				 <?php
					 // 절곡기인 경우는 이미지를 출력해주는 부분을 추가한다.
					 if($item=='bending01' )
					 {
						 print '<div class="d-flex justify-content-between align-items-center mb-4">';
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/a105.jpg'>";
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/a101_84.jpg'>";
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/a101_78.jpg'>";
						  print '</div>';
						 print '<div class="d-flex justify-content-between align-items-center mb-4">';				  
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/a103.jpg'>";
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/a115.jpg'>";
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/d605_80.jpg'>";
						print '</div>';
						 print '<div class="d-flex justify-content-between align-items-center mb-4">';				  
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/d605_86.jpg'>";
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/d612.jpg'>";
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/d602.jpg'>";
						print '</div>';
						 print '<div class="d-flex justify-content-between align-items-center mb-4">';				  
						  print "<img style='width:25%;height:auto' src='http://8440.co.kr/img/bending/d603.jpg'>";
						print '</div>';
						 
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
	<!-- ajax 전송으로 DB 수정 -->
	<? include "../formload.php"; ?>	
	
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
		
	// console.log(getDayOfWeek('2022-03-26'));
	// const startDate = '2022-03-26';
	// const lastDate = '2022-10-31';
	// var datestr = getDatesStartToLast(startDate, lastDate);
	// // console.log(datestr);
    // for(i=0;i<datestr.length;i++)
	// {
		// if(getDayOfWeek(datestr[i])=='금')
		 // {
        // // DB 추가	
		       // $("#table").val('mymclist');
		       // //$("#command").val('update');
		        // $("#command").val('insert');
		       // // $("#command").val('delete');  // insert, delete, update
		       // $("#field").val('checkdate');
		       // $("#strtmp").val(datestr[i]);
		       // // $("#recnum").val(num);
		       // // $("#arr").val('free');
		   
				   // // data저장을 위한 ajax처리구문
					// $.ajax({
						// url: "../proDB.php",
						// type: "post",		
						// data: $("#Form1").serialize(),
						// dataType:"json",
						// success : function( data ){
							// console.log( data);
						// },
						// error : function( jqxhr , status , error ){
							// console.log( jqxhr , status , error );
						// } 			      		
					   // });	
		     	// }  // end of if			   
			// } // end of for
			
			
			
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
				data: $("#Form1").serialize(),
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
					data: $("#Form1").serialize(),
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
					data: $("#Form1").serialize(),
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
