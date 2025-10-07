<?php include getDocumentRoot() . '/session.php';   
 
 if(!isset($_SESSION["level"]) || $level>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }

ini_set('display_errors','1');  // 화면에 warning 없애기   


?>
 
<?php include getDocumentRoot() . '/common.php' ?>
<?php include getDocumentRoot() . '/load_header.php' ?>
 
<title> 조명천장 부자재 </title> 
</head>
 
<body >

<?php
if($_REQUEST['menu']!=='no')
      include '../myheader.php'; ?>   

<?php
 
// include "../subload_notice.php";  //공지사항 불러오기
include "_request.php";

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();     

// part수
// 부품 title 배열 만들기
$title_arr = array("","일반휀","휠터휀 (LH용)","판넬고정구 (금성아크릴)","비상구 스위치 (건흥KH-9015)","비상등","할로겐(7W -6500K)","T5 일반 5W(300)","T5 일반 11W(600)","T5 일반 15W(900)","T5 일반 20W(1200)","T5 KS 6W(300)","T5 KS 10W(600)","T5 KS 15W(900)","T5 KS 20W(1200)","직관등 600mm","직관등 800mm","직관등 1000mm","직관등 1200mm","할로겐(7W -6500K KS)");
// part20 추가 7월20일 김부장님 요청 "할로겐(7W -6500K KS)"

$itemCount = count($title_arr)  ;

// print $itemCount;
	 
$num_arr=array(); 
$partin_arr = array_fill(0, $itemCount, 0); // 모든 요소를 0으로 초기화
$partout_arr = array_fill(0, $itemCount, 0); // 모든 요소를 0으로 초기화
$partremain_arr = array_fill(0, $itemCount, 0); // 모든 요소를 0으로 초기화

// 전체합계(입고부분)를 산출하는 부분 

$sql="select * from mirae8440.part ";
 
$tmpsum = 0; 

 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   			
			for($i=0;$i<$itemCount;$i++)
				{
					$tmp = 'part' . (string)($i+1) ;   // part1~18 생성 로직		
                    // 입고 누적 루틴					
					$partin_arr[$i] = intval($partin_arr[$i]) + intval($row[$tmp]) ;				
				}
			
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

// var_dump($partin_arr);

// 전체합계(출고부분)를 처리하는 부분 
$sql="select * from mirae8440.ceiling ";

try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			// 2020년 이후 날짜인지 확인
			$mainAssemblyDate = $row["mainassembly_date"];
			$lcAssemblyDate = $row["lcassembly_date"];
			$basicdate = '';

			if ($mainAssemblyDate >= "2010-01-01" || $lcAssemblyDate >= "2010-01-01") {
				
				if ($mainAssemblyDate >= "2010-01-01" )
					$basicdate = $mainAssemblyDate;
				if ($lcAssemblyDate >= "2010-01-01" )
					$basicdate = $lcAssemblyDate;

				for($i=0;$i<$itemCount;$i++)
				  {
						$tmp = 'part' . (string)($i+1) ;   // part1~18 생성 로직				                   									  
						$partout_arr[$i] = intval($partout_arr[$i]) + intval($row[$tmp]);	
                        // print $tmp . ' ';						
				  }			 
			  }			 
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
 


 // var_dump($partout_arr);

// 부품 입고/출고 계산해서 재고량 파악 
for($i=0;$i<$itemCount;$i++)
		$partremain_arr[$i] = (int)$partin_arr[$i] - (int)$partout_arr[$i]  ;
 
// 검색을 위해 모든 검색변수 공백제거
$search = str_replace(' ', '', $search); 

$sql ="select * from mirae8440.part order by inputdate desc ";
  

?>   
   
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h4 class="modal-title">원자재 현황 부족(마이너스) 상태 알림</h4>
        </div>
        <div class="modal-body">		
           <div class="row gx-4 gx-lg-4 align-items-center">		  
				   <br>
				   <div id="alertmsg" class="fs-3" > </div> <br>
				  <br>		  									
				</div>
			</div>		  
        <div class="modal-footer">
          <button id="closeModalBtn" type="button" class="btn btn-default btn-sm " data-dismiss="modal">닫기</button>
        </div>
		</div>
		</div>
      </div>      
   
 
  <form name="board_form" id="board_form"  method="post" action="list_part_table.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>">  
   
		<input type="hidden" id="username" name="username" value="<?=$user_name?>" size="5" > 					
		<input type="hidden" id="BigsearchTag" name="BigsearchTag" value="<?=$BigsearchTag?>" size="5" > 					
		
		<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
		<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
		<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
		<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
		<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
		<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" > 	
		<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" > 	
		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
		<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
		<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
		<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
		<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
		<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
		<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
		<input type="hidden" id="list" name="list" value="<?=$list?>" > 				
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>" > 	
		<div id="vacancy" style="display:none">  </div>
		
