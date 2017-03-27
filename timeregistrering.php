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
$TimeReg = new TimeregistreringRegister($db);
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

date_default_timezone_set('Europe/Oslo');

/*if(isset($_POST['registrer'])) {
    $bruker = $_SESSION['bruker'];
    $bruker_id = $bruker->getBrukerId();
    $oppgave_id = $_POST['oppgave'];
    $dato = $_POST['dato'];
    $starttid = DateTime::createFromFormat('H:i', $_POST['starttid']);
    $stopptid = DateTime::createFromFormat('H:i', $_POST['stopptid']);
    $starttidStr = $starttid->format("H:i:s");
    $stopptidStr = $stopptid->format("H:i:s");
    $automatisk = isset($_POST['automatisk']) ? 1 : 0;
    $prosjekt_id = $OppgaveReg->hentOppgave($oppgave_id)->getProsjektId();
    $oppgaver = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt_id);
    $TimeReg->lagTimeregistrering($oppgave_id, $bruker_id, $dato, $starttidStr, $stopptidStr, $automatisk);
    echo "Timeregistrering OK";
}*/

if(isset($_POST['submit'])){
    $id = $_POST['regId'];
    echo $_POST['submit'];
    switch($_POST['submit']){
        case 'Start':
            $TimeReg->startTimeReg($_POST['oppgave'], $_SESSION['bruker']->getId());
            break;
        case 'Pause':
            $TimeReg->pauserTimeReg($id);
            break;
        case 'Fortsett':
            $TimeReg->fortsettTimeReg($id);
            break;
        case 'Stopp':
            $TimeReg->stoppTimeReg($id);
            break;
    }
}
if(isset($_POST['forslag'])){
    $oppgaveId = $_POST['oppgave'];
    header("Location: nyttTidsestimat.php?oppgaveId=" . $oppgaveId);
    return;
}


$brukernavn = $_SESSION['bruker']->getBrukerNavn();
$registrering = $TimeReg->hentAktiveTimeregistreringer($_SESSION['bruker']->getId());
//$prosjekt = $ProsjektReg->hentAlleProsjekt();
if($registrering != null && sizeof($registrering) > 0){
    $registrering = $registrering[0];
    $oppgave = $OppgaveReg->hentOppgave($registrering->getOppgaveId());
    $prosjekt = $ProsjektReg->hentProsjektFraFase($oppgave->getFaseId());
    $prosjekt_id = $prosjekt->getId();
    echo $twig->render('timeregistrering.html', array( 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'aktiv'=>true, 'visSkjema'=>true, 'prosjekt'=>$prosjekt, 'oppgave'=>$oppgave, 'registrering'=>$registrering, 'brukernavn'=>$brukernavn, 'dagensdato'=>date("Y-m-d"), 'brukerTilgang'=>$_SESSION['brukerTilgang']));
}
else{
    if(isset($_POST['prosjekt'])) {
        $prosjekt_id = $_POST['prosjekt'];
        $oppgaveListe = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt_id);
    }
    $prosjektListe = $ProsjektReg->hentAlleProsjekt();
    $visSkjema = ($prosjekt_id > 0 && sizeof($oppgaveListe) > 0) ? true : false;
    echo $twig->render('timeregistrering.html', array( 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'aktiv'=>false, 'visSkjema'=>$visSkjema, 'prosjektListe'=>$prosjektListe, 'oppgaveListe'=>$oppgaveListe, 'brukernavn'=>$brukernavn, 'dagensdato'=>date("Y-m-d"), 'klokkeslett'=>date('H:i'), 'valgtProsjekt'=>$prosjekt_id, 'valgtOppgave'=>$oppgave_id, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
}



?>
