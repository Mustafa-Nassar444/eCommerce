<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
include 'admin/connect.php';
$tpl = 'includes/templates/';
$langs = 'includes/languages/';
$func = 'includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';

include $func . 'function.php';
include $langs . 'english.php';
include $tpl . 'header.php';
