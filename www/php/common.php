<?php
/**
 * 이미지 처리 클래스 및 헬퍼 함수
 * 로컬 및 서버 환경 모두 지원
 */

class Image {
    var $file;
    var $image_width;
    var $image_height;
    var $width;
    var $height;
    var $ext;
    var $types = array('', 'gif', 'jpeg', 'png', 'swf');
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

// // 파일 압축 메소드 
    // function compress_image($source, $destination, $quality) { 
        // $info = getimagesize($source); 
        // if ($info['mime'] == 'image/jpeg') 
            // $image = imagecreatefromjpeg($source); 
        // elseif ($info['mime'] == 'image/gif') 
            // $image = imagecreatefromgif($source); 
        // elseif ($info['mime'] == 'image/png') 
            // $image = imagecreatefrompng($source); 

     // elseif ($info['mime'] == 'image/x-ms-bmp') 
      // $image = imagecreatefrombmp($source);

        // imagejpeg($image, $destination, $quality); 
        // return $destination;
    // }

// // 파일 압축 메소드 수정 (모바일 카메라 촬영 지원)
function compress_image($source, $destination, $quality) { 
    // 원본 파일 존재 및 읽기 권한 확인
    if (!file_exists($source) || !is_readable($source)) {
        error_log("원본 파일에 접근할 수 없습니다: " . $source);
        return false;
    }
    
    // 파일 크기 확인 (0바이트 파일 방지)
    $sourceSize = filesize($source);
    if ($sourceSize === 0) {
        error_log("원본 파일이 비어있습니다: " . $source);
        return false;
    }
    
    $info = getimagesize($source); 
    if ($info === false) {
        error_log("이미지 정보를 읽을 수 없습니다: " . $source);
        return false;
    }
    
    error_log("압축 대상 이미지: " . $source . " (크기: {$sourceSize} bytes, 타입: " . ($info['mime'] ?? 'unknown') . ")");
    
    // 이미지 리소스 생성
    $image = false;
    try {
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        } elseif ($info['mime'] == 'image/x-ms-bmp') {
            if (function_exists('imagecreatefrombmp')) {
                $image = imagecreatefrombmp($source);
            } else {
                error_log("BMP 파일을 지원하지 않습니다: " . $source);
                return false;
            }
        } else {
            error_log("지원하지 않는 이미지 형식: " . ($info['mime'] ?? 'unknown'));
            return false;
        }
        
        if ($image === false) {
            error_log("이미지 리소스 생성 실패: " . $source);
            return false;
        }
        
    } catch (Exception $e) {
        error_log("이미지 리소스 생성 중 오류: " . $e->getMessage());
        return false;
    }
    
    // 압축된 파일 저장
    $saveResult = imagejpeg($image, $destination, $quality);
    
    // 이미지 리소스 해제
    imagedestroy($image);
    
    if (!$saveResult || !file_exists($destination)) {
        error_log("압축 파일 저장 실패: " . $destination);
        return false;
    }
    
    $destSize = filesize($destination);
    error_log("압축 파일 저장 성공: " . $destination . " (크기: {$destSize} bytes)");
    
    // 크기 조정 (최대 800px)
    $destInfo = getimagesize($destination);
    if ($destInfo !== false) {
        list($width, $height) = $destInfo;
        if ($width > 800 || $height > 800) {
            error_log("이미지 크기 조정 필요: {$width}x{$height}");
            
            $rate = $width / $height;

            if ($width > $height) {
                $new_width = 800;
                $new_height = 800 / $rate;
            } else {
                $new_width = 800 * $rate;
                $new_height = 800;
            }
            
            error_log("새 크기: {$new_width}x{$new_height}");

            try {
                // `Image` 클래스를 사용해 크기 조정
                $imageObj = new Image($destination);
                $imageObj->width($new_width);
                $imageObj->height($new_height);
                $imageObj->save(false); // 파일을 다시 저장
                
                $finalSize = filesize($destination);
                error_log("크기 조정 완료: " . $destination . " (최종 크기: {$finalSize} bytes)");
                
            } catch (Exception $e) {
                error_log("크기 조정 실패: " . $e->getMessage());
                // 크기 조정 실패해도 압축된 파일은 사용 가능
            }
        }
    }

    return $destination; // 최종 파일 경로 반환
}
	
?>