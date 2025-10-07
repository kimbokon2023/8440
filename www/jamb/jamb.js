$(function() {	
	// 쟘형태 선택버튼 변경시
    $("#sel1").change(function() {
	  switch (this.value) {
       case '멍텅구리' :
       case '멍텅구리-사이드(좌큼)' :
       case '멍텅구리-사이드(우큼)' :
	     var sheet = $('#canvas_outline');
	     var box = $('#canvas');
/*		 sheet.css('height','1200px');
		 sheet.css('width','2000px');
		 box.css('height','800px');  
		 box.css('width','1900px');
 */ 
       load_normaljamb_format();  
       setTimeout(maketable, 100);	   
	   setTimeout(redraw, 100);	  	   
       
	   break;
		
       case '와이드쟘' :		
       case '와이드쟘-사이드(좌큼)' :		
       case '와이드쟘-사이드(우큼)' :		
	   load_format(); 
       setTimeout(maketable, 100);	   	          
	   setTimeout(redraw, 100);	  	   	   
	   break;
		
	  }		  
	    redraw();
        load_jpg(this.value);
		
    });	
	// 작업소장 변경시 연신율 변경
    $("#sel2").change(function() {
		var rate=$("#sel2 option:selected").val();
		if(rate=='추영덕소장')
		{
			   $("#sel3").val('0.75');
			   redraw();
		}
		else
		{
			   $("#sel3").val('0.6');
			   redraw();
		}		
		
		});		
	// 연신율 선택버튼 변경시
    $("#sel3").change(function() {		
		redraw();
    });	
	
	// 실측치 draw 선택 변경시
    $("#measuredraw").change(function() {
      if($("#measuredraw").is(":checked")){		 
        $("#old_draw").val('1');   // 실측치 draw 선택시 '1'  	  
        load_jpg($("#sel1 option:selected").val());  //쟘형태 전달		
		if($("#makedraw").is(":checked")){		
		        load_makedraw($("#sel1 option:selected").val());  //쟘형태 전달		            		
		      }		
        }else{
        $("#old_draw").val('0');   // 실측치 draw 미 선택시 '0'  	  			
        unload_jpg($("#sel1 option:selected").val());  //쟘형태 전달		              
		if($("#makedraw").is(":checked")){		
		        load_makedraw($("#sel1 option:selected").val());  //쟘형태 전달		            		
		      }
        }
       });		
	   
	// 제작치수 draw 선택 변경시
    $("#makedraw").change(function() {
	  redraw();		
      });			
	  
	  
	  
// 자재 출고 전화번호 검색 버튼	
    $("#searchtel").on("click", function() {
     //   exe_searchTel();
    });		
		
});	

function redraw() {
	var test="";	
      if($("#makedraw").is(":checked")){		
		test = $("input[name=makedraw]:checked").val();
        $("#new_draw").val('1');   // 제작치수 draw 선택시 '1'  	 
		var sel = $("#sel1 option:selected").val();
		if($("#measuredraw").is(":checked")==true) 
		       load_jpg(sel);  //쟘형태 전달	
                load_makedraw(sel);  //쟘형태 전달		            			 
        }
		
		else{
		test = $("input[name=makedraw]:checked").val();
        $("#new_draw").val('0');   // 제작치수 draw 미 선택시 '0'  	  		
		unload_jpg($("#sel1 option:selected").val());  //쟘형태 전달		              
		if($("#measuredraw").is(":checked")==true) 		
             load_jpg($("#sel1 option:selected").val());  //쟘형태 전달		        // 화면을 지우고 실측치 draw를 선택해 준다.     
		 
        }
	
}

function select_jamb() {
	var sel=$("#sel1 option:selected").val();
	$('#col2').val($('#col1').val());
	setTimeout(redraw, 100);	  
}

function second_select_jamb() {
	var sel=$("#sel1 option:selected").val();
	$('#col1').val($('#col2').val());
	setTimeout(redraw, 100);	  
}


function unload_jpg() {
			
	        var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');			 			 

            $('#title').text('');		 
 
             ctx.fillStyle = "rgba(255, 255, 255,1 )";
             ctx.clearRect (0, 0, 3200, 1200);					

	        var canvas = document.getElementById('canvas_side');
            var ctx = canvas.getContext('2d');

             ctx.fillStyle = "rgba(255, 255, 255,1 )";
             ctx.clearRect (0, 0, 4000, 1600);			   
}

function load_makedraw(jpg) {
	// $('#display_jambtype').show();

	switch(jpg) {
	   case '와이드쟘' :	  
	     $('#jambtype_col1').html("<img src='../img/jamb/wide.jpg'>");
	     load_makedraw_widejamb();
	   break;
	   case '와이드쟘-사이드(좌큼)' :	  
	   case '와이드쟘-사이드(우큼)' :	  	   
	     $('#jambtype_col1').html("<img src='../img/jamb/sideopen.jpg'>");
	   break;	   
	   case '멍텅구리' :	  	   
	     $('#jambtype_col1').html("<img src='../img/jamb/widewithouthpi.jpg'>");
	     load_makedraw_normaljamb();		 
	   break;	   	   
	   case '멍텅구리-사이드(좌큼)' :	  	   
	   case '멍텅구리-사이드(우큼)' :	  	   
	     $('#jambtype_col1').html("<img src='../img/jamb/widewithouthpisideopen.jpg'>");
	   break;	   	   	   
	   case '쪽쟘' :	  	   
	     $('#jambtype_col1').html("<img src='../img/jamb/narrow.jpg'>");
	   break;	   	   	   	   
		
	default:
       break;	
		
	}
	    // alert(jpg);
        //  $("#material_list").load("./show_list.php?text1="+text1 +"&text2="+text2+"&text3="+text3+"&text4="+text4+"&ceilingbar="+ceilingbar);
 
}	

function load_jpg(jpg) {
	// $('#display_jambtype').show();

	switch(jpg) {
	   case '와이드쟘' :	  
	     $('#jambtype_col1').html("<img src='../img/jamb/wide.jpg'>");
	     load_widejamb();
	   break;
	   case '와이드쟘-사이드(좌큼)' :	  
	   case '와이드쟘-사이드(우큼)' :	  	   
	     $('#jambtype_col1').html("<img src='../img/jamb/sideopen.jpg'>");
	   break;	   
	   case '멍텅구리' :	  	   
	     $('#jambtype_col1').html("<img src='../img/jamb/widewithouthpi.jpg'>");
	     load_normaljamb();		 
	   break;	   	   
	   case '멍텅구리-사이드(좌큼)' :	  	   
	   case '멍텅구리-사이드(우큼)' :	  	   
	     $('#jambtype_col1').html("<img src='../img/jamb/widewithouthpisideopen.jpg'>");
	   break;	   	   	   
	   case '쪽쟘' :	  	   
	     $('#jambtype_col1').html("<img src='../img/jamb/narrow.jpg'>");
	   break;	   	   	   	   
		
	default:
       break;	
		
	}
	    // alert(jpg);
        //  $("#material_list").load("./show_list.php?text1="+text1 +"&text2="+text2+"&text3="+text3+"&text4="+text4+"&ceilingbar="+ceilingbar);
 
}	


function load_normaljamb_format() {

$('#j_row1').html("");	

var data = [[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],
];

jexcel(document.getElementById('j_row1'), {
    //data:data,
    csv:'https://8440.co.kr/jamb//normal.csv',
	csvHeaders:false,
    tableOverflow:true,   // 스크롤바 형성 여부
    rowResize:true,
    columnDrag:true,
    columns: [
        { title: '호기', type: 'text', width:'30' },
        { title: '층', type: 'text', width:'30' },
        { title: 'U', type: 'text', width:'30' },
		{ title: 'G', type: 'text', width:'30' },
        { title: 'MH1', type: 'text', width:'40' },
        { title: 'MH2', type: 'text', width:'40' },
        { title: 'JD1', type: 'text', width:'30' },
		{ title: 'JD2', type: 'text', width:'30' },
		{ title: 'OP1', type: 'text', width:'40' },
		{ title: 'OP2', type: 'text', width:'40' },
		{ title: 'R', type: 'text', width:'40' },
		{ title: 'K1', type: 'text', width:'30' },
		{ title: 'K2', type: 'text', width:'30' },
		{ title: '상판W', type: 'text', width:'40' },
		{ title: 'JB1', type: 'text', width:'30' },
		{ title: 'JB2', type: 'text', width:'30' },
		{ title: 'C1', type: 'text', width:'30' },
		{ title: 'C2', type: 'text', width:'30' },
		{ title: 'A1', type: 'text', width:'30' },
		{ title: 'A2', type: 'text', width:'30' },		
		{ title: 'B1', type: 'text', width:'30' },
		{ title: 'B2', type: 'text', width:'30' },				
		{ title: 'SIDE좌W', type: 'text', width:'55' },
		{ title: 'SIDE우W', type: 'text', width:'55' },
		{ title: 'LH1', type: 'text', width:'40' },
		{ title: 'LH2', type: 'text', width:'40' },
		{ title: 'RH1', type: 'text', width:'40' },
		{ title: 'RH2', type: 'text', width:'40' },

       // { type: 'calendar', width:'50' },
    ],

});
}

function load_format() {

$('#j_row1').html("");	

var data = [    [''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],
];

jexcel(document.getElementById('j_row1'), {
    // data:data,
    csv:'https://8440.co.kr/jamb/sample.csv',
	csvHeaders:false,
   // tableOverflow:true,   // 스크롤바 형성 여부
    rowResize:true,
    columnDrag:true,
    columns: [
        { title: '호기', type: 'text', width:'30' },
        { title: '층', type: 'text', width:'30' },
        { title: 'U', type: 'text', width:'30' },
		{ title: 'G', type: 'text', width:'30' },
        { title: 'MH1', type: 'text', width:'40' },
        { title: 'MH2', type: 'text', width:'40' },
        { title: 'JD1', type: 'text', width:'30' },
		{ title: 'JD2', type: 'text', width:'30' },
		{ title: 'OP1', type: 'text', width:'40' },
		{ title: 'OP2', type: 'text', width:'40' },
		{ title: 'R', type: 'text', width:'40' },
		{ title: 'K1', type: 'text', width:'30' },
		{ title: 'K2', type: 'text', width:'30' },
		{ title: '상판W', type: 'text', width:'40' },
		{ title: 'JB1', type: 'text', width:'30' },
		{ title: 'JB2', type: 'text', width:'30' },
		{ title: 'C1', type: 'text', width:'30' },
		{ title: 'C2', type: 'text', width:'30' },
		{ title: 'A1', type: 'text', width:'30' },
		{ title: 'A2', type: 'text', width:'30' },		
		{ title: 'B1', type: 'text', width:'30' },
		{ title: 'B2', type: 'text', width:'30' },				
		{ title: 'SIDE좌W', type: 'text', width:'55' },
		{ title: 'SIDE우W', type: 'text', width:'55' },
		{ title: 'LH1', type: 'text', width:'40' },
		{ title: 'LH2', type: 'text', width:'40' },
		{ title: 'RH1', type: 'text', width:'40' },
		{ title: 'RH2', type: 'text', width:'40' },

       // { type: 'calendar', width:'50' },
    ],

});
}

function load_setdata() {

$('#setdata').html("");	

setting= jexcel(document.getElementById('setdata'), {
    // data:data,
    csv:'https://8440.co.kr/jamb/setdata.csv',
	csvHeaders:false,
   // tableOverflow:true,   // 스크롤바 형성 여부
    rowResize:true,
    columnDrag:true,
    columns: [
        { title: '(-)OP', type: 'text', width:'60' },
        { title: '(-)R', type: 'text', width:'60' },
        { title: '(+)JD', type: 'text', width:'60' },
		{ title: '(+)JB', type: 'text', width:'60' },
        { title: '(+)C', type: 'text', width:'60' },
        { title: '(+)A', type: 'text', width:'60' },
        { title: '(+)K', type: 'text', width:'60' },


       // { type: 'calendar', width:'50' },
    ],

  });



}

function changeUri(tmpdata)
{
	  var ua = window.navigator.userAgent;
      var postData; 	 
	
	     if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(tmpdata);
            } else {
                postData = tmpdata;
            }

      return postData;
}

