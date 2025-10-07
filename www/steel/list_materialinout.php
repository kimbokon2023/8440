<?php
 session_start();

 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }
   
ini_set('display_errors','1');
  
$titlemessage = '원자재 기간별 수불보고서';
 ?>
   
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
  
<title>  <?=$titlemessage?> </title> 
 </head>
<?php
if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
if(isset($_REQUEST["separate_date"]))   //출고일 접수일
	 $separate_date=$_REQUEST["separate_date"];	 
if(isset($_REQUEST["display_sel"]))   //목록표에 제목,이름 등 나오는 부분
	 $display_sel=$_REQUEST["display_sel"];	 
	 else
		$display_sel='doughnut';	 
	
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 $find=$_REQUEST["find"];	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	  

  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
   
  if($separate_date=="") $separate_date="2";
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 
$start=$_REQUEST["start"];	   // 처음실행할때 당월 데이터를 출력하기 위함.
if($start=='start')  
	{
			$year=substr(date("Y-m-d",time()),0,4) ;
			$month=substr(date("Y-m-d",time()),5,2) ;			
			$fromdate=$year . "-" . $month . "-" . "01" ;			
			// print date("Y-m-d",time());
			$todate=$year . "-" . $month . "-" . "31" ;
			$Transtodate=strtotime($todate.'+1 days');
			$Transtodate=date("Y-m-d",$Transtodate);	
	}
	else
	{
		if($fromdate=="")
		{
			$fromdate=substr(date("Y-m-d",time()),0,4) ;
			$fromdate=$fromdate . "-01-01";
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
	}		
  
$sql="select * from mirae8440.steelsource"; 					 // 자재DB에서 정보를 받아온다.

try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $counter=0;
   $steelsource_num=array();
   $steelsource_item=array();
   $steelsource_spec=array();
   $steelsource_take=array();   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   $counter++;
	   
 			  $steelsource_num[$counter]=$row["num"];			  
 			  $steelsource_item[$counter]=$row["item"];
 			  $steelsource_spec[$counter]=$row["spec"];
		      $steelsource_take[$counter]=$row["take"];   
	
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

$SettingDate="indate ";  //입고자재 배열 누적
$common="   where (outdate between date('$fromdate') and date('$Transtodate')) and (which='1') " ;  // 입고자재
 // 전체합계(입고부분)를 산출하는 부분 
$input_sum_title=array(); 
$input_sum=array();

$sql="select * from mirae8440.steel " .$common; 	
 
 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
              $num=$row["num"];			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  
			  $tmp=$item . $spec;
	
        for($i=1;$i<=$rowNum;$i++) {			 			  

	          $input_sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='1' and $tmp==$input_sum_title[$i])
				    $input_sum[$i]=$sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
     // $sum[$i]=(float)-1;				
		           } 

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


 // 전체합계(출고부분)를 처리하는 부분 

$SettingDate="outdate ";  //입고자재 배열 누적
$common="   where (outdate between date('$fromdate') and date('$Transtodate')) and (which='2') " ; 	 // 출고자재
$output_sum_title=array(); 
$output_sum=array();
 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  
			  $tmp=$item . $spec;
	
        for($i=1;$i<=$rowNum;$i++) {
			 			  
 			  
	          $output_sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='2' and $tmp==$output_sum_title[$i])
				    $output_sum[$i]=$sum[$i] - (int)$steelnum;			
		           }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

  // outdate는 원자재의 입출고일을 말한다. 
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from mirae8440.steel where (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  " . $a; 					
	                       			
			     }
			 elseif($search!=""&&$find!="all")  { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from mirae8440.steel where ($find like '%$search%') ";
							  $sql .=" and (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  ";

						}	   
				   
            elseif($search!=""&&$find=="all") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from mirae8440.steel where ((outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sql .="or (item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (comment like '%$search%')) and (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  ";

						}

               }
  if($mode=="") {
							 $sql="select * from mirae8440.steel where (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  "; 				
					
                }	
?>
		 
<body >

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
			<h4 class="modal-title"> 부족(마이너스) 상태 알림 </h4>
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

<form name="board_form" id="board_form" method="post" action="list_materialinout.php?mode=search&search=<?=$search?>&find=<?=$find?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>&display_sel=<?=$display_sel?>">  

<div class="container">
<div class="card">
<div class="card-body">  

