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
$TeamReg = new TeamRegister($db);
//$BrukerReg = new BrukerRegister($db);
$aktivert = "";
$prosjektStartSlutt = "";
$error = "";

session_start();

$aktivert = $_SESSION['bruker']->isAktivert();
if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if(!isset($_REQUEST['prosjektId'])){
    header("Location: prosjektadministrering.php?error=ingenProsjekt");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || !(($_SESSION['brukerTilgang']->isTeamleder() && $ProsjektReg) || $_SESSION['brukerTilgang']->isProsjektadmin())){
    //Sjekk om brukeren er prosjektadmin eller teamleder for dette prosjektet
    //echo "Du har ikke tilgang til faseoppretting";
    header("Location: index.php?error=manglendeRettighet&side=fasopp");
    return;
}

$prosjektId = $_REQUEST['prosjektId'];
if ($_SESSION['bruker']->getId() != $TeamReg->hentTeam($ProsjektReg->hentProsjekt($prosjektId)->getTeam())->getLeder() && $_SESSION['bruker']->getId() != $ProsjektReg->hentProsjekt($prosjektId)->getLeder()) {
    header("Location: prosjektdetaljer.php?error=ugyldigFase&prosjektId=" . $prosjektId);
    return;
}

if(isset($_POST['lagre'])){
    $prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
    if($_POST['faseStartdato'] < $prosjekt->getStartDato() || $_POST['faseSluttdato'] > $prosjekt->getSluttDato()) {
        header("Location: faseoppretting.php?error=datoUtenforProsjekt&prosjektId=" . $prosjektId);
        return;
    } if ($_POST['faseStartdato'] > $_POST['faseSluttdato']) {
        header("Location: faseoppretting.php?error=sluttEtterStart&prosjektId=" . $prosjektId);
        return;
    }
    $nyFase = new Fase();
    $nyFase->setProsjektId($prosjektId);
    $nyFase->setNavn($_POST['faseNavn']);
    $nyFase->setStartDato($_POST['faseStartdato']);
    $nyFase->setSluttDato($_POST['faseSluttdato']);
    $nyFase->setTilstand($_POST['faseTilstand']);
    if(isset($_POST['faseId'])){ //Redigering dersom id er satt på forhånd
        $nyFase->setId($_POST['faseId']);
        $FaseReg->redigerFase($nyFase);
        //header("Location: faseadministrering.php");
        header("Location: prosjektdetaljer.php?prosjektId=" . $prosjektId);
        return;
    }
    else{
        $FaseReg->lagFase($nyFase);
        //header("Location: faseadministrering.php");
        header("Location: prosjektdetaljer.php?prosjektId=" . $prosjektId);
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
        $fase->setId(-1);
    }
}

if (isset($_GET['error'])) {
    $error = $_GET['error'];
}

echo $twig->render('faseoppretting.html', 
            array('aktivert'=>$aktivert,
                  'innlogget'=>$_SESSION['innlogget'], 
                  'bruker'=>$_SESSION['bruker'], 
                  'TeamReg'=>$TeamReg, 
                  'prosjekt'=>$prosjekt, 
                  'fase'=>$fase, 
                  'fasetilstander'=>Fase::$tilstander, 
                  'brukerTilgang'=>$_SESSION['brukerTilgang'], 
                  'error'=>$error));
?>