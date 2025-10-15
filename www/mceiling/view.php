<?php
/**
 * 천장/LC 조회 수정 페이지
 * 로컬 및 서버 환경 모두 지원
 * Google Drive 연동
 */

// 서버 환경 디버깅용 에러 표시
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 이렇게 하면 항상 서버에서 새로 불러옵니다.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// 에러 핸들러 등록
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [{$errno}]: {$errstr} in {$errfile} on line {$errline}");
    echo "<div style='background: #ffebee; color: #c62828; padding: 10px; margin: 10px; border: 1px solid #ef5350; border-radius: 4px;'>";
    echo "<strong>Error [{$errno}]:</strong> {$errstr}<br>";
    echo "<strong>File:</strong> {$errfile}<br>";
    echo "<strong>Line:</strong> {$errline}";
    echo "</div>";
    return false; // PHP 기본 핸들러도 실행
});

try {
    require_once __DIR__ . '/../bootstrap.php';
} catch (Exception $e) {
    die("bootstrap.php 로드 실패: " . $e->getMessage());
}

try {
    require_once getDocumentRoot() . '/session.php';
} catch (Exception $e) {
    die("session.php 로드 실패: " . $e->getMessage());
}

// trans_date() 함수 정의 (함수 중복 방지)
if (!function_exists('trans_date')) {
    function trans_date($tdate) {
        if (empty($tdate)) return '';
        if (substr($tdate, 0, 2) == "20") {
            return mb_substr($tdate, 0, 10, "utf-8");
        }
        return $tdate;
    }
}

try {
    require_once("../lib/mydb.php");
} catch (Exception $e) {
    die("mydb.php 로드 실패: " . $e->getMessage());
}

// 세션 변수 초기화 (?? '' 형태)
$DB = $_SESSION["DB"] ?? 'mirae8440';
$chkMobile = $_SESSION["chkMobile"] ?? false;
$level = $_SESSION["level"] ?? 999;

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    header("Location: {$protocol}://{$host}/login/login_form.php");
    exit;
}

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';

// 데이터베이스 연결
try {
    $pdo = db_connect();
} catch (Exception $e) {
    error_log("DB 연결 실패: " . $e->getMessage());
    die("<div class='alert alert-danger'>데이터베이스 연결 실패: " . htmlspecialchars($e->getMessage()) . "</div>");
}

// 사진 데이터 초기화
$picNum = 0;
$picData = array();

// Google API 관련 변수 초기화
$service = null;
$googleDriveEnabled = false; 

// Google API 클라이언트 로드 시도
try {
    $vendorAutoload = getDocumentRoot() . '/vendor/autoload.php';
    $serviceAccountKeyFile = getDocumentRoot() . '/tokens/mytoken.json';
    
    if (file_exists($vendorAutoload) && file_exists($serviceAccountKeyFile)) {
        require_once $vendorAutoload;
        
        if (class_exists('Google_Client') || class_exists('\Google\Client')) {
            // Google Drive 클라이언트 설정
            $client = new Google_Client();
            $client->setAuthConfig($serviceAccountKeyFile);
            $client->addScope(Google_Service_Drive::DRIVE);
            
            // Google Drive 서비스 초기화
            $service = new Google_Service_Drive($client);
            $googleDriveEnabled = true;
            
            error_log("Google Drive 연동 성공");
        } else {
            error_log("Google_Client 클래스를 찾을 수 없습니다.");
        }
    } else {
        if (!file_exists($vendorAutoload)) {
            error_log("Composer autoload 파일 없음: {$vendorAutoload}");
        }
        if (!file_exists($serviceAccountKeyFile)) {
            error_log("Google 서비스 계정 키 파일 없음: {$serviceAccountKeyFile}");
        }
    }
} catch (Exception $e) {
    error_log("Google Drive 초기화 실패: " . $e->getMessage());
    $googleDriveEnabled = false;
}

// 특정 폴더 확인 함수
function getFolderId($service, $folderName, $parentFolderId = null) {
    if (!$service) return null;
    
    try {
        $query = "name='" . addslashes($folderName) . "' and mimeType='application/vnd.google-apps.folder' and trashed=false";
        if ($parentFolderId) {
            $query .= " and '" . addslashes($parentFolderId) . "' in parents";
        }
        
        $response = $service->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id, name)'
        ]);
        
        return count($response->files) > 0 ? $response->files[0]->id : null;
    } catch (Exception $e) {
        error_log("폴더 ID 조회 실패: " . $e->getMessage());
        return null;
    }
}

// 폴더 ID 가져오기 (Google Drive가 활성화된 경우만)
$miraeFolderId = null;
$uploadsFolderId = null;

if ($googleDriveEnabled && $service) {
    $miraeFolderId = getFolderId($service, '미래기업');
    $uploadsFolderId = getFolderId($service, 'imgwork', $miraeFolderId);
}

// 데이터베이스에서 파일 정보 가져오기
$tablename = 'ceilingwrap';
$item = 'ceilingwrap';

$sql = "SELECT * FROM {$DB}.picuploads WHERE item = ? AND parentnum = ?";
try {
    $stmh = $pdo->prepare($sql);
    $stmh->execute([$item, $num]);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $picname = $row["picname"] ?? '';
        
        if ($googleDriveEnabled && $service && preg_match('/^[a-zA-Z0-9_-]{25,}$/', $picname)) {
            // Google Drive 파일 ID로 처리
            $fileId = $picname;
            
            try {
                $file = $service->files->get($fileId, ['fields' => 'webViewLink, thumbnailLink']);
                $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";
                $webViewLink = $file->webViewLink;
                $picData[] = ['thumbnail' => $thumbnailUrl, 'link' => $webViewLink, 'fileId' => $fileId];
            } catch (Exception $e) {
                error_log("Google Drive 파일 정보 가져오기 실패: " . $e->getMessage());
                $picData[] = ['thumbnail' => "https://drive.google.com/uc?id=$fileId", 'link' => null, 'fileId' => $fileId];
            }
        } elseif ($googleDriveEnabled && $service) {
            // Google Drive에서 파일 이름으로 검색
            try {
                $query = sprintf("name='%s' and trashed=false", addslashes($picname));
                $response = $service->files->listFiles([
                    'q' => $query,
                    'fields' => 'files(id, webViewLink, thumbnailLink)',
                    'pageSize' => 1
                ]);
                
                if (count($response->files) > 0) {
                    $file = $response->files[0];
                    $fileId = $file->id;
                    $thumbnailUrl = $file->thumbnailLink ?? "https://drive.google.com/uc?id=$fileId";
                    $webViewLink = $file->webViewLink;
                    $picData[] = ['thumbnail' => $thumbnailUrl, 'link' => $webViewLink, 'fileId' => $fileId];
                    
                    // 데이터베이스 업데이트
                    $updateSql = "UPDATE {$DB}.picuploads SET picname = ? WHERE item = ? AND parentnum = ? AND picname = ?";
                    $updateStmh = $pdo->prepare($updateSql);
                    $updateStmh->execute([$fileId, $item, $num, $picname]);
                } else {
                    error_log("Google Drive에서 파일을 찾을 수 없습니다: " . $picname);
                }
            } catch (Exception $e) {
                error_log("Google Drive 파일 검색 실패: " . $e->getMessage());
            }
        } else {
            // Google Drive 미사용 시 로컬 경로 사용
            error_log("Google Drive 비활성화 상태 - 파일: " . $picname);
        }
    }
} catch (PDOException $ex) {
    error_log("사진 정보 조회 오류: " . $ex->getMessage());
}

$picNum = count($picData);

