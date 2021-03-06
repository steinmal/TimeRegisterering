<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'tilgangsfunksjoner.php';
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$BrukerReg = new BrukerRegister($db);
$ProsjektReg = new ProsjektRegister($db);
$FaseReg = new FaseRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$TeamReg = new TeamRegister($db);
$aktivert = "";
$valgtOppgave = "";
$tilstander = Oppgave::getTilstander();


session_start();


if(!isInnlogget()){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$bruker = $_SESSION['bruker'];
$tilgang = $_SESSION['brukerTilgang'];
$aktivert = isAktiv();

if(!isset($_REQUEST['prosjektId'])){
    header("Location: prosjektadministrering.php?error=ingenProsjekt");
    return;
}
$prosjektId = $_REQUEST['prosjektId'];
$prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
if ($prosjekt == null) {
    header('Location: prosjektadministrering.php?error=ugyldigProsjekt');
    return;
}

if(!(isTeamleder() || isProsjektadmin()))
{
    header("Location: index.php?error=manglendeRettighet&side=fasopp");
    return;
}

if (!(isProsjektadmin()
    || isProsjektTeamLeder($BrukerReg, $prosjektId)
    || isProsjektOwner($ProsjektReg, $prosjektId)))
{
    header('Location: prosjektdetaljer.php?error=ugyldigOppgaveAct&prosjektId=' . $prosjektId);
    return;
}

//Forslag: Dersom prosjektid eksisterer, anta at brukeren skal fylle inn skjema for å opprette oppgave
//Hvis ikke, anta at skjema er ferdig utfyllt, men da må faseId være satt
//else: header(location...)

$oppgaveTyper = $OppgaveReg->hentAlleOppgaveTyper();
$faser = $FaseReg->hentAlleFaser($prosjekt->getId());

//Benytt hentOppgave dersom en oppgave skal redigeres
//Forslag for oppretting av oppgaver: Gjør om "lagoppgave"-metoden til å ta inn et objekt av type Oppgave
//Bruk isåfall new Oppgave og fyll inn denne fra $_POST vha set-metodene.
if(isset($_GET['oppgaveId'])) {
    $valgtOppgave = $OppgaveReg->hentOppgave($_GET['oppgaveId']);
} else {
    $valgtOppgave = new Oppgave();
    if(isset($_REQUEST['fase']) && $_REQUEST['fase'] > 0){
        $valgtOppgave->setFaseId($_REQUEST['fase']);
    }
}


if(isset($_POST['opprettOppgave'])){
    if($valgtOppgave->getFaseId() <= 0){
        header("Location: oppgaveOppretting.php?prosjektId=" . $prosjektId . "&error=ingenFase");
        return;
    }
    $faseId = $_POST['fase'];
    $foreldreId = null;
    if(isset($_POST['foreldreId']) && $_POST['foreldreId'] != 0) {
        $foreldreId = $_POST['foreldreId'];
    }
    $oppgaveTypeId = $_POST['oppgavetype'];
    $oppgaveNavn = $_POST['oppgaveNavn'];
    $tidsestimat = $_POST['tidsestimat'];
    $periode = $_POST['periode'];
    $tilstand = $_POST['tilstand'];


    if(!isset($_POST['oppgaveId'])){
        $OppgaveReg->lagOppgave($foreldreId, $oppgaveTypeId, $faseId, $oppgaveNavn, $tidsestimat, $periode, $tilstand);
        header("Location: prosjektdetaljer.php?prosjektId=" . $prosjektId);
        return;
    }
    else{
        $OppgaveReg->redigerOppgave($_POST['oppgaveId'], $foreldreId, $oppgaveTypeId, $faseId, $oppgaveNavn, $tidsestimat, $periode, $tilstand);
        header("Location: prosjektdetaljer.php?prosjektId=" . $prosjektId);
        return;
    }
}


echo $twig->render('oppgaveoppretting.html', array(
        'aktivert'=>$aktivert,
        'innlogget'=>$_SESSION['innlogget'],
        'TeamReg'=>$TeamReg,
        'bruker'=>$_SESSION['bruker'],
        'valgtProsjekt'=>$prosjekt,
        'valgtOppgave'=>$valgtOppgave,
        'oppgavetyper'=>$oppgaveTyper,
        'faser'=>$faser,
        'tilstander'=>$tilstander,
        'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>