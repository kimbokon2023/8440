<?php
// 로컬/서버 환경 설정
$is_local = $_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
$base_url = $is_local ? 'http://localhost/mirae8440/www' : 'http://8440.co.kr';

require_once __DIR__ . '/../common/functions.php';
// =================================================================================
// 2. unit_price_form.php - 데이터 입력/수정/삭제 폼 페이지
// =================================================================================
?>
<?php require_once(includePath('session.php'));
include getDocumentRoot() . '/load_header.php';

// --- 기본 변수 설정 ---
$tablename = isset($_REQUEST["tablename"]) ? $_REQUEST["tablename"] : "";
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : "new";
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";

$page_title = ($mode == "edit") ? "단가 정보 수정" : (($mode == "copy") ? "단가 정보 복사 등록" : "단가 정보 신규 등록");
$row = []; // 데이터 배열 초기화

if ($mode == "edit" && !empty($num)) {
  require_once(includePath('lib/mydb.php'));
  $pdo = db_connect();
  try {
    $sql = "SELECT * FROM mirae8440.{$tablename} WHERE num = :num";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':num', $num, PDO::PARAM_INT);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    print "오류: " . $e->getMessage();
  }
}

// echo "<pre>";
// print_r($row);
// echo "</pre>";
?>

<title><?= $page_title ?></title>
<style>
  /* 모바일 환경 최적화 */
  @media (max-width: 768px) {
    /* body와 html 오버플로우 방지 */
    html, body {
      overflow-x: hidden !important;
      max-width: 100% !important;
      width: 100% !important;
      box-sizing: border-box !important;
      margin: 0 !important;
      padding: 0 !important;
    }
    
    * {
      max-width: 100% !important;
      box-sizing: border-box !important;
    }
    
    /* 컨테이너 최적화 */
    .container {
      padding: 0.5rem !important;
      max-width: 100% !important;
      width: 100% !important;
      margin: 0 auto !important;
      overflow-x: hidden !important;
    }
    
    /* 카드 영역 최적화 */
    .card {
      margin: 0.5rem auto !important;
      width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
      overflow-x: hidden !important;
    }
    
    .card-header {
      padding: 0.75rem !important;
    }
    
    .card-header h4 {
      font-size: 1.25rem !important;
      word-wrap: break-word !important;
      overflow-wrap: break-word !important;
      margin-bottom: 0 !important;
    }
    
    /* 카드 헤더 버튼 그룹 최적화 */
    .card-header .d-flex.justify-content-between {
      flex-direction: column !important;
      align-items: stretch !important;
      gap: 0.75rem !important;
    }
    
    .card-header .header-buttons {
      display: flex !important;
      flex-direction: row !important;
      flex-wrap: nowrap !important;
      gap: 0.5rem !important;
      justify-content: flex-end !important;
      width: 100% !important;
      align-items: center !important;
    }
    
    .card-header .header-buttons button {
      flex: 0 0 auto !important;
      width: auto !important;
      min-width: fit-content !important;
      max-width: none !important;
      padding: 0.5rem 0.75rem !important;
      font-size: 0.9rem !important;
      white-space: nowrap !important;
      color: #fff !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      text-align: center !important;
      visibility: visible !important;
      opacity: 1 !important;
      line-height: 1.5 !important;
      height: auto !important;
      background-color: #000 !important;
      border-color: #000 !important;
      overflow: visible !important;
      text-overflow: clip !important;
    }
    
    /* 삭제 버튼만 빨간색 */
    .card-header .header-buttons button.btn-danger,
    .card-header .header-buttons button#deleteBtnHeader,
    .card-header .header-buttons button#deleteBtn {
      background-color: #dc3545 !important;
      border-color: #dc3545 !important;
      color: #fff !important;
    }
    
    /* 나머지 버튼은 검정색 */
    .card-header .header-buttons button:not(.btn-danger):not(#deleteBtnHeader):not(#deleteBtn) {
      background-color: #000 !important;
      border-color: #000 !important;
      color: #fff !important;
    }
    
    .card-header .header-buttons button i {
      margin-right: 0.25rem !important;
      display: inline-block !important;
      visibility: visible !important;
      opacity: 1 !important;
    }
    
    /* 아이콘이 없는 버튼도 적절한 패딩 유지 */
    .card-header .header-buttons button {
      padding-left: 0.75rem !important;
      padding-right: 0.75rem !important;
    }
    
    /* 작은 화면에서 헤더 버튼 조정 */
    @media (max-width: 480px) {
      .card-header .d-flex.justify-content-between {
        flex-direction: column !important;
        align-items: stretch !important;
      }
      
      .card-header .header-buttons {
        justify-content: center !important;
        flex-wrap: wrap !important;
        width: 100% !important;
        margin-top: 0.5rem !important;
        gap: 0.5rem !important;
      }
      
      .card-header .header-buttons button {
        flex: 0 0 auto !important;
        width: auto !important;
        min-width: fit-content !important;
        max-width: none !important;
        font-size: 0.85rem !important;
        padding: 0.5rem 0.75rem !important;
      }
    }
    
    .card-body {
      padding: 0.75rem !important;
      overflow-x: hidden !important;
    }
    
    /* 폼 입력 필드 최적화 */
    .form-label {
      font-size: 0.9rem !important;
      margin-bottom: 0.25rem !important;
      word-wrap: break-word !important;
      overflow-wrap: break-word !important;
    }
    
    .form-control,
    input[type="text"],
    input[type="number"],
    textarea,
    select {
      width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
      padding: 0.5rem !important;
      font-size: 1rem !important;
    }
    
    /* 행 레이아웃 최적화 */
    .row {
      margin-left: 0 !important;
      margin-right: 0 !important;
    }
    
    .col-md-6,
    .col-md-4 {
      width: 100% !important;
      max-width: 100% !important;
      flex: 0 0 100% !important;
      padding-left: 0.5rem !important;
      padding-right: 0.5rem !important;
      margin-bottom: 0.75rem !important;
    }
    
    /* 버튼 그룹 최적화 - 수정 모드일 때 한 줄에 배치 */
    .d-flex.justify-content-between {
      flex-direction: row !important;
      align-items: center !important;
      flex-wrap: nowrap !important;
      gap: 0.5rem !important;
      margin-top: 1rem !important;
      justify-content: space-between !important;
    }
    
    .d-flex.justify-content-between > div {
      display: flex !important;
      flex-direction: row !important;
      flex-wrap: nowrap !important;
      gap: 0.5rem !important;
      flex: 0 0 auto !important;
    }
    
    .d-flex.justify-content-between > div:first-child {
      order: 0 !important;
    }
    
    .d-flex.justify-content-between > div:last-child {
      order: 0 !important;
    }
    
    .d-flex.justify-content-between button {
      flex: 0 0 auto !important;
      width: auto !important;
      min-width: fit-content !important;
      max-width: none !important;
      padding: 0.5rem 0.75rem !important;
      font-size: 0.85rem !important;
      white-space: nowrap !important;
      background-color: #000 !important;
      border-color: #000 !important;
      color: #fff !important;
      overflow: visible !important;
      text-overflow: clip !important;
    }
    
    /* 삭제 버튼만 빨간색 (본문) */
    .d-flex.justify-content-between button.btn-danger,
    .d-flex.justify-content-between button#deleteBtn {
      background-color: #dc3545 !important;
      border-color: #dc3545 !important;
      color: #fff !important;
    }
    
    /* 나머지 버튼은 검정색 (본문) */
    .d-flex.justify-content-between button:not(.btn-danger):not(#deleteBtn) {
      background-color: #000 !important;
      border-color: #000 !important;
      color: #fff !important;
    }
    
    /* 저장 버튼 강조 */
    .d-flex.justify-content-between button.btn-primary {
      flex: 0 0 auto !important;
      min-width: auto !important;
    }
    
    /* 모바일에서 버튼이 너무 많으면 줄바꿈 허용 */
    @media (max-width: 480px) {
      .d-flex.justify-content-between {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
      }
      
      .d-flex.justify-content-between button {
        flex: 0 0 auto !important;
        width: auto !important;
        min-width: fit-content !important;
        max-width: none !important;
        padding: 0.5rem 0.75rem !important;
      }
    }
    
    /* 텍스트 줄바꿈 */
    span, text, label, p, div {
      word-wrap: break-word !important;
      overflow-wrap: break-word !important;
    }
    
    /* hr 최적화 */
    hr {
      margin: 1rem 0 !important;
    }
    
    /* 모달/팝업 최적화 */
    body {
      padding: 0 !important;
    }
  }
  
  /* PC 환경에서도 모바일 스타일 일부 적용 */
  @media (min-width: 769px) {
    .card {
      max-width: 100% !important;
    }
    
    .form-control {
      max-width: 100% !important;
    }
    
    /* PC에서 헤더 버튼 가로 배치 */
    .card-header .d-flex.justify-content-between {
      flex-direction: row !important;
      align-items: center !important;
    }
    
    .card-header .header-buttons {
      flex-wrap: nowrap !important;
      width: auto !important;
    }
    
    /* PC에서도 버튼 색상 적용 */
    .card-header .header-buttons button:not(.btn-danger):not(#deleteBtnHeader):not(#deleteBtn) {
      background-color: #000 !important;
      border-color: #000 !important;
      color: #fff !important;
    }
    
    .card-header .header-buttons button.btn-danger,
    .card-header .header-buttons button#deleteBtnHeader,
    .card-header .header-buttons button#deleteBtn {
      background-color: #dc3545 !important;
      border-color: #dc3545 !important;
      color: #fff !important;
    }
    
    .d-flex.justify-content-between button:not(.btn-danger):not(#deleteBtn) {
      background-color: #000 !important;
      border-color: #000 !important;
      color: #fff !important;
    }
    
    .d-flex.justify-content-between button.btn-danger,
    .d-flex.justify-content-between button#deleteBtn {
      background-color: #dc3545 !important;
      border-color: #dc3545 !important;
      color: #fff !important;
    }
  }
