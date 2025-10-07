<?php
// 원자재 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'steelini.php';   
	 
$today=date("Y-m-d");  // 현재일 저장   	 

$tablename = 'wssteel';

$st_content='';

 require_once("../lib/mydb.php");
 $pdo = db_connect();

if($id!=null && $id!=0)
{	
  
 try{
     $sql = "select * from mirae8440." . $tablename . " where id=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $id, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
	
	 include 'steelrowDB.php';

		      if($demand!="0000-00-00" and $demand!="1970-01-01" and $demand!="")  $demand = date("Y-m-d", strtotime( $demand) );
					else $demand="";			
		      if($doneday!="0000-00-00" and $doneday!="1970-01-01" and $doneday!="")  $doneday = date("Y-m-d", strtotime( $doneday) );
					else $doneday="";			
		      if($regist_day!="0000-00-00" and $regist_day!="1970-01-01" and $regist_day!="")  $regist_day = date("Y-m-d", strtotime( $regist_day) );
					else $regist_day="";		
					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
}

// grid 분할하는 로직 불러오기 공통사용
include 'load_steel.php';

if($mode=='new')
{
	$regist_day = date('Y-m-d');
}

if($mode=='copy')  // 복사면 id를 초기화 한다.
{
	$id = '';
	$copystr = '(복사 데이터)';
}


// 품목관리 항목지정
 try{
     $sql = "select * from mirae8440.wssteelitem ";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $id, PDO::PARAM_STR);      
     $stmh->execute();            
      
    $steelitem = array();
	array_push($steelitem,''); 
	
	while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			
			array_push($steelitem, $row["item"]); 
				
	    }	// end of while
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }

// print_r($steelitem);

// json형태의 배열로 만들어보기

$steelitemtmp ='';

sort($steelitem);

for($i=0;$i<count($steelitem);$i++)
{
   $steelitemtmp .= " { text : '" . $steelitem[$i] . "' , value: '"  . $steelitem[$i] . "' }";  	
  if( $i!= count($steelitem)-1) // 마지막에는 콤마를 없애준다.
   $steelitemtmp .= "," ;	
}

// 규격 항목지정
 try{
     $sql = "select * from mirae8440.wssteelspec ";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $id, PDO::PARAM_STR);      
     $stmh->execute();            
      
    $steelspec = array();
	array_push($steelspec,''); 
	
	while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			
			array_push($steelspec, $row["spec"]); 
				
	    }	// end of while
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }

// print_r($steelspec);

// json형태의 배열로 만들어보기

$steelspectmp ='';

sort($steelspec);

for($i=0;$i<count($steelspec);$i++)
{
   $steelspectmp .= " { text : '" . $steelspec[$i] . "' , value: '"  . $steelspec[$i] . "' }";  	
  if( $i!= count($steelspec)-1) // 마지막에는 콤마를 없애준다.
   $steelspectmp .= "," ;	
}

// $tmp = "        { text: '홍길동', value: '홍길동' },
                // { text: '김영무', value: '김영무' },
                // { text: '이미래', value: '이미래' } ";

 ?>
 
<!DOCTYPE html>
<html lang="ko">
<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="item" content="">
<meta name="author" content="">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" >
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>	    
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script> 
<!-- toast Grid 사용을 위한 부분 -->
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>

<script src="http://8440.co.kr/common.js"></script>
	
<link rel="stylesheet" href="./css/style.css" type="text/css" />	
    
</head>

<title> 우성 원자재 </title>

<body>

