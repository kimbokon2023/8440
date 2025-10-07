<?php
if(!isset($_SESSION))      
        session_start(); 
	$user_name= $_SESSION["name"];
	$user_id= $_SESSION["userid"];
	

  require_once("../lib/mydb.php");
  $pdo = db_connect();
  
  // php에서 자바스크립에서 저장한 JSON방식의 쿠키를 가져와서 사용할려면 아래와 같이 json_decode해야 한다. var_dump로 배열내용 확인가능 
  $cookie =  json_decode($_COOKIE["ordercart"]); 
    	
?>

<!DOCTYPE html>
    <head>        
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>금속 쇼핑몰 (주문하기) </title> <div id=cookiedisplay> </div>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />        
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>		
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
		
		
		<!-- Core theme CSS (includes Bootstrap)-->			
        <link href="./css/styles.css" rel="stylesheet" />		

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="./css/bootstrapmodal.css" rel="stylesheet" />					  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

    </head>		
	

  <style>
    /* body {
      min-height: 100vh;

      background: -webkit-gradient(linear, left bottom, right top, from(#92b5db), to(#1d466c));
      background: -webkit-linear-gradient(bottom left, #92b5db 0%, #1d466c 100%);
      background: -moz-linear-gradient(bottom left, #92b5db 0%, #1d466c 100%);
      background: -o-linear-gradient(bottom left, #92b5db 0%, #1d466c 100%);
      background: linear-gradient(to top right, #92b5db 0%, #1d466c 100%);
    } */

    .input-form {
      max-width: 680px;

      margin-top: 15px;
      padding: 20px;

      background: #fff;
      -webkit-border-radius: 10px;
      -moz-border-radius: 10px;
      border-radius: 10px;
      -webkit-box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.15);
      -moz-box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.15);
      box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.15)
    }
  </style>

<body id="page-top">

<div class="container">  
	
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">주문전송 알림</h4>
        </div>
        <div class="modal-body">		
		   <div class="fs-1 mb-5 justify-content-center" >
		  주문이 완료되었습니다. <br> 
		   <br> 
		  이용해 주셔서 감사합니다.                         	  
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-3 px-lg-1">
                <a class="navbar-brand fs-1 justify-content-center" href="#!"> </a>                
                
                    <form class="d-flex">							
                            <button type="button" id="addcart"   class="btn btn-outline-dark"  onclick="location.href='./cart.php'"> 
                                <i class="bi-cart-fill me-1"></i>
                                장바구니                           </button>	&nbsp;&nbsp; 	
                        <button class="btn btn-outline-dark" type="button"  onclick="location.href='./index.php'">
                             <i class="fa-solid fa-house-user"></i>
                            작품쇼핑몰 
                            
                        </button>
						<? 
						  if($user_name=='김보곤')
						  {
						    print '&nbsp;&nbsp;   <button class="btn btn-outline-dark" type="button" onclick="javascript:movetolist();">
                            <i class="bi-tool-fill me-1"></i>
                            아이템등록
                        </button> ';													?>
						
						    &nbsp;&nbsp;   <button class="btn btn-outline-dark" type="button" onclick="location.href='admin.php'">
                            <i class="bi-tool-fill me-1"></i>
                            대쉬보드
                        </button> 
						<?
							}						
						?>

                    </form>
                </div>            
        </nav>

<form  id="board_form" name="board_form" method="post" action="registerorder.php?mode=new"  enctype="multipart/form-data" > 			
<!-- 주문/결재 section-->
<section class="py-0">   
         <div class="container px-0 px-lg-1 mt-3">    		
                <div class="row gx-1 gx-lg-1 align-items-center">                      
                        <div class="fs-1 mb-1 border border-light" id=leftchar>
							  <label class="form-check-label" for="leftchar">
									&nbsp;&nbsp;  주문/결재
							  </label>				
						</div>						                 
                      					
						</div>    
                    </div>   
</div>						
</section>
		
