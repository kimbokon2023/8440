<?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
include  $_SERVER['DOCUMENT_ROOT'] . '/load_header.php'; 
include  $_SERVER['DOCUMENT_ROOT'] . '/myheader.php'; 

$tablename=$_REQUEST["tablename"];   //table 이름

?>   

<div class="container">
<div class="row d-flex justify-content-center  p-3">
 <div class="d-flex mt-3 mb-1 justify-content-center">   		
    <button type="button"   class="btn btn-dark  btn-sm" onclick="location.href='../list.php'" > 목록(List) </button>	&nbsp;	
    <button type="button"   class="btn btn-success btn-sm" onclick="location.href='./view2.php?tablename=<?=$tablename?>'" > 품질/환경 절차서 </button>		&nbsp;&nbsp;
    <button type="button"   class="btn btn-secondary btn-sm" onclick="location.href='./view3.php?tablename=<?=$tablename?>'" > 환경절차서 제,개정 이력 </button>		&nbsp;&nbsp;
</div>
</div>
</div>
<div class="container">
<div class="row d-flex justify-content-center  p-3">
<span type="button"    class="btn btn-primary btn-lg fs-4"  >  품질/환경경영매뉴얼  </span>		&nbsp;&nbsp;
</div>
<div class="row d-flex justify-content-center  p-3">

</HEAD>

<BODY>