<div class="card">
<div class="card-body">  
 	<div class="d-flex mb-3 mt-4 justify-content-center align-items-center"> 		 
		<H5>
			 <?=$titlemessage?>
		</H5>		 
	</div>	
    
	<div class="row"> 		  
		<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 		
		<!-- 기간설정 칸 -->
		 <?php include $_SERVER['DOCUMENT_ROOT'] . '/setdate.php' ?>
		  &nbsp;&nbsp; <button  type="button" class="btn btn-danger btn-sm" id="downloadcsvBtn"> CSV파일 다운로드 </button>
		</div>
	</div>	  
					  
		<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 					  
		<?php
		//  입고물량 누계				
		$sql="select * from mirae8440.steel where (outdate between date('$fromdate') and date('$Transtodate')) and (which='1')  ";  // 입고자재통계					
		$output_item_arr = array();	
		$output_weight_arr = array();	
		$input_make_name = array();	 // 입고물량의 임시이름 키값으로 사용함.
		$input_item = array();	 // 입고물량 배열
		$input_spec = array();	 // 입고물량 배열
		$input_arr_num = array();	 // 입고물량 배열
		$output_arr_num = array();	 // 출고물량 배열
		$sum_arr = array();	
		$sum = array();	
		$temp_arr = array();	
		$count=0;  

		$total=0;
		try{  
		$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
		$total_row=$stmh->rowCount();	  

		   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $num=$row["num"];
			  $outdate=$row["outdate"];			
			  $indate=$row["indate"];
			  $outworkplace=$row["outworkplace"];
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  $model=$row["model"];	 	
			  $temp_arr = explode("*", $spec);		
			  // 키값을 이름조합으로 만듬
			  $tmpName = $item . $spec;  // 재질 + 규격으로 고유이름을 만듬.

			 $found = 0;
			 for($i=0;$i<=$count;$i++)	{  // 값은 재질,규격이 있으면 누적함. 아니면 별도로 담음
					if($tmpName==$input_make_name[$i]) {
						$input_arr_num[$i] += (int) $steelnum;  // 같을때 수량 누적
						$found = 1;
						break;
						}
				  }							  
			 if(!$found) {  // 찾지 못했을때
						array_push( $input_make_name , $tmpName );
						array_push( $input_item , $item );
						array_push( $input_spec , $spec );
						array_push( $input_arr_num ,(int)$steelnum );				
						array_push( $output_arr_num , '0' );				
						array_push( $sum , '0' );													
						$total++;							  
				  }
														 
			  
			  $output_weight_arr[$count]=floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum)/1000000) ; //편의상 비중은 7.93으로 함. 별차이 없음
			  
							switch ($item) {
								case   "304 HL"     :   $output_item_arr[0] += $output_weight_arr[$count]; break;
								case   "304 MR"     :   $output_item_arr[1] += $output_weight_arr[$count]; break;	

								case   "PO"     :   $output_item_arr[3] += $output_weight_arr[$count]; break;	
								case   "EGI"     :   $output_item_arr[4] += $output_weight_arr[$count]; break;
								case   "CR"     :   $output_item_arr[5] += $output_weight_arr[$count]; break;									
								default:  $output_item_arr[2] += $output_weight_arr[$count];break;	
							}	
						  $count++;		
						  $start_num--;  
						 } 
			  } catch (PDOException $Exception) {
			  print "오류: ".$Exception->getMessage();
			  }  
			  
			  print "<br>";
			  print " <h5 span class='input-group-text'> 입고물량 : 304 HL " . number_format($output_item_arr[0]) . "KG, &nbsp;&nbsp;   ";
			  print "  304 MR " . number_format($output_item_arr[1]) . "KG, &nbsp;&nbsp;   " ; 
			  print "  기타SUS " . number_format($output_item_arr[2]) . "KG,  &nbsp;&nbsp;  " ; 
			  print " PO " . number_format($output_item_arr[3]) . "KG, &nbsp;&nbsp;    " ; 
			  print "  EGI " . number_format($output_item_arr[4]) . "KG, &nbsp;&nbsp;   " ; 
			  print " CR " . number_format($output_item_arr[5]) . "KG </h5> <br> </div>" ; 

                //  print "total input : " . $total . "EA" ;
				// print_r($input_arr_num);

				 //  출고물량 누계				
				$sql="select * from mirae8440.steel where (outdate between date('$fromdate') and date('$Transtodate')) and (which='2')  ";  // 출고자재통계					
				$$output_arr_num = array();	
				$output_item_arr = array();	
				$output_weight_arr = array();	
				$input_arr = array();	
				$material = array();	
				$grid_item = array();	
				$count=0;   
				 try{  
				  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
				  $total_row=$stmh->rowCount();	  
					
					   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
						  $num=$row["num"];
						  $outdate=$row["outdate"];			
						  $indate=$row["indate"];
						  $outworkplace=$row["outworkplace"];
						  $item=$row["item"];			  
						  $spec=$row["spec"];
						  $steelnum=$row["steelnum"];			  
						  $company=$row["company"];
						  $comment=$row["comment"];
						  $which=$row["which"];	 	
						  $model=$row["model"];	 	
						  $temp_arr = explode("*", $spec);	
						  $tmpName = $item . $spec;  // 재질 + 규격으로 고유이름을 만듬.						  

							$found = 0;
							for($i=0;$i<=$total;$i++)	{  // 값은 재질,규격이 있으면 누적함. 아니면 별도로 담음
									if(trim($tmpName)==trim($input_make_name[$i]))
										{
										// print $total . " match total : ". $tmpName . "   " . $input_make_name[$i] . "<br>" ;
										$output_arr_num[$i] += (int) $steelnum;  // 같을때 수량 누적
										$found = 1;
										break;
										}
							     }		
							if(!$found) {  // 찾지 못했을때
										// print $total . " wrong match total : ". $tmpName . "   " . $input_make_name[$i] . "<br>" ;
										array_push( $input_make_name , $tmpName );
										array_push( $input_item , $item );
										array_push( $input_spec , $spec );
										array_push( $input_arr_num , '0' );				
										array_push( $output_arr_num , $steelnum );				
										$total++;										
								  }
							 							  
				  
				  
								   $output_weight_arr[$count]=floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum)/1000000) ;
						  
										switch ($item) {
											case   "304 HL"     :   $output_item_arr[0] += $output_weight_arr[$count]; break;
											case   "304 MR"     :   $output_item_arr[1] += $output_weight_arr[$count]; break;	

											case   "PO"     :   $output_item_arr[3] += $output_weight_arr[$count]; break;	
											case   "EGI"     :   $output_item_arr[4] += $output_weight_arr[$count]; break;
											case   "CR"     :   $output_item_arr[5] += $output_weight_arr[$count]; break;									
											default:  $output_item_arr[2] += $output_weight_arr[$count];break;	
										}	
								
								$count++;	// 출고 자료 카운트
							 } 
						  } catch (PDOException $Exception) {
						  print "오류: ".$Exception->getMessage();
						  }  
						  
						  print ' <div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 	';
						  print " <h5 span class='input-group-text text-primary'> 출고물량 : 304 HL " . number_format($output_item_arr[0]) . "KG, &nbsp;&nbsp;   ";
						  print "  304 MR " . number_format($output_item_arr[1]) . "KG, &nbsp;&nbsp;   " ; 
						  print "  기타SUS " . number_format($output_item_arr[2]) . "KG,  &nbsp;&nbsp;  " ; 
						  print " PO " . number_format($output_item_arr[3]) . "KG, &nbsp;&nbsp;    " ; 
						  print "  EGI " . number_format($output_item_arr[4]) . "KG, &nbsp;&nbsp;   " ; 
						  print " CR " . number_format($output_item_arr[5]) . "KG </h5> <br> " ; 

                      // print "출고 총 아이템수량 : " . $total . "매" ;
                      // print "출고 총 아이템수량 count 배열 : " . count($input_make_name) . "매" ;
                       
					   $totalinput = 0 ;
					   $totaloutput = 0 ;
                       for($i=0;$i<=count($input_make_name);$i++)	{  // 값은 재질,규격이 있으면 누적함. 아니면 별도로 담음
								$material[$i] = $input_item[$i];	
								$grid_item[$i] = $input_spec[$i];	
								$input_num[$i] = $input_arr_num[$i];	
								$output_num[$i] =$output_arr_num[$i];	
								$sum[$i] = $input_arr_num[$i] -$output_arr_num[$i];
								$totalinput += $input_num[$i];
								$totaloutput += $output_num[$i];
						 }	
                       // print "총입고수량 합계 : " . $totalinput . "<br>";
                       // print "총출고수량 합계 : " . $totaloutput . "<br>";

			 ?>	                
	</div>       
	</div>  	
	</div>  	
	</div>  	
	<div class="card">
	<div class="card-body">  
		<style>
		
		 #grid {
			 width : 1000px;
		 }
		 </style>    
		 <div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 	
			<div id="grid" > 	
			</div>	
		</div>	
	</div>	
	</div>	
	
  </div>
