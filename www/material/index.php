
<?php
   session_start();
   $level= $_SESSION["level"];
   $id_name= $_SESSION["name"];   
 if(!isset($_SESSION["level"]) || $level>7) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }  
   
    if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 
if($check==null)
		$check='1';

 ?>
 <!DOCTYPE HTML>
 <html>
 <head>
<meta charset="UTF-8">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>

<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>

<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>

<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>    
<link rel="stylesheet" href="../css/partner.css" type="text/css" />
 <title> 미래기업 원자재 관리시스템 </title>
 </head>
 <style> 
#panel, #flip {
  padding: 5px;
  text-align: center;
  background-color: #e5eecc;
  border: solid 1px #c3c3c3;
}

#panel {
  padding: 50px;
  display: none;
}
</style>
 <?php
  
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
  if(isset($_REQUEST["separate_date"]))   //출고일 접수일
	 $separate_date=$_REQUEST["separate_date"];	 
	 
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
  
 // $find="firstord";	    //검색할때 고정시킬 부분 저장 ex) 전체/공사담당/건설사 등
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
 
  $scale = 100;       // 한 페이지에 보여질 게시글 수
  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
	 
  if(isset($_REQUEST["keyword"]))
     $keyword=$_REQUEST["keyword"];
  else 
     $keyword="";    	 
 
 $cursort=$_REQUEST["cursort"];    // 현재 정렬모드 지정
   
  
  if($separate_date=="") $separate_date="1";
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

$fromdate="2019-01-01";



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
		  
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 $find=$_REQUEST["find"];
 
$process="전체";  // 기본 전체로 정한다.   
$comment_arr=array(); 
$indate_arr=array(); 
$num_arr=array(); 

	 $sql="select * from mirae8440.steelsource order by sortorder asc, item asc, spec asc "; 					

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
			  
			  $company=$row["take"];			    
			  if($row["take"]=='미래기업') $company='';	// 일반매입처리
			  if($row["take"]=='윤스틸') $company='';		// 일반매입처리	  
		      $steelsource_take[$counter]= $company ;   
			  $comment_arr[$i]= '';
			  			 
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

if($separate_date=="1") $SettingDate="outdate ";
    else
		 $SettingDate="indate ";

$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체
  
 // 전체합계(입고부분)를 산출하는 부분 
$sum_title=array(); 
$sum=array();

$sql="select * from mirae8440.steel " . $b; 	
 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
			  
			  $outworkplace=$row["outworkplace"];		
			  $indate=$row["indate"];			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  
			  if($company=='미래기업') $company='';		
			  if($company=='윤스틸') $company='';		
			  
			  $tmp=$item . $spec . $company;			  
	
        for($i=1;$i<=$rowNum;$i++) {			 			  

	         $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i] . $steelsource_take[$i] ;
			 // 코멘트 배열에 현장명 넣고 어디 현장인지 추적할때 사용한다.
			 
			 
			  if($which=='1' and $tmp==$sum_title[$i])
			  {
				    $sum[$i]=$sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
					if($comment_arr[$i]!='')
					        $comment_arr[$i]= $comment_arr[$i] . "/" . $indate . "  " . $outworkplace   . "    " .  $steelnum . "매" ;
						else
							$comment_arr[$i]= $indate . "  " . $outworkplace   . "    " .  $steelnum  . "매";
			  }
     // $sum[$i]=(float)-1;				
		           }
	
			  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


 // 전체합계(출고부분)를 처리하는 부분 

$sql="select * from mirae8440.steel " . $b; 	 
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
			  
			  if($company=='미래기업') $company='';			  
			  if($company=='윤스틸') $company='';			  
			  
			  $tmp=$item . $spec . $company;
	
        for($i=1;$i<=$rowNum;$i++) {			 			  
 			  
	        $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i] . $steelsource_take[$i] ;
			  if($which=='2' and $tmp==$sum_title[$i])  // 출고 숫자 더해주기 합계표	
				    $sum[$i]=$sum[$i] - (int)$steelnum;			
		           }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  



  if($mode=="") {
							 $sql="select * from mirae8440.steel " . $a; 					
	                         $sqlcon = "select * from mirae8440.steel " . $b;   // 전체 레코드수를 파악하기 위함.					
                }		
				         
   
