<?php
require_once __DIR__ . '/bootstrap.php';

// 로그인 체크
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    header("Location: login_manager.php");
    exit;
}

// 로그아웃 처리
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['manager_logged_in']);
    unset($_SESSION['manager_userid']);
    unset($_SESSION['manager_name']);
    unset($_SESSION['manager_level']);
    header("Location: login_manager.php");
    exit;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
$DB = $_SESSION['DB'] ?? 'mirae8440';

// 시공사례 목록 조회
$portfolios = [];
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'all';

try {
    $sql = "SELECT * FROM {$DB}.portfolio WHERE 1=1";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " AND (title LIKE ? OR location LIKE ?)";
        $searchParam = '%' . $search . '%';
        $params[] = $searchParam;
        $params[] = $searchParam;
    }
    
    if ($category !== 'all') {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    $sql .= " ORDER BY display_order ASC, created_at DESC";
    
    $stmh = $pdo->prepare($sql);
    if (!empty($params)) {
        foreach ($params as $index => $param) {
            $stmh->bindValue($index + 1, $param, PDO::PARAM_STR);
        }
    }
    $stmh->execute();
    $portfolios = $stmh->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("시공사례 조회 오류: " . $e->getMessage());
}

$manager_name = $_SESSION['manager_name'] ?? '관리자';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>시공사례 등록 관리</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            padding-top: 20px; 
            background-color: #f5f5f5; 
        }
        .container { 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        }
        .portfolio-item {
            transition: transform 0.2s;
        }
        .portfolio-item:hover {
            transform: translateY(-5px);
        }
        .portfolio-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .btn-action {
            margin: 2px;
        }
        
        /* 파일 업로드 영역 스타일 (포미스톤 스타일 참고) */
        .file-upload-area {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            position: relative;
        }
        
        .file-upload-area:hover {
            border-color: #007bff;
            background: #e7f3ff;
        }
        
        .file-upload-area.dragover {
            border-color: #007bff;
            background: rgba(0, 123, 255, 0.1);
            transform: scale(1.02);
        }
        
        .file-upload-content {
            pointer-events: none;
        }
        
        .file-upload-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            color: #6c757d;
        }
        
        .file-upload-text {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }
        
        .file-upload-text strong {
            color: #007bff;
        }
        
        .file-upload-hint {
            color: #6c757d;
            font-size: 0.85rem;
            margin: 0.5rem 0 0 0;
        }
        
        .file-upload-preview {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        
        .file-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }
        
        .file-icon {
            width: 24px;
            height: 24px;
            color: #6c757d;
            flex-shrink: 0;
        }
        
        .file-details {
            flex: 1;
            min-width: 0;
        }
        
        .file-name {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 500;
            color: #212529;
            word-break: break-all;
        }
        
        .file-size {
            margin: 0.25rem 0 0 0;
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .file-remove {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
            padding: 0;
        }
        
        .file-remove:hover {
            background: #c82333;
            transform: scale(1.1);
        }
        
        .file-remove svg {
            width: 16px;
            height: 16px;
        }
        
        /* 이미지 미리보기 그리드 */
        #imagePreviewGrid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .preview-item {
            position: relative;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }
        
        .preview-item:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .preview-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        
        .preview-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 2;
        }
        
        .preview-badge.main {
            background: #28a745;
        }
        
        .preview-item .preview-remove {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            line-height: 1;
            z-index: 2;
            transition: all 0.2s;
        }
        
        .preview-item .preview-remove:hover {
            background: #c82333;
            transform: scale(1.1);
        }
        
        .preview-item .preview-main-btn {
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 123, 255, 0.9);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 0.75rem;
            cursor: pointer;
            z-index: 2;
            transition: all 0.2s;
        }
        
        .preview-item .preview-main-btn:hover {
            background: rgba(0, 123, 255, 1);
        }
        
        /* 모달 backdrop 블러 제거 */
        .modal-backdrop {
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
        
        /* 모달 닫힌 후 body 스타일 초기화 */
        body.modal-open {
            overflow: hidden;
        }
        
        /* 모달 본문 세로 스크롤 */
        #portfolioModal .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        /* 모달 다이얼로그 스크롤 설정 */
        #portfolioModal .modal-dialog {
            max-height: calc(100vh - 1rem);
            display: flex;
            flex-direction: column;
        }
        
        #portfolioModal .modal-content {
            max-height: calc(100vh - 1rem);
            display: flex;
            flex-direction: column;
        }
        
        #portfolioModal .modal-footer {
            flex-shrink: 0;
        }
        
        #portfolioModal .modal-header {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2><i class="fas fa-tools"></i> 시공사례 등록 관리</h2>
            <div>
                <span class="me-3"><?php echo htmlspecialchars($manager_name); ?>님</span>
                <a href="index.php" class="btn btn-outline-secondary me-2">홈페이지</a>
                <a href="?action=logout" class="btn btn-danger">로그아웃</a>
            </div>
        </div>

        <!-- 검색 및 필터 -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="get" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="제목 또는 위치 검색..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="get" class="d-flex">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <select name="category" class="form-select me-2" onchange="this.form.submit()">
                        <option value="all" <?php echo $category === 'all' ? 'selected' : ''; ?>>전체 카테고리</option>
                        <option value="ceiling" <?php echo $category === 'ceiling' ? 'selected' : ''; ?>>조명천장</option>
                        <option value="jamb" <?php echo $category === 'jamb' ? 'selected' : ''; ?>>쟘(JAMB)</option>
                        <option value="sill" <?php echo $category === 'sill' ? 'selected' : ''; ?>>재료분리대</option>
                        <option value="etc" <?php echo $category === 'etc' ? 'selected' : ''; ?>>기타</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- 등록 버튼 -->
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#portfolioModal" onclick="openModal('add')">
                <i class="fas fa-plus"></i> 새 시공사례 등록
            </button>
        </div>

        <!-- 시공사례 목록 -->
        <div class="row">
            <?php if (empty($portfolios)): ?>
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>등록된 시공사례가 없습니다.</p>
                </div>
            <?php else: ?>
                <?php foreach ($portfolios as $portfolio): ?>
                    <div class="col-md-4 mb-4 portfolio-item">
                        <div class="card h-100">
                            <?php if (!empty($portfolio['thumbnail'])): ?>
                                <img src="<?php echo htmlspecialchars($portfolio['thumbnail']); ?>" class="card-img-top portfolio-image" alt="<?php echo htmlspecialchars($portfolio['title']); ?>">
                            <?php elseif (!empty($portfolio['main_image'])): ?>
                                <img src="<?php echo htmlspecialchars($portfolio['main_image']); ?>" class="card-img-top portfolio-image" alt="<?php echo htmlspecialchars($portfolio['title']); ?>">
                            <?php else: ?>
                                <div class="card-img-top portfolio-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($portfolio['title']); ?></h5>
                                <p class="card-text text-muted small">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($portfolio['location'] ?? '위치 미입력'); ?><br>
                                    <i class="fas fa-calendar"></i> <?php echo $portfolio['project_date'] ? date('Y-m-d', strtotime($portfolio['project_date'])) : '날짜 미입력'; ?><br>
                                    <span class="badge bg-secondary"><?php 
                                        $categories = ['ceiling' => '조명천장', 'jamb' => '쟘', 'sill' => '재료분리대', 'etc' => '기타'];
                                        echo $categories[$portfolio['category']] ?? $portfolio['category'];
                                    ?></span>
                                    <?php if ($portfolio['is_published'] == 0): ?>
                                        <span class="badge bg-warning">비공개</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-action" onclick="openModal('edit', <?php echo $portfolio['id']; ?>)">
                                        <i class="fas fa-edit"></i> 수정
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-action" onclick="deletePortfolio(<?php echo $portfolio['id']; ?>)">
                                        <i class="fas fa-trash"></i> 삭제
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- 등록/수정 모달 -->
    <div class="modal fade" id="portfolioModal" tabindex="-1" aria-labelledby="portfolioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="portfolioModalLabel">시공사례 등록</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="portfolioForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="portfolio_id" name="id">
                        <div class="mb-3">
                            <label for="title" class="form-label">제목 (현장명) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">카테고리 <span class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="ceiling">엘리베이터 조명천장</option>
                                    <option value="jamb">엘리베이터 쟘(JAMB)</option>
                                    <option value="sill">재료분리대(SILL COVER)</option>
                                    <option value="etc">기타</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="project_date" class="form-label">시공일자</label>
                                <input type="date" class="form-control" id="project_date" name="project_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">시공 위치</label>
                            <input type="text" class="form-control" id="location" name="location" placeholder="예: 충남 천안 공주대 천안캠">
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">설명</label>
                            <textarea class="form-control" id="content" name="content" rows="3" placeholder="시공 내용에 대한 간단한 설명을 입력하세요."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">시공사례 이미지 (여러 장 가능) <span class="text-danger">*</span></label>
                            <div class="file-upload-area" id="fileUploadArea">
                                <input type="file" id="images" name="images[]" accept="image/*" multiple style="display: none;">
                                <div class="file-upload-content" id="fileUploadContent">
                                    <svg class="file-upload-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" y1="3" x2="12" y2="15"></line>
                                    </svg>
                                    <p class="file-upload-text">
                                        <strong>파일을 드래그하여 놓거나</strong> 클릭하여 선택하세요<br>
                                        <small>(여러 장 선택 가능, 첫 번째 이미지가 대표 이미지)</small>
                                    </p>
                                    <p class="file-upload-hint">
                                        JPG, PNG, GIF (1장당 최대 10MB)
                                    </p>
                                </div>
                            </div>
                            <div id="imagePreviewGrid" class="mt-3"></div>
                        </div>
                        <div class="mb-3">
                            <label for="display_order" class="form-label">표시 순서</label>
                            <input type="number" class="form-control" id="display_order" name="display_order" value="0">
                            <div class="form-text">숫자가 작을수록 먼저 표시됩니다.</div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" checked>
                                <label class="form-check-label" for="is_published">
                                    공개 여부 (체크 해제 시 비공개)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                        <button type="submit" class="btn btn-primary">저장</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.10/sweetalert2.all.min.js"></script>
    <script>
        // 모달 열기
        function openModal(mode, id = null) {
            const modalElement = document.getElementById('portfolioModal');
            const modal = new bootstrap.Modal(modalElement);
            const form = document.getElementById('portfolioForm');
            const modalTitle = document.getElementById('portfolioModalLabel');
            
            form.reset();
            document.getElementById('imagePreviewGrid').innerHTML = '';
            resetFileUpload();
            
            if (mode === 'add') {
                modalTitle.textContent = '새 시공사례 등록';
                document.getElementById('portfolio_id').value = '';
            } else if (mode === 'edit' && id) {
                modalTitle.textContent = '시공사례 수정';
                // 데이터 로드
                loadPortfolioData(id);
            }
            
            modal.show();
            
            // 모달 닫힐 때 backdrop 완전히 제거
            modalElement.addEventListener('hidden.bs.modal', function cleanup() {
                // backdrop 제거
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                // body 클래스 제거
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                // 이벤트 리스너 제거 (메모리 누수 방지)
                modalElement.removeEventListener('hidden.bs.modal', cleanup);
            }, { once: true });
        }
        
        // 여러 이미지를 관리하는 배열
        let uploadedImages = [];
        let existingImages = []; // 수정 시 기존 이미지
        
        // 파일 업로드 영역 초기화
        function resetFileUpload() {
            const fileInput = document.getElementById('images');
            const fileUploadContent = document.getElementById('fileUploadContent');
            const imagePreviewGrid = document.getElementById('imagePreviewGrid');
            
            fileInput.value = '';
            uploadedImages = [];
            existingImages = [];
            fileUploadContent.style.display = 'block';
            imagePreviewGrid.innerHTML = '';
        }
        
        // 파일 크기 포맷팅
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
        
        // 모든 이미지 미리보기 표시
        function showAllImagePreviews() {
            const imagePreviewGrid = document.getElementById('imagePreviewGrid');
            
            if (uploadedImages.length === 0 && existingImages.length === 0) {
                imagePreviewGrid.innerHTML = '';
                return;
            }
            
            // 모든 이미지 합치기 (업로드된 것 + 기존 것)
            const allImages = [...existingImages, ...uploadedImages];
            
            imagePreviewGrid.innerHTML = allImages.map((img, index) => {
                const isMain = index === 0;
                // 새로 선택한 이미지는 preview 속성에 base64 데이터가 있음
                // 기존 이미지는 thumbnail 또는 original 경로가 있음
                const imageUrl = img.preview || img.thumbnail || img.original || '';
                const imageName = img.filename || img.name || `이미지 ${index + 1}`;
                
                // imageUrl이 없으면 빈 이미지 표시
                if (!imageUrl) {
                    return `
                        <div class="preview-item" data-index="${index}">
                            <div style="width: 100%; height: 150px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                                <span>이미지 없음</span>
                            </div>
                            <span class="preview-badge ${isMain ? 'main' : ''}">${isMain ? '메인' : index + 1}</span>
                            <button type="button" class="preview-remove" onclick="removeImage(${index})" aria-label="이미지 제거">×</button>
                            ${!isMain ? `<button type="button" class="preview-main-btn" onclick="setMainImage(${index})">메인으로</button>` : ''}
                        </div>
                    `;
                }
                
                return `
                    <div class="preview-item" data-index="${index}">
                        <img src="${imageUrl}" alt="${imageName}" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' width=\\'150\\' height=\\'150\\'%3E%3Crect fill=\\'%23f3f4f6\\' width=\\'150\\' height=\\'150\\'/%3E%3Ctext fill=\\'%239ca3af\\' x=\\'50%25\\' y=\\'50%25\\' text-anchor=\\'middle\\' dominant-baseline=\\'middle\\'%3E이미지 없음%3C/text%3E%3C/svg%3E'">
                        <span class="preview-badge ${isMain ? 'main' : ''}">${isMain ? '메인' : index + 1}</span>
                        <button type="button" class="preview-remove" onclick="removeImage(${index})" aria-label="이미지 제거">×</button>
                        ${!isMain ? `<button type="button" class="preview-main-btn" onclick="setMainImage(${index})">메인으로</button>` : ''}
                    </div>
                `;
            }).join('');
        }
        
        // 이미지 제거
        function removeImage(index) {
            const allImages = [...existingImages, ...uploadedImages];
            if (index < existingImages.length) {
                // 기존 이미지 제거
                existingImages.splice(index, 1);
            } else {
                // 업로드된 이미지 제거
                const uploadIndex = index - existingImages.length;
                uploadedImages.splice(uploadIndex, 1);
            }
            showAllImagePreviews();
        }
        
        // 메인 이미지 설정
        function setMainImage(index) {
            const allImages = [...existingImages, ...uploadedImages];
            if (index < 0 || index >= allImages.length || index === 0) return;
            
            // 배열 순서 변경 (선택한 이미지를 첫 번째로)
            const selectedImage = allImages[index];
            
            if (index < existingImages.length) {
                // 기존 이미지인 경우
                existingImages.splice(index, 1);
                existingImages.unshift(selectedImage);
            } else {
                // 업로드된 이미지인 경우
                const uploadIndex = index - existingImages.length;
                uploadedImages.splice(uploadIndex, 1);
                existingImages.unshift(selectedImage);
            }
            
            showAllImagePreviews();
        }
        
        // 파일 업로드 영역 클릭 이벤트
        $('#fileUploadArea').on('click', function(e) {
            if (e.target === document.getElementById('images') || e.target.closest('.preview-remove') || e.target.closest('.preview-main-btn')) {
                return;
            }
            e.preventDefault();
            e.stopPropagation();
            $('#images').click();
        });
        
        // 드래그앤드롭 이벤트
        $('#fileUploadArea').on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });
        
        $('#fileUploadArea').on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });
        
        $('#fileUploadArea').on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
            
            const files = Array.from(e.originalEvent.dataTransfer.files);
            if (files.length > 0) {
                handleMultipleFileSelect(files);
            }
        });
        
        // 파일 선택 시 (여러 장)
        $('#images').on('change', function(e) {
            e.stopPropagation();
            if (this.files.length > 0) {
                handleMultipleFileSelect(Array.from(this.files));
            }
        });
        
        // 여러 파일 선택 처리
        function handleMultipleFileSelect(files) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            // 파일 검증
            for (const file of files) {
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire('오류', `${file.name}: JPG, PNG, GIF 파일만 업로드 가능합니다.`, 'error');
                    return;
                }
                
                if (file.size > maxSize) {
                    Swal.fire('오류', `${file.name}: 파일 크기는 10MB를 초과할 수 없습니다.`, 'error');
                    return;
                }
            }
            
            // 파일들을 업로드 배열에 추가
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedImages.push({
                        file: file,
                        preview: e.target.result,
                        name: file.name,
                        size: file.size
                    });
                    showAllImagePreviews();
                };
                reader.readAsDataURL(file);
            });
            
            // 파일 input 초기화 (같은 파일을 다시 선택할 수 있도록)
            document.getElementById('images').value = '';
        }

        // 시공사례 데이터 로드
        function loadPortfolioData(id) {
            fetch(`api/portfolio.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const portfolio = data.data;
                        document.getElementById('portfolio_id').value = portfolio.id;
                        document.getElementById('title').value = portfolio.title || '';
                        document.getElementById('category').value = portfolio.category || 'etc';
                        document.getElementById('location').value = portfolio.location || '';
                        document.getElementById('project_date').value = portfolio.project_date || '';
                        document.getElementById('content').value = portfolio.content || '';
                        document.getElementById('display_order').value = portfolio.display_order || 0;
                        document.getElementById('is_published').checked = portfolio.is_published == 1;
                        
                        // 기존 이미지 로드
                        existingImages = [];
                        uploadedImages = [];
                        
                        // 메인 이미지 추가
                        if (portfolio.main_image) {
                            existingImages.push({
                                original: portfolio.main_image,
                                thumbnail: portfolio.thumbnail || portfolio.main_image,
                                name: portfolio.main_image.split('/').pop(),
                                isMain: true
                            });
                        }
                        
                        // 추가 이미지들 추가
                        if (portfolio.images && Array.isArray(portfolio.images) && portfolio.images.length > 0) {
                            portfolio.images.forEach(img => {
                                existingImages.push({
                                    original: img.original || img,
                                    thumbnail: img.thumbnail || img.original || img,
                                    name: (img.original || img).split('/').pop(),
                                    isMain: false
                                });
                            });
                        }
                        
                        showAllImagePreviews();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('오류', '데이터를 불러오는 중 오류가 발생했습니다.', 'error');
                });
        }

        // 폼 제출
        document.getElementById('portfolioForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // 이미지 검증 (신규 등록 시)
            const id = document.getElementById('portfolio_id').value;
            const allImages = [...existingImages, ...uploadedImages];
            
            if (!id && allImages.length === 0) {
                Swal.fire('오류', '최소 1장 이상의 이미지를 업로드해주세요.', 'error');
                return;
            }
            
            const formData = new FormData(this);
            
            // 기존 이미지 경로 전송 (수정 시)
            if (existingImages.length > 0) {
                existingImages.forEach((img, index) => {
                    formData.append(`existing_images[]`, JSON.stringify({
                        original: img.original,
                        thumbnail: img.thumbnail
                    }));
                });
            }
            
            // 새로 업로드된 파일 추가
            uploadedImages.forEach((imgObj, index) => {
                if (imgObj.file) {
                    formData.append(`images[]`, imgObj.file);
                }
            });
            
            // FormData를 사용할 때는 POST로 보내고, API에서 id가 있으면 수정으로 처리
            const method = 'POST';
            
            fetch('api/portfolio.php', {
                method: method,
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('성공', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('오류', data.message || '저장 중 오류가 발생했습니다.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('오류', '서버와의 통신 중 오류가 발생했습니다.', 'error');
            });
        });


        // 삭제
        function deletePortfolio(id) {
            Swal.fire({
                title: '삭제 확인',
                text: '정말로 이 시공사례를 삭제하시겠습니까?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '삭제',
                cancelButtonText: '취소'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`api/portfolio.php?id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('삭제 완료', data.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('오류', data.message || '삭제 중 오류가 발생했습니다.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('오류', '서버와의 통신 중 오류가 발생했습니다.', 'error');
                    });
                }
            });
        }
    </script>
</body>
</html>
