<?php
if(!isset($_SESSION))      
        session_start(); 
	$user_name= $_SESSION["name"];
    $user_id= $_SESSION["userid"];
	
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";	

  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode=""; 
	
 // ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
 
require_once("../lib/mydb.php");
$pdo = db_connect();     

// 작품명 배열에 넣기

print 'No : ' . $num;
 	 
	 try{  
      $sql = "select * from mirae8440.shop where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC); 

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
			$payment= $row["payment"];    // 결재확인중, 결재완료   두가지 형태 결과
			
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
	

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
 } 	
	
$totalsaleprice += $deliveryfee;	
   	
?>

<!DOCTYPE html>
    <head>        
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>자료수정하기 </title> <div id=cookiedisplay> </div>
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
          <h4 class="modal-title">수정완료 알림</h4>
        </div>
        <div class="modal-body">		
		   <div class="fs-1 mb-5 justify-content-center" >
		  자료가 수정되었습니다. <br> 
		   <br> 		  
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<form  id="board_form" name="board_form" method="post" action="registerorder.php?mode=modify&num=<?=$num?>"  enctype="multipart/form-data" > 			

<!-- 배송정보 section-->
<section class="py-0">        
         
<div class="container">
    <div class="input-form-backgroud row">
      <div class="input-form col-md-12 mx-auto">

        <form class="validation-form" novalidate>
	     <div class="row">
        <h3 class="mb-3">정보수정 &nbsp; <button class="btn btn-success btn-lg btn-block" type="button" id=updateBtn > 수정하기</button> </h3>
		</div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="state">진행상태</label>              
			  <select class="form-control" name="state" id="state" >
			    <?php
				   $opt = ["주문완료","주문접수","제작중","배송중","배송완료"];
				   
		   for($i=0;$i<count($opt);$i++) {
			     if($state==$opt[$i])
							print "<option selected value='" . $opt[$i] . "'> " . $opt[$i] .   "</option>";
					 else   
  						 print "<option value='" . $opt[$i] . "'> " . $opt[$i] .   "</option>";
		      } 		   
		     ?>
                </select>			
              
            </div>
              <div class="col-md-6 mb-3">
              <label for="totalsaleprice">결재금액 </label>
              <input type="text" class="form-control" id="totalsaleprice" name="totalsaleprice"  value="<?=$totalsaleprice?>" disabled >              
            </div>
          </div>			
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="deliveryfee">배송비</label>
              <input type="text" class="form-control" id="deliveryfee" name="deliveryfee"  value="<?=$deliveryfee?>" required>
              
            </div>
              <div class="col-md-6 mb-3">
              <label for="payment">결재여부 </label>
			  <select class="form-control" name="payment" id="payment" >
			    <?php
						   $opt = ["결재확인중","결재완료"];
						   
				   for($i=0;$i<count($opt);$i++) {
						 if($payment==$opt[$i])
									print "<option selected value='" . $opt[$i] . "'> " . $opt[$i] .   "</option>";
							 else   
								 print "<option value='" . $opt[$i] . "'> " . $opt[$i] .   "</option>";
					  } 		   
					 ?>
                </select>	
            </div>
          </div>		
		
           <div class="mb-3">
              <label for="orderlist">주문현황 Raw Data</label>
              <input type="text" class="form-control" id="orderlist" name="orderlist" value="<?=$orderlist?>" required>            
            </div>	
           <div class="mb-3">
              <label for="password">비밀번호(주문수정시 사용)</label>
              <input type="text" class="form-control" id="password" name="password" value="<?=$password?>" required>
              <div class="invalid-feedback">
                비밀번호를 입력해주세요.
              </div>
            </div>		
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="name">주문하신분 성함</label>
              <input type="text" class="form-control" id="name" name="name"  value="<?=$name?>" required>
              <div class="invalid-feedback">
                이름을 입력해주세요.
              </div>
            </div>
              <div class="col-md-6 mb-3">
              <label for="tel">연락처 </label>
              <input type="text" class="form-control" id="tel" name="tel"  value="<?=$tel?>" required>
              <div class="invalid-feedback">
                전화번호를 입력해주세요.
              </div>
            </div>
          </div>
		  
		  
          <div class="row">
            <div class="col-md-6 mb-3">										  
							  <label  for="receivename">
								배송 받으실분 이름 
							  </label>							  
              <input type="text" class="form-control" id="receivename" name="receivename" value="<?=$receivename?>" required>

            </div>
            <div class="col-md-6 mb-3">
              <label for="receivetel">배송 받을분 연락처</label>
              <input type="text" class="form-control" id="receivetel"  name="receivetel" value="<?=$receivetel?>" required>

            </div>
          </div>		  
		  
          <div class="row">		  
            <div class="col-md-6 mb-3">
            <label for="email">이메일 <span class="text-muted">&nbsp;(필수 아님)</span> </label>
            <input type="email" class="form-control" id="email" name="email" value="<?=$email?>" >
            <div class="invalid-feedback">
              이메일을 입력해주세요.
            </div>
          </div>	
		  </div>	

          <div class="mb-3">
            <label for="address">배송받으실 주소</label>
            <input type="text" class="form-control" id="address"  name="address" value="<?=$address?>" required>
            <div class="invalid-feedback">
              주소를 입력해주세요.
            </div>
          </div>

          <div class="mb-3">
            <label for="address2">세부 상세주소 <span class="text-muted">&nbsp;(필수 아님)</span></label>
            <input type="text" class="form-control" id="address2" name="address2" value="<?=$address2?>"  >
          </div>
		  
          <div class="mb-3">
            <label for="request">기타 배송시 요청사항 <span class="text-muted">&nbsp;(필수 아님)</span> </label>
            <input type="text" class="form-control" id="request" name="request" value="<?=$request?>"  >
          </div>

          <div class="row">

            <div class="col-md-4 mb-3">
              <label for="code">추천인 코드(있을시)</label>
              <input type="text" class="form-control" id="code"  name="code" value="<?=$code?>"  >
              <div class="invalid-feedback">
                추천인 코드를 입력해주세요.
              </div>
            </div>
          </div>
          
        </form>
      </div>
    </div>
</div>
</section>		 
		 
</form>
  		
	
</body>
	
	


	
</html>

<script>


$(document).ready(function(){
	
// 수정 버튼 클릭시
$("#updateBtn").click(function(){      
	// 모달 띄워주고
	$('#myModal').modal('show');
	// 2초후 실행 폼전송
	setTimeout(function() {
	// form submit	
	
	$('#myModal').modal('hide');
	$("#board_form").submit(); 		
	
	//자식창 닫고 부모창 reload
	//opener.document.location.reload();		
	//self.close();    
 	
	}, 500);


});


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
	
	
}



</script>