<P CLASS=HStyle0 STYLE='margin-right:35.0pt;line-height:130%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD colspan="4" width="620" height="290" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:none;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:100%;'><SPAN STYLE='font-size:32.0pt;font-family:"맑은 고딕";font-weight:"bold";background-color:#fcfcfc;line-height:100%;'>품질/환경경영매뉴얼</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:100%;'><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";font-weight:"bold";background-color:#fcfcfc;line-height:100%;'>(Quality/</SPAN><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>Env</SPAN><SPAN STYLE='font-size:13.3pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>i</SPAN><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>ronmenta</SPAN><SPAN STYLE='font-size:13.3pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>l</SPAN><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";font-weight:"bold";background-color:#fcfcfc;line-height:100%;'> Management Manual)</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";font-weight:"bold";font-style:"italic";line-height:160%;'>ISO 9001-2015/ISO 14001-2015</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="3" width="174" height="119" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:none;border-bottom:none;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="94" height="40" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD width="178" height="40" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
	<TD rowspan="3" width="174" height="119" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:none;border-bottom:none;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="94" height="40" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD width="178" height="40" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="94" height="40" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>배 포 처</SPAN></P>
	</TD>
	<TD width="178" height="40" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질관리팀</SPAN></P>
	</TD>
</TR>
<TR>
	<TD colspan="4" width="620" height="577" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:none;border-bottom:none;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='margin-right:5.0pt;line-height:210%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-right:5.0pt;line-height:210%;'><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:210%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;■ 관리본(CONTROLLED COPY)</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-right:5.0pt;line-height:210%;'><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:210%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;□ 비관리본(UNCONTROLLED COPY)</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-right:5.0pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-right:5.0pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-right:5.0pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";font-style:"italic";text-decoration:"underline";line-height:160%;'>본 문서는 (주)미래기업의 대외비이므로 </SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";font-style:"italic";text-decoration:"underline";line-height:160%;'>주관부문 책임자의 공식적인 허가없이 제 3자에게 복사하여 주거나 </SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";font-style:"italic";text-decoration:"underline";line-height:160%;'>내용을 공개하는 것을 금지합니다.</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-right:5.0pt;text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:150%;'><SPAN STYLE='font-size:24.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:150%;'><SPAN STYLE='font-size:30.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>(주)미래기업</SPAN><SPAN STYLE='font-size:14.0pt;font-family:"굴림";line-height:150%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:150%;'><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>본사 및 공장: 경기도 김포시 양촌읍 흥신로 220-27&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-family:"굴림";font-weight:"bold";'>&nbsp; </SPAN><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp; </SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:140%;'><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:140%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TEL: 031-983-8440&nbsp; FAX: 031-982-8449&nbsp; </SPAN><SPAN STYLE='font-size:15.0pt;font-family:"굴림";line-height:140%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN></P>
	</TD>
</TR>
<TR>
	<TD colspan="4" width="620" height="36" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:none;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='margin-right:5.0pt;text-align:center;line-height:130%;'><SPAN STYLE='font-size:24.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>&nbsp;</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:16.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>A. 목&nbsp; 차</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>1/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="620" height="850" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='margin-top:5.7pt;text-align:center;line-height:300%;'><SPAN STYLE='font-size:20.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>- 목&nbsp;&nbsp; 차 -</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>A. 목 차</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>B. 품질/환경경영 매뉴얼 승인</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>1. 일반사항</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";line-height:300%;'>&nbsp;&nbsp;&nbsp;1.1 목적 및 개요</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";line-height:300%;'>&nbsp;&nbsp;&nbsp;1.2 참조 문서</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";line-height:300%;'>&nbsp;&nbsp;&nbsp;1.3 </SPAN><SPAN STYLE='font-size:10.6pt;font-family:"맑은 고딕";line-height:300%;'>회사소개 및 품질/환경경영시스템의 경계 및 적용 가능성</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";line-height:300%;'>&nbsp;&nbsp;&nbsp;1.4 품질/환경방침</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>2. 용어의 정의</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>3. 용어 정의</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>4. 품질/환경경영시스템과 그 프로세스</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>5. 조직, 책임 및 권한</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";line-height:300%;'>&nbsp;&nbsp;&nbsp;5.1 조직</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";line-height:300%;'>&nbsp;&nbsp;&nbsp;5.2 의사소통</SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>첨부A. 내부문서 목록 </SPAN></P>
	<P CLASS=HStyle0 STYLE='margin-left:135.8pt;line-height:300%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:300%;'>첨부B. 업무분장</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"굴림체";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"굴림체";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>B. 제‧개정 이력 및 승인</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD colspan="4" width="620" height="47" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.1pt;font-family:"맑은 고딕";letter-spacing:1.2pt;font-weight:"bold";line-height:160%;'>제정․개정․폐지 이력</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="32" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:double #000000 1.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>번 호</SPAN></P>
	</TD>
	<TD width="98" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:double #000000 1.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제,개정 일자</SPAN></P>
	</TD>
	<TD width="159" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:double #000000 1.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제,개정 사유</SPAN></P>
	</TD>
	<TD width="314" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:double #000000 1.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제,개정 요약</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:double #000000 1.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>0</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 1.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 1.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>제정</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:double #000000 1.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.7pt;line-height:130%;'><SPAN STYLE='font-size:9.4pt;font-family:"맑은 고딕";line-height:130%;'>ISO 9001/14001 : 2015 도입에 따른 매뉴얼 제정</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.7pt;line-height:130%;'><SPAN STYLE='font-size:10.5pt;font-family:"맑은 고딕";line-height:130%;'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="49" height="40" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="98" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="159" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:7.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="314" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-indent:8.5pt;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="4" width="49" height="129" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>결</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>재</SPAN></P>
	</TD>
	<TD width="98" height="32" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>구&nbsp;&nbsp; 분</SPAN></P>
	</TD>
	<TD width="158" height="32" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>작&nbsp;&nbsp; 성</SPAN></P>
	</TD>
	<TD width="158" height="32" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>검&nbsp;&nbsp; 토</SPAN></P>
	</TD>
	<TD width="158" height="32" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>승&nbsp;&nbsp; 인</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="98" height="28" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>부&nbsp;&nbsp; 서</SPAN></P>
	</TD>
	<TD width="158" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="158" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="158" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="98" height="40" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>성&nbsp;&nbsp; 명</SPAN></P>
	</TD>
	<TD width="158" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(인)</SPAN></P>
	</TD>
	<TD width="158" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(인)</SPAN></P>
	</TD>
	<TD width="158" height="40" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(인)</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="98" height="28" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>일&nbsp;&nbsp; 자</SPAN></P>
	</TD>
	<TD width="158" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
	<TD width="158" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
	<TD width="158" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
</TABLE>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>1. 일반사항</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>3/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>1. 일반사항(General)</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;1.1 목적 및 개요</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.1.1 이 품질/환경경영매뉴얼에는 (주)미래기업</SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'> (</SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>이하 ‘당사’라고도 한다.)의 </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;사명(mission)과 비전을 달성하기 위하여 품질/환경경영시스템의 방침과 전사적 </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;관리 구조를 기술한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.1.2 이를 바탕으로 각 업무에서 품질/환경과 관련된 주요 방침과 책임/권한을 규정함&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 으로서 제품/서비스에 대하여 요구되는 품질/환경 수준을 확보하고 유지하기 위하&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 여 제정하였다. </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.1.3 당사의 품질/환경경영시스템을 이해하고 활용함으로서 맡은 바 업무를 잘 수행할&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 수 있으므로 더욱중요하다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.1.4 당사는 본 매뉴얼에 기술된 품질/환경경영시스템을 바탕으로 다음 사항을 달성하&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 고자 한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) 고객과 기타 요구사항에 충족하는 제품/서비스를 일관되게 제공함</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2) 종합적인 고객만족 증진 목표를 효과적이고 효율적으로 달성함</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.1.5 품질/환경경영시스템 실행에서 주요한 사항은 다음과 같다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) 고객만족</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;가) (주)미래기업의 실질적인 품질/환경의 측정 수단은 고객 만족이다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;나) 고객 만족과 당사가 제공하는 제품/서비스에 대한 품질 및 환경경영은 현재뿐&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 아니라 미래에도 당사의 경쟁력을 위하여 중요한 요소이다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2) 지속적 개선</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;당사의 품질/환경경영시스템이 성공의 굳건한 기초를 제공하도록 보장하기 위해&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 서, 우리는 품질/환경경영시스템과 관련 프로세스를 지속적으로 개선하는 것이 매&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 우 중요하다. </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3) 프로세스 접근</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;가) 품질/환경경영매뉴얼에 기술된 품질/환경경영시스템은 국제표준인ISO 9000,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ISO 9001/14001 및 ISO 9004에 포함된 프로세스접근방법 및 품질경영원칙을&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 활용한다</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;나) 프로세스접근 방식은 우리의 능력을 지속적으로 개선하기 위하여 중요하다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;1.2 참조문서(Reference Documents).</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.2.1 이 품질/환경매뉴얼에서는 ISO 9001/14001과 관련하여 ISO 9000을 참조 한다. 다&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 음의 문서는 당사 품질/환경경영시스템의 기준 문서가 된다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) ISO 9000 : 2015, 품질경영시스템-기본사항 및 용어</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2) ISO 9001 : 2015, 품질경영시스템-요구사항</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3) ISO 14001 : 2015, 환경경영시스템-요구사항</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.2.2 고객요구사항 문서 : </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.2.3 각 참조문서는 최신판을 적용한다.</SPAN></P>

<P CLASS=HStyle0>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>1. 일반사항</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>4/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;1.3 회사소개 및 </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>품질/환경경영시스템의 경계 및 적용 가능성</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.3.1 회사명: </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'> (이하 ‘당사’라고도 함) </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.3.2 주소(소재지): </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;본사: 경기도 김포시 양촌읍 흥신로 220=27</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;공장:&nbsp; 상 동&nbsp; </SPAN><SPAN STYLE='font-size:14.0pt;font-family:"굴림";font-weight:"bold";line-height:150%;'> </SPAN><SPAN STYLE='font-size:14.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'> </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.3.3 주요 연혁: 생략</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;1.3.4 주요 제품, </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>경계 및 적용 가능성</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) 적용범위 : 엘리베이터 의장품</SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>의 생산</SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>에 대하여 적용한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2) 주요 제품/서비스 : 당사에서 생산하는 모든 제품에 대하여 적용한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3) 적용 장소 : 본사(사무실 및 공장) </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4) 적용범위에 포함되지 않는 부분 : 당사는 ISO 9001/14001 : 2015의 요구사항에 따&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 라 품질/환경경영시스템을 수행하고 있으나, 요구사항 중에서 적용이 제외되는 사&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 항은 다음과 같다. </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ISO 9001/14001 : 2015 요구사항 </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>품질경영시스템의 경계 및 적용 가능성</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;</SPAN>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="75" height="41" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>조항번호</SPAN></P>
	</TD>
	<TD width="131" height="41" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>적용 불포함 부분</SPAN></P>
	</TD>
	<TD width="252" height="41" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제외사유</SPAN></P>
	</TD>
	<TD width="131" height="41" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>비&nbsp; 고</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="75" height="64" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>ISO9001:2015 8.3</SPAN></P>
	</TD>
	<TD width="131" height="64" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>제품 및 서비스의 설계와 개발</SPAN></P>
	</TD>
	<TD width="252" height="64" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>사업영역에 포함되지 않음</SPAN></P>
	</TD>
	<TD width="131" height="64" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>1. 일반사항</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>5/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'>&nbsp;&nbsp;1.4 품질/환경방침&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>&nbsp;품질 방침</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:14.0pt;font-family:"휴먼엑스포";font-weight:"bold";color:#0066cc;line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";font-weight:"bold";color:#0066cc;line-height:150%;'>&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>1. 지속적인 품질개선 및 개발</SPAN></P>

<P CLASS=HStyle0 STYLE='margin-left:66.0pt;text-align:left;text-indent:-33.0pt;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'><SPAN style='HWP-TAB:1;'>&nbsp;</SPAN>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle0 STYLE='margin-left:66.0pt;text-align:left;text-indent:-33.0pt;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.&nbsp; 고객 만족&nbsp; 품질 경영 체제 구축</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";color:#0000ff;line-height:150%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";color:#0000ff;line-height:150%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:14.0pt;font-family:"휴먼엑스포";font-weight:"bold";color:#0066cc;line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>품질 목표</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:14.0pt;font-family:"휴먼엑스포";font-weight:"bold";color:#0066cc;line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:130%;'><SPAN STYLE='font-size:15.0pt;font-family:"굴림";font-weight:"bold";line-height:130%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. 고객 불량률 최소화</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:130%;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. A/S 및 클레임 ZERO 화&nbsp;&nbsp; </SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;당사의 모든 임직원은 품질방침을 달성하기 위하여 품질경영시스템과 관련된 요구사항을 준수하고 품질목표를 설정하고 설정된 목표를 달성하기 위하여 노력을 기울인다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;대표이사는 품질방침이 당사의 사업목적에 부합하는지를 주기적으로 검토하고 방침의 변경이 필요한 경우 방침을 개정하고 전 임직원에게 홍보한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>2022년&nbsp; 03월&nbsp; 02일</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:11.2pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>㈜&nbsp; 미&nbsp; 래&nbsp; 기&nbsp; 업</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>대표이사 소 현 철 (인)</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>1. 일반사항</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>6/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'>&nbsp;&nbsp;1.4 품질/환경방침</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;∎ 환경방침</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;당사는 환경기본 방침에 의거하여 자연환경과의 조화 및 지역사회와 공생을 중요시하며&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 환경경영활동을 추진하는 것을 가장 중요한 과제의 하나로 서 내걸고 아래 사항을 정한&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 다.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;환경 방침</SPAN><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp; </SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:180%;'><SPAN STYLE='font-size:12.0pt;font-family:"굴림체";font-weight:"bold";line-height:180%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:15.0pt;font-family:"굴림체";font-weight:"bold";line-height:180%;'>1</SPAN><SPAN STYLE='font-size:12.0pt;font-family:"굴림체";font-weight:"bold";line-height:180%;'>. </SPAN><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>환경친화 경영</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. 환경오염 최소화</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. 환경법규 및 규정준수</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;환경 목표</SPAN><SPAN STYLE='font-size:16.0pt;font-family:"맑은 고딕";line-height:160%;'> </SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:12.0pt;font-family:"굴림체";font-weight:"bold";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:12.0pt;font-family:"굴림체";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:15.0pt;font-family:"굴림체";font-weight:"bold";line-height:160%;'>1</SPAN><SPAN STYLE='font-size:12.0pt;font-family:"굴림체";font-weight:"bold";line-height:160%;'>. </SPAN><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>환경 친화적 제품생산</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. 환경영향 최소화</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:15.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. 지속적&nbsp; 환경 개선수행</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>아울러 환경방침을 사외로부터 요구시, 또는 기타 필요에 대응하여 공표한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>2022년&nbsp; 03월&nbsp; 02일</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:11.2pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>㈜&nbsp; 미&nbsp; 래&nbsp; 기&nbsp; 업</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>대표이사 소 현 철 (인)</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>&nbsp;(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>3. 용어의 정의</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>7/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>3. 용어 및 정의(Terms and Definitions).</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;3.1 당사의 품질/환경경영시스템은 국제적으로 인정된 ISO 9000 / 14001에 주어진 용어&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 및 정의와 동일한 용어를 사용한다.</SPAN></P>

<P CLASS=HStyle2 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>조직(organization)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;조직의 목표 달성에 대한 책임, 권한 및 관계가 있는 자체의 기능을 가진 사람 또는</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;사람의 집단</SPAN></P>

<P CLASS=HStyle2 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";letter-spacing:0.6pt;font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>조직상황(context of the organization)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;조직의 목표 달성과 개발에 대한 조직의 접근법에 영향을 줄 수 있는&nbsp; 내부 및 외부&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 이슈의 조합</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>이해관계자(interested party)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;의사결정 또는 활동에 영향을 줄 수 있거나, 영향을 받을 수 있거나 또는 그들 자신이&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 영향을 받는 다는 인식을 할 수 있는 사람 또는 조직</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>공급자(provider)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;제품 또는 서비스를 제공하는 조직</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>외부 공급자(external provider)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;조직의 일부분이 아닌 공급자</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>품질기획(quality planning)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;품질목표를 세우고, 품질목표를 달성하기 위하여 필요한 운용 프로세스 및 관련&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 자원을 규정하는 데 중점을 둔 품질경영의 일부</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";letter-spacing:0.3pt;font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>변경관리(change control)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;형상관리&gt; 형상정보에 대한 공식적인 승인 후, 출력을 관리하기 위한 활동</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";letter-spacing:0.3pt;font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>프로세스(process)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;의도된 결과를 만들어 내기 위해 입력을 사용하여 상호 관련되거나 상호 작용하는&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 활동의 집합</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";letter-spacing:0.3pt;font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>절차(procedure)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;활동 또는 프로세스를 수행하기 위하여 규정된 방식</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>방침(policy)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;조직&gt; 최고경영자에 의해 공식적으로 표명된 조직의 의도 및 방향</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>요구사항(requirement)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명시적인 니즈 또는 기대, 일반적으로 묵시적이거나 의무적인 요구 또는 기대</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>법적 요구사항(statutory requirement)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;법적 기관이 규정한 의무 요구사항</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>규제적 요구사항(regulatory requirement)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;법적 기관으로부터 위임 받은 기관이 규정한 의무 요구사항</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>부적합(nonconformity)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;요구사항의 불충족</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>수리(repair)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;부적합한 제품 또는 서비스에 대해 의도된 용도대로 쓰일 수 있도록 하는 조치</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:145%;'>폐기(scrap)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:145%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:145%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;부적합 제품 또는 서비스에 대해 원래의 의도된 용도로 쓰이지 않도록 취하는 조치</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>3. 용어의 정의</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>8/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>결함(defect)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;의도되거나 규정된 용도에 관련된 부적합</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>적합(conformity)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;요구사항의 충족</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>목표(objective)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;달성되어야 할 결과</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>제품(product)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:10.7pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;조직과 고객간에 어떠한 행위/거래/처리(transaction)도 없이 생산될 수 있는 조직의 출력</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>성과(performance)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;측정 가능한 결과</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>리스크(risk)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;불확실성의 영향</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>리스크와 기회(risks and opportunities) </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;잠재적 악영향(위협)과 잠재적 유익한 결과(기회) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>문서(document)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;정보 및 정보가 포함된 매체</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>문서화된 정보(documented information)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;조직에 의해 관리되고 유지되도록 요구되는 정보 및 정보가 포함되어 있는 매체</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>품질매뉴얼(quality manual)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;조직의 품질경영시스템에 대한 시방서</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>피드백(feedback)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;고객만족&gt; 제품, 서비스, 또는 불만-처리 프로세스에 대한 의견, 논평 및 관심의 표현</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>불만/불평(complaint)</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;고객 만족&gt; 제품 또는 서비스에 관련되거나, 대응 또는 해결이 명시적 또는 묵시적&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 으로 기대되는 불만-처리 프로세스 자체에 관련되어 조직에 제기된 불만족의 표현</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>모니터링(monitoring)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;시스템, 프로세스, 제품, 서비스 또는 활동의 상태를 확인결정</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>측정(measurement)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;값을 결정/확인결정하는 프로세스</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>시정조치(corrective action)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;부적합의 원인을 제거하고 재발을 방지하기 위한 조치</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>시정(correction)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;발견된 부적합을 제거하기 위한 행위</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>재등급/등급변경(regrade)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;최초 요구사항과 다른 요구사항에 적합하도록 부적합한 제품 또는 서비스의 등급을&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 변경하는 것</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:150%;'>특채(concession)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:150%;'>&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:10.2pt;font-family:"맑은 고딕";line-height:150%;'>규정된 요구사항에 적합하지 않은 제품 또는 서비스를 사용하거나 불출하는 것에 대한 허가</SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>3. 용어의 정의</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>9/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:150%;'><SPAN STYLE='font-size:10.2pt;font-family:"맑은 고딕";line-height:150%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>재작업(rework)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;부적합한 제품 또는 서비스에 대해 요구사항에 적합하도록 하는 조치</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>심사 프로그램(audit programme)</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;특정한 기간 동안 계획되고, 특정한 목적을 위하여 관리되는 하나 또는 그 이상의&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 심사의 조합</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>환경경영시스템(environmental management system) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;환경측면을 관리하고, 준수의무사항을 충족하며, 리스크 및 기회를 다루기 위한 경영시&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 스템의 일부 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>환경방침(environmental policy) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;최고경영자에 의해 공식적으로 제시된 환경성과와 관련된 조직의 의도 및 방향 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>환경(environment) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;조직이 운용되는 주변여건(공기, 물, 토양, 천연자원, 식물군(群), 동물군, 인간 및 이들&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 요소 간의 상호관계를 포함) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>환경측면(environmental aspect) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;환경과 상호작용하거나 상호작용할 수 있는 조직의 활동 또는 제품 또는 서비스 요소 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>환경여건(environmental condition) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;어떤 시점에서 결정된 환경의 상태 또는 특성 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>환경영향(environmental impact) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;조직의 환경측면에 의해 전체적 또는 부분적으로 환경에 좋은 영향을 미치거나 나쁜&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 영향을 미칠 수 있는 모든 환경 변화 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>환경목표(environmental objective) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;환경방침과 일관성이 있게 조직이 설정한 목표 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>오염예방(prevention of pollution) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;부정적 환경적 영향을 감소시키기 위하여 어떠한 형태의 오염물질 또는 폐기물의 발&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 생, 방출 또는 배출의 회피, 저감 또는 관리(분리 또는 조합하여)를 위한 프로세스, 관&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 행, 기술, 재료, 제품, 서비스 또는 에너지의 사용 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>준수의무사항(compliance obligations, 표준용어) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;법적 요구사항 및 그 밖의 요구사항(legal requiremetns and other requirements, 허용&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 용어) 조직이 준수해야 하는 법적 요구사항과 조직이 준수해야 하거나 준수하기로 선&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 택한 그 밖의 요구사항 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>전과정(life cycle) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;천연자원으로부터 원료의 획득 또는 채취에서 최종 폐기까지의 제품(또는 서비스) 시&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 스템의 연속적이고 상호 연결된 단계 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>환경성과(environmental performance) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;환경측면의 관리(management)와 관련된 성과 </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>&nbsp;&nbsp;▶ </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:155%;'>환경여건(environmental condition) </SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:155%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:155%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;어떤 시점에서 결정된 환경의 상태 또는 특성 </SPAN></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:19.2pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>4. 품질/환경경영시스템과 그 프로세스</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>10/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>4. 품질/환경경영시스템과 그 프로세스</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;4.1 당사의 품질/환경경영시스템은 관련된 업무프로세스와 품질/환경방침을 수립, 문서화&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 하고 실행하는 전반적인 경영시스템으로서 다음 사항을 목적으로 한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.1.1 고객요구사항을 충족시키거나 능가함</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.1.2 ISO 9001/14001 품질/환경경영시스템 요구사항을 만족하는 제품 및 서비스를 제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 공하기 위함</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;4.2 프로세스 접근방법</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.2.1 우리는 단순하게 정의하고 관리하는 수단으로서 ISO 9000에서 제시하는 프로세스&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 접근방법을 채택한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.2.2 각 프로세스는 다음 사항을 포함한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) 바라는 결과가 달성됨을 보장하기 위한 프로세스 입력, 관리 및 출력</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) 시스템의 효과성이 달성됨을 보장하기 위한 상호 관련된 프로세스간의 연계</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.2.3 프로세스를 기반으로 한 당사의 경영시스템 구성은 다음과 같으며, 전반적인 구&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 조는 그림4.1과 같다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.2.4 각 프로세스간의 상호관계는 그림 4.2과 같다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.2.5 각 프로세스 책임자는 계획에 따라 바라는 결과를 얻기 위하여 모니터링 항목(프&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 로세스 성과지표)을 규정, 관리하고 지속적으로 개선해야 한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.2.6 프로세스/절차서는 서술형식으로 기술되며, 프로세스 또는 절차전개흐름도를 포&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 함하거나 참조한다. </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.2.7 </SPAN><SPAN STYLE='font-size:10.6pt;font-family:"맑은 고딕";line-height:160%;'>각 프로세스의 업무 수행을 위한 프로세스/절차서는 부속서A 문서목록을 참조한다.&nbsp; </SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.2.8 업무가 외주처리되는 경우 해당 업무는 외부공급자 관리 프로세스에 따라 관리&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 해야 한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;4.2.9 품질/환경경영시스템 문서 체계는 다음과 같다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG src="file:///C:\Users\light\Documents\0미래기업품질ISO\ISO\PICCDC2.png" width="624" height="270" vspace="0" hspace="0" border="0"></SPAN></P>

<P CLASS=HStyle0>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>4. 품질/환경경영시스템과 그 프로세스</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>11/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'>그림4.1 경영시스템 프로세스 맵</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><IMG src="file:///C:\Users\light\Documents\0미래기업품질ISO\ISO\PICCDE2.png" width="624" height="697" vspace="0" hspace="0" border="0"></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>4. 품질/환경경영시스템과 그 프로세스</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>12/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'>그림4.2 프로세스 상호작용 매트릭스( ※ 기울림체 : ISO 14001, 굵은체 : 공통)</SPAN></P>

<P CLASS=HStyle0>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="2" colspan="2" width="193" height="72" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle27><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";line-height:100%;'>구&nbsp;&nbsp;&nbsp; 분</SPAN></P>
	</TD>
	<TD colspan="8" width="429" height="25" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle28><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>핵심&nbsp; 프로세스</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="48" height="48" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle29 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>영업</SPAN></P>
	<P CLASS=HStyle29 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>관리</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle30 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>생 산</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle30 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>고객불만</SPAN></P>
	<P CLASS=HStyle30 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>관리</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle30 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>개발</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle30 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>운용</SPAN></P>
	<P CLASS=HStyle30 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>관리</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle30 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>환경영향평가</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle30 STYLE='background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>준수평가</SPAN></P>
	</TD>
	<TD width="93" height="48" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle31><SPAN STYLE='font-family:"맑은 고딕";'>Requirement</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="13" width="40" height="413" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:none;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>지</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>원</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>프</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>로</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>세</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>스</SPAN></P>
	</TD>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;제조설비 관리</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>7.1.3</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle37 STYLE='text-align:justify;background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>&nbsp;계측기&nbsp; 관리</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>7.1.5</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;설계 관리</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>○</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>7.5.3</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:none;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle37 STYLE='text-align:justify;background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'> </SPAN><SPAN STYLE='font-size:9.9pt;font-family:"맑은 고딕";line-height:100%;'>협력업체(외부공급자)</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>○</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.4.3</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle37 STYLE='text-align:justify;background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'> </SPAN><SPAN STYLE='font-size:9.9pt;font-family:"맑은 고딕";line-height:100%;'>구매(외부제공 제품)</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>○</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.4</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;식별 및 추적성</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.5.2</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;보존</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.5.4</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:none;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle37 STYLE='text-align:justify;background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;변경점 관리</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>○</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.5.6</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle37 STYLE='text-align:justify;background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;검사 및 시험</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.6</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle37 STYLE='text-align:justify;background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>&nbsp;부적합 관리</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>　</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.7</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle37 STYLE='text-align:justify;background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>&nbsp;비상사태 관리</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>8.2</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle37 STYLE='text-align:justify;background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>&nbsp;법규등록</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>6.1.3</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="32" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle37 STYLE='text-align:justify;background-color:#ffffff;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'> </SPAN><SPAN STYLE='font-size:9.9pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>환경모니터링 및 측정</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>●</SPAN></P>
	</TD>
	<TD width="48" height="32" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;</SPAN></P>
	</TD>
	<TD width="93" height="32" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>9.1.1</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="8" width="40" height="265" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>경</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>영</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>프</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>로</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>세</SPAN></P>
	<P CLASS=HStyle32><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>스</SPAN></P>
	</TD>
	<TD width="153" height="33" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33 STYLE='text-align:justify;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>&nbsp;목표관리/경영검토</SPAN></P>
	</TD>
	<TD rowspan="8" colspan="7" width="336" height="265" valign="top" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>ISO 9001 / 14001 전 PROCESS 공통적용</SPAN></P>
	</TD>
	<TD width="93" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>9.3 / 5.2.2 / 5.3 / 6.2</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="33" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33 STYLE='text-align:justify;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>&nbsp;부적합, 시정 조치</SPAN></P>
	</TD>
	<TD width="93" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>10.2</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="33" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33 STYLE='text-align:justify;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:9.6pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>문서화된 정보의 관리</SPAN></P>
	</TD>
	<TD width="93" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>7.5</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="33" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33 STYLE='text-align:justify;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>&nbsp;내부심사</SPAN></P>
	</TD>
	<TD width="93" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>9.2</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="33" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33 STYLE='text-align:justify;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>&nbsp;교육 훈련</SPAN></P>
	</TD>
	<TD width="93" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>7.1.6 / 7.2</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="33" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33 STYLE='text-align:justify;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>&nbsp;고객만족</SPAN></P>
	</TD>
	<TD width="93" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>9.1.2</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="33" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33 STYLE='text-align:justify;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>&nbsp;의사소통</SPAN></P>
	</TD>
	<TD width="93" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>7.4</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="153" height="33" valign="middle" bgcolor="#ffffff" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle33 STYLE='text-align:justify;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>&nbsp;리스크 관리</SPAN></P>
	</TD>
	<TD width="93" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>6.1</SPAN></P>
	</TD>
</TR>
<TR>
	<TD colspan="2" width="193" height="48" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle28><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>Requirement</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.2</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.5</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.2.1</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>8.3</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>8.1</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" style='border-left:none;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>6.1.2</SPAN></P>
	</TD>
	<TD width="48" height="48" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle34><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>9.1.2</SPAN></P>
	</TD>
	<TD width="93" height="48" valign="middle" style='border-left:none;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:0.0pt 0.0pt 0.0pt 0.0pt'>
	<P CLASS=HStyle38><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-style:"italic";line-height:100%;'>ISO 9001 /14001 : 2015</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>5. 조직, 책임 및 권한</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>13/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>5. 조직, 책임 및 권한</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;5.1 조직</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;5.1.1 </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>당사의 최고경영자 및 기타 핵심 인원의 상호관계는 다음 조직도[그림1]과 같다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:16.3pt;'><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";'>&nbsp;&nbsp; </SPAN><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";'>[그림1]</SPAN><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:16.3pt;'><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:16.3pt;'><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:16.3pt;'><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";'><IMG src=".\PICCE02.gif" width="0" height="43" vspace="0" hspace="0" border="0"></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:16.3pt;'><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";'><IMG src=".\PICCE03.gif" width="1" height="58" vspace="0" hspace="0" border="0"><IMG src=".\PICCE14.gif" width="1" height="58" vspace="0" hspace="0" border="0"><IMG src=".\PICCE15.gif" width="1" height="58" vspace="0" hspace="0" border="0"></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";line-height:100%;'><IMG src=".\PICCE16.gif" width="433" height="0" vspace="0" hspace="0" border="0"><IMG src=".\PICCE17.gif" width="1" height="58" vspace="0" hspace="0" border="0"></SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:16.3pt;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="86" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"굴림";font-weight:"bold";'>대표이사</SPAN></P>
	</TD>
</TR>
</TABLE><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:16.3pt;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="61" height="33" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"굴림";font-weight:"bold";line-height:160%;'>관리팀</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='text-align:left;line-height:16.3pt;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="59" height="189" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-weight:"bold";text-decoration:"underline";line-height:160%;'>경영지원</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.인사,노무</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.총무</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.교육훈련</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.경영검토</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.내부품질</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;환경감사</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.비상대응</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;체제운영</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.환경관리</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	</TD>
</TR>
</TABLE>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="76" height="34" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"굴림";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;N/A</SPAN></P>
	</TD>
</TR>
</TABLE><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle16><SPAN STYLE='font-family:"명조,한컴돋움";'><BR></SPAN></P>

<P CLASS=HStyle16>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="60" height="186" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:9.0pt;line-height:160%;'> </SPAN><SPAN STYLE='font-size:8.0pt;line-height:160%;'> </SPAN><SPAN STYLE='font-size:8.0pt;font-weight:"bold";text-decoration:"underline";line-height:160%;'>재무</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.경리</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.회계</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	</TD>
</TR>
</TABLE>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="51" height="189" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;font-weight:"bold";text-decoration:"underline";line-height:160%;'>&nbsp;구매</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.자재구매</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.협력업체</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;관리</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	</TD>
</TR>
</TABLE>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="67" height="189" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;font-weight:"bold";text-decoration:"underline";line-height:160%;'>&nbsp;영업</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.마케팅계획</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.계약검토</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.고객불만족&nbsp;&nbsp; 접수</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.고객만족도&nbsp; </SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;조사</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	</TD>
</TR>
</TABLE>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="61" height="30" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"굴림";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;영업팀</SPAN></P>
	</TD>
</TR>
</TABLE>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="62" height="185" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;font-weight:"bold";text-decoration:"underline";line-height:160%;'>생산관리</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.생산계획</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.설비관리</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.계측기관리</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	</TD>
</TR>
</TABLE>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="60" height="190" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;line-height:160%;'> </SPAN><SPAN STYLE='font-size:8.0pt;font-weight:"bold";text-decoration:"underline";line-height:160%;'>품질관리</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.최종검사</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.수입검사</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.부적합품</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;관리</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.고객불만족</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;처리</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.품질환경</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;프로그램</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0>&nbsp;</P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="57" height="34" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"굴림";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;생산팀</SPAN></P>
	</TD>
</TR>
</TABLE>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="60" height="184" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;font-weight:"bold";text-decoration:"underline";line-height:160%;'>개발</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.제품개발</SPAN></P>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;line-height:160%;'>.지재권관리</SPAN></P>
	<P CLASS=HStyle0>&nbsp;</P>
	<P CLASS=HStyle0>&nbsp;</P>
	<P CLASS=HStyle0>&nbsp;</P>
	<P CLASS=HStyle0>&nbsp;</P>
	<P CLASS=HStyle0>&nbsp;</P>
	<P CLASS=HStyle0>&nbsp;</P>
	</TD>
</TR>
</TABLE><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;5.1.2 경영 대리인(Management representative): 최고경영자는 상무로서 경영 대리인의&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 업무를 겸한다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;5.1.3 각 부서장은 제품, 프로세스 운영 및 품질/환경경영시스템과 관련하여 다음 사항&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 에 대한 책임과 권한이 있다.</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) 부적합의 예방</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2) 문제 파악 및 기록</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3) 해결책의 실행 및 검증</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4) 불만 사항이 시정될 때까지 후속 프로세스의 진행</SPAN></P>

