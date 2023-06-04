<?php
if (file_get_contents("php://input") !== "") {
    $post = json_decode(file_get_contents("php://input"));
    include_once("./../../tools/lang/get.php");
    if (isset($post->lang)) {
        $lang = getLang($post->lang);
    }   else {
        $lang = getLang();
    }
    if (isset($post->room) && isset($post->id) && isset($post->password) &&
    isset($post->index) && isset($post->delIndex)) {
        if ($post->room == "*") {
            echo json_encode(array (
                "error" => "none",
                "add" => array(),
                "remove" => array()
            ));
        }   else {
            include_once("./../../tools/roles/color.php");
            include_once("./../../tools/users/username.php");
            include_once("./../../tools/users/avatar.php");
            include_once("./../../tools/users/LogIn.php");
            $index = $post->index;
            $room = $post->room;
            $id = $post->id;
            $password = $post->password;
            if (LogIn($id, $password)) {
                include_once("./../../tools/messages/get.php");
                $database = Messsages($id, $password, $room, $post->lang);
                if ($database["error"] == "none") {
                    $messages = $database["messages"]->messages;
                    $loop = $index;
                    $data = [];
                    $lenght = count($messages);
                    while ($loop < $lenght) {
                        $item = $messages[$loop];
                        $item->Profile=avatar($item->Id);
                        $item->Color=color($item->Id, $room);
                        $item->Id=username($item->Id);
                        array_push($data, $item);
                        $loop++;
                    }
                    $deletedMs = $database["messages"]->deleted;
                    $delData = [];
                    $delIndex = $post->delIndex;
                    if ($delIndex < count($deletedMs)) {
                        $index = 0;
                        foreach ($deletedMs as $key) {
                            if ($index >= $delIndex) {
                                array_push($delData, array(
                                    "Deleted" => $key->Deleted,
                                    "Id" => username($key->Id),
                                    "Value" => $key->Value,
                                    "Date" => $key->Date
                                ));
                            }
                            $index++;
                        }
                    }
                    include_once("./../../tools/users/role.php");
                    $role = roleOfUser($id, true, $room);
                    echo json_encode(array (
                        "error" => "none",
                        "add" => $data,
                        "remove" => $delData,
                        "role" => $role
                    ));
                }   else {
                    echo json_encode(array(
                        "error" => $database["error"]
                    ));
                }
            }   else {
                echo json_encode(array(
                    "error" => $lang->Iuser
                ));
            }
        }
    }   else {
        echo json_encode(array(
            "error" => $lang->Mdata
        ));
    }
}
?>