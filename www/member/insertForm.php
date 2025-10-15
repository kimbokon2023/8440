<?php
/**
 * 회원가입 폼 페이지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card align-middle" style="width:40rem; border-radius:20px;">
            <div class="card-title" style="margin-top:30px;">
                <h3 class="card-title text-center" style="color:#113366;">회원가입</h3>
            </div>
            
            <div id="group" class="card-body align-middle">
                <form class="form-signin" name="member_form" method="post" action="insertPro.php">
                    <h4 class="form-signin-heading p-5">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text p-3" id="basic-addon3">* 아이디</span>
                            </div>
                            <input type="text" class="form-control" name="id" placeholder="영문자 가능" required aria-describedby="basic-addon3">
                            <a href="#"><img src="../img/check_id.gif" onclick="check_id()" alt="아이디 중복확인"></a>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">* 비밀번호</span>
                            </div>
                            <input class="form-control" type="password" name="pass" required aria-describedby="basic-addon3">
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">* 비밀번호 확인</span>
                            </div>
                            <input class="form-control" type="password" name="pass_confirm" required aria-describedby="basic-addon3">
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">* 이름</span>
                            </div>
                            <input class="form-control" type="text" name="name" required aria-describedby="basic-addon3">
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">* 닉네임</span>
                            </div>
                            <input class="form-control" type="text" name="nick" placeholder="글작성시 화면표시" required aria-describedby="basic-addon3">
                            <a href="#"><img src="../img/check_id.gif" onclick="check_nick()" alt="닉네임 중복확인"></a>
                        </div>
                    </h4>
                    
                    <div class="clear"></div>
                    
                    <h5 class="form-signin-heading">* 는 필수 입력항목입니다.^^</h5>
                    
                    <h4 class="card-title text-center" style="color:#113366;">
                        <div id="button">
                            <a href="#"><img src="../img/button_save.gif" onclick="check_input()" alt="저장"></a>&nbsp;&nbsp;
                            <a href="#"><img src="../img/button_reset.gif" onclick="reset_form()" alt="초기화"></a>
                        </div>
                    </h4>
                </form>
            </div>
        </div>
    </div>

<script type="text/javascript">
(function() {
    'use strict';
    
    /**
     * 아이디 중복 확인
     */
    window.check_id = function() {
        var form = document.member_form;
        if (!form || !form.id) return;
        
        var id = form.id.value;
        window.open(
            "check_id.php?id=" + encodeURIComponent(id),
            "IDcheck",
            "left=20, top=200, width=300, height=100, scrollbars=no, resizable=yes"
        );
    };
    
    /**
     * 닉네임 중복 확인
     */
    window.check_nick = function() {
        var form = document.member_form;
        if (!form || !form.nick) return;
        
        var nick = form.nick.value;
        window.open(
            "check_nick.php?nick=" + encodeURIComponent(nick),
            "NICKcheck",
            "left=20, top=200, width=300, height=100, scrollbars=no, resizable=yes"
        );
    };
    
    /**
     * 입력값 검증
     */
    window.check_input = function() {
        var form = document.member_form;
        if (!form) return;
        
        if (form.pass.value != form.pass_confirm.value) {
            alert("비밀번호가 일치하지 않습니다.\n다시 입력해주세요.");
            form.pass.focus();
            form.pass.select();
            return;
        }
        
        form.submit();
    };
    
    /**
     * 폼 초기화
     */
    window.reset_form = function() {
        var form = document.member_form;
        if (!form) return;
        
        form.id.value = "";
        form.pass.value = "";
        form.pass_confirm.value = "";
        form.name.value = "";
        form.nick.value = "";
        form.id.focus();
    };
    
})();
</script>
</body>
</html>
