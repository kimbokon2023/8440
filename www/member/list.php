<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));  

 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header ("Location:https://8440.co.kr/login/logout.php");
         exit;
   }   
 ?>
 
<?php include getDocumentRoot() . '/load_header.php' ?>
  
<title> 미래기업 회원관리 </title> 
	 
<body>

<? include getDocumentRoot() . '/myheader.php'; ?>   


    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
        }
        .sortable-header {
            cursor: pointer;
            user-select: none;
            position: relative;
        }
        .sortable-header:hover {
            background-color: #dee2e6;
        }
        .sort-icon {
            margin-left: 5px;
            opacity: 0.5;
        }
        .sort-icon.active {
            opacity: 1;
            color: #0d6efd;
        }
    </style> 
 
 </head>
 
<?php
     
$tablename = "member";

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
	 
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
    $page=(int)$_REQUEST["page"];  // 페이지 번호
  else
    $page=1;	 
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";

       if(isset($_REQUEST["search"]))   // search 쿼리스트링 값 할당 체크
         $search=$_REQUEST["search"];
       else 
         $search="";
     
       if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
         $find=$_REQUEST["find"];
       else
         $find="";

       // 정렬 관련 변수 추가
       if(isset($_REQUEST["sort_field"]))
         $sort_field=$_REQUEST["sort_field"];
       else
         $sort_field="id";

       if(isset($_REQUEST["sort_order"]))
         $sort_order=$_REQUEST["sort_order"];
       else
         $sort_order="desc";
	  

  $scale = 50;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.	 
	       
      // 정렬 쿼리 생성
   $order_clause = " order by " . $sort_field . " " . $sort_order;

   if($mode=="search"){
         if(!$search) {
			$sql ="select * from mirae8440." . $tablename . $order_clause . " limit $first_num, $scale"; 
			$sqlcon ="select * from " . $DB . "."  . $tablename . $order_clause;
             }
              $sql="select * from " . $DB . "."  . $tablename . " where id like '%$search%' or name like '%$search%'  or nick like '%$search%'" . $order_clause . " limit $first_num, $scale";
              $sqlcon="select * from " . $DB . "."  . $tablename . " where id like '%$search%' or name like '%$search%'  or nick like '%$search%'" . $order_clause;
       } else {
              $sql="select * from " . $DB . "."  . $tablename . $order_clause . " limit $first_num, $scale";
              $sqlcon="select * from " . $DB . "."  . $tablename . $order_clause;
       }


  // 전체 레코드수를 파악한다.
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp2;     // 전체 글수	  		
         					 
     $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산		
			 
 ?> 

<form name="board_form" id="board_form"  method="post" action="list.php?mode=search&search=<?=$search?>">
	<input type="hidden" name="sort_field" id="sort_field" value="<?=$sort_field?>" >
	<input type="hidden" name="sort_order" id="sort_order" value="<?=$sort_order?>" >

<div class="container justify-content-center">  
	<input type="hidden" id="page" name="page" value="<?=$page?>"  >     
<div class="d-flex mt-2 mb-1 justify-content-center">  
	<span class="text-secondary fs-5 " > &nbsp;&nbsp; 회원 정보관리 &nbsp;&nbsp;</span>
	 <button type="button" class="btn btn-dark btn-sm mx-3"  onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>  
</div>	 
 
<div class="d-flex mt-1 mb-1 justify-content-center">   
	<div class="input-group p-2 mb-2 justify-content-center">	  
	    <button type="button"   class="btn btn-dark btn-sm me-2" onclick="popupCenter('write_form.php?id=null', '회원 등록', 800, 500);return false;" > 등록 </button>	
	    <button type="button"   class="btn btn-dark btn-sm me-2" onclick="popupCenter('setline.php?id=null', '결재라인 등록', 600, 400);return false;" > 결재라인 등록 </button>	
		<input type="text" name="search" id="search" value="<?=$search?>" size="30" autocomplete="off" onkeydown="JavaScript:SearchEnter();" placeholder="검색어 입력"> 
		<button type="button" id="searchBtn" class="btn btn-dark"  >  <i class="bi bi-search"> </i> </button>			
	</div>
