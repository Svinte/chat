<?php
    function roleOfUser($userId) {
        include_once("./../../data/get.php");
        $users = getData("users", "users");
        foreach ($users as $key) {
            if ($key[1] == $userId) {
                return $key[3];
                break;
            }
        }
        return "Main";
    }
?>