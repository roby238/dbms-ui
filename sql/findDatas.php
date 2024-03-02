<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;
$tableName = $obj->tableName;

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 

$query = "SELECT * FROM $tableName ORDER BY idx;";
try{
    $result = $pdo->query($query);
    $outp = $result->fetchAll(PDO::FETCH_NUM);
}catch(Exception $e) {
}

echo json_encode($outp); 
?>
