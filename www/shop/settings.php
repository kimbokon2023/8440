<?php session_start(); 
							                	
$readIni = array();   // 환경파일 불러오기
$readIni = parse_ini_file("./settings.ini",false);	
// 초기 서버를 이동중에 저정해야할 변수들을 저장하면서 작업한다. 자료를 추가 불러올때 카운터 숫자등..
$init_read = array();   // 환경파일 불러오기
$init_read = parse_ini_file("./settings.ini",false);	

if(isset($_REQUEST["SelectWork"]))  // 어떤 작업을 지시했는지 파악해서 돌려줌.
	$SelectWork=$_REQUEST["SelectWork"];
		else 
			$SelectWork="";   // 초기화		

 if(file_exists('uploadfilearr.txt'))
    $myfiles = file("uploadfilearr.txt");
	   else
		   $myfiles = array();
	   
// DB에서 자재정보를 읽어온다.	   
require_once("../lib/mydb.php");
$pdo = db_connect();	
$sql="select * from mirae8440.steelsource"; 
    try{  

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $count=0;
   $source_num=array();
   $sortorder=array();
   $source_item=array();
   $source_spec=array();
   $source_take=array();   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
 			  $source_num[$count]=$row["num"];			  
 			  $sortorder[$count]=$row["sortorder"];
 			  $source_item[$count]=$row["item"];
 			  $source_spec[$count]=$row["spec"];
		      $source_take[$count]=$row["take"]; 
	        $count++;	   			  
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
	   
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- 화면에 UI창 알람창 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<body>
<title> 미래기업 원자재 환경설정 </title>
<style>
   @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");
   header * {
	color : white;
}

a {
	 text-decoration : none;
}
</style>

	<header class ="d-flex fex-column align-items-center flex-md-row p-1 bg-primary" >
    <h1 class="h4 my-0 me-md-auto"> 미래기업 원자재 환경설정 </h1>
	<div class="d-flex align-items-center">	  
	  <div class="flex-grow-1 ms-3">
			     <?php
					if(!isset($_SESSION["userid"]))
					{
				?>
						  <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
				<?php
					}
					else
					 {
				?>
					<?=$_SESSION["nick"]?> (level:<?=$_SESSION["level"]?>) | 
						<a href="../login/logout.php">로그아웃</a>
						
				<?php
					 }
				?>	
		
	  </div>
	  
</div>
	</header>
	<section class ="d-flex fex-column align-items-left flex-md-row p-1">
	 <div class="p-2 pt-md-3 pb-md-3 text-left" style="width:100%;">	  
		 <form id="mainFrm" method="post" enctype="multipart/form-data" action="process.php"  >		
            <input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>"> 
            <input type="hidden" id="vacancy" name="vacancy" > 
		    <div class="card-header"> 						
			           <div class="input-group p-1 mb-1">	
						<button  type="button" class="btn btn-primary" id="PreviousBtn">원자재화면으로 돌아가기 </button>	    	 &nbsp;&nbsp;							
						<button  type="button" class="btn btn-success" id="SavesettingsBtn">환경설정 적용&저장</button>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span class="input-group-text">  원자재 자료 수정/저장 화면 &nbsp;  </span>
									</div> 	
										<div class="input-group p-1 mb-1">										
										<span style="color:red;"> &nbsp;&nbsp; 원자재(철판) 단가(KG당) &nbsp;&nbsp;   </span>						 			 
										<span class="input-group-text bg-secondary "> <i class="bi bi-layout-text-window"></i> </span>
										<span class="input-group-text text-white  bg-secondary ">PO </span>						  					   
										<input  type="text" id="PO" style="color:grey;text-align:right;" name="PO" size="6" value="<?=$readIni['PO']?>"  onkeyup="inputNumberFormat(this)"> 
										<span class="input-group-text text-white  bg-secondary ">CR </span>						  					   
										<input  type="text" id="CR" style="color:black;text-align:right;" name="CR" size="6" value="<?=$readIni['CR']?>" onkeyup="inputNumberFormat(this)"> 
										<span class="input-group-text text-white  bg-secondary ">EGI </span>						  					   
										<input  type="text" id="EGI" style="color:brown;text-align:right;" name="EGI" size="6" value="<?=$readIni['EGI']?>" onkeyup="inputNumberFormat(this)"> 
										 <span class="input-group-text  text-white bg-secondary "> 304 HL </span>						  					   
										<input  type="text" id="HL304" style="color:purple;text-align:right;" name="HL304" size="6" value="<?=$readIni['HL304']?>" onkeyup="inputNumberFormat(this)"> 
										<span class="input-group-text  text-white bg-secondary "> 304 MR </span>						  					   
										<input  type="text" id="MR304" style="color:blue;text-align:right;" name="MR304" size="6" value="<?=$readIni['MR304']?>" onkeyup="inputNumberFormat(this)"> 						
										<span class="input-group-text  text-white bg-secondary "> 특수소재평균값 </span>						  					   
										<input  type="text" id="etcsteel" style="color:red;text-align:right;" name="etcsteel" size="6" value="<?=$readIni['etcsteel']?>" onkeyup="inputNumberFormat(this)"> 																					 													
										</div> 
									
									
												<div class="input-group p-1 mb-1">	  
													분류순서(그룹정렬)은 화면에 강제정렬을 위해 지정합니다. 1. CR, 2. PO, 3. EGI, 4. 304 HL,  5. 304 HL NSP, 6. 304 MR, 7. 304 BA, 8. 304 2B BA, 9. AL, 10. 기타 특수소재
												</div> 												
												
												<div class="input-group p-1 mb-1">	  
													
													<button  type="button" id="prependBtn"  class="btn btn-secondary" >DATA 추가</button> &nbsp;
													<button  type="button" id="deldataBtn" class="btn btn-warning">   선택 삭제</button> &nbsp;
													<button  type="button" id="calculate"  class="btn btn-dark" > </button> 															
												</div> 
											   
				</div>
						
				<div id="grid" style="float:left;width:800px;margin-left:30px;"> </div>		
					
	            <div id="tui-pagination-container" class="tui-pagination"></div>				
				 <input id="Call_Ecount" type=hidden value="0" >	
				 <input id="source_num" name="source_num[]" type=hidden value="<?=$source_num?>" >	
				 <input id="sortorder" name="sortorder[]" type=hidden value="<?=$sortorder?>" >	
				 <input id="source_item" name="source_item[]" type=hidden value="<?=$source_item?>" >	
				 <input id="source_spec" name="source_spec[]" type=hidden value="<?=$source_spec?>" >	
				 <input id="source_take" name="source_take[]" type=hidden value="<?=$source_take?>" >					 
	     	</form>		              
             	
            <form id="PreviousBtn_click" method="post" enctype="multipart/form-data" action="list.php"  >	</form>			  
		  </div>
		  
  <script> 

function inputNumberFormat(obj) { 
obj.value = comma(uncomma(obj.value)); 
} 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    str = str.replace(/\./g, ''); 
    return Number(str.replace(/[^\d]+/g, '')); 
}

