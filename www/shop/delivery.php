<?php
 session_start();

 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 
 // ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
 
require_once("../lib/mydb.php");
$pdo = db_connect();     

// 작품명 배열에 넣기
 				
$sql="select * from mirae8440.shopitem order by num desc" ; 					
$sqlcon = "select * from mirae8440.shopitem order by num desc" ;   // 전체 레코드수를 파악하기 위함.					

$workarr = array();
$filenamearr = array();

try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];			  		  			  
			  $item=$row["item"];			  
			  $filename1=$row["filename1"];	 	
           $workarr[$num] = $item ;		  
           $filenamearr[$num] = $filename1 ;		  
			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];

$sql="select * from mirae8440.shop where delvalue!='1' order by num desc" ; 					
$sqlcon = "select * from mirae8440.shop  where delvalue!='1' order by num desc" ;   // 전체 레코드수를 파악하기 위함.					
		
   
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp2;     // 전체 글수	           					 
   
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			$num=$row["num"];
			$password = $row["password"];
			$state =$row["state"];
			$orderdate =$row["orderdate"];
			$orderlist = $row["orderlist"];   // orderlist 복호화 중요함 핵심기술
			$name= $row["name"]; 
			$tel= $row["tel"]; 
			$receivename= $row["receivename"]; 
			$receivetel= $row["receivetel"]; 
			$email= $row["email"]; 
			$address= $row["address"]; 
			$address2= $row["address2"]; 
			$request= $row["request"]; 
			$code= $row["code"];
			$deliveryfee= $row["deliveryfee"];  // 3000원으로 배송비 일단 세팅함	 		
			$payment= $row["payment"];  // 결재확인중 결재완료

// orderlist를 파싱하는 구문 (중요함) 핵심기술

$arr = explode(',', $orderlist);  // comma로 구분된 자료 불러오기	

// var_dump($arr);	  	
  
$count =0 ;  
$idarr = array();
$quantityarr = array();
$salepricearr = array();

// print count($arr);

$orderliststr = "";
$totalsaleprice = 0;
 foreach($arr as $value) { 
 // id의 값을 추출해서 배열에 넣는다
	preg_match('/id(.*?)quantity/',$value, $match);  // 결과물 match에 저장됨
	$idarr[$count] = $match[1];	
 // quantity 수량의 값을 추출해서 배열에 넣는다	
	preg_match('/quantity(.*?)saleprice/',$value, $match);  // 결과물 match에 저장됨
	$quantityarr[$count] = $match[1];	
 // 개당 판매금액 saleprice 값을 추출해서 배열에 넣는다	
	preg_match('/saleprice(.*?) /',$value, $match);  // 결과물 match에 저장됨
	$salepricearr[$count] = $match[1];	
	
    $orderliststr .= '작품번호 : ' . $idarr[$count] .'작품명 : ' . $workarr[intval($idarr[$count])] . ', 수량 : ' . $quantityarr[$count] . ', 금액 : ' . number_format(intval($quantityarr[$count]) * intval($salepricearr[$count])) . ' <br><br> '; 		 		
	$totalsaleprice += intval($quantityarr[$count]) * intval($salepricearr[$count]);
	$count++;
			 
			$start_num--;  
			 } 
	   }
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
  
?>    

<!DOCTYPE html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />

<meta property="og:type" content="미래기업 작품 쇼핑몰">
<meta property="og:title" content="이제 멋진 금속액자형 작품과 만나보세요">
<meta property="og:url" content="8440.co.kr/shop">
<meta property="og:description" content="멋진 금속 제작품을 선물해 보세요!">
<meta property="og:image" content="http://8440.co.kr/img/thumbnail.jpg"> 		
		
        <title>미래기업 금속작품 주문&배송 조회</title> <div id=cookiedisplay> </div>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>
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
  <script src="js/scripts.js"></script>
    </head>
	

        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">		
            <div class="container px-4 px-lg-5">
                				
				
                    <form class="d-flex">
                        <button class="btn btn-outline-dark" type="button"  onclick="location.href='./index.php'">
                             <i class="fa-solid fa-house-user"></i>
                            작품쇼핑몰                             
                        </button>	
						&nbsp;						
                        <button  class="btn btn-outline-dark" type="button"  onclick="location.href='./cart.php'">
                            <i class="bi-cart-fill me-1"></i>
                            장바구니
                            <span id="cartnum" class="badge bg-dark text-white ms-1 rounded-pill"><?=$cartnum?> </span>
                        </button> 						
						<? 
						  if($user_name=='김보곤')
						  {
						    print '&nbsp;  <button class="btn btn-outline-dark" type="button" onclick="javascript:movetolist();">
                            <i class="bi-tool-fill me-1"></i>
                            아이템등록
                        </button> ';													?>
						
						    &nbsp;  <button class="btn btn-outline-dark" type="button" onclick="location.href='admin.php'">
                            <i class="bi-tool-fill me-1"></i>
                            대쉬보드
                        </button> 
						<?
							}						
						?>

                    </form>
                </div>            
        </nav>	
	
		
