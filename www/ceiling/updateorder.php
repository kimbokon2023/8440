	
<?php include getDocumentRoot() . '/load_header.php' ?>	
 <?php
	  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
   
 // 기간을 정하는 구간
 
$todate=date("Y-m-d");   // 현재일자 변수지정   
	 
if(isset($_REQUEST["num"]))
     $num=$_REQUEST["num"];
  else 
     $num="";     

$common=" where  num=$num ";  // 생산예정일이 현재일보다 클때 조건

$sql = "select * from mirae8440.ceiling " . $common; 							

$nowday=date("Y-m-d");   // 현재일자 변수지정   

$stmh = $pdo->query($sql);    
   
$row = $stmh->fetch(PDO::FETCH_ASSOC) ;
include '_rowDB.php';
	 
// print $sql;		 		 
		 
?>

   
   
 <title>  천장 laser 순서 </title> 
 </head> 
 
 <div class="container" style="width:280px;">          
  <div class="card mt-3">
	<div class="card-body">
	<div class="d-flex  p-1 m-1 mt-1 mb-4 justify-content-center align-items-center ">  
		<span class="badge bg-primary fs-5" > &nbsp;&nbsp; 천장 laser 순서 </span>
	 </div>
	<div class="d-flex  p-1 m-1 mt-1 mb-4 justify-content-center align-items-center ">  	 
			 <form id="board_form" name="board_form" method="post" >	 
				<input type=hidden id="num" name="num" value="<?=$num?>"  >	
			&nbsp;&nbsp; 	<input type="text" id="work_order" name="work_order" value="<?=$work_order?>" size="2" class="text-center fs-5" >&nbsp; 
			<button  type="button" class="btn btn-secondary btn-sm mb-2" id="saveBtn"> 저장 </button>	 &nbsp;									 

			</form>
	   </div> 
	   </div> 
	</div>
 </div>
  </body>
</html>   

<script>
window.addEventListener('load', function() {
  var work_order = document.getElementById('work_order');
  work_order.focus();
});


$(document).ready(function(){
	
$("#saveBtn").click(function(){  	
		    $.ajax({
			url: "updatework_orderprocess.php",
    	  	type: "post",		
   			data: $("#board_form").serialize(),
   			dataType:"json",
			success : function( data ){
				console.log( data);
				opener.location.reload();
				self.close();
				
			},
			error : function( jqxhr , status , error ){
				console.log( jqxhr , status , error );
			} 			      		
		   });
	   });
});

</script>
