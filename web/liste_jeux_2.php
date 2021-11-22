<?php
include_once dirname(__FILE__) . '/../structure/header.php';
//echo $_SERVER['SERVER_NAME'];
include_once dirname(__FILE__) . '/../structure/sql.php';

$type_operation=1;// liste de jeux standard
if(isset($_GET["p"])){
    if($_GET["p"]=="poids"){
        $type_operation=2;// completer poids et taille des jeux
    }
}

//var_dump(URL_PAR);


if($type_operation==2){
    $array_liste_collonne=array(
        array("nom","ASC","jeu_nom",null),
        array("x","ASC","jeu_x","jeu_bgg_width"),
        array("y","ASC","jeu_y","jeu_bgg_depth"),
        array("z","ASC","jeu_z","jeu_bgg_length"),
        array("poids","ASC","jeu_poids","jeu_bgg_weight"),
    );
}else{// valable pour le operation 1
    $array_liste_collonne=array(
        array("nom","ASC","jeu_nom"),
        array("année prod.","ASC","jeu_bgg_yearpublished"),
        array("difficulté","DESC","jeu_bgg_averageweight"),
        array("note","DESC","jeu_bgg_note_bayesienne"),
    );

}

foreach ($_POST as $key=>$post_actu){
    if($post_actu==""){
        unset($_POST[$key]);
    }else{
        if(substr($key, 0, 4) == "data"){
            $quoi_update_array=explode("@",$key);
            var_dump(explode("@",$key));
            $array_inter=array_column($array_liste_collonne, 0);
            foreach ($array_inter as $key=>$value){
                $array_inter[$key]=transformerEnURL($value);
            }
            $champ= $array_liste_collonne[array_search($quoi_update_array[2], $array_inter)][2];

            $sql_update_data = "UPDATE `jeux`.`jeu` \n"
                . "SET `".$champ."` = '".$post_actu."' \n"
                . "WHERE `jeu`.`jeu_id` = ".$quoi_update_array[1]."";
            mysqli_query ($ezine_db, $sql_update_data) or ezine_mysql_die($ezine_db, $sql_update_data) ;
            //$champ= array_search("poids", array_column($array_liste_collonne, 0));
            var_dump($champ);
            echo "<br>".$sql_update_data;
        }
    }
}



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
    /*if(isset($_POST[$txt_format])) {
        $sortie .= '<input id="' . $txt_format . '" name="' . $txt_format . '" type="hidden" value="' . $_POST[$txt_format] . '">';
    }*/

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
function bouton_valider_classement($txt){
    $txt_format = transformerEnURL($txt);
    $retour=null;
    if(isset ($_POST[$txt_format]) ) {
        //var_dump($txt_format."JJJ");
        $retour = '<button type="submit" name="' . $txt_format . '" value="' . $_POST[$txt_format] . '" class="btn btn-primary">Submit</button>';
    }
    return $retour;
}

//var_dump($array_liste_collonne);
//var_dump($array_liste_collonne);

var_dump($_POST);



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
    if($type_operation==2){
        foreach ($array_liste_collonne as $col_actu){
            if(isset($col_actu[3])){
                $calcul=null;
                $calcul_txt=null;
                if($col_actu[0]=="poids"){
                    $calcul=$bdd_liste_jeu->{$col_actu[3]}*0.45359237;
                }else{
                    $calcul=$bdd_liste_jeu->{$col_actu[3]}*(304/12);
                }
                if(is_null($bdd_liste_jeu->{$col_actu[2]}) or $bdd_liste_jeu->{$col_actu[2]}==0){
                    if(!(is_null($calcul) or $calcul==0)){
                        $calcul_txt='placeholder="'.number_format($calcul, 1, '.', ' ').'"';
                    }
                    $ligne_tableau.='<td><input type="text" name ="data@'.$bdd_liste_jeu->jeu_id.'@'.transformerEnURL($col_actu[0]).'" class="form-control" '.$calcul_txt.'></td>';
                }else{
                    if(!(is_null($calcul) or $calcul==0)){
                        $calcul_txt='<small style="color: grey">('.number_format($calcul, 1, '.', ' ').')</small>';
                    }
                    $ligne_tableau.='<td>'.$bdd_liste_jeu->{$col_actu[2]}.''.$calcul_txt.'</td>';
                }
            }else{
                $ligne_tableau.='<td>'.$bdd_liste_jeu->{$col_actu[2]}.'</td>';
            }
            //$ligne_tableau.='<td>'.$bdd_liste_jeu->{$col_actu[2]}.'</td>';

        }
    }else{
        foreach ($array_liste_collonne as $col_actu){
            $ligne_tableau.='<td>'.$bdd_liste_jeu->{$col_actu[2]}.'</td>';
        }
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
        <form role="form" action="<?php echo FILE."?".URL_PAR; ?>" method="post" enctype="multipart/form-data" name="form_1">
            <?php
                /*$hidden=null;
                foreach ($array_liste_collonne as $col_actu){
                    $hidden.=bouton_classement_hidden($col_actu[0]);
                }

                echo $hidden;
                var_dump($hidden);*/
            ?>

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
                        <?php
                        $btn_valider=null;
                        foreach ($array_liste_collonne as $col_actu){
                            //if(isset($_POST[""]))
                            $btn_valider.=bouton_valider_classement($col_actu[0]);
                        }
                        echo $btn_valider;
                        ?>

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
