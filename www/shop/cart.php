<?php
if(!isset($_SESSION))      
        session_start(); 
	$user_name= $_SESSION["name"];
	$user_id= $_SESSION["userid"];
	

  require_once("../lib/mydb.php");
  $pdo = db_connect();
  
  // php에서 자바스크립에서 저장한 JSON방식의 쿠키를 가져와서 사용할려면 아래와 같이 json_decode해야 한다. var_dump로 배열내용 확인가능 
  $cookie =  json_decode($_COOKIE["shopcart"]); 
    	
?>

<!DOCTYPE html>
    <head>        
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>미래기업 쇼핑몰 장바구니</title> <div id=cookiedisplay> </div>
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

    </head>		

<body id="page-top">

        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-5 px-lg-2">
                <a class="navbar-brand fs-1" href="#!"> 장바구니 </a>
                
                
                    <form class="d-flex">
                        <button class="btn btn-outline-dark" type="button"  onclick="location.href='./index.php'">
                             <i class="fa-solid fa-house-user"></i>
                            작품쇼핑몰                             
                        </button>
						&nbsp;
						<button  class="btn btn-outline-dark" type="button" onclick="javascript:movetodelivery();">
						 
                            <i class="bi bi-credit-card-fill"></i>
                            주문&배송정보                            
                        </button>							
						<? 
						  if($user_name=='김보곤')
						  {
						    print '&nbsp; <button class="btn btn-outline-dark" type="button" onclick="javascript:movetolist();">
                            <i class="bi-tool-fill me-1"></i>
                            아이템등록
                        </button> ';			?>						
						    &nbsp; <button class="btn btn-outline-dark" type="button" onclick="location.href='admin.php'">
                            <i class="bi-tool-fill me-1"></i>
                            대쉬보드
                        </button> 
						<?
							}						
						?>

                    </form>
                </div>            
        </nav>
				
<!-- 전체선택 section-->
<section class="py-0">   
         <div class="container px-0 px-lg-5 mt-5">    		
                <div class="row gx-3 gx-lg-1 align-items-center">                      
                        <div class="fs-1 mb-1 border border-light">
						<div class="form-check ms-3">						
							  <input class="form-check-input" type="checkbox" value="" id="ckBox">
							  <label class="form-check-label" for="ckBox">
								전체선택
							  </label>
													
						&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" id="emptycart"  onclick="javascript:emptycart()" class="btn btn-outline-dark " >                                 
                                장바구니 비우기
								</button>  					
						</div>						                 
                      					
                    </div>    
                    </div>   
						</div>	
					
</section>
		
<!-- shopcart items section-->
<section class="py-3 bg-light">
<div class="container px-2 px-lg-5 mt-5">     
<?

//var_dump($cookie);
// 자료수 구하기
$recordnum = $cookie!=null ? count($cookie) : 0 ;
//print '레코드 수 : ' . $recordnum;

$deliveryfeenum = 0;

$count = 1;

$totalamount = 0;

