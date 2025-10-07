<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/session.php');	
  
// 첫 화면 표시 문구
$title_message = '도장 발주';
  
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=5) {         
		 sleep(1);
         header ("Location:https://8440.co.kr/login/logout.php");
         exit;
   }
?>
   
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?> 
  
<title>  <?=$title_message?>  </title> 
 
 </head> 
  	 
<body>
   
<?php   

include '_request.php';

if ($mode=="modify" || $mode=="copy"){
	
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
  }

if ($mode=="not"){    // 수정모드가 아닐때 신규 자료일때는 변수 초기화 한다.
          
			  $orderdate=date("Y-m-d");
			  $indate=date("Y-m-d");
			  $company=null;
			  $text=null;  
  } 
  
$company_arr = array();

array_push($company_arr,"");
array_push($company_arr,"진성케미칼");
array_push($company_arr,"유일기업");
array_push($company_arr,"은성산업");
array_push($company_arr,"한산엘테크");
$company_count=count($company_arr);

if($company=='')
	$company = "유일기업";
  
  
// print $mode;  
// print $num;  
?>
     

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

<div class="container-fluid ">  
<div class="card mt-2 mb-4">  
<div class="card-body">  
	
	<div class="card mt-2 mb-4">  
<div class="card-body">  
<div class="d-flex justify-content-center mt-2 mb-3">   
     <h5> <?=$title_message?>  &nbsp; &nbsp; &nbsp; &nbsp;  발주번호:&nbsp; <?=$num?> &nbsp; &nbsp; </h5>
</div>	 
	
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
<div class="d-flex justify-content-center mt-3 mb-2">   	      	  
	 <span  class="badge bg-danger fs-6"> 콤마(,)를 사용하면 자료가 정확히 나오지 않습니다. 콤마(,)는 절대 사용하지 마세요!  </span>
</div>		
<div class="d-flex justify-content-center mt-2 mb-2">   	      	  
	<span class="fs-6" id="addressDisplay">    </span>
</div>			
<div class="d-flex justify-content-start mt-3">   
	<button type="button"   class="btn btn-dark btn-sm me-1" onclick="self.close();" >  &times; 닫기 </button>	
	<button type="button"  class="btn btn-dark btn-sm" onclick="javascript:savetext('insert.php?num=<?=$num?>&page=<?=$page?>&text=<?=$text?>&scale=<?=$scale?>')" >  <i class="bi bi-floppy-fill"></i> 저장 </button>
	
	  <!-- 새로운 버튼 추가 -->
    <button type="button" class="btn btn-outline-primary btn-sm ms-5" id="insertRow">
        <ion-icon name="add-outline"></ion-icon> 선택 밑 행 삽입
    </button>
    <button type="button" class="btn btn-outline-danger btn-sm mx-4"  id="deleteRow" >
        <ion-icon name="trash-outline"></ion-icon> 선택 행 삭제
    </button>
	
</div>	
</div>		
</div>		
		
<div class="d-flex justify-content-center mt-2 mb-1">   	      	 	 
	<div id="grid"> </div>	 
</div> 
		<input type="hidden" id="textsave" name="textsave" value="<?=$text?>" size="100" > 		
		<input type="hidden" id="mode" name="mode" value="<?=$mode?>" size="100" > 				
        
     <br><br>		
   </div> <!--card-body-->
   </div> <!--card -->
   </div> <!--container-->
