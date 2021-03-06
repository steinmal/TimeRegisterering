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
$OppgaveTypeReg = new OppgaveRegister($db);
$TeamReg = new TeamRegister($db);
$error = "";
$aktivert ="";
session_start();

if(!isInnlogget()){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = isAktiv();
if(!isSystemAdmin() || !$aktivert){
    header("Location: index.php?error=manglendeRettighet&side=optopp");
    return;
}
if(isset($_GET['error'])){
    $error = $_GET['error'];
}
if(isset($_POST['lagre'])){
    $nyType = new Oppgavetype();
    $nyType->setNavn($_POST['oppgaveNavn']);
    if(isset($_POST['oppgavetypeId'])){
        $nyType->setId($_POST['oppgavetypeId']);
        $OppgaveTypeReg->redigerOppgaveType($nyType);
        header("Location: oppgavetyper.php");
        return;
    }
    else{
        $OppgaveTypeReg->lagOppgaveType($nyType->getNavn());
        header("Location: oppgavetyper.php");
    }
}
else {
    if(isset($_GET['oppgavetypeId'])){
        $oppgavetype = $OppgaveTypeReg->hentOppgaveType($_GET['oppgavetypeId']);

    }
    else{
        $oppgavetype = new Oppgavetype();
        $oppgavetype->setId(-1);
    }
}

echo $twig->render('oppgavetypeoppretting.html', array('aktivert'=>$aktivert, 'oppgavetype'=>$oppgavetype, 'TeamReg'=>$TeamReg, 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'],  'error'=>$error, 'brukerReg'=>$BrukerReg, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