$nowday=date("Y-m-d");   // 현재일자 변수지정   


	 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
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
			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
   
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp2;     // 전체 글수	  		
         					 
     $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 
   //   print "$page&nbsp;$total_page&nbsp;$current_page&nbsp;$search&nbsp;$mode";
		
		if($regist_state==null)
			 $regist_state="1";
		 
			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$outdate) {
                            $date_font="red";
						}
												
								$font="black";
								
							  
 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
}	
			 
			?>
			
<body>
     <div  class="container-fluid">
	 <br>
	 <br>
<div id="top-menu">
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
			<div class="row">
           <div class="col-6"> 
		         <h3 class="display-5 font-center text-left"> 
	<?=$_SESSION["name"]?> | 
		<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
		
<?php
	 }
?>
</h3>
</div> </div> 
<br>

<button  type="button" class="btn btn-success btn-lg btn-lg " onclick="location.href='../steel/list.php';"> PC화면으로 이동  </button>&nbsp;&nbsp;&nbsp;	
<button  type="button" class="btn btn-primary btn-lg btn-lg " onclick="location.href='./notinputlist.php?check=2';"> 미입고 자재 List  </button>&nbsp;&nbsp;&nbsp;	
 
<form id="board_form" name="board_form" method="get" action="index.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>">  
<br>
	  <H1 class="display-3 font-center text-center">  미래기업 원자재 List  </H1> <br>
	  <H3 class="display-4 font-center text-center text-danger">  (특수소재는 현장에 있는 실제 철판 수량 확인 후 작업하세요.)  </H3> <br>
	<div class="row">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<button type="button"  class="btn btn-dark btn-lg " onclick="location.href='index.php?keyword='"> 전체   </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button"  class="btn btn-danger btn-lg " onclick="location.href='index.php?keyword=CR'"> CR </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button type="button"  class="btn btn-warning btn-lg " onclick="location.href='index.php?keyword=PO'"> PO </button>  &nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-info btn-lg " onclick="location.href='index.php?keyword=EGI'"> EGI   </button>&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-success btn-lg btn-lg " onclick="location.href='index.php?keyword=304 HL'"> 304 HL  </button>&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-secondary btn-lg " onclick="location.href='index.php?keyword=304 MR'">  304 MR </button> &nbsp;&nbsp;&nbsp;&nbsp;
<button  type="button" class="btn btn-primary btn-lg " onclick="location.href='index.php?keyword=ETC'">  특수소재   </button> &nbsp;&nbsp;&nbsp;&nbsp;
		</div>	
	
<br>			  

	<div class="row">
        <div class="col-1">
      <h2 class="display-5 font-center text-center"> 번호 </h2>
        </div>
        <div class="col-3">
      <h2 class="display-5 font-center text-center"> 종류 </h2>
        </div>
        <div class="col-3">
      <h2 class="display-5 font-center text-center"> SPEC(TxWxH) </h2>
        </div>			
        <div class="col-1">
      <h2 class="display-5 font-center text-center"> 수량</h2>
        </div>			
        <div class="col-2">
      <h2 class="display-5 font-center text-center"> 업체</h2>
        </div>			
        <div class="col-2">
      <h2 class="display-5 font-center text-center"> 비고 </h2>
        </div>					
</div>		
<br>


<?php			
         $real_count = 0;
	     for($i=1;$i<=$rowNum;$i++)
		       {
			  $item_sum=0;
              $number=$i;
			  $item=$steelsource_item[$i];
			  $spec=$steelsource_spec[$i];
			  $take=$steelsource_take[$i];
			  $item_sum=$sum[$i];
         if($item_sum>0) {			 
         $real_count++;           
    // print substr($item,0,6);
    if(($keyword=='' or (substr(trim($item),0,6) == $keyword)) or (($keyword=='ETC') and (substr(trim($item),0,6)!='EGI') and (substr(trim($item),0,6)!='CR') and (substr(trim($item),0,6)!='PO') and (substr(trim($item),0,6)!='304 HL') and (substr(trim($item),0,6)!='304 MR')))
	           {
			?>	
	<div class="row">
	
				<div class="col-1"> 
				<h2 class="display-5 font-center text-center" > <?=$real_count?> <h2> </div>	  				 
			        <div class="col-3">
				  <h2 class="display-5 font-center text-center"  > <?=$item?> </h2> </div>
			        <div class="col-3">				  
				  <h2 class="display-5 font-center text-center">  <?=$spec?>  </h2> </div>
			        <div class="col-1">				  				  
				  <h2 class="display-5 font-center text-center">  <?=$item_sum?>   </h2> </div>				
			        <div class="col-2">				  				  
				  <h2 class="display-5 font-center text-center">  <?=$take?>   </h2> </div>				  
			        <div class="col-2">				  		
				<?php
				 if($keyword=='ETC')
					  $etc = $comment_arr[$i];
				  else
						  $etc = '';    
				  
				?>  
				  <h2 class="display-5 font-center text-center"> 
                  <a href="#" onclick="window.open('viewinput.php?etc=<?=$etc?>','철판 입고현장 보기','left=10,top=10, scrollbars=yes, toolbars=no,width=1400,height=700');" border="0" > 
				  <?=iconv_substr($etc,0,8,"utf-8")?>&nbsp;</a>
				     </h2> </div>				  				  

        </div> 
			   <?php			}			   			   
			        
							}
						}
  ?>		
  
			
			
			<br>
			<br>

			<div class="row">
		         <h4 class="display-4 text-left"  > 		   
				 <div id="flip">&nbsp;&nbsp; 오늘도 수고 많았습니다.</div>
