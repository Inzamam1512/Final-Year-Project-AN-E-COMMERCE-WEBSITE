<div class="col-md-12 text-center">&copy; Copyright</div>
<script>
        jQuery(window).scroll(function(){

    });

    function detailsmodal(id)
    {
        var data = {"id": id };
        jQuery.ajax({
           url: '/EcomSite/includes/detailsmodal.php',
            method: "post",
            data: data,
            success: function(data){
                jQuery('body').append(data);
                jQuery('#details-modal').modal('toggle');
            },
            error: function(){
                alert("Something Went Wrong!");
            }
        });
    }

    function update_cart(mode, edit_id, edit_size){
      var data = {"mode" : mode, "edit_id" : edit_id, "edit_size" : edit_size};
      jQuery.ajax({
        url: '/EcomSite/admin/parsers/update_cart.php',
        method: "post",
        data: data,
        success: function(){location.reload();},
        error: function(){alert("Something Went Wrong!");}
      });
    }

    function add_to_cart(){
      jQuery('#modal_errors').html("");
      var size = jQuery('#size').val();
      var quantity = jQuery('#quantity').val();
      var available = jQuery('#available').val();
      var error = '';
      var data = jQuery('#add_product_form').serialize();
      if(size == '' || quantity=='' || quantity == 0){
        error += '<p class="text-danger text-center">You must choose a size and quantity.</p>';
        jQuery('#modal_errors').html(error);
        return;
      }
      else if(quantity > available){
       //alert("lsjf");
       error += '<p class="text-danger text-center">There are only '+available+' available.</p>';
       jQuery('#modal_errors').html(error);
       return;
     }

     /*else if(!is_logged_in2()){
      //alert("lsjf");
      error += '<p class="text-danger text-center">You have to log in first.</p>';
      jQuery('#modal_errors').html(error);
      return;
    }*/

     else{
        jQuery.ajax({
          url: '/EcomSite/admin/parsers/add_cart.php',
          method: 'post',
          data: data,
          success: function(){
            location.reload();
          },
          error: function(){alert("Something Went Wrong");}
        });
      }
    }

</script>
</body>
</html>
