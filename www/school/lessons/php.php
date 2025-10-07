<?php
// PHP 코드 실행 중 에러를 자세히 보기 위한 설정 (개발 시 유용)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP 강좌 </title>
  <link rel="stylesheet" href="../css/lessons_style.css?ver=1">
  <style>
    /* 기본 스타일 (강좌 페이지 자체 스타일) */
    body { font-family: sans-serif; line-height: 1.6; color: #333; }
    .toc { border: 1px solid #ccc; padding: 15px; margin-bottom: 30px; background-color: #f9f9f9; }
    .toc h2 { margin-top: 0; }
    .toc ul { list-style-type: disc; margin-left: 20px; }
    .toc ul ul { list-style-type: circle; margin-left: 20px; }
    h1, h2 { border-bottom: 2px solid #eee; padding-bottom: 5px; color: #2c3e50; }
    h2 { margin-top: 40px; }
    h3 { margin-top: 30px; border-bottom: 1px dashed #ccc; padding-bottom: 3px; color: #34495e;}
    h4 { margin-top: 25px; font-weight: bold; color: #7f8c8d; }
    pre { background-color: #f4f4f4; padding: 15px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; font-size: 0.9em; line-height: 1.4; }
    code.language-php { font-family: Consolas, 'Courier New', monospace; color: #c7254e; background-color: #f9f2f4; padding: 2px 4px; border-radius: 4px; }
    code.language-html { font-family: Consolas, 'Courier New', monospace; color: #333; }
    .example { border: 1px solid #ecf0f1; padding: 15px; margin-top: 10px; margin-bottom: 20px; background-color: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .example h4 { margin-top: 0; font-size: 1.1em; color: #3498db; }
    .output { background-color: #e9f6fe; border: 1px solid #bde0fe; padding: 10px; margin-top: 5px; border-radius: 3px; font-style: italic; font-size: 0.95em; white-space: pre-wrap; }
    .note { background-color: #fef9e7; border-left: 4px solid #f1c40f; padding: 10px 15px; margin: 15px 0; font-size: 0.95em; }
    .warning { background-color: #fdedec; border-left: 4px solid #e74c3c; padding: 10px 15px; margin: 15px 0; font-size: 0.95em; }

    /* TOC 활성 링크 스타일 */
    .toc a.active {
      color: #e74c3c;
      font-weight: bold;
    }
  </style>
</head>
<body>

<h1>PHP 강좌 </h1>
<p>이 페이지는 웹 개발에서 널리 사용되는 서버 사이드 스크립트 언어인 PHP의 기초부터 핵심 개념까지 다룹니다. 첫 번째 파트에서는 PHP 소개, 개발 환경 설정, 기본 문법, 변수, 상수, 데이터 타입, 그리고 연산자 사용법을 학습합니다.</p>

<div class="toc">
  <h2>📖 PHP 강좌 목차</h2>
  <ul>
    <li><a href="#intro-php">PHP 소개 및 환경 설정</a>
        <ul>
            <li><a href="#intro-what-is-php">PHP란? (서버 사이드 스크립트)</a></li>
            <li><a href="#intro-how-php-works">PHP 동작 방식</a></li>
            <li><a href="#intro-setup">개발 환경 설정 (XAMPP, MAMP, Docker 등)</a></li>
        </ul>
    </li>
    <li><a href="#php-syntax">기본 문법</a>
        <ul>
            <li><a href="#syntax-tags">PHP 태그 (<code>&lt;?php ... ?&gt;</code>)</a></li>
            <li><a href="#syntax-echo-print">출력 (echo, print)</a></li>
            <li><a href="#syntax-comments">주석 (Comments)</a></li>
            <li><a href="#syntax-case-sensitivity">대소문자 구분</a></li>
            <li><a href="#syntax-statements-semicolons">문장과 세미콜론</a></li>
            <li><a href="#syntax-embedding">HTML에 PHP 삽입</a></li>
        </ul>
    </li>
    <li><a href="#php-variables">변수 (Variables)</a>
        <ul>
            <li><a href="#variables-naming">변수 선언 및 이름 규칙 ($)</a></li>
            <li><a href="#variables-scope">변수 스코프 (지역, 전역, 정적)</a></li>
            <li><a href="#variables-superglobals">슈퍼글로벌 변수 (Superglobals)</a></li>
        </ul>
    </li>
     <li><a href="#php-constants">상수 (Constants)</a>
        <ul>
            <li><a href="#constants-define">define() 함수</a></li>
            <li><a href="#constants-const">const 키워드</a></li>
            <li><a href="#constants-magic">매직 상수 (Magic Constants)</a></li>
        </ul>
    </li>
    <li><a href="#php-data-types">데이터 타입 (Data Types)</a>
        <ul>
            <li><a href="#types-scalar">스칼라 타입 (Boolean, Integer, Float, String)</a></li>
            <li><a href="#types-compound">복합 타입 (Array, Object)</a></li>
            <li><a href="#types-special">특수 타입 (NULL, Resource)</a></li>
            <li><a href="#types-checking">타입 확인 (gettype(), is_*)</a></li>
            <li><a href="#types-casting">타입 캐스팅 (Type Casting)</a></li>
        </ul>
    </li>
    <li><a href="#php-operators">연산자 (Operators)</a>
        <ul>
            <li><a href="#operators-arithmetic">산술 연산자</a></li>
            <li><a href="#operators-assignment">할당 연산자</a></li>
            <li><a href="#operators-comparison">비교 연산자</a></li>
            <li><a href="#operators-logical">논리 연산자</a></li>
            <li><a href="#operators-increment-decrement">증감 연산자</a></li>
            <li><a href="#operators-string">문자열 연산자</a></li>
            <li><a href="#operators-array">배열 연산자</a></li>
            <li><a href="#operators-ternary">삼항 연산자</a></li>
            <li><a href="#operators-error-control">에러 제어 연산자 (@)</a></li>
            <li><a href="#operators-execution">실행 연산자 (백틱)</a></li>
            <li><a href="#operators-type">타입 연산자 (instanceof)</a></li>
            <li><a href="#operators-null-coalescing">Null 병합 연산자 (??)</a></li>
        </ul>
    </li>
    <li><a href="#php-control-structures">제어 구조 (Control Structures)</a>
        <ul>
            <li><a href="#control-conditional">조건문 (if, elseif, else, switch)</a></li>
            <li><a href="#control-loops">반복문 (for, while, do-while, foreach)</a></li>
            <li><a href="#control-break-continue">break, continue</a></li>
            <li><a href="#control-alternative-syntax">대체 문법</a></li>
        </ul>
    </li>
    <li><a href="#php-functions">함수 (Functions)</a>
        <ul>
            <li><a href="#functions-defining">함수 정의 및 호출</a></li>
            <li><a href="#functions-parameters">매개변수 및 인수 (기본값, 타입 힌팅, 가변 인자)</a></li>
            <li><a href="#functions-return">반환 값 (return, 반환 타입 힌팅)</a></li>
            <li><a href="#functions-scope">함수 내 변수 스코프 (global, static)</a></li>
            <li><a href="#functions-anonymous">익명 함수 (클로저)</a></li>
            <li><a href="#functions-arrow">화살표 함수 (PHP 7.4+)</a></li>
            <li><a href="#functions-built-in">내장 함수 활용</a></li>
        </ul>
    </li>
    <li><a href="#php-arrays">배열 (Arrays)</a>
        <ul>
            <li><a href="#arrays-types">배열 종류 (인덱스, 연관, 다차원)</a></li>
            <li><a href="#arrays-creating">배열 생성 (array(), [])</a></li>
            <li><a href="#arrays-accessing">요소 접근 및 조작</a></li>
            <li><a href="#arrays-looping">배열 순회 (foreach)</a></li>
            <li><a href="#arrays-functions">주요 배열 함수</a></li>
        </ul>
    </li>
    <li><a href="#php-web">웹 페이지 연동</a>
        <ul>
            <li><a href="#web-forms">HTML 폼 처리 (GET, POST)</a></li>
            <li><a href="#web-validation">폼 유효성 검사 및 데이터 정제</a></li>
            <li><a href="#web-urls">URL 파라미터 처리</a></li>
            <li><a href="#web-headers">헤더 조작 (header())</a></li>
            <li><a href="#web-include-require">파일 포함 (include, require)</a></li>
        </ul>
    </li>
     <li><a href="#php-state">상태 관리</a>
        <ul>
            <li><a href="#state-cookies">쿠키 (Cookies)</a></li>
            <li><a href="#state-sessions">세션 (Sessions)</a></li>
        </ul>
    </li>
    <li><a href="#php-files">파일 시스템</a>
        <ul>
            <li><a href="#files-reading">파일 읽기</a></li>
            <li><a href="#files-writing">파일 쓰기</a></li>
            <li><a href="#files-info">파일 정보 확인</a></li>
            <li><a href="#files-directories">디렉토리 다루기</a></li>
        </ul>
    </li>
    <li><a href="#php-uploads">파일 업로드 처리</a></li>
    <li><a href="#php-database">데이터베이스 연동 (MySQL)</a>
        <ul>
            <li><a href="#db-connect">연결 (MySQLi, PDO)</a></li>
            <li><a href="#db-querying">쿼리 실행 (query)</a></li>
            <li><a href="#db-prepared">Prepared Statements (SQL 인젝션 방지)</a></li>
            <li><a href="#db-fetching">데이터 가져오기 (fetch)</a></li>
            <li><a href="#db-insert-update-delete">데이터 삽입, 수정, 삭제</a></li>
            <li><a href="#db-closing">연결 종료</a></li>
        </ul>
    </li>
     <li><a href="#php-oop">객체 지향 프로그래밍 (OOP) 기초</a>
        <ul>
            <li><a href="#oop-classes-objects">클래스와 객체</a></li>
            <li><a href="#oop-properties-methods">속성(Properties)과 메서드(Methods)</a></li>
            <li><a href="#oop-visibility">접근 제어 (Visibility)</a></li>
            <li><a href="#oop-constructor-destructor">생성자 및 소멸자</a></li>
            <li><a href="#oop-this">$this</a></li>
            <li><a href="#oop-inheritance">상속 (Inheritance)</a></li>
            <li><a href="#oop-static">Static 키워드</a></li>
            <li><a href="#oop-constants">클래스 상수</a></li>
            <li><a href="#oop-namespaces">네임스페이스 (Namespaces)</a></li>
            <li><a href="#oop-autoloading">오토로딩 (Autoloading)</a></li>
            <li><a href="#oop-more">(간략) 인터페이스, 추상 클래스, 트레이트</a></li>
        </ul>
    </li>
     <li><a href="#php-errors">에러 처리 및 디버깅</a>
        <ul>
            <li><a href="#errors-reporting">에러 리포팅 설정</a></li>
            <li><a href="#errors-logging">에러 로깅</a></li>
            <li><a href="#errors-exceptions">예외 처리 (try-catch-finally, throw)</a></li>
            <li><a href="#errors-debugging">디버깅 기법</a></li>
        </ul>
    </li>
     <li><a href="#php-security">보안 기초</a>
        <ul>
            <li><a href="#security-xss">XSS 방지</a></li>
            <li><a href="#security-sql-injection">SQL 인젝션 방지</a></li>
            <li><a href="#security-csrf">CSRF 방지</a></li>
            <li><a href="#security-password">비밀번호 해싱</a></li>
            <li><a href="#security-file-uploads">파일 업로드 보안</a></li>
        </ul>
    </li>
    <li><a href="#php-modern">현대 PHP 및 다음 단계</a>
         <ul>
            <li><a href="#modern-composer">Composer (의존성 관리)</a></li>
            <li><a href="#modern-psr">PSR 표준</a></li>
            <li><a href="#modern-frameworks">프레임워크 (Laravel, Symfony 등)</a></li>
            <li><a href="#modern-versions">PHP 버전</a></li>
            <li><a href="#modern-next">마무리 및 추가 학습</a></li>
        </ul>
    </li>
  </ul>
</div>

<section id="intro-php">
  <h2>PHP 소개 및 환경 설정</h2>

  <h3 id="intro-what-is-php">PHP란? (서버 사이드 스크립트)</h3>
  <p>PHP는 PHP: Hypertext Preprocessor"의 재귀적 약자로, 주로 웹 개발에 사용되는 서버 사이드 스크립트 언어(Server-Side Scripting Language)입니다. 서버 사이드 언어는 웹 서버에서 코드가 실행되어 그 결과를 HTML 형태로 클라이언트(웹 브라우저)에게 보내주는 방식으로 동작합니다.</p>
  <p>PHP를 사용하면 다음과 같은 작업을 할 수 있습니다:</p>
  <ul>
    <li>동적인 웹 페이지 콘텐츠 생성 (사용자 입력, 데이터베이스 정보 등에 따라 다른 내용 표시)</li>
    <li>데이터베이스 연동 (MySQL, PostgreSQL 등 다양한 DB 지원)</li>
    <li>사용자 로그인, 회원 가입 등 세션 및 쿠키 관리</li>
    <li>폼 데이터 처리 및 유효성 검사</li>
    <li>파일 업로드 및 다운로드 처리</li>
    <li>이미지 처리, PDF 생성 등 다양한 작업 가능</li>
    <li>명령줄 스크립트 작성</li>
  </ul>
  <p>PHP는 배우기 쉽고, 방대한 커뮤니티와 자료를 가지고 있으며, WordPress, Laravel, Symfony 등 강력한 프레임워크와 CMS(콘텐츠 관리 시스템)의 기반이 되는 등 웹 개발 생태계에서 중요한 위치를 차지하고 있습니다.</p>

  <h3 id="intro-how-php-works">PHP 동작 방식</h3>
  <p>PHP 기반 웹 페이지의 일반적인 동작 과정은 다음과 같습니다:</p>
  <ol>
    <li>사용자가 웹 브라우저에서 PHP 페이지(.php 파일)를 요청합니다.</li>
    <li>웹 서버(예: Apache, Nginx)는 해당 요청을 받아 PHP 인터프리터에게 전달합니다.</li>
    <li>PHP 인터프리터는 `.php` 파일 내의 PHP 코드를 실행합니다.
        <ul>
            <li>데이터베이스 조회, 파일 읽기/쓰기, 계산 등 필요한 작업을 수행합니다.</li>
            <li>실행 결과를 바탕으로 동적인 HTML 콘텐츠를 생성합니다.</li>
        </ul>
    </li>
    <li>PHP 인터프리터는 최종적으로 생성된 HTML 결과(및 기타 헤더 정보)를 웹 서버에게 반환합니다.</li>
    <li>웹 서버는 이 HTML 결과를 사용자의 웹 브라우저에게 응답으로 보냅니다.</li>
    <li>웹 브라우저는 수신한 HTML을 해석하여 사용자에게 화면을 보여줍니다. (브라우저는 PHP 코드를 직접 볼 수 없습니다)</li>
  </ol>

  <h3 id="intro-setup">개발 환경 설정 (XAMPP, MAMP, Docker 등)</h3>
  <p>PHP 코드를 작성하고 실행하려면 웹 서버(Apache/Nginx), PHP 인터프리터, 그리고 보통 데이터베이스(MySQL/MariaDB)가 설치된 개발 환경이 필요합니다.</p>
  <p>초보자가 쉽게 환경을 구축할 수 있는 방법은 통합 설치 패키지를 사용하는 것입니다:</p>
  <ul>
    <li><strong>XAMPP:</strong> Windows, macOS, Linux를 모두 지원하는 가장 널리 사용되는 무료 패키지입니다. Apache, MariaDB, PHP, Perl을 포함합니다. (<a href="https://www.apachefriends.org/" target="_blank">XAMPP 다운로드</a>)</li>
    <li><strong>MAMP:</strong> macOS 환경에 최적화된 패키지입니다. 무료 버전과 유료 Pro 버전이 있습니다. (<a href="https://www.mamp.info/" target="_blank">MAMP 다운로드</a>)</li>
    <li><strong>WampServer:</strong> Windows 환경을 위한 패키지입니다. Apache, MySQL, PHP를 포함합니다.</li>
  </ul>
  <p>최근에는 Docker를 사용하여 개발 환경을 컨테이너화하는 방식도 많이 사용됩니다. 이는 환경 구성을 코드화하고 이식성을 높이는 장점이 있지만, 초보자에게는 다소 복잡할 수 있습니다.</p>
  <p>통합 패키지를 설치한 후, 웹 서버의 문서 루트(Document Root) 디렉토리(예: XAMPP의 `htdocs`, MAMP의 `htdocs`)에 `.php` 확장자를 가진 파일을 만들고 웹 브라우저에서 해당 파일의 URL(예: `http://localhost/myfile.php`)로 접속하여 실행 결과를 확인할 수 있습니다.</p>
  <p class="note">개발 환경 설정 및 사용법은 선택한 도구(XAMPP, MAMP 등)의 공식 문서를 참고하는 것이 가장 정확합니다.</p>
</section>

<section id="php-syntax">
  <h2>기본 문법</h2>

  <h3 id="syntax-tags">PHP 태그 (<code>&lt;?php ... ?&gt;</code>)</h3>
  <p>PHP 코드는 시작 태그 <code>&lt;?php</code> 와 종료 태그 <code>?&gt;</code> 사이에 작성됩니다. 웹 서버는 이 태그 사이의 코드를 PHP 코드로 인식하고 실행합니다.</p>
  <pre><code class="language-php">&lt;?php
// 이 안에 PHP 코드를 작성합니다.
echo "Hello from PHP!";
?&gt;</code></pre>
  <p>파일 전체가 PHP 코드로만 이루어진 경우, 파일 끝의 종료 태그 <code>?&gt;</code>는 생략하는 것이 권장됩니다. 이는 파일 끝에 의도치 않은 공백이나 줄바꿈이 출력되는 것을 방지하기 위함입니다.</p>
  <pre><code class="language-php">&lt;?php
// 파일 전체가 PHP 코드일 경우 종료 태그 생략 권장
$message = "Only PHP here.";
echo $message;
// ?> 여기서는 생략</code></pre>
  <p class="warning">Short tags(<code>&lt;? ... ?&gt;</code>)나 ASP-style tags(<code>&lt;% ... %&gt;</code>)도 있지만, 서버 설정에 따라 동작하지 않을 수 있고 이식성이 떨어지므로 사용하지 않는 것이 좋습니다. <code>&lt;?= ... ?&gt;</code>는 <code>&lt;?php echo ... ?&gt;</code>의 축약형으로, 간단한 출력에 유용하게 사용될 수 있습니다.</p>
   <pre><code class="language-php">&lt;p&gt;오늘 날짜는 &lt;?= date('Y-m-d') ?&gt; 입니다.&lt;/p&gt;
&lt;!-- 위 코드는 아래와 동일 --&gt;
&lt;p&gt;오늘 날짜는 &lt;?php echo date('Y-m-d'); ?&gt; 입니다.&lt;/p&gt;
</code></pre>

  <h3 id="syntax-echo-print">출력 (echo, print)</h3>
  <p>PHP에서 웹 페이지로 데이터를 출력(화면에 표시)할 때 주로 <code>echo</code> 또는 <code>print</code>를 사용합니다.</p>
  <ul>
    <li><code>echo</code>: 하나 이상의 문자열을 출력할 수 있으며, 반환 값이 없습니다. 일반적으로 <code>print</code>보다 약간 빠릅니다. 괄호 없이 사용 가능합니다.</li>
    <li><code>print</code>: 하나의 문자열만 출력할 수 있으며, 항상 1을 반환합니다. 괄호 사용이 권장됩니다.</li>
  </ul>
  <p>대부분의 경우 <code>echo</code>를 사용하는 것이 일반적입니다.</p>
  <pre><code class="language-php">&lt;?php
echo "Hello, world!"; // 문자열 출력
echo "&lt;br&gt;";        // HTML 태그도 출력 가능
echo "이것은 ", "여러 개 ", "문자열 출력입니다."; // 콤마로 여러 개 출력 (echo만 가능)
echo "&lt;br&gt;";

print "print로 출력합니다.";
print("&lt;br&gt;");
// print "첫 번째", " 두 번째"; // 오류: print는 하나의 인자만 받음

$result = print "print는 1을 반환합니다."; // 출력하고 1을 $result에 할당
echo "&lt;br&gt;print 반환값: " . $result; // 문자열 연결 연산자 . 사용
?&gt;</code></pre>
  <div class="example">
    <h4>출력 예제 결과</h4>
    <div class="output">
        <?php
            echo "Hello, world!";
            echo "<br>";
            echo "이것은 ", "여러 개 ", "문자열 출력입니다.";
            echo "<br>";

            print "print로 출력합니다.";
            print("<br>");

            $result = print "print는 1을 반환합니다.";
            echo "<br>print 반환값: " . $result;
        ?>
    </div>
  </div>

  <h3 id="syntax-comments">주석 (Comments)</h3>
  <p>PHP 코드 내에 설명을 추가하거나 특정 코드를 임시로 비활성화할 때 주석을 사용합니다. 주석은 PHP 인터프리터에 의해 무시됩니다.</p>
  <ul>
    <li>한 줄 주석: <code>//</code> 또는 <code>#</code> 뒤의 내용은 해당 줄 끝까지 주석 처리됩니다.</li>
    <li>여러 줄 주석: <code>/*</code> 와 <code>*/</code> 사이의 모든 내용은 주석 처리됩니다.</li>
  </ul>
  <pre><code class="language-php">&lt;?php
// 이것은 한 줄 주석입니다.
# 이것도 한 줄 주석입니다. (Perl 스타일)

/*
 이것은
 여러 줄에 걸친
 주석입니다.
 */

$name = "PHP User"; // 사용자 이름 저장 (한 줄 주석)

// echo "이 코드는 실행되지 않습니다.";

/*
echo "이 블록 전체가";
echo "주석 처리됩니다.";
*/

echo "주석 밖의 코드는 실행됩니다."; // 주석 처리되지 않은 코드
?&gt;</code></pre>

  <h3 id="syntax-case-sensitivity">대소문자 구분</h3>
  <p>PHP에서 대소문자 구분 규칙은 다음과 같습니다:</p>
  <ul>
    <li><strong>변수 이름:</strong> 대소문자를 구분합니다. <code>$myVar</code>와 <code>$myvar</code>는 다른 변수입니다.</li>
    <li><strong>상수 이름:</strong> 기본적으로 대소문자를 구분합니다. (<code>define()</code> 함수의 세 번째 인수로 <code>true</code>를 주면 구분하지 않게 할 수 있지만, 권장되지 않습니다.)</li>
    <li><strong>함수 이름, 클래스 이름, 키워드(<code>if</code>, <code>else</code>, <code>echo</code> 등), 내장 함수:</strong> 대소문자를 구분하지 않습니다. 하지만 일관성을 위해 소문자로 작성하는 것이 일반적인 컨벤션입니다.</li>
  </ul>
  <pre><code class="language-php">&lt;?php
$name = "Alice";
$Name = "Bob";
echo $name; // 출력: Alice
echo "&lt;br&gt;";
echo $Name; // 출력: Bob
echo "&lt;br&gt;";

define("GREETING", "Hello");
echo GREETING; // 출력: Hello
// echo greeting; // 오류 발생 (기본적으로 대소문자 구분)

// 함수/키워드는 대소문자 구분 안 함 (하지만 소문자 권장)
ECHO "Case insensitive echo.&lt;br&gt;";
function myFunction() {
    echo "Inside myFunction.&lt;br&gt;";
}
MYFUNCTION(); // 호출 가능
?&gt;</code></pre>

  <h3 id="syntax-statements-semicolons">문장과 세미콜론</h3>
  <p>PHP 코드는 여러 개의 문장(Statement)으로 구성됩니다. 각 문장은 특정 작업을 수행하는 코드 단위이며, 반드시 세미콜론(<code>;</code>)으로 끝나야 합니다.</p>
  <pre><code class="language-php">&lt;?php
$x = 5; // 변수 할당 문장
$y = 10;
$sum = $x + $y; // 계산 및 할당 문장
echo $sum; // 출력 문장
echo "&lt;br&gt;";

// if 문도 세미콜론으로 끝나는 여러 문장을 포함할 수 있음
if ($sum > 10) {
    echo "Sum is greater than 10"; // 이것도 문장
    $message = "Large sum"; // 이것도 문장
} // if 블록 끝에는 세미콜론 없음
?&gt;</code></pre>
  <p class="warning">PHP 코드 블록의 마지막 문장 뒤(<code>?&gt;</code> 바로 앞)에는 세미콜론을 생략할 수도 있지만, 일관성을 위해 항상 붙이는 것이 좋습니다.</p>

  <h3 id="syntax-embedding">HTML에 PHP 삽입</h3>
  <p>PHP의 가장 큰 특징 중 하나는 HTML 코드 중간에 PHP 코드를 자유롭게 삽입하여 동적인 콘텐츠를 생성할 수 있다는 것입니다.</p>
  <pre><code class="language-html">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;PHP와 HTML&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;환영합니다!&lt;/h1&gt;

    &lt;p&gt;PHP를 사용하여 동적인 콘텐츠를 생성할 수 있습니다.&lt;/p&gt;

    &lt;p&gt;현재 시간은:
    &lt;strong&gt;
        &lt;?php
        // PHP 코드를 사용하여 현재 시간을 출력
        date_default_timezone_set('Asia/Seoul'); // 시간대 설정
        echo date('Y년 m월 d일 H시 i분 s초');
        ?&gt;
    &lt;/strong&gt;
    입니다.
    &lt;/p&gt;

    &lt;?php
    // PHP 변수를 HTML 속성에 사용
    $username = "관리자";
    $isAdmin = true;
    $profileImage = "images/admin.png";
    ?&gt;

    &lt;div class="user-profile &lt;?php if ($isAdmin) { echo 'admin-user'; } ?&gt;"&gt;
        &lt;h2&gt;사용자: &lt;?php echo htmlspecialchars($username); // XSS 방지를 위해 htmlspecialchars 사용 ?&gt;&lt;/h2&gt;
        &lt;img src="&lt;?php echo htmlspecialchars($profileImage); ?&gt;" alt="프로필 이미지"&gt;
    &lt;/div&gt;

    &lt;p&gt;페이지 하단입니다.&lt;/p&gt;

&lt;/body&gt;
&lt;/html&gt;
</code></pre>
  <p>위 예시처럼 HTML 코드 내에서 <code>&lt;?php ... ?&gt;</code> 태그를 사용하여 PHP 변수 값을 출력하거나, 조건에 따라 다른 HTML 구조를 생성할 수 있습니다.</p>
</section>


<section id="php-variables">
    <h2>변수 (Variables)</h2>
    <p>변수는 데이터를 저장하기 위한 메모리 공간에 붙여진 이름입니다. PHP에서 변수는 프로그래밍의 기본적인 구성 요소입니다.</p>

    <h3 id="variables-naming">변수 선언 및 이름 규칙 ($)</h3>
    <ul>
        <li>PHP 변수는 항상 달러 기호(<code>$</code>)로 시작합니다.</li>
        <li><code>$</code> 다음에는 문자(a-z, A-Z) 또는 밑줄(<code>_</code>)로 시작해야 합니다. 숫자로 시작할 수 없습니다.</li>
        <li>변수 이름은 문자, 숫자, 밑줄로만 구성될 수 있습니다. (공백이나 특수 문자 포함 불가)</li>
        <li>변수 이름은 대소문자를 구분합니다 (<code>$name</code>과 <code>$Name</code>은 다름).</li>
        <li>PHP는 동적 타입 언어이므로, 변수를 선언할 때 타입을 명시할 필요가 없습니다. 변수에 값이 할당될 때 타입이 결정됩니다.</li>
        <li>값을 할당할 때는 할당 연산자(<code>=</code>)를 사용합니다.</li>
    </ul>
    <pre><code class="language-php">&lt;?php
    $message = "안녕하세요!"; // 문자열 변수
    $count = 10;           // 정수 변수
    $price = 99.99;        // 실수 변수
    $_isValid = true;      // 불리언 변수 (밑줄로 시작 가능)
    $userName = "홍길동";   // Camel case (일반적)
    $user_id = 1001;       // Snake case

    echo $message; echo "&lt;br&gt;";
    echo $count;   echo "&lt;br&gt;";
    echo $price;   echo "&lt;br&gt;";
    echo $_isValid; // true는 "1"로 출력됨, false는 아무것도 출력 안 됨 (echo 사용 시)
    echo "&lt;br&gt;";

    // 잘못된 변수 이름 예시
    // $1stPlace = "Gold"; // 숫자로 시작 불가
    // $user name = "Alice"; // 공백 포함 불가
    // $user-email = "..."; // 하이픈(-) 포함 불가

    // 값 재할당 가능 (타입도 변경 가능)
    $count = "이제 문자열";
    echo $count; echo "&lt;br&gt;";
    ?&gt;</code></pre>

    <h3 id="variables-scope">변수 스코프 (지역, 전역, 정적)</h3>
    <p>변수 스코프(Scope)는 변수가 유효한(접근 가능한) 코드의 범위를 의미합니다.</p>
    <ul>
        <li><strong>지역 변수 (Local Variables):</strong> 함수 내부에서 선언된 변수입니다. 해당 함수 내부에서만 접근 가능하며, 함수 실행이 끝나면 사라집니다.</li>
        <li><strong>전역 변수 (Global Variables):</strong> 함수 외부에서 선언된 변수입니다. 스크립트 전체 영역에서 접근 가능하지만, 함수 내부에서 직접 접근할 수는 없습니다. 함수 내에서 전역 변수를 사용하려면 <code>global</code> 키워드를 사용하거나 <code>$GLOBALS</code> 슈퍼글로벌 배열을 이용해야 합니다.</li>
        <li><strong>정적 변수 (Static Variables):</strong> 함수 내부에서 <code>static</code> 키워드로 선언된 변수입니다. 지역 변수와 달리 함수 호출이 끝나도 값이 유지되며, 다음 함수 호출 시 이전 값을 기억합니다. 함수 내부에서만 접근 가능합니다.</li>
    </ul>
    <pre><code class="language-php">&lt;?php
    $globalMessage = "전역 메시지"; // 전역 변수

    function myLocalScopeFunction() {
        $localMessage = "지역 메시지"; // 지역 변수
        echo $localMessage; // 접근 가능
        echo "&lt;br&gt;";
        // echo $globalMessage; // 직접 접근 불가 (Notice: Undefined variable)
    }

    myLocalScopeFunction();
    // echo $localMessage; // 접근 불가 (Fatal error: Uncaught Error: Undefined variable)

    function accessGlobalVariable() {
        // 방법 1: global 키워드 사용
        global $globalMessage;
        echo "함수 내에서 global 사용: " . $globalMessage;
        echo "&lt;br&gt;";

        // 방법 2: $GLOBALS 배열 사용
        echo "함수 내에서 \$GLOBALS 사용: " . $GLOBALS['globalMessage'];
        echo "&lt;br&gt;";

        // 함수 내에서 전역 변수 값 변경 시도
        $globalMessage = "전역 메시지 변경됨";
    }

    accessGlobalVariable();
    echo "함수 실행 후 전역 변수: " . $globalMessage; // 변경된 값 출력
    echo "&lt;br&gt;";

    function staticVariableCounter() {
        static $count = 0; // 정적 변수 선언 및 초기화 (최초 호출 시 1번만 실행)
        $count++;
        echo "Static 카운터: " . $count;
        echo "&lt;br&gt;";
    }

    staticVariableCounter(); // 출력: Static 카운터: 1
    staticVariableCounter(); // 출력: Static 카운터: 2 (이전 값 유지)
    staticVariableCounter(); // 출력: Static 카운터: 3
    ?&gt;</code></pre>
    <p class="warning">전역 변수를 남용하는 것은 코드의 의존성을 높이고 추적을 어렵게 만들 수 있으므로, 가급적 함수 매개변수나 반환값을 사용하는 것이 좋습니다.</p>

    <h3 id="variables-superglobals">슈퍼글로벌 변수 (Superglobals)</h3>
    <p>PHP에는 미리 정의된 특별한 배열 변수들이 있으며, 스크립트 어느 곳에서든 (함수 내부 포함) <code>global</code> 키워드나 <code>$GLOBALS</code> 없이 바로 접근할 수 있습니다. 이를 슈퍼글로벌 변수라고 합니다.</p>
    <ul>
        <li><code>$GLOBALS</code>: 모든 전역 변수를 포함하는 연관 배열.</li>
        <li><code>$_SERVER</code>: 서버 및 실행 환경 정보를 담고 있는 배열 (예: 요청 메서드, 사용자 IP, 파일 경로 등).</li>
        <li><code>$_GET</code>: HTTP GET 방식으로 전달된 변수들을 담고 있는 연관 배열 (URL 쿼리 스트링).</li>
        <li><code>$_POST</code>: HTTP POST 방식으로 전달된 변수들을 담고 있는 연관 배열.</li>
        <li><code>$_REQUEST</code>: <code>$_GET</code>, <code>$_POST</code>, <code>$_COOKIE</code> 내용을 모두 포함하는 연관 배열 (보안 및 명확성 문제로 사용 자제 권장).</li>
        <li><code>$_FILES</code>: HTTP POST 방식으로 업로드된 파일 정보를 담고 있는 다차원 배열.</li>
        <li><code>$_COOKIE</code>: HTTP 쿠키를 통해 전달된 변수들을 담고 있는 연관 배열.</li>
        <li><code>$_SESSION</code>: 세션 변수들을 담고 있는 연관 배열.</li>
        <li><code>$_ENV</code>: 환경 변수들을 담고 있는 연관 배열.</li>
    </ul>
     <div class="example">
        <h4>슈퍼글로벌 변수 예제</h4>
        <p>현재 페이지 URL: <?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?></p>
        <p>요청 방식: <?php echo htmlspecialchars($_SERVER['REQUEST_METHOD']); ?></p>
        <p>사용자 IP 주소: <?php echo htmlspecialchars($_SERVER['REMOTE_ADDR']); ?></p>
        <p>쿼리 스트링 (예: ?name=Test&age=30):</p>
        <pre>$_GET: <?php print_r($_GET); // 배열 내용 확인 ?></pre>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" name="username" placeholder="이름 입력 (POST)">
            <button type="submit">제출</button>
        </form>
        <p>POST 데이터:</p>
        <pre>$_POST: <?php print_r($_POST); ?></pre>
    </div>
    <pre><code class="language-php">&lt;?php
    // $_SERVER 예시
    echo "현재 스크립트 파일명: " . htmlspecialchars($_SERVER['PHP_SELF']);
    echo "&lt;br&gt;";
    echo "서버 호스트 이름: " . htmlspecialchars($_SERVER['SERVER_NAME']);
    echo "&lt;br&gt;";

    // $_GET 예시 (URL에 ?id=123&category=books 와 같이 전달 시)
    if (isset($_GET['id'])) {
        $itemId = htmlspecialchars($_GET['id']);
        echo "요청된 아이템 ID: " . $itemId;
        echo "&lt;br&gt;";
    }

    // $_POST 예시 (폼 제출 시)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
        $submittedName = htmlspecialchars($_POST['username']);
        echo "제출된 이름: " . $submittedName;
        echo "&lt;br&gt;";
    }

    // 슈퍼글로벌은 함수 내에서도 직접 사용 가능
    function showRequestMethod() {
        echo "함수 내 요청 방식: " . htmlspecialchars($_SERVER['REQUEST_METHOD']);
    }
    showRequestMethod();
    ?&gt;
    &lt;!-- 간단한 GET/POST 테스트 폼 --&gt;
    &lt;form method="get" action=""&gt;
      &lt;input type="text" name="query" placeholder="GET 검색어"&gt;
      &lt;button type="submit"&gt;GET 전송&lt;/button&gt;
    &lt;/form&gt;
    &lt;form method="post" action=""&gt;
      &lt;input type="text" name="email" placeholder="POST 이메일"&gt;
      &lt;button type="submit"&gt;POST 전송&lt;/button&gt;
    &lt;/form&gt;
    </code></pre>
    <p class="warning">사용자로부터 입력받는 <code>$_GET</code>, <code>$_POST</code> 등의 데이터는 항상 보안 위협(XSS 등)에 노출될 수 있으므로, 출력하거나 사용하기 전에 반드시 검증하고 이스케이프 처리(예: <code>htmlspecialchars()</code>)를 해야 합니다.</p>
</section>

<section id="php-constants">
    <h2>상수 (Constants)</h2>
    <p>상수는 스크립트 실행 중 변하지 않는 고정된 값을 저장하는 식별자입니다. 변수와 달리 <code>$</code> 기호 없이 사용하며, 한 번 정의되면 값을 변경하거나 재정의할 수 없습니다.</p>

    <h3 id="constants-define">define() 함수</h3>
    <p><code>define(name, value, case_insensitive)</code> 함수를 사용하여 상수를 정의합니다.</p>
    <ul>
        <li><code>name</code>: 상수 이름 (문자열, 일반적으로 대문자 사용).</li>
        <li><code>value</code>: 상수의 값 (스칼라 값(int, float, string, bool) 또는 array 가능).</li>
        <li><code>case_insensitive</code> (선택 사항): <code>true</code>로 설정하면 대소문자를 구분하지 않지만, PHP 7.3부터 deprecated 되었고 PHP 8.0에서 제거되었습니다. 사용하지 않는 것이 좋습니다.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
    define("SITE_NAME", "나의 웹사이트");
    define("MAX_USERS", 100);
    define("ALLOWED_EXTENSIONS", ["jpg", "png", "gif"]); // 배열 상수 (PHP 5.6+)

    echo SITE_NAME; echo "&lt;br&gt;"; // 나의 웹사이트
    echo MAX_USERS; echo "&lt;br&gt;"; // 100
    print_r(ALLOWED_EXTENSIONS); echo "&lt;br&gt;"; // Array ( [0] => jpg [1] => png [2] => gif )

    // 상수는 값 변경 불가
    // SITE_NAME = "다른 이름"; // 오류 발생

    // 상수는 어디서든 접근 가능 (함수 내부 포함)
    function displaySiteInfo() {
        echo "사이트 이름: " . SITE_NAME;
    }
    displaySiteInfo(); echo "&lt;br&gt;";
    ?&gt;</code></pre>

    <h3 id="constants-const">const 키워드</h3>
    <p><code>const</code> 키워드를 사용하여 클래스 외부(전역 범위) 또는 클래스 내부에서 상수를 정의할 수도 있습니다. (PHP 5.3+ 부터 전역 범위 지원)</p>
    <ul>
        <li><code>const</code>는 컴파일 타임에 결정되므로, <code>define()</code>과 달리 조건문이나 루프 안에서 동적으로 정의할 수 없습니다.</li>
        <li>클래스 내부에서 정의할 때는 클래스 상수(Class Constant)가 됩니다.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
    const APP_VERSION = "1.0.2";
    const DEFAULT_ROLE = "guest";
    const SUPPORTED_LANGUAGES = ['ko', 'en']; // 배열 상수 (PHP 5.6+)

    echo APP_VERSION; echo "&lt;br&gt;"; // 1.0.2
    echo DEFAULT_ROLE; echo "&lt;br&gt;"; // guest

    // 조건문 내에서 const 사용 불가
    // if (true) {
    //    const MY_CONST = 123; // 오류 발생 (Parse error)
    // }

    class AppConfig {
        const DB_HOST = "localhost"; // 클래스 상수
        public function getDbHost() {
            return self::DB_HOST; // 클래스 내부에서 self::로 접근
        }
    }

    echo AppConfig::DB_HOST; // 클래스 외부에서 ClassName::CONST_NAME 으로 접근
    $config = new AppConfig();
    echo "&lt;br&gt;" . $config->getDbHost();
    ?&gt;</code></pre>
    <p class="note">일반적으로 전역 상수는 <code>define()</code> 또는 <code>const</code> 모두 사용할 수 있지만, 클래스 상수는 반드시 <code>const</code>를 사용해야 합니다. <code>const</code>가 약간 더 빠르고 가독성이 좋다는 의견도 있습니다.</p>

    <h3 id="constants-magic">매직 상수 (Magic Constants)</h3>
    <p>PHP에는 코드의 위치나 상태에 따라 값이 달라지는 미리 정의된 특별한 상수들이 있습니다. 이들은 두 개의 밑줄(<code>__</code>)로 시작하고 끝납니다.</p>
    <ul>
        <li><code>__LINE__</code>: 현재 파일의 줄 번호.</li>
        <li><code>__FILE__</code>: 현재 파일의 전체 경로와 이름.</li>
        <li><code>__DIR__</code>: 현재 파일이 있는 디렉토리 경로.</li>
        <li><code>__FUNCTION__</code>: 현재 함수의 이름.</li>
        <li><code>__CLASS__</code>: 현재 클래스의 이름.</li>
        <li><code>__METHOD__</code>: 현재 클래스 메서드의 이름.</li>
        <li><code>__NAMESPACE__</code>: 현재 네임스페이스의 이름.</li>
        <li><code>ClassName::class</code>: 클래스의 전체 이름(네임스페이스 포함)을 문자열로 반환 (PHP 5.5+).</li>
    </ul>
    <pre><code class="language-php">&lt;?php
    echo "현재 줄 번호: " . __LINE__ . "&lt;br&gt;"; // 예: 3
    echo "현재 파일 경로: " . __FILE__ . "&lt;br&gt;";
    echo "현재 디렉토리: " . __DIR__ . "&lt;br&gt;";

    function testFunction() {
        echo "함수 이름: " . __FUNCTION__ . "&lt;br&gt;";
    }
    testFunction();

    class MyTestClass {
        public function testMethod() {
            echo "클래스 이름: " . __CLASS__ . "&lt;br&gt;";
            echo "메서드 이름: " . __METHOD__ . "&lt;br&gt;";
             echo "클래스 이름 (::class): " . MyTestClass::class . "&lt;br&gt;";
        }
    }
    $myObj = new MyTestClass();
    $myObj->testMethod();
    ?&gt;</code></pre>
</section>

<section id="php-data-types">
    <h2>데이터 타입 (Data Types)</h2>
    <p>PHP는 다양한 종류의 데이터를 저장하고 처리할 수 있도록 여러 데이터 타입을 지원합니다.</p>

    <h3 id="types-scalar">스칼라 타입 (Scalar Types)</h3>
    <p>단일 값을 나타내는 기본 타입입니다.</p>
    <ul>
        <li><strong>Boolean (불리언):</strong> 논리적인 값 <code>true</code> 또는 <code>false</code>를 나타냅니다. 대소문자를 구분하지 않지만(<code>TRUE</code>, <code>False</code> 가능), 소문자 사용이 권장됩니다.
            <pre><code class="language-php">$is_active = true;
$is_logged_in = false;</code></pre>
        </li>
        <li><strong>Integer (정수):</strong> 소수점 없는 숫자를 나타냅니다. 10진수, 8진수(접두사 <code>0</code>), 16진수(접두사 <code>0x</code>), 2진수(접두사 <code>0b</code>)로 표현 가능합니다.
            <pre><code class="language-php">$decimal = 1234;
$octal = 0123; // 10진수 83
$hexadecimal = 0x1A; // 10진수 26
$binary = 0b1011; // 10진수 11
$negative = -567;</code></pre>
        </li>
        <li><strong>Float (실수, 부동소수점 수) / Double:</strong> 소수점을 가지는 숫자를 나타냅니다. 지수 표기법도 사용 가능합니다.
            <pre><code class="language-php">$pi = 3.14159;
$scientific = 1.23e4; // 1.23 * 10^4 = 12300
$small_number = 5E-3; // 5 * 10^-3 = 0.005</code></pre>
             <p class="warning">부동소수점 수는 내부적으로 정확한 표현이 어려워 비교 시 주의가 필요합니다. (예: <code>0.1 + 0.2</code>가 정확히 <code>0.3</code>이 아닐 수 있음)</p>
        </li>
        <li><strong>String (문자열):</strong> 문자들의 순차적인 집합을 나타냅니다. 네 가지 방법으로 표현할 수 있습니다:
            <ul>
                <li><strong>작은따옴표 (Single quotes):</strong> 문자열을 그대로 표현합니다. 변수 치환이나 이스케이프 시퀀스(<code>\n</code>, <code>\t</code> 등, <code>\'</code>와 <code>\\</code> 제외)를 해석하지 않습니다.
                    <pre><code class="language-php">$single_quoted = '이것은 작은따옴표 문자열입니다. 변수 $name 은 해석되지 않습니다. \n 줄바꿈 안됨.';</code></pre>
                </li>
                <li><strong>큰따옴표 (Double quotes):</strong> 변수 치환(<code>{$variable}</code> 또는 <code>$variable</code>)과 대부분의 이스케이프 시퀀스(<code>\n</code>, <code>\r</code>, <code>\t</code>, <code>\$</code>, <code>\"</code>, <code>\\</code> 등)를 해석합니다.
                    <pre><code class="language-php">$name = "PHP";
$double_quoted = "안녕하세요, {$name} 사용자님!\n이스케이프 시퀀스가 동작합니다.";</code></pre>
                </li>
                <li><strong>Heredoc 문법:</strong> 여러 줄의 문자열을 정의할 때 유용하며, 큰따옴표 문자열처럼 변수와 이스케이프 시퀀스를 해석합니다. <code>&lt;&lt;&lt;IDENTIFIER</code>로 시작하여 <code>IDENTIFIER;</code>로 끝납니다. 종료 식별자는 반드시 새 줄에서 시작하고 다른 문자 없이 단독으로 와야 합니다.
                    <pre><code class="language-php">$city = "서울";
$heredoc_string = &lt;&lt;&lt;EOT
이것은 히어독(Heredoc) 문자열입니다.
여러 줄에 걸쳐 작성할 수 있으며,
변수 $city ({$city}) 와 이스케이프 시퀀스(\n)가 해석됩니다.
EOT; // 종료 식별자는 들여쓰기 없이 새 줄에서 시작해야 함</code></pre>
                </li>
                 <li><strong>Nowdoc 문법:</strong> Heredoc과 유사하지만 작은따옴표 문자열처럼 변수와 이스케이프 시퀀스를 해석하지 않습니다. 시작 식별자를 작은따옴표로 감쌉니다 (<code>&lt;&lt;&lt;'IDENTIFIER'</code>). (PHP 5.3+)
                    <pre><code class="language-php">$variable = "해석 안 됨";
$nowdoc_string = &lt;&lt;&lt;'EOD'
이것은 나우독(Nowdoc) 문자열입니다.
작은따옴표 문자열과 비슷하게 변수 $variable 과 \n 이스케이프 시퀀스가
해석되지 않고 그대로 출력됩니다.
EOD; // 종료 식별자 규칙은 Heredoc과 동일</code></pre>
                </li>
            </ul>
             <p>문자열 내 특정 문자에 접근하려면 인덱스(0부터 시작)를 사용하거나 <code>mb_substr()</code> 등의 함수를 사용합니다 (멀티바이트 문자열 처리 시 <code>mb_*</code> 함수 권장).</p>
             <pre><code class="language-php">$str = "Hello";
echo $str[0]; // H
echo $str[1]; // e</code></pre>
        </li>
    </ul>

    <h3 id="types-compound">복합 타입 (Compound Types)</h3>
    <p>여러 값을 묶어서 나타내는 타입입니다.</p>
     <ul>
         <li><strong>Array (배열):</strong> 순서가 있는 값들의 맵(map)입니다. 키(key)는 정수(인덱스 배열) 또는 문자열(연관 배열)일 수 있으며, 값(value)은 어떤 데이터 타입이든 가능합니다. <code>array()</code> 구조 또는 단축 문법 <code>[]</code> (PHP 5.4+)를 사용하여 생성합니다. (배열은 다음 섹션에서 자세히 다룹니다.)
             <pre><code class="language-php">$indexed_array = [10, 20, 30]; // 인덱스 배열
$associative_array = ["name" => "Alice", "age" => 30]; // 연관 배열</code></pre>
         </li>
         <li><strong>Object (객체):</strong> 클래스의 인스턴스입니다. 데이터(속성)와 기능(메서드)을 가집니다. <code>new</code> 키워드를 사용하여 생성합니다. (OOP 섹션에서 자세히 다룹니다.)
              <pre><code class="language-php">class User {
    public $name;
}
$user_object = new User();
$user_object->name = "Bob";</code></pre>
              <p><code>stdClass</code> 객체를 사용하여 간단한 객체를 만들 수도 있습니다: <code>$generic_object = new stdClass(); $generic_object->property = 'value';</code> 또는 <code>$generic_object = (object) ["property" => "value"];</code></p>
         </li>
         <li><strong>Callable (콜러블):</strong> 호출 가능한 값(함수 이름 문자열, 익명 함수, 객체 메서드 배열 등)을 나타내는 의사 타입(pseudo-type)입니다. 타입 힌팅 등에 사용됩니다.</li>
         <li><strong>Iterable (이터러블):</strong> <code>foreach</code> 루프로 반복 가능한 값(배열, Traversable 인터페이스를 구현한 객체 등)을 나타내는 의사 타입입니다. 타입 힌팅 등에 사용됩니다.</li>
     </ul>

    <h3 id="types-special">특수 타입 (Special Types)</h3>
     <ul>
         <li><strong>NULL:</strong> 값이 없거나 비어 있음을 나타내는 특별한 타입입니다. <code>null</code>이라는 유일한 값을 가집니다. 변수에 값이 할당되지 않았거나(<code>undefined</code>와 유사한 상태이지만 PHP는 명시적으로 <code>null</code> 사용), <code>unset()</code>으로 변수가 파괴되었거나, 명시적으로 <code>null</code>이 할당된 경우입니다.
             <pre><code class="language-php">$no_value = null;
$unassigned_var; // Notice: Undefined variable... 초기값은 null과 유사하게 취급될 수 있음
$unset_var = 10;
unset($unset_var); // 변수 파괴</code></pre>
         </li>
         <li><strong>Resource (리소스):</strong> 외부 자원(예: 데이터베이스 연결, 파일 핸들)에 대한 참조를 저장하는 특별한 변수입니다. 특정 함수(예: <code>fopen()</code>, <code>mysqli_connect()</code>)에 의해 반환됩니다.</li>
     </ul>

    <h3 id="types-checking">타입 확인 (gettype(), is_*)</h3>
    <p>변수의 데이터 타입을 확인하는 함수들입니다.</p>
    <ul>
        <li><code>gettype($variable)</code>: 변수의 타입을 문자열("boolean", "integer", "double", "string", "array", "object", "resource", "NULL", "unknown type")로 반환합니다.</li>
        <li><code>is_bool()</code>, <code>is_int()</code>/<code>is_integer()</code>/<code>is_long()</code>, <code>is_float()</code>/<code>is_double()</code>/<code>is_real()</code>, <code>is_string()</code>, <code>is_array()</code>, <code>is_object()</code>, <code>is_null()</code>, <code>is_resource()</code>, <code>is_numeric()</code>(숫자 또는 숫자형 문자열), <code>is_scalar()</code>(boolean, int, float, string) 등: 특정 타입인지 여부를 불리언 값으로 반환합니다.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $var1 = true;
     $var2 = 123;
     $var3 = 3.14;
     $var4 = "Hello";
     $var5 = [1, 2];
     $var6 = null;
     $var7 = fopen("php.php", "r"); // 예시 리소스 (파일 핸들)
     $var8 = new stdClass();

     echo gettype($var1); // boolean
     echo "&lt;br&gt;";
     echo gettype($var4); // string
     echo "&lt;br&gt;";
     echo gettype($var5); // array
     echo "&lt;br&gt;";
     echo gettype($var7); // resource (or resource (stream))
     echo "&lt;br&gt;";

     var_dump(is_int($var2)); // bool(true)
     var_dump(is_string($var2)); // bool(false)
     var_dump(is_array($var5)); // bool(true)
     var_dump(is_null($var6)); // bool(true)
     var_dump(is_object($var8)); // bool(true)
     var_dump(is_numeric("123.45")); // bool(true)
     var_dump(is_numeric("abc")); // bool(false)

     if ($var7) { fclose($var7); } // 리소스 사용 후 닫기
     ?&gt;</code></pre>
     <p class="note"><code>var_dump()</code> 함수는 변수의 타입과 값을 포함한 자세한 정보를 출력하여 디버깅에 매우 유용합니다.</p>

    <h3 id="types-casting">타입 캐스팅 (Type Casting)</h3>
    <p>변수의 타입을 다른 타입으로 명시적으로 변환하는 것을 타입 캐스팅이라고 합니다. 변수 앞에 원하는 타입을 괄호 안에 넣어 지정합니다.</p>
    <ul>
        <li><code>(int)</code> 또는 <code>(integer)</code>: 정수로 변환.</li>
        <li><code>(bool)</code> 또는 <code>(boolean)</code>: 불리언으로 변환.</li>
        <li><code>(float)</code>, <code>(double)</code>, <code>(real)</code>: 실수로 변환.</li>
        <li><code>(string)</code>: 문자열로 변환.</li>
        <li><code>(array)</code>: 배열로 변환.</li>
        <li><code>(object)</code>: 객체(<code>stdClass</code>)로 변환.</li>
        <li><code>(unset)</code>: NULL로 변환 (Deprecated, 사용하지 않음).</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $num_string = "123.45";
     $float_num = (float)$num_string; // 실수로 캐스팅
     $int_num = (int)$num_string;   // 정수로 캐스팅 (소수점 이하 버림)

     var_dump($float_num); // float(123.45)
     var_dump($int_num);   // int(123)

     $score = 95;
     $is_passed = (bool)$score; // 숫자를 불리언으로 (0 아니면 true)
     var_dump($is_passed); // bool(true)

     $value = 0;
     $is_false = (bool)$value;
     var_dump($is_false); // bool(false)

     $item = 100;
     $item_string = (string)$item; // 문자열 "100"으로 변환
     var_dump($item_string); // string(3) "100"

     $data_array = (array)$item_string; // 문자열을 배열로 변환 (인덱스 0에 값 할당)
     var_dump($data_array); // array(1) { [0]=> string(3) "100" }

     $data_object = (object)$data_array; // 배열을 객체로 변환 (키가 속성 이름이 됨)
     var_dump($data_object); // object(stdClass)#1 (1) { ["0"]=> string(3) "100" }
     ?&gt;</code></pre>
     <p>PHP는 연산 시 필요에 따라 자동으로 타입을 변환하는 타입 저글링(Type Juggling)도 수행하지만, 예기치 않은 결과를 초래할 수 있으므로 명시적 캐스팅이나 타입 비교(<code>===</code>) 사용이 권장될 때가 많습니다.</p>
</section>

<section id="php-operators">
    <h2>연산자 (Operators)</h2>
    <p>연산자는 하나 이상의 값(피연산자)에 대해 특정 연산을 수행하도록 지시하는 기호입니다. PHP는 다양한 종류의 연산자를 제공합니다.</p>

    <h3 id="operators-arithmetic">산술 연산자</h3>
    <p>수학적 계산을 수행합니다.</p>
    <ul>
        <li><code>+</code> (덧셈), <code>-</code> (뺄셈), <code>*</code> (곱셈), <code>/</code> (나눗셈)</li>
        <li><code>%</code> (나머지): <code>$a % $b</code>는 <code>$a</code>를 <code>$b</code>로 나눈 나머지를 반환.</li>
        <li><code></code> (거듭제곱): <code>$a  $b</code>는 <code>$a</code>의 <code>$b</code> 제곱을 반환 (PHP 5.6+).</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $x = 10; $y = 3;
     echo $x + $y; // 13
     echo "&lt;br&gt;";
     echo $x - $y; // 7
     echo "&lt;br&gt;";
     echo $x * $y; // 30
     echo "&lt;br&gt;";
     echo $x / $y; // 3.333...
     echo "&lt;br&gt;";
     echo $x % $y; // 1
     echo "&lt;br&gt;";
     echo 2  4; // 16
     ?&gt;</code></pre>

    <h3 id="operators-assignment">할당 연산자</h3>
    <p>변수에 값을 할당합니다.</p>
    <ul>
        <li><code>=</code>: 오른쪽 피연산자의 값을 왼쪽 피연산자(변수)에 할당.</li>
        <li><code>+=</code>, <code>-=</code>, <code>*=</code>, <code>/=</code>, <code>%=</code>, <code>=</code>, <code>.=</code> (문자열 결합 후 할당): 산술/문자열 연산 후 결과를 왼쪽 변수에 다시 할당 (복합 할당 연산자). 예: <code>$a += 5;</code>는 <code>$a = $a + 5;</code>와 동일.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $a = 10;
     $a += 5; // $a는 15가 됨
     echo $a; // 15
     echo "&lt;br&gt;";

     $str = "Hello";
     $str .= " World!"; // $str = $str . " World!";
     echo $str; // Hello World!
     ?&gt;</code></pre>

    <h3 id="operators-comparison">비교 연산자</h3>
    <p>두 피연산자를 비교하여 <code>true</code> 또는 <code>false</code>를 반환합니다.</p>
    <ul>
        <li><code>==</code> (동등): 값이 같으면 <code>true</code> (타입 저글링 발생).</li>
        <li><code>===</code> (일치): 값과 타입이 모두 같으면 <code>true</code> (타입 저글링 없음). 권장되는 비교 방식.</li>
        <li><code>!=</code> 또는 <code>&lt;&gt;</code> (부등): 값이 다르면 <code>true</code> (타입 저글링 발생).</li>
        <li><code>!==</code> (불일치): 값 또는 타입이 다르면 <code>true</code> (타입 저글링 없음). 권장되는 비교 방식.</li>
        <li><code>&lt;</code> (미만), <code>&gt;</code> (초과), <code>&lt;=</code> (이하), <code>&gt;=</code> (이상)</li>
        <li><code>&lt;=&gt;</code> (우주선 연산자, Spaceship operator - PHP 7+): 두 피연산자를 비교하여 왼쪽이 작으면 -1, 같으면 0, 크면 1을 반환.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $num1 = 100;
     $num2 = "100";

     var_dump($num1 == $num2);  // bool(true) (값이 같음)
     var_dump($num1 === $num2); // bool(false) (타입이 다름 - integer vs string)
     var_dump($num1 != $num2);  // bool(false)
     var_dump($num1 !== $num2); // bool(true)

     var_dump(50 < 100); // bool(true)
     var_dump(50 >= 50); // bool(true)

     echo 10 &lt;=&gt; 20; // -1
     echo "&lt;br&gt;";
     echo 10 &lt;=&gt; 10; // 0
     echo "&lt;br&gt;";
     echo 20 &lt;=&gt; 10; // 1
     ?&gt;</code></pre>
     <p class="warning"><code>==</code> 연산자는 예기치 않은 결과를 초래할 수 있으므로(예: <code>0 == false</code>, <code>0 == ""</code> 등이 <code>true</code>), 값과 타입을 모두 비교하는 <code>===</code> 연산자를 사용하는 것이 더 안전합니다.</p>

    <h3 id="operators-logical">논리 연산자</h3>
    <p>불리언 값을 조합하여 논리적인 결과를 반환합니다.</p>
    <ul>
        <li><code>and</code> 또는 <code>&&</code> (논리곱 - AND): 양쪽 모두 <code>true</code>일 때 <code>true</code>. (<code>&&</code>가 <code>and</code>보다 우선순위 높음)</li>
        <li><code>or</code> 또는 <code>||</code> (논리합 - OR): 양쪽 중 하나라도 <code>true</code>이면 <code>true</code>. (<code>||</code>가 <code>or</code>보다 우선순위 높음)</li>
        <li><code>xor</code> (배타적 논리합 - XOR): 양쪽이 서로 다를 때(하나는 <code>true</code>, 하나는 <code>false</code>) <code>true</code>.</li>
        <li><code>!</code> (논리 부정 - NOT): 피연산자의 불리언 값을 반전시킴.</li>
    </ul>
    <p class="note"><code>&&</code> 와 <code>||</code> 연산자는 단축 평가(Short-circuit evaluation)를 수행합니다. 즉, 왼쪽 피연산자만으로 전체 결과가 결정되면 오른쪽 피연산자는 평가(실행)하지 않습니다.</p>
    <pre><code class="language-php">&lt;?php
    $is_logged_in = true;
    $is_admin = false;

    var_dump($is_logged_in && $is_admin); // bool(false)
    var_dump($is_logged_in || $is_admin); // bool(true)
    var_dump(!$is_admin);                // bool(true)
    var_dump($is_logged_in xor $is_admin);// bool(true)

    // 단축 평가 예시
    $username = "TestUser";
    // $username이 true이고 checkPermission() 결과도 true여야 함
    $can_access = ($username && checkPermission($username)); // checkPermission() 실행됨

    function checkPermission($user) { echo "&lt;br&gt;checkPermission called for {$user}&lt;br&gt;"; return true;}

    $guest_user = "";
    // $guest_user가 false(빈 문자열)이므로 오른쪽 checkPermission은 실행 안 됨
    $guest_can_access = ($guest_user && checkPermission($guest_user));
    var_dump($guest_can_access); // bool(false)
    ?&gt;</code></pre>

    <h3 id="operators-increment-decrement">증감 연산자</h3>
    <p>변수의 정수 값을 1 증가시키거나 감소시킵니다.</p>
    <ul>
        <li><code>++$a</code> (전위 증가): <code>$a</code>의 값을 1 증가시키고, 증가된 값을 반환.</li>
        <li><code>$a++</code> (후위 증가): <code>$a</code>의 현재 값을 반환하고, 그 후에 <code>$a</code>의 값을 1 증가시킴.</li>
        <li><code>--$a</code> (전위 감소): <code>$a</code>의 값을 1 감소시키고, 감소된 값을 반환.</li>
        <li><code>$a--</code> (후위 감소): <code>$a</code>의 현재 값을 반환하고, 그 후에 <code>$a</code>의 값을 1 감소시킴.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $a = 5;
     echo ++$a; // 6 (먼저 증가 후 반환)
     echo "&lt;br&gt;";
     echo $a;   // 6

     $b = 5;
     echo $b++; // 5 (현재 값 반환 후 증가)
     echo "&lt;br&gt;";
     echo $b;   // 6
     ?&gt;</code></pre>

    <h3 id="operators-string">문자열 연산자</h3>
    <ul>
        <li><code>.</code> (결합 연산자): 두 문자열을 이어붙입니다.</li>
        <li><code>.=</code> (결합 후 할당 연산자): 왼쪽 변수 문자열 뒤에 오른쪽 문자열을 이어붙인 후 다시 할당합니다. (<code>$str .= " more";</code>)</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $firstName = "길동";
     $lastName = "홍";
     $fullName = $lastName . $firstName; // 문자열 결합
     echo $fullName; // 홍길동

     $greeting = "안녕하세요, ";
     $greeting .= $fullName . "님!"; // 결합 후 할당
     echo "&lt;br&gt;" . $greeting; // 안녕하세요, 홍길동님!
     ?&gt;</code></pre>

    <h3 id="operators-array">배열 연산자</h3>
    <ul>
        <li><code>+</code> (합집합): 두 배열을 합칩니다. 왼쪽 배열에 이미 있는 키는 오른쪽 배열의 값으로 덮어쓰지 않습니다.</li>
        <li><code>==</code> (동등): 키와 값이 모두 같으면 <code>true</code> (순서, 타입 무관).</li>
        <li><code>===</code> (일치): 키, 값, 순서, 타입이 모두 같으면 <code>true</code>.</li>
        <li><code>!=</code> 또는 <code>&lt;&gt;</code> (부등)</li>
        <li><code>!==</code> (불일치)</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $arr1 = ["a" => 1, "b" => 2];
     $arr2 = ["b" => 3, "c" => 4];
     $union = $arr1 + $arr2; // 키 'b'는 $arr1의 값(2) 유지
     print_r($union); // Array ( [a] => 1 [b] => 2 [c] => 4 )
     echo "&lt;br&gt;";

     $arr3 = [1, 2, 3];
     $arr4 = [1, '2', 3]; // 값 2의 타입이 다름
     $arr5 = [1, 2, 3];

     var_dump($arr3 == $arr4); // bool(true) (값만 비교)
     var_dump($arr3 === $arr4); // bool(false) (타입 다름)
     var_dump($arr3 === $arr5); // bool(true) (값, 타입, 순서 모두 같음)
     ?&gt;</code></pre>

    <h3 id="operators-ternary">삼항 연산자</h3>
    <p>조건에 따라 다른 값을 반환하는 간단한 조건 표현식입니다.</p>
    <p><code>(condition) ? value_if_true : value_if_false</code></p>
    <pre><code class="language-php">&lt;?php
    $age = 20;
    $status = ($age >= 19) ? "성인" : "미성년자";
    echo $status; // 성인
    ?&gt;</code></pre>

     <h3 id="operators-null-coalescing">Null 병합 연산자 (?? - PHP 7+)</h3>
     <p>왼쪽 피연산자가 <code>null</code>이 아니면 왼쪽 값을 반환하고, <code>null</code>이면 오른쪽 피연산자를 반환합니다. <code>isset()</code>과 삼항 연산자를 축약한 형태입니다.</p>
      <p><code>$variable = $value_to_check ?? $default_value;</code></p>
      <pre><code class="language-php">&lt;?php
      // $_GET['user'] 값이 있으면 사용하고, 없으면 'guest' 사용
      $username = $_GET['user'] ?? 'guest';
      echo "사용자 이름: " . htmlspecialchars($username);
      echo "&lt;br&gt;";

      // 위 코드는 아래와 유사함:
      // $username = isset($_GET['user']) ? $_GET['user'] : 'guest';

      $config['port'] = null;
      $port = $config['port'] ?? 3306; // $config['port']가 null이므로 3306 사용
      echo "포트: " . $port; // 3306

      // Null 병합 할당 연산자 (??=) - PHP 7.4+
      $settings['timeout'] = $settings['timeout'] ?? 5000; // 기존 방식
      $settings['timeout'] ??= 5000; // 변수가 null이면 기본값 할당 (위와 동일)
      ?&gt;</code></pre>

    <h3 id="operators-error-control">에러 제어 연산자 (@)</h3>
    <p>표현식 앞에서 <code>@</code> 기호를 사용하면 해당 표현식에서 발생하는 에러 메시지 출력을 억제합니다. 예를 들어 파일 열기 시 에러를 무시하고 직접 처리하고 싶을 때 사용될 수 있습니다.</p>
    <p class="warning">에러를 무시하는 것은 디버깅을 어렵게 만들고 잠재적인 문제를 숨길 수 있으므로, 매우 신중하게 꼭 필요한 경우에만 사용해야 합니다. 가급적 <code>try...catch</code>나 적절한 에러 처리 로직을 사용하는 것이 좋습니다.</p>
     <pre><code class="language-php">&lt;?php
     // 파일을 열 때 에러가 발생해도 메시지 표시 안 함
     $file_handle = @fopen("non_existent_file.txt", "r");

     if ($file_handle === false) {
         echo "파일 열기 실패! (에러 메시지 억제됨)";
     } else {
         // 파일 처리...
         fclose($file_handle);
     }
     ?&gt;</code></pre>

     <h3 id="operators-execution">실행 연산자 (백틱 ``)</h3>
     <p>백틱(<code>` `</code>)으로 감싸인 내용은 셸(shell) 명령어로 취급되어 서버에서 실행되고, 그 출력 결과가 반환됩니다.</p>
     <p class="warning">이 연산자는 심각한 보안 위험을 초래할 수 있습니다. 사용자 입력값을 포함하여 실행할 경우 명령어 삽입(Command Injection) 공격에 매우 취약해집니다. 또한 서버 설정(<code>shell_exec</code> 비활성화 등)에 따라 동작하지 않을 수 있습니다. <strong>가급적 사용하지 않는 것이 안전합니다.</strong></p>
      <pre><code class="language-php">&lt;?php
      // 예시 (주의해서 사용!)
      // $output = `ls -l`; // 현재 디렉토리 목록 (Linux/macOS)
      // $output = `dir`;    // 현재 디렉토리 목록 (Windows)
      // echo "&lt;pre&gt;$output&lt;/pre&gt;";
      ?></code></pre>

      <h3 id="operators-type">타입 연산자 (instanceof)</h3>
      <p>객체가 특정 클래스의 인스턴스인지, 또는 특정 클래스를 상속받았는지, 특정 인터페이스를 구현했는지 확인합니다.</p>
       <pre><code class="language-php">&lt;?php
       class MyClass {}
       class ChildClass extends MyClass {}

       $obj1 = new MyClass();
       $obj2 = new ChildClass();
       $obj3 = new stdClass();

       var_dump($obj1 instanceof MyClass);    // bool(true)
       var_dump($obj2 instanceof MyClass);    // bool(true) (상속받았으므로)
       var_dump($obj2 instanceof ChildClass); // bool(true)
       var_dump($obj3 instanceof MyClass);    // bool(false)
       ?&gt;</code></pre>
</section>


<br><br>
<hr>

<section id="php-control-structures">
    <h2>제어 구조 (Control Structures)</h2>
    <p>제어 구조는 프로그램의 실행 흐름을 조건에 따라 분기하거나 특정 코드를 반복 실행하도록 제어합니다.</p>

    <h3 id="control-conditional">조건문 (if, elseif, else, switch)</h3>
    <p>조건문은 주어진 조건의 참(true)/거짓(false) 여부에 따라 다른 코드 블록을 실행합니다.</p>

    <h4>if, elseif, else</h4>
    <ul>
        <li><code>if (condition) { ... }</code>: 조건이 참일 때 코드 실행.</li>
        <li><code>elseif (condition) { ... }</code> (또는 <code>else if</code>): 이전 조건이 거짓이고 현재 조건이 참일 때 실행.</li>
        <li><code>else { ... }</code>: 모든 이전 조건이 거짓일 때 실행.</li>
    </ul>
    <pre><code class="language-php">&lt;?php
    $score = 85;

    if ($score >= 90) {
        echo "A 등급";
    } elseif ($score >= 80) { // elseif 또는 else if 사용 가능
        echo "B 등급"; // 이 블록 실행
    } elseif ($score >= 70) {
        echo "C 등급";
    } else {
        echo "F 등급";
    }
    echo "&lt;br&gt;";

    $is_member = true;
    $points = 120;

    if ($is_member) {
        echo "회원님, 환영합니다!";
        if ($points > 100) { // 중첩 if
            echo " 특별 혜택 대상입니다!";
        }
        echo "&lt;br&gt;";
    } else {
        echo "로그인이 필요합니다.&lt;br&gt;";
    }
    ?&gt;</code></pre>

    <h4>switch</h4>
    <p><code>switch</code> 문은 하나의 표현식 값을 여러 특정 값(<code>case</code>)과 비교하여 일치하는 블록을 실행합니다.</p>
    <ul>
        <li><code>case value:</code>: 비교할 값. 표현식 값과 <code>case</code> 값이 느슨한 비교(<code>==</code>)로 일치하면 실행.</li>
        <li><code>break;</code>: <code>switch</code> 문 실행을 종료. <code>break</code>가 없으면 다음 <code>case</code> 코드가 계속 실행됨 (fall-through).</li>
        <li><code>default:</code>: 어떤 <code>case</code>와도 일치하지 않을 때 실행 (선택 사항).</li>
    </ul>
    <pre><code class="language-php">&lt;?php
    $day_of_week = date('N'); // 현재 요일 (1: 월요일, ..., 7: 일요일)

    switch ($day_of_week) {
        case 1:
            echo "월요일입니다.";
            break;
        case 2:
            echo "화요일입니다.";
            break;
        case 3:
            echo "수요일입니다.";
            break;
        case 4:
            echo "목요일입니다.";
            break;
        case 5:
            echo "금요일입니다.";
            break;
        case 6:
        case 7: // case 6 또는 7일 때 동일 코드 실행 (fall-through 활용)
            echo "주말입니다!";
            break;
        default:
            echo "알 수 없는 요일입니다.";
    }
    ?&gt;</code></pre>

    <h3 id="control-loops">반복문 (for, while, do-while, foreach)</h3>
    <p>반복문은 특정 조건이 만족되는 동안 코드 블록을 반복해서 실행합니다.</p>

    <h4>for</h4>
    <p>정해진 횟수만큼 반복할 때 주로 사용됩니다.</p>
    <p><code>for (초기화; 조건; 증감) { ... }</code></p>
    <pre><code class="language-php">&lt;?php
    for ($i = 0; $i < 5; $i++) {
        echo "for 반복: " . $i . "&lt;br&gt;";
    }
    ?&gt;</code></pre>

    <h4>while</h4>
    <p>조건이 참인 동안 계속 반복합니다. 조건 검사를 루프 시작 전에 합니다.</p>
    <p><code>while (조건) { ... }</code></p>
    <pre><code class="language-php">&lt;?php
    $count = 0;
    while ($count < 3) {
        echo "while 반복: " . $count . "&lt;br&gt;";
        $count++;
    }
    ?&gt;</code></pre>

    <h4>do-while</h4>
    <p><code>while</code>과 유사하지만, 조건을 루프 실행 후에 검사합니다. 따라서 루프 본문이 최소 한 번은 실행됩니다.</p>
    <p><code>do { ... } while (조건);</code></p>
    <pre><code class="language-php">&lt;?php
    $attempts = 5;
    do {
        echo "do-while 시도: " . $attempts . "&lt;br&gt;"; // 최소 한 번 실행
        $attempts++;
    } while ($attempts < 5); // 조건은 처음부터 거짓
    ?&gt;</code></pre>

    <h4>foreach</h4>
    <p>배열이나 객체의 모든 요소를 순회할 때 가장 많이 사용되는 편리한 반복문입니다.</p>
    <p><code>foreach (배열 as $value) { ... }</code> (값만 필요할 때)<br>
       <code>foreach (배열 as $key => $value) { ... }</code> (키와 값 모두 필요할 때)</p>
    <pre><code class="language-php">&lt;?php
    $colors = ["red", "green", "blue"];
    foreach ($colors as $color) { // 값만 순회
        echo "색상: " . $color . "&lt;br&gt;";
    }

    $user_info = ["name" => "Alice", "age" => 30, "city" => "Seoul"];
    foreach ($user_info as $key => $value) { // 키와 값 순회
        echo htmlspecialchars($key) . ": " . htmlspecialchars($value) . "&lt;br&gt;";
    }
    ?&gt;</code></pre>

    <h3 id="control-break-continue">break, continue</h3>
    <ul>
        <li><code>break;</code>: 현재 실행 중인 반복문(<code>for</code>, <code>while</code>, <code>do-while</code>, <code>foreach</code>)이나 <code>switch</code> 문을 즉시 종료합니다.</li>
        <li><code>continue;</code>: 현재 반복문의 나머지 부분을 건너뛰고 다음 반복을 시작합니다.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     for ($i = 0; $i < 10; $i++) {
         if ($i == 3) {
             continue; // i가 3일 때는 아래 echo를 건너뛰고 다음 반복(i=4)으로
         }
         if ($i == 7) {
             break; // i가 7이면 반복문 완전 종료
         }
         echo "현재 숫자: " . $i . "&lt;br&gt;";
     }
     // 출력: 0, 1, 2, 4, 5, 6
     ?&gt;</code></pre>
     <p><code>break N;</code> 또는 <code>continue N;</code> 처럼 숫자를 지정하여 여러 단계의 중첩 루프를 한 번에 빠져나오거나 건너뛸 수도 있지만, 코드 가독성을 해칠 수 있어 권장되지는 않습니다.</p>

    <h3 id="control-alternative-syntax">대체 문법</h3>
    <p>PHP는 <code>if</code>, <code>while</code>, <code>for</code>, <code>foreach</code>, <code>switch</code> 제어 구조에 대해 중괄호(<code>{}</code>) 대신 콜론(<code>:</code>)과 <code>endif;</code>, <code>endwhile;</code>, <code>endfor;</code>, <code>endforeach;</code>, <code>endswitch;</code>를 사용하는 대체 문법을 제공합니다. 주로 HTML 템플릿 내에서 PHP 코드를 삽입할 때 가독성을 높이기 위해 사용됩니다.</p>
     <pre><code class="language-php">&lt;ul&gt;
    &lt;?php for ($i = 1; $i <= 3; $i++): ?&gt;
        &lt;li&gt;아이템 &lt;?php echo $i; ?&gt;&lt;/li&gt;
    &lt;?php endfor; ?&gt;
&lt;/ul&gt;

&lt;?php $is_logged_in = true; ?&gt;
&lt;?php if ($is_logged_in): ?&gt;
    &lt;p&gt;환영합니다!&lt;/p&gt;
&lt;?php else: ?&gt;
    &lt;p&gt;로그인이 필요합니다.&lt;/p&gt;
&lt;?php endif; ?&gt;
</code></pre>
</section>

<section id="php-functions">
    <h2>함수 (Functions)</h2>
    <p>함수는 특정 작업을 수행하는 코드 블록을 정의하고, 필요할 때마다 호출하여 재사용할 수 있게 해주는 기능입니다. 코드를 구조화하고 유지보수를 용이하게 합니다.</p>

    <h3 id="functions-defining">함수 정의 및 호출</h3>
    <p><code>function</code> 키워드와 함수 이름, 괄호<code>()</code> 안의 매개변수 목록, 중괄호<code>{}</code> 안의 함수 본문으로 함수를 정의합니다.</p>
    <p>정의된 함수는 함수 이름과 괄호<code>()</code>를 사용하여 호출합니다.</p>
     <pre><code class="language-php">&lt;?php
     // 함수 정의
     function sayHello() {
         echo "안녕하세요!&lt;br&gt;";
     }

     // 함수 호출
     sayHello(); // 출력: 안녕하세요!

     // 매개변수가 있는 함수 정의
     function greetUser($name) {
         echo "반갑습니다, " . htmlspecialchars($name) . "님!&lt;br&gt;";
     }

     // 함수 호출 (인수 전달)
     greetUser("홍길동"); // 출력: 반갑습니다, 홍길동님!
     greetUser("Alice"); // 출력: 반갑습니다, Alice님!
     ?&gt;</code></pre>
     <p>함수 이름은 일반적으로 camelCase 또는 snake_case를 사용하며, 일관성 있는 규칙을 따르는 것이 좋습니다. 함수 이름은 대소문자를 구분하지 않습니다.</p>

    <h3 id="functions-parameters">매개변수 및 인수 (기본값, 타입 힌팅, 가변 인자)</h3>
    <ul>
        <li><strong>매개변수(Parameter):</strong> 함수 정의 시 괄호 안에 선언되어 함수로 전달될 값을 받는 변수.</li>
        <li><strong>인수(Argument):</strong> 함수 호출 시 괄호 안에 전달하는 실제 값.</li>
        <li><strong>기본 매개변수 값 (Default Argument Values):</strong> 매개변수에 기본값을 할당하여, 함수 호출 시 해당 인수가 전달되지 않으면 기본값을 사용하도록 할 수 있습니다. 기본값이 있는 매개변수는 반드시 기본값 없는 매개변수 뒤에 위치해야 합니다.
             <pre><code class="language-php">function printMessage($message, $prefix = "[INFO]") {
    echo $prefix . " " . htmlspecialchars($message) . "&lt;br&gt;";
}
printMessage("작업 완료"); // 출력: [INFO] 작업 완료
printMessage("에러 발생", "[ERROR]"); // 출력: [ERROR] 에러 발생</code></pre>
        </li>
        <li><strong>타입 힌팅 (Type Hinting / Type Declarations - PHP 5+):</strong> 매개변수나 반환 값의 타입을 명시하여 코드의 안정성과 가독성을 높일 수 있습니다. 타입이 일치하지 않으면 오류가 발생합니다 (기본적으로는 TypeError).
            <ul>
                <li>스칼라 타입 (<code>int</code>, <code>float</code>, <code>string</code>, <code>bool</code>) - PHP 7.0+</li>
                <li>클래스/인터페이스 이름</li>
                <li><code>array</code></li>
                <li><code>callable</code></li>
                <li><code>iterable</code> (PHP 7.1+)</li>
                <li><code>object</code> (PHP 7.2+)</li>
                <li><code>mixed</code> (PHP 8.0+) - 여러 타입 가능</li>
                <li><code>void</code> (반환 타입 전용, PHP 7.1+) - 아무것도 반환하지 않음</li>
                <li>Nullable types (<code>?Type</code> - PHP 7.1+): 해당 타입 또는 <code>null</code> 허용.</li>
                <li>Union types (<code>Type1|Type2</code> - PHP 8.0+): 명시된 타입 중 하나 허용.</li>
            </ul>
             <pre><code class="language-php">declare(strict_types=1); // 엄격한 타입 모드 활성화 (권장)

function addNumbers(int $a, float $b): float { // 매개변수 타입, 반환 타입 명시
    return $a + $b;
}
$sum = addNumbers(5, 3.5); // int와 float 전달 -> float 반환
var_dump($sum); // float(8.5)
// addNumbers("5", "3.5"); // strict_types=1 에서는 TypeError 발생

function processUser(?User $user): void { // User 객체 또는 null 허용, 반환값 없음
    if ($user !== null) {
        echo "처리 중인 사용자: " . $user->name;
    } else {
        echo "사용자 정보 없음";
    }
}
// class User { public $name; } // User 클래스 정의 필요
</code></pre>
        </li>
         <li><strong>가변 인자 함수 (Variadic Functions - PHP 5.6+):</strong> <code>...</code> 연산자를 사용하여 정해지지 않은 개수의 인수를 배열로 받을 수 있습니다. <code>...</code> 매개변수는 반드시 마지막 매개변수여야 합니다.
             <pre><code class="language-php">function sumAll(...$numbers): int {
    $total = 0;
    foreach ($numbers as $num) {
        if (is_int($num)) { // 정수만 더하기 (예시)
            $total += $num;
        }
    }
    return $total;
}
echo sumAll(1, 2, 3, 4, 5); // 15
echo "&lt;br&gt;";
echo sumAll(10, 20); // 30
</code></pre>
          <p>인수 목록에서 <code>...</code>를 사용하여 배열이나 이터러블 객체의 요소를 개별 인수로 펼쳐서 전달할 수도 있습니다 (Argument Unpacking).</p>
           <pre><code class="language-php">$nums = [10, 20, 30];
echo sumAll(...$nums); // sumAll(10, 20, 30) 과 동일 -> 60
</code></pre>
        </li>
    </ul>

    <h3 id="functions-return">반환 값 (return, 반환 타입 힌팅)</h3>
    <p><code>return</code> 문은 함수의 실행을 종료하고 지정된 값을 함수 호출 지점으로 반환합니다. <code>return</code> 문 뒤의 코드는 실행되지 않습니다.</p>
    <p>함수에서 명시적으로 <code>return</code> 문을 사용하지 않으면 <code>NULL</code>이 반환됩니다.</p>
    <p>PHP 7.0부터는 반환 값의 타입을 명시할 수 있습니다 (<code>function name(...): returnType { ... }</code>).</p>
     <pre><code class="language-php">&lt;?php
     function multiply(float $a, float $b): float { // 반환 타입을 float로 명시
         return $a * $b;
     }
     $product = multiply(4.5, 2.0);
     var_dump($product); // float(9)

     function checkAge(int $age): string {
         if ($age >= 19) {
             return "성인"; // 문자열 반환
         } else {
             return "미성년자"; // 문자열 반환
         }
         // 이 아래 코드는 실행되지 않음
         echo "함수 끝";
     }
     echo checkAge(25); // 성인

     function noReturn() {
         echo "&lt;br&gt;아무것도 반환하지 않는 함수.";
         // return; // NULL 반환
     }
     var_dump(noReturn()); // NULL
     ?&gt;</code></pre>

    <h3 id="functions-scope">함수 내 변수 스코프 (global, static)</h3>
    <p>함수 내에서 변수를 다룰 때 스코프를 이해하는 것이 중요합니다.</p>
    <ul>
        <li>함수 내에서 선언된 변수는 기본적으로 지역 변수입니다.</li>
        <li>함수 내에서 전역 변수에 접근하려면 <code>global $variableName;</code> 또는 <code>$GLOBALS['variableName']</code>을 사용해야 합니다. (Part 1 내용 복습)</li>
        <li><code>static</code> 키워드를 사용하여 함수 내에 정적 변수를 선언하면, 함수 호출이 끝나도 값이 유지되어 다음 호출 시 이전 값을 사용할 수 있습니다. (Part 1 내용 복습)</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $counter = 0; // 전역 변수

     function incrementCounter() {
         global $counter; // 전역 변수 사용 선언
         $counter++;
         echo "전역 카운터: " . $counter . "&lt;br&gt;";

         static $local_static_counter = 0; // 정적 지역 변수
         $local_static_counter++;
         echo "정적 지역 카운터: " . $local_static_counter . "&lt;br&gt;";
     }

     incrementCounter();
     incrementCounter();
     ?&gt;</code></pre>

    <h3 id="functions-anonymous">익명 함수 (클로저)</h3>
    <p>익명 함수(Anonymous Function)는 이름이 없는 함수로, 주로 변수에 할당되거나 다른 함수의 인수로 전달(콜백 함수)됩니다. PHP에서 익명 함수는 클로저(Closure) 객체로 구현됩니다.</p>
    <p>클로저는 자신이 정의된 스코프의 변수를 "기억"하고 접근할 수 있는 특징을 가집니다. 외부 변수를 클로저 내부에서 사용하려면 <code>use</code> 키워드를 사용합니다.</p>
     <pre><code class="language-php">&lt;?php
     // 변수에 익명 함수 할당
     $greet = function($name) {
         echo "Hello, " . htmlspecialchars($name) . "!&lt;br&gt;";
     }; // 문장의 끝이므로 세미콜론 필요

     $greet("Anonymous Function"); // 변수를 통해 함수 호출

     // 외부 변수를 사용하는 클로저 (use 키워드)
     $messagePrefix = "[DEBUG]";
     $logger = function($logMessage) use ($messagePrefix) {
         // use 키워드로 외부 변수 $messagePrefix를 클로저 내부로 가져옴
         echo $messagePrefix . " " . htmlspecialchars($logMessage) . "&lt;br&gt;";
         // $messagePrefix = "[INFO]"; // 클로저 내부에서 use로 가져온 변수 수정 불가 (값 복사)
         // 참조로 가져오려면 use (&$messagePrefix) 사용
     };

     $logger("사용자 로그인 성공"); // 출력: [DEBUG] 사용자 로그인 성공
     $logger("데이터베이스 연결 오류"); // 출력: [DEBUG] 데이터베이스 연결 오류

     // 콜백 함수로 익명 함수 사용 예시 (array_map)
     $numbers = [1, 2, 3, 4];
     $squared = array_map(function($n) {
         return $n * $n;
     }, $numbers);
     print_r($squared); // Array ( [0] => 1 [1] => 4 [2] => 9 [3] => 16 )
     ?&gt;</code></pre>

    <h3 id="functions-arrow">화살표 함수 (PHP 7.4+)</h3>
    <p>화살표 함수(Arrow Function)는 익명 함수를 더 간결하게 작성하는 문법입니다. <code>fn</code> 키워드를 사용하며, 항상 단일 표현식만 가질 수 있고 그 표현식의 결과가 자동으로 반환됩니다.</p>
    <p>가장 큰 특징은 <strong>외부 스코프의 변수를 자동으로 "캡처"하여 사용할 수 있다</strong>는 점입니다 (<code>use</code> 키워드 불필요).</p>
    <p><code>fn(parameter_list) => expression</code></p>
     <pre><code class="language-php">&lt;?php
     $factor = 10;

     // 익명 함수 (use 필요)
     $multiplier_anon = function($n) use ($factor) {
         return $n * $factor;
     };

     // 화살표 함수 (use 불필요, $factor 자동 캡처)
     $multiplier_arrow = fn($n) => $n * $factor;

     echo $multiplier_anon(5); // 50
     echo "&lt;br&gt;";
     echo $multiplier_arrow(5); // 50
     echo "&lt;br&gt;";

     // array_map과 화살표 함수 사용
     $numbers = [1, 2, 3, 4];
     $tripled = array_map(fn($n) => $n * 3, $numbers);
     print_r($tripled); // Array ( [0] => 3 [1] => 6 [2] => 9 [3] => 12 )
     ?&gt;</code></pre>
     <p>화살표 함수는 주로 콜백 함수 등 간단한 함수를 정의할 때 코드를 매우 간결하게 만들어 줍니다.</p>

    <h3 id="functions-built-in">내장 함수 활용</h3>
    <p>PHP는 문자열 처리, 배열 조작, 수학 계산, 날짜/시간 처리, 파일 시스템 접근, 데이터베이스 연동 등 다양한 작업을 위한 수많은 내장 함수(Built-in Functions)를 제공합니다.</p>
    <p>모든 내장 함수를 외울 필요는 없으며, 필요할 때 <a href="https://www.php.net/manual/en/" target="_blank">PHP 공식 매뉴얼</a>을 참조하여 원하는 기능을 찾아 사용하는 것이 중요합니다.</p>
    <p>몇 가지 예시:</p>
    <ul>
        <li><strong>문자열 함수:</strong> <code>strlen()</code>, <code>strpos()</code>, <code>substr()</code>, <code>str_replace()</code>, <code>strtolower()</code>, <code>strtoupper()</code>, <code>trim()</code>, <code>explode()</code>, <code>implode()</code>, <code>htmlspecialchars()</code></li>
        <li><strong>배열 함수:</strong> <code>count()</code>, <code>sort()</code>, <code>in_array()</code>, <code>array_push()</code>, <code>array_key_exists()</code> (다음 섹션에서 더 자세히)</li>
        <li><strong>수학 함수:</strong> <code>abs()</code>, <code>round()</code>, <code>ceil()</code>, <code>floor()</code>, <code>rand()</code>/<code>mt_rand()</code>, <code>max()</code>, <code>min()</code>, <code>sqrt()</code></li>
        <li><strong>날짜/시간 함수:</strong> <code>date()</code>, <code>time()</code>, <code>strtotime()</code>, <code>mktime()</code></li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $myString = "  PHP is Fun!  ";
     echo "문자열 길이: " . strlen($myString) . "&lt;br&gt;"; // 17 (공백 포함)
     echo "소문자로: " . strtolower($myString) . "&lt;br&gt;"; //   php is fun!
     echo "앞뒤 공백 제거: '" . trim($myString) . "'&lt;br&gt;"; // 'PHP is Fun!'
     echo "'Fun' 위치: " . strpos($myString, "Fun") . "&lt;br&gt;"; // 9 (0부터 시작)
     $replaced = str_replace("Fun", "Awesome", $myString);
     echo "치환 결과: " . $replaced . "&lt;br&gt;"; //   PHP is Awesome!

     $timestamp = time(); // 현재 유닉스 타임스탬프
     echo "현재 타임스탬프: " . $timestamp . "&lt;br&gt;";
     echo "현재 날짜/시간: " . date("Y-m-d H:i:s", $timestamp) . "&lt;br&gt;"; // 포맷 지정 출력
     ?&gt;</code></pre>
</section>

<section id="php-arrays">
    <h2>배열 (Arrays)</h2>
    <p>배열은 여러 개의 값을 하나의 변수에 저장하고 관리할 수 있는 강력한 데이터 구조입니다. PHP 배열은 매우 유연하여 다양한 형태와 기능을 가집니다.</p>

    <h3 id="arrays-types">배열 종류 (인덱스, 연관, 다차원)</h3>
    <ul>
        <li><strong>인덱스 배열 (Indexed Arrays):</strong> 숫자 인덱스(0부터 시작)를 사용하여 값에 접근하는 배열입니다.
            <pre><code class="language-php">$colors = ["red", "green", "blue"]; // 0 => "red", 1 => "green", 2 => "blue"
echo $colors[0]; // red</code></pre>
        </li>
        <li><strong>연관 배열 (Associative Arrays):</strong> 문자열 키(key)를 사용하여 값(value)에 접근하는 배열입니다. 키는 고유해야 합니다.
            <pre><code class="language-php">$user = ["name" => "Alice", "age" => 30, "city" => "Seoul"];
echo $user["name"]; // Alice</code></pre>
        </li>
        <li><strong>다차원 배열 (Multidimensional Arrays):</strong> 배열의 요소로 또 다른 배열을 포함하는 배열입니다. 테이블 형태의 데이터 등을 표현할 때 유용합니다.
            <pre><code class="language-php">$matrix = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
];
echo $matrix[1][2]; // 6 (두 번째 행, 세 번째 열)

$users = [
    ["name" => "Alice", "age" => 30],
    ["name" => "Bob", "age" => 25]
];
echo $users[0]["name"]; // Alice</code></pre>
        </li>
    </ul>
    <p class="note">PHP 배열은 인덱스 키와 문자열 키를 혼합하여 사용할 수도 있습니다.</p>

    <h3 id="arrays-creating">배열 생성 (array(), [])</h3>
    <p>배열은 <code>array()</code> 언어 구조 또는 단축 문법 <code>[]</code> (PHP 5.4+ 권장)를 사용하여 생성합니다.</p>
    <pre><code class="language-php">&lt;?php
    // 인덱스 배열 생성
    $fruits_long = array("apple", "banana", "cherry");
    $fruits_short = ["apple", "banana", "cherry"]; // 권장

    // 연관 배열 생성
    $person_long = array("name" => "Charlie", "age" => 35);
    $person_short = [ // 권장
        "name" => "Charlie",
        "age" => 35,
        "city" => "Busan" // 나중에 요소 추가 용이
    ];

    // 빈 배열 생성
    $empty_array = [];

    print_r($fruits_short); echo "&lt;br&gt;";
    print_r($person_short); echo "&lt;br&gt;";
    ?&gt;</code></pre>

    <h3 id="arrays-accessing">요소 접근 및 조작</h3>
    <p>배열 요소는 대괄호<code>[]</code> 안에 키(인덱스 또는 문자열)를 지정하여 접근합니다.</p>
    <ul>
        <li><strong>요소 읽기:</strong> <code>$array[key]</code></li>
        <li><strong>요소 수정:</strong> <code>$array[key] = newValue;</code></li>
        <li><strong>새 요소 추가:</strong>
            <ul>
                <li>인덱스 배열 끝에 추가: <code>$array[] = value;</code></li>
                <li>연관 배열에 새 키로 추가: <code>$array['new_key'] = value;</code></li>
            </ul>
        </li>
        <li><strong>요소 존재 확인:</strong>
            <ul>
                <li><code>isset($array[key])</code>: 키가 존재하고 값이 <code>NULL</code>이 아닌지 확인.</li>
                <li><code>array_key_exists(key, $array)</code>: 키가 배열에 존재하는지 확인 (값이 <code>NULL</code>이어도 true).</li>
                <li><code>empty($array[key])</code>: 키가 존재하지 않거나, 존재하더라도 값이 "비어있다"고 간주되는 값(<code>false</code>, <code>0</code>, <code>"0"</code>, <code>""</code>, <code>null</code>, <code>[]</code>)인지 확인.</li>
            </ul>
        </li>
        <li><strong>요소 삭제:</strong> <code>unset($array[key]);</code></li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $car = ["brand" => "Kia", "model" => "Sorento", "year" => 2024];

     // 읽기
     echo "모델: " . $car["model"]; // Sorento
     echo "&lt;br&gt;";

     // 수정
     $car["year"] = 2025;
     echo "연식 변경: " . $car["year"]; // 2025
     echo "&lt;br&gt;";

     // 새 요소 추가 (연관 배열)
     $car["color"] = "White";

     // 새 요소 추가 (인덱스 배열)
     $numbers = [10, 20];
     $numbers[] = 30; // 끝에 30 추가 (인덱스 2)
     $numbers[5] = 50; // 특정 인덱스에 추가 (중간 인덱스 건너뜀)

     print_r($car); echo "&lt;br&gt;";
     print_r($numbers); echo "&lt;br&gt;"; // Array ( [0] => 10 [1] => 20 [2] => 30 [5] => 50 )

     // 존재 확인
     var_dump(isset($car["model"])); // bool(true)
     var_dump(array_key_exists("color", $car)); // bool(true)
     var_dump(empty($car["price"])); // bool(true) (price 키가 없으므로)

     // 삭제
     unset($car["year"]);
     print_r($car); echo "&lt;br&gt;"; // year 키/값 삭제됨
     ?&gt;</code></pre>

    <h3 id="arrays-looping">배열 순회 (foreach)</h3>
    <p><code>foreach</code>는 배열의 모든 요소를 반복하는 가장 쉽고 일반적인 방법입니다.</p>
     <pre><code class="language-php">&lt;?php
     $languages = ["PHP", "JavaScript", "Python"];
     echo "&lt;ul&gt;";
     foreach ($languages as $lang) {
         echo "&lt;li&gt;" . htmlspecialchars($lang) . "&lt;/li&gt;";
     }
     echo "&lt;/ul&gt;";

     $capitals = ["South Korea" => "Seoul", "Japan" => "Tokyo", "USA" => "Washington D.C."];
     echo "&lt;dl&gt;";
     foreach ($capitals as $country => $city) {
         echo "&lt;dt&gt;" . htmlspecialchars($country) . "&lt;/dt&gt;";
         echo "&lt;dd&gt;" . htmlspecialchars($city) . "&lt;/dd&gt;";
     }
     echo "&lt;/dl&gt;";
     ?&gt;</code></pre>
     <p><code>for</code> 루프는 인덱스가 순차적인 숫자일 때 사용할 수 있지만, <code>foreach</code>가 더 안전하고 편리합니다.</p>

    <h3 id="arrays-functions">주요 배열 함수</h3>
    <p>PHP는 배열을 다루는 데 유용한 다양한 내장 함수를 제공합니다.</p>
    <ul>
        <li><code>count($array)</code>: 배열 요소의 개수를 반환.</li>
        <li>정렬 함수:
            <ul>
                <li><code>sort($array)</code>: 값 기준 오름차순 정렬 (키 유지 안 함).</li>
                <li><code>rsort($array)</code>: 값 기준 내림차순 정렬 (키 유지 안 함).</li>
                <li><code>asort($array)</code>: 값 기준 오름차순 정렬 (키-값 연관 유지).</li>
                <li><code>arsort($array)</code>: 값 기준 내림차순 정렬 (키-값 연관 유지).</li>
                <li><code>ksort($array)</code>: 키 기준 오름차순 정렬.</li>
                <li><code>krsort($array)</code>: 키 기준 내림차순 정렬.</li>
                <li><code>usort($array, $callback)</code>, <code>uasort()</code>, <code>uksort()</code>: 사용자 정의 함수로 정렬.</li>
            </ul>
        </li>
        <li>추가/제거 함수:
            <ul>
                <li><code>array_push($array, $value1, ...)</code>: 배열 끝에 하나 이상의 요소 추가. (<code>$array[] = $value;</code> 와 유사)</li>
                <li><code>array_pop($array)</code>: 배열 끝 요소를 제거하고 반환.</li>
                <li><code>array_unshift($array, $value1, ...)</code>: 배열 시작 부분에 하나 이상의 요소 추가.</li>
                <li><code>array_shift($array)</code>: 배열 시작 부분 요소를 제거하고 반환.</li>
            </ul>
        </li>
        <li><code>array_merge($array1, $array2, ...)</code>: 여러 배열을 병합하여 새 배열 반환. 동일한 문자열 키는 뒤의 배열 값으로 덮어씀. 숫자 키는 재인덱싱됨. (<code>+</code> 연산자와 동작 방식 다름)</li>
        <li><code>array_keys($array)</code>: 배열의 모든 키를 담은 새 배열 반환.</li>
        <li><code>array_values($array)</code>: 배열의 모든 값을 담은 새 배열 반환.</li>
        <li><code>in_array($needle, $haystack, $strict = false)</code>: 배열(<code>$haystack</code>)에 특정 값(<code>$needle</code>)이 존재하는지 확인. <code>$strict</code>가 <code>true</code>면 타입까지 비교.</li>
        <li><code>array_key_exists($key, $array)</code>: 배열에 특정 키가 존재하는지 확인.</li>
        <li><code>array_search($needle, $haystack, $strict = false)</code>: 배열에서 특정 값의 첫 번째 키(인덱스 또는 문자열 키)를 반환. 없으면 <code>false</code> 반환.</li>
        <li><code>list($var1, $var2, ...) = $array;</code>: 배열의 요소를 개별 변수에 할당 (인덱스 배열에 주로 사용, 구문). PHP 7.1부터 <code>[$var1, $var2] = $array;</code> 단축 문법 가능.</li>
        <li><code>explode($delimiter, $string)</code>: 문자열을 구분자(<code>$delimiter</code>)로 분할하여 배열로 반환.</li>
        <li><code>implode($glue, $array)</code> (또는 <code>join()</code>): 배열 요소들을 연결 문자열(<code>$glue</code>)로 이어붙여 하나의 문자열로 반환.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $my_array = ["b" => 5, "a" => 3, "c" => 8];
     echo "배열 크기: " . count($my_array) . "&lt;br&gt;"; // 3

     asort($my_array); // 값 기준 오름차순 정렬 (키 유지)
     print_r($my_array); echo "&lt;br&gt;"; // Array ( [a] => 3 [b] => 5 [c] => 8 )

     ksort($my_array); // 키 기준 오름차순 정렬
     print_r($my_array); echo "&lt;br&gt;"; // Array ( [a] => 3 [b] => 5 [c] => 8 ) (이미 키 순서였음)

     if (in_array(5, $my_array)) { echo "배열에 5가 있습니다.&lt;br&gt;"; }
     if (array_key_exists("c", $my_array)) { echo "배열에 'c' 키가 있습니다.&lt;br&gt;"; }

     $keys = array_keys($my_array);
     print_r($keys); echo "&lt;br&gt;"; // Array ( [0] => a [1] => b [2] => c )

     $values = array_values($my_array);
     print_r($values); echo "&lt;br&gt;"; // Array ( [0] => 3 [1] => 5 [2] => 8 )

     // list() 예전 방식
     $colors = ["red", "green", "blue"];
     list($c1, $c2, $c3) = $colors;
     echo $c1; // red
     echo "&lt;br&gt;";

     // 배열 구조 분해 (PHP 7.1+)
     [$r, $g, $b] = $colors;
     echo $g; // green
     echo "&lt;br&gt;";

     // explode / implode
     $tags_string = "php, javascript, css";
     $tags_array = explode(", ", $tags_string); // 콤마와 공백으로 분리
     print_r($tags_array); echo "&lt;br&gt;"; // Array ( [0] => php [1] => javascript [2] => css )
     $tags_rejoined = implode(" | ", $tags_array); // | 로 다시 합치기
     echo $tags_rejoined; // php | javascript | css
     ?&gt;</code></pre>
</section>

<br><br>
<hr>

<section id="php-web">
    <h2>웹 페이지 연동</h2>
    <p>PHP는 웹 개발을 위해 탄생한 언어답게, 웹 페이지와 상호작용하는 다양한 기능을 제공합니다. 사용자가 입력한 폼 데이터를 처리하고, URL 정보를 다루며, HTTP 헤더를 제어하고, 공통된 페이지 요소를 포함시키는 방법을 알아봅니다.</p>

    <h3 id="web-forms">HTML 폼 처리 (GET, POST)</h3>
    <p>웹 페이지에서 사용자 입력을 받는 가장 일반적인 방법은 HTML 폼(<code>&lt;form&gt;</code>)을 사용하는 것입니다. PHP는 폼을 통해 전송된 데이터를 쉽게 받아 처리할 수 있습니다.</p>
    <p>폼 데이터 전송 방식에는 주로 GET과 POST가 사용됩니다.</p>
    <ul>
        <li><strong>GET 방식:</strong>
            <ul>
                <li>데이터가 URL의 쿼리 스트링(Query String)에 포함되어 전송됩니다 (예: <code>mypage.php?name=Alice&age=30</code>).</li>
                <li>URL에 데이터가 노출되므로 보안에 민감한 데이터 전송에는 부적합합니다.</li>
                <li>전송 데이터 길이에 제한이 있습니다.</li>
                <li>주로 검색어나 페이지 번호 등 간단하고 노출되어도 괜찮은 데이터를 전달할 때 사용됩니다.</li>
                <li>PHP에서는 <code>$_GET</code> 슈퍼글로벌 배열을 통해 데이터에 접근합니다.</li>
            </ul>
        </li>
        <li><strong>POST 방식:</strong>
            <ul>
                <li>데이터가 HTTP 요청 본문(body)에 포함되어 전송됩니다. URL에 노출되지 않습니다.</li>
                <li>GET 방식보다 보안성이 높고, 데이터 길이 제한이 거의 없습니다.</li>
                <li>주로 회원 가입, 로그인, 파일 업로드 등 민감하거나 용량이 큰 데이터를 전송할 때 사용됩니다.</li>
                <li>PHP에서는 <code>$_POST</code> 슈퍼글로벌 배열을 통해 데이터에 접근합니다.</li>
            </ul>
        </li>
    </ul>

    <div class="example">
        <h4>GET 방식 폼 예제</h4>
        <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>#web-forms">
            <label for="search_query">검색어:</label>
            <input type="text" id="search_query" name="q">
            <button type="submit">검색 (GET)</button>
        </form>
        <div class="output">
            <?php
            if (isset($_GET['q'])) {
                $query = htmlspecialchars($_GET['q']); // XSS 방지
                echo "GET으로 검색된 단어: <strong>" . $query . "</strong>";
            } else {
                echo "GET 검색어가 없습니다.";
            }
            ?>
        </div>
    </div>

    <div class="example">
        <h4>POST 방식 폼 예제</h4>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>#web-forms">
            <label for="username_post">사용자명:</label>
            <input type="text" id="username_post" name="username">
            <label for="password_post">비밀번호:</label>
            <input type="password" id="password_post" name="password">
            <button type="submit">로그인 (POST)</button>
        </form>
         <div class="output">
            <?php
            // $_SERVER['REQUEST_METHOD']로 요청 방식 확인
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
                $username = htmlspecialchars($_POST['username']);
                // 비밀번호는 실제 서비스에서는 절대 그대로 출력하면 안 됩니다!
                // $password = $_POST['password'];
                echo "POST로 제출된 사용자명: <strong>" . $username . "</strong>";
                // echo "<br>비밀번호 길이: " . strlen($password);
            } else {
                 echo "POST 데이터가 없습니다.";
            }
            ?>
        </div>
    </div>

    <p><code>$_REQUEST</code> 슈퍼글로벌 배열은 <code>$_GET</code>, <code>$_POST</code>, <code>$_COOKIE</code> 데이터를 모두 포함하지만, 데이터 출처가 불분명하고 보안상 혼란을 야기할 수 있으므로 가급적 사용하지 않고 <code>$_GET</code> 또는 <code>$_POST</code>를 명시적으로 사용하는 것이 좋습니다.</p>
    <p class="warning">사용자로부터 받은 모든 데이터(<code>$_GET</code>, <code>$_POST</code> 등)는 신뢰할 수 없으므로, 화면에 출력하거나 데이터베이스에 저장하기 전에 반드시 유효성 검사(Validation) 및 보안 처리(Sanitization, Escaping)를 수행해야 합니다. 특히 화면 출력 시에는 <code>htmlspecialchars()</code> 함수를 사용하여 크로스 사이트 스크립팅(XSS) 공격을 방지하는 것이 필수적입니다.</p>
    <p>폼 처리 시 <code>isset()</code> 함수나 Null 병합 연산자(<code>??</code>)를 사용하여 해당 데이터가 실제로 전송되었는지 확인하는 것이 중요합니다.</p>
     <pre><code class="language-php">&lt;?php
     // isset() 사용
     $userId = null;
     if (isset($_GET['user_id'])) {
         $userId = (int)$_GET['user_id']; // 정수로 변환 시도
     }

     // Null 병합 연산자 (??) 사용 (PHP 7+) - 더 간결
     $page = $_GET['page'] ?? 1; // $_GET['page']가 없거나 null이면 1을 할당
     $page = (int)$page;

     echo "User ID: " . ($userId ?? '없음') . ", Page: " . $page;
     ?&gt;</code></pre>

    <h3 id="web-validation">폼 유효성 검사 및 데이터 정제</h3>
    <p>사용자가 폼을 통해 제출한 데이터는 항상 유효하지 않거나 악의적일 수 있으므로, 서버 측에서 반드시 검증(Validation)하고 정제(Sanitization)해야 합니다.</p>
    <ul>
        <li><strong>기본 검사:</strong>
            <ul>
                <li>필수 입력값 확인: <code>empty()</code>, <code>isset()</code></li>
                <li>길이 확인: <code>strlen()</code> (<code>mb_strlen()</code> 권장)</li>
            </ul>
        </li>
        <li><strong>데이터 타입 검사:</strong>
            <ul>
                <li>숫자인지 확인: <code>is_numeric()</code></li>
                <li>정수/실수 확인: <code>is_int()</code>, <code>is_float()</code></li>
                <li>이메일 형식 확인: <code>filter_var($email, FILTER_VALIDATE_EMAIL)</code></li>
                <li>URL 형식 확인: <code>filter_var($url, FILTER_VALIDATE_URL)</code></li>
            </ul>
        </li>
        <li><strong>데이터 정제 (Sanitization):</strong> 위험할 수 있는 문자 제거 또는 변경
            <ul>
                <li><code>htmlspecialchars($string)</code>: HTML 특수 문자를 엔티티로 변환 (XSS 방지용 출력 시 필수).</li>
                <li><code>strip_tags($string, $allowed_tags)</code>: HTML 및 PHP 태그 제거 (허용할 태그 지정 가능).</li>
                <li><code>filter_var($data, FILTER_SANITIZE_STRING)</code>: 태그 제거 (Deprecated in PHP 8.0).</li>
                <li><code>filter_var($email, FILTER_SANITIZE_EMAIL)</code>: 이메일 주소에서 허용되지 않는 문자 제거.</li>
                <li><code>filter_var($url, FILTER_SANITIZE_URL)</code>: URL에서 허용되지 않는 문자 제거.</li>
            </ul>
        </li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $errors = [];
     $name = '';
     $email = '';

     if ($_SERVER["REQUEST_METHOD"] == "POST") {
         // 이름 검사
         if (empty($_POST['name'])) {
             $errors[] = "이름은 필수 입력 항목입니다.";
         } else {
             // 이름 정제 (예: 앞뒤 공백 제거)
             $name = trim($_POST['name']);
             // 추가 검증 가능 (예: 길이 제한)
         }

         // 이메일 검사
         if (empty($_POST['email'])) {
             $errors[] = "이메일은 필수 입력 항목입니다.";
         } else {
             $email = $_POST['email'];
             if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                 $errors[] = "유효하지 않은 이메일 형식입니다.";
             } else {
                 // 이메일 정제 (필요시)
                 $email = filter_var($email, FILTER_SANITIZE_EMAIL);
             }
         }

         // 에러가 없으면 처리 진행
         if (empty($errors)) {
             echo "&lt;h4&gt;폼 제출 성공!&lt;/h4&gt;";
             echo "이름: " . htmlspecialchars($name) . "&lt;br&gt;";
             echo "이메일: " . htmlspecialchars($email) . "&lt;br&gt;";
             // 데이터베이스 저장 등 다음 단계 진행
         }
     }
     ?&gt;

     &lt;!-- 에러 메시지 출력 --&gt;
     &lt;?php if (!empty($errors)): ?&gt;
         &lt;div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 10px;"&gt;
             &lt;strong&gt;오류 발생:&lt;/strong&gt;&lt;br&gt;
             &lt;?php foreach ($errors as $error): ?&gt;
                 &lt;?php echo htmlspecialchars($error); ?&gt;&lt;br&gt;
             &lt;?php endforeach; ?&gt;
         &lt;/div&gt;
     &lt;?php endif; ?&gt;

     &lt;!-- 폼 (자기 자신에게 POST) --&gt;
     &lt;form method="post" action="&lt;?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?&gt;#web-validation"&gt;
         &lt;label for="name_val">이름:&lt;/label&gt;
         &lt;input type="text" id="name_val" name="name" value="&lt;?php echo htmlspecialchars($name); ?&gt;"&gt;&lt;br&gt;
         &lt;label for="email_val">이메일:&lt;/label&gt;
         &lt;input type="text" id="email_val" name="email" value="&lt;?php echo htmlspecialchars($email); ?&gt;"&gt;&lt;br&gt;
         &lt;button type="submit"&gt;제출&lt;/button&gt;
     &lt;/form&gt;
     </code></pre>
    <p><code>filter_var()</code> 함수는 다양한 유효성 검사 및 정제 필터를 제공하므로 적극 활용하는 것이 좋습니다.</p>

    <h3 id="web-urls">URL 파라미터 처리</h3>
    <p>GET 방식으로 전달되는 URL의 쿼리 스트링(<code>?key1=value1&key2=value2</code>)은 <code>$_GET</code>으로 접근합니다. 반대로 PHP 배열 데이터를 쿼리 스트링으로 만들 때는 <code>http_build_query()</code> 함수를 사용합니다.</p>
    <p><code>parse_url()</code> 함수는 URL 문자열을 파싱하여 scheme, host, path, query 등의 구성 요소를 연관 배열로 반환합니다.</p>
    <p>URL에 포함될 수 없는 문자(공백, 특수문자 등)는 <code>urlencode()</code> 함수로 인코딩하고, 인코딩된 문자열을 원래대로 되돌릴 때는 <code>urldecode()</code> 함수를 사용합니다.</p>
     <pre><code class="language-php">&lt;?php
     // 쿼리 스트링 생성
     $params = [
         'search' => 'PHP Tutorial',
         'page' => 2,
         'lang' => 'ko'
     ];
     $query_string = http_build_query($params);
     echo "생성된 쿼리 스트링: " . htmlspecialchars($query_string);
     // 출력 예: search=PHP+Tutorial&page=2&lang=ko
     echo "&lt;br&gt;";
     $link = "search.php?" . $query_string;
     echo "생성된 링크: &lt;a href='#'&gt;" . htmlspecialchars($link) . "&lt;/a&gt;&lt;br&gt;";

     // URL 파싱
     $url = "https://www.example.com/path/to/page.php?id=123&category=tech#section";
     $parsed_url = parse_url($url);
     print_r($parsed_url);
     // 출력 예: Array ( [scheme] => https [host] => www.example.com [path] => /path/to/page.php [query] => id=123&category=tech [fragment] => section )
     echo "&lt;br&gt;";
     if (isset($parsed_url['query'])) {
         parse_str($parsed_url['query'], $query_params); // 쿼리 스트링을 배열로 변환
         print_r($query_params); // Array ( [id] => 123 [category] => tech )
         echo "&lt;br&gt;";
     }

     // URL 인코딩/디코딩
     $korean_query = "검색어 예시";
     $encoded_query = urlencode($korean_query);
     echo "인코딩된 쿼리: " . htmlspecialchars($encoded_query); // 예: %EA%B2%80%EC%83%89%EC%96%B4+%EC%98%88%EC%8B%9C
     echo "&lt;br&gt;";
     $decoded_query = urldecode($encoded_query);
     echo "디코딩된 쿼리: " . htmlspecialchars($decoded_query); // 검색어 예시
     ?&gt;</code></pre>

    <h3 id="web-headers">헤더 조작 (header())</h3>
    <p><code>header()</code> 함수는 브라우저에게 보내는 원시 HTTP 헤더를 설정합니다. 이 함수는 매우 중요하며 다양한 용도로 사용됩니다.</p>
    <p class="warning"><strong>매우 중요:</strong> <code>header()</code> 함수는 어떠한 HTML 출력(echo, print, HTML 태그, 심지어 공백이나 줄바꿈)보다도 반드시 먼저 호출되어야 합니다. 그렇지 않으면 "Headers already sent" 에러가 발생합니다.</p>
    <p>주요 사용 사례:</p>
    <ul>
        <li><strong>페이지 리디렉션 (Redirect):</strong> 사용자를 다른 URL로 보냅니다. <code>Location:</code> 헤더를 사용하며, 보통 <code>exit;</code> 또는 <code>die();</code>와 함께 사용하여 리디렉션 후 스크립트 실행을 중단시킵니다.
             <pre><code class="language-php">header("Location: https://www.google.com");
exit; // 스크립트 실행 중단 필수</code></pre>
        </li>
        <li><strong>콘텐츠 타입(Content-Type) 지정:</strong> 브라우저에게 전송하는 콘텐츠의 종류(MIME 타입)를 알려줍니다. 기본값은 <code>text/html</code>입니다.
             <pre><code class="language-php">// JSON 데이터 응답 시
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['message' => '성공']);

// 이미지 파일 출력 시 (예: image.php)
// header('Content-Type: image/jpeg');
// readfile('path/to/image.jpg');

// 파일 다운로드 시
// header('Content-Type: application/octet-stream');
// header('Content-Disposition: attachment; filename="download.zip"');
// readfile('path/to/file.zip');
</code></pre>
        </li>
         <li><strong>HTTP 상태 코드 변경:</strong> 기본값 200 OK 외에 다른 상태 코드(예: 404 Not Found, 301 Moved Permanently)를 보낼 수 있습니다.
             <pre><code class="language-php">header("HTTP/1.1 404 Not Found");
// 또는 header("Status: 404 Not Found"); (구 방식 호환성)
echo "페이지를 찾을 수 없습니다.";</code></pre>
        </li>
        <li><strong>캐싱 제어:</strong> 브라우저 캐싱 동작을 제어하는 헤더(<code>Cache-Control</code>, <code>Expires</code>, <code>Pragma</code>)를 설정할 수 있습니다.</li>
    </ul>
     <h4>"Headers already sent" 에러 해결 방법:</h4>
     <ul>
         <li><code>header()</code> 함수 호출 전에 어떤 출력(HTML, 공백, <code>echo</code> 등)도 없는지 확인합니다. 특히 PHP 파일 시작(<code>&lt;?php</code>) 전이나 끝(<code>?&gt;</code>) 뒤의 공백/줄바꿈에 주의합니다.</li>
         <li>출력 버퍼링(Output Buffering)을 사용합니다. 스크립트 시작 부분에 <code>ob_start();</code>를 호출하면 PHP 출력이 즉시 브라우저로 전송되지 않고 내부 버퍼에 저장됩니다. <code>header()</code> 함수는 버퍼 내용과 관계없이 호출될 수 있으며, 스크립트 끝에서 <code>ob_end_flush();</code> 등으로 버퍼 내용을 전송합니다.
             <pre><code class="language-php">&lt;?php
             ob_start(); // 출력 버퍼링 시작

             echo "이 내용은 버퍼에 저장됩니다.&lt;br&gt;";

             // 출력 이후에도 header() 호출 가능
             header("X-Custom-Header: MyValue");

             // ... 다른 로직 ...

             echo "버퍼 내용 추가.&lt;br&gt;";

             // 헤더 전송 및 버퍼 내용 출력
             ob_end_flush();
             ?&gt;</code></pre>
         </li>
     </ul>

    <h3 id="web-include-require">파일 포함 (include, require)</h3>
    <p>다른 PHP 파일의 내용을 현재 스크립트에 포함시켜 코드를 재사용할 수 있게 합니다. 주로 웹사이트의 공통 부분(헤더, 푸터, 네비게이션, 함수 라이브러리 등)을 분리하여 관리할 때 사용됩니다.</p>
    <ul>
        <li><code>include 'filename.php';</code>: 지정된 파일을 포함시킵니다. 파일이 없거나 읽을 수 없으면 Warning 에러를 발생시키고 스크립트 실행을 계속합니다.</li>
        <li><code>require 'filename.php';</code>: 지정된 파일을 포함시킵니다. 파일이 없거나 읽을 수 없으면 Fatal Error를 발생시키고 스크립트 실행을 중단합니다. 필수적인 파일을 포함할 때 사용합니다.</li>
        <li><code>include_once 'filename.php';</code>: <code>include</code>와 동일하지만, 해당 파일이 이미 포함되었다면 다시 포함하지 않습니다.</li>
        <li><code>require_once 'filename.php';</code>: <code>require</code>와 동일하지만, 해당 파일이 이미 포함되었다면 다시 포함하지 않습니다. 함수나 클래스 정의 파일을 포함할 때 중복 정의 오류를 방지하기 위해 주로 사용됩니다.</li>
    </ul>
    <p>파일 경로는 상대 경로 또는 절대 경로를 사용할 수 있습니다. 경로 문제를 피하기 위해 매직 상수 <code>__DIR__</code> (현재 파일의 디렉토리 경로)을 사용하는 것이 좋습니다.</p>
     <div class="example">
        <h4>파일 포함 예제</h4>
        <p>가정: 현재 디렉토리에 `header.php`와 `footer.php` 파일이 있다고 가정합니다.</p>
        <pre><code class="language-php">// header.php 내용 예시:
&lt;!DOCTYPE html&gt;
&lt;html lang="ko"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;title&gt;&lt;?php echo $pageTitle ?? '기본 제목'; ?&gt;&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;header&gt;
        &lt;h1&gt;웹사이트 헤더&lt;/h1&gt;
        &lt;nav&gt;메뉴...&lt;/nav&gt;
    &lt;/header&gt;
    &lt;main&gt;

// footer.php 내용 예시:
    &lt;/main&gt;
    &lt;footer&gt;
        &lt;p&gt;&copy; &lt;?php echo date('Y'); ?&gt; 웹사이트 이름. 모든 권리 보유.&lt;/p&gt;
    &lt;/footer&gt;
&lt;/body&gt;
&lt;/html&gt;

// 메인 페이지 (예: index.php)
&lt;?php
$pageTitle = "메인 페이지"; // header.php에서 사용할 변수 정의
require_once __DIR__ . '/header.php'; // 현재 파일 기준 header.php 포함 (require_once 권장)

echo "&lt;h2&gt;메인 콘텐츠 영역&lt;/h2&gt;";
echo "&lt;p&gt;이 페이지의 내용은 동적으로 생성됩니다.&lt;/p&gt;";

require_once __DIR__ . '/footer.php'; // footer.php 포함
?&gt;
</code></pre>
     </div>
    <p>포함된 파일은 현재 파일의 변수 스코프를 공유합니다. 즉, 포함하는 파일에서 정의된 변수를 포함된 파일에서 사용할 수 있고, 그 반대도 가능합니다.</p>
</section>

<section id="php-state">
    <h2>상태 관리</h2>
    <p>HTTP는 기본적으로 상태가 없는(stateless) 프로토콜입니다. 즉, 각 요청은 이전 요청과 독립적입니다. 하지만 웹 애플리케이션에서는 사용자의 로그인 상태, 장바구니 내용 등 여러 요청에 걸쳐 상태를 유지해야 할 필요가 있습니다. PHP는 이를 위해 쿠키(Cookies)와 세션(Sessions) 메커니즘을 제공합니다.</p>

    <h3 id="state-cookies">쿠키 (Cookies)</h3>
    <p>쿠키는 클라이언트(웹 브라우저)에 저장되는 작은 텍스트 데이터 조각입니다. 서버는 <code>Set-Cookie</code> HTTP 헤더를 통해 브라우저에게 쿠키를 저장하도록 지시하고, 브라우저는 이후 동일한 서버(도메인)로 요청을 보낼 때마다 저장된 쿠키를 HTTP 요청 헤더(<code>Cookie:</code>)에 자동으로 포함하여 전송합니다.</p>
    <p>주요 특징:</p>
    <ul>
        <li>클라이언트 측에 저장됩니다.</li>
        <li>데이터 용량 제한이 있습니다 (보통 브라우저당, 도메인당 개수 및 크기 제한).</li>
        <li>사용자가 직접 보거나 수정, 삭제할 수 있으므로 보안에 민감한 정보(비밀번호 등)를 저장해서는 안 됩니다.</li>
        <li>주로 사용자 식별(로그인 유지 - 토큰 저장), 설정 저장(테마, 언어), 추적 등에 사용됩니다.</li>
    </ul>
    <h4>쿠키 설정: <code>setcookie()</code></h4>
    <p><code>setcookie(name, value, expire, path, domain, secure, httponly, [samesite])</code> 함수를 사용하여 쿠키를 설정합니다. 이 함수 역시 <code>header()</code>처럼 어떠한 출력보다 먼저 호출되어야 합니다.</p>
    <ul>
        <li><code>name</code>: 쿠키 이름.</li>
        <li><code>value</code>: 쿠키 값.</li>
        <li><code>expire</code> (선택 사항): 만료 시간 (유닉스 타임스탬프). 0 또는 과거 시간 지정 시 쿠키 삭제. 생략 시 브라우저 종료 시 삭제(세션 쿠키).</li>
        <li><code>path</code> (선택 사항): 쿠키가 유효한 서버 경로. <code>'/'</code> 지정 시 전체 사이트에서 유효.</li>
        <li><code>domain</code> (선택 사항): 쿠키가 유효한 도메인.</li>
        <li><code>secure</code> (선택 사항): <code>true</code> 설정 시 HTTPS 연결에서만 쿠키 전송.</li>
        <li><code>httponly</code> (선택 사항): <code>true</code> 설정 시 JavaScript에서 <code>document.cookie</code>로 쿠키에 접근하는 것을 막아 XSS 공격 방어에 도움. 권장 설정.</li>
        <li><code>samesite</code> (선택 사항, PHP 7.3+): CSRF 공격 방어를 위한 설정 ('None', 'Lax', 'Strict').</li>
    </ul>

    <h4>쿠키 읽기: <code>$_COOKIE</code></h4>
    <p>브라우저가 보낸 쿠키는 <code>$_COOKIE</code> 슈퍼글로벌 배열을 통해 접근할 수 있습니다. (주의: <code>setcookie()</code>로 설정한 쿠키는 다음 HTTP 요청부터 <code>$_COOKIE</code>에서 읽을 수 있습니다.)</p>

    <h4>쿠키 삭제:</h4>
    <p><code>setcookie()</code> 함수를 사용하되, 만료 시간을 과거로 설정합니다. (예: <code>time() - 3600</code>)</p>

     <pre><code class="language-php">&lt;?php
     // 쿠키 설정 (출력 전에 호출)
     $cookie_name = "username";
     $cookie_value = "AliceWonder";
     $expiry_time = time() + (86400 * 30); // 86400초 = 1일 -> 30일 후 만료
     // setcookie($cookie_name, $cookie_value, $expiry_time, "/", "", true, true); // path='/', secure=true, httponly=true

     // setcookie()는 보통 다른 PHP 로직이나 HTML 출력 전에 위치해야 합니다.
     // 이 예제에서는 설명을 위해 여기에 작성하지만, 실제로는 파일 상단에 위치해야 할 수 있습니다.
     // 이미 출력이 발생했다면 아래 코드는 Warning을 발생시키고 쿠키는 설정되지 않을 수 있습니다.
     if (!headers_sent()) { // 헤더가 이미 전송되었는지 확인
        setcookie($cookie_name, $cookie_value, $expiry_time, "/", "", false, true); // localhost 테스트 위해 secure=false
        // 쿠키 삭제 예시:
        // setcookie("old_cookie", "", time() - 3600, "/");
     } else {
         echo "&lt;p class='warning'&gt;경고: 헤더가 이미 전송되어 쿠키를 설정할 수 없습니다.&lt;/p&gt;";
     }
     ?&gt;
     &lt;!DOCTYPE html&gt;
     &lt;!-- ... (HTML 헤더) ... --&gt;
     &lt;body&gt;
     &lt;h3&gt;쿠키 예제&lt;/h3&gt;
     &lt;?php
     // 쿠키 읽기
     if (isset($_COOKIE[$cookie_name])) {
         echo "쿠키 '" . htmlspecialchars($cookie_name) . "'의 값: " . htmlspecialchars($_COOKIE[$cookie_name]);
     } else {
         echo "쿠키 '" . htmlspecialchars($cookie_name) . "'가 설정되지 않았거나 만료되었습니다.";
         echo "&lt;br&gt;페이지를 새로고침하면 설정된 쿠키를 읽을 수 있습니다.";
     }
     echo "&lt;br&gt;모든 쿠키 확인: &lt;pre&gt;";
     print_r($_COOKIE);
     echo "&lt;/pre&gt;";
     ?&gt;
     &lt;/body&gt;
     &lt;/html&gt;
     </code></pre>

    <h3 id="state-sessions">세션 (Sessions)</h3>
    <p>세션은 쿠키와 달리 서버 측에 사용자 데이터를 저장하는 방식입니다. 클라이언트(브라우저)에게는 고유한 세션 ID만 쿠키 형태로 저장하고, 서버는 이 ID를 통해 해당 사용자의 데이터를 찾아 상태를 유지합니다.</p>
    <p>주요 특징:</p>
    <ul>
        <li>데이터가 서버에 저장되므로 쿠키보다 보안성이 높습니다. (사용자가 직접 수정 불가)</li>
        <li>저장 용량 제한이 쿠키보다 훨씬 자유롭습니다.</li>
        <li>주로 로그인 상태 유지, 장바구니 정보 저장 등 중요한 상태 정보 관리에 사용됩니다.</li>
        <li>세션 ID는 기본적으로 세션 쿠키(브라우저 종료 시 삭제)로 관리되지만, 설정 변경 가능합니다.</li>
    </ul>
    <h4>세션 사용 단계:</h4>
    <ol>
        <li><strong>세션 시작/재개: <code>session_start()</code></strong><br>
           세션을 사용하려는 모든 페이지 상단(<strong>어떠한 출력보다 먼저</strong>)에서 <code>session_start()</code> 함수를 호출해야 합니다. 이 함수는 기존 세션이 있으면 재개하고, 없으면 새로운 세션을 시작하고 세션 ID를 생성하여 클라이언트에게 쿠키로 전달합니다.</li>
        <li><strong>세션 데이터 저장/읽기: <code>$_SESSION</code></strong><br>
           <code>session_start()</code> 이후에는 <code>$_SESSION</code> 슈퍼글로벌 배열을 통해 세션 데이터를 일반 배열처럼 읽고 쓸 수 있습니다.
           <pre><code class="language-php">$_SESSION['username'] = "Bob"; // 세션 변수 저장
$_SESSION['cart_items'] = ["item1", "item2"];
$currentUser = $_SESSION['username'] ?? 'Guest'; // 세션 변수 읽기</code></pre></li>
        <li><strong>세션 데이터 삭제:</strong>
            <ul>
                <li>특정 세션 변수 삭제: <code>unset($_SESSION['variable_name']);</code></li>
                <li>모든 세션 변수 삭제: <code>session_unset();</code></li>
            </ul>
        </li>
        <li><strong>세션 완전 종료: <code>session_destroy()</code></strong><br>
           서버에 저장된 모든 세션 데이터를 파괴하고 세션 ID를 무효화합니다. 보통 로그아웃 시 사용됩니다. 세션 쿠키 자체는 이 함수로 삭제되지 않으므로, 필요하면 별도로 삭제해야 합니다.
           <pre><code class="language-php">// 로그아웃 예시
session_start();
$_SESSION = array(); // 모든 세션 변수 비우기 (선택 사항)
if (ini_get("session.use_cookies")) { // 세션 쿠키 삭제
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy(); // 세션 완전 종료
header("Location: login.php"); // 로그인 페이지로 리디렉션
exit;
</code></pre></li>
    </ol>
    <p>세션 ID가 탈취되면 세션 하이재킹(Session Hijacking) 공격이 가능하므로, <code>session_regenerate_id(true);</code> 함수를 사용하여 로그인 성공 등 중요 시점에 세션 ID를 주기적으로 변경해주는 것이 보안상 좋습니다.</p>
     <div class="example">
        <h4>세션 예제 (간단한 방문 카운터)</h4>
        <pre><code class="language-php">&lt;?php
        // 모든 PHP 코드 및 HTML 출력 전에 호출해야 함
        if (session_status() == PHP_SESSION_NONE) { // 세션이 이미 시작되었는지 확인 (선택 사항)
             session_start();
        }

        // 방문 횟수 세션 변수 초기화 및 증가
        if (isset($_SESSION['visit_count'])) {
            $_SESSION['visit_count']++;
        } else {
            $_SESSION['visit_count'] = 1;
        }

        $visitCount = $_SESSION['visit_count'];
        ?&gt;
        &lt;!DOCTYPE html&gt;
        &lt;html&gt;
        &lt;head&gt;&lt;title&gt;세션 카운터&lt;/title&gt;&lt;/head&gt;
        &lt;body&gt;
            &lt;h3&gt;세션 방문 카운터&lt;/h3&gt;
            &lt;p&gt;이 페이지 방문 횟수: &lt;strong&gt;&lt;?php echo $visitCount; ?&gt;&lt;/strong&gt; 번&lt;/p&gt;
            &lt;p&gt;&lt;a href="&lt;?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?&gt;"&gt;페이지 새로고침&lt;/a&gt;&lt;/p&gt;
            &lt;!-- 세션 초기화 버튼 (예시) --&gt;
            &lt;form method="post"&gt;
              &lt;button type="submit" name="reset_session"&gt;카운터 초기화&lt;/button&gt;
            &lt;/form&gt;
            &lt;?php
            if (isset($_POST['reset_session'])) {
                session_unset(); // 모든 세션 변수 제거
                session_destroy(); // 세션 종료
                // 필요시 페이지 새로고침 또는 리디렉션
                echo "&lt;p&gt;카운터가 초기화되었습니다. 페이지를 새로고침하세요.&lt;/p&gt;";
            }
            ?&gt;
        &lt;/body&gt;
        &lt;/html&gt;
        </code></pre>
     </div>
</section>

<section id="php-files">
    <h2>파일 시스템</h2>
    <p>PHP는 서버의 파일 시스템에 접근하여 파일을 읽고 쓰고, 디렉토리를 다루는 다양한 함수를 제공합니다.</p>

    <h3 id="files-reading">파일 읽기</h3>
    <ul>
        <li><code>file_get_contents(filename)</code>: 파일 전체 내용을 문자열로 읽어옵니다. 간단하고 편리하지만 큰 파일에는 메모리 문제를 일으킬 수 있습니다.</li>
        <li><code>fopen(filename, mode)</code>: 파일을 특정 모드(mode)로 엽니다. 파일 핸들(resource)을 반환하며, 실패 시 <code>false</code>를 반환합니다.
            <ul><li>읽기 모드: <code>'r'</code> (텍스트 읽기), <code>'rb'</code> (바이너리 읽기).</li></ul>
        </li>
        <li><code>fread(handle, length)</code>: 열린 파일 핸들에서 지정된 길이(<code>length</code> 바이트)만큼 데이터를 읽습니다.</li>
        <li><code>fgets(handle, [length])</code>: 파일 핸들에서 한 줄을 읽습니다. (줄바꿈 문자 포함).</li>
        <li><code>feof(handle)</code>: 파일 포인터가 파일의 끝(End Of File)에 도달했는지 확인합니다.</li>
        <li><code>fclose(handle)</code>: 열린 파일 핸들을 닫습니다. 파일 작업 후에는 반드시 닫아야 합니다.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $filename = "my_file.txt"; // 예시 파일명

     // file_get_contents 사용 (간단한 읽기)
     if (file_exists($filename) && is_readable($filename)) {
         $content = file_get_contents($filename);
         echo "&lt;h4&gt;file_get_contents 결과:&lt;/h4&gt;&lt;pre&gt;" . htmlspecialchars($content) . "&lt;/pre&gt;";
     } else {
         echo "&lt;p&gt;{$filename} 파일을 읽을 수 없습니다. (file_get_contents)&lt;/p&gt;";
     }

     // fopen/fread/fclose 사용 (큰 파일 또는 라인 단위 처리)
     $file_handle = @fopen($filename, "r"); // @는 에러 출력 억제
     if ($file_handle) {
         echo "&lt;h4&gt;fopen/fgets 결과:&lt;/h4&gt;&lt;pre&gt;";
         while (!feof($file_handle)) { // 파일 끝까지 반복
             $line = fgets($file_handle); // 한 줄씩 읽기
             if ($line !== false) { // 읽기 성공 확인
                echo htmlspecialchars(trim($line)) . "\n"; // 앞뒤 공백 제거 후 출력
             }
         }
         echo "&lt;/pre&gt;";
         fclose($file_handle); // 파일 핸들 닫기
     } else {
          echo "&lt;p&gt;{$filename} 파일을 열 수 없습니다. (fopen)&lt;/p&gt;";
     }
     ?&gt;
     </code></pre>

    <h3 id="files-writing">파일 쓰기</h3>
     <ul>
        <li><code>file_put_contents(filename, data, [flags])</code>: 파일에 데이터를 씁니다. 파일이 없으면 생성합니다.
            <ul>
                <li>기본적으로 파일을 덮어씁니다.</li>
                <li><code>flags</code>에 <code>FILE_APPEND</code>를 사용하면 파일 끝에 데이터를 추가합니다.</li>
                <li><code>flags</code>에 <code>LOCK_EX</code>를 사용하면 쓰기 동안 파일 잠금을 시도합니다.</li>
            </ul>
        </li>
        <li><code>fopen(filename, mode)</code>: 쓰기 모드로 파일을 엽니다.
             <ul><li>쓰기 모드: <code>'w'</code> (쓰기 전용, 덮어씀, 없으면 생성), <code>'w+'</code> (읽기/쓰기), <code>'a'</code> (쓰기 전용, 이어쓰기, 없으면 생성), <code>'a+'</code> (읽기/쓰기), <code>'x'</code> (쓰기 전용, 파일 있으면 실패), <code>'x+'</code>, <code>'c'</code>, <code>'c+'</code>. 바이너리 모드는 <code>'b'</code> 추가 (예: <code>'wb'</code>).</li></ul>
        </li>
        <li><code>fwrite(handle, string, [length])</code>: 열린 파일 핸들에 문자열을 씁니다.</li>
        <li><code>fclose(handle)</code>: 파일을 닫아 변경 사항을 저장합니다.</li>
        <li><code>flock(handle, operation)</code>: 파일을 잠그거나 해제합니다 (동시 쓰기 방지 등). <code>LOCK_SH</code>(공유 잠금), <code>LOCK_EX</code>(배타적 잠금), <code>LOCK_UN</code>(잠금 해제).</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $log_filename = "app_log.txt";
     $log_message = date("[Y-m-d H:i:s]") . " 새로운 로그 메시지.\n";

     // file_put_contents 사용 (이어쓰기)
     $result = file_put_contents($log_filename, $log_message, FILE_APPEND | LOCK_EX);
     if ($result !== false) {
         echo "&lt;p&gt;로그가 성공적으로 기록되었습니다. (file_put_contents)&lt;/p&gt;";
     } else {
         echo "&lt;p&gt;로그 기록 실패!&lt;/p&gt;";
     }

     // fopen/fwrite/fclose 사용 (덮어쓰기)
     $data_filename = "data_output.txt";
     $data_to_write = "이것은 새로 쓸 데이터입니다.\n두 번째 줄입니다.";
     $file_handle_write = @fopen($data_filename, "w"); // 쓰기 모드 (기존 내용 삭제됨)
     if ($file_handle_write) {
         if (flock($file_handle_write, LOCK_EX)) { // 쓰기 잠금 시도
            $bytes_written = fwrite($file_handle_write, $data_to_write);
            flock($file_handle_write, LOCK_UN); // 잠금 해제
            fclose($file_handle_write); // 파일 닫기
            if ($bytes_written !== false) {
                 echo "&lt;p&gt;{$data_filename}에 데이터 쓰기 성공 ({$bytes_written} 바이트).&lt;/p&gt;";
            } else {
                 echo "&lt;p&gt;데이터 쓰기 실패!&lt;/p&gt;";
            }
         } else {
             echo "&lt;p&gt;파일 잠금 실패!&lt;/p&gt;";
             fclose($file_handle_write);
         }
     } else {
         echo "&lt;p&gt;{$data_filename} 파일을 열 수 없습니다. (fopen 'w')&lt;/p&gt;";
     }
     ?&gt;
     </code></pre>
     <p class="warning">파일 쓰기 작업은 서버의 파일 권한(permission) 설정에 영향을 받습니다. 웹 서버 프로세스(예: www-data, apache)가 해당 디렉토리에 쓰기 권한이 있어야 합니다.</p>

    <h3 id="files-info">파일 정보 확인</h3>
    <p>파일이나 디렉토리의 존재 여부 및 속성을 확인하는 함수들입니다.</p>
    <ul>
        <li><code>file_exists(filename)</code>: 파일 또는 디렉토리가 존재하는지 확인.</li>
        <li><code>is_file(filename)</code>: 일반 파일인지 확인.</li>
        <li><code>is_dir(filename)</code>: 디렉토리인지 확인.</li>
        <li><code>filesize(filename)</code>: 파일 크기를 바이트 단위로 반환.</li>
        <li><code>filemtime(filename)</code>: 파일의 최종 수정 시간을 유닉스 타임스탬프로 반환.</li>
        <li><code>is_readable(filename)</code>: 파일을 읽을 수 있는지 확인.</li>
        <li><code>is_writable(filename)</code>: 파일에 쓸 수 있는지 확인.</li>
        <li><code>pathinfo(path, [options])</code>: 파일 경로 정보를 연관 배열(디렉토리명, 기본이름, 확장자 등)로 반환.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $check_file = "my_file.txt"; // 위에서 읽기 테스트한 파일

     if (file_exists($check_file)) {
         echo "{$check_file} 파일 존재함.&lt;br&gt;";
         if (is_file($check_file)) echo "- 일반 파일입니다.&lt;br&gt;";
         if (is_readable($check_file)) echo "- 읽기 가능합니다.&lt;br&gt;";
         if (is_writable($check_file)) echo "- 쓰기 가능합니다.&lt;br&gt;";
         echo "- 파일 크기: " . filesize($check_file) . " 바이트&lt;br&gt;";
         echo "- 최종 수정 시간: " . date("Y-m-d H:i:s", filemtime($check_file)) . "&lt;br&gt;";
         print_r(pathinfo($check_file));
     } else {
         echo "{$check_file} 파일이 존재하지 않습니다.&lt;br&gt;";
     }

     $check_dir = "."; // 현재 디렉토리
     if (is_dir($check_dir)) {
         echo "{$check_dir}는 디렉토리입니다.&lt;br&gt;";
     }
     ?&gt;</code></pre>

    <h3 id="files-directories">디렉토리 다루기</h3>
    <ul>
        <li><code>mkdir(pathname, [mode], [recursive])</code>: 새 디렉토리를 생성합니다. <code>mode</code>는 권한(예: 0777, 0755), <code>recursive</code>가 true면 중간 경로도 함께 생성.</li>
        <li><code>rmdir(dirname)</code>: 비어 있는 디렉토리를 삭제합니다.</li>
        <li><code>scandir(directory)</code>: 디렉토리 내의 파일과 디렉토리 목록을 배열로 반환합니다 (<code>.</code> 와 <code>..</code> 포함).</li>
        <li><code>is_dir(filename)</code>: 디렉토리인지 확인.</li>
        <li><code>opendir(path)</code>, <code>readdir(dir_handle)</code>, <code>closedir(dir_handle)</code>: 디렉토리를 열고, 항목을 하나씩 읽고, 닫는 저수준 함수.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     $new_dir = "my_new_directory";

     // 디렉토리 생성 (이미 있으면 @로 에러 억제)
     if (!is_dir($new_dir)) {
        if (@mkdir($new_dir, 0755)) {
             echo "{$new_dir} 디렉토리 생성 성공.&lt;br&gt;";
         } else {
             echo "{$new_dir} 디렉토리 생성 실패!&lt;br&gt;";
         }
     } else {
         echo "{$new_dir} 디렉토리가 이미 존재합니다.&lt;br&gt;";
     }

     // 디렉토리 내용 읽기 (scandir)
     $current_dir_files = scandir("."); // 현재 디렉토리
     echo "&lt;h4&gt;현재 디렉토리 내용:&lt;/h4&gt;&lt;pre&gt;";
     print_r($current_dir_files);
     echo "&lt;/pre&gt;";

     // 디렉토리 삭제 (비어 있을 때만 가능)
     // if (is_dir($new_dir) && count(scandir($new_dir)) == 2) { // ., .. 만 있는지 확인
     //    if (@rmdir($new_dir)) {
     //        echo "{$new_dir} 디렉토리 삭제 성공.&lt;br&gt;";
     //    } else {
     //        echo "{$new_dir} 디렉토리 삭제 실패!&lt;br&gt;";
     //    }
     // }
     ?&gt;</code></pre>
</section>

<section id="php-uploads">
    <h2>파일 업로드 처리</h2>
    <p>PHP는 HTML 폼을 통해 클라이언트가 서버로 파일을 업로드하는 기능을 지원합니다.</p>
    <h4>1. HTML 폼 설정</h4>
    <p>파일 업로드를 위한 HTML 폼은 반드시 다음 두 가지 조건을 만족해야 합니다:</p>
    <ul>
        <li><code>method</code> 속성은 <strong>"post"</strong>여야 합니다.</li>
        <li><code>enctype</code> 속성은 <strong>"multipart/form-data"</strong>여야 합니다.</li>
        <li>파일 선택을 위한 <code>&lt;input type="file" name="..."&gt;</code> 요소가 있어야 합니다.</li>
    </ul>
    <pre><code class="language-html">&lt;form action="upload_handler.php" method="post" enctype="multipart/form-data"&gt;
  &lt;label for="fileToUpload"&gt;업로드할 파일 선택:&lt;/label&gt;
  &lt;input type="file" name="uploadedFile" id="fileToUpload"&gt;
  &lt;!-- 파일 크기 제한 (클라이언트 측, 서버 측 검증 필수) --&gt;
  &lt;input type="hidden" name="MAX_FILE_SIZE" value="5000000" /&gt; &lt;!-- 예: 5MB --&gt;
  &lt;button type="submit"&gt;파일 업로드&lt;/button&gt;
&lt;/form&gt;
</code></pre>

    <h4>2. PHP 스크립트 처리 (예: upload_handler.php)</h4>
    <p>업로드된 파일 정보는 <code>$_FILES</code> 슈퍼글로벌 배열을 통해 접근할 수 있습니다. <code>$_FILES</code>는 각 파일 입력 필드 이름(<code>name</code> 속성값)을 키로 가지는 다차원 배열입니다.</p>
    <p><code>$_FILES['input_name']</code> 배열의 주요 키:</p>
    <ul>
        <li><code>['name']</code>: 클라이언트 측의 원본 파일 이름.</li>
        <li><code>['type']</code>: 파일의 MIME 타입 (브라우저가 제공, 신뢰도 낮음).</li>
        <li><code>['size']</code>: 파일 크기 (바이트 단위).</li>
        <li><code>['tmp_name']</code>: 서버에 임시로 저장된 파일의 경로.</li>
        <li><code>['error']</code>: 업로드 에러 코드 (<code>UPLOAD_ERR_OK</code>(0)이면 성공).</li>
    </ul>

    <h4>처리 단계:</h4>
    <ol>
        <li>폼 제출 확인 (<code>$_SERVER['REQUEST_METHOD'] == 'POST'</code>).</li>
        <li>파일 입력 필드 존재 확인 (<code>isset($_FILES['input_name'])</code>).</li>
        <li>에러 확인: <code>$_FILES['input_name']['error']</code> 값을 확인하여 업로드 성공 여부 판단 (<code>UPLOAD_ERR_OK</code> 인지). 다양한 에러 코드에 따른 처리 필요.</li>
        <li>파일 검증 (매우 중요):
            <ul>
                <li>파일 크기 제한 확인 (<code>['size']</code>).</li>
                <li>파일 타입/확장자 확인 (<code>['name']</code>에서 확장자 추출, <code>['type']</code>은 참고만, 필요시 <code>mime_content_type()</code> 또는 <code>finfo</code> 사용). 허용된 타입/확장자만 처리.</li>
            </ul>
        </li>
        <li>임시 파일을 안전한 위치로 이동:
            <ul>
                <li><code>is_uploaded_file($_FILES['input_name']['tmp_name'])</code>: 해당 파일이 정말 HTTP POST 업로드를 통해 온 것인지 확인 (보안).</li>
                <li><code>move_uploaded_file(tmp_name, destination)</code>: 임시 파일을 원하는 저장 경로(<code>destination</code>)로 이동시키고 파일명을 설정합니다.</li>
                <li><code>destination</code> 경로는 웹 서버가 쓰기 권한을 가진 디렉토리여야 합니다.</li>
                <li>파일명 중복 방지 및 보안을 위해 파일명을 새로 생성하거나 안전하게 처리하는 것이 좋습니다 (예: 타임스탬프, 고유 ID 사용, 확장자 유지).</li>
            </ul>
        </li>
        <li>성공/실패 처리 및 피드백.</li>
    </ol>

    <pre><code class="language-php">&lt;?php
    // upload_handler.php 예시

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 1. 파일 입력 필드 확인
        if (isset($_FILES["uploadedFile"])) {
            $upload_file = $_FILES["uploadedFile"];

            // 2. 에러 코드 확인
            if ($upload_file["error"] === UPLOAD_ERR_OK) {
                // 업로드 성공
                $file_name = basename($upload_file["name"]); // 원본 파일명 (보안상 그대로 사용하지 않는 것이 좋음)
                $file_type = $upload_file["type"];
                $file_size = $upload_file["size"];
                $tmp_path = $upload_file["tmp_name"]; // 임시 파일 경로

                // 3. 파일 검증 (예시: 크기 및 확장자)
                $max_size = 5 * 1024 * 1024; // 5MB
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
                $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $errors = [];
                if ($file_size > $max_size) {
                    $errors[] = "파일 크기가 너무 큽니다. (최대 5MB)";
                }
                if (!in_array($file_extension, $allowed_extensions)) {
                    $errors[] = "허용되지 않는 파일 확장자입니다. (" . implode(", ", $allowed_extensions) . ")";
                }

                // 4. 에러 없으면 파일 이동
                if (empty($errors)) {
                    // 저장될 디렉토리 및 파일명 설정 (보안 및 중복 방지 고려)
                    $upload_dir = __DIR__ . "/uploads/"; // 현재 스크립트 기준 uploads 디렉토리
                    // 디렉토리 없으면 생성 시도
                    if (!is_dir($upload_dir)) {
                        @mkdir($upload_dir, 0755, true);
                    }
                    // 새 파일명 생성 (예: 고유 ID + 확장자)
                    $new_filename = uniqid("file_", true) . "." . $file_extension;
                    $destination = $upload_dir . $new_filename;

                    // is_uploaded_file()로 보안 검사 후 move_uploaded_file()로 이동
                    if (is_uploaded_file($tmp_path)) {
                        if (move_uploaded_file($tmp_path, $destination)) {
                            echo "&lt;p&gt;파일 업로드 성공! 저장된 파일: " . htmlspecialchars($new_filename) . "&lt;/p&gt;";
                            // 데이터베이스에 파일 정보 저장 등 후속 처리
                        } else {
                            echo "&lt;p class='warning'&gt;파일 이동 실패! 권한을 확인하세요.&lt;/p&gt;";
                        }
                    } else {
                        echo "&lt;p class='warning'&gt;유효하지 않은 업로드 파일입니다.&lt;/p&gt;";
                    }
                } else {
                    // 검증 에러 출력
                    echo "&lt;p class='warning'&gt;파일 검증 오류:&lt;br&gt;" . implode("&lt;br&gt;", $errors) . "&lt;/p&gt;";
                }

            } else {
                // 업로드 에러 처리
                $error_message = "파일 업로드 오류 발생: ";
                switch ($upload_file["error"]) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $error_message .= "파일 크기가 너무 큽니다.";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $error_message .= "파일이 부분적으로만 업로드되었습니다.";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $error_message .= "파일이 업로드되지 않았습니다.";
                        break;
                    default:
                        $error_message .= "알 수 없는 오류 (코드: " . $upload_file["error"] . ")";
                }
                 echo "&lt;p class='warning'&gt;" . $error_message . "&lt;/p&gt;";
            }
        } else {
            echo "&lt;p&gt;업로드된 파일 정보가 없습니다.&lt;/p&gt;";
        }
    } else {
        echo "&lt;p&gt;파일을 업로드하려면 POST 방식으로 제출해야 합니다.&lt;/p&gt;";
    }
    ?&gt;
     </code></pre>
     <p class="warning">파일 업로드 기능은 보안에 매우 신중해야 합니다. 업로드 디렉토리 권한 설정, 파일 확장자 및 MIME 타입 검증, 파일명 처리, 악성 코드 검사(가능하다면) 등을 철저히 수행해야 합니다. 웹 서버 설정(<code>php.ini</code>의 <code>upload_max_filesize</code>, <code>post_max_size</code> 등)도 업로드 용량 등에 영향을 미칩니다.</p>
</section>


<br><br>
<hr>

<section id="php-database">
    <h2>데이터베이스 연동 (MySQL)</h2>
    <p>PHP는 다양한 데이터베이스 시스템과 연동하여 데이터를 저장, 조회, 수정, 삭제하는 기능을 강력하게 지원합니다. 가장 널리 사용되는 조합 중 하나는 PHP와 MySQL(또는 MariaDB)입니다.</p>
    <p>PHP에서 MySQL 데이터베이스에 접근하는 주요 방법은 두 가지 확장(extension)이 있습니다:</p>
    <ul>
        <li><strong>MySQLi (MySQL Improved):</strong> MySQL 데이터베이스에 특화된 기능을 제공하며, 절차적(procedural) 방식과 객체 지향(object-oriented) 방식을 모두 지원합니다.</li>
        <li><strong>PDO (PHP Data Objects):</strong> 다양한 종류의 데이터베이스(MySQL, PostgreSQL, SQLite, MS SQL Server 등)에 일관된 인터페이스로 접근할 수 있게 해주는 추상화 계층입니다. 데이터베이스 종류 변경 시 코드 수정이 적다는 장점이 있습니다.</li>
    </ul>
    <p class="note">이 강좌에서는 주로 MySQLi (객체 지향 방식)와 PDO 사용법을 다룹니다. 보안을 위해 Prepared Statements 사용을 강력히 권장합니다.</p>
    <p class="warning">데이터베이스 관련 작업을 수행하려면 먼저 데이터베이스 서버(MySQL/MariaDB)가 설치 및 실행 중이어야 하고, 사용할 데이터베이스와 테이블, 그리고 접속할 사용자 계정이 미리 준비되어 있어야 합니다.</p>

    <h3 id="db-connect">연결 (MySQLi, PDO)</h3>
    <p>데이터베이스 작업을 시작하려면 먼저 PHP 스크립트에서 데이터베이스 서버에 연결해야 합니다. 연결 시에는 호스트 주소, 사용자 이름, 비밀번호, 데이터베이스 이름 등의 정보가 필요합니다.</p>

    <h4>MySQLi (Object-Oriented) 연결</h4>
    <pre><code class="language-php">&lt;?php
    // --- MySQLi 연결 정보 (실제 환경에서는 설정 파일 등으로 분리) ---
    $servername = "localhost"; // 또는 DB 서버 IP 주소
    $username = "db_user";     // DB 사용자 이름
    $password = "db_password"; // DB 사용자 비밀번호
    $dbname = "my_database";   // 사용할 데이터베이스 이름

    // --- MySQLi 객체 생성 및 연결 시도 ---
    // new mysqli(...)는 연결을 시도하고, 성공 시 mysqli 객체를, 실패 시 관련 에러 정보를 담은 객체를 반환.
    $mysqli = new mysqli($servername, $username, $password, $dbname);

    // --- 연결 에러 확인 ---
    // $mysqli->connect_error 속성은 연결 실패 시 에러 메시지를 포함 (성공 시 null).
    if ($mysqli->connect_error) {
        // 연결 실패 시 스크립트 실행 중단 및 에러 메시지 출력 (실제 서비스에서는 사용자에게 친화적인 메시지 표시)
        die("MySQLi 연결 실패: " . $mysqli->connect_error);
    } else {
        echo "&lt;p class='output'&gt;MySQLi 연결 성공!&lt;/p&gt;";
        // 문자 인코딩 설정 (UTF-8 권장)
        $mysqli->set_charset("utf8mb4");
    }

    // --- 연결 종료 (스크립트 끝에서 또는 필요시) ---
    // $mysqli->close(); // 뒤 예제에서 계속 사용하기 위해 주석 처리
    ?&gt;</code></pre>

    <h4>PDO (PHP Data Objects) 연결</h4>
    <p>PDO는 DSN(Data Source Name) 문자열을 사용하여 연결 정보를 지정합니다. DSN은 데이터베이스 종류에 따라 형식이 다릅니다.</p>
    <pre><code class="language-php">&lt;?php
    // --- PDO 연결 정보 ---
    $db_host = "localhost";
    $db_name = "my_database";
    $db_user = "db_user";
    $db_pass = "db_password";
    $charset = "utf8mb4";

    // DSN (Data Source Name) 설정 (MySQL 기준)
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";

    // PDO 옵션 설정 (선택 사항)
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 에러 발생 시 Exception throw
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // 기본 fetch 모드를 연관 배열로 설정
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Prepared Statement 에뮬레이션 비활성화 (보안 강화)
    ];

    // --- PDO 객체 생성 및 연결 시도 (try-catch 사용 권장) ---
    try {
        $pdo = new PDO($dsn, $db_user, $db_pass, $options);
        echo "&lt;p class='output'&gt;PDO 연결 성공!&lt;/p&gt;";
    } catch (\PDOException $e) { // PDO 연결 실패 시 PDOException 발생
        // 실제 서비스에서는 사용자 친화적 메시지 표시 및 로깅
        die("PDO 연결 실패: " . $e->getMessage());
    }

    // --- 연결 종료 (객체를 null로 설정하거나 스크립트 종료 시 자동 해제) ---
    // $pdo = null; // 뒤 예제에서 계속 사용하기 위해 주석 처리
    ?&gt;</code></pre>
    <p class="warning">데이터베이스 연결 정보(사용자 이름, 비밀번호 등)는 소스 코드에 직접 노출하지 않고, 별도의 설정 파일이나 환경 변수를 사용하여 안전하게 관리하는 것이 매우 중요합니다.</p>

    <h3 id="db-querying">쿼리 실행 (query)</h3>
    <p>데이터베이스에 연결한 후 SQL 쿼리를 실행하여 데이터를 조작할 수 있습니다. 간단한 쿼리는 <code>query()</code> 메서드를 사용할 수 있지만, 보안(SQL 인젝션) 문제 때문에 사용자 입력값이 포함된 쿼리에는 절대 사용해서는 안 됩니다.</p>
    <p class="warning">아래 예시는 설명을 위한 것이며, 실제로는 Prepared Statements를 사용해야 합니다.</p>
     <pre><code class="language-php">&lt;?php
     // --- !!!주의: 아래 코드는 SQL 인젝션에 취약합니다. 사용하지 마세요!!! ---
     /*
     // MySQLi (OOP) - query() 사용 (비권장)
     $sql = "SELECT id, name FROM users WHERE id = 1"; // 사용자 입력 없는 안전한 쿼리 예시
     $result = $mysqli->query($sql);

     if ($result) {
         echo "MySQLi 쿼리 성공. 반환된 행 수: " . $result->num_rows . "&lt;br&gt;";
         // 결과 처리... (fetch 사용)
         $result->free(); // 결과 세트 메모리 해제
     } else {
         echo "MySQLi 쿼리 오류: " . $mysqli->error . "&lt;br&gt;";
     }

     // PDO - query() 사용 (비권장)
     $sql_pdo = "SELECT email FROM users WHERE name = 'Alice'"; // 사용자 입력 없는 안전한 쿼리 예시
     try {
         $stmt = $pdo->query($sql_pdo);
         if ($stmt) {
             echo "PDO 쿼리 성공. 반환된 행 수: " . $stmt->rowCount() . "&lt;br&gt;"; // rowCount는 SELECT에서 항상 정확하지 않을 수 있음
             // 결과 처리... (fetch 사용)
         }
     } catch (\PDOException $e) {
          echo "PDO 쿼리 오류: " . $e->getMessage() . "&lt;br&gt;";
     }
     */
     // --- !!!위 코드는 예시일 뿐이며, 실제로는 아래 Prepared Statements를 사용해야 합니다!!! ---
     ?&gt;</code></pre>

    <h3 id="db-prepared">Prepared Statements (SQL 인젝션 방지)</h3>
    <p>Prepared Statements(준비된 구문)는 SQL 쿼리를 실행하는 가장 안전하고 권장되는 방법입니다. 쿼리 템플릿을 먼저 데이터베이스에 보내 컴파일하고, 나중에 실제 값(파라미터)을 바인딩하여 실행하는 방식입니다.</p>
    <p>장점:</p>
    <ul>
        <li><strong>SQL 인젝션 공격 방지:</strong> 쿼리 구조와 데이터가 분리되어 전달되므로, 악의적인 입력값이 쿼리 구조를 변경하는 것을 원천적으로 차단합니다.</li>
        <li><strong>성능 향상:</strong> 동일한 쿼리 템플릿이 여러 번 실행될 경우, 미리 컴파일된 쿼리를 재사용하여 성능이 향상될 수 있습니다.</li>
    </ul>

    <h4>MySQLi Prepared Statements (OOP)</h4>
    <p><code>prepare()</code> -> <code>bind_param()</code> -> <code>execute()</code> -> (결과 처리) -> <code>close()</code> 순서로 사용합니다.</p>
    <ul>
        <li><code>$mysqli->prepare(sql_template)</code>: SQL 템플릿(값이 들어갈 자리는 <code>?</code> 사용)을 준비하고 statement 객체 반환.</li>
        <li><code>$stmt->bind_param(types, $var1, ...)</code>: 변수를 파라미터에 바인딩. <code>types</code>는 각 변수의 타입을 나타내는 문자열 ('s': string, 'i': integer, 'd': double, 'b': blob).</li>
        <li><code>$stmt->execute()</code>: 준비된 쿼리를 실행.</li>
        <li><code>$stmt->get_result()</code>: SELECT 쿼리 실행 후 결과 세트(mysqli_result 객체)를 가져옴 (MySQL Native Driver(mysqlnd) 필요). 또는 <code>$stmt->bind_result(...)</code> 와 <code>$stmt->fetch()</code> 사용.</li>
        <li><code>$stmt->close()</code>: statement 객체 닫기.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     // --- MySQLi Prepared Statement 예시 (SELECT) ---
     $userId = 1; // 사용자 입력값이라고 가정
     $sql_template = "SELECT name, email FROM users WHERE id = ?"; // 값 대신 ? 사용

     $stmt = $mysqli->prepare($sql_template);
     if ($stmt) {
         // bind_param('타입', 변수1, 변수2...) - 타입 'i'는 정수(integer)
         $stmt->bind_param("i", $userId); // 변수 $userId를 첫 번째 ? 에 정수 타입으로 바인딩
         $stmt->execute(); // 쿼리 실행

         // 결과 가져오기 (get_result 사용 - mysqlnd 필요)
         $result = $stmt->get_result();
         if ($result->num_rows > 0) {
             echo "&lt;h4&gt;MySQLi Prepared SELECT 결과:&lt;/h4&gt;";
             while ($row = $result->fetch_assoc()) { // 연관 배열로 결과 가져오기
                 echo "이름: " . htmlspecialchars($row['name']) . ", 이메일: " . htmlspecialchars($row['email']) . "&lt;br&gt;";
             }
             $result->free();
         } else {
             echo "&lt;p&gt;사용자 ID {$userId} 에 해당하는 결과 없음.&lt;/p&gt;";
         }
         $stmt->close(); // statement 닫기
     } else {
          echo "MySQLi prepare 오류: " . $mysqli->error . "&lt;br&gt;";
     }
     ?&gt;</code></pre>

    <h4>PDO Prepared Statements</h4>
    <p><code>prepare()</code> -> (<code>bindParam()</code> / <code>bindValue()</code> 또는 <code>execute()</code>에 배열 전달) -> <code>execute()</code> -> (결과 처리) 순서로 사용합니다.</p>
    <ul>
        <li><code>$pdo->prepare(sql_template)</code>: SQL 템플릿(값이 들어갈 자리는 <code>?</code> 또는 이름 있는 플레이스홀더 <code>:name</code> 사용)을 준비하고 PDOStatement 객체 반환.</li>
        <li><code>$stmt->bindParam(parameter, $variable, [data_type])</code>: 변수를 파라미터에 참조로 바인딩. <code>execute()</code> 시점의 변수 값이 사용됨.</li>
        <li><code>$stmt->bindValue(parameter, value, [data_type])</code>: 값을 파라미터에 바인딩. <code>bindValue()</code> 호출 시점의 값이 사용됨.</li>
        <li><code>$stmt->execute([input_parameters])</code>: 준비된 쿼리를 실행. 파라미터 바인딩을 위한 배열을 인수로 전달할 수 있음 (<code>?</code> 플레이스홀더 또는 이름 있는 플레이스홀더 <code>:name => value</code>).</li>
        <li>결과 처리는 PDOStatement 객체의 <code>fetch()</code>, <code>fetchAll()</code> 등 사용.</li>
    </ul>
    <pre><code class="language-php">&lt;?php
    // --- PDO Prepared Statement 예시 (SELECT - 이름 있는 플레이스홀더) ---
    $userCity = "Seoul"; // 사용자 입력값이라고 가정
    $minAge = 25;

    $sql_template_pdo = "SELECT name, age FROM users WHERE city = :city AND age > :min_age";
    try {
        $stmt = $pdo->prepare($sql_template_pdo);

        // 방법 1: bindParam/bindValue 사용
        // $stmt->bindParam(':city', $userCity, PDO::PARAM_STR); // 변수를 참조로 바인딩
        // $stmt->bindValue(':min_age', $minAge, PDO::PARAM_INT); // 값을 직접 바인딩
        // $stmt->execute();

        // 방법 2: execute()에 배열 전달 (더 간편)
        $stmt->execute([':city' => $userCity, ':min_age' => $minAge]);
        // 또는 $stmt->execute(['city' => $userCity, 'min_age' => $minAge]); // 콜론(:) 생략 가능

        $users = $stmt->fetchAll(); // 모든 결과를 연관 배열의 배열로 가져오기 (기본 fetch mode가 FETCH_ASSOC 가정)

        if ($users) {
            echo "&lt;h4&gt;PDO Prepared SELECT 결과 (도시: {$userCity}, 나이 > {$minAge}):&lt;/h4&gt;";
            echo "&lt;ul&gt;";
            foreach ($users as $user) {
                echo "&lt;li&gt;이름: " . htmlspecialchars($user['name']) . ", 나이: " . htmlspecialchars($user['age']) . "&lt;/li&gt;";
            }
            echo "&lt;/ul&gt;";
        } else {
             echo "&lt;p&gt;조건에 맞는 사용자 없음.&lt;/p&gt;";
        }

    } catch (\PDOException $e) {
        echo "PDO prepare/execute 오류: " . $e->getMessage() . "&lt;br&gt;";
    }
    ?&gt;</code></pre>

    <h3 id="db-fetching">데이터 가져오기 (fetch)</h3>
    <p>SELECT 쿼리 실행 후 결과를 가져오는 방법입니다.</p>
    <ul>
        <li><strong>MySQLi (<code>$result = $stmt->get_result()</code> 사용 시):</strong> <code>mysqli_result</code> 객체
            <ul>
                <li><code>$result->fetch_assoc()</code>: 결과를 연관 배열(컬럼명 => 값)로 한 행씩 가져옴.</li>
                <li><code>$result->fetch_row()</code>: 결과를 인덱스 배열로 한 행씩 가져옴.</li>
                <li><code>$result->fetch_object()</code>: 결과를 객체로 한 행씩 가져옴.</li>
                <li><code>$result->fetch_all(MYSQLI_ASSOC)</code>: 모든 결과를 지정된 형식의 배열로 가져옴 (메모리 주의).</li>
                <li><code>$result->num_rows</code>: 결과 행의 수.</li>
                <li><code>$result->free()</code>: 결과 세트 메모리 해제.</li>
            </ul>
        </li>
         <li><strong>MySQLi (<code>$stmt->bind_result()</code> 사용 시):</strong>
            <ul>
                <li><code>$stmt->bind_result($var1, $var2, ...)</code>: 결과를 받을 변수를 순서대로 바인딩.</li>
                <li><code>$stmt->fetch()</code>: 바인딩된 변수로 다음 행의 데이터를 가져옴 (while 루프 사용).</li>
                <li><code>$stmt->store_result()</code>: 전체 결과를 버퍼에 저장 (<code>$stmt->num_rows</code> 사용 가능).</li>
                <li><code>$stmt->num_rows</code>: 결과 행의 수 (<code>store_result()</code> 호출 후).</li>
                <li><code>$stmt->free_result()</code>: 결과 버퍼 해제.</li>
            </ul>
        </li>
        <li><strong>PDO (<code>$stmt = $pdo->prepare(...); $stmt->execute(...);</code> 사용 시):</strong> <code>PDOStatement</code> 객체
            <ul>
                <li><code>$stmt->fetch(fetch_style)</code>: 다음 한 행을 지정된 형식(<code>fetch_style</code>)으로 가져옴. 기본값은 <code>PDO::ATTR_DEFAULT_FETCH_MODE</code> 설정 따름 (예: <code>PDO::FETCH_ASSOC</code>, <code>PDO::FETCH_NUM</code>, <code>PDO::FETCH_OBJ</code>, <code>PDO::FETCH_BOTH</code>).</li>
                <li><code>$stmt->fetchAll(fetch_style)</code>: 모든 결과를 지정된 형식의 배열로 가져옴 (메모리 주의).</li>
                <li><code>$stmt->fetchColumn([column_number])</code>: 다음 행의 특정 컬럼 값 하나만 가져옴.</li>
                <li><code>$stmt->rowCount()</code>: INSERT, UPDATE, DELETE 쿼리로 영향을 받은 행의 수를 반환. SELECT에서는 항상 정확하지 않을 수 있음.</li>
            </ul>
        </li>
    </ul>

    <h3 id="db-insert-update-delete">데이터 삽입, 수정, 삭제</h3>
    <p>INSERT, UPDATE, DELETE 쿼리도 반드시 Prepared Statements를 사용해야 합니다.</p>
     <pre><code class="language-php">&lt;?php
     // --- MySQLi 예시 (INSERT) ---
     $new_name = "Charlie";
     $new_email = "charlie@example.com";
     $new_city = "Busan";
     $new_age = 35;

     $sql_insert = "INSERT INTO users (name, email, city, age) VALUES (?, ?, ?, ?)";
     $stmt_insert = $mysqli->prepare($sql_insert);
     if ($stmt_insert) {
         // 타입: s=string, i=integer
         $stmt_insert->bind_param("sssi", $new_name, $new_email, $new_city, $new_age);
         if ($stmt_insert->execute()) {
             $last_id = $mysqli->insert_id; // 마지막으로 삽입된 행의 auto_increment ID 가져오기
             echo "&lt;p class='output'&gt;MySQLi: 새 사용자 삽입 성공! ID: {$last_id}&lt;/p&gt;";
         } else {
             echo "&lt;p class='warning'&gt;MySQLi INSERT 실행 오류: " . $stmt_insert->error . "&lt;/p&gt;";
         }
         $stmt_insert->close();
     } else {
         echo "MySQLi prepare 오류: " . $mysqli->error . "&lt;br&gt;";
     }

     // --- PDO 예시 (UPDATE) ---
     $update_id = $last_id ?? 1; // 방금 삽입한 ID 또는 예시 ID
     $updated_age = 36;

     $sql_update = "UPDATE users SET age = :age WHERE id = :id";
     try {
         $stmt_update = $pdo->prepare($sql_update);
         $stmt_update->bindParam(':age', $updated_age, PDO::PARAM_INT);
         $stmt_update->bindParam(':id', $update_id, PDO::PARAM_INT);
         if ($stmt_update->execute()) {
             $affected_rows = $stmt_update->rowCount(); // 영향을 받은 행 수
             echo "&lt;p class='output'&gt;PDO: 사용자 정보 수정 성공! 영향을 받은 행: {$affected_rows}&lt;/p&gt;";
         } else {
              echo "&lt;p class='warning'&gt;PDO UPDATE 실행 실패!&lt;/p&gt;";
         }
     } catch (\PDOException $e) {
         echo "PDO UPDATE 오류: " . $e->getMessage() . "&lt;br&gt;";
     }

     // --- PDO 예시 (DELETE) ---
     /*
     $delete_id = $update_id;
     $sql_delete = "DELETE FROM users WHERE id = :id";
     try {
         $stmt_delete = $pdo->prepare($sql_delete);
         $stmt_delete->execute([':id' => $delete_id]); // execute 배열로 파라미터 전달
         $affected_rows = $stmt_delete->rowCount();
         echo "&lt;p class='output'&gt;PDO: 사용자 삭제 성공! 영향을 받은 행: {$affected_rows}&lt;/p&gt;";
     } catch (\PDOException $e) {
          echo "PDO DELETE 오류: " . $e->getMessage() . "&lt;br&gt;";
     }
     */
     ?&gt;</code></pre>

    <h3 id="db-closing">연결 종료</h3>
    <p>데이터베이스 작업이 끝나면 연결을 종료하는 것이 좋습니다. PHP 스크립트 실행이 끝나면 연결은 자동으로 닫히지만, 명시적으로 닫아주는 것이 좋은 습관입니다.</p>
    <ul>
        <li>MySQLi: <code>$mysqli->close();</code></li>
        <li>PDO: <code>$pdo = null;</code> (객체 참조를 제거하여 연결 해제 유도)</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     // 스크립트 끝에서 연결 종료
     if (isset($mysqli) && $mysqli instanceof mysqli) { $mysqli->close(); }
     if (isset($pdo)) { $pdo = null; }
     echo "&lt;p&gt;데이터베이스 연결이 종료되었습니다.&lt;/p&gt;";
     ?&gt;</code></pre>

    <h3 id="db-pdo-vs-mysqli">PDO vs MySQLi</h3>
    <ul>
        <li><strong>이식성:</strong> PDO는 다양한 데이터베이스를 지원하므로 DB 변경 시 코드 수정이 적습니다. MySQLi는 MySQL/MariaDB 전용입니다.</li>
        <li><strong>API 스타일:</strong> PDO는 객체 지향 API만 제공합니다. MySQLi는 객체 지향 및 절차적 API를 모두 제공합니다.</li>
        <li><strong>플레이스홀더:</strong> PDO는 이름 있는 플레이스홀더(<code>:name</code>)와 물음표(<code>?</code>)를 모두 지원합니다. MySQLi는 물음표(<code>?</code>)만 지원합니다. 이름 있는 플레이스홀더가 가독성이 더 좋습니다.</li>
        <li><strong>기능:</strong> MySQLi는 MySQL의 최신 기능을 더 빠르게 지원할 수 있습니다.</li>
    </ul>
    <p>일반적으로 새로운 프로젝트에서는 PDO를 사용하는 것이 이식성과 유연성 측면에서 권장됩니다.</p>
</section>


<section id="php-oop">
    <h2>객체 지향 프로그래밍 (OOP) 기초</h2>
    <p>객체 지향 프로그래밍(Object-Oriented Programming)은 실제 세계의 사물이나 개념을 '객체(Object)' 단위로 모델링하고, 객체들 간의 상호작용을 통해 프로그램을 구성하는 방식입니다. 코드의 재사용성, 유지보수성, 확장성을 높이는 데 도움을 줍니다.</p>
    <p>PHP는 버전 5부터 완전한 객체 지향 기능을 지원하기 시작했습니다.</p>

    <h3 id="oop-classes-objects">클래스와 객체</h3>
    <ul>
        <li><strong>클래스 (Class):</strong> 객체를 만들기 위한 '틀' 또는 '설계도'입니다. 객체가 가질 속성(데이터)과 메서드(기능)를 정의합니다. <code>class</code> 키워드로 정의합니다.</li>
        <li><strong>객체 (Object):</strong> 클래스를 바탕으로 실제로 메모리에 생성된 실체(인스턴스, instance)입니다. <code>new</code> 키워드를 사용하여 클래스로부터 객체를 생성합니다.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     // 클래스 정의
     class Car {
         // 속성 (멤버 변수)
         public $color = "red"; // public: 어디서든 접근 가능
         public $brand = "Unknown";

         // 메서드 (멤버 함수)
         public function startEngine() {
             echo $this->brand . " 시동을 겁니다... 부릉!&lt;br&gt;"; // $this는 현재 객체를 가리킴
         }

         public function setColor($newColor) {
             $this->color = $newColor;
         }
     }

     // 객체 생성 (인스턴스화)
     $myCar = new Car(); // Car 클래스의 인스턴스(객체) 생성
     $yourCar = new Car();

     // 객체의 속성 접근 및 수정 (-> 연산자 사용)
     $myCar->brand = "Hyundai";
     $yourCar->brand = "Kia";
     $yourCar->setColor("blue"); // 메서드를 통해 속성 변경

     // 객체의 메서드 호출
     $myCar->startEngine(); // 출력: Hyundai 시동을 겁니다... 부릉!
     $yourCar->startEngine(); // 출력: Kia 시동을 겁니다... 부릉!

     echo "내 차 색상: " . $myCar->color . "&lt;br&gt;"; // red
     echo "네 차 색상: " . $yourCar->color . "&lt;br&gt;"; // blue
     ?&gt;</code></pre>

    <h3 id="oop-properties-methods">속성(Properties)과 메서드(Methods)</h3>
    <ul>
        <li><strong>속성 (Property):</strong> 클래스 내에 선언된 변수로, 객체의 상태(데이터)를 나타냅니다. 멤버 변수라고도 합니다.</li>
        <li><strong>메서드 (Method):</strong> 클래스 내에 정의된 함수로, 객체의 동작(기능)을 나타냅니다. 멤버 함수라고도 합니다.</li>
    </ul>

    <h3 id="oop-visibility">접근 제어 (Visibility)</h3>
    <p>속성과 메서드 앞에 접근 제어 키워드를 사용하여 외부에서의 접근 수준을 제어할 수 있습니다. 이를 캡슐화(Encapsulation)의 일부로 봅니다.</p>
    <ul>
        <li><code>public</code>: 어디서든(클래스 내부, 외부, 상속받은 클래스) 접근 가능합니다. (기본값)</li>
        <li><code>protected</code>: 클래스 내부와 상속받은 자식 클래스 내부에서만 접근 가능합니다. 클래스 외부에서는 접근 불가.</li>
        <li><code>private</code>: 해당 속성/메서드가 정의된 클래스 내부에서만 접근 가능합니다. 상속받은 클래스에서도 접근 불가.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     class BankAccount {
         public $accountNumber; // 계좌번호 (외부 공개)
         private $balance = 0; // 잔액 (외부에서 직접 접근 불가)
         protected $accountHolder; // 예금주 (자식 클래스에서 접근 가능)

         public function __construct($number, $holder) { // 생성자
             $this->accountNumber = $number;
             $this->accountHolder = $holder;
         }

         public function deposit($amount) { // 입금 (public 메서드)
             if ($amount > 0) {
                 $this->balance += $amount;
                 $this->logTransaction("입금", $amount); // private 메서드 호출
                 return true;
             }
             return false;
         }

         public function withdraw($amount) { // 출금 (public 메서드)
              if ($amount > 0 && $this->balance >= $amount) {
                 $this->balance -= $amount;
                 $this->logTransaction("출금", $amount);
                 return true;
             }
             return false;
         }

         public function getBalance() { // 잔액 조회 (public 메서드)
             return $this->balance; // 내부에서 private 속성 접근 가능
         }

         private function logTransaction($type, $amount) { // 내부용 로그 기록 (private 메서드)
             echo "거래 기록: " . $type . " " . $amount . "원 (계좌: " . $this->accountNumber . ")&lt;br&gt;";
         }
     }

     $myAccount = new BankAccount("111-222", "홍길동");
     echo "계좌번호: " . $myAccount->accountNumber . "&lt;br&gt;"; // public 속성 접근 가능
     // echo $myAccount->balance; // 오류: Fatal error: Cannot access private property BankAccount::$balance
     // $myAccount->logTransaction("테스트", 100); // 오류: Fatal error: Uncaught Error: Call to private method

     $myAccount->deposit(50000); // 입금 메서드 호출
     $myAccount->withdraw(20000); // 출금 메서드 호출
     echo "현재 잔액: " . $myAccount->getBalance() . "원&lt;br&gt;"; // public 메서드를 통해 잔액 확인
     ?&gt;</code></pre>

    <h3 id="oop-constructor-destructor">생성자 및 소멸자</h3>
    <ul>
        <li><strong>생성자 (Constructor):</strong> <code>__construct()</code> 메서드는 객체가 생성될 때(<code>new</code> 사용 시) 자동으로 호출됩니다. 주로 객체의 초기화 작업을 수행합니다.</li>
        <li><strong>소멸자 (Destructor):</strong> <code>__destruct()</code> 메서드는 객체가 메모리에서 해제되기 직전에 자동으로 호출됩니다. 주로 객체가 사용하던 자원(파일 핸들, DB 연결 등)을 해제하는 작업을 수행합니다.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     class DatabaseConnection {
         private $connection;
         private $host;

         public function __construct($host, $user, $pass, $db) { // 생성자: 연결 정보 받아 DB 연결
             echo "DB 연결 시도...&lt;br&gt;";
             $this->host = $host;
             // 실제 DB 연결 로직 (예시로 mysqli 사용)
             $this->connection = new mysqli($host, $user, $pass, $db);
             if ($this->connection->connect_error) {
                 throw new Exception("DB 연결 실패: " . $this->connection->connect_error);
             }
             echo "DB 연결 성공! (" . $this->host . ")&lt;br&gt;";
         }

         public function query($sql) {
             echo "쿼리 실행: " . htmlspecialchars($sql) . "&lt;br&gt;";
             // 실제 쿼리 실행 로직...
             return true; // 예시로 true 반환
         }

         public function __destruct() { // 소멸자: 객체 소멸 시 DB 연결 해제
             if ($this->connection) {
                 $this->connection->close();
                 echo "DB 연결 해제됨. (" . $this->host . ")&lt;br&gt;";
             }
         }
     }

     try {
         $dbConn1 = new DatabaseConnection("localhost", "user1", "pass1", "db1"); // 객체 생성 시 __construct 호출
         $dbConn1->query("SELECT * FROM users");
         // $dbConn1 객체 사용...

         // 스크립트 종료 또는 $dbConn1 참조가 없어지면 __destruct 자동 호출됨
         // unset($dbConn1); // 명시적으로 객체 참조 제거 시 소멸자 호출

     } catch (Exception $e) {
         echo "오류 발생: " . $e->getMessage() . "&lt;br&gt;";
     }

     echo "스크립트 종료 지점.&lt;br&gt;";
     ?&gt;</code></pre>
     <p>PHP 8.0부터는 생성자 속성 승격(Constructor Property Promotion) 기능이 도입되어 생성자에서 속성 선언과 초기화를 동시에 간결하게 할 수 있습니다:</p>
     <pre><code class="language-php">class Point {
    // PHP 8.0+ 생성자 속성 승격
    public function __construct(public float $x = 0.0, public float $y = 0.0) {
        // 속성 선언과 할당이 자동으로 이루어짐
    }
}
$p = new Point(1.5, 2.5);
echo $p->x; // 1.5</code></pre>

    <h3 id="oop-this">$this</h3>
    <p>클래스의 메서드 내부에서 <code>$this</code> 키워드는 현재 객체(인스턴스) 자신을 가리킵니다. 이를 통해 객체의 다른 속성이나 메서드에 접근할 수 있습니다.</p>
    <p class="warning">Static 메서드 내부에서는 <code>$this</code>를 사용할 수 없습니다 (객체 인스턴스가 없으므로).</p>

    <h3 id="oop-inheritance">상속 (Inheritance)</h3>
    <p>상속은 기존 클래스(부모 클래스, 슈퍼 클래스)의 속성과 메서드를 물려받아 새로운 클래스(자식 클래스, 서브 클래스)를 정의하는 기능입니다. 코드 재사용성을 높이고 클래스 간의 계층 구조를 형성합니다.</p>
    <p><code>extends</code> 키워드를 사용하여 상속 관계를 정의합니다.</p>
    <p>자식 클래스는 부모 클래스의 <code>public</code> 및 <code>protected</code> 멤버(속성, 메서드)를 사용할 수 있습니다. <code>private</code> 멤버는 상속되지 않습니다.</p>
    <p>자식 클래스는 부모 클래스의 메서드를 재정의(Override)할 수 있습니다. 재정의된 자식 메서드 내에서 부모 메서드를 호출해야 할 경우 <code>parent::methodName()</code> 구문을 사용합니다.</p>
     <pre><code class="language-php">&lt;?php
     // 부모 클래스
     class Animal {
         public $name;
         protected $sound;

         public function __construct($name, $sound) {
             $this->name = $name;
             $this->sound = $sound;
         }

         public function speak() {
             echo $this->name . "(이)가 " . $this->sound . " 소리를 냅니다.&lt;br&gt;";
         }
     }

     // 자식 클래스 (Animal 상속)
     class Dog extends Animal {
         public $breed;

         public function __construct($name, $breed = "믹스견") {
             // 부모 클래스의 생성자 호출하여 name과 기본 sound 설정
             parent::__construct($name, "멍멍");
             $this->breed = $breed;
         }

         // 부모 클래스의 speak 메서드 재정의 (Overriding)
         public function speak() {
             echo $this->name . " (" . $this->breed . ")가 " . $this->sound . " 짖습니다!&lt;br&gt;";
         }

         public function fetch() {
             echo $this->name . "(이)가 공을 가져옵니다.&lt;br&gt;";
         }
     }

     $dog1 = new Dog("바둑이");
     $dog2 = new Dog("해피", "골든 리트리버");

     $dog1->speak(); // 재정의된 speak() 메서드 호출
     $dog2->speak();
     $dog2->fetch(); // 자식 클래스에 추가된 메서드 호출

     echo $dog1->name . "의 품종: " . $dog1->breed . "&lt;br&gt;";
     // echo $dog1->sound; // 오류: Fatal error: Cannot access protected property Dog::$sound (외부 접근 불가)
     ?&gt;</code></pre>
     <p><code>final</code> 키워드를 클래스나 메서드 앞에 붙이면 각각 상속이나 오버라이딩을 금지할 수 있습니다.</p>

    <h3 id="oop-static">Static 키워드</h3>
    <p><code>static</code> 키워드를 사용하여 정의된 속성이나 메서드는 특정 객체(인스턴스)에 속하는 것이 아니라 클래스 자체에 속합니다.</p>
    <ul>
        <li><strong>정적 속성 (Static Property):</strong> 클래스의 모든 인스턴스가 공유하는 변수입니다. <code>ClassName::$propertyName</code>으로 접근합니다.</li>
        <li><strong>정적 메서드 (Static Method):</strong> 객체를 생성하지 않고 클래스 이름을 통해 직접 호출할 수 있는 메서드입니다. <code>ClassName::methodName()</code>으로 호출합니다. 정적 메서드 내부에서는 <code>$this</code>를 사용할 수 없으며, 다른 정적 멤버에 접근할 때는 <code>self::$propertyName</code> 또는 <code>self::methodName()</code>을 사용합니다.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     class Counter {
         public static $count = 0; // 정적 속성 (모든 Counter 객체가 공유)

         public function __construct() {
             self::$count++; // 객체 생성 시 정적 속성 증가 (self:: 사용)
         }

         public static function getCount() { // 정적 메서드
             return self::$count;
         }
     }

     echo "초기 카운트: " . Counter::getCount() . "&lt;br&gt;"; // 객체 없이 정적 메서드 호출 -> 0

     $c1 = new Counter();
     $c2 = new Counter();

     echo "객체 생성 후 카운트: " . Counter::$count . "&lt;br&gt;"; // 클래스 이름으로 정적 속성 접근 -> 2
     echo "메서드로 확인한 카운트: " . Counter::getCount() . "&lt;br&gt;"; // 2
     // echo $c1->count; // PHP 버전/설정에 따라 경고 또는 오류 (정적 속성은 클래스로 접근 권장)
     ?&gt;</code></pre>
     <p>정적 멤버는 유틸리티 함수나 클래스 관련 상수 관리 등에 사용됩니다.</p>

    <h3 id="oop-constants">클래스 상수</h3>
    <p>클래스 내에서 변경되지 않는 고정된 값을 정의할 때 <code>const</code> 키워드를 사용합니다. 클래스 상수 역시 클래스 자체에 속하며, <code>ClassName::CONSTANT_NAME</code>으로 접근합니다. 메서드 내에서는 <code>self::CONSTANT_NAME</code>으로 접근합니다.</p>
     <pre><code class="language-php">&lt;?php
     class MathUtils {
         const PI = 3.14159; // 클래스 상수 정의

         public static function circleArea($radius) {
             return self::PI * $radius * $radius; // self::로 상수 접근
         }
     }

     echo "원주율: " . MathUtils::PI . "&lt;br&gt;"; // 클래스 이름으로 상수 접근
     echo "반지름 5인 원의 넓이: " . MathUtils::circleArea(5) . "&lt;br&gt;";
     ?&gt;</code></pre>

     <h3 id="oop-namespaces">네임스페이스 (Namespaces)</h3>
     <p>네임스페이스는 코드(클래스, 함수, 상수 등)를 그룹화하여 이름 충돌을 방지하는 방법입니다. 특히 라이브러리나 프레임워크를 사용하거나 대규모 프로젝트를 진행할 때 필수적입니다.</p>
     <p><code>namespace Vendor\Package;</code> 처럼 파일 상단에 선언하여 해당 파일의 코드가 속할 네임스페이스를 지정합니다.</p>
     <p>다른 네임스페이스의 코드를 사용하려면 <code>use Vendor\Package\ClassName;</code> 구문으로 가져오거나, 전체 네임스페이스 경로(<code>\Vendor\Package\ClassName</code>)를 사용합니다.</p>
      <pre><code class="language-php">// 파일: src/MyLibrary/Utils.php
&lt;?php
namespace MyLibrary; // 네임스페이스 선언

const VERSION = "1.0";

function helperFunction() {
    return "Helper from MyLibrary";
}

class Calculator {
    public function add($a, $b) { return $a + $b; }
}
?&gt;

// 파일: index.php
&lt;?php
require_once 'src/MyLibrary/Utils.php'; // 실제로는 오토로더 사용

// use 키워드로 가져오기
use MyLibrary\Calculator;
use function MyLibrary\helperFunction; // 함수 가져오기 (PHP 5.6+)
use const MyLibrary\VERSION;          // 상수 가져오기 (PHP 5.6+)

// use MyLibrary as Lib; // 별칭 사용

$calc = new Calculator(); // 네임스페이스 없이 클래스 이름 사용
echo $calc->add(10, 5); // 15
echo "&lt;br&gt;";

echo helperFunction(); // Helper from MyLibrary
echo "&lt;br&gt;";
echo VERSION; // 1.0
echo "&lt;br&gt;";

// 전체 경로 사용
$calc2 = new \MyLibrary\Calculator();
echo \MyLibrary\helperFunction();
?&gt;</code></pre>

    <h3 id="oop-autoloading">오토로딩 (Autoloading)</h3>
    <p>클래스를 사용하려고 할 때 해당 클래스 파일이 아직 포함(include/require)되지 않았다면, PHP는 자동으로 해당 파일을 찾아 로드하는 메커니즘을 제공합니다. 이를 오토로딩이라고 합니다.</p>
    <p><code>spl_autoload_register()</code> 함수를 사용하여 오토로더 함수(클래스 이름을 인수로 받아 해당 파일을 require하는 함수)를 등록하는 것이 표준적인 방법입니다.</p>
    <p>현대 PHP 개발에서는 Composer라는 의존성 관리 도구가 PSR-4 표준에 따른 오토로딩 설정을 자동으로 처리해 주는 경우가 대부분입니다.</p>
      <pre><code class="language-php">&lt;?php
      // 간단한 오토로더 예시 (실제 프로젝트에서는 Composer 사용 권장)
      spl_autoload_register(function ($className) {
          // 네임스페이스와 클래스 이름을 파일 경로로 변환 (PSR-4 규칙 유사하게)
          // 예: MyLibrary\Calculator -> src/MyLibrary/Calculator.php
          echo "Autoloading class: " . $className . "&lt;br&gt;";
          $baseDir = __DIR__ . '/src/'; // 기본 디렉토리 설정 필요
          $className = ltrim($className, '\\');
          $fileName  = '';
          if ($lastNsPos = strrpos($className, '\\')) {
              $namespace = substr($className, 0, $lastNsPos);
              $className = substr($className, $lastNsPos + 1);
              $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
          }
          $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
          $filePath = $baseDir . $fileName;

          if (file_exists($filePath)) {
              require $filePath;
          }
      });

      // 이제 require 없이 클래스 사용 가능 (spl_autoload_register가 파일을 찾아 로드)
      // 가정: src/MyLibrary/Utils.php 파일이 존재하고 MyLibrary\Calculator 클래스 정의
      try {
        $calc = new MyLibrary\Calculator();
        echo $calc->add(20, 30); // 50
      } catch (Error $e) {
          echo "오토로딩 실패 또는 클래스 없음: " . $e->getMessage();
      }
      ?&gt;</code></pre>

    <h3 id="oop-more">(간략) 인터페이스, 추상 클래스, 트레이트</h3>
    <ul>
        <li><strong>인터페이스 (Interface):</strong> 클래스가 반드시 구현해야 하는 메서드의 목록(껍데기)을 정의합니다. 다중 상속 효과를 낼 수 있습니다 (<code>implements</code> 키워드 사용).</li>
        <li><strong>추상 클래스 (Abstract Class):</strong> 직접 인스턴스화될 수 없으며, 하나 이상의 추상 메서드(구현 내용 없는 메서드)를 포함할 수 있는 클래스입니다. 자식 클래스에서 반드시 추상 메서드를 구현해야 합니다 (<code>abstract</code> 키워드 사용).</li>
        <li><strong>트레이트 (Trait):</strong> 클래스에 메서드 구현을 재사용하기 위한 메커니즘입니다. 상속과 달리 여러 트레이트를 하나의 클래스에서 사용할 수 있습니다 (<code>trait</code>, <code>use</code> 키워드 사용, PHP 5.4+).</li>
    </ul>
</section>

<section id="php-errors">
    <h2>에러 처리 및 디버깅</h2>
    <p>개발 중 발생하는 에러를 효과적으로 처리하고 코드를 디버깅하는 것은 매우 중요합니다.</p>

    <h3 id="errors-reporting">에러 리포팅 설정</h3>
    <p>PHP가 어떤 종류의 에러를 보고하고 화면에 표시할지를 설정할 수 있습니다.</p>
    <ul>
        <li><code>error_reporting(level)</code>: 보고할 에러 레벨을 설정합니다. (예: <code>E_ALL</code> - 모든 에러 및 경고, <code>E_ERROR | E_PARSE</code> - 심각한 에러만). 개발 중에는 <code>E_ALL</code>을 권장합니다.</li>
        <li><code>display_errors</code> (php.ini 또는 <code>ini_set()</code>): 에러 메시지를 화면에 표시할지 여부를 결정합니다 ('On' 또는 'Off'). 개발 환경에서는 'On'으로 설정하여 문제를 즉시 파악하고, 실제 서비스(운영) 환경에서는 반드시 'Off'로 설정하여 사용자에게 내부 오류 정보가 노출되지 않도록 해야 합니다.</li>
    </ul>
     <pre><code class="language-php">&lt;?php
     // 개발 환경 설정 예시 (스크립트 상단)
     error_reporting(E_ALL); // 모든 에러 보고
     ini_set('display_errors', 1); // 화면에 에러 표시 켜기

     // 운영 환경 설정 예시 (php.ini 에서 설정 권장)
     // error_reporting(E_ALL); // 모든 에러 보고는 유지하되,
     // ini_set('display_errors', 0); // 화면 표시는 끄고
     // ini_set('log_errors', 1); // 에러 로깅은 켬
     // ini_set('error_log', '/path/to/php-error.log'); // 에러 로그 파일 경로 지정
     ?&gt;</code></pre>

    <h3 id="errors-logging">에러 로깅</h3>
    <p><code>display_errors</code>를 끈 운영 환경에서도 에러 발생 사실과 내용을 기록하여 문제를 추적할 수 있도록 에러 로깅을 설정해야 합니다.</p>
    <ul>
        <li><code>error_log(message, [message_type], [destination], [extra_headers])</code>: 에러 메시지를 지정된 곳(기본값: PHP 에러 로그 파일)에 기록합니다.</li>
        <li><code>php.ini</code> 설정: <code>log_errors = On</code>, <code>error_log = /path/to/logfile</code> 등으로 에러 로그 파일 경로를 지정하는 것이 일반적입니다.</li>
    </ul>

    <h3 id="errors-exceptions">예외 처리 (try-catch-finally, throw)</h3>
    <p>예외(Exception)는 예상치 못한 에러 상황이 발생했을 때 이를 처리하기 위한 객체 지향적인 방법입니다. <code>try...catch...finally</code> 블록을 사용합니다.</p>
    <ul>
        <li><code>try { ... }</code>: 예외가 발생할 수 있는 코드를 실행합니다.</li>
        <li><code>catch (ExceptionType $e) { ... }</code>: <code>try</code> 블록에서 특정 타입(<code>ExceptionType</code>)의 예외가 발생하면 실행됩니다. <code>$e</code> 변수는 예외 객체를 담고 있으며, <code>getMessage()</code>, <code>getCode()</code>, <code>getFile()</code>, <code>getLine()</code>, <code>getTraceAsString()</code> 등의 메서드로 상세 정보를 얻을 수 있습니다. 여러 개의 <code>catch</code> 블록을 사용하여 다른 타입의 예외를 개별적으로 처리할 수 있습니다.</li>
        <li><code>finally { ... }</code> (PHP 5.5+): 예외 발생 여부와 관계없이 항상 실행됩니다. 자원 해제 등에 사용됩니다.</li>
        <li><code>throw new ExceptionType("Error message", code);</code>: 의도적으로 예외를 발생시킵니다. <code>Exception</code> 클래스를 상속받아 사용자 정의 예외 클래스를 만들 수도 있습니다.</li>
    </ul>
    <p>PDO는 에러 모드를 <code>PDO::ERRMODE_EXCEPTION</code>으로 설정하면 데이터베이스 오류 발생 시 <code>PDOException</code>을 발생시켜 <code>try...catch</code>로 처리할 수 있습니다.</p>
     <pre><code class="language-php">&lt;?php
     function calculateInverse($number) {
         if (!is_numeric($number)) {
             throw new InvalidArgumentException("숫자만 입력 가능합니다."); // 예외 발생
         }
         if ($number == 0) {
             throw new Exception("0으로 나눌 수 없습니다."); // 일반 예외 발생
         }
         return 1 / $number;
     }

     try {
         echo "계산 시작...&lt;br&gt;";
         $result1 = calculateInverse(10);
         echo "10의 역수: " . $result1 . "&lt;br&gt;";

         $result2 = calculateInverse(0); // Exception 발생
         echo "0의 역수: " . $result2 . "&lt;br&gt;"; // 실행 안 됨

         $result3 = calculateInverse("abc"); // InvalidArgumentException 발생
         echo "'abc'의 역수: " . $result3 . "&lt;br&gt;"; // 실행 안 됨

     } catch (InvalidArgumentException $e) { // 특정 예외 타입 처리
         echo "잘못된 인수 오류: " . $e->getMessage() . "&lt;br&gt;";
     } catch (Exception $e) { // 그 외 모든 Exception 타입 처리 (순서 중요)
         echo "일반 오류: " . $e->getMessage() . "&lt;br&gt;";
         // 에러 로깅 등 추가 처리 가능
         error_log("Error in calculateInverse: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
     } finally {
         echo "계산 시도 완료.&lt;br&gt;"; // 항상 실행
     }

     echo "스크립트 계속 실행...&lt;br&gt;";
     ?&gt;</code></pre>

    <h3 id="errors-debugging">디버깅 기법</h3>
    <p>코드의 오류를 찾고 수정하는 과정(디버깅)을 위한 몇 가지 방법입니다.</p>
    <ul>
        <li><code>var_dump($variable1, ...)</code>: 변수의 타입과 값을 포함한 상세 정보를 출력합니다. 가장 기본적이고 유용한 디버깅 함수입니다.</li>
        <li><code>print_r($variable, [return])</code>: 변수의 정보를 사람이 읽기 쉬운 형태로 출력합니다. 배열이나 객체 구조를 볼 때 유용합니다. 두 번째 인수로 <code>true</code>를 주면 출력 대신 문자열로 반환합니다.</li>
        <li><code>debug_print_backtrace()</code> / <code>debug_backtrace()</code>: 함수 호출 스택(어떤 함수가 순서대로 호출되었는지) 정보를 출력하거나 배열로 반환합니다. 복잡한 코드 흐름 추적에 도움이 됩니다.</li>
        <li><strong>Xdebug:</strong> 강력한 PHP 디버깅 및 프로파일링 확장 도구입니다. 에디터/IDE와 연동하여 중단점(breakpoint) 설정, 변수 값 확인, 코드 실행 단계별 추적(step debugging) 등 고급 디버깅 기능을 제공합니다. 개발 환경에 설정하여 사용하는 것을 적극 권장합니다.</li>
        <li>로그 파일 분석: <code>error_log()</code> 함수나 서버 로그 파일을 통해 에러 및 원하는 변수 값을 기록하고 분석합니다.</li>
    </ul>
</section>

<section id="php-security">
    <h2>보안 기초</h2>
    <p>웹 애플리케이션 개발 시 보안은 매우 중요합니다. PHP 애플리케이션에서 흔히 발생하는 보안 취약점과 기본적인 방어 방법을 알아봅니다.</p>
    <p class="warning">보안은 매우 깊고 광범위한 주제이며, 여기에 소개된 내용은 기본적인 사항입니다. 안전한 애플리케이션 개발을 위해서는 지속적인 학습과 주의가 필요합니다.</p>

    <h3 id="security-xss">XSS (Cross-Site Scripting) 방지</h3>
    <p>XSS는 공격자가 악의적인 스크립트(주로 JavaScript)를 웹 페이지에 삽입하여 다른 사용자의 브라우저에서 실행되도록 하는 공격입니다. 사용자의 쿠키 탈취, 개인 정보 유출 등의 피해를 일으킬 수 있습니다.</p>
    <p><strong>방어:</strong> 사용자 입력값 등 신뢰할 수 없는 데이터를 HTML 페이지에 출력할 때는 항상 <code>htmlspecialchars()</code> 함수를 사용하여 특수 문자(<code>&lt;</code>, <code>&gt;</code>, <code>&</code>, <code>"</code>, <code>'</code>)를 HTML 엔티티로 변환해야 합니다.</p>
    <pre><code class="language-php">&lt;?php
    // $user_comment = $_POST['comment']; // 사용자로부터 받은 댓글 내용 (악성 스크립트 포함 가능)
    $user_comment = '&lt;script&gt;alert("악성 스크립트 실행!");&lt;/script&gt;'; // 예시

    // 잘못된 출력 (XSS 취약)
    // echo "&lt;div&gt;" . $user_comment . "&lt;/div&gt;";

    // 안전한 출력
    echo "&lt;div&gt;" . htmlspecialchars($user_comment, ENT_QUOTES | ENT_HTML5, 'UTF-8') . "&lt;/div&gt;";
    // ENT_QUOTES: 작은따옴표, 큰따옴표 모두 변환
    // ENT_HTML5: HTML5 엔티티 사용
    // 'UTF-8': 문자 인코딩 지정
    ?&gt;</code></pre>
    <p>상황에 따라 <code>strip_tags()</code>를 사용하여 HTML 태그 자체를 제거할 수도 있지만, <code>htmlspecialchars()</code>가 더 일반적이고 안전한 방법입니다.</p>

    <h3 id="security-sql-injection">SQL 인젝션 방지</h3>
    <p>SQL 인젝션은 공격자가 입력값을 조작하여 원래 의도와 다른 악의적인 SQL 쿼리가 데이터베이스에서 실행되도록 하는 공격입니다. 데이터 유출, 변조, 삭제 등 심각한 피해를 일으킬 수 있습니다.</p>
    <p><strong>방어:</strong> 데이터베이스 쿼리에 사용자 입력값을 직접 포함시키지 말고, 항상 Prepared Statements (MySQLi 또는 PDO) 를 사용해야 합니다. Prepared Statements는 쿼리 구조와 데이터를 분리하여 처리하므로 SQL 인젝션을 효과적으로 방지합니다. (자세한 내용은 <a href="#db-prepared">데이터베이스 연동 섹션</a> 참조)</p>
    <p class="warning">절대로 사용자 입력을 그대로 SQL 쿼리 문자열에 연결(concatenation)하는 방식을 사용해서는 안 됩니다! 구형 함수인 <code>mysql_real_escape_string()</code> 등은 더 이상 충분한 보호를 제공하지 못합니다.</p>

    <h3 id="security-csrf">CSRF (Cross-Site Request Forgery) 방지</h3>
    <p>CSRF는 사용자가 자신의 의지와 무관하게 공격자가 의도한 행동(예: 글쓰기, 회원 정보 수정, 탈퇴)을 특정 웹사이트에 요청하게 만드는 공격입니다. 사용자가 로그인된 상태를 악용합니다.</p>
    <p><strong>방어:</strong> 상태 변경을 유발하는 중요한 요청(POST, PUT, DELETE 등)에 대해 CSRF 토큰을 사용하는 것이 일반적인 방법입니다.</p>
    <ol>
        <li>사용자에게 폼을 보여줄 때, 서버는 예측 불가능한 임의의 토큰 값을 생성하여 세션에 저장하고 폼 내의 숨겨진 필드(<code>&lt;input type="hidden" name="csrf_token" value="..."&gt;</code>)에 포함시켜 전송합니다.</li>
        <li>사용자가 폼을 제출하면, 서버는 제출된 폼 데이터의 토큰 값과 세션에 저장된 토큰 값을 비교합니다.</li>
        <li>두 토큰 값이 일치하면 유효한 요청으로 간주하고 처리하며, 일치하지 않으면 CSRF 공격 시도로 보고 요청을 거부합니다.</li>
        <li>토큰은 각 사용자 세션마다, 또는 각 요청마다 고유하게 생성하는 것이 좋습니다.</li>
    </ol>
    <p>프레임워크를 사용하면 CSRF 보호 기능이 내장되어 있는 경우가 많습니다.</p>

    <h3 id="security-password">비밀번호 해싱</h3>
    <p>사용자 비밀번호를 데이터베이스에 절대 평문(plaintext)으로 저장해서는 안 됩니다. 만약 데이터베이스가 유출될 경우 모든 사용자 비밀번호가 노출됩니다.</p>
    <p><strong>처리:</strong> 비밀번호는 반드시 강력한 단방향 해시 함수를 사용하여 해싱한 후 저장해야 합니다. PHP 5.5 이상에서는 <code>password_hash()</code> 함수와 <code>password_verify()</code> 함수 사용이 표준입니다.</p>
    <ul>
        <li><code>password_hash(password, algorithm, [options])</code>: 비밀번호를 안전하게 해싱합니다. <code>algorithm</code>은 <code>PASSWORD_DEFAULT</code> (현재 권장 알고리즘, 현재는 bcrypt) 또는 <code>PASSWORD_BCRYPT</code> 등을 사용합니다. 자동으로 솔트(salt)를 생성하고 포함시켜 레인보우 테이블 공격을 방지합니다.</li>
        <li><code>password_verify(password, hash)</code>: 사용자가 입력한 비밀번호(<code>password</code>)가 저장된 해시(<code>hash</code>)와 일치하는지 안전하게 검증합니다.</li>
    </ul>
    <pre><code class="language-php">&lt;?php
    $plain_password = "mySecretPassword123";

    // 비밀번호 해싱 (회원 가입 시)
    // PASSWORD_DEFAULT는 PHP 버전에 따라 더 강력한 알고리즘으로 변경될 수 있음
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
    echo "해시된 비밀번호 (DB 저장용): " . $hashed_password . "&lt;br&gt;";
    // 예시 출력: $2y$10$abcdefghijklmnopqrstuvwxzyABCDEFGHIJKLMNOPQRSTUV (매번 달라짐)

    // 비밀번호 검증 (로그인 시)
    $login_attempt = "mySecretPassword123";
    if (password_verify($login_attempt, $hashed_password)) {
        echo "비밀번호 일치! 로그인 성공.&lt;br&gt;";
    } else {
        echo "비밀번호 불일치! 로그인 실패.&lt;br&gt;";
    }

    $wrong_attempt = "wrongPassword";
    if (password_verify($wrong_attempt, $hashed_password)) {
        echo "비밀번호 일치! 로그인 성공.&lt;br&gt;";
    } else {
        echo "비밀번호 불일치! 로그인 실패.&lt;br&gt;";
    }
    ?&gt;</code></pre>
    <p class="warning">절대로 <code>md5()</code>, <code>sha1()</code> 같은 오래된 해시 함수를 비밀번호 저장에 사용해서는 안 됩니다. 이 함수들은 현재 기준으로 매우 취약합니다.</p>

    <h3 id="security-file-uploads">파일 업로드 보안</h3>
    <p>파일 업로드 기능은 악성 파일(웹 셸 등)이 서버에 업로드되어 실행될 위험이 있습니다. (<a href="#php-uploads">파일 업로드 처리 섹션</a> 내용 복습 및 강조)</p>
    <p><strong>방어:</strong></p>
    <ul>
        <li>업로드 파일 확장자 및 MIME 타입을 엄격하게 화이트리스트 방식으로 제한합니다 (허용할 것만 명시).</li>
        <li>파일 크기 제한을 서버 측에서 반드시 검증합니다.</li>
        <li>업로드된 파일 이름은 절대 사용자 입력을 그대로 사용하지 않고, 서버에서 안전한 방식으로 새로 생성합니다 (예: 고유 ID, 해시값 사용). 원래 확장자는 유지할 수 있습니다.</li>
        <li>업로드된 파일은 웹에서 직접 접근 불가능한 디렉토리에 저장하는 것이 가장 안전합니다. 웹에서 접근해야 한다면, PHP 스크립트를 통해 인증/권한 검사 후 파일 내용을 읽어 출력(<code>readfile()</code>)하는 방식으로 제공합니다.</li>
        <li>업로드 디렉토리의 실행 권한을 제거합니다.</li>
        <li>가능하다면 바이러스 검사 솔루션을 연동합니다.</li>
    </ul>
</section>

<section id="php-modern">
    <h2>현대 PHP 및 다음 단계</h2>
    <p>PHP는 꾸준히 발전하고 있는 언어입니다. 과거의 PHP 이미지와 달리, 현대 PHP는 강력한 객체 지향 기능, 타입 시스템 개선, 성능 향상, 그리고 풍부한 생태계를 갖추고 있습니다.</p>

    <h3 id="modern-composer">Composer (의존성 관리)</h3>
    <p>Composer는 PHP 프로젝트의 의존성 관리(Dependency Management) 도구입니다. 프로젝트에 필요한 외부 라이브러리(패키지)들을 선언하고(<code>composer.json</code> 파일), 자동으로 다운로드 및 설치, 업데이트, 오토로딩 설정을 관리해 줍니다. 현대 PHP 개발의 필수 도구입니다.</p>
    <p><a href="https://getcomposer.org/" target="_blank">Packagist</a>는 Composer가 사용하는 기본 패키지 저장소로, 수많은 오픈소스 PHP 라이브러리를 찾고 사용할 수 있습니다.</p>

    <h3 id="modern-psr">PSR 표준</h3>
    <p>PSR(PHP Standards Recommendations)은 PHP 커뮤니티 그룹(PHP-FIG)에서 제정한 일련의 코딩 표준 권고안입니다. 여러 개발자와 프레임워크가 일관된 방식으로 코드를 작성하고 상호 운용성을 높이기 위한 목적을 가집니다.</p>
    <p>주요 PSR 표준:</p>
    <ul>
        <li>PSR-1: 기본 코딩 표준 (파일, 네임스페이스, 클래스 이름 등)</li>
        <li>PSR-12: 확장 코딩 스타일 가이드 (PSR-2 확장, 들여쓰기, 제어 구조 등 상세 규칙)</li>
        <li>PSR-4: 오토로딩 표준 (네임스페이스와 파일 경로 매핑 규칙)</li>
        <li>PSR-3: 로거 인터페이스</li>
        <li>PSR-7: HTTP 메시지 인터페이스 (요청/응답 객체)</li>
        <li>PSR-11: 컨테이너 인터페이스 (의존성 주입)</li>
        <li>PSR-15: HTTP 서버 요청 핸들러 (미들웨어)</li>
    </ul>
    <p>PSR 표준을 따르면 코드 품질과 협업 효율성을 높일 수 있습니다.</p>

    <h3 id="modern-frameworks">프레임워크 (Laravel, Symfony 등)</h3>
    <p>PHP 프레임워크는 웹 애플리케이션 개발에 필요한 공통적인 기능(라우팅, ORM, 템플릿 엔진, 보안 기능 등)을 미리 구현해 놓은 뼈대 구조입니다. 프레임워크를 사용하면 개발 생산성을 크게 높이고, 표준화된 구조를 따르며, 보안 및 성능 관련 모범 사례를 적용하는 데 도움이 됩니다.</p>
    <p>인기 있는 PHP 프레임워크:</p>
    <ul>
        <li><strong>Laravel:</strong> 가장 인기 있는 프레임워크 중 하나로, 우아한 문법과 편리한 기능, 방대한 생태계가 특징입니다.</li>
        <li><strong>Symfony:</strong> 견고하고 유연하며 재사용 가능한 컴포넌트 기반의 프레임워크로, 대규모 애플리케이션 개발에 적합합니다. Laravel 등 다른 많은 프로젝트에 영향을 주었습니다.</li>
        <li><strong>CodeIgniter:</strong> 가볍고 배우기 쉬우며 좋은 성능을 가진 프레임워크입니다.</li>
        <li>Laminas (구 Zend Framework), CakePHP 등 다양한 프레임워크가 있습니다.</li>
    </ul>
    <p>PHP 기초를 다진 후에는 프레임워크를 학습하여 실제 웹 애플리케이션 개발 역량을 키우는 것이 좋습니다.</p>

    <h3 id="modern-versions">PHP 버전</h3>
    <p>PHP는 지속적으로 새로운 버전이 출시되며, 각 버전마다 새로운 기능 추가, 성능 향상, 보안 강화가 이루어집니다. 과거 버전은 보안 지원이 중단(End Of Life, EOL)되므로, 항상 최신 안정 버전 또는 보안 지원이 활성화된 버전을 사용하는 것이 매우 중요합니다.</p>
    <p>현재(2025년 기준 가정) PHP 8.x 버전이 널리 사용되고 있으며, 이전 버전(특히 PHP 7.4 미만)은 사용을 지양해야 합니다. PHP 공식 웹사이트(<a href="https://www.php.net/supported-versions.php" target="_blank">php.net</a>)나 <a href="https://php.watch/versions" target="_blank">PHP.Watch</a> 등에서 버전별 지원 기간을 확인할 수 있습니다.</p>

    <h3 id="modern-next">마무리 및 추가 학습</h3>
    <p>이 강좌를 통해 PHP의 기본적인 문법부터 웹 연동, 데이터베이스 처리, OOP 기초, 보안 개념까지 폭넓게 다루었습니다. 이 지식을 바탕으로 여러분은 동적인 웹 애플리케이션을 개발할 수 있는 능력을 갖추게 되었습니다.</p>
    <p>앞으로 더 성장하기 위한 추천 학습 방향:</p>
    <ul>
        <li>PHP 심화 학습: OOP 고급(인터페이스, 트레이트, 디자인 패턴), 비동기 PHP(Swoole, ReactPHP - 고급), 성능 최적화 등.</li>
        <li>프레임워크 학습: Laravel 또는 Symfony 와 같은 주요 프레임워크 중 하나를 선택하여 깊이 있게 학습합니다.</li>
        <li>데이터베이스 심화: SQL 쿼리 최적화, 인덱싱, 트랜잭션 관리 등.</li>
        <li>프론트엔드 기술 학습: JavaScript, CSS 및 관련 프레임워크(React, Vue 등)를 함께 학습하여 풀스택 개발 역량을 강화합니다.</li>
        <li>서버 및 인프라 지식: 웹 서버(Nginx, Apache) 설정, 리눅스 기본 명령어, Docker, 클라우드 서비스(AWS, GCP 등) 관련 지식.</li>
        <li>꾸준한 실습: 직접 프로젝트를 기획하고 개발해보는 것이 가장 중요합니다.</li>
    </ul>
     <p class="note"><strong>PHP 개발자로서의 여정을 응원합니다! 꾸준히 학습하고 경험을 쌓아 멋진 웹 애플리케이션을 만들어나가시길 바랍니다.</strong></p>
</section>


<br><br>
<hr>

<script src="../js/script.js?ver=1"></script>

</body>
</html>