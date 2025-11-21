/**
 * 시공사례 슬라이더 JavaScript
 */

/**
 * 포트폴리오 이미지 슬라이드 (좌우 버튼)
 * @param {number} portfolioId - 포트폴리오 ID
 * @param {number} direction - 방향 (-1: 이전, 1: 다음)
 */
function portfolioSlide(portfolioId, direction) {
    const slider = document.querySelector(`.portfolio-slider[data-slider-id="${portfolioId}"]`);
    if (!slider) return;

    const items = slider.querySelectorAll('.portfolio-slider__item');
    if (items.length <= 1) return;

    // 현재 활성 슬라이드 찾기
    let currentIndex = 0;
    items.forEach((item, index) => {
        if (item.classList.contains('active')) {
            currentIndex = index;
        }
    });

    // 다음 인덱스 계산 (순환)
    let nextIndex = currentIndex + direction;
    if (nextIndex < 0) nextIndex = items.length - 1;
    else if (nextIndex >= items.length) nextIndex = 0;

    // 슬라이드 전환
    items[currentIndex].classList.remove('active');
    items[nextIndex].classList.add('active');

    // 인디케이터 업데이트
    updateIndicators(slider, nextIndex);

    // 카운터 업데이트
    updateCounter(slider, nextIndex + 1, items.length);
}

/**
 * 특정 슬라이드로 이동 (인디케이터 클릭)
 * @param {number} portfolioId - 포트폴리오 ID
 * @param {number} targetIndex - 대상 인덱스
 */
function portfolioGoToSlide(portfolioId, targetIndex) {
    const slider = document.querySelector(`.portfolio-slider[data-slider-id="${portfolioId}"]`);
    if (!slider) return;

    const items = slider.querySelectorAll('.portfolio-slider__item');
    if (targetIndex < 0 || targetIndex >= items.length) return;

    // 현재 활성 슬라이드 제거
    items.forEach(item => item.classList.remove('active'));

    // 대상 슬라이드 활성화
    items[targetIndex].classList.add('active');

    // 인디케이터 업데이트
    updateIndicators(slider, targetIndex);

    // 카운터 업데이트
    updateCounter(slider, targetIndex + 1, items.length);
}

/**
 * 인디케이터 업데이트
 * @param {HTMLElement} slider - 슬라이더 요소
 * @param {number} activeIndex - 활성 인덱스
 */
function updateIndicators(slider, activeIndex) {
    const indicators = slider.querySelectorAll('.indicator-dot');
    indicators.forEach((indicator, index) => {
        if (index === activeIndex) {
            indicator.classList.add('active');
        } else {
            indicator.classList.remove('active');
        }
    });
}

/**
 * 카운터 업데이트
 * @param {HTMLElement} slider - 슬라이더 요소
 * @param {number} current - 현재 번호 (1부터 시작)
 * @param {number} total - 전체 개수
 */
function updateCounter(slider, current, total) {
    const counter = slider.querySelector('.portfolio-slider__counter .current');
    if (counter) {
        counter.textContent = current;
    }
}

// 키보드 네비게이션 (선택 사항)
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
        const focusedSlider = document.querySelector('.portfolio-item:hover .portfolio-slider');
        if (focusedSlider) {
            const portfolioId = focusedSlider.dataset.sliderId;
            const direction = e.key === 'ArrowLeft' ? -1 : 1;
            portfolioSlide(parseInt(portfolioId), direction);
        }
    }
});

console.log('✅ Portfolio slider initialized');

// ==================== 시공사례 모달 ====================

// 모달 데이터 저장
let currentPortfolioData = null;
let currentModalImageIndex = 0;

// 카테고리 매핑
const categoryNames = {
    'ceiling': '조명천장',
    'jamb': '쟘(JAMB)',
    'sill': '재료분리대',
    'etc': '기타'
};

/**
 * 포트폴리오 모달 열기
 * @param {number} portfolioId - 포트폴리오 ID
 */
function openPortfolioModal(portfolioId) {
    event.stopPropagation(); // 이벤트 버블링 방지

    // 데이터 가져오기
    const portfolioElement = document.querySelector(`.portfolio-item[data-portfolio-id="${portfolioId}"]`);
    if (!portfolioElement) return;

    const dataAttr = portfolioElement.getAttribute('data-portfolio-data');
    if (!dataAttr) return;

    try {
        currentPortfolioData = JSON.parse(dataAttr);
        console.log('📥 Portfolio data loaded:', currentPortfolioData);
        console.log('🖼️ Images array:', currentPortfolioData.images);
        console.log('📊 Total images:', currentPortfolioData.images ? currentPortfolioData.images.length : 0);
    } catch (e) {
        console.error('Portfolio data parse error:', e);
        console.error('Raw data:', dataAttr);
        return;
    }

    // 모달 채우기
    populateModal(currentPortfolioData);

    // 모달 표시
    const modal = document.getElementById('portfolioModal');
    modal.classList.add('active');

    // body 스크롤 방지
    document.body.classList.add('modal-open');

    // ESC 키로 닫기
    document.addEventListener('keydown', handleModalKeydown);
}

/**
 * 포트폴리오 모달 닫기
 * @param {Event} event - 이벤트 객체 (선택사항)
 */
