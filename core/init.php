<?php
$db = mysqli_connect('localhost', 'root', '', 'ecomsite');
if(mysqli_connect_errno())
{
    echo 'Fail';
    die();
}

session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/EcomSite/config.php';
//require_once '../config.php';
require_once BASEURL.'helpers/helpers.php';
require_once BASEURL.'vendor/autoload.php';

$cart_id = '';
if(isset($_COOKIE[CART_COOKIE])){
  $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}

if(isset($_SESSION['SBUser'])){
    $user_id = $_SESSION['SBUser'];
    $query = $db->query("select * from users where id = '$user_id'");
    $user_data = mysqli_fetch_assoc($query);
    $fn = explode(' ', $user_data['full_name']);
    $user_data['first'] = $fn[0];
    $user_data['last'] = $fn[1];
}

if(isset($_SESSION['SBUser2'])){
    $user_id_user = $_SESSION['SBUser2'];
    $query = $db->query("select * from users where id = '$user_id_user'");
    $user_data_user = mysqli_fetch_assoc($query);
    $fn = explode(' ', $user_data_user['full_name']);
    $user_data_user['first'] = $fn[0];
    $user_data_user['last'] = $fn[1];
}

if(isset($_SESSION['success_flash'])){
    echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
    unset($_SESSION['success_flash']);
}

if(isset($_SESSION['error_flash'])){
    echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
    unset($_SESSION['error_flash']);
}
