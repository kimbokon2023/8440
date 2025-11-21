<!-- 시공사례 Section -->
<section id="portfolio" class="section portfolio">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Portfolio</span>
            <h2 class="section-title">시공사례 updated</h2>
            <p class="section-description">미래기업이 완성한 프리미엄 시공 프로젝트를 만나보세요.</p>
        </div>

        <?php if (count($portfolios) > 0): ?>
        <div class="portfolio-grid">
            <?php foreach ($portfolios as $portfolio):
                // 이미지 배열 준비
                $allImages = [];
                if (!empty($portfolio['main_image'])) {
                    $allImages[] = [
                        'original' => $portfolio['main_image'],
                        'thumbnail' => $portfolio['thumbnail'] ?: $portfolio['main_image']
                    ];
                }
                // 추가 이미지 병합
                if (!empty($portfolio['images']) && is_array($portfolio['images'])) {
                    foreach ($portfolio['images'] as $img) {
                        if (is_array($img)) {
                            $allImages[] = [
                                'original' => $img['original'] ?? $img,
                                'thumbnail' => $img['thumbnail'] ?? ($img['original'] ?? $img)
                            ];
                        } else {
                            $allImages[] = [
                                'original' => $img,
                                'thumbnail' => $img
                            ];
                        }
                    }
                }

                $imageCount = count($allImages);
                
                // 카테고리 매핑
                $categoryNames = [
                    'ceiling' => '조명천장',
                    'jamb' => '쟘(JAMB)',
                    'sill' => '재료분리대',
                    'etc' => '기타'
                ];
            ?>
            <article class="portfolio-item"
                     data-portfolio-id="<?php echo $portfolio['id']; ?>"
                     data-portfolio-data='<?php echo json_encode([
                         'id' => $portfolio['id'],
                         'title' => $portfolio['title'],
                         'category' => $portfolio['category'],
                         'location' => $portfolio['location'] ?? '',
                         'project_date' => $portfolio['project_date'] ?? '',
                         'content' => $portfolio['content'] ?? '',
                         'images' => $allImages
                     ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>'
                     onclick="openPortfolioModal(<?php echo $portfolio['id']; ?>)"
                     style="cursor: pointer;">
                <div class="portfolio-item__image-wrapper">
                    <?php if ($imageCount > 0): ?>
                        <!-- 이미지 슬라이더 -->
                        <div class="portfolio-slider" data-slider-id="<?php echo $portfolio['id']; ?>">
                            <?php foreach ($allImages as $index => $image): ?>
                                <div class="portfolio-slider__item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo htmlspecialchars($image['thumbnail']); ?>"
                                         alt="<?php echo htmlspecialchars($portfolio['title']); ?> - 이미지 <?php echo $index + 1; ?>"
                                         loading="lazy"
                                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'350\' height=\'280\'%3E%3Crect fill=\'%23f3f4f6\' width=\'350\' height=\'280\'/%3E%3Ctext fill=\'%239ca3af\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3E이미지 없음%3C/text%3E%3C/svg%3E'">
                                </div>
                            <?php endforeach; ?>

                            <?php if ($imageCount > 1): ?>
                                <!-- 네비게이션 버튼 -->
                                <button class="portfolio-slider__prev"
                                        onclick="event.stopPropagation(); portfolioSlide(<?php echo $portfolio['id']; ?>, -1)"
                                        aria-label="이전 이미지">
                                    ‹
                                </button>
                                <button class="portfolio-slider__next"
                                        onclick="event.stopPropagation(); portfolioSlide(<?php echo $portfolio['id']; ?>, 1)"
                                        aria-label="다음 이미지">
                                    ›
                                </button>

                                <!-- 이미지 카운터 -->
                                <div class="portfolio-slider__counter">
                                    <span class="current">1</span> / <?php echo $imageCount; ?>
                                </div>

                                <!-- 인디케이터 -->
                                <div class="portfolio-slider__indicators">
                                    <?php for ($i = 0; $i < $imageCount; $i++): ?>
                                        <span class="indicator-dot <?php echo $i === 0 ? 'active' : ''; ?>"
                                              onclick="event.stopPropagation(); portfolioGoToSlide(<?php echo $portfolio['id']; ?>, <?php echo $i; ?>)"></span>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="portfolio-item__no-image">
                            <span>이미지 없음</span>
                        </div>
                    <?php endif; ?>

                    <span class="portfolio-item__category"><?php echo $categoryNames[$portfolio['category']] ?? '기타'; ?></span>
                </div>

                <div class="portfolio-item__content">
                    <h3 class="portfolio-item__title"><?php echo htmlspecialchars($portfolio['title']); ?></h3>

                    <?php if (!empty($portfolio['location'])): ?>
                        <p class="portfolio-item__location">
                            📍 <?php echo htmlspecialchars($portfolio['location']); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($portfolio['project_date'])): ?>
                        <p class="portfolio-item__date">
                            📅 <?php echo date('Y년 m월', strtotime($portfolio['project_date'])); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($portfolio['content'])): ?>
                        <p class="portfolio-item__description">
                            <?php echo nl2br(htmlspecialchars(mb_substr($portfolio['content'], 0, 100))); ?>
                            <?php if (mb_strlen($portfolio['content']) > 100): ?>...<?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="portfolio-empty">
            <p>등록된 시공사례가 없습니다.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- 시공사례 모달 -->
<div id="portfolioModal" class="portfolio-modal" onclick="closePortfolioModal(event)">
    <div class="portfolio-modal__overlay"></div>

    <div class="portfolio-modal__container" onclick="event.stopPropagation()">
        <!-- 닫기 버튼 -->
        <button class="portfolio-modal__close" onclick="closePortfolioModal()" aria-label="닫기">
            ×
        </button>

        <!-- 모달 헤더 -->
        <div class="portfolio-modal__header">
            <div class="portfolio-modal__category"></div>
            <h2 class="portfolio-modal__title"></h2>
            <div class="portfolio-modal__meta">
                <span class="portfolio-modal__location"></span>
                <span class="portfolio-modal__date"></span>
            </div>
        </div>

        <!-- 갤러리 영역 -->
        <div class="portfolio-modal__gallery">
            <!-- 메인 이미지 -->
            <div class="portfolio-modal__main-image">
                <img src="" alt="" id="modalMainImage">

                <!-- 네비게이션 버튼 -->
                <button class="portfolio-modal__nav portfolio-modal__nav--prev"
                        onclick="modalNavigateImage(-1)"
                        aria-label="이전 이미지">
                    ‹
                </button>
                <button class="portfolio-modal__nav portfolio-modal__nav--next"
                        onclick="modalNavigateImage(1)"
                        aria-label="다음 이미지">
                    ›
                </button>

                <!-- 이미지 카운터 -->
                <div class="portfolio-modal__counter">
                    <span id="modalCurrentIndex">1</span> / <span id="modalTotalImages">1</span>
                </div>
            </div>

            <!-- 썸네일 그리드 -->
            <div class="portfolio-modal__thumbnails" id="modalThumbnails">
                <!-- JavaScript로 동적 생성 -->
            </div>
        </div>

        <!-- 설명 영역 -->
        <div class="portfolio-modal__content">
            <p class="portfolio-modal__description"></p>
        </div>
    </div>
</div>

