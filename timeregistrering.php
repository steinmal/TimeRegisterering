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

if(isset($_SESSION['innlogget']) && $_SESSION['innlogget'] == true) {
    $innlogget = $_SESSION['innlogget']; //Gjør denne noe?
} else {
    header("Location: index.php");
    return;
}

date_default_timezone_set('Europe/Oslo');

if(isset($_POST['registrer'])) {
    $bruker = $_SESSION['bruker'];
    $bruker_id = $bruker->getBrukerId();
    $oppgave_id = $_POST['oppgave'];
    $dato = $_POST['dato'];
    $starttid = DateTime::createFromFormat('H:i', $_POST['starttid']);
    $stopptid = DateTime::createFromFormat('H:i', $_POST['stopptid']);
    $starttidStr = $starttid->format("H:i:s");
    $stopptidStr = $stopptid->format("H:i:s");
    $automatisk = isset($_POST['automatisk']) ? 1 : 0;
    $TimeReg->lagTimeregistrering($oppgave_id, $bruker_id, $dato, $starttidStr, $stopptidStr, $automatisk);

    echo "Timeregistrering OK";
}


$prosjekter = $ProsjektReg->hentAlleProsjekter();

if(isset($_POST['prosjekt'])) {
    $prosjekt_id = $_POST['prosjekt'];
    $oppgaver = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt_id);
}

$brukernavn = $_SESSION['bruker']->getBrukerNavn();

echo $twig->render('timeregistrering.html', array('prosjekter'=>$prosjekter, 'oppgaver'=>$oppgaver, 'brukernavn'=>$brukernavn, 'dagensdato'=>date("Y-m-d"), 'klokkeslett'=>date('H:i'), 'valgtProsjekt'=>$prosjekt_id));
?>
