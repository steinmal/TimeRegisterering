<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$UserReg = new UserRegister($db);
$TeamReg = new TeamRegister($db);


session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] != true){
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
var_dump($prosjekt);
var_dump($OppgaveReg);
$OppgaveListe = $OppgaveReg->hentOppgaverFraProsjekt($prosjekt->getId());

echo $twig->render('prosjektdetaljer.html', array('prosjekt'=>$Prosjekt, 'oppgavereg'=>$OppgaveReg, 'oppgaveliste'=>$OppgaveListe));

?>