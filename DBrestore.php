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
$alert = "";
$forceLogout = false;
session_start();

if($_SESSION['innlogget'] && $_SESSION['brukerTilgang']->isBrukeradmin()) {
    
    if(isset($_GET['action'])){
        if ($_GET['action'] == "factoryreset") {
            DbHelper::executeMySQLFile("sql/wipe.sql");
            DbHelper::executeMySQLFile("sql/factoryReset.sql");
            $alert = "Database har blitt fabrikkgjenoprettet. Du må logge inn på nytt.";
            $forceLogout = true;
            //return;
        }
        if ($_GET['action'] == "backup-220417") {
            DbHelper::executeMySQLFile("sql/wipe.sql");
            DbHelper::executeMySQLFile("sql/22-04-2017DataBaseDump.sql");
            $alert = "Database har blitt gjenopprettet. Du må logge inn på nytt.";
            $forceLogout = true;
            //return;
        }
    }
    //$alert = "asdfg";
    echo $twig->render('DBrestore.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'],
    'brukerTilgang'=>$_SESSION['brukerTilgang'], 'noRadio'=>$noRadio, 'deaktivertError'=>$deaktivertError, 'error'=>$error, 'alert'=>$alert, 'forceLogout'=>$forceLogout));

}
else {
    header("Location: index.php?error=manglendeRettighet");
}
//echo $twig->render('index.html', array(
//    'error'=>$error));

?>
