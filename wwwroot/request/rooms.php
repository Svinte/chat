<?php
$post = file_get_contents("php://input");
$post = json_decode($post);
if (isset($post->type) && isset($post->value) && isset($post->id) && isset($post->password)) {
    $value = $post->value;
    $id = $post->id;
    $password = $post->password;
    include_once("./../../tools/users/LogIn.php");
    if (LogIn($id, $password)) {
        include_once("./../../tools/users/lang.php");
        $lang = userLang($id, $password);
        switch ($post->type) {
            case "create":
                if (isset($value->name) && isset($value->public)) {
                    $name = $value->name;
                    $rid = strtolower($name);
                    if (31 > strlen($name) && strlen($name) > 0 && $name !== "*") {
                        $date = date("D M d Y H:i:s O");
                        $rooms = file_get_contents("./../../data/rooms.json");
                        $rooms = json_decode($rooms);
                        $user = file_get_contents("./../../data/users/$id.json");
                        $user = json_decode($user);
                        if (count(get_object_vars($user->rooms)) < 100) {
                            if ($value->public) {
                                if (isset($rooms->$rid)) {
                                    echo json_encode(array(
                                        "error" => $lang->Croom
                                    ));
                                }   else {
                                    $rooms->$rid = array(
                                        "name" => $name,
                                        "owner" => $id,
                                        "public" => true,
                                        "created" => $date
                                    );
                                    $rooms = json_encode($rooms);
                                    file_put_contents("./../../data/rooms.json", $rooms);
                                    file_put_contents("./../../data/database/$rid.json", json_encode(array("messages" => [], "deleted" => [])));
                                    $user->rooms->$rid = "Admin";
                                    $user = json_encode($user);
                                    file_put_contents("./../../data/users/$id.json", $user);
                                    echo json_encode(array(
                                        "error" => "none"
                                    ));
                                }
                            }   else {
                                $rid = "Main";
                                $symbols = [
                                    "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1",
                                    "2","3","4","5","6","7","8","9"
                                ];
                                while (isset($rooms->$rid)) {
                                    $index = 0;
                                    $rid = "#";
                                    while ($index < 12) {
                                        $rand = rand(0, count($symbols)-1);
                                        $rid .= $symbols[$rand];
                                        $index++;
                                    }
                                }
                                $rooms->$rid = array(
                                    "name" => $name,
                                    "owner" => $id,
                                    "public" => false,
                                    "created" => $date
                                );
                                $rooms = json_encode($rooms);
                                file_put_contents("./../../data/rooms.json", $rooms);
                                file_put_contents("./../../data/database/$rid.json", json_encode(array("messages" => [], "deleted" => [])));
                                $users = file_get_contents("./../../data/users.json");
                                $users = json_decode($users);
                                $users->$id->rooms->$rid = "Admin";
                                $users = json_encode($users);
                                file_put_contents("./../../data/users.json", $users);
                                echo json_encode(array(
                                    "error" => "none"
                                ));
                            }
                        }   else {
                            echo json_encode(array(
                                "error" => $lang->RroomLimit
                            ));
                        }
                    }   else {
                        echo json_encode(array(
                            "error" => $lang->LroomName
                        ));
                    }
                }
                break;
            case "get":
                $database = file_get_contents("./../../data/rooms.json");
                $database = json_decode($database);
                include_once("./../../tools/users/username.php");
                if ($value == "*") {
                    $rooms = array();
                    foreach ($database as $key => $item) {
                        if ($item->public) {
                            array_push($rooms, array(
                                "name" => $item->name,
                                "id" => $key,
                                "owner" => username($item->owner)
                            ));
                        }
                    }
                    echo json_encode(array(
                        "error" => "none",
                        "value" => $rooms
                    ));
                }   else {
                    if (isset($database->$value)) {
                        if ($database->$value->public) {
                            echo json_encode(array(
                                "error" => "none",
                                "value" => array(
                                    "name" => $database->$value->name,
                                    "id" => $value,
                                    "owner" => username($database->$value->owner)
                                )
                            ));
                        }   else {
                            echo json_encode(array(
                                "error" => $lang->NfoundRoom
                            ));
                        }
                    }   else {
                        echo json_encode(array(
                            "error" => $lang->NfoundRoom
                        ));
                    }
                }
                break;
            case "join":
                $value = strtolower($value);
                $database = file_get_contents("./../../data/rooms.json");
                $database = json_decode($database);
                if ($database->$value) {
                    if ($database->$value->public) {
                        $user = file_get_contents("./../../data/users/$id.json");
                        $user = json_decode($user);
                        if (isset($user->rooms->$value)) {
                            echo json_encode(array(
                                "error" => $lang->AmemberOfRoom
                            ));
                        }   else {
                            if (count(get_object_vars($user->rooms)) < 100) {
                                $user->rooms->$value = "Main";
                                $user = json_encode($user);
                                file_put_contents("./../../data/users/$id.json", $user);
                                echo json_encode(array(
                                    "error" => "none",
                                    "value" => ""
                                ));
                            }   else {
                                echo json_encode(array(
                                    "error" => $lang->RroomLimit
                                ));
                            }
                        }
                    }   else {
                        echo json_encode(array(
                            "error" => $lang->NfoundRoom
                        ));
                    }
                }   else {
                    echo json_encode(array(
                        "error" => $lang->NfoundRoom
                    ));
                }
                break;
            default:
                echo json_encode(array(
                    "error" => $lang->IrequestType
                ));
                break;
        }
    }
}
?>