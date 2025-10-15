<?php
/**
 * 천장 사진 업로드 처리 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    } elseif (isset($_POST[$key])) {
        return $_POST[$key];
    }
    return $default;
}

// 파일 압축 함수
function compress_image($source, $destination, $quality) {
    $info = getimagesize($source);
    
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } elseif ($info['mime'] == 'image/x-ms-bmp') {
        $image = imagecreatefrombmp($source);
    } else {
        return false;
    }
    
    if ($image === false) {
        return false;
    }
    
    imagejpeg($image, $destination, $quality);
    imagedestroy($image);
    return $destination;
}

// Image 클래스 정의
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
    var $dir;
    var $name;
    
    function __construct($name = '') {
        $this->file = $name;
        $info = getimagesize($name);
        $this->image_width = $info[0];
        $this->image_height = $info[1];
        $this->type = $this->types[$info[2]];
        $info = pathinfo($name);
        $this->dir = $info['dirname'];
        $this->name = str_replace('.' . $info['extension'], '', $info['basename']);
        $this->ext = $info['extension'];
    }
    
    function dir($dir = '') {
        if (!$dir) return $this->dir;
        $this->dir = $dir;
    }
    
    function name($name = '') {
        if (!$name) return $this->name;
        $this->name = $name;
    }
    
    function width($width = '') {
        $this->width = $width;
    }
    
    function height($height = '') {
        $this->height = $height;
    }
    
    function resize($percentage = 50) {
        if ($this->crop) {
            $this->crop = false;
            $this->width = round($this->width * ($percentage / 100));
            $this->height = round($this->height * ($percentage / 100));
            $this->image_width = round($this->width / ($percentage / 100));
            $this->image_height = round($this->height / ($percentage / 100));
        } else {
            $this->width = round($this->image_width * ($percentage / 100));
            $this->height = round($this->image_height * ($percentage / 100));
        }
    }
    
    function crop($top = 0, $left = 0) {
        $this->crop = true;
        $this->top = $top;
        $this->left = $left;
    }
    
    function quality($quality = 70) {
        $this->quality = $quality;
    }
    
    function show() {
        $this->save(true);
    }
    
    function save($show = false) {
        if ($show) @header('Content-Type: image/' . $this->type);
        
        if (!$this->width && !$this->height) {
            $this->width = $this->image_width;
            $this->height = $this->image_height;
        } elseif (is_numeric($this->width) && empty($this->height)) {
            $this->height = round($this->width / ($this->image_width / $this->image_height));
        } elseif (is_numeric($this->height) && empty($this->width)) {
            $this->width = round($this->height / ($this->image_height / $this->image_width));
        } else {
            if ($this->width <= $this->height) {
                $height = round($this->width / ($this->image_width / $this->image_height));
                if ($height != $this->height) {
                    $percentage = ($this->image_height * 100) / $height;
                    $this->image_height = round($this->height * ($percentage / 100));
                }
            } else {
                $width = round($this->height / ($this->image_height / $this->image_width));
                if ($width != $this->width) {
                    $percentage = ($this->image_width * 100) / $width;
                    $this->image_width = round($this->width * ($percentage / 100));
                }
            }
        }
        
        if ($this->crop) {
            $this->image_width = $this->width;
            $this->image_height = $this->height;
        }
        
        if ($this->type == 'jpeg') $image = imagecreatefromjpeg($this->file);
        if ($this->type == 'png') $image = imagecreatefrompng($this->file);
        if ($this->type == 'gif') $image = imagecreatefromgif($this->file);
        
        $new_image = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($new_image, $image, 0, 0, $this->top, $this->left, $this->width, $this->height, $this->image_width, $this->image_height);
        
        $name = $show ? null : $this->dir . DIRECTORY_SEPARATOR . $this->name . '.' . $this->ext;
        
        if ($this->type == 'jpeg') imagejpeg($new_image, $name, $this->quality);
        if ($this->type == 'png') imagepng($new_image, $name);
        if ($this->type == 'gif') imagegif($new_image, $name);
        
        imagedestroy($image);
        imagedestroy($new_image);
    }
}

// 요청 변수 초기화
$num = getRequestValue("num", '');
$check = getRequestValue("check", '0');

// 파일 업로드 설정
$uploads_dir = '../imgceiling';
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
$uploadSize = 100000000;

// 업로드 디렉토리 생성 및 권한 설정
if (!file_exists($uploads_dir)) {
    @mkdir($uploads_dir, 0755, true);
}
@chmod($uploads_dir, 0755);

// 업로드된 파일 개수 확인
if (!isset($_FILES['upfile']) || !isset($_FILES['upfile']['name'])) {
    echo "업로드된 파일이 없습니다.";
    exit;
}

$countfiles = count($_FILES['upfile']['name']);

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 파일 처리 루프
for ($i = 0; $i < $countfiles; $i++) {
    $filename = $_FILES['upfile']['name'][$i] ?? '';
    
    // 파일이 없으면 건너뛰기
    if (empty($filename)) {
        continue;
    }
    
    echo "처리 중: " . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . "<br>";
    
    // 파일 확장자 확인
    $tmpNm = explode('.', $filename);
    $ext = strtolower(end($tmpNm));
    
    echo "확장자: " . htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . "<br>";
    
    // 확장자 검증
    if (!in_array($ext, $allowed_ext)) {
        echo "허용되지 않는 확장자입니다: " . htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . "<br>";
        continue;
    }
    
    // 임시 파일 확인
    if (!isset($_FILES['upfile']["tmp_name"][$i]) || empty($_FILES['upfile']["tmp_name"][$i])) {
        echo "임시 파일이 없습니다.<br>";
        continue;
    }
    
    // 업로드 에러 확인
    if ($_FILES['upfile']['error'][$i] !== UPLOAD_ERR_OK) {
        echo "파일 업로드 오류: " . $_FILES['upfile']['error'][$i] . "<br>";
        continue;
    }
    
    // 새 파일명 생성
    $new_file_name = date("Y_m_d_H_i_s");
    $newfilename1 = $new_file_name . "_" . $i . "." . $ext;
    $url1 = $uploads_dir . '/' . $newfilename1;
    
    // 파일 크기 정보 가져오기
    $imageInfo = @getimagesize($_FILES['upfile']["tmp_name"][$i]);
    
    if ($imageInfo === false) {
        echo "유효한 이미지 파일이 아닙니다.<br>";
        continue;
    }
    
    list($width, $height, $type, $attr) = $imageInfo;
    
    echo "크기: {$width} x {$height}<br>";
    echo "타입: {$type}<br>";
    echo "속성: {$attr}<br>";
    
    // 압축 비율 결정
    $switch_s = ($width > 700) ? 80 : 100;
    
    // 이미지 압축 및 저장
    try {
        $filename1 = compress_image($_FILES['upfile']["tmp_name"][$i], $url1, 70);
        
        if (!$filename1) {
            echo "이미지 압축 실패<br>";
            continue;
        }
    } catch (Exception $ex) {
        error_log("이미지 압축 오류: " . $ex->getMessage());
        echo "이미지 처리 중 오류가 발생했습니다.<br>";
        continue;
    }
    
    // 파일 정보 출력
    echo "<h2>파일 정보</h2><h1>
        <ul>
            <li>자료번호: " . htmlspecialchars($num, ENT_QUOTES, 'UTF-8') . "</li>
            <li>파일명: " . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . "</li>
            <li>확장자: " . htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . "</li>
            <li>URL: " . htmlspecialchars($url1, ENT_QUOTES, 'UTF-8') . "</li>
            <li>저장된 파일명: " . htmlspecialchars($newfilename1, ENT_QUOTES, 'UTF-8') . "</li>
        </ul>
    </h1>";
    
    // 이미지 리사이징
    try {
        $re_image = new Image($filename1);
        $rate = $width / $height;
        
        if ($width > $height) {
            $re_image->width(800);
            $re_image->height(800 / $rate);
        } else {
            $re_image->width(800 * $rate);
            $re_image->height(800);
        }
        
        $re_image->save();
    } catch (Exception $ex) {
        error_log("이미지 리사이징 오류: " . $ex->getMessage());
        echo "이미지 리사이징 중 오류가 발생했습니다.<br>";
    }
    
    // 데이터베이스에 파일 정보 저장
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO mirae8440.ceilpicfile (parentnum, picname) VALUES (?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->bindValue(2, $newfilename1, PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
        
        echo "데이터베이스 저장 완료<br>";
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("DB 저장 오류 (num: {$num}, file: {$newfilename1}): " . $ex->getMessage());
        echo "오류: 데이터베이스 저장 중 문제가 발생했습니다.<br>";
    }
    
} // end of for loop

// 부모창 업데이트 및 창 닫기
echo "<script> 
    if (window.opener && window.opener.document.getElementById('pInput')) {
        window.opener.document.getElementById('pInput').value = '100';
    }
    self.close();
</script>";
?>
