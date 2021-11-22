<?php


include_once dirname(__FILE__) . '/../structure/sql.php';

//if (file_exists('https://www.boardgamegeek.com/xmlapi2/collection?username=titich')) {
//$xml = simplexml_load_string('https://www.boardgamegeek.com/xmlapi2/collection?username=titich');
//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1&stats=1');
//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1&id=432');
$xml="erreur";
$erreur=0;


while($xml=="erreur"){
    if($xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1')){
        $array = (array)$xml;
        $bgg_jeux=array();
        $i=0;
        foreach ($array["item"] as $key=>$jeu_actu ){
            $jeu_nom_original=null;
            $jeu_actu_array=(array)$jeu_actu;
            $annee_publication=null;
            if(isset($jeu_actu_array["version"])){
                //var_dump("--------------------------------------------------------------------------------------------------------------------------------------");

                $jeu_bgg_version=(array)$jeu_actu_array["version"];
                if(isset($jeu_bgg_version["item"])) {
                    $jeu_bgg_version_item = (array)$jeu_bgg_version["item"];

                    $jeu_bgg_width = (array)$jeu_bgg_version_item["width"];
                    $jeu_bgg_length = (array)$jeu_bgg_version_item["length"];
                    $jeu_bgg_depth = (array)$jeu_bgg_version_item["depth"];
                    $jeu_bgg_weight = (array)$jeu_bgg_version_item["weight"];
                }else{
                    $jeu_bgg_width["@attributes"]["value"]=null;
                    $jeu_bgg_length["@attributes"]["value"]=null;
                    $jeu_bgg_depth["@attributes"]["value"]=null;
                    $jeu_bgg_weight["@attributes"]["value"]=null;
                }
            }else{
                $jeu_bgg_width["@attributes"]["value"]=null;
                $jeu_bgg_length["@attributes"]["value"]=null;
                $jeu_bgg_depth["@attributes"]["value"]=null;
                $jeu_bgg_weight["@attributes"]["value"]=null;
            }

            if(isset($jeu_actu_array["yearpublished"])){
                $bgg_jeux[$i]["yearpublished"]=$jeu_actu_array["yearpublished"];
                $annee_publication=$jeu_actu_array["yearpublished"];
            }else{
                $annee_publication="NULL";
            }
            $jeu_actu_status =(array)$jeu_actu_array["status"];
            $i++;

            /////////////////////////////////remplissage bdd////////////////////////////////////
            $array_champ=array(
                array('jeu_bgg_yearpublished',$annee_publication),
                array('jeu_bgg_numplays',$jeu_actu_array["numplays"]),
                array('jeu_bgg_image',$jeu_actu_array["image"]),
                array('jeu_bgg_thumbnail',$jeu_actu_array["thumbnail"]),
                array('jeu_bgg_own',$jeu_actu_status["@attributes"]["own"]),
                array('jeu_bgg_lastmodified',$jeu_actu_status["@attributes"]["lastmodified"]),
                array('jeu_bgg_id',$jeu_actu_array["@attributes"]["objectid"]),
                array('jeu_bgg_width',$jeu_bgg_width["@attributes"]["value"]),
                array('jeu_bgg_length',$jeu_bgg_length["@attributes"]["value"]),
                array('jeu_bgg_depth',$jeu_bgg_depth["@attributes"]["value"]),
                array('jeu_bgg_weight',$jeu_bgg_weight["@attributes"]["value"]),
            );

            if($jeu_actu_status["@attributes"]["own"]==1){
                $sql = "SELECT *  FROM `jeu` WHERE `jeu_nom` LIKE '".addslashes($jeu_actu_array["name"])."';";
                if ($result = mysqli_query($ezine_db,$sql )) {
                    if(mysqli_num_rows($result)==1){
                        while ($obj = mysqli_fetch_object($result)) {
                            echo "<br>".$obj->jeu_nom."----------------update------------------------------------------------";

                            $sql_a_modifier=null;
                            $sql_liste_modif=null;
                            foreach ($array_champ as $champ_actu){
                                //if($champ_actu[1]!=$obj->{$champ_actu[0]} and $obj->{$champ_actu[0]}!=0){
                                if($champ_actu[1]!=$obj->{$champ_actu[0]}){
                                    //echo "<br>".$champ_actu[0]."";
                                    //echo "<br>".$champ_actu[1]."!=".$obj->{$champ_actu[0]};
                                    if(!is_null($sql_a_modifier)){
                                        $sql_a_modifier.=",";
                                        $sql_liste_modif.=",";
                                    }
                                    if(is_null($champ_actu[1]) or $champ_actu[1]=="NULL"){
                                        $data="NULL";
                                    }else{
                                        $data="'".$champ_actu[1]."'";
                                    }
                                    $sql_a_modifier.="`".$champ_actu[0]."` = ".$data."";
                                    $sql_liste_modif.=$champ_actu[0];
                                }
                                //echo "<br>".$sql_a_modifier."";
                            }
                            if(!is_null($sql_a_modifier)){
                                $sql2 = "UPDATE `jeu` \n"
                                    . "SET ".$sql_a_modifier."\n"
                                    . "WHERE `jeu`.`jeu_id` = ".$obj->jeu_id.";";
                                //echo "<br>".$sql2;
                                mysqli_query($ezine_db,$sql2);

                                $sql4 = "INSERT INTO `jeu_stats` (`jeu_stats_id`, `jeu_stats_opération`, `jeu_stats_date`,`jeu_stats_quoi`, `jeu_stats_objet`)\n"
                                    . "VALUES (NULL, '2', NOW(),'".$sql_liste_modif."','".$obj->jeu_id."')";
                                mysqli_query($ezine_db,$sql4);
                                //echo "<br>".$sql4;

                            }else{
                                echo "<br> il n'y a rien a modifier";
                            }
                        }
                    }else{
                        echo "<br> pas trouvé: ".$jeu_actu_array["name"]."-------------new-------";
                        $sql_liste_champ=null;
                        $sql_insert_new=null;

                        foreach ($array_champ as $champ_actu){
                            //echo "<br>".$champ_actu[0]."";
                            //echo "<br>".$champ_actu[1]."!=".$obj->{$champ_actu[0]};
                            if(!is_null($sql_liste_champ)){
                                $sql_liste_champ.=",";
                                $sql_insert_new.=",";
                            }
                            $sql_liste_champ.="`".$champ_actu[0]."`";
                            if(is_null($champ_actu[1]) or $champ_actu[1]=="NULL"){
                                $sql_insert_new.="NULL";
                            }else{
                                $sql_insert_new.="'".addslashes($champ_actu[1])."'";
                            }
                            //echo "<br>".$sql_a_modifier."";
                        }



                        $sql2 = "INSERT INTO `jeu` (`jeu_id`,`jeu_nom`,`jeu_bgg_subtype`, ".$sql_liste_champ."\n"
                            ." , `jeu_ceated`) \n"
                            . " VALUES (NULL,'".addslashes($jeu_actu_array["name"])."','boardgame',".$sql_insert_new." \n"
                            . " ,NOW())";
                         echo "<br>".$sql2;
                        mysqli_query($ezine_db,$sql2);

                        $sql5 = "SELECT `jeu_id`  \n"
                            . "FROM `jeu` \n"
                            . "WHERE `jeu_nom` LIKE '".addslashes($jeu_actu_array["name"])."'";
                        if ($result = mysqli_query($ezine_db,$sql5 )) {
                            if(mysqli_num_rows($result)==1){
                                $bdd_id = mysqli_fetch_object($result);
                                $sql3 = "INSERT INTO `jeu_stats` (`jeu_stats_id`, `jeu_stats_opération`, `jeu_stats_date`, `qui`)\n"
                                    . "VALUES (NULL, '1', NOW(), '".$bdd_id->jeu_id."')";
                                mysqli_query($ezine_db,$sql3);
                            }
                        }



                    }
                    // Free result set
                    mysqli_free_result($result);

                }else{

                }
            }
        }
    }else{
        $xml="erreur";
    }
}

//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1') or die($erreur=1);

//var_dump($array);


/*$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/thing?id=432');
$array = (array)$xml;
var_dump($array);*/




//var_dump($array["item"]);


?>