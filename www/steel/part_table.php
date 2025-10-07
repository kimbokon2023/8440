<?php
 session_start();

 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
  
// ctrl shift R 키를 누르지 않고 cache를 새로고침하는 구문....
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

function conv_num($num) {
$number = (float)str_replace('|', '', $num);
return $number;
}

function trans_date($tdate) {
  if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
		else $tdate="";							
	return $tdate;	
}

// 모바일 접속 or PC접속 알아내기 PC는 mobile=0
$mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";
$mobile = 0;
if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT']))
   $mobile = 1;

 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>

<link rel="stylesheet" type="text/css" href="../css/steel.css?v=1"> 

<script src="http://8440.co.kr/js/date.js"></script>  <!-- 기간을 설정하는 관련 js 포함 -->
<script src="http://8440.co.kr/common.js"></script>

<!-- 최초화면에서 보여주는 상단메뉴 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<link  rel="stylesheet" type="text/css" href="../css/common.css?v=3">

<title> 미래기업 원자재(철판) 입출고 이력  </title> 
</head>
 
<body >
	<!-- 스피너를 감싸는 요소 추가 -->
	<div id="spinner-wrapper">
		<div class="spinner" ></div>
	</div>


	<!-- JavaScript 추가 spinner-->
	<script type="text/javascript">
		// 페이지가 로딩될 때 실행되는 함수
		window.onload = function() {
			// 스피너를 감싸는 요소를 가져옵니다.
			var spinnerWrapper = document.getElementById('spinner-wrapper');
			// 스피너를 표시합니다.
			spinnerWrapper.style.display = 'flex';
			// 페이지 로딩이 완료되면 스피너를 숨깁니다.
			window.setTimeout(function() {
				spinnerWrapper.style.display = 'none';
			}, 1000); // 1초 후에 실행
		};
	</script>



 <?php
 
 include "_request.php";

require_once("../lib/mydb.php");
$pdo = db_connect();	     
     
 // 철판종류에 대한 추출부분
 $sql="select * from mirae8440.steelsource order by sortorder asc, item desc "; 					

 try{  

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();     
   $steelsource=array();   
   $pass='0';
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   			  
          $company = trim($row["take"]);  // steelsource는 take 사급업체
			// 일반매입처리
			if ($company == '미래기업' || $company == '윤스틸' || $company == '현진스텐') {
				$company = '';
			}
			  
			      
				array_push($steelsource , trim($row["item"]) . '|' . trim($row["spec"]) . '|' . $company  ); 			  
			 
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}    

