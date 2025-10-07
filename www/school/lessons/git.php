<!doctype html>
<html lang="ko" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Git 기본 흐름: clone → pull → push</title>
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .step-card {
      border: 1px solid var(--bs-border-color);
      border-radius: 1rem;
      transition: transform .15s ease, box-shadow .15s ease;
      background: var(--bs-body-bg);
    }
    .step-card:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08); }
    .icon-badge {
      width: 56px; height: 56px;
      border-radius: 14px;
      display:flex; align-items:center; justify-content:center;
      font-size: 28px;
    }
    .connector {
      display: none;
    }
    @media (min-width: 992px) {
      .connector {
        display:block;
        height: 1px;
        background: var(--bs-border-color);
        position: relative;
        margin: 0 1rem;
        flex: 1 1 auto;
        align-self: center;
      }
      .connector::after {
        content:"";
        position:absolute; right:-4px; top:-4px;
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--bs-primary);
      }
    }
    pre {
      background: var(--bs-tertiary-bg);
      border-radius: .75rem;
      padding: 1rem 1.25rem;
      overflow:auto;
      margin-bottom: .5rem;
    }
    .copy-btn {
      position: absolute; top: .5rem; right: .5rem;
    }
    .code-box {
      position: relative;
    }
    .legend-dot {
      width:10px; height:10px; border-radius:50%;
      display:inline-block; margin-right:.5rem;
    }
    .cloud-box, .local-box {
      border: 1px dashed var(--bs-border-color);
      border-radius: .75rem;
      padding: .75rem 1rem;
      background: var(--bs-body-bg);
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg border-bottom">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#"><i class="bi bi-git me-2"></i>Git 협업 흐름</a>
      <div class="ms-auto d-flex gap-2">
        <button id="themeToggle" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-moon-stars"></i> 테마
        </button>
        <a class="btn btn-primary btn-sm" href="#quick">빠른 시작</a>
      </div>
    </div>
  </nav>

  <main class="container py-4 py-lg-5">
    <!-- 헤더 -->
    <header class="mb-4">
      <h1 class="h3 h-lg-2 fw-bold mb-2">git clone → git pull → git push</h1>
      <p class="text-secondary mb-0">원격 저장소(예: GitHub)와 내 컴퓨터(로컬) 사이의 기본 협업 흐름을 한 장으로 이해해보세요.</p>
    </header>

    <!-- 상단 다이어그램 -->
    <section class="mb-5">
      <div class="row g-3 align-items-center">
        <div class="col-lg">
          <div class="cloud-box">
            <div class="d-flex align-items-center gap-3">
              <div class="icon-badge bg-primary-subtle text-primary"><i class="bi bi-cloud-fill"></i></div>
              <div>
                <div class="fw-bold">원격 저장소 (GitHub 등)</div>
                <div class="small text-secondary">모든 팀원이 공유하는 중앙 저장소</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-auto d-none d-lg-block">
          <div class="connector"></div>
        </div>

        <div class="col-lg">
          <div class="local-box">
            <div class="d-flex align-items-center gap-3">
              <div class="icon-badge bg-success-subtle text-success"><i class="bi bi-laptop"></i></div>
              <div>
                <div class="fw-bold">내 컴퓨터 (로컬 저장소)</div>
                <div class="small text-secondary">내가 코드를 수정하고 실행하는 공간</div>
              </div>
            </div>
          </div>
          <div class="mt-3 small">
            <span class="legend-dot" style="background:var(--bs-primary)"></span>push (업로드)
            <span class="ms-3 legend-dot" style="background:var(--bs-success)"></span>pull (다운로드)
          </div>
        </div>
      </div>
    </section>

    <!-- 단계 카드 -->
    <section class="mb-5">
      <div class="row g-4">
        <!-- 1. clone -->
        <div class="col-lg-4">
          <div class="step-card p-4 h-100">
            <div class="d-flex align-items-center mb-3">
              <div class="icon-badge bg-dark-subtle text-dark me-3"><i class="bi bi-download"></i></div>
              <h2 class="h5 mb-0">1) git clone (처음 1회)</h2>
            </div>
            <p class="mb-3 text-secondary">원격 저장소의 <strong>모든 파일·이력·브랜치</strong>를 내 컴퓨터에 통째로 복제합니다.</p>
            <div class="code-box">
              <button class="btn btn-outline-secondary btn-sm copy-btn" data-target="#code-clone"><i class="bi bi-clipboard"></i></button>
              <pre id="code-clone"><code>git clone https://github.com/사용자/저장소.git
# 예) Tabulator
# git clone https://github.com/olifolkerd/tabulator.git</code></pre>
            </div>
            <ul class="small text-secondary mb-0">
              <li>처음 한 번만 실행</li>
              <li>프로젝트 폴더와 .git(이력)이 같이 생김</li>
            </ul>
          </div>
        </div>

        <!-- 2. pull -->
        <div class="col-lg-4">
          <div class="step-card p-4 h-100">
            <div class="d-flex align-items-center mb-3">
              <div class="icon-badge bg-success-subtle text-success me-3"><i class="bi bi-arrow-down-circle"></i></div>
              <h2 class="h5 mb-0">2) git pull (작업 전 습관)</h2>
            </div>
            <p class="mb-3 text-secondary">팀원이 올린 최신 변경을 <strong>원격 → 로컬</strong>로 가져옵니다. 충돌을 줄이는 최고의 습관.</p>
            <div class="code-box">
              <button class="btn btn-outline-secondary btn-sm copy-btn" data-target="#code-pull"><i class="bi bi-clipboard"></i></button>
              <pre id="code-pull"><code>git pull
