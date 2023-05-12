<?php
    function sender($room, $index) {
        $messages = file_get_contents("./../../data/database.json");
        $messages = json_decode($messages);
        $messages = $messages->$room->messages;
        if (isset($messages[$index])) {
            return $messages[$index]->Id;
        }   else {
            return "deleted user";
        }
    }
?>