<?php


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
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Maintenance</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../web/liste_jeux.php?p=poids">ecrire poids</a></li>
                            <li><a class="dropdown-item" href="../web/description.php">Description</a></li>
                            <li><a class="dropdown-item" href="../auto/bgg_completion.php" target="_blank">Mise à jour</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
