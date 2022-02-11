<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/EcomSite/core/init.php';
include 'includes/head.php';

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
//$hashed = password_hash($password, PASSWORD_DEFAULT);
$errors = array();
?>


<style>
    body{
        background-image: url("/EcomSite/images/headerlogo/background.png");
        background-size: 100w 100w;
        background-attachment: fixed;
    }
</style>

<div id="login-form">
    <div>
        <?php
            if($_POST){
                //form validation..
                //check email exists in db
                $query = $db->query("select * from users where email = '$email' and permissions = 'admin,'");
                $user = mysqli_fetch_assoc($query); 
                $userCount = mysqli_num_rows($query);
                if($userCount < 1){
                    $errors[] = 'Have No Account. Please Create One First';
                }

                else if(!password_verify($password, $user['password'])){
                    $errors[] = 'The password does not match';
                }

                //check for errors
                if(!empty($errors)){
                    echo display_errors($errors);
                }else{
                    //log user in..
                    $user_id = $user['id'];
                    login($user_id);

                }
            }

        ?>
    </div>
    <h2 class="text-center">Login</h2><hr>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Login" class="btn btn-primary">
        </div>
    </form>
    <!--p class="text-right"><a href="/EcomSite/index.php" alt="home">Visit Site</a></p-->
</div>


<?php include 'includes/footer.php'; ?>
