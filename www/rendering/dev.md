# ElevatorViz Pro - 기술 문서 (Technical Documentation)

## 1. 프로젝트 개요
**프로젝트명**: 엘리베이터 렌더링 도구 (Panel Nano Banana Rendering)
**경로**: `www/rendering/`
**메인 파일**: `index.php`
**목표**: 사용자가 엘리베이터 레이아웃과 자재 이미지를 업로드하고 설정을 구성하면, Google Gemini 3 Pro 모델을 사용하여 고품질 3D 렌더링을 생성하는 웹 기반 시각화 도구입니다.

## 2. 아키텍처 및 기술 스택
이 프로젝트는 **레거시 PHP 환경 내에 임베딩된 Single-Page Application (SPA)** 형태로 설계되었습니다.

*   **Server-Side (서버 측)**: PHP (v7.4+ 호환)
    *   **진입점**: `index.php`
    *   **의존성**: `../bootstrap.php` (세션 관리, 환경 변수, DB 연결).
    *   **인증**: `$_SESSION`을 직접 사용하여 사용자 로그인 여부 및 레벨(Level) 기반 권한 제어.
    *   **설정**: `gemini_api.txt` (서버에 저장된 API Key를 읽어와 JS에 주입).
*   **Client-Side (클라이언트 측)**: Vanilla JavaScript (ES Modules)
    *   **빌드 과정 없음**: 브라우저에서 직접 실행.
    *   **스타일링**: Tailwind CSS (v3.4) CDN 사용.
    *   **AI SDK**: Google GenAI SDK (`@google/genai`)를 `importmap`으로 로드 (ESM CDN).

## 3. 핵심 구현 내용

### 3.1. 프롬프트 엔지니어링 원리 (Prompt Engineering Principles)
Gemini 3 Pro 모델이 정확한 3D 렌더링을 생성하도록 유도하는 핵심 로직입니다. 단순한 이미지 생성이 아니라, **구조적 정확성**과 **물리적 재질감**을 구현하기 위해 다음과 같은 원리가 적용되었습니다.

1.  **기하학적 구조 보존 (Geometry Preservation)**
    *   **원리**: 생성형 AI는 종종 구조를 창의적으로 왜곡하는 경향이 있습니다. 이를 방지하기 위해 "Reference Layout Structure(참조 레이아웃 구조)" 이미지를 **Ground Truth(절대적 진실)**로 취급하도록 강력한 지시어를 사용합니다.
    *   **프롬프트**: `"You are an expert 3D architectural visualizer. PRIMARY OBJECTIVE: PRESERVE GEOMETRY... Treat this layout image as the ONLY truth for geometry."`
    *   **효과**: 사용자가 업로드한 도면의 선, 비율, 투시가 유지된 채로 텍스처만 입혀집니다.

2.  **멀티모달 매핑 (Multimodal Mapping)**
    *   **원리**: 텍스트와 이미지를 동시에 처리하는 Gemini의 능력을 활용하여, 특정 위치에 특정 자재를 매핑합니다.
    *   **구조**: 
        *   `MATERIAL A`: 출입구 도어 (Elevation Door)
        *   `MATERIAL B{n}`: 판넬 (Panels 1~11)
        *   `MATERIAL C`: 바닥 (Floor)
    *   각 파트별로 이미지 데이터(Base64)와 설명 텍스트를 쌍으로 전송하여 모델이 "이 이미지는 이 부분에 적용해야 한다"는 것을 명확히 인식하게 합니다.

3.  **물리적 반사 로직 (Physics-based Reflection Logic)**
    *   **원리**: 사용자가 슬라이더로 조절하는 반사 강도(0~100%)를 AI가 이해할 수 있는 **광학적/물리적 용어**로 변환합니다.
    *   **구현 (`getReflectionContext`)**:
        *   **Mirror 타입**:
            *   < 30%: "Foggy/Antique Mirror" (뿌얗고 앤티크한 거울)
            *   < 70%: "Standard Polished" (일반적인 폴리싱)
            *   > 70%: "Perfect Chrome/Mirror" (완벽한 거울, 날카로운 반사)
        *   **일반 재질**:
            *   < 20%: "Matte/Flat" (무광)
            *   > 80%: "High Gloss / Wet Look" (유광/젖은 느낌)
            *   그 외: "Satin/Semi-Gloss" (반광)
    *   **효과**: 단순한 숫자가 아니라 "젖은 느낌", "안개 낀 거울" 같은 묘사를 통해 훨씬 사실적인 질감을 생성합니다.

