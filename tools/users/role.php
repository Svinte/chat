<?php
    function roleOfUser($userId, $room = false, $roomId = false) {
        $users = file_get_contents("./../../data/users.json");
        $users = json_decode($users);
        if (isset($userId)) {
            if ($room) {
                if (isset($users->$userId->rooms->$roomId)) {
                    return $users->$userId->rooms->$roomId;
                }   else {
                    return "Main";
                }
            }   else {
                return $users->$userId->role;
            }
        }
        return "Main";
    }
?>