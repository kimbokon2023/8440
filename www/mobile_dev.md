# 모바일 UI/UX 개발 가이드

## 개요
이 문서는 미래기업 업무포탈의 모바일 최적화 개선사항을 정리한 문서입니다.
`work/list.php`에 적용된 모바일 개선사항을 다른 메뉴에도 일관되게 적용하기 위한 가이드입니다.

**기준 화면 크기**: `@media (max-width: 768px)`

---

## 1. 기본 레이아웃 설정

### 1.1 뷰포트 및 전체 Width 제한

```css
@media (max-width: 768px) {
    /* body와 html의 width 제한 */
    html, body {
        max-width: 100vw !important;
        overflow-x: hidden !important;
        font-size: 16px !important; /* 기본 폰트 크기 */
    }

    /* 컨테이너 width 제한 */
    .container-fluid {
        max-width: 100vw !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
        overflow-x: hidden !important;
    }

    /* 모든 row의 width 제한 */
    .row {
        max-width: 100vw !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        overflow-x: hidden !important;
    }

    /* card의 width 제한 */
    .card {
        max-width: 100% !important;
        overflow-x: hidden !important;
        margin-bottom: 10px !important;
    }
}
```

**목적**: 가로 스크롤 완전 제거, 모바일 화면에 맞는 크기 제한

---

## 2. 공지사항 배너 최적화

### 2.1 배너 크기 및 폰트 조정

```css
@media (max-width: 768px) {
    .shadow-lg {
        min-height: auto !important;
        padding: 12px !important;
        max-width: 100% !important;
        overflow: hidden !important;
    }

    .shadow-lg i {
        font-size: 1.8rem !important;
    }

    .shadow-lg .text-white {
        font-size: 1rem !important;
        line-height: 1.4 !important;
    }

    .shadow-lg .badge {
        font-size: 1rem !important;
        padding: 5px 10px !important;
    }

    /* 이미지 숨기기 */
    .shadow-lg img {
        display: none !important;
    }
}
```

**효과**:
- 배너 높이 자동 조정
- 아이콘 크기 증가 (1.8rem)
- 이미지 숨김으로 공간 절약

---

## 3. 상단 제어 영역 최적화

### 3.1 제목 및 버튼 크기

```css
@media (max-width: 768px) {
    /* 제목 영역 */
    .d-flex h5 {
        font-size: 1.1rem !important;
        white-space: nowrap !important;
    }

    /* 버튼 크기 */
    .btn-sm {
        font-size: 0.85rem !important;
        padding: 0.4rem 0.6rem !important;
        white-space: nowrap !important;
    }

    /* 아이콘 크기 */
    ion-icon {
        font-size: 1.2rem !important;
        vertical-align: middle;
    }
}
```

### 3.2 입력 필드 크기

```css
@media (max-width: 768px) {
    /* 날짜 입력 */
    #fromdate, #todate {
        width: 110px !important;
        font-size: 0.9rem !important;
        padding: 0.4rem !important;
    }

    /* 검색 입력 */
    #search {
        width: 110px !important;
        font-size: 0.9rem !important;
        padding: 0.4rem !important;
    }

    /* 선택 박스 */
    .form-select {
        font-size: 0.9rem !important;
        height: 32px !important;
        padding: 0.4rem 0.6rem !important;
    }

    /* 배지 */
    .badge {
        font-size: 0.85rem !important;
        padding: 0.3rem 0.6rem !important;
    }
}
```

### 3.3 상단 카드 레이아웃 정리

```css
@media (max-width: 768px) {
    .card-body {
        padding: 12px !important;
    }

    /* 전체 영역 세로 배치 */
    .card-body .d-flex {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
    }

    /* 제목과 총 개수 영역만 가로 배치 */
    .card-body > .d-flex:first-of-type {
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 15px !important;
        padding-bottom: 12px !important;
        border-bottom: 2px solid #e9ecef !important;
    }

    /* 버튼 그룹 여백 */
    .card-body > .d-flex {
        margin-bottom: 10px !important;
    }

    /* 날짜 입력 영역 - Grid 레이아웃 */
    .card-body .d-flex:has(#fromdate) {
        display: grid !important;
        grid-template-columns: 1fr auto 1fr !important;
        gap: 8px !important;
        align-items: center !important;
    }

    #fromdate, #todate {
        width: 100% !important;
    }

    /* 검색 영역 - Grid 레이아웃 */
    .card-body .d-flex:has(#search) {
        display: grid !important;
        grid-template-columns: auto 1fr auto !important;
        gap: 8px !important;
        align-items: center !important;
    }

    #find {
        width: auto !important;
        min-width: 80px !important;
    }

    #search {
        width: 100% !important;
    }

    #searchBtn {
        white-space: nowrap !important;
    }

    /* 버튼 영역 배경 */
    .card-body > .d-flex.justify-content-center {
        background: #f8f9fa !important;
        padding: 10px !important;
        border-radius: 8px !important;
        margin: 8px 0 !important;
    }
}
```

