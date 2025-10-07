<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/session.php');

if(!isset($_SESSION["level"]) || $_SESSION["level"]>8) {
		 sleep(1);
		 header("Location:https://8440.co.kr/login/login_form.php"); 
         exit;
   }  
   
include $_SERVER['DOCUMENT_ROOT'] . "/common.php";
 
    // 첫 화면 표시 문구
 $title_message = '외주발주 관리'; 
 
 ?>

 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>

<title> <?=$title_message?> </title>
			
<body>

<?php 

if($user_name !== '덴크리' && $user_name !== '서한컴퍼니' && $user_name !== '다온텍' ) 
		include '../myheader.php'; 
	else
		include '../partner_myheader.php'; 


?>  

 <?php
 
include '_request.php';
    
  $sum=array();
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";      
   
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
   $find=$_REQUEST["find"];
  	
$now = date("Y-m-d");	 // 현재 날짜와 크거나 같으면 출고예정으로 구분

 $orderby="order by num desc ";	
 
// 외주업체인 경우 해당업체만 볼 수 있도록 화면구성함
if($user_name === '덴크리'  or  $user_name === '서한컴퍼니'   or  $user_name === '다온텍'  )  
{
	switch ($check) {
		case 1:  // 미출고 리스트 체크된 경우
			$attached=" and ((workday='') or (workday='0000-00-00')) and (firstord='$user_name')  ";
			$whereattached=" where workday=''  and (firstord='$user_name')  ";
			break;
		case 2:
			$attached=" and ((workday!='') and (workday!='0000-00-00'))  and (firstord='$user_name') ";
			$whereattached=" where workday!='' and (firstord='$user_name')  ";
			break;
		case 3:
			$attached=" and ( confirm='발주서변경' )  and (firstord='$user_name') ";
			$whereattached=" where confirm='발주서변경'  and (firstord='$user_name') ";
			break;
		case 4:
			$attached=" and (firstord='$user_name') and (((workday!='') and (workday!='0000-00-00')) and ((demand='') or (demand='0000-00-00')))  ";
			$whereattached=" where (firstord='$user_name') and (((workday!='') and (workday!='0000-00-00')) and ((demand='') or (demand='0000-00-00')))  ";
			break;
		default:
			$attached=" and (firstord='$user_name') ";
			$whereattached=" where (firstord='$user_name') ";	
	}
}
else
{  // 미래기업 본사에서 클릭시
	switch ($check) {
		case 1:  // 미출고 리스트 체크된 경우
			$attached=" and ((workday='') or (workday='0000-00-00')) ";
			$whereattached=" where workday=''    ";
			break;
		case 2:
			$attached=" and ((workday!='') and (workday!='0000-00-00'))  ";
			$whereattached=" where workday!=''   ";
			break;
		case 3:
			$attached=" and ( confirm='발주서변경' )  ";
			$whereattached=" where confirm='발주서변경'  ";
			break;
		case 4:
			$attached=" and (((workday!='') and (workday!='0000-00-00')) and ((demand='') or (demand='0000-00-00')))  ";
			$whereattached=" where (((workday!='') and (workday!='0000-00-00')) and ((demand='') or (demand='0000-00-00')))  ";
			break;
		default:
			$attached=" ";
			$whereattached=" ";	
	}
	

}	
	 
$a= " " . $orderby ;
$b=  " " . $orderby;

// 발주상태 자료 통계 뽑기
// 발주업체가 같은 것만 추출한다
$change_order = 0;	
$new_order = 0;	

