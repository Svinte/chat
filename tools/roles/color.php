<?php
    function color($id) {
        include_once("./../../data/get.php");
        $database = getData("database", "*");
        include_once("./../../data/get.php");
        $users = getData("users", "users");
        $role = "Main";
        foreach ($users as $key) {
            if ($id == $key[1]) {
                $role = $key[3];
                break;
            }
        }
        include_once("./../../data/get.php");
        $role = getData("roles", $role);
        $color = $role->color;
        return $color;
    };
?>