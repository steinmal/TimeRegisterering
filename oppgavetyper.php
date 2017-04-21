<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$UserReg = new UserRegister($db);
$OppgaveRegister = new OppgaveRegister($db);
$TeamRegister = new TeamRegister($db);
$error = "";
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if((!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isBrukeradmin() != true)
    && $_REQUEST['brukerId'] != $_SESSION['bruker']->getId()){
    header("Location: index.php?error=manglendeRettighet&side=optred");
    //echo "Du har ikke tilgang til oppgavetyperedigering";
    //Foreslår returnering til index.php?error=noAccess eller lignende
    return;
}
if(isset($_GET['error'])){
    $error = $_GET['error'];
}
$oppgavetyper= $OppgaveRegister->hentAlleOppgaveTyper();


echo $twig->render('oppgavetyper.html', array('oppgavetyper'=>$oppgavetyper, 'TeamReg'=>$TeamReg, 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'],  'error'=>$error, 'userReg'=>$UserReg, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
