<?php
    function law($role, $section) {
        $data = file_get_contents("./../../data/roles.json");
        $data = json_decode($data);
        $role = $data->$role;
        if ($role->law->$section == "true") {
            return "true";
        }   else {
            return "false";
        }
    }
?>