<?php
function bouton_classement($txt,$sens="ASC"){
    $sortie="";
    $txt_format = slugify($txt);
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
    $txt_format = slugify($txt);
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
    $txt_format = slugify($txt);
    $retour=null;
    if(isset ($_POST[$txt_format]) ) {
        //var_dump($txt_format."JJJ");
        $retour = '<button type="submit" name="' . $txt_format . '" value="' . $_POST[$txt_format] . '" class="btn btn-primary">Submit</button>';
    }
    return $retour;
}
