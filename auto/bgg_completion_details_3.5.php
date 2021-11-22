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

        $sql_controle_where.="AND `".$key."` LIKE '".addslashes($value)."'";
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
                $jeu_note_bayesienne=(array)$jeu_stats["bayesaverage"];
                $jeu_averageweight=(array)$jeu_stats["averageweight"];

                $playingtime=(array)$array_detail_jeu["playingtime"];
                $minplaytime=(array)$array_detail_jeu["minplaytime"];
                $maxplaytime=(array)$array_detail_jeu["maxplaytime"];
                $minage=(array)$array_detail_jeu["minage"];

                $link=(array)$array_detail_jeu["link"];

                foreach ($link as $link_actu){
                    $link_actu2=(array)$link_actu;
                    if($link_actu2["@attributes"]["type"]=="boardgamecategory"){

                        $array=array(
                            "category_nom_en"=>$link_actu2["@attributes"]["value"],
                        );
                        $array2=array(
                            "jeu"=>$bdd_liste_jeux->jeu_id,
                            "category"=>bdd_ajout($ezine_db,"category",$array),
                        );
                        bdd_ajout($ezine_db,"category_jeu",$array2);
                    }elseif($link_actu2["@attributes"]["type"]=="boardgamemechanic"){

                        $array=array(
                            "mechanic_nom_en"=>$link_actu2["@attributes"]["value"],
                        );
                        $array2=array(
                            "jeu"=>$bdd_liste_jeux->jeu_id,
                            "mechanic"=>bdd_ajout($ezine_db,"mechanic",$array),
                        );
                        bdd_ajout($ezine_db,"mechanic_jeu",$array2);
                    }
                }
                //var_dump($link);

                $array_champ=array(
                    array('jeu_bgg_subtype',$array_detail_jeu["@attributes"]["type"]),
                    array('jeu_bgg_original_name',$jeu_nom_original),
                    array('jeu_bgg_minplayers',$min_player["@attributes"]["value"]),
                    array('jeu_bgg_maxplayers',$max_player["@attributes"]["value"]),
                    array('jeu_bgg_note_bayesienne',$jeu_note_bayesienne["@attributes"]["value"]),
                    array('jeu_bgg_note',$jeu_note["@attributes"]["value"]),
                    array('jeu_bgg_averageweight',$jeu_averageweight["@attributes"]["value"]),

                    array('jeu_bgg_playingtime',$playingtime["@attributes"]["value"]),
                    array('jeu_bgg_minplaytime',$minplaytime["@attributes"]["value"]),
                    array('jeu_bgg_maxplaytime',$maxplaytime["@attributes"]["value"]),
                    array('jeu_bgg_minage',$minage["@attributes"]["value"]),
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