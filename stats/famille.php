<?php
include_once dirname(__FILE__) . '/../structure/sql.php';

include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];
//var_dump($_POST);
$affichage=null;
$cat_select=null;
$cat_sous_select=null;

if(isset($_POST["choix_cat"]) && isset($_POST["choix_sous_cat"])){
    //$txt_requete= $_POST["choix_cat"].": ".$_POST["choix_sous_cat"];
    $cat_select=$_POST["choix_cat"];
    $cat_sous_select=$_POST["choix_sous_cat"];

    $sql_affichage = "SELECT\n"
        . "    `jeu`,\n"
        . "    `jeu_id`,\n"
        . "    `jeu_nom`,\n"
        . "    `jeu_bgg_note`,\n"
        . "    `jeu_bgg_note_nb_vote`,\n"
        . "    `jeu_bgg_averageweight`,\n"
        . "    `jeu_bgg_averageweight_nb_vote`,\n"
        . "    `jeu_bgg_note_bayesienne`,\n"
        . "    `jeu_bgg_subtype`,\n"
        . "    `jeu_bgg_own`\n"
        . "FROM `family`\n"
        . "LEFT JOIN `family_jeu` ON `family_jeu`.`family` = `family`.`family_id`\n"
        . "LEFT JOIN `jeu` ON `jeu`.`jeu_id` = `family_jeu`.`jeu`\n"
        . "WHERE `family_nom_en` LIKE '".$_POST["choix_cat"].":".$_POST["choix_sous_cat"]."';";
    //    . "WHERE `family_nom_en` LIKE '".$txt_requete."';";
    //    . "WHERE `family_nom_en` LIKE 'Admin: Upcoming Releases';";
    //echo "<p>".$sql_affichage."</p>";
    $res_affichage = mysqli_query ($ezine_db, $sql_affichage) or ezine_mysql_die($ezine_db, $sql_affichage) ;
    //$num_affichage=mysqli_insert_id($ezine_db);
    $nbre_affichage=mysqli_num_rows($res_affichage);
    $i=1;
    while($bdd_affichage = mysqli_fetch_object($res_affichage)){
        $affichage.='<tr>';
        $affichage.='<th scope="row">'.$i.'</th>';
        $affichage.='<td><a class="btn btn-primary btn-sm" href="../structure/jeu_detail.php?id='.$bdd_affichage->jeu_id.'" role="button"><i class="fas fa-info"></i></a></td>';
        if(is_null($bdd_affichage->jeu_nom)){
            $affichage.='<td> ID = '.$bdd_affichage->jeu.'</td>';
        }else{
            $affichage.='<td>'.$bdd_affichage->jeu_nom.'</td>';
        }
        $affichage.='<td>'.$bdd_affichage->jeu_bgg_note.'</td>';
        $affichage.='<td>'.$bdd_affichage->jeu_bgg_averageweight.'</td>';
        $affichage.='</tr>';
        $affichage.='';
        $affichage.='';
        $i++;
    }
    //var_dump($nbre_affichage."@");
}
//var_dump($affichage);
?>

<?php
$sql_liste_famille = "SELECT `cat`  \n"
    . "FROM `v_family_cut` \n"
    . "GROUP BY `cat`  \n"
    . "ORDER BY `cat` ASC;";
//echo "<p>".$sql_liste_famille."</p>";
$res_liste_famille = mysqli_query ($ezine_db, $sql_liste_famille) or ezine_mysql_die($ezine_db, $sql_liste_famille) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_famille=mysqli_num_rows($res_liste_famille);
$liste_cat="";
$liste_js_sous_cat="";
while($bdd_liste_famille = mysqli_fetch_object($res_liste_famille)){
    //var_dump($bdd_liste_famille);
    $selected=null;
    if($cat_select==$bdd_liste_famille->cat){
        $selected="selected";
    }
    $liste_cat.='<option '.$selected.' value="'.$bdd_liste_famille->cat.'">'.$bdd_liste_famille->cat.'</option>';

    $sql_sous_cat = "SELECT `cat`,`sous_cat`  \n"
        . "FROM `v_family_cut` \n"
        . "WHERE `cat` LIKE '".$bdd_liste_famille->cat."';";
    //echo "<p>".$sql_sous_cat."</p>";
    $res_sous_cat = mysqli_query ($ezine_db, $sql_sous_cat) or ezine_mysql_die($ezine_db, $sql_sous_cat) ;
    //$num_ticket=mysqli_insert_id($ezine_db);
    $nbre_sous_cat=mysqli_num_rows($res_sous_cat);
    $liste_sous_cat=null;
    while($bdd_sous_cat = mysqli_fetch_object($res_sous_cat)){
        //var_dump($bdd_sous_cat);
        if(!is_null($liste_sous_cat)){
            $liste_sous_cat.="|";
        }
        $liste_sous_cat.=$bdd_sous_cat->sous_cat;

    }
    $liste_js_sous_cat.='case "'.$bdd_liste_famille->cat.'":';
    $liste_js_sous_cat.='chaine_sous_cat = "'.$liste_sous_cat.'";';
    $liste_js_sous_cat.='break;';
}
?>

<main>
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light" style="padding-top: 5px !important;">
        <form role="form" action="<?php echo FILE."?".URL_PAR; ?>" method="post" enctype="multipart/form-data" name="form_1">
            <div class="card">
                <div class="card-body">
                    <div class="input-group">
                        <select id="choix_cat" name="choix_cat" class="form-select" onchange="charger_sous_cat()" >
                            <option hidden disabled selected value="00">Sélectionnez une catégorie</option>
                            <?php echo $liste_cat; ?>
                        </select>
                        <div id="calque_ville" class="" style="">
                        </div>
                        <div id="calque_btn_go" class="" style=""></div>
                    </div>
                </div>
            </div>
        </form>

        <div class="card mt-3 ">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                <?php echo $affichage; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<?php
//include_once dirname(__FILE__) . '/../graph/graph_global_langue.php';
//include_once dirname(__FILE__) . '/../map/data/LMB_map1_0.php';

include_once dirname(__FILE__) . '/../js/villes_dep.php';




include_once dirname(__FILE__) . '/../structure/footer.php';
?>
