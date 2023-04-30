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
    <form autocomplete="off" id="form">
        <input type='text' name='value' placeholder='message' id="inp">
        <button type='submit' id="send">Send</button>
    </form>
    <div id="chat">
        <?php
            $date = date("Y-m-d-h-i", time());
            include_once("./data/get.php");
            $deldata = getData("deleted", "deleted");
            $delIndex = count($deldata);
            echo "<script>var lastUser = ['','$date'];var delIndex = $delIndex;</script>";
        ?>
    </div>
    <button id="Userprofile"></button>
    <div id="profile"></div>
    <script async>
        var index = "*";
        if (localStorage.getItem("userName") !== null) {
            document.getElementById("inp").focus();
            window.scrollTo(0, document.body.scrollHeight);
        } else {
            document.location.href="new.php";
        }
        document.getElementById("Userprofile").onclick = function () {
            document.location.href="profile.php";
        }
        function update() {
            const request = new XMLHttpRequest();
            request.onreadystatechange = function (e) {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText !== "") {
                        const response = JSON.parse(this.responseText);
                        const add = response["add"];
                        if (response["remove"].length !== 0) {
                            window.location.reload();
                        }
                        const div = document.getElementById("chat");
                        if (window.scrollY+window.innerHeight > document.body.scrollHeight) {
                            var scroll = true;
                        }   else {
                            var scroll = false;
                        }
                        if (index == "*") {
                            index = 0;
                        }
                        add.forEach(element => {
                            const item = document.createElement("h1");
                            item.innerText=element["Id"];
                            const item2 = document.createElement("h2");
                            item2.innerText=element["Date"];
                            const item3 = document.createElement("h3");
                            item3.className=JSON.stringify([index, element["Id"]]);
                            item3.innerText=element["Value"];
                            if (lastUser[0] !== element["Id"]) {
                                item.style.color=element["Color"];
                                div.appendChild(item);
                                div.appendChild(item2);
                            } else if (lastUser[1] !== element["Date"]) {
                                div.appendChild(item2);
                            }
                            div.appendChild(item3);
                            lastUser = [element["Id"], element["Date"]];
                            index++;
                        });
                        setTimeout(update, 1000);
                        if (scroll == true) {
                            window.scrollTo(0, document.body.scrollHeight);
                        }
                    }
                }
            }
            request.open("POST", "./tools/messages/get.php", true);
            request.setRequestHeader("Content-type", "application/json");
            request.send(JSON.stringify({
                "index": index,
                "delIndex": delIndex
            }));
        }
        update(this.readyState +", "+ this.status);
        const aux = document.createElement("div");
        aux.id="aux";
        window.onauxclick = function(e) {
            if (e.target.nodeName == "H3") {
                const aux_text = document.createElement("h4");
                aux.appendChild(aux_text);
                const deleteMs = document.createElement("h4");
                deleteMs.innerText="Poista";
                aux.appendChild(deleteMs);
                const e_Index = JSON.parse(e.target.className)[0];
                const e_Id = JSON.parse(e.target.className)[1];
                document.body.appendChild(aux);
                aux_text.innerText="Ilmianna "+e_Id;
                aux.style.top = cursor[0]+"px";
                aux.style.left = cursor[1]+"px";
                deleteMs.onclick = function () {
                    const request = new XMLHttpRequest();
                    request.open("POST", "./tools/messages/delete.php");
                    request.setRequestHeader("Content-type", "application/json");
                    request.send(JSON.stringify({
                        Id: JSON.parse(localStorage.getItem("userName"))[1],
                        password: JSON.parse(localStorage.getItem("userName"))[2],
                        index: JSON.parse(target.className)[0]
                    }))
                }
                if (e_Id == JSON.parse(localStorage.getItem("userName"))[0] || moderator) {
                    deleteMs.style.display="block";
                }   else {
                    deleteMs.style.display="none";
                }
                document.onclick = function(e) {
                    if (e.target !== aux) {
                        document.body.removeChild(aux);
                        document.onclick = function () {};
                        aux.innerHTML="";
                    }
                }
            }
        }
        window.oncontextmenu = function (e) {
            e.preventDefault();
        }
        window.onclick = function(e) {
            if (e.target.nodeName == "H1") {
                
            }
        }
        let cursor = [0, 0];
        document.body.onmousemove = function (e) {
            cursor[0] = e.clientY;
            cursor[1] = e.clientX;
        }
        document.getElementById("form").onsubmit = function (e) {
            e.preventDefault();
            const request = new XMLHttpRequest();
            request.open("POST", "./tools/messages/new.php");
            request.setRequestHeader("Content-type", "application/json");
            request.send(JSON.stringify({
                Id: JSON.parse(localStorage.getItem("userName"))[1],
                password: JSON.parse(localStorage.getItem("userName"))[2],
                value: document.getElementById("inp").value
            }));
            document.getElementById("inp").value = null;
        }
        if (JSON.parse(localStorage.getItem("userName"))[3]) {
            var moderator = true;
        }   else {
            var moderator = false;
        }
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
    max-width: 100vw;
    overflow-wrap: break-word;
}
h3:hover {
    background-color: #fff1;
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
#Userprofile {
    position: fixed;
    width: 4rem;
    height: 4rem;
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
#aux {
    position: fixed;
    background-color: #000;
    padding: 1rem;
    border: #fff 2px solid;
    border-radius: 1rem;
}
h4 {
    font-size: 1rem;
    color: #fff;
    font-family: serif;
}
h4:hover {
    color: red;
}
h5 {
    font-size: 1rem;
    color: #fff;
    font-family: serif;
}
</style>