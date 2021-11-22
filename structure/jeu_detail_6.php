<?php
include_once dirname(__FILE__) . '/../structure/sql.php';
include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];

?>



<?php
$array_pagnation=array();
$sql_pagnation = "SELECT \n"
    . "	    `jeu_id`\n"
    . "    ,`jeu_nom`\n"
    . "FROM `jeu` \n"
    . "WHERE `jeu_bgg_subtype` LIKE 'boardgame' \n"
    . "ORDER BY `jeu_nom`  ASC;";
//echo "<p>".$sql_pagnation."</p>";
$res_pagnation = mysqli_query ($ezine_db, $sql_pagnation) or ezine_mysql_die($ezine_db, $sql_pagnation) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_pagnation=mysqli_num_rows($res_pagnation);
while ($bdd_pagnation = mysqli_fetch_object($res_pagnation)){
    $array_pagnation[]=$bdd_pagnation->jeu_id;
}
//var_dump($array_pagnation);

$pagnation_nbre=20;
$pagnation_compress=null;
$pagation_txt=null;
$id=$array_pagnation[0];
$key_pagnation=0;
if(isset($_GET["id"])){
    $id=$_GET["id"];
    $key_pagnation = array_search($id, $array_pagnation);
}
if($key_pagnation<=0){
    $pagation_txt.='<li class="page-item disabled"><a class="page-link">Précédent</a></li>';
}else{
    $pagation_txt.='<li class="page-item"><a class="page-link" href="'.FILE.'?id='.$array_pagnation[$key_pagnation-1].'">Précédent</a></li>';
}

if($key_pagnation + ($pagnation_nbre/2) >= $nbre_pagnation ){
    $min_pagnation=$nbre_pagnation-$pagnation_nbre;
    //var_dump("ff");
}else{

    $min_pagnation=$key_pagnation-($pagnation_nbre/2)+1;
}
if($key_pagnation-($pagnation_nbre/2) < 0){
    $max_pagnation=$pagnation_nbre;
}else{
    $max_pagnation=$key_pagnation+($pagnation_nbre/2)+1;
}
foreach ($array_pagnation as $key=>$pagnation_actu){
    if($key_pagnation >= ($pagnation_nbre/2) and is_null($pagnation_compress)){
        //var_dump($key_pagnation);
        //var_dump($pagnation_nbre);
        $pagnation_compress=1;
        $pagation_txt.='<li class="page-item disabled"><a class="page-link">...</a></li>';
    }
    if((($key >= $min_pagnation) and ($key < $max_pagnation))
    ){
        if($key==$key_pagnation){
            $pagation_txt.='<li class="page-item active" aria-current="page"><a class="page-link">'.($key+1).'</a></li>';
        }else{
            $pagation_txt.='<li class="page-item"><a class="page-link" href="'.FILE.'?id='.$array_pagnation[$key].'">'.($key+1).'</a></li>';
        }
    }
}
if($key_pagnation < $nbre_pagnation-1-($pagnation_nbre/2)){
    $pagation_txt.='<li class="page-item disabled"><a class="page-link">...</a></li>';
}
if($key_pagnation>=$nbre_pagnation-1){
    $pagation_txt.='<li class="page-item disabled"><a class="page-link">Suivant</a></li>';
}else{
    $pagation_txt.='<li class="page-item"><a class="page-link" href="'.FILE.'?id='.$array_pagnation[$key_pagnation+1].'">Suivant</a></li>';
}

//var_dump($key_pagnation);



$sql_jeu_detail = "SELECT *\n"
    . "FROM `jeu`\n"
    . "WHERE `jeu_id` = ".$id;
//echo "<p>".$sql_jeu_detail."</p>";
$res_jeu_detail = mysqli_query ($ezine_db, $sql_jeu_detail) or ezine_mysql_die($ezine_db, $sql_jeu_detail) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_jeu_detail=mysqli_num_rows($res_jeu_detail);
$bdd_jeu_detail = mysqli_fetch_object($res_jeu_detail);

