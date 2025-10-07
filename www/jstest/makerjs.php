<!DOCTYPE html>
<html>
<head>
    <title>MakerJS Example</title>
    <script src="https://maker.js.org/target/js/browser.maker.js" type="text/javascript"></script>
</head>
<body>
    <script>
        function demo(font, text, font_size, combine, center_character_origin) {
            this.models = {
                example: new makerjs.models.Text(font, text, font_size, combine, center_character_origin)
            };
        }

        demo.metaParameters = [
            { title: "font", type: "font", value: "*" },
            { title: "text", type: "text", value: "Hello" },
            { title: "font size", type: "range", min: 10, max: 200, value: 72 },
            { title: "combine", type: "bool", value: false },
            { title: "center character origin", type: "bool", value: false }
        ];

        // 여기서 demo 함수를 사용해 보세요.
		demo();
    </script>
</body>
</html>
