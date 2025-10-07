<style>
    body {
        margin: 4em;
    }

    .container {
        width: 50%;
        height: 90%;
        position: relative;
        z-index: 100;
        perspective: 1300px;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
    }

    .page {
        position: absolute;
        transform-style: preserve-3d;
        transition-property: transform;
        width: 50%;
        height: 100%;
        left: 50%;
        transform-origin: left center;
    }

    #first,
    #first .back {
        transform: rotateY(180deg);
    }

    #first {
        z-index: 102;
    }

    #second {
        z-index: 103;
        transition: transform 0.8s ease-in-out;
    }

    #third .content {
        width: 50%;
    }

    #fourth {
        z-index: 101;
    }

    .page > div,
    .outer,
    .content,
    .helper-class-to-make-bug-visbile {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        backface-visibility: hidden;
    }

    .page > div {
        width: 100%;
        transform-style: preserve-3d;
    }

    .back {
        transform: rotateY(-180deg);
    }

    .outer {
        width: 100%;
        overflow: hidden;
        z-index: 999;
    }

    .content {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .content img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>

<div class="container">
    <div class="page" id="first">
        <div class="back">
            <div class="outer">
                <div class="content">
                    <img src="http://8440.co.kr/1/img/01.jpg">
                </div>
            </div>
        </div>
    </div>
    <div class="page" id="second">
        <div class="outer">
            <div class="content">
                <img src="http://8440.co.kr/1/img/02.jpg">
            </div>
        </div>
    </div>
    <div class="page" id="third">
        <div class="outer">
            <div class="content">
                <img src="http://8440.co.kr/1/img/02.jpg">
            </div>
        </div>
    </div>
    <div class="page" id="fourth">
        <div class="outer">
            <div class="content">
                <img src="http://8440.co.kr/1/img/02.jpg">
            </div>
        </div>
    </div>

    <div id="prev" onclick="prevImg()"></div>
    <div id="next" onclick="nextImg()"></div>
</div>

<script>
    let currentPage = 1; // 현재 페이지 번호

    function prevImg() {
        currentPage = (currentPage - 1 < 1) ? 17 : currentPage - 1;
        let imagePath = `http://8440.co.kr/1/img/${String(currentPage).padStart(2, '0')}.jpg`;
        document.getElementById('second').querySelector('.content img').src = imagePath;
        rotatePage();
    }

    function nextImg() {
        currentPage = (currentPage + 1 > 17) ? 1 : currentPage + 1;
        let imagePath = `http://8440.co.kr/1/img/${String(currentPage).padStart(2, '0')}.jpg`;
        document.getElementById('second').querySelector('.content img').src = imagePath;
        rotatePage();
    }

    function rotatePage() {
        let second = document.getElementById('second');
        second.style.transform = "rotateY(-180deg)";

        setTimeout(function () {
            second.style.transform = "rotateY(0deg)";
        }, 800);
    }
</script>
