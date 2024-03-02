<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;
$status = "";

$tableName = $obj->tableName;
$thisColumnName = $obj->thisColumnName;
$foreignTableName = $obj->foreignTableName;
$foreignColumnName = $obj->foreignColumnName;

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 

$query = "alter table $tableName add foreign key($thisColumnName) references $foreignTableName($foreignColumnName) on update cascade;";
try{
    $pdo->exec($query);
    $status = "Foreign Key Set Successfully";    
    $success = true;
}catch(Exception $e) {
   $status= "Fail to Set Foreign Key!";
   $success = false;
}

$outp = array('success' => $success, 'status'=> $status);

echo json_encode($outp); 
?>