<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;&nbsp;&nbsp;5.1.4 조직에 따른 업무분장은 첨부.B를 참조한다.</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>5. 조직, 책임 및 권한</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>14/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'>&nbsp;&nbsp;5.2 의사소통</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'>&nbsp;&nbsp;&nbsp;&nbsp;5.2.1 내부의사 소통</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="77" height="49" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>수단</SPAN></P>
	</TD>
	<TD width="191" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>내&nbsp; 용</SPAN></P>
	</TD>
	<TD width="63" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>주 관</SPAN></P>
	</TD>
	<TD width="113" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>방 법</SPAN></P>
	</TD>
	<TD width="93" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>시&nbsp; 기</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="77" height="53" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>보 고</SPAN></P>
	</TD>
	<TD width="191" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 구두보고</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 업무보고서</SPAN></P>
	</TD>
	<TD width="63" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>해당</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>부서</SPAN></P>
	</TD>
	<TD width="113" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>보고서</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>구두</SPAN></P>
	</TD>
	<TD width="93" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 발생시</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="77" height="60" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>회의</SPAN></P>
	</TD>
	<TD width="191" height="60" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 부서별 회의</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 주간회의</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'>- <SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>월간회의</SPAN></P>
	</TD>
	<TD width="63" height="60" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>해당</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>부서</SPAN></P>
	</TD>
	<TD width="113" height="60" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>회의</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>회의</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>회의</SPAN></P>
	</TD>
	<TD width="93" height="60" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 필요시</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 1회/주</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 1회/월</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="77" height="53" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>경영검토</SPAN></P>
	</TD>
	<TD width="191" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 사업계획 대비 실적</SPAN></P>
	</TD>
	<TD width="63" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>품질</SPAN></P>
	</TD>
	<TD width="113" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>회의</SPAN></P>
	</TD>
	<TD width="93" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 1회/년</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="77" height="60" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>요 청</SPAN></P>
	</TD>
	<TD width="191" height="60" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 정보/업무연락/기타</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'>- <SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>업무연락/업무협조</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'>- <SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>근급/정보/업무</SPAN></P>
	</TD>
	<TD width="63" height="60" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>해당</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>부서</SPAN></P>
	</TD>
	<TD width="113" height="60" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>e-mail</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>업무협조전</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>유선/구두</SPAN></P>
	</TD>
	<TD width="93" height="60" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 발생시</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="77" height="53" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>게시</SPAN></P>
	</TD>
	<TD width="191" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 각종행사 전달사항</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 교육홍보/프로젝트진행</SPAN></P>
	</TD>
	<TD width="63" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>총무</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>총무</SPAN></P>
	</TD>
	<TD width="113" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>공고판</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>현황판</SPAN></P>
	</TD>
	<TD width="93" height="53" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 발생시</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'>&nbsp;&nbsp;&nbsp;&nbsp;5.2.2 외부의사 소통</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="63" height="49" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>이 해</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>관계자</SPAN></P>
	</TD>
	<TD width="78" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>부&nbsp; 문</SPAN></P>
	</TD>
	<TD width="153" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>내&nbsp; 용</SPAN></P>
	</TD>
	<TD width="56" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>주 관</SPAN></P>
	</TD>
	<TD width="97" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>방 법</SPAN></P>
	</TD>
	<TD width="93" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:130%;'>시&nbsp; 기</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="63" height="99" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>고 객</SPAN></P>
	</TD>
	<TD width="78" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>영업</SPAN></P>
	</TD>
	<TD width="153" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 개발요청</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 납기관련</SPAN></P>
	</TD>
	<TD width="56" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>영업</SPAN></P>
	</TD>
	<TD width="97" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>회의</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>회의</SPAN></P>
	</TD>
	<TD width="93" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:double #000000 2.0pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 발생시</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="78" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>품질</SPAN></P>
	</TD>
	<TD width="153" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 품질문제</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 클레임</SPAN></P>
	</TD>
	<TD width="56" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>품질</SPAN></P>
	</TD>
	<TD width="97" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>공문</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>공문</SPAN></P>
	</TD>
	<TD width="93" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 발생시</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="63" height="99" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>외부</SPAN></P>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>공급자</SPAN></P>
	</TD>
	<TD width="78" height="73" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>구매</SPAN></P>
	</TD>
	<TD width="153" height="73" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 개발의뢰</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 구매</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 납기관리</SPAN></P>
	</TD>
	<TD width="56" height="73" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>구매</SPAN></P>
	</TD>
	<TD width="97" height="73" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>회의/공문</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>발주서</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>전화</SPAN></P>
	</TD>
	<TD width="93" height="73" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 발생시</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="78" height="26" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>기타구매</SPAN></P>
	</TD>
	<TD width="153" height="26" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 구매</SPAN></P>
	</TD>
	<TD width="56" height="26" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>구매</SPAN></P>
	</TD>
	<TD width="97" height="26" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>발주서</SPAN></P>
	</TD>
	<TD width="93" height="26" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 발생시</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="63" height="99" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>관 청</SPAN></P>
	</TD>
	<TD width="78" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>환경</SPAN></P>
	</TD>
	<TD width="153" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 환경측정결과</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 환경모니터링</SPAN></P>
	</TD>
	<TD width="56" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>총무</SPAN></P>
	</TD>
	<TD width="97" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>공문</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>공문</SPAN></P>
	</TD>
	<TD width="93" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 발생시</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="78" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>안전보건</SPAN></P>
	</TD>
	<TD width="153" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 소방관련 모니터링</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>- 건강검진</SPAN></P>
	</TD>
	<TD width="56" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>총무</SPAN></P>
	</TD>
	<TD width="97" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>공문</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>자동통보</SPAN></P>
	</TD>
	<TD width="93" height="49" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>&nbsp;</SPAN></P>
	<P CLASS=HStyle0 STYLE='line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>▶ 1회/년</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle16 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>첨부A. 내부문서 목록</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>15/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'><A NAME="_Toc334787426"></A><A NAME="MasterListofKeyQMSDocumentsAppendixA">첨부 A</A>. 본 매뉴얼에 참조된 주요 내부QMS 문서 목록</SPAN></P>

