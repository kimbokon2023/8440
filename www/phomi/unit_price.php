<?php
// =================================================================================
// 1. unit_price.php - 데이터 목록 조회 페이지
// =================================================================================
?>
<?php require_once(includePath('session.php'));

// --- 페이지 설정 ---
$title_message = '포미스톤 단가표';
$tablename = "phomi_unitprice";
?>
<?php include getDocumentRoot() . '/load_header.php' ?>

<title> <?= $title_message ?> </title>
<style>
  /* 테이블 행에 마우스를 올렸을 때 커서를 포인터로 변경 */
  .table-hover tbody tr:hover {
    cursor: pointer;
  }
  /* DataTables 검색창 오른쪽 정렬 */
  .dataTables_filter {
    float: right;
  }
  
  /* 토글 컬럼 스타일 */
  .toggle-column {
    transition: all 0.3s ease;
    width: 80px !important;
    max-width: 80px !important;
    min-width: 80px !important;
  }
  
  /* 토글 컬럼이 숨겨진 상태 */
  .toggle-column.hidden {
    display: none !important;
  }
  
  /* 테이블 컬럼 너비 조정 */
  #unitPriceTable th.toggle-column,
  #unitPriceTable td.toggle-column {
    width: 80px !important;
    max-width: 80px !important;
    min-width: 80px !important;
  }
</style>
</head>

