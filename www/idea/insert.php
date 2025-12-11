<?php
/**
 * Idea 직원 제안제도 입력/수정/삭제 처리 페이지
 * 제안사항을 등록, 수정, 삭제하고 첨부파일을 관리합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
require_once(includePath('session.php'));

// JSON 응답 헤더 설정
header("Content-Type: application/json; charset=utf-8");

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? 0;
$filedelete = $_REQUEST["filedelete"] ?? '';
$tablename = 'idea';

// 변수 초기화
$success = false;
$message = '';
$serverfilename = '';
$filename = '';
$newfilename = '';
$delfile = '';

// 응답 데이터
$response = array();

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

/**
 * Image 클래스 - 이미지 리사이징 및 압축
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
        if (empty($name) || !file_exists($name)) {
            throw new Exception('파일이 존재하지 않습니다: ' . $name);
        }
        
        $this->file = $name;
        $info = @getimagesize($name);
        
        if ($info === false) {
            throw new Exception('유효한 이미지 파일이 아닙니다: ' . $name);
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
        
        if ($this->type == 'jpeg') $image = imagecreatefromjpeg($this->file);
        if ($this->type == 'png') $image = imagecreatefrompng($this->file);
        if ($this->type == 'gif') $image = imagecreatefromgif($this->file);
        
        $new_image = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($new_image, $image, 0, 0, $this->top, $this->left, 
                          $this->width, $this->height, $this->image_width, $this->image_height);
        
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
 * @param string $source 원본 파일 경로
 * @param string $destination 대상 파일 경로
 * @param int $quality 품질 (0-100)
 * @return string 대상 파일 경로
 */
function compress_image($source, $destination, $quality) {
    $info = @getimagesize($source);
    
    if ($info === false) {
        error_log("Invalid image file in idea/insert.php: " . $source);
        return false;
    }
    
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } elseif ($info['mime'] == 'image/x-ms-bmp') {
        $image = imagecreatefrombmp($source);
    } else {
        error_log("Unsupported image type in idea/insert.php: " . $info['mime']);
        return false;
    }
    
    if ($image === false) {
        error_log("Failed to create image resource in idea/insert.php");
        return false;
    }
    
    imagejpeg($image, $destination, $quality);
    imagedestroy($image);
    
    return $destination;
}

