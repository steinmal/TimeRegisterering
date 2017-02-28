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


session_start();


if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.html");
    return;
}

$prosjektId = 0;
if (isset($_REQUEST['prosjekt']))
    $prosjektId = $_REQUEST['prosjekt'];
$prosjekt = $ProsjektReg->hentProsjekt($prosjektId);
if ($prosjekt == null) {
    echo "Ugyldig prosjektID";
    return;
}
$OppgaveListe = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt->getId());


$prosjektliste = $ProsjektReg->hentAlleProsjekter($db);
$brukerliste =$UserReg->hentAlleBrukere();
$brukParent = true;
$valgtProsjekt = new Prosjekt();
/*
if(isset($_POST['opprettProsjekt'])){
    $nyttProsjekt = new Prosjekt();
    $nyttProsjekt->setProsjektNavn($_POST['prosjektNavn']);
    $nyttProsjekt->setProsjektParent($_POST['foreldreProsjekt']);
    $nyttProsjekt->setProsjektLeder($_POST['prosjektLeder']);
    $nyttProsjekt->setProsjektBeskrivelse($_POST['prosjektBeskrivelse']);
    $nyttProsjekt->setProsjektStartDato($_POST['startDato']);
    $nyttProsjekt->setProsjektSluttDato($_POST['sluttDato']);
    if(!isset($_POST['prosjektId'])){
        $ProsjektReg->lagProsjekt($nyttProsjekt);
        header("Location: prosjektadministrering.php");
    }
    else{
        $nyttProsjekt->setProsjektId($_POST['prosjektId']);
        $ProsjektReg->redigerProsjekt($nyttProsjekt);
        header("Location: prosjektadministrering.php");
    }
}
elseif(isset($_GET['action'])){
    switch($_GET['action']){
        case 'Opprett grunnprosjekt':
            $brukParent = false;
            $valgtProsjekt->setProsjektId(-1); // verdier < 0 tas ikke med videre
            break;
        case 'Rediger':
            if(!isset($_GET['prosjektId'])){
                header("Location: prosjektadministrering.php?error=noRadio");
            }
            $valgtProsjekt = $ProsjektReg->hentProsjekt($_GET['prosjektId']); //Noe lignende dette
            echo($valgtProsjekt->getId());
            break;
        case 'Opprett underprosjekt':
            $valgtProsjekt->setProsjektParent($_GET['prosjektId']);
            $valgtProsjekt->setProsjektId(-1);
            //$valgtProsjekt->setProsjektLeder($prosjektReg->hentProsjekt($_GET['prosjektId']).getProsjektLeder());
            //$valgtProsjekt->setProsjektId();
            break;
    }
}
*/



echo $twig->render('oppgaveoppretting.html', array('brukParent'=>$brukParent, 'valgtProsjekt'=>$prosjekt, 'valgtOppgave'=>$valgtOppgave,
                    'oppgaver'=>$oppgaveliste, 'oppgavetyper'=>$oppgavetyper, 'faser'=>$faser));
?>