//제작치수로 Table 수치 변경하기
function maketable() {
	
	        // $('#spreadsheet').html("");  // 초기화
             
            var trlength =$('#j_row1 tbody tr').length;				         // 실측 테이블 값
            var tmplength =$('#spreadsheet tbody tr').length;				         // 제작수치 데이터 개수
			
			// 제작치수 테이블 테이블 생성
			// table1.deleteRow();
			for(i=1;i<tmplength;i++)
				    table1.deleteRow(0,1);
			
			table1.insertRow(trlength-1);
			
		    // alert(trlength);
			var pos=1;
			 // 설정 데이타 불러오기 setdata
		   var load_op = Number($('#setdata tr:eq(1)>td:eq(1)').html());
		   var load_r = Number($('#setdata tr:eq(1)>td:eq(2)').html());
		   var load_jd = Number($('#setdata tr:eq(1)>td:eq(3)').html());
		   var load_jb = Number($('#setdata tr:eq(1)>td:eq(4)').html());
		   var load_c = Number($('#setdata tr:eq(1)>td:eq(5)').html());
		   var load_a = Number($('#setdata tr:eq(1)>td:eq(6)').html());
		   var load_k = Number($('#setdata tr:eq(1)>td:eq(7)').html());

			
			for(pos=1;pos<=trlength;pos++) {

		    var title1 = $('#j_row1 tr:eq(' + pos + ')>td:eq(1)').html();
		    var title2 = $('#j_row1 tr:eq(' + pos + ')>td:eq(2)').html();
			
		      u = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(3)').html());
		      g = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(4)').html());
		      mh1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(5)').html());
		      mh2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(6)').html());
		      jd1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(7)').html());
		      jd2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(8)').html());
		      op1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(9)').html());
		      op2= Number($('#j_row1 tr:eq(' + pos + ')>td:eq(10)').html());
		      r = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(11)').html());
		      k1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(12)').html());
		      k2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(13)').html());
		      upper_wing = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(14)').html());
		      jb1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(15)').html());
		      jb2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(16)').html());
		      c1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(17)').html());		
		      c2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(18)').html());		
		      a1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(19)').html());		
		      a2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(20)').html());		
		      b1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(21)').html());		
		      b2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(22)').html());		
		      side_leftwing = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(23)').html());		
		      side_rightwing = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(24)').html());		
		      lh1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(25)').html());		
		      lh2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(26)').html());		
		      rh1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(27)').html());		
		      rh2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(28)').html());		
			  
		jd1=jd1 + load_jd;
          if(Number(jd2)==0)
			    jd2=jd1;			
               else
				jd2=jd2 + load_jd;
			
		jb1=jb1 + load_jb;			
          if(Number(jb2)==0)
			    jb2=jb1;									
			   else
				jb2= jb2 + load_jb;
			
		op1=op1 + load_op;			
          if(Number(op2)==0)
			    op2=op1;									
			   else
				op2= op2 + load_op;			
			
			
          if(Number(mh2)==0)
			    mh2=mh1;

         k1=k1+load_k;
         k2=k2+load_k;
		 
		 a1=a1+load_a;
         a2=a2+load_a;
		 
		 c1=c1+load_c;
         c2=c2+load_c;		 
			
	      table1.setRowData(pos-1,[title1,title2,u,g,mh1,mh2,jd1,jd2,op1,op2,r,k1,k2,upper_wing,jb1,jb2,c1,c2,a1,a2,b1,b2,side_leftwing,side_rightwing,lh1,lh2,rh1,rh2]);	 
		  
			}
}

  
// 실측치 와이드쟘 상판 그려주기  
function load_widejamb() {
	        var new_draw = $("#new_draw").val();   // 제작치수 draw 체크여부 확인
			
            var xscale=$("#Xscale option:selected").val();
			var scale;
			// alert(xscale);
			if(xscale=='none') scale=1;
			if(xscale=='2x1') scale=0.5;
			if(xscale=='3x1') scale=0.33;
			if(xscale=='5x1') scale=0.2;
			var upper_pop;
			
	        var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');
			
			var pos = $('#col1').val();// 몇번째 데이터인지 불러온다.
		   //  alert(pos);
			
			var startX = 200;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
			var startY = 80;	
          // 실측서를 먼저 화면에 그려준다.
		    var title1 = $('#j_row1 tr:eq(' + pos + ')>td:eq(1)').html();			
			
		    var title2 = $('#j_row1 tr:eq(' + pos + ')>td:eq(2)').html();
			
		    var u = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(3)').html());
		    var g = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(4)').html());			
		
		    var mh1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(5)').html());
		    var mh2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(6)').html());
		    var jd1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(7)').html());
		    var jd2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(8)').html());
		    var op1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(9)').html());
		    var op2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(10)').html());
		    var r = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(11)').html());
		    var k1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(12)').html());
		    var k2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(13)').html());
		    var upper_wing = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(14)').html());
		    var jb1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(15)').html());
		    var jb2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(16)').html());
		    var c1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(17)').html());
		    var c2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(18)').html());
		    var a1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(19)').html());
		    var a2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(20)').html());
		    var b1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(21)').html());
		    var b2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(22)').html());
		    var side_leftwing = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(23)').html());
		    var side_rightwing = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(24)').html());
		    var lh1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(25)').html());
		    var lh2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(26)').html());
		    var rh1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(27)').html());
		    var rh2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(28)').html());
			
			// 연신율
	        var bendrate=Number($("#sel3 option:selected").val());
			var bend_level=0; // 와이드쟘 쫄대타입이면 4회 밴딩			level로 벤딩 회수 산출

	        var h1=c1;   // 상판의 c1부위를 다른이름으로 지정, 막판에 귀돌이 없는 부분을 표현하기 위함.
            var h2=c2;			
			 
			 if(k2<1) 
				  k2=k1;
			 if(mh2<1) 
				  mh2=mh1;			  
			 if(jb2<1) 
				  jb2=jb1;			
			 if(jd2<1) 
				  jd2=jd1;		

		if(b1<=0) 
			b1=g-2;	
		if(b2<=0) 
			b2=g-2;
      if(u<1)
		{
			h1=0;h2=0;
		}		
	        var width = r + h1+h2;			 
			
			if(u>0) bend_level++;
			if(g>0) bend_level += 2;
			if(mh1>0) bend_level += 2;
			if(jd1>0) bend_level += 2;
			if(upper_wing>0) bend_level++;
			
	        var height = u + g + mh1 + jd1 + upper_wing	- (bendrate*bend_level) + k1 ;
			
			 $('#title').text(title1 + '호기 ' + title2 + '층' + ' (' + width + ' x ' + height + ' mm)    실측쟘 : 검정색라인');		 			 
					
             ctx.fillStyle = "rgba(255, 255, 255,1 )";
             ctx.clearRect (0, 0, 3200, 1200);				   
			 
	 var bend_level=1;  
	   // 와이드쟘 실측치 상판 돌출부위 산출
	   if(u>1 && g>1) {
		   if(bendrate==0.75)
		       upper_pop = 3.25;
						else
								upper_pop = 3.4;			  
	            }
				 else
					 upper_pop=0;
			  
	                ctx.beginPath();
					ctx.setLineDash([0, 0]);
			        ctx.strokeStyle = '#000';						  
                    ctx.moveTo(startX, startY);
  			        ctx.lineTo(startX + width, startY);                             // width 
                    ctx.stroke();		
				 if(u>0) {	  // u값이 0보다 클때
			        ctx.lineTo(startX + width, startY + u);                           // u
                    ctx.stroke();			
			        ctx.lineTo(startX + width, startY + u + upper_pop - bendrate*bend_level);  // 상부 돌출부위
                    ctx.stroke();		
			        ctx.lineTo(startX + width - h2, startY + u + upper_pop - bendrate*(bend_level+1));  // 상판 돌출
                    ctx.stroke();						
					bend_level += 2;
				 }
				 if(g>0) {
			        ctx.lineTo(startX + width - h2, startY + u + (g-upper_pop) - bendrate*bend_level);  // g값
                    ctx.stroke();			     					
					bend_level += 2;
				     }
			        ctx.lineTo(startX + width - h2, startY + u + g + mh1 - bendrate * bend_level);  // mh1
                    ctx.stroke();		
					bend_level ++;					
			    var cal = (r - op1)/2	; 		
			        ctx.lineTo(startX + width - h2 - cal, startY + u + g + mh1 +jd1 - bendrate * bend_level);  // jd까지 오른쪽 그리기
                    ctx.stroke();		
				if(k1>0) // k1값이 있으면 늘어난치수 있음
				{
					bend_level ++;										 
			        ctx.lineTo(startX + width - h2 - cal, startY + u + g + mh1 +jd1 + k1- bendrate * bend_level);  // k1까지 오른쪽 그리기
                    ctx.stroke();	
				}					
					bend_level ++;										 
			        ctx.lineTo(startX + width - h2 - cal, startY + u + g + mh1 +jd1 + k1 + upper_wing - bendrate * bend_level);  // 날개
                    ctx.stroke();							
			        ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 + k1 + upper_wing - bendrate * bend_level);  // 오픈그리기
                    ctx.stroke();		
					bend_level --;
			        ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 + k1 -  bendrate * bend_level);  //jd까지 왼쪽 상판 날개
                    ctx.stroke();		
				if(k1>0) // k2값이 있으면 늘어난치수 있음
				{
			        ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 - bendrate * bend_level);  // k1까지 오른쪽 그리기
                    ctx.stroke();	
				}	
				
				    bend_level -= 2;
			        ctx.lineTo(startX + h1 , startY + u + g + mh1 - bendrate * bend_level);  // 왼쪽jd영역
                    ctx.stroke();	

				    bend_level -= 2;
			        ctx.lineTo(startX + h1, startY + u + (g-upper_pop) - bendrate*bend_level);  // 왼쪽 mh
                    ctx.stroke();
             if(u>0) {
			        ctx.lineTo(startX + h1, startY + u + upper_pop - bendrate*1);  // 상판 돌출
                    ctx.stroke();						
					ctx.lineTo(startX , startY + u + upper_pop - bendrate*1);  //				
                    ctx.stroke();						
			        }
					ctx.lineTo(startX, startY);  //				
                    ctx.stroke();						
					// 각도구하기  절곡 회수 구하기
					bend_level=0;
				 if(u>0)
                      bend_level += 2;
				 if(g>0)
                      bend_level += 2;				  
				  if(mh1>0)
                      bend_level ++;				  
				  
					var Angle_left = getAngle(startX + width - h2, startY + u + g + mh1 - bendrate * bend_level,   startX + width - h2 - cal, startY + u + g + mh1 +jd1 - bendrate * 7);
					Angle_left=Angle_left.toFixed(1);
					var Angle_right = getAngle(startX + h1 , startY + u + g + mh1 - bendrate * bend_level,     startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 -  bendrate * 7);
					Angle_right=Angle_right.toFixed(1);
					
					$("#left_angle").val(Angle_left);
					$("#right_angle").val(Angle_right);

 // 상판 절곡선을 그린다
  
  if(new_draw!='1') {
  var offset=1;
  ctx.strokeStyle = '#09f';
  ctx.beginPath();
  ctx.setLineDash([70, 10]);
  ctx.lineDashOffset = -offset;
  bend_level=1;
     
  if(u>1)   {
  ctx.moveTo(startX, startY+u-bendrate*bend_level);  //   
  ctx.lineTo(startX + width, startY+u-bendrate*bend_level);       // 첫번째 절곡라인   
  ctx.stroke();  
  bend_level += 2;
      }
  if(g>1)   {	  
           ctx.moveTo(startX + h1, startY + u + g - bendrate*bend_level);           // 두번째 절곡라인     
		   ctx.lineTo(startX + width - h2 , startY + u + g - bendrate*bend_level);
		   ctx.stroke();	
           bend_level += 2;		   
		}
   if(mh1>0) {
   ctx.moveTo(startX + width - h2, startY + u + g + mh1 - bendrate * bend_level); // 세번째 절곡라인     
   ctx.lineTo(startX + h1, startY + u + g + mh1 - bendrate * bend_level); 
   ctx.stroke();  
   bend_level += 2;		      
      }
   ctx.moveTo(startX + width - h2 - cal, startY + u + g + mh1 +jd1 + k1- bendrate * bend_level); // 네번째 절곡라인     
   ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + mh1 + k1 + jd1 -  bendrate * bend_level); 
   ctx.stroke();        
   ctx.closePath();	

// 치수문자 화면출력
  ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
  ctx.font = '20px serif';
  var p_u=u-bendrate;
  if(u>1)
      var p_g=g-bendrate*2;
     else
      var p_g=g-bendrate*1;		 
  
  ctx.strokeText('( 전체폭 ) ' + width, (startX + width)/2, startY - 20);   // 전체폭 출력
  ctx.strokeText('( R ) ' + r, (startX + width)/2, startY + g + u + 30);   // r치수 출력
  if(u>1)
     ctx.strokeText('( U ) ' + p_u, startX+ width +50, startY + u - (u/2) );   // u치수 출력
  if(g>1)
     ctx.strokeText('( G ) ' + p_g, startX+ width +50, startY + u + g - (g/2) );   // g치수 출력
 if(h1>1) 
     ctx.strokeText('( H2 ) ' + h2, startX+ width -h2 , startY + u + g + 20 );   // h2치수 출력
 if(h2>1)
     ctx.strokeText('( H1 ) ' + h1, startX+ +h1 -80 , startY + u + g + 20 );   // h1치수 출력
  var p_upper_pop=upper_pop; 
 if(u>1 && g>1)   
     ctx.strokeText('(상판쫄대돌출) ' + p_upper_pop, startX+ width -230, startY + u + g + upper_pop*5 );   // 상판돌출 치수 출력
 if(g>1) p_mh=mh1-bendrate*2;
    else
	 	p_mh=mh1-bendrate*1;
	
     if(mh1>0) 
			ctx.strokeText('( MH ) ' + p_mh, startX+ width, startY + u + g - (g/2) + mh1/2 );   // mh 치수 출력 
  p_jd=jd1-bendrate*2;
  ctx.strokeText('( JD ) ' + p_jd, startX+ width, startY + u + g - (g/2) + +mh1 + jd1/2 );   // jd 치수 출력   
  p_upper_wing=upper_wing - bendrate*1;
  ctx.strokeText('( 상판날개 ) ' + p_upper_wing, startX+ width, startY + u + g + mh1 + jd1 + k1 + upper_wing/2 );   // 상판날개 치수 출력    
				if(k1>0) // k2값이 있으면 늘어난치수 있음
				{
			          ctx.strokeText('( K2 ) ' + k2, startX+ width, startY + u + g  +mh1 + jd1 + k1/2 );   // k1 치수 출력    
                    ctx.stroke();	
				}	  
  Angle_right = 90 - Angle_right;
  Angle_right=Angle_right.toFixed(1);
  ctx.strokeText('각도 ' + Angle_right + ' ˚', startX+ width-150, startY + u + g - (g/2) + +mh1 + jd1/2 );   // 각도 출력    
  ctx.strokeText('각도 ' + Angle_left + ' ˚', startX+ h1 +50, startY + u + g - (g/2) + +mh1 + jd1/2 );   // 좌측 각도 출력    
  ctx.strokeText('( OP ) ' + op1, (startX + width)/2, startY + height + 30);   // op 치수 출력  
  ctx.strokeText('( 전체높이 ) ' + height, startX-150 , startY + u + g - (g/2) + mh1/2 + 100 );   // 전체높이 치수 출력 
  ctx.closePath();	   
  
  }
 //상판 단면도 그리기 ***********************************************************************************************************************
   ctx.beginPath();
   ctx.moveTo(startX + width + 300, startY );   // 300거리 띄워서 시작점 잡기
  if(u>1) {
   ctx.lineTo(startX + width + 300 , startY + u ); // u값 그리기
   ctx.stroke();  
  }
  if(g>1) {
   ctx.lineTo(startX + width + 300 + g, startY + u); // g값 그리기
   ctx.stroke();    
  }
   ctx.lineTo(startX + width + 300 + g, startY + u + mh1); // mh값 그리기
   ctx.stroke();    
   ctx.lineTo(startX + width + 300 + g + jd1, startY + u + mh1); // jd값 그리기
   ctx.stroke();       
   if(k2>0)
   {
     ctx.lineTo(startX + width + k2 + 300 + g + jd1, startY + u + mh1); // k2값 그리기
     ctx.stroke();       
   }
   
   ctx.lineTo(startX + width + 300 + k2 + g + jd1, startY + u + mh1 - upper_wing); // 상판 날개값 그리기
   ctx.stroke();          
   
if(new_draw!='1') { 
   if(u>1)
		ctx.strokeText('( U ) ' + u , startX + 220 + width, startY );   // u치수 출력
   if(g>1)	
		ctx.strokeText('( G ) ' + g , startX + 220 + width, startY + u + 15);   // g치수 출력
   if(mh1>0)
		ctx.strokeText('( MH ) ' + mh1 , startX + 180 + g + width, startY + u + (mh1/2));   // mh치수 출력
   ctx.strokeText('( JD ) ' + jd1 , startX + 250 + g + width + jd1/2 , startY + u + mh1 + 25);   // jd치수 출력
   if(k2>0)
   {
     ctx.strokeText('( k2 ) ' + k2 , startX + 250 + g + k2 + width + jd2 - 50 , startY + u + mh1 + 25);   // k2치수 출력
     ctx.stroke();       
   }   
   ctx.strokeText('( 상판날개 ) ' + upper_wing , startX + 250 + g + width + jd1 -50 , startY + u + mh1 - upper_wing -20 );   // 상판 날개
}

   // side 그리기 그리기 ******
   // side 그리기 그리기 ******
   
   
   // side 좌측 기둥 그리기 ******

	        var canvas = document.getElementById('canvas_side');
            var ctx = canvas.getContext('2d');

             ctx.fillStyle = "rgba(255, 255, 255,1 )";
             ctx.clearRect (0, 0, 4000, 1600);	
			
		    var scale_lh1 =lh1 * scale;
		    var scale_lh2 =lh2 * scale;			
			var scale_rh1 = rh1 * scale ;
			var scale_rh2 = rh2 * scale ;	
			
	var max_height=0;
     	if(lh1>lh2)
				 max_height=lh1;
			    else
					 max_height=lh2;				 
	var side_left_height = mh1+max_height;				 
	var side_left_width = a1 + c1 + jb1 + side_leftwing;
	
			var startX = 400;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
			var startY = 80;	

 // side 좌측 단면도 그리기
   ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
   var xpos=startX-250;
   var ypos=startY + side_leftwing  ;  //첫번째 절곡라인
   var side_left_width=0;
   ctx.moveTo(xpos, ypos); // 300거리 띄워서 시작점 잡기
  
   ctx.lineTo(xpos + side_leftwing , ypos ); // 좌측날개 그리기
   ctx.stroke();
   side_left_width += side_leftwing;
  if(k1>0)  // k1값이 있을때 그려주기
  {	  
   ctx.lineTo(xpos + side_leftwing, ypos + k1 ); // k1값 그리기
   ctx.stroke();
   side_left_width += k1;
  }
   ypos=startY  + k1 ;  //
   Angle_left=90-Angle_left;
   var sinA=Math.sin(Angle_left*Math.PI/180) * jb1;
   var cosA=Math.cos(Angle_left*Math.PI/180) * jb1;
 
   ctx.lineTo(xpos   + side_leftwing - Math.abs(cosA.toFixed(1)) , ypos  + Math.abs(sinA.toFixed(1)) ); // jb값 그리기
   ctx.stroke();   
   side_left_width += jb1;       
   ctx.lineTo(xpos  + side_leftwing  - Math.abs(cosA.toFixed(1)) -c1 , ypos  + Math.abs(sinA.toFixed(1)) ); // c1값 그리기
   ctx.stroke();  
   side_left_width += c1;       
   ctx.lineTo(xpos  + side_leftwing  - Math.abs(cosA.toFixed(1)) -c1 , ypos  + Math.abs(sinA.toFixed(1)) -a1); // a1값 그리기   
   ctx.stroke();   
   side_left_width += a1;          
   ctx.closePath();	   

  if(new_draw!='1') {
  // side 좌측단면도 치수 써주기 
   ctx.font = '20px serif';
   sectionstart=startX-250;
   ctx.strokeText('뒷날개 ' + side_leftwing , sectionstart-50, startY);   // 날개치수 출력
   if(k1>0)    
		ctx.strokeText('( K ) ' + k1 , sectionstart+side_leftwing*1.5, startY+k1/2+20);   // k1치수 출력   
	
   ctx.strokeText('( JB ) ' + jb1 , sectionstart + side_leftwing*1.5, startY+k1+jb1/2);   // jb치수 출력  
   if(c1>0)    
		ctx.strokeText('( C1 ) ' + c1 ,  sectionstart-50, startY + k1 + jb1 + 40);   // c1치수 출력   
   if(a1>0) 
		ctx.strokeText('( A1 ) ' + a1 ,  sectionstart-130, startY + jb1 + k1 -20);   // a1치수 출력     
  }   

// side 좌측 기둥 그리기 (본판) 
if(mh1>1 && b1<1)
    {
	  b1=10; //가상치로 10을 준다. 보통 10~ 15미리 돌출부위
	  b2=10;
	}
  

        ctx.beginPath();
		ctx.moveTo(startX, startY + side_left_width);
		line(startX + mh1, startY + side_left_width);   
		line(startX + mh1 + lh1, startY + side_left_width);   
		line(startX + mh1 + lh1, startY + side_left_width - a1 - bendrate);   
		line(startX + mh1 + lh1, startY + side_left_width - a1 - c1 - bendrate*3);   
		line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - bendrate*4);   
		line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - k1 - bendrate*6);   
		line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - k1 - side_leftwing - bendrate*8);   
		line(startX + mh1, startY + side_left_width - a1 - c1 - jb1 - k1 - side_leftwing - bendrate*8);   
		line(startX + mh1, startY + side_left_width - a1 - c1 - b1 - bendrate*4);   
		line(startX , startY + side_left_width - a1 - c1 - b1 - bendrate*4);   
		line(startX , startY + side_left_width); 
        ctx.closePath();			

