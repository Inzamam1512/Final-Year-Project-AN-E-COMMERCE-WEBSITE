<?php
function display_errors($errors){
    $display = '<ul class="bg-danger">';
    foreach($errors as $error){
        $display .= '<li class="text-danger">'.$error.'</li>';
    }
    $display .= '</ul>';
    return $display;
}

function sanitize($dirty){
    return htmlentities($dirty,ENT_QUOTES,"UTF-8");
}

function money($number){
    return '৳ '.number_format($number, 2);
}

function login($user_id){
    $_SESSION['SBUser'] = $user_id;
    global $db;
    $date = date("Y-m-d H:i:s");
    $db->query("update users set last_login = '$date' where id = '$user_id'");
    $_SESSION['success_flash'] = 'You are logged in!';
    header('Location: index.php');
}

function login2($user_id_user){
    $_SESSION['SBUser2'] = $user_id_user;
    global $db;
    $date = date("Y-m-d H:i:s");
    $db->query("update users set last_login = '$date' where id = '$user_id_user'");
    $_SESSION['success_flash'] = 'You are logged in!';
    header('Location: index.php');
}

function is_logged_in(){
    if(isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0){
        return true;
    }
    return false;
}

function is_logged_in2(){
    if(isset($_SESSION['SBUser2']) && $_SESSION['SBUser2'] > 0){
        return true;
    }
    return false;
}

function login_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'You must be logged in to access that page';
    header('Location: '.$url);
}

function login_error_redirectTo($url = 'EcomSite/signin.php'){
    $_SESSION['error_flash'] = 'You must be logged in to access that page';
    header('Location: '.$url);
}

function permission_error_redirect($url = 'login.php'){
    $_SESSION['error_flash'] = 'You do not have the permission to access that page';
    header('Location: '.$url);
}

function has_permission($permission = 'admin'){
    global $user_data;
    $permissions = explode(',', $user_data['permissions']); //var_dump($permissionn);die();
    if(in_array($permission, $permissions, true)){
        return true;
    }
    return false;
}

function pretty_date($date){
    return date("M d, Y h:i A", strtotime($date));
}

function get_category($child_id){
    global $db;
    $id = sanitize($child_id);
    $sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid', c.category AS 'child'
            FROM categories c
            INNER JOIN categories p
            ON c.parent = p.id
            WHERE c.id = '$id'";
    $query = $db->query($sql);
    $category = mysqli_fetch_assoc($query);
    return $category;
}

function sizesToArray($string){
  $sizesArray = explode(',', $string);
  $returnArray = array();
  foreach($sizesArray as $size){
    $s = explode(':', $size);
    $returnArray[] = array('size' => $s[0], 'quantity' => $s[1]);
  }
  return $returnArray;
}

function sizesToString($sizes){
  $sizeString = '';
  foreach($sizes as $size){
    $sizeString .= $size['size'].':'.$size['quantity'].',';
  }
  $trimmed = rtrim($sizeString, ',');
  return $trimmed;
}

function getOfferedProduct($brandID)
{
  global $db;
  $offerdProductsId = '';
  $sql = "select id from products where brand = '$brandID'";
  $offerdProductsIdResults = $db->query($sql);

  while($Proid = mysqli_fetch_assoc($offerdProductsIdResults)){
    $offerdProductsId .= $Proid['id'].',';
  }
  //echo count($offerdProductsId);
  //$string = implode(",", $offerdProductsId);
  //var_dump($offerdProductsId);
  //echo $offerdProductsId;
  return $offerdProductsId;
}
