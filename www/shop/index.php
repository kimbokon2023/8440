<?php
if(!isset($_SESSION))      
        session_start(); 
	$user_name= $_SESSION["name"];
	$user_id= $_SESSION["userid"];
	

  require_once("../lib/mydb.php");
  $pdo = db_connect();
  
  $ip_address = $_SERVER["REMOTE_ADDR"];

  $ip_address = 'ip_address : '.$ip_address;  
  
// 접속 ip 기록
 $data=date("Y-m-d H:i:s") . " - " . $_SESSION["userid"] . " - " . $_SESSION["name"] . '  ' . $ip_address ;	 
 $pdo->beginTransaction();
 $sql = "insert into mirae8440.logdata(data) values(?) " ;
 $stmh = $pdo->prepare($sql); 
 $stmh->bindValue(1, $data, PDO::PARAM_STR);   
 $stmh->execute();
 $pdo->commit(); 
 
 
// 서버의 정보를 읽어와 랜덤으로 메인화면 꾸미기
 				
$sql="select * from mirae8440.shopitem order by num desc" ; 					

$numarr = array();
$catagoryarr = array();
$itemarr = array();
$itemdesarr = array();
$filenamearr = array();
$youtubearr1 = array();
$youtubearr2 = array();

try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

 			  $num=$row["num"];			  
 			  $catagory=$row["catagory"];			  
 			  $dporder=$row["dporder"];			  
			  		  
			  $item=$row["item"];			  
			  $itemdes=$row["itemdes"];
			  $sale=$row["sale"];			  
			  $price=$row["price"];
			  $saleprice=$row["saleprice"];
			  $filename1=$row["filename1"];	 
			  $youtube1=$row["youtube1"];	 
			  $youtube2=$row["youtube2"];	 
			
			   array_push($numarr, $num );
			   array_push($catagoryarr, $catagory );
			   array_push($itemarr, $item );
			   $vowels = array("\n", "\r", "\r\n", "\n\r");
			   $tmparr = str_replace($vowels,'<br/>',$itemdes);
			   array_push($itemdesarr, $tmparr );			   
			   array_push($filenamearr, $filename1 );	  
			   array_push($youtubearr1, $youtube1 );	  
			   array_push($youtubearr2, $youtube2 );	  
			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
 
// 랜던하게 자료 추출 
$rnd = rand(0, count($catagoryarr)-1);
$rndcatagory = $catagoryarr[$rnd];
$rnditem = $itemarr[$rnd];
$vowels = array("\n", "\r", "\r\n", "\n\r");
$rnditemdes = str_replace($vowels,'<br>',$itemdesarr[$rnd]);
$rndfilename = $filenamearr[$rnd];
$rndyoutube1 = $youtubearr1[$rnd];
$rndyoutube2 = $youtubearr2[$rnd];
  
    	
?>

<!DOCTYPE html>
<html lang="ko">
<head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />		

<?php //if (preg_match('/(facebook|kakaotalk)/',$_SERVER['HTTP_USER_AGENT']) == true) {  ?>
<meta property="og:type" content="나만의 작품">
<meta property="og:title" content="이제 멋진 금속액자형 작품과 만나보세요">
<meta property="og:url" content="8440.co.kr/shop">
<meta property="og:description" content="멋진 금속 제작품을 선물해 보세요!">
<meta property="og:image" content="http://8440.co.kr/img/thumbnail.jpg"> 

		
        <title> Steel Art Mall</title> <div id=cookiedisplay> </div>
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

    </head>
		
