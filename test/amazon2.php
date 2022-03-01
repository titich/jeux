<?php
include_once dirname(__FILE__) . '/../structure/sql.php';
include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];
require dirname(__FILE__) . '/../vendor/autoload.php';
require dirname(__FILE__) . '/../cle/cle.php';


$api_key = $api_key_amazon;
$country = 'CH';
$q = urlencode($_GET['q']);
$startIndex = (isset($_GET['start'])) ? $_GET['start'] : '0';
$maxResults = 10;
$uri = 'https://www.googleapis.com/shopping/search/v1/public/products?thumbnails=110:*&key='.$api_key.'&country='.$country.'&q='.$q.'&startIndex='.$startIndex.'&maxResults='.$maxResults;

var_dump($uri);
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
