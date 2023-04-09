<?php
    $request = $_POST;
    $database = file_exists("database.json");
    $database = json_decode($database);
    $lenght = 0;
    if ($request < $lenght) {
        echo "<script>alert('sus')</script>";
    }
    else {
        echo "<script>alert('Eisus')</script>";
    }
?>