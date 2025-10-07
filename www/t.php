

<!-- canvas  선언 ( 크기를 미리 정해야 한다 ) -->
<!DOCTYPE HTML>
<html>

<body>
<canvas id ="canvas" width=500 height=500 ></canvas>
 <input type="button" id="hw" value="Hello world" />

</body>

 </html>


<script>

window.onload = function(){
    var hw = document.getElementById('hw');
    hw.addEventListener('click', function(){
        //alert('Hello world');
		draw();
	var p = new Path2D('M10 10 h 80 v 80 h -80 Z');	
    })
}

function draw() {
  var canvas = document.getElementById('canvas');
  if (canvas.getContext) {
    var ctx = canvas.getContext('2d');

    var rectangle = new Path2D();
    rectangle.rect(10, 10, 50, 50);

    var circle = new Path2D();
    circle.moveTo(125, 35);
    circle.arc(100, 35, 25, 0, 2 * Math.PI);

    ctx.stroke(rectangle);
    ctx.fill(circle);
  }
}
 

</script>

 