 <!-- Work List -->
<?php
 session_start();
 $user_name= $_SESSION["name"];
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"])) {
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }
   
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header ("Expires: 0"); // rfc2616 - Section 14.21   

//header("Refresh:0");  // reload refresh   
	  
 if(isset($_REQUEST["new"]))
       $new=$_REQUEST["new"];
   else
       $new="";   
	  
 ?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미래기업 추억의 테트리스</title>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<script src="../js/common.js"></script>  
<link rel="stylesheet" href="css/style.css">
	
</head>
<body>

 <input id=score name=score type=hidden >
 <input id=restartVal name=restartVal type=hidden >
 <input id=user_name name=user_name type=hidden value="<?=$user_name?>">
 
<!--
                <div class="input-group p-3 mb-0">	                                    
                     <span class="input-group-text align-items-center" style="width:450px;">											
							랭킹 기록 (일시정지 ESC키)							
							</span>							
                          <H4> <span style="color:blue;">   &nbsp; &nbsp; &nbsp;  미래기업 테트리스 최강자(숨은 고수를 찾아라!) </span> </h4>
						</div>						 -->		
				
  <div class=total>
	<div class="total leftsection" >  				
	    <div class="input-group p-0 mb-2">	 
			 <span class="input-group-text align-items-center" style="width:50px;" >순위</span>
			 <span class="input-group-text align-items-center" style="width:100px;" >성명</span>
			 <span class="input-group-text align-items-center" style="width:100px;" >점수</span>
			 <span class="input-group-text align-items-center" style="width:200px;" >기록일시</span>
			 <span class="input-group-text align-items-center" style="width:250px;" >랭킹 기록 (일시정지 ESC키)	</span>
	</div>	
		
 <?php
 
require_once("../lib/mydb.php");
$pdo = db_connect();	

$sql="select * from mirae8440.tetris order by score desc";
$start_num =1;
   try{  
	  $stmh = $pdo->query($sql);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp=$stmh->rowCount();    
 	  
	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
	  if($start_num<=30) {	  
		  $num=$row["num"];
		  $rec_date=$row["rec_date"];
		  $name=$row["name"];
		  $score=$row["score"];
	  
?>	  
	 	    <div class="input-group p-0 mb-0">	 
			 <span class="input-group-text align-items-center" style="width:50px;" ><?=$start_num?> </span>
			 <span class="input-group-text align-items-center" style="width:100px;" ><?=$name?></span>
			 <span class="input-group-text align-items-center" style="width:100px;" ><?=$score?></span>
			 <span class="input-group-text align-items-center" style="width:200px;" ><?=$rec_date?></span>
	          </div>	 	  
<?php	  
					}
	     	  $start_num++;
	 } 
  } catch (PDOException $Exception) {
     print "오류: ".$Exception->getMessage();
  } 
?> 
   	
	</div>
    <div class="total wrapper">
        <div class="game-text">		
		
					<?	
					
			echo '			    <div class="input-group p-0 mb-2">	 
			 <span class="input-group-text align-items-center" style="width:50px;" >순위</span>
			 <span class="input-group-text align-items-center" style="width:100px;" >성명</span>
			 <span class="input-group-text align-items-center" style="width:100px;" >점수</span>
			 <span class="input-group-text align-items-center" style="width:200px;" >기록일시</span>
					</div>	';
					
				require_once("../lib/mydb.php");
				$pdo = db_connect();	
				$sql="select * from mirae8440.tetris order by score desc";
				$start_num =1;
				   try{  
					  $stmh = $pdo->query($sql);         // 검색 조건에 맞는 쿼리 전체 개수
					  $temp=$stmh->rowCount();    
					  
					  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			   
					  if($start_num<=10) {
						  $num=$row["num"];
						  $rec_date=$row["rec_date"];
						  $name=$row["name"];
						  $score=$row["score"];	  
		  
						  print		'<div class="input-group p-0 mb-0">	 <span class="input-group-text align-items-center" style="width:50px;" > ' . $start_num ;
						  print      '</span> <span class="input-group-text align-items-center" style="width:100px;" >' . $name ;
						  print	     '</span> <span class="input-group-text align-items-center" style="width:100px;" >' . $score ;
						  print   '</span> <span class="input-group-text align-items-center" style="width:200px;" >' . $rec_date ;
						  print    '</span> </div>	'; 	  
					          }
							  $start_num++;
					 } 
				  } catch (PDOException $Exception) {
					 print "오류: ".$Exception->getMessage();
				  } 
				?> 	
		
            <span> 게임종료 </span>
            <button id=reStartBtn >다시시작</button>

        </div>
        <div class="score">0</div>
        <div class="playground">
            <ul></ul>
        </div>
    </div>    
   </div>
<div id="vacancy" style="display:none;" > 	</div>    	
    
<script src="js/tetris.js" type="module"> </script>   

<script>
$(document).ready(function(){	
					 $("#reStartBtn").click(function(){   
							$("#restartVal").val('1'); 						 
					 });	
});
</script>


</body>
</html>