<P CLASS=HStyle26><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:100%;'>ISO 9001/14001 : 2015 규격 요구사항 대비표</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="237" height="26" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>요 구 사 항</SPAN></P>
	</TD>
	<TD width="90" height="26" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD width="135" height="26" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문 서 명</SPAN></P>
	</TD>
	<TD width="154" height="26" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>비&nbsp; 고</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-size:13.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>4 조직상황</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:double #000000 2.0pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:double #000000 2.0pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;4.1 조직과 조직상황의 이해</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP401</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:130%;'>조직및상황분석절차서</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;4.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>이해관계자의 니즈와 기대 이해</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP401</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:130%;'>조직및상황분석절차서</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;4.3 품질경영시스템 적용범위 결정</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;4.3 환경경영시스템 적용범위 결정</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'> </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>4.4 품질경영시스템과 그 프로세스</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'> </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>4.4 환경경영시스템</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-size:13.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>5 리더십</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;5.1 리더십과 의지표명</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>QEP-501</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:130%;'>리더십 절차서</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;5.1.1 일반사항</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;5.1.2 고객중시</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;5.2 방침</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;5.2 환경방침</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;5.2.1 품질방침의 수립</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;5.2.2 품질방침에 대한 의사소통</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;5.3 조직의 역할, 책임 및 권한</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEM001</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-size:13.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>6 기획</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;6.1 리스크와 기회를 다루는 조치</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-601</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:8.0pt;font-family:"맑은 고딕";line-height:130%;'>리스크및목표관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;&nbsp;6.1.1 일반사항 </SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-601</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:8.0pt;font-family:"맑은 고딕";line-height:130%;'>리스크및목표관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;&nbsp;6.1.2 환경측면 </SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>EP-1101</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;font-family:"맑은 고딕";line-height:160%;'>환경영향 평가 및 등록</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;&nbsp;6.1.3 준수의무사항 </SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>EP-1102</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>환경법규 관리</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;&nbsp;6.1.4 조치 계획 </SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-601</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:8.0pt;font-family:"맑은 고딕";line-height:130%;'>리스크및목표관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;6.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>품질목표와 품질목표 달성 기획</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-601</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:8.0pt;font-family:"맑은 고딕";line-height:130%;'>리스크및목표관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;6.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>환경목표와 이를 달성하기 위한 기획</SPAN><SPAN STYLE='font-family:"맑은 고딕";'> </SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-601</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:8.0pt;font-family:"맑은 고딕";line-height:130%;'>리스크및목표관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-style:"italic";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;6.2.1 환경목표 </SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>EP-1103</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:6.0pt;font-family:"맑은 고딕";line-height:130%;'>환경경영프로그램및환경계획수립</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-style:"italic";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;6.2.2 환경목표 달성을 위한 조치 기획</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>EP-1103</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:6.0pt;font-family:"맑은 고딕";line-height:130%;'>환경경영프로그램및환경계획수립</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-style:"italic";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="28" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;6.3 변경의 기획</SPAN></P>
	</TD>
	<TD width="90" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="28" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>첨부A. 내부문서 목록</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>16/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="237" height="25" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>요 구 사 항</SPAN></P>
	</TD>
	<TD width="90" height="25" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD width="135" height="25" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문 서 명</SPAN></P>
	</TD>
	<TD width="154" height="25" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>비&nbsp; 고</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-size:13.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>7 지원</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;7.1 자원</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;7.1.1 일반사항</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;7.1.2 인원</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP701</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:7.0pt;font-family:"맑은 고딕";line-height:130%;'>인적자원및조직의지식절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;7.1.3 기반구조</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-702</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:7.0pt;font-family:"맑은 고딕";line-height:130%;'>제조설비및치공구관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;7.1.4 프로세스 운용 환경</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;7.1.5 모니터링 자원과 측정 자원</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-703</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:6.0pt;font-family:"맑은 고딕";line-height:130%;'>모니터링및측정자원관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;&nbsp;7.1.6 조직의 지식</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP701</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:7.0pt;font-family:"맑은 고딕";line-height:130%;'>인적자원및조직의지식절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;7.2 역량/적격성</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP701</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:7.0pt;font-family:"맑은 고딕";line-height:130%;'>인적자원및조직의지식절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;7.2 역량</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP701</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:7.0pt;font-family:"맑은 고딕";line-height:130%;'>인적자원및조직의지식절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;7.3 인식</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-601</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:8.0pt;font-family:"맑은 고딕";line-height:130%;'>리스크및목표관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;7.4 의사소통</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"Arial,한컴돋움";'>&nbsp;&nbsp;7.4.1 </SPAN><SPAN STYLE='font-family:"돋움";'>일반사항 </SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-704</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>의사소통 절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"Arial,한컴돋움";'>&nbsp;&nbsp;7.4.2 </SPAN><SPAN STYLE='font-family:"돋움";'>내부 의사소통 </SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-704</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>의사소통 절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp; </SPAN><SPAN STYLE='font-family:"Arial,한컴돋움";'>7.4.3 </SPAN><SPAN STYLE='font-family:"돋움";'>외부 의사소통 </SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-704</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:130%;'><SPAN STYLE='font-family:"맑은 고딕";'>의사소통 절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;7.5 문서화된 정보</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;7.5.1 일반사항</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;7.5.2 작성(creating) 및 갱신</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-705</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:130%;'>문서및기록관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;7.5.3 문서화된 정보의 관리</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-705</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:130%;'>문서및기록관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-size:13.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>8 운용</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="25" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;8.1 운용 기획 및 관리</SPAN></P>
	</TD>
	<TD width="90" height="25" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:8.0pt;font-family:"맑은 고딕";line-height:160%;'>EP-1202~1207</SPAN></P>
	</TD>
	<TD width="135" height="25" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:6.0pt;font-family:"맑은 고딕";line-height:160%;'>소음및진동,수질,대기,폐기물,환경유해물질,에너지및자원관리</SPAN></P>
	</TD>
	<TD width="154" height="25" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:6.0pt;font-family:"맑은 고딕";line-height:160%;'>EP-1202,1203,1204,1205,1206,1207</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;8.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>제품 및 서비스 요구사항</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-801</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:130%;'>주문및계약관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.2.1 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>고객과의 의사소통</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-801</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:130%;'>주문및계약관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.2.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>제품 및 서비스에 대한 요구사항의 결정</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-801</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:130%;'>주문및계약관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.2.3 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>제품 및 서비스에 대한 요구사항의 검토</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-801</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:130%;'>주문및계약관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.2.4 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>제품 및 서비스에 대한 요구사항의 변경</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-801</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;line-height:130%;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:130%;'>주문및계약관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;8.2 비상사태 대비 및 대응 </SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>EP-1208</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>비상사태관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-style:"italic";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;8.3 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>제품 및 서비스의 설계와 개발</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>N/A</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.3.1 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>일반사항</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-802</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>설계및개발관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>N/A</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.3.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>설계와 개발 기획</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-802</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>설계및개발관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>N/A</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.3.3 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>설계와 개발 입력</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-802</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>설계및개발관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>N/A</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.3.4 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>설계와 개발관리</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-802</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>설계및개발관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>N/A</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.3.5 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>설계와 개발 출력</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-802</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>설계및개발관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>N/A</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="24" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.3.6 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>설계와 개발 변경</SPAN></P>
	</TD>
	<TD width="90" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-802</SPAN></P>
	</TD>
	<TD width="135" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>설계및개발관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="24" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>N/A</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>첨부A. 내부문서 목록</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>17/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>요 구 사 항</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문 서 명</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:double #000000 2.0pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>비&nbsp; 고</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.5.5 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>인도 후 활동</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-803</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:6.0pt;font-family:"맑은 고딕";line-height:160%;'>제품보존,인도,변경관리 절차서</SPAN><SPAN STYLE='font-size:9.0pt;font-family:"굴림";line-height:160%;'> </SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.5.6 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>변경관리</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-803</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:6.0pt;font-family:"맑은 고딕";line-height:160%;'>제품보존,인도,변경관리 절차서</SPAN><SPAN STYLE='font-size:9.0pt;font-family:"굴림";line-height:160%;'> </SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;8.6 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>제</SPAN><SPAN STYLE='font-family:"맑은 고딕";'>품 및 서비스의 불출</SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>/</SPAN><SPAN STYLE='font-family:"맑은 고딕";'>출시</SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>(release)</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-804</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>모니터링및측정절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;8.7 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>부적합 출력</SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>/</SPAN><SPAN STYLE='font-family:"맑은 고딕";'>산출물</SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>(output)</SPAN><SPAN STYLE='font-family:"맑은 고딕";'>의 관리</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-805</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:8.0pt;font-family:"맑은 고딕";line-height:160%;'>부적합제품의관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;8.4 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>외부에서 제공되는 프로세스</SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>, </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>제품 및 서비스의 관리</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-806</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>구매절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.4.1 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>일반사항</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-806</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>구매절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.4.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>관리의 유형과 정도</SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>(extent)</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-806</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>구매절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.4.3 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>외부공급자를 위한 정보</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;8.5 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>생산 및 서비스 제공</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-807</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>공정관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.5.1 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>생산 및 서비스 제공의 관리</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-807</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>공정관리절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.5.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>식별과 추적성</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-808</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>식별및추적성절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.5.3 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>고객 또는 외부공급자의 재산</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;8.5.4 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>보존</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-803</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:6.0pt;font-family:"맑은 고딕";line-height:160%;'>제품보존,인도,변경관리 절차서</SPAN><SPAN STYLE='font-size:9.0pt;font-family:"굴림";line-height:160%;'> </SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-size:13.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>9 성과 평가</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;9.1 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>모니터링</SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>, </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>측정</SPAN><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>, </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>분석 및 평가</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;9.1.1 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>일반사항</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;9.1.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>고객만족</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-901</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>고객만족도측정절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'> </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;9.1.2 준수평가 </SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>EP-1301</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>환경점검및측정관리</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;9.1.3 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>분석 및 평가</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QP-902</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>데이터분석절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;9.2 내부심사</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;9.2.1 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>일반사항 </SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-903</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>내부심사절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-style:"italic";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;9.2.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>내부심사 프로그램 </SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-903</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>내부심사절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";font-style:"italic";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;9.3 경영검토/경영평가</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-904</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>경영검토절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;9.3.1 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>일반사항</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;9.3.2 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>경영검토 입력사항</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-904</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>경영검토절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;&nbsp;9.3.3 </SPAN><SPAN STYLE='font-family:"맑은 고딕";'>경영검토 출력사항</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-904</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>경영검토절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-size:13.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:100%;'>10 개선</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:solid #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";color:#333399;'>※ 제목</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;10.1 일반사항</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;10.2 부적합 및 시정조치</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>QEP-1001</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";line-height:160%;'>시정조치및개선절차서</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:dotted #000000 0.4pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="237" height="27" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:left;line-height:100%;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>&nbsp;10.3 지속적 개선</SPAN></P>
	</TD>
	<TD width="90" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="135" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 0.4pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="154" height="27" valign="middle" style='border-left:solid #000000 0.4pt;border-right:solid #000000 1.1pt;border-top:dotted #000000 0.4pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"맑은 고딕";'>&nbsp;</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD rowspan="5" width="140" height="83" valign="middle" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='line-height:100%;'><SPAN STYLE='font-size:12.8pt;font-family:"굴림";font-weight:"bold";line-height:100%;'> </SPAN><SPAN STYLE='font-size:12.0pt;font-family:"휴먼고딕";font-weight:"bold";line-height:100%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD rowspan="3" width="268" height="52" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:18.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>품질/환경경영매뉴얼</SPAN></P>
	</TD>
	<TD width="83" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>문서번호</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="18" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>QEM001</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>제정일자</SPAN></P>
	</TD>
	<TD colspan="3" width="125" height="23" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>2022. 03. 02</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="83" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정일자</SPAN></P>
	</TD>
	<TD rowspan="2" colspan="3" width="125" height="20" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>-</SPAN></P>
	</TD>
