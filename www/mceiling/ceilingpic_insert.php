<?php
/**
 * 천장 포장 사진 업로드 처리 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

include "../php/common.php";

// 공통 변수 초기화 함수
function getRequestValue($key, $default = '') {
    if (isset($_REQUEST[$key])) {
        return $_REQUEST[$key];
    }
    return $default;
}

// 요청 변수 초기화
$num = getRequestValue("num", '');
$workplacename = getRequestValue("workplacename", '');

// 테이블 정보
$tablename = "ceilingwrap";
$item = "ceilingwrap";

// 파일 업로드 설정
$filechoice = 'upfile';
$uploads_dir = '../imgceiling';
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF');
$uploadSize = 100000000;

// 업로드 디렉토리 생성 및 권한 설정
if (!file_exists($uploads_dir)) {
    @mkdir($uploads_dir, 0755, true);
}
@chmod($uploads_dir, 0755);

// 업로드된 파일 개수 확인
if (!isset($_FILES[$filechoice]) || !isset($_FILES[$filechoice]['name'])) {
    echo "업로드된 파일이 없습니다.";
    exit;
}

$countfiles = count($_FILES[$filechoice]['name']);

// 데이터베이스 연결
require_once("../lib/mydb.php");
$pdo = db_connect();

// 파일 처리 루프
for ($i = 0; $i < $countfiles; $i++) {
    $filename = $_FILES[$filechoice]['name'][$i] ?? '';
    
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
    if (!isset($_FILES[$filechoice]["tmp_name"][$i]) || empty($_FILES[$filechoice]["tmp_name"][$i])) {
        echo "임시 파일이 없습니다.<br>";
        continue;
    }
    
    // 업로드 에러 확인
    if ($_FILES[$filechoice]['error'][$i] !== UPLOAD_ERR_OK) {
        echo "파일 업로드 오류: " . $_FILES[$filechoice]['error'][$i] . "<br>";
        continue;
    }
    
    // 새 파일명 생성
    $new_file_name = date("Y_m_d_H_i_s");
    $newfilename1 = $new_file_name . "_" . $i . "." . $ext;
    $url1 = $uploads_dir . '/' . $newfilename1;
    
    // 파일 크기 정보 가져오기
    $imageInfo = @getimagesize($_FILES[$filechoice]["tmp_name"][$i]);
    
    if ($imageInfo === false) {
        echo "유효한 이미지 파일이 아닙니다.<br>";
        continue;
    }
    
    list($width, $height, $type, $attr) = $imageInfo;
    
    echo "크기: {$width} x {$height}<br>";
    echo "타입: {$type}<br>";
    
    // 압축 비율 결정
    $compress_quality = ($width > 700) ? 80 : 100;
    
    // 이미지 압축 및 저장
    try {
        $filename1 = compress_image($_FILES[$filechoice]["tmp_name"][$i], $url1, 70);
        
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
    
    // 이미지 리사이징 (Image 클래스 사용 - PHP Imagick 또는 사용자 정의 클래스로 가정)
    // 만약 Image 클래스가 정의되어 있다면 사용, 없으면 건너뜀
    if (class_exists('Image')) {
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
    }
    
    // 데이터베이스에 파일 정보 저장
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO mirae8440.ceilpicfile (tablename, item, parentnum, picname) 
                VALUES (?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $tablename, PDO::PARAM_STR);
        $stmh->bindValue(2, $item, PDO::PARAM_STR);
        $stmh->bindValue(3, $num, PDO::PARAM_STR);
        $stmh->bindValue(4, $newfilename1, PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
        
        echo "데이터베이스 저장 완료<br>";
        
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("DB 저장 오류 (num: {$num}, file: {$newfilename1}): " . $ex->getMessage());
        echo "오류: 데이터베이스 저장 중 문제가 발생했습니다.<br>";
    }
    
} // end of for loop

// 로그 기록
$data = date("Y-m-d H:i:s") . " - " . 
        ($_SESSION["userid"] ?? 'unknown') . " - " . 
        ($_SESSION["name"] ?? 'unknown') . " - " . 
        $workplacename . " - 포장사진";

try {
    $pdo->beginTransaction();
    
    $sql = "INSERT INTO mirae8440.logdata(data) VALUES (?)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $data, PDO::PARAM_STR);
    $stmh->execute();
    
    $pdo->commit();
    
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log("로그 기록 오류: " . $ex->getMessage());
}

// 부모창 업데이트 (선택사항)
// echo "<script> opener.document.getElementById('pInput').value='100'; </script>";

echo "<br><h3>업로드 완료</h3>";
echo "<a href='javascript:window.close();'>창 닫기</a>";
?>
