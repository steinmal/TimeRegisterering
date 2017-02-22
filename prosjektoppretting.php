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


if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] = false){
    header("Location: index.html");
}
else{
    //$prosjektliste = 
    //$prosjektliste = Prosjekt::hentAlleProsjekter($db);
    $brukerliste = User::hentAlleBrukere($db);
}


if(isset($_GET['action'])){
    switch($_GET['action']){
        case 'Opprett GrunnProsjekt':
            //$valgtProsjekt = new Prosjekt();
            break;
        case 'Rediger':
            //$valgtProsjekt = Prosjekt::hentProsjektById($_GET['prosjektId']); //Noe lignende dette
            break;
        case 'Opprett UnderProsjekt':
            break;
    }
}

echo $twig->render('prosjektoppretting.html', array('valgtProsjekt'=>$valgtProsjekt, 'prosjeker'=>$prosjektliste, 'brukere'=>$brukerliste));
?>