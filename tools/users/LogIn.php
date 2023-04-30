<?php
    function LogIn($Id, $Password) {
        if (isset($Id) && isset($Password)) {
            $database = file_get_contents("./data/users.json");
            $database = json_decode($database);
            foreach ($database->users as $key) {
                if ($key[1] == $Id) {
                    if ($key[2] == $Password) {
                        return "false";
                        break;
                    }
                }
            }
        }
        return "true";
    }
?>