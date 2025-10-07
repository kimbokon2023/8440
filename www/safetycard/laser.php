<?php

session_start();
$level= $_SESSION["level"];
$id_name= $_SESSION["name"];   

isset($_REQUEST["qrcode"])  ? $qrcode=$_REQUEST["qrcode"] :   $qrcode=""; 
isset($_REQUEST["name"])  ? $name=$_REQUEST["name"] :   $name=""; 


 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
<meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>

<link rel="stylesheet" href="../css/partner.css" type="text/css" />
 
 <title> 안전보건 카드뉴스 </title>
 
 </head>
 <body>
 
   
<div class="container mb-5 mt-5"  >
	<div class="card w-80">
	  <div class="card-body">
					<!-- Product details-->
			<div class="card-body p-4">
				<div class="text-center fs-2">
					<!-- name-->
					<h3 class="fw-bolder"> <?=$name?> </h3>
				</div>
				<div class="text-center fs-3">                                    
				   <h4 class="fw-bolder">
				   <img src=<?=$qrcode?> style="width:100%;height:100%;" >
				   </h4>
				</div>
			</div>
		</div>		
		</div>		
		</div>		
 
 
 
 
</body>
</html>