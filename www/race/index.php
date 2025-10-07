<?php
require_once __DIR__ . '/../common/functions.php';
?>
 <?php
 session_start(); 
 
  if(!isset($_SESSION["level"]) || $_SESSION["level"]>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:" . $_SERVER["DOCUMENT_ROOT"] . "/login/login_form.php"); 
         exit;
   } 
 
include getDocumentRoot() . '/load_header.php' ;
 
$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];

$visitor_ip = $_SERVER['REMOTE_ADDR']; // 방문자의 IP 주소
$visit_time = date('Y-m-d H:i:s'); // 현재 시간
 
require_once($_SERVER["DOCUMENT_ROOT"] . "/lib/mydb.php");
$pdo = db_connect();

try {
    $sql = "INSERT INTO mirae8440.visitors (ip_address, visit_name, visit_time) VALUES (?, ?, ?)";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $visitor_ip, PDO::PARAM_STR);
    $stmh->bindValue(2, $user_name, PDO::PARAM_STR);
    $stmh->bindValue(3, $visit_time, PDO::PARAM_STR);
    $stmh->execute();

   //  echo "방문자 정보가 기록되었습니다.";
} catch (PDOException $Exception) {
    echo "오류: " . $Exception->getMessage();
}


?>

<meta name="viewport" content="width=device-width, initial-scale=0.6 maximum-scale=0.5, user-scalable=no">

<title>동물 경주 Game</title>

<style>
	.imgrace {
	  width: 1%; /* Adjust the width as needed */
	  position: absolute;
	  top: 10%; /* Adjust the top position as needed */
	  left: 20%; /* Center horizontally */
	  transform: translateX(-50%); /* Center horizontally */
	  cursor: pointer;
	}

	.goalline {
	  width: 1%; /* Adjust the width as needed */
	  height: 200%;
	  position: absolute;
	  right: 2%; /* Adjust the right position as needed */
	  top: 23%; /* Adjust the top position as needed */
	}
		
	.modalcustom {
	  display: none;
	  position: fixed;
	  z-index: 1;
	  left: 0;
	  top: 0;
	  width: 100%;
	  height: 100%;
	  background-color: rgba(0, 0, 0, 0.7);
	}

	.modalcustom-content {
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  height: 100%;
	}

	#countdown {
	  font-size: 30em;
	  color: white;
	  position: absolute;
	  top: 50%;
	  left: 50%;
	  transform: translate(-50%, -50%);
	}		
  </style>

</head>
<body>

<?php require_once(includePath('myheader.php')); ?> 

<div id="modalcount" class="modalcustom">
  <div class="modalcustom-content">
    <div id="countdown"></div>
  </div>
</div>
		
<form name="board_form" id="board_form"  method="post" >  	
	<input type="hidden" id="startsign" name="startsign"  > 	
	<input type="hidden" id="current" name="current"  > 	
	<input type="hidden" id="reachtime" name="reachtime"  > 	
	<input type="hidden" id="finishLine" name="finishLine"  > 	
	<input type="hidden" id="intervalTime" name="intervalTime"  > 	
	<input type="hidden" id="startPosition" name="startPosition[]"  > 	
	<input type="hidden" id="players" name="players[]"  > 	
	
<div class="container-fluid">
 <div class="card">
   <div class="card-body">
	<div class="row p-1">
			<div class="col-sm-3">
					<div class="card">
						<div class="card-body">
							<span id="waitlist"> </span>
						</div>		
					</div>		
			</div>		
		<div class="col-sm-5">
			<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-center mt-2">
							<a class="nav-link" href="<?$root_dir?>/index2.php?home=1" title="mirae Homepage"  ><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-house-check-fill" viewBox="0 0 16 16">
							  <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
							  <path d="m8 3.293 4.712 4.712A4.5 4.5 0 0 0 8.758 15H3.5A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
							  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.707l.547.547 1.17-1.951a.5.5 0 1 1 .858.514Z"/>
							</svg> </a>
						</div>
						<div class="d-flex justify-content-center  mt-2">
						<button  type="button"  id="refreshBtn" class="btn btn-outline-secondary">새로고침</button> &nbsp;&nbsp;&nbsp;
							<h2> 동물 경주 </h2> &nbsp;&nbsp;&nbsp;		
							<button  type="button"  id="startButton" class="btn btn-dark">경주 시작</button> &nbsp;&nbsp;&nbsp;
						</div>

				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-center mt-2">
							<span id="display_status"> </span>
						</div>
						

				</div>
			</div>
		</div>
