<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? 0;
$filedelete = $_REQUEST["filedelete"] ?? '';

// 서버 파일명 초기화
$serverfilename = '';

// 디버그 출력 (개발 환경에서만)
if (isset($_FILES['mainBefore']['name'])) {
    error_log("업로드 파일: " . $_FILES['mainBefore']['name']);
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 파일 삭제 처리
if ($filedelete == 'before') {
    $newfilename = '';
    $delfile = "filename = ?";
    
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.error SET {$delfile} WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $newfilename, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_INT);
        $stmh->execute();
        
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("파일 삭제 오류: " . $ex->getMessage());
    }
}
					

// 이미지 리사이즈 클래스
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
            error_log("이미지 파일을 찾을 수 없음: " . $name);
            return;
        }
        
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
        if ($show) {
            @header('Content-Type: image/' . $this->type);
        }
        
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
        
        // 이미지 생성
        if ($this->type == 'jpeg') $image = imagecreatefromjpeg($this->file);
        elseif ($this->type == 'png') $image = imagecreatefrompng($this->file);
        elseif ($this->type == 'gif') $image = imagecreatefromgif($this->file);
        else return;
        
        $new_image = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($new_image, $image, 0, 0, $this->top, $this->left, 
                          $this->width, $this->height, $this->image_width, $this->image_height);
        
        $name = $show ? null : $this->dir . DIRECTORY_SEPARATOR . $this->name . '.' . $this->ext;
        
        // 이미지 저장
        if ($this->type == 'jpeg') imagejpeg($new_image, $name, $this->quality);
        elseif ($this->type == 'png') imagepng($new_image, $name);
        elseif ($this->type == 'gif') imagegif($new_image, $name);
        
        imagedestroy($image);
        imagedestroy($new_image);
    }
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
        error_log("지원하지 않는 이미지 형식: " . $info['mime']);
        return false;
    }
    
    imagejpeg($image, $destination, $quality);
    imagedestroy($image);
    return $destination;
}

// 파일 업로드 처리
if (isset($_FILES['mainBefore']['name']) && $_FILES['mainBefore']['name'] != '') {
    // 업로드 설정
    define('UPLOAD_ERR_INI_SIZE', "100000000");
    
    $uploads_dir = './img';
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
    $uploadSize = 100000000;
    
    // 업로드 디렉토리 생성 및 권한 설정
    if (!file_exists($uploads_dir)) {
        @mkdir($uploads_dir, 0755, true);
    }
    @chmod($uploads_dir, 0755);
    
    // 파일 정보 추출
    $error = $_FILES['mainBefore']['error'];
    $name = $_FILES['mainBefore']['name'];
    $tmpNm = explode('.', $name);
    $ext = strtolower(end($tmpNm));
    
    // 확장자 검증
    if (!in_array($ext, $allowed_ext)) {
        error_log("허용되지 않는 확장자: " . $ext);
        exit;
    }
    
    // 새 파일명 생성
    $new_file_name = date("Y_m_d_H_i_s");
    $newfilename = $new_file_name . "_1." . $ext;
    $url1 = $uploads_dir . '/' . $newfilename;
    
    // 기존 파일 삭제 (존재하는 경우)
    if (isset($_REQUEST["serverfilename"]) && $_REQUEST["serverfilename"] != '') {
        $old_file = './img/' . $_REQUEST["serverfilename"];
        if (file_exists($old_file)) {
            @unlink($old_file);
        }
    }
    
    $serverfilename = $newfilename;
    
    // 이미지 압축
    $filename = compress_image($_FILES["mainBefore"]["tmp_name"], $url1, 70);
    
    if ($filename) {
        // 이미지 크기 정보 가져오기
        list($width, $height, $type, $attr) = getImagesize($_FILES["mainBefore"]["tmp_name"]);
        
        // 리사이즈 비율 결정
        $switch_s = ($width > 700) ? 80 : 100;
        
        // 이미지 리사이즈
        $re_image = new Image($filename);
        $rate = $width / $height;
        
        if ($width > $height) {
            $re_image->width(800);
            $re_image->height(800 / $rate);
        } else {
            $re_image->width(800 * $rate);
            $re_image->height(800);
        }
        
        $re_image->save();
    }
}

$table = 'error';

