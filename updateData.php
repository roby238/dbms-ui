<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;
$status = "";

$tableName = $obj->tableName;
$dataArray = $obj->dataArray;
$columnArray = $obj->columnArray;
$dataIdx = $obj->dataIdx;

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 

$query = "UPDATE $tableName SET ". $columnArray[0] . " = ?";
for($i = 1; $i < sizeof($columnArray); $i++){ $query = $query . ", ". $columnArray[$i] ." = ?"; }
$query = $query . " where idx = ?;";
try{
    $stmt = $pdo->prepare($query);
    for($i = 0; $i < sizeof($dataArray); $i++) { $stmt -> bindValue($i + 1, $dataArray[$i]); }
    $stmt -> bindValue(sizeof($dataArray) + 1, $dataIdx);
    $stmt -> execute();
    $status = "Data Updated Successfully";    
    $success = true;
}catch(Exception $e) {
   $status= "Fail to Update Data!";
   $success = false;
}

$outp = array('success' => $success, 'status'=> $status);

echo json_encode($outp); 
?>
