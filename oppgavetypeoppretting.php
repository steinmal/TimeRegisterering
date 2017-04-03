<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$UserReg = new UserRegister($db);
$OppgaveTypeReg = new OppgaveRegister($db);
$error = "";
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

if((!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isBrukeradmin() != true)
    && $_REQUEST['brukerId'] != $_SESSION['bruker']->getId()){
    echo "Du har ikke tilgang til Brukerredigering";
    //Foreslår returnering til index.php?error=noAccess eller lignende
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

echo $twig->render('oppgavetypeoppretting.html', array('oppgavetype'=>$oppgavetype, 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'],  'error'=>$error, 'userReg'=>$UserReg, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