</div>
 
<div class="row d-flex"  >
<table class="table table-hover">
      <thead class="table-secondary" >
	    <tr>
        <th class="text-center" > 번호</th>
        <th class="text-center sortable-header" onclick="sortTable('division')" > 
          division 
          <i class="bi bi-arrow-up sort-icon <?=($sort_field=='division' && $sort_order=='asc') ? 'active' : ''?>"></i>
          <i class="bi bi-arrow-down sort-icon <?=($sort_field=='division' && $sort_order=='desc') ? 'active' : ''?>"></i>
        </th>   		
        <th class="text-center sortable-header" onclick="sortTable('name')" > 
          이름 
          <i class="bi bi-arrow-up sort-icon <?=($sort_field=='name' && $sort_order=='asc') ? 'active' : ''?>"></i>
          <i class="bi bi-arrow-down sort-icon <?=($sort_field=='name' && $sort_order=='desc') ? 'active' : ''?>"></i>
        </th>
        <th class="text-center sortable-header" onclick="sortTable('part')" > 
          파트 
          <i class="bi bi-arrow-up sort-icon <?=($sort_field=='part' && $sort_order=='asc') ? 'active' : ''?>"></i>
          <i class="bi bi-arrow-down sort-icon <?=($sort_field=='part' && $sort_order=='desc') ? 'active' : ''?>"></i>
        </th>   		
        <th class="text-center sortable-header" onclick="sortTable('position')" > 
          position 
          <i class="bi bi-arrow-up sort-icon <?=($sort_field=='position' && $sort_order=='asc') ? 'active' : ''?>"></i>
          <i class="bi bi-arrow-down sort-icon <?=($sort_field=='position' && $sort_order=='desc') ? 'active' : ''?>"></i>
        </th>   		
        <th class="text-center sortable-header" onclick="sortTable('id')" > 
          ID 
          <i class="bi bi-arrow-up sort-icon <?=($sort_field=='id' && $sort_order=='asc') ? 'active' : ''?>"></i>
          <i class="bi bi-arrow-down sort-icon <?=($sort_field=='id' && $sort_order=='desc') ? 'active' : ''?>"></i>
        </th>
        <th class="text-center" > P/W   </th>
        <th class="text-center sortable-header" onclick="sortTable('hp')" > 
          전번 
		     <i class="bi bi-arrow-up sort-icon <?=($sort_field=='hp' && $sort_order=='asc') ? 'active' : ''?>"></i>
		     <i class="bi bi-arrow-down sort-icon <?=($sort_field=='hp' && $sort_order=='desc') ? 'active' : ''?>"></i>
		 </th>
		 <th class="text-center sortable-header" onclick="sortTable('level')" > 
		     레벨 
		     <i class="bi bi-arrow-up sort-icon <?=($sort_field=='level' && $sort_order=='asc') ? 'active' : ''?>"></i>
		     <i class="bi bi-arrow-down sort-icon <?=($sort_field=='level' && $sort_order=='desc') ? 'active' : ''?>"></i>
		 </th>
		 <th class="text-center sortable-header" onclick="sortTable('numorder')" > 
		     numorder 
		     <i class="bi bi-arrow-up sort-icon <?=($sort_field=='numorder' && $sort_order=='asc') ? 'active' : ''?>"></i>
		     <i class="bi bi-arrow-down sort-icon <?=($sort_field=='numorder' && $sort_order=='desc') ? 'active' : ''?>"></i>
		 </th>   		
		 <th class="text-center sortable-header" onclick="sortTable('eworks_level')" > 
		     eworks_level 
		     <i class="bi bi-arrow-up sort-icon <?=($sort_field=='eworks_level' && $sort_order=='asc') ? 'active' : ''?>"></i>
		     <i class="bi bi-arrow-down sort-icon <?=($sort_field=='eworks_level' && $sort_order=='desc') ? 'active' : ''?>"></i>
		 </th>   		
	 </tr>
       </thead>
	<tbody>  
	
