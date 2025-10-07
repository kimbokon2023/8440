<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	 
  
  if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {          		 
		 sleep(1);
		  header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }  
 

ini_set('display_errors','1');  // 화면에 warning 없애기   

?>
 
<?php include getDocumentRoot() . '/common.php' ?>
<?php include getDocumentRoot() . '/load_header.php' ?>


<title> 주요부품 입출고 이력 </title> 
</head>
 
<body >

 <?php

isset($_REQUEST["mode"])  ? $mode = $_REQUEST["mode"] : $mode=""; 
isset($_REQUEST["num"])  ? $num = $_REQUEST["num"] : $num=""; 
 
include "_request.php";

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();  	     

// part수
// 부품 title 배열 만들기
$title_arr = array("","일반휀","휠터휀 (LH용)","판넬고정구 (금성아크릴)","비상구 스위치 (건흥KH-9015)","비상등","할로겐(7W -6500K)","T5 일반 5W(300)","T5 일반 11W(600)","T5 일반 15W(900)","T5 일반 20W(1200)","T5 KS 6W(300)","T5 KS 10W(600)","T5 KS 15W(900)","T5 KS 20W(1200)","직관등 600mm","직관등 800mm","직관등 1000mm","직관등 1200mm","할로겐(7W -6500K KS)");
// part20 추가 7월20일 김부장님 요청 "할로겐(7W -6500K KS)"
$itemCount = count($title_arr)  ;

$title = $title_arr[$num-1];

$num_arr=array(); 
$data_arr=array();   // 입고일자 배열

// 전체합계(입고부분)를 산출하는 부분 

$sql="select * from mirae8440.part ";
 
$tmpsum = 0; 

 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowCount=$stmh->rowCount();
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   			
			for($i=0;$i<$itemCount;$i++)
				{
					$tmp = 'part' . (string)($num) ;   // $num으로 넘어온 배열 순서에 해당되는 것 찾기	
				 if( (int)$row[$tmp] > 0 )  // 0이 아니고 part번호가 같을때	
                    // 입고 누적 루틴										
					array_push($data_arr,$row['inputdate'] . "!input! 입고 ! " . $row[$tmp] );
				}
			
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


// 출고부분 합계 처리하는 부분 

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
					$tmp = 'part' . (string)($i+1) ;   // part1~20 생성 로직				                   					
				  if( ($i+1) == (int)$num && (int)$row[$tmp] > 0 )  // 0이 아니고 part번호가 같을때
					   array_push($data_arr, $basicdate . "!output!" .  $row["workplacename"] .  "!" . $row[$tmp] );   // 조립, LC 조립완료기준 기준으로 정리한다
				}			 
			  }			 

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
 
// 부품 입고/출고 계산해서 재고량 파악 
// 일자 배열을 합치고
// $arr = array_merge($inputdate_arr, $outputdate_arr);
$arr = array_unique($data_arr);

rsort($arr);    // 내림차순 정렬
// 배열형식의 데이터를 3분할 해서 입고 출고 이력을 만든다.

// var_dump($arr);

$inputNum = 0;
$outputNum = 0;

$inoutdate_arr=array(); 
$partin_arr=array(); 
$partout_arr=array(); 
$partremain_arr=array(); 
$placename=array(); 

sort($arr);    // 내림차순 정렬

for($i=0;$i<count($arr);$i++)
{
     $exarr = explode("!",$arr[$i]);	

     // print $i;	 
	 
	 $inoutdate_arr[$i] = $exarr[0];   // 배열의 역순으로 정리해서 본다.
	 
	 if($exarr[1]=='input')  // 입고 계산
	  {
		 $inputNum += (int)$exarr[3];
		 $partin_arr[$i] = (int)$exarr[3] ;
		 $placename[$i] = $exarr[2] ;
	  }
		 
	 if($exarr[1]=='output') // 출고 계산
	 {
		 $outputNum += (int)$exarr[3];	 
		 $partout_arr[$i] = (int)$exarr[3] ;
		 $placename[$i] = $exarr[2] ;
	 }
		 		
	$partremain_arr[$i] = $inputNum - $outputNum ;	
	 
}

