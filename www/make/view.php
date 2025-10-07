<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");  	
  
 if(!isset($_SESSION["level"]) || $level>=5) {
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");
         exit;
   }   
   
 // 첫 화면 표시 문구
 $title_message = '도장 발주서 보기';
 
?>   
   
<?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

<title> <?=$title_message?> </title> 
 
<body>
  
<?php 

include '_request.php';

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
?>


<script>   // 화면을 시간 지연 후 나타내 주기
function callit() {
setTimeout(function(){
 save_check();  //your code here
}, 500);
}
</script>	

<form  id="board_form" name="board_form" method="post" > 
<div class="container-fluid ">  
<div class="card mt-2 mb-4">  
<div class="card-body">  
	
	<div class="card mt-2 mb-4">  
<div class="card-body">  
<div class="d-flex justify-content-center mt-2 mb-3">   
     <h5> <?=$title_message?>  &nbsp; &nbsp; &nbsp; &nbsp;  발주번호:&nbsp; <?=$num?> &nbsp; &nbsp; </h5>
</div>	 
	
<div class="d-flex justify-content-center mt-2">   	      
	<span class="text-dark fs-6 ">  발주일   </span>&nbsp;
	 <input disabled type="date" id="orderdate" name="orderdate" value="<?=$orderdate?>"  > &nbsp; &nbsp;
	  <span class="text-dark fs-6">  접수일   </span>&nbsp;
	  
	  <input disabled  type="date" id="indate" name="indate" value="<?=$indate?>" >&nbsp; &nbsp;
	 <span class="text-dark fs-6">  발주처   </span>&nbsp;
	 <?php
	   if($company=="")
		    $company="유일기업";
		?>
	  <input disabled  type="text" id="company" name="company" value="<?=$company?>" size="20" placeholder="발주처" >	 
	</div>	
<div class="d-flex justify-content-center mt-3 mb-2">   	      	  
	 <span  class="badge bg-danger fs-6"> 콤마(,)를 사용하면 자료가 정확히 나오지 않습니다. 콤마(,)는 절대 사용하지 마세요!  </span>
</div>		
	
<div class="d-flex justify-content-start mt-3">   
	<button type="button"   class="btn btn-dark btn-sm me-1" onclick="self.close();" >  &times; 닫기 </button>	
	<button type="button"  class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&team_check=<?=$team_check?>&plan_output_check=<?=$plan_output_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=1&scale=<?=$scale?>';" >  <i class="bi bi-pencil-square"></i>  수정  </button>	
	<button type="button"  class="btn btn-dark btn-sm me-1"    onclick="location.href='write_form.php';" > <i class="bi bi-pencil"></i>  신규 </button>
	<button type="button"  class="btn btn-primary btn-sm me-1" onclick="location.href='write_form.php?mode=copy&num=<?=$num?>&search=<?=$search?>';" ><ion-icon name="copy-outline"></ion-icon> 데이터 복사 </button>
	<button type="button"  class="btn btn-success btn-sm me-1" onclick="window.open('savefile.php?num=<?=$num?>&upnum=<?=$upnum?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&sort=<?=$sort?>&m2=<?=$m2?>','인쇄하기','left=100,top=100, scrollbars=yes, toolbars=no,width=1500,height=700');" > <i class="bi bi-printer"></i>발주서 인쇄 </button>	
	<button type="button"  class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php?num=<?=$num?>&page=<?=$page?>&check=<?=$check?>&scale=<?=$scale?>')"> <i class="bi bi-trash"></i>  삭제  </button>
</div>	
</div>		
</div>		
		

<div class="d-flex justify-content-center">   	      	 	 
	<div id="grid" style="width:1300px;"> </div>	 
</div> 

		<input type="hidden" id="num" name="num" value="<?=$num?>" > 		
		<input type="hidden" id="text" name="text" value="<?=$text?>"  > 		
		<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>"  > 	
		<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>"  > 	
		<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>"  > 					
		<input type="hidden" id="year" name="year" value="<?=$year?>"  > 	
		<input type="hidden" id="check" name="check" value="<?=$check?>"  > 			
		<input type="hidden" id="stable" name="stable" value="<?=$stable?>"  > 	
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 
		
	
  	
	  </div> 
	</div>   <!-- php 실행을 위한 빈자리 -->   	 
   </div> 
</form>       	      

<script>

$(document).ready(function(){
	
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
 arr=text.split('|');
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
			}
		data.push(row); 
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
		  align: 'center'
		},			
		{
		  header: '현장명',
		  name: 'col2',
		  width:400,	 		
		  align: 'center'
		},
		{
		  header: '품목',
		  name: 'col3',
		  width:200,	 		
		  align: 'center'
		},
		{
		  header: '수량',
		  name: 'col4',
		  width:70,	 		
		  align: 'center'
		},
		{
		  header: '단위',
		  name: 'col5',
		  width:70,	 		
		  align: 'center'
		},
		{
		  header: '단가',
		  name: 'col6',
		  width:100,	 		
		  align: 'center'
		},
		{
		  header: '색상',
		  name: 'col7',
		  width:300,	 		
		  align: 'center'
		}				
	  ],
	columnOptions: {
			resizable: true
		  },
	  rowHeaders: ['rowNum','checkbox'],
	  // pageOptions: {
		// useClient: false,
		// perPage: 20
	  // }	  
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
				  background: '#CFE2FF'
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
	
	grid.hideColumn('col1');
	
});



