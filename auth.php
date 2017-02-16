<?php
$dsn = "mysql:dbname=stud_v17_gruppe2;host=kark.hin.no";
$user = "stud_v17_gruppe2";
$pswd = "gruppe2";

try {
    $db = new PDO($dsn, $user, $pswd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES latin1 COLLATE latin1_general_ci"));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "OK";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>