<div class="container">  
	
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">작품 상세정보</h4>
        </div>
        <div class="modal-body">
		
		<input type="hidden" name="saleprice_val" id="saleprice_val" > 
		<input type="hidden" name="cartsave" id="cartsave" > 
		  
		  작품번호 : <input type="text" name="test" id="test" size="2" value=""/> <br><br>
		  
		  				
           <div class="row gx-4 gx-lg-4 align-items-center">
           <div class="col-md-5"><img id="imgID" class="card-img-top mb-5 mb-md-0" src="" alt="..." >
		   <br>
		   <br>
		   <br> <p class=lasercut >  </p>
			<div class="embed-responsive embed-responsive-16by9">
				<iframe id=youtubeID class="embed-responsive-item" src="<?=$youtube1?>"  frameborder="0" allowfullscreen></iframe>
			</div> 		   

		   <br> <p class=workdone >  </p>
			<div class="embed-responsive embed-responsive-16by9">
				<iframe id=youtubeIDsecond class="embed-responsive-item" src="<?=$youtube1?>"  frameborder="0" allowfullscreen></iframe>
			</div> 
		   
		   </div>
           <div class="col-md-7">
                        <div class="small mb-5">
                        <h1 id="catagory_sub" class="display-5 fw-bolder"> </h1>
                        <div id="item_sub" class="fs-1 mb-5" > </div>
						
                        <div id="itemdes_sub" class="fs-3 mb-5" > </div>	   
					  
					  <div class="d-flex justify-content-center large text-warning mb-2"> 
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                    </div>		
					  
					   <div class="d-flex fs-2 mb-2">
                       <span id="price_sub" class="text-decoration-line-through"> 11</span> &nbsp;								
					   <span id="salepricerate" style="color:red;font-weight:bold;"> </span>  &nbsp;	
					     </div> 
						 <div class="d-flex fs-2 mb-5">
					   <span  style="color:blue;"> 판매가 </span>  &nbsp;
					   <span id="saleprice_sub" > </span> 						                           
                       </div> 			
                        
                        <div class="d-flex">                          
								<div class="minus">  <a href="javascript:change_qty2('m')"><img src="./imgsub/minus.png"  style="width:40px;height:40px;"  alt="-"></a></div> &nbsp;						
                                <input name="quantity" id="quantity"  class="form-control text-center me-2" type="num" value="1" style="max-width: 5rem" readonly="readonly"/>																								
								<div class="plus">  <a href="javascript:change_qty2('p')"><img src="./imgsub/plus.png" style="width:40px;height:40px;" alt="+"></a></div>
                                
								<input name="total_num" id="total_num"  class="form-control fs-2 text-right me-2" type="num" value="1" style="max-width: 15rem" readonly="readonly"/>	
							   </div>
							   <br>
							<div class="d-flex">
                            <button type="button" id="addcart"   class="btn btn-outline-dark mt-auto fs-1" > 
                                <i class="bi-cart-fill me-1"></i>
                                장바구니 넣기                           </button>
							</div>
													
							</div>
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
	
    <body id="page-top">
	

        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
			  <div class="d-flex justify-content-left"> 
			  <? if(isset($_SESSION["name"])) print '<a class="navbar-brand" href="../index.php"> Metal Art</a>';
			      else  print '<a class="navbar-brand" href="#!"> Steel Art</a>';  ?>				  
                  </div>
			</div>
				<div class="container px-4 px-lg-5">
                
                    <form class="d-flex">
                        <button  class="btn btn-outline-dark" type="button" onclick="javascript:movetocart();">
                            <i class="bi-cart-fill me-1"></i>
                            장바구니
                            <span id="cartnum" class="badge bg-dark text-white ms-1 rounded-pill"><?=$cartnum?> </span>
                        </button> 
						&nbsp;
						<button  class="btn btn-outline-dark" type="button" onclick="javascript:movetodelivery();">
						 
                            <i class="bi bi-credit-card-fill"></i>
                            주문&배송정보                            
                        </button>
						<? 
						  if($user_name=='김보곤')
						  {
						    print '&nbsp;&nbsp;   <button class="btn btn-outline-dark" type="button" onclick="javascript:movetolist();">
                            <i class="bi-tool-fill me-1"></i>
                            등록
                        </button> ';													?>
						
						    &nbsp;&nbsp;   <button class="btn btn-outline-dark" type="button" onclick="location.href='admin.php'">
                            <i class="bi-tool-fill me-1"></i>
                            대쉬
                        </button> 
						<?
							}						
						?>

                    </form>
                </div>            
        </nav>
		
		

        <!-- Masthead-->
        <header class="masthead">
            <div class="container">
                <div class="masthead-subheading">나만의 스타일</div>
                <div class="masthead-heading text-uppercase">당신과 어울리는 멋진 작품과 만나보세요!</div>
                <a class="btn btn-primary btn-xl text-uppercase" href="#services">Tell Me More</a>
            </div>
        </header>		
		
		<br><br>
		
        <!-- Material-->
		<div id=Materialshow >
		<h2 class="justify-content-center text-center"> 작품 재질 설명</h2>
		</div>
		<div id=Material   style="display:none;" >
        <section class="page-section" >
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">최고급 스테인리스 304 사용</h2>
                    <h3 class="section-subheading text-muted">SUS/STS 304 Stainless 제품</h3>
                </div>
                <div class="row text-center">
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                           <img class="card-img-top mb-2 mb-md-0" src="./assets/img/stainless.jpg" alt="..." onmouseenter="zoomIn(event)" 
        onmouseleave="zoomOut(event)" >
                        </span>
                        <h4 class="my-3">304 스테인리스 사용</h4>
                        <p class="text-muted">304 스테인레스는 식품 장비, 일반 화학 장비 및 원자력 산업 장비의 스테인레스 내열강으로 사용됨. 304 스테인리스 강의 방청 성능은 200 시리즈 스테인리스 강보다 강하고, 스테인레스 내식성이 우수함. 304 스테인레스 스틸은 끓는 온도보다 65 % 이하의 농도로 질산에서 강한 내식성을 가지고 있음. 또한 알칼리 용액과 대부분의 유기 및 무기산에 대한 내식성 우수. 스테인레스 스틸은 표면이 아름답고 내식성이 좋고, 도금과 같은 표면 처리를 할 필요가 없으며 스테인리스 강 고유의 표면 특성을 발휘합니다. 그것은 일반적으로 스테인레스 스틸이라고 불리는 많은 A종류의 강철에 사용됨, 13 크롬강과 18-8 크롬-니켈강과 같은 고 합금강이 성능을 나타냄.</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                           <img class="card-img-top mb-2 mb-md-0" src="./assets/img/tistainless.jpg" alt="..." onmouseenter="zoomIn(event)" 
        onmouseleave="zoomOut(event)">
                        </span>
                        <h4 class="my-3">티타늄 소재 (Gold, Bronze, etc)</h4>
                        <p class="text-muted">티타늄 합금의 다양한 온도에서의 높은 강도, 강성, 인성, 낮은 밀도, 좋은 내식성을 지니는 티타늄의 특성은, 항공 우주 구조 및 기타 고성능 애플리케이션에 무게 감소를 가능하게 함. 
