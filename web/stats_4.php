<?php
include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];
include_once dirname(__FILE__) . '/../structure/sql.php';
?>

<?php
$array_link_category=array("category","mechanic","designer","artist");

function cat_graph($ezine_db,$actu){
    $graph_categoty_stats="series: [{data: [";
    $sql_category_stats = "SELECT\n"
        . "    `".$actu."_jeu_id`,\n"
        . "    `".$actu."_nom_en`,\n"
        . "    `nbre`\n"
        . "FROM `v_nbre_par_".$actu."`\n"
        . "ORDER BY `v_nbre_par_".$actu."`.`nbre`DESC\n"
        . "LIMIT 0,500";
//echo "<p>".$sql_category_stats."</p>";
    $res_category_stats = mysqli_query ($ezine_db, $sql_category_stats) or ezine_mysql_die($ezine_db, $sql_category_stats) ;
//$num_ticket=mysqli_insert_id($ezine_db);
    $nbre_category_stats=mysqli_num_rows($res_category_stats);
//var_dump($bdd_category_stats);
    $array_drilldown=array();
    while($bdd_category_stats = mysqli_fetch_object($res_category_stats)){
        //var_dump($bdd_category_stats);
        $graph_categoty_stats.="{name: '";
        $graph_categoty_stats.=addslashes($bdd_category_stats->{$actu."_nom_en"});
        $graph_categoty_stats.="',y: ";
        $graph_categoty_stats.=$bdd_category_stats->nbre;
        $graph_categoty_stats.=",drilldown: '";
        $graph_categoty_stats.=addslashes($bdd_category_stats->{$actu."_nom_en"});
        $graph_categoty_stats.="'";
        $graph_categoty_stats.="},";

        $data_drilldown=null;

        $sql_drilldown = "SELECT\n"
            . "    `jeu_nom`\n"
            . "FROM\n"
            . "    `".$actu."_jeu`\n"
            . "LEFT JOIN `jeu` ON `jeu`.`jeu_id` = `".$actu."_jeu`.`jeu`\n"
            . "WHERE\n"
            . "    `".$actu."` = ".$bdd_category_stats->{$actu."_jeu_id"}."";
        $res_drilldown = mysqli_query ($ezine_db, $sql_drilldown) or ezine_mysql_die($ezine_db, $sql_drilldown) ;
        while($bdd_drilldown = mysqli_fetch_object($res_drilldown)){
            $data_drilldown.='["'.addslashes($bdd_drilldown->jeu_nom).'",1],';
        }
        $array_drilldown[]=array($bdd_category_stats->{$actu."_nom_en"},$data_drilldown);

    }
    $graph_categoty_stats.="]}],drilldown: {series: [";
    foreach ($array_drilldown as $dilldown_actu){
        $graph_categoty_stats.='{name: "'.$dilldown_actu[0].'",id: "'.$dilldown_actu[0].'",data: ['.$dilldown_actu[1].']},';
    }
    $graph_categoty_stats.=']}';
    return $graph_categoty_stats;
}
//$graph_categoty_stats=cat_graph($ezine_db,"category");
//$graph_categoty_stats=cat_graph($ezine_db,"artist");



//echo $graph_categoty_stats;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sql_nbre_fait = "SELECT\n"
    . "    COUNT(*) AS `total`,\n"
    . "    COUNT(`jeu_x`) AS `nbre_fait`\n"
    . "FROM\n"
    . "    `jeu`\n"
    . "WHERE\n"
    . "    `jeu_est_boite` = 1;";
