<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$OppgaveReg = new OppgaveRegister($db);
$UserReg = new UserRegister($db);
$FaseReg = new FaseRegister($db);
$estimatListe = array();
$oppgave = "";

session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    echo "Du har ikke tilgang til oppgaveadministrering";
    return;
}
if(isset($_GET['oppgave'])) {

    $oppgave_id = $_GET['oppgave'];
    $oppgave = $OppgaveReg->hentOppgave($oppgave_id);
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






echo $twig->render('oppgavedetaljer.html', array('fasereg'=>$FaseReg,'oppgave'=>$oppgave, 'estimatliste'=>$estimatListe, 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'userReg'=>$UserReg, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
