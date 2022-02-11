<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/EcomSite/core/init.php';
  $name = sanitize($_POST['full_name']);
  $email = sanitize($_POST['email']);
  $street = sanitize($_POST['street']);
  $city = sanitize($_POST['city']);
  $division = sanitize($_POST['division']);
  $zip_code = sanitize($_POST['zip_code']);
  $mobile = sanitize($_POST['mobile']);
  $errors = array();
  $required = array(
    'full_name' => 'Full Name',
    'email' => 'Email',
    'street' => 'Street Address',
    'city' => 'City',
    'zip_code' => 'Postal Code',
    'mobile' => 'Contact Number',
  );

  //check if all the required fields are filled out
  foreach($required as $f => $d){
    if(empty($_POST[$f]) || $_POST[$f] == ''){
      $errors[] = $d.' is required.';
    }
  }

  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors[] = 'Please enter a valid email.';
  }

  if(!empty($errors)){
    echo display_errors($errors);
  }else{
    echo 'passed';
  }

?>