<div class="container-fluid"> 

    <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
	<!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
          <h5 class="modal-title">알림</h5>
        </div>
        <div class="modal-body">		
		   <div id=alertmsg class="fs-1 mb-5 justify-content-center" >
		     결재가 진행중입니다. <br> 
		   <br> 
			 수정사항이 있으면 결재권자에게 말씀해 주세요.
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
        </div>
      </div>
      
    </div>
  </div>
			
			<div class="card align-middle" style="width:63rem; border-radius:20px;">		
			 
				<div class="card" style="padding:10px;margin:10px;">
					
					<h6 class="display-5 card-title text-center" style="color:#113366;"> 
						<?=$copystr?> 원자재 &nbsp;
						<button type="button" id=closeBtn class="btn btn-outline-secondary btn-sm" >적용후  <i class="bi bi-box-arrow-left"></i>  </button>					
						<button id="saveBtn" class="btn btn-outline-dark btn-sm" type="button"> <i class="bi bi-hdd-fill"></i>저장 </button>						
					  <? if($mode!='new' and $mode!='makesub') { ?>							 
                         <button id="showjpgBtn" class="btn btn-outline-dark btn-sm" type="button">거래명세표 PDF</button>						 
                         <button id="copyBtn" class="btn btn-outline-dark btn-sm" type="button" onclick="location.href='steelwrite_form.php?mode=copy&id=<?=$id?>&check=<?=$check?>';"> <i class="bi bi-clipboard-plus-fill"></i>복사</button>						 
					  <? } ?>
					    
					  <? if($mode!='new' and $mode!='makesub') { ?>	
						 <button type="button" id=delBtn class="btn btn-outline-danger btn-sm" ><i class="bi bi-trash3"></i>삭제</button>						
					  <? } ?>						
					  </h6>
				</div>	
				<div class="card-body text-center">
				<form id="board_form" name="board_form" method="post"  enctype="multipart/form-data" >
					<input type="hidden" id="mode" name="mode" value="<?=$mode?>">
					<input type="hidden" id="id" name="id" value="<?=$id?>" >			  													
					<input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" > 					
					<input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  > 					
					<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>"  > 										
					<input type="hidden" id="st_content" name="st_content" value="<?=$st_content?>"  > 

			  
				<span class="form-control">
				    <div class="input-group">
					   <div class="input-group-prepend">
							<span class="input-group-text">현장명</span>
						  </div>
						<input type="text" class="form-control"  type="text" name="workplacename" id="workplacename" value="<?=$workplacename?>" required >						
					</div>
				
				    <div class="input-group">
					   <div class="input-group-prepend">
							<span class="input-group-text">납품할 장소(주소)</span>
						  </div>
						<input type="text" class="form-control"  type="text" name="address" id="address" value="<?=$address?>" >						
					</div>
				
				    <div class="input-group input-group-sm">
					   <div class="input-group-prepend">
							<span class="input-group-text">접수일자</span>
						  </div>
					       <input type="date" class="form-control" name="regist_day" id="regist_day" value="<?=$regist_day?>"  > 					
						<div class="input-group-append">
							<span class="input-group-text">납품일자</span>
							</div>
					   	  <input type="date" class="form-control" name="doneday" id="doneday" value="<?=$doneday?>"  > 					
						  
						<div class="input-group-append">	
						<span class="input-group-text">청구일자</span>
						  </div>
					       <input type="date" class="form-control" name="demand" id="demand" value="<?=$demand?>"  > 											  						  
					</div>													
					
				    <div class="input-group input-group-sm">	
					   <div class="input-group-prepend">
							<span class="input-group-text">공급처</span>
						  </div>						  
							<input type="text"  class="form-control" name="supplier" id="supplier" value="<?=$supplier?>"  > 											
					</div>		
				    <div class="input-group input-group-sm">	
					   <div class="input-group-prepend">
							<span class="input-group-text">납품처</span>
						  </div>						  
							<input type="text"  class="form-control" name="secondord" id="secondord" value="<?=$secondord?>"  > 					
						<div class="input-group-append input-group-sm">
							<span class="input-group-text">납품처 담당</span>																					
						  </div>
							<input type="text"  class="form-control" name="secondordman" id="secondordman" size= 11 value="<?=$secondordman?>"  > 					
						<div class="input-group-append input-group-sm">
							<span class="input-group-text">&nbsp; 연락처</span>																					
						  </div>							
						<input type="text"  class="form-control" name="secondordmantel" id="secondordmantel" size=11 value="<?=$secondordmantel?>"  > 												
					</div>			
									
					
				    <div class="input-group">
                        <br>					
					</div>		

				<span class="form-control">
					<h6 class="display-5 text-left" > 
						세부 내역  &nbsp;&nbsp;
						<button  type="button" id="reloadBtn" class="btn btn-danger btn-sm"> <i class="bi bi-arrow-clockwise"></i> </button> &nbsp;						
						<button  type="button" id="registitemBtn" class="btn btn-outline-primary btn-sm"> 품목 관리</button> &nbsp;						
						<button  type="button" id="registspecBtn" class="btn btn-outline-primary btn-sm"> 규격 관리</button> &nbsp;&nbsp;&nbsp;&nbsp;						
						<button  type="button" id="deldataBtn" class="btn btn-outline-dark btn-sm"> 선택삭제</button> &nbsp;						
					</h6>	
					<div id="grid">  </div>						
				</span>

				<span class="form-control">
					<h6 class="text-center" > 
					총 금액합 &nbsp;
						<input  type="text" name="totalamount_str" id="totalamount_str" style="text-align:right;" size=7 value="<?=$totalamount_str?>"  > 	
					</h6>
				</span>	
				
				<span class="form-control">
					<h6 class="text-center" > 
						비고
						<textarea name="memo" id="memo" class="form-control" placeholder="비고 기록"><?=$memo?></textarea>
					</h6>
				</span>			

				<br> 	  
				<button id="saveBtn" class="btn btn-lg btn-dark btn-block" type="button">
				<? if((int)$id>0) print '저장';  else print '저장'; ?></button>
				<? if($user_name=='우성test' || $user_name=='김보곤') {  ?>				
				<button id="delBtn" class="btn btn-lg btn-danger btn-block" type="button">삭제</button>
				<button id="showjpgBtn" class="btn btn-lg btn-outline-primary btn-block" type="button">거래명세표 PDF</button>
				<? } ?>
				<button id="backBtn" class="btn btn-lg btn-outline-dark btn-block" type="button" onclick="self.close();">
				창 닫기기</button>
			  </form>			  
				</div>
       	 
		

			</div>
		</div>		
				
     </div>

