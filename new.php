<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User</title>
    <link rel="shortcut icon" href="https://betonikasa.netlify.app/gallery/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php
        if (isset($_POST["name"]) && isset($_POST["password"]) && isset($_POST["password"])) {
            $password = $_POST["password"];
            $user = $_POST["name"];
            if ($password !== $_POST["password2"]) {
                echo "<script>document.location.href='new.php?error=Väärä+salasana'</script>";
            }   else {
                function nameexists() {
                    $database = file_get_contents("database.json");
                    $database = json_decode($database);
                    foreach ($database->users as $key) {
                        if ($key[0] == $_POST["name"]) {
                            return true;
                            break;
                        }
                    }
                    return false;
                }
                if (nameexists()) {
                    echo "<script>document.location.href='new.php?error=Käyttäjänimi+on+jo+käytössä'</script>";
                }   else {
                    function idexists($newId) {
                        $database = file_get_contents("database.json");
                        $database = json_decode($database);
                        foreach ($database->users as $key) {
                            if ($key[1] == $newId) {
                                return true;
                                break;
                            }
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
                            $rand = rand(0, count($symbols));
                            $id .= $symbols[$rand];
                            $index++;
                        }
                    }
                    $database = file_get_contents("database.json");
                    $database = json_decode($database);
                    array_push($database->users, [$user, $id, $password]);
                    $database = json_encode($database);
                    file_put_contents("database.json", $database);
                    echo "
                    <h1>Käytäjä luotu</h1>
                    <script>localStorage.setItem('userName', JSON.stringify(['$user', '$id', '$password']))</script>
                    ";
                }
            }
        }   else {
            echo '
            <form action="new.php" method="POST">
                <h1>Luo uusi käyttäjä</h1>
                <input type="text" placeholder="Käyttäjänimi" name="name" require minlength="4" maxlength="20">
                <input type="text" placeholder="Salasana" name="password" require minlength="4" maxlength="20">
                <input type="text" placeholder="Salasana uudelleen" name="password2" require minlength="4" maxlength="20">
                <button type="submit">Luo käyttäjä</button>
            </form>
            ';
            if (isset($_GET["error"])) {
                $error = $_GET["error"];
                echo "<h1>Virhe: $error</h1>";
            }
        }
    ?>
</body>
</html>
<style>
body {
    background-color: black;
}
form {
    width: min-content;
    display: block;
    margin: 30vh auto auto auto;
    font-family: arial;
}
input {
    display: block;
    margin: 0.7rem;
    background-color: black;
    border: 1px solid white;
    color: white;
    outline: none;
    max-width: 90vw;
    width: 300px;
    box-sizing:border-box;
    padding: 0.4rem;
}
input:focus-visible {
    border: 2px solid white;
}
h1 {
    text-align: center;
    font-size: 1.5rem;
    color: white;
}
button {
    background-color: transparent;
    border: none;
    color: white;
    font-size: 0.8rem;
    float: right;
}
button:hover {
    color: blue;
}
</style>