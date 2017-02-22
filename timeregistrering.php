<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
session_start();

if(isset($_SESSION['innlogget'])) {
    $innlogget = $_SESSION['innlogget'];
} else {
    header("Location: index.php");
    return;
}

$prosjekter = Prosjekt::hentAlleProsjekter($db);

echo $twig->render('timeregistrering.html', array('prosjekter'=>$prosjekter));
?>
