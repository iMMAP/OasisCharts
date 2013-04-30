<?php

function str2hex($string) {
    $hex = "";
    for ($i = 0; $i < strlen($string); $i++)
        $hex .= (strlen(dechex(ord($string[$i]))) < 2) ? "0" . dechex(ord($string[$i])) : dechex(ord($string[$i]));
    return $hex;
}

function get_md5($pStr) {
    $data = mb_convert_encoding($pStr, 'UTF-16LE', 'UTF-8');
    $h = str2hex(md5($data, true));
    return strtoupper($h);
}

function getDDFormat($ddName, $table) {
    return "dd_" . $ddName . "_" . $table;
}

function immap_session_start()
{
    if(session_id() === '') {
        session_start();
    } 
}

?>