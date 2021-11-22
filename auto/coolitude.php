<?php

include_once dirname(__FILE__) . '/../structure/sql.php';

$sql_cool = "CALL `p_coolitude`();";
//echo "<p>".$sql_cool."</p>";
$res_cool = mysqli_query ($ezine_db, $sql_cool) or ezine_mysql_die($ezine_db, $sql_cool) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_cool=mysqli_num_rows($res_cool);
$bdd_cool=array();
while($bdd_cool_dessous = mysqli_fetch_array($res_cool)){
    $bdd_cool[]=$bdd_cool_dessous;
}
//mysqli_free_result($res_cool);
$res_cool->free();
$ezine_db->next_result();

foreach ($bdd_cool as $bdd_cool_actu){


    echo "".$bdd_cool_actu["jeu_id"]." ";

    $sql_actu = "SELECT `jeu_id`,`jeu_nom`,`jeu_coolitude`\n"
        . "FROM `jeu` \n"
        . "WHERE `jeu_id` = ".$bdd_cool_actu["jeu_id"].";";
    //$sql_actu = "CALL `p_coolitude`();";
//echo "<p>".$sql_actu."</p>";
    $res_actu = mysqli_query ($ezine_db, $sql_actu) or ezine_mysql_die($ezine_db, $sql_actu) ;
//$num_ticket=mysqli_insert_id($ezine_db);
    $nbre_actu=mysqli_num_rows($res_actu);
    $bdd_actu = mysqli_fetch_object($res_actu);

    echo "".$bdd_actu->jeu_id."</br>";

}



?>

