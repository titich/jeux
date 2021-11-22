<?php
function btn_click($nom_array,$zone){
    $sortie=null;
    foreach ($nom_array as $key=>$nom){
        $etat_btn_best="btn-outline-secondary";
        $etat_btn_best_value=1;
        //$invisible=null;
        ///$nom_clean = strtr($nom, 'ÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜÝáàâäãåçéèêëíìîïñóòôöõúùûüýÿ', 'AAAAAACEEEEEIIIINOOOOOUUUUYaaaaaaceeeeiiiinooooouuuuyy');
        //$nom_clean = nettoyerChaine($nom);
        $nom_clean = slugify($nom);
        $actif=null;
        if(isset($_POST[$zone.'_type'])){
            if($_POST[$zone.'_type']==$nom_clean){
                $actif="checked";
            }
        }elseif($key==0){
            $actif="checked";
        }
        //$sortie.='<button id="nbjoueurs_'.$nom.'" name="nbjoueurs_'.$nom.'" class="btn '.$etat_btn_best.'" type="submit" value="'.$etat_btn_best_value.'">'.ucfirst($nom).'</button>';
        $sortie.='<input type="radio" class="btn-check" name="'.$zone.'_type" id="'.$zone.'_'.$nom_clean.'" autocomplete="off" value="'.$nom_clean.'" '.$actif.'>';
        $sortie.='<label class="btn btn-outline-primary" for="'.$zone.'_'.$nom_clean.'">'.$nom.'</label>';
    }

    return $sortie;
}