$sql = 'select * from mirae8440.outorder ' . $orderby ;
$stmh = $pdo->query($sql); 
try{
	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			$confirm=$row["confirm"];	
			$firstord=$row["firstord"];	
			
			if ($confirm=='발주서변경' && $firstord==$user_name)
	               $change_order++;
			   
			 if ($confirm=='' && $firstord==$user_name)
	               $new_order++;			
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  


$search = str_replace(' ', '', $search);  

		  if($search==""){
					 $sql="select * from mirae8440.outorder " . $whereattached . $a; 						                 
			       }
			 elseif($search!=""&&$find!="all")
			    {
         			$sql="select * from mirae8440.outorder where ($find like '%$search%') " . $attached . $a;         			
                 }     				 
			if ($search != "" && $find == "all") {
			  $condition = "replace(workplacename,' ','') like '%$search%' 
				or firstordman like '%$search%' 
				or secondordman like '%$search%' 
				or chargedman like '%$search%'
				or delicompany like '%$search%'
				or type1 like '%$search%'
				or firstord like '%$search%'
				or secondord like '%$search%'
				or car_inside1 like '%$search%'
				or memo like '%$search%'
				or memo2 like '%$search%'
				or material1 like '%$search%'
				or material2 like '%$search%'
				or material3 like '%$search%'
				or material4 like '%$search%'
				or material5 like '%$search%'
				or air_su like '%$search%'
				or comment1 like '%$search%'
				or comment2 like '%$search%'
				or comment3 like '%$search%'
				or comment4 like '%$search%'
				or comment5 like '%$search%'
				or comment6 like '%$search%'
				or comment7 like '%$search%'
				or comment8 like '%$search%'
				or comment9 like '%$search%'
				or comment10 like '%$search%'";
			
				$sql = "select * from mirae8440.outorder where ($condition) $attached $a";				
			
			} 
	

// search 모드가 아닐때는 100개로 데이터 제한
if($mode!=='search')  
	$sql .= ' limit 0, 50';
   

   // print 'search : ' . $search . '<br>' ;   
   // print '$check' . $check. '<br>' ;   
   // print $sql; 	   
   
 try{  

	  $stmh = $pdo->query($sql);            //  맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp1;     // 전체 글수	 		

	  
?>
 

<form id="board_form" name="board_form" method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=<?=$stable?>">  

	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 	
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 	
	<input type="hidden" id="page" name="page" value="<?=$page?>" size="5" > 	
	<input type="hidden" id="scale" name="scale" value="<?=$scale?>" size="5" > 	
	<input type="hidden" id="yearcheckbox" name="yearcheckbox" value="<?=$yearcheckbox?>" size="5" > 	
	<input type="hidden" id="year" name="year" value="<?=$year?>" size="5" > 	
	<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 	
	<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 	
	<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 	
	<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 	
	<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 	
	<input type="hidden" id="cursort" name="cursort" value="<?=$cursort?>" size="5" > 	
	<input type="hidden" id="sortof" name="sortof" value="<?=$sortof?>" size="5" > 	
	<input type="hidden" id="stable" name="stable" value="<?=$stable?>" size="5" > 	
	<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 			
	<input type="hidden" id="check_draw" name="check_draw" value="<?=$check_draw?>" size="1" > 	
	<input type="hidden" id="notorder" name="notorder" value="<?=$notorder?>" size="1" > 	

<div class="container-fluid" >  		  

<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/modal.php'; ?>
								
<div class="card" >  		  
<div class="card-body" >  	 
	<div class="d-flex mb-1 mt-1 justify-content-center  align-items-center " >  			
		<h4>	<span class="text-dark">  <?=$title_message?></span> </h4>
	</div>	 	
	<div class="d-flex mb-1 mt-1 justify-content-center  align-items-center" >  		  			 
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="color:red;"> 한국엘리베이터/ 팝너트X  </span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 형광등 = 내고대상 아님  
	</div>
	<div class="d-flex mb-1 mt-1 justify-content-center  align-items-center" >  	
		<span class="badge bg-success fs-6" >	
			모든 : MCI-600 (017) / 500 (013) / 550 (012)  
		 </span> 
		 &nbsp;&nbsp;&nbsp; 

		<span class="badge bg-danger fs-6" >	
			덴크리 외주 011.012,013D,025,017
		 </span> 
		&nbsp;&nbsp;&nbsp; 
		<b> '발주서변경/신규발주건에 대한 팝업창이 뜹니다.' </b>
		&nbsp;&nbsp;&nbsp; 		 
	</div>	 
	<div class="d-flex mb-1 mt-1 justify-content-center  align-items-center" >        
	   <?= $total_row ?> 자료 &nbsp; &nbsp;&nbsp;&nbsp;				
		&nbsp; &nbsp; 							
	   <button type="button" class="btn btn-dark   btn-sm" onclick="search_condition('0')"> 전체 </button> &nbsp;   		
	   <button type="button" class="btn btn-dark btn-sm" onclick="search_condition('1')"> 미출고 </button> &nbsp;   		       
	   <button type="button" class="btn btn-danger btn-sm" onclick="search_condition('3')"> 발주서변경 </button> &nbsp;   		
		
		<button type="button" id="outputBtn" class="btn btn-outline-danger  btn-sm"> 출고완료 미청구 </button> &nbsp;				
       &nbsp; &nbsp; &nbsp; 
        <select id="find" name="find"  class="form-select form-select-sm me-1 w-auto"  >
           <?php		  
		      if($find=="")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord'>발주처</option>
           <option value='type' >타입</option>		   		   

		   <?php
			  } ?>		
		  <?php		  
		      if($find=="all")
			  {			?>	  
           <option value='all' selected>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord'>발주처</option>
           <option value='type' >타입</option>		   		   

		   <?php
			  } ?>
		  <?php
		      if($find=="workplacename")
			  {			?>	  
           <option value='all' >전체</option>
           <option value='workplacename' selected>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord'>발주처</option>
           <option value='type' >타입</option>		   
		   <?php
			  } ?>			  
		  <?php
		      if($find=="firstord")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord' selected>원청</option>
           <option value='secondord'>발주처</option>
           <option value='type' >타입</option>		   		   

		   <?php
			  } ?>			  
		  <?php
		      if($find=="secondord")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord' selected>발주처</option>
           <option value='type' >타입</option>		   
		   
		   <?php
			  } ?>	  			  
		  		  
		  <?php
		      if($find=="type")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>원청</option>
           <option value='secondord' >발주처</option>
           <option value='type' selected>타입</option>
		   
		   <?php
			  } ?>	  				  			  
				  
        </select>
		
          &nbsp;
          <input type="text" id="search" name="search"  class="form-control w-auto me-1"  value="<?=$search?>" onkeydown="JavaScript:SearchEnter();" >
          
		  &nbsp;
		  <button id="searchBtn" type="button" class="btn btn-outline-dark  btn-sm" > <i class="bi bi-search"></i> 검색  </button> 
		  
		  &nbsp;&nbsp;&nbsp; 
		  
		<? if($user_name !== '덴크리' && $user_name !== '서한컴퍼니' && $user_name !== '다온텍' ) { ?>
		   <button type="button" class="btn btn-dark  btn-sm me-2" id="writeBtn"> <i class="bi bi-pencil"></i>  신규  </button> 			     
           <button type="button" class="btn btn-dark  btn-sm" onclick="window.open('batchDB.php','청구일/출고일 일괄처리','left=10,top=50, scrollbars=yes, toolbars=no,width=1800,height=850');"> 청구일/출고일 일괄처리 </button>    &nbsp;
           <button  type="button" class="btn btn-dark  btn-sm" id="downloadcsvBtn" onclick="window.open('call_csv.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&list=1&sortof=6&cursort=<?=$cursort?>&process=<?=$process?>&year=<?=$year?>&stable=0&output_check=<?=$output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>$plan_output_check=<?=$plan_output_check?>&check=<?=$check?>','CSV 파일추출','left=100,top=100, scrollbars=yes, toolbars=no,width=1600,height=500');">  CSV 엑셀 다운로드 </button>           &nbsp;
		<?  } ?>      		   
           <button type="button" class="btn btn-dark  btn-sm" onclick="window.open('plan_making.php?mode=search&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&check=<?=$check?>','납품일정 List DB','left=10,top=10, scrollbars=yes, toolbars=no,width=1800,height=800');" border="0">납품예정 </button>    &nbsp;
                      
		</div> 
	</div>	 
	</div>	 
		
 <style>
