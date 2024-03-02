<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
    <HEAD>
        <TITLE>DB</TITLE>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="-1">
        <script>
            history.pushState(null, null, location.href); 
            window.onpopstate = function() { 
                history.go(1); 
            }
        </script>
        <?php 
            include 'log_head.php';
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel = "stylesheet" href = 'css/main.css'>
    </HEAD>
    <BODY onload="showClock('<?=session_cache_expire()?>')">
        <?php
        if(!$jb_login ) {?>
        <div style = "display: flex; justify-content: center">
            <form action = "main.php" method = 'post' name = 'logcont'>
                <h1 style = "align-items:center; margin-top: 0px">DB UI</h1>
                <?
                if(isset($msg)){?>
                    <div style = "text-align:center; color: red; background: white; font-weight: bold; font-size: 14px"><?=$msg?></div><?
                }?>
                <input style = "width: 200px; background-size: 200px 100%; background-position: -50px 0; background-size: 50px 100%;
                transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1); 
                background-position: -200px 0; margin: 40px 25px;" type = 'text' name = 'id' placeholder = "ID만" required="">
                </input>
                <input style = "width: 200px; background-size: 200px 100%; background-position: -50px 0; background-size: 50px 100%;
                transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
                background-position: -200px 0; margin: 40px 25px;" type = 'password' name = 'password' placeholder = "PW" required="">
                </input>
                <button style = "margin-left: 25px" onclick = "on_login()">로그인</button>    
            </form>
        </div><?
        }
        else {
            $dbid = sprintf("db%s", $id);
            $sqlid = sprintf("%s", $id); 
            ?>
            <div class = 'whole'>
                <div style = "display: flex; justify-content: center; align-items: center">
                    <h1>DB [<?=$id?>]</h1>
                </div>
                <div style = "width: 100%; ">
                    
                    <div style = "display: flex; justify-content: center">
                        <div id = "displayStatus" class = 'text_bar2' ></div>
                        <div class = 'text_bar' ><span id = "tableCount"></span>&nbsp;table(s) in DB</div>
                        <div style = "display: flex; justify-content: center">
                            <select id = 'tableName' name = 'tableName'></select>
                            <div><button id = "select_table_btn" class = "button_50" onclick='onSelectTable()'>선택</button></div>
                        </div>
                        <div><button class = "button_150" style = "margin-left: 6px; margin-right: 6px;" 
                                    onclick = 'on_create_table()'>테이블 생성</button></div>
                        <div class = 'time' style = "display: flex; justify-content: center">
                            <div id = "time_bar" class = 'text_bar' >남은 시간 <div id = "divClock"> </div> 
                            </div>
                            <div>
                                <button class = "button_100" onclick = "on_logout()">LOGOUT</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id = "tableInfo" style = "display: flex; flex-flow: nowrap; justify-content: center; display:none">
                    <div>
                        <div id = "table_box">
                            <div id = "table_box_bar">
                                <input id = "newTableName" type = "text" style = "display: none" value = "">
                                <div id = "selectedTableName">선택 테이블:&nbsp;<span id = "thisTableName"></span></div>
                            </div>
                            <div>
                                <div id = "updateTableNameFlagBox" style = "display: none">
                                    <button class = "button_150" onclick = "onUpdateTableName()" style = "margin: 10px 10px;">수정 완료</button>
                                    <button class = "button_150" onclick = "onUpdateTableNameCancel()" style = "margin: 10px 10px;">수정 취소</button>
                                </div>
                                <div id = "updateTableNameBox" style = "display: block">
                                    <button class = "button_150" onclick = "onUpdateTableNameFlag()" style = "margin: 10px 10px;">테이블 수정</button>
                                    <button class = "button_150" onclick = "onDropTable()" style = "margin: 10px 10px;">테이블 삭제</button>
                                </div>
                            </div>
                        </div> 
                        <div id = "column_box2">
                            <div class = "column_box_bar">외래키 등록</div>
                            <div>
                                <select id = 'select_not_foreign_column' name = 'select_not_foreign_column' style = "margin-left: 5px;"></select>을
                                <select id = 'select_foreign_table' name = 'select_foreign_table' style = "margin-left: 5px;" onchange = 'onFindForeignColumns()'></select>의
                                <select id = 'select_foreign_column' name = 'select_foreign_column' style = "margin-left: 5px;" ></select>으로
                                <button id = "btn_set_foreign_column" class = "button_50" style = "margin: 10px 10px;" onclick='onSetForeignKey()'>등록</button>
                            </div>
                        </div> 
                        <div id = "column_box3">
                            <div class = "column_box_bar">외래키 해제</div>
                            <div>
                                <select id = 'select_this_foreign_column' name = 'select_foreign_column' style = "margin-left: 5px;"></select>
                                <button id = "btn_unset_foreign_column" class = "button_110" style = "margin: 10px 10px;" onclick='onUnsetForeignKey()'>모두 해제</button>
                            </div>
                        </div> 
                    </div>
                    <table id = "column_box"></table>
                </div>
                <div id = "dataInfo" style = "display: flex; justify-content: center; align-items: center; display: none">
                        <table id = "data_box"></table>
                </div>
            </div>
        <form action = 'main.php' name = 'logcont' method = 'post' style= 'display:none'>
            <input type = 'hidden' name = 'svalue' value = ''> 
        </form>
            <?php
        }
        ?>
        <SCRIPT>
            function on_login(){
                document.logcont.svalue.value = false;
                document.logcont.submit();
            }
            function on_logout(){
                document.logcont.svalue.value = true;
                document.logcont.submit();
            }
            function on_create_table(){
                let openwin = window.open("new_table.php", "CREATE TABLE", "height = 600, width = 800, resizable = no");
            }
            function showClock(t){
                var divClock = document.getElementById('divClock');
                var msg = ": [ ";
                t = parseInt(t);
                var seconds =  t % 60;
                if(seconds < 10){seconds = "0" + seconds;}
                var minutes = Math.trunc(t / 60)
                if(minutes < 10){minutes = "0" + Math.trunc(t / 60);}
                msg += minutes + " : " + seconds + " ] ";
                divClock.innerText = msg;
                if(t == 60) {document.getElementById("time_bar").style.color = "red";}
                if(t < 1){
                    on_logout();
                }
                else setTimeout(showClock, 1000, t - 1);  //1초마다 갱신
            }
            function onUpdateTableNameFlag() {
                $('#newTableName').css("display", "block");
                $('#selectedTableName').css("display", "none");
                $('#newTableName').val($('#'+$('#tableName').val()).text());
                $('#updateTableNameFlagBox').css("display", "block");
                $('#updateTableNameBox').css("display", "none");
            }
            function onUpdateTableNameCancel() {
                $('#newTableName').css("display", "none");
                $('#selectedTableName').css("display", "block");
                $('#updateTableNameFlagBox').css("display", "none");
                $('#updateTableNameBox').css("display", "block");
            }
            function onUpdateTableName() {
                var oldTableName = $('#thisTableName').text();
                var newTableName = $('#newTableName').val();
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'oldTableName': oldTableName, 'newTableName': newTableName};
                onXmlHttpStatus(obj, './sql/updateTableName.php', console.log); 
                
                $('#'+$('#tableName').val()).text(newTableName);
                $('#newTableName').css("display", "none");
                $('#selectedTableName').css("display", "block");
                $('#thisTableName').text(newTableName);
                $('#updateTableNameFlagBox').css("display", "none");
                $('#updateTableNameBox').css("display", "block");
            }
            function onCreateTableColumnFlag(idx) {
                $('#columnTableNewName').css("display", "block");
                $('#columnTableNewType').css("display", "block");
                $('#columnTableNewSize').css("display", "block");
                $('#columnTableCbtn').css("display", "none");
                $('#columnTableCFbtn').css("display", "block");
                $('#columnTableCCbtn').css("display", "block");
            }
            function onCreateTableColumnCancel(idx) {
                $('#columnTableNewName').css("display", "none");
                $('#columnTableNewType').css("display", "none");
                $('#columnTableNewSize').css("display", "none");
                $('#columnTableCbtn').css("display", "block");
                $('#columnTableCFbtn').css("display", "none");
                $('#columnTableCCbtn').css("display", "none");
            }
            function onCreateTableColumn(idx) {
                var tableName = $('#thisTableName').text();
                var newTableColumnName = $("#columnTableNewName").val();
                var newTableColumnType = $("#columnTableNewType").val();
                var newTableColumnSize = $("#columnTableNewSize").val();
                var beforeOrderColumnName = $('#columnTableTr'+idx+'Span0').text();
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName':tableName,
                           'newTableColumnName': newTableColumnName, 'newTableColumnType': newTableColumnType, 
                           'newTableColumnSize': newTableColumnSize, 'beforeOrderColumnName': beforeOrderColumnName};
                onXmlHttpStatus(obj, './sql/createTableColumn.php', onViewColumn);

                $('#columnTableNewName').css("display", "none");
                $('#columnTableNewType').css("display", "none");
                $('#columnTableNewSize').css("display", "none");
                $('#columnTableCbtn').css("display", "block");
                $('#columnTableCFbtn').css("display", "none");
                $('#columnTableCCbtn').css("display", "none");
            }
            function onUpdateTableColumnFlag(idx) {
                $('#columnTableTr'+idx+"Span0").css("display", "none");
                $('#columnTableTr'+idx+"Text0").css("display", "block");
                $('#columnTableTr'+idx+"Span1").css("display", "none");
                $('#columnTableTr'+idx+"Text1").css("display", "block");
                $('#columnTableTr'+idx+"Span2").css("display", "none");
                $('#columnTableTr'+idx+"Text2").css("display", "block");
                $('#columnTableTr'+idx+"Tbtn0").css("display", "none");
                $('#columnTableTr'+idx+"Fbtn0").css("display", "block");
                $('#columnTableTr'+idx+"Tbtn1").css("display", "none");
                $('#columnTableTr'+idx+"Fbtn1").css("display", "block");
            }
            function onUpdateTableColumnCancel(idx) {
                $('#columnTableTr'+idx+"Span0").css("display", "block");
                $('#columnTableTr'+idx+"Text0").css("display", "none");
                $('#columnTableTr'+idx+"Span1").css("display", "block");
                $('#columnTableTr'+idx+"Text1").css("display", "none");
                if($('#columnTableTr'+idx+"Span2").text() != null || $('#columnTableTr'+idx+"Span2").text() != "") {
                    $('#columnTableTr'+idx+"Span2").css("display", "block");
                    $('#columnTableTr'+idx+"Text2").css("display", "none");
                }
                $('#columnTableTr'+idx+"Tbtn0").css("display", "block");
                $('#columnTableTr'+idx+"Fbtn0").css("display", "none");
                $('#columnTableTr'+idx+"Tbtn1").css("display", "block");
                $('#columnTableTr'+idx+"Fbtn1").css("display", "none");
            }
            function onUpdateTableColumn(idx) {
                var tableName = $('#thisTableName').text();
                var oldTableColumnName = $('#columnTableTr'+idx+"Span0").text();
                var tableColumnName = $('#columnTableTr'+idx+'Text0').val();
                var tableColumnType = $('#columnTableTr'+idx+'Text1').val();
                if($('#columnTableTr'+idx+'Text2').val() != null || $('#columnTableTr'+idx+'Text2').val() != "") {
                    var tableColumnSize = $('#columnTableTr'+idx+'Text2').val();
                }
                else {var tableColumnSize = null;}
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName':tableName,
                           'tableColumnName': tableColumnName, 'tableColumnType': tableColumnType, 
                           'tableColumnSize': tableColumnSize, 'oldTableColumnName': oldTableColumnName};
                onXmlHttpStatus(obj, './sql/updateTableColumn.php', onViewColumn);

                $('#columnTableTr'+idx+"Span0").css("display", "block");
                $('#columnTableTr'+idx+"Text0").css("display", "none");
                $('#columnTableTr'+idx+"Span1").css("display", "block");
                $('#columnTableTr'+idx+"Text1").css("display", "none");
                if($('#columnTableTr'+idx+"Span2").text() != null || $('#columnTableTr'+idx+"Span2").text() != "") {
                    $('#columnTableTr'+idx+"Span2").css("display", "block");
                    $('#columnTableTr'+idx+"Text2").css("display", "none");
                }
                $('#columnTableTr'+idx+"Tbtn0").css("display", "block");
                $('#columnTableTr'+idx+"Fbtn0").css("display", "none");
                $('#columnTableTr'+idx+"Tbtn1").css("display", "block");
                $('#columnTableTr'+idx+"Fbtn1").css("display", "none");
            }
            function onChangeTableColumnOrder(tableColumnSeries) {
                var tableColumnArray = tableColumnSeries.split("@");
                var tableColumnName = tableColumnArray[0];
                var tableColumnType = tableColumnArray[1];
                if(tableColumnArray[2] != null || tableColumnArray[2] != "") {
                    var tableColumnSize = tableColumnArray[2];
                }
                else {
                    var tableColumnSize = null;
                }
                var beforeOrderColumnName = tableColumnArray[3];

                var tableName = $('#thisTableName').text();
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName, 
                        'tableColumnName': tableColumnName, 'tableColumnType': tableColumnType, 'tableColumnSize' : tableColumnSize,
                        'beforeOrderColumnName' : beforeOrderColumnName};
                onXmlHttpStatus(obj, './sql/changeTableColumnOrder.php', onViewColumn);
            }
            function onDropTableColumn(idx) {
                var result = confirm("정말 컬럼을 삭제하시겠습니까?");
                if(result){ 
                    var pw = prompt("패스워드를 입력해주세요", "");
                    <?php echo "var paas = '$password';";?>
                    if(pw === paas){
                        var tableName = $('#thisTableName').text();
                        var tableColumnName = $('#columnTableTr'+idx+"Span0").text();
                        var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName, 'tableColumnName': tableColumnName};
                        onXmlHttpStatus(obj, './sql/dropTableColumn.php', onViewColumn);
                    }
                    else{
                        confirm("비밀번호가 틀렸습니다.")
                    }
                }
            }
            function onDropTable() {
                var result = confirm("정말 테이블을 삭제하시겠습니까? 복구할 수 없습니다.");
                if(result){ 
                    var pw = prompt("패스워드를 입력해주세요", "");
                    <?php echo "var paas = '$password';";?>
                    if(pw === paas){
                        var tableName = $('#thisTableName').text();
                        var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName};
                        onXmlHttpStatus(obj, './sql/dropTable.php');
                    }
                    else{
                        confirm("비밀번호가 틀렸습니다.")
                    }
                }   
            }
            function onInsertDataFlag(length) {
                for(var i = 1; i < length - 1; i++) {
                    $('#dataTableIText'+i).css("display", "block");
                }
                $('#dataTableIbtn').css("display", "none");
                $('#dataTableIFbtn').css("display", "block");
                $('#dataTableICbtn').css("display", "block");
            }
            function onInsertDataCancel(length) {
                for(var i = 1; i < length - 1; i++) {
                    $('#dataTableIText'+i).css("display", "none");
                }
                $('#dataTableIbtn').css("display", "block");
                $('#dataTableIFbtn').css("display", "none");
                $('#dataTableICbtn').css("display", "none");
            }
            function onInsertData() {
                var newDataArray = new Array(); var dataSize = document.getElementsByName('newData').length;
                var thisColumnArray = new Array(); var columnSize = document.getElementsByName('thisColumn').length;
                for(var i = 0; i < dataSize; i++) {
                    newDataArray[i] = document.getElementsByName('newData')[i].value;
                }
                for(var i = 0; i < columnSize; i++) {
                    thisColumnArray[i] = document.getElementsByName('thisColumn')[i].innerText;
                }
                console.log(newDataArray);
                console.log(thisColumnArray);
                const TIME_ZONE = 9 * 60 * 60 * 1000; // 9시간
                const d = new Date();
                const date = new Date(d.getTime() + TIME_ZONE).toISOString().split('T')[0];
                const time = d.toTimeString().split(' ')[0];
                var newDateTime = date + " " + time;

                var tableName = $('#thisTableName').text();
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName, 
                           'dataArray': newDataArray, 'columnArray': thisColumnArray, 'newDateTime': newDateTime};
                onXmlHttpStatus(obj,'./sql/insertData.php', onFindDatas);

                for(var i = 1; i < length - 1; i++) {
                    $('#dataTableIText'+i).css("display", "none");
                }
                $('#dataTableIbtn').css("display", "block");
                $('#dataTableIFbtn').css("display", "none");
                $('#dataTableICbtn').css("display", "none");
            }
            function onUpdateDataFlag(idx, length) {
                for(var i = 1; i < length - 1; i++) {
                    $('#dataTableTr'+idx+'Span'+i).css("display", "none");
                    $('#dataTableTr'+idx+'Text'+i).css("display", "block");
                }
                $('#dataTableTr'+idx+'Ubtn').css("display", "none");
                $('#dataTableTr'+idx+'UFbtn').css("display", "block");
                $('#dataTableTr'+idx+'Dbtn').css("display", "none");
                $('#dataTableTr'+idx+'UCbtn').css("display", "block");
            }
            function onUpdateDataCancel(idx, length) {
                for(var i = 1; i < length - 1; i++) {
                    $('#dataTableTr'+idx+'Text'+i).css("display", "none");
                    $('#dataTableTr'+idx+'Span'+i).css("display", "block");
                }
                $('#dataTableTr'+idx+'UFbtn').css("display", "none");
                $('#dataTableTr'+idx+'Ubtn').css("display", "block");
                $('#dataTableTr'+idx+'UCbtn').css("display", "none");
                $('#dataTableTr'+idx+'Dbtn').css("display", "block");
            }
            function onUpdateData(idx, length) {
                var thisDataArray = new Array();
                var thisColumnArray = new Array();
                for(var i = 1; i < length - 1; i++) {
                    thisDataArray[i-1] = $('#dataTableTr'+idx+'Text'+i).val();
                }
                for(var i = 0; i < length - 2; i++) {
                    thisColumnArray[i] = document.getElementsByName('thisColumn')[i].innerText;
                }
                var tableName = $('#thisTableName').text();
                var dataIdx = $("#dataTableTr"+idx+"Span0").text();
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName, 
                           'dataArray': thisDataArray, 'columnArray': thisColumnArray, 'dataIdx': dataIdx};
                onXmlHttpStatus(obj, './sql/updateData.php', onFindDatas);

                for(var i = 1; i < length - 1; i++) {
                    $('#dataTableTr'+idx+'Text'+i).css("display", "none");
                    $('#dataTableTr'+idx+'Span'+i).css("display", "block");
                }
                $('#dataTableTr'+idx+'UFbtn').css("display", "none");
                $('#dataTableTr'+idx+'Ubtn').css("display", "block");
                $('#dataTableTr'+idx+'UCbtn').css("display", "none");
                $('#dataTableTr'+idx+'Dbtn').css("display", "block");
            }
            function onDeleteData(dataIdx) {
                var result = confirm("정말 데이터를 삭제하시겠습니까?");
                if(result){ 
                    var tableName = $('#tableName').val();
                    var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName, 
                            'dataIdx': dataIdx};
                    onXmlHttpStatus(obj, './sql/deleteData.php', onFindDatas);
                }
            }
            function onSelectTable() {
                $('#thisTableName').text($('#'+$('#tableName').val()).text());
                onFindAllTableNames();
                onForeignSetting();
                onFindTableColumns();
                onFindDatas();
            }
            function onViewColumn() {
                onForeignSetting();
                onFindTableColumns();
                onFindDatas();
            }
            function onViewTable() {
                onForeignSetting();
                onFindTableColumns();
            }
            function onFindAllTableNames() {
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>'};
                dbParam = JSON.stringify(obj); 
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        //console.log(xmlhttp.responseText);
                        $('#tableName').empty();
                        var result = JSON.parse(xmlhttp.responseText);
                        document.getElementById('tableCount').innerText = result.tableName.length;
                        for(var i = 0; i < result.tableName.length; i++) {
                            var option = document.createElement('option');
                            option.setAttribute('id', "tableName"+i);
                            option.setAttribute('value', "tableName"+i);
                            option.innerText = result.tableName[i].TABLE_NAME;
                            $('#tableName').append(option);
                        }
                    }
                };
                xmlhttp.open('GET', './sql/findAllTables.php?x=' + dbParam, true); 
                xmlhttp.send();
            }
            function onForeignSetting() {
                var tableName = $('#thisTableName').text();
                $('#select_foreign_table').empty();
                $('#select_not_foreign_column').empty();
                $('#select_this_foreign_column').empty();
                    var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName':tableName};
                    dbParam = JSON.stringify(obj); xmlhttp1 = new XMLHttpRequest();
                    xmlhttp1.onreadystatechange = function() {
                        if(xmlhttp1.readyState == 4 && xmlhttp1.status == 200) {
                            var result = JSON.parse(xmlhttp1.responseText);
                            console.log(result);
                            for(var i = 0; i < result.length; i++) {
                                var option = document.createElement('option');
                                option.setAttribute('value', result[i].COLUMN_NAME);
                                option.innerText = result[i].COLUMN_NAME;
                                $('#select_this_foreign_column').append(option);
                            }       
                        }
                    };
                    xmlhttp1.open('GET', './sql/findForeignKey.php?x=' + dbParam, true); 
                    xmlhttp1.send();

                    var result2 = [];
                    var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName':tableName};
                    dbParam = JSON.stringify(obj); 
                    xmlhttp2 = new XMLHttpRequest();
                    xmlhttp2.onreadystatechange = function() {
                        if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200) {
                            result2 = JSON.parse(xmlhttp2.responseText);
                            for(var i = 0; i < result2.length; i++) {
                                var option2 = document.createElement('option');
                                option2.setAttribute('value', result2[i].COLUMN_NAME);
                                option2.innerText = result2[i].COLUMN_NAME;
                                $('#select_not_foreign_column').append(option2);
                            }                      
                        }
                    };
                    xmlhttp2.open('GET', './sql/findNotForeignKey.php?x=' + dbParam, true); 
                    xmlhttp2.send();

                    var result3 = [];
                    var foreignTable = "";
                    var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName':tableName};
                    dbParam = JSON.stringify(obj); 
                    xmlhttp3 = new XMLHttpRequest();
                    xmlhttp3.onreadystatechange = function() {
                        if(xmlhttp3.readyState == 4 && xmlhttp3.status == 200) {
                            result3 = JSON.parse(xmlhttp3.responseText);
                            for(var i = 0; i < result3.length; i++) {
                                var option3 = document.createElement('option');
                                option3.setAttribute('value', result3[i].TABLE_NAME);
                                option3.innerText = result3[i].TABLE_NAME;
                                $('#select_foreign_table').append(option3);
                            }                      
                        }
                    };
                    xmlhttp3.open('GET', './sql/findOtherTable.php?x=' + dbParam, true); 
                    xmlhttp3.send();
            }
            function onSetForeignKey() {
                var tableName = $('#thisTableName').text();
                var thisColumnName = $('#select_not_foreign_column').val();
                var foreignTableName = $('#select_foreign_table').val();
                var foreignColumnName = $('#select_foreign_column').val();
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName':tableName,
                            'thisColumnName':thisColumnName, 'foreignTableName':foreignTableName, 'foreignColumnName':foreignColumnName};
                onXmlHttpStatus(obj, './sql/setForeignKey.php', onViewTable);
            }
            function onUnsetForeignKey() {
                var tableName = $('#thisTableName').text();
                var foreignColumn = $('#select_this_foreign_column').val();
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName':tableName, 'foreignColumn':foreignColumn};
                onXmlHttpStatus(obj, './sql/unsetForeignKey.php', onViewTable);
            }
            function onFindForeignColumns() {
                $('#select_foreign_column').empty();
                var foreignTable = $('#select_foreign_table').val();
                var result4 = [];
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName':foreignTable};
                dbParam = JSON.stringify(obj); 
                xmlhttp4 = new XMLHttpRequest();
                xmlhttp4.onreadystatechange = function() {
                    if(xmlhttp4.readyState == 4 && xmlhttp4.status == 200) {
                        result4 = JSON.parse(xmlhttp4.responseText);
                        for(var i = 0; i < result4.length; i++) {
                            var option4 = document.createElement('option');
                            option4.setAttribute('value', result4[i].COLUMN_NAME);
                            option4.innerText = result4[i].COLUMN_NAME;
                            $('#select_foreign_column').append(option4);
                        }                      
                    }
                };
                xmlhttp4.open('GET', './sql/findColumn.php?x=' + dbParam, true); 
                xmlhttp4.send();
            }
            function onFindTableColumns() {
                var tableName = $('#thisTableName').text();
                $('#column_box').empty();
                document.getElementById('tableInfo').style.display = 'flex';
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName};
                dbParam = JSON.stringify(obj); 
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        //console.log(xmlhttp.responseText);
                        var result = JSON.parse(xmlhttp.responseText);
                        for(var i = 0; i < result.length; i++) {
                            var tr = document.createElement('tr');
                            tr.setAttribute("id", "columnTableTr"+i);
                
                            var td = document.createElement('td');
                            td.innerText = "- 컬럼 " + (i + 1) + ": ";
                            tr.append(td);

                            var td0 = document.createElement('td');
                            td0.setAttribute("id", "columnTableTr"+i+"Td0");
                            var span0 = document.createElement('span');
                            span0.setAttribute('id', "columnTableTr"+i+"Span0");
                            span0.style.display = "block";
                            span0.innerText = result[i].COLUMN_NAME;
                            td0.append(span0);
                            var text0 = document.createElement('input');
                            text0.setAttribute("type", "text");
                            text0.setAttribute("id", "columnTableTr"+i+"Text0");
                            text0.setAttribute("value", result[i].COLUMN_NAME);
                            text0.style.display = "none";
                            td0.append(text0);
                            tr.append(td0);

                            var td1 = document.createElement('td');
                            td1.setAttribute("id", "columnTableTr"+i+"Td1");
                            var span1 = document.createElement('span');
                            span1.setAttribute('id', "columnTableTr"+i+"Span1");
                            span1.style.display = "block";
                            span1.innerText = "(TYPE: " + result[i].DATA_TYPE + ")";
                            td1.append(span1);
                            var text1 = document.createElement('input');
                            text1.setAttribute("type", "text");
                            text1.setAttribute("id", "columnTableTr"+i+"Text1");
                            text1.setAttribute("value", result[i].DATA_TYPE);
                            text1.style.display = "none";
                            td1.append(text1);
                            tr.append(td1);
                            
                            var td2 = document.createElement('td');
                            td2.setAttribute("id", "columnTableTr"+i+"Td2");
                            var span2 = document.createElement('span');
                            span2.setAttribute('id', "columnTableTr"+i+"Span2");
                            var text2 = document.createElement('input');
                            text2.setAttribute("type", "text");
                            text2.setAttribute("id", "columnTableTr"+i+"Text2");
                            span2.style.display = "block";
                            text2.style.display = "none";
                            if(result[i].CHARACTER_MAXIMUM_LENGTH != null) {    
                                span2.innerText = "(SIZE: " + result[i].CHARACTER_MAXIMUM_LENGTH + ")";
                                text2.setAttribute("value", result[i].CHARACTER_MAXIMUM_LENGTH);
                            }
                            td2.append(span2);
                            td2.append(text2);
                            tr.append(td2);

                            var td3 = document.createElement('td');
                            td3.setAttribute("id", "columnTableTr"+i+"Td3");
                            if(result[i].COLUMN_KEY != "") {
                                td3.innerText = "(KEY: " + result[i].COLUMN_KEY + ")";
                            }
                            tr.append(td3);

                            var td4 = document.createElement("td");
                            if( i > 0 ) {
                                var button1 = document.createElement("button");
                                button1.setAttribute("id", "columnTableTr"+i+"Tbtn0");
                                button1.setAttribute("class", "button_100");
                                button1.style.display = "block";
                                button1.setAttribute("onclick", "onUpdateTableColumnFlag("+i+");");
                                button1.innerText = "컬럼 수정";
                                td4.append(button1);
                                var button1 = document.createElement("button");
                                button1.setAttribute("id", "columnTableTr"+i+"Fbtn0");
                                button1.setAttribute("class", "button_100");
                                button1.style.display = "none";
                                button1.setAttribute("onclick", "onUpdateTableColumn("+i+");");
                                button1.innerText = "수정 완료";
                                td4.append(button1);
                            }
                            tr.append(td4);

                            var td5 = document.createElement("td");
                            if( i > 0 ) {
                                var button1 = document.createElement("button");
                                button1.setAttribute("id", "columnTableTr"+i+"Tbtn1");
                                button1.setAttribute("class", "button_100");
                                button1.style.display = "block";
                                button1.setAttribute("onclick", "onDropTableColumn("+i+");");
                                button1.innerText = "컬럼 삭제";
                                td5.append(button1);
                                var button1 = document.createElement("button");
                                button1.setAttribute("id", "columnTableTr"+i+"Fbtn1");
                                button1.setAttribute("class", "button_100");
                                button1.style.display = "none";
                                button1.setAttribute("onclick", "onUpdateTableColumnCancel("+i+");");
                                button1.innerText = "수정 취소";
                                td5.append(button1);
                            }
                            tr.append(td5);

                            var td6 = document.createElement("td");
                            if( i > 1 && i < result.length - 1 ) {
                                var button3 = document.createElement("button");
                                button3.setAttribute("class", "button_25");
                                button3.setAttribute("onclick", "onChangeTableColumnOrder('"+result[i].COLUMN_NAME+"@"+result[i].DATA_TYPE+"@"+result[i].CHARACTER_MAXIMUM_LENGTH+"@"+result[i-2].COLUMN_NAME+"');");
                                button3.innerText = "▲";
                                td6.append(button3);
                            }
                            tr.append(td6);

                            var td7 = document.createElement("td");
                            if( i > 0 && i < result.length - 2 ) {
                                var button4 = document.createElement("button");
                                button4.setAttribute("class", "button_25");
                                button4.setAttribute("onclick", "onChangeTableColumnOrder('"+result[i].COLUMN_NAME+"@"+result[i].DATA_TYPE+"@"+result[i].CHARACTER_MAXIMUM_LENGTH+"@"+result[i+1].COLUMN_NAME+"');");
                                button4.innerText = "▼";
                                td7.append(button4);
                            }
                            tr.append(td7);

                            $('#column_box').append(tr);
                        }
                        var tr = document.createElement('tr');
                        var td = document.createElement('td');
                        td.innerText = "- 컬럼 N: ";
                        tr.append(td);
                        var td = document.createElement('td');
                        var text = document.createElement('input');
                        text.setAttribute('id', 'columnTableNewName');
                        text.setAttribute('type', 'text');
                        text.style.display = "none";
                        td.append(text);
                        tr.append(td);
                        var td = document.createElement('td');
                        var text = document.createElement('input');
                        text.setAttribute('id', 'columnTableNewType');
                        text.setAttribute('type', 'text');
                        text.style.display = "none";
                        td.append(text);
                        tr.append(td);
                        var td = document.createElement('td');
                        var text = document.createElement('input');
                        text.setAttribute('id', 'columnTableNewSize');
                        text.setAttribute('type', 'text');
                        text.style.display = "none";
                        td.append(text);
                        tr.append(td);
                        var td = document.createElement('td');
                        tr.append(td);
                        var td = document.createElement('td');
                        var button = document.createElement("button");
                        button.setAttribute("id", "columnTableCbtn");
                        button.setAttribute("class", "button_100");
                        button.style.display = "block";
                        button.setAttribute("onclick", "onCreateTableColumnFlag();");
                        button.innerText = "컬럼 추가";
                        td.append(button);
                        var button = document.createElement("button");
                        button.setAttribute("id", "columnTableCFbtn");
                        button.setAttribute("class", "button_100");
                        button.style.display = "none";
                        button.setAttribute("onclick", "onCreateTableColumn("+(result.length-2)+");");
                        button.innerText = "추가 완료";
                        td.append(button);
                        tr.append(td);
                        var td = document.createElement('td');
                        var button = document.createElement("button");
                        button.setAttribute("id", "columnTableCCbtn");
                        button.setAttribute("class", "button_100");
                        button.style.display = "none";
                        button.setAttribute("onclick", "onCreateTableColumnCancel();");
                        button.innerText = "추가 취소";
                        td.append(button);
                        tr.append(td);
                        $('#column_box').append(tr);
                    }
                };
                xmlhttp.open('GET', './sql/findTableColumns.php?x=' + dbParam, true); 
                xmlhttp.send();
            }
            function onFindDatas() {
                document.getElementById('dataInfo').style.display = 'flex';
                $('#data_box').empty();
                var tableName = $('#thisTableName').text();
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName};
                dbParam = JSON.stringify(obj); 
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        var result = JSON.parse(xmlhttp.responseText);
                        var columnLength = result.length;
                        var tr = document.createElement("tr");
                        tr.setAttribute('id', "data_box_bar");
                        for(var i = 0; i < columnLength; i++) {
                            var td = document.createElement("td");
                            if(i > 0 && result[i].COLUMN_NAME != 'idate') {
                               td.setAttribute("name", "thisColumn") 
                            }
                            td.setAttribute('style', "padding: 0px 5px;");
                            td.innerText = result[i].COLUMN_NAME;
                            tr.append(td);
                        }
                        var td1 = document.createElement("td");
                        tr.append(td1);
                        var td2 = document.createElement("td");
                        tr.append(td2);

                        $('#data_box').append(tr);

                        var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName};
                        dbParam = JSON.stringify(obj); 
                        var xmlhttp2 = new XMLHttpRequest();
                        xmlhttp2.onreadystatechange = function() {
                            if(xmlhttp2.readyState == 4 && xmlhttp2.status == 200) {
                                var result = JSON.parse(xmlhttp2.responseText);
                                if(result == null) result = [];
                                for(var i = 0; i < result.length; i++) {
                                    var tr = document.createElement("tr");
                                    tr.setAttribute('id', "dataTableTr"+i);
                                    if(result.length > 0) {
                                        for(var j = 0; j < columnLength; j++) {
                                            var td = document.createElement("td");
                                            td.setAttribute('id', "dataTableTr"+i+"Td"+j);
                                            td.setAttribute('style', "padding: 0px 5px;");
                                            var span = document.createElement("span");
                                            var text = document.createElement("input");
                                            span.setAttribute("id","dataTableTr"+i+"Span"+j);
                                            text.setAttribute("id","dataTableTr"+i+"Text"+j);
                                            span.innerText = result[i][j];
                                            span.style.display = "block";
                                            td.append(span);
                                            
                                            text.setAttribute("type","text");
                                            text.setAttribute("value", result[i][j]);
                                            text.style.display = "none";
                                            td.append(text);
                                            tr.append(td);
                                        }
                                        var td1 = document.createElement("td");
                                        var button1 = document.createElement("button");
                                        button1.setAttribute("id","dataTableTr"+i+"Ubtn");
                                        button1.setAttribute("class","button_50");
                                        button1.setAttribute("onclick","onUpdateDataFlag("+i+","+columnLength+");");
                                        button1.style.display = "block";
                                        button1.innerText = "수정";
                                        td1.append(button1);
                                        var button1 = document.createElement("button");
                                        button1.setAttribute("id","dataTableTr"+i+"UFbtn");
                                        button1.setAttribute("class","button_50");
                                        button1.setAttribute("onclick","onUpdateData("+i+","+columnLength+");");
                                        button1.style.display = "none";
                                        button1.innerText = "완료";
                                        td1.append(button1);
                                        tr.append(td1);

                                        var td2 = document.createElement("td");
                                        var button2 = document.createElement("button");
                                        button2.setAttribute("id","dataTableTr"+i+"Dbtn");
                                        button2.setAttribute("class","button_50");
                                        button2.setAttribute("onclick","onDeleteData("+i+","+columnLength+");");
                                        button2.style.display = "block";
                                        button2.innerText = "삭제";
                                        td2.append(button2);
                                        var button2 = document.createElement("button");
                                        button2.setAttribute("id","dataTableTr"+i+"UCbtn");
                                        button2.setAttribute("class","button_50");
                                        button2.setAttribute("onclick","onUpdateDataCancel("+i+","+columnLength+");");
                                        button2.style.display = "none";
                                        button2.innerText = "취소";
                                        td2.append(button2);
                                        tr.append(td2);
                                        $('#data_box').append(tr);
                                    }
                                }
                                var tr = document.createElement("tr");
                                var td = document.createElement("td");
                                tr.append(td);
                                for(var i = 1; i < columnLength-1; i++) {
                                    var td = document.createElement("td");
                                    var text = document.createElement("input");
                                    text.setAttribute("type", "text");
                                    text.setAttribute("name", "newData");
                                    text.setAttribute("id", "dataTableIText"+i);
                                    text.style.display = "none";
                                    td.append(text);
                                    tr.append(td);
                                }
                                var td = document.createElement("td");
                                tr.append(td);
                                var td = document.createElement("td");
                                var button = document.createElement("button");
                                button.setAttribute("id","dataTableIbtn");
                                button.setAttribute("class","button_50");
                                button.setAttribute("onclick","onInsertDataFlag("+columnLength+");");
                                button.style.display = "block";
                                button.innerText = "생성";
                                td.append(button);
                                var button = document.createElement("button");
                                button.setAttribute("id","dataTableIFbtn");
                                button.setAttribute("class","button_50");
                                button.setAttribute("onclick","onInsertData();");
                                button.style.display = "none";
                                button.innerText = "완료";
                                td.append(button);
                                tr.append(td);
                                var td = document.createElement("td");
                                var button = document.createElement("button");
                                button.setAttribute("id","dataTableICbtn");
                                button.setAttribute("class","button_50");
                                button.setAttribute("onclick","onInsertDataCancel("+columnLength+");");
                                button.style.display = "none";
                                button.innerText = "취소";
                                td.append(button);
                                tr.append(td);
                                $('#data_box').append(tr);
                            }
                        };
                        xmlhttp2.open('GET', './sql/findDatas.php?x=' + dbParam, true); 
                        xmlhttp2.send();
                    }
                };
                xmlhttp.open('GET', './sql/findDataColumns.php?x=' + dbParam, true); 
                xmlhttp.send();

                
            }

            function onXmlHttpStatus(obj, url, func) {
                dbParam = JSON.stringify(obj); 
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        console.log(xmlhttp.responseText);
                        var result = JSON.parse(xmlhttp.responseText);
                        if(result.success == true) {
                            document.getElementById('displayStatus').style.color = "blue";
                            func();
                        }
                        else if(result.success == false) {
                            document.getElementById('displayStatus').style.color = "red";
                        }
                        document.getElementById('displayStatus').innerText = result.status;
                        
                    }
                };
                xmlhttp.open('GET', url+'?x=' + dbParam, true); 
                xmlhttp.send();
            }
        </SCRIPT>
        <?
        if(isset($jb_login) && $jb_login == true) {?>
                <SCRIPT>
                onFindAllTableNames();
                </SCRIPT>
        <?}
        ?>
    </BODY>
</HTML>
