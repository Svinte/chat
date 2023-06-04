const Lname = document.getElementById("Lname"),
    Lpassword = document.getElementById("Lpassword"),
    Sname = document.getElementById("Sname"),
    Spassword = document.getElementById("Spassword"),
    Spassword2 = document.getElementById("Spassword2"),
    Smail = document.getElementById("Smail")
document.getElementById("l").onsubmit = function (e) {
    e.preventDefault();
    if (Lpassword.value == "" || Lname.value == "") {
        popup("Please enter all values");
    }   else {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const response = JSON.parse(this.responseText);
                if (response["error"] == "none") {
                    localStorage.setItem("user", JSON.stringify([
                    response["response"]["name"],
                    response["response"]["id"],
                    response["response"]["password"],
                    response["response"]["role"]
                ]));
                localStorage.setItem("profile", response["response"]["avatar"]);
                document.location.href="/";
                }   else {
                    popup(response["error"]);
                }
            }
        }
        request.open("POST", "request/login.php");
        request.setRequestHeader("Content-type", "application/json");
        request.send(JSON.stringify({
            "name": Lname.value,
            "password": Lpassword.value,
            "lang": navigator.language
        }));
    }
}
document.getElementById("s").onsubmit = function (e) {
    e.preventDefault();
    if (Sname.value == "" || Spassword.value == "" || Spassword2.value == "" || Smail.value == "") {
        popup("Please enter all values");
    }   else {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                const response = JSON.parse(this.responseText);
                if (response["error"] == "none") {
                    localStorage.setItem("user", JSON.stringify([
                    response["response"]["name"],
                    response["response"]["id"],
                    response["response"]["password"],
                    response["response"]["role"]
                ]));
                localStorage.setItem("profile", response["response"]["profile"]);
                document.location.href="/";
                }   else {
                    popup(response["error"]);
                }
            }
        }
        request.open("POST", "request/singin.php");
        request.setRequestHeader("Content-type", "application/json");
        request.send(JSON.stringify({
            "name": Sname.value,
            "password": Spassword.value,
            "lang": navigator.language,
            "mail": Smail.value
        }));
    }
}