// side 좌측 절곡선을 그린다.
  if(new_draw!='1') {	  
  var offset=1;
  ctx.strokeStyle = '#09f';
  ctx.beginPath();
  ctx.setLineDash([70, 10]);
  ctx.lineDashOffset = -offset;
 
  ctx.moveTo( startX + mh1, startY + side_leftwing - bendrate*2 );     // 위에서 첫번째
  line(startX + mh1 + lh2, startY + side_leftwing - bendrate*2);       // 첫번째 절곡라인

  if(k1>0) {
  ctx.moveTo(startX + mh1 , startY + side_leftwing + k1 - bendrate*4);  	  
  line(startX + mh1 + lh2, startY + side_leftwing + k1 - bendrate*4); // 두번째 절곡라인 (9도만 접는부위는 안함)
  }
  ctx.moveTo(startX , startY + side_leftwing + k1 + jb1 - bendrate*5);  
  line(startX + mh1 + lh1, startY + side_leftwing + k1 + jb1 - bendrate*5); // 세번째 절곡라인 (9도만 접는부위는 안함)
  ctx.moveTo(startX , startY + side_leftwing + k1 + jb1 + c1 - bendrate*7);  
     line(startX + mh1 + lh1, startY + side_leftwing + k1 + jb1 + c1 - bendrate*7); // 네번째 절곡라인 

  ctx.closePath();			
  }


