<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php')); 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();  
?>

<?php include getDocumentRoot() . '/load_header.php' ?>
<title>포미스톤 코드별 질감,제품명,사이즈</title>
</head>

<body class="p-4">
<?php require_once(includePath('myheader.php')); ?>

<div class="container-fluid mt-4">
  <h5 class="mb-4 fw-bold text-center">포미스톤 코드별 질감, 제품명, 사이즈 </h5>
  <span class="mb-4 fw-bold text-center text-muted">미국식 Gray, 영국식 Grey 혼용표현 주의 </span>

  <div class="row">

<?php
$korean_pronunciation = [
  "Castol White" => "캐스톨 화이트",
  "Veil Gray" => "베일 그레이",
  "Veil Dark Grey" => "베일 다크 그레이",
  "Veil Dark Gray" => "베일 다크 그레이",
  "Shahara Light Gray" => "사하라 라이트 그레이",
  "Cloud Yellow" => "클라우드 옐로우",
  "Andes White" => "안데스 화이트",
  "Nile Dark Grey" => "나일 다크 그레이",
  "Andes Grey" => "안데스 그레이",
  "Andes Gray" => "안데스 그레이",
  "Sunis White" => "수니스 화이트",
  "Kamu Red" => "카무 레드",
  "Cloud White" => "클라우드 화이트",
  "Plain White" => "플레인 화이트",
  "Australila Yellow" => "오스트레일리아 옐로우",
  "Greyish Desert" => "그레이시 데져트",
  "Dark Gray" => "다크 그레이",
  "Portoro" => "포르토로",
  "Nile Light Yellow" => "나일 라이트 옐로우",
  "Veil White" => "베일 화이트",
  "Castol Blue" => "캐스톨 블루",
  "Andes Yellow" => "안데스 옐로우",
  "Blue Grey" => "블루 그레이",
  "Andes Gold" => "안데스 골드",
  "Multi-Color Red" => "멀티컬러 레드",
  "H02" => "H02",
  "H04" => "H04"
];

function render_table($rows, $thead_class, $text_class) {
  global $korean_pronunciation;
  echo "<div class='col-sm-4'>";
  echo "<div class='table-responsive'>";
  echo "<table class='table table-bordered table-striped table-sm align-middle'>";
  echo "<thead class='{$thead_class} text-center'><tr><th>Code</th><th>질감</th><th>제품명</th><th>사이즈</th></tr></thead>";
  echo "<tbody class='text-start'>";
  foreach ($rows as $row) {
    echo "<tr>";
    foreach ($row as $i => $col) {
      if ($i === 0) {
        echo "<td class='{$text_class} fw-bold'>{$col}</td>";
      } elseif ($i === 2) {
        $korean = $korean_pronunciation[$col] ?? '';
        $display = $korean ? "{$col} ({$korean})" : $col;
        echo "<td>{$display}</td>";
      } else {
        echo "<td>{$col}</td>";
      }
    }
    echo "</tr>";
  }
  echo "</tbody></table></div></div>";
}

