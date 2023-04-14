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
    <form action='index.php' method='POST'>
        <input type='text' name='value' placeholder='message' id="inp">
        <button type='submit' id="send">Send</button>
        <input type="hidden" name="password" id="password">
        <input type="hidden" name="id" id="id">
    </form>
    <div id="chat">
        <?php
            $date = date("Y-m-d-h-i", time());
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
                                    "Date" => date("Y-m-d-h-i", time())
                                ];
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
            $lastUser = "";
            $lastTime = "";
            $index = 0;
            foreach ($messages as $key) {
                $user = username($key->Id);
                if ($key->Id !== $lastUser) {
                    echo "<h1>$user</h1>";
                    echo "<h2>$key->Date</h2>";
                } else if ($key->Id == $lastUser && $key->Date !== $lastTime) {
                    echo "<h2>$key->Date</h2>";
                }
                echo "<h3>$key->Value</h3>";
                $lastUser = $key->Id;
                $lastTime = $key->Date;
                $index++;
            }
            echo "<script>var index = $index;</script>";
            if (!isset($_POST["id"])) {
                echo "<h1>Palvelin</h1>";
                echo "<h2>$date</h2>";
                echo "<h3>Tervetuloa mukaan</h3>";
                echo "<script>var lastUser = ['Palvelin','$date']</script>";
            }   else {
                echo "<script>var lastUser = ['$user','$lastTime']</script>";
            }
        ?>
    </div>
    <button id="profile"></button>
    <script>
        //JOs käyttäjä on admin = anna oikeus poistaa viestejä
        document.getElementById("inp").focus();
        window.scrollTo(0, document.body.scrollHeight);
        document.getElementById("password").value = JSON.parse(localStorage.getItem("userName"))[2];
        document.getElementById("id").value = JSON.parse(localStorage.getItem("userName"))[1];
    </script>
    <script async>
        document.getElementById("profile").onclick = function () {
            document.location.href="user.php";
        }
        function update() {
            const request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                try {
                    const response = JSON.parse(this.responseText);
                    const div = document.querySelector("div");
                    if (this.readyState == 4 && this.status == 200) {
                        if (window.scrollY+window.innerHeight > document.body.scrollHeight) {
                            var scroll = true;
                        }   else {
                            var scroll = false;
                        }
                        var lenght = 0;
                        response.forEach(element => {
                            index++;
                            lenght++;
                            const item = document.createElement("h1");
                            item.innerText=element["Id"];
                            const item2 = document.createElement("h2");
                            item2.innerText=element["Date"];
                            const item3 = document.createElement("h3");
                            item3.innerText=element["Value"];
                            if (lastUser[0] !== element["Id"]) {
                                div.appendChild(item);
                                div.appendChild(item2);
                            } else if (lastUser[1] !== element["Date"]) {
                                div.appendChild(item2);
                            }
                            div.appendChild(item3);
                        });
                        setTimeout(update, 1000);
                        if (scroll == true && lenght > 0) {
                            window.scrollTo(0, document.body.scrollHeight);
                        }
                    }
                } catch {}
            }
            request.open("GET", "answer.php?index="+index, true);
            request.setRequestHeader("Content-type", "application/json");
            request.send();
        }
        update();
    </script>
</body>
</html>
<style>
body {
    background-color: black;
    color: white;
    padding-bottom: 6vh;
}
h1 {
    display: inline;
    font-size: 1.2rem;
    margin: 0;
    font-family: serif;
    color: blue;
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
form {
    position: fixed;
    bottom: 2vh;
    display: block;
    margin: auto;
    left: 0;
    right: 0;
    width: max-content;
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
#inp {
    width: calc(95vw - 5rem);
    background-color: #111;
    color: #fff;
    padding: 0.5rem;
    border-radius: 5px;
    border: #fff solid 1.5px;
    outline: none;
    box-sizing: border-box;
    margin: 0;
    font-size: 0.9rem;
    font-family: monospace;
}
#send {
    width: 5vw;
    min-width: fit-content;
    font-size: 1rem;
    color: #fff;
    background-color: #111;
    margin: 0;
    height: 35px;
    font-family: monospace;
    border-radius: 5px;
    border: 1.5px solid white;
}
</style>