$steelsource_item = array_unique($steelsource);


 
if($fromdate=="")
{	
	$fromdate="2010-01-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}


// 각 아이템별 고유의 이름 즉 아이템 + 스펙 같고 사급여부에 따라 합치는 작업 필요함

 $sql="select * from mirae8440.steelsource order by sortorder asc, item asc, spec asc"; 	// 정렬순서 정함.				

 try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh   
   $counter=0;
   $steelsource_num=array();
   $steelsource_item=array();
   $steelsource_spec=array();
   $steelsource_take=array();   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	         
 			  $steelsource_num[$counter]=$row["num"];			  
 			  $steelsource_item[$counter]=trim($row["item"]);
 			  $steelsource_spec[$counter]=trim($row["spec"]);
			  
			  $company=trim($row["take"]);			    
			  if($row["take"]=='미래기업') $company='';	// 일반매입처리
			  if($row["take"]=='윤스틸') $company='';		// 일반매입처리	  
			  if($row["take"]=='현진스텐') $company='';		// 일반매입처리	  
		      $steelsource_take[$counter]= $company ;    			  
			  
			  $counter++;	   
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

$rowNum = $counter;

if($separate_date=="1") $SettingDate="outdate ";
    else
		 $SettingDate="indate ";

$common = "   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
  
 // 전체합계(입고부분)를 산출하는 부분 
$title_arr=array(); 
$titleurl_arr=array(); 
$sum= array();
$num_arr=array(); 
$partin_arr=array(); 
$partout_arr=array(); 
$partremain_arr=array(); 


// 전체합계(입고부분)를 처리하는 부분 
$sql="select * from mirae8440.steel " ;
 
 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
			  
			  $outdate=$row["outdate"];			  
			  $item=trim($row["item"]);			  
			  $spec=trim($row["spec"]);
			  $steelnum=$row["steelnum"];			  
			  $company=trim($row["company"]);
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  
			  if($company=='미래기업') $company='';	// 일반매입처리
			  if($company=='윤스틸') $company='';		// 일반매입처리	  
			  if($company=='현진스텐') $company='';		// 일반매입처리	  
			  $tmp=$item . '|' . $spec . '|' . $company;
	
        for($i=0;$i<=$rowNum;$i++) {  // 자재의 종류의 누적 숫자
	          $title_arr[$i] = trim($steelsource_item[$i]) . '|' . trim($steelsource_spec[$i]) . '|' . trim($steelsource_take[$i]) ;
			  if($which=='1' and $tmp==$title_arr[$i])
				     $partin_arr[$i]=$partin_arr[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표		 
     
		           }
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

 // 전체합계(출고부분)를 처리하는 부분 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
			  
			  $outdate=$row["outdate"];				  
			  $item=trim($row["item"]);			  
			  $spec=trim($row["spec"]);
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  		
			  
			  if($company=='미래기업') $company='';
			  if($company=='윤스틸') $company='';
			  if($company=='현진스텐') $company='';
			  
			  $tmp=$item . '|' . $spec . '|' . $company;
	
        for($i=0;$i<=$rowNum;$i++) {  // 자재의 종류의 누적 숫자
	           $title_arr[$i] = $steelsource_item[$i] . '|' . $steelsource_spec[$i] . '|' . $steelsource_take[$i] ;
			   $titleurl_arr[$i] =  rawurlencode($title_arr[$i]); 
			  if($which=='2' and $tmp==$title_arr[$i])
				    $partout_arr[$i]= $partout_arr[$i] + (int)$steelnum;		
				
		           }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
  
// part수
// 부품 title 배열 만들기
$itemCount = count($title_arr)  ;

// 부품 입고/출고 계산해서 재고량 파악 
// 타이틀 분할
$title_arr1=array(); 
$title_arr2=array(); 
$title_arr3=array(); 
for($i=0;$i<$itemCount;$i++)
  {
		$partremain_arr[$i] = (int)$partin_arr[$i] - (int)$partout_arr[$i]  ;
		$tmp = explode('|',$title_arr[$i]);
		
		array_push($title_arr1, $tmp[0]);
		array_push($title_arr2, $tmp[1]);
		array_push($title_arr3, $tmp[2]);
  }


?>   
   
  
   
 <div class="container-fluid" > 
 
  <form name="board_form" id="board_form"  method="post" action="part_table.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>">  
   
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
						
	<div class="d-flex mb-3 mt-2 justify-content-center align-items-center"> 
		<div id="display_board" class="text-primary fs-3 text-center" style="display:none"> 
		</div>     
	</div>	
	<div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 
		 <span class="fs-5">  원자재(철판) 입출고 현황 &nbsp;  </span> &nbsp; &nbsp; &nbsp;
		 <button id="closeBtn" type="button" class="btn btn-dark btn-sm " onclick="self.close();">창닫기</button>
	 </div>	
    <div class="d-flex mb-1 mt-2 justify-content-center align-items-center"> 		 

	   
		 <div id="grid" class="board" >		 </div>   </div>  
	 
 
	</form>
 
 
        </div>		

  </body>
  </html>
  
  
<script>
$(document).ready(function(){	

	
  $('a').children().css('textDecoration'|'none');  // a tag 전체 밑줄없앰.	
  $('a').parent().css('textDecoration'|'none');

	 var numcopy = new Array(); 
	 var arr =  <?php echo json_encode($titleurl_arr); ?> ;
	 var title = <?php echo json_encode($title_arr1); ?> ;
	 var arr1  = <?php echo json_encode($title_arr2); ?> ;
	 var arr2  = <?php echo json_encode($title_arr3); ?> ;
	 var arr3  = <?php echo json_encode($partin_arr); ?> ;
	 var arr4  = <?php echo json_encode($partout_arr); ?> ;
	 var arr5  = <?php echo json_encode($partremain_arr); ?> ;
	 
	 console.log(title);
	 	  
	 var rowNum = title.length ;   // sum_title의 길이
	 var count=0;

	const COL_COUNT = 6;
	
	let counter=0;

	const data = [];
	const columns = [];
	
	 for(i=0;i<rowNum;i++) {	
	 
		const row = { name: count }; 
		// 잔여수량이 0보다 큰 것만 나타내기
		if(Number(arr5[i])>0 )
		 {
		 for (let j = 0; j < COL_COUNT; j++ ) {				
			row[`col1`] = title[i] ;						 						
			row[`col2`] = arr1[i] ;			
			row[`col3`] = arr2[i] ;			
			row[`col4`] = arr3[i] ;			
			row[`col5`] = arr4[i] ;			
			row[`col6`] = arr5[i] ;			
				}
			numcopy[counter] = arr[i] ; 	
			counter++;
			data.push(row); 	
		 }		
				
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
	  bodyHeight: 600,					  					
	  columns: [ 				   
		{
		  header: '부품명',
		  name: 'col1',
		  sortingType: 'desc',
		  sortable: true,
		  width:250,		  
		  align: 'center'
		},			
		{
		  header: 'SPEC ',
		  name: 'col2',
		  width:150,		  
		  align: 'center'
		},	
		{
		  header: '사급업체',
		  name: 'col3',
		  width:150,		  
		  align: 'center'
		},	
		{
		  header: '입고 ',
		  name: 'col4',
		  width:100,		  
		  align: 'center'
		},
		{
		  header: '출고',
		  name: 'col5',
		  width:100,		  
		  align: 'center'
		},
		{
		  header: '재고',
		  name: 'col6',
		  width:100,			  	
		  align: 'center',
		}
	  ],
	columnOptions: {
			resizable: true
		  },
	  rowHeaders: ['rowNum'],
	  pageOptions: {
		useClient: false,
		perPage: 20
	  },
});	
	
// grid 색상등 꾸미기
	var Grid = tui.Grid; // or require('tui-grid')
	Grid.applyTheme('default', {
			selection: {
				background: '#FFFF',
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
				  background: '#FFFF'
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
	
	

// 더블클릭 이벤트	
grid.on('dblclick', (e) => {	
    var link = 'http://8440.co.kr/steel/part_view.php?arr=' + numcopy[e.rowKey] ; 	   
   popupCenter(link, '원자재 입출고 상세내역' ,1000,900);	
   
});		
 	

});


</script>