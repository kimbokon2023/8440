<?php session_start(); 
$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];   

if(isset($_REQUEST["SelectWork"]))  // 어떤 작업을 지시했는지 파악해서 돌려줌.
	$SelectWork=$_REQUEST["SelectWork"];
		else 
			$SelectWork="";   // 초기화	

?>
<!DOCTYPE html>
<meta charset="UTF-8">
<html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<body>
<title> 은성레이져 통합정보 </title>
<style>
   @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");
</style>

	<header class ="d-flex fex-column align-items-center flex-md-row p-1 bg-primary" >
    <h1 class="h4 my-0 me-md-auto"> 		
	은성레이져 견적산출 </h1>
	<div class="d-flex align-items-center">	  
	  <div class="flex-grow-1 ms-3">
		
	  </div>	  
</div>
	</header>
	<section class ="d-flex fex-column align-items-left flex-md-row p-1">
	 <div class="p-2 pt-md-3 pb-md-3 text-left" style="width:100%;">	  
		 <form id="mainFrm" method="post" enctype="multipart/form-data" action="insert_test.php"  >		
            <input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>"> 
            <input type="hidden" id="vacancy" name="vacancy" > 
		    <div class="card-header"> 			
           &nbsp; <button type="button" id="saveBtn"  class="btn btn-info"> DATA 저장 </button>	&nbsp;		
			 <!-- 배열을 전달하기 위한 Grid 값 -->			  	
			   <input id="test" name="test[]" type=hidden >			    
			   <input id="test1" name="test1[]" type=hidden >			    
				</div>
	     	</form>		 			  
		  </div>
		  
 <script> 
$(document).ready(function(){	
	
										
					$("#saveBtn").click(function(){    // DATA 저장버튼 누름
					       
						   let tmp = ['test','test1','test2'];  // 배열 초기 선언
						   
					       $("#SelectWork").val('insert');
					       $("#test").val(tmp);
					       $("#test1").val(tmp);
					         console.log(tmp);
							 
						    let parse;
							
							parse = JSON.stringify(tmp);
							 console.log(parse);
							 
						// JSON 형태의 배열	 
							var dataArray = new Array(

										{firstname:'Gil-dong',lastname:'Hong'},

										{firstname:'Sun-shin',lastname:'Yi'},

										{firstname:'Yeong-sil',lastname:'Jang'}

									);

							var jsonEncode = JSON.stringify(dataArray);

							console.log(jsonEncode);

                            

							var jsonDecode = JSON.parse(jsonEncode);

							console.log(jsonDecode[0].firstname + ' ' + jsonDecode[0].lastname);							 
							 
							 $("#test").val(jsonEncode);
							 
	                        $("#mainFrm").submit(); 								 
					     });					

});
  </script>    
  </div>	 
</section>

</html>