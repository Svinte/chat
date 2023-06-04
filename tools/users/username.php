<?php
    function username($userId) {
        if (file_exists("./../../data/users/$userId.json")) {
            $database = file_get_contents("./../../data/users/$userId.json");
            $database = json_decode($database);
            return $database->name;
        }   else {
            return "Deleted user";
        }
    }
?>