// 천장 데이터 조회
$row = null;
try {
    $sql = "SELECT * FROM {$DB}.ceiling WHERE num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $rowFile = getDocumentRoot() . '/ceiling/_row.php';
        if (file_exists($rowFile)) {
            include $rowFile;
        } else {
            error_log("_row.php 파일을 찾을 수 없습니다: " . $rowFile);
            // _row.php가 없는 경우 수동으로 변수 할당
            foreach ($row as $key => $value) {
                $$key = $value;
            }
        }
        
        // 날짜 변환
        $workday = trans_date($workday ?? '');
        $demand = trans_date($demand ?? '');
        $orderday = trans_date($orderday ?? '');
        $deadline = trans_date($deadline ?? '');
        $testday = trans_date($testday ?? '');
        $lc_draw = trans_date($lc_draw ?? '');
        $lclaser_date = trans_date($lclaser_date ?? '');
        $lcbending_date = trans_date($lcbending_date ?? '');
        $lcwelding_date = trans_date($lcwelding_date ?? '');
        $lcpainting_date = trans_date($lcpainting_date ?? '');
        $lcassembly_date = trans_date($lcassembly_date ?? '');
        $main_draw = trans_date($main_draw ?? '');
        $eunsung_make_date = trans_date($eunsung_make_date ?? '');
        $eunsung_laser_date = trans_date($eunsung_laser_date ?? '');
        $mainbending_date = trans_date($mainbending_date ?? '');
        $mainwelding_date = trans_date($mainwelding_date ?? '');
        $mainpainting_date = trans_date($mainpainting_date ?? '');
        $mainassembly_date = trans_date($mainassembly_date ?? '');
        $etclaser_date = trans_date($etclaser_date ?? '');
        $etcbending_date = trans_date($etcbending_date ?? '');
        $etcwelding_date = trans_date($etcwelding_date ?? '');
        $etcpainting_date = trans_date($etcpainting_date ?? '');
        $etcassembly_date = trans_date($etcassembly_date ?? '');
        
        $order_date1 = trans_date($order_date1 ?? '');
        $order_date2 = trans_date($order_date2 ?? '');
        $order_date3 = trans_date($order_date3 ?? '');
        $order_date4 = trans_date($order_date4 ?? '');
        $order_input_date1 = trans_date($order_input_date1 ?? '');
        $order_input_date2 = trans_date($order_input_date2 ?? '');
        $order_input_date3 = trans_date($order_input_date3 ?? '');
        $order_input_date4 = trans_date($order_input_date4 ?? '');
    } else {
        error_log("천장 데이터를 찾을 수 없습니다 (num: {$num})");
        die("<div class='alert alert-warning'>해당 천장 데이터를 찾을 수 없습니다.</div>");
    }
    
} catch (PDOException $ex) {
    error_log("천장 데이터 조회 오류 (num: {$num}): " . $ex->getMessage());
    die("<div class='alert alert-danger'>데이터를 불러오는 중 문제가 발생했습니다: " . htmlspecialchars($ex->getMessage()) . "</div>");
}

// 필수 변수들 초기화 (?? '' 형태) - _row.php에서 설정되지 않을 수 있음
$main_draw = $main_draw ?? '';
$lc_draw = $lc_draw ?? '';
$etc_draw = $etc_draw ?? '';
$bon_su = $bon_su ?? 0;
$lc_su = $lc_su ?? 0;
$etc_su = $etc_su ?? 0;
$type = $type ?? '';
$secondord = $secondord ?? '';
$workplacename = $workplacename ?? '';
$cabledone = $cabledone ?? '';

// 날짜 필드 초기화
$eunsung_laser_date = $eunsung_laser_date ?? '';
$mainbending_date = $mainbending_date ?? '';
$mainwelding_date = $mainwelding_date ?? '';
$mainpainting_date = $mainpainting_date ?? '';
$mainassembly_date = $mainassembly_date ?? '';
$lclaser_date = $lclaser_date ?? '';
$lcbending_date = $lcbending_date ?? '';
$lcwelding_date = $lcwelding_date ?? '';
$lcpainting_date = $lcpainting_date ?? '';
$lcassembly_date = $lcassembly_date ?? '';
$etclaser_date = $etclaser_date ?? '';
$etcbending_date = $etcbending_date ?? '';
$etcwelding_date = $etcwelding_date ?? '';
$etcpainting_date = $etcpainting_date ?? '';
$etcassembly_date = $etcassembly_date ?? '';

// 기타 필드 초기화
$first_writer = $first_writer ?? '';
$update_log = $update_log ?? '';
$check_draw = $check_draw ?? '';
$scale = $scale ?? '';
$su = $su ?? '';
$memo = $memo ?? '';
$outsourcing = $outsourcing ?? '';
$outsourcing_memo = $outsourcing_memo ?? '';
$address = $address ?? '';
$firstord = $firstord ?? '';
$firstordman = $firstordman ?? '';
$firstordmantel = $firstordmantel ?? '';
$secondordman = $secondordman ?? '';
$secondordmantel = $secondordmantel ?? '';
$delivery = $delivery ?? '';
$delipay = $delipay ?? '';
$chargedman = $chargedman ?? '';
$chargedmantel = $chargedmantel ?? '';
$inseung = $inseung ?? '';
$car_insize = $car_insize ?? '';
$material1 = $material1 ?? '';
$material2 = $material2 ?? '';
$material3 = $material3 ?? '';
$material4 = $material4 ?? '';
$material5 = $material5 ?? '';
$material6 = $material6 ?? '';
$memo2 = $memo2 ?? '';

// 설계 완료 여부
$main_draw_arr = "";
if (substr($main_draw, 0, 2) == "20") {
    $main_draw_arr = mb_substr($main_draw, 0, 10, "utf-8");
} elseif ((int)$bon_su < 1) {
    $main_draw_arr = "X";
}

$lc_draw_arr = "";
if (substr($lc_draw, 0, 2) == "20") {
    $lc_draw_arr = mb_substr($lc_draw, 0, 10, "utf-8");
} elseif ((int)$lc_su < 1) {
    $lc_draw_arr = "X";
}

if (in_array($type, ['011', '012', '013D', '025', '017', '014'])) {
    $lc_draw_arr = "X";
}

$etc_draw_arr = "";
if (substr($etc_draw, 0, 2) == "20") {
    $etc_draw_arr = mb_substr($etc_draw, 0, 10, "utf-8");
} elseif ((int)$etc_su < 1) {
    $etc_draw_arr = "X";
}

// 본천장 날짜 처리
if ((int)$bon_su < 1) {
    $eunsung_laser_date = "X";
    $mainbending_date = "X";
    $mainwelding_date = "X";
    $mainpainting_date = "X";
    $mainassembly_date = "X";
}

// L/C 날짜 처리
if ((int)$lc_su < 1 || in_array($type, ['011', '012', '013D', '025', '017', '014', '037', '038'])) {
    $lclaser_date = "X";
    $lcbending_date = "X";
    $lcwelding_date = "X";
    $lcpainting_date = "X";
    $lcassembly_date = "X";
}

// 기타 날짜 처리
if ((int)$etc_su < 1) {
    $etclaser_date = "X";
    $etcwelding_date = "X";
    $etcpainting_date = "X";
    $etcassembly_date = "X";
    $etcbending_date = "X";
}

$workplacename = "[" . $secondord . "]" . $workplacename;

include getDocumentRoot() . '/load_header.php';
?>
<style>
    #panel, #flip {
        padding: 3px;
        text-align: left;
        color: brown;
        border: solid 1px #c3c3c3;
    }
    
    #panel {
        padding: 40px;
        display: none;
    }
    
    #addpanel, #addflip {
        padding: 3px;
        text-align: center;
        color: white;
        background-color: grey;
        border: solid 1px #c3c3c3;
    }
    
    #addpanel {
        padding: 30px;
        display: none;
    }
    
    .table th {
        vertical-align: middle;
        font-size: 16px;
    }
    
    .table td {
        vertical-align: middle;
        font-size: 16px;
    }
    
    @media (min-width: 800px) {
        .table th {
            font-size: 22px;
        }
        .table td {
            font-size: 22px;
        }
    }
    
    input[type="checkbox"] {
        transform: scale(1.6);
        margin-right: 10px;
    }
    
    <?php if ($chkMobile): ?>
    body, table th, table td, h3, .form-control {
        font-size: 30px;
    }
    h4 {
        font-size: 35px;
    }
    .btn-sm {
        font-size: 26px;
    }
    <?php endif; ?>
