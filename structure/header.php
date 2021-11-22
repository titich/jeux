<?php
$path = $_SERVER['PHP_SELF'];
//var_dump($path);
$dossier_array=explode("/",$path);
//var_dump($dossier_array);

$tabUrl = parse_url ( $_SERVER [ 'REQUEST_URI' ] ) ;
define("FILE", basename ($path));
define("DOSSIER", $dossier_array[2]);

//var_dump($tabUrl);
if(array_key_exists('query',$tabUrl)){
    define("URL_PAR",$tabUrl['query']);
}
else{
    define("URL_PAR","");
}
$sallage_champ_ajout_suppression=rand();

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Conod Pierre">
    <title>Gestion Jeux</title>

    <!-- Bootstrap core CSS -->
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="../favicon/meeple_180.png" sizes="180x180">
    <link rel="icon" href="../favicon/meeple_032.png" sizes="32x32" type="image/png">
    <link rel="icon" href="../favicon/meeple_016.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#7952b3">


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="../node_modules/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/perso.css" rel="stylesheet">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="../node_modules/bootstrap-select/dist/css/bootstrap-select.min.css">

</head>
<body>

<?php
include_once dirname(__FILE__) . '/../structure/menu.php';
?>