$a_rows = [
  ["A1", "Marble", "Castol White", "1200*2400"], ["A2", "Marble", "Veil Gray", "1200*2400"], ["A3", "Marble", "Veil Dark Grey", "1200*2400"], ["A4", "Marble", "Shahara Light Gray", "1200*2400"],
  ["A5", "Marble", "Cloud Yellow", "1200*2400"], ["A6", "Marble", "Andes White", "1200*2400"], ["A7", "Marble", "Nile Dark Grey", "1200*2400"], ["A8", "Marble", "Andes Grey", "1200*2400"],
  ["A9", "Marble", "Sunis White", "1200*2400"], ["A10", "Marble", "Kamu Red", "1200*2400"], ["A11", "Rome Travertine", "Andes Gray", "1200*2400"], ["A12", "Rome Travertine", "Cloud White", "1200*2400"],
  ["A13", "Sandstone", "Plain White", "1200*2400"], ["A14", "Sandstone", "Australila Yellow", "1200*2400"], ["A15", "Polish Concrete Wall", "Greyish Desert", "1200*2400"], ["A16", "Polish Concrete Wall", "Dark Gray", "1200*2400"],
  ["A17", "Skyline", "Portoro", "1200*2400"], ["A18", "Skyline", "Veil Gray", "1200*2400"], ["A19", "Skyline", "Nile Light Yellow", "1200*2400"], ["A20", "Mount Celestial", "Castol White", "1200*2400"],
  ["A21", "Mount Celestial", "Veil White", "1200*2400"], ["A22", "Mount Celestial", "Veil Dark Gray", "1200*2400"], ["A23", "Mount Celestial", "Castol Blue", "1200*2400"], ["A24", "Devine Mushroom", "Andes Yellow", "1200*2400"],
  ["A25", "Devine Mushroom", "Blue Grey", "1200*2400"], ["A26", "Rough Surface", "Andes Gold", "1200*2400"], ["A27", "Rough Surface", "Portoro", "1200*2400"], ["A28", "Rough Surface", "Multi-Color Red", "1200*2400"],
  ["A29", "Arcurate Rock", "Greyish Desert", ""], ["A30", "Oman liner stone", "Andes White", ""], ["A31", "Oman liner stone", "Andes Yellow", ""], ["A32", "Oman liner stone", "H02", ""], ["A33", "Oman liner stone", "H04", ""]
];

$b_rows = [
  ["B1", "Marble", "Castol White", "1200*3000"], ["B2", "Marble", "Veil Gray", "1200*3000"], ["B3", "Marble", "Veil Dark Grey", "1200*3000"], ["B4", "Marble", "Shahara Light Gray", "1200*2700"],
  ["B5", "Marble", "Cloud Yellow", "1200*3000"], ["B6", "Marble", "Andes White", "1200*3000"], ["B7", "Marble", "Nile Dark Grey", "1200*3000"], ["B8", "Marble", "Andes Grey", "1200*2700"],
  ["B9", "Marble", "Sunis White", "1200*2700"], ["B10", "Marble", "Kamu Red", "1200*2700"], ["B11", "Rome Travertine", "Andes Gray", "1200*3000"], ["B12", "Rome Travertine", "Cloud White", "1200*3000"],
  ["B13", "Sandstone", "Plain White", "1200*2700"], ["B14", "Sandstone", "Australila Yellow", "1200*2700"], ["B15", "Polish Concrete Wall", "Greyish Desert", "1200*2700"], ["B16", "Polish Concrete Wall", "Dark Gray", "1200*2700"],
  ["B17", "Skyline", "Portoro", "1200*3000"], ["B18", "Skyline", "Veil Gray", "1200*3000"], ["B19", "Skyline", "Nile Light Yellow", "1200*3000"], ["B20", "Mount Celestial", "Castol White", "1200*3000"],
  ["B21", "Mount Celestial", "Veil White", "1200*3000"], ["B22", "Mount Celestial", "Veil Dark Gray", "1200*3000"], ["B23", "Mount Celestial", "Castol Blue", "1200*2700"], ["B24", "Devine Mushroom", "Andes Yellow", "운영 X"],
  ["B25", "Devine Mushroom", "Blue Grey", "운영 X"], ["B26", "Rough Surface", "Andes Gold", "1200*2700"], ["B27", "Rough Surface", "Portoro", "1200*2700"], ["B28", "Rough Surface", "Multi-Color Red", "1200*2700"],
  ["B29", "Arcurate Rock", "Greyish Desert", "1200*2700"], ["B30", "Oman liner stone", "Andes White", "1200*2700"], ["B31", "Oman liner stone", "Andes Yellow", "1200*2700"], ["B32", "Oman liner stone", "H02", "1200*2700"], ["B33", "Oman liner stone", "H04", "1200*2700"]
];

$z_rows = [
  ["Z1", "Marble", "Veil Dark Grey", "1200*600"],
  ["Z2", "Marble", "Shahara Light Gray", "1200*600"]
];

render_table($a_rows, "table-secondary", "text-dark");
render_table($b_rows, "table-primary", "text-primary");
render_table($z_rows, "table-info", "text-info");
?>
 
</div>
</div>
</body>
</html>
