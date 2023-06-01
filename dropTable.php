<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;

$tableName = $obj->tableName;

$status = "";

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 
$query = sprintf("DROP TABLE %s", $tableName);
try{    
    $pdo->exec($query);
    $status = "Table Dropped successfully";
    $success = true;    
}catch(Exception $e) {
    $status = "Table Drop Failed";
    $success = false;   
}

$outp = array('status'=> $status);

echo json_encode($outp); 
?>