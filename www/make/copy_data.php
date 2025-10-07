  <?php
  session_start(); 

// 첫 화면 표시 문구
$title_message = '도장 발주';					  
					  
  if(isset($_REQUEST["sort"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $sort=$_REQUEST["sort"];
  else
   $sort="1";

$num=$_REQUEST["num"];	
if(isset($_REQUEST["mode"]))  //
	   $mode=$_REQUEST["mode"];
	  else
	   $mode="not";

  require_once("../lib/mydb.php");
  $pdo = db_connect();

    try{
      $sql = "select * from mirae8440.make where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
			  $num=$row["num"];
			  $orderdate=$row["orderdate"];
			  $indate=$row["indate"];
			  $company=$row["company"];
			  $text=$row["text"];  	  

	     }		 
			  
      }
     catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }	 
  
$company_arr = array();

array_push($company_arr,"");
array_push($company_arr,"진성산업");
array_push($company_arr,"유일기업");
array_push($company_arr,"은성산업");
array_push($company_arr,"한산엘테크");
$company_count=count($company_arr);	 

// 발주일자 접수일자 오늘자로 초기화
$orderdate = date("Y-m-d");
$indate = date("Y-m-d");

?>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

<title> <?=$title_message?> </title> 
  
<body>

<? include '../myheader.php'; ?>   
   
<div class="container justify-content-center ">  
<div class="card mt-2 mb-4">  
<div class="card-body">  
<div class="d-flex justify-content-center mt-2 mb-2">   

     <h4> &nbsp; &nbsp; 발주서 등록/수정 (발주처 반드시 지정하세요!) &nbsp; &nbsp;  발주번호:&nbsp; <?=$num?> &nbsp;  </h4>

		<button type=button  class="btn btn-secondary btn-sm" onclick="javascript:load('list.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>')" > 목록 </button>&nbsp;						

		<button type=button  class="btn btn-dark btn-sm" onclick="javascript:savetext('insert.php?num=<?=$num?>&page=<?=$page?>&text=<?=$text?>')" > 완료(저장) </button>&nbsp;

	</div>

 <?php
    if($mode=="modify"){
  ?>
	<form  id="board_form" name="board_form" method="post"   action="insert.php?mode=modify&num=<?=$num?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>" > 
  <?php  } 
else  {
  ?>
	<form  id="board_form" name="board_form" method="post"  action="insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>&scale=<?=$scale?>"> 
  <?php
	}
  ?>	   
<div class="d-flex justify-content-center mt-1 mb-1">   	      
	<span class="badge bg-secondary fs-6">  발주일   </span>&nbsp;
	 <input   type="date" id="orderdate" name="orderdate" value="<?=$orderdate?>"  > &nbsp; &nbsp;
	 <span class="badge bg-secondary fs-6">  접수일   </span>&nbsp;
	  <input    type="date" id="indate" name="indate" value="<?=$indate?>" >&nbsp; &nbsp;
	 <span class="badge bg-secondary fs-6">  발주처   </span>&nbsp;
		<select name="company" id="company" >
				   <?php		

				   for($i=0;$i<$company_count;$i++) {
						   if($company==$company_arr[$i])
								   print "<option selected value='" . $company_arr[$i] . "'> " . $company_arr[$i] .   "</option>";
							   else
									print "<option value='" . $company_arr[$i] . "'> " . $company_arr[$i] .   "</option>";
				   } 		   
						?>	  
				</select>	 	 
</div>	
	<div class="d-flex justify-content-center mt-2">   	      	  
		 <span  class="badge bg-danger fs-5"> 콤마(,)를 사용하면 자료가 정확히 나오지 않습니다. 콤마(,)는 절대 사용하지 마세요!  </span>
	</div>				

<div class="d-flex justify-content-center mt-2 mb-1">   	      	 	 
	<div id="grid"> </div>	 
</div> 

		<input type="hidden" id="textsave" name="textsave" value="<?=$text?>" size="100" > 		
		<input type="hidden" id="mode" name="mode" value="<?=$mode?>" size="100" > 		
		<input type="hidden" id="page" name="page" value="<?=$page?>" size="100" > 		
	

  			</form>     
  
   </div> <!--card-body-->
   </div> <!--card -->
   </div> <!--container-->

<script>


$(document).ready(function(){

		var mode='<?php echo $mode; ?>' ;	

		var arr=<?php echo  json_encode($arr);?>;
		var name='<?php echo $user_name; ?>' ;
		var counter = '<?php echo $counter ;?>';
		var left_check = new Array();
		var mid_check = new Array();
		var right_check = new Array();
		var done_check = new Array();
		var remain_check = new Array();

		var tmp; 

		var rowNum = "<? echo $counter; ?>" ; 	
		let row_count = 50;
		const COL_COUNT = 6;

			const data = [];
			const columns = [];	

		 var text='<?php echo $text; ?>' ;	
		 
		 
		 console.log(text);
															// copy인경우를 넣어줘야 한다. 도장복사시....
		 arr=text.split('|');
		if(mode=='copy') {                                  // copy인경우를 넣어줘야 한다. 도장복사시....
				for(i=0;i<arr.length-1;i++) {	
					 const row = { name: i }; 
					 tmp=arr[i].split(',');	
					 for (let j = 0; j < COL_COUNT; j++ ) {				
						row[`col1`] = tmp[0] ;						 						
						row[`col2`] = tmp[1] ;						 						
						row[`col3`] = tmp[2] ;						 						
						row[`col4`] = tmp[3] ;						 						
						row[`col5`] = tmp[4] ;						 						
						row[`col6`] = tmp[5] ;						 						
						row[`col7`] = tmp[6] ;						 						
						row[`col8`] = '';						 						
						row[`col9`] = '';						 						
							}
						data.push(row); 
				}
		}
		 else
		 {	 
				for(i=0;i<15;i++) {	
					 const row = { name: i }; 					 
						row[`col1`] = '' ;						 						
						row[`col2`] = '' ;						 											 						
						row[`col3`] = '' ;						 											 						
						row[`col4`] = '' ;						 												 						
						row[`col5`] = '' ;						 											 						
						row[`col6`] = '' ;						 												 						
						row[`col7`] = '' ;						 											 						
						row[`col8`] = '' ;						 											 						
						row[`col9`] = '' ;						 											 						
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
			  bodyHeight: 620,					  					
			  columns: [ 				   
				{
				  header: '구분',
				  name: 'col1',
				  sortingType: 'desc',
				  sortable: true,
				  width:50,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxLength: 50
					}
				  },	 		
				  align: 'center'
				},			
				{
				  header: '현장명',
				  name: 'col2',
				  width:300,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxLength: 50
					}
				  },	 		
				  align: 'center'
				},
				{
				  header: '품목',
				  name: 'col3',
				  width:200,
				  editor: {
							type: 'select',
							options: {
							  maxLength: 20,
							  useViewMode: true, // 추가
							  listItems: [
								{ text: '천장', value: '천장' },
								{ text: 'L/C', value: 'L/C' },
								{ text: '중판', value: '중판' },
								{ text: '커버', value: '커버' },
								{ text: '쪽쟘상판', value: '쪽쟘상판' },
								{ text: '쪽쟘기둥', value: '쪽쟘기둥' },
								{ text: '쟘상판', value: '쟘상판' },
								{ text: '쟘기둥', value: '쟘기둥' },
								{ text: '휀커버', value: '휀커버' },
								{ text: '보강', value: '보강' },
								{ text: '비상구', value: '비상구' },
								{ text: '', value: '' },							  								
							  ]
							}
						  }	, 			 		
				  align: 'center'
				},				
				{
				  header: '특이품목 기재시 적용',
				  name: 'col9',
				  width:150,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxLength: 20
					}
				  },	 		
				  align: 'center'
				},
				{
				  header: '수량',
				  name: 'col4',
				  width:70,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxLength: 50
					}
				  },	 		
				  align: 'center'
				},
				{
				  header: '단위',
				  name: 'col5',
				  width:70,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxLength: 50
					}
				  },	 		
				  align: 'center'
				},
				{
				  header: '단가',
				  name: 'col6',
				  width:60,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxLength: 50
					}
				  },	 		
				  align: 'center'
				},
				{
				  header: '기본색상',
				  name: 'col7',
				  width:160,
				  editor: {
							type: 'select',
							options: {
							  maxLength: 20,
							  useViewMode: true, // 추가
							  listItems: [
								{ text: '백색무광', value: '백색무광' },
								{ text: '백색유광', value: '백색유광' },
								{ text: '흑색무광', value: '흑색무광' },
								{ text: '흑색유광', value: '흑색유광' },
								{ text: '펄골드', value: '펄골드' },
								{ text: '진고동', value: '진고동' },
								{ text: '샴페인골드', value: '샴페인골드' },
								{ text: 'DK쿠퍼', value: 'DK쿠퍼' },
								{ text: '', value: '' },
															
							  ]
							}
						  }	, 		
				  align: 'center'
				},	
				{
				  header: '별도색상인 경우(기재)',
				  name: 'col8',
				  width:200,
					  editor: {
					type: CustomTextEditor,
					options: {
					  maxLength: 50
					}
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
			  },
			});	
			
		 savetext = function(href) {
			 var tmp;
			 tmp="";
				 
			 for(i=0;i<grid.getRowCount();i++) {
				tmp = tmp +grid.getValue(i, 'col1');			
				tmp += ',' + grid.getValue(i, 'col2');
				tmp += ',' + grid.getValue(i, 'col3');
				tmp += ',' + grid.getValue(i, 'col4');
				tmp += ',' + grid.getValue(i, 'col5');
				tmp += ',' + grid.getValue(i, 'col6');
				tmp += ',' + grid.getValue(i, 'col7');
				tmp += '|' ;		 
			 }
				 $("#textsave").val(tmp);			// 테이블의 텍스트를 히든형태로 폼에 기록하기
				 $("form:first").submit();  // 페이지의 첫번째 폼을 서밋한다.
				 console.log(trlength);
				 console.log(tmp);
			}			
	  
	  grid.hideColumn('col1');
	  