th {
    white-space: nowrap;
}
</style>

<div class="row m-1 mt-1 mb-1 justify-content-center align-items-center"> 		 

<table class="table table-hover" id="myTable">
  <thead class="table-primary">
    <tr>
      <th class="text-center">번호</th>
      <th class="text-center">접수일</th>
      <th class="text-center">매입처</th>
      <th class="text-center">발주확인</th>
      <th class="text-center">발주일</th>
      <th class="text-center">납기일</th>
      <th class="text-center">출고일</th>
      <th class="text-center">청구</th>
      <th class="text-center">현장명</th>
      <th class="text-center">발주처</th>
      <th class="text-center">타입</th>
      <th class="text-center">인승</th>
      <th class="text-center">Car inside</th>
      <th class="text-center">결합</th>
      <th class="text-center">L/C</th>
      <th class="text-center">기타</th>
      <th class="text-center">운반비 내역</th>
      <th class="text-center">비고</th>
	  
    </tr>	
  </thead>
 <tbody>
  
    <?php  
	  if ($page<=1)  
		$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
	  else 
		$start_num=$total_row-($page-1) * $scale;		  
	
	  while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	      include '_row.php';	  

		$sum[0] = $sum[0] + (int)$su;
		$sum[1] += (int)$bon_su;
		$sum[2] += (int)$lc_su;
		$sum[3] += (int)$etc_su;
		$sum[4] += (int)$air_su;
		$sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;

		$dis_text = " 결합단위 : " . $sum[0] . " (SET),  L/C : "  . $sum[2] . "  (EA), 기타 : "  . $sum[3] . "  (EA)"; 			   			  

		$startday=trans_date($startday);
		$workday=trans_date($workday);
		$demand=trans_date($demand);
		$orderday=trans_date($orderday);
		$deadline=trans_date($deadline);
		$testday=trans_date($testday);
		$lc_draw=trans_date($lc_draw);
		$lclaser_date=trans_date($lclaser_date);
		$lclbending_date=trans_date($lclbending_date);
		$lclwelding_date=trans_date($lclwelding_date);
		$lcpainting_date=trans_date($lcpainting_date);
		$lcassembly_date=trans_date($lcassembly_date);
		$main_draw=trans_date($main_draw);			
		$eunsung_make_date=trans_date($eunsung_make_date);			
		$eunsung_laser_date=trans_date($eunsung_laser_date);			
		$mainbending_date=trans_date($mainbending_date);			
		$mainwelding_date=trans_date($mainwelding_date);			
		$mainpainting_date=trans_date($mainpainting_date);			
		$mainassembly_date=trans_date($mainassembly_date);										

		$order_date1=trans_date($order_date1);					   
		$order_date2=trans_date($order_date2);					   
		$order_date3=trans_date($order_date3);					   
		$order_date4=trans_date($order_date4);					   
		$order_input_date1=trans_date($order_input_date1);					   
		$order_input_date2=trans_date($order_input_date2);					   
		$order_input_date3=trans_date($order_input_date3);					   
		$order_input_date4=trans_date($order_input_date4);		

	  // 덴크리 직접작업을 위한 설계내용 5개 항목추가
	  
	  $deliverynum=$row["deliverynum"];  
	  $confirm=$row["confirm"];
	  $submemo=$row["submemo"];
	  $pdffile_name = $row["pdffile_name"];
	  $copied_file = $row["copied_file"];			
			  	  				  
	  $state_work=0;
	  if($row["checkbox"]==0) $state_work=1;
	  if(substr($row["workday"],0,2)=="20") $state_work=2;
	  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
	  
       $typeAll="";
	   $tmp="";
       for($i=1;$i<=10;$i++) {
		   $tmp='type' . $i;
		 if($i>1 && $$tmp!='' )
      			 $typeAll .= '<br>' . $$tmp;     
		     else
				  $typeAll .= $$tmp;   
	   }      
	   $car_insideAll="";
	   $tmp="";
       for($i=1;$i<=10;$i++) {
		   $tmp='car_inside' . $i;
		 if($i>1 && $$tmp!='' )
      			 $car_insideAll .= '<br>' . $$tmp;   
		     else
				  $car_insideAll .= $$tmp;   
	   }     
	   $inseungAll="";
	   $tmp="";
       for($i=1;$i<=10;$i++) {
		   $tmp='inseung' . $i;
		 if($i>1 && $$tmp!='' )
      			 $inseungAll .='<br>' . $$tmp;    
		     else
				  $inseungAll .= $$tmp;   
	   }
	        
	   $lc_suAll="";	   	   
	   
	    $itemNames = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth'];		
       for($i=0;$i<10;$i++) {
		   $tmp = ${$itemNames[$i].'_item3'};		   
		   $basic = '';
		   $basic = ${$itemNames[$i].'_item1'};
		   // print '<tr> <td> ';
		   // print $$tmp;
		   // print ' </td> </tr>';
	   if( $basic ==='프레임')
		   {
			 if($lc_suAll !== '' )
					 $lc_suAll .='<br>' . $tmp;    
				 else
					  $lc_suAll .= $tmp;   
			}
	     }
	   
	 	   
	   if(($confirm!='재확인완료' and $confirm!='') and ($confirm!='발주서변경' and $confirm!=''))
	      {
		     $confirm ='확인완료';
			 $color = 'text-dark';			
		  }
		  
        if($confirm=='재확인완료' )
			 $color = 'text-primary';
		 
		$confirmblink =''; 
        if($confirm=='발주서변경' )
		   {
			 $color = 'text-danger';
			 $confirmblink ='blink'; 
		   }
		$deadlineblink ='';
		if($deadline==date("Y-m-d",time()))  // 납품예정
			   $deadlineblink ='blink'; 
						
        $deli_text="";
		if($delivery!="" || $delipay!=0)
			  $deli_text = $delivery . " " . $delipay ; 	

        if($confirm=='' )
			$confirm ='&nbsp;&nbsp;';
		  
			 ?>
				  
	    <tr onclick="redirectToView('<?=$num?>')" >
			<td class="text-center"> <?php echo echo_null($start_num); ?> </td>
			<td class="text-center" style="width:80px;" data-order="<?=$orderday ?>"> <?php echo echo_null($orderday); ?> </td>
			<td class="text-center"> <?php echo echo_null($firstord); ?> </td>
			<td class="text-center <?=$color?>  <?=$confirmblink?> "> <?php echo $confirm; ?> </td>
			<td class="text-center" data-order="<?=$startday ?>"> <?php echo echo_null(iconv_substr($startday, 5, 5, "utf-8")); ?> </td>
			<td class="text-center" data-order="<?=$deadline ?>"> <?php echo echo_null(iconv_substr($deadline, 5, 5, "utf-8")); ?> </td>
			<td class="text-center" data-order="<?=$workday ?>">  <?php echo echo_null(iconv_substr($workday, 5, 5, "utf-8")); ?> </td>
			<td class="text-center" data-order="<?=$demand ?>"> <?php echo echo_null(iconv_substr($demand, 5, 5, "utf-8")); ?> </td>
			<td class="text-left"> <?php echo echo_null($workplacename); ?> </td>
			<td class="text-center"> <?php echo echo_null($secondord); ?> </td>
			<td class="text-center"> <?php echo $typeAll; ?> </td>
			<td class="text-center"> <?php echo $inseungAll; ?> </td>
			<td class="text-center"> <?php echo $car_insideAll; ?> </td>
			<td class="text-center"> <?php echo $su; ?> </td>
			<td class="text-center"> <?php echo $lc_suAll; ?> </td>
			<td class="text-center"> <?php echo echo_null($etc_su); ?> </td>
			<td class="text-center"> <?php echo echo_null($deli_text); ?> </td>
			<td class="text-center"> <?php echo echo_null(iconv_substr($memo, 0, 8, "utf-8")); ?> </td>
		</tr>

		     
		<?php	
		  $start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
 ?>
 
       </tbody>
	   </table>	   
   </div> 