//echo "<p>".$sql_nbre_fait."</p>";
$res_nbre_fait = mysqli_query ($ezine_db, $sql_nbre_fait) or ezine_mysql_die($ezine_db, $sql_nbre_fait) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_nbre_fait=mysqli_num_rows($res_nbre_fait);
//var_dump($bdd_nbre_fait);
$bdd_nbre_fait = mysqli_fetch_object($res_nbre_fait);
$progress_bar_pr100=$bdd_nbre_fait->nbre_fait/$bdd_nbre_fait->total*100;
$progress_bar_pr100_txt=number_format($progress_bar_pr100,2,"."," ");
$progress_bar_nbre_fait='<div class="progress-bar" role="progressbar" style="width: '.$progress_bar_pr100_txt.'%;" aria-valuenow="'.$bdd_nbre_fait->nbre_fait.'" aria-valuemin="0" aria-valuemax="'.$bdd_nbre_fait->total.'">'.$progress_bar_pr100_txt.'%</div>';

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sql_date_sortie = "SELECT\n"
    . "	`jeu_bgg_yearpublished`,\n"
    . "    Count(*) AS `Nbre`\n"
    . "FROM `jeu`\n"
    . "WHERE `jeu_bgg_yearpublished` IS NOT NULL\n"
    . "GROUP BY(`jeu_bgg_yearpublished`)  \n"
    . "ORDER BY `jeu`.`jeu_bgg_yearpublished` ASC;";
//echo "<p>".$sql_date_sortie."</p>";
$res_date_sortie = mysqli_query ($ezine_db, $sql_date_sortie) or ezine_mysql_die($ezine_db, $sql_date_sortie) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_date_sortie=mysqli_num_rows($res_date_sortie);
//var_dump($bdd_date_sortie);
//$bdd_date_sortie = mysqli_fetch_all($res_date_sortie);
$sorie_graph2=null;
$annee_en_cours=null;
$premiere_annee=0;
while($bdd_date_sortie = mysqli_fetch_object($res_date_sortie)){
    //var_dump($bdd_date_sortie);
    if(is_null($annee_en_cours)){
        $annee_en_cours=$bdd_date_sortie->jeu_bgg_yearpublished;
        $premiere_annee=$bdd_date_sortie->jeu_bgg_yearpublished;
    }
    while ($annee_en_cours < $bdd_date_sortie->jeu_bgg_yearpublished) {

        $sorie_graph2.="null,";
        //echo " ".$annee_en_cours;
        $annee_en_cours++;
    }
    $sorie_graph2.=$bdd_date_sortie->Nbre.",";
    //echo " @".$annee_en_cours;
    $annee_en_cours++;

}
//var_dump($res_date_sortie);
//var_dump($bdd_date_sortie);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<main>
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <h1 class="display-6 fw-normal">Avancement poids</h1>
        <div class="progress">
            <?php echo $progress_bar_nbre_fait; ?>

        </div>
    </div>
    <?php
    foreach ($array_link_category as $cat_actu){
        echo '<div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">';
        echo '<figure class="highcharts-figure">';
        echo '<div id="'.$cat_actu.'"></div>';
        echo '</figure>';
        echo '</div>';
    }
    ?>

    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <h1 class="display-6 fw-normal">category</h1>
        <div class="accordion" id="accordionExample">

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        <p style="width: 25%" class="lead fw-normal">And an even wittier subheading </p>
                        <div style="width: 50%" class="progress lead fw-normal">
                            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <ul class="list-group">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                            <li class="list-group-item">A fourth item</li>
                            <li class="list-group-item">And a fifth one</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Accordion Item #2
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Accordion Item #3
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <figure class="highcharts-figure">
            <div id="graph2"></div>
        </figure>
    </div>

    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-5">
            <h1 class="display-4 fw-normal">Punny headline</h1>
            <p class="lead fw-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pages.</p>
            <a class="btn btn-outline-secondary" href="#">Coming soon</a>
        </div>
    </div>
</main>



<script src="../vendor/Highcharts-9.2.2/code/highcharts.js"></script>
<script src="../vendor/Highcharts-9.2.2/code/modules/drilldown.js"></script>
<script src="../vendor/Highcharts-9.2.2/code/modules/exporting.js"></script>
<script src="../vendor/Highcharts-9.2.2/code/modules/export-data.js"></script>
<script src="../vendor/Highcharts-9.2.2/code/modules/accessibility.js"></script>
<?php
//include_once dirname(__FILE__) . '/../graph/graph_global_langue.php';
//include_once dirname(__FILE__) . '/../map/data/LMB_map1_0.php';

/*foreach ($array_link_category as $cat_actu){
    $graph_data=cat_graph($ezine_db,$cat_actu);
    include dirname(__FILE__) . '/../graph/graph1.php';
}*/


include_once dirname(__FILE__) . '/../graph/graph2.php';




include_once dirname(__FILE__) . '/../structure/footer.php';
?>