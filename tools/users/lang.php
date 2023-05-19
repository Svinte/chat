<?php
function userLang($id, $password, $mark = false) {
    if (isset($id) && isset($password)) {
        include_once("./../../tools/users/LogIn.php");
        if (LogIn($id, $password)) {
            $database = file_get_contents("./../../data/settings.json");
            $database = json_decode($database);
            $lang = $database->$id->lang;
            if ($mark) {
                return $lang;
            }   else {
                include_once("./../../tools/lang/get.php");
                return getLang($lang);
            }
        }   else {
            return "en-EN";
        }
    }   else {
        return "en-EN";
    }
}
?>