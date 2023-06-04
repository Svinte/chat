<?php
    include_once("./../../tools/users/LogIn.php");
    include_once("./../../tools/lang/get.php");
    if (file_get_contents("php://input") !== "") {
        $post = json_decode(file_get_contents("php://input"));
        if (isset($post->lang)) {
            $lang = getLang($post->lang);
        } else {
            $lang = getLang();
        }
        if (isset($post->Id) && isset($post->password) && isset($post->value) && isset($post->room)) {
            $Id = strval($post->Id);
            $password = $post->password;
            $value = $post->value;
            $room = $post->room;
            if ($value !== "") {
                if (LogIn($Id, $password) == "true") {
                    include_once("./../../tools/messages/get.php");
                    $messages = Messsages($Id, $password, $room, $post->lang);
                    if ($messages["error"] == "none") {
                        $messages = $messages["messages"];
                        $date = date("D M d Y H:i:s O");
                        $new = [
                            "Id" => $Id,
                            "Value" => $value,
                            "Date" => $date
                        ];
                        array_push($messages->messages, $new);
                        $messages = json_encode($messages);
                        file_put_contents("./../../data/database/$room.json", $messages);
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
        }   else {
            echo json_encode(array(
                "error" => $lang->Mdata
            ));
        }
    }
?>