<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/session.php';
 
$menu=$_REQUEST["menu"]; 
   
$title_message = '원자재 내역 추적';   
   
?>

<?php 

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
		 sleep(1);
	          header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
   }    
   
	include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';
   
 ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/modal.php'; ?>

<title> <?=$title_message?>  </title>

</head>

<body>

<?php

isset($_REQUEST["arr"]) ? $reviced_arr = str_replace("@", "+", urldecode($_REQUEST["arr"])) : $reviced_arr = "";

// 자재명에 & 사용한 것을 @로 변경했으니 다시 돌려줌
// $reviced_arr = str_replace("@", "&", $reviced_arr);

// var_dump($reviced_arr);
 
include "_request.php";

require_once("../lib/mydb.php"); 
$pdo = db_connect();	     
    
$num_arr=array(); 
$data_arr=array();   // 입고일자 배열

// 전체합계(입고부분)를 산출하는 부분 
$sql = "SELECT * FROM mirae8440.steel ";
$tmpsum = 0 ;

try {
    // 레코드 전체 sql 설정
    $stmh = $pdo->query($sql);
    $rowCount = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        $outdate = $row["outdate"];
        $item = trim($row["item"]);
        $spec = trim($row["spec"]);
        $steelnum = $row["steelnum"];
        $company = trim($row["company"]);
        $comment = $row["comment"];
        $which = $row["which"];
    
        $tmp = $item . '|' . $spec . '|' . $company;

        if ($which == '1' && $tmp == trim($reviced_arr)) {
			if($row["supplier"] !== null)
					array_push($data_arr, $row['outdate'] . "|input| (입고) 공급처: " . $row["supplier"] . " - " . $row["outworkplace"] . " | " . $steelnum);
				else
					array_push($data_arr, $row['outdate'] . "|input| (입고)  - " . $row["outworkplace"] . " | " . $steelnum);
        }

        if ($which == '2' && $tmp == trim($reviced_arr)) {
            array_push($data_arr, $row['outdate'] . "|output| " . $row["outworkplace"] . " | " . $steelnum);
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 부품 입고/출고 계산해서 재고량 파악
sort($data_arr); // 내림차순 정렬

$inputNum = 0;
$outputNum = 0;

$inoutdate_arr = array();
$partin_arr = array();
$partout_arr = array();
$partremain_arr = array();
$placename = array();

// var_dump($arr);

for ($i = 0; $i < count($data_arr); $i++) {
    $exarr = explode("|", $data_arr[$i]);

    $inoutdate_arr[$i] = $exarr[0];

    if (trim($exarr[1]) == 'input') { // 입고 계산
        $inputNum += (int)$exarr[3];
        $partin_arr[$i] = (int)$exarr[3];
        $placename[$i] = $exarr[2];
    }

    if (trim($exarr[1]) == 'output') { // 출고 계산
        $outputNum += (int)$exarr[3];
        $partout_arr[$i] = (int)$exarr[3];
        $placename[$i] = $exarr[2];
    }

    $partremain_arr[$i] = $inputNum - $outputNum;
}


?>   
  
<form name="board_form" id="board_form"  method="post" action="part_table.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>">    
   
 <div class="container-fluid" >
  
						
	<div class="d-flex mb-3 mt-2 justify-content-center align-items-center"> 
		<div id="display_board" class="text-primary fs-3 text-center" style="display:none"> 
		</div>     
	</div>	
	
	<div class="row justify-content-center align-items-center"> 
	<div class="col-sm-2"> 
	</div>
	<div class="col-sm-8"> 
		<div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 
			<span class="fs-5 text-primary">  <?=$reviced_arr?>  </span>   <span class="fs-5">&nbsp; (입출고 상세내역) 추적  ( <?=count($data_arr)?> 건 기록) </span>	
		 </div>	
	 </div>
	<div class="col-sm-2 text-end"> 
		<button id="closeBtn" type="button" class="btn btn-secondary btn-sm " onclick="self.close();"><i class="bi bi-x-lg"></i>  창닫기 </button>
	</div>	 
	</div>	 
    <div class="row mb-1 mt-2 justify-content-center align-items-center"> 		 
	
		 <div id="grid" >
	  
		  </div>

		</div>	
		
        </div>		
  
	</form>
 
  </body>
  </html>
  
  
<script>
$(document).ready(function(){	
	
  $('a').children().css('textDecoration'|'none');  // a tag 전체 밑줄없앰.	
  $('a').parent().css('textDecoration'|'none');

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
	  bodyHeight: 750,					  					
	  columns: [ 				   
		{
		  header: '입출고일',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:150,		  
		  align: 'center'
		},			
		{
		  header: '현장명',
		  name: 'col2',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:440,
		  align: 'left'
		},	
		{
		  header: '입고',
		  name: 'col3',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:100,	 		
		  align: 'center'
		},
		{
		  header: '출고',
		  name: 'col4',
		  sortingType: 'desc',
		  sortable: true,		  
		  width:100,
		  align: 'center'
		},
		{
		  header: '재고',
		  name: 'col5',
		  sortingType: 'desc',
		  sortable: true,		  
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
	
	// grid 색상등 꾸미기
	var Grid = tui.Grid; // or require('tui-grid')
	Grid.applyTheme('default', {
			selection: {
				background: '#e6eef5',
				border: '#fdfcfc'
			  },
			  scrollbar: {
				background: '#e6eef5',
				thumb: '#d9d9d9',
				active: '#c1c1c1'
			  },
			  row: {
				hover: {
				  background: '#ccc'
				}
			  },
			  cell: {
				normal: {
				  background: '#fbfbfb',
				  border: '#e6eef5',
				  showVerticalBorder: true
				},
				header: {
				  background: '#e6eef5',
				  border: '#fdfcfc',
				  showVerticalBorder: true
				},
				rowHeader: {
				  border: '#e6eef5',
				  showVerticalBorder: true
				},
				editable: {
				  background: '#fbfbfb'
				},
				selectedHeader: {
				  background: '#e6eef5'
				},
				focused: {
				  border: '#e6eef5'
				},
				disabled: {
				  text: '#e6eef5'
				}
			  }	
	});


 	

});


</script>