<!-- shopcart items section-->
<section class="py-1 bg-light">
<div class="container px-1 px-lg-1 mt-2">     
<?

//var_dump($cookie);
// 자료수 구하기
$recordnum = $cookie!=null ? count($cookie) : 0 ;
//print '레코드 수 : ' . $recordnum;

$deliveryfeenum = 0;

$count = 1;

$totalamount = 0;

$num_arr = array();
$saleprice_arr = array();

if($cookie!=null) // 쿠키가 있을때만 실행
foreach ($cookie as $coo) {

 try{
   $sql = "select * from mirae8440.shopitem order by dporder asc";

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		      $num=$row["num"];
              $numarr[$count-1] = $num ;			  
			  
	if($coo->id == $num)  // 작품등록번호가 같은 경우 처리
	{		
 			  $catagory=$row["catagory"];			  
 			  $dporder=$row["dporder"];			  
			  		  
			  $item=$row["item"];			  
			  $itemdes=$row["itemdes"];
			  $sale=$row["sale"];			  
			  $price=$row["price"];
			  $saleprice=$row["saleprice"];
			  $filename1=$row["filename1"];	 	

              $imgurl1="./img/" . $filename1;	


$salepricearr[$count-1] = $saleprice ;
$quantityarr[$count-1] = $coo->quantity ;  // 쿠키의 수량

// $deliveryfeestr = $count == 1 ? $deliveryfee : "배송비 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    0" ;
 $deliveryfeestr = "";

$quantity = '주문수량 : ' . $coo->quantity . '개' ;  
$amountnum = $coo->quantity * $saleprice ;
$totalamount = 0;
$totaldeliveryfee = $deliveryfeenum;
$totalorder = $totalamount + $totaldeliveryfee ;
$amount = '상품금액 : ￦ ' . number_format($amountnum)  ;
	 		
?>			
<div id="line<?=$num?>" class="d-flex align-items-center border border-2 p-1 mb-2">

<input type=hidden  id="numarr" name="numarr[]">
<input type=hidden  id="quantityarr" name="quantityarr[]">
<input type=hidden  id="salepricearr" name="salepricearr[]">
<input type=hidden  id="deliveryfeeval" name="deliveryfeeval" value="3000">

  <div class="flex-shrink-0 p-1 mb-1">
	<!-- Product image-->
			 <img src="<?=$filename1?>" style="width:100px;height:100px;">	
  </div>
  <div class="flex-grow-1 ms-2">
	<div class="d-flex fs-4">
			<!-- Product name-->
			 <?=$item?>
			 </div>
  </div>
	                        
  <div class="flex-shrink-0 border border-1 p-3 mb-0">		        						
                          <?=$quantity?> <br>
						  <?=$amount?> 
						  </div>  
					
	</div>
					
<?
	    } // end of if statement `작품번호 같은가? 
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }	
	 
  $count++;	 
} // end of while 레코드수	 
?> 					

	</div>
</section>
		
<!-- 결재정보 요약 section-->
<section class="py-1 bg-light ">
<div class="container px-0 px-lg-0 mt-1 ">     
		
<div class="d-flex justify-content-center border border-2 p-2 mb-2">

  <div class="flex-shrink-0 ms-5">
	<div class="d-flex fs-5">
			총 선택상품금액 <br>
			</div>
     <div class="d-flex fs-2 font-weight-bold" id=totalamount>			
			 <?=number_format($totalamount) . "원"?>
			 </div>
  </div>
  <div class="flex-shrink-0 ms-5">
	<div class="d-flex fs-1">
			+ <br>
			</div>
     
  </div>
  <div class="flex-shrink-0 ms-5">
	<div class="d-flex fs-5">
			총 배송비 <br>
			</div>
     <div class="d-flex fs-2 " id=deliveryfee>			
			 <?=number_format($totaldeliveryfee) . "원"?>
			 </div>
  </div>
  <div class="flex-shrink-0 ms-5"> 
			| <br>
			| <br>
     
  </div>  
  <div class="flex-shrink-0 ms-5">
	<div class="d-flex fs-4 ">
			총 주문금액 <br>
			</div>
     <div class="d-flex fs-3 badge bg-primary text-wrap" id=resultamount>			 
		<?=number_format($totalorder) . "원"?>
		</div>
          
			 </div>
	</div>	        					
   </div>
