<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 및 권한 체크
$level = $_SESSION["level"] ?? 999;
if (!isset($_SESSION["level"]) || $level > 8) {
    echo "<script>alert('권한이 없습니다.'); window.close();</script>";
    exit;
}

$mode = $_REQUEST["mode"] ?? 'insert';
$num = $_REQUEST["num"] ?? '';
$type = $_REQUEST["type"] ?? 'main'; // main: 원자재, aux: 부자재

// DB 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 초기값 설정
$outdate = date("Y-m-d");
$indate = '';
$requestdate = '';
$outworkplace = '';
$steel_item = '';
$spec = '';
$steelnum = '';
$company = '';
$supplier = '';
$request_comment = '';
$which = '1'; // 1: 요청, 2: 발주, 3: 완료
$model = '';
$payment = '법인카드';
$inventory = '';
$eworks_item = ($type === 'aux') ? '부자재구매' : '원자재구매';

// 수정 모드일 경우 데이터 로드
if ($mode === 'modify' && $num) {
    try {
        $sql = "SELECT * FROM mirae8440.eworks WHERE num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            include '_row.php';
            $type = ($eworks_item === '부자재구매') ? 'aux' : 'main';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// 철판 종류/규격/업체 로드 (원자재용)
include "../load_company.php"; // $suply_company_arr
// 철판 종류
$steelitem_arr = [];
try {
    $stmh = $pdo->query("SELECT item FROM mirae8440.steelitem");
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $steelitem_arr[] = trim($row['item']);
    }
} catch (Exception $e) {}
$steelitem_arr[] = '304 Mirror 1.2T';
sort($steelitem_arr);

// 규격
$spec_arr = [];
try {
    $stmh = $pdo->query("SELECT spec FROM mirae8440.steelspec");
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $spec_arr[] = trim($row['spec']);
    }
} catch (Exception $e) {}
sort($spec_arr);

// --- 재고량 계산 로직 제거 (AJAX로 대체) ---
// get_stock_data.php 사용

?>
<?php include getDocumentRoot() . '/load_header.php'; ?>
<title>통합 발주 등록/수정</title>
<style>
    .form-label { font-weight: bold; }
    .hidden { display: none; }
    
    /* 모바일 최적화 */
    @media (max-width: 768px) {
        .container { padding: 5px; }
        .card-body { padding: 10px; }
        input, select, textarea { font-size: 16px !important; } /* iOS 줌 방지 */
    }
</style>

<body>
<?php include getDocumentRoot() . '/common/modal.php'; ?>