</style>
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><?= $page_title ?></h4>
                <div class="header-buttons">
                    <?php if ($mode == 'edit') : ?>
                        <button type="button" class="btn btn-info btn-sm me-2" id="copyBtnHeader">
                            <i class="bi bi-files"></i> 복사
                        </button>
                        <button type="button" class="btn btn-danger btn-sm me-2" id="deleteBtnHeader">삭제</button>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary btn-sm me-2" form="unit_price_form" id="saveBtnHeader">저장</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close()" id="closeBtnHeader">닫기</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form name="unit_price_form" id="unit_price_form" method="post" action="unit_price_process.php" onsubmit="return validateForm()">
                <input type="hidden" name="mode" value="<?= ($mode == 'copy') ? 'new' : $mode ?>">
                <input type="hidden" name="num" value="<?= ($mode == 'copy') ? '' : $num ?>">
                <input type="hidden" name="tablename" value="<?= $tablename ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="prodcode" class="form-label">품목코드</label>
                        <input type="text" class="form-control" id="prodcode" name="prodcode" value="<?= $row['prodcode'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">분류</label>
                        <input type="text" class="form-control" id="type" name="type" value="<?= $row['type'] ?? '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="texture_kor" class="form-label">질감명 (한글)</label>
                        <input type="text" class="form-control" id="texture_kor" name="texture_kor" value="<?= $row['texture_kor'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="texture_eng" class="form-label">질감명 (영문)</label>
                        <input type="text" class="form-control" id="texture_eng" name="texture_eng" value="<?= $row['texture_eng'] ?? '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="design_kor" class="form-label">디자인명 (한글)</label>
                        <input type="text" class="form-control" id="design_kor" name="design_kor" value="<?= $row['design_kor'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="design_eng" class="form-label">디자인명 (영문)</label>
                        <input type="text" class="form-control" id="design_eng" name="design_eng" value="<?= $row['design_eng'] ?? '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="size" class="form-label">원장 사이즈</label>
                        <input type="text" class="form-control" id="size" name="size" value="<?= $row['size'] ?? '' ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="thickness" class="form-label">두께 (mm)</label>
                        <input type="text" class="form-control" id="thickness" name="thickness" value="<?= $row['thickness'] ?? '' ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="area" class="form-label">헤베 (㎡)</label>
                        <input type="number" step="0.01" class="form-control" id="area" name="area" value="<?= $row['area'] ?? '' ?>">
                    </div>
                </div>

                <hr>

                <?php if (isset($level) && $level <= 5) : ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price_per_m2" class="form-label">총판 단가 (㎡)</label>
                        <input type="number" class="form-control" id="price_per_m2" name="price_per_m2" value="<?= $row['price_per_m2'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price_agent" class="form-label">대리점 단가 (㎡)</label>
                        <input type="number" class="form-control" id="price_agent" name="price_agent" value="<?= $row['price_agent'] ?? '' ?>">
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dist_price_per_m2" class="form-label">일반 유통 단가 (㎡)</label>
                        <input type="number" class="form-control" id="dist_price_per_m2" name="dist_price_per_m2" value="<?= $row['dist_price_per_m2'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="dist_price_total" class="form-label">일반 유통 단가 (원장)</label>
                        <input type="number" class="form-control" id="dist_price_total" name="dist_price_total" value="<?= $row['dist_price_total'] ?? '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="retail_price_per_m2" class="form-label">소비자 가격 (㎡)</label>
                        <input type="number" class="form-control" id="retail_price_per_m2" name="retail_price_per_m2" value="<?= $row['retail_price_per_m2'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="retail_price_total" class="form-label">소비자 가격 (원장)</label>
                        <input type="number" class="form-control" id="retail_price_total" name="retail_price_total" value="<?= $row['retail_price_total'] ?? '' ?>">
                    </div>
                </div>
                
                <hr>

                <div class="mb-3">
                    <label for="image_url" class="form-label">이미지 URL</label>
                    <input type="text" class="form-control" id="image_url" name="image_url" value="<?= $row['image_url'] ?? '' ?>">
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <?php if ($mode == 'edit') : ?>
                            <button type="button" class="btn btn-info me-2" id="copyBtn">
                                <i class="bi bi-files"></i> 복사
                            </button>
                            <button type="button" class="btn btn-danger" id="deleteBtn">삭제</button>
                        <?php endif; ?>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">저장</button>
                        <button type="button" class="btn btn-secondary" onclick="window.close()">닫기</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateForm() {
    if (document.getElementById('prodcode').value.trim() === '') {
        alert('품목코드를 입력해주세요.');
        return false;
    }
    return true;
}

