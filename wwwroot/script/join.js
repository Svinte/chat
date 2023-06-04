function Main() {
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText !== "") {
                const response = JSON.parse(this.responseText);
                if (response["error"] == "none") {
                    response["value"].forEach(element => {
                        const h1 = document.createElement("h1");
                        const h2 = document.createElement("h2");
                        h1.innerText = element["name"];
                        h2.innerText = element["owner"];
                        h1.className=JSON.stringify([element["id"],element["owner"]]);
                        h1.onclick = function (e) {
                            const className = JSON.parse(e.target.className);
                            popup([["Join room", function () {
                                const className = this.className; 
                                document.body.removeChild(document.getElementById("popup"));
                                const request = new XMLHttpRequest();
                                request.onreadystatechange = function () {
                                    if (this.readyState == 4 && this.status == 200) {
                                        const response = JSON.parse(this.responseText);
                                        if (response["error"] == "none") {
                                            window.location.href="/";
                                        }   else {
                                            popup(response["error"]);
                                        }
                                    }
                                }
                                request.open("POST", "/request/rooms.php");
                                request.setRequestHeader("Content-type", "application/json");
                                request.send(JSON.stringify({
                                    "id": JSON.parse(localStorage.getItem("user"))[1],
                                    "password": JSON.parse(localStorage.getItem("user"))[2],
                                    "type": "join",
                                    "value": JSON.parse(className)[0]
                                }))
                            }]], false, h1.className);
                        }
                        document.getElementById("sel").appendChild(h1);
                        document.getElementById("sel").appendChild(h2);
                    });
                }   else {
                    popup(response["error"]);
                }
            }
        }
    }
    request.open("POST", "/request/rooms.php");
    request.setRequestHeader("Content-type", "application/json");
    request.send(JSON.stringify({
        "id": JSON.parse(localStorage.getItem("user"))[1],
        "password": JSON.parse(localStorage.getItem("user"))[2],
        "type": "get",
        "value": "*"
    }));
}
window.onclick = function (e) {
    const target = e.target;
    if (document.getElementById("popup")) {
        if (target.nodeName !== "H1") {
            const popup = document.getElementById("popup");
            if (target !== popup && target.parentNode !== popup) {
                document.body.removeChild(popup);
            }
        }
    }
}
Main();