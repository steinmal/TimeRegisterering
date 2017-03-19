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


if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    //Sjekk om brukeren er prosjektadmin eller teamleder for dette prosjektet
    echo "Du har ikke tilgang til faseoppretting";
    return;
}

if(!isset($_REQUEST['prosjektId'])){
    header("Location: faseadministrering.php");
    return;
}
$prosjektId = $_REQUEST['prosjektId'];

if(isset($_POST['lagre'])){
    $nyFase = new Fase();
    $nyFase->setProsjektId($prosjektId);
    $nyFase->setFaseNavn($_POST['faseNavn']);
    $nyFase->setFaseStartDato($_POST['faseStartdato']);
    $nyFase->setFaseSluttDato($_POST['faseSluttdato']);
    $nyFase->setFaseTilstand($_POST['faseTilstand']);
    if(isset($_POST['faseId'])){ //Redigering dersom id er satt på forhånd
        $nyFase->setFaseId($_POST['faseId']);
        $FaseReg->redigerFase($nyFase);
        //header("Location: faseadministrering.php");
        header("Location: prosjektdetaljer.php?prosjekt=" . $prosjektId);
        return;
    }
    else{
        $FaseReg->lagFase($nyFase);
        //header("Location: faseadministrering.php");
        header("Location: prosjektdetaljer.php?prosjekt=" . $prosjektId);
    }
}
else{
    $prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
    /*if(isset($_GET['rediger'])){
        if(!isset($_GET['faseId'])){
            header("Location: faseadministrering.php?error=noRadio");
            return;
        }*/
    if(isset($_GET['faseId'])){
        $fase = $FaseReg->hentFase($_GET['faseId']);
    }
    else{
        $fase = new Fase();
        $fase->setFaseId(-1);
    }
}

echo $twig->render('faseoppretting.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'prosjekt'=>$prosjekt, 'fase'=>$fase, 'fasetilstander'=>Fase::$tilstander, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>