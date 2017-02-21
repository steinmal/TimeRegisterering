<?php
session_start();
unset($_SESSION['innlogget']);
unset($_SESSION['bruker']);
session_destroy();
header("Location: index.php");
?>
