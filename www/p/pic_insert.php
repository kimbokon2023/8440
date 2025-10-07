 <?php session_start(); 

$workplacename=$_REQUEST["workplacename"];
$workername = $_REQUEST["workername"];


 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
		 else 
			$num="";
		
 if(isset($_REQUEST["filedelete"]))
    $filedelete=$_REQUEST["filedelete"];
		 else 
			$filedelete="";		
		
// 파일 삭제에 대한 처부부분

if($filedelete=='before' || $filedelete=='after')
{
$newfilename = '';

if($filedelete=='before')
	$delfile = " filename1=? ";   // before
  else
	  $delfile = " filename2=? ";   //after
	
		 require_once("../lib/mydb.php");
		 $pdo = db_connect();

		try{		 
			$pdo->beginTransaction();   
			$sql = "update mirae8440.work set ";
			$sql .= $delfile . " where num=? LIMIT 1" ;        
			   
			 $stmh = $pdo->prepare($sql); 
			 
			 $stmh->bindValue(1, $newfilename, PDO::PARAM_STR);             
			 $stmh->bindValue(2, $num, PDO::PARAM_STR);             
			 
			 $stmh->execute();
			 $pdo->commit(); 
				} catch (PDOException $Exception) {
				   $pdo->rollBack();
				   print "오류: ".$Exception->getMessage();
			 }  

header("Location:http://8440.co.kr/p/reg_pic.php?num=$num&check=$check");			 
}
	
		
 require_once("../lib/mydb.php");
 $pdo = db_connect();     
      
 try{
     $sql = "select * from mirae8440.work where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
     $update_log=$row["update_log"];
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
	 
 $data=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
 $update_log = $data . $update_log . "&#10";  // 개행문자 Textarea      
 	  
	  
	try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.work set update_log=? where num=?  LIMIT 1";            
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $update_log, PDO::PARAM_STR);  	 
     $stmh->bindValue(2, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }     
	   		 
  if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 			

class Image {
    
    var $file;
    var $image_width;
    var $image_height;
    var $width;
    var $height;
    var $ext;
    var $types = array('','gif','jpeg','png','swf');
    var $quality = 70;
    var $top = 0;
    var $left = 0;
    var $crop = false;
    var $type;
    
    function __construct($name='') {
        $this->file = $name;
        $info = getimagesize($name);
        $this->image_width = $info[0];
        $this->image_height = $info[1];
        $this->type = $this->types[$info[2]];
        $info = pathinfo($name);
        $this->dir = $info['dirname'];
        $this->name = str_replace('.'.$info['extension'], '', $info['basename']);
        $this->ext = $info['extension'];
    }
    
    function dir($dir='') {
        if(!$dir) return $this->dir;
        $this->dir = $dir;
    }
    
    function name($name='') {
        if(!$name) return $this->name;
        $this->name = $name;
    }
    
    function width($width='') {
        $this->width = $width;
    }
    
    function height($height='') {
        $this->height = $height;
    }
    
    function resize($percentage=50) {
        if($this->crop) {
            $this->crop = false;
            $this->width = round($this->width*($percentage/100));
            $this->height = round($this->height*($percentage/100));
            $this->image_width = round($this->width/($percentage/100));
            $this->image_height = round($this->height/($percentage/100));
        } else {
            $this->width = round($this->image_width*($percentage/100));
            $this->height = round($this->image_height*($percentage/100));
        }
        
    }
    
    function crop($top=0, $left=0) {
        $this->crop = true;
        $this->top = $top;
        $this->left = $left;
    }
    
    function quality($quality=70) {
        $this->quality = $quality;
    }
    
    function show() {
        $this->save(true);
    }
    
