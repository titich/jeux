<?php
?>
<script type="text/javascript">
    function dropdown_perso(quoi){
        let i=0;
        let age_max = <?php echo $age_max; ?>;
        //alert(quoi);
        if(quoi==0){
            document.getElementById("age_min_joueur").innerHTML = 'tout';
        }else{
            document.getElementById("age_min_joueur").innerHTML = quoi;
        }

        while (i < age_max){
            //alert (i);
            //let Element_actu= "li_age_min_joueur_"+ i;
            //alert (Element_actu);
            document.getElementById("li_age_min_joueur_"+ i).classList.remove("active");
            i++;
        }
        //alert ("fini");
        document.getElementById("li_age_min_joueur_" + quoi).classList.add("active");

        document.getElementById("age_min_joueur_val").value = quoi;

    }
</script>
