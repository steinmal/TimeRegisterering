<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
require_once('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$BrukerReg = new BrukerRegister($db);
$error="";
$innlogget = 0;
$bruker = "";
$brukerTilgang = "";
session_start();


if (isset($_GET['token'])) {
    $aktiveringskode = $_GET['token'];
    if (strlen($aktiveringskode) == 40
            && ctype_alnum($aktiveringskode) //Kun bokstaver og tall
            && $BrukerReg->aktiverBrukerMedAktiveringskode($aktiveringskode)) {
        echo "Bruker aktivert";
        return;
    }
}

echo "Aktivering feilet";

?>
