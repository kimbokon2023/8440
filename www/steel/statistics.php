<?php
 session_start();

 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
	          header("Location:http://8440.co.kr/login/login_form.php"); 
         exit;
   }
   
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21   
//header("Refresh:0");  // reload refresh   

 ?>
 
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/steel.css">
 <link rel="stylesheet" type="text/css" href="../css/jexcel.css"> 
 <script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>

<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
 <title> 미래기업 통합정보시스템 </title> 
 </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css" integrity="sha512-C7hOmCgGzihKXzyPU/z4nv97W0d9bv4ALuuEbSf6hm93myico9qa0hv4dODThvCsqQUmKmLcJmlpRmCaApr83g==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" integrity="sha512-hZf9Qhp3rlDJBvAKvmiG+goaaKRZA6LKUO35oK6EsM0/kjPK32Yw7URqrq3Q+Nvbbt8Usss+IekL7CRn83dYmw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w==" crossorigin="anonymous" />

 
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
 
$process="전체";  // 기본 전체로 정한다.   

	 $sql="select * from mirae8440.steelsource"; 					

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
			  
			 /* print  $steelsource_num[$counter];			  
 			 print  $steelsource_item[$counter];
 			 print $steelsource_spec[$counter];
		     print $steelsource_take[$counter];    */
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

if($separate_date=="1") $SettingDate="outdate ";
    else
		 $SettingDate="indate ";

$common="   where (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date') " ;
 
 // 전체합계(입고부분)를 산출하는 부분 
$sum_title=array(); 
$sum=array();

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

	          $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='1' and $tmp==$sum_title[$i])
				    $sum[$i]=$sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
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
			  
			  $tmp=$item . $spec;
	
        for($i=1;$i<=$rowNum;$i++) {
			 			  
 			  
	          $sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='2' and $tmp==$sum_title[$i])
				    $sum[$i]=$sum[$i] - (int)$steelnum;			
		           }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

  
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
							 $sql="select * from mirae8440.steel where (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  " . $a; 				
					
                }		
				         
   
$nowday=date("Y-m-d");   // 현재일자 변수지정   
	
$output_item_arr = array();	
$output_weight_arr = array();	
$input_arr = array();	
$temp_arr = array();	
$count=0;
   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $total_row=$stmh->rowCount();



			 
			?>
		 
<body >

 <div id="wrap">

   <div id="content">			 
  <form name="board_form" id="board_form"  method="post" action="statistics.php?mode=search&search=<?=$search?>&find=<?=$find?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>&display_sel=<?=$display_sel?>">  
  <div id="col2">    
  
       <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
	   
<div id=display_board class=background name=display_board > 
	
     <div class="clear"></div> 	 
   
