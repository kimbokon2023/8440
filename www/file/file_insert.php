 <?php  
 session_start();  
 
 /*  파일 및 이미지를 서버에 올리기 위한 통합 모듈 제작
 서버 저장위치 : 일반 첨부파일 이미지 함께 사용 fileuploads
 fileuploads 필드구성 (id, tablename, item, parentid, realname, savename) 
 본문의 form에 hidden 형식으로 전달되도록 반드시 지정할 것
 
 <!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
	<input type="hidden" id="parentid" name="parentid" value="<?=$parentid?>" >			  								
	<input type="hidden" id="fileorimage" name="fileorimage" value="<?=$fileorimage?>" >			  								
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  								
	<input type="hidden" id="upfile" name="upfile" value="<?=$upfile?>" >			  								
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >	
	<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >			
	<input type="hidden" id="timekey" name="timekey" value="<?=$timekey?>" >			
 
 파일 저장시 new파일인 경우는 난수를 발생시켜 저장한 후 그 이름을 전달해서 파일의 수정/신규 저장시 기록하고, (임시저장기술 적용)
 아닐경우 그 파일을 삭제하는 루틴제작
 
 */
 
 ini_set('display_errors', 1);
 
include "../php/common.php";
 
isset($_REQUEST["id"])  ? $id=$_REQUEST["id"] :   $id=''; 
isset($_REQUEST["fileorimage"])  ? $fileorimage=$_REQUEST["fileorimage"] : $fileorimage=''; // file or image
isset($_REQUEST["item"])  ? $item=$_REQUEST["item"] :   $item=''; 
isset($_REQUEST["upfilename"])  ? $upfilename=$_REQUEST["upfilename"] : $upfilename=''; 
isset($_REQUEST["tablename"])  ? $tablename=$_REQUEST["tablename"] :  $tablename=''; 
isset($_REQUEST["savetitle"])  ? $savetitle=$_REQUEST["savetitle"] :  $savetitle='';   // log기록 저장 타이틀

// var_dump($upfilename);
// var_dump($_FILES[$upfilename]['name']);

$countfiles = count($_FILES[$upfilename]['name']);

 require_once("../lib/mydb.php");
 $pdo = db_connect();