</section>
	
<!-- 구매전 주의사항 section-->
<section class="h-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-10 col-xl-8">
        <div class="card" style="border-radius: 10px;">
          <div class="card-header px-4 py-5 text-center">
            <h1 class="text-muted mb-0"> 구매전 주의사항 / <span style="color: #a8729a;">FAQ</span>!</h1>
          </div>
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <p class="lead fw-normal mb-0" style="color: #a8729a;"> ☆ 금속제품이니 금속 뒷면이 날카로울 수 있으니 손베임에 주의해 주세요!</p>              
            </div>
            <div class="card shadow-0 border mb-4">
              <div class="card-body">
                <div class="row">
				<p class="lead fw-large mb-0" style="color: #a8729a;">
				    ☞ 아이들과의 접촉이 되지 않도록 해 주시고, 장난감으로 사용하지 말아주세요.
				 </p>
                </div>
              </div>
            </div>
			<div class="d-flex justify-content-between align-items-center mb-4">
              <p class="lead fw-normal mb-0" style="color: #a8729a;"> ☆ 블랙/골드/브론즈/실버 색상의 철판은 제조시 약간의 색감차이가 있을 수 있습니다.</p>              
            </div>
			<div class="d-flex justify-content-between align-items-center mb-4">
              <p class="lead fw-normal mb-0" style="color: #a8729a;"> ☆ 금속의 중량으로 떨어짐이 있을 수 있으니 벽에 걸 때 꼭 유의해 주세요.</p>              
            </div>			
			<div class="d-flex justify-content-between align-items-center mb-4">
              <p class="lead fw-normal mb-0" style="color: #a8729a;"> ☆ 미러(mirror) 재질로 제작된 제품은 셀프 유리막코팅제로 닦으면 편리합니다.</p>              
            </div> 
			<!--
            <div class="card shadow-0 border mb-4">
              <div class="card-body">
                <div class="row">
				<p class="lead fw-small mb-0" style="color: #a8729a;">
				    ☞ 미러(Mirror)재질 레이져 작업시 약간의 가공 스크래치가 발생할 수 있습니다.
				 </p>
                </div>
              </div>
            </div>			
			-->
			<div class="d-flex justify-content-between align-items-center mb-4">
              <p class="lead fw-normal mb-0" style="color: #a8729a;"> ☆  단순 변심에 의한 교환은 불가함을 알려드립니다. </p>              
            </div>

          </div>
          <div class="card-footer border-0 px-5 py-4"
            style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
            <h1 class="d-flex align-items-center justify-content-center text-white mb-0"> 
			<i class="bi bi-telephone-outbound-fill"></i> &nbsp;
			  고객센터 031-983-8440</h1>
			  <h3 class="d-flex align-items-center justify-content-center text-center text-white mb-0"> 
			
			  ( 운영시간 : 월~금(공휴일제외) 오전9시 ~ 오후5시 ※점심시간 12:00~13:00 ) </h3>
			  
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

	 
<!-- 결재정보 section-->
<section class="py-1">   
         <div class="container px-0 px-lg-1 mt-5">    		
                <div class="row gx-3 gx-lg-1 align-items-center">                      
                        <div class="fs-1 mb-1 border border-light" id=leftchar>
							  <label class="form-check-label" for="leftchar">
									&nbsp;&nbsp;  입금(결재) 계좌정보 &nbsp;&nbsp; &nbsp;&nbsp; 
									 <span class="fs-2 badge   rounded-pill bg-secondary">  &nbsp;&nbsp; 기업은행 339-084210-04-033 &nbsp;&nbsp;  예금주: &nbsp; (주)미래기업  &nbsp;&nbsp; </span>
							  </label>				
						</div>						                 
                      					
						</div>    
                    </div>   