</div>

</div>
</div>
	<div class="row p-3">	
		<div class="d-flex justify-content-center mb-1 mt-1">
			 <img class="goalline" src="../img/goalline.jpg" id="goalline">
		 </div>
		 <div class="player-container">
		 
		</div>				
	</div>

</div>

</form>

<script>
// Define an array to store starting positions for all players
const startingPositions = [];
var players = []; // Initialize the players array
var playerIndex = 0; // Initialize the players array
var refreshInterval = 300; // milliseconds (0.3초)

function addPlayer(name, playerIndex) {
    const playerContainer = document.querySelector('.player-container');
    const playerSpan = document.createElement('span');
    const playerNameSpan = document.createElement('span');
    const playerImage = document.createElement('img');
    
    const initialTop = 20; // Initial top position
    const spacing = 10; // Adjust the spacing as needed
    
    const top = initialTop + (playerIndex * spacing);
			
    playerSpan.className = 'imgrace'; // Common class for all players

	const images = ['rabbit_small', 'turtle_small', 'horse_small', 'lamb_small', 'wolf_small', 'ostrich_small', 'leopard_small', 'goat_small', 'fox_small', 'puppy_small', 'lion_small', 'deer_small', 'cat_small', 'pig_small', 'giraffe_small', 'gorilla_small', 'donkey_small', 'person_small' ];
	const classNames = ['badge bg-primary', 'badge bg-success', 'badge bg-secondary', 'badge bg-danger', 'badge bg-warning text-dark', 'badge bg-info text-dark', 'badge bg-light text-dark', 'badge bg-dark'];

	// Create an array of available image and class indices
	const availableIndices = [...Array(images.length).keys()];
	const spanindex = [...Array(classNames.length).keys()];

	// Generate a random index from the available indices
	const randomImageIndex = availableIndices[Math.floor(Math.random() * availableIndices.length)];
	const randomSpanIndex = availableIndices[Math.floor(Math.random() * spanindex.length)];

	// Get the corresponding image and class
	const imageSource = '../img/' + images[randomImageIndex] + '.gif' ;
	const classToAdd = classNames[randomSpanIndex];

	// Remove the used index from the available indices
	availableIndices.splice(availableIndices.indexOf(randomImageIndex), 1);

	// Apply the chosen class and image
	playerNameSpan.className = classToAdd;
	playerImage.src = imageSource;

	
	
	// switch (playerIndex)
	// {
		// case 0 :
			// playerNameSpan.className = 'badge bg-primary'; // Add classes for styling if needed
			// playerImage.src = '../img/rabbit.gif'; // Set the appropriate image source
	     // break;
		// case 1 :
			// playerNameSpan.className = 'badge bg-success'; // Add classes for styling if needed
			// playerImage.src = '../img/turtle.gif'; // Set the appropriate image source
		// break;
		// case 2 :
			// playerNameSpan.className = 'badge bg-secondary'; // Add classes for styling if needed
			// playerImage.src = '../img/horse_small.gif'; // Set the appropriate image source
	     // break;
		
	// }
    // console.log('playerIndex ', playerIndex);
    // console.log('name ', name);
    // console.log('playerNameSpan.className ', playerNameSpan.className);
	
    playerImage.style.width = '100px'; // Set the image width    
    playerImage.style.height = '55px'; // Set the image Height    
    playerNameSpan.textContent = name;
    playerSpan.style.top = `${top}%`; // Set the calculated top position
    playerSpan.style.left = '10%'; // Center horizontally
    playerSpan.style.transform = 'translateX(-50%)'; // Center horizontally
    
    const raceTrackWidth = window.innerWidth - 500; // Adjust the width as needed
    const startPositionPercentage = 5; // Adjust the percentage as needed
    const startPosition = (raceTrackWidth * startPositionPercentage) / 100;

    playerSpan.style.left = `${startPosition}px`; // Set the initial left position
    
    // Append the child elements to the playerSpan
    playerSpan.appendChild(playerNameSpan);
    playerSpan.appendChild(playerImage);
    
    playerContainer.appendChild(playerSpan);
	
	// sendRaceTimeToServer();
}

