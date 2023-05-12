<?php
$database = file_get_contents("php://input");
$database = json_decode($database);
include_once("./../../tools/lang/get.php");
$lang = getLang($database->lang);
if (isset($database->name) && isset($database->password) && isset($database->mail)) {
    $name = $database->name;
    $password = $database->password;
    $mail = $database->mail;
    if (31 > strlen($name) && strlen($name) > 3) {
        if (31 > strlen($password) && strlen($name) > 3) {
            $database = file_get_contents("./../../data/users.json");
            $database = json_decode($database);
            foreach ($database as $key => $value) {
                if (strtolower($value->name) == strtolower($name)) {
                    echo json_encode(array(
                        "error" => $lang->Cname
                    ));
                    $taken = true;
                    break;
                }
            }
            if (!isset($taken)) {
                function idexists($newId) {
                    $database = file_get_contents("./../../data/users.json");
                    $database = json_decode($database);
                    if (isset($database->$newId)) {
                        return true;
                    }
                    return false;
                }
                $id = "_palvelin";
                $symbols = [
                    "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1",
                    "2","3","4","5","6","7","8","9"
                ];
                while (idexists($id)) {
                    $index = 0;
                    $id = "_";
                    while ($index < 12) {
                        $rand = rand(0, count($symbols)-1);
                        $id .= $symbols[$rand];
                        $index++;
                    }
                }
                $database = file_get_contents("./../../data/login.json");
                $database = json_decode($database);
                $database->$id = array(
                    "name" => $name,
                    "password" => $password,
                );
                $database = json_encode($database);
                file_put_contents("./../../data/login.json", $database);
                //mail($mail, $lang->EsingInHeader, $lang->EsingInBody);
                echo json_encode(array(
                    "error" => "none",
                    "response" => array(
                        $lang->Cemail
                    )
                ));
            }
        }   else {
            echo json_encode(array(
                "error" => $lang->Lpassword
            ));
        }
    }   else {
        echo json_encode(array(
            "error" => $lang->Lname
        ));
    }
}   else {
    echo json_encode(array(
        "error" => $lang->Mdata
    ));
}
?>