<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'tilgangsfunksjoner.php';
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
$SysReg = new SystemRegister($db);
$aktivert = "";
session_start();

if(!isInnlogget()){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = isAktiv();
if(!isSystemAdmin() || !isAktiv()){
    header("Location: index.php?error=manglendeRettighet");
    return;
}

if(isset($_GET['error'])){
    $error = $_GET['error'];
}
$systemVariabler = $SysReg->hentSystemvariabel();
if(isset($_POST['submit'])){
    if(isset($_POST['tidsparameter'])) {
        if($systemVariabler[0]) {
            $SysReg->redigerSystemvariabel($_POST['tidsparameter']);
        }
        else {
            $SysReg->lagSystemvariabel($_POST['tidsparameter']);
        }
    }
    $systemVariabler = $SysReg->hentSystemvariabel(); //Oppdater hvis det blir gjort endringer
}

if(isset($_GET['action'])){
    if ($_GET['action'] == "factoryreset") {
        DbHelper::executeMySQLFile("sql/wipe.sql");
        DbHelper::executeMySQLFile("sql/factoryReset.sql");
        $alert = "Database har blitt fabrikkgjenoprettet. Du må logge inn på nytt.";
        $forceLogout = true;
        //return;
    }
    if ($_GET['action'] == "backup-050617") {
        DbHelper::executeMySQLFile("sql/wipe.sql");
        DbHelper::executeMySQLFile("sql/05-06-2017DataBaseDump.sql");
        $alert = "Database har blitt gjenopprettet. Du må logge inn på nytt.";
        $forceLogout = true;
        //return;
    }
}
if (isset($_POST['action']) && $_POST['action'] == "Gjenopprett backup") {
    define('FILENAME_TAG', "userfile");
    $file = $_FILES[FILENAME_TAG]['tmp_name'];
    $size = $_FILES[FILENAME_TAG]['size'];
    // VIKTIG !  sjekk at vi jobber med riktig fil
    if(is_uploaded_file($file) && $size != 0 && $size <= 10000000) {
        DbHelper::executeMySQLFile("sql/wipe.sql");
        DbHelper::executeMySQLFile($file);
        $alert = "Database har blitt gjenopprettet. Du må logge inn på nytt.";
        $forceLogout = true;
    }
}
//$alert = "asdfg";
echo $twig->render('SystemInnstillinger.html', array('aktivert'=>$aktivert, 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'systemVariabler'=>$systemVariabler[0],
'brukerTilgang'=>$_SESSION['brukerTilgang'], 'error'=>$error, 'alert'=>$alert, 'forceLogout'=>$forceLogout));

//echo $twig->render('index.html', array(
//    'error'=>$error));

?>
