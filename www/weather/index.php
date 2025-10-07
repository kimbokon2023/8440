<?php
// index.php
?><!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>오늘 시간당 강수량</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    #loading { color: #666; margin-bottom: 1em; }
    ul { list-style: none; padding-left: 0; }
    li { margin: 4px 0; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    th { background: #f4f4f4; }
  </style>
</head>
<body>
  <h1>오늘 시간당 강수량</h1>
  <p id="loading">로딩 중…</p>

  <!-- 시간별 리스트 (예: 서울) -->
  <ul id="hourlyList"></ul>

  <!-- 지역별 누적 테이블 -->
  <table id="sumTable">
    <thead>
      <tr>
        <th>지역</th>
        <th>0시–현재시 누적 강수량 (mm)</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <script>
  (async () => {
    // 1) “Decoding” 탭에서 복사한 ServiceKey (+ 와 = 포함)
    const serviceKey = 'EFI7Fchltxh8LNyMu+UE9GSklj4ZsJqpL1UAYP6S0ci9D7fqJA98RRdxJos8KxwwEr6L9GAuAEB6E9IA1v1j2Q==';

    // 2) 같은 폴더의 proxy.php 로 요청
    const proxy = 'proxy.php?';

    // 3) 주요 도시와 ASOS 지점번호
    const regions = {
      '서울': 108,
      '부산': 159,
      '대구': 143,
      '인천': 112,
      '광주': 156,
      '대전': 133,
      '울산': 152,
      '수원': 109
    };

    // 4) 오늘 날짜(YYYYMMDD)와 현재 시각(HH) 계산
    const now = new Date();
    const YYYY = now.getFullYear();
    const MM   = String(now.getMonth()+1).padStart(2,'0');
    const DD   = String(now.getDate()).padStart(2,'0');
    const HH   = String(now.getHours()).padStart(2,'0');
    const dateStr = `${YYYY}${MM}${DD}`;

    // 5) 공통 파라미터
    const params = new URLSearchParams({
      ServiceKey: serviceKey,
      pageNo: '1',
      numOfRows: '24',
      dataType: 'JSON',
      dataCd: 'ASOS',
      dateCd: 'HR',
      startDt: dateStr,
      endDt: dateStr,
      startHh: '00',
      endHh: HH
    });

    // 6) 모든 지역에 대해 병렬 호출
    const results = await Promise.all(
      Object.entries(regions).map(async ([name, stnId]) => {
        const url = proxy + params.toString() + '&stnIds=' + stnId;
        const res = await fetch(url);
        if (!res.ok) throw new Error(`${name} 데이터 로딩 실패 (HTTP ${res.status})`);
        const data = await res.json();               // proxy.php 가 JSON을 반환하도록 수정되어 있어야 합니다
        const items = data.response?.body?.items?.item || [];
        return { name, items };
      })
    );

    console.log(results);

    // 7) 시간별 리스트 (‘서울’ 예시)
    const ul = document.getElementById('hourlyList');
    const seoul = results.find(r => r.name === '서울');
    if (seoul) {
      seoul.items.forEach(o => {
        const li = document.createElement('li');
        li.textContent = `${o.tm.slice(-2)}시 강수량: ${o.rn} mm`;
        ul.appendChild(li);
      });
    }

    // 8) 지역별 누적 합계 테이블
    const tbody = document.querySelector('#sumTable tbody');
    results.forEach(({ name, items }) => {
      const sum = items.reduce((acc, o) => acc + parseFloat(o.rn || 0), 0);
      const tr  = document.createElement('tr');
      tr.innerHTML = `<td>${name}</td><td>${sum.toFixed(1)}</td>`;
      tbody.appendChild(tr);
    });

    // 9) 로딩 메시지 제거
    document.getElementById('loading').remove();
  })().catch(err => {
    console.error(err);
    document.getElementById('loading').textContent = '데이터 로딩 중 오류가 발생했습니다.';
  });
  </script>
</body>
</html>