function inputNumberFormat(obj) { 
    obj.value = comma(uncomma(obj.value)); 
} 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}


function date_mask(formd, textid) {

/*
input onkeyup에서
formd == this.form.name
textid == this.name
*/

var form = eval("document."+formd);
var text = eval("form."+textid);

var textlength = text.value.length;

if (textlength == 4) {
text.value = text.value + "-";
} else if (textlength == 7) {
text.value = text.value + "-";
} else if (textlength > 9) {
//날짜 수동 입력 Validation 체크
var chk_date = checkdate(text);

if (chk_date == false) {
return;
}
}
}

function checkdate(input) {
   var validformat = /^\d{4}\-\d{2}\-\d{2}$/; //Basic check for format validity 
   var returnval = false;

   if (!validformat.test(input.value)) {
    alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
   } else { //Detailed check for valid date ranges 
    var yearfield = input.value.split("-")[0];
    var monthfield = input.value.split("-")[1];
    var dayfield = input.value.split("-")[2];
    var dayobj = new Date(yearfield, monthfield - 1, dayfield);
   }

   if ((dayobj.getMonth() + 1 != monthfield)
     || (dayobj.getDate() != dayfield)
     || (dayobj.getFullYear() != yearfield)) {
    alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
   } else {
    //alert ('Correct date'); 
    returnval = true;
   }
   if (returnval == false) {
    input.select();
   }
   return returnval;
  }
  
function input_Text(){
    document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value)*1.1));   // 콤마를 계산해 주고 다시 붙여주고
  var copyText = document.getElementById("test");   // 클립보드 복사 
  copyText.select();
  document.execCommand("Copy");
}  

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}
function Enter_Check(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_search();  // 실행할 이벤트
    }
}
function Enter_CheckTel(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_searchTel();  // 실행할 이벤트
    }
}

function exe_search()
{
      var postData = changeUri(document.getElementById("outworkplace").value);
      var sendData = $(":input:radio[name=root]:checked").val();

      $("#displaysearch").show();
//	 if(sendData=='주일')
//         $("#displaysearch").load("./search.php?mode=search&search=" + postData);
//	 if(sendData=='경동') 
         $("#displaysearch").load("./searchkd.php?mode=search&search=" + postData);	  
}
function exe_searchTel()
{
	  var postData =  changeUri(document.getElementById("receiver").value);
      $("#displaysearchworker").show();
      $("#displaysearchworker").load("./workerlist.php?mode=search&search=" + postData);
}

function del(href) {
        Swal.fire({
            title: '자료 삭제',
            text: "삭제는 신중! 정말 삭제하시겠습니까?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '삭제',
            cancelButtonText: '취소'
        }).then((result) => {
            if (result.isConfirmed) {
                                // 데이터베이스에서의 기록 삭제
                $.ajax({
                    url: 'delete.php',
                    type: 'post',
                    data: $("#board_form").serialize(),
                    dataType: 'json',
                }).done(function(data) {
                    // 삭제 후 처리
                    Toastify({
                        text: "파일 삭제완료 ",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        },
                    }).showToast();
                    setTimeout(function() {
                        if (window.opener && !window.opener.closed) {
                            window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
                            window.opener.location.reload(); // 부모 창 새로고침
                        }
                         window.close();
                    }, 1000);
                });
            }
        });   
}


function sortall(){
 save_check();	
 var sort;
 sort=$("#sort").val();		
 if(sort=='1')
     $("#sort").val("2");
   else
	   $("#sort").val("1");
 $("#modify").val("1");			// 소팅할 것
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    
}

function saveit() {
// $("#modify").val("1");			// 이전화면 유지
 save_check();
 document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    	
}


function savetext() {
 var trlength =$('#spreadsheet tbody tr').length;	
 var tmp;
 tmp="";
 
 for(i=0;i<trlength;i++)
     tmp = tmp + table1.getRowData(i) + '|';	 

alert(trlength);
alert(tmp);
 $("#text").val(tmp);			// 테이블의 텍스트를 히든형태로 폼에 기록하기
 
// document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    	
}


function load(href) 
     {
		   save_check();
           document.location.href = href;  
}	
function movetowin(href) 
     {
		   save_check();
           document.location.href = href;  
}	


function save_check() {   // 체크된 것 저장하기
// $("#modify").val("1");			// 이전화면 유지
 //document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과    	
 //var arr=new Array("<? echo $arr; ?>");   // php배열 가져오는 법

}


function send_alert() {   // 알림을 서버에 저장
 
var tmp; 				
		
	tmp="./save_alert.php";	
		
    $("#vacancy").load(tmp);      
	
    alertify.alert('발주서 전송 알림창', '<h1> 발주서가 전송되었습니다. </h1>'); 	

 }      
 
function click_it()
{	
// load 알림설정
var tmp; 				
var name='<?php echo $user_name; ?>' ;
 
	tmp="./load_alert.php";			
    $("#vacancy").load(tmp);     
	
 var alerts=$("#alerts").val();	 

}

// 인터벌은 지연시간 후 계속 실행하는 부분입니다.
	var timer;
	timer=setInterval(function(){
		click_it();
	},2000); 
 
 
 function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}


 </script> 

</body>
</html>
