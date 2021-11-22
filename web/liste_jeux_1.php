<?php
include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];
include_once dirname(__FILE__) . '/../structure/sql.php';
?>




<?php
$array_liste_collonne=array(
    array("nom","ASC","jeu_nom"),
    array("année prod.","ASC","jeu_bgg_yearpublished"),
    array("difficulté","DESC","jeu_bgg_averageweight"),
    array("note","DESC","jeu_bgg_note_bayesienne"),
);

function nettoyerChaine($string) {
    $string = str_replace(' ', '_', $string);
    $string = preg_replace('/[^A-Za-z0-9-_]/', '', $string);
    return preg_replace('/-+/', '-', $string);
}

function transformerEnURL($string) {
    $dict = array("I'm" => "I am");
    return strtolower(preg_replace(array( '#[s-]+#', '#[^A-Za-z0-9. -_]+#' ), array( '-', '' ), nettoyerChaine(str_replace(array_keys($dict), array_values($dict), urldecode($string)))));
}

function bouton_classement($txt,$sens="ASC"){
    $sortie="";
    $txt_format = transformerEnURL($txt);
    //echo $txt_format;
    //echo nettoyerChaine($txt);
    $sortie.='<button class="btn ';
    if(isset($_POST[$txt_format])){
        $sortie.='btn-secondary';
    }else{
        //$sortie.='btn-outline-secondary';
        $sortie.='btn-light';
    }
    $sortie.='" type="submit" name="';
    $sortie.=$txt_format;
    $sortie.='" value="';
    if(isset($_POST[$txt_format])){
        //echo $txt_format." = ".$_POST[$txt_format];
        if($_POST[$txt_format]==0){
            if($sens=="DESC"){
                $sortie.=2;
            }else{
                $sortie.=1;
            }
        }elseif($_POST[$txt_format]==1){
            if($sens=="DESC"){
                $sortie.=0;
            }else{
                $sortie.=2;
            }
        }elseif($_POST[$txt_format]==2){
            if($sens=="DESC"){
                $sortie.=1;
            }else{
                $sortie.=0;
            }
        }
    }else{
        if($sens=="DESC"){
            $sortie.=2;
        }else{
            $sortie.=1;
        }
    }
    $sortie.='">';
    $sortie.=ucfirst($txt);
    if(isset($_POST[$txt_format])){
        if($_POST[$txt_format]==0){

        }elseif($_POST[$txt_format]==1){
            $sortie.=' <i class="fas fa-sort-alpha-down"></i>';
        }elseif($_POST[$txt_format]==2){
            $sortie.=' <i class="fas fa-sort-alpha-up"></i>';
        }
    }else{

    }

    $sortie.='</button>';
    //$sortie.='<input type="hidden" name="sens" value="'.$sens.'">';
    $sortie.='</th>';
    $sortie.='';

    return $sortie;
}
function bouton_classement_POST($txt,$champ_bdd){
    $txt_format = transformerEnURL($txt);
    $retour=null;
    if(isset ($_POST[$txt_format])){
        //var_dump("hello");
        if($_POST[$txt_format]==1){
            $retour="`".$champ_bdd."` ASC, ";
        }elseif($_POST[$txt_format]==2){
            $retour="`".$champ_bdd."` DESC, ";
        }
    }
    return $retour;
}

//var_dump($array_liste_collonne);
//var_dump($array_liste_collonne);

//var_dump($_POST);

/*$sql = "SELECT MAX(`jeu_bgg_maxplayers`) AS `max`\n"
    . "FROM `jeu`";
*/
/*$sql = "SELECT `jeu_nom`, `jeu_bgg_minplayers`,`jeu_bgg_maxplayers`\n"
    . "FROM `jeu` \n"
    . "WHERE 10 BETWEEN `jeu_bgg_minplayers` and `jeu_bgg_maxplayers`";
*/
$sql_liste_jeu_filtre=null;
if(isset($_POST["nbjoueurs"])){
    if($_POST["nbjoueurs"]>=1){
        $sql_liste_jeu_filtre="AND ".$_POST["nbjoueurs"]." BETWEEN `jeu_bgg_minplayers` and `jeu_bgg_maxplayers`";
    }
}


$sql_liste_jeu_order_by=null;
foreach ($array_liste_collonne as $col_actu){
    $qqch= bouton_classement_POST($col_actu[0],$col_actu[2]);
    if(!is_null($qqch)){
        $sql_liste_jeu_order_by=$qqch;
    }
}

/*}else{
    var_dump("existe pas");
}*/

//var_dump($bgg_jeux);