</form> 
    
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
	const COL_COUNT = 9;

		const data = [];
		const columns = [];	

	 var text='<?php echo $text; ?>' ;	
	 arr=text.split('|');
	if(mode!=='not') { 
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
	 
		 
let isComposing = false;

class CustomTextEditor {
  constructor(props) {
    const el = document.createElement('input');
    const { maxLength } = props.columnInfo.editor.options;

    el.type = 'text';
    el.maxLength = maxLength;
    el.value = String(props.value);

    this.el = el;
    this.props = props;
    this.el.addEventListener('keydown', this.onKeyDown.bind(this));
  }

  onKeyDown(event) {
    var { key } = event;
    var { rowKey, columnName } = this.props;

    if (key === 'ArrowUp' || key === 'ArrowDown' || key === 'ArrowLeft' || key === 'ArrowRight') {
      event.preventDefault();
      event.stopPropagation();

      this.props.grid.finishEditing();
      this.navigateNextCell(key, rowKey, columnName);
    }
  }

  navigateNextCell(key, rowKey, columnName) {
    var grid = this.props.grid;
    var { rowKey: newRowKey, columnName: newColumnName } = grid.getFocusedCell();

		switch (key) {
		  case 'ArrowUp':
			newRowKey = Math.max(newRowKey - 1, 0);
			break;
		  case 'ArrowDown':
			newRowKey = Math.min(newRowKey + 1, grid.getRowCount() - 1);
			break;
		  case 'ArrowLeft':
			newColumnName = grid.prevColumnName(columnName);
			break;
		  case 'ArrowRight':
			newColumnName = grid.nextColumnName(columnName);
			break;
		}

    if (newRowKey !== rowKey || newColumnName !== columnName) {
      grid.focus(newRowKey, newColumnName);
      grid.startEditing(newRowKey, newColumnName);
    }
  }

  getElement() {
    return this.el;
  }

  getValue() {
    return this.el.value;
  }

  mounted() {
    this.el.focus();
    this.el.setSelectionRange(this.el.value.length, this.el.value.length);
  }
}

let keySequence = '';

document.addEventListener('keydown', (ev) => {
  const { key, target } = ev;

  if (target.tagName === 'INPUT') {
    return;
  }

  const { rowKey, columnName } = grid.getFocusedCell();
  
  
  // 한글을 추적하기 위해 keySequence에 key를 추가합니다.
  keySequence += key;

  // 한글 완성형 정규식을 사용하여 한글 문자를 찾습니다.
  const hangulRegex = /[\uAC00-\uD7AF]/;
  const hasHangul = hangulRegex.test(keySequence);

  if (hasHangul) {
    const { rowKey, columnName } = grid.getFocusedCell();
    grid.setValue(rowKey, columnName, keySequence);
    grid.startEditing(rowKey, columnName, { editor: CustomTextEditor });
  } else {
    // 한글이 아닌 경우 다른 처리를 수행합니다.
	  if (!isComposing) {
    const isAlphaNumericKey = (key.length === 1) && (
      (key.charCodeAt(0) >= 48 && key.charCodeAt(0) <= 57) || // 숫자 0-9
      (key.charCodeAt(0) >= 65 && key.charCodeAt(0) <= 90) || // 대문자 A-Z
      (key.charCodeAt(0) >= 97 && key.charCodeAt(0) <= 122) // 소문자 a-z
    );

    if (isAlphaNumericKey) {
      grid.setValue(rowKey, columnName, key);
      grid.startEditing(rowKey, columnName, { editor: CustomTextEditor });
    }
  }	
	
	
  }
  
  // 키 시퀀스를 리셋합니다.
  setTimeout(() => {
    keySequence = '';
  }, 200);
  
});
		
		
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
			   rowHeaders: ['rowNum','checkbox'],	
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
				 console.log(tmp);
				 $("#board_form").submit();  // 페이지의 첫번째 폼을 서밋한다.				 
			}			
	  
	  grid.hideColumn('col1');
	    
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
						 if(set_num!='' && EAstring ==='') 
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
					
