<?php
$post = file_get_contents("php://input");
$post = json_decode($post);
if (isset($post->value) && isset($post->type) && isset($post->id) && isset($post->password)) {
    $id = $post->id;
    $password = $post->password;
    include_once("./../../tools/users/LogIn.php");
    if (LogIn($id, $password)) {
        include_once("./../../tools/users/lang.php");
        $lang = userLang($id, $password);
        file_put_contents("./../../data/video/$id.json", json_encode($post->value));
    }   else {
        echo json_encode(array(
            "error" => "Invalid userdata. Log in again to fix problem."
        ));
    }
}   else {   
    echo json_encode(array(
        "error" => "The system did not send the necessary information, or it is incomplete."
    ));
}
?>