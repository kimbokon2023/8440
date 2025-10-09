<?php
require_once __DIR__ . '/bootstrap.php';

$dis_text2 = ""; // 변수 초기화

$page=1;	 

$scale = 10;       // 한 페이지에 보여질 게시글 수
$page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
$first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.

$sum_ceiling = array();
 
$now = date("Y-m-d",time()) ;


// 접수일자로 접수수량 체크  
$a = "  where orderday='$now' order by num desc ";    
$sql = "select * from mirae8440.ceiling " . $a; 					
	   
 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $total_row=$temp1;	  
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  	  
	  
$ceiling_registedate = $total_row;	  
  

// 출고일자로 접수수량 체크  
$a="   where deadline='$now' order by num desc ";    
$sql="select * from mirae8440.ceiling " . $a; 					
	   
 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $today_total_row=$temp1;	  
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  	  
	  
$ceiling_duedate = $today_total_row;	  
  
// 출고완료 수량 체크  
$a="   where workday='$now' order by num desc ";    
$sql="select * from mirae8440.ceiling " . $a; 					
	   
	 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $total_row=$temp1;	  
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  	  	  
	$ceiling_outputdonedate = $total_row;	  
	$a="   where deadline='$now' order by num desc ";  
	$sql="select * from mirae8440.ceiling " . $a; 					
	  
?>
<style>
	th {		
	   text-align : center;	
	}
  
.table-hover tbody tr:hover {
	cursor: pointer;
}	
</style>
	
<div class="d-flex justify-content-center align-items-center mb-2">
	<button class="btn btn-primary btn-sm batchBtn" type="button"  >  <i class="bi bi-printer"></i> </button> &nbsp;&nbsp;		 		 
	<button class="btn btn-secondary btn-sm laserceilingplanBtn" type="button"  > <i class="bi bi-calendar-check"></i> </button> &nbsp;&nbsp;
	<button class="btn btn-success btn-sm mobileBtn" type="button"  > <i class="bi bi-card-checklist"></i>  출하 일정</button> &nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
	<span  class="text-dark dis_text2"></span>  
</div>

