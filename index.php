<?php
    //Once the user reach our site we want to initialize a session
    @session_start();
    $_SESSION['id'] = @$_COOKIE['offline'];
     
    //And include the database connection file
    include('dbconnect.php');

    //Here we want to create a cookie named "offline" when user come to our site
    //if this one does'nt exist yet and set it as SESSION id.
    if(empty($_SESSION) && empty(@$_COOKIE['offline'])){

        //This cookie is a random number between 1 and 2000000 
        setcookie("offline" , rand(1 , 2000000) , time() + (86400 * 30), "/");
        $_SESSION['id'] = @$_COOKIE['offline'];
    }

    //Then we want to fetch the products from the database
    $products = $cnx->query('SELECT * FROM produit');

    //Counting off_cart products
    $cart_count = $cnx->query('SELECT * FROM off_cart WHERE id_user = "'.$_COOKIE['offline'].'" ');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style.css">
    <title>Home</title>
</head>
<nav class="menu app_flex">
    <h1>HOME PAGE</h1>
    <div class="btns app_flex">
        <span data-count="<?= $cart_count->rowCount() ?>" class="opt">
            <a href="cart.php" >Cart</a>
        </span>
        <span class="opt">
            <a href="">Log In</a>
        </span>
    </div>
</nav>
<body class="body app_flex">
    <h2>Simple products</h2>
    
    <form action="" method="post" class="app_flex">
        <div class="products-list app_flex">
            <?php
                //Here we loop over our products in our database and display them white a while loop
                while($product = $products->fetch()){

                    //We look if the user click on an add button and we insert the related product to his cart
                    if(isset($_POST[$product['id_produit']])){

                        //Before adding the product we need to know if the product is already
                        //in the user off_cart.
                            $cart_ver = $cnx->query('SELECT * FROM off_cart WHERE id_user = "'.$_COOKIE['offline'].'" AND id_produit = "'.$product['id_produit'].'" ');

                            if(empty($cart_ver->fetch())){
    
                            
                            //So we only have to insert the id of the product and also the user id to the off_cart table
                            $insert = $cnx->prepare('INSERT INTO off_cart(id_produit , id_user) VALUES(?,?)');
                            $insert->execute(array($product['id_produit'],
                                                   $_SESSION['id']));
    
                            //And we add the succes message
                            $msg = "Product added to your cart";
                        }else{
                            //If the product is already added we throw this error message
                            $msg = "Already added product";
                        }
                    }
            ?>
                <div class="product app_flex">
                    <span>
                        <?= utf8_encode($product['nom_produit']) ?>
                    </span>
                    <button class="product-add app_flex" name="<?= $product['id_produit'] ?>">
                        +
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