<div id=list_board >    
	 <div id="title" style="width:300px" ><h2> 원자재 입출고 차트보기 </h2> </div>	 
      <div class="clear"></div>	 
 <!-- <div id="title2">
 <div id class="blink"  style="white-space:nowrap">  <font color=red> *****  AS 진행 현황 ***** </font> </div>
	  </div>  -->

        <div id="list_search">
        <div id="list_search1"> <br> ▷ 총 <?= $total_row ?> 개의 자료 파일이 있습니다.</div>
        <div id="list_search111"> 
			 <?php
	    if($separate_date=="1") {
			 ?>
			&nbsp; 입고일 <input type="radio" checked name="separate_date" value="1">
			&nbsp; 출고일 <input type="radio" name="separate_date" value="2">
			<?php
             		}    ?>	 
			 <?php
	    if($separate_date=="2") {
			 ?>
			&nbsp; 입고일 <input type="radio"  name="separate_date" value="1">
			&nbsp; 출고일 <input type="radio" checked name="separate_date" value="2">
			<?php
             		}    ?>	 		

       <input id="preyear" type='button' onclick='pre_year()' value='전년도'>
	   <input id ="three_month" type='button' onclick='three_month_ago()' value='M-3월'>	 	   
	   <input id ="prepremonth" type='button' onclick='prepre_month()' value='전전월'>	 	   
	   <input id ="premonth" type='button' onclick='pre_month()' value='전월'>	 
       <input type="text" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">부터	   
       <input type="text" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">까지
	   <input id ="thismonth" type='button' onclick='this_month()' value='당월'>
       <input id ="thisyear" type='button' onclick='this_year()' value='당해년도'>		 					
       </div>		
        <div id="list_search2"> <img src="../img/select_search.gif"></div>
        <div id="list_search3">
        <select name="find">
           <?php		  
	     	switch ($find) {
			 case 'all' : print "  
           <option value='all' selected >전체</option>
           <option value='outworkplace'>현장명</option>
           <option value='model'>모델명</option>
           <option value='item'>철판종류</option>
           <option value='comment'>발주처</option>
           <option value='company'>해당업체</option> "; break;
			 case 'outworkplace' : print "  
           <option value='all'  >전체</option>
           <option value='outworkplace' selected>현장명</option>
           <option value='model'>모델명</option>
           <option value='item'>철판종류</option>
           <option value='comment'>발주처</option>		   
           <option value='company'>해당업체</option> "; break;		   
			 case 'model' : print "  
           <option value='all'  >전체</option>
           <option value='outworkplace' >현장명</option>
           <option value='model'selected>모델명</option>
           <option value='item'>철판종류</option>
           <option value='comment'>발주처</option>		   
           <option value='company'>해당업체</option> "; break;	
			 case 'item' : print "  
           <option value='all'  >전체</option>
           <option value='outworkplace' >현장명</option>
           <option value='model'>모델명</option>
           <option value='item'selected>철판종류</option>
           <option value='comment'>발주처</option>		   
           <option value='company'>해당업체</option> "; break;			   
		   case 'comment' : print "  
           <option value='all'  >전체</option>
           <option value='outworkplace' >현장명</option>
           <option value='model'>모델명</option>
           <option value='item'selected>철판종류</option>
           <option value='comment' selected >발주처</option>		   
           <option value='company'>해당업체</option> "; break;			   
			 case 'company' : print "  
           <option value='all'  >전체</option>
           <option value='outworkplace' >현장명</option>
           <option value='model'>모델명</option>
           <option value='item'>철판종류</option>
           <option value='comment'>발주처</option>		   
           <option value='company'selected>해당업체</option> "; break;		   
		default : print "  
           <option value='all'  >전체</option>
           <option value='outworkplace' >현장명</option>
           <option value='model'>모델명</option>
           <option value='item'>철판종류</option>
           <option value='comment'>발주처</option>		   
           <option value='company'>해당업체</option> "; break;				   		   
			  } ?>			  
        </select>
				
		</div> <!-- end of list_search3 -->

        <div id="list_search4"><input type="text" name="search" id="search" value="<?=$search?>"> </div>
		 <div id="list_search5"><input type="image" src="../img/list_search_button.gif"></div> 
      </div> <!-- end of list_search -->
      <div class="clear"></div>
	 <?php
	    
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
			  
			//  print $temp_arr[0];
			  
			  $output_weight_arr[$count]=floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum)/1000000) ;
			  
							switch ($item) {
								case   "304 HL"     :   $output_item_arr[0] += $output_weight_arr[$count]; break;
								case   "304 MR"     :   $output_item_arr[1] += $output_weight_arr[$count]; break;	

								case   "PO"     :   $output_item_arr[3] += $output_weight_arr[$count]; break;	
								case   "EGI"     :   $output_item_arr[4] += $output_weight_arr[$count]; break;
								case   "CR"     :   $output_item_arr[5] += $output_weight_arr[$count]; break;									
								default:  $output_item_arr[2] += $output_weight_arr[$count];break;	
							}					  		  			  

              $count++;
?>

			<?php
			$start_num--;  
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
  print "<br>";
  print " <h2><span style='color:red' > 304 HL </span> " . number_format($output_item_arr[0]) . "KG, &nbsp;&nbsp;   ";
  print " <span style='color:blue' > 304 MR </span>" . number_format($output_item_arr[1]) . "KG, &nbsp;&nbsp;   " ; 
  print " <span style='color:orange' > 기타SUS </span>" . number_format($output_item_arr[2]) . "KG,  &nbsp;&nbsp;  " ; 
  print " <span style='color:green' > PO </span>" . number_format($output_item_arr[3]) . "KG, &nbsp;&nbsp;   " ; 
  print " <span style='color:purple' > EGI </span>" . number_format($output_item_arr[4]) . "KG, &nbsp;&nbsp;   " ; 
  print " <span style='color:brown' > CR </span>" . number_format($output_item_arr[5]) . "KG </h2> <br><br><br>" ; 

		switch ($display_sel) {
			case   "doughnut"     :   $chartchoice[0]='checked'; break;
			case   "pie"     :   $chartchoice[1]='checked'; break;
			case   "bar"     :   $chartchoice[2]='checked'; break;
			case   "line"     :   $chartchoice[3]='checked'; break;
			case   "radar"     :   $chartchoice[4]='checked'; break;
			case   "polarArea"     :   $chartchoice[5]='checked'; break;
		}
 ?>
   <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
   <input id="display_sel" name="display_sel" type='hidden' value='<?=$display_sel?>' >
   			&nbsp; 도넛 <input type="radio" <?=$chartchoice[0]?> name="chart_sel" value="doughnut">
			&nbsp; 파이 <input type="radio" <?=$chartchoice[1]?> name="chart_sel" value="pie">
			&nbsp; 바 <input type="radio" <?=$chartchoice[2]?> name="chart_sel" value="bar">
			&nbsp; 라인 <input type="radio" <?=$chartchoice[3]?> name="chart_sel" value="line">
			&nbsp; 레이더 <input type="radio" <?=$chartchoice[4]?> name="chart_sel" value="radar">
			&nbsp; Polar Area <input type="radio" <?=$chartchoice[5]?> name="chart_sel" value="polarArea"> 
			<br><br>
