<?php
include_once dirname(__FILE__) . '/../structure/sql.php';
include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];


//require dirname(__FILE__) . '/../vendor/DeepLy-master/src/ChrisKonnertz/DeepLy/DeepLy.php';
//require chriskonnertz/deeply;
//require dirname(__FILE__) . '/../vendor/autoload.php';

/*$deepl = \Scn\DeeplApiConnector\DeeplClient::create('1e26389b-5ef7-322d-ad5c-81ee07aede1c:fx');


    $translation = new \Scn\DeeplApiConnector\Model\TranslationConfig(
        'My little Test',
        \Scn\DeeplApiConnector\Enum\LanguageEnum::LANGUAGE_FR
    );

    $translationObject = $deepl->getTranslation($translation);

*/

$sql = "SELECT *,\n"
    . "CASE\n"
    . "	WHEN `expansion_bgg_id` IN ( \n"
    . "        SELECT `jeu_bgg_id`\n"
    . "		FROM `jeu`\n"
    . "		WHERE `jeu_bgg_subtype` LIKE \'boardgameexpansion\'\n"
    . "    ) THEN 1\n"
    . "    else 0\n"
    . "end AS `posedee`\n"
    . "FROM `expansion_jeu`\n"
    . "LEFT JOIN `expansion` ON `expansion`.`expansion_id` = `expansion_jeu`.`expansion`\n"
    . "WHERE `jeu` = 410;";

?>



<?php
$id=2;
if(isset($_GET["id"])){
    $id=$_GET["id"];
}

$sql_jeu_detail = "SELECT *\n"
    . "FROM `jeu`\n"
    . "WHERE `jeu_id` = ".$id;
//echo "<p>".$sql_jeu_detail."</p>";
$res_jeu_detail = mysqli_query ($ezine_db, $sql_jeu_detail) or ezine_mysql_die($ezine_db, $sql_jeu_detail) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_jeu_detail=mysqli_num_rows($res_jeu_detail);
$bdd_jeu_detail = mysqli_fetch_object($res_jeu_detail);

$data_array=array(
        array("nom"=>"Volume:","data"=>$bdd_jeu_detail->jeu_x." x ".$bdd_jeu_detail->jeu_y." x ".$bdd_jeu_detail->jeu_z),
        array("nom"=>"Poids:","data"=>$bdd_jeu_detail->jeu_poids." Kg"),
        array("nom"=>"Année de publication:","data"=>$bdd_jeu_detail->jeu_bgg_yearpublished),
        array("nom"=>"Nbre de parties jouées:","data"=>$bdd_jeu_detail->jeu_bgg_numplays),
        array("nom"=>"Nbre de joueurs:","data"=>$bdd_jeu_detail->jeu_bgg_minplayers." à ".$bdd_jeu_detail->jeu_bgg_maxplayers),
        array("nom"=>"Nbre de joueurs recommandé:","data"=>$bdd_jeu_detail->jeu_bgg_nb_player_recommended),
        array("nom"=>"Nbre de joueurs best:","data"=>$bdd_jeu_detail->jeu_bgg_nb_player_best),
        array("nom"=>"Note","data"=>$bdd_jeu_detail->jeu_bgg_note),
        array("nom"=>"Difficulté:","data"=>$bdd_jeu_detail->jeu_bgg_averageweight),
);

$tableau_data=null;
foreach ($data_array as $data_actu){
    $tableau_data.='<tr>';
    $tableau_data.='<th scope="row" style="text-align: right;">'.$data_actu["nom"].'</th>';
    $tableau_data.='<td  style="text-align: left;">'.$data_actu["data"].'</td>';
    $tableau_data.='</tr>';
    $tableau_data.='';
}

$description=null;
if(is_null($bdd_jeu_detail->jeu_description_fr)){
    $description.="pas en FR<br>";
    $description.=$bdd_jeu_detail->jeu_bgg_description;
}else{
    $description.=$bdd_jeu_detail->jeu_description_fr;
}
//var_dump($description);
?>

<main>
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title"><?php echo $bdd_jeu_detail->jeu_nom; ?></h1>
                <p class="card-text" style="text-align: left;"><?php echo $description; ?></p>
                <?php

                ?>
            </div>
            <div>
                <div class="col-4">
                    <table class="table">
                        <tbody>
                        <?php echo $tableau_data; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-4">
                    <img src="<?php echo $bdd_jeu_detail->jeu_bgg_image; ?>" class="img-fluid" alt="...">
                </div>
            </div>
        </div>
    </div>
</main>



<script src="../vendor/Highcharts-9.2.2/code/highcharts.js"></script>
<script src="../vendor/Highcharts-9.2.2/code/modules/series-label.js"></script>
<script src="../vendor/Highcharts-9.2.2/code/modules/exporting.js"></script>
<script src="../vendor/Highcharts-9.2.2/code/modules/export-data.js"></script>
<script src="../vendor/Highcharts-9.2.2/code/modules/accessibility.js"></script>
<?php
//include_once dirname(__FILE__) . '/../graph/graph_global_langue.php';
//include_once dirname(__FILE__) . '/../map/data/LMB_map1_0.php';



include_once dirname(__FILE__) . '/../graph/graph1.php';




include_once dirname(__FILE__) . '/../structure/footer.php';
?>
