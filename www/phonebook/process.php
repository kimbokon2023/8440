<?php
require_once __DIR__ . '/../common/functions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$is_local = ($httpHost === 'localhost' || strpos($httpHost, '127.0.0.1') !== false);
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

$DB = $_SESSION['DB'] ?? '';
$level = $_SESSION['level'] ?? '';
$user_name = $_SESSION['name'] ?? '';
$user_id = $_SESSION['userid'] ?? '';
$website = $_SESSION['WebSite'] ?? '';

if ($level === '' || (is_numeric($level) && (int)$level > 5)) {
    $loginPath = $website !== '' ? rtrim($website, '/') . '/login/login_form.php' : '/login/login_form.php';
    header('Location:' . $loginPath);
    exit;
}

require_once includePath('lib/mydb.php');
$pdo = db_connect();

require_once __DIR__ . '/_request.php';

$tablename = $tablename !== '' ? $tablename : 'phonebook';
$tableIdentifier = $DB !== '' ? $DB . '.' . $tablename : $tablename;
$SelectWork = $SelectWork !== '' ? $SelectWork : '';
$num = $num === '' ? '' : (int)$num;
$phone_name = trim($phone_name);
$phonenumber = trim($phonenumber);
$belongstr = trim($belongstr);

try {
    switch ($SelectWork) {
        case 'update':
            if ($num === '') {
                throw new InvalidArgumentException('수정할 항목의 번호가 없습니다.');
            }

            $pdo->beginTransaction();
            $sql = "UPDATE {$tableIdentifier} SET phone_name = :phone_name, phonenumber = :phonenumber, belongstr = :belongstr WHERE num = :num LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':phone_name', $phone_name, PDO::PARAM_STR);
            $stmt->bindValue(':phonenumber', $phonenumber, PDO::PARAM_STR);
            $stmt->bindValue(':belongstr', $belongstr, PDO::PARAM_STR);
            $stmt->bindValue(':num', $num, PDO::PARAM_INT);
            $stmt->execute();
            $pdo->commit();
            echo 'success';
            break;

        case 'insert':
            $pdo->beginTransaction();
            $sql = "INSERT INTO {$tableIdentifier} (phone_name, phonenumber, belongstr) VALUES (:phone_name, :phonenumber, :belongstr)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':phone_name', $phone_name, PDO::PARAM_STR);
            $stmt->bindValue(':phonenumber', $phonenumber, PDO::PARAM_STR);
            $stmt->bindValue(':belongstr', $belongstr, PDO::PARAM_STR);
            $stmt->execute();
            $pdo->commit();
            echo 'success';
            break;

        case 'delete':
            if ($num === '') {
                throw new InvalidArgumentException('삭제할 항목의 번호가 없습니다.');
            }

            $pdo->beginTransaction();
            $sql = "DELETE FROM {$tableIdentifier} WHERE num = :num";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':num', $num, PDO::PARAM_INT);
            $stmt->execute();
            $pdo->commit();
            echo 'success';
            break;

        default:
            echo 'no-action';
            break;
    }
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo $exception->getMessage();
}
