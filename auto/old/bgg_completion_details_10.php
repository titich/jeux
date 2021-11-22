<?php

include_once dirname(__FILE__) . '/../structure/sql.php';


//if (file_exists('https://www.boardgamegeek.com/xmlapi2/collection?username=titich')) {
//$xml = simplexml_load_string('https://www.boardgamegeek.com/xmlapi2/collection?username=titich');
//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1&stats=1');
//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1&id=432');
//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1');
//$array = (array)$xml;
//var_dump($array);

/*$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/thing?id=432');
$array = (array)$xml;
var_dump($array);*/

/*$ch = curl_init("https://api-free.deepl.com/v2/translate");

curl https://api-free.deepl.com/v2/translate \
	-d auth_key=1e26389b-5ef7-322d-ad5c-81ee07aede1c:fx \
	-d "text=Hello, world!"  \
	-d "target_lang=DE"
*/





function bdd_ajout($ezine_db,$table,$array_champ){
    //$val=$link_actu2["@attributes"]["value"];
    //echo"<br> Catégorie:".$link_actu2["@attributes"]["value"];
    $sql_controle_where=null;
    $sql_controle_select=null;
    $sql_ajout_values=null;

    foreach ($array_champ as $key=>$value){
        if(!is_null($sql_controle_select)){
            $sql_controle_select.=",";
            $sql_ajout_values.=",";
        }

        $sql_controle_where.=" AND `".$key."` LIKE '".addslashes($value)."'";
        $sql_controle_select.="`".$key."`";
        $sql_ajout_values.="'".addslashes($value)."'";
    }
    $sql_controle = "SELECT ".$sql_controle_select.",`".$table."_id`  \n"
        . "FROM `".$table."` \n"
        . "WHERE 1 \n"
        . $sql_controle_where;
    //echo "<p>".$sql_controle."</p>";
    $res_controle = mysqli_query ($ezine_db, $sql_controle) or ezine_mysql_die($ezine_db, $sql_controle) ;
    $nbre_controle=mysqli_num_rows($res_controle);
    //echo "<br> nbre: ".$nbre_controle;
    if($nbre_controle==0){
        $sql_ajout = "INSERT INTO `".$table."`(".$sql_controle_select.")\n"
            . "VALUES(".$sql_ajout_values.");";
        //echo "<p>".$sql_ajout."</p>";
        mysqli_query ($ezine_db, $sql_ajout) or ezine_mysql_die($ezine_db, $sql_ajout) ;
        $category_id = mysqli_insert_id($ezine_db);
        //echo "<br> id_insert: ".$category_id;
    }else{
        $category_id = mysqli_fetch_object($res_controle)->{$table."_id"};
    }
    return $category_id;
}

$sql_liste_jeux = "SELECT * \n"
    . "FROM `jeu`  \n"
    . "ORDER BY `jeu_last_update_script` ASC, `jeu_id` ASC\n"
    . "LIMIT 0,25;";
//    . "LIMIT 0,1;";
//echo $sql_liste_jeux;

