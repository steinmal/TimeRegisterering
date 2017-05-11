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
$BrukerReg = new BrukerRegister($db);
$aktivert = "";
session_start();

if(!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] == false){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}
$aktivert = $_SESSION['bruker']->isAktivert();
if(!isset($_SESSION['brukerTilgang']) || $_SESSION['brukerTilgang']->isTeamleder() != true || !$_SESSION['bruker']->isAktivert()){
    header("Location: index.php?error=manglendeRettighet&side=teamrapp");
    return;
}

date_default_timezone_set('Europe/Oslo');
$firstDayOfMonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
$lastDayOfMonth = mktime(0, 0, 0, date("m"), date("t"), date("Y"));

$datefrom = date("Y-m-d", $firstDayOfMonth);
$dateto = date("Y-m-d", $lastDayOfMonth);

if (isset($_SESSION['datefrom'])) $datefrom = $_SESSION['datefrom'];
if (isset($_SESSION['dateto'])) $dateto = $_SESSION['dateto'];

if (isset($_GET['daterange']) && strlen($_GET['daterange']) == 23) {
    $datefrom = substr($_GET['daterange'], 0, 10);
    $dateto = substr($_GET['daterange'], 13, 10);
}

$_SESSION['datefrom'] = $datefrom;
$_SESSION['dateto'] = $dateto;

$bruker = $_SESSION['bruker'];

$teamIDs = array();
$teamIDs = $TeamReg->getTeamIdFraTeamleder($bruker->getId());

$teams = array();
foreach ($teamIDs as $teamID) {
    $teams[] = $TeamReg->hentTeam($teamID);
}

$teamMedlemmer = array();
if (isset($_GET['team'])) {
    $teamID = $_GET['team'];
    $teamMedlemmer = $TeamReg->getTeamMedlemmer($teamID);

}

$brukerIds = array();
foreach ($teamIDs as $teamId) {
    $brukerIdArray = array();
    $brukerIdArray = $TeamReg->getTeamMedlemmerId($teamId);
    foreach ($brukerIdArray as $brukerId) {
        $brukerIds[] = $brukerId;
    }
}

$timeregistreringer = array();
foreach($brukerIds as $brukerId) {
    $brukersTimeregistreringer = array();
    $brukersTimeregistreringer = $TimeReg->hentTimeregistreringerFraBruker($brukerId, $datefrom, $dateto);
    foreach ($brukersTimeregistreringer as $timereg) {
        $timeregistreringer[] = $timereg;
    }
}

$ansatt = isset($_GET['ansatt'])?$_GET['ansatt']:"";
$oppgavetype = isset($_GET['oppgavetype'])?$_GET['oppgavetype']:"";

$twigArray = array('innlogget'=>$_SESSION['innlogget'],
    'bruker'=>$_SESSION['bruker'],
    'timeregistreringer'=>$timeregistreringer,
    'oppgavereg'=>$OppgaveReg,
    'teamReg'=>$TeamReg,
    'timeReg'=>$TimeReg,
    'brukerReg'=>$BrukerReg,
    'teams'=>$teams,
    'valgtTeam'=>$teamID,
    //'brukerIds'=>$brukerIds,
    'teamMedlemmer'=>$teamMedlemmer,
    
    'brukerTilgang'=>$_SESSION['brukerTilgang'],
    'ansatt'=>$ansatt,
    'oppgavetype'=>$oppgavetype,
    'datefrom'=>$datefrom,
    'dateto'=>$dateto
    );

if(isset($_GET['download'])){
    if ($ansatt){
        foreach($timeregistreringer as $key => $element) {
            if ($BrukerReg->hentBruker($element->getBrukerId())->getNavn() != $ansatt) {
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
    
    $filename = date('Y-m-d') . ' TimeRegistrering rapport.xlsx'; // TODO: Ta med periode
    
    $objPHPExcel = new PHPExcel();
    $tmpFile = tempnam('tempfolder', 'tmp');
    
    if ($ansatt) {
        file_put_contents($tmpFile, "<html><body>" . $tabellRender . "</body></html>");
        $excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
        $objPHPExcel = $excelHTMLReader->load($tmpFile);
        unlink($tmpFile); 
    } else {
        $teamMedlemmer = $TeamReg->getTeamMedlemmer($teamID);
        foreach($teamMedlemmer as $bruker) {
            $currentSheet = $objPHPExcel->createSheet();

            $id = $bruker->getId();
            $navn = $bruker->getNavn();
            $currentSheet->setTitle($navn);
            $sheetArray = array();
            $sheetArray[0] = array("Ansatt: ", $navn);
            $sheetArray[1] = array("Bruker ID:", $id);
            $sheetArray[2] = array("Periode :", $datefrom, $dateto);
            $sheetArray[3] = array("");
            $sheetArray[4] = array(
                "Dato",
                "Fra",
                "Til",
                "Timer",
                "Oppgave",
                "Oppgavetype"
                );
            $i = 5;
            foreach($timeregistreringer as $timereg) {
                $timeregBrukerId = $timereg->getBrukerId();
                if($id == $timeregBrukerId) {
                    $oppgave = $OppgaveReg->hentOppgave($timereg->getOppgaveId());
                    $oppgavetype = $OppgaveReg->hentOppgaveType($oppgave->getType());
                    $sheetArray[$i] = array(
                        $timereg->getDato(),
                        $timereg->getFra(), 
                        $timereg->getTil(),
                        $timereg->getHourString(),
                        $oppgave->getNavn(),
                        $oppgavetype->getNavn()
                        );
                    $i++;
                }
            }
            $currentSheet->fromArray($sheetArray, null, 'A1');
        }
        $objPHPExcel->removeSheetByIndex($objPHPExcel->getIndex($objPHPExcel->getSheetByName('Worksheet')));
    }
    
    fclose($tmpFile);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename='.$filename);
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    $objWriter->save('php://output');
    exit;
}
$twigArray['TeamReg'] = $TeamReg; // TODO: Trengs denne? Har allerede teamReg
$twigArray['aktivert'] = $aktivert;
$tabellRender = $twig->render('rapportansatt.html', $twigArray);

echo $twig->render('teamrapporttopp.html', $twigArray);
echo $tabellRender;
echo $twig->render('teamrapportbunn.html', $twigArray);
