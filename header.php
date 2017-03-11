<?php

spl_autoload_register(function ($class_name) {
    require_once 'classes/' . $class_name . '.class.php';
});

require_once 'vendor/autoload.php';
include('auth.php');
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$UserReg = new UserRegister($db);
session_start();

if(isset($_SESSION['innlogget'])) {
    $innlogget = $_SESSION['innlogget'];
}



echo $twig->render('header.html', array('innlogget'=>$innlogget, 'bruker'=>$_SESSION['bruker']));
?>