<?php
session_start();
unset($_SESSION['innlogget']);
unset($_SESSION['bruker']);
unset($_SESSION['brukerTilgang']);
session_destroy();
header("Location: index.php");
?>
