<?php
include 'connect.php';
$tpl = 'includes/templates/';
$langs = 'includes/languages/';
$func = 'includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';

include $func . 'function.php';
include $langs . 'english.php';
include $tpl . 'header.php';
if (!isset($noNavbar)) {
  include $tpl . 'navbar.php';
}
