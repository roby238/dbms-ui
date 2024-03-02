<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;

$tableName = $obj->tableName;
$tableColumnName = $obj->tableColumnName;

$status = "";

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 
$query = "ALTER TABLE $tableName DROP COLUMN $tableColumnName;";
try{    
    $pdo->exec($query);
    $status = "Table Column Dropped successfully";
    $success = true;    
}catch(Exception $e) {
    $status = "Table Column Drop Failed";
    $success = false;   
}

$outp = array('success' => $success, 'status' => $status);

echo json_encode($outp); 
?>