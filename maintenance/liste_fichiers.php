<?php
include_once dirname(__FILE__) . '/../structure/sql.php';

include_once dirname(__FILE__) . '/../structure/header.php';

include_once dirname(__FILE__) . '/../function/media_type_MIME.php';
include_once dirname(__FILE__) . '/../function/slugify.php';

//echo $_SERVER['SERVER_NAME'];
//var_dump($_POST);

$sql_liste_jeu = "SELECT\n"
    . "    `jeu_media_id`,\n"
    . "    `jeu_nom`,\n"
    . "    `jeu_media_type`,\n"
    . "    `jeu_media_type_nom_fr`,\n"
    . "    `jeu_media_MIME`,\n"
    . "    `jeu_media_dossier`,\n"
    . "    `jeu_media_fichier`,\n"
    . "    `language`,\n"
    . "    `jeu_media_a_imprimer`,\n"
    . "    `jeu_media_officiel`\n"
    . "FROM\n"
    . "    `jeu_media`\n"
    . "LEFT JOIN `jeu` ON `jeu`.`jeu_id` = `jeu_media`.`jeu`\n"
    . "LEFT JOIN `jeu_media_type` ON `jeu_media_type`.`jeu_media_type_id`=`jeu_media`.`jeu_media_type`\n"
    . "WHERE\n"
    . "    `language` IS NULL\n"
    . "     OR `jeu_media_a_imprimer` IS NULL\n"
 //   . "     OR `jeu_media_imprimer` IS NULL\n"
    . "     OR `jeu_media_officiel` IS NULL\n"
    . "ORDER BY `jeu`.`jeu_nom` ASC\n"
    . "LIMIT 0,1;";

//echo "<p>".$sql_liste_jeu."</p>";
$res_liste_jeu = mysqli_query ($ezine_db, $sql_liste_jeu) or ezine_mysql_die($ezine_db, $sql_liste_jeu) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_jeu=mysqli_num_rows($res_liste_jeu);
$bdd_liste_jeu = mysqli_fetch_object($res_liste_jeu);

$titre_jeu=$bdd_liste_jeu->jeu_nom;

$btn_fichier=null;
$btn_fichier.='<a role="button" class="btn btn-outline-secondary" href="'.$bdd_liste_jeu->jeu_media_dossier.$bdd_liste_jeu->jeu_media_fichier.'" target="_blank">';
$btn_fichier.=media_type_MIME($bdd_liste_jeu->jeu_media_MIME);
$btn_fichier.='</a>';



$val_bdd_actu=null;
if(isset($_POST["media_langue"])){
    foreach ($_POST["media_langue"] as $lang_actu){
        if(!is_null($val_bdd_actu)){
            $val_bdd_actu.="/";
        }
        $val_bdd_actu.=$lang_actu;
    }
    if(!is_null($val_bdd_actu)){
        $sql_update = "UPDATE `jeu_media`\n"
            . "SET `language` = '".$val_bdd_actu."'\n"
            . "WHERE `jeu_media`.`jeu_media_id` = ".$bdd_liste_jeu->jeu_media_id."";
        //echo "<p>".$sql_update."</p>";
        mysqli_query ($ezine_db, $sql_update) or ezine_mysql_die($ezine_db, $sql_update) ;
        $bdd_liste_jeu->language=$val_bdd_actu;
    }
}
$select_langue = null;
$sql_langue = "SELECT `language_id`,`language_nom_fr`\n"
    . "FROM `language`\n"
    . "ORDER BY `language`.`language_nom_fr` ASC;";
//echo "<p>".$sql_langue."</p>";
$res_langue = mysqli_query ($ezine_db, $sql_langue) or ezine_mysql_die($ezine_db, $sql_langue) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_langue=mysqli_num_rows($res_langue);
$array_langue_bdd=explode("/",$bdd_liste_jeu->language);
while($bdd_langue = mysqli_fetch_object($res_langue)){
    $select_langue.='<option ';
    if(in_array($bdd_langue->language_id,$array_langue_bdd)){
        $select_langue.='selected ';
    }
    $select_langue.='value="'.$bdd_langue->language_id.'">'.$bdd_langue->language_nom_fr.'</option>';
}




$array_checked=array(
        array("nom" => "est officiel",          "champ_bdd" => "jeu_media_officiel",    ),
        array("nom" => "est à faire imprimer",  "champ_bdd" => "jeu_media_a_imprimer",  ),
);

$btn_swich=null;
foreach ($array_checked as $key=>$actu){
    $name_sluggy=slugify($actu["nom"]);

    if(isset($_POST[$name_sluggy]) && $_POST[$name_sluggy]=="on"){
        $sql_update = "UPDATE `jeu_media`\n"
            . "SET `".$actu["champ_bdd"]."` = '1'\n"
            . "WHERE `jeu_media`.`jeu_media_id` = ".$bdd_liste_jeu->jeu_media_id."";
        //echo "<p>".$sql_update."</p>";
    }else{
        $sql_update = "UPDATE `jeu_media`\n"
            . "SET `".$actu["champ_bdd"]."` = '0'\n"
            . "WHERE `jeu_media`.`jeu_media_id` = ".$bdd_liste_jeu->jeu_media_id."";
        //echo "<p>".$sql_update."</p>";
    }
    mysqli_query ($ezine_db, $sql_update) or ezine_mysql_die($ezine_db, $sql_update) ;

    //var_dump($bdd_liste_jeu->{"".$actu["champ_bdd"]});
    $checked=null;
    if(!is_null($bdd_liste_jeu->{$actu["champ_bdd"]})){
        if($bdd_liste_jeu->{$actu["champ_bdd"]}==1){
            $checked.= ' checked';
        }
    }

    $btn_swich.='<div class="form-check form-switch">';
    $btn_swich.='<input class="form-check-input" name="'.$name_sluggy.'" type="checkbox" role="switch" id="'.$name_sluggy.'" '.$checked.'>';
    $btn_swich.='<label class="form-check-label" for="'.$name_sluggy.'">'.$actu["nom"].'?</label>';
    $btn_swich.='</div>';
    $btn_swich.='';
    $btn_swich.='';

}

//var_dump($array_checked);




/*$deepLy = new ChrisKonnertz\DeepLy\DeepLy();

$translatedText = $deepLy->translate('Hello world!', 'DE', 'EN');

echo $translatedText; // Prints "Hallo Welt!"*/

?>

<main>
    <div class="position-relative overflow-hidden bg-light">
        <form role="form" action="<?php FILE ?>" method="post" enctype="multipart/form-data" name="form_recherche">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title"><?php echo $titre_jeu; ?></h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-1 text-center">
                            <h5><?php echo $bdd_liste_jeu->jeu_media_type_nom_fr; ?></h5>
                            <?php echo $btn_fichier; ?>
                        </div>
                        <div class="col">
                            <select name="media_langue[]" class="form-select" size="5"  multiple>
                                <?php echo $select_langue; ?>
                            </select>
                        </div>
                        <div class="col">
                            <?php echo $btn_swich; ?>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">valider</button>
                </div>
            </div>
        </form>
    </div>



</main>


<?php
//include_once dirname(__FILE__) . '/../graph/graph_global_langue.php';
//include_once dirname(__FILE__) . '/../map/data/LMB_map1_0.php';






include_once dirname(__FILE__) . '/../structure/footer.php';
?>
