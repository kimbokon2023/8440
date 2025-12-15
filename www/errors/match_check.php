<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 및 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 8) {
    echo "<script>alert('권한이 없습니다.'); location.href='../index.php';</script>";
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 1. 매칭 테이블이 없으면 생성
try {
    $sql_create = "CREATE TABLE IF NOT EXISTS mirae8440.error_match (
        id INT NOT NULL AUTO_INCREMENT,
        steel_num INT NOT NULL COMMENT '원자재 출고 ID',
        error_num INT NOT NULL COMMENT '부적합 보고서 ID',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX idx_steel_num (steel_num),
        INDEX idx_error_num (error_num)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $pdo->exec($sql_create);
} catch (PDOException $e) {
    // 테이블 생성 오류 무시 (이미 존재할 수 있음)
}

// 2. 처리 로직 (매칭 등록/해제)
$mode = $_REQUEST['mode'] ?? '';



if ($mode === 'link' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $error_num = $_POST['error_num'] ?? 0;
    $steel_nums = $_POST['steel_nums'] ?? []; // 배열

    if ($error_num && !empty($steel_nums)) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO mirae8440.error_match (steel_num, error_num) VALUES (?, ?)");
            // 연결된 데이터 반환을 위한 준비
            // 1. Steel 정보 조회
            $in_clause = implode(',', array_fill(0, count($steel_nums), '?'));
            $sql_steel = "SELECT num, item, spec, bad_choice, outdate, outworkplace FROM mirae8440.steel WHERE num IN ($in_clause)";
            $stmt_steel = $pdo->prepare($sql_steel);
            $stmt_steel->execute($steel_nums);
            $steels = $stmt_steel->fetchAll(PDO::FETCH_ASSOC);
            $steel_map = [];
            foreach($steels as $s) {
                $steel_map[$s['num']] = $s;
            }

            // 2. Error 정보 조회 (Place)
            $stmt_error = $pdo->prepare("SELECT place FROM mirae8440.error WHERE num = ?");
            $stmt_error->execute([$error_num]);
            $error_info = $stmt_error->fetch(PDO::FETCH_ASSOC);
            $place = $error_info['place'] ?? '-';

            $response_data = [];
            $now = date('Y-m-d H:i:s');

            foreach ($steel_nums as $s_num) {
                $stmt->execute([$s_num, $error_num]);
                $match_id = $pdo->lastInsertId();
                
                if(isset($steel_map[$s_num])) {
                    $s = $steel_map[$s_num];
                    $response_data[] = [
                        'match_id' => $match_id,
                        'steel_num' => $s_num,
                        'error_num' => $error_num,
                        'place' => $place,
                        'item' => $s['item'],
                        'spec' => $s['spec'],
                        'bad_choice' => $s['bad_choice'],
                        'outdate' => $s['outdate'],
                        'outworkplace' => $s['outworkplace'],
                        'created_at' => $now
                    ];
                }
            }
            $pdo->commit();
            
            // 현재 연결된 개수 조회
            $stmtCount = $pdo->query("SELECT COUNT(*) FROM mirae8440.error_match WHERE error_num = $error_num");
            $newCount = $stmtCount->fetchColumn();

            echo json_encode(['success' => true, 'message' => '매칭이 완료되었습니다.', 'new_count' => $newCount, 'error_num' => $error_num, 'data' => $response_data]);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => '오류 발생: ' . $e->getMessage()]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
    exit;
}

if ($mode === 'exclude' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $steel_nums = $_POST['steel_nums'] ?? []; // 배열

    if (!empty($steel_nums)) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO mirae8440.error_match (steel_num, error_num) VALUES (?, 0)");
            
            // 제외된 데이터 반환을 위한 준비
            $in_clause = implode(',', array_fill(0, count($steel_nums), '?'));
            $sql_steel = "SELECT num, item, spec, bad_choice, outdate, outworkplace FROM mirae8440.steel WHERE num IN ($in_clause)";
            $stmt_steel = $pdo->prepare($sql_steel);
            $stmt_steel->execute($steel_nums);
            $steels = $stmt_steel->fetchAll(PDO::FETCH_ASSOC);
            $steel_map = [];
            foreach($steels as $s) {
                $steel_map[$s['num']] = $s;
            }

            $response_data = [];
            $now = date('Y-m-d H:i:s');

            foreach ($steel_nums as $s_num) {
                $stmt->execute([$s_num]);
                $match_id = $pdo->lastInsertId();
                
                if(isset($steel_map[$s_num])) {
                    $s = $steel_map[$s_num];
                    $response_data[] = [
                        'match_id' => $match_id,
                        'steel_num' => $s_num,
                        'item' => $s['item'],
                        'spec' => $s['spec'],
                        'bad_choice' => $s['bad_choice'],
                        'outdate' => $s['outdate'],
                        'outworkplace' => $s['outworkplace'],
                        'created_at' => $now
                    ];
                }
            }
            $pdo->commit();
            
            echo json_encode(['success' => true, 'message' => '선택한 항목이 제외되었습니다.', 'data' => $response_data]);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => '오류 발생: ' . $e->getMessage()]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
    exit;    
}