//$test=array(1,2,3,4);
//var_dump($test);
//var_dump(simplexml_load_string('https://www.boardgamegeek.com/xmlapi2/collection?username=titich'));

/*$sql="SELECT * FROM jeu";
if ($result = mysqli_query($ezine_db,$sql )) {
    //echo "Returned rows are: " . mysqli_num_rows($result);
    while ($obj = mysqli_fetch_object($result)) {
        //printf("%s (%s)\n", $obj->Lastname, $obj->Age);
        echo "<br>".$obj->jeu_nom;
    }
    // Free result set
    mysqli_free_result($result);
}*/


/*
https://www.boardgamegeek.com/xmlapi2/
https://www.rpggeek.com/xmlapi2/
https://www.videogamegeek.com/xmlapi2/
 */

$ligne_tableau="";
$i=1;
$sql_liste_jeu="SELECT * \n"
    . "FROM `jeu`\n"
    . "WHERE `jeu_bgg_subtype` = 'boardgame'\n"
    . $sql_liste_jeu_filtre."\n"
    . "ORDER BY ".$sql_liste_jeu_order_by." `jeu_bgg_averageweight` DESC,`jeu_bgg_yearpublished` DESC";
//echo "<p>".$sql_liste_jeu."</p>";
$res_liste_jeu = mysqli_query ($ezine_db, $sql_liste_jeu) or ezine_mysql_die($ezine_db, $sql_liste_jeu) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_jeu=mysqli_num_rows($res_liste_jeu);
while($bdd_liste_jeu = mysqli_fetch_object($res_liste_jeu)){
    //var_dump($bdd_liste_jeu->jeu_nom);
    $ligne_tableau.="<tr>";
    $ligne_tableau.='<th scope="row">'.$i.'</th>';
    foreach ($array_liste_collonne as $col_actu){
        $ligne_tableau.='<td>'.$bdd_liste_jeu->{$col_actu[2]}.'</td>';
    }
    //$ligne_tableau.='<td><a href="https://www.boardgamegeek.com/xmlapi2/thing?id='.$bdd_liste_jeu->jeu_bgg_id.'&stats=1" >'.$bdd_liste_jeu->jeu_bgg_id.'</a> </td>';
    $ligne_tableau.='<td>
        <a target="_blank" type="button" class="btn btn-bgg btn-sm" href="https://www.boardgamegeek.com/boardgame/'.$bdd_liste_jeu->jeu_bgg_id.'/" >
            <img src="../vendor/BGG/navbar-logo-bgg-b2.svg" alt="lien BGG" height="15px"  />
        </a>
    </td>';
    $ligne_tableau.="</tr>";


    $i++;
}




?>

<!--<a href="https://www.boardgamegeek.com/xmlapi2/collection?username=titich" >lien</a>--!>
<main>
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <form role="form" action="<?php echo FILE; ?>" method="post" enctype="multipart/form-data" name="form_1">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nbjoueurs" class="form-label">Nbre de joueurs:</label>
                            <select id="nbjoueurs" name="nbjoueurs" class="form-select">
                                <?php
                                echo '<option value="0"';
                                if(isset($_POST["nbjoueurs"]) and $_POST["nbjoueurs"]==$i){
                                    echo "selected";
                                }
                                echo '>tout</option>';
                                for ($i = 1; $i < 10; $i++) {
                                    echo '<option value="'.$i.'"';
                                    if(isset($_POST["nbjoueurs"]) and $_POST["nbjoueurs"]==$i){
                                        echo "selected";
                                    }
                                    echo '>'.$i.'</option>';
                                }
                                echo '<option value="'.$i.'"';
                                if(isset($_POST["nbjoueurs"]) and $_POST["nbjoueurs"]==$i){
                                    echo "selected";
                                }
                                echo '>'.$i.' +</option>';
                                ?>

                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <?php
                            foreach ($array_liste_collonne as $col_actu){
                                echo '<th scope="col">'.bouton_classement($col_actu[0],$col_actu[1]).'</th>';
                            }
                        ?>
                        <th scope="col">Lien BGG</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        echo $ligne_tableau;
                    ?>
                    </tbody>
                </table>
            </div>
        </form>
        <div class="col-md-5 p-lg-5 mx-auto my-5">
            <h1 class="display-4 fw-normal">Punny headline</h1>
            <p class="lead fw-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple’s marketing pages.</p>
            <a class="btn btn-outline-secondary" href="#">Coming soon</a>
        </div>

    </div>
</main>


<?php
include_once dirname(__FILE__) . '/../structure/footer.php';
?>
