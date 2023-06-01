<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;

$tableName = $obj->tableName;
$tableColumnName = $obj->tableColumnName;
$tableColumnType = $obj->tableColumnType;
$tableColumnSize = $obj->tableColumnSize;
$oldTableColumnName = $obj->oldTableColumnName;

$status = "";

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 
if($tableColumnSize == null || $tableColumnSize == "") $query = "ALTER TABLE $tableName CHANGE $oldTableColumnName $tableColumnName $tableColumnType;";
else $query = "ALTER TABLE $tableName CHANGE $oldTableColumnName $tableColumnName $tableColumnType($tableColumnSize);";

try{
    $pdo->exec($query);    
    $status = "Table Column Updated successfully";
    $success = true;    
}catch(Exception $e) {
    $status = "Table Column Update Failed";
    $success = false;   
}

$outp = array('success' => $success, 'status' => $status);

echo json_encode($outp); 
?>