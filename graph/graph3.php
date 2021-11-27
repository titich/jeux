<?php
/**
 * Created by PhpStorm.
 * User: lmbcop1
 * Date: 30.01.2017
 * Time: 09:42
 */


?>
<script type="text/javascript">
    Highcharts.stockChart('graph3', {

        rangeSelector: {
            selected: 1
        },

        title: {
            text: 'courbes'
        },
        legend: {
            enabled: true,
        },
        credits: {
            enabled: false
        },
        yAxis: [{ // Primary yAxis

        }, { // Secondary yAxis
            reversed: true,
            allowDecimals: false,
        }],

        series: [<?php echo $graph_data; ?>]
    });
</script>
