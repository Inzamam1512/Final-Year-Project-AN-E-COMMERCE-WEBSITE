<?php
require_once '../core/init.php';
if(!is_logged_in()){
   login_error_redirect();
}
if(!has_permission('admin')){
    permission_error_redirect('../index.php');
}
include 'includes/head.php';
include 'includes/navigation.php';

$sql = "select * from brands order by brand";
$results = $db->query($sql);
$errors = array();

//Edit Brand
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "select * from brands where id = '$edit_id'";
    $edit_result = $db->query($sql2);
    $eBrand = mysqli_fetch_assoc($edit_result);

}

//Delete Brand
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "delete from brands where id = '$delete_id'";
    $db->query($sql);
    header('Location: brands.php');
}

//if add form is submitted
if(isset($_POST['add_submit'])){
    $brand = sanitize($_POST['brand']);
    //check if brand is blank
    if($_POST['brand'] == ''){
        $errors[] .= 'You must enter a brand!';
    }
    $sql = "select * from brands where brand = '$brand'";
    if(isset($_GET['edit'])){
        $sql = "select * from brands where brand = '$brand' and id != '$edit_id'";
    }
    $result = $db->query($sql);
    $count = mysqli_num_rows($result);
    if($count > 0){
        $errors[] .= 'That brand already exists.';
    }

    //display errors
    if(!empty($errors)){
        echo display_errors($errors);
    }
    else{
        //add brand to database..
        $sql = "insert into brands(brand) values ('$brand')";
        if(isset($_GET['edit'])){
            $sql = "update brands set brand = '$brand' where id = '$edit_id'";
        }
        $db->query($sql);
        header('Location: brands.php');
    }
}
?>

<h2 class="text-center">Brands</h2> <hr>
<!--Brand from -->
<div class="text-center">
    <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
        <div class="from-group">
            <?php
              $brand_value = '';
              if(isset($_GET['edit'])){
                $brand_value = $eBrand['brand'];
              }
              else{
                  if(isset($_POST['brand'])){
                      $brand_value = sanitize($_POST['brand']);
                  }
              }
            ?>

            <label for="brand"><?=((isset($_GET['edit']))? 'Edit' : 'Add'); ?> Brand:</label>
            <input type="text" name="brand" id="brand" class="" value="<?=$brand_value; ?>">
            <?php if(isset($_GET['edit'])): ?>
                <a href="brands.php" class="btn btn-default">Cancel</a>
            <?php endif; ?>
            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))? 'Edit' : 'Add'); ?> Brand" class="btn btn-success">
        </div>
    </form>
</div><hr>

<table class="table table-bordered table-striped table-auto table-condensed">
    <thead>
        <th></th><th>Brand</th><th></th>
    <thead>
    <tbody>
        <?php while($brand = mysqli_fetch_assoc($results)): ?>
            <tr>
                <td><a href="brands.php?edit=<?=$brand['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                <td><?=$brand['brand'];?></td>
                <td><a href="brands.php?delete=<?=$brand['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include 'includes/footer.php'; ?>
