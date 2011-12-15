<?php

if (!function_exists('getCodeContent')) {

    /**
     * Get stripped file content
     * @param   string  $filename
     * @return  string
     */
    function getCodeContent($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<?php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);
        return $o;
    }

}