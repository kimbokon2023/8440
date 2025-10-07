 <?php session_start(); ?>
  
 <meta charset="utf-8">
 <?php
  if(!isset($_SESSION["userid"])) {
 ?>
  <script>
        alert('로그인 후 이용해 주세요.');
        history.back();
  </script>
 <?php
  }

  if(isset($_REQUEST["page"]))
    $page=$_REQUEST["page"];
  else 
    $page=1;   // 1로 설정해야 함
 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

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
  
  if(isset($_REQUEST["asprocess"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $asprocess=$_REQUEST["asprocess"];
  else
   $asprocess="전체";

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	       

if(isset($_REQUEST["scale"])) 
     $scale=$_REQUEST["scale"];  // 화면표시 목록수	      
		  
  $content="";
/*  $page=$_REQUEST["page"];
  $list=$_REQUEST["list"];
  $search=$_REQUEST["search"];
  $find=$_REQUEST["find"]; */
  
  $condate=$_REQUEST["condate"];     // form전송으로 하는 경우만 _REQUEST로 받고, a href 태그로 보낸 것은 PDO를 통해 $row문장으로 자료를 갖고 온다. 기억하자.
  $condate1=$_REQUEST["condate1"];     // form전송으로 하는 경우만 _REQUEST로 받고, a href 태그로 보낸 것은 PDO를 통해 $row문장으로 자료를 갖고 온다. 기억하자.
  $condate2=$_REQUEST["condate2"];     // form전송으로 하는 경우만 _REQUEST로 받고, a href 태그로 보낸 것은 PDO를 통해 $row문장으로 자료를 갖고 온다. 기억하자.
  $estimate1=$_REQUEST["estimate1"];
  $estimate2=$_REQUEST["estimate2"];
  $estimate3=$_REQUEST["estimate3"];
  $estimate4=$_REQUEST["estimate4"];
  
  $bill1=$_REQUEST["bill1"];
  $bill2=$_REQUEST["bill2"];
  $bill3=$_REQUEST["bill3"];
  $bill4=$_REQUEST["bill4"];
  $bill5=$_REQUEST["bill5"];
  $bill6=$_REQUEST["bill6"];

  $billdate1=$_REQUEST["billdate1"];
  $billdate2=$_REQUEST["billdate2"];
  $billdate3=$_REQUEST["billdate3"];
  $billdate4=$_REQUEST["billdate4"];
  $billdate5=$_REQUEST["billdate5"];
  $billdate6=$_REQUEST["billdate6"];  
  
  $deposit1=$_REQUEST["deposit1"];
  $deposit2=$_REQUEST["deposit2"];
  $deposit3=$_REQUEST["deposit3"];
  $deposit4=$_REQUEST["deposit4"];
  $deposit5=$_REQUEST["deposit5"];
  $deposit6=$_REQUEST["deposit6"];
  
  $depositdate1=$_REQUEST["depositdate1"];
  $depositdate2=$_REQUEST["depositdate2"];
  $depositdate3=$_REQUEST["depositdate3"];
  $depositdate4=$_REQUEST["depositdate4"];
  $depositdate5=$_REQUEST["depositdate5"];
  $depositdate6=$_REQUEST["depositdate6"];  
  
  $claimdate1=$_REQUEST["claimdate1"];
  $claimdate2=$_REQUEST["claimdate2"];
  $claimdate3=$_REQUEST["claimdate3"];
  $claimdate4=$_REQUEST["claimdate4"];
  $claimdate5=$_REQUEST["claimdate5"];  
  $claimdate6=$_REQUEST["claimdate6"];  
  
  $claimamount1=$_REQUEST["claimamount1"];
  $claimamount2=$_REQUEST["claimamount2"];
  $claimamount3=$_REQUEST["claimamount3"];
  $claimamount4=$_REQUEST["claimamount4"];
  $claimamount5=$_REQUEST["claimamount5"];
  $claimamount6=$_REQUEST["claimamount6"];
  $claimamount7=$_REQUEST["claimamount7"];
  
  $claimbalance1=$_REQUEST["claimbalance1"];
  $claimbalance2=$_REQUEST["claimbalance2"];
  $claimbalance3=$_REQUEST["claimbalance3"];
  $claimbalance4=$_REQUEST["claimbalance4"];
  $claimbalance5=$_REQUEST["claimbalance5"];
  $claimbalance6=$_REQUEST["claimbalance6"];
  $claimbalance7=$_REQUEST["claimbalance7"];

  $claimfix1=$_REQUEST["claimfix1"];
  $claimfix2=$_REQUEST["claimfix2"];
  $claimfix3=$_REQUEST["claimfix3"];
  $claimfix4=$_REQUEST["claimfix4"];
  $claimfix5=$_REQUEST["claimfix5"];
  $claimfix6=$_REQUEST["claimfix6"];
  $claimfix7=$_REQUEST["claimfix7"];
  
  $claimperson=$_REQUEST["claimperson"];
  $claimtel=$_REQUEST["claimtel"];
  $receivable=$_REQUEST["receivable"];
  $workplacename=$_REQUEST["workplacename"];
  $chargedperson=$_REQUEST["chargedperson"];
  $address=$_REQUEST["address"];
  $firstord=$_REQUEST["firstord"];
  $firstordman=$_REQUEST["firstordman"];
  $firstordmantel=$_REQUEST["firstordmantel"];
  $secondord=$_REQUEST["secondord"];
  $secondordman=$_REQUEST["secondordman"];
  $secondordmantel=$_REQUEST["secondordmantel"];
  $worklist=$_REQUEST["worklist"];
  $motormaker=$_REQUEST["motormaker"];
  $power=$_REQUEST["power"];
  $workday=$_REQUEST["workday"];
  $worker=$_REQUEST["worker"];
  $endworkday=$_REQUEST["endworkday"];
  $cableday=$_REQUEST["cableday"];
  $cablestaff=$_REQUEST["cablestaff"];
  $endcableday=$_REQUEST["endcableday"];  
  $asday=$_REQUEST["asday"];
  $asorderman=$_REQUEST["asorderman"];
  $asordermantel=$_REQUEST["asordermantel"];
  $aslist=$_REQUEST["aslist"];
  $asresult=$_REQUEST["asresult"];
  $ashistory=$_REQUEST["ashistory"];
  $comment=$_REQUEST["comment"];
  
  $totalbill=$_REQUEST["totalbill"];
  $accountnote=$_REQUEST["accountnote"];
  $asproday=$_REQUEST["asproday"];
  $asendday=$_REQUEST["asendday"];
  $asman=$_REQUEST["asman"];
  $work_state=$_REQUEST["work_state"];
  $as_state=$_REQUEST["as_state"];
  $sum_receivable=$_REQUEST["sum_receivable"];
  $sum_deposit=$_REQUEST["sum_deposit"];
  $sum_claimamount=$_REQUEST["sum_claimamount"];
  $sum_bill=$_REQUEST["sum_bill"];
  $sum_estimate=$_REQUEST["sum_estimate"];
  $as_refer=$_REQUEST["as_refer"];  
  $change_worklist=$_REQUEST["change_worklist"];
  $checkbox=$_REQUEST["checkbox"];
  $checkstep=$_REQUEST["checkstep"];
  $asfee=$_REQUEST["asfee"];
  $asfee_estimate=$_REQUEST["asfee_estimate"];
  $promiseday=$_REQUEST["promiseday"];
  $as_check=$_REQUEST["as_check"];  
  $outputmemo=$_REQUEST["outputmemo"];  
  $aswriter=$_REQUEST["aswriter"];  
  $setdate=$_REQUEST["setdate"];  
  
  $subject=$workplacename;
  
 $all_check=$_REQUEST["all_check"];	 // 시공완료일 무시 전체선택 
  
  $sum=[];
  $sum[0]=$_REQUEST["estimate1"];
  $sum[1]=$_REQUEST["estimate2"];
  $sum[2]=$_REQUEST["estimate3"];
  $sum[3]=$_REQUEST["estimate4"];  

for($i=0;$i<=3;$i++)
{
 if($sum[$i]!="") $sumhap=preg_replace("/[^0-9]*/s","",$sum[$i]);
}   

  $sum_estimate=$sumhap;
  
   $sumbill=[];
  $sumdeposit=[];
  $sumbill[0]=preg_replace("/[^0-9]*/s","",$bill1); 
  $sumbill[1]=preg_replace("/[^0-9]*/s","",$bill2);
  $sumbill[2]=preg_replace("/[^0-9]*/s","",$bill3);
  $sumbill[3]=preg_replace("/[^0-9]*/s","",$bill4);
  $sumbill[4]=preg_replace("/[^0-9]*/s","",$bill5);
  $sumbill[5]=preg_replace("/[^0-9]*/s","",$bill6);
  $sumdeposit[0]=preg_replace("/[^0-9]*/s","",$deposit1);
  $sumdeposit[1]=preg_replace("/[^0-9]*/s","",$deposit2);
  $sumdeposit[2]=preg_replace("/[^0-9]*/s","",$deposit3);
  $sumdeposit[3]=preg_replace("/[^0-9]*/s","",$deposit4);
  $sumdeposit[4]=preg_replace("/[^0-9]*/s","",$deposit5);
  $sumdeposit[5]=preg_replace("/[^0-9]*/s","",$deposit6); 
  
  $total_bill=0;
  $total_deposit=0;
  for($i=0;$i<=5;$i++)
  {
	  $total_bill +=$sumbill[$i];
	  $total_deposit +=$sumdeposit[$i];
  }
$total_receivable=$sumhap-$total_deposit;  
$sum_receivable=$total_receivable;
$sum_bill=$total_bill;
$sum_deposit=$total_deposit;
$total_receivable=number_format($total_receivable);
//$vat_total_bill=$total_bill+$total_bill*0.1;
$total_bill=number_format($total_bill);
//$vat_total_deposit=$total_deposit+$total_deposit*0.1;
$total_deposit=number_format($total_deposit);

// 계약일이 없으면 계약전 checkbox 1;

  if(substr($condate,0,2)=="20") $checkbox="0";
           else
			   $checkbox="1";
    
  $a=[];
  $b=[];
  $c=[];
  $a[0]=preg_replace("/[^0-9]*/s","",$claimamount1);     // (float)로 해도 문자에 콤마등이 있으면 변환이 안된다. 옆의 함수를 사용해야 숫자계산이 된다. 필독@@
  $a[1]=preg_replace("/[^0-9]*/s","",$claimamount2);  
  $a[2]=preg_replace("/[^0-9]*/s","",$claimamount3);  
  $a[3]=preg_replace("/[^0-9]*/s","",$claimamount4);  
  $a[4]=preg_replace("/[^0-9]*/s","",$claimamount5);  
  $a[5]=preg_replace("/[^0-9]*/s","",$claimamount6);  
  
  $c[0]=preg_replace("/[^0-9]*/s","",$claimfix1);     // (float)로 해도 문자에 콤마등이 있으면 변환이 안된다. 옆의 함수를 사용해야 숫자계산이 된다. 필독@@
  $c[1]=preg_replace("/[^0-9]*/s","",$claimfix2);  
  $c[2]=preg_replace("/[^0-9]*/s","",$claimfix3);  
  $c[3]=preg_replace("/[^0-9]*/s","",$claimfix4);  
  $c[4]=preg_replace("/[^0-9]*/s","",$claimfix5);  
  $c[5]=preg_replace("/[^0-9]*/s","",$claimfix6);  
  
  
  $total_claimamount=0;
  $total_claimfix=0;
  $total_balance=0;
  
  for($i=0;$i<=5;$i++)  // 6개 데이터 합계
  {
	  if($a[$i]>0)
	  {
          $b[$i]=0; 
	      $total_claimamount +=$a[$i];
	      $total_claimfix +=$c[$i];
		  $total_balance= $sumhap - $total_claimamount;
	      $b[$i] = $total_balance;
	   }	  
	   else 
	   
		   $b[$i]=0;	   
  }  
  
 $claimbalance1=number_format($b[0]);
 $claimbalance2=number_format($b[1]);
 $claimbalance3=number_format($b[2]);
 $claimbalance4=number_format($b[3]);
 $claimbalance5=number_format($b[4]);
 $claimbalance6=number_format($b[5]);
  
  $claimamount7=number_format($total_claimamount);
  $sum_claimamount=$total_claimamount;    
  $sum_claimfix=$total_claimfix;
  $claimfix7=number_format($total_claimfix);
  $claimbalance7=number_format($total_balance);		
		
 // 진행상황 저장
                              $state_work=0;
							  if(substr($condate,0,2)=="20") $state_work=1;
							  if(substr($workday,0,2)=="20") $state_work=2;
							  if(substr($endworkday,0,2)=="20") $state_work=3;
							  if(substr($cableday,0,2)=="20") $state_work=4;
							  if(substr($endcableday,0,2)=="20") $state_work=5;			
							  
							  $font="black";
							  switch ($state_work) {
											case 1: $work_state="착공전"; $font="blue"; break;
											case 2: $work_state="시공중"; $font="blue"; break;
											case 3: $work_state="결선대기"; $font="brown"; break;
											case 4: $work_state="결선중"; $font="grey"; break;
											case 5: $work_state="결선완료"; $font="red";break;											
											default: $work_state="계약전"; 
										}		


              $state_as=0;    // AS 색상 등 표현하는 계산 
			  if(substr($asday,0,2)=="20") $state_as=1;
			  if(substr($asproday,0,2)=="20") $state_as=2;
			  if(substr($asendday,0,2)=="20") $state_as=3;			  
			  
			  $font_as="black";
			  switch ($state_as) {
							case 1: $as_state="접수완료"; $font_as="blue"; break;
							case 2: $as_state="처리예약"; $font_as="grey"; break;
							case 3: $as_state="처리완료"; $font_as="red"; break;							
						    default: $as_state="미접수"; 
						}



  $files = $_FILES["upfile"];    //첨부파일	
  $count = count($files["name"]); 
  if($count>0)
  {
  $upload_dir = '../uploads/';   //물리적 저장위치    
 /*     $file[0]="";
     $file[1]="";
  */
  for ($i=0; $i<$count; $i++)
      {
			 $upfile_name[$i]     = $files["name"][$i];         //교재 190페이지 참조
			 $upfile_tmp_name[$i] = $files["tmp_name"][$i];
			 $upfile_type[$i]     = $files["type"][$i];
			 $upfile_size[$i]     = $files["size"][$i];
			 $upfile_error[$i]    = $files["error"][$i];
			 $file = explode(".", $upfile_name[$i]);
			 $file_name = $file[0];
			 $file_ext  = $file[1];

			 if (!$upfile_error[$i])
			 {
			$new_file_name = date("Y_m_d_H_i_s");
			$new_file_name = $new_file_name."_".$i;
			$copied_file_name[$i] = $new_file_name.".".$file_ext;      
			$uploaded_file[$i] = $upload_dir . $copied_file_name[$i];

			if( $upfile_size[$i]  > 12000000 ) {

			print("
			 <script>
			   alert('업로드 파일 크기가 지정된 용량(10MB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
			   history.back();
			 </script>
			");
			exit;
			}
/* 				if ( ($upfile_type[$i] != "image/gif") && ($upfile_type[$i] != "image/jpeg"))
			{
			print(" <script>
					  alert('JPG와 GIF 이미지 파일만 업로드 가능합니다!');
					  history.back();
					</script>");
			exit;
			} */
			if (!move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i]) )
			{
			print("<script>
					alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
					history.back();
				  </script>");
			 exit;
			}
/* 			
			echo "파일 전송된 이름은  {$upfile_tmp_name[$i]} "; */
			  }
      }
  }      
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 

    
 if ($mode=="modify"){
     $num_checked = count($_REQUEST['del_file']);
     $position = $_REQUEST['del_file'];
 
     for($i=0; $i<$num_checked; $i++)      // delete checked item
     {
	 $index = $position[$i];
	 $del_ok[$index] = "y";
     }
      
     try{
        $sql = "select * from chandj.work where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
       
			  for ($i=0; $i<$count; $i++)						
			  {
				   $field_org_name = "file_name_".$i;
				   $field_real_name = "file_copied_".$i;
				$org_name_value = $upfile_name[$i];
				$org_real_value = $copied_file_name[$i];
				if ($del_ok[$i] == "y")
				{
				$delete_field = "file_copied_".$i;
				$delete_name = $row[$delete_field];
						
				$delete_path = $upload_dir . $delete_name;
				unlink($delete_path);
						
					   try{
						   $pdo->beginTransaction(); 
						   $sql = "update chandj.work set $field_org_name = ?, $field_real_name = ?  where num=?";
						   $stmh = $pdo->prepare($sql); 
						   $stmh->bindValue(1, $org_name_value, PDO::PARAM_STR); 
						   $stmh->bindValue(2, $org_real_value, PDO::PARAM_STR);  
						   $stmh->bindValue(3, $num, PDO::PARAM_STR);     
						   $stmh->execute();
						   $pdo->commit(); 
						} catch (PDOException $Exception) {
							$pdo->rollBack();
							print "오류: ".$Exception->getMessage();
						}             
				}  else  {
				  if (!$upfile_error[$i])
				  {
						   try{
					  $pdo->beginTransaction(); 
					  $sql = "update chandj.work set $field_org_name = ?, $field_real_name = ?  where num=?";
							  $stmh = $pdo->prepare($sql); 
							  $stmh->bindValue(1, $org_name_value, PDO::PARAM_STR);  
							  $stmh->bindValue(2, $org_real_value, PDO::PARAM_STR);  
							  $stmh->bindValue(3, $num, PDO::PARAM_STR);     
							  $stmh->execute();
							  $pdo->commit(); 
							  } catch (PDOException $Exception) {
								 $pdo->rollBack();
								 print "오류: ".$Exception->getMessage();
							  }      					
					}
				}
			  }
			  
	/* 	print "접속완료"	  ; */
     try{
        $pdo->beginTransaction();   
        $sql = "update chandj.work set subject=?, content=?, is_html=?, estimate1=?, estimate2=?,estimate3=?,estimate4=?,";
        $sql .= "bill1=?, bill2=?, bill3=?, bill4=?, bill5=?, bill6=?, receivable=?, workplacename=?, chargedperson=?, ";
        $sql .= "address=? ,firstord=? ,firstordman=? ,firstordmantel=? ,secondord=?,";
        $sql .= "secondordman=? ,secondordmantel=? ,worklist=? ,motormaker=?, power=? ,";
        $sql .= "workday=? ,worker=? ,cableday=? ,cablestaff=? ,";
        $sql .= "asday=? ,asorderman=? ,asordermantel=? ,aslist=? ,asresult=? ,ashistory=? ,comment=?, condate=? ,";        
        $sql .= "billdate1=?, billdate2=?, billdate3=?, billdate4=?, billdate5=?, billdate6=?, endworkday=?, endcableday=? ,";        
        $sql .= "depositdate1=?, depositdate2=?, depositdate3=?, depositdate4=?, depositdate5=?, depositdate6=?,";        
        $sql .= "deposit1=?, deposit2=?, deposit3=?, deposit4=?, deposit5=?, deposit6=?, ";          
        $sql .= "totalbill=? , accountnote=?, asproday=?, asendday=?, asman=?,";        
        $sql .= "claimdate1=?, claimdate2=?, claimdate3=?, claimdate4=?, claimdate5=?, claimdate6=?,";        
        $sql .= "claimamount1=?, claimamount2=?, claimamount3=?, claimamount4=?, claimamount5=?, claimamount6=?, claimamount7=?,";        
        $sql .= "claimbalance1=?, claimbalance2=?, claimbalance3=?, claimbalance4=?, claimbalance5=?, claimbalance6=?, claimbalance7=?,";        		
        $sql .= "claimperson=?, claimtel=?, work_state=?, as_state=?, regist_day=?, sum_bill=?, sum_receivable=?, sum_deposit=?, sum_claimamount=?, sum_estimate=?, as_refer=?,";            
        $sql .= "change_worklist=?, checkbox=?, checkstep=?, asfee=?, asfee_estimate=?, promiseday=?, as_check=?, outputmemo=?, aswriter=?, setdate=?,";            
        $sql .= "claimfix1=?, claimfix2=?, claimfix3=?, claimfix4=?, claimfix5=?, claimfix6=?, claimfix7=?, condate1=?, condate2=? where num=?  LIMIT 1";            
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $subject, PDO::PARAM_STR);  
     $stmh->bindValue(2, $content, PDO::PARAM_STR);  
     $stmh->bindValue(3, $html_ok, PDO::PARAM_STR);       
		
     $stmh->bindValue(4, $estimate1, PDO::PARAM_STR);        
     $stmh->bindValue(5, $estimate2, PDO::PARAM_STR);        
     $stmh->bindValue(6, $estimate3, PDO::PARAM_STR);        
     $stmh->bindValue(7, $estimate4, PDO::PARAM_STR);        
     $stmh->bindValue(8, $bill1, PDO::PARAM_STR);        
     $stmh->bindValue(9, $bill2, PDO::PARAM_STR);        
     $stmh->bindValue(10, $bill3, PDO::PARAM_STR);        
     $stmh->bindValue(11, $bill4, PDO::PARAM_STR);        
     $stmh->bindValue(12, $bill5, PDO::PARAM_STR);        
     $stmh->bindValue(13, $bill6, PDO::PARAM_STR);        
     $stmh->bindValue(14, $receivable, PDO::PARAM_STR);        
     $stmh->bindValue(15, $workplacename, PDO::PARAM_STR);        
     $stmh->bindValue(16, $chargedperson, PDO::PARAM_STR);        
     $stmh->bindValue(17, $address, PDO::PARAM_STR);        
     $stmh->bindValue(18, $firstord, PDO::PARAM_STR);        
     $stmh->bindValue(19, $firstordman, PDO::PARAM_STR);        
     $stmh->bindValue(20, $firstordmantel, PDO::PARAM_STR);        
     $stmh->bindValue(21, $secondord, PDO::PARAM_STR);
     $stmh->bindValue(22, $secondordman, PDO::PARAM_STR);
     $stmh->bindValue(23, $secondordmantel, PDO::PARAM_STR);
     $stmh->bindValue(24, $worklist, PDO::PARAM_STR);
     $stmh->bindValue(25, $motormaker, PDO::PARAM_STR);
     $stmh->bindValue(26, $power, PDO::PARAM_STR);
     if($workday!="") 	 $workday = date("Y-m-d", strtotime( $workday) );
	 $stmh->bindValue(27, $workday, PDO::PARAM_STR);
     $stmh->bindValue(28, $worker, PDO::PARAM_STR);
     if($cableday!="") $cableday = date("Y-m-d", strtotime( $cableday) );
	 $stmh->bindValue(29, $cableday, PDO::PARAM_STR);
     $stmh->bindValue(30, $cablestaff, PDO::PARAM_STR);
     if($asday!="") $asday = date("Y-m-d", strtotime( $asday) );
	 $stmh->bindValue(31, $asday, PDO::PARAM_STR);
	 $stmh->bindValue(32, $asorderman, PDO::PARAM_STR);
     $stmh->bindValue(33, $asordermantel, PDO::PARAM_STR);
     $stmh->bindValue(34, $aslist, PDO::PARAM_STR);
     $stmh->bindValue(35, $asresult, PDO::PARAM_STR);
     $stmh->bindValue(36, $ashistory, PDO::PARAM_STR);
     $stmh->bindValue(37, $comment, PDO::PARAM_STR);	
	 if($condate!="") $condate = date("Y-m-d", strtotime($condate) );
     $stmh->bindValue(38, $condate, PDO::PARAM_STR);	
	 if($billdate1!="")  $billdate1 = date("Y-m-d", strtotime($billdate1) );
     $stmh->bindValue(39, $billdate1, PDO::PARAM_STR);		
	 if($billdate2!="") $billdate2 = date("Y-m-d", strtotime($billdate2) );
     $stmh->bindValue(40, $billdate2, PDO::PARAM_STR);		
	 if($billdate3!="") $billdate3 = date("Y-m-d", strtotime($billdate3) );
     $stmh->bindValue(41, $billdate3, PDO::PARAM_STR);		
	  if($billdate4!="") $billdate4 = date("Y-m-d", strtotime($billdate4) );
     $stmh->bindValue(42, $billdate4, PDO::PARAM_STR);		
	 if($billdate5!="") $billdate5 = date("Y-m-d", strtotime($billdate5) );
     $stmh->bindValue(43, $billdate5, PDO::PARAM_STR);		
	  if($billdate16="") $billdate6 = date("Y-m-d", strtotime($billdate6) );
     $stmh->bindValue(44, $billdate6, PDO::PARAM_STR);		
	 if($endworkday!="")  $endworkday = date("Y-m-d", strtotime($endworkday) );
     $stmh->bindValue(45, $endworkday, PDO::PARAM_STR);		
	 if($endcableday!="") $endcableday = date("Y-m-d", strtotime($endcableday) );
     $stmh->bindValue(46, $endcableday, PDO::PARAM_STR);	
	 
	if($depositdate1!="")  $depositdate1 = date("Y-m-d", strtotime($depositdate1) );
     $stmh->bindValue(47, $depositdate1, PDO::PARAM_STR);		
	 if($depositdate2!="") $depositdate2 = date("Y-m-d", strtotime($depositdate2) );
     $stmh->bindValue(48, $depositdate2, PDO::PARAM_STR);		
	 if($depositdate3!="") $depositdate3 = date("Y-m-d", strtotime($depositdate3) );
     $stmh->bindValue(49, $depositdate3, PDO::PARAM_STR);		
	 if($depositdate4!="")   $depositdate4 = date("Y-m-d", strtotime($depositdate4) );
     $stmh->bindValue(50, $depositdate4, PDO::PARAM_STR);		
	 if($depositdate5!="") $depositdate5 = date("Y-m-d", strtotime($depositdate5) );
     $stmh->bindValue(51, $depositdate5, PDO::PARAM_STR);		
	 if($depositdate6!="") $depositdate6 = date("Y-m-d", strtotime($depositdate6) );
     $stmh->bindValue(52, $depositdate6, PDO::PARAM_STR);		     
     $stmh->bindValue(53, $deposit1, PDO::PARAM_STR);        
     $stmh->bindValue(54, $deposit2, PDO::PARAM_STR);        
     $stmh->bindValue(55, $deposit3, PDO::PARAM_STR);        
     $stmh->bindValue(56, $deposit4, PDO::PARAM_STR);        
     $stmh->bindValue(57, $deposit5, PDO::PARAM_STR);        
     $stmh->bindValue(58, $deposit6, PDO::PARAM_STR);  	
     $stmh->bindValue(59, $totalbill, PDO::PARAM_STR); 
     $stmh->bindValue(60, $accountnote, PDO::PARAM_STR); 
	 if($asproday!="") $asproday = date("Y-m-d", strtotime($asproday) );
     $stmh->bindValue(61, $asproday, PDO::PARAM_STR);		 
	 if($asendday!="") $asendday = date("Y-m-d", strtotime($asendday) );
     $stmh->bindValue(62, $asendday, PDO::PARAM_STR);	 
     $stmh->bindValue(63, $asman, PDO::PARAM_STR); 	
     $now= $regist_day=date('Y-m-d'); //now(); //////////////////////////////////////////////////////////////////////////// 날짜 함수 처리부분
	 
	 if($claimdate1!="") $claimdate1 = date("Y-m-d", strtotime($claimdate1) );
     $stmh->bindValue(64, $claimdate1, PDO::PARAM_STR);		
	 if($claimdate2!="") $claimdate2 = date("Y-m-d", strtotime($claimdate2) );
     $stmh->bindValue(65, $claimdate2, PDO::PARAM_STR);		
	 if($claimdate3!="") $claimdate3 = date("Y-m-d", strtotime($claimdate3) );
     $stmh->bindValue(66, $claimdate3, PDO::PARAM_STR);		
	 if($claimdate4!="")  $claimdate4 = date("Y-m-d", strtotime($claimdate4) );
     $stmh->bindValue(67, $claimdate4, PDO::PARAM_STR);		
	 if($claimdate5!="") $claimdate5 = date("Y-m-d", strtotime($claimdate5) );
     $stmh->bindValue(68, $claimdate5, PDO::PARAM_STR);		
	 if($claimdate6!="") $claimdate6 = date("Y-m-d", strtotime($claimdate6) );
     $stmh->bindValue(69, $claimdate6, PDO::PARAM_STR);	

     $stmh->bindValue(70, $claimamount1, PDO::PARAM_STR);        
     $stmh->bindValue(71, $claimamount2, PDO::PARAM_STR);        
     $stmh->bindValue(72, $claimamount3, PDO::PARAM_STR);        
     $stmh->bindValue(73, $claimamount4, PDO::PARAM_STR);        
     $stmh->bindValue(74, $claimamount5, PDO::PARAM_STR);        
     $stmh->bindValue(75, $claimamount6, PDO::PARAM_STR);  
     $stmh->bindValue(76, $claimamount7, PDO::PARAM_STR);  
	 $stmh->bindValue(77, $claimbalance1, PDO::PARAM_STR);        
     $stmh->bindValue(78, $claimbalance2, PDO::PARAM_STR);        
     $stmh->bindValue(79, $claimbalance3, PDO::PARAM_STR);        
     $stmh->bindValue(80, $claimbalance4, PDO::PARAM_STR);        
     $stmh->bindValue(81, $claimbalance5, PDO::PARAM_STR);        
     $stmh->bindValue(82, $claimbalance6, PDO::PARAM_STR);  	 
     $stmh->bindValue(83, $claimbalance7, PDO::PARAM_STR);  	 
     $stmh->bindValue(84, $claimperson, PDO::PARAM_STR);  
     $stmh->bindValue(85, $claimtel, PDO::PARAM_STR);    
     $stmh->bindValue(86, $work_state, PDO::PARAM_STR);    
     $stmh->bindValue(87, $as_state, PDO::PARAM_STR);    
	 
	 $stmh->bindValue(88, $regist_day, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(89, $sum_bill, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(90, $sum_receivable, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(91, $sum_deposit, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(92, $sum_claimamount, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(93, $sum_estimate, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(94, $as_refer, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(95, $change_worklist, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(96, $checkbox, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(97, $checkstep, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(98, $asfee, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(99, $asfee_estimate, PDO::PARAM_STR); 	
	 if($promiseday!="") $promiseday = date("Y-m-d", strtotime($promiseday) );
	 $stmh->bindValue(100, $promiseday, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(101, $as_check, PDO::PARAM_STR); 	 	 	 
	 $stmh->bindValue(102, $outputmemo, PDO::PARAM_STR); 	 	 	 
     $stmh->bindValue(103, $aswriter, PDO::PARAM_STR); 	 	 	 
     if($setdate!="") $setdate = date("Y-m-d", strtotime($setdate) );	 
	 $stmh->bindValue(104, $setdate, PDO::PARAM_STR); 	 
     $stmh->bindValue(105, $claimfix1, PDO::PARAM_STR);        
     $stmh->bindValue(106, $claimfix2, PDO::PARAM_STR);        
     $stmh->bindValue(107, $claimfix3, PDO::PARAM_STR);        
     $stmh->bindValue(108, $claimfix4, PDO::PARAM_STR);        
     $stmh->bindValue(109, $claimfix5, PDO::PARAM_STR);        
     $stmh->bindValue(110, $claimfix6, PDO::PARAM_STR);  
     $stmh->bindValue(111, $claimfix7, PDO::PARAM_STR); 
     $stmh->bindValue(112, $condate1, PDO::PARAM_STR); 
     $stmh->bindValue(113, $condate2, PDO::PARAM_STR); 

	 
	 $stmh->bindValue(114, $num, PDO::PARAM_STR);		   //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문  4개 추가 84->88EA
     $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } else	{
	 
	 // 데이터 신규 등록하는 구간
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into chandj.work(id, name, nick, subject, content,is_html, ";
     $sql .= " file_name_0, file_name_1, file_name_2, file_copied_0,  file_copied_1, file_copied_2, ";
	 $sql .= "estimate1,estimate2 ,estimate3,estimate4 ,bill1 ,bill2 ,bill3 ,bill4 ,bill5 ,bill6 ,";
     $sql .= "receivable,workplacename,chargedperson ,address ,firstord ,firstordman ,firstordmantel ,secondord,";
     $sql .= "secondordman,secondordmantel,worklist,motormaker,power,workday,worker,cableday,cablestaff,";
     $sql .= "asday,asorderman,asordermantel,aslist,asresult,ashistory,comment,condate,billdate1 ,billdate2,";
	 $sql .= "billdate3 ,billdate4 ,billdate5 ,billdate6, endworkday, endcableday,";
	 $sql .= "depositdate1 ,depositdate2, depositdate3 ,depositdate4 ,depositdate5 ,depositdate6, ";
	 $sql .= "deposit1 ,deposit2, deposit3 ,deposit4 ,deposit5 ,deposit6, ";
	 $sql .= "totalbill , accountnote, asproday, asendday, asman, ";   // regist_day도 최근수정일로 +1개 추가
	 $sql .= "claimdate1 ,claimdate2,claimdate3 ,claimdate4 ,claimdate5, claimdate6,";   // 기성청구부분 추가 공무담당 전화
	 $sql .= "claimamount1 ,claimamount2,claimamount3 ,claimamount4 ,claimamount5 ,claimamount6, claimamount7,";   // 기성청구부분 추가 공무담당 전화
	 $sql .= "claimbalance1 ,claimbalance2,claimbalance3 ,claimbalance4 ,claimbalance5 ,claimbalance6,claimbalance7,";   // 기성청구부분 추가 공무담당 전화
	 $sql .= "claimperson,claimtel,work_state, as_state, sum_bill, sum_receivable, sum_deposit, sum_claimamount, sum_estimate, as_refer, change_worklist, checkbox, checkstep, asfee, asfee_estimate, promiseday, as_check, outputmemo, aswriter, setdate,"; 
	 $sql .= "claimfix1 ,claimfix2,claimfix3 ,claimfix4 ,claimfix5 ,claimfix6, claimfix7, condate1, condate2, regist_day)"; 

     $sql .= "values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";// 총 12개 레코드 추가
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";
	 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";   // 총 72 개 레코드 추가 위의 Set과 숫자가 딱 맞아야만 입력이 실행된다.
	 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";   // 기성청구란 공무담당 등 19개 필드
	 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";   // 기성청구란 공무담당 등 19개 필드 , 4개 필드 추가
	 $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now())";   // 기성청구란 공무담당 등 19개 필드 , 4개 필드 추가
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $_SESSION["userid"], PDO::PARAM_STR);  
     $stmh->bindValue(2, $_SESSION["name"], PDO::PARAM_STR);  
     $stmh->bindValue(3, $_SESSION["nick"], PDO::PARAM_STR);   
     $stmh->bindValue(4, $subject, PDO::PARAM_STR);  
     $stmh->bindValue(5, $content, PDO::PARAM_STR);  
     $stmh->bindValue(6, $is_html, PDO::PARAM_STR);    
	 
     $stmh->bindValue(7, $upfile_name[0], PDO::PARAM_STR); 
     $stmh->bindValue(8, $upfile_name[1], PDO::PARAM_STR);  
     $stmh->bindValue(9, $upfile_name[2], PDO::PARAM_STR);   
     $stmh->bindValue(10, $copied_file_name[0], PDO::PARAM_STR);  
     $stmh->bindValue(11, $copied_file_name[1], PDO::PARAM_STR);  
     $stmh->bindValue(12, $copied_file_name[2], PDO::PARAM_STR);        
	   
     $stmh->bindValue(13, $estimate1, PDO::PARAM_STR);        
     $stmh->bindValue(14, $estimate2, PDO::PARAM_STR);        
     $stmh->bindValue(15, $estimate3, PDO::PARAM_STR);        
     $stmh->bindValue(16, $estimate4, PDO::PARAM_STR);        
     $stmh->bindValue(17, $bill1, PDO::PARAM_STR);        
     $stmh->bindValue(18, $bill2, PDO::PARAM_STR);        
     $stmh->bindValue(19, $bill3, PDO::PARAM_STR);        
     $stmh->bindValue(20, $bill4, PDO::PARAM_STR);        
     $stmh->bindValue(21, $bill5, PDO::PARAM_STR);        
     $stmh->bindValue(22, $bill6, PDO::PARAM_STR);  
	 
     $stmh->bindValue(23, $receivable, PDO::PARAM_STR);        
     $stmh->bindValue(24, $workplacename, PDO::PARAM_STR);        
     $stmh->bindValue(25, $chargedperson, PDO::PARAM_STR);        
     $stmh->bindValue(26, $address, PDO::PARAM_STR);        
     $stmh->bindValue(27, $firstord, PDO::PARAM_STR);        
     $stmh->bindValue(28, $firstordman, PDO::PARAM_STR);        
     $stmh->bindValue(29, $firstordmantel, PDO::PARAM_STR);        
     $stmh->bindValue(30, $secondord, PDO::PARAM_STR);
	 
     $stmh->bindValue(31, $secondordman, PDO::PARAM_STR);
     $stmh->bindValue(32, $secondordmantel, PDO::PARAM_STR);
     $stmh->bindValue(33, $worklist, PDO::PARAM_STR);
     $stmh->bindValue(34, $motormaker, PDO::PARAM_STR);
     $stmh->bindValue(35, $power, PDO::PARAM_STR);
	 if($workday!="") $workday = date("Y-m-d", strtotime( $workday) );
     $stmh->bindValue(36, $workday, PDO::PARAM_STR);
     $stmh->bindValue(37, $worker, PDO::PARAM_STR);
	 if($cableday!="") $cableday = date("Y-m-d", strtotime( $cableday) );
     $stmh->bindValue(38, $cableday, PDO::PARAM_STR);
     $stmh->bindValue(39, $cablestaff, PDO::PARAM_STR);
	 
	  if($asday!="") $asday = date("Y-m-d", strtotime( $asday) );
     $stmh->bindValue(40, $asday, PDO::PARAM_STR);
     $stmh->bindValue(41, $asorderman, PDO::PARAM_STR);
     $stmh->bindValue(42, $asordermantel, PDO::PARAM_STR);
     $stmh->bindValue(43, $aslist, PDO::PARAM_STR);
     $stmh->bindValue(44, $asresult, PDO::PARAM_STR);
     $stmh->bindValue(45, $ashistory, PDO::PARAM_STR);
     $stmh->bindValue(46, $comment, PDO::PARAM_STR);
	  if($condate!="") $condate = date("Y-m-d", strtotime($condate) );
     $stmh->bindValue(47, $condate, PDO::PARAM_STR);
	  if($billdate1!="") $billdate1 = date("Y-m-d", strtotime($billdate1) );
     $stmh->bindValue(48, $billdate1, PDO::PARAM_STR);		
	 if($billdate2!="") $billdate2 = date("Y-m-d", strtotime($billdate2) );
     $stmh->bindValue(49, $billdate2, PDO::PARAM_STR);		 
	 if($billdate3!="") $billdate3 = date("Y-m-d", strtotime($billdate3) );
     $stmh->bindValue(50, $billdate3, PDO::PARAM_STR);		
	 if($billdate4!="") $billdate4 = date("Y-m-d", strtotime($billdate4) );
     $stmh->bindValue(51, $billdate4, PDO::PARAM_STR);		
	 if($billdate5!="") $billdate5 = date("Y-m-d", strtotime($billdate5) );
     $stmh->bindValue(52, $billdate5, PDO::PARAM_STR);		
	 if($billdate16="")$billdate6 = date("Y-m-d", strtotime($billdate6) );
     $stmh->bindValue(53, $billdate6, PDO::PARAM_STR);	
     if($endworkday!="")$endworkday = date("Y-m-d", strtotime($endworkday) );     
	 $stmh->bindValue(54, $endworkday, PDO::PARAM_STR);		
	 if($endcableday!="") $endcableday = date("Y-m-d", strtotime($endcableday) );
     $stmh->bindValue(55, $endcableday, PDO::PARAM_STR);	

	 if($depositdate1!="") $depositdate1 = date("Y-m-d", strtotime($depositdate1) );
     $stmh->bindValue(56, $depositdate1, PDO::PARAM_STR);		
	 if($depositdate2!="") $depositdate2 = date("Y-m-d", strtotime($depositdate2) );
     $stmh->bindValue(57, $depositdate2, PDO::PARAM_STR);		
	 if($depositdate3!="") $depositdate3 = date("Y-m-d", strtotime($depositdate3) );
     $stmh->bindValue(58, $depositdate3, PDO::PARAM_STR);		
	 if($depositdate4!="")  $depositdate4 = date("Y-m-d", strtotime($depositdate4) );
     $stmh->bindValue(59, $depositdate4, PDO::PARAM_STR);		
	 if($depositdate5!="") $depositdate5 = date("Y-m-d", strtotime($depositdate5) );
     $stmh->bindValue(60, $depositdate5, PDO::PARAM_STR);		
	 if($depositdate6!="") $depositdate6 = date("Y-m-d", strtotime($depositdate6) );
     $stmh->bindValue(61, $depositdate6, PDO::PARAM_STR);		     
     $stmh->bindValue(62, $deposit1, PDO::PARAM_STR);        
     $stmh->bindValue(63, $deposit2, PDO::PARAM_STR);        
     $stmh->bindValue(64, $deposit3, PDO::PARAM_STR);        
     $stmh->bindValue(65, $deposit4, PDO::PARAM_STR);        
     $stmh->bindValue(66, $deposit5, PDO::PARAM_STR);        
     $stmh->bindValue(67, $deposit6, PDO::PARAM_STR);  	
     $stmh->bindValue(68, $totalbill, PDO::PARAM_STR); 
     $stmh->bindValue(69, $accountnote, PDO::PARAM_STR); 
	 if($asproday!="") $asproday = date("Y-m-d", strtotime($asproday) );
     $stmh->bindValue(70, $asproday, PDO::PARAM_STR);		 
	 if($asendday!="") $asendday = date("Y-m-d", strtotime($asendday) );
     $stmh->bindValue(71, $asendday, PDO::PARAM_STR);	 
     $stmh->bindValue(72, $asman, PDO::PARAM_STR); 	 	 
	 
	 if($claimdate1!="") $claimdate1 = date("Y-m-d", strtotime($claimdate1) );
     $stmh->bindValue(73, $claimdate1, PDO::PARAM_STR);		
	 if($claimdate2!="") $claimdate2 = date("Y-m-d", strtotime($claimdate2) );
     $stmh->bindValue(74, $claimdate2, PDO::PARAM_STR);		
	 if($claimdate3!="") $claimdate3 = date("Y-m-d", strtotime($claimdate3) );
     $stmh->bindValue(75, $claimdate3, PDO::PARAM_STR);		
	 if($claimdate4!="")  $claimdate4 = date("Y-m-d",strtotime($claimdate4) );
     $stmh->bindValue(76, $claimdate4, PDO::PARAM_STR);		
	 if($claimdate5!="") $claimdate5 = date("Y-m-d", strtotime($claimdate5) );
     $stmh->bindValue(77, $claimdate5, PDO::PARAM_STR);		
	 if($claimdate6!="") $claimdate6 = date("Y-m-d", strtotime($claimdate6) );
     $stmh->bindValue(78, $claimdate6, PDO::PARAM_STR);		

     $stmh->bindValue(79, $claimamount1, PDO::PARAM_STR);        
     $stmh->bindValue(80, $claimamount2, PDO::PARAM_STR);        
     $stmh->bindValue(81, $claimamount3, PDO::PARAM_STR);        
     $stmh->bindValue(82, $claimamount4, PDO::PARAM_STR);        
     $stmh->bindValue(83, $claimamount5, PDO::PARAM_STR);        
     $stmh->bindValue(84, $claimamount6, PDO::PARAM_STR);  
     $stmh->bindValue(85, $claimamount7, PDO::PARAM_STR);  
	 $stmh->bindValue(86, $claimbalance1, PDO::PARAM_STR);        
     $stmh->bindValue(87, $claimbalance2, PDO::PARAM_STR);        
     $stmh->bindValue(88, $claimbalance3, PDO::PARAM_STR);        
     $stmh->bindValue(89, $claimbalance4, PDO::PARAM_STR);        
     $stmh->bindValue(90, $claimbalance5, PDO::PARAM_STR);        
     $stmh->bindValue(91, $claimbalance6, PDO::PARAM_STR);  	 
     $stmh->bindValue(92, $claimbalance7, PDO::PARAM_STR);  	 
     $stmh->bindValue(93, $claimperson, PDO::PARAM_STR);  
     $stmh->bindValue(94, $claimtel, PDO::PARAM_STR);   
     $stmh->bindValue(95, $work_state, PDO::PARAM_STR);   
     $stmh->bindValue(96, $as_state, PDO::PARAM_STR);   
     $stmh->bindValue(97, $sum_bill, PDO::PARAM_STR);   
     $stmh->bindValue(98, $sum_receivable, PDO::PARAM_STR);   
     $stmh->bindValue(99, $sum_deposit, PDO::PARAM_STR);   
     $stmh->bindValue(100, $sum_claimamount, PDO::PARAM_STR);   
     $stmh->bindValue(101, $sum_estimate, PDO::PARAM_STR);   
     $stmh->bindValue(102, $as_refer, PDO::PARAM_STR);   
     $stmh->bindValue(103, $change_worklist, PDO::PARAM_STR);   
     $stmh->bindValue(104, $checkbox, PDO::PARAM_STR);   
     $stmh->bindValue(105, $checkstep, PDO::PARAM_STR);   
     $stmh->bindValue(106, $asfee, PDO::PARAM_STR);   
     $stmh->bindValue(107, $asfee_estimate, PDO::PARAM_STR); 
	 if($promiseday!="") $promiseday = date("Y-m-d", strtotime($promiseday) );
     $stmh->bindValue(108, $promiseday, PDO::PARAM_STR);   
     $stmh->bindValue(109, $as_check, PDO::PARAM_STR);   
     $stmh->bindValue(110,$outputmemo, PDO::PARAM_STR);   
	 $stmh->bindValue(111, $aswriter, PDO::PARAM_STR);   
	 if($setdate!="") $setdate = date("Y-m-d", strtotime($setdate) );     	 
     $stmh->bindValue(112, $setdate, PDO::PARAM_STR);   
     $stmh->bindValue(113, $claimfix1, PDO::PARAM_STR);        
     $stmh->bindValue(114, $claimfix2, PDO::PARAM_STR);        
     $stmh->bindValue(115, $claimfix3, PDO::PARAM_STR);        
     $stmh->bindValue(116, $claimfix4, PDO::PARAM_STR);        
     $stmh->bindValue(117, $claimfix5, PDO::PARAM_STR);        
     $stmh->bindValue(118, $claimfix6, PDO::PARAM_STR);  
     $stmh->bindValue(119, $claimfix7, PDO::PARAM_STR); 	 
     $stmh->bindValue(120, $condate1, PDO::PARAM_STR); 	 
     $stmh->bindValue(121, $condate2, PDO::PARAM_STR); 	  
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
   }
    ?>
  <script>
        alert('자료등록/수정 완료');      
  </script>
<?php
 if($mode=="not")
	   header("Location:http://5130.co.kr/account/list.php?num=$num&page=$page&search=$search&find=$find&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&scale=$scale&all_check=$all_check");    // 신규가입일때는 리스트로 이동
	 else		 
       header("Location:http://5130.co.kr/account/view.php?num=$num&page=$page&search=$search&find=$find&process=$process&asprocess=$asprocess&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&scale=$scale&all_check=$all_check");  
 
 ?>