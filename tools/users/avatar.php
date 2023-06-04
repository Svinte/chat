<?php
function avatar($id) {
$database = file_get_contents("./../../data/users/$id.json");
$database = json_decode($database);
if (isset($database->$id->profile)) {
    return $database->$id->profile;
}   else {
    return "https://betonikasa.netlify.app/gallery/logo.webp";
}
}
?>