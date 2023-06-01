<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;
$status = "";

$tableName = $obj->tableName;
str_replace(" ", "", $tableName);
preg_replace("/[ #\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i", "", $tableName);

$columnSeriesArray = [];
$columnSeriesRow = [];
$columnSeriesRow = explode("%", $obj->columnSeries);
for($i = 0; $i < sizeof($columnSeriesRow); $i++){
    $columnSeriesArray[$i] = explode("@", $columnSeriesRow[$i]);
}

$query = sprintf("CREATE TABLE %s(idx INT(6) AUTO_INCREMENT PRIMARY KEY", $tableName);
if($obj->columnSeries != null) {
    for($i = 0; $i < sizeof($columnSeriesRow); $i++) {
        $tableColumnName = $columnSeriesArray[$i][0];
        $tableColumnType = $columnSeriesArray[$i][1];
        $tableColumnSize = $columnSeriesArray[$i][2];
        str_replace(" ", "", $tableColumnName);
        preg_replace("/[ #\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i", "", $tableColumnName);
        str_replace(" ", "", $tableColumnType);
        preg_replace("/[ #\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i", "", $tableColumnType);
        str_replace(" ", "", $tableColumnSize);
        preg_replace("/[ #\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i", "", $tableColumnSize);

        if($columnSeriesArray[$i][2] == null) $query = $query . sprintf(", %s %s NOT NULL", $tableColumnName, $tableColumnType);
        else $query = $query . sprintf(", %s %s(%s) NOT NULL", $tableColumnName, $tableColumnType, $tableColumnSize);
    }
}
$query = $query . ", idate TIMESTAMP);";

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 

try{
    $pdo->exec($query);
    $status = "Table <" . $tableName . "> Created Successfully";    
    $success = true;
}catch(Exception $e) {
   $status= "Fail to Create Table: <" . $tableName.">";
   $success = false;
}

$outp = array('success' => $success, 'status'=> $status);

echo json_encode($outp); 
?>