</div> 

</form>	

<div class="container-fluid mt-3 mb-3">
<? include '../footer_sub.php'; ?>
</div>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var outorderpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 50,
        "lengthMenu": [25, 50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        // "createdRow": function(row, data, dataIndex) {
            // $(row).find('td').css('border', '1px solid #eeee'); // 셀에 스타일 적용
        // },		
		
        "order": [[0, 'desc']]
    });
	


    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('outorderpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var outorderpageNumber = dataTable.page.info().page + 1;
        setCookie('outorderpageNumber', outorderpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('outorderpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });
	  
});

function restorePageNumber() {
    var savedPageNumber = getCookie('outorderpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
    }
}



function SearchEnter(){	    
    if(event.keyCode == 13){
		setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장
		document.getElementById('board_form').submit(); 
    }
}

$(document).ready(function(){

	$("#writeBtn").click(function(){ 
	
		var page = outorderpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
			
		var url = "write_form.php"; 

		customPopup(url, '외주 수주내역', 1880, 920); 	
	 });		
		 	
	
$("#searchBtn").click(function(){ 	
	// DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
	dataTable.page(0).draw('page');
	setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장
	 document.getElementById('board_form').submit();    
 
 });		
		

	// 출고완료 미청구 리스트 정렬	
	$("#outputBtn").click(function(){	
		$('#check').val('4');       // 선택
		// DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
		dataTable.page(0).draw('page');
		setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장		
		$('#board_form').submit();		
	});		
				 
	$("#closeModalBtn").click(function(){     
		
		$('#myModal').modal('hide');	

		// 쿠키 설정 - 1일 동안 유지  하루 동안 뜨지 않기
		var date = new Date();
		date.setTime(date.getTime() + (3 * 60 * 60 * 1000)); // 1일 - 24를 3으로 3시간
		document.cookie = 'modalClosed=true; expires=' + date.toUTCString() + '; path=/';		
	});
		
    $("#without").change(function(){
        if($("#without").is(":checked")){
            $('#check').val('1');
			// DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
			dataTable.page(0).draw('page');
			setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장	   
            $('#board_form').submit();	
        }else{
            $('#check').val('');
			// DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
			dataTable.page(0).draw('page');
			setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장		  
            $('#board_form').submit();						
        }
    });
    $("#outputlist").change(function(){
        if($("#outputlist").is(":checked")){
            $('#output_check').val('1');
			// DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
			dataTable.page(0).draw('page');
			setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장	   
            $('#board_form').submit();
			
        }else{
            $('#output_check').val('');
			// DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
			dataTable.page(0).draw('page');
			setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장	  
            $('#board_form').submit();			
        }
    });	
	
    $("#plan_outputlist").change(function(){
        if($("#plan_outputlist").is(":checked")){
            $('#plan_output_check').val('1');
			// DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
			dataTable.page(0).draw('page');
			setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장		  			          
            $('#board_form').submit();
			
        }else{
            $('#plan_output_check').val('');
			// DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
			dataTable.page(0).draw('page');
			setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장					
            $('#board_form').submit();			
        }
    });	
	
    $("#team").change(function(){
        if($("#team").is(":checked")){
            $('#team_check').val('1');          
            $('#board_form').submit();
			
        }else{
            $('#team_check').val('');
    // DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
    dataTable.page(0).draw('page');
    setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장		  		  
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
});		

  // 모달 창 표시 여부 확인
  function checkModalStatus() {
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
      var cookie = cookies[i].trim();
      if (cookie.indexOf('modalClosed=') === 0) {
        return cookie.substring('modalClosed='.length, cookie.length) !== 'true';
      }
    }
    return true;
  }	
	

function dis_text()
{  
		var dis_text = '<?php echo $dis_text; ?>';
		$("#dis_text").val(dis_text);
}	

function search_condition(con) {
    $("#check").val(con);
    $("#search").val('');

    // DataTables의 페이지를 1페이지로 설정하고, 쿠키에 저장
    dataTable.page(0).draw('page');
    setCookie('outorderpageNumber', 1, 10); // 쿠키에 페이지 번호 저장

    // 폼 제출 (페이지 새로고침)
    $('#board_form').submit();
}

 
function check_msg() {   // 덴크리 발주서변경건수 신규등록현장수 보여주는 화면 구성
	var user_name = '<?php echo $user_name; ?>';
	var change_order = '<?php echo $change_order; ?>';
	var new_order = '<?php echo $new_order; ?>';
    var cookieCheck = getCookie("popupYN");	
	
	if(( Number(change_order)>0 || Number(new_order)>0 ) && (user_name=='덴크리' || user_name=='서한컴퍼니'   || user_name=='다온텍'   ))
		{

			  tmp='발주서 변경 :  ' + change_order + ' 건 <br> 신규발주 : ' + new_order + ' 건 <br> 감사합니다.';		
			  $('#alertmsg').html(tmp); 			  
				var showModal = checkModalStatus();
			if (showModal) {
			   $('#myModal').modal('show');	
			}
				  
				  // if (cookieCheck != "N")	
				  // {
					// // 하루 한번이 아니면 띄워주고 쿠키저장한다.
					// setCookie("popupYN", "N", 1);   // 하루한번을 위한 쿠키 저장
					// $('#myModal').modal('show');	
				  // }			  			  
		}		

 }      	
 

// 화면 디스플레이  
setTimeout(function() { 
 dis_text();
 check_msg();
}, 300);


function redirectToView(num) {
    var page = outorderpageNumber; // 현재 페이지 번호 (+1을 해서 1부터 시작하도록 조정)
    	
    var url = "view.php?menu=no&num=" + num ;        

	customPopup(url, '외주 수주내역', 1850, 920); 		    
}
</script>


<script>
	$(document).ready(function(){
		saveLogData('천장 외주발주 관리 '); // 다른 페이지에 맞는 menuName을 전달
	});
</script> 
	
	</body>  
  </html>
  