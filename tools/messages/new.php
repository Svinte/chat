<?php
    include_once("./../users/LogIn.php");
    include_once("./../../data/get.php");
    $post = json_decode(file_get_contents("php://input"));
    $Id = strval($post->Id);
    $password = $post->password;
    $value = $post->value;
    if (isset($Id) && isset($password) && isset($value)) {
        if (LogIn($Id, $password)) {
            $database = getData("database", "*");
            $new = [
                "Id" => $Id,
                "Value" => $value,
                "Date" => date("Y-m-d-h-i", time())
            ];
            array_push($database->messages, $new);
            $database = json_encode($database);
            file_put_contents("./../../data/database.json", $database);
        }
    }
?>