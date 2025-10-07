<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));
   
if(isset($_REQUEST["check"])) 
	 $check=$_REQUEST["check"]; 
   else
     $check=$_POST["check"]; 
if($check==null)
		$check='1';
 ?>
 
 <?php include getDocumentRoot() . '/load_header.php' ?>

 <title> 발주처별 HPI 정보 </title>

<!-- Light & Subtle Theme CSS -->
<link rel="stylesheet" href="../css/dashboard-style.css" type="text/css" />

<style>
/* HPI List Specific Styles - Light & Subtle Theme */
.hpi-navigation-buttons {
    background: var(--gradient-primary);
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    box-shadow: var(--dashboard-shadow);
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.hpi-nav-btn {
    background: var(--gradient-accent);
    color: white !important;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(100, 116, 139, 0.15);
}

.hpi-nav-btn:hover {
    background: var(--gradient-info);
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(100, 116, 139, 0.25);
}

.hpi-section-title {
    color: var(--dashboard-text);
    font-weight: 600;
    font-size: 1.3rem;
    margin: 0;
}

.hpi-section-subtitle {
    color: var(--dashboard-text-secondary);
    font-weight: 500;
    margin: 1.5rem 0;
    font-size: 1.1rem;
}

.hpi-image-container {
    background: white;
    border: 2px solid var(--dashboard-border);
    border-radius: 8px;
    padding: 1.5rem;
    margin: 1.5rem 0;
    text-align: center;
    box-shadow: var(--dashboard-shadow);
    transition: all 0.2s ease;
}

.hpi-image-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.1);
}

.hpi-image-container img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.fill {
    object-fit: fill;
}

.contain {
    object-fit: contain;
}

.cover {
    width: auto;
    height: auto;
    object-fit: cover;
}

.img {
    width: auto;
    height: auto;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .hpi-navigation-buttons {
        padding: 1rem;
    }

    .hpi-nav-btn {
        display: block;
        width: 100%;
        margin: 0.25rem 0;
    }

    .hpi-image-container {
        padding: 1rem;
    }

    .hpi-section-title {
        font-size: 1.1rem;
    }

    .hpi-section-subtitle {
        font-size: 1rem;
    }
}
</style>

 </head>

  <script>
    function fnMove(seq){
        var offset = $("#div" + seq).offset();
        $('html, body').animate({scrollTop : offset.top}, 400);
    }
</script>
			
<body>

<? include '../myheader.php'; ?>  

<div class="container">

<div class="modern-management-card mt-2">
<div class="modern-dashboard-header">
	<h3 class="hpi-section-title text-center">HPI 정보</h3>
</div>

<div class="hpi-navigation-buttons">
   <button type="button" class="hpi-nav-btn" onclick="fnMove('1')">TKEK(티센)</button>
   <button type="button" class="hpi-nav-btn" onclick="fnMove('2')">OTIS(오티스)</button>
   <button type="button" class="hpi-nav-btn" onclick="fnMove('3')">누리엔지니어링</button>
   <button type="button" class="hpi-nav-btn" onclick="fnMove('4')">대오정공(동양)</button>
   <button type="button" class="hpi-nav-btn" onclick="fnMove('5')">한진엘리베이터(S05B)</button>
   <button type="button" class="hpi-nav-btn" onclick="fnMove('6')">동양엘리베이터(우성)</button>
   <button type="button" class="hpi-nav-btn" onclick="fnMove('7')">ETS1 노출형</button>
</div>
 
<form id="board_form" name="board_form" method="get" action="index.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>">  
<br>			
</div>

<div id="div1" class="modern-management-card">
<div class="modern-dashboard-header">
	<h3 class="hpi-section-title text-center">TKEK(티센) TK엘리베이터</h3>
</div>

<div style="padding: 1.5rem;">
<h4 class="hpi-section-subtitle text-center">TKEK(티센) HD-S20D/30D/20L/30L 동일</h4>

<div class="hpi-image-container">
	<img width="900px;" src="../img/hpi20ds.jpg">
</div>

<h3 class="hpi-section-subtitle text-center">HD-S20D/30D/20L/30L 막판 타공 형태 (노출형)</h3>

<div class="hpi-image-container">
	<img width="900px;" src="../img/20dpunch.jpg">
</div>

<h4 class="hpi-section-subtitle text-center">TKEK(티센) 매립형 HD-S55D/50D 동일</h4>

<div class="hpi-image-container">
	<img src="../img/hpi50ds.jpg">
</div>

<h3 class="hpi-section-subtitle text-center">매립형 HD-S55D/50D 막판 타공 형태 - ** 이 형태는 막판 하부 점검구 여부 확인필요함</h3>

<div class="hpi-image-container">
	<img src="../img/50dpunch1.jpg">
