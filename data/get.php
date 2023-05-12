<?php
    function getData($file, $root) {
        if (file_exists("./../../data/$file.json")) {
            $database = file_get_contents("./../../data/$file.json");
        }   elseif (file_exists("./../data/$file.json")) {
            $database = file_get_contents("./../data/$file.json");
        }   else {
            $database = file_get_contents("data/$file.json");
        }
        $database = json_decode($database);
        if ($root == "*") {
            return $database;
        }   else {
            return $database->$root;
        }
    }
?>