<canvas id="myChart" width="800" height="500"></canvas>

     </div>   

	</form>

	 </div>   
    </div> <!-- end of col2 -->
   </div> <!-- end of content -->
   </div> 	   
  </div> <!-- end of wrap -->
  


<script>
/* Checkbox change event */
$('input[name="chart_sel"]').change(function() {
    // 모든 radio를 순회한다.
    $('input[name="chart_sel"]').each(function() {
        var value = $(this).val();              // value
        var checked = $(this).prop('checked');  // jQuery 1.6 이상 (jQuery 1.6 미만에는 prop()가 없음, checked, selected, disabled는 꼭 prop()를 써야함)
        // var checked = $(this).attr('checked');   // jQuery 1.6 미만 (jQuery 1.6 이상에서는 checked, undefined로 return됨)
        // var checked = $(this).is('checked');
        var $label = $(this).next();
 
        if(checked)  {
           $("#display_sel").val(value);
	       document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 	
		}

    });
});

    var arr1 = <?php echo json_encode($output_item_arr);?> ;
	var ctx = document.getElementById('myChart');
    var chart_type = document.getElementById('display_sel').value;

	var myChart = new Chart(ctx, {
		type: chart_type,
		data: {
			labels: ['304 HL', '304 MR', '기타SUS', 'PO','EGI','CR'],
			datasets: [{
				label: '#주요 원자재 입/출고현황 단위(KG) ',
				data: [arr1[0], arr1[1], arr1[2],arr1[3], arr1[4], arr1[5] ],
				backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)'
				],
				borderColor: [
					'rgba(255, 99, 132, 1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
				],
				borderWidth: 1
			}]
		},
		options: {
			responsive: false,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			},
		}
	});
</script>
<script>

function blinker() {
	$('.blinking').fadeOut(500);
	$('.blinking').fadeIn(500);
}
setInterval(blinker, 1000);


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
// document.getElementById('search').value=null; 
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
// document.getElementById('search').value=null; 
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


function three_month_ago(){    // 석달전
			// document.getElementById('search').value=null; 
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();
			if(dd<10) {
				dd='0'+dd;
			} 

			mm=mm-3;  // 전전전월
			if(mm<-1) {
				mm='11';
			} 			
			if(mm<1) {
				mm='12';
			} 
			if(mm<10) {
				mm='0'+mm;
			} 
			if(mm>=12) {
				yyyy=yyyy-1;
			} 


			frompreyear = yyyy+'-' + mm+'-01';

			var tmp=0;
				  
			switch (Number(mm)) {
				
				case 1 :
				case 3 :
				case 5 :
				case 7 :
				case 8 :
				case 10 :
				case 12 :
				  tmp=31 ;
				  break;
				case 2 :   
				   tmp=28;
				   break;
				case 4 :
				case 6 :
				case 9 :
				case 11:
				   tmp=30;
				   break;
			}  	  

			topreyear = yyyy + '-' + mm + '-' + tmp ;

				document.getElementById("fromdate").value = frompreyear;
				document.getElementById("todate").value = topreyear;
				document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

function  prepre_month(){    // 전전월
			// document.getElementById('search').value=null; 
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();
			if(dd<10) {
				dd='0'+dd;
			} 

			mm=mm-2;  // 전전월
			if(mm<1) {
				mm='12';
			} 
			if(mm<10) {
				mm='0'+mm;
			} 
			if(mm>=12) {
				yyyy=yyyy-1;
			} 


			frompreyear = yyyy+'-' + mm+'-01';

			var tmp=0;
				  
			switch (Number(mm)) {
				
				case 1 :
				case 3 :
				case 5 :
				case 7 :
				case 8 :
				case 10 :
				case 12 :
				  tmp=31 ;
				  break;
				case 2 :   
				   tmp=28;
				   break;
				case 4 :
				case 6 :
				case 9 :
				case 11:
				   tmp=30;
				   break;
			}  	  

			topreyear = yyyy + '-' + mm + '-' + tmp ;

				document.getElementById("fromdate").value = frompreyear;
				document.getElementById("todate").value = topreyear;
				document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과 
} 

 
function pre_month(){    // 전월
//	document.getElementById('search').value=null; 
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
// document.getElementById('search').value=null; 
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
// document.getElementById('search').value=null; 
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
// document.getElementById('search').value=null; 
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
// document.getElementById('search').value=null; 
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
// document.getElementById('search').value=null; 
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

// document.getElementById('search').value=null; 
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

// document.getElementById('search').value=null; 

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


	

	
	//			document.getElementById('removeDataset').addEventListener('click', function() {
	//		config.data.datasets.splice(0, 1);
	//	window.myPie.update();
  </script>
  
  

<?php
if($mode==""&&$fromdate==null)  
{
  echo ("<script language=javascript> this_year();</script>");  // 당해년도 화면에 초기세팅하기
}

?>
  </body>

  </html>