<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/EcomSite/core/init.php';
unset($_SESSION['SBUser']);
header('Location: login.php');
?>