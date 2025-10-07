<!DOCTYPE html>
<html>
<head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   
   <script>
        $(document).ready(function () {
            // 변수를 선언합니다.
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');

            // 이벤트를 연결합니다.
            $(canvas).on({
                mousedown: function (event) {
                    // 위치를 얻어냅니다.
                    var position = $(this).offset();
                    var x = event.pageX - position.left;
                    var y = event.pageY - position.top;

                    // 선을 그립니다.
                    context.beginPath();
                    context.moveTo(x, y);
                },
                mouseup: function (event) {
                    // 위치를 얻어냅니다.
                    var position = $(this).offset();
                    var x = event.pageX - position.left;  // 이렇게 계산 안하고 event.offsetX로 하면된다.
                    var y = event.pageY - position.top;

                    // 선을 그립니다.
                    context.lineTo(x, y);
                    context.stroke();
                }
            });
        });
		
		
		function drawit(x, y) {

	        var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');
	                context.beginPath();
                    context.moveTo(10.25, 29.56);
			        context.lineTo(x, y);
                    context.stroke();		
			
		}
    </script>
</head>
<body>
<input id="drawline" type="button" value="그리기" onclick="javascript:drawit(100,385.25);" style="width:200px; height:50px; float:left;">
<br>
<br>
<br>
    <canvas id="canvas" width="3000" height="1000" style="border: 2px solid black">

    </canvas>
</body>
</html> 