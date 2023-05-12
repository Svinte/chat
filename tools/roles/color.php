<?php
    function color($id) {
        include_once("./../../data/get.php");
        include_once("./../../data/get.php");
        $users = getData("users", "*");
        $role = "Main";
        if (isset($users->$id)) {
            $role = $users->$id->role;
        }
        include_once("./../../data/get.php");
        $role = getData("roles", $role);
        $color = $role->color;
        return $color;
    };
?>