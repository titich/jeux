<?php
$selected = null;
if(!is_null($cat_sous_select)){
    $selected=$cat_sous_select;
}
?>
<script type="text/javascript">
    function liste_sous_cat(cat)
    {
        var chaine_sous_cat="";

        switch(cat)
        {
            <?php echo $liste_js_sous_cat; ?>
        }
        return chaine_sous_cat;
    }
    function charger_sous_cat()
    {
        var sous_cat=""; var nb_sous_cat=0; var chaine_liste="";
        var cat_txt = document.getElementById("choix_cat").value;
        var cat = cat_txt;
        var selected="";

        if(cat!="00") {
            //alert(cat_txt + "-" + cat);
            sous_cat = liste_sous_cat(cat).split('|');
            nb_sous_cat = sous_cat.length;
            //alert(nb_sous_cat);

            chaine_liste = "<select id='choix_sous_cat' name='choix_sous_cat' class='form-select' onChange='change_sous_cat(\"" + cat_txt +  "\")'>";
            chaine_liste += "<option hidden disabled selected value='Sélection'>Sélectionner une ville</option>";
            for(var defil=0;defil<nb_sous_cat; defil++)
            {
                if(sous_cat[defil] == '<?php echo $selected; ?>'){
                    selected='selected';
                }else{
                    selected="";
                }
                chaine_liste += "<option "+ selected +" value='" + sous_cat[defil] + "'>" + sous_cat[defil] + "</option>";
            }
            chaine_liste += "</select>";
            document.getElementById("calque_ville").innerHTML = chaine_liste;

        } else {
            alert("Veuillez préciser une catégorie");
        }
    }


    function change_sous_cat(cat_txt)
    {
        var sous_cat2 = document.getElementById("choix_sous_cat").value;
        var btn_go = "";

        //alert("Veuillez préciser une catégorie " + cat_txt + " " + sous_cat2);

        btn_go = '<button class="btn btn-outline-secondary" type="submit">Go!</button>';
        document.getElementById("calque_btn_go").innerHTML = btn_go;


    }
</script>
<?php
if(!is_null($cat_sous_select)){
    ?>
    <script type="text/javascript">
        charger_sous_cat();
    </script>
    <?php
}
?>