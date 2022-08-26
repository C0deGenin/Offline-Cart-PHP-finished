<?php
    @session_start();
     
    //And include the database connection file
    include('dbconnect.php');

    

  
    //Fetching off_cart products
    $off_cart = $cnx->query('SELECT * FROM off_cart WHERE id_user = "'.$_COOKIE['offline'].'" ');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style.css">
    <title>CART</title>
</head>
<nav class="menu app_flex">
    <h1>YOUR CART</h1>
    <div class="btns app_flex">
        <span class="opt">
            <a href="index.php" >Home</a>
        </span>
        <span class="opt">
            <a href="">Log In</a>
        </span>
    </div>
</nav>
<body class="body app_flex">
    <h2>Your cart</h2>
    
    <form action="" method="post" class="app_flex">
        <div class="products-list app_flex">
            <?php
                //Here we loop over our products in our database and display them white a while loop
                while($cart_product = $off_cart->fetch()){
                    
                    //We select the name current product of the loop
                    $product = $cnx->query('SELECT nom_produit FROM produit WHERE id_produit = "'.$cart_product['id_produit'].'" ');
                    $product_name = $product->fetch();

                    //We look if the user click on an delete button and we delete the related product from his cart
                    if(isset($_POST[$cart_product['id_produit']])){

                            $cart_delete = $cnx->query('DELETE FROM off_cart WHERE id_user = "'.$_SESSION['id'].'" AND id_produit = "'.$cart_product['id_produit'].'" ');     
    
                            //And we add the succes message
                            $msg = "Product deleted from your cart";

                    }
            ?>
                <div class="product app_flex">
                    <span>
                        <?= utf8_encode($product_name['nom_produit']) ?>
                    </span>
                    <button class="product-add app_flex" name="<?= $cart_product['id_produit'] ?>">
                        x
                    </button>
                </div>
            <?php 
                }
            ?>
        </div>
    </form>
    <h3>
        <?php 
            if(@$msg){
                echo @$msg; 
            }
        ?>
    </h3>
</body>
</html>