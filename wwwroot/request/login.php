<?php
$database = file_get_contents("php://input");
$database = json_decode($database);
include_once("./../../tools/lang/get.php");
$lang = getLang($database->lang);
if (isset($database->name) && isset($database->password)) {
    $name = $database->name;
    $password = $database->password;
    $database = file_get_contents("./../../data/users.json");
    $database = json_decode($database);
    foreach ($database as $key => $val) {
        if ($val == $name) {
            $id = $key;
        }
    }
    if (isset($id)) {
        $database = file_get_contents("./../../data/users/$id.json");
        $database = json_decode($database);
        include_once("./../../tools/users/LogIn.php");
        if (LogIn($id, $password)) {
            include_once("./../../tools/users/username.php");
            $user = username($id);
            $role = $database->role;
            $avatar = $database->avatar;
            error_log($user);
            echo json_encode(array(
                "error" => "none",
                "response" => array(
                    "id" => $id,
                    "name" => $name,
                    "password" => $password,
                    "role" => $role,
                    "avatar" => $avatar
                )
            ));
        }   else {
            if (!isset($database->$id)) {
                echo json_encode(array(
                    "error" => $lang->Iuser
                    ));
            }   elseif ($database->password !== $password) {
                echo json_encode(array(
                    "error" => $lang->Ipassword
                ));
            }   else {
                echo json_encode(array(
                    "error" => $lang->Elogin
                ));
            }
        }
    }   else {
        echo json_encode(array(
            "error" => $lang->Iuser
        ));
    }
}   else {
    echo json_encode(array(
        "error" => $lang->Iuser
    ));
}
?>