**핵심 포인트**:
- 제목/개수: 가로 배치 (좌우 정렬)
- 버튼 그룹: 세로 배치, 회색 배경
- 날짜 입력: Grid 레이아웃 (1fr auto 1fr)
- 검색 영역: Grid 레이아웃 (auto 1fr auto)

---

## 4. 버튼 영역 최적화

### 4.1 버튼 줄바꿈 허용

```css
@media (max-width: 768px) {
    /* 버튼 영역 줄바꿈 허용 */
    .d-flex.justify-content-center {
        flex-wrap: wrap !important;
        overflow-x: visible !important;
        gap: 0.4rem !important;
        justify-content: flex-start !important;
    }

    /* 버튼 영역 가운데 정렬 유지 */
    .d-flex.justify-content-center.align-items-center {
        justify-content: center !important;
    }
}
```

**효과**: 버튼이 화면을 넘으면 자동으로 다음 줄로 이동

---

## 5. 팝업 프레임 최적화

```css
@media (max-width: 768px) {
    #showalignframe,
    #showextractframe,
    #showframe,
    #showsearchtoolframe {
        position: fixed !important;
        left: 50% !important;
        top: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 95% !important;
        max-width: 400px !important;
        z-index: 9999 !important;
        max-height: 80vh !important;
        overflow-y: auto !important;
    }

    /* 정렬, 부가기능 버튼들 */
    #showalign, #showextract, #showdate, #showsearchtool {
        min-width: 70px !important;
    }
}
```

**효과**: 팝업이 화면 중앙에 표시, 적절한 크기 유지

---

## 6. DataTables 최적화

### 6.1 불필요한 컨트롤 숨기기

```css
@media (max-width: 768px) {
    /* Show entries, Live Search 숨기기 */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        display: none !important;
    }

    /* 페이지네이션 최적화 */
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.9rem !important;
        margin-top: 15px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.7rem !important;
        margin: 0 2px !important;
    }

    /* 정보 표시 최적화 */
    .dataTables_wrapper .dataTables_info {
        font-size: 0.9rem !important;
        text-align: center !important;
        margin-top: 10px !important;
        margin-bottom: 10px !important;
    }
}
```

**효과**:
- Show entries 숨김
- Live Search 숨김
- 페이지 버튼 크기 증가
- 정보 표시 가운데 정렬

---

## 7. 테이블을 카드 레이아웃으로 변경

### 7.1 기본 카드 구조

```css
@media (max-width: 768px) {
    /* 테이블 헤더 숨기기 */
    #myTable thead {
        display: none;
    }

    /* 테이블을 카드 레이아웃으로 변경 */
    #myTable,
    #myTable tbody,
    #myTable tr,
    #myTable td {
        display: block;
        width: 100%;
    }

    #myTable tr {
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 14px;
        overflow: hidden;
    }
}
```

### 7.2 불필요한 필드 숨기기

```css
@media (max-width: 768px) {
    /* 모바일에서 불필요한 필드 숨기기 */
    #myTable td:nth-child(1),  /* 구분 (신규/덧방) */
    #myTable td:nth-child(2),  /* 외주 */
    #myTable td:nth-child(4),  /* 검사 */
    #myTable td:nth-child(5),  /* 배정 */
    #myTable td:nth-child(6),  /* 예정 */
    #myTable td:nth-child(7),  /* 설계 */
    #myTable td:nth-child(8),  /* 출고 */
    #myTable td:nth-child(9),  /* 시공 */
    #myTable td:nth-child(10), /* 전사진 */
    #myTable td:nth-child(11), /* 후사진 */
    #myTable td:nth-child(12), /* 청구 */
    #myTable td:nth-child(14), /* 재질(소재) */
    #myTable td:nth-child(16), /* 시공팀 */
    #myTable td:nth-child(18), /* HPI */
    #myTable td:nth-child(19)  /* 비고 */
    {
        display: none !important;
    }
}
```

