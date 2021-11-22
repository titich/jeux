<?php
include_once dirname(__FILE__) . '/../structure/sql.php';
include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];

require dirname(__FILE__) . '/../vendor/autoload.php';
use BabyMarkt\DeepL\DeepL;
$authKey = '1e26389b-5ef7-322d-ad5c-81ee07aede1c:fx';
$deepl   = new DeepL($authKey,2,'api-free.deepl.com');

//var_dump($_POST);

//var_dump($_POST["description_fr"]);
if(isset($_POST["description_fr"]) && isset($_POST["valider"])){
    $id_actu=$_POST["id_actu"];
    $txt_fr=nl2br(htmlentities($_POST["description_fr"], ENT_QUOTES , "UTF-8"));
    //$message = nl2br(stripslashes(strip_tags($_POST['message'])));
    //$txt_fr=htmlspecialchars($_POST["description_fr"]);
    //echo $txt_fr;
    //var_dump(htmlentities(addslashes("C'uzco est une nouvelle édition 2018 de Java de l'éditeur français Super Meeple qui déplace l'action en Amérique du Sud, ce qui est conforme aux autres titres de la trilogie Mask ainsi qu'aux plans originaux des auteurs pour le dessin."), ENT_QUOTES , "UTF-8"));
    $sql_update = "UPDATE `jeu`\n"
        . "SET\n"
        . '    `jeu_description_fr` = "'.$txt_fr.'",'
        . '    `jeu_description_fr_update_date`= NOW()'
        . "WHERE"
        . "    `jeu`.`jeu_id` = ".$id_actu;
    mysqli_query ($ezine_db, $sql_update) or ezine_mysql_die($ezine_db, $sql_update) ;
    //echo "<br>".$sql_update."<br>";

}

$sql_requete = "SELECT\n"
    . "    `jeu_id`,\n"
    . "    `jeu_nom`,\n"
    . "    `jeu_description_fr`,\n"
    . "    `jeu_description_fr_update_date`,\n"
    . "    `jeu_bgg_description`,\n"
    . "    `jeu_bgg_description_update_date`\n"
    . "FROM\n"
    . "    `jeu`\n"
    . "WHERE\n"
    . "    (`jeu_bgg_description_update_date` > `jeu_description_fr_update_date` \n"
    . "    OR `jeu_description_fr` IS NULL)\n"
    . "    AND `jeu_bgg_description` IS NOT NULL\n"
    . "ORDER BY `jeu_id` ASC\n"
    . "LIMIT 0, 1;";

//echo "<p>".$sql_requete."</p>";
$res_requete = mysqli_query ($ezine_db, $sql_requete) or ezine_mysql_die($ezine_db, $sql_requete) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_requete=mysqli_num_rows($res_requete);
if($nbre_requete!=0){
$bdd_requete = mysqli_fetch_object($res_requete);


//$txt_en=$bdd_requete->jeu_bgg_description;
//$txt_en = str_replace( array( "<br>', '<br />', "\n", "\r" ), array( '', '', '', ''), $bdd_requete->jeu_bgg_description );
$txt_en = preg_replace('/\s\s+/', ' ', trim($bdd_requete->jeu_bgg_description));
$txt_en = str_replace( '&#10;&#10;', '&#10;',  $txt_en );
$txt_en = str_replace( '&#10;&#10;', '&#10;',  $txt_en );
$txt_en = str_replace( '&#10;&#10;', '&#10;',  $txt_en );
$txt_en = str_replace( '&#10;&#10;', '&#10;',  $txt_en );
$txt_en = str_replace( '&#10; ', '&#10;',  $txt_en );


$txt_traduit="";
if(isset($_POST["trad"])){
    //$txt_traduit=str_replace("e" ,"€", $txt_en);

    //$txt_traduit_result = $deepl->translate($txt_en, 'en', 'fr');
    //$txt_traduit_result = $deepl->translate("have you", 'en', 'fr');
    $txt_traduit_result = $deepl->translate($txt_en, 'en', 'fr');
    //var_dump($txt_traduit_result);
    $txt_traduit=$txt_traduit_result[0]["text"];
    if($lastChar = substr($txt_traduit, -1)==";"){
        $txt_traduit=trim(substr($txt_traduit,0,-1));
    }
}
//var_dump($txt_traduit);

//$txt_en=str_replace(CHR(10),"",$txt_en);
// et celle là aussi :
//$txt_en=str_replace(CHR(13),"",$txt_en);

//var_dump($txt_en);
?>

<main>
    <div class="position-relative overflow-hidden bg-light">
        <div class="card">
            <div class="card-body">
                <form role="form" action="<?php echo FILE; ?>" method="post" enctype="multipart/form-data" name="form_1">
                    <fieldset disabled>
                        <div class="mb-3">
                            <label for="description_bgg" class="form-label">Description BGG</label>
                            <textarea class="form-control" id="description_bgg" name="description_bgg" style="height: 300px"><?php echo $txt_en; ?></textarea>
                        </div>
                    </fieldset>
                    <input type="hidden" value="<?php echo $bdd_requete->jeu_id; ?>" name="id_actu" id="id_actu">
                    <button type="submit" class="btn btn-primary" name="trad">Traduction</button>
                    <div class="mb-3">
                        <label for="description_fr" class="form-label">Description en français proposée</label>
                        <textarea class="form-control" placeholder="écrire la traduction ici ..." id="description_fr" name="description_fr" style="height: 300px"><?php echo $txt_traduit; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="valider">Submit</button>
                </form>
            </div>
        </div>

    </div>
</main>

<?php
}else{
?>
<main>
    <div class="position-relative overflow-hidden bg-light text-center">
        <div class="alert alert-success" role="alert" style="margin: 16px;">
            <h3>Pas de traductions à faire</h3>
        </div>
    </div>
</main>
<?php
}
?>

<?php
//include_once dirname(__FILE__) . '/../graph/graph_global_langue.php';
//include_once dirname(__FILE__) . '/../map/data/LMB_map1_0.php';








include_once dirname(__FILE__) . '/../structure/footer.php';
?>