$(document).ready(function() {
    // 복사 모드일 때 세션 스토리지에서 데이터 로드
    <?php if ($mode == 'copy') : ?>
    loadCopyData();
    <?php endif; ?>
    
    // 삭제 버튼 이벤트 (본문과 헤더 모두)
    $('#deleteBtn, #deleteBtnHeader').click(function() {
        if (confirm('정말로 이 항목을 삭제하시겠습니까?')) {
            var form = document.unit_price_form;
            form.mode.value = 'delete';
            form.submit();
        }
    });
    
    // 복사 버튼 이벤트 (본문과 헤더 모두)
    $('#copyBtn, #copyBtnHeader').click(function() {
        if (confirm('이 데이터를 복사하여 새 항목을 생성하시겠습니까?')) {
            copyData();
        }
    });
    
    // 저장 버튼 이벤트 (헤더)
    $('#saveBtnHeader').click(function(e) {
        e.preventDefault();
        if (validateForm()) {
            document.unit_price_form.submit();
        }
    });
});

function loadCopyData() {
    var copyData = JSON.parse(sessionStorage.getItem('copyData') || '{}');
    if (Object.keys(copyData).length > 0) {
        // 품목코드는 복사하지 않음 (중복 방지)
        $('#prodcode').val('').focus();
        $('#type').val(copyData.type || '');
        $('#texture_kor').val(copyData.texture_kor || '');
        $('#texture_eng').val(copyData.texture_eng || '');
        $('#design_kor').val(copyData.design_kor || '');
        $('#design_eng').val(copyData.design_eng || '');
        $('#size').val(copyData.size || '');
        $('#thickness').val(copyData.thickness || '');
        $('#area').val(copyData.area || '');
        <?php if (isset($level) && $level <= 5) : ?>
        $('#price_per_m2').val(copyData.price_per_m2 || '');
        $('#price_agent').val(copyData.price_agent || '');
        <?php endif; ?>
        $('#dist_price_per_m2').val(copyData.dist_price_per_m2 || '');
        $('#dist_price_total').val(copyData.dist_price_total || '');
        $('#retail_price_per_m2').val(copyData.retail_price_per_m2 || '');
        $('#retail_price_total').val(copyData.retail_price_total || '');
        $('#image_url').val(copyData.image_url || '');
        
        // 세션 스토리지에서 데이터 삭제
        sessionStorage.removeItem('copyData');
    }
}

