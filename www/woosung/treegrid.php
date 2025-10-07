<!DOCTYPE HTML>
<html>

<head>
<meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script> 
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.css" rel="stylesheet">

<script src="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.0/dist/extensions/treegrid/bootstrap-table-treegrid.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-treegrid@0.3.0/css/jquery.treegrid.css">
<script src="https://cdn.jsdelivr.net/npm/jquery-treegrid@0.3.0/js/jquery.treegrid.min.js"></script>

  
<script src="http://8440.co.kr/common.js"></script>  

</head>

<div class="d-flex p-2 justify-content-center">						
	<!-- <button type="button"  class="fs-3 btn btn-danger   " onclick="location.href='index.php?mode=search&search=<?=$search?>&check=3'">  미청구   </button> &nbsp;  -->
	<button type="button"  id=writeBtn class=" btn btn-secondary btn-lg  " > 데이터 등록  </button>  
</div>

<table id="table" data-search="true" data-visible-search="true">

</table>

<script>

$(function() {
	
var $table = $('#table');
var search='';		
load_data();

});

  // function typeFormatter(value, row, index) {
    // if (value === 'menu') {
      // return '菜单'
    // }
    // if (value === 'button') {
      // return '按钮'
    // }
    // if (value === 'api') {
      // return '接口'
    // }
    // return '-'
  // }

  // function statusFormatter(value, row, index) {
    // if (value === 1) {
      // return '<span class="label label-success">正常</span>'
    // }
    // return '<span class="label label-default">비정상</span>'
  // }
   


function load_data()
 {  
  // alert(query);
  // $( '#table > tbody').empty();  
	
   var $table = $('#table')	;
		  
		$table.bootstrapTable({
		  url: "fetchtest.php" ,  
		  idField: 'id',
		  showColumns: true,
		  columns: [
			{
			  field: 'ck',
			  checkbox: true
			},
			{
			  field: 'name',
			  title: '현장(품명)',			
			  sortable: true,
			},
			{
			  field: 'company',
			  title: '발주처',
			  sortable: true,
			  align: 'center',
			  // formatter: 'statusFormatter'			  		  
			},
			{
			  field: 'date1',
			  title: '접수일',
			  sortable: true,
			  align: 'center',
			  // formatter: 'statusFormatter'			  		  
			},
			{
			  field: 'date2',
			  title: '납기일',
			  sortable: true,
			  align: 'center',
			  // formatter: 'statusFormatter'			  
			},
		  ],
		  treeShowField: 'name',
		  parentIdField: 'pid',
		  // Proper js  선택사항 수정시 작동하는 것
		  onPostBody: function() {
			var columns = $table.bootstrapTable('getOptions').columns

			if (columns && columns[0][1].visible) {
			  $table.treegrid({
				treeColumn: 1,
				onChange: function() {
				  $table.bootstrapTable('resetView')
				}
			  })
			}
		  }
		})

       

		// Here, you can expect to have in the 'e' variable the sender property, which is the boostrap-table object
		$('#table').on('dbl-click-cell.bs.table', function(event, name, place, data) {
					 // var nodeID = $('#tree').treeview('getSelected') ;
					 popupCenter('write_form.php?id=' + data.id + '&parent_id=' + data.parent_id , '발주서', 750, 900);
					 
					 console.log(event);			 
					 console.log(name);			 
					 console.log(place);			 
					 console.log(data.id);			 
					 
					// Your logic goes here
				});
 } 		
	   
  
</script>