function fetchWaitlist() {
    $.ajax({
        url: 'read_visitors.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Check if response has the 'waitlist' property and it's an object
            if (response.hasOwnProperty('waitlist') && typeof response.waitlist === 'object') {
                // Convert object values to an array of names
                var namesArray = Object.values(response.waitlist);
                
                // Clear existing player elements and reset the players array
                $('.player-container').empty();                
                // Add player elements based on names
                for (let i = 0; i < namesArray.length; i++) {
                    const name = namesArray[i];
					
                    addPlayer(name, i);
					// Check if the player is not already in the players array
					if (!players.some(player => player.name === name)) {
						// Push player information to the players array
						players.push({ name: name, position: 0, raceTime: Date.now() });
					}
                }
				
				// Update the HTML with the waitlist data
				var waitlistSpan = document.getElementById('waitlist');
				waitlistSpan.textContent = '접속: ' + namesArray.join(', ');
				
            } else {
                console.error('Invalid JSON response:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + error);
        }
    });
}

var raceStartTime = 0;

const startButton = document.getElementById('startButton');

let raceInterval;

startButton.addEventListener('click', function () {
    displaymodal();
});

function displaymodal() 
{
	
 stopRefreshing();
 $("#startsign").val('0'); 
	
const modal = document.getElementById("modalcount");
const countdown = document.getElementById("countdown");
  modal.style.display = "block";
  countdown.innerText = 4;
  startCountdown(4);	  
  resetPositions() ;  //save 서버 포함됨

		  
		function startCountdown(counter) {
		  countdown.innerText = counter;
		  if (counter > 0) {
			setTimeout(() => {
			  startCountdown(counter - 1);
			}, 1000);
		  } else {
			modal.style.display = "none";			
			 $("#startsign").val('1');			 
		  }
		}	
}


function resetPositions() {    
    
	startRefreshing();   	
    zeroToServer();	
	
    const playerDivs = document.querySelectorAll('.imgrace'); // Select all elements with the 'imgrace' class
    
    const raceTrackWidth = window.innerWidth - 500; // Adjust the width as needed
    const startPositionPercentage = 5; // Adjust the percentage as needed
    const startPosition = (raceTrackWidth * startPositionPercentage) / 100;
    
    // Loop through each playerDiv and reset its position
    playerDivs.forEach(playerDiv => {
        playerDiv.style.left = `${startPosition}px`;
    });


    $("#current").val(Date.now()); // Reset current time
}
	
// 0.3초마다 load_data 함수 호출
let intervalID = setInterval(load_data, refreshInterval);
// setInterval(fetchWaitlist, 5000);
	
// 정지 버튼을 클릭할 때 호출되는 함수
function stopRefreshing() {
  clearInterval(intervalID); // setInterval을 정지
}

// 재개 버튼을 클릭할 때 호출되는 함수
function startRefreshing() {
  // intervalID = setInterval(load_data, refreshInterval); // setInterval을 재개
}	

$(document).ready(function(){		
	fetchWaitlist();		
	// Event delegation to handle clicks on .player-container
	$('.player-container').on('click', '.imgrace', function() {
		
		// Calculate values based on the screen width
		var screenWidth = window.innerWidth;

		// Define ratio for finishLine and intervalTime
		var finishLineRatio = 0.9; // Adjust the ratio as needed
		var intervalTimeRatio = 0.02; // Adjust the ratio as needed

		// Calculate finishLine and intervalTime based on screen width and ratio
		var finishLine = screenWidth * finishLineRatio;
		var intervalTime = screenWidth * intervalTimeRatio;		
		
		// Set the calculated values to their respective elements
		$("#finishLine").val(finishLine);
		$("#intervalTime").val(intervalTime);		
		
		var playerIndex = $(this).index(); // Get the index of the clicked player
		var intervalTime = Number($("#intervalTime").val());

		// Get the current position of the player
		var currentPosition = players[playerIndex].position;
		var prevPosition = currentPosition;

		// Calculate the new position and update it
		var newPosition = currentPosition + intervalTime;
		
		players[playerIndex].position = newPosition;		
		players[playerIndex].prevPosition = prevPosition;		
		players[playerIndex].raceTime = Date.now();		
		players[playerIndex].finishLine = finishLine;		
		players[playerIndex].intervalTime = intervalTime;	
        if( players[playerIndex].current === 0 || players[playerIndex].current === '')		
		       players[playerIndex].current = $("#current").val();
		   
		// Update the displayed position and race time
		$(this).css('left', newPosition + 'px');
				
		$("#startsign").val('1');

		// Send updated race time to the server
		 sendRaceTimeToServer();
		// load_data();
	});	
	
	$("#refreshBtn").click(function(){ 
		location.reload();
	});	
});
		
function formatTime(milliseconds) {
    const date = new Date(milliseconds/1000);
    const hours = date.getUTCHours().toString().padStart(2, '0');
    const minutes = date.getUTCMinutes().toString().padStart(2, '0');
    const seconds = date.getUTCSeconds().toString().padStart(2, '0');
    // return `${hours}시 ${minutes}분 ${seconds}초`;
    return `${seconds}초`;
}

function sendRaceTimeToServer() {
    // console.log('players', players);
    var xhr = new XMLHttpRequest();
    var formData = new FormData();    

    xhr.open('POST', 'save_race_data.php', true);

    // Create an array to store player information
    var playerDataArray = [];

    for (let i = 0; i < players.length; i++) {
        const player = players[i];
        const playerData = {
            user_name: player.name,
            position: player.position,
            prevPosition: player.prevPosition,
            raceTime: player.raceTime,
            finishLine: player.finishLine,
            intervalTime : player.intervalTime,
            current : player.current
        };
        playerDataArray.push(playerData);
    }

    // Append player data array to formData
    formData.append('players', JSON.stringify(playerDataArray));
    // formData.append('players', playerDataArray);
	
    formData.append('startsign', '1');    

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('정상전송');
        }
    };

    xhr.send(formData);
}
	
// 서버 점수 및 시간 초기화
function zeroToServer() {    
    var xhr = new XMLHttpRequest();
    var formData = new FormData();    
	
	
	$("#current").val(Date.now());


    xhr.open('POST', 'save_race_data.php', true);

    // Create an array to store player information
    var playerDataArray = [];

    for (let i = 0; i < players.length; i++) {
        const player = players[i];
        const playerData = {
            user_name: player.name,
            position: 50,
            prevPosition: 50,
            raceTime: 0,
            current : $("#current").val()
        };
        playerDataArray.push(playerData);
    }

    // Append player data array to formData
     formData.append('players', JSON.stringify(playerDataArray));

    formData.append('startsign', '0');    

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Zero 세팅 정상전송');
        }
    };

    xhr.send(formData);
}

// load_data 함수의 수정된 코드
function load_data() {
    $.ajax({
        url: 'race_data.json',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
			
			$("#display_status").text(JSON.stringify(data));
			console.log(data);		
           
        },
        error: function() {
            console.log('Failed to fetch race data.');
        }
    });
}

    </script>
</body>
</html>