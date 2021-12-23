<?php

/**
 * Versions
 * **********************************************************************************************************************
 * 14 (27.11.2021)
 * ajout de la 1ere version de la gestion des prets.
 * **********************************************************************************************************************
 **/
$ezine_db=null;

include_once dirname(__FILE__) . '/../structure/sql.php';
include_once dirname(__FILE__) . '/../structure/header.php';

//echo $_SERVER['SERVER_NAME'];
include_once dirname(__FILE__) . '/../function/age.php';
include_once dirname(__FILE__) . '/../function/bouton_classement.php';
include_once dirname(__FILE__) . '/../function/slugify.php';
include_once dirname(__FILE__) . '/../function/btn_click.php';


//$_POST["r_editeur"]='22';

if(isset($_POST["id_jeux_pret"]) && isset($_POST["pret_qui"])){
    //var_dump("prete ".$_POST["id_jeux_pret"]." à ".$_POST["pret_qui"]."");
    $sql_up_pret = "UPDATE\n"
        . "    `jeu`\n"
        . "SET\n"
        . "    `jeu_est_pret` = '1',\n"
        . "    `jeu_est_pret_qui` = '".$_POST["pret_qui"]."'\n"
        . "WHERE\n"
        . "    `jeu_id` = '".$_POST["id_jeux_pret"]."';";
    //echo "<p>".$sql_up_pret."</p>";
    mysqli_query($ezine_db, $sql_up_pret) or ezine_mysql_die($ezine_db, $sql_up_pret);

    $sql_up_pret2 = "INSERT INTO `jeu_stats`(\n"
        . "    `jeu_stats_id`,\n"
        . "    `jeu_stats_opération`,\n"
        . "    `jeu_stats_date`,\n"
        . "    `qui`,\n"
        . "    `jeu_stats_quoi`,\n"
        . "    `jeu_stats_objet`\n"
        . ")\n"
        . "VALUES(NULL, '3', NOW(), '', '', '".$_POST["id_jeux_pret"]."');";
    //echo "<p>".$sql_up_pret2."</p>";
    mysqli_query($ezine_db, $sql_up_pret2) or ezine_mysql_die($ezine_db, $sql_up_pret2);

}
if(isset($_POST["retour"])){
    $sql_up_retour = "UPDATE\n"
        . "    `jeu`\n"
        . "SET\n"
        . "    `jeu_est_pret` = '0',\n"
        . "    `jeu_est_pret_qui` = NULL\n"
        . "WHERE\n"
        . "    `jeu_id` = '".$_POST["retour"]."';";
    //echo "<p>".$sql_up_retour."</p>";
    mysqli_query($ezine_db, $sql_up_retour) or ezine_mysql_die($ezine_db, $sql_up_retour);

    $sql_up_retour2 = "INSERT INTO `jeu_stats`(\n"
        . "    `jeu_stats_id`,\n"
        . "    `jeu_stats_opération`,\n"
        . "    `jeu_stats_date`,\n"
        . "    `qui`,\n"
        . "    `jeu_stats_quoi`,\n"
        . "    `jeu_stats_objet`\n"
        . ")\n"
        . "VALUES(NULL, '4', NOW(), '', '', '".$_POST["retour"]."');";
    //echo "<p>".$sql_up_retour2."</p>";
    mysqli_query($ezine_db, $sql_up_retour2) or ezine_mysql_die($ezine_db, $sql_up_retour2);
}
$sql_liste_personnes_pret = "SELECT\n"
    . "    `personne_pret_id`,\n"
    . "    `personne_pret_nom`,\n"
    . "    `personne_pret_prenom`\n"
    . "FROM\n"
    . "    `personne_pret`;";
//echo "<p>".$sql_liste_personnes_pret."</p>";
$res_liste_personnes_pret = mysqli_query($ezine_db, $sql_liste_personnes_pret) or ezine_mysql_die($ezine_db, $sql_liste_personnes_pret);
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_personnes_pret = mysqli_num_rows($res_liste_personnes_pret);
$liste_personnes_pret = null;
while ($bdd_liste_personnes_pret = mysqli_fetch_object($res_liste_personnes_pret)) {
    $liste_personnes_pret .= '<option value="' . $bdd_liste_personnes_pret->personne_pret_id . '">' . $bdd_liste_personnes_pret->personne_pret_nom . ' ' . $bdd_liste_personnes_pret->personne_pret_prenom . '</option>';
}


