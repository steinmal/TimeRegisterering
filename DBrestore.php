
<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});
require_once 'vendor/autoload.php';

session_start();
$error = "";

if($_SESSION['innlogget'] && $_SESSION['brukerTilgang']->isBrukeradmin()) {
    DbHelper::executeMySQLFile("sql/wipe.sql");
    DbHelper::executeMySQLFile("sql/factoryReset.sql");
}
else {
    header("Location: index.php?error=manglendeRettighet");
}
//echo $twig->render('index.html', array(
//    'error'=>$error));

?>
