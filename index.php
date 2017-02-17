<?php
require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$bool = false;
if ($bool == true) {
    $boolIsTrue = true;
}
else {
    $boolIsTrue = false;
}

echo $twig->render('index.html', array('boolIsTrue'=>$boolIsTrue));
?>
