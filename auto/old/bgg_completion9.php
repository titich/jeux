<?php

include_once dirname(__FILE__) . '/../structure/sql.php';

//if (file_exists('https://www.boardgamegeek.com/xmlapi2/collection?username=titich')) {
//$xml = simplexml_load_string('https://www.boardgamegeek.com/xmlapi2/collection?username=titich');
//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1&stats=1');
//$xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1&id=432');
$xml="erreur";
$erreur=0;

function bdd_ajout($ezine_db,$table,$array_champ){
    //$val=$link_actu2["@attributes"]["value"];
    //echo"<br> Catégorie:".$link_actu2["@attributes"]["value"];
    //var_dump($table);
    //var_dump($array_champ);
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

while($xml=="erreur"){
    if($xml = simplexml_load_file('https://www.boardgamegeek.com/xmlapi2/collection?username=titich&version=1')){
        $array = (array)$xml;
        $bgg_jeux=array();
        $i=0;
        foreach ($array["item"] as $key=>$jeu_actu ){
            //var_dump($jeu_actu);
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

                    $link=(array)$jeu_bgg_version_item["link"];
                    //var_dump($link);
                    $array_link_category=array("boardgamepublisher","language");

                    $sql_info = "SELECT `jeu_id`\n"
                        . "FROM `jeu`\n"
                        . "WHERE\n"
                        . "    `jeu_bgg_id` = ".$jeu_actu_array["@attributes"]["objectid"]."\n"
                        . "    AND `jeu_bgg_collid` = ".$jeu_actu_array["@attributes"]["collid"];

                    //echo "<br>".$sql."<br>"; // si trouvé jeu_id autrement increment
                    //echo "<p>".$sql_info."</p>";
                    $res_info = mysqli_query ($ezine_db, $sql_info) or ezine_mysql_die($ezine_db, $sql_info) ;
                    //$num_ticket=mysqli_insert_id($ezine_db);
                    $nbre_info=mysqli_num_rows($res_info);
                    //var_dump($nbre_info);
                    //var_dump($num_ticket);
                    if($nbre_info == 0){
                        $sql_info2 = "SELECT `AUTO_INCREMENT`+1 as `id_actu`\n"
                            . "FROM  INFORMATION_SCHEMA.TABLES\n"
                            . "WHERE TABLE_SCHEMA = 'jeux'\n"
                            . "AND   TABLE_NAME   = 'jeu';";
                        //echo "<p>".$sql_info2."</p>";
                        $res_info2 = mysqli_query ($ezine_db, $sql_info2) or ezine_mysql_die($ezine_db, $sql_info2) ;
                        //$num_ticket=mysqli_insert_id($ezine_db);
                        $nbre_info2=mysqli_num_rows($res_info2);
                        $bdd_info = mysqli_fetch_object($res_info2);
                        $id_j=$bdd_info->id_actu;

                    }else{
                        $bdd_info = mysqli_fetch_object($res_info);
                        $id_j=$bdd_info->jeu_id;
                    }
                    //echo"<br>".$id_j."<br>";

                    foreach ($link as $link_actu){
                        $link_actu2=(array)$link_actu;
                        //var_dump($link_actu2);
                        //var_dump($link_actu2["@attributes"]["type"]);

                        foreach ($array_link_category as $link_category_actu){
                            //var_dump($link_actu2["@attributes"]["type"]);
                            //var_dump($link_category_actu);
                            if($link_actu2["@attributes"]["type"]==$link_category_actu){
                                //var_dump("@@");
                                $array=array(
                                    $link_category_actu."_nom_en"=>$link_actu2["@attributes"]["value"],
                                    $link_category_actu."_bgg_id"=>$link_actu2["@attributes"]["id"],
                                );
                                $array2=array(
                                    "jeu"=>$id_j,
                                    $link_category_actu=>bdd_ajout($ezine_db,$link_category_actu,$array),
                                );
                                bdd_ajout($ezine_db,$link_category_actu."_jeu",$array2);
                            }
                        }
                        /*if($link_actu2["@attributes"]["type"]=="boardgameexpansion"){
                            $jeu_de_base_bgg_id=$link_actu2["@attributes"]["id"];
                        }*/
                    }

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

            if(!isset($jeu_actu_array["image"])){
                $jeu_actu_array["image"]=null;
            }
            if(!isset($jeu_actu_array["thumbnail"])){
                $jeu_actu_array["thumbnail"]=null;
            }


            $jeu_actu_status =(array)$jeu_actu_array["status"];
            $i++;

            //var_dump($jeu_actu_status);

            /////////////////////////////////remplissage bdd////////////////////////////////////
            $array_champ=array(
                array('jeu_bgg_yearpublished',$annee_publication),
                array('jeu_bgg_numplays',$jeu_actu_array["numplays"]),
                array('jeu_bgg_image',$jeu_actu_array["image"]),
                array('jeu_bgg_thumbnail',$jeu_actu_array["thumbnail"]),
                array('jeu_bgg_own',$jeu_actu_status["@attributes"]["own"]),
                array('jeu_bgg_preordered',$jeu_actu_status["@attributes"]["preordered"]),
                array('jeu_bgg_prevowned',$jeu_actu_status["@attributes"]["prevowned"]),
                array('jeu_bgg_fortrade',$jeu_actu_status["@attributes"]["fortrade"]),
                array('jeu_bgg_want',$jeu_actu_status["@attributes"]["want"]),
                array('jeu_bgg_wanttoplay',$jeu_actu_status["@attributes"]["wanttoplay"]),
                array('jeu_bgg_wanttobuy',$jeu_actu_status["@attributes"]["wanttobuy"]),
                array('jeu_bgg_wishlist',$jeu_actu_status["@attributes"]["wishlist"]),
                array('jeu_bgg_lastmodified',$jeu_actu_status["@attributes"]["lastmodified"]),
                array('jeu_bgg_id',$jeu_actu_array["@attributes"]["objectid"]),
                array('jeu_bgg_width',$jeu_bgg_width["@attributes"]["value"]),
                array('jeu_bgg_length',$jeu_bgg_length["@attributes"]["value"]),
                array('jeu_bgg_depth',$jeu_bgg_depth["@attributes"]["value"]),
                array('jeu_bgg_weight',$jeu_bgg_weight["@attributes"]["value"]),
                array('jeu_bgg_collid',$jeu_actu_array["@attributes"]["collid"]),
            );


            if($jeu_actu_status["@attributes"]["own"]==1 or $jeu_actu_status["@attributes"]["own"]==0){
                //if($jeu_actu_status["@attributes"]["own"]==1){
                $sql = "SELECT *\n"
                    . "FROM `jeu`\n"
                    . "WHERE\n"
                    . "    `jeu_bgg_id` = ".$jeu_actu_array["@attributes"]["objectid"]."\n"
                    . "    AND `jeu_bgg_collid` = ".$jeu_actu_array["@attributes"]["collid"];

                /*if($jeu_actu_array["@attributes"]["objectid"]==38984){
                    echo "<br>".$sql."<br>";
                }*/
                //$sql = "SELECT *  FROM `jeu` WHERE `jeu_nom` LIKE '".addslashes($jeu_actu_array["name"])."';";
                if ($result = mysqli_query($ezine_db,$sql )) {
                    if(mysqli_num_rows($result)==1){
                        while ($obj = mysqli_fetch_object($result)) {
                            echo "<br>".$obj->jeu_nom."----------------update------------------------------------------------";

                            $sql_a_modifier=null;
                            $sql_liste_modif=null;
                            foreach ($array_champ as $champ_actu){
                                //var_dump($obj->jeu_id);
                                //var_dump($champ_actu[0]);
                                if($champ_actu[0]=="jeu_bgg_collid"){
                                    //var_dump("");
                                    //var_dump($champ_actu[1]);
                                    //var_dump($obj->{$champ_actu[0]});
                                }


                                //if($champ_actu[1]!=$obj->{$champ_actu[0]} and $obj->{$champ_actu[0]}!=0){
                                if(($champ_actu[1]!=$obj->{$champ_actu[0]}) AND !($champ_actu[1]=="NULL" AND is_null($obj->{$champ_actu[0]}))){
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
                                /*if($obj->jeu_id=="495"){
                                    echo "<br>".$sql2;
                                    echo "<br>".$sql4;
                                }*/

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