<?php
function LogIn($Id, $Password) {
    if (isset($Id) && isset($Password)) {
        $database = file_get_contents("./../../data/users.json");
        $database = json_decode($database);
        if (isset($database->$Id)) {
            if ($database->$Id->password == $Password) {
                return true;
            }   else {
                return false;
            }
        }   else {
            return false;
        }
    }
}
?>