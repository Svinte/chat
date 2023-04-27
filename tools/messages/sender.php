<?php
    function sender($index) {
        $messages = file_get_contents("./../../data/database.json");
        $messages = json_decode($messages);
        $messages = $messages->messages;
        if (isset($messages[$index])) {
            return $messages[$index]->Id;
        }   else {
            return "deleted user";
        }
    }
?>