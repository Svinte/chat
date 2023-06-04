function popup(text, error = true, target = null) {
    if (document.getElementById("popup")) {
        document.body.removeChild(document.getElementById("popup"));
    }
    const popup = document.createElement("div");
    popup.id = "popup";
    document.body.appendChild(popup);
    if (error) {
        const h1 = document.createElement("h1");
        h1.innerText = text;
        popup.appendChild(h1);
        const ok = document.createElement("button");
        ok.innerText = "Ok";
        popup.appendChild(ok);
        ok.onclick = function okClick() {
            document.body.removeChild(popup);
            ok.removeEventListener("click", okClick);
        }
    }   else {
        text.forEach(element => {
            const h4 = document.createElement("h4");
            h4.innerText = element[0];
            h4.className=target;
            h4.onclick = element[1];
            popup.appendChild(h4);
        });
        var top = 0;
        var left = 0;
        if (cursor[0]+popup.clientHeight > window.innerHeight) {
            top = popup.clientHeight;
        }
        if (cursor[1]+popup.clientWidth > window.innerWidth) {
            left = popup.clientWidth;
        }
        popup.style.top = cursor[0]-top+"px";
        popup.style.left = cursor[1]-left+"px";
    }
}
function add(isPublic) {
    const name = prompt("Room name");
    if (name) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const response = JSON.parse(this.responseText);
                if (response["error"] == "none") {
                    window.location.href="/";
                }   else {
                    popup(response["error"], true);
                }
            }
        }
        request.open("POST", "/request/rooms.php");
        request.setRequestHeader("Content-type", "application/json");
        request.send(JSON.stringify({
            type: "create",
            id: JSON.parse(localStorage.getItem("user"))[1],
            password: JSON.parse(localStorage.getItem("user"))[2],
            value: {
                name: name,
                public: isPublic
            }
        }));
    }
}
document.getElementById("Public").onclick = function () {add(true)};
document.getElementById("Private").onclick = function () {add(false)};