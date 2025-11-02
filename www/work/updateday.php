<?php
// OpCache 클리어 (개발 중에만 사용)
if (function_exists('opcache_reset')) {
    opcache_reset();
}

require_once __DIR__ . '/../bootstrap.php';

// 디버그: 파일 버전 확인
echo "<!-- FILE VERSION: 2025-10-31 v2.0 -->\n";

// 파라미터 초기화 (안전한 방식)
$num = $_REQUEST["num"] ?? '';

// num이 비어있으면 에러 처리
if ($num === '') {
    die("Error: num parameter is required - Please check URL");
}

// 현재일자 변수지정
$todate = date("Y-m-d");
$nowday = date("Y-m-d");

// Prepared Statement를 사용한 안전한 쿼리
$sql = "SELECT * FROM mirae8440.work WHERE num = :num";

// 디버그 정보
if (isLocal()) {
    echo "<!-- DEBUG: Using PREPARE statement (not query) -->\n";
    echo "<!-- DEBUG SQL: " . htmlspecialchars($sql) . " -->\n";
    echo "<!-- DEBUG num parameter: " . htmlspecialchars($num) . " -->\n";
}

try {
    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();
    
    if (isLocal()) {
        echo "<!-- DEBUG: PDO connection established -->\n";
    }
    
    $stmh = $pdo->prepare($sql);
    
    if (isLocal()) {
        echo "<!-- DEBUG: SQL prepared successfully -->\n";
    }
    
    $stmh->bindValue(':num', $num, PDO::PARAM_INT);
    
    if (isLocal()) {
        echo "<!-- DEBUG: Parameter bound successfully -->\n";
    }
    
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        die("Error: No data found for num = " . htmlspecialchars($num));
    }
    
    include '_row.php';
} catch (PDOException $e) {
    if (isLocal()) {
        die("Database Error: " . $e->getMessage());
    } else {
        die("Database Error occurred. Please contact administrator.");
    }
}

// 디버그용 (로컬에서만)
// if (isLocal()) echo "<!-- DEBUG SQL: " . htmlspecialchars($sql) . " -->\n";

?>

<?php include getDocumentRoot() . '/load_header.php' ?>	
   
   
 <title>  생산 예정일 변경하기 </title> 
 </head> 
 
 <div class="container" style="width:280px;">        
  <div class="card mt-3">
	<div class="card-body">
	<div class="d-flex  p-1 m-1 mt-1 mb-4 justify-content-center align-items-center ">  
		<span class="badge bg-secondary fs-6" > &nbsp;&nbsp; Jamb 생산 예정일 변경 </span>
	 </div>
	<div class="d-flex  p-1 m-1 mt-1 mb-4 justify-content-center align-items-center ">  	 
	 <form id="board_form" name="board_form" method="post" >	 
		<input type=hidden id="num" name="num" value="<?=$num?>"  >	
	&nbsp;&nbsp; 	<input type="date" id="endworkday" name="endworkday" value="<?=$endworkday?>" >
	<button  type="button" class="btn btn-secondary btn-sm mb-2" id="saveBtn"> 저장 </button>	 &nbsp;									 

	</form>
	   </div> 
	   </div> 
	</div>
 </div>  
</html>   

<script>
window.addEventListener('load', function() {
  var endworkday = document.getElementById('endworkday');
  endworkday.focus();
});


$(document).ready(function(){
	
$("#saveBtn").click(function(){  	
		    $.ajax({
			url: "updatedayprocess.php",
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
