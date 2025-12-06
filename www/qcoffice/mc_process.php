<?php
/**
 * QC 사무실 정비 항목 정보 처리 (Insert/Update/Delete)
 */

require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('lib/mydb.php'));

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 8) {
    echo "<script>alert('권한이 없습니다.'); history.back();</script>";
    exit;
}

$DB = $_SESSION["DB"] ?? 'mirae8440';
$tablename = 'myarea';

$pdo = db_connect();

$mode = $_REQUEST["mode"] ?? "";
$num = $_REQUEST["num"] ?? "";

$mcno = $_POST["mcno"] ?? "";
$mcname = $_POST["mcname"] ?? "";
$mcspec = $_POST["mcspec"] ?? "";
$mcmaker = $_POST["mcmaker"] ?? "";
$mcmain = $_POST["mcmain"] ?? "";
$mcsub = $_POST["mcsub"] ?? "";
$qrcode = $_POST["qrcode"] ?? "";

try {
    if ($mode == "insert") {
        $sql = "INSERT INTO {$DB}.{$tablename} (mcno, mcname, mcspec, mcmaker, mcmain, mcsub, qrcode) 
                VALUES (:mcno, :mcname, :mcspec, :mcmaker, :mcmain, :mcsub, :qrcode)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':mcno', $mcno);
        $stmt->bindParam(':mcname', $mcname);
        $stmt->bindParam(':mcspec', $mcspec);
        $stmt->bindParam(':mcmaker', $mcmaker);
        $stmt->bindParam(':mcmain', $mcmain);
        $stmt->bindParam(':mcsub', $mcsub);
        $stmt->bindParam(':qrcode', $qrcode);
        
        $stmt->execute();
        
        echo "<script>
            alert('항목이 성공적으로 등록되었습니다.');
            location.href = 'mc_list.php';
        </script>";
        
    } else if ($mode == "update") {
        $sql = "UPDATE {$DB}.{$tablename} SET 
                mcno = :mcno, 
                mcname = :mcname, 
                mcspec = :mcspec, 
                mcmaker = :mcmaker, 
                mcmain = :mcmain, 
                mcsub = :mcsub, 
                qrcode = :qrcode 
                WHERE num = :num";
                
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':mcno', $mcno);
        $stmt->bindParam(':mcname', $mcname);
        $stmt->bindParam(':mcspec', $mcspec);
        $stmt->bindParam(':mcmaker', $mcmaker);
        $stmt->bindParam(':mcmain', $mcmain);
        $stmt->bindParam(':mcsub', $mcsub);
        $stmt->bindParam(':qrcode', $qrcode);
        $stmt->bindParam(':num', $num);
        
        $stmt->execute();
        
        echo "<script>
            alert('항목 정보가 수정되었습니다.');
            location.href = 'mc_list.php';
        </script>";
        
    } else if ($mode == "delete") {
        $sql = "DELETE FROM {$DB}.{$tablename} WHERE num = :num";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':num', $num);
        $stmt->execute();
        
        echo "<script>
            alert('항목이 삭제되었습니다.');
            location.href = 'mc_list.php';
        </script>";
    }
    
} catch (PDOException $e) {
    echo "<script>
        alert('DB 오류가 발생했습니다: " . addslashes($e->getMessage()) . "');
        history.back();
    </script>";
}
?>
