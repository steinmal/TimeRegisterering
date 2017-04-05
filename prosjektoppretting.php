<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$UserReg = new UserRegister($db);
$TeamReg = new TeamRegister($db);
$error = "none";


session_start();


if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isProsjektadmin() != true){
    header("Location: index.php?error=manglendeRettighet");
    return;
}

$prosjektliste = $ProsjektReg->hentAlleProsjekt($db);
$brukerliste = $UserReg->hentAlleBrukere();
$teamListe = $TeamReg->hentAlleTeam();
$brukParent = true;
$valgtProsjekt = new Prosjekt();
if(isset($_POST['opprettProsjekt'])){
    $nyttProsjekt = new Prosjekt();
    
    foreach(array('prosjektNavn', 'prosjektLeder', 'team', 'prosjektBeskrivelse', 'startDato', 'sluttDato') as $field) {
        if(!isset($_POST[$field]) || strcmp($_POST[$field], "") == 0) {
            header('Location: prosjektoppretting.php?error=ingenVerdi&felt=' . $field);
            return;
        }
    }
    
    $nyttProsjekt->setNavn($_POST['prosjektNavn']);
    $nyttProsjekt->setParent($_POST['foreldreProsjekt']);
    $nyttProsjekt->setLeder($_POST['prosjektLeder']);
    $nyttProsjekt->setTeam($_POST['team']);
    $nyttProsjekt->setBeskrivelse($_POST['prosjektBeskrivelse']);
    
    $start = $_REQUEST['startDato'];
    $slutt = $_REQUEST['sluttDato'];
    if($start > $slutt){
        header("Location: prosjektoppretting.php?error=stoppEtterStart&prosjektId=" . $_POST['prosjektId']); 
        return;
    }
    $nyttProsjekt->setStartDato($start);
    $nyttProsjekt->setSluttDato($slutt);
    if(!isset($_POST['prosjektId'])){
        $ProsjektReg->lagProsjekt($nyttProsjekt);
        header("Location: prosjektadministrering.php?error=lagret");
        return;
    }
    else{
        $nyttProsjekt->setId($_POST['prosjektId']);
        $ProsjektReg->redigerProsjekt($nyttProsjekt);
        header("Location: prosjektadministrering.php?error=redigert");
        return;
    }
}
elseif(isset($_GET['action'])){
    switch($_GET['action']){
        case 'Opprett grunnprosjekt':
            $brukParent = false;
            $valgtProsjekt->setId(-1); // verdier < 0 tas ikke med videre
            break;
        case 'Rediger':
            if(!isset($_GET['prosjektId'])){
                header("Location: prosjektadministrering.php?error=noRadio");
                return;
            }
            $valgtProsjekt = $ProsjektReg->hentProsjekt($_GET['prosjektId']); //Noe lignende dette
            break;
        case 'Opprett underprosjekt':
            $valgtProsjekt->setParent($_GET['prosjektId']);
            $valgtProsjekt->setId(-1);
            //$valgtProsjekt->setProsjektLeder($prosjektReg->hentProsjekt($_GET['prosjektId']).getLeder());
            //$valgtProsjekt->setProsjektId();
            break;
        case 'Arkiver':
            if(!isset($_GET['prosjektId'])){
                header("Location: prosjektadministrering.php?error=noRadio");
            }
            $error = $ProsjektReg->arkiverProsjekt($_GET['prosjektId']);
            header("Location: prosjektadministrering.php?error=$error");
            return;
    }
}

if(isset($_GET['error'])) {
    $error = $_GET['error'];
}

echo $twig->render('prosjektoppretting.html', array('error'=>$error, 'innlogget'=>$_SESSION['innlogget'], 'teamListe'=>$teamListe, 'bruker'=>$_SESSION['bruker'], 'brukParent'=>$brukParent, 'valgtProsjekt'=>$valgtProsjekt, 'prosjekter'=>$prosjektliste, 'brukere'=>$brukerliste, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>