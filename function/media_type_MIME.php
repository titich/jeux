<?php
function media_type_MIME($mime){
    switch ($mime) {
        case "application/pdf":
            $sortie='<h3><i class="far fa-file-pdf"></i></h3>';
            break;
        case "image/jpg":
        case "image/png":
            $sortie='<h3><i class="far fa-file-image"></i></h3>';
            break;
        case "application/word":
            $sortie='<h3><i class="far fa-file-word"></i></h3>';
            break;
        case "application/powerpoint":
            $sortie='<h3><i class="far fa-file-powerpoint"></i></h3>';
            break;
        case "application/excel":
            $sortie='<h3><i class="far fa-file-excel"></i></h3>';
            break;
        case "sound/mp3":
            $sortie='<h3><i class="far fa-file-audio"></i></h3>';
            break;
        case "application/zip":
            $sortie='<h3><i class="far fa-file-archive"></i></h3>';
            break;
        default:
            $sortie='<h3><i class="far fa-file"></i></h3>';
            break;
    }
    return $sortie;
}
