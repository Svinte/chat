<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>chat</title>
    <link rel="shortcut icon" href="https://betonikasa.netlify.app/gallery/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php
        $date = date("Y-m-d-h-m", time());
        if (isset($_POST["id"])) {
            $password = $_POST["password"];
            $id = $_POST["id"];
            $database = file_get_contents("database.json");
            $database = json_decode($database);
            foreach ($database->users as $key) {
                if ($key[1] == $id) {
                    if ($key[2] == $password) {
                        if (isset($_POST["value"])) {
                            $new = [
                                "Id" => $_POST["id"],
                                "Value" => $_POST["value"],
                                "Date" => date("Y-m-d-h-m", time())
                            ];
                            echo $_POST["value"];
                            $database = file_get_contents("database.json");
                            $database = json_decode($database);
                            array_push($database->messages, $new);
                            $database = json_encode($database);
                            file_put_contents("database.json", $database);
                        }
                    }   else {
                        echo "<script>alert(`Väärä salasana`)</script>";
                    }
                }
            }
        }
    ?>
    <form action='index.php' method='POST'>
        <input type='text' name='value' placeholder='message' required>
        <button type='submit'>Send</button>
        <input type="hidden" name="password" id="password">
        <input type="hidden" name="id" id="id">
    </form>
    <div>
        <?php
            $database = file_get_contents("database.json");
            $database = json_decode($database);
            $users = $database->users;
            $messages = $database->messages;
            function username($userId) {
                $database = file_get_contents("database.json");
                $database = json_decode($database);
                $users = $database->users;
                foreach ($users as $key) {
                    if (array_values($key)[1] == $userId) {
                        return array_values($key)[0];
                    }
                }
                return "Deleted user";
            }
            $lastUser = array_values($messages)[0];
            $lastTime = array_values($messages)[0];
            $index = 0;
            foreach ($messages as $key) {
                $user = username($key->Id);
                if ($key->Id == $lastUser) {
                    if (date_format("Y-m-d-h", $key->Date) != date_format($lastTime)) {
                        echo "<h1>$user</h1>";
                        echo "<h2>$key->Date</h2>";
                    }
                }   else {
                    echo "<h1>$user</h1>";
                }
                echo "<h3>$key->Value</h3>";
                $index++;
            }
            echo "<script>var index = $index;</script>";
            if (!isset($_POST["id"])) {
                echo "<h1>Palvelin</h1>";
                echo "<h2>$date</h2>";
                echo "<h3>Tervetuloa mukaan</h3>";
            }
        ?>
    </div>
    <button id="profile"></button>
    <script>
        //JOs käyttäjä on admin = anna oikeus poistaa viestejä
        window.scrollTo(0, document.body.scrollHeight);
        document.getElementById("password").value = JSON.parse(localStorage.getItem("userName"))[1];
        document.getElementById("id").value = JSON.parse(localStorage.getItem("userName"))[0];
        function update() {
            const request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText)
                }
            }
            request.open("GET", "answer.php", true);
            request.send(index);
        }
        update();
    </script>
</body>
</html>
<style>
body {
    background-color: black;
    color: white;
}
h1 {
    display: inline;
    font-size: 1.2rem;
    margin: 0;
    font-family: serif;
}
h2 {
    display: inline;
    font-size: 0.9rem;
    margin: 0;
    margin-left: 1rem;
    font-family: monospace;
}
h3 {
    margin: 0;
    margin-bottom: 1rem;
    font-size: 1rem;
    margin-left: 1rem;
    font-family: arial;
    display: block;
}
h3:last-child {
    margin-bottom: 7vh;
}
form {
    position: fixed;
    bottom: 2vh;
}
#profile {
    position: fixed;
    width: 5vw;
    height: 5vw;
    border: 2px solid gray;
    border-radius: 100%;
    top: 1vh;
    right: 1vw;
}
</style>