</style>

<title>천장/LC</title>
</head>

<body>
    <form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="20971520"> <!-- 20MB -->
        <input type="hidden" id="first_writer" name="first_writer" value="<?= htmlspecialchars($first_writer ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="update_log" name="update_log" value="<?= htmlspecialchars($update_log ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="check_draw" name="check_draw" value="<?= htmlspecialchars($check_draw ?? '', ENT_QUOTES, 'UTF-8') ?>" size="1">
        <input type="hidden" id="scale" name="scale" value="<?= htmlspecialchars($scale ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="num" name="num" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" id="workplacename" name="workplacename" value="<?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?>">
        <input id="pInput" name="pInput" type="hidden" value="0">
        <input id="vacancy" name="vacancy" type="hidden">
        
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-start mb-2">
                        <button class="btn btn-sm" onclick="window.close();" 
                                style="background: linear-gradient(90deg, #e0e4ea 0%, #cfd8dc 100%); color: #495057; font-weight:600; border:1px solid #b0bec5; box-shadow: 0 2px 8px rgba(180,180,180,0.08);">
                            &times; 닫기
                        </button>&nbsp;
                    </div>
                    
                    <div class="d-flex mt-2 mb-2 justify-content-center">
                        <h2>
                            <span class="badge" style="background: linear-gradient(90deg, #e0e4ea 0%, #cfd8dc 100%); color: #495057; font-weight:600; border:1px solid #b0bec5; box-shadow: 0 2px 8px rgba(180,180,180,0.08);">
                                천장/LC 조회 수정
                            </span>
                        </h2>
                        <button class="btn btn-sm mx-2" id="viewOrder" 
                                onclick="navigateToLink(event, '../ceiling/view.php?num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>'); return false;" 
                                style="background: linear-gradient(90deg, #ececec 0%, #d3d3d3 100%); color: #333; border: 1px solid #bdbdbd; box-shadow: 0 2px 6px rgba(180,180,180,0.08); font-weight: 500;">
                            <i class="bi bi-arrow-right-circle" style="color:#888;"></i> 수주서 이동
                        </button>
                    </div>
                    
                    <div class="d-flex mt-3 mb-2 justify-content-center">
                        <?php if ($chkMobile): ?>
                            <!-- 모바일: 카메라/앨범 선택 버튼 -->
                            <button type="button" class="btn btn-lg btn-primary mx-2" onclick="showPhotoOptions();" style="font-size: 24px; padding: 12px 24px;">
                                📸 포장사진등록
                            </button>
                            
                            <!-- 숨겨진 file input (카메라용) -->
                            <input id="upfile_camera" name="upfile_camera[]" type="file" 
                                   accept="image/*" capture="environment" multiple 
                                   style="display: none;" onchange="handleFileSelect(this);">
                            
                            <!-- 숨겨진 file input (앨범용) -->
                            <input id="upfile_gallery" name="upfile_gallery[]" type="file" 
                                   accept="image/*" multiple 
                                   style="display: none;" onchange="handleFileSelect(this);">
                            
                            <!-- 실제 전송용 hidden input -->
                            <input id="upfile" name="upfile[]" type="file" multiple style="display: none;">
                    <?php else: ?>
                    <!-- 데스크톱: 파일 선택만 -->
                    <label for="upfile" class="form-control text-center fs-4 mx-3" style="width:20%;">포장사진등록</label>
                    <input id="upfile" name="upfile[]" type="file" class="form-control fs-4" style="width:30%;" 
                           multiple accept=".gif, .jpg, .jpeg, .png" onchange="handleDesktopFileSelect(this);">
                    <?php endif; ?>
                    </div>
                    
                    <?php if ($chkMobile): ?>
                    <!-- 모바일 사진 선택 모달 -->
                    <div id="photoOptionsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center;">
                        <div style="background: white; border-radius: 12px; padding: 20px; width: 90%; max-width: 400px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                            <h3 style="text-align: center; margin-bottom: 20px; font-size: 28px; color: #333;">사진 등록 방법 선택</h3>
                            
                            <button type="button" onclick="selectCamera();" 
                                    style="width: 100%; padding: 20px; margin-bottom: 15px; font-size: 26px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                                📷 카메라로 촬영하기
                            </button>
                            
                            <button type="button" onclick="selectGallery();" 
                                    style="width: 100%; padding: 20px; margin-bottom: 15px; font-size: 26px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; border-radius: 8px; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);">
                                🖼️ 앨범에서 선택하기
                            </button>
                            
                            <button type="button" onclick="closePhotoOptions();" 
                                    style="width: 100%; padding: 15px; font-size: 22px; background: #f0f0f0; color: #666; border: 1px solid #ddd; border-radius: 8px;">
                                취소
                            </button>
                        </div>
                    </div>
                    
                    <!-- 모바일 디버그 정보 표시 영역 -->
                    <div id="mobileDebugInfo" style="display: none; position: fixed; bottom: 0; left: 0; right: 0; background: #333; color: #fff; padding: 10px; font-size: 12px; max-height: 200px; overflow-y: auto; z-index: 10000;">
                        <button onclick="document.getElementById('mobileDebugInfo').style.display='none';" 
                                style="float: right; background: #f44336; color: white; border: none; padding: 5px 10px; border-radius: 3px;">
                            닫기
                        </button>
                        <div id="debugLog"></div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">현장명</th>
                                    <th class="text-center">발주접수일</th>
                                    <th class="text-center">납기일</th>
                                    <th class="text-center">본천장설계</th>
                                    <th class="text-center">LC설계</th>
                                    <th class="text-center">기타설계</th>
                                    <th class="text-center">결합(SET)</th>
                                    <th class="text-center">본천장</th>
                                    <th class="text-center">L/C</th>
                                    <th class="text-center">기타</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start"><?= htmlspecialchars($workplacename, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center"><?= htmlspecialchars($orderday, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center"><?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center"><?= htmlspecialchars($main_draw_arr, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center"><?= htmlspecialchars($lc_draw_arr, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center"><?= htmlspecialchars($etc_draw_arr, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center"><?= htmlspecialchars($su ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center"><?= (int)($bon_su ?? 0) > 0 ? htmlspecialchars($bon_su, ENT_QUOTES, 'UTF-8') : '' ?></td>
                                    <td class="text-center"><?= (int)($lc_su ?? 0) > 0 ? htmlspecialchars($lc_su, ENT_QUOTES, 'UTF-8') : '' ?></td>
                                    <td class="text-center"><?= (int)($etc_su ?? 0) > 0 ? htmlspecialchars($etc_su, ENT_QUOTES, 'UTF-8') : '' ?></td>
                                </tr>
                                <tr>
                                    <td colspan="10" class="text-start text-primary fw-bold">
                                        <span class="text-dark">비고:</span>
                                        <?= htmlspecialchars($memo ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (!empty($outsourcing ?? '')): ?>
                    <div class="row justify-content-center">
                        <div class="badge bg-success text-white text-center my-2" style="width:35%;">
                            <h3 class="mb-0">외주가공 있음</h3>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="text-danger text-center my-2">
                            <h5 class="mb-0"><?= htmlspecialchars($outsourcing_memo ?? '', ENT_QUOTES, 'UTF-8') ?></h5>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="<?= $chkMobile ? 'col-sm-12' : 'col-sm-4' ?> rounded" style="border: 2px solid #392f31;">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-primary text-center"><h4>본천장 제조현황</h4></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">본 laser완료</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="eunsung_laser_date" id="eunsung_laser_date" 
                                                       class="form-control w120px fs-6" style="text-align:center;" 
                                                       value="<?= htmlspecialchars($eunsung_laser_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($eunsung_laser_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('eunsung_laser_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('eunsung_laser_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">본 절곡</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="mainbending_date" id="mainbending_date" 
                                                       class="form-control w120px fs-6" style="text-align:center;" 
                                                       value="<?= htmlspecialchars($mainbending_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($mainbending_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('mainbending_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('mainbending_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">본 제관</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="mainwelding_date" id="mainwelding_date" 
                                                       class="form-control w120px fs-6" style="text-align:center;" 
                                                       value="<?= htmlspecialchars($mainwelding_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($mainwelding_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('mainwelding_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('mainwelding_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">본 도장</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="mainpainting_date" id="mainpainting_date" 
                                                       class="form-control w120px fs-6" style="text-align:center;" 
                                                       value="<?= htmlspecialchars($mainpainting_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($mainpainting_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('mainpainting_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('mainpainting_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">본 조립</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="mainassembly_date" id="mainassembly_date" 
                                                       class="form-control w120px fs-6" style="text-align:center;" 
                                                       value="<?= htmlspecialchars($mainassembly_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($mainassembly_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('mainassembly_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('mainassembly_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="<?= $chkMobile ? 'col-sm-12' : 'col-sm-4' ?> rounded" style="border: 2px solid #392f31;">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center text-danger">
                                            <h5>L/C (Type: <?= htmlspecialchars($type ?? '', ENT_QUOTES, 'UTF-8') ?>)</h5>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">L/C laser</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="lclaser_date" id="lclaser_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($lclaser_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($lclaser_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('lclaser_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('lclaser_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">L/C 절곡</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="lcbending_date" id="lcbending_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($lcbending_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($lcbending_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('lcbending_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('lcbending_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">L/C 제관</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="lcwelding_date" id="lcwelding_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($lcwelding_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($lcwelding_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('lcwelding_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('lcwelding_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">L/C 도장</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="lcpainting_date" id="lcpainting_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($lcpainting_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($lcpainting_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('lcpainting_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('lcpainting_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-danger text-center">
                                            <input class="ms-3 fs-5" type="checkbox" id="cabledone" name="cabledone" value="결선완료" 
                                                   <?= ($cabledone ?? '') === '결선완료' ? 'checked' : '' ?>>
                                            <label for="cabledone" class="fw-bold fs-5">결선완료</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">L/C 포장</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="lcassembly_date" id="lcassembly_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($lcassembly_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($lcassembly_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('lcassembly_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('lcassembly_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="<?= $chkMobile ? 'col-sm-12' : 'col-sm-4' ?> rounded" style="border: 2px solid #392f31;">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center text-secondary"><h4>기타</h4></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">기타 레이저</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="etclaser_date" id="etclaser_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($etclaser_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($etclaser_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('etclaser_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('etclaser_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">기타 절곡</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="etcbending_date" id="etcbending_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($etcbending_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($etcbending_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('etcbending_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('etcbending_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">기타 제관</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="etcwelding_date" id="etcwelding_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($etcwelding_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($etcwelding_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('etcwelding_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('etcwelding_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">기타 도장</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="etcpainting_date" id="etcpainting_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($etcpainting_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($etcpainting_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('etcpainting_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('etcpainting_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">기타 조립</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <input type="text" name="etcassembly_date" id="etcassembly_date" 
                                                       class="form-control text-center w120px fs-6" 
                                                       value="<?= htmlspecialchars($etcassembly_date, ENT_QUOTES, 'UTF-8') ?>">&nbsp;&nbsp;
                                                <?php if ($etcassembly_date != 'X'): ?>
                                                    <button type="button" class="btn btn-primary me-4" onclick="saveData('etcassembly_date');">완료</button>
                                                    <button type="button" class="btn btn-danger" onclick="dodatadel('etcassembly_date');">삭제</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-2 mb-2">&nbsp;&nbsp;&nbsp;</div>
                    
                    <div class="row mt-2 mb-2">
                        <h2 class="fs-2 font-center text-center">
                            <div id="addflip">추가정보 보기</div>
                        </h2>
                    </div>
                    
                    <div id="addpanel">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="font-center text-center" colspan="3">현장주소</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($address ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center" style="width:15%;">제품출고일</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($workday, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="font-center text-center">원청</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($firstord ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">담당</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($firstordman ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="font-center text-center">연락처</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($firstordmantel ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">발주처</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($secondord ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="font-center text-center">담당</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($secondordman ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">연락처</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($secondordmantel ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="font-center text-center">운반비내역</td>
                                    <td class="font-center text-center"><?= htmlspecialchars(($delivery ?? '') . ' ' . ($delipay ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">담당</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($chargedman ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="font-center text-center">연락처</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($chargedmantel ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">타입</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($type ?? '', ENT_QUOTES, 'UTF-8') ?> &nbsp;&nbsp;&nbsp; 인승: <?= htmlspecialchars($inseung ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="font-center text-center">car insize</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($car_insize ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">재질1</td>
                                    <td class="font-center text-center"><?= htmlspecialchars(($material2 ?? '') . ' ' . ($material1 ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="font-center text-center">재질2</td>
                                    <td class="font-center text-center"><?= htmlspecialchars(($material4 ?? '') . ' ' . ($material3 ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">재질3</td>
                                    <td class="font-center text-center"><?= htmlspecialchars(($material6 ?? '') . ' ' . ($material5 ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="font-center text-center">비고1</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($memo ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">비고2</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($memo2 ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="font-center text-center">청구일자</td>
                                    <td class="font-center text-center"><?= htmlspecialchars($demand, ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">최초등록</td>
                                    <td class="font-center text-center" colspan="3"><?= htmlspecialchars($first_writer ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                                <tr>
                                    <td class="font-center text-center">log</td>
                                    <td class="font-center text-start" colspan="3"><?= htmlspecialchars($update_log ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row">
                        <div id="displayPicture" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>

<script type="text/javascript">
(function() {
    'use strict';
    
    var num = <?= json_encode($num, JSON_UNESCAPED_UNICODE) ?>;
    var picNum = <?= json_encode($picNum, JSON_UNESCAPED_UNICODE) ?>;
    var picData = <?= json_encode($picData, JSON_UNESCAPED_UNICODE) ?>;
    var isMobile = <?= json_encode($chkMobile, JSON_UNESCAPED_UNICODE) ?>;
    
    // 페이지 로드 완료 플래그
    var pageLoadComplete = false;
    var timer3 = null;
    
    /**
     * 모바일 디버그 로그 함수
     */
    function mobileLog(message, data) {
        console.log(message, data);
        
        if (isMobile) {
            var debugDiv = document.getElementById('debugLog');
            if (debugDiv) {
                var time = new Date().toLocaleTimeString('ko-KR');
                var logMessage = time + ' - ' + message;
                if (data) {
                    if (typeof data === 'object') {
                        logMessage += ': ' + JSON.stringify(data);
                    } else {
                        logMessage += ': ' + data;
                    }
                }
                
                var logEntry = '<div style="border-bottom: 1px solid #555; padding: 5px 0; font-size: 11px; word-wrap: break-word;">' + logMessage + '</div>';
                debugDiv.innerHTML += logEntry;
                
                // 디버그 창 자동 표시
                document.getElementById('mobileDebugInfo').style.display = 'block';
                
                // 스크롤을 맨 아래로
                debugDiv.scrollTop = debugDiv.scrollHeight;
                
                // 최대 50개 로그만 유지
                var logEntries = debugDiv.children;
                if (logEntries.length > 50) {
                    debugDiv.removeChild(logEntries[0]);
                }
            }
        }
    }
    
    $(document).ready(function() {
        // 모달 닫기 버튼
        $("#closeModalBtn").click(function() {
            $('#myModal').modal('hide');
        });
        
        // 초기값 설정
        $("#pInput").val('50');
        
        // 초기 사진 로드 (한 번만)
        setTimeout(function() {
            displayPictureLoad();
            pageLoadComplete = true;
        }, 500);
        
        // 2초 간격으로 사진 업데이트 체크 (업로드 후에만)
        timer3 = setInterval(function() {
            var pInputVal = $("#pInput").val();
            
            // 업로드 완료 상태일 때만 새로고침
            if (pInputVal == '100') {
                console.log("새로운 사진 로드 중...");
                displayPicture();
                $("#pInput").val(''); // 상태 초기화하여 반복 방지
            }
        }, 2000);
        
        // 파일 선택 시 업로드 처리 (데스크톱)
        $("#upfile").change(function(e) {
            FileProcess();
        });
        
        // 추가 정보 토글
        $("#addflip").click(function() {
            $("#addpanel").slideToggle();
        });
        
        $("#addpanel").click(function() {
            $("#addpanel").slideUp("slow");
        });
        
        // 결선 완료 체크박스
        $("#cabledone").change(function() {
            var checked = $(this).is(":checked") ? "결선완료" : "";
            var num = $("#num").val();
            
            $.ajax({
                url: "insert_cabledone.php",
                type: "POST",
                data: { mode: "modify", num: num, cabledone: checked },
                dataType: "json",
                success: function(response) {
                    if (response.num && typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '저장 완료',
                            text: '결선 상태가 저장되었습니다.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    // 부모창 업데이트 (결선완료 체크박스 상태)
                    updateParentWindow('cabledone', checked);
                },
                error: function(xhr, status, error) {
                    console.log("Error: ", status, error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '저장 실패',
                            text: '서버에 데이터를 저장하는 데 실패했습니다.',
                            icon: 'error'
                        });
                    }
                }
            });
        });
    });
    
    /**
     * 모바일 사진 선택 옵션 모달 표시
     */
    window.showPhotoOptions = function() {
        var modal = document.getElementById('photoOptionsModal');
        if (modal) {
            modal.style.display = 'flex';
        }
    };
    
    /**
     * 모바일 사진 선택 옵션 모달 닫기
     */
    window.closePhotoOptions = function() {
        var modal = document.getElementById('photoOptionsModal');
        if (modal) {
            modal.style.display = 'none';
        }
    };
    
    /**
     * 카메라로 촬영 선택
     */
    window.selectCamera = function() {
        console.log("카메라 촬영 선택");
        closePhotoOptions();
        document.getElementById('upfile_camera').click();
    };
    
    /**
     * 앨범에서 선택
     */
    window.selectGallery = function() {
        console.log("앨범 선택");
        closePhotoOptions();
        document.getElementById('upfile_gallery').click();
    };
    
    /**
     * 파일 선택 처리 (모바일)
     */
    window.handleFileSelect = function(input) {
        mobileLog("=== 모바일 파일 선택 시작 ===");
        mobileLog("선택된 파일 수", input.files ? input.files.length : 0);
        
        if (!input.files || input.files.length === 0) {
            mobileLog("선택된 파일이 없습니다.");
            return;
        }
        
        try {
            // 새 FormData 생성 (기존 form 데이터 제외)
            var data = new FormData();
            
            // 필수 필드 추가
            data.append("num", num);
            data.append("tablename", "ceilingwrap");
            data.append("item", "ceilingwrap");
            data.append("folderPath", "imgwork");
            data.append("DBtable", "picuploads");
            data.append("isMobile", isMobile ? "1" : "0");
            
            mobileLog("FormData 생성 완료, num", num);
            
            // 선택된 파일들을 FormData에 추가
            var fileCount = 0;
            var fileInfo = [];
            
            for (var i = 0; i < input.files.length; i++) {
                var file = input.files[i];
                
                var fileSizeMB = Math.round(file.size / (1024 * 1024) * 100) / 100;
                var sizeText = fileSizeMB >= 1 ? fileSizeMB + 'MB' : Math.round(file.size / 1024) + 'KB';
                
                fileInfo.push({
                    name: file.name,
                    size: sizeText,
                    type: file.type || 'unknown',
                    lastModified: new Date(file.lastModified).toLocaleString('ko-KR'),
                    webkitRelativePath: file.webkitRelativePath || '',
                    isFromCamera: file.type === '' || !file.type // 카메라 촬영 파일 감지
                });
                
                // 파일 크기 체크 (20MB 제한)
                if (file.size > 20 * 1024 * 1024) {
                    mobileLog("파일 크기 초과", {
                        name: file.name,
                        size: Math.round(file.size / (1024 * 1024)) + "MB",
                        limit: "20MB"
                    });
                    alert("파일 크기가 너무 큽니다: " + file.name + "\n크기: " + Math.round(file.size / (1024 * 1024)) + "MB\n(최대 20MB)");
                    continue;
                }
                
                // 이미지 파일 확인 (모바일 카메라 촬영 시 type이 빈 문자열일 수 있음)
                if (file.type && !file.type.startsWith('image/')) {
                    mobileLog("이미지 파일이 아님", file.name);
                    alert("이미지 파일만 업로드 가능합니다: " + file.name);
                    continue;
                }
                
                // 모바일 카메라 촬영 파일의 경우 type이 빈 문자열일 수 있으므로 파일 확장자로 확인
                if (!file.type && file.name) {
                    var extension = file.name.toLowerCase().split('.').pop();
                    var allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!allowedExtensions.includes(extension)) {
                        mobileLog("지원하지 않는 파일 확장자", file.name);
                        alert("지원하지 않는 파일 형식입니다: " + file.name);
                        continue;
                    }
                    mobileLog("파일 확장자로 이미지 확인됨", extension);
                }
                
                // 카메라 촬영 파일인지 확인
                var isCameraFile = file.type === '' || !file.type;
                if (isCameraFile) {
                    mobileLog("카메라 촬영 파일 감지", {
                        name: file.name,
                        size: file.size,
                        lastModified: new Date(file.lastModified).toLocaleString('ko-KR')
                    });
                }
                
                data.append("upfile[]", file);
                fileCount++;
            }
            
            mobileLog("파일 정보", fileInfo);
            
            if (fileCount === 0) {
                mobileLog("업로드할 파일이 없습니다.");
                if (typeof hideMsgModal !== 'undefined') {
                    hideMsgModal();
                }
                return;
            }
            
            mobileLog("업로드 시작", fileCount + "개 파일");
            
            if (typeof showMsgModal !== 'undefined') {
                showMsgModal(1);
            }
            
            // AJAX 업로드
            $.ajax({
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                url: "/filedrive/fileprocess.php",
                type: "POST",
                data: data,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                            mobileLog("업로드 진행률", percentComplete + "%");
                            
                            // 진행률 표시 업데이트
                            if (typeof showMsgModal !== 'undefined') {
                                var progressText = "업로드 중... " + percentComplete + "%";
                                // 진행률을 표시할 수 있다면 여기서 업데이트
                            }
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    mobileLog("업로드 성공", typeof response);
                    handleUploadResponse(response);
                    $("#pInput").val('100');
                },
                error: function(jqxhr, status, error) {
                    mobileLog("업로드 실패", {
                        status: jqxhr.status,
                        error: error,
                        statusText: jqxhr.statusText
                    });
                    
                    if (typeof hideMsgModal !== 'undefined') {
                        hideMsgModal();
                    }
                    
                    var errorMsg = "파일 업로드 중 오류가 발생했습니다.\n";
                    errorMsg += "상태: " + jqxhr.status + "\n";
                    errorMsg += "오류: " + error;
                    
                    if (jqxhr.responseText) {
                        mobileLog("응답 텍스트", jqxhr.responseText.substring(0, 500));
                        
                        // JSON 파싱 시도
                        try {
                            var responseData = JSON.parse(jqxhr.responseText);
                            if (responseData.error) {
                                errorMsg += "\n상세 오류: " + responseData.error;
                            }
                            if (responseData.message) {
                                errorMsg += "\n메시지: " + responseData.message;
                            }
                            if (responseData.file) {
                                errorMsg += "\n파일: " + responseData.file;
                            }
                            if (responseData.line) {
                                errorMsg += "\n라인: " + responseData.line;
                            }
                        } catch (parseError) {
                            mobileLog("JSON 파싱 실패", parseError.message);
                            // JSON이 아닌 경우 HTML 오류일 가능성이 높음
                            if (jqxhr.responseText.indexOf('Fatal error') !== -1) {
                                errorMsg += "\n서버 오류: PHP Fatal Error 발생";
                                mobileLog("Fatal Error 감지", jqxhr.responseText.substring(0, 1000));
                            } else if (jqxhr.responseText.indexOf('Parse error') !== -1) {
                                errorMsg += "\n서버 오류: PHP Parse Error 발생";
                                mobileLog("Parse Error 감지", jqxhr.responseText.substring(0, 1000));
                            } else if (jqxhr.responseText.indexOf('Call to undefined function') !== -1) {
                                errorMsg += "\n서버 오류: 정의되지 않은 함수 호출";
                                mobileLog("Undefined Function 감지", jqxhr.responseText.substring(0, 1000));
                            } else if (jqxhr.responseText.indexOf('Class ') !== -1 && jqxhr.responseText.indexOf(' not found') !== -1) {
                                errorMsg += "\n서버 오류: 클래스를 찾을 수 없음";
                                mobileLog("Class Not Found 감지", jqxhr.responseText.substring(0, 1000));
                            }
                        }
                    }
                    
                    alert(errorMsg);
                }
            });
            
        } catch (e) {
            mobileLog("파일 처리 중 예외", e.message);
            alert("파일 처리 중 오류가 발생했습니다: " + e.message);
            
            if (typeof hideMsgModal !== 'undefined') {
                hideMsgModal();
            }
        } finally {
            // input 초기화
            input.value = '';
        }
    };
    
    /**
     * 데스크톱 파일 선택 처리
     */
    window.handleDesktopFileSelect = function(input) {
        console.log("=== 데스크톱 파일 선택 시작 ===");
        console.log("선택된 파일 수:", input.files.length);
        
        if (!input.files || input.files.length === 0) {
            console.log("선택된 파일이 없습니다.");
            return;
        }
        
        try {
            // 파일 크기 검증
            var totalSize = 0;
            var fileInfo = [];
            
            for (var i = 0; i < input.files.length; i++) {
                var file = input.files[i];
                totalSize += file.size;
                
                var fileSizeMB = Math.round(file.size / (1024 * 1024) * 100) / 100;
                var sizeText = fileSizeMB >= 1 ? fileSizeMB + 'MB' : Math.round(file.size / 1024) + 'KB';
                
                fileInfo.push({
                    name: file.name,
                    size: sizeText,
                    type: file.type
                });
                
                // 개별 파일 크기 체크 (20MB 제한)
                if (file.size > 20 * 1024 * 1024) {
                    console.error("파일이 너무 큽니다:", file.name, file.size);
                    alert("파일 크기가 너무 큽니다: " + file.name + "\n크기: " + fileSizeMB + "MB\n(최대 20MB)");
                    input.value = '';
                    return;
                }
                
                // 이미지 파일 확인
                if (file.type && !file.type.startsWith('image/')) {
                    console.error("이미지 파일이 아닙니다:", file.name, file.type);
                    alert("이미지 파일만 업로드 가능합니다: " + file.name);
                    input.value = '';
                    return;
                }
            }
            
            console.log("파일 정보:", fileInfo);
            console.log("총 크기:", Math.round(totalSize / (1024 * 1024) * 100) / 100 + "MB");
            
            // 전체 크기 체크 (100MB 제한)
            if (totalSize > 100 * 1024 * 1024) {
                console.error("전체 파일 크기가 너무 큽니다:", totalSize);
                alert("전체 파일 크기가 너무 큽니다: " + Math.round(totalSize / (1024 * 1024) * 100) / 100 + "MB\n(최대 100MB)");
                input.value = '';
                return;
            }
            
            // 기존 폼 제출 방식 사용
            var form = $('#board_form')[0];
            if (form) {
                console.log("폼 제출 시작");
                form.submit();
            }
            
        } catch (e) {
            console.error("파일 처리 중 예외:", e);
            alert("파일 처리 중 오류가 발생했습니다: " + e.message);
            input.value = '';
        }
    };
    
    /**
     * 업로드 응답 처리 공통 함수
     */
    function handleUploadResponse(response) {
        console.log("응답 타입:", typeof response);
        
        var successCount = 0;
        var errorCount = 0;
        var errorMessages = [];
        
        // 응답이 배열인지 확인
        if (Array.isArray(response)) {
            response.forEach(function(item) {
                if (item.status === "success") {
                    successCount++;
                } else if (item.status === "error") {
                    errorCount++;
                    errorMessages.push("파일: " + item.file + ", 메시지: " + item.message);
                }
            });
        } else if (response && typeof response === 'object') {
            // 단일 객체 응답 처리
            if (response.status === "success") {
                successCount = 1;
            } else if (response.status === "error") {
                errorCount = 1;
                errorMessages.push("메시지: " + (response.message || '알 수 없는 오류'));
            }
        } else {
            console.error("예상치 못한 응답 형식:", response);
            errorCount = 1;
            errorMessages.push("서버 응답 형식이 올바르지 않습니다.");
        }
        
        if (successCount > 0 && typeof Toastify !== 'undefined') {
            Toastify({
                text: successCount + "개의 파일이 성공적으로 업로드되었습니다.",
                duration: 2000,
                close: true,
                gravity: "top",
                position: "center",
                backgroundColor: "#4fbe87"
            }).showToast();
        }
        
        if (errorCount > 0) {
            console.error("업로드 오류:", errorMessages);
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: "오류 발생: " + errorCount + "개의 파일 업로드 실패\n상세 오류: " + errorMessages.join("\n"),
                    duration: 5000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#f44336"
                }).showToast();
            } else {
                alert("업로드 실패:\n" + errorMessages.join("\n"));
            }
        }
        
        setTimeout(function() {
            if (typeof hideMsgModal !== 'undefined') {
                hideMsgModal();
            }
        }, 1000);
    }
    
    // 파일 업로드 처리 함수 (데스크톱용)
    function FileProcess() {
        var form = $('#board_form')[0];
        var data = new FormData(form);
        
        data.append("tablename", "ceilingwrap");
        data.append("item", "ceilingwrap");
        data.append("folderPath", "imgwork");
        data.append("DBtable", "picuploads");
        
        console.log("업로드 파일:", $('#upfile').val());
        
        if (typeof showMsgModal !== 'undefined') {
            showMsgModal(1);
        }
        
        $.ajax({
            enctype: "multipart/form-data",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            url: "/filedrive/fileprocess.php",
            type: "POST",
            data: data,
            success: function(response) {
                console.log("업로드 응답:", response);
                handleUploadResponse(response);
                $("#pInput").val('100');
            },
            error: function(jqxhr, status, error) {
                console.error("업로드 실패:", jqxhr, status, error);
                console.error("응답 텍스트:", jqxhr.responseText);
                
                if (typeof hideMsgModal !== 'undefined') {
                    hideMsgModal();
                }
                
                alert("파일 업로드 중 오류가 발생했습니다: " + error);
            }
        });
    }
    
    // 새로운 사진 불러오기 (업로드 후)
    window.displayPicture = function() {
        console.log("새로운 사진 불러오기 시작");
        $('#displayPicture').show();
        var params = $("#num").val();
        
        $.ajax({
            url: '/filedrive/fileprocess.php',
            type: 'GET',
            data: {
                num: params,
                tablename: 'ceilingwrap',
                item: 'ceilingwrap',
                folderPath: 'imgwork'
            },
            dataType: 'json'
        }).done(function(data) {
            console.log("사진 데이터:", data);
            
            $("#displayPicture").html('');
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(picData, index) {
                    var thumbnail = picData.thumbnail || '/assets/default-thumbnail.png';
                    var link = picData.link || '#';
                    var fileId = picData.fileId || null;
                    
                    if (!fileId) {
                        console.error("fileId가 누락되었습니다. index: " + index, picData);
                        return;
                    }
                    
                    $("#displayPicture").append(
                        "<div class='d-flex justify-content-center mt-2 mb-1'>" +
                            "<img id='pic" + index + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +
                        "</div>" +
                        "<div class='d-flex justify-content-center'>" +
                            "<button type='button' class='mb-3 text-center btn btn-danger' id='delPic" + index + "' onclick=\"delPicFn('" + index + "', '" + fileId + "')\">" +
                                "<i class='bi bi-trash'></i>" +
                            "</button>" +
                        "</div>"
                    );
                });
            } else {
                $("#displayPicture").append("<div class='text-center text-muted'>등록된 사진이 없습니다.</div>");
            }
            
            console.log("새로운 사진 로드 완료:", data.length + "개");
            $("#pInput").val('loaded'); // 로드 완료 표시 (무한 반복 방지)
        }).fail(function(error) {
            console.error("사진 불러오기 오류:", error);
            $("#pInput").val('error'); // 오류 상태 표시
            alert("사진을 불러오는 중 문제가 발생했습니다.");
        });
    };
    
    // 기존 사진 로드 (페이지 로드 시 한 번만)
    window.displayPictureLoad = function() {
        console.log("기존 사진 로드 시작");
        $('#displayPicture').show();
        
        $("#displayPicture").html('');
        
        if (!Array.isArray(picData) || picData.length === 0) {
            console.log("표시할 사진이 없습니다.");
            $("#displayPicture").append("<div class='text-center text-muted'>등록된 사진이 없습니다.</div>");
            $("#pInput").val('loaded'); // 로드 완료 표시
            return;
        }
        
        picData.forEach(function(pic, index) {
            var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
            var link = pic.link || '#';
            var fileId = pic.fileId || null;
            
            if (!fileId) {
                console.error("fileId가 누락되었습니다. index: " + index, pic);
                return;
            }
            
            $("#displayPicture").append(
                "<div class='d-flex justify-content-center mt-2 mb-1'>" +
                    "<img id='pic" + index + "' src='" + thumbnail + "' style='width:150px; height:auto;'>" +
                "</div>" +
                "<div class='d-flex justify-content-center'>" +
                    "<button type='button' class='mb-3 text-center btn btn-danger' id='delPic" + index + "' onclick=\"delPicFn('" + index + "', '" + fileId + "')\">" +
                        "<i class='bi bi-trash'></i>" +
                    "</button>" +
                "</div>"
            );
        });
        
        console.log("기존 사진 로드 완료:", picData.length + "개");
        $("#pInput").val('loaded'); // 로드 완료 표시 (무한 반복 방지)
    };
    
    // 파일 삭제 처리
    window.delPicFn = function(divID, fileId) {
        if (confirm("삭제한 사진은 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
            $.ajax({
                url: '/filedrive/fileprocess.php',
                type: 'DELETE',
                data: JSON.stringify({
                    fileId: fileId,
                    tablename: "ceilingwrap",
                    item: "ceilingwrap",
                    folderPath: "imgwork",
                    DBtable: "picuploads"
                }),
                contentType: "application/json",
                dataType: 'json'
            }).done(function(response) {
                if (response.status === 'success') {
                    console.log("삭제 완료:", response);
                    $("#pic" + divID).remove();
                    $("#delPic" + divID).remove();
                    
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: "사진이 성공적으로 삭제되었습니다.",
                            duration: 2000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#f44336"
                        }).showToast();
                    }
                } else {
                    console.log(response.message);
                }
            }).fail(function(error) {
                console.error("삭제 중 오류:", error);
                alert("파일 삭제 중 문제가 발생했습니다.");
            });
        }
    };
    
    var imgObj = new Image();
    
    window.showImgWin = function(imgName) {
        imgObj.src = imgName;
        setTimeout(function() {
            createImgWin(imgObj);
        }, 100);
    };
    
    function createImgWin(imgObj) {
        if (!imgObj.complete) {
            setTimeout(function() {
                createImgWin(imgObj);
            }, 100);
            return;
        }
        window.open("", "imageWin", "width=" + imgObj.width + ",height=" + imgObj.height);
    }
    
    window.displayoutputlist = function() {
        if (typeof $ !== 'undefined') {
            $("#displayoutput").show();
            $("#displayoutput").load("./outputlist.php");
        }
    };
    
    /**
     * 부모창 업데이트 함수
     */
    function updateParentWindow(fieldName, value) {
        if (window.opener && !window.opener.closed) {
            try {
                console.log("부모창 업데이트:", fieldName, value);
                
                var parentDoc = window.opener.document;
                var updated = false;
                
                // 1. ID로 직접 찾기
                var parentElement = parentDoc.getElementById(fieldName);
                if (parentElement) {
                    updateElement(parentElement, value);
                    updated = true;
                    console.log("부모창 요소 업데이트 성공 (ID):", fieldName, value);
                }
                
                // 2. name 속성으로 찾기
                if (!updated) {
                    var elementsByName = parentDoc.getElementsByName(fieldName);
                    if (elementsByName && elementsByName.length > 0) {
                        updateElement(elementsByName[0], value);
                        updated = true;
                        console.log("부모창 요소 업데이트 성공 (name):", fieldName, value);
                    }
                }
                
                // 3. 특정 테이블 셀에서 찾기 (천장 작업 상태 표시용)
                if (!updated && fieldName.includes('_date')) {
                    var statusCell = findStatusCell(parentDoc, fieldName);
                    if (statusCell) {
                        statusCell.textContent = value || '';
                        updated = true;
                        console.log("부모창 상태 셀 업데이트 성공:", fieldName, value);
                    }
                }
                
                // 4. 결선완료 체크박스 처리
                if (fieldName === 'cabledone') {
                    var cabledoneCheckbox = parentDoc.querySelector('input[name="cabledone"], #cabledone');
                    if (cabledoneCheckbox) {
                        cabledoneCheckbox.checked = (value === '결선완료');
                        updated = true;
                        console.log("부모창 결선완료 체크박스 업데이트:", value);
                    }
                }
                
                // 업데이트가 성공한 경우 이벤트 발생
                if (updated) {
                    // 부모창의 페이지 번호 복원 함수 호출
                    if (typeof window.opener.restorePageNumber === 'function') {
                        window.opener.restorePageNumber();
                    }
                    
                    // 부모창의 테이블이나 리스트 새로고침 시도
                    if (typeof window.opener.refreshList === 'function') {
                        window.opener.refreshList();
                    }
                    
                    // 부모창의 상태 업데이트 함수 호출
                    if (typeof window.opener.updateStatus === 'function') {
                        window.opener.updateStatus(fieldName, value);
                    }
                } else {
                    console.log("부모창에서 요소를 찾을 수 없음:", fieldName);
                    
                    // 부모창 전체 새로고침 (요소를 찾을 수 없는 경우)
                    setTimeout(function() {
                        if (window.opener && !window.opener.closed) {
                            window.opener.location.reload();
                        }
                    }, 500);
                }
                
            } catch (e) {
                console.error("부모 창 업데이트 오류:", e);
                
                // 오류 발생 시 부모창 새로고침
                try {
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload();
                    }
                } catch (reloadError) {
                    console.error("부모창 새로고침 실패:", reloadError);
                }
            }
        } else {
            console.log("부모창이 없거나 닫혀있음");
        }
    }
    
    /**
     * 요소 업데이트 헬퍼 함수
     */
    function updateElement(element, value) {
        if (element.tagName === 'INPUT' && element.type === 'checkbox') {
            element.checked = (value === '결선완료' || value === '1' || value === true);
        } else {
            element.value = value || '';
        }
        
        // 변경 이벤트 발생시키기
        var changeEvent = new Event('change', { bubbles: true });
        element.dispatchEvent(changeEvent);
        
        // input 이벤트도 발생시키기 (더 많은 리스너가 감지할 수 있도록)
        var inputEvent = new Event('input', { bubbles: true });
        element.dispatchEvent(inputEvent);
    }
    
    /**
     * 상태 셀 찾기 헬퍼 함수
     */
    function findStatusCell(parentDoc, fieldName) {
        try {
            // 테이블에서 해당 필드명과 관련된 셀 찾기
            var tables = parentDoc.querySelectorAll('table');
            for (var i = 0; i < tables.length; i++) {
                var cells = tables[i].querySelectorAll('td, th');
                for (var j = 0; j < cells.length; j++) {
                    var cell = cells[j];
                    if (cell.textContent && cell.textContent.includes(fieldName.replace('_date', ''))) {
                        // 다음 셀 또는 관련 셀 찾기
                        var nextCell = cell.nextElementSibling;
                        if (nextCell) {
                            return nextCell;
                        }
                    }
                }
            }
            
            // 특정 패턴으로 찾기
            var statusTexts = {
                'eunsung_laser_date': '본 laser완료',
                'mainbending_date': '본 절곡',
                'mainwelding_date': '본 제관',
                'mainpainting_date': '본 도장',
                'mainassembly_date': '본 조립',
                'lclaser_date': 'L/C laser',
                'lcbending_date': 'L/C 절곡',
                'lcwelding_date': 'L/C 제관',
                'lcpainting_date': 'L/C 도장',
                'lcassembly_date': 'L/C 포장',
                'etclaser_date': '기타 레이저',
                'etcbending_date': '기타 절곡',
                'etcwelding_date': '기타 제관',
                'etcpainting_date': '기타 도장',
                'etcassembly_date': '기타 조립'
            };
            
            var searchText = statusTexts[fieldName];
            if (searchText) {
                var cells = parentDoc.querySelectorAll('td, th');
                for (var i = 0; i < cells.length; i++) {
                    if (cells[i].textContent && cells[i].textContent.includes(searchText)) {
                        var nextCell = cells[i].nextElementSibling;
                        if (nextCell) {
                            return nextCell;
                        }
                    }
                }
            }
            
            return null;
        } catch (e) {
            console.error("상태 셀 찾기 오류:", e);
            return null;
        }
    }
    
    /**
     * 날짜 데이터 저장 함수
     */
    window.saveData = function(anyone) {
        var id = "#" + anyone;
        var tmp = "./insert.php?num=" + num + "&data=" + anyone + "&from_view=1";
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        dd = (dd < 10) ? '0' + dd : dd;
        mm = (mm < 10) ? '0' + mm : mm;
        
        today = yyyy + '-' + mm + '-' + dd;
        
        console.log("데이터 저장:", anyone, "날짜:", today);
        
        $.ajax({
            url: tmp,
            type: 'GET',
            success: function(response) {
                console.log("저장 성공:", response);
                $(id).val(today);
                
                // 부모창 업데이트
                updateParentWindow(anyone, today);
            },
            error: function(xhr, status, error) {
                console.error("저장 실패:", error);
                alert("저장 중 오류가 발생했습니다.");
            }
        });
    };
    
    /**
     * 날짜 데이터 삭제 함수
     */
    window.dodatadel = function(anyone) {
        var id = "#" + anyone;
        var tmp = "./insert.php?num=" + num + "&deldata=" + anyone + "&from_view=1";
        
        console.log("데이터 삭제:", anyone);
        
        $.ajax({
            url: tmp,
            type: 'GET',
            success: function(response) {
                console.log("삭제 성공:", response);
                $(id).val('');
                
                // 부모창 업데이트 (삭제 시 빈 값으로 업데이트)
                updateParentWindow(anyone, '');
            },
            error: function(xhr, status, error) {
                console.error("삭제 실패:", error);
                alert("삭제 중 오류가 발생했습니다.");
            }
        });
    };
    
    /**
     * 모든 날짜 데이터 일괄 저장 함수
     */
    window.dodata_all = function() {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        dd = (dd < 10) ? '0' + dd : dd;
        mm = (mm < 10) ? '0' + mm : mm;
        
        today = yyyy + '-' + mm + '-' + dd;
        
        var arr = [
            'eunsung_laser_date', 'lclaser_date', 'mainbending_date', 'lcbending_date',
            'mainwelding_date', 'lcwelding_date', 'mainpainting_date', 'lcpainting_date',
            'mainassembly_date', 'lcassembly_date', 'etclaser_date', 'etcbending_date',
            'etcwelding_date', 'etcpainting_date', 'etcassembly_date'
        ];
        
        console.log("일괄 저장 시작:", arr.length + "개 항목");
        
        for (var i = 0; i < arr.length; i++) {
            var tmp = "./insert.php?num=" + num + "&data=" + arr[i];
            $.ajax({
                url: tmp,
                type: 'GET',
                async: false, // 순차 처리
                success: function(response) {
                    console.log("저장:", arr[i], response);
                }
            });
            var id = "#" + arr[i];
            $(id).val(today);
            
            // 각 항목마다 부모창 업데이트
            updateParentWindow(arr[i], today);
        }
        
        console.log("일괄 저장 완료");
        
        // 부모창에 일괄 저장 완료 알림
        if (window.opener && !window.opener.closed) {
            try {
                if (typeof window.opener.showNotification === 'function') {
                    window.opener.showNotification('일괄 저장 완료', '모든 작업 상태가 저장되었습니다.');
                }
            } catch (e) {
                console.error("부모창 알림 오류:", e);
            }
        }
    };
    
    /**
     * 날짜 데이터 삭제 함수 (일괄)
     */
    window.dodatadel_all = function(anyone) {
        var id = "#" + anyone;
        var tmp = "./insert.php?num=" + num + "&deldata=" + anyone + "&from_view=1";
        
        console.log("데이터 삭제:", anyone);
        
        $.ajax({
            url: tmp,
            type: 'GET',
            success: function(response) {
                console.log("삭제 성공:", response);
                $(id).val('');
                
                // 부모창 업데이트 (삭제 시 빈 값으로 업데이트)
                updateParentWindow(anyone, '');
            },
            error: function(xhr, status, error) {
                console.error("삭제 실패:", error);
            }
        });
    };
    
    window.navigateToLink = function(event, url) {
        if (event.target.tagName !== 'A') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '수주서로 이동하시겠어요?',
                    text: '세부 내역 페이지로 이동합니다.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '이동',
                    cancelButtonText: '취소'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        if (typeof customPopup !== 'undefined') {
                            customPopup(url, '세부 내역', 1900, 980);
                        } else {
                            window.location.href = url;
                        }
                    }
                });
            } else {
                if (typeof customPopup !== 'undefined') {
                    customPopup(url, '세부 내역', 1900, 980);
                } else {
                    window.location.href = url;
                }
            }
        }
    };
    
})();
</script>
</body>
</html>
