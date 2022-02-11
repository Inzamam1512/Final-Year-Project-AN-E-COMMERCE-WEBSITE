<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/EcomSite/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
if(!has_permission('admin')){
    permission_error_redirect('../index.php');
}
include 'includes/head.php';
include 'includes/navigation.php';

$offerSql = "SELECT * FROM offers";
$offerResult = $db->query($offerSql);
$offerCount = mysqli_num_rows($offerResult);
$offer = mysqli_fetch_assoc($offerResult);
$brand_ids = $offer['brand'];
$brandSql = "SELECT brand FROM brands WHERE id IN ({$brand_ids})"; // selected brands for offer. trying to get selected brands name for offer
$brandResult = $db->query($brandSql);
$brand_id_array = explode(',', $brand_ids);


$flag = 0;
//Delete Offer
if(isset($_GET['delete'])){
    $id = sanitize($_GET['delete']);
    $db->query("delete from offers where id = '$id'");
    //offer price will be updated in product table

    for($i=0; $i<count($brand_id_array); $i++){
      $offerProductIds = '';
      $offerProductIds = getOfferedProduct($brand_id_array[$i]); // get the offered products
      $offerProductIds = rtrim($offerProductIds, ',');
      $offerProductIdsArray = explode(',', $offerProductIds);

      foreach($offerProductIdsArray as $offerProductId){
          $db->query("update products set list_price = 0 where id = '$offerProductId'");
      }
    }
    header('Location: offeradmin.php');
}

if(isset($_GET['add'])){
  $flag = 1;
  //$brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):'');
  $off = ((isset($_POST['off']) && $_POST['off'] != '')?sanitize($_POST['off']):'');
  $offerdes = ((isset($_POST['offerdes']) && $_POST['offerdes'] != '')?sanitize($_POST['offerdes']):'');
}

else if(isset($_GET['edit'])){
  $flag = 2;
  $edit_id = (int)$_GET['edit'];
  //$brand = ((isset($_POST['brand']))?sanitize($_POST['brand']):$offer['brand']);
  $off = ((isset($_POST['off']))?sanitize($_POST['off']):$offer['off']);
  $offerdes = ((isset($_POST['offerdes']))?sanitize($_POST['offerdes']):$offer['offerdes']);
}

if($_POST){
  $brand = "";
  foreach($_POST['brand'] as $selected){
    //echo $selected."</br>";
    $var = $selected.",";
    $brand .= $var;
  }

  $brand = rtrim($brand, ',');
  if(count($brand) != 0){
    $insertSql = "insert into offers (`brand`, `off`, `offerdes`) values
    ('$brand', '$off', '$offerdes')";

    if(isset($_GET['edit'])){
      $insertSql = "update offers set brand = '$brand', off = '$off', offerdes = '$offerdes' where id = '$edit_id'";
    }
    $db->query($insertSql);

    $offerSql = "SELECT * FROM offers";
    $offerResult = $db->query($offerSql);
    $offerCount = mysqli_num_rows($offerResult);
    $offer = mysqli_fetch_assoc($offerResult);
    $brand_ids = $offer['brand'];
    $brandSql = "SELECT brand FROM brands WHERE id IN ({$brand_ids})"; // selected brands for offer. trying to get selected brands name for offer
    $brandResult = $db->query($brandSql);
    $brand_id_array = explode(',', $brand_ids);

    //normalization of the list_price in products
    $db->query("update products set list_price = 0");

    //update the price for offered product

    for($i=0; $i<count($brand_id_array); $i++){
      $offerProductIds = '';
      $offerProductIds = getOfferedProduct($brand_id_array[$i]); // get the offered products for each brand id
      $offerProductIds = rtrim($offerProductIds, ',');
      $offerProductIdsArray = explode(',', $offerProductIds);

      foreach($offerProductIdsArray as $offerProductId){
          //echo $offerProductId.'<br>';
          $normalPriceResult = $db->query("select price from products where id = '$offerProductId'");
          $normalPrice = mysqli_fetch_assoc($normalPriceResult);

          $offerPrice = $normalPrice['price'] - ($normalPrice['price'] * $off);
          //echo $off.' '.$normalPrice['price'].' '.' '.$normalPrice['list_price'].' '.$offerPrice;die();
          $db->query("update products set list_price = '$offerPrice'  where id = '$offerProductId'");
      }
    }
    //var_dump(count($brand));die();
    header('Location: offeradmin.php');
  }

}
?>

<?php if($flag != 0): ?>
<!--offer add or edit-->
<h2 class="text-center"> <?=((isset($_GET['edit']))? 'Edit' : 'Add'); ?> Offer</h2><hr>
<form action="offeradmin.php?<?=((isset($_GET['edit']))? 'edit='.$edit_id : 'add=1'); ?>" method="POST">

    <div class="form-group col-md-3">
      <?php
        $allBrandSql = "SELECT * FROM brands";
        $allBrandResult = $db->query($allBrandSql);
      ?>
        <label for="brand">Brand*:</label><br>

          <?php while($allBrand = mysqli_fetch_assoc($allBrandResult)): ?>
            <?php $f = 0; ?>
            <?php for($i=0; $i<count($brand_id_array); $i++): ?>
              <?php if($allBrand['id'] == $brand_id_array[$i]): ?>
                <?php $f = 1; ?>
                <input type="checkbox" name="brand[]" value="<?=$allBrand['id'];?>" checked><?=$allBrand['brand'];?><br>
              <?php endif; ?>
            <?php endfor; ?>
            <?php if($f == 0): ?>
              <input type="checkbox" name="brand[]" value="<?=$allBrand['id'];?>"><?=$allBrand['brand'];?><br>
            <?php endif; ?>
          <?php endwhile; ?>

    </div>

    <div class="form-group col-md-3">
        <label for="off">Off*:</label>
        <input type="text" id="off" name="off" class="form-control" value="<?=$off;?>" required>
    </div>


    <div class="form-group col-md-6">
        <label for="offerdes">Description*:</label>
        <textarea id="offerdes" name="offerdes" class="form-control" rows="6" required><?=$offerdes;?></textarea>
    </div>
    <div class="form-group pull-right">
        <a href="offeradmin.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="<?=((isset($_GET['edit']))? 'Edit' : 'Add'); ?> Offer" class="btn btn-success pull-right">
    </div>
    <div class="clearfix" ></div>

</form>

<?php else: ?>

<h2 class="text-center">Offers</h2>
<?php if($offerCount == 0): ?>
  <a href="offeradmin.php?add=1" class="btn btn-success pull-right" id="add-offer-btn">Add Offer</a><div class="clearfix"></div>
<?php endif; ?>
<hr>
<table class="table table-bordered table-condensed table-striped">
    <thead>
        <th></th><th>Brand</th><th>Off</th><th>Description</th>
    </thead>
    <tbody>
        <tr>
            <td>
              <?php if($offerCount != 0): ?>
                <a href="offeradmin.php?edit=<?=$offer['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="offeradmin.php?delete=<?=$offer['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
              <?php endif; ?>
            </td>
            <td>
              <?php if($offerCount != 0): ?>
                <?php while ($brandsName = mysqli_fetch_assoc($brandResult)):?>
                  <?=$brandsName['brand']."<br>" ?>
                <?php endwhile; ?>
              <?php endif; ?>
            </td>
            <td>
              <?=$offer['off'];?>
            </td>
            <td>
              <?=$offer['offerdes'];?>
            </td>
        </tr>

    </tbody>

</table>
<?php endif; ?>
<?php include 'includes/footer.php' ?>
