<?php
$post = file_get_contents("php://input");
$post = json_decode($post);
$id = $post->id;
$password = $post->password;
$type = $post->type;
$room = $post->room;
include_once("./../../tools/lang/get.php");
$language = getLang($post->lang, true);
$lang = getLang($post->lang);
if (isset($post->room)) {
    include_once("./../../tools/users/LogIn.php");
    if (LogIn($id, $password)) {
        $database = file_get_contents("./../../data/settings.json");
        $database = json_decode($database);
        if ($type == "get") {
            if ($room == "*") {
                $delIndex = 0;
            }   else {
                if (file_exists("./../../data/database/$room.json")) {
                    $delIndex = file_get_contents("./../../data/database/$room.json");
                    $delIndex = json_decode($delIndex);
                    $delIndex = $delIndex->deleted;
                    $delIndex = count($delIndex);
                }
            }
            if (isset($database->$id)) {
                $database = file_get_contents("./../../data/settings.json");
                $database = json_decode($database);
                $data = $database->$id;
                $roomsId = file_get_contents("./../../data/users.json");
                $roomsId = json_decode($roomsId);
                $roomsId = $roomsId->$id->rooms;
                $roomsJson = file_get_contents("./../../data/rooms.json");
                $roomsJson = json_decode($roomsJson);
                $rooms = array();
                foreach ($roomsId as $key => $value) {
                    error_log($key);
                    if (isset($roomsJson->$key)) {
                        array_push($rooms, array("name" => $roomsJson->$key->name, "id" => $key));
                    }   else {
                        //poista profiilista
                    }
                }
                echo json_encode(array(
                    "error" => "none",
                    "response" => $data,
                    "delIndex" => $delIndex,
                    "rooms" => $rooms
                ));
            }   else {
                $data = array(
                    "update" => 1000,
                    "verify" => false,
                    "lang" => $language
                );
                $database->$id = $data;
                $database = json_encode($database);
                file_put_contents("./../../data/settings.json", $database);
                echo json_encode(array(
                    "error" => "none",
                    "response" => $data,
                    "delIndex" => $delIndex
                ));
            }
        }   else if ($type == "set") {
            $value = $post->value;
            
        }   else {
            echo json_encode(array(
                "error" => $lang->IrequestType
            ));
        }
    }   else {
        echo json_encode(array(
            "error" => $lang->Iuser
        ));
    }
}   else {
    echo json_encode(array(
        "error" => $lang->Mdata
    ));
}