<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
 
 <!DOCTYPE html>


  <input type="button" id="test1" name="test2" onclick="fncAddRow('row1');">행추가</input>

    <input type="button" id="test2" name="test2">행삭제</input>

    <table>

        <tr>

            <th><input type="checkbox" id="row1" name="row1"/></th>

            <th>헤더1</th>

            <th>헤더2</th>

            <th>헤더3</th>

        </tr>

        <tr>

            <td><input type="checkbox" id="row2" name="row2"/><td>

            <td>내용1</td>

            <td>내용2</td>

            <td>내용3</td>

        </tr>

    </table>



<script>

      fncRowReset = function(obj){

            $(obj).find(':input').each(function(){

                  this.value = '';

                  this.checked = '';

            });

      };

//초기화 할 행 Object를 받아서 input 의 값을 비워주고 checkbox 일 경우 체크를 해제합니다.



//행추가 function

      fncAddRow = function(obj){

            

            var varTable = $(obj).parent().find('>table'); // 행추가 table

            var varTableLastRow = varTable.find('tr:last'); // 행추가 마지막 row

            var copyRow = varTableLastRow.clone(true); // 행추가 마지막 row 복사

                        

            // 행추가 row 값 초기화

            fncRowReset(copyRow);

                        

            // 행추가 맨 마지막에 append

            varTable.append(copyRow);

      };

//행추가 버튼을 object로 받아 그 위치를 기준으로 추가할 table을 찾고 그 table의 마지막 row를 복사해서 그 아래에 추가합니다.



//- 행삭제 function

      fncDeleteRow = function(obj){

            var varTable = $(obj).parent().find('>table');      // 행삭제 table

            var trCount = varTable.find('>tbody >tr').size();     // 행삭제 body row count

            if(trCount == 1){

                  alert('더이상 삭제할 수 없습니다.');

                  return;

            }



            if(confirm("선택하신 항목을 삭제하시겠습니까?")){

                  var chkCnt = 0;

                  varTable.find('>tbody input:checkbox').each(function(idx){

                        if(this.checked){

                              var nowRowSize = varTable.find('>tbody >tr').size();

                              var choiceRow = $(this).parent().parent();

                              if(nowRowSize == 1){

                                    // 마지막 row 값 초기화

                                    fncRowReset(choiceRow);     

                              } else {

                                    // 행삭제

                                    choiceRow.remove();

                              }

                              chkCnt++;

                        }

                  });

                  

                  if(chkCnt == 0){

                        alert('선택된 항목이 없습니다.');

                        return;

                  }

            }

            

            var newTrCnt = varTable.find('>tbody >tr').size();

            

            // 행삭제 tr id 초기화 (순서대로)

            for(var i = 0; i < newTrCnt; i++){

                  varTable.find('>tbody >tr:eq('+i+')').attr('id', i);

            }

      };

//행삭제 버튼을 Object로 받아 그 위치를 기준으로 table의 선택한 row를 삭제합니다.

//row가 1개일 경우에는 삭제가 불가능하게 막습니다.



//- 행 전체선택 / 해제 function

      fncCheckAll = function(obj){

            var chkAll = $(obj);

            var varTable = chkAll.parent().parent().parent().parent();

            

            var chkYn = obj.checked;

            

            varTable.find('>tbody input:checkbox').each(function(idx){

                  this.checked = chkYn;

            });

      };
	  </script>