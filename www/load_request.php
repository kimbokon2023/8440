 <?php
require_once __DIR__ . '/bootstrap.php';

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
  if(isset($_REQUEST["separate_date"]))   //출고일 완료일
	 $separate_date=$_REQUEST["separate_date"];	 
	 
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
	  
  require_once("./lib/mydb.php");
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
 
  $scale = 15;       // 한 페이지에 보여질 게시글 수
  $page_scale = 15;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	   
 $sum_title=array(); 
$sum=array();

$sql="select * from mirae8440.eworks where indate='' and is_deleted IS NULL AND eworks_item = '원자재구매' order by outdate desc, num desc" ; 	
 
$nowday=date("Y-m-d");   // 현재일자 변수지정   

	 try{  
// 레코드 전체 sql 설정

   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
 			  $outdate=$row["outdate"];			  
			  
			  $requestdate=$row["requestdate"];
			  $indate=$row["indate"];
			  $outworkplace=$row["outworkplace"];
			  
			  $steel_item=$row["steel_item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $request_comment=$row["request_comment"];
			  $which=$row["which"];	 	

			  
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


   
	 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
	  $total_row = $temp1;     // 전체 글수	  		
	  $request_count = $temp1;     // 전체 검색수	  		
         					 
	
		if($regist_state==null)
			 $regist_state="1";
		 
			  $date_font="black";  // 현재일자 Red 색상으로 표기
			  if($nowday==$outdate) {
                            $date_font="red";
						}
												
								$font="black";
							
							switch ($regist_state) {
								case   "1"     :  $font_state="black"; $regist_word="등록"; break;
								case   "2"     :  $font_state="red"  ; $regist_word="접수"; break;	
								case   "3"     :  $font_state="blue"  ; $regist_word="완료"; break;	
								default:  $regist_word="등록"; break;
							}								
							  
 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
}				 
			?>
			
<style>
    .rounded-card {
        border-radius: 15px !important;  /* 조절하고 싶은 라운드 크기로 설정하세요. */
    }
.table-hover tbody tr:hover {
	cursor: pointer;
}		
</style>			
    <div class="card rounded-card  mb-2 mt-3 " id="requestCard">
        <div class="card-header  text-center ">   
		<span class="text-dark fs-6"> 원자재(철판) 미입고   </span> 
        </div>
        <div class="card-body p-2 m-1 mb-3justify-content-center">	
        <div class="d-flex">	
			
		<table class="table table-bordered table-hover table-sm">
			<thead class="table-primary">	
			 <tr>
			  <th scope="col" class="text-center" style="width:5%;">접수</th>
			  <th scope="col" class="text-center" style="width:8%;">납기(필요일)</th>
			  <th scope="col" class="text-center" style="width:8%;">진행상태</th>
			  <th scope="col" class="text-center" style="width:20%;">현 장 명</th>
			  <th scope="col" class="text-center" style="width:10%;">종류</th>
			  <th scope="col" class="text-center" style="width:10%;">규격</th>
			  <th scope="col" class="text-center" style="width:8%;">수량</th>
			  <th scope="col" class="text-center" style="width:10%;">공급업체</th>
			  <th scope="col" class="text-center" style="width:15%;">비고</th>
		  </tr>
		  </thead>
	 <?php
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		     else 
		      	$start_num=$total_row-($page-1) * $scale;
			
			$request_asked_count = 0;
			$request_send_count = 0;
			$steel_done = 0;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

            include "./request/_row.php";

			// 함수제작
			$indate = NullCheckDate($indate);
			$outdate = NullCheckDate($outdate);
			$requestdate = NullCheckDate($requestdate);  
			
	
 if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
	
	if($outdate!='') $outdate=substr($row["outdate"],5,5);
												
	$blinking = "";
	$font_state = "";
		 if($which=='') $which='1';
	     switch ($which) {
			case   "1"             :      
         	     	$tmp_word="<span class='blink badge bg-primary'> 요청 </span>";		            
					$request_asked_count ++ ;
					break;			      
			case   "2"             :
				   $tmp_word="<span class='badge bg-secondary'> 발주보냄 </span>";		            
				   $request_send_count ++ ;
				   break;			
			default: break;
		}			 
		 		
    if($outdate!='') $outdate=substr($row["outdate"],5,5);		
			
				
}

?>

  <tr onclick="redirectToView_request('<?=$num?>')">
      <td class="text-center"> <?=iconv_substr($outdate,0,15, "utf-8")?>   </td>
      <td class="text-center"> <?=iconv_substr($requestdate,5,5, "utf-8")?>         </td>      
      <td class="text-center   <?=$blinking?>" style="color: <?=$font_state?>;" >	 <?=$tmp_word?>     </td>
      <td class="text-center">   <?=iconv_substr($outworkplace,0,25,"utf-8")?>         </td>
      <td class="text-center">   <?=$steel_item?>         </td>
      <td class="text-center">   <?=$spec?>         </td>
      <td class="text-center">   <?=$steelnum?>         </td>
      <td class="text-center">   <?=$supplier?>         </td>
      <td class="text-center">   <?=iconv_substr($request_comment,0,25,"utf-8")?>         </td>
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
  </div>

<script> 

function redirectToView_request(num) {
	popupCenter("./request/view.php?menu=no&num=" + num, "원자재 주문", 1700, 900);	  
    
}

</script> 
  