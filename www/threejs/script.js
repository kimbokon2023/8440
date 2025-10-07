// 씬, 카메라, 렌더러 설정
var scene = new THREE.Scene();
var camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.z = 5;

var renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);

// 라인 그리기
var linePoints = [];
linePoints.push(new THREE.Vector3(-1, 0, 0));
linePoints.push(new THREE.Vector3(1, 0, 0));
var lineGeometry = new THREE.BufferGeometry().setFromPoints(linePoints);
var lineMaterial = new THREE.LineBasicMaterial({ color: 0x0000ff });
var line = new THREE.Line(lineGeometry, lineMaterial);
scene.add(line);

// 원 그리기
var circleGeometry = new THREE.CircleBufferGeometry(1, 32);
var circleMaterial = new THREE.MeshBasicMaterial({ color: 0x00ff00, wireframe: true });
var circle = new THREE.Mesh(circleGeometry, circleMaterial);
scene.add(circle);

// 렌더 루프
function animate() {
    requestAnimationFrame(animate);
    renderer.render(scene, camera);
}
animate();
