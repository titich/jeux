<?php
include_once dirname(__FILE__) . '/../structure/sql.php';

include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];

?>

<?php
$sql_liste_jeu = "SELECT\n"
    . "    `category_jeu_id`,\n"
    . "    `category_nom_en`,\n"
    . "    `nbre`\n"
    . "FROM `v_nbre_par_category`\n"
    . "ORDER BY `v_nbre_par_category`.`nbre`DESC;";
//echo "<p>".$sql_liste_jeu."</p>";
$res_liste_jeu = mysqli_query ($ezine_db, $sql_liste_jeu) or ezine_mysql_die($ezine_db, $sql_liste_jeu) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_jeu=mysqli_num_rows($res_liste_jeu);
while($bdd_liste_jeu = mysqli_fetch_object($res_liste_jeu)){}


$deepLy = new ChrisKonnertz\DeepLy\DeepLy();

$translatedText = $deepLy->translate('Hello world!', 'DE', 'EN');

echo $translatedText; // Prints "Hallo Welt!"

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
