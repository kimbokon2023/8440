 <?php
  session_start(); 
  
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=5) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }   
  
$callback=$_REQUEST["callback"];  // 출고현황에서 체크번호
  
  if(isset($_REQUEST["mode"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $mode=$_REQUEST["mode"];
  else
   $mode="";

  if(isset($_REQUEST["which"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $which=$_REQUEST["which"];
  else
   $which="2";
  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";

   if(isset($_REQUEST["page"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $page=$_REQUEST["page"];
  else
   $page=1;   

  if(isset($_REQUEST["search"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $search=$_REQUEST["search"];
  else
   $search="";
  
  if(isset($_REQUEST["find"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $find=$_REQUEST["find"];
  else
   $find="";

  if(isset($_REQUEST["process"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $process=$_REQUEST["process"];
  else
   $process="전체";

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];

  require_once("../lib/mydb.php");
  $pdo = db_connect();
    
  
    try{
      $sql = "select * from mirae8440.shopitem where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{

              $num=$row["num"];
 			  $catagory=$row["catagory"];			  
 			  $dporder=$row["dporder"];			  
			  		  
			  $item=$row["item"];			  
			  $itemdes=$row["itemdes"];
			  $sale=$row["sale"];			  
			  $price=$row["price"];
			  $saleprice=$row["saleprice"];
			  $filename1=$row["filename1"];	 	
              $imgurl1="./img/" . $filename1;	

			  $youtube1=$row["youtube1"];	 	
			  $youtube2=$row["youtube2"];				  
	 					
			  
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
  

?>

   <!DOCTYPE HTML>
   <html>
   <head> 
   <title> 미래기업 금속작품실 아이템관리 조회화면 </title>
   <meta charset="utf-8">
	<!-- CSS only -->
   <link  rel="stylesheet" type="text/css" href="../css/common.css"> 	
   <link  rel="stylesheet" type="text/css" href="../css/steel.css"> 
   <link  rel="stylesheet" type="text/css" href="../css/radio.css"> 		
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">   

   </head>
 
   <body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>    
    <script src="http://8440.co.kr/common.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>	
   
   <div id="wrap">
	   <?php
    if($mode=="modify"){
  ?>
	<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)"  action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" > 
  <?php  } else {
  ?>
	<form  id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>"> 
  <?php
	}
  ?>	   
   <div id="header">
   <?php include "../lib/top_login2.php"; ?>
   </div>  
   <div id="menu">
   <?php include "../lib/top_menu2.php"; ?>
    </div>  
    <div id="content">
	
			      
       <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>"  >
       <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>"  >
	   <input type="hidden" id=filedelete name=filedelete >
	
    <br><br>
    <div id="work_col4"> 
	  <div id=top_title style="width:1000px; height:30px;"> <h4> &nbsp; &nbsp; 금속작품 조회 화면 	&nbsp;	&nbsp;	&nbsp;	&nbsp;
	  		     
			<button type="button" id="gotoList"  class="btn btn-secondary" onclick="location.href='list.php?mode=search&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>'" > 목록(List) </button>	
			<button type="button" id="updateBtn"  class="btn btn-success" onclick="location.href='write_form.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>'" > 수정 </button>	
			<button type="button" id="deleteBtn"  class="btn btn-danger" onclick="javascript:del('delete.php?num=<?=$num?>&page=<?=$page?>')" > 삭제 </button>	&nbsp;       	   												
			<button type="button" id="copydataBtn"  class="btn btn-primary" onclick="location.href='copy_data.php?mode=copy&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>'" >데이타 복사 </button>	&nbsp;       	   												
			<button type="button" id="writeBtn"  class="btn btn-dark" onclick="location.href='write_form.php'" > 글쓰기 </button>	&nbsp; 
	</h4> 
	</div>
  	
	      <div class="clear"></div>	
		  <br> <br>

		<div class="form-group">
	   <div class="row">
		 <div class="col-md-4 mb-3">
                <label for="catagorysel"> 아이템 카테고리 </label>
				 <input type="text" class="form-control" id="catagory"  name="catagory" value="<?=$catagory?>" disabled >                
			</div>
			 <div class="col-md-2 mb-3">
                <label for="dporder"> DP 순서 </label>
				 <input type="text" class="form-control" id="dporder"  name="dporder" value="<?=$dporder?>" disabled >                
			</div>
			 <div class="col-md-6 mb-3">
                <label for="item"> 아이템 이름 </label>
                <input type="text" class="form-control" id="item"  name="item" value="<?=$item?>"  disabled  >
			</div>
			

			</div>
			</div>
                     
            <div class="form-group">
                <label for="itemdes"> 아이템 상세설명 </label>
                <textarea class="form-control" id="itemdes" rows="4"  name="itemdes" disabled  ><?=$itemdes?> </textarea>				
            </div>
			
            <div class="form-group">
			   <div class="row">
				 <div class="col-md-4 mb-3">			
					<label for="sale"> Sale 여부 </label>
					<input class="form-control" id="sale"  name="sale" value="<?=$sale?>" disabled  >				
				</div>
				 <div class="col-md-4 mb-3">				
                <label for="price"> 최초 가격 </label>
                <input type="text" class="form-control" id="price"  name="price" value="<?=$price?>"  disabled >
				</div>			
				 <div class="col-md-4 mb-3">
                <label for="saleprice"> 할인적용 가격 </label>
                <input type="text" class="form-control" id="saleprice"  name="saleprice" value="<?=$saleprice?>" disabled  >				
            </div>
            </div>
            </div>
						
            <div class="form-group">
			   <div class="row">
				 <div class="col-md-6 mb-3">			
					<label for="youtube1"> Youtube1 </label>
					<input class="form-control" id="youtube1"  name="youtube1" value="<?=$youtube1?>" disabled  >				
				</div>
				 <div class="col-md-6 mb-3">				
                <label for="youtube2"> Youtube2 </label>
                <input type="text" class="form-control" id="youtube2"  name="youtube2" value="<?=$youtube2?>"  disabled >
				</div>							 
            </div>
            </div>
			
            <div class="form-group">
                <label for="mainbefore"> <span style="color:blue;">  이미지파일 선택  </span> </label>
                <input  id="mainbefore" type="file" class="form-control"  name="filename1" value="<?=$filename1?>"  disabled  >
				<img src="<?=$imgrl?>">
			<!--	<button type="button" id="regpicBtn"  class="btn btn-secondary" > 제품사진등록  onchange="this.value" </button>	&nbsp;	 -->
				<br>				  <br>      
				
	  <?php 
			if($filename1!=null) {				
			       
			  print '<img src="' . $filename1 . '" style="width:100px; height:100px;">';
			  print " <br> " ;  
			  print "기존 업로드 파일 있음 " . $filename1 ;  			  			  
			  print " <br> " ;  			  
			  print "<div class='imagediv' > ";
			  echo "<img class='before_work' src='". $imgurl  . "'>";			  			  
			  print "</div> <br> ";
			  
              
			  }
		?>
		   	<div id="displaypic" style="display:none;" >	
				<img id="imgtmp" src="<?=$imgurl?>">					
		   </div>
				
            </div>
		 
     <div class="clear"></div> 	   
	 
	 
	  </div> 
	   </div>  
		
	</form>
 </div>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
	</body>
 </html>

<script>

$(document).ready(function(){

$("#regpicBtn").click(function(){    // 사진등록
  const num = $("#num").val();
  window.open('reg_pic.php?num=' + num ,"사진등록","width=1200, height=700, top=0,left=0,scrollbars=no");	
});

$("#file").change(function(e) {
    //do whatever you want here
  	var fileData = <?php echo json_encode($file);?> ;	
	
  // $('#displaypic').show(); 	
  // $('#displaypic').load('pic_insert.php?file=' + fileData ); 	
  
  
});
 
	 	  
delPicFn = function(divID, delChoice) {
	console.log(divID, delChoice);

	$.ajax({
		url:'delpic.php?picname=' + delChoice ,
		type:'post',
		data: $("mainFrm").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const picname = data["picname"];		   
		   console.log(data);
			$("#pic" + divID).remove();  // 그림요소 삭제
			$("#delPic" + divID).remove();  // 그림요소 삭제

		   $("#pInput").val('');			
        });		

	};
	
});

function displayPicture() {        // 첨부파일 형식으로 사진 불러오기
	$('#displayPicture').show();
	params = $("#num").val();	
	console.log($("#num").val());
	$.ajax({
		url:'loadpic.php?num='+params ,
		type:'post',
		data: $("mainFrm").serialize(),
		dataType: 'json',
		}).done(function(data){						
		   const recnum = data["recnum"];		   
		   console.log(data);
		   $("#displayPicture").html('');
		   for(i=0;i<recnum;i++) {			   
			   $("#displayPicture").append("<img id=pic" + i + " src ='img/" + data["img_arr"][i] + "'> " );			   
         	   $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" +  data["img_arr"][i] + "')> 삭제 </button>&nbsp;");					   
		      }		   
			    $("#pInput").val('');			
    });	
}

function displayPictureLoad() {    // 이미 있는 파일 불러오기
	$('#displayPicture').show();
	var picNum = "<? echo $picNum; ?>"; 					
	var picData = <?php echo json_encode($picData);?> ;	
    for(i=0;i<picNum;i++) {
       $("#displayPicture").append("<img id=pic" + i + " src ='img/" + picData[i] + "'> " );			
	   $("#displayPicture").append("&nbsp;<button type='button' class='btn btn-secondary' id='delPic" + i + "' onclick=delPicFn('" + i + "','" + picData[i] + "')> 삭제 </button>&nbsp;");		
	   
	  }		   
		$("#pInput").val('');	
}
	
	
function delPic(delChoice)
{
if(delChoice=='before')
    $("#filedelete").val('before');
if(delChoice=='after')
    $("#filedelete").val('after');
   
document.getElementById('board_form').submit();	

}
	
function del(href) 
     {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
           document.location.href = href;
          }
     }	

</script>