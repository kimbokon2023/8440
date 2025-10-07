<?php 
// 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'ini.php';   

require_once("../lib/mydb.php");
$pdo = db_connect();	 
?>

<!doctype html>

<html lang="ko">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="우성스틸앤엘리베이터">

<!-- theme meta -->
<meta name="theme-name" content="우성스틸앤엘리베이터" />

<meta name="author" content="우성스틸앤엘리베이터.com">

<title>우성스틸앤엘리베이터</title>

  
<!-- tree grid로 upgrage -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script> 
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.css" rel="stylesheet">

<script src="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.0/dist/extensions/treegrid/bootstrap-table-treegrid.min.js"></script>

<link rel="stylesheet" href="../js/jquery-treegrid-master/css/jquery.treegrid.css">
<script src="../js/jquery-treegrid-master/js/jquery.treegrid.min.js"></script>

<link rel="stylesheet" href="css/style2.css">
  
<script src="http://8440.co.kr/common.js"></script>  

</head>
<body>

<? include 'navbarsub.php'; ?>

<!-- Section Slider Start -->
<!-- Slider Start -->
<section class="sub-slider">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<span class="h6 d-inline-block mb-4 subhead text-uppercase">Elevator 의장재 및 원자재</span>
				<h2 class="text-uppercase text-white mb-5"> Woosung <span class="text-color"> Steel </span><br> And Elevator </h2>
			
			<!--	<a href="pricing.html" target="_blank" class="btn btn-main " >Join Us <i class="ti-angle-right ml-3"></i></a>  -->
			</div>
		</div>
	</div>
</section>
<!-- Section Slider End -->


 <?php
    
$sum=array(); 
 
$time = time();  
$today=date("Y-m-d");  // 현재일 저장   	  
$yearAfter=date("Y-m-d",strtotime("+12 month", $time)) ; // 1년 후 산출
   
switch($check) {  
  case '0' :  //전체 체크된 경우
  				$attached=" ";
			    $orderby=" order by id desc  ";																	
				break;		 
  case '1' :  // 미실측 체크된 경우
  				$attached=" where (measureday='') or (measureday='0000-00-00') ";
			    $orderby=" order by id desc  ";																	
				break;		
  case '2' :  // 미시공 체크된 경우
  				$attached=" where (doneday='') or (doneday='0000-00-00') ";
			    $orderby=" order by id desc  ";																	
				break;		
  case '3' :  // 미청구 체크된 경우
  				$attached=" where (demand='') or (demand='0000-00-00') or (demand between date('$today') and date('$yearAfter')) ";
			    $orderby=" order by id desc  ";																	
				break;		
	default:	
	        $attached=" ";
	  	    $orderby=" order by id desc  ";															
		}		 
	 
$a= $attached . " " . $orderby . " ";  
$b= $attached .  " " . $orderby;

		  if($search==""){
					 $sql="select * from mirae8440." . $tablename . " " . $a; 					
	                 $sqlcon = "select * from mirae8440." . $tablename . " "  . $b;   // 전체 레코드수를 파악하기 위함.					 				
		  }
			else						 
		  			 
        { // 필드별 검색하기
					  $sql ="select * from mirae8440." . $tablename . " " . " where ((workplacename like '%$search%' ) or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) or (memo2 like '%$search%' ) ) " . $a;
					  
                      $sqlcon ="select * from mirae8440." . $tablename . " " . "  where ((workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sqlcon .=" or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) or (memo2 like '%$search%' )) " . $b;
				  } 
			 
?>
	
<div  class="container-fluid">

<form id="board_form" name="board_form" method="post" >  
<!-- 우성 로고 작은거 출력
<div class="d-flex p-1 justify-content-center">
   <h3 align="center"><img src="./img/woosungtitle.jpg"></h3>
</div>  -->

	<div class="d-flex p-2 justify-content-center">			
		<button type="button"  id=writeBtn class=" btn btn-outline-secondary " > 데이터 등록   </button>  
	</div>

	<input type="hidden" id="mode" name="mode" value="<?=$mode?>"  > 						
	<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 						
	<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 			
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
	<input type="hidden" id="parent_id" name="parent_id" value="<?=$parent_id?>" >			  										


	<div class="d-flex p-2">	  
        <div class="col-sm pt-1">
		
<!--
		<div>
		  <label><span>paginationSwitchDown</span> <input class="form-control" type="text" value="fa-caret-square-down"></label>
		</div>
		<div>
		  <label><span>paginationSwitchUp</span> <input class="form-control" type="text" value="fa-caret-square-up"></label>
		</div>  -->

		
		<table  id="table"		
			data-height="640" 			
			data-pagination="true" 			
			data-search="true" 
			data-show-search-clear-button="true" 		
		  >  </table>		  
		  
<!--
data-height="640"
data-show-pagination-switch="true"		
data-pagination="true"
data-side-pagination="true" 
data-search="true" 
data-show-search-clear-button="true"
   -->

    </div>
        </div>
	<div class="row d-flex p-2 mb-5">		
        <div class="col-sm-12 pt-2">
             <br>
             <br>
        </div>
	</div>

 </form>	

</div> <!-- end of  container -->     