<div class="container">  
		
<input type="hidden" name="saleprice_val" id="saleprice_val" > 
<input type="hidden" name="cartsave" id="cartsave" > 	
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">주문 & 배송조회</h4>
        </div>
        <div class="modal-body">
		<div  class="fs-2 mb-2" >
		  <div class="row justify-content-center align-items-center mb-5">	
			<div class="col-md-12 "> 		  
		  검색한 전화번호 뒷 8자리 : <input type="text" name="resulttel" id="resulttel" value=""/> 			
			</div>
		   </div>
		   </div>
		   <div  class="current fs-4 mb-2" >
		   <div class=" row justify-content-center align-items-center mb-5">		  
			  <div class="col-2 justify-content-center align-items-center text-center"> 	  주문번호   </div> 
			  <div class="col-2 justify-content-center align-items-center text-center">     주문일시	  </div>
			  <div class="col-2 justify-content-center align-items-center text-center">	  진행상태   </div>
			  <div class="col-6 justify-content-center align-items-center text-center">     주문내용   </div>		  
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
	
	
<section class="vh-100" style="background-color: #8c9eff;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12">
        <div class="card card-stepper text-black" style="border-radius: 16px;">

          <div class="card-body p-5">

            <div class="d-flex justify-content-between align-items-center p-2 mb-5">           
				<h4 class="mb-1  p-2">
				   <span class="text-muted" > 주문하신분 전화번호로 조회하기 <span> <br> <br>
					<label for="lookuptel">주문 & 배송 </label> &nbsp;&nbsp;
					<input type="text"  id="lookuptel"  name="lookuptel" onkeydown="return captureReturnKey(event)" placeholder="전번 뒷 8자리 입력 '00000000'" required>
					 <button type="button" id="lookuporderBtn"   class="btn btn-outline-dark mt-auto fs-4" >                                 
                                조회  </button>
				</h4>             

            </div>
			
		   <div  class="fs-3 mb-1" >
		   <div class="maincurrent row justify-content-center align-items-center mb-2 p-1 border border-1">		  
			  <div class="col-2 justify-content-center align-items-center text-center"> 	  주문<br>번호   </div> 
			  <div class="col-2 justify-content-center align-items-center text-center">     주문<br>일시	  </div>
			  <div class="col-2 justify-content-center align-items-center text-center">	  진행<br>상태   </div>
			  <div class="col-6 justify-content-center align-items-center text-center">     주문내용   </div>		  
		  </div>
			</div>			
			

            <div id="progressbar-2" class="d-flex justify-content-between mx-0 mt-0 mb-5 px-0 pt-0 pb-1">

			&nbsp;

            </div>
            <div id="statebar" class="d-flex justify-content-between mx-0 mt-5 mb-5 px-0 pt-0 pb-0">
			
              <span class="dot"></span>
              <hr class="flex-fill track-line " ><span class="dot"></span>
              <hr class="flex-fill track-line"  ><span class="dot"></span>              
              <hr class="flex-fill track-line"  ><span
                class="d-flex justify-content-center align-items-center big-dot dot">
                <i class="fa fa-check text-white"></i></span>	

            </div>
		
			
  
            <div class="d-flex justify-content-between">
              <div class="d-lg-flex align-items-center">
                <i class="fas fa-clipboard-list fa-3x me-lg-4 mb-3 mb-lg-0"></i>
                <div>
                  <p class="fw-bold mb-1">주문</p>
                  <p class="fw-bold mb-0">접수</p>
                </div>
              </div>
              <div class="d-lg-flex align-items-center">
                <i class="fas fa-box-open fa-3x me-lg-4 mb-3 mb-lg-0"></i>
                <div>
                  <p class="fw-bold mb-1">주문 물품</p>
                  <p class="fw-bold mb-0">제작중</p>   
                </div>
              </div>
              <div class="d-lg-flex align-items-center">
                <i class="fas fa-shipping-fast fa-3x me-lg-4 mb-3 mb-lg-0"></i>
                <div>
                  <p class="fw-bold mb-1">물품</p>
                  <p class="fw-bold mb-0">배송중</p>
                </div>
              </div>
              <div class="d-lg-flex align-items-center">
                <i class="fas fa-home fa-3x me-lg-4 mb-3 mb-lg-0"></i>
                <div>
                  <p class="fw-bold mb-1">배송</p>
                  <p class="fw-bold mb-0">완료</p>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between">
			  &nbsp;
            </div>

          </div>

        </div>
      </div>
    </div>
  </div>
   
		
