<?php
include_once dirname(__FILE__) . '/../structure/sql.php';
include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];
require dirname(__FILE__) . '/../vendor/autoload.php';
?>

<?php
/*$sql_liste_jeu = "SELECT\n"
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
*/
// initialize client
require_once 'vendor/autoload.php';
use ApaiIO\ApaiIO;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Lookup;

/**
 * Gets ASIN by url of a product
 */
function getASIN($url) {
    $pattern = "%/([a-zA-Z0-9]{10})(?:[/?]|$)%";
    preg_match($pattern, $url, $matches);
    if($matches && isset($matches[1])) {
        $asin = $matches[1];
    } else {
        echo "Couldn\'t parse url and extract ASIN: {$url}
\n";
        return false;
    }
    return $asin;
}

$url = 'http://www.amazon.com/gp/product/1491910291/ref=as_li_tl?ie=UTF8&camp=1789&creative=9325&creativeASIN=1491910291&linkCode=as2&tag=achgu-20&linkId=A3CZKDVUDYL7PUFB';

$asin = getASIN($url);

echo "ASIN: $asin\n";

$conf = new GenericConfiguration();
try {

    $conf->setCountry('com')
        ->setAccessKey('YOUR ACCESS KEY')
        ->setSecretKey('YOUR SECRET KEY')
        ->setAssociateTag('YOUR ASSOCIATE TAG');

} catch (\Exception $e) {
    echo $e->getMessage();
}
$apaiIO = new ApaiIO($conf);
$lookup = new Lookup();

$lookup->setItemId($asin);
$lookup->setIdType('ASIN');
$lookup->setResponseGroup(['Offers']);
$conf->setResponseTransformer('\ApaiIO\ResponseTransformer\XmlToSimpleXmlObject');
$formattedResponse = $apaiIO->runOperation($lookup);
$price = $formattedResponse->Items->Item->OfferSummary->LowestNewPrice->Amount / 100;

echo "Price: $ $price\n";

?>

<main>
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div class="accordion" id="category">
            <div class="accordion-item">
                <h2 class="accordion-header" id="header_electronic">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#electronic" aria-expanded="false" aria-controls="electronic" style="padding-top: 0px; padding-bottom: 0px;">
                        <div style="width: 25%" class="lead fw-normal">Electronic</div>
                        <div style="width: 50%" class="progress lead fw-normal">
                            <div class="progress-bar" role="progressbar" style="width: 1%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </button>
                </h2>

                <div id="electronic" class="accordion-collapse collapse" aria-labelledby="header_electronic" data-bs-parent="#category">
                    <div class="accordion-body">
                        <ul class="list-group">
                            <li class="list-group-item" style="text-align: left;padding-top: 0px;padding-bottom: 0px;">Celestia</li>
                        </ul>
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
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Accordion Item #3
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#category">
                    <div class="accordion-body">
                        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
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