</TR>
<TR>
	<TD rowspan="2" width="268" height="31" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:12.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>첨부B. 업무분장</SPAN></P>
	</TD>
</TR>
<TR>
	<TD width="83" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>개정번호</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-family:"맑은 고딕";font-weight:"bold";'>0</SPAN></P>
	</TD>
	<TD width="37" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:9.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>Page</SPAN></P>
	</TD>
	<TD width="52" height="22" valign="middle" bgcolor="#ffffff" style='border-left:solid #000000 1.1pt;border-right:solid #000000 1.1pt;border-top:solid #000000 1.1pt;border-bottom:solid #000000 1.1pt;padding:1.4pt 1.4pt 1.4pt 1.4pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:160%;'>18/18</SPAN></P>
	</TD>
</TR>
</TABLE></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'>첨부 B. 책임과 권한에 대한 업무분장</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:11.0pt;font-family:"굴림";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-size:11.0pt;font-family:"굴림";font-weight:"bold";line-height:160%;'>&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-family:"굴림";font-weight:"bold";'>1. 관리팀장</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1) 품질/환경매뉴얼 작업주관 및 관리</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2) 해당 품질/환경시스템 수립 및 유지관리</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(3) 경리, 회계, 인사, 총무 </SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(3) 환경법규 및 자료 관리</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(4) 경영검토 자료 취합 및 보고</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(5) 내부감사 계획, 실행주관 및 보고</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(6) 교육훈련 주관</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </SPAN><SPAN STYLE='font-family:"굴림";font-weight:"bold";'>(7) </SPAN><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>비상 대응체제 운영&nbsp; </SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(8) 구매 및 협력업체 관리</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. 영업팀장</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1) 해당 품질/환경시스템 수립 및 유지</SPAN></P>

