<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;

$oldTableName = $obj->oldTableName;
$newTableName = $obj->newTableName;

str_replace(" ", "", $oldTableName);
preg_replace("/[ #\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i", "", $oldTableName);
str_replace(" ", "", $newTableName);
preg_replace("/[ #\&\+\%@=\/\\\:;,\.'\"\^`~\|\!\?\*$#<>()\[\]\{\}]/i", "", $newTableName);

$status = "";

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 
$query = "ALTER TABLE ".$oldTableName." RENAME ".$newTableName.";";
try{    
    $pdo->exec($query);
    $status = "Table Name < " . $newTableName . " >Updated successfully";
    $success = true;    
}catch(Exception $e) {
    $status = "Table Name Update Failed";
    $success = false;   
}

$outp = array('success' => $success, 'status' => $status);

echo json_encode($outp); 
?>