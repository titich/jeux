<?php
/**
 * Created by PhpStorm.
 * User: lmbcop1
 * Date: 30.01.2017
 * Time: 09:42
 */

?>
<script type="text/javascript">
    Highcharts.chart('graph2', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'nbres de jeu par années'
        },
        plotOptions: {
            series: {
                pointStart: <?php echo $premiere_annee; ?>
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Nbre de jeux'
            }
        },
        series: [{
            name: 'Installation',
            data: [<?php echo $sorie_graph2; ?>]
        }]
    });
</script>