if ($result_liste_jeux = mysqli_query($ezine_db,$sql_liste_jeux )) {
    //echo "<br>Returned rows are: " . mysqli_num_rows($result_liste_jeux);
    if(mysqli_num_rows($result_liste_jeux)!=0){
        while ($bdd_liste_jeux = mysqli_fetch_object($result_liste_jeux)) {
            if($array_detail_jeu_iter2=simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/thing?id='.$bdd_liste_jeux->jeu_bgg_id.'&stats=1')){
                //var_dump($array_detail_jeu_iter2);

                $array_detail_jeu_iter=(array)$array_detail_jeu_iter2;
                $array_detail_jeu=(array)$array_detail_jeu_iter["item"];

                $original_name=(array)$array_detail_jeu["name"][0];
                if($original_name["@attributes"]["type"]=="primary"){
                    $jeu_nom_original=$original_name["@attributes"]["value"];
                }
                $min_player=(array)$array_detail_jeu["minplayers"];
                $max_player=(array)$array_detail_jeu["maxplayers"];
                $jeu_stats_inter=(array)$array_detail_jeu["statistics"];
                $jeu_stats=(array)$jeu_stats_inter["ratings"];
                $jeu_note=(array)$jeu_stats["average"];
                $jeu_note_nb_vote=(array)$jeu_stats["usersrated"];
                $jeu_note_bayesienne=(array)$jeu_stats["bayesaverage"];
                $jeu_averageweight=(array)$jeu_stats["averageweight"];
                $jeu_averageweight_nb_vote=(array)$jeu_stats["numweights"];

                $playingtime=(array)$array_detail_jeu["playingtime"];
                $minplaytime=(array)$array_detail_jeu["minplaytime"];
                $maxplaytime=(array)$array_detail_jeu["maxplaytime"];
                $minage=(array)$array_detail_jeu["minage"];

                $description=(array)$array_detail_jeu["description"];

                //var_dump($description[0]);

                $poll=(array)$array_detail_jeu["poll"];
                if(isset($poll[0])){
                    $poll_data_0=(array)$poll[0];
                    $poll_stats_nb_joueurs=array();
                    if($poll_data_0["@attributes"]["name"]=="suggested_numplayers"){
                        $nbre_votes=$poll_data_0["@attributes"]["totalvotes"];
                        if($bdd_liste_jeux->jeu_bgg_nb_player_nb_votes!=$nbre_votes){
                            if($nbre_votes>=1){
                                $nb_player_array=array();
                                foreach ($poll_data_0["results"] as $poll_result_actu){
                                    $poll_result_actu=(array)$poll_result_actu;
                                    //var_dump($poll_result_actu["@attributes"]["numplayers"]);
                                    foreach ($poll_result_actu["result"] as $note_actu){
                                        $note_actu=(array)$note_actu;
                                        //var_dump($note_actu["@attributes"]["value"]);
                                        //var_dump($note_actu["@attributes"]["numvotes"]);
                                        $nb_player_array[$poll_result_actu["@attributes"]["numplayers"]][$note_actu["@attributes"]["value"]]=$note_actu["@attributes"]["numvotes"];
                                    }
                                }
                                //var_dump($nb_player_array);
                                $nb_player_best=null;
                                $nb_player_recommended=null;

                                foreach ($nb_player_array as $key=>$nb_player_actu){
                                    if($nb_player_actu["Best"]>=$nb_player_actu["Recommended"] and $nb_player_actu["Best"]>=$nb_player_actu["Not Recommended"]){
                                        if(is_null($nb_player_best)){
                                            $nb_player_best.="|";
                                        }
                                        $nb_player_best.=$key."|";
                                        //var_dump($nb_player_actu["Best"]);
                                        //var_dump(">=");
                                        //var_dump($nb_player_actu["Recommended"]);
                                    }
                                    if($nb_player_actu["Best"]>=$nb_player_actu["Not Recommended"] or $nb_player_actu["Recommended"]>=$nb_player_actu["Not Recommended"]){
                                        if(is_null($nb_player_recommended)){
                                            $nb_player_recommended.="|";
                                        }
                                        $nb_player_recommended.=$key."|";
                                    }
                                }
                                //echo "<p>".$nb_player_best."</p>";
                                //echo "<p>".$nb_player_recommended."</p>";
                                //var_dump($poll_data_0["results"]);
                            }
                        }
                        //var_dump($poll_data_0["@attributes"]["totalvotes"]);
                    }

                    //var_dump($poll[0]);
                }else{
                    echo "erreur sondage";
                }

                $jeu_de_base_bgg_id=null;

                $link=(array)$array_detail_jeu["link"];
                $array_link_category=array("category","mechanic","designer","artist","expansion","family");
                foreach ($link as $link_actu){
                    $link_actu2=(array)$link_actu;
                    foreach ($array_link_category as $link_category_actu){
                        if($link_actu2["@attributes"]["type"]=="boardgame".$link_category_actu){
                            $array=array(
                                //$link_category_actu."_nom_en"=>$link_actu2["@attributes"]["value"],
                                $link_category_actu."_bgg_id"=>$link_actu2["@attributes"]["id"],
                            );
                            $array2=array(
                                "jeu"=>$bdd_liste_jeux->jeu_id,
                                $link_category_actu=>bdd_ajout($ezine_db,$link_category_actu,$array),
                            );
                            bdd_ajout($ezine_db,$link_category_actu."_jeu",$array2);
                        }
                    }
                    /*if($link_actu2["@attributes"]["type"]=="boardgameexpansion"){
                        $jeu_de_base_bgg_id=$link_actu2["@attributes"]["id"];
                    }*/
                }




                //var_dump($link);
                //var_dump($bdd_cool->jeu_id);

                $array_champ=array(
                    array('jeu_bgg_subtype',$array_detail_jeu["@attributes"]["type"],0),
                    array('jeu_bgg_original_name',$jeu_nom_original,0),
                    array('jeu_bgg_minplayers',$min_player["@attributes"]["value"],0),
                    array('jeu_bgg_maxplayers',$max_player["@attributes"]["value"],0),
                    array('jeu_bgg_note_bayesienne',$jeu_note_bayesienne["@attributes"]["value"],1),
                    array('jeu_bgg_note',$jeu_note["@attributes"]["value"],1),
                    array('jeu_bgg_note_nb_vote',$jeu_note_nb_vote["@attributes"]["value"],1),
                    array('jeu_bgg_averageweight',$jeu_averageweight["@attributes"]["value"],1),
                    array('jeu_bgg_averageweight_nb_vote',$jeu_averageweight_nb_vote["@attributes"]["value"],1),

                    array('jeu_bgg_playingtime',$playingtime["@attributes"]["value"],0),
                    array('jeu_bgg_minplaytime',$minplaytime["@attributes"]["value"],0),
                    array('jeu_bgg_maxplaytime',$maxplaytime["@attributes"]["value"],0),

                    array('jeu_bgg_minage',$minage["@attributes"]["value"],0),
                    //array('jeu_de_base_bgg_id',$jeu_de_base_bgg_id),
                );

                //$sql_cool = "CALL `p_coolitude_id`('".$bdd_liste_jeux->jeu_id."');";
                //echo "<p>".$sql_cool."</p>";
                //$res_cool = mysqli_query ($ezine_db, $sql_cool) or ezine_mysql_die($ezine_db, $sql_cool) ;
                //$num_ticket=mysqli_insert_id($ezine_db);
                //$nbre_cool=mysqli_num_rows($res_cool);
                //$bdd_cool = mysqli_fetch_object($res_cool);*/
                /*if($nbre_cool!=0){
                    $array_champ[]=array('jeu_coolitude',$bdd_cool->n,1);
                }*/

                if($bdd_liste_jeux->jeu_bgg_description!=$description[0]){
                    $array_champ[]=array('jeu_bgg_description',$description[0],0);
                    $array_champ[]=array('jeu_bgg_description_update_date',date("Y-m-d H:i:s"),0);
                }
                if($bdd_liste_jeux->jeu_bgg_nb_player_nb_votes!=$nbre_votes){
                    $array_champ[]=array('jeu_bgg_nb_player_nb_votes',$nbre_votes,1);
                    $array_champ[]=array('jeu_bgg_nb_player_best',$nb_player_best,0);
                    $array_champ[]=array('jeu_bgg_nb_player_recommended',$nb_player_recommended,0);
                }
                //var_dump($array_champ);


                $sql_a_modifier=null;
                $sql_liste_modif=null;
                foreach ($array_champ as $champ_actu){
                    //var_dump($champ_actu);
                    //var_dump($champ_actu[1]."!=".$bdd_liste_jeux->{$champ_actu[0]});
                    if($champ_actu[1]!=$bdd_liste_jeux->{$champ_actu[0]}){

                        if($champ_actu[2]==1){
                            //var_dump("<br>ahah");
                            //var_dump($champ_actu[0]);
                            $sql_existe = "SELECT * \n"
                                . "FROM `evolution_note_type` \n"
                                . "WHERE `evolution_note_type_nom` = '".$champ_actu[0]."'";
                            //echo "<p>".$sql_existe."</p>";
                            $res_existe = mysqli_query ($ezine_db, $sql_existe) or ezine_mysql_die($ezine_db, $sql_existe) ;
                            //$num_ticket=mysqli_insert_id($ezine_db);
                            $nbre_existe=mysqli_num_rows($res_existe);
                            if($nbre_existe==0){
                                $sql_existe2 = "INSERT INTO `evolution_note_type`(`evolution_note_type_nom`) \n"
                                    . "VALUES ('".$champ_actu[0]."') \n";
                                //echo "<p>".$sql_existe."</p>";
                                mysqli_query ($ezine_db, $sql_existe2) or ezine_mysql_die($ezine_db, $sql_existe2) ;
                                $id_evolution_note_type=mysqli_insert_id($ezine_db);
                            }else{
                                $bdd_existe = mysqli_fetch_object($res_existe);
                                $id_evolution_note_type=$bdd_existe->evolution_note_type_id;
                            }
                            //var_dump($id_evolution_note_type);
                            //var_dump($bdd_liste_jeux);
                            $sql_existe3 = "INSERT INTO `evolution_note`(`jeu`, `evolution_note_type`, `evolution_note_de`, `evolution_note_a`) \n"
                                . "VALUES ('".$bdd_liste_jeux->jeu_id."','".$id_evolution_note_type."','".$bdd_liste_jeux->{$champ_actu[0]}."','".$champ_actu[1]."') \n";
                            //echo "<p>".$sql_existe."</p>";
                            mysqli_query ($ezine_db, $sql_existe3) or ezine_mysql_die($ezine_db, $sql_existe3) ;

                        }

                        //echo "<br>".$champ_actu[0]."";
                        //echo "<br>".$champ_actu[1]."!=".$obj->{$champ_actu[0]};
                        if(!is_null($sql_a_modifier)){
                            $sql_a_modifier.=",";
                            $sql_liste_modif.=",";
                        }
                        if(is_null($champ_actu[1]) or $champ_actu[1]=="NULL"){
                            $data="NULL";
                        }else{
                            $data="'".addslashes($champ_actu[1])."'";
                        }
                        $sql_a_modifier.="`".$champ_actu[0]."` = ".$data."";
                        $sql_liste_modif.=$champ_actu[0];
                    }
                    //echo "<br>".$sql_a_modifier."";
                }
                if(!is_null($sql_a_modifier)){
                    $sql2 = "UPDATE `jeu` \n"
                        . "SET ".$sql_a_modifier."\n"
                        . ",`jeu_last_update_script` = NOW() \n"
                        . ",`jeu_last_update` = NOW() \n"
                        . "WHERE `jeu`.`jeu_id` = ".$bdd_liste_jeux->jeu_id.";";
                    //echo "<br>".$sql2;
                    mysqli_query($ezine_db,$sql2);

                    $sql4 = "INSERT INTO `jeu_stats` (`jeu_stats_id`, `jeu_stats_opération`, `jeu_stats_date`,`jeu_stats_quoi`, `jeu_stats_objet`)\n"
                        . "VALUES (NULL, '2', NOW(),'".$sql_liste_modif."','".$bdd_liste_jeux->jeu_id."')";
                    mysqli_query($ezine_db,$sql4);
                    //echo "<br>".$sql4;
                    echo "<br>".$bdd_liste_jeux->jeu_nom." fait!";

                }else{
                    echo "<br> ".$bdd_liste_jeux->jeu_nom.": il n'y a rien a modifier";
                    $sql6 = "UPDATE `jeu` \n"
                        . "SET \n"
                        . "`jeu_last_update_script` = NOW() \n"
                        . "WHERE `jeu`.`jeu_id` = ".$bdd_liste_jeux->jeu_id.";";
                    //echo "<br>".$sql2;
                    mysqli_query($ezine_db,$sql6);
                }
            }else{
                echo "<br>--------------------- pas de lecture -------------------------";
            }
        }
    }
}

?>