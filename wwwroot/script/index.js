if (localStorage.getItem("user") == null) {
    window.location.href="auth.html";
}
if (sessionStorage.getItem("room") == null) {
    var room = "*";    
}   else {
    var room = JSON.parse(sessionStorage.getItem("room"))[0];
}
var date = new Date;
var lastUser = ["",date];
var role;
if (sessionStorage.getItem("room") == null) {
    document.title = "Chat | Home";
    document.getElementById("roomName").innerText="Home";
}   else {
    document.title = "Chat | "+JSON.parse(sessionStorage.getItem("room"))[1];
    document.getElementById("roomName").innerText=JSON.parse(sessionStorage.getItem("room"))[1];
}
settings();
function deleteMs() {
    const target = this;
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText !== "") {
                const response = JSON.parse(this.responseText);
                if (response["error"] !== "none") {
                    popup(response["error"]);
                }
            }
        }
    }
    request.open("POST", "request/delete.php");
    request.setRequestHeader("Content-type", "application/json");
    request.send(JSON.stringify({
        "Id": JSON.parse(localStorage.getItem("user"))[1],
        "password": JSON.parse(localStorage.getItem("user"))[2],
        "index": JSON.parse(target.className)[0],
        "room": room,
        "lang": JSON.parse(sessionStorage.getItem("settings"))["lang"]
    }))
}
function createRoom() {
    window.location.href="create.html";
}
function joinRoom() {
    window.location.href="join.html";
}
function settings() {
    function rooms(newRooms) {
        var selector = document.getElementById("selector");
        newRooms.forEach(element => {
            if (!document.getElementById(element["id"])) {
                const h1 = document.createElement("h1");
                h1.id=element["id"];
                h1.innerText=element["name"];
                selector.appendChild(h1);
            }
        });
    }
    const request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (JSON.parse(this.responseText)["error"] == "none") {
                sessionStorage.setItem("settings", JSON.stringify(JSON.parse(this.responseText)["response"]));
                sessionStorage.setItem("delIndex", JSON.stringify(JSON.parse(this.responseText)["delIndex"]));
                localStorage.setItem("profile", JSON.stringify(JSON.parse(this.responseText)["avatar"]));
                rooms(JSON.parse(this.responseText)["rooms"]);
                main();
            }   else {
                popup(JSON.parse(this.responseText)["error"] || "Server side error");
            }
        }
    }
    request.open("POST", "request/settings.php");
    request.setRequestHeader("Content-type", "application/json");
    request.send(JSON.stringify({
        "id": JSON.parse(localStorage.getItem("user"))[1],
        "password": JSON.parse(localStorage.getItem("user"))[2],
        "lang":  navigator.language,
        "type": "get",
        "room": room
    }))
}
function main() {
    var settings = JSON.parse(sessionStorage.getItem("settings"));
    var index = 0;
    document.getElementById("Userprofile").onclick = function () {
        document.location.href="profile.html";
    }
    document.getElementById("Userprofile").style.backgroundImage = "url("+localStorage.getItem("profile")+")";
    function update() {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function (e) {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText !== "") {
                    const response = JSON.parse(this.responseText);
                    if (response["error"] == "none") {
                        const add = response["add"];
                        if (response["remove"].length !== 0) {
                            window.location.reload();
                        }
                        if (!moderator) {
                            if (response["role"] == "Admin") {
                                role = response["role"];
                            }
                        }
                        const chat = document.getElementById("chat");
                        if (window.scrollY+window.innerHeight > document.body.scrollHeight) {
                            var scroll = true;
                        }   else {
                            var scroll = false;
                        }
                        add.forEach(element => {
                            var date = new Date(element["Date"]);
                            const div = document.createElement("div");
                            div.className = JSON.stringify([index, element["Id"]]);
                            const item = document.createElement("h1");
                            item.innerText=element["Id"];
                            const item2 = document.createElement("h2");
                            item2.innerText=date.getDate()+"."+(date.getMonth()+1)+"."+date.getFullYear()+":"+date.getHours()+"."+date.getMinutes();
                            const item4 = document.createElement("img");
                            item4.className = "avatar";
                            item4.src = element["Profile"];
                            var type = "txt";
                            try {
                                const url = new URL(element["Value"]);
                                if (element["Value"].toLowerCase().match(/\.(jpeg|jpg|gif|png|webp)$/)) {
                                    var item3 = document.createElement("img");
                                    item3.src=url;
                                    item3.className="img";
                                    item3.alt=element["Value"];
                                    type = "img";
                                    item3.onload = function() {
                                        if (scroll == true) {
                                            window.scrollTo(0, document.body.scrollHeight);
                                        }
                                    }
                                }   else {
                                    type = "link";
                                }
                            } catch (error) {}
                            if (type == "txt") {
                                var item3 = document.createElement("h3"); 
                                item3.innerText=element["Value"];
                            }   else if (type == "link") {
                                var item3 = document.createElement("a");
                                item3.innerText=element["Value"];
                                item3.href=element["Value"];
                                item3.target="_blank";
                            }
                            if (lastUser[0] !== element["Id"]) {
                                chat.appendChild(item4);
                                item.style.color=element["Color"];
                                div.appendChild(item);
                            }
                            div.appendChild(item2);
                            div.appendChild(item3);
                            chat.appendChild(div);
                            lastUser = [element["Id"], element["Date"]];
                            index++;
                        });
                        if (room !== "*") {
                            setTimeout(update, settings["update"]);
                        }
                        if (scroll == true) {
                            window.scrollTo(0, document.body.scrollHeight);
                        }
                    }   else {
                        popup(response["error"]);
                    }
                }
            }
        }
        request.open("POST", "request/get.php", true);
        request.setRequestHeader("Content-type", "application/json");
        request.send(JSON.stringify({
            "index": index,
            "delIndex": sessionStorage.getItem("delIndex"),
            "room": room,
            "lang": JSON.parse(sessionStorage.getItem("settings"))["lang"],
            "id": JSON.parse(localStorage.getItem("user"))[1],
            "password": JSON.parse(localStorage.getItem("user"))[2]
        }));
    }
    update();
    window.onauxclick = function(e) {
        if (e.target.parentNode.parentNode == chat || e.target.parentNode == chat) {
            if (e.target.parentNode == chat) {
                var target = e.target;
            }   else {
                var target = e.target.parentNode;
            }
            if (target.className !== "avatar") {
                const e_Id = JSON.parse(target.className)[1];
                popup([["Delete", deleteMs],["Report "+e_Id]], false, target.className);
            }
        }
    }
    window.oncontextmenu = function (e) {
        e.preventDefault();
    }
    document.getElementById("form").onsubmit = function (e) {
        e.preventDefault();
        if (document.getElementById("inp").value !== "") {
            const request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText !== "") {
                        console.log(this.responseText);
                        const response = JSON.parse(this.responseText);
                        if (response["error"] !== "none") {
                            popup(response["error"]);
                        }
                    }
                }
            }
            request.open("POST", "request/new.php");
            request.setRequestHeader("Content-type", "application/json");
            request.send(JSON.stringify({
                "Id": JSON.parse(localStorage.getItem("user"))[1],
                "password": JSON.parse(localStorage.getItem("user"))[2],
                "value": document.getElementById("inp").value,
                "lang": JSON.parse(sessionStorage.getItem("settings"))["lang"],
                "room": room
            }));
            document.getElementById("inp").value = null;
        }
    }
    if (localStorage.getItem("user") !== "") {
        if (JSON.parse(localStorage.getItem("user"))[3]) {
            var moderator = true;
        }   else {
            var moderator = false;
        }
    }
    window.onclick = function (e) {
        const target = e.target;
        if (target == document.getElementById("call")) {
            window.location.href="call.html";
        }   else if (target == document.getElementById("add")) {
            popup([["Join room",joinRoom],["Create room",createRoom]], false);
        }   else {
            if (document.getElementById("addRoom")) {
                const addRoom = document.getElementById("addRoom");
                if (target.parentNode !== addRoom && target !== addRoom) {
                    document.body.removeChild(addRoom);
                }
            }
        }
        var selector = document.getElementById("selector");
        if (target.parentNode == selector && target.nodeName == "H1") {
            if (room !== target.id) {
                sessionStorage.setItem("room", JSON.stringify([target.id, target.innerText]));
                window.location.reload();
            }
        }
        if (target !== document.getElementById("popup") && target !== document.getElementById("add")) {
            if (document.getElementById("popup")) {
                document.body.removeChild(document.getElementById("popup"));
            }
        }
    }   
    document.getElementById("inp").focus();
    window.scrollTo(0, document.body.scrollHeight);
}