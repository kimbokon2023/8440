 <?php 
	  
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
 
  $scale = 10;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
    
  if($separate_date=="") $separate_date="1";
 

if($separate_date=="1") $SettingDate="outdate ";
    else
		 $SettingDate="indate ";

$a=" order by num desc limit $first_num, $scale";
$b=" order by num desc ";
  
 // 전체합계(입고부분)를 산출하는 부분 

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
			  $model=$row["model"];	 
			  
			  $tmp=$item . $spec;	  

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
			  $model=$row["model"];	 			  
			  
			  $tmp=$item . $spec;  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
  

  if($mode=="") {
							 $sql="select * from mirae8440.steel " . $a; 					
	                         $sqlcon = "select * from mirae8440.steel " . $b;   // 전체 레코드수를 파악하기 위함.					
                }		
				         
   
$nowday=date("Y-m-d");   // 현재일자 변수지정   



$common="   where " . $SettingDate . " between date('$fromdate') and date('$Transtodate') order by " . $SettingDate;
$a= $common . " desc, num desc limit $first_num, $scale";    //내림차순
$b= $common . " desc, num desc ";    //내림차순 전체

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
			  $model=$row["model"];	 			  
			  
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
							  
	
			 
?>

<style>

.rounded-card {
	border-radius: 15px !important;  /* 조절하고 싶은 라운드 크기로 설정하세요. */
}
.table-hover tbody tr:hover {
	cursor: pointer;
}		
	
</style>			
			
	<div class="container mt-2">
    <div class="card rounded-card">
        <div class="card-body p-2 m-1 mb-1 mt-1 d-flex justify-content-center">     
            <h5> 원자재 입출고 현황 </h5>
        </div>
        <div class="card-body p-2 m-1 mb-3  d-flex justify-content-center">	

<table class="table table-bordered table-hover table-sm">
  <thead class="thead-dark">
    <tr>
      <th scope="col" class="text-center">입출고</th>
      <th scope="col" class="text-center">구분</th>
      <th scope="col" class="text-center">현장명</th>
      <th scope="col" class="text-center">모델</th>
      <th scope="col" class="text-center">종류</th>
      <th scope="col" class="text-center">규격</th>
      <th scope="col" class="text-center">수량</th>
      <th scope="col" class="text-center">비고</th>
    </tr>
  </thead>
 
 <tbody>
 
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
        if($outdate!='0000-00-00' || $outdate!='')
			   $basicdate = $outdate;
		   else
			   $basicdate = $indate;
	
	      $basicdate=substr($basicdate,5,5);				
											
	     if($which=='1')
		       {
               $tmp_word="입고";
			   $font_state="black";
			   }
               else
			   {
	               $tmp_word="출고";
			       $font_state="red";				   
			   }
								
				?>			     
				
    <tr>
      <td class="text-center">
        <a href="./steel/view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" target="_blank" onclick="window.open(this.href, 'newwindow', 'width=1400,height=700'); return false;">
          <?=$basicdate?>
        </a>
      </td>
      <td class="text-center">
        <a href="./steel/view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" target="_blank" onclick="window.open(this.href, 'newwindow', 'width=1400,height=700'); return false;">
          <?=$tmp_word?>
        </a>
      </td>
      <td class="text-center">
        <a href="./steel/view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" target="_blank" onclick="window.open(this.href, 'newwindow', 'width=1400,height=700'); return false;">
          <?=$outworkplace?>
        </a>
      </td>
      <td class="text-center">
        <a href="./steel/view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" target="_blank" onclick="window.open(this.href, 'newwindow', 'width=1400,height=700'); return false;">
          <?=$model?>
        </a>
      </td>
      <td class="text-center">
        <a href="./steel/view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" target="_blank" onclick="window.open(this.href, 'newwindow', 'width=1400,height=700'); return false;">
          <?=$item?>
        </a>
      </td>
      <td class="text-center">
        <a href="./steel/view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" target="_blank" onclick="window.open(this.href, 'newwindow', 'width=1400,height=700'); return false;">
          <?=$spec?>
        </a>
      </td>
      <td class="text-center">
        <a href="./steel/view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" target="_blank" onclick="window.open(this.href, 'newwindow', 'width=1400,height=700'); return false;">
          <?=$steelnum?>
        </a>
      </td>
      <td class="text-center">
        <a href="./steel/view.php?num=<?=$num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>" target="_blank" onclick="window.open(this.href, 'newwindow', 'width=1400,height=700'); return false;">
          <?=iconv_substr($comment,0,13,"utf-8")?>
        </a>
      </td>
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