<div class="container mt-3">
    <div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $mode === 'insert' ? '신규 등록' : '수정' ?></h5>
            <button type="button" class="btn-close btn-close-white" onclick="window.close()"></button>
        </div>
        <div class="card-body">
            <form id="orderForm" enctype="multipart/form-data">
                <input type="hidden" name="mode" value="<?= $mode ?>">
                <input type="hidden" name="num" value="<?= $num ?>">
                <input type="hidden" name="type" id="typeInput" value="<?= $type ?>">
                
                <!-- 타입 선택 (신규 등록시에만 가능) -->
                <div class="mb-3 text-center">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="type_select" id="type_main" value="main" <?= $type === 'main' ? 'checked' : '' ?> <?= $mode === 'modify' ? 'disabled' : '' ?> onchange="toggleType('main')">
                        <label class="btn btn-outline-primary" for="type_main">원자재 (Main)</label>

                        <input type="radio" class="btn-check" name="type_select" id="type_aux" value="aux" <?= $type === 'aux' ? 'checked' : '' ?> <?= $mode === 'modify' ? 'disabled' : '' ?> onchange="toggleType('aux')">
                        <label class="btn btn-outline-success" for="type_aux">부자재 (Aux)</label>
                    </div>
                </div>

                <hr>

                <!-- 공통 필드 -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="form-label">진행상태</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="which" id="which1" value="1" <?= $which == '1' ? 'checked' : '' ?>>
                                <label class="form-check-label text-primary" for="which1">요청</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="which" id="which2" value="2" <?= $which == '2' ? 'checked' : '' ?>>
                                <label class="form-check-label text-danger" for="which2">발주</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="which" id="which3" value="3" <?= $which == '3' ? 'checked' : '' ?>>
                                <label class="form-check-label text-secondary" for="which3">완료</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">접수일</label>
                        <input type="date" class="form-control" name="outdate" value="<?= $outdate ?>">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="form-label">납기일(필요)</label>
                        <input type="date" class="form-control" name="requestdate" value="<?= $requestdate ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">완료일</label>
                        <input type="date" class="form-control" name="indate" id="indate" value="<?= $indate ?>">
                    </div>
                </div>

                <!-- 원자재 전용 필드 -->
                <div id="field_main" class="<?= $type === 'main' ? '' : 'hidden' ?>">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="form-label">현장명</label>
                            <input type="text" class="form-control" name="outworkplace_main" value="<?= $type === 'main' ? $outworkplace : '' ?>" placeholder="현장명 입력">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label">모델</label>
                            <input type="text" class="form-control" name="model" value="<?= $model ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">사급업체</label>
                            <div class="input-group">
                                <select class="form-select" name="company" id="company">
                                    <option value="">선택하세요</option>
                                    <?php foreach ($suply_company_arr as $comp): ?>
                                        <option value="<?= $comp ?>" <?= $company == $comp ? 'selected' : '' ?>><?= $comp ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-secondary" onclick="popupCenter('../standard_outsourcing/list.php', '납품회사 등록', 600, 700)"><i class="bi bi-gear"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label">철판 종류</label>
                            <div class="input-group">
                                <select class="form-select" name="steel_item" id="steel_item">
                                    <option value="">선택하세요</option>
                                    <?php foreach ($steelitem_arr as $item): ?>
                                        <option value="<?= $item ?>" <?= $steel_item == $item ? 'selected' : '' ?>><?= $item ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-secondary" onclick="popupCenter('../standard_material/list.php?item=steel_item', '원자재 종류 관리', 600, 700)"><i class="bi bi-gear"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">재고이관</label>
                            <input type="text" class="form-control text-danger" name="inventory" value="<?= $inventory ?>" readonly placeholder="자동처리">
                        </div>
                    </div>
                </div>

                <!-- 부자재 전용 필드 -->
                <div id="field_aux" class="<?= $type === 'aux' ? '' : 'hidden' ?>">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="form-label">물품명</label>
                            <input type="text" class="form-control" name="outworkplace_aux" value="<?= $type === 'aux' ? $outworkplace : '' ?>" placeholder="구입 물품명 입력">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="form-label">결제방식</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment" value="법인카드" <?= $payment == '법인카드' ? 'checked' : '' ?>>
                                    <label class="form-check-label">법인카드</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment" value="세금계산서" <?= $payment == '세금계산서' ? 'checked' : '' ?>>
                                    <label class="form-check-label">세금계산서</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 공통 하단 필드 -->
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="form-label">규격</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="spec" id="spec" value="<?= $spec ?>" list="specList">
                            <datalist id="specList">
                                <?php foreach ($spec_arr as $s): ?>
                                    <option value="<?= $s ?>">
                                <?php endforeach; ?>
                            </datalist>
                            <button type="button" class="btn btn-outline-secondary spec-extra" onclick="popupCenter('../standard/list.php', '규격(spec) 관리', 600, 700)"><i class="bi bi-gear"></i></button>
                            <span class="input-group-text spec-extra">재고</span>
                            <input type="text" class="form-control spec-extra" id="stock" value="0" readonly style="max-width: 80px;">
                            <button type="button" class="btn btn-outline-secondary spec-extra" onclick="searchStock()"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">수량</label>
                        <input type="text" class="form-control" name="steelnum" id="steelnum" value="<?= $steelnum ?>" required>
                    </div>
                </div>

                <!-- 사이즈 버튼 (원자재용) -->
                <div class="row mb-2 <?= $type === 'main' ? '' : 'hidden' ?>" id="size_buttons">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-success btn-sm mb-1" onclick="size42150_click();searchStock();">1.2t 4'*2150</button>
                        <button type="button" class="btn btn-outline-success btn-sm mb-1" onclick="size4_8_click();searchStock();">1.2t 4'*8'</button>
                        <button type="button" class="btn btn-outline-success btn-sm mb-1" onclick="size4_2600_click();searchStock();">1.2t 4'*2600</button>
                        <button type="button" class="btn btn-outline-success btn-sm mb-1" onclick="size4_2700_click();searchStock();">1.2t 4'*2700</button>
                        <button type="button" class="btn btn-outline-success btn-sm mb-1" onclick="size4_3000_click();searchStock();">1.2t 4'*3000</button>
                        <button type="button" class="btn btn-outline-success btn-sm mb-1" onclick="size4_3200_click();searchStock();">1.2t 4'*3200</button>
                        <button type="button" class="btn btn-outline-success btn-sm mb-1" onclick="size4_4000_click();searchStock();">1.2t 4'*4000</button>
                    </div>
                </div>

                <!-- 유틸리티 버튼 (원자재용) -->
                <div class="row mb-2 <?= $type === 'main' ? '' : 'hidden' ?>" id="utility_buttons">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="searchSimilarStock()">
                            <i class="bi bi-search"></i> 유사 재고
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="openCalculator()">
                            종류/규격 선택 후 톤 계산기 (<span class="badge bg-primary">샤집</span> 사용)
                        </button>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="form-label">공급처</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="supplier" id="supplier" value="<?= $supplier ?>">
                            <button type="button" class="btn btn-outline-secondary" onclick="popupCenter('../standard_supplier/list.php', '공급처 등록', 600, 700)"><i class="bi bi-gear"></i></button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">비고</label>
                    <textarea class="form-control" name="request_comment" rows="3"><?= $request_comment ?></textarea>
                </div>

                <!-- 파일 업로드 (부자재용이지만 공통으로 열어둠, 필요시 숨김 처리 가능) -->
                <div class="mb-3">
                    <label class="form-label">파일 첨부</label>
                    <input type="file" class="form-control" name="upfile[]" multiple>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                    <button type="button" class="btn btn-primary" onclick="saveData(1)">
                        <i class="bi bi-cart-plus"></i> 구매카트 담기
                    </button>
                    <button type="button" class="btn btn-dark" onclick="saveData(0)">저장</button>
                    <button type="button" class="btn btn-secondary" onclick="window.close()">닫기</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function toggleType(type) {
        document.getElementById('typeInput').value = type;
        var specExtras = document.querySelectorAll('.spec-extra');
        
        if (type === 'main') {
            document.getElementById('field_main').classList.remove('hidden');
            document.getElementById('field_aux').classList.add('hidden');
            document.getElementById('size_buttons').classList.remove('hidden');
            document.getElementById('utility_buttons').classList.remove('hidden');
            specExtras.forEach(el => el.classList.remove('hidden'));
        } else {
            document.getElementById('field_main').classList.add('hidden');
            document.getElementById('field_aux').classList.remove('hidden');
            document.getElementById('size_buttons').classList.add('hidden');
            document.getElementById('utility_buttons').classList.add('hidden');
            specExtras.forEach(el => el.classList.add('hidden'));
        }
    }

    function saveData(isCart = 0) {
        var form = document.getElementById('orderForm');
        var formData = new FormData(form);
        
        // 구매카트 여부 추가
        formData.append('cart', isCart);

        // 유효성 검사
        var type = formData.get('type');
        var outworkplace = type === 'main' ? formData.get('outworkplace_main') : formData.get('outworkplace_aux');
        var steelnum = formData.get('steelnum');

        if (!outworkplace || !steelnum) {
            Swal.fire('경고', '현장명(물품명)과 수량은 필수입니다.', 'warning');
            return;
        }

        // AJAX 전송
        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: formData,
            },
            error: function(xhr, status, error) {
                console.error(error);
                Swal.fire('오류', '서버 통신 중 오류가 발생했습니다.', 'error');
            }
        });
    }
    
    // 완료 체크 시 날짜 자동 입력
    document.querySelectorAll('input[name="which"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === '3') {
                document.getElementById('indate').value = new Date().toISOString().split('T')[0];
            }
        });
    });

    // --- 편의 기능 JS (request/write_form.php에서 포팅) ---
    function searchStock(){
       var item = $('#steel_item').val();
       var spec = $('#spec').val();
       var company = $('#company').val();

       if(!item || !spec) {
           Swal.fire('알림', '종류와 규격을 입력해주세요.', 'warning');
           return;
       }

       $.ajax({
           url: 'get_stock_data.php',
           type: 'GET',
           data: {
               mode: 'exact',
               item: item,
               spec: spec,
               company: company
           },
           dataType: 'json',
           success: function(response) {
               if(response.error) {
                   Swal.fire('오류', response.error, 'error');
                   return;
               }
               
               $('#stock').val(response.stock);
               
               if(response.stock > 0) {
                   // Swal.fire('재고 확인', '재고: ' + response.stock, 'info');
               } else {
                   Swal.fire('재고 확인', '자재 없음', 'warning');
               }
           },
           error: function() {
               Swal.fire('오류', '서버 통신 중 오류가 발생했습니다.', 'error');
           }
       });
    }

    function searchSimilarStock(){
       var item = $('#steel_item').val();

       if(!item) {
           Swal.fire('알림', '종류를 입력해주세요.', 'warning');
           return;
       }

       $.ajax({
           url: 'get_stock_data.php',
           type: 'GET',
           data: {
               mode: 'similar',
               item: item
           },
           dataType: 'json',
           success: function(response) {
               if(response.error) {
                   Swal.fire('오류', response.error, 'error');
                   return;
               }

               let tmp = '';
               if(response.list && response.list.length > 0) {
                   response.list.forEach(function(row) {
                       tmp += row.text + '     수량 ' + row.stock + "<br>";
                   });
               } else {
                   tmp = '자재 없음';
               }

               Swal.fire({
                   title: '유사 재고',
                   html: '<div style="text-align:left; max-height:300px; overflow-y:auto;">' + tmp + '</div>',
                   icon: 'info'
               });
           },
           error: function() {
               Swal.fire('오류', '서버 통신 중 오류가 발생했습니다.', 'error');
           }
       });
    }

    function openCalculator() {
        var a = $('#steel_item').val();
        var b = $('#spec').val();
        var href = '../request/calTon.php?steel_item=' + a + '&spec=' + b; 
        popupCenter(href, '톤/수량계산기', 1000, 650);
    }
    
    // 사이즈 버튼 핸들러 (common.js 의존성 제거 및 직접 구현)
    function size42150_click() { $("#spec").val("1.2*1219*2150"); }
    function size4_8_click() { $("#spec").val("1.2*1219*2438"); }
    function size4_2600_click() { $("#spec").val("1.2*1219*2600"); }
    function size4_2700_click() { $("#spec").val("1.2*1219*2700"); }
    function size4_3000_click() { $("#spec").val("1.2*1219*3000"); }
    function size4_3200_click() { $("#spec").val("1.2*1219*3200"); }
    function size4_4000_click() { $("#spec").val("1.2*1219*4000"); }
</script>
</body>
</html>
