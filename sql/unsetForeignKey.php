<?
$obj = json_decode($_GET["x"], false); 

$dbHost = "localhost";
$dbUser = $obj->dbUser;
$dbPasswd = $obj->dbPasswd;
$dbName = $obj->dbName;
$status = "";

$tableName = $obj->tableName;
$foreignColumn = $obj->foreignColumn;

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPasswd); 

$query = "select * from information_schema.table_constraints where table_name = '$tableName';";
try{
    $result = $pdo->query($query);
    $row = $result->fetchAll(PDO::FETCH_NUM);
    
    //echo json_encode($row);
    for($i = 0; $i < sizeof($row); $i++) {
        $option = $row[$i][2];
        //echo json_encode($option);
        if($option != "PRIMARY") {
            $query = "alter table $tableName drop constraint $option;";
        }
    }
    
    //print $query;
    try{
        $pdo->exec($query);
        $status = "Foreign Key UnSet Successfully";    
        $success = true;
        $outp = array('success' => $success, 'status'=> $status);
        echo json_encode($outp); 
    }catch(Exception $e) {
        //print $outp;
        $status= "Fail to UnSet Foreign Key!";
        $success = false;
        $outp = array('success' => $success, 'status'=> $status);
        echo json_encode($outp); 
    }
}catch(Exception $e) {
    $status= "No Constraint Options...";
    $success = false;
    $outp = array('success' => $success, 'status'=> $status);
    echo json_encode($outp); 
}
?>
