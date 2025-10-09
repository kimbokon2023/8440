 <?php
    if(!isset($_SESSION)) 
    { 
       session_start(); 
    } 
?>
  
 
   <a href="../index.php"><img src="../img/logo.png" border="0"></a>
	<img src="../img/moto.jpg">&nbsp;<img src="../img/8440qrcode.png" style="margin-top:3px;width:40px; border:0; " >
	&nbsp;&nbsp;
	
	<button type="button" class="btn btn-outline-secondary btn-sm" onclick="location.href='../qc/menu.php';"> 장비점검 </button> 
	<button type="button" class="btn btn-outline-secondary btn-sm" onclick="location.href='../error/index.php';"> 부적합 품질보고 </button> 
	<button type="button" class="btn btn-outline-secondary btn-sm" onclick="location.href='../annualleave/index.php';"> 연차 </button> 
&nbsp;	
<?php
    if(!isset($_SESSION["userid"]))
	{
?>
          <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
<?php
	}
	else
	 {
?>
	<?=$_SESSION["nick"]?> (lv:<?=$_SESSION["level"]?>) | 
		<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
		
		<?php
		if($_SESSION["userid"]=='a' || $_SESSION["userid"]=='mirae')
		print ' | <a href="../automan/list.php"]?> 전산실장 정산</a>';
	 }
?>
	 