// side 좌측 치수문자 화면출력
  ctx.beginPath();
  var side_left_height = mh1 + max(lh1,lh2);
  if(new_draw!='1') {
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
  ctx.font = '25px serif';
  ctx.strokeText('( Side(좌) LH2 뒷날개쪽 Height ) ' + lh2 , startX + side_left_height/2 - 100, startY-25);   // lh2 출력
  ctx.strokeText('( Side(좌) LH1 Height ) ' + lh1 , startX + side_left_height/2 - 100, startY + side_left_width +30);   // lh1 출력
  ctx.strokeText('( Side(좌) 전체 Height ) ' + side_left_height , startX + side_left_height/2 - 100, startY + side_left_width +60);   // 전체 height 출력
     
  ctx.font = '20px serif';
  var p_txt=side_leftwing - bendrate*2;
  ctx.strokeText('( 뒷날개 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing - bendrate*2 - 20);   // 첫번째 절곡 날개치수 출력
  if(k1>0)
    {
     var p_txt=k1 - bendrate*2;		
     ctx.strokeText('( K1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 - bendrate*2 - 20);   // 두번째 절곡 날개치수 출력
	}
		if(k1>0)
			var p_txt=jb1 - bendrate*1;
		 else
		   var p_txt=jb1 - bendrate*3;
   
     ctx.strokeText('( JB1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 + jb1/2 - bendrate*2 - 20);   // 세번째 절곡 날개치수 출력  	  

     var p_txt = c1 - bendrate*2;		
     ctx.strokeText('( C1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 +jb1 + c1/2 - bendrate*2 );   // 네번째 절곡 날개치수 출력
     var p_txt = a1 - bendrate;		
     ctx.strokeText('( A1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 +jb1 + c1 + a1/2 - bendrate*2 );   // 5째 절곡 날개치수 출력	 
	 if(mh1>0)	{
		ctx.strokeText('( MH1 ) ' + mh1, startX + mh1/2 -20, startY + side_leftwing + k1 +jb1 - 40);   // 막판과 만나는 돌출부위
		ctx.strokeText('( 막판Gap 돌출부위 ) ' + b1, startX + mh1  + 5, startY + side_leftwing + k1 +jb1 - 25);   // 막판과 만나는 돌출부위	 
	 }
  }	 
	 
// 우측 기둥쪽 화면에 그리기 ****************************************************
// 우측 기둥쪽 화면에 그리기 ****************************************************	 

	var max_height=0;
     	if(rh1>rh2)
				 max_height=rh1;
			    else
					 max_height=rh2;				 
	var side_right_height = mh2+max_height;				 
	var side_right_width = a2 + c2 + jb2 + side_rightwing;
	
			var startX = 400;  // 처음 선을 그릴때 우,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
			var startY = side_left_width * 2 + 250;	

 // 실측치 side 우측 단면도 그리기
  ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
   var xpos=startX - 250;
   var ypos=startY;  //첫번째 절곡라인
   var side_right_width=0;
   ctx.moveTo(xpos, ypos); // 시작점 잡기
  
   ctx.lineTo(xpos + side_rightwing , ypos ); // 우측날개 그리기
   ctx.stroke();
   side_right_width += side_rightwing;
  if(k2>0)  // k2값이 있을때 그려주기
  {	  
   ctx.lineTo(xpos + side_rightwing, ypos - k2 ); // k2값 그리기
   ctx.stroke();
   side_right_width += k2;
  }
   ypos=startY - k2 ;  //
  
if(new_draw!='1') {  
   Angle_right=90- Angle_right;
   var sinA=Math.sin(Angle_right*Math.PI/180) * jb2;
   var cosA=Math.cos(Angle_right*Math.PI/180) * jb2;
}
 else
 {
   var sinA=Math.sin(Angle_right*Math.PI/180) * jb2;
   var cosA=Math.cos(Angle_right*Math.PI/180) * jb2;
 }
					 
			   
   ctx.lineTo(xpos + side_rightwing - Math.abs(cosA.toFixed(1)) , ypos - Math.abs(sinA.toFixed(1)) ); // jb값 그리기
   ctx.stroke();   
   side_right_width += jb2;       
   ctx.lineTo(xpos + side_rightwing  - Math.abs(cosA.toFixed(1)) - c2 , ypos - Math.abs(sinA.toFixed(1)) ); // c2값 그리기
   ctx.stroke();  
   side_right_width += c2;       
   ctx.lineTo(xpos + side_rightwing  - Math.abs(cosA.toFixed(1)) - c2 , ypos - Math.abs(sinA.toFixed(1)) + a2); // a2값 그리기   
   ctx.stroke();   
   
  
   side_right_width += a2;          
   ctx.closePath();	   

  // side우측 단면도 치수 써주기 
 sectionstart=startX-250;  
 if(new_draw!='1') {  
   ctx.font = '20px serif';
   ctx.strokeText('뒷날개 ' + side_rightwing , sectionstart-50, startY+ 20);   // 날개치수 출력
   if(k2>0)
		ctx.strokeText('( K ) ' + k2 , sectionstart+side_rightwing*1.5, startY -k2/2  );   // k2치수 출력   
   ctx.strokeText('( JB ) ' + jb2 , sectionstart + side_rightwing*1.5, startY -k2 - jb2/2);   // jb치수 출력   
   if(c2>0)
		ctx.strokeText('( C2 ) ' + c2 ,  sectionstart-40, startY - k2 - jb2 - 10);   // c2치수 출력   
   if(a2>0)
		ctx.strokeText('( A2 ) ' + a2 ,  sectionstart-130, startY - jb2 - k2 + 30);   // a2치수 출력     
  }  

// side우측 그리기 (본판) 
	    var startY = side_left_width * 1 + 300;	 // startY값 재설정

        ctx.beginPath();
		ctx.moveTo(startX, startY );
		line(startX + mh2, startY );   
		line(startX + mh2 + rh1, startY );   
		line(startX + mh2 + rh1, startY  + a2 - bendrate);   
		line(startX + mh2 + rh1, startY  + a2 + c2 - bendrate*3);   
		line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + bendrate*4);   
		line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + k2 - bendrate*6);   
		line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + k2 + side_rightwing - bendrate*8);   
		line(startX + mh2, startY + a2 + c2 + jb2 + k2 + side_rightwing - bendrate*8);   
		line(startX + mh2, startY + a2 + c2 + b1 - bendrate*4);   
		line(startX , startY + a2 + c2 + b1 - bendrate*4);   
		line(startX , startY); 
        ctx.closePath();			

// side우측 절곡선을 그린다.
  if(new_draw!='1') {
  var offset=1;
  ctx.strokeStyle = '#09f';
  ctx.beginPath();
  ctx.setLineDash([70, 10]);
  ctx.lineDashOffset = -offset;
  if(a2>0) {
  ctx.moveTo(startX , startY + a2 - bendrate*1);  
  line(startX + mh2 + rh1, startY + a2 - bendrate*1); // 첫번째 절곡라인   
  }  
  if(c2>0) {
    ctx.moveTo(startX , startY + a2 +c2 - bendrate*3);  
    line(startX + mh2 + rh1, startY + a2 + c2 - bendrate*3); // 두번째 절곡라인    
  }

  if(k2>0) {
  ctx.moveTo(startX + mh2 , startY + a2 + c2 + jb2 - bendrate*4);  	  
  line(startX + mh2 + rh2, startY + a2 + c2  + jb2 - bendrate*4); // 세번째 절곡라인 (9도만 접는부위는 안함)
  }
  ctx.moveTo(startX + mh2, startY + a2 + c2 + k2 + jb2 - bendrate*6);  
  line(startX + mh2 + rh2, startY + a2 + c2 + k2 + jb2 - bendrate*6); // 네번째 절곡라인

  ctx.closePath();			
 }

// side우측 치수문자 화면출력
  if(new_draw!='1') {
  ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
  var side_right_height = mh2 + max(rh1,rh2);
  ctx.font = '25px serif';
  ctx.strokeText('( Side(우) RH2 뒷날개쪽 Height ) ' + rh2 , startX + side_right_height/2 - 100, startY + side_right_width +30);   // rh2 출력
  ctx.strokeText('( Side(우) RH1 Height ) ' + rh1 , startX + side_right_height/2 - 100, startY - 30);   // rh1 출력
  ctx.strokeText('( Side(우) 전체 Height ) ' + side_right_height , startX + side_right_height/2 - 100, startY - 60);   // 전체 height 출력
     
  ctx.font = '20px serif';
  var p_txt=side_rightwing - bendrate*2;
  ctx.strokeText('( 뒷날개 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + k2 +jb2 + c2 + a2 + side_rightwing/2 - bendrate*2 + 10);   // 첫번째 절곡 날개치수 출력
  if(k2>0)
    {
     var p_txt=k2 - bendrate*2;		
     ctx.strokeText('( K2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + jb2 + c2 + a2 +  k2/2 - bendrate*2 - 10);   // 두번째 절곡 날개치수 출력
	}
		if(k2>0)
			var p_txt=jb2 - bendrate*1;
		 else
		   var p_txt=jb2 - bendrate*3;
   
     ctx.strokeText('( JB2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + a2 + c2 + jb2/2 - bendrate*2 - 20);   // 세번째 절곡 날개치수 출력  	  

     var p_txt = c2 - bendrate*2;	
     if(c2>0)	 
		ctx.strokeText('( C2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY  + a2 + c2/2 - bendrate*2 );   // 네번째 절곡 날개치수 출력
     var p_txt = a2 - bendrate;	
     if(a2>0)	 
		ctx.strokeText('( A2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY  - bendrate );   // 5째 절곡 날개치수 출력	 
     if(mh1>0) {
		ctx.strokeText('( MH2 ) ' + mh2, startX + mh2/2  - 20, startY +a2 + c2 + 45);   // 막판과 만나는 돌출부위	 
      if(b1>1)		
		  ctx.strokeText('( 막판Gap 돌출부위 ) ' + b2, startX + mh2  + 5, startY +a2 + c2 + 25);   // 막판과 만나는 돌출부위	 
	 }
  }	 

   
}

//*****************************************************************************************************************************************
					// 제작치수 와이드쟘 상판 그려주기  
					function load_makedraw_widejamb() {
						
								var xscale=$("#Xscale option:selected").val();
								var scale;
								// alert(xscale);
								if(xscale=='none') scale=1;
								if(xscale=='2x1') scale=0.5;
								if(xscale=='3x1') scale=0.33;
								if(xscale=='5x1') scale=0.2;
								var upper_pop;
								
								var canvas = document.getElementById('canvas');
								var ctx = canvas.getContext('2d');
								
								var pos = $('#col1').val();// 몇번째 데이터인지 불러온다.
							   //  alert(pos);
								
								var startX = 200;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
								var startY = 80;	
								
								// 제작치수 반영치 설정에서 c값의 차이를 불러온다.
								var c_gap = Number($('#setdata tr:eq(1)>td:eq(5)').html());						
								var jb_gap = Number($('#setdata tr:eq(1)>td:eq(4)').html());														
								var a_gap = Number($('#setdata tr:eq(1)>td:eq(6)').html());														

								
								// alert(c_gap);
								
								
							  // 실측서를 먼저 화면에 그려준다.
								var title1 = $('#spreadsheet tr:eq(' + pos + ')>td:eq(1)').html();			
								
								var title2 = $('#spreadsheet tr:eq(' + pos + ')>td:eq(2)').html();
								
								var u = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(3)').html());
								var g = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(4)').html());			
							
								var mh1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(5)').html());
								var mh2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(6)').html());
								var jd1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(7)').html());
								var jd2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(8)').html());
								var op1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(9)').html());
								var op2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(10)').html());
								var r = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(11)').html());
								var k1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(12)').html());
								var k2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(13)').html());
								var upper_wing = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(14)').html());
								var jb1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(15)').html());
								var jb2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(16)').html());
								var c1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(17)').html());
								var c2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(18)').html());
								var a1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(19)').html());
								var a2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(20)').html());
								var b1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(21)').html());
								var b2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(22)').html());
								var side_leftwing = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(23)').html());
								var side_rightwing = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(24)').html());
								var lh1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(25)').html());
								var lh2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(26)').html());
								var rh1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(27)').html());
								var rh2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(28)').html());
														

									// 연신율
									var bendrate=Number($("#sel3 option:selected").val());
									var bend_level=0; // 와이드쟘 쫄대타입이면 4회 밴딩			level로 벤딩 회수 산출

									var h1=c1;   // 상판의 c1부위를 다른이름으로 지정, 막판에 귀돌이 없는 부분을 표현하기 위함.
									var h2=c2;			
									 
									 if(k2<1) 
										  k2=k1;
									 if(mh2<1) 
										  mh2=mh1;			  
									 if(jb2<1) 
										  jb2=jb1;			
									 if(jd2<1) 
										  jd2=jd1;		

								if(b1<=0) 
									b1=g-2;	
								if(b2<=0) 
									b2=g-2;
								
								if(u>1)
                						startX = startX - c_gap;								
									else
                						startX = startX;																		
								
							  if(u<1)
								{
									h1=0;h2=0;
								}		
									var width = r + h1+h2;			 
									
									if(u>0) bend_level++;
									if(g>0) bend_level += 2;
									if(mh1>0) bend_level += 2;
									if(jd1>0) bend_level += 2;
									if(upper_wing>0) bend_level++;
									
									var height = u + g + mh1 + jd1 + upper_wing	- (bendrate*bend_level) + k1 ;
									
									 $('#title').text(title1 + '호기 ' + title2 + '층' + ' (' + width + ' x ' + height + ' mm)    실측쟘 : 검정색라인');		 			 
									 
							 var bend_level=1;  
							   // 와이드쟘 실측치 상판 돌출부위 산출
							   if(u>1 && g>1) {
								   if(bendrate==0.75)
									   upper_pop = 3.25;
												else
														upper_pop = 3.4;			  
										}
										 else
											 upper_pop=0;
									  
											ctx.beginPath();
											ctx.setLineDash([0, 0]);
											ctx.strokeStyle = 'red';						  
											ctx.moveTo(startX, startY);
											ctx.lineTo(startX + width, startY);                             // width 
											ctx.stroke();		
										 if(u>0) {	  // u값이 0보다 클때
											ctx.lineTo(startX + width, startY + u);                           // u
											ctx.stroke();			
											ctx.lineTo(startX + width, startY + u + upper_pop - bendrate*bend_level);  // 상부 돌출부위
											ctx.stroke();		
											ctx.lineTo(startX + width - h2, startY + u + upper_pop - bendrate*(bend_level+1));  // 상판 돌출
											ctx.stroke();						
											bend_level += 2;
										 }
										 if(g>0) {
											ctx.lineTo(startX + width - h2, startY + u + (g-upper_pop) - bendrate*bend_level);  // g값
											ctx.stroke();			     					
											bend_level += 2;
											 }
											ctx.lineTo(startX + width - h2, startY + u + g + mh1 - bendrate * bend_level);  // mh1
											ctx.stroke();		
											bend_level ++;					
										var cal = (r - op1)/2	; 		
											ctx.lineTo(startX + width - h2 - cal, startY + u + g + mh1 +jd1 - bendrate * bend_level);  // jd까지 오른쪽 그리기
											ctx.stroke();		
										if(k1>0) // k1값이 있으면 늘어난치수 있음
										{
											bend_level ++;										 
											ctx.lineTo(startX + width - h2 - cal, startY + u + g + mh1 +jd1 + k1- bendrate * bend_level);  // k1까지 오른쪽 그리기
											ctx.stroke();	
										}					
											bend_level ++;										 
											ctx.lineTo(startX + width - h2 - cal, startY + u + g + mh1 +jd1 + k1 + upper_wing - bendrate * bend_level);  // 날개
											ctx.stroke();							
											ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 + k1 + upper_wing - bendrate * bend_level);  // 오픈그리기
											ctx.stroke();		
											bend_level --;
											ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 + k1 -  bendrate * bend_level);  //jd까지 왼쪽 상판 날개
											ctx.stroke();		
										if(k1>0) // k2값이 있으면 늘어난치수 있음
										{
											ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 - bendrate * bend_level);  // k1까지 오른쪽 그리기
											ctx.stroke();	
										}	
										
											bend_level -= 2;
											ctx.lineTo(startX + h1 , startY + u + g + mh1 - bendrate * bend_level);  // 왼쪽jd영역
											ctx.stroke();	

											bend_level -= 2;
											ctx.lineTo(startX + h1, startY + u + (g-upper_pop) - bendrate*bend_level);  // 왼쪽 mh
											ctx.stroke();
									 if(u>0) {
											ctx.lineTo(startX + h1, startY + u + upper_pop - bendrate*1);  // 상판 돌출
											ctx.stroke();						
											ctx.lineTo(startX , startY + u + upper_pop - bendrate*1);  //				
											ctx.stroke();						
											}
											ctx.lineTo(startX, startY);  //				
											ctx.stroke();						
											// 각도구하기  절곡 회수 구하기
											bend_level=0;
										 if(u>0)
											  bend_level += 2;
										 if(g>0)
											  bend_level += 2;				  
										  if(mh1>0)
											  bend_level ++;				  
										  
											var Angle_left = getAngle(startX + width - h2, startY + u + g + mh1 - bendrate * bend_level,   startX + width - h2 - cal, startY + u + g + mh1 +jd1 - bendrate * 7);
											Angle_left=Angle_left.toFixed(1);
											var Angle_right = getAngle(startX + h1 , startY + u + g + mh1 - bendrate * bend_level,     startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 -  bendrate * 7);
											Angle_right=Angle_right.toFixed(1);
											
											$("#left_angle").val(Angle_left);
											$("#right_angle").val(Angle_right);

						 // 상판 절곡선을 그린다
						  
						  var offset=1;
						  ctx.strokeStyle = 'brown';
						  ctx.beginPath();
						  ctx.setLineDash([70, 10]);
						  ctx.lineDashOffset = -offset;
						  bend_level=1;
							 
						  if(u>1)   {
						  ctx.moveTo(startX, startY+u-bendrate*bend_level);  //   
						  ctx.lineTo(startX + width, startY+u-bendrate*bend_level);       // 첫번째 절곡라인   
						  ctx.stroke();  
						  bend_level += 2;
							  }
						  if(g>1)   {	  
								   ctx.moveTo(startX + h1, startY + u + g - bendrate*bend_level);           // 두번째 절곡라인     
								   ctx.lineTo(startX + width - h2 , startY + u + g - bendrate*bend_level);
								   ctx.stroke();	
								   bend_level += 2;		   
								}
						   if(mh1>0) {
						   ctx.moveTo(startX + width - h2, startY + u + g + mh1 - bendrate * bend_level); // 세번째 절곡라인     
						   ctx.lineTo(startX + h1, startY + u + g + mh1 - bendrate * bend_level); 
						   ctx.stroke();  
						   bend_level += 2;		      
							  }
						   ctx.moveTo(startX + width - h2 - cal, startY + u + g + mh1 +jd1 + k1- bendrate * bend_level); // 네번째 절곡라인     
						   ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + mh1 + k1 + jd1 -  bendrate * bend_level); 
						   ctx.stroke();        
						   ctx.closePath();	

						// 치수문자 화면출력
						  ctx.beginPath();
						  ctx.strokeStyle = 'blue';
						  ctx.setLineDash([0, 0]);
						  ctx.font = '20px serif';
						  var p_u=u-bendrate;
						  if(u>1)
							  var p_g=g-bendrate*2;
							 else
							  var p_g=g-bendrate*1;		 
						  
						  ctx.strokeText('( 전체폭 ) ' + width, (startX + width)/2, startY - 20);   // 전체폭 출력
						  ctx.strokeText('( R ) ' + r, (startX + width)/2, startY + g + u + 30);   // r치수 출력
						  if(u>1)
							 ctx.strokeText('( U ) ' + p_u, startX+ width +50, startY + u - (u/2) );   // u치수 출력
						  if(g>1)
							 ctx.strokeText('( G ) ' + p_g, startX+ width +50, startY + u + g - (g/2) );   // g치수 출력
						 if(h1>1) 
							 ctx.strokeText('( H2 ) ' + h2, startX+ width -h2 , startY + u + g + 20 );   // h2치수 출력
						 if(h2>1)
							 ctx.strokeText('( H1 ) ' + h1, startX+ +h1 -80 , startY + u + g + 20 );   // h1치수 출력
						  var p_upper_pop=upper_pop; 
						 if(u>1 && g>1)   
							 ctx.strokeText('(상판쫄대돌출) ' + p_upper_pop, startX+ width -230, startY + u + g + upper_pop*5 );   // 상판돌출 치수 출력
						 if(g>1) p_mh=mh1-bendrate*2;
							else
								p_mh=mh1-bendrate*1;
							
							 if(mh1>0) 
									ctx.strokeText('( MH ) ' + p_mh, startX+ width, startY + u + g - (g/2) + mh1/2 );   // mh 치수 출력 
						  p_jd=jd1-bendrate*2;
						  ctx.strokeText('( JD ) ' + p_jd, startX+ width, startY + u + g - (g/2) + +mh1 + jd1/2 );   // jd 치수 출력   
						  p_upper_wing=upper_wing - bendrate*1;
						  ctx.strokeText('( 상판날개 ) ' + p_upper_wing, startX+ width, startY + u + g + mh1 + jd1 + k1 + upper_wing/2 );   // 상판날개 치수 출력    
										if(k1>0) // k2값이 있으면 늘어난치수 있음
										{
											  ctx.strokeText('( K2 ) ' + k2, startX+ width, startY + u + g  +mh1 + jd1 + k1/2 );   // k1 치수 출력    
											ctx.stroke();	
										}	  
						  Angle_right = 90 - Angle_right;
						  Angle_right=Angle_right.toFixed(1);
						  ctx.strokeText('각도 ' + Angle_right + ' ˚', startX+ width-150, startY + u + g - (g/2) + +mh1 + jd1/2 );   // 각도 출력    
						  ctx.strokeText('각도 ' + Angle_left + ' ˚', startX+ h1 +50, startY + u + g - (g/2) + +mh1 + jd1/2 );   // 좌측 각도 출력    
						  ctx.strokeText('( OP ) ' + op1, (startX + width)/2, startY + height + 30);   // op 치수 출력  
						  ctx.strokeText('( 전체높이 ) ' + height, startX-150 , startY + u + g - (g/2) + mh1/2 + 100 );   // 전체높이 치수 출력 
						  ctx.closePath();	   
						  
						  
						 //상판 단면도 그리기 ***********************************************************************************************************************
						   ctx.beginPath();
				           ctx.strokeStyle = 'red';
						   ctx.moveTo(startX + width + 300, startY );   // 300거리 띄워서 시작점 잡기
						  if(u>1) {
						   ctx.lineTo(startX + width + 300 , startY + u ); // u값 그리기
						   ctx.stroke();  
						  }
						  if(g>1) {
						   ctx.lineTo(startX + width + 300 + g, startY + u); // g값 그리기
						   ctx.stroke();    
						  }
						   ctx.lineTo(startX + width + 300 + g, startY + u + mh1); // mh값 그리기
						   ctx.stroke();    
						   ctx.lineTo(startX + width + 300 + g + jd1, startY + u + mh1); // jd값 그리기
						   ctx.stroke();       
						   if(k2>0)
						   {
							 ctx.lineTo(startX + width + k2 + 300 + g + jd1, startY + u + mh1); // k2값 그리기
							 ctx.stroke();       
						   }
						   
						   ctx.lineTo(startX + width + 300 + k2 + g + jd1, startY + u + mh1 - upper_wing); // 상판 날개값 그리기
						   ctx.stroke();       

						  ctx.strokeStyle = 'blue';						   
						   
						   if(u>1)
								ctx.strokeText('( U ) ' + u , startX + 220 + width, startY );   // u치수 출력
						   if(g>1)	
								ctx.strokeText('( G ) ' + g , startX + 220 + width, startY + u + 15);   // g치수 출력
						   if(mh1>0)
								ctx.strokeText('( MH ) ' + mh1 , startX + 180 + g + width, startY + u + (mh1/2));   // mh치수 출력
						   ctx.strokeText('( JD ) ' + jd1 , startX + 250 + g + width + jd1/2 , startY + u + mh1 + 25);   // jd치수 출력
						   if(k2>0)
						   {
							 ctx.strokeText('( k2 ) ' + k2 , startX + 250 + g + k2 + width + jd2 - 50 , startY + u + mh1 + 25);   // k2치수 출력
							 ctx.stroke();       
						   }   
						   ctx.strokeText('( 상판날개 ) ' + upper_wing , startX + 250 + g + width + jd1 -50 , startY + u + mh1 - upper_wing -20 );   // 상판 날개


						   // side 그리기 그리기 ******
						   // side 그리기 그리기 ******
						   
						   
						   // side 좌측 기둥 그리기 ******

									var canvas = document.getElementById('canvas_side');
									var ctx = canvas.getContext('2d');
									
									var scale_lh1 =lh1 * scale;
									var scale_lh2 =lh2 * scale;			
									var scale_rh1 = rh1 * scale ;
									var scale_rh2 = rh2 * scale ;	
									
							var max_height=0;
								if(lh1>lh2)
										 max_height=lh1;
										else
											 max_height=lh2;				 
							var side_left_height = mh1+max_height;				 
							var side_left_width = a1 + c1 + jb1 + side_leftwing;
							
									var startX = 400;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
									var startY = 80;	

						 // side 좌측 단면도 그리기
						   ctx.beginPath();
						  ctx.strokeStyle = 'red';
						  ctx.setLineDash([0, 0]);
						   var xpos=startX-250;
						   var ypos=startY + side_leftwing  ;  //첫번째 절곡라인
						   var side_left_width=0;
						   ctx.moveTo(xpos, ypos); // 300거리 띄워서 시작점 잡기
						  
						   ctx.lineTo(xpos + side_leftwing , ypos ); // 좌측날개 그리기
						   ctx.stroke();
						   side_left_width += side_leftwing;
						  if(k1>0)  // k1값이 있을때 그려주기
						  {	  
						   ctx.lineTo(xpos + side_leftwing, ypos + k1 ); // k1값 그리기
						   ctx.stroke();
						   side_left_width += k1;
						  }
						   ypos=startY  + k1 ;  //
						   Angle_left=90-Angle_left;
						   var sinA=Math.sin(Angle_left*Math.PI/180) * jb1;
						   var cosA=Math.cos(Angle_left*Math.PI/180) * jb1;
						 
						   ctx.lineTo(xpos   + side_leftwing - Math.abs(cosA.toFixed(1)) , ypos  + Math.abs(sinA.toFixed(1)) ); // jb값 그리기
						   ctx.stroke();   
						   side_left_width += jb1;       
						   ctx.lineTo(xpos  + side_leftwing  - Math.abs(cosA.toFixed(1)) -c1 , ypos  + Math.abs(sinA.toFixed(1)) ); // c1값 그리기
						   ctx.stroke();  
						   side_left_width += c1;       
						   ctx.lineTo(xpos  + side_leftwing  - Math.abs(cosA.toFixed(1)) -c1 , ypos  + Math.abs(sinA.toFixed(1)) -a1); // a1값 그리기   
						   ctx.stroke();   
						   side_left_width += a1;          
						   
						// 돌출부위 산출하기 B값
						   ctx.moveTo(xpos   + side_leftwing + 30, ypos + jd1); // jd값 선 긋기 이동
                           line(xpos   + side_leftwing + 80, ypos + jd1);					
						   b1 = (ypos  + Math.abs(sinA.toFixed(1))) - (ypos + jd1) ;						   
						   b1 = b1.toFixed(1);		
                           b1 = parseInt(b1);						   
						   						   
						   ctx.closePath();	   
						   
						   

						  // side 좌측단면도 치수 써주기 				  
						   ctx.beginPath();
						   ctx.font = '20px serif';
						   ctx.strokeStyle = 'blue';
						   sectionstart=startX-250;
						   ctx.strokeText('뒷날개 ' + side_leftwing , sectionstart-50, startY);   // 날개치수 출력
						   if(k1>0)    
								ctx.strokeText('( K ) ' + k1 , sectionstart+side_leftwing*1.5, startY+k1/2+20);   // k1치수 출력   
							
						   ctx.strokeText('( JB ) ' + jb1 , sectionstart + side_leftwing*1.5, startY+k1+jb1/2);   // jb치수 출력  
						   if(c1>0)    
								ctx.strokeText('( C1 ) ' + c1 ,  sectionstart-50, startY + k1 + jb1 + 40);   // c1치수 출력   
						   if(a1>0) 
								ctx.strokeText('( A1 ) ' + a1 ,  sectionstart-130, startY + jb1 + k1 -20);   // a1치수 출력     
						   if(b1>0)
  						     	ctx.strokeText('( 돌출B1 ) ' + b1 ,  xpos   + side_leftwing + 30, ypos + jd1-30);   // b1치수 출력     				   
	
						// side 좌측 기둥 그리기 (본판) 
						if(mh1>1 && b1<1)
							{
							  b1=10; //가상치로 10을 준다. 보통 10~ 15미리 돌출부위
							  b2=10;
							}
						  

								ctx.beginPath();
								ctx.strokeStyle = 'red';
								ctx.moveTo(startX, startY + side_left_width);
								line(startX + mh1, startY + side_left_width);   
								line(startX + mh1 + lh1, startY + side_left_width);   
								line(startX + mh1 + lh1, startY + side_left_width - a1 - bendrate);   
								line(startX + mh1 + lh1, startY + side_left_width - a1 - c1 - bendrate*3);   
								line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - bendrate*4);   
								line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - k1 - bendrate*6);   
								line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - k1 - side_leftwing - bendrate*8);   
								line(startX + mh1, startY + side_left_width - a1 - c1 - jb1 - k1 - side_leftwing - bendrate*8);   
								line(startX + mh1, startY + side_left_width - a1 - c1 - b1 - bendrate*4);   
								line(startX , startY + side_left_width - a1 - c1 - b1 - bendrate*4);   
								line(startX , startY + side_left_width); 
								ctx.closePath();			

						// side 좌측 절곡선을 그린다.
						  var offset=1;
						  ctx.strokeStyle = 'brown';
						  ctx.beginPath();
						  ctx.setLineDash([70, 10]);
						  ctx.lineDashOffset = -offset;
						 
						  ctx.moveTo( startX + mh1, startY + side_leftwing - bendrate*2 );     // 위에서 첫번째
						  line(startX + mh1 + lh2, startY + side_leftwing - bendrate*2);       // 첫번째 절곡라인

						  if(k1>0) {
						  ctx.moveTo(startX + mh1 , startY + side_leftwing + k1 - bendrate*4);  	  
						  line(startX + mh1 + lh2, startY + side_leftwing + k1 - bendrate*4); // 두번째 절곡라인 (9도만 접는부위는 안함)
						  }
						  ctx.moveTo(startX , startY + side_leftwing + k1 + jb1 - bendrate*5);  
						  line(startX + mh1 + lh1, startY + side_leftwing + k1 + jb1 - bendrate*5); // 세번째 절곡라인 (9도만 접는부위는 안함)
						  ctx.moveTo(startX , startY + side_leftwing + k1 + jb1 + c1 - bendrate*7);  
							 line(startX + mh1 + lh1, startY + side_leftwing + k1 + jb1 + c1 - bendrate*7); // 네번째 절곡라인 

						  ctx.closePath();			


						// side 좌측 치수문자 화면출력
						  ctx.beginPath();
						  var side_left_height = mh1 + max(lh1,lh2);
						  ctx.strokeStyle = 'blue';
						  ctx.setLineDash([0, 0]);
						  ctx.font = '25px serif';
						  ctx.strokeText('( Side(좌) LH2 뒷날개쪽 Height ) ' + lh2 , startX + side_left_height/2 - 100, startY-25);   // lh2 출력
						  ctx.strokeText('( Side(좌) LH1 Height ) ' + lh1 , startX + side_left_height/2 - 100, startY + side_left_width +30);   // lh1 출력
						  ctx.strokeText('( Side(좌) 전체 Height ) ' + side_left_height , startX + side_left_height/2 - 100, startY + side_left_width +60);   // 전체 height 출력
							 
						  ctx.font = '20px serif';
						  var p_txt=side_leftwing - bendrate*2;
						  ctx.strokeText('( 뒷날개 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing - bendrate*2 - 20);   // 첫번째 절곡 날개치수 출력
						  if(k1>0)
							{
							 var p_txt=k1 - bendrate*2;		
							 ctx.strokeText('( K1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 - bendrate*2 - 20);   // 두번째 절곡 날개치수 출력
							}
								if(k1>0)
									var p_txt=jb1 - bendrate*1;
								 else
								   var p_txt=jb1 - bendrate*3;
						   
							 ctx.strokeText('( JB1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 + jb1/2 - bendrate*2 - 20);   // 세번째 절곡 날개치수 출력  	  

							 var p_txt = c1 - bendrate*2;		
							 ctx.strokeText('( C1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 +jb1 + c1/2 - bendrate*2 );   // 네번째 절곡 날개치수 출력
							 var p_txt = a1 - bendrate;		
							 ctx.strokeText('( A1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 +jb1 + c1 + a1/2 - bendrate*2 );   // 5째 절곡 날개치수 출력	 
							 if(mh1>0)	{
								ctx.strokeText('( MH1 ) ' + mh1, startX + mh1/2 -20, startY + side_leftwing + k1 +jb1 - 40);   // 막판과 만나는 돌출부위
								ctx.strokeText('( 막판Gap 돌출부위 ) ' + b1, startX + mh1  + 5, startY + side_leftwing + k1 +jb1 - 25);   // 막판과 만나는 돌출부위	 
							 }
							 
						// 우측 기둥쪽 화면에 그리기 ****************************************************
						// 우측 기둥쪽 화면에 그리기 ****************************************************	 

							var max_height=0;
								if(rh1>rh2)
										 max_height=rh1;
										else
											 max_height=rh2;				 
							var side_right_height = mh2+max_height;				 
							var side_right_width = a2 + c2 + jb2 + side_rightwing;
							
									var startX = 400;  // 처음 선을 그릴때 우,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
									var startY = side_left_width * 2 + 250 - 20;	

						 // 실측치 side 우측 단면도 그리기
						  ctx.beginPath();
						  ctx.strokeStyle = 'red';
						  ctx.setLineDash([0, 0]);
						   var xpos=startX - 250;
						   var ypos=startY;  //첫번째 절곡라인
						   var side_right_width=0;
						   ctx.moveTo(xpos, ypos); // 시작점 잡기
						  
						   ctx.lineTo(xpos + side_rightwing , ypos ); // 우측날개 그리기
						   ctx.stroke();
						   side_right_width += side_rightwing;
						  if(k2>0)  // k2값이 있을때 그려주기
						  {	  
						   ctx.lineTo(xpos + side_rightwing, ypos - k2 ); // k2값 그리기
						   ctx.stroke();
						   side_right_width += k2;
						  }
						   ypos=startY - k2 ;  //						  

						   Angle_right=90- Angle_right;
						   var sinA=Math.sin(Angle_right*Math.PI/180) * jb2;
						   var cosA=Math.cos(Angle_right*Math.PI/180) * jb2;
											 
									   
						   ctx.lineTo(xpos + side_rightwing - Math.abs(cosA.toFixed(1)) , ypos - Math.abs(sinA.toFixed(1)) ); // jb값 그리기
						   ctx.stroke();   
						   side_right_width += jb2;       
						   ctx.lineTo(xpos + side_rightwing  - Math.abs(cosA.toFixed(1)) - c2 , ypos - Math.abs(sinA.toFixed(1)) ); // c2값 그리기
						   ctx.stroke();  
						   side_right_width += c2;       
						   ctx.lineTo(xpos + side_rightwing  - Math.abs(cosA.toFixed(1)) - c2 , ypos - Math.abs(sinA.toFixed(1)) + a2); // a2값 그리기   
						   ctx.stroke();   						  
						   side_right_width += a2;          
						// 돌출부위 산출하기 B값
						   ctx.moveTo(xpos   + side_leftwing + 30, ypos - jd2); // jd값 선 긋기 이동
                           line(xpos   + side_leftwing + 80, ypos - jd1);					
						   b2 = (ypos - jd1) - (ypos  - Math.abs(sinA.toFixed(1)))  ;						   
						   b2 = b2.toFixed(1);		
						   b2=parseInt(b2);   // int형으로 바꾸기, 소수점이 있으면 그래프가 산으로 감.
						   ctx.closePath();	   

						  // side우측 단면도 치수 써주기 
						 sectionstart=startX-250;  
						   ctx.strokeStyle = 'blue';
						   ctx.font = '20px serif';
						   ctx.beginPath();
						   ctx.strokeText('뒷날개 ' + side_rightwing , sectionstart-50, startY+ 20);   // 날개치수 출력
						   if(k2>0)
								ctx.strokeText('( K ) ' + k2 , sectionstart+side_rightwing*1.5, startY -k2/2  );   // k2치수 출력   
						   ctx.strokeText('( JB ) ' + jb2 , sectionstart + side_rightwing*1.5, startY -k2 - jb2/2);   // jb치수 출력   
						   if(c2>0)
								ctx.strokeText('( C2 ) ' + c2 ,  sectionstart-40, startY - k2 - jb2 - 10);   // c2치수 출력   
						   if(a2>0)
								ctx.strokeText('( A2 ) ' + a2 ,  sectionstart-130, startY - jb2 - k2 + 30);   // a2치수 출력     
						   if(b2>0)
  						     	ctx.strokeText('( 돌출B2 ) ' + b2 ,   sectionstart+30,  startY  - jd2 );   // b2치수 출력     
						   ctx.closePath();	 	
							
						// side우측 그리기 (본판) 
								var startY = side_left_width * 1 + 300 - 20;	 // startY값 재설정

								ctx.beginPath();
								ctx.strokeStyle = 'red';
								
								ctx.moveTo(startX, startY );
								line(startX + mh2, startY );   
								line(startX + mh2 + rh1, startY );   
								line(startX + mh2 + rh1, startY  + a2 - bendrate);   
								line(startX + mh2 + rh1, startY  + a2 + c2 - bendrate*3);   
								line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + bendrate*4);   
								line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + k2 - bendrate*6);   
								line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + k2 + side_rightwing - bendrate*8);   
								line(startX + mh2, startY + a2 + c2 + jb2 + k2 + side_rightwing - bendrate*8);   
								line(startX + mh2, startY + a2 + c2 +b2 - bendrate*4);   
								line(startX , startY + a2 + c2 + b2 - bendrate*4);   
								line(startX , startY); 
								
								ctx.closePath();			

						// side우측 절곡선을 그린다.
						  var offset=1;
						  ctx.strokeStyle = 'brown';
						  ctx.beginPath();
						  ctx.setLineDash([70, 10]);
						  ctx.lineDashOffset = -offset;
						  if(a2>0) {
						  ctx.moveTo(startX , startY + a2 - bendrate*1);  
						  line(startX + mh2 + rh1, startY + a2 - bendrate*1); // 첫번째 절곡라인   
						  }  
						  if(c2>0) {
							ctx.moveTo(startX , startY + a2 +c2 - bendrate*3);  
							line(startX + mh2 + rh1, startY + a2 + c2 - bendrate*3); // 두번째 절곡라인    
						  }

						  if(k2>0) {
						  ctx.moveTo(startX + mh2 , startY + a2 + c2 + jb2 - bendrate*4);  	  
						  line(startX + mh2 + rh2, startY + a2 + c2  + jb2 - bendrate*4); // 세번째 절곡라인 (9도만 접는부위는 안함)
						  }
						  ctx.moveTo(startX + mh2, startY + a2 + c2 + k2 + jb2 - bendrate*6);  
						  line(startX + mh2 + rh2, startY + a2 + c2 + k2 + jb2 - bendrate*6); // 네번째 절곡라인

						  ctx.closePath();			
						

						// side우측 치수문자 화면출력
						  ctx.beginPath();
						  ctx.strokeStyle = 'blue';
						  ctx.setLineDash([0, 0]);
						  var side_right_height = mh2 + max(rh1,rh2);
						  ctx.font = '25px serif';
						  ctx.strokeText('( Side(우) RH2 뒷날개쪽 Height ) ' + rh2 , startX + side_right_height/2 - 100, startY + side_right_width +30);   // rh2 출력
						  ctx.strokeText('( Side(우) RH1 Height ) ' + rh1 , startX + side_right_height/2 - 100, startY - 30);   // rh1 출력
						  ctx.strokeText('( Side(우) 전체 Height ) ' + side_right_height , startX + side_right_height/2 - 100, startY - 60);   // 전체 height 출력
							 
						  ctx.font = '20px serif';
						  var p_txt=side_rightwing - bendrate*2;
						  ctx.strokeText('( 뒷날개 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + k2 +jb2 + c2 + a2 + side_rightwing/2 - bendrate*2 + 10);   // 첫번째 절곡 날개치수 출력
						  if(k2>0)
							{
							 var p_txt=k2 - bendrate*2;		
							 ctx.strokeText('( K2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + jb2 + c2 + a2 +  k2/2 - bendrate*2 - 10);   // 두번째 절곡 날개치수 출력
							}
								if(k2>0)
									var p_txt=jb2 - bendrate*1;
								 else
								   var p_txt=jb2 - bendrate*3;
						   
							 ctx.strokeText('( JB2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + a2 + c2 + jb2/2 - bendrate*2 - 20);   // 세번째 절곡 날개치수 출력  	  

							 var p_txt = c2 - bendrate*2;	
							 if(c2>0)	 
								ctx.strokeText('( C2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY  + a2 + c2/2 - bendrate*2 );   // 네번째 절곡 날개치수 출력
							 var p_txt = a2 - bendrate;	
							 if(a2>0)	 
								ctx.strokeText('( A2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY  - bendrate );   // 5째 절곡 날개치수 출력	 
							 if(mh1>0) {
								ctx.strokeText('( MH2 ) ' + mh2, startX + mh2/2  - 20, startY +a2 + c2 + 45);   // 막판과 만나는 돌출부위	 
							  if(b1>0)		
								  ctx.strokeText('( 막판Gap 돌출부위 ) ' + b2, startX + mh2  + 5, startY +a2 + c2 + b2 + 10);   // 막판과 만나는 돌출부위	 
							 }
						  						   
}		
								
								
								
							
					   
					

function line(a,b)
{
   var canvas = document.getElementById('canvas_side');
   var ctx = canvas.getContext('2d');
   ctx.lineTo(a  , b); 
   ctx.stroke();
}

function max(a, b)
{
	if(a>b)
		 return a;
	 else 
		 return b;
}


















































































// 멍텅구리 실측치 데이터 상판 그려주기  
function load_normaljamb() {
	        var new_draw = $("#new_draw").val();   // 제작치수 draw 체크여부 확인
			
            var xscale=$("#Xscale option:selected").val();
			var scale;
			// alert(xscale);
			if(xscale=='none') scale=1;
			if(xscale=='2x1') scale=0.5;
			if(xscale=='3x1') scale=0.33;
			if(xscale=='5x1') scale=0.2;
			var upper_pop;
			
	        var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');
			
			var pos = $('#col1').val();// 몇번째 데이터인지 불러온다.
		   //  alert(pos);
			
			var startX = 200;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
			var startY = 80;	
          // 실측서를 먼저 화면에 그려준다.
		    var title1 = $('#j_row1 tr:eq(' + pos + ')>td:eq(1)').html();			
			
		    var title2 = $('#j_row1 tr:eq(' + pos + ')>td:eq(2)').html();
			
		    var u = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(3)').html());
		    var g = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(4)').html());			
		
		    var mh1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(5)').html());
		    var mh2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(6)').html());
		    var jd1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(7)').html());
		    var jd2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(8)').html());
		    var op1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(9)').html());
		    var op2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(10)').html());
		    var r = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(11)').html());
		    var k1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(12)').html());
		    var k2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(13)').html());
		    var upper_wing = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(14)').html());
		    var jb1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(15)').html());
		    var jb2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(16)').html());
		    var c1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(17)').html());
		    var c2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(18)').html());
		    var a1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(19)').html());
		    var a2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(20)').html());
		    var b1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(21)').html());
		    var b2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(22)').html());
		    var side_leftwing = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(23)').html());
		    var side_rightwing = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(24)').html());
		    var lh1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(25)').html());
		    var lh2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(26)').html());
		    var rh1 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(27)').html());
		    var rh2 = Number($('#j_row1 tr:eq(' + pos + ')>td:eq(28)').html());
			
			// 연신율
	        var bendrate=Number($("#sel3 option:selected").val());
			var bend_level=0; // 와이드쟘 쫄대타입이면 4회 밴딩			level로 벤딩 회수 산출

	        var h1=c1;   // 상판의 c1부위를 다른이름으로 지정, 막판에 귀돌이 없는 부분을 표현하기 위함.
            var h2=c2;			
			 
			 if(k2<1) 
				  k2=k1;  
			 if(jb2<1) 
				  jb2=jb1;			
			 if(jd2<1) 
				  jd2=jd1;		

		if(b1<=0) 
			b1=g-2;	
		if(b2<=0) 
			b2=g-2;
      if(u<1)
		{
			h1=0;h2=0;
		}		
	        var width = r + h1+h2;			 
			
			if(u>0) bend_level++;
			if(g>0) bend_level += 2;
			if(jd1>0) bend_level += 2;
			if(upper_wing>0) bend_level++;
			
	        var height = u + g  + jd1 + upper_wing	- (bendrate*bend_level) + k1 ;
			
			 $('#title').text(title1 + '호기 ' + title2 + '층' + ' (' + width + ' x ' + height + ' mm)    실측쟘 : 검정색라인');		 			 
					
             ctx.fillStyle = "rgba(255, 255, 255,1 )";
             ctx.clearRect (0, 0, 3200, 1200);				   
			 
	 var bend_level=1;  
	   // 와이드쟘 실측치 상판 돌출부위 산출
	   if(u>1) {
		   if(bendrate==0.75)
		       upper_pop = 3.25;
						else
								upper_pop = 3.4;			  
	            }
				 else
					 upper_pop=0;
			  
	                ctx.beginPath();
					ctx.setLineDash([0, 0]);
			        ctx.strokeStyle = '#000';						  
                    ctx.moveTo(startX, startY);
  			        ctx.lineTo(startX + width, startY);                             // width 
                    ctx.stroke();		
				 if(g>0) {	  // g값이 0보다 클때   멍텅구리는 g값이 먼저다.. 돌출 상단부위
			        ctx.lineTo(startX + width, startY + g);                           // u
                    ctx.stroke();			
			        ctx.lineTo(startX + width, startY + g - bendrate*(bend_level));  // 상부 돌출부위
                    ctx.stroke();												
					bend_level += 2;
				 }	
			        ctx.lineTo(startX + width , startY + g + u + upper_pop - bendrate*(bend_level));  // 상판 돌출부위
                    ctx.stroke();					 
			        ctx.lineTo(startX + width - h2, startY + g + u + upper_pop - bendrate*(bend_level));  // 상판 돌출부위
                    ctx.stroke();						
					bend_level += 2;
				 

			    var cal = (r - op1)/2	; 		
			        ctx.lineTo(startX + width - h2 - cal, startY + u + g + jd1 - bendrate * bend_level);  // jd까지 오른쪽 그리기
                    ctx.stroke();		
				if(k1>0) // k1값이 있으면 늘어난치수 있음
				{
					bend_level ++;										 
			        ctx.lineTo(startX + width - h2 - cal, startY + u + g + jd1 + k1- bendrate * bend_level);  // k1까지 오른쪽 그리기
                    ctx.stroke();	
				}					
					bend_level ++;										 
			        ctx.lineTo(startX + width - h2 - cal, startY + u + g + jd1 + k1 + upper_wing - bendrate * bend_level);  // 날개
                    ctx.stroke();							
			        ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + jd1 + k1 + upper_wing - bendrate * bend_level);  // 오픈그리기
                    ctx.stroke();		
					bend_level --;
			        ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + jd1 + k1 -  bendrate * bend_level);  //jd까지 왼쪽 상판 날개
                    ctx.stroke();		
				if(k1>0) // k2값이 있으면 늘어난치수 있음
				{
			        ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + jd1 - bendrate * bend_level);  // k1까지 오른쪽 그리기
                    ctx.stroke();	
				}	
				
				    bend_level -= 2;
			        ctx.lineTo(startX + h1 , startY + u + g + upper_pop - bendrate * bend_level);  // 왼쪽jd영역
                    ctx.stroke();	

				    bend_level -= 2;
			        ctx.lineTo(startX + h1, startY + u + g + upper_pop - bendrate*bend_level);  
                    ctx.stroke();
					ctx.lineTo(startX , startY + u + g + upper_pop - bendrate*bend_level);  //				
                    ctx.stroke();						
					ctx.lineTo(startX, startY);  //				
                    ctx.stroke();						
					// 각도구하기  절곡 회수 구하기
					bend_level=0;
				 if(g>0)
                      bend_level += 2;
				 if(u>0)
                      bend_level += 2;				  
				  
					var Angle_left = getAngle(startX + width - h2, startY + u + g + mh1 - bendrate * bend_level,   startX + width - h2 - cal, startY + u + g + mh1 +jd1 - bendrate * 7);
					Angle_left=Angle_left.toFixed(1);
					var Angle_right = getAngle(startX + h1 , startY + u + g + mh1 - bendrate * bend_level,     startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 -  bendrate * 7);
					Angle_right=Angle_right.toFixed(1);
					
					$("#left_angle").val(Angle_left);
					$("#right_angle").val(Angle_right);

 // 상판 절곡선을 그린다
  
  if(new_draw!='1') {
  var offset=1;
  ctx.strokeStyle = '#09f';
  ctx.beginPath();
  ctx.setLineDash([70, 10]);
  ctx.lineDashOffset = -offset;
  bend_level=1;
     
  if(g>1)   {
  ctx.moveTo(startX, startY+g-bendrate*bend_level);  //   
  ctx.lineTo(startX + width, startY+g-bendrate*bend_level);       // 첫번째 절곡라인   
  ctx.stroke();  
  bend_level += 2;
      }
  if(u>1)   {	  
           ctx.moveTo(startX , startY + u + g - bendrate*bend_level);           // 두번째 절곡라인     
		   ctx.lineTo(startX + width , startY + u + g - bendrate*bend_level);
		   ctx.stroke();	
           bend_level += 2;		   
		}

   ctx.moveTo(startX + width - h2 - cal, startY + u + g + jd1 + k1- bendrate * bend_level); // 세번째 절곡라인     
   ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + k1 + jd1 -  bendrate * bend_level); 
   ctx.stroke();        
   ctx.closePath();	

// 치수문자 화면출력
  ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
  ctx.font = '20px serif';
  var p_g=g-bendrate;
  if(u>1 && g>1)
         var p_u=u-bendrate*2;
	    else
	     var p_u=u-bendrate*1;
  
  ctx.strokeText('( 전체폭 ) ' + width, (startX + width)/2, startY - 20);   // 전체폭 출력
  ctx.strokeText('( R ) ' + r, (startX + width)/2, startY + g + u + 30);   // r치수 출력
  if(g>1)
     ctx.strokeText('( G ) ' + p_g, startX+ width +50, startY + u - (u/2) );   // g치수 출력
  if(u>1)
     ctx.strokeText('( U ) ' + p_u, startX+ width +50, startY + u + g - (g/2) );   // u치수 출력
 if(h1>1) 
     ctx.strokeText('( H2 ) ' + h2, startX+ width -h2 , startY + u + g + 20 );   // h2치수 출력
 if(h2>1)
     ctx.strokeText('( H1 ) ' + h1, startX+ +h1 -80 , startY + u + g + 20 );   // h1치수 출력
  var p_upper_pop=upper_pop; 
 if(u>1)   
     ctx.strokeText('(상판쫄대돌출) ' + p_upper_pop, startX+ width -230, startY + u + g + upper_pop*5 );   // 상판돌출 치수 출력
 if(g>1) p_mh=mh1-bendrate*2;
    else
	 	p_mh=mh1-bendrate*1;
	
     if(mh1>0) 
			ctx.strokeText('( MH ) ' + p_mh, startX+ width, startY + u + g - (g/2) + mh1/2 );   // mh 치수 출력 
  p_jd=jd1-bendrate*2;
  ctx.strokeText('( JD ) ' + p_jd, startX+ width, startY + u + g - (g/2) + +mh1 + jd1/2 );   // jd 치수 출력   
  p_upper_wing=upper_wing - bendrate*1;
  ctx.strokeText('( 상판날개 ) ' + p_upper_wing, startX+ width, startY + u + g + mh1 + jd1 + k1 + upper_wing/2 );   // 상판날개 치수 출력    
				if(k1>0) // k2값이 있으면 늘어난치수 있음
				{
			          ctx.strokeText('( K2 ) ' + k2, startX+ width, startY + u + g  +mh1 + jd1 + k1/2 );   // k1 치수 출력    
                    ctx.stroke();	
				}	  
  Angle_right = 90 - Angle_right;
  Angle_right=Angle_right.toFixed(1);
  ctx.strokeText('각도 ' + Angle_right + ' ˚', startX+ width-150, startY + u + g - (g/2) + +mh1 + jd1/2 );   // 각도 출력    
  ctx.strokeText('각도 ' + Angle_left + ' ˚', startX+ h1 +50, startY + u + g - (g/2) + +mh1 + jd1/2 );   // 좌측 각도 출력    
  ctx.strokeText('( OP ) ' + op1, (startX + width)/2, startY + height + 30);   // op 치수 출력  
  ctx.strokeText('( 전체높이 ) ' + height, startX-150 , startY + u + g - (g/2) + mh1/2 + 100 );   // 전체높이 치수 출력 
  ctx.closePath();	   
  
  }
 //상판 단면도 그리기 ***********************************************************************************************************************
   ctx.beginPath();
   ctx.moveTo(startX + width + 300, startY );   // 300거리 띄워서 시작점 잡기
  if(u>1) {
   ctx.lineTo(startX + width + 300 + u, startY ); // u값 그리기
   ctx.stroke();  
  }
  if(g>1) {
   ctx.lineTo(startX + width + 300 + u , startY + g); // g값 그리기
   ctx.stroke();    
  }

   ctx.moveTo(startX + width + 300, startY );   // 300거리 띄워서 시작점 잡기
   ctx.lineTo(startX + width + 300 , startY + jd1); // jd값 그리기
   ctx.stroke();       
   if(k2>0)
   {
     ctx.lineTo(startX + width + 300, startY + jd1 + k2); // k2값 그리기
     ctx.stroke();       
     ctx.moveTo(startX + width + 300, startY + jd1 ); // k2값 그리기	 
     ctx.lineTo(startX + width + 300 + 30 , startY + jd1); // k2값 그리기
     ctx.stroke();       	 
   }
   ctx.moveTo(startX + width + 300, startY + jd1 + k2); // k2값 그리기	    
   ctx.lineTo(startX + width + 300 + upper_wing , startY + jd1 + k2 ); // 상판 날개값 그리기
   ctx.stroke();          
   
if(new_draw!='1') { 
   if(u>1)
		ctx.strokeText('( U ) ' + u , startX + 280 + width, startY - 20 );   // u치수 출력
   if(g>1)	
		ctx.strokeText('( G ) ' + g , startX + 320 + width + 30, startY + g + 15);   // g치수 출력

   ctx.strokeText('( JD ) ' + jd1 , startX + 250 + width +80 , startY + jd1/2 + 25);   // jd치수 출력
   if(k2>0)
   {
     ctx.strokeText('( k2 ) ' + k2 , startX + 250 + width + 80 , startY + jd1 - 25);   // k2치수 출력
     ctx.stroke();       
   }   
   ctx.strokeText('( 상판날개 ) ' + upper_wing , startX + 250 + width + upper_wing + 50 , startY + jd1 + k1  );   // 상판 날개
}

   // side 그리기 그리기 ******
   // side 그리기 그리기 ******
   
   
   // side 좌측 기둥 그리기 ******

	        var canvas = document.getElementById('canvas_side');
            var ctx = canvas.getContext('2d');

             ctx.fillStyle = "rgba(255, 255, 255,1 )";
             ctx.clearRect (0, 0, 4000, 1600);	
			
		    var scale_lh1 =lh1 * scale;
		    var scale_lh2 =lh2 * scale;			
			var scale_rh1 = rh1 * scale ;
			var scale_rh2 = rh2 * scale ;	
			
	var max_height=0;
     	if(lh1>lh2)
				 max_height=lh1;
			    else
					 max_height=lh2;				 
	var side_left_height = mh1+max_height;				 
	var side_left_width = a1 + c1 + jb1 + side_leftwing;
	
			var startX = 400;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
			var startY = 80;	

 // side 좌측 단면도 그리기
   ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
   var xpos=startX-250;
   var ypos=startY + side_leftwing  ;  //첫번째 절곡라인
   var side_left_width=0;
   ctx.moveTo(xpos, ypos); // 300거리 띄워서 시작점 잡기
  
   ctx.lineTo(xpos + side_leftwing , ypos ); // 좌측날개 그리기
   ctx.stroke();
   side_left_width += side_leftwing;
  if(k1>0)  // k1값이 있을때 그려주기
  {	  
   ctx.lineTo(xpos + side_leftwing, ypos + k1 ); // k1값 그리기
   ctx.stroke();
   side_left_width += k1;
  }
   ypos=startY  + k1 ;  //
   Angle_left=90-Angle_left;
   var sinA=Math.sin(Angle_left*Math.PI/180) * jb1;
   var cosA=Math.cos(Angle_left*Math.PI/180) * jb1;
 
   ctx.lineTo(xpos   + side_leftwing - Math.abs(cosA.toFixed(1)) , ypos  + Math.abs(sinA.toFixed(1)) ); // jb값 그리기
   ctx.stroke();   
   side_left_width += jb1;       
   ctx.lineTo(xpos  + side_leftwing  - Math.abs(cosA.toFixed(1)) -c1 , ypos  + Math.abs(sinA.toFixed(1)) ); // c1값 그리기
   ctx.stroke();  
   side_left_width += c1;       
   ctx.lineTo(xpos  + side_leftwing  - Math.abs(cosA.toFixed(1)) -c1 , ypos  + Math.abs(sinA.toFixed(1)) -a1); // a1값 그리기   
   ctx.stroke();   
   side_left_width += a1;          
   ctx.closePath();	   

  if(new_draw!='1') {
  // side 좌측단면도 치수 써주기 
   ctx.font = '20px serif';
   sectionstart=startX-250;
   ctx.strokeText('뒷날개 ' + side_leftwing , sectionstart-50, startY);   // 날개치수 출력
   if(k1>0)    
		ctx.strokeText('( K ) ' + k1 , sectionstart+side_leftwing*1.5, startY+k1/2+20);   // k1치수 출력   
	
   ctx.strokeText('( JB ) ' + jb1 , sectionstart + side_leftwing*1.5, startY+k1+jb1/2);   // jb치수 출력  
   if(c1>0)    
		ctx.strokeText('( C1 ) ' + c1 ,  sectionstart-50, startY + k1 + jb1 + 40);   // c1치수 출력   
   if(a1>0) 
		ctx.strokeText('( A1 ) ' + a1 ,  sectionstart-130, startY + jb1 + k1 -20);   // a1치수 출력     
  }   

// side 좌측 기둥 그리기 (본판) 
if(mh1>1 && b1<1)
    {
	  b1=10; //가상치로 10을 준다. 보통 10~ 15미리 돌출부위
	  b2=10;
	}
  

        ctx.beginPath();
		ctx.moveTo(startX, startY + side_left_width);
		line(startX + mh1, startY + side_left_width);   
		line(startX + mh1 + lh1, startY + side_left_width);   
		line(startX + mh1 + lh1, startY + side_left_width - a1 - bendrate);   
		line(startX + mh1 + lh1, startY + side_left_width - a1 - c1 - bendrate*3);   
		line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - bendrate*4);   
		line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - k1 - bendrate*6);   
		line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - k1 - side_leftwing - bendrate*8);   
		line(startX + mh1, startY + side_left_width - a1 - c1 - jb1 - k1 - side_leftwing - bendrate*8);   
		line(startX + mh1, startY + side_left_width - a1 - c1 - b1 - bendrate*4);   
		line(startX , startY + side_left_width - a1 - c1 - b1 - bendrate*4);   
		line(startX , startY + side_left_width); 
        ctx.closePath();			

