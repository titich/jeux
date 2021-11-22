<?php

include_once dirname(__FILE__) . '/../structure/sql.php';

//if (file_exists('https://www.boardgamegeek.com/xmlapi2/collection?username=titich')) {
//$xml = simplexml_load_string('https://www.boardgamegeek.com/xmlapi2/collection?username=titich');
//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1&stats=1');
//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1&id=432');
$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1');
$array = (array)$xml;
//var_dump($array);

/*$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/thing?id=432');
$array = (array)$xml;
var_dump($array);*/


$sql_liste_jeux = "SELECT * \n"
    . "FROM `jeu`  \n"
    . "ORDER BY `jeu_last_update_script` ASC, `jeu_id` ASC\n"
    . "LIMIT 0,25;";
//echo $sql_liste_jeux;

if ($result_liste_jeux = mysqli_query($ezine_db,$sql_liste_jeux )) {
    //echo "<br>Returned rows are: " . mysqli_num_rows($result_liste_jeux);
    if(mysqli_num_rows($result_liste_jeux)!=0){
        while ($bdd_liste_jeux = mysqli_fetch_object($result_liste_jeux)) {
            if($array_detail_jeu_iter2=simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/thing?id='.$bdd_liste_jeux->jeu_bgg_id.'&stats=1')){
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
                $jeu_note_bayesienne=(array)$jeu_stats["bayesaverage"];
                $jeu_averageweight=(array)$jeu_stats["averageweight"];

                $array_champ=array(
                    array('jeu_bgg_subtype',$array_detail_jeu["@attributes"]["type"]),
                    array('jeu_bgg_original_name',$jeu_nom_original),
                    array('jeu_bgg_minplayers',$min_player["@attributes"]["value"]),
                    array('jeu_bgg_maxplayers',$max_player["@attributes"]["value"]),
                    array('jeu_bgg_note_bayesienne',$jeu_note_bayesienne["@attributes"]["value"]),
                    array('jeu_bgg_note',$jeu_note["@attributes"]["value"]),
                    array('jeu_bgg_averageweight',$jeu_averageweight["@attributes"]["value"]),
                );
                //var_dump($array_champ);

                $sql_a_modifier=null;
                $sql_liste_modif=null;
                foreach ($array_champ as $champ_actu){
                    //var_dump($champ_actu);
                    //var_dump($champ_actu[1]."!=".$bdd_liste_jeux->{$champ_actu[0]});
                    if($champ_actu[1]!=$bdd_liste_jeux->{$champ_actu[0]}){
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