<?php
/**
 * Concert 레코드 등록/수정 처리 스크립트
 * 파일 업로드와 함께 게시글을 등록하거나 수정합니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../bootstrap.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<meta charset="utf-8">
<?php

// 세션 변수 초기화
$user_id = $_SESSION['userid'] ?? '';
$user_name = $_SESSION['name'] ?? '';
$user_nick = $_SESSION['nick'] ?? '';
$DB = $_SESSION['DB'] ?? 'phptest1';

// 로그인 확인
if (empty($user_id)) {
    ?>
    <script>
        alert('로그인 후 이용해 주세요.');
        history.back();
    </script>
    <?php
    exit;
}

// 요청 파라미터 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$html_ok = $_REQUEST["html_ok"] ?? '';
$subject = $_REQUEST["subject"] ?? '';
$content = $_REQUEST["content"] ?? '';

// 파일 업로드 관련 변수 초기화
$files = $_FILES["upfile"] ?? array();
$count = isset($files["name"]) ? count($files["name"]) : 0;
$upfile_name = array();
$upfile_tmp_name = array();
$upfile_type = array();
$upfile_size = array();
$upfile_error = array();
$copied_file_name = array();
$uploaded_file = array();
$del_ok = array();

// 업로드 디렉토리 설정 (환경에 따라 동적 설정)
$upload_dir = __DIR__ . '/../data/';
if (!is_dir($upload_dir)) {
    $upload_dir = 'C:/xampp/htdocs/data/';
}

// 디렉토리 생성 및 권한 설정
if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        error_log("Failed to create upload directory: {$upload_dir}");
        die("업로드 디렉토리 생성 실패");
    }
}

// 파일 업로드 처리
for ($i = 0; $i < $count; $i++) {
    $upfile_name[$i] = $files["name"][$i] ?? '';
    $upfile_tmp_name[$i] = $files["tmp_name"][$i] ?? '';
    $upfile_type[$i] = $files["type"][$i] ?? '';
    $upfile_size[$i] = $files["size"][$i] ?? 0;
    $upfile_error[$i] = $files["error"][$i] ?? UPLOAD_ERR_NO_FILE;
    
    // 파일명 초기화
    $copied_file_name[$i] = '';
    
    if ($upfile_error[$i] === UPLOAD_ERR_OK) {
        $file = explode(".", $upfile_name[$i]);
        $file_name = $file[0] ?? '';
        $file_ext = isset($file[1]) ? strtolower($file[1]) : '';
        
        // 파일 크기 검증 (5MB)
        if ($upfile_size[$i] > 5000000) {
            ?>
            <script>
                alert('업로드 파일 크기가 지정된 용량(5MB)을 초과합니다!\n파일 크기를 체크해주세요!');
                history.back();
            </script>
            <?php
            exit;
        }
        
        // 파일 타입 검증
        $allowed_types = array("image/gif", "image/jpeg", "image/jpg", "image/png");
        if (!in_array($upfile_type[$i], $allowed_types)) {
            ?>
            <script>
                alert('JPG, GIF, PNG 이미지 파일만 업로드 가능합니다!');
                history.back();
            </script>
            <?php
            exit;
        }
        
        // 새 파일명 생성
        $new_file_name = date("Y_m_d_H_i_s");
        $new_file_name = $new_file_name . "_" . $i;
        $copied_file_name[$i] = $new_file_name . "." . $file_ext;
        $uploaded_file[$i] = $upload_dir . $copied_file_name[$i];
        
        // 파일 이동
        if (!move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i])) {
            error_log("Failed to move uploaded file: {$upfile_name[$i]}");
            ?>
            <script>
                alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
                history.back();
            </script>
            <?php
            exit;
        }
    }
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 수정 모드
if ($mode == "modify") {
    // 삭제할 파일 확인
    $num_checked = isset($_REQUEST['del_file']) ? count($_REQUEST['del_file']) : 0;
    $position = $_REQUEST['del_file'] ?? array();
    
    for ($i = 0; $i < $num_checked; $i++) {
        $index = $position[$i];
        $del_ok[$index] = "y";
    }
    
    try {
        // 기존 레코드 조회
        $sql = "SELECT * FROM {$DB}.concert WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            throw new Exception("해당 레코드를 찾을 수 없습니다.");
        }
        
    } catch (PDOException $ex) {
        error_log("DB select error in insert.php: " . $ex->getMessage());
        die("데이터 조회 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
    
    // 파일 처리
    for ($i = 0; $i < $count; $i++) {
        $field_org_name = "file_name_" . $i;
        $field_real_name = "file_copied_" . $i;
        $org_name_value = $upfile_name[$i] ?? '';
        $org_real_value = $copied_file_name[$i] ?? '';
        
        if (isset($del_ok[$i]) && $del_ok[$i] == "y") {
            // 파일 삭제
            $delete_field = "file_copied_" . $i;
            $delete_name = $row[$delete_field] ?? '';
            
            if (!empty($delete_name)) {
                $delete_path = $upload_dir . $delete_name;
                
                if (file_exists($delete_path)) {
                    if (!unlink($delete_path)) {
                        error_log("Failed to delete file: {$delete_path}");
                    }
                }
            }
            
            try {
                $pdo->beginTransaction();
                $sql = "UPDATE {$DB}.concert SET {$field_org_name} = ?, {$field_real_name} = ? WHERE num = ?";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(1, $org_name_value, PDO::PARAM_STR);
                $stmh->bindValue(2, $org_real_value, PDO::PARAM_STR);
                $stmh->bindValue(3, $num, PDO::PARAM_STR);
                $stmh->execute();
                $pdo->commit();
            } catch (PDOException $ex) {
                $pdo->rollBack();
                error_log("DB update error in insert.php (delete): " . $ex->getMessage());
            }
        } else {
            // 새 파일 업로드
            if ($upfile_error[$i] === UPLOAD_ERR_OK && !empty($org_name_value)) {
                try {
                    $pdo->beginTransaction();
                    $sql = "UPDATE {$DB}.concert SET {$field_org_name} = ?, {$field_real_name} = ? WHERE num = ?";
                    $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(1, $org_name_value, PDO::PARAM_STR);
                    $stmh->bindValue(2, $org_real_value, PDO::PARAM_STR);
                    $stmh->bindValue(3, $num, PDO::PARAM_STR);
                    $stmh->execute();
                    $pdo->commit();
                } catch (PDOException $ex) {
                    $pdo->rollBack();
                    error_log("DB update error in insert.php (upload): " . $ex->getMessage());
                }
            }
        }
    }
    
    // 제목과 내용 업데이트
    try {
        $pdo->beginTransaction();
        $sql = "UPDATE {$DB}.concert SET subject = ?, content = ?, is_html = ? WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $html_ok, PDO::PARAM_STR);
        $stmh->bindValue(4, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("DB update error in insert.php (content): " . $ex->getMessage());
        die("데이터 업데이트 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
    
} else {
    // 신규 등록 모드
    if ($html_ok == "y") {
        $is_html = "y";
    } else {
        $is_html = "";
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
    }
    
    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO {$DB}.concert (id, name, nick, subject, content, regist_day, hit, is_html, file_name_0, file_name_1, file_name_2, file_copied_0, file_copied_1, file_copied_2) VALUES (?, ?, ?, ?, ?, NOW(), 0, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmh->bindValue(2, $user_name, PDO::PARAM_STR);
        $stmh->bindValue(3, $user_nick, PDO::PARAM_STR);
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);
        $stmh->bindValue(5, $content, PDO::PARAM_STR);
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);
        $stmh->bindValue(7, $upfile_name[0] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(8, $upfile_name[1] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(9, $upfile_name[2] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(10, $copied_file_name[0] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(11, $copied_file_name[1] ?? '', PDO::PARAM_STR);
        $stmh->bindValue(12, $copied_file_name[2] ?? '', PDO::PARAM_STR);
        $stmh->execute();
        
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log("DB insert error in insert.php: " . $ex->getMessage());
        die("데이터 등록 오류: " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}

// 로컬/서버 환경에 따른 동적 리다이렉션
$baseUrl = getBaseUrl();
$redirectUrl = $baseUrl . "/concert/list.php";

header("Location: " . $redirectUrl);
exit;

?>
