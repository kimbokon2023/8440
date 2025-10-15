<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// DB 이름 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 현재일자 변수지정
$todate = date("Y-m-d");
$nowday = date("Y-m-d");

// SQL 조건 및 쿼리
$common = " WHERE which <> '3' ORDER BY num";
$sql = "SELECT * FROM {$DB}.request" . $common;

$counter = 1;
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>자재 미입고 리스트</title>
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/steel.css">
    <link rel="stylesheet" type="text/css" href="../css/jexcel.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css" />
    
    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
    <script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
</head>

<?php
// 요청 파라미터 초기화
$check = $_REQUEST["check"] ?? ($_POST["check"] ?? '');
$plan_output_check = $_REQUEST["plan_output_check"] ?? ($_POST["plan_output_check"] ?? '0');
$output_check = $_REQUEST["output_check"] ?? ($_POST["output_check"] ?? '0');
$team_check = $_REQUEST["team_check"] ?? ($_POST["team_check"] ?? '0');
$measure_check = $_REQUEST["measure_check"] ?? ($_POST["measure_check"] ?? '0');
$page = $_REQUEST["page"] ?? 1;

// 정렬 관련 변수 초기화
$cursort = $_REQUEST["cursort"] ?? 0;
$sortof = $_REQUEST["sortof"] ?? 0;
$stable = $_REQUEST["stable"] ?? 0;
 
// 정렬 로직
if (isset($_REQUEST["sortof"])) {
    if ($sortof == 1 && $stable == 0) {      // 접수일 클릭되었을때
        $cursort = ($cursort != 1) ? 1 : 2;
    }
    if ($sortof == 2 && $stable == 0) {     // 납기일 클릭되었을때
        $cursort = ($cursort != 3) ? 3 : 4;
    }
    if ($sortof == 3 && $stable == 0) {     // 실측일 클릭되었을때
        $cursort = ($cursort != 5) ? 5 : 6;
    }
    if ($sortof == 4 && $stable == 0) {     // 도면작성일 클릭되었을때
        $cursort = ($cursort != 7) ? 7 : 8;
    }
    if ($sortof == 5 && $stable == 0) {     // 출고일 클릭되었을때
        $cursort = ($cursort != 9) ? 9 : 10;
    }
    if ($sortof == 6 && $stable == 0) {     // 청구 클릭되었을때
        $cursort = ($cursort != 11) ? 11 : 12;
    }
} else {
    $sortof = 0;
    $cursort = 0;
}

// 기타 변수 초기화
$sum = array();
$mode = $_REQUEST["mode"] ?? "";
$find = $_REQUEST["find"] ?? "";

// 기간을 정하는 구간
$fromdate = $_REQUEST["fromdate"] ?? "";
$todate = $_REQUEST["todate"] ?? "";

if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 4);
    $fromdate = $fromdate . "-01-01";
}

if ($todate == "") {
    $todate = substr(date("Y-m-d", time()), 0, 4) . "-12-31";
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}
 
// 배열 초기화
$counter = 0;
$outdate_arr = array();
$outworkplace_arr = array();
$item_arr = array();
$spec_arr = array();
$steelnum_arr = array();
$company_arr = array();
$sum_arr = array();

$sum1 = 0;
$sum2 = 0;
$sum3 = 0;

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $num = $row["num"];
        $outdate = $row["outdate"];
        $indate = $row["indate"];
        $outworkplace = $row["outworkplace"];
        $item = $row["item"];
        $spec = $row["spec"];
        $steelnum = $row["steelnum"];
        $company = $row["company"];
        $comment = $row["comment"];
        $which = $row["which"];
        
        $outdate_arr[$counter] = $outdate;
        $outworkplace_arr[$counter] = $outworkplace;
        $item_arr[$counter] = $item;
        $spec_arr[$counter] = $spec;
        $steelnum_arr[$counter] = $steelnum;
        $company_arr[$counter] = $company;
        
        $workitem = "";
        $sum_arr[$counter] = $workitem;
        $counter++;
    }
} catch (PDOException $ex) {
    error_log("자재 미입고 리스트 조회 오류: " . $ex->getMessage());
}
?>
		 
<body >

 <div id="wrap">
 
 <h1> &nbsp; 자재 미입고 리스트 </h1>
  <br>
	 <div id="grid" >
  
  </div>
     <div class="clear"></div> 		 

	 </div>

   <div class="clear"></div>	
   
   </div> 	   
  </div> <!-- end of wrap -->
  
