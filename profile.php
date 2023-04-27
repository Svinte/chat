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
    $password = $_POST["password"];
    $database = file_get_contents("database.json");
    $database = json_decode($database);
    $changed = "Vialliset käyttäjätiedot";
    if ($name !== $oldName) {
        $index = 0;
        foreach ($database->users as $key) {
            if ($key[0] == $oldName) {
                if (strtoupper($key[1]) == strtoupper($id)) {
                    if ($password == $key[2]) {
                        $database->users[$index][0] = $name;
                        $database = json_encode($database);
                        file_put_contents("database.json", $database);
                        $changed = "Käyttäjänimi vaihdettiin onnistuneesti";
                        echo "<script>localStorage.setItem('userName', JSON.stringify(['$name', '$key[1]', '$key[2]']))</script>";
                        break;
                    }   else {
                        break;
                    }
                }   else {
                    $changed = "Käyttäjänimi on jo olemassa";
                    break;
                }
            }
            $index++;
        }
    }   else {
        $changed = "Aseta uusi käyttäjänimi";
    }
    echo $changed;
}
?>
<body>
    <form action="user.php" method="POST" autocomplete="off">
        <input type="text" placeholder="Käyttäjänimi" required id="name" name="name">
        <input type="submit" value="Vaihda" onclick="local">
        <input type="hidden" id="id" name="id">
        <input type="hidden" id="oldName" name="oldName">
        <input type="hidden" name="password" id="pass">
    </form>
</body>
<script>
if (localStorage.getItem("userName") == null) {
    document.location.href="new.php";
} else {
    document.getElementById("name").value = JSON.parse(localStorage.getItem("userName"))[0];
    document.getElementById("oldName").value = JSON.parse(localStorage.getItem("userName"))[0];
    document.getElementById("id").value = JSON.parse(localStorage.getItem("userName"))[1];
    document.getElementById("pass").value = JSON.parse(localStorage.getItem("userName"))[2];
    function local() {
        if (document.getElementById("name").value !== "") {
            localStorage.setItem("userName",JSON.parse([document.getElementById("name").value, id]))
        }
    }
}
</script>
</html>