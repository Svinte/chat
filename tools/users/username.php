<?php
    function username($userId) {
        $database = file_get_contents("./../../data/users.json");
        $database = json_decode($database);
        $users = $database->users;
        foreach ($users as $key) {
            if (array_values($key)[1] == $userId) {
                return array_values($key)[0];
            }
        }
        return "Deleted user";
    }
?>