<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;
$status = "";

$tableName = $obj->tableName;
$dataIdx = $obj->dataIdx;

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 

$query = "DELETE from $tableName where idx = :dataIdx";
try{
    $stmt = $pdo->prepare($query);
    $stmt -> bindValue(":dataIdx", $dataIdx);
    $stmt -> execute();
    $status = "Data Deleted Successfully";    
    $success = true;
}catch(Exception $e) {
   $status= "Fail to Delete Data!";
   $success = false;
}

$outp = array('success' => $success, 'status'=> $status);

echo json_encode($outp); 
?>
<?
?>