# 특정 브랜치만: git pull origin 브랜치이름</code></pre>
            </div>
            <ul class="small text-secondary mb-0">
              <li>“작업 시작 전”에 실행</li>
              <li>브랜치 최신화 → 충돌 예방</li>
            </ul>
          </div>
        </div>

        <!-- 3. add/commit/push -->
        <div class="col-lg-4">
          <div class="step-card p-4 h-100">
            <div class="d-flex align-items-center mb-3">
              <div class="icon-badge bg-primary-subtle text-primary me-3"><i class="bi bi-arrow-up-circle"></i></div>
              <h2 class="h5 mb-0">3) add → commit → push</h2>
            </div>
            <p class="mb-3 text-secondary">내 변경을 기록으로 남기고 <strong>로컬 → 원격</strong>으로 업로드합니다.</p>
            <div class="code-box">
              <button class="btn btn-outline-secondary btn-sm copy-btn" data-target="#code-push"><i class="bi bi-clipboard"></i></button>
              <pre id="code-push"><code>git add .                     # 변경 파일 선택
git commit -m "메시지"       # 변경 이력 저장
git push                      # 원격에 업로드
# 특정 브랜치: git push origin 브랜치이름</code></pre>
            </div>
            <ul class="small text-secondary mb-0">
              <li><code>add</code>: 올릴 파일 지정</li>
              <li><code>commit</code>: 스냅샷(기록) 만들기</li>
              <li><code>push</code>: 중앙 저장소로 업로드</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- 빠른 시작 / 브랜치 요약 -->
    <section id="quick" class="mb-5">
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="p-4 border rounded-4 h-100">
            <h3 class="h6 fw-bold mb-3"><i class="bi bi-lightning-charge me-2"></i>빠른 시작(명령 모음)</h3>
            <div class="code-box">
              <button class="btn btn-outline-secondary btn-sm copy-btn" data-target="#code-quick"><i class="bi bi-clipboard"></i></button>
              <pre id="code-quick"><code># 1) 처음 프로젝트 받기
git clone https://github.com/사용자/저장소.git
cd 저장소

# 2) 작업 전 최신화
git pull

# 3) 수정 후 업로드
git add .
git commit -m "작업 내용 요약"
git push</code></pre>
            </div>
            <div class="small text-secondary">※ 처음 1회만 <code>clone</code>, 그 이후엔 <code>pull</code> → 작업 → <code>push</code> 루틴</div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="p-4 border rounded-4 h-100">
            <h3 class="h6 fw-bold mb-3"><i class="bi bi-diagram-3 me-2"></i>브랜치(Branch) 한눈에</h3>
            <ul class="mb-3">
              <li><strong>main</strong>: 배포용 안정 브랜치</li>
              <li><strong>dev</strong>: 통합 개발 브랜치</li>
              <li><strong>feature/기능명</strong>: 기능 단위 작업용</li>
            </ul>
            <div class="code-box">
              <button class="btn btn-outline-secondary btn-sm copy-btn" data-target="#code-branch"><i class="bi bi-clipboard"></i></button>
              <pre id="code-branch"><code># 브랜치 목록
git branch
# 새 브랜치 생성 & 전환
git checkout -b feature/tabulator-grid
# 원격 추적 설정(처음 1회)
git push -u origin feature/tabulator-grid</code></pre>
            </div>
            <div class="small text-secondary">※ 기능별로 브랜치를 쪼개면 충돌·리뷰가 쉬워집니다.</div>
          </div>
        </div>
      </div>
    </section>

    <!-- 용어 정리 -->
    <section class="mb-5">
      <div class="p-4 border rounded-4">
        <h3 class="h6 fw-bold mb-3"><i class="bi bi-journal-code me-2"></i>용어 간단 정리</h3>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="d-flex">
              <i class="bi bi-cloud me-2 text-primary"></i>
              <div>
                <div class="fw-bold">원격(Remote)</div>
                <div class="small text-secondary">GitHub/GitLab 등 중앙 저장소</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex">
              <i class="bi bi-laptop me-2 text-success"></i>
              <div>
                <div class="fw-bold">로컬(Local)</div>
                <div class="small text-secondary">내 컴퓨터의 저장소(작업 공간)</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex">
              <i class="bi bi-git me-2 text-dark"></i>
              <div>
                <div class="fw-bold">커밋(Commit)</div>
                <div class="small text-secondary">변경 기록의 스냅샷</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="text-center text-secondary small">
      Made with Bootstrap 5 · Bootstrap Icons · © 2025
    </footer>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // 테마 토글 (light/dark)
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

    // 코드 복사
    document.querySelectorAll('.copy-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const target = document.querySelector(btn.dataset.target);
        const text = target.innerText.trim();
        navigator.clipboard.writeText(text).then(() => {
          const original = btn.innerHTML;
          btn.innerHTML = '<i class="bi bi-check2"></i>';
          setTimeout(() => btn.innerHTML = original, 1200);
        });
      });
    });
  </script>
</body>
</html>
