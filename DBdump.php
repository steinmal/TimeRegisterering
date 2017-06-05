
<?php
spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'tilgangsfunksjoner.php';
require_once 'vendor/autoload.php';
include_once(dirname(__FILE__) . '/vendor/ifsnop/mysqldump-php/src/Ifsnop/Mysqldump/Mysqldump.php');

session_start();
$error = "";

if(!isInnlogget()){
    header("Location: index.php?error=ikkeInnlogget");
    return;
}

if(!isSystemAdmin()){
    header("Location: index.php?error=manglendeRettighet");
    return;
}

try {
    $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:dbname=stud_v17_gruppe2;host=kark.hin.no', 'stud_v17_gruppe2', 'gruppe2');

    $file_url = 'DataBaseDump.sql';
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"" . date('d-m-Y') . basename($file_url) . "\"");

    $dump->start('php://output'); //Skriv direkte til stdout
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
