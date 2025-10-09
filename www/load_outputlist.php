<?php

  require_once("./lib/mydb.php");
  $pdo = db_connect();	
 
 // 기간을 정하는 구간
$fromdate=date("Y-m-d");  
$total_sum=0; 
$total_m2=0; 

$sql="select * from mirae8440.output where outdate  between date('$fromdate') and date('$fromdate') order by num desc"; 					
	                         
$nowday=date("Y-m-d");   // 현재일자 변수지정          					 

$start_num=1;	
			?>
	  <div class="clear"> </div>	
	  <div id="top_board"> <div id="top_board0">금일 (주일/경동) 출고 리스트 </div> <div id="top_board1"> 스크린 총 틀수 :  </div>  <div id="top_board2"> <?=$total_sum?>  </div> <div id="top_board3"> 스크린 면적(㎡) 합 :  </div> <div id="top_board4"> <?=$total_m2?> </div> </div>
	  <div class="clear"> </div>	     
	 <div id="output_top_title">
      <div id="output_title1"> 번호 </div>
      <div id="output_title2"> 출고일자 </a> </div>     <!-- 출고일자 -->
      <div id="output_title33"> 상태 </a> </div>     <!-- 상태 -->
      <div id="output_title7"> 현 장 명 </div>     
      <div id="output_title8"> 수신처 </div>     
      <div id="output_title9"> 수신 주소 </div>     
      <div id="output_title16"> 스크린틀수  </div>      
      <div id="output_title15"> 스크린면적(㎡)  </div>      
      <div id="output_title12"> 비 고    </div>      
      </div>
      <div id="list_content">
			<?php     
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp1;     // 전체 글수	  		    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $item_num=$row["num"];
			  $outdate=$row["outdate"];
			  $item_indate=$row["indate"];
			  $item_orderman=$row["orderman"];
			  $item_outworkplace=$row["outworkplace"];
			  $item_outputplace=$row["outputplace"];
			  $item_receiver=$row["receiver"];
			  $item_phone=$row["phone"];
			  $item_comment=$row["comment"];	  
			  $root=$row["root"];	  
			  $steel=$row["steel"];	  
			  $motor=$row["motor"];	  
			  $delivery=$row["delivery"];	  
			  $regist_state=$row["regist_state"];	 

			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$outdate) 
                            $date_font="red";
								
							  
 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
}  
							switch ($regist_state) {
								case   "1"     :  $font_state="black"; $regist_word="등록"; break;
								case   "2"     :  $font_state="red"  ; $regist_word="접수"; break;	
								case   "3"     :  $font_state="blue"  ; $regist_word="완료"; break;	
								default:  $regist_word="등록"; break;
							}					  
			 ?>
				<div id="outlist_item" > 
			    <div id="outlist_item1"><a href="./output/view.php?num=<?=$item_num?>&page=1&fromdate=<?=$fromdate?>&todate=<?=$fromdate?>&separate_date=1" >
				<?=$start_num ?></div>			
			    <div id="outlist_item2" style="color:<?=$date_font?>;">
				<b> <?=substr($outdate,0,15)?></b></div>
		     	<div id="outlist_item33"style="color:<?=$font_state?>;" > <?=$regist_word?> </div>			
				<div id="outlist_item7"> <?=iconv_substr($item_outworkplace,0,14,"utf-8")?> </div>
				<div id="outlist_item8"><?=iconv_substr($item_receiver,0,8,"utf-8")?></div>
				<div id="outlist_item9"><?=iconv_substr($item_outputplace,0,20,"utf-8")?></div>
				<?php
		
		
// 면적구하는 부분
// 출고번호를 추적한다. 
$upnum=$item_num;
$sqlTemp_orderlist="select * from mirae8440.orderlist  where outputnum='$item_num' limit 1";	 // 처음 내림차순

  
  try{  
	  $stmh_temp = $pdo->query($sqlTemp_orderlist);            // 검색조건에 맞는글 stmh
      $counter=0;
	  $m2=0;
	  $sum=0;
	       while($row_temp = $stmh_temp->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row_temp["num"];			  
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
$sqlTemp="select * from mirae8440.make  where upnum='$upnum' order by num desc";	 // 처음 내림차순 
 $parentnum=$upnum; 
	 try{  
	  $m_array=array();
	  $stmh_temp = $pdo->query($sqlTemp);            // 검색조건에 맞는글 stmh
      $counter=0;
	  $m2=0;
	  $sum=0;
	       while($row_temp = $stmh_temp->fetch(PDO::FETCH_ASSOC)) {
			  $upnum=$row_temp["upnum"];			  
if((int)$upnum==(int)$parentnum)
      {	
			  $counter++;
			 $number=$row_temp["number"];  
			 $x=(int)$row_temp["cutwidth"];  
			 $y=(int)$row_temp["cutheight"];  
			 $z=(int)$row_temp["number"];  
			 $m_array[$counter]=$x/1000 * $y/1000 * $z;
			 $m2 += $m_array[$counter];
			 $sum+=(int)$number;
   			 }
	   }		
     $total_sum+=$sum;   // 화면누계작성
     $total_m2+=$m2;
     $total_m2=number_format((float)$total_m2, 2, '.', '');	 
   } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
 $m2=number_format((float)$m2, 2, '.', '');
			if($sum>0) 
				print '<div id="outlist_item16">' . iconv_substr($sum,0,3, "utf-8") . '</div>';
				else
					print '<div id="outlist_item16">  &nbsp </div>'; 
				
			if($m2>0) 
				print '<div id="outlist_item15">' . iconv_substr($m2,0,8, "utf-8") . '</div>';
				else
					print '<div id="outlist_item15">  &nbsp </div>';
				
				?> 
				<div id="outlist_item12"><?=iconv_substr($item_comment,0,30,"utf-8")?></div></a>
		        <div class="clear"> </div>
				</div>
			<?php
			$start_num++;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  

 ?>

       </div>

<script> 
  // 스크린합 구한값 화면에 출력하기
var total_sum = <?php echo json_encode($total_sum ?? ''); ?>;
var total_m2 = <?php echo json_encode($total_m2 ?? ''); ?>;
$("#top_board2").text(total_sum);
$("#top_board4").text(total_m2);
</script>
