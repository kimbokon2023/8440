<?php
require_once __DIR__ . '/bootstrap.php';
  
  $page=1;	 
  
  $scale = 10;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  $now = date("Y-m-d",time()) ;
  
// 접수일자로 접수수량 체크  
$a="   where orderday='$now' order by num desc ";    
$sql="select * from mirae8440.work " . $a; 					
	   
	 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $total_row=$temp1;	  
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  	  
	  
$jamb_registedate = $total_row;	  
  
// 출고완료 수량 체크  
$a="   where workday='$now' order by num desc ";    
$sql="select * from mirae8440.work " . $a; 					
	   
	 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $total_row=$temp1;	  
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  	  
	  
$jamb_outputdonedate = $total_row;	  
  
// 출고예정 수량 체크  
$a="   where endworkday='$now' order by num desc ";    
$sql="select * from mirae8440.work " . $a; 					
	   
	 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $total_row=$temp1;	  
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  	  
	  
// 출고예정일별로 찾기
	  
$a="   where endworkday='$now' order by num desc ";  
$sql="select * from mirae8440.work " . $a; 	
$dis_text = "jamb 금일출고 ";

// print_r($total_row);
  
?>		
	
<style>
.rounded-card {
	border-radius: 15px !important;  /* 조절하고 싶은 라운드 크기로 설정하세요. */
}
a {
	text-decoration: none;
}  
.table-hover tbody tr:hover {
	cursor: pointer;
}      
</style>	

<div class="d-flex justify-content-center align-items-center mb-2">
	<button class="btn btn-primary btn-sm jambbatchBtn " type="button"  > <i class="bi bi-printer"></i> </button> &nbsp;
	<button class="btn btn-secondary btn-sm  planmakingBtn" type="button" > <i class="bi bi-calendar-check"></i>  </button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
	<span  class="text-dark dis_text" ></span>    
	<span  class="text-dark out_dis_text" ></span>    
</div>

<?php if($total_row>0) : ?>
	<table class="table table-bordered table-hover table-sm">
			<thead class="table-primary">		
			<tr>				
				<th class="text-center"  style="width:25%;"> 현장명 </th>				
				<th class="text-center"  style="width:20%;">재질(소재)</th>
				<th class="text-center">발주처/소장</th>
				<th class="text-center">시공내역</th>
				<th class="text-center"> 비고</th>
			</tr>
		</thead>
		<tbody>   		
