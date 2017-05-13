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

$teamMedlemmer = array();
$timeregistreringer = array();

function sumHours($timeregArray) {
    $hoursArray = array();
    foreach($timeregArray as $timeregs) {
        $hoursArray[] = $timeregs->getHourAsDateInterval();
    }
    $sumHours = DateHelper::sumDateIntervalList($hoursArray);
    $days = $sumHours->format("%d"); 

    if($days > 0) {
        $daysHours = explode(':', DateHelper::sumDateIntervalList($hoursArray)->format("%d:%H"));
        $hours = ($daysHours[0] * 24) + $daysHours[1];
        return $hours . ":" . $sumHours->format("%I");
    } else {
        return $sumHours->format("%H:%I");
    }
}

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
$teams = $TeamReg->getAlleTeamFraTeamleder($bruker->getId());

if (isset($_GET['team'])) {
    $valgtTeam = $_GET['team'];
    $teamMedlemmer = $TeamReg->getTeamMedlemmer($valgtTeam);
    $timeregistreringer = $TimeReg->hentTimeregistreringerFraTeam($valgtTeam, $datefrom, $dateto, true);
    $sumHours = sumHours($timeregistreringer);
}

$ansatt = isset($_GET['ansatt'])?$_GET['ansatt']:"";
$oppgavetype = isset($_GET['oppgavetype'])?$_GET['oppgavetype']:"";

$twigArray = array('innlogget'=>$_SESSION['innlogget'],
    'bruker'=>$_SESSION['bruker'],
    'brukerTilgang'=>$_SESSION['brukerTilgang'],

    'oppgavereg'=>$OppgaveReg,
    'teamReg'=>$TeamReg,
    'timeReg'=>$TimeReg,
    'brukerReg'=>$BrukerReg,
    'teams'=>$teams,
    'valgtTeam'=>$valgtTeam,
    'timeregistreringer'=>$timeregistreringer,
    'sumHours'=>$sumHours,

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
    $sumHours = sumHours($timeregistreringer);
    
    $teamNavn = $TeamReg->hentTeam($valgtTeam)->getNavn();
    $filename = $datefrom . "_" . $dateto . " TimeRegistrering - " . $teamNavn . ".xlsx";

    
    $objPHPExcel = new PHPExcel();
    $tmpFile = tempnam('tempfolder', 'tmp');
    
    if ($ansatt) {
    $filename = $datefrom . "_" . $dateto . " TimeRegistrering - " . $teamNavn . " - " . $ansatt . ".xlsx";
        file_put_contents($tmpFile, "<html><body>" . $tabellRender . "</body></html>");
        $excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
        $objPHPExcel = $excelHTMLReader->load($tmpFile);
        unlink($tmpFile); 
    } else {
        foreach($teamMedlemmer as $bruker) {
            $currentSheet = $objPHPExcel->createSheet();
            $id = $bruker->getId();
            $navn = $bruker->getNavn();
            $currentSheet->setTitle($navn);
            $sheetArray = array();
            $sheetArray[0] = array("Ansatt: ", $navn);
            $sheetArray[1] = array("Periode :", $datefrom, $dateto);
            $sheetArray[2] = array("");
            $sheetArray[3] = array(
                "Dato",
                "Fra",
                "Til",
                "Timer",
                "Oppgave",
                "Oppgavetype"
                );
            $i = 4;
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
            //$sheetArray[] = array(Sum, "", "", $sumHours);
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
