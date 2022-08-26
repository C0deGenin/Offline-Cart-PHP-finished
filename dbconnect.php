<?php

//Connexion a la base de donnée

$user = "root";

$pass = "";

$host = "127.0.0.1:3309";

$db = "cart";



try{

    $cnx = new PDO('mysql:host='.$host.';dbname='.$db.'' , $user , $pass);

}catch(exception $e){

    die("Echec de connexion a la base de données".$e->getMessage());

}



?>