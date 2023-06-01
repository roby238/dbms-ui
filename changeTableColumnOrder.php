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
$beforeOrderColumnName = $obj->beforeOrderColumnName;

$status = "";

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 
if($tableColumnSize == null || $tableColumnSize == "" || $tableColumnSize == "null"){ 
    $query = "ALTER TABLE $tableName MODIFY COLUMN 
    $tableColumnName $tableColumnType AFTER $beforeOrderColumnName;";
}
else{
    $query = "ALTER TABLE $tableName MODIFY COLUMN 
    $tableColumnName $tableColumnType($tableColumnSize) AFTER $beforeOrderColumnName;";
}
try{    
    $pdo->exec($query);
    $status = "Table Column Changed successfully";
    $success = true;    
}catch(Exception $e) {
    $status = "Table Column Change Failed";
    $success = false;   
}

$outp = array('success' => $success, 'status' => $status);

echo json_encode($outp); 
?>