<!-- Footer-->
<? include "footer.php" ?>   
  
</section>


  <form id=Form1 name="Form1">
    <input type=hidden id="searchtel" name="searchtel" >
  </form>  
  <form id=Form2 name="Form2">
    <input type=hidden id="ordernum" name="ordernum" >
  </form>  
 
<script>
$(document).ready(function(){
	
  $('#lookuptel').focus();
  
  let telnum = getCookie('tel'); 
  
  $('#lookuptel').val(telnum);	// 임시로 설정 검색을 위해서
  
  $("#lookuporderBtn").click(function(){ 
  
	var workarr = <?php echo json_encode($workarr);?> ;   // 작품명배열
	
	let str = $('#lookuptel').val();
	
	str = str.replace(/-/gi, "");
	str = str.replace(/\s/gi, "");  // 공백제거	
	str = str.slice(-8);;  // 뒷에서 8자리
	
	$('#searchtel').val(str);				 

	    $.ajax({
			url: "searchpassword.php",
    	  	type: "post",		
   			data: $("#Form1").serialize(),
   			dataType:"json",
			success : function( data ){
			console.log( data);

		   $('#resulttel').val($('#searchtel').val());
		   
		   	var myClass = "myClass";
		   	var itemClass = "itemClass";
		   	var endClass = "endClass";
		   // 기존의 div 제거
		   $("." + myClass).remove();  		   
		   		
			var tag='';  // tag 비워주고 계속 누적해서 나간다
			
              for(i=0;i<data['num_arr'].length;i++)
			  {			
			   var pretag = "<div class='myClass row justify-content-center align-items-center mb-2 p-1 border border-2'> ";		   
			   var c1 = "<div class='child col-2 justify-content-center align-items-center text-center p-1 ' >";
			   var c2 = "<div class='child col-6 justify-content-center align-items-center text-center p-1 ' >";		                 // 주문리스트 파싱하기		  
		        var string = data['orderlist_arr'][i];
				var array = string.split(",");
			
				var totalsaleprice = 0;				
				var orderliststr = '';					
				
				// 중첩자식요소 제거
				// $(".myClass").remove();  			
				
				console.log(array);
				// console.log('array[0]' + array[0]);
				// console.log('substring : ' + array[0].substring(array[0].indexOf("id")+2,array[0].indexOf("quantity")));
				
				for(j=0;j<array.length;j++)
						{
							let tmp = String(array[j]) ;	// substring은 스트링이 아니면 에러발생해서 앞에 붙여주자..
							let id = tmp.substring(tmp.indexOf("id")+2,tmp.indexOf("quantity"));  // id와 quantity 사이 추출						
							let quantity = tmp.substring(tmp.indexOf("quantity")+8,tmp.indexOf("saleprice")); 						
							let saleprice = tmp.substring(tmp.indexOf("saleprice")+9, tmp.length); 			
				
				            // console.log('saleprice : ' + saleprice);
							orderliststr += '작품번호 : ' + id + '<br> 작품명 : ' + workarr[Number(id)] + '<br> 수량 : ' + Number(quantity) + '개, 금액 : ' + (Number(quantity) * Number(saleprice)).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '원 배송비 : ' + (data['deliveryfee_arr'][i]).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")   + '원 <br>' ;  		 		
							totalsaleprice += (Number(quantity) * Number(saleprice));										
						} 	
						
						totalsaleprice += data['deliveryfee_arr'][i];	
						// 총 합계금액 보여주기
                    	orderliststr += '<br> <span class="totalamount"> 총 주문금액 : ' + totalsaleprice.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '원 </span> <br>' ;  			 		                    
                    	orderliststr += '<br> <span > 결재상태 : ' + data['payment_arr'][i] + ' </span> <br><br>' ;  			 		                    
						
				   anchor = '	<a onclick="itemClick(' + data['num_arr'][i] + '); return false;"> ';  // anchor tag 추가하기
				   				   
				   c1 = c1 + anchor ;
				   c2 = c2 + anchor ;
				   
				   tag += pretag;
				   tag += c1 + data['num_arr'][i] + "</a> </div>" ;
				   tag += c1 + data['orderdate_arr'][i] + "</a> </div>" ;
				   if(data['state_arr'][i]==null)
					   data['state_arr'][i]= '';
				   
					   tag += c1 + data['state_arr'][i] + "</a> </div>" ;
				       tag += c2 + orderliststr + "</a>  </div> </div> " ;  // 마지막 row 닫아줌 /div
					
			  }
			  
			  $(".current").after(tag); 				  

                    $(".totalamount").css("font-weight", "bold");
                    $(".totalamount").css("color", "green");
					
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
		   
	  $('#myModal').modal('show');
	  
 });	 

});

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    $("#lookuporderBtn").click();
}

// 주문배송목록 클릭시 처리부분
// 주문배송목록 클릭시 처리부분
function itemClick(ordernum){   	
// ordernum 주문번호임
// 띄워둔 모달 숨기고
  $('#myModal').modal('hide');
	
// 전화번호 포커싱	
//  $('#lookuptel').focus();   
  
	var workarr = <?php echo json_encode($workarr);?> ;   // 작품명배열
	var filenamearr = <?php echo json_encode($filenamearr);?> ;   // 이미지파일 경로명 배열 불러오기
	
	console.log('workarr' + workarr);
	console.log('filenamearr' + filenamearr);
	
	$('#ordernum').val(ordernum);				 

	    $.ajax({
			url: "searchordernum.php",
    	  	type: "post",		
   			data: $("#Form2").serialize(),
   			dataType:"json",
			success : function( data ){
			console.log( data);
	   
		   	var myClass = "myClass";
		   	var itemClass = "itemClass";
		   	var endClass = "endClass";
			let state;
			
		   // 기존의 div 제거
		   $("." + myClass).remove();
		   
 		   var pretag = "  <div class='myClass row justify-content-center align-items-center mb-5 p-1 border boder-2'>	";		   
		   var c1 = "<div class='child col-2 justify-content-center align-items-center text-center p-1' >";
		   var c2 = "<div class='child col-6 justify-content-center align-items-center text-center p-1' >";
		   var spantag ; 
		   		   
  		   var div = $(pretag).addClass(myClass);				
		   $(".maincurrent").after(div);  // 본문은 maincurrent class 다음에 추가함		

              for(i=0;i<data['num_arr'].length;i++)
			  {				  		  
                // 주문리스트 파싱하기		  
		        var string = data['orderlist_arr'][i];
				var array = string.split(",");
			
				var totalsaleprice=0;				
				var orderliststr = '';	
				var anchor = '';
				
				console.log(array);
				// console.log('array[0]' + array[0]);
				// console.log('substring : ' + array[0].substring(array[0].indexOf("id")+2,array[0].indexOf("quantity")));
				
				for(j=0;j<array.length;j++)
						{
							let tmp = String(array[j]) ;	// substring은 스트링이 아니면 에러발생해서 앞에 붙여주자..
							id = tmp.substring(tmp.indexOf("id")+2,tmp.indexOf("quantity"));  // id와 quantity 사이 추출						
							let quantity = tmp.substring(tmp.indexOf("quantity")+8,tmp.indexOf("saleprice")); 						
							let saleprice = tmp.substring(tmp.indexOf("saleprice")+9, tmp.length); 			
				
				            console.log('saleprice : ' + saleprice);
							orderliststr += '작품번호 : ' + id + '<br> 작품명 : ' + workarr[Number(id)] ;
							orderliststr += '<br> <img src="' + filenamearr[Number(id)] + '" style="width:100px;height:100px;";> <br> 수량 : ' + Number(quantity) + '개, 금액 : ' + (Number(quantity) * Number(saleprice)).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '원, 배송비 : ' + (data['deliveryfee_arr'][i]).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")   + '원 <br><br>' ;  		 				 		
							totalsaleprice += (Number(quantity) * Number(saleprice));			
							
						} 								
				   totalsaleprice += data['deliveryfee_arr'][i];	
				   orderliststr += '<br> <span class="totalamount"> 총 주문금액 : ' + totalsaleprice.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + '원 </span> <br><br>' ;  			 		                    				

				   c1 = c1 + anchor ;
				   c2 = c2 + anchor ;
				   tag = c1 + data['num_arr'][i] + " </div>" ;
				   tag += c1 + data['orderdate_arr'][i].substring(0,13) + "시" + " </div>" ;
				   if(data['state_arr'][i]==null)
					   data['state_arr'][i]= '';
				   spantag = "<div id=deliveryword" + data['num_arr'][i] + " class='child col-2 justify-content-center align-items-center text-center p-2 badge bg-dark text-white rounded-pill fs-2' >" ; // 문자색을 넣을려고 만듬
					   tag += spantag + data['state_arr'][i] + "<br><br> " + data['payment_arr'][i] +" </div>" ;
				       tag += c2 + orderliststr + " </div> " ;			   

                   $(".myClass").append(tag); 	// 부모 엘리먼트에 추가함	  
				   $(".totalamount").css("font-weight", "bold");
				   $(".totalamount").css("color", "green");		

                   state = data['state_arr'][i] ;		    // 진행상태 저장

               for(i=0;i<data['num_arr'].length;i++)
			     {
				   if(state=='주문완료')
                       $("#deliveryword" + data['num_arr'][i]).attr("class", "child col-2 justify-content-center align-items-center text-center p-2 badge fs-3 bg-secondary text-white rounded-pill");				   
				   if(state=='주문접수')
                       $("#deliveryword" + data['num_arr'][i]).attr("class", "child col-2 justify-content-center align-items-center text-center p-2 badge fs-3 bg-info text-white rounded-pill");				   				   
				   if(state=='제작중')
                       $("#deliveryword" + data['num_arr'][i]).attr("class", "child col-2 justify-content-center align-items-center text-center p-2 badge fs-3 bg-dark text-white rounded-pill");					   
				   if(state=='배송중')
                       $("#deliveryword" + data['num_arr'][i]).attr("class", "child col-2 justify-content-center align-items-center text-center p-2 badge fs-3 bg-danger text-white rounded-pill");					   
				   if(state=='배송완료')
                       $("#deliveryword" + data['num_arr'][i]).attr("class", "child col-2 justify-content-center align-items-center text-center p-2 badge fs-3 bg-success text-white rounded-pill");						   
				  }
				  
			  }
			  
     // 진행상태 그래픽으로 표현하는 부분 
	               // <li class="step0 active text-center" id="step1"></li>
              // <li class="step0 active text-center" id="step2"></li>
              // <li class="step0 active text-center" id="step3"></li>
              // <li class="step0 text-muted text-end" id="step4"></li>
	// 조건을 만들고 해당되면 제이쿼리로 속성 class를 변경해 준다.
       	   
			console.log('state_arr : ' + state);
			  
			let tmpstr = ``;
			
			  switch (state){
				case "주문완료" :
                 tmpstr = ` <span class="d-flex justify-content-center align-items-center big-dot dot">
				 <i class="fa fa-check text-white"></i> </span> 
				 <hr class="flex-fill track-line " ><span class="dot"> </span> 
				 <hr class="flex-fill track-line"  ><span class="dot"></span> 
				 <hr class="flex-fill track-line"  ><span class="dot"> </span> `;				
				break;

				case "주문접수" :
                 tmpstr = ` <span class="d-flex justify-content-center align-items-center big-dot dot">
				 <i class="fa fa-check text-white"></i> </span> 
				 <hr class="flex-fill track-line " ><span class="dot"> </span> 
				 <hr class="flex-fill track-line"  ><span class="dot"></span> 
				 <hr class="flex-fill track-line"  ><span class="dot"> </span> `;
				  break;

				case "제작중" :
                  tmpstr = ` <span class="dot"></span> 
				 <hr class="flex-fill track-line " ><span            
				 class="d-flex justify-content-center align-items-center big-dot dot">
				 <i class="fa fa-check text-white"></i></span> </span> 
				 <hr class="flex-fill track-line"  ><span class="dot"></span>
				 <hr class="flex-fill track-line"  ><span class="dot"></span> `;	
				  break;
				  
				case "배송중" :
                  tmpstr = ` <span class="dot"></span> 
				 <hr class="flex-fill track-line " ><span class="dot"> </span> 
				 <hr class="flex-fill track-line"  ><span            
				 class="d-flex justify-content-center align-items-center big-dot dot">
				 <i class="fa fa-check text-white"></i></span> 
				 <hr class="flex-fill track-line"  ><span class="dot"></span> `;	
				  break;
				  
				case "배송완료" :
                 tmpstr = ` <span class="dot"></span> 
				 <hr class="flex-fill track-line " ><span class="dot"> </span> 
				 <hr class="flex-fill track-line"  ><span class="dot"></span> 
				 <hr class="flex-fill track-line"  ><span            
				 class="d-flex justify-content-center align-items-center big-dot dot">
				 <i class="fa fa-check text-white"></i></span> `;				  
				  break;

			}
			
			$('#statebar').html(tmpstr);
					
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });	   
	  
}
</script>