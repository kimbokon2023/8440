<?php   
session_start();    
  
print $_FILES['mainBefore']['name'];
  
$level= $_SESSION["level"];
$user_name= $_SESSION["name"];

isset($_REQUEST["mode"])  ? $mode = $_REQUEST["mode"] : $mode=""; 
isset($_REQUEST["num"])  ? $num = $_REQUEST["num"] : $num=0; 
	
 if(isset($_REQUEST["filedelete"]))
    $filedelete=$_REQUEST["filedelete"];
		 else 
			$filedelete="";		
		
// 파일삭제 처리부분

if($filedelete=='before')
{
$newfilename = '';

if($filedelete=='before')
	$delfile = " filename=? ";   // before
	
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();	

		try{		 
			$pdo->beginTransaction();   
			$sql = "update mirae8440.emeeting set ";
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

}  // end of if
					

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
						
if($_FILES['mainBefore']['name']!='' ) {   			 // 파일 선택이 일어났을때
	//Auth key
	define('UPLOAD_ERR_INI_SIZE',"100000000");

    $uploads_dir = './img'; //업로드 폴더 -현재 처리하는 폴더 하부로 imgtest 폴더
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
	$newfilename = $new_file_name."_1.".$ext;      
    $url1 = $uploads_dir.'/'.$newfilename; //올린 파일 명 그대로 복사해라.  시간초 등으로 파일이름 만들기
	
    // 기존파일 서버에서 파일삭제
    unlink('./img/' .  $_REQUEST["serverfilename"]);	
	
	$serverfilename = $newfilename;  // 서버에 저장할 화일명	

//요기부분 수정했습니다.
    $filename = compress_image($_FILES["mainBefore"]["tmp_name"], $url1, 70); //실제 파일용량 줄이는 부분

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
  
$re_image = new Image($filename);
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
	  
$table = 'emeeting';

 if ($mode=="modify" || $mode=="insert"){   

$fieldarr = array();
$strarr = array();

array_push($fieldarr, 'place');
array_push($fieldarr, 'occur');
array_push($fieldarr, 'errortype');
array_push($fieldarr, 'emember');
array_push($fieldarr, 'content');
array_push($fieldarr, 'method');
array_push($fieldarr, 'filename');
array_push($fieldarr, 'serverfilename');
array_push($fieldarr, 'approve');



array_push($strarr, $_REQUEST["place"]);
array_push($strarr, $_REQUEST["occur"] );
array_push($strarr, $_REQUEST["errortype"]);
array_push($strarr, $_REQUEST["emember"]);
array_push($strarr, $_REQUEST["content"]);
array_push($strarr, $_REQUEST["method"]);

if($_FILES['mainBefore']['name'] == null )	
  {
	array_push($strarr, $_REQUEST["filename"]);   // 파일 이름을 선택하지 않으면 기존의 이름으로	
	array_push($strarr, $_REQUEST["serverfilename"]);
  }
else
 {
	// 파일 선택이 있다면 아래와 같이 기록한다.
	array_push($strarr, $_FILES['mainBefore']['name']);	
    array_push($strarr, $serverfilename);
 }

array_push($strarr, $_REQUEST["approve"]);

// array_push($strarr, 14);
if($mode=="modify")
    array_push($strarr, $num);
}
			  
 require_once("../lib/mydb.php");
 $pdo = db_connect();
     
 if ($mode=="modify"){      
     try{
        $sql = "select * from mirae8440." . $table . " where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
       			  
     try{
        $pdo->beginTransaction();   				
		$sql = "update mirae8440." . $table . " set " ;     	
		for($i=0;$i<count($fieldarr);$i++)  //  필드 배열수 만큼 반복
		 {
			 if($i!=0)
				  $sql .= ' , ';
		   $sql .= $fieldarr[$i] . '=? ' ;
		 }
		    $sql .= " where num=?  LIMIT 1";		
    
		$stmh = $pdo->prepare($sql); 
		for($i=0;$i<count($strarr);$i++)  //  필드 배열수 만큼 반복	
			$stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_STR);  
			
		 
		 $stmh->execute();
		 $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } 
 
 // insert인 경우 
 if ($mode=="insert")	 	  
{	
     $sql = "insert into mirae8440." . $table . "  ( " ;     	

	for($j=0;$j<count($fieldarr);$j++)  //  필드 배열수 만큼 반복
	 {
		 if($j!=0)
			  $sql .= ' , ';
	   $sql .= $fieldarr[$j] ;
	 }
	  $sql .= ' )  values( ';

	for($j=0;$j<count($fieldarr);$j++)  //  필드 배열수 만큼 반복
	 {
		 if($j!=0 ) 
			  $sql .= ' , ';
	   $sql .= '?';
	 }	      
	$sql .= ' ) ';
	
   try{
     $pdo->beginTransaction();
     $stmh = $pdo->prepare($sql);
	 for($i=0;$i<count($strarr);$i++)
       $stmh->bindValue($i+1,$strarr[$i],PDO::PARAM_STR);      	 
	 
     $stmh->execute();   
     $pdo->commit(); 
		} catch (PDOException $Exception) {
		   $pdo->rollBack();
		   print "오류: ".$Exception->getMessage();
	   }    
}
   
 if ($mode=="delete"){	 	 
   try{	   
		// 파일 삭제시 첨부파일을 먼저 삭제 한다.
		 require_once("../lib/mydb.php");
		 $pdo = db_connect();

	     $sql = "select * from mirae8440.emeeting where num = ? ";
		  $stmh = $pdo->prepare($sql); 
		  $stmh->bindValue(1,$num,PDO::PARAM_STR); 
		  $stmh->execute();      
		  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.		
		  // 서버의 파일이름을 삭제한다.
		  $serverfilename=$row["serverfilename"];				 
		  
				} catch (PDOException $Exception) {
				   $pdo->rollBack();
				   print "오류: ".$Exception->getMessage();
			 } 

      // 서버에서 파일삭제
      unlink('./img/' .  $serverfilename);			 
	   
	try{	 
     $pdo->beginTransaction();
  	 
     $sql = "delete from mirae8440.emeeting where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();	 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
}

var_dump($_FILES['mainBefore']['name']);

 ?>
 

 

