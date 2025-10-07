 <?php   
  session_start();   
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=8) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://5130.co.kr/login/logout.php");
         exit;
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
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];
  if(isset($_REQUEST["regist_state"]))  // 등록하면 1로 설정 접수상태
   $regist_state=$_REQUEST["regist_state"];
  else
   $regist_state="1";
  
 			  $con_num=$_REQUEST["con_num"];
 			  $item_outdate=$_REQUEST["outdate"];
			  $item_indate=$_REQUEST["indate"];
			  $item_orderman=$_REQUEST["orderman"];
			  $item_outworkplace=$_REQUEST["outworkplace"];
			  $item_outputplace=$_REQUEST["outputplace"];
			  $item_phone=$_REQUEST["phone"];
			  $item_receiver=$_REQUEST["receiver"];
			  $item_comment=$_REQUEST["comment"];
			  $root=$_REQUEST["root"];	  
			  $steel=$_REQUEST["steel"];	  
			  $motor=$_REQUEST["motor"];	 
			  $delivery=$_REQUEST["delivery"];	 
			  $regist_state=$_REQUEST["regist_state"];	 
	  	  
      $item_file_0 = $_REQUEST["file_name_0"];
      $item_file_1 = $_REQUEST["file_name_1"];
      $item_file_2 = $_REQUEST["file_name_2"];
      $item_file_3 = $_REQUEST["file_name_3"];
      $item_file_4 = $_REQUEST["file_name_4"];
      $copied_file_0 =  $_REQUEST["file_copied_0"];
      $copied_file_1 =  $_REQUEST["file_copied_1"];
      $copied_file_2 =  $_REQUEST["file_copied_2"];	  
      $copied_file_3 =  $_REQUEST["file_copied_3"];	  
      $copied_file_4 =  $_REQUEST["file_copied_4"];	  

  $files = $_FILES["upfile"];    //첨부파일	
  $count = count($files["name"]); 
  if($count>0)
  {
  $upload_dir = '../uploads/output/';   //물리적 저장위치     output폴더
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

			if (!move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i]) )
			{
			print("<script>
					alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
					history.back();
				  </script>");
			 exit;
			}

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
        $sql = "select * from chandj.output where num=?";  // get target record
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
						   $sql = "update chandj.output set $field_org_name = ?, $field_real_name = ?  where num=?";
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
					  $sql = "update chandj.output set $field_org_name = ?, $field_real_name = ?  where num=?";
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
        $sql = "update chandj.output set outdate=?, indate=?, orderman=?, outworkplace=?, outputplace=?, receiver=?, phone=?, comment=?, con_num=?, root=?, steel=?, motor=?,";
        $sql .= " delivery=?, regist_state=? where num=?  LIMIT 1";            
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $item_outdate, PDO::PARAM_STR);  
     $stmh->bindValue(2, $item_indate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $item_orderman, PDO::PARAM_STR);       
		
     $stmh->bindValue(4, $item_outworkplace, PDO::PARAM_STR);        
     $stmh->bindValue(5, $item_outputplace, PDO::PARAM_STR);        
     $stmh->bindValue(6, $item_receiver, PDO::PARAM_STR);        
     $stmh->bindValue(7, $item_phone, PDO::PARAM_STR);        
     $stmh->bindValue(8, $item_comment, PDO::PARAM_STR);        
     $stmh->bindValue(9, $con_num, PDO::PARAM_STR);        
     $stmh->bindValue(10, $root, PDO::PARAM_STR);        
     $stmh->bindValue(11, $steel, PDO::PARAM_STR);        
     $stmh->bindValue(12, $motor, PDO::PARAM_STR);        
     $stmh->bindValue(13, $delivery, PDO::PARAM_STR);        
     $stmh->bindValue(14, $regist_state, PDO::PARAM_STR);        
     $stmh->bindValue(15, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
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
  	 
     $sql = "insert into chandj.output(outdate, indate, orderman, outworkplace, outputplace, receiver, phone, comment, con_num, root, steel, motor,";
     $sql .= "file_name_0, file_name_1, file_name_2,file_name_3,file_name_4, file_copied_0, file_copied_1, file_copied_2, file_copied_3, file_copied_4, delivery, regist_state)";
	 

     $sql .= "values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?,";// 총 24개 레코드 추가
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $item_outdate, PDO::PARAM_STR);  
     $stmh->bindValue(2, $item_indate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $item_orderman, PDO::PARAM_STR);       
		
     $stmh->bindValue(4, $item_outworkplace, PDO::PARAM_STR);        
     $stmh->bindValue(5, $item_outputplace, PDO::PARAM_STR);        
     $stmh->bindValue(6, $item_receiver, PDO::PARAM_STR);        
     $stmh->bindValue(7, $item_phone, PDO::PARAM_STR);        
     $stmh->bindValue(8, $item_comment, PDO::PARAM_STR);  	 
     $stmh->bindValue(9, $con_num, PDO::PARAM_STR);  	 
     $stmh->bindValue(10, $root, PDO::PARAM_STR);  	 
     $stmh->bindValue(11, $steel, PDO::PARAM_STR);  	 
     $stmh->bindValue(12, $motor, PDO::PARAM_STR);  	 
 
     $stmh->bindValue(13, $upfile_name[0], PDO::PARAM_STR); 
     $stmh->bindValue(14, $upfile_name[1], PDO::PARAM_STR);  
     $stmh->bindValue(15, $upfile_name[2], PDO::PARAM_STR);   
     $stmh->bindValue(16, $upfile_name[3], PDO::PARAM_STR);   
     $stmh->bindValue(17, $upfile_name[4], PDO::PARAM_STR);   
     $stmh->bindValue(18, $copied_file_name[0], PDO::PARAM_STR);  
     $stmh->bindValue(19, $copied_file_name[1], PDO::PARAM_STR);  
     $stmh->bindValue(20, $copied_file_name[2], PDO::PARAM_STR);        
     $stmh->bindValue(21, $copied_file_name[3], PDO::PARAM_STR);        
     $stmh->bindValue(22, $copied_file_name[4], PDO::PARAM_STR);        
     $stmh->bindValue(23, $delivery, PDO::PARAM_STR);        
     $stmh->bindValue(24, $regist_state, PDO::PARAM_STR);        
	 
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
	   header("Location:http://5130.co.kr/output/read_DB.php?num=$num&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");    // 신규가입일때는 리스트로 이동
	 else		 
      header("Location:http://5130.co.kr/output/view.php?num=$num&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date");  
 ?>