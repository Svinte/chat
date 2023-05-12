<?php
    function law($role, $section) {
        include_once("./../../data/get.php");
        if (getData("roles", $role)->law->$section == "true") {
            return "true";
        }   else {
            return "false";
        }
    }
?>