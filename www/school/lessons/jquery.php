<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> jQuery 강좌 </title>
  <link rel="stylesheet" href="../css/lessons_style.css?ver=1">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <style>
    /* 기본 스타일 (강좌 페이지 자체 스타일) */
    body { font-family: sans-serif; line-height: 1.6; }
    .toc { border: 1px solid #ccc; padding: 15px; margin-bottom: 30px; background-color: #f9f9f9; }
    .toc h2 { margin-top: 0; }
    .toc ul { list-style-type: disc; margin-left: 20px; }
    .toc ul ul { list-style-type: circle; margin-left: 20px; }
    h1, h2 { border-bottom: 2px solid #eee; padding-bottom: 5px; }
    h2 { margin-top: 40px; }
    h3 { margin-top: 30px; border-bottom: 1px dashed #ccc; padding-bottom: 3px;}
    h4 { margin-top: 25px; font-weight: bold;}
    pre { background-color: #f4f4f4; padding: 15px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; font-size: 0.9em; }
    code.language-javascript, code.language-html, code.language-css { font-family: Consolas, monospace; color: #333; }
    .example { border: 1px solid #eee; padding: 15px; margin-top: 10px; margin-bottom: 20px; background-color: #fff; border-radius: 4px;}
    .example h4 { margin-top: 0; font-size: 1.1em; color: #555; }
    .note { background-color: #e7f3fe; border-left: 4px solid #2196F3; padding: 10px 15px; margin: 15px 0; font-size: 0.95em;}
    .warning { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 10px 15px; margin: 15px 0; font-size: 0.95em;}
    .output { background-color: #e9ecef; padding: 10px; margin-top: 5px; border-radius: 3px; font-style: italic; font-size: 0.9em; }

    /* 예제 시연용 스타일 */
    .highlight { background-color: yellow; border: 1px solid orange; }
    .box { border: 1px solid blue; padding: 10px; margin: 5px; }
    #test-area p { margin: 5px 0; }
    #test-area input[type="text"] { border: 1px solid #ccc; padding: 5px; }

    /* TOC 활성 링크 스타일 */
    .toc a.active {
      color: #d9534f;
      font-weight: bold;
    }
  </style>
</head>
<body>

<h1> jQuery 강좌 - 소개, 선택자, 기본 DOM 조작</h1>
<p>이 페이지는 JavaScript 라이브러리인 jQuery의 기초부터 활용까지 다룹니다. 첫 번째 파트에서는 jQuery 소개, 설정 방법, 핵심인 선택자 사용법, 그리고 기본적인 DOM 조작 방법을 학습합니다.</p>
<p class="note"><strong>선수 지식:</strong> 이 강좌를 진행하기 전에 HTML, CSS, 그리고 기본적인 JavaScript 지식이 필요합니다.</p>

<div class="toc">
  <h2>📖 jQuery 목차</h2>
  <ul>
    <li><a href="#intro">jQuery 소개 및 설정</a>
        <ul>
            <li><a href="#intro-what-is-jquery">jQuery란?</a></li>
            <li><a href="#intro-features">특징 및 장점</a></li>
            <li><a href="#intro-setup">jQuery 설정 (CDN, 다운로드)</a></li>
            <li><a href="#intro-ready">$(document).ready()</a></li>
            <li><a href="#intro-syntax">기본 문법 ($(selector).action())</a></li>
        </ul>
    </li>
    <li><a href="#selectors">선택자 (Selectors)</a>
        <ul>
            <li><a href="#selectors-basic">기본 선택자 (CSS 선택자)</a></li>
            <li><a href="#selectors-hierarchy">계층 선택자</a></li>
            <li><a href="#selectors-filters-basic">기본 필터링 선택자</a></li>
            <li><a href="#selectors-filters-content">콘텐츠 필터링 선택자</a></li>
            <li><a href="#selectors-filters-visibility">가시성 필터링 선택자</a></li>
            <li><a href="#selectors-filters-attribute">속성 필터링 선택자</a></li>
            <li><a href="#selectors-filters-form">폼 선택자 및 필터</a></li>
        </ul>
    </li>
    <li><a href="#dom-manipulation">DOM 조작 (Manipulation)</a>
        <ul>
            <li><a href="#dom-content">콘텐츠 변경 (.html(), .text(), .val())</a></li>
            <li><a href="#dom-attributes">속성 변경 (.attr(), .prop(), .removeAttr(), .removeProp())</a></li>
            <li><a href="#dom-classes">클래스 조작 (.addClass(), .removeClass(), .toggleClass(), .hasClass())</a></li>
            <li><a href="#dom-css">CSS 조작 (.css(), 크기/위치 메서드)</a></li>
            <li><a href="#dom-adding">요소 추가 (.append(), .prepend(), .before(), .after() 등)</a></li>
            <li><a href="#dom-removing">요소 제거 (.remove(), .empty(), .detach())</a></li>
            <li><a href="#dom-cloning">요소 복제 (.clone())</a></li>
            <li><a href="#dom-wrapping">요소 감싸기/해제 (.wrap(), .unwrap() 등)</a></li>
        </ul>
    </li>
     <li><a href="#dom-traversing">DOM 탐색 (Traversing)</a>
        <ul>
            <li><a href="#traverse-filtering">필터링 (.filter(), .not(), .has(), .is(), .eq())</a></li>
            <li><a href="#traverse-descendants">자손 탐색 (.find(), .children())</a></li>
            <li><a href="#traverse-ancestors">조상 탐색 (.parent(), .parents(), .closest() 등)</a></li>
            <li><a href="#traverse-siblings">형제 탐색 (.siblings(), .next(), .prev() 등)</a></li>
            <li><a href="#traverse-chaining">체이닝 (Chaining)</a></li>
        </ul>
    </li>
    <li><a href="#events">이벤트 처리 (Events)</a>
        <ul>
            <li><a href="#event-binding">이벤트 핸들러 바인딩 (.on(), 단축 메서드)</a></li>
            <li><a href="#event-unbinding">이벤트 핸들러 제거 (.off())</a></li>
            <li><a href="#event-object">이벤트 객체</a></li>
            <li><a href="#event-delegation">이벤트 위임 (.on() 활용)</a></li>
            <li><a href="#event-triggering">이벤트 강제 발생 (.trigger(), .triggerHandler())</a></li>
            <li><a href="#event-helper">이벤트 헬퍼 (.hover(), .ready() 등)</a></li>
        </ul>
    </li>
     <li><a href="#effects">효과 및 애니메이션 (Effects & Animations)</a>
        <ul>
            <li><a href="#effects-basic">기본 효과 (.show(), .hide(), .toggle())</a></li>
            <li><a href="#effects-fading">페이딩 효과 (.fadeIn(), .fadeOut(), .fadeToggle(), .fadeTo())</a></li>
            <li><a href="#effects-sliding">슬라이딩 효과 (.slideDown(), .slideUp(), .slideToggle())</a></li>
            <li><a href="#effects-animate">사용자 정의 애니메이션 (.animate())</a></li>
            <li><a href="#effects-queue">애니메이션 큐 및 제어 (.stop(), .delay(), .promise())</a></li>
        </ul>
    </li>
    <li><a href="#ajax">AJAX (Asynchronous JavaScript and XML)</a>
         <ul>
            <li><a href="#ajax-intro">jQuery AJAX 소개</a></li>
            <li><a href="#ajax-load">.load() 메서드</a></li>
            <li><a href="#ajax-get-post">$.get(), $.post() 메서드</a></li>
            <li><a href="#ajax-getjson">$.getJSON() 메서드</a></li>
            <li><a href="#ajax-core">$.ajax() 메서드 (핵심)</a></li>
            <li><a href="#ajax-helpers">AJAX 헬퍼 함수 및 전역 이벤트</a></li>
        </ul>
    </li>
    <li><a href="#utilities">유틸리티 메서드 (Utilities)</a></li>
    <li><a href="#noconflict">NoConflict 모드</a></li>
    <li><a href="#conclusion">마무리</a></li>
  </ul>   
</div>

<section id="intro">
  <h2>jQuery 소개 및 설정</h2>

  <h3 id="intro-what-is-jquery">jQuery란?</h3>
  <p>jQuery는 "Write less, do more" 라는 슬로건을 가진 경량의 JavaScript 라이브러리입니다. 복잡하고 길어질 수 있는 JavaScript 코드를 더 간결하고 쉽게 작성할 수 있도록 다양한 기능을 제공합니다.</p>
  <p>특히 과거 웹 브라우저 간의 호환성 문제를 해결하고, HTML 문서 탐색(Traversing) 및 조작(Manipulation), 이벤트 처리, 애니메이션, AJAX 통신 등을 매우 편리하게 구현할 수 있게 도와주어 웹 개발 생산성을 크게 향상시켰습니다.</p>
  <p class="warning">현대에는 순수 JavaScript(Vanilla JS) 자체 기능이 강력해지고, React, Vue, Angular 같은 컴포넌트 기반 프레임워크가 등장하면서 jQuery의 인기가 예전 같지는 않습니다. 하지만 여전히 많은 기존 웹사이트와 프로젝트에서 사용되고 있으며, 특정 상황에서는 빠르고 간편하게 기능을 구현하는 데 유용할 수 있습니다.</p>

  <h3 id="intro-features">특징 및 장점</h3>
  <ul>
    <li><strong>간결한 문법:</strong> 짧고 직관적인 코드로 DOM 조작 및 이벤트 처리가 가능합니다.</li>
    <li><strong>강력한 선택자:</strong> CSS 선택자를 활용하여 HTML 요소를 쉽게 선택하고 조작할 수 있습니다.</li>
    <li><strong>크로스 브라우징 지원:</strong> 다양한 웹 브라우저에서 동일하게 동작하도록 호환성 문제를 해결해 줍니다.</li>
    <li><strong>DOM 조작 편의성:</strong> HTML 요소의 내용, 속성, 스타일 등을 쉽게 변경, 추가, 제거할 수 있습니다.</li>
    <li><strong>쉬운 이벤트 처리:</strong> 복잡한 이벤트 핸들링을 간결하게 구현할 수 있습니다.</li>
    <li><strong>애니메이션 효과:</strong> 간단한 코드로 다양한 시각적 효과와 애니메이션을 구현할 수 있습니다.</li>
    <li><strong>AJAX 간소화:</strong> 서버와의 비동기 데이터 통신을 쉽게 구현할 수 있도록 도와줍니다.</li>
    <li><strong>확장성:</strong> 다양한 플러그인을 통해 기능을 쉽게 확장할 수 있습니다.</li>
  </ul>

  <h3 id="intro-setup">jQuery 설정 (CDN, 다운로드)</h3>
  <p>jQuery를 사용하려면 먼저 HTML 문서에 jQuery 라이브러리 파일을 포함시켜야 합니다. 두 가지 주요 방법이 있습니다.</p>
  <ol>
    <li>
      <strong>CDN (Content Delivery Network) 사용 (권장):</strong><br>
      Google, Microsoft, jQuery.com 등에서 제공하는 CDN 링크를 사용하는 것이 가장 일반적이고 편리합니다. 사용자는 이미 다른 사이트에서 CDN을 통해 jQuery를 다운로드했을 수 있으므로 로딩 속도가 빠를 수 있으며, 서버 부하를 줄일 수 있습니다.
      <pre><code class="language-html">&lt;!-- HTML 문서의 &lt;head&gt; 또는 &lt;body&gt; 닫는 태그 직전에 추가 --&gt;
&lt;script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"&gt;&lt;/script&gt;
&lt;!-- integrity와 crossorigin 속성은 보안(SRI)을 위한 것으로, 포함하는 것이 좋습니다. --&gt;
&lt;!-- 버전 번호(3.7.1)는 최신 안정 버전을 확인하고 사용하는 것이 좋습니다. --&gt;</code></pre>
    </li>
    <li>
      <strong>파일 다운로드 사용:</strong><br>
      <a href="https://jquery.com/download/" target="_blank">jQuery 공식 웹사이트</a>에서 파일을 직접 다운로드하여 프로젝트 폴더에 저장하고, 상대 경로로 링크합니다. 오프라인 환경이나 특정 버전 사용이 필요할 때 유용합니다.
      <ul>
          <li><strong>Compressed/Minified 버전 (.min.js):</strong> 공백과 주석이 제거되어 파일 크기가 작습니다. 실제 서비스 환경에 사용합니다.</li>
          <li><strong>Uncompressed 버전 (.js):</strong> 개발 및 디버깅 시 코드를 확인하기 용이합니다.</li>
      </ul>
       <pre><code class="language-html">&lt;!-- 예시: 프로젝트의 js 폴더에 저장한 경우 --&gt;
&lt;script src="js/jquery-3.7.1.min.js"&gt;&lt;/script&gt;</code></pre>
    </li>
  </ol>
  <p class="note">jQuery 라이브러리 스크립트는 jQuery 코드를 사용하는 다른 스크립트보다 먼저 로드되어야 합니다.</p>

  <h3 id="intro-ready">$(document).ready()</h3>
  <p>웹 페이지의 HTML 요소들이 모두 로드되고 DOM 트리가 완성된 후에 JavaScript 코드가 실행되도록 보장하는 것은 매우 중요합니다. DOM이 준비되기 전에 요소를 조작하려고 하면 에러가 발생할 수 있습니다.</p>
  <p>jQuery는 이를 위해 <code>$(document).ready()</code> 메서드를 제공합니다. 이 메서드 안에 작성된 코드는 DOM이 완전히 로드되고 조작 가능한 상태가 되었을 때 실행됩니다.</p>
  <pre><code class="language-javascript">// 기본 형태
$(document).ready(function() {
  // DOM이 준비된 후 실행될 jQuery 코드 작성
  console.log("DOM is ready!");
  // 예: $('#myElement').hide();
});

// 축약 형태 (더 많이 사용됨)
$(function() {
  // DOM이 준비된 후 실행될 jQuery 코드 작성
  console.log("DOM is ready! (shorthand)");
});</code></pre>
  <p>바닐라 JavaScript의 <code>DOMContentLoaded</code> 이벤트와 유사한 역할을 합니다.</p>

  <h3 id="intro-syntax">기본 문법 ($(selector).action())</h3>
  <p>jQuery의 기본 문법은 매우 직관적입니다.</p>
  <p><code>$(selector).action()</code></p>
  <ul>
    <li><code>$()</code>: jQuery 함수 또는 jQuery 객체를 생성하는 팩토리 함수입니다. <code>jQuery()</code>와 동일합니다.
        <ul>
            <li><code>selector</code>: HTML 요소를 선택하기 위한 CSS 선택자 문자열입니다.</li>
        </ul>
    </li>
    <li><code>.action()</code>: 선택된 요소에 대해 수행할 작업을 나타내는 jQuery 메서드입니다. (예: <code>.hide()</code>, <code>.text()</code>, <code>.addClass()</code> 등)</li>
  </ul>
  <p>jQuery 메서드는 종종 체이닝(Chaining)을 지원하여, 하나의 선택된 요소 집합에 대해 여러 메서드를 연속적으로 호출할 수 있습니다.</p>
  <pre><code class="language-javascript">$(document).ready(function(){
  // ID가 'title'인 요소를 선택하여 숨김
  $('#title').hide();

  // 모든 p 태그를 선택하여 텍스트 색상을 파란색으로 변경
  $('p').css('color', 'blue');

  // 클래스가 'item'인 모든 요소를 찾아서 'highlight' 클래스를 추가하고, 페이드 인 효과 적용 (체이닝)
  $('.item')
    .addClass('highlight') // 클래스 추가
    .fadeIn(1000);          // 1초 동안 서서히 나타남
});</code></pre>
</section>

<section id="selectors">
  <h2>선택자 (Selectors)</h2>
  <p>jQuery의 강력한 기능 중 하나는 CSS 선택자를 사용하여 HTML 요소를 매우 쉽고 유연하게 선택할 수 있다는 것입니다. <code>$()</code> 함수 안에 CSS 선택자 문자열을 전달하면 해당 요소(들)을 나타내는 jQuery 객체가 반환됩니다.</p>
  <p class="note">jQuery 선택자는 대부분의 CSS1, CSS2, CSS3 선택자를 지원하며, jQuery 고유의 필터 선택자도 제공합니다.</p>

   <div class="example">
      <h4>선택자 예제용 HTML (<span id="test-area-id">#test-area</span>)</h4>
      <div id="test-area">
          <h2 class="main heading">제목 1</h2>
          <p class="content first">첫 번째 문단 <span>(span)</span>.</p>
          <div class="box">
              <p class="content second">두 번째 문단.</p>
              <ul>
                  <li>항목 1</li>
                  <li class="selected">항목 2</li>
                  <li>항목 3 <a href="https://example.com" target="_blank">링크</a></li>
              </ul>
          </div>
          <p>세 번째 문단 (내용 없음).</p>
          <form>
              <input type="text" name="username" value="초기값">
              <input type="password" name="password">
              <input type="checkbox" name="agree" checked> 동의
              <button type="submit">전송</button>
              <input type="button" value="일반 버튼" disabled>
          </form>
          <div style="display: none;">숨겨진 div</div>
      </div>
      <div id="output-selector" class="output">선택 결과가 여기에 표시됩니다.</div>
  </div>

  <h3 id="selectors-basic">기본 선택자 (CSS 선택자)</h3>
  <ul>
    <li>ID 선택자: <code>$('#idName')</code></li>
    <li>클래스 선택자: <code>$('.className')</code></li>
    <li>요소(태그) 선택자: <code>$('tagName')</code></li>
    <li>전체 선택자: <code>$('*')</code></li>
    <li>다중 선택자: <code>$('selector1, selector2, ...')</code></li>
  </ul>
  <pre><code class="language-javascript">$(function(){ // $(document).ready() 축약형
  // ID
  $('#test-area-id').css('border', '2px solid green');

  // Class
  $('.content').css('font-style', 'italic');

  // Tag
  $('li').css('color', 'purple');

  // Multiple
  $('h2, .box p').css('text-decoration', 'underline');
});</code></pre>

  <h3 id="selectors-hierarchy">계층 선택자</h3>
  <ul>
    <li>자손 선택자: <code>$('ancestor descendant')</code></li>
    <li>자식 선택자: <code>$('parent > child')</code></li>
    <li>인접 형제 선택자: <code>$('prev + next')</code></li>
    <li>일반 형제 선택자: <code>$('prev ~ siblings')</code></li>
  </ul>
   <pre><code class="language-javascript">$(function(){
  // 자손: .box 안의 모든 p
  $('.box p').css('background-color', '#eee');

  // 자식: ul 바로 아래 li
  $('ul > li').css('border-bottom', '1px dotted gray');

  // 인접 형제: .selected 바로 다음 li
  $('.selected + li').css('font-weight', 'bold');

  // 일반 형제: .selected 뒤의 모든 li 형제
  // $('.selected ~ li')....
});</code></pre>

  <h3 id="selectors-filters-basic">기본 필터링 선택자</h3>
  <p>선택된 요소 집합 내에서 특정 조건에 맞는 요소를 필터링합니다. 콜론(<code>:</code>)으로 시작합니다.</p>
  <ul>
    <li><code>:first</code> / <code>:last</code>: 선택된 요소 중 첫 번째 / 마지막 요소.</li>
    <li><code>:even</code> / <code>:odd</code>: 짝수 / 홀수 번째 요소 (0부터 시작).</li>
    <li><code>:eq(index)</code>: 지정된 인덱스(0부터 시작)와 일치하는 요소.</li>
    <li><code>:gt(index)</code>: 지정된 인덱스보다 큰 인덱스를 가진 요소.</li>
    <li><code>:lt(index)</code>: 지정된 인덱스보다 작은 인덱스를 가진 요소.</li>
    <li><code>:not(selector)</code>: 지정된 선택자와 일치하지 않는 요소.</li>
    <li><code>:header</code>: 모든 제목 요소 (h1 ~ h6).</li>
    <li><code>:animated</code>: 현재 애니메이션이 진행 중인 요소.</li>
    <li><code>:focus</code>: 현재 포커스를 가진 요소.</li>
    <li><code>:root</code>: 문서의 루트 요소 (<code>&lt;html&gt;</code>).</li>
  </ul>
  <pre><code class="language-javascript">$(function(){
  // 첫 번째 li와 마지막 li
  $('li:first').addClass('highlight');
  $('li:last').addClass('highlight');

  // 홀수 번째(1, 3, ...) li 배경색 변경
  // $('li:odd').css('background-color', 'lightcyan'); // 인덱스 기준 1, 3...

  // 인덱스 1인 li (두 번째 항목)
  $('li:eq(1)').css('border', '2px solid red'); // 항목 2

  // .content 클래스를 가지지 않은 p 요소
  $('p:not(.content)').css('color', 'gray'); // 세 번째 문단
});</code></pre>

  <h3 id="selectors-filters-content">콘텐츠 필터링 선택자</h3>
  <ul>
    <li><code>:contains(text)</code>: 지정된 텍스트를 포함하는 요소 (대소문자 구분).</li>
    <li><code>:empty</code>: 자식 요소(텍스트 노드 포함)가 없는 요소.</li>
    <li><code>:has(selector)</code>: 지정된 선택자와 일치하는 자손 요소를 하나 이상 포함하는 요소.</li>
    <li><code>:parent</code>: 자식 요소(텍스트 노드 포함)가 있는 요소 (<code>:empty</code>의 반대).</li>
  </ul>
  <pre><code class="language-javascript">$(function(){
  // "링크" 텍스트를 포함하는 요소
  $('li:contains("링크")').css('background-color', 'lightpink'); // 항목 3의 li

  // 비어있는 p 요소
  $('p:empty').html('[비어 있음]').css('background-color', 'yellow'); // 세 번째 문단

  // a 태그를 자손으로 가지는 li
  $('li:has(a)').css('border-left', '3px solid blue'); // 항목 3
});</code></pre>

  <h3 id="selectors-filters-visibility">가시성 필터링 선택자</h3>
  <ul>
    <li><code>:hidden</code>: 화면에 보이지 않는 요소 (<code>display: none</code>, <code>type="hidden"</code>, <code>visibility: hidden</code>, <code>opacity: 0</code>, 너비/높이 0 등).</li>
    <li><code>:visible</code>: 화면에 보이는 요소 (<code>:hidden</code>의 반대).</li>
  </ul>
  <pre><code class="language-javascript">$(function(){
  // 숨겨진 div 찾아서 보이기
  $('div:hidden').show().text('숨겨졌던 div입니다.');

  let visiblePCount = $('p:visible').length;
  $('#output-selector').text(`화면에 보이는 p 태그 개수: ${visiblePCount}`);
});</code></pre>
  <p class="warning"><code>:hidden</code>과 <code>:visible</code>은 요소의 CSS 상태를 확인하므로 다른 선택자에 비해 성능이 느릴 수 있습니다.</p>

  <h3 id="selectors-filters-attribute">속성 필터링 선택자</h3>
  <p>CSS 속성 선택자와 동일하게 작동합니다.</p>
  <ul>
    <li><code>[attribute]</code>: 특정 속성을 가진 요소.</li>
    <li><code>[attribute=value]</code>: 속성 값이 정확히 일치하는 요소.</li>
    <li><code>[attribute!=value]</code>: 속성 값이 일치하지 않거나 속성이 없는 요소.</li>
    <li><code>[attribute^=value]</code>: 속성 값이 특정 값으로 시작하는 요소.</li>
    <li><code>[attribute$=value]</code>: 속성 값이 특정 값으로 끝나는 요소.</li>
    <li><code>[attribute*=value]</code>: 속성 값에 특정 값이 포함된 요소.</li>
    <li><code>[attribute~=value]</code>: 속성 값이 특정 단어(공백으로 구분)를 포함하는 요소.</li>
    <li><code>[attribute|=value]</code>: 속성 값이 특정 값이거나 특정 값으로 시작하고 하이픈(-)이 붙는 요소.</li>
    <li><code>[attr1][attr2]...</code>: 여러 속성 조건을 동시에 만족하는 요소.</li>
  </ul>
  <pre><code class="language-javascript">$(function(){
  // target 속성을 가진 a 요소
  $('a[target]').css('font-style', 'italic');

  // target 속성 값이 '_blank'인 a 요소
  $('a[target="_blank"]').css('background-color', 'lightyellow');

  // href 속성 값이 'https'로 시작하는 a 요소
  $('a[href^="https"]').after(' (secure)');
});</code></pre>

  <h3 id="selectors-filters-form">폼 선택자 및 필터</h3>
  <p>폼(Form) 요소와 관련된 특수한 선택자 및 필터입니다.</p>
  <ul>
    <li>폼 요소 선택자:
      <ul>
        <li><code>:input</code>: 모든 <code>&lt;input&gt;</code>, <code>&lt;textarea&gt;</code>, <code>&lt;select&gt;</code>, <code>&lt;button&gt;</code> 요소.</li>
        <li><code>:text</code>, <code>:password</code>, <code>:radio</code>, <code>:checkbox</code>, <code>:submit</code>, <code>:reset</code>, <code>:button</code>, <code>:image</code>, <code>:file</code>: 특정 <code>type</code>의 <code>&lt;input&gt;</code> 요소.</li>
      </ul>
    </li>
    <li>폼 상태 필터:
        <ul>
          <li><code>:enabled</code> / <code>:disabled</code>: 활성화 / 비활성화된 폼 요소.</li>
          <li><code>:checked</code>: 체크된 상태의 라디오 버튼 또는 체크박스.</li>
          <li><code>:selected</code>: 선택된 상태의 <code>&lt;option&gt;</code> 요소.</li>
      </ul>
    </li>
  </ul>
   <pre><code class="language-javascript">$(function(){
  // 모든 input 요소에 테두리
  // $(':input').css('border', '1px dashed orange'); // 너무 광범위할 수 있음

  // type="text" input 요소
  $('input:text').val('텍스트 입력됨');

  // 체크된 체크박스 옆에 텍스트 추가
  $('input:checked').after(' (동의함)');

  // 비활성화된 버튼 스타일 변경
  $(':button:disabled').css('opacity', 0.5);
});</code></pre>
</section>


<section id="dom-manipulation">
  <h2 id="dom-manipulation-intro">DOM 조작 (Manipulation)</h2>
  <p>jQuery를 사용하면 선택한 HTML 요소의 내용, 속성, 스타일 등을 매우 쉽게 변경, 추가, 제거할 수 있습니다.</p>

  <h3 id="dom-content">콘텐츠 변경 (.html(), .text(), .val())</h3>
  <ul>
    <li><code>.html()</code>: 선택한 요소의 HTML 내용을 가져오거나 설정합니다. 인수를 전달하지 않으면 첫 번째 요소의 HTML 내용을 반환하고, HTML 문자열을 인수로 전달하면 선택된 모든 요소의 내용을 변경합니다.</li>
    <li><code>.text()</code>: 선택한 요소의 텍스트 내용(HTML 태그 제외)을 가져오거나 설정합니다. 인수를 전달하지 않으면 선택된 모든 요소의 텍스트 내용을 합쳐서 반환하고, 텍스트 문자열을 인수로 전달하면 선택된 모든 요소의 내용을 변경합니다. (바닐라 JS의 <code>textContent</code>와 유사)</li>
    <li><code>.val()</code>: 주로 폼 요소(<code>input</code>, <code>select</code>, <code>textarea</code>)의 <code>value</code> 값을 가져오거나 설정합니다.</li>
  </ul>
   <div class="example">
      <h4>콘텐츠 변경 예제용 HTML</h4>
      <div id="content-div" class="box">이것은 <b>원래</b> 콘텐츠입니다.</div>
      <input type="text" id="content-input" value="초기 입력값">
      <button id="btn-get-content">콘텐츠 가져오기</button>
      <button id="btn-set-content">콘텐츠 설정하기</button>
      <div id="output-content-dom" class="output"></div>
  </div>
  <pre><code class="language-javascript">$(function(){
    const $contentDiv = $('#content-div'); // jQuery 객체를 변수에 저장할 때 $ 접두사 사용 관례
    const $contentInput = $('#content-input');
    const $outputDiv = $('#output-content-dom');

    $('#btn-get-content').on('click', function(){
        let htmlContent = $contentDiv.html();
        let textContent = $contentDiv.text();
        let inputValue = $contentInput.val();
        $outputDiv.html(`div HTML: ${htmlContent}<br>div Text: ${textContent}<br>Input Value: ${inputValue}`);
    });

    $('#btn-set-content').on('click', function(){
        $contentDiv.html('<strong>새로운 HTML</strong> 콘텐츠!');
        $contentInput.val('변경된 입력값');
        $outputDiv.text('콘텐츠와 입력 값이 변경되었습니다.');
    });
});</code></pre>
  <p class="warning"><code>.html()</code> 메서드로 내용을 설정할 때 사용자 입력값을 그대로 사용하면 XSS 공격에 취약할 수 있습니다. 신뢰할 수 없는 데이터는 <code>.text()</code>를 사용하거나 적절한 이스케이프 처리가 필요합니다.</p>

  <h3 id="dom-attributes">속성 변경 (.attr(), .prop(), .removeAttr(), .removeProp())</h3>
  <ul>
    <li><code>.attr('attributeName')</code>: 첫 번째 요소의 지정된 속성 값을 반환합니다.</li>
    <li><code>.attr('attributeName', 'value')</code>: 선택된 모든 요소의 지정된 속성 값을 설정합니다.</li>
    <li><code>.attr({ 'attr1': 'val1', 'attr2': 'val2' })</code>: 여러 속성을 객체 형태로 한 번에 설정합니다.</li>
    <li><code>.removeAttr('attributeName')</code>: 선택된 요소에서 지정된 속성을 제거합니다.</li>
    <li><code>.prop('propertyName')</code>: 첫 번째 요소의 지정된 프로퍼티 값을 반환합니다.</li>
    <li><code>.prop('propertyName', value)</code>: 선택된 모든 요소의 지정된 프로퍼티 값을 설정합니다. (<code>value</code>는 주로 boolean 또는 DOM 프로퍼티 값)</li>
    <li><code>.removeProp('propertyName')</code>: 선택된 요소에서 지정된 프로퍼티를 제거합니다. (주의: <code>checked</code>, <code>selected</code>, <code>disabled</code> 같은 내장 프로퍼티는 제거하면 안 됨)</li>
  </ul>
  <h4><code>.attr()</code> vs <code>.prop()</code></h4>
  <p>둘 다 요소의 속성/프로퍼티를 다루지만 약간의 차이가 있습니다.</p>
  <ul>
    <li><code>.attr()</code>은 HTML 속성(attribute) 자체의 값을 다룹니다 (HTML 소스 코드에 명시된 값).</li>
    <li><code>.prop()</code>은 DOM 요소 객체의 프로퍼티(property) 값을 다룹니다 (JavaScript 객체의 속성 값).</li>
    <li><code>checked</code>, <code>selected</code>, <code>disabled</code> 와 같은 boolean 속성/프로퍼티의 경우, 값 자체가 아닌 현재 상태를 확인하려면 <strong><code>.prop()</code>을 사용</strong>하는 것이 좋습니다. (예: 체크박스의 체크 상태는 <code>.prop('checked')</code>로 확인)</li>
    <li>커스텀 속성(<code>data-*</code> 제외)은 <code>.attr()</code>로 접근해야 합니다.</li>
  </ul>
   <div class="example">
      <h4>속성 변경 예제용 HTML</h4>
      <img id="attr-img" src="https://placeholder.co/50?text=A" alt="이미지 A" title="기본 툴팁">
      <input type="checkbox" id="attr-check" value="agree"> 동의합니다
      <button id="btn-change-prop">속성/프로퍼티 변경</button>
      <div id="output-attr-dom" class="output"></div>
  </div>
  <pre><code class="language-javascript">$(function(){
    const $img = $('#attr-img');
    const $checkbox = $('#attr-check');
    const $outputDiv = $('#output-attr-dom');

    $('#btn-change-prop').on('click', function(){
        // attr 사용
        $img.attr('src', 'https://placeholder.co/75?text=B');
        $img.attr({ alt: '이미지 B', title: '변경된 툴팁' }); // 여러 속성 변경
        let currentAlt = $img.attr('alt');

        // prop 사용 (checked 상태 확인)
        let isChecked = $checkbox.prop('checked'); // 현재 체크 상태 (true/false)
        $outputDiv.html(`이미지 alt: ${currentAlt}<br>체크박스 상태: ${isChecked}`);

        // prop으로 상태 변경
        $checkbox.prop('checked', !isChecked); // 상태 반전
        $checkbox.prop('disabled', true); // 비활성화
    });
});</code></pre>

  <h3 id="dom-classes">클래스 조작 (.addClass(), .removeClass(), .toggleClass(), .hasClass())</h3>
  <p>요소의 CSS 클래스를 동적으로 추가, 제거, 토글하는 데 사용됩니다. 스타일 변경 시 매우 유용합니다.</p>
  <ul>
    <li><code>.addClass('className1 className2 ...')</code>: 하나 이상의 클래스를 추가합니다.</li>
    <li><code>.removeClass('className1 className2 ...')</code>: 하나 이상의 클래스를 제거합니다. 인수가 없으면 모든 클래스를 제거합니다.</li>
    <li><code>.toggleClass('className', [switch])</code>: 클래스가 있으면 제거하고, 없으면 추가합니다. 두 번째 인수로 boolean 값(<code>switch</code>)을 주면, true일 때 추가, false일 때 제거 동작만 수행합니다.</li>
    <li><code>.hasClass('className')</code>: 요소가 지정된 클래스를 가지고 있는지 확인하여 boolean 값을 반환합니다. (선택된 요소 중 하나라도 가지고 있으면 true)</li>
  </ul>
  <div class="example">
      <h4>클래스 조작 예제용 HTML</h4>
      <div id="class-box" class="box initial-class">클래스를 조작할 박스</div>
      <button id="btn-add-class">클래스 추가</button>
      <button id="btn-remove-class">클래스 제거</button>
      <button id="btn-toggle-class">클래스 토글</button>
      <style>.added-style { color: white; background-color: navy; } .highlight { border: 3px solid gold; }</style>
      <div id="output-class-dom" class="output"></div>
  </div>
   <pre><code class="language-javascript">$(function(){
    const $box = $('#class-box');
    const $outputDiv = $('#output-class-dom');

    function updateOutput() {
        let currentClasses = $box.attr('class'); // 현재 클래스 목록 확인
        let hasHighlight = $box.hasClass('highlight');
        $outputDiv.text(`현재 클래스: ${currentClasses} | highlight 클래스 유무: ${hasHighlight}`);
    }
    updateOutput(); // 초기 상태 출력

    $('#btn-add-class').on('click', function(){
        $box.addClass('added-style highlight');
        updateOutput();
    });
    $('#btn-remove-class').on('click', function(){
        $box.removeClass('initial-class added-style'); // 여러 클래스 제거
        updateOutput();
    });
    $('#btn-toggle-class').on('click', function(){
        $box.toggleClass('highlight');
        updateOutput();
    });
});</code></pre>

  <h3 id="dom-css">CSS 조작 (.css(), 크기/위치 메서드)</h3>
  <p>jQuery를 사용하여 요소의 CSS 스타일 속성을 직접 가져오거나 설정할 수 있습니다. 하지만 대부분의 경우 CSS 클래스를 토글하는 방식이 더 권장됩니다.</p>
  <ul>
    <li><code>.css('propertyName')</code>: 첫 번째 요소의 지정된 CSS 속성 값을 반환합니다.</li>
    <li><code>.css('propertyName', 'value')</code>: 선택된 모든 요소의 지정된 CSS 속성 값을 설정합니다.</li>
    <li><code>.css({ 'prop1': 'val1', 'prop2': 'val2' })</code>: 여러 CSS 속성을 객체 형태로 한 번에 설정합니다.</li>
    <li><strong>크기 관련 메서드:</strong>
        <ul>
            <li><code>.width()</code> / <code>.height()</code>: 요소의 콘텐츠 영역 너비/높이 (padding, border, margin 제외).</li>
            <li><code>.innerWidth()</code> / <code>.innerHeight()</code>: 콘텐츠 + padding 너비/높이.</li>
            <li><code>.outerWidth(includeMargin?)</code> / <code>.outerHeight(includeMargin?)</code>: 콘텐츠 + padding + border 너비/높이. 인수로 <code>true</code>를 주면 margin까지 포함.</li>
        </ul>
    </li>
    <li><strong>위치 관련 메서드:</strong>
        <ul>
            <li><code>.offset()</code>: 문서(document) 기준 요소의 현재 좌표 (<code>{ top: value, left: value }</code> 객체 반환). 읽기/쓰기 가능.</li>
            <li><code>.position()</code>: 가장 가까운 <code>position</code> 속성이 있는 조상 요소 기준 요소의 현재 좌표 (<code>{ top: value, left: value }</code> 객체 반환). 읽기 전용.</li>
            <li><code>.scrollTop()</code> / <code>.scrollLeft()</code>: 요소 내부의 스크롤된 거리 (수직/수평)를 가져오거나 설정.</li>
        </ul>
    </li>
  </ul>
  <div class="example">
      <h4>CSS 조작 예제용 HTML</h4>
      <div id="css-box" class="box" style="width: 150px; height: 100px; padding: 10px; margin: 5px; position: relative; background-color: lightcoral;"></div>
      <button id="btn-get-css">CSS/크기/위치 가져오기</button>
      <button id="btn-set-css">CSS 설정하기</button>
      <div id="output-css-dom" class="output"></div>
  </div>
   <pre><code class="language-javascript">$(function(){
    const $box = $('#css-box');
    const $outputDiv = $('#output-css-dom');

    $('#btn-get-css').on('click', function(){
        let bgColor = $box.css('background-color');
        let width = $box.width(); // 콘텐츠 너비
        let outerWidth = $box.outerWidth(true); // margin 포함 전체 너비
        let offset = $box.offset(); // 문서 기준 좌표
        let position = $box.position(); // 부모 기준 좌표
        $outputDiv.html(`배경색: ${bgColor}<br>
                         콘텐츠 너비: ${width}px<br>
                         전체 너비(margin포함): ${outerWidth}px<br>
                         문서 좌표: top ${offset.top}, left ${offset.left}<br>
                         부모 기준 좌표: top ${position.top}, left ${position.left}`);
    });

    $('#btn-set-css').on('click', function(){
        $box.css({
            'background-color': 'lightblue', // 속성 이름은 문자열로 (하이픈 포함 가능)
            'border': '2px dashed blue',
            'font-size': '18px', // 또는 fontSize: '18px' (camelCase)
            'opacity': 0.8
        });
        $outputDiv.text('CSS 속성이 변경되었습니다.');
        // $box.width(200); // 너비 설정
        // $box.offset({ top: 100, left: 50 }); // 위치 설정
    });
});</code></pre>
</section>


<br><br>
<hr>

<section id="dom-adding">
  <h3>요소 추가 (.append(), .prepend(), .before(), .after() 등)</h3>
  <p>선택한 요소를 기준으로 새로운 HTML 콘텐츠나 기존 요소를 추가할 수 있습니다.</p>
  <ul>
    <li><strong>선택한 요소 내부에 추가:</strong>
        <ul>
            <li><code>.append(content1, [content2, ...])</code>: 선택한 각 요소의 내부 끝에 콘텐츠를 추가합니다.</li>
            <li><code>.prepend(content1, [content2, ...])</code>: 선택한 각 요소의 내부 시작에 콘텐츠를 추가합니다.</li>
        </ul>
    </li>
    <li><strong>선택한 요소 외부에 추가:</strong>
        <ul>
            <li><code>.after(content1, [content2, ...])</code>: 선택한 각 요소의 바로 뒤에 콘텐츠를 추가합니다. (형제 레벨)</li>
            <li><code>.before(content1, [content2, ...])</code>: 선택한 각 요소의 바로 앞에 콘텐츠를 추가합니다. (형제 레벨)</li>
        </ul>
    </li>
    <li><strong>콘텐츠를 다른 요소로 이동/추가 (위 메서드들의 반대 동작):</strong>
        <ul>
            <li><code>.appendTo(target)</code>: 선택한 콘텐츠를 `target` 요소의 내부 끝으로 이동/추가합니다.</li>
            <li><code>.prependTo(target)</code>: 선택한 콘텐츠를 `target` 요소의 내부 시작으로 이동/추가합니다.</li>
            <li><code>.insertAfter(target)</code>: 선택한 콘텐츠를 `target` 요소의 바로 뒤로 이동/추가합니다.</li>
            <li><code>.insertBefore(target)</code>: 선택한 콘텐츠를 `target` 요소의 바로 앞으로 이동/추가합니다.</li>
        </ul>
    </li>
  </ul>
  <p><code>content</code> 인자로는 HTML 문자열, DOM 요소, jQuery 객체 등이 올 수 있습니다.</p>
  <div class="example">
      <h4>요소 추가 예제용 HTML</h4>
      <div id="add-area" class="box">
          <p>기준 단락</p>
      </div>
      <button id="btn-append">Append</button>
      <button id="btn-prepend">Prepend</button>
      <button id="btn-after">After</button>
      <button id="btn-before">Before</button>
      <button id="btn-append-to">Append To</button>
      <div id="output-add-dom" class="output"></div>
  </div>
  <pre><code class="language-javascript">$(function(){
    const $addArea = $('#add-area');
    const $outputDiv = $('#output-add-dom');
    let counter = 0;

    $('#btn-append').on('click', function(){
        counter++;
        // HTML 문자열 추가
        $addArea.append(`&lt;p class="added"&gt;Append된 단락 ${counter}&lt;/p&gt;`);
        // jQuery 객체 추가 (새로 생성)
        const $newDiv = $('&lt;div&gt;').text(`Append된 div ${counter}`).addClass('box');
        $addArea.append($newDiv);
        $outputDiv.text('append 실행됨');
    });

    $('#btn-prepend').on('click', function(){
        counter++;
        $addArea.prepend(`&lt;p class="added"&gt;Prepend된 단락 ${counter}&lt;/p&gt;`);
        $outputDiv.text('prepend 실행됨');
    });

    $('#btn-after').on('click', function(){
        counter++;
        $addArea.after(`&lt;p class="added"&gt;After된 단락 ${counter} (div 바깥)&lt;/p&gt;`);
        $outputDiv.text('after 실행됨');
    });

     $('#btn-before').on('click', function(){
        counter++;
        $addArea.before(`&lt;p class="added"&gt;Before된 단락 ${counter} (div 바깥)&lt;/p&gt;`);
        $outputDiv.text('before 실행됨');
    });

    $('#btn-append-to').on('click', function(){
        counter++;
        // 새로 생성한 요소를 특정 위치로 이동/추가
        $(`&lt;span style="color:red;"&gt;[Appended To ${counter}] &lt;/span&gt;`).appendTo($addArea.find('p:first'));
        $outputDiv.text('appendTo 실행됨');
    });
});</code></pre>
</section>

<section id="dom-removing">
  <h3>요소 제거 (.remove(), .empty(), .detach())</h3>
  <p>선택한 요소를 DOM 트리에서 제거합니다.</p>
  <ul>
    <li><code>.remove([selector])</code>: 선택한 요소를 DOM에서 완전히 제거합니다. 해당 요소에 연결된 데이터나 이벤트 핸들러도 함께 제거됩니다. 인수로 선택자를 전달하면, 선택된 요소들 중 해당 선택자와 일치하는 요소만 제거합니다.</li>
    <li><code>.empty()</code>: 선택한 요소의 모든 자식 요소를 제거합니다. 선택한 요소 자체는 그대로 남아있습니다.</li>
    <li><code>.detach([selector])</code>: <code>.remove()</code>와 유사하게 요소를 DOM에서 제거하지만, 연결된 데이터와 이벤트 핸들러는 유지합니다. 나중에 해당 요소를 다시 DOM에 추가하면 데이터와 이벤트가 그대로 살아있습니다.</li>
  </ul>
   <div class="example">
      <h4>요소 제거 예제용 HTML</h4>
      <div id="remove-area" class="box">
          <p id="p1">첫 번째 자식 단락</p>
          <p id="p2">두 번째 자식 단락 <button class="remove-btn">Remove Me</button></p>
          <p id="p3">세 번째 자식 단락</p>
      </div>
      <button id="btn-remove-p2">p#p2 제거 (remove)</button>
      <button id="btn-empty-area">#remove-area 비우기 (empty)</button>
      <button id="btn-detach-p1">p#p1 분리 (detach)</button>
      <button id="btn-reattach-p1">분리된 p#p1 다시 추가</button>
      <div id="output-remove-dom" class="output"></div>
  </div>
  <pre><code class="language-javascript">$(function(){
    const $removeArea = $('#remove-area');
    const $outputDiv = $('#output-remove-dom');
    let $detachedElement = null; // 분리된 요소를 저장할 변수

    // 버튼 클릭 시 자신(버튼) 제거
    // .on()의 이벤트 위임 활용 (파트 2 후반부에 설명)
    $removeArea.on('click', '.remove-btn', function(){
        $(this).remove(); // 클릭된 버튼 제거
        $outputDiv.text('.remove-btn 제거됨');
    });

    $('#btn-remove-p2').on('click', function(){
        $('#p2').remove();
        $outputDiv.text('p#p2 제거됨');
    });

    $('#btn-empty-area').on('click', function(){
        $removeArea.empty(); // 자식 요소 모두 제거
        $outputDiv.text('#remove-area 내용 비워짐');
    });

    $('#btn-detach-p1').on('click', function(){
        const $p1 = $('#p1');
        if ($p1.length > 0) { // 요소가 존재하는지 확인
            $detachedElement = $p1.detach(); // 요소를 분리하고 변수에 저장
            $outputDiv.text('p#p1 분리됨 (데이터/이벤트 유지)');
            console.log($detachedElement); // jQuery 객체 확인
        } else {
            $outputDiv.text('p#p1이 이미 분리되었거나 없습니다.');
        }
    });

    $('#btn-reattach-p1').on('click', function(){
        if ($detachedElement) {
            $removeArea.prepend($detachedElement); // 분리했던 요소를 다시 추가
            $outputDiv.text('분리된 p#p1 다시 추가됨');
            $detachedElement = null; // 다시 추가했으므로 변수 비우기
        } else {
            $outputDiv.text('분리된 요소가 없습니다.');
        }
    });
});</code></pre>
</section>

<section id="dom-cloning">
  <h3>요소 복제 (.clone())</h3>
  <p><code>.clone([withDataAndEvents], [deepWithDataAndEvents])</code> 메서드는 선택한 요소를 복제합니다.</p>
  <ul>
    <li>기본적으로 요소 자체와 그 자손 요소들을 복제합니다 (깊은 복사).</li>
    <li>첫 번째 인수로 <code>true</code>를 전달하면, 요소에 연결된 데이터(<code>.data()</code>)와 이벤트 핸들러까지 함께 복제합니다. (기본값: <code>false</code>)</li>
    <li>두 번째 인수는 첫 번째 인수와 동일한 기능을 하지만, 자손 요소들의 데이터와 이벤트까지 복제할지를 결정합니다. (일반적으로 첫 번째 인수만 사용)</li>
  </ul>
   <div class="example">
      <h4>요소 복제 예제용 HTML</h4>
      <div id="clone-source" class="box">
          원본 박스 <button class="clone-btn">클릭!</button>
      </div>
      <div id="clone-target" class="box" style="min-height: 50px; background-color: lightgray;">복제 대상 영역</div>
      <button id="btn-clone">복제 (기본)</button>
      <button id="btn-clone-with-events">복제 (이벤트 포함)</button>
      <div id="output-clone-dom" class="output"></div>
  </div>
  <pre><code class="language-javascript">$(function(){
    const $source = $('#clone-source');
    const $target = $('#clone-target');
    const $outputDiv = $('#output-clone-dom');

    // 원본 요소에 이벤트 핸들러 등록
    $source.find('.clone-btn').on('click', function(){
        $outputDiv.text('원본 버튼 클릭됨!');
    });
    // 원본 요소에 데이터 저장 (jQuery .data() 사용)
    $source.data('info', '원본 데이터');

    $('#btn-clone').on('click', function(){
        const $cloned = $source.clone(); // 기본 복제 (이벤트, 데이터 복제 X)
        $cloned.find('.clone-btn').text('클릭(X)'); // 복제본 버튼 텍스트 변경
        $target.append($cloned);
        $outputDiv.text('기본 복제 완료. 복제본의 버튼을 클릭해보세요.');
        console.log('기본 복제본 데이터:', $cloned.data('info')); // undefined
    });

    $('#btn-clone-with-events').on('click', function(){
        const $clonedWithEvents = $source.clone(true); // 이벤트, 데이터 함께 복제
        $clonedWithEvents.find('.clone-btn').text('클릭(O)');
        $target.append($clonedWithEvents);
        $outputDiv.text('이벤트 포함 복제 완료. 복제본의 버튼을 클릭해보세요.');
        console.log('이벤트 포함 복제본 데이터:', $clonedWithEvents.data('info')); // "원본 데이터"
    });
});</code></pre>
</section>

<section id="dom-wrapping">
    <h3>요소 감싸기/해제 (.wrap(), .unwrap() 등)</h3>
    <p>선택한 요소를 다른 HTML 구조로 감싸거나, 부모 요소를 제거할 수 있습니다.</p>
    <ul>
        <li><code>.wrap(wrappingElement)</code>: 선택한 각각의 요소를 주어진 HTML 구조나 요소로 감쌉니다.</li>
        <li><code>.wrapAll(wrappingElement)</code>: 선택한 모든 요소를 하나의 그룹으로 묶어 주어진 HTML 구조나 요소로 감쌉니다.</li>
        <li><code>.wrapInner(wrappingElement)</code>: 선택한 각 요소의 내부 콘텐츠를 주어진 HTML 구조나 요소로 감쌉니다.</li>
        <li><code>.unwrap()</code>: 선택한 요소의 부모 요소를 제거합니다.</li>
    </ul>
    <p><code>wrappingElement</code> 인자로는 HTML 문자열, DOM 요소, jQuery 객체, 또는 함수가 올 수 있습니다.</p>
    <div class="example">
        <h4>요소 감싸기 예제용 HTML</h4>
        <div id="wrap-area">
            <p class="wrap-item">아이템 1</p>
            <p class="wrap-item">아이템 2</p>
            <span class="wrap-item">아이템 3</span>
        </div>
        <button id="btn-wrap">Wrap</button>
        <button id="btn-wrap-all">Wrap All</button>
        <button id="btn-wrap-inner">Wrap Inner</button>
        <button id="btn-unwrap">Unwrap</button>
        <div id="output-wrap-dom" class="output"></div>
    </div>
    <pre><code class="language-javascript">$(function(){
        const $items = $('#wrap-area .wrap-item');
        const $outputDiv = $('#output-wrap-dom');

        $('#btn-wrap').on('click', function(){
            $items.wrap('&lt;div class="wrapper" style="border: 1px solid red; margin: 3px;"&gt;&lt;/div&gt;');
            $outputDiv.text('.wrap() 실행됨 - 각 요소가 개별 div로 감싸짐');
        });

        $('#btn-wrap-all').on('click', function(){
             // wrap 하기 전에 이전 wrapper를 제거해야 할 수 있음 (unwrap 사용)
            if ($items.parent().is('.group-wrapper')) $items.unwrap();
            $items.wrapAll('&lt;div class="group-wrapper" style="border: 2px solid blue; padding: 10px;"&gt;&lt;/div&gt;');
            $outputDiv.text('.wrapAll() 실행됨 - 모든 요소가 하나의 div로 감싸짐');
        });

        $('#btn-wrap-inner').on('click', function(){
            $items.wrapInner('&lt;strong style="color: green;"&gt;&lt;/strong&gt;');
            $outputDiv.text('.wrapInner() 실행됨 - 각 요소 내부 콘텐츠가 strong 태그로 감싸짐');
        });

        $('#btn-unwrap').on('click', function(){
            // 가장 최근에 감싼 wrapper를 제거
            if ($items.parent().is('strong')) {
                $items.unwrap(); // strong 태그 제거
            } else if ($items.parent().is('.wrapper')) {
                $items.unwrap(); // 개별 wrapper 제거
            } else if ($items.parent().is('.group-wrapper')) {
                $items.unwrap(); // 그룹 wrapper 제거
            }
            $outputDiv.text('.unwrap() 실행됨 - 부모 요소 제거 시도');
        });
});</code></pre>
</section>

<section id="dom-traversing">
  <h2>DOM 탐색 (Traversing)</h2>
  <p>jQuery는 선택된 요소를 기준으로 DOM 트리를 쉽게 이동하며 다른 요소를 찾는 강력한 탐색(Traversing) 메서드들을 제공합니다.</p>

  <h3 id="traverse-filtering">필터링 (.filter(), .not(), .has(), .is(), .eq())</h3>
  <p>선택된 요소 집합 내에서 특정 조건에 맞는 요소만 걸러내거나, 특정 조건에 맞는지 확인할 수 있습니다.</p>
  <ul>
      <li><code>.filter(selector | function)</code>: 선택된 요소 중 주어진 선택자나 함수 조건에 맞는 요소만 남깁니다.</li>
      <li><code>.not(selector | function)</code>: 선택된 요소 중 주어진 선택자나 함수 조건에 맞지 않는 요소만 남깁니다.</li>
      <li><code>.has(selector)</code>: 선택된 요소 중 지정된 선택자와 일치하는 자손 요소를 가진 요소만 남깁니다.</li>
      <li><code>.is(selector | function)</code>: 선택된 요소 중 하나라도 주어진 선택자나 함수 조건에 맞으면 <code>true</code>를 반환합니다.</li>
      <li><code>.eq(index)</code>: 선택된 요소 집합에서 특정 인덱스(음수 인덱스 가능, -1은 마지막 요소)에 해당하는 요소를 선택합니다. (jQuery 객체 반환)</li>
  </ul>
   <div class="example">
      <h4>필터링 예제용 HTML</h4>
      <ul id="filter-list">
          <li class="item active">항목 1 (active)</li>
          <li class="item">항목 2</li>
          <li class="item special active">항목 3 (active, special)</li>
          <li class="item">항목 4 <span>(span 포함)</span></li>
          <li class="item special">항목 5 (special)</li>
      </ul>
      <div id="output-filter-dom" class="output"></div>
  </div>
  <pre><code class="language-javascript">$(function(){
      const $listItems = $('#filter-list li');
      const $outputDiv = $('#output-filter-dom');

      // .filter(): .active 클래스를 가진 li 요소만 선택
      const $activeItems = $listItems.filter('.active');
      $outputDiv.html(`Active 항목 개수: ${$activeItems.length}<br>`);
      $activeItems.css('background-color', 'yellow');

      // .not(): .special 클래스를 가지지 않은 li 요소만 선택
      const $notSpecialItems = $listItems.not('.special');
      $outputDiv.append(`Not Special 항목 개수: ${$notSpecialItems.length}<br>`);

      // .has(): span 자손을 가진 li 요소 선택
      const $hasSpanItems = $listItems.has('span');
      $outputDiv.append(`Span 포함 항목: ${$hasSpanItems.text()}<br>`);
      $hasSpanItems.css('border', '1px solid green');

      // .is(): #filter-list가 ul 태그인지 확인
      let isUl = $('#filter-list').is('ul');
      $outputDiv.append(`#filter-list는 ul인가? ${isUl}<br>`);

      // .eq(): 세 번째(index 2) li 요소 선택
      const $thirdItem = $listItems.eq(2); // 항목 3
      $thirdItem.css('color', 'red');
      // 마지막 요소 선택 (음수 인덱스)
      const $lastItem = $listItems.eq(-1); // 항목 5
      $lastItem.css('font-weight', 'bold');
  });</code></pre>

  <h3 id="traverse-descendants">자손 탐색 (.find(), .children())</h3>
  <ul>
      <li><code>.find(selector)</code>: 선택된 요소의 모든 자손 중에서 주어진 선택자와 일치하는 요소를 찾습니다.</li>
      <li><code>.children([selector])</code>: 선택된 요소의 직계 자식 중에서 주어진 선택자(선택 사항)와 일치하는 요소를 찾습니다.</li>
  </ul>
  <div class="example">
      <h4>자손 탐색 예제용 HTML</h4>
      <div id="descendant-area" class="box">
          <p class="level1">레벨 1 - 단락 1</p>
          <div class="level1 box"> 레벨 1 - div
              <p class="level2">레벨 2 - 단락 2</p>
              <span class="level2">레벨 2 - span</span>
          </div>
          <p class="level1">레벨 1 - 단락 3</p>
      </div>
  </div>
  <pre><code class="language-javascript">$(function(){
      const $area = $('#descendant-area');

      // .find(): #descendant-area 안의 모든 p 요소
      const $allParas = $area.find('p');
      console.log('find("p") 개수:', $allParas.length); // 3
      $allParas.css('text-decoration', 'underline');

      // .children(): #descendant-area의 직계 자식 요소
      const $directChildren = $area.children();
      console.log('children() 개수:', $directChildren.length); // 3 (p, div, p)
      // .children()에 선택자 사용: 직계 자식 중 div 요소
      const $directDiv = $area.children('div');
      $directDiv.css('border-color', 'red');
  });</code></pre>

  <h3 id="traverse-ancestors">조상 탐색 (.parent(), .parents(), .closest() 등)</h3>
   <ul>
      <li><code>.parent([selector])</code>: 선택된 요소의 직계 부모 요소를 선택합니다. 선택자(선택 사항)를 전달하면 부모가 해당 선택자와 일치할 경우에만 선택됩니다.</li>
      <li><code>.parents([selector])</code>: 선택된 요소의 모든 조상 요소를 선택합니다 (DOM 트리 루트까지). 선택자(선택 사항)를 전달하면 조상 중 해당 선택자와 일치하는 요소만 필터링됩니다.</li>
      <li><code>.parentsUntil(selector | element)</code>: 선택된 요소의 조상 요소를 찾되, 주어진 선택자나 요소 이전까지의 조상만 선택합니다.</li>
      <li><code>.closest(selector)</code>: 선택된 요소 자신을 포함하여 가장 가까운 조상 중 주어진 선택자와 일치하는 첫 번째 요소를 찾습니다. 매우 유용.</li>
  </ul>
  <div class="example">
      <h4>조상 탐색 예제용 HTML (위 자손 탐색 예제 재활용)</h4>
  </div>
  <pre><code class="language-javascript">$(function(){
      const $level2Span = $('#descendant-area .level2:contains("span")'); // 레벨 2 span 선택

      // .parent(): 직계 부모 (레벨 1 div)
      const $directParent = $level2Span.parent();
      $directParent.css('background-color', 'lightyellow');

      // .parents(): 모든 조상 (레벨 1 div, #descendant-area, body, html)
      const $allAncestors = $level2Span.parents();
      console.log('parents() 개수:', $allAncestors.length);
      // .parents()에 선택자 사용: 조상 중 .box 클래스를 가진 요소
      const $boxAncestors = $level2Span.parents('.box');
      $boxAncestors.css('box-shadow', '2px 2px 5px gray');

      // .closest(): 자신 포함 가장 가까운 .box 조상 (#descendant-area 안의 .box)
      const $closestBox = $level2Span.closest('.box');
      console.log('closest(".box") ID:', $closestBox.parent().attr('id')); // 부모 ID 확인

      // .parentsUntil(): #descendant-area 이전까지의 조상 (레벨 1 div)
      const $parentsUntilArea = $level2Span.parentsUntil('#descendant-area');
      $parentsUntilArea.css('padding', '15px');
  });</code></pre>

  <h3 id="traverse-siblings">형제 탐색 (.siblings(), .next(), .prev() 등)</h3>
  <ul>
      <li><code>.siblings([selector])</code>: 선택된 요소의 모든 형제 요소를 선택합니다. 선택자(선택 사항)로 필터링 가능.</li>
      <li><code>.next([selector])</code>: 선택된 요소의 바로 다음 형제 요소를 선택합니다.</li>
      <li><code>.prev([selector])</code>: 선택된 요소의 바로 이전 형제 요소를 선택합니다.</li>
      <li><code>.nextAll([selector])</code>: 선택된 요소 뒤에 오는 모든 형제 요소를 선택합니다.</li>
      <li><code>.prevAll([selector])</code>: 선택된 요소 앞에 오는 모든 형제 요소를 선택합니다.</li>
      <li><code>.nextUntil(selector | element)</code>: 선택된 요소 뒤의 형제 중 주어진 선택자/요소 이전까지의 형제를 선택합니다.</li>
      <li><code>.prevUntil(selector | element)</code>: 선택된 요소 앞의 형제 중 주어진 선택자/요소 이전까지의 형제를 선택합니다.</li>
  </ul>
  <div class="example">
      <h4>형제 탐색 예제용 HTML (위 필터링 예제 재활용)</h4>
  </div>
   <pre><code class="language-javascript">$(function(){
       const $item3 = $('#filter-list .special:contains("항목 3")'); // 항목 3 선택

       // .siblings(): 항목 3의 모든 형제 요소
       const $siblings = $item3.siblings();
       console.log('siblings() 개수:', $siblings.length); // 4
       // .siblings()에 선택자 사용: 형제 중 .active 클래스를 가진 요소 (항목 1)
       const $activeSibling = $item3.siblings('.active');
       $activeSibling.css('font-size', '1.2em');

       // .next(): 항목 3 바로 다음 형제 (항목 4)
       const $nextItem = $item3.next();
       $nextItem.css('border-top', '2px solid blue');

       // .prev(): 항목 3 바로 이전 형제 (항목 2)
       const $prevItem = $item3.prev();
       $prevItem.css('border-bottom', '2px solid blue');

       // .nextAll(): 항목 3 뒤의 모든 형제 (항목 4, 5)
       const $nextAllItems = $item3.nextAll();
       $nextAllItems.css('margin-left', '20px');

       // .prevAll(): 항목 3 앞의 모든 형제 (항목 1, 2)
       const $prevAllItems = $item3.prevAll();
       $prevAllItems.css('opacity', 0.7);
   });</code></pre>

  <h3 id="traverse-chaining">체이닝 (Chaining)</h3>
  <p>대부분의 jQuery 메서드는 jQuery 객체 자신을 반환합니다. 이를 통해 여러 메서드를 점(<code>.</code>)으로 연결하여 연속적으로 호출하는 체이닝(Chaining)이 가능합니다. 코드를 간결하고 읽기 쉽게 만들어 줍니다.</p>
  <p><code>.end()</code> 메서드를 사용하면 체이닝 중간에 필터링 등으로 변경된 선택 상태를 이전 상태로 되돌릴 수 있습니다.</p>
  <pre><code class="language-javascript">$(function(){
      $('#filter-list li') // 1. 모든 li 선택
          .filter(':odd')    // 2. 홀수 번째 li만 필터링 (인덱스 1, 3)
          .css('background-color', 'lightgray') // 3. 배경색 변경
          .end()             // 4. 선택 상태를 이전(모든 li)으로 되돌림
          .eq(0)             // 5. 첫 번째 li 선택 (항목 1)
          .addClass('highlight') // 6. 클래스 추가
          .next()            // 7. 다음 형제 li 선택 (항목 2)
          .css('color', 'green'); // 8. 글자색 변경
  });</code></pre>
</section>


<section id="events">
  <h2>이벤트 처리 (Events)</h2>
  <p>jQuery는 JavaScript의 이벤트 처리를 더욱 간편하고 일관성 있게 만들어 줍니다.</p>

  <h3 id="event-binding">이벤트 핸들러 바인딩 (.on(), 단축 메서드)</h3>
  <p>이벤트 핸들러를 요소에 연결하는 가장 핵심적인 메서드는 <strong><code>.on()</code></strong> 입니다. 여러 이벤트 타입을 한 번에 등록하거나, 이벤트 위임(delegation)을 구현하는 등 매우 유연합니다.</p>
  <p><code>$(selector).on(events, [selector], [data], handler)</code></p>
  <ul>
      <li><code>events</code>: 하나 이상의 이벤트 타입 문자열 (공백으로 구분, 예: <code>'click'</code>, <code>'mouseover mouseout'</code>). 네임스페이스 지정 가능 (예: <code>'click.myPlugin'</code>).</li>
      <li><code>selector</code> (선택 사항): 이벤트 위임을 위한 하위 요소 선택자 문자열. 지정하면, 선택된 요소(<code>$(selector)</code>) 내부의 `selector`와 일치하는 하위 요소에서 이벤트가 발생했을 때만 핸들러가 실행됩니다. (자세한 내용은 <a href="#event-delegation">이벤트 위임</a> 참조)</li>
      <li><code>data</code> (선택 사항): 이벤트 핸들러에 전달할 추가 데이터 객체. 핸들러 내에서 <code>event.data</code>로 접근 가능.</li>
      <li><code>handler(eventObject)</code>: 이벤트 발생 시 실행될 함수. 이벤트 객체를 인수로 받습니다.</li>
  </ul>

  <h4>단축 메서드 (Shorthand Methods)</h4>
  <p>자주 사용되는 이벤트를 위한 단축 메서드도 제공됩니다. 내부적으로 <code>.on()</code>을 사용합니다.</p>
  <p>예: <code>.click(handler)</code>, <code>.dblclick(handler)</code>, <code>.mouseenter(handler)</code>, <code>.mouseleave(handler)</code>, <code>.hover(handlerInOut, handlerOut)</code>, <code>.focus(handler)</code>, <code>.blur(handler)</code>, <code>.keydown(handler)</code>, <code>.keyup(handler)</code>, <code>.change(handler)</code>, <code>.submit(handler)</code> 등.</p>

   <div class="example">
      <h4>이벤트 바인딩 예제용 HTML</h4>
      <button id="btn-on-click">클릭 (.on)</button>
      <button id="btn-shorthand">클릭 (단축)</button>
      <input type="text" id="input-focus" placeholder="포커스/블러">
      <div id="hover-box" class="box">마우스를 올려보세요 (.hover)</div>
      <div id="output-event-bind" class="output"></div>
  </div>
  <pre><code class="language-javascript">$(function(){
      const $outputDiv = $('#output-event-bind');

      // .on() 사용
      $('#btn-on-click').on('click', function(event) {
          $outputDiv.text(`.on()으로 클릭 이벤트 처리됨. 타입: ${event.type}`);
      });

      // 여러 이벤트 한 번에 등록 + 네임스페이스
      $('#input-focus').on('focus.myApp blur.myApp', function(event){
          let message = event.type === 'focus' ? '포커스 얻음' : '포커스 잃음';
          $(this).toggleClass('highlight'); // this는 이벤트 발생 요소(input)
          $outputDiv.text(message + ' (네임스페이스: .myApp)');
      });

      // 단축 메서드 사용
      $('#btn-shorthand').click(function() { // .on('click', handler)와 동일
          $outputDiv.text('단축 메서드(.click)로 처리됨');
      });

      // .hover() 사용 (mouseenter + mouseleave)
      $('#hover-box').hover(
          function() { // mouseenter 핸들러
              $(this).addClass('highlight');
              $outputDiv.text('마우스 들어옴 (.hover)');
          },
          function() { // mouseleave 핸들러
              $(this).removeClass('highlight');
              $outputDiv.text('마우스 나감 (.hover)');
          }
      );

      // 데이터 전달 예시
      $('#btn-on-click').on('click', { user: "Alice" }, function(event) {
          console.log(`클릭한 사용자: ${event.data.user}`); // "Alice"
      });

  });</code></pre>

  <h3 id="event-unbinding">이벤트 핸들러 제거 (.off())</h3>
  <p><code>.on()</code>으로 등록한 이벤트 핸들러를 제거할 때는 <strong><code>.off()</code></strong> 메서드를 사용합니다.</p>
  <p><code>$(selector).off(events, [selector], [handler])</code></p>
  <ul>
      <li>인수 없이 <code>.off()</code>: 요소에 등록된 모든 이벤트 핸들러 제거.</li>
      <li><code>.off('click')</code>: 요소의 모든 'click' 이벤트 핸들러 제거.</li>
      <li><code>.off('click', handlerFunction)</code>: 요소의 'click' 이벤트 중 특정 `handlerFunction`만 제거. (핸들러 제거 시 함수 참조가 동일해야 하므로 익명 함수가 아닌 기명 함수 사용 필요)</li>
      <li><code>.off('click', '.child-selector')</code>: 이벤트 위임으로 등록된 특정 하위 요소('.child-selector')의 'click' 핸들러 제거.</li>
      <li><code>.off('.myNamespace')</code>: 특정 네임스페이스('.myNamespace')를 가진 모든 이벤트 핸들러 제거.</li>
  </ul>
   <pre><code class="language-javascript">$(function(){
      function handleClick() {
          console.log("특정 핸들러 클릭됨!");
          // 이 핸들러를 제거하려면 기명 함수여야 함
          $('#btn-remove-handler').off('click', handleClick); // 자신을 제거
          $('#output-remove-event').text('handleClick 핸들러 제거됨');
      }

      $('#btn-remove-handler').on('click', handleClick); // 기명 함수로 핸들러 등록

      $('#btn-remove-all-clicks').on('click', function(){
          $('#btn-remove-handler').off('click'); // #btn-remove-handler의 모든 click 핸들러 제거
          $('#output-remove-event').text('#btn-remove-handler의 모든 클릭 이벤트 제거됨');
      });

       $('#btn-remove-namespace').on('click', function(){
          $('#input-focus').off('.myApp'); // .myApp 네임스페이스 이벤트 모두 제거
          $('#output-remove-event').text('#input-focus의 .myApp 네임스페이스 이벤트 제거됨');
      });
  });</code></pre>
   <div class="example">
      <h4>이벤트 제거 예제용 HTML</h4>
      <button id="btn-remove-handler">클릭 (특정 핸들러 제거용)</button>
      <button id="btn-remove-all-clicks">모든 Click 핸들러 제거</button>
      <button id="btn-remove-namespace">네임스페이스(.myApp) 제거</button>
      <input type="text" id="input-focus" placeholder="포커스/블러"> <div id="output-remove-event" class="output"></div>
  </div>

  <h3 id="event-object">이벤트 객체</h3>
  <p>jQuery는 브라우저 간의 차이를 일부 표준화한 이벤트 객체(Event Object)를 핸들러 함수에 전달합니다. 바닐라 JS의 이벤트 객체와 유사한 정보를 포함합니다.</p>
  <p>주요 프로퍼티 및 메서드:</p>
  <ul>
    <li><code>event.type</code>: 이벤트 타입 (예: 'click').</li>
    <li><code>event.target</code>: 이벤트가 발생한 가장 안쪽 요소.</li>
    <li><code>event.currentTarget</code>: 이벤트 핸들러가 부착된 요소 (<code>this</code>와 동일).</li>
    <li><code>event.relatedTarget</code>: 마우스 이벤트(<code>mouseover</code>, <code>mouseout</code> 등)에서 관련 요소 (예: 마우스가 들어오기 전/나간 후 요소).</li>
    <li><code>event.pageX</code> / <code>event.pageY</code>: 문서 전체 기준 마우스 포인터 좌표.</li>
    <li><code>event.which</code>: 키보드 키 코드 또는 마우스 버튼 번호 (브라우저 차이 표준화).</li>
    <li><code>event.data</code>: <code>.on()</code>으로 핸들러 등록 시 전달한 데이터.</li>
    <li><code>event.preventDefault()</code>: 이벤트의 기본 동작 취소.</li>
    <li><code>event.stopPropagation()</code>: 이벤트 버블링 중단.</li>
    <li><code>event.stopImmediatePropagation()</code>: 이벤트 버블링 및 같은 요소에 등록된 다른 핸들러 실행까지 중단.</li>
    <li><code>event.timeStamp</code>: 이벤트 발생 시각 (jQuery가 표준화 시도).</li>
  </ul>
  <pre><code class="language-javascript">$(function(){
      $('#event-object-area').on('click', function(event){
          let info = `타입: ${event.type}, 타겟: ${event.target.id}, currentTarget: ${event.currentTarget.id}, 마우스 X: ${event.pageX}`;
          $('#output-event-obj').text(info);
          console.log(event); // 콘솔에서 전체 이벤트 객체 확인 가능
      });
      $('#event-object-area a').on('click', function(event){
          event.preventDefault(); // 링크 이동 방지
          $('#output-event-obj').append('<br>링크 기본 동작 방지됨.');
      });
  });</code></pre>
  <div class="example">
      <h4>이벤트 객체 예제용 HTML</h4>
      <div id="event-object-area" class="box">
          <p>여기를 클릭해보세요.</p>
          <button id="obj-btn">버튼</button>
          <a href="#" id="obj-link">링크 (기본동작 방지됨)</a>
      </div>
      <div id="output-event-obj" class="output"></div>
  </div>

  <h3 id="event-delegation">이벤트 위임 (.on() 활용)</h3>
  <p>이벤트 위임은 여러 하위 요소의 이벤트를 공통 조상 요소에서 처리하는 효율적인 방법입니다. <code>.on()</code> 메서드의 두 번째 인수인 <code>selector</code>를 사용합니다.</p>
  <p><code>$(ancestorSelector).on(eventType, descendantSelector, handler)</code></p>
  <p><code>ancestorSelector</code>에 이벤트 리스너를 등록하지만, 이벤트가 발생했을 때 <code>event.target</code>(또는 그 조상 중)이 <code>descendantSelector</code>와 일치하는 경우에만 <code>handler</code> 함수를 실행합니다. 핸들러 내에서 <code>this</code>는 <code>descendantSelector</code>와 일치한 요소를 가리킵니다.</p>
  <p>장점:</p>
  <ul>
    <li>DOM에 추가되는 이벤트 리스너 수를 줄여 성능 향상.</li>
    <li>동적으로 추가/삭제되는 요소에 대해서도 별도의 이벤트 처리 없이 동작.</li>
  </ul>
   <div class="example">
      <h4>이벤트 위임 예제용 HTML</h4>
      <ul id="delegation-list-jq">
          <li>아이템 A</li>
          <li>아이템 B</li>
          <li>아이템 C</li>
      </ul>
      <button id="btn-add-item-jq">아이템 추가 (위임)</button>
      <div id="output-delegation-jq" class="output">클릭된 아이템:</div>
  </div>
  <pre><code class="language-javascript">$(function(){
      const $list = $('#delegation-list-jq');
      const $outputDiv = $('#output-delegation-jq');

      // 상위 요소(ul)에 이벤트 핸들러 위임
      $list.on('click', 'li', function(event){
          // 여기서 this는 클릭된 'li' 요소를 가리킴
          const $clickedItem = $(this); // 클릭된 li의 jQuery 객체
          $outputDiv.text(`클릭된 아이템: ${$clickedItem.text()}`);
          // 모든 li의 highlight 클래스 제거 후 클릭된 항목에만 추가
          $list.find('li').removeClass('highlight');
          $clickedItem.addClass('highlight');
      });

      // 동적으로 아이템 추가
      let newItemCounter = 0;
      $('#btn-add-item-jq').on('click', function(){
          newItemCounter++;
          const $newItem = $(`&lt;li&gt;새 아이템 ${newItemCounter}&lt;/li&gt;`);
          $list.append($newItem);
          // 새로 추가된 li에도 위임된 이벤트 핸들러가 자동으로 적용됨!
      });
  });</code></pre>

  <h3 id="event-triggering">이벤트 강제 발생 (.trigger(), .triggerHandler())</h3>
  <p>코드 내에서 특정 요소의 이벤트를 인위적으로 발생시킬 수 있습니다.</p>
  <ul>
      <li><code>.trigger('eventType', [extraParameters])</code>: 지정된 타입의 이벤트를 발생시키고, 브라우저의 기본 동작(예: 폼의 submit)도 수행합니다. 이벤트 버블링도 발생합니다.</li>
      <li><code>.triggerHandler('eventType', [extraParameters])</code>: 이벤트를 발생시키지만, 기본 동작은 수행하지 않고 이벤트 버블링도 발생하지 않습니다. 선택된 요소 집합 중 첫 번째 요소에 대해서만 핸들러를 실행하고 그 핸들러의 반환값을 반환합니다.</li>
  </ul>
   <div class="example">
      <h4>이벤트 발생 예제용 HTML</h4>
      <button id="btn-real">실제 클릭 버튼</button>
      <button id="btn-trigger">Trigger Click</button>
      <button id="btn-trigger-handler">TriggerHandler Click</button>
      <form id="trigger-form" action="javascript:alert('Form Submitted!')">
        <button type="submit">Submit</button>
      </form>
      <div id="output-trigger" class="output"></div>
  </div>
   <pre><code class="language-javascript">$(function(){
      const $outputDiv = $('#output-trigger');

      $('#btn-real').on('click', function(){
          $outputDiv.text('실제 버튼 클릭됨!');
      });

      $('#btn-trigger').on('click', function(){
          $outputDiv.text('Trigger 버튼 클릭 -> 실제 버튼 클릭 강제 발생');
          $('#btn-real').trigger('click'); // #btn-real의 click 이벤트 발생
      });

       $('#btn-trigger-handler').on('click', function(){
          $outputDiv.text('TriggerHandler 버튼 클릭 -> 실제 버튼 클릭 강제 발생 (버블링/기본동작 X)');
          // triggerHandler는 핸들러만 실행
          $('#btn-real').triggerHandler('click');
      });

      $('#trigger-form').on('submit', function(event){
          // event.preventDefault(); // 이 줄 주석 처리 시 trigger()는 기본 제출 동작 수행
          $outputDiv.append('<br>Submit 이벤트 핸들러 실행됨!');
          return false; // triggerHandler 예제를 위해 false 반환
      });

      // 폼 제출 트리거
      // $('#trigger-form').trigger('submit'); // 기본 제출 동작(alert) 수행됨
      // $('#trigger-form').triggerHandler('submit'); // 기본 제출 동작 수행 안 됨, 핸들러만 실행

  });</code></pre>

  <h3 id="event-helper">이벤트 헬퍼 (.hover(), .ready() 등)</h3>
  <p>jQuery는 특정 이벤트 조합이나 상황을 더 쉽게 처리하기 위한 헬퍼 메서드를 제공합니다.</p>
  <ul>
      <li><code>.hover(handlerInOut, handlerOut)</code>: 마우스가 요소에 들어왔을 때(<code>mouseenter</code>)와 나갔을 때(<code>mouseleave</code>) 실행될 함수를 한 번에 등록합니다.</li>
      <li><code>$(document).ready(handler)</code> 또는 <code>$(handler)</code>: DOM 로딩 완료 시 실행될 함수를 등록합니다. (이미 설명됨)</li>
      <li><code>.toggle(handler1, handler2, ...)</code> (jQuery 1.8 이전 버전): 클릭할 때마다 등록된 함수들을 순차적으로 실행했으나, 현재는 요소의 표시/숨김 전환 메서드(<code>.toggle()</code>)로 의미가 변경되었습니다.</li>
  </ul>
</section>

<br><br>
<hr>

<section id="effects">
  <h2>효과 및 애니메이션 (Effects & Animations)</h2>
  <p>jQuery는 웹 페이지에 다양한 시각적 효과와 애니메이션을 쉽게 추가할 수 있는 메서드들을 제공합니다.</p>

  <h3 id="effects-basic">기본 효과 (.show(), .hide(), .toggle())</h3>
  <p>요소를 화면에 보이거나 숨기는 기본적인 효과입니다.</p>
  <ul>
      <li><code>.show([duration], [easing], [callback])</code>: 숨겨진 요소를 화면에 표시합니다.</li>
      <li><code>.hide([duration], [easing], [callback])</code>: 보이는 요소를 화면에서 숨깁니다.</li>
      <li><code>.toggle([duration], [easing], [callback])</code>: 요소의 보임/숨김 상태를 전환합니다.</li>
  </ul>
  <p><strong>옵션:</strong></p>
  <ul>
      <li><code>duration</code> (선택 사항): 효과 지속 시간 (밀리초 단위 숫자, 또는 문자열 'fast'(200ms), 'slow'(600ms)). 기본값 400ms.</li>
      <li><code>easing</code> (선택 사항): 효과의 속도 곡선 ('linear' 또는 'swing'(기본값)). 더 다양한 이징 효과는 jQuery UI 라이브러리 필요.</li>
      <li><code>callback</code> (선택 사항): 효과가 완료된 후 실행될 함수.</li>
  </ul>
  <div class="example">
      <h4>기본 효과 예제용 HTML</h4>
      <button id="btn-hide">Hide</button>
      <button id="btn-show">Show</button>
      <button id="btn-toggle">Toggle</button>
      <div id="basic-effect-box" class="box" style="background-color: lightgreen;">기본 효과 테스트 박스</div>
      <div id="output-effect-basic" class="output"></div>
  </div>
  <pre><code class="language-javascript">$(function(){
      const $box = $('#basic-effect-box');
      const $outputDiv = $('#output-effect-basic');

      $('#btn-hide').on('click', function(){
          $box.hide('slow', function(){ // 600ms 동안 숨기고 완료 후 콜백 실행
              $outputDiv.text('박스 숨김 완료 (slow)');
          });
      });
      $('#btn-show').on('click', function(){
          $box.show(1000, 'linear', function(){ // 1초 동안 linear 속도로 보이고 콜백 실행
              $outputDiv.text('박스 보임 완료 (1000ms, linear)');
          });
      });
      $('#btn-toggle').on('click', function(){
          $box.toggle('fast'); // 200ms 동안 상태 전환
           $outputDiv.text('박스 토글 실행 (fast)');
      });
  });</code></pre>

  <h3 id="effects-fading">페이딩 효과 (.fadeIn(), .fadeOut(), .fadeToggle(), .fadeTo())</h3>
  <p>요소의 투명도(opacity)를 조절하여 서서히 나타나거나 사라지는 효과를 만듭니다.</p>
  <ul>
      <li><code>.fadeIn([duration], [easing], [callback])</code>: 요소를 서서히 나타나게 합니다.</li>
      <li><code>.fadeOut([duration], [easing], [callback])</code>: 요소를 서서히 사라지게 합니다.</li>
      <li><code>.fadeToggle([duration], [easing], [callback])</code>: 페이드 인/아웃 상태를 전환합니다.</li>
      <li><code>.fadeTo(duration, opacity, [easing], [callback])</code>: 지정된 시간 동안 요소의 투명도를 특정 `opacity` 값(0.0 ~ 1.0)으로 변경합니다. 요소가 완전히 숨겨지지는 않습니다(공간 차지).</li>
  </ul>
  <div class="example">
      <h4>페이딩 효과 예제용 HTML</h4>
      <button id="btn-fade-out">Fade Out</button>
      <button id="btn-fade-in">Fade In</button>
      <button id="btn-fade-toggle">Fade Toggle</button>
      <button id="btn-fade-to">Fade To 0.5</button>
      <div id="fade-box" class="box" style="background-color: lightblue;">페이딩 효과 테스트 박스</div>
      <div id="output-effect-fade" class="output"></div>
  </div>
   <pre><code class="language-javascript">$(function(){
      const $box = $('#fade-box');
      const $outputDiv = $('#output-effect-fade');

      $('#btn-fade-out').on('click', function(){
          $box.fadeOut(800, function(){
              $outputDiv.text('Fade Out 완료');
          });
      });
      $('#btn-fade-in').on('click', function(){
          $box.fadeIn('slow');
          $outputDiv.text('Fade In 실행 (slow)');
      });
      $('#btn-fade-toggle').on('click', function(){
          $box.fadeToggle(function(){
             $outputDiv.text('Fade Toggle 완료');
          });
      });
       $('#btn-fade-to').on('click', function(){
          $box.fadeTo('fast', 0.5); // 투명도 50%로 변경
          $outputDiv.text('Fade To 0.5 실행 (fast)');
      });
  });</code></pre>

  <h3 id="effects-sliding">슬라이딩 효과 (.slideDown(), .slideUp(), .slideToggle())</h3>
  <p>요소의 높이를 조절하여 미끄러지듯 나타나거나 사라지는 효과를 만듭니다.</p>
   <ul>
      <li><code>.slideDown([duration], [easing], [callback])</code>: 숨겨진 요소를 아래로 미끄러지듯 나타나게 합니다.</li>
      <li><code>.slideUp([duration], [easing], [callback])</code>: 보이는 요소를 위로 미끄러지듯 사라지게 합니다.</li>
      <li><code>.slideToggle([duration], [easing], [callback])</code>: 슬라이드 다운/업 상태를 전환합니다.</li>
  </ul>
   <div class="example">
      <h4>슬라이딩 효과 예제용 HTML</h4>
      <button id="btn-slide-up">Slide Up</button>
      <button id="btn-slide-down">Slide Down</button>
      <button id="btn-slide-toggle">Slide Toggle</button>
      <div id="slide-box" class="box" style="background-color: lightcoral; padding: 20px;">슬라이딩 효과 테스트 박스<br>여러 줄 내용<br>확인용</div>
      <div id="output-effect-slide" class="output"></div>
  </div>
   <pre><code class="language-javascript">$(function(){
      const $box = $('#slide-box');
      const $outputDiv = $('#output-effect-slide');

      $('#btn-slide-up').on('click', function(){
          $box.slideUp(function(){
              $outputDiv.text('Slide Up 완료');
          });
      });
      $('#btn-slide-down').on('click', function(){
          $box.slideDown('slow');
           $outputDiv.text('Slide Down 실행 (slow)');
      });
      $('#btn-slide-toggle').on('click', function(){
          $box.slideToggle(1000); // 1초 동안 토글
           $outputDiv.text('Slide Toggle 실행 (1000ms)');
      });
  });</code></pre>

  <h3 id="effects-animate">사용자 정의 애니메이션 (.animate())</h3>
  <p><code>.animate()</code> 메서드를 사용하면 여러 숫자형 CSS 속성 값을 부드럽게 변경하는 사용자 정의 애니메이션을 만들 수 있습니다.</p>
  <p><code>.animate(properties, [duration], [easing], [callback])</code></p>
  <ul>
      <li><code>properties</code>: 애니메이션 할 CSS 속성과 목표값을 포함하는 객체. (예: <code>{ opacity: 0.5, left: '+=50', height: 'toggle' }</code>)
          <ul>
              <li>값은 숫자(단위 없는 경우 px), 또는 <code>'show'</code>, <code>'hide'</code>, <code>'toggle'</code> 문자열, 또는 상대값(<code>'+=10'</code>, <code>'-=10'</code>)일 수 있습니다.</li>
              <li>색상 애니메이션은 기본적으로 지원되지 않지만, jQuery UI나 다른 플러그인을 사용하면 가능합니다.</li>
              <li><code>transform</code> 속성 애니메이션은 주의가 필요하며, CSS Transition/Animation을 사용하는 것이 더 효율적일 수 있습니다.</li>
          </ul>
      </li>
      <li><code>duration</code>, <code>easing</code>, <code>callback</code>: 다른 효과 메서드와 동일한 옵션.</li>
  </ul>
  <div class="example">
      <h4>사용자 정의 애니메이션 예제용 HTML</h4>
      <button id="btn-animate">Animate</button>
      <div id="animate-box" class="box" style="background-color: gold; position: relative; width: 100px; height: 100px;">Animate Me!</div>
      <div id="output-effect-animate" class="output"></div>
  </div>
   <pre><code class="language-javascript">$(function(){
      const $box = $('#animate-box');
      const $outputDiv = $('#output-effect-animate');

      $('#btn-animate').on('click', function(){
          $outputDiv.text('애니메이션 시작...');
          $box.animate({
              left: '+=100px', // 오른쪽으로 100px 이동
              opacity: 0.5,     // 투명도 50%
              height: '+=50px', // 높이 50px 증가
              width: '150px'   // 너비 150px로 변경
          }, 1500, 'swing', function(){ // 1.5초 동안 실행 후 콜백
              $outputDiv.text('애니메이션 완료!');
              // 원래 상태로 돌아가는 애니메이션 (체이닝)
              $(this).animate({
                  left: '-=100px',
                  opacity: 1,
                  height: '-=50px',
                  width: '100px'
              }, 800);
          });
      });
  });</code></pre>

  <h3 id="effects-queue">애니메이션 큐 및 제어 (.stop(), .delay(), .promise())</h3>
  <ul>
      <li><strong>애니메이션 큐 (Queue):</strong> jQuery 효과 및 애니메이션 메서드는 기본적으로 내부 큐(queue, 'fx' 큐)에 순서대로 추가되어 하나씩 실행됩니다.</li>
      <li><code>.stop([clearQueue], [jumpToEnd])</code>: 현재 실행 중인 애니메이션을 중지합니다.
          <ul>
              <li><code>clearQueue</code> (boolean, 기본값 <code>false</code>): <code>true</code>면 큐에 대기 중인 모든 애니메이션도 제거합니다.</li>
              <li><code>jumpToEnd</code> (boolean, 기본값 <code>false</code>): <code>true</code>면 현재 애니메이션을 즉시 완료 상태로 만듭니다.</li>
          </ul>
      </li>
      <li><code>.delay(duration)</code>: 애니메이션 큐에 지정된 시간(ms)만큼의 지연을 추가합니다. 다음 애니메이션 실행을 잠시 멈춥니다.</li>
       <li><code>.promise()</code>: 선택한 요소의 특정 큐(기본 'fx')에 있는 모든 애니메이션이 완료될 때 이행되는 Promise 객체를 반환합니다. 여러 애니메이션 완료 후 특정 작업을 수행할 때 유용합니다.</li>
  </ul>
   <div class="example">
      <h4>애니메이션 제어 예제용 HTML</h4>
      <button id="btn-animate-queue">Animate Queue</button>
      <button id="btn-stop-animate">Stop Animation</button>
      <div id="queue-box" class="box" style="background-color: violet; position: relative; width: 50px; height: 50px;">Queue</div>
      <div id="output-effect-queue" class="output"></div>
  </div>
  <pre><code class="language-javascript">$(function(){
      const $box = $('#queue-box');
      const $outputDiv = $('#output-effect-queue');

      $('#btn-animate-queue').on('click', function(){
          $outputDiv.text('큐 애니메이션 시작');
          $box.animate({ left: '+=100px' }, 1000) // 1초 이동
              .delay(500) // 0.5초 지연
              .animate({ top: '+=50px' }, 800)   // 0.8초 이동
              .fadeOut('slow')                 // 서서히 사라짐
              .promise().done(function(){       // 모든 애니메이션 완료 후 실행
                  $outputDiv.text('큐 애니메이션 완료!');
                  // 다시 보이게 하고 위치 초기화 (예시)
                  $(this).css({left: 0, top: 0, display: 'inline-block', opacity: 1});
              });
      });

      $('#btn-stop-animate').on('click', function(){
          $box.stop(true, false); // 큐 비우고 현재 애니메이션 즉시 중지
          // $box.stop(true, true); // 큐 비우고 현재 애니메이션 완료 상태로 이동
          $outputDiv.text('애니메이션 중지됨 (stop(true, false))');
      });
  });</code></pre>
</section>

<section id="ajax">
  <h2>AJAX (Asynchronous JavaScript and XML)</h2>

  <h3 id="ajax-intro">jQuery AJAX 소개</h3>
  <p>AJAX는 웹 페이지 전체를 새로고침하지 않고도 백그라운드에서 서버와 데이터를 교환하여 동적으로 페이지 일부를 업데이트하는 기술입니다. jQuery는 복잡한 JavaScript AJAX 구현을 매우 간편하게 만들어주는 다양한 메서드를 제공합니다.</p>
  <p class="note">AJAX는 원래 XML 데이터를 사용하는 기술이었지만, 현재는 주로 JSON 형식을 사용하여 데이터를 주고받습니다.</p>

  <h3 id="ajax-load">.load() 메서드</h3>
  <p><code>.load(url, [data], [callback])</code> 메서드는 서버에서 HTML 데이터를 가져와 선택한 요소의 내용(<code>innerHTML</code>)으로 로드하는 가장 간단한 방법입니다.</p>
  <ul>
      <li><code>url</code>: 데이터를 가져올 서버 URL. URL 뒤에 CSS 선택자를 추가하여 (예: <code>'content.html #main'</code>) 특정 부분만 가져올 수도 있습니다.</li>
      <li><code>data</code> (선택 사항): 서버로 보낼 데이터 객체 또는 문자열. 전달되면 POST 방식으로 요청합니다.</li>
      <li><code>callback(response, status, xhr)</code> (선택 사항): 로드 완료 후 실행될 함수. <code>status</code>는 'success', 'error' 등.</li>
  </ul>
   <div class="example">
      <h4>.load() 예제용 HTML</h4>
      <button id="btn-load-content">외부 콘텐츠 로드</button>
      <div id="load-result" class="box" style="min-height: 50px; background-color: #f9f9f9;"></div>
      <div id="output-ajax-load" class="output"></div>
      </div>
  <pre><code class="language-javascript">$(function(){
      $('#btn-load-content').on('click', function(){
          $('#output-ajax-load').text('콘텐츠 로딩 중...');
          // dummy.html 파일의 #loaded-content 부분만 가져와서 #load-result에 넣음
          $('#load-result').load('dummy.html #loaded-content', function(response, status, xhr){
              if (status === "success") {
                  $('#output-ajax-load').text('콘텐츠 로드 성공!');
              } else if (status === "error") {
                   $('#output-ajax-load').text(`콘텐츠 로드 실패: ${xhr.status} ${xhr.statusText}`);
              }
          });
          // $('#load-result').load('dummy.html'); // 파일 전체 로드
      });
  });</code></pre>
   <p class="warning"><code>.load()</code>는 JavaScript 코드를 실행할 수 있어 보안 위험이 있을 수 있으며, 주로 같은 도메인 내의 HTML 조각을 로드하는 데 사용됩니다.</p>

  <h3 id="ajax-get-post">$.get(), $.post() 메서드</h3>
  <p><code>$.get()</code>과 <code>$.post()</code>는 각각 HTTP GET 방식과 POST 방식으로 서버에 요청을 보내고 데이터를 받는 간편한 메서드입니다.</p>
  <ul>
      <li><code>$.get(url, [data], [callback], [dataType])</code></li>
      <li><code>$.post(url, [data], [callback], [dataType])</code></li>
  </ul>
  <ul>
      <li><code>url</code>: 요청 URL.</li>
      <li><code>data</code> (선택 사항): 서버로 보낼 데이터 (객체 또는 문자열). GET 요청 시 URL 쿼리 스트링으로 변환됨.</li>
      <li><code>callback(data, status, xhr)</code> (선택 사항): 요청 성공 시 실행될 함수. <code>data</code>는 서버 응답 데이터.</li>
      <li><code>dataType</code> (선택 사항): 예상되는 서버 응답 데이터 타입 ('xml', 'html', 'script', 'json', 'text'). 지정하지 않으면 jQuery가 추측.</li>
  </ul>
  <pre><code class="language-javascript">$(function(){
      // $.get() 예시 (JSONPlaceholder 사용)
      $('#btn-ajax-get').on('click', function(){
           $('#output-ajax-gp').text('GET 요청 중...');
           $.get('https://jsonplaceholder.typicode.com/posts/1', function(data, status){
               if (status === 'success') {
                   $('#output-ajax-gp').html(`GET 성공!<br>제목: ${data.title}`);
                   console.log(data);
               } else {
                   $('#output-ajax-gp').text('GET 실패: ' + status);
               }
           }, 'json'); // 응답 타입을 JSON으로 기대
      });

      // $.post() 예시 (JSONPlaceholder 사용)
      $('#btn-ajax-post').on('click', function(){
           $('#output-ajax-gp').text('POST 요청 중...');
           const postData = { title: 'jQuery POST Test', body: 'This is the body.', userId: 1 };
           $.post('https://jsonplaceholder.typicode.com/posts', postData, function(data, status){
                if (status === 'success') { // 보통 생성 성공 시 201 status
                   $('#output-ajax-gp').html(`POST 성공!<br>생성된 ID: ${data.id}<br>제목: ${data.title}`);
                   console.log(data);
               } else {
                   $('#output-ajax-gp').text('POST 실패: ' + status);
               }
           }, 'json');
      });
  });</code></pre>
   <div class="example">
      <h4>$.get(), $.post() 예제용 HTML</h4>
      <button id="btn-ajax-get">GET 요청 (데이터 가져오기)</button>
      <button id="btn-ajax-post">POST 요청 (데이터 생성)</button>
      <div id="output-ajax-gp" class="output"></div>
  </div>

  <h3 id="ajax-getjson">$.getJSON() 메서드</h3>
  <p><code>$.getJSON(url, [data], [callback])</code>은 <code>$.get()</code>과 유사하지만, 서버 응답 데이터 타입을 항상 JSON으로 기대하고 자동으로 파싱해주는 간편 메서드입니다.</p>
  <pre><code class="language-javascript">$(function(){
      $('#btn-ajax-getjson').on('click', function(){
           $('#output-ajax-json').text('getJSON 요청 중...');
            $.getJSON('https://jsonplaceholder.typicode.com/users/2', function(data){
                 $('#output-ajax-json').html(`getJSON 성공!<br>사용자 이름: ${data.name}<br>이메일: ${data.email}`);
                 console.log(data);
            })
            .fail(function(jqXHR, textStatus, errorThrown) { // 실패 시 처리 (.fail 사용 가능)
                 $('#output-ajax-json').text(`getJSON 실패: ${textStatus}, ${errorThrown}`);
            });
      });
  });</code></pre>
    <div class="example">
      <h4>$.getJSON() 예제용 HTML</h4>
      <button id="btn-ajax-getjson">Get JSON Data</button>
      <div id="output-ajax-json" class="output"></div>
  </div>

  <h3 id="ajax-core">$.ajax() 메서드 (핵심)</h3>
  <p><code>$.ajax(url, [settings])</code> 또는 <code>$.ajax([settings])</code>는 모든 종류의 AJAX 요청을 처리할 수 있는 가장 강력하고 유연한 핵심 메서드입니다. 다양한 설정을 객체(<code>settings</code>) 형태로 전달하여 요청을 세밀하게 제어할 수 있습니다.</p>
  <p>주요 설정 옵션:</p>
  <ul>
      <li><code>url</code>: 요청 URL.</li>
      <li><code>method</code> (또는 <code>type</code>): HTTP 요청 방식 ('GET', 'POST', 'PUT', 'DELETE' 등). 기본값 'GET'.</li>
      <li><code>data</code>: 서버로 보낼 데이터 (객체, 문자열, FormData 등).</li>
      <li><code>dataType</code>: 예상되는 서버 응답 타입 ('json', 'xml', 'html', 'text', 'script').</li>
      <li><code>contentType</code>: 서버로 데이터를 보낼 때의 컨텐츠 타입 (예: <code>'application/json'</code>, <code>'application/x-www-form-urlencoded'</code>). 기본값은 <code>'application/x-www-form-urlencoded; charset=UTF-8'</code>. JSON 전송 시 <code>'application/json'</code> 명시 필요.</li>
      <li><code>headers</code>: 요청 헤더를 설정하는 객체 (예: <code>{ 'Authorization': 'Bearer token' }</code>).</li>
      <li><code>success(data, textStatus, jqXHR)</code>: 요청 성공 시 실행될 콜백 함수.</li>
      <li><code>error(jqXHR, textStatus, errorThrown)</code>: 요청 실패 시 실행될 콜백 함수.</li>
      <li><code>complete(jqXHR, textStatus)</code>: 요청 완료 시 (성공/실패 무관) 실행될 콜백 함수.</li>
      <li><code>beforeSend(jqXHR, settings)</code>: 요청 보내기 직전에 실행될 함수. 요청 취소 가능 (<code>return false;</code>).</li>
      <li><code>timeout</code>: 요청 제한 시간 (밀리초).</li>
      <li><code>async</code>: 비동기 요청 여부 (boolean). 기본값 <code>true</code>. (<code>false</code>는 동기 요청으로 브라우저를 멈추게 하므로 절대 사용 금지).</li>
  </ul>
   <pre><code class="language-javascript">$(function(){
       $('#btn-ajax-core').on('click', function(){
            $('#output-ajax-core').text('$.ajax 요청 중...');
            $.ajax({
                url: 'https://jsonplaceholder.typicode.com/todos/1',
                method: 'GET', // 기본값이지만 명시
                dataType: 'json', // 예상 응답 타입
                timeout: 5000, // 5초 제한 시간
                beforeSend: function() {
                    console.log('요청 보내기 전...');
                    // 로딩 인디케이터 표시 등
                },
                success: function(data, status, xhr) {
                    // 요청 성공
                    $('#output-ajax-core').html(`$.ajax 성공! Status: ${status}<br>
                                                Todo 제목: ${data.title}<br>
                                                완료 여부: ${data.completed}`);
                    console.log('성공 응답 데이터:', data);
                },
                error: function(xhr, status, error) {
                    // 요청 실패 (네트워크 오류, 서버 오류, 타임아웃 등)
                     $('#output-ajax-core').text(`$.ajax 실패! Status: ${status}, Error: ${error}`);
                     console.error('AJAX Error:', xhr, status, error);
                },
                complete: function(xhr, status) {
                    // 요청 완료 (성공/실패 무관)
                    console.log(`요청 완료. Status: ${status}`);
                    // 로딩 인디케이터 숨김 등
                }
            });
       });

        // POST 요청 예시 ($.ajax 사용)
        /*
        const newTodo = { userId: 1, title: 'Learn jQuery AJAX', completed: false };
        $.ajax({
            url: 'https://jsonplaceholder.typicode.com/todos',
            method: 'POST',
            contentType: 'application/json', // JSON으로 보낼 때 중요!
            data: JSON.stringify(newTodo), // 데이터를 JSON 문자열로 변환
            dataType: 'json',
            success: function(data){ console.log("POST 성공:", data); },
            error: function(err){ console.error("POST 실패:", err); }
        });
        */
   });</code></pre>
   <div class="example">
      <h4>$.ajax() 예제용 HTML</h4>
      <button id="btn-ajax-core">$.ajax() 요청</button>
      <div id="output-ajax-core" class="output"></div>
  </div>

  <h3 id="ajax-helpers">AJAX 헬퍼 함수 및 전역 이벤트</h3>
  <ul>
      <li><code>$.param(object)</code>: 객체를 URL 쿼리 스트링 형식의 문자열로 변환합니다.</li>
      <li><strong>전역 AJAX 이벤트 핸들러:</strong> 특정 요소(주로 <code>document</code>)에 등록하여 페이지 내의 모든 AJAX 요청에 대해 특정 시점(시작, 성공, 실패, 완료 등)에 공통 작업을 수행할 수 있습니다. (예: 로딩 인디케이터 표시/숨김)
          <ul>
              <li><code>.ajaxStart(handler)</code> / <code>.ajaxStop(handler)</code></li>
              <li><code>.ajaxSend(handler)</code> / <code>.ajaxComplete(handler)</code></li>
              <li><code>.ajaxSuccess(handler)</code> / <code>.ajaxError(handler)</code></li>
          </ul>
           <pre><code class="language-javascript">// 전역 AJAX 이벤트 예시 (로딩 인디케이터)
$(document).ajaxStart(function() {
  $("#loading-indicator").show(); // AJAX 요청 시작 시 로딩 표시 보이기
}).ajaxStop(function() {
  $("#loading-indicator").hide(); // 모든 AJAX 요청 완료 시 로딩 표시 숨기기
});</code></pre>
           <div id="loading-indicator" style="display: none; color: blue;">Loading...</div>
      </li>
  </ul>
</section>


<section id="utilities">
  <h2>유틸리티 메서드 (Utilities)</h2>
  <p>jQuery는 DOM 조작 외에도 배열/객체 처리, 타입 검사 등 다양한 유틸리티 함수를 <code>$</code> (또는 <code>jQuery</code>) 객체 자체의 메서드로 제공합니다.</p>
  <ul>
      <li><code>$.each(collection, callback(indexOrKey, value))</code>: 배열이나 객체를 순회하며 각 요소/속성에 대해 콜백 함수를 실행합니다. 콜백 함수 내에서 <code>return false;</code>는 반복을 중단시킵니다.</li>
      <li><code>$.map(collection, callback(value, indexOrKey))</code>: 배열이나 객체의 각 요소/속성을 콜백 함수로 처리하여 그 결과를 담은 새로운 배열을 반환합니다.</li>
      <li><code>$.grep(array, callback(element, index), [invert])</code>: 배열에서 콜백 함수가 true를 반환하는 요소들만 필터링하여 새로운 배열을 반환합니다. <code>invert</code>가 true면 false를 반환하는 요소만 남깁니다.</li>
      <li><code>$.trim(string)</code>: 문자열 앞뒤의 공백을 제거합니다. (JavaScript `string.trim()`과 유사)</li>
      <li><code>$.type(value)</code>: 값의 내부 JavaScript 타입을 문자열로 반환합니다 ('string', 'number', 'boolean', 'function', 'array', 'object', 'null', 'undefined', 'date', 'regexp'). <code>typeof</code>보다 정확합니다.</li>
      <li><code>$.isNumeric(value)</code>: 값이 숫자인지(또는 숫자로 변환 가능한 문자열인지) 확인합니다.</li>
      <li><code>$.isFunction(value)</code>: 값이 함수인지 확인합니다.</li>
      <li><code>$.isArray(value)</code>: 값이 배열인지 확인합니다.</li>
      <li><code>$.isEmptyObject(obj)</code>: 객체가 비어있는지 (자체 속성이 없는지) 확인합니다.</li>
      <li><code>$.extend([deep], target, object1, [objectN])</code>: 하나 이상의 객체를 첫 번째 객체(<code>target</code>)로 병합(merge)합니다. <code>deep</code> 인수가 true면 깊은 복사(중첩 객체도 복사)를 수행합니다. 객체 복사나 플러그인 작성 시 유용합니다.</li>
  </ul>
  <pre><code class="language-javascript">$(function(){
      // $.each 예시
      const arr = ["a", "b", "c"];
      $.each(arr, function(index, value){
          console.log(`Index ${index}: ${value}`);
      });
      const obj = { name: "Test", value: 123 };
      $.each(obj, function(key, value){
          console.log(`Key ${key}: ${value}`);
      });

      // $.map 예시
      const nums = [1, 2, 3];
      const doubledNums = $.map(nums, function(val, idx){
          return val * 2;
      });
      console.log(doubledNums); // [ 2, 4, 6 ]

      // $.grep 예시
      const mixedArr = [1, "two", 3, "four", 5];
      const numbersOnly = $.grep(mixedArr, function(elem, idx){
          return $.isNumeric(elem); // 숫자인 요소만 필터링
      });
      console.log(numbersOnly); // [ 1, 3, 5 ]

      // $.trim 예시
      console.log($.trim("   hello world   ")); // "hello world"

      // $.type 예시
      console.log($.type([])); // "array"
      console.log($.type(null)); // "null"
      console.log($.type(/regex/)); // "regexp"

      // $.extend 예시 (얕은 복사)
      const defaults = { a: 1, b: 2 };
      const options = { b: 3, c: 4 };
      const settings = $.extend({}, defaults, options); // 새 객체 {}에 병합
      console.log(settings); // { a: 1, b: 3, c: 4 }
      console.log(defaults); // { a: 1, b: 2 } (원본은 변경 안 됨)
  });</code></pre>
</section>

<section id="noconflict">
  <h2>NoConflict 모드</h2>
  <p>다른 JavaScript 라이브러리(예: Prototype, MooTools)도 <code>$</code> 기호를 사용하는 경우가 있습니다. 이럴 경우 충돌이 발생할 수 있는데, jQuery는 <code>$.noConflict()</code> 메서드를 제공하여 <code>$</code> 변수의 제어권을 포기하고 다른 라이브러리가 사용하도록 할 수 있습니다.</p>
  <pre><code class="language-javascript">// jQuery의 $ 별칭 사용 권한을 포기하고, jQuery 객체 자체를 반환받음
var jq = $.noConflict();

// 이제 $는 다른 라이브러리가 사용 (만약 있다면)
// jQuery 기능은 'jq' 변수를 사용해야 함
jq(document).ready(function(){
  jq("button").click(function(){
    jq("p").text("jQuery is still working using 'jq'!");
  });
});

// 또는 IIFE(즉시 실행 함수 표현식)를 사용하여 $를 안전하게 사용
(function($) {
  // 이 함수 내부에서는 $가 jQuery를 가리킴
  $(document).ready(function() {
    $("#myElement").hide();
  });
})(jQuery); // jQuery 객체를 인수로 전달
</code></pre>
</section>

<section id="conclusion">
  <h2>마무리</h2>
  <p>이것으로 jQuery 종합 강좌를 마칩니다. 이 강좌를 통해 jQuery의 기본 개념부터 선택자, DOM 조작, 이벤트 처리, 효과 및 애니메이션, AJAX 통신, 그리고 몇 가지 유용한 유틸리티까지 폭넓게 살펴보았습니다.</p>
  <p>jQuery는 한때 웹 개발의 필수 라이브러리였으며, 간결한 문법과 강력한 기능으로 많은 개발자들에게 사랑받았습니다. 비록 현대에는 바닐라 JavaScript와 프레임워크/라이브러리가 많은 부분을 대체하고 있지만, 여전히 jQuery의 기본 원리를 이해하는 것은 웹 개발의 역사를 이해하고 기존 코드를 유지보수하는 데 도움이 될 수 있습니다.</p>
  <h4>주요 학습 내용 요약:</h4>
  <ul>
      <li><strong>선택자:</strong> CSS 선택자를 이용한 강력한 요소 선택.</li>
      <li><strong>DOM 조작:</strong> 콘텐츠, 속성, 클래스, 스타일, 요소 추가/제거 등을 쉽게 처리.</li>
      <li><strong>이벤트 처리:</strong> <code>.on()</code>을 이용한 유연한 이벤트 바인딩, 위임, 제어.</li>
      <li><strong>효과 및 애니메이션:</strong> 간단한 코드로 시각적 효과 구현.</li>
      <li><strong>AJAX:</strong> 서버와의 비동기 통신 간소화.</li>
  </ul>
  <h4>다음 단계는?</h4>
  <ul>
      <li>바닐라 JavaScript 복습 및 심화: jQuery가 내부적으로 어떻게 동작하는지 이해하기 위해 순수 JavaScript의 DOM API, 이벤트 처리, Promise 등을 다시 한번 깊이 있게 학습하는 것이 좋습니다.</li>
      <li>현대 프론트엔드 프레임워크 학습: React, Vue, Angular 등 현대적인 프레임워크/라이브러리를 배우는 것을 추천합니다. 컴포넌트 기반 개발, 상태 관리 등 최신 웹 개발 패러다임을 익힐 수 있습니다.</li>
      <li>실습 프로젝트: 배운 jQuery 지식을 활용하여 간단한 인터랙티브 웹 페이지나 기능을 직접 만들어 보세요.</li>
  </ul>
  <p>jQuery는 웹 개발의 특정 문제들을 매우 빠르고 쉽게 해결해 줄 수 있는 유용한 도구입니다. 필요에 따라 적절히 활용하시길 바랍니다. 즐거운 코딩 여정이 되기를 응원합니다!</p>
</section>

<br><br>
<hr>

<br><br>

<script src="../js/script.js?ver=1"></script>

</body>
</html>