// 데이터 처리 (INSERT/MODIFY)
if ($mode == "modify" || $mode == "insert") {
    $fieldarr = array();
    $strarr = array();
    
    // 필드명 배열
    array_push($fieldarr, 
        'part', 'reporter', 'place', 'occur', 'occurconfirm', 'errortype', 
        'saveurl', 'content', 'method', 'involved', 'filename', 'serverfilename', 
        'approve', 'steelrequirement', 'materialFee', 'deliveryFee', 'workFee', 
        'etcFee', 'totalFee', 'materialRaw'
    );
    
    // 기본 필드값 배열
    array_push($strarr,
        $_REQUEST["part"] ?? '',
        $_REQUEST["reporter"] ?? '',
        $_REQUEST["place"] ?? '',
        $_REQUEST["occur"] ?? '',
        $_REQUEST["occurconfirm"] ?? '',
        $_REQUEST["errortype"] ?? '',
        $_REQUEST["saveurl"] ?? '',
        $_REQUEST["content"] ?? '',
        $_REQUEST["method"] ?? '',
        $_REQUEST["involved"] ?? ''
    );
    
    // 파일명 처리
    if (!isset($_FILES['mainBefore']['name']) || $_FILES['mainBefore']['name'] == '') {
        array_push($strarr, 
            $_REQUEST["filename"] ?? '', 
            $_REQUEST["serverfilename"] ?? ''
        );
    } else {
        array_push($strarr, 
            $_FILES['mainBefore']['name'], 
            $serverfilename
        );
    }
    
    // 승인 및 강재 요구사항
    array_push($strarr, 
        $_REQUEST["approve"] ?? '', 
        $_REQUEST["steelrequirement"] ?? ''
    );
    
    // 비용 필드 (INT 변환, 콤마 제거)
    array_push($strarr,
        intval(str_replace(',', '', $_REQUEST["materialFee"] ?? '0')),
        intval(str_replace(',', '', $_REQUEST["deliveryFee"] ?? '0')),
        intval(str_replace(',', '', $_REQUEST["workFee"] ?? '0')),
        intval(str_replace(',', '', $_REQUEST["etcFee"] ?? '0')),
        intval(str_replace(',', '', $_REQUEST["totalFee"] ?? '0')),
        $_REQUEST["materialRaw"] ?? ''
    );
    
    // MODIFY 모드일 경우 num 추가
    if ($mode == "modify") {
        array_push($strarr, $num);
    }
}

// MODIFY 모드: 기존 레코드 수정
if ($mode == "modify") {
    try {
        // 기존 데이터 조회
        $sql = "SELECT * FROM {$DB}.{$table} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            error_log("수정할 레코드를 찾을 수 없음: num = " . $num);
            exit;
        }
    } catch (PDOException $ex) {
        error_log("레코드 조회 오류: " . $ex->getMessage());
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        // UPDATE 쿼리 생성
        $sql = "UPDATE {$DB}.{$table} SET ";
        for ($i = 0; $i < count($fieldarr); $i++) {
            if ($i != 0) $sql .= ', ';
            $sql .= $fieldarr[$i] . ' = ?';
        }
        $sql .= " WHERE num = ? LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        for ($i = 0; $i < count($strarr); $i++) {
            // 마지막 6개 필드는 INT (materialFee, deliveryFee, workFee, etcFee, totalFee + num)
            if ($i >= count($strarr) - 6) {
                $stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_INT);
            } else {
                $stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_STR);
            }
        }
        
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("레코드 수정 오류: " . $ex->getMessage());
    }
}

// INSERT 모드: 새 레코드 삽입
if ($mode == "insert") {
    $sql = "INSERT INTO {$DB}.{$table} (" . implode(', ', $fieldarr) . ") " .
           "VALUES (" . implode(', ', array_fill(0, count($fieldarr), '?')) . ")";
    
    try {
        $pdo->beginTransaction();
        $stmh = $pdo->prepare($sql);
        
        // 파라미터 바인딩
        for ($i = 0; $i < count($strarr); $i++) {
            // 마지막 5개 필드는 INT (materialFee, deliveryFee, workFee, etcFee, totalFee)
            if ($i >= count($strarr) - 5) {
                $stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_INT);
            } else {
                $stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_STR);
            }
        }
        
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("레코드 삽입 오류: " . $ex->getMessage());
    }
}

// DELETE 모드: 레코드 삭제
if ($mode == "delete") {
    try {
        // 파일명 조회
        $sql = "SELECT serverfilename FROM {$DB}.error WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row && $row["serverfilename"]) {
            $serverfilename = $row["serverfilename"];
            
            // 파일 삭제
            $file_path = './img/' . $serverfilename;
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
        }
    } catch (PDOException $ex) {
        error_log("파일 정보 조회 오류: " . $ex->getMessage());
    }
    
    try {
        $pdo->beginTransaction();
        
        // 레코드 삭제
        $sql = "DELETE FROM {$DB}.error WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("레코드 삭제 오류: " . $ex->getMessage());
    }
}

?>
 

 

