<?php
    function color($id, $room = "*") {
        if (file_exists("./../../data/users/$id.json")) {
            $user = file_get_contents("./../../data/users/$id.json");
            $user = json_decode($user);
            if ($room == "*") {
                $urole = $user->role;
            }   else {
                $urole = $user->rooms->$room;
            }
        }   else {
            $urole = "Main";
        }
        $role = file_get_contents("./../../data/roles.json");
        $role = json_decode($role);
        $role = $role->$urole;
        $color = $role->color;
        return $color;
    };
?>