<div id="panel"> 고생한 당신이 오늘의 주인공입니다. </div>
				
               			  </h4>
</div>			
		
  </div>
		<br> <br>

		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 				
		<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 				
	
		<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 				
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
		
	                
		<?php
			?>
        <div id="list_search4"></div>

        <div id="list_search5"></div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <div id="list_search11">		
		    
      </div> <!-- end of list_search11  -->
<div id="list_search12">			  	
	  
      </div> <!-- end of list_search12  -->
	<div class="row">
        <div class="col-2">
      <h5 class="display-5 font-center text-center"> 입출고일 </h5>
        </div>
        <div class="col-1">
      <h5 class="display-5 font-center text-center">구분 </h5>
        </div>
        <div class="col-3">
      <h5 class="display-5 font-center text-center"> 현장명 </h5>
        </div>
        <div class="col-2">
      <h5 class="display-5 font-center text-center"> 철판종류</h5>
        </div>				
        <div class="col-2">
      <h5 class="display-5 font-center text-center"> 규격</h5>
        </div>						
        <div class="col-1">
      <h5 class="display-5 font-center text-center"> 수량 </h5>	  
        </div>								

</div>		
                <br><br>
    <?php  

		 if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
	    
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
	
	 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
	 }	  
			 ?>			 
	<div class="row">

        <div class="col-2"> 
			 <h5 class="display-5 font-center text-center" style="color:<?=$date_font?>;"> 
				 <?=iconv_substr($outdate,0,10, "utf-8")?> <h5> </div>	  
				 <?php						       //    접수일이 당일이면 깜빡이는 효과부여								
	     if($which=='1')
		       {
               $tmp_word="입고";
			   $font_state="blue";
			   }
               else
			   {
	               $tmp_word="출고";
			       $font_state="red";				   
			   }								
				?>						 
			        <div class="col-1">
				  <h5 class="display-5 font-center text-center" style="color:<?=$font_state?>;" > <?=$tmp_word?> </h5> </div>
			        <div class="col-3">				  
				  <h5 class="display-5 font-center text-center">  <?=iconv_substr($outworkplace,0,20,"utf-8")?>  </h5> </div>
			        <div class="col-2">				  				  
				  <h5 class="display-5 font-center text-center">  <?=iconv_substr($item,0,20,"utf-8")?>   </h5> </div>
			        <div class="col-2">				  				  
				  <h5 class="display-5 font-center text-center">  <?=iconv_substr($spec,0,15,"utf-8")?>   </h5> </div>				  
			        <div class="col-1">				  				  
  				<h5 class="display-5 font-center text-center">  <?=iconv_substr($steelnum,0,3,"utf-8")?>   </h5> </div>				  
			  </div>
        </div>        
      </div>	 				  
</a>
            <div class="clear" > </div>
			<?php
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  

 ?>
 <br>
 <br>

	</form>	
         </div> <!-- end of  container -->     
  
<script>
function check_level()
			  {
				window.open("check_level.php?nick="+document.member_form.nick.value,"NICKcheck", "left=200,top=200,width=300,height=100, scrollbars=no, resizable=yes");
			  }