// side 좌측 절곡선을 그린다.
  if(new_draw!='1') {	  
  var offset=1;
  ctx.strokeStyle = '#09f';
  ctx.beginPath();
  ctx.setLineDash([70, 10]);
  ctx.lineDashOffset = -offset;
 
  ctx.moveTo( startX + mh1, startY + side_leftwing - bendrate*2 );     // 위에서 첫번째
  line(startX + mh1 + lh2, startY + side_leftwing - bendrate*2);       // 첫번째 절곡라인

  if(k1>0) {
  ctx.moveTo(startX + mh1 , startY + side_leftwing + k1 - bendrate*4);  	  
  line(startX + mh1 + lh2, startY + side_leftwing + k1 - bendrate*4); // 두번째 절곡라인 (9도만 접는부위는 안함)
  }
  ctx.moveTo(startX , startY + side_leftwing + k1 + jb1 - bendrate*5);  
  line(startX + mh1 + lh1, startY + side_leftwing + k1 + jb1 - bendrate*5); // 세번째 절곡라인 (9도만 접는부위는 안함)
  ctx.moveTo(startX , startY + side_leftwing + k1 + jb1 + c1 - bendrate*7);  
     line(startX + mh1 + lh1, startY + side_leftwing + k1 + jb1 + c1 - bendrate*7); // 네번째 절곡라인 

  ctx.closePath();			
  }


