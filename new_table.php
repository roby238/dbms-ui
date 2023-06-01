<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
    <HEAD>
        <TITLE>Create Table</TITLE>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php 
            include 'log_head.php';
        ?>
    </HEAD>
    <BODY>
        <input type = 'hidden' id = 'column_number' value = '0'>
        <div style = "display: flex; justify-content: center">
            CREATE TABLE 
        </div>
        <div style = "display: flex; justify-content: center">   
            <div id = 'new_table_info'>
                table name: <br>
                <input type = 'text' id = 'new_table_name' name = 'new_table_name' placeholder = "table name"><br>
                table column: <br>
            </div>
            <div>
                <input type = 'button' value = '컬럼 추가' onclick = 'on_create_column()'>
                <input type = 'button' value = '테이블 생성' onclick = 'on_create_finish()'>
            </div>
        </div>

        <SCRIPT>
            function on_create_column(){
                var column_number = parseInt(document.getElementById('column_number').value);
                column_number++;
                document.getElementById('column_number').value = column_number;
                let parent_element1 = document.getElementById('new_table_info');
                let child_element1 = document.createElement('input');
                child_element1.setAttribute("type", "text");
                child_element1.setAttribute("id", "new_table_column_name" + column_number);
                child_element1.setAttribute("name", "new_table_column_name" + column_number);
                child_element1.setAttribute("placeholder", "name");
                parent_element1.appendChild(child_element1);

                let parent_element2 = document.getElementById('new_table_info');
                let child_element2 = document.createElement('input');
                child_element2.setAttribute("type", "text");
                child_element2.setAttribute("id", "new_table_column_type" + column_number);
                child_element2.setAttribute("name", "new_table_column_type" + column_number);
                child_element2.setAttribute("placeholder", "type(INT, VARCHAR..)");
                parent_element2.appendChild(child_element2);

                let parent_element3 = document.getElementById('new_table_info');
                let child_element3 = document.createElement('input');
                child_element3.setAttribute("type", "text");
                child_element3.setAttribute("id", "new_table_column_size" + column_number);
                child_element3.setAttribute("name", "new_table_column_size" + column_number);
                child_element3.setAttribute("placeholder", "size");
                parent_element3.appendChild(child_element3);

                let parent_element4 = document.getElementById('new_table_info');
                let child_element4 = document.createElement('br');
                parent_element4.appendChild(child_element4);
            }

            function on_create_finish(){
                var column_number = parseInt(document.getElementById('column_number').value);
                var new_table_column_series = "";
                for(var i = 1; i <= column_number; i++){
                    if( i == 1 ) {
                        new_table_column_series += document.getElementById('new_table_column_name' + i).value
                                                + "@" + document.getElementById('new_table_column_type' + i).value
                                                + "@" + document.getElementById('new_table_column_size' + i).value;
                    }
                    else {
                        new_table_column_series += "%" + document.getElementById('new_table_column_name' + i).value
                                                + "@" + document.getElementById('new_table_column_type' + i).value
                                                + "@" + document.getElementById('new_table_column_size' + i).value;
                    }
                }
                //console.log(column_number);
                console.log(new_table_column_series);
                //opener.document.create_cont.submit();
                
                var tableName = document.getElementById('new_table_name').value;
                var obj = {'dbUser': '<?=$sqlid?>', 'dbPasswd':'<?=$password?>', 'dbName':'<?=$dbid?>', 'tableName': tableName, 'columnSeries': new_table_column_series};
                dbParam = JSON.stringify(obj); 
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        console.log(xmlhttp.responseText);
                        var result = JSON.parse(xmlhttp.responseText);
                        
                        //opener.document.create_cont.tx_mode.value = 1;
                        //opener.document.create_cont.new_table_name.value = document.getElementById('new_table_name').value;
                        opener.document.getElementById('displayStatus').innerText = result.status;
                        opener.onFindAllTableNames();
                        opener.onForeignSetting();
                        window.close();  
                    }
                };
                xmlhttp.open('GET', './sql/createTable.php?x=' + dbParam, true); 
                xmlhttp.send();
            }
        </SCRIPT>
    </BODY>
</HTML>