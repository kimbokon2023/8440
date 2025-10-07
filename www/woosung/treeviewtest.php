<?php 
// 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'ini.php';    
?>

<!DOCTYPE HTML>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>  
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.js"></script>
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.css" />
<link rel="stylesheet" href="./css/style.css" />
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
  
<script src="http://8440.co.kr/common.js"></script>

<style>
.tree, .tree ul {
    margin:0;
    padding:0;
    list-style:none
}
.tree ul {
    margin-left:1em;
    position:relative
}
.tree ul ul {
    margin-left:.5em
}
.tree ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    border-left:1px solid
}
.tree li {
    margin:0;
    padding:0 1em;
    line-height:2em;
    color:#369;
    font-weight:700;
    position:relative
}
.tree ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px solid;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
}
.tree ul li:last-child:before {
    background:#fff;
    height:auto;
    top:1em;
    bottom:0
}
.indicator {
    margin-right:5px;
}
.tree li a {
    text-decoration: none;
    color:#369;
}
.tree li button, .tree li button:active, .tree li button:focus {
    text-decoration: none;
    color:#369;
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
}
</style>

<title> 통합정보 시스템 </title>

</head>

<div class="container" style="margin-top:30px;">
  <div class="row">
    <div class="col-md-4">
      <ul id="tree1">
        <p class="well" style="height:135px;"><strong>Initialization no parameters</strong>

          <br /> <code>$('#tree1').treed();</code>

        </p>
        <li><a href="#">TECH</a>

          <ul>
            <li>Company Maintenance</li>
            <li>Employees
              <ul>
                <li>Reports
                  <ul>
                    <li>Report1</li>
                    <li>Report2</li>
                    <li>Report3</li>
                  </ul>
                </li>
                <li>Employee Maint.</li>
              </ul>
            </li>
            <li>Human Resources</li>
          </ul>
        </li>
        <li>XRP
          <ul>
            <li>Company Maintenance</li>
            <li>Employees
              <ul>
                <li>Reports
                  <ul>
                    <li>Report1</li>
                    <li>Report2</li>
                    <li>Report3</li>
                  </ul>
                </li>
                <li>Employee Maint.</li>
              </ul>
            </li>
            <li>Human Resources</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="col-md-4">
      <p class="well" style="height:135px;"><strong>Initialization optional parameters</strong>

        <br /> <code>$('#tree2').treed({openedClass : 'glyphicon-folder-open', closedClass : 'glyphicon-folder-close'});</code>

      </p>
      <ul id="tree2">
        <li><a href="#">TECH</a>

          <ul>
            <li>Company Maintenance</li>
            <li>Employees
              <ul>
                <li>Reports
                  <ul>
                    <li>Report1</li>
                    <li>Report2</li>
                    <li>Report3</li>
                  </ul>
                </li>
                <li>Employee Maint.</li>
              </ul>
            </li>
            <li>Human Resources</li>
          </ul>
        </li>
        <li>XRP
          <ul>
            <li>Company Maintenance</li>
            <li>Employees
              <ul>
                <li>Reports
                  <ul>
                    <li>Report1</li>
                    <li>Report2</li>
                    <li>Report3</li>
                  </ul>
                </li>
                <li>Employee Maint.</li>
              </ul>
            </li>
            <li>Human Resources</li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="col-md-4">
      <p class="well" style="height:135px;"><strong>Initialization optional parameters</strong>

        <br /> <code>$('#tree3').treed({openedClass:'glyphicon-chevron-right', closedClass:'glyphicon-chevron-down'});</code>

      </p>
      <ul id="tree3">
        <li><a href="#">TECH</a>

          <ul>
            <li>Company Maintenance</li>
            <li>Employees
              <ul>
                <li>Reports
                  <ul>
                    <li>Report1</li>
                    <li>Report2</li>
                    <li>Report3</li>
                  </ul>
                </li>
                <li>Employee Maint.</li>
              </ul>
            </li>
            <li>Human Resources</li>
          </ul>
        </li>
        <li>XRP
          <ul>
            <li>Company Maintenance</li>
            <li>Employees
              <ul>
                <li>Reports
                  <ul>
                    <li>Report1</li>
                    <li>Report2</li>
                    <li>Report3</li>
                  </ul>
                </li>
                <li>Employee Maint.</li>
              </ul>
            </li>
            <li>Human Resources</li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>

</body>  


</html>

<script>

$.fn.extend({
    treed: function (o) {
      
      var openedClass = 'glyphicon-minus-sign';
      var closedClass = 'glyphicon-plus-sign';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }				
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
      tree.find('.branch .indicator').each(function(){
        $(this).on('click', function () {
            $(this).closest('li').click();			
        });
      });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('#tree1').treed();

$('#tree2').treed({openedClass:'glyphicon-folder-open', closedClass:'glyphicon-folder-close'});

$('#tree3').treed({openedClass:'glyphicon-chevron-right', closedClass:'glyphicon-chevron-down'});

</script>