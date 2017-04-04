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
//$UserReg = new UserRegister($db);
//$TeamReg = new TeamRegister($db);
$rapportType = "";

session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] != true){
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || !$_SESSION['brukerTilgang']->isProsjektadmin()){
    echo "Du har ikke tilgang til prosjektrapporter<br/>";
    //header-relokasjon med feilmelding eller en egen feilmeldingstemplate?
    return;
}

if(!isset($_GET['prosjektId'])){
    header("Location: prosjektadministrering.php");
    return;
}
if(isset($_GET['rapportType'])){
    $rapportType = $_GET['rapportType'];
}
$prosjekt = $ProsjektReg->hentProsjekt($_GET['prosjektId']);
$twigs = array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'prosjekt'=>$prosjekt, 'type'=>$rapportType);

$TimeregReg = new TimeregistreringRegister($db);
$FaseReg = new FaseRegister($db);
//$rapportProsjekt = new RapportProsjekt($ProsjektReg, $OppgaveReg, $TimeregReg, $prosjekt);
$oversikt = new ProsjektOversikt($prosjekt, $ProsjektReg, $FaseReg, $OppgaveReg, $TimeregReg);

//Type kan slås sammen med rapportType

$type = 'team';
if(isset($_GET['rapportType'])){ $type = $_GET['rapportType']; }
switch ($type) {
    case 'team':
        break;
    case 'prosjekt':
        /*$grunnProsjekt = $prosjekt;
        $underProsjekt = $ProsjektReg->hentUnderProsjekt($prosjekt->getId());*/
        $twigs['oversiktListe'] = $oversikt->getOversiktListe();
        $twigs['oppgaveTyper'] = $OppgaveReg->getAlleOppgavetyper();
        break;
    case 'oppgave':
        
        break;
}

echo $twig->render('prosjektrapport.html', $twigs);