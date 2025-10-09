<?php
header("Content-Type: application/json"); // JSON 응답 설정

// 1. vendor autoload를 가장 먼저 (namespace 때문에)
require_once __DIR__ . '/../vendor/autoload.php';

// Google API 클래스들이 로드됨 (IDE 인식용 주석)
// @see Google_Client
// @see Google_Service_Drive

// 2. 공통 함수 로드
require_once __DIR__ . '/../common/functions.php';

// 3. 데이터베이스 연결
require_once __DIR__ . '/../lib/mydb.php';

$num = $_REQUEST["num"] ?? '';
$tablename = $_REQUEST["tablename"] ?? '';
$item = $_REQUEST["item"] ?? '';

// Google Drive 서비스 계정 인증 설정
$serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';

try {
    $client = new Google_Client();
    $client->setAuthConfig($serviceAccountKeyFile);
    $client->addScope(Google_Service_Drive::DRIVE);
    
    $service = new Google_Service_Drive($client);
} catch (Exception $e) {
    echo json_encode(['error' => 'Google Drive 초기화 실패: ' . $e->getMessage()]);
    exit;
}

// SQL 쿼리 실행 및 데이터 처리
$pdo = db_connect();
$sql = "SELECT * FROM mirae8440.picuploads WHERE tablename = ? AND item = ? AND parentnum = ?";

$img_arr = [];

try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute([$tablename, $item, $num]);

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $picname = $row["picname"];
        $fileData = [
            'id' => $picname,
            'thumbnail' => null,
            'link' => null,
            'name' => null
        ];

        try {
            // Google Drive 파일 정보 가져오기
            $file = $service->files->get($picname, ['fields' => 'id, name, thumbnailLink, webContentLink']);
            $fileData['name'] = $file->name;
            $fileData['thumbnail'] = $file->thumbnailLink;
            $fileData['link'] = $file->webContentLink;
        } catch (Exception $e) {
            error_log("Google Drive 파일 정보 가져오기 실패: " . $e->getMessage());
            $fileData['thumbnail'] = "/uploads/" . $picname; // 썸네일이 없으면 로컬 경로 사용
        }

        $img_arr[] = $fileData;
    }
} catch (PDOException $Exception) {
    echo json_encode(['error' => $Exception->getMessage()]);
    exit;
}

// 반환 데이터 생성
$data = [
    "recnum" => count($img_arr),
    "img_arr" => $img_arr,
];

// JSON 응답 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
