<?php
function LogIn($Id, $Password) {
    if (file_exists("./../../data/users/$Id.json")) {
        if (isset($Id) && isset($Password)) {
            $database = file_get_contents("./../../data/users/$Id.json");
            $database = json_decode($database);
            if ($database->password === $Password) {
                return true;
            }   else {
                return false;
            }
        }   else {
            return false;
        }
    }   else {
        return false;
    }
}
?>