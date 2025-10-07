import BLOCKS from "./blocks.js"

// DOM
const playground = document.querySelector(".playground > ul");
const gameText = document.querySelector(".game-text");
const scoreDisplay = document.querySelector(".score");
const restartButton = document.querySelector(".game-text > button");

// Setting
const GAME_ROWS = 20;
const GAME_COLS = 10;

// variables
let score = 0;
let duration = 200;
let downInterval;
let tempMovingItem;
let counter=0;


const movingItem = {
    type : "",
    direction : 3,
    top: 0,
    left: 0,
};

// 시작할때 실행되는 부분

let restartVal = $("#restartVal").val();
if(restartVal == '1')
           init();
	   



// functions
function init() {
    score=0;
    scoreDisplay.innerText = score;
    tempMovingItem = { ...movingItem };  // spread operator는 movingItem의 값만 tempMovingItem에 넣어준다.

    for (let i=0; i < GAME_ROWS; i++) {
    prepenNewLine()
    }
    generateNewBlock()
}

function prepenNewLine() {
    const li = document.createElement('li');
    const ul = document.createElement('ul');
    for (let j=0; j < GAME_COLS; j++) {
        const matrix = document.createElement('li');
        ul.prepend(matrix);
    }
    li.prepend(ul);
    playground.prepend(li);    
}

function renderBlocks(moveType=""){
   counter = 0;	
   const { type, direction, top, left } = tempMovingItem;    // type으로 이름을 짧게 하는 방식이다. 원래는 tempMovingItem.type 이런식으로 표현해야 함.

   const movingBlocks = document.querySelectorAll(".moving");  // 기존에 있던 모양을 지워줌, moving이란 인자를 줘서 이전모양 삭제
   movingBlocks.forEach(moving => {
       moving.classList.remove(type,"moving");
   })
   BLOCKS[type][direction].some(block => {      // forEach는 중단할 수 없기때문에 some을 사용함. 주의요함
       counter ++; // 한번만 저장하기 위해 변수 연산
       const x = block[0] + left;
       const y = block[1] + top;
       // 범위를 벗어나는 처리구문
       const target = playground.childNodes[y] ? playground.childNodes[y].childNodes[0].childNodes[x] : null; // 범위확인 3항연산자       
       const isAvailable = checkEmpty(target);
       if(isAvailable) {
           target.classList.add(type, "moving")
       } else {
          tempMovingItem = { ...movingItem };
          if(moveType==='retry') {          // game over 나오면
              clearInterval(downInterval);
              showGameoverText();
            }
          setTimeout(()=>{
            renderBlocks('retry');
            if(moveType==='top'){
                  seizeBlock();
              }
          },0)
          return true;    // 값이 없을때 강제로 중지해서 리소스를 효율적으로 사용함.
       }
   })
   movingItem.left = left;
   movingItem.top = top;
   movingItem.direction = direction;
}

function seizeBlock(){
    const movingBlocks = document.querySelectorAll(".moving");  // 기존에 있던 모양을 지워줌, moving이란 인자를 줘서 이전모양 삭제
    movingBlocks.forEach(moving => {
        moving.classList.remove("moving");
        moving.classList.add("seized");
    })

    checkMatch();
}

function checkMatch() {
    const childNodes = playground.childNodes;
    childNodes.forEach(child=> {
        let matched = true;
        child.children[0].childNodes.forEach(li=> {
            if(!li.classList.contains("seized")){
                matched = false;                
            }
        })
        if(matched) {
            child.remove();
            prepenNewLine();
            score++;
            scoreDisplay.innerText = score;
        }
    })
    generateNewBlock();
}

function generateNewBlock() {

    clearInterval(downInterval);
    downInterval = setInterval(()=> {
        moveBlock('top',1);
    }, duration)

    const blockArray = Object.entries(BLOCKS);
    const randomIndex = Math.floor(Math.random() * blockArray.length);
  //   console.log(blockArray);

    movingItem.type = blockArray[randomIndex][0];
    movingItem.top = 0;
    movingItem.left = 3;
    movingItem.direction = 0;
    tempMovingItem = { ...movingItem };
    renderBlocks();
}

function checkEmpty(target){
    if(!target || target.classList.contains("seized")){
        return false;
    }    
    return true;
}

function moveBlock(moveType, amount){
    tempMovingItem[moveType] += amount;
    renderBlocks(moveType)
}

function changeDirection() {
  const direction = tempMovingItem.direction;
  direction ===3 ? tempMovingItem.direction = 0 : tempMovingItem.direction ++ ;
  renderBlocks();
}

function dropBlock() {
    clearInterval(downInterval);
    downInterval = setInterval(()=> {
          moveBlock('top',1);
    },10);    
}
function delayBlock() {
  clearInterval(downInterval);
}
function reStartInterval() {
    downInterval = setInterval(()=> {
    }, duration)
}

// event handling
document.addEventListener("keydown", e=> {
    switch(e.keyCode) { 
        case 39:  // 우측키
            moveBlock("left", 1);
            break;
        case 37:  // 좌측키
            moveBlock("left", -1);
            break;
        case 40: //방향키 아래키   
            moveBlock("top", 1);
            break;
        case 38: //방향키 모양변경
            changeDirection();
            break;
        case 32: // spacebar 키 
            dropBlock();
            break;
		case 27: // ESC 키 
            delayBlock();
            break;
        default:
            break;    
    }
//  console.log(e)
})

restartButton.addEventListener("click", () => {
    playground.innerHTML = "";
    gameText.style.display = "none";
    init();
})


function showGameoverText() {
var score = scoreDisplay.innerText;
var user_name = $("#user_name").val();			  
let txt="insert.php?name=" + user_name + "&score=" + score ;	
let restartVal = $("#restartVal").val();
if(restartVal == '1')    
            $("#vacancy").load(txt);	
$("#restartVal").val('0'); 				  		
gameText.style.display = "flex";
location.replace('http://8440.co.kr/tetris/index.php');
}