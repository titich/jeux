<?php

class gestion_dossier
{
    /**
     * @see http://php.net/manual/de/function.array-map.php
     * @see http://www.php.net/manual/en/function.rmdir.php
     * @see http://www.php.net/manual/en/function.glob.php
     * @see http://php.net/manual/de/function.unlink.php
     * @param string $path
     */
    public function delete($path) {
        if (is_dir($path)) {
            array_map(function($value) {
                $this->delete($value);
                rmdir($value);
            },glob($path . '/*', GLOB_ONLYDIR));
            array_map('unlink', glob($path."/*"));
        }
    }
}