**핵심**: 필수 필드만 표시 (접수일, 현장명, 원청, 발주처, 수량)

### 7.3 카드 내 필드 스타일

```css
@media (max-width: 768px) {
    #myTable td {
        text-align: left !important;
        padding: 12px !important;
        border: none !important;
        position: relative;
        padding-left: 35% !important;
        white-space: normal !important;
        word-wrap: break-word;
        min-height: 40px;
        font-size: 1rem !important;
        line-height: 1.6 !important;
    }

    /* 라벨 표시 */
    #myTable td:before {
        content: attr(data-label);
        position: absolute;
        left: 12px;
        width: 30%;
        padding-right: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 600;
        color: #6b7280;
        font-size: 0.85rem;
    }

    /* 라벨과 값 사이 콜론 */
    #myTable td:after {
        content: ':';
        position: absolute;
        left: 32%;
        font-weight: bold;
        color: #9ca3af;
    }

    /* 첫 번째 셀 숨김 */
    #myTable td:first-child {
        display: none !important;
    }

    #myTable td:first-child:after {
        display: none;
    }

    #myTable td:first-child:before {
        display: none;
    }
}
```

**핵심**:
- 라벨 30%, 값 65% 비율
- data-label 속성으로 라벨 표시
- 긴 라벨은 ellipsis(...) 처리
- 콜론으로 라벨과 값 구분

### 7.4 중요 필드 강조

```css
@media (max-width: 768px) {
    /* 접수일 강조 */
    #myTable td:nth-child(3) {
        font-weight: 600;
        color: #495057;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 12px !important;
        margin-bottom: 8px;
    }

    /* 현장명 강조 - 가장 중요 */
    #myTable td:nth-child(13) {
        background: #e7f3ff;
        font-weight: 700;
        font-size: 1.05rem !important;
        color: #0056b3;
        padding: 14px 12px !important;
        padding-left: 12px !important;
        margin: 8px 0;
        border-radius: 4px;
        border-left: 4px solid #0056b3;
        display: block !important;
    }

    /* 현장명 라벨 위쪽 배치 */
    #myTable td:nth-child(13):before {
        position: static !important;
        display: block !important;
        width: 100% !important;
        margin-bottom: 6px;
        font-size: 0.85rem !important;
        color: #6b7280 !important;
        font-weight: 600 !important;
    }

    /* 현장명 콜론 제거 */
    #myTable td:nth-child(13):after {
        display: none !important;
    }

    /* 원청 강조 */
    #myTable td:nth-child(15) {
        font-weight: 600;
        color: #059669;
    }

    /* 수량 강조 */
    #myTable td:nth-child(17) {
        font-weight: 600;
        color: #dc2626;
        font-size: 1rem !important;
    }
}
```