<!-- Section Footer Start -->
<!-- footer Start -->
<footer class="footer bg-black-50">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 col-md-6">
				<div class="footer-widget">
					<h4 class="mb-4 text-white letter-spacing text-uppercase">본사 주소</h4>
					<p>서울사무소 : 서울시 강서구 마곡중앙로 59-11 엠비즈타워 509,510호 <br>
						공장 : 경기도 광주시 곤지암읍 내선길 112-9  <br>						
						Fax : 02-6404-5787		
					</p>
					<span class="text-white">+82 (02) 6339-6325</span>
					<span class="text-white">woosung6339@gmail.com</span>
				</div>
			</div>
		</div>

		<div class="row align-items-center mt-3 px-3 bg-black mx-1">
			<div class="col-lg-4">
				<p class="text-white mt-3">우성스틸앤엘리베이터 © 2022 , copyrights </p>
			</div>
			<div class="col-lg-6 ml-auto text-right">
				<ul class="list-inline mb-0 footer-socials">
					<li class="list-inline-item"><a href="#"><i class="ti-facebook"></i> </a></li>
					<li class="list-inline-item"><a href="#"><i class="ti-twitter"></i> </a></li>					
				</ul>
			</div>
		</div>
	</div>
</footer>
<!-- Section Footer End -->


</body>  


</html>


<script>

$(function() {
	
var $resetbutton = $('#resetbutton');	
var $table = $('#table');
var search='';	

// // table 아이콘등 만들기
// $('label input').blur(function () {
  // var icons = {}
  // $('label').each(function () {
	// icons[$(this).find('span').text()] = $(this).find('input').val()
  // })
  // $table.bootstrapTable('destroy').bootstrapTable({
	// icons: icons
  // })
// })


  // alert(query);
  // $( '#table > tbody').empty();
	
var $table = $('#table')	;
		  
$table.bootstrapTable({
		  url: "fetchtest.php" ,  
		  idField: 'id',
		  showColumns: true,
		  search: true,		  
		  columns: [
			// {
			  // field: 'ck',
			  // checkbox: false
			// },
			{
			  field: 'name',
			  title: '현장(품명)',			
			  sortable: true,
			},
			{
			  field: 'company',
			  title: '발주처/수신처',
			  sortable: true,
			  align: 'center',
			  // formatter: 'statusFormatter'			  		  
			},
			{
			  field: 'date1',
			  title: '접수일',
			  sortable: true,
			  align: 'center',
			  // formatter: 'statusFormatter'			  		  
			},
			{
			  field: 'date2',
			  title: '납기일',
			  sortable: true,
			  align: 'center',
			  // formatter: 'statusFormatter'			  
			 }
		  ],
		  treeShowField: 'name',
		  parentIdField: 'pid',
		  // // Proper js  선택사항 수정시 작동하는 것
		  onPostBody: function() {
			  
			var columns = $table.bootstrapTable('getOptions').columns			
            // 초기화면은 하위폴더 접히게 검색시는 펼치게
			// console.log($table.treegrid());
			
			$('input[type=search]').keydown(function() {
				console.log($('input[type=search]').val());
			  });
			
			if (columns && columns[0][1].visible && $('input[type=search]').val()=='' ) {
			  $table.treegrid({
			    // 1번 컬럼으로 펼치고 닫고 하는 구조 만든다.
				treeColumn: 0,
				initialState: 'collapsed',  // 초기화면 접힌 것 표시
				onChange: function() {
				  $table.bootstrapTable('resetView')	
				}
			  })
			}
			else  // 검색창에 입력이 있을 경우
			  {
				  $table.treegrid({
					// 1번 컬럼으로 펼치고 닫고 하는 구조 만든다.
					treeColumn: 0,
					initialState: 'expanded',  // 초기화면 접힌 것 표시
					onChange: function() {
					  $table.bootstrapTable('resetView')	
					}
				  })
			  }			
		  },	  
		
    customSearch: function (data, text) {		
		// console.log(text);
        if (!text) {
          return data;
        }

		
        const index = [];
        for (let i = 0; i < data.length; i++) {
          const row = data[i];
          for (const value of Object.values(row)) {
            if ((value + '').includes(text)) {
              index.push(...getIndexArray(data, row, i));
            }
          }
        }
        return data.filter((row, i) => {
          return index.includes(i);
        })
      }
	})
	
	// console.log($table);

// Here, you can expect to have in the 'e' variable the sender property, which is the boostrap-table object
$('#table').on('dbl-click-cell.bs.table', function(event, name, place, data) {
 // var nodeID = $('#tree').treeview('getSelected') ;
	 popupCenter('write_form.php?id=' + data.id + '&parent_id=' + data.parent_id , '발주서', 750, 900);
	 
	 // console.log(event);			 
	 // console.log(name);			 
	 // console.log(place);			 
	 // console.log(data.id);			 
	 // console.log(data.searchfield);			 	 
});
		



// $('#table').treegrid('collapseAll');
	
// 글쓰기 버튼 클릭시 동작
$("#writeBtn").click(function(){ 				 
	popupCenter('write_form.php?mode=new&parent_id=0' , '발주서', 750, 900);			 
	})	
	
$resetbutton.click(function () {
	  $table.bootstrapTable('resetSearch');
	})	

})

	   	
  function getIndexArray(data, row, index) {
    const arr = [index]
    while (true) {
      if (Number(row.pid) === 0) {  // row.pid가 문자로 되서 계속 오류가 발생한 것임. 주의 요함
        break
      }
      row = data.find(it => it.id === row.pid)
      arr.push(data.indexOf(row))
    }
    return arr
  }	
  
</script>