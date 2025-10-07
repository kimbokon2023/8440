<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> JavaScript 강좌  </title>
  <link rel="stylesheet" href="../css/lessons_style.css?ver=1">
  <style>
    /* 기본 스타일 */
    body { font-family: sans-serif; line-height: 1.6; }
    .toc { border: 1px solid #ccc; padding: 15px; margin-bottom: 30px; background-color: #f9f9f9; }
    .toc h2 { margin-top: 0; }
    h1, h2 { border-bottom: 2px solid #eee; padding-bottom: 5px; }
    h2 { margin-top: 40px; }
    h3 { margin-top: 30px; border-bottom: 1px dashed #ccc; padding-bottom: 3px;}
    pre { background-color: #f4f4f4; padding: 15px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; font-size: 0.9em; }
    code.language-javascript { font-family: Consolas, monospace; color: #333; }
    code.language-html { font-family: Consolas, monospace; color: #333; }
    .example { border: 1px solid #eee; padding: 15px; margin-top: 10px; margin-bottom: 20px; background-color: #fff; border-radius: 4px;}
    .example h4 { margin-top: 0; font-size: 1.1em; color: #555; }
    .note { background-color: #e7f3fe; border-left: 4px solid #2196F3; padding: 10px 15px; margin: 15px 0; font-size: 0.95em;}
    .warning { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 10px 15px; margin: 15px 0; font-size: 0.95em;}

    /* TOC 활성 링크 스타일 */
    .toc a.active {
      color: #d9534f;
      font-weight: bold;
    }
  </style>
</head>
<body>

<h1> JavaScript 강좌 </h1>
<p>이 페이지는 웹 페이지를 동적이고 상호작용적으로 만드는 핵심 언어인 JavaScript의 기초를 다룹니다. 첫 번째 파트에서는 기본 개념부터 변수, 데이터 타입, 연산자까지 학습합니다.</p>
<p class="note"><strong>학습 목표:</strong> JavaScript가 무엇인지 이해하고, 기본적인 문법과 변수 선언, 다양한 데이터 타입을 활용하며 기본적인 연산을 수행할 수 있게 됩니다.</p>

<div class="toc">
  <h2>📖 목차 </h2>
  <ul>
    <li><a href="#intro">JavaScript 소개</a></li>
    <li><a href="#setup">JavaScript 설정 방법</a></li>
    <li><a href="#syntax">기본 문법 및 주석</a></li>
    <li><a href="#variables">변수 (Variables) - var, let, const</a></li>
    <li><a href="#datatypes">데이터 타입 (Data Types)</a></li>
    <li><a href="#type-conversion">타입 변환 (Type Conversion)</a></li>
    <li><a href="#operators">연산자 (Operators)</a></li>

    <li><a href="#control-flow">제어 흐름 (Control Flow)</a></li>
    <li><a href="#conditionals">조건문 (Conditional Statements)</a>
        <ul>
            <li><a href="#if-else">if, else if, else</a></li>
            <li><a href="#switch">switch</a></li>
        </ul>
    </li>
    <li><a href="#loops">반복문 (Loops)</a>
        <ul>
            <li><a href="#for">for</a></li>
            <li><a href="#while">while, do...while</a></li>
            <li><a href="#break-continue">break, continue</a></li>
            <li><a href="#for-in">for...in (객체 속성 반복)</a></li>
            <li><a href="#for-of">for...of (이터러블 반복)</a></li>
        </ul>
    </li>
    <li><a href="#functions-basic">함수 기초 (Functions Basics)</a></li>
    <li><a href="#function-definition">함수 정의 방법</a></li>
    <li><a href="#function-params-args">매개변수와 인수</a></li>
    <li><a href="#function-return">반환 값 (Return Values)</a></li>
    <li><a href="#function-scope-intro">함수 스코프 기초</a></li>
    <li><a href="#objects-deep">객체 심화 (Objects Deep Dive)</a>
        <ul>
            <li><a href="#object-basics">객체 기본 및 속성 접근</a></li>
            <li><a href="#object-es6-features">객체 ES6+ 기능</a></li>
            <li><a href="#object-methods-this">메서드와 'this'</a></li>
            <li><a href="#object-built-in-methods">내장 객체 메서드</a></li>
        </ul>
    </li>
    <li><a href="#arrays-deep">배열 심화 (Arrays Deep Dive)</a>
        <ul>
            <li><a href="#array-basics">배열 기본 및 조작 메서드</a></li>
            <li><a href="#array-search-methods">배열 검색 메서드</a></li>
            <li><a href="#array-iteration-methods">배열 고차 함수 (반복 메서드)</a></li>
            <li><a href="#array-sort">배열 정렬 (sort)</a></li>
            <li><a href="#array-destructuring-spread">배열 구조 분해 할당 및 전개 구문</a></li>
        </ul>
    </li>
     <li><a href="#functions-deep">함수 심화 (Functions Deep Dive)</a>
        <ul>
            <li><a href="#arrow-functions">화살표 함수 (Arrow Functions)</a></li>
            <li><a href="#this-keyword">`this` 키워드</a></li>
            <li><a href="#closures">클로저 (Closures)</a></li>
        </ul>
    </li>
	<li><a href="#dom-intro">DOM 소개</a></li>
	<li><a href="#dom-selecting">DOM 요소 선택</a></li>
	<li><a href="#dom-traversing">DOM 탐색 (Traversing)</a></li>
	<li><a href="#dom-modifying">DOM 요소 수정</a>
		<ul>
			<li><a href="#dom-modifying-content">콘텐츠 변경</a></li>
			<li><a href="#dom-modifying-attributes">속성 변경</a></li>
			<li><a href="#dom-modifying-styles">스타일 변경</a></li>
		</ul>
	</li>
	<li><a href="#dom-creating-adding-removing">DOM 요소 생성, 추가, 제거</a></li>
	<li><a href="#events-intro">이벤트 소개</a></li>
	<li><a href="#event-handling-models">이벤트 처리 모델</a></li>
	<li><a href="#event-object">이벤트 객체 (Event Object)</a></li>
	<li><a href="#event-flow">이벤트 흐름 (Event Flow)</a></li>
	<li><a href="#event-delegation">이벤트 위임 (Event Delegation)</a></li>
	<li><a href="#common-event-types">주요 이벤트 타입</a></li>	  
    <li><a href="#async-intro">비동기 JavaScript 소개</a></li>
    <li><a href="#callbacks">콜백 함수 (Callbacks)</a></li>
    <li><a href="#promises">프로미스 (Promises)</a></li>
    <li><a href="#async-await">async / await</a></li>
    <li><a href="#networking-fetch">네트워크 요청 (Fetch API)</a></li>
    <li><a href="#json">JSON (JavaScript Object Notation)</a></li>
    <li><a href="#es6-modules">ES6 모듈 (Modules)</a></li>
    <li><a href="#error-handling">에러 처리 (Error Handling)</a></li>
    <li><a href="#regex-intro">정규 표현식 기초 (Regular Expressions Intro)</a></li>
    <li><a href="#next-steps">마무리 및 다음 단계</a></li>  	
  </ul>    
</div>

<section id="intro">
  <h2>JavaScript 소개</h2>
  <p>JavaScript(JS)는 웹 페이지에 생동감을 불어넣는 데 사용되는 <strong>스크립트 언어</strong> 또는 <strong>프로그래밍 언어</strong>입니다. 원래 웹 브라우저 내에서 실행되어 사용자 인터페이스를 동적으로 만들고 사용자와 상호작용하기 위해 개발되었지만, 현재는 Node.js와 같은 환경을 통해 서버 측 개발 등 다양한 분야에서 사용되고 있습니다.</p>
  <ul>
    <li><strong>동적 콘텐츠 변경:</strong> HTML 내용이나 CSS 스타일을 실시간으로 변경할 수 있습니다.</li>
    <li><strong>사용자 상호작용:</strong> 버튼 클릭, 마우스 이동, 키보드 입력 등 사용자 행동에 반응하여 특정 동작을 수행하게 할 수 있습니다.</li>
    <li><strong>비동기 통신:</strong> 서버와 데이터를 주고받아 페이지 전체를 새로고침하지 않고도 필요한 부분만 업데이트할 수 있습니다 (AJAX).</li>
    <li><strong>다양한 기능 구현:</strong> 애니메이션, 게임, 데이터 시각화 등 복잡한 기능 구현이 가능합니다.</li>
  </ul>
  <p>HTML이 구조를, CSS가 스타일을 담당한다면, JavaScript는 <strong>동작(Behavior)</strong>을 담당한다고 생각할 수 있습니다.</p>
</section>

<section id="setup">
  <h2>JavaScript 설정 방법</h2>
  <p>JavaScript 코드를 HTML 문서에 포함시키는 방법은 크게 세 가지입니다.</p>
  <ol>
    <li>
      <strong>내부 스크립트 (Internal Script):</strong> HTML 문서의 <code>&lt;head&gt;</code> 또는 <code>&lt;body&gt;</code> 섹션 안에 <code>&lt;script&gt;</code> 태그를 사용하여 JavaScript 코드를 직접 작성합니다. 간단한 코드나 해당 페이지에만 필요한 코드를 작성할 때 사용합니다.
      <pre><code class="language-html">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;title&gt;내부 스크립트&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
  &lt;h1 id="greeting"&gt;안녕하세요!&lt;/h1&gt;

  &lt;script&gt;
    // JavaScript 코드 작성
    console.log("페이지 로드 완료!");
    document.getElementById("greeting").textContent = "반갑습니다!"; // h1 내용 변경
  &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
      <p class="note"><code>&lt;body&gt;</code> 태그가 닫히기 직전에 <code>&lt;script&gt;</code>를 위치시키는 것이 일반적입니다. 이는 HTML 요소들이 먼저 로드된 후 스크립트가 실행되도록 하여, 스크립트가 조작하려는 HTML 요소를 찾지 못하는 오류를 방지하기 위함입니다.</p>
    </li>
    <li>
      <strong>외부 스크립트 (External Script):</strong> 별도의 <code>.js</code> 파일을 만들고, HTML 문서에서 <code>&lt;script&gt;</code> 태그의 <code>src</code> 속성을 사용하여 연결합니다. 코드가 길거나 여러 페이지에서 재사용될 때 사용하며, 코드 관리와 캐싱 효율성 측면에서 <strong>가장 권장되는 방법</strong>입니다.
      <pre><code class="language-html">&lt;!-- HTML 파일 (&lt;body&gt; 끝 부분 추천) --&gt;
&lt;script src="myScript.js"&gt;&lt;/script&gt;</code></pre>
      <pre><code class="language-javascript">// myScript.js 파일 내용
console.log("외부 스크립트 실행!");

function showMessage() {
  alert("버튼이 클릭되었습니다!");
}

// HTML에 있는 ID가 'myButton'인 버튼에 클릭 이벤트 연결
const myButton = document.getElementById("myButton");
if (myButton) {
  myButton.addEventListener("click", showMessage);
}</code></pre>
      <p><code>&lt;script&gt;</code> 태그에 <code>defer</code> 나 <code>async</code> 속성을 추가하여 스크립트 로딩 및 실행 시점을 제어할 수도 있습니다. (추후 고급 파트에서 다룰 수 있습니다)</p>
    </li>
    <li>
      <strong>인라인 스크립트 (Inline Script - 비권장):</strong> HTML 요소의 이벤트 속성(예: <code>onclick</code>, <code>onmouseover</code>)에 직접 JavaScript 코드를 작성하는 방식입니다. 코드 가독성과 유지보수성을 해치므로 거의 사용되지 않습니다.
      <pre><code class="language-html">&lt;!-- 비권장 방식 --&gt;
&lt;button onclick="alert('버튼 클릭됨!');"&gt;클릭하세요&lt;/button&gt;</code></pre>
    </li>
  </ol>
</section>

<section id="syntax">
  <h2>기본 문법 및 주석</h2>
  <p>JavaScript 코드는 일련의 <strong>문장(Statement)</strong>들로 구성됩니다.</p>
  <ul>
    <li><strong>문장 구분:</strong> 각 문장은 세미콜론(<code>;</code>)으로 끝내는 것이 좋습니다. JavaScript는 세미콜론 자동 삽입(ASI) 기능이 있지만, 예상치 못한 오류를 방지하기 위해 명시적으로 작성하는 것이 안전합니다.</li>
    <li><strong>대소문자 구분:</strong> JavaScript는 대소문자를 엄격하게 구분합니다. <code>myVariable</code>과 <code>myvariable</code>은 다른 변수입니다.</li>
    <li><strong>공백과 줄바꿈:</strong> 코드 가독성을 위해 적절한 공백과 들여쓰기를 사용합니다. 대부분의 공백과 줄바꿈은 코드 실행에 영향을 주지 않습니다.</li>
    <li><strong>주석 (Comments):</strong> 코드에 대한 설명을 추가하거나 특정 코드를 임시로 비활성화할 때 사용합니다. 주석은 실행되지 않습니다.
        <ul>
            <li>한 줄 주석: <code>//</code> 뒤의 모든 내용은 주석 처리됩니다.</li>
            <li>여러 줄 주석: <code>/*</code> 와 <code>*/</code> 사이의 모든 내용은 주석 처리됩니다.</li>
        </ul>
    </li>
  </ul>
  <pre><code class="language-javascript">// 이것은 한 줄 주석입니다.
let message = "Hello, JavaScript!"; // 문장 끝에 세미콜론 사용 권장
console.log(message);

/*
이것은
여러 줄에 걸친
주석입니다.
*/

// 대소문자 구분 예시
let name = "Alice";
// let Name = "Bob"; // 'name'과 'Name'은 다른 변수

// 가독성을 위한 공백과 들여쓰기
function greet(personName) {
  if (personName) {
    console.log("Hello, " + personName + "!");
  } else {
    console.log("Hello there!");
  }
}</code></pre>
</section>

<section id="variables">
  <h2>변수 (Variables) - var, let, const</h2>
  <p>변수는 데이터를 저장하고 참조하기 위해 사용하는 메모리 공간의 이름입니다. JavaScript에서 변수를 선언하는 키워드는 <code>var</code>, <code>let</code>, <code>const</code> 세 가지가 있습니다.</p>

  <h3><code>var</code> (구 방식, 사용 지양)</h3>
  <ul>
    <li>ES5까지 사용되던 변수 선언 방식입니다.</li>
    <li><strong>함수 스코프(Function Scope):</strong> 함수 내에서 선언된 변수는 함수 전체에서 유효합니다. 블록 스코프(<code>{}</code>)를 따르지 않습니다.</li>
    <li><strong>변수 호이스팅(Hoisting):</strong> 변수 선언이 스코프 최상단으로 끌어올려지는 것처럼 동작합니다. 선언 전에 사용해도 오류가 나지 않지만, 값은 <code>undefined</code>입니다.</li>
    <li><strong>재선언 가능:</strong> 같은 이름으로 변수를 다시 선언해도 오류가 발생하지 않습니다.</li>
  </ul>
  <pre><code class="language-javascript">function testVar() {
  if (true) {
    var x = 10;
  }
  console.log(x); // 출력: 10 (if 블록 밖에서도 접근 가능)

  console.log(y); // 출력: undefined (호이스팅)
  var y = 20;
  console.log(y); // 출력: 20

  var z = 30;
  var z = 40; // 재선언 가능
  console.log(z); // 출력: 40
}
testVar();</code></pre>
  <p class="warning"><code>var</code>는 함수 스코프와 호이스팅 문제로 인해 예기치 않은 동작을 유발할 수 있으므로, 현대 JavaScript에서는 <strong><code>let</code>과 <code>const</code> 사용이 권장</strong>됩니다.</p>

  <h3><code>let</code> (ES6+)</h3>
  <ul>
    <li><strong>블록 스코프(Block Scope):</strong> 변수가 선언된 블록(<code>{}</code>), 구문 또는 표현식 내에서만 유효합니다.</li>
    <li><strong>호이스팅 발생 O, TDZ(Temporal Dead Zone) 존재:</strong> 선언이 스코프 최상단으로 끌어올려지지만, 변수 선언 코드에 도달하기 전까지는 접근할 수 없는 '일시적 사각지대(TDZ)'에 놓입니다. 선언 전에 접근하면 참조 오류(ReferenceError)가 발생합니다.</li>
    <li><strong>재선언 불가:</strong> 같은 스코프 내에서 같은 이름으로 변수를 다시 선언할 수 없습니다.</li>
    <li><strong>값 재할당 가능:</strong> 선언 후 다른 값을 할당할 수 있습니다.</li>
  </ul>
  <pre><code class="language-javascript">function testLet() {
  if (true) {
    let a = 100;
    console.log(a); // 출력: 100
  }
  // console.log(a); // 오류: ReferenceError: a is not defined (블록 스코프)

  // console.log(b); // 오류: ReferenceError: Cannot access 'b' before initialization (TDZ)
  let b = 200;
  console.log(b); // 출력: 200

  let c = 300;
  // let c = 400; // 오류: SyntaxError: Identifier 'c' has already been declared

  c = 500; // 값 재할당 가능
  console.log(c); // 출력: 500
}
testLet();</code></pre>

  <h3><code>const</code> (ES6+)</h3>
  <ul>
    <li><code>let</code>과 마찬가지로 <strong>블록 스코프</strong>를 가지며, <strong>TDZ</strong>가 존재하고 <strong>재선언이 불가능</strong>합니다.</li>
    <li><strong>값 재할당 불가 (상수):</strong> 선언 시 반드시 값을 할당해야 하며, 한 번 할당된 값을 변경할 수 없습니다. (단, 객체나 배열의 경우 내부 속성이나 요소는 변경 가능)</li>
    <li>주로 값이 변경되지 않을 상수나, 객체/배열 참조를 저장할 때 사용됩니다.</li>
  </ul>
  <pre><code class="language-javascript">function testConst() {
  const PI = 3.14159;
  console.log(PI); // 출력: 3.14159

  // PI = 3.14; // 오류: TypeError: Assignment to constant variable.

  // const G; // 오류: SyntaxError: Missing initializer in const declaration

  const person = { name: "Alice", age: 30 };
  console.log(person); // 출력: { name: 'Alice', age: 30 }

  // 객체의 속성은 변경 가능
  person.age = 31;
  console.log(person); // 출력: { name: 'Alice', age: 31 }

  // 객체 자체를 다른 객체로 재할당하는 것은 불가능
  // person = { name: "Bob" }; // 오류: TypeError: Assignment to constant variable.

  const colors = ["red", "green", "blue"];
  colors.push("yellow"); // 배열 요소 추가 가능
  console.log(colors); // 출력: [ 'red', 'green', 'blue', 'yellow' ]
  // colors = ["orange"]; // 오류: TypeError: Assignment to constant variable.
}
testConst();</code></pre>
  <p class="note"><strong>변수 선언 가이드라인:</strong> 기본적으로 <code>const</code>를 사용하고, 값이 변경되어야 하는 경우에만 <code>let</code>을 사용하는 것이 좋습니다. <code>var</code>는 가급적 사용하지 않습니다.</p>
</section>

<section id="datatypes">
  <h2>데이터 타입 (Data Types)</h2>
  <p>JavaScript는 동적 타입(dynamically typed) 언어로, 변수를 선언할 때 타입을 명시하지 않으며 값이 할당될 때 타입이 결정됩니다. JavaScript의 데이터 타입은 크게 <strong>원시 타입(Primitive types)</strong>과 <strong>객체 타입(Object type)</strong>으로 나뉩니다.</p>

  <h3>원시 타입 (Primitive Types)</h3>
  <p>변경 불가능한 값(immutable)이며, 메모리에 값이 직접 저장됩니다.</p>
  <ul>
    <li><strong>String:</strong> 텍스트 데이터를 나타냅니다. 작은따옴표(<code>''</code>)나 큰따옴표(<code>""</code>)로 감싸서 표현합니다. ES6부터 백틱(<code>``</code>)을 사용한 템플릿 리터럴(Template Literals)도 지원합니다.
      <pre><code class="language-javascript">let singleQuote = 'Hello';
let doubleQuote = "World";
let templateLiteral = `Greeting: ${singleQuote}, ${doubleQuote}!`; // 표현식 삽입 가능
console.log(templateLiteral); // 출력: Greeting: Hello, World!</code></pre>
    </li>
    <li><strong>Number:</strong> 정수와 실수를 모두 포함하는 숫자 데이터 타입입니다. <code>Infinity</code>, <code>-Infinity</code>, <code>NaN</code>(Not a Number)과 같은 특수 숫자 값도 있습니다.
      <pre><code class="language-javascript">let integer = 100;
let float = 3.14;
let infinity = Infinity;
let nan = NaN; // 0 / 0 등의 연산 결과
console.log(typeof nan); // 출력: number</code></pre>
    </li>
     <li><strong>BigInt:</strong> <code>Number</code> 타입이 안전하게 표현할 수 있는 최대 정수(<code>Number.MAX_SAFE_INTEGER</code>)보다 큰 정수를 다루기 위한 타입입니다. 정수 리터럴 뒤에 <code>n</code>을 붙여 표현합니다.
      <pre><code class="language-javascript">const bigIntValue = 9007199254740991n + 10n;
console.log(bigIntValue); // 출력: 9007199254741001n
// console.log(bigIntValue + 10); // 오류: TypeError: Cannot mix BigInt and other types</code></pre>
    </li>
    <li><strong>Boolean:</strong> 논리적인 값 <code>true</code>와 <code>false</code>를 나타냅니다. 주로 조건문에서 사용됩니다.
      <pre><code class="language-javascript">let isTrue = true;
let isFalse = false;
console.log(5 > 3); // 출력: true</code></pre>
    </li>
    <li><strong>Undefined:</strong> 값이 할당되지 않은 변수의 기본값입니다. 변수를 선언만 하고 값을 할당하지 않으면 <code>undefined</code> 상태가 됩니다.
      <pre><code class="language-javascript">let unassignedValue;
console.log(unassignedValue); // 출력: undefined</code></pre>
    </li>
    <li><strong>Null:</strong> 값이 '없음' 또는 '비어있음'을 의도적으로 명시할 때 사용합니다. <code>undefined</code>와 달리 개발자가 명시적으로 할당하는 값입니다.
      <pre><code class="language-javascript">let emptyValue = null;
console.log(emptyValue); // 출력: null
console.log(typeof null); // 출력: object (JavaScript의 유명한 버그 중 하나)</code></pre>
    </li>
    <li><strong>Symbol:</strong> ES6에서 추가된 타입으로, 고유하고 변경 불가능한 식별자를 만들 때 사용합니다. 주로 객체 속성의 충돌을 방지하기 위해 사용됩니다.
      <pre><code class="language-javascript">const id1 = Symbol('id');
const id2 = Symbol('id');
console.log(id1 === id2); // 출력: false (설명이 같아도 고유함)</code></pre>
    </li>
  </ul>

  <h3>객체 타입 (Object Type)</h3>
  <p>데이터와 기능(메서드)의 복합적인 구조를 가질 수 있는 타입입니다. 원시 타입을 제외한 모든 값(배열, 함수, 정규표현식 등)은 객체 타입에 속합니다. 객체는 변경 가능한 값(mutable)이며, 메모리에 참조(주소)가 저장됩니다.</p>
  <ul>
    <li><strong>Object:</strong> 이름(key)과 값(value)으로 구성된 속성(property)들의 집합입니다. 중괄호<code>{}</code>를 사용하여 생성합니다.
      <pre><code class="language-javascript">let person = {
  name: "Alice", // 속성 (key: "name", value: "Alice")
  age: 30,       // 속성 (key: "age", value: 30)
  greet: function() { // 메서드 (기능)
    console.log("Hello, my name is " + this.name);
  }
};
console.log(person.name); // 출력: Alice
person.greet(); // 출력: Hello, my name is Alice</code></pre>
    </li>
    <li><strong>Array:</strong> 순서가 있는 값들의 목록입니다. 대괄호<code>[]</code>를 사용하여 생성합니다. 배열의 각 요소는 인덱스(0부터 시작)를 통해 접근합니다.
      <pre><code class="language-javascript">let fruits = ["apple", "banana", "cherry"];
console.log(fruits[0]); // 출력: apple
console.log(fruits.length); // 출력: 3
fruits.push("orange"); // 배열 끝에 요소 추가
console.log(fruits); // 출력: [ 'apple', 'banana', 'cherry', 'orange' ]</code></pre>
    </li>
    <li><strong>Function:</strong> 코드를 실행하는 단위로, JavaScript에서는 함수도 객체로 취급됩니다. (함수에 대해서는 다음 파트에서 자세히 다룹니다.)</li>
  </ul>

  <h3><code>typeof</code> 연산자</h3>
  <p><code>typeof</code> 연산자는 피연산자의 데이터 타입을 문자열로 반환합니다.</p>
  <pre><code class="language-javascript">console.log(typeof "Hello");     // "string"
console.log(typeof 123);         // "number"
console.log(typeof 123n);        // "bigint"
console.log(typeof true);        // "boolean"
console.log(typeof undefined);   // "undefined"
console.log(typeof Symbol("id"));// "symbol"
console.log(typeof null);        // "object" (주의!)
console.log(typeof { a: 1 });    // "object"
console.log(typeof [1, 2, 3]);   // "object" (배열도 객체 타입)
console.log(typeof function() {}); // "function" (함수는 특별하게 'function' 반환)</code></pre>
</section>


<section id="type-conversion">
    <h2>타입 변환 (Type Conversion)</h2>
    <p>JavaScript는 특정 상황에서 한 데이터 타입을 다른 타입으로 변환합니다. 이는 <strong>명시적 변환(Explicit Conversion)</strong>과 <strong>암묵적 변환(Implicit Conversion 또는 Coercion)</strong>으로 나뉩니다.</p>

    <h3>명시적 변환 (Explicit Conversion)</h3>
    <p>개발자가 의도를 가지고 타입을 변환하는 경우입니다. 내장 함수 <code>String()</code>, <code>Number()</code>, <code>Boolean()</code> 등을 사용합니다.</p>
    <ul>
        <li><strong>문자열로 변환 (<code>String()</code>):</strong>
          <pre><code class="language-javascript">let num = 123;
let bool = true;
let obj = { a: 1 };

console.log(String(num));    // "123"
console.log(String(bool));   // "true"
console.log(String(null));   // "null"
console.log(String(undefined)); // "undefined"
console.log(String(obj));    // "[object Object]" (객체는 기본적으로 이렇게 변환)

// .toString() 메서드 사용 (null, undefined 제외)
console.log(num.toString()); // "123"
console.log(bool.toString());// "true"</code></pre>
        </li>
        <li><strong>숫자로 변환 (<code>Number()</code>):</strong>
          <pre><code class="language-javascript">let strNum = "456";
let strFloat = "3.14";
let strEmpty = "";
let strText = "hello";
let boolTrue = true;
let boolFalse = false;

console.log(Number(strNum));     // 456
console.log(Number(strFloat));   // 3.14
console.log(Number(strEmpty));   // 0
console.log(Number("  123  ")); // 123 (앞뒤 공백 무시)
console.log(Number(strText));    // NaN (숫자로 변환 불가)
console.log(Number(boolTrue));   // 1
console.log(Number(boolFalse));  // 0
console.log(Number(null));       // 0
console.log(Number(undefined));  // NaN

// parseInt(), parseFloat() 사용 (문자열에서 숫자 부분만 추출)
console.log(parseInt("100px")); // 100
console.log(parseFloat("3.14em")); // 3.14</code></pre>
        </li>
        <li><strong>불리언으로 변환 (<code>Boolean()</code>):</strong> <code>false</code>, <code>0</code>, <code>-0</code>, <code>""</code>(빈 문자열), <code>null</code>, <code>undefined</code>, <code>NaN</code>을 제외한 모든 값은 <code>true</code>로 변환됩니다. 이를 "Truthy" 와 "Falsy" 값이라고 합니다.
          <pre><code class="language-javascript">console.log(Boolean(1));         // true
console.log(Boolean(0));         // false
console.log(Boolean("hello"));   // true
console.log(Boolean(""));        // false
console.log(Boolean({}));        // true (빈 객체도 true)
console.log(Boolean([]));        // true (빈 배열도 true)
console.log(Boolean(null));      // false
console.log(Boolean(undefined)); // false
console.log(Boolean(NaN));       // false</code></pre>
        </li>
    </ul>

    <h3>암묵적 변환 (Implicit Conversion / Coercion)</h3>
    <p>JavaScript 엔진이 연산 등의 상황에서 필요에 따라 자동으로 타입을 변환하는 경우입니다. 편리할 수도 있지만, 예측하지 못한 결과를 초래할 수 있으므로 주의해야 합니다.</p>
    <ul>
        <li><strong>문자열 연결 연산자 (<code>+</code>):</strong> <code>+</code> 연산자 한쪽에 문자열이 있으면 다른 쪽 피연산자도 문자열로 변환되어 연결됩니다.
          <pre><code class="language-javascript">console.log(1 + "2");      // "12" (1이 문자열 "1"로 변환됨)
console.log("10" + 5);     // "105" (5가 문자열 "5"로 변환됨)
console.log(true + " is boolean"); // "true is boolean"</code></pre>
        </li>
        <li><strong>산술 연산자 (<code>-</code>, <code>*</code>, <code>/</code>, <code>%</code> 등):</strong> 문자열 연결 외의 산술 연산자는 피연산자를 숫자로 변환하려고 시도합니다.
          <pre><code class="language-javascript">console.log("10" - 5);     // 5 ("10"이 숫자 10으로 변환됨)
console.log("10" * 2);     // 20
console.log("10" / "2");   // 5
console.log("hello" - 3);  // NaN ("hello"는 숫자로 변환 불가)
console.log(5 * null);     // 0 (null은 0으로 변환됨)
console.log(5 + true);     // 6 (true는 1로 변환됨)</code></pre>
        </li>
        <li><strong>비교 연산자 (<code>==</code>, <code>!=</code> 등):</strong> 동등 비교 연산자(<code>==</code>)는 타입을 변환하여 비교합니다. 일치 비교 연산자(<code>===</code>)는 타입 변환 없이 값과 타입 모두 비교하므로 더 안전하고 권장됩니다.
          <pre><code class="language-javascript">console.log(1 == "1");     // true (타입 변환 후 비교)
console.log(1 === "1");    // false (타입이 다르므로 false)
console.log(0 == false);   // true
console.log(0 === false);  // false
console.log(null == undefined); // true (== 비교 시 특별 규칙)
console.log(null === undefined);// false</code></pre>
        </li>
        <li><strong>논리 연산 및 조건문:</strong> 조건문(<code>if</code>, <code>while</code> 등)이나 논리 연산자(<code>&&</code>, <code>||</code>, <code>!</code>)의 피연산자는 불리언으로 암묵적 변환됩니다 (Truthy/Falsy 규칙 적용).
          <pre><code class="language-javascript">if ("hello") { // "hello"는 Truthy 값이므로 true로 평가됨
  console.log("Truthy value!");
}
if (0) { // 0은 Falsy 값이므로 false로 평가됨
  // 이 블록은 실행되지 않음
} else {
  console.log("Falsy value!");
}
console.log(!null); // true (null은 Falsy, !Falsy는 true)
console.log("Value" && 123); // 123 (&&는 첫 번째 Falsy 또는 마지막 Truthy 반환)
console.log(null || "Default"); // "Default" (||는 첫 번째 Truthy 또는 마지막 Falsy 반환)</code></pre>
        </li>
    </ul>
    <p class="warning">암묵적 타입 변환은 코드를 이해하기 어렵게 만들 수 있습니다. 가능한 명시적 변환을 사용하고, 비교 시에는 <code>===</code>, <code>!==</code>를 사용하는 것이 좋습니다.</p>
</section>


<section id="operators">
  <h2>연산자 (Operators)</h2>
  <p>연산자는 값에 대해 특정 연산(산술, 할당, 비교, 논리 등)을 수행하도록 하는 기호입니다.</p>

  <h3>산술 연산자 (Arithmetic Operators)</h3>
  <p>수학적 계산을 수행합니다.</p>
  <ul>
    <li><code>+</code> (덧셈)</li>
    <li><code>-</code> (뺄셈)</li>
    <li><code>*</code> (곱셈)</li>
    <li><code>/</code> (나눗셈)</li>
    <li><code>%</code> (나머지)</li>
    <li><code></code> (거듭제곱 - ES7)</li>
    <li><code>++</code> (증가: 변수 값 1 증가) - 전위(<code>++x</code>), 후위(<code>x++</code>)</li>
    <li><code>--</code> (감소: 변수 값 1 감소) - 전위(<code>--x</code>), 후위(<code>x--</code>)</li>
  </ul>
  <pre><code class="language-javascript">let a = 10;
let b = 4;
console.log(a + b); // 14
console.log(a - b); // 6
console.log(a * b); // 40
console.log(a / b); // 2.5
console.log(a % b); // 2 (10을 4로 나눈 나머지)
console.log(2  3); // 8 (2의 3제곱)

let count = 5;
count++; // count = count + 1; (후위 증가: 현재 값 사용 후 증가)
console.log(count); // 6
++count; // count = count + 1; (전위 증가: 증가 후 값 사용)
console.log(count); // 7

let x = 3;
let y = x++; // y = 3, x = 4 (후위 증가)
console.log(`x: ${x}, y: ${y}`);
let z = ++x; // x = 5, z = 5 (전위 증가)
console.log(`x: ${x}, z: ${z}`);</code></pre>

  <h3>할당 연산자 (Assignment Operators)</h3>
  <p>변수에 값을 할당합니다.</p>
  <ul>
    <li><code>=</code> (할당)</li>
    <li><code>+=</code> (덧셈 후 할당: <code>x += y</code> 는 <code>x = x + y</code>)</li>
    <li><code>-=</code> (뺄셈 후 할당)</li>
    <li><code>*=</code> (곱셈 후 할당)</li>
    <li><code>/=</code> (나눗셈 후 할당)</li>
    <li><code>%=</code> (나머지 후 할당)</li>
    <li><code>=</code> (거듭제곱 후 할당)</li>
  </ul>
  <pre><code class="language-javascript">let num = 10;
num += 5; // num = num + 5;
console.log(num); // 15
num *= 2; // num = num * 2;
console.log(num); // 30</code></pre>

  <h3>비교 연산자 (Comparison Operators)</h3>
  <p>두 값을 비교하여 불리언(<code>true</code> 또는 <code>false</code>) 값을 반환합니다.</p>
  <ul>
    <li><code>==</code> (동등: 값만 비교, 타입 변환 발생)</li>
    <li><code>!=</code> (부동등: 값만 비교, 타입 변환 발생)</li>
    <li><code>===</code> (일치: 값과 타입 모두 비교, 타입 변환 없음) - 권장</li>
    <li><code>!==</code> (불일치: 값 또는 타입이 다름, 타입 변환 없음) - 권장</li>
    <li><code>&gt;</code> (초과)</li>
    <li><code>&lt;</code> (미만)</li>
    <li><code>&gt;=</code> (이상)</li>
    <li><code>&lt;=</code> (이하)</li>
  </ul>
  <pre><code class="language-javascript">console.log(5 == "5");  // true
console.log(5 === "5"); // false
console.log(null == undefined); // true
console.log(null === undefined); // false

console.log(10 > 5);    // true
console.log(10 <= 10);  // true</code></pre>

  <h3>논리 연산자 (Logical Operators)</h3>
  <p>불리언 값들을 조합하여 결과를 반환합니다.</p>
  <ul>
    <li><code>&&</code> (AND): 두 피연산자 모두 <code>true</code>일 때 <code>true</code> 반환. (단축 평가: 첫 번째가 <code>false</code>면 두 번째는 평가 안 함)</li>
    <li><code>||</code> (OR): 두 피연산자 중 하나라도 <code>true</code>일 때 <code>true</code> 반환. (단축 평가: 첫 번째가 <code>true</code>면 두 번째는 평가 안 함)</li>
    <li><code>!</code> (NOT): 피연산자의 불리언 값을 반전시킴.</li>
  </ul>
  <pre><code class="language-javascript">let isLoggedIn = true;
let hasPermission = false;

console.log(isLoggedIn && hasPermission); // false
console.log(isLoggedIn || hasPermission); // true
console.log(!isLoggedIn);                // false

// 단축 평가 활용 예시
let user = { name: "Alice" };
let userName = user && user.name; // user가 Truthy하면 user.name 평가, 아니면 user 반환
console.log(userName); // "Alice"

let defaultName = null;
let displayName = defaultName || "Guest"; // defaultName이 Falsy하면 "Guest" 사용
console.log(displayName); // "Guest"</code></pre>

  <h3>삼항 연산자 (Ternary Operator)</h3>
  <p>세 개의 피연산자를 가지는 유일한 연산자로, 조건에 따라 다른 값을 반환하는 간단한 조건문 축약형입니다.</p>
  <p><code>condition ? valueIfTrue : valueIfFalse</code></p>
  <pre><code class="language-javascript">let age = 20;
let message = (age >= 19) ? "성인입니다." : "미성년자입니다.";
console.log(message); // "성인입니다."</code></pre>

  <h3>기타 연산자</h3>
  <p>이 외에도 <code>typeof</code>(타입 확인), <code>instanceof</code>(객체 타입 확인), 비트 연산자(<code>&</code>, <code>|</code>, <code>^</code>, <code>~</code>, <code>&lt;&lt;</code>, <code>&gt;&gt;</code>), 쉼표 연산자 등 다양한 연산자들이 있습니다.</p>

</section>

<br><br>
<hr>
<p>JavaScript 강좌의 두 번째 파트입니다. 여기서는 코드의 실행 순서를 제어하는 조건문과 반복문, 그리고 코드 블록을 재사용 가능하게 만드는 함수의 기본적인 사용법을 학습합니다.</p>
<p class="note"><strong>학습 목표:</strong> 특정 조건에 따라 다른 코드를 실행하고, 반복적인 작업을 효율적으로 처리하며, 기본적인 함수를 정의하고 호출할 수 있게 됩니다.</p>

<section id="control-flow">
  <h2>제어 흐름 (Control Flow)</h2>
  <p>제어 흐름은 프로그램의 코드가 실행되는 순서를 제어하는 방법을 의미합니다. 기본적으로 코드는 위에서 아래로 순차적으로 실행되지만, 제어문을 사용하면 특정 조건에 따라 실행 흐름을 분기하거나 특정 코드를 반복 실행할 수 있습니다.</p>
</section>

<section id="conditionals">
  <h2>조건문 (Conditional Statements)</h2>
  <p>조건문은 주어진 조건식의 평가 결과(true 또는 false)에 따라 다른 코드 블록을 실행하도록 제어합니다.</p>

  <h3 id="if-else">if, else if, else</h3>
  <p>가장 기본적인 조건문입니다.</p>
  <ul>
    <li><code>if (condition) { ... }</code>: <code>condition</code>이 true일 경우 중괄호 안의 코드를 실행합니다.</li>
    <li><code>else if (anotherCondition) { ... }</code>: 앞선 <code>if</code> 또는 <code>else if</code> 조건이 false이고, <code>anotherCondition</code>이 true일 경우 해당 코드 블록을 실행합니다. 여러 개의 <code>else if</code>를 사용할 수 있습니다.</li>
    <li><code>else { ... }</code>: 앞선 모든 <code>if</code> 및 <code>else if</code> 조건이 false일 경우 실행됩니다. (선택 사항)</li>
  </ul>
  <pre><code class="language-javascript">let score = 75;
let grade;

if (score >= 90) {
  grade = "A";
} else if (score >= 80) {
  grade = "B";
} else if (score >= 70) {
  grade = "C";
} else if (score >= 60) {
  grade = "D";
} else {
  grade = "F";
}

console.log(`점수 ${score}점은 ${grade} 등급입니다.`); // 출력: 점수 75점은 C 등급입니다.

// if 단독 사용
let temperature = 30;
if (temperature > 28) {
  console.log("에어컨을 켜세요."); // 출력: 에어컨을 켜세요.
}

// if-else 사용
let age = 15;
if (age >= 19) {
  console.log("성인입니다.");
} else {
  console.log("미성년자입니다."); // 출력: 미성년자입니다.
}</code></pre>

  <h3 id="switch">switch</h3>
  <p><code>switch</code> 문은 하나의 표현식 값을 여러 개의 특정 값(<code>case</code>)과 비교하여 일치하는 경우 해당 코드 블록을 실행합니다. 여러 <code>else if</code> 문을 대체할 수 있습니다.</p>
  <ul>
    <li><code>case value:</code>: 비교할 값. 표현식의 값과 <code>case</code>의 값이 일치하면(일치 비교 <code>===</code> 사용) 해당 <code>case</code> 아래 코드를 실행합니다.</li>
    <li><code>break;</code>: <code>switch</code> 문 실행을 종료합니다. <code>break</code>가 없으면 일치하는 <code>case</code> 이후의 모든 <code>case</code> 코드가 실행됩니다 (fall-through).</li>
    <li><code>default:</code>: 어떤 <code>case</code>와도 일치하지 않을 때 실행됩니다. (선택 사항)</li>
  </ul>
  <pre><code class="language-javascript">let fruit = "apple";
let fruitColor;

switch (fruit) {
  case "apple":
    fruitColor = "red or green";
    break; // switch 문 종료
  case "banana":
    fruitColor = "yellow";
    break;
  case "orange":
    fruitColor = "orange";
    break;
  case "grape":
    fruitColor = "purple";
    break;
  default: // 어떤 case와도 일치하지 않을 때
    fruitColor = "unknown";
}
console.log(`The color of ${fruit} is ${fruitColor}.`); // 출력: The color of apple is red or green.

// break 없는 경우 (fall-through)
let signal = "yellow";
switch (signal) {
  case "red":
    console.log("Stop!");
    break;
  case "yellow": // signal이 "yellow"이므로 여기서부터 실행
    console.log("Prepare to stop or proceed with caution.");
    // break가 없으므로 아래 case도 실행됨
  case "green":
    console.log("Go!");
    break; // 여기서 switch 문 종료
  default:
    console.log("Invalid signal.");
}
// 출력:
// Prepare to stop or proceed with caution.
// Go!
</code></pre>
</section>

<section id="loops">
  <h2>반복문 (Loops)</h2>
  <p>반복문은 특정 조건이 만족되는 동안 코드 블록을 반복적으로 실행합니다.</p>

  <h3 id="for">for</h3>
  <p><code>for</code> 루프는 반복 횟수가 예측 가능하거나, 특정 범위 내에서 반복할 때 주로 사용됩니다.</p>
  <p><code>for (initialization; condition; finalExpression) { ... }</code></p>
  <ul>
    <li><code>initialization</code>: 루프 시작 전에 한 번 실행 (주로 카운터 변수 초기화).</li>
    <li><code>condition</code>: 각 반복 시작 전에 평가. true이면 루프 본문 실행, false이면 루프 종료.</li>
    <li><code>finalExpression</code>: 각 반복 종료 후에 실행 (주로 카운터 변수 증감).</li>
  </ul>
  <pre><code class="language-javascript">// 0부터 4까지 출력
for (let i = 0; i < 5; i++) {
  console.log(`Current number: ${i}`);
}
// 출력:
// Current number: 0
// Current number: 1
// Current number: 2
// Current number: 3
// Current number: 4

// 배열 요소 순회
let colors = ["red", "green", "blue"];
for (let i = 0; i < colors.length; i++) {
  console.log(`Color at index ${i}: ${colors[i]}`);
}
// 출력:
// Color at index 0: red
// Color at index 1: green
// Color at index 2: blue
</code></pre>

  <h3 id="while">while, do...while</h3>
  <p><code>while</code> 루프는 주어진 조건이 true인 동안 코드 블록을 반복 실행합니다. 조건은 루프 본문 실행 전에 평가됩니다.</p>
  <p><code>while (condition) { ... }</code></p>
  <p><code>do...while</code> 루프는 <code>while</code>과 유사하지만, 조건을 루프 본문 실행 *후에* 평가합니다. 따라서 루프 본문이 최소 한 번은 실행되는 것을 보장합니다.</p>
  <p><code>do { ... } while (condition);</code></p>
  <pre><code class="language-javascript">// while 루프 예시
let count = 0;
while (count < 3) {
  console.log(`while count: ${count}`);
  count++;
}
// 출력:
// while count: 0
// while count: 1
// while count: 2

// do...while 루프 예시 (조건이 처음부터 false여도 최소 한 번 실행)
let num = 5;
do {
  console.log(`do...while num: ${num}`); // 이 줄은 실행됨
  num++;
} while (num < 5); // 조건은 false
// 출력: do...while num: 5
</code></pre>
  <p class="warning"><code>while</code> 루프 사용 시 조건이 언젠가는 false가 되도록 만들지 않으면 무한 루프에 빠질 수 있으므로 주의해야 합니다.</p>

  <h3 id="break-continue">break, continue</h3>
  <ul>
    <li><code>break</code>: 현재 실행 중인 반복문(<code>for</code>, <code>while</code>, <code>do...while</code>)이나 <code>switch</code> 문을 즉시 종료하고 다음 코드로 넘어갑니다.</li>
    <li><code>continue</code>: 현재 반복문의 나머지 부분을 건너뛰고 다음 반복을 시작합니다.</li>
  </ul>
  <pre><code class="language-javascript">// break 예시: 5를 찾으면 루프 종료
for (let i = 0; i < 10; i++) {
  if (i === 5) {
    console.log("Found 5! Breaking loop.");
    break; // 루프 종료
  }
  console.log(`Checking number: ${i}`);
}
// 출력:
// Checking number: 0
// Checking number: 1
// Checking number: 2
// Checking number: 3
// Checking number: 4
// Found 5! Breaking loop.

// continue 예시: 짝수만 출력 (홀수는 건너뛰기)
for (let i = 0; i < 10; i++) {
  if (i % 2 !== 0) { // i가 홀수이면
    continue; // 다음 반복으로 넘어감
  }
  console.log(`Even number: ${i}`);
}
// 출력:
// Even number: 0
// Even number: 2
// Even number: 4
// Even number: 6
// Even number: 8
</code></pre>

  <h3 id="for-in">for...in (객체 속성 반복)</h3>
  <p><code>for...in</code> 루프는 객체의 열거 가능한(enumerable) 속성 이름(key)들을 순회합니다.</p>
  <pre><code class="language-javascript">const user = {
  name: "Bob",
  age: 25,
  city: "Seoul"
};

for (let key in user) {
  // key 변수에는 속성 이름("name", "age", "city")이 차례로 할당됨
  console.log(`Property: ${key}, Value: ${user[key]}`);
}
// 출력 (순서는 보장되지 않음):
// Property: name, Value: Bob
// Property: age, Value: 25
// Property: city, Value: Seoul
</code></pre>
  <p class="warning"><code>for...in</code>은 프로토타입 체인(prototype chain) 상의 상속된 속성까지 열거할 수 있으며, 순서를 보장하지 않습니다. 따라서 배열 순회에는 사용하지 않는 것이 좋습니다. 배열 순회에는 일반 <code>for</code> 루프나 <code>for...of</code>를 사용하세요.</p>

  <h3 id="for-of">for...of (이터러블 반복)</h3>
  <p><code>for...of</code> 루프 (ES6+)는 반복 가능한(iterable) 객체(배열, 문자열, Map, Set 등)의 값(value)들을 순회합니다. 현재 가장 권장되는 배열 및 이터러블 순회 방식입니다.</p>
  <pre><code class="language-javascript">// 배열 순회
const fruits = ["apple", "banana", "cherry"];
for (let fruit of fruits) {
  // fruit 변수에는 배열의 각 요소 값이 차례로 할당됨
  console.log(`Fruit: ${fruit}`);
}
// 출력:
// Fruit: apple
// Fruit: banana
// Fruit: cherry

// 문자열 순회
const message = "Hello";
for (let char of message) {
  // char 변수에는 문자열의 각 문자가 차례로 할당됨
  console.log(char);
}
// 출력:
// H
// e
// l
// l
// o
</code></pre>
</section>

<section id="functions-basic">
  <h2>함수 기초 (Functions Basics)</h2>
  <p>함수(Function)는 특정 작업을 수행하는 독립적인 코드 블록입니다. 함수를 사용하면 다음과 같은 장점이 있습니다:</p>
  <ul>
    <li><strong>재사용성:</strong> 동일한 코드를 여러 번 작성할 필요 없이 함수를 호출하여 재사용할 수 있습니다.</li>
    <li><strong>모듈화:</strong> 코드를 기능 단위로 나누어 관리하기 용이하게 하고, 코드 가독성을 높입니다.</li>
    <li><strong>추상화:</strong> 함수의 내부 구현을 몰라도 함수 이름과 기능만 알면 사용할 수 있습니다.</li>
  </ul>
</section>

<section id="function-definition">
  <h2>함수 정의 방법</h2>
  <p>JavaScript에서 함수를 정의하는 주요 방법은 두 가지입니다.</p>
  <ol>
    <li>
      <strong>함수 선언문 (Function Declaration):</strong> <code>function</code> 키워드와 함수 이름, 매개변수 목록, 함수 본문(<code>{}</code>)으로 구성됩니다.
      <ul>
        <li><strong>호이스팅 발생:</strong> 함수 선언은 스코프 최상단으로 끌어올려지므로, 함수 정의 코드보다 앞에서 호출할 수 있습니다.</li>
      </ul>
      <pre><code class="language-javascript">function greet(name) {
  console.log(`Hello, ${name}!`);
}

greet("Alice"); // 출력: Hello, Alice!
// 함수 선언은 호이스팅되므로 정의 전에 호출 가능
sayGoodbye("Bob"); // 출력: Goodbye, Bob!

function sayGoodbye(name) {
  console.log(`Goodbye, ${name}!`);
}</code></pre>
    </li>
    <li>
      <strong>함수 표현식 (Function Expression):</strong> 변수에 익명 함수(이름 없는 함수)나 기명 함수를 할당하는 방식입니다.
      <ul>
        <li><strong>호이스팅 주의:</strong> 변수 선언(<code>let</code>, <code>const</code>, <code>var</code>) 자체는 호이스팅될 수 있지만, 함수 할당은 호이스팅되지 않습니다. 따라서 함수 표현식은 정의(할당)된 이후에만 호출할 수 있습니다.</li>
      </ul>
      <pre><code class="language-javascript">// 익명 함수 표현식
const add = function(a, b) {
  return a + b;
};

// 기명 함수 표현식 (함수 내부에서 재귀 호출 등에 사용 가능)
const factorial = function calculateFactorial(n) {
  if (n === 0) {
    return 1;
  }
  return n * calculateFactorial(n - 1); // 내부에서 이름 사용 가능
};

// 함수 표현식 호출
let sum = add(5, 3);
console.log(`Sum: ${sum}`); // 출력: Sum: 8

// console.log(multiply(2, 4)); // 오류: TypeError: multiply is not a function (호이스팅 X)
const multiply = function(a, b) {
  return a * b;
};
console.log(multiply(2, 4)); // 출력: 8
</code></pre>
    </li>
  </ol>
  <p class="note">ES6부터는 <strong>화살표 함수(Arrow Function)</strong>라는 간결한 함수 정의 방식도 도입되었습니다. 이는 다음 파트에서 자세히 다룹니다.</p>
</section>

<section id="function-params-args">
  <h2>매개변수와 인수</h2>
  <ul>
    <li><strong>매개변수 (Parameter):</strong> 함수를 정의할 때 함수가 받을 값을 나타내는 변수 이름입니다. (함수 정의 시 괄호 안에 명시)</li>
    <li><strong>인수 (Argument):</strong> 함수를 호출할 때 실제로 전달하는 값입니다. (함수 호출 시 괄호 안에 전달)</li>
  </ul>
  <pre><code class="language-javascript">         // parameter 'name'과 'age' 정의
function displayInfo(name, age) {
  console.log(`Name: ${name}, Age: ${age}`);
}

displayInfo("Charlie", 35); // argument "Charlie"와 35 전달
// 출력: Name: Charlie, Age: 35

// 인수가 매개변수보다 적게 전달되면, 남는 매개변수는 undefined가 됨
displayInfo("David");
// 출력: Name: David, Age: undefined

// 인수가 매개변수보다 많게 전달되면, 초과된 인수는 무시됨 (arguments 객체로 접근 가능 - 구 방식)
displayInfo("Eve", 28, "Seoul");
// 출력: Name: Eve, Age: 28
</code></pre>

  <h3>기본 매개변수 (Default Parameters - ES6+)</h3>
  <p>함수 호출 시 인수가 전달되지 않았거나 <code>undefined</code>가 전달된 경우 사용할 기본값을 매개변수에 지정할 수 있습니다.</p>
  <pre><code class="language-javascript">function greetUser(name = "Guest") { // name의 기본값을 "Guest"로 설정
  console.log(`Welcome, ${name}!`);
}

greetUser("Alice"); // 출력: Welcome, Alice!
greetUser();        // 출력: Welcome, Guest! (인수가 없으므로 기본값 사용)
greetUser(undefined); // 출력: Welcome, Guest! (undefined 전달 시 기본값 사용)
greetUser(null);      // 출력: Welcome, null! (null은 Falsy 값이지만 undefined가 아니므로 기본값 사용 안 함)
</code></pre>

  <h3>나머지 매개변수 (Rest Parameters - ES6+)</h3>
  <p>함수가 받을 인수의 개수가 정해져 있지 않을 때, 남은 인수들을 배열로 모아서 받을 수 있습니다. 점 세 개(<code>...</code>)를 사용하며, 반드시 마지막 매개변수여야 합니다.</p>
  <pre><code class="language-javascript">function sumAll(...numbers) { // numbers는 전달된 모든 인수를 담는 배열이 됨
  let total = 0;
  for (let num of numbers) {
    total += num;
  }
  return total;
}

console.log(sumAll(1, 2, 3));         // 출력: 6
console.log(sumAll(10, 20, 30, 40)); // 출력: 100
console.log(sumAll());               // 출력: 0

function logArguments(first, second, ...others) {
  console.log("First:", first);
  console.log("Second:", second);
  console.log("Others:", others); // 나머지 인수들이 배열로 들어옴
}

logArguments('a', 'b', 'c', 'd', 'e');
// 출력:
// First: a
// Second: b
// Others: [ 'c', 'd', 'e' ]
</code></pre>
</section>

<section id="function-return">
  <h2>반환 값 (Return Values)</h2>
  <p>함수는 <code>return</code> 키워드를 사용하여 결과값을 함수 호출 부분으로 되돌려줄 수 있습니다. 함수는 단 하나의 값만 반환할 수 있습니다 (객체나 배열을 반환하여 여러 값을 묶어서 전달 가능).</p>
  <ul>
    <li><code>return value;</code>: 함수 실행을 종료하고 <code>value</code>를 반환합니다.</li>
    <li><code>return</code>만 사용하거나 함수 끝까지 <code>return</code> 문이 없으면 함수는 <code>undefined</code>를 반환합니다.</li>
  </ul>
  <pre><code class="language-javascript">function calculateArea(width, height) {
  if (width <= 0 || height <= 0) {
		console.error("너비와 높이는 양수여야 합니다.");
		return; // 오류 시 undefined 반환하고 함수 종료 (Early return)
	  }
	  let area = width * height;
	  return area; // 계산된 넓이 값 반환
	}

let rectangleArea = calculateArea(10, 5);
console.log(`사각형의 넓이: ${rectangleArea}`); // 출력: 사각형의 넓이: 50

let invalidArea = calculateArea(-5, 10); // 오류 메시지 출력됨
console.log(`잘못된 넓이: ${invalidArea}`); // 출력: 잘못된 넓이: undefined

function logMessage(message) {
  console.log(message);
  // return 문이 없으므로 undefined를 반환
}

let result = logMessage("테스트 메시지"); // "테스트 메시지" 출력됨
console.log(`logMessage 반환값: ${result}`); // 출력: logMessage 반환값: undefined
</code></pre>
</section>

<section id="function-scope-intro">
    <h2>함수 스코프 기초</h2>
    <p>스코프(Scope)는 변수나 함수에 접근할 수 있는 유효 범위를 의미합니다.</p>
    <ul>
        <li><strong>전역 스코프 (Global Scope):</strong> 코드의 가장 바깥 영역입니다. 여기서 선언된 변수나 함수는 코드 어디에서든 접근 가능합니다 (전역 변수).</li>
        <li><strong>지역 스코프 (Local Scope) / 함수 스코프 (Function Scope):</strong> 함수 내부에서 선언된 변수나 함수는 해당 함수 내부에서만 접근 가능합니다 (지역 변수).</li>
    </ul>
    <p>ES6의 <code>let</code>과 <code>const</code>는 <strong>블록 스코프 (Block Scope)</strong>를 가지지만, 여기서는 함수 스코프의 기본 개념만 간단히 소개합니다. 스코프와 클로저(Closure)에 대한 더 자세한 내용은 다음 파트에서 다룹니다.</p>
    <pre><code class="language-javascript">let globalVar = "전역 변수"; // 전역 스코프

function myFunction() {
  let localVar = "지역 변수"; // 지역(함수) 스코프
  console.log(localVar); // 함수 내부에서는 접근 가능
  console.log(globalVar); // 함수 내부에서 전역 변수 접근 가능

  if (true) {
      let blockVar = "블록 변수 (let)"; // 블록 스코프
      var functionScopeVar = "함수 스코프 변수 (var)"; // 함수 스코프
      console.log(blockVar);
  }
  // console.log(blockVar); // 오류: blockVar is not defined (블록 스코프 밖)
  console.log(functionScopeVar); // 출력: 함수 스코프 변수 (var) (함수 스코프이므로 접근 가능)
}

myFunction();
// 출력:
// 지역 변수
// 전역 변수
// 블록 변수 (let)
// 함수 스코프 변수 (var)

// console.log(localVar); // 오류: localVar is not defined (함수 외부에서 지역 변수 접근 불가)
console.log(globalVar); // 출력: 전역 변수 (전역 변수는 어디서든 접근 가능)
</code></pre>
    <p>함수 내에서 선언된 변수는 외부 스코프의 같은 이름 변수를 가릴 수 있습니다 (Shadowing).</p>
</section>

<br><br>
<hr>

<section id="objects-deep">
  <h2>객체 심화 (Objects Deep Dive)</h2>
  <p>객체는 이름(key)과 값(value) 쌍으로 이루어진 속성(property)들의 컬렉션입니다. JavaScript에서 매우 중요한 데이터 구조입니다.</p>

  <h3 id="object-basics">객체 기본 및 속성 접근</h3>
  <p>객체 리터럴(<code>{}</code>)을 사용하여 객체를 생성하고, 속성에 접근하는 방법은 두 가지입니다:</p>
  <ul>
    <li><strong>점 표기법 (Dot notation):</strong> <code>object.propertyName</code>. 속성 이름이 유효한 식별자일 때 사용합니다. 가장 일반적인 방식입니다.</li>
    <li><strong>대괄호 표기법 (Bracket notation):</strong> <code>object['propertyName']</code>. 속성 이름이 동적으로 결정되거나, 유효한 식별자가 아닌 문자(공백, 특수문자 등)를 포함할 때 사용합니다. 속성 이름은 문자열로 전달해야 합니다.</li>
  </ul>
  <pre><code class="language-javascript">const user = {
  name: "Alice",
  "user age": 30, // 속성 이름에 공백 포함
  isAdmin: true,
  address: { // 중첩 객체
    city: "Seoul",
    zipCode: "12345"
  }
};

// 점 표기법
console.log(user.name); // 출력: Alice
console.log(user.address.city); // 출력: Seoul

// 대괄호 표기법
console.log(user['user age']); // 출력: 30 (공백 포함된 이름 접근)
let propName = "isAdmin";
console.log(user[propName]); // 출력: true (변수를 이용한 동적 접근)

// 속성 추가 및 수정
user.email = "alice@example.com"; // 새 속성 추가
user.name = "Alice Kim"; // 기존 속성 값 수정
user['user age'] = 31;

// 속성 삭제
delete user.isAdmin;

console.log(user);
/* 출력 예시:
{
  name: 'Alice Kim',
  'user age': 31,
  address: { city: 'Seoul', zipCode: '12345' },
  email: 'alice@example.com'
}
*/</code></pre>

  <h3 id="object-es6-features">객체 ES6+ 기능</h3>
  <p>ES6부터 객체 리터럴 사용을 더 편리하게 만드는 몇 가지 기능이 추가되었습니다.</p>
  <ul>
    <li><strong>속성 값 단축 (Property value shorthand):</strong> 변수 이름과 속성 키 이름이 같을 경우, <code>{ key: variable }</code> 대신 <code>{ variable }</code>으로 축약할 수 있습니다.</li>
    <li><strong>메서드 정의 단축 (Method definition shorthand):</strong> <code>methodName: function() { ... }</code> 대신 <code>methodName() { ... }</code>으로 축약할 수 있습니다.</li>
    <li><strong>계산된 속성 이름 (Computed property names):</strong> 대괄호 안에 표현식을 넣어 속성 이름을 동적으로 생성할 수 있습니다. <code>{ [expression]: value }</code></li>
  </ul>
  <pre><code class="language-javascript">let userName = "Bob";
let userAge = 25;
let propertyKey = "country";

const person = {
  // 속성 값 단축
  userName, // userName: userName 과 동일
  userAge,  // userAge: userAge 와 동일

  // 메서드 정의 단축
  greet() { // greet: function() { ... } 와 동일
    console.log(`Hello, my name is ${this.userName}`);
  },

  // 계산된 속성 이름
  [propertyKey]: "South Korea", // propertyKey 변수 값("country")이 속성 이름이 됨
  ["user" + "Status"]: "active" // 표현식 결과("userStatus")가 속성 이름이 됨
};

console.log(person);
// 출력: { userName: 'Bob', userAge: 25, greet: [Function: greet], country: 'South Korea', userStatus: 'active' }
person.greet(); // 출력: Hello, my name is Bob
</code></pre>

  <h3 id="object-methods-this">메서드와 'this'</h3>
  <p>객체의 속성 값이 함수인 경우, 이를 메서드(Method)라고 부릅니다. 메서드 내부에서 <code>this</code> 키워드는 일반적으로 해당 메서드를 호출한 객체를 가리킵니다.</p>
  <pre><code class="language-javascript">const calculator = {
  operand1: 5,
  operand2: 10,
  add() {
    // 여기서 this는 calculator 객체를 가리킴
    return this.operand1 + this.operand2;
  },
  subtract() {
    return this.operand1 - this.operand2;
  }
};

console.log(calculator.add()); // 출력: 15
console.log(calculator.subtract()); // 출력: -5
</code></pre>
  <p class="note"><code>this</code>의 동작 방식은 함수 호출 방식에 따라 달라지므로 주의가 필요합니다. 이는 <a href="#this-keyword">`this` 키워드</a> 섹션에서 더 자세히 다룹니다.</p>

  <h3 id="object-built-in-methods">내장 객체 메서드</h3>
  <p>JavaScript의 `Object` 생성자는 객체를 다루는 데 유용한 여러 정적 메서드를 제공합니다.</p>
  <ul>
    <li><code>Object.keys(obj)</code>: 객체의 열거 가능한 자체 속성 이름(key)들을 배열로 반환합니다.</li>
    <li><code>Object.values(obj)</code>: 객체의 열거 가능한 자체 속성 값(value)들을 배열로 반환합니다.</li>
    <li><code>Object.entries(obj)</code>: 객체의 열거 가능한 자체 속성 <code>[key, value]</code> 쌍을 요소로 하는 배열을 반환합니다.</li>
    <li><code>Object.assign(target, ...sources)</code>: 하나 이상의 출처(source) 객체들의 속성을 대상(target) 객체로 복사합니다. (얕은 복사)</li>
    <li><code>obj.hasOwnProperty(prop)</code>: 객체가 특정 속성을 직접 소유하고 있는지(프로토타입 상속 제외) 불리언 값으로 반환합니다.</li>
    <li><code>Object.freeze(obj)</code>: 객체를 동결하여 속성 추가, 삭제, 변경을 막습니다. (얕은 동결)</li>
    <li><code>Object.seal(obj)</code>: 객체를 밀봉하여 속성 추가, 삭제는 막지만 기존 속성 값 변경은 허용합니다.</li>
  </ul>
  <pre><code class="language-javascript">const car = {
  brand: "Hyundai",
  model: "Sonata",
  year: 2025
};

console.log(Object.keys(car));   // 출력: [ 'brand', 'model', 'year' ]
console.log(Object.values(car)); // 출력: [ 'Hyundai', 'Sonata', 2025 ]
console.log(Object.entries(car)); // 출력: [ [ 'brand', 'Hyundai' ], [ 'model', 'Sonata' ], [ 'year', 2025 ] ]

const carDetails = { color: "Black", sunroof: true };
const mergedCar = Object.assign({}, car, carDetails); // car와 carDetails를 새 객체 {}에 병합
console.log(mergedCar);
// 출력: { brand: 'Hyundai', model: 'Sonata', year: 2025, color: 'Black', sunroof: true }
console.log(car); // 원본 car 객체는 변경되지 않음

console.log(car.hasOwnProperty('brand')); // 출력: true
console.log(car.hasOwnProperty('toString')); // 출력: false (toString은 프로토타입 메서드)

Object.freeze(car);
car.year = 2026; // 변경 안 됨 (strict mode에서는 오류 발생)
console.log(car.year); // 출력: 2025
</code></pre>
</section>

<section id="arrays-deep">
  <h2>배열 심화 (Arrays Deep Dive)</h2>
  <p>배열은 순서가 있는 값들의 목록으로, JavaScript에서 데이터를 효율적으로 관리하는 데 필수적입니다. 다양한 내장 메서드를 제공합니다.</p>

  <h3 id="array-basics">배열 기본 및 조작 메서드</h3>
  <p>배열 리터럴(<code>[]</code>)로 생성하며, 인덱스(0부터 시작)로 요소에 접근합니다. <code>length</code> 속성으로 길이를 알 수 있습니다.</p>
  <ul>
    <li><strong>요소 추가/제거 (원본 배열 변경):</strong>
      <ul>
        <li><code>push(item1, ..., itemN)</code>: 배열 끝에 하나 이상의 요소 추가, 새 length 반환.</li>
        <li><code>pop()</code>: 배열 끝 요소 제거 및 반환.</li>
        <li><code>unshift(item1, ..., itemN)</code>: 배열 시작 부분에 하나 이상의 요소 추가, 새 length 반환.</li>
        <li><code>shift()</code>: 배열 시작 부분 요소 제거 및 반환.</li>
        <li><code>splice(start, deleteCount, item1, ..., itemN)</code>: 지정한 위치에서 요소를 제거하거나 추가. 제거된 요소 배열 반환.</li>
      </ul>
    </li>
    <li><strong>배열 일부 추출/병합 (원본 배열 변경 안 함):</strong>
        <ul>
            <li><code>slice(start, end)</code>: 배열의 `start` 인덱스부터 `end` 인덱스 전까지의 요소를 추출하여 새 배열로 반환.</li>
            <li><code>concat(array1, ..., arrayN)</code>: 기존 배열에 다른 배열이나 값들을 이어붙여 새 배열로 반환.</li>
        </ul>
    </li>
    <li><code>join(separator)</code>: 배열의 모든 요소를 문자열로 연결하여 반환. `separator` 생략 시 쉼표(<code>,</code>) 사용.</li>
  </ul>
  <pre><code class="language-javascript">let fruits = ["apple", "banana"];

fruits.push("cherry", "orange"); // 끝에 추가
console.log(fruits); // [ 'apple', 'banana', 'cherry', 'orange' ]

let removedFruit = fruits.pop(); // 끝 요소 제거
console.log(removedFruit); // "orange"
console.log(fruits); // [ 'apple', 'banana', 'cherry' ]

fruits.unshift("grape"); // 시작 부분에 추가
console.log(fruits); // [ 'grape', 'apple', 'banana', 'cherry' ]

fruits.shift(); // 시작 요소 제거
console.log(fruits); // [ 'apple', 'banana', 'cherry' ]

// 인덱스 1 위치에서 1개 요소 제거하고 "kiwi", "melon" 추가
let removedItems = fruits.splice(1, 1, "kiwi", "melon");
console.log(removedItems); // [ 'banana' ] (제거된 요소)
console.log(fruits); // [ 'apple', 'kiwi', 'melon', 'cherry' ]

let slicedFruits = fruits.slice(1, 3); // 인덱스 1부터 3 전까지 (1, 2)
console.log(slicedFruits); // [ 'kiwi', 'melon' ]
console.log(fruits); // 원본 불변: [ 'apple', 'kiwi', 'melon', 'cherry' ]

let moreFruits = ["strawberry", "pineapple"];
let combinedFruits = fruits.concat(moreFruits, "mango");
console.log(combinedFruits); // [ 'apple', 'kiwi', 'melon', 'cherry', 'strawberry', 'pineapple', 'mango' ]

console.log(fruits.join(" - ")); // "apple - kiwi - melon - cherry"
</code></pre>

  <h3 id="array-search-methods">배열 검색 메서드</h3>
  <ul>
      <li><code>indexOf(searchElement, fromIndex)</code>: 배열에서 지정된 요소를 찾아 첫 번째 인덱스를 반환. 없으면 -1 반환.</li>
      <li><code>lastIndexOf(searchElement, fromIndex)</code>: 배열 뒤쪽부터 검색하여 첫 번째 인덱스 반환. 없으면 -1 반환.</li>
      <li><code>includes(searchElement, fromIndex)</code> (ES7+): 배열에 지정된 요소가 포함되어 있는지 불리언 값으로 반환. (<code>NaN</code> 검색 가능)</li>
  </ul>
   <pre><code class="language-javascript">let numbers = [10, 20, 30, 20, 40];
console.log(numbers.indexOf(20)); // 1 (첫 번째 20의 인덱스)
console.log(numbers.indexOf(20, 2)); // 3 (인덱스 2부터 검색 시작)
console.log(numbers.indexOf(50)); // -1 (없음)
console.log(numbers.lastIndexOf(20)); // 3 (뒤에서부터 찾은 첫 번째 20의 인덱스)
console.log(numbers.includes(30)); // true
console.log(numbers.includes(50)); // false
</code></pre>

  <h3 id="array-iteration-methods">배열 고차 함수 (반복 메서드)</h3>
  <p>이 메서드들은 콜백 함수(callback function)를 인수로 받아 배열의 각 요소에 대해 특정 작업을 수행합니다. 매우 유용하고 자주 사용됩니다.</p>
  <ul>
    <li><code>forEach(callback(element, index, array))</code>: 배열의 각 요소에 대해 콜백 함수를 실행. 반환값 없음.</li>
    <li><code>map(callback(element, index, array))</code>: 배열의 각 요소에 대해 콜백 함수를 실행하고, 그 결과를 모아 새로운 배열을 반환.</li>
    <li><code>filter(callback(element, index, array))</code>: 콜백 함수가 true를 반환하는 요소들만 모아 새로운 배열을 반환.</li>
    <li><code>reduce(callback(accumulator, currentValue, index, array), initialValue)</code>: 배열의 각 요소에 대해 콜백 함수를 실행하여 하나의 누적된 결과값을 반환. <code>initialValue</code>는 초기 누적값 (선택 사항).</li>
    <li><code>find(callback(element, index, array))</code>: 콜백 함수가 true를 반환하는 첫 번째 요소를 반환. 없으면 <code>undefined</code> 반환.</li>
    <li><code>findIndex(callback(element, index, array))</code>: 콜백 함수가 true를 반환하는 첫 번째 요소의 인덱스를 반환. 없으면 -1 반환.</li>
    <li><code>some(callback(element, index, array))</code>: 배열의 요소 중 하나라도 콜백 함수가 true를 반환하면 true 반환.</li>
    <li><code>every(callback(element, index, array))</code>: 배열의 모든 요소가 콜백 함수에서 true를 반환하면 true 반환.</li>
  </ul>
   <pre><code class="language-javascript">let nums = [1, 2, 3, 4, 5];

// forEach: 각 요소 출력
nums.forEach((num, index) => {
  console.log(`Index ${index}: ${num}`);
});

// map: 각 요소를 제곱한 새 배열 생성
let squaredNums = nums.map(num => num * num);
console.log(squaredNums); // [ 1, 4, 9, 16, 25 ]

// filter: 짝수만 필터링한 새 배열 생성
let evenNums = nums.filter(num => num % 2 === 0);
console.log(evenNums); // [ 2, 4 ]

// reduce: 모든 요소의 합계 계산
let sum = nums.reduce((accumulator, currentValue) => {
  return accumulator + currentValue;
}, 0); // 초기값 0
console.log(sum); // 15

// find: 3보다 큰 첫 번째 요소 찾기
let firstLargeNum = nums.find(num => num > 3);
console.log(firstLargeNum); // 4

// findIndex: 3보다 큰 첫 번째 요소의 인덱스 찾기
let firstLargeIndex = nums.findIndex(num => num > 3);
console.log(firstLargeIndex); // 3

// some: 홀수가 하나라도 있는지 확인
let hasOdd = nums.some(num => num % 2 !== 0);
console.log(hasOdd); // true

// every: 모든 요소가 0보다 큰지 확인
let allPositive = nums.every(num => num > 0);
console.log(allPositive); // true
</code></pre>

  <h3 id="array-sort">배열 정렬 (sort)</h3>
  <p><code>sort(compareFunction)</code> 메서드는 배열 요소를 정렬합니다. 원본 배열을 직접 변경합니다.</p>
  <ul>
      <li><code>compareFunction</code>이 제공되지 않으면, 요소들은 문자열 유니코드 코드 포인트 순서로 정렬됩니다. (숫자 정렬 시 문제 발생 가능)</li>
      <li>숫자를 제대로 정렬하려면 비교 함수(compare function)를 전달해야 합니다.
          <ul>
              <li><code>compareFunction(a, b)</code>가 0보다 작으면 <code>a</code>가 <code>b</code>보다 앞에 오도록 정렬.</li>
              <li><code>compareFunction(a, b)</code>가 0보다 크면 <code>b</code>가 <code>a</code>보다 앞에 오도록 정렬.</li>
              <li><code>compareFunction(a, b)</code>가 0이면 순서 변경 없음.</li>
              <li>오름차순 정렬: <code>(a, b) => a - b</code></li>
              <li>내림차순 정렬: <code>(a, b) => b - a</code></li>
          </ul>
      </li>
  </ul>
  <pre><code class="language-javascript">let months = ["March", "Jan", "Feb", "Dec"];
months.sort(); // 문자열 기준 정렬
console.log(months); // [ 'Dec', 'Feb', 'Jan', 'March' ]

let points = [40, 100, 1, 5, 25, 10];
points.sort(); // 문자열로 변환 후 정렬 (예상과 다름)
console.log(points); // [ 1, 10, 100, 25, 40, 5 ] (잘못된 숫자 정렬)

// 숫자 오름차순 정렬
points.sort((a, b) => a - b);
console.log(points); // [ 1, 5, 10, 25, 40, 100 ]

// 숫자 내림차순 정렬
points.sort((a, b) => b - a);
console.log(points); // [ 100, 40, 25, 10, 5, 1 ]

// 객체 배열 정렬 (예: 나이 기준 오름차순)
let users = [
  { name: "Alice", age: 30 },
  { name: "Bob", age: 25 },
  { name: "Charlie", age: 35 }
];
users.sort((a, b) => a.age - b.age);
console.log(users);
// 출력: [ { name: 'Bob', age: 25 }, { name: 'Alice', age: 30 }, { name: 'Charlie', age: 35 } ]
</code></pre>

  <h3 id="array-destructuring-spread">배열 구조 분해 할당 및 전개 구문</h3>
  <p>ES6에서 도입된 문법으로 배열 작업을 더 편리하게 해줍니다.</p>
  <ul>
    <li><strong>구조 분해 할당 (Destructuring Assignment):</strong> 배열의 요소를 개별 변수에 쉽게 할당할 수 있습니다.
      <pre><code class="language-javascript">const rgb = [255, 128, 0];

// 기본 구조 분해
const [red, green, blue] = rgb;
console.log(red, green, blue); // 255 128 0

// 일부 요소만 할당 및 기본값 설정
const [r, , b, alpha = 1] = rgb; // green 건너뛰기, alpha 기본값 1
console.log(r, b, alpha); // 255 0 1

// 나머지 요소 모으기 (Rest pattern)
const [first, second, ...others] = ["a", "b", "c", "d", "e"];
console.log(first); // "a"
console.log(second); // "b"
console.log(others); // [ 'c', 'd', 'e' ]</code></pre>
    </li>
    <li><strong>전개 구문 (Spread Syntax):</strong> 점 세 개(<code>...</code>)를 사용하여 배열이나 이터러블 객체를 펼쳐서 개별 요소로 만듭니다. 배열 복사, 병합, 함수 인수 전달 등에 유용합니다.
      <pre><code class="language-javascript">// 배열 복사 (얕은 복사)
const original = [1, 2, 3];
const copy = [...original];
console.log(copy); // [ 1, 2, 3 ]
console.log(copy === original); // false (다른 배열 객체)

// 배열 병합
const arr1 = [1, 2];
const arr2 = [3, 4];
const merged = [...arr1, ...arr2, 5];
console.log(merged); // [ 1, 2, 3, 4, 5 ]

// 함수 인수로 전달
function sum(a, b, c) {
  return a + b + c;
}
const numbersToAdd = [5, 10, 15];
const result = sum(...numbersToAdd); // sum(5, 10, 15)와 동일
console.log(result); // 30</code></pre>
    </li>
  </ul>
</section>

<section id="functions-deep">
    <h2>함수 심화 (Functions Deep Dive)</h2>
    <p>함수의 기본적인 정의와 사용법을 넘어, ES6에서 도입된 화살표 함수, 동작 방식이 까다로운 <code>this</code> 키워드, 그리고 JavaScript의 중요 개념인 클로저에 대해 알아봅니다.</p>

    <h3 id="arrow-functions">화살표 함수 (Arrow Functions - ES6+)</h3>
    <p>화살표 함수는 <code>function</code> 키워드 대신 화살표(<code>=></code>)를 사용하여 함수를 더 간결하게 표현하는 문법입니다.</p>
    <ul>
        <li><strong>기본 문법:</strong> <code>(param1, param2, ...) => { statements }</code></li>
        <li>매개변수가 하나일 경우 괄호(<code>()</code>) 생략 가능: <code>param => { statements }</code></li>
        <li>함수 본문이 하나의 표현식(반환값)일 경우 중괄호(<code>{}</code>)와 <code>return</code> 키워드 생략 가능: <code>param => expression</code></li>
        <li>객체 리터럴을 반환할 때는 괄호로 감싸야 함: <code>() => ({ a: 1 })</code></li>
    </ul>
    <pre><code class="language-javascript">// 기본 함수 표현식
const add_legacy = function(a, b) {
  return a + b;
};

// 화살표 함수
const add_arrow = (a, b) => {
  return a + b;
};

// 본문이 한 줄이면 return과 중괄호 생략 가능
const subtract = (a, b) => a - b;

// 매개변수가 하나면 괄호 생략 가능
const square = x => x * x;

// 매개변수가 없으면 빈 괄호 사용
const sayHello = () => console.log("Hello!");

// 객체 리터럴 반환 시 괄호 필요
const createPerson = (name, age) => ({ name: name, age: age });

console.log(add_arrow(5, 3)); // 8
console.log(subtract(10, 4)); // 6
console.log(square(7)); // 49
sayHello(); // Hello!
console.log(createPerson("Grace", 28)); // { name: 'Grace', age: 28 }
</code></pre>
    <h4>화살표 함수와 일반 함수의 주요 차이점:</h4>
    <ol>
        <li><strong><code>this</code> 바인딩:</strong> 화살표 함수는 자신의 <code>this</code>를 생성하지 않고, 자신을 포함하고 있는 외부 스코프(lexical scope)의 <code>this</code>를 그대로 사용합니다. 이는 <code>this</code> 관련 문제를 해결하는 데 큰 도움이 됩니다. (자세한 내용은 <a href="#this-keyword">`this` 키워드</a> 섹션 참조)</li>
        <li><strong><code>arguments</code> 객체 없음:</strong> 화살표 함수 내부에서는 <code>arguments</code> 객체를 사용할 수 없습니다. 대신 나머지 매개변수(<code>...args</code>)를 사용해야 합니다.</li>
        <li><strong>생성자 함수로 사용 불가:</strong> 화살표 함수는 <code>new</code> 키워드와 함께 사용하여 객체를 생성할 수 없습니다. (<code>prototype</code> 속성 없음)</li>
        <li><code>yield</code> 키워드 사용 불가: 화살표 함수는 제너레이터(generator) 함수로 사용할 수 없습니다.</li>
    </ol>

    <h3 id="this-keyword">`this` 키워드</h3>
    <p><code>this</code>는 함수가 호출될 때 결정되는 특별한 키워드로, 함수가 실행되는 맥락(context)을 참조합니다. <code>this</code>가 가리키는 값은 함수를 어떻게 호출했는지에 따라 동적으로 결정됩니다.</p>
    <ul>
        <li><strong>전역 컨텍스트 (Global Context):</strong> 함수 외부에서 <code>this</code>를 사용하면 전역 객체를 가리킵니다. 브라우저 환경에서는 <code>window</code> 객체, Node.js 환경에서는 <code>global</code> 객체입니다. (단, strict mode에서는 <code>undefined</code>)
        <pre><code class="language-javascript">console.log(this === window); // 브라우저에서 true (strict mode 아닐 시)
function checkGlobalThis() {
  "use strict"; // 엄격 모드
  console.log(this); // undefined
}
checkGlobalThis();</code></pre></li>
        <li><strong>함수 호출 (Simple Function Call):</strong> 함수를 직접 호출할 때, non-strict mode에서는 전역 객체(<code>window</code>)를, strict mode에서는 <code>undefined</code>를 가리킵니다. 이는 종종 혼란을 야기합니다.
        <pre><code class="language-javascript">function whoAmI() {
  console.log(this);
}
whoAmI(); // non-strict: window, strict: undefined
</code></pre></li>
        <li><strong>메서드 호출 (Method Invocation):</strong> 객체의 메서드로 함수를 호출할 때, <code>this</code>는 해당 메서드를 호출한 객체를 가리킵니다. (<code>object.method()</code>)
        <pre><code class="language-javascript">const myObj = {
  name: "My Object",
  getName: function() {
    console.log(this.name); // this는 myObj를 가리킴
  }
};
myObj.getName(); // 출력: My Object

const getNameFunc = myObj.getName;
// getNameFunc(); // 오류 발생 가능 (non-strict: window.name, strict: TypeError) - this가 myObj가 아님
</code></pre></li>
        <li><strong>생성자 함수 호출 (Constructor Invocation):</strong> <code>new</code> 키워드를 사용하여 함수(생성자)를 호출할 때, <code>this</code>는 새로 생성되는 객체를 가리킵니다.
        <pre><code class="language-javascript">function Person(name) {
  // new 키워드로 호출되면 this는 새로 생성된 Person 객체를 가리킴
  this.name = name;
  console.log(this);
}
const person1 = new Person("Alice"); // 출력: Person { name: 'Alice' }
const person2 = new Person("Bob");   // 출력: Person { name: 'Bob' }
</code></pre></li>
        <li><strong>이벤트 핸들러 (Event Handlers):</strong> HTML 요소의 이벤트 핸들러로 함수가 호출될 때, <code>this</code>는 보통 이벤트를 발생시킨 요소를 가리킵니다. (단, 화살표 함수나 `bind` 사용 시 달라질 수 있음)
        <pre><code class="language-html">&lt;button id="myBtn"&gt;Click Me&lt;/button&gt;
&lt;script&gt;
  const btn = document.getElementById('myBtn');
  btn.addEventListener('click', function() {
    console.log(this); // this는 button 요소를 가리킴
    this.textContent = 'Clicked!';
  });

  btn.addEventListener('mouseover', () => {
      // 화살표 함수는 this를 외부 스코프(여기서는 전역 window)에서 가져옴
      console.log(this); // window (또는 strict mode에서는 undefined)
      // btn.style.backgroundColor = 'yellow'; // this 대신 btn 변수 사용해야 함
  });
&lt;/script&gt;</code></pre></li>
        <li><strong>명시적 바인딩 (Explicit Binding):</strong> <code>call()</code>, <code>apply()</code>, <code>bind()</code> 메서드를 사용하여 함수의 <code>this</code> 값을 명시적으로 지정할 수 있습니다.
            <ul>
                <li><code>func.call(thisArg, arg1, arg2, ...)</code>: 함수를 호출하면서 <code>this</code>를 <code>thisArg</code>로 설정하고, 인수들을 개별적으로 전달.</li>
                <li><code>func.apply(thisArg, [argsArray])</code>: 함수를 호출하면서 <code>this</code>를 <code>thisArg</code>로 설정하고, 인수들을 배열 형태로 전달.</li>
                <li><code>func.bind(thisArg)</code>: 함수의 <code>this</code> 값을 <code>thisArg</code>로 영구히 바인딩한 새로운 함수를 반환 (호출은 하지 않음).</li>
            </ul>
        <pre><code class="language-javascript">function greet(greeting, punctuation) {
  console.log(`${greeting}, ${this.name}${punctuation}`);
}

const personA = { name: "Alice" };
const personB = { name: "Bob" };

// call 사용
greet.call(personA, "Hello", "!"); // Hello, Alice!

// apply 사용
greet.apply(personB, ["Hi", "?"]); // Hi, Bob?

// bind 사용
const greetAlice = greet.bind(personA, "Good morning"); // this를 personA로, 첫 인수를 "Good morning"으로 고정
greetAlice("."); // Good morning, Alice.
greetAlice("!!!"); // Good morning, Alice!!!
</code></pre></li>
        <li><strong>화살표 함수에서의 `this`: 화살표 함수는 자신의 `this`를 가지지 않습니다. 대신, 자신을 감싸고 있는 가장 가까운 non-arrow 함수의 `this` 또는 전역 컨텍스트의 `this`를 참조합니다 (Lexical `this`). 이는 콜백 함수 등에서 `this` 관련 혼란을 크게 줄여줍니다.
        <pre><code class="language-javascript">const counter = {
  count: 0,
  start() {
    // 일반 함수 사용 시: setInterval 콜백의 this는 window 또는 undefined
    // setInterval(function() {
    //   this.count++; // 여기서 this는 counter가 아님 (TypeError 발생 가능)
    //   console.log(this.count);
    // }, 1000);

    // 화살표 함수 사용 시: this는 start 메서드의 this(counter 객체)를 참조
    setInterval(() => {
      this.count++;
      console.log(this.count); // 정상 작동
    }, 1000);
  }
};
// counter.start(); // 1초마다 count 증가 출력
</code></pre></li>
    </ul>
    <p class="warning"><code>this</code>는 JavaScript에서 가장 혼란스러운 부분 중 하나입니다. 함수가 어떻게 호출되었는지 주의 깊게 살펴보고, ES6+ 환경에서는 가능한 화살표 함수를 활용하거나 <code>bind</code>를 사용하여 <code>this</code>를 명확히 하는 것이 좋습니다.</p>

    <h3 id="closures">클로저 (Closures)</h3>
    <p>클로저(Closure)는 함수와 그 함수가 선언될 당시의 렉시컬 환경(Lexical Environment)의 조합입니다. 간단히 말해, 함수가 자신이 정의된 스코프(Scope)를 기억하고, 해당 스코프의 변수에 계속 접근할 수 있는 현상 또는 기능을 의미합니다.</p>
    <p>클로저는 다음과 같은 상황에서 자연스럽게 발생합니다:</p>
    <ul>
        <li>함수 내부에서 다른 함수를 정의하고 반환할 때</li>
        <li>콜백 함수가 외부 함수의 변수를 참조할 때</li>
    </ul>
    <h4>클로저의 작동 원리:</h4>
    <p>함수가 정의될 때, 그 함수는 자신이 속한 렉시컬 스코프(변수와 상위 스코프에 대한 참조 등)를 기억합니다. 나중에 그 함수가 외부 스코프에서 호출되더라도, 정의될 때 기억했던 스코프에 접근하여 변수를 읽거나 수정할 수 있습니다.</p>

    <h4>클로저 예시 1: 함수 팩토리</h4>
    <pre><code class="language-javascript">function makeAdder(x) {
  // 외부 함수 makeAdder의 스코프. x 변수가 여기에 속함.
  return function(y) {
    // 내부 함수는 외부 함수 스코프의 x에 접근 가능 (클로저)
    return x + y;
  };
}

const add5 = makeAdder(5); // add5 함수는 x=5인 스코프를 기억
const add10 = makeAdder(10); // add10 함수는 x=10인 스코프를 기억

console.log(add5(2)); // 7 (기억된 x=5 와 y=2를 더함)
console.log(add10(3)); // 13 (기억된 x=10 와 y=3을 더함)
</code></pre>

    <h4>클로저 예시 2: 정보 은닉 (Private Variables)</h4>
    <pre><code class="language-javascript">function createCounter() {
  let count = 0; // 외부에서 직접 접근 불가능한 '비공개' 변수

  return {
    increment: function() {
      count++;
      console.log(count);
    },
    decrement: function() {
      count--;
      console.log(count);
    },
    getCount: function() {
      return count;
    }
  };
}

const counter1 = createCounter();
counter1.increment(); // 1
counter1.increment(); // 2
// console.log(counter1.count); // 오류: count 변수는 외부에서 접근 불가

const counter2 = createCounter(); // counter1과 다른 스코프를 가짐
counter2.increment(); // 1
console.log(`Counter 1 count: ${counter1.getCount()}`); // Counter 1 count: 2
console.log(`Counter 2 count: ${counter2.getCount()}`); // Counter 2 count: 1
</code></pre>
    <p>클로저는 상태를 유지하고 정보를 은닉하는 등 JavaScript에서 강력한 패턴을 구현하는 데 핵심적인 역할을 합니다.</p>
</section>


<section id="dom-intro">
  <h2>DOM 소개</h2>
  <p><strong>DOM (Document Object Model)</strong>은 웹 브라우저가 HTML 문서를 이해하고 조작할 수 있도록 문서를 트리 구조의 객체 모델로 표현한 것입니다. JavaScript는 이 DOM을 통해 HTML 요소에 접근하고, 내용을 변경하며, 스타일을 조작하고, 새로운 요소를 추가하거나 제거하는 등 웹 페이지를 동적으로 제어할 수 있습니다.</p>
  <p>각 HTML 태그는 DOM 트리에서 하나의 노드(Node) 객체가 되며, 이 노드들은 부모-자식 관계를 형성합니다. 최상위 노드는 <code>document</code> 객체입니다.</p>
  <p class="note">참고: BOM (Browser Object Model)은 브라우저 창이나 탭 자체를 다루는 객체 모델(<code>window</code>, <code>navigator</code>, <code>location</code>, <code>history</code>, <code>screen</code> 등)입니다. DOM은 BOM의 일부인 <code>window.document</code> 객체를 통해 접근합니다.</p>
</section>

<section id="dom-selecting">
  <h2>DOM 요소 선택</h2>
  <p>JavaScript로 HTML 요소를 조작하려면 먼저 원하는 요소를 선택해야 합니다. 다양한 선택 메서드가 제공됩니다.</p>
  <ul>
    <li><code>document.getElementById('idName')</code>: 주어진 ID를 가진 단일 요소를 반환합니다. ID는 고유해야 하므로 가장 빠르고 직접적인 방법입니다. 없으면 <code>null</code> 반환.</li>
    <li><code>document.getElementsByTagName('tagName')</code>: 주어진 태그 이름(예: 'p', 'div')을 가진 모든 요소를 <code>HTMLCollection</code>(유사 배열 객체, 실시간 반영)으로 반환합니다.</li>
    <li><code>document.getElementsByClassName('className')</code>: 주어진 클래스 이름을 가진 모든 요소를 <code>HTMLCollection</code>으로 반환합니다.</li>
    <li><code>document.querySelector('cssSelector')</code>: 주어진 CSS 선택자(예: '#id', '.class', 'tag[attr="value"]')와 일치하는 첫 번째 요소를 반환합니다. 매우 유연하고 강력한 최신 방법입니다. 없으면 <code>null</code> 반환.</li>
    <li><code>document.querySelectorAll('cssSelector')</code>: 주어진 CSS 선택자와 일치하는 모든 요소를 <code>NodeList</code>(유사 배열 객체, 정적 또는 실시간 반영될 수 있음)로 반환합니다.</li>
  </ul>
  <div class="example">
      <h4>DOM 요소 선택 예제용 HTML</h4>
      <div id="container">
          <h1 class="title main-title">메인 제목</h1>
          <p class="content">첫 번째 문단입니다.</p>
          <p class="content special">두 번째 <em>특별한</em> 문단입니다.</p>
          <ul>
              <li>항목 1</li>
              <li class="item-selected">항목 2</li>
              <li>항목 3</li>
          </ul>
      </div>
      <div id="output-selecting" class="output">선택 결과가 여기에 표시됩니다.</div>
  </div>
  <pre><code class="language-javascript">// ID로 선택
const containerDiv = document.getElementById('container');
console.log(containerDiv); // <div id="container">...</div>

// 태그 이름으로 선택 (HTMLCollection 반환)
const paragraphs = document.getElementsByTagName('p');
console.log(paragraphs); // HTMLCollection [ p.content, p.content.special ]
console.log(paragraphs.length); // 2
console.log(paragraphs[0].textContent); // "첫 번째 문단입니다."

// 클래스 이름으로 선택 (HTMLCollection 반환)
const contentParas = document.getElementsByClassName('content');
console.log(contentParas); // HTMLCollection [ p.content, p.content.special ]

// CSS 선택자로 첫 번째 요소 선택
const mainTitle = document.querySelector('.title'); // 첫 번째 .title 클래스 요소
console.log(mainTitle.textContent); // "메인 제목"

const secondPara = document.querySelector('p.special'); // .special 클래스를 가진 p 요소
console.log(secondPara.textContent); // "두 번째 특별한 문단입니다."

// CSS 선택자로 모든 요소 선택 (NodeList 반환)
const listItems = document.querySelectorAll('ul > li');
console.log(listItems); // NodeList [ li, li.item-selected, li ]
console.log(listItems.length); // 3

// NodeList는 forEach 사용 가능 (HTMLCollection은 경우에 따라 불가능할 수 있음)
listItems.forEach((item, index) => {
  console.log(`List item ${index + 1}: ${item.textContent}`);
});

// document 대신 특정 요소 하위에서 검색 가능
const selectedItem = containerDiv.querySelector('.item-selected');
console.log(selectedItem.textContent); // "항목 2"

// 결과 출력 예시
const outputDivS = document.getElementById('output-selecting');
outputDivS.textContent = `ID 'container': ${containerDiv ? '찾음' : '못찾음'}, 첫 번째 p 내용: ${paragraphs[0]?.textContent}`;
</code></pre>
  <p class="note"><code>querySelector</code>와 <code>querySelectorAll</code>은 CSS 선택자를 그대로 사용할 수 있어 매우 편리하며 현대 JavaScript에서 널리 사용됩니다.</p>
</section>

<section id="dom-traversing">
  <h2>DOM 탐색 (Traversing)</h2>
  <p>선택한 요소를 기준으로 부모, 자식, 형제 요소 등으로 이동하며 DOM 트리를 탐색할 수 있습니다.</p>
  <p class="warning">주의: `childNodes`, `firstChild`, `lastChild`, `previousSibling`, `nextSibling` 등은 텍스트 노드(공백, 줄바꿈 포함)나 주석 노드도 포함할 수 있습니다. 일반적으로 Element 노드만 다루고 싶다면 `children`, `firstElementChild`, `lastElementChild`, `previousElementSibling`, `nextElementSibling` 등을 사용하는 것이 더 편리합니다.</p>
  <ul>
    <li><strong>부모 요소:</strong>
      <ul>
        <li><code>element.parentNode</code>: 부모 노드를 반환 (모든 노드 타입).</li>
        <li><code>element.parentElement</code>: 부모 요소 노드를 반환 (Element 노드만). 대부분의 경우 더 유용.</li>
        <li><code>element.closest('cssSelector')</code>: 자신을 포함하여 가장 가까운 조상 중 주어진 CSS 선택자와 일치하는 요소를 반환. 매우 유용.</li>
      </ul>
    </li>
    <li><strong>자식 요소:</strong>
      <ul>
        <li><code>element.childNodes</code>: 모든 자식 노드(텍스트, 주석 포함)를 <code>NodeList</code>로 반환.</li>
        <li><code>element.children</code>: 자식 요소 노드(Element만)를 <code>HTMLCollection</code>으로 반환.</li>
        <li><code>element.firstChild</code> / <code>element.lastChild</code>: 첫 번째/마지막 자식 노드.</li>
        <li><code>element.firstElementChild</code> / <code>element.lastElementChild</code>: 첫 번째/마지막 자식 요소 노드.</li>
        <li><code>element.hasChildNodes()</code>: 자식 노드가 있는지 확인.</li>
      </ul>
    </li>
    <li><strong>형제 요소:</strong>
      <ul>
        <li><code>element.previousSibling</code> / <code>element.nextSibling</code>: 이전/다음 형제 노드.</li>
        <li><code>element.previousElementSibling</code> / <code>element.nextElementSibling</code>: 이전/다음 형제 요소 노드.</li>
      </ul>
    </li>
      <li><code>element.matches('cssSelector')</code>: 요소가 주어진 CSS 선택자와 일치하는지 확인.</li>
  </ul>
  <div class="example">
      <h4>DOM 탐색 예제용 HTML</h4>
      <div id="traverse-container">
          <p>첫 번째 단락</p>
          <ul>
              <li id="item-1">항목 1</li>
              <li id="item-2" class="selected"> 항목 2 </li>
              <li id="item-3">항목 3</li>
          </ul>
          <p>마지막 단락</p>
      </div>
       <div id="output-traversing" class="output">탐색 결과가 여기에 표시됩니다.</div>
  </div>
   <pre><code class="language-javascript">const item2 = document.getElementById('item-2');
const ulElement = item2.parentElement; // 부모 요소 (ul)
const container = item2.closest('#traverse-container'); // 가장 가까운 조상 #traverse-container

console.log(ulElement.tagName); // "UL"
console.log(container.id); // "traverse-container"

// 자식 요소 탐색 (Element 노드만 권장)
const listItemsCollection = ulElement.children; // HTMLCollection [ li#item-1, li#item-2.selected, li#item-3 ]
console.log(listItemsCollection.length); // 3
console.log(ulElement.firstElementChild.id); // "item-1"
console.log(ulElement.lastElementChild.textContent); // "항목 3"

// 형제 요소 탐색 (Element 노드만 권장)
const prevItem = item2.previousElementSibling; // li#item-1
const nextItem = item2.nextElementSibling; // li#item-3
console.log(prevItem.textContent); // "항목 1"
console.log(nextItem ? nextItem.textContent : "다음 형제 없음"); // "항목 3"

// previousSibling은 주석이나 텍스트 노드를 반환할 수 있음
console.log(item2.previousSibling.nodeType); // 8 (주석 노드) 또는 3 (텍스트 노드 - 공백) 일 수 있음

// matches 확인
console.log(item2.matches('.selected')); // true

// 결과 출력 예시
const outputDivT = document.getElementById('output-traversing');
outputDivT.textContent = `item-2의 부모 태그: ${ulElement?.tagName}, 이전 형제 요소 내용: ${prevItem?.textContent}`;

</code></pre>
</section>

<section id="dom-modifying">
  <h2>DOM 요소 수정</h2>
  <p>선택한 요소의 내용, 속성, 스타일 등을 변경할 수 있습니다.</p>

  <h3 id="dom-modifying-content">콘텐츠 변경</h3>
  <ul>
    <li><code>element.innerHTML</code>: 요소 내부의 HTML 마크업 전체를 가져오거나 설정합니다. HTML 문자열을 직접 다루므로 편리하지만, 사용자 입력값 등을 그대로 넣을 경우 XSS(Cross-Site Scripting) 공격에 취약해질 수 있으므로 주의해야 합니다.</li>
    <li><code>element.textContent</code>: 요소 내부의 모든 텍스트 내용만 가져오거나 설정합니다. HTML 태그는 해석하지 않고 순수 텍스트로 처리합니다. 텍스트만 다룰 때는 <code>innerHTML</code>보다 안전하고 성능이 좋습니다.</li>
    <li><code>element.innerText</code>: 화면에 렌더링되는 텍스트 내용을 가져오거나 설정합니다. CSS 스타일에 따라 숨겨진 텍스트는 제외될 수 있으며, 레이아웃 계산이 필요하여 <code>textContent</code>보다 느릴 수 있습니다.</li>
  </ul>
  <div class="example">
      <h4>콘텐츠 변경 예제용 HTML</h4>
      <div id="content-box">이것은 <b>원래</b> 콘텐츠입니다.</div>
      <button id="btn-change-html">innerHTML 변경</button>
      <button id="btn-change-text">textContent 변경</button>
      <div id="output-content" class="output">변경 결과가 여기에 표시됩니다.</div>
  </div>
<pre><code class="language-javascript">const contentBox = document.getElementById('content-box');
const outputDivC = document.getElementById('output-content');

// 초기 내용 확인
console.log('innerHTML:', contentBox.innerHTML); // "이것은 <b>원래</b> 콘텐츠입니다."
console.log('textContent:', contentBox.textContent); // "이것은 원래 콘텐츠입니다."
console.log('innerText:', contentBox.innerText); // "이것은 원래 콘텐츠입니다." (렌더링 상태에 따라 다를 수 있음)

document.getElementById('btn-change-html').addEventListener('click', () => {
  contentBox.innerHTML = '<strong>innerHTML</strong>로 변경됨! <span>태그 적용됨</span>';
  outputDivC.textContent = 'innerHTML 변경됨';
});

document.getElementById('btn-change-text').addEventListener('click', () => {
  contentBox.textContent = '<strong>textContent</strong>로 변경됨! <span>태그는 텍스트로</span>';
  outputDivC.textContent = 'textContent 변경됨';
});

// 주의: 사용자 입력을 innerHTML에 직접 넣지 마세요! (아래는 XSS 예시 코드 문자열)
// const userInput = '&lt;img src=x onerror=alert(&quot;XSS!&quot;)&gt;'; // <--- 이 부분을 HTML 엔티티로 변경
// contentBox.innerHTML = userInput; // 매우 위험! (이 줄은 여전히 주석 처리되어 실행되지 않음)
</code></pre>

  <h3 id="dom-modifying-attributes">속성 변경</h3>
  <p>HTML 요소의 속성(attribute) 값을 가져오거나 설정, 제거할 수 있습니다.</p>
  <ul>
      <li><code>element.getAttribute('attrName')</code>: 지정된 속성 값을 문자열로 반환.</li>
      <li><code>element.setAttribute('attrName', 'value')</code>: 속성 값을 설정.</li>
      <li><code>element.hasAttribute('attrName')</code>: 속성 존재 여부를 불리언으로 반환.</li>
      <li><code>element.removeAttribute('attrName')</code>: 속성을 제거.</li>
      <li>직접 속성 접근: <code>id</code>, <code>src</code>, <code>href</code>, <code>value</code>, <code>disabled</code>, <code>checked</code> 등 표준 속성은 객체의 프로퍼티처럼 직접 접근 가능 (예: <code>img.src = 'new.jpg';</code>, <code>input.value = 'text';</code>).</li>
      <li><code>element.dataset</code>: <code>data-*</code> 커스텀 속성에 접근하는 방법 (예: HTML <code>&lt;div data-user-id="123"&gt;</code> -> JS <code>div.dataset.userId</code>). 속성 이름은 camelCase로 변환됨.</li>
      <li><code>element.className</code>: 클래스 속성 값을 문자열로 직접 제어. (기존 클래스 덮어씀)</li>
      <li><code>element.classList</code>: 클래스를 더 편리하게 제어하는 메서드 제공 (`add()`, `remove()`, `toggle()`, `contains()`). 클래스 조작 시 권장되는 방식.</li>
  </ul>
   <div class="example">
      <h4>속성 변경 예제용 HTML</h4>
      <a id="myLink" href="https://example.com" target="_blank" data-link-type="external">예시 링크</a>
      <img id="myImage" src="https://placeholder.co/100?text=Old" alt="예시 이미지" class="image default-border">
      <button id="btn-change-attr">속성 변경</button>
      <div id="output-attr" class="output">변경 결과가 여기에 표시됩니다.</div>
  </div>
  <pre><code class="language-javascript">const myLink = document.getElementById('myLink');
const myImage = document.getElementById('myImage');
const outputDivA = document.getElementById('output-attr');

document.getElementById('btn-change-attr').addEventListener('click', () => {
  // get/setAttribute
  let currentHref = myLink.getAttribute('href');
  myLink.setAttribute('href', 'https://naver.com');
  myLink.setAttribute('title', '네이버로 이동'); // title 속성 추가

  // 직접 속성 접근
  myImage.src = 'https://via.placeholder.com/150?text=New';
  myImage.alt = '변경된 이미지';

  // dataset
  console.log(myLink.dataset.linkType); // "external"
  myLink.dataset.visited = 'true'; // data-visited="true" 속성 추가

  // classList 사용 (권장)
  myImage.classList.remove('default-border'); // 클래스 제거
  myImage.classList.add('thumbnail'); // 클래스 추가
  myImage.classList.toggle('highlight'); // 'highlight' 클래스 있으면 제거, 없으면 추가

  outputDivA.textContent = `링크 href 변경됨: ${myLink.href}, 이미지 src 변경됨. 이미지 클래스: ${myImage.className}`;
});
</code></pre>

  <h3 id="dom-modifying-styles">스타일 변경</h3>
  <p>JavaScript로 요소의 스타일을 직접 변경할 수 있지만, CSS 클래스를 이용한 스타일 변경을 권장합니다.</p>
  <ul>
      <li><code>element.style.property = 'value'</code>: 요소의 인라인 스타일을 직접 설정합니다. CSS 속성 이름은 camelCase로 변환하여 사용합니다 (예: <code>background-color</code> -> <code>backgroundColor</code>, <code>font-size</code> -> <code>fontSize</code>).</li>
      <li><code>element.style.cssText = '...'</code>: 인라인 스타일 전체를 문자열로 설정.</li>
      <li><code>window.getComputedStyle(element)</code>: 요소에 최종적으로 적용된 모든 CSS 스타일 값을 읽어옵니다 (읽기 전용).</li>
  </ul>
   <div class="example">
      <h4>스타일 변경 예제용 HTML</h4>
      <div id="style-box" style="width: 100px; height: 100px; border: 1px solid black; background-color: lightblue;"></div>
      <button id="btn-change-style">인라인 스타일 변경</button>
      <button id="btn-toggle-class">클래스 토글 (권장)</button>
      <style>.active-style { background-color: salmon !important; border: 3px dashed red; transform: scale(1.1); transition: all 0.3s ease; }</style>
      <div id="output-style" class="output">변경 결과가 여기에 표시됩니다.</div>
  </div>
  <pre><code class="language-javascript">const styleBox = document.getElementById('style-box');
const outputDivSt = document.getElementById('output-style');

document.getElementById('btn-change-style').addEventListener('click', () => {
  // 인라인 스타일 직접 변경 (camelCase 사용)
  styleBox.style.backgroundColor = 'lightgreen';
  styleBox.style.borderWidth = '5px';
  styleBox.style.borderColor = 'green';
  styleBox.style.borderRadius = '10px'; // 모서리 둥글게

  // 계산된 스타일 읽기 (읽기 전용)
  const computedStyle = window.getComputedStyle(styleBox);
  outputDivSt.textContent = `인라인 스타일 변경됨. 계산된 배경색: ${computedStyle.backgroundColor}`;
});

document.getElementById('btn-toggle-class').addEventListener('click', () => {
  // CSS 클래스 토글 (이 방식이 더 효율적이고 관리하기 좋음)
  styleBox.classList.toggle('active-style');
  let message = styleBox.classList.contains('active-style') ? '클래스 추가됨' : '클래스 제거됨';
  outputDivSt.textContent = message;
});
</code></pre>
  <p class="warning"><code>element.style</code>은 인라인 스타일만 제어합니다. CSS 파일이나 <code>&lt;style&gt;</code> 태그에 정의된 스타일 규칙을 직접 변경하지는 못하며, 인라인 스타일은 명시도가 높아 다른 스타일 규칙을 덮어쓸 수 있습니다. 따라서 복잡한 스타일 변경은 CSS 클래스를 추가/제거하는 방식(<code>classList</code> 사용)이 훨씬 좋습니다.</p>
</section>

<section id="dom-creating-adding-removing">
  <h2>DOM 요소 생성, 추가, 제거</h2>
  <p>JavaScript로 새로운 HTML 요소를 만들고 문서에 추가하거나 기존 요소를 제거할 수 있습니다.</p>
  <ul>
      <li><strong>생성:</strong>
          <ul>
              <li><code>document.createElement('tagName')</code>: 지정된 태그 이름의 새 요소를 생성.</li>
              <li><code>document.createTextNode('text')</code>: 텍스트 노드를 생성.</li>
              <li><code>document.createDocumentFragment()</code>: 여러 요소를 추가할 때 성능 향상을 위해 사용하는 가상의 DOM 노드 (메모리 내 컨테이너).</li>
          </ul>
      </li>
      <li><strong>추가/삽입 (구 방식):</strong>
          <ul>
              <li><code>parentNode.appendChild(newNode)</code>: <code>parentNode</code>의 자식 목록 끝에 <code>newNode</code>를 추가.</li>
              <li><code>parentNode.insertBefore(newNode, referenceNode)</code>: <code>parentNode</code>의 자식인 <code>referenceNode</code> 앞에 <code>newNode</code>를 삽입. (<code>referenceNode</code>가 <code>null</code>이면 <code>appendChild</code>와 동일)</li>
          </ul>
      </li>
       <li><strong>추가/삽입 (최신 방식 - 더 편리):</strong>
          <ul>
              <li><code>element.append(...nodes or strings)</code>: 요소의 마지막 자식 뒤에 노드나 문자열 추가.</li>
              <li><code>element.prepend(...nodes or strings)</code>: 요소의 첫 번째 자식 앞에 노드나 문자열 추가.</li>
              <li><code>element.before(...nodes or strings)</code>: 요소 앞에 형제로 노드나 문자열 추가.</li>
              <li><code>element.after(...nodes or strings)</code>: 요소 뒤에 형제로 노드나 문자열 추가.</li>
          </ul>
      </li>
      <li><strong>제거/교체:</strong>
          <ul>
              <li><code>parentNode.removeChild(childNode)</code>: <code>parentNode</code>에서 <code>childNode</code>를 제거.</li>
              <li><code>parentNode.replaceChild(newNode, oldNode)</code>: <code>parentNode</code>의 <code>oldNode</code>를 <code>newNode</code>로 교체.</li>
              <li><code>element.remove()</code> (최신 방식): 요소를 DOM 트리에서 직접 제거.</li>
          </ul>
      </li>
       <li><strong>HTML 문자열 삽입:</strong>
           <ul>
               <li><code>element.insertAdjacentHTML(position, htmlString)</code>: 지정된 위치에 HTML 문자열을 파싱하여 삽입. `position`은 'beforebegin', 'afterbegin', 'beforeend', 'afterend'. (<code>innerHTML</code>보다 유연하지만 XSS 주의 필요)</li>
           </ul>
       </li>
  </ul>
  <div class="example">
      <h4>요소 추가/제거 예제용 HTML</h4>
      <ul id="dynamic-list">
          <li>기존 항목 1</li>
      </ul>
      <button id="btn-add-item">항목 추가</button>
      <button id="btn-remove-item">마지막 항목 제거</button>
      <div id="output-create" class="output">변경 결과가 여기에 표시됩니다.</div>
  </div>
  <pre><code class="language-javascript">const dynamicList = document.getElementById('dynamic-list');
const outputDivCr = document.getElementById('output-create');
let itemCount = 1;

document.getElementById('btn-add-item').addEventListener('click', () => {
  itemCount++;
  // 1. 새 li 요소 생성
  const newItem = document.createElement('li');

  // 2. 텍스트 노드 생성 및 li에 추가 (textContent 사용이 더 간단)
  // const textNode = document.createTextNode(`새 항목 ${itemCount}`);
  // newItem.appendChild(textNode);
  newItem.textContent = `새 항목 ${itemCount}`; // 이 방법이 더 간결

  // 3. data-* 속성 추가
  newItem.dataset.itemId = `item-${itemCount}`;

  // 4. 생성된 li 요소를 ul 끝에 추가 (append 사용)
  dynamicList.append(newItem);
  // dynamicList.appendChild(newItem); // 구 방식

  outputDivCr.textContent = `"${newItem.textContent}" 추가됨. 총 항목 수: ${dynamicList.children.length}`;

  // insertAdjacentHTML 예시 (주의해서 사용)
  // dynamicList.insertAdjacentHTML('beforeend', `<li>insertAdjacentHTML 항목 ${itemCount}</li>`);
});

document.getElementById('btn-remove-item').addEventListener('click', () => {
  const lastItem = dynamicList.lastElementChild;
  if (lastItem) {
    // lastItem.remove(); // 최신 방식
    dynamicList.removeChild(lastItem); // 구 방식
    outputDivCr.textContent = `"${lastItem.textContent}" 제거됨. 총 항목 수: ${dynamicList.children.length}`;
    // itemCount--; // 실제 구현 시 필요할 수 있음
  } else {
    outputDivCr.textContent = '제거할 항목이 없습니다.';
  }
});

// DocumentFragment 사용 예시 (여러 요소 추가 시 성능 향상)
/*
const fragment = document.createDocumentFragment();
for (let i = 0; i < 5; i++) {
  const item = document.createElement('li');
  item.textContent = `Fragment Item ${i + 1}`;
  fragment.appendChild(item);
}
dynamicList.appendChild(fragment); // 한 번의 DOM 변경으로 여러 요소 추가
*/
</code></pre>
</section>

<section id="events-intro">
  <h2>이벤트 소개</h2>
  <p>이벤트(Event)는 웹 페이지에서 발생하는 사건들을 의미합니다. 예를 들어, 사용자가 버튼을 클릭하거나, 마우스를 움직이거나, 키보드를 누르거나, 페이지 로드가 완료되는 것 등이 모두 이벤트입니다.</p>
  <p>JavaScript를 사용하면 이러한 이벤트가 발생했을 때 특정 코드가 실행되도록 이벤트 핸들러(Event Handler) 또는 이벤트 리스너(Event Listener) 함수를 등록하여 페이지를 상호작용적으로 만들 수 있습니다.</p>
</section>

<section id="event-handling-models">
  <h2>이벤트 처리 모델</h2>
  <p>이벤트를 처리하는 방법은 몇 가지가 있지만, 현대 JavaScript에서는 <strong>`addEventListener`</strong> 메서드를 사용하는 것이 표준이자 권장 방식입니다.</p>
  <ol>
    <li><strong>인라인 이벤트 핸들러 (비권장):</strong> HTML 요소의 `on<event>` 속성에 직접 JavaScript 코드를 작성하는 방식입니다. (예: `<button onclick="alert('클릭!');">`) HTML과 JavaScript 코드가 섞여 유지보수가 어렵습니다.</li>
    <li><strong>전통적 이벤트 핸들러 (프로퍼티 리스너):</strong> DOM 요소 객체의 `on<event>` 프로퍼티에 핸들러 함수를 할당하는 방식입니다. (예: `btn.onclick = function() { ... };`) 간단하지만, 하나의 이벤트에 하나의 핸들러만 등록할 수 있다는 단점이 있습니다.
    <pre><code class="language-javascript">const myButton = document.getElementById('myButton');
if (myButton) {
  myButton.onclick = function() {
    console.log("Button clicked! (Handler 1)");
  };
  // 이전에 할당된 핸들러를 덮어씀
  myButton.onclick = function() {
    console.log("Button clicked! (Handler 2)"); // Handler 1은 실행 안 됨
  };
}</code></pre></li>
    <li><strong>모던 이벤트 리스너 (`addEventListener`):</strong> `element.addEventListener('eventName', handlerFunction, [options or useCapture])` 메서드를 사용하여 이벤트를 등록합니다.
        <ul>
            <li>`eventName`: 이벤트 이름 (예: 'click', 'mouseover', 'keydown'). 'on' 접두사 없음.</li>
            <li>`handlerFunction`: 이벤트 발생 시 실행될 콜백 함수. 이 함수는 이벤트 객체를 인수로 받습니다.</li>
            <li>`options` (객체) / `useCapture` (불리언): 이벤트 흐름(캡처링/버블링), 한 번만 실행 여부 등 옵션 지정. (기본값: 버블링 단계에서 처리)</li>
        </ul>
        <p><strong>장점:</strong></p>
        <ul>
            <li>하나의 이벤트에 여러 개의 핸들러를 등록할 수 있습니다.</li>
            <li>이벤트 흐름(캡처링/버블링) 제어가 가능합니다.</li>
            <li><code>removeEventListener</code>로 등록된 핸들러를 제거할 수 있습니다.</li>
            <li>코드 분리가 용이하여 가독성과 유지보수성이 좋습니다.</li>
        </ul>
        <pre><code class="language-javascript">const modernButton = document.getElementById('modernBtn'); // 예시 버튼

function handler1() {
  console.log("Modern Handler 1 executed!");
}
function handler2(event) { // 이벤트 객체(event)를 받을 수 있음
  console.log("Modern Handler 2 executed! Event type:", event.type);
  // 이벤트 핸들러 제거 예시 (한 번만 실행되도록)
  // modernButton.removeEventListener('click', handler2);
}

if (modernButton) {
  // 여러 핸들러 등록 가능
  modernButton.addEventListener('click', handler1);
  modernButton.addEventListener('click', handler2);
  modernButton.addEventListener('mouseover', () => { console.log("Mouse over!"); });

  // 핸들러 제거 (등록 시 사용한 함수 참조가 정확히 일치해야 함)
  // modernButton.removeEventListener('click', handler1);
}
</code></pre></li>
  </ol>
  <p class="note">특별한 이유가 없다면 항상 <strong>`addEventListener`</strong>를 사용하여 이벤트를 처리하는 것이 좋습니다.</p>
</section>

<section id="event-object">
  <h2>이벤트 객체 (Event Object)</h2>
  <p>이벤트가 발생하면, 브라우저는 발생한 이벤트에 대한 상세 정보를 담은 이벤트 객체(Event Object)를 생성하여 이벤트 핸들러 함수에 인수로 전달합니다. 이 객체를 통해 이벤트의 종류, 발생 위치, 관련 요소 등에 접근할 수 있습니다.</p>
  <p>주요 프로퍼티 및 메서드:</p>
  <ul>
    <li><code>event.type</code>: 발생한 이벤트의 종류 (예: 'click', 'keydown').</li>
    <li><code>event.target</code>: 이벤트가 실제로 발생한 요소 (가장 안쪽 요소).</li>
    <li><code>event.currentTarget</code>: 이벤트 핸들러가 현재 바인딩된 요소 (<code>this</code>와 같을 수 있음).</li>
    <li><code>event.preventDefault()</code>: 이벤트의 기본 동작(예: 폼 제출, 링크 이동)을 막습니다.</li>
    <li><code>event.stopPropagation()</code>: 이벤트가 상위 요소로 전파(버블링 또는 캡처링)되는 것을 막습니다.</li>
    <li><code>event.timeStamp</code>: 이벤트가 생성된 시간 (페이지 로드 후 경과 시간, 밀리초).</li>
    <li>마우스 이벤트 관련: <code>clientX</code>/<code>clientY</code> (뷰포트 기준 좌표), <code>pageX</code>/<code>pageY</code> (문서 전체 기준 좌표), <code>button</code> (클릭된 마우스 버튼 번호).</li>
    <li>키보드 이벤트 관련: <code>key</code> (눌린 키의 문자 값), <code>code</code> (눌린 키의 물리적 코드 값), <code>altKey</code>, <code>ctrlKey</code>, <code>shiftKey</code>, <code>metaKey</code> (보조키 눌림 여부).</li>
  </ul>
  <div class="example">
      <h4>이벤트 객체 예제용 HTML</h4>
      <a id="preventLink" href="https://naver.com" target="_blank">네이버 (기본 동작 막기)</a><br><br>
      <div id="outerBox" style="padding: 20px; border: 1px solid red;">
          Outer Box (currentTarget)
          <button id="innerBtn" style="margin: 10px;">Inner Button (target)</button>
      </div>
      <input type="text" id="keyInput" placeholder="키보드를 입력하세요">
      <div id="output-event" class="output">이벤트 정보가 여기에 표시됩니다.</div>
  </div>
  <pre><code class="language-javascript">const preventLink = document.getElementById('preventLink');
const outerBox = document.getElementById('outerBox');
const innerBtn = document.getElementById('innerBtn');
const keyInput = document.getElementById('keyInput');
const outputDivE = document.getElementById('output-event');

// 기본 동작 막기
preventLink.addEventListener('click', function(event) {
  event.preventDefault(); // 링크 이동 기본 동작 막기
  outputDivE.textContent = '링크 기본 동작이 막혔습니다!';
});

// target vs currentTarget, stopPropagation
outerBox.addEventListener('click', function(event) {
  // 이벤트 핸들러는 outerBox에 붙어 있음 -> currentTarget은 outerBox
  // 실제 클릭은 innerBtn에서 발생했을 수 있음 -> target은 innerBtn
  outputDivE.textContent = `currentTarget: ${event.currentTarget.id}, target: ${event.target.id}`;
});

innerBtn.addEventListener('click', function(event) {
  outputDivE.textContent = `Inner button clicked! target: ${event.target.id}`;
  // event.stopPropagation(); // 이 줄의 주석을 해제하면 이벤트가 outerBox로 전파(버블링)되지 않음
});

// 키보드 이벤트 정보
keyInput.addEventListener('keydown', function(event) {
  outputDivE.textContent = `Key pressed: ${event.key}, Code: ${event.code}, Ctrl key: ${event.ctrlKey}`;
});
</code></pre>
</section>

<section id="event-flow">
    <h2>이벤트 흐름 (Event Flow)</h2>
    <p>DOM 요소에서 이벤트가 발생하면, 이벤트는 DOM 트리를 따라 특정 순서로 전파됩니다. 이 흐름은 두 단계로 나뉩니다.</p>
    <ol>
        <li><strong>캡처링 단계 (Capturing Phase):</strong> 이벤트가 최상위 요소(<code>window</code>)에서 시작하여 이벤트가 발생한 실제 요소(<code>event.target</code>)까지 아래 방향으로 전파됩니다.</li>
        <li><strong>버블링 단계 (Bubbling Phase):</strong> 이벤트가 발생한 실제 요소(<code>event.target</code>)에서 시작하여 최상위 요소(<code>window</code>)까지 위 방향으로 다시 전파됩니다.</li>
    </ol>
    <p><code>addEventListener</code>의 세 번째 인수를 사용하여 어느 단계에서 이벤트를 처리할지 지정할 수 있습니다.</p>
    <ul>
        <li><code>addEventListener('click', handler, false)</code> (또는 생략): 버블링 단계에서 핸들러 실행 (기본값).</li>
        <li><code>addEventListener('click', handler, true)</code>: 캡처링 단계에서 핸들러 실행.</li>
    </ul>
    <div class="example">
        <h4>이벤트 흐름 예제용 HTML</h4>
        <div id="grandparent" style="border: 2px solid red; padding: 30px;"> Grandparent
            <div id="parent" style="border: 2px solid green; padding: 20px;"> Parent
                <button id="child" style="padding: 10px;">Child (Click Me)</button>
            </div>
        </div>
        <div id="output-flow" class="output">이벤트 흐름 로그가 여기에 표시됩니다.</div>
    </div>
    <pre><code class="language-javascript">const gp = document.getElementById('grandparent');
const p = document.getElementById('parent');
const c = document.getElementById('child');
const outputDivF = document.getElementById('output-flow');
outputDivF.textContent = 'Child 버튼을 클릭해보세요.';

// 캡처링 단계 리스너 (true 옵션)
gp.addEventListener('click', () => { outputDivF.textContent += '\nGrandparent Capturing'; }, true);
p.addEventListener('click', () => { outputDivF.textContent += '\nParent Capturing'; }, true);
c.addEventListener('click', () => { outputDivF.textContent += '\nChild Capturing'; }, true);

// 버블링 단계 리스너 (false 또는 생략)
gp.addEventListener('click', () => { outputDivF.textContent += '\nGrandparent Bubbling'; });
p.addEventListener('click', () => { outputDivF.textContent += '\nParent Bubbling'; });
c.addEventListener('click', (event) => {
    outputDivF.textContent = '--- 이벤트 흐름 시작 ---'; // 로그 초기화
    outputDivF.textContent += '\nChild Bubbling (target)';
    // event.stopPropagation(); // 여기서 전파를 멈추면 부모 요소들의 버블링 핸들러는 실행되지 않음
});

// Child 버튼 클릭 시 출력 순서:
// --- 이벤트 흐름 시작 ---
// Grandparent Capturing
// Parent Capturing
// Child Capturing
// Child Bubbling (target)
// Parent Bubbling
// Grandparent Bubbling
</code></pre>
    <p>대부분의 경우 기본값인 버블링 단계에서 이벤트를 처리합니다. 캡처링 단계는 특별한 경우(예: 특정 요소에 도달하기 전에 이벤트 처리)에 사용될 수 있습니다.</p>
</section>

<section id="event-delegation">
    <h2>이벤트 위임 (Event Delegation)</h2>
    <p>이벤트 위임은 이벤트 버블링 원리를 활용하여, 여러 개의 하위 요소에 각각 이벤트 핸들러를 등록하는 대신 상위 요소에 하나의 핸들러만 등록하여 이벤트를 처리하는 기법입니다.</p>
    <p>상위 요소에 등록된 핸들러 내에서 <code>event.target</code>을 확인하여 이벤트가 실제로 발생한 하위 요소를 식별하고, 해당 요소에 맞는 처리를 수행합니다.</p>
    <h4>장점:</h4>
    <ul>
        <li><strong>성능 향상:</strong> 등록되는 이벤트 핸들러 수가 줄어들어 메모리 사용량이 감소하고 성능이 향상됩니다.</li>
        <li><strong>동적 요소 처리 용이:</strong> 페이지 로드 후 동적으로 추가되는 하위 요소에도 별도의 핸들러 등록 없이 이벤트 처리가 자동으로 적용됩니다.</li>
        <li><strong>코드 관리 용이:</strong> 이벤트 관련 코드가 한 곳에 모여 관리가 편해집니다.</li>
    </ul>
    <div class="example">
        <h4>이벤트 위임 예제용 HTML</h4>
        <ul id="delegation-list">
            <li>항목 1</li>
            <li>항목 2</li>
            <li>항목 3</li>
        </ul>
        <button id="btn-add-dynamic">동적 항목 추가</button>
        <div id="output-delegation" class="output">클릭된 항목:</div>
    </div>
    <pre><code class="language-javascript">const delegationList = document.getElementById('delegation-list');
const outputDivD = document.getElementById('output-delegation');

// 상위 요소(ul)에 하나의 이벤트 리스너 등록
delegationList.addEventListener('click', function(event) {
  // 클릭된 요소가 LI 태그인지 확인
  if (event.target && event.target.tagName === 'LI') {
    // event.target은 실제로 클릭된 li 요소를 가리킴
    outputDivD.textContent = `클릭된 항목: ${event.target.textContent}`;
    event.target.style.fontWeight = 'bold'; // 예시: 클릭된 항목 굵게

    // 이전에 굵게 표시된 항목 스타일 초기화 (선택 사항)
    // Array.from(this.children).forEach(li => {
    //   if (li !== event.target) li.style.fontWeight = 'normal';
    // });
  }
});

// 동적으로 항목 추가하는 버튼
let dynamicItemCount = 3;
document.getElementById('btn-add-dynamic').addEventListener('click', () => {
  dynamicItemCount++;
  const newItem = document.createElement('li');
  newItem.textContent = `동적 항목 ${dynamicItemCount}`;
  delegationList.append(newItem);
  // 새로 추가된 항목에도 별도의 이벤트 리스너 등록 필요 없음!
});
</code></pre>
    <p>이벤트 위임은 특히 리스트나 테이블 등 동일한 종류의 하위 요소가 많은 경우 매우 효과적인 패턴입니다.</p>
</section>

<section id="common-event-types">
    <h2>주요 이벤트 타입</h2>
    <p>웹 개발에서 자주 사용되는 이벤트 타입들입니다.</p>
    <ul>
        <li><strong>마우스 이벤트:</strong>
            <ul>
                <li><code>click</code>: 마우스 버튼 클릭 시.</li>
                <li><code>dblclick</code>: 마우스 버튼 더블 클릭 시.</li>
                <li><code>mousedown</code> / <code>mouseup</code>: 마우스 버튼을 누르거나 뗄 때.</li>
                <li><code>mouseover</code> / <code>mouseout</code>: 마우스 포인터가 요소 위로 올라오거나 벗어날 때 (버블링 발생).</li>
                <li><code>mouseenter</code> / <code>mouseleave</code>: 마우스 포인터가 요소 영역으로 들어오거나 나갈 때 (버블링 없음, 더 자주 사용됨).</li>
                <li><code>mousemove</code>: 마우스 포인터가 요소 위에서 움직일 때.</li>
            </ul>
        </li>
        <li><strong>키보드 이벤트:</strong>
            <ul>
                <li><code>keydown</code>: 키를 누르고 있을 때 (누르는 동안 계속 발생 가능).</li>
                <li><code>keyup</code>: 눌렀던 키를 뗄 때.</li>
                <li><code>keypress</code>: (Deprecated) 문자 키가 눌렸을 때. <code>keydown</code> 사용 권장.</li>
            </ul>
        </li>
        <li><strong>폼 이벤트:</strong>
            <ul>
                <li><code>submit</code>: 폼이 제출될 때 (<code>&lt;form&gt;</code> 요소에서 발생). <code>event.preventDefault()</code>로 기본 제출 동작 막기 가능.</li>
                <li><code>change</code>: 폼 요소(<code>&lt;input&gt;</code>, <code>&lt;select&gt;</code>, <code>&lt;textarea&gt;</code>)의 값이 변경되고 포커스를 잃었을 때. (checkbox/radio는 즉시 발생)</li>
                <li><code>input</code>: 폼 요소의 값이 변경될 때마다 실시간으로 발생 (<code>&lt;textarea&gt;</code>, <code>&lt;input type="text"&gt;</code> 등).</li>
                <li><code>focus</code>: 요소가 포커스를 얻었을 때.</li>
                <li><code>blur</code>: 요소가 포커스를 잃었을 때.</li>
            </ul>
        </li>
         <li><strong>윈도우/문서 이벤트:</strong>
            <ul>
                <li><code>load</code>: 페이지의 모든 리소스(이미지, 스타일시트 등) 로드가 완료되었을 때 (<code>window</code> 객체에서 발생).</li>
                <li><code>DOMContentLoaded</code>: HTML 문서 파싱이 완료되고 DOM 트리가 완성되었을 때 (이미지 등 외부 리소스 로드 기다리지 않음, <code>document</code> 객체에서 발생). 스크립트 실행 시점으로 더 선호됨.</li>
                <li><code>resize</code>: 브라우저 창 크기가 변경될 때 (<code>window</code> 객체).</li>
                <li><code>scroll</code>: 문서나 특정 요소가 스크롤될 때 (<code>window</code> 또는 스크롤 가능한 요소).</li>
                <li><code>beforeunload</code> / <code>unload</code>: 사용자가 페이지를 떠나려고 할 때 (<code>window</code> 객체).</li>
            </ul>
        </li>
    </ul>
    <p>이 외에도 터치 이벤트, 드래그 앤 드롭 이벤트, 미디어 이벤트 등 다양한 종류의 이벤트가 있습니다.</p>
</section>

<hr>

<section id="async-intro">
  <h2>비동기 JavaScript 소개</h2>
  <p>JavaScript는 기본적으로 싱글 스레드(Single Thread) 기반 언어로, 한 번에 하나의 작업만 처리할 수 있습니다. 코드가 위에서 아래로 순차적으로 실행되는 것을 동기적(Synchronous) 처리라고 합니다.</p>
  <p>만약 시간이 오래 걸리는 작업(예: 네트워크 요청, 큰 파일 읽기)을 동기적으로 처리하면, 해당 작업이 끝날 때까지 다음 코드가 실행되지 못하고 멈추게 됩니다(<strong>블로킹(Blocking)</strong>). 이는 특히 웹 브라우저 환경에서 사용자 인터페이스(UI)가 멈추는 등 사용자 경험을 크게 저해합니다.</p>
  <p><strong>비동기(Asynchronous)</strong> 처리는 이러한 블로킹 문제를 해결하기 위한 방식입니다. 시간이 오래 걸리는 작업을 백그라운드(브라우저 API 또는 Node.js의 libuv)에 위임하고, 그 작업이 완료되기를 기다리는 동안 다른 코드를 계속 실행합니다. 작업이 완료되면 결과를 받아서 처리할 수 있도록 약속(예: 콜백 함수, Promise)을 합니다.</p>
  <p class="note">JavaScript 런타임 환경(브라우저, Node.js)은 이벤트 루프(Event Loop), 콜 스택(Call Stack), 태스크 큐(Task Queue) 등을 통해 비동기 작업을 관리합니다. 비동기 함수 호출 시 작업은 Web API(브라우저) 등으로 넘겨지고, 콜 스택은 비워져 다른 작업을 처리합니다. Web API에서 작업이 완료되면 콜백 함수나 Promise 처리 함수가 태스크 큐로 이동하고, 이벤트 루프는 콜 스택이 비었을 때 큐에서 함수를 가져와 콜 스택에 넣고 실행합니다.</p>
</section>

<section id="callbacks">
  <h2>콜백 함수 (Callbacks)</h2>
  <p>콜백 함수는 다른 함수의 인수로 전달되어, 특정 작업(주로 비동기 작업)이 완료된 후에 호출되는 함수입니다. 비동기 처리의 가장 기본적인 패턴입니다.</p>
  <pre><code class="language-javascript">// 예시: setTimeout (지정된 시간 후에 콜백 함수 실행)
console.log("작업 시작");

setTimeout(function() { // 2초 후에 실행될 콜백 함수
  console.log("2초 후 작업 완료!");
}, 2000); // 2000ms = 2초

console.log("다른 작업 계속 실행");

// 출력 순서:
// 작업 시작
// 다른 작업 계속 실행
// (2초 후) 2초 후 작업 완료!
</code></pre>
  <p><strong>콜백 지옥 (Callback Hell) / 파멸의 피라미드 (Pyramid of Doom):</strong><br>
  비동기 작업이 여러 개 중첩될 경우, 콜백 함수 안에 또 다른 콜백 함수가 들어가는 형태가 반복되어 코드의 들여쓰기가 깊어지고 가독성과 유지보수성이 급격히 떨어지는 문제가 발생할 수 있습니다.</p>
  <pre><code class="language-javascript">// 콜백 지옥 예시 (가상 코드)
step1(function(result1) {
  step2(result1, function(result2) {
    step3(result2, function(result3) {
      step4(result3, function(result4) {
        // ... 계속 중첩 ...
        console.log("모든 단계 완료!");
      });
    });
  });
});</code></pre>
  <p>이러한 문제를 해결하기 위해 Promise와 async/await가 등장했습니다.</p>
</section>

<section id="promises">
  <h2>프로미스 (Promises - ES6+)</h2>
  <p>Promise는 비동기 작업의 최종 완료(또는 실패)와 그 결과 값을 나타내는 객체입니다. 콜백 함수 대신 비동기 작업의 상태와 결과를 더 명확하게 관리하고, 콜백 지옥 문제를 해결하는 데 도움을 줍니다.</p>
  <p>Promise는 세 가지 상태를 가집니다:</p>
  <ul>
    <li><strong>Pending (대기):</strong> 비동기 작업이 아직 완료되지 않은 초기 상태.</li>
    <li><strong>Fulfilled (이행):</strong> 비동기 작업이 성공적으로 완료된 상태. 결과 값을 가집니다.</li>
    <li><strong>Rejected (거부):</strong> 비동기 작업이 실패한 상태. 실패 이유(Error 객체)를 가집니다.</li>
  </ul>
  <p>Promise 객체는 주로 <code>.then()</code>, <code>.catch()</code>, <code>.finally()</code> 메서드를 사용하여 후속 처리를 연결합니다.</p>
  <ul>
    <li><code>promise.then(onFulfilled, onRejected)</code>: Promise가 이행(fulfilled)되면 <code>onFulfilled</code> 함수가 호출되고, 거부(rejected)되면 <code>onRejected</code> 함수(선택 사항)가 호출됩니다. <code>.then()</code>은 새로운 Promise를 반환하여 체이닝(chaining)이 가능합니다.</li>
    <li><code>promise.catch(onRejected)</code>: Promise가 거부(rejected)될 때만 호출되는 에러 처리 함수를 등록합니다. <code>.then(null, onRejected)</code>와 유사합니다.</li>
    <li><code>promise.finally(onFinally)</code>: Promise가 이행되든 거부되든 상관없이 항상 마지막에 호출되는 함수를 등록합니다. (리소스 정리 등에 사용)</li>
  </ul>

  <pre><code class="language-javascript">// Promise 생성 예시 (비동기 작업을 Promise로 감싸기)
function fetchData(shouldSucceed) {
  return new Promise((resolve, reject) => {
    console.log("데이터 가져오는 중...");
    setTimeout(() => {
      if (shouldSucceed) {
        const data = { id: 1, content: "성공 데이터" };
        resolve(data); // 성공 시 resolve 호출 (상태: fulfilled)
      } else {
        const error = new Error("데이터 가져오기 실패!");
        reject(error); // 실패 시 reject 호출 (상태: rejected)
      }
    }, 1500); // 1.5초 후 완료
  });
}

// Promise 사용 예시 (성공 케이스)
fetchData(true)
  .then(data => { // 첫 번째 .then (onFulfilled)
    console.log("성공:", data);
    // 여기서 또 다른 Promise를 반환하여 체이닝 가능
    return `처리된 데이터: ${data.content}`;
  })
  .then(processedData => { // 두 번째 .then (이전 then의 반환값을 받음)
    console.log("후속 처리:", processedData);
  })
  .catch(error => { // 실패 시 실행될 .catch
    console.error("실패:", error.message);
  })
  .finally(() => { // 성공/실패 여부와 관계없이 항상 실행
    console.log("Promise 처리 완료.");
  });

// Promise 사용 예시 (실패 케이스)
/*
fetchData(false)
  .then(data => {
    console.log("성공:", data); // 실행되지 않음
  })
  .catch(error => { // 실패했으므로 .catch 실행
    console.error("실패:", error.message);
  })
  .finally(() => {
    console.log("Promise 처리 완료.");
  });
*/
</code></pre>

  <h4>Promise 정적 메서드</h4>
  <ul>
    <li><code>Promise.all(iterable)</code>: 여러 개의 Promise를 동시에 실행하고, 모든 Promise가 이행될 때까지 기다렸다가 결과들을 배열로 반환하는 새로운 Promise를 반환합니다. 하나라도 거부되면 즉시 거부됩니다.</li>
    <li><code>Promise.race(iterable)</code>: 여러 개의 Promise 중 가장 먼저 이행되거나 거부되는 Promise의 결과(또는 에러)를 그대로 따르는 새로운 Promise를 반환합니다.</li>
    <li><code>Promise.resolve(value)</code>: 주어진 값으로 이행되는 Promise를 반환합니다.</li>
    <li><code>Promise.reject(reason)</code>: 주어진 이유로 거부되는 Promise를 반환합니다.</li>
  </ul>
   <pre><code class="language-javascript">const p1 = Promise.resolve("첫 번째 성공");
const p2 = new Promise(resolve => setTimeout(() => resolve("두 번째 성공 (시간 걸림)"), 1000));
const p3 = Promise.reject("세 번째 실패");

// Promise.all 예시
Promise.all([p1, p2])
  .then(results => console.log("Promise.all 성공:", results)) // ["첫 번째 성공", "두 번째 성공 (시간 걸림)"]
  .catch(error => console.error("Promise.all 실패:", error));

Promise.all([p1, p2, p3])
  .then(results => console.log("Promise.all 성공:", results))
  .catch(error => console.error("Promise.all 실패:", error)); // "세 번째 실패" (하나라도 실패하면 catch)

// Promise.race 예시
Promise.race([p2, p3]) // p2(1초)보다 p3(즉시 실패)가 빠름
  .then(result => console.log("Promise.race 성공:", result))
  .catch(error => console.error("Promise.race 실패:", error)); // "세 번째 실패"
</code></pre>
</section>

<section id="async-await">
  <h2>async / await (ES2017+)</h2>
  <p><code>async</code>와 <code>await</code>는 Promise를 기반으로 동작하며, 비동기 코드를 마치 동기 코드처럼 더 읽기 쉽고 간결하게 작성할 수 있도록 도와주는 문법적 설탕(syntactic sugar)입니다.</p>
  <ul>
    <li><strong><code>async function</code>:</strong> 함수 앞에 <code>async</code> 키워드를 붙이면 해당 함수는 항상 Promise를 반환합니다. 함수 내에서 명시적으로 Promise를 반환하지 않더라도, 반환값은 자동으로 Promise.resolve()로 감싸집니다.</li>
    <li><strong><code>await</code>:</strong> <code>await</code> 키워드는 <strong><code>async</code> 함수 내부에서만</strong> 사용할 수 있으며, Promise가 완료(이행 또는 거부)될 때까지 함수의 실행을 일시 중지합니다. Promise가 이행되면 그 결과 값을 반환하고, 거부되면 에러를 발생시킵니다(throw).</li>
  </ul>
  <p><code>await</code>를 사용하면 <code>.then()</code> 체인 대신 변수에 결과 값을 직접 할당하는 것처럼 코드를 작성할 수 있습니다.</p>
  <p>에러 처리는 주로 <code>try...catch</code> 문을 사용합니다.</p>
  <pre><code class="language-javascript">// 이전 fetchData 함수를 async/await와 함께 사용

async function processData() {
  console.log("데이터 처리 시작 (async/await)");
  try {
    // await는 fetchData(true) Promise가 완료될 때까지 기다림
    const data = await fetchData(true); // Promise가 이행되면 결과값이 data에 할당됨
    console.log("데이터 수신 성공:", data);

    // 결과값을 이용한 추가 작업
    const processedData = `async/await 처리된 데이터: ${data.content}`;
    console.log("후속 처리:", processedData);

    // 실패 케이스 시뮬레이션
    // const failedData = await fetchData(false);
    // console.log("실패 데이터:", failedData); // 이 줄은 실행되지 않고 catch 블록으로 이동

    return processedData; // async 함수는 Promise를 반환

  } catch (error) { // await가 기다린 Promise가 거부되면 에러 발생 -> catch 블록 실행
    console.error("데이터 처리 중 오류 발생:", error.message);
    // 에러 상황에 맞는 값 반환 또는 다른 에러 처리 가능
    return "처리 실패";
  } finally {
      console.log("데이터 처리 종료 (async/await)");
  }
}

// async 함수 호출 (Promise 반환)
processData()
  .then(result => console.log("최종 결과:", result))
  .catch(error => console.error("최종 에러:", error)); // async 함수 내에서 처리되지 않은 에러

</code></pre>
  <p><code>async/await</code>는 비동기 코드를 동기 코드처럼 보이게 하여 가독성을 크게 향상시키므로, 현대 JavaScript 비동기 처리의 표준 방식으로 자리 잡고 있습니다.</p>
</section>


<section id="networking-fetch">
  <h2>네트워크 요청 (Fetch API)</h2>
  <p>웹 페이지는 종종 서버와 데이터를 주고받아야 합니다(예: API 호출). <strong>Fetch API</strong>는 네트워크 요청 및 응답을 처리하는 현대적이고 강력한 인터페이스를 제공합니다. Promise를 기반으로 동작합니다.</p>
  <p>기본적인 사용법은 <code>fetch(url, [options])</code> 형태입니다.</p>
  <ul>
      <li><code>url</code>: 요청을 보낼 URL.</li>
      <li><code>options</code> (선택 사항): 요청 메서드(<code>method</code>: 'GET', 'POST', 'PUT', 'DELETE' 등), 헤더(<code>headers</code>), 본문(<code>body</code>) 등을 설정하는 객체.</li>
  </ul>
  <p><code>fetch()</code> 함수는 HTTP 응답을 나타내는 <strong>Response 객체</strong>로 이행되는 Promise를 반환합니다. 이 Response 객체를 사용하여 실제 데이터를 추출해야 합니다.</p>
  <ul>
      <li><code>response.ok</code>: HTTP 상태 코드가 성공 범위(200-299)인지 나타내는 불리언 값.</li>
      <li><code>response.status</code>: HTTP 상태 코드 (예: 200, 404, 500).</li>
      <li><code>response.json()</code>: 응답 본문을 JSON으로 파싱하여 JavaScript 객체로 변환하는 Promise 반환.</li>
      <li><code>response.text()</code>: 응답 본문을 텍스트로 변환하는 Promise 반환.</li>
      <li><code>response.blob()</code>: 응답 본문을 Blob(Binary Large Object)으로 변환하는 Promise 반환 (이미지 등).</li>
  </ul>

  <pre><code class="language-javascript">// 예시: JSONPlaceholder API에서 사용자 데이터 가져오기 (GET 요청)
const apiUrl = 'https://jsonplaceholder.typicode.com/users/1';

fetch(apiUrl) // GET 요청은 기본값
  .then(response => {
    console.log("Response Status:", response.status); // 응답 상태 코드 확인
    if (!response.ok) { // HTTP 에러 처리 (4xx, 5xx 등)
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json(); // 응답 본문을 JSON으로 파싱 (이것도 Promise 반환)
  })
  .then(userData => { // JSON 파싱 완료 후 실제 데이터(객체) 받음
    console.log("사용자 데이터:", userData);
    // 예: 사용자 이름 출력
    document.getElementById('output-fetch').textContent = `사용자 이름: ${userData.name}`;
  })
  .catch(error => { // 네트워크 오류 또는 위의 throw new Error 처리
    console.error("Fetch 오류:", error);
    document.getElementById('output-fetch').textContent = `데이터 로딩 실패: ${error.message}`;
  });


// 예시: 데이터 생성 (POST 요청)
const newUser = {
  name: "New User",
  username: "newuser",
  email: "new@example.com"
};

/*
fetch('https://jsonplaceholder.typicode.com/users', {
  method: 'POST', // 요청 메서드
  headers: {
    'Content-Type': 'application/json' // 보내는 데이터 타입 명시
  },
  body: JSON.stringify(newUser) // JavaScript 객체를 JSON 문자열로 변환하여 body에 담음
})
.then(response => response.json())
.then(createdUser => {
  console.log("생성된 사용자:", createdUser); // 서버가 생성된 사용자 정보 반환 (ID 포함)
})
.catch(error => console.error("POST 요청 오류:", error));
*/
</code></pre>
   <div class="example">
        <h4>Fetch API 예제 결과</h4>
        <div id="output-fetch" class="output">데이터 로딩 중...</div>
    </div>
  <p class="note">실제 서비스에서는 CORS(Cross-Origin Resource Sharing) 정책, 인증 처리 등 고려해야 할 사항이 더 있습니다.</p>
</section>

<section id="json">
    <h2>JSON (JavaScript Object Notation)</h2>
    <p>JSON은 데이터를 교환하기 위한 경량의 텍스트 기반 형식입니다. JavaScript 객체 리터럴 문법과 매우 유사하지만, 몇 가지 차이점이 있습니다.</p>
    <ul>
        <li>키(key)는 반드시 큰따옴표(<code>""</code>)로 감싸야 합니다.</li>
        <li>값(value)은 문자열(큰따옴표 사용), 숫자, 불리언, 배열, 다른 JSON 객체, <code>null</code>만 가능합니다. (함수, <code>undefined</code>, <code>Date</code> 객체 등은 직접 표현 불가)</li>
        <li>주석을 사용할 수 없습니다.</li>
    </ul>
    <p>JSON은 주로 서버와 클라이언트 간 데이터 전송, 설정 파일 등에서 널리 사용됩니다.</p>
    <p>JavaScript는 JSON 처리를 위한 내장 <code>JSON</code> 객체를 제공합니다.</p>
    <ul>
        <li><code>JSON.stringify(value, replacer, space)</code>: JavaScript 값(객체, 배열 등)을 JSON 형식의 문자열로 변환합니다.
            <ul>
                <li><code>replacer</code> (선택 사항): 변환 동작을 변경하는 함수 또는 포함할 속성 이름 배열.</li>
                <li><code>space</code> (선택 사항): 가독성을 위한 공백(숫자) 또는 문자열 삽입.</li>
            </ul>
        </li>
        <li><code>JSON.parse(text, reviver)</code>: JSON 형식의 문자열을 JavaScript 값(객체, 배열 등)으로 파싱합니다.
            <ul>
                <li><code>reviver</code> (선택 사항): 파싱된 값을 변형하는 함수.</li>
            </ul>
        </li>
    </ul>
    <pre><code class="language-javascript">const book = {
  title: "모던 JavaScript 튜토리얼",
  author: "코드마스터",
  year: 2025,
  chapters: ["기본", "DOM", "비동기"],
  isPublished: true,
  price: null // null 값 가능
  // description: undefined, // undefined는 JSON으로 변환 시 제외됨
  // printInfo: function() {} // 함수는 제외됨
};

// JavaScript 객체 -> JSON 문자열
const jsonString = JSON.stringify(book, null, 2); // null, 2는 가독성을 위한 들여쓰기 옵션
console.log("JSON 문자열:");
console.log(jsonString);
/* 출력 예시:
{
  "title": "모던 JavaScript 튜토리얼",
  "author": "코드마스터",
  "year": 2025,
  "chapters": [
    "기본",
    "DOM",
    "비동기"
  ],
  "isPublished": true,
  "price": null
}
*/

// JSON 문자열 -> JavaScript 객체
const jsonReceived = `{
  "id": 101,
  "productName": "노트북",
  "inStock": true,
  "tags": ["computer", "electronics"]
}`;

try {
  const product = JSON.parse(jsonReceived);
  console.log("\n파싱된 객체:");
  console.log(product);
  console.log(product.productName); // "노트북"
} catch (error) { // 잘못된 JSON 형식일 경우 에러 발생
  console.error("JSON 파싱 오류:", error);
}
</code></pre>
</section>

<section id="es6-modules">
    <h2>ES6 모듈 (Modules)</h2>
    <p>모듈(Module)은 JavaScript 코드를 별도의 파일로 분리하여 작성하고, 필요에 따라 다른 파일에서 가져와(<code>import</code>) 사용할 수 있게 하는 시스템입니다. 모듈을 사용하면 다음과 같은 장점이 있습니다:</p>
    <ul>
        <li><strong>코드 구성 및 재사용성 향상:</strong> 관련 있는 코드들을 파일 단위로 묶어 관리.</li>
        <li><strong>스코프 분리:</strong> 모듈 스코프는 전역 스코프와 분리되어 변수 이름 충돌 방지.</li>
        <li><strong>의존성 관리:</strong> 파일 간의 의존 관계를 명확히 할 수 있음.</li>
    </ul>
    <p>ES6 모듈 시스템은 <code>export</code>와 <code>import</code> 키워드를 사용합니다.</p>
    <ul>
        <li><strong><code>export</code> (내보내기):</strong> 모듈 파일 내에서 다른 모듈이 사용할 수 있도록 변수, 함수, 클래스 등을 내보냅니다.
            <ul>
                <li><strong>Named Export (이름 지정 내보내기):</strong> 여러 개를 내보낼 수 있으며, 가져올 때(import) 해당 이름을 사용해야 합니다.
                    <pre><code class="language-javascript">// utils.js
export const PI = 3.14;
export function double(n) { return n * 2; }
export class Helper { /* ... */ }

// 또는 아래처럼 한 번에 내보내기
const E = 2.71;
function triple(n) { return n * 3; }
// export { E, triple };</code></pre>
                </li>
                <li><strong>Default Export (기본 내보내기):</strong> 모듈 당 하나만 가능하며, 가져올 때(import) 원하는 이름을 사용할 수 있습니다.
                    <pre><code class="language-javascript">// myModule.js
export default function myFunction() {
  console.log("Default Export Function");
}
// export default class MyClass { /* ... */ }
// const myValue = 100; export default myValue;</code></pre>
                </li>
            </ul>
        </li>
        <li><strong><code>import</code> (가져오기):</strong> 다른 모듈에서 내보낸 기능을 현재 모듈로 가져옵니다. <code>import</code> 문은 일반적으로 모듈 최상단에 위치합니다.
            <ul>
                <li><strong>Named Import:</strong> <code>export</code>된 이름을 중괄호(<code>{}</code>) 안에 명시하여 가져옵니다. 이름을 변경하려면 <code>as</code> 키워드를 사용합니다.
                    <pre><code class="language-javascript">// main.js
import { PI, double, Helper } from './utils.js';
// import { E as Euler, triple } from './utils.js'; // 이름 변경 예시

console.log(PI);
console.log(double(5));
// console.log(Euler);</code></pre>
                </li>
                <li><strong>Default Import:</strong> 중괄호 없이 원하는 이름을 사용하여 기본 내보내기를 가져옵니다.
                    <pre><code class="language-javascript">// main.js
import myFunc from './myModule.js'; // 이름은 자유롭게 지정 가능

myFunc();</code></pre>
                </li>
                <li><strong>Namespace Import:</strong> 모듈에서 내보낸 모든 named export를 하나의 객체로 가져옵니다.
                    <pre><code class="language-javascript">// main.js
import * as utils from './utils.js';

console.log(utils.PI);
console.log(utils.double(10));</code></pre>
                </li>
                 <li><strong>Side Effect Import:</strong> 모듈의 코드를 실행만 하고 아무것도 가져오지 않을 때 사용합니다 (예: 전역 설정 적용).
                    <pre><code class="language-javascript">// main.js
import './setup.js'; // setup.js 파일의 코드를 실행
</code></pre></li>
            </ul>
        </li>
    </ul>
    <h4>HTML에서 모듈 사용하기</h4>
    <p>브라우저에서 ES6 모듈을 사용하려면 <code>&lt;script&gt;</code> 태그에 <code>type="module"</code> 속성을 추가해야 합니다.</p>
    <pre><code class="language-html">&lt;!-- 모듈 로드 --&gt;
&lt;script type="module" src="main.js"&gt;&lt;/script&gt;

&lt;!-- 인라인 모듈 스크립트 --&gt;
&lt;script type="module"&gt;
  import { someFunction } from './anotherModule.js';
  someFunction();
&lt;/script&gt;
</code></pre>
    <p class="note">모듈 내부의 코드는 기본적으로 'strict mode'로 실행되며, 최상위 레벨의 `this`는 `undefined`입니다. 또한, 모듈은 브라우저에서 기본적으로 한 번만 로드되고 실행됩니다.</p>
    <p>복잡한 프로젝트에서는 여러 모듈 파일과 의존성을 관리하기 위해 모듈 번들러(Module Bundler) (예: Webpack, Parcel, Rollup)를 사용하는 경우가 많습니다. 번들러는 여러 모듈 파일을 하나 또는 몇 개의 파일로 묶어주고, 코드 최적화, 구형 브라우저 호환성 처리 등의 작업을 수행합니다.</p>
</section>

<section id="error-handling">
    <h2>에러 처리 (Error Handling)</h2>
    <p>프로그램 실행 중 예기치 않은 문제가 발생하면 에러(Error)가 발생하여 프로그램이 중단될 수 있습니다. 에러 처리는 이러한 에러에 대처하여 프로그램이 비정상적으로 종료되는 것을 막고, 사용자에게 적절한 피드백을 제공하거나 복구 작업을 수행하기 위한 중요한 과정입니다.</p>

    <h3><code>try...catch...finally</code> 문</h3>
    <p>JavaScript의 기본적인 에러 처리 구문입니다.</p>
    <ul>
        <li><code>try { ... }</code>: 에러가 발생할 가능성이 있는 코드를 이 블록 안에 작성합니다.</li>
        <li><code>catch (error) { ... }</code>: <code>try</code> 블록 내에서 에러가 발생하면, 프로그램 실행이 <code>catch</code> 블록으로 즉시 이동합니다. <code>error</code> 매개변수에는 발생한 에러 객체가 전달됩니다. (<code>catch</code> 블록은 선택 사항이지만, <code>try</code>만 단독으로 쓸 수는 없음)</li>
        <li><code>finally { ... }</code>: 에러 발생 여부와 관계없이 <code>try</code> 또는 <code>catch</code> 블록 실행 후 항상 실행되는 코드 블록입니다. 주로 자원 해제 등 마무리 작업에 사용됩니다. (선택 사항)</li>
    </ul>
    <pre><code class="language-javascript">console.log("에러 처리 시작");

try {
  console.log("try 블록 시작");
  // 잠재적 에러 발생 코드
  let result = someUndefinedFunction(); // ReferenceError 발생
  console.log("이 코드는 실행되지 않음"); // 에러 발생 시 이후 코드는 건너뜀
} catch (error) { // 에러 발생 시 실행됨
  console.error("catch 블록 실행됨!");
  console.error("에러 이름:", error.name); // 예: "ReferenceError"
  console.error("에러 메시지:", error.message); // 예: "someUndefinedFunction is not defined"
  // console.error("스택 트레이스:", error.stack); // 에러 발생 경로 추적 정보
} finally { // 항상 실행됨
  console.log("finally 블록 실행됨 (마무리 작업)");
}

console.log("에러 처리 후 계속 실행");
</code></pre>

    <h3><code>throw</code> 문</h3>
    <p>개발자가 의도적으로 에러를 발생시킬 때 사용합니다. 주로 사용자 정의 에러를 만들거나 특정 조건에서 예외 상황임을 알릴 때 사용합니다.</p>
    <p><code>throw expression;</code> (<code>expression</code>은 보통 <code>new Error(...)</code> 객체)</p>
    <pre><code class="language-javascript">function divide(a, b) {
  if (b === 0) {
    // 0으로 나누는 것은 에러 상황이므로 에러를 발생시킴
    throw new Error("0으로 나눌 수 없습니다!");
  }
  return a / b;
}

try {
  let result1 = divide(10, 2);
  console.log("결과 1:", result1); // 5

  let result2 = divide(5, 0); // 여기서 에러 발생(throw)
  console.log("결과 2:", result2); // 실행 안 됨

} catch (error) {
  console.error("나눗셈 오류:", error.message); // "0으로 나눌 수 없습니다!"
}
</code></pre>

    <h3>Error 객체</h3>
    <p>JavaScript에는 다양한 종류의 내장 에러 객체가 있습니다 (<code>Error</code>, <code>SyntaxError</code>, <code>ReferenceError</code>, <code>TypeError</code>, <code>RangeError</code>, <code>URIError</code> 등). <code>new Error('메시지')</code>를 사용하여 기본적인 에러 객체를 만들 수 있으며, <code>name</code>과 <code>message</code> 프로퍼티를 가집니다.</p>
</section>

<section id="regex-intro">
  <h2>정규 표현식 기초 (Regular Expressions Intro)</h2>
  <p>정규 표현식(Regular Expression, Regex 또는 RegExp)은 문자열에서 특정 패턴을 검색, 매칭, 치환하는 데 사용되는 강력한 도구입니다.</p>
  <p>JavaScript에서 정규 표현식은 두 가지 방법으로 생성할 수 있습니다:</p>
  <ul>
      <li><strong>리터럴 방식:</strong> 슬래시(<code>/</code>) 사이에 패턴을 작성합니다. <code>/pattern/flags</code></li>
      <li><strong>생성자 방식:</strong> <code>new RegExp('pattern', 'flags')</code></li>
  </ul>
  <p><strong>플래그(Flags):</strong></p>
  <ul>
      <li><code>g</code> (Global): 문자열 전체에서 패턴과 일치하는 모든 부분을 찾습니다. (없으면 첫 번째 일치만 찾음)</li>
      <li><code>i</code> (Ignore case): 대소문자를 구분하지 않고 검색합니다.</li>
      <li><code>m</code> (Multiline): 여러 줄 모드. <code>^</code>와 <code>$</code>가 각 줄의 시작/끝에 대응하도록 합니다.</li>
  </ul>
   <p><strong>주요 메서드:</strong></p>
   <ul>
       <li><code>regex.test(string)</code>: 문자열이 정규식 패턴과 일치하는지 불리언 값으로 반환.</li>
       <li><code>regex.exec(string)</code>: 문자열에서 패턴과 일치하는 부분을 찾아 상세 정보(배열 또는 null) 반환. <code>g</code> 플래그와 함께 사용하여 모든 일치 찾기 가능.</li>
       <li><code>string.match(regex)</code>: 문자열에서 패턴과 일치하는 부분을 찾아 배열(또는 null)로 반환. <code>g</code> 플래그 유무에 따라 동작 방식 다름.</li>
       <li><code>string.search(regex)</code>: 문자열에서 패턴과 처음 일치하는 부분의 인덱스 반환. 없으면 -1 반환.</li>
       <li><code>string.replace(regex, replacement)</code>: 문자열에서 패턴과 일치하는 부분을 찾아 `replacement`(문자열 또는 함수)로 치환한 새 문자열 반환.</li>
       <li><code>string.split(regex)</code>: 정규식 패턴을 기준으로 문자열을 분할하여 배열로 반환.</li>
   </ul>

  <h4>간단한 패턴 예시:</h4>
  <pre><code class="language-javascript">let text = "Hello World! hello javascript.";

// 'hello' 문자열 찾기 (대소문자 구분 X, 전역 검색)
const regexHello = /hello/gi;

console.log(regexHello.test(text)); // true (일치하는 부분이 있음)

console.log(text.match(regexHello)); // [ 'Hello', 'hello' ] (g 플래그로 모든 일치 찾음)

// 숫자가 있는지 확인
const regexDigits = /\d/; // \d는 숫자 하나를 의미
console.log(regexDigits.test("abc123xyz")); // true

// 이메일 형식 검사 (간단한 예시)
const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
console.log(regexEmail.test("test@example.com")); // true
console.log(regexEmail.test("invalid-email")); // false

// 공백으로 시작하거나 끝나는지 확인
const regexSpaces = /^\s|\s$/;
console.log(regexSpaces.test("  leading space")); // true
console.log(regexSpaces.test("trailing space  ")); // true
console.log(regexSpaces.test("no surrounding spaces")); // false

// 문자열 치환
let replacedText = text.replace(/javascript/i, "JS"); // 대소문자 구분 없이 'javascript'를 'JS'로 변경
console.log(replacedText); // "Hello World! hello JS."
</code></pre>
  <p class="note">정규 표현식은 매우 강력하지만 문법이 복잡할 수 있습니다. 필요할 때마다 특정 패턴을 검색하여 학습하는 것이 효율적입니다.</p>
</section>

<section id="next-steps">
  <h2>마무리 및 다음 단계</h2>
  <p>JavaScript의 기본 문법부터 객체, 배열, 함수, DOM 조작, 이벤트 처리, 비동기 프로그래밍, 그리고 몇 가지 고급 주제까지 폭넓게 학습했습니다. 이 정도면 웹 페이지를 동적으로 만들고 상호작용을 구현하는 데 필요한 탄탄한 기반을 갖추었다고 할 수 있습니다.</p>
  <p>하지만 JavaScript의 세계는 매우 넓고 깊습니다. 여기서 멈추지 말고 다음 단계로 나아가세요!</p>

  <h4>추천 학습 경로:</h4>
  <ol>
    <li><strong>꾸준한 연습 및 프로젝트 진행:</strong>
        <ul>
            <li>작은 웹 프로젝트(Todo 리스트, 계산기, 간단한 게임 등)를 직접 만들어보며 배운 내용을 적용하고 문제 해결 능력을 키우세요.</li>
            <li>온라인 코딩 챌린지 사이트(Codewars, LeetCode 등)에서 JavaScript 알고리즘 문제를 풀어보세요.</li>
        </ul>
    </li>
    <li><strong>JavaScript 심화 학습:</strong>
        <ul>
            <li>프로토타입과 상속(Prototype-based inheritance)</li>
            <li>ES6+의 더 많은 기능들 (Generators, Proxy, Reflect, Set, Map 등)</li>
            <li>성능 최적화 기법</li>
            <li>디자인 패턴 (Design Patterns)</li>
        </ul>
    </li>
    <li><strong>프론트엔드 프레임워크/라이브러리 학습:</strong>
        <ul>
            <li>React, Vue, Angular 와 같은 인기 있는 프론트엔드 프레임워크/라이브러리를 배우면 복잡한 사용자 인터페이스를 효율적으로 개발할 수 있습니다.</li>
        </ul>
    </li>
    <li><strong>백엔드 개발 (Node.js):</strong>
        <ul>
            <li>JavaScript를 사용하여 서버 측 개발을 하고 싶다면 Node.js 환경과 관련 프레임워크(Express 등)를 학습해 보세요.</li>
        </ul>
    </li>
    <li><strong>타입스크립트 (TypeScript):</strong>
        <ul>
            <li>JavaScript에 정적 타입을 추가한 TypeScript를 배우면 대규모 애플리케이션 개발 시 코드 안정성과 유지보수성을 높일 수 있습니다.</li>
        </ul>
    </li>
    <li><strong>다양한 Web APIs 탐구:</strong>
        <ul>
            <li>Web Storage (localStorage, sessionStorage)</li>
            <li>Geolocation API</li>
            <li>Canvas API (그래픽)</li>
            <li>Web Audio API</li>
            <li>Web Workers (백그라운드 스크립트)</li>
        </ul>
    </li>
  </ol>
  <p>가장 중요한 것은 꾸준히 코딩하고, 만들고 싶은 것을 직접 만들어보는 경험입니다. 막히는 부분이 있다면 구글 검색, Stack Overflow, 개발 커뮤니티 등을 적극 활용하세요.</p>
  <p class="note"><strong>이제 여러분은 JavaScript 개발자로서의 여정을 시작할 준비가 되었습니다. 즐겁게 코딩하세요!</strong></p>
</section>


<script src="../js/script.js?ver=1"></script>

</body>
</html>