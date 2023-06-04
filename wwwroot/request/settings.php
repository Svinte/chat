<?php
$post = file_get_contents("php://input");
$post = json_decode($post);
$id = $post->id;
$password = $post->password;
$type = $post->type;
$room = strtolower($post->room);
include_once("./../../tools/lang/get.php");
$language = getLang($post->lang, true);
$lang = getLang($post->lang);
if (isset($post->room)) {
    include_once("./../../tools/users/LogIn.php");
    if (LogIn($id, $password)) {
        $database = file_get_contents("./../../data/users/$id.json");
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
            $trouble = false;
            $database = file_get_contents("./../../data/users/$id.json");
            $database = json_decode($database);
            $roomsId = $database->rooms;
            if (isset($database->avatar)) {
                $avatar = $database->avatar;
            }   else {
                $database->avatar = "https://betonikasa.netlify.app/gallery/favicon.ico";
                $trouble = true;
            }
            $roomsJson = file_get_contents("./../../data/rooms.json");
            $roomsJson = json_decode($roomsJson);
            $rooms = array();
            foreach ($roomsId as $key => $value) {
                if (isset($roomsJson->$key)) {
                    array_push($rooms, array("name" => $roomsJson->$key->name, "id" => $key));
                }   else {
                    unset($database->rooms->$key);
                    $trouble = true;
                }
            }
            if ($trouble) {
                file_put_contents("./../../data/users/$id.json", json_encode($database));
            }
            if (isset($database->settings)) {
                $data = $database->settings;
                echo json_encode(array(
                    "error" => "none",
                    "response" => $data,
                    "delIndex" => $delIndex,
                    "rooms" => $rooms,
                    "avatar" => $avatar
                ));
            }   else {
                $data = array(
                    "update" => 1000,
                    "verify" => false,
                    "lang" => $language,
                    "avatar" => $avatar
                );
                $database->settings = $data;
                $database = json_encode($database);
                file_put_contents("./../../data/users/$id.json", $database);
                echo json_encode(array(
                    "error" => "none",
                    "response" => $data,
                    "delIndex" => $delIndex,
                    "rooms" => $rooms
                ));
            }
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