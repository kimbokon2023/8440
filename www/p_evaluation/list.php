<?php
require_once __DIR__ . '/../common/functions.php';
?>
﻿<?php
require_once getDocumentRoot() . '/session.php'; // 세션 파일 포함
  
   // 첫 화면 표시 문구   
$title_message = '협력업체 평가표';

 ?>
 
<?php include getDocumentRoot() . '/load_header.php' ?> 
  
<title>  <?=$title_message?>  </title> 

    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
        }
    </style> 
 
 </head> 
 
 	 
<body>

  <?php require_once(includePath('myheader.php')); ?>   
 
<?php

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }    
    
$tablename = "p_evaluation";
 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";

       if(isset($_REQUEST["search"]))   // search 쿼리스트링 값 할당 체크
         $search=$_REQUEST["search"];
       else 
         $search="";
     
 
   $sqlAll="select * from mirae8440.p_evaluation where txt1 like '%$search%' or txt2 like '%$search%'  or txt3 like '%$search%'  or txt4 like '%$search%'   or txt5 like '%$search%'   or txt6 like '%$search%' "; 	 
      
   if($mode=="search"){
         if(!$search) {
				$sql ="select * from mirae8440.p_evaluation order by txt6 desc   "; 				
             }
              $sql= $sqlAll .  "  order by txt6 desc   ";              
       } else {
              $sql="select * from mirae8440.p_evaluation order by txt6 desc  ";              
       }


// 전체 레코드수를 파악한다.
try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	$total_row=$stmh->rowCount();    		
	
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

try{  
$stmh = $pdo->query($sql); 
		 
			 
?>
  
<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>">  
  
<div class="container">  
 <div class="card mt-2 mb-2">  
	<div class="card-body">    	
  
 <div class="d-flex mt-3 mb-1 justify-content-center">  
    <img src="../img/standards.jpg" class="form-control" >
  </div>	 
 <div class="d-flex mt-3 mb-1 justify-content-center">  
  <h3>   <?=$title_message?>  </h3>  
  </div>	 
  
<div class="d-flex mb-2 px-5 px-lg-2 mt-2  justify-content-center align-items-center">                
	▷ <?= $total_row ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	<input type="text" class="form-control me-2" style="width:150px;height:32px;" name="search" id="search" value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" placeholder="검색어" autocomplete="off" >
	<button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"> <i class="bi bi-search"></i> 검색 </button>			
	<button type="button" class="btn btn-dark btn-sm" id="writeBtn" >  <i class="bi bi-pencil"></i>  신규 </button> &nbsp;&nbsp;&nbsp;				
</div>	
	   
<div class="row d-flex"  >
<table class="table table-hover" id="myTable">
		<thead class="table-primary" >
			<tr>
				 <th class="text-center" >번호  </td>
				 <th class="text-center" >작성일 </td>
				 <th class="text-center" >업체명 </td>
				 <th class="text-center" >대표자 </td>
				 <th class="text-center" >품명 </td>     
				 <th class="text-center" >전년구입실적 </td>  
				 <th class="text-center" >평가점수 </td>     
				 <th class="text-center" >판정결과 </td>     
			 </tr>
		</thead>
		<tbody> 
 
 
<?php  
$start_num=$total_row;  
			 
 while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
  
  include '_row.php';
  
  $item_date=substr($item_date, 0, 10);
  $item_subject=str_replace(" ", "&nbsp;", $row["subject"]);
   
  if((int)$txt17 >= 60)
	  $txt30 = '합격';
   
 ?>
 
			<tr onclick="redirectToView('<?=$num?>', '<?=$tablename?>')">  
				  <td class="text-center" > <?= $start_num ?> </td>
				  <td class="text-start" >  <?= $txt6 ?>  </td>  
				  <td class="text-center" > <?= $txt1 ?> </td>			
				  <td class="text-center" > <?= $txt3 ?> </td> 
				  <td class="text-center" > <?= $txt5 ?> </td>      
				  <td class="text-center" > <?= $txt4 ?> </td>       
				  <td class="text-center" > <?= $txt17 ?> </td>     
				  <td class="text-center" > <?= $txt30 ?>  </td>        
			</tr>

 <?php
	$start_num--;
    }
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
 ?>
  </tbody>
		  </table>  
</div>
   
</div>
</div>
</div>

</form>   

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var partnerpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[0, 'desc']]
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('partnerpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var partnerpageNumber = dataTable.page.info().page + 1;
        setCookie('partnerpageNumber', partnerpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('partnerpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('partnerpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function redirectToView(num, tablename) {
    var page = partnerpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
    	
    var url = "write_form.php?num=" + num + "&tablename=" + tablename;          

	customPopup(url, '협력업체 평가표', 1200, 900); 		    
}

$(document).ready(function(){
	
	$("#writeBtn").click(function(){ 
		var page = partnerpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)	
		var tablename = '<?php echo $tablename; ?>';		
		var url = "write_form.php?tablename=" + tablename; 				
		customPopup(url, '협력업체 평가표', 1300, 850); 	
	 });			 
		
});	

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('협력업체 평가표'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>
