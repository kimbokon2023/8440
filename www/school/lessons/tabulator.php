<!doctype html>
<html lang="ko" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tabulator vs jQuery DataTables — 강점 한눈에</title>
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .cardx { border: 1px solid var(--bs-border-color); border-radius: 1rem; background: var(--bs-body-bg); }
    .cardx:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.08); }
    .icon-badge { width:56px; height:56px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:28px; }
    .connector { display:none; }
    @media (min-width:992px) { .connector{display:block;height:1px;background:var(--bs-border-color);position:relative;margin:0 1rem;flex:1 1 auto;align-self:center;} }
    .legend-dot { width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:.5rem; }
    pre { background: var(--bs-tertiary-bg); border-radius:.75rem; padding:1rem 1.25rem; overflow:auto; }
    .code-box { position:relative; }
    .copy-btn { position:absolute; top:.5rem; right:.5rem; }
    .feature-badge { font-weight:600; }
    .table-features td, .table-features th { vertical-align: middle; }
    .tip { border-left:4px solid var(--bs-primary); padding-left:.75rem; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg border-bottom">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#"><i class="bi bi-columns-gap me-2"></i>Tabulator 강점 다이어그램</a>
      <div class="ms-auto d-flex gap-2">
        <button id="themeToggle" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-moon-stars"></i> 테마
        </button>
        <a class="btn btn-primary btn-sm" href="#summary">요약</a>
      </div>
    </div>
  </nav>

  <main class="container py-4 py-lg-5">
    <header class="mb-4">
      <h1 class="h3 h-lg-2 fw-bold mb-2">왜 <span class="text-primary">Tabulator</span>인가? (vs jQuery DataTables)</h1>
      <p class="text-secondary mb-0">대용량, 인터랙션(에디터/드래그/트리/그룹핑), 가상 스크롤, 다양한 포맷터/에디터, 내보내기 등 프론트엔드 데이터 그리드에 필요한 기능을 <b>플러그인 없이</b> 폭넓게 제공합니다.</p>
    </header>

    <!-- 상단 비교 다이어그램 -->
    <section class="mb-5" id="summary">
      <div class="row g-3 align-items-stretch">
        <div class="col-lg">
          <div class="p-4 cardx h-100">
            <div class="d-flex align-items-center gap-3 mb-2">
              <div class="icon-badge bg-success-subtle text-success"><i class="bi bi-grid-3x3-gap-fill"></i></div>
              <div>
                <div class="fw-bold fs-5">Tabulator</div>
                <div class="small text-secondary">현대적 데이터 그리드(순수 JS, jQuery 의존 X)</div>
              </div>
            </div>
            <ul class="mb-0 text-secondary small">
              <li><b>가상 스크롤</b>(수만 행도 부드럽게), <b>트리/그룹/피벗 유사 집계</b>, <b>셀 에디팅</b> 내장</li>
              <li>강력한 <b>포맷터/에디터/밸리데이터</b> 체계, <b>드래그&드롭</b> 재정렬/그룹/선택</li>
              <li><b>열/필터/정렬 상태 유지</b>, <b>반응형 레이아웃</b>, <b>원격 데이터 소스</b> 페이징/가상화</li>
              <li>CSV/XLSX/PDF/HTML <b>내보내기</b> 내장(옵션/빌트인)</li>
            </ul>
          </div>
        </div>

        <div class="col-lg-auto d-none d-lg-block">
          <div class="connector"></div>
        </div>

        <div class="col-lg">
          <div class="p-4 cardx h-100">
            <div class="d-flex align-items-center gap-3 mb-2">
              <div class="icon-badge bg-secondary-subtle text-secondary"><i class="bi bi-table"></i></div>
              <div>
                <div class="fw-bold fs-5">jQuery DataTables</div>
                <div class="small text-secondary">jQuery 생태계의 검증된 테이블</div>
              </div>
            </div>
            <ul class="mb-0 text-secondary small">
              <li><b>표 기반</b> 목록/검색/페이징에 강함, 플러그인 생태계 풍부</li>
              <li>고급 인터랙션(트리/셀 에디팅/가상화)은 <b>추가 플러그인/커스텀</b> 의존</li>
              <li>대용량/복잡 UI에선 성능 및 유지보수 어려움 발생 가능</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- 기능 비교표 -->
    <section class="mb-5">
      <div class="p-4 cardx">
        <h2 class="h5 fw-bold mb-3"><i class="bi bi-check2-square me-2"></i>기능 비교 한눈에</h2>
        <div class="table-responsive">
          <table class="table table-features align-middle">
            <thead>
              <tr>
                <th style="min-width:220px;">기능</th>
                <th class="text-success">Tabulator</th>
                <th class="text-secondary">jQuery DataTables</th>
                <th>비고</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>가상 스크롤(대용량)</td>
                <td class="text-success"><i class="bi bi-check2-circle"></i></td>
                <td class="text-secondary"><i class="bi bi-dash-circle"></i></td>
                <td class="small text-secondary">수만 행에서도 스무스한 스크롤</td>
              </tr>
              <tr>
                <td>셀 에디팅(인풋/셀렉트/체크 등)</td>
                <td class="text-success"><i class="bi bi-check2-circle"></i></td>
                <td class="text-secondary"><i class="bi bi-exclamation-circle"></i></td>
                <td class="small text-secondary">DT는 플러그인/커스텀 필요</td>
              </tr>
              <tr>
                <td>트리/그룹/집계</td>
                <td class="text-success"><i class="bi bi-check2-circle"></i></td>
                <td class="text-secondary"><i class="bi bi-exclamation-circle"></i></td>
                <td class="small text-secondary">계층/섹션별 합계 UI 쉽게 구성</td>
              </tr>
              <tr>
                <td>드래그&드롭(열/행/그룹)</td>
                <td class="text-success"><i class="bi bi-check2-circle"></i></td>
                <td class="text-secondary"><i class="bi bi-dash-circle"></i></td>
                <td class="small text-secondary">대화형 레이아웃 변경/정렬</td>
              </tr>
              <tr>
                <td>포맷터/밸리데이터</td>
                <td class="text-success"><i class="bi bi-check2-circle"></i></td>
                <td class="text-secondary"><i class="bi bi-exclamation-circle"></i></td>
                <td class="small text-secondary">가격, 수량, 배지, 진행바 등</td>
              </tr>
              <tr>
                <td>내보내기(CSV/XLSX/PDF)</td>
                <td class="text-success"><i class="bi bi-check2-circle"></i></td>
                <td class="text-secondary"><i class="bi bi-exclamation-circle"></i></td>
                <td class="small text-secondary">DT는 보통 Buttons 플러그인</td>
              </tr>
              <tr>
                <td>반응형 레이아웃/열 숨김 우선순위</td>
                <td class="text-success"><i class="bi bi-check2-circle"></i></td>
                <td class="text-secondary"><i class="bi bi-exclamation-circle"></i></td>
                <td class="small text-secondary">모바일/태블릿 UI 케어</td>
              </tr>
              <tr>
                <td>원격 데이터(서버사이드)와의 유연성</td>
                <td class="text-success"><i class="bi bi-check2-circle"></i></td>
                <td class="text-secondary"><i class="bi bi-check2-circle"></i></td>
                <td class="small text-secondary">둘 다 가능, Tabulator는 컬럼/상태관리 강점</td>
              </tr>
              <tr>
                <td>jQuery 의존성</td>
                <td class="text-success"><i class="bi bi-x-circle"></i> (없음)</td>
                <td class="text-secondary"><i class="bi bi-check2-circle"></i></td>
                <td class="small text-secondary">현대 프레임워크(React/Vue) 호환성↑</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="small tip mt-3 text-secondary">
          <b>요약:</b> <span class="feature-badge text-success">Tabulator</span>는 “대용량 + 복합 인터랙션 + 내장 에디팅/가상화”에 강합니다. 
          <span class="feature-badge text-secondary">DataTables</span>는 “표준 목록/검색/페이징”에 간단하고 익숙합니다.
        </div>
      </div>
    </section>

    <!-- 코드 스니펫: 빠른 시작 -->
    <section class="mb-5">
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="p-4 cardx h-100">
            <h3 class="h6 fw-bold mb-3"><i class="bi bi-lightning-charge me-2"></i>Tabulator 빠른 시작</h3>
            <div class="code-box mb-3">
              <button class="btn btn-outline-secondary btn-sm copy-btn" data-target="#code-tab-cdn"><i class="bi bi-clipboard"></i></button>
              <pre id="code-tab-cdn"><code>&lt;link href="https://unpkg.com/tabulator-tables@5.6.0/dist/css/tabulator.min.css" rel="stylesheet"&gt;
&lt;script src="https://unpkg.com/tabulator-tables@5.6.0/dist/js/tabulator.min.js"&gt;&lt;/script&gt;

&lt;div id="grid"&gt;&lt;/div&gt;
&lt;script&gt;
const table = new Tabulator("#grid", {
  height: 420,
  layout: "fitColumns",
  columns: [
    { title: "제품", field: "name", editor: "input" },
    { title: "수량", field: "qty", hozAlign: "right", editor: "number", validator:"integer" },
    { title: "가격", field: "price", hozAlign: "right", formatter: "money", editor: "number" },
  ],
  data: [
    { name:"블럭", qty:12, price:1500 },
    { name:"패널", qty:4, price:9800 },
  ],
});
&lt;/script&gt;</code></pre>
            </div>
            <ul class="small text-secondary mb-0">
              <li>셀 에디팅/밸리데이션/포맷터 바로 사용</li>
              <li>가상 스크롤/그룹/트리/내보내기 옵션 제공</li>
            </ul>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="p-4 cardx h-100">
            <h3 class="h6 fw-bold mb-3"><i class="bi bi-rocket me-2"></i>DataTables 기본 시작(참고)</h3>
            <div class="code-box mb-3">
              <button class="btn btn-outline-secondary btn-sm copy-btn" data-target="#code-dt-cdn"><i class="bi bi-clipboard"></i></button>
              <pre id="code-dt-cdn"><code>&lt;link href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" rel="stylesheet"&gt;
&lt;script src="https://code.jquery.com/jquery-3.7.1.min.js"&gt;&lt;/script&gt;
&lt;script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"&gt;&lt;/script&gt;

&lt;table id="example"&gt;...&lt;/table&gt;
&lt;script&gt;
$(function(){
  $('#example').DataTable({
    paging: true, searching: true, ordering: true
  });
});
&lt;/script&gt;</code></pre>
            </div>
            <ul class="small text-secondary mb-0">
              <li>고급 기능(에디팅/가상화/트리 등)은 별도 플러그인/커스텀 필요</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- 실무 케이스 -->
    <section class="mb-5">
      <div class="p-4 cardx">
        <h2 class="h6 fw-bold mb-3"><i class="bi bi-briefcase me-2"></i>실무에서 Tabulator가 특히 빛나는 상황</h2>
        <div class="row g-3">
          <div class="col-md-6">
            <ul class="mb-0 text-secondary small">
              <li><b>ERP/CRM</b>형 대화면 그리드: 행/열 수가 많고, 인라인 편집·검증 필요</li>
              <li><b>제조/물류</b> 데이터: 트리(품목-BOM), 그룹(거래처별/현장별 집계), 드래그 재정렬</li>
              <li><b>대용량 로그/리스트</b>: 가상 스크롤 + 서버사이드 페이징/필터</li>
            </ul>
          </div>
          <div class="col-md-6">
            <ul class="mb-0 text-secondary small">
              <li><b>내보내기</b>: CSV/XLSX/PDF 버튼 제공, 엑셀 전달 업무 흐름에 적합</li>
              <li><b>상태 복원</b>: 열 너비/정렬/필터 상태 저장 → 사용자별 편의성 업</li>
              <li><b>프레임워크</b>와 결합: React/Vue/Vanilla에서 jQuery 의존 없이 사용</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- 마이그레이션 팁 -->
    <section class="mb-5">
      <div class="p-4 cardx">
        <h2 class="h6 fw-bold mb-3"><i class="bi bi-arrows-move me-2"></i>DataTables → Tabulator 마이그레이션 간단 매핑</h2>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>요구사항</th>
                <th>DataTables에서 하던 방식</th>
                <th>Tabulator에서의 대응</th>
              </tr>
            </thead>
            <tbody class="small text-secondary">
              <tr>
                <td>셀 에디팅</td>
                <td>별도 플러그인/커스텀</td>
                <td><code>editor: "input|number|select|tickCross"</code></td>
              </tr>
              <tr>
                <td>대용량 스크롤</td>
                <td>서버 페이징 위주</td>
                <td><code>virtualDom</code> 기본 + 서버/클라이언트 혼용</td>
              </tr>
              <tr>
                <td>트리/그룹</td>
                <td>커스텀 렌더링/플러그인</td>
                <td><code>dataTree:true</code>, <code>groupBy</code> 옵션</td>
              </tr>
              <tr>
                <td>내보내기</td>
                <td>Buttons 플러그인</td>
                <td><code>download("csv"|"xlsx"|"pdf")</code></td>
              </tr>
              <tr>
                <td>열 상태 유지</td>
                <td>수동 저장</td>
                <td><code>columnDefaults/mutators/persistence</code> 옵션</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="small text-secondary tip mt-2">
          <b>팁:</b> 기존 DataTables JSON 응답을 그대로 사용해도 되며, 컬럼 정의만 Tabulator 포맷으로 매핑하면 점진 도입이 쉽습니다.
        </div>
      </div>
    </section>

    <!-- 선택 가이드 -->
    <section class="mb-5">
      <div class="p-4 cardx">
        <h2 class="h6 fw-bold mb-3"><i class="bi bi-question-circle me-2"></i>어떤 걸 고르면 좋을까?</h2>
        <div class="row g-3 small text-secondary">
          <div class="col-md-6">
            <div class="p-3 border rounded-3">
              <div class="fw-bold mb-2 text-success"><i class="bi bi-check2-circle me-1"></i>Tabulator 추천</div>
              <ul class="mb-0">
                <li>행/열이 많고 <b>에디팅/밸리데이션</b>이 필수</li>
                <li><b>그룹/트리/드래그/내보내기</b> 등 인터랙션이 풍부해야 함</li>
                <li>React/Vue 등과 <b>jQuery 의존 없이</b> 결합하고 싶음</li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 border rounded-3">
              <div class="fw-bold mb-2 text-secondary"><i class="bi bi-dash-circle me-1"></i>DataTables 적합</div>
              <ul class="mb-0">
                <li><b>간단한 목록/검색/페이징</b> 중심</li>
                <li>기존 jQuery 플러그인 생태계를 계속 활용</li>
                <li>대용량/복합 인터랙션 요구가 낮음</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="text-center text-secondary small mb-5">
      Made with Bootstrap 5 · Bootstrap Icons · © 2025
    </footer>
    <div class="mb-5">
      <h2 class="h6 fw-bold mb-3"><i class="bi bi-table me-2"></i>Tabulator 테이블 사용 예시</h2>
      <div class="p-3 border rounded-3 bg-light">
        <div class="mb-2">
          <b>아래는 Tabulator로 만든 간단한 상품 테이블 예시입니다.</b><br>
          <span class="text-secondary small">셀을 더블클릭하면 직접 수정할 수 있습니다.</span>
        </div>
        <div id="grid"></div>
      </div>
    </div>


    <link href="https://unpkg.com/tabulator-tables@5.6.0/dist/css/tabulator.min.css" rel="stylesheet">
<script src="https://unpkg.com/tabulator-tables@5.6.0/dist/js/tabulator.min.js"></script>

<div id="grid"></div>
<script>
/** -----------------------------
 * 유틸: 1차/2차 옵션 생성 (2차는 랜덤)
 * ----------------------------- */
const L1_OPTIONS = ["분류-A", "분류-B", "분류-C", "분류-D", "분류-E"]; // 1차는 5개 고정(코드로 동적 생성)
const CHILDREN_CACHE = new Map(); // 같은 1차에 대해 2차를 동일하게 유지(세션 동안)

// 2차 옵션을 랜덤으로 만들어 반환(카테고리별 캐시)
function getChildrenOptions(parentValue) {
  if (!parentValue) return [];
  if (CHILDREN_CACHE.has(parentValue)) return CHILDREN_CACHE.get(parentValue);

  const count = 3 + Math.floor(Math.random() * 4); // 3~6개
  const options = Array.from({ length: count }, (_, i) => {
    const rnd = Math.floor(Math.random() * 900) + 100; // 100~999
    return `${parentValue}-옵션${i + 1}-${rnd}`;
  });
  CHILDREN_CACHE.set(parentValue, options);
  return options;
}

/** -----------------------------
 * 커스텀 에디터: 1차/2차 Select
 * Tabulator의 editor:function 형태 사용
 * ----------------------------- */

// 공용 helper: select 엘리먼트 생성
function createSelectElement(values, initialValue, { placeholder = null, disabled = false } = {}) {
  const sel = document.createElement("select");
  sel.style.width = "100%";
  sel.style.height = "100%";
  sel.style.boxSizing = "border-box";

  if (placeholder) {
    const ph = document.createElement("option");
    ph.value = "";
    ph.textContent = placeholder;
    ph.disabled = true;
    ph.selected = !initialValue;
    sel.appendChild(ph);
  }

  values.forEach(v => {
    const opt = document.createElement("option");
    opt.value = v;
    opt.textContent = v;
    sel.appendChild(opt);
  });

  if (initialValue && values.includes(initialValue)) {
    sel.value = initialValue;
  }
  sel.disabled = disabled;
  return sel;
}

// 1차 선택 에디터 (values: L1_OPTIONS)
function firstSelectEditor(cell, onRendered, success, cancel, editorParams) {
  const current = cell.getValue();
  const el = createSelectElement(L1_OPTIONS, current, { placeholder: "1차 선택…" });

  onRendered(() => el.focus());
  el.addEventListener("change", () => success(el.value));
  el.addEventListener("blur", () => success(el.value));
  el.addEventListener("keydown", (e) => {
    if (e.key === "Enter") success(el.value);
    if (e.key === "Escape") cancel();
  });

  return el;
}

// 2차 선택 에디터 (상위 1차 값에 따라 동적 옵션)
function secondSelectEditor(cell, onRendered, success, cancel, editorParams) {
  const rowData = cell.getRow().getData();
  const parent = rowData.level1; // 1차 값
  const options = getChildrenOptions(parent);
  const disabled = !parent || options.length === 0;

  const el = createSelectElement(
    options,
    cell.getValue(),
    { placeholder: parent ? "2차 선택…" : "먼저 1차를 선택하세요", disabled }
  );

  onRendered(() => el.focus());
  el.addEventListener("change", () => success(el.value));
  el.addEventListener("blur", () => success(el.value));
  el.addEventListener("keydown", (e) => {
    if (e.key === "Enter") success(el.value);
    if (e.key === "Escape") cancel();
  });

  return el;
}

/** -----------------------------
 * Tabulator 초기화
 * ----------------------------- */
const table = new Tabulator("#grid", {
  height: 420,
  layout: "fitColumns",
  columns: [
    // 1열: 1차 셀렉트 (5개 선택)
    { title: "1차", field: "level1", editor: firstSelectEditor, width: 140 },

    // 2열: 2차 셀렉트 (1차 값에 따라 옵션 동적/랜덤)
    { title: "2차", field: "level2", editor: secondSelectEditor, width: 200 },

    // 기존 컬럼 유지 (원하시면 위치 바꾸세요)
    { title: "제품", field: "name", editor: "input" },
    { title: "수량", field: "qty", hozAlign: "right", editor: "number", validator: "integer" },
    { title: "가격", field: "price", hozAlign: "right", formatter: "money", editor: "number" },
  ],
  data: [
    // level1/level2는 비워두고 사용자가 선택
    { level1: "", level2: "", name: "블럭", qty: 12, price: 1500 },
    { level1: "", level2: "", name: "패널", qty: 4,  price: 9800 },
  ],
});

// 1차가 바뀌면 2차 값을 초기화(연동 보장)
table.on("cellEdited", (cell) => {
  if (cell.getField() === "level1") {
    cell.getRow().update({ level2: "" });
  }
});


    // 테마 토글
    const toggleBtn = document.getElementById('themeToggle');
    toggleBtn.addEventListener('click', () => {
      const html = document.documentElement;
      const current = html.getAttribute('data-bs-theme') || 'light';
      const next = current === 'light' ? 'dark' : 'light';
      html.setAttribute('data-bs-theme', next);
      toggleBtn.innerHTML = next === 'dark'
        ? '<i class="bi bi-brightness-high"></i> 테마'
        : '<i class="bi bi-moon-stars"></i> 테마';
    });

</script>
</body>
</html>
