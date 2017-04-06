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
$UserReg = new UserRegister($db);
$TeamReg = new TeamRegister($db);
$error = "";


session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] != true){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    header("Location: index.php?error=manglendeRettighet&side=pradm");
    //echo "Du har ikke tilgang til prosjektadministrering";
    return;
}

$prosjektId = 0;
if (isset($_REQUEST['prosjekt']))
    $prosjektId = $_REQUEST['prosjekt'];
    var_dump($prosjektId);
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

echo $twig->render('prosjektdetaljer.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'prosjekt'=>$prosjekt, 'oppgavereg'=>$OppgaveReg, 'faseliste'=>$FaseListe, 'oppgaveliste'=>$OppgaveListe, 'brukerTilgang'=>$_SESSION['brukerTilgang'], 'error'=>$error));

?>