<div class="container mt-2 mb-5" >
<div class="card">
<div class="card-body">	  
	<div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 
		 <span class="fs-6">  조명/천장 주요 부품 리스트 &nbsp;  </span> 		
		 <button type="button" class="btn btn-dark btn-sm mx-2"  onclick='location.reload();' > <i class="bi bi-arrow-clockwise"></i> </button>  	   		  								
	</div>	
    <div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 		 
	<?php
	   if($chkMobile===false) // pc접속일때 
	      {  ?>	   
		 <div id="grid" class="board" >		 </div>   
		 
	<?php
		  } else {    // 모바일인 경우 
		  ?>	  
	   
													  
				<table class="table table-hover table-bordered" id="myTable_top">
				  <thead class="table-primary">
					<tr>
							<th class="text-center">번호</th>
							<th class="text-center">부품명</th>
							<th class="text-center">입고 </th>
							<th class="text-center">출고 </th>
							<th class="text-center">재고 </th>
						  </tr>	       
					<tbody>	  
					 <?php		

								// inputlist 작성				
								 for ($i=0;$i<$itemCount;$i++)
									{
										$tmp = "part" . (int)($i+1) ; 
										
										if($row[$tmp]!='') 						  
											 $inputlist .= $title_arr[$i] . " : " .  $row[$tmp] . "EA,  ";  // part1~part20 = ''
										// print  $$tmp . '   ';				
										
								
								?>
							<tr onclick="popupCenter('part_view.php?num=<?=($i+1)?>','부품 이력조회', 800,500);" > 
								<td class="text-center">     <?=$i+1 ?>				    </td>
								<td class="text-center">	 <?=$title_arr[$i]?>		</td>            
								<td class="text-center">	 <?=$partin_arr[$i]?>		</td>			
								<td class="text-center">	 <?=$partout_arr[$i]?>			</td>			
								<td class="text-center">	 <?=$partremain_arr[$i]?>			</td>	
						    </tr>								  	
								
								
							<?php
							$start_num--;  
							 }   
							 ?>
							 
							 <?php							 
							 }  ?>	  		  
		
				</tbody>
			  </table>
			</div>
			  
		
       <?php		 
	   
		 try{

			  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
			  $total_row=$stmh->rowCount();
	      
		?>	 		
	
	<div class="d-flex justify-content-center align-items-center mt-5"> 		
		<div class="input-group p-1 mb-1 justify-content-center align-items-center">
	
		▷ 총 <?= $total_row ?>건 	  &nbsp;  &nbsp; 
	
     	<button type="button" class="btn btn-dark  btn-sm " onclick="popupCenter('part_write_form.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&check=<?=$check?>','주요부품 입력',900,500);">
 
		<i class="bi bi-pencil"></i> 입고등록 </button> 
 
		
		</div>
   </div>		       
</div>		       
<div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 	
	
<table class="table table-hover" id="myTable">
  <thead class="table-primary">
    <tr>
      <th class="text-center" style="width:80px;" >번호</th>
      <th class="text-center" style="width:150px;" >입고일</th>
      <th class="text-center">입고 부품 리스트</th>
    </tr>
  </thead>
  <tbody>
        
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

			    $inputlist = "";
			    $inputdate = $row["inputdate"];
				$num=$row["num"];

                // inputlist 작성				
				 for ($i=0;$i<$itemCount;$i++)
					{
						$tmp = "part" . (int)($i+1) ; 
						
						if($row[$tmp]!='') 						  
						     $inputlist .= $title_arr[$i] . " : " .  $row[$tmp] . "EA,  ";  // part1~part18 = ''
						// print  $$tmp . '   ';				
					}	
					
			 if($inputdate!="") {
				$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
				$inputdate = $inputdate . $week[ date('w',  strtotime($inputdate)  ) ] ;
				}	
                
				?>
					
			  <tr class="table-hover" onclick="handleRowClick('<?=$num?>')" >
				<td class="text-center"  style="cursor:pointer;" ><?=$start_num?></td>
				<td class="text-center"  style="cursor:pointer;"><?=$inputdate?></td>
				<td  style="cursor:pointer;"><?=$inputlist?></td>
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

	</form>
  
 </body>
</html>		

<script>

var dataTable; // DataTables 인스턴스 전역 변수
var ceilingpartpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

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
    var savedPageNumber = getCookie('ceilingpartpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var ceilingpartpageNumber = dataTable.page.info().page + 1;
        setCookie('ceilingpartpageNumber', ceilingpartpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('ceilingpartpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
});

function restorePageNumber() {
    var savedPageNumber = getCookie('ceilingpartpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}


function handleRowClick(num)
{
	popupCenter('part_write_form.php?mode=modify&num=' + num ,'주요부품 입력',900,500);
	
}



$(document).ready(function(){	


$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
});


$("#searchBtn").click(function(){ 
	
	  // $BigsearchTag  설정
	  var str = '<?php echo $BigsearchTag; ?>' ;
	  
     $("#BigsearchTag").val(str.replace(' ','|'));
     $("#stable").val('1');
     $("#page").val('1');
     $("#list").val('1');
	 $("#board_form").submit();  
	 
 document.getElementById('board_form').submit();   
 
 
 });		
 
	 var numcopy = new Array(); 
	 var title = <?php echo json_encode($title_arr); ?> ;
	 var arr1  = <?php echo json_encode($partin_arr); ?> ;
	 var arr2  = <?php echo json_encode($partout_arr); ?> ;
	 var arr3  = <?php echo json_encode($partremain_arr); ?> ;
	 
	 console.log(title);
	 	  
	 var rowNum = title.length ;   // sum_title의 길이
	 var count=0;

	const COL_COUNT = 4;

	const data = [];
	const columns = [];
	
	 for(i=1;i<rowNum;i++) {	
	 
		const row = { name: count }; 
				 
		 for (let j = 0; j < COL_COUNT; j++ ) {				
			row[`col1`] = title[i] ;						 						
			row[`col2`] = arr1[i] ;			
			row[`col3`] = arr2[i] ;			
			row[`col4`] = arr3[i] ;			
				}
			
		if(title[i] !=='중국산휀')
		{
			  numcopy.push(i+1) ; 	
			  data.push(row); 	
		}				
	 }
	 
 const grid = new tui.Grid({
	  el: document.getElementById('grid'),
	  data: data,
	  bodyHeight: 330,					  					
	  columns: [ 				   
		{
		  header: '부품명',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:350,	 		
		  align: 'center'
		},			
		{
		  header: '입고 ',
		  name: 'col2',
		  width:100,	
		  align: 'center'
		},
		{
		  header: '출고',
		  name: 'col3',
		  width:100,	
		  align: 'center'
		},
		{
		  header: '재고',
		  name: 'col4',
		  width:100,	
		  align: 'center',
		}
	  ],
	columnOptions: {
			resizable: true
		  },
	  rowHeaders: ['rowNum'],
	  // pageOptions: {
		// useClient: false,
		// perPage: 20
	  // },
	});	
	var Grid = tui.Grid; // or require('tui-grid')
	Grid.applyTheme('striped', {
			selection: {
				background: '#4555f9',
				border: '#004082'
			  },
			  scrollbar: {
				background: '#f5f5f5',
				thumb: '#d9d9d9',
				active: '#c1c1c1'
			  },
			  row: {
				even: {
				  background: '#0000'
				},
				hover: {
				  background: '#cfe2ff'
				}
			  },
			  cell: {
				normal: {
				  background: '#fbfbfb',
				  border: '#e0e0e0',
				  showVerticalBorder: true
				},
				header: {
				  background: '#cfe2ff',
				  border: '#ccc',
				  showVerticalBorder: true
				},
				rowHeader: {
				  border: '#ccc',
				  showVerticalBorder: true
				},
				editable: {
				  background: '#fbfbfb'
				},
				selectedHeader: {
				  background: '#d8d8d8'
				},
				focused: {
				  border: '#418ed4'
				},
				disabled: {
				  text: '#b0b0b0'
				}
			  }	
	});	
	
	

// 더블클릭 이벤트	
grid.on('dblclick', (e) => {
	
    var link = 'https://8440.co.kr/ceiling/part_view.php?num=' + numcopy[e.rowKey] ; 	
   if(numcopy[e.rowKey]>0)
       popupCenter(link, '주요 부품 추적' ,800,900);	
   console.log(e.rowKey);
});		
 	

});

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('조명/천장 주요 부품 리스트'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>