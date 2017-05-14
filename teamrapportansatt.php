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

$ansatt = "";
$oppgavetype = "";
$oppgavetyper = $OppgaveReg->hentAlleOppgaveTyper();

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

function filtrerTimeregsForAnsatt($timeregs, $ansattNavn, $BrukerReg) {
    foreach($timeregs as $key => $element) {
        if($BrukerReg->hentBruker($element->getBrukerId())->getNavn() != $ansattNavn) {
            unset($timeregs[$key]);
        }
    }
    return $timeregs;
}

function filtrerTimeregsForOppgavetype($timeregs, $oppgavetypeNavn, $OppgaveReg) {
    foreach($timeregs as $key => $element) {
        if ($OppgaveReg->getOppgavetypeTekst($OppgaveReg->hentOppgave($element->getOppgaveId())->getType()) != $oppgavetypeNavn) {
            unset($timeregs[$key]);
        }
    }
    return $timeregs;
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

$brukerType = $BrukerReg->getBrukertype($bruker->getBrukertype());
if($brukerType->isProsjektadmin() or $brukerType->isSystemadmin()) {
    $teams = $TeamReg->hentAlleTeam();
} else {
    $teams = $TeamReg->getAlleTeamFraTeamleder($bruker->getId());
}

$valgtTeam = null;
$sumHours = "";
if (isset($_GET['team'])) {
    $valgtTeam = $_GET['team'];
    $teamMedlemmer = $TeamReg->getTeamMedlemmer($valgtTeam);
    $timeregistreringer = $TimeReg->hentTimeregistreringerFraTeam($valgtTeam, $datefrom, $dateto, true);
    $sumHours = sumHours($timeregistreringer);
}

if(isset($_GET['ansatt'])) {
    $ansatt = $_GET['ansatt'];
    if($ansatt) {
        $timeregistreringer = filtrerTimeregsForAnsatt($timeregistreringer, $ansatt, $BrukerReg);
        $sumHours = sumHours($timeregistreringer);
    }
}

if(isset($_GET['oppgavetype'])) {
    $oppgavetype = $_GET['oppgavetype'];
    if($oppgavetype) {
        $timeregistreringer = filtrerTimeregsForOppgavetype($timeregistreringer, $oppgavetype, $OppgaveReg);
        $sumHours = sumHours($timeregistreringer);
    }
}

$twigArray = array('innlogget'=>$_SESSION['innlogget'],
    'bruker'=>$_SESSION['bruker'],
    'brukerTilgang'=>$_SESSION['brukerTilgang'],

    'teams'=>$teams,
    'valgtTeam'=>$valgtTeam,
    
    'timeregistreringer'=>$timeregistreringer,
    'sumHours'=>$sumHours,

    'teamMedlemmer'=>$teamMedlemmer,
    'valgtAnsatt'=>$ansatt,
    
    'oppgavetyper'=>$oppgavetyper,
    'valgtOppgavetype'=>$oppgavetype,
    
    'datefrom'=>$datefrom,
    'dateto'=>$dateto
);

if(isset($_GET['download'])){
    if ($ansatt){
        $timeregistreringer = filtrerTimeregsForAnsatt($timeregistreringer, $ansatt, $BrukerReg);
    }
    if ($oppgavetype){
        $timeregistreringer = filtrerTimeregsForOppgavetype($timeregistreringer, $oppgavetype, $OppgaveReg);
    }
    
    $twigArray['timeregistreringer'] = $timeregistreringer;
    $sumHours = sumHours($timeregistreringer);
    $twigArray['sumHours'] = $sumHours;
    $tabellRender = $twig->render('rapportansatt.html', $twigArray);

    $teamNavn = $TeamReg->hentTeam($valgtTeam)->getNavn();

    $objPHPExcel = new PHPExcel();
    $tmpFile = tempnam('tempfolder', 'tmp');

    // Rapport når ansatt er valgt
    if ($ansatt) { 
        $filename = $datefrom . "_" . $dateto . " TimeRegistrering - " . $teamNavn . " - " . $ansatt . ".xlsx";
        file_put_contents($tmpFile, "<html><body>" . $tabellRender . "</body></html>");
        $excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
        $objPHPExcel = $excelHTMLReader->load($tmpFile);
        unlink($tmpFile);
        
    // Rapport for hele teamet, et ark per ansatt
    } else {
        $filename = $datefrom . "_" . $dateto . " TimeRegistrering - " . $teamNavn . ".xlsx";
        foreach($teamMedlemmer as $bruker) {
            $currentSheet = $objPHPExcel->createSheet();
            $id = $bruker->getId();
            $navn = $bruker->getNavn();
            $currentSheet->setTitle($navn);
            $sheetArray = array();
            $sheetArray[0] = array("Ansatt: ", $navn);
            $sheetArray[1] = array("Periode :", $datefrom, $dateto);
            $sheetArray[2] = array("");

            $brukersSum = sumHours(filtrerTimeregsForAnsatt($timeregistreringer, $navn, $BrukerReg));
            $brukersTimeregs = array();
            $brukersTimeregs[0] = array(
                "Dato",
                "Fra",
                "Til",
                "Timer",
                "Oppgave",
                "Oppgavetype"
            );
            $i = 1;
            foreach($timeregistreringer as $timereg) {
                $timeregBrukerId = $timereg->getBrukerId();
                if($id == $timeregBrukerId) {
                    $oppgave = $OppgaveReg->hentOppgave($timereg->getOppgaveId());
                    $oppgavetype = $OppgaveReg->hentOppgaveType($oppgave->getType());
                    $brukersTimeregs[$i] = array(
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
            $brukersTimeregs[$i] = array("Totalt", "", "", $brukersSum);
            $currentSheet->fromArray($sheetArray, null, 'A1');
            $currentSheet->fromArray($brukersTimeregs, null, 'A4');
            if($i < 2) { // Ingen timeregs registrert på bruker, fjerner sheet for valgt bruker.
                $objPHPExcel->removeSheetByIndex($objPHPExcel->getIndex($objPHPExcel->getSheetByName($navn)));
            }
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