    function save($show=false) {
 
        if($show) @header('Content-Type: image/'.$this->type);
        
        if(!$this->width && !$this->height) {
            $this->width = $this->image_width;
            $this->height = $this->image_height;
        } elseif (is_numeric($this->width) && empty($this->height)) {
            $this->height = round($this->width/($this->image_width/$this->image_height));
        } elseif (is_numeric($this->height) && empty($this->width)) {
            $this->width = round($this->height/($this->image_height/$this->image_width));
        } else {
            if($this->width<=$this->height) {
                $height = round($this->width/($this->image_width/$this->image_height));
                if($height!=$this->height) {
                    $percentage = ($this->image_height*100)/$height;
                    $this->image_height = round($this->height*($percentage/100));
                }
            } else {
                $width = round($this->height/($this->image_height/$this->image_width));
                if($width!=$this->width) {
                    $percentage = ($this->image_width*100)/$width;
                    $this->image_width = round($this->width*($percentage/100));
                }
            }
        }
        
        if($this->crop) {
            $this->image_width = $this->width;
            $this->image_height = $this->height;
        }
 
        if($this->type=='jpeg') $image = imagecreatefromjpeg($this->file);
        if($this->type=='png') $image = imagecreatefrompng($this->file);
        if($this->type=='gif') $image = imagecreatefromgif($this->file);
        
        $new_image = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($new_image, $image, 0, 0, $this->top, $this->left, $this->width, $this->height, $this->image_width, $this->image_height);
        
        $name = $show ? null: $this->dir.DIRECTORY_SEPARATOR.$this->name.'.'.$this->ext;
    
        if($this->type=='jpeg') imagejpeg($new_image, $name, $this->quality);
        if($this->type=='png') imagepng($new_image, $name);
        if($this->type=='gif') imagegif($new_image, $name);
 
        imagedestroy($image); 
        imagedestroy($new_image);
        
    }
    
}			
			
			
			
			
if($_FILES['mainBefore']['name']!='') {   			
	//Auth key
	define('UPLOAD_ERR_INI_SIZE',"100000000");

    $uploads_dir = '../imgwork'; //업로드 폴더 -현재 처리하는 폴더 하부로 imgtest 폴더
    $allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF'); //이미지 파일만 허용
 
 
 	//첨부파일이 있다면
	$uploadSize = 100000000;
	@mkdir("$upload_dir", 0707);
  	@chmod("$upload_dir", 0707);
	
  	// 올라간 파일의 퍼미션을 변경합니다.
  	chmod("$uploads_dir", 0755);

    // 변수 정리
    $error = $_FILES['mainBefore']['error'];
    $name = $_FILES['mainBefore']['name'];     
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
	$newfilename1 = $new_file_name."_1.".$ext;      
    $url1 = $uploads_dir.'/'.$newfilename1; //올린 파일 명 그대로 복사해라.  시간초 등으로 파일이름 만들기
	
	// 사진회전시키기
	    
     $tmpimage = imagecreatefromjpeg($url1);
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
    $filename1 = compress_image($_FILES["mainBefore"]["tmp_name"], $url1, 70); //실제 파일용량 줄이는 부분

list($width, $height, $type, $attr) = getImagesize($_FILES["mainBefore"]["tmp_name"]);
echo $width."<br>";
echo $height."<br>";
echo $type."<br>";
echo $attr."<br>";

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
            <li>파일형식: {$_FILES['mainBefore']['type']}</li>
            <li>파일크기: {$_FILES['mainBefore']['size']} 바이트</li>
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

		 require_once("../lib/mydb.php");
		 $pdo = db_connect();

		try{		 
			$pdo->beginTransaction();   
			$sql = "update mirae8440.work set ";
			$sql .=" filename1=? where num=? LIMIT 1" ;        
			   
			 $stmh = $pdo->prepare($sql); 

			 $stmh->bindValue(1, $newfilename1, PDO::PARAM_STR);             
			 $stmh->bindValue(2, $num, PDO::PARAM_STR);             
			 
			 $stmh->execute();
			 $pdo->commit(); 
				} catch (PDOException $Exception) {
				   $pdo->rollBack();
				   print "오류: ".$Exception->getMessage();
			 }                         
			   
 }


