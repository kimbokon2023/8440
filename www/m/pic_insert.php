<?php
/**
 * 사진 업로드 처리 페이지
 * 시공 전후 사진을 업로드하고 리사이징하여 저장합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$user_id = $_SESSION["userid"] ?? '';
$user_name = $_SESSION["name"] ?? '';

// 로그인 확인
if (!isset($_SESSION["userid"])) {
?>
    <script>
        alert('로그인 후 이용해 주세요.');
        history.back();
    </script>
<?php
    exit;
}

// 요청 파라미터 초기화
$workplacename = $_REQUEST["workplacename"] ?? '';
$num = $_REQUEST["num"] ?? '';

/**
 * Image 클래스
 * 이미지 리사이징 및 처리를 위한 클래스
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
    var $dir;
    var $name;
    
    function __construct($name = '') {
        if (!file_exists($name)) {
            throw new Exception("파일을 찾을 수 없습니다: " . $name);
        }
        
        $this->file = $name;
        $info = @getimagesize($name);
        
        if ($info === false) {
            throw new Exception("유효하지 않은 이미지 파일입니다.");
        }
        
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
        
        $image = null;
        if ($this->type == 'jpeg') $image = imagecreatefromjpeg($this->file);
        if ($this->type == 'png') $image = imagecreatefrompng($this->file);
        if ($this->type == 'gif') $image = imagecreatefromgif($this->file);
        
        if ($image === null) {
            throw new Exception("이미지를 생성할 수 없습니다.");
        }
        
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

/**
 * 이미지 압축 함수
 * @param string $source - 원본 파일 경로
 * @param string $destination - 저장 파일 경로
 * @param int $quality - JPEG 품질 (0-100)
 * @return string - 저장된 파일 경로
 */
function compress_image($source, $destination, $quality) {
    $info = @getimagesize($source);
    
    if ($info === false) {
        throw new Exception("유효하지 않은 이미지 파일입니다.");
    }
    
    $image = null;
    
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
            throw new Exception("BMP 이미지는 지원되지 않습니다.");
        }
    }
    
    if ($image === null) {
        throw new Exception("이미지를 생성할 수 없습니다.");
    }
    
    imagejpeg($image, $destination, $quality);
    imagedestroy($image);
    
    return $destination;
}

// 업로드 디렉토리 설정
$uploads_dir = __DIR__ . '/../imgwork';
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');

// 디렉토리 생성 및 권한 설정
if (!is_dir($uploads_dir)) {
    if (!mkdir($uploads_dir, 0755, true)) {
        error_log("디렉토리 생성 실패: " . $uploads_dir);
        die("업로드 디렉토리를 생성할 수 없습니다.");
    }
}
chmod($uploads_dir, 0755);

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 시공 전 사진 처리
if (isset($_FILES['mainBefore']) && $_FILES['mainBefore']['name'] != '') {
    try {
        // 파일 업로드 에러 확인
        if ($_FILES['mainBefore']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("파일 업로드 오류: " . $_FILES['mainBefore']['error']);
        }
        
        // 변수 정리
        $name = $_FILES['mainBefore']['name'];
        $tmpNm = explode('.', $name);
        $ext = strtolower(end($tmpNm));
        
        echo htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . "<br>";
        
        // 확장자 확인
        if (!in_array($ext, $allowed_ext)) {
            throw new Exception("허용되지 않는 확장자입니다.");
        }
        
        // 파일명 생성
        $new_file_name = date("Y_m_d_H_i_s");
        $newfilename1 = $new_file_name . "_1." . $ext;
        $url1 = $uploads_dir . '/' . $newfilename1;
        
        // 이미지 압축 및 저장
        $filename1 = compress_image($_FILES["mainBefore"]["tmp_name"], $url1, 70);
        
        // 이미지 정보 가져오기
        $image_info = @getimagesize($_FILES["mainBefore"]["tmp_name"]);
        if ($image_info === false) {
            throw new Exception("이미지 정보를 가져올 수 없습니다.");
        }
        
        list($width, $height, $type, $attr) = $image_info;
        
        echo htmlspecialchars($width, ENT_QUOTES, 'UTF-8') . "<br>";
        echo htmlspecialchars($height, ENT_QUOTES, 'UTF-8') . "<br>";
        echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . "<br>";
        echo htmlspecialchars($attr, ENT_QUOTES, 'UTF-8') . "<br>";
        
        // EXIF 데이터 처리 (회전)
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($url1);
            
            if ($exif && !empty($exif['Orientation'])) {
                echo "사진 정보: " . htmlspecialchars($exif['Orientation'], ENT_QUOTES, 'UTF-8') . "<br>";
                
                $image = imagecreatefromjpeg($url1);
                $rotated = null;
                
                switch ($exif['Orientation']) {
                    case 8:
                        $rotated = imagerotate($image, 90, 0);
                        break;
                    case 3:
                        $rotated = imagerotate($image, 180, 0);
                        break;
                    case 6:
                        $rotated = imagerotate($image, -90, 0);
                        break;
                }
                
                if ($rotated !== null) {
                    imagejpeg($rotated, $url1, 70);
                    imagedestroy($rotated);
                }
                
                imagedestroy($image);
            }
        }
        
        // 파일 정보 출력
        echo "<h2>파일 정보</h2><h1>
            <ul>
                <li>자료번호: " . htmlspecialchars($num, ENT_QUOTES, 'UTF-8') . "</li>
                <li>파일명: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</li>
                <li>확장자: " . htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . "</li>
                <li>파일형식: " . htmlspecialchars($_FILES['mainBefore']['type'], ENT_QUOTES, 'UTF-8') . "</li>
                <li>파일크기: " . htmlspecialchars($_FILES['mainBefore']['size'], ENT_QUOTES, 'UTF-8') . " 바이트</li>
                <li>url: " . htmlspecialchars($url1, ENT_QUOTES, 'UTF-8') . "</li>
                <li>filename: " . htmlspecialchars($filename1, ENT_QUOTES, 'UTF-8') . "</li>
            </ul></h1>";
        
        // 이미지 리사이징
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
        
        // 데이터베이스 업데이트
        try {
            $pdo->beginTransaction();
            
            $sql = "UPDATE {$DB}.work SET filename1 = ? WHERE num = ? LIMIT 1";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $newfilename1, PDO::PARAM_STR);
            $stmh->bindValue(2, $num, PDO::PARAM_STR);
            $stmh->execute();
            
            $pdo->commit();
        } catch (PDOException $ex) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("DB update error in m/pic_insert.php (mainBefore): " . $ex->getMessage());
            throw new Exception("데이터베이스 업데이트 중 오류가 발생했습니다.");
        }
        
    } catch (Exception $e) {
        error_log("mainBefore upload error: " . $e->getMessage());
        echo "<p style='color:red;'>오류: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    }
}

