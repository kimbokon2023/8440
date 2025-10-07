# UI/UX Design Documentation - Light & Subtle Theme

## 프로젝트 개요
미래기업 ERP 시스템의 라이트 & 서틀 디자인 시스템 - 전체 시스템에 적용된 통일된 색상 톤앤매너

## 디자인 컨셉

### 🎨 **Color Palette (Light & Subtle Theme)**
```css
:root { 
    /* Primary Colors - Much lighter and more subtle */
    --dashboard-primary: #f8fafc;        /* Very light blue-gray */
    --dashboard-secondary: #f1f5f9;      /* Light blue-gray */
    --dashboard-accent: #64748b;         /* Muted slate blue */
    --dashboard-accent-light: #94a3b8;   /* Lighter slate */

    /* Text Colors - Softer tones */
    --dashboard-text: #334155;           /* Soft dark gray */
    --dashboard-text-secondary: #64748b; /* Medium gray */
    --dashboard-text-muted: #94a3b8;     /* Light gray */

    /* Border and Background */
    --dashboard-border: #e2e8f0;         /* Very light border */
    --dashboard-hover: #f8fafc;          /* Subtle hover */
    --dashboard-shadow: 0 1px 3px rgba(51, 65, 85, 0.04); /* Much lighter shadow */

    /* Status Colors - Muted versions */
    --status-success: #84cc16;           /* Muted green */
    --status-info: #0ea5e9;              /* Muted blue */
    --status-warning: #f59e0b;           /* Muted orange */
    --status-danger: #ef4444;            /* Muted red */

    /* Highlight Colors */
    --cyan-light: #f0fbff;               /* Very light cyan for thead */
    --cyan-medium: #c7f0ff;              /* Medium cyan for accents */

    /* Button Text Colors - Optimal Contrast */
    --btn-text-on-dark: #ffffff;         /* White text on dark backgrounds */
    --btn-text-on-light: #1e293b;        /* Dark text on light backgrounds */
    --btn-text-on-accent: #ffffff;       /* White text on accent colors */
    --btn-hover-text: #ffffff;           /* White text on hover states */
}
```

### 🎭 **Design Philosophy**
- **Light & Subtle**: 최소한의 색상 강도로 전문적이고 차분한 분위기
- **Professional Elegance**: 비즈니스 환경에 적합한 세련된 디자인
- **Soft Interactions**: 미세하고 부드러운 호버 효과
- **Readable Typography**: 높은 가독성을 위한 적절한 색상 대비
- **Consistent Spacing**: 일관된 패딩과 마진 시스템
- **Subtle Shadows**: 깊이감을 주되 과하지 않은 그림자 효과
- **Calm Colors**: 시각적 피로를 줄이는 차분한 색조

## UI 컴포넌트

### 1. **Modern Management Card** 📊
```css
.modern-management-card {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    overflow: hidden;
    transition: all 0.2s ease;
    margin-bottom: 1rem;
}

.modern-management-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}
```

**구조**:
```
┌─────────────────────────────┐
│ Header (Light blue-gray)    │
├─────────────────────────────┤
│ Content Area                │
│ - Tables                    │
│ - Data displays             │
│ - Interactive elements      │
└─────────────────────────────┘
```

### 2. **Card Headers** 📋
```css
.modern-dashboard-header {
    background: var(--dashboard-secondary);  /* Light blue-gray */
    color: #000;                            /* Black text */
    padding: 0.25rem;                       /* Minimal padding */
    text-align: center;
    font-size: 0.9rem;
    font-weight: 500;
    letter-spacing: 0.3px;
}
```

**특징**:
- 매우 압축된 패딩 (0.25rem)
- 검정 텍스트로 명확한 가독성
- 모든 하위 요소 (h1~h6, span, links) 검정색 통일

### 3. **Table Styling** 📈
```css
.modern-dashboard-table th {
    background: #f0fbff;  /* Very light cyan */
    color: var(--dashboard-text);
    padding: 0.5rem;
    font-size: 0.8rem;
    font-weight: 500;
    border: none;
    text-align: center;
}

.modern-dashboard-table tr:hover td {
    background-color: var(--dashboard-hover);
}
```

