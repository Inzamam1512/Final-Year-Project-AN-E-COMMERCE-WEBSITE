<?php
    $sql = "select * from categories where parent = 0";
    $pquery = $db->query($sql);
?>

<!-- Top Nav Bar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <a href="index.php" class="navbar-brand">Ecommerce Site</a>
        <ul class="nav navbar-nav">
            <?php while($parent = mysqli_fetch_assoc($pquery)) : ?>
            <?php
                $parent_id = $parent['id'];
                $sql2 = "select * from categories where parent = '$parent_id'";
                $cquery = $db->query($sql2);
            ?>
            <!-- Menu Items -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <?php while($child = mysqli_fetch_assoc($cquery)) : ?>
                        <li><a href="category.php?cat=<?=$child['id'];?>"> <?php echo $child['category']; ?> </a></li>
                    <?php endwhile; ?>
                </ul>
            </li>
            <?php endwhile; ?>
            <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart">My Cart</span></a></li>

            <!-- user account-->
            <li class="dropdown">
              <?php if(!is_logged_in2()):?>
                <a href="signin.php">Log In</a>
              <?php else: ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data_user['first'];?>!
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="userDashboard.php">My account</a></li>
                    <li><a href="change_password.php">Change Password</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
              <?php endif; ?>
            </li>

        </ul>
    </div>
</nav>
