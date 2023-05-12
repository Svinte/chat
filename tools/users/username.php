<?php
    function username($userId) {
        $database = file_get_contents("./../../data/users.json");
        $database = json_decode($database);
        if (isset($database->$userId)) {
            return $database->$userId->name;
        }
        return "Deleted user";
    }
?>