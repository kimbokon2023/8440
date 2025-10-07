<?php\nrequire_once __DIR__ . '/common/functions.php';
require_once(includePath('session.php'));

 if(!isset($_SESSION["level"]) || $level>7) {	     
		 sleep(1);
         header ("Location:" . $WebSite . "login/logout.php");         
         exit;
   }     
$APIKEY = "2ddb841648d38606331320046099cf67";

 isset($_REQUEST["Lat"]) ? $Lat=$_REQUEST["Lat"] : $Lat='';	 
 isset($_REQUEST["Lng"]) ? $Lng=$_REQUEST["Lng"] : $Lng='';	 
 isset($_REQUEST["HomeAddress"]) ? $HomeAddress=$_REQUEST["HomeAddress"] : $HomeAddress='';	 

 ?>
 
<?php include getDocumentRoot() . '/load_header.php' ?>

<!-- 카카오맵에 필요한 3가지 API -->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$APIKEY?>&libraries=LIBRARY"></script>
<!-- services 라이브러리 불러오기 -->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$APIKEY?>&libraries=services"></script>
<!-- services와 clusterer, drawing 라이브러리 불러오기 -->
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$APIKEY?>&libraries=services,clusterer,drawing"></script>


	<title> 미래기업 주변주소 </title> 
    <style>
    .screen_out {display:block;overflow:hidden;position:absolute;left:-9999px;width:1px;height:1px;font-size:0;line-height:0;text-indent:-9999px}
    .wrap_content {overflow:hidden;height:330px}
    .wrap_map {width:50%;height:300px;float:left;position:relative}
    .wrap_roadview {width:50%;height:300px;float:left;position:relative}
    .wrap_button {position:absolute;left:15px;top:12px;z-index:2}
    .btn_comm {float:left;display:block;width:70px;height:27px;background:url(https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/sample_button_control.png) no-repeat}
    .btn_linkMap {background-position:0 0;}
    .btn_resetMap {background-position:-69px 0;}
    .btn_linkRoadview {background-position:0 0;}
    .btn_resetRoadview {background-position:-69px 0;}
</style>

</head>



