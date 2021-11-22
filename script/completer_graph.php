<?php
include_once dirname(__FILE__) . '/../structure/sql.php';

include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];

?>

<?php
$par_evo=array();

$sql_liste_evo = "SELECT `evolution_note_type_id`,`evolution_note_type_nom`\n"
    . "FROM `evolution_note_type`  \n"
    . "ORDER BY `evolution_note_type`.`evolution_note_type_id` ASC;";
//echo "<p>".$sql_liste_evo."</p>";
$res_liste_evo = mysqli_query ($ezine_db, $sql_liste_evo) or ezine_mysql_die($ezine_db, $sql_liste_evo) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_evo=mysqli_num_rows($res_liste_evo);
while($bdd_liste_evo = mysqli_fetch_object($res_liste_evo)) {
    $par_evo[]=array("id"=>$bdd_liste_evo->evolution_note_type_id,"quoi"=>$bdd_liste_evo->evolution_note_type_nom);
}
//var_dump($par_evo);

$sql_liste_jeu = "SELECT `jeu_id`,`jeu_nom` \n"
    . "FROM `jeu` \n"
    . "ORDER BY `jeu`.`jeu_id` ASC;";
//echo "<p>".$sql_liste_jeu."</p>";
$res_liste_jeu = mysqli_query ($ezine_db, $sql_liste_jeu) or ezine_mysql_die($ezine_db, $sql_liste_jeu) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_jeu=mysqli_num_rows($res_liste_jeu);
while($bdd_liste_jeu = mysqli_fetch_object($res_liste_jeu)){
    foreach ($par_evo as $evo_actu){
        $sql_existe = "SELECT *  \n"
            . "FROM `evolution_note` \n"
            . "WHERE `jeu` = ".$bdd_liste_jeu->jeu_id." \n"
            . "AND `evolution_note_type` = ".$evo_actu["id"]." \n"
            . "ORDER BY `jeu` ASC;";
//echo "<p>".$sql_existe."</p>";
        $res_existe = mysqli_query ($ezine_db, $sql_existe) or ezine_mysql_die($ezine_db, $sql_existe) ;
//$num_ticket=mysqli_insert_id($ezine_db);
        $nbre_existe=mysqli_num_rows($res_existe);
        if($nbre_existe==0){
            $sql_insert = "INSERT INTO `evolution_note`(\n"
                . "    `evolution_note_date`,\n"
                . "    `jeu`,\n"
                . "    `evolution_note_type`,\n"
                . "    `evolution_note_de`,\n"
                . "    `evolution_note_a`\n"
                . ")\n"
                . "VALUES(\n"
                . "    NOW(), ".$bdd_liste_jeu->jeu_id.", ".$evo_actu["id"].", NULL,(\n"
                . "    SELECT\n"
                . "        `".$evo_actu["quoi"]."`\n"
                . "    FROM\n"
                . "        `jeu`\n"
                . "    WHERE\n"
                . "        `jeu_id` = ".$bdd_liste_jeu->jeu_id."\n"
                . "));";
            //echo "<p>".$sql_insert."</p>";
            mysqli_query ($ezine_db, $sql_insert) or ezine_mysql_die($ezine_db, $sql_insert) ;
            echo"".$bdd_liste_jeu->jeu_nom." = ".$nbre_existe."</br>";
        }else{
            //echo"".$bdd_liste_jeu->jeu_nom."</br>";
        }

    }





    //echo"".$bdd_liste_jeu->jeu_nom." = ".$nbre_existe."</br>";


}




?>

<main>
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <figure class="highcharts-figure">
            <div id="graph1"></div>
            <p class="highcharts-description">
                Basic line chart showing trends in a dataset. This chart includes the
                <code>series-label</code> module, which adds a label to each line for
                enhanced readability.
            </p>
        </figure>
    </div>
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-5">
            <h1 class="display-4 fw-normal">Punny headline</h1>
            <p class="lead fw-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pages.</p>
            <a class="btn btn-outline-secondary" href="#">Coming soon</a>
        </div>
    </div>

    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <figure class="highcharts-figure">
            <div id="graph1"></div>
            <p class="highcharts-description">
                Basic line chart showing trends in a dataset. This chart includes the
                <code>series-label</code> module, which adds a label to each line for
                enhanced readability.
            </p>
        </figure>
    </div>
</main>




include_once dirname(__FILE__) . '/../structure/footer.php';
?>
