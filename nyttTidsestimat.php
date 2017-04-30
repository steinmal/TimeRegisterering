<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$TeamReg = new TeamRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$ProsjektReg = new ProsjektRegister($db);
$FaseReg = new FaseRegister($db);
$error = "";
$aktivert = "";

session_start();


if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = $_SESSION['bruker']->isAktivert();
if(!isset($_SESSION['brukerTilgang']) || !$_SESSION['bruker']->isAktivert()){
    header("Location: index.php?error=manglendeRettighet&side=nyttEst");
    return;
}
if(isset($_GET['error'])){
    $error = $_GET['error'];
}

$oppgaveId = $_REQUEST['oppgaveId'];
$oppgave = $OppgaveReg->hentOppgave($oppgaveId);

$teamID = $ProsjektReg->hentProsjektFraFase($FaseReg->hentFase($oppgave->getId())->getId())->getTeam();
$brukerTeamIds = $TeamReg->hentTeamIdFraBruker($_SESSION['bruker']->getId());
$tilgang = false;
foreach ($brukerTeamIds as $tID) {
    if ($teamID == $tID) {
        $tilgang = true;
        break;
    }
}
if (! $tilgang) {
    header('Location: timeregistrering.php?error=ugyldigTimeEst');
    return;
}
if (isset($_POST['submit'])) {
    if(is_numeric($_POST['estimat'])){
        $estimat = $_POST['estimat'];
        $OppgaveReg->lagNyttEstimat($oppgaveId, $estimat, $_SESSION['bruker']);
        header("Location: timeregistrering.php?sendt=1");
        return;
    }
    else {
        header("Location: nyttTidsestimat.php?error=1");
    }

}


echo $twig->render('nyttTidsestimat.html', array('aktivert'=>$aktivert, 'oppgave'=>$oppgave, 'TeamReg'=>$TeamReg, 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'],
                     'error'=>$error, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>