티타늄의 원자량은 47.88임. 티타늄은 가볍고, 강하고, 내식성과 자연에 풍부하게 존재함. 티타늄의 유용한 특성 중 하나는 높은 용융점이고 3135°F (1725 °C). 이 융점은 철강 보다 약 400 °F 높으며 알루미늄보다 약 2000 °F 높음. 티타늄은 무 독성이며, 인간의 조직 및 뼈와 생물학적으로 호환됨. 훌륭한 내식성, 생체적합성 및 강도는 티타늄을 화학, 석유화학, 해양 환경, 바이오 재료용으로 유용하게 사용되고 있음.</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
						 <img class="card-img-top mb-2 mb-md-0" src="./assets/img/frame.jpg" alt="..." onmouseenter="zoomIn(event)" 
        onmouseleave="zoomOut(event)">
                        </span>
                        <h4 class="my-3">편리한 액자형태 제작</h4>
                        <p class="text-muted">집안에서 손쉽게 벽에 걸 수 있도록 철판끝을 액자형태로 4면절곡해서 입체감을 살렸으며, 상부의 벽과 고정되는 형태의 홀은 3개로 중앙에 1개만 고정 가능하고, 또한 양쪽 2개홀 고정도 가능하도록 제작되었음.</p>
                    </div>
                </div>
            </div>
        </section>		
		</div>
		
        <!-- Product section-->
        <section class="py-5" id="services" >
            <div class="container px-4 px-lg-5 my-5"  onclick="javascript:OrderDialog('<?=$numarr[$rnd]?>')" >
                <div class="row gx-4 gx-lg-5 align-items-center" >
                    <div class="col-md-6">
					<img class="card-img-top mb-5 mb-md-0" src="<?=$rndfilename?>" alt="..." onmouseenter="zoomIn(event)" 
							onmouseleave="zoomOut(event)"></div>
                    <div class="col-md-6">
                        <div class="small mb-1 fs-1"><?=$rndcatagory?></div>
                        <h1 class="display-5 fw-bolder"><?=$rnditem?></h1>
						<br>
                        <p class="lead "> <?=$rnditemdes?> </p>
                        
                    </div>
                </div>
            </div>
        </section>
        <!-- Related items section-->
        <section class="py-5 bg-light">
            <div class="container px-4 px-lg-5 mt-5">
                <h2 class="fw-bolder mb-4"> 관련 상품 </h2>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
				
<?

