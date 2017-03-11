<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$UserReg = new UserRegister($db);

session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php");
    return;
}

if((!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isBrukeradmin() != true)
        && $_REQUEST['brukerId'] != $_SESSION['bruker']->getBrukerId()){
    echo "Du har ikke tilgang til Brukerredigering";
    //Foreslår returnering til index.php?error=noAccess eller lignende
    return;
}

if(!isset($_REQUEST['brukerId'])){
    header("Location: brukeradministrering.php?error=noRadio");
    return;
}
if(isset($_REQUEST['action'])){
    $bruker = $UserReg->hentBruker($_REQUEST['brukerId']);
    switch ($_REQUEST['action']) {
        case 'Rediger':
            break;
        case 'Lagre':
            if($_SESSION['brukerTilgang']->isBrukeradmin()){
                $bruker->setBrukerNavn($_POST['navn']);
                $bruker->setBrukerType($_POST['type']);
            }
            echo $bruker->getBrukertype();
            $bruker->setBrukerEpost($_POST['epost']);
            $bruker->setBrukerTelefon($_POST['telefon']);
            
            $UserReg->redigerBruker($bruker);
            if($_SESSION['brukerTilgang']->isBrukeradmin()){
                header("Location: brukeradministrering.php?error=lagret");
            }
            else{
                header("Location: index.php?error=lagret");
            }
            return;
        case 'Bekreft':
            if($_POST['nytt_pass'] != $_POST['nytt_pass2']){
                header("Location: brukerredigering.php?action=Rediger&brukerId=" . $_REQUEST['brukerId'] . "&error=mismatch");
                return;
            }
            $bruker->setPassord($_POST['nytt_pass']);
            $UserReg->redigerBruker($bruker);
            break;
    }
}

$typer = $UserReg->getAlleBrukertyper();

echo $twig->render('brukerredigering.html', array('bruker'=>$bruker, 'error'=>$_GET['error'], 'typer'=>$typer, 'userReg'=>$UserReg, 'brukerTilgang'=>$_SESSION['brukerTilgang']));

?>