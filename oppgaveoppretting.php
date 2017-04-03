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
$OppgaveReg = new OppgaveRegister($db);


session_start();


if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    echo "Du har ikke tilgang til oppgaveoppretting";
    return;
}

//Forslag: Dersom prosjektid eksisterer, anta at brukeren skal fylle inn skjema for å opprette oppgave
//Hvis ikke, anta at skjema er ferdig utfyllt, men da må faseId være satt
//else: header(location...)

//ProsjektId = 0 har lite hensikt, tror databasen gir feilmelding i slikt tilfelle pga foreign key-forventninger
$prosjektId = 0;
if (isset($_REQUEST['prosjekt']))
    $prosjektId = $_REQUEST['prosjekt'];
$prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
if ($prosjekt == null) {
    echo "Ugyldig prosjektID";
    return;
}
$oppgaveTyper = $OppgaveReg->hentAlleOppgaveTyper();
$faser = $FaseReg->hentAlleFaser($prosjekt->getId());

//Benytt hentOppgave dersom en oppgave skal redigeres
//Forslag for oppretting av oppgaver: Gjør om "lagoppgave"-metoden til å ta inn et objekt av type Oppgave
//Bruk isåfall new Oppgave og fyll inn denne fra $_POST vha set-metodene.
$valgtOppgave = new Oppgave();

if(isset($_POST['opprettOppgave'])){
    if(!isset($_POST['faseId']) && $_POST['faseId'] <= 0){
        header("Location: oppgaveOppretting.php?prosjekt=" . $prosjektId . "&error=ingenFase");
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
    
    
    if(!isset($_POST['oppgaveId'])){
        $OppgaveReg->lagOppgave($foreldreId, $oppgaveTypeId, $faseId, $oppgaveNavn, $tidsestimat, $periode);
        header("Location: prosjektdetaljer.php?prosjekt=" . $prosjektId);
        return;
    }
    else{
        $nyttProsjekt->setId($_POST['prosjektId']);
        $ProsjektReg->redigerProsjekt($nyttProsjekt);
        header("Location: prosjektadministrering.php");
        return;
    }
}

echo $twig->render('oppgaveoppretting.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'valgtProsjekt'=>$prosjekt, 'valgtOppgave'=>$valgtOppgave,
                    'oppgavetyper'=>$oppgaveTyper, 'faser'=>$faser, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>