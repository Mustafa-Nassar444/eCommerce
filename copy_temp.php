<?php
ob_start();
session_start();
$pageTitle = '';
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
$pageTitle = "Members";
if (isset($_SESSION['Username'])) {

    include 'init.php';
    if ($do == 'Manage') {
    } elseif ($do == 'Add') {
    } elseif ($do == 'Insert') {
    } elseif ($do == 'Edit') {
    } elseif ($do == 'Update') {
    } elseif ($do == 'Delete') {
    } elseif ($do == 'Activate') {
    }
    include $tpl . "footer.php";
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