// side 좌측 치수문자 화면출력
  ctx.beginPath();
  var side_left_height = mh1 + max(lh1,lh2);
  if(new_draw!='1') {
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
  ctx.font = '25px serif';
  ctx.strokeText('( Side(좌) LH2 뒷날개쪽 Height ) ' + lh2 , startX + side_left_height/2 - 100, startY-25);   // lh2 출력
  ctx.strokeText('( Side(좌) LH1 Height ) ' + lh1 , startX + side_left_height/2 - 100, startY + side_left_width +30);   // lh1 출력
  ctx.strokeText('( Side(좌) 전체 Height ) ' + side_left_height , startX + side_left_height/2 - 100, startY + side_left_width +60);   // 전체 height 출력
     
  ctx.font = '20px serif';
  var p_txt=side_leftwing - bendrate*2;
  ctx.strokeText('( 뒷날개 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing - bendrate*2 - 20);   // 첫번째 절곡 날개치수 출력
  if(k1>0)
    {
     var p_txt=k1 - bendrate*2;		
     ctx.strokeText('( K1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 - bendrate*2 - 20);   // 두번째 절곡 날개치수 출력
	}
		if(k1>0)
			var p_txt=jb1 - bendrate*1;
		 else
		   var p_txt=jb1 - bendrate*3;
   
     ctx.strokeText('( JB1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 + jb1/2 - bendrate*2 - 20);   // 세번째 절곡 날개치수 출력  	  

     var p_txt = c1 - bendrate*2;		
     ctx.strokeText('( C1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 +jb1 + c1/2 - bendrate*2 );   // 네번째 절곡 날개치수 출력
     var p_txt = a1 - bendrate;		
     ctx.strokeText('( A1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 +jb1 + c1 + a1/2 - bendrate*2 );   // 5째 절곡 날개치수 출력	 
	 if(mh1>0)	{
		ctx.strokeText('( MH1 ) ' + mh1, startX + mh1/2 -20, startY + side_leftwing + k1 +jb1 - 40);   // 막판과 만나는 돌출부위
		ctx.strokeText('( 막판Gap 돌출부위 ) ' + b1, startX + mh1  + 5, startY + side_leftwing + k1 +jb1 - 25);   // 막판과 만나는 돌출부위	 
	 }
  }	 
	 
// 우측 기둥쪽 화면에 그리기 ****************************************************
// 우측 기둥쪽 화면에 그리기 ****************************************************	 

	var max_height=0;
     	if(rh1>rh2)
				 max_height=rh1;
			    else
					 max_height=rh2;				 
	var side_right_height = mh2+max_height;				 
	var side_right_width = a2 + c2 + jb2 + side_rightwing;
	
			var startX = 400;  // 처음 선을 그릴때 우,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
			var startY = side_left_width * 2 + 250;	

 // 실측치 side 우측 단면도 그리기
  ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
   var xpos=startX - 250;
   var ypos=startY;  //첫번째 절곡라인
   var side_right_width=0;
   ctx.moveTo(xpos, ypos); // 시작점 잡기
  
   ctx.lineTo(xpos + side_rightwing , ypos ); // 우측날개 그리기
   ctx.stroke();
   side_right_width += side_rightwing;
  if(k2>0)  // k2값이 있을때 그려주기
  {	  
   ctx.lineTo(xpos + side_rightwing, ypos - k2 ); // k2값 그리기
   ctx.stroke();
   side_right_width += k2;
  }
   ypos=startY - k2 ;  //
  
if(new_draw!='1') {  
   Angle_right=90- Angle_right;
   var sinA=Math.sin(Angle_right*Math.PI/180) * jb2;
   var cosA=Math.cos(Angle_right*Math.PI/180) * jb2;
}
 else
 {
   var sinA=Math.sin(Angle_right*Math.PI/180) * jb2;
   var cosA=Math.cos(Angle_right*Math.PI/180) * jb2;
 }
					 
			   
   ctx.lineTo(xpos + side_rightwing - Math.abs(cosA.toFixed(1)) , ypos - Math.abs(sinA.toFixed(1)) ); // jb값 그리기
   ctx.stroke();   
   side_right_width += jb2;       
   ctx.lineTo(xpos + side_rightwing  - Math.abs(cosA.toFixed(1)) - c2 , ypos - Math.abs(sinA.toFixed(1)) ); // c2값 그리기
   ctx.stroke();  
   side_right_width += c2;       
   ctx.lineTo(xpos + side_rightwing  - Math.abs(cosA.toFixed(1)) - c2 , ypos - Math.abs(sinA.toFixed(1)) + a2); // a2값 그리기   
   ctx.stroke();   
   
  
   side_right_width += a2;          
   ctx.closePath();	   

  // side우측 단면도 치수 써주기 
 sectionstart=startX-250;  
 if(new_draw!='1') {  
   ctx.font = '20px serif';
   ctx.strokeText('뒷날개 ' + side_rightwing , sectionstart-50, startY+ 20);   // 날개치수 출력
   if(k2>0)
		ctx.strokeText('( K ) ' + k2 , sectionstart+side_rightwing*1.5, startY -k2/2  );   // k2치수 출력   
   ctx.strokeText('( JB ) ' + jb2 , sectionstart + side_rightwing*1.5, startY -k2 - jb2/2);   // jb치수 출력   
   if(c2>0)
		ctx.strokeText('( C2 ) ' + c2 ,  sectionstart-40, startY - k2 - jb2 - 10);   // c2치수 출력   
   if(a2>0)
		ctx.strokeText('( A2 ) ' + a2 ,  sectionstart-130, startY - jb2 - k2 + 30);   // a2치수 출력     
  }  

// side우측 그리기 (본판) 
	    var startY = side_left_width * 1 + 300;	 // startY값 재설정

        ctx.beginPath();
		ctx.moveTo(startX, startY );
		line(startX + mh2, startY );   
		line(startX + mh2 + rh1, startY );   
		line(startX + mh2 + rh1, startY  + a2 - bendrate);   
		line(startX + mh2 + rh1, startY  + a2 + c2 - bendrate*3);   
		line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + bendrate*4);   
		line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + k2 - bendrate*6);   
		line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + k2 + side_rightwing - bendrate*8);   
		line(startX + mh2, startY + a2 + c2 + jb2 + k2 + side_rightwing - bendrate*8);   
		line(startX + mh2, startY + a2 + c2 + b1 - bendrate*4);   
		line(startX , startY + a2 + c2 + b1 - bendrate*4);   
		line(startX , startY); 
        ctx.closePath();			

// side우측 절곡선을 그린다.
  if(new_draw!='1') {
  var offset=1;
  ctx.strokeStyle = '#09f';
  ctx.beginPath();
  ctx.setLineDash([70, 10]);
  ctx.lineDashOffset = -offset;
  if(a2>0) {
  ctx.moveTo(startX , startY + a2 - bendrate*1);  
  line(startX + mh2 + rh1, startY + a2 - bendrate*1); // 첫번째 절곡라인   
  }  
  if(c2>0) {
    ctx.moveTo(startX , startY + a2 +c2 - bendrate*3);  
    line(startX + mh2 + rh1, startY + a2 + c2 - bendrate*3); // 두번째 절곡라인    
  }

  if(k2>0) {
  ctx.moveTo(startX + mh2 , startY + a2 + c2 + jb2 - bendrate*4);  	  
  line(startX + mh2 + rh2, startY + a2 + c2  + jb2 - bendrate*4); // 세번째 절곡라인 (9도만 접는부위는 안함)
  }
  ctx.moveTo(startX + mh2, startY + a2 + c2 + k2 + jb2 - bendrate*6);  
  line(startX + mh2 + rh2, startY + a2 + c2 + k2 + jb2 - bendrate*6); // 네번째 절곡라인

  ctx.closePath();			
 }

// side우측 치수문자 화면출력
  if(new_draw!='1') {
  ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
  var side_right_height = mh2 + max(rh1,rh2);
  ctx.font = '25px serif';
  ctx.strokeText('( Side(우) RH2 뒷날개쪽 Height ) ' + rh2 , startX + side_right_height/2 - 100, startY + side_right_width +30);   // rh2 출력
  ctx.strokeText('( Side(우) RH1 Height ) ' + rh1 , startX + side_right_height/2 - 100, startY - 30);   // rh1 출력
  ctx.strokeText('( Side(우) 전체 Height ) ' + side_right_height , startX + side_right_height/2 - 100, startY - 60);   // 전체 height 출력
     
  ctx.font = '20px serif';
  var p_txt=side_rightwing - bendrate*2;
  ctx.strokeText('( 뒷날개 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + k2 +jb2 + c2 + a2 + side_rightwing/2 - bendrate*2 + 10);   // 첫번째 절곡 날개치수 출력
  if(k2>0)
    {
     var p_txt=k2 - bendrate*2;		
     ctx.strokeText('( K2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + jb2 + c2 + a2 +  k2/2 - bendrate*2 - 10);   // 두번째 절곡 날개치수 출력
	}
		if(k2>0)
			var p_txt=jb2 - bendrate*1;
		 else
		   var p_txt=jb2 - bendrate*3;
   
     ctx.strokeText('( JB2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + a2 + c2 + jb2/2 - bendrate*2 - 20);   // 세번째 절곡 날개치수 출력  	  

     var p_txt = c2 - bendrate*2;	
     if(c2>0)	 
		ctx.strokeText('( C2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY  + a2 + c2/2 - bendrate*2 );   // 네번째 절곡 날개치수 출력
     var p_txt = a2 - bendrate;	
     if(a2>0)	 
		ctx.strokeText('( A2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY  - bendrate );   // 5째 절곡 날개치수 출력	 
     if(mh1>0) {
		ctx.strokeText('( MH2 ) ' + mh2, startX + mh2/2  - 20, startY +a2 + c2 + 45);   // 막판과 만나는 돌출부위	 
      if(b1>1)		
		  ctx.strokeText('( 막판Gap 돌출부위 ) ' + b2, startX + mh2  + 5, startY +a2 + c2 + 25);   // 막판과 만나는 돌출부위	 
	 }
  }	 

   
}

//*****************************************************************************************************************************************
// 제작치수 멍텅구리 상판 그려주기  
function load_makedraw_normaljamb() {
						
								var xscale=$("#Xscale option:selected").val();
								var scale;
								// alert(xscale);
								if(xscale=='none') scale=1;
								if(xscale=='2x1') scale=0.5;
								if(xscale=='3x1') scale=0.33;
								if(xscale=='5x1') scale=0.2;
								var upper_pop;
								
								var canvas = document.getElementById('canvas');
								var ctx = canvas.getContext('2d');
								
								var pos = $('#col1').val();// 몇번째 데이터인지 불러온다.
							   //  alert(pos);
								

								
								// 제작치수 반영치 설정에서 c값의 차이를 불러온다.
								var c_gap = Number($('#setdata tr:eq(1)>td:eq(5)').html());						
								var jb_gap = Number($('#setdata tr:eq(1)>td:eq(4)').html());														
								var a_gap = Number($('#setdata tr:eq(1)>td:eq(6)').html());														


								var startX = 200 - c_gap;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
								var startY = 80;									
								// alert(c_gap);
								
								
							  // 실측서를 먼저 화면에 그려준다.
								var title1 = $('#spreadsheet tr:eq(' + pos + ')>td:eq(1)').html();			
								
								var title2 = $('#spreadsheet tr:eq(' + pos + ')>td:eq(2)').html();
								
								var u = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(3)').html());
								var g = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(4)').html());			
							
								var mh1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(5)').html());
								var mh2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(6)').html());
								var jd1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(7)').html());
								var jd2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(8)').html());
								var op1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(9)').html());
								var op2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(10)').html());
								var r = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(11)').html());
								var k1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(12)').html());
								var k2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(13)').html());
								var upper_wing = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(14)').html());
								var jb1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(15)').html());
								var jb2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(16)').html());
								var c1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(17)').html());
								var c2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(18)').html());
								var a1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(19)').html());
								var a2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(20)').html());
								var b1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(21)').html());
								var b2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(22)').html());
								var side_leftwing = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(23)').html());
								var side_rightwing = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(24)').html());
								var lh1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(25)').html());
								var lh2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(26)').html());
								var rh1 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(27)').html());
								var rh2 = Number($('#spreadsheet tr:eq(' + pos + ')>td:eq(28)').html());
														

									// 연신율
									var bendrate=Number($("#sel3 option:selected").val());
									var bend_level=0; // 와이드쟘 쫄대타입이면 4회 밴딩			level로 벤딩 회수 산출

									var h1=c1;   // 상판의 c1부위를 다른이름으로 지정, 막판에 귀돌이 없는 부분을 표현하기 위함.
									var h2=c2;			
									 
									 if(k2<1) 
										  k2=k1;
									 if(jb2<1) 
										  jb2=jb1;			
									 if(jd2<1) 
										  jd2=jd1;		

								if(b1<=0) 
									b1=g-2;	
								if(b2<=0) 
									b2=g-2;
								
								 if(u<1)
		{
			h1=0;h2=0;
		}		
	        var width = r + h1+h2;			 
			
			if(u>0) bend_level++;
			if(g>0) bend_level += 2;
			if(jd1>0) bend_level += 2;
			if(upper_wing>0) bend_level++;
			
	        var height = u + g  + jd1 + upper_wing	- (bendrate*bend_level) + k1 ;
			
			 $('#title').text(title1 + '호기 ' + title2 + '층' + ' (' + width + ' x ' + height + ' mm)    실측쟘 : 검정색라인');		 			 
	   
			 
	 var bend_level=1;  
	   // 와이드쟘 실측치 상판 돌출부위 산출
	   if(u>1) {
		   if(bendrate==0.75)
		       upper_pop = 3.25;
						else
								upper_pop = 3.4;			  
	            }
				 else
					 upper_pop=0;
									  
											ctx.beginPath();
											ctx.setLineDash([0, 0]);
											ctx.strokeStyle = 'red';						  
											ctx.moveTo(startX, startY);
											ctx.lineTo(startX + width, startY);                             // width 
											ctx.stroke();		
										 if(g>0) {	  // g값이 0보다 클때   멍텅구리는 g값이 먼저다.. 돌출 상단부위
											ctx.lineTo(startX + width, startY + g);                           // u
											ctx.stroke();			
											ctx.lineTo(startX + width, startY + g - bendrate*(bend_level));  // 상부 돌출부위
											ctx.stroke();												
											bend_level += 2;
										 }	
											ctx.lineTo(startX + width , startY + g + u + upper_pop - bendrate*(bend_level));  // 상판 돌출부위
											ctx.stroke();					 
											ctx.lineTo(startX + width - h2, startY + g + u + upper_pop - bendrate*(bend_level));  // 상판 돌출부위
											ctx.stroke();						
											bend_level += 2;
										 

										var cal = (r - op1)/2	; 		
											ctx.lineTo(startX + width - h2 - cal, startY + u + g + jd1 - bendrate * bend_level);  // jd까지 오른쪽 그리기
											ctx.stroke();		
										if(k1>0) // k1값이 있으면 늘어난치수 있음
										{
											bend_level ++;										 
											ctx.lineTo(startX + width - h2 - cal, startY + u + g + jd1 + k1- bendrate * bend_level);  // k1까지 오른쪽 그리기
											ctx.stroke();	
										}					
											bend_level ++;										 
											ctx.lineTo(startX + width - h2 - cal, startY + u + g + jd1 + k1 + upper_wing - bendrate * bend_level);  // 날개
											ctx.stroke();							
											ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + jd1 + k1 + upper_wing - bendrate * bend_level);  // 오픈그리기
											ctx.stroke();		
											bend_level --;
											ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + jd1 + k1 -  bendrate * bend_level);  //jd까지 왼쪽 상판 날개
											ctx.stroke();		
										if(k1>0) // k2값이 있으면 늘어난치수 있음
										{
											ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + jd1 - bendrate * bend_level);  // k1까지 오른쪽 그리기
											ctx.stroke();	
										}	
										
											bend_level -= 2;
											ctx.lineTo(startX + h1 , startY + u + g + upper_pop - bendrate * bend_level);  // 왼쪽jd영역
											ctx.stroke();	

											bend_level -= 2;
											ctx.lineTo(startX + h1, startY + u + g + upper_pop - bendrate*bend_level);  
											ctx.stroke();
											ctx.lineTo(startX , startY + u + g + upper_pop - bendrate*bend_level);  //				
											ctx.stroke();						
											ctx.lineTo(startX, startY);  //				
											ctx.stroke();						
											// 각도구하기  절곡 회수 구하기
											bend_level=0;
										 if(g>0)
											  bend_level += 2;
										 if(u>0)
											  bend_level += 2;				  
										  
											var Angle_left = getAngle(startX + width - h2, startY + u + g + mh1 - bendrate * bend_level,   startX + width - h2 - cal, startY + u + g + mh1 +jd1 - bendrate * 7);
											Angle_left=Angle_left.toFixed(1);
											var Angle_right = getAngle(startX + h1 , startY + u + g + mh1 - bendrate * bend_level,     startX + width - h2 - cal - op1, startY + u + g + mh1 +jd1 -  bendrate * 7);
											Angle_right=Angle_right.toFixed(1);
											
											$("#left_angle").val(Angle_left);
											$("#right_angle").val(Angle_right);

						 // 상판 절곡선을 그린다
						  
						  var offset=1;
						  ctx.strokeStyle = 'brown';
						  ctx.beginPath();
						  ctx.setLineDash([70, 10]);
						  ctx.lineDashOffset = -offset;
						  bend_level=1;
							 
						  if(g>1)   {
						  ctx.moveTo(startX, startY+g-bendrate*bend_level);  //   
						  ctx.lineTo(startX + width, startY+g-bendrate*bend_level);       // 첫번째 절곡라인   
						  ctx.stroke();  
						  bend_level += 2;
							  }
						  if(u>1)   {	  
								   ctx.moveTo(startX , startY + u + g - bendrate*bend_level);           // 두번째 절곡라인     
								   ctx.lineTo(startX + width , startY + u + g - bendrate*bend_level);
								   ctx.stroke();	
								   bend_level += 2;		   
								}

						   ctx.moveTo(startX + width - h2 - cal, startY + u + g + jd1 + k1- bendrate * bend_level); // 세번째 절곡라인     
						   ctx.lineTo(startX + width - h2 - cal - op1, startY + u + g + k1 + jd1 -  bendrate * bend_level); 
						   ctx.stroke();        
						   ctx.closePath();	

						// 치수문자 화면출력
						  ctx.beginPath();
						  ctx.strokeStyle = 'blue';
						  ctx.setLineDash([0, 0]);
						  ctx.font = '20px serif';
						  var p_g=g-bendrate;
						  if(u>1 && g>1)
								 var p_u=u-bendrate*2;
								else
								 var p_u=u-bendrate*1;
						  
						  ctx.strokeText('( 전체폭 ) ' + width, (startX + width)/2, startY - 20);   // 전체폭 출력
						  ctx.strokeText('( R ) ' + r, (startX + width)/2, startY + g + u + 30);   // r치수 출력
						  if(g>1)
							 ctx.strokeText('( G ) ' + p_g, startX+ width +50, startY + u - (u/2) );   // g치수 출력
						  if(u>1)
							 ctx.strokeText('( U ) ' + p_u, startX+ width +50, startY + u + g - (g/2) );   // u치수 출력
						 if(h1>1) 
							 ctx.strokeText('( H2 ) ' + h2, startX+ width -h2 , startY + u + g + 20 );   // h2치수 출력
						 if(h2>1)
							 ctx.strokeText('( H1 ) ' + h1, startX+ +h1 -80 , startY + u + g + 20 );   // h1치수 출력
						  var p_upper_pop=upper_pop; 
						 if(u>1)   
							 ctx.strokeText('(상판쫄대돌출) ' + p_upper_pop, startX+ width -230, startY + u + g + upper_pop*5 );   // 상판돌출 치수 출력
						 if(g>1) p_mh=mh1-bendrate*2;
							else
								p_mh=mh1-bendrate*1;
							
							 if(mh1>0) 
									ctx.strokeText('( MH ) ' + p_mh, startX+ width, startY + u + g - (g/2) + mh1/2 );   // mh 치수 출력 
						  p_jd=jd1-bendrate*2;
						  ctx.strokeText('( JD ) ' + p_jd, startX+ width, startY + u + g - (g/2) + +mh1 + jd1/2 );   // jd 치수 출력   
						  p_upper_wing=upper_wing - bendrate*1;
						  ctx.strokeText('( 상판날개 ) ' + p_upper_wing, startX+ width, startY + u + g + mh1 + jd1 + k1 + upper_wing/2 );   // 상판날개 치수 출력    
										if(k1>0) // k2값이 있으면 늘어난치수 있음
										{
											  ctx.strokeText('( K2 ) ' + k2, startX+ width, startY + u + g  +mh1 + jd1 + k1/2 );   // k1 치수 출력    
											ctx.stroke();	
										}	  
						  Angle_right = 90 - Angle_right;
						  Angle_right=Angle_right.toFixed(1);
						  ctx.strokeText('각도 ' + Angle_right + ' ˚', startX+ width-150, startY + u + g - (g/2) + +mh1 + jd1/2 );   // 각도 출력    
						  ctx.strokeText('각도 ' + Angle_left + ' ˚', startX+ h1 +50, startY + u + g - (g/2) + +mh1 + jd1/2 );   // 좌측 각도 출력    
						  ctx.strokeText('( OP ) ' + op1, (startX + width)/2, startY + height + 30);   // op 치수 출력  
						  ctx.strokeText('( 전체높이 ) ' + height, startX-150 , startY + u + g - (g/2) + mh1/2 + 100 );   // 전체높이 치수 출력 
						  ctx.closePath();	   
						  
						  
						 //상판 단면도 그리기 ***********************************************************************************************************************
						   ctx.beginPath();
						   ctx.moveTo(startX + width + 300, startY );   // 300거리 띄워서 시작점 잡기
						  if(u>1) {
						   ctx.lineTo(startX + width + 300 + u, startY ); // u값 그리기
						   ctx.stroke();  
						  }
						  if(g>1) {
						   ctx.lineTo(startX + width + 300 + u , startY + g); // g값 그리기
						   ctx.stroke();    
						  }

						   ctx.moveTo(startX + width + 300, startY );   // 300거리 띄워서 시작점 잡기
						   ctx.lineTo(startX + width + 300 , startY + jd1); // jd값 그리기
						   ctx.stroke();       
						   if(k2>0)
						   {
							 ctx.lineTo(startX + width + 300, startY + jd1 + k2); // k2값 그리기
							 ctx.stroke();       
							 ctx.moveTo(startX + width + 300, startY + jd1 ); // k2값 그리기	 
							 ctx.lineTo(startX + width + 300 + 30 , startY + jd1); // k2값 그리기
							 ctx.stroke();       	 
						   }
						   ctx.moveTo(startX + width + 300, startY + jd1 + k2); // k2값 그리기	    
						   ctx.lineTo(startX + width + 300 + upper_wing , startY + jd1 + k2 ); // 상판 날개값 그리기
						   ctx.stroke();          
						   
						   if(u>1)
								ctx.strokeText('( U ) ' + u , startX + 280 + width, startY - 20 );   // u치수 출력
						   if(g>1)	
								ctx.strokeText('( G ) ' + g , startX + 320 + width + 30, startY + g + 15);   // g치수 출력

						   ctx.strokeText('( JD ) ' + jd1 , startX + 250 + width +80 , startY + jd1/2 + 25);   // jd치수 출력
						   if(k2>0)
						   {
							 ctx.strokeText('( k2 ) ' + k2 , startX + 250 + width + 80 , startY + jd1 - 25);   // k2치수 출력
							 ctx.stroke();       
						   }   
						   ctx.strokeText('( 상판날개 ) ' + upper_wing , startX + 250 + width + upper_wing + 50 , startY + jd1 + k1  );   // 상판 날개



						   // side 그리기 그리기 ******
						   // side 그리기 그리기 ******
						   
						   
						   // side 좌측 기둥 그리기 ******

									var canvas = document.getElementById('canvas_side');
									var ctx = canvas.getContext('2d');
									
									var scale_lh1 =lh1 * scale;
									var scale_lh2 =lh2 * scale;			
									var scale_rh1 = rh1 * scale ;
									var scale_rh2 = rh2 * scale ;	
									
							var max_height=0;
								if(lh1>lh2)
										 max_height=lh1;
										else
											 max_height=lh2;				 
							var side_left_height = mh1+max_height;				 
							var side_left_width = a1 + c1 + jb1 + side_leftwing;
							
									var startX = 400;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
									var startY = 80;	

						 // side 좌측 단면도 그리기
						   ctx.beginPath();
						  ctx.strokeStyle = 'red';
						  ctx.setLineDash([0, 0]);
						   var xpos=startX-250;
						   var ypos=startY + side_leftwing  ;  //첫번째 절곡라인
						   var side_left_width=0;
						   ctx.moveTo(xpos, ypos); // 300거리 띄워서 시작점 잡기
						  
						   ctx.lineTo(xpos + side_leftwing , ypos ); // 좌측날개 그리기
						   ctx.stroke();
						   side_left_width += side_leftwing;
						  if(k1>0)  // k1값이 있을때 그려주기
						  {	  
						   ctx.lineTo(xpos + side_leftwing, ypos + k1 ); // k1값 그리기
						   ctx.stroke();
						   side_left_width += k1;
						  }
						   ypos=startY  + k1 ;  //
						   Angle_left=90-Angle_left;
						   var sinA=Math.sin(Angle_left*Math.PI/180) * jb1;
						   var cosA=Math.cos(Angle_left*Math.PI/180) * jb1;
						 
						   ctx.lineTo(xpos   + side_leftwing - Math.abs(cosA.toFixed(1)) , ypos  + Math.abs(sinA.toFixed(1)) ); // jb값 그리기
						   ctx.stroke();   
						   side_left_width += jb1;       
						   ctx.lineTo(xpos  + side_leftwing  - Math.abs(cosA.toFixed(1)) -c1 , ypos  + Math.abs(sinA.toFixed(1)) ); // c1값 그리기
						   ctx.stroke();  
						   side_left_width += c1;       
						   ctx.lineTo(xpos  + side_leftwing  - Math.abs(cosA.toFixed(1)) -c1 , ypos  + Math.abs(sinA.toFixed(1)) -a1); // a1값 그리기   
						   ctx.stroke();   
						   side_left_width += a1;          
						   
						// 돌출부위 산출하기 B값
						   ctx.moveTo(xpos   + side_leftwing + 30, ypos + jd1); // jd값 선 긋기 이동
                           line(xpos   + side_leftwing + 80, ypos + jd1);					
						   b1 = (ypos  + Math.abs(sinA.toFixed(1))) - (ypos + jd1) ;						   
						   b1 = b1.toFixed(1);		
                           b1 = parseInt(b1);						   
						   						   
						   ctx.closePath();	   
						   
						   

						  // side 좌측단면도 치수 써주기 				  
						   ctx.beginPath();
						   ctx.font = '20px serif';
						   ctx.strokeStyle = 'blue';
						   sectionstart=startX-250;
						   ctx.strokeText('뒷날개 ' + side_leftwing , sectionstart-50, startY);   // 날개치수 출력
						   if(k1>0)    
								ctx.strokeText('( K ) ' + k1 , sectionstart+side_leftwing*1.5, startY+k1/2+20);   // k1치수 출력   
							
						   ctx.strokeText('( JB ) ' + jb1 , sectionstart + side_leftwing*1.5, startY+k1+jb1/2);   // jb치수 출력  
						   if(c1>0)    
								ctx.strokeText('( C1 ) ' + c1 ,  sectionstart-50, startY + k1 + jb1 + 40);   // c1치수 출력   
						   if(a1>0) 
								ctx.strokeText('( A1 ) ' + a1 ,  sectionstart-130, startY + jb1 + k1 -20);   // a1치수 출력     
						   if(b1>0)
  						     	ctx.strokeText('( 돌출B1 ) ' + b1 ,  xpos   + side_leftwing + 30, ypos + jd1-30);   // b1치수 출력     				   
	
						// side 좌측 기둥 그리기 (본판) 
						if(mh1>1 && b1<1)
							{
							  b1=10; //가상치로 10을 준다. 보통 10~ 15미리 돌출부위
							  b2=10;
							}
						  

								ctx.beginPath();
								ctx.strokeStyle = 'red';
								ctx.moveTo(startX, startY + side_left_width);
								line(startX + mh1, startY + side_left_width);   
								line(startX + mh1 + lh1, startY + side_left_width);   
								line(startX + mh1 + lh1, startY + side_left_width - a1 - bendrate);   
								line(startX + mh1 + lh1, startY + side_left_width - a1 - c1 - bendrate*3);   
								line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - bendrate*4);   
								line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - k1 - bendrate*6);   
								line(startX + mh1 + lh2, startY + side_left_width - a1 - c1 - jb1 - k1 - side_leftwing - bendrate*8);   
								line(startX + mh1, startY + side_left_width - a1 - c1 - jb1 - k1 - side_leftwing - bendrate*8);   
								line(startX + mh1, startY + side_left_width - a1 - c1 - b1 - bendrate*4);   
								line(startX , startY + side_left_width - a1 - c1 - b1 - bendrate*4);   
								line(startX , startY + side_left_width); 
								ctx.closePath();			

						// side 좌측 절곡선을 그린다.
						  var offset=1;
						  ctx.strokeStyle = 'brown';
						  ctx.beginPath();
						  ctx.setLineDash([70, 10]);
						  ctx.lineDashOffset = -offset;
						 
						  ctx.moveTo( startX + mh1, startY + side_leftwing - bendrate*2 );     // 위에서 첫번째
						  line(startX + mh1 + lh2, startY + side_leftwing - bendrate*2);       // 첫번째 절곡라인

						  if(k1>0) {
						  ctx.moveTo(startX + mh1 , startY + side_leftwing + k1 - bendrate*4);  	  
						  line(startX + mh1 + lh2, startY + side_leftwing + k1 - bendrate*4); // 두번째 절곡라인 (9도만 접는부위는 안함)
						  }
						  ctx.moveTo(startX , startY + side_leftwing + k1 + jb1 - bendrate*5);  
						  line(startX + mh1 + lh1, startY + side_leftwing + k1 + jb1 - bendrate*5); // 세번째 절곡라인 (9도만 접는부위는 안함)
						  ctx.moveTo(startX , startY + side_leftwing + k1 + jb1 + c1 - bendrate*7);  
							 line(startX + mh1 + lh1, startY + side_leftwing + k1 + jb1 + c1 - bendrate*7); // 네번째 절곡라인 

						  ctx.closePath();			


						// side 좌측 치수문자 화면출력
						  ctx.beginPath();
						  var side_left_height = mh1 + max(lh1,lh2);
						  ctx.strokeStyle = 'blue';
						  ctx.setLineDash([0, 0]);
						  ctx.font = '25px serif';
						  ctx.strokeText('( Side(좌) LH2 뒷날개쪽 Height ) ' + lh2 , startX + side_left_height/2 - 100, startY-25);   // lh2 출력
						  ctx.strokeText('( Side(좌) LH1 Height ) ' + lh1 , startX + side_left_height/2 - 100, startY + side_left_width +30);   // lh1 출력
						  ctx.strokeText('( Side(좌) 전체 Height ) ' + side_left_height , startX + side_left_height/2 - 100, startY + side_left_width +60);   // 전체 height 출력
							 
						  ctx.font = '20px serif';
						  var p_txt=side_leftwing - bendrate*2;
						  ctx.strokeText('( 뒷날개 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing - bendrate*2 - 20);   // 첫번째 절곡 날개치수 출력
						  if(k1>0)
							{
							 var p_txt=k1 - bendrate*2;		
							 ctx.strokeText('( K1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 - bendrate*2 - 20);   // 두번째 절곡 날개치수 출력
							}
								if(k1>0)
									var p_txt=jb1 - bendrate*1;
								 else
								   var p_txt=jb1 - bendrate*3;
						   
							 ctx.strokeText('( JB1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 + jb1/2 - bendrate*2 - 20);   // 세번째 절곡 날개치수 출력  	  

							 var p_txt = c1 - bendrate*2;		
							 ctx.strokeText('( C1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 +jb1 + c1/2 - bendrate*2 );   // 네번째 절곡 날개치수 출력
							 var p_txt = a1 - bendrate;		
							 ctx.strokeText('( A1 ) ' + p_txt, startX + mh1 + lh2 + 5, startY + side_leftwing + k1 +jb1 + c1 + a1/2 - bendrate*2 );   // 5째 절곡 날개치수 출력	 
							 if(mh1>0)	{
								ctx.strokeText('( MH1 ) ' + mh1, startX + mh1/2 -20, startY + side_leftwing + k1 +jb1 - 40);   // 막판과 만나는 돌출부위
								ctx.strokeText('( 막판Gap 돌출부위 ) ' + b1, startX + mh1  + 5, startY + side_leftwing + k1 +jb1 - 25);   // 막판과 만나는 돌출부위	 
							 }
							 
						// 우측 기둥쪽 화면에 그리기 ****************************************************
						// 우측 기둥쪽 화면에 그리기 ****************************************************	 

							var max_height=0;
								if(rh1>rh2)
										 max_height=rh1;
										else
											 max_height=rh2;				 
							var side_right_height = mh2+max_height;				 
							var side_right_width = a2 + c2 + jb2 + side_rightwing;
							
									var startX = 400;  // 처음 선을 그릴때 우,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
									var startY = side_left_width * 2 + 250 - 20;	

						 // 실측치 side 우측 단면도 그리기
						  ctx.beginPath();
						  ctx.strokeStyle = 'red';
						  ctx.setLineDash([0, 0]);
						   var xpos=startX - 250;
						   var ypos=startY;  //첫번째 절곡라인
						   var side_right_width=0;
						   ctx.moveTo(xpos, ypos); // 시작점 잡기
						  
						   ctx.lineTo(xpos + side_rightwing , ypos ); // 우측날개 그리기
						   ctx.stroke();
						   side_right_width += side_rightwing;
						  if(k2>0)  // k2값이 있을때 그려주기
						  {	  
						   ctx.lineTo(xpos + side_rightwing, ypos - k2 ); // k2값 그리기
						   ctx.stroke();
						   side_right_width += k2;
						  }
						   ypos=startY - k2 ;  //						  

						   Angle_right=90- Angle_right;
						   var sinA=Math.sin(Angle_right*Math.PI/180) * jb2;
						   var cosA=Math.cos(Angle_right*Math.PI/180) * jb2;
											 
									   
						   ctx.lineTo(xpos + side_rightwing - Math.abs(cosA.toFixed(1)) , ypos - Math.abs(sinA.toFixed(1)) ); // jb값 그리기
						   ctx.stroke();   
						   side_right_width += jb2;       
						   ctx.lineTo(xpos + side_rightwing  - Math.abs(cosA.toFixed(1)) - c2 , ypos - Math.abs(sinA.toFixed(1)) ); // c2값 그리기
						   ctx.stroke();  
						   side_right_width += c2;       
						   ctx.lineTo(xpos + side_rightwing  - Math.abs(cosA.toFixed(1)) - c2 , ypos - Math.abs(sinA.toFixed(1)) + a2); // a2값 그리기   
						   ctx.stroke();   						  
						   side_right_width += a2;          
						// 돌출부위 산출하기 B값
						   ctx.moveTo(xpos   + side_leftwing + 30, ypos - jd2); // jd값 선 긋기 이동
                           line(xpos   + side_leftwing + 80, ypos - jd1);					
						   b2 = (ypos - jd1) - (ypos  - Math.abs(sinA.toFixed(1)))  ;						   
						   b2 = b2.toFixed(1);		
						   b2=parseInt(b2);   // int형으로 바꾸기, 소수점이 있으면 그래프가 산으로 감.
						   ctx.closePath();	   

						  // side우측 단면도 치수 써주기 
						 sectionstart=startX-250;  
						   ctx.strokeStyle = 'blue';
						   ctx.font = '20px serif';
						   ctx.beginPath();
						   ctx.strokeText('뒷날개 ' + side_rightwing , sectionstart-50, startY+ 20);   // 날개치수 출력
						   if(k2>0)
								ctx.strokeText('( K ) ' + k2 , sectionstart+side_rightwing*1.5, startY -k2/2  );   // k2치수 출력   
						   ctx.strokeText('( JB ) ' + jb2 , sectionstart + side_rightwing*1.5, startY -k2 - jb2/2);   // jb치수 출력   
						   if(c2>0)
								ctx.strokeText('( C2 ) ' + c2 ,  sectionstart-40, startY - k2 - jb2 - 10);   // c2치수 출력   
						   if(a2>0)
								ctx.strokeText('( A2 ) ' + a2 ,  sectionstart-130, startY - jb2 - k2 + 30);   // a2치수 출력     
						   if(b2>0)
  						     	ctx.strokeText('( 돌출B2 ) ' + b2 ,   sectionstart+30,  startY  - jd2 );   // b2치수 출력     
						   ctx.closePath();	 	
							
						// side우측 그리기 (본판) 
								var startY = side_left_width * 1 + 300 - 20;	 // startY값 재설정

								ctx.beginPath();
								ctx.strokeStyle = 'red';
								
								ctx.moveTo(startX, startY );
								line(startX + mh2, startY );   
								line(startX + mh2 + rh1, startY );   
								line(startX + mh2 + rh1, startY  + a2 - bendrate);   
								line(startX + mh2 + rh1, startY  + a2 + c2 - bendrate*3);   
								line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + bendrate*4);   
								line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + k2 - bendrate*6);   
								line(startX + mh2 + rh2, startY  + a2 + c2 + jb2 + k2 + side_rightwing - bendrate*8);   
								line(startX + mh2, startY + a2 + c2 + jb2 + k2 + side_rightwing - bendrate*8);   
								line(startX + mh2, startY + a2 + c2 +b2 - bendrate*4);   
								line(startX , startY + a2 + c2 + b2 - bendrate*4);   
								line(startX , startY); 
								
								ctx.closePath();			

						// side우측 절곡선을 그린다.
						  var offset=1;
						  ctx.strokeStyle = 'brown';
						  ctx.beginPath();
						  ctx.setLineDash([70, 10]);
						  ctx.lineDashOffset = -offset;
						  if(a2>0) {
						  ctx.moveTo(startX , startY + a2 - bendrate*1);  
						  line(startX + mh2 + rh1, startY + a2 - bendrate*1); // 첫번째 절곡라인   
						  }  
						  if(c2>0) {
							ctx.moveTo(startX , startY + a2 +c2 - bendrate*3);  
							line(startX + mh2 + rh1, startY + a2 + c2 - bendrate*3); // 두번째 절곡라인    
						  }

						  if(k2>0) {
						  ctx.moveTo(startX + mh2 , startY + a2 + c2 + jb2 - bendrate*4);  	  
						  line(startX + mh2 + rh2, startY + a2 + c2  + jb2 - bendrate*4); // 세번째 절곡라인 (9도만 접는부위는 안함)
						  }
						  ctx.moveTo(startX + mh2, startY + a2 + c2 + k2 + jb2 - bendrate*6);  
						  line(startX + mh2 + rh2, startY + a2 + c2 + k2 + jb2 - bendrate*6); // 네번째 절곡라인

						  ctx.closePath();			
						

						// side우측 치수문자 화면출력
						  ctx.beginPath();
						  ctx.strokeStyle = 'blue';
						  ctx.setLineDash([0, 0]);
						  var side_right_height = mh2 + max(rh1,rh2);
						  ctx.font = '25px serif';
						  ctx.strokeText('( Side(우) RH2 뒷날개쪽 Height ) ' + rh2 , startX + side_right_height/2 - 100, startY + side_right_width +30);   // rh2 출력
						  ctx.strokeText('( Side(우) RH1 Height ) ' + rh1 , startX + side_right_height/2 - 100, startY - 30);   // rh1 출력
						  ctx.strokeText('( Side(우) 전체 Height ) ' + side_right_height , startX + side_right_height/2 - 100, startY - 60);   // 전체 height 출력
							 
						  ctx.font = '20px serif';
						  var p_txt=side_rightwing - bendrate*2;
						  ctx.strokeText('( 뒷날개 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + k2 +jb2 + c2 + a2 + side_rightwing/2 - bendrate*2 + 10);   // 첫번째 절곡 날개치수 출력
						  if(k2>0)
							{
							 var p_txt=k2 - bendrate*2;		
							 ctx.strokeText('( K2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + jb2 + c2 + a2 +  k2/2 - bendrate*2 - 10);   // 두번째 절곡 날개치수 출력
							}
								if(k2>0)
									var p_txt=jb2 - bendrate*1;
								 else
								   var p_txt=jb2 - bendrate*3;
						   
							 ctx.strokeText('( JB2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY + a2 + c2 + jb2/2 - bendrate*2 - 20);   // 세번째 절곡 날개치수 출력  	  

							 var p_txt = c2 - bendrate*2;	
							 if(c2>0)	 
								ctx.strokeText('( C2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY  + a2 + c2/2 - bendrate*2 );   // 네번째 절곡 날개치수 출력
							 var p_txt = a2 - bendrate;	
							 if(a2>0)	 
								ctx.strokeText('( A2 ) ' + p_txt, startX + mh2 + rh2 + 5, startY  - bendrate );   // 5째 절곡 날개치수 출력	 
							 if(mh1>0) {
								ctx.strokeText('( MH2 ) ' + mh2, startX + mh2/2  - 20, startY +a2 + c2 + 45);   // 막판과 만나는 돌출부위	 
							  if(b1>0)		
								  ctx.strokeText('( 막판Gap 돌출부위 ) ' + b2, startX + mh2  + 5, startY +a2 + c2 + b2 + 10);   // 막판과 만나는 돌출부위	 
							 }
						  						   
}		
			