4.  **조명 온도 (Lighting Temperature)**
    *   **원리**: 켈빈(K) 값을 직접 전달하여 분위기를 형성합니다.
    *   **예시**: `2000K` (따뜻한/주황빛) ~ `6500K` (차가운/주광빛).

### 3.2. 사용자 인터페이스 (UI/UX)
*   **Drag & Drop**: 레이아웃, 도어, 바닥재 업로드 시 직관적인 드래그 앤 드롭 지원.
*   **원형 컬러 피커**: 금속 재질(실버, 골드, 브론즈, 블랙)을 그라데이션 원형 버튼으로 직관적으로 선택.
*   **실시간 미리보기**: 파일 업로드 즉시 브라우저에서 미리보기(Object URL) 제공.
*   **반응형 디자인**: Tailwind CSS를 활용하여 모바일/데스크탑 환경 대응.

### 3.3. 네비게이션 및 보안
*   **헤더 동기화**: 레거시 `myheader.php`의 모든 메뉴(견적, 수주, 구매, 품질, 안전, 연구소, 근태 등)를 Tailwind CSS로 완벽하게 포팅했습니다.
*   **권한 제어**: 
    *   대리점(Level 20) 사용자에게는 내부 업무용 메뉴(구매, 연구소 등)를 숨기고, '포미스톤' 메뉴는 노출하는 등 세밀한 조건부 렌더링 적용.
    *   비로그인 사용자(Guest)는 로그인 페이지로 자동 리다이렉트.
*   **보안**: API Key는 서버(`gemini_api.txt`)에 보관하며, PHP가 렌더링 시점에만 JS 변수로 주입합니다. (클라이언트 코드에 하드코딩하지 않음)

### 3.4. 내보내기 (Export)
*   **다운로드**: 생성된 이미지는 `panel_YYYYMMDDHHMMSS.png` 형식의 타임스탬프 파일명으로 다운로드됩니다.

## 4. 버전 기록 (Version History)

| 버전 | 날짜 | 변경 사항 |
|---|---|---|
| **v1.0** | 2025-12-12 | 초기 릴리즈. React 버전을 PHP/JS로 포팅 완료. 드래그 앤 드롭, 전체 메뉴 동기화. |
| **v1.1** | 2025-12-12 | 프롬프트 고도화. 영문 시스템 프롬프트 및 물리 반사 로직 복원 적용. |
| **v1.2** | 2025-12-12 | 기술 문서 한글화 및 프롬프트 원리 섹션 추가. |
| **v1.3** | 2025-12-12 | 프롬프트 디버그 UI 추가, 판넬 드래그&드롭, 상세 에러 핸들링, 생성 버튼 타이머 추가. 프롬프트 로직 개선(패널 구분선, 리얼 스케일, 평면 애칭, 1/11번 패널 매핑). |

## 5. 파일 구조
```
/rendering/
├── index.php           # 메인 애플리케이션 (로직, UI, 스타일 포함)
├── gemini_api.txt      # API Key (Git 제외 대상)
├── dev.md              # 기술 문서 (현재 파일)
└── ref/                # 참조용 React 레거시 코드 (보관용)
```

## 6. 향후 계획 (Roadmap)
*   [ ] **서버 저장 기능**: 생성된 렌더링 이미지를 서버(`/data/rendering/`)에 저장하는 PHP 엔드포인트 구현.
*   [ ] **히스토리 뷰**: 사용자가 과거에 생성한 이미지를 조회하는 기능.
*   [ ] **모바일 최적화**: 모바일 전용 UI 레이아웃 고도화.