// Cell 변경이 발생할때 마다 계산

					grid.on('editingFinish', ev => {

					 let set_num;  
					 let set_color; 		
					 let set_item; 
					 let set_place; 
					 
				  	 let i = ev.rowKey;
					 console.log(ev.rowKey);
					 set_num = Number(grid.getValue(i, 'col4')) ; 
					EAstring = grid.getValue(i, 'col5') ; 					 
					 const mode= '<?php echo $mode ;?>';
				     if(set_num!='' && mode!="modify"  && EAstring ==='') 	 
						 {
						     grid.setValue(i, 'col5', 'EA');
						    // grid.setValue(i, 'col7', '백색유광');
							 
						 }

					set_color = grid.getValue(i, 'col8') ;  						 
						 if(set_color!='') 
						 {
						     grid.setValue(i, 'col7', set_color);
							 
						 }		         

					 set_item = grid.getValue(i, 'col9') ;  						 
						 if(set_item!='') 
						 {
						     grid.setValue(i, 'col3', set_item);
							 
						 }						
					 set_place = grid.getValue(i, 'col2') ;
					 
					});

		  	  

});


function saveit() {
// $("#modify").val("1");			// 이전화면 유지
 save_check();
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    	
}



 
function load(href) 
     {
           document.location.href = href;  
}	
function movetowin(href) 
     {
		   save_check();
           document.location.href = href;  
}	

function send_alert() {   // 알림을 서버에 저장 
var tmp; 				
		
	tmp="./save_alert.php";	
		
    $("#vacancy").load(tmp);      
	
    alertify.alert('발주서 전송 알림창', '<h1> 발주서가 전송되었습니다. </h1>'); 	

 }       

 </script> 

</body>

	
 </html>
