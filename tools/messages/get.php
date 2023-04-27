<?php
include_once("./../roles/color.php");
include_once("./../users/username.php");
if (file_get_contents("php://input") !== "") { 
    $post = json_decode(file_get_contents("php://input"));
    include_once("./../../data/get.php");
    $users = getData("roles", "*");
    include_once("./../../data/get.php");
    $database = getData("database", "*");
    $index = $post->index;
    if ($index == "*") {
        $index = 0;
    }
    $loop = $index;
    $data = [];
    while ($loop < count($database->messages)) {
        $item = $database->messages[$loop];
        $item->Color=color($item->Id);
        $item->Id=username($item->Id);
        array_push($data, $item);
        $loop++;
    }
    include_once("./../../data/get.php");
    $deletedMs = getData("deleted", "deleted");
    $delData = [];
    $delIndex = $post->delIndex;
    if ($delIndex < count($deletedMs)) {
        $index = 0;
        foreach ($deletedMs as $key) {
            if ($index >= $delIndex) {
                include_once("./../users/username.php");
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
    echo json_encode(array (
        "add" => $data,
        "remove" => $delData
    ));
}
?>