$etat_jeux=array(
    array("nom"=>"own","bdd"=>"jeu_bgg_own","bdd_vue"=>"nb_own"),
    array("nom"=>"préorded","bdd"=>"jeu_bgg_preordered","bdd_vue"=>"nb_preordered"),
    array("nom"=>"veut vendre","bdd"=>"jeu_bgg_prevowned","bdd_vue"=>"nb_prevowned"),
    array("nom"=>"a_vendre","bdd"=>"jeu_bgg_fortrade","bdd_vue"=>"nb_fortrade"),
    array("nom"=>"veux","bdd"=>"jeu_bgg_want","bdd_vue"=>"nb_want"),
    array("nom"=>"veux jouer","bdd"=>"jeu_bgg_wanttoplay","bdd_vue"=>"nb_wanttoplay"),
    array("nom"=>"veut acheter","bdd"=>"jeu_bgg_wanttobuy","bdd_vue"=>"nb_wanttobuy"),
    array("nom"=>"wishlist","bdd"=>"jeu_bgg_wishlist","bdd_vue"=>"nb_wishlist"),
    array("nom"=>"prété","bdd"=>"jeu_est_pret","bdd_vue"=>"jeu_est_pret"),
);
$sql_filtre_own=null;
foreach ($etat_jeux as $key=>$etat_jeu_actu){
    $sql_nb_cat = "SELECT `".$etat_jeu_actu["bdd_vue"]."` AS `nb`  \n"
        . "FROM `v_nb_cat`;";
    //echo "<p>".$sql_nb_cat."</p>";
    $res_nb_cat = mysqli_query ($ezine_db, $sql_nb_cat) or ezine_mysql_die($ezine_db, $sql_nb_cat) ;
    //$num_ticket=mysqli_insert_id($ezine_db);
    $nbre_nb_cat=mysqli_num_rows($res_nb_cat);
    $bdd_nb_cat = mysqli_fetch_object($res_nb_cat);
    if($bdd_nb_cat->nb==0){
        unset($etat_jeux[$key]);
    }else{
        $etat_jeux[$key]["nbre"]=$bdd_nb_cat->nb;
        if(isset($_POST["btn-".$etat_jeu_actu["bdd"]]) && $_POST["btn-".$etat_jeu_actu["bdd"]]=="on"){
            //var_dump($etat_jeu_actu);
            if(is_null($sql_filtre_own)){
                $sql_filtre_own.= " AND (`".$etat_jeu_actu["bdd"]."` = 1 ";
            }else{
                $sql_filtre_own.= " OR `".$etat_jeu_actu["bdd"]."` = 1 ";
            }
        }
    }
}
if(!is_null($sql_filtre_own)){
    $sql_filtre_own.= " )";
}