if($_FILES['mainAfter']['name']!='') {  

// 시공 후 사진부분 사이즈 축소 및 크기변경

	//Auth key
	define('UPLOAD_ERR_INI_SIZE',"100000000");

    $uploads_dir = '../imgwork/'; //업로드 폴더 -현재 처리하는 폴더 하부로 imgtest 폴더
    $allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF'); //이미지 파일만 허용
 
 
 	//첨부파일이 있다면
	$uploadSize = 1000000000;
	@mkdir("$upload_dir", 0707);
  	@chmod("$upload_dir", 0707);
	
  	// 올라간 파일의 퍼미션을 변경합니다.
  	chmod("$uploads_dir", 0755);

    // 변수 정리
    $error = $_FILES['mainAfter']['error'];
    $name = $_FILES['mainAfter']['name'];     
    $tmpNm =  explode( '.', $name );
    $ext = strtolower(end($tmpNm));
	
     echo "$ext <br>";
    // 확장자 확인
    if( !in_array($ext, $allowed_ext) ) {
        echo "허용되지 않는 확장자입니다.";
        exit;
    }
	$new_file_name = date("Y_m_d_H_i_s");
	$newfilename2 = $new_file_name."_2.".$ext;      
    $url2 = $uploads_dir.'/'.$newfilename2; //올린 파일 명 그대로 복사해라.  시간초 등으로 파일이름 만들기


           //     if (!allow_file_type($upfile[$i][extension], $allow_type))
             //       alert("허용하지 않는 파일형식 입니다.");


//요기부분 수정했습니다.
    $filename2 = compress_image($_FILES["mainAfter"]["tmp_name"], $url2, 70); //실제 파일용량 줄이는 부분

list($width, $height, $type, $attr) = getImagesize($_FILES["mainAfter"]["tmp_name"]);
echo $width."<br>";
echo $height."<br>";
echo $type."<br>";
echo $attr."<br>";

if($width > 700){
 $switch_s=80;
}else{
 $switch_s=100;
}
    $buffer = file_get_contents($url2);
 
    // 파일 정보 출력
    echo "<h2>파일 정보</h2> <h1>
        <ul>
            <li>파일명: $name</li>
            <li>확장자: $ext</li>
            <li>파일형식: {$_FILES['mainAfter']['type']}</li>
            <li>파일크기: {$_FILES['mainAfter']['size']} 바이트</li>
            <li>url: {$url2}</li>
            <li>filename: {$filename2}</li>
        </ul> </h1>";
    
$re_image = new Image($filename2);
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

		 require_once("../lib/mydb.php");
		 $pdo = db_connect();

		try{		 
			$pdo->beginTransaction();   
			$sql = "update mirae8440.work set ";
			$sql .=" filename2=? where num=? LIMIT 1" ;        
			   
			 $stmh = $pdo->prepare($sql); 
			 
			 $stmh->bindValue(1, $newfilename2, PDO::PARAM_STR);             
			 $stmh->bindValue(2, $num, PDO::PARAM_STR);             
			 
			 $stmh->execute();
			 $pdo->commit(); 
				} catch (PDOException $Exception) {
				   $pdo->rollBack();
				   print "오류: ".$Exception->getMessage();
			 }                         
}       
 



// 파일 압축 메소드 
    function compress_image($source, $destination, $quality) { 
        $info = getimagesize($source); 
        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source); 
        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source); 
        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source); 

     elseif ($info['mime'] == 'image/x-ms-bmp') 
      $image = imagecreatefrombmp($source);

        imagejpeg($image, $destination, $quality); 
        return $destination;
    }


// log 기록 남기기

 $data=date("Y-m-d H:i:s") . " - " . $_SESSION["userid"] . " - " . $_SESSION["name"] . "  " . $workplacename . " - 사진기록" ;	
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 $pdo->beginTransaction();
 $sql = "insert into mirae8440.logdata(data) values(?) " ;
 $stmh = $pdo->prepare($sql); 
 $stmh->bindValue(1, $data, PDO::PARAM_STR);   
 $stmh->execute();
 $pdo->commit(); 


header("Location:http://8440.co.kr/p/reg_pic.php?num=$num&check=$check&workername=$workername");

?>  
  
  
 

