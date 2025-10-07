 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/> 
  
<link rel="stylesheet" href="../css/partner.css" type="text/css" />

<title>이미지 크기 변환 예제</title>
</head>

<body>
     <div  class="container-fluid">
	 <div class="row">   <div class="col">      <h3 class="display-4 text-left">    
	 
<?php

// public function __construct(){ /* nothing */ }

	//Auth key
	define('UPLOAD_ERR_INI_SIZE',"60000000");

    $uploads_dir = './imgtest'; //업로드 폴더 -현재 처리하는 폴더 하부로 imgtest 폴더
    $allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF'); //이미지 파일만 허용
 
 
 	//첨부파일이 있다면
	$uploadSize = 60000000;
	@mkdir("$upload_dir", 0707);
  	@chmod("$upload_dir", 0707);
	
  	// 올라간 파일의 퍼미션을 변경합니다.
  	chmod("$uploads_dir", 0755);

    // 변수 정리
    $error = $_FILES['mainImgInput']['error'];
    $name = $_FILES['mainImgInput']['name'];     
    $tmpNm =  explode( '.', $name );
    $ext = strtolower(end($tmpNm));

     echo "$ext <br>";
    // 확장자 확인
    if( !in_array($ext, $allowed_ext) ) {
        echo "허용되지 않는 확장자입니다.";
        exit;
    }
    $newfile=$tmpNm[0].".".$ext ;
    $url = $uploads_dir.'/'.$newfile; //올린 파일 명 그대로 복사해라.


           //     if (!allow_file_type($upfile[$i][extension], $allow_type))
             //       alert("허용하지 않는 파일형식 입니다.");


//요기부분 수정했습니다.
    $filename = compress_image($_FILES["mainImgInput"]["tmp_name"], $url, 70); //실제 파일용량 줄이는 부분

list($width, $height, $type, $attr) = getImagesize($_FILES["mainImgInput"]["tmp_name"]);
echo $width."<br>";
echo $height."<br>";
echo $type."<br>";
echo $attr."<br>";

if($width > 700){
 $switch_s=80;
}else{
 $switch_s=100;
}


    $buffer = file_get_contents($url);
 
    // 파일 정보 출력
    echo "<h2>파일 정보</h2> <h1>
        <ul>
            <li>파일명: $name</li>
            <li>확장자: $ext</li>
            <li>파일형식: {$_FILES['mainImgInput']['type']}</li>
            <li>파일크기: {$_FILES['mainImgInput']['size']} 바이트</li>
            <li>url: {$url}</li>
            <li>filename: {$filename}</li>
        </ul> </h1>";
 
 
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

$re_image = new Image($filename);
$re_image -> width(800);
$re_image -> height(450);
$re_image -> save();

?>
 </h3>	  </div> </div>
</body>
</html>
