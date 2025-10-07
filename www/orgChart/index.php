<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>회사 조직도</title>
</head>
<body>
    <div id="org_chart_div"></div>

    <script type="text/javascript">
        google.charts.load('current', {packages:["orgchart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            const data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');

            // 조직도 데이터 추가
            data.addRows([
                ['소현철 대표', '', 'CEO/President'],
                ['관리/영업지원', '소현철 대표', '지원파트'],
                ['설계', '소현철 대표', '지원파트'],
                ['기업전담부서', '소현철 대표', '지원파트'],
                ['이경묵 공장장', '소현철 대표', '제조/생산'],

                ['총괄 최장중 이사', '관리/영업지원', ''],
                ['영업관리 조경임 부장', '관리/영업지원', ''],
                ['총무/경리 소민지 사원', '관리/영업지원', ''],

                ['설계 이미래 과장', '설계', ''],
                ['설계 이소정 사원', '설계', ''],

                ['연구 김보곤 실장', '기업전담부서', ''],
                ['연구 안현섭 차장', '기업전담부서', ''],

                ['절곡 조성원 부장', '이경묵 공장장', ''],
                ['절곡 김영무 과장', '이경묵 공장장', ''],
                ['가공 라나 과장', '이경묵 공장장', ''],
                ['가공 까심 사원', '이경묵 공장장', ''],
                ['가공 샤질 사원', '이경묵 공장장', ''],
                ['가공 팀 사원', '이경묵 공장장', ''],
                ['조립 권영철 부장', '이경묵 공장장', ''],
                ['조립 안병길 실장', '이경묵 공장장', ''],
                ['조립 김수로 대리', '이경묵 공장장', ''],
                ['조립 불한 사원', '이경묵 공장장', ''],
            ]);

            const chart = new google.visualization.OrgChart(document.getElementById('org_chart_div'));
            chart.draw(data, {allowHtml: true});
        }
    </script>
</body>
</html>
