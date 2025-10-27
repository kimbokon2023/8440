<!-- 도움말 모달 -->
<div id="helpModal" class="help-modal">
    <div class="help-modal-content">
        <div class="help-modal-header">
            <h2><i class="fas fa-question-circle"></i> 도움말</h2>
            <button class="help-close-btn" onclick="closeHelp()">&times;</button>
        </div>
        <div class="help-modal-body" id="helpContent">
            <!-- 도움말 내용이 여기에 동적으로 삽입됩니다 -->
        </div>
    </div>
</div>

<style>
.help-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
    border: none;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 12px var(--shadow-hover);
    transition: all 0.3s ease;
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.help-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px var(--shadow-hover);
}

.help-modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease;
}

[data-theme="dark"] .help-modal {
    background-color: rgba(0, 0, 0, 0.8);
}

.help-modal-content {
    background: var(--bg-secondary);
    margin: 5% auto;
    width: 90%;
    max-width: 800px;
    border-radius: 12px;
    box-shadow: 0 5px 20px var(--shadow);
    animation: slideDown 0.3s ease;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
}

.help-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    border-bottom: 2px solid var(--border-light);
    background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
    color: var(--text-white);
    border-radius: 12px 12px 0 0;
}

.help-modal-header h2 {
    margin: 0;
    font-size: 22px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.help-close-btn {
    background: none;
    border: none;
    color: var(--text-white);
    font-size: 32px;
    cursor: pointer;
    line-height: 1;
    transition: transform 0.2s ease;
}

.help-close-btn:hover {
    transform: scale(1.2);
}

.help-modal-body {
    padding: 30px;
    overflow-y: auto;
    flex: 1;
    color: var(--text-primary);
}

.help-section {
    margin-bottom: 25px;
}

.help-section h3 {
    color: #667eea;
    font-size: 18px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

[data-theme="dark"] .help-section h3 {
    color: #9ba3f5;
}

.help-section p {
    margin: 8px 0;
    line-height: 1.6;
    color: var(--text-secondary);
}

.help-section ul {
    margin: 10px 0;
    padding-left: 25px;
}

.help-section li {
    margin: 8px 0;
    line-height: 1.6;
    color: var(--text-secondary);
}

.help-tip {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 12px 15px;
    margin: 15px 0;
    border-radius: 4px;
}

[data-theme="dark"] .help-tip {
    background: #3d3316;
    border-left-color: #ffc107;
}

.help-tip strong {
    color: #856404;
}

[data-theme="dark"] .help-tip strong {
    color: #ffd966;
}

.help-tip p {
    color: #856404;
}

[data-theme="dark"] .help-tip p {
    color: #f5e6c0;
}

.help-warning {
    background: #f8d7da;
    border-left: 4px solid #dc3545;
    padding: 12px 15px;
    margin: 15px 0;
    border-radius: 4px;
}

[data-theme="dark"] .help-warning {
    background: #3a1f21;
    border-left-color: #dc3545;
}

.help-warning strong {
    color: #721c24;
}

[data-theme="dark"] .help-warning strong {
    color: #f5c6cb;
}

.help-warning p {
    color: #721c24;
}

[data-theme="dark"] .help-warning p {
    color: #f5c6cb;
}

.help-info {
    background: #d1ecf1;
    border-left: 4px solid #17a2b8;
    padding: 12px 15px;
    margin: 15px 0;
    border-radius: 4px;
}

[data-theme="dark"] .help-info {
    background: #1e3338;
    border-left-color: #17a2b8;
}

.help-info strong {
    color: #0c5460;
}

[data-theme="dark"] .help-info strong {
    color: #b8e6f0;
}

.help-info p {
    color: #0c5460;
}

[data-theme="dark"] .help-info p {
    color: #b8e6f0;
}

.help-step {
    background: var(--hover-bg);
    padding: 15px;
    margin: 10px 0;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.help-step strong {
    color: #667eea;
    display: block;
    margin-bottom: 8px;
}

[data-theme="dark"] .help-step strong {
    color: #9ba3f5;
}

.help-step p {
    color: var(--text-secondary);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@media (max-width: 768px) {
    .help-btn {
        width: 50px;
        height: 50px;
        font-size: 20px;
        bottom: 20px;
        right: 20px;
    }

    .help-modal-content {
        width: 95%;
        margin: 2% auto;
        max-height: 90vh;
    }

    .help-modal-header {
        padding: 15px 20px;
    }

    .help-modal-header h2 {
        font-size: 18px;
    }

    .help-modal-body {
        padding: 20px;
    }

    .help-section h3 {
        font-size: 16px;
    }
}
</style>

<script>
// 도움말 모달 열기
function openHelp() {
    document.getElementById('helpModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // 스크롤 방지
}

// 도움말 모달 닫기
function closeHelp() {
    document.getElementById('helpModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // 스크롤 복원
}

// 모달 배경 클릭시 닫기
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('helpModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeHelp();
            }
        });
    }
});

// ESC 키로 닫기
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeHelp();
    }
});
</script>