$(document).ready(function(){
	
	let timer2 = setTimeout(function(){  // 시작과 동시에 계산이 이뤄진다.
		// calculateit();
		$("#TIME_DATE").val(getToday());		
		console.log(getToday());	
	}, 1000)
	
	
					 $("#PreviousBtn").click(function(){       // 이전화면 돌아가기		    							 
						   $("#PreviousBtn_click").submit();
					 });		
				 
					 $("#upload_fileplus").change(function(){   
						 $("#SelectWork").val('uploadfile_second');
							$("#mainFrm").submit();
					 });						  					 
					 
					 $("#SavesettingsBtn").click(function(){   
					    // 저장 취소 선택
							Swal.fire({ 
							       title: '환경파일 변경 후 내용저장', 
								   text: " 모든DATA가 다시 저장됩니다. '\n 진행하시겠습니까?", 
								   icon: 'warning', 
								   showCancelButton: true, 
								   confirmButtonColor: '#3085d6', 
								   cancelButtonColor: '#d33', 
								   confirmButtonText: '저장', 
								   cancelButtonText: '취소' })
								   .then((result) => { if (result.isConfirmed) { 
								    $("#SelectWork").val('saveini'); 	
                                     savegrid();  									
							       $("#mainFrm").submit();  
								   
								   Swal.fire( '저장이 완료되었습니다.', '변경완료!', 'success' ) } })	
								
									  // Swal.fire({ icon: 'success', // Alert 타입 
											  // title: '자료저장 성공', // Alert 제목 
											  // text: '자료가 저장되었습니다.', // Alert 내용 
											// });
								   
					 });		// 환경설정 저장클릭				
					 
									 
							 
					 $("#deldataBtn").click(function(){    deldataDo(); });	  
					 $("#SelInsertDataBtn").click(function(){    SelInsertData(); });	  							 
	
					 $("#Ecount_estimate").click(function(){Ecount_login_click('estimate');});	
					 
					 
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
					  					  
					var count = "<? echo $count; ?>"; 
					var source_item = <?php echo json_encode($source_item);?> ;
					var sortorder = <?php echo json_encode($sortorder);?> ;
					var source_spec = <?php echo json_encode($source_spec);?> ;
					var source_take = <?php echo json_encode($source_take);?> ;
					
					// console.log(source_item[0]);
					
					let row_count = count;
					const COL_COUNT = 3;
					
					const data = [];
					const columns = [];
					
				  if(count>0) {
					for (let i = 0; i < row_count; i += 1) {
					  const row = { name: i };
					  for (let j = 0; j < COL_COUNT; j += 1) {
						row[`sortorder`] = sortorder[i] ;	                   				
						row[`source_item`] = source_item[i] ;	                   				
						row['source_spec'] = source_spec[i] ;
						row['source_take'] = source_take[i] ;

					  }
						data.push(row);
					}
				 
				  
				const grid = new tui.Grid({
					  el: document.getElementById('grid'),
					  data: data,
					  bodyHeight: 550,
					   columns: [ 				   
						{
						  header: '분류순서(그룹정렬)',
						  name: 'sortorder',
						  sortingType: 'desc',
						  sortable: true,
						  width:150,
						  editor: {
							type: CustomTextEditor,
							options: {
							  maxsource_take: 40
							}
						  },	 		
						  align: 'center'
						},
						{
						  header: '철판종류',
						  name: 'source_item',
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
						  header: '재질규격',
						  name: 'source_spec',
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
						  header: '사급업체(여부)',
						  name: 'source_take',
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
					  rowHeaders: ['rowNum','checkbox'],
					  pageOptions: {
						useClient: false,
						perPage: 20
					  },
					});					

				 // 셀 자동계산 
					 calculate.addEventListener('click', () => {
					  calculateit();
					});									
					
				// grid 변경된 내용을 php 넘기기 위해 input hidden에 넣는다.
					function savegrid() {		
								 let source_num	    = new Array();
								 let source_spec	= new Array();
								 let sortorder	= new Array();
								 let source_item	= new Array();
								 let source_take	= new Array();
								 for(i=0;i<grid.getRowCount();i++) {                         
									 source_num[i] = i+1;
									 source_spec[i] = grid.getValue(i, 'source_spec');
									 // 콤마 제거 replace
                                     source_spec[i] = source_spec[i].replace(",", "_");
									 sortorder[i] = grid.getValue(i, 'sortorder');
									 sortorder[i] = sortorder[i].replace(",", "_");
									 source_item[i] = grid.getValue(i, 'source_item');
									 source_item[i] = source_item[i].replace(",", "_");
									 source_take[i] = grid.getValue(i, 'source_take');		
                                   if(source_take[i]!=null)									 
									    source_take[i] = source_take[i].replace(",", "_");									 
								 }	
								$('#source_num').val(source_num);				 
								$('#source_spec').val(source_spec);				 
								$('#sortorder').val(sortorder);				 
								$('#source_item').val(source_item);				 
								$('#source_take').val(source_take);										
                                // console.log(source_spec[0]);							
					   }					
					
					
					// Cell 변경이 발생할때 마다 계산
					    function calculateit() {					 

							 let set_source_item ;
							 let sortorder ;
							 let set_source_spec ;
							 let set_source_take ;				
							 
							 for(i=0;i<grid.getRowCount();i++) {                         
								 sortorder = grid.getValue(i, 'sortorder');
								 set_source_item = grid.getValue(i, 'source_item');
								 set_source_spec = grid.getValue(i, 'source_spec');
								 set_source_take = grid.getValue(i, 'source_take');  		 
								 
								// grid.setRow(i, grid.getRow(i));                                 // 새로운 row키 부여
								 grid.setValue(i, 'sortorder', sortorder);
								 grid.setValue(i, 'source_item', set_source_item);
								 grid.setValue(i, 'source_spec', set_source_spec);
								 grid.setValue(i, 'source_take', set_source_take);
							
							 }	 	
                           // console.log(grid.getValue(0, 'source_item'));							 
                          
						}	 

				 function ChangeData() {
					  calculateit();					  
				 }	 	

					grid.on('editingFinish', ev => {
					//  console.log('check!', ev);
					  ChangeData();  // 자료가 변경되면 다시 계산하는 루틴작성을 위한 연습
					});

					grid.on('mouseout', ev => {
					//  console.log('uncheck!', ev);
					  ChangeData();  // 자료가 변경되면 다시 계산하는 루틴작성을 위한 연습
					});
					const appendBtn = document.getElementById('appendBtn');		
					
					const appendedData = {
					  source_item : '',		     
					};
					 // InsertRow 1 (After row)
					// appendBtn.addEventListener('click', () => {
					  // grid.appendRow(appendedData);			
					  // // grid.on('focusChange', ({ rowKey }) => {
						  // // const index = grid.getIndexOfRow(rowKey);
						  // // grid.appendRow({}, { at: index + 1 })
						// // });
					  
					// });
					 // InsertRow 1 (Before row)
					prependBtn.addEventListener('click', () => {						
						  grid.prependRow(appendedData, {
															  at: 1,
															  extendPrevRowSpan: true,
															 focus: true	});
						});  
					// edit cell
					grid.on('afterChange', ev => {
					  const { changes } = ev;
						if (ev.origin === 'cell' && changes[0].columnName !== 'rowStatus') {
						  grid.setValue(changes[0].rowKey, 'rowStatus', 'U')
						}
					});
											
		
            function deldataDo()  {
				    var tmp = grid.getCheckedRowKeys();
					tmp.forEach(function(e){
                        grid.removeRow(e);
                     });					                    
					// console.log(grid.getCheckedRowKeys());
			      }				  
            function SelInsertData()  {    // 선택한 데이터 이후에 삽입
				    var tmp = grid.getCheckedRowKeys();
					tmp.forEach(function(e){
					 appendRow(e+1);        // 함수를 만들어서 한줄삽입처리함.
					  console.log(e);
					});	
					 
			     }					 
				 
			function appendRow(index) {
						var newRow = {
							eventId: '',
							localEvent: '',
							copyControl: ''
								};
						if (index== null) { // 행(row) 추가(끝에)
							grid.appendRow(epgCleanRow, null);
							} else { // 끝이 아닐때는 행(row) 삽입 실행
									var optionsOpt = {
											at: index,
											extendPrevRowSpan: false,
											focus: false
											};
									grid.appendRow(newRow , optionsOpt);
									}
				}
				 
	 
				 
		} // end of grid	 count>0 구문					
});	 // end of readydocument


 
function 	alert_msg(titlemsg,contextmsg) {
// 화면에 메시지창
	Swal.fire({ 
		   title: titlemsg, 
		   text: contextmsg , 
		   icon: 'success',                  // success, error, warning, info, question  5가지 가능함.
		   showCancelButton: true, 
		   confirmButtonColor: '#3085d6', 
		   cancelButtonColor: '#d33', 
		   confirmButtonText: '저장', 
		   cancelButtonText: '취소' })
		   .then((result) => { if (result.isConfirmed) { 
			$("#SelectWork").val('saveini'); 						 
			$("#mainFrm").submit();  
		   
		   Swal.fire( '수고하세요.', '알림완료!', 'success' ) } })		
}
	
					 
function getToday(){   // 2021-01-28 형태리턴
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;    //1월이 0으로 되기때문에 +1을 함.
    var date = now.getDate();

    month = month >=10 ? month : "0" + month;
    date  = date  >= 10 ? date : "0" + date;
     // ""을 빼면 year + month (숫자+숫자) 됨.. ex) 2018 + 12 = 2030이 리턴됨.

    //console.log(""+year + month + date);
    return today = ""+year + "-" + month + "-" + date; 
}

// 형식 : sleep(초)    //1/1000초 단위 ex) 5초 = sleep(5000)

function sleep(num){	//[1/1000초]

			 var now = new Date();

			   var stop = now.getTime() + num;

			   while(true){

				 now = new Date();

				 if(now.getTime() > stop)return;

			   }

}
</script> 	 
</section>

</body>

</html>