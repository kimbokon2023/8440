<?php
require_once __DIR__ . '/../bootstrap.php';

header("Content-Type: application/json; charset=utf-8");

// 로그인 체크
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => '로그인이 필요합니다.']);
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
$DB = $_SESSION['DB'] ?? 'mirae8440';

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

// 이미지 업로드 디렉토리 설정
$uploadDir = __DIR__ . '/../uploads/portfolio/';
$originalsDir = $uploadDir . 'originals/';
$thumbnailsDir = $uploadDir . 'thumbnails/';

// 디렉토리 생성
if (!file_exists($originalsDir)) {
    mkdir($originalsDir, 0777, true);
}
if (!file_exists($thumbnailsDir)) {
    mkdir($thumbnailsDir, 0777, true);
}

// 썸네일 생성 함수
function createThumbnail($sourcePath, $destPath, $maxWidth = 300, $maxHeight = 300) {
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }
    
    $width = $imageInfo[0];
    $height = $imageInfo[1];
    $mime = $imageInfo['mime'];
    
    // 비율 계산
    $ratio = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = (int)($width * $ratio);
    $newHeight = (int)($height * $ratio);
    
    // 이미지 생성
    switch ($mime) {
        case 'image/jpeg':
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $source = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $source = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }
    
    $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
    
    // PNG 투명도 유지
    if ($mime === 'image/png') {
        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);
    }
    
    imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    // 저장
    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($thumbnail, $destPath, 85);
            break;
        case 'image/png':
            imagepng($thumbnail, $destPath);
            break;
        case 'image/gif':
            imagegif($thumbnail, $destPath);
            break;
    }
    
    imagedestroy($source);
    imagedestroy($thumbnail);
    
    return true;
}

