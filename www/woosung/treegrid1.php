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

<table id="table"></table>

<script>
  var $table = $('#table')

  $(function() {
    $table.bootstrapTable({
      url: "fetchtest.php" , 
      idField: 'id',
      showColumns: true,
      search: true,
      columns: [
        {
          field: 'ck',
          checkbox: true
        },
        {
          field: 'name',
          title: '名称'
        },
        {
          field: 'status',
          title: '状态',
          sortable: true,
          align: 'center',
          formatter: 'statusFormatter'
        },
        {
          field: 'permissionValue',
          title: '权限值'
        }
      ],
      treeShowField: 'name',
      parentIdField: 'pid',
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
      },
      customSearch: function (data, text) {
        if (!text) {
          return data
        }
        const index = []
        for (let i = 0; i < data.length; i++) {
          const row = data[i]
          for (const value of Object.values(row)) {
            if ((value + '').includes(text)) {
              index.push(...getIndexArray(data, row, i))
            }
          }
        }
        return data.filter((row, i) => {
          return index.includes(i)
        })
      }
    })
  })
  
  function getIndexArray(data, row, index) {
    const arr = [index]
    while (true) {
      if (row.pid === 0) {
        break
      }
      row = data.find(it => it.id === row.pid)
      arr.push(data.indexOf(row))
    }
    return arr
  }

  function typeFormatter(value, row, index) {
    if (value === 'menu') {
      return '菜单'
    }
    if (value === 'button') {
      return '按钮'
    }
    if (value === 'api') {
      return '接口'
    }
    return '-'
  }

  function statusFormatter(value, row, index) {
    if (value === 1) {
      return '<span class="label label-success">正常</span>'
    }
    return '<span class="label label-default">锁定</span>'
  }
</script>