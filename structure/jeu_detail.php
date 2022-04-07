<?php




include_once dirname(__FILE__) . '/../structure/sql.php';
include_once dirname(__FILE__) . '/../structure/header.php';

include_once dirname(__FILE__) . '/../function/media_type_MIME.php';
//echo $_SERVER['SERVER_NAME'];

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


$sql_jeu_detail = "SELECT * ,`duree_moyenne`\n"
    . "FROM `jeu` \n"
    . "LEFT JOIN (\n"
    . "    SELECT `jeu`, AVG(`jeu_partie_duree`) AS `duree_moyenne`\n"
    . "    FROM `jeu_partie` \n"
    . "    WHERE `jeu` = ".$id."\n"
    . "    GROUP BY `jeu`  ) AS `b`  ON `b`.`jeu` = `jeu`.`jeu_id`\n"
    . "WHERE `jeu_id` = ".$id;
//echo "<p>".$sql_jeu_detail."</p>";
$res_jeu_detail = mysqli_query ($ezine_db, $sql_jeu_detail) or ezine_mysql_die($ezine_db, $sql_jeu_detail) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_jeu_detail=mysqli_num_rows($res_jeu_detail);
$bdd_jeu_detail = mysqli_fetch_object($res_jeu_detail);

function data_cat($ezine_db,$id, $cat,$bdd){
    $sql_editeur = "SELECT `jeu`,`".$bdd."_id`,`".$bdd."_nom_en`,`".$bdd."_nom_fr` \n"
        . "FROM `".$bdd."_jeu`\n"
        . "left JOIN `".$bdd."` ON `".$bdd."`.`".$bdd."_id` = `".$bdd."_jeu`.`".$bdd."`\n"
        . "WHERE `jeu` = ".$id."\n"
        . "ORDER BY `".$bdd."_nom_en` ASC;";
//echo "<p>".$sql_editeur."</p>";
    $res_editeur = mysqli_query ($ezine_db, $sql_editeur) or ezine_mysql_die($ezine_db, $sql_editeur) ;
//$num_ticket=mysqli_insert_id($ezine_db);
    $nbre_editeur=mysqli_num_rows($res_editeur);
    $ligne_editeur=null;
    if($nbre_editeur == 1){
        $bdd_editeur = mysqli_fetch_object($res_editeur);
        //$ligne_editeur.=$bdd_editeur->boardgamepublisher_nom_en;
        $ligne_editeur.= '<button type="submit" name="r_'.$cat.'" value="'.$bdd_editeur->{$bdd . "_id"}.'" class="btn btn-link" style="padding: 0px;">'.$bdd_editeur->{$bdd . "_nom_en"}.'</button>';
    }elseif($nbre_editeur > 1){
        $ligne_editeur.= "<ul>";
        while($bdd_editeur = mysqli_fetch_object($res_editeur)) {
            //$ligne_editeur.= "<li>".$bdd_editeur->boardgamepublisher_nom_en."</li>";
            $ligne_editeur.= '<li><button type="submit" name="r_'.$cat.'" value="'.$bdd_editeur->{$bdd."_id"}.'" class="btn btn-link" style="padding: 0px;">'.$bdd_editeur->{$bdd."_nom_en"}.'</button></li>';
        }
        $ligne_editeur.= "</ul>";
    }else{}
    //return $nbre_editeur;
    return $ligne_editeur;
}
/*
$ligne_editeur=data_cat($ezine_db,$id,"editeur","boardgamepublisher");
$ligne_artist=data_cat($ezine_db,$id,"artist","artist");
$ligne_createur=data_cat($ezine_db,$id,"createur","designer");*/
//var_dump(data_cat($ezine_db,$id,"artist","artist"));
//var_dump($ligne_editeur);

