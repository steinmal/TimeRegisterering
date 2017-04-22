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
$error = "none";


session_start();


if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isProsjektadmin() != true){
    header("Location: index.php?error=manglendeRettighet&side=propp");
    return;
}


$brukerliste = $BrukerReg->hentAlleBrukere();
$valgtTeam = new Team();

if(!isset($_REQUEST['action'])){
    //header("Location: prosjektadministrering.php?error=ingenAction");
    echo "Ingen Action";
    return;
}
$action =  $_REQUEST['action'];
if(isset($_POST['opprettTeam'])){
    $nyttTeam = new Team();
    
    foreach(array('teamNavn', 'teamLeder') as $field) {
        if(!isset($_POST[$field]) || strcmp($_POST[$field], "") == 0) {
            header('Location: teamoppretting.php?error=ingenVerdi&felt=' . $field . '&action=' . $action);
            // REFACTOR: Ikke bruk header for reload, men sett $error og fiks if-else-logikk slik at skjemaet vises på nytt men med feilmelding
            return;
        }
    }
    
    $nyttTeam->setNavn($_POST['teamNavn']);
    $nyttTeam->setLeder($_POST['teamLeder']);

    $idString = isset($_POST['teamId']) ? ("&teamId=" . $_POST['teamdId']) : "";

    if(!isset($_POST['teamId'])){
        $TeamReg->lagTeam($nyttTeam);
        header("Location: teamadministrering.php?adminerror=lagret");
        return;
    }
    else{
        $nyttTeam->setId($_POST['teamId']);
        $TeamReg->redigerTeam($nyttTeam);
        header("Location: teamadministrering.php?adminerror=redigert");
        return;
    }
}
switch($_GET['action']){
    case 'Opprett nytt team':
        $valgtTeam->setId(-1); // verdier < 0 tas ikke med videre
        break;
    case 'Rediger':
        if(!isset($_GET['teamId'])){
            header("Location: teamadministrering.php?error=noRadio");
            return;
        }
        $valgtTeam = $TeamReg->hentTeam($_GET['teamId']); //Noe lignende dette
        break;
    default:
        break;
}

if(isset($_GET['error'])) {
    $error = $_GET['error'];
}

echo $twig->render('teamoppretting.html', array('error'=>$error, 'innlogget'=>$_SESSION['innlogget'], 'TeamReg'=>$TeamReg, 'action'=>$action, 'teamListe'=>$teamListe, 'bruker'=>$_SESSION['bruker'], 'brukParent'=>$brukParent, 'valgtTeam'=>$valgtTeam, 'brukere'=>$brukerliste, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>