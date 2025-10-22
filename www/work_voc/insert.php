<?php
/**
 * work_voc 모듈 - 데이터 삽입/수정 처리
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? '';

// 권한 체크
if ($level > 8) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 요청 변수 초기화
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$html_ok = $_REQUEST["html_ok"] ?? '';
$subject = $_REQUEST["subject"] ?? '';
$content = $_REQUEST["content"] ?? '';

// 파일 업로드 처리
$files = $_FILES["upfile"] ?? [];
$count = isset($files["name"]) ? count($files["name"]) : 0;
$upload_dir = 'C:\temp\\';   // 물리적 저장위치

$upfile_name = [];
$upfile_tmp_name = [];
$upfile_type = [];
$upfile_size = [];
$upfile_error = [];
$copied_file_name = [];
$uploaded_file = [];

for ($i = 0; $i < $count; $i++) {
    $upfile_name[$i] = $files["name"][$i] ?? '';
    $upfile_tmp_name[$i] = $files["tmp_name"][$i] ?? '';
    $upfile_type[$i] = $files["type"][$i] ?? '';
    $upfile_size[$i] = $files["size"][$i] ?? 0;
    $upfile_error[$i] = $files["error"][$i] ?? 0;

    if ($upfile_name[$i]) {
        $file = explode(".", $upfile_name[$i]);
        $file_name = $file[0] ?? '';
        $file_ext = $file[1] ?? '';

        if (!$upfile_error[$i]) {
            $new_file_name = date("Y_m_d_H_i_s");
            $new_file_name = $new_file_name . "_" . $i;
            $copied_file_name[$i] = $new_file_name . "." . $file_ext;
            $uploaded_file[$i] = $upload_dir . $copied_file_name[$i];

            if ($upfile_size[$i] > 5000000) {
                echo '<script>
                    alert("업로드 파일 크기가 지정된 용량(5MB)을 초과합니다! 파일 크기를 체크해주세요!");
                    history.back();
                </script>';
                exit;
            }

            if (($upfile_type[$i] != "image/gif") && ($upfile_type[$i] != "image/jpeg")) {
                echo '<script>
                    alert("JPG와 GIF 이미지 파일만 업로드 가능합니다!");
                    history.back();
                </script>';
                exit;
            }

            if (!move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i])) {
                echo '<script>
                    alert("파일을 지정한 디렉토리에 복사하는데 실패했습니다.");
                    history.back();
                </script>';
                exit;
            }
        }
    }
}

// 데이터베이스 연결
$pdo = db_connect();

try {
    if ($mode == "modify") {
        // 수정 모드
        $num_checked = count($_REQUEST['del_file'] ?? []);
        $position = $_REQUEST['del_file'] ?? [];
        $del_ok = [];

        for ($i = 0; $i < $num_checked; $i++) {
            $index = $position[$i];
            $del_ok[$index] = "y";
        }

        try {
            $sql = "SELECT * FROM mirae8440.voc WHERE num = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $num, PDO::PARAM_STR);
            $stmh->execute();
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            echo "오류: " . $Exception->getMessage();
            exit;
        }

        for ($i = 0; $i < $count; $i++) {
            $field_org_name = "file_name_" . $i;
            $field_real_name = "file_copied_" . $i;
            $org_name_value = $upfile_name[$i];
            $org_real_value = $copied_file_name[$i];

            if (($del_ok[$i] ?? '') == "y") {
                $delete_field = "file_copied_" . $i;
                $delete_name = $row[$delete_field] ?? '';

                if ($delete_name) {
                    $delete_path = $upload_dir . $delete_name;
                    if (file_exists($delete_path)) {
                        unlink($delete_path);
                    }
                }

                try {
                    $pdo->beginTransaction();
                    $sql = "UPDATE mirae8440.voc SET $field_org_name = ?, $field_real_name = ? WHERE num = ?";
                    $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(1, $org_name_value, PDO::PARAM_STR);
                    $stmh->bindValue(2, $org_real_value, PDO::PARAM_STR);
                    $stmh->bindValue(3, $num, PDO::PARAM_STR);
                    $stmh->execute();
                    $pdo->commit();
                } catch (PDOException $Exception) {
                    $pdo->rollBack();
                    echo "오류: " . $Exception->getMessage();
                    exit;
                }
            } else {
                if (!$upfile_error[$i]) {
                    try {
                        $pdo->beginTransaction();
                        $sql = "UPDATE mirae8440.voc SET $field_org_name = ?, $field_real_name = ? WHERE num = ?";
                        $stmh = $pdo->prepare($sql);
                        $stmh->bindValue(1, $org_name_value, PDO::PARAM_STR);
                        $stmh->bindValue(2, $org_real_value, PDO::PARAM_STR);
                        $stmh->bindValue(3, $num, PDO::PARAM_STR);
                        $stmh->execute();
                        $pdo->commit();
                    } catch (PDOException $Exception) {
                        $pdo->rollBack();
                        echo "오류: " . $Exception->getMessage();
                        exit;
                    }
                }
            }
        }

        try {
            $pdo->beginTransaction();
            $sql = "UPDATE mirae8440.voc SET subject = ?, content = ?, is_html = ? WHERE num = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $subject, PDO::PARAM_STR);
            $stmh->bindValue(2, $content, PDO::PARAM_STR);
            $stmh->bindValue(3, $html_ok, PDO::PARAM_STR);
            $stmh->bindValue(4, $num, PDO::PARAM_STR);
            $stmh->execute();
            $pdo->commit();
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            echo "오류: " . $Exception->getMessage();
            exit;
        }

    } else {
        // 신규 등록 모드
        if ($html_ok == "y") {
            $is_html = "y";
        } else {
            $is_html = "";
            $content = htmlspecialchars($content);
        }

        try {
            $pdo->beginTransaction();
            $sql = "INSERT INTO mirae8440.voc(id, name, nick, subject, content, regist_day, hit, is_html,
                    file_name_0, file_name_1, file_name_2, file_copied_0, file_copied_1, file_copied_2)
                    VALUES(?, ?, ?, ?, ?, now(), 0, ?, ?, ?, ?, ?, ?, ?)";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $_SESSION["userid"] ?? '', PDO::PARAM_STR);
            $stmh->bindValue(2, $_SESSION["name"] ?? '', PDO::PARAM_STR);
            $stmh->bindValue(3, $_SESSION["nick"] ?? '', PDO::PARAM_STR);
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
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            echo "오류: " . $Exception->getMessage();
            exit;
        }
    }

} catch (Exception $e) {
    error_log("work_voc insert error: " . $e->getMessage());
    echo "처리 중 오류가 발생했습니다.";
    exit;
}

header("Location:" . getBaseUrl() . "/work_voc/list.php?page=$page");
?>