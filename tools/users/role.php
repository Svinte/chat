<?php
    function roleOfUser($userId, $room = false, $roomId = false) {
        if (isset($userId)) {
            if (file_exists("./../../data/users/$userId.json")) {
                $user = file_get_contents("./../../data/users/$userId.json");
                $user = json_decode($user);
                if ($room) {
                    if (isset($user->rooms->$roomId)) {
                        return $user->rooms->$roomId;
                    }   else {
                        return "Main";
                    }
                }   else {
                    return $user->role;
                }
            }   else {
                return "Main";
            }
        }   else {
            return "Main";
        }
    }
?>