function closePortfolioModal(event) {
    if (event) {
        // 오버레이 클릭 시에만 닫기
        if (event.target.id !== 'portfolioModal') return;
    }

    const modal = document.getElementById('portfolioModal');
    if (!modal) return;
    
    modal.classList.remove('active');

    // body 스크롤 복원
    document.body.classList.remove('modal-open');

    // 키보드 이벤트 리스너 제거
    document.removeEventListener('keydown', handleModalKeydown);

    // 데이터 초기화
    currentPortfolioData = null;
    currentModalImageIndex = 0;
}

/**
 * 모달에 데이터 채우기
 * @param {Object} data - 포트폴리오 데이터
 */
function populateModal(data) {
    // 헤더 정보
    const categoryEl = document.querySelector('.portfolio-modal__category');
    const titleEl = document.querySelector('.portfolio-modal__title');
    const locationEl = document.querySelector('.portfolio-modal__location');
    const dateEl = document.querySelector('.portfolio-modal__date');
    const descEl = document.querySelector('.portfolio-modal__description');

    if (categoryEl) categoryEl.textContent = categoryNames[data.category] || '기타';
    if (titleEl) titleEl.textContent = data.title;
    if (locationEl) locationEl.textContent = data.location ? `📍 ${data.location}` : '';
    if (dateEl) dateEl.textContent = data.project_date ? `📅 ${formatDate(data.project_date)}` : '';
    if (descEl) descEl.textContent = data.content || '';

    // 이미지 갤러리
    if (data.images && data.images.length > 0) {
        currentModalImageIndex = 0;
        updateModalImage(0, data.images);
        createThumbnails(data.images);
    }
}

/**
 * 날짜 포맷
 * @param {string} dateStr - 날짜 문자열
 * @returns {string} - 포맷된 날짜
 */
function formatDate(dateStr) {
    const date = new Date(dateStr);
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString();
    return `${year}년 ${month}월`;
}

/**
 * 모달 이미지 업데이트
 * @param {number} index - 이미지 인덱스
 * @param {Array} images - 이미지 배열
 */
function updateModalImage(index, images) {
    if (!images || images.length === 0) return;

    const mainImg = document.getElementById('modalMainImage');
    if (!mainImg) return;

    // 경로 정규화 (절대 경로로 변환)
    let imagePath = images[index].original || images[index];
    if (typeof imagePath === 'object') {
        imagePath = imagePath.original || imagePath.thumbnail || imagePath;
    }
    
    // 이미 경로가 /로 시작하지 않으면 추가
    if (imagePath && !imagePath.startsWith('/') && !imagePath.startsWith('http')) {
        imagePath = '/' + imagePath;
    }

    mainImg.src = imagePath;
    mainImg.alt = `${currentPortfolioData.title} - 이미지 ${index + 1}`;

    // 카운터 업데이트
    const currentIndexEl = document.getElementById('modalCurrentIndex');
    const totalImagesEl = document.getElementById('modalTotalImages');
    if (currentIndexEl) currentIndexEl.textContent = index + 1;
    if (totalImagesEl) totalImagesEl.textContent = images.length;

    // 썸네일 active 상태 업데이트
    const thumbnails = document.querySelectorAll('.portfolio-modal__thumbnail');
    thumbnails.forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.add('active');
        } else {
            thumb.classList.remove('active');
        }
    });

    currentModalImageIndex = index;
}

/**
 * 썸네일 생성
 * @param {Array} images - 이미지 배열
 */
function createThumbnails(images) {
    const container = document.getElementById('modalThumbnails');
    if (!container) return;
    
    container.innerHTML = '';

    if (images.length <= 1) {
        return; // 이미지가 1개면 썸네일 표시 안 함
    }

    images.forEach((image, index) => {
        // 경로 정규화
        let thumbPath = image.thumbnail || image.original || image;
        if (typeof thumbPath === 'object') {
            thumbPath = thumbPath.thumbnail || thumbPath.original || thumbPath;
        }
        
        if (thumbPath && !thumbPath.startsWith('/') && !thumbPath.startsWith('http')) {
            thumbPath = '/' + thumbPath;
        }

        const thumbDiv = document.createElement('div');
        thumbDiv.className = 'portfolio-modal__thumbnail' + (index === 0 ? ' active' : '');
        thumbDiv.onclick = () => updateModalImage(index, images);

        const thumbImg = document.createElement('img');
        thumbImg.src = thumbPath;
        thumbImg.alt = `썸네일 ${index + 1}`;

        thumbDiv.appendChild(thumbImg);
        container.appendChild(thumbDiv);
    });
}

/**
 * 모달 이미지 네비게이션 (좌우 버튼)
 * @param {number} direction - 방향 (-1: 이전, 1: 다음)
 */
function modalNavigateImage(direction) {
    if (!currentPortfolioData || !currentPortfolioData.images) return;

    const images = currentPortfolioData.images;
    if (images.length <= 1) return;

    let nextIndex = currentModalImageIndex + direction;

    // 순환
    if (nextIndex < 0) nextIndex = images.length - 1;
    else if (nextIndex >= images.length) nextIndex = 0;

    updateModalImage(nextIndex, images);
}

/**
 * 키보드 이벤트 핸들러
 * @param {KeyboardEvent} e - 키보드 이벤트
 */
function handleModalKeydown(e) {
    if (e.key === 'Escape') {
        closePortfolioModal();
    } else if (e.key === 'ArrowLeft') {
        modalNavigateImage(-1);
    } else if (e.key === 'ArrowRight') {
        modalNavigateImage(1);
    }
}

console.log('✅ Portfolio modal initialized');

