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
    //echo "Du har ikke tilgang til prosjektadministrering";
    return;
}

$prosjektId = 0;
if (isset($_REQUEST['prosjektId']))
    $prosjektId = $_REQUEST['prosjektId'];
   // var_dump($prosjektId);
$prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
if ($prosjekt == null) {
    header("Location: prosjektadministrering.php?error=ugyldigProsjekt");
    //echo "Ugyldig prosjektID";
    return;
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
$OppgaveListe = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt->getId());
$FaseListe = $FaseReg->hentAlleFaser($prosjekt->getId());

$aktivert = $_SESSION['bruker']->isAktivert();

//var_dump($prosjekt);

echo $twig->render('prosjektdetaljer.html', array('aktivert'=>$aktivert, 'innlogget'=>$_SESSION['innlogget'], 'TeamReg'=>$TeamReg, 'bruker'=>$_SESSION['bruker'], 'prosjekt'=>$prosjekt, 'oppgavereg'=>$OppgaveReg, 'faseliste'=>$FaseListe, 'oppgaveliste'=>$OppgaveListe, 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'error'=>$error));

?>