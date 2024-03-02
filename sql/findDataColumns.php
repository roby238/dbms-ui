<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;
$tableName = $obj->tableName;

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 

$query = "SELECT column_name from INFORMATION_SCHEMA.columns 
where table_schema = :dbName and table_name = :tableName order by ordinal_position;";
try{
    $stmt = $pdo->prepare($query);
    $stmt -> bindValue('dbName', $dbName);
    $stmt -> bindValue('tableName', $tableName);
    $stmt -> execute();
    $outp = $stmt->fetchAll(PDO::FETCH_ASSOC);    
}catch(Exception $e) {
}

echo json_encode($outp); 
?>
