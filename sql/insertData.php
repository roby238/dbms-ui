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
$newDateTime = $obj->newDateTime;

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 

$query = "INSERT into " . $tableName . "(" . @$columnArray[0];
for($i = 1; $i < sizeof($columnArray); $i++){ $query = $query . ", " . $columnArray[$i]; }
$query = $query . ", idate) values(?";//'$new_data_array[0]'
for($i = 1; $i < sizeof($columnArray); $i++){ $query = $query . ", ?";} //'$new_data_array[$i]'
$query = $query . ", '$newDateTime')";
try{
    $stmt = $pdo->prepare($query);
    for($i = 0; $i < sizeof($dataArray); $i++) { $stmt -> bindValue($i + 1, $dataArray[$i]); }
    $stmt -> execute();
    $status = "Data Inserted Successfully";    
    $success = true;
}catch(Exception $e) {
   $status= "Fail to Insert Data!";
   $success = false;
}

$outp = array('success' => $success, 'status'=> $status);

echo json_encode($outp); 
?>