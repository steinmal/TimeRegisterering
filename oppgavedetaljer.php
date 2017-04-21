<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$OppgaveReg = new OppgaveRegister($db);
$ProsjektReg = new ProsjektRegister($db);
$UserReg = new UserRegister($db);
$FaseReg = new FaseRegister($db);
$TeamReg = new TeamRegister($db);
$estimatListe = array();
$oppgave = "";
$error="";

session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    header("Location: index.php?error=manglendeRettighet&side=oppgdet");
    return;
}
if(isset($_GET['oppgave'])) {

    $oppgave_id = $_GET['oppgave'];
    $oppgave = $OppgaveReg->hentOppgave($oppgave_id);
    $brukerId = $_SESSION['bruker']->getId();
    $teamLederId = $TeamReg->hentTeam($ProsjektReg->hentProsjektFraFase($oppgave->getFaseId())->getTeam())->getLeder();
    if ($brukerId != $teamLederId) {
        header("Location: prosjektdetaljer.php?error=ugyldigOppgave&prosjekt=" . $ProsjektReg->hentProsjektFraFase($oppgave->getFaseId())->getId());
        return;
    }
    $estimatListe = $OppgaveReg->hentAlleEstimatForOppgave($oppgave_id);

    if(isset($_GET['accept'])){
        if(isset($_GET['estimat'])){
            $estimatId = $_GET['accept'];
            $estimat=$_GET['estimat'];
            $OppgaveReg->endreEstimatForOppgave($oppgave_id,$estimat);
            $OppgaveReg->slettEstimatForslag($estimatId);
            header("Location: oppgavedetaljer.php?oppgave=" . $oppgave_id);
            return;
        }
    }
    if(isset($_GET['reject'])){
        $estimatId=$_GET['reject'];
        $OppgaveReg->slettEstimatForslag($estimatId);
        header("Location: oppgavedetaljer.php?oppgave=" . $oppgave_id);
        return;

    }
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}





echo $twig->render('oppgavedetaljer.html', array('fasereg'=>$FaseReg,'oppgave'=>$oppgave, 'TeamReg'=>$TeamReg, 'estimatliste'=>$estimatListe, 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'userReg'=>$UserReg, 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'error'=>$error));
