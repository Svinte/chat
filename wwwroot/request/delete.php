<?php
    if (file_get_contents("php://input") !== "") {
        $post = json_decode(file_get_contents("php://input"));
        include_once("./../../tools/users/LogIn.php");
        include_once("./../../tools/messages/sender.php");
        include_once("./../../tools/roles/law.php");
        include_once("./../../tools/users/role.php");
        include_once("./../../tools/lang/get.php");
        if (isset($post->lang)) {
            $lang = getLang($post->lang);
        }   else {
            $lang = getLang();
        }
        if (isset($post->Id) && isset($post->password) && isset($post->index)
        && isset($post->room) && isset($post->lang)) {
            $Id = $post->Id;
            $password = $post->password;
            $index = $post->index;
            $room = $post->room;
            if (LogIn($Id, $password)) {
                $database = json_decode(file_get_contents("./../../data/database/$room.json"));
                function delete($room, $index, $database) {
                    $item = $database->messages[$index];
                    array_splice($database->messages, $index, 1);
                    $new = array (
                        "Deleted" => date("Y-m-d-h-i-S"),
                        "Id" => $item->Id,
                        "Value" => $item->Value,
                        "Date" => $item->Date
                    );
                    array_push($database->deleted, $new);
                    $database = json_encode($database);
                    file_put_contents("./../../data/database/$room.json", $database);
                }
                $role = roleOfUser($Id, true, $room);
                $Srole = roleOfUser($Id, true, $room);
                if (law($role, "Moderate")) {
                    delete($room, $index, $database);
                }   elseif (law($Srole, "Moderate")) {
                    delete($room, $index, $database);
                }   elseif (sender($room, $index) == $Id) {
                    delete($room, $index, $database);
                }   else {
                    echo json_encode(array(
                        "error" => $lang->Ipremission
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
    }
?>