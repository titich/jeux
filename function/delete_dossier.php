<?php
function delete_dossier($path) {
    if (is_dir($path)) {
        array_map(function($value) {
            $this->delete($value);
            rmdir($value);
        },glob($path . '/*', GLOB_ONLYDIR));
        array_map('unlink', glob($path."/*"));
    }
}