<?php if($today_total_row>0) : ?>		
	<table class="table table-bordered table-hover table-sm">
		<thead class="table-primary">	
            <tr>                
                <th scope="col" style="width:15%;" > 도장/조립 </th>
                <th scope="col" style="width:20%;"  > 현  장  명 </th>				
                <th scope="col">발 주 처</th>
                <th scope="col">타입</th>                
                <th scope="col">본/LC/기타</th>
                <th scope="col">비고</th>
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
		
		   $sum = array();
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		     
               include './ceiling/_rowDB.php';			 
			  
			  $sum1[$counter] += (int)$su;
			  $sum2[$counter] += (int)$bon_su;
			  $sum3[$counter] += (int)$lc_su;
			  $sum4[$counter] += (int)$etc_su;
			  $sum5[$counter] += (int)$air_su;			  


              $sum[0] = $sum[0] + (int)$su;
			  $sum[1] += (int)$bon_su;
			  $sum[2] += (int)$lc_su;
			  $sum[3] += (int)$etc_su;
			  $sum[4] += (int)$air_su;
			  $sum[5] += (int)$su + (int)$bon_su + (int)$lc_su + (int)$etc_su + (int)$air_su;
			  
			  $dis_text2 = " 총 : " . $sum[0] . " (SET),  본천장 : " . $sum[1] . " ,  L/C : "  . $sum[2] . "  , 기타 : "  . $sum[3] ; 			   			  			  
			  
		      $main_draw_arr="";			  
			  if(substr($main_draw,0,2)=="20")  $main_draw_arr= iconv_substr($main_draw,5,5,"utf-8");		    
			     elseif((int)$bon_su<1) $main_draw_arr= "X";		    
   
   		        $lc_draw_arr="";			  
			  if(substr($lc_draw,0,2)=="20")  $lc_draw_arr= iconv_substr($lc_draw,5,5,"utf-8") ;
			     elseif((int)$lc_su<1) $lc_draw_arr = "X";	
			  if($type=='011'||$type=='012'|| $type=='013D'||$type=='025'||$type=='017'||$type=='014'||$type=='037')
			                         $lc_draw_arr = "X";	
             // 본천장 등 수량 있으면 표시하는 변수 $list
			  $list='';			   
			  if((int)$bon_su>0)
				  $list .= ' 본:' . $bon_su;			   
			  if((int)$lc_su>0)
				  $list .= ' LC:' . $lc_su;			   
			  if((int)$etc_su + (int)$air_su>0)
				  $list .= ' 기타:' . (string)((int)$etc_su + (int)$air_su) ;
			  
			$paintcondition = 1; 
			$donecondition = 1; 		
			$paintlist = '&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;   ';
			$donelist = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   ';
			
		    // 도장 완료
			  if ( (( $main_draw!='' and $mainpainting_date!='' and (int)$bon_su>0 )  or  $main_draw_arr== "X" ) and  ( ( $lc_draw!='' and $lcpainting_date!=''  and (int)$lc_su>0 )  or  $lc_draw_arr== "X" ) or  ( $etcpainting_date!='' and (int)$etc_su > 0 ) )
				    $paintcondition = 0; 
											
		    // 조립 완료
			if ( (( $main_draw!='' and $mainassembly_date!='' and (int)$bon_su>0 )  or  $main_draw_arr== "X" ) and  ( ( $lc_draw!='' and $lcassembly_date!=''  and (int)$lc_su>0 )  or  $lc_draw_arr== "X" ) or  ( $etcassembly_date!='' and (int)$etc_su > 0 ) )
				    $donecondition = 0; 
				
			// 도장완료
			if(!$paintcondition)
			    {
					   $paintlist = '완료';					   
				}
				// 제작완료				
			  if(!$donecondition)
			    {					   
					   $donelist = '완료';
				}			  
			  
			  if($workday!='') $workday=substr($row["workday"],5,5);

		 ?>			
			<tr onclick="redirectToView_ceiling('<?=$num?>')">				
				<td class="text-center"> <?=$paintlist?> / <?=$donelist?> </td>
				<?php
					$display_workplacename = (mb_strlen($workplacename, 'UTF-8') > 8) ? mb_substr($workplacename, 0, 8, 'UTF-8') . '..' : $workplacename;					
					$display_secondord = (mb_strlen($secondord, 'UTF-8') > 8) ? mb_substr($secondord, 0, 8, 'UTF-8') . '..' : $secondord;
					$display_type = (mb_strlen($type, 'UTF-8') > 8) ? mb_substr($type, 0, 8, 'UTF-8') . '..' : $type;
					$display_list = (mb_strlen($list, 'UTF-8') > 8) ? mb_substr($list, 0, 8, 'UTF-8') . '..' : $list;
					$display_memo = (mb_strlen($memo, 'UTF-8') > 8) ? mb_substr($memo, 0, 8, 'UTF-8') . '..' : $memo;
				?>
				<td title="<?=htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8')?>"><?= $display_workplacename ?> </td>				
				<td class="text-center" title="<?=htmlspecialchars($secondord, ENT_QUOTES, 'UTF-8')?>"> <?= $display_secondord ?> </td>
				<td class="text-center" title="<?=htmlspecialchars($type, ENT_QUOTES, 'UTF-8')?>"> <?= $display_type ?> </td>				
				<td class="text-center" title="<?=htmlspecialchars($list, ENT_QUOTES, 'UTF-8')?>"> <?= $display_list ?> </td>
				<td class="text-start" title="<?=htmlspecialchars($memo, ENT_QUOTES, 'UTF-8')?>"> <?= $display_memo ?> </td>
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
	   금일은 천장 출고 예정이 없습니다. 
</div>
<?php endif; ?>

<script> 

function redirectToView_ceiling(num) {
	popupCenter("./ceiling/view.php?menu=no&num=" + num, "조명천장 수주내역", 1700, 900);	  
    
}

function dis_text2()
{  
	var dis_text = <?php echo json_encode($dis_text2 ?? ''); ?>;
	$(".dis_text2").text(dis_text);
}	

  // 쟘 합계 화면에 출력하기
setTimeout(function() {
	 // console.log('Works!');
	 dis_text2();
}, 1000);

$(document).ready(function(){
	
	$(".batchBtn").off('click').on('click', function(){
		popupCenter('./ceiling/batchDB_invoice.php','묶음출고증',1800,780);  
	});
	$(".mobileBtn").off('click').on('click', function(){
		popupCenter('./mceiling/list.php','모바일 관리화면',1920,1000);  
	});

	$(".laserceilingplanBtn").off('click').on('click', function(){  
		window.open('/ceiling/plan_cutandbending.php','레이져가공일정','left=50,top=50, scrollbars=yes, toolbars=no,width=1850,height=970'); 		  
	});	
	
});


</script> 
  
