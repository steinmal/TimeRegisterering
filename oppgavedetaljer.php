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
$BrukerReg = new BrukerRegister($db);
$FaseReg = new FaseRegister($db);
$TeamReg = new TeamRegister($db);
$estimatListe = array();
$oppgave = "";
$error="";
$aktivert = "";

session_start();

if(!isInnlogget()){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = isAktiv();

if(!isTeamleder() || !$aktivert)){
    header("Location: index.php?error=manglendeRettighet&side=oppgdet");
    return;
}
if(isset($_GET['oppgaveId'])) {

    $oppgave_id = $_GET['oppgaveId'];
    $oppgave = $OppgaveReg->hentOppgave($oppgave_id);
    $fase = $FaseReg->hentFase($oppgave->getFaseId());
    $prosjekt = $ProsjektReg->hentProsjektFraFase($fase->getId());
    $team = $TeamReg->hentTeam($prosjekt->getTeam());
    $antallMedlemmer = $TeamReg->antallMedlemmerTeam($team->getId());
    $oppgaveType = $OppgaveReg->hentOppgaveTypeTekst($oppgave->getId());
    $regTid = $OppgaveReg->hentAktiveTimerPrOppgaveDesimal($oppgave->getId());
    $godkjentTid = $OppgaveReg->hentGodkjenteTimerPrOppgave($oppgave->getId());
    
    $brukerId = $_SESSION['bruker']->getId();
    $teawmLederId = $team->getLeder();
    //$teamLederId = $TeamReg->hentTeam($ProsjektReg->hentProsjektFraFase($oppgave->getFaseId())->getTeam())->getLeder();
    $prosjektlederId = $prosjekt->getLeder();
    //$prosjektlederId = $ProsjektReg->hentProsjektFraFase($oppgave->getFaseId())->getLeder();
    if ($brukerId != $teamLederId && $brukerId != $prosjektlederId && !isProsjektAdmin()) {
        header("Location: prosjektdetaljer.php?error=ugyldigOppgave&prosjektId=" . $ProsjektReg->hentProsjektFraFase($oppgave->getFaseId())->getId());
        return;
    }
    $estimatListe = $OppgaveReg->hentAlleEstimatForOppgave($oppgave_id);

    if(isset($_GET['accept'])){
        if(isset($_GET['estimat'])){
            $estimatId = $_GET['accept'];
            $estimat=$_GET['estimat'];
            $OppgaveReg->endreEstimatForOppgave($oppgave_id,$estimat);
            $OppgaveReg->slettEstimatForslag($estimatId);
            header("Location: oppgavedetaljer.php?oppgaveId=" . $oppgave_id);
            return;
        }
    }
    if(isset($_GET['reject'])){
        $estimatId=$_GET['reject'];
        $OppgaveReg->slettEstimatForslag($estimatId);
        header("Location: oppgavedetaljer.php?oppgaveId=" . $oppgave_id);
        return;

    }
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}





echo $twig->render('oppgavedetaljer.html', 
             array('aktivert'=>$aktivert, 
                   'fasereg'=>$FaseReg,
                   'oppgave'=>$oppgave, 
                   'TeamReg'=>$TeamReg, 
                   'estimatliste'=>$estimatListe, 
                   'innlogget'=>$_SESSION['innlogget'], 
                   'bruker'=>$_SESSION['bruker'], 
                   'brukerReg'=>$BrukerReg, 
                   'brukerTilgang'=>$_SESSION['brukerTilgang'], 
                   'error'=>$error,
                   'prosjekt'=>$prosjekt,
                   'fase'=>$fase,
                   'team'=>$team,
                   'antallMedlemmer'=>$antallMedlemmer,
                   'BrukerReg'=>$BrukerReg,
                   'oppgaveType'=>$oppgaveType,
                   'registrertTid'=>$regTid,
                   'godkjentTid'=>$godkjentTid));
