<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$FaseReg = new FaseRegister($db);
$BrukerReg = new BrukerRegister($db);
$TeamReg = new TeamRegister($db);
$error = "";
$aktivert = "";


session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] != true){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true || !$_SESSION['bruker']->isAktivert()){
    header("Location: index.php?error=manglendeRettighet&side=pradm");
    return;
}

$prosjektId = 0;
if (isset($_REQUEST['prosjektId']))
    $prosjektId = $_REQUEST['prosjektId'];
$prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
if ($prosjekt == null) {
    header("Location: prosjektadministrering.php?error=ugyldigProsjekt");
    //echo "Ugyldig prosjektID";
    return;
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'Arkiver':
            /*$ProsjektReg->arkiverProsjekt($_GET['prosjektId']);
            break;*/
            if(!isset($_REQUEST['prosjektId'])){
                header("Location: prosjektadministrering.php?error=noRadio");
                return;
            }
            $prosjekt = $ProsjektReg->hentProsjekt($_REQUEST['prosjektId']);
            $oversikt = new ProsjektOversikt($prosjekt, $ProsjektReg, new FaseRegister($db), new OppgaveRegister($db), new TimeregistreringRegister($db));
            $ProsjektReg->arkiverProsjekt($prosjekt->getId());
            foreach($oversikt->getAlleUnderProsjekt(true) as $uProsjekt){
                if($uProsjekt->getStatus() == 0){
                    $ProsjektReg->arkiverProsjekt($uProsjekt->getId(), 2);
                }
            }
            $error = "arkivert";
            break;
        case 'Gjenopprett':
            /*$gjenopprett = true;
            $ProsjektReg->arkiverProsjekt($_GET['prosjektId'], $gjenopprett);
            break;*/
            if(!isset($_REQUEST['prosjektId'])){
                header("Location: prosjektadministrering.php?error=noRadio");
                return;
            }
            $prosjektTemp = clone $prosjekt;
            while($prosjektTemp->getParent() != 1){
                while($prosjektTemp->getStatus() != 1 && $prosjektTemp->getParent() != 1){
                    $prosjektTemp = $ProsjektReg->hentProsjekt($prosjektTemp->getParent());
                }
                $oversikt = new ProsjektOversikt($prosjektTemp, $ProsjektReg, new FaseRegister($db), new OppgaveRegister($db), new TimeregistreringRegister($db));
                $oversikt->gjennopprett($ProsjektReg);
                $prosjektTemp->setStatus(0);
                $prosjektTemp = $ProsjektReg->hentProsjekt($prosjektTemp->getParent());
            }
            if($prosjektTemp->getStatus() != 0){
                $oversikt = new ProsjektOversikt($prosjektTemp, $ProsjektReg, new FaseRegister($db), new OppgaveRegister($db), new TimeregistreringRegister($db));
                $oversikt->gjennopprett($ProsjektReg);
            }
            $error = "gjennopprettet";
            //$ProsjektReg->arkiverProsjekt($_GET['prosjektId'], 0);
    }
    //header("Location: prosjektdetaljer.php?prosjektId=".$prosjektId."&error=$error");
    //return;
    //Reload project
    if (isset($_REQUEST['visProsjekt'])) {
        $prosjektId = $_REQUEST['visProsjekt'];
    }
    $prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
    if ($prosjekt == null) {
        header("Location: prosjektadministrering.php?error=ugyldigProsjekt");
        //echo "Ugyldig prosjektID";
        return;
    }
}

$OppgaveListe = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt->getId());
$FaseListe = $FaseReg->hentAlleFaser($prosjekt->getId());
$TimeregReg = new TimeregistreringRegister($db);

$prosjektOversiktRoot = new ProsjektOversikt($prosjekt, $ProsjektReg, $FaseReg, $OppgaveReg, $TimeregReg, ProsjektOversikt::$OT_TIMER);
$parentProsjekt = null;
if ($prosjekt->getParent()) {
    $parentProsjekt = $ProsjektReg->hentProsjekt($prosjekt->getParent());
}

$aktivert = $_SESSION['bruker']->isAktivert();

var_dump($_SESSION['brukerTilgang']);
$brukerKanRedigere = ($_SESSION['brukerTilgang']->isProsjektadmin() || $_SESSION['bruker']->getId() == $TeamReg->hentTeam($prosjekt->getTeam())->getLeder()); //hvis brukerID == prosjekt->getLeder eller prosjekt->getTeam->getLeder

echo $twig->render('prosjektdetaljer.html', 
                   array('aktivert'=>$aktivert, 
                   'innlogget'=>$_SESSION['innlogget'], 
                   'TeamReg'=>$TeamReg, 
                   'bruker'=>$_SESSION['bruker'], 
                   'prosjekt'=>$prosjekt, 
                   'oppgavereg'=>$OppgaveReg, 
                   'brukerReg'=>$BrukerReg,
                   'faseliste'=>$FaseListe, 
                   'oppgaveliste'=>$OppgaveListe, 
                   'brukerTilgang'=>$_SESSION['brukerTilgang'], 
                   'error'=>$error, 
                   "prosjektOversiktRoot"=>$prosjektOversiktRoot->getOversiktListe(), 
                   'parentProsjekt'=>$parentProsjekt,
                   'brukerKanRedigere'=>$brukerKanRedigere));

?>