if ($mode === 'exclude_report' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $error_num = $_POST['error_num'] ?? 0;

    if ($error_num) {
        try {
            $pdo->beginTransaction();
            // steel_num = 0 indicates report exclusion
            $stmt = $pdo->prepare("INSERT INTO mirae8440.error_match (steel_num, error_num) VALUES (0, ?)");
            $stmt->execute([$error_num]);
            $match_id = $pdo->lastInsertId();
            
            // Return data for the table row
            $stmt_error = $pdo->prepare("SELECT place FROM mirae8440.error WHERE num = ?");
            $stmt_error->execute([$error_num]);
            $error_info = $stmt_error->fetch(PDO::FETCH_ASSOC);
            $place = $error_info['place'] ?? '-';
            
            $item_data = [
                'match_id' => $match_id,
                'error_num' => $error_num,
                'place' => $place,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => '보고서가 제외되었습니다.', 'data' => $item_data]);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => '오류 발생: ' . $e->getMessage()]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
    exit;
}

// 매칭 해제 로직
if ($mode === 'unlink' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $match_id = $_POST['match_id'] ?? 0;
    if ($match_id) {
        $pdo->query("DELETE FROM mirae8440.error_match WHERE id = $match_id");
        echo "<script>alert('매칭이 해제되었습니다.'); location.replace('match_check.php');</script>";
        exit;
    }
    }


// 연결된 자재 목록 조회 (JSON)
if ($mode === 'get_linked_items') {
    $error_num = $_GET['error_num'] ?? 0;
    if ($error_num) {
        $sql = "
            SELECT m.id as match_id, s.item, s.spec, s.bad_choice, s.outdate
            FROM mirae8440.error_match m
            JOIN mirae8440.steel s ON m.steel_num = s.num
            WHERE m.error_num = :error_num
            ORDER BY s.outdate DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':error_num' => $error_num]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    }
}

