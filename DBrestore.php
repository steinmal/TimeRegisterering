<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$error="";
$innlogget = 0;
$bruker = "";
$brukerTilgang = "";
session_start();

if($_SESSION['innlogget'] && $_SESSION['brukerTilgang']->isBrukeradmin()) {
    
    if(isset($_GET['action'])){
        if ($_GET['action'] == "factoryreset") {
            DbHelper::executeMySQLFile("sql/wipe.sql");
            DbHelper::executeMySQLFile("sql/factoryReset.sql");
            return;
        }
        if ($_GET['action'] == "backup-220417") {
            DbHelper::executeMySQLFile("sql/wipe.sql");
            DbHelper::executeMySQLFile("sql/22-04-2017DataBaseDump.sql");
            return;
        }
    }
    echo $twig->render('DBrestore.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'],
    'brukerTilgang'=>$_SESSION['brukerTilgang'], 'noRadio'=>$noRadio, 'deaktivertError'=>$deaktivertError, 'error'=>$error));

}
else {
    header("Location: index.php?error=manglendeRettighet");
}
//echo $twig->render('index.html', array(
//    'error'=>$error));

?>
