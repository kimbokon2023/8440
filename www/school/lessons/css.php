<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> CSS 강좌</title>
  <link rel="stylesheet" href="../css/lessons_style.css?ver=1">
  <style>
    /* 기본 스타일 (강좌 페이지 자체 스타일) */
    body { font-family: sans-serif; line-height: 1.6; }
    .toc { border: 1px solid #ccc; padding: 15px; margin-bottom: 30px; background-color: #f9f9f9; }
    .toc h2 { margin-top: 0; }
    h1, h2 { border-bottom: 2px solid #eee; padding-bottom: 5px; }
    h2 { margin-top: 40px; }
    h3 { margin-top: 30px; border-bottom: 1px dashed #ccc; padding-bottom: 3px;}
    pre { background-color: #f4f4f4; padding: 15px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; font-size: 0.9em; }
    code { font-family: Consolas, monospace; }
    .example { border: 1px solid #eee; padding: 15px; margin-top: 10px; margin-bottom: 20px; background-color: #fff; border-radius: 4px;}
    .example h4 { margin-top: 0; font-size: 1.1em; color: #555; }
    .note { background-color: #fffbdd; border-left: 4px solid #ffca00; padding: 10px 15px; margin: 15px 0; font-size: 0.95em;}

    /* CSS 예제 시연용 스타일 */
    .box { background-color: lightblue; padding: 20px; border: 5px solid navy; margin: 10px; }
    .flex-container { display: flex; background-color: dodgerblue; padding: 10px; }
    .flex-item { background-color: #f1f1f1; margin: 10px; padding: 20px; font-size: 30px; text-align: center; }
    .grid-container { display: grid; grid-template-columns: auto auto auto; background-color: #2196F3; padding: 10px; gap: 10px; }
    .grid-item { background-color: rgba(255, 255, 255, 0.8); border: 1px solid rgba(0, 0, 0, 0.8); padding: 20px; font-size: 30px; text-align: center; }

    /* TOC 활성 링크 스타일 */
    .toc a.active {
      color: #d9534f;
      font-weight: bold;
    }
  </style>
</head>
<body>

<h1> CSS 강좌</h1>
<p>이 페이지는 웹 페이지의 스타일을 정의하는 CSS(Cascading Style Sheets)의 핵심 개념과 주요 속성들을 다룹니다. 초급부터 중급 수준까지 필요한 내용을 포함합니다.</p>

<div class="toc">
  <h2>📖 목차</h2>
  <ul>
    <li><a href="#intro">CSS 소개 및 적용 방법</a></li>
    <li><a href="#syntax">기본 문법 및 주석</a></li>
    <li><a href="#selectors">선택자 (Selectors)</a></li>
    <li><a href="#cascade-specificity">캐스케이드, 명시도, 상속</a></li>
    <li><a href="#units-values">단위 및 값 (Units & Values)</a></li>
    <li><a href="#colors">색상 (Colors)</a></li>
    <li><a href="#boxmodel">박스 모델 (Box Model)</a></li>
    <li><a href="#backgrounds">배경 (Backgrounds)</a></li>
    <li><a href="#text-fonts">텍스트 및 폰트</a></li>
    <li><a href="#display">Display 속성</a></li>
    <li><a href="#positioning">Positioning (위치 지정)</a></li>
    <li><a href="#floats">Floats (과거 레이아웃 방식)</a></li>
    <li><a href="#flexbox">Flexbox 레이아웃</a></li>
    <li><a href="#grid">Grid 레이아웃</a></li>
    <li><a href="#transitions">전환 (Transitions)</a></li>
    <li><a href="#transforms">변형 (Transforms)</a></li>
    <li><a href="#animations">애니메이션 (Animations)</a></li>
    <li><a href="#responsive">반응형 웹과 미디어 쿼리</a></li>
    <li><a href="#variables">CSS 변수 (Custom Properties)</a></li>
    <li><a href="#pseudo">가상 클래스와 가상 요소</a></li>
  </ul>
</div>

<section id="intro">
  <h2>CSS 소개 및 적용 방법</h2>
  <p>CSS (Cascading Style Sheets)는 HTML 문서의 표현(디자인, 레이아웃 등)을 기술하는 스타일 시트 언어입니다. HTML이 웹 페이지의 구조를 정의한다면, CSS는 그 구조를 시각적으로 꾸미는 역할을 합니다.</p>
  <p>CSS를 HTML에 적용하는 방법은 세 가지가 있습니다:</p>
  <ol>
    <li>
      <strong>외부 스타일 시트 (External Style Sheet):</strong> 별도의 <code>.css</code> 파일을 만들어 HTML 문서의 <code>&lt;head&gt;</code> 섹션에서 <code>&lt;link&gt;</code> 태그로 연결하는 방식입니다. 여러 페이지에 일관된 스타일을 적용하기 쉽고 유지보수가 용이하여 <strong>가장 권장되는 방법</strong>입니다.
      <pre><code class="language-html">&lt;!-- HTML 파일 (&lt;head&gt; 안에) --&gt;
&lt;link rel="stylesheet" href="styles.css"&gt;</code></pre>
      <pre><code class="language-css">/* styles.css 파일 내용 */
body {
  font-family: Arial, sans-serif;
}
h1 {
  color: navy;
}</code></pre>
    </li>
    <li>
      <strong>내부 스타일 시트 (Internal Style Sheet):</strong> HTML 문서의 <code>&lt;head&gt;</code> 섹션 안에 <code>&lt;style&gt;</code> 태그를 사용하여 CSS 코드를 직접 작성하는 방식입니다. 해당 HTML 문서에만 적용됩니다.
      <pre><code class="language-html">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;title&gt;내부 스타일&lt;/title&gt;
  &lt;style&gt;
    body {
      background-color: lightyellow;
    }
    p {
      color: green;
    }
  &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
  &lt;p&gt;이 문단은 초록색입니다.&lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
    </li>
    <li>
      <strong>인라인 스타일 (Inline Style):</strong> HTML 요소의 <code>style</code> 속성에 직접 CSS 코드를 작성하는 방식입니다. 특정 요소 하나에만 스타일을 적용할 때 사용하지만, 재사용성이 낮고 유지보수가 어려워 꼭 필요한 경우가 아니면 권장되지 않습니다. (명시도가 가장 높음)
      <pre><code class="language-html">&lt;p style="color: red; font-size: 20px;"&gt;이 문단은 빨간색이고 글자 크기는 20px입니다.&lt;/p&gt;</code></pre>
    </li>
  </ol>
</section>

<section id="syntax">
  <h2>기본 문법 및 주석</h2>
  <p>CSS 규칙은 <strong>선택자(Selector)</strong>와 <strong>선언 블록(Declaration Block)</strong>으로 구성됩니다. 선언 블록은 중괄호<code>{}</code> 안에 하나 이상의 <strong>선언(Declaration)</strong>을 포함하며, 각 선언은 <strong>속성(Property)</strong>과 <strong>값(Value)</strong>으로 이루어지고 세미콜론<code>;</code>으로 끝납니다.</p>
  <pre><code class="language-css">선택자 {
  속성: 값;
  /* 다른 속성: 다른 값; */
}

/* 예시 */
p {
  color: blue; /* 글자 색상을 파란색으로 설정 */
  font-size: 16px; /* 글자 크기를 16픽셀로 설정 */
}</code></pre>
  <p>CSS 주석은 <code>/*</code>로 시작하여 <code>*/</code>로 끝납니다. 여러 줄에 걸쳐 작성할 수 있습니다.</p>
</section>

<section id="selectors">
  <h2>선택자 (Selectors)</h2>
  <p>선택자는 어떤 HTML 요소에 스타일을 적용할지 지정하는 패턴입니다. 다양한 종류의 선택자가 있습니다.</p>
  <div class="example">
    <h4>기본 선택자</h4>
    <ul>
      <li><strong>전체 선택자 (Universal Selector):</strong> <code>*</code> - 모든 요소를 선택합니다. (성능에 영향을 줄 수 있어 주의)</li>
      <li><strong>타입 선택자 (Type Selector):</strong> <code>elementName</code> (예: <code>h1</code>, <code>p</code>, <code>div</code>) - 특정 태그 이름의 모든 요소를 선택합니다.</li>
      <li><strong>클래스 선택자 (Class Selector):</strong> <code>.className</code> - 특정 클래스 속성 값을 가진 모든 요소를 선택합니다. (가장 흔하게 사용됨)</li>
      <li><strong>ID 선택자 (ID Selector):</strong> <code>#idName</code> - 특정 ID 속성 값을 가진 요소를 선택합니다. (ID는 페이지 내에서 고유해야 함)</li>
      <li><strong>속성 선택자 (Attribute Selector):</strong>
        <ul>
            <li><code>[attr]</code>: 특정 속성을 가진 요소</li>
            <li><code>[attr=value]</code>: 특정 속성 값이 정확히 일치하는 요소</li>
            <li><code>[attr~=value]</code>: 속성 값 목록(공백으로 구분) 중 특정 값이 포함된 요소</li>
            <li><code>[attr|=value]</code>: 속성 값이 특정 값과 같거나 특정 값 뒤에 하이픈(-)이 오는 요소 (주로 언어 코드)</li>
            <li><code>[attr^=value]</code>: 속성 값이 특정 값으로 시작하는 요소</li>
            <li><code>[attr$=value]</code>: 속성 값이 특정 값으로 끝나는 요소</li>
            <li><code>[attr*=value]</code>: 속성 값에 특정 값이 포함된 요소</li>
        </ul>
      </li>
    </ul>
    <pre><code class="language-css">/* 전체 선택자 */
* { margin: 0; padding: 0; box-sizing: border-box; }

/* 타입 선택자 */
h2 { color: green; }
p { line-height: 1.5; }

/* 클래스 선택자 */
.highlight { background-color: yellow; }
.text-center { text-align: center; }

/* ID 선택자 */
#main-title { font-size: 3em; }

/* 속성 선택자 */
a[target] { color: purple; } /* target 속성이 있는 모든 a 요소 */
a[target="_blank"] { background-color: lightgray; } /* target 속성 값이 "_blank"인 a 요소 */
img[alt~="dog"] { border: 2px solid brown; } /* alt 속성 값 목록에 "dog"이 포함된 img */
[lang|="en"] { font-style: italic; } /* lang 속성이 "en" 또는 "en-"으로 시작하는 요소 */
a[href^="https://"] { font-weight: bold; } /* href가 "https://"로 시작하는 a 요소 */
a[href$=".pdf"]::after { content: " (PDF)"; } /* href가 ".pdf"로 끝나는 a 요소 */
a[href*="example.com"] { color: orange; } /* href에 "example.com"이 포함된 a 요소 */
input[type="text"] { border: 1px solid #ccc; } /* type 속성이 "text"인 input 요소 */
</code></pre>
  </div>

  <div class="example">
    <h4>그룹 선택자 (Grouping Selector)</h4>
    <p>쉼표(<code>,</code>)를 사용하여 여러 선택자에 동일한 스타일 규칙을 적용합니다.</p>
    <pre><code class="language-css">h1, h2, h3 {
  font-family: 'Georgia', serif;
  color: #333;
}</code></pre>
  </div>

  <div class="example">
    <h4>결합자 (Combinators)</h4>
    <p>선택자들을 결합하여 특정 관계에 있는 요소를 선택합니다.</p>
    <ul>
      <li><strong>자손 결합자 (Descendant Combinator):</strong> ` ` (공백) - 특정 요소의 모든 하위(자손) 요소를 선택합니다. (예: `div p` - div 안의 모든 p 요소)</li>
      <li><strong>자식 결합자 (Child Combinator):</strong> `&gt;` - 특정 요소의 바로 아래 자식 요소를 선택합니다. (예: `ul &gt; li` - ul 바로 아래의 li 요소)</li>
      <li><strong>일반 형제 결합자 (General Sibling Combinator):</strong> `~` - 같은 부모를 가지며, 특정 요소 뒤에 오는 모든 형제 요소를 선택합니다. (예: `h2 ~ p` - h2 뒤에 나오는 모든 p 형제 요소)</li>
      <li><strong>인접 형제 결합자 (Adjacent Sibling Combinator):</strong> `+` - 같은 부모를 가지며, 특정 요소 바로 뒤에 오는 형제 요소를 선택합니다. (예: `h2 + p` - h2 바로 다음에 나오는 p 형제 요소)</li>
    </ul>
    <pre><code class="language-css">/* 자손 결합자: div 안에 있는 모든 p */
div p { color: gray; }

/* 자식 결합자: .container 바로 아래 자식인 p */
.container > p { font-weight: bold; }

/* 인접 형제 결합자: h2 바로 뒤에 오는 p */
h2 + p { margin-top: 0; }

/* 일반 형제 결합자: #logo 뒤에 오는 모든 nav 요소 */
#logo ~ nav { display: inline-block; margin-left: 20px; }
</code></pre>
  </div>
  <p class="note">선택자는 매우 다양하며, 가상 클래스와 가상 요소 선택자도 중요합니다. 이는 <a href="#pseudo">별도 섹션</a>에서 다룹니다.</p>
</section>

<section id="cascade-specificity">
  <h2>캐스케이드, 명시도, 상속</h2>
  <p>하나의 요소에 여러 CSS 규칙이 적용될 수 있을 때, 어떤 스타일이 최종적으로 적용될지를 결정하는 규칙입니다.</p>
  <ol>
    <li><strong>캐스케이드 (Cascade):</strong> 스타일 시트가 계단식으로 적용되는 원리입니다. 브라우저 기본 스타일, 사용자 스타일 시트, 제작자 스타일 시트 순으로 적용되며, 동일 출처 내에서는 나중에 선언된 규칙이 우선합니다.</li>
    <li><strong>명시도 (Specificity):</strong> 선택자가 얼마나 구체적인지를 나타내는 값입니다. 명시도가 높은 규칙이 우선 적용됩니다. 계산 순서는 다음과 같습니다 (높은 순):
      <ol>
        <li>인라인 스타일 (<code>style="..."</code>)</li>
        <li>ID 선택자 (<code>#id</code>)</li>
        <li>클래스 선택자 (<code>.class</code>), 속성 선택자 (<code>[attr]</code>), 가상 클래스 (<code>:hover</code>)</li>
        <li>타입 선택자 (<code>p</code>), 가상 요소 (<code>::before</code>)</li>
      </ol>
      <p>예: <code>#main-nav > ul li.active a</code>는 <code>div p</code>보다 명시도가 훨씬 높습니다.</p>
      <p><code>!important</code> 키워드를 선언 끝에 붙이면 모든 명시도 규칙을 무시하고 가장 높은 우선순위를 갖게 됩니다. 하지만 코드 관리를 어렵게 만들 수 있으므로 꼭 필요한 경우에만 제한적으로 사용해야 합니다.</p>
      <pre><code class="language-css">p { color: blue; }
.content p { color: green; } /* 클래스 선택자가 더 명시적이므로 green 적용 */
#sidebar p { color: red; }    /* ID 선택자가 더 명시적이므로 red 적용 */
p { color: purple !important; } /* !important가 가장 우선하므로 purple 적용 */
</code></pre>
    </li>
    <li><strong>상속 (Inheritance):</strong> 부모 요소에 적용된 일부 CSS 속성(주로 텍스트 관련 속성: `color`, `font-family`, `font-size`, `line-height`, `text-align` 등)은 자식 요소에게 상속됩니다. 모든 속성이 상속되는 것은 아닙니다 (예: `border`, `padding`, `margin`, `background` 등은 상속되지 않음).
      <p><code>inherit</code> 키워드를 사용하여 강제로 부모의 속성 값을 상속받게 할 수 있습니다.</p>
      <pre><code class="language-css">body {
  font-family: Arial, sans-serif; /* 자식 요소들에게 상속됨 */
  color: #333; /* 자식 요소들에게 상속됨 */
  border: 1px solid black; /* 상속되지 않음 */
}

.special-box {
  border: 1px solid red;
  /* 부모(body)의 color 값을 강제로 상속받음 (기본값은 보통 검정) */
  color: inherit;
}</code></pre>
    </li>
  </ol>
</section>

<section id="units-values">
    <h2>단위 및 값 (Units & Values)</h2>
    <p>CSS 속성 값에는 다양한 단위를 사용할 수 있습니다.</p>
    <h3>길이 단위</h3>
    <ul>
        <li><strong>절대 단위 (Absolute Units):</strong> 고정된 크기. 주로 `px` (픽셀)이 사용됩니다. 화면 출력에는 `px`가 적합하며, 인쇄용으로는 `cm`, `mm`, `in`, `pt`, `pc` 등이 사용될 수 있습니다.</li>
        <li><strong>상대 단위 (Relative Units):</strong> 다른 값에 상대적인 크기. 반응형 디자인에 유용합니다.
            <ul>
                <li><code>%</code>: 부모 요소의 같은 속성 값에 대한 백분율.</li>
                <li><code>em</code>: 현재 요소의 `font-size`에 대한 배수. (만약 `font-size: 16px;`이면 `2em`은 `32px`)</li>
                <li><code>rem</code> (Root em): 루트 요소(<code>&lt;html&gt;</code>)의 `font-size`에 대한 배수. (<code>em</code>보다 예측 가능하여 더 선호됨)</li>
                <li><code>vw</code> (Viewport Width): 뷰포트 너비의 1/100. (<code>100vw`는 뷰포트 전체 너비)</li>
                <li><code>vh</code> (Viewport Height): 뷰포트 높이의 1/100. (<code>100vh</code>는 뷰포트 전체 높이)</li>
                <li><code>vmin</code>, <code>vmax</code>: 뷰포트의 너비와 높이 중 더 작은 값 또는 더 큰 값의 1/100.</li>
            </ul>
        </li>
    </ul>
    <pre><code class="language-css">p {
  font-size: 16px; /* 절대 단위 */
  line-height: 1.5; /* 단위 없음 (font-size의 배수) - 권장 */
  padding: 1em; /* 현재 요소 font-size(16px)의 1배 = 16px */
  margin-bottom: 2rem; /* 루트 요소 font-size의 2배 */
}

.container {
  width: 80%; /* 부모 요소 너비의 80% */
  max-width: 1200px; /* 최대 너비는 1200px */
}

.full-screen-banner {
  width: 100vw; /* 뷰포트 전체 너비 */
  height: 50vh; /* 뷰포트 높이의 50% */
}</code></pre>

    <h3>키워드 값</h3>
    <p><code>initial</code> (기본값), <code>inherit</code> (부모 값 상속), <code>unset</code> (상속 가능하면 inherit, 아니면 initial), <code>auto</code> (브라우저 계산) 등 특수한 키워드 값도 사용됩니다.</p>
</section>

<section id="colors">
    <h2>색상 (Colors)</h2>
    <p>CSS에서 색상을 지정하는 방법은 여러 가지가 있습니다.</p>
    <ul>
        <li><strong>색상 이름 (Named Colors):</strong> <code>red</code>, <code>blue</code>, <code>green</code>, <code>lightgray</code> 등 미리 정의된 이름 사용 (약 140개).</li>
        <li><strong>HEX (16진수 색상 코드):</strong> <code>#RRGGBB</code> 또는 <code>#RGB</code> 형식. 가장 널리 사용됨.
            <ul>
                <li><code>#FF0000</code> (빨강), <code>#00FF00</code> (초록), <code>#0000FF</code> (파랑)</li>
                <li><code>#F00</code> (<code>#FF0000</code>과 동일), <code>#0F0</code>, <code>#00F</code></li>
                <li><code>#RRGGBBAA</code> 또는 <code>#RGBA</code> (투명도 포함, 최신 브라우저 지원)</li>
            </ul>
        </li>
        <li><strong>RGB / RGBA:</strong> <code>rgb(red, green, blue)</code> 형식 (각 값은 0-255). <code>rgba(red, green, blue, alpha)</code> 형식 (alpha는 투명도, 0.0 ~ 1.0).</li>
        <li><strong>HSL / HSLA:</strong> <code>hsl(hue, saturation, lightness)</code> 형식 (hue: 색상환 각도 0-360, saturation/lightness: %). <code>hsla(hue, saturation, lightness, alpha)</code> 형식 (alpha는 투명도). 색상 조작이 직관적임.</li>
    </ul>
    <pre><code class="language-css">body { background-color: lightgray; } /* 이름 */
h1 { color: #333333; } /* HEX */
.highlight { background-color: rgba(255, 255, 0, 0.5); } /* RGBA (노란색 반투명) */
button { background-color: hsl(210, 80%, 50%); } /* HSL (파란색 계열) */
button:hover { background-color: hsla(210, 80%, 60%, 0.8); } /* HSLA (밝고 투명도 약간) */
</code></pre>
</section>


<section id="boxmodel">
  <h2>박스 모델 (Box Model)</h2>
  <p>모든 HTML 요소는 CSS에서 사각형 박스로 표현됩니다. 이 박스는 네 가지 영역으로 구성됩니다:</p>
  <div class="example">
    <div class="box">이것은 박스 모델 예시입니다.</div>
  </div>
  <ul>
    <li><strong>콘텐츠 (Content):</strong> 텍스트, 이미지 등 실제 내용이 표시되는 영역. `width`와 `height` 속성으로 크기를 제어합니다.</li>
    <li><strong>패딩 (Padding):</strong> 콘텐츠 영역과 테두리 사이의 여백. 배경색/이미지는 패딩 영역까지 적용됩니다.
      <ul>
        <li><code>padding-top</code>, <code>padding-right</code>, <code>padding-bottom</code>, <code>padding-left</code></li>
        <li><code>padding: value;</code> (상하좌우 동일)</li>
        <li><code>padding: value1 value2;</code> (상하, 좌우)</li>
        <li><code>padding: value1 value2 value3;</code> (상, 좌우, 하)</li>
        <li><code>padding: value1 value2 value3 value4;</code> (상, 우, 하, 좌 - 시계방향)</li>
      </ul>
    </li>
    <li><strong>테두리 (Border):</strong> 패딩 영역 바깥쪽의 선.
      <ul>
        <li><code>border-width</code>, <code>border-style</code> (<code>solid</code>, <code>dashed</code>, <code>dotted</code> 등), <code>border-color</code></li>
        <li><code>border: width style color;</code> (예: <code>border: 1px solid black;</code>)</li>
        <li>각 방향별 설정 가능: <code>border-top</code>, <code>border-right</code> 등</li>
        <li><code>border-radius</code>: 테두리를 둥글게 만듭니다.</li>
      </ul>
    </li>
    <li><strong>마진 (Margin):</strong> 테두리 바깥쪽의 여백. 다른 요소와의 간격을 조정합니다. 패딩과 동일한 단축 속성 규칙을 따릅니다.
      <ul>
          <li>수직 마진 병합 (Margin Collapsing): 인접한 블록 요소의 수직 마진은 더 큰 값으로 병합될 수 있습니다.</li>
      </ul>
    </li>
  </ul>
  <pre><code class="language-css">.box-example {
  width: 300px; /* 콘텐츠 너비 */
  height: 150px; /* 콘텐츠 높이 */
  padding: 20px; /* 상하좌우 20px 패딩 */
  border: 10px solid red; /* 10px 붉은 실선 테두리 */
  margin: 30px auto; /* 상하 30px, 좌우 자동(가운데 정렬) 마진 */
  background-color: lightyellow;
  border-radius: 15px; /* 모서리 둥글게 */
}</code></pre>

  <h3>box-sizing 속성</h3>
  <p><code>box-sizing</code> 속성은 `width`와 `height`가 콘텐츠 영역만 포함할지(<code>content-box</code> - 기본값), 아니면 패딩과 테두리까지 포함할지를 결정합니다(<code>border-box</code>).</p>
  <p><code>border-box</code>를 사용하면 요소의 전체 크기를 예측하기 쉬워 레이아웃 작업이 편리해집니다. 많은 개발자들이 모든 요소에 <code>border-box</code>를 적용하는 것을 선호합니다.</p>
  <pre><code class="language-css">*, *::before, *::after {
  box-sizing: border-box; /* 모든 요소와 가상 요소에 적용 */
}

.element {
  width: 200px; /* padding, border 포함 최종 너비가 200px */
  padding: 10px;
  border: 2px solid black;
  box-sizing: border-box;
}</code></pre>
</section>


<section id="backgrounds">
    <h2>배경 (Backgrounds)</h2>
    <p>요소의 배경을 꾸미는 속성들입니다.</p>
    <ul>
        <li><code>background-color</code>: 배경 색상 지정.</li>
        <li><code>background-image</code>: 배경 이미지 지정 (<code>url('path/to/image.jpg')</code>). 여러 개 지정 가능.</li>
        <li><code>background-repeat</code>: 이미지 반복 방식 (<code>repeat</code>, <code>no-repeat</code>, <code>repeat-x</code>, <code>repeat-y</code>).</li>
        <li><code>background-position</code>: 이미지 위치 (<code>top</code>, <code>center</code>, <code>bottom</code>, <code>left</code>, <code>right</code>, 또는 `%`, `px` 값 조합. 예: <code>center center</code>, <code>top right</code>, <code>50% 50%</code>).</li>
        <li><code>background-size</code>: 이미지 크기 (<code>auto</code>, <code>cover</code>(꽉 채움), <code>contain</code>(잘리지 않게 맞춤), 또는 `px`, `%` 값).</li>
        <li><code>background-attachment</code>: 이미지가 스크롤될 때 같이 움직일지(<code>scroll</code> - 기본값) 또는 고정될지(<code>fixed</code>).</li>
        <li><code>background-origin</code>: 배경 이미지 위치 기준 (<code>padding-box</code>, <code>border-box</code>, <code>content-box</code>).</li>
        <li><code>background-clip</code>: 배경이 그려지는 영역 (<code>padding-box</code>, <code>border-box</code>, <code>content-box</code>, <code>text</code> - 실험적).</li>
        <li><code>background</code>: 위 속성들을 한 번에 지정하는 단축 속성.</li>
    </ul>
    <pre><code class="language-css">.hero-section {
  background-color: #f0f0f0;
  background-image: url('images/banner.jpg');
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover; /* 이미지가 요소를 꽉 채우도록 */
  background-attachment: fixed; /* 스크롤해도 배경은 고정 */
  height: 400px;
  color: white;
  text-shadow: 1px 1px 2px black;
}

/* 단축 속성 사용 예 */
.another-bg {
  background: lightblue url('images/pattern.png') repeat-x top left;
}</code></pre>
</section>

<section id="text-fonts">
    <h2>텍스트 및 폰트</h2>
    <p>텍스트의 모양과 정렬 등을 제어하는 속성들입니다.</p>
    <ul>
        <li><code>color</code>: 글자 색상.</li>
        <li><code>font-family</code>: 글꼴 지정 (여러 개 지정 시 앞에서부터 사용 가능한 폰트 사용, 마지막은 generic family(<code>serif</code>, <code>sans-serif</code>, <code>monospace</code> 등) 지정 권장).
            <ul><li><code>@font-face</code> 규칙으로 웹 폰트 사용 가능.</li></ul>
        </li>
        <li><code>font-size</code>: 글자 크기 (`px`, `em`, `rem`, `%` 등).</li>
        <li><code>font-weight</code>: 글자 굵기 (`normal`(400), `bold`(700), 또는 100~900 숫자).</li>
        <li><code>font-style</code>: 글자 스타일 (`normal`, `italic`, `oblique`).</li>
        <li><code>line-height</code>: 줄 간격 (단위 없는 숫자(글자 크기의 배수) 권장, `px`, `em`, `%` 등).</li>
        <li><code>text-align</code>: 텍스트 수평 정렬 (`left`, `right`, `center`, `justify`(양쪽 정렬)).</li>
        <li><code>text-decoration</code>: 텍스트 장식 (`none`, `underline`, `overline`, `line-through`). 하위 속성으로 `text-decoration-line`, `text-decoration-color`, `text-decoration-style` 등이 있음.</li>
        <li><code>text-transform</code>: 텍스트 대소문자 변환 (`none`, `capitalize`(단어 첫 글자 대문자), `uppercase`, `lowercase`).</li>
        <li><code>letter-spacing</code>: 글자 사이 간격 (자간).</li>
        <li><code>word-spacing</code>: 단어 사이 간격.</li>
        <li><code>text-indent</code>: 문단의 첫 줄 들여쓰기.</li>
        <li><code>white-space</code>: 공백 문자 처리 방식 (`normal`, `nowrap`(줄바꿈 안 함), `pre`(HTML 공백/줄바꿈 유지), `pre-wrap`, `pre-line`).</li>
        <li><code>text-overflow</code>: 내용이 넘칠 때 처리 방식 (`clip`(자름), `ellipsis`(말줄임표 `...`)). `overflow: hidden;` 및 `white-space: nowrap;`과 함께 사용.</li>
        <li><code>text-shadow</code>: 텍스트 그림자 효과 (`h-offset v-offset blur-radius color`).</li>
        <li><code>font</code>: 여러 font 관련 속성을 한 번에 지정하는 단축 속성 (`font-style font-weight font-size/line-height font-family`).</li>
    </ul>
    <pre><code class="language-css">@font-face {
  font-family: 'MyCustomFont';
  src: url('fonts/myfont.woff2') format('woff2'),
       url('fonts/myfont.woff') format('woff');
  font-weight: normal;
  font-style: normal;
}

body {
  font-family: 'MyCustomFont', Arial, sans-serif; /* 사용자 정의 폰트 우선 적용 */
  font-size: 16px;
  line-height: 1.6;
  color: #333;
}

h1 {
  font-size: 2.5rem;
  font-weight: bold;
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 2px;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

p {
  text-align: justify;
  margin-bottom: 1em;
}

a {
  color: #007bff;
  text-decoration: none; /* 링크 밑줄 제거 */
}
a:hover {
  text-decoration: underline; /* 마우스 올리면 밑줄 표시 */
}

.ellipsis-text {
  width: 200px; /* 너비 제한 필요 */
  white-space: nowrap; /* 줄바꿈 방지 */
  overflow: hidden; /* 넘치는 내용 숨김 */
  text-overflow: ellipsis; /* 말줄임표(...) 표시 */
  border: 1px solid #ccc; /* 확인용 테두리 */
  padding: 5px;
}</code></pre>
    <div class="example">
        <h4>말줄임표 예시</h4>
        <p class="ellipsis-text">이 텍스트는 매우 길어서 지정된 너비 안에 다 들어가지 않을 경우 말줄임표로 처리됩니다.</p>
    </div>
</section>


<section id="display">
    <h2>Display 속성</h2>
    <p><code>display</code> 속성은 요소가 화면에 어떻게 보이고 다른 요소와 어떻게 상호작용할지(렌더링 방식)를 결정합니다. 주요 값은 다음과 같습니다.</p>
    <ul>
        <li><code>block</code>: 블록 레벨 요소. 항상 새 줄에서 시작하고, 가능한 전체 너비를 차지합니다. <code>width</code>, <code>height</code>, <code>margin</code>, <code>padding</code> 적용 가능. (예: <code>&lt;div&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;h1&gt;</code>-<code>&lt;h6&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>)</li>
        <li><code>inline</code>: 인라인 레벨 요소. 새 줄에서 시작하지 않고, 콘텐츠 너비만큼만 차지합니다. <code>width</code>, <code>height</code> 적용 불가, <code>margin-top/bottom</code> 적용 불가. <code>padding</code>, <code>margin-left/right</code>는 적용 가능. (예: <code>&lt;span&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;img&gt;</code>, <code>&lt;strong&gt;</code>)</li>
        <li><code>inline-block</code>: <code>inline</code>처럼 다른 요소와 같은 줄에 배치되지만, <code>block</code>처럼 <code>width</code>, <code>height</code>, <code>margin</code>, <code>padding</code>을 모두 적용할 수 있습니다.</li>
        <li><code>none</code>: 요소를 화면에서 완전히 숨깁니다. 공간도 차지하지 않습니다. (<code>visibility: hidden;</code>은 공간은 차지하면서 보이지만 않게 함)</li>
        <li><code>flex</code>: 플렉스 컨테이너로 만듭니다. (<a href="#flexbox">Flexbox</a> 섹션 참조)</li>
        <li><code>grid</code>: 그리드 컨테이너로 만듭니다. (<a href="#grid">Grid</a> 섹션 참조)</li>
        <li>기타: <code>table</code>, <code>table-cell</code> 등 테이블처럼 동작하게 하는 값들도 있습니다.</li>
    </ul>
    <pre><code class="language-css">span {
  display: block; /* span을 블록 요소처럼 동작하게 함 */
  width: 100px;
  height: 50px;
  background-color: lightcoral;
  margin: 10px;
}

li {
  display: inline-block; /* li를 가로로 배치하고 크기/여백 지정 가능하게 함 */
  background-color: lightseagreen;
  padding: 10px;
  margin-right: 5px;
}

.hidden-element {
  display: none; /* 요소를 완전히 숨김 */
}</code></pre>
</section>

<section id="positioning">
    <h2>Positioning (위치 지정)</h2>
    <p><code>position</code> 속성은 요소의 배치 방식을 결정합니다. <code>top</code>, <code>right</code>, <code>bottom</code>, <code>left</code> 속성과 함께 사용하여 위치를 조정합니다.</p>
    <ul>
        <li><code>static</code>: 기본값. 일반적인 문서 흐름에 따라 배치되며, <code>top</code>, <code>right</code>, <code>bottom</code>, <code>left</code> 속성이 적용되지 않습니다.</li>
        <li><code>relative</code>: 일반적인 문서 흐름에 따라 배치되지만, <code>top</code>, <code>right</code>, <code>bottom</code>, <code>left</code> 속성을 사용하여 <strong>원래 위치를 기준</strong>으로 상대적인 오프셋을 지정할 수 있습니다. 차지하는 공간은 원래 위치를 기준으로 합니다. (<code>absolute</code> 위치 지정의 기준(containing block)이 됩니다)</li>
        <li><code>absolute</code>: 일반적인 문서 흐름에서 벗어납니다. <strong>가장 가까운 <code>position</code>이 <code>static</code>이 아닌 조상 요소를 기준</strong>으로 <code>top</code>, <code>right</code>, <code>bottom</code>, <code>left</code> 위치가 결정됩니다. 만약 그런 조상이 없으면 초기 컨테이닝 블록(보통 <code>&lt;body&gt;</code>)을 기준으로 합니다.</li>
        <li><code>fixed</code>: 일반적인 문서 흐름에서 벗어납니다. <strong>뷰포트(브라우저 창)를 기준</strong>으로 <code>top</code>, <code>right</code>, <code>bottom</code>, <code>left</code> 위치가 결정됩니다. 스크롤해도 항상 같은 위치에 고정됩니다. (예: 상단 고정 네비게이션 바)</li>
        <li><code>sticky</code>: 일반적인 문서 흐름에 따라 배치되지만, 스크롤 위치가 특정 임계점(<code>top</code>, <code>right</code>, <code>bottom</code>, <code>left</code> 값으로 지정)에 도달하면 <code>fixed</code>처럼 동작하여 화면에 고정됩니다. 컨테이닝 블록을 벗어나지는 않습니다.</li>
    </ul>
    <p><code>z-index</code> 속성은 <code>position</code>이 <code>static</code>이 아닌 요소들의 쌓임 순서(stacking order)를 결정합니다. 값이 클수록 위에 표시됩니다. (같은 쌓임 맥락(stacking context) 내에서 비교)</p>
    <pre><code class="language-css">.relative-box {
  position: relative;
  top: 10px;
  left: 20px;
  background-color: yellow;
  width: 200px;
}

.absolute-box {
  position: absolute;
  top: 50px;
  right: 30px;
  background-color: lightpink;
  width: 100px;
  height: 100px;
}

.fixed-header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background-color: #333;
  color: white;
  padding: 10px;
  z-index: 1000; /* 다른 요소들보다 위에 표시되도록 */
}

.sticky-sidebar {
  position: sticky;
  top: 20px; /* 뷰포트 상단에서 20px 떨어졌을 때 고정 시작 */
  background-color: lightgreen;
  padding: 15px;
}

.modal-background {
  position: fixed;
  top: 0; left: 0; width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.5);
  z-index: 900;
}
.modal-content {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%); /* 정확한 가운데 정렬 */
  background-color: white;
  padding: 20px;
  z-index: 901; /* 배경보다 위에 */
}</code></pre>
</section>

<section id="floats">
    <h2>Floats (과거 레이아웃 방식)</h2>
    <p><code>float</code> 속성은 요소를 일반적인 흐름에서 벗어나 왼쪽(<code>left</code>)이나 오른쪽(<code>right</code>)으로 이동시키고, 주변의 텍스트나 인라인 요소들이 그 주위를 감싸도록 합니다. 원래 이미지 주변에 텍스트를 배치하기 위해 고안되었습니다.</p>
    <p>과거에는 복잡한 웹 레이아웃을 구성하는 데 널리 사용되었지만, Flexbox와 Grid의 등장으로 레이아웃 목적으로는 거의 사용되지 않습니다. <code>float</code>를 사용하면 부모 요소가 자식의 높이를 인식하지 못하는 문제 등이 발생하여 <code>clear</code> 속성이나 clearfix 핵(hack) 등으로 해결해야 했습니다.</p>
    <ul>
        <li><code>float: left;</code>: 요소를 왼쪽으로 띄웁니다.</li>
        <li><code>float: right;</code>: 요소를 오른쪽으로 띄웁니다.</li>
        <li><code>float: none;</code>: 기본값.</li>
        <li><code>clear</code>: <code>float</code>된 요소의 영향을 받지 않도록 설정합니다.
            <ul>
                <li><code>clear: left;</code>: 왼쪽에 <code>float</code>된 요소 아래로 이동.</li>
                <li><code>clear: right;</code>: 오른쪽에 <code>float</code>된 요소 아래로 이동.</li>
                <li><code>clear: both;</code>: 양쪽에 <code>float</code>된 요소 모두의 아래로 이동.</li>
            </ul>
        </li>
    </ul>
    <div class="note">
        <strong>주의:</strong> 현대적인 웹 레이아웃에는 <strong>Flexbox</strong>나 <strong>Grid</strong> 사용을 강력히 권장합니다. <code>float</code>는 꼭 필요한 경우(예: 이미지와 텍스트 배치)에만 제한적으로 사용하는 것이 좋습니다.
    </div>
    <pre><code class="language-css">.img-left {
  float: left;
  margin-right: 15px;
  margin-bottom: 10px;
}

.clearfix::after { /* Clearfix 핵 예시 */
  content: "";
  display: block;
  clear: both;
}

.container-with-floats {
  border: 1px solid red; /* float된 자식 높이를 인식하는지 확인용 */
}
.container-with-floats > div {
    float: left;
    width: 50%;
    padding: 10px;
    box-sizing: border-box;
}

.footer {
  clear: both; /* float된 요소들 아래에 배치 */
  padding-top: 20px;
  border-top: 1px solid #ccc;
}
</code></pre>
    <div class="example clearfix">
        <h4>Float 예시 (이미지 + 텍스트)</h4>
        <img src="https://placehold.co/100" alt="placeholder" class="img-left">
        <p>이 텍스트는 왼쪽에 float된 이미지 주위를 감싸게 됩니다. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
     <div class="example">
        <h4>Float 예시 (레이아웃 - Clearfix 적용)</h4>
        <div class="container-with-floats clearfix">
            <div style="background-color: lightblue;">첫 번째 컬럼</div>
            <div style="background-color: lightcoral;">두 번째 컬럼</div>
             </div>
         <div class="footer">Float 아래 내용</div>
    </div>
</section>


<section id="flexbox">
  <h2>Flexbox 레이아웃</h2>
  <p>CSS Flexible Box Layout (Flexbox)는 1차원(행 또는 열) 레이아웃을 위한 강력하고 유연한 모델입니다. 요소 간의 공간 배분, 정렬 등을 쉽게 제어할 수 있습니다.</p>
  <p>Flexbox는 <strong>컨테이너(Container)</strong>와 <strong>아이템(Item)</strong>으로 구성됩니다. 컨테이너에 `display: flex;` 또는 `display: inline-flex;`를 적용하면 자식 요소들이 플렉스 아이템이 됩니다.</p>

  <h3>Flex Container 속성</h3>
  <ul>
    <li><code>display: flex | inline-flex;</code>: 플렉스 컨테이너로 지정.</li>
    <li><code>flex-direction: row | row-reverse | column | column-reverse;</code>: 아이템 배치 주축 방향 설정 (기본값: `row` - 가로).</li>
    <li><code>flex-wrap: nowrap | wrap | wrap-reverse;</code>: 아이템들이 한 줄에 들어가지 않을 때 줄바꿈 여부 설정 (기본값: `nowrap`).</li>
    <li><code>flex-flow: &lt;flex-direction&gt; &lt;flex-wrap&gt;;</code>: `flex-direction`과 `flex-wrap`의 단축 속성.</li>
    <li><code>justify-content: flex-start | flex-end | center | space-between | space-around | space-evenly;</code>: 주축(main axis) 방향 아이템 정렬 방식.</li>
    <li><code>align-items: stretch | flex-start | flex-end | center | baseline;</code>: 교차축(cross axis) 방향 아이템 정렬 방식 (한 줄일 때).</li>
    <li><code>align-content: stretch | flex-start | flex-end | center | space-between | space-around | space-evenly;</code>: 여러 줄일 때 교차축 방향 줄 간격 및 정렬 방식 (`flex-wrap: wrap`일 때 적용).</li>
    <li><code>gap</code>, <code>row-gap</code>, <code>column-gap</code>: 아이템 사이의 간격 지정.</li>
  </ul>

  <h3>Flex Item 속성</h3>
  <ul>
    <li><code>order: &lt;integer&gt;;</code>: 아이템 배치 순서 변경 (기본값: 0). 작은 값일수록 먼저 배치.</li>
    <li><code>flex-grow: &lt;number&gt;;</code>: 컨테이너 여유 공간이 있을 때 아이템이 늘어나는 비율 (기본값: 0).</li>
    <li><code>flex-shrink: &lt;number&gt;;</code>: 컨테이너 공간이 부족할 때 아이템이 줄어드는 비율 (기본값: 1).</li>
    <li><code>flex-basis: &lt;length&gt; | auto;</code>: 아이템의 기본 크기 지정 (기본값: `auto`).</li>
    <li><code>flex: &lt;flex-grow&gt; &lt;flex-shrink&gt; &lt;flex-basis&gt;;</code>: 위 세 속성의 단축 속성 (예: `flex: 1;`은 `flex: 1 1 0%;`와 유사, `flex: auto;`는 `flex: 1 1 auto;`, `flex: none;`은 `flex: 0 0 auto;`).</li>
    <li><code>align-self: auto | stretch | flex-start | flex-end | center | baseline;</code>: 개별 아이템의 교차축 정렬 방식 변경 (`align-items`보다 우선 적용됨).</li>
  </ul>

  <div class="example">
    <h4>Flexbox 예시</h4>
    <div class="flex-container" style="justify-content: space-around; align-items: center; height: 200px;">
      <div class="flex-item" style="flex-grow: 1;">1</div>
      <div class="flex-item" style="flex-grow: 2; align-self: flex-start;">2 (더 많이 늘어남, 위쪽 정렬)</div>
      <div class="flex-item" style="order: -1;">3 (order -1, 맨 앞으로)</div>
    </div>
  </div>

  <pre><code class="language-css">.container {
  display: flex;
  flex-direction: row; /* 가로 배치 (기본값) */
  justify-content: space-between; /* 주축 양 끝으로 정렬, 사이 간격 균등 배분 */
  align-items: center; /* 교차축 중앙 정렬 */
  height: 300px; /* 컨테이너 높이 지정 필요 (align-items 확인용) */
  border: 1px solid black;
  padding: 10px;
  gap: 10px; /* 아이템 사이 간격 */
}

.item {
  background-color: lightblue;
  padding: 20px;
  text-align: center;
}

.item-1 {
  flex-grow: 1; /* 남는 공간의 1/3 차지 */
}
.item-2 {
  flex-grow: 2; /* 남는 공간의 2/3 차지 */
  align-self: flex-end; /* 이 아이템만 아래쪽 정렬 */
}
.item-3 {
  order: -1; /* 가장 앞으로 배치 */
  flex-basis: 100px; /* 기본 너비 100px */
  flex-shrink: 0; /* 공간 부족해도 줄어들지 않음 */
}</code></pre>
</section>

<section id="grid">
  <h2>Grid 레이아웃</h2>
  <p>CSS Grid Layout은 2차원(행과 열) 레이아웃 시스템으로, 복잡한 웹 페이지 구조를 만들기에 매우 강력하고 유연합니다. Flexbox가 주로 1차원 배열에 적합하다면, Grid는 행과 열을 동시에 제어하는 데 뛰어납니다.</p>
  <p>Grid 역시 <strong>컨테이너(Container)</strong>와 <strong>아이템(Item)</strong>으로 구성됩니다. 컨테이너에 `display: grid;` 또는 `display: inline-grid;`를 적용하면 자식 요소들이 그리드 아이템이 됩니다.</p>

  <h3>Grid Container 속성</h3>
  <ul>
    <li><code>display: grid | inline-grid;</code>: 그리드 컨테이너로 지정.</li>
    <li><code>grid-template-columns: ...;</code>: 열의 크기와 개수 정의 (예: `100px 1fr 2fr;` - 첫 열 100px, 나머지 공간 1:2 비율, `repeat(3, 1fr);` - 동일 크기 3열).
        <ul><li>`fr` 단위: 사용 가능한 공간의 비율.</li></ul>
    </li>
    <li><code>grid-template-rows: ...;</code>: 행의 크기와 개수 정의 (<code>grid-template-columns</code>와 유사).</li>
    <li><code>grid-template-areas: "..." "..." ...;</code>: 각 영역에 이름을 부여하여 아이템 배치 (문자열 사용).</li>
    <li><code>gap</code>, <code>row-gap</code>, <code>column-gap</code>: 그리드 트랙(행/열) 사이의 간격 지정.</li>
    <li><code>justify-items: start | end | center | stretch;</code>: 그리드 아이템 내부 콘텐츠의 가로 정렬 (모든 아이템).</li>
    <li><code>align-items: start | end | center | stretch;</code>: 그리드 아이템 내부 콘텐츠의 세로 정렬 (모든 아이템).</li>
    <li><code>place-items: &lt;align-items&gt; &lt;justify-items&gt;;</code>: 위 두 속성의 단축 속성.</li>
    <li><code>justify-content: start | end | center | stretch | space-around | space-between | space-evenly;</code>: 그리드 자체(아이템 전체)의 컨테이너 내 가로 정렬 (그리드 크기가 컨테이너보다 작을 때).</li>
    <li><code>align-content: start | end | center | stretch | space-around | space-between | space-evenly;</code>: 그리드 자체(아이템 전체)의 컨테이너 내 세로 정렬 (그리드 크기가 컨테이너보다 작을 때).</li>
    <li><code>place-content: &lt;align-content&gt; &lt;justify-content&gt;;</code>: 위 두 속성의 단축 속성.</li>
    <li><code>grid-auto-columns</code>, <code>grid-auto-rows</code>: 명시적으로 지정되지 않은 트랙의 크기 설정.</li>
    <li><code>grid-auto-flow: row | column | dense;</code>: 자동 배치 알고리즘 방식 (빈 공간 채우기 등).</li>
  </ul>

  <h3>Grid Item 속성</h3>
  <ul>
    <li><code>grid-column-start</code>, <code>grid-column-end</code>, <code>grid-row-start</code>, <code>grid-row-end</code>: 아이템이 시작하고 끝나는 그리드 라인 번호 지정.</li>
    <li><code>grid-column: &lt;start-line&gt; / &lt;end-line&gt;;</code> (또는 <code>&lt;start-line&gt; / span &lt;number&gt;;</code>)</li>
    <li><code>grid-row: &lt;start-line&gt; / &lt;end-line&gt;;</code> (또는 <code>&lt;start-line&gt; / span &lt;number&gt;;</code>)</li>
    <li><code>grid-area: &lt;row-start&gt; / &lt;col-start&gt; / &lt;row-end&gt; / &lt;col-end&gt;;</code> 또는 `grid-template-areas`에서 지정한 이름.</li>
    <li><code>justify-self: start | end | center | stretch;</code>: 개별 아이템 내부 콘텐츠의 가로 정렬 (`justify-items`보다 우선).</li>
    <li><code>align-self: start | end | center | stretch;</code>: 개별 아이템 내부 콘텐츠의 세로 정렬 (`align-items`보다 우선).</li>
    <li><code>place-self: &lt;align-self&gt; &lt;justify-self&gt;;</code>: 위 두 속성의 단축 속성.</li>
  </ul>

  <div class="example">
      <h4>Grid 예시</h4>
      <div class="grid-container" style="grid-template-columns: auto 1fr; grid-template-rows: 50px 150px; grid-template-areas: 'header header' 'sidebar main';">
          <div class="grid-item" style="grid-area: header; background-color: coral;">Header</div>
          <div class="grid-item" style="grid-area: sidebar; background-color: lightskyblue;">Sidebar</div>
          <div class="grid-item" style="grid-area: main; background-color: lightyellow;">Main Content</div>
      </div>
      <br>
       <div class="grid-container" style="grid-template-columns: repeat(3, 1fr);">
          <div class="grid-item" style="grid-column: 1 / 3; background-color: lightgreen;">Item 1 (1열~2열 차지)</div>
          <div class="grid-item" style="grid-row: 2 / 4; background-color: lightpink;">Item 2 (2행~3행 차지)</div>
          <div class="grid-item">Item 3</div>
          <div class="grid-item">Item 4</div>
          <div class="grid-item">Item 5</div>
      </div>
  </div>

  <pre><code class="language-css">.wrapper {
  display: grid;
  /* 3개의 열 정의: 첫 열은 최소 100px, 최대 1fr 비율, 가운데 열 1fr, 마지막 열 자동 크기 */
  grid-template-columns: minmax(100px, 1fr) 1fr auto;
  /* 2개의 행 정의: 첫 행 자동 크기, 두 번째 행 최소 200px */
  grid-template-rows: auto minmax(200px, auto);
  /* 행과 열 사이 간격 */
  gap: 20px 10px; /* row-gap column-gap */
  /* 그리드 영역 이름 정의 */
  grid-template-areas:
    "header header header"
    "sidebar main main";
  height: 400px; /* 확인용 높이 */
  border: 1px solid blue;
}

.item-header {
  grid-area: header; /* 이름으로 배치 */
  background-color: lightcoral;
}
.item-sidebar {
  grid-area: sidebar;
  background-color: lightblue;
}
.item-main {
  grid-area: main;
  background-color: lightgreen;
}

/* 다른 예: 라인 번호 사용 */
.another-item {
  grid-column: 1 / 3; /* 첫 번째 열 라인에서 세 번째 열 라인 전까지 (1, 2열 차지) */
  grid-row: 2 / span 2; /* 두 번째 행 라인에서 시작해서 2개 행 차지 */
  background-color: lightsalmon;
  justify-self: center; /* 이 아이템만 가로 중앙 정렬 */
  align-self: end; /* 이 아이템만 세로 하단 정렬 */
}</code></pre>
</section>


<section id="transitions">
    <h2>전환 (Transitions)</h2>
    <p>CSS <code>transition</code> 속성은 요소의 속성 값이 변경될 때 부드러운 시각적 효과(애니메이션)를 적용합니다. 예를 들어, 마우스를 올렸을 때(<code>:hover</code>) 색상이나 크기가 갑자기 변하는 대신 서서히 변하게 할 수 있습니다.</p>
    <ul>
        <li><code>transition-property</code>: 전환 효과를 적용할 CSS 속성 이름 (예: <code>width</code>, <code>background-color</code>, <code>all</code>).</li>
        <li><code>transition-duration</code>: 전환 효과가 지속되는 시간 (예: <code>0.3s</code>, <code>500ms</code>).</li>
        <li><code>transition-timing-function</code>: 전환 효과의 속도 곡선 (<code>linear</code>, <code>ease</code>(기본값), <code>ease-in</code>, <code>ease-out</code>, <code>ease-in-out</code>, <code>cubic-bezier(...)</code>).</li>
        <li><code>transition-delay</code>: 전환 효과가 시작되기 전의 지연 시간.</li>
        <li><code>transition</code>: 위 네 가지 속성을 한 번에 지정하는 단축 속성. (<code>property duration timing-function delay</code> 순서)</li>
    </ul>
    <div class="example">
        <h4>Transition 예시</h4>
        <button class="transition-button">마우스를 올려보세요</button>
        <style>
            .transition-button {
                padding: 10px 20px;
                background-color: dodgerblue;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                /* 전환 효과 정의 */
                transition: background-color 0.4s ease-in-out, transform 0.3s ease;
            }
            .transition-button:hover {
                background-color: darkblue;
                transform: scale(1.1); /* 약간 확대 */
            }
        </style>
    </div>
    <pre><code class="language-css">.button {
  background-color: blue;
  color: white;
  padding: 10px 15px;
  border: none;
  /* 모든 속성 변경에 대해 0.5초 동안 ease-out 효과 적용 */
  transition: all 0.5s ease-out;
}

.button:hover {
  background-color: darkblue;
  border-radius: 8px; /* 모서리도 부드럽게 변경됨 */
  transform: translateY(-3px); /* 약간 위로 이동 */
}</code></pre>
    <p class="note">모든 CSS 속성에 전환 효과를 적용할 수 있는 것은 아닙니다. 색상, 크기, 위치, 변형(transform) 등 계산 가능한 중간값을 가지는 속성들에 주로 사용됩니다.</p>
</section>

<section id="transforms">
    <h2>변형 (Transforms)</h2>
    <p><code>transform</code> 속성은 요소의 위치, 크기, 각도 등을 변경하여 시각적인 변형 효과를 줍니다. 레이아웃에 영향을 주지 않고(차지하는 공간은 그대로) 모양만 바꿉니다.</p>
    <ul>
        <li><code>transform: none | &lt;transform-function&gt;+;</code>: 하나 이상의 변형 함수를 적용합니다.
            <ul>
                <li><code>translate(x, y)</code> / <code>translateX(x)</code> / <code>translateY(y)</code>: 요소를 수평/수직으로 이동.</li>
                <li><code>scale(x, y)</code> / <code>scaleX(x)</code> / <code>scaleY(y)</code>: 요소 크기를 수평/수직으로 조절 (1은 원래 크기, 0.5는 절반, 2는 두 배).</li>
                <li><code>rotate(angle)</code>: 요소를 지정된 각도(<code>deg</code>)만큼 회전.</li>
                <li><code>skew(x-angle, y-angle)</code> / <code>skewX(angle)</code> / <code>skewY(angle)</code>: 요소를 수평/수직으로 기울임.</li>
                <li><code>matrix(...)</code>: 2D 변형을 행렬로 직접 지정 (고급).</li>
            </ul>
        </li>
        <li><code>transform-origin: x y z;</code>: 변형의 기준점 설정 (기본값: <code>50% 50%</code> 또는 <code>center center</code>).</li>
        <li>3D 변형 함수도 있습니다: <code>translate3d</code>, <code>scale3d</code>, <code>rotate3d</code>, <code>rotateX</code>, <code>rotateY</code>, <code>rotateZ</code>, <code>perspective</code> 등.</li>
    </ul>
    <div class="example">
        <h4>Transform 예시</h4>
        <div class="transform-box">변형 박스</div>
        <style>
            .transform-box {
                width: 100px; height: 100px; background-color: crimson; color: white;
                display: flex; align-items: center; justify-content: center;
                margin: 50px;
                transition: transform 0.5s ease; /* 변형에 전환 효과 추가 */
                transform-origin: bottom right; /* 기준점을 오른쪽 아래로 */
            }
            .transform-box:hover {
                transform: rotate(-15deg) scale(1.2) translateX(20px); /* 여러 변형 동시 적용 */
            }
        </style>
    </div>
    <pre><code class="language-css">.element {
  width: 150px;
  height: 150px;
  background-color: orange;
  transition: transform 0.4s ease;
}

.element:hover {
  /* 시계 방향 45도 회전하고, 가로 1.5배 확대 */
  transform: rotate(45deg) scaleX(1.5);
  transform-origin: center center; /* 변형 기준점 중앙 (기본값) */
}</code></pre>
</section>


<section id="animations">
    <h2>애니메이션 (Animations)</h2>
    <p>CSS <code>animation</code> 속성은 <code>@keyframes</code> 규칙과 함께 사용하여 요소에 복잡한 애니메이션 효과를 적용합니다. Transition이 상태 변화에 따른 단일 효과라면, Animation은 여러 단계(keyframes)를 거치는 복잡한 움직임을 만들 수 있습니다.</p>
    <ol>
        <li><strong><code>@keyframes</code> 규칙 정의:</strong> 애니메이션의 중간 단계별 스타일을 정의합니다. `from`(0%)과 `to`(100%) 또는 퍼센트(%) 값으로 키프레임을 지정합니다.
        <pre><code class="language-css">@keyframes slide-in {
  from { /* 0% */
    transform: translateX(-100%);
    opacity: 0;
  }
  to { /* 100% */
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}</code></pre>
        </li>
        <li><strong>요소에 애니메이션 적용:</strong> <code>animation</code> 관련 속성을 사용하여 <code>@keyframes</code> 애니메이션을 요소에 적용합니다.
            <ul>
                <li><code>animation-name</code>: 적용할 <code>@keyframes</code> 이름.</li>
                <li><code>animation-duration</code>: 애니메이션 1회 지속 시간.</li>
                <li><code>animation-timing-function</code>: 속도 곡선 (Transition과 동일).</li>
                <li><code>animation-delay</code>: 시작 전 지연 시간.</li>
                <li><code>animation-iteration-count</code>: 반복 횟수 (`infinite`는 무한 반복).</li>
                <li><code>animation-direction</code>: 반복 시 방향 (`normal`, `reverse`, `alternate`(정방향-역방향 반복), `alternate-reverse`).</li>
                <li><code>animation-fill-mode</code>: 애니메이션 시작 전/후 요소 스타일 (`none`, `forwards`(끝난 상태 유지), `backwards`(시작 전 0% 상태 적용), `both`).</li>
                <li><code>animation-play-state</code>: 애니메이션 재생 상태 제어 (`running`, `paused`).</li>
                <li><code>animation</code>: 위 속성들을 한 번에 지정하는 단축 속성.</li>
            </ul>
        </li>
    </ol>
     <div class="example">
        <h4>Animation 예시</h4>
        <div class="animated-box">애니메이션!</div>
        <style>
            @keyframes bounce {
              0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
              40% { transform: translateY(-30px); }
              60% { transform: translateY(-15px); }
            }
            .animated-box {
                width: 100px; height: 100px; background-color: mediumpurple; color: white;
                border-radius: 50%; /* 원 모양 */
                display: flex; align-items: center; justify-content: center; margin: 30px;
                /* 애니메이션 적용 */
                animation-name: bounce;
                animation-duration: 2s;
                animation-timing-function: ease-in-out;
                animation-iteration-count: infinite; /* 무한 반복 */
            }
        </style>
    </div>
    <pre><code class="language-css">.element-to-animate {
  width: 100px;
  height: 100px;
  background-color: teal;
  position: relative; /* 애니메이션을 위해 필요할 수 있음 */

  /* 애니메이션 적용 (단축 속성) */
  /* 이름: slide-in, 시간: 1초, 속도: ease-out, 지연: 0.5초, 반복: 1회, 방향: 정방향, 끝난 상태 유지 */
  animation: slide-in 1s ease-out 0.5s 1 normal forwards;
}

.pulsing-dot {
    width: 20px; height: 20px; background-color: red; border-radius: 50%;
    /* 이름: pulse, 시간: 1.5초, 속도: ease-in-out, 반복: 무한, 방향: 번갈아 */
    animation: pulse 1.5s ease-in-out infinite alternate;
}</code></pre>
</section>

<section id="responsive">
    <h2>반응형 웹과 미디어 쿼리</h2>
    <p>반응형 웹 디자인(Responsive Web Design, RWD)은 다양한 디바이스(데스크탑, 태블릿, 모바일 등)의 화면 크기와 해상도에 맞추어 웹 페이지의 레이아웃과 디자인이 자동으로 조절되도록 하는 접근 방식입니다.</p>
    <p><strong>미디어 쿼리 (Media Queries)</strong>는 반응형 웹 디자인을 구현하는 핵심 기술로, 특정 조건(예: 뷰포트 너비, 기기 방향)에 따라 다른 CSS 규칙을 적용할 수 있게 합니다.</p>
    <p>먼저 HTML <code>&lt;head&gt;</code>에 <strong>뷰포트 메타 태그</strong>를 추가하여 모바일 기기에서 페이지가 적절한 비율로 표시되도록 설정하는 것이 일반적입니다.</p>
    <pre><code class="language-html">&lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;</code></pre>
    <p>미디어 쿼리 구문은 <code>@media</code> 규칙을 사용합니다.</p>
    <ul>
        <li><code>@media media-type and (condition) { ... CSS rules ... }</code></li>
        <li><code>media-type</code>: <code>all</code>, <code>print</code>, <code>screen</code> 등 (보통 <code>screen</code> 또는 생략).</li>
        <li><code>condition</code>: <code>width</code>, <code>height</code>, <code>min-width</code>, <code>max-width</code>, <code>orientation</code>(<code>portrait</code>|<code>landscape</code>) 등. 논리 연산자 <code>and</code>, <code>not</code>, 쉼표(<code>,</code> - OR) 사용 가능.</li>
    </ul>
    <p><strong>모바일 우선 (Mobile First)</strong> 접근 방식: 작은 화면(모바일)을 위한 기본 스타일을 먼저 작성하고, 미디어 쿼리를 사용하여 더 큰 화면(태블릿, 데스크탑)을 위한 스타일을 점진적으로 추가하는 방식이 권장됩니다.</p>

    <pre><code class="language-css">/* 모바일 기본 스타일 (Mobile First) */
.container {
  width: 95%;
  margin: 0 auto;
}
.sidebar {
  display: none; /* 모바일에서는 숨김 */
}
.main-content {
  width: 100%;
}
nav ul li {
  display: block; /* 모바일 메뉴 세로 배치 */
  margin-bottom: 10px;
}

/* 태블릿 크기 이상 (예: 768px 이상) */
@media screen and (min-width: 768px) {
  .container {
    width: 90%;
    display: flex; /* Flexbox 레이아웃 사용 시작 */
    gap: 20px;
  }
  .sidebar {
    display: block; /* 사이드바 표시 */
    flex: 0 0 200px; /* 너비 200px 고정 */
  }
  .main-content {
    flex: 1; /* 남은 공간 차지 */
  }
  nav ul li {
    display: inline-block; /* 메뉴 가로 배치 */
    margin-right: 15px;
    margin-bottom: 0;
  }
}

/* 데스크탑 크기 이상 (예: 1024px 이상) */
@media screen and (min-width: 1024px) {
  .container {
    width: 80%;
    max-width: 1200px; /* 최대 너비 제한 */
  }
  h1 {
    font-size: 3rem; /* 데스크탑에서 제목 크게 */
  }
}

/* 가로 모드일 때 */
@media screen and (orientation: landscape) {
  .some-element {
    /* 가로 모드일 때만 적용될 스타일 */
  }
}

/* 인쇄 시 스타일 */
@media print {
  body { font-family: serif; color: black; }
  a { text-decoration: none; color: black; }
  .sidebar, nav, footer { display: none; } /* 인쇄 시 불필요한 요소 숨김 */
}
</code></pre>
    <p class="note">미디어 쿼리의 중단점(breakpoint) 값(예: 768px, 1024px)은 프로젝트의 디자인과 콘텐츠에 따라 적절하게 설정해야 합니다.</p>
</section>

<section id="variables">
    <h2>CSS 변수 (Custom Properties)</h2>
    <p>CSS 변수(정식 명칭: Custom Properties for Cascading Variables)는 CSS 값(색상, 크기 등)을 재사용 가능한 변수로 정의하고 사용할 수 있게 하는 기능입니다. 유지보수성을 높이고 테마 변경 등을 쉽게 구현할 수 있습니다.</p>
    <ul>
        <li><strong>변수 정의:</strong> 두 개의 하이픈(<code>--</code>)으로 시작하는 이름을 사용하며, 주로 루트 요소(<code>:root</code> - HTML 문서 전체에 적용)나 특정 요소 범위 내에서 정의합니다.
        <pre><code class="language-css">:root {
  --primary-color: #007bff;
  --secondary-color: #6c757d;
  --base-font-size: 16px;
  --main-font: Arial, sans-serif;
  --padding-standard: 15px;
}</code></pre></li>
        <li><strong>변수 사용:</strong> <code>var()</code> 함수를 사용하여 정의된 변수 값을 가져옵니다. 두 번째 인자로 기본값(fallback)을 지정할 수 있습니다.
        <pre><code class="language-css">body {
  font-family: var(--main-font);
  font-size: var(--base-font-size);
  color: var(--secondary-color);
}

button {
  background-color: var(--primary-color);
  color: white;
  padding: var(--padding-standard, 10px); /* 기본값 10px 지정 */
}

a {
  color: var(--primary-color);
}</code></pre></li>
        <li><strong>변수 재정의 (스코프):</strong> 특정 요소 내에서 변수를 재정의하면 해당 요소와 그 자식 요소들에게만 적용됩니다. 이를 이용해 테마 변경 등을 구현할 수 있습니다.
        <pre><code class="language-css">.dark-theme {
  --primary-color: #ffffff;
  --secondary-color: #dddddd;
  --background-color: #333333; /* 새로운 변수 정의 또는 기존 변수 재정의 */
}

.dark-theme body { /* .dark-theme 클래스가 적용된 요소 내부의 body (예시) */
  background-color: var(--background-color);
  color: var(--secondary-color);
}
.dark-theme button {
  background-color: var(--primary-color);
  color: var(--background-color); /* 재정의된 값 사용 */
}</code></pre></li>
    </ul>
    <p>CSS 변수는 JavaScript로도 접근하고 수정할 수 있어 동적인 테마 변경 등에 활용도가 높습니다.</p>
</section>

<section id="pseudo">
  <h2>가상 클래스와 가상 요소</h2>
  <p>CSS에는 실제 HTML 구조에는 없지만 특정 상태나 위치에 따라 요소를 선택하거나 스타일을 추가할 수 있는 특별한 선택자들이 있습니다.</p>

  <h3>가상 클래스 (Pseudo-classes)</h3>
  <p>콜론(<code>:</code>) 하나로 시작하며, 요소의 특별한 상태를 나타냅니다.</p>
  <ul>
      <li><strong>링크/사용자 동작 관련:</strong>
          <ul>
              <li><code>:link</code>: 방문하지 않은 링크.</li>
              <li><code>:visited</code>: 방문한 링크 (프라이버시 제한으로 스타일 변경 제한적).</li>
              <li><code>:hover</code>: 마우스 포인터가 올라가 있는 요소.</li>
              <li><code>:active</code>: 사용자가 활성화한(클릭 중인) 요소.</li>
              <li><code>:focus</code>: 현재 포커스를 받은 요소 (주로 폼 요소, 링크).</li>
              <li><code>:focus-within</code>: 요소 자체 또는 그 자손 요소가 포커스를 받았을 때 해당 요소 선택.</li>
          </ul>
      </li>
      <li><strong>폼 관련:</strong>
          <ul>
              <li><code>:checked</code>: 체크된 상태의 라디오 버튼, 체크박스.</li>
              <li><code>:disabled</code>: 비활성화된 폼 요소.</li>
              <li><code>:enabled</code>: 활성화된 폼 요소.</li>
              <li><code>:required</code>: `required` 속성이 있는 폼 요소.</li>
              <li><code>:optional</code>: `required` 속성이 없는 폼 요소.</li>
              <li><code>:valid</code> / <code>:invalid</code>: 입력 값이 유효성 검사 규칙에 맞거나 맞지 않을 때.</li>
              <li><code>:in-range</code> / <code>:out-of-range</code>: 숫자 입력 값이 지정된 범위 내/외일 때.</li>
              <li><code>:read-only</code> / <code>:read-write</code>: 읽기 전용 또는 수정 가능한 폼 요소.</li>
          </ul>
      </li>
      <li><strong>구조적 가상 클래스:</strong>
          <ul>
              <li><code>:root</code>: 문서의 루트 요소 (HTML에서는 <code>&lt;html&gt;</code>).</li>
              <li><code>:first-child</code> / <code>:last-child</code>: 형제 요소 중 첫 번째/마지막 자식 요소.</li>
              <li><code>:nth-child(n)</code>: 형제 요소 중 n번째 자식 요소. `n`은 숫자, 키워드(<code>odd</code>, <code>even</code>), 또는 수식(<code>2n+1</code>) 가능.</li>
              <li><code>:nth-last-child(n)</code>: 형제 요소 중 끝에서 n번째 자식 요소.</li>
              <li><code>:first-of-type</code> / <code>:last-of-type</code>: 같은 타입의 형제 요소 중 첫 번째/마지막 요소.</li>
              <li><code>:nth-of-type(n)</code> / <code>:nth-last-of-type(n)</code>: 같은 타입의 형제 요소 중 (끝에서) n번째 요소.</li>
              <li><code>:only-child</code>: 형제가 없는 유일한 자식 요소.</li>
              <li><code>:only-of-type</code>: 같은 타입의 형제가 없는 유일한 요소.</li>
              <li><code>:empty</code>: 자식 요소(텍스트 노드 포함)가 없는 요소.</li>
          </ul>
      </li>
      <li><strong>부정 가상 클래스:</strong>
          <ul>
              <li><code>:not(selector)</code>: 괄호 안의 선택자와 일치하지 않는 요소 선택.</li>
          </ul>
      </li>
  </ul>
  <pre><code class="language-css">/* 링크 스타일 순서: LVHA (Link-Visited-Hover-Active) */
a:link { color: blue; }
a:visited { color: purple; }
a:hover { color: red; text-decoration: underline; }
a:active { color: orange; }

input:focus { border-color: blue; outline: none; box-shadow: 0 0 3px blue; }

input[type="checkbox"]:checked + label { font-weight: bold; }

/* 짝수 행 배경색 다르게 (줄무늬 테이블) */
tr:nth-child(even) { background-color: #f2f2f2; }

/* 리스트 첫 항목과 마지막 항목 스타일 */
li:first-child { padding-top: 0; }
li:last-child { border-bottom: none; }

/* p 태그 중 첫 번째 p 태그 */
p:first-of-type { font-weight: bold; }

/* 클래스가 .button이 아닌 모든 버튼 */
button:not(.disabled):hover { background-color: lightgray; }
</code></pre>

  <h3>가상 요소 (Pseudo-elements)</h3>
  <p>콜론 두 개(<code>::</code>)로 시작하며 (구형 브라우저 호환을 위해 <code>:</code> 하나도 가능하지만 <code>::</code> 권장), 요소의 특정 부분을 선택하여 스타일을 적용하거나 내용을 삽입합니다.</p>
  <ul>
      <li><code>::before</code>: 요소 내용의 시작 부분 앞에 가상 요소를 생성하고 스타일 적용/내용 삽입 (<code>content</code> 속성 필수).</li>
      <li><code>::after</code>: 요소 내용의 끝 부분 뒤에 가상 요소를 생성하고 스타일 적용/내용 삽입 (<code>content</code> 속성 필수). (Clearfix 등에 사용)</li>
      <li><code>::first-letter</code>: 블록 레벨 요소의 첫 번째 글자 선택.</li>
      <li><code>::first-line</code>: 블록 레벨 요소의 첫 번째 줄 선택.</li>
      <li><code>::selection</code>: 사용자가 드래그하여 선택한 텍스트 부분.</li>
      <li><code>::placeholder</code>: 폼 입력 필드의 플레이스홀더 텍스트.</li>
      <li><code>::marker</code>: 리스트 항목(<code>&lt;li&gt;</code>)의 마커(불릿, 숫자).</li>
  </ul>
  <pre><code class="language-css">/* 필수 입력 필드 뒤에 별표(*) 추가 */
label.required::after {
  content: " *";
  color: red;
}

/* 인용구 앞뒤에 따옴표 모양 추가 */
blockquote::before {
  content: "“";
  font-size: 3em;
  color: gray;
}
blockquote::after {
  content: "”";
  font-size: 3em;
  color: gray;
}

/* 첫 글자 스타일 (단락 시작 장식) */
p::first-letter {
  font-size: 2em;
  font-weight: bold;
  color: darkred;
  float: left; /* 글자가 텍스트를 감싸도록 */
  margin-right: 0.1em;
  line-height: 1;
}

/* 사용자가 선택한 텍스트 스타일 변경 */
::selection {
  background-color: yellow;
  color: black;
}

/* 플레이스홀더 텍스트 스타일 */
input::placeholder {
  color: #aaa;
  font-style: italic;
}

/* 리스트 마커 스타일 변경 */
ul li::marker {
  content: "🚀 "; /* 유니코드 이모지 사용 */
  color: blue;
}
</code></pre>
</section>

<br><br><br>
<p style="text-align:center; font-style: italic;">CSS를 통해 아름답고 반응성 좋은 웹 페이지를 만들어 보세요!</p>
<br><br><br>

<script src="../js/script.js?ver=1"></script>

</body>
</html>