// print '<br>';
// print '<br>';


// var_dump($partin_arr); 
// var_dump($partout_arr); 
// var_dump($partremain_arr); 

?>   
   
   
 <div class="container-fluid" >
  <form name="board_form" id="board_form"  method="post" action="part_table.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>">  
						
	<div class="d-flex mb-3 mt-2 justify-content-center align-items-center"> 
		<div id="display_board" class="text-primary fs-3 text-center" style="display:none"> 
		</div>     
	</div>	
	<div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 
		 <span class="fs-5">  일자별 주요 부품 입출고  </span> &nbsp;&nbsp; <span class="fs-5 text-primary">  <?=$title?> </span> &nbsp; &nbsp; &nbsp;
		 
		  <button id="closeBtn" type="button" class="btn btn-dark btn-sm " onclick="self.close();">창닫기</button>
	 </div>	
    <div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 		 
	
		 <div id="grid" class="board" >
	  
		  </div>

		</div>	
	
  
	</form>
 
 
 
        </div>		

  </body>
  </html>
  
  
<script>
$(document).ready(function(){	
	
  $('a').children().css('textDecoration','none');  // a tag 전체 밑줄없앰.	
  $('a').parent().css('textDecoration','none');

	 var numcopy = new Array(); 
	 var arr = <?php echo json_encode($inoutdate_arr); ?> ;
	 var arr1  = <?php echo json_encode($placename); ?> ;
	 var arr2  = <?php echo json_encode($partin_arr); ?> ;
	 var arr3  = <?php echo json_encode($partout_arr); ?> ;
	 var arr4  = <?php echo json_encode($partremain_arr); ?> ;
	 
	 
	 	  
	 var rowNum = arr.length ;   // sum_title의 길이
	 var count=0;

	const COL_COUNT = 5;

	const data = [];
	const columns = [];
	
	 for(i=rowNum-1;i>=0;i--) {	 // 역순으로 출력하기 0보다 크고 데이터수보다 작은 구간
	 
		const row = { name: count }; 
				 
		 for (let j = 0; j < COL_COUNT; j++ ) {				
			row[`col1`] = arr[i] ;						 						
			row[`col2`] = arr1[i] ;			
			row[`col3`] = arr2[i] ;			
			row[`col4`] = arr3[i] ;			
			row[`col5`] = arr4[i] ;			
				}
			numcopy[i] = i+1 ; 	
			data.push(row); 								
	 }
 
	 class CustomTextEditor {
	  constructor(props) {
		const el = document.createElement('input');
		const { maxLength } = props.columnInfo.editor.options;

		el.type = 'text';
		el.maxLength = maxLength;
		el.value = String(props.value);

		this.el = el;
	  }

	  getElement() {
		return this.el;
	  }

	  getValue() {
		return this.el.value;
	  }

	  mounted() {
		this.el.select();
	  }
	}	
	
	 
const grid = new tui.Grid({
	  el: document.getElementById('grid'),
	  data: data,
	  bodyHeight: 700,					  					
	  columns: [ 				   
		{
		  header: '입고 조립완료일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:150,		  
		  align: 'center'
		},			
		{
		  header: '현장명',
		  name: 'col2',
		  width:200,		  
		  align: 'left'
		},	
		{
		  header: '입고',
		  name: 'col3',
		  width:100,
		  // editor: 'text', 		
		  align: 'center'
		},
		{
		  header: '출고',
		  name: 'col4',
		  width:100,
		  // editor: 'text', 		
		  align: 'center'
		},
		{
		  header: '재고',
		  name: 'col5',
		  width:100,	
		  // editor : 'text',		  
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
			  }	
	});		
	

 	

});

function SearchEnter(){	
	 
    if(event.keyCode == 13){
		document.getElementById('board_form').submit(); 
    }
}

function movetoPage(page){ 	  
	  $("#page").val(page); 	  	  
     
     $("#list").val('1');
	 $("#board_form").submit();  
	 
}	


</script>