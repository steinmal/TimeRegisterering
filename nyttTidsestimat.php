<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$OppgaveReg = new OppgaveRegister($db);
$error = "";

session_start();


if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}
if(isset($_GET['error'])){
    $error = $_GET['error'];
}

$oppgaveId = $_REQUEST['oppgaveId'];
$oppgave = $OppgaveReg->hentOppgave($oppgaveId);
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


echo $twig->render('nyttTidsestimat.html', array('oppgave'=>$oppgave, 'innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'],
                     'error'=>$error, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>