if($cookie!=null) // 쿠키가 있을때만 실행
foreach ($cookie as $coo) {

$num_arr = array();
$saleprice_arr = array();

 try{
   $sql = "select * from mirae8440.shopitem order by dporder asc";

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		      $num=$row["num"];
			  
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

$numarr[$count-1] = $num ;
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

  <div class="flex-shrink-0 border border-light">	
		<!-- check box -->	
    <div class="d-flex fs-1">		
			<div class="form-check">
				  <input class="form-check-input" type="checkbox" value="" id="check<?=$num?>" name=checklist>
				  <label class="form-check-label" for="flexCheckDefault">
					
				  </label>
			</div>	
		</div>		 
	</div>

  <div class="flex-shrink-0 p-2 mb-2">
	<!-- Product image-->
			 <img src="<?=$filename1?>" style="width:125px;height:125px;">	
  </div>
  <div class="flex-grow-1 ms-3">
	<div class="d-flex fs-3">
			<!-- Product name-->
			 <?=$item?>
			 </div>
  </div>
	                        
  <div class="flex-shrink-0 border border-1 p-5 mb-5">		        						
                          <?=$quantity?> 
						  </div>
  <div class="flex-shrink-0 border border-1 p-5 mb-5">			        						
                          <?=$amount?> 
						  </div>
  <div class="flex-shrink-0 border border-0 p-2 mb-5" >		        						
	<button type="button" id="delline"  onclick="javascript:dellineFn('<?=$num?>')" class="btn btn-outline-dark " >                                 
                                장바구니에서 삭제
								</button>  
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
<section class="py-3 bg-light ">
<div class="container px-0 px-lg-5 mt-4 ">     
		
<div class="d-flex justify-content-center border border-2 p-5 mb-5">

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
		 
		
			
<!-- 주문버튼 section-->
<section class="py-3 bg-light">
<div class="container px-1 px-lg-0 mt-1 ">     
		
<div class="d-flex justify-content-center border border-0 p-2 mb-5">
 <div class="flex-shrink-0 ">
	<button type="button" id="order"   class="btn btn-success  fs-1" > 
                                <i class="bi-cart-fill me-1"></i>
                                주문하기
								</button>   
  </div>
	</div>	        					
   </div>
</section>
		
<!-- Footer-->
<? include "footer.php" ?>   		
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
		
</body>
	
	


	
</html>


<script>


$(document).ready(function(){
	
	// deleteCookie('shopcart'); 
	
// 쿠키의 장바구니 수치를 불러옴

// allDelCookies('http://8440.co.kr/shop', '/');

reloadShopCart();

calCart();

// 전체선택시 전체 체크
$('#ckBox').click(function(){
	var checked = $('#ckBox').is(':checked');
	
	if(checked)
		$('input:checkbox').prop('checked',true);
	  else
		 $('input:checkbox').prop('checked',false); 
	  
});

	// 개별 체크박스 선택시
	$('input:checkbox').click(function(){    // 화면의 checkbox 클릭시 동작함
		  calCart();					
	});

	// 주문하기 비우기
	$('#order').click(function(){ 	
	
	if(calCart()>0) 	// cart에 자료가 존재하면		  
	  location.href='order.php';
	  else
	   alert('주문할 상품을 선택하세요!');	  
	 

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
// 주문하기에 저장될 변수
Objectcart = new Array();
 
// console.clear();	
console.log('numarr ' + numarr);	
console.log('quantityarr ' + quantityarr);	
console.log('salepricearr ' + salepricearr);	

		//체크된 값만 가져오기
		let checkarr = [];

		$('input:checkbox[name=checklist]:checked').each(function(i,val){
			console.log('index :' + i);	
			checkarr.push(val.id);
			for(j=0;j<numarr.length;j++) 			
				if(val.id.replace('check', '')==numarr[j])	
				  { 					
					  totalamount = totalamount + (quantityarr[j] * salepricearr[j]);	
					    // 초기화를 위해 object data 선언을 여기 넣어야 한다. 주의
						var data = new Object();					  
						data.id = numarr[j];
						data.quantity = quantityarr[j];
						data.saleprice =  salepricearr[j];
						Objectcart.push(data);
						// console.log(numarr[j] + ' ' + quantityarr[j] + ' ' + salepricearr[j] + ' ' );
						console.log('ordercart 쿠키' + JSON.stringify(Objectcart));
					  
				  }
		});
			
		console.log(checkarr);	
		console.log('totalamount ' + totalamount);	
		
// 쿠키정보에 주문선택한 리스트 저장

		console.log('ordercart 쿠키저장' + JSON.stringify(Objectcart));
		setCookie ('ordercart', JSON.stringify(Objectcart), 3600);   // 쿠키에 저장함
  		
		
	// 선택이 없을때는 배송비 0 처리	
	if(checkarr.length==0) 
		deliveryfee = 0;
		
// 총상품금액 계산해서 화면표시
	$('#totalamount').text(comma(totalamount) + '원');		
	$('#deliveryfee').text(comma(deliveryfee) + '원');		
	$('#resultamount').text(comma(totalamount + deliveryfee) + '원' );		
	
    return totalamount;
 }

// 장바구니에서 삭제버튼
function dellineFn(num) {		
	$('#line' + num).remove();
// 쿠키 내용 변경하기	


// 쿠키 불러옴
let getcart = getCookie("shopcart");

	if(getcart!=null)
	{	
		Objectcart = JSON.parse(getcart);

		console.log('쿠키삭제 장바구니 내용변경');
		// 특정 id의 값이 같은 것을 삭제함.
		const idx = Objectcart.findIndex(function(item) {return item.id === num});
		Objectcart.splice(idx,1);

		 console.log(Objectcart);

		setCookie ('shopcart', JSON.stringify(Objectcart), 1000);   // 쿠키에 저장함

		reloadShopCart(); // 장바구니 수량 화면 수정

		calCart() ; // check 다시 계산하기

	}

 }

// 장바구니 비우기
function emptycart() {	
var numarr = <?php echo json_encode($numarr);?> ;
if(numarr.length>0) 	
if(confirm("장바구니 전체 비우시겠습니까?")) 
	{    
		deleteCookie('shopcart');	
		location.reload(); // 페이지 리로드
	}
 }


</script>
