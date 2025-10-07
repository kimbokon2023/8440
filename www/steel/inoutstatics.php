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
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
<script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

 <style>
   @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");
   header * {
	color : white;
}

a {
	 text-decoration : none;
}
</style>
<title> 미래기업 원자재 기간별 수불보고서 </title> 
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
 
     $process="전체";  // 기본 전체로 정한다.   
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

// if($separate_date=="1") $SettingDate="outdate ";
    // else
		 // $SettingDate="indate ";

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

<header class ="d-flex fex-column align-items-center flex-md-row p-1 bg-primary" >
    <h1 class="h4 my-0 me-md-auto"> 		
	원자재 기간별 수불 보고서 </h1>
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
						<a href="../login/logout.php">로그아웃</a> | <a href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a>
						
				<?php
					 }
				?>											
		
	  </div>
	  
</div>
	</header>
	<section class ="d-flex fex-column align-items-left flex-md-row p-1">
	 <div class="p-2 pt-md-3 pb-md-3 text-left" style="width:100%;">


  <form name="board_form" id="board_form"  method="post" action="inoutstatics.php?mode=search&search=<?=$search?>&find=<?=$find?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>&display_sel=<?=$display_sel?>">  
  <div class="card-header">      
       <div class="input-group p-2 mb-10">
			   <input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
			   
			<div id=display_board class=background name=display_board > 
			
			 <div class="clear"></div> 	 
		   
					<div id=list_board >    
						 
							<div id="list_search">        
							<div id="list_search111"> 		

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
							   <option value='company'>해당업체</option> "; break;
								 case 'outworkplace' : print "  
							   <option value='all'  >전체</option>
							   <option value='outworkplace' selected>현장명</option>
							   <option value='model'>모델명</option>
							   <option value='item'>철판종류</option>
							   <option value='company'>해당업체</option> "; break;		   
								 case 'model' : print "  
							   <option value='all'  >전체</option>
							   <option value='outworkplace' >현장명</option>
							   <option value='model'selected>모델명</option>
							   <option value='item'>철판종류</option>
							   <option value='company'>해당업체</option> "; break;	
								 case 'item' : print "  
							   <option value='all'  >전체</option>
							   <option value='outworkplace' >현장명</option>
							   <option value='model'>모델명</option>
							   <option value='item'selected>철판종류</option>
							   <option value='company'>해당업체</option> "; break;			   
								 case 'company' : print "  
							   <option value='all'  >전체</option>
							   <option value='outworkplace' >현장명</option>
							   <option value='model'>모델명</option>
							   <option value='item'>철판종류</option>
							   <option value='company'selected>해당업체</option> "; break;		   
							default : print "  
							   <option value='all'  >전체</option>
							   <option value='outworkplace' >현장명</option>
							   <option value='model'>모델명</option>
							   <option value='item'>철판종류</option>
							   <option value='company'>해당업체</option> "; break;				   
							   
								  } ?>			  
							</select>
									
							</div> <!-- end of list_search3 -->

							<div id="list_search4"><input type="text" name="search" id="search" value="<?=$search?>"> </div>
							 <div id="list_search5"><input type="image" src="../img/list_search_button.gif"></div> 
						  </div> <!-- end of list_search -->
						 </div> <!-- end of list_search -->
				   	   </div> <!-- end of list_search -->
					  </div> <!-- end of list_search -->
				  <div class="input-group p-2 mb-10">
				  <div class="clear"></div>
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
						  print " <h3 span class='input-group-text'> 입고물량 : 304 HL " . number_format($output_item_arr[0]) . "KG, &nbsp;&nbsp;   ";
						  print "  304 MR " . number_format($output_item_arr[1]) . "KG, &nbsp;&nbsp;   " ; 
						  print "  기타SUS " . number_format($output_item_arr[2]) . "KG,  &nbsp;&nbsp;  " ; 
						  print " PO " . number_format($output_item_arr[3]) . "KG, &nbsp;&nbsp;    " ; 
						  print "  EGI " . number_format($output_item_arr[4]) . "KG, &nbsp;&nbsp;   " ; 
						  print " CR " . number_format($output_item_arr[5]) . "KG </h3> <br> </div>" ; 

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
						  
						  print "<br>  <div class='input-group p-1 mb-1'>";
						  print " <h3 span class='input-group-text text-primary'> 출고물량 : 304 HL " . number_format($output_item_arr[0]) . "KG, &nbsp;&nbsp;   ";
						  print "  304 MR " . number_format($output_item_arr[1]) . "KG, &nbsp;&nbsp;   " ; 
						  print "  기타SUS " . number_format($output_item_arr[2]) . "KG,  &nbsp;&nbsp;  " ; 
						  print " PO " . number_format($output_item_arr[3]) . "KG, &nbsp;&nbsp;    " ; 
						  print "  EGI " . number_format($output_item_arr[4]) . "KG, &nbsp;&nbsp;   " ; 
						  print " CR " . number_format($output_item_arr[5]) . "KG </h3> <br> " ; 

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

                 &nbsp;&nbsp; <button  type="button" class="btn btn-danger" id="downloadcsvBtn"> CSV파일 다운로드 </button>	    	 &nbsp;&nbsp;									 
				 </div>       
      </div> <!-- end of card header-->		         
   	</form>

  <div id="grid" style="float:left;width:900px;margin-left:30px;"> </div>	


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
			  pageOptions: {
				useClient: false,
				perPage: 20
			  },					  
		
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


  </script>  

   </div>
  </div>	 
</section>

</body>

</html>