// GET: 조회
if ($method === 'GET') {
    try {
        if ($id) {
            // 단일 조회
            $sql = "SELECT * FROM {$DB}.portfolio WHERE id = ?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $id, PDO::PARAM_INT);
            $stmh->execute();
            $portfolio = $stmh->fetch(PDO::FETCH_ASSOC);
            
            if ($portfolio) {
                // images 필드가 있으면 JSON 디코딩
                if (isset($portfolio['images']) && !empty($portfolio['images'])) {
                    $decoded = json_decode($portfolio['images'], true);
                    $portfolio['images'] = $decoded !== null ? $decoded : [];
                } else {
                    $portfolio['images'] = [];
                }
                echo json_encode(['status' => 'success', 'data' => $portfolio], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['status' => 'error', 'message' => '시공사례를 찾을 수 없습니다.']);
            }
        } else {
            // 목록 조회
            $sql = "SELECT * FROM {$DB}.portfolio ORDER BY display_order ASC, created_at DESC";
            $stmh = $pdo->query($sql);
            $portfolios = $stmh->fetchAll(PDO::FETCH_ASSOC);
            
            // images 필드 JSON 디코딩 처리
            foreach ($portfolios as &$portfolio) {
                if (isset($portfolio['images']) && !empty($portfolio['images'])) {
                    $decoded = json_decode($portfolio['images'], true);
                    $portfolio['images'] = $decoded !== null ? $decoded : [];
                } else {
                    $portfolio['images'] = [];
                }
            }
            unset($portfolio); // 참조 해제
            
            echo json_encode(['status' => 'success', 'data' => $portfolios], JSON_UNESCAPED_UNICODE);
        }
    } catch (PDOException $e) {
        error_log("시공사례 조회 오류: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => '데이터 조회 중 오류가 발생했습니다.']);
    }
    exit;
}

// POST: 등록 (id가 없으면) 또는 수정 (id가 있으면)
if ($method === 'POST' && (!isset($_POST['id']) || empty($_POST['id']))) {
    try {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $category = $_POST['category'] ?? 'etc';
        $location = $_POST['location'] ?? '';
        $project_date = $_POST['project_date'] ?? null;
        $display_order = (int)($_POST['display_order'] ?? 0);
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        $created_by = $_SESSION['manager_userid'] ?? '';
        
        if (empty($title)) {
            echo json_encode(['status' => 'error', 'message' => '제목을 입력해주세요.']);
            exit;
        }
        
        // 이미지 업로드 처리 - 여러 이미지 지원
        $main_image = '';
        $thumbnail = '';
        $uploadedImages = [];
        
        // 여러 이미지 파일 처리 (images[] 배열)
        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            $fileCount = count($_FILES['images']['name']);
            $maxSize = 10 * 1024 * 1024; // 10MB
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'size' => $_FILES['images']['size'][$i]
                    ];
                    
                    // 파일 크기 검증
                    if ($file['size'] > $maxSize) {
                        echo json_encode(['status' => 'error', 'message' => $file['name'] . ' 파일 크기는 10MB를 초과할 수 없습니다.']);
                        exit;
                    }
                    
                    // 파일 타입 검증
                    $fileType = mime_content_type($file['tmp_name']);
                    if (!in_array($fileType, $allowedTypes)) {
                        echo json_encode(['status' => 'error', 'message' => $file['name'] . ' JPG, PNG, GIF 파일만 업로드 가능합니다.']);
                        exit;
                    }
                    
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $fileName = 'portfolio_' . time() . '_' . uniqid() . '_' . $i . '.' . $extension;
                    $filePath = $originalsDir . $fileName;
                    
                    if (move_uploaded_file($file['tmp_name'], $filePath)) {
                        $imagePath = 'uploads/portfolio/originals/' . $fileName;
                        
                        // 썸네일 생성
                        $thumbnailName = 'thumb_' . $fileName;
                        $thumbnailPath = $thumbnailsDir . $thumbnailName;
                        $thumbnailPathResult = '';
                        
                        if (createThumbnail($filePath, $thumbnailPath)) {
                            $thumbnailPathResult = 'uploads/portfolio/thumbnails/' . $thumbnailName;
                        }
                        
                        // 첫 번째 이미지는 메인 이미지
                        if ($i === 0) {
                            $main_image = $imagePath;
                            $thumbnail = $thumbnailPathResult;
                        } else {
                            // 추가 이미지는 배열에 저장
                            $uploadedImages[] = [
                                'original' => $imagePath,
                                'thumbnail' => $thumbnailPathResult ?: $imagePath
                            ];
                        }
                    }
                }
            }
        }
        
        if (empty($main_image)) {
            echo json_encode(['status' => 'error', 'message' => '최소 1장 이상의 이미지를 업로드해주세요.']);
            exit;
        }
        
        // images 필드 처리 (배열을 JSON 문자열로 인코딩)
        $imagesJson = !empty($uploadedImages) ? json_encode($uploadedImages, JSON_UNESCAPED_UNICODE) : null;
        
        $sql = "INSERT INTO {$DB}.portfolio (title, content, category, location, project_date, main_image, thumbnail, images, display_order, is_published, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $title, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $category, PDO::PARAM_STR);
        $stmh->bindValue(4, $location, PDO::PARAM_STR);
        $stmh->bindValue(5, $project_date ?: null, PDO::PARAM_STR);
        $stmh->bindValue(6, $main_image, PDO::PARAM_STR);
        $stmh->bindValue(7, $thumbnail, PDO::PARAM_STR);
        $stmh->bindValue(8, $imagesJson, PDO::PARAM_STR);
        $stmh->bindValue(9, $display_order, PDO::PARAM_INT);
        $stmh->bindValue(10, $is_published, PDO::PARAM_INT);
        $stmh->bindValue(11, $created_by, PDO::PARAM_STR);
        $stmh->execute();
        
        echo json_encode(['status' => 'success', 'message' => '시공사례가 성공적으로 등록되었습니다.']);
    } catch (PDOException $e) {
        error_log("시공사례 등록 오류: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => '등록 중 오류가 발생했습니다.']);
    }
    exit;
}

