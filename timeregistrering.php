<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$TimeReg = new TimeregistreringRegister($db);
session_start();

if(isset($_SESSION['innlogget']) && $_SESSION['innlogget'] == true) {
    $innlogget = $_SESSION['innlogget'];
} else {
    header("Location: index.php");
    return;
}

if(isset($_POST['registrer'])) {
    $bruker = $_SESSION['bruker'];

    $bruker_id = $bruker->getBrukerId();
    $prosjekt_id = $_POST['prosjekt'];
    $dato = $_POST['dato'];
    $starttid = $_POST['starttid'];
    $stopptid = $_POST['stopptid'];
    $automatisk = isset($_POST['automatisk']) ? 1 : 0;
    /*
    echo $prosjekt_id;
    var_dump($prosjekt_id);
    $TimeReg->lagTimeregistrering($prosjekt_id, $bruker_id, $dato, $starttid, $stopptid, $automatisk);

    echo "Timereg OK";
    */
}


$prosjekter = $ProsjektReg->hentAlleProsjekter($db);
echo $twig->render('timeregistrering.html', array('prosjekter'=>$prosjekter));
?>