</div>		 
			  
		
  </body>
</html>    
 
 
 <script >
	

$(document).ready(function(){
	
 // 원자재 종류 관리 등록수정삭제 
 $("#registitemBtn").click(function(){   	 
	href = './registitem.php';		
	popupCenter(href, '원자재 품목 관리', 600, 600);
 });		
 // 규격 등록 수정 삭제 
 $("#registspecBtn").click(function(){   	 
	href = './registspec.php';		
	popupCenter(href, '규격(spec) 관리', 600, 600);
 });
	
 	 	 
$("#closeModalBtn").click(function(){ 
    $('#myModal').modal('hide');
}); 	 
	
$("#closeBtn").click(function(){    // 저장하고 창닫기	
	opener.location.reload();    
	self.close();
});		

$("#reloadBtn").click(function(){    // reload    
	location.reload();		
});		

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
						  
	  
	// var count = "<? echo $count; ?>"; 
	
	var item = <?php echo json_encode($item);?> ;
	var spec = <?php echo json_encode($spec);?> ;
	var steelnum = <?php echo json_encode($steelnum);?> ;
	var unitprice = <?php echo json_encode($unitprice);?> ;
	var amount = <?php echo json_encode($amount);?> ;
	var tax = <?php echo json_encode($tax);?> ;
	var totalamount = <?php echo json_encode($totalamount);?> ;
	var comment = <?php echo json_encode($comment);?> ;  
   
   console.log('자료수 : ' + item.length);
   console.log(item);
   
	let row_count = item.length;				   
	
	const COL_COUNT = 8;
	
	const data = [];
	const columns = [];
	 
	for (let i = 0; i < row_count; i += 1) {
		const row = { name: i };
		row[`item`] = item[i] ;						 						
		row[`spec`] = spec[i] ;						 						
		row[`steelnum`] = steelnum[i] ;						 						
		row[`unitprice`] = unitprice[i] ;						 						
		row[`amount`] = amount[i] ;						 						
		row[`tax`] = tax[i] ;						 						
		row[`totalamount`] = totalamount[i] ;			
        row[`comment`] = comment[i] ;			
							
		data.push(row);
	  }	  
	  
	  	  // select 항목정보
	  var myItemArr = new Array();		
	  var myItem = new Object();
	  
	  myItem.text = "송강호";
	  myItem.value = "1";            
	  myItemArr.push(myItem);	 

	  // var jsonarr = new Object();
	  
	  // jsonarr.listItems = myItemArr;		  
	  
	  var jsontext = JSON.stringify(myItemArr);
	  // var reg = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi;
	  var reg = /[\[\]]/gi;
		
	  let replaced_str = jsontext.replace(reg,'');
	  
	  console.log(replaced_str); 
  
   const grid = new tui.Grid({
	  el: document.getElementById('grid'),
	  data: data,
	  bodyHeight: 220,	
	   columns: [ 				   
		{
		  header: '품목',
		  name: 'item',
		  width: 200,
		  // editor: 'text',  // 'null'이 안나오도록 텍스트 지정한다.
          formatter: 'listItemText',
          editor: {
            type: 'select',
            options: { listItems :  [ <? echo $steelitemtmp ?>  ]
					  }
            },			
			
		  align: 'left'		  
		},
		{
		  header: '규격',
		  name: 'spec',
		  width:150,						  
		//	editor: 'text', 
          formatter: 'listItemText',
          editor: {
            type: 'select',
            options: { listItems :  [ <? echo $steelspectmp ?>  ]
					  }
            },				
			align: 'center'
		  
		},			
		{
		  header: '수량',
		  name: 'steelnum',
		  width:40,						  
			editor: 'text',  
			align: 'right'
		  // sortingType: 'desc',
		  // sortable: true,          
		  // editingEvent :  'Click'		  
		},
		{
		  header: '단가',
		  name: 'unitprice',
		  width:60,						  
		  // sortingType: 'desc',
		  // sortable: true,
			editor: 'text', 		  
		  align: 'right'		  
		},						
		{
		  header: '공급가액',
		  name: 'amount',
		  width:90,
		  // sortingType: 'desc',
		  // sortable: true,
			editor: 'text', 		  
		  align: 'right'
		},				
		{
		  header: '세액',
		  name: 'tax',
		  width:60,
		  // sortingType: 'desc',
		  // sortable: true,
			editor: 'text', 		  
		  align: 'right'
		},			
		{
		  header: '총금액',
		  name: 'totalamount',
		  width:100,
		  // sortingType: 'desc',
		  // sortable: true,
		  editor: 'text', 		  
		  align: 'right'
		},
		{
		  header: '비고',
		  name: 'comment',
		  width:80,			
		  editor: 'text', 		  
		  align: 'left'
		}		
	  ],
	  
	columnOptions: {
		resizable: true
		  },
	  rowHeaders: ['rowNum','checkbox'],  // rowNum으로 나와야 숫자나옴
	  pageOptions: {
		useClient: false,
		perPage: 10
	  },
	  
	});	
	
// grid 꾸미기
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
				  background: '#EEFAEE'
				},
				hover: {
				  background: '#ccc'
				}
			  },
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

 // 초기로드 되면 계산한다.
  calculateit();
  
			// grid.on('beforeChange', ev => {
			  // console.log('before change:', ev);
			  // calculateit();
			// });
			grid.on('afterChange', ev => {
			  console.log('after change:', ev);
			  // grid.refresh();
			  calculateit();
			})
 
			 // console에 이벤트를 출력한다. 
			grid.on('editingFinish', ev => {
				ChangeData();  // 자료가 변경되면 다시 계산하는 루틴작성을 위한 연습
			console.log('check!', ev);					  
			});

			grid.on('mouseout', ev => {
			//  console.log('uncheck!', ev);
			  ChangeData();  // 자료가 변경되면 다시 계산하는 루틴작성을 위한 연습
			});

			grid.on('focusChange', ev => {
			 ChangeData();  // 그리드가 뭔가 변경되었을때 감지함
			 console.log('change onGridUpdated cell!', ev);
			 }); 	
		
								