### 4. **Button Styling** 🔘
```css
.modern-toolbar-btn {
    background: var(--gradient-accent);
    color: var(--btn-text-on-accent) !important;  /* White text on dark */
    border: none;
    border-radius: 6px;
    padding: 0.4rem 0.8rem;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(51, 65, 85, 0.1);
}

.modern-toolbar-btn:hover {
    color: var(--btn-hover-text) !important;  /* Ensure white text */
    transform: translateY(-1px);
    opacity: 0.9;
}

.modern-quality-goal-btn {
    background: var(--dashboard-secondary);
    color: var(--btn-text-on-light) !important;  /* Dark text on light */
    border: 1px solid var(--dashboard-border);
    border-radius: 8px;
}

.modern-quality-goal-btn:hover {
    background: #c7f0ff;  /* Medium cyan on hover */
    color: var(--btn-text-on-light) !important;  /* Keep dark text */
}
```

## 색상 시스템

### **Primary Color Hierarchy**
1. **Card Headers**: `var(--dashboard-secondary)` (#f1f5f9) - Light blue-gray
2. **Table Headers**: `#f0fbff` - Very light cyan
3. **Hover Accents**: `#c7f0ff` - Medium cyan
4. **Text**: `#000` (Black) for all headers and important text
5. **Borders**: `var(--dashboard-border)` (#e2e8f0) - Very light

### **Interaction Colors**
```css
/* Hover States */
:hover {
    background: #c7f0ff;  /* Medium cyan */
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

/* Focus States */
:focus {
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 0.2rem rgba(100, 116, 139, 0.25);
}
```

## 타이포그래피

### **Font Hierarchy**
```css
/* Card Headers */
.modern-dashboard-header {
    font-size: 0.9rem;
    font-weight: 500;
    color: #000;
}

/* Table Headers */
.modern-dashboard-table th {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--dashboard-text);
}

/* Table Data */
.modern-dashboard-table td {
    font-size: 0.85rem;
    color: var(--dashboard-text);
}

/* Data Values */
.modern-data-value {
    font-weight: 500;
    color: var(--dashboard-text);
    font-size: 0.9rem;
}

/* Data Details */
.modern-data-details {
    color: var(--dashboard-text-secondary);
    font-size: 0.8rem;
    line-height: 1.4;
}
```

## 레이아웃 원칙

### **Spacing System**
- **Card Padding**: 1.5rem (desktop), 1rem (mobile)
- **Header Padding**: 0.25rem (매우 압축됨)
- **Table Cell Padding**: 0.5rem
- **Button Padding**: 0.4rem 0.8rem
- **Margin Bottom**: 1rem (cards), 0.5rem (components)

### **Border Radius**
- **Cards**: 12px
- **Buttons**: 6-8px
- **Small Elements**: 4px

### **Shadows**
```css
/* Default Shadow */
--dashboard-shadow: 0 1px 3px rgba(51, 65, 85, 0.04);

/* Hover Shadow */
box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);

/* Button Shadow */
box-shadow: 0 1px 3px rgba(51, 65, 85, 0.1);
```

## 애니메이션

### **Micro Interactions**
```css
/* Hover Animations */
.modern-management-card:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* Button Hover */
.modern-toolbar-btn:hover {
    transform: translateY(-1px);
    opacity: 0.9;
}

/* Table Row Hover */
.clickable-row:hover {
    background-color: var(--dashboard-hover);
    transition: background-color 0.2s ease;
}
```

**특징**:
- 매우 미세한 움직임 (1px translateY)
- 빠른 transition (0.2s)
- 부드러운 easing

## 반응형 디자인

### **Mobile Adaptations**
```css
@media (max-width: 768px) {
    .modern-management-card {
        margin-bottom: 0.5rem;
    }

    .modern-dashboard-header {
        padding: 0.5rem;
        font-size: 0.8rem;
    }

    .modern-dashboard-table th,
    .modern-dashboard-table td {
        padding: 0.3rem;
        font-size: 0.75rem;
    }

    .modern-toolbar-btn {
        padding: 0.3rem 0.6rem;
        font-size: 0.8rem;
        margin: 0.1rem;
    }
}
```

## 그래프 및 차트 시스템

### **Highcharts 기반 데이터 시각화**
**설계 원칙**: Light & Subtle 테마와 조화를 이루는 정교한 데이터 시각화

```css
/* Chart Container - Light & Subtle Theme */
.compact-chart-container {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    padding: 0.9rem;
    box-shadow: var(--dashboard-shadow);
    transition: all 0.2s ease;
}

.compact-chart-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(51, 65, 85, 0.08);
}

.chart-container {
    background: white;
    border-radius: 8px;
    height: 280px;
    overflow: hidden;
}

/* Chart Canvas Alternative for Div-based charts */
.chart-canvas {
    height: 280px !important;
    border-radius: 8px;
}
```

### **Highcharts 테마 설정**
```javascript
// Light & Subtle 테마용 Highcharts 기본 설정
Highcharts.setOptions({
    chart: {
        backgroundColor: 'rgba(255, 255, 255, 0.95)',
        style: {
            fontFamily: '"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif'
        }
    },
    title: {
        style: {
            fontSize: '14px',
            fontWeight: '600',
            color: '#334155',
            fontFamily: '"Inter", sans-serif'
        }
    },
    colors: [
        '#64748b', // dashboard-accent
        '#94a3b8', // dashboard-accent-light
        '#475569', // darker slate
        '#6b7280', // gray-500
        '#8b5cf6', // purple-500
        '#06b6d4', // cyan-500
        '#10b981', // emerald-500
        '#f59e0b'  // amber-500
    ],
    xAxis: {
        labels: {
            style: { fontSize: '11px', color: '#64748b' }
        },
        gridLineColor: '#e2e8f0',
        lineColor: '#e2e8f0'
    },
    yAxis: {
        labels: {
            style: { fontSize: '11px', color: '#64748b' }
        },
        gridLineColor: '#e2e8f0',
        title: {
            style: { fontSize: '12px', color: '#64748b' }
        }
    },
    legend: {
        itemStyle: { fontSize: '11px', color: '#64748b' }
    },
    tooltip: {
        backgroundColor: 'rgba(255, 255, 255, 0.96)',
        borderColor: '#e2e8f0',
        style: { fontSize: '11px', color: '#334155' }
    }
});

// 개별 차트 구현 예시
Highcharts.chart('chartId', {
    chart: {
        type: 'column' // 또는 'line', 'area', 'pie' 등
    },
    title: {
        text: '월별 수주금액 추이 (천원)'
    },
    xAxis: {
        categories: monthLabels
    },
    yAxis: {
        title: { text: '천원' }
    },
    series: [{
        name: '수주금액',
        data: chartData, // 이미 천원 단위로 변환된 데이터
        color: '#64748b'
    }],
    tooltip: {
        formatter: function() {
            return '<b>' + this.series.name + '</b><br/>' +
                   this.x + ': ' + Highcharts.numberFormat(this.y, 0) + ' 천원';
        }
    },
    credits: { enabled: false }
});
```

### **차트 타입별 설정**
```javascript
// 컬럼 차트 (막대 그래프)
{
    chart: { type: 'column' },
    plotOptions: {
        column: {
            borderRadius: 2,
            borderWidth: 0,
            groupPadding: 0.1,
            pointPadding: 0.05
        }
    }
}

// 라인 차트 (선 그래프)
{
    chart: { type: 'line' },
    plotOptions: {
        line: {
            lineWidth: 2,
            marker: {
                radius: 4,
                fillColor: '#ffffff',
                lineWidth: 2
            }
        }
    }
}

// 파이 차트
{
    chart: { type: 'pie' },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: { fontSize: '11px', color: '#334155' }
            },
            showInLegend: true
        }
    }
}
```

### **8:4 그리드 레이아웃 (차트:테이블)**
**구조**: 차트(8) : 데이터 테이블(4) 비율로 시각적 균형 제공
```html
<div class="row">
    <div class="col-md-8">
        <div class="compact-chart-container">
            <div id="chartMain" class="chart-container"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="table-container">
            <table class="modern-dashboard-table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">월</th>
                        <th class="text-center">수주금액(천원)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 데이터 행들 -->
                </tbody>
            </table>
        </div>
    </div>
</div>
```

### **테이블 컨테이너 스타일링**
```css
.table-container {
    background: linear-gradient(135deg, #ffffff, var(--dashboard-primary));
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    padding: 1rem;
    margin-left: 1rem;
    box-shadow: var(--dashboard-shadow);
    height: fit-content;
}

.table-container .table {
    margin-bottom: 0;
    background: transparent;
}

.table-container .table th {
    background: #f0fbff !important;
    color: var(--dashboard-text) !important;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.6rem;
    border: 1px solid var(--dashboard-border);
}

.table-container .table td {
    font-size: 0.9rem;
    padding: 0.7rem;
    border: 1px solid var(--dashboard-border);
    background: rgba(255, 255, 255, 0.8);
    color: var(--dashboard-text);
}

@media (max-width: 768px) {
    .table-container {
        margin-left: 0;
        margin-top: 1rem;
    }
}
```

### **금액 단위 표준화 (천원 기준)**
**천원 단위 처리**: 모든 금액 데이터는 천원 단위로 표시하여 가독성 향상

```php
// PHP 테이블 데이터 표시
<?= number_format($amount/1000) ?> 천원

// PHP 차트 데이터 변환 (JavaScript 배열)
<?= json_encode(array_map(function($x) {
    return round($x/1000); // 천원 단위로 반올림
}, $amounts_array)) ?>

// PHP 합계 계산
$total_amount = array_sum($amounts_array);
echo number_format($total_amount/1000) . ' 천원';

// PHP 월별 데이터 처리 예시
$monthly_data = [];
foreach($raw_data as $month => $amount) {
    $monthly_data[] = [
        'month' => $month . '월',
        'amount' => round($amount/1000),
        'display' => number_format($amount/1000) . ' 천원'
    ];
}
```

```javascript
// JavaScript Highcharts 툴팁 포맷팅
tooltip: {
    formatter: function() {
        return '<b>' + this.series.name + '</b><br/>' +
               this.x + ': <b>' +
               Highcharts.numberFormat(this.y, 0) + ' 천원</b>';
    }
}

// Y축 레이블 포맷팅
yAxis: {
    title: { text: '수주금액 (천원)' },
    labels: {
        formatter: function() {
            return Highcharts.numberFormat(this.value, 0);
        }
    }
}
```

### **데이터 검증 및 에러 처리**
```php
// 안전한 데이터 변환
function safe_amount_convert($amount) {
    $clean_amount = str_replace(',', '', $amount);
    $numeric_amount = is_numeric($clean_amount) ? floatval($clean_amount) : 0;
    return round($numeric_amount / 1000);
}

// 배열 데이터 안전 변환
function convert_amounts_to_thousands($amounts_array) {
    return array_map(function($amount) {
        return safe_amount_convert($amount);
    }, array_filter($amounts_array, 'is_numeric'));
}

// JavaScript에서 null/undefined 처리
const chartData = <?= json_encode($chart_data) ?> || [];
const safeChartData = chartData.map(val => val || 0);
```

### **차트 타이포그래피 시스템**
```css
/* 차트 제목 */
.compact-chart-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dashboard-text);
    margin-bottom: 1rem;
    text-align: center;
}

/* 차트 부제목 */
.chart-subtitle {
    font-size: 0.85rem;
    font-weight: 400;
    color: var(--dashboard-text-secondary);
    text-align: center;
    margin-bottom: 0.5rem;
}

/* 배지 스타일 */
.compact-badge {
    background: var(--dashboard-accent);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 16px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
    margin: 0.2rem;
}

.compact-badge.secondary {
    background: var(--dashboard-secondary);
    color: var(--dashboard-text);
    border: 1px solid var(--dashboard-border);
}

/* 데이터 라벨 */
.data-label {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--dashboard-text-secondary);
}

.data-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--dashboard-text);
}

/* 차트 범례 커스텀 */
.chart-legend {
    font-size: 0.8rem;
    color: var(--dashboard-text-secondary);
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 0.5rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}
```

### **반응형 차트 설정**
```javascript
// Highcharts 반응형 설정
responsive: {
    rules: [{
        condition: {
            maxWidth: 768
        },
        chartOptions: {
            title: {
                style: { fontSize: '12px' }
            },
            xAxis: {
                labels: {
                    style: { fontSize: '9px' },
                    rotation: -45
                }
            },
            yAxis: {
                labels: { style: { fontSize: '9px' } },
                title: { style: { fontSize: '10px' } }
            },
            legend: {
                itemStyle: { fontSize: '9px' }
            }
        }
    }]
}
```

## 품질 가이드라인

### **Design Standards**
- **Color Contrast**: WCAG AA 준수 (4.5:1 이상)
- **Touch Targets**: 최소 44px (모바일)
- **Loading Performance**: CSS 최적화로 빠른 렌더링
- **Consistency**: 모든 페이지에서 동일한 컴포넌트 사용
- **Chart Integration**: Highcharts와 기본 테마의 시각적 일관성

### **Accessibility**
- **Text Contrast**: 검정 텍스트로 높은 가독성 (WCAG AA+ 준수)
- **Button Contrast**: 명확한 보색 대비로 텍스트 가시성 보장
  - 어두운 배경: 흰색 텍스트 (`--btn-text-on-accent`)
  - 밝은 배경: 어두운 텍스트 (`--btn-text-on-light`)
- **Focus Indicators**: 명확한 포커스 상태 (2px 아웃라인)
- **Color Independence**: 색상에만 의존하지 않는 정보 전달
- **Chart Accessibility**: 데이터 테이블과 차트의 병행 제공
- **Interactive States**: 모든 상태(hover, focus, active)에서 텍스트 가독성 유지

## CSS 파일 구조

### **Consolidated CSS**
```
css/dashboard-style.css
├── CSS Variables (색상 팔레트)
├── Management Cards
├── Headers & Typography
├── Tables
├── Buttons & Interactions
├── Forms & Inputs
├── Badges & Status
├── Chart Containers (Highcharts 스타일)
├── Compact Typography
├── Photo Frames
├── Organization Charts
├── Responsive Breakpoints
└── Print Styles
```

## 사용 가이드라인

### **Light & Subtle 테마 사용 권장 시나리오**
- ✅ **대시보드**: 메인 관리 페이지 및 통계 화면
- ✅ **데이터 관리**: 목록, 상세보기, 편집 인터페이스
- ✅ **비즈니스 도구**: 업무용 전문 화면
- ✅ **장시간 사용**: 시각적 피로도 최소화 필요한 화면
- ✅ **보고서**: 프린트 및 공유 가능한 문서형 화면
- ✅ **차트 화면**: 데이터 시각화가 중심인 페이지

### **Highcharts 차트 시스템 사용 권장**
- ✅ **월별/연간 추이**: 시계열 데이터 표시
- ✅ **비교 분석**: 여러 데이터셋 비교
- ✅ **비율 표시**: 파이 차트, 도넛 차트
- ✅ **상세 분석**: 드릴다운 기능 필요한 경우
- ✅ **대용량 데이터**: 1000+ 데이터 포인트
- ✅ **인터랙션**: 사용자 상호작용이 중요한 차트

### **Color Usage Rules**
- **Card Headers**: Light blue-gray 배경 + 검정 텍스트
- **Table Headers**: Very light cyan 배경
- **Hover Effects**: Medium cyan 배경
- **Text**: 모든 중요 텍스트는 검정색 (#000)
- **Borders**: 매우 연한 회색 (#e2e8f0)

### **구현 체크리스트**

#### **기본 Light & Subtle 테마**
- [ ] **CSS 링크**: `<link rel="stylesheet" href="../css/dashboard-style.css">`
- [ ] **Card 구조**: `.modern-management-card` + `.modern-dashboard-header`
- [ ] **테이블 스타일**: `.modern-dashboard-table` + `#f0fbff` header 배경
- [ ] **버튼 클래스**: `.btn-dark`, `.btn-secondary` 등 적절한 클래스
- [ ] **텍스트 색상**: Header 내 모든 요소 `#000` (검정색)
- [ ] **반응형**: 768px 이하 모바일 최적화

#### **Highcharts 차트 시스템**
- [ ] **라이브러리 로드**: Highcharts + exporting 모듈
- [ ] **HTML 구조**: `<div id="chartId" class="chart-container"></div>`
- [ ] **8:4 레이아웃**: 차트(col-md-8) + 테이블(col-md-4)
- [ ] **색상 설정**: `#64748b` (dashboard-accent) 기본 색상
- [ ] **금액 단위**: 모든 금액 데이터 천원 단위(/1000)
- [ ] **툴팁 포맷**: `Highcharts.numberFormat(value, 0) + ' 천원'`
- [ ] **반응형 차트**: 모바일에서 폰트 크기 자동 조정
- [ ] **데이터 검증**: null/undefined 값 처리

#### **품질 보증**
- [ ] **접근성**: 색상 대비 4.5:1 이상
- [ ] **성능**: 차트 로딩 시간 3초 이하
- [ ] **호환성**: Chrome, Firefox, Safari, Edge 테스트
- [ ] **데이터 정확성**: 원본 데이터와 차트 데이터 일치
- [ ] **에러 처리**: 데이터 없을 때 적절한 메시지 표시

## 확장 및 발전 계획

### **테마 확장 가능성**
- **🌙 다크 모드**: CSS 변수 기반 다크 테마 (2025 Q2 계획)
- **🎨 부서별 테마**: 각 부서별 브랜드 색상 커스터마이징
- **🎯 고대비 모드**: 시각 접근성 향상 테마 (WCAG AAA 준수)
- **📱 모바일 퍼스트**: 터치 최적화 인터페이스
- **🖨️ 프린트 최적화**: 흑백 프린트용 스타일

### **차트 시스템 고도화**
- **📊 고급 차트 타입**:
  - Gantt Chart (일정 관리)
  - Heatmap (활동 패턴)
  - Treemap (계층 데이터)
  - Sankey Diagram (플로우 분석)
- **🎭 애니메이션**: 부드러운 데이터 전환 효과
- **📱 터치 인터랙션**: 모바일 제스처 지원
- **🔄 실시간 업데이트**: WebSocket 기반 라이브 차트
- **📤 Export 기능**: PDF, PNG, SVG 내보내기

### **컴포넌트 라이브러리 확장**
- **📋 고급 테이블**:
  - 정렬, 필터링, 페이징
  - 인라인 편집 기능
  - 가상 스크롤 (대용량 데이터)
- **📝 폼 시스템**:
  - 유효성 검증 UI
  - 다단계 폼 위저드
  - 파일 업로드 컴포넌트
- **🔔 알림 시스템**:
  - Toast 알림
  - 모달 다이얼로그
  - 인라인 메시지
- **📊 대시보드 위젯**:
  - KPI 카드
  - 미니 차트
  - 프로그레스 인디케이터

### **성능 및 최적화**
- **⚡ 지연 로딩**: 화면에 보이는 컴포넌트만 로드
- **🗜️ 압축 최적화**: CSS/JS 번들 크기 최소화
- **🔄 캐싱 전략**: 브라우저 캐싱 활용
- **📱 PWA 준비**: Progressive Web App 지원
- **🔍 SEO 최적화**: 검색 엔진 친화적 마크업

## 기술 스택

### **Core Technologies**
- **CSS**: Custom Properties (CSS Variables), Flexbox, CSS Grid
- **Framework**: Bootstrap 5 (utility classes + custom components)
- **Charts**: Highcharts 11.x (데이터 시각화 전담)
- **JavaScript**: ES6+ (모던 브라우저 지원)
- **PHP**: 7.4+ (서버사이드 데이터 처리)

### **Browser Compatibility**
- **Chrome**: 90+ (완전 지원)
- **Firefox**: 88+ (완전 지원)
- **Safari**: 14+ (완전 지원)
- **Edge**: 90+ (완전 지원)
- **Mobile**: iOS Safari 14+, Chrome Mobile 90+

### **Performance Standards**
- **CSS**: 압축된 단일 파일 (~15KB gzipped)
- **Charts**: 지연 로딩 (lazy loading)
- **Images**: WebP 형식 우선, fallback PNG/JPG
- **Fonts**: 시스템 폰트 우선, 웹폰트는 필요시만
- **JavaScript**: 최소한의 외부 의존성

### **Development Workflow**
- **CSS Architecture**: BEM 방법론 + CSS Custom Properties
- **Version Control**: 모든 스타일 변경사항 추적
- **Testing**: 크로스 브라우저 테스트 필수
- **Documentation**: 모든 컴포넌트 사용법 문서화
- **Maintenance**: 통합 CSS 파일로 쉬운 업데이트

## 프로젝트 적용 현황

### **전체 적용 현황 맵**

#### **✅ Light & Subtle 테마 완료**
```
미래기업 ERP
├── 📊 대시보드
│   ├── ✅ index2.php (메인)
│   └── ✅ css/dashboard-style.css (통합 CSS)
├── 💼 업무 관리
│   ├── ✅ work/statistics.php (통계)
│   ├── ✅ work/list_hpi.php (HPI 정보)
│   ├── ✅ work/estimate.php (단가 관리)
│   └── ✅ work/front_log.php (프론트 로그)
├── 🏗️ 천장 부문
│   ├── ✅ ceiling/front_log.php (프론트 로그)
│   └── ✅ ceiling/work_statistics.php (제조통계)
└── 📈 그래프 시스템
    ├── ✅ monthly_jamb.php (JAMB 수주)
    ├── ✅ monthly_ceiling.php (천장 수주)
    └── ✅ work/output_statis.php (매출 통계)
```

#### **🔄 진행 예정**
- `QC/` - 품질관리 모듈
- `steel/` - 강재 관리 모듈
- `analysis/` - 분석 모듈
- `member/` - 사용자 관리

#### **📊 통계 요약**
- **완료**: 11개 파일
- **진행 중**: 2개 파일
- **전체 진행률**: ~65%
- **예상 완료**: 2025년 2월

### **현재 적용 완료 (Light & Subtle)**
- ✅ `index2.php` - 메인 대시보드
- ✅ `css/dashboard-style.css` - 통합 스타일시트
- ✅ `work/statistics.php` - 통계 페이지
- ✅ `work/list_hpi.php` - HPI 정보
- ✅ `work/estimate.php` - 단가 관리
- ✅ `work/front_log.php` - 프론트 로그
- ✅ `ceiling/front_log.php` - 천장 프론트 로그
- ✅ `ceiling/work_statistics.php` - 천장 제조통계

### **차트 시스템 적용 완료**
- ✅ `monthly_jamb.php` - JAMB 수주현황 (Highcharts)
- ✅ `monthly_ceiling.php` - 천장 수주현황 (Highcharts)
- ✅ `work/output_statis.php` - 매출 통계 (Highcharts)

### **향후 적용 계획**
- 🔄 전체 ERP 시스템의 점진적 Light & Subtle 테마 적용
- 📊 모든 통계 페이지에 Highcharts 시스템 도입
- 📱 모바일 최적화 강화
- ♿ 접근성 개선 (WCAG AA+ 목표)
- 🎨 다크 모드 지원 (선택사항)
- 📈 고급 차트 인터랙션 기능 추가

## Claude Code 재사용 가이드

### **차트 모듈 적용 가이드**

#### **신규 파일 생성 시 프롬프트**
```
[파일명]에 Light & Subtle 테마 기반 Highcharts 차트를 구현해주세요:

🎨 디자인 요구사항:
1. Light & Subtle 색상 팔레트 (#64748b 기본, #94a3b8 보조)
2. Modern Management Card 구조 적용
3. 8:4 그리드 레이아웃 (차트:데이터테이블)
4. 천원 단위 금액 표시 (모든 금액 /1000 처리)

🔧 기술적 구현:
- Highcharts 라이브러리 사용 (Chart.js 대신)
- div 기반 차트 컨테이너 (canvas 대신)
- dashboard-style.css 스타일시트 포함
- 반응형 디자인 (모바일 최적화)
- 가로 스크롤 방지

📊 데이터 처리:
- PHP: array_map(function($x){return round($x/1000);}, $amounts)
- JavaScript: Highcharts.numberFormat(value, 0) + ' 천원'
- 테이블과 차트 데이터 동기화

✅ 품질 기준:
- 색상 접근성 (WCAG AA 준수)
- 일관된 타이포그래피
- 부드러운 호버 효과
- 데이터 검증 및 에러 처리
```

#### **기존 파일 업그레이드 프롬프트**
```
[파일명]을 Light & Subtle + Highcharts 시스템으로 업그레이드해주세요:

🔄 변경 사항:
1. Chart.js → Highcharts 마이그레이션
2. Material Blue → Light & Subtle 색상 변경
3. 기존 레이아웃 유지하되 Modern Management Card 적용
4. 금액 표시 단위 천원으로 통일

⚠️  주의사항:
- 기존 기능 및 데이터 로직 보존
- PHP 변수명 및 구조 최대한 유지
- 사용자 경험 일관성 확보
- 기존 JavaScript 이벤트 핸들러 보존
```

#### **디버깅 및 최적화 프롬프트**
```
[파일명]의 차트 모듈을 디버깅하고 최적화해주세요:

🐛 확인 항목:
- Highcharts 데이터 검증 (null, undefined 처리)
- 천원 단위 변환 정확성
- 모바일 반응형 작동
- 색상 일관성 (Light & Subtle 테마)

⚡ 성능 최적화:
- 불필요한 CSS/JS 제거
- 차트 렌더링 최적화
- 데이터 캐싱 고려
- 메모리 누수 방지
```

### **그래프 모듈 구현 패턴**

#### **1. 기본 구조 템플릿**
```html
<!DOCTYPE html>
<html>
<head>
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../css/dashboard-style.css" type="text/css" />
    <!-- Highcharts Library -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <title>차트 제목</title>
</head>
<body>
    <div class="container">
        <div class="modern-management-card">
            <div class="modern-dashboard-header">
                <h3>차트 제목</h3>
            </div>
            <div class="card-body row">
                <div class="col-md-8">
                    <div class="compact-chart-container">
                        <div id="chartMain" class="chart-container"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="table-container">
                        <!-- 데이터 테이블 -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Highcharts 구현
    </script>
</body>
</html>
```

#### **2. PHP 데이터 처리 패턴**
```php
// 1. 데이터베이스에서 원시 데이터 가져오기
$raw_data = [];
$chart_data = [];
$table_data = [];

// 2. 데이터 변환 및 천원 단위 계산
foreach($db_results as $row) {
    $amount_in_thousands = round($row['amount'] / 1000);
    $chart_data[] = $amount_in_thousands;
    $table_data[] = [
        'label' => $row['month'] . '월',
        'amount' => $amount_in_thousands,
        'display' => number_format($amount_in_thousands) . ' 천원'
    ];
}

// 3. JavaScript로 안전하게 데이터 전달
$js_chart_data = json_encode($chart_data);
$js_labels = json_encode(array_column($table_data, 'label'));
```

#### **3. 일관된 차트 구현**
```javascript
// 표준 차트 설정
function createStandardChart(containerId, title, labels, data) {
    return Highcharts.chart(containerId, {
        chart: { type: 'column' },
        title: { text: title },
        xAxis: { categories: labels },
        yAxis: { title: { text: '천원' } },
        series: [{
            name: '금액',
            data: data,
            color: '#64748b'
        }],
        tooltip: {
            formatter: function() {
                return '<b>' + this.series.name + '</b><br/>' +
                       this.x + ': ' + Highcharts.numberFormat(this.y, 0) + ' 천원';
            }
        },
        credits: { enabled: false }
    });
}

// 사용 예시
const chartData = <?= $js_chart_data ?>;
const chartLabels = <?= $js_labels ?>;
createStandardChart('chartMain', '월별 수주현황', chartLabels, chartData);
```

### **적용된 그래프 모듈 파일**
- ✅ `monthly_jamb.php` - JAMB 수주현황 분석
- ✅ `monthly_ceiling.php` - 조명천장/본천장 수주현황 분석
- ✅ `work/output_statis.php` - 매출 통계 차트
- 🔄 `ceiling/work_statistics.php` - 천장 제조통계 (부분 적용)

### **향후 적용 예정 파일**
- `graph/annual_comparison.php` - 연간 비교 분석
- `graph/department_stats.php` - 부서별 통계
- `graph/quarterly_report.php` - 분기별 보고서

---

## 버전 히스토리

### **Version 4.0 - Enhanced Chart System** *(2025-01-19)*
- ✅ Highcharts 통합 가이드 추가
- ✅ 차트 타입별 상세 설정 문서화
- ✅ 금액 단위 표준화 (천원 기준) 강화
- ✅ 반응형 차트 설정 가이드
- ✅ 데이터 검증 및 에러 처리 패턴
- ✅ 표준 구현 템플릿 제공

### **Version 3.0 - Light & Subtle Theme** *(2025-01-19)*
- 🎨 Light & Subtle 색상 팔레트 도입
- 🏗️ Modern Management Card 시스템
- 📱 모바일 우선 반응형 디자인
- ♿ 접근성 향상 (WCAG AA 준수)
- 📊 기본 차트 시스템 구축

### **Version 2.0 - Component System** *(2025-01-18)*
- 🧩 컴포넌트 기반 아키텍처
- 📋 테이블 스타일링 시스템
- 🔘 버튼 및 인터랙션 가이드
- 📝 타이포그래피 계층 구조

### **Version 1.0 - Foundation** *(2025-01-17)*
- 🎨 기본 색상 시스템 구축
- 🎭 디자인 철학 수립
- 📐 레이아웃 원칙 정의

---

**Author**: Claude Code Assistant
**Current Version**: 4.0 - Enhanced Chart System
**Theme Philosophy**: Professional, Calm, Accessible
**Last Updated**: 2025-01-19
**Next Update**: Chart Animation & Advanced Interactions