<?php
include_once dirname(__FILE__) . '/../structure/sql.php';

include_once dirname(__FILE__) . '/../structure/header.php';

include_once dirname(__FILE__) . '/../function/media_type_MIME.php';

//echo $_SERVER['SERVER_NAME'];

?>

<?php
$sql_liste_jeu = "SELECT\n"
    . "    `jeu_media_id`,\n"
    . "    `jeu_nom`,\n"
    . "    `jeu_media_type`,\n"
    . "    `jeu_media_MIME`,\n"
    . "    `jeu_media_dossier`,\n"
    . "    `jeu_media_fichier`,\n"
    . "    `language`,\n"
    . "    `jeu_media_a_imprimer`\n"
    . "FROM\n"
    . "    `jeu_media`\n"
    . "LEFT JOIN `jeu` ON `jeu`.`jeu_id` = `jeu_media`.`jeu`\n"
    . "WHERE\n"
    . "    `language` IS NULL\n"
    . "     OR `jeu_media_a_imprimer` IS NULL\n"
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


/*$deepLy = new ChrisKonnertz\DeepLy\DeepLy();

$translatedText = $deepLy->translate('Hello world!', 'DE', 'EN');

echo $translatedText; // Prints "Hallo Welt!"*/

?>

<main>
    <div class="position-relative overflow-hidden bg-light">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title"><?php echo $titre_jeu; ?></h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-1 text-center">
                        <?php echo $btn_fichier; ?>
                    </div>
                    <div class="col">
                        <select class="form-select" size="5"  multiple>
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Est officiel?</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Est à imprimer?</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



</main>


<?php
//include_once dirname(__FILE__) . '/../graph/graph_global_langue.php';
//include_once dirname(__FILE__) . '/../map/data/LMB_map1_0.php';






include_once dirname(__FILE__) . '/../structure/footer.php';
?>