<?php  	
 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $total_row=$temp1;	
		
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
		
		    $jamb_duedate = $total_row;
		    $jamb_registedate = 0 ;
		    $jamb_outputdonedate = 0 ;
	    
		   $sum = array();
		   $out_sum = array();
		   $check_outsourcing = 0;
		   
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			   include './work/_row.php';			   
			  
			if(empty($outsourcing))  
			{
			  $sum[0] = $sum[0] + (int)$widejamb;
			  $sum[1] += (int)$normaljamb;
			  $sum[2] += (int)$smalljamb;
			  $sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
			  
			}
			else
			{			  
			  $out_sum[0] = $out_sum[0] + (int)$widejamb;
			  $out_sum[1] += (int)$normaljamb;
			  $out_sum[2] += (int)$smalljamb;
			  $out_sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
			  $check_outsourcing = 1;
		}
					  
			 if($check_outsourcing) 
					$out_dis_text = "외주 ☞ " . $out_sum[3] . " ,         막판 : " . $out_sum[0] . " ,  막판(無) : " . $out_sum[1] . " ,  쪽쟘 : "  . $out_sum[2] . " "  ; 			  
				
			$dis_text = "";
			if ($sum[0] != 0 || $sum[1] != 0 || $sum[2] != 0) {
				$dis_text = "총 : " . $sum[3] . "(SET)";
				if ($sum[0] != 0) {
					$dis_text .= " ,  막판 : " . $sum[0];
				}
				if ($sum[1] != 0) {
					$dis_text .= " ,  막판(無) : " . $sum[1];
				}
				if ($sum[2] != 0) {
					$dis_text .= " ,  쪽쟘 : " . $sum[2];
				}
				$dis_text .= " ";
			}
			  

		      if($orderday!="0000-00-00" and $orderday!="1970-01-01"  and $orderday!="") $orderday = date("Y-m-d", strtotime( $orderday) );
					else $orderday="";
		      if($measureday!="0000-00-00" and $measureday!="1970-01-01" and $measureday!="")   $measureday = date("Y-m-d", strtotime( $measureday) );
					else $measureday="";
		      if($drawday!="0000-00-00" and $drawday!="1970-01-01" and $drawday!="")  $drawday = date("Y-m-d", strtotime( $drawday) );
					else $drawday="";
		      if($deadline!="0000-00-00" and $deadline!="1970-01-01" and $deadline!="")  $deadline = date("Y-m-d", strtotime( $deadline) );
					else $deadline="";
		      if($workday!="0000-00-00" and $workday!="1970-01-01"  and $workday!="")  $workday = date("Y-m-d", strtotime( $workday) );
					else $workday="";					
		      if($endworkday!="0000-00-00" and $endworkday!="1970-01-01" and $endworkday!="")  $endworkday = date("Y-m-d", strtotime( $endworkday) );
					else $endworkday="";	
		      if($demand!="0000-00-00" and $demand!="1970-01-01" and $demand!="")  $demand = date("Y-m-d", strtotime( $demand) );
					else $demand="";						
			  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;
			  			  
			  $font="black";
			  switch ($state_work) {
                            case 1: $state_str="착공전"; $font="black";break;				  
							case 2: $state_str="시공중"; $font="blue"; break;						
							default: $font="grey"; $state_str="계약전"; 
						}

			  $font1="black";
              $draw_done="";			  
			  if(substr($row["drawday"],0,2)=="20") 
			  {
			      $draw_done = "OK";	
					if($designer!='')
						 $draw_done = $designer ;
			  }
              if($workday!='') $workday=substr($row["workday"],5,5);
			   $materials="";
				  $materials=$material2 . " " . $material1 . " " . $material3 . $material4 . $material5 . $material6;
		
				 $workitem="";
				 if($widejamb!="")
					    $workitem="막판" . $widejamb . " "; 
				 if($normaljamb!="")
					    $workitem .="막(無)" . $normaljamb . " "; 					
				 if($smalljamb!="")
					    $workitem .="쪽쟘" . $smalljamb . " "; 	

                if($checkstep!=='신규' && $outsourcing === '')  
				        $checkstep = '';
					else if ($outsourcing !== null)
				             $checkstep = $checkstep . '(' . $outsourcing . ')' ;										   				  
 ?>						 
	<tr onclick="redirectToView_jamb('<?=$num?>')">		
		<td class="text-start" title="<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>">
			<?php
				$display_workplacename = (mb_strlen($workplacename, 'UTF-8') > 10) ? mb_substr($workplacename, 0, 10, 'UTF-8') . '..' : $workplacename;
				echo $display_workplacename;
			?>
		</td>
		<td class="text-center" title="<?= htmlspecialchars($materials, ENT_QUOTES, 'UTF-8') ?>">
			<?php
				$display_materials = (mb_strlen($materials, 'UTF-8') > 12) ? mb_substr($materials, 0, 12, 'UTF-8') . '..' : $materials;
				echo $display_materials;
			?>
		</td>
		<td class="text-center" title="<?= htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8') . '/' . htmlspecialchars($worker, ENT_QUOTES, 'UTF-8') ?>">
			<?php
				$display_secondord = (mb_strlen($secondord, 'UTF-8') > 8) ? mb_substr($secondord, 0, 8, 'UTF-8') . '..' : $secondord;
				$display_worker = (mb_strlen($worker, 'UTF-8') > 8) ? mb_substr($worker, 0, 8, 'UTF-8') . '..' : $worker;
				echo $display_secondord . '/' . $display_worker;
			?>
		</td>
		<td class="text-center" title="<?= htmlspecialchars($workitem, ENT_QUOTES, 'UTF-8') ?>">
			<?php
				$display_workitem = (mb_strlen($workitem, 'UTF-8') > 8) ? mb_substr($workitem, 0, 8, 'UTF-8') . '..' : $workitem;
				echo $display_workitem;
			?>
		</td>
		<td class="text-start" title="<?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?>">
			<?php
				$display_memo = (mb_strlen($memo, 'UTF-8') > 8) ? mb_substr($memo, 0, 8, 'UTF-8') . '..' : $memo;
				echo $display_memo;
			?>
		</td>
	</tr>	  
	<?php
	$start_num--;
	 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;  
 ?>		
	</tbody>
	</table>
	<?php else : ?>
	<div class="d-flex text-center justify-content-center fs-3 mb-2 mt-3">				
		   금일 쟘 출고 예정이 없습니다. 
	</div>
	<?php endif; ?>

<script> 
function redirectToView_jamb(num) {
	popupCenter("./work/view.php?menu=no&num=" + num, "Jamb 수주현황", 1800, 900);	      
}
function dis_text()
{  
	var dis_text = '<?php echo $dis_text; ?>';
	var out_dis_text = '<?php echo $out_dis_text; ?>';
	$(".dis_text").text(dis_text);
	$(".out_dis_text").text(out_dis_text);
}	

  // 쟘 합계 화면에 출력하기
setTimeout(function() {
 // console.log('Works!');
 dis_text();
}, 1000);

$(document).ready(function(){	
	$(".jambbatchBtn").off('click').on('click', function(){  
		window.open('./work/batchDB_invoice.php','묶음출고증','left=10,top=50, scrollbars=yes, toolbars=no,width=1800,height=840');  
	});
	$(".planmakingBtn").off('click').on('click', function(){  
		window.open('./work/plan_making.php?option=init','생산일정(생산완료제외) DB','left=50,top=50, scrollbars=yes, toolbars=no,width=1850,height=970'); 		  
	});
});
</script> 
  