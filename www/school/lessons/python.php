<?php
// PHP 관련 설정 (이 파일 자체는 PHP 실행보다는 HTML/Python 내용 표시에 중점)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Python 강좌 </title>
  <link rel="stylesheet" href="../css/lessons_style.css?ver=1">
  <style>
    /* 기본 스타일 */
    body { font-family: sans-serif; line-height: 1.6; color: #333; }
    .toc { border: 1px solid #ccc; padding: 15px; margin-bottom: 30px; background-color: #f9f9f9; }
    .toc h2 { margin-top: 0; }
    .toc ul { list-style-type: disc; margin-left: 20px; }
    .toc ul ul { list-style-type: circle; margin-left: 20px; }
    h1, h2 { border-bottom: 2px solid #eee; padding-bottom: 5px; color: #34495e; /* Python Blue 느낌 */ }
    h2 { margin-top: 40px; }
    h3 { margin-top: 30px; border-bottom: 1px dashed #ccc; padding-bottom: 3px; color: #f39c12; /* Python Yellow 느낌 */ }
    h4 { margin-top: 25px; font-weight: bold; color: #7f8c8d; }
    pre { background-color: #23241f; color: #f8f8f2; padding: 15px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; font-size: 0.95em; line-height: 1.5; font-family: 'Fira Code', Consolas, 'Courier New', monospace; }
    /* 간단한 Python 구문 강조 */
    code.language-python .py-keyword { color: #f92672; font-weight: bold; } /* def, class, if, for, import 등 */
    code.language-python .py-function { color: #a6e22e; } /* 함수 이름 */
    code.language-python .py-string { color: #e6db74; } /* 문자열 */
    code.language-python .py-number { color: #ae81ff; } /* 숫자 */
    code.language-python .py-comment { color: #75715e; font-style: italic; } /* 주석 */
    code.language-python .py-operator { color: #f92672; } /* =, +, -, *, / 등 */
    code.language-python .py-builtin { color: #66d9ef; } /* print, len, type 등 내장 함수 */
    code.language-python .py-parameter { color: #fd971f; } /* self, 매개변수 */
    code.language-python .py-class { color: #a6e22e; font-weight: bold;} /* 클래스 이름 */

    code.language-html { font-family: Consolas, 'Courier New', monospace; color: #333; }
    .example { border: 1px solid #ecf0f1; padding: 15px; margin-top: 10px; margin-bottom: 20px; background-color: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .example h4 { margin-top: 0; font-size: 1.1em; color: #3498db; }
    .output { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; margin-top: 5px; border-radius: 3px; font-size: 0.95em; white-space: pre-wrap; font-family: Consolas, 'Courier New', monospace;}
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

<h1> Python 강좌 </h1>
<p>이 페이지는 간결하고 강력한 프로그래밍 언어인 Python의 기초부터 핵심적인 사용법까지 다룹니다. 첫 번째 파트에서는 Python 소개, 설치 및 실행 방법, 기본적인 문법 규칙, 변수, 주요 데이터 타입(숫자, 불리언), 그리고 연산자 사용법을 학습합니다.</p>

<div class="toc">
  <h2>📖 Python 강좌 목차</h2>
  <ul>
    <li><a href="#python-intro">Python 소개 및 시작하기</a>
        <ul>
            <li><a href="#intro-what-is-python">Python이란?</a></li>
            <li><a href="#intro-features">특징 및 장점</a></li>
            <li><a href="#intro-applications">활용 분야</a></li>
            <li><a href="#intro-setup">Python 설치 및 환경 설정</a></li>
            <li><a href="#intro-repl">대화형 인터프리터 (REPL) 사용</a></li>
            <li><a href="#intro-running-scripts">Python 스크립트 파일 실행 (.py)</a></li>
            <li><a href="#intro-editors">코드 에디터 및 IDE</a></li>
        </ul>
    </li>
    <li><a href="#python-syntax">기본 문법 및 입출력</a>
        <ul>
            <li><a href="#syntax-indentation">들여쓰기 (Indentation)</a></li>
            <li><a href="#syntax-statements">문장 (Statements)</a></li>
            <li><a href="#syntax-comments">주석 (Comments)</a></li>
            <li><a href="#syntax-variables">변수와 할당</a></li>
            <li><a href="#syntax-naming">식별자 규칙 및 Naming Conventions</a></li>
            <li><a href="#syntax-io">기본 입출력 (print(), input())</a></li>
        </ul>
    </li>
    <li><a href="#python-data-types">데이터 타입 (Data Types)</a>
        <ul>
            <li><a href="#types-overview">데이터 타입 개요 및 동적 타이핑</a></li>
            <li><a href="#types-numeric">숫자형 (int, float, complex)</a></li>
            <li><a href="#types-boolean">불리언 (bool)</a></li>
            <li><a href="#types-none">None 타입</a></li>
            <li><a href="#types-sequence">시퀀스 타입 (Sequence Types)</a>
                <ul>
                    <li><a href="#types-string">문자열 (str)</a></li>
                    <li><a href="#types-list">리스트 (list)</a></li>
                    <li><a href="#types-tuple">튜플 (tuple)</a></li>
                    <li><a href="#types-range">range 객체</a></li>
                </ul>
            </li>
            <li><a href="#types-mapping">매핑 타입 (Mapping Type)</a>
                 <ul>
                    <li><a href="#types-dict">딕셔너리 (dict)</a></li>
                </ul>
            </li>
             <li><a href="#types-set">세트 타입 (Set Types)</a>
                 <ul>
                    <li><a href="#types-set-set">세트 (set)</a></li>
                    <li><a href="#types-set-frozenset">프로즌세트 (frozenset)</a></li>
                </ul>
            </li>
            <li><a href="#types-checking">타입 확인 (type(), isinstance())</a></li>
            <li><a href="#types-casting">형 변환 (Type Casting)</a></li>
        </ul>
    </li>
     <li><a href="#python-operators">연산자 (Operators)</a>
        <ul>
            <li><a href="#operators-arithmetic">산술 연산자</a></li>
            <li><a href="#operators-assignment">할당 연산자</a></li>
            <li><a href="#operators-comparison">비교 연산자</a></li>
            <li><a href="#operators-logical">논리 연산자</a></li>
            <li><a href="#operators-identity">식별 연산자 (is, is not)</a></li>
            <li><a href="#operators-membership">멤버십 연산자 (in, not in)</a></li>
            <li><a href="#operators-bitwise">비트 연산자 (간략히)</a></li>
            <li><a href="#operators-precedence">연산자 우선순위</a></li>
        </ul>
    </li>
     <li><a href="#python-control-flow">제어 흐름 (Control Flow)</a>
        <ul>
            <li><a href="#control-if">조건문 (if, elif, else)</a></li>
            <li><a href="#control-for">for 반복문 (in, range)</a></li>
            <li><a href="#control-while">while 반복문</a></li>
            <li><a href="#control-break-continue-else">반복 제어 (break, continue, else)</a></li>
            <li><a href="#control-pass">pass 문</a></li>
        </ul>
    </li>
     <li><a href="#python-functions">함수 (Functions)</a>
        <ul>
            <li><a href="#functions-defining">함수 정의 (def) 및 호출</a></li>
            <li><a href="#functions-docstrings">독스트링 (Docstrings)</a></li>
            <li><a href="#functions-parameters">매개변수와 인수 (위치, 키워드, 기본값)</a></li>
            <li><a href="#functions-args-kwargs">가변 인자 (*args, kwargs)</a></li>
            <li><a href="#functions-return">반환 값 (return)</a></li>
            <li><a href="#functions-scope">변수 스코프 (LEGB Rule, global, nonlocal)</a></li>
            <li><a href="#functions-lambda">람다 함수 (Lambda Functions)</a></li>
            <li><a href="#functions-map-filter">map, filter (간략히)</a></li>
            <li><a href="#functions-annotations">타입 힌트 (Type Hints)</a></li>
        </ul>
    </li>
    <li><a href="#python-modules-packages">모듈과 패키지</a>
        <ul>
            <li><a href="#modules-importing">모듈 임포트 (import, from...import, as)</a></li>
            <li><a href="#modules-standard-library">표준 라이브러리 소개 (math, random, datetime 등)</a></li>
            <li><a href="#modules-creating">모듈 만들기</a></li>
            <li><a href="#modules-name-main">`if __name__ == "__main__":`</a></li>
            <li><a href="#modules-packages">패키지 개념 및 생성</a></li>
            <li><a href="#modules-pip">pip와 PyPI (외부 패키지 설치)</a></li>
        </ul>
    </li>
     <li><a href="#python-file-io">파일 입출력 (File I/O)</a>
        <ul>
            <li><a href="#file-opening">파일 열기 (open(), 모드)</a></li>
            <li><a href="#file-with">with 문 사용</a></li>
            <li><a href="#file-reading">파일 읽기 (.read(), .readline(), .readlines())</a></li>
            <li><a href="#file-writing">파일 쓰기 (.write(), .writelines())</a></li>
            <li><a href="#file-paths">파일 경로 다루기 (os.path)</a></li>
        </ul>
    </li>
     <li><a href="#python-exceptions">예외 처리 (Exceptions)</a>
        <ul>
            <li><a href="#exceptions-intro">예외란?</a></li>
            <li><a href="#exceptions-try-except">try...except 블록</a></li>
            <li><a href="#exceptions-handling-specific">특정 예외 처리</a></li>
            <li><a href="#exceptions-else-finally">else 및 finally 블록</a></li>
            <li><a href="#exceptions-raise">예외 발생시키기 (raise)</a></li>
            <li><a href="#exceptions-custom">사용자 정의 예외</a></li>
        </ul>
    </li>
    <li><a href="#python-oop">객체 지향 프로그래밍 (OOP) 기초</a>
        <ul>
            <li><a href="#oop-concepts">OOP 개념 소개</a></li>
            <li><a href="#oop-classes-objects">클래스(class)와 객체(object)</a></li>
            <li><a href="#oop-init">생성자 (__init__)</a></li>
            <li><a href="#oop-attributes">속성 (인스턴스 변수, 클래스 변수)</a></li>
            <li><a href="#oop-methods">메서드 (인스턴스 메서드, self)</a></li>
            <li><a href="#oop-inheritance">상속 (Inheritance)</a></li>
            <li><a href="#oop-super">super() 함수</a></li>
            <li><a href="#oop-special-methods">특수 메서드 (Dunder Methods)</a></li>
            <li><a href="#oop-more">(간략) 정보 은닉, 데코레이터(@property)</a></li>
        </ul>
    </li>
    <li><a href="#python-stdlib-intro">주요 표준 라이브러리 활용</a>
        <ul>
            <li><a href="#stdlib-datetime">datetime (날짜와 시간)</a></li>
            <li><a href="#stdlib-math">math (수학 함수)</a></li>
            <li><a href="#stdlib-random">random (난수 생성)</a></li>
            <li><a href="#stdlib-os">os (운영체제 인터페이스)</a></li>
            <li><a href="#stdlib-json">json (JSON 데이터 처리)</a></li>
        </ul>
    </li>
    <li><a href="#python-venv">가상 환경 (Virtual Environments)</a></li>
    <li><a href="#python-next-steps">다음 단계 및 추가 학습</a></li>
  </ul>
</div>

<section id="python-intro">
  <h2>Python 소개 및 시작하기</h2>

  <h3 id="intro-what-is-python">Python이란?</h3>
  <p>Python(파이썬)은 1991년 귀도 반 로섬(Guido van Rossum)이 개발한 인터프리터 방식의 고급 프로그래밍 언어입니다. 배우기 쉽고 문법이 간결하며 가독성이 높아 프로그래밍 입문자에게 매우 적합하며, 동시에 강력한 기능과 풍부한 라이브러리를 바탕으로 다양한 분야에서 전문가들에게도 널리 사용됩니다.</p>
  <p>Python의 디자인 철학은 "The Zen of Python" (<code>import this</code> 실행 시 확인 가능)에 잘 나타나 있으며, 코드의 명료함과 가독성을 중요하게 생각합니다.</p>

  <h3 id="intro-features">특징 및 장점</h3>
  <ul>
    <li><strong>쉬운 문법과 높은 가독성:</strong> 영어와 유사한 자연스러운 문법 구조와 엄격한 들여쓰기 규칙 덕분에 코드를 읽고 이해하기 쉽습니다.</li>
    <li><strong>인터프리터 언어:</strong> 코드를 한 줄씩 해석하고 실행하므로 개발 과정이 빠르고 디버깅이 용이합니다. (컴파일 과정 불필요)</li>
    <li><strong>동적 타이핑 (Dynamic Typing):</strong> 변수를 선언할 때 타입을 명시할 필요가 없어 유연합니다.</li>
    <li><strong>객체 지향 프로그래밍 (OOP) 지원:</strong> 클래스와 객체를 사용하여 효율적이고 구조적인 프로그래밍이 가능합니다.</li>
    <li><strong>강력한 표준 라이브러리:</strong> 운영체제 인터페이스, 네트워크 통신, 데이터 처리 등 다양한 작업을 위한 풍부한 모듈이 기본적으로 제공됩니다.</li>
    <li><strong>방대한 외부 라이브러리 및 커뮤니티:</strong> PyPI(Python Package Index)를 통해 웹 개발, 데이터 과학, 인공 지능, 게임 개발 등 다양한 분야의 수많은 외부 라이브러리를 쉽게 설치하고 사용할 수 있으며, 활발한 커뮤니티의 지원을 받을 수 있습니다.</li>
    <li><strong>높은 확장성 및 이식성:</strong> C/C++ 등으로 작성된 코드를 통합하거나, 다양한 운영체제(Windows, macOS, Linux)에서 동일하게 실행 가능합니다.</li>
  </ul>

  <h3 id="intro-applications">활용 분야</h3>
  <p>Python은 다재다능한 언어로 다양한 분야에서 활용됩니다:</p>
  <ul>
    <li>웹 개발: Django, Flask, FastAPI 등의 프레임워크를 이용한 웹 애플리케이션 및 API 개발.</li>
    <li>데이터 과학 및 분석: NumPy, Pandas, Matplotlib, Scikit-learn 등을 활용한 데이터 처리, 분석, 시각화.</li>
    <li>인공 지능 및 머신러닝: TensorFlow, PyTorch, Keras 등 라이브러리를 이용한 AI 모델 개발 및 학습.</li>
    <li>자동화 및 스크립팅: 시스템 관리, 파일 처리, 웹 크롤링 등 반복적인 작업을 자동화하는 스크립트 작성.</li>
    <li>데스크톱 애플리케이션 개발: PyQt, Tkinter 등을 이용한 GUI 애플리케이션 개발.</li>
    <li>게임 개발: Pygame 등을 이용한 간단한 게임 개발.</li>
    <li>기타: 과학 컴퓨팅, 교육, 네트워크 프로그래밍 등.</li>
  </ul>

  <h3 id="intro-setup">Python 설치 및 환경 설정</h3>
  <p>Python을 사용하려면 먼저 컴퓨터에 Python 인터프리터를 설치해야 합니다.</p>
  <ol>
    <li><strong>Python 다운로드:</strong> <a href="https://www.python.org/downloads/" target="_blank">Python 공식 웹사이트</a>에서 자신의 운영체제(Windows, macOS, Linux)에 맞는 최신 안정 버전을 다운로드합니다. (현재 기준 Python 3.x 버전 사용 권장)</li>
    <li><strong>설치:</strong>
        <ul>
            <li>Windows: 다운로드한 설치 파일을 실행합니다. 설치 과정에서 "Add Python X.X to PATH" 옵션을 반드시 체크해야 명령 프롬프트나 PowerShell에서 `python` 명령어를 쉽게 사용할 수 있습니다. "Customize installation"을 통해 설치 경로 등을 변경할 수 있습니다.</li>
            <li>macOS: 최신 macOS에는 보통 Python 3 버전이 기본적으로 설치되어 있거나, Homebrew (`brew install python3`) 등을 통해 쉽게 설치할 수 있습니다. 공식 웹사이트에서 다운로드한 설치 프로그램을 사용할 수도 있습니다.</li>
            <li>Linux: 대부분의 Linux 배포판에는 Python 3가 기본적으로 설치되어 있습니다. 터미널에서 `python3 --version` 명령어로 확인하고, 설치되지 않았다면 패키지 관리자(예: `sudo apt update && sudo apt install python3` (Debian/Ubuntu), `sudo yum install python3` (CentOS/RHEL))를 사용하여 설치합니다.</li>
        </ul>
    </li>
    <li><strong>설치 확인: 터미널(명령 프롬프트, PowerShell, Terminal)을 열고 다음 명령어를 입력하여 Python 버전이 제대로 출력되는지 확인합니다.
        <pre><code class="language-bash">python --version
# 또는
python3 --version</code></pre>
        <p>(시스템에 따라 `python` 또는 `python3` 명령어를 사용해야 할 수 있습니다.)</p>
        <p>Python과 함께 pip(Python 패키지 관리자)도 보통 자동으로 설치됩니다. 다음 명령어로 pip 버전도 확인해 보세요.</p>
        <pre><code class="language-bash">pip --version
# 또는
pip3 --version</code></pre>
    </li>
  </ol>

  <h3 id="intro-repl">대화형 인터프리터 (REPL) 사용</h3>
  <p>Python 인터프리터는 코드를 한 줄씩 입력하고 즉시 결과를 확인할 수 있는 대화형 모드(REPL: Read-Eval-Print Loop)를 제공합니다. 간단한 코드 테스트나 Python 기능 확인에 유용합니다.</p>
  <p>터미널에서 `python` 또는 `python3` 명령어를 입력하면 REPL이 시작됩니다.</p>
  <pre><code class="language-bash">$ python3
Python 3.10.12 (main, Nov 20 2023, 15:14:05) [GCC 11.4.0] on linux
Type "help", "copyright", "credits" or "license" for more information.
>>> print("Hello, Python!") <span class="py-comment"># 코드 입력 후 Enter</span>
Hello, Python!
>>> 2 + 3
5
>>> message = "Welcome"
>>> message
'Welcome'
>>> exit() <span class="py-comment"># REPL 종료</span></code></pre>

  <h3 id="intro-running-scripts">Python 스크립트 파일 실행 (.py)</h3>
  <p>여러 줄의 Python 코드는 텍스트 에디터를 사용하여 <code>.py</code> 확장자를 가진 파일로 저장합니다. (예: `hello.py`)</p>
  <pre><code class="language-python"><span class="py-comment"># hello.py 파일 내용</span>
<span class="py-keyword">def</span> <span class="py-function">greet</span>(<span class="py-parameter">name</span>):
    <span class="py-builtin">print</span>(<span class="py-string">f"Hello, </span><span class="py-parameter">{name}</span><span class="py-string">!"</span>)

<span class="py-function">greet</span>(<span class="py-string">"World"</span>)
<span class="py-function">greet</span>(<span class="py-string">"Python Learner"</span>)</code></pre>
  <p>터미널에서 해당 파일이 있는 디렉토리로 이동한 후, `python` 또는 `python3` 명령어 뒤에 파일 이름을 입력하여 실행합니다.</p>
  <pre><code class="language-bash">$ python3 hello.py
Hello, World!
Hello, Python Learner!</code></pre>

  <h3 id="intro-editors">코드 에디터 및 IDE</h3>
  <p>간단한 메모장으로도 Python 코드를 작성할 수 있지만, 코드 자동 완성, 구문 강조, 디버깅 등 개발 생산성을 높여주는 전문 코드 에디터나 통합 개발 환경(IDE)을 사용하는 것이 좋습니다.</p>
  <p>인기 있는 도구:</p>
  <ul>
    <li><strong>Visual Studio Code (VS Code):</strong> 가볍고 강력하며 확장 기능이 풍부한 무료 코드 에디터. Python 확장 설치 시 매우 편리한 개발 환경 제공. (강력 추천)</li>
    <li><strong>PyCharm: JetBrains사에서 만든 Python 전용 IDE. 강력한 기능과 디버깅 도구 제공. 무료 Community 버전과 유료 Professional 버전.</li>
    <li><strong>Sublime Text: 가볍고 빠른 코드 에디터.</li>
    <li><strong>Jupyter Notebook/Lab: 데이터 과학 및 분석 분야에서 많이 사용되는 웹 기반 인터랙티브 환경.</li>
    <li>기타: Atom, Vim, Emacs 등</li>
  </ul>
</section>

<section id="python-syntax">
  <h2>기본 문법 및 입출력</h2>

  <h3 id="syntax-indentation">들여쓰기 (Indentation)</h3>
  <p>Python에서 들여쓰기는 단순한 코드 가독성을 위한 스타일 규칙이 아니라, 코드 블록(Code Block)을 구분하는 문법적 요소입니다. <code>if</code>, <code>for</code>, <code>while</code>, <code>def</code>, <code>class</code> 등 콜론(<code>:</code>)으로 끝나는 문장 다음 줄부터 시작되는 코드 블록은 반드시 동일한 수준으로 들여써야 합니다.</p>
  <p>일반적으로 공백 4칸(Space 4)을 사용하는 것이 PEP 8(Python 스타일 가이드) 권장 사항입니다. 탭(Tab) 사용도 가능하지만, 공백과 탭을 혼용해서는 안 됩니다.</p>
  <pre><code class="language-python"><span class="py-keyword">if</span> <span class="py-builtin">True</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"이 코드는 if 블록 안에 있습니다."</span>) <span class="py-comment"># 공백 4칸 들여쓰기</span>
    <span class="py-builtin">print</span>(<span class="py-string">"같은 블록 안의 코드입니다."</span>)     <span class="py-comment"># 동일한 들여쓰기 수준</span>
<span class="py-comment"># print("이 코드는 잘못된 들여쓰기입니다.") # IndentationError 발생</span>

<span class="py-builtin">print</span>(<span class="py-string">"이 코드는 if 블록 밖에 있습니다."</span>) <span class="py-comment"># 들여쓰기 없음</span>

<span class="py-keyword">for</span> i <span class="py-keyword">in</span> <span class="py-builtin">range</span>(<span class="py-number">2</span>):
    <span class="py-builtin">print</span>(<span class="py-string">f"Outer loop: </span><span class="py-parameter">{i}</span><span class="py-string">"</span>)
    <span class="py-keyword">if</span> i <span class="py-operator">==</span> <span class="py-number">0</span>:
        <span class="py-builtin">print</span>(<span class="py-string">"  Inner block starts"</span>) <span class="py-comment"># 더 깊은 들여쓰기</span>
        <span class="py-builtin">print</span>(<span class="py-string">"  Inside inner block"</span>)
    <span class="py-builtin">print</span>(<span class="py-string">"Back to outer loop block"</span>)</code></pre>
  <p class="warning">들여쓰기 오류(IndentationError)는 Python 초보자가 흔히 겪는 오류 중 하나이므로 주의해야 합니다.</p>

  <h3 id="syntax-statements">문장 (Statements)</h3>
  <p>Python 코드는 실행 가능한 개별 명령 단위인 문장(Statement)들로 구성됩니다. 일반적으로 한 줄에 하나의 문장을 작성합니다. 세미콜론(<code>;</code>)은 문장 끝에 필수가 아니며, 한 줄에 여러 문장을 작성할 때 구분자로 사용할 수 있지만 권장되지 않습니다.</p>
  <pre><code class="language-python"><span class="py-comment"># 각 줄이 하나의 문장</span>
<span class="py-parameter">message</span> <span class="py-operator">=</span> <span class="py-string">"Hello"</span>
<span class="py-parameter">count</span> <span class="py-operator">=</span> <span class="py-number">10</span>
<span class="py-builtin">print</span>(<span class="py-parameter">message</span>, <span class="py-parameter">count</span>)

<span class="py-comment"># 한 줄에 여러 문장 (권장하지 않음)</span>
<span class="py-parameter">a</span> <span class="py-operator">=</span> <span class="py-number">1</span>; <span class="py-parameter">b</span> <span class="py-operator">=</span> <span class="py-number">2</span>; <span class="py-builtin">print</span>(<span class="py-parameter">a</span> <span class="py-operator">+</span> <span class="py-parameter">b</span>)</code></pre>

  <h3 id="syntax-comments">주석 (Comments)</h3>
  <p>주석은 코드에 대한 설명을 추가하거나 특정 코드를 임시로 비활성화할 때 사용합니다. Python 인터프리터는 주석을 무시합니다.</p>
  <ul>
    <li>한 줄 주석: <code>#</code> 기호 뒤의 내용은 해당 줄 끝까지 주석 처리됩니다.</li>
    <li>여러 줄 주석: 공식적인 여러 줄 주석 문법은 없지만, 여러 줄 문자열(삼중 따옴표 <code>""" """</code> 또는 <code>''' '''</code>)을 사용하여 유사한 효과를 낼 수 있습니다. (주로 함수나 클래스의 설명을 위한 Docstring으로 사용됨)</li>
  </ul>
   <pre><code class="language-python"><span class="py-comment"># 이것은 한 줄 주석입니다.</span>
<span class="py-parameter">name</span> <span class="py-operator">=</span> <span class="py-string">"Python"</span> <span class="py-comment"># 변수에 문자열 할당</span>

<span class="py-comment"># print("이 코드는 실행되지 않습니다.")</span>

<span class="py-string">"""
이것은 여러 줄 문자열 리터럴입니다.
주석처럼 사용될 수 있지만, 실제로는 문자열 값입니다.
함수나 클래스 정의 바로 아래에 위치하면 Docstring이 됩니다.
"""</span>

<span class="py-builtin">print</span>(<span class="py-string">"주석 밖의 코드는 실행됩니다."</span>)</code></pre>

  <h3 id="syntax-variables">변수와 할당</h3>
  <p>변수(Variable)는 값을 저장하는 메모리 공간을 가리키는 이름입니다. Python에서는 변수를 사용하기 전에 별도의 선언 과정이 필요 없으며, 값을 할당(Assignment)하는 순간 변수가 생성됩니다.</p>
  <p>할당 연산자 <code>=</code> 를 사용하여 변수에 값을 할당합니다.</p>
  <pre><code class="language-python"><span class="py-comment"># 변수 생성 및 값 할당</span>
<span class="py-parameter">message</span> <span class="py-operator">=</span> <span class="py-string">"Hello, Python!"</span>
<span class="py-parameter">counter</span> <span class="py-operator">=</span> <span class="py-number">100</span>
<span class="py-parameter">pi</span> <span class="py-operator">=</span> <span class="py-number">3.14</span>
<span class="py-parameter">is_active</span> <span class="py-operator">=</span> <span class="py-keyword">True</span>

<span class="py-builtin">print</span>(<span class="py-parameter">message</span>)
<span class="py-builtin">print</span>(<span class="py-parameter">counter</span>)

<span class="py-comment"># 변수에 다른 값 재할당 가능 (타입도 변경 가능 - 동적 타이핑)</span>
<span class="py-parameter">counter</span> <span class="py-operator">=</span> <span class="py-string">"이제 문자열 타입"</span>
<span class="py-builtin">print</span>(<span class="py-parameter">counter</span>)

<span class="py-comment"># 다중 할당</span>
<span class="py-parameter">x</span>, <span class="py-parameter">y</span>, <span class="py-parameter">z</span> <span class="py-operator">=</span> <span class="py-number">10</span>, <span class="py-number">20</span>, <span class="py-string">"thirty"</span>
<span class="py-builtin">print</span>(<span class="py-parameter">x</span>, <span class="py-parameter">y</span>, <span class="py-parameter">z</span>)</code></pre>

  <h3 id="syntax-naming">식별자 규칙 및 Naming Conventions</h3>
  <p>변수, 함수, 클래스 등의 이름을 식별자(Identifier)라고 합니다.</p>
  <p><strong>규칙:</strong></p>
  <ul>
    <li>영문자(<code>a-z</code>, <code>A-Z</code>), 숫자(<code>0-9</code>), 밑줄(<code>_</code>)로 구성됩니다.</li>
    <li>숫자로 시작할 수 없습니다.</li>
    <li>대소문자를 구분합니다 (<code>myVar</code>와 <code>myvar</code>는 다름).</li>
    <li>Python 키워드(예: <code>if</code>, <code>for</code>, <code>def</code>, <code>class</code> 등)는 식별자로 사용할 수 없습니다.</li>
  </ul>
  <p><strong>Naming Conventions (일반적인 약속):</strong></p>
  <ul>
    <li>변수, 함수, 메서드 이름: 소문자와 밑줄을 사용하는 snake_case를 권장합니다. (예: <code>user_name</code>, <code>calculate_area</code>)</li>
    <li>클래스 이름: 각 단어의 첫 글자를 대문자로 하는 CapWords (또는 PascalCase)를 사용합니다. (예: <code>MyClass</code>, <code>UserProfile</code>)</li>
    <li>상수 이름: 모든 글자를 대문자로 하고 단어 사이는 밑줄로 구분하는 ALL_CAPS를 사용합니다. (예: <code>MAX_VALUE</code>, <code>PI</code>)</li>
    <li>밑줄 하나로 시작하는 이름 (예: <code>_internal_var</code>): 내부적으로 사용됨을 암시 (강제성은 없음).</li>
    <li>밑줄 두 개로 시작하는 이름 (예: <code>__private_var</code>): 이름 맹글링(name mangling)으로 클래스 외부에서 직접 접근하기 어렵게 만듦 (완전한 비공개는 아님).</li>
    <li>밑줄 두 개로 시작하고 끝나는 이름 (예: <code>__init__</code>, <code>__str__</code>): Python의 특별한 용도로 예약된 이름 (Special methods 또는 Dunder methods).</li>
  </ul>

  <h3 id="syntax-io">기본 입출력 (print(), input())</h3>
  <ul>
    <li><strong>출력: <code>print()</code> 함수</strong>
      <p>값을 화면(표준 출력)에 표시합니다. 여러 개의 값을 쉼표(<code>,</code>)로 구분하여 전달하면 공백으로 구분되어 출력됩니다.</p>
      <pre><code class="language-python"><span class="py-builtin">print</span>(<span class="py-string">"Hello, World!"</span>)
<span class="py-parameter">name</span> <span class="py-operator">=</span> <span class="py-string">"Alice"</span>
<span class="py-parameter">age</span> <span class="py-operator">=</span> <span class="py-number">30</span>
<span class="py-builtin">print</span>(<span class="py-string">"이름:"</span>, <span class="py-parameter">name</span>, <span class="py-string">"나이:"</span>, <span class="py-parameter">age</span>) <span class="py-comment"># 출력: 이름: Alice 나이: 30</span>

<span class="py-comment"># 출력 옵션: sep (구분자), end (끝 문자)</span>
<span class="py-builtin">print</span>(<span class="py-number">1</span>, <span class="py-number">2</span>, <span class="py-number">3</span>, <span class="py-parameter">sep</span><span class="py-operator">=</span><span class="py-string">", "</span>) <span class="py-comment"># 출력: 1, 2, 3</span>
<span class="py-builtin">print</span>(<span class="py-string">"첫 번째 줄"</span>, <span class="py-parameter">end</span><span class="py-operator">=</span><span class="py-string">" "</span>) <span class="py-comment"># 기본값 '\n'(줄바꿈) 대신 공백 사용</span>
<span class="py-builtin">print</span>(<span class="py-string">"두 번째 줄"</span>)

<span class="py-comment"># f-string (Formatted String Literals - Python 3.6+) 사용 권장</span>
<span class="py-builtin">print</span>(<span class="py-string">f"이름: {name}, 나이: {age}"</span>) <span class="py-comment"># 출력: 이름: Alice, 나이: 30</span></code></pre>
    </li>
    <li><strong>입력: <code>input()</code> 함수</strong>
        <p>사용자로부터 키보드 입력을 받습니다. 입력받은 값은 항상 문자열(str) 타입으로 반환됩니다.</p>
        <pre><code class="language-python"><span class="py-parameter">user_name</span> <span class="py-operator">=</span> <span class="py-builtin">input</span>(<span class="py-string">"이름을 입력하세요: "</span>) <span class="py-comment"># 프롬프트 메시지 출력</span>
<span class="py-builtin">print</span>(<span class="py-string">f"입력된 이름: {user_name}"</span>)

<span class="py-parameter">user_age_str</span> <span class="py-operator">=</span> <span class="py-builtin">input</span>(<span class="py-string">"나이를 입력하세요: "</span>)
<span class="py-comment"># input() 결과는 문자열이므로 숫자 계산을 위해서는 형 변환 필요</span>
<span class="py-keyword">try</span>:
    <span class="py-parameter">user_age_int</span> <span class="py-operator">=</span> <span class="py-builtin">int</span>(<span class="py-parameter">user_age_str</span>)
    <span class="py-builtin">print</span>(<span class="py-string">f"10년 후 나이: {user_age_int + 10}"</span>)
<span class="py-keyword">except</span> <span class="py-builtin">ValueError</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"잘못된 나이 형식입니다."</span>)</code></pre>
    </li>
  </ul>
</section>

<section id="python-data-types-intro">
    <h2>데이터 타입 (Data Types)</h2>
    <h3 id="types-overview">데이터 타입 개요 및 동적 타이핑</h3>
    <p>데이터 타입은 변수가 저장할 수 있는 값의 종류(예: 숫자, 문자열, 불리언 등)를 나타냅니다. Python은 동적 타이핑(Dynamic Typing) 언어로, 변수를 선언할 때 타입을 미리 지정할 필요가 없으며, 변수에 값이 할당될 때 인터프리터가 자동으로 타입을 결정합니다. 또한, 변수는 실행 중에 다른 타입의 값을 가질 수도 있습니다.</p>
    <p>Python의 주요 내장 데이터 타입은 다음과 같습니다:</p>
    <ul>
        <li>숫자형 (Numeric Types): <code>int</code> (정수), <code>float</code> (부동소수점 수), <code>complex</code> (복소수)</li>
        <li>불리언 (Boolean Type): <code>bool</code> (<code>True</code> 또는 <code>False</code>)</li>
        <li>None 타입: <code>NoneType</code> (값이 없음을 나타내는 <code>None</code>)</li>
        <li>시퀀스 타입 (Sequence Types): 순서가 있는 값들의 집합. <code>str</code> (문자열), <code>list</code> (리스트), <code>tuple</code> (튜플)</li>
        <li>매핑 타입 (Mapping Type): 키-값 쌍의 집합. <code>dict</code> (딕셔너리)</li>
        <li>세트 타입 (Set Types): 순서 없고 중복 없는 값들의 집합. <code>set</code>, <code>frozenset</code></li>
        <li>기타: 바이트 시퀀스(<code>bytes</code>, <code>bytearray</code>), 메모리 뷰(<code>memoryview</code>) 등</li>
    </ul>
    <p><code>type()</code> 내장 함수를 사용하여 변수의 현재 데이터 타입을 확인할 수 있습니다.</p>
    <pre><code class="language-python"><span class="py-parameter">a</span> <span class="py-operator">=</span> <span class="py-number">10</span>       <span class="py-comment"># int</span>
<span class="py-parameter">b</span> <span class="py-operator">=</span> <span class="py-number">3.14</span>     <span class="py-comment"># float</span>
<span class="py-parameter">c</span> <span class="py-operator">=</span> <span class="py-string">"Python"</span> <span class="py-comment"># str</span>
<span class="py-parameter">d</span> <span class="py-operator">=</span> <span class="py-keyword">True</span>     <span class="py-comment"># bool</span>
<span class="py-parameter">e</span> <span class="py-operator">=</span> [<span class="py-number">1</span>, <span class="py-number">2</span>, <span class="py-number">3</span>]  <span class="py-comment"># list</span>
<span class="py-parameter">f</span> <span class="py-operator">=</span> {<span class="py-string">'key'</span>: <span class="py-string">'value'</span>} <span class="py-comment"># dict</span>
<span class="py-parameter">g</span> <span class="py-operator">=</span> <span class="py-keyword">None</span>     <span class="py-comment"># NoneType</span>

<span class="py-builtin">print</span>(<span class="py-builtin">type</span>(<span class="py-parameter">a</span>))  <span class="py-comment"># &lt;class 'int'&gt;</span>
<span class="py-builtin">print</span>(<span class="py-builtin">type</span>(<span class="py-parameter">c</span>))  <span class="py-comment"># &lt;class 'str'&gt;</span>
<span class="py-builtin">print</span>(<span class="py-builtin">type</span>(<span class="py-parameter">f</span>))  <span class="py-comment"># &lt;class 'dict'&gt;</span>

<span class="py-parameter">a</span> <span class="py-operator">=</span> <span class="py-string">"Changed"</span> <span class="py-comment"># 이제 a는 str 타입</span>
<span class="py-builtin">print</span>(<span class="py-builtin">type</span>(<span class="py-parameter">a</span>))  <span class="py-comment"># &lt;class 'str'&gt;</span></code></pre>
</section>

<section id="types-numeric">
    <h3>숫자형 (int, float, complex)</h3>
    <ul>
        <li><strong><code>int</code> (정수):</strong> 소수점 없는 숫자를 나타냅니다. Python 3에서는 정수의 크기 제한이 사실상 없습니다 (메모리가 허용하는 한).</li>
        <li><strong><code>float</code> (부동소수점 수):</strong> 소수점이나 지수(e 또는 E)를 포함하는 숫자를 나타냅니다. 컴퓨터에서 실수를 표현하는 방식 때문에 약간의 오차가 발생할 수 있습니다.</li>
        <li><strong><code>complex</code> (복소수):</strong> 실수부와 허수부(<code>j</code> 또는 <code>J</code> 접미사 사용)를 가지는 숫자를 나타냅니다. (일반적인 프로그래밍에서는 자주 사용되지 않음)</li>
    </ul>
     <pre><code class="language-python"><span class="py-parameter">int_num</span> <span class="py-operator">=</span> <span class="py-number">123</span>
<span class="py-parameter">large_int</span> <span class="py-operator">=</span> <span class="py-number">98765432109876543210</span>
<span class="py-parameter">float_num</span> <span class="py-operator">=</span> <span class="py-number">3.14</span>
<span class="py-parameter">exp_float</span> <span class="py-operator">=</span> <span class="py-number">2.5e4</span>  <span class="py-comment"># 2.5 * 10^4 = 25000.0</span>
<span class="py-parameter">complex_num</span> <span class="py-operator">=</span> <span class="py-number">2</span> <span class="py-operator">+</span> <span class="py-number">3j</span>

<span class="py-builtin">print</span>(<span class="py-string">f"Int: {int_num}, Type: {type(int_num)}"</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"Float: {float_num}, Type: {type(float_num)}"</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"Complex: {complex_num}, Type: {type(complex_num)}"</span>)

<span class="py-comment"># 부동소수점 오차 예시</span>
<span class="py-builtin">print</span>(<span class="py-number">0.1</span> <span class="py-operator">+</span> <span class="py-number">0.2</span>) <span class="py-comment"># 0.30000000000000004 (정확히 0.3이 아닐 수 있음)</span>
<span class="py-comment"># 정확한 계산이 필요하면 decimal 모듈 사용 고려</span>
</code></pre>
</section>

<section id="types-boolean">
    <h3>불리언 (bool)</h3>
    <p>논리적인 참(True) 또는 거짓(False)을 나타내는 데이터 타입입니다. 비교 연산이나 조건문에서 주로 사용됩니다.</p>
    <p class="note">Python에서 <code>True</code>와 <code>False</code>는 첫 글자가 대문자임에 유의해야 합니다.</p>
    <p>다양한 값들이 불리언 컨텍스트(예: <code>if</code> 문)에서 평가될 때 참 또는 거짓으로 간주됩니다:</p>
    <ul>
        <li>거짓(False)으로 간주되는 값 (Falsy):
            <ul>
                <li><code>False</code></li>
                <li>숫자 <code>0</code> (정수 0, 실수 0.0)</li>
                <li>빈 시퀀스 또는 컬렉션: <code>""</code> (빈 문자열), <code>[]</code> (빈 리스트), <code>()</code> (빈 튜플), <code>{}</code> (빈 딕셔너리), <code>set()</code> (빈 세트)</li>
                <li><code>None</code></li>
            </ul>
        </li>
        <li>참(True)으로 간주되는 값 (Truthy): 위 Falsy 값을 제외한 모든 값.</li>
    </ul>
     <pre><code class="language-python"><span class="py-parameter">is_python_fun</span> <span class="py-operator">=</span> <span class="py-keyword">True</span>
<span class="py-parameter">is_learning_done</span> <span class="py-operator">=</span> <span class="py-keyword">False</span>

<span class="py-builtin">print</span>(<span class="py-parameter">is_python_fun</span>)  <span class="py-comment"># True</span>
<span class="py-builtin">print</span>(<span class="py-builtin">type</span>(<span class="py-parameter">is_python_fun</span>)) <span class="py-comment"># &lt;class 'bool'&gt;</span>

<span class="py-comment"># Truthy/Falsy 예시</span>
<span class="py-keyword">if</span> <span class="py-number">0</span>: <span class="py-comment"># 0은 Falsy</span>
    <span class="py-builtin">print</span>(<span class="py-string">"0 is True"</span>)
<span class="py-keyword">else</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"0 is False"</span>) <span class="py-comment"># 실행됨</span>

<span class="py-keyword">if</span> [<span class="py-number">1</span>]: <span class="py-comment"># 비어있지 않은 리스트는 Truthy</span>
    <span class="py-builtin">print</span>(<span class="py-string">"[1] is True"</span>) <span class="py-comment"># 실행됨</span>
<span class="py-keyword">else</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"[1] is False"</span>)

<span class="py-builtin">print</span>(<span class="py-builtin">bool</span>(<span class="py-number">0</span>))   <span class="py-comment"># False</span>
<span class="py-builtin">print</span>(<span class="py-builtin">bool</span>(<span class="py-string">""</span>))  <span class="py-comment"># False</span>
<span class="py-builtin">print</span>(<span class="py-builtin">bool</span>(<span class="py-keyword">None</span>)) <span class="py-comment"># False</span>
<span class="py-builtin">print</span>(<span class="py-builtin">bool</span>(<span class="py-number">10</span>))  <span class="py-comment"># True</span>
<span class="py-builtin">print</span>(<span class="py-builtin">bool</span>(<span class="py-string">"Hi"</span>)) <span class="py-comment"># True</span>
</code></pre>
</section>

<section id="types-none">
    <h3>None 타입</h3>
    <p><code>None</code>은 값이 없거나 존재하지 않음을 나타내는 특별한 값입니다. <code>NoneType</code>이라는 자체 타입을 가집니다.</p>
    <p>함수가 명시적으로 값을 반환하지 않거나, 변수를 초기화할 때 값이 아직 정해지지 않았음을 나타내기 위해 사용됩니다.</p>
    <pre><code class="language-python"><span class="py-parameter">no_value</span> <span class="py-operator">=</span> <span class="py-keyword">None</span>
<span class="py-builtin">print</span>(<span class="py-parameter">no_value</span>)        <span class="py-comment"># None</span>
<span class="py-builtin">print</span>(<span class="py-builtin">type</span>(<span class="py-parameter">no_value</span>))   <span class="py-comment"># &lt;class 'NoneType'&gt;</span>

<span class="py-keyword">def</span> <span class="py-function">my_function</span>():
    <span class="py-comment"># 아무것도 return 하지 않음</span>
    <span class="py-keyword">pass</span>

<span class="py-parameter">result</span> <span class="py-operator">=</span> <span class="py-function">my_function</span>()
<span class="py-builtin">print</span>(<span class="py-parameter">result</span>)        <span class="py-comment"># None</span>

<span class="py-comment"># None 비교 시에는 'is' 연산자 사용 권장</span>
<span class="py-keyword">if</span> <span class="py-parameter">no_value</span> <span class="py-keyword">is</span> <span class="py-keyword">None</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"변수에 값이 없습니다."</span>)</code></pre>
</section>

<section id="python-operators">
    <h2>연산자 (Operators)</h2>
    <p>연산자는 값(피연산자)에 대해 특정 연산을 수행하도록 하는 기호입니다. Python은 다양한 종류의 연산자를 제공합니다.</p>

    <h3 id="operators-arithmetic">산술 연산자</h3>
    <p>수학적 계산을 수행합니다.</p>
    <ul>
        <li><code>+</code> (덧셈), <code>-</code> (뺄셈), <code>*</code> (곱셈)</li>
        <li><code>/</code> (나눗셈): 결과를 항상 float으로 반환.</li>
        <li><code>%</code> (나머지)</li>
        <li><code></code> (거듭제곱)</li>
        <li><code>//</code> (몫 나눗셈 / 정수 나눗셈): 나눗셈 결과에서 소수점 이하를 버리고 정수 부분만 반환.</li>
    </ul>
     <pre><code class="language-python"><span class="py-parameter">a</span> <span class="py-operator">=</span> <span class="py-number">10</span>
<span class="py-parameter">b</span> <span class="py-operator">=</span> <span class="py-number">3</span>

<span class="py-builtin">print</span>(<span class="py-string">f"</span><span class="py-parameter">{a}</span><span class="py-string"> + </span><span class="py-parameter">{b}</span><span class="py-string"> = {a + b}"</span>)     <span class="py-comment"># 13</span>
<span class="py-builtin">print</span>(<span class="py-string">f"</span><span class="py-parameter">{a}</span><span class="py-string"> - </span><span class="py-parameter">{b}</span><span class="py-string"> = {a - b}"</span>)     <span class="py-comment"># 7</span>
<span class="py-builtin">print</span>(<span class="py-string">f"</span><span class="py-parameter">{a}</span><span class="py-string"> * </span><span class="py-parameter">{b}</span><span class="py-string"> = {a * b}"</span>)     <span class="py-comment"># 30</span>
<span class="py-builtin">print</span>(<span class="py-string">f"</span><span class="py-parameter">{a}</span><span class="py-string"> / </span><span class="py-parameter">{b}</span><span class="py-string"> = {a / b}"</span>)     <span class="py-comment"># 3.333... (float)</span>
<span class="py-builtin">print</span>(<span class="py-string">f"</span><span class="py-parameter">{a}</span><span class="py-string"> % </span><span class="py-parameter">{b}</span><span class="py-string"> = {a % b}"</span>)     <span class="py-comment"># 1</span>
<span class="py-builtin">print</span>(<span class="py-string">f"</span><span class="py-parameter">{a}</span><span class="py-string">  </span><span class="py-parameter">{b}</span><span class="py-string"> = {a  b}"</span>) <span class="py-comment"># 1000 (10의 3제곱)</span>
<span class="py-builtin">print</span>(<span class="py-string">f"</span><span class="py-parameter">{a}</span><span class="py-string"> // </span><span class="py-parameter">{b}</span><span class="py-string"> = {a // b}"</span>) <span class="py-comment"># 3 (몫)</span></code></pre>

    <h3 id="operators-assignment">할당 연산자</h3>
    <p>변수에 값을 할당합니다.</p>
    <ul>
        <li><code>=</code>: 기본 할당.</li>
        <li><code>+=</code>, <code>-=</code>, <code>*=</code>, <code>/=</code>, <code>%=</code>, <code>=</code>, <code>//=</code>: 산술 연산 후 할당 (복합 할당 연산자). 예: <code>x += 5</code>는 <code>x = x + 5</code>와 동일.</li>
    </ul>
     <pre><code class="language-python"><span class="py-parameter">count</span> <span class="py-operator">=</span> <span class="py-number">0</span>
<span class="py-parameter">count</span> <span class="py-operator">+=</span> <span class="py-number">1</span>  <span class="py-comment"># count = count + 1</span>
<span class="py-builtin">print</span>(<span class="py-parameter">count</span>) <span class="py-comment"># 1</span>

<span class="py-parameter">total</span> <span class="py-operator">=</span> <span class="py-number">100</span>
<span class="py-parameter">total</span> <span class="py-operator">//=</span> <span class="py-number">6</span> <span class="py-comment"># total = total // 6</span>
<span class="py-builtin">print</span>(<span class="py-parameter">total</span>) <span class="py-comment"># 16</span></code></pre>

    <h3 id="operators-comparison">비교 연산자</h3>
    <p>두 값을 비교하여 <code>True</code> 또는 <code>False</code>를 반환합니다.</p>
    <ul>
        <li><code>==</code> (같음)</li>
        <li><code>!=</code> (다름)</li>
        <li><code>&gt;</code> (큼)</li>
        <li><code>&lt;</code> (작음)</li>
        <li><code>&gt;=</code> (크거나 같음)</li>
        <li><code>&lt;=</code> (작거나 같음)</li>
    </ul>
     <pre><code class="language-python"><span class="py-parameter">x</span> <span class="py-operator">=</span> <span class="py-number">10</span>
<span class="py-parameter">y</span> <span class="py-operator">=</span> <span class="py-number">5</span>
<span class="py-parameter">z</span> <span class="py-operator">=</span> <span class="py-number">10</span>

<span class="py-builtin">print</span>(<span class="py-parameter">x</span> <span class="py-operator">==</span> <span class="py-parameter">y</span>)  <span class="py-comment"># False</span>
<span class="py-builtin">print</span>(<span class="py-parameter">x</span> <span class="py-operator">==</span> <span class="py-parameter">z</span>)  <span class="py-comment"># True</span>
<span class="py-builtin">print</span>(<span class="py-parameter">x</span> <span class="py-operator">!=</span> <span class="py-parameter">y</span>)  <span class="py-comment"># True</span>
<span class="py-builtin">print</span>(<span class="py-parameter">x</span> <span class="py-operator">&gt;</span> <span class="py-parameter">y</span>)   <span class="py-comment"># True</span>
<span class="py-builtin">print</span>(<span class="py-parameter">y</span> <span class="py-operator">&lt;=</span> <span class="py-parameter">z</span>) <span class="py-comment"># True</span>

<span class="py-comment"># 문자열 비교 (사전 순서)</span>
<span class="py-builtin">print</span>(<span class="py-string">'apple'</span> <span class="py-operator">&lt;</span> <span class="py-string">'banana'</span>) <span class="py-comment"># True</span>
</code></pre>
    <p class="note">Python의 <code>==</code>는 값만 비교합니다. JavaScript의 <code>===</code>와 유사하게 동작합니다.</p>

    <h3 id="operators-logical">논리 연산자</h3>
    <p>불리언 값을 조합하여 논리적인 결과를 반환합니다.</p>
    <ul>
        <li><code>and</code>: 두 피연산자 모두 <code>True</code>일 때 <code>True</code> 반환 (단축 평가).</li>
        <li><code>or</code>: 두 피연산자 중 하나라도 <code>True</code>일 때 <code>True</code> 반환 (단축 평가).</li>
        <li><code>not</code>: 피연산자의 불리언 값을 반전시킴.</li>
    </ul>
    <p><strong>단축 평가 (Short-circuit Evaluation):</strong><br>
    <code>and</code> 연산은 왼쪽이 <code>False</code>이면 오른쪽을 평가하지 않고 <code>False</code>(정확히는 왼쪽 값)를 반환합니다.<br>
    <code>or</code> 연산은 왼쪽이 <code>True</code>이면 오른쪽을 평가하지 않고 <code>True</code>(정확히는 왼쪽 값)를 반환합니다.</p>
     <pre><code class="language-python"><span class="py-parameter">is_logged_in</span> <span class="py-operator">=</span> <span class="py-keyword">True</span>
<span class="py-parameter">has_permission</span> <span class="py-operator">=</span> <span class="py-keyword">False</span>

<span class="py-builtin">print</span>(<span class="py-parameter">is_logged_in</span> <span class="py-keyword">and</span> <span class="py-parameter">has_permission</span>) <span class="py-comment"># False</span>
<span class="py-builtin">print</span>(<span class="py-parameter">is_logged_in</span> <span class="py-keyword">or</span> <span class="py-parameter">has_permission</span>)  <span class="py-comment"># True</span>
<span class="py-builtin">print</span>(<span class="py-keyword">not</span> <span class="py-parameter">has_permission</span>)             <span class="py-comment"># True</span>

<span class="py-comment"># 단축 평가 예시</span>
<span class="py-parameter">x</span> <span class="py-operator">=</span> <span class="py-number">5</span>
<span class="py-parameter">y</span> <span class="py-operator">=</span> <span class="py-number">0</span>

<span class="py-comment"># y가 0이 아니어야 나눗셈 가능</span>
<span class="py-parameter">result</span> <span class="py-operator">=</span> <span class="py-parameter">y</span> <span class="py-operator">!=</span> <span class="py-number">0</span> <span class="py-keyword">and</span> <span class="py-parameter">x</span> / <span class="py-parameter">y</span> <span class="py-operator">&gt;</span> <span class="py-number">1</span> <span class="py-comment"># y != 0이 False이므로 뒤의 나눗셈은 실행되지 않음 (ZeroDivisionError 방지)</span>
<span class="py-builtin">print</span>(<span class="py-parameter">result</span>) <span class="py-comment"># False</span>

<span class="py-parameter">name</span> <span class="py-operator">=</span> <span class="py-string">"Alice"</span>
<span class="py-parameter">user_name</span> <span class="py-operator">=</span> <span class="py-parameter">name</span> <span class="py-keyword">or</span> <span class="py-string">"Guest"</span> <span class="py-comment"># name이 Truthy하므로 name("Alice") 반환</span>
<span class="py-builtin">print</span>(<span class="py-parameter">user_name</span>) <span class="py-comment"># Alice</span>

<span class="py-parameter">name</span> <span class="py-operator">=</span> <span class="py-string">""</span> <span class="py-comment"># Falsy</span>
<span class="py-parameter">user_name</span> <span class="py-operator">=</span> <span class="py-parameter">name</span> <span class="py-keyword">or</span> <span class="py-string">"Guest"</span> <span class="py-comment"># name이 Falsy하므로 "Guest" 반환</span>
<span class="py-builtin">print</span>(<span class="py-parameter">user_name</span>) <span class="py-comment"># Guest</span>
</code></pre>
</section>


<br><br>
<hr>

<section id="types-sequence">
    <h2>시퀀스 타입 (Sequence Types)</h2>
    <p>시퀀스는 데이터에 순서(번호)를 붙여 나열한 것으로, 파이썬의 중요한 데이터 구조입니다. 주요 시퀀스 타입으로는 문자열(str), 리스트(list), 튜플(tuple)이 있습니다.</p>

    <h3 id="types-string">문자열 (str)</h3>
    <p>문자열은 텍스트 데이터를 나타냅니다. 작은따옴표(<code>'</code>), 큰따옴표(<code>"</code>), 또는 삼중 따옴표(<code>'''</code> 또는 <code>"""</code> - 여러 줄 문자열 정의 시 유용)로 감싸서 생성합니다.</p>
    <p>주요 특징:</p>
    <ul>
        <li><strong>순서(Sequence):</strong> 문자들은 정해진 순서를 가지며, 인덱스(Index)를 통해 각 문자에 접근할 수 있습니다 (0부터 시작).</li>
        <li><strong>불변성(Immutable):</strong> 생성된 후에는 문자열 자체의 내용을 변경할 수 없습니다. 변경이 필요하면 새로운 문자열을 만들어야 합니다.</li>
        <li><strong>인덱싱(Indexing):</strong> <code>string[index]</code> 형태로 특정 위치의 문자에 접근합니다. 음수 인덱스(<code>-1</code>은 마지막 문자)도 사용 가능합니다.</li>
        <li><strong>슬라이싱(Slicing):</strong> <code>string[start:stop:step]</code> 형태로 문자열의 일부(부분 문자열)를 추출합니다. <code>start</code>는 포함, <code>stop</code>은 미포함됩니다. <code>step</code>은 간격 (기본값 1).</li>
    </ul>
    <pre><code class="language-python"><span class="py-parameter">s1</span> <span class="py-operator">=</span> <span class="py-string">'Hello'</span>
<span class="py-parameter">s2</span> <span class="py-operator">=</span> <span class="py-string">"Python"</span>
<span class="py-parameter">s3</span> <span class="py-operator">=</span> <span class="py-string">"""이것은
여러 줄에 걸친
문자열입니다."""</span>

<span class="py-builtin">print</span>(<span class="py-parameter">s1</span>)
<span class="py-builtin">print</span>(<span class="py-parameter">s3</span>)

<span class="py-comment"># 인덱싱</span>
<span class="py-builtin">print</span>(<span class="py-parameter">s2</span>[<span class="py-number">0</span>])  <span class="py-comment"># P</span>
<span class="py-builtin">print</span>(<span class="py-parameter">s2</span>[<span class="py-number">2</span>])  <span class="py-comment"># t</span>
<span class="py-builtin">print</span>(<span class="py-parameter">s2</span>[<span class="py-operator">-</span><span class="py-number">1</span>]) <span class="py-comment"># n (마지막 문자)</span>

<span class="py-comment"># 슬라이싱</span>
<span class="py-builtin">print</span>(<span class="py-parameter">s2</span>[<span class="py-number">1</span>:<span class="py-number">4</span>]) <span class="py-comment"># yth (인덱스 1부터 4 전까지)</span>
<span class="py-builtin">print</span>(<span class="py-parameter">s2</span>[:<span class="py-number">3</span>])  <span class="py-comment"># Pyt (처음부터 3 전까지)</span>
<span class="py-builtin">print</span>(<span class="py-parameter">s2</span>[<span class="py-number">2</span>:])  <span class="py-comment"># thon (인덱스 2부터 끝까지)</span>
<span class="py-builtin">print</span>(<span class="py-parameter">s2</span>[::<span class="py-number">2</span>]) <span class="py-comment"># Pto (처음부터 끝까지 2칸 간격)</span>
<span class="py-builtin">print</span>(<span class="py-parameter">s2</span>[:: <span class="py-operator">-</span><span class="py-number">1</span>])<span class="py-comment"># nohtyP (문자열 뒤집기)</span>

<span class="py-comment"># 불변성 확인</span>
<span class="py-comment"># s1[0] = 'J' # TypeError: 'str' object does not support item assignment</span>
<span class="py-parameter">s1</span> <span class="py-operator">=</span> <span class="py-string">'J'</span> <span class="py-operator">+</span> <span class="py-parameter">s1</span>[<span class="py-number">1</span>:] <span class="py-comment"># 새로운 문자열 'Jello' 생성</span>
<span class="py-builtin">print</span>(<span class="py-parameter">s1</span>) <span class="py-comment"># Jello</span></code></pre>

    <h4>문자열 연산 및 메서드</h4>
    <ul>
        <li><code>+</code> (연결), <code>*</code> (반복)</li>
        <li><code>len(string)</code>: 문자열 길이 반환.</li>
        <li><code>.upper()</code> / <code>.lower()</code>: 대/소문자로 변환된 새 문자열 반환.</li>
        <li><code>.strip()</code> / <code>.lstrip()</code> / <code>.rstrip()</code>: 양쪽/왼쪽/오른쪽의 공백(또는 지정된 문자) 제거한 새 문자열 반환.</li>
        <li><code>.split([sep])</code>: 문자열을 구분자(<code>sep</code>, 기본값 공백) 기준으로 나누어 리스트로 반환.</li>
        <li><code>separator.join(iterable)</code>: iterable(리스트 등)의 문자열 요소들을 <code>separator</code> 문자열로 이어붙여 새 문자열 반환.</li>
        <li><code>.replace(old, new, [count])</code>: 문자열 내의 <code>old</code> 부분을 <code>new</code>로 치환한 새 문자열 반환. <code>count</code> 지정 시 최대 횟수만큼 치환.</li>
        <li><code>.find(sub, [start], [end])</code>: 부분 문자열 <code>sub</code>을 찾아 시작 인덱스를 반환. 없으면 -1 반환.</li>
        <li><code>.index(sub, [start], [end])</code>: <code>.find()</code>와 유사하나, 없으면 ValueError 발생.</li>
        <li><code>.startswith(prefix)</code> / <code>.endswith(suffix)</code>: 문자열이 특정 접두사/접미사로 시작/끝나는지 확인 (True/False).</li>
        <li><code>.count(sub)</code>: 부분 문자열 <code>sub</code>이 등장하는 횟수 반환.</li>
        <li><code>.isdigit()</code> / <code>.isalpha()</code> / <code>.isalnum()</code>: 문자열이 숫자/알파벳/알파벳 또는 숫자로만 구성되었는지 확인.</li>
    </ul>
     <pre><code class="language-python"><span class="py-parameter">greeting</span> <span class="py-operator">=</span> <span class="py-string">" Hello, World! "</span>
<span class="py-builtin">print</span>(<span class="py-string">"원본:"</span>, <span class="py-parameter">greeting</span>)
<span class="py-builtin">print</span>(<span class="py-string">"길이:"</span>, <span class="py-builtin">len</span>(<span class="py-parameter">greeting</span>))
<span class="py-builtin">print</span>(<span class="py-string">"대문자:"</span>, <span class="py-parameter">greeting</span>.upper())
<span class="py-builtin">print</span>(<span class="py-string">"소문자:"</span>, <span class="py-parameter">greeting</span>.lower())
<span class="py-builtin">print</span>(<span class="py-string">"공백 제거:"</span>, <span class="py-parameter">greeting</span>.strip())
<span class="py-builtin">print</span>(<span class="py-string">"분리:"</span>, <span class="py-parameter">greeting</span>.strip().split(<span class="py-string">','</span>)) <span class="py-comment"># ['Hello', ' World!']</span>
<span class="py-builtin">print</span>(<span class="py-string">"치환:"</span>, <span class="py-parameter">greeting</span>.replace(<span class="py-string">'World'</span>, <span class="py-string">'Python'</span>))
<span class="py-builtin">print</span>(<span class="py-string">"찾기 ('o'):"</span>, <span class="py-parameter">greeting</span>.find(<span class="py-string">'o'</span>)) <span class="py-comment"># 5 (첫 번째 'o')</span>
<span class="py-builtin">print</span>(<span class="py-string">"시작 확인 (' H'):"</span>, <span class="py-parameter">greeting</span>.startswith(<span class="py-string">' H'</span>)) <span class="py-comment"># True</span>

<span class="py-parameter">words</span> <span class="py-operator">=</span> [<span class="py-string">'Python'</span>, <span class="py-string">'is'</span>, <span class="py-string">'easy'</span>]
<span class="py-builtin">print</span>(<span class="py-string">"결합:"</span>, <span class="py-string">' '</span>.join(<span class="py-parameter">words</span>)) <span class="py-comment"># Python is easy</span></code></pre>

    <h4>f-string (Formatted String Literals - Python 3.6+)</h4>
    <p>문자열 앞에 <code>f</code> 또는 <code>F</code>를 붙이고, 중괄호(<code>{}</code>) 안에 변수나 표현식을 넣어 값을 문자열에 포함시키는 가장 현대적이고 편리한 방식입니다.</p>
    <pre><code class="language-python"><span class="py-parameter">name</span> <span class="py-operator">=</span> <span class="py-string">"Alice"</span>
<span class="py-parameter">age</span> <span class="py-operator">=</span> <span class="py-number">30</span>
<span class="py-parameter">pi</span> <span class="py-operator">=</span> <span class="py-number">3.141592</span>

<span class="py-comment"># 변수 삽입</span>
<span class="py-builtin">print</span>(<span class="py-string">f"이름: {name}, 나이: {age}"</span>)

<span class="py-comment"># 표현식 삽입</span>
<span class="py-builtin">print</span>(<span class="py-string">f"나이의 두 배: {age * 2}"</span>)

<span class="py-comment"># 포맷 지정 (소수점 자릿수, 정렬 등)</span>
<span class="py-builtin">print</span>(<span class="py-string">f"원주율 (소수점 2자리): {pi:.2f}"</span>) <span class="py-comment"># 3.14</span>
<span class="py-builtin">print</span>(<span class="py-string">f"숫자 채우기: {age:05d}"</span>) <span class="py-comment"># 00030</span>
<span class="py-builtin">print</span>(<span class="py-string">f"'{name:&gt;10}'"</span>) <span class="py-comment"># '     Alice' (오른쪽 정렬, 10칸)</span></code></pre>
    <p>(이전 버전의 <code>.format()</code> 메서드나 <code>%</code> 연산자 방식도 있지만, f-string 사용이 권장됩니다.)</p>

    <h3 id="types-list">리스트 (list)</h3>
    <p>리스트는 순서가 있고 변경 가능한(mutable) 값들의 컬렉션입니다. 대괄호(<code>[]</code>)를 사용하여 생성하며, 서로 다른 타입의 값들을 포함할 수 있습니다.</p>
    <p>주요 특징:</p>
    <ul>
        <li><strong>변경 가능(Mutable):</strong> 생성 후 요소 추가, 삭제, 수정이 가능합니다.</li>
        <li><strong>순서(Ordered):</strong> 요소들이 입력된 순서대로 저장되고 인덱스로 접근 가능합니다.</li>
        <li><strong>인덱싱 및 슬라이싱:</strong> 문자열과 동일하게 작동하며, 슬라이싱을 이용한 수정도 가능합니다.</li>
        <li>다양한 타입의 요소 포함 가능.</li>
    </ul>
     <pre><code class="language-python"><span class="py-comment"># 리스트 생성</span>
<span class="py-parameter">empty_list</span> <span class="py-operator">=</span> []
<span class="py-parameter">numbers</span> <span class="py-operator">=</span> [<span class="py-number">1</span>, <span class="py-number">5</span>, <span class="py-number">2</span>, <span class="py-number">4</span>, <span class="py-number">3</span>]
<span class="py-parameter">mixed_list</span> <span class="py-operator">=</span> [<span class="py-number">1</span>, <span class="py-string">"apple"</span>, <span class="py-keyword">True</span>, <span class="py-number">3.14</span>, [<span class="py-string">'a'</span>, <span class="py-string">'b'</span>]] <span class="py-comment"># 중첩 리스트 가능</span>

<span class="py-builtin">print</span>(<span class="py-string">"리스트:"</span>, <span class="py-parameter">numbers</span>)
<span class="py-builtin">print</span>(<span class="py-string">"길이:"</span>, <span class="py-builtin">len</span>(<span class="py-parameter">numbers</span>))

<span class="py-comment"># 인덱싱 및 슬라이싱</span>
<span class="py-builtin">print</span>(<span class="py-string">"첫 요소:"</span>, <span class="py-parameter">numbers</span>[<span class="py-number">0</span>]) <span class="py-comment"># 1</span>
<span class="py-builtin">print</span>(<span class="py-string">"마지막 요소:"</span>, <span class="py-parameter">numbers</span>[<span class="py-operator">-</span><span class="py-number">1</span>]) <span class="py-comment"># 3</span>
<span class="py-builtin">print</span>(<span class="py-string">"부분 리스트:"</span>, <span class="py-parameter">numbers</span>[<span class="py-number">1</span>:<span class="py-number">4</span>]) <span class="py-comment"># [5, 2, 4]</span>
<span class="py-builtin">print</span>(<span class="py-string">"중첩 리스트 요소:"</span>, <span class="py-parameter">mixed_list</span>[<span class="py-number">4</span>][<span class="py-number">0</span>]) <span class="py-comment"># 'a'</span>

<span class="py-comment"># 요소 수정 (Mutable)</span>
<span class="py-parameter">numbers</span>[<span class="py-number">1</span>] <span class="py-operator">=</span> <span class="py-number">50</span>
<span class="py-builtin">print</span>(<span class="py-string">"수정 후:"</span>, <span class="py-parameter">numbers</span>) <span class="py-comment"># [1, 50, 2, 4, 3]</span>

<span class="py-comment"># 슬라이싱으로 여러 요소 수정</span>
<span class="py-parameter">numbers</span>[<span class="py-number">2</span>:<span class="py-number">4</span>] <span class="py-operator">=</span> [<span class="py-number">200</span>, <span class="py-number">400</span>, <span class="py-number">99</span>] <span class="py-comment"># 인덱스 2, 3 위치에 새 요소들 삽입</span>
<span class="py-builtin">print</span>(<span class="py-string">"슬라이싱 수정 후:"</span>, <span class="py-parameter">numbers</span>) <span class="py-comment"># [1, 50, 200, 400, 99, 3]</span></code></pre>

    <h4>리스트 메서드</h4>
    <ul>
        <li><code>.append(item)</code>: 리스트 끝에 요소 추가.</li>
        <li><code>.insert(index, item)</code>: 지정된 인덱스에 요소 삽입.</li>
        <li><code>.extend(iterable)</code>: 리스트 끝에 다른 iterable(리스트, 튜플 등)의 모든 요소 추가.</li>
        <li><code>.pop([index])</code>: 지정된 인덱스(기본값 -1, 마지막 요소)의 요소를 제거하고 반환.</li>
        <li><code>.remove(value)</code>: 리스트에서 첫 번째로 나타나는 특정 값을 제거. 없으면 ValueError 발생.</li>
        <li><code>.index(value, [start], [end])</code>: 특정 값의 첫 번째 인덱스 반환. 없으면 ValueError 발생.</li>
        <li><code>.count(value)</code>: 특정 값의 개수 반환.</li>
        <li><code>.sort(key=None, reverse=False)</code>: 리스트 자체를 정렬 (in-place). <code>key</code> 함수로 정렬 기준 지정 가능, <code>reverse=True</code>는 내림차순.</li>
        <li><code>.reverse()</code>: 리스트 요소 순서를 뒤집음 (in-place).</li>
        <li><code>.clear()</code>: 리스트의 모든 요소 제거.</li>
        <li><code>.copy()</code>: 리스트의 얕은 복사본(shallow copy) 생성.</li>
    </ul>
    <p>내장 함수 <code>sorted(iterable, key=None, reverse=False)</code>는 원본을 변경하지 않고 정렬된 새로운 리스트를 반환합니다.</p>
     <pre><code class="language-python"><span class="py-parameter">my_list</span> <span class="py-operator">=</span> [<span class="py-string">'a'</span>, <span class="py-string">'c'</span>, <span class="py-string">'b'</span>]

<span class="py-parameter">my_list</span>.append(<span class="py-string">'d'</span>) <span class="py-comment"># ['a', 'c', 'b', 'd']</span>
<span class="py-parameter">my_list</span>.insert(<span class="py-number">1</span>, <span class="py-string">'x'</span>) <span class="py-comment"># ['a', 'x', 'c', 'b', 'd']</span>
<span class="py-parameter">my_list</span>.extend([<span class="py-string">'e'</span>, <span class="py-string">'f'</span>]) <span class="py-comment"># ['a', 'x', 'c', 'b', 'd', 'e', 'f']</span>

<span class="py-parameter">popped_item</span> <span class="py-operator">=</span> <span class="py-parameter">my_list</span>.pop() <span class="py-comment"># 'f' 제거 및 반환</span>
<span class="py-parameter">popped_at_index</span> <span class="py-operator">=</span> <span class="py-parameter">my_list</span>.pop(<span class="py-number">1</span>) <span class="py-comment"># 'x' 제거 및 반환</span>

<span class="py-keyword">if</span> <span class="py-string">'c'</span> <span class="py-keyword">in</span> <span class="py-parameter">my_list</span>: <span class="py-comment"># 멤버십 연산자 사용 가능</span>
    <span class="py-parameter">my_list</span>.remove(<span class="py-string">'c'</span>) <span class="py-comment"># ['a', 'b', 'd', 'e']</span>

<span class="py-builtin">print</span>(<span class="py-string">f"List: {my_list}"</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"Index of 'b': {my_list.index('b')}"</span>) <span class="py-comment"># 1</span>

<span class="py-parameter">my_list</span>.sort() <span class="py-comment"># 원본 정렬 ['a', 'b', 'd', 'e']</span>
<span class="py-builtin">print</span>(<span class="py-string">f"Sorted: {my_list}"</span>)
<span class="py-parameter">my_list</span>.reverse() <span class="py-comment"># 원본 뒤집기 ['e', 'd', 'b', 'a']</span>
<span class="py-builtin">print</span>(<span class="py-string">f"Reversed: {my_list}"</span>)

<span class="py-parameter">new_sorted_list</span> <span class="py-operator">=</span> <span class="py-builtin">sorted</span>(<span class="py-parameter">my_list</span>, <span class="py-parameter">reverse</span><span class="py-operator">=</span><span class="py-keyword">True</span>) <span class="py-comment"># 새 리스트 반환 ['e', 'd', 'b', 'a']</span>
<span class="py-builtin">print</span>(<span class="py-string">f"Sorted (new): {new_sorted_list}"</span>)</code></pre>

    <h4>리스트 컴프리헨션 (List Comprehension)</h4>
    <p>기존의 iterable(주로 리스트)을 기반으로 조건에 따라 요소를 가공하여 새로운 리스트를 만드는 간결하고 효율적인 방법입니다.</p>
    <p>기본 형식: <code>[expression for item in iterable if condition]</code></p>
    <pre><code class="language-python"><span class="py-comment"># 0부터 9까지 숫자의 제곱으로 이루어진 리스트 생성</span>
<span class="py-parameter">squares</span> <span class="py-operator">=</span> [<span class="py-parameter">x</span><span class="py-operator"></span><span class="py-number">2</span> <span class="py-keyword">for</span> x <span class="py-keyword">in</span> <span class="py-builtin">range</span>(<span class="py-number">10</span>)]
<span class="py-builtin">print</span>(<span class="py-parameter">squares</span>) <span class="py-comment"># [0, 1, 4, 9, 16, 25, 36, 49, 64, 81]</span>

<span class="py-comment"># 리스트에서 짝수만 필터링하여 새 리스트 생성</span>
<span class="py-parameter">numbers</span> <span class="py-operator">=</span> [<span class="py-number">1</span>, <span class="py-number">2</span>, <span class="py-number">3</span>, <span class="py-number">4</span>, <span class="py-number">5</span>, <span class="py-number">6</span>]
<span class="py-parameter">even_numbers</span> <span class="py-operator">=</span> [<span class="py-parameter">num</span> <span class="py-keyword">for</span> num <span class="py-keyword">in</span> <span class="py-parameter">numbers</span> <span class="py-keyword">if</span> <span class="py-parameter">num</span> <span class="py-operator">%</span> <span class="py-number">2</span> <span class="py-operator">==</span> <span class="py-number">0</span>]
<span class="py-builtin">print</span>(<span class="py-parameter">even_numbers</span>) <span class="py-comment"># [2, 4, 6]</span>

<span class="py-comment"># 문자열 리스트에서 각 문자열의 길이를 담은 리스트 생성</span>
<span class="py-parameter">words</span> <span class="py-operator">=</span> [<span class="py-string">'apple'</span>, <span class="py-string">'banana'</span>, <span class="py-string">'cherry'</span>]
<span class="py-parameter">word_lengths</span> <span class="py-operator">=</span> [<span class="py-builtin">len</span>(<span class="py-parameter">word</span>) <span class="py-keyword">for</span> word <span class="py-keyword">in</span> <span class="py-parameter">words</span>]
<span class="py-builtin">print</span>(<span class="py-parameter">word_lengths</span>) <span class="py-comment"># [5, 6, 6]</span></code></pre>
    <p>리스트 컴프리헨션은 일반적인 <code>for</code> 루프보다 간결하고 종종 더 빠릅니다.</p>

    <h3 id="types-tuple">튜플 (tuple)</h3>
    <p>튜플은 순서가 있고 변경 불가능한(immutable) 값들의 컬렉션입니다. 소괄호(<code>()</code>)를 사용하여 생성합니다.</p>
    <p>주요 특징:</p>
    <ul>
        <li><strong>불변성(Immutable):</strong> 생성 후 요소 변경, 추가, 삭제가 불가능합니다.</li>
        <li><strong>순서(Ordered):</strong> 요소 순서가 유지되고 인덱스로 접근 가능합니다.</li>
        <li>인덱싱 및 슬라이싱: 문자열, 리스트와 동일하게 사용 가능 (읽기 전용).</li>
        <li>리스트보다 약간 더 가볍고 빠를 수 있습니다.</li>
        <li>함수에서 여러 값을 반환하거나, 딕셔너리의 키로 사용하는 등 값이 변경되지 않아야 하는 경우에 유용합니다.</li>
    </ul>
    <pre><code class="language-python"><span class="py-comment"># 튜플 생성</span>
<span class="py-parameter">empty_tuple</span> <span class="py-operator">=</span> ()
<span class="py-parameter">point</span> <span class="py-operator">=</span> (<span class="py-number">10</span>, <span class="py-number">20</span>)
<span class="py-parameter">colors</span> <span class="py-operator">=</span> <span class="py-string">'red'</span>, <span class="py-string">'green'</span>, <span class="py-string">'blue'</span> <span class="py-comment"># 괄호 생략 가능 (패킹)</span>
<span class="py-parameter">single_item_tuple</span> <span class="py-operator">=</span> (<span class="py-string">'hello'</span>,) <span class="py-comment"># 요소가 하나일 때는 반드시 쉼표(,) 필요</span>

<span class="py-builtin">print</span>(<span class="py-string">"튜플:"</span>, <span class="py-parameter">point</span>)
<span class="py-builtin">print</span>(<span class="py-string">"타입:"</span>, <span class="py-builtin">type</span>(<span class="py-parameter">colors</span>)) <span class="py-comment"># &lt;class 'tuple'&gt;</span>

<span class="py-comment"># 인덱싱</span>
<span class="py-builtin">print</span>(<span class="py-string">"첫 요소:"</span>, <span class="py-parameter">point</span>[<span class="py-number">0</span>]) <span class="py-comment"># 10</span>

<span class="py-comment"># 불변성 확인</span>
<span class="py-comment"># point[0] = 15 # TypeError: 'tuple' object does not support item assignment</span>

<span class="py-comment"># 튜플 언패킹 (Unpacking)</span>
<span class="py-parameter">x</span>, <span class="py-parameter">y</span> <span class="py-operator">=</span> <span class="py-parameter">point</span>
<span class="py-builtin">print</span>(<span class="py-string">f"x={x}, y={y}"</span>) <span class="py-comment"># x=10, y=20</span>

<span class="py-comment"># 주요 메서드 (제한적)</span>
<span class="py-builtin">print</span>(<span class="py-string">"길이:"</span>, <span class="py-builtin">len</span>(<span class="py-parameter">colors</span>)) <span class="py-comment"># 3</span>
<span class="py-builtin">print</span>(<span class="py-string">"Index of 'green':"</span>, <span class="py-parameter">colors</span>.index(<span class="py-string">'green'</span>)) <span class="py-comment"># 1</span>
<span class="py-builtin">print</span>(<span class="py-string">"Count of 'red':"</span>, <span class="py-parameter">colors</span>.count(<span class="py-string">'red'</span>)) <span class="py-comment"># 1</span></code></pre>

    <h3 id="types-range">range 객체</h3>
    <p><code>range()</code> 함수는 특정 범위의 정수 시퀀스를 나타내는 이터러블(iterable) 객체를 생성합니다. 실제 숫자들이 담긴 리스트를 만드는 것이 아니라, 필요할 때 해당 범위의 숫자를 생성해주는 방식이므로 메모리 효율적입니다.</p>
    <p>주로 <code>for</code> 루프와 함께 사용됩니다.</p>
    <ul>
        <li><code>range(stop)</code>: 0부터 <code>stop - 1</code>까지의 정수 시퀀스.</li>
        <li><code>range(start, stop)</code>: <code>start</code>부터 <code>stop - 1</code>까지의 정수 시퀀스.</li>
        <li><code>range(start, stop, step)</code>: <code>start</code>부터 <code>stop - 1</code>까지 <code>step</code> 간격의 정수 시퀀스.</li>
    </ul>
    <pre><code class="language-python"><span class="py-parameter">r1</span> <span class="py-operator">=</span> <span class="py-builtin">range</span>(<span class="py-number">5</span>)      <span class="py-comment"># 0, 1, 2, 3, 4</span>
<span class="py-parameter">r2</span> <span class="py-operator">=</span> <span class="py-builtin">range</span>(<span class="py-number">2</span>, <span class="py-number">6</span>)     <span class="py-comment"># 2, 3, 4, 5</span>
<span class="py-parameter">r3</span> <span class="py-operator">=</span> <span class="py-builtin">range</span>(<span class="py-number">1</span>, <span class="py-number">10</span>, <span class="py-number">2</span>) <span class="py-comment"># 1, 3, 5, 7, 9</span>

<span class="py-builtin">print</span>(<span class="py-parameter">r1</span>) <span class="py-comment"># range(0, 5) - 객체 자체 출력</span>
<span class="py-builtin">print</span>(<span class="py-builtin">list</span>(<span class="py-parameter">r1</span>)) <span class="py-comment"># [0, 1, 2, 3, 4] - 리스트로 변환하여 확인</span>

<span class="py-comment"># for 루프와 함께 사용</span>
<span class="py-keyword">for</span> i <span class="py-keyword">in</span> <span class="py-builtin">range</span>(<span class="py-number">3</span>):
    <span class="py-builtin">print</span>(<span class="py-string">f"Looping with range: {i}"</span>)</code></pre>
</section>

<section id="python-control-flow">
    <h2>제어 흐름 (Control Flow)</h2>
    <p>제어 흐름 구문은 코드의 실행 순서를 제어하여 조건에 따라 다른 코드를 실행하거나 특정 코드를 반복 실행합니다.</p>

    <h3 id="control-if">조건문 (if, elif, else)</h3>
    <p>조건문은 특정 조건이 참(True)인지 거짓(False)인지에 따라 다른 코드 블록을 실행합니다.</p>
    <ul>
        <li><code>if condition:</code>: 조건(<code>condition</code>)이 참(Truthy)일 경우 해당 블록 실행.</li>
        <li><code>elif another_condition:</code>: (else if) 이전 <code>if</code> 또는 <code>elif</code> 조건이 거짓이고 현재 조건이 참일 경우 해당 블록 실행. 여러 개 사용 가능.</li>
        <li><code>else:</code>: 모든 이전 <code>if</code> 및 <code>elif</code> 조건이 거짓일 경우 실행 (선택 사항).</li>
    </ul>
    <p>조건 평가는 불리언 값(<code>True</code>/<code>False</code>) 또는 Truthy/Falsy 값을 기준으로 합니다.</p>
     <pre><code class="language-python"><span class="py-parameter">temperature</span> <span class="py-operator">=</span> <span class="py-number">25</span>

<span class="py-keyword">if</span> <span class="py-parameter">temperature</span> <span class="py-operator">&gt;</span> <span class="py-number">30</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"매우 덥습니다."</span>)
<span class="py-keyword">elif</span> <span class="py-parameter">temperature</span> <span class="py-operator">&gt;=</span> <span class="py-number">20</span>: <span class="py-comment"># 25 >= 20 이므로 이 블록 실행</span>
    <span class="py-builtin">print</span>(<span class="py-string">"따뜻합니다."</span>)
<span class="py-keyword">elif</span> <span class="py-parameter">temperature</span> <span class="py-operator">&gt;=</span> <span class="py-number">10</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"쌀쌀합니다."</span>)
<span class="py-keyword">else</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"춥습니다."</span>)

<span class="py-comment"># 간단한 if</span>
<span class="py-parameter">name</span> <span class="py-operator">=</span> <span class="py-string">"Alice"</span>
<span class="py-keyword">if</span> <span class="py-parameter">name</span>: <span class="py-comment"># 비어있지 않은 문자열은 Truthy</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"Hello, {name}!"</span>)

<span class="py-comment"># 삼항 조건 연산자 (Ternary Operator) - 간단한 조건부 할당에 유용</span>
<span class="py-parameter">age</span> <span class="py-operator">=</span> <span class="py-number">17</span>
<span class="py-parameter">status</span> <span class="py-operator">=</span> <span class="py-string">"성인"</span> <span class="py-keyword">if</span> <span class="py-parameter">age</span> <span class="py-operator">&gt;=</span> <span class="py-number">18</span> <span class="py-keyword">else</span> <span class="py-string">"미성년자"</span>
<span class="py-builtin">print</span>(<span class="py-parameter">status</span>) <span class="py-comment"># 미성년자</span></code></pre>

    <h3 id="control-for">for 반복문 (in, range)</h3>
    <p><code>for</code> 루프는 시퀀스(리스트, 튜플, 문자열 등)나 다른 이터러블(iterable) 객체의 각 항목을 순서대로 반복하여 처리합니다.</p>
    <p>기본 형식: <code>for 변수 in 이터러블:</code></p>
    <pre><code class="language-python"><span class="py-comment"># 리스트 순회</span>
<span class="py-parameter">fruits</span> <span class="py-operator">=</span> [<span class="py-string">"apple"</span>, <span class="py-string">"banana"</span>, <span class="py-string">"cherry"</span>]
<span class="py-keyword">for</span> fruit <span class="py-keyword">in</span> <span class="py-parameter">fruits</span>:
    <span class="py-builtin">print</span>(<span class="py-string">f"I like {fruit}s!"</span>)

<span class="py-comment"># 문자열 순회</span>
<span class="py-keyword">for</span> char <span class="py-keyword">in</span> <span class="py-string">"Python"</span>:
    <span class="py-builtin">print</span>(<span class="py-parameter">char</span>, <span class="py-parameter">end</span><span class="py-operator">=</span><span class="py-string">" "</span>) <span class="py-comment"># P y t h o n</span>
<span class="py-builtin">print</span>() <span class="py-comment"># 줄바꿈</span>

<span class="py-comment"># range() 사용</span>
<span class="py-keyword">for</span> i <span class="py-keyword">in</span> <span class="py-builtin">range</span>(<span class="py-number">1</span>, <span class="py-number">6</span>): <span class="py-comment"># 1부터 5까지</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"Number: {i}"</span>)

<span class="py-comment"># 딕셔너리 순회</span>
<span class="py-parameter">user_info</span> <span class="py-operator">=</span> {<span class="py-string">"name"</span>: <span class="py-string">"Bob"</span>, <span class="py-string">"age"</span>: <span class="py-number">25</span>}
<span class="py-comment"># 기본적으로 키(key)를 순회</span>
<span class="py-keyword">for</span> key <span class="py-keyword">in</span> <span class="py-parameter">user_info</span>:
    <span class="py-builtin">print</span>(<span class="py-string">f"Key: {key}, Value: {user_info[key]}"</span>)
<span class="py-comment"># 값만 순회: .values()</span>
<span class="py-keyword">for</span> value <span class="py-keyword">in</span> <span class="py-parameter">user_info</span>.values():
    <span class="py-builtin">print</span>(<span class="py-string">f"Value: {value}"</span>)
<span class="py-comment"># 키-값 쌍 순회: .items()</span>
<span class="py-keyword">for</span> key, value <span class="py-keyword">in</span> <span class="py-parameter">user_info</span>.items():
    <span class="py-builtin">print</span>(<span class="py-string">f"{key} = {value}"</span>)</code></pre>

    <h3 id="control-while">while 반복문</h3>
    <p><code>while</code> 루프는 주어진 조건이 참(True)인 동안 코드 블록을 계속 반복 실행합니다. 조건은 루프 시작 전에 평가됩니다.</p>
    <p>기본 형식: <code>while condition:</code></p>
    <pre><code class="language-python"><span class="py-parameter">count</span> <span class="py-operator">=</span> <span class="py-number">0</span>
<span class="py-keyword">while</span> <span class="py-parameter">count</span> <span class="py-operator">&lt;</span> <span class="py-number">5</span>:
    <span class="py-builtin">print</span>(<span class="py-string">f"While count is: {count}"</span>)
    <span class="py-parameter">count</span> <span class="py-operator">+=</span> <span class="py-number">1</span> <span class="py-comment"># 조건이 언젠가 False가 되도록 값을 변경해야 함 (무한 루프 주의)</span>

<span class="py-builtin">print</span>(<span class="py-string">"Loop finished."</span>)

<span class="py-comment"># 사용자가 'quit'을 입력할 때까지 반복</span>
<span class="py-comment"># command = ""</span>
<span class="py-comment"># while command.lower() != "quit":</span>
<span class="py-comment">#     command = input("Enter command ('quit' to exit): ")</span>
<span class="py-comment">#     print(f"You entered: {command}")</span>
<span class="py-comment"># print("Exiting program.")</span></code></pre>
    <p class="warning"><code>while</code> 루프 사용 시 조건이 항상 참이면 무한 루프에 빠지게 됩니다. 루프 내에서 조건에 영향을 주는 값을 변경하거나 <code>break</code> 문을 사용하여 루프를 탈출할 수 있도록 설계해야 합니다.</p>

    <h3 id="control-break-continue-else">반복 제어 (break, continue, else)</h3>
    <ul>
        <li><code>break</code>: 현재 실행 중인 가장 안쪽의 <code>for</code> 또는 <code>while</code> 루프를 즉시 종료합니다.</li>
        <li><code>continue</code>: 현재 반복문의 나머지 부분을 건너뛰고 다음 반복을 시작합니다.</li>
        <li><code>else</code> 절 (루프와 함께 사용): <code>for</code>나 <code>while</code> 루프가 <code>break</code> 문으로 중간에 종료되지 않고, 정상적으로 모든 반복을 완료했을 때 실행되는 코드 블록입니다.</li>
    </ul>
    <pre><code class="language-python"><span class="py-comment"># break 예시: 5 찾기</span>
<span class="py-keyword">for</span> num <span class="py-keyword">in</span> <span class="py-builtin">range</span>(<span class="py-number">1</span>, <span class="py-number">11</span>):
    <span class="py-keyword">if</span> <span class="py-parameter">num</span> <span class="py-operator">==</span> <span class="py-number">5</span>:
        <span class="py-builtin">print</span>(<span class="py-string">"Found 5! Breaking loop."</span>)
        <span class="py-keyword">break</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"Checking {num}"</span>)

<span class="py-builtin">print</span>(<span class="py-string">"-"</span><span class="py-operator">*</span><span class="py-number">10</span>)

<span class="py-comment"># continue 예시: 홀수 건너뛰기</span>
<span class="py-keyword">for</span> i <span class="py-keyword">in</span> <span class="py-builtin">range</span>(<span class="py-number">1</span>, <span class="py-number">6</span>):
    <span class="py-keyword">if</span> <span class="py-parameter">i</span> <span class="py-operator">%</span> <span class="py-number">2</span> <span class="py-operator">!=</span> <span class="py-number">0</span>:
        <span class="py-keyword">continue</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"Even number: {i}"</span>)

<span class="py-builtin">print</span>(<span class="py-string">"-"</span><span class="py-operator">*</span><span class="py-number">10</span>)

<span class="py-comment"># loop-else 예시: 리스트에서 특정 값 찾기</span>
<span class="py-parameter">my_list</span> <span class="py-operator">=</span> [<span class="py-number">1</span>, <span class="py-number">3</span>, <span class="py-number">7</span>, <span class="py-number">9</span>]
<span class="py-parameter">search_value</span> <span class="py-operator">=</span> <span class="py-number">5</span>
<span class="py-keyword">for</span> item <span class="py-keyword">in</span> <span class="py-parameter">my_list</span>:
    <span class="py-keyword">if</span> <span class="py-parameter">item</span> <span class="py-operator">==</span> <span class="py-parameter">search_value</span>:
        <span class="py-builtin">print</span>(<span class="py-string">f"{search_value} found!"</span>)
        <span class="py-keyword">break</span>
<span class="py-keyword">else</span>: <span class="py-comment"># for 루프가 break 없이 완료되면 실행됨</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"{search_value} not found in the list."</span>)</code></pre>

    <h3 id="control-pass">pass 문</h3>
    <p><code>pass</code>는 아무런 동작도 수행하지 않는 문장입니다. 문법적으로는 코드가 필요하지만 프로그램 로직상 특별히 할 일이 없을 때 사용됩니다. 주로 나중에 구현할 함수나 클래스, 조건문 등의 빈 블록을 채우는 용도로 사용됩니다.</p>
    <pre><code class="language-python"><span class="py-keyword">def</span> <span class="py-function">my_future_function</span>():
    <span class="py-comment"># TODO: 나중에 기능 구현 예정</span>
    <span class="py-keyword">pass</span> <span class="py-comment"># 문법 오류 방지</span>

<span class="py-keyword">if</span> <span class="py-number">10</span> <span class="py-operator">&gt;</span> <span class="py-number">5</span>:
    <span class="py-keyword">pass</span> <span class="py-comment"># 조건은 참이지만 아무것도 안 함</span>
<span class="py-keyword">else</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"This won't run"</span>)

<span class="py-function">my_future_function</span>() <span class="py-comment"># 호출해도 아무 일도 일어나지 않음</span></code></pre>
</section>

<br><br>
<hr>

<section id="types-mapping">
    <h2>매핑 타입 (Mapping Type)</h2>
    <p>매핑 타입은 키(Key)와 값(Value)을 연결(매핑)하여 저장하는 데이터 구조입니다. Python의 대표적인 매핑 타입은 딕셔너리(dict)입니다.</p>

    <h3 id="types-dict">딕셔너리 (dict)</h3>
    <p>딕셔너리는 키(Key)와 값(Value)을 쌍으로 가지는 컬렉션입니다. 중괄호(<code>{}</code>)를 사용하여 생성하며, <code>key: value</code> 형태로 요소를 정의합니다.</p>
    <p>주요 특징:</p>
    <ul>
        <li><strong>키-값 쌍 (Key-Value Pair):</strong> 각 요소는 고유한 키와 해당 키에 연결된 값으로 구성됩니다.</li>
        <li><strong>키의 유일성 및 불변성:</strong> 딕셔너리 내의 키는 고유해야 하며, 변경 불가능한(immutable) 타입(주로 문자열, 숫자, 튜플)만 사용할 수 있습니다. 리스트나 다른 딕셔너리는 키가 될 수 없습니다.</li>
        <li><strong>변경 가능(Mutable):</strong> 생성 후 요소(키-값 쌍)를 추가, 수정, 삭제할 수 있습니다.</li>
        <li><strong>순서 (Python 3.7+):</strong> Python 3.7 버전부터 딕셔너리는 요소가 삽입된 순서를 기억합니다. (이전 버전에서는 순서가 보장되지 않았습니다.)</li>
        <li>값(Value)은 어떤 데이터 타입이든 가능하며 중복될 수 있습니다.</li>
    </ul>
    <pre><code class="language-python"><span class="py-comment"># 딕셔너리 생성</span>
<span class="py-parameter">empty_dict</span> <span class="py-operator">=</span> {}
<span class="py-parameter">person</span> <span class="py-operator">=</span> {
    <span class="py-string">'name'</span>: <span class="py-string">'Alice'</span>,
    <span class="py-string">'age'</span>: <span class="py-number">30</span>,
    <span class="py-string">'city'</span>: <span class="py-string">'Seoul'</span>,
    <span class="py-number">1995</span>: <span class="py-string">'birth_year'</span> <span class="py-comment"># 숫자도 키가 될 수 있음</span>
}
<span class="py-parameter">contact</span> <span class="py-operator">=</span> <span class="py-builtin">dict</span>(<span class="py-parameter">email</span><span class="py-operator">=</span><span class="py-string">'alice@example.com'</span>, <span class="py-parameter">phone</span><span class="py-operator">=</span><span class="py-string">'010-1234-5678'</span>) <span class="py-comment"># dict() 생성자 사용</span>

<span class="py-builtin">print</span>(<span class="py-string">"딕셔너리:"</span>, <span class="py-parameter">person</span>)
<span class="py-builtin">print</span>(<span class="py-string">"타입:"</span>, <span class="py-builtin">type</span>(<span class="py-parameter">person</span>)) <span class="py-comment"># &lt;class 'dict'&gt;</span>
<span class="py-builtin">print</span>(<span class="py-string">"길이 (키 개수):"</span>, <span class="py-builtin">len</span>(<span class="py-parameter">person</span>)) <span class="py-comment"># 4</span>

<span class="py-comment"># 값 접근</span>
<span class="py-comment"># 1. 대괄호 표기법: 키가 없으면 KeyError 발생</span>
<span class="py-builtin">print</span>(<span class="py-string">"이름:"</span>, <span class="py-parameter">person</span>[<span class="py-string">'name'</span>]) <span class="py-comment"># Alice</span>
<span class="py-comment"># print(person['country']) # KeyError: 'country'</span>

<span class="py-comment"># 2. .get() 메서드: 키가 없으면 None 또는 지정된 기본값 반환</span>
<span class="py-builtin">print</span>(<span class="py-string">"도시:"</span>, <span class="py-parameter">person</span>.get(<span class="py-string">'city'</span>)) <span class="py-comment"># Seoul</span>
<span class="py-builtin">print</span>(<span class="py-string">"직업:"</span>, <span class="py-parameter">person</span>.get(<span class="py-string">'job'</span>)) <span class="py-comment"># None (키가 없으므로)</span>
<span class="py-builtin">print</span>(<span class="py-string">"직업 (기본값):"</span>, <span class="py-parameter">person</span>.get(<span class="py-string">'job'</span>, <span class="py-string">'Unknown'</span>)) <span class="py-comment"># Unknown</span>

<span class="py-comment"># 요소 추가 및 수정</span>
<span class="py-parameter">person</span>[<span class="py-string">'email'</span>] <span class="py-operator">=</span> <span class="py-string">'alice.kim@example.com'</span> <span class="py-comment"># 새 키-값 쌍 추가</span>
<span class="py-parameter">person</span>[<span class="py-string">'age'</span>] <span class="py-operator">=</span> <span class="py-number">31</span> <span class="py-comment"># 기존 키 값 수정</span>
<span class="py-builtin">print</span>(<span class="py-string">"수정 후:"</span>, <span class="py-parameter">person</span>)

<span class="py-comment"># 요소 삭제</span>
<span class="py-keyword">del</span> <span class="py-parameter">person</span>[<span class="py-number">1995</span>] <span class="py-comment"># del 키워드 사용</span>
<span class="py-builtin">print</span>(<span class="py-string">"삭제 후:"</span>, <span class="py-parameter">person</span>)

<span class="py-comment"># 키 존재 확인</span>
<span class="py-keyword">if</span> <span class="py-string">'city'</span> <span class="py-keyword">in</span> <span class="py-parameter">person</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"city 키가 존재합니다."</span>)</code></pre>

    <h4>딕셔너리 메서드</h4>
    <ul>
        <li><code>.keys()</code>: 딕셔너리의 모든 키를 담은 뷰(view) 객체 반환.</li>
        <li><code>.values()</code>: 딕셔너리의 모든 값을 담은 뷰 객체 반환.</li>
        <li><code>.items()</code>: 딕셔너리의 모든 (키, 값) 튜플을 담은 뷰 객체 반환.</li>
        <li><code>.pop(key, [default])</code>: 지정된 키의 요소를 제거하고 해당 값을 반환. 키가 없으면 KeyError 발생 (<code>default</code> 값 지정 시 해당 값 반환).</li>
        <li><code>.popitem()</code>: 마지막으로 삽입된 (키, 값) 튜플을 제거하고 반환 (Python 3.7+). 이전 버전에서는 임의의 항목 제거.</li>
        <li><code>.update(other_dict or iterable)</code>: 다른 딕셔너리나 (키, 값) 쌍의 iterable로 현재 딕셔너리를 갱신 (기존 키는 덮어씀).</li>
        <li><code>.clear()</code>: 딕셔너리의 모든 요소 제거.</li>
        <li><code>.copy()</code>: 딕셔너리의 얕은 복사본 생성.</li>
    </ul>
    <p class="note"><code>.keys()</code>, <code>.values()</code>, <code>.items()</code>가 반환하는 뷰 객체는 딕셔너리 내용이 변경되면 실시간으로 반영됩니다. 리스트로 변환하려면 <code>list()</code> 함수를 사용합니다.</p>
     <pre><code class="language-python"><span class="py-parameter">player_scores</span> <span class="py-operator">=</span> {<span class="py-string">'Alice'</span>: <span class="py-number">95</span>, <span class="py-string">'Bob'</span>: <span class="py-number">88</span>, <span class="py-string">'Charlie'</span>: <span class="py-number">92</span>}

<span class="py-builtin">print</span>(<span class="py-string">"Keys:"</span>, <span class="py-parameter">player_scores</span>.keys())   <span class="py-comment"># dict_keys(['Alice', 'Bob', 'Charlie'])</span>
<span class="py-builtin">print</span>(<span class="py-string">"Values:"</span>, <span class="py-parameter">player_scores</span>.values()) <span class="py-comment"># dict_values([95, 88, 92])</span>
<span class="py-builtin">print</span>(<span class="py-string">"Items:"</span>, <span class="py-parameter">player_scores</span>.items()) <span class="py-comment"># dict_items([('Alice', 95), ('Bob', 88), ('Charlie', 92)])</span>

<span class="py-comment"># .items()를 이용한 반복 처리</span>
<span class="py-keyword">for</span> name, score <span class="py-keyword">in</span> <span class="py-parameter">player_scores</span>.items():
    <span class="py-builtin">print</span>(<span class="py-string">f"{name}: {score}점"</span>)

<span class="py-parameter">removed_score</span> <span class="py-operator">=</span> <span class="py-parameter">player_scores</span>.pop(<span class="py-string">'Bob'</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"Removed score for Bob: {removed_score}"</span>) <span class="py-comment"># 88</span>
<span class="py-builtin">print</span>(<span class="py-string">"After pop:"</span>, <span class="py-parameter">player_scores</span>) <span class="py-comment"># {'Alice': 95, 'Charlie': 92}</span>

<span class="py-parameter">last_item</span> <span class="py-operator">=</span> <span class="py-parameter">player_scores</span>.popitem()
<span class="py-builtin">print</span>(<span class="py-string">f"Removed last item: {last_item}"</span>) <span class="py-comment"># ('Charlie', 92)</span>
<span class="py-builtin">print</span>(<span class="py-string">"After popitem:"</span>, <span class="py-parameter">player_scores</span>) <span class="py-comment"># {'Alice': 95}</span>

<span class="py-parameter">player_scores</span>.update({<span class="py-string">'David'</span>: <span class="py-number">90</span>, <span class="py-string">'Alice'</span>: <span class="py-number">98</span>}) <span class="py-comment"># David 추가, Alice 점수 갱신</span>
<span class="py-builtin">print</span>(<span class="py-string">"After update:"</span>, <span class="py-parameter">player_scores</span>) <span class="py-comment"># {'Alice': 98, 'David': 90}</span></code></pre>

    <h4>딕셔너리 컴프리헨션 (Dictionary Comprehension)</h4>
    <p>리스트 컴프리헨션과 유사하게, iterable을 기반으로 딕셔너리를 간결하게 생성하는 방법입니다.</p>
    <p>기본 형식: <code>{key_expression: value_expression for item in iterable if condition}</code></p>
    <pre><code class="language-python"><span class="py-comment"># 숫자를 키로, 제곱을 값으로 하는 딕셔너리 생성</span>
<span class="py-parameter">squares_dict</span> <span class="py-operator">=</span> {<span class="py-parameter">x</span>: <span class="py-parameter">x</span><span class="py-operator"></span><span class="py-number">2</span> <span class="py-keyword">for</span> x <span class="py-keyword">in</span> <span class="py-builtin">range</span>(<span class="py-number">1</span>, <span class="py-number">6</span>)}
<span class="py-builtin">print</span>(<span class="py-parameter">squares_dict</span>) <span class="py-comment"># {1: 1, 2: 4, 3: 9, 4: 16, 5: 25}</span>

<span class="py-comment"># 기존 딕셔너리에서 특정 조건(값이 90 이상)을 만족하는 항목만 추출</span>
<span class="py-parameter">scores</span> <span class="py-operator">=</span> {<span class="py-string">'math'</span>: <span class="py-number">85</span>, <span class="py-string">'english'</span>: <span class="py-number">92</span>, <span class="py-string">'science'</span>: <span class="py-number">95</span>}
<span class="py-parameter">high_scores</span> <span class="py-operator">=</span> {<span class="py-parameter">subject</span>: <span class="py-parameter">score</span> <span class="py-keyword">for</span> subject, score <span class="py-keyword">in</span> <span class="py-parameter">scores</span>.items() <span class="py-keyword">if</span> <span class="py-parameter">score</span> <span class="py-operator">&gt;=</span> <span class="py-number">90</span>}
<span class="py-builtin">print</span>(<span class="py-parameter">high_scores</span>) <span class="py-comment"># {'english': 92, 'science': 95}</span></code></pre>
</section>

<section id="types-set">
    <h2>세트 타입 (Set Types)</h2>
    <p>세트(Set)는 순서가 없고 중복되지 않는(unique) 요소들의 컬렉션입니다.</p>

    <h3 id="types-set-set">세트 (set)</h3>
    <p><code>set</code>은 변경 가능한(mutable) 세트 타입입니다. 중괄호(<code>{}</code>) 안에 요소들을 나열하거나 <code>set()</code> 생성자를 사용하여 만듭니다.</p>
    <p class="warning">빈 세트를 만들 때는 반드시 <code>set()</code>을 사용해야 합니다. <code>{}</code>는 빈 딕셔너리를 생성합니다.</p>
    <p>주요 특징 및 사용 사례:</p>
    <ul>
        <li><strong>중복 요소 자동 제거:</strong> 리스트 등에서 중복을 제거하는 데 유용합니다.</li>
        <li><strong>빠른 멤버십 테스트:</strong> 특정 요소가 세트 안에 있는지(<code>in</code> 연산자) 매우 빠르게 확인할 수 있습니다.</li>
        <li>순서가 없으므로 인덱싱이나 슬라이싱은 지원하지 않습니다.</li>
        <li>합집합, 교집합, 차집합 등 수학적인 집합 연산이 가능합니다.</li>
    </ul>
     <pre><code class="language-python"><span class="py-comment"># 세트 생성</span>
<span class="py-parameter">empty_set</span> <span class="py-operator">=</span> <span class="py-builtin">set</span>() <span class="py-comment"># 빈 세트 생성</span>
<span class="py-parameter">fruits_set</span> <span class="py-operator">=</span> {<span class="py-string">'apple'</span>, <span class="py-string">'banana'</span>, <span class="py-string">'orange'</span>, <span class="py-string">'apple'</span>} <span class="py-comment"># 중복 'apple'은 하나만 저장됨</span>
<span class="py-parameter">numbers_set</span> <span class="py-operator">=</span> <span class="py-builtin">set</span>([<span class="py-number">1</span>, <span class="py-number">2</span>, <span class="py-number">2</span>, <span class="py-number">3</span>, <span class="py-number">1</span>]) <span class="py-comment"># 리스트에서 중복 제거하여 세트 생성</span>

<span class="py-builtin">print</span>(<span class="py-string">"과일 세트:"</span>, <span class="py-parameter">fruits_set</span>) <span class="py-comment"># 출력 순서는 다를 수 있음: {'orange', 'banana', 'apple'}</span>
<span class="py-builtin">print</span>(<span class="py-string">"숫자 세트:"</span>, <span class="py-parameter">numbers_set</span>) <span class="py-comment"># {1, 2, 3}</span>
<span class="py-builtin">print</span>(<span class="py-string">"길이:"</span>, <span class="py-builtin">len</span>(<span class="py-parameter">fruits_set</span>)) <span class="py-comment"># 3</span>

<span class="py-comment"># 요소 추가/제거 (Mutable)</span>
<span class="py-parameter">fruits_set</span>.add(<span class="py-string">'grape'</span>) <span class="py-comment"># 요소 추가</span>
<span class="py-parameter">fruits_set</span>.add(<span class="py-string">'banana'</span>) <span class="py-comment"># 이미 있는 요소 추가 시 변화 없음</span>
<span class="py-builtin">print</span>(<span class="py-string">"추가 후:"</span>, <span class="py-parameter">fruits_set</span>)

<span class="py-parameter">fruits_set</span>.remove(<span class="py-string">'orange'</span>) <span class="py-comment"># 요소 제거 (없으면 KeyError 발생)</span>
<span class="py-parameter">fruits_set</span>.discard(<span class="py-string">'kiwi'</span>) <span class="py-comment"># 요소 제거 (없어도 에러 발생 안 함)</span>
<span class="py-builtin">print</span>(<span class="py-string">"제거 후:"</span>, <span class="py-parameter">fruits_set</span>)

<span class="py-parameter">popped_item</span> <span class="py-operator">=</span> <span class="py-parameter">numbers_set</span>.pop() <span class="py-comment"># 임의의 요소 제거 및 반환</span>
<span class="py-builtin">print</span>(<span class="py-string">f"Pop된 요소: {popped_item}, 남은 세트: {numbers_set}"</span>)

<span class="py-comment"># 멤버십 테스트</span>
<span class="py-keyword">if</span> <span class="py-string">'apple'</span> <span class="py-keyword">in</span> <span class="py-parameter">fruits_set</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"'apple'이 세트에 있습니다."</span>)</code></pre>

    <h4>집합 연산</h4>
    <ul>
        <li><code>|</code> 또는 <code>.union(other_set)</code>: 합집합</li>
        <li><code>&</code> 또는 <code>.intersection(other_set)</code>: 교집합</li>
        <li><code>-</code> 또는 <code>.difference(other_set)</code>: 차집합 (첫 번째 세트에만 있는 요소)</li>
        <li><code>^</code> 또는 <code>.symmetric_difference(other_set)</code>: 대칭 차집합 (양쪽 세트 중 한 곳에만 있는 요소)</li>
    </ul>
    <p>메서드 형태(<code>.update()</code>, <code>.intersection_update()</code> 등)는 원본 세트를 직접 수정합니다.</p>
     <pre><code class="language-python"><span class="py-parameter">set_a</span> <span class="py-operator">=</span> {<span class="py-number">1</span>, <span class="py-number">2</span>, <span class="py-number">3</span>, <span class="py-number">4</span>}
<span class="py-parameter">set_b</span> <span class="py-operator">=</span> {<span class="py-number">3</span>, <span class="py-number">4</span>, <span class="py-number">5</span>, <span class="py-number">6</span>}

<span class="py-builtin">print</span>(<span class="py-string">"합집합 (|):"</span>, <span class="py-parameter">set_a</span> | <span class="py-parameter">set_b</span>)        <span class="py-comment"># {1, 2, 3, 4, 5, 6}</span>
<span class="py-builtin">print</span>(<span class="py-string">"교집합 (&):"</span>, <span class="py-parameter">set_a</span> & <span class="py-parameter">set_b</span>)        <span class="py-comment"># {3, 4}</span>
<span class="py-builtin">print</span>(<span class="py-string">"차집합 (-):"</span>, <span class="py-parameter">set_a</span> - <span class="py-parameter">set_b</span>)        <span class="py-comment"># {1, 2}</span>
<span class="py-builtin">print</span>(<span class="py-string">"대칭 차집합 (^):"</span>, <span class="py-parameter">set_a</span> ^ <span class="py-parameter">set_b</span>) <span class="py-comment"># {1, 2, 5, 6}</span>

<span class="py-comment"># 부분집합/상위집합 확인</span>
<span class="py-builtin">print</span>(<span class="string">"{1, 2} is subset of set_a:"</span>, {<span class="py-number">1</span>, <span class="py-number">2</span>}.issubset(<span class="py-parameter">set_a</span>)) <span class="py-comment"># True</span>
<span class="py-builtin">print</span>(<span class="string">"set_a is superset of {3, 4}:"</span>, <span class="py-parameter">set_a</span>.issuperset({<span class="py-number">3</span>, <span class="py-number">4</span>})) <span class="py-comment"># True</span>
<span class="py-builtin">print</span>(<span class="string">"set_a and {7, 8} are disjoint:"</span>, <span class="py-parameter">set_a</span>.isdisjoint({<span class="py-number">7</span>, <span class="py-number">8</span>})) <span class="py-comment"># True</span></code></pre>

    <h3 id="types-set-frozenset">프로즌세트 (frozenset)</h3>
    <p><code>frozenset</code>은 변경 불가능한(immutable) 세트 타입입니다. 생성 후에는 요소를 추가하거나 제거할 수 없습니다.</p>
    <p>세트의 불변 버전이 필요할 때 사용됩니다. 예를 들어, 딕셔너리의 키는 불변 타입만 가능하므로 <code>frozenset</code>은 키로 사용될 수 있지만 일반 <code>set</code>은 불가능합니다. 또한, 다른 세트의 요소로 포함될 수도 있습니다.</p>
    <pre><code class="language-python"><span class="py-parameter">fs1</span> <span class="py-operator">=</span> <span class="py-builtin">frozenset</span>([<span class="py-string">'a'</span>, <span class="py-string">'b'</span>, <span class="py-string">'c'</span>])
<span class="py-parameter">fs2</span> <span class="py-operator">=</span> <span class="py-builtin">frozenset</span>([<span class="py-string">'c'</span>, <span class="py-string">'d'</span>])

<span class="py-builtin">print</span>(<span class="py-parameter">fs1</span>) <span class="py-comment"># frozenset({'a', 'b', 'c'})</span>

<span class="py-comment"># 변경 불가 확인</span>
<span class="py-comment"># fs1.add('d') # AttributeError: 'frozenset' object has no attribute 'add'</span>

<span class="py-comment"># 집합 연산은 가능 (새 frozenset 반환)</span>
<span class="py-parameter">union_fs</span> <span class="py-operator">=</span> <span class="py-parameter">fs1</span> | <span class="py-parameter">fs2</span>
<span class="py-builtin">print</span>(<span class="py-parameter">union_fs</span>) <span class="py-comment"># frozenset({'a', 'b', 'c', 'd'})</span>

<span class="py-comment"># 딕셔너리 키로 사용</span>
<span class="py-parameter">dict_with_frozenset_key</span> <span class="py-operator">=</span> {<span class="py-parameter">fs1</span>: <span class="py-string">'Set A'</span>, <span class="py-parameter">fs2</span>: <span class="py-string">'Set B'</span>}
<span class="py-builtin">print</span>(<span class="py-parameter">dict_with_frozenset_key</span>[<span class="py-parameter">fs1</span>]) <span class="py-comment"># Set A</span></code></pre>
</section>


<section id="python-functions">
    <h2>함수 심화 (Functions Deep Dive)</h2>
    <p>이전 파트에서 함수의 기본 정의, 호출, 매개변수, 반환값, 스코프 기초를 배웠습니다. 여기서는 가변 인자, 스코프 심화, 람다 함수, 타입 힌트 등 함수의 더 다양한 기능을 살펴봅니다.</p>

    <h3 id="functions-parameters">매개변수와 인수 (복습 및 심화)</h3>
    <ul>
        <li><strong>위치 인수 (Positional Arguments):</strong> 함수 호출 시 전달된 값들이 함수 정의 시 매개변수 순서대로 할당됩니다.</li>
        <li><strong>키워드 인수 (Keyword Arguments):</strong> <code>parameter_name=value</code> 형태로 함수를 호출하여 매개변수 순서와 상관없이 값을 전달할 수 있습니다.</li>
        <li><strong>기본 매개변수 값 (Default Parameter Values):</strong> 함수 정의 시 <code>parameter=default_value</code> 형태로 기본값을 지정할 수 있습니다. 해당 인수가 전달되지 않으면 기본값이 사용됩니다.</li>
    </ul>
     <pre><code class="language-python"><span class="py-keyword">def</span> <span class="py-function">describe_pet</span>(<span class="py-parameter">pet_name</span>, <span class="py-parameter">animal_type</span><span class="py-operator">=</span><span class="py-string">'dog'</span>): <span class="py-comment"># animal_type 기본값 'dog'</span>
    <span class="py-string">"""반려동물 정보를 출력하는 함수"""</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"\nI have a {animal_type}."</span>)
    <span class="py-builtin">print</span>(<span class="py-string">f"My {animal_type}'s name is {pet_name.title()}."</span>)

<span class="py-comment"># 위치 인수로 호출</span>
<span class="py-function">describe_pet</span>(<span class="py-string">'willie'</span>, <span class="py-string">'hamster'</span>)

<span class="py-comment"># 키워드 인수로 호출 (순서 무관)</span>
<span class="py-function">describe_pet</span>(<span class="py-parameter">animal_type</span><span class="py-operator">=</span><span class="py-string">'cat'</span>, <span class="py-parameter">pet_name</span><span class="py-operator">=</span><span class="py-string">'luna'</span>)

<span class="py-comment"># 기본값 사용</span>
<span class="py-function">describe_pet</span>(<span class="py-parameter">pet_name</span><span class="py-operator">=</span><span class="py-string">'buddy'</span>) <span class="py-comment"># animal_type은 기본값 'dog' 사용</span>

<span class="py-comment"># 위치 인수와 키워드 인수 혼용 시, 위치 인수가 먼저 와야 함</span>
<span class="py-function">describe_pet</span>(<span class="py-string">'max'</span>, <span class="py-parameter">animal_type</span><span class="py-operator">=</span><span class="py-string">'rabbit'</span>)</code></pre>

    <h3 id="functions-args-kwargs">가변 인자 (*args, kwargs)</h3>
    <p>함수가 임의의 개수의 인수를 받을 수 있도록 합니다.</p>
    <ul>
        <li><strong><code>*args</code> (Arbitrary Positional Arguments):</strong> 함수 정의 시 매개변수 이름 앞에 별표(<code>*</code>)를 붙이면, 함수 호출 시 전달된 남는 위치 인수들이 하나의 튜플(tuple)로 묶여 해당 매개변수에 전달됩니다. 관례적으로 <code>args</code>라는 이름을 사용합니다.</li>
        <li><strong><code>kwargs</code> (Arbitrary Keyword Arguments):</strong> 함수 정의 시 매개변수 이름 앞에 별표 두 개(<code></code>)를 붙이면, 함수 호출 시 전달된 남는 키워드 인수들이 딕셔너리(dict)로 묶여 해당 매개변수에 전달됩니다. 관례적으로 <code>kwargs</code>라는 이름을 사용합니다.</li>
    </ul>
    <h4>매개변수 순서</h4>
    <p>함수 정의 시 매개변수는 다음 순서를 따라야 합니다:</p>
    <p><code>표준 위치 매개변수</code> -> <code>기본값 있는 위치 매개변수</code> -> <code>*args</code> -> <code>키워드 전용 매개변수(선택 사항)</code> -> <code>기본값 있는 키워드 전용 매개변수(선택 사항)</code> -> <code>kwargs</code></p>
    <p>키워드 전용 매개변수는 <code>*</code> 뒤나 <code>*args</code> 뒤에 정의하여 반드시 키워드 인수로만 전달받도록 강제할 수 있습니다.</p>
    <pre><code class="language-python"><span class="py-keyword">def</span> <span class="py-function">make_pizza</span>(<span class="py-parameter">size</span>, <span class="py-operator">*</span><span class="py-parameter">toppings</span>): <span class="py-comment"># 남는 위치 인수들이 toppings 튜플에 담김</span>
    <span class="py-string">"""피자 사이즈와 토핑 목록을 받아 요약 출력"""</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"\nMaking a {size}-inch pizza with the following toppings:"</span>)
    <span class="py-keyword">for</span> topping <span class="py-keyword">in</span> <span class="py-parameter">toppings</span>:
        <span class="py-builtin">print</span>(<span class="py-string">f"- {topping}"</span>)

<span class="py-function">make_pizza</span>(<span class="py-number">12</span>, <span class="py-string">'pepperoni'</span>)
<span class="py-function">make_pizza</span>(<span class="py-number">16</span>, <span class="py-string">'mushrooms'</span>, <span class="py-string">'green peppers'</span>, <span class="py-string">'extra cheese'</span>)

<span class="py-keyword">def</span> <span class="py-function">build_profile</span>(<span class="py-parameter">first</span>, <span class="py-parameter">last</span>, <span class="py-operator"></span><span class="py-parameter">user_info</span>): <span class="py-comment"># 남는 키워드 인수들이 user_info 딕셔너리에 담김</span>
    <span class="py-string">"""사용자 프로필 딕셔너리 생성"""</span>
    <span class="py-parameter">user_info</span>[<span class="py-string">'first_name'</span>] <span class="py-operator">=</span> <span class="py-parameter">first</span>
    <span class="py-parameter">user_info</span>[<span class="py-string">'last_name'</span>] <span class="py-operator">=</span> <span class="py-parameter">last</span>
    <span class="py-keyword">return</span> <span class="py-parameter">user_info</span>

<span class="py-parameter">user_profile</span> <span class="py-operator">=</span> <span class="py-function">build_profile</span>(<span class="py-string">'albert'</span>, <span class="py-string">'einstein'</span>,
                           <span class="py-parameter">location</span><span class="py-operator">=</span><span class="py-string">'princeton'</span>,
                           <span class="py-parameter">field</span><span class="py-operator">=</span><span class="py-string">'physics'</span>)
<span class="py-builtin">print</span>(<span class="py-parameter">user_profile</span>)
<span class="py-comment"># {'location': 'princeton', 'field': 'physics', 'first_name': 'albert', 'last_name': 'einstein'}</span>

<span class="py-comment"># 키워드 전용 매개변수 예시</span>
<span class="py-keyword">def</span> <span class="py-function">example_func</span>(<span class="py-parameter">pos1</span>, <span class="py-operator">*</span>, <span class="py-parameter">kw_only1</span>, <span class="py-parameter">kw_only2</span><span class="py-operator">=</span><span class="py-string">'default'</span>):
    <span class="py-builtin">print</span>(<span class="py-string">f"Positional: {pos1}, Keyword-Only: {kw_only1}, {kw_only2}"</span>)

<span class="py-comment"># example_func(1, 2) # TypeError: example_func() missing 1 required keyword-only argument: 'kw_only1'</span>
<span class="py-function">example_func</span>(<span class="py-number">1</span>, <span class="py-parameter">kw_only1</span><span class="py-operator">=</span><span class="py-number">2</span>) <span class="py-comment"># Positional: 1, Keyword-Only: 2, default</span>

<span class="py-comment"># 인수 언패킹 (Unpacking Arguments)</span>
<span class="py-parameter">args_list</span> <span class="py-operator">=</span> [<span class="py-number">12</span>, <span class="py-string">'mushrooms'</span>, <span class="py-string">'olives'</span>]
<span class="py-function">make_pizza</span>(<span class="py-operator">*</span><span class="py-parameter">args_list</span>) <span class="py-comment"># 리스트/튜플 요소를 개별 위치 인수로 전달</span>

<span class="py-parameter">profile_dict</span> <span class="py-operator">=</span> {<span class="py-string">'location'</span>: <span class="py-string">'bern'</span>, <span class="py-string">'field'</span>: <span class="py-string">'relativity'</span>}
<span class="py-parameter">user_profile2</span> <span class="py-operator">=</span> <span class="py-function">build_profile</span>(<span class="py-string">'albert'</span>, <span class="py-string">'einstein'</span>, <span class="py-operator"></span><span class="py-parameter">profile_dict</span>) <span class="py-comment"># 딕셔너리 아이템을 키워드 인수로 전달</span>
<span class="py-builtin">print</span>(<span class="py-parameter">user_profile2</span>)</code></pre>

    <h3 id="functions-scope">변수 스코프 (LEGB Rule, global, nonlocal)</h3>
    <p>스코프(Scope)는 변수가 유효한(참조 가능한) 코드 영역을 의미합니다. Python은 변수를 찾을 때 다음 순서(LEGB 규칙)로 스코프를 탐색합니다:</p>
    <ol>
        <li><strong>L (Local):</strong> 현재 함수 또는 클래스 메서드 내부.</li>
        <li><strong>E (Enclosing function locals):</strong> 현재 함수를 포함하는 외부 함수의 지역 스코프 (중첩 함수 경우).</li>
        <li><strong>G (Global):</strong> 모듈(파일) 수준에 정의된 전역 스코프.</li>
        <li><strong>B (Built-in):</strong> 파이썬 내장 함수/예외 등 미리 정의된 이름들.</li>
    </ol>
    <p>기본적으로 함수 내에서는 지역 변수만 생성 및 수정할 수 있습니다.</p>
    <ul>
        <li><strong><code>global</code> 키워드:</strong> 함수 내에서 전역 변수의 값을 수정하고 싶을 때 사용합니다. 해당 변수가 함수 내에서 전역 변수임을 명시합니다. (읽기만 할 때는 필요 없음)</li>
        <li><strong><code>nonlocal</code> 키워드 (Python 3+):</strong> 중첩 함수 내부에서, 자신을 감싸고 있는 가장 가까운 외부 함수(전역 제외)의 변수를 수정하고 싶을 때 사용합니다.</li>
    </ul>
     <pre><code class="language-python"><span class="py-parameter">x</span> <span class="py-operator">=</span> <span class="py-string">"global x"</span> <span class="py-comment"># 전역 변수</span>

<span class="py-keyword">def</span> <span class="py-function">outer_func</span>():
    <span class="py-parameter">y</span> <span class="py-operator">=</span> <span class="py-string">"outer y"</span> <span class="py-comment"># Enclosing 함수 스코프 변수</span>

    <span class="py-keyword">def</span> <span class="py-function">inner_func</span>():
        <span class="py-comment"># global x # 전역 x를 수정하려면 이 선언 필요</span>
        <span class="py-comment"># nonlocal y # 외부 함수 y를 수정하려면 이 선언 필요</span>
        <span class="py-parameter">z</span> <span class="py-operator">=</span> <span class="py-string">"local z"</span> <span class="py-comment"># 지역 변수</span>

        <span class="py-builtin">print</span>(<span class="py-string">f"Inside inner: z = {z}"</span>) <span class="py-comment"># Local 스코프</span>
        <span class="py-builtin">print</span>(<span class="py-string">f"Inside inner: y = {y}"</span>) <span class="py-comment"># Enclosing 스코프</span>
        <span class="py-builtin">print</span>(<span class="py-string">f"Inside inner: x = {x}"</span>) <span class="py-comment"># Global 스코프</span>

        <span class="py-comment"># y = "modified outer y" # 이렇게 하면 inner_func의 새로운 지역 변수 y 생성</span>
        <span class="py-keyword">nonlocal</span> y <span class="py-comment"># 외부 함수의 y를 사용하겠다고 선언</span>
        y <span class="py-operator">=</span> <span class="py-string">"modified outer y"</span>

    <span class="py-function">inner_func</span>()
    <span class="py-builtin">print</span>(<span class="py-string">f"Inside outer after inner call: y = {y}"</span>) <span class="py-comment"># 수정된 y 출력</span>

<span class="py-function">outer_func</span>()
<span class="py-builtin">print</span>(<span class="py-string">f"Global scope: x = {x}"</span>)

<span class="py-keyword">def</span> <span class="py-function">modify_global</span>():
    <span class="py-keyword">global</span> x
    x <span class="py-operator">=</span> <span class="py-string">"modified global x"</span>

<span class="py-function">modify_global</span>()
<span class="py-builtin">print</span>(<span class="py-string">f"After modify_global call: x = {x}"</span>)</code></pre>
    <p class="warning"><code>global</code> 키워드를 남용하면 코드 추적이 어려워지므로 꼭 필요한 경우에만 사용하고, 함수 간 데이터 전달은 주로 매개변수와 반환값을 이용하는 것이 좋습니다.</p>

    <h3 id="functions-lambda">람다 함수 (Lambda Functions)</h3>
    <p>람다 함수는 이름 없는 익명 함수(Anonymous Function)를 만드는 간결한 방법입니다. <code>lambda</code> 키워드를 사용하며, 주로 다른 함수의 인수로 전달되는 간단한 콜백 함수나 정렬 키 함수 등을 정의할 때 유용합니다.</p>
    <p>기본 형식: <code>lambda arguments: expression</code></p>
    <ul>
        <li><code>arguments</code>: 쉼표로 구분된 매개변수 목록.</li>
        <li><code>expression</code>: 단일 표현식. 이 표현식의 결과가 함수의 반환값이 됩니다.</li>
        <li>별도의 <code>return</code> 문을 사용하지 않습니다.</li>
        <li>복잡한 로직이나 여러 문장을 포함할 수 없습니다.</li>
    </ul>
    <pre><code class="language-python"><span class="py-comment"># 일반 함수</span>
<span class="py-keyword">def</span> <span class="py-function">double</span>(<span class="py-parameter">x</span>):
    <span class="py-keyword">return</span> <span class="py-parameter">x</span> <span class="py-operator">*</span> <span class="py-number">2</span>

<span class="py-comment"># 람다 함수</span>
<span class="py-parameter">double_lambda</span> <span class="py-operator">=</span> <span class="py-keyword">lambda</span> x: x <span class="py-operator">*</span> <span class="py-number">2</span>

<span class="py-builtin">print</span>(<span class="py-function">double</span>(<span class="py-number">5</span>)) <span class="py-comment"># 10</span>
<span class="py-builtin">print</span>(<span class="py-parameter">double_lambda</span>(<span class="py-number">5</span>)) <span class="py-comment"># 10</span>

<span class="py-comment"># 여러 인수 사용</span>
<span class="py-parameter">add_lambda</span> <span class="py-operator">=</span> <span class="py-keyword">lambda</span> a, b: a <span class="py-operator">+</span> b
<span class="py-builtin">print</span>(<span class="py-parameter">add_lambda</span>(<span class="py-number">3</span>, <span class="py-number">7</span>)) <span class="py-comment"># 10</span>

<span class="py-comment"># 콜백 함수로 사용 (예: 리스트 정렬 시 key 함수)</span>
<span class="py-parameter">points</span> <span class="py-operator">=</span> [(<span class="py-number">1</span>, <span class="py-number">5</span>), (<span class="py-number">3</span>, <span class="py-number">2</span>), (<span class="py-number">2</span>, <span class="py-number">8</span>)]
<span class="py-comment"># 각 튜플의 두 번째 요소(y 좌표) 기준으로 정렬</span>
<span class="py-parameter">points</span>.sort(<span class="py-parameter">key</span><span class="py-operator">=</span><span class="py-keyword">lambda</span> p: p[<span class="py-number">1</span>])
<span class="py-builtin">print</span>(<span class="py-parameter">points</span>) <span class="py-comment"># [(3, 2), (1, 5), (2, 8)]</span></code></pre>

    <h3 id="functions-map-filter">map, filter (간략히)</h3>
    <p><code>map()</code>과 <code>filter()</code>는 함수형 프로그래밍 스타일에서 자주 사용되는 내장 함수입니다.</p>
    <ul>
        <li><code>map(function, iterable)</code>: iterable의 각 요소에 <code>function</code>을 적용한 결과를 이터레이터(iterator)로 반환합니다. 리스트 등으로 변환하여 사용합니다 (예: <code>list(map(...))</code>).</li>
        <li><code>filter(function, iterable)</code>: iterable의 각 요소 중 <code>function</code>을 적용했을 때 결과가 참(True)인 요소들만 포함하는 이터레이터를 반환합니다.</li>
    </ul>
    <p>최근에는 리스트/딕셔너리/세트 컴프리헨션(Comprehension)이나 제너레이터 표현식(Generator Expression)이 가독성 측면에서 더 선호되는 경향이 있습니다.</p>
     <pre><code class="language-python"><span class="py-parameter">numbers</span> <span class="py-operator">=</span> [<span class="py-number">1</span>, <span class="py-number">2</span>, <span class="py-number">3</span>, <span class="py-number">4</span>, <span class="py-number">5</span>]

<span class="py-comment"># map 사용: 각 요소를 제곱</span>
<span class="py-parameter">squares_iterator</span> <span class="py-operator">=</span> <span class="py-builtin">map</span>(<span class="py-keyword">lambda</span> x: x<span class="py-operator"></span><span class="py-number">2</span>, <span class="py-parameter">numbers</span>)
<span class="py-builtin">print</span>(<span class="py-string">"Map 결과 (리스트 변환):"</span>, <span class="py-builtin">list</span>(<span class="py-parameter">squares_iterator</span>)) <span class="py-comment"># [1, 4, 9, 16, 25]</span>

<span class="py-comment"># filter 사용: 짝수만 필터링</span>
<span class="py-parameter">evens_iterator</span> <span class="py-operator">=</span> <span class="py-builtin">filter</span>(<span class="py-keyword">lambda</span> x: x <span class="py-operator">%</span> <span class="py-number">2</span> <span class="py-operator">==</span> <span class="py-number">0</span>, <span class="py-parameter">numbers</span>)
<span class="py-builtin">print</span>(<span class="py-string">"Filter 결과 (리스트 변환):"</span>, <span class="py-builtin">list</span>(<span class="py-parameter">evens_iterator</span>)) <span class="py-comment"># [2, 4]</span>

<span class="py-comment"># 컴프리헨션 사용 (더 Pythonic 하다고 여겨짐)</span>
<span class="py-parameter">squares_comp</span> <span class="py-operator">=</span> [<span class="py-parameter">x</span><span class="py-operator"></span><span class="py-number">2</span> <span class="py-keyword">for</span> x <span class="py-keyword">in</span> <span class="py-parameter">numbers</span>]
<span class="py-parameter">evens_comp</span> <span class="py-operator">=</span> [<span class="py-parameter">x</span> <span class="py-keyword">for</span> x <span class="py-keyword">in</span> <span class="py-parameter">numbers</span> <span class="py-keyword">if</span> <span class="py-parameter">x</span> <span class="py-operator">%</span> <span class="py-number">2</span> <span class="py-operator">==</span> <span class="py-number">0</span>]
<span class="py-builtin">print</span>(<span class="py-string">"Comprehension 결과 (제곱):"</span>, <span class="py-parameter">squares_comp</span>)
<span class="py-builtin">print</span>(<span class="py-string">"Comprehension 결과 (짝수):"</span>, <span class="py-parameter">evens_comp</span>)</code></pre>
    <p>(<code>functools.reduce()</code> 함수는 iterable의 요소들을 누적하여 단일 값으로 만드는 함수입니다.)</p>

    <h3 id="functions-annotations">타입 힌트 (Type Hints - PEP 484)</h3>
    <p>Python 3.5부터 함수 매개변수와 반환 값에 대한 타입 힌트(Type Hint)를 추가할 수 있는 문법이 도입되었습니다. 이는 코드의 가독성을 높이고, MyPy와 같은 정적 타입 검사 도구를 사용하여 개발 단계에서 잠재적인 타입 오류를 찾는 데 도움을 줍니다.</p>
    <p class="warning">중요: 타입 힌트는 힌트(hint)일 뿐이며, Python 인터프리터가 런타임에 타입을 강제하거나 검사하지는 않습니다. Python은 여전히 동적 타이핑 언어입니다.</p>
    <p>기본 문법:</p>
    <ul>
        <li>매개변수: <code>parameter_name: type_hint</code></li>
        <li>반환 값: <code>-> return_type_hint</code></li>
    </ul>
    <p><code>typing</code> 모듈을 사용하여 더 복잡한 타입(리스트, 딕셔너리, Optional 등)을 표현할 수 있습니다.</p>
    <pre><code class="language-python"><span class="py-keyword">from</span> typing <span class="py-keyword">import</span> List, Dict, Optional, Union <span class="py-comment"># 타입 힌트를 위한 모듈 임포트</span>

<span class="py-keyword">def</span> <span class="py-function">greeting</span>(<span class="py-parameter">name</span>: <span class="py-builtin">str</span>) <span class="py-operator">-&gt;</span> <span class="py-builtin">str</span>:
    <span class="py-string">"""이름을 받아 인사말 문자열을 반환 (타입 힌트 사용)"""</span>
    <span class="py-keyword">return</span> <span class="py-string">f"Hello, {name}!"</span>

<span class="py-keyword">def</span> <span class="py-function">process_data</span>(<span class="py-parameter">data</span>: List[<span class="py-builtin">int</span>], <span class="py-parameter">options</span>: Optional[Dict[<span class="py-builtin">str</span>, <span class="py-builtin">bool</span>]] <span class="py-operator">=</span> <span class="py-keyword">None</span>) <span class="py-operator">-&gt;</span> Union[<span class="py-builtin">int</span>, <span class="py-keyword">None</span>]:
    <span class="py-string">"""데이터 처리 예시 (복잡한 타입 힌트)
    - data: 정수 리스트
    - options: 문자열 키, 불리언 값을 가지는 딕셔너리 또는 None (기본값 None)
    - 반환값: 정수 또는 None
    """</span>
    <span class="py-keyword">if</span> <span class="py-parameter">options</span> <span class="py-keyword">is</span> <span class="py-keyword">None</span>:
        <span class="py-parameter">options</span> <span class="py-operator">=</span> {} <span class="py-comment"># 기본값 처리</span>

    <span class="py-keyword">if</span> <span class="py-parameter">data</span>:
        <span class="py-keyword">return</span> <span class="py-builtin">sum</span>(<span class="py-parameter">data</span>)
    <span class="py-keyword">else</span>:
        <span class="py-keyword">return</span> <span class="py-keyword">None</span>

<span class="py-parameter">message</span>: <span class="py-builtin">str</span> <span class="py-operator">=</span> <span class="py-function">greeting</span>(<span class="py-string">"Pythonista"</span>)
<span class="py-builtin">print</span>(<span class="py-parameter">message</span>)

<span class="py-parameter">result</span> <span class="py-operator">=</span> <span class="py-function">process_data</span>([<span class="py-number">10</span>, <span class="py-number">20</span>])
<span class="py-builtin">print</span>(<span class="py-parameter">result</span>) <span class="py-comment"># 30</span></code></pre>
    <p>타입 힌트는 필수는 아니지만, 협업이나 대규모 프로젝트에서 코드의 명확성을 높이는 데 큰 도움이 됩니다.</p>
</section>

<br><br>
<hr>

<section id="python-modules-packages">
    <h2>모듈과 패키지</h2>
    <p>Python 코드가 길어지고 복잡해지면, 관련된 코드들을 별도의 파일로 분리하여 관리하는 것이 효율적입니다. 모듈(Module)과 패키지(Package)는 코드를 구조화하고 재사용성을 높이는 중요한 방법입니다.</p>

    <h3 id="modules-importing">모듈 임포트 (import, from...import, as)</h3>
    <p>모듈(Module)은 Python 정의(함수, 클래스, 변수 등)가 들어 있는 <code>.py</code> 파일입니다. 다른 Python 스크립트에서 모듈을 임포트(import)하여 해당 모듈에 정의된 기능들을 사용할 수 있습니다.</p>
    <p>임포트하는 방법:</p>
    <ul>
        <li><strong><code>import module_name</code>:</strong> 모듈 전체를 임포트합니다. 모듈 내의 객체에 접근하려면 <code>module_name.object_name</code> 형식을 사용합니다.
             <pre><code class="language-python"><span class="py-keyword">import</span> math <span class="py-comment"># math 모듈 임포트</span>

<span class="py-builtin">print</span>(math.pi) <span class="py-comment"># math 모듈의 pi 상수 사용</span>
<span class="py-builtin">print</span>(math.sqrt(<span class="py-number">16</span>)) <span class="py-comment"># math 모듈의 sqrt 함수 사용 (4.0)</span></code></pre>
        </li>
        <li><strong><code>import module_name as alias</code>:</strong> 모듈을 임포트하면서 별칭(alias)을 지정합니다. 모듈 이름이 길거나 다른 이름과 충돌할 때 유용합니다.
             <pre><code class="language-python"><span class="py-keyword">import</span> pandas <span class="py-keyword">as</span> pd <span class="py-comment"># pandas 라이브러리를 pd라는 별칭으로 임포트</span>
<span class="py-comment"># df = pd.DataFrame(...) # pd 별칭 사용</span></code></pre>
        </li>
        <li><strong><code>from module_name import name1, name2, ...</code>:</strong> 모듈에서 특정 이름(함수, 클래스, 변수 등)만 직접 현재 스코프로 가져옵니다. 가져온 이름은 모듈 이름 없이 바로 사용할 수 있습니다.
             <pre><code class="language-python"><span class="py-keyword">from</span> math <span class="py-keyword">import</span> pi, sqrt

<span class="py-builtin">print</span>(pi)     <span class="py-comment"># 모듈 이름 없이 바로 사용</span>
<span class="py-builtin">print</span>(sqrt(<span class="py-number">25</span>)) <span class="py-comment"># 5.0</span></code></pre>
        </li>
        <li><strong><code>from module_name import name as alias</code>:</strong> 특정 이름을 가져오면서 별칭을 지정합니다.
             <pre><code class="language-python"><span class="py-keyword">from</span> math <span class="py-keyword">import</span> sqrt <span class="py-keyword">as</span> square_root
<span class="py-builtin">print</span>(square_root(<span class="py-number">9</span>)) <span class="py-comment"># 3.0</span></code></pre>
        </li>
         <li><strong><code>from module_name import *</code>:</strong> 모듈의 모든 이름(밑줄<code>_</code>로 시작하는 이름 제외)을 현재 스코프로 가져옵니다. 이름 충돌 가능성이 있고 코드 가독성을 해치므로 사용을 권장하지 않습니다.</li>
    </ul>

    <h3 id="modules-standard-library">표준 라이브러리 소개</h3>
    <p>Python은 설치 시 기본적으로 매우 유용하고 다양한 기능을 제공하는 표준 라이브러리(Standard Library)를 포함하고 있습니다. 별도의 설치 없이 <code>import</code>하여 바로 사용할 수 있습니다.</p>
    <p>주요 표준 라이브러리 모듈 예시 (일부):</p>
    <ul>
        <li><code>math</code>: 수학 관련 함수 및 상수 (<code>sqrt</code>, <code>sin</code>, <code>cos</code>, <code>log</code>, <code>pi</code>, <code>e</code> 등).</li>
        <li><code>random</code>: 난수 생성 관련 함수 (<code>random</code>, <code>randint</code>, <code>choice</code>, <code>shuffle</code> 등).</li>
        <li><code>datetime</code>: 날짜 및 시간 처리 관련 클래스 및 함수.</li>
        <li><code>os</code>: 운영체제와 상호작용하는 기능 (파일/디렉토리 조작, 환경 변수 접근 등).</li>
        <li><code>sys</code>: Python 인터프리터 관련 정보 및 제어 기능 (명령줄 인수 접근, 최대 재귀 깊이 등).</li>
        <li><code>json</code>: JSON 데이터 파싱 및 생성.</li>
        <li><code>re</code>: 정규 표현식 처리.</li>
        <li><code>urllib</code>: URL 관련 처리 (요청 보내기 등, `requests` 외부 라이브러리가 더 많이 사용됨).</li>
        <li><code>csv</code>: CSV 파일 읽기 및 쓰기.</li>
        <li><code>collections</code>: 추가적인 데이터 구조 제공 (<code>deque</code>, <code>Counter</code>, <code>defaultdict</code> 등).</li>
    </ul>
    <p>필요한 기능이 있다면 먼저 표준 라이브러리에 있는지 확인해보는 것이 좋습니다. 자세한 내용은 <a href="https://docs.python.org/3/library/" target="_blank">Python 공식 문서</a>를 참조하세요.</p>

    <h3 id="modules-creating">모듈 만들기</h3>
    <p>직접 모듈을 만드는 것은 간단합니다. Python 코드가 담긴 <code>.py</code> 파일을 생성하면 그 파일 자체가 하나의 모듈이 됩니다.</p>
    <div class="example">
      <h4>모듈 생성 및 사용 예시</h4>
      <p>1. `my_module.py` 파일 생성:</p>
      <pre><code class="language-python"><span class="py-comment"># my_module.py</span>
<span class="py-builtin">print</span>(<span class="py-string">"my_module 로딩됨!"</span>)

<span class="py-parameter">PI</span> <span class="py-operator">=</span> <span class="py-number">3.14159</span>

<span class="py-keyword">def</span> <span class="py-function">add</span>(<span class="py-parameter">a</span>, <span class="py-parameter">b</span>):
    <span class="py-keyword">return</span> <span class="py-parameter">a</span> <span class="py-operator">+</span> <span class="py-parameter">b</span>

<span class="py-keyword">class</span> <span class="py-class">MyClass</span>:
    <span class="py-keyword">def</span> <span class="py-function">__init__</span>(<span class="py-parameter">self</span>, <span class="py-parameter">name</span>):
        <span class="py-parameter">self</span>.name <span class="py-operator">=</span> <span class="py-parameter">name</span>

    <span class="py-keyword">def</span> <span class="py-function">greet</span>(<span class="py-parameter">self</span>):
        <span class="py-builtin">print</span>(<span class="py-string">f"Hello, {self.name}!"</span>)

<span class="py-comment"># 아래 코드는 이 파일이 직접 실행될 때만 동작 (import될 때는 실행 안 됨)</span>
<span class="py-keyword">if</span> __name__ <span class="py-operator">==</span> <span class="py-string">"__main__"</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"my_module이 직접 실행되었습니다."</span>)
    <span class="py-parameter">result</span> <span class="py-operator">=</span> <span class="py-function">add</span>(<span class="py-number">10</span>, <span class="py-number">5</span>)
    <span class="py-builtin">print</span>(<span class="py-string">f"테스트 결과: {result}"</span>)
</code></pre>
      <p>2. 같은 디렉토리에 `main.py` 파일 생성 후 `my_module` 임포트:</p>
       <pre><code class="language-python"><span class="py-comment"># main.py</span>
<span class="py-keyword">import</span> my_module <span class="py-comment"># my_module.py 임포트 (처음 임포트 시 my_module.py 실행됨)</span>
<span class="py-keyword">from</span> my_module <span class="py-keyword">import</span> MyClass

<span class="py-builtin">print</span>(<span class="py-string">"Pi 값:"</span>, my_module.PI)
<span class="py-parameter">sum_result</span> <span class="py-operator">=</span> my_module.add(<span class="py-number">7</span>, <span class="py-number">8</span>)
<span class="py-builtin">print</span>(<span class="py-string">"덧셈 결과:"</span>, <span class="py-parameter">sum_result</span>)

<span class="py-parameter">obj</span> <span class="py-operator">=</span> <span class="py-function">MyClass</span>(<span class="py-string">"Alice"</span>) <span class="py-comment"># from으로 가져왔으므로 바로 사용 가능</span>
<span class="py-parameter">obj</span>.greet()</code></pre>
       <p>3. `main.py` 실행 결과:</p>
       <pre><code class="output">my_module 로딩됨!
Pi 값: 3.14159
덧셈 결과: 15
Hello, Alice!</code></pre>
    </div>

    <h3 id="modules-name-main">`if __name__ == "__main__":`</h3>
    <p>Python 파일은 직접 스크립트로 실행될 수도 있고, 다른 파일에서 모듈로 임포트될 수도 있습니다.</p>
    <p><code>__name__</code>이라는 특별한 내장 변수는 다음과 같은 값을 가집니다:</p>
    <ul>
        <li>파일이 직접 실행될 경우: <code>__name__</code> 값은 <code>"__main__"</code>이 됩니다.</li>
        <li>파일이 다른 모듈에서 임포트될 경우: <code>__name__</code> 값은 해당 모듈의 이름(파일 이름에서 <code>.py</code> 제외)이 됩니다.</li>
    </ul>
    <p>따라서 <code>if __name__ == "__main__":</code> 블록 안의 코드는 해당 파일이 직접 실행될 때만 실행되고, 모듈로 임포트될 때는 실행되지 않습니다. 이는 모듈을 테스트하거나 예제 코드를 작성할 때 유용하게 사용됩니다.</p>

    <h3 id="modules-packages">패키지 개념 및 생성</h3>
    <p>프로젝트 규모가 커지면 여러 모듈 파일들을 관련된 것끼리 묶어 관리할 필요가 생깁니다. 패키지(Package)는 모듈들을 담는 디렉토리입니다.</p>
    <p>Python에서 어떤 디렉토리를 패키지로 인식하게 하려면, 해당 디렉토리 안에 `__init__.py` 라는 이름의 빈 파일(내용이 있을 수도 있음)을 포함시키면 됩니다. 이 파일은 해당 디렉토리가 패키지임을 알려주는 역할을 하며, 패키지가 임포트될 때 초기화 코드를 넣을 수도 있습니다.</p>
    <p>예시 구조:</p>
    <pre><code>my_project/
│
├── main.py
│
└── my_package/            # 패키지 디렉토리
    ├── __init__.py        # 패키지임을 표시 (필수)
    ├── module1.py
    └── module2.py
    └── sub_package/       # 하위 패키지
        ├── __init__.py
        └── module3.py
</code></pre>
    <p>패키지 내의 모듈을 임포트할 때는 점(<code>.</code>)을 사용하여 경로를 지정합니다.</p>
     <pre><code class="language-python"><span class="py-comment"># main.py 에서 임포트 예시</span>
<span class="py-keyword">import</span> my_package.module1
<span class="py-keyword">from</span> my_package <span class="py-keyword">import</span> module2
<span class="py-keyword">from</span> my_package.sub_package <span class="py-keyword">import</span> module3 <span class="py-keyword">as</span> mod3

my_package.module1.some_function()
module2.another_function()
mod3.yet_another_function()</code></pre>

    <h3 id="modules-pip">pip와 PyPI (외부 패키지 설치)</h3>
    <p>Python의 강력함은 방대한 외부 라이브러리(패키지) 생태계에 있습니다. PyPI(Python Package Index)는 이러한 외부 패키지들이 등록되어 있는 공식 저장소입니다.</p>
    <p>pip는 PyPI에서 패키지를 설치하고 관리하는 패키지 관리 도구입니다. Python 설치 시 보통 함께 설치됩니다.</p>
    <p>주요 pip 명령어 (터미널에서 실행):</p>
    <ul>
        <li><code>pip install package_name</code>: 특정 패키지 설치.</li>
        <li><code>pip install package_name==1.0.4</code>: 특정 버전 설치.</li>
        <li><code>pip install --upgrade package_name</code>: 패키지 최신 버전으로 업그레이드.</li>
        <li><code>pip uninstall package_name</code>: 패키지 제거.</li>
        <li><code>pip list</code>: 설치된 패키지 목록 확인.</li>
        <li><code>pip freeze</code>: 설치된 패키지 목록을 <code>requirements.txt</code> 파일 형식으로 출력.</li>
        <li><code>pip install -r requirements.txt</code>: <code>requirements.txt</code> 파일에 명시된 패키지들을 한 번에 설치. (프로젝트 의존성 관리에 필수적)</li>
    </ul>
    <p>예시: 데이터 분석 라이브러리 `pandas` 설치</p>
    <pre><code class="language-bash">$ pip install pandas</code></pre>
</section>

<section id="python-file-io">
    <h2>파일 입출력 (File I/O)</h2>
    <p>Python을 사용하여 컴퓨터의 파일 시스템에 있는 파일을 읽거나 쓸 수 있습니다.</p>

    <h3 id="file-opening">파일 열기 (open(), 모드)</h3>
    <p>파일 작업을 하려면 먼저 <code>open(file, mode='r', encoding=None)</code> 내장 함수를 사용하여 파일을 열어야 합니다.</p>
    <ul>
        <li><code>file</code>: 열 파일의 경로 및 이름 (문자열).</li>
        <li><code>mode</code> (선택 사항): 파일을 여는 모드 (기본값 'r').
            <ul>
                <li><code>'r'</code>: 읽기 모드 (기본값). 파일이 없으면 FileNotFoundError 발생.</li>
                <li><code>'w'</code>: 쓰기 모드. 파일이 있으면 내용을 덮어쓰고, 없으면 새로 생성.</li>
                <li><code>'a'</code>: 이어쓰기 모드. 파일이 있으면 끝에 내용을 추가하고, 없으면 새로 생성.</li>
                <li><code>'x'</code>: 배타적 생성 모드. 파일이 없으면 새로 생성하고, 있으면 FileExistsError 발생.</li>
                <li><code>'b'</code>: 바이너리 모드 (이미지, 오디오 등 텍스트 아닌 파일 처리 시). 예: <code>'rb'</code>, <code>'wb'</code>.</li>
                <li><code>'+'</code>: 읽기 및 쓰기 모드 (업데이트). 예: <code>'r+'</code>, <code>'w+'</code>, <code>'a+'</code>.</li>
            </ul>
        </li>
        <li><code>encoding</code> (선택 사항): 텍스트 모드에서 사용할 인코딩 방식. 시스템 기본 인코딩을 사용하지만, 명시적으로 <code>'utf-8'</code>을 지정하는 것이 좋습니다 (특히 다른 시스템과 파일 교환 시).</li>
    </ul>
    <p><code>open()</code> 함수는 파일 객체(File Object)를 반환하며, 이 객체를 통해 파일 읽기/쓰기 작업을 수행합니다.</p>
    <p class="warning">파일 작업이 끝나면 반드시 <code>.close()</code> 메서드를 호출하여 파일을 닫아야 합니다. 파일을 닫지 않으면 변경 사항이 제대로 저장되지 않거나 시스템 자원이 낭비될 수 있습니다. <strong><code>with</code> 문을 사용하면 파일을 자동으로 닫아주므로 더 안전하고 권장됩니다.</strong></p>

    <h3 id="file-with">with 문 사용</h3>
    <p><code>with open(...) as file_variable:</code> 구문은 파일 작업 블록이 끝나면(들여쓰기 벗어나면) 자동으로 파일을 닫아줍니다. 리소스 관리에 매우 유용하여 파일 처리 시 가장 권장되는 방식입니다.</p>
    <pre><code class="language-python"><span class="py-comment"># with 문을 사용하여 파일 읽기 (자동으로 close 됨)</span>
<span class="py-keyword">try</span>:
    <span class="py-keyword">with</span> <span class="py-builtin">open</span>(<span class="py-string">'example.txt'</span>, <span class="py-string">'r'</span>, <span class="py-parameter">encoding</span><span class="py-operator">=</span><span class="py-string">'utf-8'</span>) <span class="py-keyword">as</span> f:
        <span class="py-parameter">content</span> <span class="py-operator">=</span> f.read()
        <span class="py-builtin">print</span>(<span class="py-string">"--- 파일 내용 (with) ---"</span>)
        <span class="py-builtin">print</span>(<span class="py-parameter">content</span>)
<span class="py-keyword">except</span> <span class="py-builtin">FileNotFoundError</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"Error: example.txt 파일을 찾을 수 없습니다."</span>)

<span class="py-comment"># with 문을 사용하여 파일 쓰기</span>
<span class="py-keyword">try</span>:
    <span class="py-keyword">with</span> <span class="py-builtin">open</span>(<span class="py-string">'output.txt'</span>, <span class="py-string">'w'</span>, <span class="py-parameter">encoding</span><span class="py-operator">=</span><span class="py-string">'utf-8'</span>) <span class="py-keyword">as</span> f:
        f.write(<span class="py-string">"이것은 파이썬으로 작성된 파일입니다.\n"</span>)
        f.write(<span class="py-string">두 번째 줄입니다.\n</span>)
    <span class="py-builtin">print</span>(<span class="py-string">"output.txt 파일에 쓰기 완료."</span>)
<span class="py-keyword">except</span> <span class="py-builtin">IOError</span> <span class="py-keyword">as</span> e:
    <span class="py-builtin">print</span>(<span class="py-string">f"파일 쓰기 오류: {e}"</span>)</code></pre>

    <h3 id="file-reading">파일 읽기</h3>
    <p>파일 객체의 메서드를 사용하여 내용을 읽습니다.</p>
    <ul>
        <li><code>file.read([size])</code>: 파일 전체 내용(또는 지정된 <code>size</code> 바이트만큼)을 하나의 문자열로 읽어 반환합니다. 큰 파일의 경우 메모리 문제 발생 가능.</li>
        <li><code>file.readline()</code>: 파일에서 한 줄을 읽어 문자열로 반환합니다 (줄바꿈 문자 <code>\n</code> 포함). 파일 끝에 도달하면 빈 문자열 반환.</li>
        <li><code>file.readlines()</code>: 파일의 모든 줄을 읽어 각 줄을 요소로 하는 리스트로 반환합니다. 큰 파일 주의.</li>
        <li>파일 객체 직접 반복: 파일 객체는 이터레이터이므로 <code>for line in file:</code> 형태로 한 줄씩 읽는 것이 가장 효율적이고 Pythonic한 방법입니다.</li>
    </ul>
     <pre><code class="language-python"><span class="py-comment"># 파일 생성 (예시용)</span>
<span class="py-keyword">with</span> <span class="py-builtin">open</span>(<span class="py-string">'lines.txt'</span>, <span class="py-string">'w'</span>, <span class="py-parameter">encoding</span><span class="py-operator">=</span><span class="py-string">'utf-8'</span>) <span class="py-keyword">as</span> f:
    f.write(<span class="py-string">"첫 번째 라인\n"</span>)
    f.write(<span class="py-string">"두 번째 라인\n"</span>)
    f.write(<span class="py-string">"세 번째 라인\n"</span>)

<span class="py-comment"># 파일 읽기 (권장 방식: 한 줄씩 반복)</span>
<span class="py-builtin">print</span>(<span class="py-string">"\n--- 파일 읽기 (한 줄씩 반복) ---"</span>)
<span class="py-keyword">try</span>:
    <span class="py-keyword">with</span> <span class="py-builtin">open</span>(<span class="py-string">'lines.txt'</span>, <span class="py-string">'r'</span>, <span class="py-parameter">encoding</span><span class="py-operator">=</span><span class="py-string">'utf-8'</span>) <span class="py-keyword">as</span> reader:
        <span class="py-keyword">for</span> line <span class="py-keyword">in</span> <span class="py-parameter">reader</span>:
            <span class="py-builtin">print</span>(<span class="py-parameter">line</span>.strip()) <span class="py-comment"># strip()으로 양 끝 공백/줄바꿈 제거 후 출력</span>
<span class="py-keyword">except</span> <span class="py-builtin">FileNotFoundError</span>:
     <span class="py-builtin">print</span>(<span class="py-string">"Error: lines.txt 파일을 찾을 수 없습니다."</span>)

<span class="py-comment"># readlines() 사용 예시</span>
<span class="py-builtin">print</span>(<span class="py-string">"\n--- 파일 읽기 (readlines) ---"</span>)
<span class="py-keyword">try</span>:
    <span class="py-keyword">with</span> <span class="py-builtin">open</span>(<span class="py-string">'lines.txt'</span>, <span class="py-string">'r'</span>, <span class="py-parameter">encoding</span><span class="py-operator">=</span><span class="py-string">'utf-8'</span>) <span class="py-keyword">as</span> reader:
        <span class="py-parameter">lines</span> <span class="py-operator">=</span> reader.readlines() <span class="py-comment"># 모든 줄을 리스트로 읽음</span>
        <span class="py-builtin">print</span>(<span class="py-parameter">lines</span>)
        <span class="py-keyword">for</span> line <span class="py-keyword">in</span> <span class="py-parameter">lines</span>:
            <span class="py-builtin">print</span>(line.strip())
<span class="py-keyword">except</span> <span class="py-builtin">FileNotFoundError</span>:
     <span class="py-builtin">print</span>(<span class="py-string">"Error: lines.txt 파일을 찾을 수 없습니다."</span>)</code></pre>

    <h3 id="file-writing">파일 쓰기</h3>
    <p>파일 객체의 메서드를 사용하여 데이터를 씁니다.</p>
    <ul>
        <li><code>file.write(string)</code>: 문자열을 파일에 씁니다. 줄바꿈 문자(<code>\n</code>)는 자동으로 추가되지 않으므로 필요시 직접 넣어줘야 합니다.</li>
        <li><code>file.writelines(lines)</code>: 문자열 리스트(또는 다른 iterable)를 파일에 씁니다. 각 문자열 끝에 줄바꿈 문자가 자동으로 추가되지 않습니다.</li>
    </ul>
    <pre><code class="language-python"><span class="py-parameter">data_to_write</span> <span class="py-operator">=</span> [<span class="py-string">"라인 1\n"</span>, <span class="py-string">"Line 2\n"</span>, <span class="py-string">"세 번째 줄\n"</span>]

<span class="py-keyword">try</span>:
    <span class="py-comment"># 'w' 모드는 기존 파일 내용을 덮어씀</span>
    <span class="py-keyword">with</span> <span class="py-builtin">open</span>(<span class="py-string">'write_example.txt'</span>, <span class="py-string">'w'</span>, <span class="py-parameter">encoding</span><span class="py-operator">=</span><span class="py-string">'utf-8'</span>) <span class="py-keyword">as</span> writer:
        writer.write(<span class="py-string">"파일에 직접 쓰기.\n"</span>)
        writer.writelines(<span class="py-parameter">data_to_write</span>) <span class="py-comment"># 리스트의 문자열들을 씀</span>
    <span class="py-builtin">print</span>(<span class="py-string">"write_example.txt 파일 쓰기 완료."</span>)

    <span class="py-comment"># 'a' 모드는 파일 끝에 이어씀</span>
    <span class="py-keyword">with</span> <span class="py-builtin">open</span>(<span class="py-string">'write_example.txt'</span>, <span class="py-string">'a'</span>, <span class="py-parameter">encoding</span><span class="py-operator">=</span><span class="py-string">'utf-8'</span>) <span class="py-keyword">as</span> appender:
        appender.write(<span class="py-string">"이 내용은 이어쓰기 됩니다.\n"</span>)
    <span class="py-builtin">print</span>(<span class="py-string">"write_example.txt 파일 이어쓰기 완료."</span>)
<span class="py-keyword">except</span> <span class="py-builtin">IOError</span> <span class="py-keyword">as</span> e:
     <span class="py-builtin">print</span>(<span class="py-string">f"파일 쓰기/이어쓰기 오류: {e}"</span>)</code></pre>

    <h3 id="file-paths">파일 경로 다루기 (os.path)</h3>
    <p>파일 경로를 문자열로 직접 다루는 것보다 <code>os.path</code> 모듈을 사용하면 운영체제(Windows, macOS, Linux)에 상관없이 호환되는 방식으로 경로를 처리할 수 있습니다.</p>
    <ul>
        <li><code>os.path.join(path1, path2, ...)</code>: 여러 경로 구성요소를 운영체제에 맞는 구분자(<code>/</code> 또는 <code>\</code>)로 연결하여 완전한 경로 생성. 권장 방식.</li>
        <li><code>os.path.exists(path)</code>: 해당 경로의 파일이나 디렉토리가 존재하는지 확인.</li>
        <li><code>os.path.isfile(path)</code> / <code>os.path.isdir(path)</code>: 일반 파일인지 / 디렉토리인지 확인.</li>
        <li><code>os.path.basename(path)</code>: 경로의 마지막 부분(파일 또는 디렉토리 이름) 반환.</li>
        <li><code>os.path.dirname(path)</code>: 경로의 디렉토리 부분 반환.</li>
        <li><code>os.path.splitext(path)</code>: 경로를 파일명과 확장자로 분리하여 튜플로 반환.</li>
        <li><code>os.path.abspath(path)</code>: 상대 경로를 절대 경로로 변환.</li>
        <li><code>os.getcwd()</code>: 현재 작업 디렉토리 경로 반환.</li>
        <li><code>os.listdir(path)</code>: 디렉토리 내의 파일/디렉토리 목록을 리스트로 반환.</li>
        <li><code>os.mkdir(path)</code> / <code>os.makedirs(path)</code>: 디렉토리 생성 (<code>makedirs</code>는 중간 경로도 생성).</li>
        <li><code>os.remove(path)</code> / <code>os.unlink(path)</code>: 파일 삭제.</li>
        <li><code>os.rmdir(path)</code> / <code>os.removedirs(path)</code>: 빈 디렉토리 삭제.</li>
    </ul>
     <pre><code class="language-python"><span class="py-keyword">import</span> os

<span class="py-parameter">file_name</span> <span class="py-operator">=</span> <span class="py-string">"my_data.csv"</span>
<span class="py-parameter">data_dir</span> <span class="py-operator">=</span> <span class="py-string">"data_files"</span>

<span class="py-comment"># OS 호환되는 경로 생성</span>
<span class="py-parameter">full_path</span> <span class="py-operator">=</span> os.path.join(os.getcwd(), <span class="py-parameter">data_dir</span>, <span class="py-parameter">file_name</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"Full path: {full_path}"</span>)

<span class="py-comment"># 파일 존재 여부 확인</span>
<span class="py-keyword">if</span> os.path.exists(<span class="py-parameter">full_path</span>):
    <span class="py-builtin">print</span>(<span class="py-string">f"'{file_name}' exists.")
    <span class="py-builtin">print</span>(<span class="py-string">f"Is file? {os.path.isfile(full_path)}"</span>)
    <span class="py-builtin">print</span>(<span class="py-string">f"Directory name: {os.path.dirname(full_path)}"</span>)
    <span class="py-builtin">print</span>(<span class="py-string">f"Base name: {os.path.basename(full_path)}"</span>)
    <span class="py-parameter">name</span>, <span class="py-parameter">ext</span> <span class="py-operator">=</span> os.path.splitext(<span class="py-parameter">full_path</span>)
    <span class="py-builtin">print</span>(<span class="py-string">f"Name: {name}, Extension: {ext}"</span>)
<span class="py-keyword">else</span>:
    <span class="py-builtin">print</span>(<span class="py-string">f"'{file_name}' does not exist.")

<span class="py-comment"># 디렉토리 생성 예시</span>
<span class="py-keyword">try</span>:
    <span class="py-keyword">if</span> <span class="py-keyword">not</span> os.path.exists(<span class="py-parameter">data_dir</span>):
        os.makedirs(<span class="py-parameter">data_dir</span>) <span class="py-comment"># 중간 경로 포함 생성</span>
        <span class="py-builtin">print</span>(<span class="py-string">f"Directory '{data_dir}' created.")
<span class="py-keyword">except</span> <span class="py-builtin">OSError</span> <span class="py-keyword">as</span> e:
    <span class="py-builtin">print</span>(<span class="py-string">f"디렉토리 생성 오류: {e}"</span>)
</code></pre>
</section>

<section id="python-exceptions">
    <h2>예외 처리 (Exceptions)</h2>
    <p>프로그램 실행 중 예기치 않은 문제(오류)가 발생하면 예외(Exception)가 발생하여 프로그램이 비정상적으로 종료될 수 있습니다. 예외 처리는 이러한 상황에 대처하여 프로그램이 중단되는 것을 막고, 오류를 적절히 처리하거나 사용자에게 알리는 메커니즘입니다.</p>

    <h3 id="exceptions-intro">예외란?</h3>
    <p>Python에서 발생하는 오류는 크게 두 가지로 나눌 수 있습니다:</p>
    <ul>
        <li>구문 오류 (Syntax Errors): 코드의 문법 자체가 잘못되어 파이썬 인터프리터가 코드를 해석(파싱)할 수 없는 경우입니다. 예: 콜론(<code>:</code>) 누락, 잘못된 들여쓰기. 이 오류는 코드를 실행하기 전에 발견됩니다.</li>
        <li>예외 (Exceptions / Runtime Errors): 문법은 올바르지만, 코드 실행 중에 발생하는 오류입니다. 예: 0으로 나누기(<code>ZeroDivisionError</code>), 존재하지 않는 파일 열기(<code>FileNotFoundError</code>), 잘못된 타입 사용(<code>TypeError</code>), 존재하지 않는 키 접근(<code>KeyError</code>) 등.</li>
    </ul>
    <p>예외 처리는 주로 런타임 에러(Exception)를 다룹니다.</p>

    <h3 id="exceptions-try-except">try...except 블록</h3>
    <p><code>try...except</code> 블록은 예외가 발생할 가능성이 있는 코드를 안전하게 실행하고, 예외 발생 시 처리할 코드를 정의합니다.</p>
    <p>기본 구조:</p>
    <pre><code class="language-python"><span class="py-keyword">try</span>:
    <span class="py-comment"># 예외가 발생할 수 있는 코드 블록</span>
    <span class="py-parameter">result</span> <span class="py-operator">=</span> <span class="py-number">10</span> / <span class="py-number">0</span> <span class="py-comment"># ZeroDivisionError 발생 예상</span>
    <span class="py-builtin">print</span>(<span class="py-string">"이 줄은 실행되지 않습니다."</span>)
<span class="py-keyword">except</span> <span class="py-builtin">ZeroDivisionError</span>: <span class="py-comment"># 특정 예외 타입(ZeroDivisionError)을 잡아서 처리</span>
    <span class="py-builtin">print</span>(<span class="py-string">"0으로 나눌 수 없습니다!"</span>)
<span class="py-keyword">except</span> <span class="py-builtin">Exception</span>: <span class="py-comment"># 다른 모든 종류의 예외를 잡아서 처리 (가장 마지막에 위치 권장)</span>
    <span class="py-builtin">print</span>(<span class="py-string">"알 수 없는 오류가 발생했습니다."</span>)

<span class="py-builtin">print</span>(<span class="py-string">"프로그램 계속 진행..."</span>)</code></pre>
    <p><code>try</code> 블록의 코드를 실행하다가 예외가 발생하면, 해당 예외 타입과 일치하는 첫 번째 <code>except</code> 블록의 코드가 실행되고 <code>try...except</code> 블록 전체가 종료됩니다. 만약 예외가 발생하지 않으면 <code>except</code> 블록은 실행되지 않습니다.</p>

    <h3 id="exceptions-handling-specific">특정 예외 처리</h3>
    <p>여러 개의 <code>except</code> 블록을 사용하여 발생할 수 있는 특정 예외 타입별로 다른 처리 로직을 구현할 수 있습니다. 더 구체적인 예외 타입을 먼저 작성해야 합니다.</p>
    <p><code>except (TypeError, ValueError):</code> 처럼 여러 예외 타입을 하나의 블록에서 처리할 수도 있습니다.</p>
    <p><code>except ExceptionType as e:</code> 형태로 예외 객체를 변수(<code>e</code>)에 할당받아 오류에 대한 상세 정보(<code>str(e)</code>, <code>type(e)</code> 등)를 확인할 수 있습니다.</p>
    <pre><code class="language-python"><span class="py-parameter">my_dict</span> <span class="py-operator">=</span> {<span class="py-string">'a'</span>: <span class="py-number">1</span>}
<span class="py-parameter">my_list</span> <span class="py-operator">=</span> [<span class="py-number">10</span>, <span class="py-number">20</span>]

<span class="py-keyword">try</span>:
    <span class="py-comment"># key = 'b' # KeyError 발생 시나리오</span>
    <span class="py-comment"># index = 5 # IndexError 발생 시나리오</span>
    <span class="py-parameter">value</span> <span class="py-operator">=</span> <span class="py-parameter">my_dict</span>[<span class="py-string">'a'</span>] + <span class="py-parameter">my_list</span>[<span class="py-number">0</span>]
    <span class="py-builtin">print</span>(<span class="py-string">f"계산 결과: {value}"</span>)
<span class="py-keyword">except</span> <span class="py-builtin">KeyError</span> <span class="py-keyword">as</span> ke: <span class="py-comment"># KeyError 발생 시</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"오류: 딕셔너리에 없는 키입니다 - {ke}"</span>)
<span class="py-keyword">except</span> <span class="py-builtin">IndexError</span> <span class="py-keyword">as</span> ie: <span class="py-comment"># IndexError 발생 시</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"오류: 잘못된 리스트 인덱스입니다 - {ie}"</span>)
<span class="py-keyword">except</span> (<span class="py-builtin">TypeError</span>, <span class="py-builtin">ValueError</span>) <span class="py-keyword">as</span> te: <span class="py-comment"># TypeError 또는 ValueError 발생 시</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"오류: 타입 또는 값 관련 오류 - {te}"</span>)
<span class="py-keyword">except</span> <span class="py-builtin">Exception</span> <span class="py-keyword">as</span> e: <span class="py-comment"># 위에서 잡지 못한 다른 모든 예외 처리</span>
    <span class="py-builtin">print</span>(<span class="py-string">f"알 수 없는 오류 발생: {e}"</span>)
</code></pre>

    <h3 id="exceptions-else-finally">else 및 finally 블록</h3>
    <ul>
        <li><code>else</code>: <code>try</code> 블록에서 예외가 발생하지 않았을 경우에만 실행되는 코드 블록입니다. (선택 사항)</li>
        <li><code>finally</code>: 예외 발생 여부나 <code>try</code>, <code>except</code>, <code>else</code> 블록의 실행 결과와 상관없이 항상 마지막에 실행되는 코드 블록입니다. 주로 파일 닫기, 네트워크 연결 해제 등 자원 정리 작업에 사용됩니다. (선택 사항)</li>
    </ul>
    <pre><code class="language-python"><span class="py-parameter">f</span> <span class="py-operator">=</span> <span class="py-keyword">None</span> <span class="py-comment"># 파일 객체 변수 초기화</span>
<span class="py-keyword">try</span>:
    <span class="py-parameter">f</span> <span class="py-operator">=</span> <span class="py-builtin">open</span>(<span class="py-string">'myfile.txt'</span>, <span class="py-string">'r'</span>, <span class="py-parameter">encoding</span><span class="py-operator">=</span><span class="py-string">'utf-8'</span>)
    <span class="py-parameter">content</span> <span class="py-operator">=</span> f.read()
    <span class="py-comment"># 파일 처리 작업...</span>
<span class="py-keyword">except</span> <span class="py-builtin">FileNotFoundError</span>:
    <span class="py-builtin">print</span>(<span class="py-string">"파일을 찾을 수 없습니다."</span>)
<span class="py-keyword">except</span> <span class="py-builtin">IOError</span> <span class="py-keyword">as</span> e:
    <span class="py-builtin">print</span>(<span class="py-string">f"파일 읽기 오류: {e}"</span>)
<span class="py-keyword">else</span>: <span class="py-comment"># try 블록에서 예외가 발생하지 않았을 때 실행</span>
    <span class="py-builtin">print</span>(<span class="py-string">"파일을 성공적으로 읽었습니다."</span>)
    <span class="py-comment"># print(content)</span>
<span class="py-keyword">finally</span>: <span class="py-comment"># 항상 실행됨</span>
    <span class="py-keyword">if</span> <span class="py-parameter">f</span>: <span class="py-comment"># 파일 객체가 생성되었다면 (열기 성공했다면)</span>
        f.close() <span class="py-comment"># 파일을 닫음</span>
        <span class="py-builtin">print</span>(<span class="py-string">"파일 리소스를 해제했습니다."</span>)</code></pre>
    <p class="note">파일 처리 시에는 <code>with open(...) as f:</code> 구문을 사용하면 <code>finally</code> 블록에서 <code>f.close()</code>를 호출할 필요 없이 자동으로 파일을 닫아주므로 더 안전하고 간결합니다.</p>

    <h3 id="exceptions-raise">예외 발생시키기 (raise)</h3>
    <p><code>raise</code> 키워드를 사용하여 특정 조건에서 의도적으로 예외를 발생시킬 수 있습니다. 주로 함수나 메서드에서 유효하지 않은 입력값이나 예상치 못한 상황을 알릴 때 사용됩니다.</p>
    <pre><code class="language-python"><span class="py-keyword">def</span> <span class="py-function">validate_age</span>(<span class="py-parameter">age</span>):
    <span class="py-keyword">if</span> <span class="py-keyword">not</span> <span class="py-builtin">isinstance</span>(<span class="py-parameter">age</span>, <span class="py-builtin">int</span>):
        <span class="py-keyword">raise</span> <span class="py-builtin">TypeError</span>(<span class="py-string">"나이는 정수여야 합니다."</span>)
    <span class="py-keyword">if</span> <span class="py-parameter">age</span> <span class="py-operator">&lt;</span> <span class="py-number">0</span>:
        <span class="py-keyword">raise</span> <span class="py-builtin">ValueError</span>(<span class="py-string">"나이는 음수일 수 없습니다."</span>)
    <span class="py-builtin">print</span>(<span class="py-string">"유효한 나이입니다."</span>)

<span class="py-keyword">try</span>:
    <span class="py-function">validate_age</span>(<span class="py-number">25</span>)
    <span class="py-comment"># validate_age("thirty") # TypeError 발생</span>
    <span class="py-function">validate_age</span>(<span class="py-operator">-</span><span class="py-number">5</span>) <span class="py-comment"># ValueError 발생</span>
<span class="py-keyword">except</span> (<span class="py-builtin">TypeError</span>, <span class="py-builtin">ValueError</span>) <span class="py-keyword">as</span> e:
    <span class="py-builtin">print</span>(<span class="py-string">f"검증 오류: {e}"</span>)</code></pre>

    <h3 id="exceptions-custom">사용자 정의 예외</h3>
    <p>Python의 내장 예외 클래스(<code>Exception</code> 또는 더 구체적인 예외 클래스)를 상속받아 애플리케이션의 특정 오류 상황을 나타내는 사용자 정의 예외 클래스를 만들 수 있습니다. 이를 통해 에러 처리를 더 의미있고 구조적으로 만들 수 있습니다.</p>
    <pre><code class="language-python"><span class="py-comment"># 사용자 정의 예외 클래스</span>
<span class="py-keyword">class</span> <span class="py-class">InsufficientFundsError</span>(<span class="py-builtin">Exception</span>):
    <span class="py-keyword">def</span> <span class="py-function">__init__</span>(<span class="py-parameter">self</span>, <span class="py-parameter">balance</span>, <span class="py-parameter">amount</span>):
        <span class="py-parameter">self</span>.balance <span class="py-operator">=</span> <span class="py-parameter">balance</span>
        <span class="py-parameter">self</span>.amount <span class="py-operator">=</span> <span class="py-parameter">amount</span>
        <span class="py-parameter">message</span> <span class="py-operator">=</span> <span class="py-string">f"잔액 부족: 현재 잔액 {balance}, 출금 요청액 {amount}"</span>
        <span class="py-builtin">super</span>().<span class="py-function">__init__</span>(<span class="py-parameter">message</span>) <span class="py-comment"># 부모 Exception 클래스의 생성자 호출</span>

<span class="py-keyword">def</span> <span class="py-function">withdraw</span>(<span class="py-parameter">balance</span>, <span class="py-parameter">amount</span>):
    <span class="py-keyword">if</span> <span class="py-parameter">amount</span> <span class="py-operator">&gt;</span> <span class="py-parameter">balance</span>:
        <span class="py-keyword">raise</span> <span class="py-function">InsufficientFundsError</span>(<span class="py-parameter">balance</span>, <span class="py-parameter">amount</span>) <span class="py-comment"># 사용자 정의 예외 발생</span>
    <span class="py-builtin">print</span>(<span class="py-string">"출금 성공!"</span>)
    <span class="py-keyword">return</span> <span class="py-parameter">balance</span> <span class="py-operator">-</span> <span class="py-parameter">amount</span>

<span class="py-keyword">try</span>:
    <span class="py-parameter">current_balance</span> <span class="py-operator">=</span> <span class="py-number">10000</span>
    <span class="py-function">withdraw</span>(<span class="py-parameter">current_balance</span>, <span class="py-number">15000</span>)
<span class="py-keyword">except</span> <span class="py-function">InsufficientFundsError</span> <span class="py-keyword">as</span> e:
    <span class="py-builtin">print</span>(<span class="py-string">f"출금 오류: {e}"</span>) <span class="py-comment"># 사용자 정의 예외 메시지 출력</span>
    <span class="py-comment"># print(f"부족 금액: {e.amount - e.balance}") # 예외 객체의 추가 속성 접근 가능</span>
</code></pre>
</section>

<section id="python-oop">
    <h2>객체 지향 프로그래밍 (OOP) 기초</h2>
    <p>객체 지향 프로그래밍(Object-Oriented Programming)은 프로그램을 여러 개의 독립적인 객체(Object)들의 상호작용으로 보는 관점입니다. 객체는 데이터(속성)와 그 데이터를 처리하는 함수(메서드)를 하나로 묶은 것입니다.</p>

    <h3 id="oop-concepts">OOP 개념 소개</h3>
    <ul>
        <li><strong>클래스 (Class):</strong> 객체를 만들기 위한 설계도 또는 틀. 속성과 메서드를 정의합니다.</li>
        <li><strong>객체 (Object / Instance):</strong> 클래스를 바탕으로 메모리에 생성된 실체. 클래스에 정의된 속성과 메서드를 가집니다.</li>
        <li><strong>캡슐화 (Encapsulation):</strong> 데이터(속성)와 기능(메서드)을 하나로 묶고, 외부에서의 직접적인 데이터 접근을 제한하여 정보 은닉을 구현하는 것 (Python에서는 접근 제어자보다는 네이밍 컨벤션(<code>_</code>, <code>__</code>)을 주로 사용).</li>
        <li><strong>상속 (Inheritance):</strong> 기존 클래스(부모)의 속성과 메서드를 물려받아 새로운 클래스(자식)를 만드는 것. 코드 재사용성 증대.</li>
        <li><strong>다형성 (Polymorphism):</strong> 동일한 이름의 메서드가 객체(클래스)에 따라 다른 방식으로 동작하는 것 (메서드 오버라이딩 등).</li>
    </ul>

    <h3 id="oop-classes-objects">클래스(class)와 객체(object)</h3>
    <p><code>class</code> 키워드로 클래스를 정의하고, <code>클래스이름()</code> 형태로 객체(인스턴스)를 생성합니다.</p>
    <pre><code class="language-python"><span class="py-comment"># 클래스 정의</span>
<span class="py-keyword">class</span> <span class="py-class">Dog</span>:
    <span class="py-comment"># 클래스 속성 (모든 Dog 객체가 공유)</span>
    <span class="py-parameter">species</span> <span class="py-operator">=</span> <span class="py-string">"Canis familiaris"</span>

    <span class="py-comment"># 생성자 메서드 (__init__)</span>
    <span class="py-keyword">def</span> <span class="py-function">__init__</span>(<span class="py-parameter">self</span>, <span class="py-parameter">name</span>, <span class="py-parameter">age</span>):
        <span class="py-comment"># 인스턴스 속성 (각 Dog 객체마다 고유)</span>
        <span class="py-parameter">self</span>.name <span class="py-operator">=</span> <span class="py-parameter">name</span>
        <span class="py-parameter">self</span>.age <span class="py-operator">=</span> <span class="py-parameter">age</span>
        <span class="py-builtin">print</span>(<span class="py-string">f"{self.name} 강아지 생성!"</span>)

    <span class="py-comment"># 인스턴스 메서드</span>
    <span class="py-keyword">def</span> <span class="py-function">bark</span>(<span class="py-parameter">self</span>):
        <span class="py-builtin">print</span>(<span class="py-string">f"{self.name} says: 멍멍!"</span>)

    <span class="py-keyword">def</span> <span class="py-function">get_info</span>(<span class="py-parameter">self</span>):
        <span class="py-keyword">return</span> <span class="py-string">f"{self.name} is a {self.species} aged {self.age}."</span>

<span class="py-comment"># 객체(인스턴스) 생성</span>
<span class="py-parameter">my_dog</span> <span class="py-operator">=</span> <span class="py-function">Dog</span>(<span class="py-string">"Buddy"</span>, <span class="py-number">3</span>) <span class="py-comment"># __init__ 메서드 자동 호출</span>
<span class="py-parameter">your_dog</span> <span class="py-operator">=</span> <span class="py-function">Dog</span>(<span class="py-string">"Lucy"</span>, <span class="py-number">5</span>)

<span class="py-comment"># 속성 접근 및 메서드 호출</span>
<span class="py-builtin">print</span>(<span class="py-parameter">my_dog</span>.name)    <span class="py-comment"># Buddy</span>
<span class="py-builtin">print</span>(<span class="py-parameter">your_dog</span>.age)   <span class="py-comment"># 5</span>
<span class="py-builtin">print</span>(<span class="py-parameter">my_dog</span>.species) <span class="py-comment"># Canis familiaris (클래스 속성)</span>
<span class="py-parameter">my_dog</span>.bark()     <span class="py-comment"># Buddy says: 멍멍!</span>
<span class="py-builtin">print</span>(<span class="py-parameter">your_dog</span>.get_info()) <span class="py-comment"># Lucy is a Canis familiaris aged 5.</span></code></pre>

    <h3 id="oop-init">생성자 (__init__)</h3>
    <p><code>__init__</code> 메서드는 클래스로부터 객체가 생성될 때 자동으로 호출되는 특별한 메서드(생성자)입니다. 주로 객체가 가질 속성들을 초기화하는 역할을 합니다. 첫 번째 매개변수는 항상 <code>self</code>여야 합니다.</p>

    <h3 id="oop-attributes">속성 (인스턴스 변수, 클래스 변수)</h3>
    <ul>
        <li><strong>인스턴스 변수 (Instance Variable):</strong> 각 객체(인스턴스)마다 독립적으로 가지는 속성입니다. 일반적으로 <code>__init__</code> 메서드 내에서 <code>self.variable_name = value</code> 형태로 정의하고 할당합니다.</li>
        <li><strong>클래스 변수 (Class Variable):</strong> 해당 클래스의 모든 객체가 공유하는 속성입니다. 클래스 정의 바로 아래에 선언합니다. 클래스 이름(<code>ClassName.variable</code>) 또는 객체(<code>instance.variable</code> - 단, 인스턴스에 동일 이름 속성 없을 시)를 통해 접근 가능합니다.</li>
    </ul>
     <pre><code class="language-python"><span class="py-keyword">class</span> <span class="py-class">Circle</span>:
    <span class="py-parameter">pi</span> <span class="py-operator">=</span> <span class="py-number">3.14159</span> <span class="py-comment"># 클래스 변수 (모든 Circle 객체가 공유)</span>

    <span class="py-keyword">def</span> <span class="py-function">__init__</span>(<span class="py-parameter">self</span>, <span class="py-parameter">radius</span>):
        <span class="py-parameter">self</span>.radius <span class="py-operator">=</span> <span class="py-parameter">radius</span> <span class="py-comment"># 인스턴스 변수 (각 객체마다 다름)</span>

    <span class="py-keyword">def</span> <span class="py-function">get_area</span>(<span class="py-parameter">self</span>):
        <span class="py-comment"># 클래스 변수는 self 또는 클래스 이름으로 접근 가능</span>
        <span class="py-keyword">return</span> <span class="py-parameter">self</span>.pi <span class="py-operator">*</span> (<span class="py-parameter">self</span>.radius <span class="py-operator"></span> <span class="py-number">2</span>)
        <span class="py-comment"># return Circle.pi * (self.radius  2)</span>

<span class="py-parameter">c1</span> <span class="py-operator">=</span> <span class="py-function">Circle</span>(<span class="py-number">5</span>)
<span class="py-parameter">c2</span> <span class="py-operator">=</span> <span class="py-function">Circle</span>(<span class="py-number">10</span>)

<span class="py-builtin">print</span>(<span class="py-string">f"Circle 1 Radius: {c1.radius}, Area: {c1.get_area()}"</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"Circle 2 Radius: {c2.radius}, Area: {c2.get_area()}"</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"Shared PI value: {Circle.pi}"</span>) <span class="py-comment"># 클래스 이름으로 접근</span></code></pre>

    <h3 id="oop-methods">메서드 (인스턴스 메서드, self)</h3>
    <p>메서드는 클래스 내에 정의된 함수입니다. 객체의 동작을 정의합니다.</p>
    <p><strong>인스턴스 메서드 (Instance Method):</strong> 가장 일반적인 메서드 형태로, 첫 번째 매개변수로 항상 <code>self</code>를 받습니다. <code>self</code>는 메서드를 호출한 객체(인스턴스) 자신을 참조하며, 이를 통해 해당 객체의 다른 속성이나 메서드에 접근할 수 있습니다.</p>
    <p>(정적 메서드(<code>@staticmethod</code>)와 클래스 메서드(<code>@classmethod</code>)도 있지만 여기서는 다루지 않습니다.)</p>

    <h3 id="oop-inheritance">상속 (Inheritance)</h3>
    <p>상속은 기존 클래스(부모 클래스, Superclass)의 기능을 물려받아 새로운 클래스(자식 클래스, Subclass)를 만드는 것입니다. 코드 재사용과 계층 구조화에 유용합니다.</p>
    <p>클래스 정의 시 괄호 안에 부모 클래스 이름을 명시합니다: <code>class ChildClass(ParentClass):</code></p>
    <p>자식 클래스는 부모 클래스의 모든 속성과 메서드(private 멤버 <code>__</code> 제외)를 상속받으며, 자신만의 속성이나 메서드를 추가하거나 부모의 메서드를 재정의(Override)할 수 있습니다.</p>

    <h3 id="oop-super">super() 함수</h3>
    <p>자식 클래스에서 부모 클래스의 메서드(특히 생성자 <code>__init__</code>)를 호출해야 할 때 <code>super()</code> 함수를 사용합니다.</p>
    <pre><code class="language-python"><span class="py-keyword">class</span> <span class="py-class">Animal</span>: <span class="py-comment"># 부모 클래스</span>
    <span class="py-keyword">def</span> <span class="py-function">__init__</span>(<span class="py-parameter">self</span>, <span class="py-parameter">name</span>):
        <span class="py-parameter">self</span>.name <span class="py-operator">=</span> <span class="py-parameter">name</span>
        <span class="py-builtin">print</span>(<span class="py-string">"Animal created."</span>)

    <span class="py-keyword">def</span> <span class="py-function">eat</span>(<span class="py-parameter">self</span>):
        <span class="py-builtin">print</span>(<span class="py-string">f"{self.name} is eating."</span>)

    <span class="py-keyword">def</span> <span class="py-function">speak</span>(<span class="py-parameter">self</span>):
        <span class="py-builtin">print</span>(<span class="py-string">"Animal makes a sound."</span>)

<span class="py-keyword">class</span> <span class="py-class">Cat</span>(<span class="py-class">Animal</span>): <span class="py-comment"># 자식 클래스 (Animal 상속)</span>
    <span class="py-keyword">def</span> <span class="py-function">__init__</span>(<span class="py-parameter">self</span>, <span class="py-parameter">name</span>, <span class="py-parameter">breed</span>):
        <span class="py-builtin">super</span>().<span class="py-function">__init__</span>(<span class="py-parameter">name</span>) <span class="py-comment"># 부모 클래스의 __init__ 호출</span>
        <span class="py-parameter">self</span>.breed <span class="py-operator">=</span> <span class="py-parameter">breed</span>
        <span class="py-builtin">print</span>(<span class="py-string">"Cat created."</span>)

    <span class="py-comment"># 부모 메서드 오버라이딩</span>
    <span class="py-keyword">def</span> <span class="py-function">speak</span>(<span class="py-parameter">self</span>):
        <span class="py-builtin">print</span>(<span class="py-string">f"{self.name} says: Meow!"</span>)

    <span class="py-comment"># 자식 클래스 고유 메서드</span>
    <span class="py-keyword">def</span> <span class="py-function">purr</span>(<span class="py-parameter">self</span>):
        <span class="py-builtin">print</span>(<span class="py-string">f"{self.name} is purring..."</span>)

<span class="py-parameter">my_cat</span> <span class="py-operator">=</span> <span class="py-function">Cat</span>(<span class="py-string">"Nabi"</span>, <span class="py-string">"Korean Shorthair"</span>)
<span class="py-parameter">my_cat</span>.eat()   <span class="py-comment"># 부모 메서드 사용</span>
<span class="py-parameter">my_cat</span>.speak() <span class="py-comment"># 오버라이딩된 자식 메서드 사용</span>
<span class="py-parameter">my_cat</span>.purr()  <span class="py-comment"># 자식 고유 메서드 사용</span>
<span class="py-builtin">print</span>(<span class="py-string">f"{my_cat.name}'s breed: {my_cat.breed}"</span>)</code></pre>

    <h3 id="oop-special-methods">특수 메서드 (Dunder Methods)</h3>
    <p>밑줄 두 개(<code>__</code>)로 시작하고 끝나는 특별한 이름의 메서드들을 특수 메서드(Special Methods) 또는 던더 메서드(Dunder Methods)라고 부릅니다. 이 메서드들은 Python의 내장 함수(<code>len()</code>, <code>str()</code> 등)나 연산자(<code>+</code>, <code>[]</code> 등)가 객체에 사용될 때 자동으로 호출되어 해당 동작을 정의합니다.</p>
    <ul>
        <li><code>__init__(self, ...)</code>: 생성자 (객체 생성 시).</li>
        <li><code>__str__(self)</code>: <code>print()</code> 함수나 <code>str()</code> 함수로 객체를 출력할 때 사용될 사용자 친화적인 문자열 반환.</li>
        <li><code>__repr__(self)</code>: 개발자를 위한 객체의 공식적인 문자열 표현 반환 (REPL 등에서 객체 출력 시). <code>__str__</code>이 없으면 <code>__repr__</code>이 대신 사용됨.</li>
        <li><code>__len__(self)</code>: <code>len()</code> 함수로 객체의 길이를 구할 때 호출됨.</li>
        <li><code>__getitem__(self, key)</code>: 인덱싱(<code>obj[key]</code>) 사용 시 호출됨.</li>
        <li><code>__add__(self, other)</code>: 덧셈 연산자(<code>+</code>) 사용 시 호출됨.</li>
        <li>기타 등등 (<code>__eq__</code>, <code>__iter__</code>, <code>__call__</code>...)</li>
    </ul>
     <pre><code class="language-python"><span class="py-keyword">class</span> <span class="py-class">Vector</span>:
    <span class="py-keyword">def</span> <span class="py-function">__init__</span>(<span class="py-parameter">self</span>, <span class="py-parameter">x</span>, <span class="py-parameter">y</span>):
        <span class="py-parameter">self</span>.x <span class="py-operator">=</span> <span class="py-parameter">x</span>
        <span class="py-parameter">self</span>.y <span class="py-operator">=</span> <span class="py-parameter">y</span>

    <span class="py-keyword">def</span> <span class="py-function">__str__</span>(<span class="py-parameter">self</span>): <span class="py-comment"># print() 출력용</span>
        <span class="py-keyword">return</span> <span class="py-string">f"Vector({self.x}, {self.y})"</span>

    <span class="py-keyword">def</span> <span class="py-function">__repr__</span>(<span class="py-parameter">self</span>): <span class="py-comment"># 개발자용/REPL 출력용</span>
         <span class="py-keyword">return</span> <span class="py-string">f"Vector(x={self.x}, y={self.y})"</span>

    <span class="py-keyword">def</span> <span class="py-function">__add__</span>(<span class="py-parameter">self</span>, <span class="py-parameter">other</span>): <span class="py-comment"># + 연산자 오버로딩</span>
        <span class="py-keyword">if</span> <span class="py-builtin">isinstance</span>(<span class="py-parameter">other</span>, <span class="py-class">Vector</span>):
            <span class="py-keyword">return</span> <span class="py-function">Vector</span>(<span class="py-parameter">self</span>.x <span class="py-operator">+</span> <span class="py-parameter">other</span>.x, <span class="py-parameter">self</span>.y <span class="py-operator">+</span> <span class="py-parameter">other</span>.y)
        <span class="py-keyword">return</span> <span class="py-builtin">NotImplemented</span> <span class="py-comment"># 다른 타입과의 덧셈은 지원 안 함</span>

    <span class="py-keyword">def</span> <span class="py-function">__len__</span>(<span class="py-parameter">self</span>): <span class="py-comment"># len() 함수</span>
        <span class="py-comment"># 예시로 벡터의 크기(magnitude)의 정수 부분을 반환</span>
        <span class="py-keyword">import</span> math
        <span class="py-keyword">return</span> <span class="py-builtin">int</span>(math.sqrt(<span class="py-parameter">self</span>.x<span class="py-operator"></span><span class="py-number">2</span> <span class="py-operator">+</span> <span class="py-parameter">self</span>.y<span class="py-operator"></span><span class="py-number">2</span>))

<span class="py-parameter">v1</span> <span class="py-operator">=</span> <span class="py-function">Vector</span>(<span class="py-number">2</span>, <span class="py-number">3</span>)
<span class="py-parameter">v2</span> <span class="py-operator">=</span> <span class="py-function">Vector</span>(<span class="py-number">4</span>, <span class="py-number">5</span>)

<span class="py-builtin">print</span>(<span class="py-parameter">v1</span>) <span class="py-comment"># Vector(2, 3) (__str__ 호출됨)</span>
<span class="py-builtin">print</span>(<span class="py-builtin">repr</span>(<span class="py-parameter">v1</span>)) <span class="py-comment"># Vector(x=2, y=3) (__repr__ 호출됨)</span>
<span class="py-parameter">v3</span> <span class="py-operator">=</span> <span class="py-parameter">v1</span> <span class="py-operator">+</span> <span class="py-parameter">v2</span> <span class="py-comment"># __add__ 호출됨</span>
<span class="py-builtin">print</span>(<span class="py-parameter">v3</span>) <span class="py-comment"># Vector(6, 8)</span>
<span class="py-builtin">print</span>(<span class="py-builtin">len</span>(<span class="py-parameter">v1</span>)) <span class="py-comment"># 3 (sqrt(2^2 + 3^2) = sqrt(13) approx 3.6 -> int(3.6) = 3) (__len__ 호출됨)</span>
</code></pre>

    <h3 id="oop-more">(간략) 정보 은닉, 데코레이터(@property)</h3>
    <p>Python의 OOP에는 더 많은 개념이 있습니다:</p>
    <ul>
        <li><strong>정보 은닉 (Information Hiding):</strong> 속성 이름 앞에 밑줄(<code>_</code> 또는 <code>__</code>)을 붙여 외부에서의 직접 접근을 지양하도록 권고하거나(<code>_</code>) 이름 맹글링(<code>__</code>)을 통해 접근을 더 어렵게 만듭니다. 직접 접근 대신 getter/setter 메서드를 제공하여 캡슐화를 강화할 수 있습니다.</li>
        <li><strong><code>@property</code> 데코레이터:</strong> 메서드를 속성처럼 접근할 수 있게 만들어주는 기능입니다. getter, setter, deleter를 정의하여 속성 접근을 제어하는 데 유용합니다.</li>
        <li>기타: 추상 클래스(<code>abc</code> 모듈), 다중 상속, 믹스인(Mixin) 등.</li>
    </ul>
</section>

<section id="python-stdlib-intro">
    <h2>주요 표준 라이브러리 활용</h2>
    <p>Python 표준 라이브러리의 유용한 모듈 몇 가지 사용법을 간단히 살펴봅니다.</p>

    <h3 id="stdlib-datetime">datetime (날짜와 시간)</h3>
    <p>날짜와 시간 데이터를 생성하고 조작하는 기능을 제공합니다.</p>
    <pre><code class="language-python"><span class="py-keyword">import</span> datetime

<span class="py-comment"># 현재 날짜와 시간</span>
<span class="py-parameter">now</span> <span class="py-operator">=</span> datetime.datetime.now()
<span class="py-builtin">print</span>(<span class="py-string">f"현재 시간: {now}"</span>)

<span class="py-comment"># 특정 날짜/시간 생성</span>
<span class="py-parameter">specific_date</span> <span class="py-operator">=</span> datetime.datetime(<span class="py-number">2025</span>, <span class="py-number">12</span>, <span class="py-number">25</span>, <span class="py-number">10</span>, <span class="py-number">30</span>, <span class="py-number">0</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"특정 시간: {specific_date}"</span>)

<span class="py-comment"># 날짜/시간 포맷팅 (strftime)</span>
<span class="py-builtin">print</span>(<span class="py-string">"포맷팅:"</span>, now.strftime(<span class="py-string">"%Y년 %m월 %d일 %H시 %M분 %S초"</span>))

<span class="py-comment"># 문자열로부터 날짜/시간 파싱 (strptime)</span>
<span class="py-parameter">date_str</span> <span class="py-operator">=</span> <span class="py-string">"2024-01-15"</span>
<span class="py-parameter">parsed_date</span> <span class="py-operator">=</span> datetime.datetime.strptime(<span class="py-parameter">date_str</span>, <span class="py-string">"%Y-%m-%d"</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"파싱된 날짜: {parsed_date}"</span>)

<span class="py-comment"># 날짜/시간 연산 (timedelta)</span>
<span class="py-parameter">one_day</span> <span class="py-operator">=</span> datetime.timedelta(<span class="py-parameter">days</span><span class="py-operator">=</span><span class="py-number">1</span>)
<span class="py-parameter">tomorrow</span> <span class="py-operator">=</span> <span class="py-parameter">now</span> <span class="py-operator">+</span> <span class="py-parameter">one_day</span>
<span class="py-builtin">print</span>(<span class="py-string">f"내일: {tomorrow}"</span>)</code></pre>

    <h3 id="stdlib-math">math (수학 함수)</h3>
    <p>수학적인 계산을 위한 함수와 상수를 제공합니다.</p>
     <pre><code class="language-python"><span class="py-keyword">import</span> math

<span class="py-builtin">print</span>(<span class="py-string">f"원주율 (pi): {math.pi}"</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"자연 상수 (e): {math.e}"</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"제곱근 (sqrt(16)): {math.sqrt(16)}"</span>) <span class="py-comment"># 4.0</span>
<span class="py-builtin">print</span>(<span class="py-string">f"거듭제곱 (pow(2, 10)): {math.pow(2, 10)}"</span>) <span class="py-comment"># 1024.0</span>
<span class="py-builtin">print</span>(<span class="py-string">f"올림 (ceil(4.2)): {math.ceil(4.2)}"</span>) <span class="py-comment"># 5</span>
<span class="py-builtin">print</span>(<span class="py-string">f"내림 (floor(4.8)): {math.floor(4.8)}"</span>) <span class="py-comment"># 4</span>
<span class="py-builtin">print</span>(<span class="py-string">f"로그 (log10(100)): {math.log10(100)}"</span>) <span class="py-comment"># 2.0</span></code></pre>

    <h3 id="stdlib-random">random (난수 생성)</h3>
    <p>무작위 수(난수)를 생성하고 시퀀스를 무작위로 섞는 등의 기능을 제공합니다.</p>
    <pre><code class="language-python"><span class="py-keyword">import</span> random

<span class="py-comment"># 0.0 이상 1.0 미만의 난수</span>
<span class="py-builtin">print</span>(<span class="py-string">"Random float:"</span>, random.random())

<span class="py-comment"># 지정된 범위의 정수 난수 (a <= N <= b)</span>
<span class="py-builtin">print</span>(<span class="py-string">"Random integer (1-10):"</span>, random.randint(<span class="py-number">1</span>, <span class="py-number">10</span>))

<span class="py-comment"># 시퀀스에서 임의의 요소 선택</span>
<span class="py-parameter">my_list</span> <span class="py-operator">=</span> [<span class="py-string">'a'</span>, <span class="py-string">'b'</span>, <span class="py-string">'c'</span>, <span class="py-string">'d'</span>, <span class="py-string">'e'</span>]
<span class="py-builtin">print</span>(<span class="py-string">"Random choice:"</span>, random.choice(<span class="py-parameter">my_list</span>))

<span class="py-comment"># 시퀀스 요소 순서 섞기 (in-place)</span>
<span class="py-builtin">print</span>(<span class="py-string">"Original list:"</span>, <span class="py-parameter">my_list</span>)
random.shuffle(<span class="py-parameter">my_list</span>)
<span class="py-builtin">print</span>(<span class="py-string">"Shuffled list:"</span>, <span class="py-parameter">my_list</span>)

<span class="py-comment"># 시퀀스에서 중복 없이 여러 개 요소 선택</span>
<span class="py-builtin">print</span>(<span class="py-string">"Random sample (k=3):"</span>, random.sample(<span class="py-parameter">my_list</span>, <span class="py-number">3</span>))</code></pre>

    <h3 id="stdlib-os">os (운영체제 인터페이스)</h3>
    <p>파일 시스템 접근, 환경 변수, 프로세스 관리 등 운영체제와 상호작용하는 기능을 제공합니다. 파일 경로 처리는 <code>os.path</code> 하위 모듈을 사용하는 것이 좋습니다.</p>
     <pre><code class="language-python"><span class="py-keyword">import</span> os

<span class="py-builtin">print</span>(<span class="py-string">f"현재 작업 디렉토리: {os.getcwd()}"</span>)

<span class="py-comment"># 디렉토리 목록</span>
<span class="py-comment"># print("현재 디렉토리 목록:", os.listdir('.'))</span>

<span class="py-comment"># 환경 변수 읽기</span>
<span class="py-parameter">path_env</span> <span class="py-operator">=</span> os.environ.get(<span class="py-string">'PATH'</span>)
<span class="py-comment"># print(f"PATH 환경 변수 (일부): {path_env[:50]}...")</span>

<span class="py-comment"># 파일/디렉토리 존재 확인 및 경로 조작 (os.path 사용 권장)</span>
<span class="py-parameter">file_path</span> <span class="py-operator">=</span> os.path.join(os.getcwd(), <span class="py-string">'my_file.txt'</span>) <span class="py-comment"># OS 호환 경로 생성</span>
<span class="py-builtin">print</span>(<span class="py-string">f"파일 경로: {file_path}"</span>)
<span class="py-builtin">print</span>(<span class="py-string">f"파일 존재 여부: {os.path.exists(file_path)}"</span>)
</code></pre>

    <h3 id="stdlib-json">json (JSON 데이터 처리)</h3>
    <p>JSON(JavaScript Object Notation) 형식의 데이터를 파싱하거나 Python 객체를 JSON 문자열로 변환하는 기능을 제공합니다.</p>
    <ul>
        <li><code>json.dumps(obj, indent=None)</code>: Python 객체(<code>dict</code>, <code>list</code> 등)를 JSON 형식의 문자열로 변환합니다. <code>indent</code>로 들여쓰기 지정 가능.</li>
        <li><code>json.dump(obj, fp)</code>: Python 객체를 JSON 형식으로 파일 객체(<code>fp</code>)에 씁니다.</li>
        <li><code>json.loads(s)</code>: JSON 형식의 문자열<code>s</code>를 Python 객체로 파싱합니다.</li>
        <li><code>json.load(fp)</code>: 파일 객체(<code>fp</code>)에서 JSON 데이터를 읽어 Python 객체로 파싱합니다.</li>
    </ul>
     <pre><code class="language-python"><span class="py-keyword">import</span> json

<span class="py-parameter">person_data</span> <span class="py-operator">=</span> {
    <span class="py-string">"name"</span>: <span class="py-string">"David"</span>,
    <span class="py-string">"age"</span>: 28,
    <span class="py-string">"isStudent"</span>: <span class="py-keyword">False</span>,
    <span class="py-string">"courses"</span>: [<span class="py-string">"Python"</span>, <span class="py-string">"Data Analysis"</span>],
    <span class="py-string">"address"</span>: <span class="py-keyword">None</span>
}

<span class="py-comment"># Python 객체 -> JSON 문자열</span>
<span class="py-parameter">json_string</span> <span class="py-operator">=</span> json.dumps(<span class="py-parameter">person_data</span>, <span class="py-parameter">indent</span><span class="py-operator">=</span><span class="py-number">4</span>, <span class="py-parameter">ensure_ascii</span><span class="py-operator">=</span><span class="py-keyword">False</span>) <span class="py-comment"># indent: 들여쓰기, ensure_ascii=False: 한글 등 유지</span>
<span class="py-builtin">print</span>(<span class="py-string">"--- JSON 문자열 ---"</span>)
<span class="py-builtin">print</span>(<span class="py-parameter">json_string</span>)

<span class="py-comment"># JSON 문자열 -> Python 객체</span>
<span class="py-parameter">json_data_str</span> <span class="py-operator">=</span> <span class="py-string">'{"id": 101, "item": "Laptop", "price": 1200.50}'</span>
<span class="py-parameter">product_data</span> <span class="py-operator">=</span> json.loads(<span class="py-parameter">json_data_str</span>)
<span class="py-builtin">print</span>(<span class="py-string">"\n--- 파싱된 Python 객체 ---"</span>)
<span class="py-builtin">print</span>(<span class="py-parameter">product_data</span>)
<span class="py-builtin">print</span>(<span class="py-string">"Item Name:"</span>, <span class="py-parameter">product_data</span>[<span class="py-string">'item'</span>])</code></pre>
</section>

<section id="python-venv">
    <h2>가상 환경 (Virtual Environments)</h2>
    <p>가상 환경은 프로젝트별로 독립된 Python 실행 환경 및 라이브러리 설치 공간을 제공하는 방법입니다. 여러 프로젝트를 진행할 때 각 프로젝트가 요구하는 패키지 버전 충돌을 방지하고 의존성을 관리하는 데 필수적입니다.</p>
    <p>Python 3.3 버전부터 내장된 <code>venv</code> 모듈을 사용하는 것이 표준입니다.</p>
    <h4>가상 환경 생성 및 활성화:</h4>
    <ol>
        <li><strong>생성 (터미널에서):</strong> 프로젝트 디렉토리 내에서 다음 명령 실행 (`myenv`는 원하는 가상 환경 이름).
            <pre><code class="language-bash"><span class="py-comment"># Linux/macOS</span>
python3 -m venv myenv

<span class="py-comment"># Windows</span>
python -m venv myenv</code></pre>
            <p>현재 디렉토리에 `myenv`라는 폴더가 생성되고 그 안에 독립된 Python 환경이 구성됩니다.</p>
        </li>
        <li><strong>활성화 (터미널에서):</strong> 가상 환경을 사용하려면 활성화해야 합니다.
            <pre><code class="language-bash"><span class="py-comment"># Linux/macOS (bash/zsh)</span>
source myenv/bin/activate

<span class="py-comment"># Windows (Command Prompt)</span>
myenv\Scripts\activate.bat

<span class="py-comment"># Windows (PowerShell)</span>
.\myenv\Scripts\Activate.ps1
<span class="py-comment"># (PowerShell 실행 정책 오류 시: Set-ExecutionPolicy RemoteSigned -Scope CurrentUser 실행 후 시도)</span></code></pre>
            <p>활성화되면 터미널 프롬프트 앞에 `(myenv)` 와 같이 가상 환경 이름이 표시됩니다. 이제 `pip install`로 설치하는 패키지는 이 가상 환경 내에만 설치됩니다.</p>
        </li>
        <li><strong>비활성화 (터미널에서):</strong> 가상 환경 사용을 마치려면 다음 명령 실행.
            <pre><code class="language-bash">deactivate</code></pre>
        </li>
    </ol>
    <h4>의존성 관리 (<code>requirements.txt</code>):</h4>
    <p>프로젝트에 필요한 패키지 목록을 <code>requirements.txt</code> 파일에 기록하여 관리하는 것이 일반적입니다.</p>
    <ul>
        <li>현재 가상 환경에 설치된 패키지 목록 저장:
            <pre><code class="language-bash">pip freeze > requirements.txt</code></pre>
        </li>
        <li><code>requirements.txt</code> 파일로부터 모든 패키지 설치:
            <pre><code class="language-bash">pip install -r requirements.txt</code></pre>
        </li>
    </ul>
    <p>프로젝트를 시작할 때는 항상 가상 환경을 먼저 만들고 활성화한 후 필요한 패키지를 설치하는 습관을 들이는 것이 좋습니다.</p>
</section>

<section id="python-next-steps">
    <h2>다음 단계 및 추가 학습</h2>
    <p>축하합니다! Python의 기본적인 문법부터 데이터 구조, 함수, 모듈, 파일 처리, 예외 처리, 객체 지향 기초, 주요 표준 라이브러리, 가상 환경까지 폭넓은 내용을 학습했습니다. 이 지식은 여러분이 Python을 사용하여 다양한 종류의 프로그램을 개발하는 데 훌륭한 기반이 될 것입니다.</p>
    <p>Python의 세계는 이것보다 훨씬 넓습니다. 여기서 멈추지 말고 관심 있는 분야를 중심으로 더 깊이 탐구해 보세요!</p>
    <h4>추천 학습 분야:</h4>
    <ul>
        <li><strong>웹 개발:
            <ul>
                <li><strong>Flask: 가볍고 유연한 마이크로 프레임워크. API 개발이나 작은 웹 앱에 적합.</li>
                <li><strong>Django: 풀스택 웹 프레임워크. 관리자 페이지, ORM 등 많은 기능 내장. 복잡하고 큰 웹 애플리케이션 개발에 적합.</li>
                <li>FastAPI: 현대적이고 빠른 고성능 API 개발 프레임워크.</li>
            </ul>
        </li>
        <li><strong>데이터 과학 및 머신러닝:
            <ul>
                <li><strong>NumPy: 고성능 수치 계산 라이브러리 (배열, 행렬 연산).</li>
                <li><strong>Pandas: 데이터 분석 및 조작을 위한 강력한 라이브러리 (DataFrame 등).</li>
                <li><strong>Matplotlib / Seaborn: 데이터 시각화 라이브러리.</li>
                <li><strong>Scikit-learn: 머신러닝 알고리즘 및 도구 모음.</li>
                <li><strong>TensorFlow / PyTorch: 딥러닝 프레임워크.</li>
            </ul>
        </li>
         <li><strong>자동화 및 스크립팅:
            <ul>
                <li><code>requests</code>: HTTP 요청 라이브러리 (웹 API 호출).</li>
                <li><code>Beautiful Soup</code> / <code>Scrapy</code>: 웹 스크레이핑/크롤링 라이브러리.</li>
                <li><code>Selenium</code>: 웹 브라우저 자동화 도구.</li>
                <li>파일/시스템 관련 표준 라이브러리 심화 활용.</li>
            </ul>
        </li>
        <li><strong>GUI 개발: PyQt, Kivy, Tkinter 등 라이브러리 학습.</li>
        <li><strong>테스팅: <code>unittest</code>, <code>pytest</code> 등 테스트 프레임워크 학습.</li>
        <li><strong>Python 심화: 제너레이터(Generators), 데코레이터(Decorators), 동시성(Concurrency - asyncio, threading, multiprocessing), 메타클래스 등.</li>
    </ul>
    <p>가장 중요한 것은 지속적인 학습과 꾸준한 연습입니다. 흥미로운 프로젝트를 직접 만들어보면서 부딪히는 문제들을 해결해나가는 과정에서 실력이 가장 빠르게 성장할 것입니다. 공식 문서, 온라인 강좌, 개발 커뮤니티(Stack Overflow, Reddit 등)를 적극 활용하세요.</p>
     <p class="note"><strong>Python과 함께하는 여러분의 코딩 여정을 응원합니다! Happy Coding!</strong></p>
</section>

<br><br>
<hr>

<script src="../js/script.js?ver=1"></script>

</body>
</html>