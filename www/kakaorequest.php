<?php //공통 : Config
$REST_API_KEY   = "a05cf8e20a592a3c7d518203a097f08e"; // 내 애플리케이션 > 앱 설정 > 요약 정보
$CLIENT_SECRET  = "lPE16DCvWsduAWTue9b51vocFCxxA9Y8"; // 내 애플리케이션 > 제품 설정 > 카카오 로그인 > 보안
$REDIRECT_URI   = urlencode("http://8440.co.kr/kakaorequest.php");
?>


<?php //공통 : API Call Function
function Call($callUrl, $method, $headers = array(), $data = array(), $returnType="jsonObject")
{
    echo "<pre>".$callUrl."</pre>";
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $callUrl);
        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($ch, CURLOPT_POST, false);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTP200ALIASES, array(400));
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        echo "<pre>".$status_code.":".$response."</pre>";
        
        if ($returnType=="jsonObject") return json_decode($response);
        else return $response;     
    } catch (Exception $e) {
        echo $e;
    }    
}
?>

<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" >
	    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script> 	
	<script src="https://developers.kakao.com/sdk/js/kakao.js"></script> 
	
	
<link rel="icon" type="image/x-icon" href="../favicon.ico">   <!-- 33 x 33 -->
<link rel="shortcut icon" type="image/png" href="../favicon.png">    <!-- 144 x 144 -->
<link rel="apple-touch-icon" type="image/png" href="../favicon.png">
	
<title>카카오톡 메시지 수신 동의</title>

  </head>
 <style> 
		  html,body {
		  height: 100%;
		}
</style>

<div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
	<div class="col-1"></div>
        <div class="col-10 text-center">
			<div class="card align-middle" style="width:20rem; border-radius:20px;">
				<div class="card" style="padding:15px;margin:10px;">
					<h3 class="card-title text-center" style="color:#113366;">미래기업 카카오톡 </h3>
				</div>	
				<div class="card-body text-center">
			  <form class="form-signin" method="get" action="login_result.php">
				<h5 class="form-signin-heading">메시지 수신동의</h5>
				
				<!--	<a href="https://kauth.kakao.com/oauth/authorize?client_id=<?=$REST_API_KEY?>&response_type=code&redirect_uri=<?=$REDIRECT_URI?>"><img src="//k.kakaocdn.net/14/dn/btqCn0WEmI3/nijroPfbpCa4at5EIsjyf0/o.jpg" width="222" /> </a>  -->
       				<a href="javascript:void(0)" onclick="kakaoLogin();" > <img src="//k.kakaocdn.net/14/dn/btqCn0WEmI3/nijroPfbpCa4at5EIsjyf0/o.jpg" width="222" /> </a>  
						<br> 
						<br>
     				<button type="button" class="btn btn-primary" onclick="kakaoLogout();" >  로그아웃  </button>

			  </form>			  
				</div>
       	   	</div>
			</div>		
				<div class="col-1"></div>
	  </div>

	</div>	

<script>
Kakao.init('2ddb841648d38606331320046099cf67'); //발급받은 키 중 javascript키를 사용해준다.
console.log(Kakao.isInitialized()); // sdk초기화여부판단
//카카오로그인
function kakaoLogin() {
kakaoLogout();    
  }
//카카오로그아웃  
function kakaoLogout() {
    if (Kakao.Auth.getAccessToken()) {
      Kakao.API.request({
        url: '/v1/user/unlink',
        success: function (response) {
        	console.log(response)
					Kakao.Auth.login({
						  success: function (response) {
							Kakao.API.request({
							  url: '/v2/user/me',
							  success: function (response) {
								  console.log(response)
							  },
							  fail: function (error) {
								console.log(error)
							  },
							})
						  },
						  fail: function (error) {
							console.log(error)
						  },
						})
			
			
        },
        fail: function (error) {
          console.log(error)
		  Kakao.Auth.login({
						  success: function (response) {
							Kakao.API.request({
							  url: '/v2/user/me',
							  success: function (response) {
								  console.log(response)
							  },
							  fail: function (error) {
								console.log(error)
							  },
							})
						  },
						  fail: function (error) {
							console.log(error)
						  },
						})
		  
		  
        },
      })
      Kakao.Auth.setAccessToken(undefined)
    }
	
else
{	
			  Kakao.Auth.login({
						  success: function (response) {
							Kakao.API.request({
							  url: '/v2/user/me',
							  success: function (response) {
								  console.log(response)
							  },
							  fail: function (error) {
								console.log(error)
							  },
							})
						  },
						  fail: function (error) {
							console.log(error)
						  },
						})
	
  }  
}
  
  
  
</script>


</body>
</html>