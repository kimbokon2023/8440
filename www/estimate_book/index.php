<?php
require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;

// 권한 체크
if ($level > 5) {
    echo "<script>alert('권한이 없습니다.'); location.href='/';</script>";
    exit;
}

include getDocumentRoot() . '/load_header.php';
?>
<title>주소록 관리</title>
<!-- Tabulator CSS and JS -->
<link href="https://unpkg.com/tabulator-tables@6.2.1/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@6.2.1/dist/js/tabulator.min.js"></script>

<style>
    body { background-color: #f8f9fa; }
    .container-fluid { padding: 20px; }
    .page-header { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
    .page-title { font-size: 1.5rem; font-weight: 700; color: #333; margin: 0; }
    .action-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .search-box { max-width: 300px; }
    #address-table { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>

<body>
    <?php require_once(includePath('myheader.php')); ?>   

    <div class="container-fluid">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">주소록 관리</h1>
                <p class="text-muted mb-0">거래처 및 연락처 정보를 관리합니다.</p>
            </div>
            <div>
                <button class="btn btn-success me-2" disabled onclick="window.open('import_csv.php', '_blank')">
                    <i class="fas fa-file-import"></i> CSV 가져오기
                </button>
                <button class="btn btn-primary" onclick="openAddressModal(null)">
                    <i class="fas fa-plus"></i> 신규 등록
                </button>
            </div>
        </div>

        <div class="action-bar">
            <div class="search-box position-relative">
                <input type="text" id="searchInput" class="form-control" placeholder="검색어 입력...">
                <button id="clearSearchBtn" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-secondary" style="display:none; text-decoration:none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div>
                <button class="btn btn-outline-secondary" onclick="downloadCSV()">
                    <i class="fas fa-download"></i> 엑셀 다운로드
                </button>
            </div>
        </div>

        <div id="address-table"></div>
    </div>

    <!-- 주소록 모달 -->
    <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">주소록 정보</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="addressFrame" style="width:100%; height:500px; border:none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        var table;

        document.addEventListener('DOMContentLoaded', function() {
            table = new Tabulator("#address-table", {
                layout: "fitColumns",
                height: "70vh",
                ajaxURL: "search_customers.php",
                ajaxConfig: "POST",
                ajaxContentType: "json",
                pagination: true,
                paginationMode: "remote",
                filterMode: "remote",
                sortMode: "remote",
                paginationSize: 50,
                columns: [
                    {title: "ID", field: "id", width: 60, visible: false},
                    {title: "표시 이름", field: "display_name", width: 150},
                    {title: "회사", field: "company_name", width: 150},
                    {title: "부서", field: "department", width: 100},
                    {title: "휴대폰", field: "mobile_phone", width: 130},
                    {title: "근무처 전화", field: "work_phone", width: 130},
                    {title: "이메일", field: "email", width: 200},
                    {title: "메모", field: "memo", formatter: "textarea"},
                    {title: "관리", formatter: function(cell, formatterParams, onRendered){
                        return '<button class="btn btn-sm btn-outline-primary me-1" onclick="event.stopPropagation(); openAddressModal(' + cell.getData().id + ')">수정</button>' +
                               '<button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); deleteAddress(' + cell.getData().id + ')">삭제</button>';
                    }, width: 120, hozAlign: "center", headerSort: false}
                ],
                rowClick: function(e, row){
                    openAddressModal(row.getData().id);
                },
            });

            // 검색 기능
            var searchInput = document.getElementById("searchInput");
            var clearBtn = document.getElementById("clearSearchBtn");

            searchInput.addEventListener("keyup", function(e) {
                console.log("Search input:", e.target.value);
                table.setFilter("search", "like", e.target.value);
                toggleClearBtn();
            });

            clearBtn.addEventListener("click", function() {
                searchInput.value = "";
                table.clearFilter();
                toggleClearBtn();
                searchInput.focus();
            });

            function toggleClearBtn() {
                if (searchInput.value.length > 0) {
                    clearBtn.style.display = "block";
                } else {
                    clearBtn.style.display = "none";
                }
            }
        });

        function openAddressModal(id) {
            var url = 'write.php?iframe=1';
            if (id) {
                url += '&id=' + id;
            }
            document.getElementById('addressFrame').src = url;
            var modal = new bootstrap.Modal(document.getElementById('addressModal'));
            modal.show();
        }

        function closeAddressModal() {
            var modalEl = document.getElementById('addressModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        }

        function reloadTable() {
            table.replaceData();
        }

        function deleteAddress(id) {
            if (confirm('정말 삭제하시겠습니까?')) {
                fetch('delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({id: id})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('삭제되었습니다.');
                        reloadTable();
                    } else {
                        alert(data.message || '삭제 실패');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function downloadCSV() {
            table.download("csv", "address_book.csv");
        }
    </script>
    <?php include getDocumentRoot() . '/load_footer.php'; ?>
</body>