$array_joueurs_nommes=array(
    array("qui"=>"Alexine","Naissance"=>"2012-09-26"),
    array("qui"=>"Eliam","Naissance"=>"2015-02-16"),
    array("qui"=>"Lara","Naissance"=>"2016-07-12"),
);
foreach ($array_joueurs_nommes as $key=>$j_actu){
    $array_joueurs_nommes[$key]["age"]=age($j_actu["Naissance"]);
}
//var_dump($array_joueurs_nommes);



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
        array("populatité","DESC","jeu_coolitude"),
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
            foreach ($array_inter as $key2=>$value){
                $array_inter[$key2]=slugify($value);
            }
            $champ= $array_liste_collonne[array_search($quoi_update_array[2], $array_inter)][2];

            $sql_update_data = "UPDATE `jeux`.`jeu` \n"
                . "SET `".$champ."` = '".$post_actu."' \n"
                . "WHERE `jeu`.`jeu_id` = ".$quoi_update_array[1]."";
            mysqli_query ($ezine_db, $sql_update_data) or ezine_mysql_die($ezine_db, $sql_update_data) ;
            //$champ= array_search("poids", array_column($array_liste_collonne, 0));
            //var_dump($champ);
            //echo "<br>".$sql_update_data;
        }
    }
}
if(isset($_POST["checkbox_boite"])){
    foreach ($_POST["checkbox_boite"] as $ligne_actu){
        $ligne_actu_array=explode("@",$ligne_actu);
        //echo "<br>".$ligne_actu;
        if(isset($_POST["boite_".$ligne_actu_array[0]])){
            if($ligne_actu_array[1]!="on"){
                //echo "<br>".$ligne_actu." off -> on";
                $sql_up = "UPDATE `jeu` \n"
                    . "SET `jeu_est_boite` = '1' \n"
                    . "WHERE `jeu`.`jeu_id` = ".$ligne_actu_array[0].";";
                mysqli_query ($ezine_db, $sql_up) or ezine_mysql_die($ezine_db, $sql_up) ;
            }
        }else{
            if($ligne_actu_array[1]!="off"){
                //echo "<br>".$ligne_actu." on -> off";
                $sql_up2 = "UPDATE `jeu` \n"
                    . "SET `jeu_est_boite` = '0' \n"
                    . "WHERE `jeu`.`jeu_id` = ".$ligne_actu_array[0].";";
                mysqli_query ($ezine_db, $sql_up2) or ezine_mysql_die($ezine_db, $sql_up2) ;
            }
        }
    }
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
if(isset($_POST["nbjoueurs_type"])){
    $nb_joueurs=0;
    if(isset($_POST["nbjoueurs"])){
        $nb_joueurs=$_POST["nbjoueurs"];
    }
    if($_POST["nbjoueurs_type"]=="boite"){
        if($nb_joueurs>=1){
            $sql_liste_jeu_filtre.="AND ".$nb_joueurs." BETWEEN `jeu_bgg_minplayers` and `jeu_bgg_maxplayers`";
        }
    }elseif($_POST["nbjoueurs_type"]=="recommande"){
        if($nb_joueurs>=1){
            $sql_liste_jeu_filtre.="AND `jeu_bgg_nb_player_recommended` LIKE '%|".$nb_joueurs."|%'";
        }
    }elseif($_POST["nbjoueurs_type"]=="best"){
        if($nb_joueurs>=1){
            $sql_liste_jeu_filtre.="AND `jeu_bgg_nb_player_best` LIKE '%|".$nb_joueurs."|%'";
        }
    }
}
if(isset($_POST["age_min_joueur_type"])){
    //var_dump($_POST["age_min_joueur_type"]);
    $age_min_joueur=0;
    if(isset($_POST["age_min_joueur_val"]) && $_POST["age_min_joueur_val"]!= "abc"){
        $age_min_joueur=$_POST["age_min_joueur_val"];
        //var_dump($age_min_joueur);
    }
    if($_POST["age_min_joueur_type"]=="boite"){
        if($age_min_joueur>=1){
            $sql_liste_jeu_filtre.="AND `jeu_bgg_minage` <= '".$age_min_joueur."' ";
        }
    }elseif($_POST["age_min_joueur_type"]=="observe"){
        if($age_min_joueur>=1){
            $sql_liste_jeu_filtre.="AND `jeu_bgg_suggested_playerage` <= '".$age_min_joueur."' ";
        }
    }
}
//var_dump($sql_liste_jeu_filtre);

if($type_operation==1){
    $sql_liste_jeu_filtre.="AND `jeu_bgg_subtype` = 'boardgame'";
}
if($type_operation==2){
    $sql_liste_jeu_filtre.="AND `jeu_est_boite` = 1 AND `jeu_poids` IS NULL AND `jeu_bgg_own` = 1 ";
}


$sql_liste_jeu_order_by=null;
foreach ($array_liste_collonne as $col_actu){
    $qqch= bouton_classement_POST($col_actu[0],$col_actu[2]);
    if(!is_null($qqch)){
        $sql_liste_jeu_order_by=$qqch;
    }
}

if(isset($_POST["r_editeur"])){
    $sql_max_editeur = "SELECT\n"
        . "    MAX(`boardgamepublisher_id`) AS `max_boardgamepublisher`\n"
        . "FROM\n"
        . "    `boardgamepublisher`;";
    //echo "<p>".$sql_max_editeur."</p>";
    $res_max_editeur = mysqli_query ($ezine_db, $sql_max_editeur) or ezine_mysql_die($ezine_db, $sql_max_editeur) ;
    //$num_ticket=mysqli_insert_id($ezine_db);
    $nbre_max_editeur=mysqli_num_rows($res_max_editeur);
    $bdd_max_editeur = mysqli_fetch_object($res_max_editeur);

    if(is_numeric($_POST["r_editeur"]) && $_POST["r_editeur"]<= $bdd_max_editeur->max_boardgamepublisher){
        $sql_liste_jeu_filtre.="AND `boardgamepublisher_id`= ".$_POST["r_editeur"]."\n";
    }



}



//var_dump($_POST);
/*}else{
    var_dump("existe pas");
}*/

//var_dump($bgg_jeux);

//$test=array(1,2,3,4);
//var_dump($test);
//var_dump(simplexml_load_string("https://www.boardgamegeek.com/xmlapi2/collection?username=titich'));

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

// . "    AND `boardgamepublisher`.`boardgamepublisher_nom_en`= \'iello\'\n"

$ligne_tableau="";
$i=1;
$sql_liste_jeu="SELECT * \n"
    . "FROM `jeu`\n"
    . "INNER JOIN `boardgamepublisher_jeu` ON `boardgamepublisher_jeu`.`jeu` = `jeu`.`jeu_id`\n"
    . "INNER JOIN `boardgamepublisher` ON `boardgamepublisher`.`boardgamepublisher_id` = `boardgamepublisher_jeu`.`boardgamepublisher`\n"
    . "WHERE 1\n"
    . $sql_liste_jeu_filtre." ".$sql_filtre_own."\n"
    . "ORDER BY ".$sql_liste_jeu_order_by." `jeu_bgg_averageweight` DESC,`jeu_bgg_yearpublished` DESC";
//echo "<p>".$sql_liste_jeu."</p>";
$res_liste_jeu = mysqli_query ($ezine_db, $sql_liste_jeu) or ezine_mysql_die($ezine_db, $sql_liste_jeu) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_liste_jeu=mysqli_num_rows($res_liste_jeu);
while($bdd_liste_jeu = mysqli_fetch_object($res_liste_jeu)){
    //var_dump($bdd_liste_jeu->jeu_nom);
    $ligne_tableau.="<tr>";
    $ligne_tableau.='<th scope="row">'.$i.'</th>';
    //$ligne_tableau.='<td><a class="btn btn-primary btn-sm" href="../structure/jeu_detail.php?id='.$bdd_liste_jeu->jeu_id.'" role="button"><i class="fas fa-info"></i></a></td>';
    $ligne_tableau.='<td>';
    $ligne_tableau.='<div class="btn-group" role="group">';
    $ligne_tableau.='<a class="btn btn-primary btn-sm" href="../structure/jeu_detail.php?id='.$bdd_liste_jeu->jeu_id.'" role="button"><i class="fas fa-info"></i></a>';
    if($bdd_liste_jeu->jeu_est_pret ==1){
        $ligne_tableau.='<button type="submit" id="retour" name="retour" class="btn btn-primary btn-sm" value="'.$bdd_liste_jeu->jeu_id.'">';
        $ligne_tableau.='<span class="fa-stack">';
        $ligne_tableau.='<i class="fas fa-share fa-stack-1x"></i>';
        $ligne_tableau.='<i class="fas fa-ban fa-stack-2x" style="color:Tomato"></i>';
        $ligne_tableau.='</span>';
        $ligne_tableau.='';
        $ligne_tableau.='</button>';
    }else{
        $ligne_tableau.='<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#Modal_pret" data-id_jeu="'.$bdd_liste_jeu->jeu_id.'" data-titre_jeu="'.$bdd_liste_jeu->jeu_nom.'"><i class="fas fa-share"></i></button>';
    }
    $ligne_tableau.='</div>';
    $ligne_tableau.='</td>';

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
                    $ligne_tableau.='<td><input type="text" name ="data@'.$bdd_liste_jeu->jeu_id.'@'.slugify($col_actu[0]).'" class="form-control" '.$calcul_txt.'></td>';
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
        $bouton_cheked=null;
        $bouton_cheked_etat="off";
        if ($bdd_liste_jeu->jeu_est_boite == 1){
            $bouton_cheked="checked";
            $bouton_cheked_etat="on";
        }
        $ligne_tableau.='<td>            
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox"  value="on" id="'.$bdd_liste_jeu->jeu_id.'" name ="boite_'.$bdd_liste_jeu->jeu_id.'" '.$bouton_cheked.'>
                    <input id="checkbox_boite" name="checkbox_boite[]" type="hidden" value="'.$bdd_liste_jeu->jeu_id.'@'.$bouton_cheked_etat.'">
                </div>     
            </td>';
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








//var_dump($_POST);





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
                        <?php
                        if(isset($_POST["age_min_joueur_val"]) and is_numeric($_POST["age_min_joueur_val"])){
                            echo '<input type="hidden" name="age_min_joueur_val" id="age_min_joueur_val" value="'.$_POST["age_min_joueur_val"].'">';
                        }else{
                            echo '<input type="hidden" name="age_min_joueur_val" id="age_min_joueur_val" value="abc">';
                        }
                        ?>
                        <div class="row">
                            <div class="col mb-2">
                                <div class="btn-group" role="group">
                                    <?php

                                    foreach ($etat_jeux as $etat_jeu_actu){
                                        $etat=null;
                                        if($etat_jeu_actu["bdd"]=="jeu_bgg_own" && empty($_POST)){
                                            $etat="checked";
                                        }
                                        if(isset($_POST['btn-'.$etat_jeu_actu["bdd"]]) && $_POST['btn-'.$etat_jeu_actu["bdd"]]== "on"){
                                            $etat="checked";
                                        }
                                        echo '<input type="checkbox" class="btn-check" id="btn-'.$etat_jeu_actu["bdd"].'" name="btn-'.$etat_jeu_actu["bdd"].'" '.$etat.' autocomplete="off">';
                                        echo '<label class="btn btn-outline-primary" for="btn-'.$etat_jeu_actu["bdd"].'">'.$etat_jeu_actu["nom"].' <span class="badge rounded-pill bg-secondary">'.$etat_jeu_actu["nbre"].'</span></label>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <select id="nbjoueurs" name="nbjoueurs" class="form-select col-sm-3">
                                        <option value="" disabled selected hidden>Nbre de joueurs:</option>
                                        <?php
                                        echo '<option value="0"';
                                        if(isset($_POST["nbjoueurs"]) and $_POST["nbjoueurs"]==0){
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
                                    <?php

                                    echo btn_click(array("boite","recommandé","best"),"nbjoueurs");
                                    ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="age_min_joueur" name="age_min_joueur" aria-expanded="false" style="text-align: left;">
                                        <?php
                                        if(isset($_POST["age_min_joueur_val"]) and is_numeric($_POST["age_min_joueur_val"])){
                                            if($_POST["age_min_joueur_val"]==0){
                                                echo 'tout';
                                            }else{
                                                echo $_POST["age_min_joueur_val"];
                                            }
                                        }else{
                                            echo "Age min joueurs:";
                                        }
                                        ?>

                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php
                                        $sql_age_max = "SELECT max(`age`) as `age_max`\n"
                                            . "FROM (\n"
                                            . " SELECT `jeu_bgg_minage` as `age`\n"
                                            . " FROM `jeu` \n"
                                            . " WHERE `jeu_bgg_minage` IS NOT NULL\n"
                                            . " UNION ALL\n"
                                            . " SELECT `jeu_bgg_suggested_playerage` as `age`\n"
                                            . " FROM `jeu` \n"
                                            . " WHERE `jeu_bgg_suggested_playerage` IS NOT NULL\n"
                                            . ") a\n"
                                            . "\n"
                                            . "ORDER BY `age`";
                                        //echo "<p>".$sql_age_max."</p>";
                                        $res_age_max = mysqli_query ($ezine_db, $sql_age_max) or ezine_mysql_die($ezine_db, $sql_age_max) ;
                                        //$num_ticket=mysqli_insert_id($ezine_db);
                                        $nbre_age_max=mysqli_num_rows($res_age_max);
                                        $bdd_age_max = mysqli_fetch_object($res_age_max);

                                        $age_max=$bdd_age_max->age_max;
                                        $i=1;
                                        $selected=null;
                                        if(isset($_POST["age_min_joueur_val"]) && $_POST["age_min_joueur_val"]==0){
                                            $selected = ' active';
                                        }
                                        echo '<li id="li_age_min_joueur_0" class="dropdown-item d-flex justify-content-between align-items-start'.$selected.'" onclick="dropdown_perso(\'0\' style="padding-top: 0px;padding-bottom: 0px;")"><div class="">tout</div></li>';
                                        for ($i = 1; $i <= $age_max; $i++) {
                                            echo '<li id="li_age_min_joueur_'.$i.'" class="dropdown-item d-flex justify-content-between align-items-start';
                                            if(isset($_POST["age_min_joueur_val"]) and $_POST["age_min_joueur_val"]==$i){
                                                echo " active";
                                            }
                                            echo '" onclick="dropdown_perso(\''.$i.'\')" style="padding-top: 0px;padding-bottom: 0px;"><div class="">';
                                            if($i==$age_max){
                                                echo $i." +";
                                            }else{
                                                echo $i;
                                            }
                                            echo '</div>';

                                            //var_dump(array_search($i,array_column($array_joueurs_nommes,"age")));
                                            //var_dump($array_joueurs_nommes[array_search($i,array_column($array_joueurs_nommes,"age"))]["qui"]);
                                            $est_age=array_search($i,array_column($array_joueurs_nommes,"age"));
                                            if($est_age!==false){
                                                echo '<small class=" text-muted">'.$array_joueurs_nommes[$est_age]["qui"].'</small>';
                                            }


                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                        echo btn_click(array("boite","observé"),"age_min_joueur");
                                        ?>
                                </div>
                            </div>

                        </div>

                        <?php
                        $btn_valider=null;
                        foreach ($array_liste_collonne as $col_actu){
                            //if(isset($_POST[""]))
                            $btn_valider.=bouton_valider_classement($col_actu[0]);
                        }
                        echo $btn_valider;
                        ?>
                        <input type="submit" value="Submit">
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"></th>
                        <?php
                        foreach ($array_liste_collonne as $col_actu){
                            echo '<th scope="col">'.bouton_classement($col_actu[0],$col_actu[1]).'</th>';
                        }
                        if($type_operation==2){
                            echo '<th scope="col">Est boite?</th>';
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
                <div class="modal fade" id="Modal_pret" tabindex="-1" aria-labelledby="Modal_pret_label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="Modal_pret_label">Prêt de jeux</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="id_jeux_pret" name="id_jeux_pret" value="XXX">
                                <select class="form-select" id="pret_qui" name="pret_qui">

                                    <option disabled selected hidden>Choisir une personne</option>
                                    <?php echo $liste_personnes_pret; ?>

                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script type="text/javascript">
    var Modal_pret = document.getElementById('Modal_pret')
    Modal_pret.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget
        // Extract info from data-bs-* attributes
        var id_jeu = button.getAttribute('data-id_jeu')
        var titre = button.getAttribute('data-titre_jeu')
        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        //alert(recipient);
        var modalTitle = Modal_pret.querySelector('.modal-title')
        //var modalBodyInput = Modal_pret.querySelector('.recipient-name')
        var modalHidden = document.getElementById("id_jeux_pret");

        modalTitle.textContent = 'Prêt de ' + titre;
        modalHidden.value = id_jeu;
    })
</script>
<?php


include_once dirname(__FILE__) . '/../js/dropdown_perso.php';

include_once dirname(__FILE__) . '/../structure/footer.php';
?>
