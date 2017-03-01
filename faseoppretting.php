<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$FaseReg = new FaseRegister($db);
//$UserReg = new UserRegister($db);


session_start();


if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] = false){
    header("Location: index.html");
}
if(!isset($_REQUEST['prosjektId'])){
    header("Location: faseadministrering.php");
}

if(isset($_POST['lagre'])){
    $nyFase = new Fase();
    $nyFase->setProsjektId($_POST['prosjektId']);
    $nyFase->setFaseNavn($_POST['faseNavn']);
    $nyFase->setFaseStartDato($_POST['faseStartdato']);
    $nyFase->setFaseSluttDato($_POST['faseSluttdato']);
    if(isset($_POST['faseId'])){
        $nyFase->setFaseId($_POST['faseId']);
        $FaseReg->redigerFase($nyFase);
        header("Location: faseadministrering.php");
    }
    else{
        $FaseReg->lagFase($nyFase);
        header("Location: faseadministrering.php");
    }
}
else{
    $prosjekt = $ProsjektReg->hentProsjekt($_GET['prosjektId']);
    if(isset($_GET['rediger'])){
        if(!isset($_GET['faseId'])){
            header("Location: faseadministrering.php?error=noRadio");
        }
        $fase = $FaseReg->hentFase($_GET['faseId']);
    }
    else{
        $fase = new Fase();
        $fase->setFaseId(-1);
    }
}

echo $twig->render('faseoppretting.html', array('prosjekt'=>$prosjekt, 'fase'=>$fase));
?>