</section>		

	 
<!-- 배송정보 section-->
<section class="py-0">   
      <div class="mb-3" id="tmp" >t </div>
         
<div class="container">
    <div class="input-form-backgroud row">
      <div class="input-form col-md-12 mx-auto">
        <h3 class="mb-3">배송정보입력</h3>
        <form class="validation-form" novalidate>
           <div class="mb-3">
              <label for="password">비밀번호(주문수정시 사용)</label>
              <input type="text" class="form-control" id="password" name="password" placeholder="" value="" required>
              <div class="invalid-feedback">
                비밀번호를 입력해주세요.
              </div>
            </div>		
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="name">주문하신분 성함</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="" value="" required>
              <div class="invalid-feedback">
                이름을 입력해주세요.
              </div>
            </div>
              <div class="col-md-6 mb-3">
              <label for="tel">연락처 </label>
              <input type="text" class="form-control" id="tel" name="tel" placeholder="" value="" required>
              <div class="invalid-feedback">
                전화번호를 입력해주세요.
              </div>
            </div>
          </div>
		  
		  
          <div class="row">
            <div class="col-md-6 mb-3">			
							  <input class="form-check-input" type="checkbox" value="" id="receivenamecheck">
							  <label  for="receivenamecheck">
								배송 받으실분 이름 (주문인동일 체크)
							  </label>							  
              <input type="text" class="form-control" id="receivename" name="receivename" placeholder="" value="" required>
              <div class="invalid-feedback">
                배송 받으실분 입력하세요.
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="receivetel">배송 받을분 연락처</label>
              <input type="text" class="form-control" id="receivetel"  name="receivetel" placeholder="" value="" required>
              <div class="invalid-feedback">
                 전화번호를 입력해주세요.
              </div>
            </div>
          </div>		  
		  
          <div class="row">		  
            <div class="col-md-6 mb-3">
            <label for="email">이메일 <span class="text-muted">&nbsp;(필수 아님)</span> </label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" >
            <div class="invalid-feedback">
              이메일을 입력해주세요.
            </div>
          </div>	
		  </div>	

          <div class="mb-3">
            <label for="address">배송받으실 주소</label>
            <input type="text" class="form-control" id="address"  name="address" placeholder="서울특별시 강남구" required>
            <div class="invalid-feedback">
              주소를 입력해주세요.
            </div>
          </div>

          <div class="mb-3">
            <label for="address2">세부 상세주소 <span class="text-muted">&nbsp;(필수 아님)</span></label>
            <input type="text" class="form-control" id="address2" name="address2" placeholder="상세주소를 입력해주세요." >
          </div>
		  
          <div class="mb-3">
            <label for="request">기타 배송시 요청사항 <span class="text-muted">&nbsp;(필수 아님)</span> </label>
            <input type="text" class="form-control" id="request" name="request" placeholder="요청사항을 입력해주세요." >
          </div>

          <div class="row">
            <!-- <div class="col-md-8 mb-3">
              <label for="root">가입 경로</label>
              <select class="custom-select d-block w-100" id="root">
                <option value=""></option>
                <option>검색</option>
                <option>카페</option>
              </select>
              <div class="invalid-feedback">
                가입 경로를 선택해주세요.
              </div>
             </div> -->
            <div class="col-md-4 mb-3">
              <label for="code">추천인 코드(있을시)</label>
              <input type="text" class="form-control" id="code"  name="code" placeholder="" >
              <div class="invalid-feedback">
                추천인 코드를 입력해주세요.
              </div>
            </div>
          </div>
          <hr class="mb-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="aggrement" required>
            <label class="custom-control-label" for="aggrement">개인정보 수집 및 이용에 동의합니다.</label>
          </div>
          <div class="mb-4"></div>
          <button class="btn btn-success btn-lg btn-block" type="button" id=orderBtn >결재 후 주문서 전송하기</button>
        </form>
      </div>
    </div>
