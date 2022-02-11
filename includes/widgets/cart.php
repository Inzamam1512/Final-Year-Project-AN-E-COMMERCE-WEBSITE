<h3 class = "text-center"> Shopping Cart </h3>
<div>
  <?php if(empty($cart_id) || !is_logged_in2()): ?>
    <p> Your shopping cart is empty. </p>
  <?php else:
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $results = mysqli_fetch_assoc($cartQ);
    $items = json_decode($results['items'],true);
    $sub_total = 0;
    ?>

    <table class = "table table-condensed" id = "cart_widgets">
      <tbody>
        <?php foreach($items as $item):
          $productQ = $db->query("SELECT * FROM products WHERE id = '{$item['id']}'");
          $product = mysqli_fetch_assoc($productQ);
        ?>
        <tr>
          <td> <?=$item['quantity']; ?> </td>
          <td>  <?=substr($product['title'],0,10); ?> </td>
          <td>
            <?php if($product['list_price'] > 0): ?>
              <?=money($item['quantity'] * $product['list_price']); ?>
            <?php else: ?>
              <?=money($item['quantity'] * $product['price']); ?>
            <?php endif; ?>
          </td>
        </tr>
        <?php
          if($product['list_price'] > 0){
            $sub_total += ($item['quantity'] * $product['list_price']);
          }
          else{
            $sub_total += ($item['quantity'] * $product['price']);
          }
        endforeach; ?>
        <tr>
          <td> </td>
          <td> Sub Total</td>
          <td> <?=money($sub_total);?> </td>
        </tr>
      </tbody>
    </table>
    <a href = "cart.php" class = "btn btn-xs btn-primary pull-right"> View Cart </a>
    <div class="clearfix">

    </div>

  <?php endif; ?>
</div
