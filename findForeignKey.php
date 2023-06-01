<?php

$obj = json_decode($_GET["x"], false); 
// ① php에서 넘어온 JSON 요청을 PHP 객체로 변환

$conn = new mysqli("localhost", $obj->sqlUser, $obj->dbPasswd, $obj->dbUser); 
// ② DB 연결 (호스트,아이디,비번,DB명)

$stmt = $conn->prepare("select column_name, constraint_name from information_schema.key_column_usage where table_name = ? and referenced_table_name is not null;"); 

$stmt->bind_param("s", $obj->tableName); 
// ③ 요청 데이터로 ? 자리 채움.

$stmt->execute();

$result = $stmt->get_result();

$outp = $result->fetch_all(MYSQLI_ASSOC);

if($outp != null) {
    
}
echo json_encode($outp); 
// ④ PHP 객체를 JSON 객체로 반환

?>