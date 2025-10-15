<?php
/**
 * 변경사항 저장 완료 메시지
 * 로컬 및 서버 환경 모두 지원
 */

session_start();

// 세션 변수 안전하게 가져오기
$user_name = isset($_SESSION["name"]) ? $_SESSION["name"] : '사용자';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>저장 완료</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Malgun Gothic', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .message-container {
            background: white;
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        h2 {
            color: #333;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: block;
            stroke-width: 3;
            stroke: #4bb543;
            stroke-miterlimit: 10;
            margin: 0 auto 20px;
            animation: scaleIn 0.3s ease-in-out 0.1s both;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        
        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 3;
            stroke-miterlimit: 10;
            stroke: #4bb543;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        
        .checkmark-check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.3s forwards;
        }
        
        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }
        
        .auto-close-notice {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
        </svg>
        
        <h2><?=htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8')?>님! 변경사항이 저장되었습니다.</h2>
        
        <p class="auto-close-notice">이 창은 1.5초 후 자동으로 닫힙니다.</p>
    </div>

    <script>
        'use strict';
        
        // 1.5초 후 창 닫기
        setTimeout(function() {
            window.close();
            
            // 창이 닫히지 않는 경우를 위한 폴백
            setTimeout(function() {
                if (!window.closed) {
                    alert('창을 닫으려면 확인을 클릭하세요.');
                    window.close();
                }
            }, 100);
        }, 1500);
    </script>
</body>
</html>