<?php  
  if ($page==1)  
    $start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
  else 
    $start_num=$total_row-($page-1) * $scale;
			 
 while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {  
         include '_row.php';
 ?>
	<tr onclick="redirectToView('<?=$id?>')">  
	  <td class="text-center"> <?= $start_num ?> </td>
	  <td class="text-center">  <?= $division ?>      </td>     	  
	  <td class="text-center"> <?= $name ?>   </td>
	  <td class="text-center">  <?= $part ?>      </td>     	  
	  <td class="text-center">  <?= $position ?>      </td>     	  
	  <td class="text-center"> <?= $id ?> </td>
	  <td class="text-center"> <input type="password" name="password" value="<?= $pass ?>" disabled>    </td>
	  <td class="text-center">  <?= $hp ?>   </td>
	  <td class="text-center">  <?= $level ?>      </td>
	  <td class="text-center">  <?= $numorder ?>      </td>     	  
	  <td class="text-center">  <?= $eworks_level ?>      </td>     	  
	</tr>      
  
 <?php
	$start_num--;
    }
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  

   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;
  
 ?>  
  	  </tbody>
		  </table>  
</div>
<div class="row row-cols-auto mt-5 justify-content-center align-items-center"> 
 <?php
	if($page!=1 && $page>$page_scale){
              $prev_page = $page - $page_scale;    
              // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
              if($prev_page <= 0) 
              $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
		      print '<button class="btn btn-outline-secondary btn-sm" type="button" id=previousListBtn  onclick="javascript:movetoPage(' . $prev_page . ')"> ◀ </button> &nbsp;' ;              
            }
            for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) {        // [1][2][3] 페이지 번호 목록 출력
              if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
                print '<span class="text-secondary" >  ' . $i . '  </span>'; 
              else 
                   print '<button class="btn btn-outline-secondary btn-sm" type="button" id=moveListBtn onclick="javascript:movetoPage(' . $i . ')">' . $i . '</button> &nbsp;' ;     			
            }

            if($page<$total_page){
              $next_page = $page + $page_scale;
              if($next_page > $total_page) 
                     $next_page = $total_page;
                // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
				  print '<button class="btn btn-outline-secondary btn-sm" type="button" id=nextListBtn onclick="javascript:movetoPage(' . $next_page . ')"> ▶ </button> &nbsp;' ; 
            }
            ?>         
   </div>
</div>
</form>   

<script>

function redirectToView(id) {
	popupCenter('write_form.php?id=' + id, '회원정보 수정', 800, 550);    
}

function sortTable(field) {
	var currentSortField = $("#sort_field").val();
	var currentSortOrder = $("#sort_order").val();
	
	// 같은 필드를 클릭했을 때는 순서를 반대로, 다른 필드를 클릭했을 때는 기본값(desc)으로
	if (currentSortField === field) {
		var newOrder = (currentSortOrder === 'asc') ? 'desc' : 'asc';
	} else {
		var newOrder = 'desc';
	}
	
	$("#sort_field").val(field);
	$("#sort_order").val(newOrder);
	$("#page").val('1'); // 정렬할 때는 첫 페이지로
	
	$("#board_form").submit();
}

$(document).ready(function(){
	
$("#searchBtn").click(function(){ 	
	  // page 1로 초기화 해야함
     $("#page").val('1');
	 document.getElementById('board_form').submit();    
 
 });	
	
	movetoPage = function(page){ 	  
	  $("#page").val(page); 
      // var echo="<?php echo $partOpt; ?>"; 
      // var searchOpt="<?php echo $searchOpt; ?>"; 
      // var search="<?php echo $search; ?>"; 

     // $("#partOpt").val(partOpt);
     // $("#searchOpt").val(searchOpt);
     // $("#search").val(search);
     
     // 정렬 정보도 유지
     $("#sort_field").val("<?=$sort_field?>");
     $("#sort_order").val("<?=$sort_order?>");
     
	 $("#board_form").submit();  
	}				
	
});	
	
function SearchEnter(){

    if(event.keyCode == 13){	
		$("#page").val('1');		
		document.getElementById('board_form').submit(); 
    }
}
</script>

</body>
</html>