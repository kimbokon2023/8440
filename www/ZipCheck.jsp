<%@ page contentType = "text/html; charset=euc-kr" %>
<%@ page import = "java.util.*" %>
<%@ page import = "fourleaf.logon.*" %>

<%
	request.setCharacterEncoding("euc-kr");
	
	String check = request.getParameter("check");
	String area3 = request.getParameter("area3");
	LogonDBBean manager = LogonDBBean.getInstance();
	Vector zipcodeList = manager.zipcodeRead(area3);
	
	int totalList = zipcodeList.size();
%>

<html>
<head><title>우편번호 검색</title></head>
<link href = "style.css" rel = "stylesheet" type = "text/css">
<script>
	function dongCheck()
	{
		if(document.zipForm.area3.value == "")
		{
			alert("동 이름을 입력하세요.");
			document.zipForm.area3.focus();
			return;
		}
		document.zipForm.submit();
	}

	function sendAddress(zipcode, area1, area2, area3, area4)
	{
		var address = area1 + " " + area2 + " " + area3 + " " + area4;
		
		opener.document.userinput.zipcode.value = zipcode;
		opener.document.userinput.address.value = address;
		self.close();
	}		
</script>
<body bgcolor = "#FFFFCC">
<center>
	<b>우편번호 찾기</b>
<table>
<form name = "zipForm" method = "post" action = "ZipCheck.jsp">
<tr>
	<td><br>동 이름 입력 : <input name = "area3" type = "text">
		<input type = "button" value = "검  색" onclick = "dongCheck();">
	</td>
</tr>
<input type = "hidden" name = "check" value = "n">
</form>
<%
	if(check.equals("n"))
	{
%>
<%
		if(zipcodeList.isEmpty())
		{
%>
<tr>
	<td align = "center"><br>검색된 결과가 없습니다.</td>
</tr>
<%
		}
		else
		{
%>
<tr>
	<td align = "center"><br>
		#검색 후, 아래 우편번호를 클릭하면 자동으로 입력됩니다.
	</td>
</tr>
<%
			for(int i = 0; i < totalList; i++)
			{
				ZipcodeBean zipBean = (ZipcodeBean)zipcodeList.elementAt(i);
				String tempZipcode = zipBean.getZipcode().trim();
				String temptArea1 = zipBean.getArea1().trim();
				String temptArea2 = zipBean.getArea2().trim();
				String temptArea3 = zipBean.getArea3().trim();
				String temptArea4 = zipBean.getArea4().trim();
%>
<tr>
	<td>
		<a href = "javascript:sendAddress('<%= tempZipcode %>','<%= temptArea1 %>',
			'<%= temptArea2 %>','<%= temptArea3 %>','<%= temptArea4 %>')">
			<%= tempZipcode %>&nbsp;<%= temptArea1 %>&nbsp;<%= temptArea2 %>
			&nbsp;<%= temptArea3 %>&nbsp;<%= temptArea4 %></a><br>

<%
			} // for
		}
%>
<%
	}
%>
	</td>
</tr>
<tr>
	<td align = "center"><br><a href = "javascript:this.close();">닫 기</a></td>
</tr>
</table>
</center>
</body>
</html>