<P CLASS=HStyle6 STYLE='text-indent:-49.6pt;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2) 계약검토 및 계약관련 문서유지관리업무&nbsp; </SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(3) 제품 영업 및 관리 </SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(4) 고객관련 업무 및 고객만족도 측정</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(5) 고객불만사항 접수 </SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-family:"굴림";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</SPAN></P>

<P CLASS=HStyle0 STYLE='text-align:left;'><SPAN STYLE='font-family:"굴림";font-weight:"bold";'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3</SPAN><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>. 생산팀장</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(1) 해당 품질/환경시스템 수립 및 유지</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(2) 생산계획 수립 및 관리</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(3) 설비 점검 및 유지관리</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(5) 생산에 소요되는 자재의 인수, 보관, 불출, 자재현황관리 등</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(6) 검사 및 시험관리</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(7) 검사, 측정 및 시험장비 관리 및 교정관리</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(8) 환경경영 프로그램 관리</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(9) 제품 식별 및 추적성 관리</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(10) 제품에 대한 취급 보관 포장 보존 및 인도관리</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(11) 해당 시 고객자산의 관리</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(12) 부적합품의 관리 </SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(13) 고객불만사항 처리 </SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(14) 수입 및 출하 검사</SPAN></P>

<P CLASS=HStyle39 STYLE='text-indent:-25.9pt;line-height:140%;'><SPAN STYLE='font-family:"굴림체";font-weight:"bold";'>&nbsp;&nbsp;(15) 시정 및 예방조치</SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";font-weight:"bold";line-height:180%;'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'><SPAN STYLE='font-family:"맑은 고딕";'><BR></SPAN></P>

<P CLASS=HStyle0 STYLE='line-height:180%;'>
<TABLE border="1" cellspacing="0" cellpadding="0" style='border-collapse:collapse;border:none;'>
<TR>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0><SPAN STYLE='font-family:"굴림";'>&nbsp;</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:center;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>(주)미래기업</SPAN></P>
	</TD>
	<TD width="205" height="25" valign="middle" style='border-left:none;border-right:none;border-top:solid #000000 0.4pt;border-bottom:none;padding:1.4pt 5.1pt 1.4pt 5.1pt'>
	<P CLASS=HStyle0 STYLE='text-align:right;'><SPAN STYLE='font-size:11.0pt;font-family:"맑은 고딕";line-height:160%;'>A4(210×297)</SPAN></P>
	</TD>
</TR>
</TABLE></P>
<BR><BR>

</div>
</div>


</BODY>

</HTML>