// grid 변경된 내용을 php 넘기기 위해 input hidden에 넣는다.
function savegrid()   {
	
		let item  =  new Array();  
		let spec  =  new Array();  
		let steelnum  =  new Array();  
		let unitprice  =  new Array();  
		let amount  =  new Array();  				
		let tax  =  new Array(); 
		let comment  =  new Array(); 
		let tatalamount  =  new Array(); 
		let tmpstr ;  // 배열을 저장할 변수	  
		let arr = '';
		
		// console.log(grid.getRowCount());	//삭제시 숫자가 정상적으로 줄어든다.
		 const MAXcount = grid.getRowCount() + 20 ;  // 20개 데이터를 rowkey 영향으로 더 검색한다.						     		 
		 for(i=0; i < MAXcount; i++) {      // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/ 추가된 것을 반영못함.    
			if( grid.getValue(i, 'item' ) != null  ) 
				{				 								   
					item.push(grid.getValue(i, 'item' ));																 
					spec.push(grid.getValue(i, 'spec' ));																 
					steelnum.push(uncomma(grid.getValue(i, 'steelnum' )));																 
					unitprice.push(uncomma(grid.getValue(i, 'unitprice' )));																 
					amount.push(uncomma(grid.getValue(i, 'amount' )));																 
					tax.push(uncomma(grid.getValue(i, 'tax' )));																 
					totalamount.push(uncomma(grid.getValue(i, 'totalamount' )));
					
					// null값 체크
					if(grid.getValue(i, 'comment' ) === null )						
					     comment.push('');		
					   else
						   comment.push(grid.getValue(i, 'comment' ));		
						   
				} // end of else				
			 }
         // 배열에 자료를 넣기 전에 일단 초기화해준다.
		 console.log(item);
		//  grid.clear();  // 그리드 전체 삭제 clear
			 
		 // 배열형식을 구분자를 줘서 저장하는 루틴임 AAA | AAAA | BBBB | 이런식으로 저장한다.
		 tmpstr = '';
		 
		 for(i=0; i < item.length ; i++) {      // grid.value는 중간중간 데이터가 빠진다. rowkey가 삭제/ 추가된 것을 반영못함.   
             if( item[i] !== null ) 		 
				{					
					tmpstr += item[i];																 
					tmpstr += '|';
					tmpstr += spec[i];	
					tmpstr += '|';
					tmpstr += uncomma(steelnum[i] );
					tmpstr += '|';
					tmpstr += uncomma(unitprice[i] );
					tmpstr += '|';
					tmpstr += uncomma(amount[i] );	
					tmpstr += '|';
					tmpstr += uncomma(tax[i] );	
					tmpstr += '|';
					tmpstr += uncomma(totalamount[i] );	
					tmpstr += '|';
					tmpstr += comment[i]; 
				   if(item.length -1 != i)  // 마지막에 | 표시 없앰
						tmpstr += '|';
				}
			 }	
              
			// console.log(item.length);
			console.log(tmpstr);
			
			$('#st_content').val(tmpstr);					
	   }	
	   
	function calculateit() {
		
		let set_ea = 0;
		let set_unit = 0;
		let set_amount = 0;
		let set_tax = 0;
		let set_total = 0;

		let totalamount = 0;   // 견적총액 산출		
			   
        const MAXcount = grid.getRowCount() + 20 ;  // 20개 데이터를 rowkey 영향으로 더 검색한다.						     		 
	      for(i = 0; i < MAXcount ; i++) {
				set_ea = Number(uncomma(grid.getValue(i, 'steelnum')));
				set_unit = Number(uncomma(grid.getValue(i, 'unitprice')));
				set_amount = set_ea * set_unit ;
				set_tax = set_amount * 0.1 ;
				set_total = set_amount + set_tax ;
				  if(set_ea > 0) 
					{
						grid.setValue(i, 'steelnum', comma(set_ea));	 
						grid.setValue(i, 'unitprice', comma(set_unit)) ;	 
						grid.setValue(i, 'amount', comma(set_amount));	 
						grid.setValue(i, 'tax', comma(set_tax));	 
						grid.setValue(i, 'totalamount', comma(set_total));	 
						totalamount += set_total ;
					}
				}	 
					
			  $('#totalamount_str').val(totalamount.toLocaleString('en-US'));	
			  // $("#laserRunTime").val(laserRunTime);  // 레이져 가동시간 화면에 보여주기

		 }	 