include getDocumentRoot() . '/load_header.php';
?>
<title>부적합 매칭 확인</title>
<style>
    .split-container {
        display: flex;
        flex-wrap: wrap;
        height: calc(100vh - 300px);
        overflow: hidden;
    }
    .panel {
        /* overflow-y: auto; REMOVED */
        border: 1px solid #dee2e6;
        height: 100%;
        /* padding: 10px; REMOVED */
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .panel-header {
        padding: 10px;
        background: #f8f9fa; /* Or inherit */
        border-bottom: 1px solid #dee2e6;
        flex-shrink: 0; 
    }
    .panel-body {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }
    .panel-left {
        flex: 1;
        min-width: 400px;
        border-right: none;
    }
    .panel-center {
        width: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-top: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
    }
    .panel-right {
        flex: 1;
        min-width: 400px;
        border-left: none;
    }
    .item-card {
        background: white;
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 8px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .item-card:hover {
        background-color: #e9ecef;
    }
    .item-card.selected {
        border-color: #0d6efd;
        background-color: #e7f1ff;
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
    }
    .group-header {
        font-weight: bold;
        padding: 8px;
        background: #e2e3e5;
        margin-top: 10px;
        margin-bottom: 5px;
        border-radius: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    /* 트리 구조 스타일 */
    .tree-item {
        margin-left: 20px;
        border-left: 2px solid #ccc;
        position: relative;
    }
    .tree-item::before {
        content: '';
        position: absolute;
        left: -2px;
        top: 15px;
        width: 10px;
        height: 2px;
        background: #ccc;
    }
    
    .report-card {
        background: white;
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 8px;
        border-radius: 4px;
        cursor: pointer;
    }
    .report-card.active {
        border-color: #198754;
        background-color: #d1e7dd;
    }
    
    .badge-site {
        background-color: #0056b3;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.8em;
    }
    
    /* 모바일 대응 */
    @media (max-width: 768px) {
        .split-container {
            height: auto;
            flex-direction: column;
        }
        .panel {
            height: 500px;
        }
        .panel-center {
            width: 100%;
            height: 60px;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
        }
    }

    /* 헤더 z-index 조정 (메뉴 드롭다운보다 낮게 설정) */
    .sticky-top {
        z-index: 999 !important;
    }
    
    /* Toast CSS */
    #toast-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2000;
        pointer-events: none;
    }
    .toast-message {
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 15px 30px;
        border-radius: 30px;
        margin-bottom: 10px;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        font-size: 1.1rem;
        text-align: center;
        white-space: nowrap;
    }
    .toast-message.show {
        opacity: 1;
    }
</style>

<body>
<?php require_once(includePath('myheader.php')); ?>

<div class="container py-2 mt-3">
    <div class="row mb-2">
        <div class="col">
            <h4 id="pageTitle"><i class="bi bi-node-plus-fill text-primary"></i> 부적합 매칭 확인 <span id="totalCountBadge" class="badge bg-secondary rounded-pill fs-6" style="vertical-align: middle;">Loading...</span></h4>
            <small class="text-muted">원자재 출고 불량 건과 부적합 보고서를 연결하여 관리합니다.</small>
        </div>
        <div class="col-auto">
             <button class="btn btn-outline-info btn-sm me-2" onclick="openHelpModal()"><i class="bi bi-question-circle"></i> 도움말</button>
             <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()"><i class="bi bi-arrow-clockwise"></i> 새로고침</button>
        </div>
    </div>

    <?php
    // 좌측 데이터: 매칭되지 않은 출고 불량 (which=2: 출고)
    // bad_choice 가 있거나 (빈값 아님)
    $sql_unmatched = "
        SELECT 
            s.num, s.outdate, s.item, s.spec, s.bad_choice, s.outworkplace, s.steelnum
        FROM mirae8440.steel s
        LEFT JOIN mirae8440.error_match m ON s.num = m.steel_num
        WHERE s.which = '2' 
          AND (s.bad_choice IS NOT NULL AND s.bad_choice != '')
          AND s.bad_choice NOT IN ('소재', '기타', '해당없음', '소장', '개발품', '업체', '운반중')
          AND s.outdate >= '2025-01-01'
          AND m.id IS NULL
        ORDER BY s.outdate DESC, s.outworkplace ASC
    ";
    
    $unmatched_items = [];
    $total_unmatched_count = 0;
    try {
        $stmt = $pdo->query($sql_unmatched);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $site = $row['outworkplace'] ?: '(현장명 없음)';
            $unmatched_items[$site][] = $row;
            $total_unmatched_count++;
        }
    } catch (PDOException $e) {
        echo "Error loading steel data: " . $e->getMessage();
    }

    // 우측 데이터: 부적합 보고서 목록
    $search_error = $_GET['search_error'] ?? '';
    $where_conditions = ["occur >= '2025-01-01'"];
    
    if ($search_error) {
        $where_conditions[] = "(place LIKE '%$search_error%' OR reporter LIKE '%$search_error%')";
    }
    
    // 이미 연결된 보고서는 제외
    $where_conditions[] = "NOT EXISTS (SELECT 1 FROM mirae8440.error_match m WHERE m.error_num = mirae8440.error.num)";
    
    $where_clause = "WHERE " . implode(' AND ', $where_conditions);
    $sql_error = "SELECT * FROM mirae8440.error $where_clause ORDER BY num DESC LIMIT 50";
    $error_reports = [];
    try {
        $stmt = $pdo->query($sql_error);
        $error_reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error loading reports: " . $e->getMessage();
    }
    
    // JS에 초기 카운트 전달을 위한 스크립트
    echo "<script>const initialTotalCount = {$total_unmatched_count};</script>";
    
    // 이미 매칭된 데이터 확인용
    // 트리 구조로 보여주기 위해
    ?>

    <div class="split-container">
        <!-- 좌측 패널: 미매칭 원자재 -->
        <!-- 좌측 패널: 미매칭 원자재 -->
        <div class="panel panel-left" id="leftPanel">
            <div class="panel-header bg-light">
                <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> 매칭 대기 중인 원자재 불량</h6>
            </div>
            
            <div class="panel-body">
                <?php if (empty($unmatched_items)): ?>
                    <div class="text-center text-muted p-5">매칭할 불량 내역이 없습니다.</div>
                <?php else: ?>
                    <?php foreach ($unmatched_items as $site => $items): ?>
                        <div class="group-wrapper">
                            <div class="group-header">
                                <span><i class="bi bi-building"></i> <?= htmlspecialchars($site) ?> <span class="badge bg-secondary"><?= count($items) ?>건</span></span>
                                <button class="btn btn-sm btn-outline-dark py-0" onclick="selectAllInGroup(this)">전체선택</button>
                            </div>
                            <?php foreach ($items as $item): ?>
                                <div class="item-card tree-item" id="item-card-<?= $item['num'] ?>" onclick="toggleSelection(this, <?= $item['num'] ?>)">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= htmlspecialchars($item['item']) ?> (<?= htmlspecialchars($item['spec']) ?>)</strong>
                                        <span class="text-danger fw-bold"><?= htmlspecialchars($item['bad_choice']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted small">
                                        <span><?= $item['outdate'] ?></span>
                                        <span>수량: <?= $item['steelnum'] ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- 중앙 패널: 액션 버튼 -->
        <div class="panel-center d-flex flex-column gap-2">
            <button class="btn btn-primary" id="btnLink" onclick="linkItems()" disabled title="선택한 항목을 우측 보고서에 연결">
                <i class="bi bi-arrow-right-circle-fill fs-2"></i>
                <div style="font-size: 0.7rem;">연결</div>
            </button>
            <button class="btn btn-danger" id="btnExclude" onclick="excludeItems()" disabled title="선택한 항목을 매칭 대상에서 제외">
                <i class="bi bi-x-circle-fill fs-2"></i>
                <div style="font-size: 0.7rem;">제외 X</div>
            </button>
        </div>

        <!-- 우측 패널: 부적합 보고서 -->
        <div class="panel panel-right">
            <div class="panel-header bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0"><i class="bi bi-file-text"></i> 부적합 보고서 선택</h6>
                    <!-- <button class="btn btn-sm btn-success" onclick="createNewReport()"><i class="bi bi-plus-lg"></i> 새 보고서 작성</button> -->
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="errorSearch" placeholder="현장명/보고자 검색" value="<?= htmlspecialchars($search_error) ?>">
                    <button class="btn btn-outline-secondary" onclick="searchReports()"><i class="bi bi-search"></i></button>
                </div>
            </div>

            <div class="panel-body">
                <div id="reportList" class="mt-2">
                    <?php foreach ($error_reports as $report): ?>
                        <div class="report-card" onclick="selectReport(this, <?= $report['num'] ?>)">
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-info text-dark">No. <?= $report['num'] ?></span>
                                <div>
                                    <button class="btn btn-xs btn-outline-secondary py-0 me-1" onclick="viewReport(<?= $report['num'] ?>, event)" style="font-size: 0.75rem;">자세히 보기</button>
                                    <button class="btn btn-xs btn-outline-danger py-0" onclick="excludeReport(<?= $report['num'] ?>, event)" style="font-size: 0.75rem;">제외</button>
                                </div>
                            </div>
                            <div class="fw-bold mt-1"><?= htmlspecialchars($report['place']) ?></div>
                            <div class="small text-muted text-truncate"><?= htmlspecialchars($report['content']) ?></div>
                            <div class="d-flex justify-content-between small text-muted mt-1">
                                <span><?= $report['reporter'] ?></span>
                                <span><?= $report['occur'] ?></span>
                            </div>
                            
                            <!-- 이미 연결된 항목 표시 -->
                            <?php
                                // 이 보고서에 연결된 자재 수 카운트
                                $sql_cnt = "SELECT COUNT(*) as cnt FROM mirae8440.error_match WHERE error_num = {$report['num']}";
                                $cnt = $pdo->query($sql_cnt)->fetchColumn();
                                if ($cnt > 0) {
                                    echo "<div id='report-link-info-{$report['num']}' class='mt-1 text-primary small pointer-cursor' style='cursor:pointer; text-decoration:underline;' onclick='showLinkedItems({$report['num']}, event)'><i class='bi bi-link-45deg'></i> 연결된 자재: {$cnt}건 (상세보기)</div>";
                                } else {
                                    echo "<div id='report-link-info-{$report['num']}' class='mt-1 text-primary small pointer-cursor' style='cursor:pointer; text-decoration:underline; display:none;' onclick='showLinkedItems({$report['num']}, event)'></div>";
                                }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 매칭 현황 (연결된 것들 확인 및 해제) -->
    <div class="card mt-3">
        <div class="card-header">
            <strong><i class="bi bi-link"></i> 최근 매칭 현황</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>보고서 ID</th>
                            <th>발생일</th>
                            <th>자재출고 현장명</th>
                            <th>부적합 현장명</th>
                            <th>자재정보</th>
                            <th>불량유형</th>
                            <th>비매칭 사유</th>
                            <th>매칭일시</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_matched = "
                            SELECT m.id as match_id, m.created_at, m.error_num,
                                e.place,
                                s.item, s.spec, s.bad_choice, s.outdate, s.outworkplace
                            FROM mirae8440.error_match m
                            LEFT JOIN mirae8440.steel s ON m.steel_num = s.num
                            LEFT JOIN mirae8440.error e ON m.error_num = e.num
                            ORDER BY m.created_at DESC LIMIT 100
                        ";
                        try {
                            $stmt = $pdo->query($sql_matched);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $isExcluded = ($row['error_num'] == 0);
                                $isReportExcluded = ($row['item'] === null); // If steel join is null, it's a report exclusion (assuming steel_num=0)
                                
                                $displayErrorNum = $isExcluded ? '-' : $row['error_num'];
                                $displayPlace = $isExcluded ? '-' : $row['place'];
                                
                                $remark = '정상';
                                if ($isExcluded) $remark = '<span class="badge bg-danger">보고서통과</span>';
                                if ($isReportExcluded) $remark = '<span class="badge bg-warning text-dark">자재불필요</span>';                                
                                
                                // 말줄임 처리 (15자)
                                $outworkplace_short = mb_strlen($row['outworkplace'] ?? '', 'utf-8') > 15 
                                    ? mb_substr($row['outworkplace'], 0, 15, 'utf-8') . '..' 
                                    : $row['outworkplace'];
                                    
                                $place_short = ($displayPlace !== '-' && mb_strlen($displayPlace, 'utf-8') > 15) 
                                    ? mb_substr($displayPlace, 0, 15, 'utf-8') . '..' 
                                    : $displayPlace;

                                echo "<tr>";
                                echo "<td>{$displayErrorNum}</td>";
                                echo "<td>{$row['outdate']}</td>";
                                echo "<td title='{$row['outworkplace']}'>{$outworkplace_short}</td>";
                                echo "<td title='{$displayPlace}'>{$place_short}</td>";
                                echo "<td>" . ($row['item'] ? "{$row['item']} ({$row['spec']})" : "-") . "</td>";
                                echo "<td class='text-danger'>" . ($row['bad_choice'] ?? "-") . "</td>";
                                echo "<td>{$remark}</td>";
                                echo "<td>{$row['created_at']}</td>";
                                echo "<td><form method='post' onsubmit='return confirm(\"매칭/제외를 해제하시겠습니까?\");' style='margin:0;'>
                                        <input type='hidden' name='mode' value='unlink'>
                                        <input type='hidden' name='match_id' value='{$row['match_id']}'>
                                        <button type='submit' class='btn btn-xs btn-outline-danger py-0'>해제</button>
                                      </form></td>";
                                echo "</tr>";
                            }
                        } catch (Exception $e) {
                             echo "<tr><td colspan='7'>데이터 로드 중 오류</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<form id="linkForm" method="post">
    <input type="hidden" name="mode" value="link">
    <input type="hidden" name="error_num" id="form_error_num">
    <!-- steel_nums[] will be appended dynamically -->
</form>

<script>
    let selectedSteelNums = new Set();
    let selectedErrorNum = null;
    let currentTotalCount = (typeof initialTotalCount !== 'undefined') ? initialTotalCount : 0;

    // 초기 카운트 표시
    document.addEventListener('DOMContentLoaded', function() {
        updateTotalCountDisplay();
    });

    function updateTotalCountDisplay() {
        const badge = document.getElementById('totalCountBadge');
        if(badge) badge.innerText = currentTotalCount + '건';
    }

    function toggleSelection(el, num) {
        if (selectedSteelNums.has(num)) {
            selectedSteelNums.delete(num);
            el.classList.remove('selected');
        } else {
            selectedSteelNums.add(num);
            el.classList.add('selected');
        }
        updateButtonState();
    }

    function selectAllInGroup(btn) {
        let wrapper = btn.closest('.group-wrapper');
        let items = wrapper.querySelectorAll('.item-card');
        items.forEach(el => {
            // Get onclick attribute to extract num
            // A bit hacky but works for simple prototype
            let onClick = el.getAttribute('onclick');
            let num = parseInt(onClick.match(/\d+/)[0]);
            
            if (!selectedSteelNums.has(num)) {
                selectedSteelNums.add(num);
                el.classList.add('selected');
            }
        });
        updateButtonState();
    }

    function selectReport(el, num) {
        // Deselect others
        document.querySelectorAll('.report-card').forEach(c => c.classList.remove('active'));
        
        if (selectedErrorNum === num) {
            selectedErrorNum = null; // Toggle off
        } else {
            selectedErrorNum = num;
            el.classList.add('active');
        }
        updateButtonState();
    }

    function updateButtonState() {
        const btnLink = document.getElementById('btnLink');
        const btnExclude = document.getElementById('btnExclude');
        
        // 연결 버튼: 자재 선택 O AND 보고서 선택 O
        btnLink.disabled = !(selectedSteelNums.size > 0 && selectedErrorNum !== null);
        
        // 제외 버튼: 자재 선택 O (보고서 선택 여부 상관없음)
        btnExclude.disabled = !(selectedSteelNums.size > 0);
    }

    function linkItems() {
        if (!selectedErrorNum || selectedSteelNums.size === 0) return;
        
        let formData = new FormData();
        formData.append('mode', 'link');
        formData.append('error_num', selectedErrorNum);
        selectedSteelNums.forEach(num => formData.append('steel_nums[]', num));

        fetch('match_check.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                
                // 1. 좌측 목록에서 선택된 항목 제거
                selectedSteelNums.forEach(num => {
                    let el = document.getElementById('item-card-' + num);
                    if (el) el.remove();
                    currentTotalCount--; // 카운트 감소
                });
                updateTotalCountDisplay(); // 카운트 UI 갱신
                
                // 2. 우측 보고서 카드의 개수 업데이트
                let infoDiv = document.getElementById('report-link-info-' + data.error_num);
                if (infoDiv) {
                    infoDiv.innerHTML = `<i class='bi bi-link-45deg'></i> 연결된 자재: ${data.new_count}건 (상세보기)`;
                    infoDiv.style.display = 'block';
                }

                // 3. 하단 테이블에 행 추가 (동적)
                if (data.data && data.data.length > 0) {
                     const tbody = document.querySelector('table tbody');
                     data.data.forEach(item => {
                        const tr = document.createElement('tr');
                        // 말줄임 로직 (JS)
                        let outworkplace = item.outworkplace || '';
                        let outworkplace_short = outworkplace.length > 15 ? outworkplace.substring(0, 15) + '..' : outworkplace;
                        
                        let place = item.place || '';
                        let place_short = place.length > 15 ? place.substring(0, 15) + '..' : place;
                         
                        tr.innerHTML = `
                            <td>${item.error_num}</td>
                            <td>${item.outdate}</td>
                            <td title='${outworkplace}'>${outworkplace_short}</td>
                            <td title='${place}'>${place_short}</td>
                            <td>${item.item} (${item.spec})</td>
                            <td class='text-danger'>${item.bad_choice}</td>
                            <td></td>
                            <td>${item.created_at}</td>
                            <td><form method='post' onsubmit='return confirm(\"매칭/제외를 해제하시겠습니까?\");' style='margin:0;'>
                                    <input type='hidden' name='mode' value='unlink'>
                                    <input type='hidden' name='match_id' value='${item.match_id}'>
                                    <button type='submit' class='btn btn-xs btn-outline-danger py-0'>해제</button>
                                  </form></td>
                        `;
                        // 맨 앞에 추가
                        tbody.insertBefore(tr, tbody.firstChild);
                     });
                }
                
                // 4. 선택 상태 초기화
                selectedSteelNums.clear();
                selectedErrorNum = null; 
                document.querySelectorAll('.report-card.active').forEach(el => el.classList.remove('active'));
                updateButtonState();
                
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('요청 처리 중 오류가 발생했습니다.');
        });
    }
    function excludeItems() {
        if (selectedSteelNums.size === 0) return;
        // if (!confirm('선택한 ' + selectedSteelNums.size + '건의 항목을 매칭 대상에서 제외하시겠습니까?')) return; // 사용자 요청으로 alert/confirm 제거

        let formData = new FormData();
        formData.append('mode', 'exclude');
        selectedSteelNums.forEach(num => formData.append('steel_nums[]', num));

        fetch('match_check.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                
                // 1. 좌측 목록에서 선택된 항목 제거
                selectedSteelNums.forEach(num => {
                    let el = document.getElementById('item-card-' + num);
                    if (el) el.remove();
                    currentTotalCount--;
                });
                updateTotalCountDisplay();
                
                // 2. 하단 테이블에 행 추가 (동적)
                if (data.data && data.data.length > 0) {
                     const tbody = document.querySelector('table tbody');
                     data.data.forEach(item => {
                        const tr = document.createElement('tr');
                        // 말줄임 로직 (JS)
                        let outworkplace = item.outworkplace || '';
                        let outworkplace_short = outworkplace.length > 15 ? outworkplace.substring(0, 15) + '..' : outworkplace;
                         
                        tr.innerHTML = `
                            <td>-</td>
                            <td>${item.outdate}</td>
                            <td title='${outworkplace}'>${outworkplace_short}</td>
                            <td title='-'>-</td>
                            <td>${item.item} (${item.spec})</td>
                            <td class='text-danger'>${item.bad_choice}</td>
                            <td><span class="badge bg-danger">제외</span></td>
                            <td>${item.created_at}</td>
                            <td><form method='post' onsubmit='return confirm(\"매칭/제외를 해제하시겠습니까?\");' style='margin:0;'>
                                    <input type='hidden' name='mode' value='unlink'>
                                    <input type='hidden' name='match_id' value='${item.match_id}'>
                                    <button type='submit' class='btn btn-xs btn-outline-danger py-0'>해제</button>
                                  </form></td>
                        `;
                        // 맨 앞에 추가
                        tbody.insertBefore(tr, tbody.firstChild);
                        // 10개 넘어가면 뒤에꺼 삭제? (일단 유지)
                     });
                }

                
                // 3. 선택 상태 초기화
                selectedSteelNums.clear();
                updateButtonState();
                
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('요청 처리 중 오류가 발생했습니다.');
        });
    }

    function showToast(message) {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
        }
        
        let toast = document.createElement('div');
        toast.className = 'toast-message';
        toast.innerText = message;
        container.appendChild(toast);
        
        // Trigger reflow
        void toast.offsetWidth; 
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 500);
        }, 2000);
    }
    
    function searchReports() {
        let keyword = document.getElementById('errorSearch').value;
        location.href = 'match_check.php?search_error=' + encodeURIComponent(keyword);
    }
    
    // 엔터 검색
    document.getElementById('errorSearch').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            searchReports();
        }
    });

    function openHelpModal() {
        var myModal = new bootstrap.Modal(document.getElementById('helpModal'), {
            keyboard: true
        });
        myModal.show();
    }

    function showLinkedItems(errorNum, event) {
        event.stopPropagation(); // 카드 선택 방지
        
        // 데이터 조회
        fetch('match_check.php?mode=get_linked_items&error_num=' + errorNum)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('linkedItemsBody');
                tbody.innerHTML = '';
                
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">연결된 자재가 없습니다.</td></tr>';
                } else {
                    data.forEach(item => {
                        let html = `
                            <tr>
                                <td>${item.item}</td>
                                <td>${item.spec}</td>
                                <td class="text-danger">${item.bad_choice}</td>
                                <td>${item.outdate}</td>
                                <td>
                                    <button class="btn btn-xs btn-outline-danger py-0" onclick="unlinkItem(${item.match_id})">해제</button>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += html;
                    });
                }
                
                var myModal = new bootstrap.Modal(document.getElementById('linkedItemsModal'), {
                    keyboard: true
                });
                myModal.show();
            })
            .catch(err => alert('데이터 로드 중 오류가 발생했습니다.'));
    }

    function unlinkItem(matchId) {
        if (!confirm('정말 이 자재와의 연결을 해제하시겠습니까?')) return;
        
        // 동적 폼 생성 후 제출
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = 'match_check.php';
        
        let modeInput = document.createElement('input');
        modeInput.type = 'hidden';
        modeInput.name = 'mode';
        modeInput.value = 'unlink';
        form.appendChild(modeInput);
        
        let idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'match_id';
        idInput.value = matchId;
        form.appendChild(idInput);
        
        document.body.appendChild(form);
        form.submit();
    }

    function viewReport(num, event) {
        event.stopPropagation(); // 카드 선택 방지
        let url = 'write_form.php?num=' + num;
        let name = 'DefectiveReportView';
        let option = 'width=1200,height=800,top=100,left=100,scrollbars=yes';
        window.open(url, name, option);
    }

    function excludeReport(num, event) {
        event.stopPropagation();
        if (!confirm('이 부적합 보고서를 매칭 대상에서 제외하시겠습니까?')) return;
        
        let formData = new FormData();
        formData.append('mode', 'exclude_report');
        formData.append('error_num', num);
        
        fetch('match_check.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                
                // 1. Remove from right panel
                // Find all cards with this error number (though usually one)
                // We don't have IDs on report cards, but we can reload or find parent
                // Ideally add ID to report card. For now, reload is safest but slow. 
                // Let's reload to be safe or just hide it if we can find it.
                // Actually selectReport pass 'this'. We don't have reference here.
                // Let's reload for simplicity or try to find elements.
                location.reload(); 
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('오류가 발생했습니다.');
        });
    }
</script>

<!-- 도움말 모달 -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white py-3">
                <h5 class="modal-title fs-5" id="helpModalLabel">
                    <i class="bi bi-info-circle"></i> 부적합 매칭 확인 도움말
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto; font-size: 1.15rem;">
                <div class="p-2">
                    
                    <h6 class="fw-bold text-primary mb-2"><i class="bi bi-check2-square"></i> 매칭 방법</h6>
                    <p class="text-muted mb-4">
                        1. <strong>좌측 패널</strong>에서 매칭할 원자재 불량 항목을 선택합니다 (다중 선택 가능).<br>
                        2. <strong>우측 패널</strong>에서 연결할 부적합 보고서를 선택합니다 (하나만 선택 가능).<br>
                        3. 중앙의 <strong>[연결]</strong> 버튼을 클릭하여 매칭을 완료합니다.<br>
                        * 현장별 전체선택 버튼을 활용하면 편리합니다.
                    </p>

                    <h6 class="fw-bold text-success mb-2"><i class="bi bi-funnel"></i> 자동 제외 항목</h6>
                    <p class="text-muted mb-4">
                        다음의 불량 유형은 부적합 보고서 작성 대상이 아니므로 <strong>매칭 대기 목록에서 자동으로 제외</strong>됩니다.<br>
                        <span class="text-danger fw-bold">(제외 항목: '소재', '기타', '해당없음', '소장', '개발품', '업체', '운반중')</span>
                    </p>

                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-link"></i> 매칭 해제</h6>
                    <p class="text-muted mb-4">
                        하단의 <strong>[최근 매칭 현황]</strong> 테이블에서 잘못 연결된 항목의 <strong>[해제]</strong> 버튼을 눌러 매칭을 취소할 수 있습니다.
                    </p>

                    <h6 class="fw-bold text-info mb-2"><i class="bi bi-eye"></i> 보고서 및 매칭 제외</h6>
                    <p class="text-muted mb-4">
                        - <strong>이미 연결된 자재</strong>가 있는 부적합 보고서는 우측 리스트에서 <strong>자동으로 제외</strong>됩니다.<br> (단, 하단의 최근 매칭 현황에서는 계속 확인할 수 있습니다.)<br>
                        - 보고서 리스트의 <span class="badge bg-outline-secondary text-dark border">자세히 보기</span> 버튼을 누르면 해당 부적합 보고서의 상세 내용을 팝업으로 확인할 수 있습니다.
                    </p>
                    
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>



<!-- 연결된 자재 목록 모달 -->
<div class="modal fade" id="linkedItemsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-link-45deg"></i> 연결된 자재 목록</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>품명</th>
                            <th>규격</th>
                            <th>불량유형</th>
                            <th>출고일</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody id="linkedItemsBody">
                        <!-- Ajax Load -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
