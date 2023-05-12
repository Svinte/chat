<?php
function Messsages($id, $password, $room, $lang) {
    include_once("./../../tools/lang/get.php");
    $lang = getLang($lang);
    if (isset($id) && isset($password) && isset($room)) {
        include_once("./../../tools/users/LogIn.php");
        if (LogIn($id, $password)) {
            $database = file_get_contents("./../../data/users.json");
            $database = json_decode($database);
            if (isset($database->$id)) {
                $role = $database->$id->rooms->$room;
                $messages = file_get_contents("./../../data/database/$room.json");
                $messages = json_decode($messages);
                return array("error" => "none", "messages" => $messages, "role" => $role);
            }   else {
                return array("error" => $lang->NfoundRoom);
            }
        }   else {
            return array("error" => $lang->Iuser);
        }
    }
}
?>