function enlever_trait($txt){
    //var_dump($txt);

    if (is_null($txt)) {

        return "aucune";
    }
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
function convertToHoursMins($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}

//echo convertToHoursMins($bdd_jeu_detail->jeu_bgg_playingtime); // should output 4 hours 17 minutes


$data_array=array(
    array("nom"=>"Volume","data"=>$bdd_jeu_detail->jeu_x." x ".$bdd_jeu_detail->jeu_y." x ".$bdd_jeu_detail->jeu_z." mm", "logo" => "fas fa-ruler-combined","masque"=>" x  x  mm"),
    array("nom"=>"Poids","data"=>$bdd_jeu_detail->jeu_poids." Kg", "logo" => "fas fa-weight-hanging","masque"=>" Kg"),
    array("nom"=>"Année de publication","data"=>$bdd_jeu_detail->jeu_bgg_yearpublished, "logo" => "fas fa-calendar-alt","masque"=>null),
    array("nom"=>"Nbre de parties jouées","data"=>$bdd_jeu_detail->jeu_bgg_numplays, "logo" => "fas fa-calculator","masque"=>null),
    array("nom"=>"Nbre de joueurs","data"=>$bdd_jeu_detail->jeu_bgg_minplayers." à ".$bdd_jeu_detail->jeu_bgg_maxplayers, "logo" => "fas fa-users","masque"=>null),
    array("nom"=>"Nbre de joueurs recommandé","data"=>enlever_trait($bdd_jeu_detail->jeu_bgg_nb_player_recommended), "logo" => "fas fa-users","masque"=>null),
    array("nom"=>"Nbre de joueurs best","data"=>enlever_trait($bdd_jeu_detail->jeu_bgg_nb_player_best), "logo" => "fas fa-users","masque"=>null),
    array("nom"=>"Note","data"=>$bdd_jeu_detail->jeu_bgg_note, "logo" => "fas fa-crown","masque"=>null),
    array("nom"=>"Difficulté","data"=>$bdd_jeu_detail->jeu_bgg_averageweight, "logo" => "fas fa-balance-scale","masque"=>null),
    array("nom"=>"Durée","data"=>convertToHoursMins($bdd_jeu_detail->jeu_bgg_playingtime)."", "logo" => "fas fa-clock","masque"=>null),
    array("nom"=>"Durée moyenne constatée","data"=>convertToHoursMins($bdd_jeu_detail->duree_moyenne)."", "logo" => "fas fa-clock","masque"=>null),
    array("nom"=>"éditeur","data"=>data_cat($ezine_db,$id,"editeur","boardgamepublisher"), "logo" => "far fa-copyright","masque"=>null),
    array("nom"=>"Créateur","data"=>data_cat($ezine_db,$id,"createur","designer"), "logo" => "fas fa-hat-wizard","masque"=>null),
    array("nom"=>"Artiste","data"=>data_cat($ezine_db,$id,"artist","artist"), "logo" => "fas fa-paint-brush","masque"=>null),
);

/*$ligne_editeur=data_cat($ezine_db,$id,"editeur","boardgamepublisher");
$ligne_artist=data_cat($ezine_db,$id,"artist","artist");
$ligne_createur=data_cat($ezine_db,$id,"createur","designer");*/


$tableau_data=null;
foreach ($data_array as $data_actu){
    //var_dump("<br>");
    //echo "<br>";
    //var_dump(empty($data_actu["data"]));
    /*var_dump($data_actu["data"]);
    var_dump($data_actu["masque"]);
    echo "<hr>";
    var_dump(!is_null($data_actu["data"]));
    var_dump($data_actu["data"]!=$data_actu["masque"]);
    echo "<hr>";
    echo "<hr>";*/

    if(!is_null($data_actu["data"]) AND $data_actu["data"]!=$data_actu["masque"]){
        $tableau_data.='<tr data-bs-toggle="tooltip" data-bs-placement="left" title="'.$data_actu["nom"].'">';
        $tableau_data.='<th scope="row" style="text-align: right;"><i class="'.$data_actu["logo"].'"></i></th>';
        //$tableau_data.='<th scope="row" style="text-align: right;"><i class="'.$data_actu["logo"].'"></i>'.$data_actu["nom"].'</th>';
        //$tableau_data.='<th scope="row" style="text-align: right;"><button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="left" title="Tooltip on left">Tooltip on left</button></th>';
        $tableau_data.='<td  style="text-align: left;">'.$data_actu["data"].'</td>';
        $tableau_data.='</tr>';
        $tableau_data.='';
    }
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
$extention_data_own=null;
$extention_data_no_own=null;
while($bdd_liste_extention = mysqli_fetch_object($res_liste_extention)){

    if($bdd_liste_extention->posedee==1){
        $sql_detail_ext = "SELECT `jeu_id` \n"
            . "FROM `jeu` \n"
            . "WHERE `jeu_bgg_id` = '".$bdd_liste_extention->expansion_bgg_id."';";
        //echo "<p>".$sql_detail_ext."</p>";
        $res_detail_ext = mysqli_query ($ezine_db, $sql_detail_ext) or ezine_mysql_die($ezine_db, $sql_detail_ext) ;
//$num_ticket=mysqli_insert_id($ezine_db);
        $nbre_detail_ext=mysqli_num_rows($res_detail_ext);
        $bdd_detail_ext = mysqli_fetch_object($res_detail_ext);
        $extention_data_own.='<tr>';
        $extention_data_own.='<td colspan="2" style="text-align: left;"><a class="btn btn-primary" href="'.FILE.'?id='.$bdd_detail_ext->jeu_id.'" role="button">'.$bdd_liste_extention->expansion_nom_en.'</a></td>';
        $extention_data_own.='</tr>';
    }else{
        $extention_data_no_own.='<tr>';
        $extention_data_no_own.='<th scope="row" style="text-align: right;">'.$bdd_liste_extention->expansion_nom_en.'</th>';
        $extention_data_no_own.='<td  style="text-align: left;"><a class="btn btn-bgg btn-sm" href="https://www.boardgamegeek.com/boardgame/'.$bdd_liste_extention->expansion_bgg_id.'/" role="button"><img src="../vendor/BGG/navbar-logo-bgg-b2.svg" alt="lien BGG" height="15px"  /></a></td>';
        $extention_data_no_own.='</tr>';
    }
    //$extention_data.='<td  style="text-align: left;"><a class="btn btn-primary" href="#" role="button">Link</a>'.$bdd_liste_extention->posedee.'</td>';


    $extention_data_own.='';
}

$affichage_media=null;
$media_last=null;

$sql_liste_jeu = "SELECT `jeu_media_type`,`jeu_media_type_nom_fr`, `jeu_media_dossier`,`jeu_media_fichier`,`jeu_media_MIME`\n"
    . "FROM `jeu_media` \n"
    . "LEFT JOIN `jeu_media_type` ON `jeu_media_type`.`jeu_media_type_id`=`jeu_media`.`jeu_media_type`\n"
    . "WHERE `jeu` =".$id.";";
//echo "<p>".$sql_liste_jeu."</p>";
$res_liste_jeu = mysqli_query ($ezine_db, $sql_liste_jeu) or ezine_mysql_die($ezine_db, $sql_liste_jeu) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_jeu=mysqli_num_rows($res_liste_jeu);
if($nbre_liste_jeu>=1){
    while($bdd_liste_jeu = mysqli_fetch_object($res_liste_jeu)){
        if($media_last != $bdd_liste_jeu->jeu_media_type){
            if(!is_null($affichage_media)){
                $affichage_media.='<hr>';
            }
            $affichage_media.='<h5 class="card-title">'.$bdd_liste_jeu->jeu_media_type_nom_fr.'</h5>';
        }else{
            $affichage_media.=' ';
        }
        $affichage_media.='<a role="button" class="btn btn-outline-secondary" href="'.$bdd_liste_jeu->jeu_media_dossier.$bdd_liste_jeu->jeu_media_fichier.'" target="_blank">';
        //$affichage_media.='<a class="btn btn-outline-secondary" href="#" role="button"><h3><i class="far fa-file-pdf"></i></h3></a> ';


        //var_dump($bdd_liste_jeu->jeu_media_MIME);
        $affichage_media.=media_type_MIME($bdd_liste_jeu->jeu_media_MIME);



        $affichage_media.='</a>';

        $media_last=$bdd_liste_jeu->jeu_media_type;
    }
}



$graph_data=null;
$sql_graph_cat = "SELECT `evolution_note_type`,`evolution_note_type_nom`,count(*) as nbre,`jeu_bgg_note_bayesienne`,`evolution_note_type_sens_graph`\n"
    . "FROM `evolution_note` \n"
    . "LEFT JOIN `evolution_note_type` ON `evolution_note_type`.`evolution_note_type_id`=`evolution_note`.`evolution_note_type`\n"
    . "LEFT JOIN `jeu` ON `jeu`.`jeu_id`= `evolution_note`.`jeu`\n"
    . "WHERE `jeu` = '".$id."' \n"
    . "GROUP BY `evolution_note_type`;";
//echo "<p>".$sql_graph_cat."</p>";
$res_graph_cat = mysqli_query ($ezine_db, $sql_graph_cat) or ezine_mysql_die($ezine_db, $sql_graph_cat) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_graph_cat=mysqli_num_rows($res_graph_cat);
while($bdd_graph_cat = mysqli_fetch_object($res_graph_cat)){
    $visible= "false";
    if(
        ($bdd_graph_cat->evolution_note_type_nom=="jeu_bgg_note" && $bdd_graph_cat->jeu_bgg_note_bayesienne == 0)
        or ($bdd_graph_cat->evolution_note_type_nom=="jeu_bgg_note_bayesienne" && $bdd_graph_cat->jeu_bgg_note_bayesienne != 0)
    ){
        $visible=true;
    }

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
    $graph_data.="],step: true,";
    $graph_data.="yAxis:".$bdd_graph_cat->evolution_note_type_sens_graph.",";
    $graph_data.="visible:".$visible.",},";
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
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start" >
                    <div>
                        <img src="<?php echo $bdd_jeu_detail->jeu_bgg_image; ?>" class="img-fluid" style="width: 33%">
                        <p class="card-text" style="text-align: left;"><?php echo $description; ?></p>
                    </div>
                    <div class="col-2">
                        <div >
                            <table class="table">
                                <tbody>
                                <form role="form" action="../web/liste_jeux.php" method="post" enctype="multipart/form-data" name="form_recherche">
                                <?php echo $tableau_data; ?>
                                </form>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        //var_dump($extention_data_own);
                        if(!is_null($extention_data_own) or !is_null($extention_data_no_own)){
                        ?>
                        <div >
                            <?php
                            //var_dump($extention_data_own);
                            if(!is_null($extention_data_own)){
                                ?>
                                <table class="table">
                                    <tbody>
                                    <?php echo $extention_data_own; ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            //var_dump($extention_data_no_own);
                            if(!is_null($extention_data_no_own)){
                                ?>
                                <button class="btn btn-bgg" type="button" data-bs-toggle="collapse" data-bs-target="#extentions_bgg" aria-expanded="false" aria-controls="extentions_bgg">
                                    BGG extentions
                                </button>
                                <div class="collapse" id="extentions_bgg">
                                    <table class="table">
                                        <tbody>
                                        <?php echo $extention_data_no_own; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            }
                            echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(!is_null($affichage_media)){ ?>
            <balise id="media">
                <div class="card mt-3">
                    <div class="card-header">
                        <h1 class="card-title">Média</h1>
                    </div>
                    <div class="card-body">
                        <?php echo $affichage_media; ?>
                    </div>
                </div>
            </balise>
            <?php } ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h1 class="card-title">details</h1>
                </div>
                <div class="card-body">
                    <figure class="highcharts-figure">
                        <div id="graph3"></div>
                    </figure>
                </div>
            </div>
        </div>
</main>


<script src="../node_modules/highcharts/highstock.js"></script>
<script src="../node_modules/highcharts/modules/data.js"></script>
<script src="../node_modules/highcharts/modules/exporting.js"></script>
<script src="../node_modules/highcharts/modules/export-data.js"></script>

<?php
//include_once dirname(__FILE__) . '/../graph/graph_global_langue.php';
//include_once dirname(__FILE__) . '/../map/data/LMB_map1_0.php';


include_once dirname(__FILE__) . '/../graph/graph3.php';


include_once dirname(__FILE__) . '/../structure/footer.php';
?>
