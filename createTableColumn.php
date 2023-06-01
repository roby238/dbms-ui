<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;

$tableName = $obj->tableName;
$newTableColumnName = $obj->newTableColumnName;
str_replace(" ", "", $newTableColumnName);
preg_replace("/[ #\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i", "", $newTableColumnName);
$newTableColumnType = $obj->newTableColumnType;
str_replace(" ", "", $newTableColumnType);
preg_replace("/[ #\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i", "", $newTableColumnType);
$newTableColumnSize = $obj->newTableColumnSize;
str_replace(" ", "", $newTableColumnSize);
preg_replace("/[ #\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i", "", $newTableColumnSize);
$beforeOrderColumnName = $obj->beforeOrderColumnName;

$status = "";

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 
if($newTableColumnSize == null) $query = "ALTER TABLE $tableName ADD $newTableColumnName $newTableColumnType AFTER $beforeOrderColumnName;";
else $query = "ALTER TABLE $tableName ADD $newTableColumnName $newTableColumnType($newTableColumnSize) AFTER $beforeOrderColumnName;";
try{    
    $pdo->exec($query);
    $status = "Table Column Created successfully";
    $success = true;    
}catch(Exception $e) {
    $status = "Table Column Create Failed";
    $success = false;   
}

$outp = array('success' => $success, 'status' => $status);

echo json_encode($outp); 
?>