// 파일 삭제 처리
if ($filedelete === 'before') {
    $newfilename = '';
    $delfile = "filename = ?";
    
    try {
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.{$tablename} SET {$delfile} WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $newfilename, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
        
        $success = true;
        $message = '파일이 삭제되었습니다.';
        
    } catch (PDOException $ex) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("File delete error in idea/insert.php: " . $ex->getMessage());
        
        $success = false;
        $message = '파일 삭제 중 오류가 발생했습니다.';
    }
    
    echo json_encode(array(
        'success' => $success,
        'message' => $message,
        'num' => $num
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

// 이미지 파일 업로드 처리
if (isset($_FILES['mainBefore']) && $_FILES['mainBefore']['name'] != '') {
    $uploads_dir = __DIR__ . '/img';
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
    
    // 디렉토리 생성
    if (!is_dir($uploads_dir)) {
        if (!mkdir($uploads_dir, 0755, true)) {
            error_log("Failed to create upload directory in idea/insert.php: " . $uploads_dir);
        }
    }
    
    // 업로드 에러 확인
    if ($_FILES['mainBefore']['error'] !== UPLOAD_ERR_OK) {
        error_log("Upload error in idea/insert.php: " . $_FILES['mainBefore']['error']);
    } else {
        $name = $_FILES['mainBefore']['name'];
        $tmpNm = explode('.', $name);
        $ext = strtolower(end($tmpNm));
        
        // 확장자 확인
        if (!in_array($ext, $allowed_ext)) {
            error_log("Invalid file extension in idea/insert.php: " . $ext);
        } else {
            $new_file_name = date("Y_m_d_H_i_s");
            $newfilename = $new_file_name . "_1." . $ext;
            $url1 = $uploads_dir . '/' . $newfilename;
            
            // 기존 파일 삭제
            $old_serverfilename = $_REQUEST["serverfilename"] ?? '';
            if (!empty($old_serverfilename) && file_exists($uploads_dir . '/' . $old_serverfilename)) {
                @unlink($uploads_dir . '/' . $old_serverfilename);
            }
            
            $serverfilename = $newfilename;
            
            // 이미지 압축
            try {
                $filename = compress_image($_FILES["mainBefore"]["tmp_name"], $url1, 70);
                
                if ($filename === false) {
                    error_log("Image compression failed in idea/insert.php");
                    $filename = $_FILES["mainBefore"]["tmp_name"];
                }
                
                // 이미지 크기 확인 및 리사이징
                $imageInfo = @getimagesize($_FILES["mainBefore"]["tmp_name"]);
                
                if ($imageInfo !== false) {
                    list($width, $height, $type, $attr) = $imageInfo;
                    
                    if ($width > 700) {
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
                
            } catch (Exception $ex) {
                error_log("Image processing error in idea/insert.php: " . $ex->getMessage());
            }
        }
    }
}

// 수정 또는 입력 모드
if ($mode === "modify" || $mode === "insert") {
    $fieldarr = array();
    $strarr = array();
    
    // 필드 배열 설정
    array_push($fieldarr, 'place');
    array_push($fieldarr, 'occur');
    array_push($fieldarr, 'errortype');
    array_push($fieldarr, 'emember');
    array_push($fieldarr, 'content');
    array_push($fieldarr, 'method');
    array_push($fieldarr, 'filename');
    array_push($fieldarr, 'serverfilename');
    array_push($fieldarr, 'approve');
    array_push($fieldarr, 'payment');
    array_push($fieldarr, 'firstone');
    
    // 값 배열 설정
    array_push($strarr, $_REQUEST["place"] ?? '');
    array_push($strarr, $_REQUEST["occur"] ?? '');
    
    // 체크박스 값 처리
    $errortype_val = "";
    if (isset($_REQUEST["errortype"])) {
        if (is_array($_REQUEST["errortype"])) {
            $errortype_val = implode("!", $_REQUEST["errortype"]);
        } else {
            $errortype_val = $_REQUEST["errortype"];
        }
    }
    array_push($strarr, $errortype_val);
    
    array_push($strarr, $_REQUEST["emember"] ?? '');
    array_push($strarr, $_REQUEST["content"] ?? '');
    array_push($strarr, $_REQUEST["method"] ?? '');
    
    // 파일 처리
    if (!isset($_FILES['mainBefore']) || $_FILES['mainBefore']['name'] == null) {
        array_push($strarr, $_REQUEST["filename"] ?? '');
        array_push($strarr, $_REQUEST["serverfilename"] ?? '');
    } else {
        array_push($strarr, $_FILES['mainBefore']['name']);
        array_push($strarr, $serverfilename);
    }
    
    array_push($strarr, $_REQUEST["approve"] ?? '');
    array_push($strarr, $_REQUEST["payment"] ?? '');
    array_push($strarr, $_REQUEST["firstone"] ?? '');
    
    if ($mode === "modify") {
        array_push($strarr, $num);
    }
}

// 수정 모드
if ($mode === "modify") {
    if (empty($num)) {
        echo json_encode(array(
            'success' => false,
            'message' => '잘못된 접근입니다. (num 누락)',
            'num' => $num
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        // 기존 레코드 확인
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            echo json_encode(array(
                'success' => false,
                'message' => '수정할 레코드를 찾을 수 없습니다.',
                'num' => $num
            ), JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // 수정 처리
        $pdo->beginTransaction();
        
        $sql = "UPDATE {$DB}.{$tablename} SET ";
        for ($i = 0; $i < count($fieldarr); $i++) {
            if ($i != 0) {
                $sql .= ', ';
            }
            $sql .= $fieldarr[$i] . ' = ?';
        }
        $sql .= " WHERE num = ? LIMIT 1";
        
        $stmh = $pdo->prepare($sql);
        for ($i = 0; $i < count($strarr); $i++) {
            $stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_STR);
        }
        
        $stmh->execute();
        $rowCount = $stmh->rowCount();
        
        $pdo->commit();
        
        if ($rowCount > 0) {
            $success = true;
            $message = '수정되었습니다.';
        } else {
            $success = false;
            $message = '수정할 내용이 없습니다.';
        }
        
    } catch (PDOException $ex) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Update error in idea/insert.php: " . $ex->getMessage());
        
        $success = false;
        $message = '수정 중 오류가 발생했습니다.';
    }
}

// 신규 입력 모드
if ($mode === "insert") {
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.{$tablename} (";
        for ($j = 0; $j < count($fieldarr); $j++) {
            if ($j != 0) {
                $sql .= ', ';
            }
            $sql .= $fieldarr[$j];
        }
        $sql .= ') VALUES (';
        
        for ($j = 0; $j < count($fieldarr); $j++) {
            if ($j != 0) {
                $sql .= ', ';
            }
            $sql .= '?';
        }
        $sql .= ')';
        
        $stmh = $pdo->prepare($sql);
        for ($i = 0; $i < count($strarr); $i++) {
            $stmh->bindValue($i + 1, $strarr[$i], PDO::PARAM_STR);
        }
        
        $stmh->execute();
        $num = $pdo->lastInsertId();
        
        $pdo->commit();
        
        $success = true;
        $message = '등록되었습니다.';
        
    } catch (PDOException $ex) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Insert error in idea/insert.php: " . $ex->getMessage());
        
        $success = false;
        $message = '등록 중 오류가 발생했습니다.';
    }
}

// 삭제 모드
if ($mode === "delete") {
    if (empty($num)) {
        echo json_encode(array(
            'success' => false,
            'message' => '잘못된 접근입니다. (num 누락)',
            'num' => $num
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        // 파일 정보 조회
        $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $serverfilename = $row["serverfilename"] ?? '';
            
            // 서버에서 파일 삭제
            if (!empty($serverfilename)) {
                $file_path = __DIR__ . '/img/' . $serverfilename;
                if (file_exists($file_path)) {
                    if (!@unlink($file_path)) {
                        error_log("Failed to delete file in idea/insert.php: " . $file_path);
                    }
                }
            }
        }
        
        // DB 레코드 삭제
        $pdo->beginTransaction();
        
        $sql = "DELETE FROM {$DB}.{$tablename} WHERE num = ? LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        
        $rowCount = $stmh->rowCount();
        
        $pdo->commit();
        
        if ($rowCount > 0) {
            $success = true;
            $message = '삭제되었습니다.';
        } else {
            $success = false;
            $message = '삭제할 레코드를 찾을 수 없습니다.';
        }
        
    } catch (PDOException $ex) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Delete error in idea/insert.php: " . $ex->getMessage());
        
        $success = false;
        $message = '삭제 중 오류가 발생했습니다.';
    }
}

// JSON 응답 생성
$response = array(
    'success' => $success,
    'message' => $message,
    'num' => $num,
    'tablename' => $tablename
);

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>
