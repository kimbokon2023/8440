
<!-- Section Menu Start -->
<!-- Header Start -->    
<!--<nav class="navbar navbar-expand-lg navigation fixed-top" id="navbar">-->
<nav class="navbar navbar-expand-lg navigation fixed-top" id="navbar">
	<div class="container-fluid">
		<a class="navbar-brand" href="index.php">
			<h3 class="text-white text-capitalize"></i>우성스틸앤<span class="text-color">엘리베이터</span></h3>
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsid"
			aria-controls="navbarsid" aria-expanded="false" aria-label="Toggle navigation">
			<span class="ti-view-list"></span>
		</button>
		<div class="collapse text-center navbar-collapse" id="navbarsid">
			<ul class="navbar-nav mx-auto">
				<li class="nav-item active">
					<a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item"><a class="nav-link" href="order.php">공사관리</a></li>
				<li class="nav-item"><a class="nav-link"  href="#" onclick="popupCenter('schedule.php' , '일정표', 900, 900);">일정</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
						aria-expanded="false">원자재</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="steel.php">원자재 입출고</a></li>
						<li><a class="dropdown-item" href="#">통계자료</a></li>						
					</ul>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
						aria-expanded="false">Etc Menu</a>
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="../login/logout.php">로그아웃</a></li>
						<li><a class="dropdown-item" href="../member/updateForm.php?id=<?=$_SESSION["userid"]?>">정보수정</a></li>						
					</ul>
				</li>
			</ul>
			<div class="my-md-0 ml-lg-4 mt-4 mt-lg-0 ml-auto text-lg-right mb-3 mb-lg-0">
				<a href="tel:+02-6339-6325">
					<h3 class="text-color mb-0"><i class="ti-mobile mr-2"></i>+02-6339-6325</h3>
				</a>
			</div>
		</div>
	</div>
</nav>
