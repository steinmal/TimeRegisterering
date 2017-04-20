<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});
require_once 'vendor/autoload.php';
require_once 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php';

include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$ProsjektReg = new ProsjektRegister($db);
$OppgaveReg = new OppgaveRegister($db);
$TimeReg = new TimeregistreringRegister($db);
$TeamReg = new TeamRegister($db);
$UserReg = new UserRegister($db);
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true){
    header("Location: index.php?error=manglendeRettighet&side=teamrapp");
    //echo "Kun teamleder har tilgang til TeamRapport";
    return;
}

date_default_timezone_set('Europe/Oslo');
$firstDayOfMonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
$lastDayOfMonth = mktime(0, 0, 0, date("m"), date("t"), date("Y"));

//$datefrom = date("Y-m-d", $firstDayOfMonth);          // Finne en standarddato som får med alle registreringer.   
//$dateto = date("Y-m-d", $lastDayOfMonth); 
$datefrom = date("Y-m-d", strtotime('1970-01-01'));          // Manuell, fjernes // Fikk en warning på denne, måtte sette strtotime().
$dateto = date("Y-m-d"); 

/* Følgende er ikke implementert enda... 
if (isset($_GET['daterange']) && strlen($_GET['daterange']) == 23) {
    $datefrom = substr($_GET['daterange'], 0, 10);
    $dateto = substr($_GET['daterange'], 13, 10);
}*/

$bruker = $_SESSION['bruker'];

$teamIDs = array();
$teamIDs = $TeamReg->getTeamIdFraTeamleder($bruker->getId());

$teams = array();
$brukerIds = array();
foreach ($teamIDs as $teamID) {
    $teams[] = $TeamReg->hentTeam($teamID);
    $brukerIdArray = array();
    $brukerIdArray = $TeamReg->getTeamMedlemmerId($teamID);
    foreach ($brukerIdArray as $brukerId) {
        $brukerIds[] = $brukerId;
    }
}

$timeregistreringer = array();
foreach($brukerIds as $brukerId) {
    $brukersTimeregistreringer = array();
    $brukersTimeregistreringer = $TimeReg->hentTimeregistreringerFraBruker($brukerId, $datefrom, $dateto);
    //$brukersTimeregistreringer = $TimeReg->hentTimeregistreringerFraBruker($brukerId);         // Finne en standarddato som får med alle registreringer. 
    foreach ($brukersTimeregistreringer as $timereg) {
        $timeregistreringer[] = $timereg;
    }
}

$ansatt = isset($_GET['ansatt'])?$_GET['ansatt']:"";
$oppgavetype = isset($_GET['oppgavetype'])?$_GET['oppgavetype']:"";

$twigArray = array('innlogget'=>$_SESSION['innlogget'],
    'bruker'=>$_SESSION['bruker'],
    'timeregistreringer'=>$timeregistreringer,
//    'brukernavn'=>$brukernavn, //brukes ikke
    'oppgavereg'=>$OppgaveReg,
    'teamReg'=>$TeamReg,
    'timeReg'=>$TimeReg,
    'userReg'=>$UserReg,
    'brukerIds'=>$brukerIds,
    'teams'=>$teams,
    'brukerTilgang'=>$_SESSION['brukerTilgang'],
    'ansatt'=>$ansatt,
    'oppgavetype'=>$oppgavetype
    );

if(isset($_GET['download'])){
    if ($ansatt){
        foreach($timeregistreringer as $key => $element) {
            if ($UserReg->hentBruker($element->getBrukerId())->getNavn() != $ansatt) {
                unset($timeregistreringer[$key]);
            }
        }
    }
    if ($oppgavetype){
        foreach($timeregistreringer as $key => $element) {
            if ($OppgaveReg->getOppgavetypeTekst($OppgaveReg->hentOppgave($element->getOppgaveId())->getType()) != $oppgavetype) {
                unset($timeregistreringer[$key]);
            }
        }
    }
    $twigArray['timeregistreringer'] = $timeregistreringer;

    $tabellRender = $twig->render('rapportansatt.html', $twigArray);

    $filename = date('Y-m-d') . ' TimeRegistrering rapport.xlsx';
 
    $objPHPExcel = new PHPExcel();
    $tmpFile = tempnam('tempfolder', 'tmp');
    
    file_put_contents($tmpFile, "<html><body>" . $tabellRender . "</body></html>");
    
    $excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
    //$excelHTMLReader->loadIntoExisting($testTemp, $objPHPExcel); //

    $objPHPExcel = $excelHTMLReader->load($tmpFile);

    unlink($tmpFile); 
    //fclose($tmpFile);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename='.$filename);
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    $objWriter->save('php://output');
    exit;
}

$tabellRender = $twig->render('rapportansatt.html', $twigArray);

echo $twig->render('teamrapporttopp.html', $twigArray);
echo $tabellRender;
echo $twig->render('teamrapportbunn.html', $twigArray);
