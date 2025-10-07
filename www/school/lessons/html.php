<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title> HTML 강좌</title>
  <link rel="stylesheet" href="../css/lessons_style.css?ver=1">
  <style>
    pre { background-color: #f4f4f4; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
    code { font-family: Consolas, monospace; }
    .toc { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background-color: #f9f9f9; }
    .toc h2 { margin-top: 0; }
    h2 { border-bottom: 2px solid #eee; padding-bottom: 5px; margin-top: 40px; }
    table { border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>

<h1> HTML 강좌</h1>
<p>이 페이지는 웹 개발 학습에 필요한 주요 HTML 태그와 개념들을 다룹니다.</p>

<div class="toc">
  <h2>📖 목차</h2>
  <ul>
    <li><a href="#intro">HTML 소개</a></li>
    <li><a href="#structure">기본 구조 및 Head 요소</a></li>
    <li><a href="#semantic">시맨틱(Semantic) 태그</a></li>
    <li><a href="#texttags">텍스트 관련 태그</a></li>
    <li><a href="#inlineblock">인라인 요소와 블록 요소</a></li>
    <li><a href="#attributes">주요 속성 (Attributes)</a></li>
    <li><a href="#link">링크 태그 (a)</a></li>
    <li><a href="#image">이미지 태그 (img)</a></li>
    <li><a href="#list">리스트 태그 (ul, ol, dl)</a></li>
    <li><a href="#table">테이블 태그 (table)</a></li>
    <li><a href="#form">폼 태그 (form)</a></li>
    <li><a href="#multimedia">멀티미디어 태그 (audio, video, iframe)</a></li>
    <li><a href="#entities">HTML 엔티티</a></li>
    <li><a href="#comments">주석 (Comments)</a></li>
  </ul>
</div>

<section id="intro">
  <h2>HTML 소개</h2>
  <p>HTML은 Hyper Text Markup Language의 약자로, 웹 페이지의 구조와 콘텐츠를 정의하는 표준 마크업 언어입니다. 태그(Tag)를 사용하여 웹 브라우저에 내용이 어떻게 표시될지 알려줍니다.</p>
</section>

<section id="structure">
  <h2>HTML 기본 구조 및 Head 요소</h2>
  <p>모든 HTML 문서는 기본적인 구조를 가집니다. <code>&lt;head&gt;</code> 섹션에는 문서 자체에 대한 정보(메타데이터), CSS 링크, 스크립트 등이 포함됩니다.</p>
  <pre><code>&lt;!DOCTYPE html&gt; &lt;!-- HTML5 문서임을 선언 --&gt;
&lt;html lang="ko"&gt; &lt;!-- 문서의 주 언어 설정 --&gt;
&lt;head&gt;
  &lt;meta charset="UTF-8"&gt; &lt;!-- 문자 인코딩 설정 (UTF-8 권장) --&gt;
  &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt; &lt;!-- 모바일 기기 뷰포트 설정 --&gt;
  &lt;meta name="description" content="이 페이지는 HTML 기초를 다룹니다."&gt; &lt;!-- 페이지 설명 (검색 엔진용) --&gt;
  &lt;meta name="keywords" content="HTML, 웹 개발, 기초"&gt; &lt;!-- 페이지 키워드 (검색 엔진용) --&gt;
  &lt;title&gt;페이지 제목&lt;/title&gt; &lt;!-- 브라우저 탭에 표시될 제목 --&gt;
  &lt;link rel="stylesheet" href="style.css"&gt; &lt;!-- 외부 CSS 파일 연결 --&gt;
  &lt;script src="script.js" defer&gt;&lt;/script&gt; &lt;!-- 외부 JavaScript 파일 연결 (defer: HTML 파싱 후 실행) --&gt;
  &lt;style&gt;
    /* 페이지 내부에 직접 CSS 스타일 정의 */
    body { font-family: sans-serif; }
  &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
  &lt;!-- 웹 페이지에 실제로 표시될 콘텐츠 --&gt;
  &lt;h1&gt;메인 제목&lt;/h1&gt;
  &lt;p&gt;여기에 내용이 들어갑니다.&lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;
</code></pre>
</section>

<section id="semantic">
    <h2>시맨틱(Semantic) 태그</h2>
    <p>HTML5에서는 문서의 구조를 더 의미론적으로 나타내기 위한 태그들이 도입되었습니다. 이는 코드 가독성, 접근성, SEO(검색 엔진 최적화)에 도움을 줍니다.</p>
    <ul>
        <li><code>&lt;header&gt;</code>: 페이지나 섹션의 머리말 영역 (로고, 제목, 네비게이션 등)</li>
        <li><code>&lt;nav&gt;</code>: 주요 네비게이션 링크 영역</li>
        <li><code>&lt;main&gt;</code>: 페이지의 핵심 콘텐츠 영역 (문서 당 한 번만 사용 권장)</li>
        <li><code>&lt;article&gt;</code>: 독립적으로 배포하거나 재사용될 수 있는 콘텐츠 (블로그 글, 뉴스 기사 등)</li>
        <li><code>&lt;section&gt;</code>: 문서의 일반적인 구획 (관련 있는 콘텐츠 그룹)</li>
        <li><code>&lt;aside&gt;</code>: 주요 콘텐츠와 간접적으로 관련된 부가 정보 영역 (사이드바, 광고 등)</li>
        <li><code>&lt;footer&gt;</code>: 페이지나 섹션의 꼬리말 영역 (저작권 정보, 관련 링크 등)</li>
        <li><code>&lt;figure&gt;</code>, <code>&lt;figcaption&gt;</code>: 이미지, 도표 등과 그 설명을 묶는 데 사용</li>
    </ul>
    <pre><code>&lt;body&gt;
  &lt;header&gt;
    &lt;h1&gt;웹사이트 로고/제목&lt;/h1&gt;
    &lt;nav&gt;
      &lt;ul&gt;
        &lt;li&gt;&lt;a href="/"&gt;홈&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="/about"&gt;소개&lt;/a&gt;&lt;/li&gt;
      &lt;/ul&gt;
    &lt;/nav&gt;
  &lt;/header&gt;

  &lt;main&gt;
    &lt;article&gt;
      &lt;h2&gt;글 제목&lt;/h2&gt;
      &lt;p&gt;글 내용...&lt;/p&gt;
      &lt;figure&gt;
        &lt;img src="image.jpg" alt="관련 이미지"&gt;
        &lt;figcaption&gt;이미지 설명&lt;/figcaption&gt;
      &lt;/figure&gt;
    &lt;/article&gt;
    &lt;section&gt;
      &lt;h2&gt;관련 섹션&lt;/h2&gt;
      &lt;p&gt;섹션 내용...&lt;/p&gt;
    &lt;/section&gt;
  &lt;/main&gt;

  &lt;aside&gt;
    &lt;h3&gt;부가 정보&lt;/h3&gt;
    &lt;p&gt;광고나 관련 링크 등&lt;/p&gt;
  &lt;/aside&gt;

  &lt;footer&gt;
    &lt;p&gt;&copy; 2025 웹사이트 이름. 모든 권리 보유.&lt;/p&gt;
  &lt;/footer&gt;
&lt;/body&gt;
</code></pre>
</section>

<section id="texttags">
  <h2>텍스트 관련 태그</h2>
  <p>HTML은 텍스트의 구조와 의미를 나타내는 다양한 태그를 제공합니다.</p>
  <ul>
    <li><code>&lt;h1&gt;</code> ~ <code>&lt;h6&gt;</code>: 제목 태그 (숫자가 작을수록 중요도가 높음)</li>
    <li><code>&lt;p&gt;</code>: 문단 (Paragraph)</li>
    <li><code>&lt;strong&gt;</code>: 중요한 내용 강조 (보통 굵게 표시)</li>
    <li><code>&lt;em&gt;</code>: 강조 (Emphasis) (보통 기울임꼴로 표시)</li>
    <li><code>&lt;b&gt;</code>: 시각적으로 굵게 표시 (특별한 중요성 없음)</li>
    <li><code>&lt;i&gt;</code>: 시각적으로 기울임꼴 표시 (특별한 강조 없음, 아이콘 등에 사용되기도 함)</li>
    <li><code>&lt;u&gt;</code>: 밑줄 (Underline) (링크와 혼동될 수 있어 사용 주의)</li>
    <li><code>&lt;s&gt;</code>: 취소선 (Strikethrough) (더 이상 정확하지 않거나 관련 없는 내용)</li>
    <li><code>&lt;mark&gt;</code>: 하이라이트 (Highlight)</li>
    <li><code>&lt;sup&gt;</code>: 위 첨자 (Superscript)</li>
    <li><code>&lt;sub&gt;</code>: 아래 첨자 (Subscript)</li>
    <li><code>&lt;small&gt;</code>: 작은 텍스트 (부가 정보, 저작권 등)</li>
    <li><code>&lt;br&gt;</code>: 줄 바꿈 (Line Break)</li>
    <li><code>&lt;hr&gt;</code>: 수평선 (Thematic Break)</li>
    <li><code>&lt;pre&gt;</code>: 서식이 미리 지정된 텍스트 (공백과 줄 바꿈 유지)</li>
    <li><code>&lt;code&gt;</code>: 코드 조각</li>
    <li><code>&lt;blockquote&gt;</code>: 인용 블록</li>
    <li><code>&lt;q&gt;</code>: 짧은 인용문</li>
  </ul>
  <pre><code>&lt;h1&gt;가장 큰 제목&lt;/h1&gt;
&lt;h6&gt;가장 작은 제목&lt;/h6&gt;
&lt;p&gt;이것은 &lt;strong&gt;중요한&lt;/strong&gt; 문단입니다. &lt;em&gt;강조&lt;/em&gt;할 수도 있습니다.&lt;/p&gt;
&lt;p&gt;텍스트를 &lt;b&gt;굵게&lt;/b&gt;, &lt;i&gt;기울여서&lt;/i&gt;, &lt;u&gt;밑줄&lt;/u&gt; 치거나, &lt;s&gt;취소선&lt;/s&gt;을 넣을 수 있습니다.&lt;/p&gt;
&lt;p&gt;이 부분은 &lt;mark&gt;하이라이트&lt;/mark&gt; 됩니다.&lt;/p&gt;
&lt;p&gt;E=mc&lt;sup&gt;2&lt;/sup&gt;, H&lt;sub&gt;2&lt;/sub&gt;O&lt;/p&gt;
&lt;p&gt;&lt;small&gt;저작권 정보.&lt;/small&gt;&lt;/p&gt;
첫 번째 줄.&lt;br&gt;두 번째 줄.
&lt;hr&gt;
&lt;pre&gt;
  이 텍스트는
  공백과 줄바꿈이
  그대로 유지됩니다.
&lt;/pre&gt;
&lt;p&gt;인라인 코드는 &lt;code&gt;console.log('Hello')&lt;/code&gt; 처럼 씁니다.&lt;/p&gt;
&lt;blockquote cite="출처 URL"&gt;
  &lt;p&gt;긴 인용문입니다.&lt;/p&gt;
&lt;/blockquote&gt;
&lt;p&gt;그는 &lt;q&gt;안녕하세요&lt;/q&gt;라고 말했습니다.&lt;/p&gt;
</code></pre>
</section>

<section id="inlineblock">
    <h2>인라인 요소와 블록 요소</h2>
    <p>HTML 요소는 크게 인라인(inline) 요소와 블록(block) 요소로 나뉩니다.</p>
    <ul>
        <li><strong>블록 요소:</strong> 항상 새 줄에서 시작하고, 사용 가능한 전체 너비를 차지합니다. (예: <code>&lt;h1&gt;</code>-<code>&lt;h6&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;div&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;li&gt;</code>, <code>&lt;table&gt;</code>, <code>&lt;form&gt;</code>, <code>&lt;header&gt;</code>, <code>&lt;footer&gt;</code> 등)</li>
        <li><strong>인라인 요소:</strong> 새 줄에서 시작하지 않고, 필요한 만큼의 너비만 차지합니다. 다른 인라인 요소나 텍스트와 같은 줄에 위치할 수 있습니다. (예: <code>&lt;a&gt;</code>, <code>&lt;span&gt;</code>, <code>&lt;img&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;em&gt;</code>, <code>&lt;input&gt;</code>, <code>&lt;label&gt;</code> 등)</li>
    </ul>
    <p><code>&lt;div&gt;</code>는 특별한 의미 없이 콘텐츠를 그룹화하는 대표적인 블록 요소이며, <code>&lt;span&gt;</code>은 특별한 의미 없이 텍스트 일부를 그룹화하는 대표적인 인라인 요소입니다. 주로 CSS 스타일링이나 JavaScript 조작을 위해 사용됩니다.</p>
    <pre><code>&lt;div style="background-color: lightblue;"&gt;이것은 div 블록 요소입니다. 전체 너비를 차지합니다.&lt;/div&gt;
&lt;p&gt;문단 내에서 &lt;span style="background-color: yellow;"&gt;이 부분은 span 인라인 요소&lt;/span&gt;입니다. 필요한 너비만 차지합니다.&lt;/p&gt;
</code></pre>
</section>

<section id="attributes">
    <h2>주요 속성 (Attributes)</h2>
    <p>HTML 태그는 속성을 가질 수 있으며, 이는 요소에 대한 추가 정보를 제공하거나 동작을 제어합니다.</p>
    <ul>
        <li><code>id</code>: 요소에 고유한 식별자를 부여합니다. (페이지 내에서 유일해야 함. CSS, JavaScript, 내부 링크(#)에서 사용)</li>
        <li><code>class</code>: 요소를 그룹화하는 데 사용되는 클래스 이름을 부여합니다. (여러 요소가 같은 클래스를 가질 수 있음. CSS, JavaScript에서 주로 사용)</li>
        <li><code>style</code>: 요소에 직접 인라인 CSS 스타일을 적용합니다. (권장되는 방식은 아님)</li>
        <li><code>title</code>: 요소에 대한 추가 정보(툴팁)를 제공합니다. (마우스를 올렸을 때 나타남)</li>
        <li><code>lang</code>: 요소 내용의 언어를 지정합니다. (<code>&lt;html&gt;</code> 태그에 주로 사용)</li>
        <li><code>href</code>: <code>&lt;a&gt;</code> 태그에서 이동할 URL을 지정합니다.</li>
        <li><code>src</code>: <code>&lt;img&gt;</code>, <code>&lt;script&gt;</code>, <code>&lt;audio&gt;</code>, <code>&lt;video&gt;</code> 등에서 리소스의 경로를 지정합니다.</li>
        <li><code>alt</code>: <code>&lt;img&gt;</code> 태그에서 이미지를 표시할 수 없을 때 대신 표시될 텍스트 (접근성에 중요)</li>
        <li><code>width</code>, <code>height</code>: 이미지, 비디오 등의 크기를 지정합니다. (단위 없이 쓰면 픽셀)</li>
        <li><code>target</code>: <code>&lt;a&gt;</code> 태그에서 링크를 열 방식을 지정합니다. (<code>_blank</code>: 새 탭/창)</li>
        <li><code>disabled</code>: 폼 요소 등을 비활성화합니다.</li>
        <li><code>readonly</code>: 폼 입력 필드를 읽기 전용으로 만듭니다. (값은 전송됨)</li>
        <li><code>required</code>: 폼 요소가 필수 입력 항목임을 나타냅니다.</li>
        <li><code>placeholder</code>: 폼 입력 필드에 표시될 도움말 텍스트입니다.</li>
    </ul>
    <pre><code>&lt;!-- id와 class 예시 --&gt;
&lt;h2 id="main-title" class="heading important"&gt;제목&lt;/h2&gt;
&lt;p class="text"&gt;내용&lt;/p&gt;

&lt;!-- style 예시 --&gt;
&lt;p style="color: blue; font-size: 18px;"&gt;파란색 큰 글씨&lt;/p&gt;

&lt;!-- title 예시 --&gt;
&lt;abbr title="World Wide Web"&gt;WWW&lt;/abbr&gt;

&lt;!-- lang 예시 (html 태그에 이미 적용됨) --&gt;
&lt;p lang="en"&gt;This paragraph is in English.&lt;/p&gt;

&lt;!-- target 예시 --&gt;
&lt;a href="https://google.com" target="_blank"&gt;구글 (새 탭)&lt;/a&gt;

&lt;!-- 폼 관련 속성 예시 --&gt;
&lt;input type="text" placeholder="이름을 입력하세요" required&gt;
&lt;input type="checkbox" disabled&gt; 비활성 체크박스
&lt;input type="text" value="수정 불가" readonly&gt;
</code></pre>
</section>

<section id="link">
  <h2>링크 태그 (a)</h2>
  <p><code>&lt;a&gt;</code> 태그(Anchor)는 다른 웹 페이지, 같은 페이지 내의 특정 위치, 파일, 이메일 주소 등으로 연결하는 하이퍼링크를 만듭니다.</p>
  <ul>
      <li>외부 페이지 연결: <code>href="https://www.example.com"</code></li>
      <li>내부 페이지 연결: <code>href="/about.html"</code> 또는 <code>href="contact.html"</code></li>
      <li>페이지 내 특정 위치 연결: <code>href="#section-id"</code> (연결 대상 요소에 <code>id="section-id"</code> 속성 필요)</li>
      <li>이메일 링크: <code>href="mailto:user@example.com"</code></li>
      <li>전화 링크: <code>href="tel:+821012345678"</code></li>
      <li>새 탭/창에서 열기: <code>target="_blank"</code> 속성 추가</li>
      <li>다운로드 링크: <code>download</code> 속성 추가 (브라우저가 지원하는 경우)</li>
  </ul>
  <pre><code>&lt;!-- 외부 링크 --&gt;
&lt;a href="https://www.w3schools.com"&gt;W3Schools로 이동&lt;/a&gt;

&lt;!-- 페이지 내 링크 (아래 #entities 섹션으로 이동) --&gt;
&lt;a href="#entities"&gt;HTML 엔티티 섹션으로 가기&lt;/a&gt;

&lt;!-- 새 탭에서 열기 --&gt;
&lt;a href="https://google.com" target="_blank"&gt;구글 (새 탭에서 열기)&lt;/a&gt;

&lt;!-- 이메일 링크 --&gt;
&lt;a href="mailto:info@example.com"&gt;메일 보내기&lt;/a&gt;

&lt;!-- 전화 링크 --&gt;
&lt;a href="tel:+821012345678"&gt;전화 걸기&lt;/a&gt;

&lt;!-- 다운로드 링크 --&gt;
&lt;a href="/files/document.pdf" download&gt;문서 다운로드&lt;/a&gt;
&lt;a href="/images/logo.png" download="my-logo.png"&gt;로고 다운로드 (다른 이름으로)&lt;/a&gt;
</code></pre>
</section>

<section id="image">
  <h2>이미지 태그 (img)</h2>
  <p><code>&lt;img&gt;</code> 태그는 웹 페이지에 이미지를 삽입합니다. 닫는 태그가 없는 빈 태그(empty tag)입니다.</p>
  <ul>
      <li><code>src</code> (필수): 이미지 파일의 경로 (URL 또는 상대/절대 경로)</li>
      <li><code>alt</code> (필수): 이미지를 표시할 수 없을 때 나타날 대체 텍스트 (웹 접근성에 매우 중요)</li>
      <li><code>width</code>, <code>height</code>: 이미지의 너비와 높이 (픽셀 단위 또는 % 단위). 지정하지 않으면 원본 크기로 표시됩니다. CSS로 제어하는 것이 더 권장됩니다.</li>
      <li><code>loading="lazy"</code>: 이미지가 뷰포트에 가까워질 때까지 로딩을 지연시켜 성능을 향상시킵니다 (브라우저 지원 필요).</li>
  </ul>
  <pre><code>&lt;!-- 기본 이미지 삽입 --&gt;
&lt;img src="images/logo.png" alt="회사 로고"&gt;

&lt;!-- 크기 지정 --&gt;
&lt;img src="images/photo.jpg" alt="풍경 사진" width="300" height="200"&gt;

&lt;!-- 외부 이미지 링크 --&gt;
&lt;img src="https://via.placeholder.com/150" alt="Placeholder Image"&gt;

&lt;!-- 지연 로딩 --&gt;
&lt;img src="images/large-image.jpg" alt="큰 이미지" loading="lazy"&gt;

&lt;!-- 링크가 있는 이미지 --&gt;
&lt;a href="https://example.com"&gt;
  &lt;img src="images/button.png" alt="사이트 방문 버튼"&gt;
&lt;/a&gt;
</code></pre>
</section>

<section id="list">
  <h2>리스트 태그 (ul, ol, dl)</h2>
  <p>목록을 표시하는 데 사용되는 태그들입니다.</p>
  <ul>
      <li><code>&lt;ul&gt;</code> (Unordered List): 순서가 없는 목록 (보통 글머리 기호로 표시)</li>
      <li><code>&lt;ol&gt;</code> (Ordered List): 순서가 있는 목록 (보통 숫자로 표시)
          <ul>
              <li><code>type</code> 속성: '1'(숫자-기본값), 'a'(소문자 알파벳), 'A'(대문자 알파벳), 'i'(소문자 로마 숫자), 'I'(대문자 로마 숫자)</li>
              <li><code>start</code> 속성: 시작 번호 지정</li>
              <li><code>reversed</code> 속성: 번호를 역순으로 표시</li>
          </ul>
      </li>
      <li><code>&lt;li&gt;</code> (List Item): <code>&lt;ul&gt;</code> 또는 <code>&lt;ol&gt;</code> 내의 각 항목</li>
      <li><code>&lt;dl&gt;</code> (Definition List): 용어와 설명을 묶은 목록</li>
      <li><code>&lt;dt&gt;</code> (Definition Term): 용어</li>
      <li><code>&lt;dd&gt;</code> (Definition Description): 용어에 대한 설명</li>
  </ul>

  <h3>순서 없는 목록 (ul)</h3>
  <pre><code>&lt;ul&gt;
  &lt;li&gt;항목 1&lt;/li&gt;
  &lt;li&gt;항목 2&lt;/li&gt;
  &lt;li&gt;항목 3
    &lt;ul&gt; &lt;!-- 중첩 목록 --&gt;
      &lt;li&gt;하위 항목 2-1&lt;/li&gt;
      &lt;li&gt;하위 항목 2-2&lt;/li&gt;
    &lt;/ul&gt;
  &lt;/li&gt;
&lt;/ul&gt;
</code></pre>

  <h3>순서 있는 목록 (ol)</h3>
  <pre><code>&lt;h4&gt;기본 숫자 목록&lt;/h4&gt;
&lt;ol&gt;
  &lt;li&gt;첫 번째 단계&lt;/li&gt;
  &lt;li&gt;두 번째 단계&lt;/li&gt;
&lt;/ol&gt;

&lt;h4&gt;알파벳 소문자 목록&lt;/h4&gt;
&lt;ol type="a"&gt;
  &lt;li&gt;옵션 A&lt;/li&gt;
  &lt;li&gt;옵션 B&lt;/li&gt;
&lt;/ol&gt;

&lt;h4&gt;3부터 시작하는 로마 숫자 대문자 목록&lt;/h4&gt;
&lt;ol type="I" start="3"&gt;
  &lt;li&gt;항목 III&lt;/li&gt;
  &lt;li&gt;항목 IV&lt;/li&gt;
&lt;/ol&gt;

&lt;h4&gt;역순 목록&lt;/h4&gt;
&lt;ol reversed&gt;
  &lt;li&gt;마지막 항목&lt;/li&gt;
  &lt;li&gt;두 번째 항목&lt;/li&gt;
  &lt;li&gt;첫 번째 항목&lt;/li&gt;
&lt;/ol&gt;
</code></pre>

  <h3>정의 목록 (dl)</h3>
  <pre><code>&lt;dl&gt;
  &lt;dt&gt;HTML&lt;/dt&gt;
  &lt;dd&gt;Hyper Text Markup Language의 약자로 웹 페이지의 구조를 정의합니다.&lt;/dd&gt;
  &lt;dt&gt;CSS&lt;/dt&gt;
  &lt;dd&gt;Cascading Style Sheets의 약자로 웹 페이지의 스타일을 지정합니다.&lt;/dd&gt;
&lt;/dl&gt;
</code></pre>
</section>

<section id="table">
  <h2>테이블 태그 (table)</h2>
  <p><code>&lt;table&gt;</code> 태그는 데이터를 표 형식으로 표시합니다. 관련 태그들과 함께 사용됩니다.</p>
  <ul>
      <li><code>&lt;table&gt;</code>: 표 전체를 감싸는 컨테이너. <code>border="1"</code> 속성은 오래된 방식으로, CSS로 테두리를 설정하는 것이 좋습니다.</li>
      <li><code>&lt;caption&gt;</code>: 표의 제목이나 설명</li>
      <li><code>&lt;thead&gt;</code>: 표의 머리글(header) 행 그룹</li>
      <li><code>&lt;tbody&gt;</code>: 표의 본문(body) 행 그룹</li>
      <li><code>&lt;tfoot&gt;</code>: 표의 바닥글(footer) 행 그룹 (요약 등)</li>
      <li><code>&lt;tr&gt;</code> (Table Row): 표의 행</li>
      <li><code>&lt;th&gt;</code> (Table Header): 행/열의 제목 셀 (보통 굵게, 가운데 정렬됨)
          <ul>
              <li><code>scope</code> 속성: 해당 헤더 셀이 'col'(열) 또는 'row'(행) 중 무엇에 대한 제목인지 명시 (접근성 향상)</li>
          </ul>
      </li>
      <li><code>&lt;td&gt;</code> (Table Data): 일반 데이터 셀</li>
      <li><code>colspan</code> 속성: 셀이 가로로 여러 열을 병합하도록 지정</li>
      <li><code>rowspan</code> 속성: 셀이 세로로 여러 행을 병합하도록 지정</li>
  </ul>
  <pre><code>&lt;h3&gt;기본 테이블 예시&lt;/h3&gt;
&lt;table&gt;
  &lt;caption&gt;월별 판매 실적&lt;/caption&gt;
  &lt;thead&gt;
    &lt;tr&gt;
      &lt;th scope="col"&gt;월&lt;/th&gt;
      &lt;th scope="col"&gt;상품 A&lt;/th&gt;
      &lt;th scope="col"&gt;상품 B&lt;/th&gt;
    &lt;/tr&gt;
  &lt;/thead&gt;
  &lt;tbody&gt;
    &lt;tr&gt;
      &lt;th scope="row"&gt;1월&lt;/th&gt;
      &lt;td&gt;100&lt;/td&gt;
      &lt;td&gt;150&lt;/td&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;th scope="row"&gt;2월&lt;/th&gt;
      &lt;td&gt;120&lt;/td&gt;
      &lt;td&gt;180&lt;/td&gt;
    &lt;/tr&gt;
  &lt;/tbody&gt;
  &lt;tfoot&gt;
    &lt;tr&gt;
      &lt;th scope="row"&gt;합계&lt;/th&gt;
      &lt;td&gt;220&lt;/td&gt;
      &lt;td&gt;330&lt;/td&gt;
    &lt;/tr&gt;
  &lt;/tfoot&gt;
&lt;/table&gt;

&lt;h3&gt;셀 병합(colspan, rowspan) 예시&lt;/h3&gt;
&lt;table&gt;
  &lt;caption&gt;시간표&lt;/caption&gt;
  &lt;thead&gt;
    &lt;tr&gt;
      &lt;th&gt;시간&lt;/th&gt;
      &lt;th&gt;월요일&lt;/th&gt;
      &lt;th&gt;화요일&lt;/th&gt;
    &lt;/tr&gt;
  &lt;/thead&gt;
  &lt;tbody&gt;
    &lt;tr&gt;
      &lt;th&gt;1교시&lt;/th&gt;
      &lt;td&gt;국어&lt;/td&gt;
      &lt;td rowspan="2"&gt;수학&lt;/td&gt; &lt;!-- 2개 행 병합 --&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;th&gt;2교시&lt;/th&gt;
      &lt;td&gt;영어&lt;/td&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;th&gt;점심&lt;/th&gt;
      &lt;td colspan="2"&gt;점심 시간&lt;/td&gt; &lt;!-- 2개 열 병합 --&gt;
    &lt;/tr&gt;
  &lt;/tbody&gt;
&lt;/table&gt;
</code></pre>
</section>

<section id="form">
  <h2>폼 태그 (form)</h2>
  <p><code>&lt;form&gt;</code> 태그는 사용자로부터 입력을 받기 위한 컨트롤(입력 필드, 버튼 등)을 포함하는 영역을 정의합니다. 입력된 데이터는 서버로 전송될 수 있습니다.</p>
  <ul>
      <li><code>&lt;form&gt;</code>: 폼 요소들을 감싸는 태그
          <ul>
              <li><code>action</code> 속성: 폼 데이터가 전송될 서버 측 URL</li>
              <li><code>method</code> 속성: 데이터를 전송할 HTTP 방식 ('get' 또는 'post')
                  <ul>
                      <li><code>get</code>: 데이터를 URL 뒤에 붙여서 전송 (짧은 데이터, 검색 등). 데이터가 노출됨.</li>
                      <li><code>post</code>: 데이터를 HTTP 요청 본문에 담아 전송 (길거나 민감한 데이터, 파일 업로드 등). 데이터가 노출되지 않음.</li>
                  </ul>
              </li>
              <li><code>enctype</code> 속성: <code>method="post"</code> 일 때 데이터 인코딩 방식 지정. 파일 업로드 시 <code>"multipart/form-data"</code> 사용.</li>
          </ul>
      </li>
      <li><code>&lt;input&gt;</code>: 다양한 종류의 입력 필드를 만드는 태그 (닫는 태그 없음)
          <ul>
              <li><code>type</code> 속성: 입력 필드의 종류 지정
                  <ul>
                      <li><code>text</code>: 일반 텍스트 입력</li>
                      <li><code>password</code>: 비밀번호 입력 (입력 내용 가려짐)</li>
                      <li><code>email</code>: 이메일 주소 입력 (기본 형식 검증)</li>
                      <li><code>number</code>: 숫자 입력 (증감 버튼 제공 가능)</li>
                      <li><code>date</code>: 날짜 선택 (달력 UI 제공)</li>
                      <li><code>checkbox</code>: 여러 개 중 선택 가능한 체크박스 (같은 <code>name</code> 사용)</li>
                      <li><code>radio</code>: 여러 개 중 하나만 선택 가능한 라디오 버튼 (같은 <code>name</code> 사용)</li>
                      <li><code>file</code>: 파일 선택</li>
                      <li><code>submit</code>: 폼 데이터를 <code>action</code>에 지정된 URL로 전송하는 버튼</li>
                      <li><code>reset</code>: 폼 내용을 초기 상태로 되돌리는 버튼</li>
                      <li><code>button</code>: 일반 버튼 (JavaScript로 동작 제어)</li>
                      <li><code>hidden</code>: 사용자에게 보이지 않지만 서버로 전송되는 값</li>
                      <li>기타: <code>tel</code>, <code>url</code>, <code>search</code>, <code>range</code>, <code>color</code> 등</li>
                  </ul>
              </li>
              <li><code>name</code> 속성: 서버에서 데이터를 식별하기 위한 이름 (필수)</li>
              <li><code>value</code> 속성: 입력 필드의 초기 값 또는 전송될 값 (checkbox, radio 등에서 중요)</li>
              <li><code>placeholder</code>: 입력 필드 안에 표시되는 도움말 텍스트</li>
              <li><code>required</code>: 필수 입력 필드임을 나타냄</li>
              <li><code>checked</code>: checkbox나 radio 버튼이 초기에 선택된 상태로 설정</li>
              <li><code>disabled</code>: 입력 필드 비활성화</li>
              <li><code>readonly</code>: 읽기 전용 (값은 전송됨)</li>
              <li><code>min</code>, <code>max</code>, <code>step</code>: number, range, date 타입 등에서 값의 범위나 간격 지정</li>
              <li><code>maxlength</code>: text, password 등에서 최대 입력 글자 수 지정</li>
          </ul>
      </li>
      <li><code>&lt;label&gt;</code>: 폼 컨트롤(input 등)의 설명을 제공하는 태그. <code>for</code> 속성으로 연결된 컨트롤의 <code>id</code>를 지정하면 라벨 클릭 시 해당 컨트롤이 활성화/선택됩니다 (접근성 향상).</li>
      <li><code>&lt;textarea&gt;</code>: 여러 줄의 텍스트를 입력받는 필드
          <ul>
              <li><code>rows</code>, <code>cols</code> 속성: 초기 보이는 행/열 크기 지정 (CSS로 제어 권장)</li>
          </ul>
      </li>
      <li><code>&lt;select&gt;</code>: 드롭다운 목록(콤보 박스)을 만드는 태그</li>
      <li><code>&lt;option&gt;</code>: <code>&lt;select&gt;</code> 내의 각 항목
          <ul>
              <li><code>value</code> 속성: 해당 옵션 선택 시 전송될 값</li>
              <li><code>selected</code> 속성: 초기에 선택된 상태로 설정</li>
          </ul>
      </li>
      <li><code>&lt;optgroup&gt;</code>: <code>&lt;option&gt;</code>들을 그룹화 (<code>label</code> 속성으로 그룹 이름 지정)</li>
      <li><code>&lt;button&gt;</code>: 클릭 가능한 버튼 (<code>&lt;input type="button"&gt;</code>보다 유연함. 내부에 HTML 콘텐츠 포함 가능)
          <ul>
              <li><code>type</code> 속성: 'submit'(기본값, 폼 전송), 'reset'(폼 초기화), 'button'(일반 버튼)</li>
          </ul>
      </li>
      <li><code>&lt;fieldset&gt;</code>: 관련된 폼 컨트롤들을 그룹화 (테두리 표시)</li>
      <li><code>&lt;legend&gt;</code>: <code>&lt;fieldset&gt;</code> 그룹의 제목</li>
  </ul>

  <pre><code>&lt;form action="/submit-form.php" method="post" enctype="multipart/form-data"&gt;
  &lt;fieldset&gt;
    &lt;legend&gt;개인 정보&lt;/legend&gt;
    &lt;p&gt;
      &lt;label for="name"&gt;이름:&lt;/label&gt;
      &lt;input type="text" id="name" name="username" placeholder="홍길동" required&gt;
    &lt;/p&gt;
    &lt;p&gt;
      &lt;label for="email"&gt;이메일:&lt;/label&gt;
      &lt;input type="email" id="email" name="useremail" required&gt;
    &lt;/p&gt;
    &lt;p&gt;
      &lt;label for="password"&gt;비밀번호:&lt;/label&gt;
      &lt;input type="password" id="password" name="userpass" required minlength="8"&gt;
    &lt;/p&gt;
    &lt;p&gt;
      &lt;label for="birthdate"&gt;생년월일:&lt;/label&gt;
      &lt;input type="date" id="birthdate" name="birthdate"&gt;
    &lt;/p&gt;
  &lt;/fieldset&gt;

  &lt;fieldset&gt;
    &lt;legend&gt;선호 사항&lt;/legend&gt;
    &lt;p&gt;관심 분야 (중복 선택 가능):&lt;br&gt;
      &lt;input type="checkbox" id="interest1" name="interests" value="tech"&gt;
      &lt;label for="interest1"&gt;기술&lt;/label&gt;
      &lt;input type="checkbox" id="interest2" name="interests" value="sports" checked&gt; &lt;!-- 기본 선택 --&gt;
      &lt;label for="interest2"&gt;스포츠&lt;/label&gt;
      &lt;input type="checkbox" id="interest3" name="interests" value="music"&gt;
      &lt;label for="interest3"&gt;음악&lt;/label&gt;
    &lt;/p&gt;
    &lt;p&gt;성별:&lt;br&gt;
      &lt;input type="radio" id="gender_m" name="gender" value="male" required&gt;
      &lt;label for="gender_m"&gt;남성&lt;/label&gt;
      &lt;input type="radio" id="gender_f" name="gender" value="female"&gt;
      &lt;label for="gender_f"&gt;여성&lt;/label&gt;
      &lt;input type="radio" id="gender_o" name="gender" value="other"&gt;
      &lt;label for="gender_o"&gt;기타&lt;/label&gt;
    &lt;/p&gt;
    &lt;p&gt;
      &lt;label for="city"&gt;거주 도시:&lt;/label&gt;
      &lt;select id="city" name="city"&gt;
        &lt;option value=""&gt;-- 도시 선택 --&lt;/option&gt;
        &lt;optgroup label="수도권"&gt;
            &lt;option value="seoul"&gt;서울&lt;/option&gt;
            &lt;option value="incheon" selected&gt;인천&lt;/option&gt; &lt;!-- 기본 선택 --&gt;
        &lt;/optgroup&gt;
        &lt;optgroup label="기타"&gt;
            &lt;option value="busan"&gt;부산&lt;/option&gt;
            &lt;option value="daegu"&gt;대구&lt;/option&gt;
        &lt;/optgroup&gt;
      &lt;/select&gt;
    &lt;/p&gt;
    &lt;p&gt;
      &lt;label for="comments"&gt;남기실 말:&lt;/label&gt;&lt;br&gt;
      &lt;textarea id="comments" name="comments" rows="4" cols="50" placeholder="여기에 내용을 입력하세요..."&gt;&lt;/textarea&gt;
    &lt;/p&gt;
    &lt;p&gt;
        &lt;label for="profile_pic"&gt;프로필 사진:&lt;/label&gt;
        &lt;input type="file" id="profile_pic" name="profile_pic" accept="image/*"&gt; &lt;!-- 이미지 파일만 선택 가능하도록 --&gt;
    &lt;/p&gt;
  &lt;/fieldset&gt;

  &lt;input type="hidden" name="form_id" value="user_registration"&gt; &lt;!-- 숨겨진 값 --&gt;

  &lt;p&gt;
    &lt;input type="submit" value="가입하기"&gt; &lt;!-- 폼 전송 버튼 --&gt;
    &lt;input type="reset" value="다시 작성"&gt; &lt;!-- 폼 초기화 버튼 --&gt;
    &lt;button type="button" onclick="alert('버튼 클릭됨!')"&gt;일반 버튼&lt;/button&gt; &lt;!-- 자바스크립트 연동 버튼 --&gt;
  &lt;/p&gt;
&lt;/form&gt;
</code></pre>
</section>

<section id="multimedia">
    <h2>멀티미디어 태그 (audio, video, iframe)</h2>
    <p>HTML은 오디오, 비디오 콘텐츠를 내장하고 다른 웹 페이지를 삽입하는 기능도 제공합니다.</p>
    <ul>
        <li><code>&lt;audio&gt;</code>: 오디오 파일 재생
            <ul>
                <li><code>controls</code> 속성: 재생, 볼륨 등 기본 컨트롤 표시</li>
                <li><code>autoplay</code> 속성: 자동 재생 (사용자 경험을 해칠 수 있어 주의, 종종 브라우저에서 제한됨)</li>
                <li><code>loop</code> 속성: 반복 재생</li>
                <li><code>muted</code> 속성: 음소거 상태로 시작</li>
                <li><code>src</code> 속성: 오디오 파일 경로 지정 (<code>&lt;source&gt;</code> 태그 사용 권장)</li>
            </ul>
        </li>
        <li><code>&lt;video&gt;</code>: 비디오 파일 재생 (<code>&lt;audio&gt;</code>와 유사한 속성 사용)
            <ul>
                <li><code>width</code>, <code>height</code> 속성: 비디오 플레이어 크기 지정</li>
                <li><code>poster</code> 속성: 비디오 로딩 중 또는 재생 전에 표시될 이미지 경로</li>
            </ul>
        </li>
        <li><code>&lt;source&gt;</code>: <code>&lt;audio&gt;</code> 또는 <code>&lt;video&gt;</code> 태그 내에서 여러 형식의 미디어 파일 지정 (브라우저 호환성 확보)
            <ul>
                <li><code>src</code> 속성: 미디어 파일 경로</li>
                <li><code>type</code> 속성: 미디어 파일의 MIME 타입 (예: 'audio/mpeg', 'video/mp4')</li>
            </ul>
        </li>
        <li><code>&lt;iframe&gt;</code>: 현재 HTML 페이지 내에 다른 HTML 문서를 삽입 (Inline Frame)
            <ul>
                <li><code>src</code> 속성: 삽입할 문서의 URL</li>
                <li><code>width</code>, <code>height</code> 속성: iframe의 크기 지정</li>
                <li><code>frameborder</code> 속성: 테두리 표시 여부 (0 또는 1, CSS로 제어 권장)</li>
                <li><code>allowfullscreen</code> 속성: 전체 화면 모드 허용</li>
                <li><code>sandbox</code> 속성: 보안을 위해 iframe 내 콘텐츠의 권한 제한</li>
            </ul>
        </li>
    </ul>
    <pre><code>&lt;h3&gt;오디오 재생&lt;/h3&gt;
&lt;audio controls&gt;
  &lt;source src="audio/music.mp3" type="audio/mpeg"&gt;
  &lt;source src="audio/music.ogg" type="audio/ogg"&gt;
  브라우저가 오디오 태그를 지원하지 않습니다. &lt;a href="audio/music.mp3"&gt;여기서 다운로드&lt;/a&gt;하세요.
&lt;/audio&gt;

&lt;h3&gt;비디오 재생&lt;/h3&gt;
&lt;video width="400" height="300" controls poster="images/video_poster.jpg"&gt;
  &lt;source src="videos/movie.mp4" type="video/mp4"&gt;
  &lt;source src="videos/movie.webm" type="video/webm"&gt;
  브라우저가 비디오 태그를 지원하지 않습니다.
&lt;/video&gt;

&lt;h3&gt;유튜브 영상 삽입 (iframe 사용)&lt;/h3&gt;
&lt;iframe width="560" height="315"
        src="https://www.youtube.com/embed/VIDEO_ID"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen&gt;
&lt;/iframe&gt;

&lt;h3&gt;다른 웹 페이지 삽입&lt;/h3&gt;
&lt;iframe src="https://www.w3schools.com" width="80%" height="400" style="border:1px solid black;"&gt;
  &lt;p&gt;iframe을 지원하지 않는 브라우저입니다.&lt;/p&gt;
&lt;/iframe&gt;
</code></pre>
</section>

<section id="entities">
    <h2>HTML 엔티티</h2>
    <p>HTML 문서 내에서 특정 문자들은 특별한 의미를 가집니다 (예: <code>&lt;</code>, <code>&gt;</code>). 이러한 문자들을 문자 그대로 표시하거나 키보드로 입력하기 어려운 특수 문자를 표시하려면 HTML 엔티티(Entity) 코드를 사용해야 합니다.</p>
    <ul>
        <li><code>&amp;lt;</code> : <code>&lt;</code> (보다 작음)</li>
        <li><code>&amp;gt;</code> : <code>&gt;</code> (보다 큼)</li>
        <li><code>&amp;amp;</code> : <code>&amp;</code> (앰퍼샌드)</li>
        <li><code>&amp;quot;</code> : <code>"</code> (큰따옴표)</li>
        <li><code>&amp;apos;</code> : <code>'</code> (작은따옴표 - HTML5에서 표준화, XML에서는 표준)</li>
        <li><code>&amp;nbsp;</code> : 줄 바꿈 없는 공백 (Non-breaking space)</li>
        <li><code>&amp;copy;</code> : <code>&copy;</code> (저작권 기호)</li>
        <li><code>&amp;reg;</code> : <code>&reg;</code> (등록 상표 기호)</li>
        <li><code>&amp;trade;</code> : <code>&trade;</code> (상표 기호)</li>
        <li>기타 다양한 문자 엔티티가 존재합니다 (예: 화살표, 통화 기호 등).</li>
    </ul>
    <pre><code>&lt;!-- '<p>' 태그를 그대로 출력하고 싶을 때 --&gt;
&lt;p&gt;HTML 태그는 &amp;lt;p&amp;gt; 와 같이 생겼습니다.&lt;/p&gt;

&lt;!-- 여러 개의 공백을 넣고 싶을 때 (일반 공백은 하나로 처리됨) --&gt;
&lt;p&gt;공백&amp;nbsp;&amp;nbsp;&amp;nbsp;세 개 넣기.&lt;/p&gt;

&lt;!-- 저작권 표시 --&gt;
&lt;p&gt;Copyright &amp;copy; 2025.&lt;/p&gt;
</code></pre>
</section>

<section id="comments">
    <h2>주석 (Comments)</h2>
    <p>HTML 주석은 코드 내에 설명을 추가하거나 특정 부분을 임시로 비활성화하는 데 사용됩니다. 주석은 웹 브라우저에 표시되지 않습니다.</p>
    <pre><code>&lt;!-- 이것은 한 줄 주석입니다. --&gt;

&lt;p&gt;이 내용은 화면에 보입니다.&lt;/p&gt;

&lt;!--
  이것은
  여러 줄에 걸친
  주석입니다.
--&gt;

&lt;!-- &lt;p&gt;이 문단은 주석 처리되어 화면에 보이지 않습니다.&lt;/p&gt; --&gt;
</code></pre>
</section>

<br><br><br>
<p style="text-align:center; font-style: italic;">HTML 학습을 응원합니다!</p>
<br><br><br>

<script src="../js/script.js?ver=1"></script>

</body>
</html>