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
    header("Location: index.php");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isProsjektadmin() != true){
    echo "Du har ikke tilgang til prosjektadministrering";
    return;
}

$prosjektliste = $ProsjektReg->hentAlleProsjekt($db);
$brukerliste =$UserReg->hentAlleBrukere();
$brukParent = true;
$valgtProsjekt = new Prosjekt();
//echo(count($brukerliste));
//echo($brukerliste[0]);
if(isset($_POST['opprettProsjekt'])){
    $nyttProsjekt = new Prosjekt();
    $nyttProsjekt->setNavn($_POST['prosjektNavn']);
    $nyttProsjekt->setParent($_POST['foreldreProsjekt']);
    $nyttProsjekt->setLeder($_POST['prosjektLeder']);
    $nyttProsjekt->setBeskrivelse($_POST['prosjektBeskrivelse']);
    $nyttProsjekt->setStartDato($_POST['startDato']);
    $nyttProsjekt->setSluttDato($_POST['sluttDato']);
    if(!isset($_POST['prosjektId'])){
        $ProsjektReg->lagProsjekt($nyttProsjekt);
        header("Location: prosjektadministrering.php");
        return;
    }
    else{
        $nyttProsjekt->setId($_POST['prosjektId']);
        $ProsjektReg->redigerProsjekt($nyttProsjekt);
        header("Location: prosjektadministrering.php");
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
            //$valgtProsjekt->setProsjektLeder($prosjektReg->hentProsjekt($_GET['prosjektId']).getProsjektLeder());
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


echo $twig->render('prosjektoppretting.html', array('innlogget'=>$_SESSION['innlogget'], 'bruker'=>$_SESSION['bruker'], 'brukParent'=>$brukParent, 'valgtProsjekt'=>$valgtProsjekt, 'prosjekter'=>$prosjektliste, 'brukere'=>$brukerliste, 'brukerTilgang'=>$_SESSION['brukerTilgang']));
?>