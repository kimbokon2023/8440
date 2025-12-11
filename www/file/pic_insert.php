<?php
/**
 * 이미지 업로드 처리 스크립트
 * 이미지를 업로드하고 압축/리사이징 후 DB에 저장합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// common.php 포함
include __DIR__ . "/../php/common.php";

// 세션 변수 초기화
$user_id = $_SESSION['userid'] ?? '';
$user_name = $_SESSION['name'] ?? '';
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';
$workplacename = $_REQUEST["workplacename"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$item = $_REQUEST["item"] ?? '';

// 파일 업로드 관련 변수 초기화
$filechoice = 'upfile';
$uploads_dir = __DIR__ . '/../uploads'; // 업로드 폴더
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
$uploadSize = 100000000; // 최대 업로드 크기

// 입력 검증
if (empty($tablename)) {
    error_log("pic_insert.php error: No tablename specified");
    die("테이블명이 지정되지 않았습니다.");
}

if (!isset($_FILES[$filechoice]) || empty($_FILES[$filechoice]['name'])) {
    error_log("pic_insert.php error: No file uploaded");
    die("업로드할 파일이 없습니다.");
}

// 파일 개수 확인
$countfiles = count($_FILES[$filechoice]['name']);

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 업로드 디렉토리 생성 및 권한 설정
if (!is_dir($uploads_dir)) {
    if (!mkdir($uploads_dir, 0755, true)) {
        error_log("Failed to create upload directory: {$uploads_dir}");
        die("업로드 디렉토리 생성 실패");
    }
}
chmod($uploads_dir, 0755);

// 이미지 업로드 처리
for ($i = 0; $i < $countfiles; $i++) {
    $filename = $_FILES[$filechoice]['name'][$i];
    
    if (empty($filename)) {
        continue;
    }
    
    try {
        // 파일 정보 추출
        $error = $_FILES[$filechoice]['error'][$i];
        $name = $_FILES[$filechoice]['name'][$i];
        $tmp_file = $_FILES[$filechoice]['tmp_name'][$i];
        $tmpNm = explode('.', $name);
        $ext = strtolower(end($tmpNm));
        
        echo htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . "<br>";
        
        // 에러 확인
        if ($error !== UPLOAD_ERR_OK) {
            error_log("File upload error code: {$error} for file: {$name}");
            continue;
        }
        
        // 확장자 확인
        if (!in_array($ext, $allowed_ext)) {
            echo "허용되지 않는 이미지 확장자입니다: " . htmlspecialchars($ext, ENT_QUOTES, 'UTF-8') . "<br>";
            continue;
        }
        
        // 새 파일명 생성
        $new_file_name = date("Y_m_d_H_i_s");
        $newfilename1 = $new_file_name . "_" . $i . "." . $ext;
        $url1 = $uploads_dir . '/' . $newfilename1;
        
        // 이미지 압축 및 저장
        $filename1 = compress_image($tmp_file, $url1, 70);
        
        if ($filename1 === false) {
            error_log("Image compression failed for: {$name}");
            continue;
        }
        
        // 이미지 크기 확인
        $image_info = @getimagesize($tmp_file);
        if ($image_info === false) {
            error_log("Failed to get image size for: {$name}");
            continue;
        }
        
        list($width, $height, $type, $attr) = $image_info;
        
        echo "Width: {$width}<br>";
        echo "Height: {$height}<br>";
        echo "Type: {$type}<br>";
        
        // JPEG/JPG 이미지 회전 처리
        if ($ext == 'jpg' || $ext == 'jpeg') {
            $exif = @exif_read_data($url1);
            
            if ($exif && !empty($exif['Orientation'])) {
                echo '사진 정보: ' . $exif['Orientation'] . "<br>";
                
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
                    imagejpeg($rotated, $url1, 90);
                    imagedestroy($rotated);
                }
                imagedestroy($image);
            }
        }
        
        // 이미지 리사이징
        if (class_exists('Image')) {
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
        }
        
        // 파일 정보 출력
        echo "<h2>파일 정보</h2>
            <ul>
                <li>자료번호: " . htmlspecialchars($num, ENT_QUOTES, 'UTF-8') . "</li>
                <li>파일명: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</li>
                <li>확장자: {$ext}</li>
                <li>저장명: {$newfilename1}</li>
                <li>크기: {$width}x{$height}</li>
            </ul>";
        
        // DB에 파일 정보 저장
        try {
            $pdo->beginTransaction();
            
            $sql = "INSERT INTO {$DB}.picuploads (tablename, item, parentnum, picname) VALUES (?, ?, ?, ?)";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $tablename, PDO::PARAM_STR);
            $stmh->bindValue(2, $item, PDO::PARAM_STR);
            $stmh->bindValue(3, $num, PDO::PARAM_STR);
            $stmh->bindValue(4, $newfilename1, PDO::PARAM_STR);
            $stmh->execute();
            
            $pdo->commit();
        } catch (PDOException $ex) {
            $pdo->rollBack();
            error_log("DB insert error in pic_insert.php: " . $ex->getMessage());
            echo "DB 저장 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "<br>";
        }
        
    } catch (Exception $ex) {
        error_log("Image upload error in pic_insert.php: " . $ex->getMessage());
        echo "이미지 업로드 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8') . "<br>";
    }
}

// 로그 기록 남기기
try {
    $data = date("Y-m-d H:i:s") . " - " . $user_id . " - " . $user_name . " " . $workplacename . " - 사진기록";
    
    $pdo->beginTransaction();
    $sql = "INSERT INTO {$DB}.logdata (data) VALUES (?)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $data, PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("Log insert error in pic_insert.php: " . $ex->getMessage());
}

?>