$num_arr = array();
$catagory_arr = array();
$item_arr = array();
$itemdes_arr = array();
$sale_arr = array();
$price_arr = array();
$saleprice_arr = array();
$filename_arr = array();
$youtube1_arr = array();
$youtube2_arr = array();

 try{
   $sql = "select * from mirae8440.shopitem order by dporder asc";

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		      $num=$row["num"];
 			  $catagory=$row["catagory"];			  
 			  $dporder=$row["dporder"];			  
			  		  
			  $item=$row["item"];			  
			  $itemdes=$row["itemdes"];
			  $sale=$row["sale"];			  
			  $price=$row["price"];
			  $saleprice=$row["saleprice"];
			  $filename1=$row["filename1"];	 	

              $imgurl1="./img/" . $filename1;	
			  
			  $youtube1=$row["youtube1"];	 
			  $youtube2=$row["youtube2"];	 
			
			  			  

$catagory_arr[$num] = $catagory ;
$item_arr[$num] = $item ;
$itemdes_arr[$num] = $itemdes ;
$sale_arr[$num] = $sale ;
$saleprice_arr[$num] = $saleprice ;
$price_arr[$num] = $price ;
$filename_arr[$num] = $filename1 ;
$youtube1_arr[$num] = $youtube1 ;
$youtube2_arr[$num] = $youtube2 ;
	
$price1 = '￦' . number_format($price) ;
$offrate = (int) (($price-$saleprice)/$price * 100);
$price2 =  $offrate . '%Sale' ;
$price3 =  '￦' . number_format($saleprice) ;

	 		
				?>			
                  <div class="col mb-5">			     
                        <div class="card h-100" onclick="OrderDialog('<?=$num?>')" >
                            <!-- Product image-->
                            <img class="card-img-top" src="<?=$filename1?>" onmouseenter="zoomIn(event)" onmouseleave="zoomOut(event)">
		     
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center fs-3">
                                    <!-- Product name-->
                                    <h4 class="fw-bolder"> <?=$item?> </h4>
                                    <!-- Product reviews--> 
                                 <?   
									if($sale='적용') 
										?>
									    <div class="d-flex justify-content-center small text-warning mb-2"> 
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                    </div>									
									
                                    <!-- Product price-->
                                   <span class="text-decoration-line-through"> <?=$price1?> </span> &nbsp; 								
							       <span style="color:red;font-weight:bold;"> <?=$price2?> </span> 								   
									<span> <?=$price3?> </span>
                                </div>
                            </div>
                            <!-- Product actions-->
                            
							<div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"> <button class="btn btn-outline-dark " data-id='<?=$num?>' id="OrderDialog"> 장바구니 담기 </button></div>
                            </div>  
                        </div>
					
                    </div> 
<?
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }	
?> 					
			


					
                </div>
            </div>
        </section>
 <!-- youtube section-->
        <section class="py-5 bg-light">
            <div class="container px-5 px-lg-5 mt-4">
                
	 <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-xl-1 justify-content-center">
	<div class="embed-responsive embed-responsive-16by9">
	  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/u2Xar8El-Oo"  frameborder="0" allowfullscreen></iframe>
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

function OrderDialog(num){
 var catagory_arr = <?php echo json_encode($catagory_arr);?> ;
 var item_arr = <?php echo json_encode($item_arr);?> ;
 var itemdes_arr = <?php echo json_encode($itemdes_arr);?> ;
 var sale_arr = <?php echo json_encode($sale_arr);?> ;
 var saleprice_arr = <?php echo json_encode($saleprice_arr);?> ;
 var price_arr = <?php echo json_encode($price_arr);?> ;
 var filename_arr = <?php echo json_encode($filename_arr);?> ;
 var youtube1_arr = <?php echo json_encode($youtube1_arr);?> ;
 var youtube2_arr = <?php echo json_encode($youtube2_arr);?> ;

     // var num = $(this).data('id');
     $(".modal-body #test").val( num );
	 
	 $("#imgID").attr("src", filename_arr[num]);	
	 console.log(youtube1_arr[num]);
	 // 초기화
	 <!-- $(".workdone").text("");		  -->
	 <!-- $(".lasercut").text("");			  -->
	 <!-- $("#youtubeID").attr("src", '');	 -->
		<!-- $("#youtubeIDsecond").attr("src", '');	 -->
	 
	 if(!isEmpty(youtube1_arr[num]))
	   {         	   
		 $(".lasercut").text("Laser Cut");			 
		 $("#youtubeID").attr("src", youtube1_arr[num]);	
		 }
	 if(!isEmpty(youtube2_arr[num]))
	   {         	   		 
		 $(".workdone").text("완성품");			 	   
		$("#youtubeIDsecond").attr("src", youtube2_arr[num]);	
		}
     // 초기화
     $("#quantity").val('1');	 
	 
     result = price_arr[num].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	 tmp = '￦' + result;	 
     $("#price_sub").text( tmp );
	 
	 $("#saleprice_val").val( saleprice_arr[num] );
	 result = saleprice_arr[num].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	 tmp = '￦' + result;
     $("#saleprice_sub").text( tmp );
	 
	  // 주문금액 보여주기	  
	  $("#total_num").val(tmp);	 
	 
	 pricerate = Math.floor((price_arr[num]-saleprice_arr[num])/price_arr[num] * 100);
	 result = pricerate.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	 tmp =  result + '%OFF  ';	 
	  $("#salepricerate").text( tmp );
	  	  
	  $("#item_sub").text( item_arr[num] );
	  
	  var str = itemdes_arr[num];
      var words = str.split('\n');
	  
	  console.log(words);
	  // div 내용을 비우기
	  $( '.explain' ).empty();
	  // 개행문자 처리해서 문단을 아름답게 꾸미기
	  // d-flex 구문으로 br 이나 \n 사용하지 못함.
	  for(i=0;i<words.length;i++)
	     $( '#itemdes_sub' ).before( '<p class = "explain fs-3 mb-5" > ' + words[i] + ' </p>' );
	  //tmparr = replaceAll(itemdes_arr[num], '\n', "<br/>");
	  //tmparr = replaceAll(tmparr, '\r', "<br/>");
	  //tmparr = replaceAll(tmparr, '\n\r', "<br/>");
	  //tmparr = replaceAll(tmparr, '\r\n', "<br/>");    
	  
	  //tmparr = replaceAll(itemdes_arr[num], '\r\n', "<br/>");    
	  
	  // const str = tmparr.replace(/\\r\\n|\\n|\\r/gm,"<br/>")

	  // $("#itemdes_sub").css("flex-wrap", "wrap");	
	 //  $("#itemdes_sub").text(itemdes_arr[num]);
	  $("#catagory_sub").text( catagory_arr[num] );
	  	 
     // As pointed out in comments, 
     // it is superfluous to have to manually call the modal.
     $('#myModal').modal('show');

}