// PUT: 수정 (POST로도 처리 가능하도록)
if ($method === 'PUT' || ($method === 'POST' && isset($_POST['id']) && !empty($_POST['id']))) {
    try {
        // POST로 PUT 요청이 오는 경우 (FormData 사용)
        if ($method === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $putData = $_POST;
        } else {
            parse_str(file_get_contents('php://input'), $putData);
            $id = $putData['id'] ?? null;
        }
        
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID가 필요합니다.']);
            exit;
        }
        
        // 기존 데이터 조회
        $sql = "SELECT * FROM {$DB}.portfolio WHERE id = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_INT);
        $stmh->execute();
        $existing = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$existing) {
            echo json_encode(['status' => 'error', 'message' => '시공사례를 찾을 수 없습니다.']);
            exit;
        }
        
        // POST로 온 경우 $_POST 사용, PUT로 온 경우 $putData 사용
        if ($method === 'POST') {
            $title = $_POST['title'] ?? $existing['title'];
            $content = $_POST['content'] ?? $existing['content'];
            $category = $_POST['category'] ?? $existing['category'];
            $location = $_POST['location'] ?? $existing['location'];
            $project_date = $_POST['project_date'] ?? $existing['project_date'];
            $display_order = (int)($_POST['display_order'] ?? $existing['display_order']);
            $is_published = isset($_POST['is_published']) ? 1 : $existing['is_published'];
        } else {
            $title = $putData['title'] ?? $existing['title'];
            $content = $putData['content'] ?? $existing['content'];
            $category = $putData['category'] ?? $existing['category'];
            $location = $putData['location'] ?? $existing['location'];
            $project_date = $putData['project_date'] ?? $existing['project_date'];
            $display_order = (int)($putData['display_order'] ?? $existing['display_order']);
            $is_published = isset($putData['is_published']) ? 1 : $existing['is_published'];
        }
        
        // 기존 이미지 처리
        $main_image = $existing['main_image'];
        $thumbnail = $existing['thumbnail'];
        $images = [];
        
        // 기존 images 디코딩
        if (isset($existing['images']) && !empty($existing['images'])) {
            $decoded = json_decode($existing['images'], true);
            $images = $decoded !== null ? $decoded : [];
        }
        
        // 기존 이미지 경로 처리 (existing_images[] 배열)
        if ($method === 'POST' && isset($_POST['existing_images']) && is_array($_POST['existing_images'])) {
            $newImages = [];
            foreach ($_POST['existing_images'] as $imgJson) {
                $imgData = json_decode($imgJson, true);
                if ($imgData && isset($imgData['original'])) {
                    $newImages[] = $imgData;
                }
            }
            // 첫 번째가 메인 이미지
            if (!empty($newImages)) {
                $main_image = $newImages[0]['original'];
                $thumbnail = $newImages[0]['thumbnail'] ?? $newImages[0]['original'];
                // 나머지는 추가 이미지
                if (count($newImages) > 1) {
                    $images = array_slice($newImages, 1);
                } else {
                    $images = [];
                }
            }
        }
        
        // 새 이미지 업로드 처리 (images[] 배열)
        $uploadedNewImages = [];
        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            $fileCount = count($_FILES['images']['name']);
            $maxSize = 10 * 1024 * 1024; // 10MB
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'size' => $_FILES['images']['size'][$i]
                    ];
                    
                    // 파일 크기 검증
                    if ($file['size'] > $maxSize) {
                        echo json_encode(['status' => 'error', 'message' => $file['name'] . ' 파일 크기는 10MB를 초과할 수 없습니다.']);
                        exit;
                    }
                    
                    // 파일 타입 검증
                    $fileType = mime_content_type($file['tmp_name']);
                    if (!in_array($fileType, $allowedTypes)) {
                        echo json_encode(['status' => 'error', 'message' => $file['name'] . ' JPG, PNG, GIF 파일만 업로드 가능합니다.']);
                        exit;
                    }
                    
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $fileName = 'portfolio_' . time() . '_' . uniqid() . '_' . $i . '.' . $extension;
                    $filePath = $originalsDir . $fileName;
                    
                    if (move_uploaded_file($file['tmp_name'], $filePath)) {
                        $imagePath = 'uploads/portfolio/originals/' . $fileName;
                        
                        // 썸네일 생성
                        $thumbnailName = 'thumb_' . $fileName;
                        $thumbnailPath = $thumbnailsDir . $thumbnailName;
                        $thumbnailPathResult = '';
                        
                        if (createThumbnail($filePath, $thumbnailPath)) {
                            $thumbnailPathResult = 'uploads/portfolio/thumbnails/' . $thumbnailName;
                        }
                        
                        $uploadedNewImages[] = [
                            'original' => $imagePath,
                            'thumbnail' => $thumbnailPathResult ?: $imagePath
                        ];
                    }
                }
            }
        }
        
        // 새로 업로드된 이미지가 있으면 첫 번째를 메인으로, 나머지는 추가 이미지로
        if (!empty($uploadedNewImages)) {
            // 기존 메인 이미지가 없거나 새 이미지로 교체하는 경우
            if (empty($main_image) || !isset($_POST['existing_images'])) {
                $main_image = $uploadedNewImages[0]['original'];
                $thumbnail = $uploadedNewImages[0]['thumbnail'] ?? $uploadedNewImages[0]['original'];
                if (count($uploadedNewImages) > 1) {
                    $images = array_merge($images, array_slice($uploadedNewImages, 1));
                }
            } else {
                // 기존 이미지가 있고 새 이미지를 추가하는 경우
                $images = array_merge($images, $uploadedNewImages);
            }
        }
        
        // images 필드 처리 (배열을 JSON 문자열로 인코딩)
        $imagesJson = !empty($images) ? json_encode($images, JSON_UNESCAPED_UNICODE) : null;
        
        $sql = "UPDATE {$DB}.portfolio SET title = ?, content = ?, category = ?, location = ?, project_date = ?, 
                main_image = ?, thumbnail = ?, images = ?, display_order = ?, is_published = ? WHERE id = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $title, PDO::PARAM_STR);
        $stmh->bindValue(2, $content, PDO::PARAM_STR);
        $stmh->bindValue(3, $category, PDO::PARAM_STR);
        $stmh->bindValue(4, $location, PDO::PARAM_STR);
        $stmh->bindValue(5, $project_date ?: null, PDO::PARAM_STR);
        $stmh->bindValue(6, $main_image, PDO::PARAM_STR);
        $stmh->bindValue(7, $thumbnail, PDO::PARAM_STR);
        $stmh->bindValue(8, $imagesJson, PDO::PARAM_STR);
        $stmh->bindValue(9, $display_order, PDO::PARAM_INT);
        $stmh->bindValue(10, $is_published, PDO::PARAM_INT);
        $stmh->bindValue(11, $id, PDO::PARAM_INT);
        $stmh->execute();
        
        echo json_encode(['status' => 'success', 'message' => '시공사례가 성공적으로 수정되었습니다.']);
    } catch (PDOException $e) {
        error_log("시공사례 수정 오류: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => '수정 중 오류가 발생했습니다.']);
    }
    exit;
}

