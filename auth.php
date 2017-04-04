<?php
//$dsn = "mysql:dbname=stud_v17_gruppe2;host=kark.hin.no";
$dsn = "mysql:dbname=stud_v17_gruppe2;host=85.167.98.174:3306";
$user = "stud_v17_gruppe2";
$pswd = "gruppe2";

try {
    $db = new PDO($dsn, $user, $pswd);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
