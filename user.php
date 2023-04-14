<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="shortcut icon" href="https://betonikasa.netlify.app/gallery/favicon.ico" type="image/x-icon">
</head>
<?php
if (isset($_POST["name"])) {
    $oldName = $_POST["oldName"];
    $name = $_POST["name"];
    $id = $_POST["id"];
    $database = file_get_contents("database.json");
    $database = json_decode($database);
    foreach ($database->users as $key) {
        if ($key[0] == $oldName) {
            if ($key[1] == $id) {
                $key[0] == $name;
                array_push($database->users, $database->users);
                $database = json_encode($database);
                file_put_contents("database.json", $database);
                echo "<script>window.close()</script>";
            }   else {
                echo "Käyttäjänimi on jo käytössä";
            }
        }
    }
}
?>
<body>
    <form action="user.php" method="post">
        <input type="text" placeholder="Käyttäjänimi" required id="name">
        <input type="submit" value="Vaihda" onclick="local">
        <input type="text" style="display: none;" id="id" disabled>
        <input type="text" style="display: none;" id="oldName" disabled>
    </form>
</body>
<script>
if (localStorage.getItem("userName") == null) {
    document.location.href="new.php";
} else {
    const id = JSON.parse(localStorage.getItem("userName"))[1];
    document.getElementById("name").value == JSON.parse(localStorage.getItem("userName"))[0];
    document.getElementById("oldName").value == JSON.parse(localStorage.getItem("userName"))[0];
    document.getElementById("id").value == id;
    function local() {
        if (document.getElementById("name").value !== "") {
            localStorage.setItem("userName",JSON.parse([document.getElementById("name").value, id]))
        }
    }
}
</script>
</html>