<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php')); 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();  

// 기간을 정하는 구간	 
$todate = date("Y-m-d"); // 현재일자 변수지정   	
$common = " order by num desc ";  // 출고예정일이 현재일보다 클때 조건	
$sql = "select * from mirae8440.logdata " . $common; 			
$nowday = date("Y-m-d");   // 현재일자 변수지정   
$counter = 0;	
?>

<?php include getDocumentRoot() . '/load_header.php' ?>
<title>포미스톤 색상 정보 테이블</title>	
</head> 

<body class="p-10">
<div class="container mt-4 d-flex justify-content-center">
  <div>
    <h5 class="mb-4 fw-bold text-center">포미스톤 색상 정보 테이블</h5>
    <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm align-middle">
        <thead class="table-dark">
          <tr>
            <th class="text-center">영문 색상명</th>
            <th class="text-center">한글어 발음</th>
            <th class="text-center">뜻/느낌 설명</th>
          </tr>
        </thead>
        <tbody>
          <tr><td class="text-start">CASTOL WHITE</td><td class="text-start">캐스톨 화이트</td><td class="text-start">고급스러운 흰색, 약간 미색 느낌</td></tr>
          <tr><td class="text-start">VEIL GRAY</td><td class="text-start">베일 그레이</td><td class="text-start">얇은 베일처럼 흐릿한 회색</td></tr>
          <tr><td class="text-start">VEIL DARK GREY</td><td class="text-start">베일 다크 그레이</td><td class="text-start">짙은 회색, 안개 낀 느낌</td></tr>
          <tr><td class="text-start">SHAHARA LIGHT GRAY</td><td class="text-start">사하라 라이트 그레이</td><td class="text-start">사막의 밝은 회색, 모래빛 회색</td></tr>
          <tr><td class="text-start">CLOUD YELLOW</td><td class="text-start">클라우드 옐로우</td><td class="text-start">연한 노란색, 구름 틈 햇살 느낌</td></tr>
          <tr><td class="text-start">ANDES WHITE</td><td class="text-start">안데스 화이트</td><td class="text-start">산맥처럼 청량하고 밝은 흰색</td></tr>
          <tr><td class="text-start">NILE DARK GREY</td><td class="text-start">나일 다크 그레이</td><td class="text-start">나일강의 깊은 물빛 회색</td></tr>
          <tr><td class="text-start">ANDES GRAY</td><td class="text-start">안데스 그레이</td><td class="text-start">차분한 회색, 자연 느낌</td></tr>
          <tr><td class="text-start">SUNIS WHITE</td><td class="text-start">수니스 화이트</td><td class="text-start">밝고 따뜻한 흰색 (브랜드성 이름일 수 있음)</td></tr>
          <tr><td class="text-start">KAMU RED</td><td class="text-start">카무 레드</td><td class="text-start">강렬한 빨강색 (Kamu는 브랜드/지명일 수 있음)</td></tr>
          <tr><td class="text-start">CLOUD WHITE</td><td class="text-start">클라우드 화이트</td><td class="text-start">순백색, 구름처럼 뽀얀 느낌</td></tr>
          <tr><td class="text-start">PLAIN WHITE</td><td class="text-start">플레인 화이트</td><td class="text-start">순수하고 단순한 흰색</td></tr>
          <tr><td class="text-start">AUSTRALIA YELLOW</td><td class="text-start">오스트레일리아 옐로우</td><td class="text-start">따뜻한 호주 햇살 같은 노란색</td></tr>
          <tr><td class="text-start">GREYISH DESERT</td><td class="text-start">그레이쉬 데저트</td><td class="text-start">회갈색에 가까운 색, 건조한 느낌</td></tr>
          <tr><td class="text-start">DARK GRAY</td><td class="text-start">다크 그레이</td><td class="text-start">짙은 회색, 중후한 느낌</td></tr>
          <tr><td class="text-start">PORTORO</td><td class="text-start">포르토로</td><td class="text-start">고급 검정 대리석 색상 (금줄이 섞인 경우 많음)</td></tr>
          <tr><td class="text-start">NILE LIGHT YELLOW</td><td class="text-start">나일 라이트 옐로우</td><td class="text-start">은은한 노란색, 부드러운 느낌</td></tr>
          <tr><td class="text-start">CASTOL BLUE</td><td class="text-start">캐스톨 블루</td><td class="text-start">깊고 중후한 파란색 (브랜드 느낌)</td></tr>
          <tr><td class="text-start">ANDES YELLOW</td><td class="text-start">안데스 옐로우</td><td class="text-start">산맥의 빛나는 노란빛</td></tr>
          <tr><td class="text-start">BLUE GREY</td><td class="text-start">블루 그레이</td><td class="text-start">푸른빛이 도는 회색</td></tr>
          <tr><td class="text-start">ANDES GOLD</td><td class="text-start">안데스 골드</td><td class="text-start">금빛이 감도는 고급스러운 색상</td></tr>
          <tr><td class="text-start">MULTI-COLOR RED</td><td class="text-start">멀티컬러 레드</td><td class="text-start">여러 색이 섞인 붉은 계열</td></tr>
          <tr><td class="text-start">H02</td><td class="text-start">H02 (코드명)</td><td class="text-start">색상 코드, 별도 지정된 의미 없음</td></tr>
          <tr><td class="text-start">H04</td><td class="text-start">H04 (코드명)</td><td class="text-start">색상 코드, 별도 지정된 의미 없음</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