<body>
  <?php require_once(includePath('myheader.php')); ?>

  <?php
  // // --- 권한 확인 ---
  // if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
  //   echo "<script>alert('접근 권한이 없습니다.'); location.href='" . $_SESSION["WebSite"] . "login/login_form.php';</script>";
  //   exit;
  // }

  require_once(includePath('lib/mydb.php'));
  $pdo = db_connect();

  // --- 검색 처리 ---
  $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

      try {
      // 검색어가 있을 경우와 없을 경우의 SQL문 분기
      $sql = "SELECT * FROM mirae8440." . $tablename;
      if (!empty($search)) {
        $sql .= " WHERE prodcode LIKE ? OR texture_eng LIKE ? OR texture_kor LIKE ? OR design_eng LIKE ? OR design_kor LIKE ? OR type LIKE ? OR size LIKE ? OR thickness LIKE ? OR area LIKE ? OR dist_price_per_m2 LIKE ? OR dist_price_total LIKE ? OR retail_price_per_m2 LIKE ? OR retail_price_total LIKE ?";
        $search_param = "%" . $search . "%";
      }
      $sql .= " ORDER BY CAST(SUBSTRING(prodcode, 1, 1) AS CHAR) ASC, CAST(SUBSTRING(prodcode, 2) AS UNSIGNED) ASC";
      
      $stmh = $pdo->prepare($sql);
      
      // 검색어가 있을 경우에만 파라미터 바인딩
      if (!empty($search)) {
        $stmh->bindValue(1, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(2, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(3, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(4, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(5, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(6, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(7, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(8, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(9, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(10, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(11, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(12, $search_param, PDO::PARAM_STR);
        $stmh->bindValue(13, $search_param, PDO::PARAM_STR);
      }

    $stmh->execute();
    $total_row = $stmh->rowCount();
  } catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
    exit;
  }
  ?>

  <form name="board_form" id="board_form" method="post" action="unit_price.php">
    <div class="container-fluid justify-content-center">
      <div class="card mt-2 mb-4">
        <div class="card-body">
          <div class="d-flex mt-3 mb-2 justify-content-center">
            <h5> <?= $title_message ?> </h5>
            <button type="button" class="btn btn-dark btn-sm mx-3" onclick='location.reload();' title="새로고침"> <i class="bi bi-arrow-clockwise"></i> </button>
            <button type="button" class="btn btn-danger btn-sm mx-1" onclick="location.href='list.php';" title="수주 관리">
						<i class="bi bi-file-earmark-text"></i> 수주
					</button>
					<button type="button" class="btn btn-primary btn-sm mx-1" onclick="location.href='list_estimate.php';" title="견적 관리">
						<i class="bi bi-file-earmark-text"></i> 견적
					</button>
					<button type="button" class="btn btn-success btn-sm mx-1" onclick="location.href='list_outorder.php';" title="출고요청서">
						<i class="bi bi-box-seam"></i> 출고요청서
					</button>
					<button type="button" class="btn btn-warning btn-sm mx-1" onclick="window.open('intro_goods.php', 'introGoodsPopup', 'width=1200,height=800,scrollbars=yes,resizable=yes');" title="색상 정보 테이블">
						<i class="bi bi-palette-fill"></i> 색상정보
					</button>
					<!-- <button type="button" class="btn btn-secondary btn-sm mx-1" onclick="location.href='unit_price.php';" title="단가표">
						<i class="bi bi-currency-dollar"></i> 단가표
					</button>	             -->
          </div>

          <div class="d-flex mt-4 mb-1 justify-content-center align-items-center">
            <?php if (isset($_SESSION['level']) && $_SESSION['level'] == '1'): ?>
              <div class="form-check form-switch me-3">
                <input class="form-check-input fs-6" type="checkbox" id="showHeadOfficePrice" name="showHeadOfficePrice">
                <label class="form-check-label fs-6" for="showHeadOfficePrice">공급가 보기</label>
              </div>
            <?php endif; ?>
            <div class="input-group me-2" style="width: 250px;">
                <input type="text" id="search" class="form-control" name="search" autocomplete="off" value="<?= $search ?>" placeholder="품목코드, 질감, 디자인 등">
                <button id="searchBtn" type="submit" class="btn btn-dark btn-sm"><i class="bi bi-search"></i></button>
            </div>
            <button type="button" class="btn btn-primary btn-sm mx-2" id="writeBtn"> <i class="bi bi-pencil"></i> 신규 등록 </button>
          </div>

          <div class="row d-flex mt-3">
            <div class="col">
              <table class="table table-hover table-sm table-bordered" id="unitPriceTable">
                <thead class="table-primary text-center">
                  <tr>
                    <th>품목코드</th>
                    <th>질감(영문)</th>
                    <th>질감(한글)</th>
                    <th>디자인(영문)</th>
                    <th>디자인(한글)</th>
                    <th>이미지</th>
                    <th>분류</th>
                    <th>사이즈</th>
                    <th>두께(mm)</th>
                    <th>헤베(㎡)</th>
                    <?php if (isset($_SESSION['level']) && $_SESSION['level'] == '1'): ?>
                      <th class="toggle-column hidden">공급가(㎡)</th>
                    <?php endif; ?>
                    <th>대리점가(㎡)</th>
                    <th>유통가(㎡)</th>
                    <th>유통가(원장)</th>
                    <th>소비자가(㎡)</th>
                    <th>소비자가(원장)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                    <tr onclick="redirectToEdit('<?= $row['num'] ?>', '<?= $tablename ?>')">
                      <td class="text-center"><?= $row['prodcode'] ?></td>
                      <td><?= $row['texture_eng'] ?></td>
                      <td><?= $row['texture_kor'] ?></td>
                      <td><?= $row['design_eng'] ?></td>
                      <td><?= $row['design_kor'] ?></td>
                      <td class="text-center">
                        <?php if (!empty($row['image_url'])): ?>
                          <a href="javascript:void(0);" onclick="openPagePopup('<?= $row['image_url'] ?>'); event.stopPropagation();" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-image"></i>
                          </a>
                        <?php else: ?>
                          <span class="text-muted">-</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center"><?= $row['type'] ?></td>
                      <td class="text-center"><?= $row['size'] ?></td>
                      <td class="text-center"><?= $row['thickness'] ?></td>
                      <td class="text-center"><?= $row['area'] ?></td>
                      <?php if (isset($_SESSION['level']) && $_SESSION['level'] == '1'): ?>
                        <td class="text-end toggle-column hidden"><?= number_format($row['price_per_m2']) ?></td>
                      <?php endif; ?>
                      <td class="text-end"><?= number_format($row['price_agent']) ?></td>
                      <td class="text-end"><?= number_format($row['dist_price_per_m2']) ?></td>
                      <td class="text-end"><?= number_format($row['dist_price_total']) ?></td>
                      <td class="text-end"><?= number_format($row['retail_price_per_m2']) ?></td>
                      <td class="text-end"><?= number_format($row['retail_price_total']) ?></td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div><!--card-body-->
      </div><!--card -->
    </div><!--container-->
  </form>

  <script>
    $(document).ready(function() {
      // DataTables 초기화
      var table = $('#unitPriceTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": false, // 상단의 커스텀 검색 사용
        "pageLength": 120,
        "lengthMenu": [25, 50, 100, 120, 200, 500, 1000],
        "language": {
          "lengthMenu": "_MENU_ 개씩 보기",
          "zeroRecords": "표시할 데이터가 없습니다.",
          "info": "페이지 _PAGE_ / _PAGES_ (총 <?= $total_row ?> 개)",
          "infoEmpty": "",
          "infoFiltered": "",
          "paginate": {
            "first": "처음",
            "last": "마지막",
            "next": "다음",
            "previous": "이전"
          }
        },
        "order": [
          [0, 'asc']
        ], // 품목코드 기준 오름차순 정렬
        "columnDefs": [
          {
            "targets": 0, // 품목코드 열
            "type": "string",
            "render": function(data, type, row) {
              if (type === 'sort') {
                // 정렬용 데이터: 첫 글자 + 숫자 부분을 0으로 패딩
                const match = data.match(/^([A-Z])(\d+)$/);
                if (match) {
                  const letter = match[1];
                  const number = match[2].padStart(3, '0');
                  return letter + number;
                }
                return data;
              }
              return data; // 표시용 데이터는 그대로
            }
          }
        ]
      });

      // 페이지 로드 시 저장된 체크박스 상태 복원
      var savedState = localStorage.getItem('showHeadOfficePrice');
      console.log('저장된 상태:', savedState); // 디버깅용
      
      if (savedState === 'true' || savedState === true) {
        $('#showHeadOfficePrice').prop('checked', true);
        // 약간의 지연 후 공급가 열 표시 (DOM이 완전히 로드된 후)
        setTimeout(function() {
          showSupplyPriceColumn();
        }, 100);
      } else {
        // 체크되지 않은 상태로 초기화
        $('#showHeadOfficePrice').prop('checked', false);
        localStorage.setItem('showHeadOfficePrice', 'false');
        // 초기 상태에서는 공급가 열이 이미 숨겨져 있으므로 별도 처리 불필요
      }

      // 공급가 보기 체크박스 이벤트 처리
      $('#showHeadOfficePrice').change(function() {
        var isChecked = $(this).is(':checked');
        console.log('체크박스 상태 변경:', isChecked); // 디버깅용
        
        // 상태를 localStorage에 저장
        localStorage.setItem('showHeadOfficePrice', isChecked.toString());
        
        if (isChecked) {
          // 공급가 열 표시
          showSupplyPriceColumn();
        } else {
          // 공급가 열 숨김
          hideSupplyPriceColumn();
        }
      });

      // 공급가 열 표시 함수
      function showSupplyPriceColumn() {
        console.log('공급가 열 표시 함수 실행');
        
        // CSS 클래스 제거로 공급가 열을 표시
        $('.toggle-column').removeClass('hidden');
      }

      // 공급가 열 숨김 함수
      function hideSupplyPriceColumn() {
        console.log('공급가 열 숨김 함수 실행');
        
        // CSS 클래스 추가로 공급가 열을 숨김
        $('.toggle-column').addClass('hidden');
      }

      // 숫자 포맷팅 함수
      function number_format(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }
    });

    // 페이지 팝업 열기
    function openPagePopup(pageUrl) {
      window.open(pageUrl, 'pagePopup', 'width=1000,height=700,scrollbars=yes,resizable=yes');
    }

    // 수정 폼으로 이동 (팝업)
    function redirectToEdit(num, tablename) {
      var url = "unit_price_form.php?mode=edit&num=" + num + "&tablename=" + tablename;
      // window.open(url, '단가 정보 수정', 'width=1000,height=800,scrollbars=yes');
      customPopup(url, '단가 정보 수정', 1000, 900);
    }

    // 신규 등록 폼으로 이동 (팝업)
    $("#writeBtn").click(function() {
      var tablename = '<?= $tablename ?>';
      var url = "unit_price_form.php?tablename=" + tablename;
      // window.open(url, '단가 정보 신규 등록', 'width=1000,height=800,scrollbars=yes');
      customPopup(url, '단가 정보 신규 등록', 1000, 900);
    });

    // 서버에 작업 기록
    $(document).ready(function() {
      saveLogData('포미스톤 단가표 조회');
    });
  </script>
</body>
</html>