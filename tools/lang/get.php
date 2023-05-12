<?php
function getLang($lang = "en-EN", $mark = false) {
    $database = file_get_contents("./../../data/lang.json");
    $database = json_decode($database);
    if ($database->$lang === null) {
        $lang = "en-EN";
    }
    if ($mark) {
        return $lang;
    }   else {
        return $database->$lang;
    }
} 
?>