</form>

<script>
$(document).ready(function(){
	// this_month();

			 $("#downloadcsvBtn").click(function(){  
               Do_gridexport();			   
			 });	


			 class CustomTextEditor {
			  constructor(props) {
				const el = document.createElement('input');
				const { maxsource_take } = props.columnInfo.editor.options;

				el.type = 'text';
				el.maxsource_take = maxsource_take;
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
								  
			var count = "<? echo $total; ?>"; 
			var material = <?php echo json_encode($material);?> ;	
			var grid_item = <?php echo json_encode($grid_item);?> ;	
			var input_num = <?php echo json_encode($input_num);?> ;	
			var output_num = <?php echo json_encode($output_num);?> ;	
			var sum = <?php echo json_encode($sum);?> ;	
			
			let row_count = count;
			const COL_COUNT = 5;
			
			const data = [];
			const columns = [];
			
		  if(count>0) {
			for (let i = 0; i < row_count; i += 1) {
			  const row = { name: i };
			  for (let j = 0; j < COL_COUNT; j += 1) {
				row[`material`] = material[i] ;	                   				
				row[`grid_item`] = grid_item[i] ;	                   				
				row['input_num'] = input_num[i] ;
				row['output_num'] = output_num[i] ;
				row['sum'] = sum[i] ;

			  }
				data.push(row);
			}		 
		  
		      const grid = new tui.Grid({
			  el: document.getElementById('grid'),
			  data: data,
			  bodyHeight: 450,
			   columns: [ 				   
				{
				  header: '재질구분',
				  name: 'material',
				  sortingType: 'desc',
				  sortable: true,
				  width:230,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 40
					}
				  },	 		
				  align: 'center'
				},
				{
				  header: '규격',
				  name: 'grid_item',
				  sortingType: 'desc',
				  sortable: true,
				  width:200,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 40
					}
				  },	 		
				  align: 'center'
				},						
				{
				  header: '입고수량',
				  name: 'input_num',
				  width:150,						  
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 10
					} 			
				  }	,  
					align: 'center'
				  // sortingType: 'desc',
				  // sortable: true,          
				  // editingEvent :  'Click'		  
				},
				{
				  header: '출고수량',
				  name: 'output_num',
				  width:150,
				  // sortingType: 'desc',
				  // sortable: true,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 10
					}
				  }	, 		  
				  align: 'center'
				},
				{
				  header: '수불합계(잔량파악)',
				  name: 'sum',
				  width:150,
				  // sortingType: 'desc',
				  // sortable: true,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 10
					}
				  }	, 		  
				  align: 'center'
				}							
			  ],
			 columnOptions: {
					resizable: true
				  },
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

		
		
		
		function Do_gridexport() { 	
		
		    const data = grid.getData();
				console.log(data);			
				console.log(data.length);

			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.
			
			let row_count = count;
			const COL_COUNT = 5;						
			
			for (let i = 0; i < row_count; i += 1) {
			  let row = "";			  
			   row += grid.getValue(i, 'material') + ',' ;
			   row += grid.getValue(i, 'grid_item') + ',' ;
			   row += grid.getValue(i, 'input_num') + ',' ;
			   row += grid.getValue(i, 'output_num') + ',' ;
			   row += grid.getValue(i, 'sum') + ',' ;
			   
			   csvContent += row + "\r\n";
			}		 		  

			// data.forEach(function(rowArray) {
				// let row = rowArray.join(",");
				// csvContent += row + "\r\n";
			// });
			
			// let csvContent = "data:text/csv;charset=utf-8,\uFEFF" + data.map(e => e.join(",")).join("\n");  // 간결한 표현식
			
			var encodedUri = encodeURI(csvContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download", "steel_InOut_report.csv");
			document.body.appendChild(link); 
			link.click();

			}    //csv 파일 export		
    } // end of grid	 count>0 구문			
});

</script>    
</div>
</body>
</html>