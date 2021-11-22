<?php

function ezine_mysql_die($error = "",$requete=""){

    //var_dump($message_erreur);
    if (!empty(mysqli_error($error))) {
        $erreur_array["retour SQL"]=mysqli_error($error);
        $erreur_array["requete"]=$requete;

        var_dump($erreur_array);
        echo "<p>".$requete."</p>";
        echo "<p><a href=\"javascript:history.go(-1)\">retour</a></p>";
        echo _PAS_DEFFINI;
    }
    exit;
}


function ezine_connecte_db()
{
    //var_dump("ggg");
    if(isset($_SERVER['SERVER_NAME'])){
        if ($_SERVER['SERVER_NAME']=="jeux.conod.org"){
            // Paramêtre de connexion serveur
            require_once "ezine.conf/conod.ezine.conf.php";
            //var_dump("conod");
        }elseif($_SERVER['SERVER_NAME']=="lmbsvsie01.lmb.liebherr.i"){
            // Paramêtre de connexion serveur
            require_once "ezine.conf/lmb.ezine.conf.php";
            //var_dump("lmb");
        }else{

        }
    }else{
        require_once "ezine.conf/conod.ezine.conf.php";
    }

    //var_dump($host);
    //var_dump($password);
    $db = mysqli_connect($host,$login,$password,$base) or ezine_mysql_die();
    //mysql_select_db($base);
    //mysql_query("SET NAMES 'utf8'");
    mysqli_set_charset($db, "utf8");

    $db->{"name_bdd"} = $base;

    //var_dump($db->name_bdd);
return $db;
}

?>