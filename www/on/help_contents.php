<?php
// 각 페이지별 도움말 내용을 반환하는 함수

function getHelpContent($page) {
    $contents = [
        'customer_list' => "
            <div class='help-section'>
                <h3><i class='fas fa-users'></i> 거래처 관리 화면</h3>
                <p>발주처 거래처 정보를 등록하고 관리하는 화면입니다.</p>
            </div>

            <div class='help-section'>
                <h3><i class='fas fa-plus-circle'></i> 거래처 등록</h3>
                <p><strong>신규 거래처 등록</strong> 버튼을 클릭하여 새로운 거래처를 등록할 수 있습니다.</p>
                <ul>
                    <li>회사명, 대표자, 연락처 정보 입력</li>
                    <li>담당자 정보 입력 (선택사항)</li>
                    <li>사업자번호, 계좌 정보 입력 (선택사항)</li>
                </ul>
            </div>

            <div class='help-section'>
                <h3><i class='fas fa-search'></i> 검색 및 필터</h3>
                <ul>
                    <li><strong>상태별 필터</strong>: 활성/비활성 거래처 필터링</li>
                    <li><strong>키워드 검색</strong>: 회사명, 담당자, 전화번호로 검색</li>
                </ul>
            </div>

            <div class='help-section'>
                <h3><i class='fas fa-tasks'></i> 거래처 목록 작업</h3>
                <ul>
                    <li><strong>보기</strong>: 거래처 상세 정보 및 발주 통계 확인</li>
                    <li><strong>수정</strong>: 거래처 정보 수정</li>
                    <li><strong>삭제</strong>: 거래처 삭제 (관련 발주가 있으면 삭제 불가)</li>
                </ul>
            </div>

            <div class='help-warning'>
                <strong><i class='fas fa-exclamation-triangle'></i> 주의사항:</strong>
                <p>• 해당 거래처로 등록된 발주가 있으면 삭제할 수 없습니다</p>
                <p>• 삭제 대신 비활성 상태로 변경하는 것을 권장합니다</p>
            </div>
        ",

        'product_list' => "
            <div class='help-section'>
                <h3><i class='fas fa-box-open'></i> 제품 관리 화면</h3>
                <p>발주 가능한 제품 정보를 등록하고 관리하는 화면입니다.</p>
            </div>

            <div class='help-section'>
                <h3><i class='fas fa-plus-circle'></i> 제품 등록</h3>
                <p><strong>신규 제품 등록</strong> 버튼을 클릭하여 새로운 제품을 등록할 수 있습니다.</p>
                <ul>
                    <li>제품코드 (선택사항, 고유 식별자)</li>
                    <li>제품명 (필수)</li>
                    <li>제품구분: 아크릴, LED바, 간판, 조명, 기타</li>
                    <li>규격: 제품의 크기나 사양</li>
                    <li>단위: EA, 개, m 등</li>
                    <li>기준단가: 발주 시 기본으로 사용될 가격</li>
                </ul>
            </div>

            <div class='help-section'>
                <h3><i class='fas fa-filter'></i> 검색 및 필터</h3>
                <ul>
                    <li><strong>제품구분 필터</strong>: 특정 제품 종류만 보기</li>
                    <li><strong>상태 필터</strong>: 활성/비활성 제품 필터링</li>
                    <li><strong>키워드 검색</strong>: 제품명이나 규격으로 검색</li>
                </ul>
            </div>

            <div class='help-tip'>
                <strong><i class='fas fa-lightbulb'></i> 팁:</strong>
                <p>• 자주 사용하는 제품을 미리 등록해두면 발주 등록 시 빠르게 선택할 수 있습니다</p>
                <p>• 기준단가를 설정하면 발주 등록 시 자동으로 입력됩니다</p>
            </div>

            <div class='help-warning'>
                <strong><i class='fas fa-exclamation-triangle'></i> 주의사항:</strong>
                <p>• 해당 제품으로 등록된 발주가 있으면 삭제할 수 없습니다</p>
                <p>• 비활성 상태의 제품은 새로운 발주 등록 시 선택할 수 없습니다</p>
            </div>
        ",

        'order_view' => "
            <div class='help-section'>
                <h3><i class='fas fa-file-invoice'></i> 발주 상세보기 화면</h3>
                <p>등록된 발주의 상세 정보를 확인하는 화면입니다.</p>
            </div>

            <div class='help-section'>
                <h3><i class='fas fa-info-circle'></i> 확인 가능한 정보</h3>
                <ul>
                    <li><strong>기본 정보</strong>: 발주번호, 발주일자, 납품일, 상태, 우선순위</li>
                    <li><strong>거래처 정보</strong>: 거래처명, 연락처</li>
                    <li><strong>제품 정보</strong>: 제품명, 제품구분, 규격</li>
                    <li><strong>금액 정보</strong>: 수량, 단가, 총액, 부가세 포함 여부</li>
                    <li><strong>배송 정보</strong>: 납품주소, 비고사항</li>
                </ul>
            </div>

            <div class='help-section'>
                <h3><i class='fas fa-tasks'></i> 가능한 작업</h3>
                <ul>
                    <li><strong>수정</strong>: 발주 정보 수정</li>
                    <li><strong>삭제</strong>: 발주 삭제 (확인 후 삭제됨)</li>
                    <li><strong>일정 관리</strong>: 달력에서 납품 일정 확인</li>
                    <li><strong>목록으로</strong>: 발주 목록 화면으로 이동</li>
                </ul>
            </div>

            <div class='help-tip'>
                <strong><i class='fas fa-lightbulb'></i> 팁:</strong>
                <p>• 우선순위가 높거나 긴급한 발주는 색상으로 구분되어 표시됩니다</p>
                <p>• 상태를 변경하려면 수정 버튼을 클릭하세요</p>
            </div>

            <div class='help-warning'>
                <strong><i class='fas fa-exclamation-triangle'></i> 주의사항:</strong>
                <p>• 삭제된 발주는 복구할 수 없습니다</p>
                <p>• 완료된 발주도 수정할 수 있지만, 통계에 영향을 줄 수 있습니다</p>
            </div>
        "
    ];

    return $contents[$page] ?? '';
}
?>