function ChangeData() {
	calculateit();
	// grid.setValue(0, 'item' , '');  					  
}	 		 

$("#deldataBtn").click(function(){  	  
	var tmp = grid.getCheckedRowKeys();
		console.log(tmp);
		tmp.forEach(function(e){
				 grid.removeRow(e);			
		});	 					
	   calculateit();  	  
});	   			  	 
				 
function SelInsertData()  {    // 선택한 데이터 이후에 삽입
		var tmp = grid.getCheckedRowKeys();
		tmp.forEach(function(e){
		 appendRow(e+1);        // 함수를 만들어서 한줄삽입처리함.
		  console.log(e);
		});	
	  // grid.resetOriginData(data);			 // 데이터 update					
	  //  grid.resetData(data);			 // 데이터 update				 
	 }					 
		 
function appendRow(index=null) {
			var newRow = {
				eventId: '',
				localEvent: '',
				copyControl: ''
					};
			if (index== null) { // 행(row) 추가(끝에)
				grid.appendRow(newRow);
				} else { // 끝이 아닐때는 행(row) 삽입 실행
						var optionsOpt = {
								at: index,
								extendPrevRowSpan: false,
								focus: false
								};
						grid.appendRow(newRow , optionsOpt);
						}       				
	}				 
			

$("#closeModalBtn").click(function(){ 
	$('#myModal').modal('hide');
});

