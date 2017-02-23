<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);


session_start();


if(!isset($_SESSION['innlogget']) && $_SESSION['innlogget'] = true){
    header("Location: index.html");
}
else{
    $prosjektliste = $ProsjektReg->hentAlleProsjekter();
    
}

echo $twig->render('prosjektadministrering.html', array('register'=>$ProsjektReg, 'prosjektliste'=>$prosjektliste));

?>