// 이미지인 경우
if($fileorimage=='image') {
	for($i=0;$i<$countfiles;$i++){
		$filename = $_FILES[$upfilename]['name'][$i];
			if($filename !='') {   			
				//Auth key
				define('UPLOAD_ERR_INI_SIZE',"100000000");

				$uploads_dir = '../uploads'; //업로드 폴더 상위 uploads 폴더선택
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF'); //이미지 파일만 허용
			 
			 
				//첨부파일이 있다면
				$uploadSize = 100000000;
				@mkdir("$upload_dir", 0707);
				@chmod("$upload_dir", 0707);
				
				// 올라간 파일의 퍼미션을 변경합니다.
				chmod("$uploads_dir", 0755);

				// 변수 정리
				$error = $_FILES[$upfilename]['name'][$i];
				$name = $_FILES[$upfilename]['name'][$i];     
				$tmpNm =  explode( '.', $name );
				$ext = strtolower(end($tmpNm));

				 echo "$ext <br>";
				// 확장자 확인
				if( !in_array($ext, $allowed_ext) ) {
					echo "허용되지 않는 확장자입니다.";
					exit;
				}
								
			   // $newfile=$tmpNm[0].".".$ext ;
				$new_file_name = date("Y_m_d_H_i_s");
				$newfilename1 = $new_file_name."_" . $i . "." . $ext;      
				$url1 = $uploads_dir.'/'.$newfilename1; //올린 파일 명 그대로 복사해라.  시간초 등으로 파일이름 만들기
				
				// 사진회전시키기
			// 이미지 로드 및 회전 처리 부분
			if ($ext == 'jpg' || $ext == 'jpeg') {
				// JPEG 이미지인 경우
				 // $tmpimage = imagecreatefromjpeg($url1);
				 $exif = exif_read_data($url1);
				 
				 print '사진 정보' . $exif['Orientation'] . "<br>";
				 
						if(!empty($exif['Orientation']))
							{
								switch($exif['Orientation'])
								{
									case 8:
										$url1 = imagerotate($url1,90,0);
										break;
									case 3:
										$url1 = imagerotate($url1,180,0);
										break;
									case 6:
										$url1 = imagerotate($url1,-90,0);
										break;

								}
							}


				//요기부분 수정했습니다.
				$filename1 = compress_image($_FILES[$upfilename]["tmp_name"][$i], $url1, 70); //실제 파일용량 줄이는 부분

				list($width, $height, $type, $attr) = getImagesize($_FILES[$upfilename]["tmp_name"][$i]);

				if($width > 700){
				 $switch_s=80;
				}else{
				 $switch_s=100;
				}
				$buffer = file_get_contents($url1);
			 
				// 파일 정보 출력
				echo "<h2>파일 정보</h2> <h1>
					<ul>
						<li>자료번호: $num</li>
						<li>파일명: $name</li>
						<li>확장자: $ext</li>
						<li>url: {$url1}</li>
						<li>filename: {$filename1}</li>
					</ul> </h1>";
				
				$re_image = new Image($filename1);
				$rate=$width/$height;
				if($width>$height) {
						$re_image -> width(800);
						$re_image -> height(800/$rate);
					}
					else
					{
						$re_image -> width(800*$rate);
						$re_image -> height(800);
					}
					$re_image -> save();
			// insert
					try{		 
						$pdo->beginTransaction();   
						$sql = "insert into mirae8440.fileuploads ";
						$sql .=" (tablename, item, parentid, realname, savename) " ;        
						$sql .=" values(?, ?, ?, ?, ?) " ;        
						   
						 $stmh = $pdo->prepare($sql); 
						 
						 $stmh->bindValue(1, $tablename, PDO::PARAM_STR);   
						 $stmh->bindValue(2, $item, PDO::PARAM_STR);   
						 $stmh->bindValue(3, $id, PDO::PARAM_STR);             
						 $stmh->bindValue(4, $filename, PDO::PARAM_STR);   
						 $stmh->bindValue(5, $newfilename1, PDO::PARAM_STR);   
						 
						 
						 $stmh->execute();
						 $pdo->commit(); 
							} catch (PDOException $Exception) {
							   $pdo->rollBack();
							   print "오류: ".$Exception->getMessage();
						 }  					
			}			
			elseif ($ext == 'png') {
				// PNG 이미지인 경우				
				// $tmpimage = imagecreatefrompng($url1);
					// PNG 이미지 압축 및 저장 로직					

				//요기부분 수정했습니다.
				$filename1 = compress_image($_FILES[$upfilename]["tmp_name"][$i], $url1, 70); //실제 파일용량 줄이는 부분

				list($width, $height, $type, $attr) = getImagesize($_FILES[$upfilename]["tmp_name"][$i]);

				if($width > 700){
				 $switch_s=80;
				}else{
				 $switch_s=100;
				}
				$buffer = file_get_contents($url1);
			 
				// 파일 정보 출력
				echo "<h2>파일 정보</h2> <h1>
					<ul>
						<li>자료번호: $num</li>
						<li>파일명: $name</li>
						<li>확장자: $ext</li>
						<li>url: {$url1}</li>
						<li>filename: {$filename1}</li>
					</ul> </h1>";
				
				$re_image = new Image($filename1);
				$rate=$width/$height;
				if($width>$height) {
						$re_image -> width(800);
						$re_image -> height(800/$rate);
					}
					else
					{
						$re_image -> width(800*$rate);
						$re_image -> height(800);
					}
					$re_image -> save();


			// insert
					try{		 
						$pdo->beginTransaction();   
						$sql = "insert into mirae8440.fileuploads ";
						$sql .=" (tablename, item, parentid, realname, savename) " ;        
						$sql .=" values(?, ?, ?, ?, ?) " ;        
						   
						 $stmh = $pdo->prepare($sql); 
						 
						 $stmh->bindValue(1, $tablename, PDO::PARAM_STR);   
						 $stmh->bindValue(2, $item, PDO::PARAM_STR);   
						 $stmh->bindValue(3, $id, PDO::PARAM_STR);             
						 $stmh->bindValue(4, $filename, PDO::PARAM_STR);   
						 $stmh->bindValue(5, $newfilename1, PDO::PARAM_STR);   
						 
						 
						 $stmh->execute();
						 $pdo->commit(); 
							} catch (PDOException $Exception) {
							   $pdo->rollBack();
							   print "오류: ".$Exception->getMessage();
						 }  													
			} 	
	  } 
	} // end of for statement    
} // end of if
else  
{		
// file인 경우	
		for($i=0;$i<$countfiles;$i++){
			$filename = $_FILES[$upfilename]['name'][$i];
		if($filename !='') {   			
			//Auth key
			define('UPLOAD_ERR_INI_SIZE',"100000000");

			$uploads_dir = '../uploads'; //업로드 폴더 상위 uploads 폴더선택
		 
			//첨부파일이 있다면
			$uploadSize = 100000000;
			@mkdir("$upload_dir", 0707);
			@chmod("$upload_dir", 0707);
			
			// 올라간 파일의 퍼미션을 변경합니다.
			chmod("$uploads_dir", 0755);

			// 변수 정리
			$error = $_FILES[$upfilename]['name'][$i];
			$name = $_FILES[$upfilename]['name'][$i];    // 파일의 이름 
			$tmp_file = $_FILES[$upfilename]['tmp_name'][$i];  // 서버에 업로드시 필요함
			$tmpNm =  explode( '.', $name );
			$ext = strtolower(end($tmpNm));
			
		   // $newfile=$tmpNm[0].".".$ext ;
			$new_file_name = date("Y_m_d_H_i_s");
			$newfilename1 = $new_file_name."_" . $i . "." . $ext;      
			$url1 = $uploads_dir.'/'.$newfilename1; //올린 파일 명 그대로 복사해라.  시간초 등으로 파일이름 만들기
			
			//서버에 임시로 저장된 파일은 스크립트가 종료되면 사라지므로 파일을 이동해야함.
		     move_uploaded_file($tmp_file, $url1);
		// insert
				try{		 
					$pdo->beginTransaction();   
					$sql = "insert into mirae8440.fileuploads ";
					$sql .=" (tablename, item, parentid, realname, savename) " ;        
					$sql .=" values(?, ?, ?, ?, ?) " ;        
					   
					 $stmh = $pdo->prepare($sql); 
					 
					 $stmh->bindValue(1, $tablename, PDO::PARAM_STR);   
					 $stmh->bindValue(2, $item, PDO::PARAM_STR);   
					 $stmh->bindValue(3, $id, PDO::PARAM_STR);             
					 $stmh->bindValue(4, $filename, PDO::PARAM_STR);   
					 $stmh->bindValue(5, $newfilename1, PDO::PARAM_STR);   
					 
					 
					 $stmh->execute();
					 $pdo->commit(); 
						} catch (PDOException $Exception) {
						   $pdo->rollBack();
						   print "오류: ".$Exception->getMessage();
					 }  	

			}

		} // end of for statement   
	
	
}

// log 기록 남기기

 $data=date("Y-m-d H:i:s") . " - " . $_SESSION["userid"] . " - " . $_SESSION["name"] . "  " . $tablename . "  " . $savetitle . " - file 기록" ;	
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 $pdo->beginTransaction();
 $sql = "insert into mirae8440.logdata(data) values(?) " ;
 $stmh = $pdo->prepare($sql); 
 $stmh->bindValue(1, $data, PDO::PARAM_STR);   
 $stmh->execute();
 $pdo->commit(); 

?>  
  
  
 

