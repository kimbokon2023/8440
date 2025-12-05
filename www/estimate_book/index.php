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

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
:root {
    /* 라이트 모드 색상 - 그레이 계열 */
    --bg-primary: #ffffff;
    --bg-secondary: #ffffff;
    --bg-card: #f8f9fa;
    --bg-gradient-start: #6c757d;
    --bg-gradient-end: #495057;
    --text-primary: #333333;
    --text-secondary: #666666;
    --text-white: #ffffff;
    --border-color: #e0e0e0;
    --border-light: #f0f0f0;
    --shadow: rgba(0,0,0,0.08);
    --shadow-hover: rgba(108, 117, 125, 0.2);
    --hover-bg: #f8f9fa;
}

[data-theme="dark"] {
    /* 다크 모드 색상 */
    --bg-primary: #1a1a2e;
    --bg-secondary: #16213e;
    --bg-card: #1e2a3a;
    --bg-gradient-start: #495057;
    --bg-gradient-end: #343a40;
    --text-primary: #e2e8f0;
    --text-secondary: #cbd5e0;
    --text-white: #ffffff;
    --border-color: #4a5568;
    --border-light: #2d3748;
    --shadow: rgba(0,0,0,0.3);
    --shadow-hover: rgba(108, 117, 125, 0.5);
    --hover-bg: #2d3748;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--bg-primary);
    color: var(--text-primary);
    transition: background-color 0.3s ease, color 0.3s ease;
}

.order-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 15px 12px 25px 12px;
}

.page-header {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: white;
    padding: 20px 25px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.15);
}

.page-header h1 {
    margin: 0 0 8px 0;
    font-size: 22px;
    font-weight: 600;
    color: white;
}

.page-header p {
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
    font-size: 13px;
}

.filter-section {
    background: var(--bg-card);
    padding: 15px 18px;
    border-radius: 9px;
    margin-bottom: 15px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px var(--shadow);
}

.filter-form {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 6px;
}

.filter-group label {
    font-size: 13px;
    color: var(--text-secondary);
    white-space: nowrap;
}

.filter-group input,
.filter-group select {
    padding: 6px 10px;
    border: 1px solid var(--border-color);
    border-radius: 5px;
    font-size: 13px;
    background: white;
    color: var(--text-primary);
    transition: border-color 0.3s ease;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: #6c757d;
    box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.1);
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--shadow-hover);
    color: var(--text-white);
}

.btn-secondary {
    background: #6c757d;
    color: var(--text-white);
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    color: var(--text-white);
}

.table-container {
    background: white;
    border-radius: 9px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px var(--shadow);
    overflow: hidden;
    padding: 10px;
}
</style>

<body>
    <?php require_once(includePath('myheader.php')); ?>   

    <div class="order-container">
        <div class="page-header">
            <h1><i class="fas fa-address-book"></i> 주소록 관리</h1>
            <p>거래처 및 연락처 정보를 관리합니다.</p>
        </div>

        <div class="filter-section">
            <div class="filter-form">
                <div class="filter-group">
                    <label><i class="fas fa-search"></i> 검색어</label>
                    <div class="position-relative">
                        <input type="text" id="searchInput" placeholder="검색어 입력..." style="width: 300px;">
                        <button id="clearSearchBtn" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-secondary" style="display:none; text-decoration:none; padding: 0; right: 10px !important;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <button class="btn btn-primary" onclick="openAddressModal(null)">
                    <i class="fas fa-plus"></i> 신규 등록
                </button>
                
                <button class="btn btn-secondary" onclick="window.open('import_csv.php', '_blank')" disabled>
                    <i class="fas fa-file-import"></i> CSV 가져오기
                </button>

                <button class="btn btn-secondary" onclick="downloadCSV()">
                    <i class="fas fa-download"></i> 엑셀 다운로드
                </button>
                
                <button class="btn btn-outline-secondary" onclick="openHelpModal()">
                    <i class="fas fa-question-circle"></i> 도움말
                </button>
            </div>
        </div>

        <div class="table-container">
            <div id="address-table"></div>
        </div>
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

    <!-- 도움말 모달 -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white py-3">
                    <h5 class="modal-title fs-5" id="helpModalLabel">
                        <i class="fas fa-question-circle"></i> 주소록 관리 사용법
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto; font-size: 1.15rem;">
                    <div class="p-2">
                        <h6 class="fw-bold text-primary mb-2"><i class="fas fa-search"></i> 조회 및 검색</h6>
                        <p class="text-muted mb-4">
                            <strong>검색어</strong>를 입력하여 거래처 및 연락처 정보를 빠르게 찾을 수 있습니다.<br>
                            검색어 입력 시 자동으로 필터링됩니다.
                        </p>

                        <h6 class="fw-bold text-success mb-2"><i class="fas fa-plus"></i> 신규 등록</h6>
                        <p class="text-muted mb-4">
                            상단의 <strong>'신규 등록'</strong> 버튼을 클릭하여<br>
                            새로운 주소록 정보를 등록할 수 있습니다.
                        </p>

                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-edit"></i> 수정 및 삭제</h6>
                        <p class="text-muted mb-4">
                            목록의 <strong>관리</strong> 컬럼에서 <strong>수정</strong> 또는 <strong>삭제</strong> 버튼을 클릭하여<br>
                            정보를 수정하거나 삭제할 수 있습니다.
                        </p>
                        
                        <h6 class="fw-bold text-warning mb-2"><i class="fas fa-download"></i> 엑셀 다운로드</h6>
                        <p class="text-muted mb-4">
                            <strong>'엑셀 다운로드'</strong> 버튼을 클릭하여<br>
                            현재 목록을 엑셀 파일(CSV)로 다운로드할 수 있습니다.
                        </p>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
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

        function openHelpModal() {
            var modal = new bootstrap.Modal(document.getElementById('helpModal'));
            modal.show();
        }
    </script>
    <?php include getDocumentRoot() . '/load_footer.php'; ?>
</body>
