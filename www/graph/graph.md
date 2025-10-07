# Graph 모듈 디자인 가이드

미래기업 ERP 시스템의 그래프 모듈 디자인 표준 가이드입니다. (Highcharts 기반)

## 디자인 시스템

### 색상 팔레트 (Dashboard Cyan/Light Blue Theme)
```css
:root {
    --primary-blue: #0288d1;     /* Material Design Light Blue 600 */
    --secondary-blue: #0277bd;   /* Material Design Light Blue 700 */
    --light-blue: #b3e5fc;       /* Material Design Light Blue 100 */
    --glass-bg: rgba(224, 242, 254, 0.3);        /* Light Blue 50 with opacity */
    --glass-border: rgba(176, 230, 247, 0.5);    /* Light Blue 200 with opacity */
    --shadow: 0 2px 12px rgba(2, 136, 209, 0.08); /* Primary blue shadow */
    --text-primary: #01579b;     /* Material Design Light Blue 900 */
    --text-secondary: #0277bd;   /* Material Design Light Blue 700 */
}
```

**실제 색상 정보:**
- 기본 테마: Material Design Light Blue 계열
- 주색상: Light Blue 600 (#0288d1) - 밝은 하늘색
- 보조색상: Light Blue 700 (#0277bd) - 진한 하늘색
- 배경색: Light Blue 50 (#e0f2fe) - 매우 연한 하늘색

### 배경 및 컨테이너
```css
body {
    background: linear-gradient(135deg, #e0f2fe 0%, #f1f8fe 100%);
    overflow-x: hidden;
}

.container-fluid {
    max-width: 100%;
    overflow-x: hidden;
    padding: 0.9rem;
}
```

### 글래스모피즘 효과
```css
.glass-container {
    background: linear-gradient(135deg, var(--glass-bg), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    box-shadow: var(--shadow);
}
```

## 타이포그래피

### 기본 폰트 크기 (10% 축소 적용)
```css
/* 차트 제목 */
.compact-chart-title {
    font-size: 0.81rem;
    font-weight: 600;
    color: var(--text-primary);
}

/* 테이블 헤더 */
.table th {
    font-size: 0.81rem;
    font-weight: 600;
    padding: 0.54rem;
}

/* 테이블 데이터 */
.table td {
    font-size: 1.0125rem;
    padding: 0.675rem;
}

/* 라디오 버튼 라벨 (2배 크기) */
.form-check-label {
    font-size: 0.9rem !important;
    font-weight: 500;
}

/* 라디오 버튼 */
.form-check-input {
    width: 1.25rem;
    height: 1.25rem;
}
```

### 컴팩트 배지
```css
.compact-badge {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}
```

## 레이아웃

### 8:4 그리드 구조 (업체별 월별 수주금액)
```html
<div class="card-body row">
    <div class="col-md-8">
        <!-- 차트 영역 -->
        <div class="compact-chart-container">
            <canvas class="chart-canvas"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <!-- 테이블 영역 -->
        <div class="table-container">
            <table class="table">...</table>
        </div>
    </div>
</div>
```

### 차트 컨테이너
```css
.compact-chart-container {
    background: linear-gradient(135deg, var(--glass-bg), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.9rem;
    box-shadow: var(--shadow);
}

.chart-canvas {
    height: 280px !important;
    border-radius: 8px;
}
```

## Highcharts 설정

### 기본 설정
```javascript
Highcharts.chart('chartId', {
    chart: {
        type: 'column', // 또는 'line'
        backgroundColor: 'rgba(255, 255, 255, 0.9)'
    },
    title: {
        text: '차트 제목',
        style: { fontSize: '14px', fontWeight: '600', color: '#01579b' }
    },
    series: [{
        name: '데이터',
        data: [데이터 배열],
        color: '#0288d1' // Material Design Light Blue 600
    }],
    credits: { enabled: false }
});
```

### 상세 Highcharts 설정
```javascript
Highcharts.chart('chartId', {
    chart: {
        type: 'column',
        backgroundColor: 'rgba(255, 255, 255, 0.9)'
    },
    title: {
        text: '월별 수주금액 추이 (천원)',
        style: { fontSize: '14px', fontWeight: '600', color: '#01579b' }
    },
    xAxis: {
        categories: ['월별 라벨'],
        labels: { style: { fontSize: '10px', color: '#01579b' } }
    },
    yAxis: {
        title: { text: '천원', style: { fontSize: '10px', color: '#01579b' } },
        labels: { style: { fontSize: '10px', color: '#01579b' } }
    },
    series: [{
        name: '수주금액',
        data: [데이터/1000], // 천원 단위 변환
        color: '#0288d1'
    }],
    tooltip: {
        formatter: function() {
            return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' 천원</b>';
        }
    },
    legend: { enabled: false },
    credits: { enabled: false }
});
```

## 금액 단위 처리 (천원 표시)

### PHP 데이터 변환
```php
// 테이블 표시
<?= number_format($amount/1000) ?>

// 차트 데이터
<?= json_encode(array_map(function($x){return $x/1000;}, $data)) ?>

// 합계 표시
합계: <?= number_format(array_sum($data)/1000) ?>천원
```

### Highcharts 툴팁
```javascript
tooltip: {
    formatter: function() {
        return this.series.name + ': <b>' + Highcharts.numberFormat(this.y, 0) + ' 천원</b>';
    }
}
```

### 레이블 및 제목
- 테이블 헤더: `수주금액(천원)`
- 차트 범례: `월별 수주금액(천원)`
- 차트 제목: `월별 수주금액 추이 (천원)`

## 테이블 스타일

### 기본 테이블
```css
.table {
    margin-bottom: 0;
    background: transparent;
}

.table th {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    font-size: 0.81rem;
    font-weight: 600;
    border: none;
    padding: 0.54rem;
}

.table td {
    font-size: 1.0125rem;
    padding: 0.675rem;
    border-color: var(--glass-border);
    background: rgba(255, 255, 255, 0.5);
}

.table-secondary {
    background: linear-gradient(135deg, var(--light-blue), var(--secondary-blue)) !important;
    color: white;
}
```

### 테이블 컨테이너
```css
.table-container {
    background: linear-gradient(135deg, var(--glass-bg), rgba(255, 255, 255, 0.05));
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem;
    margin-left: 1rem;
}
```

## 반응형 디자인

### 스크롤 방지
```css
body, .container-fluid {
    overflow-x: hidden;
}
```

### 모바일 최적화
```css
@media (max-width: 768px) {
    .col-md-8, .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .table-container {
        margin-left: 0;
    }
}
```

## 적용 방법

### 1. Highcharts 라이브러리 추가
```html
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
```

### 2. HTML 구조
```html
<!-- Chart.js canvas 대신 div 사용 -->
<div id="chartMain" class="chart-container"></div>
```

### 3. CSS 간소화
- 불필요한 glassmorphism 효과 제거
- chart-container 클래스 추가
- 복잡한 애니메이션 효과 제거

### 4. 새 파일 생성 시
1. Highcharts 라이브러리 포함
2. Material Design Light Blue 색상 팔레트 적용
3. 금액 표시 시 항상 천원 단위로 처리
4. 8:4 그리드 레이아웃 적용 (차트:테이블)

## 파일 목록

현재 이 디자인이 적용된 파일:
- `monthly_jamb.php` - JAMB 수주현황 분석
- `monthly_ceiling.php` - 조명천장/본천장 수주현황 분석

## Claude Code 재사용 프롬프트

```
이 graph.md 파일의 디자인 가이드를 기반으로 [파일명]에 다음을 적용해주세요:
1. Material Design Light Blue 테마 (Cyan/하늘색 계열)
2. Highcharts로 차트 구현 (Chart.js 대신)
3. 금액 단위를 천원으로 변경 (모든 금액에 /1000 적용)
4. 8:4 그리드 레이아웃 (차트:테이블)
5. 간소화된 CSS (불필요한 효과 제거)
6. 가로 스크롤 방지

필수 적용사항:
- Highcharts 라이브러리 포함
- canvas → div 변경
- PHP 금액 처리: array_map(function($x){return $x/1000;}, $data)
- 색상: #0288d1 (Light Blue 600), #0277bd (Light Blue 700)
```

---

*이 가이드는 미래기업 ERP 시스템의 그래프 모듈 일관성을 위해 작성되었습니다.*
*수정 시 기존 적용 파일들과의 일관성을 유지해주세요.*