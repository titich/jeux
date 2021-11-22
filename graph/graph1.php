<?php
/**
 * Created by PhpStorm.
 * User: lmbcop1
 * Date: 30.01.2017
 * Time: 09:42
 */

?>
<script type="text/javascript">
    Highcharts.chart('<?php echo $cat_actu; ?>', {
        chart: {
            //type: 'column'
            type: 'pie'
            //type: 'treemap'
        },
        title: {
            text: 'nbre par <?php echo $cat_actu; ?>'
        },
        <?php echo $graph_data; ?>
    });
</script>