function copyData() {
    // 현재 폼의 데이터를 수집
    var formData = {
        tablename: '<?= $tablename ?>',
        prodcode: $('#prodcode').val(),
        type: $('#type').val(),
        texture_kor: $('#texture_kor').val(),
        texture_eng: $('#texture_eng').val(),
        design_kor: $('#design_kor').val(),
        design_eng: $('#design_eng').val(),
        size: $('#size').val(),
        thickness: $('#thickness').val(),
        area: $('#area').val(),
        dist_price_per_m2: $('#dist_price_per_m2').val(),
        dist_price_total: $('#dist_price_total').val(),
        retail_price_per_m2: $('#retail_price_per_m2').val(),
        retail_price_total: $('#retail_price_total').val(),
        image_url: $('#image_url').val()
    };
    
    <?php if (isset($level) && $level <= 5) : ?>
    // 권한이 있는 경우에만 추가 단가 정보 포함
    formData.price_per_m2 = $('#price_per_m2').val();
    formData.price_agent = $('#price_agent').val();
    <?php endif; ?>
    
    // URL 파라미터 생성
    var params = new URLSearchParams();
    params.append('mode', 'copy');
    params.append('tablename', formData.tablename);
    
    // 데이터를 세션 스토리지에 저장
    sessionStorage.setItem('copyData', JSON.stringify(formData));
    
    // 새 창으로 복사 모드 폼 열기
    window.open(
        'unit_price_form.php?' + params.toString(),
        '_blank',
        'width=1000,height=800,scrollbars=yes,resizable=yes'
    );
}
</script>
</body>
</html>
