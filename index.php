<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$BrukerReg = new BrukerRegister($db);
$TeamReg = new TeamRegister($db);
$TimeReg = new TimeregistreringRegister($db);
$ProsjektReg = new ProsjektRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$innlogget = false;
$loginFail="";
$bruker="";
$error="";
$side = "";
$brukerTilgang="";
$regSucc = "";
$aktivert = "";
session_start();


if(isset($_POST['login'])) {
    $brukernavn = $_POST['brukernavn'];
    $passord = $_POST['passord'];
    $BrukerReg->login($brukernavn, $passord);
    if(isset($_POST['fail'])){
        $loginfail = $_POST['fail'];
    }
}
if(isset($_POST['fail'])){
    $loginFail = $_POST['fail'];
}

if(isset($_GET['error'])) {
    $error = $_GET['error'];
} if (isset($_GET['side'])) {
    $side = $_GET['side'];
}
if(isset($_GET['regSucc'])) {
    $regSucc = $_GET['regSucc'];
}
if(isset($_SESSION['innlogget'])) {
    $innlogget = $_SESSION['innlogget'];
    $bruker = $_SESSION['bruker'];
    $brukerTilgang = $_SESSION['brukerTilgang'];
    $aktivert = $_SESSION['bruker']->isAktivert();
    
    //Timeregistrering, automatisk
    if(isset($_POST['submit'])){
        if($_POST['submit'] == "Start"){
            $timereg = $TimeReg->hentAktiveTimeregistreringer($_SESSION['bruker']->getId());
            if(sizeof($timereg) > 0){
                header("Location: index.php?error=alleredeAktivTimereg");
                return;
            }
            if(!isset($_POST['oppgave'])){
                header("Location: index.php?error=ugyldigOppgave&prosjekt=" . $_POST['prosjektId']);
                return;
            }
        } else {
            $id = 0;
            if(isset($_POST['timeregId']) && $_POST['timeregId'] == 0){
                header("Location: index.php?error=ugyldigTimereg");
                return;
            } else {
                $id = $_POST['timeregId'];
                $timereg = $TimeReg->hentTimeregistrering($id);
                if($timereg == null || $timereg->getBrukerId() != $_SESSION['bruker']->getId()) {  //Registreringen hører ikke til innlogget bruker
                    header("Location: index.php?error=ugyldigTimereg");
                    return;
                }
            }
        }
        switch($_POST['submit']){
            case 'Start':
                if (!isProsjektadmin()) {
                    $prosjekt = $ProsjektReg->hentProsjektFraOppgave($_POST['oppgave']);
                    $teamListe = $TeamReg->hentTeamIdFraBruker($_SESSION['bruker']->getId());
                    if (!in_array($prosjekt->getTeam(), $teamListe)) {
                        header("Location: index.php?error=ugyldigOppgave&prosjekt=" . $_POST['prosjektId']);
                        return;
                    }
                }
                $TimeReg->startTimeReg($_POST['oppgave'], $_SESSION['bruker']->getId());
                break;
            case 'Pause':
                if ($timereg->getStatus() == 0 || $timereg->getStatus() == 2)  { //status = start eller fortsett
                    $TimeReg->pauserTimeReg($id);
                } else {
                    header("Location: index.php?error=ugyldigPause");
                    return;
                }
                break;
            case 'Fortsett':
                if ($timereg->getStatus() == 1) { //status = pause
                    $TimeReg->fortsettTimeReg($id);
                } else {
                    header("Location: index.php?error=ugyldigFortsettelse");
                    return;
                }
                break;
            case 'Stopp':
                if ($timereg->getStatus() == 3) { //status = stopp
                    header("Location: index.php?error=ugyldigStopp");
                    return;
                }
                $TimeReg->stoppTimeReg($id);
                break;
            default:
                break;
        }
    }
    if(isset($_POST['forslag'])){
        $oppgaveId = $_POST['oppgave'];
        header("Location: nyttTidsestimat.php?oppgaveId=" . $oppgaveId);
        return;
    }
    $brukernavn = $_SESSION['bruker']->getNavn();
    $registrering = $TimeReg->hentAktiveTimeregistreringer($_SESSION['bruker']->getId());
    $prosjekt_id = 0;
    //$prosjekt = $ProsjektReg->hentAlleProsjekt();
    if($registrering != null && sizeof($registrering) > 0){     //aktiv timereg
        $registrering = $registrering[0];
        $oppgave = $OppgaveReg->hentOppgave($registrering->getOppgaveId());
        $prosjekt = $ProsjektReg->hentProsjektFraFase($oppgave->getFaseId());
        $prosjekt_id = $prosjekt->getId();
        echo $twig->render('index.html', array(
                    'loginFail'=>$loginFail,
                    'innlogget'=>$innlogget,
                    'bruker'=>$bruker,
                    'brukerTilgang'=>$brukerTilgang,
                    'error'=>$error,
                    'side'=>$side,
                    'TeamReg'=>$TeamReg,
                    'regSucc'=>$regSucc,
                    'aktivert'=>$aktivert,
                    'valgtOppgave'=>$oppgave->getId(),
                    'TeamReg'=>$TeamReg,
                    'aktiv'=>true,
                    'visOppgave'=>true,
                    'visSkjema'=>true,
                    'prosjekt'=>$prosjekt,
                    'oppgave'=>$oppgave,
                    'registrering'=>$registrering,
                    'brukernavn'=>$brukernavn,
                    'dagensdato'=>date("Y-m-d"),
                    'manuell'=>false));
    }
    else{
        $brukerID = $_SESSION['bruker']->getId();
        //$alleProsjekter = array();
        if ($_SESSION['brukerTilgang']->isProsjektadmin()) {
            $prosjektListe = $ProsjektReg->hentAlleProsjekt();
        } else { // Optimalisert kode!! Hurra!!!!
            $prosjektListe = $ProsjektReg->hentTeamProsjektFraBruker($_SESSION['bruker']->getId());
            $idArr = array();
            foreach($prosjektListe as $p){
                $idArr[] = $p->getId();
            }
            $children = array();
            while($children = $ProsjektReg->hentUnderProsjektFraListe($idArr)){
                $idArr = array();
                foreach($children as $c){
                    if(!isset($prosjektListe[$c->getId()])){
                        $prosjektListe[$c->getId()] = $c;
                        $idArr[] = $c->getId();
                    }
                }
            }
        }

        $oppgaveListe = array();
        if(isset($_POST['prosjektId'])) {
            if(!isset($prosjektListe[$_POST['prosjektId']])) {
                header("Location: index.php?error=ugyldigProsjekt");
                return;
            }
            $prosjekt_id = $_POST['prosjektId'];
            $oppgaveListe = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt_id);
        }
        
        $oppgave_id = 0;
        $tidsestimat = 0;
        $aktivTid = 0;
        if(isset($_POST['oppgave'])) {
            $oppgave_id = $_POST['oppgave'];
            $tidsestimat = $OppgaveReg->hentOppgave($oppgave_id)->getTidsestimat();
            $aktivTid = $OppgaveReg->hentAktiveTimerPrOppgaveDesimal($oppgave_id);
            $prosjekt_id = $_POST['prosjektId'];
        }
    
        $visOppgave = ($prosjekt_id > 0 && sizeof($oppgaveListe) > 0 || $prosjekt_id > 0 && $oppgave_id > 0) ? true : false;
        $visSkjema = $oppgave_id > 0 ? true : false; 
        $dagensdato = date('Y-m-d');
        $now = date('h:i:s');
        $sysReg = new SystemRegister($db);
        $sysVar = $sysReg->hentSystemvariabel();
        $ikkeLengerBak = date('Y-m-d', strtotime('-' . $sysVar[0]->getTidsparameter() . ' days'));
    
    if (!isset($sendt)) $sendt = ""; //Quickfix - usikker på funksjon
    echo $twig->render('index.html', array(
        'loginFail'=>$loginFail,
        'innlogget'=>$innlogget,
        'bruker'=>$bruker,
        'brukerTilgang'=>$brukerTilgang,
        'error'=>$error,
        'side'=>$side,
        'TeamReg'=>$TeamReg,
        'regSucc'=>$regSucc,
        'aktivert'=>$aktivert,
        'sendt'=>$sendt,
        'aktiv'=>false,
        'visOppgave'=>$visOppgave, 
        'prosjektListe'=>$prosjektListe, 
        'oppgaveListe'=>$oppgaveListe, 
        'brukernavn'=>$brukernavn, 
        'klokkeslett'=>date('H:i'), 
        'valgtProsjekt'=>$prosjekt_id, 
        'valgtOppgave'=>$oppgave_id, 
        'OppgaveReg'=>$OppgaveReg,
        'visSkjema'=>$visSkjema,
        'tidsestimat'=>$tidsestimat,
        'aktivTid'=>$aktivTid,
        'dagensdato'=>$dagensdato,
        'now'=>$now,
        'ikkeLengerBak'=>$ikkeLengerBak,
        'ProsjektReg'=>$ProsjektReg));
    }
} else {
    echo $twig->render('index.html', array(
    'loginFail'=>$loginFail,
    'innlogget'=>$innlogget,
    'bruker'=>$bruker,
    'brukerTilgang'=>$brukerTilgang,
    'error'=>$error,
    'side'=>$side,
    'TeamReg'=>$TeamReg,
    'regSucc'=>$regSucc));
}

?>
