const data = [
    ["rahul", "delhi", "accounts dept"],
    ["rajeev", "UP", "sales dept"]
];

let csvContent = "data:text/csv;charset=utf-8,";

data.forEach(function(rowArray) {
    let row = rowArray.join(",");
    csvContent += row + "\r\n";
});

// 위와 같은 코드인데 간결하게 작성함.
const data = [
    ["rahul", "delhi", "accounts dept"],
    ["rajeev", "UP", "sales dept"]
];
let csvContent = "data:text/csv;charset=utf-8," + data.map(e => e.join(",")).join("\n");  // 간결한 표현식

// 위의 코드를 다운로드 하고 인코딩을 위한 코드는 아래와 같습니다.

// var encodedUri = encodeURI(csvContent);
// window.open(encodedUri);

// 이 함수는(, /? : @ & = + $)를 제외한 특수 문자를 인코딩하고 인코딩 된 URI를 나타내는 문자열 값을 반환합니다.
// CSV 파일에 특정 이름을 지정하려면 숨겨진 DOM 노드를 만들고 다운로드 기능을 설정해야합니다. 이것은 아래에서 수행됩니다.

var encodedUri = encodeURI(csvContent);
var link = document.createElement("a");
link.setAttribute("href", encodedUri);
link.setAttribute("download", "my_data.csv");
document.body.appendChild(link); 
link.click();

// 데이터를 큰 따옴표 안에 삽입하려면 CSV 데이터 개체를 만드는 동안JSON.stringify()함수를 사용할 수 있습니다.
// 아래 코드를 참조하십시오.

// const data = [
    // ["rahul", "delhi", "accounts dept"],
    // ["rajeev", "UP", "sales dept"]
// ];
// var csv = data.map(function(d){
       // return JSON.stringify(d);
    // })
    // .join('\n') 
    // .replace(/(^\[)|(\]$)/mg, '');
// console.log(csv);

// 출력:

// "rahul" "delhi" "accounts"
// "rajev" "UP"  "sales dept"