$("#insertRow").click(function () {
    const checkedRows = grid.getCheckedRows(); // 체크된 행 가져오기

    if (checkedRows.length === 0) {
        alert("먼저 삽입할 위치의 행을 선택하세요.");
        return;
    }

    const firstCheckedRowKey = checkedRows[0].rowKey; // 첫 번째 체크된 행의 rowKey
    let rowIndex = firstCheckedRowKey + 1; // 현재 행 아래 삽입 위치

    console.log("삽입 위치:", rowIndex);

    // ✅ 기존 데이터를 한 칸씩 뒤로 이동 (마지막 행부터 뒤로 밀어야 데이터 손실 없음)
    for (let i = grid.getRowCount() - 1; i >= rowIndex; i--) {
        let rowData = grid.getRow(i);
        grid.setValue(i + 1, 'col1', rowData.col1);
        grid.setValue(i + 1, 'col2', rowData.col2);
        grid.setValue(i + 1, 'col3', rowData.col3);
        grid.setValue(i + 1, 'col4', rowData.col4);
        grid.setValue(i + 1, 'col5', rowData.col5);
        grid.setValue(i + 1, 'col6', rowData.col6);
        grid.setValue(i + 1, 'col7', rowData.col7);
        grid.setValue(i + 1, 'col8', rowData.col8);
        grid.setValue(i + 1, 'col9', rowData.col9);
    }

    // ✅ 새로운 행을 삽입 위치에 추가
    const newRow = {
        col1: '',
        col2: '',
        col3: '',
        col4: '',
        col5: '',
        col6: '',
        col7: '',
        col8: '',
        col9: ''
    };
    grid.setValue(rowIndex, 'col1', newRow.col1);
    grid.setValue(rowIndex, 'col2', newRow.col2);
    grid.setValue(rowIndex, 'col3', newRow.col3);
    grid.setValue(rowIndex, 'col4', newRow.col4);
    grid.setValue(rowIndex, 'col5', newRow.col5);
    grid.setValue(rowIndex, 'col6', newRow.col6);
    grid.setValue(rowIndex, 'col7', newRow.col7);
    grid.setValue(rowIndex, 'col8', newRow.col8);
    grid.setValue(rowIndex, 'col9', newRow.col9);

    grid.focus(rowIndex, 'col1'); // 삽입된 행에 포커스
});

		
$("#deleteRow").click(function () {
    const checkedRows = grid.getCheckedRows(); // 체크된 행 가져오기

    if (checkedRows.length === 0) {
        alert("삭제할 행을 선택하세요.");
        return;
    }

    // 체크된 행의 rowKey를 정렬
    const checkedRowKeys = checkedRows.map(row => row.rowKey).sort((a, b) => a - b);

    checkedRowKeys.forEach(rowKey => {
        const rowCount = grid.getRowCount();

        // 선택된 행부터 마지막 행까지 한 칸씩 앞으로 덮어씀
        for (let i = rowKey; i < rowCount - 1; i++) {
            let nextRow = grid.getRow(i + 1);
            grid.setValue(i, 'col1', nextRow.col1);
            grid.setValue(i, 'col2', nextRow.col2);
            grid.setValue(i, 'col3', nextRow.col3);
            grid.setValue(i, 'col4', nextRow.col4);
            grid.setValue(i, 'col5', nextRow.col5);
            grid.setValue(i, 'col6', nextRow.col6);
            grid.setValue(i, 'col7', nextRow.col7);
            grid.setValue(i, 'col8', nextRow.col8);
            grid.setValue(i, 'col9', nextRow.col9);
        }

        // 마지막 행은 빈 값으로 초기화
        const lastIndex = grid.getRowCount() - 1;
        grid.setValue(lastIndex, 'col1', '');
        grid.setValue(lastIndex, 'col2', '');
        grid.setValue(lastIndex, 'col3', '');
        grid.setValue(lastIndex, 'col4', '');
        grid.setValue(lastIndex, 'col5', '');
        grid.setValue(lastIndex, 'col6', '');
        grid.setValue(lastIndex, 'col7', '');
        grid.setValue(lastIndex, 'col8', '');
        grid.setValue(lastIndex, 'col9', '');
    });

    // 포커스 제거
    grid.blur();
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

 $(document).ready(function() {
    $('#company').on('change', function() {
        var selectedCompany = $(this).val();
        var addressInfo = '';

        if (selectedCompany === '진성케미칼') {
            addressInfo = '(주)진성케미칼  /주소 경기도 김포시 양촌읍 삼도공단로 66-1(가동)  담당자 노하늘과장 010-3167-1154';
        } else {
            addressInfo = ''; // 선택이 '진성케미칼'이 아니면 비워두기
        }

        $('#addressDisplay').text(addressInfo);
    });

    // 페이지 로드 시 선택된 값 처리
    $('#company').trigger('change');
});

 </script> 
</body>	
 </html>
