<?php
$sql_nbre_description = "SELECT *\n"
    . "FROM `v_avancement`;";
//echo "<p>".$sql_nbre_description."</p>";
$res_nbre_description = mysqli_query ($ezine_db, $sql_nbre_description) or ezine_mysql_die($ezine_db, $sql_nbre_description) ;
//$num_ticket=mysqli_insert_id($ezine_db);
$nbre_nbre_description=mysqli_num_rows($res_nbre_description);
$a_faire=array();
$nb_poids=null;
$nb_description=null;
while($bdd_nbre_description = mysqli_fetch_array($res_nbre_description)){
    if($bdd_nbre_description["nbre_a_faire"]==0){
        $bdd_nbre_description["badge"]=null;
    }else{
        $bdd_nbre_description["badge"]='<span class="badge bg-primary rounded-pill">'.$bdd_nbre_description["nbre_a_faire"].'</span>';
    }
    if($bdd_nbre_description["affichage"]==0){
        $bdd_nbre_description["nbre_a_faire_affichage"]=0;
    }else{
        $bdd_nbre_description["nbre_a_faire_affichage"]=$bdd_nbre_description["nbre_a_faire"];
    }
    $a_faire[]=$bdd_nbre_description;
}
$tot_a_faire = array_sum(array_column($a_faire, 'nbre_a_faire_affichage'));
//var_dump($a_faire);

?>

<header class="site-header sticky-top py-1">
    <nav class="navbar navbar-expand">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gestion jeux</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../web/liste_jeux.php">liste jeux</a></li>
                    <li class="nav-item"><a class="nav-link" href="../web/stats.php">Stats</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Maintenance<span class="badge bg-primary rounded-pill m-2"><?php echo $tot_a_faire; ?></span></a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="../web/liste_jeux.php?p=poids"><li class="d-flex justify-content-between align-items-center">Écrire poids<?php echo $a_faire[0]["badge"]; ?></li></a>
                            <a class="dropdown-item" href="../web/description.php"><li class="d-flex justify-content-between align-items-center">Description<?php echo $a_faire[1]["badge"]; ?></li></a>
                            <a class="dropdown-item" href="../web/liste_jeux.php?p=media"><li class="d-flex justify-content-between align-items-center">Media<?php echo $a_faire[2]["badge"]; ?></li></a>
                            <a class="dropdown-item" href="../maintenance/liste_fichiers.php"><li class="d-flex justify-content-between align-items-center">compléter media<?php echo $a_faire[3]["badge"]; ?></li></a>
                            <a class="dropdown-item" href="../auto/bgg_completion.php" target="_blank"><li class="d-flex justify-content-between align-items-center">Mise à jour</li></a>
                            <a class="dropdown-item" href="../test/data_bgstats2.php" target="_blank"><li class="d-flex justify-content-between align-items-center">Up BGStats</li></a>

                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="../stats/famille.php">familles</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
