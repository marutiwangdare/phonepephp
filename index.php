<?php 
// Include configuration file 
include_once 'config.php'; 
 
?>

<div style="display:flex">
    <?php 
        // Fetch products from the database 
        $results = $db->query("SELECT * FROM products WHERE status = 1"); 
        while($row = $results->fetch_assoc()){ 
    ?>
        <div style="margin-left:10px">
            <img src="images/<?php if($row['image'])echo $row['image'];else echo "dummy.png" ?>"/>
            <div class="body">
                <h5><?php echo $row['name']; ?></h5>
                <h6>Price: <?php echo $row['price']?></h6>
				
                <!--payment form for displaying the buy button -->
                <form action="sendPayRequest.php" method="post">

                  <input type="hidden" name="product_id" value="<?php echo $row['id']?>">
                    <input type="hidden" name="amount" value="<?php echo $row['price']?>">
					
                    <!-- Display the payment button. -->
                    <input type="image" name="submit" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif">
                </form>
            </div>
        </div>
    <?php } ?>
</div>