<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<script>
function saveExcelFile() {
    var strHtml = "";
    strHtml += "<html>\n";
    
    strHtml += "<head>\n";
    strHtml += "<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">\n";
    strHtml += "<meta name=ProgId content=Excel.Sheet>\n";
    strHtml += "<meta name=Generator content=\"Microsoft Excel 2007\">\n";
    strHtml += "<style>\n";
    strHtml += "td, th {\n";
    strHtml += " border: 1px solid #000000;\n";
    strHtml += "}\n";
    strHtml += "</style>\n";
    strHtml += "</head>\n";
    
    strHtml += "<body>\n";
    strHtml += "<table>\n";
    strHtml += "<tr>\n";
    strHtml += "<th style=\"background-color: #EEEEEE;\"> 국어         </th>\n";
    strHtml += "<th style=\"background-color: #EEEEEE;\">영어</th>\n";
    strHtml += "<th style=\"background-color: #EEEEEE;\">수학</th>\n";
    strHtml += "</tr>\n";
    strHtml += "<tr>\n";
    strHtml += "<td>100점</td>\n";
    strHtml += "<td>90점</td>\n";
    strHtml += "<td>95점</td>\n";
    strHtml += "</tr>\n";
    strHtml += "</table>\n";
    strHtml += "</body>\n";
    
    strHtml += "</html>";
    
    var elemA = document.createElement("a");
    elemA.href = URL.createObjectURL(new Blob([strHtml], {type: "application/csv;charset=utf-8;"}));
    elemA.download = "fileName.xls";
    elemA.style.display = "none";
    
    document.body.appendChild(elemA);
    elemA.click();
    document.body.removeChild(elemA);
}
</script>
</head>
<body>
    <input type="button" value="실행" onclick="saveExcelFile();" />
</body>
</html>