$(document).ready(function(){
    $("#without").change(function(){
        if($("#without").is(":checked")){
            $('#check').val('1');
            $('#search').val('');			 // search 입력란 비우기			
            $('#board_form').submit();	
        }else{
            $('#check').val('');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();						
        }
    });
    $("#outputlist").change(function(){
        if($("#outputlist").is(":checked")){
            $('#output_check').val('1');
           //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#output_check').val('');
          //   $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });	
	
    $("#plan_outputlist").change(function(){
        if($("#plan_outputlist").is(":checked")){
            $('#plan_output_check').val('1');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#plan_output_check').val('');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });	
	
    $("#team").change(function(){
        if($("#team").is(":checked")){
            $('#team_check').val('1');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#team_check').val('');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });		
    $("#notmeasure").change(function(){        // 미실측리스트 클릭시 동작	
        if($("#notmeasure").is(":checked")){
            $('#measure_check').val('1');		
            $('#board_form').submit();
			
        }else{
            $('#measure_check').val('');			
            $('#board_form').submit();			
        }
    });		
	/*
  $('#showall').click(function(){
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();		  
    });		
	*/

	  
});		

</script>

  </body>
  
<script> 

  	

function blinker() {
	$('.blinking').fadeOut(700);
	$('.blinking').fadeIn(700);
}
setInterval(blinker, 1500);


/* $(document).ready(function() { 
	$("input:radio[name=separate_date]").click(function() { 
	process_list(); 
	}) 
); */

  $(function() {
     $( "#id_of_the_component" ).datepicker({ dateFormat: 'yy-mm-dd'}); 
});  
$(function () {
            $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#up_fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#up_todate").datepicker({ dateFormat: 'yy-mm-dd'});			
			
});
 
 function up_pre_year(){   // 윗쪽 전년도 추출
document.getElementById('search').value=null; 
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

today = mm+'/'+dd+'/'+yyyy;
yyyy=yyyy-1;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

document.getElementById("up_fromdate").value = frompreyear;
document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
}  
 
function pre_year(){   // 전년도 추출
document.getElementById('search').value=null; 
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

today = mm+'/'+dd+'/'+yyyy;
yyyy=yyyy-1;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
}  

function up_pre_month(){    // 윗쪽 전월
	document.getElementById('search').value=null; 
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

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 
 
function pre_month(){    // 전월
	document.getElementById('search').value=null; 
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

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function up_this_year(){   // 윗쪽 당해년도
document.getElementById('search').value=null; 
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

today = mm+'/'+dd+'/'+yyyy;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
fromdate1=frompreyear;
todate1=topreyear;
document.getElementById('view_table').value="search"; 
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 


function this_year(){   // 아래쪽 당해년도
document.getElementById('search').value=null; 
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

today = mm+'/'+dd+'/'+yyyy;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
fromdate1=frompreyear;
todate1=topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function up_this_month(){   // 윗쪽 당해월
document.getElementById('search').value=null; 
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
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 


function this_month(){   // 당해월
document.getElementById('search').value=null; 
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
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function From_tomorrow(){   // 익일 이후
var today = new Date();
var dd = today.getDate()+1;  // 하루를 더해준다. 익일
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 
frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-12-31';	
    document.getElementById("fromdate").value = frompreyear;   
    document.getElementById("todate").value = topreyear;       
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 



function Fromthis_today(){   // 금일이후
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function up_this_today(){   // 윗쪽 날짜 입력란 금일
document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-'+mm+'-'+dd;

    document.getElementById("up_fromdate").value = frompreyear;
    document.getElementById("up_todate").value = topreyear;
document.getElementById('view_table').value="search"; 	
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function this_today(){   // 금일
document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-'+mm+'-'+dd;

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function this_tomorrow(){   // 익일

document.getElementById('search').value=null; 
var today = new Date();
var dd = today.getDate()+1;
var mm = today.getMonth()+1; //January is 0! 항상 1을 더해야 해당월을 구한다
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-'+dd;
topreyear = yyyy+'-'+mm+'-'+dd;

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
	
document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 

function process_list(){   // 접수일 출고일 라디오버튼 클릭시

document.getElementById('search').value=null; 

document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 

function exe_view_table(){   // 출고현황 검색을 클릭시 실행

document.getElementById('view_table').value="search"; 

document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 

function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}  


$(document).ready(function(){
  $("#flip").click(function(){
    $("#panel").slideToggle();
  });
});

$(document).ready(function(){
  $("#panel").click(function(){
    $("#panel").slideUp("slow");
  });
});

</script>
 

  </body>
  
  </html>