function enlever_trait($txt){
    //$sortie=str_replace("|"," ",$txt);
    $array= explode("|",$txt);
    $array = array_merge(array_filter($array));
    //var_dump($array);
    $base=$array[0];
    //var_dump($base);
    $old=null;
    $last=$array[0];
    $est_suivit=true;
    $nbre=count($array);
    if($nbre==1){
        $sortie=$base;
    }elseif($nbre==0){
        $sortie=null;
    }else{
        foreach ($array as $actu){
            //var_dump($actu);
            //var_dump($old);
            if(!is_null($old)){
                //var_dump($old);
                if($old+1 == $actu){
                    $last=$actu;
                    //var_dump($last);
                    //$est_suivit=true;
                }else{
                    $est_suivit=false;
                    //var_dump("false");
                }

            }
            $old=$actu;
        }
        if($est_suivit==true){
            $sortie=$base." à ".$last;
        }else{
            $sortie=implode(",",$array);
        }
    }

    //var_dump("-------".$sortie."-------");
    return $sortie;
}

$data_array=array(
    array("nom"=>"Volume:","data"=>$bdd_jeu_detail->jeu_x." x ".$bdd_jeu_detail->jeu_y." x ".$bdd_jeu_detail->jeu_z),
    array("nom"=>"Poids:","data"=>$bdd_jeu_detail->jeu_poids." Kg"),
    array("nom"=>"Année de publication:","data"=>$bdd_jeu_detail->jeu_bgg_yearpublished),
    array("nom"=>"Nbre de parties jouées:","data"=>$bdd_jeu_detail->jeu_bgg_numplays),
    array("nom"=>"Nbre de joueurs:","data"=>$bdd_jeu_detail->jeu_bgg_minplayers." à ".$bdd_jeu_detail->jeu_bgg_maxplayers),
    array("nom"=>"Nbre de joueurs recommandé:","data"=>enlever_trait($bdd_jeu_detail->jeu_bgg_nb_player_recommended)),
    array("nom"=>"Nbre de joueurs best:","data"=>enlever_trait($bdd_jeu_detail->jeu_bgg_nb_player_best)),
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


$sql_liste_extention = "SELECT *,\n"
    . "CASE\n"
    . "	WHEN `expansion_bgg_id` IN ( \n"
    . "        SELECT `jeu_bgg_id`\n"
    . "		FROM `jeu`\n"
    . "		WHERE `jeu_bgg_subtype` LIKE 'boardgameexpansion'\n"
    . "    ) THEN 1\n"
    . "    else 0\n"
    . "end AS `posedee`\n"
    . "FROM `expansion_jeu`\n"
    . "LEFT JOIN `expansion` ON `expansion`.`expansion_id` = `expansion_jeu`.`expansion`\n"
    . "WHERE `jeu` = '".$id."'"
    . "ORDER BY `posedee` DESC,`expansion`.`expansion_bgg_id` ASC ";
//echo "<p>".$sql_liste_extention."</p>";
$res_liste_extention = mysqli_query ($ezine_db, $sql_liste_extention) or ezine_mysql_die($ezine_db, $sql_liste_extention) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_extention=mysqli_num_rows($res_liste_extention);
$extention_data=null;
while($bdd_liste_extention = mysqli_fetch_object($res_liste_extention)){
    $extention_data.='<tr>';

    if($bdd_liste_extention->posedee==1){
        $sql_detail_ext = "SELECT `jeu_id` \n"
            . "FROM `jeu` \n"
            . "WHERE `jeu_bgg_id` = '".$bdd_liste_extention->expansion_bgg_id."';";
        //echo "<p>".$sql_detail_ext."</p>";
        $res_detail_ext = mysqli_query ($ezine_db, $sql_detail_ext) or ezine_mysql_die($ezine_db, $sql_detail_ext) ;
//$num_ticket=mysqli_insert_id($ezine_db);
        $nbre_detail_ext=mysqli_num_rows($res_detail_ext);
        $bdd_detail_ext = mysqli_fetch_object($res_detail_ext);

        $extention_data.='<td colspan="2" style="text-align: left;"><a class="btn btn-primary" href="'.FILE.'?id='.$bdd_detail_ext->jeu_id.'" role="button">'.$bdd_liste_extention->expansion_nom_en.'</a></td>';
    }else{
        $extention_data.='<th scope="row" style="text-align: right;">'.$bdd_liste_extention->expansion_nom_en.'</th>';
        $extention_data.='<td  style="text-align: left;"><a class="btn btn-bgg btn-sm" href="https://www.boardgamegeek.com/boardgame/'.$bdd_liste_extention->expansion_bgg_id.'/" role="button"><img src="../vendor/BGG/navbar-logo-bgg-b2.svg" alt="lien BGG" height="15px"  /></a></td>';
    }
    //$extention_data.='<td  style="text-align: left;"><a class="btn btn-primary" href="#" role="button">Link</a>'.$bdd_liste_extention->posedee.'</td>';

    $extention_data.='</tr>';
    $extention_data.='';
}
$graph_data=null;
$sql_graph_cat = "SELECT `evolution_note_type`,`evolution_note_type_nom`,count(*) as nbre\n"
    . "FROM `evolution_note` \n"
    . "LEFT JOIN `evolution_note_type` ON `evolution_note_type`.`evolution_note_type_id`=`evolution_note`.`evolution_note_type`\n"
    . "WHERE `jeu` = '".$id."' \n"
    . "GROUP BY `evolution_note_type`;";
//echo "<p>".$sql_graph_cat."</p>";
$res_graph_cat = mysqli_query ($ezine_db, $sql_graph_cat) or ezine_mysql_die($ezine_db, $sql_graph_cat) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_graph_cat=mysqli_num_rows($res_graph_cat);
while($bdd_graph_cat = mysqli_fetch_object($res_graph_cat)){
    $graph_data.="{name: '".$bdd_graph_cat->evolution_note_type_nom."',data: [";
    //var_dump($bdd_graph_cat->evolution_note_type);
    $sql_graph_global = "SELECT *  \n"
        . "FROM `evolution_note` \n"
        . "WHERE `jeu` = '".$id."'  \n"
        . "AND `evolution_note_type` = '".$bdd_graph_cat->evolution_note_type."'  \n"
        . "ORDER BY `evolution_note`.`evolution_note_date` ASC;";
    //echo "<p>".$sql_graph_global."</p>";
    $res_graph_global = mysqli_query ($ezine_db, $sql_graph_global) or ezine_mysql_die($ezine_db, $sql_graph_global) ;
    //$num_ticket=mysqli_insert_id($ezine_db);
    $nbre_graph_global=mysqli_num_rows($res_graph_global);
    while($bdd_graph_global = mysqli_fetch_object($res_graph_global)){
        //var_dump($bdd_graph_global);
        $date_javascript= date('Y,m-1,d,H,i,s',strtotime($bdd_graph_global->evolution_note_date));
        $graph_data.="[Date.UTC(".$date_javascript."), ".$bdd_graph_global->evolution_note_a."],";
        $derniere_val= $bdd_graph_global->evolution_note_a;
    }
    //var_dump($derniere_val."@");
    $date_javascript_now= date('Y,m-1,d,H,i,s');
    $graph_data.="[Date.UTC(".$date_javascript_now."), ".$derniere_val."],";
    $graph_data.="],step: true,},";
}
//var_dump("gg");

?>

<main>
    <div class=" overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title"><?php echo $bdd_jeu_detail->jeu_nom; ?></h1>
            </div>
            <div class="card-body">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php echo $pagation_txt; ?>
                    </ul>
                </nav>
                <p class="card-text" style="text-align: left;"><?php echo $description; ?></p>
            </div>
            <div class="d-flex justify-content-between align-items-start">
                <img src="<?php echo $bdd_jeu_detail->jeu_bgg_image; ?>" class="img-fluid" style="width: 33%">
                <div >
                    <table class="table">
                        <tbody>
                        <?php echo $tableau_data; ?>
                        </tbody>
                    </table>
                </div>
                <div >
                    <table class="table">
                        <tbody>
                        <?php echo $extention_data; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h1 class="card-title">details</h1>
            </div>
            <div class="card-body">
                <figure class="highcharts-figure">
                    <div id="graph3"></div>
                    <p class="highcharts-description">
                        Basic line chart showing trends in a dataset. This chart includes the
                        <code>series-label</code> module, which adds a label to each line for
                        enhanced readability.
                    </p>
                </figure>
            </div>
        </div>
    </div>
</main>

<script src="../vendor/Highcharts-Stock-9.2.2/code/highstock.js"></script>
<script src="../vendor/Highcharts-Stock-9.2.2/code/modules/data.js"></script>
<script src="../vendor/Highcharts-Stock-9.2.2/code/modules/exporting.js"></script>
<script src="../vendor/Highcharts-Stock-9.2.2/code/modules/export-data.js"></script>
<?php
//include_once dirname(__FILE__) . '/../graph/graph_global_langue.php';
//include_once dirname(__FILE__) . '/../map/data/LMB_map1_0.php';



include_once dirname(__FILE__) . '/../graph/graph3.php';


include_once dirname(__FILE__) . '/../structure/footer.php';
?>