**색상 가이드**:
- 현장명: 파란 배경 (#e7f3ff), 왼쪽 강조선 (#0056b3)
- 원청: 녹색 텍스트 (#059669)
- 수량: 빨간색 텍스트 (#dc2626)

### 7.5 테이블 래퍼

```css
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: visible !important;
    }
}
```

---

## 8. HTML 구조 요구사항

### 8.1 테이블 td에 data-label 속성 추가

각 td 태그에 data-label 속성을 추가하여 모바일에서 라벨이 표시되도록 합니다.

```html
<tr onclick="redirectToView(...)">
    <td>신규</td>
    <td data-label="외주">...</td>
    <td data-label="접수">2024-01-15</td>
    <td data-label="검사">01-20</td>
    <td data-label="배정">01-22</td>
    <td data-label="예정">01-25</td>
    <td data-label="설계">홍길동</td>
    <td data-label="출고">01-30</td>
    <td data-label="시공">02-05</td>
    <td data-label="전사진">등록</td>
    <td data-label="후사진">등록</td>
    <td data-label="청구">02-10</td>
    <td data-label="현장명">서울아파트 A동</td>
    <td data-label="재질(소재)">STS304</td>
    <td data-label="원청">OTIS</td>
    <td data-label="발주처">한산</td>
    <td data-label="시공팀">김철수</td>
    <td data-label="설치수량">막10멍5</td>
    <td data-label="HPI">2000mm</td>
    <td data-label="비고">긴급공사</td>
</tr>
```

### 8.2 테이블 래퍼 구조

```html
<div class="row p-1 mt-1 mb-1 justify-content-center align-items-center">
    <div class="table-responsive">
        <table class="table table-hover" id="myTable">
            <thead class="table-primary">
                <!-- 헤더 -->
            </thead>
            <tbody>
                <!-- 데이터 행 -->
            </tbody>
        </table>
    </div><!-- table-responsive -->
</div><!-- row -->
```

---

## 9. 적용 체크리스트

### 9.1 기본 설정
- [ ] 뷰포트 메타 태그 확인 (`load_header.php`에 있음)
- [ ] html, body, container-fluid width 제한
- [ ] overflow-x: hidden 적용

### 9.2 상단 영역
- [ ] 공지사항 배너 폰트 크기 조정
- [ ] 공지사항 이미지 숨김
- [ ] 제목 및 버튼 크기 조정
- [ ] 입력 필드 크기 조정

### 9.3 레이아웃
- [ ] 카드 패딩 조정
- [ ] 버튼 그룹 세로 배치
- [ ] 날짜 영역 Grid 레이아웃
- [ ] 검색 영역 Grid 레이아웃
- [ ] 버튼 영역 회색 배경

### 9.4 버튼
- [ ] 버튼 줄바꿈 허용 (flex-wrap: wrap)
- [ ] 버튼 최소 크기 설정
- [ ] 적절한 gap 설정

### 9.5 팝업
- [ ] 팝업 프레임 중앙 정렬
- [ ] 최대 너비 400px
- [ ] 세로 스크롤 허용

### 9.6 DataTables
- [ ] Show entries 숨김
- [ ] Live Search 숨김
- [ ] 페이지네이션 버튼 크기 증가
- [ ] 정보 표시 가운데 정렬

### 9.7 테이블 카드
- [ ] 테이블 헤더 숨김
- [ ] tr을 카드로 변경
- [ ] 불필요한 필드 숨김 (프로젝트별로 선택)
- [ ] data-label 속성 추가
- [ ] 라벨 30%, 값 65% 비율
- [ ] 중요 필드 강조 스타일
- [ ] 현장명 세로 레이아웃

---

## 10. 프로젝트별 커스터마이징

### 10.1 표시할 필드 선택

각 메뉴의 특성에 맞게 표시할 필드를 선택합니다.

```css
/* 예시: 다른 메뉴의 필드 선택 */
@media (max-width: 768px) {
    /* 숨길 필드 선택 */
    #myTable td:nth-child(5),
    #myTable td:nth-child(8),
    #myTable td:nth-child(12) {
        display: none !important;
    }
}
```

### 10.2 강조할 필드 선택

중요한 필드에 색상이나 배경을 적용합니다.

```css
@media (max-width: 768px) {
    /* 가장 중요한 필드 */
    #myTable td:nth-child(X) {
        background: #e7f3ff;
        border-left: 4px solid #0056b3;
    }

    /* 경고 필드 */
    #myTable td:nth-child(Y) {
        color: #dc2626;
        font-weight: 600;
    }

    /* 성공 필드 */
    #myTable td:nth-child(Z) {
        color: #059669;
        font-weight: 600;
    }
}
```

---

## 11. 성능 최적화 팁

### 11.1 CSS 우선순위
- `!important` 사용으로 기존 스타일 오버라이드
- 모바일 전용 스타일은 미디어 쿼리 안에만 작성

### 11.2 터치 최적화
- 버튼 최소 크기: 44px × 44px
- 버튼 간격: 최소 8px
- 클릭 영역 충분히 확보

### 11.3 폰트 크기 가이드
- 기본 텍스트: 1rem (16px)
- 라벨: 0.85rem - 0.9rem
- 제목: 1.1rem - 1.2rem
- 버튼: 0.85rem - 0.9rem
- 중요 정보: 1rem - 1.05rem

---

## 12. 테스트 가이드

### 12.1 테스트 환경
- Chrome DevTools 모바일 모드
- 실제 Android/iOS 기기
- 다양한 화면 크기 (320px ~ 768px)

### 12.2 테스트 항목
- [ ] 가로 스크롤 없음
- [ ] 모든 버튼 터치 가능
- [ ] 텍스트 가독성
- [ ] 입력 필드 사용 편의성
- [ ] 팝업 정상 표시
- [ ] 카드 클릭 동작
- [ ] 페이지네이션 동작
- [ ] 검색 기능 동작

### 12.3 주요 체크포인트
1. **가로 스크롤**: 어떤 요소도 화면 밖으로 나가지 않아야 함
2. **버튼 배치**: 모든 버튼이 화면에 표시되어야 함
3. **카드 레이아웃**: 라벨과 값이 명확히 구분되어야 함
4. **강조 표시**: 중요 정보가 눈에 잘 띄어야 함
5. **여백**: 적절한 여백으로 답답하지 않아야 함

---

## 13. 예제 코드 (전체)

### 13.1 CSS 전체 구조

```css
<style>
/* PC 스타일 */
.card-header, .card-body {
    padding: 4px;
}

/* 모바일 반응형 스타일 */
@media (max-width: 768px) {
    /* 1. 기본 레이아웃 */
    html, body {
        max-width: 100vw !important;
        overflow-x: hidden !important;
        font-size: 16px !important;
    }

    .container-fluid {
        max-width: 100vw !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
        overflow-x: hidden !important;
    }

    .row {
        max-width: 100vw !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        overflow-x: hidden !important;
    }

    .card {
        max-width: 100% !important;
        overflow-x: hidden !important;
        margin-bottom: 10px !important;
    }

    /* 2. 공지사항 배너 */
    .shadow-lg {
        min-height: auto !important;
        padding: 12px !important;
        max-width: 100% !important;
        overflow: hidden !important;
    }

    .shadow-lg i {
        font-size: 1.8rem !important;
    }

    .shadow-lg .text-white {
        font-size: 1rem !important;
        line-height: 1.4 !important;
    }

    .shadow-lg .badge {
        font-size: 1rem !important;
        padding: 5px 10px !important;
    }

    .shadow-lg img {
        display: none !important;
    }

    /* 3. 제목 및 버튼 */
    .d-flex h5 {
        font-size: 1.1rem !important;
        white-space: nowrap !important;
    }

    .btn-sm {
        font-size: 0.85rem !important;
        padding: 0.4rem 0.6rem !important;
        white-space: nowrap !important;
    }

    /* 4. 입력 필드 */
    #fromdate, #todate {
        width: 110px !important;
        font-size: 0.9rem !important;
        padding: 0.4rem !important;
    }

    #search {
        width: 110px !important;
        font-size: 0.9rem !important;
        padding: 0.4rem !important;
    }

    .form-select {
        font-size: 0.9rem !important;
        height: 32px !important;
        padding: 0.4rem 0.6rem !important;
    }

    /* 5. 팝업 프레임 */
    #showalignframe,
    #showextractframe,
    #showframe,
    #showsearchtoolframe {
        position: fixed !important;
        left: 50% !important;
        top: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 95% !important;
        max-width: 400px !important;
        z-index: 9999 !important;
        max-height: 80vh !important;
        overflow-y: auto !important;
    }

    #showalign, #showextract, #showdate, #showsearchtool {
        min-width: 70px !important;
    }

    /* 6. 카드 레이아웃 정리 */
    .card-body {
        padding: 12px !important;
    }

    .card-body .d-flex {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
    }

    .card-body > .d-flex:first-of-type {
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 15px !important;
        padding-bottom: 12px !important;
        border-bottom: 2px solid #e9ecef !important;
    }

    .card-body > .d-flex {
        margin-bottom: 10px !important;
    }

    .card-body .d-flex:has(#fromdate) {
        display: grid !important;
        grid-template-columns: 1fr auto 1fr !important;
        gap: 8px !important;
        align-items: center !important;
    }

    #fromdate, #todate {
        width: 100% !important;
    }

    .card-body .d-flex:has(#search) {
        display: grid !important;
        grid-template-columns: auto 1fr auto !important;
        gap: 8px !important;
        align-items: center !important;
    }

    #find {
        width: auto !important;
        min-width: 80px !important;
    }

    #search {
        width: 100% !important;
    }

    #searchBtn {
        white-space: nowrap !important;
    }

    .card-body > .d-flex.justify-content-center {
        background: #f8f9fa !important;
        padding: 10px !important;
        border-radius: 8px !important;
        margin: 8px 0 !important;
    }

    /* 7. 버튼 영역 줄바꿈 */
    .d-flex.justify-content-center {
        flex-wrap: wrap !important;
        overflow-x: visible !important;
        gap: 0.4rem !important;
        justify-content: flex-start !important;
    }

    .d-flex.justify-content-center.align-items-center {
        justify-content: center !important;
    }

    /* 8. DataTables */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        display: none !important;
    }

    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.9rem !important;
        margin-top: 15px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.7rem !important;
        margin: 0 2px !important;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 0.9rem !important;
        text-align: center !important;
        margin-top: 10px !important;
        margin-bottom: 10px !important;
    }

    /* 9. 테이블 카드 레이아웃 */
    #myTable thead {
        display: none;
    }

    #myTable,
    #myTable tbody,
    #myTable tr,
    #myTable td {
        display: block;
        width: 100%;
    }

    #myTable tr {
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 14px;
        overflow: hidden;
    }

    /* 10. 필드 숨기기 (프로젝트별 수정) */
    #myTable td:nth-child(1),
    #myTable td:nth-child(2) {
        display: none !important;
    }

    /* 11. 카드 내 필드 스타일 */
    #myTable td {
        text-align: left !important;
        padding: 12px !important;
        border: none !important;
        position: relative;
        padding-left: 35% !important;
        white-space: normal !important;
        word-wrap: break-word;
        min-height: 40px;
        font-size: 1rem !important;
        line-height: 1.6 !important;
    }

    #myTable td:before {
        content: attr(data-label);
        position: absolute;
        left: 12px;
        width: 30%;
        padding-right: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 600;
        color: #6b7280;
        font-size: 0.85rem;
    }

    #myTable td:after {
        content: ':';
        position: absolute;
        left: 32%;
        font-weight: bold;
        color: #9ca3af;
    }

    #myTable td:first-child {
        display: none !important;
    }

    #myTable td:first-child:after {
        display: none;
    }

    #myTable td:first-child:before {
        display: none;
    }

    /* 12. 중요 필드 강조 (프로젝트별 수정) */
    #myTable td:nth-child(3) {
        font-weight: 600;
        color: #495057;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 12px !important;
        margin-bottom: 8px;
    }

    #myTable td:nth-child(13) {
        background: #e7f3ff;
        font-weight: 700;
        font-size: 1.05rem !important;
        color: #0056b3;
        padding: 14px 12px !important;
        padding-left: 12px !important;
        margin: 8px 0;
        border-radius: 4px;
        border-left: 4px solid #0056b3;
        display: block !important;
    }

    #myTable td:nth-child(13):before {
        position: static !important;
        display: block !important;
        width: 100% !important;
        margin-bottom: 6px;
        font-size: 0.85rem !important;
        color: #6b7280 !important;
        font-weight: 600 !important;
    }

    #myTable td:nth-child(13):after {
        display: none !important;
    }

    /* 13. 기타 */
    .badge {
        font-size: 0.85rem !important;
        padding: 0.3rem 0.6rem !important;
    }

    ion-icon {
        font-size: 1.2rem !important;
        vertical-align: middle;
    }

    .table-responsive {
        overflow-x: visible !important;
    }
}
</style>
```

---

## 14. 참고 자료

### 14.1 적용된 파일
- **원본**: `C:\Project\mirae8440\www\work\list.php`
- **작업일**: 2025-01-12
- **기준 해상도**: 768px 이하

### 14.2 주요 개선사항 요약
1. ✅ 가로 스크롤 완전 제거
2. ✅ 버튼 줄바꿈으로 모든 버튼 표시
3. ✅ 테이블을 카드 형식으로 변경
4. ✅ 필수 정보만 표시 (5-6개 필드)
5. ✅ 중요 정보 색상 강조
6. ✅ 큰 폰트와 넉넉한 여백
7. ✅ Grid 레이아웃으로 깔끔한 정렬
8. ✅ DataTables 불필요한 컨트롤 숨김
9. ✅ 팝업 중앙 정렬
10. ✅ 터치 친화적 크기

### 14.3 다음 적용 대상 메뉴
- `estimate/list.php` - 견적 목록
- `order/list.php` - 주문 목록
- `delivery/list.php` - 배송 목록
- 기타 목록형 페이지

---

## 15. 문의 및 지원

모바일 최적화 관련 문의사항이나 추가 개선사항은 개발팀에 문의하세요.

**문서 버전**: 1.0
**최종 수정일**: 2025-01-12
**작성자**: Claude (AI Assistant)