$(document).on("click", "#addcart", function () { 

let num = $("#test").val();
let cartsave = $("#cartsave").val();
let quantity = $("#quantity").val();  
let existQuantity = 0;

// 쿠키 불러옴
let getcart = getCookie("shopcart");

	if(getcart!=null)
	{	
	 console.log('자료있음');
		
	Objectcart = JSON.parse(getcart);

	let Cartcount=Object.keys(Objectcart).length;

	console.log('카트담긴 수 : ' + Cartcount);

		// 기존카트에 작품번호가 있으면 수정해야 함.
		// 기존카트에서 있는지 확인
	for(i=0;i<Cartcount;i++)
	{
	  console.log('쿠키아이디 ' + Objectcart[i].id);  
	  if(Objectcart[i].id==num)
		  {
			existNum = Objectcart[i].id;
			existQuantity = Number(Objectcart[i].quantity);
			cartdel(num);
			}
	}

	//console.log(getcart);
	//console.log(Cartcount);

	var data = new Object();

	data.id = num;

	if(Number(existQuantity)>0)   // 기존의 카트 수량이 있으면 더해준다.
		data.quantity = Number(quantity) + Number(existQuantity) ;
	   else	
			data.quantity = quantity;
			
	Objectcart.push(data);

	console.log(Objectcart);
	setCookie ('shopcart', JSON.stringify(Objectcart), 3600);   // 쿠키에 저장함

	reloadShopCart(); // 장바구니 수량 화면 수정

}
else
{
	 console.log('null 상태에서 추가');
		
	Objectcart = new Array();

	var data = new Object();

	data.id = num;
	data.quantity = quantity;	
	Objectcart.push(data);

	console.log(Objectcart);
	setCookie ('shopcart', JSON.stringify(Objectcart), 3600);   // 쿠키에 저장함

	reloadShopCart(); // 장바구니 수량 화면 수정

}


 
$('#myModal').modal('hide');

// 장바구니 이동여부 물어보기
if(confirm("장바구니로 이동하시겠습니까?")) 
   location.href = './cart.php';       


});

function change_qty2(t){
  //var min_qty = '수량버튼'*1;
  var min_qty = 1;
  var this_qty = parseInt($("#quantity").val());  
  let saleprice = $("#saleprice_val").val();	
  var max_qty = '200'; // 현재 재고
  if(t=="m"){
    this_qty -= 1;
    if(this_qty<min_qty){
      //alert("최소구매수량 이상만 구매할 수 있습니다.");
      this_qty = 1;
      }
    }
    else if(t=="p"){
      this_qty += 1;
      if(this_qty>max_qty){
        alert("죄송합니다. 재고가 부족합니다.");
        return;
        }
    }
	
	amount = this_qty * saleprice ;
	$("#quantity").val(this_qty);
	
	const cn2 = amount.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
	$("#total_num").val('￦' + cn2);
	
	
}



function replaceAll(str, searchStr, replaceStr) {

   return str.split(searchStr).join(replaceStr);
}





</script>
