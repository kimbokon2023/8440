<?php
// PHP 관련 설정 (이 파일 자체는 PHP 실행보다는 HTML/SQL 내용 표시에 중점)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> SQL 강좌 </title>
  <link rel="stylesheet" href="../css/lessons_style.css?ver=1">
  <style>
    /* 기본 스타일 */
    body { font-family: sans-serif; line-height: 1.6; color: #333; }
    .toc { border: 1px solid #ccc; padding: 15px; margin-bottom: 30px; background-color: #f9f9f9; }
    .toc h2 { margin-top: 0; }
    .toc ul { list-style-type: disc; margin-left: 20px; }
    .toc ul ul { list-style-type: circle; margin-left: 20px; }
    h1, h2 { border-bottom: 2px solid #eee; padding-bottom: 5px; color: #2c3e50; }
    h2 { margin-top: 40px; }
    h3 { margin-top: 30px; border-bottom: 1px dashed #ccc; padding-bottom: 3px; color: #34495e;}
    h4 { margin-top: 25px; font-weight: bold; color: #7f8c8d; }
    pre { background-color: #282c34; color: #abb2bf; padding: 15px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; font-size: 0.95em; line-height: 1.5; font-family: 'Source Code Pro', Consolas, monospace;}
    /* 간단한 SQL 구문 강조 */
    code.language-sql .sql-keyword { color: #c678dd; font-weight: bold; } /* SELECT, FROM, WHERE 등 */
    code.language-sql .sql-function { color: #61afef; } /* COUNT, SUM 등 */
    code.language-sql .sql-string { color: #98c379; } /* '문자열' */
    code.language-sql .sql-number { color: #d19a66; } /* 숫자 */
    code.language-sql .sql-comment { color: #5c6370; font-style: italic; } /* 주석 */
    code.language-sql .sql-operator { color: #56b6c2; } /* =, >, <, AND 등 */
    code.language-sql .sql-identifier { color: #e5c07b; } /* 테이블명, 컬럼명 등 */
    code.language-sql .sql-punctuation { color: #abb2bf; } /* *, (), , */

    code.language-html { font-family: Consolas, 'Courier New', monospace; color: #333; }
    .example { border: 1px solid #ecf0f1; padding: 15px; margin-top: 10px; margin-bottom: 20px; background-color: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .example h4 { margin-top: 0; font-size: 1.1em; color: #3498db; }
    .output { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; margin-top: 5px; border-radius: 3px; font-size: 0.95em; white-space: pre-wrap; font-family: Consolas, 'Courier New', monospace;}
    .note { background-color: #fef9e7; border-left: 4px solid #f1c40f; padding: 10px 15px; margin: 15px 0; font-size: 0.95em; }
    .warning { background-color: #fdedec; border-left: 4px solid #e74c3c; padding: 10px 15px; margin: 15px 0; font-size: 0.95em; }

    /* 예제 테이블 스타일 */
    table.sql-example { border-collapse: collapse; margin: 10px 0; width: auto; }
    table.sql-example th, table.sql-example td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
    table.sql-example th { background-color: #f2f2f2; font-weight: bold; }

    /* TOC 활성 링크 스타일 */
    .toc a.active {
      color: #e74c3c;
      font-weight: bold;
    }
  </style>
</head>
<body>

<h1> SQL 강좌  </h1>
<p>이 페이지는 데이터베이스를 관리하고 데이터를 조작하는 표준 언어인 SQL(Structured Query Language)의 기초부터 핵심적인 사용법까지 다룹니다. 첫 번째 파트에서는 데이터베이스와 SQL의 기본 개념을 이해하고, 가장 중요한 데이터 조회(SELECT), 필터링(WHERE), 정렬(ORDER BY) 방법을 학습합니다.</p>

<div class="toc">
  <h2>📖 SQL 강좌 목차</h2>
  <ul>
    <li><a href="#sql-intro">SQL 및 데이터베이스 소개</a>
        <ul>
            <li><a href="#intro-db-rdbms">데이터베이스와 RDBMS란?</a></li>
            <li><a href="#intro-tables-keys">테이블, 행, 열, 키 (기본키, 외래키)</a></li>
            <li><a href="#intro-what-is-sql">SQL이란? (DDL, DML, DCL, TCL)</a></li>
            <li><a href="#intro-sql-dialects">SQL 표준 및 방언(Dialects)</a></li>
        </ul>
    </li>
    <li><a href="#sql-syntax">기본 SQL 문법</a>
        <ul>
            <li><a href="#syntax-statements-clauses">문장(Statements)과 절(Clauses)</a></li>
            <li><a href="#syntax-case-sensitivity">대소문자 구분 (키워드 vs 식별자)</a></li>
            <li><a href="#syntax-comments">주석 (Comments)</a></li>
            <li><a href="#syntax-semicolons">세미콜론 (;)</a></li>
        </ul>
    </li>
    <li><a href="#sql-select">데이터 조회 (SELECT)</a>
        <ul>
            <li><a href="#select-basic">기본 SELECT FROM 구문</a></li>
            <li><a href="#select-all-columns">모든 열 선택 (*)</a></li>
            <li><a href="#select-alias">별칭 사용 (AS)</a></li>
            <li><a href="#select-limit">결과 수 제한 (LIMIT, TOP, ROWNUM)</a></li>
            <li><a href="#select-distinct">중복 제거 (DISTINCT)</a></li>
        </ul>
    </li>
    <li><a href="#sql-where">데이터 필터링 (WHERE)</a>
        <ul>
            <li><a href="#where-comparison">비교 연산자 (=, !=, &lt;&gt;, &lt;, &gt;, &lt;=, &gt;=)</a></li>
            <li><a href="#where-logical">논리 연산자 (AND, OR, NOT)</a></li>
            <li><a href="#where-between-in">범위 및 목록 조건 (BETWEEN, IN)</a></li>
            <li><a href="#where-like">패턴 매칭 (LIKE, 와일드카드 %, _)</a></li>
            <li><a href="#where-null">NULL 값 처리 (IS NULL, IS NOT NULL)</a></li>
        </ul>
    </li>
    <li><a href="#sql-orderby">데이터 정렬 (ORDER BY)</a></li>
    <li><a href="#sql-aggregate">집계 함수 (Aggregate Functions)</a>
        <ul>
            <li><a href="#aggregate-count">COUNT()</a></li>
            <li><a href="#aggregate-sum-avg">SUM(), AVG()</a></li>
            <li><a href="#aggregate-min-max">MIN(), MAX()</a></li>
            <li><a href="#aggregate-nulls">NULL 값 처리</a></li>
        </ul>
    </li>
    <li><a href="#sql-groupby">데이터 그룹화 (GROUP BY)</a></li>
    <li><a href="#sql-having">그룹 필터링 (HAVING)</a></li>
    <li><a href="#sql-joins">테이블 조인 (JOIN)</a>
        <ul>
            <li><a href="#join-inner">INNER JOIN</a></li>
            <li><a href="#join-left">LEFT (OUTER) JOIN</a></li>
            <li><a href="#join-right">RIGHT (OUTER) JOIN</a></li>
            <li><a href="#join-full">FULL OUTER JOIN</a></li>
            <li><a href="#join-cross">CROSS JOIN</a></li>
            <li><a href="#join-self">SELF JOIN</a></li>
            <li><a href="#join-on-using">ON vs USING</a></li>
        </ul>
    </li>
    <li><a href="#sql-subqueries">서브쿼리 (Subqueries)</a>
        <ul>
            <li><a href="#subquery-where">WHERE 절의 서브쿼리</a></li>
            <li><a href="#subquery-select">SELECT 절의 서브쿼리 (스칼라)</a></li>
            <li><a href="#subquery-from">FROM 절의 서브쿼리 (인라인 뷰)</a></li>
            <li><a href="#subquery-correlated">상관 서브쿼리</a></li>
            <li><a href="#subquery-exists">EXISTS 연산자</a></li>
        </ul>
    </li>
    <li><a href="#sql-set-operations">집합 연산자 (Set Operations)</a>
        <ul>
            <li><a href="#set-union-unionall">UNION, UNION ALL</a></li>
            <li><a href="#set-intersect-except">INTERSECT, EXCEPT (MINUS)</a></li>
        </ul>
    </li>
    <li><a href="#sql-dml">데이터 조작어 (DML)</a>
        <ul>
            <li><a href="#dml-insert">INSERT INTO</a></li>
            <li><a href="#dml-update">UPDATE</a></li>
            <li><a href="#dml-delete">DELETE</a></li>
            <li><a href="#dml-truncate">TRUNCATE TABLE</a></li>
        </ul>
    </li>
    <li><a href="#sql-ddl">데이터 정의어 (DDL)</a>
        <ul>
            <li><a href="#ddl-database">CREATE/ALTER/DROP DATABASE</a></li>
            <li><a href="#ddl-table">CREATE TABLE (데이터 타입, 제약 조건)</a></li>
            <li><a href="#ddl-constraints">제약 조건 (PRIMARY KEY, FOREIGN KEY, UNIQUE, NOT NULL, DEFAULT, CHECK)</a></li>
            <li><a href="#ddl-alter-table">ALTER TABLE (열 추가/수정/삭제, 제약 조건 추가/삭제)</a></li>
            <li><a href="#ddl-drop-table">DROP TABLE</a></li>
        </ul>
    </li>
    <li><a href="#sql-indexes">인덱스 (Indexes)</a></li>
    <li><a href="#sql-views">뷰 (Views)</a></li>
    <li><a href="#sql-transactions">트랜잭션 (Transactions)</a>
        <ul>
            <li><a href="#transaction-acid">ACID 속성</a></li>
            <li><a href="#transaction-commands">COMMIT, ROLLBACK, SAVEPOINT</a></li>
        </ul>
    </li>
    <li><a href="#sql-data-types">주요 데이터 타입</a></li>
    <li><a href="#sql-functions">내장 함수 (Built-in Functions)</a>
        <ul>
            <li><a href="#functions-string">문자열 함수</a></li>
            <li><a href="#functions-numeric">숫자 함수</a></li>
            <li><a href="#functions-date">날짜/시간 함수</a></li>
            <li><a href="#functions-conditional">조건 함수 (CASE, IF)</a></li>
        </ul>
    </li>
     <li><a href="#sql-conclusion">마무리</a></li>
  </ul>
</div>

<section id="sql-intro">
  <h2>SQL 및 데이터베이스 소개</h2>

  <h3 id="intro-db-rdbms">데이터베이스와 RDBMS란?</h3>
  <p><strong>데이터베이스(Database, DB)</strong>는 체계적으로 구성된 데이터의 모음입니다. 정보를 효율적으로 저장, 관리, 검색, 수정할 수 있도록 설계되었습니다.</p>
  <p><strong>관계형 데이터베이스 관리 시스템(Relational Database Management System, RDBMS)</strong>은 데이터를 행(Row)과 열(Column)으로 구성된 테이블(Table) 형태로 저장하고 관리하는 시스템입니다. 각 테이블은 특정 주제(예: 고객, 상품, 주문)에 대한 데이터를 담고 있으며, 테이블 간의 관계(Relation)를 통해 데이터를 연결하고 통합적으로 관리할 수 있습니다. 대표적인 RDBMS로는 MySQL, PostgreSQL, Oracle Database, Microsoft SQL Server, SQLite 등이 있습니다.</p>

  <h3 id="intro-tables-keys">테이블, 행, 열, 키 (기본키, 외래키)</h3>
  <ul>
    <li><strong>테이블 (Table):</strong> 관계형 데이터베이스의 기본 구조 단위로, 데이터를 행과 열의 2차원 형태로 저장합니다. (예: `customers` 테이블, `products` 테이블)</li>
    <li><strong>열 (Column / Field / Attribute):</strong> 테이블의 세로 줄로, 특정 속성(데이터의 종류)을 나타냅니다. 각 열은 고유한 이름과 데이터 타입(예: 숫자, 문자열, 날짜)을 가집니다. (예: `customer_id`, `name`, `email`, `signup_date`)</li>
    <li><strong>행 (Row / Record / Tuple):</strong> 테이블의 가로 줄로, 하나의 개체(Entity)에 대한 데이터의 집합입니다. 각 행은 테이블의 모든 열에 대한 값을 가집니다. (예: 특정 고객 한 명의 정보)</li>
    <li><strong>키 (Key):</strong> 테이블에서 특정 행을 고유하게 식별하거나 테이블 간의 관계를 정의하는 데 사용되는 특별한 열(또는 열의 조합)입니다.
        <ul>
            <li><strong>기본 키 (Primary Key, PK):</strong> 테이블 내에서 각 행을 고유하게 식별하는 키입니다. 중복된 값을 가질 수 없으며, <code>NULL</code> 값을 허용하지 않습니다. 테이블 당 하나만 존재할 수 있습니다. (예: `customers` 테이블의 `customer_id`)</li>
            <li><strong>외래 키 (Foreign Key, FK):</strong> 한 테이블의 열이 다른 테이블의 기본 키를 참조하는 키입니다. 테이블 간의 관계를 설정하고 데이터 무결성을 유지하는 데 사용됩니다. 외래 키 값은 참조하는 테이블의 기본 키 값 중 하나이거나 <code>NULL</code>일 수 있습니다. (예: `orders` 테이블의 `customer_id` 열이 `customers` 테이블의 `customer_id`를 참조)</li>
            <li><strong>고유 키 (Unique Key):</strong> 기본 키와 유사하게 중복된 값을 허용하지 않지만, <code>NULL</code> 값을 허용할 수 있습니다 (단, <code>NULL</code>은 하나만 허용되는 경우가 많음). 테이블 당 여러 개 존재할 수 있습니다.</li>
        </ul>
    </li>
  </ul>
  <div class="example">
    <h4>예시 테이블 구조</h4>
    <p><b>customers 테이블</b></p>
    <table class="sql-example">
        <thead><tr><th>customer_id (PK)</th><th>name</th><th>email (Unique)</th><th>city</th></tr></thead>
        <tbody>
            <tr><td>1</td><td>홍길동</td><td>gildong@example.com</td><td>서울</td></tr>
            <tr><td>2</td><td>김철수</td><td>chulsoo@example.com</td><td>부산</td></tr>
        </tbody>
    </table>
     <p><b>orders 테이블</b></p>
    <table class="sql-example">
        <thead><tr><th>order_id (PK)</th><th>customer_id (FK)</th><th>order_date</th><th>total_amount</th></tr></thead>
        <tbody>
            <tr><td>101</td><td>1</td><td>2025-04-27</td><td>50000</td></tr>
            <tr><td>102</td><td>2</td><td>2025-04-27</td><td>35000</td></tr>
             <tr><td>103</td><td>1</td><td>2025-04-28</td><td>70000</td></tr>
        </tbody>
    </table>
  </div>

  <h3 id="intro-what-is-sql">SQL이란? (DDL, DML, DCL, TCL)</h3>
  <p><strong>SQL (Structured Query Language)</strong>은 관계형 데이터베이스 관리 시스템(RDBMS)에서 데이터를 관리하고 조작하기 위해 사용되는 표준 언어입니다.</p>
  <p>SQL은 크게 네 가지 범주로 나눌 수 있습니다:</p>
  <ol>
    <li><strong>데이터 정의어 (Data Definition Language, DDL):</strong> 데이터베이스 구조(스키마)를 정의, 수정, 삭제하는 명령어입니다.
        <ul>
            <li><code>CREATE</code>: 데이터베이스, 테이블, 뷰, 인덱스 등 생성.</li>
            <li><code>ALTER</code>: 기존 데이터베이스 객체 구조 변경.</li>
            <li><code>DROP</code>: 데이터베이스 객체 삭제.</li>
        </ul>
    </li>
    <li><strong>데이터 조작어 (Data Manipulation Language, DML):</strong> 테이블의 데이터를 조회, 삽입, 수정, 삭제하는 명령어입니다.
        <ul>
            <li><code>SELECT</code>: 데이터 조회.</li>
            <li><code>INSERT</code>: 데이터 삽입.</li>
            <li><code>UPDATE</code>: 데이터 수정.</li>
            <li><code>DELETE</code>: 데이터 삭제.</li>
        </ul>
    </li>
    <li><strong>데이터 제어어 (Data Control Language, DCL):</strong> 데이터베이스 접근 권한을 부여(<code>GRANT</code>)하거나 회수(<code>REVOKE</code>)하는 명령어입니다. (데이터베이스 관리 영역)</li>
    <li><strong>트랜잭션 제어어 (Transaction Control Language, TCL):</strong> 데이터베이스의 트랜잭션(논리적인 작업 단위)을 관리하는 명령어입니다.
        <ul>
            <li><code>COMMIT</code>: 트랜잭션 작업 확정.</li>
            <li><code>ROLLBACK</code>: 트랜잭션 작업 취소.</li>
            <li><code>SAVEPOINT</code>: 트랜잭션 내 임시 저장점 설정.</li>
        </ul>
    </li>
  </ol>
  <p>이 강좌에서는 주로 DDL과 DML, 특히 가장 많이 사용되는 <code>SELECT</code> 문을 중심으로 학습합니다.</p>

  <h3 id="intro-sql-dialects">SQL 표준 및 방언(Dialects)</h3>
  <p>SQL은 ANSI/ISO 표준이 존재하지만, 각 RDBMS(MySQL, PostgreSQL, Oracle 등)는 표준 SQL 외에 자체적인 확장 기능이나 문법(방언, Dialect)을 가지고 있습니다. 기본적인 SQL 문법은 대부분의 RDBMS에서 호환되지만, 특정 함수나 고급 기능은 RDBMS마다 다를 수 있습니다.</p>
  <p>이 강좌에서는 가능한 표준 SQL 문법을 기준으로 설명하며, 특정 RDBMS에 따라 달라질 수 있는 부분은 필요시 언급합니다.</p>
</section>

<section id="sql-syntax">
  <h2>기본 SQL 문법</h2>

  <h3 id="syntax-statements-clauses">문장(Statements)과 절(Clauses)</h3>
  <ul>
    <li><strong>문장 (Statement):</strong> SQL에서 작업을 수행하는 완전한 명령어 단위입니다. 일반적으로 세미콜론(<code>;</code>)으로 끝납니다. (예: <code>SELECT * FROM customers;</code>)</li>
    <li><strong>절 (Clause):</strong> SQL 문장을 구성하는 논리적인 부분입니다. 특정 키워드로 시작하며, 문장의 특정 부분을 정의합니다. (예: <code>SELECT</code> 절, <code>FROM</code> 절, <code>WHERE</code> 절, <code>ORDER BY</code> 절)</li>
  </ul>
  <pre><code class="language-sql"><span class="sql-keyword">SELECT</span> <span class="sql-identifier">column1</span><span class="sql-punctuation">,</span> <span class="sql-identifier">column2</span> <span class="sql-comment">-- SELECT 절</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">table_name</span>        <span class="sql-comment">-- FROM 절</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">condition</span>         <span class="sql-comment">-- WHERE 절</span>
<span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span> <span class="sql-identifier">column1</span><span class="sql-punctuation">;</span>   <span class="sql-comment">-- ORDER BY 절, 문장 끝 ;</span>
</code></pre>

  <h3 id="syntax-case-sensitivity">대소문자 구분 (키워드 vs 식별자)</h3>
  <ul>
    <li><strong>SQL 키워드:</strong> <code>SELECT</code>, <code>FROM</code>, <code>WHERE</code>, <code>CREATE</code> 등 SQL 명령어 자체는 일반적으로 대소문자를 구분하지 않습니다. (<code>SELECT</code>나 <code>select</code>나 동일) 하지만 가독성을 위해 대문자로 작성하는 것이 일반적인 컨벤션입니다.</li>
    <li><strong>식별자 (Identifier):</strong> 테이블 이름, 열 이름, 별칭 등 사용자가 정의하는 이름은 RDBMS 종류 및 설정에 따라 대소문자 구분 여부가 다릅니다.
        <ul>
            <li>MySQL: 기본적으로 Windows에서는 구분하지 않고, Linux/macOS에서는 구분합니다 (설정 변경 가능).</li>
            <li>PostgreSQL, Oracle: 기본적으로 구분합니다 (따옴표 없이 사용 시 소문자로 처리될 수 있음).</li>
            <li>SQL Server: 기본적으로 구분하지 않습니다.</li>
        </ul>
        <p>따라서 일관성을 위해 식별자는 소문자 또는 snake_case로 작성하고, 따옴표 없이 사용하는 것이 일반적입니다. 필요시 특정 RDBMS의 규칙을 확인해야 합니다.</p>
    </li>
  </ul>

  <h3 id="syntax-comments">주석 (Comments)</h3>
  <p>SQL 문 내에 설명을 추가하거나 특정 부분을 임시로 비활성화할 때 주석을 사용합니다.</p>
  <ul>
      <li>한 줄 주석: <code>--</code> (하이픈 두 개와 공백) 뒤의 내용은 해당 줄 끝까지 주석 처리됩니다.</li>
      <li>여러 줄 주석: <code>/*</code> 와 <code>*/</code> 사이의 모든 내용은 주석 처리됩니다.</li>
  </ul>
   <pre><code class="language-sql"><span class="sql-comment">-- 이것은 한 줄 주석입니다.</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">email</span> <span class="sql-comment">-- 이름과 이메일만 선택</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">users</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">is_active</span> <span class="sql-operator">=</span> <span class="sql-number">1</span><span class="sql-punctuation">;</span>

<span class="sql-comment">/*
이것은
여러 줄 주석입니다.
WHERE 절을 잠시 비활성화할 때도 사용할 수 있습니다.
WHERE age > 30;
*/</span>
</code></pre>

  <h3 id="syntax-semicolons">세미콜론 (;)</h3>
  <p>SQL 문장의 끝을 나타냅니다. 대부분의 데이터베이스 도구나 인터페이스에서는 각 문장 끝에 세미콜론을 붙여야 여러 문장을 구분하여 실행할 수 있습니다. 단일 문장 실행 시에는 생략 가능할 수도 있지만, 명확성을 위해 붙이는 것이 좋습니다.</p>
</section>

<section id="sql-select">
  <h2>데이터 조회 (SELECT)</h2>
  <p><code>SELECT</code> 문은 데이터베이스 테이블에서 데이터를 조회하는 가장 기본적이고 중요한 SQL 명령어입니다.</p>

  <h3 id="select-basic">기본 SELECT FROM 구문</h3>
  <p><code>SELECT</code> 키워드 뒤에 조회할 열(column) 이름을 쉼표(<code>,</code>)로 구분하여 나열하고, <code>FROM</code> 키워드 뒤에 데이터를 가져올 테이블 이름을 지정합니다.</p>
  <pre><code class="language-sql"><span class="sql-comment">-- customers 테이블에서 name 열과 email 열만 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">email</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- products 테이블에서 product_name과 price 열 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">product_name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">price</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span><span class="sql-punctuation">;</span></code></pre>
  <div class="example">
    <h4>예시 실행 결과 (customers 테이블)</h4>
    <table class="sql-example">
        <thead><tr><th>name</th><th>email</th></tr></thead>
        <tbody>
            <tr><td>홍길동</td><td>gildong@example.com</td></tr>
            <tr><td>김철수</td><td>chulsoo@example.com</td></tr>
            <tr><td>이영희</td><td>younghee@example.com</td></tr>
        </tbody>
    </table>
  </div>

  <h3 id="select-all-columns">모든 열 선택 (*)</h3>
  <p>테이블의 모든 열을 조회하려면 열 이름 대신 별표(<code>*</code>)를 사용합니다.</p>
  <pre><code class="language-sql"><span class="sql-comment">-- customers 테이블의 모든 열 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span></code></pre>
  <p class="note"><code>*</code>는 편리하지만, 불필요한 데이터까지 가져와 성능에 영향을 줄 수 있고 테이블 구조 변경 시 예기치 않은 문제를 일으킬 수 있으므로, 실제 애플리케이션에서는 필요한 열 이름만 명시하는 것이 좋습니다.</p>

  <h3 id="select-alias">별칭 사용 (AS)</h3>
  <p>조회 결과에서 열 이름이나 테이블 이름에 임시로 다른 이름(별칭, Alias)을 부여할 수 있습니다. <code>AS</code> 키워드를 사용하거나, 공백으로 구분하여 지정합니다. 별칭은 결과 출력 시 가독성을 높이거나, 계산된 필드에 이름을 붙이거나, 조인(JOIN) 등에서 테이블 이름을 간결하게 표현할 때 유용합니다.</p>
  <pre><code class="language-sql"><span class="sql-comment">-- 열 별칭 사용</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">product_name</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">상품명</span><span class="sql-punctuation">,</span> <span class="sql-comment">-- AS 키워드 사용</span>
    <span class="sql-identifier">price</span> <span class="sql-identifier">가격</span><span class="sql-punctuation">,</span>       <span class="sql-comment">-- AS 생략 가능 (공백)</span>
    <span class="sql-identifier">stock_quantity</span> <span class="sql-operator">*</span> <span class="sql-identifier">price</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">total_value</span> <span class="sql-comment">-- 계산된 필드에 별칭 부여</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">products</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 테이블 별칭 사용 (조인 시 유용)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">order_date</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">c</span> <span class="sql-comment">-- customers 테이블 별칭 c</span>
<span class="sql-keyword">INNER</span> <span class="sql-keyword">JOIN</span> <span class="sql-identifier">orders</span> <span class="sql-identifier">o</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- orders 테이블 별칭 o</span>
</code></pre>
  <p>별칭에 공백이나 특수문자가 포함되거나 키워드와 충돌할 경우 큰따옴표(<code>" "</code> - 표준 SQL, PostgreSQL, Oracle)나 백틱(<code>` `</code> - MySQL) 등으로 감싸야 할 수 있습니다.</p>

  <h3 id="select-limit">결과 수 제한 (LIMIT, TOP, ROWNUM)</h3>
  <p>조회 결과의 행 수를 제한할 때 사용합니다. RDBMS마다 문법이 다릅니다.</p>
  <ul>
      <li><strong>MySQL / PostgreSQL:</strong> <code>LIMIT count</code> 또는 <code>LIMIT offset, count</code>
          <pre><code class="language-sql"><span class="sql-comment">-- 처음 5개 행만 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span> <span class="sql-keyword">LIMIT</span> <span class="sql-number">5</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 3번째 행부터 5개 행 조회 (offset 2, count 5) - 페이징 구현 시 사용</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span> <span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span> <span class="sql-identifier">product_id</span> <span class="sql-keyword">LIMIT</span> <span class="sql-number">2</span><span class="sql-punctuation">,</span> <span class="sql-number">5</span><span class="sql-punctuation">;</span>
<span class="sql-comment">-- PostgreSQL에서는 LIMIT 5 OFFSET 2; 와 같이 사용</span>
</code></pre>
      </li>
       <li><strong>SQL Server:</strong> <code>SELECT TOP count ...</code> 또는 <code>ORDER BY ... OFFSET offset ROWS FETCH NEXT count ROWS ONLY</code> (최신 버전)
            <pre><code class="language-sql"><span class="sql-comment">-- 처음 5개 행만 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-keyword">TOP</span> <span class="sql-number">5</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 3번째 행부터 5개 행 조회 (SQL Server 2012 이상)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span> <span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span> <span class="sql-identifier">product_id</span>
<span class="sql-keyword">OFFSET</span> <span class="sql-number">2</span> <span class="sql-keyword">ROWS</span> <span class="sql-keyword">FETCH</span> <span class="sql-keyword">NEXT</span> <span class="sql-number">5</span> <span class="sql-keyword">ROWS</span> <span class="sql-keyword">ONLY</span><span class="sql-punctuation">;</span>
</code></pre>
      </li>
      <li><strong>Oracle:</strong> <code>WHERE ROWNUM <= count</code> (오래된 방식) 또는 <code>FETCH FIRST count ROWS ONLY</code> (Oracle 12c 이상)
           <pre><code class="language-sql"><span class="sql-comment">-- 처음 5개 행만 조회 (Oracle 12c 이상)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span> <span class="sql-keyword">FETCH</span> <span class="sql-keyword">FIRST</span> <span class="sql-number">5</span> <span class="sql-keyword">ROWS</span> <span class="sql-keyword">ONLY</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 3번째 행부터 5개 행 조회 (Oracle 12c 이상)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span> <span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span> <span class="sql-identifier">product_id</span>
<span class="sql-keyword">OFFSET</span> <span class="sql-number">2</span> <span class="sql-keyword">ROWS</span> <span class="sql-keyword">FETCH</span> <span class="sql-keyword">NEXT</span> <span class="sql-number">5</span> <span class="sql-keyword">ROWS</span> <span class="sql-keyword">ONLY</span><span class="sql-punctuation">;</span>
</code></pre>
      </li>
  </ul>
   <p>결과 순서가 중요하다면 <code>LIMIT</code>(또는 유사 기능) 사용 시 <code>ORDER BY</code> 절을 함께 사용하는 것이 좋습니다.</p>

  <h3 id="select-distinct">중복 제거 (DISTINCT)</h3>
  <p><code>SELECT DISTINCT</code>를 사용하면 조회 결과에서 중복된 행을 제거하고 고유한 행만 반환합니다. <code>DISTINCT</code> 키워드는 <code>SELECT</code> 바로 뒤에 위치하며, 지정된 모든 열의 조합을 기준으로 중복을 판단합니다.</p>
  <pre><code class="language-sql"><span class="sql-comment">-- customers 테이블에서 중복을 제외한 도시 목록 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-keyword">DISTINCT</span> <span class="sql-identifier">city</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 중복을 제외한 brand와 category 조합 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-keyword">DISTINCT</span> <span class="sql-identifier">brand</span><span class="sql-punctuation">,</span> <span class="sql-identifier">category</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span><span class="sql-punctuation">;</span></code></pre>
</section>

<section id="sql-where">
    <h2>데이터 필터링 (WHERE)</h2>
    <p><code>WHERE</code> 절은 <code>SELECT</code>, <code>UPDATE</code>, <code>DELETE</code> 문에서 특정 조건을 만족하는 행만 선택(필터링)하는 데 사용됩니다. <code>FROM</code> 절 뒤에 위치합니다.</p>
    <p><code>WHERE condition</code></p>
    <p><code>condition</code>은 하나 이상의 표현식과 연산자로 구성되며, 평가 결과가 참(true)인 행만 결과에 포함됩니다.</p>

    <h3 id="where-comparison">비교 연산자</h3>
    <p>두 값을 비교하는 데 사용됩니다.</p>
    <ul>
        <li><code>=</code>: 같음</li>
        <li><code>!=</code> 또는 <code>&lt;&gt;</code>: 다름</li>
        <li><code>&lt;</code>: 작음</li>
        <li><code>&gt;</code>: 큼</li>
        <li><code>&lt;=</code>: 작거나 같음</li>
        <li><code>&gt;=</code>: 크거나 같음</li>
    </ul>
    <pre><code class="language-sql"><span class="sql-comment">-- city가 '서울'인 고객 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">email</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'서울'</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- price가 10000 이상인 상품 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">product_name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">price</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">price</span> <span class="sql-operator">&gt;=</span> <span class="sql-number">10000</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 주문 날짜가 '2025-04-28'이 아닌 주문 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">order_id</span><span class="sql-punctuation">,</span> <span class="sql-identifier">order_date</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">orders</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">order_date</span> <span class="sql-operator">!=</span> <span class="sql-string">'2025-04-28'</span><span class="sql-punctuation">;</span></code></pre>
    <p class="note">문자열 값은 일반적으로 작은따옴표(<code>' '</code>)로 감싸며, 숫자 값은 따옴표 없이 사용합니다. 날짜/시간 값은 RDBMS에 따라 형식과 따옴표 사용 여부가 다를 수 있습니다.</p>

    <h3 id="where-logical">논리 연산자 (AND, OR, NOT)</h3>
    <p>여러 개의 조건을 결합하는 데 사용됩니다.</p>
    <ul>
        <li><code>AND</code>: 모든 조건이 참일 때 참.</li>
        <li><code>OR</code>: 조건 중 하나라도 참일 때 참.</li>
        <li><code>NOT</code>: 조건의 결과를 반전시킴.</li>
    </ul>
    <p>연산자 우선순위(일반적으로 <code>NOT</code> > <code>AND</code> > <code>OR</code>)를 고려해야 하며, 명확성을 위해 괄호(<code>()</code>)를 사용하여 조건을 그룹화하는 것이 좋습니다.</p>
     <pre><code class="language-sql"><span class="sql-comment">-- city가 '서울'이고, 가입 날짜(signup_date)가 '2024-01-01' 이후인 고객</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'서울'</span> <span class="sql-operator">AND</span> <span class="sql-identifier">signup_date</span> <span class="sql-operator">&gt;</span> <span class="sql-string">'2024-01-01'</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 가격이 5000 미만이거나 재고(stock)가 10개 미만인 상품</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">product_name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">price</span><span class="sql-punctuation">,</span> <span class="sql-identifier">stock</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">price</span> <span class="sql-operator">&lt;</span> <span class="sql-number">5000</span> <span class="sql-operator">OR</span> <span class="sql-identifier">stock</span> <span class="sql-operator">&lt;</span> <span class="sql-number">10</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- '서울'에 살지 않는 고객</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">city</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span>
<span class="sql-keyword">WHERE</span> <span class="sql-operator">NOT</span> <span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'서울'</span><span class="sql-punctuation">;</span>
<span class="sql-comment">-- 또는 WHERE city != '서울' / WHERE city &lt;&gt; '서울'</span>

<span class="sql-comment">-- 괄호를 사용하여 조건 그룹화 (서울 또는 부산에 살면서 포인트가 1000 이상인 고객)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span>
<span class="sql-keyword">WHERE</span> <span class="sql-punctuation">(</span><span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'서울'</span> <span class="sql-operator">OR</span> <span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'부산'</span><span class="sql-punctuation">)</span> <span class="sql-operator">AND</span> <span class="sql-identifier">points</span> <span class="sql-operator">&gt;=</span> <span class="sql-number">1000</span><span class="sql-punctuation">;</span>
</code></pre>

    <h3 id="where-between-in">범위 및 목록 조건 (BETWEEN, IN)</h3>
    <ul>
        <li><code>column BETWEEN value1 AND value2</code>: 값이 <code>value1</code>과 <code>value2</code> 사이에 있는 행을 선택 (경계값 포함).</li>
        <li><code>column IN (value1, value2, ...)</code>: 값이 괄호 안의 목록 중 하나와 일치하는 행을 선택.</li>
        <li><code>NOT BETWEEN</code>, <code>NOT IN</code>: 해당 조건을 만족하지 않는 행을 선택.</li>
    </ul>
     <pre><code class="language-sql"><span class="sql-comment">-- 가격이 10000원에서 50000원 사이인 상품</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">price</span> <span class="sql-keyword">BETWEEN</span> <span class="sql-number">10000</span> <span class="sql-operator">AND</span> <span class="sql-number">50000</span><span class="sql-punctuation">;</span>
<span class="sql-comment">-- 위는 WHERE price >= 10000 AND price <= 50000 와 동일</span>

<span class="sql-comment">-- 도시가 '서울', '부산', '인천' 중 하나인 고객</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">city</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">city</span> <span class="sql-keyword">IN</span> <span class="sql-punctuation">(</span><span class="sql-string">'서울'</span><span class="sql-punctuation">,</span> <span class="sql-string">'부산'</span><span class="sql-punctuation">,</span> <span class="sql-string">'인천'</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>
<span class="sql-comment">-- 위는 WHERE city = '서울' OR city = '부산' OR city = '인천' 와 동일</span>

<span class="sql-comment">-- ID가 1, 3, 5가 아닌 주문</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">orders</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">order_id</span> <span class="sql-keyword">NOT</span> <span class="sql-keyword">IN</span> <span class="sql-punctuation">(</span><span class="sql-number">1</span><span class="sql-punctuation">,</span> <span class="sql-number">3</span><span class="sql-punctuation">,</span> <span class="sql-number">5</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>
</code></pre>

    <h3 id="where-like">패턴 매칭 (LIKE, 와일드카드 %, _)</h3>
    <p><code>LIKE</code> 연산자는 문자열 열에서 특정 패턴과 일치하는 행을 검색할 때 사용합니다. 와일드카드 문자와 함께 사용됩니다.</p>
    <ul>
        <li><code>%</code> (퍼센트 기호): 0개 이상의 임의의 문자열과 일치.</li>
        <li><code>_</code> (밑줄 기호): 정확히 1개의 임의의 문자와 일치.</li>
    </ul>
    <p><code>NOT LIKE</code>는 패턴과 일치하지 않는 행을 검색합니다.</p>
    <p>대소문자 구분 여부는 RDBMS 및 컬럼의 Collation 설정에 따라 다릅니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 이름이 '김'으로 시작하는 고객</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">name</span> <span class="sql-keyword">LIKE</span> <span class="sql-string">'김%'</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 이메일 주소에 'example.com'을 포함하는 고객</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">email</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">email</span> <span class="sql-keyword">LIKE</span> <span class="sql-string">'%@example.com'</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 이름의 두 번째 글자가 '길'인 고객</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">name</span> <span class="sql-keyword">LIKE</span> <span class="sql-string">'_길%'</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- '%' 또는 '_' 문자 자체를 검색하려면 이스케이프 문자를 사용해야 함 (RDBMS마다 다를 수 있음)</span>
<span class="sql-comment">-- 예: WHERE column LIKE '%\%%' ESCAPE '\';</span>
</code></pre>

    <h3 id="where-null">NULL 값 처리 (IS NULL, IS NOT NULL)</h3>
    <p><code>NULL</code>은 '값이 없음' 또는 '알 수 없음'을 나타내는 특별한 값입니다. <code>NULL</code> 값은 일반적인 비교 연산자(<code>=</code>, <code>!=</code> 등)로 비교할 수 없으며, <code>IS NULL</code> 또는 <code>IS NOT NULL</code> 연산자를 사용해야 합니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 이메일 주소가 입력되지 않은(NULL) 고객 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">email</span> <span class="sql-keyword">IS</span> <span class="sql-keyword">NULL</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 이메일 주소가 입력된 고객 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">email</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">email</span> <span class="sql-keyword">IS</span> <span class="sql-keyword">NOT</span> <span class="sql-keyword">NULL</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 잘못된 비교 (결과 없음 또는 예기치 않은 결과)</span>
<span class="sql-comment">-- SELECT * FROM customers WHERE email = NULL;</span>
</code></pre>
</section>

<section id="sql-orderby">
  <h2>데이터 정렬 (ORDER BY)</h2>
  <p><code>ORDER BY</code> 절은 <code>SELECT</code> 문으로 조회된 결과 행들을 특정 열(들)의 값을 기준으로 정렬하는 데 사용됩니다. 기본적으로 문장의 끝 부분(<code>LIMIT</code> 절 앞)에 위치합니다.</p>
  <p><code>ORDER BY column1 [ASC|DESC], column2 [ASC|DESC], ...</code></p>
  <ul>
      <li><code>ASC</code>: 오름차순 정렬 (기본값, 생략 가능).</li>
      <li><code>DESC</code>: 내림차순 정렬.</li>
      <li>여러 열을 지정하면, 첫 번째 열 기준으로 정렬 후 값이 같은 경우 두 번째 열 기준으로 정렬하는 식으로 진행됩니다.</li>
      <li>열 이름 대신 열의 순서 번호(<code>SELECT</code> 절 기준, 1부터 시작)나 별칭(Alias)을 사용하여 정렬할 수도 있습니다.</li>
  </ul>
   <pre><code class="language-sql"><span class="sql-comment">-- 고객 이름을 기준으로 오름차순 정렬</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">city</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span>
<span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span> <span class="sql-identifier">name</span> <span class="sql-keyword">ASC</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- ASC는 생략 가능</span>

<span class="sql-comment">-- 상품 가격을 기준으로 내림차순 정렬</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">product_name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">price</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span>
<span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span> <span class="sql-identifier">price</span> <span class="sql-keyword">DESC</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 도시(오름차순) 정렬 후, 같은 도시 내에서는 가입 날짜(내림차순)로 정렬</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">city</span><span class="sql-punctuation">,</span> <span class="sql-identifier">signup_date</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span>
<span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span> <span class="sql-identifier">city</span><span class="sql-punctuation">,</span> <span class="sql-identifier">signup_date</span> <span class="sql-keyword">DESC</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- SELECT 절의 두 번째 열(price) 기준으로 내림차순 정렬</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">product_name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">price</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span>
<span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span> <span class="sql-number">2</span> <span class="sql-keyword">DESC</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 별칭 사용 정렬</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-function">YEAR</span><span class="sql-punctuation">(</span><span class="sql-identifier">signup_date</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">join_year</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span>
<span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span> <span class="sql-identifier">join_year</span> <span class="sql-keyword">DESC</span><span class="sql-punctuation">,</span> <span class="sql-identifier">name</span> <span class="sql-keyword">ASC</span><span class="sql-punctuation">;</span>
</code></pre>
  <p><code>NULL</code> 값의 정렬 순서(맨 앞 또는 맨 뒤)는 RDBMS마다 다를 수 있으며, <code>NULLS FIRST</code> 또는 <code>NULLS LAST</code> 옵션을 제공하는 경우도 있습니다.</p>
</section>

<br><br>
<hr>
<section id="sql-aggregate">
    <h2>집계 함수 (Aggregate Functions)</h2>
    <p>집계 함수는 여러 행의 값을 바탕으로 단일 요약 값(예: 합계, 평균, 개수 등)을 계산하는 함수입니다. 주로 <code>SELECT</code> 절이나 <code>HAVING</code> 절에서 사용됩니다.</p>
    <p class="note">집계 함수는 일반적으로 <code>GROUP BY</code> 절과 함께 사용되어 그룹별 통계를 계산하지만, <code>GROUP BY</code> 없이 사용하면 전체 테이블(또는 <code>WHERE</code> 절로 필터링된 결과)에 대한 단일 집계 값을 반환합니다.</p>

    <h3 id="aggregate-count">COUNT()</h3>
    <p><code>COUNT()</code> 함수는 조건에 맞는 행의 개수를 반환합니다.</p>
    <ul>
        <li><code>COUNT(*)</code>: 테이블의 전체 행 수를 반환합니다 (NULL 값 포함).</li>
        <li><code>COUNT(column_name)</code>: 지정된 열에서 <strong>NULL이 아닌 값</strong>을 가진 행의 수를 반환합니다.</li>
        <li><code>COUNT(DISTINCT column_name)</code>: 지정된 열에서 중복을 제외한 고유한 값(NULL 제외)의 개수를 반환합니다.</li>
    </ul>
    <pre><code class="language-sql"><span class="sql-comment">-- 전체 고객 수</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">COUNT</span><span class="sql-punctuation">(*)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">total_customers</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 이메일 주소가 있는 고객 수 (email 열이 NULL이 아닌 행)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">COUNT</span><span class="sql-punctuation">(</span><span class="sql-identifier">email</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">customers_with_email</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 고객들이 거주하는 고유한 도시의 수</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">COUNT</span><span class="sql-punctuation">(</span><span class="sql-keyword">DISTINCT</span> <span class="sql-identifier">city</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">unique_cities</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span></code></pre>

    <h3 id="aggregate-sum-avg">SUM(), AVG()</h3>
    <ul>
        <li><code>SUM(column_name)</code>: 지정된 숫자 열의 모든 값의 합계를 반환합니다.</li>
        <li><code>AVG(column_name)</code>: 지정된 숫자 열의 모든 값의 평균을 반환합니다.</li>
    </ul>
    <p><code>SUM()</code>과 <code>AVG()</code>는 숫자 타입의 열에만 적용할 수 있으며, 계산 시 <code>NULL</code> 값은 무시됩니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 모든 주문의 총 금액 합계</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">SUM</span><span class="sql-punctuation">(</span><span class="sql-identifier">total_amount</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">total_sales</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">orders</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 상품들의 평균 가격</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">AVG</span><span class="sql-punctuation">(</span><span class="sql-identifier">price</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">average_price</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span><span class="sql-punctuation">;</span></code></pre>

    <h3 id="aggregate-min-max">MIN(), MAX()</h3>
    <ul>
        <li><code>MIN(column_name)</code>: 지정된 열에서 최소값을 반환합니다.</li>
        <li><code>MAX(column_name)</code>: 지정된 열에서 최대값을 반환합니다.</li>
    </ul>
    <p><code>MIN()</code>과 <code>MAX()</code>는 숫자, 문자열, 날짜/시간 타입 등 비교 가능한 모든 타입의 열에 적용할 수 있으며, 계산 시 <code>NULL</code> 값은 무시됩니다.</p>
     <pre><code class="language-sql"><span class="sql-comment">-- 가장 낮은 상품 가격</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">MIN</span><span class="sql-punctuation">(</span><span class="sql-identifier">price</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">lowest_price</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 가장 최근 가입 고객의 가입 날짜</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">MAX</span><span class="sql-punctuation">(</span><span class="sql-identifier">signup_date</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">latest_signup</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 알파벳 순으로 가장 빠른 고객 이름</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">MIN</span><span class="sql-punctuation">(</span><span class="sql-identifier">name</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">first_name_alpha</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span></code></pre>

    <h3 id="aggregate-nulls">NULL 값 처리</h3>
    <p><code>COUNT(*)</code>를 제외한 대부분의 집계 함수(<code>COUNT(column)</code>, <code>SUM</code>, <code>AVG</code>, <code>MIN</code>, <code>MAX</code>)는 계산 시 <strong><code>NULL</code> 값을 무시</strong>합니다. 이는 특히 <code>AVG()</code> 함수 사용 시 주의해야 합니다. 예를 들어, 특정 열의 평균을 계산할 때 <code>NULL</code> 값을 0으로 취급하고 싶다면 <code>AVG(IFNULL(column, 0))</code> (MySQL) 또는 <code>AVG(COALESCE(column, 0))</code> (표준 SQL) 처럼 <code>NULL</code> 값을 다른 값으로 대체하는 함수를 함께 사용해야 합니다.</p>
     <pre><code class="language-sql"><span class="sql-comment">-- commission 열이 NULL일 수도 있는 경우 평균 계산</span>
<span class="sql-comment">-- AVG(commission)은 NULL을 제외하고 평균을 계산</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">AVG</span><span class="sql-punctuation">(</span><span class="sql-identifier">commission</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">avg_commission_ignore_null</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">salespeople</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- NULL을 0으로 취급하여 평균 계산 (표준 SQL COALESCE 사용)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">AVG</span><span class="sql-punctuation">(</span><span class="sql-function">COALESCE</span><span class="sql-punctuation">(</span><span class="sql-identifier">commission</span><span class="sql-punctuation">,</span> <span class="sql-number">0</span><span class="sql-punctuation">))</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">avg_commission_null_as_zero</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">salespeople</span><span class="sql-punctuation">;</span>
</code></pre>
</section>

<section id="sql-groupby">
    <h2>데이터 그룹화 (GROUP BY)</h2>
    <p><code>GROUP BY</code> 절은 테이블의 행들을 특정 열(들)의 값이 같은 것끼리 그룹으로 묶는 데 사용됩니다. 주로 각 그룹에 대한 통계(예: 그룹별 개수, 합계, 평균)를 계산하기 위해 집계 함수와 함께 사용됩니다.</p>
    <p><code>GROUP BY column1, column2, ...</code></p>
    <p><code>GROUP BY</code> 절은 <code>WHERE</code> 절 뒤, <code>ORDER BY</code> 절 앞에 위치합니다.</p>
    <p><strong><code>GROUP BY</code> 사용 시 <code>SELECT</code> 절 규칙:</strong><br>
    <code>SELECT</code> 절에는 <code>GROUP BY</code> 절에 사용된 열(그룹화 기준 열) 또는 집계 함수(<code>COUNT</code>, <code>SUM</code>, <code>AVG</code> 등)만 포함될 수 있습니다. 다른 열을 포함하면 해당 그룹 내 어떤 행의 값을 표시해야 할지 모호해지기 때문입니다. (일부 RDBMS, 특히 MySQL의 구 버전 기본 설정에서는 이 규칙이 완화되어 있지만, 표준 SQL을 따르고 예측 가능한 결과를 얻으려면 규칙을 지키는 것이 좋습니다.)</p>

     <pre><code class="language-sql"><span class="sql-comment">-- 각 도시별 고객 수 계산</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">city</span><span class="sql-punctuation">,</span> <span class="sql-comment">-- 그룹화 기준 열</span>
    <span class="sql-function">COUNT</span><span class="sql-punctuation">(</span><span class="sql-operator">*</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">number_of_customers</span> <span class="sql-comment">-- 집계 함수</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">customers</span>
<span class="sql-keyword">GROUP</span> <span class="sql-keyword">BY</span>
    <span class="sql-identifier">city</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- city 열 기준으로 그룹화</span>

<span class="sql-comment">-- 각 상품 카테고리별 평균 가격 계산</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">category</span><span class="sql-punctuation">,</span>
    <span class="sql-function">AVG</span><span class="sql-punctuation">(</span><span class="sql-identifier">price</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">average_price</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">products</span>
<span class="sql-keyword">GROUP</span> <span class="sql-keyword">BY</span>
    <span class="sql-identifier">category</span>
<span class="sql-keyword">ORDER</span> <span class="sql-keyword">BY</span>
    <span class="sql-identifier">average_price</span> <span class="sql-keyword">DESC</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 그룹별 평균 가격으로 정렬</span>

<span class="sql-comment">-- 각 고객별 총 주문 금액 계산</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">customer_id</span><span class="sql-punctuation">,</span>
    <span class="sql-function">SUM</span><span class="sql-punctuation">(</span><span class="sql-identifier">total_amount</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">total_spent</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">orders</span>
<span class="sql-keyword">GROUP</span> <span class="sql-keyword">BY</span>
    <span class="sql-identifier">customer_id</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 잘못된 예시 (표준 SQL 아님): 그룹화되지 않은 열(name)을 SELECT 절에 포함</span>
<span class="sql-comment">-- SELECT city, name, COUNT(*) FROM customers GROUP BY city; -- 오류 발생 가능</span>
</code></pre>
     <div class="example">
        <h4>GROUP BY 예시 결과 (도시별 고객 수)</h4>
        <table class="sql-example">
            <thead><tr><th>city</th><th>number_of_customers</th></tr></thead>
            <tbody>
                <tr><td>서울</td><td>2</td></tr>
                <tr><td>부산</td><td>1</td></tr>
            </tbody>
        </table>
    </div>
</section>

<section id="sql-having">
    <h2>그룹 필터링 (HAVING)</h2>
    <p><code>HAVING</code> 절은 <code>GROUP BY</code> 절로 그룹화된 결과에 대해 조건을 적용하여 특정 그룹만 필터링하는 데 사용됩니다. <code>WHERE</code> 절이 그룹화 *전*에 개별 행을 필터링하는 것과 달리, <code>HAVING</code> 절은 그룹화 및 집계 함수 계산이 완료된 *후*에 그룹 자체를 필터링합니다.</p>
    <p><code>HAVING condition</code></p>
    <p><code>HAVING</code> 절의 조건식에는 집계 함수를 사용할 수 있습니다. <code>HAVING</code> 절은 <code>GROUP BY</code> 절 뒤, <code>ORDER BY</code> 절 앞에 위치합니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 고객 수가 2명 이상인 도시만 조회</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">city</span><span class="sql-punctuation">,</span>
    <span class="sql-function">COUNT</span><span class="sql-punctuation">(</span><span class="sql-operator">*</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">num_customers</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">customers</span>
<span class="sql-keyword">GROUP</span> <span class="sql-keyword">BY</span>
    <span class="sql-identifier">city</span>
<span class="sql-keyword">HAVING</span>
    <span class="sql-function">COUNT</span><span class="sql-punctuation">(</span><span class="sql-operator">*</span><span class="sql-punctuation">)</span> <span class="sql-operator">&gt;=</span> <span class="sql-number">2</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 그룹화된 결과(고객 수)에 대한 조건</span>

<span class="sql-comment">-- 평균 가격이 50000 이상인 상품 카테고리 조회</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">category</span><span class="sql-punctuation">,</span>
    <span class="sql-function">AVG</span><span class="sql-punctuation">(</span><span class="sql-identifier">price</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">avg_price</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">products</span>
<span class="sql-keyword">GROUP</span> <span class="sql-keyword">BY</span>
    <span class="sql-identifier">category</span>
<span class="sql-keyword">HAVING</span>
    <span class="sql-function">AVG</span><span class="sql-punctuation">(</span><span class="sql-identifier">price</span><span class="sql-punctuation">)</span> <span class="sql-operator">&gt;=</span> <span class="sql-number">50000</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- WHERE와 HAVING 함께 사용: 2024년 이후 가입한 고객 중, 총 주문 금액이 100000 이상인 고객</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span><span class="sql-punctuation">,</span>
    <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span>
    <span class="sql-function">SUM</span><span class="sql-punctuation">(</span><span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">total_amount</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">total_spent</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">customers</span> <span class="sql-identifier">c</span>
<span class="sql-keyword">JOIN</span> <span class="sql-identifier">orders</span> <span class="sql-identifier">o</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span>
<span class="sql-keyword">WHERE</span>
    <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">signup_date</span> <span class="sql-operator">&gt;=</span> <span class="sql-string">'2024-01-01'</span> <span class="sql-comment">-- 행 필터링 (그룹화 전)</span>
<span class="sql-keyword">GROUP</span> <span class="sql-keyword">BY</span>
    <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span><span class="sql-punctuation">,</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span>
<span class="sql-keyword">HAVING</span>
    <span class="sql-function">SUM</span><span class="sql-punctuation">(</span><span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">total_amount</span><span class="sql-punctuation">)</span> <span class="sql-operator">&gt;=</span> <span class="sql-number">100000</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 그룹 필터링 (그룹화 후)</span>
</code></pre>
</section>

<section id="sql-joins">
    <h2>테이블 조인 (JOIN)</h2>
    <p>관계형 데이터베이스의 핵심은 여러 테이블에 분산된 데이터를 관계를 통해 연결하고 조합하는 것입니다. 조인(JOIN)은 두 개 이상의 테이블을 특정 조건(보통 공통된 열의 값)을 기준으로 연결하여 하나의 결과 집합으로 만드는 작업입니다.</p>
    <p>조인을 사용하면 정규화된(잘 구조화된) 데이터베이스에서 필요한 정보를 효율적으로 가져올 수 있습니다.</p>
    <p class="note">조인 예시를 위해 다음 두 테이블을 가정합니다:</p>
    <div class="example">
        <p><b>employees 테이블</b></p>
        <table class="sql-example">
            <thead><tr><th>employee_id (PK)</th><th>name</th><th>department_id (FK)</th></tr></thead>
            <tbody>
                <tr><td>101</td><td>홍길동</td><td>10</td></tr>
                <tr><td>102</td><td>김철수</td><td>20</td></tr>
                <tr><td>103</td><td>이영희</td><td>10</td></tr>
                <tr><td>104</td><td>박지성</td><td>NULL</td></tr>
            </tbody>
        </table>
         <p><b>departments 테이블</b></p>
        <table class="sql-example">
            <thead><tr><th>department_id (PK)</th><th>department_name</th></tr></thead>
            <tbody>
                <tr><td>10</td><td>인사팀</td></tr>
                <tr><td>20</td><td>개발팀</td></tr>
                <tr><td>30</td><td>영업팀</td></tr>
            </tbody>
        </table>
    </div>

    <h3 id="join-inner">INNER JOIN</h3>
    <p><code>INNER JOIN</code> (또는 단순히 <code>JOIN</code>)은 두 테이블 간에 일치하는 행만 결합합니다. 조인 조건(<code>ON</code> 절)을 만족하는 데이터만 결과에 포함됩니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 직원 이름과 해당 직원이 속한 부서 이름 조회</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_name</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span> <span class="sql-comment">-- employees 테이블 별칭 e</span>
<span class="sql-keyword">INNER</span> <span class="sql-keyword">JOIN</span>
    <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 조인 조건</span>
</code></pre>
     <div class="example">
        <h4>INNER JOIN 결과 예시</h4>
        <table class="sql-example">
            <thead><tr><th>name</th><th>department_name</th></tr></thead>
            <tbody>
                <tr><td>홍길동</td><td>인사팀</td></tr>
                <tr><td>김철수</td><td>개발팀</td></tr>
                <tr><td>이영희</td><td>인사팀</td></tr>
                <tr style="opacity: 0.5;"><td><del>박지성</del></td><td><del>NULL (부서 없음)</del></td></tr>
                <tr style="opacity: 0.5;"><td><del>NULL (직원 없음)</del></td><td><del>영업팀</del></td></tr>
            </tbody>
        </table>
        <p>(부서가 없는 박지성, 직원이 없는 영업팀은 결과에서 제외됨)</p>
    </div>
    <p>여러 테이블 조인:</p>
     <pre><code class="language-sql"><span class="sql-comment">-- 직원, 부서, 직책(job_titles) 테이블 조인 예시</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">j</span><span class="sql-punctuation">.</span><span class="sql-identifier">job_title</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span>
<span class="sql-keyword">JOIN</span> <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span>
<span class="sql-keyword">JOIN</span> <span class="sql-identifier">job_titles</span> <span class="sql-identifier">j</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">job_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">j</span><span class="sql-punctuation">.</span><span class="sql-identifier">job_id</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- employees와 job_titles 조인</span>
</code></pre>

    <h3 id="join-left">LEFT (OUTER) JOIN</h3>
    <p><code>LEFT JOIN</code> (또는 <code>LEFT OUTER JOIN</code>)은 왼쪽 테이블(<code>FROM</code> 절에 먼저 나온 테이블)의 모든 행을 포함하고, 오른쪽 테이블에서는 조인 조건을 만족하는 행만 가져옵니다. 오른쪽 테이블에 일치하는 행이 없으면 해당 열들은 <code>NULL</code> 값으로 채워집니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 모든 직원의 이름과 해당 직원의 부서 이름 조회 (부서가 없는 직원도 포함)</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_name</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span> <span class="sql-comment">-- 왼쪽 테이블</span>
<span class="sql-keyword">LEFT</span> <span class="sql-keyword">JOIN</span>
    <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 오른쪽 테이블</span>
</code></pre>
     <div class="example">
        <h4>LEFT JOIN 결과 예시</h4>
        <table class="sql-example">
            <thead><tr><th>name</th><th>department_name</th></tr></thead>
            <tbody>
                <tr><td>홍길동</td><td>인사팀</td></tr>
                <tr><td>김철수</td><td>개발팀</td></tr>
                <tr><td>이영희</td><td>인사팀</td></tr>
                <tr><td>박지성</td><td>NULL</td></tr> <span class="sql-comment">-- 부서가 없어도 포함됨</span>
                 <tr style="opacity: 0.5;"><td><del>NULL (직원 없음)</del></td><td><del>영업팀</del></td></tr>
            </tbody>
        </table>
    </div>

    <h3 id="join-right">RIGHT (OUTER) JOIN</h3>
    <p><code>RIGHT JOIN</code> (또는 <code>RIGHT OUTER JOIN</code>)은 <code>LEFT JOIN</code>과 반대로 오른쪽 테이블(<code>JOIN</code> 키워드 뒤에 나온 테이블)의 모든 행을 포함하고, 왼쪽 테이블에서는 조인 조건을 만족하는 행만 가져옵니다. 왼쪽 테이블에 일치하는 행이 없으면 해당 열들은 <code>NULL</code> 값으로 채워집니다.</p>
     <pre><code class="language-sql"><span class="sql-comment">-- 모든 부서 이름과 해당 부서의 직원 이름 조회 (직원이 없는 부서도 포함)</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_name</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span> <span class="sql-comment">-- 왼쪽 테이블</span>
<span class="sql-keyword">RIGHT</span> <span class="sql-keyword">JOIN</span>
    <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 오른쪽 테이블</span>
</code></pre>
     <div class="example">
        <h4>RIGHT JOIN 결과 예시</h4>
        <table class="sql-example">
            <thead><tr><th>name</th><th>department_name</th></tr></thead>
            <tbody>
                <tr><td>홍길동</td><td>인사팀</td></tr>
                <tr><td>이영희</td><td>인사팀</td></tr>
                <tr><td>김철수</td><td>개발팀</td></tr>
                <tr><td>NULL</td><td>영업팀</td></tr> <span class="sql-comment">-- 직원이 없어도 포함됨</span>
                 <tr style="opacity: 0.5;"><td><del>박지성</del></td><td><del>NULL (부서 없음)</del></td></tr>
            </tbody>
        </table>
    </div>
    <p class="note"><code>RIGHT JOIN</code>은 <code>LEFT JOIN</code>으로 테이블 순서를 바꿔서 동일한 결과를 얻을 수 있으므로, 가독성을 위해 <code>LEFT JOIN</code>을 더 선호하는 경향이 있습니다.</p>

    <h3 id="join-full">FULL OUTER JOIN</h3>
    <p><code>FULL OUTER JOIN</code> (또는 <code>FULL JOIN</code>)은 왼쪽 테이블과 오른쪽 테이블의 모든 행을 포함합니다. 조인 조건이 일치하면 해당 행을 연결하고, 어느 한쪽에 일치하는 행이 없으면 해당 테이블의 열은 <code>NULL</code> 값으로 채워집니다.</p>
    <p class="warning"><code>FULL OUTER JOIN</code>은 모든 RDBMS에서 지원하지는 않습니다. (예: MySQL은 지원하지 않음). 지원하지 않는 경우, <code>LEFT JOIN</code>과 <code>RIGHT JOIN</code> 결과를 <code>UNION</code>하여 유사한 결과를 얻을 수 있습니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 모든 직원과 모든 부서 정보를 조회 (일치하지 않는 경우 NULL)</span>
<span class="sql-comment">-- (표준 SQL 문법, MySQL 제외)</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_name</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span>
<span class="sql-keyword">FULL</span> <span class="sql-keyword">OUTER</span> <span class="sql-keyword">JOIN</span>
    <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- MySQL에서 FULL OUTER JOIN 흉내 내기 (LEFT JOIN UNION RIGHT JOIN)</span>
<span class="sql-comment">-- (UNION은 중복 제거, 성능 고려 필요)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span> <span class="sql-keyword">LEFT</span> <span class="sql-keyword">JOIN</span> <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span>
<span class="sql-keyword">UNION</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span> <span class="sql-keyword">RIGHT</span> <span class="sql-keyword">JOIN</span> <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span><span class="sql-punctuation">;</span>
</code></pre>
    <div class="example">
        <h4>FULL OUTER JOIN 결과 예시</h4>
        <table class="sql-example">
            <thead><tr><th>name</th><th>department_name</th></tr></thead>
            <tbody>
                <tr><td>홍길동</td><td>인사팀</td></tr>
                <tr><td>김철수</td><td>개발팀</td></tr>
                <tr><td>이영희</td><td>인사팀</td></tr>
                <tr><td>박지성</td><td>NULL</td></tr> <span class="sql-comment">-- 부서 없는 직원</span>
                <tr><td>NULL</td><td>영업팀</td></tr> <span class="sql-comment">-- 직원 없는 부서</span>
            </tbody>
        </table>
    </div>

    <h3 id="join-cross">CROSS JOIN</h3>
    <p><code>CROSS JOIN</code>은 첫 번째 테이블의 모든 행과 두 번째 테이블의 모든 행을 조합하여 가능한 모든 경우의 수를 결과로 반환합니다 (카티전 곱, Cartesian Product). 일반적으로 <code>ON</code> 조건을 사용하지 않습니다.</p>
    <p>결과 행의 수는 `(첫 번째 테이블 행 수) * (두 번째 테이블 행 수)` 가 됩니다. 매우 큰 결과가 나올 수 있으므로 주의해서 사용해야 합니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 모든 직원과 모든 부서의 가능한 모든 조합 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_name</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span>
<span class="sql-keyword">CROSS</span> <span class="sql-keyword">JOIN</span> <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 콤마(,)를 사용한 암시적 크로스 조인 (오래된 방식, 권장 X)</span>
<span class="sql-comment">-- SELECT e.name, d.department_name FROM employees e, departments d;</span>
</code></pre>
     <p>주로 테스트 데이터를 생성하거나 특정 조건의 조합을 만들어야 할 때 제한적으로 사용됩니다.</p>

    <h3 id="join-self">SELF JOIN</h3>
    <p><code>SELF JOIN</code>은 테이블이 자기 자신과 조인하는 것입니다. 동일한 테이블을 서로 다른 별칭(Alias)으로 참조하여 테이블 내의 행들 간의 관계를 찾을 때 사용됩니다.</p>
    <p>예시: 직원의 이름과 그 직원의 매니저 이름을 조회 (매니저 정보도 같은 `employees` 테이블에 있다고 가정, 예: `manager_id` 열)</p>
     <pre><code class="language-sql"><span class="sql-comment">-- 직원과 해당 직원의 매니저 이름 조회</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">e1</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">employee_name</span><span class="sql-punctuation">,</span> <span class="sql-comment">-- 직원 테이블 별칭 e1</span>
    <span class="sql-identifier">e2</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">manager_name</span>   <span class="sql-comment">-- 매니저 테이블 별칭 e2</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">employees</span> <span class="sql-identifier">e1</span>
<span class="sql-keyword">LEFT</span> <span class="sql-keyword">JOIN</span> <span class="sql-comment">-- 매니저가 없는 직원도 포함하기 위해 LEFT JOIN 사용</span>
    <span class="sql-identifier">employees</span> <span class="sql-identifier">e2</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e1</span><span class="sql-punctuation">.</span><span class="sql-identifier">manager_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">e2</span><span class="sql-punctuation">.</span><span class="sql-identifier">employee_id</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 직원의 manager_id와 매니저의 employee_id 연결</span>
</code></pre>

    <h3 id="join-on-using">ON vs USING</h3>
    <p>조인 조건을 지정하는 방법입니다.</p>
    <ul>
        <li><code>ON</code>: 가장 일반적인 방법으로, <code>ON table1.column = table2.column</code> 과 같이 조인할 열과 조건을 명시적으로 지정합니다. 복잡한 조인 조건(비교 외 조건 포함)도 가능합니다.</li>
        <li><code>USING(column_name)</code>: 조인하려는 두 테이블의 열 이름이 동일할 경우 <code>ON</code> 절 대신 사용할 수 있는 축약형입니다. 괄호 안에 공통된 열 이름을 명시합니다. 결과에서 해당 열은 하나만 표시됩니다.
             <pre><code class="language-sql"><span class="sql-comment">-- ON 사용 (일반적)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span> <span class="sql-keyword">JOIN</span> <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- USING 사용 (열 이름 department_id가 동일)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span> <span class="sql-keyword">JOIN</span> <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span> <span class="sql-keyword">USING</span> <span class="sql-punctuation">(</span><span class="sql-identifier">department_id</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 결과에서 department_id 열은 하나만 나옴</span>
</code></pre>
        </li>
        <li><code>NATURAL JOIN</code>: 두 테이블 간에 이름이 같은 모든 열을 기준으로 자동으로 조인합니다. 편리해 보이지만, 예상치 못한 열이 조인 조건에 포함될 수 있고 테이블 구조 변경에 취약하므로 사용을 권장하지 않습니다.</li>
    </ul>
</section>


<br><br>
<hr>

<section id="sql-subqueries">
    <h2>서브쿼리 (Subqueries)</h2>
    <p>서브쿼리(Subquery 또는 Nested Query)는 다른 SQL 문장 안에 포함된 <code>SELECT</code> 문입니다. 서브쿼리는 복잡한 조건을 설정하거나, 주 쿼리(Outer Query)에서 사용할 값을 동적으로 생성하는 등 다양한 용도로 활용됩니다.</p>
    <p>서브쿼리는 주로 <code>WHERE</code>, <code>SELECT</code>, <code>FROM</code>, <code>HAVING</code> 절 내부에서 사용됩니다.</p>

    <h3 id="subquery-where">WHERE 절의 서브쿼리</h3>
    <p>가장 흔한 형태로, <code>WHERE</code> 절의 조건 값으로 서브쿼리의 결과를 사용합니다.</p>
    <ul>
        <li><strong>단일 행, 단일 열 반환 서브쿼리:</strong> 비교 연산자(<code>=</code>, <code>&lt;</code>, <code>&gt;</code> 등)와 함께 사용됩니다.
            <pre><code class="language-sql"><span class="sql-comment">-- 평균 가격보다 비싼 상품 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">product_name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">price</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">price</span> <span class="sql-operator">&gt;</span> <span class="sql-punctuation">(</span><span class="sql-keyword">SELECT</span> <span class="sql-function">AVG</span><span class="sql-punctuation">(</span><span class="sql-identifier">price</span><span class="sql-punctuation">)</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">products</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span></code></pre>
        </li>
        <li><strong>다중 행, 단일 열 반환 서브쿼리:</strong> <code>IN</code>, <code>NOT IN</code>, <code>ANY</code>, <code>ALL</code> 연산자와 함께 사용됩니다.
            <pre><code class="language-sql"><span class="sql-comment">-- '서울'에 사는 고객들이 주문한 주문 내역 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">orders</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">customer_id</span> <span class="sql-keyword">IN</span> <span class="sql-punctuation">(</span><span class="sql-keyword">SELECT</span> <span class="sql-identifier">customer_id</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'서울'</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 특정 부서(예: department_id=10)의 어떤 직원보다 급여를 많이 받는 직원 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">salary</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">salary</span> <span class="sql-operator">&gt;</span> <span class="sql-keyword">ANY</span> <span class="sql-punctuation">(</span><span class="sql-keyword">SELECT</span> <span class="sql-identifier">salary</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-number">10</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>
<span class="sql-comment">-- salary > (부서 10의 최소 급여) 와 동일</span>

<span class="sql-comment">-- 특정 부서(예: department_id=20)의 모든 직원보다 급여를 많이 받는 직원 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">salary</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">salary</span> <span class="sql-operator">&gt;</span> <span class="sql-keyword">ALL</span> <span class="sql-punctuation">(</span><span class="sql-keyword">SELECT</span> <span class="sql-identifier">salary</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-number">20</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>
<span class="sql-comment">-- salary > (부서 20의 최대 급여) 와 동일</span>
</code></pre>
        </li>
    </ul>

    <h3 id="subquery-select">SELECT 절의 서브쿼리 (스칼라 서브쿼리)</h3>
    <p><code>SELECT</code> 절 내부에 사용되는 서브쿼리는 반드시 단일 값(하나의 행, 하나의 열)만 반환해야 합니다. 이를 스칼라 서브쿼리(Scalar Subquery)라고 합니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 각 고객의 이름과 해당 고객의 총 주문 횟수 조회</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span>
    <span class="sql-punctuation">(</span><span class="sql-keyword">SELECT</span> <span class="sql-function">COUNT</span><span class="sql-punctuation">(</span><span class="sql-operator">*</span><span class="sql-punctuation">)</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">orders</span> <span class="sql-identifier">o</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">order_count</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">customers</span> <span class="sql-identifier">c</span><span class="sql-punctuation">;</span>
</code></pre>
    <p class="note">스칼라 서브쿼리는 편리하지만, 각 행마다 실행될 수 있어 성능에 영향을 미칠 수 있습니다. JOIN으로 동일한 결과를 얻을 수 있다면 JOIN 사용을 고려하는 것이 좋습니다.</p>

    <h3 id="subquery-from">FROM 절의 서브쿼리 (인라인 뷰)</h3>
    <p><code>FROM</code> 절에 사용되는 서브쿼리는 결과를 가상의 테이블(임시 테이블)처럼 취급합니다. 이를 인라인 뷰(Inline View) 또는 파생 테이블(Derived Table)이라고 하며, 반드시 별칭(Alias)을 지정해야 합니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 각 도시별 평균 주문 금액 조회</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">cust_city_orders</span><span class="sql-punctuation">.</span><span class="sql-identifier">city</span><span class="sql-punctuation">,</span>
    <span class="sql-function">AVG</span><span class="sql-punctuation">(</span><span class="sql-identifier">cust_city_orders</span><span class="sql-punctuation">.</span><span class="sql-identifier">total_amount</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">average_order_amount</span>
<span class="sql-keyword">FROM</span> <span class="sql-punctuation">(</span> <span class="sql-comment">-- FROM 절의 서브쿼리 시작</span>
    <span class="sql-keyword">SELECT</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">city</span><span class="sql-punctuation">,</span> <span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">total_amount</span>
    <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-identifier">c</span>
    <span class="sql-keyword">JOIN</span> <span class="sql-identifier">orders</span> <span class="sql-identifier">o</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span>
<span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">cust_city_orders</span> <span class="sql-comment">-- 서브쿼리 결과에 대한 별칭 필수!</span>
<span class="sql-keyword">GROUP</span> <span class="sql-keyword">BY</span>
    <span class="sql-identifier">cust_city_orders</span><span class="sql-punctuation">.</span><span class="sql-identifier">city</span><span class="sql-punctuation">;</span>
</code></pre>

    <h3 id="subquery-correlated">상관 서브쿼리 (Correlated Subquery)</h3>
    <p>상관 서브쿼리는 서브쿼리 내부에서 주 쿼리(Outer Query)의 열을 참조하는 서브쿼리입니다. 주 쿼리의 각 행마다 서브쿼리가 다시 실행되므로 성능에 영향을 줄 수 있습니다.</p>
    <p>위 <code>SELECT</code> 절 서브쿼리 예시가 상관 서브쿼리의 대표적인 예입니다 (<code>o.customer_id = c.customer_id</code> 부분).</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 자신의 부서 평균 급여보다 많이 받는 직원 조회</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">e1</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">e1</span><span class="sql-punctuation">.</span><span class="sql-identifier">salary</span><span class="sql-punctuation">,</span> <span class="sql-identifier">e1</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">employees</span> <span class="sql-identifier">e1</span>
<span class="sql-keyword">WHERE</span>
    <span class="sql-identifier">e1</span><span class="sql-punctuation">.</span><span class="sql-identifier">salary</span> <span class="sql-operator">&gt;</span> <span class="sql-punctuation">(</span>
        <span class="sql-keyword">SELECT</span> <span class="sql-function">AVG</span><span class="sql-punctuation">(</span><span class="sql-identifier">e2</span><span class="sql-punctuation">.</span><span class="sql-identifier">salary</span><span class="sql-punctuation">)</span>
        <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-identifier">e2</span>
        <span class="sql-keyword">WHERE</span> <span class="sql-identifier">e2</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">e1</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-comment">-- 주 쿼리의 department_id 참조</span>
    <span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>
</code></pre>

    <h3 id="subquery-exists">EXISTS 연산자</h3>
    <p><code>EXISTS</code> 연산자는 서브쿼리가 하나 이상의 행을 반환하면 <code>true</code>를, 그렇지 않으면 <code>false</code>를 반환합니다. 서브쿼리 결과의 실제 내용에는 관심이 없고 존재 여부만 확인할 때 사용하며, 종종 <code>IN</code> 보다 효율적일 수 있습니다.</p>
    <p><code>NOT EXISTS</code>는 서브쿼리가 어떠한 행도 반환하지 않으면 <code>true</code>를 반환합니다.</p>
    <pre><code class="language-sql"><span class="sql-comment">-- 한 번이라도 주문한 적이 있는 고객 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-identifier">c</span>
<span class="sql-keyword">WHERE</span> <span class="sql-keyword">EXISTS</span> <span class="sql-punctuation">(</span>
    <span class="sql-keyword">SELECT</span> <span class="sql-number">1</span> <span class="sql-comment">-- 실제 값은 중요하지 않음, 행 존재 여부만 확인</span>
    <span class="sql-keyword">FROM</span> <span class="sql-identifier">orders</span> <span class="sql-identifier">o</span>
    <span class="sql-keyword">WHERE</span> <span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span>
<span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 직원이 한 명도 없는 부서 조회</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_name</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">departments</span> <span class="sql-identifier">d</span>
<span class="sql-keyword">WHERE</span> <span class="sql-keyword">NOT</span> <span class="sql-keyword">EXISTS</span> <span class="sql-punctuation">(</span>
    <span class="sql-keyword">SELECT</span> <span class="sql-number">1</span>
    <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span> <span class="sql-identifier">e</span>
    <span class="sql-keyword">WHERE</span> <span class="sql-identifier">e</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">d</span><span class="sql-punctuation">.</span><span class="sql-identifier">department_id</span>
<span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>
</code></pre>
</section>

<section id="sql-set-operations">
    <h2>집합 연산자 (Set Operations)</h2>
    <p>집합 연산자는 두 개 이상의 <code>SELECT</code> 문의 결과 집합을 결합하거나 비교하는 데 사용됩니다.</p>
    <p class="warning">집합 연산자를 사용하려면 각 <code>SELECT</code> 문의 열 개수와 데이터 타입이 호환되어야 합니다. 열 이름은 첫 번째 <code>SELECT</code> 문을 따릅니다.</p>

    <h3 id="set-union-unionall">UNION, UNION ALL</h3>
    <ul>
        <li><code>UNION</code>: 두 개 이상의 <code>SELECT</code> 문 결과를 결합하고 중복된 행은 제거합니다.</li>
        <li><code>UNION ALL</code>: 두 개 이상의 <code>SELECT</code> 문 결과를 결합하되, 중복된 행을 포함하여 모든 행을 반환합니다. 중복 제거 과정이 없으므로 <code>UNION</code>보다 성능상 이점이 있습니다.</li>
    </ul>
     <pre><code class="language-sql"><span class="sql-comment">-- 서울 또는 부산에 사는 고객 목록 (UNION)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">city</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'서울'</span>
<span class="sql-keyword">UNION</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">city</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'부산'</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 모든 직원과 모든 고객의 이름 목록 (UNION ALL - 중복 허용)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span>
<span class="sql-keyword">UNION</span> <span class="sql-keyword">ALL</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span>
</code></pre>

    <h3 id="set-intersect-except">INTERSECT, EXCEPT (MINUS)</h3>
    <p>이 연산자들은 모든 RDBMS에서 지원하지 않을 수 있습니다.</p>
    <ul>
        <li><code>INTERSECT</code>: 두 <code>SELECT</code> 문 결과에 모두 존재하는 행만 반환합니다 (교집합).</li>
        <li><code>EXCEPT</code> (표준 SQL, PostgreSQL 등) 또는 <code>MINUS</code> (Oracle): 첫 번째 <code>SELECT</code> 문 결과에는 존재하지만 두 번째 <code>SELECT</code> 문 결과에는 존재하지 않는 행만 반환합니다 (차집합).</li>
    </ul>
    <pre><code class="language-sql"><span class="sql-comment">-- 직원(employees)이면서 동시에 고객(customers)인 사람의 이름 (지원하는 DB에서)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span>
<span class="sql-keyword">INTERSECT</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 직원이지만 고객은 아닌 사람의 이름 (지원하는 DB에서)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">employees</span>
<span class="sql-keyword">EXCEPT</span> <span class="sql-comment">-- 또는 MINUS</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">name</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span><span class="sql-punctuation">;</span>
</code></pre>
</section>

<section id="sql-dml">
    <h2>데이터 조작어 (DML)</h2>
    <p>DML(Data Manipulation Language)은 테이블의 데이터를 삽입, 수정, 삭제하는 SQL 명령어입니다.</p>

    <h3 id="dml-insert">INSERT INTO</h3>
    <p>테이블에 새로운 행(데이터)을 삽입합니다.</p>
    <ul>
        <li><strong>단일 행 삽입:</strong>
            <pre><code class="language-sql"><span class="sql-keyword">INSERT</span> <span class="sql-keyword">INTO</span> <span class="sql-identifier">table_name</span> <span class="sql-punctuation">(</span><span class="sql-identifier">column1</span><span class="sql-punctuation">,</span> <span class="sql-identifier">column2</span><span class="sql-punctuation">,</span> <span class="sql-punctuation">...)</span>
<span class="sql-keyword">VALUES</span> <span class="sql-punctuation">(</span><span class="sql-identifier">value1</span><span class="sql-punctuation">,</span> <span class="sql-identifier">value2</span><span class="sql-punctuation">,</span> <span class="sql-punctuation">...)</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 예시: customers 테이블에 새 고객 추가</span>
<span class="sql-keyword">INSERT</span> <span class="sql-keyword">INTO</span> <span class="sql-identifier">customers</span> <span class="sql-punctuation">(</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">email</span><span class="sql-punctuation">,</span> <span class="sql-identifier">city</span><span class="sql-punctuation">,</span> <span class="sql-identifier">signup_date</span><span class="sql-punctuation">)</span>
<span class="sql-keyword">VALUES</span> <span class="sql-punctuation">(</span><span class="sql-string">'새고객'</span><span class="sql-punctuation">,</span> <span class="sql-string">'new@example.com'</span><span class="sql-punctuation">,</span> <span class="sql-string">'인천'</span><span class="sql-punctuation">,</span> <span class="sql-function">CURDATE</span><span class="sql-punctuation">(</span><span class="sql-punctuation">)</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- CURDATE()는 현재 날짜 반환 함수 (RDBMS마다 다름)</span>
</code></pre>
            <p>열 목록을 생략하면 테이블 정의 순서대로 모든 열에 값을 지정해야 합니다.</p>
        </li>
        <li><strong>다중 행 삽입:</strong> <code>VALUES</code> 절 뒤에 여러 개의 값 목록을 쉼표로 구분하여 지정합니다 (표준 SQL 및 대부분 RDBMS 지원).
             <pre><code class="language-sql"><span class="sql-keyword">INSERT</span> <span class="sql-keyword">INTO</span> <span class="sql-identifier">products</span> <span class="sql-punctuation">(</span><span class="sql-identifier">product_name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">price</span><span class="sql-punctuation">)</span> <span class="sql-keyword">VALUES</span>
<span class="sql-punctuation">(</span><span class="sql-string">'상품A'</span><span class="sql-punctuation">,</span> <span class="sql-number">15000</span><span class="sql-punctuation">)</span><span class="sql-punctuation">,</span>
<span class="sql-punctuation">(</span><span class="sql-string">'상품B'</span><span class="sql-punctuation">,</span> <span class="sql-number">22000</span><span class="sql-punctuation">)</span><span class="sql-punctuation">,</span>
<span class="sql-punctuation">(</span><span class="sql-string">'상품C'</span><span class="sql-punctuation">,</span> <span class="sql-number">8000</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span></code></pre>
        </li>
         <li><strong>다른 테이블 결과 삽입:</strong> <code>SELECT</code> 문의 결과를 다른 테이블에 삽입합니다.
             <pre><code class="language-sql"><span class="sql-keyword">INSERT</span> <span class="sql-keyword">INTO</span> <span class="sql-identifier">archived_orders</span> <span class="sql-punctuation">(</span><span class="sql-identifier">order_id</span><span class="sql-punctuation">,</span> <span class="sql-identifier">customer_id</span><span class="sql-punctuation">,</span> <span class="sql-identifier">order_date</span><span class="sql-punctuation">)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">order_id</span><span class="sql-punctuation">,</span> <span class="sql-identifier">customer_id</span><span class="sql-punctuation">,</span> <span class="sql-identifier">order_date</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">orders</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">order_date</span> <span class="sql-operator">&lt;</span> <span class="sql-string">'2024-01-01'</span><span class="sql-punctuation">;</span></code></pre>
        </li>
    </ul>

    <h3 id="dml-update">UPDATE</h3>
    <p>테이블의 기존 행 데이터를 수정합니다. <strong><code>WHERE</code> 절을 사용하여 수정할 행을 정확히 지정하는 것이 매우 중요합니다.</strong> <code>WHERE</code> 절이 없으면 테이블의 모든 행이 수정됩니다!</p>
    <p><code>UPDATE table_name SET column1 = value1, column2 = value2, ... WHERE condition;</code></p>
    <pre><code class="language-sql"><span class="sql-comment">-- customer_id가 2인 고객의 도시를 '인천'으로 변경</span>
<span class="sql-keyword">UPDATE</span> <span class="sql-identifier">customers</span>
<span class="sql-keyword">SET</span> <span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'인천'</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">customer_id</span> <span class="sql-operator">=</span> <span class="sql-number">2</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 가격이 10000 미만인 모든 상품 가격을 10% 인상</span>
<span class="sql-keyword">UPDATE</span> <span class="sql-identifier">products</span>
<span class="sql-keyword">SET</span> <span class="sql-identifier">price</span> <span class="sql-operator">=</span> <span class="sql-identifier">price</span> <span class="sql-operator">*</span> <span class="sql-number">1.1</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">price</span> <span class="sql-operator">&lt;</span> <span class="sql-number">10000</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- !!!주의!!! WHERE 절 없는 UPDATE는 모든 행을 변경!</span>
<span class="sql-comment">-- UPDATE customers SET points = 0; -- 모든 고객의 포인트를 0으로 변경</span>
</code></pre>

    <h3 id="dml-delete">DELETE</h3>
    <p>테이블에서 기존 행을 삭제합니다. <strong><code>WHERE</code> 절을 사용하여 삭제할 행을 정확히 지정하는 것이 매우 중요합니다.</strong> <code>WHERE</code> 절이 없으면 테이블의 모든 행이 삭제됩니다!</p>
    <p><code>DELETE FROM table_name WHERE condition;</code></p>
     <pre><code class="language-sql"><span class="sql-comment">-- order_id가 101인 주문 삭제</span>
<span class="sql-keyword">DELETE</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">orders</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">order_id</span> <span class="sql-operator">=</span> <span class="sql-number">101</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 특정 날짜 이전의 모든 로그 삭제</span>
<span class="sql-keyword">DELETE</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">activity_log</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">log_date</span> <span class="sql-operator">&lt;</span> <span class="sql-string">'2023-01-01'</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- !!!주의!!! WHERE 절 없는 DELETE는 모든 행을 삭제!</span>
<span class="sql-comment">-- DELETE FROM customers; -- customers 테이블의 모든 데이터 삭제</span>
</code></pre>

    <h3 id="dml-truncate">TRUNCATE TABLE</h3>
    <p><code>TRUNCATE TABLE table_name;</code></p>
    <p><code>TRUNCATE TABLE</code>은 테이블의 모든 행을 매우 빠르게 삭제합니다. <code>DELETE FROM table_name;</code> (WHERE 절 없이)과 유사하지만, 동작 방식에 중요한 차이가 있습니다:</p>
    <ul>
        <li><code>TRUNCATE</code>는 일반적으로 <code>DELETE</code>보다 훨씬 빠릅니다 (테이블을 비우고 새로 만드는 방식과 유사).</li>
        <li><code>TRUNCATE</code>는 롤백(Rollback)할 수 없습니다 (트랜잭션 로그를 기록하지 않음).</li>
        <li><code>TRUNCATE</code>는 테이블의 Auto Increment 값을 초기화하는 경우가 많습니다.</li>
        <li><code>TRUNCATE</code>는 <code>WHERE</code> 절을 사용할 수 없습니다.</li>
    </ul>
    <p>테이블 구조는 유지하면서 모든 데이터를 빠르고 효율적으로 삭제하고 싶을 때 사용하지만, 되돌릴 수 없으므로 매우 주의해야 합니다.</p>
</section>

<section id="sql-ddl">
    <h2>데이터 정의어 (DDL)</h2>
    <p>DDL(Data Definition Language)은 데이터베이스 객체(데이터베이스, 테이블, 뷰, 인덱스 등)의 구조를 정의, 수정, 삭제하는 SQL 명령어입니다.</p>
    <p class="warning">DDL 문은 실행 즉시 적용되며 일반적으로 롤백(Rollback)할 수 없으므로, 특히 <code>ALTER</code>나 <code>DROP</code> 사용 시 매우 신중해야 합니다.</p>

    <h3 id="ddl-database">CREATE/ALTER/DROP DATABASE</h3>
    <ul>
        <li><code>CREATE DATABASE database_name;</code>: 새 데이터베이스를 생성합니다.</li>
        <li><code>ALTER DATABASE database_name ...;</code>: 데이터베이스 설정을 변경합니다 (RDBMS마다 옵션 상이).</li>
        <li><code>DROP DATABASE database_name;</code>: 데이터베이스를 영구적으로 삭제합니다. 내부의 모든 테이블과 데이터가 사라지므로 절대적으로 주의해야 합니다.</li>
    </ul>

    <h3 id="ddl-table">CREATE TABLE (데이터 타입, 제약 조건)</h3>
    <p>새로운 테이블을 생성하고 테이블의 열(column)과 각 열의 데이터 타입, 그리고 제약 조건(constraints)을 정의합니다.</p>
    <p><code>CREATE TABLE table_name ( column1 datatype constraints, column2 datatype constraints, ... [table_constraints] );</code></p>
    <h4>주요 데이터 타입 (RDBMS마다 조금씩 다름):</h4>
    <ul>
        <li>정수형: <code>INT</code> (또는 <code>INTEGER</code>), <code>SMALLINT</code>, <code>TINYINT</code>, <code>BIGINT</code></li>
        <li>실수형: <code>FLOAT</code>, <code>DOUBLE</code>, <code>DECIMAL(p, s)</code> (또는 <code>NUMERIC(p, s)</code> - 고정 소수점, 금액 계산 등에 사용. p=총 자릿수, s=소수부 자릿수)</li>
        <li>문자열형:
            <ul>
                <li><code>CHAR(n)</code>: 고정 길이 문자열 (길이 n).</li>
                <li><code>VARCHAR(n)</code>: 가변 길이 문자열 (최대 길이 n). 가장 흔하게 사용됨.</li>
                <li><code>TEXT</code>: 긴 가변 길이 문자열 (길이 제한 큼). <code>TINYTEXT</code>, <code>MEDIUMTEXT</code>, <code>LONGTEXT</code> 등.</li>
            </ul>
        </li>
        <li>날짜/시간형: <code>DATE</code> (날짜), <code>TIME</code> (시간), <code>DATETIME</code> (날짜와 시간), <code>TIMESTAMP</code> (날짜와 시간, 타임존 처리나 자동 업데이트 기능 있을 수 있음), <code>YEAR</code></li>
        <li>이진형: <code>BOOLEAN</code> (또는 <code>BOOL</code>, 일부 DB는 <code>TINYINT(1)</code> 사용), <code>BLOB</code> (Binary Large Object - 이미지 등)</li>
        <li>기타: <code>ENUM</code>, <code>SET</code> (MySQL), JSON (최신 DB) 등</li>
    </ul>

    <h3 id="ddl-constraints">제약 조건 (Constraints)</h3>
    <p>테이블에 저장되는 데이터의 규칙(무결성)을 강제하는 조건입니다.</p>
    <ul>
        <li><code>NOT NULL</code>: 해당 열에 <code>NULL</code> 값을 허용하지 않습니다.</li>
        <li><code>UNIQUE</code>: 해당 열(또는 열 조합)의 값이 테이블 내에서 고유해야 합니다 (<code>NULL</code>은 허용될 수 있음).</li>
        <li><code>PRIMARY KEY</code>: 행을 고유하게 식별하는 기본 키를 정의합니다. <code>NOT NULL</code>과 <code>UNIQUE</code> 제약 조건을 자동으로 포함합니다. 테이블 당 하나만 정의 가능.</li>
        <li><code>FOREIGN KEY</code>: 다른 테이블의 기본 키를 참조하여 테이블 간의 관계를 설정합니다. 참조 무결성을 강제합니다.
            <ul><li><code>REFERENCES referenced_table (referenced_column)</code>: 참조할 테이블과 열을 지정.</li><li><code>ON DELETE [RESTRICT | CASCADE | SET NULL | NO ACTION | SET DEFAULT]</code>: 참조하는 행 삭제 시 동작 정의.</li><li><code>ON UPDATE [RESTRICT | CASCADE | SET NULL | NO ACTION | SET DEFAULT]</code>: 참조하는 행 수정 시 동작 정의.</li></ul>
        </li>
        <li><code>DEFAULT value</code>: 행 삽입 시 해당 열에 값이 지정되지 않으면 사용할 기본값을 설정합니다.</li>
        <li><code>CHECK (condition)</code>: 해당 열의 값이 만족해야 하는 조건을 정의합니다 (일부 RDBMS 미지원 또는 제한적 지원).</li>
        <li>Auto Increment / Serial: 행 삽입 시 자동으로 증가하는 숫자 값을 생성합니다 (주로 기본 키에 사용). 문법은 RDBMS마다 다름 (MySQL: <code>AUTO_INCREMENT</code>, PostgreSQL: <code>SERIAL</code> 또는 Identity Columns, SQL Server: <code>IDENTITY</code>).</li>
    </ul>
    <p>제약 조건은 열 정의 시 인라인(inline)으로 정의하거나, 테이블 정의 끝에서 테이블 레벨(out-of-line)로 정의할 수 있습니다 (특히 복합 키(여러 열 조합)나 제약 조건에 이름을 부여할 때 사용).</p>
     <pre><code class="language-sql"><span class="sql-comment">-- 예시: posts 테이블 생성</span>
<span class="sql-keyword">CREATE</span> <span class="sql-keyword">TABLE</span> <span class="sql-identifier">posts</span> <span class="sql-punctuation">(</span>
    <span class="sql-identifier">post_id</span> <span class="sql-keyword">INT</span> <span class="sql-keyword">AUTO_INCREMENT</span> <span class="sql-keyword">PRIMARY</span> <span class="sql-keyword">KEY</span><span class="sql-punctuation">,</span> <span class="sql-comment">-- 자동 증가 기본 키 (MySQL)</span>
    <span class="sql-identifier">user_id</span> <span class="sql-keyword">INT</span> <span class="sql-keyword">NOT</span> <span class="sql-keyword">NULL</span><span class="sql-punctuation">,</span>                 <span class="sql-comment">-- 작성자 ID (NULL 불가)</span>
    <span class="sql-identifier">title</span> <span class="sql-keyword">VARCHAR</span><span class="sql-punctuation">(</span><span class="sql-number">255</span><span class="sql-punctuation">)</span> <span class="sql-keyword">NOT</span> <span class="sql-keyword">NULL</span><span class="sql-punctuation">,</span>         <span class="sql-comment">-- 제목 (NULL 불가)</span>
    <span class="sql-identifier">body</span> <span class="sql-keyword">TEXT</span><span class="sql-punctuation">,</span>                          <span class="sql-comment">-- 본문 (긴 텍스트)</span>
    <span class="sql-identifier">status</span> <span class="sql-keyword">VARCHAR</span><span class="sql-punctuation">(</span><span class="sql-number">20</span><span class="sql-punctuation">)</span> <span class="sql-keyword">DEFAULT</span> <span class="sql-string">'draft'</span><span class="sql-punctuation">,</span>  <span class="sql-comment">-- 상태 (기본값 'draft')</span>
    <span class="sql-identifier">created_at</span> <span class="sql-keyword">TIMESTAMP</span> <span class="sql-keyword">DEFAULT</span> <span class="sql-keyword">CURRENT_TIMESTAMP</span><span class="sql-punctuation">,</span> <span class="sql-comment">-- 생성 시각 (기본값 현재 시간)</span>
    <span class="sql-identifier">updated_at</span> <span class="sql-keyword">TIMESTAMP</span> <span class="sql-keyword">DEFAULT</span> <span class="sql-keyword">CURRENT_TIMESTAMP</span> <span class="sql-keyword">ON</span> <span class="sql-keyword">UPDATE</span> <span class="sql-keyword">CURRENT_TIMESTAMP</span><span class="sql-punctuation">,</span> <span class="sql-comment">-- 수정 시각 (자동 업데이트 - MySQL)</span>

    <span class="sql-comment">-- 외래 키 제약 조건 (테이블 레벨 정의)</span>
    <span class="sql-keyword">CONSTRAINT</span> <span class="sql-identifier">fk_user</span> <span class="sql-comment">-- 제약 조건 이름 부여 (선택 사항)</span>
        <span class="sql-keyword">FOREIGN</span> <span class="sql-keyword">KEY</span> <span class="sql-punctuation">(</span><span class="sql-identifier">user_id</span><span class="sql-punctuation">)</span> <span class="sql-keyword">REFERENCES</span> <span class="sql-identifier">users</span><span class="sql-punctuation">(</span><span class="sql-identifier">user_id</span><span class="sql-punctuation">)</span> <span class="sql-comment">-- users 테이블의 user_id 참조</span>
        <span class="sql-keyword">ON</span> <span class="sql-keyword">DELETE</span> <span class="sql-keyword">CASCADE</span> <span class="sql-comment">-- 사용자가 삭제되면 해당 사용자의 게시글도 함께 삭제</span>
        <span class="sql-comment">-- ON DELETE SET NULL, ON DELETE RESTRICT 등 다른 옵션 가능</span>
<span class="sql-punctuation">)</span><span class="sql-punctuation">;</span></code></pre>

    <h3 id="ddl-alter-table">ALTER TABLE (열 추가/수정/삭제, 제약 조건 추가/삭제)</h3>
    <p>기존 테이블의 구조를 변경합니다. 문법은 RDBMS마다 차이가 클 수 있습니다.</p>
    <ul>
        <li><strong>열 추가:</strong> <code>ALTER TABLE table_name ADD COLUMN column_name datatype constraints;</code></li>
        <li><strong>열 삭제:</strong> <code>ALTER TABLE table_name DROP COLUMN column_name;</code></li>
        <li><strong>열 수정 (타입, 제약 조건 변경):</strong> <code>ALTER TABLE table_name MODIFY COLUMN column_name new_datatype new_constraints;</code> (MySQL) 또는 <code>ALTER TABLE table_name ALTER COLUMN ...</code> (PostgreSQL, SQL Server)</li>
        <li><strong>제약 조건 추가/삭제:</strong> <code>ALTER TABLE table_name ADD CONSTRAINT ...;</code>, <code>ALTER TABLE table_name DROP CONSTRAINT ...;</code> (또는 <code>DROP PRIMARY KEY</code>, <code>DROP FOREIGN KEY</code> 등)</li>
        <li><strong>열/테이블 이름 변경:</strong> <code>ALTER TABLE table_name RENAME COLUMN old_name TO new_name;</code>, <code>ALTER TABLE table_name RENAME TO new_table_name;</code> (문법 상이)</li>
    </ul>
    <pre><code class="language-sql"><span class="sql-comment">-- posts 테이블에 published_at 열 추가 (DATE 타입, NULL 허용)</span>
<span class="sql-keyword">ALTER</span> <span class="sql-keyword">TABLE</span> <span class="sql-identifier">posts</span> <span class="sql-keyword">ADD</span> <span class="sql-keyword">COLUMN</span> <span class="sql-identifier">published_at</span> <span class="sql-keyword">DATE</span> <span class="sql-keyword">NULL</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- posts 테이블의 title 열 타입을 VARCHAR(255)에서 VARCHAR(500)으로 변경 (MySQL)</span>
<span class="sql-keyword">ALTER</span> <span class="sql-keyword">TABLE</span> <span class="sql-identifier">posts</span> <span class="sql-keyword">MODIFY</span> <span class="sql-keyword">COLUMN</span> <span class="sql-identifier">title</span> <span class="sql-keyword">VARCHAR</span><span class="sql-punctuation">(</span><span class="sql-number">500</span><span class="sql-punctuation">)</span> <span class="sql-keyword">NOT</span> <span class="sql-keyword">NULL</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- posts 테이블에서 status 열 삭제</span>
<span class="sql-keyword">ALTER</span> <span class="sql-keyword">TABLE</span> <span class="sql-identifier">posts</span> <span class="sql-keyword">DROP</span> <span class="sql-keyword">COLUMN</span> <span class="sql-identifier">status</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- posts 테이블에 UNIQUE 제약 조건 추가 (예: title)</span>
<span class="sql-keyword">ALTER</span> <span class="sql-keyword">TABLE</span> <span class="sql-identifier">posts</span> <span class="sql-keyword">ADD</span> <span class="sql-keyword">CONSTRAINT</span> <span class="sql-identifier">uq_post_title</span> <span class="sql-keyword">UNIQUE</span> <span class="sql-punctuation">(</span><span class="sql-identifier">title</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>
</code></pre>

    <h3 id="ddl-drop-table">DROP TABLE</h3>
    <p><code>DROP TABLE table_name;</code></p>
    <p>테이블을 영구적으로 삭제합니다. 테이블 구조와 모든 데이터가 사라지므로 절대적으로 주의해야 합니다. 외래 키 등으로 참조되는 테이블은 삭제가 제한될 수 있습니다.</p>
</section>

<section id="sql-indexes">
    <h2>인덱스 (Indexes)</h2>
    <p>인덱스는 데이터베이스 테이블에서 데이터 검색 성능을 향상시키기 위한 특별한 데이터 구조입니다. 책의 색인(index)과 유사하게, 특정 열(들)의 값을 빠르게 찾아 해당 행의 위치를 알려줍니다.</p>
    <h4>인덱스의 장점:</h4>
    <ul>
        <li><code>SELECT</code> 쿼리(특히 <code>WHERE</code>, <code>JOIN</code>, <code>ORDER BY</code> 절 사용 시)의 성능을 크게 향상시킬 수 있습니다.</li>
        <li>테이블의 <code>PRIMARY KEY</code>나 <code>UNIQUE</code> 제약 조건은 보통 자동으로 인덱스를 생성합니다.</li>
    </ul>
    <h4>인덱스의 단점:</h4>
    <ul>
        <li><code>INSERT</code>, <code>UPDATE</code>, <code>DELETE</code> 작업 시 인덱스도 함께 갱신되어야 하므로 쓰기 성능이 저하될 수 있습니다.</li>
        <li>인덱스 자체도 저장 공간을 차지합니다.</li>
    </ul>
    <h4>인덱스 생성 및 삭제:</h4>
    <ul>
        <li><code>CREATE INDEX index_name ON table_name (column1, column2, ...);</code></li>
        <li><code>DROP INDEX index_name ON table_name;</code> (문법은 RDBMS마다 다를 수 있음)</li>
    </ul>
    <h4>언제 인덱스를 생성해야 하는가?</h4>
    <ul>
        <li><code>WHERE</code> 절에서 자주 사용되는 열</li>
        <li><code>JOIN</code> 조건으로 자주 사용되는 열 (외래 키는 종종 자동으로 인덱싱됨)</li>
        <li><code>ORDER BY</code> 절에서 자주 사용되는 열</li>
        <li>데이터 값이 매우 다양하고(카디널리티가 높고) 테이블 크기가 클 때 효과적입니다.</li>
    </ul>
     <pre><code class="language-sql"><span class="sql-comment">-- customers 테이블의 email 열에 인덱스 생성</span>
<span class="sql-keyword">CREATE</span> <span class="sql-keyword">INDEX</span> <span class="sql-identifier">idx_customer_email</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">customers</span> <span class="sql-punctuation">(</span><span class="sql-identifier">email</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- orders 테이블의 customer_id와 order_date 조합에 인덱스 생성</span>
<span class="sql-keyword">CREATE</span> <span class="sql-keyword">INDEX</span> <span class="sql-identifier">idx_order_customer_date</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">orders</span> <span class="sql-punctuation">(</span><span class="sql-identifier">customer_id</span><span class="sql-punctuation">,</span> <span class="sql-identifier">order_date</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 생성된 인덱스 삭제</span>
<span class="sql-comment">-- DROP INDEX idx_customer_email ON customers; -- (MySQL)</span>
<span class="sql-comment">-- DROP INDEX idx_customer_email; -- (PostgreSQL, Oracle)</span>
</code></pre>
    <p>인덱스 설계는 데이터베이스 성능에 큰 영향을 미치므로 신중하게 고려해야 합니다.</p>
</section>

<section id="sql-views">
    <h2>뷰 (Views)</h2>
    <p>뷰(View)는 하나 이상의 테이블이나 다른 뷰를 기반으로 하는 가상의 테이블입니다. 뷰 자체는 데이터를 저장하지 않지만, 정의된 <code>SELECT</code> 쿼리를 통해 실제 테이블의 데이터에 접근합니다.</p>
    <h4>뷰의 장점 및 사용 사례:</h4>
    <ul>
        <li><strong>쿼리 단순화:</strong> 복잡한 <code>SELECT</code> 문(예: 여러 테이블 조인, 집계 함수 사용)을 뷰로 만들어두면, 사용자는 간단한 <code>SELECT * FROM view_name;</code> 쿼리로 원하는 데이터를 조회할 수 있습니다.</li>
        <li><strong>보안:</strong> 사용자에게 테이블 전체가 아닌 특정 열이나 특정 조건에 맞는 행만 보여주도록 접근을 제한할 수 있습니다.</li>
        <li><strong>데이터 추상화:</strong> 기반 테이블 구조가 변경되더라도 뷰의 구조는 유지하여(뷰 정의 수정 필요) 응용 프로그램에 미치는 영향을 줄일 수 있습니다.</li>
    </ul>
    <h4>뷰 생성 및 삭제:</h4>
    <ul>
        <li><code>CREATE VIEW view_name AS SELECT column1, column2, ... FROM table_name WHERE condition;</code></li>
        <li><code>DROP VIEW view_name;</code></li>
    </ul>
     <pre><code class="language-sql"><span class="sql-comment">-- '서울' 지역 고객 정보만 보여주는 뷰 생성</span>
<span class="sql-keyword">CREATE</span> <span class="sql-keyword">VIEW</span> <span class="sql-identifier">seoul_customers</span> <span class="sql-keyword">AS</span>
<span class="sql-keyword">SELECT</span> <span class="sql-identifier">customer_id</span><span class="sql-punctuation">,</span> <span class="sql-identifier">name</span><span class="sql-punctuation">,</span> <span class="sql-identifier">email</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span>
<span class="sql-keyword">WHERE</span> <span class="sql-identifier">city</span> <span class="sql-operator">=</span> <span class="sql-string">'서울'</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 생성된 뷰 조회 (테이블처럼 사용)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-operator">*</span> <span class="sql-keyword">FROM</span> <span class="sql-identifier">seoul_customers</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 고객별 총 주문 금액을 보여주는 뷰 생성</span>
<span class="sql-keyword">CREATE</span> <span class="sql-keyword">VIEW</span> <span class="sql-identifier">customer_total_spent</span> <span class="sql-keyword">AS</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span><span class="sql-punctuation">,</span>
    <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">,</span>
    <span class="sql-function">SUM</span><span class="sql-punctuation">(</span><span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">total_amount</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">total_spent</span>
<span class="sql-keyword">FROM</span> <span class="sql-identifier">customers</span> <span class="sql-identifier">c</span>
<span class="sql-keyword">LEFT</span> <span class="sql-keyword">JOIN</span> <span class="sql-identifier">orders</span> <span class="sql-identifier">o</span> <span class="sql-keyword">ON</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span> <span class="sql-operator">=</span> <span class="sql-identifier">o</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span>
<span class="sql-keyword">GROUP</span> <span class="sql-keyword">BY</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">customer_id</span><span class="sql-punctuation">,</span> <span class="sql-identifier">c</span><span class="sql-punctuation">.</span><span class="sql-identifier">name</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 뷰 삭제</span>
<span class="sql-comment">-- DROP VIEW seoul_customers;</span>
</code></pre>
    <p>단순한 뷰는 업데이트(<code>INSERT</code>, <code>UPDATE</code>, <code>DELETE</code>)가 가능할 수도 있지만, 조인, 집계 함수, <code>GROUP BY</code> 등을 포함한 복잡한 뷰는 일반적으로 업데이트가 불가능하거나 제한적입니다.</p>
</section>

<section id="sql-transactions">
    <h2>트랜잭션 (Transactions)</h2>
    <p>트랜잭션은 데이터베이스 작업을 수행하는 논리적인 단위로, 여러 개의 SQL 문(예: INSERT, UPDATE, DELETE)이 하나의 작업처럼 묶여서 처리되는 것을 의미합니다. 트랜잭션 내의 모든 작업은 전부 성공적으로 완료되거나(Commit), 하나라도 실패하면 모든 작업이 취소되어 원상 복구되어야(Rollback) 합니다.</p>

    <h3 id="transaction-acid">ACID 속성</h3>
    <p>트랜잭션은 데이터 무결성과 일관성을 보장하기 위해 다음과 같은 네 가지 속성(ACID)을 만족해야 합니다.</p>
    <ul>
        <li><strong>Atomicity (원자성):</strong> 트랜잭션 내의 모든 작업은 전부 실행되거나 전부 실행되지 않아야 합니다 (All or Nothing).</li>
        <li><strong>Consistency (일관성):</strong> 트랜잭션이 성공적으로 완료되면 데이터베이스는 항상 일관된 상태를 유지해야 합니다 (정의된 규칙, 제약 조건 등 만족).</li>
        <li><strong>Isolation (고립성/격리성):</strong> 여러 트랜잭션이 동시에 실행될 때, 각 트랜잭션은 다른 트랜잭션의 영향을 받지 않고 독립적으로 실행되는 것처럼 보여야 합니다. (격리 수준(Isolation Level) 설정에 따라 정도가 다름)</li>
        <li><strong>Durability (지속성):</strong> 성공적으로 완료된(Commit된) 트랜잭션의 결과는 시스템 장애가 발생하더라도 영구적으로 데이터베이스에 저장되어야 합니다.</li>
    </ul>

    <h3 id="transaction-commands">COMMIT, ROLLBACK, SAVEPOINT</h3>
    <p>트랜잭션을 제어하는 주요 명령어입니다.</p>
    <ul>
        <li><code>START TRANSACTION;</code> (또는 <code>BEGIN;</code>): 트랜잭션을 시작합니다. 이 시점 이후의 작업들은 하나의 논리적 단위로 묶입니다. (많은 RDBMS는 기본적으로 각 SQL 문이 자동 커밋(Autocommit)되는 모드이므로, 트랜잭션을 사용하려면 명시적으로 시작해야 합니다.)</li>
        <li><code>COMMIT;</code>: 트랜잭션 내의 모든 작업을 성공적으로 완료하고, 변경 사항을 데이터베이스에 영구적으로 반영합니다. 트랜잭션이 종료됩니다.</li>
        <li><code>ROLLBACK;</code>: 트랜잭션 내에서 수행된 모든 작업을 취소하고, 트랜잭션 시작 이전 상태로 데이터베이스를 되돌립니다. 트랜잭션이 종료됩니다.</li>
        <li><code>SAVEPOINT savepoint_name;</code>: 트랜잭션 내에 임시 저장점을 만듭니다.</li>
        <li><code>ROLLBACK TO SAVEPOINT savepoint_name;</code>: 특정 저장점까지의 작업만 롤백합니다. 트랜잭션은 종료되지 않습니다.</li>
    </ul>
     <pre><code class="language-sql"><span class="sql-comment">-- 예시: 계좌 이체 (A 계좌에서 B 계좌로 10000원 이체) - 원자성 중요!</span>

<span class="sql-keyword">START</span> <span class="sql-keyword">TRANSACTION</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 트랜잭션 시작</span>

<span class="sql-keyword">UPDATE</span> <span class="sql-identifier">accounts</span> <span class="sql-keyword">SET</span> <span class="sql-identifier">balance</span> <span class="sql-operator">=</span> <span class="sql-identifier">balance</span> <span class="sql-operator">-</span> <span class="sql-number">10000</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">account_id</span> <span class="sql-operator">=</span> <span class="sql-string">'A'</span><span class="sql-punctuation">;</span>
<span class="sql-keyword">UPDATE</span> <span class="sql-identifier">accounts</span> <span class="sql-keyword">SET</span> <span class="sql-identifier">balance</span> <span class="sql-operator">=</span> <span class="sql-identifier">balance</span> <span class="sql-operator">+</span> <span class="sql-number">10000</span> <span class="sql-keyword">WHERE</span> <span class="sql-identifier">account_id</span> <span class="sql-operator">=</span> <span class="sql-string">'B'</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 두 작업이 모두 성공했는지 확인 (실제로는 더 복잡한 확인 필요)</span>
<span class="sql-comment">-- 만약 중간에 오류가 발생했다면...</span>
<span class="sql-comment">-- ROLLBACK; -- 모든 변경 사항 취소</span>

<span class="sql-comment">-- 모든 작업이 성공했다면...</span>
<span class="sql-keyword">COMMIT</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 변경 사항 영구 저장</span>
</code></pre>
    <p>트랜잭션은 은행 업무, 예약 시스템 등 데이터 일관성이 매우 중요한 작업에 필수적입니다.</p>
</section>

<section id="sql-data-types">
    <h2>주요 데이터 타입 (복습)</h2>
    <p>앞서 <a href="#ddl-table">CREATE TABLE</a> 섹션에서 언급된 주요 데이터 타입을 다시 한번 요약합니다. 실제 사용 가능한 타입과 세부 옵션은 사용하는 RDBMS 문서를 참조해야 합니다.</p>
    <ul>
        <li>숫자형:
            <ul>
                <li><code>INT</code> / <code>INTEGER</code>: 정수. <code>TINYINT</code>, <code>SMALLINT</code>, <code>BIGINT</code> 등 크기에 따른 변형.</li>
                <li><code>DECIMAL(p, s)</code> / <code>NUMERIC(p, s)</code>: 고정 소수점 숫자 (정확한 계산 필요 시). p=총 자릿수, s=소수부 자릿수.</li>
                <li><code>FLOAT</code> / <code>DOUBLE</code>: 부동 소수점 숫자 (근사값).</li>
            </ul>
        </li>
         <li>문자열형:
            <ul>
                <li><code>CHAR(n)</code>: 고정 길이 문자열.</li>
                <li><code>VARCHAR(n)</code>: 가변 길이 문자열 (가장 일반적).</li>
                <li><code>TEXT</code>: 긴 가변 길이 문자열.</li>
            </ul>
        </li>
        <li>날짜/시간형:
            <ul>
                <li><code>DATE</code>: 날짜 (YYYY-MM-DD).</li>
                <li><code>TIME</code>: 시간 (HH:MM:SS).</li>
                <li><code>DATETIME</code>: 날짜와 시간.</li>
                <li><code>TIMESTAMP</code>: 날짜와 시간 (타임존 처리나 자동 업데이트 기능 포함 가능).</li>
                <li><code>YEAR</code>: 연도.</li>
            </ul>
        </li>
        <li>기타:
            <ul>
                <li><code>BOOLEAN</code> / <code>BOOL</code> (또는 <code>TINYINT(1)</code>).</li>
                <li><code>BLOB</code>: 바이너리 데이터.</li>
                <li><code>ENUM</code>, <code>SET</code> (MySQL 특정).</li>
                <li><code>JSON</code> (최신 DB 지원).</li>
            </ul>
        </li>
    </ul>
</section>

<section id="sql-functions">
    <h2>내장 함수 (Built-in Functions)</h2>
    <p>대부분의 RDBMS는 데이터 처리 및 조작을 위한 다양한 내장 함수를 제공합니다. 함수 이름이나 사용법은 RDBMS마다 다를 수 있으므로, 해당 시스템의 문서를 참조하는 것이 중요합니다.</p>

    <h3 id="functions-string">문자열 함수</h3>
    <ul>
        <li><code>CONCAT(str1, str2, ...)</code> (또는 <code>||</code> 연산자 - 표준 SQL, Oracle, PostgreSQL): 문자열들을 이어붙입니다.</li>
        <li><code>LENGTH(str)</code> (또는 <code>LEN(str)</code> - SQL Server): 문자열의 길이(바이트 단위 또는 문자 단위 - 설정에 따라 다름)를 반환합니다.</li>
        <li><code>SUBSTRING(str, start, length)</code> (또는 <code>SUBSTR</code>): 문자열의 일부를 추출합니다.</li>
        <li><code>UPPER(str)</code> / <code>UCASE(str)</code>: 문자열을 대문자로 변환합니다.</li>
        <li><code>LOWER(str)</code> / <code>LCASE(str)</code>: 문자열을 소문자로 변환합니다.</li>
        <li><code>REPLACE(str, from_str, to_str)</code>: 문자열 내의 특정 부분을 다른 문자열로 치환합니다.</li>
        <li><code>TRIM([BOTH|LEADING|TRAILING] [remstr] FROM str)</code>: 문자열 앞/뒤/양쪽의 공백 또는 지정된 문자를 제거합니다. <code>LTRIM</code>, <code>RTRIM</code>도 사용 가능.</li>
        <li><code>LEFT(str, length)</code> / <code>RIGHT(str, length)</code>: 문자열의 왼쪽/오른쪽에서 지정된 길이만큼 추출합니다.</li>
        <li><code>INSTR(str, substr)</code> / <code>POSITION(substr IN str)</code>: 문자열 내에서 다른 문자열의 시작 위치를 찾습니다.</li>
    </ul>
    <pre><code class="language-sql"><span class="sql-keyword">SELECT</span> <span class="sql-function">CONCAT</span><span class="sql-punctuation">(</span><span class="sql-string">'SQL '</span><span class="sql-punctuation">,</span> <span class="sql-string">'is '</span><span class="sql-punctuation">,</span> <span class="sql-string">'Fun!'</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 'SQL is Fun!'</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">LENGTH</span><span class="sql-punctuation">(</span><span class="sql-string">'Hello'</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 5</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">SUBSTRING</span><span class="sql-punctuation">(</span><span class="sql-string">'Programming'</span><span class="sql-punctuation">,</span> <span class="sql-number">5</span><span class="sql-punctuation">,</span> <span class="sql-number">7</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 'grammin' (시작 위치 1부터일 수도 있음 - DB 확인)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">UPPER</span><span class="sql-punctuation">(</span><span class="sql-string">'lower case'</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 'LOWER CASE'</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">REPLACE</span><span class="sql-punctuation">(</span><span class="sql-string">'Good morning!'</span><span class="sql-punctuation">,</span> <span class="sql-string">'morning'</span><span class="sql-punctuation">,</span> <span class="sql-string">'evening'</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 'Good evening!'</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">TRIM</span><span class="sql-punctuation">(</span><span class="sql-string">'   Extra Spaces   '</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 'Extra Spaces'</span>
</code></pre>

    <h3 id="functions-numeric">숫자 함수</h3>
    <ul>
        <li><code>ROUND(number, [decimals])</code>: 숫자를 지정된 소수점 자릿수로 반올림합니다.</li>
        <li><code>CEILING(number)</code> / <code>CEIL(number)</code>: 숫자보다 크거나 같은 가장 작은 정수(올림)를 반환합니다.</li>
        <li><code>FLOOR(number)</code>: 숫자보다 작거나 같은 가장 큰 정수(내림)를 반환합니다.</li>
        <li><code>ABS(number)</code>: 숫자의 절대값을 반환합니다.</li>
        <li><code>MOD(number, divisor)</code> (또는 <code>%</code> 연산자): 나머지를 반환합니다.</li>
        <li><code>SQRT(number)</code>: 숫자의 제곱근을 반환합니다.</li>
        <li><code>POWER(base, exponent)</code> / <code>POW(base, exponent)</code>: 거듭제곱을 반환합니다.</li>
        <li><code>RAND()</code> (또는 유사 함수): 0 이상 1 미만의 난수를 반환합니다.</li>
    </ul>
     <pre><code class="language-sql"><span class="sql-keyword">SELECT</span> <span class="sql-function">ROUND</span><span class="sql-punctuation">(</span><span class="sql-number">123.456</span><span class="sql-punctuation">,</span> <span class="sql-number">2</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 123.46</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">CEILING</span><span class="sql-punctuation">(</span><span class="sql-number">7.1</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 8</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">FLOOR</span><span class="sql-punctuation">(</span><span class="sql-number">7.9</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 7</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">ABS</span><span class="sql-punctuation">(</span><span class="sql-operator">-</span><span class="sql-number">10</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 10</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">MOD</span><span class="sql-punctuation">(</span><span class="sql-number">10</span><span class="sql-punctuation">,</span> <span class="sql-number">3</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- 1</span>
</code></pre>

    <h3 id="functions-date">날짜/시간 함수</h3>
    <p>날짜와 시간 값을 처리하고 계산하는 함수입니다. RDBMS마다 함수 이름과 형식이 매우 다양합니다.</p>
    <ul>
        <li>현재 날짜/시간: <code>NOW()</code>, <code>CURRENT_TIMESTAMP</code>, <code>GETDATE()</code>(SQL Server), <code>SYSDATE</code>(Oracle)</li>
        <li>현재 날짜: <code>CURDATE()</code>, <code>CURRENT_DATE</code></li>
        <li>현재 시간: <code>CURTIME()</code>, <code>CURRENT_TIME</code></li>
        <li>날짜/시간 부분 추출: <code>YEAR()</code>, <code>MONTH()</code>, <code>DAY()</code> (또는 <code>DAYOFMONTH</code>), <code>HOUR()</code>, <code>MINUTE()</code>, <code>SECOND()</code>, <code>DATE()</code>, <code>TIME()</code>, <code>EXTRACT(unit FROM date)</code></li>
        <li>날짜/시간 형식 지정: <code>DATE_FORMAT(date, format)</code>(MySQL), <code>TO_CHAR(date, format)</code>(Oracle, PostgreSQL), <code>FORMAT(date, format)</code>(SQL Server)</li>
        <li>날짜/시간 계산: <code>DATE_ADD(date, INTERVAL expr unit)</code>, <code>DATE_SUB(date, INTERVAL expr unit)</code>(MySQL), 날짜 + 숫자(Oracle), 날짜 + INTERVAL(PostgreSQL)</li>
        <li>날짜/시간 차이: <code>DATEDIFF(date1, date2)</code>, <code>TIMESTAMPDIFF(unit, start, end)</code>(MySQL)</li>
    </ul>
     <pre><code class="language-sql"><span class="sql-comment">-- RDBMS별 함수 예시 (MySQL 기준)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">NOW</span><span class="sql-punctuation">(</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>                     <span class="sql-comment">-- 현재 날짜와 시간</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">CURDATE</span><span class="sql-punctuation">(</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>                 <span class="sql-comment">-- 현재 날짜</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">YEAR</span><span class="sql-punctuation">(</span><span class="sql-string">'2025-04-27'</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>         <span class="sql-comment">-- 2025</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">DATE_FORMAT</span><span class="sql-punctuation">(</span><span class="sql-function">NOW</span><span class="sql-punctuation">(</span><span class="sql-punctuation">)</span><span class="sql-punctuation">,</span> <span class="sql-string">'%Y년 %m월 %d일 %H:%i:%s'</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span> <span class="sql-comment">-- '2025년 04월 27일 19:37:42' (현재 시간 기준)</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">DATE_ADD</span><span class="sql-punctuation">(</span><span class="sql-string">'2025-04-27'</span><span class="sql-punctuation">,</span> <span class="sql-keyword">INTERVAL</span> <span class="sql-number">7</span> <span class="sql-keyword">DAY</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>      <span class="sql-comment">-- '2025-05-04'</span>
<span class="sql-keyword">SELECT</span> <span class="sql-function">DATEDIFF</span><span class="sql-punctuation">(</span><span class="sql-string">'2025-05-01'</span><span class="sql-punctuation">,</span> <span class="sql-string">'2025-04-27'</span><span class="sql-punctuation">)</span><span class="sql-punctuation">;</span>    <span class="sql-comment">-- 4 (일 수 차이)</span>
</code></pre>

    <h3 id="functions-conditional">조건 함수 (CASE, IF)</h3>
    <p>조건에 따라 다른 값을 반환하는 함수입니다.</p>
    <ul>
        <li><code>CASE WHEN condition1 THEN result1 WHEN condition2 THEN result2 ... ELSE result_else END</code>: 표준 SQL의 조건 분기 구문입니다. 여러 조건을 순차적으로 확인합니다.</li>
        <li><code>IF(condition, value_if_true, value_if_false)</code>: 일부 RDBMS(MySQL 등)에서 지원하는 간단한 조건 분기 함수입니다.</li>
    </ul>
     <pre><code class="language-sql"><span class="sql-comment">-- 가격대에 따라 상품 등급 분류 (CASE 사용)</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">product_name</span><span class="sql-punctuation">,</span>
    <span class="sql-identifier">price</span><span class="sql-punctuation">,</span>
    <span class="sql-keyword">CASE</span>
        <span class="sql-keyword">WHEN</span> <span class="sql-identifier">price</span> <span class="sql-operator">&gt;=</span> <span class="sql-number">100000</span> <span class="sql-keyword">THEN</span> <span class="sql-string">'고가'</span>
        <span class="sql-keyword">WHEN</span> <span class="sql-identifier">price</span> <span class="sql-operator">&gt;=</span> <span class="sql-number">50000</span> <span class="sql-keyword">THEN</span> <span class="sql-string">'중가'</span>
        <span class="sql-keyword">ELSE</span> <span class="sql-string">'저가'</span>
    <span class="sql-keyword">END</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">price_grade</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">products</span><span class="sql-punctuation">;</span>

<span class="sql-comment">-- 재고 유무 표시 (IF 사용 - MySQL)</span>
<span class="sql-keyword">SELECT</span>
    <span class="sql-identifier">product_name</span><span class="sql-punctuation">,</span>
    <span class="sql-function">IF</span><span class="sql-punctuation">(</span><span class="sql-identifier">stock</span> <span class="sql-operator">&gt;</span> <span class="sql-number">0</span><span class="sql-punctuation">,</span> <span class="sql-string">'재고 있음'</span><span class="sql-punctuation">,</span> <span class="sql-string">'품절'</span><span class="sql-punctuation">)</span> <span class="sql-keyword">AS</span> <span class="sql-identifier">stock_status</span>
<span class="sql-keyword">FROM</span>
    <span class="sql-identifier">products</span><span class="sql-punctuation">;</span>
</code></pre>
</section>

<section id="sql-conclusion">
    <h2>마무리</h2>
    <p>이것으로 총 3개의 파트에 걸친 SQL 종합 강좌를 마칩니다. 이 강좌를 통해 데이터베이스의 기본 개념, 데이터 조회(SELECT), 필터링(WHERE), 정렬(ORDER BY), 그룹화(GROUP BY, HAVING), 테이블 조인(JOIN), 서브쿼리, 데이터 조작(INSERT, UPDATE, DELETE), 데이터 정의(CREATE, ALTER, DROP), 인덱스, 뷰, 트랜잭션 기초, 그리고 주요 내장 함수까지 SQL의 핵심적인 내용을 학습했습니다.</p>
    <p>SQL은 데이터를 다루는 거의 모든 분야에서 필수적인 기술입니다. 여기에 소개된 내용을 바탕으로 실제 데이터베이스 시스템(MySQL, PostgreSQL 등)을 설치하고 직접 테이블을 만들고 쿼리를 실행하며 연습하는 것이 매우 중요합니다.</p>
    <h4>다음 학습 단계 추천:</h4>
    <ul>
        <li><strong>특정 RDBMS 심화 학습:</strong> 주로 사용할 데이터베이스 시스템(예: MySQL, PostgreSQL)의 고유 기능, 함수, 성능 최적화 기법 등을 더 깊이 학습합니다.</li>
        <li><strong>SQL 튜닝 및 최적화:</strong> 실행 계획(Execution Plan) 분석, 인덱스 최적화, 쿼리 재작성 등 성능 개선 방법을 학습합니다.</li>
        <li><strong>데이터 모델링:</strong> 효율적이고 정규화된 데이터베이스 구조를 설계하는 방법을 배웁니다.</li>
        <li>NoSQL 데이터베이스: 관계형 데이터베이스 외에도 MongoDB, Redis, Cassandra 등 다양한 NoSQL 데이터베이스의 특징과 사용법을 알아보는 것도 좋습니다.</li>
        <li>프로그래밍 언어 연동: PHP, Python, Java, JavaScript(Node.js) 등 프로그래밍 언어에서 SQL 데이터베이스를 연동하여 애플리케이션을 개발하는 방법을 학습합니다. (예: PDO, MySQLi, ORM 라이브러리 사용법)</li>
        <li>데이터 분석 및 시각화: SQL로 추출한 데이터를 R, Python(Pandas), Tableau 등 도구를 사용하여 분석하고 시각화하는 방법을 배웁니다.</li>
    </ul>
     <p class="note"><strong>데이터는 현대 기술의 핵심입니다. SQL 능력을 꾸준히 갈고 닦아 데이터를 효과적으로 활용하는 전문가로 성장하시기를 응원합니다!</strong></p>
</section>


<br><br>
<hr>

<script src="../js/script.js?ver=1"></script>

</body>
</html>