<?php require_once(includePath('session_header.php')); 

if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
	sleep(1);
	header("Location:" . $WebSite . "login/login_form.php"); 
	exit;
}   

include getDocumentRoot() . '/load_header.php'; 
// 첫 화면 표시 문구
$title_message = '연구전담부서 운영비';   
 
if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
else
   $num="";

if(isset($_REQUEST["tablename"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $tablename=$_REQUEST["tablename"];
      
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

    try{
      $sql = "select * from  ".$DB."." . $tablename . "  where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
		 
			include '_row.php';			
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	 
?>
 
 <title> <?=$title_message ?>  </title> 
 </head>
   
<body>

<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)" > 

<div class="container">    
			      
       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
       <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
       <input type="hidden" id="page" name="page" value="<?=$page?>"  >
       <input type="hidden" id="num" name="num" value="<?=$num?>"  >
	   <input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >	   


<div class="card">    
<div class="card-header text-center">    
	  <span class="fs-4" > <?=$title_message ?>  </span >
</div>
<div class="card-body">   
<div class="d-flex mb-1 mt-3 justify-content-start">    		
	<button class="btn btn-dark btn-sm me-1" onclick="self.close();" > &times; 닫기 </button>
<?php
   if(isset($_SESSION["userid"]) &&  ( $user_name==='소현철' ||  $user_name==='소민지' ||  $user_name==='김보곤')   )
   {
  ?>	
	<button type="button"   class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?=$tablename?>&mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>'" >  <i class="bi bi-pencil-square"></i>  수정  </button>			
	<button type="button"   class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?=$tablename?>'" >  <i class="bi bi-pencil"></i>  신규 </button>			
	<button type="button"   class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php?tablename=<?=$tablename?>&num=<?=$num?>&page=<?=$page?>')" > <i class="bi bi-trash"></i>  삭제   </button>								
  <?php
   }
  ?> 	
	
</div>
  	
<div class="d-flex mb-1 mt-3 justify-content-center">   
   
<table class="table table-bordered" >
<tbody>
  <tr>
  
 <?php	 		  
	 	 $aryreg=array();
	 	 $aryitem=array();
		 if($which=='') $which='2';
	     switch ($which) {
					case   "1"             : $aryreg[0] = "checked" ; break;
					case   "2"             :$aryreg[1] =  "checked" ; break;
					default: break;
				}		
	   ?>
	<td colspan="4" class="text-center mt-3">	
    <h6>	
	   구분 :       <span class="text-primary"> 수입   </span>    	   
	   <input  type="radio" <?=$aryreg[0]?> name=which value="1">     
		   <span class="text-danger"> 지출   </span>     
		<input  type="radio" <?=$aryreg[1]?>  name=which value="2">  	 
		</h6>
	</td>
  </tr>

  <tr>
   <td class="text-center">
	 기록일   
	 </td>
	 <td>
	 <input type="date" id="proDate" name="proDate" class="form-control text-end" style="width:100px;"  value="<?=$proDate?>" size="14" >  
	 </td>
	 <td class="text-center">	 
	작성자  
	 </td>
	 <td>	
	 <input type="text" id="writer" name="writer" value="<?=$writer?>" class="form-control text-center" style="width:100px;"  >  
    	 </td>
	 </tr>	 
  <tr>
   <td class="text-center">
	품 목  	 
	 </td>
	 <td colspan="3">		  
	 <input type="text"  id="item" name="item" value="<?=$item?>" class="form-control" placeholder="품목"> 	 
    	 </td>
 </tr> 
   <tr>
   <td class="text-center">
	내 역  	 
	 </td>
	 <td colspan="3">		  
	 <input type="text"  id="memo" name="memo" value="<?=$memo?>" class="form-control" placeholder="내역"> 	 
    	 </td>
 </tr>	 
  <tr>
   <td class="text-center">
	금 액
	 </td>
	 <td colspan="3">		  
		<input type="text" name="amount" id="amount" value="<?=$amount?>"  onkeyup="inputNumberFormat(this)" class="form-control text-end" style="width:100px;" placeholder="금액" />	 	 </div>
	 </td>
	    	 
   </tr>	 
  <tr>
   <td class="text-center">
	비 고
	 </td>
	 <td colspan="3">		  
		<input type="text" name="comment" id="comment" value="<?=$comment?>"  class="form-control"  placeholder="비고" />	 	 </div>
	 </td>
	    	 
   </tr>
	</tbody>
</table>
 
</div> 
</div> 
</div> 
</form>	  

<script>

$(document).ready(function(){
		
   $("div *").find("input,textarea").prop("disabled",true);	
 });		 
	

function del(href) {    
    var user_name  = '<?php echo  $user_name ; ?>' ;
    var writer  = '<?php echo  $writer ; ?>' ;
    var admin  = '<?php echo  $admin ; ?>' ;
	if( user_name !== writer && admin !== '1' )
	{
        Swal.fire({
            title: '삭제불가',
            text: "작성자와 관리자만 삭제가능합니다.",
            icon: 'error',
            confirmButtonText: '확인'
        });
    } else {
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
				$.ajax({
					url:'delete.php',
					type:'post',
					data: $("#board_form").serialize(),
					dataType: 'json',
					}).done(function(data){		
						Toastify({
							text: "파일 삭제완료 ",
							duration: 2000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
						setTimeout(function(){
							if (window.opener && !window.opener.closed) {
								// window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
								window.opener.location.reload(); // 부모 창 새로고침
								window.close();
							}							
							
						}, 1000);
			
					  
					});
            }
        });
    }
}

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}


</script> 
	</body>
 </html>
