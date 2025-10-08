<?php require_once __DIR__ . '/../bootstrap.php';

$id=$_REQUEST["uid"] ?? '';
$pw=$_REQUEST["upw"] ?? '';

if (empty($id) || empty($pw)) {
    header('Location: ' . getBaseUrl() . '/login/login_form.php');
    exit; 
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

    <title>시스템 로그인</title>

  </head>

  <body cellpadding="0" cellspacing="0" width="100%" height="100%">
		
	<?php

try{
    $sql="select * from mirae8440.member where id=?";
    $stmh=$pdo->prepare($sql);
    $stmh->bindValue(1,$id,PDO::PARAM_STR);
    $stmh->execute();
    $count=$stmh->rowCount();
} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();

}

$row=$stmh->fetch(PDO::FETCH_ASSOC);
if($count<1) {
    ?>
<script>
alert("아이디가 틀립니다!");
history.back();
</script>

<?php
}  elseif($pw!=$row["pass"]) {
    ?>
<script>
  alert ("비밀번호가 틀립니다.!");      
  history.back();
</script>
<?php
} else {
    $_SESSION["userid"]=$row["id"];
    $_SESSION["name"]=$row["name"];
    $_SESSION["nick"]=$row["nick"];
    $_SESSION["level"]=$row["level"];
    $_SESSION["ecountID"]=$row["ecountID"];
    $_SESSION["part"]=$row["part"];	
    $_SESSION["eworks_level"]=$row["eworks_level"];	
    $_SESSION["position"]=$row["position"];	
    $_SESSION["hp"]=$row["hp"];	
    $_SESSION["DB"]=$DB ;
	
 	
$data=date("Y-m-d H:i:s") . " - " . $_SESSION["userid"] . " - " . $_SESSION["name"] ;	
$pdo->beginTransaction();
$sql = "insert into ".$DB.".logdata(data) values(?) " ;
$stmh = $pdo->prepare($sql); 
$stmh->bindValue(1, $data, PDO::PARAM_STR);   
$stmh->execute();
$pdo->commit(); 

// 로그인 성공 시 쿠키 설정
setcookie("showTodoView", "show", time() + 86400, "/"); // 1일 동안 유효
setcookie("showBoardView", "show", time() + 86400, "/"); // 1일 동안 유효

if(isset($_SESSION["url"])) {
    $redirectUrl = $_SESSION["url"];
    unset($_SESSION["url"]); // 리디렉션 후 세션에서 URL 제거
    header('Location: ' . $redirectUrl);
    exit;
}
	
	
// 우성스틸 테스트화면
  	if($_SESSION["userid"]=='woosung') 
	{
   header ("Location:../woosung/index.php");
	exit;  
	} 	
	
// 덴크리 분기
  	if($_SESSION["userid"]=='3675')  // 김재현님 전번뒷자리
	{
   header ("Location:../outorder/list.php");
	exit;  
	}  
// 서한컴퍼니 분기  -> 덴크리 리스트로 이동
  	if($_SESSION["userid"]=='sc') 
	{
   header ("Location:../outorder/list.php");
	exit;  
	} 
// 다온텍 분기  -> 덴크리 리스트로 이동
  	if($_SESSION["userid"]=='abg') 
	{
    header ("Location:../outorder/list.php");
	exit;  
	} 
	
// 은성 관리자화면으로 분기
  	if($_SESSION["userid"]=='esadmin') 
	{
   header ("Location:../es/esadmin.php");
	exit;  
	}
 
 	if($_SESSION["part"]=='es') 
	{
   header ("Location:../es/index.php");
	exit;  
	}
	
	if($_SESSION["level"]==8) 
	{
		header ("Location:../p/index.php");
		exit;  
	}

	if($_SESSION["level"]==20) 
	{
		header ("Location:../phomi/list.php");
		exit;  
	}
	
	   elseif ($_SESSION["name"]=="이경묵")
	   {
			   header ("Location:../index2.php");
				exit;  		   
	   }	  
	   elseif ($_SESSION["userid"]=='5438'||$_SESSION["userid"]=='5342'||$_SESSION["userid"]=='8210'||$_SESSION["userid"]=='6113')
	   {
			   header ("Location:../m/index.php");
				exit;  		   
	   }
	   	   elseif ($_SESSION["userid"]=='m')
	   {
			   header ("Location:../display.php");
				exit;  		   
	   }
	   	   elseif ($_SESSION["userid"]=='5528' || $_SESSION["userid"]=='4561' || $_SESSION["userid"]=='nuri'  || $_SESSION["userid"]=='6013' || $_SESSION["userid"]=='0825' || $_SESSION["userid"]=='7781' || $_SESSION["userid"]=='7898' || $_SESSION["userid"]=='5570'|| $_SESSION["userid"]=='3240')  // 진우 추가 , 한진엘리베이터추가, 대광엘리베이터, GSE추가
	   {
			   header ("Location:../partner/index.php"); //우성스틸 화면으로 이동/파트너 한에스티 엘리브디자인
				exit;  		   
	   }	   
		elseif($_SESSION["level"]<8) {
			   header ("Location:../index2.php");
				exit;  
				}
}

?>

</body>
</html>