$("#closeBtn").click(function(){    // 저장하고 창닫기	
});	

// 견적서 PDF파일 만들기 버튼	 
$("#showjpgBtn").click(function(){    // jpg보여주기 그리고 pdf파일 생성	
	var id = '<?php echo $id; ?>' ; 
	var link ;
	link = 'showjpg_steel.php?id=' + id;
	window.open(link, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=30,left=20,width=1300,height=900");
});	
			
$("#saveBtn").click(function(){      // DATA 저장버튼 누름
   // 견적서 그리드 저장
    savegrid();
	var id = $("#id").val();  	
	   	   
// 결재상신이 아닌경우 수정안됨	 
if(Number(id)>0) 
		   $("#mode").val('modify');     
		  else
			  $("#mode").val('insert');     
		  
	// 자료 삽입/수정하는 모듈		  
	Fninsert();	
		
 }); 
 

// 삽입/수정하는 모듈 
function Fninsert() {	 

// 폼데이터 전송시 사용함 Get form         
var form = $('#board_form')[0];  	    
// Create an FormData object          
var data = new FormData(form); 

tmp='파일을 저장중입니다. 잠시만 기다려주세요.';		
$('#alertmsg').html(tmp); 			  
$('#myModal').modal('show'); 	

// console.log('form 데이터');		  
// console.log(data);		  

$.ajax({	
	enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의
	processData: false,    
	contentType: false,      
	cache: false,           
	timeout: 600000, 			
	url: "steel_insert.php",
	type: "post",		
	data: data,			
	// dataType:"text",  // text형태로 보냄
	success : function(data){
		console.log(data);
		// opener.location.reload();
		// window.close();	
		setTimeout(function() {
			$('#myModal').modal('hide');  
			}, 1000);		
		   
	},
	error : function( jqxhr , status , error ){
		console.log( jqxhr , status , error );
				} 			      		
   });		

	// else
	  // {
	  // tmp='보고자만 결재상신 상태가 아닌 경우 수정이 가능합니다.';		
	  // $('#alertmsg').html(tmp); 			  
		// $('#myModal').modal('show');  
	  // }
	  
} 
		 
$("#delBtn").click(function(){      // del
	var id = $("#id").val();    
	var user_name = $("#user_name").val();  

	if( (user_name=='우성test') || user_name=='김보곤') {	
         if(confirm("데이터를 삭제하면 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
	   $("#mode").val('delete');    	   
	   
		$.ajax({
			url: "steel_insert.php",
			type: "post",		
			data: $("#board_form").serialize(),			
			success : function( data ){
				console.log( data);
				opener.location.reload();
				window.close();					
			},
			  error : function( jqxhr , status , error ){
				   console.log( jqxhr , status , error );
			} 			      		
		   });	
		 }		   
		} // end of if
		else
		  {
	      tmp='삭제권한이 없습니다.';		
		  $('#alertmsg').html(tmp); 			  
			$('#myModal').modal('show');  
		  }
			
 }); // end of function
			 
 

}); // end of ready document
  
  
// null 체크 후 ''으로 리턴하기 
function isEmpty( val ){
if(val == null || typeof(val) == "undefined" || $.trim(val) == "") {
return true;
}
return false;
}
 
 
//아래 위의 함수와는 반대로 값이 들어있으면 true를 반환하고, 값이 들어있지 않으면 false를 반환,
function isNotEmpty( val ){
return !isEmpty(val);
}
   
  
  
</script>
