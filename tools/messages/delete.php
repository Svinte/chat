<?php
    include_once("./../users/LogIn.php");
    include_once("sender.php");
    include_once("./../../data/get.php");
    include_once("./../roles/law.php");
    include_once("./../users/role.php");
    $post = json_decode(file_get_contents("php://input"));
    $Id = strval($post->Id);
    $password = $post->password;
    $index = $post->index;
    $database = getData("database", "*");
    function delete($index, $database) {
        $item = $database->messages[$index];
        array_splice($database->messages, $index, 1);
        $database = json_encode($database);
        file_put_contents("./../../data/database.json", $database);
        include_once("./../../data/get.php");
        $database = getData("deleted", "*");
        $new = array (
            "Deleted" => date("Y-m-d-h-i-S"),
            "Id" => $item->Id,
            "Value" => $item->Value,
            "Date" => $item->Date
        );
        array_push($database->deleted, $new);
        $database = json_encode($database);
        file_put_contents("./../../data/deleted.json", $database);
    }
    if (LogIn($Id, $password) == "true") {
        if (sender($index) == $Id) {
            delete($index, $database);
        }   else if (law(roleOfUser($Id), "Moderate") == "true") {
            delete($index, $database);
        }
    }
?>