<? include './myheader.php'; ?>   


    <div class="container mt-4 mb-1">
    <div class="card">
    <div class="card-body">
	
	
	
	    <div class="d-flex mb-1 mt-2 justify-content-center align-items-center">  
            <span class="fs-5">		     전직원 연락처 </span>
		</div>	
		
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-primary">
                <tr class="text-center">
                    <th>번호</th>
                    <th>직급</th>
                    <th>성명</th>
                    <th>연락처</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $employees = [
                    ["대표", "소현철", "010-3784-5438"],                    
                    ["공장장", "이경묵", "010-8925-7473"],                    
					["관리이사", "최장중", "010-8875-9622"],                    
                    ["전산실장", "김보곤", "010-5123-8210"],                    
                    ["부장", "조성원", "010-2476-2562"],
                    ["부장", "조경임", "010-2811-5342"],										
                    ["부장", "권영철", "010-5559-7179"],
                    ["차장", "안현섭", "010-9414-5123"],                    
                    ["과장", "김영무", "010-3488-7067"],					
                    ["과장", "이미래", "010-2637-3060"],					
                    ["과장", "라나", "010-5926-9992"],					
                    ["대리", "김수로", "010-2556-5702"],
                    ["사원", "샤집", "010-6665-0615"],
                    ["사원", "까심", "010-5943-7210"],
					["사원", "소민지", "010-2580-5438"],
					["사원", "이소정", "010-3341-5448"],
					["사원", "볼한", "010-5185-6056"],
					["사원", "딥", "010-2893-7845"],
					["실장", "안병길", "010-9846-9000"],
					["사원", "이도훈", "010-2031-5446"]
                ];

                foreach ($employees as $key => $employee) {
                    $number = $key + 1;
                    list($position, $name, $contact) = $employee;
                ?>
                    <tr class="text-center">
                        <td><?php echo $number; ?></td>
                        <td><?php echo $position; ?></td>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $contact; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>




<form id="board_form" name="board_form" method="post" enctype="multipart/form-data" action="roadview.php"  >
<input type="hidden" id="Lat" name="Lat" value="<?=$Lat?>">
<input type="hidden" id="Lng" name="Lng" value="<?=$Lng?>">
<input type="hidden" id="HomeAddress" name="HomeAddress" value="<?=$HomeAddress?>">
</form>
<div class="container">  
	<div class="card">  
	<div class="card-body">  
	
	<div class="d-flex fs-3 mb-3 mt-2 justify-content-center">    
	  <a href="#" onclick="javascript:moveto('1')"> 미래기업 주소 </a>	&nbsp;&nbsp;&nbsp;
	  <a href="#" onclick="javascript:moveto('4')"> 이경묵공장장 </a>	&nbsp;&nbsp;&nbsp;	  	  
	  <a href="#" onclick="javascript:moveto('3')"> 안현섭차장 </a>	&nbsp;&nbsp;&nbsp;	  
	</div>

	</div>  


<div class="wrap_content">
    <div class="wrap_map">
        <div id="map" style="width:100%;height:100%"></div> <!-- 지도를 표시할 div 입니다 -->
        <div class="wrap_button">
            <a href="javascript:;" class="btn_comm btn_linkMap" target="_blank" onclick="moveKakaoMap(this)"><span class="screen_out">지도 크게보기</span></a> <!-- 지도 크게보기 버튼입니다 -->
            <a href="javascript:;" class="btn_comm btn_resetMap" onclick="resetKakaoMap()"><span class="screen_out">지도 초기화</span></a> <!-- 지도 크게보기 버튼입니다 -->
        </div>
    </div>
    <div class="wrap_roadview">
        <div id="roadview" style="width:100%;height:100%"></div> <!-- 로드뷰를 표시할 div 입니다 -->
        <div class="wrap_button">
            <a href="javascript:;" class="btn_comm btn_linkRoadview" target="_blank" onclick="moveKakaoRoadview(this)"><span class="screen_out">로드뷰 크게보기</span></a> <!-- 로드뷰 크게보기 버튼입니다 -->
            <a href="javascript:;" class="btn_comm btn_resetRoadview" onclick="resetRoadview()"><span class="screen_out">로드뷰 크게보기</span></a> <!-- 로드뷰 리셋 버튼입니다 -->
        </div>
    </div>
</div>
</div>

<? include 'footer.php'; ?>

</div>

<script>

$(document).ready(function(){
	
var Lat = "<?php echo $Lat; ?>";
var Lng = "<?php echo $Lng; ?>";
var HomeAddress = "<?php echo $HomeAddress; ?>";

console.log('Lat : ');
console.log(Lat);

if(Lat=='' || Lat==null)
  {
	console.log('null 실행');  
    Lat = '37.676328924477936';
    Lng = '126.61606606909503';
    HomeAddress = '(주) 미래기업';	
  }	
	
 
 
 
var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
    mapCenter = new kakao.maps.LatLng(parseFloat(Lat), parseFloat(Lng) ), // 지도의 중심 좌표 (위도, 경도) 웹툴에서 https://xn--yq5bk9r.com/blog/map-coordinates 검색가능
    mapOption = {
        center: mapCenter, // 지도의 중심 좌표
        level: 4 // 지도의 확대 레벨
    };

// 지도를 표시할 div와  지도 옵션으로  지도를 생성합니다
var map = new kakao.maps.Map(mapContainer, mapOption);

// 지도에 올릴 마커를 생성합니다.
var mMarker = new kakao.maps.Marker({
    position: mapCenter, // 지도의 중심좌표에 올립니다.
    map: map // 생성하면서 지도에 올립니다.
});

// 지도에 올릴 장소명 인포윈도우 입니다.
var mLabel = new kakao.maps.InfoWindow({
    position: mapCenter, // 지도의 중심좌표에 올립니다.
    content: HomeAddress // 인포윈도우 내부에 들어갈 컨텐츠 입니다.
});
mLabel.open(map, mMarker); // 지도에 올리면서, 두번째 인자로 들어간 마커 위에 올라가도록 설정합니다.


var rvContainer = document.getElementById('roadview'); // 로드뷰를 표시할 div
var rv = new kakao.maps.Roadview(rvContainer); // 로드뷰 객체 생성
var rc = new kakao.maps.RoadviewClient(); // 좌표를 통한 로드뷰의 panoid를 추출하기 위한 로드뷰 help객체 생성
var rvResetValue = {} //로드뷰의 초기화 값을 저장할 변수
rc.getNearestPanoId(mapCenter, 50, function(panoId) {
    rv.setPanoId(panoId, mapCenter);//좌표에 근접한 panoId를 통해 로드뷰를 실행합니다.
    rvResetValue.panoId = panoId;
});

// 로드뷰 초기화 이벤트
kakao.maps.event.addListener(rv, 'init', function() {

    // 로드뷰에 올릴 마커를 생성합니다.
    var rMarker = new kakao.maps.Marker({
        position: mapCenter,
        map: rv //map 대신 rv(로드뷰 객체)로 설정하면 로드뷰에 올라갑니다.
    });

    // 로드뷰에 올릴 장소명 인포윈도우를 생성합니다.
    var rLabel = new kakao.maps.InfoWindow({
        position: mapCenter,
        content: HomeAddress
    });
    rLabel.open(rv, rMarker);

    // 로드뷰 마커가 중앙에 오도록 로드뷰의 viewpoint 조정 합니다.
    var projection = rv.getProjection(); // viewpoint(화면좌표)값을 추출할 수 있는 projection 객체를 가져옵니다.
    
    // 마커의 position과 altitude값을 통해 viewpoint값(화면좌표)를 추출합니다.
    var viewpoint = projection.viewpointFromCoords(rMarker.getPosition(), rMarker.getAltitude());
    rv.setViewpoint(viewpoint); //로드뷰에 뷰포인트를 설정합니다.

    //각 뷰포인트 값을 초기화를 위해 저장해 놓습니다.
    rvResetValue.pan = viewpoint.pan;
    rvResetValue.tilt = viewpoint.tilt;
    rvResetValue.zoom = viewpoint.zoom;
});

//지도 이동 이벤트 핸들러
function moveKakaoMap(self){
    
    var center = map.getCenter(), 
        lat = center.getLat(),
        lng = center.getLng();

    self.href = 'https://map.kakao.com/link/map/' + encodeURIComponent(HomeAddress) + ',' + lat + ',' + lng; //Kakao 지도로 보내는 링크
}

//지도 초기화 이벤트 핸들러
function resetKakaoMap(){
    map.setCenter(mapCenter); //지도를 초기화 했던 값으로 다시 셋팅합니다.
    map.setLevel(mapOption.level);
}

//로드뷰 이동 이벤트 핸들러
function moveKakaoRoadview(self){
    var panoId = rv.getPanoId(); //현 로드뷰의 panoId값을 가져옵니다.
    var viewpoint = rv.getViewpoint(); //현 로드뷰의 viewpoint(pan,tilt,zoom)값을 가져옵니다.
    self.href = 'https://map.kakao.com/?panoid='+panoId+'&pan='+viewpoint.pan+'&tilt='+viewpoint.tilt+'&zoom='+viewpoint.zoom; //Kakao 지도 로드뷰로 보내는 링크
}

//로드뷰 초기화 이벤트 핸들러
function resetRoadview(){
    //초기화를 위해 저장해둔 변수를 통해 로드뷰를 초기상태로 돌립니다.
    rv.setViewpoint({
        pan: rvResetValue.pan, tilt: rvResetValue.tilt, zoom: rvResetValue.zoom
    });
    rv.setPanoId(rvResetValue.panoId);
}
 
 
});	 //end of ready
		
		
	
function moveto(sel){ 	
	 switch (sel){
       case '1' :
			Lat = '37.676328924477936';
			Lng = '126.61606606909503';
			HomeAddress = '(주) 미래기업';		         
	        break;       
       case '3' :
			Lat = '37.6188090794083';
			Lng = '126.69455376299669';
			HomeAddress = '안현섭차장 집';		         
	        break;
       case '4' :
			Lat = '37.69537938970068';
			Lng = '126.60202603190524';
			HomeAddress = '이경묵 공장장집';		         
	        break;
       case '6' :
			Lat = '37.684714658453125';
			Lng = '126.60071352586884';
			HomeAddress = '김영무 주임집';		         
	        break;
			
	 }
     $("#Lat").val(Lat);
     $("#Lng").val(Lng);
     $("#HomeAddress").val(HomeAddress);
     document.getElementById('board_form').submit();    
 
 }		

</script>
</body>
</html>