<!DOCTYPE html>
<html>
<head>
    <title>Right-Click Menu Example</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        #menu {
            position: absolute;
            display: none;
            background-color: #eee;
            border: 1px solid #999;
            padding: 5px;
            min-width: 100px;
        }
    </style>
</head>
<body>
    <div id="menu">
        <ul>
            <li><a href="#">메뉴 항목 1</a></li>
            <li><a href="#">메뉴 항목 2</a></li>
            <li><a href="#">메뉴 항목 3</a></li>
        </ul>
    </div>
    <button id="btn">오른쪽 버튼으로 메뉴 보기</button>
    <script>
        $(document).ready(function() {
            // 오른쪽 버튼 클릭 시, 메뉴 보이기
            $('#btn').on('contextmenu', function(e) {
                e.preventDefault();
                $('#menu').css({
                    display: 'block',
                    left: e.pageX,
                    top: e.pageY
                });
            });

            // 메뉴에서 다른 곳을 클릭하면 메뉴 숨기기
            $(document).on('click', function() {
                $('#menu').hide();
            });
        });
    </script>
</body>
</html>
