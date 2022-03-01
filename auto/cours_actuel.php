<?php
$api_exchangerate= null;
include_once dirname(__FILE__) . '/../structure/sql.php';
require dirname(__FILE__) . '/../vendor/autoload.php';
require dirname(__FILE__) . '/../cle/cle.php';


$req_url = 'https://v6.exchangerate-api.com/v6/'.$api_exchangerate.'/latest/CHF';
$response_json = file_get_contents($req_url);

// Continuing if we got a result
if(false !== $response_json) {

    // Try/catch for json_decode operation
    try {

        // Decoding
        $response = json_decode($response_json);

        // Check for success
        if('success' === $response->result) {

            // YOUR APPLICATION CODE HERE, e.g.
            //$base_price = 12; // Your price in USD
            //$valeur =  round(($valeur * $response->conversion_rates->CHF), 2);
            //$valeur2 =$response->conversion_rates->CHF;
            $euro =$response->conversion_rates->EUR;
            $dollar =$response->conversion_rates->USD;
            $australian_dollar =$response->conversion_rates->AUD;

        }

    }
    catch(Exception $e) {
        // Handle JSON parse error...
    }

}

/*var_dump($valeur);
var_dump("<hr>");
var_dump($valeur2);
var_dump("<hr>");*/




$sql_cours = "INSERT INTO `cours_actuel` (`cours_actuel_id`, `cours_actuel_EUR`, `cours_actuel_USD`, `cours_actuel_AUD`, `cours_actuel_date`)\n"
    . "VALUES (NULL, '".$euro."', '".$dollar."', '".$australian_dollar."', NOW());";

//echo "<p>".$sql_cours."</p>";
mysqli_query ($ezine_db, $sql_cours) or ezine_mysql_die($ezine_db, $sql_cours) ;






?>

