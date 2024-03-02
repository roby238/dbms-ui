<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 

$query = "SELECT table_name FROM information_schema.tables WHERE table_schema = :dbName GROUP BY table_name;";
try{
    $stmt = $pdo->prepare($query);
    $stmt -> bindValue('dbName', $dbName);
    $stmt -> execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
}catch(Exception $e) {
}

$outp = array('tableName'=>$result);

echo json_encode($outp); 
?>