</div>

<div class="hpi-image-container">
	<img src="../img/50dpunch2.jpg">
</div>
</div>
</div>
<div id="div2" class="modern-management-card">
<div class="modern-dashboard-header">
	<h3 class="hpi-section-title text-center">OTIS(오티스) HIX모델</h3>
</div>

<div style="padding: 1.5rem;">
<h4 class="hpi-section-subtitle text-center">(매립형) HIX-A161,162,163,164,165 막판타공은 동일</h4>

<div class="hpi-image-container">
	<img src="../img/hixa161.jpg">
</div>

<h3 class="hpi-section-subtitle text-center">(매립형) HIX-A161,162,163 브라켓</h3>

<div class="hpi-image-container">
	<img src="../img/hixa161bracket.jpg">
</div>

<h3 class="hpi-section-subtitle text-center">(매립형) HIX-A164,165브라켓</h3>

<div class="hpi-image-container">
	<img src="../img/hixa164bracket.jpg">
</div>

<h3 class="hpi-section-subtitle text-center">(노출형) HIX-A201/A202 막판 타공</h3>

<div class="hpi-image-container">
	<img width="900px;" src="../img/hixa201.jpg">
</div>
</div>
</div>

<div id="div3" class="modern-management-card">
<div class="modern-dashboard-header">
	<h3 class="hpi-section-title text-center">누리엔지니어링 표준형</h3>
</div>

<div style="padding: 1.5rem;">
<h4 class="hpi-section-subtitle text-center">(매립형) 250*80 타공형(표준) 브라켓</h4>

<div class="hpi-image-container">
	<img src="../img/nurihpi.jpg">
</div>

<div class="hpi-image-container">
	<img src="../img/nurihpi2.jpg">
</div>
</div>
</div>  

<div id="div4" class="modern-management-card">
<div class="modern-dashboard-header">
	<h3 class="hpi-section-title text-center">대오정공(동양) 표준형</h3>
</div>

<div style="padding: 1.5rem;">
<h4 class="hpi-section-subtitle text-center">(매립형) 300*70 타공형(표준) 브라켓</h4>

<div class="hpi-image-container">
	<img src="../img/deohpi.jpg">
</div>

<div class="hpi-image-container">
	<img src="../img/deohpi2.jpg">
</div>

<div class="hpi-image-container">
	<img src="../img/300_85_HPI.png">
</div>
</div>
</div>  

<div id="div5" class="modern-management-card">
<div class="modern-dashboard-header">
	<h3 class="hpi-section-title text-center">한진엘리베이터 S05B(노출형)</h3>
</div>

<div style="padding: 1.5rem;">
<h4 class="hpi-section-subtitle text-center">(노출형) 170*50 타공형(표준) 전면 피스고정 3.4타공</h4>

<div class="hpi-image-container">
	<img src="../img/s05bhpi.jpg">
</div>

<div class="hpi-image-container">
	<img src="../img/s05bhpi2.jpg">
</div>

<div class="hpi-image-container">
	<img src="../img/s05b_real.png">
</div>
</div>
</div>  

<div id="div6" class="modern-management-card">
<div class="modern-dashboard-header">
	<h3 class="hpi-section-title text-center">동양엘리베이터(우성) (노출매립 혼용)</h3>
</div>

<div style="padding: 1.5rem;">
<h4 class="hpi-section-subtitle text-center">(매립노출혼용) 300*85 타공 브라켓 있음</h4>

<div class="hpi-image-container">
	<img src="../img/donghpi.jpg">
</div>

<div class="hpi-image-container">
	<img src="../img/donghpi2.jpg">
</div>
</div>
</div>  
   
<div id="div7" class="modern-management-card">
<div class="modern-dashboard-header">
	<h3 class="hpi-section-title text-center">ETS1 노출형</h3>
</div>

<div style="padding: 1.5rem;">
<h4 class="hpi-section-subtitle text-center">(노출) 300*70 타공 브라켓 있음</h4>

<div class="hpi-image-container">
	<img src="../img/ets1hpi1.jpg" style="width:80%;">
</div>

<div class="hpi-image-container">
	<img src="../img/ets1hpi2.jpg" style="width:80%;">
</div>

<div class="hpi-image-container">
	<img src="../img/ets1hpi3.jpg" style="width:80%;">
</div>

<div class="hpi-image-container">
	<img src="../img/ets1hpi4.jpg" style="width:80%;">
</div>
</div>
</div>  
			
		</form>
         </div> <!-- end of  container-fluid -->       
    

<script>
	$(document).ready(function(){
		saveLogData('jamb HPI 정보조회 '); // 다른 페이지에 맞는 menuName을 전달
	});
</script> 
	
	</body>  
  </html>
  