<script>
    $(document).ready(function () {
        var arr1 = <?php echo json_encode($outdate_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr2 = <?php echo json_encode($outworkplace_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr3 = <?php echo json_encode($item_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr4 = <?php echo json_encode($spec_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr5 = <?php echo json_encode($steelnum_arr, JSON_UNESCAPED_UNICODE); ?>;
        var arr6 = <?php echo json_encode($company_arr, JSON_UNESCAPED_UNICODE); ?>;
        
        var rowNum = <?php echo $counter; ?>;
        
        var data = [];
        var columns = [];
        var COL_COUNT = 6;
        
        for (var i = 0; i < rowNum; i++) {
            var row = { name: i };
            for (var k = 0; k < COL_COUNT; k++) {
                row['col1'] = arr1[i];
                row['col2'] = arr2[i];
                row['col3'] = arr3[i];
                row['col4'] = arr4[i];
                row['col5'] = arr5[i];
                row['col6'] = arr6[i];
            }
            data.push(row);
        }
        
        function CustomTextEditor(props) {
            var el = document.createElement('input');
            var maxLength = props.columnInfo.editor.options.maxLength;
            
            el.type = 'text';
            el.maxLength = maxLength;
            el.value = String(props.value);
            
            this.el = el;
        }
        
        CustomTextEditor.prototype.getElement = function () {
            return this.el;
        };
        
        CustomTextEditor.prototype.getValue = function () {
            return this.el.value;
        };
        
        CustomTextEditor.prototype.mounted = function () {
            this.el.select();
        };
        
        var grid = new tui.Grid({
            el: document.getElementById('grid'),
            data: data,
            bodyHeight: 700,
            columns: [
                {
                    header: '요청일',
                    name: 'col1',
                    sortingType: 'desc',
                    sortable: true,
                    width: 120,
                    editor: {
                        type: CustomTextEditor
                    },
                    align: 'center'
                },
                {
                    header: '현장명',
                    name: 'col2',
                    width: 350,
                    editor: {
                        type: CustomTextEditor
                    },
                    align: 'center'
                },
                {
                    header: '철판종류',
                    name: 'col3',
                    width: 200,
                    editor: {
                        type: CustomTextEditor
                    },
                    align: 'center'
                },
                {
                    header: '규격',
                    name: 'col4',
                    width: 100,
                    editor: {
                        type: CustomTextEditor
                    },
                    align: 'center'
                },
                {
                    header: '수량',
                    name: 'col5',
                    width: 40,
                    editor: {
                        type: CustomTextEditor
                    },
                    align: 'center'
                },
                {
                    header: '납품업체',
                    name: 'col6',
                    width: 120,
                    editor: {
                        type: CustomTextEditor
                    },
                    align: 'center'
                }
            ],
            columnOptions: {
                resizable: true
            },
            rowHeaders: ['rowNum'],
            pageOptions: {
                useClient: false,
                perPage: 20
            }
        });
        
        var Grid = tui.Grid;
        Grid.applyTheme('default', {
            cell: {
                normal: {
                    background: '#fbfbfb',
                    border: '#e0e0e0',
                    showVerticalBorder: true
                },
                header: {
                    background: '#eee',
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
    });
</script>
  


  </body>
  
  <script> 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}


function pre_month(){    // 전월
// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10) {
    dd='0'+dd;
} 

mm=mm-1;
if(mm<1) {
    mm='12';
} 
if(mm<10) {
    mm='0'+mm;
} 
if(mm>=12) {
    yyyy=yyyy-1;
} 


frompreyear = yyyy+'-' + mm+'-01';

var tmp=0;
	  
switch (Number(mm)) {
	
	case 1 :
	case 3 :
	case 5 :
	case 7 :
	case 8 :
	case 10 :
	case 12 :
	  tmp=31 ;
	  break;
	case 2 :   
	   tmp=28;
	   break;
	case 4 :
	case 6 :
	case 9 :
	case 11:
       tmp=30;
	   break;
}  	  

topreyear = yyyy + '-' + mm + '-' + tmp ;

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 


function this_month(){   // 당해월
// document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-01';

var tmp=0;
	  
switch (Number(mm)) {
	
	case 1 :
	case 3 :
	case 5 :
	case 7 :
	case 8 :
	case 10 :
	case 12 :
	  tmp=31 ;
	  break;
	case 2 :   
	   tmp=28;
	   break;
	case 4 :
	case 6 :
	case 9 :
	case 11:
       tmp=30;
	   break;
		}  	  

     topreyear = yyyy + '-' + mm + '-' + tmp ;

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
    document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 


function this_year()  {   // 당해년도
//		document.getElementById('search').value=null; 
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
			dd = '0' + dd;
		} 

		if(mm<10) {
			mm = '0' + mm;
		} 

		frompreyear = yyyy + '-01' + '-01';

		var tmp=0;
			  
		switch (Number(mm)) {
			
			case 1 :
			case 3 :
			case 5 :
			case 7 :
			case 8 :
			case 10 :
			case 12 :
			  tmp=31 ;
			  break;
			  
			case 2 :   
			   tmp=28;
			   break;
			   
			case 4 :
			case 6 :
			case 9 :
			case 11:
	          tmp=30;
			   break;
				}  	  

			 topreyear = yyyy + '-' + mm + '-' + dd ;

			document.getElementById("fromdate").value = frompreyear;
			document.getElementById("todate").value = topreyear;
		    document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

</script>

  </html>