// DELETE: 삭제
if ($method === 'DELETE') {
    try {
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID가 필요합니다.']);
            exit;
        }
        
        // 기존 데이터 조회
        $sql = "SELECT * FROM {$DB}.portfolio WHERE id = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_INT);
        $stmh->execute();
        $existing = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if (!$existing) {
            echo json_encode(['status' => 'error', 'message' => '시공사례를 찾을 수 없습니다.']);
            exit;
        }
        
        // 파일 삭제
        if (!empty($existing['main_image']) && file_exists(__DIR__ . '/../' . $existing['main_image'])) {
            unlink(__DIR__ . '/../' . $existing['main_image']);
        }
        if (!empty($existing['thumbnail']) && file_exists(__DIR__ . '/../' . $existing['thumbnail'])) {
            unlink(__DIR__ . '/../' . $existing['thumbnail']);
        }
        
        // DB 삭제
        $sql = "DELETE FROM {$DB}.portfolio WHERE id = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $id, PDO::PARAM_INT);
        $stmh->execute();
        
        echo json_encode(['status' => 'success', 'message' => '시공사례가 성공적으로 삭제되었습니다.']);
    } catch (PDOException $e) {
        error_log("시공사례 삭제 오류: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => '삭제 중 오류가 발생했습니다.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => '지원하지 않는 메서드입니다.']);
?>