</div>
</section>		 
		 
</form>
		
<!-- Footer-->
<? include "footer.php" ?>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
		
		
		
</body>
	
	


	
</html>

<script>


$(document).ready(function(){
	
calCart();
// order 버튼 클릭시
$("#orderBtn").click(function(){  
    var numarr = <?php echo json_encode($numarr);?> ; 	
	// 모달 띄워주고
	$('#myModal').modal('show');

	// 기존카트에서 주문한 것을 제거한다.
	for(i=0;i<numarr.length;i++)
           cartdel(numarr[i]);

	calCart() ; // check 다시 계산하기
	// 2초후 실행 폼전송
	setTimeout(function() {
	// form submit
	
	// 쿠키에 주문자 전번를 기록함.
	setCookie ('tel', $('#tel').val() , 3600);   // 전번 쿠키에 저장함
	$('#myModal').modal('hide');
	$("#board_form").submit(); 	
    // 폼전송 후 delivery.php로 이동됨
 	
	}, 2000);


});


});


// 장바구니 금액 다시 계산
function calCart() {	

// 배열로 쿠키내용을 불로오고 수정하고 해서 처리한다.
var numarr = <?php echo json_encode($numarr);?> ;
var quantityarr = <?php echo json_encode($quantityarr);?> ;
var salepricearr = <?php echo json_encode($salepricearr);?> ;
let totalamount = 0;
let deliveryfee = 3000;

console.log('numarr ' + numarr);	
console.log('quantityarr ' + quantityarr);	
console.log('salepricearr ' + salepricearr);	

			for(j=0;j<numarr.length;j++) 			
					  totalamount = totalamount + (quantityarr[j] * salepricearr[j]);						
		console.log('totalamount ' + totalamount);	
		
// 총상품금액 계산해서 화면표시
	$('#totalamount').text(comma(totalamount) + '원');		
	$('#deliveryfee').text(comma(deliveryfee) + '원');		
	$('#resultamount').text(comma(totalamount + deliveryfee) + '원' );		
	
    return totalamount;
 }

window.addEventListener('load', () => {
  const forms = document.getElementsByClassName('validation-form');

  Array.prototype.filter.call(forms, (form) => {
    form.addEventListener('submit', function (event) {
      if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
      }

      form.classList.add('was-validated');
    }, false);
  });
}, false);


// 전체선택시 전체 체크
$("input:checkbox[id='receivenamecheck']").click(function(){
	var checked = $('#receivenamecheck').is(':checked');
	
	if(checked)
	{
		$('#receivename').val($('#name').val());
		$('#receivetel').val($('#tel').val());
	}
	  
});

// 주소입력시 처리
window.onload = function(){
    document.getElementById("address").addEventListener("click", function(){ //주소입력칸을 클릭하면
        //카카오 지도 발생
        new daum.Postcode({
            oncomplete: function(data) { //선택시 입력값 세팅
                document.getElementById("address").value = data.address; // 주소 넣기
                document.querySelector("input[id=address2]").focus(); //상세입력 포커싱
            }
        }).open();
    });
	
// 임시정보 클릭시
    document.getElementById("tmp").addEventListener("click", function(){ //주소입력칸을 클릭하면
        //임시자료입력 
		
		$('#name').val('테스트');
		$('#receivename').val('테스트');
		$('#password').val('1');
		$('#tel').val('010-5123-8210');
		$('#receivetel').val('010-5123-8210');
		$('#email').val('222@naver.com');
		$('#code').val('1234');
		$('#request').val('요구사항 입력창');		
		$('#address').val('주소1');		
		$('#address2').val('주소2');		
		$('#aggrement').val('1');		
		
    });	
	
	
}



</script>