// 시공 후 사진 처리
if (isset($_FILES['mainAfter']) && $_FILES['mainAfter']['name'] != '') {
    try {
        // 파일 업로드 에러 확인
        if ($_FILES['mainAfter']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("파일 업로드 오류: " . $_FILES['mainAfter']['error']);
        }
        
        // 변수 정리
        $name = $_FILES['mainAfter']['name'];
        $tmpNm = explode('.', $name);
        $ext = strtolower(end($tmpNm));
        
        echo htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . "<br>";
        
        // 확장자 확인
        if (!in_array($ext, $allowed_ext)) {
            throw new Exception("허용되지 않는 확장자입니다.");
        }
        
        // 파일명 생성
        $new_file_name = date("Y_m_d_H_i_s");
        $newfilename2 = $new_file_name . "_2." . $ext;
        $url2 = $uploads_dir . '/' . $newfilename2;
        
        // 이미지 압축 및 저장
        $filename2 = compress_image($_FILES["mainAfter"]["tmp_name"], $url2, 70);
        
        // 이미지 정보 가져오기
        $image_info = @getimagesize($_FILES["mainAfter"]["tmp_name"]);
        if ($image_info === false) {
            throw new Exception("이미지 정보를 가져올 수 없습니다.");
        }
        
        list($width, $height, $type, $attr) = $image_info;
        
        echo htmlspecialchars($width, ENT_QUOTES, 'UTF-8') . "<br>";
        echo htmlspecialchars($height, ENT_QUOTES, 'UTF-8') . "<br>";
        echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . "<br>";
        echo htmlspecialchars($attr, ENT_QUOTES, 'UTF-8') . "<br>";
        
        // 파일 정보 출력
        echo "<h2>파일 정보</h2><h1>
            <ul>
                <li>파일명: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</li>
                <li>확장자: " . htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . "</li>
                <li>파일형식: " . htmlspecialchars($_FILES['mainAfter']['type'], ENT_QUOTES, 'UTF-8') . "</li>
                <li>파일크기: " . htmlspecialchars($_FILES['mainAfter']['size'], ENT_QUOTES, 'UTF-8') . " 바이트</li>
                <li>url: " . htmlspecialchars($url2, ENT_QUOTES, 'UTF-8') . "</li>
                <li>filename: " . htmlspecialchars($filename2, ENT_QUOTES, 'UTF-8') . "</li>
            </ul></h1>";
        
        // 이미지 리사이징
        $re_image = new Image($filename2);
        $rate = $width / $height;
        
        if ($width > $height) {
            $re_image->width(800);
            $re_image->height(800 / $rate);
        } else {
            $re_image->width(800 * $rate);
            $re_image->height(800);
        }
        
        $re_image->save();
        
        // 데이터베이스 업데이트
        try {
            $pdo->beginTransaction();
            
            $sql = "UPDATE {$DB}.work SET filename2 = ? WHERE num = ? LIMIT 1";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $newfilename2, PDO::PARAM_STR);
            $stmh->bindValue(2, $num, PDO::PARAM_STR);
            $stmh->execute();
            
            $pdo->commit();
        } catch (PDOException $ex) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("DB update error in m/pic_insert.php (mainAfter): " . $ex->getMessage());
            throw new Exception("데이터베이스 업데이트 중 오류가 발생했습니다.");
        }
        
    } catch (Exception $e) {
        error_log("mainAfter upload error: " . $e->getMessage());
        echo "<p style='color:red;'>오류: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    }
}

// 로그 기록
try {
    $data = date("Y-m-d H:i:s") . " - " . $user_id . " - " . $user_name . " " . $workplacename . " - 사진기록";
    
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO {$DB}.logdata(data) VALUES(?)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $data, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
} catch (PDOException $ex) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Log insert error in m/pic_insert.php: " . $ex->getMessage());
}

// 리다이렉션
$baseUrl = getBaseUrl();
header("Location: " . $baseUrl . "/m/reg_pic.php?num=" . urlencode($num));
exit;
