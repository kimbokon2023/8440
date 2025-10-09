<?php
// VOC 등록/수정 처리 - 로컬/서버 환경 호환
require_once __DIR__ . '/../bootstrap.php';

// 입력값 검증 및 초기화
$num = $_REQUEST["num"] ?? '';
$workername = $_REQUEST["workername"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$mode = $_REQUEST["mode"] ?? '';
$childnum = $_REQUEST["childnum"] ?? '';
$parent = $_REQUEST["parent"] ?? '';
$html_ok = $_REQUEST["html_ok"] ?? '';
$subject = $_REQUEST["workplacename"] ?? '';
$content = $_REQUEST["content"] ?? '';

// 입력값 유효성 검사
if (empty($num) || !is_numeric($num)) {
    die("유효하지 않은 번호입니다.");
}

if (empty($content)) {
    die("협의사항 내용을 입력해주세요.");
}

// Database connection is already available from bootstrap.php
if (!isset($pdo) || !$pdo) {
    try {
        $pdo = db_connect();
    } catch (Exception $e) {
        die("데이터베이스 연결에 실패했습니다.");
    }
}

// 1. work 테이블에서 update_log 가져오기
try {
    $sql = "select * from mirae8440.work where num=?";
    $stmh = $pdo->prepare($sql);  
    $stmh->bindValue(1, $num, PDO::PARAM_INT);      
    $stmh->execute();            
     
    $row = $stmh->fetch(PDO::FETCH_ASSOC); 	 
    $update_log = $row["update_log"] ?? '';
    
} catch (PDOException $Exception) {
    if (isLocal()) {
        print "오류: ".$Exception->getMessage();
    } else {
        error_log("Database error in voc_insert.php (select work): " . $Exception->getMessage());
        print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
    }
    exit;
}

// 2. update_log 업데이트
$data = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . "  ";	
$update_log = $data . $update_log . "&#10";  // 개행문자 Textarea      

try {
    $pdo->beginTransaction();   
    $sql = "update mirae8440.work set update_log=? where num=? LIMIT 1";            
   
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $update_log, PDO::PARAM_STR);         
    $stmh->bindValue(2, $num, PDO::PARAM_INT);
 
    $stmh->execute();
    $pdo->commit(); 
    
} catch (PDOException $Exception) {
    $pdo->rollBack();
    if (isLocal()) {
        print "오류: ".$Exception->getMessage();
    } else {
        error_log("Database error in voc_insert.php (update work): " . $Exception->getMessage());
        print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
    }
    exit;
}

// 3. alert 테이블 업데이트
$voc_alert = 1;  // 알람설정
$a = 1;
	
try {
    $pdo->beginTransaction();   
    $sql = "update mirae8440.alert set voc_alert=? where num=? LIMIT 1";  
   
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $voc_alert, PDO::PARAM_INT);    	 
    $stmh->bindValue(2, $a, PDO::PARAM_INT);      	 
 
    $stmh->execute();
    $pdo->commit(); 
    
} catch (PDOException $Exception) {
    $pdo->rollBack();
    if (isLocal()) {
        print "오류: ".$Exception->getMessage();
    } else {
        error_log("Database error in voc_insert.php (update alert): " . $Exception->getMessage());
        print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
    }
    exit;
}

// 4. VOC 테이블 처리 (수정 또는 등록)
$is_html = "1";

if ($mode == "modify") {
    // 수정 모드
    try {
        $sql = "select * from mirae8440.voc where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $childnum, PDO::PARAM_INT); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $Exception) {
        if (isLocal()) {
            print "오류: ".$Exception->getMessage();
        } else {
            error_log("Database error in voc_insert.php (select voc): " . $Exception->getMessage());
            print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
        }
        exit;
    } 
       
    try {
        $pdo->beginTransaction();   
        $sql = "update mirae8440.voc set subject=?, content=?, is_html=? where num=?";
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $subject, PDO::PARAM_STR);  
        $stmh->bindValue(2, $content, PDO::PARAM_STR);  
        $stmh->bindValue(3, $is_html, PDO::PARAM_STR);     
        $stmh->bindValue(4, $childnum, PDO::PARAM_INT);   
        $stmh->execute();
        $pdo->commit(); 
        
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        if (isLocal()) {
            print "오류: ".$Exception->getMessage();
        } else {
            error_log("Database error in voc_insert.php (update voc): " . $Exception->getMessage());
            print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
        }
        exit;
    }                         
       
} else {
    // 등록 모드
    $upfile_name = array('', '', '');
    $copied_file_name = array('', '', '');
    
    try {
        $pdo->beginTransaction();
        $sql = "insert into mirae8440.voc(id, name, nick, subject, content, regist_day, hit, is_html, ";
        $sql .= " file_name_0, file_name_1, file_name_2, file_copied_0, file_copied_1, file_copied_2, parent) ";
        $sql .= "values(?, ?, ?, ?, ?, now(), 0, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, $_SESSION["userid"] ?? '', PDO::PARAM_STR);  
        $stmh->bindValue(2, $_SESSION["name"] ?? '', PDO::PARAM_STR);  
        $stmh->bindValue(3, $_SESSION["nick"] ?? '', PDO::PARAM_STR);   
        $stmh->bindValue(4, $subject, PDO::PARAM_STR);  
        $stmh->bindValue(5, $content, PDO::PARAM_STR);  
        $stmh->bindValue(6, $is_html, PDO::PARAM_STR);    
        $stmh->bindValue(7, $upfile_name[0], PDO::PARAM_STR); 
        $stmh->bindValue(8, $upfile_name[1], PDO::PARAM_STR);  
        $stmh->bindValue(9, $upfile_name[2], PDO::PARAM_STR);   
        $stmh->bindValue(10, $copied_file_name[0], PDO::PARAM_STR);  
        $stmh->bindValue(11, $copied_file_name[1], PDO::PARAM_STR);  
        $stmh->bindValue(12, $copied_file_name[2], PDO::PARAM_STR);        
        $stmh->bindValue(13, $parent, PDO::PARAM_INT);        
        $stmh->execute();
        $pdo->commit(); 
        
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        if (isLocal()) {
            print "오류: ".$Exception->getMessage();
        } else {
            error_log("Database error in voc_insert.php (insert voc): " . $Exception->getMessage());
            print "데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.";
        }
        exit;
    }   
}

// 5. 리다이렉션 (환경별 URL 처리)
$redirectUrl = getBaseUrl() . "/p/view.php?num=" . urlencode($parent) . "&workername=" . urlencode($workername);
header("Location: " . $redirectUrl);
exit;
?>
